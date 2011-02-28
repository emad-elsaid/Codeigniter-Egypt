<?php if( $mode=='config' ): ?>
titles:
	type:textarea
height:
	type:textbox 
	default:300px
width:
	type:textbox 
	default:100%
<?php elseif( $mode=='layout' ): ?>
<?= count( explode( "\n", $info->titles ) ); ?>
<?php elseif( $mode=='view' ): ?>

	
	<?php
	$ci->load->library( 'gui' );
	
	//assign every key to it's value
	$info->titles = explode( "\n", $info->titles );
	echo $ci->gui->tab(array_combine( $info->titles, $cell),array(),
			array('width'=>$info->width,'height'=>$info->height)
			);
	?>
<?php endif; ?>
