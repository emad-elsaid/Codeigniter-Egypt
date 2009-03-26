<?php
/**
 * content class
 *
 * @package	Vunsy
 * @subpackage	Vunsy
 * @category	model file
 * @author	Emad Elsaid
 * @link	http://github.com/blazeeboy/vunsy
 */
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
		$sec->get_by_id($this->parent_section );
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
		
		$CI =& get_instance();
		
		if( $CI->vunsy->edit_mode() AND !strstr($this->info, "LOCKED"))
		{
				$text = $CI->load->view('edit_mode/container'
						,array(
							'text'=>$text
							,'parent'=>$this->parent_content
							,'id'=>$this->id
							,'cell'=>$this->cell
							,'sort'=>$this->sort
							,'can_delete'=>$this->can_delete()
							,'can_addin'=>$this->can_addin()
							,'can_edit'=>$this->can_edit()
						)
						, TRUE);
		}
				
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
