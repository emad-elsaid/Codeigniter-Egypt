<?php
class Section extends DataMapper {
	var $table = 'section';
	var $has_many = array('content','section');
	
    function Section()
    {
        parent::DataMapper();
    }
	
	function get($limit,$offset)
	{	
		
		$c = new Section();
		$c->get_by_id( $this->parent_section );
		
		$this->parents = array();
		
		while( $c->exists() )
		{
			array_push( $this->parents, $c );
			$c = $c->get_by_id( $this->parent_section );	
		}
		
		return parent::get($limit,$offset);
	}
	
	function save( $object= '' )
	{
		if( empty($this->id) and empty($object) )
		{
			$s = new Section();
			$s->where( 'sort >=', $this->sort );
			$s->where( 'parent', $this->parent_section );
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
			$this->section->get();
			// delete all subsections relations
			$this->delete($this->section->all);
			// delete all children
			$this->section->delete_all();
			
			// update all the sections sort after that section
			// that in the same parent section
			$s = new Section();
			$s->where( 'sort >', $this->sort );
			$s->where( 'parent', $this->parent_section );
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
			$cont->where_relate_section('id',$this->id);//same section
			//if(!empty($parent))
				$cont->where_related($parent);//same parent
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
				$cont->where_relate_section('id',$this->id);//same section
				//if(!empty($parent))
					$cont->where_related($parent);//same parent
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
			//$object->save();
			
		}
	}
	
	function deattach( $object='' )
	{
		
		// delete relation to the parent and the section
		$l = new Layout();
		$l->where_related($object)->get();
		$l->delete( $object );
		$this->delete( $object );
		
		//=========================
		//normalize the  sort numbers
		//=========================
		$cont = new Content();
		$cont->where_relate_section('id',$this->id);//same section
		$cont->where_related($l);//same parent
		$cont->where('cell',$object->cell);// same cell
		$cont->where('sort',$object->sort);//greater sort
		$cont->get();//get them to process
		
		// if that content object exists then 
		// we don't have to change any sort number
		// if not exists then we need to fillthat hole
		if(! $cont->exists() )
		{
			// we have to push all the content up to fill that hole
			// these content must me in the same section,parent,cell
			// and have sort nubmer greater than that content
			$cont->where_relate_section('id',$this->id);//same section
			$cont->where_related($l);//same parent
			$cont->where('cell',$object->cell);// same cell
			$cont->where('sort >',$object->sort);//greater sort
			$cont->get();//get them to process
			foreach( $cont->all as $item )
			{
				$item->sort--;
				$item->save();
			}
			
		}
	}
}
