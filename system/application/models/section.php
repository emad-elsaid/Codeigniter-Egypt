<?php
/**
 * section class
 *
 * @package	Vunsy
 * @subpackage	Vunsy
 * @category	model file
 * @author	Emad Elsaid
 * @link	http://github.com/blazeeboy/vunsy
 */
class Section extends DataMapper {
	var $table = 'section';
	
    function Section()
    {
        parent::DataMapper();
    }
	
	function get_parents()
	{	
		$c = new Section();
		$c->get_by_id( $this->id );
		
		$parents = array();
		while( !empty($c->parent_section) )
		{
			$c = $c->get_by_id( $c->parent_section );
			array_push( $parents, $c->id );
		}
		return $parents;
	}
	
	function save( $object= '' )
	{
		if( empty($this->id) and empty($object) )
		{
			$s = new Section();
			$s->where( 'sort >=', $this->sort );
			$s->where( 'parent_section', $this->parent_section );
			$s->get();
			
			foreach( $s->all as $item )
			{
				$item->sort++;
				$item->save();
			}
		}
		
		parent::save($object);
		
	}
	
	function delete_with_sub( )
	{
			// getting the subsections
			$c = new Section();
			$c->where( 'parent_section', $this->id );
			$c->get();
			// delete all subsections relations
			$c->delete_all();
			// delete all children
			$cont = new Content();
			$cont->get_where_parent_section($this->id);
			$cont->delete_all();
			
			// update all the sections sort after that section
			// that in the same parent section
			$s = new Section();
			$s->where( 'sort >', $this->sort );
			$s->where( 'parent_section', $this->parent_section );
			$s->get();
			
			foreach( $s->all as $item )
			{
				$item->sort--;
				$item->save();
			}
		
		//delete this section
		parent::delete($object);
		
	}
	
	function attach( $object='', $parent='', $cell='', $sort='' )
	{
		if(! empty($object) )
		{
			// synchronyze the cell and sort numbers
			// to prevent paradox
			if( empty($cell)) 
				$cell = $object->cell;
			else
				$object->cell = $cell;
			if( empty($sort))
				$sort = $object->sort;
			else
				$object->sort = $sort;
			
			// check if that place it took
			$cont = new Content();
			$cont->where('parent_section',$this->id);//same section
			$cont->where('parent_content',$parent->id);//same parent
			$cont->where('cell',$cell);// same cell
			$cont->where('sort',$sort);//greater sort
			$cont->get();//get them to process
			
			// if that content object exists then that place is taken
			// so we have to get a place for it
			if( $cont->exists() )
			{
				// put the content in it's place require change all it's 
				// sisters that has a greater sort number to be increased
				// get all this content belong to this parent and this section
				// and the same cell and has a sort number greater that this
				// sort number
				$cont->where('parent_section',$this->id);//same section
				$cont->where('parent_content',$parent->id);//same parent
				$cont->where('cell',$cell);// same cell
				$cont->where('sort >=',$sort);//greater sort
				$cont->get();//get them to process
				foreach( $cont->all as $item )
				{
					$item->sort++;
					$item->save();
				}
				
			}
			
			//save the object itself
			$object->save();
			
		}
	}
	
	function attach_section( $section='' )
	{
			// check if that place it took
			$cont = new Section();
			$cont->where( 'parent_section', $this->id );//same section
			$cont->where( 'sort',$section->sort );//greater sort
			$cont->get();//get them to process
			
			if( $cont->exists() )
			{
				$cont->where('parent_section',$this->id);//same section
				$cont->where('sort >=',$section->sort);//greater sort
				$cont->get();//get them to process
				foreach( $cont->all as $item )
				{
					$item->sort++;
					$item->save();
				}
				
			}
			
			$section->save();
	}
	
