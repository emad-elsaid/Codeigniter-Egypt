<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * application class
 *
 * @package	Vunsy
 * @subpackage	Vunsy
 * @category	library file
 * @author	Emad Elsaid
 * @link	http://github.com/blazeeboy/vunsy
 */
class app {
	
	// application information
	var $name ='';
	var $ver = '';
	var $author = '';
	var $website = '';
	
	// application run permissions
	var $perm = '';
	
	// pages and sections of the application
	var $pages = array();
	var $index = '';
	var $page = '';
	
	var $info_msg = array();
	var $error_msg = array();
	
	// application view specifications
	var $show_toolbar = TRUE;
	var $show_title = TRUE;
	var $show_statusbar = TRUE;
	
	// application URLs and folders
	
	/* like http://localhost/system/application/views/apps/appname/
	 * to include images and javascript and css files 
	 * from your app folder
	 */
	var $full_url = '';
	
	/*like apps/appname/
	 * used to include view files
	 */
	var $view_folder ='';
	
	/* like: http://localhost/index.php/app/appname/
	 * used for links to another page in the app.
	 */
	var $url = '';
	
	/* like system/application/views/apps/appname/
	 * path from the root to the app folder
	 */
	var $ci_folder = '';
	
	/* like H:\serverfolder\ci\system\application\views\apps\appname\
	 * used to open files and write files in the application
	 */
	var $full_folder = '';
	
	function app( $data )
	{
		$this->load_app( $data['name'], $data['page'] );
	}
	
	function load_app( $name='', $page='' )
	{
		if( empty($name) )
			return FALSE;
		
		$CI =& get_instance();
		// URLs for the app
		$this->view_folder = 'apps/'.$name.'/';
		
		$u_temp = explode($name,current_url());
		$this->url = $u_temp[0] .$name.'/';
		$this->ci_folder = APP.'views/apps/'.$name.'/';
		$this->full_url = base_url().'system/application/views/apps/'.$name.'/';
		$this->full_folder = str_replace('/','\\',APPPATH).'views\\apps\\'.$name.'\\';
		if( ! file_exists( $this->full_folder) )
			$this->full_folder = str_replace('\\','/',$this->full_folder);

		$this->_get_app_data();
		
		//setting the page;
		if( ! empty($page) )
			$this->page = $page;
			
	}
	
	function _get_app_data()
	{
		$CI =& get_instance();
		
		if( !function_exists('read_file') )
			$CI->load->helper('file');
		
		$ini = read_file($this->full_folder.'app.json');
		$ini = json_decode($ini);
		
		if( ! is_object($ini) ) 
			show_error( "Not a valid application, expected error in JSON file" );
		//copy variables to this object
		foreach( $ini as $key=>$value )
			$this->$key = $value;
			
		$this->page = $this->index;
			
	}
	
	function render()
	{
		
		if(!$this->can_view())
			show_error('Access denied');
			
		// getting the page itself
		$CI =& get_instance();
		
		$p = $this->page;
		if( isset($this->pages->$p) ) 
		{
			$page_text = $CI->load->view( $this->view_folder.$this->pages->$p, '', TRUE);
		}
		else
		{
			$page_text = $CI->load->view( $this->view_folder.$p, '', TRUE );
		}
		
		
		echo $CI->load->view(
					"edit_mode/app",
					array( "app"=> &$this, "content"=>$page_text),
					TRUE 
				);	
		
	}
	
	function add_css( $path="", $local=FALSE )
	{
		if( $local )
			$path = $this->full_url.$path;
		
		add_css( $path );
	}
	
	function add_js( $path="", $local=FALSE  )
	{
		if( $local )
			$path = $this->full_url.$path;
		
		add_js( $path );
	}
	
	function add_dojo( $path="" )
	{
		add_dojo( $path );
	}
	
	
	function css_text()
	{
		$CI =& get_instance();
		return $CI->vunsy->css_text();
	}
	
	function js_text()
	{
		$CI =& get_instance();
		return $CI->vunsy->js_text();
	}
	
	function dojo_text()
	{
		$CI =& get_instance();
		return $CI->vunsy->dojo_text();
	}
	
	function header_text()
	{
		$CI =& get_instance();
		return $CI->vunsy->header_text();
	}
	
	function can_view()
	{
		return perm_chck( $this->perm );
	}
	
	function app_url( $p='' )
	{
		if( ! empty($p) )
		{
			return $this->url.$p;
		}
		else
		{
			return FALSE;
		}
	}
	
	function add_info( $text='' )
	{
		if( is_array($text) )
		{
			foreach( $text as $item )
				$this->add_info( $item );
		}
		else
		{		
			array_push( $this->info_msg, $text);
		}
	}
	
	function add_error( $text='' )
	{
		if( is_array($text) )
		{
			foreach( $text as $item )
				$this->add_error( $item );
		}
		else
		{
			array_push( $this->error_msg, $text);
		}
	}
	
	function info_text()
	{
		$t = '';
		$CI =& get_instance();
		$CI->load->library('gui');
		foreach( $this->info_msg as $item )
			$t .= $CI->gui->info( $item );
		
		return $t;
	}
	
	function error_text()
	{
		$t = '';
		$CI =& get_instance();
		if( ! isset($CI->gui) ) $CI->load->library( 'gui' );
		foreach( $this->error_msg as $item )
			$t .= $CI->gui->error( $item );
		
		return $t;
	}
}
