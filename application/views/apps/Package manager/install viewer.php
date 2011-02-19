<?php 
$ci =& get_instance();
$ci->load->helper( 'directory' );
$ci->load->helper( 'file' );
$ci->load->library( 'gui' );
$tmp = $ci->app->ci_folder.'tmp/';

$dir = directory_map( $tmp,TRUE );

if( count($dir)>0 )
{
	$package = read_file( $tmp.$dir[0] );
	$package = unserialize( $package );
	
	// making table of properties
	$prop_header = array(
					'p'=>'Property',
					'v'=>'Value'
					);
	$prop = array();
	foreach($package as $key=>$value )
	{
		if( is_string($value) )
		{
			$prop_item['p'] = $key;
			$prop_item['v'] = nl2br($value);
			array_push( $prop, $prop_item );
		}
	}
	
	
	// making files table
	$total_size = 0;
	$stat = array();
	$header = array( 
				'file'=>'File name',
				'size'=>'Size (KB)',
				'action'=>'Action'
				);
	foreach( $package->files as $key=>$value )
	{
		$stat_item['file'] 		= $key;
		$stat_item['size'] 		= round(strlen($value)/1024, 2);
		$total_size += strlen($value);
		if( file_exists( $key ) )
			$stat_item['action']	= 'replace';
		else
			$stat_item['action'] 	= 'create';
		array_push( $stat, $stat_item );
	}
	
	
	// display the tables
	echo $ci->gui->info( 
			'Total files '.count($package->files).
			' , '.round( $total_size/1024, 2).' KB'
			);
	echo $ci->gui->grid( $prop_header, $prop );
	echo $ci->gui->grid( $header, $stat );
	
	$repo_pkg = $ci->app->ci_folder.'repo/'
				.$package->name.'-'
				.$package->version;
	if( file_exists( $repo_pkg ) )
	{
		$ci->app->add_error( 'package already installed , you have to remove it at first' );
	}
	else
	{
		$ci->app->add_info( $ci->gui->form(
			$ci->app->app_url( 'installation' ),
			array( ''=>
			'If you pressed that button all the package files will be installed on the system'. 
			$ci->gui->button('Install','install','type="submit"'))
		));
	}
}
else
{
	$ci->app->add_error( 'Package file not found, try to reupload OR check tmp folder premissions' );
}
