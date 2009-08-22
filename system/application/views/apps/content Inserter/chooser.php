<?php
$hidden = array(
				'parent_section'=>$ci->uri->segment(5)
				,'parent_content'=>$ci->uri->segment(6)
				,'cell'=>$ci->uri->segment(7)
				,'sort'=>$ci->uri->segment(8) 
				);
				
$ci->load->library('gui');
echo $ci->gui->form(
		 $ci->app->app_url('Data Editor'), 
		array(
				"Choose a content"=>$ci->gui->file("path", "", "", array('root'=>'system/application/views/content/') )
				,""=> $ci->gui->button( "", "Add that content", array("type"=>"submit") )
		)
		,''
		,$hidden
);
?>
