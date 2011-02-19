<?php
$ci =& get_instance();
$ci->load->helper( 'directory' );

$map = directory_map( APPPATH.'views/apps/', TRUE );
if( array_search( 'index.html', $map ) )
	unset( $map[array_search( 'index.html', $map )] );

foreach ( $map as $index=>$item )
{
	$map[$index] = array(
			'Application' => $item,
			'Run' => anchor( site_url('admin/app').'/'.$item, 'RUN'),
			'Uninstall' => anchor( $ci->app->app_url("uninstall/{$item}"), 'Uninstall' ),
			'Download' => anchor( $ci->app->app_url("download/{$item}"), 'Download' )
	);
}

$ci->load->library( 'gui' );
echo $ci->gui->grid( array( 
	'Application'=>'Application name',
	'Run'=>'Run',
	'Download' => 'Download',
	'Uninstall'=>'Uninstall'
	),
	$map
);
