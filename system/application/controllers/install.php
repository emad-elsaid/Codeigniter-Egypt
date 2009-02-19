<?php

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

