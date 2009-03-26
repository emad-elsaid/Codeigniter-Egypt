<?php

$ci =& get_instance();
$ci->load->library( 'zip' );

$app = $ci->uri->segment( 5 );
$path = "./system/application/views/apps/$app/";

$ci->zip->read_dir($path);
$ci->zip->download("{$app}.zip");
