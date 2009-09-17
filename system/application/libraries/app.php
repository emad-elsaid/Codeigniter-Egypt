<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/** \addtogroup Libraries 
 * app class: application class that loads and render application pages
 *
 * @package	Vunsy
 * @subpackage	Vunsy
 * @category	library file
 * @author	Emad Elsaid
 * @link	http://github.com/blazeeboy/vunsy
 */
class app {
	
	/**
	 * application name
	 */
	var $name ='';
	/**
	 *  application current version
	 */
	var $ver = '';
	/**
	 *  application author name
	 */
	var $author = '';
	/**
	 *  application website or author's website
	 */
	var $website = '';
	
	/**
	 *  application run permissions, if it's true the application will run.
	 * otherwise application will raise permission denied message
	 */ 
	var $perm = '';
	
	/**
	 *  pages and sections of the application
	 *  it's an array Key(page title ) => value(file name)
	 *  and you have to write filename without extension 
	 *  if the required page not in that array the file name 
	 *  will be displayed as title
	 */
	var $pages = array();
	
	/**
	 *  index page filename or key from $pages
	 */
	var $index = '';
	
	/**
	 *  current running page title
	 */
	var $page = '';
	
	/**
	 *  array or information messages to display in head of the page
	 */
	var $info_msg = array();
	
	/**
	 *  array or error messages to display in head of the page
	 */
	var $error_msg = array();
	
	/**
	 *  show hide menubar
	 */ 
	var $show_toolbar = TRUE;
	
	/**
	 *  show hide titlebar
	 */
	var $show_title = TRUE;
	
	/**
	 *  show hide statusbar
	 */
	var $show_statusbar = TRUE;
	
	/**
	 *  like http://localhost/system/application/views/apps/appname/
	 * to include images and javascript and css files 
	 * from your app folder
	 */
	var $full_url = '';
	
	/**
	 * like apps/appname/
	 * used to include view files
	 */
	var $view_folder ='';
	
	/**
	 *  like: http://localhost/index.php/app/appname/
	 * used for links to another page in the app.
	 */
	var $url = '';
	
	/**
	 *  like: http://localhost/index.php/app/ajax/appname/
	 * used for links to another page in the app with ajax.
	 */
	var $ajax_url = '';
	
	/**
	 *  like system/application/views/apps/appname/
	 * path from the root to the app folder
	 */
	var $ci_folder = '';
	
	/**
	 *  like H:\serverfolder\ci\system\application\views\apps\appname\
	 * used to open files and write files in the application
	 */
	var $full_folder = '';
	
	/**
	 * detemine if the page rendered as ajax page or not
	 * if true the page return will be printed only without HTML
	 * default application container(html,head,body,js,css)
	 * else all the HTML page will be returned
	 * */
	var $ajax = FALSE;
	
	function app( $data )
	{
		if( ! $this->load_app( $data['name'], $data['page'] ) )
			show_404( '' );
	}
	
	/**
	 *  load application page
	 * @param:
	 * 		$data: array that has name: for application name, page: the pag titlee to load
	 */
	function load_app( $name='', $page='' )
	{
		if( empty($name) )
			return FALSE;
		
		$CI =& get_instance();
		// URLs for the app
		$this->view_folder = 'apps/'.$name.'/';
		
		$u_temp = explode($name,current_url());
		$this->url = $u_temp[0] .$name.'/';
		$this->ajax_url = site_url( 'admin/ajax/'.$name ).'/';
		$this->ci_folder = APP.'views/apps/'.$name.'/';
		$this->full_url = base_url().'system/application/views/apps/'.$name.'/';
		$this->full_folder = str_replace('/','\\',APPPATH).'views\\apps\\'.$name.'\\';
		if( ! file_exists( $this->full_folder) )
			$this->full_folder = str_replace('\\','/',$this->full_folder);

		$this->_get_app_data();
		
		//setting the page;
		if( ! empty($page) )
			$this->page = $page;
		
		return TRUE;	
	}
	
