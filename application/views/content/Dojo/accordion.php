<?php if( $mode=='config' ): ?>
titles:
	type:textarea
style:
	type:textarea
<?php elseif( $mode=='layout' ): ?>
<?= count( explode( "\n", $titles ) ); ?>
<?php elseif( $mode=='view' ): ?>

<?php
$ci =& get_instance();
$ci->load->library( 'gui' );

//assign every key to it's value
$titles = explode( "\n", $titles );
$content = array();

$i=0;
foreach( $titles as $item )
	$content[ $item ] = $cell[$i++];

// printing the accordion
echo $ci->gui->accordion( $content, '', $style );
?>
<?php endif; ?>
