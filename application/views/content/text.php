<?php if( $mode=='config' ):
//the plugin requirements as a YAML object is here ?>
text:
	type: editor


<?php elseif( $mode=='layout' ): 
//replace 0 with number of cells your plugin has ?>
0


<?php elseif( $mode=='view' ):
//the real content of your plugin goes here ?>

<?=$text?>

<?php endif; ?>
