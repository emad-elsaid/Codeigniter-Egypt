<?php if( $mode=='config' ): ?>
cells:
	type:number
	default:1
style:
	type:textarea
<?php elseif( $mode=='layout' ): ?>
<?= $info->cells ?>
<?php elseif( $mode=='view' ): ?>

<?php
$ci =& get_instance();
$ci->load->library( 'gui' );
echo $ci->gui->hbox( $cell, '', $info->style );
?>
<?php endif; ?>
