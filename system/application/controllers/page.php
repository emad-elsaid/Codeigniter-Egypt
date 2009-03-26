<?php
/**
 * page controller that shows current
 * section from vunsy object
 *
 * @package	Vunsy
 * @subpackage	Vunsy
 * @category	controller file
 * @author	Emad Elsaid
 * @link	http://github.com/blazeeboy/vunsy
 */
class Page extends Controller
{
	function Page()
	{
		parent::Controller();
	}
	
	function index()
	{
		if( $this->vunsy->section->can_view() )
			echo $this->vunsy->section->render();
		else
			show_error( " Permission denied" );
	}
	
}
