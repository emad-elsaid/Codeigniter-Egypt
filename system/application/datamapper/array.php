<?php

/**
 * Array Extension for DataMapper classes.
 *
 * Quickly convert DataMapper models to-and-from PHP arrays.
 *
 * @license 	MIT License
 * @category	DataMapper Extensions
 * @author  	Phil DeJarnett
 * @link    	http://www.overzealous.com/dmz/
 * @version 	1.0
 */

// --------------------------------------------------------------------------

/**
 * DMZ_Array Class
 */
class DMZ_Array {
	
	/**
	 * Convert a DataMapper model into an associative array.
	 * 
	 * @return An associative array.
	 * @param object $object The DataMapper Object to convert
	 * @param array $fields[optional] Array of fields to include.  If empty, includes all database columns.
	 */
	function to_array($object, $fields = '')
	{
		// assume all database columns if $fields is not provided.
		if(empty($fields))
		{
			$fields = $object->fields;
		}
		
		$result = array();
		
		foreach($fields as $f)
		{
			// handle related fields
			if(array_key_exists($f, $object->has_one) || array_key_exists($f, $object->has_many))
			{
				// each related item is stored as an array of ids
				// Note: this method will NOT get() the related object.
				$rels = array();
				foreach($object->{$f}->all as $item)
				{
					$rels[] = $item->id;
				}
				$result[$f] = $rels;
			}
			else
			{
				// just the field.
				$result[$f] = $object->{$f};
			}
		}
		
		return $result;
	}
	
	/**
	 * Convert the entire $object->all array result set into an array of associative arrays.
	 * 
	 * @return An array of associative arrays.
	 * @param object $object The DataMapper Object to convert
	 * @param array $fields[optional] Array of fields to include.  If empty, includes all database columns.
	 */
	function all_to_array($object, $fields = '')
	{
		// loop through each object in the $all array, convert them to
		// an array, and add them to a new array.
		$result = array();
		foreach($object->all as $o)
		{
			$result[] = $o->to_array($fields);
		}
		return $result;
	}
	
	/**
	 * Convert an associative array back into a DataMapper model.
	 * 
	 * If $fields is provided, missing fields are assumed to be empty checkboxes.
	 * 
	 * @return A list of newly related objects, or the result of the save if $save is TRUE
	 * @param object $object The DataMapper Object to save to.
	 * @param string $data A an associative array of fields to convert.
	 * @param array $fields[optional] Array of 'safe' fields.  If empty, only includes the database columns.
	 */
	function from_array($object, $data, $fields = '', $save = FALSE)
	{
		// keep track of newly related objects
		$new_related_objects = array();
		
		// Assume all database columns.
		// In this case, simply store $fields that are in the $data array.
		if(empty($fields))
		{
			$fields = $object->fields;
			foreach($data as $k => $v) {
				if(in_array($k, $fields))
				{
					$object->{$k} = $v;
				}
			}
		}
		else
		{
			// If $fields is provided, assume all $fields should exist.
			foreach($fields as $f)
			{
				if(array_key_exists($f, $object->has_one))
				{
					// Store $has_one relationships
					$c = get_class($object->{$f});
					$rel = new $c();
					$id = isset($data[$f]) ? $data[$f] : 0;
					$rel->get_by_id($id);
					if($rel->exists())
					{
						// The new relationship exists, save it.
						$new_related_objects[$f] = $rel;
					}
					else
					{
						// The new relationship does not exist, delete the old one.
						 $object->delete($object->{$f}->get());
					}
				}
				else if(array_key_exists($f, $object->has_many))
				{
					// Store $has_many relationships
					$c = get_class($object->{$f});
					$rels = new $c();
					$ids = isset($data[$f]) ? $data[$f] : FALSE;
					if(empty($ids))
					{
						// if no IDs were provided, delete all old relationships.
						$object->delete($object->{$f}->select('id')->get()->all);
					}
					else
					{
						// Otherwise, get the new ones...
						$rels->where_in('id', $ids)->select('id')->get();
						// Store them...
						$new_related_objects[$f] = $rels->all;
						// And delete any old ones that do not exist.
						$old_rels = $object->{$f}->where_not_in('id', $ids)->select('id')->get();
						$object->delete($old_rels->all);
					}
				}
				else
				{
					// Otherwise, if the $data was set, store it...
					if(isset($data[$f]))
					{
						$v = $data[$f];
					}
					else
					{
						// Or assume it was an unchecked checkbox, and clear it.
						$v = FALSE;
					}
					$object->{$f} = $v; 
				}
			}
		}
		if($save)
		{
			// Auto save
			return $object->save($new_related_objects);
		}
		else
		{
			// return new objects
			return $new_related_objects;
		}
	}
	
}

/* End of file array.php */
/* Location: ./application/datamapper/array.php */
