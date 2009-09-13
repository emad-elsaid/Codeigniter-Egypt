<?php

/**
 * CSV Extension for DataMapper classes.
 *
 * Quickly import and export a set of DataMapper models to-and-from CSV files.
 *
 * @license 	MIT License
 * @category	DataMapper Extensions
 * @author  	Phil DeJarnett
 * @link    	http://www.overzealous.com/dmz/
 * @version 	1.0
 */

// --------------------------------------------------------------------------

/**
 * DMZ_CSV Class
 */
class DMZ_CSV {
	
	/**
	 * Convert a DataMapper model into an associative array.
	 * 
	 * @param object $object The DataMapper Object to export
	 * @param mixed filename The filename to export to, or a file pointer. If this is a file pointer, it will not be closed.
	 * @param array $fields[optional] Array of fields to include.  If empty, includes all database columns.
	 * @return TRUE on success, or FALSE on failure.
	 */
	function csv_export($object, $filename, $fields = '', $include_header = TRUE)
	{
		// determine the correct field set.
		if(empty($fields))
		{
			$fields = $object->fields;
		}
		
		$success = TRUE;
		
		// determine if we need to open the file or not.
		if(is_string($filename))
		{
			// open the file, if possible.
			$fp = fopen($filename, 'w');
			if($fp === FALSE)
			{
				log_message('error', 'CSV Extension: Unable to open file ' . $filename);
				return FALSE;
			}
		}
		else
		{
			// assume file pointer.
			$fp = $filename;
		}
		
		if($include_header)
		{
			// Print out header line
			$success = fputcsv($fp, $fields);
		}
		
		if($success)
		{
			foreach($object->all as $o)
			{
				// convert each object into an array
				$result = array();
				foreach($fields as $f)
				{
					$result[] = $o->{$f};
				}
				// output CSV-formatted line
				$success = fputcsv($fp, $result);
				if(!$success)
				{
					// stop on first failure.
					break;
				}
			}
		}
		
		if(is_string($filename))
		{
			fclose($fp);
		}
		
		return $success;
	}
	
	/**
	 * Import objects from a CSV file.
	 * 
	 * Completely empty rows are automatically skipped, as are rows that
	 * start with a # sign (assumed to be comments).
	 * 
	 * @param object $object The type of DataMapper Object to import
	 * @param mixed $filename Name of CSV file.
	 * @param array $fields [optional] If empty, the database fields are used.  Otherwise used to limit what fields are saved.
	 * @param boolean $header_row [optional] If true, the first line is assumed to be a header row.  Defaults to true.
	 * @param object $auto_save [optional] If true, each object will automatically be saved.
	 * 										You should still check each object for errors.
	 * 										The result of the save is stored as $result[$index]->csv_import_result
	 * @return Array of imported objects, or FALSE if unable to import.
	 */
	function csv_import($object, $filename, $fields = '', $header_row = TRUE, $callback = NULL)
	{
		$class = get_class($object);
		
		if(empty($fields))
		{
			$fields = $object->fields;
		}
		
		// determine if we need to open the file or not.
		if(is_string($filename))
		{
			// open the file, if possible.
			$fp = fopen($filename, 'r');
			if($fp === FALSE)
			{
				log_message('error', 'CSV Extension: Unable to open file ' . $filename);
				return FALSE;
			}
		}
		else
		{
			// assume file pointer.
			$fp = $filename;
		}
		
		if(empty($callback))
		{
			$result = array();			
		}
		else
		{
			$result = 0;
		}
		$columns = NULL;
		
		while(($data = fgetcsv($fp)) !== FALSE)
		{
			// get column names
			if(is_null($columns))
			{
				if($header_row)
				{
					// store header row for column names
					$columns = $data;
					// only include columns in $fields
					foreach($columns as $index => $name)
					{
						if( ! in_array($name, $fields))
						{
							// mark column as false to skip
							$columns[$index] = FALSE;
						}
					}
					continue;
				}
				else
				{
					$columns = $fields;
				}
			}
			
			// skip on comments and empty rows
			if(empty($data) || $data[0][0] == '#' || implode('', $data) == '')
			{
				continue;
			}
			
			// create the object to save
			$o = new $class();
			foreach($columns as $index => $key)
			{
				if(count($data) <= $index)
				{
					// more header columns than data columns
					break;
				}
				
				// skip columns that were determined to not be needed above.
				if($key === FALSE)
				{
					continue;
				}
				
				// finally, it's OK to save the data column.
				$o->{$key} = $data[$index];
			}
			
			if( empty($callback))
			{
				$result[] = $o;
			}
			else
			{
				$test = call_user_func($callback, $o);
				if($test === 'stop')
				{
					break;
				}
				if($test !== FALSE)
				{
					$result++;
				}
			}
		}
		
		if(is_string($filename))
		{
			fclose($fp);
		}
		
		return $result;
	}
	
}

/* End of file csv.php */
/* Location: ./application/datamapper/csv.php */
