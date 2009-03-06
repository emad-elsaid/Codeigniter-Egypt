<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

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
	
	//css and js files
	var $css_files = array();
	var $js_files = array();
	
	// application view specifications
	var $show_toolbar = TRUE;
	var $show_title = TRUE;
	var $show_statusbar = TRUE;
	
	// application URLs and folders
	
	/* like http://localhost/system/application/views/apps/appname/
	 * to include images and javascript and css files 
	 * from your app folder
	 */
	var $folder = '';
	
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
		$this->folder = base_url().$this->ci_folder;
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

		//copy variables to this object
		foreach( $ini as $key=>$value )
			$this->$key = $value;
			
		$this->page = $this->index;
			
	}
	
	function render()
	{
		
	}
	
	function add_css($path="")
	{
		
	}
	
	function add_js( $path="" )
	{
		
	}
	
	function toolbar()
	{
		
	}
	
	function title()
	{
		
	}
	
	function help_dlg()
	{
		
	}
	
	function css()
	{
		
	}
	
	function js()
	{
		
	}
	
	function can_view()
	{
		/*$CI =& get_instance();
		
		// give permission to the root
		if( $CI->vunsy->user->is_root() )
			return TRUE;
		
		// execute the perm expression;
		eval('$temp = '.$this->perm.';');
		if( isset($temp)==TRUE )
			return $temp;
		else
			return FALSE;*/
		return perm_chck( $this->perm );
	}
	
}
