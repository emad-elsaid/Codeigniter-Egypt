<?php if($mode=='config'){ ?>
{
	"title":{"type":"textbox"}
}
<?php } ?>

<?php if($mode=='layout'){ ?>
1
<?php } ?>

<?php if($mode=='view'){ ?>
<?php
$ci->load->library( 'gui' );
echo $ci->gui->tooltipbutton( $info->title, $cell[0] );
?>
<?php } ?>
