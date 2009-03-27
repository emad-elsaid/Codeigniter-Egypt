<?php
$sessionInstances=array();
class kfmSession extends kfmObject{
	var $vars;
	var $id;
	function kfmSession($key=''){
		global $kfm;
		parent::__construct();
		$create=1;
		if($key=='' && isset($_COOKIE['kfm_cookie']) && strlen($_COOKIE['kfm_cookie'])==32){
			$key=$_COOKIE['kfm_cookie'];
		}
		if($key!='' && strlen($key)==32){
			$res=db_fetch_row("SELECT id FROM ".KFM_DB_PREFIX."session WHERE cookie='".$key."'");
			if(is_array($res) && count($res)){
				$create=0;
				$this->id=$res['id'];
				$this->isNew=false;
				$kfm->db->query("UPDATE ".KFM_DB_PREFIX."session SET last_accessed='".date('Y-m-d G:i:s')."' WHERE id='".$this->id."'");
			}
		}
		if($create){
			$kfm->db->query("INSERT INTO ".KFM_DB_PREFIX."session (last_accessed) VALUES ('".date('Y-m-d G:i:s')."')");
			$this->id=$kfm->db->lastInsertId(KFM_DB_PREFIX.'session','id');
			$key=md5($this->id);
			$kfm->db->query("UPDATE ".KFM_DB_PREFIX."session SET cookie='".$key."' WHERE id=".$this->id);
			$this->isNew=true;
		}
		$this->key=$key;
		$this->vars=array();
		setcookie('kfm_cookie',$key,0,'/');
	}
	function __construct($key=''){
		$this->kfmSession($key);
	}
	function getInstance($id=0){
		if(!$id)return false;
		global $sessionInstances;
		if(!isset($sessionInstances[$id]))$sessionInstances[$id]=new kfmSession($id);
		return $sessionInstances[$id];
	}
	function set($name='',$value=''){
		global $kfm;
		if(isset($this->vars[$name])&&$this->vars[$name]==$value)return;
		$this->vars[$name]=$value;
		$kfm->db->query("DELETE FROM ".KFM_DB_PREFIX."session_vars WHERE session_id=".$this->id." and varname='".sql_escape($name)."'");
		$kfm->db->query("INSERT INTO ".KFM_DB_PREFIX."session_vars (session_id,varname,varvalue) VALUES (".$this->id.",'".sql_escape($name)."','".sql_escape(json_encode($value))."')");
	}
	function setMultiple($vars){
		foreach($vars as $key=>$val)$this->set($key,$val);
	}
	function get($name){
		if(isset($this->vars[$name]))return $this->vars[$name];
		$res=db_fetch_row("SELECT varvalue FROM ".KFM_DB_PREFIX."session_vars WHERE session_id=".$this->id." and varname='".sql_escape($name)."'");
		if(count($res)){
			$ret=json_decode('['.stripslashes($res['varvalue']).']',true);
			if(count($ret))$ret=$ret[0];
			else $ret='';
			$this->vars[$name]=$ret;
			return $ret;
		}
		return null;
	}
}
?>
