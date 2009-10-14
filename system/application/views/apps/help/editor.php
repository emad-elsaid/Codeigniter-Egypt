<?php
    $ci->load->plugin('tinymce');
	$ci->load->library('gui');
	
	$id = $ci->uri->segment(5);
	if( $id==FALSE )
	{
		$form_url 		= $ci->app->app_url('addAction');
		$title = $text 	= '';
		$extra_links 	= '';
	}
	else
	{
		$form_url = $ci->app->app_url('editAction/'.$id);
		$help 			= $ci->load->database( 'help', TRUE );
		$post 			= $help->get_where( 'posts', array('id'=>$id) )->result();
		$title 			= $post[0]->title;
		$text 			= $post[0]->text;
		$extra_links 	= anchor( $ci->app->app_url('deleteAction/'.$id), 'Delete this Post'); 
	}
	
	echo $ci->gui->form(
				$form_url,
				array(
					'title'	=>	$ci->gui->textbox('title', $title ),
					'text'	=>	tinyMCE('text', $text ),
					''		=>	$ci->gui->button('','Submit', array('type'=>'submit')).$extra_links
				),
				array(),
				array('id'=>$id )
			);
?>