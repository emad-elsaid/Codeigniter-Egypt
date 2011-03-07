<?php if( $mode=='config' ): ?>
cellsno:
	type:number
	default:1
style:
	type:textarea
<?php elseif( $mode=='layout' ): ?>
<?= $cellsno ?>
<?php elseif( $mode=='view' ): ?>

<?php
$ci =& get_instance();
$ci->load->library( 'gui' );
echo $ci->gui->hbox( $cell, '', $style );
?>
<?php endif; ?>
