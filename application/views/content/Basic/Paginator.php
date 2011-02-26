<?php if( $mode=='config' ){
//the plugin requirements as a JSON object is here ?>
{
	"limit" : { "type":"number", "default":"10", "label":"Number of content per page" },
	"page_up" : { "type":"checkbox", "default":true, "label":"Show page numbers Before" },
	"page_down" : { "type":"checkbox", "default":true, "label":"Show page numbers After" },
	"empty_msg" : { "type":"textbox", "label":"If paginator empty display", "default":"No children available"},
	"info" : "identifier will let system identify your paginator in case of inserting more than one paginator in your page",
	"identifier":{ "type":"textbox", "default":"p" },
	"reverse":{ "type":"checkbox", "label":"Reverse content order"  },
	"pagination config" : "these variables are the config of pagination items see codeigniter pagination library",
	"num_links" : { "type":"number", "default": "2" },
	"first_link" : { "type":"textbox", "default":  "&lsaquo; First" },
	"next_link" : { "type":"textbox", "default": "&gt;" },
	"prev_link" : { "type":"textbox", "default": "&lt;" },
	"last_link" : { "type":"textbox", "default": "Last &rsaquo;" },
	"uri_segment" : { "type":"number", "default": "3" },
	"full_tag_open" : { "type":"textbox", "default": "" },
	"full_tag_close" : { "type":"textbox", "default": "" },
	"first_tag_open" : { "type":"textbox", "default": "" },
	"first_tag_close" : { "type":"textbox", "default": "&nbsp;" },
	"last_tag_open" : { "type":"textbox", "default": "&nbsp;" },
	"last_tag_close" : { "type":"textbox", "default": "" },
	"cur_tag_open" : { "type":"textbox", "default": "&nbsp;<strong>" },
	"cur_tag_close" : { "type":"textbox", "default": "</strong>" },
	"next_tag_open" : { "type":"textbox", "default": "&nbsp;" },
	"next_tag_close" : { "type":"textbox", "default": "&nbsp;" },
	"prev_tag_open" : { "type":"textbox", "default": "&nbsp;" },
	"prev_tag_close" : { "type":"textbox", "default": "" },
	"num_tag_open" : { "type":"textbox", "default": "&nbsp;" },
	"num_tag_close" : { "type":"textbox", "default": "" }
}


<?php }else if( $mode=='layout' ){ 
//replace 0 with number of cells your plugin has ?>
0


<?php }else if( $mode=='view' ){
//the real content of your plugin goes here ?>
<?php
$children = new Content();
$children->where( 'parent_content', $id );
$children->where( 'parent_section', $ci->system->section->id );

// generate pagination
$ci->load->library('pagination');
$config['base_url'] 			= site_url( $ci->system->section->id.'/'.$info->identifier );
$config['total_rows'] 			= $children->result_count();
$config['per_page'] 			= $info->limit;
if( $info->num_links>0 )
	$config['num_links'] 			= $info->num_links;
$config['first_link'] 			= $info->first_link;
$config['next_link	']			= $info->next_link;
$config['prev_link']			= $info->prev_link;
$config['last_link']			= $info->last_link;
$config['uri_segment']			= $info->uri_segment;
$config['full_tag_open']		= $info->full_tag_open;
$config['full_tag_close']		= $info->full_tag_close;
$config['first_tag_open']		= $info->first_tag_open;
$config['first_tag_close']		= $info->first_tag_close;
$config['last_tag_open']		= $info->last_tag_open;
$config['last_tag_close']		= $info->last_tag_close;
$config['cur_tag_open']		= $info->cur_tag_open;
$config['cur_tag_close']		= $info->cur_tag_close;
$config['next_tag_open']		= $info->next_tag_open;
$config['next_tag_close']		= $info->next_tag_close;
$config['prev_tag_open']		= $info->prev_tag_open;
$config['prev_tag_close']		= $info->prev_tag_close;
$config['num_tag_open']		= $info->num_tag_open;
$config['num_tag_close']		= $info->num_tag_close;

$ci->pagination->initialize($config);//++++
$links = $ci->pagination->create_links();//+++

$children->where( 'parent_content', $id );
$children->where( 'parent_section', $ci->system->section->id );

if( $ci->uri->segment(2)==$info->identifier ) 
	$children->limit( $info->limit, intval($ci->uri->segment(3)) );
else
	$children->limit( $info->limit );
	
if( $info->reverse )
	$children->order_by( 'sort', 'desc' );
else
	$children->order_by( 'sort', 'asc' );

$children->get();

// display the paginator HTML
$text = '';
if( $info->page_up )
	$text .= $links;

if( $children->result_count() > 0 )
{
	foreach ( $children as $child ) 
	{
		$text .= $child->render();
	}
}
else
{
	$ci->load->library('gui');
	$c = new Content();
	$c->get_by_id( $id );
	if( $ci->ion_auth->is_admin() and $ci->system->mode()=='edit' )
		$text .= $c->add_button(0);
	if( !empty( $info->empty_msg ) )
		$text .= $ci->gui->info( $info->empty_msg );
}

if( $info->page_down )
	$text .= $links;

echo $text;
?>
<?php } ?>
