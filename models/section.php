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
		parent::get($limit,$offset);
		
		$c = new Section();
		$c->get_by_id( $this->parent_section );
		
		$this->parents = array();
		
		while( $c->exists() )
		{
			array_push( $this->parents, $c );
			$c = $c->get_by_id( $this->parent_section );	
		}
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
	
}
