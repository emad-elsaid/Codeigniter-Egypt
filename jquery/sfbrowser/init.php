<?php // this file needs to be called in the header of your document because it adds css and js

include("config.php");
include("functions.php");
include("lang/".SFB_LANG.".php");

// create language constants
foreach ($aLang as $c=>$s) @define($c,$s);

// check language files (lang_x.js needs to be writable)
$sPhpLang = SFB_rPATH."lang/".SFB_LANG.".php";
$sJsLang = SFB_rPATH."lang/".SFB_LANG.".js";
if (!file_exists($sJsLang)||filemtime($sJsLang)<filemtime($sPhpLang)) {
	$oJsHdl = @fopen($sJsLang, 'w');// or echo("can't open lang file");
	$sJs = "// You don't need to edit this file. It is generated from the similarly named php file. Do make sure this file can be written to.\n";
	foreach ($aLang as $c=>$s) $sJs .= "var s".camelCase($c)."=\"".str_replace("\"","\\\"",$s)."\";";
	fwrite($oJsHdl, $sJs);
	fclose($oJsHdl);
}

// add javascript to header
constantsToJs(array ("PREVIEW_BYTES","SFB_DENY"));//"BASE_URI",
echo "\t\t<link rel=\"stylesheet\" type=\"text/css\" media=\"screen\" href=\"".SFB_PATH."css/sfbrowser.css\" />\n";
echo "\t\t<script type=\"text/javascript\" src=\"".SFB_PATH."lang/".SFB_LANG.".js\"></script>\n";
echo "\t\t<script type=\"text/javascript\" src=\"".SFB_PATH."array.js\"></script>\n";
echo "\t\t<script type=\"text/javascript\" src=\"".SFB_PATH."jquery.tinysort.min.js\"></script>\n";
echo "\t\t<script type=\"text/javascript\" src=\"".SFB_PATH."jquery.sfbrowser.min.js\"></script>\n";
echo "\t\t<script type=\"text/javascript\">$.sfbrowser.defaults.sfbpath = \"".SFB_PATH."\";$.sfbrowser.defaults.base = \"".SFB_BASE."\";</script>\n";

// check existing icons
$aIcons = array();
if ($handle = opendir(SFB_rPATH."icons/")) while (false !== ($file = readdir($handle))) if (filetype(SFB_rPATH."icons/".$file)=="file") $aIcons[] = array_shift(explode(".",$file));
echo "\t\t<script type=\"text/javascript\">var aIcons = ['".implode("','",$aIcons)."'];</script>\n";
?>
