<?php 
class Remote extends Controller {
	function Remote(){
		parent::Controller();
		$perm = $this->vunsy->edit_mode();
		if( ! $perm ) redirect();
	}
	
	function model()
	{
		
		//getting all the paramters
		$mod = $this->input->post( 'model' );
		$func = $this->input->post( 'function' );
		$param = $this->input->post( 'param' );
		$param = json_decode( $param );
		$modName = split( '/', $mod )[-1] ;
		
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
		
		$obj = new $mod();
		$obj->get_by_id( $id );
		if( $obj->exists() )
		{
			$expression = "$result = $obj->".$func."(".implode( ',',$param).");";
			eval( $expression );
			if( isset( $result ) )
				return json_encode( $result );
		}
	}
}
