<?php
    $help = $ci->load->database('help', TRUE);
	$id = $ci->input->post('id');
	$obj = array(
			'title'	=> $ci->input->post('title'),
			'text'	=> $ci->input->post('text')
	);
	
	$help->where('id', $id);
	$help->update('posts', $obj);
	redirect($ci->app->app_url('viewer'));
?>