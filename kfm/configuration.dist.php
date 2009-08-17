<?php
/**
 * KFM - Kae's File Manager
 *
 * configuration example file
 *
 * do not delete this file. copy it to configuration.php, remove the lines
 *   you don't want to change, and edit the rest to your own needs.
 *
 * @category None
 * @package  None
 * @author   Kae Verens <kae@verens.com>
 * @author   Benjamin ter Kuile <bterkuile@gmail.com>
 * @license  docs/license.txt for licensing
 * @link     http://kfm.verens.com/
 */

// user access details. all users may use get.php without logging in, but
//   if the following details are filled in, then login will be required
//   for the main KFM application
// for more details, see http://kfm.verens.com/security
$kfm_username = '';
$kfm_password = '';

// what type of database to use
// values allowed: mysql, pgsql, sqlite, sqlitepdo
$kfm_db_type = 'sqlitepdo';

// the following options should only be filled if you are not using sqlite/sqlitepdo as the database
$kfm_db_prefix   = 'kfm_';
$kfm_db_host     = 'localhost';
$kfm_db_name     = 'kfm';
$kfm_db_username = 'username';
$kfm_db_password = 'password';
$kfm_db_port     = '';

// where on the machine are the files located? if the first two characters are './', then the
//   files are relative to the directory that KFM is in.
// Here are some examples:
// $kfm_userfiles_address = '/home/kae/userfiles'; # absolute address in Linux
// $kfm_userfiles_address = 'D:/Files';            # absolute address in Windows
// $kfm_userfiles_address = './uploads';           # relative address
$kfm_userfiles_address = '/home/kae/Desktop/userfiles';

// where should a browser look to find the files?
// Note that this is usually the same as $kfm_userfiles_address (if it is relative), but could be different
//   in the case that the server uses mod_rewrite or personal web-sites, etc
// Use the value 'get.php' if you want to use the KFM file handler script to manage file downloads.
// If you are not using get.php, this value must end in '/'.
// Examples:
//   $kfm_userfiles_output = 'http://thisdomain.com/files/';
//   $kfm_userfiles_output = '/files/';
//   $kfm_userfiles_output = 'http://thisdomain.com/kfm/get.php';
//   $kfm_userfiles_output = '/kfm/get.php';
$kfm_userfiles_output = '/userfiles/';

// if you want to hide any panels, add them here as a comma-delimited string
// for example, $kfm_hidden_panels = 'logs,file_details,file_upload,search,directory_properties';
$kfm_hidden_panels = 'logs';

// what happens if someone double-clicks a file or presses enter on one? use 'return' for FCKeditor
// values allowed: download, return
$kfm_file_handler = 'return';

// if 'return' is chosen above, do you want to allow multiple file returns?
$kfm_allow_multiple_file_returns = true;

// directory in which KFM keeps its database and generated files
// if this starts with '/', then the address is absolute. otherwise, it is relative to $kfm_userfiles_address.
// $kfm_workdirectory = '.files';
// $kfm_workdirectory = '/home/kae/files_cache';
// warning: if you use the '/' method, then you must use the get.php method for $kfm_userfiles_output.
$kfm_workdirectory = '.files';

// maximum length of filenames in Icon mode. use 0 to turn this off, or enter the number of letters.
$kfm_files_name_length_displayed = 20;

// maximum length of filenames in Listmode. use 0 to turn this off, or enter the number of letters.
$kfm_files_name_length_in_list = 0;

// 1 = users are allowed to delete directories
// 0 = users are not allowed to delete directories
$kfm_allow_directory_delete = 1;

// 1 = users are allowed to edit directories
// 0 = users are not allowed to edit directories
$kfm_allow_directory_edit = 1;

// 1 = users are allowed to move directories
// 0 = users are not allowed to move directories
$kfm_allow_directory_move = 1;

// 1 = users are allowed to create directories
// 0 = user are not allowed create directories
$kfm_allow_directory_create = 1;

// 1 = users are allowed to create files
// 0 = users are not allowed to create files
$kfm_allow_file_create = 1;

