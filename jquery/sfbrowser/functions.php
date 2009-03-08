<?php

function errorHandler($errno, $errstr, $errfile, $errline) {
	throw new Exception($errstr, $errno);
}

if (!function_exists("dump")) {
	function dump($s) {
		echo "<pre>";
		print_r($s);
		echo "</pre>";
	}
}

function trace($s) {
	$oFile = fopen("log.txt", "a");
	$sDump  = $s."\n";
	fputs ($oFile, $sDump );
	fclose($oFile);
}

function format_size($size, $round = 0) {
    $sizes = array('B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
    for ($i=0; $size > 1024 && isset($sizes[$i+1]); $i++) $size /= 1024;
    return round($size,$round).$sizes[$i];
}

function constantsToJs($a) {
	echo "\t\t<script type=\"text/javascript\">\n";
	foreach ($a as $s) {
		$oVal = @constant($s);
		$sPrefix = substr(gettype($oVal),0,1);
		$sIsString = $sPrefix=="s"?"\"":"";
		$sVal = 0;
		switch ($sPrefix) {
			case "s": $sVal = "\"".str_replace("\\","\\\\",$oVal)."\""; break;
			case "b": $sVal = $oVal?"true":"false"; break;
			case "d": $sPrefix = "f";
			default: $sVal = $oVal;
		}
		if ($sPrefix!="N") echo "\t\t\tvar ".$sPrefix.camelCase($s)." = ".$sVal.";\n";
		else  echo "\t\t\t// ".$s." could not be found or contains a null value.\n";
	}
	echo "\t\t</script>\n";
}

function camelCase($in) {
	$out = "";
	foreach(explode("_", $in) as $n => $chunk) $out .= ucfirst(strtolower($chunk));
	return $out;
}

function numToAZ($i) {
	$s = "";
	for ($j=0;$j<strlen((string)$i);$j++) $s .= chr((int)substr((string)$i, $j, 1)%26+97);
	return $s;
}

function strip_html_tags($text) {
	$text = preg_replace(
		array(
		  // Remove invisible content
			'@<head[^>]*?>.*?</head>@siu',
			'@<style[^>]*?>.*?</style>@siu',
			'@<script[^>]*?.*?</script>@siu',
			'@<object[^>]*?.*?</object>@siu',
			'@<embed[^>]*?.*?</embed>@siu',
			'@<applet[^>]*?.*?</applet>@siu',
			'@<noframes[^>]*?.*?</noframes>@siu',
			'@<noscript[^>]*?.*?</noscript>@siu',
			'@<noembed[^>]*?.*?</noembed>@siu',
		  // Add line breaks before and after blocks
			'@</?((address)|(blockquote)|(center)|(del))@iu',
			'@</?((div)|(h[1-9])|(ins)|(isindex)|(p)|(pre))@iu',
			'@</?((dir)|(dl)|(dt)|(dd)|(li)|(menu)|(ol)|(ul))@iu',
			'@</?((table)|(th)|(td)|(caption))@iu',
			'@</?((form)|(button)|(fieldset)|(legend)|(input))@iu',
			'@</?((label)|(select)|(optgroup)|(option)|(textarea))@iu',
			'@</?((frameset)|(frame)|(iframe))@iu',
		),
		array(
			' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ',
			"\n\$0", "\n\$0", "\n\$0", "\n\$0", "\n\$0", "\n\$0",
			"\n\$0", "\n\$0",
		),
		$text );
	return strip_tags($text);
}

function getUriContents($sUri) {
	$sExt = array_pop(explode(".", $sUri));

	if ($sExt=="pdf")	$sContents = pdf2txt($sUri);
	else				$sContents = file_get_contents($sUri);

//		$bMatch = preg_match("/(?<=\*{3}(.+)\*{3})(.*)(?=\*{3}(.+)\*{3})/m", $sContents, $aMatch);
//		$bMatch = preg_match("/(?<=\*{3}\sSTART\sOF\sTHIS\sPROJECT\sGUTENBERG\sEBOOK\s(.*)\s\*{3})(.*)(?=\*{3})/m", $sContents, $aMatch);
//
//		if ($bMatch) $sContents = $aMatch[0];
//		else $sContents = "grr".$sContents;
// *** START OF THIS PROJECT GUTENBERG EBOOK THE MAN WHO HATED MARS ***
// *** END OF THIS PROJECT GUTENBERG EBOOK THE MAN WHO HATED MARS ***

	$sContents = strip_html_tags($sContents);
	$sContents = preg_replace(
		array(
			"/(\r\n)|(\n|\r)/"
			,"/(\n){3,}/"
			,"/(?<=.)(\n)(?=.)/"
			,"/\|}/"
		), array(
			"\n"
			,"\n\n"
			," "
			,"!"
		), $sContents);

	return nl2br($sContents);
}

// Function    : pdf2txt()
// Arguments   : $filename - Filename of the PDF you want to extract
// Description : Reads a pdf file, extracts data streams, and manages
//               their translation to plain text - returning the plain
//               text at the end
// Authors      : Jonathan Beckett, 2005-05-02
//                            : Sven Schuberth, 2007-03-29

function pdf2txt($filename) {

    $data = getFileData($filename);
   
    $s=strpos($data,"%")+1;
   
    $version=substr($data,$s,strpos($data,"%",$s)-1);
    if(substr_count($version,"PDF-1.2")==0)
        return handleV3($data);
    else
        return handleV2($data);

   
}
// handles the verson 1.2
function handleV2($data){
       
    // grab objects and then grab their contents (chunks)
    $a_obj = getDataArray($data,"obj","endobj");
   
    foreach($a_obj as $obj){
       
        $a_filter = getDataArray($obj,"<<",">>");
   
        if (is_array($a_filter)){
            $j++;
            $a_chunks[$j]["filter"] = $a_filter[0];

            $a_data = getDataArray($obj,"stream\r\n","endstream");
            if (is_array($a_data)){
                $a_chunks[$j]["data"] = substr($a_data[0],
strlen("stream\r\n"),
strlen($a_data[0])-strlen("stream\r\n")-strlen("endstream"));
            }
        }
    }

    // decode the chunks
    foreach($a_chunks as $chunk){

        // look at each chunk and decide how to decode it - by looking at the contents of the filter
        $a_filter = split("/",$chunk["filter"]);
       
        if ($chunk["data"]!=""){
            // look at the filter to find out which encoding has been used           
            if (substr($chunk["filter"],"FlateDecode")!==false){
                $data =@ gzuncompress($chunk["data"]);
                if (trim($data)!=""){
                    $result_data .= ps2txt($data);
                } else {
               
                    //$result_data .= "x";
                }
            }
        }
    }
   
    return $result_data;
}

//handles versions >1.2
function handleV3($data){
    // grab objects and then grab their contents (chunks)
    $a_obj = getDataArray($data,"obj","endobj");
    $result_data="";
    foreach($a_obj as $obj){
        //check if it a string
        if(substr_count($obj,"/GS1")>0){
            //the strings are between ( and )
            preg_match_all("|\((.*?)\)|",$obj,$field,PREG_SET_ORDER);
            if(is_array($field))
                foreach($field as $data)
                    $result_data.=$data[1];
        }
    }
    return $result_data;
}

function ps2txt($ps_data){
    $result = "";
    $a_data = getDataArray($ps_data,"[","]");
    if (is_array($a_data)){
        foreach ($a_data as $ps_text){
            $a_text = getDataArray($ps_text,"(",")");
            if (is_array($a_text)){
                foreach ($a_text as $text){
                    $result .= substr($text,1,strlen($text)-2);
                }
            }
        }
    } else {
        // the data may just be in raw format (outside of [] tags)
        $a_text = getDataArray($ps_data,"(",")");
        if (is_array($a_text)){
            foreach ($a_text as $text){
                $result .= substr($text,1,strlen($text)-2);
            }
        }
    }
    return $result;
}

function getFileData($filename){
    $handle = fopen($filename,"rb");
    $data = fread($handle, filesize($filename));
    fclose($handle);
    return $data;
}

function getDataArray($data,$start_word,$end_word){

    $start = 0;
    $end = 0;
    unset($a_result);
   
    while ($start!==false && $end!==false){
        $start = strpos($data,$start_word,$end);
        if ($start!==false){
            $end = strpos($data,$end_word,$start);
            if ($end!==false){
                // data is between start and end
                $a_result[] = substr($data,$start,$end-$start+strlen($end_word));
            }
        }
    }
    return $a_result;
}