<?php
$CI =& get_instance();
$hidden = array(
				'parent_section'=>$CI->uri->segment(5)
				,'parent_content'=>$CI->uri->segment(6)
				,'cell'=>$CI->uri->segment(7)
				,'sort'=>$CI->uri->segment(8) 
				);
				
$CI->load->library('gui');
echo $CI->gui->form(
		 $CI->app->app_url('Data Editor'), 
		array(
				"Choose a content"=>$CI->gui->file("path")
				,""=> $CI->gui->button( "", "Add that content", array("type"=>"submit") )
		)
		,''
		,$hidden
);

echo $CI->gui->tooltip("content","you can choose a widget or a layout from <br> system/application/views ");
?>
