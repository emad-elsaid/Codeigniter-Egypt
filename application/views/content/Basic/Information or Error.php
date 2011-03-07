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
$f = $type;
echo $ci->gui->$f( $text );
?>
<?php endif; ?>
