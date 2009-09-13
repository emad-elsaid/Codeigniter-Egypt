<?php
	$content_id = $ci->uri->segment(5);
	$content_ins = new Content();
	$content_ins->get_by_id( $content_id );
	
	if( ! $content_ins->exists() )
		show_error( 'content not found' );
	
	$parent_content = new Content();
	$parent_content->get_by_id( $content_ins->parent_content );
	
	$parent_section = new Section();
	$parent_section->get_by_id( $content_ins->parent_section );
	
	$data_table = array(
				'Content ID'=>$content_ins->id,
				'Content path'=>$content_ins->path,
				'Section'=>(empty($parent_section->name))? 'Index':$parent_section->name,
				'Subsections'=>($content_ins->subsection)? 'Yes':'No',
				'Parent'=>$parent_content->path,
				'Cell'=>$content_ins->cell,
				'Sort'=>$content_ins->sort
	);
	
	$ci->load->library('gui');
	$ci->app->add_info('Content information and the containers');
	echo $ci->gui->form( '#', $data_table );
?>

<style>
label{
	font-weight: bold;
}
tr{
	border-bottom: 1px solid black;
}
</style>
