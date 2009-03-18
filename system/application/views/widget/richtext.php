<?php if( $mode=='config' ) { ?>
{
	"info":{
		"text":{
			"type":"editor"
			,"default":"default text"
			,"tooltip":"the 2nd tooltip"
		}
		,"title":{
			"type":"textbox"
			,"tooltip":"the text for tooltip text"
		}
		,"titlecolor":{
			"type":"color"
		}
	}
}
<? }else{
	
echo $info->text;

}
?>
