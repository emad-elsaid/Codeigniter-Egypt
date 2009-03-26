<?php
/**
 * admin controller
 *
 * @package	Vunsy
 * @subpackage	Vunsy
 * @category	controller file
 * @author	Emad Elsaid
 * @link	http://github.com/blazeeboy/vunsy
 */
class Admin extends Controller {
	
	function Admin()
	{
		parent::controller();
	}
	
	function app( $appname='', $page='' )
	{
		$config = array( 'name'=>$appname,'page'=>$page );
		$this->load->library( 'app',$config);
		echo $this->app->render();
	}
}
