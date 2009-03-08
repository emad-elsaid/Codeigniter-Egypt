<?php
/*
* jQuery SFBrowser 2.5.2
* Copyright (c) 2008 Ron Valstar http://www.sjeiti.com/
* Dual licensed under the MIT and GPL licenses:
*   http://www.opensource.org/licenses/mit-license.php
*   http://www.gnu.org/licenses/gpl.html
*/
if (!(isset($_POST["a"])||isset($_GET["a"]))) exit("ku");
include("config.php");
include("functions.php");
include("lang/".SFB_LANG.".php");
foreach ($aLang as $c=>$s) @define($c,$s);
$sAction = isset($_POST["a"])?$_POST["a"]:$_GET["a"];
//
$sData = "";
$sErr = "";
$sMsg = "";//"pcnt:".count($_POST).",";
//
// chi / tsuchi: Earth
// sui / mizu: Water
// ho / ka / hi: Fire
// fu / kaze: Wind
// bar
// ku
// kung
//
// _GET : file
// _POST : a folder file allow resize
// _FILES : fileToUpload["name"]
//
// security file checking
$sSFile = "";
if (isset($_POST["file"])) $sSFile = $_POST["file"];
else if (isset($_GET["file"])) $sSFile = $_GET["file"];
else if (isset($_FILES["fileToUpload"])) $sSFile = $_FILES["fileToUpload"]["name"];
if ($sSFile!="") {
	if (isset($_POST["folder"])) $sSFile = $_POST["folder"].$sSFile;
	//if (strstr($sSFile,"sfbrowser")!==false||!preg_match('/[^:\*\?<>\|(\.\/)]+\/[^:\*\?<>\|(\.\/)]/',$sSFile)) exit(SFB_ERROR_RETURN);
	// todo: maybe check SFB_DENY here as well
}
//
//
function fileInfo($sFile) {
	$aRtr = array();
	$aRtr["type"] = filetype($sFile);
	$sFileName = array_pop(split("\/",$sFile));
	if ($aRtr["type"]=="file") {
		$aRtr["time"] = filemtime($sFile);
		$aRtr["date"] = date("j-n-Y H:i",$aRtr["time"]);
		$aRtr["size"] = filesize($sFile);
		$aRtr["mime"] = array_pop(split("\.",$sFile));//mime_content_type($sFile);
		//
		$aRtr["width"] = 0;
		$aRtr["height"] = 0;
		$aImgNfo = ($aRtr["mime"]=="jpeg"||$aRtr["mime"]=="jpg"||$aRtr["mime"]=="gif") ? getimagesize($sFile) : "";
		if (is_array($aImgNfo)) {
			list($width, $height, $type, $attr) = $aImgNfo;
			$aRtr["width"] = $width;
			$aRtr["height"] = $height;
		}
		$sNfo  = "file:\"".		$sFileName."\",";
		$sNfo .= "mime:\"".		$aRtr["mime"]."\",";
		$sNfo .= "rsize:".		$aRtr["size"].",";
		$sNfo .= "size:\"".		format_size($aRtr["size"])."\",";
		$sNfo .= "time:".		$aRtr["time"].",";
		$sNfo .= "date:\"".		$aRtr["date"]."\",";
		$sNfo .= "width:".		$aRtr["width"].",";
		$sNfo .= "height:".		$aRtr["height"];
		$aRtr["stringdata"] = $sNfo;
	} else if ($aRtr["type"]=="dir"&&$sFileName!="."&&$sFileName!=".."&&!preg_match("/^\./",$sFileName)) {
		$aRtr["mime"] = "folder";
		$aRtr["time"] = filemtime($sFile);
		$aRtr["date"] = date("j-n-Y H:i",$aRtr["time"]);
		$aRtr["size"] = filesize($sFile);
		$sNfo  = "file:\"".		$sFileName."\",";
		$sNfo .= "mime:\"".		"folder\",";
		$sNfo .= "rsize:".		"0,";
		$sNfo .= "size:\"".		"-\",";
		$sNfo .= "time:".		$aRtr["time"].",";
		$sNfo .= "date:\"".		$aRtr["date"]."\"";
		$aRtr["stringdata"] = $sNfo;
	}
	$aDeny = explode(",",SFB_DENY);
	if (!isset($aRtr["mime"])||in_array($aRtr["mime"],$aDeny)) return null;
	return $aRtr;
}
//
switch ($sAction) {

	case "chi": // retreive file list  $$$$$$todo: check SFB_DENY
		if (count($_POST)!=2||!isset($_POST["folder"])) exit("ku chi");
		$sImg = "";
		$sDir = isset($_POST["folder"])?$_POST["folder"]:"data/";
		$i = 0;
		if ($handle = opendir($sDir)) while (false !== ($file = readdir($handle))) {
			$oFNfo = fileInfo($sDir.$file);
			if ($oFNfo&&isset($oFNfo["stringdata"])) $sImg .= numToAZ($i).":{".$oFNfo["stringdata"]."},";
			$i++;
		}
		$sMsg .= "file listing";
		$sData = substr($sImg,0,strlen($sImg)-1);
	break;

	case "kung": // duplicate image
		$sCRegx = "/(?<=(_copy))([0-9])+(?=(\.))/";
		$sNRegx = "/(\.)(?=[A-Za-z0-9]+$)/";
		$oMtch = preg_match( $sCRegx, $sSFile, $aMatches);
		if (count($aMatches)>0)	$sNewFile = preg_replace($sCRegx,intval($aMatches[0])+1,$sSFile);
		else					$sNewFile = preg_replace($sNRegx,"_copy0.",$sSFile);
		$sMsg .= $sNewFile;
		if (copy($sSFile,$sNewFile)) {
			$oFNfo = fileInfo($sNewFile);
			$sData = $oFNfo["stringdata"];
			$sMsg .= LANG_FILE_DUPLICATED;
		} else {
			$sErr .= LANG_FILE_NOTDUPLICATED;
		}
	break;

	case "fu": // file upload
		//if (count($_POST)!=4||!isset($_POST["folder"])||!isset($_POST["resize"])||!isset($_POST["allow"])) exit("ku fu");
		$sElName = "fileToUpload";
		if (!empty($_FILES[$sElName]["error"])) {
			switch($_FILES[$sElName]["error"]) {
				case "1": $sErr = LANG_UPLOAD_ERR1; break;
				case "2": $sErr = LANG_UPLOAD_ERR2; break;
				case "3": $sErr = LANG_UPLOAD_ERR3; break;
				case "4": $sErr = LANG_UPLOAD_ERR4; break;
				case "6": $sErr = LANG_UPLOAD_ERR6; break;
				case "7": $sErr = LANG_UPLOAD_ERR7; break;
				case "8": $sErr = LANG_UPLOAD_ERR8; break;
				default:  $sErr = LANG_UPLOAD_ERR;
			}
		} else if (empty($_FILES["fileToUpload"]["tmp_name"])||$_FILES["fileToUpload"]["tmp_name"]=="none") {
			$sErr = "No file was uploaded..";
		} else {
			$sFolder = $_POST["folder"]; // $$ compare folder and base_uri (base uri deleted)
			$sMsg .= "sFolder_".$sFolder;
			$sPath = $sFolder;

			$sDeny = $_POST["deny"];
			$sAllow = $_POST["allow"];
			$sResize = $_POST["resize"];

			$oFile = $_FILES["fileToUpload"];
			$sFile = $oFile["name"];
			$sMime = array_pop(split("\.",$sFile));//mime_content_type($sDir.$file); //$oFile["type"]; //
			//
			$iRpt = 1;
			$sFileTo = $sPath.$oFile["name"];
			while (file_exists($sFileTo)) {
				$aFile = explode(".",$oFile["name"]);
				$aFile[0] .= "_".($iRpt++);
				$sFile = implode(".",$aFile);
				$sFileTo = $sPath.$sFile;
			}
			move_uploaded_file( $oFile["tmp_name"], $sFileTo );
			$oFNfo = fileInfo($sFileTo);

			$bAllow = $sAllow=="";
			$sFileExt = array_pop(explode(".",$sFile));
			if ($oFNfo) {
				if ($iRpt==1) $sMsg .= LANG_FILE_UPLOADED;
				else $sMsg .= LANG_FILE_EXISTSRENAMED;
				// check if file is allowed in this session $$$$$$todo: check SFB_DENY
				foreach (explode("|",$sAllow) as $sAllowExt) {
					if ($sAllowExt==$sFileExt) {
						$bAllow = true;
						break;
					}
				}
				foreach (explode("|",$sDeny) as $sDenyExt) {
					if ($sDenyExt==$sFileExt) {
						$bAllow = false;
						break;
					}
				}
			} else {
				$bAllow = false;
			}
			if (!$bAllow) {
				$sErr = str_replace(array("#1"),array($sFileExt),LANG_UPLOAD_NOTALLOWED);
				@unlink($sFileTo);
			} else {
				if ($sResize!="null"&&($sMime=="jpeg"||$sMime=="jpg")) {
					$aResize = explode(",",$sResize);
					$iToW = $aResize[0];
					$iToH = $aResize[1];
					list($iW,$iH) = getimagesize($sFileTo);
					$oImgN = imagecreatetruecolor($iToW,$iToH);
					$oImg = imagecreatefromjpeg($sFileTo);
					imagecopyresampled($oImgN,$oImg, 0,0, 0,0, $iToW,$iToH, $iW,$iH );
					imagejpeg($oImgN, $sFileTo);
				}
				$sData = $oFNfo["stringdata"];
			}
		}
	break;

	case "bar": // image resize
		$iToW = $_POST["w"];
		$iToH = $_POST["h"];
		list($iW,$iH) = getimagesize($sSFile);
		$oImgN = imagecreatetruecolor($iToW,$iToH);
		$oImg = imagecreatefromjpeg($sSFile);
		imagecopyresampled($oImgN,$oImg, 0,0, 0,0, $iToW,$iToH, $iW,$iH );
		if (imagejpeg($oImgN, $sSFile)) $sMsg .= LANG_IMG_RESIZED;
		else							$sERR .= LANG_IMG_NOTRESIZED;
	break;

	case "ka": // file delete
		if (count($_POST)!=3||!isset($_POST["folder"])||!isset($_POST["file"])) exit("ku ka");
		if (is_file($sSFile)) {
			if (@unlink($sSFile))	$sMsg .= LANG_FILE_DELETED;
			else					$sErr .= LANG_FILE_NOTDELETED;
		} else {
			if (@rmdir($sSFile))	$sMsg .= LANG_FOLDER_DELETED;
			else					$sErr .= LANG_FOLDER_NOTDELETED;
		}
	break;

	case "sui":// file force download
		if (count($_GET)!=2||!isset($_GET["file"])) exit("ku sui");
		ob_start();
		$sType = "application/octet-stream";
		header("Cache-Control: public, must-revalidate");
		header("Pragma: hack");
		header("Content-Type: " . $sSFile);
		header("Content-Length: " .(string)(filesize($sSFile)) );
		header('Content-Disposition: attachment; filename="'.array_pop(explode("/",$sSFile)).'"');
		header("Content-Transfer-Encoding: binary\n");
		ob_end_clean();
		readfile($sSFile);
	break;

	case "mizu":// read txt file contents
		$oHnd = fopen($sSFile, "r");
		$sCnt = preg_replace(array("/\n/","/\r/"),array("\\n","\\r"),addslashes(fread($oHnd, 600)));
		fclose($oHnd);
		$sData = "text:\"".$sCnt."\"";
		$sMsg .= LANG_CONTENTS_SUCCES;
	break;

	case "ho":// rename file
		if (isset($_POST["file"])&&isset($_POST["nfile"])) {
			$sFile = $_POST["file"];
			$sNFile = $_POST["nfile"];

			$sNSFile = str_replace($sFile,$sNFile,$sSFile);
			if (filetype($sSFile)=="file"&&array_pop(split("\.",$sFile))!=array_pop(split("\.",$sNFile))) {
				$sErr .= LANG_FILENAME_NOEXT;
			} else if (!preg_match("/^\w+(\.\w+)*$/",$sNFile)) {
				$sErr .= LANG_FILENAME_INVALID;
			} else {
				if ($sFile==$sNFile) {
					$sMsg .= LANG_FILENAME_NOCHANGE;
				} else {
					if ($sNFile=="") {
						$sErr .= LANG_FILENAME_NOTHING;
					} else {
						if (file_exists($sNSFile)) {
							$sErr .= LANG_FILENAME_EXISTS;
						} else {
							if (@rename($sSFile,$sNSFile)) $sMsg .= LANG_FILENAME_SUCCES;
							else $sErr .= LANG_FILENAME_FAILED;
						}
					}
				}
			}
		}
	break;

	case "tsuchi":// add folder
		if (isset($_POST["folder"]))  {
			$iRpt = 1;
			$sFolder = $_POST["folder"].LANG_NEWFOLDER;
			while (file_exists($sFolder)) $sFolder = $_POST["folder"].LANG_NEWFOLDER.($iRpt++);
			if (mkdir($sFolder)) {
				$sMsg .= LANG_FOLDER_CREATED;
				$oFNfo = fileInfo($sFolder);
				if ($oFNfo) $sData = $oFNfo["stringdata"];
				else $sErr .= LANG_FOLDER_FAILED;
			} else {
				$sErr .= LANG_FOLDER_FAILED;
			}
		}
	break;
}
$sEcho = "{error: \"".$sErr."\", msg: \"".$sMsg."\", data: {".$sData."}}";
//trace($sAction.": ".$sEcho);
if ($sAction!="sui") echo $sEcho;
