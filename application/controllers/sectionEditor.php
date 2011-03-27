<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * this system application view/edit/remove pages 
 *
 * that application view pages as tree and can add, remove and edit 
 * pages, it could make you build all your website heirarchy 
 *
 * @copyright  2011 Emad Elsaid a.k.a Blaze Boy
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt   GPL License 2.0
 * @link       https://github.com/blazeeboy/Codeigniter-Egypt
 */ 
class SectionEditor extends Application {
	
	public function __construct(){
		parent::__construct();
		
		$this->perm 	= 'admin';
		$this->name 	= lang('system_sections_editor');
		$this->author 	= "Emad Elsaid";
		$this->website 	= "http://blazeeboy.blogspot.com";
		$this->version 	= "0.1";
		$this->pages 	= array(
							'index' => lang('system_sections'),
							'add' => lang('system_new_section')
							);

		$this->load->library('gui');
	}
	
	/**
	 * shows a tree of system sections
	 * 
	 * @return void
	 */
	public function index(){

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
	
	/**
	 * get tree of sections and serialize them into JSON object
	 * 
	 * @return void
	 */
	public function queryTree(){
		
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
	
	/**
	 * add section to system
	 * 
	 * @return void
	 */
	public function add(){
		
		$this->print_text( $this->gui->form(
			site_url( 'sectionEditor/addaction' )
			,array(
					lang('system_parent_section') => $this->gui->section( 'parent_section')
					,lang('system_name_label') => $this->gui->textbox( 'name' )
					,lang('system_view_perm') => $this->gui->permission( 'view' )
					,''=>$this->gui->button( '', lang('system_add_section'), array('type'=>'submit') )
			)
		));
		
	}
	
	/**
	 * add section to system (the action page)
	 * 
	 * @return void
	 */
	public function addaction(){
		
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
	
	/**
	 * delete section from system
	 * 
	 * @return void
	 */
	public function delete($id){
		
		$s = new Section($id);
		$s->delete();
				
		redirect( 'sectionEditor' );
		
	}
	
	/**
	 * edit section in system
	 * 
	 * @return void
	 */
	public function edit($id){
		
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
				lang('system_name_label')	=>$this->gui->textbox( 'name', $s->name )
				,lang('system_view_perm')	=>$this->gui->permission( 'view', $s->view )
				,''			=>$this->gui->button( '', lang('system_edit_section'), array('type'=>'submit') )
							.anchor('sectionEditor/delete/'.$id,
							lang('system_delete_section'),'onclick="return confirm(\''.lang('system_are_you_sure').'\');"')
			)
			,''
			,$hidden
		));
		
	}
	
	/**
	 * edit section in system (the action page)
	 * 
	 * @return void
	 */
	public function editaction(){
		
		$s 			= new Section($this->input->post( 'id' ));
		$s->name 	= $this->input->post( 'name' );
		$s->view 	= $this->input->post( 'view' );
		$s->save();
		
		redirect( 'sectionEditor' );
		
	}
	
}