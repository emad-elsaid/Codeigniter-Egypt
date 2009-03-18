<?php
$CI =& get_instance();
$CI->load->library('gui');
//echo $CI->gui->color( 'color' ,'#822a34');
echo $CI->gui->file( "T", '', array( "size" => "50" ), array('sfbpath'=>'system') );
echo $CI->gui->date( 'datetttt' );
echo $CI->gui->time( 'time', 'T11:00' );
echo $CI->gui->textarea( 'textarea', 'sdsdfsdfsdfsdf');
echo $CI->gui->editor( 'textareaxzxc', 'sdsd<textarea></textarea></textarea>fsdfsdfsdf');
echo $CI->gui->dropdown( "sdsdf", "ar", array( 'ar'=>'Arabic', 'en'=>'English', 'fr'=>'frensh' ) );
echo $CI->gui->checkbox( "ID", "value", TRUE);
echo $CI->gui->textbox( "IDxzczx", "skdjfhsdkjfhskjf" );
echo $CI->gui->password( "IDdcdfgrg", 50 );
echo $CI->gui->button( "sdjkfhsdf", "sdfhsdgf" );

/*
echo $CI->gui->form( $this->app->app_url("text page2"), array(
"color" => $CI->gui->color( 'color' ,'#822a34').$CI->gui->tooltip( "color", "dskjfsghdks sdjhsbfjskbf d")
,"file chooser " => $CI->gui->file( "T", '', array( "size" => "50" ), array() )
,"Date picker" => $CI->gui->date( 'datetttt' )
,"Time" => $CI->gui->time( 'time', 'T11:00' )
,"text area" => $CI->gui->textarea( 'textarea', 'sdsdfsdfsdfsdf')
,"Editor" => $CI->gui->editor( 'textareaxzxc', 'sdsd')
,"dropdown"=> $CI->gui->dropdown( "sdsdf", "ar", array( 'ar'=>'Arabic', 'en'=>'English', 'fr'=>'frensh' ) )
,"checkbox"=> $CI->gui->checkbox( "ID", "value", TRUE)
,"textbox"=> $CI->gui->textbox( "IDxzczx", "skdjfhsdkjfhskjf" )
,"password"=> $CI->gui->password( "IDdcdfgrg", 50 )
,""=> $CI->gui->button( "sdjkfhsdf", "sdfhsdgf" ,array("type"=>'submit' ))
) );*/
?>
