<?php if($mode=='config'){ ?>
{
	"titles":{"type":"textarea"},
	"style":{"type":"textarea"}
}
<?php } ?>

<?php if($mode=='layout'){ ?>
<?= count( explode( "\n", $info->titles ) ); ?>
<?php } ?>

<?php if($mode=='view'){ ?>
<?php
$ci =& get_instance();
$ci->load->library( 'gui' );

//assign every key to it's value
$info->titles = explode( "\n", $info->titles );
$content = array();

$i=0;
foreach( $info->titles as $item )
{
	$content[ $item ] = $cell[$i++];
	
}

// printing the accordion
echo $ci->gui->accordion( $content, '', $info->style );
?>
<?php } ?>
