<?php
$ci->load->library('gui');
$hidden = array(
				'parent_section'=>$ci->uri->segment(5)
				,'parent_content'=>$ci->uri->segment(6)
				,'cell'=>$ci->uri->segment(7)
				,'sort'=>$ci->uri->segment(8) 
				);
				
echo $ci->gui->form(
		 $ci->app->app_url('Data Editor'), 
		array(
	"Choose a new content"=>$ci->gui->file("path", "", "", array('root'=>'system/application/views/content/') )
	,""=> $ci->gui->button( "", "Add that content", array("type"=>"submit") )
		)
		,''
		,$hidden
);

$recycle = new Content();
$recycle->get_by_parent_content(0);
//$recycle_array = array();
foreach ( $recycle->all as $item ) 
{
	$s = new Section();
	$s->get_by_id( $item->parent_section );
	$recycle_array->{$item->id} = $item->path.'('.$s->name.')';
}

echo $ci->gui->form(
		 $ci->app->app_url('restore'), 
		array(
	"Or from recycle bin"=>$ci->gui->dropdown( "id", '',$recycle_array )
	,""=> $ci->gui->button( "", "Restore that content", array("type"=>"submit") )
		)
		,''
		,$hidden
);
?>
