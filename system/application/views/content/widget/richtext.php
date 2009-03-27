<?php if( $mode=='config' ) { ?>
{
	"info":{
		"text":{
			"type":"editor"
			,"default":"default text"
		}
		,"title":{
			"type":"textbox"
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
