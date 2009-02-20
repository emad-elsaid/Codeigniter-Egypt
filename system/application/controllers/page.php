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
		
		if(!$this->vunsy->section->can_view())
			show_error('Access denied');
		
		$before_page = new Layout();
		$before_page->get_by_info( 'BEFORE_PAGE_LOCKED' );
		$before_page_text = $before_page->render();
		
		$page_head = new Layout();
		$page_head->get_by_info( 'PAGE_HEAD_LOCKED' );
		$page_head_text = $page_head->render();
		
		$page_body = new Layout();
		$page_body->get_by_info( 'PAGE_BODY_LOCKED' );
		$page_body_text = $page_body->render();
		
		$after_page = new Layout();
		$after_page->get_by_info( 'AFTER_PAGE_LOCKED' );
		$after_page_text = $after_page->render();	
		
		$css_text = '';
		foreach( $this->vunsy->css as $item )
			$css_text .= "	".link_tag( $item )."\n";
		
		$js_text = '';
		foreach( $this->vunsy->js as $item )
			$js_text .= "	<script type=\"text/javascript\" src=\"".$item."\" ></script>\n";
		
		// Rendering the page 
		$OUTPUT  = $before_page_text;
		$OUTPUT .= doctype( $this->config->item('doctype') );
		$OUTPUT .= "<html xmlns=\"http://www.w3.org/1999/xhtml\" >\n"
					   ."<head>\n"
					   ."	<title>"
					  .$this->config->item('site_name')
					  ." "
					  .$this->vunsy->get_section()->name
					  ."</title>\n"
					  ."	<meta http-equiv=\"content-type\" content=\"text/html;charset="
					  .$this->config->item('charset')
					  ."\" />\n"
					  . "	<meta name=\"generator\" content=\"VUNSY system\" />\n"
					  . $css_text
					  . $js_text
					  . $page_head_text
					  . "\n"
					  . "</head>\n"
					  . "<body>\n"
					  . $page_body_text
					  . "</body>\n"
					  . "</html>"
					  . $after_page_text;
		
		echo $OUTPUT;	
	}
	
	function login()
	{
		$this->load->view('login');
	}
	
	function login_action()
	{
		
	}
}
