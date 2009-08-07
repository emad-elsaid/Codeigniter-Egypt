<?php 
/**
 * remote access controller
 *
 * @package	Vunsy
 * @subpackage	Vunsy
 * @category	controller file
 * @author	Emad Elsaid
 * @link	http://github.com/blazeeboy/vunsy
 */
class Remote extends Controller {
	
	function Remote(){
		parent::Controller();
		$perm = $this->vunsy->edit_mode();
		if( ! $perm ) show_error("permission denied");
	}

	/* that's a model data grapper using ajax
	 * you can use it to get data from model
	 * send your paramters using POST method
	 * @model : the model name you want to invstigate
	 * @function : the function member you want to execute
	 * @param : a JSON text with the paramters
	 */
/*	function model()
	{
		
		//getting all the paramters
		$mod = $this->input->post( 'model' );
		$func = $this->input->post( 'function' );
		$param = $this->input->post( 'param' );
		$param = json_decode( $param );
		$modName = split( '/', $mod );
		$modName = $modName[-1] ;
		
		//load the model if not loaded
		if(! isset($this->$modName) )
		{
			$this->load->model( $mod );
		}
		
		$expression = "$result = $this->".$mod."->".$func."(".implode( ',',$param).");";
		eval( $expression );
		if ( isset( $result ) )
			return json_encode($result);
		
	}
	
	function orm()
	{
		$mod = $this->input->post( 'model' );
		$id = $this->input->post( 'id' );
		$func = $this->input->post( 'function' );
		$param = $this->input->post( 'param' );
		$param = json_decode( $param );
		echo( "cxvxcvxcvcxv" );
		$obj = new $mod();
		$obj->get_by_id( $id );
		if( $obj->exists() )
		{
			$expression = "$result = $obj->".$func."(".implode( ',',$param).");";
			eval( $expression );
			if( isset( $result ) )
				return json_encode( $result );
		}
	}*/
	
	function file()
	{
		$root = '';
		$_POST['dir'] = urldecode($_POST['dir']);

		if( file_exists($root . $_POST['dir']) )
		{
			
			$files = scandir($root . $_POST['dir']);
			natcasesort($files);
			
			if( count($files) > 2 )
			{ /* The 2 accounts for . and .. */
				echo "<ul class=\"jqueryFileTree\" style=\"display: none;\">";
				// All dirs
				foreach( $files as $file )
				{
					if( 	file_exists($root . $_POST['dir'] . $file) 
							&& $file != '.' && $file != '..' 
							&& is_dir($root . $_POST['dir'] . $file)
					) {
						echo "<li class=\"directory collapsed\"><a href=\"#\" rel=\"" . htmlentities($_POST['dir'] . $file) . "/\">" . htmlentities($file) . "</a></li>";
					}
				}
				// All files
				foreach( $files as $file )
				{
					if( 
							file_exists($root . $_POST['dir'] . $file) 
							&& $file != '.' 
							&& $file != 'index.html' 
							&& $file != '..' 
							&& !is_dir($root . $_POST['dir'] . $file)
						) {
							$ext = preg_replace('/^.*\./', '', $file);
							echo "<li class=\"file ext_$ext\"><a href=\"#\" rel=\"" . htmlentities($_POST['dir'] . $file) . "\">" . htmlentities($file) . "</a></li>";
						}
				}
				echo "</ul>";
			}
		}
	}
	
	function dir()
	{
		$root = '';
		$_POST['dir'] = urldecode($_POST['dir']);

		if( file_exists($root . $_POST['dir']) )
		{
			$files = scandir($root . $_POST['dir']);
			natcasesort($files);
			if( count($files) > 2 )
			{ /* The 2 accounts for . and .. */
				echo "<ul class=\"jqueryFileTree\" style=\"display: none;\">";
				// All dirs
				foreach( $files as $file )
				{
					if( 	file_exists($root . $_POST['dir'] . $file) 
							&& $file != '.' 
							&& $file != '..' 
							&& is_dir($root . $_POST['dir'] . $file) 
					){
						echo "<li class=\"file ext_file\"><a href=\"#\" rel=\"" . htmlentities($_POST['dir'] . $file) . "/\">Choose " . htmlentities($file) . "</a></li>";
						echo "<li class=\"directory collapsed\"><a href=\"#\" rel=\"" . htmlentities($_POST['dir'] . $file) . "/\">" . htmlentities($file) . "</a></li>";
					}
				}
				echo "</ul>";
			}
		}
	}
}

