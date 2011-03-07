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
<?= count( explode( "\n", $titles ) ); ?>
<?php elseif( $mode=='view' ): ?>


	<?php
	$ci->load->library( 'gui' );
	
	//assign every key to it's value
	$titles = explode( "\n", $titles );
	echo $ci->gui->tab(array_combine( $titles, $cell),array(),
			array('width'=>$width,'height'=>$height)
			);
	?>
<?php endif; ?>
