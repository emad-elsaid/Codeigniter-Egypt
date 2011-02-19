<?php
$ci =& get_instance();
$ci->load->helper( 'directory' );

function deldir($d)
{
	$map = directory_map($d, TRUE);
	foreach( $map as $item )
	{
		if( is_file( $d.$item ) )
		{
			unlink( $d.$item );
		}
		else
		{
			deldir( $d.$item.'/' );
		}
	}
	rmdir( $d.'/' );
}

deldir( './system/application/views/apps/'.$ci->uri->segment( 5 ).'/' );
redirect( $ci->app->app_url( 'browse' ) );
