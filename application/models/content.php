<?php
/** \addtogroup Models
 * Content class that holds the content data from the database
 *
 * @package		Codeigniter-Egypt
 * @subpackage	Codeigniter-Egypt
 * @category		model file
 * @author		Emad Elsaid
 * @link			http://github.com/blazeeboy/Codeigniter-Egypt
 */
class Content extends DataMapper {
	
	public $default_order_by = array('sort');
	public $ci;

	public function __construct($id=NULL){
		
		parent::__construct($id);
		$this->ci =& get_instance();
		
	}

	/**
	 * move the content up in it's cell
	 * returns true if it moved and false if
	 * faild to move it ... in case it's the first one
	 **/
	public function move_up(){

		if( $this->sort > 0 and isset($this->id) ){
			$this->deattach();
			$this->sort--;
			$this->attach();
			return TRUE;
		}

		return FALSE;
	}


	/**
	 * moves the current content down in it's cell
	 * return true on success and false on failure
	 **/
	public function move_down(){
		
		$cont = new Content();
		$cont->where('parent_content',$this->parent_content );//same parent
		$cont->where('cell',$this->cell);// same cell
		$cont->where('sort >',$this->sort);//greater sort
		$cont->get();//get them to process

		// if that content object exists then that content is not the last
		// and we'll move it down
		if( $cont->exists() ){
			$this->deattach();
			$this->sort ++;
			$this->attach();
			return TRUE;
		}

		return FALSE;
	}

	/**
	 * render the object edit button
	 * @param	$text: the content generated HTML
	 *
	 * returns the edit button + the content HTML
	 **/
	public function container( $text='' ){

		if( $this->ci->system->mode=='edit' AND $this->info!='PAGE_BODY_LOCKED' ){
			$text = $this->ci->load->view('edit_mode/container'
			,array(
			'text'=>$text
			,'path'=>$this->path
			,'parent'=>$this->parent_content
			,'id'=>$this->id
			,'cell'=>$this->cell
			,'sort'=>$this->sort
			)
			, TRUE);
		}
			
		return $text;
	}

	/**
	 * true if the user can view that content
	 * false if the user not permitted to see it
	 **/
	public function can_view(){
		
		return  (empty($this->view)  or perm_chck( $this->view ));
		
	}

	/**
	 * getting the number of the cells in the layout
	 * it loads the layout in config mode
	 * the layout should return the number of cells
	 * if the layout is not exist it'll return 1 cell
	 **/
	public function cells(){

		if( $this->path =='' )
			return 1;
			
		return intval($this->load->view(
						'content/'.$this->path,
					array(
							'id'=> $this->id,
							'ci'=> $this->ci,
							'info'=>$this->get_info(),
							'mode'=>'layout'
							),
							TRUE
							));
	}

	/**
	 * get the content information as an object
	 **/
	public function get_info(){

		if( !is_null($this->_cached_info_obj) )
			return $this->_cached_info_obj;

		$info = json_decode( $this->info );
		if( is_object($info) )
			foreach( $info as $key=>$value )
				if( is_array( $value ) )
				$info->$key = intval(count($value)==1);

		$this->_cached_info_obj = $info;
		
		return $info;
	}

	/**
	 * generate add button to that cell
	 * in this content object
	 **/
	public function add_button( $cell='' ){

		return $this->ci->load->view(
						'edit_mode/insert',
		array('url'=> site_url( "editor/chooser/{$this->ci->system->section->id}/{$this->id}/{$cell}/0" )),
		TRUE
		);
		
	}

