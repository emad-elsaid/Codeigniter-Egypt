<?php
/** \addtogroup Models
 * Content class that holds the content data from the database
 *
 * @package		Vunsy
 * @subpackage	Vunsy
 * @category		model file
 * @author		Emad Elsaid
 * @link			http://github.com/blazeeboy/vunsy
 */
class Content extends DataMapper {
	var $table = 'content';
	var $default_order_by = array('sort');
	var $ci;

	function __construct($id=NULL)
	{
		parent::__construct($id);
		$this->ci =& get_instance();
	}

	/**
	 * attach the object to a section and a parent content
	 * and a cell and an order as sort this will make it as a child
	 * for those parents
	 * @param	$section: parent section object or a section ID
	 * @param	$parent: content parent to connect that object to is as a child
	 * @param	$cell: the parent cell that will be the container of that object
	 * @param	$sort: the order of object in the list of siblings in that cell starts from zero the top
	 **/
	function attach( $section=NULL, $parent=NULL, $cell=NULL, $sort=NULL)
	{
		// getting section object
		if( $section==NULL )
		$section = new Section($this->parent_section);

		$section->attach( $this, $parent, $cell, $sort );
	}

	/**
	 * deattach that content from it's parent and section
	 **/
	function deattach()
	{
		$sec = new Section();
		$sec->get_by_id($this->parent_section );
		$sec->deattach( $this );
	}

	/**
	 * move the content up in it's cell
	 * returns true if it moved and false if
	 * faild to move it ... in case it's the first one
	 **/
	function move_up()
	{
		if( $this->sort > 0 and isset($this->id) )
		{
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
	function move_down()
	{
		$cont = new Content();
		//$cont->where('parent_section',$this->parent_section );//same section
		$cont->where('parent_content',$this->parent_content );//same parent
		$cont->where('cell',$this->cell);// same cell
		$cont->where('sort >',$this->sort);//greater sort
		$cont->get();//get them to process

		// if that content object exists then that content is not the last
		// and we'll move it down
		if( $cont->exists() )
		{
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
	function container( $text='' )
	{

		if( $this->ci->vunsy->edit_mode() AND $this->info!='PAGE_BODY_LOCKED' )
		{
			$text = $this->ci->load->view('edit_mode/container'
			,array(
					'text'=>$text
			,'path'=>$this->path
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
			
		return $text;
	}

	/**
	 * true if the user can view that content
	 * false if the user not permitted to see it
	 **/
	function can_view()
	{
		return  (empty($this->view)  or perm_chck( $this->view ));
	}

	/**
	 * returns if the user can edit the content or not
	 **/
	function can_edit()
	{
		return perm_chck( $this->edit );
	}

	/**
	 * returns if the user can add content in that
	 * content or not
	 **/
	function can_addin()
	{
		return perm_chck( $this->addin );
	}

	/**
	 * returns if the user can delete it or not
	 **/
	function can_delete()
	{
		return perm_chck( $this->del );
	}

	/**
	 * getting the number of the cells in the layout
	 * it loads the layout in config mode
	 * the layout should return the number of cells
	 * if the layout is not exist it'll return 1 cell
	 **/
	function cells()
	{
		if( $this->path !='' )
		{
			$c = $this->load->view(
						'content/'.$this->path,
			array(
							'id'=> $this->id,
							'ci'=> $this->ci,
							'info'=>$this->get_info(),
							'mode'=>'layout'
							),
							TRUE
							);
		} else {
			$c = 1;
		}

		return intval($c);
	}

	/**
	 * get the content information as an object
	 **/
	function get_info()
	{
		$info = json_decode( $this->info );
		if( is_object($info) )
		{
			foreach( $info as $key=>$value )
			{
				if( is_array( $value ) )
				$info->$key = intval(count($value)==1);
			}
		}
		return $info;
	}

	/**
	 * generate add button to that cell
	 * in this content object
	 **/
	function add_button( $cell='' )
	{
		return $this->ci->load->view(
						'edit_mode/insert',
		array(
						'url'=> site_url( "editor/chooser/{$this->ci->vunsy->section->id}/{$this->id}/{$cell}/0" )
		),
		TRUE
		);
	}

	/**
	 * rendering the cells and encapsulate it in the
	 * layout then return all of that
	 * taking in consideration the edit mode to display
	 * the control buttons in every cell
	 **/
	function render()
	{

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
		$layout_content = array();

		/***************************************
		 *  starting to render the cells
		 ***************************************/
		for( $i=0; $i<$cell_number; $i++ )
		{
			// getting the content in that cell and current section
			$c_children = $this->children( $this->ci->vunsy->get_section(), $i );

			$layout_content[ $i ] = '';

			// adding the cell (add button)
			if( $this->ci->vunsy->edit_mode() AND count( $c_children )==0 AND $this->can_addin() )
			$layout_content[ $i ] = $this->add_button( $i );

			// rendering the cell content
			foreach( $c_children as $item )
			$layout_content[ $i ] .= $item->render();
		}


		/**
		 * if the layout exists render the layout with the corresponding
		 * cells text if not just pass the first cell value
		 **/
		if( !empty($this->path) and !is_null($this->path) )
		{
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
		}
		else
		{
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
	 * delete all children objects and
	 * then delete the layout, this should clean the website
	 * of all the wedgits that has no relation
	 ***************************************/
	function delete( $object='' )
	{
		if( empty($object) )
		{
			$c = $this->children();
			foreach( $c as $item )
			$item->delete();
		}
		$this->deattach();
		return parent::delete( $object );
	}

	/***************************************
	 * query about the children of the current parent
	 * you can specify the section or the cell or
	 * simply query all the children of that parent or all
	 * the children of the current parent and a specific
	 * section
	 * you must pass objects to the function
	 ***************************************/
	function children($section=NULL , $cell=NULL, $limit=0, $offset=0 )
	{
		// getting the section path to the main index page
		if( ! is_null($section) )
		$par_sec = $section->get_parents();

		/*******************************
		 * a VERY COMPLEX SQL statment
		 * i have to simplify that statment generator somehow
		 *******************************/

		// selecting all the content of that parent
		$sql_stat = "SELECT * FROM `content` WHERE `parent_content`= {$this->id}";
			
		// filter the objects to the requested cell
		if( ! is_null( $cell ) )
		$sql_stat .= " AND `cell`=$cell";
			
		/***************************************
		 * filter the objects to the requested section
		 * and all the parent sections that the content requested to be
		 * shared in the sub sections ordered in ascending with sort field
		 * **************************************/
		if( ! is_null($section) )
		{
			$sql_stat .= " AND
			(
				(`parent_section`={$section->id})";
			if( count($par_sec) >0 )
			$sql_stat .= sprintf(
								" OR (`parent_section` IN (%s) AND `subsection`=%s)",
			implode(',',$par_sec),
			intval(TRUE)
			);
			$sql_stat .= ") ORDER BY `sort` ASC";
		}

		//adding the limit and offset
		if( $limit!=0)
		$sql_stat .= " LIMIT $limit OFFSET $offset";

		// submit the query
		$children = new Content();
		$children->query($sql_stat);

		// returning the final array of children
		return $children->all;
	}

	/**
	 * apply filters to $input and return
	 * the effected text
	 **/
	function apply_filters($input)
	{

		$output = $input;

		$filters_array = array_map( 'trim', explode( "\n", $this->filter ) );
		foreach( $filters_array as $item )
			if( trim($item)!='' )
				$output = $this->ci->load->view( 'filter/'.$item, array('text'=>$output,'id'=>$this->id), TRUE);

		return $output;
	}

}
