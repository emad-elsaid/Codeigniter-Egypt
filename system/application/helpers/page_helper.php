<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * page helper functions
 * to add javascripts ans css links 
 * to the page header
 *
 * @package	Vunsy
 * @subpackage	Vunsy
 * @category	helper file
 * @author	Emad Elsaid
 * @link	http://github.com/blazeeboy/vunsy
 */
if ( ! function_exists('add_js')){
	function add_js( $script = '' )
	{
		
		/* if the paramter an array then pass
		 * each element to the array again .
		 * */
		if(is_array( $script ))
		{
			foreach( $script as $item )
				add_js( $item );
		}
		
		/* is the paramter not an array then it's a string
		 * we add it to vunsy javascript array
		 * */
		else
		{
			/* get full URL if script path
			 * is local script
			 * */
			$CI =& get_instance();
			if( is_local($script) )
			{
				$script = base_url().$script;
			}
			
			// push script to vunsy JS array . 
			if(! in_array($script, $CI->vunsy->js))
				array_push( $CI->vunsy->js, $script );
		}
	}
}

if ( ! function_exists('add_css')){
	function add_css( $style = '', $position = '' )
	{
		/* if the paramter an array then pass
		 * each element to the array again .
		 * */
		if(is_array( $style ))
		{
			foreach( $style as $item )
				add_css( $item, $position );
		}
		
		/* is the paramter not an array then it's a string
		 * we add it to vunsy javascript array
		 * */
		else
		{
			$CI =& get_instance();
			
			switch( $position )
			{
				case 'first':
					if(! in_array($style, $CI->vunsy->css_first))
						array_push( $CI->vunsy->css_first, $style );
					break;
				case 'last':
					if(! in_array($style, $CI->vunsy->css_last))
						array_push( $CI->vunsy->css_last, $style );
					break;
				default:
					if(! in_array($style, $CI->vunsy->css))
						array_push( $CI->vunsy->css, $style );
			}
		}
	}
}

if ( ! function_exists('add_dojo')){
	function add_dojo( $req = '' )
	{
		
		/* if the paramter an array then pass
		 * each element to the array again .
		 * */
		if(is_array( $req ))
		{
			foreach( $req as $item )
				add_dojo( $item );
		}
		
		/* is the paramter not an array then it's a string
		 * we add it to vunsy javascript array
		 * */
		else
		{
			$CI =& get_instance();
			
			if(! in_array($req, $CI->vunsy->dojo))
				array_push( $CI->vunsy->dojo, $req );
				
		}
	}
}

if ( ! function_exists('is_local')){
	function is_local( $path )
	{
		$start =  str_split($path,7);
		if( $start[0] == "http://" )
			return FALSE;
		else
			return TRUE;
	}
}

