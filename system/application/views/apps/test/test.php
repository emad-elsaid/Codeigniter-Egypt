<?php
$CI =& get_instance();
$CI->load->library('gui');
/*echo $CI->gui->color( 'color' ,'#822a34');
echo $CI->gui->file( "T", '', array( "size" => "50" ), array() );
echo $CI->gui->date( 'datetttt' );
echo $CI->gui->time( 'time', 'T11:00' );*/
//echo $CI->gui->textarea( 'textarea', 'sdsdfsdfsdfsdf');
//echo $CI->gui->editor( 'textarea', 'sdsd<textarea></textarea></textarea>fsdfsdfsdf');
echo $CI->gui->dropdown( "sdsdf", "ar", array( 'ar'=>'Arabic', 'en'=>'English', 'fr'=>'frensh' ) );
?>
