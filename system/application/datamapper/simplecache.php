<?php

/**
 * SimpleCache Extension for DataMapper classes.
 *
 * Allows the usage of CodeIgniter query caching on DataMapper queries.
 *
 * @license 	MIT License
 * @category	DataMapper Extensions
 * @author  	Phil DeJarnett
 * @link    	http://www.overzealous.com/dmz/
 * @version 	1.0
 */

// --------------------------------------------------------------------------

/**
 * DMZ_SimpleCache Class
 */
class DMZ_SimpleCache {
	
	/**
	 * Allows CodeIgniter's caching method to cache large result sets.
	 * Call it exactly as get();
	 * 
	 * @return The DataMapper $object for chaining.
	 * @param object $object The DataMapper Object.
	 */
    function get_cached($object)
	{
        if( ! empty($object->_should_delete_cache) )
		{
            $object->db->cache_delete();
            $object->_should_delete_cache = FALSE;
        }
		
		$object->db->cache_on();
		// get the arguments, but pop the object.
		$args = func_get_args();
		array_shift($args);
		call_user_func_array(array($object, 'get'), $args);
        $object->db->cache_off();
        return $object;
    }

    /**
     * Clears the cached query the next time get_cached is called.
     * 
     * @return  The DataMapper $object for chaining.
     * @param object $object The DataMapper Object.
     */
    function clear_cache($object)
	{
		$args = func_get_args();
		array_shift($args);
		if( ! empty($args)) {
			call_user_func_array(array($object->db, 'cache_delete'), $args);
		} else {
	        $object->_should_delete_cache = TRUE;
		}
        return $object;
    }
	
}

/* End of file simplecache.php */
/* Location: ./application/datamapper/simplecache.php */
