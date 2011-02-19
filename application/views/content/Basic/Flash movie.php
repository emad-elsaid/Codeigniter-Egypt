<?php if( $mode=='config' ){
//the plugin requirements as a JSON object is here ?>
{
	"flash":{"type":"file","label":"flash file"},
	"width":{"type":"number"},
	"height":{"type":"number"},
	"bg":{"type":"color","label":"background color" },
	"quality":{"type":"dropdown","options":{
							"best":"best",
							"high":"high",
							"medium":"medium",
							"low":"low"
							}},
	"align":{"type":"dropdown","options":{
							"left":"left",
							"right":"right",
							"top":"top",
							"bottom":"bottom"
							}},
	"loop":{"type":"checkbox", "default":true},
	"transparent":{"type":"checkbox"}
}


<?php }else if( $mode=='layout' ){ 
//replace 0 with number of cells your plugin has ?>
0


<?php }else if( $mode=='view' ){
//the real content of your plugin goes here ?>
<OBJECT classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=5,0,0,0"
 width="<?=$info->width?>"
 height="<?=$info->height?>"
 align="<?=$info->align?>" >
<param name=movie value="<?=base_url().$info->flash?>">
<param name=quality value=<?=$info->quality?>> 
<?php if($info->transparent ): ?>
<param name=wmode value=transparent> 
<?php endif; ?>
<param name=bgcolor value=<?=$info->bg?>>
<param name="loop" value="<?= ($info->loop)?'true':'false' ?>">
<EMBED 
		src="<?=base_url().$info->flash?>" 
		quality="<?=$info->quality?>"
		bgcolor="<?=$info->bg?>"
		width="<?=$info->width?>"
		height="<?=$info->height?>"
		loop="<?= ($info->loop)?'true':'false' ?>"
		type="application/x-shockwave-flash"
		align="<?=$info->align?>"
		<?php if($info->transparent){?>wmode="transparent" <?php } ?>
		
		pluginspage="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash"
 >
</EMBED>
</OBJECT> 
<?php } ?>
