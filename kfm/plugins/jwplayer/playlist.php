<?php
require_once('../../initialise.php');
if(!isset($_GET['ids']))die('error: no ids get parameter defined');
$ids=explode(',',$_GET['ids']);
header("content-type:text/xml;charset=utf-8");
echo "<playlist version='1' xmlns='http://xspf.org/ns/0/'>\n";
echo "<trackList>\n";
foreach($ids as $id){
	$f=kfmFile::getInstance($id);
	if(!$f) continue;
	echo "\t<track>\n";
	echo "\t\t<title>".$f->name."</title>\n";
	echo "\t\t<location>".$f->getUrl()."#.".$f->getExtension()."</location>\n";
	echo "\t</track>\n";
}
echo "</trackList>\n";
echo "</playlist>\n";
