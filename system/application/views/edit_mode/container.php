<?php
$ci =& get_instance();

add( 'assets/edit/edit.css' );
add( 'jquery/theme/ui.core.css' );
add( 'jquery/jquery.js' );

$script = <<<EOT
<script language="javascript" >
$(function(){
	$('.editCtrl > .editCtrlToggler' ).click(function(){
		$(this).fadeOut().next('.editGroup').fadeIn();
	});
});
</script>
EOT;
add( $script );

$p = new Content();
$p->get_by_id($parent);
$img_url = base_url().'assets/edit/';
if( $can_edit OR $p->can_addin() OR $can_delete ){
?>

<div class="editCtrl vunsyCtrl"  >
<img class="editCtrlToggler" src="<?=$img_url?>edit show.png" >

<span class="editGroup ui-helper-hidden" >
<?php if( $can_edit ){ ?>
	<a class="iframe " href="<?= site_url("admin/app/content Inserter/data/$id") ?>" title="<?=$path?>" >
	<img src="<?=$img_url?>edit.png" title="Edit <?= $id ?>" >
	</a>
	<a class="iframe " href="<?= site_url("admin/app/content Inserter/up/$id") ?>" title="Move up" >
	<img src="<?=$img_url?>move up.png"  title="Move up"  >
	</a>
	<a class="iframe " href="<?= site_url("admin/app/content Inserter/down/$id") ?>" title="Move down" >
	<img src="<?=$img_url?>move down.png" title="Move down" >
	</a>
<?php } ?>

<?php if( $p->can_addin() ){ ?>
	<a class="iframe" href="<?= site_url("admin/app/content Inserter/index/{$ci->vunsy->section->id}/$parent/$cell/$sort" ) ?>" >
	<img src="<?=$img_url?>add before.png" title="Add before" >
	</a>
	
	<a class="iframe" href="<?= site_url("admin/app/content Inserter/index/{$ci->vunsy->section->id}/$parent/$cell/".($sort+1)) ?>" >
	<img src="<?=$img_url?>add after.png" title="Add after" >
	</a>
<?php } ?>

<?php if( $can_delete ){ ?>
	<a class="iframe" href="<?= site_url("admin/app/content Inserter/deleteConfirm/$id") ?>" >
	<img src="<?=$img_url?>delete.png" title="Delete" >
	</a>
<?php } ?>

</span>
</div>
<?php } ?>
<?= $text ?>
