<?php if( $mode=='config' ){
//the plugin requirements as a JSON object is here ?>
{
	"feed_url" : { "type":"textbox", "label":"RSS URL" },
	"ul list" : "if you checked that the links will be displayed in an unordered list as a container",
	"ul" : { "type":"checkbox", "label":"use unordered list" }
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
	
	if( $info->ul )
	{
		$ul 		= '<ul>';
		$ul_end 	= '</ul>';
		$li		= '<li>';
		$li_end	= '</li>';
	}
	else
	{
		$ul 		= '';
		$ul_end 	= '';
		$li		= '';
		$li_end	= '';
	}
	
	echo $ul;
	
	foreach($x->channel->item as $entry)
	{
		echo "$li<a href='$entry->link' title='$entry->title'>{$entry->title}</a>$li_end\n";
	}
	
	echo $ul_end;
}
else
{
	$ci->load->library( 'gui' );
	echo $ci->gui->error( 'feed URL field is empty, please write a valid RSS feed URL' );
}
?>
<?php } ?>
