<?php if($mode=='config'){ ?>
{
	"text":{"type":"textbox"},
	"title":{"type":"textbox"}
}
<?php } ?>

<?php if($mode=='layout'){ ?>
1
<?php } ?>

<?php if($mode=='view'){ ?>
<?php
$ci =& get_instance();
$ci->load->library( 'gui' );
echo $ci->gui->tooltipbutton( $info->text, $info->title, $cell[0] );
?>
<?php } ?>
