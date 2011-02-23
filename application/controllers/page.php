<?php
/** \addtogroup Controllers
 * Page controller that shows current
 * section from vunsy object
 *
 * @package	Vunsy
 * @subpackage	Vunsy
 * @category	controller file
 * @author	Emad Elsaid
 * @link	http://github.com/blazeeboy/vunsy
 */

class Page extends CI_Controller
{
	/**
	 * Page controller it's just like the Admin controller, it loads the current
	 * page and render it or display the permission denied mesage
	 * */
	function __construct()
	{
		parent::__construct();
		$this->load->library('vunsy');
	}
	
	/**
	 * that function loads the vunsy current section and print it
	 * if that user has the permission to view it or display
	 * a permission denied message
	 * */
	function index()
	{
		if( $this->vunsy->section->can_view() )
			echo $this->vunsy->section->render();
		else
			show_error( "Permission denied" );
	}
	
}
