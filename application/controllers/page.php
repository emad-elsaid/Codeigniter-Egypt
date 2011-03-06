<?php
/** \addtogroup Controllers
 * Page controller that shows current
 * section from system object
 *
 * @package	Codeigniter-Egypt
 * @subpackage	Codeigniter-Egypt
 * @category	controller file
 * @author	Emad Elsaid
 * @link	http://github.com/blazeeboy/Codeigniter-Egypt
 */

class Page extends CI_Controller{
	/**
	 * Page controller it's just like the Admin controller, it loads the current
	 * page and render it or display the permission denied mesage
	 * */
	public function __construct(){
		parent::__construct();
		$this->load->library('system');
	}
	
	/**
	 * that function loads the system current section and print it
	 * if that user has the permission to view it or display
	 * a permission denied message
	 * */
	public function index(){
		$this->system->section->render();
	}
}
