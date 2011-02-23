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

		$this->show_statusbar 	= FALSE;
		$this->show_title 		= FALSE;
		$this->show_toolbar 	= FALSE;
		$this->pages 			= array(
								"chooser"=>"Choose a Component",
								"data"=>"Data Editor"
								);

								$this->load->library('gui');
	}

	function addaction() {

		$c = new content();

		if( $this->input->post( "id" )!==FALSE ){
			$c->get_by_id( $this->input->post( "id" ) );
			$old_edit = $c->can_edit();
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
		$c->addin 			= $this->input->post( "addin" );
		$c->edit 			= $this->input->post( "edit" );
		$c->del 			= $this->input->post( "del" );
		$c->info 			= $this->input->post( "info" );
		$c->filter 			= $this->input->post( "filter" );

		$sec 	= new Section($c->parent_section);
		$p 		= new Content($c->parent_content);

		if(		( $p->can_addin() AND  $this->input->post( "id" )===FALSE )
		OR  ( $this->input->post( "id" )!==FALSE AND $old_edit ) ){

			if( $this->input->post( "id" )===FALSE ){
				$c->attach( $sec, $p, $c->cell, $c->sort );
				$this->add_info( 'Content added' );
			}else{
				$c->save();
				$this->ajax = TRUE;
				$this->print_text( "Content Edited" );
			}

		}else{
			show_error( 'Permission denied' );
		}
	}

	function chooser($section,$content,$cell,$sort)
	{
		add('jquery/jquery.js');
		add(<<<EOT
<script language="javascript" >
$(function(){
	$('.links div').click(function(){
		v = $(this).attr('rel');
		$(this).siblings('input').val(v);
		$(this).parents('form').submit();
	});	
});
</script>
EOT
		);
		add(<<<EOT
<style>
.links div {
display:inline-block;
padding:10px;
width:190px;
}
.links div a {
font-size:1.2em;
text-decoration:none;
color: black;
display: block;
}

.links div a:hover {
	color: #003A49;
}
.links img {
float:left;
margin-right:10px;
}
</style>
EOT
		);


		$this->load->helper('directory');
		$contents = directory_map( APPPATH.'views/content' );

		function icon( $icon, $text, $path ){

			$path = ($path=='')? '' : substr( $path, 1).'/';
			$text = substr( $text, 0, strrpos($text, '.') );
			$base = base_url();
			return <<<EOT
	<div rel="{$path}{$text}.php" ><a href="#">
	<img src="$base$icon" >$text
	</a></div>
EOT;
		}

		function render( $path, $arr ){

			$header = substr( $path, 1);
			$txt =  "<hr /><h3>$header</h3>";
			foreach ($arr as $key=>$value){
				if( is_string($value) and $value!='index.html' ){
					$img = substr( $value, 0, strrpos($value, '.') ).'.png';
					$img_p = 'assets/admin/content/'.$path.'/';
					if( file_exists( $img_p.$img ) ){
						$txt .= icon( $img_p.$img, $value, $path );
					}else{
						$txt .= icon( 'assets/admin/content/template.png', $value, $path );
					}
				}

			}
			foreach ($arr as $key=>$value){
				if( is_array($value) ){
					$txt .= render( $path.'/'.$key, $value );
				}
			}

			return $txt;
		}
		$chooser = '<div class="links" >'.$this->gui->hidden( 'path' ).render( '', $contents).'</div>';

		$hidden = array(
				'parent_section'=>$section
		,'parent_content'=>$content
		,'cell'=>$cell
		,'sort'=>$sort
		);

		$this->print_text( $this->gui->form(
		site_url('editor/data'),
		array(""=>$chooser),
		'',
		$hidden
		));

		$recycle = new Content();
		$recycle->get_by_parent_content(0);
		if( $recycle->count >0 ){
			foreach ( $recycle as $item ){
				$s = new Section($item->parent_section);
				$recycle_array->{$item->id} = $item->path.'('.$s->name.')';
			}

			$this->print_text( $this->gui->form(
			site_url('editor/restore'),
			array(
			"from Recycle bin"=>$this->gui->dropdown( "id", '',$recycle_array )
			,""=> $this->gui->button( "", "Restore that content", array("type"=>"submit") )
			)
			,''
			,$hidden
			));
		}

	}

	function data($edit=NULL){

		$this->load->helper('directory');

		function remove_ext($item)
		{ return substr( $item, 0, strrpos($item,'.') ); }
		$filters_list = directory_map(APPPATH.'views/filter');
		$filters_list = array_map( 'remove_ext', $filters_list );

		/********************************************
		 * checking if the page has a ID get paramter
		 * for edit purposes
		 ********************************************/
		$con = new Content($edit);
		if(! $con->exists() )
		$edit = FALSE;
		else
		$info = json_decode( $con->info );

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

		// determine the contetn type
		$form_ajax_url = site_url( 'editor/addaction' );
		if( $edit === FALSE ){
			$submit_script = "dijit.byId('basic_form').submit();";
		}else{
			$submit_script = <<<EOT
	dojo.xhrPost({           
         url: "$form_ajax_url",
         handleAs: "text",
         preventCache: true,               
         content: dojo.formToObject("basic_form"),
         load: function(response, args) {
				alert( response );
			 },
         error: function(response, args) {
				alert( response );
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

		if( $edit === FALSE )
		$button = $this->gui->button( '','Add Content'.$script );
		else
		$button = $this->gui->button( '','Edit Content'.$script );

		if( $this->ion_auth->is_admin() )
		$input = 'permission';
		else{
			$input = 'hidden';
		}

		if( $edit === FALSE ){
			$p_cont = new Content($hidden['parent_content']);

			if( $this->ion_auth->is_admin() )
			$input = 'permission';
			else
			{
				$input = 'hidden';
			}

			$Basic_Form = 	$this->gui->form(
			site_url('editor/addaction')
			,array(
		"Title : " => $this->gui->textbox('title'),
		"Show in subsections : " => $this->gui->checkbox('subsection'),
		"View permissions : " => $this->gui->$input('view', $p_cont->view),
		"Add in permissions : " => $this->gui->$input('addin', $p_cont->addin),
		"Edit permissions : " => $this->gui->$input('edit', $p_cont->edit),
		"Delete permissions : " => $this->gui->$input('del', $p_cont->del),
		"Filters : "=> $this->gui->select_sort( 'filter',$filters_list ),
		"" => $button
			)
			,array( 'id'=>'basic_form' )
			,$hidden
			);
		}else{
			$Basic_Form = 	$this->gui->form(
			site_url('editor/addaction')
			,array(
		"Title : " => $this->gui->textbox('title', $con->title ),
		"Show in subsections : " => $this->gui->checkbox('subsection','subsection', $con->subsection),
		"View permissions : " => $this->gui->$input('view', $con->view),
		"Add in permissions : " => $this->gui->$input('addin', $con->addin),
		"Edit permissions : " => $this->gui->$input('edit', $con->edit),
		"Delete permissions : " => $this->gui->$input('del', $con->del),
		"Filters : "=> $this->gui->select_sort( 'filter', $filters_list, $con->filter ),
		"" => $button
			)
			,array( 'id'=>'basic_form' )
			,$hidden
			);
		}
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

		$Plugin_Data = $this->load->view( 'content/'.$hidden['path'], array( "mode"=>"config" ), TRUE );
		$Plugin_Data = json_decode( $Plugin_Data );
		$Plugin_Form_Data = array();

		// starting to make the form if it is exists
		if( is_object( $Plugin_Data ) ){
			// building each field
			foreach( $Plugin_Data as $key=>$value ){

				// build the field depending on the type
				if( is_object( $value ) ){
					// this line gets the default value if in insertion mode and the
					// stored value if in the edit mode
					$cVal = '';
					$cVal = ( $edit===FALSE )? @$value->default: @$info->$key;
					$current_field = $this->gui->textbox( $key, @$value->default );

					switch( $value->type ){
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
							@$value->options );
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
				}else if( is_string( $value ) == TRUE ){
					$current_field = $this->gui->info( $value );
				}

				// checking the existance of label
				if( isset( $value->label )==TRUE )
				$Plugin_Form_Data[$value->label] = $current_field;
				else
				$Plugin_Form_Data[$key] = $current_field;

			}
		}

		if( count($Plugin_Form_Data) > 0 ){
			$Plugin_Form = $this->gui->form( '#', $Plugin_Form_Data, array("id"=>"info_form"));
			$this->print_text( $this->gui->accordion( array("Basic Data"=>$Basic_Form,"Plugin Data"=>$Plugin_Form) ));
		}else{
			$this->print_text( $this->gui->accordion( array("Basic Data"=>$Basic_Form) ));
		}

	}

	function delete_children($id){


		$c = new Content($id);

		if( $c->exists() ){
			if( $c->can_delete() ){
				$children = new Content();
				$children->get_by_parent_content( $c->id );
				$children->delete_all();
				$this->add_info( ' Children deleted' );
			}else{
				show_error( 'permission denied! please check your adminstrator' );
			}
		}else{
			show_error( 'Content not found' );
		}

	}

	function delete($id){

		$c = new Content($id);
		if( $c->exists() ){
			if( $c->can_delete() ){
				$c->delete();
				$this->add_info( 'Content deleted' );
			}else{
				show_error( 'permission denied! please check your adminstrator' );
			}
		}else{
			show_error( 'Content not found' );
		}

	}

	function down($id){


		$c = new Content($id);

		if( $c->exists() ){
			if( $c->can_edit() ){
				if( $c->move_down() ){
					$this->add_info( 'Content moved down' );
				}else{
					$this->add_info( 'Content i already the last' );
				}
			}else{
				show_error( 'permission denied! please check your adminstrator' );
			}
		}else{
			show_error( 'Content not found' );
		}

	}

	function edit($id,$sec){

		$cont = new Content($id);
		$can_edit = $cont->can_edit();
		$can_delete = $cont->can_delete();
		$parent = $cont->parent_content;
		$cell = $cont->cell;
		$sort = $cont->sort;
		$p = new Content($cont->parent_content);
		$img_url = base_url().'assets/admin/images/';
		add(<<<EOT
<style>
.links div {
	display: inline-block;
	padding: 10px;
	width: 200px;
}

.links div a {
	font-size: 1.5em;
	text-decoration: none;
	color: black;
	display: block;
}

.links div a:hover {
	color: #003A49;
}

.links img {
	float: left;
	margin-right: 10px;
}
</style>
EOT
		);
		$this->print_text('<div class="links">');
		if( $can_edit ){
			$this->print_text('
		<div><a href="'.site_url("editor/data/$id").'"> 
			<img src="'.$img_url.'edit.png">Edit Properties 
		</a></div>
		<div><a href="'.site_url("editor/up/$id").'">
			<img src="'.$img_url.'move up.png">Move Up 
		</a></div>
		<div><a href="'.site_url("editor/down/$id").'">
			<img src="'.$img_url.'move down.png">Move Down 
		</a></div>');
		}
		if( $p->can_addin() ){
			$this->print_text('
		<div><a	href="'.site_url("editor/chooser/{$sec}/$parent/$cell/$sort" ).'"> 
			<img src="'.$img_url.'add before.png">Add Before 
		</a></div>
		<div><a href="'. site_url("editor/chooser/{$sec}/$parent/$cell/".($sort+1)).'">
			<img src="'.$img_url.'add after.png">Add After 
		</a></div>');
		}
		if( $can_delete ){
			$this->print_text('
			<div><a href="'. site_url("editor/delete/$id") .'"> 
				<img src="'.$img_url.'delete.png">Delete 
			</a></div>
			<div><a href="'. site_url("editor/recycle/$id") .'">
				<img src="'.$img_url.'recycle.png">Put in Recycle 
			</a></div>
			<div><a href="'. site_url("editor/delete_children/$id") .'">
				<img src="'.$img_url.'delete children.png">Delete Children 
			</a></div>
			<div><a href="'. site_url("editor/recycle_children/$id") .'"> 
				<img src="'.$img_url.'recycle children.png">Recycle Children 
			</a></div>');
		}

		$this->print_text('
		<div><a class="iframe" href="'. site_url("editor/info/$id") .'">
			<img src="'.$img_url.'info.png">Information 
		</a></div>
	</div>');
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
label {
	font-weight: bold;
}

tr {
	border-bottom: 1px solid black;
}
</style>
EOT
		);
	}

	function recycle_children($id){

		$c = new Content($id);

		if( $c->exists() ){
			if( $c->can_delete() ){
				$children = new Content();
				$children->get_by_parent_content($c->id);
				foreach ($children as $child ){
					$child->deattach();
					$child->parent_content = 0;
					$child->save();
				}
				$this->add_info( 'Children has been removed to recycle bin' );
			}else{
				show_error( 'permission denied! please check your adminstrator' );
			}
		}else{
			show_error('Content not found');
		}

	}

	function recycle($id){

		$c = new Content($id);

		if( $c->exists() ){
			if( $c->can_delete() ){
				$c->deattach();
				$c->parent_content = 0;
				$c->save();
				$this->add_info( 'Content has been removed to recycle bin' );
			}else{
				show_error( 'permission denied! please check your adminstrator' );
			}
		}else{
			show_error( 'Content not found' );
		}
	}

	function restore(){

		$c  = new content($this->input->post( "id" ));
		$c->parent_section = $this->input->post( "parent_section" );
		$c->parent_content = $this->input->post( "parent_content" );
		$c->cell = $this->input->post( "cell" );
		$c->sort = $this->input->post( "sort" );
		$sec = new Section( $c->parent_section);
		$p = new Content($c->parent_content);

		if(	$p->can_addin() ){
			$c->attach( $sec, $p, $c->cell, $c->sort );
			$this->add_info( 'Content restored' );
			$c->save();
		}else{
			show_error( 'Permission denied' );
		}
	}

	function up($id){

		$c = new Content($id);

		if( $c->exists() ){
			if( $c->can_edit() ){
				if( $c->move_up() ){
					$this->add_info( 'Content moved up' );
				}else{
					$this->add_info( 'Content i already the first' );
				}
			}else{
				show_error( 'permission denied! please check your adminstrator' );
			}
		}else{
			show_error( 'Content not found' );
		}
	}
}
