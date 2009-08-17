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

// a variable determine if the package removed sucessfully
$sucess = TRUE;
	
	foreach( $pkg->files as $key=>$value )
	{
		
		if( file_exists($key) )
		{
			if( $value->action == 'created' )
			{
				if( ! unlink( $key ) )
				{
					$ci->app->add_error( "file : $key Cannot be deleted" );
					$sucess = FALSE;
				}
			}
			else if( $value->action == 'replaced' )
			{
				if( ! write_file( $key, $value->data ) )
				{
					$ci->app->add_error( "file : $key Cannot be reverted" );
					$sucess = FALSE;
				}
			}
		}
	}
	
	if( $sucess )
	{
		if( ! unlink( $repo.$dir[$pkg_index] ) )
		{
			$ci->app->add_error( "cannot remove package from the list" );
		}
		else
		{
			$ci->app->add_info( "Package removed" );
		}
	}
	else
	{
		$ci->app->add_error( "Package has faild to be uninstalled, an error occured" );
	}
	
function del_path($pathname, $is_filename=FALSE){
	if($is_filename){
		$pathname = substr($pathname, 0, strrpos($pathname, '/'));
	}
	// Ensure a file does not already exist with the same name
	$pathname = str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $pathname);
	
	if( empty( $pathname ) )
		return true;
		
	$next_pathname = substr($pathname, 0, strrpos($pathname, DIRECTORY_SEPARATOR));
	if( @rmdir( $pathname ) )
		del_path($next_pathname);
}


// removing empty directories
foreach( $pkg->files as $key=>$value )
{
	del_path( $key, TRUE );
}
