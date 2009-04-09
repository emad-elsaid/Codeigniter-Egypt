<?php
$ci =& get_instance();
$ci->load->helper( 'directory' );

$map = directory_map( './system/application/views/apps/', TRUE );
if( array_search( 'index.html', $map ) )
	unset( $map[array_search( 'index.html', $map )] );

for( $i=0; $i<count($map); $i++ )
{
	$map[$i] = array(
			'Application' => $map[$i],
			'Run' => anchor( site_url('admin/app').'/'.$map[$i], 'RUN'),
			'Uninstall' => anchor( $ci->app->app_url("uninstall/{$map[$i]}"), 'Uninstall' ),
			'Download' => anchor( $ci->app->app_url("download/{$map[$i]}"), 'Download' )
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
