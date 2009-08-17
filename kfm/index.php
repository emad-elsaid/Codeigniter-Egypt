<?php
/**
 * KFM - Kae's File Manager - index page
 *
 * @category None
 * @package  None
 * @author   Kae Verens <kae@verens.com>
 * @author   Benjamin ter Kuile <bterkuile@gmail.com>
 * @license  docs/license.txt for licensing
 * @link     http://kfm.verens.com/
 */
// {{{ setup
error_reporting(E_ALL);
require_once 'initialise.php';
require_once KFM_BASE_PATH.'includes/kaejax.php';
$kfm_session->set('kfm_url',dirname((!empty($_SERVER['HTTPS'])) ? "https://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'] : "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']).DIRECTORY_SEPARATOR);
$kfm_root_dir = kfmDirectory::getInstance(1);
if ($kfm_user_root_folder) {
    $dirs   = explode(DIRECTORY_SEPARATOR, trim($kfm_user_root_folder, ' '.DIRECTORY_SEPARATOR));
    $subdir = $kfm_root_dir;
    foreach ($dirs as $dirname) {
        $subdir = $subdir->getSubdir($dirname);
        if(!$subdir) die ('Error: Root directory cannot be found in the database.');
        $kfm_root_folder_id = $subdir->id;
    }
    $user_root_dir = $subdir;
} else {
    $user_root_dir = $kfm_root_dir;
}
$kfm_root_folder_id = $user_root_dir->id;
if($kfm_root_folder_name=='foldername')$kfm_root_folder_name = $user_root_dir->name;
$kfm_startupfolder_id = $user_root_dir->id;
$startup_sequence     = '[]';
if ($kfm_startup_folder) {
    $dirs                   = explode(DIRECTORY_SEPARATOR, trim($kfm_startup_folder, ' '.DIRECTORY_SEPARATOR));
    $subdir                 = $user_root_dir;
    $startup_sequence_array = array();
    foreach ($dirs as $dirname) {
        $subdir = $subdir->getSubdir($dirname);
        if(!$subdir)break;
        $startup_sequence_array[] = $subdir->id;
        $kfm_startupfolder_id     = $subdir->id;
    }
    $kfm_session->set('cwd_id', $kfm_startupfolder_id);
    $startup_sequence = '['.implode(',', $startup_sequence_array).']';
}
else if (isset($_GET['fid']) && $_GET['fid']) {
	$f = kfmFile::getInstance($_GET['fid']);
	if($f){
		$_GET['cwd']               = $f->parent;
		$kfm_startup_selectedFiles = array($_GET['fid']);
	}
}
if (isset($_GET['cwd']) && (int)$_GET['cwd']) {
	$path   = kfm_getDirectoryParentsArr($_GET['cwd']);
	$path[] = $_GET['cwd'];
	if(count($path)>1){
		$startup_sequence_array = $path;
		$kfm_startupfolder_id   = $_GET['cwd'];
		$kfm_session->set('cwd_id', $kfm_startupfolder_id);
		$startup_sequence = '['.implode(',', $startup_sequence_array).']';
	}
}
// }}}
header('Content-type: text/html; Charset = utf-8');
// {{{ export kaejax stuff
kfm_kaejax_export('kfm_changeCaption', 'kfm_copyFiles', 'kfm_createDirectory',
    'kfm_createEmptyFile', 'kfm_deleteDirectory', 'kfm_downloadFileFromUrl', 
    'kfm_extractZippedFile', 'kfm_getFileDetails', 'kfm_getFileUrl', 'kfm_getFileUrls',
    'kfm_getTagName', 'kfm_getTextFile', 'kfm_getThumbnail', 'kfm_loadDirectories',
    'kfm_loadFiles', 'kfm_moveDirectory', 'kfm_moveFiles', 'kfm_renameDirectory',
    'kfm_renameFile', 'kfm_renameFiles', 'kfm_resizeImage', 'kfm_resizeImages', 'kfm_rm',
    'kfm_rotateImage', 'kfm_cropToOriginal', 'kfm_cropToNew', 'kfm_saveTextFile',
    'kfm_search', 'kfm_tagAdd', 'kfm_tagRemove', 'kfm_zip');
if(!empty($_POST['kaejax']))kfm_kaejax_handle_client_request();
// }}}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <style type="text/css">@import "themes/<?php echo $kfm_theme; ?>/css.php";</style>
        <title>KFM - Kae's File Manager</title>
<?php // {{{ get list of plugins and show their CSS files
$plugins = array();
$h       = opendir(KFM_BASE_PATH.'plugins');
while (false!==($plugin=readdir($h))) {
    if($plugin[0]=='.')continue;
    $plugins[] = $plugin;
    if(file_exists(KFM_BASE_PATH.'plugins/'.$plugin.'/plugin.css'))echo '
    <link rel="stylesheet" href="plugins/'.$plugin.'/plugin.css" />';
}	
// }}} ?>
    </head>
    <body>
        <div id="removeme">
            <p>Please Wait - loading...</p>
            <noscript>KFM relies on JavaScript. Please either turn on JavaScript in your browser, or <a href="http://www.getfirefox.com/">get Firefox</a> if your browser does not support JavaScript.</noscript>
        </div>
<?php
// {{{ if there's a template, show it here
$templated = 0;
if (file_exists('themes/'.$kfm_theme.'/template.html')) {
    echo '<div id="templateWrapper" style="display:none">'.file_get_contents('themes/'.$kfm_theme.'/template.html').'</div>';
    $templated = 1;
}
// }}}
// {{{ daily tasks
$today             = date('Y-m-d');
$last_registration = isset($kfm_parameters['last_registration'])?$kfm_parameters['last_registration']:'';
if ($last_registration!=$today) {
// {{{ database maintenance
    echo '<iframe style="display:none" src="maintenance.php"></iframe>';
// }}}
// {{{ once per day, tell the kfm website a few simple details about usage
    if (!$kfm_dont_send_metrics) {
        echo '<img src="http://kfm.verens.com/extras/register.php?version='.urlencode(KFM_VERSION).
            '&amp;domain_name='.urlencode($_SERVER['SERVER_NAME']).
            '&amp;db_type='.$kfm_db_type.
            '&amp;plugins='.join(',',$plugins).
        '" />';
    }
// }}}
    $kfmdb->query("delete from ".KFM_DB_PREFIX."parameters where name='last_registration'");
    $kfmdb->query("insert into ".KFM_DB_PREFIX."parameters (name,value) values ('last_registration','".$today."')");
    $kfm_parameters['last_registration'] = $today;
}
// }}}
// {{{ check for default directories
if($kfm_default_directories!=''){
	$dirs=explode(',',$kfm_default_directories);
	foreach($dirs as $dir){
		$dir=trim($dir);
		@mkdir($rootdir.$dir,0755);
	}
}
// }}}
?>
        <script type="text/javascript" src="j/mootools.v1.11/mootools.v1.11.js"></script>
        <script type="text/javascript" src="j/jquery/all.php"></script>
<?php // {{{ set up JavaScript environment variables ?>
        <script type="text/javascript">
            var $j = jQuery.noConflict();
            $j.tablesorter.addParser({ 
                id: 'kfmobject', 
                is: function(s) { 
                    return false; 
                }, 
                format: function(s) {
                    return $j(s).text().toLowerCase();
                }, 
                type: 'text' 
            }); 
            var kfm_vars={
                files:{
                    name_length_displayed:<?php echo $kfm_files_name_length_displayed; ?>,
                    name_length_in_list:<?php echo $kfm_files_name_length_in_list; ?>,
                    return_id_to_cms:<?php echo $kfm_return_file_id_to_cms?'true':'false'; ?>,
                    allow_multiple_returns:<?php echo $kfm_allow_multiple_file_returns?'true':'false'; ?>,
										drags_move_or_copy:<?php echo $kfm_drags_move_or_copy_files; ?>
                },
                get_params:"<?php echo GET_PARAMS; ?>",
                permissions:{
                    dir:{
                        ed:<?php echo $kfm_allow_directory_edit; ?>,
                        mk:<?php echo $kfm_allow_directory_create; ?>,
                        mv:<?php echo $kfm_allow_directory_move; ?>,
                        rm:<?php echo $kfm_allow_directory_delete; ?>
                    },
                    file:{
                        rm:<?php echo $kfm_allow_file_delete; ?>,
                        ed:<?php echo $kfm_allow_file_edit; ?>,
                        mk:<?php echo $kfm_allow_file_create; ?>,
                        mv:<?php echo $kfm_allow_file_move; ?>
                    },
                    image:{
                        manip:<?php echo $kfm_allow_image_manipulation; ?>
                    }
                },
                root_folder_name:"<?php echo $kfm_root_folder_name; ?>",
                root_folder_id:<?php echo $kfm_root_folder_id; ?>,
                startupfolder_id:<?php echo $kfm_startupfolder_id; ?>,
                startup_sequence:<?php echo $startup_sequence; ?>,
								startup_selectedFiles:[<?php echo join(',',$kfm_startup_selectedFiles); ?>],
                show_disabled_contextmenu_links:<?php echo $kfm_show_disabled_contextmenu_links; ?>,
                use_multiple_file_upload:<?php echo $kfm_use_multiple_file_upload; ?>,
                use_templates:<?php echo $templated; ?>,
                version:'<?php echo KFM_VERSION; ?>'
            };
            var kfm_widgets=[];
            function kfm_addWidget(obj){
                kfm_widgets.push(obj);
            }
        </script>
<?php // }}} ?>
        <script type="text/javascript" src="j/all.php"></script>
        <script type="text/javascript" src="j/hooks.js"></script>
        <script type="text/javascript" src="lang/<?php echo $kfm_language; ?>.js"></script>
<?php // {{{ widgets and plugins
// {{{ include widgets if they exist
$h = opendir(KFM_BASE_PATH.'widgets');
while (false!==($dir = readdir($h))) {
    if ($dir[0]!='.'&&is_dir(KFM_BASE_PATH.'widgets/'.$dir)) {
        echo '		<script type="text/javascript" src="widgets/'.$dir.'/widget.js"></script>'."\n";
    }
}
// }}}
// {{{ show plugins if they exist
foreach ($plugins as $plugin) {
    if(file_exists(KFM_BASE_PATH.'plugins/'.$plugin.'/plugin.php')) include KFM_BASE_PATH.'plugins/'.$plugin.'/plugin.php';
    if(file_exists(KFM_BASE_PATH.'plugins/'.$plugin.'/plugin.js'))echo '		<script type="text/javascript" src="plugins/'.$plugin.'/plugin.js"></script>'."\n";
}
// }}}
// }}} ?>
        <script type="text/javascript" src="j/swfupload-2.1.0b2/swfupload.js"></script>
        <script type="text/javascript" src="j/swfupload-2.1.0b2/swfupload.swfobject.js"></script>
<?php // {{{ more JavaScript environment variables. These should be merged into the above set whenever possible ?>
        <script type="text/javascript">
            var phpsession = "<?php echo session_id(); ?>";
            var session_key="<?php echo $kfm_session->key; ?>";
            var starttype="<?php echo isset($_GET['type'])?$_GET['type']:''; ?>";
            var fckroot="<?php echo $kfm_userfiles_address; ?>";
            var fckrootOutput="<?php echo $kfm_userfiles_output; ?>";
            var kfm_file_handler="<?php echo $kfm_file_handler; ?>";
            var kfm_log_level=<?php echo $kfm_log_level; ?>;
            var kfm_return_directory=<?php echo isset($_GET['return_directory'])?'1':'0'; ?>;
            var kfm_theme="<?php echo $kfm_theme; ?>";
            var kfm_hidden_panels="<?php echo $kfm_hidden_panels; ?>".split(',');
            var kfm_show_files_in_groups_of=<?php echo $kfm_show_files_in_groups_of; ?>;
            var kfm_slideshow_delay=<?php echo ((int)$kfm_slideshow_delay)*1000; ?>;
            var kfm_listview=<?php echo $kfm_listview;?>;
            var kfm_startup_sequence_index = 0;
            var kfm_cwd_id=<?php echo $kfm_startupfolder_id; ?>;
            for(var i = 0;i<kfm_hidden_panels.length;++i)kfm_hidden_panels[i] = 'kfm_'+kfm_hidden_panels[i]+'_panel';
            <?php echo kfm_kaejax_get_javascript(); ?>
            <?php if(isset($_GET['kfm_caller_type']))echo 'window.kfm_caller_type="'.addslashes($_GET['kfm_caller_type']).'";'; ?>
            var editable_extensions=["<?php echo join('","', $kfm_editable_extensions);?>"];
            var viewable_extensions=["<?php echo join('","', $kfm_viewable_extensions);?>"];
        </script>
<?php // }}} ?>
    </body>
</html>
