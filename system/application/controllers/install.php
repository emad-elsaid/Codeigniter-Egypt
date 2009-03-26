<?php
/**
 * installer controller file
 * can be used like this:
 * www/ci_folder/index.php/install
 *
 * @package	Vunsy
 * @subpackage	Vunsy
 * @category	controller file
 * @author	Emad Elsaid
 * @link	http://github.com/blazeeboy/vunsy
 */
class Install extends Controller {

	function Install()
	{
		parent::Controller();	
	}
	
	function index()
	{
		if( ! $this->vunsy->installed() )
		{
			$this->vunsy->install();
		}
		else
		{
			$this->vunsy->install_content();
		}
	}
}

