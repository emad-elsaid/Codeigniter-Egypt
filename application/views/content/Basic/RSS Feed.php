<?php if( $mode=='config' ){
//the plugin requirements as a JSON object is here ?>
{
	"feed_url" : { "type":"textbox", "label":"RSS URL" }
}


<?php }else if( $mode=='layout' ){ 
//replace 0 with number of cells your plugin has ?>
0


<?php }else if( $mode=='view' ){
//the real content of your plugin goes here ?>
<?php
if( isset($info->feed_url) and $info->feed_url!='' )
{
	$content = file_get_contents($info->feed_url);
	$x = new SimpleXmlElement($content);
	
	foreach($x->channel->item as $entry)
	{
		echo "<a href='$entry->link' title='$entry->title'>{$entry->title}</a>\n";
	}
	
}
else
{
	$ci->load->library( 'gui' );
	echo $ci->gui->error( 'feed URL field is empty, please write a valid RSS feed URL' );
}
?>
<?php } ?>
