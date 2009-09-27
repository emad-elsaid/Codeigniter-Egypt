<?php if( $mode=='config' ){
//the plugin requirements as a JSON object is here ?>
{
	"tip" : "write the scripting language javascript,css,django and note that highlighter for php is javascript , don't blame me but blame dojo developers :( ",
	"language" : {"type":"textbox"},
	"code" : {"type":"textarea"}
}


<?php }else if( $mode=='layout' ){ 
//replace 0 with number of cells your plugin has ?>
0


<?php }else if( $mode=='view' ){
//the real content of your plugin goes here ?>
<?php
$ci->load->helper('form');
add('dojo/dojox/highlight/resources/highlight.css');
add('dojox.highlight');
add('dojox.highlight.languages.'.$info->language);
add(<<<EOT
<script language="javascript">
dojo.addOnLoad(function(){
  dojo.query("code").forEach(dojox.highlight.init);
});
</script>
EOT
);
?>
<pre><code>
<?=form_prep($info->code)?>
</code></pre>
<?php } ?>
