<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/** \addtogroup Libraries 
 * application class: application class that loads and render application pages
 *
 * @package	Codeigniter-Egypt
 * @subpackage	Codeigniter-Egypt
 * @category	Core Class
 * @author	Emad Elsaid
 * @link	http://github.com/blazeeboy/Codeigniter-Egypt
 */
class Application extends CI_Controller {
	
	/**
	 * application name
	 */
	public $name ='';
	/**
	 *  application current version
	 */
	public $version = '';
	/**
	 *  application author name
	 */
	public $author = '';
	/**
	 *  application website or author's website
	 */
	public $website = '';
	
	/**
	 *  application run permissions, if it's true the application will run.
	 * otherwise application will raise permission denied message
	 */ 
	public $perm = '';
	
	public $pages = array();
	
	/**
	 * @var String : the current page title
	 */
	public $page = '';
	
	/**
	 *  array or information messages to display in head of the page
	 */
	public $info_msg = array();
	
	/**
	 *  array or error messages to display in head of the page
	 */
	public $error_msg = array();
	
	/**
	 *  show hide menubar
	 */ 
	public $show_toolbar = TRUE;
	
	
	/**
	 * detemine if the page rendered as ajax page or not
	 * if true the page return will be printed only without HTML
	 * default application container(html,head,body,js,css)
	 * else all the HTML page will be returned
	 * */
	public $ajax = FALSE;
	
	public function __construct(){
		
		parent::__construct();
		$this->load->library('system');
		
	}
	
	public function _remap($method, $params = array()){
		
		if( !perm_chck($this->perm) )
			show_error('Permission Denied');
		
		$this->page = array_key_exists($method,$this->pages)
						? $this->pages[$method]
						: $method;
						
		if (method_exists($this, $method))
	    {
	        return call_user_func_array(array($this, $method), $params);
	    }
    		show_404();
    		
	}
	/**
	 *  render the application page and return the produced HTML
	 */
	public function _output( $output ){
		
		theme_pagetitle($this->name);
		if( $this->ajax )
			echo $output;
		else
			echo $this->load->view(
					"edit_mode/app",
					array( "app"=> &$this, "content"=>$output),
					TRUE 
				);
		
	}
	
	public function print_text( $text ){
		
		$this->load->view( 'text', array('text'=>$text) );
		
	}
	
	/**
	 * check if the that user can use the application
	 */
	public function can_view(){
		
		return perm_chck( $this->perm );
		
	}
	
	/**
	 * add information message to application, to be displyed at top of the page
	 */
	public function add_info( $text='' ){
		
		if( is_array($text) )
			foreach( $text as $item )
				$this->add_info( $item );
		else
			array_push( $this->info_msg, $text);
			
	}
	
	/**
	 * add Error message to application, to be displyed at top of the page
	 */
	public function add_error( $text='' ){
		
		if( is_array($text) )
			foreach( $text as $item )
				$this->add_error( $item );
		else
			array_push( $this->error_msg, $text);
	}
	
	/**
	 * generate the HTML of information 
	 * messages to be added at the top of the page
	 */
	public function info_text(){
		
		$t = '';
		$this->load->library('gui');
		foreach( $this->info_msg as $item )
			$t .= $this->gui->info( $item );
		
		return $t;
	}
	
	/**
	 * generate the HTML of error
	 * messages to be added at the top of the page
	 */
	public function error_text(){
		
		$t = '';
		if( ! isset($this->gui) ) $this->load->library( 'gui' );
		foreach( $this->error_msg as $item )
			$t .= $this->gui->error( $item );
		
		return $t;
	}
}
