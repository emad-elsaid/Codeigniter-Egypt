<?php
$kfmDirectoryInstances=array();
class kfmDirectory extends kfmObject{
	var $subDirs=array();
	function kfmDirectory($id=1){
		parent::__construct();
		$this->id=$id;
		if(!$id)return;
		$res=db_fetch_row("SELECT * FROM ".KFM_DB_PREFIX."directories WHERE id=".$this->id);
		if(!$res)return $this->id=0;
		$this->name=$res['name'];
		$this->pid=$res['parent'];
		$this->path=$this->getPath();
	}
	function __construct($id=1){
		$this->kfmDirectory($id);
	}
	function addSubdirToDb($name){
		global $kfm;
		$sql="INSERT INTO ".KFM_DB_PREFIX."directories (name,parent) VALUES('".sql_escape($name)."',".$this->id.")";
		return $kfm->db->exec($sql);
	}
	function checkAddr($addr){
		return (
			strpos($addr,'..')===false&&
			strpos($addr,'.')!==0&&
			strpos($addr,'/.')===false);
	}
	function createSubdir($name){
		if(!$GLOBALS['kfm_allow_directory_create'])return $this->error(kfm_lang('permissionDeniedCreateDirectory'));
		$physical_address=$this->path.$name;
		$short_version=str_replace($GLOBALS['rootdir'],'',$physical_address);
		if(!$this->checkAddr($physical_address)){
			$this->error(kfm_lang('illegalDirectoryName',$short_version));
			return false;
		}
		if(file_exists($physical_address)){
			$this->error(kfm_lang('alreadyExists',$short_version));
			return false;
		}
		mkdir($physical_address);
		if(!file_exists($physical_address)){
			$this->error(kfm_lang('failedCreateDirectoryCheck',$name));
			return false;
		}
		return $this->addSubdirToDb($name);
	}
	function delete(){
		global $kfm;
		if(!$GLOBALS['kfm_allow_directory_delete'])return $this->error(kfm_lang('permissionDeniedDeleteDirectory'));
		$files=$this->getFiles();
		foreach($files as $f){
			if(!$f->delete())return false;
		}
		$subdirs=$this->getSubdirs();
		foreach($subdirs as $subdir){
			if(!$subdir->delete())return false;
		}
		rmdir($this->path);
		if(is_dir($this->path))return $this->error('failed to delete directory '.$this->path);
		$kfm->db->exec("delete from ".KFM_DB_PREFIX."directories where id=".$this->id);
		return true;
	}
	function getFiles(){
		$this->handle=opendir($this->path);
		if(!$this->handle)return $this->error('unable to open directory');
		$filesdb=db_fetch_all("select * from ".KFM_DB_PREFIX."files where directory=".$this->id);
		$fileshash=array();
		if(is_array($filesdb))foreach($filesdb as $r)$fileshash[$r['name']]=$r['id'];
		$files=array();
		while(false!==($filename=readdir($this->handle))){
			if(is_file($this->path.$filename)&&kfmFile::checkName($filename)){
				if(!isset($fileshash[$filename]))$fileshash[$filename]=kfmFile::addToDb($filename,$this->id);
				$file=kfmFile::getInstance($fileshash[$filename]);
				if(!$file)continue;
				if($file->isImage())$file=kfmImage::getInstance($fileshash[$filename]);
				$files[]=$file;
				unset($fileshash[$filename]);
			}
		}
		closedir($this->handle);
		return $files;
	}
	function getInstance($id=1){
		global $kfmDirectoryInstances;
		if(!isset($kfmDirectoryInstances[$id])){
			$dir=new kfmDirectory($id);
			if($dir->id==0)return false;
			$kfmDirectoryInstances[$id]=$dir;
		}
		return $kfmDirectoryInstances[$id];
	}
	function getPath(){
		$pathTmp=$this->name.'/';
		$pid=$this->pid;
		if(!$pid)return $GLOBALS['rootdir'];
		while($pid>1){
			$p=kfmDirectory::getInstance($pid);
			$pathTmp=$p->name.'/'.$pathTmp;
			$pid=$p->pid;
		}
		return $GLOBALS['rootdir'].$pathTmp;
	}
	function getProperties(){
		return array(
			'allowed_file_extensions' => '',
			'name'                    => $this->name,
			'path'                    => str_replace($_SERVER['DOCUMENT_ROOT'],'',$this->path),
			'parent'                  => $this->pid,
			'is_writable'             => $this->isWritable()
		);
	}
	function getSubdirs($oldstyle=false){
		global $kfm;
		$this->handle=opendir($this->path);
		$dirsdb=db_fetch_all("select id,name from ".KFM_DB_PREFIX."directories where parent=".$this->id);
		$dirshash=array();
		if(is_array($dirsdb))foreach($dirsdb as $r)$dirshash[$r['name']]=$r['id'];
		$directories=array();
		while(false!==($file=readdir($this->handle))){
			if(is_dir($this->path.$file)&&$this->checkName($file)){
				if(!isset($dirshash[$file])){
					$this->addSubdirToDb($file);
					$dirshash[$file]=$kfm->db->lastInsertId(KFM_DB_PREFIX.'directories','id');
				}
				$directories[]=kfmDirectory::getInstance($dirshash[$file]);
				unset($dirshash[$file]);
			}
		}
		closedir($this->handle);
		return $directories;
	}
	function hasSubdirs(){
		$this->handle=opendir($this->path);
		if($this->handle){
			while(false!==($file=readdir($this->handle))){
				if($this->checkName($file) && is_dir($this->path.$file)) return true;
			}
			closedir($this->handle);
			return false;
		}else{
			$this->error('Directory could not be opened');
		}
	}
	function getSubdir($dirname){
		$res=db_fetch_row('select id from '.KFM_DB_PREFIX.'directories where name="'.$dirname.'" and parent='.$this->id);
		if($res)return kfmDirectory::getInstance($res['id']);
		return false;
	}
	function isWritable(){
		return is_writable($this->path);	
	}
	function moveTo($newParent){
		global $kfm;
		if(is_numeric($newParent))$newParent=kfmDirectory::getInstance($newParent);
		{ # check for errors
			if(!$GLOBALS['kfm_allow_directory_move'])return $this->error(kfm_lang('permissionDeniedMoveDirectory'));
			if(strpos($newParent->path,$this->path)===0) return $this->error(kfm_lang('cannotMoveIntoSelf'));
			if(file_exists($newParent->path.$this->name))return $this->error(kfm_lang('alreadyExists',$newParent->path.$this->name));
			if(!$newParent->isWritable())return $this->error(kfm_lang('isNotWritable',$newParent->path));
		}
		{ # do the move and check that it was successful
			rename($this->path,$newParent->path.'/'.$this->name);
			if(!file_exists($newParent->path.$this->name))return $this->error(kfm_lang('couldNotMoveDirectory',$this->path,$newParent->path.$this->name));
		}
		{ # update database and kfmDirectory object
			$kfm->db->exec("update ".KFM_DB_PREFIX."directories set parent=".$newParent->id." where id=".$this->id) or die('error: '.print_r($kfmdb->errorInfo(),true));
			$this->pid=$newParent->id;
			$this->path=$this->getPath();
		}
	}
	function rename($newname){
		global $kfm,$kfm_allow_directory_edit,$kfmDirectoryInstances;
		if(!$GLOBALS['kfm_allow_directory_edit'])return $this->error(kfm_lang('permissionDeniedEditDirectory'));
		if(!$this->isWritable())return $this->error(kfm_lang('permissionDeniedRename',$this->name));
		if(!$this->checkAddr($newname))return $this->error(kfm_lang('cannotRenameFromTo',$this->name,$newname));
		$parent=kfmDirectory::getInstance($this->pid);
		if(file_exists($parent->path.$newname))return $this->error(kfm_lang('aDirectoryNamedAlreadyExists',$newname));
		rename($this->path,$parent->path.$newname);
		if(file_exists($this->path))return $this->error(kfm_lang('failedRenameDirectory'));
		$kfm->db->query("update ".KFM_DB_PREFIX."directories set name='".sql_escape($newname)."' where id=".$this->id);
		$this->name=$newname;
		$this->path=$this->getPath();
		$kfmDirectoryInstances[$this->id]=$this;
	}
	function checkName($file=false){
		if($file===false)$file=$this->name;
		if(trim($file)==''|| trim($file)!=$file)return false;
		if($file=='.'||$file=='..')return false;
		foreach($GLOBALS['kfm_banned_folders'] as $ban){
			if(($ban[0]=='/' || $ban[0]=='@')&&preg_match($ban,$file))return false;
			elseif($ban==strtolower(trim($file)))return false;
		}
		if(isset($GLOBALS['kfm_allowed_folders']) && is_array($GLOBALS['kfm_allowed_folders'])){
			foreach($GLOBALS['kfm_allowed_folders'] as $allow){
				if($allow[0]=='/' || $allow[0]=='@'){
					if(preg_match($allow, $file))return true;
				}else if($allow==strtolower($file)) return true;
			}
			return false;
		}
		return true;
	}
	function addFile($file){
		if(!$GLOBALS['kfm_allow_file_create'])return $this->error(kfm_lang('permissionDeniedCreateFile'));
		if(is_numeric($file))$file=kfmFile::getInstance($file);
		if(!$this->isWritable())return $this->error(kfm_lang('fileNotCreatedDirUnwritable',$file->name));
		copy($file->path,$this->path.'/'.$file->name);
		$id=$file->addToDb($file->name,$this->id);
		if($file->isImage()){
			$file=kfmImage::getInstance($file->id);
			$newFile=kfmImage::getInstance($id);
			$newFile->setCaption($file->caption);
		}
		else $newFile=kfmFile::getInstance($id);
		$newFile->setTags($file->getTags());
		return true;
	}
}
?>
