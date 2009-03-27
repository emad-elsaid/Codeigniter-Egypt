<?php
/**
 * KFM - Kae's File Manager
 *
 * get.php - retrieves a specified file
 * can also resize an image on demand
 *
 * @category None
 * @package  None
 * @author   Kae Verens <kae@verens.com>
 * @author   Benjamin ter Kuile <bterkuile@gmail.com>
 * @license  docs/license.txt for licensing
 * @link     http://kfm.verens.com/
 */
require_once 'initialise.php';
if (isset($_SERVER['REDIRECT_QUERY_STRING'])&&$_SERVER['REDIRECT_QUERY_STRING']) {
    $arr = explode(',', $_SERVER['REDIRECT_QUERY_STRING']);
    foreach ($arr as $r) {
        $arr2           = explode('=', $r);
        $_GET[$arr2[0]] = $arr2[1];
    }
}
// { rebuild $_GET (in case it's been mangled by something)
$uri   = $_SERVER['REQUEST_URI'];
$uri2  = explode('?', $uri);
$parts = explode('&', $uri2[1]);
foreach ($parts as $part) {
    $arr = explode('=', $part);
    if (!(count($arr)>1)) continue;
    list($varname, $varval) = $arr;
    $_GET[$varname]         = urldecode($varval);
}
// }
$id = $_GET['id'];
if (!is_numeric($id)) {
    echo kfm_lang('errorInvalidID');
    exit;
}
$extension = 'unknown';
if (isset($_GET['type'])&&$_GET['type']=='thumb') {
    $path = WORKPATH.'thumbs/'.$id;
    $name = $id;
} else {
    if (isset($_GET['width'])&&isset($_GET['height'])) {
        $width  = $_GET['width'];
        $height = $_GET['height'];
        $image  = kfmImage::getInstance($id);
        if (!$image) {
            echo kfm_lang('errorFileIDNotFound', $id);
            exit;
        }
				if($width>$image->width)$width=$image->width;
				if($height>$image->height)$height=$image->height;
        $image->setThumbnail($width, $height);
        $name      = $image->thumb_id;
        $path      = $image->thumb_path;
        $extension = $image->getExtension();
    } else {
        $file = kfmFile::getInstance($id);
        if (!$file) {
            echo kfm_lang('errorFileIDNotFound', $id);
            exit;
        }
        $path      = $file->path;
        $name      = $file->name;
        $extension = $file->getExtension();
    }
}
// { headers
if (strstr($_SERVER['HTTP_USER_AGENT'], 'MSIE')) $name = preg_replace('/\./', '%2e', $name, substr_count($name, '.')-1);
@set_time_limit(0);
header('Cache-Control: max-age = 2592000');
header('Expires-Active: On');
header('Expires: Fri, 1 Jan 2500 01:01:01 GMT');
header('Pragma:');
header('Content-Length: '.(string)(filesize($path)));
if (isset($_GET['forcedownload'])) {
    header('Content-Type: force/download');
    header('Content-Disposition: attachment; filename="'.$name.'"');
} else header('Content-Type: '.get_mimetype($extension));
header('Content-Transfer-Encoding: binary');
// }
if ($file = fopen($path, 'rb')) { // send file
    while ((!feof($file))&&(connection_status()==0)) {
        print(fread($file, 1024*8));
        flush();
    }
    fclose($file);
}
return((connection_status()==0) and !connection_aborted());
?>
