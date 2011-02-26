<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class SectionEditor extends Application {
	
	function __construct(){
		parent::__construct();
		
		$this->perm = 'admin';

		$this->name 	= "Section Editor";
		$this->author 	= "Emad Elsaid";
		$this->website 	= "http://blazeeboy.blogspot.com";
		$this->version 	= "0.1";

		$this->pages 			= array('index'=>'Section Editor');

		$this->load->library('gui');
	}
	
	function addaction(){
		
		$this->load->library( 'gui' );
		
		$s = new Section();
		$s->parent_section = $this->input->post( 'parent_section' );
		$s->name = $this->input->post( 'name' );
		$s->sort = $this->input->post( 'sort' );
		$s->view = $this->input->post( 'view' );
		$s->save();
		
		redirect( 'sectionEditor' );
	}
	
	function delete($id)
	{
		$this->load->library( 'gui' );
		$s = new Section($id);
		$s->delete();
		
		redirect( 'sectionEditor' );
	}
	
	function edit($id)
	{
		$this->load->library( 'gui' );
		
		$s = new Section($id);
		
		$hidden = array( 
			'id'=>$id,
			'parent_section'=>$s->parent_section,
			'sort'=>$s->sort
		);
		
		$this->print_text( $this->gui->form(
			site_url( 'sectionEditor/editaction' )
			,array(
					'Name :'=>$this->gui->textbox( 'name', $s->name )
					,'view'=>$this->gui->permission( 'view', $s->view )
					,''=>$this->gui->button( '', 'Edit Section', array('type'=>'submit') )
			)
			,''
			,$hidden
		));
	}
	
	function editaction()
	{
		
		$s = new Section($this->input->post( 'id' ));
		$s->name = $this->input->post( 'name' );
		$s->view = $this->input->post( 'view' );
		$s->save();
		
		redirect( 'sectionEditor' );
	}
	
	function index()
	{
		$this->load->library( 'gui' );
		
		$sections = new Section();
		add(<<<EOT
<style>
ul{
	margin-left: 50px;
}
</style>
EOT
		);
		foreach( $sections as $item ){
			$item->e = anchor( 'Edit', site_url( 'sectionEditor/edit' ) );
			$item->d = anchor( 'Delete', site_url( 'sectionEditor/delete' ) );
		}
		
		function addS( $id, $sort , $text='+' ){
			$ci =& get_instance();
			$hidden = array( 'parent_section'=>$id, 'sort'=>$sort );
			return $ci->gui->tooltipbutton(
					$text
					,$ci->gui->form(
							site_url( 'sectionEditor/addaction' )
							,array(
									'Name :'=>$ci->gui->textbox( 'name' )
									,'View :'=>$ci->gui->permission( 'view' )
									,''=>$ci->gui->button( '', 'Submit', array('type'=>'submit') )
							)
							,''
							,$hidden
					)
			);
		}
		
		function printS( $id ){
			$s = new Section($id);
			$s->e = anchor( site_url( 'sectionEditor/edit' ).'/'.$s->id, 'Edit' );
			$s->d = anchor( site_url( 'sectionEditor/delete' ).'/'.$s->id,'Delete' );
			
			$output = "<li>";
			$output .= $s->id .'|';
			$output .= $s->name;
			$output .= addS( $id, 0, "Add child" );
			$output .= '|';
			$output .= $s->e;
			$output .= '|';
			$output .= $s->d;
			$c = new Section();
			$c->where( 'parent_section',  $id );
			$c->order_by( 'sort', 'asc' );
			$c->get();
			
			if( $c->result_count() > 0 ){
				$output .= "<ul>";
				
				foreach( $c as $item ){
					$output .= printS( $item->id );
					$output .= "<li>".addS( $id, $item->sort+1 )."</li>";
				}
				$output .= "</ul>";
			}
			
			$output .= "</li>";
			return $output;
		}
		
		// start to print sections from index page with ID = 1
		$this->print_text( printS(1) );
		
	}
}