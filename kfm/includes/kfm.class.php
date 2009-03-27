<?php
/**
 * Base kfm class
 */
class kfmBase extends kfmObject{
	var $doctype='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
	var $settings=array();

	/**
	 * setting function, returns a configuration parameter if one config is given, 
	 * sets a config parameter if two parameters are given
	 * @param $name
	 * @param $value optional
	 * 
	 * @return $value
	 */
	function setting($name,$value='novaluegiven'){
		if($value=='novaluegiven'){
			if(!isset($this->settings[$name]))return $this->error('Setting '.$name.' does not exists');
			return $this->settings[$name];
		}
		$this->settings[$name]=$value;
	}

	function defaultSetting($name, $value){
		if(!isset($this->settings[$name]))$this->settings[$name]=$value;
	}

	/**
	 * returns a parameter, returns the default if not present
	 * @param $parameter
	 * @param $default
	 * @return $value || $default if not present
	 */
	function getParameter($parameter, $default=false){

	}

	/**
	 * sets a parameter
	 * @param $parameter parameter name
	 * @param $value parameter value
	 * @return true on success || false on error
	 */
	function setParameter($parameter, $value){

	}
}
$kfm=new kfmBase();
