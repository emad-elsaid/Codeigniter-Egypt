<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * permission helper
 *
 * it is responsible for check all system permissions
 *
 * @copyright  2011 Emad Elsaid a.k.a Blaze Boy
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt   GPL License 2.0
 * @link       https://github.com/blazeeboy/Codeigniter-Egypt
 * @package Helpers
 */ 
if ( ! function_exists('perm_array')){
	
	/**
	 * create all permission variables array and return it
	 * 
	 * @return array 3 keys (opers : the operators, boolVars: the boolean variables, vars: the numeric variables
	 */
	function perm_array(){
		
		$CI =& get_instance();
		return array(
			'opers' => array(
						'='			=> '==',
						'>=='		=> '>=',
						'<=='		=> '<=',
						'!=='		=> '!=',
						'not'		=> '!'
			),
			'boolVars' => array(
						'admin'		=> intval($CI->ion_auth->is_admin()).'=1',
						'logged'	=> intval($CI->ion_auth->logged_in()).'=1',
						'guest'		=> intval(!$CI->ion_auth->logged_in()).'=1',
						'view'		=> intval($CI->system->mode=='view').'=1',
						'edit'		=> intval($CI->system->mode=='edit').'=1'
			),
			'vars' => array(
						'group'		=> $CI->system->group->id,
						'user'		=> $CI->system->user->id,
						'section'	=> $CI->system->section->id,
						'day'		=> date('j'),
						'month'		=> date('n'),
						'year'		=> date('Y')
			)
		);
	}
}


if ( ! function_exists('perm_chck')){
	
	/**
	 * check permission validation using current system settings
	 * 
	 * @param string $perms the permission boolean expression
	 */
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
