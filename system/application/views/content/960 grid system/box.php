<?php if( $mode=='config' ){
//the plugin requirements as a JSON object is here ?>
{
	"title":{"type":"textbox"},
	"enable_text":{"type":"checkbox"},
	"text":{"type":"smalleditor"},
	"enable_cell":{"type":"checkbox"},
	"toggler":{"type":"checkbox"}
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
add( 'assets/960.gs/jquery-fluid16.js' );
add( '<!--[if IE 6]><link rel="stylesheet" type="text/css" href="'.base_url().'assets/960.gs/ie6.css" media="screen" /><![endif]-->');
add('<!--[if IE 7]><link rel="stylesheet" type="text/css" href="'.base_url().'assets/960.gs/ie.css" media="screen" /><![endif]-->');

$class = ( $info->toggler )? 'box toggle' : 'box';
?>

<div class="<?=$class?>">
	<h2><?= $info->title ?></h2>
	<div class="block" >
		<?php if( $info->enable_text ) echo '<p>'.$info->text.'</p>'; ?>
		<?php if( $info->enable_cell ) echo $cell[0]; ?>
	</div>
</div>

<?php } ?>
