<?php
require_once('../../initialise.php');
if(!isset($_GET['id'])) die ('No id given.');
/*
$f=kfmFile::getInstance($_GET['id']);
if(!$f)die('The document with id '.$_GET['id'].' cannot be found');
$url=$f->getUrl();
$ext=$f->getExtension();
*/
$url='playlist.php?ids='.$_GET['id'];
print $kfm->doctype;
?>
<html>
<head>
<title>JW FLV MEDIA PLAYER</title>
<script type="text/javascript" src="swfobject.js"></script>
</head>
<body>
<div id="media_container"><div id="flashreplace"></div></div>
<script type="text/javascript">
var so = new SWFObject('mediaplayer.swf','player','720','260','8');
so.addParam("allowfullscreen","true");
so.addParam("allowscriptaccess","always");
so.addParam("wmode","opaque");
so.addParam("bgcolor","#000000");
so.addVariable('file','<?php echo $url;?>');
so.addVariable('linkfromdisplay','true');
//so.addVariable('callback','urchin');
so.addVariable('displaywidth','360');
so.addVariable('autoscroll','true');
so.addVariable('lightcolor','0x0099CC');
so.addVariable("width","720");
so.addVariable("height","260");
so.addVariable("autostart",true);
so.write('flashreplace');
</script>
</body>
</html>
