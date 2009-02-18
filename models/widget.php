<?php
class Widget extends Content{
		
	function Widget()
	{
		parent::Content();
	}
	
	function render()
	{
		if( !empty($this->path) )
		{
			$text = $this->load->view(
							'widget/'.$this->path,
							array(
									'id'=> $this->id,
									'data'=>unserialize($this->info);
							),
							TRUE
			);
		}
		else
		{
			$text = 'Widget Not found';
		}
		
		return parent::render( $text );
	}
}
