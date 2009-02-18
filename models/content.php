<?php
class Content extends DataMapper {
	var $table = 'content';
	
    function Content()
    {
        parent::DataMapper();
    }
	
	function delete( $object='' )
	{
		
		if( empty($object) )
		{
			$this->deattach();
		}
		
		return parent::delete( $object );
	}
	
	function attach( $section='', $parent='', $cell='', $sort='')
	{
		$section->attach( $this, $parent, $cell, $sort );
	}
	
	function deattach()
	{
		$sec = new Section();
		$sec->where_related_content('id',$this->id )->get();
		$sec->deattach( $this );
	}
	function save( $object = '')
	{
		if( empty($object) )
		{
			$this->type = get_class( $this );
		}
		
		return parent::save( $object );
	}
	
	function render( $text='' ){
		
		if( ! (empty($this->view)  or perm_chck( $this->view )) )
			$text='';
		
		
		return $text;
	}
}
