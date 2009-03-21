<?php
class Layout extends Content {
	
	//var $has_many = array('content');
	
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
		if( $this->path !='' )
			$c = $this->load->view($this->path,array('mode'=>'config'),TRUE);
		else
			$c = 1;
			
		return intval($c);
	}
	
	/***************************************
	 * the add button function
	 * **************************************/
	function add_button( $cell='', $sort='' )
	{
		$ci =& get_instance();
		$ci->load->library( 'gui' );
		add_dojo( 'dijit.form.Button' );
		
		$link = site_url( 'admin/app/content Inserter/index/'.$ci->vunsy->section->id.'/'.$this->id.'/'.$cell.'/'.$sort );
		$bText = <<<EOT
		 <span>Insert</span>
	<script type="dojo/method" event="onClick" args="evt">
		open("$link");
	</script>
EOT;
		return $ci->gui->button( "", $bText, 
				array("style"=>"font-size:13px","iconClass"=>"dijitEditorIcon dijitEditorIconInsertImage") );
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
		
		
		/***************************************
		 *  starting to render the cells
		 * **************************************/
		for( $i=0; $i<$cell_number; $i++ )
		{
			// getting the content in that cell
			$c_children = $this->children( $CI->vunsy->get_section(), $i );
			
			$cell_text = '';
			if( $CI->vunsy->edit_mode() AND count($c_children)==0 )
				$cell_text = $this->add_button($i,0); // +++ buttons +++ in start of every cell
				
			// rendering the cell content
			$sort_num = 0;
			foreach( $c_children as $item )
			{
				$sort_num++;
				$cell_text .= $item->render();
				/*if( $CI->vunsy->edit_mode())
					$cell_text .= $this->add_button($i, $sort_num);// +++ buttons +++ after every widget*/
			}
			
			// put the cell text in it's place in the layout text array
			/* that commented block was putting every cell in container,
			 * we have to put all the content in a container not the empty cell
			 * the page was disply even the empty cell plus button in a container
			 * */
			/*if( $CI->vunsy->edit_mode())
				$layout_content[ $i ] = $CI->load->view('edit_mode/container',array('text'=>$cell_text),TRUE);
			else*/
				$layout_content[ $i ] = $cell_text;
		}
		
		
		// if the layout exists render the layout with the corresponding 
		// cells text if not just pass the first cell value
		
		if( $this->path != '' )
		{
			$text = $this->load->view( 
								$this->path,
								array(
										'id'=>$this->id,
										'cell'=> $layout_content,
										'mode'=> 'view'
								),
								TRUE
			);
			
		}
		else
		{
			$text = $layout_content[0];
		}
		
		// enclose the layout in a container
		/* i comented that block and i'll make the parent class display all 
		 * the layoutsand widgets in a container
		 */
		/*if( $CI->vunsy->edit_mode())
				$text = $CI->load->view('edit_mode/container',array('text'=>$text),TRUE);
		else
				$text = $cell_text;*/
		
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
			$c = $this->children();
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
	function children($section='' , $cell='' )
	{
		
		// getting the section path to the main index page
		if( isset($section) )
			$par_sec = $section->get_parents();
		
		// selecting all the content of that parent
		$sql_stat = "SELECT * FROM `content` WHERE `parent_content`= {$this->id}";
			
		// filter the objects to the requested cell
		if( isset($cell) )
			$sql_stat .= " AND `cell`=$cell";
			
		/***************************************
		 *  filter the objects to the requested section
		 * and all the parent sections that the content requested to be 
		 * shared in the sub sections ordered in ascending with sort field
		 * **************************************/
		if( isset($section) )
		{
			$sql_stat .= " AND 
			(
				(`parent_section`={$section->id})";
				if( count($par_sec) >0 )
					$sql_stat .= sprintf(" OR (`section` IN (%s) AND `childs`=%s)", implode(',',$par_sec), intval(TRUE));
				$sql_stat .= ") ORDER BY `sort` ASC";
		}
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
		
		
		// returning the final array of children
		return $final_c;
	}
}
