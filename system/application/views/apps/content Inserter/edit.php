<?php

$ci =& get_instance();
$ci->load->library('gui');

$cont = new Content();
$cont->get_by_id($ci->uri->segment(5));

if( $cont->exists() )
{

	$hidden = array();

	/*$hidden['parent_section'] = $ci->input->post( "parent_section" );
	$hidden['parent_content'] = $ci->input->post( "parent_content" );
	$hidden['cell'] = $ci->input->post( "cell" );
	$hidden['sort'] = $ci->input->post( "sort" );
	$hidden['path'] = $ci->input->post( "path" );*/
	$hidden['info'] = $cont->info;

	// determine the contetn type
	$explodedPath = explode( '/', $cont->path );
	$hidden['type'] = $explodedPath[0];

	$script  = <<<EOT

	<script type="dojo/method" event="onClick" args="evt">
	dojo.query("[name='info']")[0].value = dojo.toJson(dijit.byId('info_form').getValues());
	dijit.byId('basic_form').submit();
	</script>
EOT;

	$button = $ci->gui->button( '','Add Content'.$script );

	$Basic_Form = 	$ci->gui->form(
		$ci->app->app_url('addaction')
		,array(
		"Show in subsections : " => $ci->gui->checkbox('subsection')
		,"View permissions : " => $ci->gui->textarea('view')
		,"Add in permissions : " => $ci->gui->textarea('addin')
		,"Edit permissions : " => $ci->gui->textarea('edit')
		,"Delete permissions : " => $ci->gui->textarea('del')
		,"" => $button
		)
		,array( 'id'=>'basic_form' )
		,$hidden
	);
	//===============================================
	/*OUR JSON OBJECT WILL BE
	 * object(
	 * 	filed1 = object( type, default, info = object(key1=value1,key2=value2...etc) )
	 * )
	 * */

	$Plugin_Data = $ci->load->view( $cont->path, array( "mode"=>"config" ), TRUE );
	$Plugin_Data = json_decode( $Plugin_Data );
	$Plugin_Form_Data = array();
	$Plugin_Form = "";

	// starting to make the form if it is exists
	if( is_object( $Plugin_Data ) AND isset( $Plugin_Data->info) AND is_object($Plugin_Data->info) )
	{
		// building each field
		foreach( $Plugin_Data->info as $key=>$value )
		{
			$current_field = $ci->gui->textbox( $key, @$value->default );
			// build the field depending on the type
			switch( $value->type )
			{
				case "textbox":
					$current_field = $ci->gui->textbox( $key, @$value->default );
					break;
				case "textarea":
					$current_field = $ci->gui->textarea( $key, @$value->default );
					break;
				case "color":
					$current_field = $ci->gui->color( $key, @$value->default );
					break;
				case "date":
					$current_field = $ci->gui->date( $key, @$value->default );
					break;
				case "editor":
					$current_field = $ci->gui->editor( $key, @$value->default );
					break;
				case "file":
					$current_field = $ci->gui->file( $key, @$value->default );
					break;
				case "number":
					$current_field = $ci->gui->number( $key, @$value->default );
					break;
				case "password":
					$current_field = $ci->gui->password( $key, @$value->default );
					break;
				case "time":
					$current_field = $ci->gui->time( $key, @$value->default );
					break;
				case "dropdown":
					$current_field = $ci->gui->dropdown( $key, @$value->default );
					break;
				case "checkbox":
					$current_field = $ci->gui->dropdown( $key, $key, @$value->default,
													@$value->options );
					break;				
			}
			
			if( isset( $value->tooltip ) )
				$current_field .= $ci->gui->tooltip( $key, $value->tooltip );
				
			$Plugin_Form_Data[$key] = $current_field;
			
		}
	}

	$Plugin_Form_Data[""] = 
	$Plugin_Form .= $ci->gui->form( '#', $Plugin_Form_Data, array("id"=>"info_form"));
	//===============================================
	echo $ci->gui->accordion( array("Basic Data"=>$Basic_Form, "Plugin Data"=>$Plugin_Form ) );
}
else
{
	$ci->app->add_error( "This content doesn't exists " );
}
