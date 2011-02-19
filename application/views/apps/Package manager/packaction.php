<?php 
$ci =& get_instance();
$ci->load->helper( 'file' );
$ci->load->helper( 'directory' );
$ci->load->helper( 'download' );
$files = $ci->input->post('files');
$files = explode( "\n", $files );

function remove_spaces($item)
{
	if( ! file_exists($item) )
		show_error( 'Error with file item :'. $item );
	
	return $item;
}
function remove_nulls( $item )
{
	$item = trim( $item );
	if( $item == '' )
		return false;
	else
		return true;
}

function dir_merge( $prefix, $arr )
{
	$arr_m = array();
	foreach( $arr as $key=>$item )
	{
		if( is_string( $item ) )
			array_push( $arr_m, $prefix.$item );
		else
		{
			$arr_m = array_merge( $arr_m, dir_merge( $prefix.$key.'/',$item ) );
		}
	}
	return $arr_m;
}

function load_directory($path)
{
	if( is_dir( $path ) )
	{
		$dir = directory_map( $path );
		return dir_merge( $path, $dir );
	}
	else
	{
		return $path;
	}
}

$files = array_filter( $files, 'remove_nulls' );
$files = array_map( 'remove_spaces' , $files );
$allFiles = array();
foreach( $files as $item )
{
	if( is_dir( $item ) )
		$allFiles = array_merge( $allFiles, load_directory( $item ) );
	else if( is_file( $item ) )
		array_push( $allFiles, $item );
}
//packing data
$data = array();

foreach( $allFiles as $item )
{
	$data[$item] = read_file( $item );
}

$package->name = $ci->input->post( 'pluginName' );
$package->version = $ci->input->post( 'version' );
$package->author = $ci->input->post( 'author' );
$package->website = $ci->input->post( 'website' );
$package->description = $ci->input->post( 'description' );
$package->files = $data;

$serialized_data = serialize( $package );

// downloading data
force_download( "{$package->name}-{$package->version}.vpkg", $serialized_data );
?>
