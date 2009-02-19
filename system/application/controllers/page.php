<?php
class Page extends Controller
{
	function Page()
	{
		parent::Controller();
		//$this->load->library('datamapper');
	}
	
	function index($section=0)
	{
		$this->vunsy->install_content();
		$c = $this->uri->total_rsegments();
		$this->load->view( 'text',array('d'=>$c));
		
	}
}
