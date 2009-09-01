<?php
$ci =& get_instance();

add( 'assets/edit/edit.css' );
$p = new Content();
$p->get_by_id($parent);
$img_url = base_url().'assets/edit/';
if( $can_edit OR $p->can_addin() OR $can_delete ){
?>

<div style="display:block" >
<?php if( $can_edit ){ ?>
	<a class="iframe vunsyCtrl" href="<?= site_url("admin/app/content Inserter/data/$id") ?>" title="<?=$path?>" >
	<img src="<?=$img_url?>edit.png" title="Edit <?= $id ?>" >
	</a>
<?php } ?>

<?php if( $p->can_addin() ){ ?>
	<a class="iframe vunsyCtrl" href="<?= site_url("admin/app/content Inserter/index/{$ci->vunsy->section->id}/$parent/$cell/$sort" ) ?>" >
	<img src="<?=$img_url?>add before.png" title="Add before" >
	</a>
	
	<a class="iframe vunsyCtrl" href="<?= site_url("admin/app/content Inserter/index/{$ci->vunsy->section->id}/$parent/$cell/".($sort+1)) ?>" >
	<img src="<?=$img_url?>add after.png" title="Add after" >
	</a>
<?php } ?>

<?php if( $can_delete ){ ?>
	<a class="iframe vunsyCtrl" href="<?= site_url("admin/app/content Inserter/deleteConfirm/$id") ?>" >
	<img src="<?=$img_url?>delete.png" title="Delete" >
	</a>
<?php } ?>

</div>
<?php } ?>
<?= $text ?>
