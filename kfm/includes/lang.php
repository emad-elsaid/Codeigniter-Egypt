<?php
function kfm_lang($str,$v1='',$v2='',$v3=''){
	global $kfm_language,$kfm_langStrings;
	if(!isset($kfm_langStrings)){
		include KFM_BASE_PATH.'lang/'.$kfm_language.'.php';
		$GLOBAL['kfm_langStrings']=$kfm_langStrings;
	}
	if(isset($kfm_langStrings[$str]))$str=$kfm_langStrings[$str];
	$i=1;
	while(strpos($str,'%'.$i)!==false)$str=str_replace('%'.$i,${'v'.$i++},$str);
	return utf8_encode($str);
}
