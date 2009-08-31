<?php if( $mode=='config' ){
//the plugin requirements as a JSON object is here ?>
{
	"header":{"type":"dropdown", "options":{"h1":"h1","h2":"h2","h3":"h3","h4":"h4","h5":"h5","h6":"h6"}},
	"id":{"type":"dropdown", "options":{" ":"none", "branding":"branding","page-heading":"page-heading"}},
	"text":{"type":"textbox"},
	"link":{"type":"checkbox"},
	"linkTo":{"type":"section"}
}


<?php }else if( $mode=='layout' ){ 
//replace 0 with number of cells your plugin has ?>
0


<?php }else if( $mode=='view' ){
//the real content of your plugin goes here ?>
<?php 
add( 'assets/960.gs/reset.css');
add( 'assets/960.gs/text.css');
//add( 'assets/960.gs/960.css');
add( 'assets/960.gs/layout.css');
//add( 'assets/960.gs/nav.css');
//add( 'jquery/jquery.js' );
//add( 'jquery/jquery-ui.js' );
//add( 'assets/960.gs/jquery-fluid16.js' );
add( '<!--[if IE 6]><link rel="stylesheet" type="text/css" href="'.base_url().'assets/960.gs/ie6.css" media="screen" /><![endif]-->');
add('<!--[if IE 7]><link rel="stylesheet" type="text/css" href="'.base_url().'assets/960.gs/ie.css" media="screen" /><![endif]-->');
?>
<?php if( $info->link==TRUE ){ ?>
<<?=$info->header?> id="<?=$info->id?>">
	<a href="<?= site_url($info->linkTo) ?>"><?=$info->text?></a>
</<?=$info->header?>>
<?php }else{ ?>
<<?=$info->header?> id="<?=$info->id?>">
	<?=$info->text?>
</<?=$info->header?>>
<?php } ?>
<?php } ?>
