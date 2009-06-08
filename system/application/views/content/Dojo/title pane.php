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
$ci =& get_instance();
$ci->load->library( 'gui' );
echo $ci->gui->titlepane( $info->title, $cell[0]);
?>
<?php } ?>
