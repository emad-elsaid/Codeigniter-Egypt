<?php if( $mode=='config' ){
//the plugin requirements as a JSON object is here ?>
{
	"title":{"type":"textbox"},
	"toggler":{"type":"checkbox"},
	"parent":{"type":"section"}
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
add( 'jquery/jquery-ui.js' );
add( 'assets/960.gs/jquery-fluid16.js' );
add( '<!--[if IE 6]><link rel="stylesheet" type="text/css" href="'.base_url().'assets/960.gs/ie6.css" media="screen" /><![endif]-->');
add('<!--[if IE 7]><link rel="stylesheet" type="text/css" href="'.base_url().'assets/960.gs/ie.css" media="screen" /><![endif]-->');

$class = ( $info->toggler )? 'box menu toggle' : 'box menu';
?>

<div class="<?=$class?>">
	<h2  id="toggle-section-menu"><?= $info->title ?></h2>
	<div class="block"  id="section-menu">
		<ul class="section menu">
	
<?php
$sections = new Section();
$sections->order_by('sort');
$sections->get_by_parent_section( $info->parent );
foreach( $sections->all as $item ){
?>

		<li>
			<a href="<?=site_url($item->id)?>" class="menuitem" >
				<?=$item->name ?>
			</a>
			
			<?php
			$sub_sections = new Section();
			$sub_sections->order_by('sort');
			$sub_sections->get_by_parent_section( $item->id );
			?>
			<?php if( count($sub_sections->all)>0 ){ ?>
			<ul class="submenu" >
				<?php foreach( $sub_sections->all as $sub ){ ?>
					
					<li>
						<a href="<?=site_url($sub->id)?>" >
						<?=$sub->name ?>
						</a>
					</li>
					
				<?php } ?>
				</ul>
			<? } ?>
		</li>
		
<?php } ?>
		</ul>
	</div>
</div>

<?php } ?>
