<?php 
$ci =& get_instance();
$ci->load->helper( 'directory' );
$ci->load->helper( 'file' );
$ci->load->library( 'gui' );
$repo = $ci->app->ci_folder.'repo/';
$pkg_index = $ci->uri->segment(5);

$dir = directory_map( $repo,TRUE );
$pkg = read_file( $repo.$dir[$pkg_index] );
$pkg = unserialize( $pkg );

	// making table of properties
	$prop_header = array(
					'p'=>'Property',
					'v'=>'Value'
					);
	$prop = array();
	foreach($pkg as $key=>$value )
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
	foreach( $pkg->files as $key=>$value )
	{
		$stat_item['file'] 		= $key;
		$stat_item['size'] 		= round($value->size/1024, 2);
		$total_size += $value->size;
		if( file_exists( $key ) )
			$stat_item['action']	= $value->action;
		else
			$stat_item['action'] 	= $value->action;
		array_push( $stat, $stat_item );
	}
	
	
	// display the tables
	echo $ci->gui->info( 
			'Total files '.count($pkg->files).
			' , '.round( $total_size/1024, 2).' KB'
			);
	echo $ci->gui->grid( $prop_header, $prop );
	echo $ci->gui->grid( $header, $stat );
