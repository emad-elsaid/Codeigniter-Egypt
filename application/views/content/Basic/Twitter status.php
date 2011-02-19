<?php if( $mode=='config' ){
//the plugin requirements as a JSON object is here ?>
{
	"user":{"type":"textbox","label":"User name"}
}


<?php }else if( $mode=='layout' ){ 
//replace 0 with number of cells your plugin has ?>
0


<?php }else if( $mode=='view' ){
//the real content of your plugin goes here ?>
<?php
$twitter_url = "http://twitter.com/statuses/user_timeline/{$info->user}.xml?count=1";
$buffer = file_get_contents($twitter_url);
$xml = new SimpleXMLElement($buffer);
echo $xml-> status -> text;
?>
<?php } ?>
