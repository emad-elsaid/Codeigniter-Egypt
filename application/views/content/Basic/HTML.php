<?php if( $mode=='config' ){ ?>
{
	"add":{"type":"checkbox","label":"add html to header"},
	"Text":{"type":"textarea"}
}
<?php } ?>
<?php if( $mode=='view' ){ ?>
<?php
if( $info->add==TRUE )
	add($info->Text);
else
	echo $info->Text;
?>
<?php } ?>
