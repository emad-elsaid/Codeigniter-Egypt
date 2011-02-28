<?php if( $mode=='config' ): ?>

feed_url :  
	type:textbox 
	label:RSS URL 

<?php elseif( $mode=='layout' ): ?>
0


<?php elseif( $mode=='view' ): ?>
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
<?php endif; ?>
