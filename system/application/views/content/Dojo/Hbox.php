<?php if($mode=='config'){ ?>
{
	"cells":{"type":"number","default":1},
	"style":{"type":"textarea"}
}
<?php } ?>

<?php if($mode=='layout'){ ?>
<?= $info->cells ?>
<?php } ?>

<?php if($mode=='view'){ ?>
<?php
$ci =& get_instance();
$ci->load->library( 'gui' );
echo $ci->gui->hbox( $cell, '', $info->style );
?>
<?php } ?>
