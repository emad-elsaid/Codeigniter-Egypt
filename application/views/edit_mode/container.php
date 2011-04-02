<?php
$ci =& get_instance();

theme_add( 'assets/admin/edit/edit.css' );

$p = new Content($parent);
if( strlen($title)==0 )
$title = ( strrpos($path,'/')!=NULL)?
		substr( $path, strrpos($path,'/')+1,strrpos($path,'.')-strrpos($path,'/')-1 ):
		substr( $path, 0,strrpos($path,'.') );
		
$url = site_url("editor/data/$id/{$ci->system->section->id}");
?>
<div class="editCtrl systemCtrl"  >
	<a class="iframe " href="<?=$url?>" title="<?=$title?>" ></a>
</div>
<?= $text ?>
