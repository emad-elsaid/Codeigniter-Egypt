<?php if( $mode=='config' ): ?>
type:
	type:dropdown
	options:
		info:info
		error:error
text:
	type:smalleditor
<?php elseif( $mode=='layout' ): ?>
0

<?php elseif( $mode=='view' ): ?>

<?php
$ci->load->library( "gui" );
$f = $info->type;
echo $ci->gui->$f( $info->text );
?>
<?php endif; ?>
