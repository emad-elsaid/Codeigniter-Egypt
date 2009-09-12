<?php
/** \addtogroup Controllers 
 * Admin controller
 *
 * @package	Vunsy
 * @subpackage	Vunsy
 * @category	controller file
 * @author	Emad Elsaid
 * @link	http://github.com/blazeeboy/vunsy
 */


class Admin extends Controller {
	
	/**
	 *  the admin controller it's current purpose is to launch applications
	 * further purposes to make varity of admin events like database browse, edit and so on
	 * */
	function Admin()
	{
		parent::controller();
	}
	
	/**
	 * launching An application an printing the output
	 * @param $appname : it's the application folder name
	 * @param $page: the page name, it's one of the pages array in config file or
	 * nothing to display the index file or a file name inside the application directory
	 * 
	 * it actually loads the app library and render it the print the output
	 */
	function app( $appname='', $page='' )
	{
		$config = array( 'name'=>$appname,'page'=>$page );
		$this->load->library( 'app',$config);
		echo $this->app->render();
	}
}
