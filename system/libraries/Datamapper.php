<?php

/**
 * Data Mapper Class
 *
 * Transforms database tables into objects.
 *
 * @licence 	MIT Licence
 * @category	Models
 * @author  	Simon Stenhouse
 * @link    	http://stensi.com
 * @version 	1.6.0
 */

// --------------------------------------------------------------------------

/**
 * Autoload
 *
 * Autoloads object classes that are used with DataMapper.
 */
spl_autoload_register('DataMapper::autoload');

// --------------------------------------------------------------------------

/**
 * Data Mapper Class
 */
class DataMapper {

	static $config = array();
	static $common = array();

	var $error;
	var $stored;
	var $prefix = '';
	var $join_prefix = '';
	var $table = '';
	var $model = '';
	var $error_prefix = '';
	var $error_suffix = '';
	var $created_field = '';
	var $updated_field = '';
	var $auto_transaction = FALSE;
	var $auto_populate_has_many = FALSE;
	var $auto_populate_has_one = FALSE;
	var $valid = FALSE;
	var $validated = FALSE;
	var $local_time = FALSE;
	var $unix_timestamp = FALSE;
	var $fields = array();
	var $all = array();
	var $parent = array();
	var $validation = array();
	var $has_many = array();
	var $has_one = array();
	var $query_related = array();

	/**
	 * Constructor
	 *
	 * Initialize DataMapper.
	 */
	function DataMapper()
	{
		$this->_assign_libraries();

		$this->_load_languages();

		$this->_load_helpers();

		// Determine model name
		if (empty($this->model))
		{
			$this->model = singular(get_class($this));
		}

		// Load stored config settings by reference
		foreach (array_keys(DataMapper::$config) as $key)
		{
			// Only if they're not already set
			if (empty($this->{$key}))
			{
				$this->{$key} =& DataMapper::$config[$key];
			}
		}

		// Load model settings if not in common storage
		if ( ! array_key_exists($this->model, DataMapper::$common))
		{
			// If model is 'datamapper' then this is the initial autoload by CodeIgniter
			if ($this->model == 'datamapper')
			{
				// Load config settings
				$this->config->load('datamapper', TRUE, TRUE);

				// Get and store config settings
				DataMapper::$config = $this->config->item('datamapper');

				return;
			}

			// Determine table name
			if (empty($this->table))
			{
				$this->table = plural(get_class($this));
			}

			// Add prefix to table
			$this->table = $this->prefix . $this->table;

			// Convert validation into associative array by field name
			$associative_validation = array();

			foreach ($this->validation as $validation)
			{
				// Populate associative validation array
				$associative_validation[$validation['field']] = $validation;
			}

			$this->validation = $associative_validation;

			// Get and store the table's field names and meta data
			$fields = $this->db->field_data($this->table);

			// Store only the field names and ensure validation list includes all fields
			foreach ($fields as $field)
			{
				// Populate fields array
				$this->fields[] = $field->name;

				// Add validation if current field has none
				if ( ! array_key_exists($field->name, $this->validation))
				{
					$this->validation[$field->name] = array('field' => $field->name, 'label' => '', 'rules' => array());
				}
			}

			// Store common model settings
			DataMapper::$common[$this->model]['table'] = $this->table;
			DataMapper::$common[$this->model]['fields'] = $this->fields;
			DataMapper::$common[$this->model]['validation'] = $this->validation;
		}

		// Load stored common model settings by reference
		foreach (array_keys(DataMapper::$common[$this->model]) as $key)
		{
			$this->{$key} =& DataMapper::$common[$this->model][$key];
		}

		// Clear object properties to set at default values
		$this->clear();
	}

	// --------------------------------------------------------------------

