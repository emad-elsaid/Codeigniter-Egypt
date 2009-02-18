<?php
class Layout extends Content {
	var $has_many = array('content');
	
	function Layout(){
		parent::Content();
	}
	
	function cells(){
		$c = ($this->path!='' AND $this->path!=NULL)? $this->load->view($this->path,array('mode'=>'config'),TRUE) : 0;
		return intval($c);
	}
	
	function render(){
		if( empty($this->path) )
			$text = 'Layout not found';
		else
		{
			
			// the main render code
			$cell_number = $this->cells();
			$layout_content = array();
			for( $i==0; $i<$cell_number; $i++ )
			{
				// getting the contetn in that cell
				$c_children = $this->children( $this->vunsy->section(), $i );
				
				// text that holds the rendered content
				$cell_text = '';
				foreach( $c_children as $item )
				{
					$cellChilds .= $item->render();
				}
				
				// put the cell text in it's place in the layout text array
				$layout_content[ $i ] = $cell_text;
			}
			
			$text = $this->load->view( 
								'layouts/'.$this->path,
								array(
										'id'=>$this->id,
										'cell'=> $layout_content
								),
								TRUE
			);
			
		}
		
		return parent::render($text);
	}
	
	function delete( $object='' )
	{
		if( empty($object) )
		{
			foreach($this->content->get() as $item ){
				$temp = new ($item->type)();
				$temp->get_by_id( $item->id );
				$temp->delete();
			}
		}
		
		return parent::delete( $object );
	}
	
	function children($section='',$cell='')
	{
		
		$children_objs = new Content();
		if( ! empty($cell) )
			$children_objs->where('cell', $cell ); // same cell number
		$children_objs->where_related_layout( 'id',$this->id );// same parent layout
		$all_sections = array_merge( $section->parents , array($this) );
		$children_objs->where_in_related( $all_sections ); // in the same section or parent sections
		$children_objs->order_by( 'sort' , asc );// sorted ascending 
		$children_objs->get();// get them to process
		
		$final_c = array();// final array of children to return them
		
		$sec = new Section();
		foreach($children_objs->all as $item )
		{
			$sec->where_related( $item )->get();
			if( $sec->id == $section->id or $item->subsection == intval(TRUE) )
			{
				// making the content object with the type
				$temp = new ($item->type)();
				$temp->get_by_id( $item->id );
				array_push( $sec, $temp);
			}
		}
		
		return $final_c;
	}
}
