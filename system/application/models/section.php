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
	
	function render()
	{
		$CI =& get_instance();
		
		if($CI->vunsy->section->can_view())
		{
		/*********************************************
		 *  redering the page BODY content
		 * here i open the edit mode so the widgets got the
		 * container box and the controller buttons
		 * and the admin toolbar 
		 * ********************************************/			
		$page_body = new Content();
		$page_body->get_by_info( 'PAGE_BODY_LOCKED' );
		$page_body_text = $page_body->render();
		if( $CI->vunsy->user->is_root())
				$page_body_text .= $CI->load->view( 'edit_mode/toolbar', '', TRUE );
		
		$doctype_text = doctype( $CI->config->item('doctype') );
		/*********************************************************
		 * display the page content
		 * i sum all the page content text
		 * before page + CSS + JS + head + body + after page
		 * *******************************************************/
		// Rendering the page 
		echo <<<EOT
{$doctype_text}
<html xmlns="http://www.w3.org/1999/xhtml" >
	<head>
	<title>{$CI->config->item('site_name')} {$this->name}</title>
	<meta http-equiv="content-type" content="text/html;charset={$CI->config->item('charset')}" />
	<meta name="generator" content="VUNSY system" />
{$CI->vunsy->css_text()}
{$CI->vunsy->js_text()}
{$CI->vunsy->dojo_text()}
	</head>
	<body class="{$CI->vunsy->dojoStyle}">
		{$page_body_text}
	</body>
</html>
EOT;
		$CI->vunsy->js = array();
		$CI->vunsy->css = array();
		$CI->vunsy->dojo = array();
		
		}//Very long IF block for view permission
		else
		{
			show_error( 'Access denied' );
		}
	}
}