	/**
	 * Autoload
	 *
	 * Autoloads object classes that are used with DataMapper.
	 *
	 * Note:
	 * It is important that they are autoloaded as loading them manually with
	 * CodeIgniter's loader class will cause DataMapper's __get and __set functions
	 * to not function.
	 *
	 * @access	public
	 * @param	string
	 * @return	void
	 */
	static function autoload($class)
	{
		// Don't attempt to autoload CI_ or MY_ prefixed classes
		if (in_array(substr($class, 0, 3), array('CI_', 'MY_')))
		{
			return;
		}

		// Prepare class
		$class = strtolower($class);

		// Prepare path
		$path = APPPATH . 'models';

		// Prepare file
		$file = $path . '/' . $class . EXT;

		// Check if file exists, require_once if it does
		if (file_exists($file))
		{
			require_once($file);
		}
		else
		{
			// Do a recursive search of the path for the class
			DataMapper::recursive_require_once($class, $path);
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Recursive Require Once
	 *
	 * Recursively searches the path for the class, require_once if found.
	 *
	 * @access	public
	 * @param	string
	 * @param	string
	 * @return	void
	 */
	static function recursive_require_once($class, $path)
	{
		if ($handle = opendir($path))
		{
			while (FALSE !== ($dir = readdir($handle)))
			{
				// If dir does not contain a dot
				if (strpos($dir, '.') === FALSE)
				{
					// Prepare recursive path
					$recursive_path = $path . '/' . $dir;

					// Prepare file
					$file = $recursive_path . '/' . $class . EXT;

					// Check if file exists, require_once if it does
					if (file_exists($file))
					{
						require_once($file);

						break;
					}
					else if (is_dir($recursive_path))
					{
						// Do a recursive search of the path for the class
						DataMapper::recursive_require_once($class, $recursive_path);
					}
				}
			}

			closedir($handle);
		}
	}

	// --------------------------------------------------------------------


	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	 *                                                                   *
	 * Magic methods                                                     *
	 *                                                                   *
	 * The following are methods to override the default PHP behaviour.  *
	 *                                                                   *
	 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */


	// --------------------------------------------------------------------

	/**
	 * Set
	 *
	 * Sets the value of the named property.
	 *
	 * @access	overload
	 * @param	string
	 * @param	string
	 * @return	void
	 */
	function __set($name, $value)
	{
		$this->{$name} = $value;
	}

	// --------------------------------------------------------------------

	/**
	 * Get
	 *
	 * Returns the value of the named property.
	 * If named property is a related item, instantiate it first.
	 *
	 * @access	overload
	 * @param	string
	 * @return	object
	 */
	function __get($name)
	{
		// Return value of the named property
		if (isset($this->{$name}))
		{
			return $this->{$name};
		}

		$has_many = in_array($name, $this->has_many);
		$has_one = in_array($name, $this->has_one);

		// If named property is a "has many" or "has one" related item
		if ($has_many OR $has_one)
		{
			// Instantiate it before accessing
			$model = ucfirst($name);
			$this->{$name} = new $model();

			// Store parent data
			$this->{$name}->parent = array('model' => $this->model, 'id' => $this->id);

			// Check if Auto Populate for "has many" or "has one" is on
			if (($has_many && $this->auto_populate_has_many) OR ($has_one && $this->auto_populate_has_one))
			{
				$this->{$name}->get();
			}

			return $this->{$name};
		}

		return NULL;
	}

	// --------------------------------------------------------------------

	/**
	 * Call
	 *
	 * Calls the watched method.
	 *
	 * @access	overload
	 * @param	string
	 * @param	string
	 * @return	void
	 */
	function __call($method, $arguments)
	{
		// List of watched method names
		$watched_methods = array('get_by_related_', 'get_by_related', 'get_by_', '_related_', '_related');

		foreach ($watched_methods as $watched_method)
		{
			// See if called method is a watched method
			if (strpos($method, $watched_method) !== FALSE)
			{
				$pieces = explode($watched_method, $method);

				if ( ! empty($pieces[0]) && ! empty($pieces[1]))
				{
					// Watched method is in the middle
					return $this->{'_' . trim($watched_method, '_')}($pieces[0], array_merge(array($pieces[1]), $arguments));
				}
				else
				{
					// Watched method is a prefix or suffix
					return $this->{'_' . trim($watched_method, '_')}(str_replace($watched_method, '', $method), $arguments);
				}
			}
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Clone
	 *
	 * Allows for a less shallow clone than the default PHP clone.
	 *
	 * @access	overload
	 * @return	void
	 */
	function __clone()
	{
		foreach ($this as $key => $value)
		{
			if (is_object($value))
			{
				$this->{$key} = clone($value);
			}
		}
	}

	// --------------------------------------------------------------------

	/**
	 * To String
	 *
	 * Converts the current object into a string.
	 *
	 * @access	overload
	 * @return	void
	 */
	function __toString()
	{
		return ucfirst($this->model);
	}

	// --------------------------------------------------------------------


	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	 *                                                                   *
	 * Main methods                                                      *
	 *                                                                   *
	 * The following are methods that form the main                      *
	 * functionality of DataMapper.                                      *
	 *                                                                   *
	 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */


	// --------------------------------------------------------------------

	/**
	 * Get
	 *
	 * Get objects.
	 *
	 * @access	public
	 * @param	integer
	 * @param	integer
	 * @return	object
	 */
	function get($limit = NULL, $offset = NULL)
	{
		// Check if this is a related object and if so, perform a related get
		if ( ! empty($this->parent))
		{
			// Set limit and offset
			$this->limit($limit, $offset);

			// If this is a "has many" related item
			if (in_array($this->parent['model'], $this->has_many))
			{
				$this->_get_relation($this->parent['model'], $this->parent['id']);
			}

			// If this is a "has one" related item
			if (in_array($this->parent['model'], $this->has_one))
			{
				$this->_get_relation($this->parent['model'], $this->parent['id']);
			}

			// For method chaining
			return $this;
		}

		// Check if object has been validated
		if ($this->validated)
		{
			// Reset validated
			$this->validated = FALSE;

			// Use this objects properties
			$data = $this->_to_array(TRUE);

			if ( ! empty($data))
			{
				// Clear this object to make way for new data
				$this->clear();

				// Get by objects properties
				$query = $this->db->get_where($this->table, $data, $limit, $offset);

				if ($query->num_rows() > 0)
				{
					// Populate all with records as objects
					$this->all = $this->_to_object($query->result(), $this->model);

					// Populate this object with values from first record
					foreach ($query->row() as $key => $value)
					{
						$this->{$key} = $value;
					}
				}
			}
		}
		else
		{
			// Clear this object to make way for new data
			$this->clear();

			// Get by built up query
			$query = $this->db->get($this->table, $limit, $offset);

			if ($query->num_rows() > 0)
			{
				// Populate all with records as objects
				$this->all = $this->_to_object($query->result(), $this->model);

				// Populate this object with values from first record
				foreach ($query->row() as $key => $value)
				{
					$this->{$key} = $value;
				}
			}
		}

		$this->_refresh_stored_values();

		// For method chaining
		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Save
	 *
	 * Saves the current record.
	 * If object is supplied, saves relations between this object and the supplied object(s).
	 *
	 * @access	public
	 * @param	mixed
	 * @return	bool
	 */
	function save($object = '')
	{
		// Temporarily store the success/failure
		$result = array();

		// Validate this objects properties
		$this->validate($object);

		// If validation passed
		if ($this->valid)
		{
			// Get current timestamp
			$timestamp = ($this->local_time) ? date('Y-m-d H:i:s') : gmdate('Y-m-d H:i:s');

			// Check if unix timestamp
			$timestamp = ($this->unix_timestamp) ? strtotime($timestamp) : $timestamp;

			// Check if object has a 'created' field
			if (in_array($this->created_field, $this->fields))
			{
				// If created datetime is empty, set it
				if (empty($this->{$this->created_field}))
				{
					$this->{$this->created_field} = $timestamp;
				}
			}

			// Check if object has an 'updated' field
			if (in_array($this->updated_field, $this->fields))
			{
				// Update updated datetime
				$this->{$this->updated_field} = $timestamp;
			}

			// Convert this object to array
			$data = $this->_to_array();

			if ( ! empty($data))
			{
				if ( ! empty($data['id']))
				{
					// Prepare data to send only changed fields
					foreach ($data as $field => $value)
					{
						// Unset field from data if it hasn't been changed
						if ($this->{$field} === $this->stored->{$field})
						{
							unset($data[$field]);
						}
					}

					// Check if only the 'updated' field has changed, and if so, revert it
					if (count($data) == 1 && isset($data[$this->updated_field]))
					{
						// Revert updated
						$this->{$this->updated_field} = $this->stored->{$this->updated_field}; 

						// Unset it
						unset($data[$this->updated_field]);
					}

					// Only go ahead with save if there is still data
					if ( ! empty($data))
					{
						// Begin auto transaction
						$this->_auto_trans_begin();

						// Update existing record
						$this->db->where('id', $this->id);
						$this->db->update($this->table, $data);

						// Complete auto transaction
						$this->_auto_trans_complete('save (update)');
					}

					// Reset validated
					$this->validated = FALSE;

					$result[] = TRUE;
				}
				else
				{
					// Prepare data to send only populated fields
					foreach ($data as $field => $value)
					{
						// Unset field from data
						if ( ! isset($value))
						{
							unset($data[$field]);
						}
					}

					// Begin auto transaction
					$this->_auto_trans_begin();

					// Create new record
					$this->db->insert($this->table, $data);

					// Complete auto transaction
					$this->_auto_trans_complete('save (insert)');

					// Assign new ID
					$this->id = $this->db->insert_id();

					// Reset validated
					$this->validated = FALSE;

					$result[] = TRUE;
				}
			}

			$this->_refresh_stored_values();

			// Check if a relationship is being saved
			if ( ! empty($object))
			{
				// Check if it is an array of relationships
				if (is_array($object))
				{
					// Begin auto transaction
					$this->_auto_trans_begin();

					foreach ($object as $obj)
					{
						if (is_array($obj))
						{
							foreach ($obj as $o)
							{
								$result[] = $this->_save_relation($o);
							}
						}
						else
						{
							$result[] = $this->_save_relation($obj);
						}
					}

					// Complete auto transaction
					$this->_auto_trans_complete('save (relationship)');
				}
				else
				{
					// Begin auto transaction
					$this->_auto_trans_begin();

					// Temporarily store the success/failure
					$result[] = $this->_save_relation($object);

					// Complete auto transaction
					$this->_auto_trans_complete('save (relationship)');
				}
			}
		}

		// If no failure was recorded, return TRUE
		return ( ! empty($result) && ! in_array(FALSE, $result));
	}

	// --------------------------------------------------------------------

	/**
	 * Delete
	 *
	 * Deletes the current record.
	 * If object is supplied, deletes relations between this object and the supplied object(s).
	 *
	 * @access	public
	 * @param	mixed
	 * @return	bool
	 */
	function delete($object = '')
	{
		if (empty($object))
		{
			if ( ! empty($this->id))
			{
				// Begin auto transaction
				$this->_auto_trans_begin();

				// Delete this object
				$this->db->where('id', $this->id);
				$this->db->delete($this->table);

				// Delete all "has many" relations for this object
				foreach ($this->has_many as $model)
				{
					// Prepare model
					$model = ucfirst($model);
					$object = new $model();

					// Determine relationship table name
					$relationship_table = $this->_get_relationship_table($object->prefix, $object->table, $object->model);

					$data = array($this->model . '_id' => $this->id);

					// Delete relation
					$this->db->delete($relationship_table, $data);
				}

				// Delete all "has one" relations for this object
				foreach ($this->has_one as $model)
				{
					// Prepare model
					$model = ucfirst($model);
					$object = new $model();

					// Determine relationship table name
					$relationship_table = $this->_get_relationship_table($object->prefix, $object->table, $object->model);

					$data = array($this->model . '_id' => $this->id);

					// Delete relation
					$this->db->delete($relationship_table, $data);
				}

				// Complete auto transaction
				$this->_auto_trans_complete('delete');

				// Clear this object
				$this->clear();

				return TRUE;
			}
		}
		else if (is_array($object))
		{
			// Begin auto transaction
			$this->_auto_trans_begin();

			// Temporarily store the success/failure
			$result = array();

			foreach ($object as $obj)
			{
				if (is_array($obj))
				{
					foreach ($obj as $o)
					{
						$result[] = $this->_delete_relation($o);
					}
				}
				else
				{
					$result[] = $this->_delete_relation($obj);
				}
			}

			// Complete auto transaction
			$this->_auto_trans_complete('delete (relationship)');

			// If no failure was recorded, return TRUE
			if ( ! in_array(FALSE, $result))
			{
				return TRUE;
			}
		}
		else
		{
			// Begin auto transaction
			$this->_auto_trans_begin();

			// Temporarily store the success/failure
			$result = $this->_delete_relation($object);

			// Complete auto transaction
			$this->_auto_trans_complete('delete (relationship)');

			return $result;
		}

		return FALSE;
	}

	// --------------------------------------------------------------------

	/**
	 * Delete All
	 *
	 * Deletes all records in this objects all list.
	 *
	 * @access	public
	 * @return	bool
	 */
	function delete_all()
	{
		if ( ! empty($this->all))
		{
			foreach ($this->all as $item)
			{
				if ( ! empty($item->id))
				{
					$item->delete();
				}
			}

			$this->clear();

			return TRUE;
		}

		return FALSE;
	}
	
	// --------------------------------------------------------------------

	/**
	 * Refresh All
	 *
	 * Removes any empty objects in this objects all list.
	 * Only needs to be used if you are looping through the all list
	 * a second time and you have deleted a record the first time through.
	 *
	 * @access	public
	 * @return	bool
	 */
	function refresh_all()
	{
		if ( ! empty($this->all))
		{
			$all = array();

			foreach ($this->all as $item)
			{
				if ( ! empty($item->id))
				{
					$all[] = $item;
				}
			}

			$this->all = $all;

			return TRUE;
		}

		return FALSE;
	}

	// --------------------------------------------------------------------

	/**
	 * Validate
	 *
	 * Validates the value of each property against the assigned validation rules.
	 *
	 * @access	public
	 * @param	mixed
	 * @return	object
	 */
	function validate($object = '')
	{
		// Return if validation has already been run
		if ($this->validated)
		{
			// For method chaining
			return $this;
		}

		// Set validated as having been run
		$this->validated = TRUE;

		// Clear errors
		$this->error = new stdClass();
		$this->error->all = array();
		$this->error->string = '';

		foreach ($this->fields as $field)
		{
			$this->error->{$field} = '';
		}

		// Loop through each property to be validated
		foreach ($this->validation as $validation)
		{
			// Get validation settings
			$field = $validation['field'];
			$label = ( ! empty($validation['label'])) ? $validation['label'] : $field;
			$rules = $validation['rules'];

			// Will validate differently if this is for a related item
			$related = (in_array($field, $this->has_many) OR in_array($field, $this->has_one));

			// Check if property has changed since validate last ran
			if ($related OR ! isset($this->stored->{$field}) OR $this->{$field} !== $this->stored->{$field})
			{
				// Only validate if field is related or required or has a value
				if ( ! $related && ! in_array('required', $rules))
				{
					if ( ! isset($this->{$field}) OR $this->{$field} === '')
					{
						continue;
					}
				}

				// Loop through each rule to validate this property against
				foreach ($rules as $rule => $param)
				{
					// Check for parameter
					if (is_numeric($rule))
					{
						$rule = $param;
						$param = '';
					}

					// Clear result
					$result = '';

					// Check rule exists
					if ($related)
					{
						// Prepare rule to use different language file lines
						$rule = 'related_' . $rule;

						if (method_exists($this->model, '_' . $rule))
						{
							// Run related rule from DataMapper or the class extending DataMapper
							$result = $this->{'_' . $rule}($object, $field, $param);
						}
					}
					else if (method_exists($this->model, '_' . $rule))
					{
						// Run rule from DataMapper or the class extending DataMapper
						$result = $this->{'_' . $rule}($field, $param);
					}
					else if (method_exists($this->form_validation, $rule))
					{
						// Run rule from CI Form Validation
						$result = $this->form_validation->{$rule}($this->{$field}, $param);
					}
					else if (function_exists($rule))
					{
						// Run rule from PHP
						$this->{$field} = $rule($this->{$field});
					}

					// Add an error message if the rule returned FALSE
					if ($result === FALSE)
					{
						// Get corresponding error from language file
						if (FALSE === ($line = $this->lang->line($rule)))
						{
							$line = 'Unable to access an error message corresponding to your field name.';
						}

						// Check if param is an array
						if (is_array($param))
						{
							// Convert into a string so it can be used in the error message
							$param = implode(', ', $param);

							// Replace last ", " with " or "
							if (FALSE !== ($pos = strrpos($param, ', ')))
							{
								$param = substr_replace($param, ' or ', $pos, 2);
							}
						}

						// Check if param is a validation field
						if (array_key_exists($param, $this->validation))
						{
							// Change it to the label value
							$param = $this->validation[$param]['label'];
						}

						// Add error message
						$this->error_message($field, sprintf($line, $label, $param));
					}
				}
			}
		}

		// Set whether validation passed
		$this->valid = empty($this->error->all);

		// For method chaining
		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Clear
	 *
	 * Clears the current object.
	 *
	 * @access	public
	 * @return	void
	 */
	function clear()
	{
		// Clear the all list
		$this->all = array();

		// Clear errors
		$this->error = new stdClass();
		$this->error->all = array();
		$this->error->string = '';

		// Clear this objects properties and set blank error messages in case they are accessed
		foreach ($this->fields as $field)
		{
			$this->{$field} = NULL;
			$this->error->{$field} = '';
		}

		// Clear this objects "has many" related objects
		foreach ($this->has_many as $related)
		{
			unset($this->{$related});
		}

		// Clear this objects "has one" related objects
		foreach ($this->has_one as $related)
		{
			unset($this->{$related});
		}

		// Clear the query related list
		$this->query_related = array();

		// Clear and refresh stored values
		$this->stored = new stdClass();

		$this->_refresh_stored_values();
	}

	// --------------------------------------------------------------------

	/**
	 * Count
	 *
	 * Returns the total count of the objects records.
	 * If on a related object, returns the total count of related objects records.
	 *
	 * @access	public
	 * @return	integer
	 */
	function count()
	{
		// Check if related object
		if ( ! empty($this->parent))
		{
			// Prepare model
			$model = ucfirst($this->parent['model']);
			$object = new $model();

			// Determine relationship table name
			$relationship_table = $this->_get_relationship_table($object->prefix, $object->table, $object->model);

			$this->db->where($this->parent['model'] . '_id', $this->parent['id']);
			$this->db->from($relationship_table);

			// Return count
			return $this->db->count_all_results();
		}
		else
		{
			$this->db->from($this->table);

			// Return count
			return $this->db->count_all_results();
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Exists
	 *
	 * Returns TRUE if the current object has a database record.
	 *
	 * @access	public
	 * @return	bool
	 */
	function exists()
	{
		return ( ! empty($this->id));
	}

	// --------------------------------------------------------------------

	/**
	 * Query
	 *
	 * Runs the specified query and populates the current object with the results.
	 *
	 * Warning: Use at your own risk.  This will only be as reliable as your query.
	 *
	 * @access	public
	 * @access	string
	 * @access	array
	 * @return	void
	 */
	function query($sql, $binds = FALSE)
	{
		// Get by objects properties
		$query = $this->db->query($sql, $binds);

		if ($query->num_rows() > 0)
		{
			// Populate all with records as objects
			$this->all = $this->_to_object($query->result(), $this->model);

			// Populate this object with values from first record
			foreach ($query->row() as $key => $value)
			{
				$this->{$key} = $value;
			}
		}

		$this->_refresh_stored_values();

		// For method chaining
		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Error Message
	 *
	 * Adds an error message to this objects error object.
	 *
	 * @access	public
	 * @access	string
	 * @access	string
	 * @return	void
	 */
	function error_message($field, $error)
	{
		if ( ! empty($field) && ! empty($error))
		{
			// Set field specific error
			$this->error->{$field} = $this->error_prefix . $error . $this->error_suffix;

			// Add field error to errors all list
			$this->error->all[] = $this->error->{$field};

			// Append field error to error message string
			$this->error->string .= $this->error->{$field};
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Get Clone
	 *
	 * Returns a clone of the current object.
	 *
	 * @access	public
	 * @return	object
	 */
	function get_clone()
	{
		return clone($this);
	}

	// --------------------------------------------------------------------

	/**
	 * Get Copy
	 *
	 * Returns an unsaved copy of the current object.
	 *
	 * @access	public
	 * @return	object
	 */
	function get_copy()
	{
		$copy = clone($this);

		$copy->id = NULL;

		return $copy;
	}

	// --------------------------------------------------------------------

	/**
	 * Get By
	 *
	 * Gets objects by specified field name and value.
	 *
	 * @access	private
	 * @param	string
	 * @param	string
	 * @return	object
	 */
	function _get_by($field, $value = array())
	{
		if (isset($value[0]))
		{
			$this->where($field, $value[0]);
		}

		return $this->get();
	}

	// --------------------------------------------------------------------

	/**
	 * Get By Related
	 *
	 * Gets objects by specified related object and optionally by field name and value.
	 *
	 * @access	private
	 * @param	string
	 * @param	mixed
	 * @return	object
	 */
	function _get_by_related($model, $arguments = array())
	{
		if ( ! empty($model))
		{
			// Add model to start of arguments
			$arguments = array_merge(array($model), $arguments);
		}

		$this->_related('where', $arguments);

		return $this->get();
	}

	// --------------------------------------------------------------------


	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	 *                                                                   *
	 * Active Record methods                                             *
	 *                                                                   *
	 * The following are methods used to provide Active Record           *
	 * functionality for data retrieval.                                 *
	 *                                                                   *
	 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */


	// --------------------------------------------------------------------

	/**
	 * Select
	 *
	 * Sets the SELECT portion of the query.
	 *
	 * @access	public
	 * @param	string
	 * @param	bool
	 * @return	object
	 */
	function select($select = '*', $escape = NULL)
	{
		// Check if this is a related object
		if ( ! empty($this->parent))
		{
			$this->db->select($this->table . '.' . $select, $escape);
		}
		else
		{
			$this->db->select($select, $escape);
		}

		// For method chaining
		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Select Max
	 *
	 * Sets the SELECT MAX(field) portion of a query.
	 *
	 * @access	public
	 * @param	string
	 * @param	string
	 * @return	object
	 */
	function select_max($select = '', $alias = '')
	{
		// Check if this is a related object
		if ( ! empty($this->parent))
		{
			$alias = ($alias != '') ? $alias : $select;

			$this->db->select_max($this->table . '.' . $select, $alias);
		}
		else
		{
			$this->db->select_max($select, $alias);
		}

		// For method chaining
		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Select Min
	 *
	 * Sets the SELECT MIN(field) portion of a query.
	 *
	 * @access	public
	 * @param	string
	 * @param	string
	 * @return	object
	 */
	function select_min($select = '', $alias = '')
	{
		// Check if this is a related object
		if ( ! empty($this->parent))
		{
			$alias = ($alias != '') ? $alias : $select;

			$this->db->select_min($this->table . '.' . $select, $alias);
		}
		else
		{
			$this->db->select_min($select, $alias);
		}

		// For method chaining
		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Select Avg
	 *
	 * Sets the SELECT AVG(field) portion of a query.
	 *
	 * @access	public
	 * @param	string
	 * @param	string
	 * @return	object
	 */
	function select_avg($select = '', $alias = '')
	{
		// Check if this is a related object
		if ( ! empty($this->parent))
		{
			$alias = ($alias != '') ? $alias : $select;

			$this->db->select_avg($this->table . '.' . $select, $alias);
		}
		else
		{
			$this->db->select_avg($select, $alias);
		}

		// For method chaining
		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Select Sum
	 *
	 * Sets the SELECT SUM(field) portion of a query.
	 *
	 * @access	public
	 * @param	string
	 * @param	string
	 * @return	object
	 */
	function select_sum($select = '', $alias = '')
	{
		// Check if this is a related object
		if ( ! empty($this->parent))
		{
			$alias = ($alias != '') ? $alias : $select;

			$this->db->select_sum($this->table . '.' . $select, $alias);
		}
		else
		{
			$this->db->select_sum($select, $alias);
		}

		// For method chaining
		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Distinct
	 *
	 * Sets the flag to add DISTINCT to the query.
	 *
	 * @access	public
	 * @param	bool
	 * @return	object
	 */
	function distinct($value = TRUE)
	{
		$this->db->distinct($value);

		// For method chaining
		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Get Where
	 *
	 * Get items matching the where clause.
	 *
	 * @access	public
	 * @param	mixed
	 * @param	string
	 * @param	string
	 * @return	bool
	 */
	function get_where($where = array(), $limit = NULL, $offset = NULL)
	{
		$this->where($where);

		return $this->get($limit, $offset);
	}

	// --------------------------------------------------------------------

	/**
	 * Where
	 *
	 * Sets the WHERE portion of the query.
	 * Separates multiple calls with AND.
	 *
	 * Called by get_where()
	 *
	 * @access	public
	 * @param	mixed
	 * @param	mixed
	 * @return	object
	 */
	function where($key, $value = NULL, $escape = TRUE)
	{
		return $this->_where($key, $value, 'AND ', $escape);
	}

	// --------------------------------------------------------------------

	/**
	 * Or Where
	 *
	 * Sets the WHERE portion of the query.
	 * Separates multiple calls with OR.
	 *
	 * @access	public
	 * @param	mixed
	 * @param	mixed
	 * @return	object
	 */
	function or_where($key, $value = NULL, $escape = TRUE)
	{
		return $this->_where($key, $value, 'OR ', $escape);
	}

	// --------------------------------------------------------------------

	/**
	 * Where
	 *
	 * Called by where() or or_where().
	 *
	 * @access	private
	 * @param	mixed
	 * @param	mixed
	 * @param	string
	 * @param	bool
	 * @return	object
	 */
	function _where($key, $value = NULL, $type = 'AND ', $escape = NULL)
	{
		if ( ! is_array($key))
		{
			$key = array($key => $value);
		}

		// Check if this is a related object
		if ( ! empty($this->parent))
		{
			foreach ($key as $k => $v)
			{
				$key[$this->table . '.' . $k] = $v;
				unset($key[$k]);
			}
		}

		$this->db->_where($key, $value, $type, $escape);

		// For method chaining
		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Where In
	 *
	 * Sets the WHERE field IN ('item', 'item') SQL query joined with
	 * AND if appropriate.
	 *
	 * @access	public
	 * @param	string
	 * @param	array
	 * @return	object
	 */
	function where_in($key = NULL, $values = NULL)
	{
	 	return $this->_where_in($key, $values);
	}

	// --------------------------------------------------------------------

	/**
	 * Or Where In
	 *
	 * Sets the WHERE field IN ('item', 'item') SQL query joined with
	 * OR if appropriate.
	 *
	 * @access	public
	 * @param	string
	 * @param	array
	 * @return	object
	 */
	function or_where_in($key = NULL, $values = NULL)
	{
	 	return $this->_where_in($key, $values, FALSE, 'OR ');
	}

	// --------------------------------------------------------------------

	/**
	 * Where Not In
	 *
	 * Sets the WHERE field NOT IN ('item', 'item') SQL query joined with
	 * AND if appropriate.
	 *
	 * @access	public
	 * @param	string
	 * @param	array
	 * @return	object
	 */
	function where_not_in($key = NULL, $values = NULL)
	{
		return $this->_where_in($key, $values, TRUE);
	}

	// --------------------------------------------------------------------

	/**
	 * Or Where Not In
	 *
	 * Sets the WHERE field NOT IN ('item', 'item') SQL query joined wuth
	 * OR if appropriate.
	 *
	 * @access	public
	 * @param	string
	 * @param	array
	 * @return	object
	 */
	function or_where_not_in($key = NULL, $values = NULL)
	{
		return $this->_where_in($key, $values, TRUE, 'OR ');
	}

	// --------------------------------------------------------------------

	/**
	 * Where In
	 *
	 * Called by where_in(), or_where_in(), where_not_in(), or or_where_not_in().
	 *
	 * @access	private
	 * @param	string
	 * @param	array
	 * @param	bool
	 * @param	string
	 * @return	object
	 */
	function _where_in($key = NULL, $values = NULL, $not = FALSE, $type = 'AND ')
	{
	 	// Check if this is a related object
		if ( ! empty($this->parent))
		{
			$this->db->_where_in($this->table . '.' . $key, $values, $not, $type);
		}
		else
		{
			$this->db->_where_in($key, $values, $not, $type);
		}

		// For method chaining
		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Like
	 *
	 * Sets the %LIKE% portion of the query.
	 * Separates multiple calls with AND.
	 *
	 * @access	public
	 * @param	mixed
	 * @param	mixed
	 * @param	string
	 * @return	object
	 */
	function like($field, $match = '', $side = 'both')
	{
		return $this->_like($field, $match, 'AND ', $side);
	}

	// --------------------------------------------------------------------

	/**
	 * Not Like
	 *
	 * Sets the NOT LIKE portion of the query.
	 * Separates multiple calls with AND.
	 *
	 * @access	public
	 * @param	mixed
	 * @param	mixed
	 * @param	string
	 * @return	object
	 */
	function not_like($field, $match = '', $side = 'both')
	{
		return $this->_like($field, $match, 'AND ', $side, 'NOT');
	}

	// --------------------------------------------------------------------

	/**
	 * Or Like
	 *
	 * Sets the %LIKE% portion of the query.
	 * Separates multiple calls with OR.
	 *
	 * @access	public
	 * @param	mixed
	 * @param	mixed
	 * @param	string
	 * @return	object
	 */
	function or_like($field, $match = '', $side = 'both')
	{
		return $this->_like($field, $match, 'OR ', $side);
	}

	// --------------------------------------------------------------------

	/**
	 * Or Not Like
	 *
	 * Sets the NOT LIKE portion of the query.
	 * Separates multiple calls with OR.
	 *
	 * @access	public
	 * @param	mixed
	 * @param	mixed
	 * @param	string
	 * @return	object
	 */
	function or_not_like($field, $match = '', $side = 'both')
	{
		return $this->_like($field, $match, 'OR ', $side, 'NOT');
	}

	// --------------------------------------------------------------------

	/**
	 * Like
	 *
	 * Sets the %LIKE% portion of the query.
	 * Separates multiple calls with AND.
	 *
	 * @access	private
	 * @param	mixed
	 * @param	mixed
	 * @param	string
	 * @param	string
	 * @param	string
	 * @return	object
	 */
	function _like($field, $match = '', $type = 'AND ', $side = 'both', $not = '')
	{
		if ( ! is_array($field))
		{
			$field = array($field => $match);
		}

		// Check if this is a related object
		if ( ! empty($this->parent))
		{
			foreach ($field as $k => $v)
			{
				$field[$this->table . '.' . $k] = $v;
				unset($field[$k]);
			}
		}

		$this->db->_like($field, $match, $type, $side, $not);

		// For method chaining
		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Group By
	 *
	 * Sets the GROUP BY portion of the query.
	 *
	 * @access	public
	 * @param	string
	 * @return	object
	 */
	function group_by($by)
	{
		// Check if this is a related object
		if ( ! empty($this->parent))
		{
			$this->db->group_by($this->table . '.' . $by);
		}
		else
		{
			$this->db->group_by($by);
		}

		// For method chaining
		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Having
	 *
	 * Sets the HAVING portion of the query.
	 * Separates multiple calls with AND.
	 *
	 * @access	public
	 * @param	string
	 * @param	string
	 * @param	bool
	 * @return	object
	 */
	function having($key, $value = '', $escape = TRUE)
	{
		return $this->_having($key, $value, 'AND ', $escape);
	}

	// --------------------------------------------------------------------

	/**
	 * Or Having
	 *
	 * Sets the OR HAVING portion of the query.
	 * Separates multiple calls with OR.
	 *
	 * @access	public
	 * @param	string
	 * @param	string
	 * @param	bool
	 * @return	object
	 */
	function or_having($key, $value = '', $escape = TRUE)
	{
		return $this->_having($key, $value, 'OR ', $escape);
	}

	// --------------------------------------------------------------------

	/**
	 * Having
	 *
	 * Sets the HAVING portion of the query.
	 * Separates multiple calls with AND.
	 *
	 * @access	private
	 * @param	string
	 * @param	string
	 * @param	string
	 * @param	bool
	 * @return	object
	 */
	function _having($key, $value = '', $type = 'AND ', $escape = TRUE)
	{
		// Check if this is a related object
		if ( ! empty($this->parent))
		{
			$this->db->_having($this->table . '.' . $key, $value, $type, $escape);
		}
		else
		{
			$this->db->_having($key, $value, $type, $escape);
		}

		// For method chaining
		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Order By
	 *
	 * Sets the ORDER BY portion of the query.
	 *
	 * @access	public
	 * @param	string
	 * @param	string
	 * @return	object
	 */
	function order_by($orderby, $direction = '')
	{
		// Check if this is a related object
		if ( ! empty($this->parent))
		{
			$this->db->order_by($this->table . '.' . $orderby, $direction);
		}
		else
		{
			$this->db->order_by($orderby, $direction);
		}

		// For method chaining
		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Limit
	 *
	 * Sets the LIMIT portion of the query.
	 *
	 * @access	public
	 * @param	integer
	 * @param	integer
	 * @return	object
	 */
	function limit($value, $offset = '')
	{
		$this->db->limit($value, $offset);

		// For method chaining
		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Offset
	 *
	 * Sets the OFFSET portion of the query.
	 *
	 * @access	public
	 * @param	integer
	 * @return	object
	 */
	function offset($offset)
	{
		$this->db->offset($offset);

		// For method chaining
		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Start Cache
	 *
	 * Starts AR caching.
	 *
	 * @access	public
	 * @return	void
	 */		
	function start_cache()
	{
		$this->db->start_cache();
	}

	// --------------------------------------------------------------------

	/**
	 * Stop Cache
	 *
	 * Stops AR caching.
	 *
	 * @access	public
	 * @return	void
	 */		
	function stop_cache()
	{
		$this->db->stop_cache();
	}

	// --------------------------------------------------------------------

	/**
	 * Flush Cache
	 *
	 * Empties the AR cache.
	 *
	 * @access	public
	 * @return	void
	 */	
	function flush_cache()
	{	
		$this->db->flush_cache();
	}

	// --------------------------------------------------------------------


	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	 *                                                                   *
	 * Transaction methods                                               *
	 *                                                                   *
	 * The following are methods used for transaction handling.          *
	 *                                                                   *
	 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */


	// --------------------------------------------------------------------

	/**
	 * Trans Off
	 *
	 * This permits transactions to be disabled at run-time.
	 *
	 * @access	public
	 * @return	void		
	 */	
	function trans_off()
	{
		$this->db->trans_enabled = FALSE;
	}

	// --------------------------------------------------------------------

	/**
	 * Trans Strict
	 *
	 * When strict mode is enabled, if you are running multiple groups of
	 * transactions, if one group fails all groups will be rolled back.
	 * If strict mode is disabled, each group is treated autonomously, meaning
	 * a failure of one group will not affect any others.
	 *
	 * @access	public
	 * @param	bool
	 * @return	void		
	 */	
	function trans_strict($mode = TRUE)
	{
		$this->db->trans_strict($mode);
	}

	// --------------------------------------------------------------------

	/**
	 * Trans Start
	 *
	 * Start a transaction.
	 *
	 * @access	public
	 * @param	bool
	 * @return	void
	 */	
	function trans_start($test_mode = FALSE)
	{	
		$this->db->trans_start($test_mode);
	}

	// --------------------------------------------------------------------

	/**
	 * Trans Complete
	 *
	 * Complete a transaction.
	 *
	 * @access	public
	 * @return	bool		
	 */	
	function trans_complete()
	{
		return $this->db->trans_complete();
	}

	// --------------------------------------------------------------------

	/**
	 * Trans Begin
	 *
	 * Begin a transaction.
	 *
	 * @access	public
	 * @param	bool
	 * @return	bool
	 */	
	function trans_begin($test_mode = FALSE)
	{	
		return $this->db->trans_begin($test_mode);
	}

	// --------------------------------------------------------------------

	/**
	 * Trans Status
	 *
	 * Lets you retrieve the transaction flag to determine if it has failed.
	 *
	 * @access	public
	 * @return	bool		
	 */	
	function trans_status()
	{
		return $this->_trans_status;
	}

	// --------------------------------------------------------------------

	/**
	 * Trans Commit
	 *
	 * Commit a transaction.
	 *
	 * @access	public
	 * @return	bool
	 */	
	function trans_commit()
	{
		return $this->db->trans_commit();
	}

	// --------------------------------------------------------------------

	/**
	 * Trans Rollback
	 *
	 * Rollback a transaction.
	 *
	 * @access	public
	 * @return	bool
	 */	
	function trans_rollback()
	{
		return $this->db->trans_rollback();
	}

	// --------------------------------------------------------------------

	/**
	 * Auto Trans Begin
	 *
	 * Begin an auto transaction if enabled.
	 *
	 * @access	public
	 * @param	bool
	 * @return	bool
	 */	
	function _auto_trans_begin()
	{
		// Begin auto transaction
		if ($this->auto_transaction)
		{
			$this->trans_begin();
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Auto Trans Complete
	 *
	 * Complete an auto transaction if enabled.
	 *
	 * @access	public
	 * @param	string
	 * @return	bool
	 */	
	function _auto_trans_complete($label = 'complete')
	{
		// Complete auto transaction
		if ($this->auto_transaction)
		{
			// Check if successful
			if (!$this->trans_complete())
			{
				$rule = 'transaction';

				// Get corresponding error from language file
				if (FALSE === ($line = $this->lang->line($rule)))
				{
					$line = 'Unable to access the ' . $rule .' error message.';
				}

				// Add transaction error message
				$this->error_message($rule, sprintf($line, $label));

				// Set validation as failed
				$this->valid = FALSE;
			}
		}
	}

	// --------------------------------------------------------------------


	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	 *                                                                   *
	 * Related methods                                                   *
	 *                                                                   *
	 * The following are methods used for managing related records.      *
	 *                                                                   *
	 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

	// --------------------------------------------------------------------

	/**
	 * Related
	 *
	 * Sets the specified related query.
	 *
	 * @access	private
	 * @param	string
	 * @param	mixed
	 * @return	object
	 */
	function _related($query, $arguments = array())
	{
		if ( ! empty($query) && ! empty($arguments))
		{
			$object = $field = $value = $option = NULL;

			// Prepare model
			if (is_object($arguments[0]))
			{
				$object = $arguments[0];

				// Prepare field and value
				$field = (isset($arguments[1])) ? $arguments[1] : 'id';
				$value = (isset($arguments[2])) ? $arguments[2] : $object->id;
			}
			else
			{
				$model = ucfirst($arguments[0]);
				$object = new $model();

				// Prepare field and value
				$field = (isset($arguments[1])) ? $arguments[1] : 'id';
				$value = (isset($arguments[2])) ? $arguments[2] : NULL;
			}

			// Ignore if field and value is invalid
			if ($field == 'id' && empty($value) OR $field != 'id' && $this->table == $object->table)
			{
				// For method chaining
				return $this;
			}

			// Determine relationship table name
			$relationship_table = $this->_get_relationship_table($object->prefix, $object->table, $object->model);

			// Retrieve related records
			if (empty($this->db->ar_select))
			{
				$this->db->select($this->table . '.*');
			}

			// Check if self referencing
			if ($this->table == $object->table)
			{
				// Add join if not already included
				if ( ! in_array($object->model, $this->query_related))
				{
					$this->db->join($relationship_table, $object->table . '.id = ' . $relationship_table . '.' . $this->model . '_id', 'left');

					$this->query_related[] = $object->model;
				}

				// Add query clause
				$this->db->{$query}($relationship_table . '.' . $object->model . '_id', $value);
			}
			else
			{
				// Add join if not already included
				if ( ! in_array($object->model, $this->query_related))
				{
					$this->db->join($relationship_table, $this->table . '.id = ' . $relationship_table . '.' . $this->model . '_id', 'left');

					$this->query_related[] = $object->model;
				}

				// Add join if not already included
				if ( ! in_array($object->table, $this->query_related))
				{
					$this->db->join($object->table, $object->table . '.id = ' . $relationship_table . '.' . $object->model . '_id', 'left');

					$this->query_related[] = $object->table;
				}

				// Add query clause
				$this->db->{$query}($object->table . '.' . $field, $value);
			}
		}

		// For method chaining
		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Get Relation
	 *
	 * Finds all related records of this objects current record.
	 *
	 * @access	private
	 * @param	string
	 * @param	integer
	 * @return	void
	 */
	function _get_relation($model, $id)
	{
		// No related items
		if (empty($model) OR empty($id))
		{
			// Reset query
			$this->db->_reset_select();

			return;
		}

		// Prepare model
		$model = ucfirst($model);
		$object = new $model();

		// Determine relationship table name
		$relationship_table = $this->_get_relationship_table($object->prefix, $object->table, $object->model);

		// Retrieve related records
		if (empty($this->db->ar_select))
		{
			$this->db->select($this->table . '.*');
		}

		// Check if self referencing
		if ($this->table == $object->table)
		{
			$this->db->from($this->table);
			$this->db->join($relationship_table, $object->table . '.id = ' . $relationship_table . '.' . $this->model . '_id', 'left');
			$this->db->where($relationship_table . '.' . $object->model . '_id = ' . $id);
		}
		else
		{
			$this->db->from($this->table);
			$this->db->join($relationship_table, $this->table . '.id = ' . $relationship_table . '.' . $this->model . '_id', 'left');
			$this->db->join($object->table, $object->table . '.id = ' . $relationship_table . '.' . $object->model . '_id', 'left');
			$this->db->where($object->table . '.id = ' . $id);
		}

		$query = $this->db->get();

		// Clear this object to make way for new data
		$this->clear();

		if ($query->num_rows() > 0)
		{
			// Populate all with records as objects
			$this->all = $this->_to_object($query->result(), $this->model);

			// Populate this object with values from first record
			foreach ($query->row() as $key => $value)
			{
				$this->{$key} = $value;
			}
		}

		$this->_refresh_stored_values();
	}

	// --------------------------------------------------------------------

	/**
	 * Save Relation
	 *
	 * Saves the relation between this and the other object.
	 *
	 * @access	private
	 * @param	object
	 * @return	bool
	 */
	function _save_relation($object)
	{
		if ( ! empty($object->model) && ! empty($this->id) && ! empty($object->id))
		{
			// Determine relationship table name
			$relationship_table = $this->_get_relationship_table($object->prefix, $object->table, $object->model);

			$data = array($this->model . '_id' => $this->id, $object->model . '_id' => $object->id);

			// Check if relation already exists
			$query = $this->db->get_where($relationship_table, $data, NULL, NULL);

			if ($query->num_rows() == 0)
			{
				// If this object has a "has many" relationship with the other object
				if (in_array($object->model, $this->has_many))
				{
					// If the other object has a "has one" relationship with this object
					if (in_array($this->model, $object->has_one))
					{
						// And it has an existing relation
						$query = $this->db->get_where($relationship_table, array($object->model . '_id' => $object->id), 1, 0);

						if ($query->num_rows() > 0)
						{
							// Find and update the other objects existing relation to relate with this object
							$this->db->where($object->model . '_id', $object->id);
							$this->db->update($relationship_table, $data);
						}
						else
						{
							// Add the relation since one doesn't exist
							$this->db->insert($relationship_table, $data);
						}

						return TRUE;
					}
					else if (in_array($this->model, $object->has_many))
					{
						// We can add the relation since this specific relation doesn't exist, and a "has many" to "has many" relationship exists between the objects
						$this->db->insert($relationship_table, $data);

						return TRUE;
					}
				}
				// If this object has a "has one" relationship with the other object
				else if (in_array($object->model, $this->has_one))
				{
					// And it has an existing relation
					$query = $this->db->get_where($relationship_table, array($this->model . '_id' => $this->id), 1, 0);
						
					if ($query->num_rows() > 0)
					{
						// Find and update the other objects existing relation to relate with this object
						$this->db->where($this->model . '_id', $this->id);
						$this->db->update($relationship_table, $data);
					}
					else
					{
						// Add the relation since one doesn't exist
						$this->db->insert($relationship_table, $data);
					}

					return TRUE;
				}
			}
			else
			{
				// Relationship already exists
				return TRUE;
			}
		}

		return FALSE;
	}

	// --------------------------------------------------------------------

	/**
	 * Delete Relation
	 *
	 * Deletes the relation between this and the other object.
	 *
	 * @access	private
	 * @param	object
	 * @return	bool
	 */
	function _delete_relation($object)
	{
		if ( ! empty($object->model) && ! empty($this->id) && ! empty($object->id))
		{
			// Determine relationship table name
			$relationship_table = $this->_get_relationship_table($object->prefix, $object->table, $object->model);

			$data = array($this->model . '_id' => $this->id, $object->model . '_id' => $object->id);

			// Delete relation
			$this->db->delete($relationship_table, $data);

			// Clear related object so it is refreshed on next access
			$this->{$object->model} = NULL;

			return TRUE;
		}

		return FALSE;
	}

	// --------------------------------------------------------------------

	/**
	 * Get Relationship Table
	 *
	 * Determines the relationship table.
	 *
	 * @access	private
	 * @param	string
	 * @param	string
	 * @param	string
	 * @return	string
	 */
	function _get_relationship_table($prefix, $table, $model)
	{
		$relationship_table = '';

		// Check if self referencing
		if ($this->table == $table)
		{
			$relationship_table = (plural($this->model) < plural($model)) ? plural($this->model) . '_' . plural($model) : plural($model) . '_' . plural($this->model);
		}
		else
		{
			$relationship_table = ($this->table < $table) ? $this->table . '_' . $table : $table . '_' . $this->table;
		}

		// Remove all occurances of the prefix from the relationship table
		$relationship_table = str_replace($prefix, '', str_replace($this->prefix, '', $relationship_table));

		// So we can prefix the beginning, using the join prefix instead, if it is set
		$relationship_table = (empty($this->join_prefix)) ? $this->prefix . $relationship_table : $this->join_prefix . $relationship_table;

		return $relationship_table;
	}

	// --------------------------------------------------------------------

	/**
	 * Count Related
	 *
	 * Returns the number of related items in the database and in the related object.
	 *
	 * @access	private
	 * @param	string
	 * @param	mixed
	 * @return	integer
	 */
	function _count_related($model, $object = '')
	{
		$count = 0;

		if ( ! empty($object))
		{
			if (is_array($object))
			{
				foreach ($object as $obj)
				{
					if (is_array($obj))
					{
						foreach ($obj as $o)
						{
							if ($o->model == $model)
							{
								$count++;
							}
						}
					}
					else
					{
						if ($obj->model == $model)
						{
							$count++;
						}
					}
				}
			}
			else
			{
				if ($object->model == $model)
				{
					$count++;
				}
			}
		}

		if ( ! empty($model) && ! empty($this->id))
		{
			// Prepare model
			$model = ucfirst($model);
			$object = new $model();

			// Store parent data
			$object->parent = array('model' => $this->model, 'id' => $this->id);

			$count += $object->count();
		}

		return $count;
	}

	// --------------------------------------------------------------------


	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	 *                                                                   *
	 * Related Validation methods                                        *
	 *                                                                   *
	 * The following are methods used to validate the                    *
	 * relationships of this object.                                     *
	 *                                                                   *
	 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */


	// --------------------------------------------------------------------

	/**
	 * Related Required (pre-process)
	 *
	 * Checks if the related object has the required related item
	 * or if the required relation already exists.
	 *
	 * @access	private
	 * @param	mixed
	 * @param	string
	 * @return	bool
	 */	
	function _related_required($object, $model)
	{
		return ($this->_count_related($model, $object) == 0) ? FALSE : TRUE;
	}

	// --------------------------------------------------------------------

	/**
	 * Related Min Size (pre-process)
	 *
	 * Checks if the value of a property is at most the minimum size.
	 * 
	 * @access	private
	 * @param	mixed
	 * @param	string
	 * @param	integer
	 * @return	bool
	 */
	function _related_min_size($object, $model, $size = 0)
	{
		return ($this->_count_related($model, $object) < $size) ? FALSE : TRUE;
	}

	// --------------------------------------------------------------------

	/**
	 * Related Max Size (pre-process)
	 *
	 * Checks if the value of a property is at most the maximum size.
	 * 
	 * @access	private
	 * @param	mixed
	 * @param	string
	 * @param	integer
	 * @return	bool
	 */
	function _related_max_size($object, $model, $size = 0)
	{
		return ($this->_count_related($model, $object) > $size) ? FALSE : TRUE;
	}

	// --------------------------------------------------------------------


	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	 *                                                                   *
	 * Validation methods                                                *
	 *                                                                   *
	 * The following are methods used to validate the                    *
	 * values of this objects properties.                                *
	 *                                                                   *
	 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */


	// --------------------------------------------------------------------

	/**
	 * Alpha Dash Dot (pre-process)
	 *
	 * Alpha-numeric with underscores, dashes and full stops.
	 *
	 * @access	private
	 * @param	string
	 * @return	bool
	 */	
	function _alpha_dash_dot($field)
	{
		return ( ! preg_match('/^([\.-a-z0-9_-])+$/i', $this->{$field})) ? FALSE : TRUE;
	}

	// --------------------------------------------------------------------

	/**
	 * Alpha Slash Dot (pre-process)
	 *
	 * Alpha-numeric with underscores, dashes, forward slashes and full stops.
	 *
	 * @access	private
	 * @param	string
	 * @return	bool
	 */	
	function _alpha_slash_dot($field)
	{
		return ( ! preg_match('/^([\.\/-a-z0-9_-])+$/i', $this->{$field})) ? FALSE : TRUE;
	}

	// --------------------------------------------------------------------

	/**
	 * Matches (pre-process)
	 *
	 * Match one field to another.
	 * This replaces the version in CI_Form_validation.
	 * 
	 * @access	private
	 * @param	string
	 * @param	string
	 * @return	bool
	 */
	function _matches($field, $other_field)
	{
		return ($this->{$field} !== $this->{$other_field}) ? FALSE : TRUE;
	}

	// --------------------------------------------------------------------

	/**
	 * Min Date (pre-process)
	 *
	 * Checks if the value of a property is at least the minimum date.
	 * 
	 * @access	private
	 * @param	string
	 * @param	string
	 * @return	bool
	 */
	function _min_date($field, $date)
	{
		return (strtotime($this->{$field}) < strtotime($date)) ? FALSE : TRUE;
	}

	// --------------------------------------------------------------------

	/**
	 * Max Date (pre-process)
	 *
	 * Checks if the value of a property is at most the maximum date.
	 * 
	 * @access	private
	 * @param	string
	 * @param	string
	 * @return	bool
	 */
	function _max_date($field, $date)
	{
		return (strtotime($this->{$field}) > strtotime($date)) ? FALSE : TRUE;
	}

	// --------------------------------------------------------------------

	/**
	 * Min Size (pre-process)
	 *
	 * Checks if the value of a property is at least the minimum size.
	 * 
	 * @access	private
	 * @param	string
	 * @param	integer
	 * @return	bool
	 */
	function _min_size($field, $size)
	{
		return ($this->{$field} < $size) ? FALSE : TRUE;
	}

	// --------------------------------------------------------------------

	/**
	 * Max Size (pre-process)
	 *
	 * Checks if the value of a property is at most the maximum size.
	 * 
	 * @access	private
	 * @param	string
	 * @param	integer
	 * @return	bool
	 */
	function _max_size($field, $size)
	{
		return ($this->{$field} > $size) ? FALSE : TRUE;
	}

	// --------------------------------------------------------------------

	/**
	 * Unique (pre-process)
	 *
	 * Checks if the value of a property is unique.
 	 * If the property belongs to this object, we can ignore it.
 	 *
	 * @access	private
	 * @param	string
	 * @return	bool
	 */
	function _unique($field)
	{
		if ( ! empty($this->{$field}))
		{
			$query = $this->db->get_where($this->table, array($field => $this->{$field}), 1, 0);

			if ($query->num_rows() > 0)
			{
				$row = $query->row();

				// If unique value does not belong to this object
				if ($this->id != $row->id)
				{
					// Then it is not unique
					return FALSE;
				}
			}
		}

		// No matches found so is unique
		return TRUE;
	}

	// --------------------------------------------------------------------

	/**
	 * Unique Pair (pre-process)
	 *
	 * Checks if the value of a property, paired with another, is unique.
 	 * If the properties belongs to this object, we can ignore it.
	 *
	 * @access	private
	 * @param	string
	 * @param	string
	 * @return	bool
	 */
	function _unique_pair($field, $other_field = '')
	{
		if ( ! empty($this->{$field}) && ! empty($this->{$other_field}))
		{
			$query = $this->db->get_where($this->table, array($field => $this->{$field}, $other_field => $this->{$other_field}), 1, 0);

			if ($query->num_rows() > 0)
			{
				$row = $query->row();
				
				// If unique pair value does not belong to this object
				if ($this->id != $row->id)
				{
					// Then it is not a unique pair
					return FALSE;
				}
			}
		}

		// No matches found so is unique
		return TRUE;
	}

	// --------------------------------------------------------------------

	/**
	 * Valid Date (pre-process)
	 *
	 * Checks whether the field value is a valid DateTime.
	 *
	 * @access	private
	 * @param	string
	 * @return	bool
	 */
	function _valid_date($field)
	{
		// Ignore if empty
		if (empty($this->{$field}))
		{
			return TRUE;
		}

		$date = date_parse($this->{$field});

		return checkdate($date['month'], $date['day'],$date['year']);
	}

	// --------------------------------------------------------------------

	/**
	 * Valid Date Group (pre-process)
	 *
	 * Checks whether the field value, grouped with other field values, is a valid DateTime.
	 *
	 * @access	private
	 * @param	string
	 * @param	array
	 * @return	bool
	 */
	function _valid_date_group($field, $fields = array())
	{
		// Ignore if empty
		if (empty($this->{$field}))
		{
			return TRUE;
		}

		$date = date_parse($this->{$fields['year']} . '-' . $this->{$fields['month']} . '-' . $this->{$fields['day']});

		return checkdate($date['month'], $date['day'],$date['year']);
	}

	// --------------------------------------------------------------------

	/**
	 * Valid Match (pre-process)
	 *
	 * Checks whether the field value matches one of the specified array values.
	 *
	 * @access	private
	 * @param	string
	 * @param	array
	 * @return	bool
	 */
	function _valid_match($field, $param = array())
	{
		return in_array($this->{$field}, $param);
	}

	// --------------------------------------------------------------------

	/**
	 * Encode PHP Tags (prep)
	 *
	 * Convert PHP tags to entities.
	 * This replaces the version in CI_Form_validation.
	 *
	 * @access	private
	 * @param	string
	 * @return	void
	 */	
	function _encode_php_tags($field)
	{
		$this->{$field} = encode_php_tags($this->{$field});
	}

	// --------------------------------------------------------------------

	/**
	 * Prep for Form (prep)
	 *
	 * Converts special characters to allow HTML to be safely shown in a form.
	 * This replaces the version in CI_Form_validation.
	 *
	 * @access	private
	 * @param	string
	 * @return	void
	 */	
	function _prep_for_form($field)
	{
		$this->{$field} = $this->form_validation->prep_for_form($this->{$field});
	}

	// --------------------------------------------------------------------

	/**
	 * Prep URL (prep)
	 *
	 * Adds "http://" to URLs if missing.
	 * This replaces the version in CI_Form_validation.
	 *
	 * @access	private
	 * @param	string
	 * @return	void
	 */	
	function _prep_url($field)
	{
		$this->{$field} = $this->form_validation->prep_url($this->{$field});
	}

	// --------------------------------------------------------------------

	/**
	 * Strip Image Tags (prep)
	 *
	 * Strips the HTML from image tags leaving the raw URL.
	 * This replaces the version in CI_Form_validation.
	 *
	 * @access	private
	 * @param	string
	 * @return	void
	 */	
	function _strip_image_tags($field)
	{
		$this->{$field} = strip_image_tags($this->{$field});
	}

	// --------------------------------------------------------------------

	/**
	 * XSS Clean (prep)
	 *
	 * Runs the data through the XSS filtering function, described in the Input Class page.
	 * This replaces the version in CI_Form_validation.
	 *
	 * @access	private
	 * @param	string
	 * @param	bool
	 * @return	void
	 */	
	function _xss_clean($field, $is_image = FALSE)
	{
		$this->{$field} = xss_clean($this->{$field}, $is_image);
	}

	// --------------------------------------------------------------------


	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	 *                                                                   *
	 * Common methods                                                    *
	 *                                                                   *
	 * The following are common methods used by other methods.           *
	 *                                                                   *
	 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */


	// --------------------------------------------------------------------

	/**
	 * To Array
	 *
	 * Converts this objects current record into an array for database queries.
	 * If validate is TRUE (getting by objects properties) empty objects are ignored.
	 *
	 * @access	private
	 * @param	bool
	 * @return	array
	 */
	function _to_array($validate = FALSE)
	{
		$data = array();

		foreach ($this->fields as $field)
		{
			if (empty($this->{$field}) && $validate)
			{
				continue;
			}
			
			$data[$field] = $this->{$field};
		}

		return $data;
	}

	// --------------------------------------------------------------------

	/**
	 * To Object
	 *
	 * Converts the query result into an array of objects.
	 *
	 * @access	private
	 * @param	array
	 * @param	string
	 * @return	array
	 */
	function _to_object($result, $model)
	{
		$items = array();

		foreach ($result as $row)
		{
			$item = new $model();

			foreach ($this->fields as $field)
			{
				if (isset($row->{$field}))
				{
					$item->{$field} = $row->{$field};
				}
				else
				{
					$item->{$field} = NULL;
				}
			}

			$item->_refresh_stored_values();

			$items[$item->id] = $item;
		}

		return $items;
	}

	// --------------------------------------------------------------------

	/**
	 * Refresh Stored Values
	 *
	 * Refreshes the stored values with the current values.
	 *
	 * @access	private
	 * @return	void
	 */
	function _refresh_stored_values()
	{
		// Update stored values
		foreach ($this->fields as $field)
		{
			$this->stored->{$field} = $this->{$field};
		}

		// Check if there is a "matches" validation rule
		foreach ($this->validation as $validation)
		{
			// If there is, match the field value with the other field value
			if (array_key_exists('matches', $validation['rules']))
			{
				$this->{$validation['field']} = $this->stored->{$validation['field']} = $this->{$validation['rules']['matches']};
			}
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Assign Libraries
	 *
	 * Assigns required CodeIgniter libraries to DataMapper.
	 *
	 * @access	private
	 * @return	void
	 */
	function _assign_libraries()
	{
		if ($CI =& get_instance())
		{
			// Load CodeIgniters form validation if not already loaded
			if ( ! isset($CI->form_validation))
			{
				$CI->load->library('form_validation');
			}

			$this->form_validation = $CI->form_validation;
			$this->lang = $CI->lang;
			$this->load = $CI->load;
			$this->db = $CI->db;
			$this->config = $CI->config;
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Load Languages
	 *
	 * Loads required language files.
	 *
	 * @access	private
	 * @return	void
	 */
	function _load_languages()
	{
		// Load the form validation language file
		$this->lang->load('form_validation');

		// Load the DataMapper language file
		$this->lang->load('datamapper');
	}

	// --------------------------------------------------------------------

	/**
	 * Load Helpers
	 *
	 * Loads required CodeIgniter helpers.
	 *
	 * @access	private
	 * @return	void
	 */
	function _load_helpers()
	{
		// Load inflector helper for singular and plural functions
		$this->load->helper('inflector');

		// Load security helper for prepping functions
		$this->load->helper('security');
	}
}

/* End of file datamapper.php */
/* Location: ./application/models/datamapper.php */