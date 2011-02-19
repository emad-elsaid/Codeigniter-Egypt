<?php 
$ci =& get_instance();
$ci->load->helper(array('form', 'url'));

function clear_folder( $path )
{
	$ci =& get_instance();
	$ci->load->helper( 'directory' );
	$dir = directory_map( $path,TRUE );
	foreach( $dir as $item )
		unlink( $path.$item );
}

if( $ci->input->post('action')!==FALSE ){
		$config['upload_path'] = $ci->app->ci_folder.'tmp/';
		$config['allowed_types'] = 'vpkg';
		$config['max_size']	= '10000';
		
		$ci->load->library('upload', $config);
	
		clear_folder( $config['upload_path'] );
		
		if ( ! $ci->upload->do_upload())
		{
			$ci->app->add_error( $ci->upload->display_errors('',''));
		}	
		else
		{
		  redirect( $ci->app->app_url( 'install viewer' ) );
		}
} 

echo form_open_multipart($ci->app->app_url('install'));
?>

<input type="file" name="userfile" size="20" />
<input type="submit" value="upload" name='action' />

</form>
