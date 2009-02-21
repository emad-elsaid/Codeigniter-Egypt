<?php
class Layout extends Content {
	var $has_many = array('content');
	
	function Layout()
	{
		parent::Content();
	}
	
	function cells()
	{
		if($this->path!='' AND $this->path!=NULL)
			$c = $this->load->view($this->path,array('mode'=>'config'),TRUE);
		else
			$c = 1;
		return intval($c);
	}
	
	function render()
	{
		
		$CI =& get_instance();
		// the main render code
		$cell_number = $this->cells();
		$layout_content = array();
		if( $CI->vunsy->edit_mode())
				$add_button = $CI->load->view('edit_mode/buttons','',TRUE);
		
		for( $i=0; $i<$cell_number; $i++ )
		{
			// getting the content in that cell
			$c_children = $this->children( $CI->vunsy->get_section(), $i );
			
			// text that holds the rendered content
			$cell_text = '';
			if( $CI->vunsy->edit_mode())
				$cell_text = $add_button;
				
			foreach( $c_children as $item )
			{
				$cell_text .= $item->render();
				if( $CI->vunsy->edit_mode())
					$cell_text .= $add_button;
			}
			
			// put the cell text in it's place in the layout text array
			if( $CI->vunsy->edit_mode())
			{
				$add_button = $CI->load->view('edit_mode/buttons','',TRUE);
				$cell_text .= $add_button;
			}
			if( $CI->vunsy->edit_mode())
				$layout_content[ $i ] = $CI->load->view('edit_mode/container',array('text'=>$cell_text),TRUE);
			else
				$layout_content[ $i ] = $cell_text;
		}
		
		
		// if the layout exists render the layout with the corresponding 
		// cells text if not just pass the first cell value
		if( $this->path != '' )
		{
			$text = $this->load->view( 
								'layouts/'.$this->path,
								array(
										'id'=>$this->id,
										'cell'=> $layout_content
								),
								TRUE
			);
		}
		else
		{
			$text = $layout_content[0];
		}
		
		if( $CI->vunsy->edit_mode())
				$text = $CI->load->view('edit_mode/container',array('text'=>$text),TRUE);
			else
				$text = $cell_text;
		
		return parent::render($text);
	}
	
	function delete( $object='' )
	{
		if( empty($object) )
		{
			$c = $this->children;
			foreach( $c as $item )
				$item->delete();
		}
		
		return parent::delete( $object );
	}
	
	function children($section='',$cell='')
	{
		
	
		$par_sec = $section->get_parents();
		
		$sql_stat = sprintf(
			"SELECT * FROM `content`
			WHERE `parent_content`=%s",$this->id );
		if( ! empty($cell) )
			$sql_stat .= sprintf(" AND `cell`=%s",$cell );
		if( ! empty($section) )
			$sql_stat .= sprintf(" AND 
			(
				(`parent_section`=%s)", $section->id );
				//foreach($par_sec as $sss ){
					if( count($par_sec) >0 )
					$sql_stat .= sprintf(" OR (`section` IN (%s) AND `childs`=%s)", implode(',',$par_sec), intval(TRUE));
				//}
				$sql_stat = $sql_stat . sprintf("
			 ) ORDER BY `sort` ASC");
		
		$children = new Content();
		$children->query($sql_stat);
	
		/*if( ! empty($cell) )
			$children_objs->where('cell', $cell ); // same cell number
		if( ! empty($section) )
			$children_objs->where( 'paren_content',$this->id );// same parent layout
		$all_sections = array_merge( $section->get_parents() , array($this) );
		$children_objs->where_in_related( $all_sections ); // in the same section or parent sections
		$children_objs->order_by( 'sort' , asc );// sorted ascending 
		$children_objs->get();// get them to process
		*/
		
		$final_c = array();// final array of children to return them
		
		//$sec = new Section();
		foreach($children->all as $item )
		{
			/*$sec->where_related( $item )->get();
			if( $sec->id == $section->id or $item->subsection == intval(TRUE) )
			{*/
				// making the content object with the type
				$temp = new $item->type();
				$temp->get_by_id( $item->id );
				array_push( $final_c, $temp);
			//}
		}
		
		return $final_c;
	}
}