	function deattach( $object='' )
	{	
		//=========================
		//normalize the  sort numbers
		//=========================
		$cont = new Content();
		// we have to push all the content up to fill that hole
		// these content must me in the same section,parent,cell
		// and have sort nubmer greater than that content
		$cont->where( 'parent_section',$object->parent_section );//same section
		$cont->where( 'parent_content',$object->parent_content );//same parent
		$cont->where( 'cell',$object->cell );// same cell
		$cont->where( 'sort >',$object->sort );//greater sort
		$cont->get();//get them to process
		foreach( $cont->all as $item )
		{
			$item->sort--;
			$item->save();
		}
			
	}
	
	function deattach_section( $section='' )
	{	
		//=========================
		//normalize the  sort numbers
		//=========================
		$cont = new Section();
		// we have to push all the content up to fill that hole
		// these content must me in the same section,parent,cell
		// and have sort nubmer greater than that content
		$cont->where( 'parent_section',$section->parent_section );//same section
		$cont->where( 'sort >',$section->sort );//greater sort
		$cont->get();//get them to process
		foreach( $cont->all as $item )
		{
			$item->sort--;
			$item->save();
		}
			
	}
	
	function can_view()
	{
		if( ! (empty($this->view)  or perm_chck( $this->view )) )
			return FALSE;
		else
			return TRUE;
	}
	
	function can_edit()
	{
		if( perm_chck( $this->edit ) )
			return TRUE;
		else
			return FALSE;
	}
	
	function can_addin()
	{
		if( perm_chck( $this->addin ) )
			return TRUE;
		else
			return FALSE;
	}
	
	function can_delete()
	{
		if( perm_chck( $this->del ) )
			return TRUE;
		else
			return FALSE;
	}
	
	function render()
	{
		$CI =& get_instance();
		/***************************************
		 *  saving the page edit mode condition in a 
		 * variable so open it in the body after that
		 * **************************************/
		$editMode = ($CI->vunsy->edit_mode() )? TRUE:FALSE;
		
		
		if($CI->vunsy->section->can_view())
		{
		
		/***************************************
		 *  rendering the BEFORE page
		 * i must close the edit mode before that 
		 * then render it so that the container box don't 
		 * display ...
		 * **************************************/
		$CI->vunsy->mode = 'view';
		
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
			$CI->vunsy->mode = 'edit';
			
		$page_body = new Layout();
		$page_body->get_by_info( 'PAGE_BODY_LOCKED' );
		$page_body_text = $page_body->render();
		if( $CI->vunsy->user->is_root())
				$page_body_text .= $CI->load->view( 'edit_mode/toolbar', '', TRUE );
		
		
		/*********************************************
		 *  redering the AFTER page content
		 * i must close the edit mode variable 
		 * before rendering so that the container
		 * of editing doesn't rendered
		 * ********************************************/
		$CI->vunsy->mode = 'view';
			
		$after_page = new Layout();
		$after_page->get_by_info( 'AFTER_PAGE_LOCKED' );
		$after_page_text = $after_page->render();	
		
		
		$doctype_text = doctype( $CI->config->item('doctype') );
		/*********************************************************
		 * display the page content
		 * i sum all the page content text
		 * before page + CSS + JS + head + body + after page
		 * *******************************************************/
		// Rendering the page 
		echo <<<EOT
{$before_page_text}
{$doctype_text}
<html xmlns="http://www.w3.org/1999/xhtml" >
	<head>
	<title>{$CI->config->item('site_name')} {$this->name}</title>
	<meta http-equiv="content-type" content="text/html;charset={$CI->config->item('charset')}" />
	<meta name="generator" content="VUNSY system" />
{$CI->vunsy->css_text()}
{$CI->vunsy->js_text()}
{$CI->vunsy->dojo_text()}
{$page_head_text}
	</head>
	<body class="{$CI->vunsy->dojoStyle}">
		{$page_body_text}
	</body>
</html>
{$after_page_text}
EOT;
		$CI->vunsy->js = array();
		$CI->vunsy->css = array();
		$CI->vunsy->dojo = array();
		
		}//Very long IF block for view permission
	}
}
