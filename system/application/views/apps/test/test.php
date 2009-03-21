<?php
$CI =& get_instance();
$CI->load->library('gui');

echo $CI->gui->file( 'fileb','file', '',array('root'=>'jquery/'),'');
echo $CI->gui->color('df','#e32bcb');
