<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * the main system controller that view current section
 *
 * that controller contains one page, it get currrent section
 * and render it to client
 *
 * @copyright  2011 Emad Elsaid a.k.a Blaze Boy
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt   GPL License 2.0
 * @link       https://github.com/blazeeboy/Codeigniter-Egypt
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