	/**
	 *  load application JSON file and sync with object
	 */
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
	
	/**
	 *  render the application page and return the produced HTML
	 */
	function render()
	{
		
		if(!$this->can_view())
			show_error('Access denied');
			
		// getting the page itself
		$CI =& get_instance();
		
		$p = $this->page;
		if( isset($this->pages->$p) )
		{
			$page_text = $CI->load->view( 
						$this->view_folder.$this->pages->$p,
						array('ci'=>$CI),
						TRUE
					);
		}
		else
		{
			$page_text = $CI->load->view(
						$this->view_folder.$p,
						array('ci'=>$CI),
						TRUE
					);
		}
		
		if( $this->ajax )
			return $page_text;
		else
			return $CI->load->view(
					"edit_mode/app",
					array( "app"=> &$this, "content"=>$page_text),
					TRUE 
				);
		
	}
	
	/**
	 *  add css file to the page head
	 * @param
	 * 		$path: file path relative to index.php
	 * 		$local: if true the put $path relative to application folder
	 */
	function add_css( $path="", $local=FALSE )
	{
		if( $local and is_array($path) )
			$path = $this->full_url.$path;
		
		add_css( $path );
	}
	
	/**
	 *  add javascript file to the page head
	 * @param
	 * 		$path: file path relative to index.php
	 * 		$local: if true the put $path relative to application folder
	 */
	function add_js( $path="", $local=FALSE  )
	{
		if( $local and is_array($path) )
			$path = $this->full_url.$path;
		
		add_js( $path );
	}
	
	/**
	 *  add dojo reuired to the page head
	 * @param
	 * 		$path: dojo widget path like: dijit.Form.Button
	 */
	function add_dojo( $path="" )
	{
		add_dojo( $path );
	}
	
	/**
	 *  add css,js,dojo,headblock to the page head
	 *  you can use it instead of add_js, add_css, ass_dojo
	 * @param
	 * 		$path: file path relative to index.php
	 * 		$local: if true the put $path relative to application folder
	 */
	function add( $path="", $local=FALSE )
	{
		if( $local and is_array($path) )
			$path = $this->full_url.$path;
		
		add( $path );
	}
	
	/**
	 *  returns css head text from vunsy library
	 */
	function css_text()
	{
		$CI =& get_instance();
		return $CI->vunsy->css_text();
	}
	
	/**
	 * returns javascript head text from vunsy library 
	 */
	function js_text()
	{
		$CI =& get_instance();
		return $CI->vunsy->js_text();
	}
	
	/**
	 * returns dojo required text from vunsy library 
	 */
	function dojo_text()
	{
		$CI =& get_instance();
		return $CI->vunsy->dojo_text();
	}
	
	/**
	 * returns head text from vunsy library 
	 */
	function header_text()
	{
		$CI =& get_instance();
		return $CI->vunsy->header_text();
	}
	
	/**
	 * check if the that user can use the application
	 */
	function can_view()
	{
		return perm_chck( $this->perm );
	}
	
	/**
	 * returns a url for a page in the current application
	 * @param
	 * 		$p: page title or page filename
	 */
	
	function app_url( $p='', $ajax=FALSE )
	{
		if( $ajax )
		{
			if( ! empty($p) )
				return $this->ajax_url.$p;
			else
				return FALSE;
		}
		else
		{
			if( ! empty($p) )
				return $this->url.$p;
			else
				return FALSE;
		}
	}
	
	/**
	 * add information message to application, to be displyed at top of the page
	 */
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
	
	/**
	 * add Error message to application, to be displyed at top of the page
	 */
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
	
	/**
	 * generate the HTML of information 
	 * messages to be added at the top of the page
	 */
	function info_text()
	{
		$t = '';
		$CI =& get_instance();
		$CI->load->library('gui');
		foreach( $this->info_msg as $item )
			$t .= $CI->gui->info( $item );
		
		return $t;
	}
	
	/**
	 * generate the HTML of error
	 * messages to be added at the top of the page
	 */
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
