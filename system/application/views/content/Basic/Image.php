<?php if( $mode=='config' ){ ?>
{
	"image":{"type":"file"},
	"style":{"type":"textarea"}
}
<?php } ?>
<?php if( $mode=='view' ){ ?>
<img src="<?= base_url().$info->image; ?>" style="<?= $info->style; ?>" ?>
<?php } ?>
