<?php if( $mode=='config' ){
//the plugin requirements as a JSON object is here ?>
{
	"container":{"type":"checkbox"},
	"container_class" : { "type":"textbox" },
	"container_style" : { "type":"textarea" },
	"size":{"type":"dropdown", "options":{"12":"12 column","16":"16 column"}},
	"text":{"type":"smalleditor"}
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
add( 'jquery/jquery.js' );
//add( 'jquery/jquery-ui.js' );
add( 'assets/960.gs/jquery-fluid16.js' );
add( '<!--[if IE 6]><link rel="stylesheet" type="text/css" href="'.base_url().'assets/960.gs/ie6.css" media="screen" /><![endif]-->');
add('<!--[if IE 7]><link rel="stylesheet" type="text/css" href="'.base_url().'assets/960.gs/ie.css" media="screen" /><![endif]-->');
?>
<?php if( $info->container==TRUE){ ?>

<div class="container_<?=$info->size?> <?=$info->container_class?>" style="<?=$info->container_style?>" >
	<div class="grid_<?=$info->size?>" id="site_info">
		<div class="box">
			<p><?=$info->text?></p>
		</div>
	</div>
	<div class="clear"></div>
</div>

<?php } else { ?>

<div class="grid_<?=$info->size?>" id="site_info">
	<div class="box">
		<p><?=$info->text?></p>
	</div>
</div>
<div class="clear"></div>

<?php } ?>
<?php } ?>
