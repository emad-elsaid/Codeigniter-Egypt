<?php
class Section extends DataMapper {
	var $table = 'section';
	
    function Section()
    {
        parent::DataMapper();
    }
	
	function get_parents()
	{	
		$c = new Section();
		$c = $this->clone();
		
		$parents = array();
		while( !empty($c->parent_section) )
		{
			$c = $c->get_by_id( $c->parent_section );
			array_push( $parents, $c );
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
	
	function delete_with_sub( $object= '' )
	{
		if(empty($object))
		{
			// getting the subsections
			$c = new Section();
			$c->get_where_parent_section( $this->id );
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
			$cont->where('parent_content',$parent);//same parent
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
				$cont->where('parent_content',$parent);//same parent
				$cont->where('cell',$cell);// same cell
				$cont->where('sort >=',$sort);//greater sort
				$cont->get();//get them to process
				foreach( $cont->all as $item )
				{
					$item->sort++;
					$item->save();
				}
				
			}
			// save relation to the section
			$this->save($object);
			
			//save relation to the parent
			$parent->save($object);
			
			//save the object itself
			$object->save();
			
		}
	}
	
	function deattach( $object='' )
	{	
		//=========================
		//normalize the  sort numbers
		//=========================
		$cont = new Content();
		$cont->where( 'parent_section',$object->parent_section );//same section
		$cont->where( 'parent_content',$object->parent_content );//same parent
		$cont->where( 'cell',$object->cell );// same cell
		$cont->where( 'sort',$object->sort );//greater sort
		$cont->get();//get them to process
		
		// if that content object exists then 
		// we don't have to change any sort number
		// if not exists then we need to fillthat hole
		if(! $cont->exists() )
		{
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
	
}
