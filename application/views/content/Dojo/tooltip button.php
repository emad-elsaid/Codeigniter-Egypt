<?php if( $mode=='config' ): ?>
title:
	type:textbox
<?php elseif( $mode=='layout' ): ?>
1
<?php elseif( $mode=='view' ): ?>

<?php
$ci->load->library( 'gui' );
echo $ci->gui->tooltipbutton( $title, $cell[0] );
?>
<?php endif; ?>
