<?php
  	$help = $ci->load->database('help', TRUE);
	$obj = array(
			'title'	=> $ci->input->post('title'),
			'text'	=> $ci->input->post('text')
	);
	$help->insert('posts', $obj);
	redirect($ci->app->app_url('viewer'));
?>