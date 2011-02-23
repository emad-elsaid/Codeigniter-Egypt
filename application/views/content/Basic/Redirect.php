<?php if( $mode=='config' ){
//the plugin requirements as a JSON object is here ?>
{
	"info": "you can redirect your page to another section in your site or to externel site, externel has the high periority",
	"section":{ "type":"section" },
	"externel":{ "type":"textbox", "label":"Externel URL" }
}


<?php }else if( $mode=='layout' ){ 
//replace 0 with number of cells your plugin has ?>
0


<?php }else if( $mode=='view' ){
//the real content of your plugin goes here ?>
<?php
$c = new Content();
$c->get_by_id( $id );

$u = ( empty($info->externel) )? site_url($info->section) :	$info->externel;

if( ($ci->vunsy->mode()=='edit') and ($c->can_edit() or $c->can_delete()) )
{
	$ci->load->library('gui');
	echo $ci->gui->info( 'Redirect content here to this '. anchor( $u, 'Page' ) );
}
else
{
	redirect( $u );
}

?>
<?php } ?>
