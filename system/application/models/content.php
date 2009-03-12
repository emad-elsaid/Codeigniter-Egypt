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
		$sec->get_by_id($this->id );
		$sec->deattach( $this );
	}
	
	/*function save( $object = '')
	{
		if( empty($object) )
		{
			$this->type = get_class( $this );
		}
		
		return parent::save( $object );
	}*/
	
	function render( $text='' ){
		
		if( ! $this->can_view() )
			$text='';
		return $text;
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
