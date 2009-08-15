<?php 
$ci =& get_instance();
$ci->load->helper( 'directory' );
$ci->load->helper( 'file' );
$ci->load->library( 'gui' );
$tmp = $ci->app->ci_folder.'tmp/';

$dir = directory_map( $tmp,TRUE );

function make_path($pathname, $is_filename=false){
	if($is_filename){
		$pathname = substr($pathname, 0, strrpos($pathname, '/'));
	}
	// Check if directory already exists
	if (is_dir($pathname) || empty($pathname)) {
		return true;
	}
	// Ensure a file does not already exist with the same name
	$pathname = str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $pathname);
	if (is_file($pathname)) {
		trigger_error('mkdirr() File exists', E_USER_WARNING);
		return false;
	}
	// Crawl up the directory tree
	$next_pathname = substr($pathname, 0, strrpos($pathname, DIRECTORY_SEPARATOR));
	if (make_path($next_pathname)) {
		if (!file_exists($pathname)) {
			return mkdir($pathname, 0777);
		}
	}
	return false;
}

if( count($dir)>0 )
{
	$sucess = TRUE;
	$package = read_file( $tmp.$dir[0] );
	$package = unserialize( $package );
	
	// making the installation action log
	foreach( $package as $key=>$value )
	{
		if( $key!='files' )
			$repo_pkg->$key = $value;
	}
	
	
	//installing files
	foreach($package->files as $key=>$item)
	{
		make_path( $key, TRUE );
		
		$file_object = NULL;
		if( file_exists( $key ) )
		{
			$file_object->data = read_file( $key );
			$file_object->action = 'replaced';
		}
		else
		{
			$file_object->action = 'created';
		}
			$file_object->size = strlen( $item );
		
		if(! write_file( $key, $item ) )
		{
			echo $ci->gui->error($key.' : Cannot be written.');
			$sucess = FALSE;
		}
		else
		{
			$repo_pkg->files[$key] = $file_object;
		}
	}
	
	$repo_pkg_name = 	$ci->app->ci_folder.'repo/'
						.$repo_pkg->name.'-'
						.$repo_pkg->version;
						
	if( ! write_file( $repo_pkg_name, serialize($repo_pkg) ) )
		$sucess = FALSE;
	
	if($sucess)
	{
		unlink( $tmp.$dir[0] );
		$ci->app->add_info( 'Installation Done.' );
	}
	else
	{
		$ci->app->add_error( 'Installation error occured' );
	}
}
else
{
	$ci->app->add_error( 'Package file not found, try to reupload OR check tmp folder premissions' );
}
