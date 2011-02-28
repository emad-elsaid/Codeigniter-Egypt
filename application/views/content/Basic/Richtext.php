<?php if( $mode=='config' ): ?>
Text:
	type:editor
<?php elseif( $mode=='view' ): ?>
<?= $info->Text ?>
<?php endif; ?>
