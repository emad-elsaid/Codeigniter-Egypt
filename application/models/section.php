<?php
/**  \addtogroup Models
 * Section class: a datamapper class that holds page data from the database
 *
 * @package	Codeigniter-Egypt
 * @subpackage	Codeigniter-Egypt
 * @category	model file
 * @author	Emad Elsaid
 * @link	http://github.com/blazeeboy/Codeigniter-Egypt
 */
class Section extends DataMapper {
	
	public $default_order_by = array('sort');
	public $ci;
	static $parents = NULL;

	public function __construct($id=NULL){
		
		parent::__construct($id);
		$this->ci =& get_instance();
		
	}

	/**
	 * get all the parent of that section up to
	 * the index page as an array of IDs
	 **/
	public function get_parents(){

		if( !is_null(Section::$parents) )
			return Section::$parents;
			
		$c = new Section($this->id);

		$parents = array();
		while( !empty($c->parent_section) ){
			$c = $c->get_by_id( $c->parent_section );
			$parents[] = $c->id;
		}
		Section::$parents = $parents;
		
		return $parents;
	}

	public function save( $object = '', $related_field = '' ){

		if( empty($this->id) and empty($object) ){
			$s = new Section();
			$s->where( 'sort >=', $this->sort );
			$s->where( 'parent_section', $this->parent_section );
			$s->get();

			foreach( $s as $item ){
				$item->sort++;
				$item->save();
			}
		}

		parent::save($object, $related_field );
	}

	/**
	 * delete that section with all it's subsections
	 **/
	public function delete( $object = '', $related_field = '' ){

		if( empty($object) ){
			// update all the sections sort after that section
			// that in the same parent section
			$s = new Section();
			$s->where( 'sort >', $this->sort );
			$s->where( 'parent_section', $this->parent_section );
			$s->get();
	
			foreach( $s as $item ){
				$item->sort--;
				$item->save();
			}
		}

		//delete this section
		parent::delete( $object, $related_field );

	}

	/**
	 * return true if that user can view the section
	 * and false if cannot view it
	 **/
	public function can_view(){
		
		return (empty($this->view) or perm_chck( $this->view ));
		
	}

	/**
	 * render the HTML of current section
	 * that function works if that section is the current
	 * section of the user
	 **/
	public function render(){

		if(!$this->ci->system->section->can_view())
			show_error( 'Access denied' );

		$page_body = new Content(1);
		$page_body_text = $page_body->render();

		// adding the admin toolbar
		if( $this->ci->ion_auth->is_admin())
		$page_body_text .= $this->ci->load->view( 'edit_mode/toolbar', '', TRUE );

		
		theme_pagetitle($this->name);
		// Rendering the page
		$this->ci->load->view('xhtml',array('body'=>$page_body_text));
		
	}
}
