<?php
class Layout extends Content {
	
	var $has_many = array('content');
	
	function Layout()
	{
		parent::Content();
	}
	
	
	/***************************************
	 * getting the number of the cells in the layout
	 * it loads the layout in config mode
	 * the layout should return the number of cells
	 * if the layout is not exist it'll return 1 cell
	 * **************************************/
	function cells()
	{
		if(! empty($this->path) )
			$c = $this->load->view($this->path,array('mode'=>'config'),TRUE);
		else
			$c = 1;
		return intval($c);
	}
	
	
	/***************************************
	 * rendering the cells and encapsulate it in the
	 * layout then return all of that
	 * taking in consideration the edit mode to display 
	 * the control buttons in every cell
	 * **************************************/
	function render()
	{
		
		$CI =& get_instance();
		
		/***************************************
		 *  the main render code
		 * **************************************/
		
		// getting the cells number
		$cell_number = $this->cells();
		
		$layout_content = array();
		
		// loading the control buttons if edit mode opened
		if( $CI->vunsy->edit_mode())
				$add_button = $CI->load->view('edit_mode/buttons','',TRUE);
		
		
		/***************************************
		 *  starting to render the cells
		 * **************************************/
		for( $i=0; $i<$cell_number; $i++ )
		{
			// getting the content in that cell
			$c_children = $this->children( $CI->vunsy->get_section(), $i );
			
			$cell_text = '';
			if( $CI->vunsy->edit_mode())
				$cell_text = $add_button; // +++ buttons +++ in start of every cell
				
			// rendering the cell content
			foreach( $c_children as $item )
			{
				$cell_text .= $item->render();
				if( $CI->vunsy->edit_mode())
					$cell_text .= $add_button;// +++ buttons +++ after every widget
			}
			
			// put the cell text in it's place in the layout text array
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
		
		// enclose the layout in a container
		if( $CI->vunsy->edit_mode())
				$text = $CI->load->view('edit_mode/container',array('text'=>$text),TRUE);
		else
				$text = $cell_text;
		
		return parent::render($text);
	}
	
	/***************************************
	 * delete all children objects and 
	 * then delete the layout, this should clean the website
	 * of all the wedgits that has no relation
	 * **************************************/
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
	
	/***************************************
	 * query about the children of the current parent
	 * you can specify the section or the cell or 
	 * simply query all the children of that parent or all 
	 * the children ofthe current parent and a specific
	 * section
	 * you must pass objects to the function
	 * **************************************/
	function children($section='',$cell='')
	{
		
		// getting the section path to the main index page
		$par_sec = $section->get_parents();
		
		// selecting all the content of that parent
		$sql_stat = sprintf(
			"SELECT * FROM `content`
			WHERE `parent_content`=%s",$this->id );
			
		// filter the objects to the requested cell
		if( ! empty($cell) )
			$sql_stat .= sprintf(" AND `cell`=%s",$cell );
			
			
		/***************************************
		 *  filter the objects to the requested section
		 * and all the parent sections that the content requested to be 
		 * shared in the sub sections ordered in ascending with sort field
		 * **************************************/
		if( ! empty($section) )
			$sql_stat .= sprintf(" AND 
			(
				(`parent_section`=%s)", $section->id );
				if( count($par_sec) >0 )
					$sql_stat .= sprintf(" OR (`section` IN (%s) AND `childs`=%s)", implode(',',$par_sec), intval(TRUE));
				$sql_stat = $sql_stat . sprintf("
			 ) ORDER BY `sort` ASC");
		
		
		// submit the query
		$children = new Content();
		$children->query($sql_stat);
	
		/***************************************
		 * building the children array with the respect objects
		 * in other meaning we'll convert all the content to their 
		 * subtype : layout, widgets, ... etc
		 * **************************************/
		$final_c = array();// final array of children to return them
		
		foreach($children->all as $item )
		{
				// making the content object with the type
				$temp = new $item->type();
				$temp->get_by_id( $item->id );
				array_push( $final_c, $temp);
		}
		
		// rturning the final array of children
		return $final_c;
	}
}
