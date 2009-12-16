<?php 
/** \addtogroup Controllers
 * Remote access controller
 *that controller serves the AJAX purpose
 * , it provide the files , content, directory widgets with
 * the information needed through AJAX
 * 
 * @package	Vunsy
 * @subpackage	Vunsy
 * @category	controller file
 * @author	Emad Elsaid
 * @link	http://github.com/blazeeboy/vunsy
 */
class Remote extends Controller {
	
	/**
	 * that controller serves the AJAX purpose
	 * , it provide the files , content, directory widgets with
	 * the information needed through AJAX
	 * */
	function Remote(){
		parent::Controller();
	}


	/**
	 * function that load a view file from directory ajax
	 * and return the result.
	 * it's mainly made for ajax purposes
	 * 
	 * */
	function ajax($file='')
	{
		if( empty( $file ) )
			show_404('');
		if( strpos( $file, '..' )!==FALSE )
			show_error( '.. characters not allwed' );
		
		$file = str_replace( '.', '/', $file );
		$file = 'ajax/'. $file;
		
		if( count($_POST)>0 )
			$info = json_decode( json_encode( $_POST ) );
		else
			$info = FALSE;
		
		$this->load->view(
						$file,
						array( 'info'=>$info )
						);
		
	}
	/* that's a model data grapper using ajax
	 * you can use it to get data from model
	 * send your paramters using POST method
	 * @model : the model name you want to invstigate
	 * @function : the function member you want to execute
	 * @param : a JSON text with the paramters
	 */
	/*function model()
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
		
	}*/
	
	/*function orm()
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
	
	
	/*
	 * loads returns the content information field
	 * @param $id: the content id you want to retrieve it's information
	 * you can use it via AJAX using the URL : site_url('remote/content/20');
	 * */
	/** i disabled it so that it could be considered as a security hole **/
	/*function remote( $id=0 )
	{
		
		if( $id==0 )
			$id = $this->uri->post('id');
			
		if( $id != FALSE && $id != 0 && $id != '' )
		{
			$cont = new Content();
			$cont->get_by_id( $id );
			$this->load->view( 'text', array( 'text'=> $cont->info ) );
		}
	}
	*/
	
	/**
	 * that function you don't have to use it at all
	 * it's the PHP backend to the file chooser widget of gui class
	 * it returns ist of files within the passed directory via POST
	 * */
	function file()
	{
		if( ! perm_chck( 'edit' ) ) show_error("permission denied");
		
		$this->load->helper('directory');
		
		$root = '';
		$_POST['dir'] = urldecode($_POST['dir']);

		if( file_exists($root . $_POST['dir']) )
		{
			
			$files = directory_map($root . $_POST['dir'], TRUE );
			natcasesort($files);
			
			if( count($files) > 0 )
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
							&& $file != 'index.html' 
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
	
	/**
	 * that function you don't have to use it at all
	 * it's the PHP backend to the directory chooser widget of gui class
	 * it returns ist of directories within the passed directory via POST
	 * */
	function dir()
	{
		if( ! perm_chck( 'edit' ) ) show_error("permission denied");
		$this->load->helper('directory');
		$root = '';
		$_POST['dir'] = urldecode($_POST['dir']);

		if( file_exists($root . $_POST['dir']) )
		{
			$files = directory_map($root . $_POST['dir'], TRUE);
			natcasesort($files);
			if( count($files) > 0 )
			{ /* The 2 accounts for . and .. */
				echo "<ul class=\"jqueryFileTree\" style=\"display: none;\">";
				// All dirs
				foreach( $files as $file )
				{
					if( 	file_exists($root . $_POST['dir'] . $file) 
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

