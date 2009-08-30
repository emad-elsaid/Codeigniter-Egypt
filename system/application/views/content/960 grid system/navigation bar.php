<?php if( $mode=='config' ){
//the plugin requirements as a JSON object is here ?>
{
	"primary":{"type":"section"},
	"enabling":"you can make a secondary nab links like login or contact us below",
	"enable_secondary":{"type":"checkbox"},
	"secondary":{"type":"section"}
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
add( 'assets/960.gs/nav.css');
add( 'jquery/jquery.js' );
//add( 'jquery/jquery-ui.js' );
//add( 'assets/960.gs/jquery-fluid16.js' );
add( '<!--[if IE 6]><link rel="stylesheet" type="text/css" href="'.base_url().'assets/960.gs/ie6.css" media="screen" /><![endif]-->');
add('<!--[if IE 7]><link rel="stylesheet" type="text/css" href="'.base_url().'assets/960.gs/ie.css" media="screen" /><![endif]-->');
?>

<ul class="nav main">
<?php 
$p_sec = new Section();
$p_sec->get_by_parent_section( $info->primary );
foreach( $p_sec->all as $item ){
?>
	<li>
		<a href="<?=site_url( $item->id)?>"><?=$item->name ?></a>
		
		<?php 
		$sub_section = new Section();
		$sub_section->get_by_parent_section( $item->id );
		if( count($sub_section->all)>0 ){
		?>
		<ul>
			<?php foreach( $sub_section->all as $sub_item ){ ?>
			<li>
				<a href="<?= site_url( $sub_item->id) ?>"><?= $sub_item->name ?></a>
			</li>
			<?php } ?>
		</ul>
		<?php } ?>
	</li>
<?php } ?>
<?php if( $info->enable_secondary==TRUE ){ ?>

	<?php 
		$p_sec = new Section();
		$p_sec->get_by_parent_section( $info->secondary );
		foreach( $p_sec->all as $item ){
	?>
	
	<li class="secondary">
		<a href="<?=site_url( $item->id)?>" ><?=$item->name ?></a>
		
		<?php 
		$sub_section = new Section();
		$sub_section->get_by_parent_section( $item->id );
		if( count($sub_section->all)>0 ){
		?>
		<ul>
			<?php foreach( $sub_section->all as $sub_item ){ ?>
			<li>
				<a href="<?= site_url( $sub_item->id) ?>"><?= $item->name ?></a>
			</li>
			<?php } ?>
		</ul>
		<?php } ?>
		
	</li>
		<?php } ?>

<?php } ?>
</ul>

<?php } ?>
