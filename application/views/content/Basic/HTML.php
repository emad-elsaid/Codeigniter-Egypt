<?php if( $mode=='config' ): ?>
add:
	type:checkbox
	label:add html to header
Text:
	type:textarea
<?php elseif( $mode=='view' ): ?>
<?php
if( $add==TRUE )
	theme_add($Text);
else
	echo $Text;
?>
<?php endif; ?>
