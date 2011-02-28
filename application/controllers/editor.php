<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Editor extends Application {

	function __construct()
	{
		parent::__construct();

		$this->perm = 'admin';

		$this->name 	= "Content Editor";
		$this->author 	= "Emad Elsaid";
		$this->website 	= "http://blazeeboy.blogspot.com";
		$this->version 	= "0.1";

		$this->show_toolbar 	= FALSE;
		$this->pages 			= array();

		$this->load->library('gui');
	}

	function chooser($section, $content, $cell, $sort){
		$this->show_toolbar = TRUE;
		add('dojo.data.ItemFileReadStore');
		add('dijit.tree.ForestStoreModel');
		add('dijit.Tree');
		add('jquery/jquery.js');
		
		$this->print_text('<div dojoType="dojo.data.ItemFileReadStore" url="'.site_url('editor/queryTree').'" jsId="ordJson"></div>');
		$this->print_text('<div dojoType="dijit.tree.ForestStoreModel" childrenAttrs="line" rootLabel="Order" store="ordJson" jsId="ordModel"></div>');
		$this->print_text('<div dojoType="dijit.Tree" id="ordTree" model="ordModel">
		<script type="dojo/method" event="onClick" args="item">
		if(item.path!=undefined){
			$("input[name=path]").val(item.path[0]);
			$("form").submit();
		}	
		</script>
		</div>');
		$hidden = array('parent_section'=>$section,'parent_content'=>$content, 'cell'=>$cell,'sort'=>$sort,'path'=>'');
		$this->print_text( $this->gui->form(site_url('editor/data'),array(),'',	$hidden	));
	}
	
	
	/*
	 * this method need to be enhanced
	 * it generate JSON object for Dojo tree of previous method,
	 * you can generate the object with json_encode
	 */ 
	function queryTree(){
		$this->ajax = TRUE;
		$this->load->helper('directory');
		$contents = directory_map( APPPATH.'views/content' );
		$folders = array_keys($contents);
		$output = "{ identifier: 'id', label: 'description',items: [";
		$k=0;
		foreach( $contents as $name=>$directory ){
			$k++;
			if( is_array($directory)){
				$output .= "{ id: '$k', description:\"$name\" ,line:[";
				
				for( $i=0; $i<count($directory); $i++ ){
					$k++;
					if( $directory[$i]!='index.html'){
						$output .= "{ id: '$k', path:'$name\/{$directory[$i]}', description: '{$directory[$i]}'}";
						if( $i<count($directory)-1 )
							$output .= ',';
					}
				}
				
				$output .= ']}';
			}else{
				if( $directory!='index.html')
				$output .= "{ id: '$k', path:'$directory', description:'$directory' }";
				
			}
			
			if( $name!=$folders[count($folders)-1] and (is_array($directory) or $directory!='index.html') )
					$output .= ',';
		}
		$output .= "]}";
		
		$this->print_text($output);
		
	}
	
	function addaction() {

		$c = new content();

		if( $this->input->post( "id" )!==FALSE ){
			$c->get_by_id( $this->input->post( "id" ) );
		}else if( $this->input->post( "id" )===FALSE ){
			$c->user = $this->ion_auth->get_user();
			$c->user = $c->user->id;
		}

		$c->parent_section 	= $this->input->post( "parent_section" );
		$c->parent_content 	= $this->input->post( "parent_content" );
		$c->title 			= $this->input->post( "title" );
		$c->cell 			= $this->input->post( "cell" );
		$c->sort 			= $this->input->post( "sort" );
		$c->path 			= $this->input->post( "path" );
		$c->type 			= $this->input->post( "type" );
		$c->subsection 		= $this->input->post ( "subsection" )==FALSE? FALSE:TRUE;
		$c->view 			= $this->input->post( "view" );
		$c->info 			= $this->input->post( "info" );
		$c->filter 			= $this->input->post( "filter" );

		$p 		= new Content($c->parent_content);

		if( $this->input->post( "id" )===FALSE ){
			$this->add_info( 'Content added' );
		}else{
			$this->ajax = TRUE;
			$this->print_text( "Content Edited" );
		}
		$c->save();

	}

	function data($edit=NULL,$sec=NULL){

		/********************************************
		 * checking if the page has a ID get paramter
		 * for edit purposes
		 ********************************************/
		$this->load->helper('directory');
		add('dijit.Dialog');
		
		// getting the content
		$con = new Content($edit);
		if(! $con->exists() )
		$edit = FALSE;
		else
		$info = json_decode( $con->info );

		
		// creating menu if editing a content
		if( $edit )
		{
			$this->show_toolbar = TRUE;
			$this->pages = array();

			$parent = $con->parent_content;
			$p = new Content($con->parent_content);

			$this->pages['up/'.$con->id] = 'Move Up';
			$this->pages['down/'.$con->id] = 'Move Down';
			$this->pages['chooser/'.$sec.'/'.$parent.'/'.$con->cell.'/'.$con->sort] = 'Add Before';
			$this->pages['chooser/'.$sec.'/'.$parent.'/'.$con->cell.'/'.($con->sort+1)] = 'Add After';
			$this->pages['delete/'.$con->id] = 'Delete';
			$this->pages['delete_children/'.$con->id] = 'Delete Children';
			$this->pages['info/'.$con->id] = 'Information';
		}

		
		// getting the filter names
		function remove_ext($item)
		{ return substr( $item, 0, strrpos($item,'.') ); }
		$filters_list = directory_map(APPPATH.'views/filter');
		$filters_list = array_map( 'remove_ext', $filters_list );

		
		// creating hidden properties
		$hidden = array();

		if( $edit === FALSE ){
			$hidden['parent_section'] = $this->input->post( "parent_section" );
			$hidden['parent_content'] = $this->input->post( "parent_content" );
			$hidden['cell'] = $this->input->post( "cell" );
			$hidden['sort'] = $this->input->post( "sort" );
			$hidden['path'] = $this->input->post( "path" );
			$hidden['info'] = "";
		}else{
			$hidden['id'] = $con->id;
			$hidden['parent_section'] = $con->parent_section;
			$hidden['parent_content'] = $con->parent_content;
			$hidden['cell'] = $con->cell;
			$hidden['sort'] = $con->sort;
			$hidden['path'] = $con->path;
			$hidden['info'] = $con->info;
		}

		// action of the form button
		$form_ajax_url = site_url( 'editor/addaction' );
		if( $edit === FALSE )
			$submit_script = "dijit.byId('basic_form').submit();";
		else{
			$submit_script = <<<EOT
	dojo.xhrPost({           
         url: "$form_ajax_url",
         handleAs: "text",
         preventCache: true,               
         content: dojo.formToObject("basic_form"),
         load: function(response, args) {
			        Dlg = new dijit.Dialog({
			            title: "Saved Successfully",
			            style: "width: 300px",
			            content : response
			        });
			        Dlg.show();
			        
			 },
         error: function(response, args) {
					Dlg = new dijit.Dialog({
			            title: "An error Occured",
			            style: "width: 300px",
			            content : response
			        });
			        Dlg.show();
			 }
    });
EOT;
		}
		
		$script  = <<<EOT
<script type="dojo/method" event="onClick" args="evt">
if( dijit.byId('info_form')!=undefined )
{
	dojo.query("[name='info']")[0].value = dojo.toJson(dijit.byId('info_form').getValues());
}
		$submit_script
</script>
EOT;


		if( $edit === FALSE ){
			
			$p_cont = new Content($hidden['parent_content']);
			
			$Basic_Form = 	$this->gui->form(
			site_url('editor/addaction')
			,array(
		"Title : " 					=> $this->gui->textbox('title'),
		"Show in subsections : " 	=> $this->gui->checkbox('subsection'),
		"View permissions : " 		=> $this->gui->permission('view', $p_cont->view),
		"Filters : "				=> $this->gui->select_sort( 'filter',$filters_list ),
		"" 							=> $this->gui->button( '','Save'.$script )
			)
			,array( 'id'=>'basic_form' )
			,$hidden
			);
		}else
			$Basic_Form = 	$this->gui->form(
			site_url('editor/addaction')
			,array(
		"Title : " 					=> $this->gui->textbox('title', $con->title ),
		"Show in subsections : " 	=> $this->gui->checkbox('subsection','subsection', $con->subsection),
		"View permissions : " 		=> $this->gui->permission('view', $con->view),
		"Filters : "				=> $this->gui->select_sort( 'filter', $filters_list, $con->filter ),
		"" 							=> $this->gui->button( '','Save'.$script )
			)
			,array( 'id'=>'basic_form' )
			,$hidden
			);
		
		//===============================================
		/*OUR JSON OBJECT LIKE THAT

		{
		"text":{
			"type":"editor"
			,"label":"Text Label"
			,"default":"default text"
		}
		,"title":{
			"type":"textbox"
		}
		,"titlecolor":"information to display"
		}
		**/
		$this->load->helper('spyc');
		$Plugin_Data = $this->load->view( 'content/'.$hidden['path'], array( "mode"=>"config" ), TRUE );
		$Plugin_Data = spyc_load( $Plugin_Data );
		$Plugin_Form_Data = array();

		// starting to make the form if it is exists
		if( !empty( $Plugin_Data ) ){
			// building each field
			foreach( $Plugin_Data as $key=>$value ){
				// build the field depending on the type
				if( is_array( $value ) ){
					// this line gets the default value if in insertion mode and the
					// stored value if in the edit mode
					$cVal = '';
					$cVal = ( $edit===FALSE )? @$value['default']: @$info->$key;
					$current_field = $this->gui->textbox( $key, @$value['default'] );
					
					switch( $value['type'] ){
						case "textbox":
							$current_field = $this->gui->textbox( $key, $cVal );
							break;
						case "textarea":
							$current_field = $this->gui->textarea( $key, $cVal );
							break;
						case "color":
							$current_field = $this->gui->color( $key, $cVal );
							break;
						case "date":
							$current_field = $this->gui->date( $key, $cVal );
							break;
						case "editor":
							$current_field = $this->gui->editor( $key, $cVal );
							break;
						case "file":
							$current_field = $this->gui->file( $key, $cVal );
							break;
						case "file list":
							$current_field = $this->gui->file_list( $key, $cVal );
							break;
						case "folder":
							$current_field = $this->gui->folder( $key, $cVal );
							break;
						case "model":
							$current_field = $this->gui->model( $key, $cVal );
							break;
						case "number":
							$current_field = $this->gui->number( $key, $cVal );
							break;
						case "password":
							$current_field = $this->gui->password( $key, $cVal );
							break;
						case "time":
							$current_field = $this->gui->time( $key, $cVal );
							break;
						case "checkbox":
							$current_field = $this->gui->checkbox( $key,$key, $cVal );
							break;
						case "dropdown":
							$current_field = $this->gui->dropdown( $key, $cVal,
							@$value['options'] );
							break;
						case "section":
							$current_field = $this->gui->section( $key, $cVal );
							break;
						case "permission":
							$current_field = $this->gui->permission( $key, $cVal );
							break;
						case "smalleditor":
							$current_field = $this->gui->smalleditor( $key, $cVal );
							break;
					}
				}else if( is_string( $value ) == TRUE )
					$current_field = $this->gui->info( $value );

				// checking the existance of label
				if( is_array($value) and array_key_exists('label',$value)==TRUE )
				$Plugin_Form_Data[$value['label']] = $current_field;
				else
				$Plugin_Form_Data[$key] = $current_field;

			}
		}

		if( count($Plugin_Form_Data) > 0 ){
			$Plugin_Form = $this->gui->form( '#', $Plugin_Form_Data, array("id"=>"info_form"));
			$this->print_text( $this->gui->accordion( array("Basic Data"=>$Basic_Form,"Plugin Data"=>$Plugin_Form) ));
		}else
			$this->print_text( $this->gui->accordion( array("Basic Data"=>$Basic_Form) ));
		
	}

	function delete_children($id){

		$c = new Content($id);

		if( $c->exists() ){
			$children = new Content();
			$children->get_by_parent_content( $c->id );
			$children->delete_all();
			$this->add_info( 'Children deleted' );
		}else{
			show_error( 'Content not found' );
		}

	}

	function delete($id){

		$c = new Content($id);
		if( $c->exists() ){
			$c->delete();
			$this->add_info( 'Content deleted' );
		}else{
			show_error( 'Content not found' );
		}

	}

	function down($id){

		$c = new Content($id);

		if( $c->exists() ){
			if( $c->move_down() ){
				$this->add_info( 'Content moved down' );
			}else{
				$this->add_info( 'Content i already the last' );
			}
		}else{
			show_error( 'Content not found' );
		}

	}

	function info($content_id){

		$content_ins = new Content($content_id);
		if( ! $content_ins->exists() )
		show_error( 'content not found' );

		$parent_content = new Content($content_ins->parent_content);

		$user =  $this->ion_auth->get_user($content_ins->user);

		$parent_section = new Section($content_ins->parent_section);
		$children = new Content();
		$children->get_by_parent_section( $content_ins->id );

		$data_table = array(
				'Content ID'=>$content_ins->id,
				'Content Title'=>$content_ins->title,
				'Content path'=>$content_ins->path,
				'Section'=> empty($parent_section->name) ? 'Index':$parent_section->name,
				'Subsections'=> $content_ins->subsection ? 'Yes':'No',
				'User added it'=> $user->username,
				'Parent'=>$parent_content->path,
				'Cell'=>$content_ins->cell,
				'Sort'=>$content_ins->sort,
				'Children count'=>$children->result_count()
		);

		$this->add_info( 'Content information and the containers' );
		$this->print_text( $this->gui->form( '#', $data_table ) );
		add(<<<EOT
<style>
label {font-weight: bold;}
tr {border-bottom: 1px solid black;}
</style>
EOT
		);
	}


	function up($id){

		$c = new Content($id);

		if( $c->exists() ){
			if( $c->move_up() ){
				$this->add_info( 'Content moved up' );
			}else{
				$this->add_info( 'Content i already the first' );
			}
		}else{
			show_error( 'Content not found' );
		}
	}
}
