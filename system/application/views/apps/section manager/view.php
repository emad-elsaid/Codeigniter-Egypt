<?php 
$ci =& get_instance();
$ci->load->library( 'gui' );

$sections = new Section();

foreach( $sections->all as $item )
{
	$item->e = anchor( 'Edit', $ci->app->app_url( 'edit' ) );
	$item->d = anchor( 'Delete', $ci->app->app_url( 'delete' ) );
	
}

function add( $id, $sort , $text='+' )
{
	$ci =& get_instance();
	$hidden = array( 'parent_section'=>$id, 'sort'=>$sort );
	return $ci->gui->tooltipbutton(
			$text
			,'New Section Info' 
			,$ci->gui->form(
					$ci->app->app_url( 'addaction' )
					,array(
							'Name :'=>$ci->gui->textbox( 'name' )
							,'View :'=>$ci->gui->textarea( 'view' )
							,''=>$ci->gui->button( '', 'Submit', array('type'=>'submit') )
					)
					,''
					,$hidden
			)
	);
}
function printS( $id )
{
	$ci =& get_instance();
	$s = new Section();
	$s->get_by_id( $id );

	$s->e = anchor( $ci->app->app_url( 'edit/' ).$s->id, 'Edit' );
	$s->d = anchor( $ci->app->app_url( 'delete/' ).$s->id,'Delete' );
	
	$output = "<li>";
	$output .= $s->id .'|';
	$output .= $s->name;
	$output .= add( $id, 0, "add first child for {$s->name}" );
	$output .= '|';
	$output .= $s->e;
	$output .= '|';
	$output .= $s->d;
	$c = new Section();
	$c->where( 'parent_section',  $id );
	$c->order_by( 'sort', 'asc' );
	$c->get();
	
	if( count($c->all) > 0 )
	{
		$output .= "<ul>";
		
		foreach( $c->all as $item )
		{
			$output .= printS( $item->id );
			$output .= "<li>".add( $id, $item->sort+1 )."</li>";
		}
		$output .= "</ul>";
	}
	
	
	$output .= "</li>";
	return $output;
	}

// start to print sections from index page with ID = 1
echo printS( 1 );
