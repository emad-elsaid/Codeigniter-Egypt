<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('add_js')){
	function add_js( $script = '' )
	{
		if(is_array( $script ))
		{
			foreach( $script as $item )
				add_js( $item );
		}
		else
		{
			$CI =& get_instance();
			array_push( $CI->vunsy->js, $script );
		}
	}
}

if ( ! function_exists('add_css')){
	function add_css( $style = '' )
	{
		if(is_array( $style ))
		{
			foreach( $style as $item )
				add_js( $item );
		}
		else
		{
			$CI =& get_instance();
			array_push( $CI->vunsy->css, $style );
		}
	}
}
