<?php
class Page extends Controller
{
	function Page()
	{
		parent::Controller();
	}
	
	function index($section=0)
	{
		
		/***************************************
		 *  saving the page edit mode condition in a 
		 * variable so open it in the body after that
		 * **************************************/
		$editMode = ($this->vunsy->edit_mode() )? TRUE:FALSE;
		
		
		if(!$this->vunsy->section->can_view())
			show_error('Access denied');
		
		/***************************************
		 *  rendering the BEFORE page
		 * i must close the edit mode before that 
		 * then render it so that the container box don't 
		 * display ...
		 * **************************************/
		$this->vunsy->mode = 'view';
		
		$before_page = new Layout();
		$before_page->get_by_info( 'BEFORE_PAGE_LOCKED' );
		$before_page_text = $before_page->render();
		
		/***************************************
		 *  rendering the page Head
		 * without the CSS and JS
		 * i have to render them after all the
		 * widgets rendered to add all the widgets needed
		 * js and css files
		 ***************************************/
		$page_head = new Layout();
		$page_head->get_by_info( 'PAGE_HEAD_LOCKED' );
		$page_head_text = $page_head->render();
		
		/*********************************************
		 *  redering the page BODY content
		 * here i open the edit mode so the widgets got the
		 * container box and the controller buttons
		 * and the admin toolbar 
		 * ********************************************/
		if( $editMode )
			$this->vunsy->mode = 'edit';
			
		$page_body = new Layout();
		$page_body->get_by_info( 'PAGE_BODY_LOCKED' );
		$page_body_text = $page_body->render();
		if( $this->vunsy->edit_mode())
				$page_body_text .= $this->load->view( 'edit_mode/toolbar', '', TRUE );
		
		
		/*********************************************
		 *  redering the AFTER page content
		 * i must close the edit mode variable 
		 * before rendering so that the container
		 * of editing doesn't rendered
		 * ********************************************/
		$this->vunsy->mode = 'view';
			
		$after_page = new Layout();
		$after_page->get_by_info( 'AFTER_PAGE_LOCKED' );
		$after_page_text = $after_page->render();	
		
		
		/*********************************************************
		 * display the page content
		 * i sum all the page content text
		 * before page + CSS + JS + head + body + after page
		 * *******************************************************/
		// Rendering the page 
		echo		   $before_page_text
					   . doctype( $this->config->item('doctype') )
					   . "<html xmlns=\"http://www.w3.org/1999/xhtml\" >"
					   ."\n<head>"
					   ."\n\t<title>"
					  .$this->config->item('site_name')
					  ." "
					  .$this->vunsy->get_section()->name
					  ."</title>"
					  ."\n\t<meta http-equiv=\"content-type\" content=\"text/html;charset="
					  .$this->config->item('charset')
					  ."\" />"
					  . "\n\t<meta name=\"generator\" content=\"VUNSY system\" />"
					  .  $this->vunsy->css_text()
					  .  $this->vunsy->js_text()
					  .  $this->vunsy->dojo_text()
					  .	$page_head_text
					  . "\n</head>"
					  . "\n<body class=\"{$this->vunsy->dojoStyle}\" >"
					  . $page_body_text
					  . "\n</body>"
					  . "\n</html>"
					  . $after_page_text;	
	}
	
}
