<?php if( $mode=='config' ){ ?>
{
	"background attributes":"if you specified a background image all the related attributes would be wrriten as well in the rendered HTML, otherwise they will be ignored",
	"background_image" : { "type" : "file" },
	"repeat" : { "type" : "dropdown",
			"options" :{"no-repeat":"no-repeat",
				"repeat":"repeat",
				"repeat-x":"repeat-x",
				"repeat-y":"repeat-y"},
			 "default" : "0" },
	"attachment" : { "type" : "dropdown", "options" : {"scroll":"scroll","fixed":"fixed"}, "default": "0" },
	"horizontal_position" : { "type" : "textbox", "default" : "left" },
	"vertical_position" : { "type" : "textbox", "default" : "top" },
	"style sheets":" you can include Reset and Text stylesheets from here ",
	"Reset960":{"type":"checkbox"},
	"Text960":{"type":"checkbox"},
	"extra attributs":"you can specify extra body classes and style",
	"class":{"type":"textbox"},
	"style":{"type":"textarea"},
	"favIcon":{"type":"file"},
	"javascript_files" : {"type":"file list"},
	"css_files" : {"type":"file list"}
}
<?php }else if( $mode=='layout' ){ 
//replace 0 with number of cells your plugin has ?>
0

<?php }else if( $mode=='view' ){
//the real content of your plugin goes here ?>

<?php 
$ci =& get_instance();
if( $ci->vunsy->edit_mode() )
{
	$c = new Content();
	$c->get_by_id( $id );
	
	if( $c->can_edit() or $c->can_delete() )
	{
		$ci->load->library( 'gui' );
		echo $ci->gui->info( 'Document settings here' );
	}
}

?>

<?php
	$local = base_url();
	
	if( $info->Reset960 ) add( 'assets/960.gs/reset.css' );
	if( $info->Text960 ) add( 'assets/960.gs/text.css' );
	if( !empty($info->favIcon) ) add('<link rel="icon" href="'.base_url().$info->favIcon.'">');
	add(explode("\n",$info->javascript_files));
	add(explode("\n",$info->css_files));
	
	$style = '';
	if( $info->background_image != '' )
	{
		$style .= "background-image: url({$local}{$info->background_image});";
		$style .= "background-position: {$info->horizontal_position} {$info->vertical_position};";
		$style .= "background-repeat: {$info->repeat};";
		$style .= "background-attachment: {$info->attachment};";
	}
	$style .= $info->style;
	if( $style!='' )
	{
		add("
<style>
body{
	{$style}
}
</style>

		");
		if(!empty($info->class))
		{ 
			add('jquery/jquery.js');
			add(
<<<EOT
<script language="javascript" >
	$(function(){
		$(document).addClass('{$info->class}');
	});
</script>
EOT
			);
		}
	}
}
?>