	/**
	 * rendering the cells and encapsulate it in the
	 * layout then return all of that
	 * taking in consideration the edit mode to display
	 * the control buttons in every cell
	 **/
	public function render(){

		/***************************************
		 *  the main render code
		 ***************************************/

		// just return nothing if it's not viewable
		if( ! $this->can_view() )
		return '';
			
		/**
		 * getting the cells number and make an empty array
		 * of all the cell content
		 **/
		$cell_number = $this->cells();
		$layout_content = NULL;
		if( $cell_number>0 )
		{
			$layout_content = array_fill(0,$cell_number,'');
			
			// getting the content in current section
			$c_children = $this->children( $this->ci->system->section );
			
			foreach( $c_children as $child )
				$layout_content[$child->cell] .= $child->render();
				
			foreach( $layout_content as $k=>$v )
				if($this->ci->system->mode=='edit' AND $v=='' AND $this->ci->ion_auth->is_admin())
					$layout_content[ $k ] = $this->add_button( $k );
		}
		/**
		 * if the layout exists render the layout with the corresponding
		 * cells text if not just pass the first cell value
		 **/
		if( !empty($this->path) and !is_null($this->path) ){
			$text = $this->load->view(
								'content/'.$this->path,
			array(
					'id'=>$this->id,
					'ci'=> $this->ci,
					'cell'=> $layout_content,
					'info'=>$this->get_info(),
					'mode'=> 'view'
					),
					TRUE
					);
		}else{
			/**
			 * if the layout not exists then but the 1st cell as
			 * the content it self
			 **/
			$text = $layout_content[0];
		}

		/**
		 * apply filters and edit button container
		 **/
		$text = $this->apply_filters( $text );
		$text = $this->container($text);
		return $text;
	}

	/***************************************
	 * query about the children of the current parent
	 * you can specify the section or the cell or
	 * simply query all the children of that parent or all
	 * the children of the current parent and a specific
	 * section
	 * you must pass objects to the function
	 ***************************************/
	public function children($section=NULL ){

		// getting the section path to the main index page
		if( ! is_null($section) )
		$par_sec = $section->get_parents();

		$contents = new Content();
		
		$contents->where( 'parent_content', $this->id );
		
		$contents	->group_start()
						->where( 'parent_section', $section->id );
						
		if( count($par_sec)>0  )
		$contents	->or_group_start()
						->where_in( 'parent_section', $par_sec )
						->where( 'subsection', 1 )
					->group_end();
					
		$contents	->group_end()
					->get();

		// returning the final array of children
		return $contents;
	}
	
	public function attach(){
		
		$cont = new Content();
		$cont->where( 'parent_content', $this->parent_content );//same parent
		$cont->where( 'cell', $this->cell );// same cell
		$cont->where( 'sort >=', $this->sort) ;//greater sort
		$cont->get();//get them to process
		foreach( $cont as $item ){
			$item->sort++;
			$item->save();
		}
		
	}
	
	public function deattach(){
		
		$cont = new Content();
		$cont->where( 'parent_content', $this->parent_content );//same parent
		$cont->where( 'cell', $this->cell );// same cell
		$cont->where( 'sort >', $this->sort );//greater sort
		$cont->get();//get them to process
		foreach( $cont as $item ){
			$item->sort--;
			$item->save();
		}
		
	}
	
	public function save($object = '', $related_field = ''){
		
		if( empty($this->id) and empty($object) )
			$this->attach();
			
		parent::save($object, $related_field);
	}
	
	//=========================
	//normalize the  sort numbers
	//=========================
	// we have to push all the content up to fill that hole
	// these content must me in the same section,parent,cell
	// and have sort nubmer greater than that content
	public function delete($object = '', $related_field = ''){
		
		$this->deattach();
		parent::delete($object, $related_field);
		
	}

	/**
	 * apply filters to $input and return
	 * the effected text
	 **/
	public function apply_filters($input){

		$output = $input;
		$filters_array = array_map( 'trim', explode( "\n", $this->filter ) );
		foreach( $filters_array as $item )
		if( trim($item)!='' )
		$output = $this->ci->load->view( 'filter/'.$item, array('text'=>$output,'id'=>$this->id), TRUE);

		return $output;
	}
}
