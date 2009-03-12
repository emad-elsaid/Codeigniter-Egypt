<?php
define("SFB_PATH",			BASE_URL."jquery/sfbrowser/");// path of sfbrowser (relative to the page it is run from)

define("SFB_rPATH",			str_replace("index.php","",FCPATH)."jquery/sfbrowser/");
define("SFB_BASE",			"../../");		// upload folder (relative to sfbpath)

define("SFB_LANG",			"en");				// the language ISO code
define("PREVIEW_BYTES",		600);				// ASCII preview ammount
define("SFB_DENY",			"");	// forbidden file extensions

define("SFB_ERROR_RETURN",	"<html><head><meta http-equiv=\"Refresh\" content=\"0;URL=http:/\" /></head></html>");
?>
