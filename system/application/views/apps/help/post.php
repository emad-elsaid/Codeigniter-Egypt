<?php
    $id = $ci->uri->segment(5);
	if( $id!==FALSE )
	{
		$help = $ci->load->database('help', TRUE);
		$topic = $help->from('posts')->where('id', $id)->get()->result();
		
		$edit = $ci->app->app_url('editor/'.$id);
		$edit = "<a href=\"$edit\" >Edit</a>";
		
		$buttons = "<div class=\"controls\">$edit</div>"; 
		echo "$buttons<h1>{$topic[0]->title}</h1><p>{$topic[0]->text}</p>";
	}
?>