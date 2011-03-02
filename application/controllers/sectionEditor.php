<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class SectionEditor extends Application {
	
	function __construct(){
		parent::__construct();
		
		$this->perm = 'admin';

		$this->name 	= "Section Editor";
		$this->author 	= "Emad Elsaid";
		$this->website 	= "http://blazeeboy.blogspot.com";
		$this->version 	= "0.1";

		$this->pages 	= array(
							'index'=>'Sections',
							'add'=>'New Section'
							);

		$this->load->library('gui');
	}
	
	function index(){

		theme_add('dojo.data.ItemFileReadStore');
		theme_add('dijit.tree.ForestStoreModel');
		theme_add('dijit.Tree');
		
		$this->print_text('<div dojoType="dojo.data.ItemFileReadStore" url="'.site_url('sectionEditor/queryTree').'" jsId="ordJson"></div>');
		$this->print_text('<div dojoType="dijit.tree.ForestStoreModel" childrenAttrs="line" store="ordJson" jsId="ordModel"></div>');
		$this->print_text('<div dojoType="dijit.Tree" id="ordTree" model="ordModel" showRoot="false" >
		<script type="dojo/method" event="onClick" args="item">
		if( item.id!=undefined )
			document.location.href = "'.site_url('sectionEditor/edit').'/"+item.id;	
		</script>
		</div>');
	}
	
	function queryTree(){
		
		$this->ajax = TRUE;
		
		function getTree($parent=NULL){
			$section = new Section();
			$section->where('parent_section', $parent)->get();
			$line = array();
			foreach( $section as $item ){
				$children = getTree($item->id);
				
				$line[] = count($children)>0
							? array('id'=>$item->id, 'description'=>$item->name, 'line'=>$children)
							: array('id'=>$item->id, 'description'=>$item->name);
			}
			return $line;
		}
		
		$this->print_text( json_encode(array( 'identifier'=>'id', 'label'=>'description','items'=>getTree(1))) );
	}
	
	function add(){
		
		$this->print_text( $this->gui->form(
			site_url( 'sectionEditor/addaction' )
			,array(
					'Parent :'=>$this->gui->section( 'parent_section')
					,'Name :'=>$this->gui->textbox( 'name' )
					,'View Permission :'=>$this->gui->permission( 'view' )
					,''=>$this->gui->button( '', 'Add Section', array('type'=>'submit') )
			)
		));
	}
	
	function addaction(){
		
		$sort = new Section();
		$sort	->where('parent_section',$this->input->post('parent_section'))
				->select_max('sort')->get();
		$sort = $sort->sort+1;		
		
		$s = new Section();
		$s->parent_section = $this->input->post( 'parent_section' );
		$s->name = $this->input->post( 'name' );
		$s->sort = $sort;
		$s->view = $this->input->post( 'view' );
		$s->save();
		
		redirect( 'sectionEditor' );
	}
	
	function delete($id){
		
		$s = new Section($id);
		$s->delete();
				
		redirect( 'sectionEditor' );
	}
	
	function edit($id){
		
		$this->load->library( 'gui' );
		
		$s = new Section($id);
		
		$hidden = array( 
			'id'				=>$id,
			'parent_section'	=>$s->parent_section,
			'sort'				=>$s->sort
		);
		
		$this->print_text( $this->gui->form(
			site_url( 'sectionEditor/editaction' )
			,array(
				'Name :'	=>$this->gui->textbox( 'name', $s->name )
				,'view'		=>$this->gui->permission( 'view', $s->view )
				,''			=>$this->gui->button( '', 'Edit Section', array('type'=>'submit') )
							.anchor('sectionEditor/delete/'.$id,
							'I want to delete That Section','onclick="return confirm(\'Are You Sure ?\');"')
			)
			,''
			,$hidden
		));
	}
	
	function editaction(){
		
		$s 			= new Section($this->input->post( 'id' ));
		$s->name 	= $this->input->post( 'name' );
		$s->view 	= $this->input->post( 'view' );
		$s->save();
		
		redirect( 'sectionEditor' );
	}
	
}