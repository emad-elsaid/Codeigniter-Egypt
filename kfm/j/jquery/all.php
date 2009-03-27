<?php
$js=file_get_contents('jquery-1.2.2.pack.js');
$js.=file_get_contents('jquery.dimensions.pack.js');
$js.=file_get_contents('jquery.impromptu.js');
$js.=file_get_contents('jquery.iutil.pack.js');
$js.=file_get_contents('jquery.idrag.js');
$js.=file_get_contents('jquery.grid.columnSizing.js');
//$js.=file_get_contents('jquery.tablesorter.pack.js');
$js.=file_get_contents('jquery.tablesorter.js');
header ('Content-type: text/javascript');
header('Expires: '.gmdate("D, d M Y H:i:s", time() + 3600*24*365).' GMT');
echo $js;
