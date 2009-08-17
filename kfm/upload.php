<?php
/**
 * KFM - Kae's File Manager
 *
 * upload.php - uploads a file and adds it to the db
 *
 * @category None
 * @package  None
 * @author   Kae Verens <kae@verens.com>
 * @author   Benjamin ter Kuile <bterkuile@gmail.com>
 * @license  docs/license.txt for licensing
 * @link     http://kfm.verens.com/
 */
require_once 'initialise.php';
$errors = array();
if ($kfm_allow_file_upload) {
    $file     = isset($_FILES['kfm_file'])?$_FILES['kfm_file']:$_FILES['Filedata'];
    $filename = $file['name'];
    $tmpname  = $file['tmp_name'];
    $cwd      = $kfm_session->get('cwd_id');
    if(!$cwd) $errors[] = kfm_lang('CWD not set');
    else {
        $toDir = kfmDirectory::getInstance($cwd);
        $to = $toDir->path.'/'.$filename;
        if (!is_file($tmpname)) $errors[] = 'No file uploaded';
        else if (!kfmFile::checkName($filename)) {
            $errors[] = 'The filename: '.$filename.' is not allowed';
        }
    }
		if ($cwd==1 && !$kfm_allow_files_in_root) $errors[] = 'Cannot upload files to the root directory';
    if (file_exists($to)) $errors[] = 'File already exists'; // TODO new string
    if (!count($errors)) {
        move_uploaded_file($tmpname, $to);
        if (!file_exists($to)) $errors[] = kfm_lang('failedToSaveTmpFile', $tmpname, $to);
        else if ($kfm_only_allow_image_upload && !getimagesize($to)) {
            $errors[] = 'only images may be uploaded';
            unlink($to);
        } else {
            chmod($to, octdec('0'.$kfm_default_upload_permission));
            $fid  = kfmFile::addToDb($filename, $kfm_session->get('cwd_id'));
            $file = kfmFile::getInstance($fid);
            if (function_exists('exif_imagetype')) {
                $imgtype = @exif_imagetype($to);
                if ($imgtype) {
                    $file    = kfmImage::getInstance($file);
                    $comment = '';
                    if ($imgtype==1) { // gif
                        $fc    = file_get_contents($to);
                        $arr   = explode('!', $fc);
                        $found = 0;
                        for ($i = 0;$i<count($arr)&&!$found;++$i) {
                            $block = $arr[$i];
                            if (substr($block, 0, 2)==chr(254).chr(21)) {
                                $found   = 1;
                                $comment = substr($block, 2, strpos($block, 0)-1);
                            }
                        }
                    } else {
                        $data = @exif_read_data($to, 0, true);
                        if (is_array($data)&&isset($data['COMMENT'])&&is_array($data['COMMENT'])) $comment = join("\n", $data['COMMENT']);
                    }
                    $file->setCaption($comment);
                } else if (isset($_POST['kfm_unzipWhenUploaded'])&&$_POST['kfm_unzipWhenUploaded']) {
                    kfm_extractZippedFile($fid);
                    $file->delete();
                }
            }
        }
    }
} else $errors[] = kfm_lang('permissionDeniedUpload');
if (isset($_REQUEST['swf']) && $_REQUEST['swf']==1) {
    if(count($errors))echo join("\n", $errors);
    else echo 'OK';
    exit;
}
?>
<html>
    <head>
        <script type="text/javascript">
<?php
$js = isset($_REQUEST['js'])?$js:'';
if (isset($_REQUEST['onload'])) echo $_REQUEST['onload'];
else if (isset($_REQUEST['onupload'])) echo $_REQUEST['onupload'];
else if (count($errors)) echo 'alert("'.addslashes(join("\n", $errors)).'");';
else echo 'parent.kfm_vars.startup_selectedFiles=['.$fid.'];parent.x_kfm_loadFiles('.$kfm_session->get('cwd_id').',parent.kfm_refreshFiles);parent.kfm_dir_openNode('.$kfm_session->get('cwd_id').');'.$js;
?>
        </script>
    </head>
    <body>
    </body>
</html>
