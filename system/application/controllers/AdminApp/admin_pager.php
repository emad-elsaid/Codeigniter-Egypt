<?php
class Admin_pager extends Controller {

	function Admin_pager(){
		parent::controller();
		$perm = $this->vunsy->user->is_root();
		if(! $perm ) redirect();
	}
	
	function index(){
		
	}
}
