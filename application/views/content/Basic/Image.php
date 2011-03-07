<?php if( $mode=='config' ): ?>
image:
	type:file
style:
	type:textarea
<?php elseif( $mode=='view' ): ?>
<img src="<?= base_url().$image; ?>" style="<?= $style; ?>" >
<?php endif; ?>
