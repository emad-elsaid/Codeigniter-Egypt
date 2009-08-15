<?php 
$ci =& get_instance();
$ci->load->helper( 'directory' );
$ci->load->library( 'gui' );
$repo = $ci->app->ci_folder.'repo/';

$dir = directory_map( $repo,TRUE );

if( count( $dir )>0 )
{
	$header = array(
				'name'=>'Package name',
				'version'=>'Version',
				'details'=>'Details',
				'uninstall'=>'Uninstall'
			);

	$grid = array();
	foreach( $dir as $index=>$value )
	{
		$arr = explode( '-', $value );
		$item->name = $arr[0];
		$item->version = $arr[1];
		$item->details = anchor($ci->app->app_url( 'package details/'.$index ), 'Details');
		$item->uninstall = anchor($ci->app->app_url( 'uninstall/'.$index ), 'Uninstall');
		array_push( $grid, $item );
		$item = NULL;
	}

	echo $ci->gui->grid( $header, $grid );
	$ci->app->add_info( count( $dir )." Packages installed. " );
}
else
{
	$ci->app->add_info( ' no packages installed ' );
}
