<?php if( $mode=='config' ): ?>
add:
	type:checkbox
	label:add html to header
Text:
	type:textarea
<?php elseif( $mode=='view' ): ?>
<?php
if( $info->add==TRUE )
	theme_add($info->Text);
else
	echo $info->Text;
?>
<?php endif; ?>
