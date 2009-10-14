<?php
   	$help = $ci->load->database('help', TRUE);
	$id = $ci->uri->segment(5);
	$help->delete('posts', array('id'=>$id));
	redirect($ci->app->app_url('viewer'));
?>