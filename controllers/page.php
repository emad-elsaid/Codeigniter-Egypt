<?php
class Page extends Controller{
	function Page(){
		parent::Controller();
		$this->load->library('datamapper');
	}
	
	function index(){
		$l = new Layout();
		print_r($l->cells());
	}
}
