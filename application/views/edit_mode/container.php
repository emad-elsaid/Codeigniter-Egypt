<?php
$ci =& get_instance();

add( 'assets/admin/edit/edit.css' );

$p = new Content();
$p->get_by_id($parent);
$title = ( strrpos($path,'/')!=NULL)?
		substr( $path, strrpos($path,'/')+1,strrpos($path,'.')-strrpos($path,'/')-1 ):
		substr( $path, 0,strrpos($path,'.') );
$url = site_url("admin/app/content Inserter/edit/$id/{$ci->vunsy->section->id}");
if( $can_edit OR $p->can_addin() OR $can_delete ){
?>

<div class="editCtrl vunsyCtrl"  >
	<a class="iframe " href="<?=$url?>" title="<?=$title?>" ></a>
</div>
<?php } ?>
<?= $text ?>
