<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * System Application class
 *
 * enable you to render pages as Dojo application with menubars 
 * and about box plus secure appplication through permission system
 *
 * @copyright  2011 Emad Elsaid a.k.a Blaze Boy
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt   GPL License 2.0
 * @link       https://github.com/blazeeboy/Codeigniter-Egypt
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
	 */
	public $ajax = FALSE;
	
	public function __construct(){
		
		parent::__construct();
		$this->load->library('system');
		
	}
	
	/**
	 * remaps the coming requested page if the permission is valid
	 * 
	 * @param string $method the required method to be executed
	 * @param array $params the rest of URI string segments as array
	 */
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
	 *  
	 *  @param string $output the requested method output string
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
	
	/**
	 * render the passed text, it is alternative to echo
	 * please use it to add the passed text to codeigniter output
	 * it uses a view file names text.php to print the $text
	 * 
	 * @param string $text the required text to be printed
	 */
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
	 * 
	 * @param string $text text to added as message in aplication header
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
	 * 
	 * @param string $text text to be added as error in application header
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
