<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Edit mode change app between Edit/view 
 *
 * this application contain 2 pages that change 
 * system mode between edit mode and view mode
 * edit mode gives the user ability to add/remove/update contents
 * view mode is the normal website mode
 *
 * @copyright  2011 Emad Elsaid a.k.a Blaze Boy
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt   GPL License 2.0
 * @link       https://github.com/blazeeboy/Codeigniter-Egypt
 */ 
class Editmode extends Application {
	
	public function __construct(){
		
		parent::__construct();
		
		$this->perm 	= 'admin';
		$this->name 	= lang('system_edit_mode_changer');
		$this->author 	= 'Emad Elsaid';
		$this->website 	= 'http://blazeeboy.blogspot.com';
		$this->version 	= '0.1';
		$this->pages 	= array(
						'edit' => lang('system_edit_mode')
						,'view' => lang('system_view_mode')
						);

	}
	
	/**
	 * change system mode to edit mode
	 * 
	 * @return void
	 */
	public function edit(){
		
		$this->system->set_mode ('edit');
		$this->load->library( 'gui' );
		$text = anchor( site_url('editmode/view') , lang('system_switch_to_view_mode') );
		$this->print_text('<center>'.$text.'</center>');
		
	}
	
	/**
	 * change system mode into view mode
	 * 
	 * @return void
	 */
	public function view(){
		
		$this->system->set_mode('view');
		$this->load->library( 'gui' );
		$text = anchor( site_url('editmode/edit') , lang('system_switch_to_view_mode') );
		$this->print_text('<center>'.$text.'</center>');
	
	}
}