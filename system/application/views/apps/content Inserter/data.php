<?php
$ci =& get_instance();
$ci->load->library('gui');
$ci->load->helper('directory');

function remove_ext($item)
{ return substr( $item, 0, strrpos($item,'.') ); }
$filters_list = directory_map(APP.'views/filter');
$filters_list = array_map( 'remove_ext', $filters_list );

/********************************************
 * checking if the page has a ID get paramter
 * for edit purposes
 ********************************************/
$edit = $ci->uri->segment(5);
if( $edit )
{
	$con = new Content();
	$con->get_by_id( $edit );
	if(! $con->exists() )
		$edit = FALSE;
	else
		$info = json_decode( $con->info );
}

$hidden = array();

if( $edit === FALSE )
{
	$hidden['parent_section'] = $ci->input->post( "parent_section" );
	$hidden['parent_content'] = $ci->input->post( "parent_content" );
	$hidden['cell'] = $ci->input->post( "cell" );
	$hidden['sort'] = $ci->input->post( "sort" );
	$hidden['path'] = $ci->input->post( "path" );
	$hidden['info'] = "";
}
else
{
	$hidden['id'] = $con->id;
	$hidden['parent_section'] = $con->parent_section;
	$hidden['parent_content'] = $con->parent_content;
	$hidden['cell'] = $con->cell;
	$hidden['sort'] = $con->sort;
	$hidden['path'] = $con->path;
	$hidden['info'] = $con->info;
}

// determine the contetn type
$form_ajax_url = $ci->app->app_url( 'addaction', true );
if( $edit === FALSE )
{
	$submit_script = "dijit.byId('basic_form').submit();";
}
else
{
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
	$button = $ci->gui->button( '','Add Content'.$script );
else
	$button = $ci->gui->button( '','Edit Content'.$script );

if( $ci->vunsy->user->is_root() )
	$input = 'permission';
else
{
	$input = 'hidden';
}

if( $edit === FALSE )
{
	$p_cont = new Content();
	$p_cont->get_by_id( $hidden['parent_content'] );
	
	if( $ci->vunsy->user->is_root() )
		$input = 'permission';
	else
	{
		$input = 'hidden';
	}
		
	$Basic_Form = 	$ci->gui->form(
		$ci->app->app_url('addaction')
		,array(
		"Title : " => $ci->gui->textbox('title'),
		"Show in subsections : " => $ci->gui->checkbox('subsection'),
		"View permissions : " => $ci->gui->$input('view', $p_cont->view),
		"Add in permissions : " => $ci->gui->$input('addin', $p_cont->addin),
		"Edit permissions : " => $ci->gui->$input('edit', $p_cont->edit),
		"Delete permissions : " => $ci->gui->$input('del', $p_cont->del),
		"Filters : "=> $ci->gui->select_sort( 'filter',$filters_list ),
		"" => $button
		)
		,array( 'id'=>'basic_form' )
		,$hidden
	);
}
else
{
	$Basic_Form = 	$ci->gui->form(
		$ci->app->app_url('addaction')
		,array(
		"Title : " => $ci->gui->textbox('title', $con->title ),
		"Show in subsections : " => $ci->gui->checkbox('subsection','subsection', $con->subsection),
		"View permissions : " => $ci->gui->$input('view', $con->view),
		"Add in permissions : " => $ci->gui->$input('addin', $con->addin),
		"Edit permissions : " => $ci->gui->$input('edit', $con->edit),
		"Delete permissions : " => $ci->gui->$input('del', $con->del),
		"Filters : "=> $ci->gui->select_sort( 'filter', $filters_list, $con->filter ),
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
 * */

$Plugin_Data = $ci->load->view( 'content/'.$hidden['path'], array( "mode"=>"config" ), TRUE );
$Plugin_Data = json_decode( $Plugin_Data );
$Plugin_Form_Data = array();

// starting to make the form if it is exists
if( is_object( $Plugin_Data ) )
{
	// building each field
	foreach( $Plugin_Data as $key=>$value )
	{
		
		// build the field depending on the type
		if( is_object( $value ) )
		{
			// this line gets the default value if in insertion mode and the
			// stored value if in the edit mode
			$cVal = '';
			$cVal = ( $edit===FALSE )? @$value->default: @$info->$key;
			$current_field = $ci->gui->textbox( $key, @$value->default );
			
			switch( $value->type )
			{
				case "textbox":
					$current_field = $ci->gui->textbox( $key, $cVal );
					break;
				case "textarea":
					$current_field = $ci->gui->textarea( $key, $cVal );
					break;
				case "color":
					$current_field = $ci->gui->color( $key, $cVal );
					break;
				case "date":
					$current_field = $ci->gui->date( $key, $cVal );
					break;
				case "editor":
					$current_field = $ci->gui->editor( $key, $cVal );
					break;
				case "file":
					$current_field = $ci->gui->file( $key, $cVal );
					break;
				case "file list":
					$current_field = $ci->gui->file_list( $key, $cVal );
					break;
				case "folder":
					$current_field = $ci->gui->folder( $key, $cVal );
					break;
				case "model":
					$current_field = $ci->gui->model( $key, $cVal );
					break;
				case "app":
					$current_field = $ci->gui->app( $key, $cVal );
					break;
				case "number":
					$current_field = $ci->gui->number( $key, $cVal );
					break;
				case "password":
					$current_field = $ci->gui->password( $key, $cVal );
					break;
				case "time":
					$current_field = $ci->gui->time( $key, $cVal );
					break;
				case "checkbox":
					$current_field = $ci->gui->checkbox( $key,$key, $cVal );
					break;
				case "dropdown":
					$current_field = $ci->gui->dropdown( $key, $cVal,
													@$value->options );
					break;				
				case "section":
					$current_field = $ci->gui->section( $key, $cVal );
					break;					
				case "permission":
					$current_field = $ci->gui->permission( $key, $cVal );
					break;				
				case "smalleditor":
					$current_field = $ci->gui->smalleditor( $key, $cVal );
					break;				
			}
		}
		else if( is_string( $value ) == TRUE )
		{
			$current_field = $ci->gui->info( $value );
		}
		
		// checking the existance of label
		if( isset( $value->label )==TRUE )
			$Plugin_Form_Data[$value->label] = $current_field;
		else
			$Plugin_Form_Data[$key] = $current_field;
		
	}
}

if( count($Plugin_Form_Data) > 0 )
{
	$Plugin_Form = $ci->gui->form( '#', $Plugin_Form_Data, array("id"=>"info_form"));
	echo $ci->gui->accordion( array("Basic Data"=>$Basic_Form,"Plugin Data"=>$Plugin_Form) );
}
else
{
	echo $ci->gui->accordion( array("Basic Data"=>$Basic_Form) );
}
