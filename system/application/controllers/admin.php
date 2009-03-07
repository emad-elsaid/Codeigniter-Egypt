<?php
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
