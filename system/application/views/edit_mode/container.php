<?php
$ci =& get_instance();

add( 'assets/admin/edit/edit.css' );

$p = new Content();
$p->get_by_id($parent);
$img_url = base_url().'assets/admin/edit/';
if( $can_edit OR $p->can_addin() OR $can_delete ){
?>

<div class="editCtrl vunsyCtrl"  >
	<a class="iframe " href="<?= site_url("admin/app/content Inserter/edit/$id/{$ci->vunsy->section->id}") ?>" title="<?=$path?>" >
		<img src="<?=$img_url?>edit show.png" title="<?=substr( $path, strrpos($path,'/')+1,strrpos($path,'.')-strrpos($path,'/')-1 ) ?>" >
	</a>
</div>
<?php } ?>
<?= $text ?>
