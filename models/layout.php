<?php
class Layout extends Content {
	var $has_many = array('content');
	
	function Layout(){
		parent::Content();
	}
	
	function cells(){
		$c = ($this->path!='' AND $this->path!=NULL)? $this->load->view($this->path,array('mode'=>'config'),TRUE) : 0;
		return intval($c);
	}
	
	function render(){
		if( $this->path == '' AND $this->path==NULL)
			return '';
		else{
			// the main render code;
			
			
			
		}
	}
	
	function delete( $object='' )
	{
		if( empty($object) )
		{
			foreach($this->content->get() as $item ){
				$temp = new ($item->type)();
				$temp->get_by_id( $item->id );
				$temp->delete();
			}
		}
		
		return parent::delete( $object );
	}
	
	/*function children($section_id,$parent,$cell){
		
		$cur_sec = new Section();
		$cur_sec->get_by_id($section_id);
		
		$sections = array();
		
		while( $cur_sec->exists() ){
			
			array_push($sections,$cur_sec);
			$cur_sec->get_by_id( $cur_sec->parent );
			
		}
		
		$children_objs = new Content();
		$children_objs->where(
		$sql_stat = sprintf("SELECT * FROM `content` WHERE `parent`='%s' AND `cell`='%s' AND ((`section`='%s')",$parent,$cell,$section);
		foreach($sections as $sss ){
			$sql_stat = $sql_stat . sprintf(" OR (`section`='%s' AND `childs`='0')",$sss);
		}
		$sql_stat = $sql_stat . sprintf(" ) ORDER BY `sort` ASC");
		$cont_child_var = db_sql($sql_stat);
		return $cont_child_var;
	}*/
}
