<?php if( $mode=='config' ){
//the plugin requirements as a JSON object is here ?>
{
	"title":{"type":"textbox"},
	"sub_title":{"type":"textbox"},
	"meta":{"type":"textbox"},
	"image":{"type":"file"},
	"text":{"type":"smalleditor"},
	"url enabling":"you can make a URL for more text about the article down there",
	"enable_url":{"type":"checkbox"},
	"section":{"type":"section"},
	"extra_url_paramters":{"type":"textbox"},
	"more":{"type":"textbox","label":"More text"},
	"first_of_array":{"type":"checkbox"}
}


<?php }else if( $mode=='layout' ){ 
//replace 0 with number of cells your plugin has ?>
<?php 
	if( $info->enable_cell ) echo "1";
	else echo "0";
?>


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
//add( 'assets/960.gs/jquery-fluid16.js' );
add( '<!--[if IE 6]><link rel="stylesheet" type="text/css" href="'.base_url().'assets/960.gs/ie6.css" media="screen" /><![endif]-->');
add('<!--[if IE 7]><link rel="stylesheet" type="text/css" href="'.base_url().'assets/960.gs/ie.css" media="screen" /><![endif]-->');


if($info->enable_url)
	$u = site_url($info->section.'/'.$info->extra_url_paramters );
else
	$u='#';
?>
<div class="<?php if($info->first_of_array) echo 'first'; ?> article">
	<h3>
		<a href="<?=$u?>"><?=$info->title?></a>
	</h3>

	<h4><?=$info->sub_title?></h4>
	<?php if($info->meta!=''){ ?>
		<p class="meta"><?=$info->meta?></p>
	<?php } ?>
	<?php if($info->image!=''){ ?>
		<a href="<?=$u?>" class="image">
			<img src="<?=base_url().$info->image?>" alt="<?=$info->title?>" />
		</a>
	<?php } ?>
	<p>
		<?=$info->text?>
		<?php if($info->enable_url){ ?>
			<a href="<?=$u?>">
				<?=$info->more?>
			</a>
		<?php } ?>
	</p>
</div>
<?php } ?>