// 1 = users are allowed to delete files
// 0 = users are not allowed to delete files
$kfm_allow_file_delete = 1;

// 1 = users are allowed to edit files
// 0 = users are not allowed to edit files
$kfm_allow_file_edit = 1;

// 1 = users are allowed to move files
// 0 = users are not allowed to move files
$kfm_allow_file_move = 1;

// 1 = users are allowed to upload files
// 0 = user are not allowed upload files
$kfm_allow_file_upload = 1;

// use this array to ban dangerous files from being uploaded.
$kfm_banned_extensions = array('asp','cfm','cgi','php','php3','php4','php5','phtm','pl','sh','shtm','shtml');

// you can use regular expressions in this one.
// for exact matches, use lowercase.
// for regular expressions, use eithe '/' or '@' as the delimiter
$kfm_banned_files = array('thumbs.db','/^\./');

// you can use regular expressions in this one.
// for exact matches, use lowercase.
// for regular expressions, use eithe '/' or '@' as the delimiter
$kfm_banned_folders = array('/^\./');

// this array tells KFM what extensions indicate files which may be edited online.
$kfm_editable_extensions = array('css','html','js','php','txt','xhtml','xml');

// this array tells KFM what extensions indicate files which may be viewed online.
// the contents of $kfm_editable_extensions will be added automatically.
$kfm_viewable_extensions = array('sql','php');

// 1 = users can only upload images
// 0 = don't restrict the types of uploadable file
$kfm_only_allow_image_upload = 0;

// 0 = only errors will be logged
// 1 = everything will be logged
$kfm_log_level = 0;

// use this array to show the order in which language files will be checked for
$kfm_preferred_languages = array('en','de','da','es','fr','nl','ga');

// themes are located in ./themes/
// to use a different theme, replace 'default' with the name of the theme's directory.
$kfm_theme = 'default';

// use ImageMagick's 'convert' program?
$kfm_use_imagemagick = 1;

// where is the 'convert' program kept, if you have it installed?
$kfm_imagemagick_path = '/usr/bin/convert';

// show files in groups of 'n', where 'n' is a number (helps speed up files display - use low numbers for slow machines)
$kfm_show_files_in_groups_of = 10;

// should disabled links be shown (but grayed out and unclickable), or completely hidden?
// you might use this if you want your users to not know what it is that's been disabled, for example.
$kfm_show_disabled_contextmenu_links = 1;

// multiple file uploads are handled through the external SWFUpload flash application.
// this can cause difficulties on some systems, so if you have problems uploading, then disable this.
$kfm_use_multiple_file_upload = 0;

// seconds between slides in a slideshow
$kfm_slideshow_delay = 4;

// allow users to resize/rotate images
$kfm_allow_image_manipulation = 1;

// set root folder name
// Set to foldername to use actual folder name or root when $kfm_user_root_folder is not set
$kfm_root_folder_name = 'foldername';

// if you are using a CMS and want to return the file's DB id instead of the URL, set this
$kfm_return_file_id_to_cms = 0;

//Permissions for uploaded files.  This only really needs changing if your
//host has a weird permissions scheme.
$kfm_default_upload_permission = '664';

//Listview or icons
$kfm_listview = 0;

// how many files to attempt to draw at a time (use a low value for old client machines, and a higher value for newer machines)
$kfm_show_files_in_groups_of = 10;

// default directories. Separate with commas. These will be created if they don't already exist.
// $kfm_default_directories='Documents,Music,Video';
$kfm_default_directories='';

// we would like to keep track of installations, to see how many there are, and what versions are in use.
// if you do not want us to have this information, then set the following variable to '1'.
$kfm_dont_send_metrics = 0;

// hours to offset server time by.
// for example, if the server is in GMT, and you are in Northern Territory, Australia, then the value to use is 9.5
$kfm_server_hours_offset = 1;

// 1=always move, 2=always copy, 3=give choice
$kfm_drags_move_or_copy_files = 3;

// allow files in the root directory
$kfm_allow_files_in_root = 1;
