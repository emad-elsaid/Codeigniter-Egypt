<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * permission helper functions
 *
 * @package	Vunsy
 * @subpackage	Vunsy
 * @category	helper file
 * @author	Emad Elsaid
 * @link	http://github.com/blazeeboy/vunsy
 */
if ( ! function_exists('perm_array')){
	function perm_array()
	{
		$CI =& get_instance();
		return array(
			'opers' => array(
						'='		=> '==',
						'>=='		=> '>=',
						'<=='		=> '<=',
						'!=='		=> '!=',
						'not'		=> '!'
			),
			'boolVars' => array(
						'admin'		=> intval($CI->ion_auth->is_admin()).'=1',
						'logged'	=> intval($CI->ion_auth->logged_in()).'=1',
						'guest'		=> intval(!$CI->ion_auth->logged_in()).'=1',
						'view'		=> intval($CI->vunsy->view_mode()).'=1',
						'edit'		=> intval($CI->vunsy->edit_mode()).'=1'
			),
			'vars' => array(
						'level'		=> $CI->session->userdata('level'),
						'user'		=> $CI->session->userdata('id'),
						'section'	=> $CI->vunsy->section->id,
						'day'		=> date('j'),
						'month'		=> date('n'),
						'year'		=> date('Y')
			)
		);
	}
}


if ( ! function_exists('perm_chck')){
	
	function perm_chck($perms=''){
		
		$CI =& get_instance();
		// if the user is admin then the result would be TRUE
		if($CI->ion_auth->is_admin()) return TRUE;
		
		$perm_vars = perm_array();
		
		$perms = str_replace( 
					array_keys		($perm_vars['boolVars']),
					array_values	($perm_vars['boolVars']),
					$perms
					);
		$vars_reg	= implode('|', array_keys($perm_vars['vars']));
		$matches	= array();
		
		
		// regular expression to ckeck is the permission string
		// is in the wanted form or not .... i know it's a little missy
		// it will extract the valid regex to matches array
		preg_match( "/(?:(?(3)\s*\b(and|or|not)\b\s+)(?:(not)\s+)?({$vars_reg}|0|1)\s*([<>]=?|!?=)\s*(\d+(?:\.\d*)?|\.\d+|'[^']*'))+/", $perms, $matches );
	
		$matches = @$matches[0];
		//no matches found (BAD OR EMPTY $perm)
		if( empty($matches) ) return FALSE;
		
		// replace operators with valid operators like = with ==
		$matches = str_replace(
					array_keys		($perm_vars['opers']),
					array_values	($perm_vars['opers']),
					$matches
					);
		
		// replace variables with it's values
		$matches = str_replace(
					array_keys		($perm_vars['vars']),
					array_values	($perm_vars['vars']),
					$matches
					);
		
		// evaluate the expression to get a boolean value
		$result = FALSE;
		eval('$result= ('.$matches.');');
		return $result;
	}
}
