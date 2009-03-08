/*
* jQuery SFBrowser
*
* Version: 2.5.2
*
* Copyright (c) 2008 Ron Valstar http://www.sjeiti.com/
*
* Dual licensed under the MIT and GPL licenses:
*   http://www.opensource.org/licenses/mit-license.php
*   http://www.gnu.org/licenses/gpl.html
*
* description
*   - A file browsing and upload plugin. Returns a list of objects with additional information on the selected files.
*
* requires
*   - jQuery 1.2+
*   - PHP5
*
* features
*   - ajax file upload
*   - localisation (English, Dutch or Spanish)
*   - sortable file table
*   - file filtering
*   - file renameing
*   - file duplication
*   - file download
*   - file/folder context menu
*	- folder creation
*   - image resizing
*   - image preview
*   - text/ascii preview
*   - multiple files selection (not in IE for now)
*	- inline or overlay browsing
*
* how it works
*   - sfbrowser now returns a list of objects rather than a list of filenames.
*	  An object contains:
*		 - file(String):		The file including its path
*		 - mime(String):		The filetype
*		 - rsize(int):			The size in bytes
*		 - size(String):		The size formatted to B, kB, MB, GB etc..
*		 - time(int):			The time in seconds from Unix Epoch
*		 - date(String):		The time formatted in "j-n-Y H:i"
*		 - width(int):			If image, the width in px
*		 - height(int):			If image, the height in px
*
* aknowlegdments
*   - ajax file upload scripts from http://www.phpletter.com/Demo/AjaxFileUpload-Demo/
*	- Spanish translation: Juan Razeto
*
* todo:
*	- code: check what timeout code in upload code really does
*	- FF: find way to disable table cell highlighting view (just the border)
*	- IE: multiple selection does not work in IE (must be CTRL)
*	- add: image preview: no-scaling on smaller images
*	- add: multiple selection with shift (?)
*	- add: make text selection in table into multiple file selection
*   - new: make preview an option
*   - new: general filetype filter
*   - new: folder information such as number of files
*   - IE: fix IE and Safari scrolling (table header moves probably thanks to absolute positioning of parents)
*   - new: add mime instead of extension (for mac)
*	- add: show zip and rar file contents in preview
*	- add: drag and drop files to folders
*   - new: create ascii file
*   - new: edit ascii file
*   - maybe: drag sfbrowser
*   - maybe: copy used functions (copy, unique and indexof) from array.js
*	- maybe: thumbnail view
*
* in this update:
*	- fixed: json error that got IE stuck
*	- fixed: better return path
*
*/
;(function($) {
	// private variables
	var oSettings = {};
	var oContents = {};
	var aSort = [];
	var iSort = 0;
	var bHasImgs = false;
	var aPath = [];
	//
	var bOverlay = false;
	//
	var sFolder;
	var sReturnPath;
	//
	// resize
	var fRzsScale;
	var fRzsAspR;
	//
	// default settings
	$.sfbrowser = {
		 id: "SFBrowser"
		,version: "2.5.2"
		,defaults: {
			 title:		""						// the title
			,sfbpath:	"sfbrowser/"			// path of sfbrowser (relative to the page it is run from)
			,base:		"data/"					// upload folder (relative to sfbpath)
			,folder:	""						// subfolder (relative to base), all returned files are relative to base
			,dirs:		true					// allow visibility and creation/deletion of subdirectories
			,upload:	true					// allow upload of files
			,deny:		[]						// not allowed file extensions
			,allow:		[]						// allowed file extensions
			,resize:	null					// resize images: array(width,height) or null
			,select:	function(a){trace(a)}	// calback function on choose
			,inline:	"body"					// a JQuery selector for inline browser
			,fixed:		false					// keep the browser open after selection (only works when inline is not "body")
			// basic control (normally no need to change)
			,img:		["gif","jpg","jpeg","png"]
			,ascii:		["txt","xml","html","htm","eml","ffcmd","js","as","php","css","java","cpp","pl","log"]
		}
	};
	$.fn.extend({
		sfbrowser: function(_settings) {
			oSettings = $.extend({}, $.sfbrowser.defaults, _settings);
			oSettings.deny = oSettings.deny.concat(sSfbDeny.split(","));
			oSettings.php = oSettings.sfbpath+"sfbrowser.php";
			oContents = {};
			aSort = [];
			bHasImgs = oSettings.allow.length===0||oSettings.img.copy().concat(oSettings.allow).unique().length<(oSettings.allow.length+oSettings.img.length);
			aPath = [];
			sFolder = oSettings.base+oSettings.folder;
			//
			bOverlay = oSettings.inline=="body";
			if (bOverlay) oSettings.fixed = false;
			//
			// fix path and base to relative
			var aFxSfbpath =	oSettings.sfbpath.split("/");
			var aFxBase =		oSettings.base.split("/");
			var iFxLen = Math.min(aFxBase.length,aFxSfbpath.length);
			var iDel = 0;
			for (var i=0;i<iFxLen;i++) {
				var sFxFolder = aFxBase[i];
				if (sFxFolder==".."&&aFxSfbpath.length>0) {
					while (true) {
						var sRem = aFxSfbpath.pop();
						if (sRem!="") {
							iDel++;
							break;
						}
					}
				} else if (sFxFolder!="") {
					aFxBase = aFxBase.splice(iDel);
					break;
				}
			}
			sReturnPath = (aFxSfbpath.join("/")+"//"+aFxBase.join("/")).replace(/(\/+)/,"/").replace(/(^\/+)/,"");
			//
			// file browser
			var sFBrowser = "<div id=\"sfbrowser\"><div id=\"fbbg\"></div>";
			sFBrowser += "<div id=\"fbwin\">";
			sFBrowser += "	<div class=\"sfbheader\">";
			sFBrowser += "		<h3>"+(oSettings.title==""?sLangSfb:oSettings.title)+"</h3>";
			sFBrowser += "		<div id=\"loadbar\"><div></div><span>"+sLangLoading+"</span></div>";
			sFBrowser += "		<ul id=\"sfbtopmenu\">";
			if (oSettings.dirs) sFBrowser += "			<li><a class=\"textbutton newfolder\" title=\""+sLangNewfolder+"\"><span>"+sLangNewfolder+"</span></a></li>";
			if (oSettings.upload) {
				sFBrowser += "			<li>";
				sFBrowser += "				<form id=\"fileio\" name=\"form\" action=\"\" method=\"POST\" enctype=\"multipart/form-data\">";
				sFBrowser += "					<input id=\"fileToUpload\" type=\"file\" size=\"1\" name=\"fileToUpload\" class=\"input\" />";
				sFBrowser += "				</form>";
				sFBrowser += "				<a class=\"textbutton upload\" title=\""+sLangUpload+"\"><span>"+sLangUpload+"</span></a>";
				sFBrowser += "			</li>";
			}
			if (!oSettings.fixed) sFBrowser += "			<li><a class=\"button cancelfb\" title=\""+sLangCancel+"\">&nbsp;<span>"+sLangCancel+"</span></a></li>";
			sFBrowser += "		</ul>";
			sFBrowser += "	</div>";
			sFBrowser += "	<div class=\"fbcontent\">";
			sFBrowser += "		<div id=\"fbtable\"><table id=\"filesDetails\" cellpadding=\"0\" cellspacing=\"0\"><thead><tr>";
			sFBrowser += "			<th>"+sLangName+"</th>";
			sFBrowser += "			<th>"+sLangType+"</th>";
			sFBrowser += "			<th>"+sLangSize+"</th>";
			sFBrowser += "			<th>"+sLangDate+"</th>";
			if (bHasImgs) sFBrowser += "		<th>"+sLangDimensions+"</th>";
			sFBrowser += "			<th width=\"54\"></th>";
			sFBrowser += "		</tr></thead><tbody><tr><td class=\"loading\" colspan=\""+(bHasImgs?6:5)+"\"></td></tr></tbody></table></div>";
			sFBrowser += "		<div id=\"fbpreview\"></div>";
			sFBrowser += "		<div class=\"button choose\">"+sLangChoose+"</div>";
			if (!oSettings.fixed) sFBrowser += "		<div class=\"button cancelfb\">"+sLangCancel+"</div>";
			sFBrowser += "		<div id=\"sfbfooter\">SFBrowser "+$.sfbrowser.version+" Copyright (c) 2008 <a href=\"http://www.sjeiti.com/\">Ron Valstar</a></div>";
			sFBrowser += "	</div>";
			sFBrowser += "</div>";
			// image resizer
			sFBrowser += "<div id=\"sfbimgresize\">";
			sFBrowser += "	<div class=\"sfbheader\">";
			sFBrowser += "		<h3>"+(oSettings.title==""?sLangSfb:oSettings.title)+": "+sLangImgResize+"</h3>";
			sFBrowser += "	</div>";
			sFBrowser += "	<div class=\"fbcontent\">";
			sFBrowser += "		"+sLangScale+": <span id=\"rszperc\">asdf</span><br/>";
			sFBrowser += "		<div id=\"sfbrsimg\">";
			sFBrowser += "			<img src=\"\" />";
			sFBrowser += "			<div id=\"crop\"></div>";
			sFBrowser += "			<div id=\"sfbButRszBut\" title=\""+sLangDragMe+"\"></div>";
			sFBrowser += "		</div>";
			sFBrowser += "		<form id=\"sfbsize\">";
			sFBrowser += "			<h4>"+sLangResize+":</h4>";
			sFBrowser += "			<label for=\"rszW\">"+sLangWidth+"</label>: <input type=\"text\" name=\"rszW\" /> px<br/>";
			sFBrowser += "			<label for=\"rszH\">"+sLangHeight+"</label>: <input type=\"text\" name=\"rszH\" /> px<br/>";
			//sFBrowser += "			Crop:<br/>";
			//sFBrowser += "			x: <input type=\"text\" name=\"crpX\" /> px<br/>";
			//sFBrowser += "			y: <input type=\"text\" name=\"crpY\" /> px<br/>";
			//sFBrowser += "			w: <input type=\"text\" name=\"crpW\" /> px<br/>";
			//sFBrowser += "			h: <input type=\"text\" name=\"crpH\" /> px<br/>";
			//sFBrowser += "			<br/>Result:<br/>";
			//sFBrowser += "			w: <span id=\"rsW\"></span> px<br/>";
			//sFBrowser += "			h: <span id=\"rsH\"></span> px";
			sFBrowser += "		</form>";
			sFBrowser += "		<div class=\"button resize\">"+sLangResize+"</div>";
			sFBrowser += "		<div class=\"button cancelresize\">"+sLangCancel+"</div>";
			sFBrowser += "	</div>";
			sFBrowser += "</div>";
			sFBrowser += "</div>";

			$("#sfbrowser").remove();
			mFB = $(sFBrowser).appendTo(oSettings.inline);
			if (!bOverlay) {
				trace("sfb inline");
				mFB.css(					{position:"relative",width:"auto",heigth:"auto"});
				mFB.find("#fbbg").remove();
				mFB.find("#fbwin").css(		{position:"relative"});
				mFB.find("#sfbimgresize").css(		{position:"absolute",top:"0px",left:"0px",height:mFB.find("#fbwin").height()+"px"});
			}

			// context menu
			var sFBcontext = "<ul id=\"sfbcontext\">";
			sFBcontext += "<li><a onclick=\"\" class=\"textbutton choose\" title=\""+sLangChoose+"\"><span>"+sLangChoose+"</span></a></li>";
			sFBcontext += "<li><a onclick=\"\" class=\"textbutton rename\" title=\""+sLangRename+"\"><span>"+sLangRename+"</span></a></li>";
			sFBcontext += "<li><a onclick=\"\" class=\"textbutton duplicate\" title=\""+sLangDuplicate+"\"><span>"+sLangDuplicate+"</span></a></li>";
			sFBcontext += "<li><a onclick=\"\" class=\"textbutton resize\" title=\""+sLangImgResize+"\"><span>"+sLangImgResize+"</span></a></li>";
			sFBcontext += "<li><a onclick=\"\" class=\"textbutton preview\" title=\""+sLangView+"\"><span>"+sLangView+"</span></a></li>";
			sFBcontext += "<li><a onclick=\"\" class=\"textbutton filedelete\" title=\""+sLangDelete+"\"><span>"+sLangDelete+"</span></a></li>";
			sFBcontext += "</ul>";
			var mFBC = $(sFBcontext).appendTo("#sfbrowser");
			mFBC.find("a.choose").click(function(){		chooseFile(); });
			mFBC.find("a.rename").click(function(){		renameSelected(); });
			mFBC.find("a.duplicate").click(function(){	duplicateFile(); });
			mFBC.find("a.resize").click(function(){		resizeImage(); });
			mFBC.find("a.preview").click(function(){		$("#sfbrowser tbody>tr.selected:first a.preview").trigger("click"); });
			mFBC.find("a.filedelete").click(function(){	$("#sfbrowser tbody>tr.selected:first a.filedelete").trigger("click"); });
			mFBC.find("a").click(function(){			$("#sfbcontext").slideUp("fast"); });

			// functions
			if (bOverlay) $(window).bind("resize", reposition);
			// top menu
			mFB.find(".cancelfb").click(		closeSFB );
			mFB.find("#fileToUpload").change(	fileUpload);
			mFB.find(".newfolder").click(		addFolder );
			// table
			mFB.find("div.choose").click(		chooseFile);
			mFB.find("thead>tr>th:not(:last)").each(function(i,o){
				$(this).click(function(){sortFbTable(i)});
			}).append("<span>&nbsp;</span>");
			// context menu
			mFB.click(function(){
				$("#sfbcontext").slideUp("fast");
			});
			// resize menu
			mFB.find("div.cancelresize").click(function(){$("#sfbimgresize").hide();});
			mFB.find("div.resize").click(resizeSend);
			mFB.find("#sfbButRszBut").mousedown( function(){
				$("body").mousemove(resizeMove);
				return false;
			});
			$("body").mouseup(function(){
				$("body").unbind("mousemove",resizeMove);
			});
			$("form#sfbsize>input[name=rszW]").change(function(){
				var w = $(this).val();
				resizeDo(fRzsScale*w,fRzsScale*(w/fRzsAspR));
			});
			$("form#sfbsize>input[name=rszH]").change(function(){
				var h = $(this).val();
				resizeDo(fRzsScale*fRzsAspR*h,fRzsScale*h);
			});


			// start
			openDir(sFolder);

			// keys
			// ESC : 27
			// (F1 : xxx : help)				#impossible: F1 browser help
			// F2 : 113 : rename
			// F4 : 115 : edit					#unimplemented
			// (F5 : xxx : copy)				#impossible: F5 reloads
			// (F6 : xxx : move)				#no key in SFB
			// (F7 : xxx : create directory)	#no key in SFB
			// F8 : 119	: delete				#unimplemented
			// F9 : 120	: properties			#unimplemented
			// (F10 : xxx : quit)				#no key in SFB
			// CTRL-A : xxx : select all
			oSettings.keys = [];
			$(window).keydown(function(e){
				oSettings.keys[e.keyCode] = true;
				//trace("key: "+e.keyCode+" ")
				if (e.keyCode==65&&oSettings.keys[17]) {
					$("#sfbrowser tbody>tr").each(function(){$(this).addClass("selected")});
					return false;
				}
			});
			$(window).keyup(function(e){
				//trace("key: "+e.keyCode+" ")
				if (oSettings.keys[113])	renameSelected();
				if (oSettings.keys[27])		closeSFB();
				oSettings.keys[e.keyCode] = false;
				return false;
			});
			
			if (bOverlay) reposition();
			
			openSFB();
		}
	});
	// init
	$(function() {
		trace("SFBrowser init");
	});
	// private functions
	//
	// open
	function openSFB() {
		trace("sfb open");
		// animation
		mFB.find("#fbbg").css({display:"none"});
		mFB.find("#fbbg").slideDown();
		mFB.find("#fbwin").css({display:"none"});
		mFB.find("#fbwin").slideDown();
	}
	//
	// close
	function closeSFB() {
		trace("sfb close");
		if (bOverlay&&!oSettings.fixed) {
			$("#sfbrowser #fbbg").fadeOut();
			$("#sfbrowser #fbwin").slideUp("normal",function(){$("#sfbrowser").remove();});
		}
	}
	// reposition
	function reposition() {
		var fFbX = Math.round($(window).height()/2-$("#fbwin").height()/2);
		var fFbY = Math.round($(window).width()/2-$("#fbwin").width()/2);
		$("#fbwin").css({
			 top:  fFbX
			,left: fFbY
		});
		$("#sfbimgresize").css({
			 height: $("#fbwin").height()
			,top:  fFbX
			,left: fFbY
		});
		$("#sfbcontext").slideUp("fast");
	}
	// sortFbTable
	function sortFbTable(nr) {
		if (nr!==null) {
			iSort = nr;
			aSort[iSort] = aSort[iSort]=="asc"?"desc":"asc";
		} else {
			if (!aSort[iSort]) aSort[iSort] = "asc";
		}

		//$("#sfbrowser tbody>tr").tsort("td:eq("+nr+")[abbr]",{attr:"abbr",order:aSort[nr]});
		$("#sfbrowser tbody>tr.folder").tsort("td:eq(0)[abbr]",{attr:"abbr",order:aSort[iSort]});
		$("#sfbrowser tbody>tr:not(.folder)").tsort("td:eq("+iSort+")[abbr]",{attr:"abbr",order:aSort[iSort]});

		mFB.find("thead>tr>th>span").each(function(i,o){$(this).css({backgroundPosition:(i==iSort?5:-9)+"px "+(aSort[iSort]=="asc"?4:-96)+"px"})});
	}
	// open directory
	function openDir(dir) {
		trace("sfb openDir "+dir+" to "+oSettings.php);
		if (dir) aPath.push(dir);
		else aPath.pop();
		$.ajax({type:"POST", url:oSettings.php, data:"a=chi&folder="+aPath.join(""), dataType:"json", success:fillList});
	}
	// fill list
	function fillList(data,status) {
		trace("sfb fillList");
		if (typeof(data.error)!="undefined") {
			if (data.error!="") {
				trace("sfb error: "+data.error);
				alert(data.error);
			} else {
				trace(data.msg);
				$("#sfbrowser tbody").children().remove();
				$("#fbpreview").html("");
				oContents = {};
				aSort = [];
				$.each( data.data, function(i,oFile) {
					// todo: logical operators could be better
					var bDir = (oFile.mime=="folder"||oFile.mime=="folderup");
					if ((oSettings.allow.indexOf(oFile.mime)!=-1||oSettings.allow.length===0)&&oSettings.deny.indexOf(oFile.mime)==-1||bDir) {
						if ((bDir&&oSettings.dirs)||!bDir) listAdd(oFile);
					}
				});
				if (aPath.length>1) listAdd({file:"..",mime:"folderup",rsize:0,size:"-",time:0,date:""});

				$("#sfbrowser thead>tr>th:eq(0)").trigger("click");
			}
		}
	}
	// add item to list
	function listAdd(obj) {;
		oContents[obj.file] = obj;
		var bFolder = obj.mime=="folder";
		var bUFolder = obj.mime=="folderup";
		var sMime = bFolder||bUFolder?sLangFolder:obj.mime;
		var sTr = "<tr id=\""+obj.file+"\" class=\""+(bFolder||bUFolder?"folder":"file")+"\">";
		sTr += "<td abbr=\""+obj.file+"\" title=\""+obj.file+"\" class=\"icon\" style=\"background-image:url("+oSettings.sfbpath+"icons/"+(aIcons.indexOf(obj.mime)!=-1?obj.mime:"default")+".gif);\">"+(obj.file.length>20?obj.file.substr(0,15)+"(...)":obj.file)+"</td>";
		sTr += "<td abbr=\""+obj.mime+"\">"+sMime+"</td>";
		sTr += "<td abbr=\""+obj.rsize+"\">"+obj.size+"</td>";
		sTr += "<td abbr=\""+obj.time+"\">"+obj.date+"</td>";
		var bVImg = (obj.width*obj.height)>0;
		sTr += (bHasImgs?("<td"+(bVImg?(" abbr=\""+(obj.width*obj.height)+"\""):"")+">"+(bVImg?(obj.width+" x "+obj.height+" px"):"")+"</td>"):"");
		sTr += "<td>";
		if (!(bFolder||bUFolder)) sTr += "	<a onclick=\"\" class=\"button preview\" title=\""+sLangView+"\">&nbsp;<span>"+sLangView+"</span></a>";
		if (!bUFolder) sTr += "	<a onclick=\"\" class=\"button filedelete\" title=\""+sLangDelete+"\">&nbsp;<span>"+sLangDelete+"</span></a>";
		sTr += "</td>";
		sTr += "</tr>";
		var mTr = $(sTr).prependTo("#sfbrowser tbody");
		mTr.find("a.filedelete").bind("click", function(el) {
			if (confirm(bFolder?sLangConfirmDeletef:sLangConfirmDelete)) {
				$.ajax({type:"POST", url:oSettings.php, data:"a=ka&folder="+aPath.join("")+"&file="+obj.file, dataType:"json", success:function(data, status){
					if (typeof(data.error)!="undefined") {
						if (data.error!="") {
							trace(data.error);
							alert(data.error);
						} else {
							trace(data.msg);
							$("#fbpreview").html("");
							delete oContents[obj.file];
							mTr.remove();
						}
					}
				}});
			}
			return false; // to prevent renaming
		});
		//mTr.find("td:last").css({textAlign:"right"}); // IE fix
		mTr.bind("mouseover", function() {
			$(this).addClass("over");
		}).bind("mouseout", function() {
			$(this).removeClass("over");
		}).bind("dblclick", function() {
			chooseFile($(this));
			return false;
		}).click( function(e) {
			clickTr(this,false);
			return false;
		}).mousedown( function(e) {
			var evt = e;
			$(this).mouseup( function(e) {
				$(this).unbind("mouseup");
				if (evt.button==2) {
					//trace("rightclick todo: create context menu: choose, rename, download, delete");
					$("#sfbcontext").slideUp("fast",function(){
						$("#sfbcontext").css({left:e.clientX+1,top:e.clientY+1});
						// check context contents
						$("#sfbcontext").children().css({display:"block"});
						if (bFolder||bUFolder) {
							$("#sfbcontext>li:has(a.preview)").css({display:"none"});
							$("#sfbcontext>li:has(a.duplicate)").css({display:"none"});
						}
						if (bUFolder) {
							$("#sfbcontext>li:has(a.rename)").css({display:"none"});
							$("#sfbcontext>li:has(a.filedelete)").css({display:"none"});
						}
						if (!obj.width||!obj.height) $("#sfbcontext>li:has(a.resize)").css({display:"none"});
						//
						$("#sfbcontext").slideDown("fast")
					});;
					clickTr(this,true);
					return false;
				} else {
					return true;
				}
			});
		});
		mTr[0].oncontextmenu = function() {
			return false;
		};
		mTr.find("a.preview").bind("click", function(el) {
			window.open(oSettings.php+"?a=sui&file="+aPath.join("")+obj.file,"_blank");
		});
		return mTr;
	}
	// chooseFile
	function chooseFile(el) {
		var a = 0;
		var aSelected = $("#sfbrowser tbody>tr.selected");
		var aSelect = [];
		// find selected trs and possible parsed element
		aSelected.each(function(){aSelect.push(oContents[$(this).attr("id")])});
		if (el&&el.find) aSelect.push(oContents[$(el).attr("id")]);
		// check if selection contains directory
		for (var i=0;i<aSelect.length;i++) {
			var oFile = aSelect[i];
			if (oFile.mime=="folder") {
				openDir(oFile.file+"/");
				return false;
			} else if (oFile.mime=="folderup") {
				openDir();
				return false;
			}
		}
		aSelect = aSelect.unique();
		// return clones, not the objects
		for (var i=0;i<aSelect.length;i++) {
			var oFile = aSelect[i];
			var oDupl = new Object();
			for (var p in oFile) oDupl[p] = oFile[p];
			aSelect[i] = oDupl;
		}
		// return
		if (aSelect.length==0) {
			alert(sLangFileNotselected);
		} else {
			// correct path
			$.each(aSelect,function(i,oFile){oFile.file = sReturnPath+aPath.join("").replace(oSettings.base,"")+oFile.file;});
			oSettings.select(aSelect);
			closeSFB();
		}
	}
	// clickTr
	function clickTr(el,right) {
		var mTr = $(el);
		var oFile = oContents[mTr.attr("id")];
		var bFolder = oFile.mime=="folder";
		var bUFolder = oFile.mime=="folderup";
		var sFile = oFile.file;
		//
		//if (!oSettings.keys[16]) trace("todo: shift selection");
		if (!oSettings.keys[17]) $("#sfbrowser tbody>tr").each(function(){if (mTr[0]!=$(this)[0]) $(this).removeClass("selected")});
		//
		// check if something is being renamed
		if (checkRename()[0]!=mTr[0]&&!right&&mTr.hasClass("selected")&&!bUFolder&&!oSettings.keys[17]) {
			renameSelected(mTr);
		} else {
			if (oSettings.keys[17]&&!right) mTr.toggleClass("selected");
			else mTr.addClass("selected");
		}
		//
		$("#fbpreview").html("");
		//
		// preview image
		if (oSettings.img.indexOf(oFile.mime)!=-1) {
			var sFuri = oSettings.sfbpath+aPath.join("")+sFile; // $$ cleanup img path
			$("<img src=\""+sFuri+"\" />").appendTo("#fbpreview").click(function(){$(this).parent().toggleClass("auto")});
		//
		// preview ascii
		} else if (oSettings.ascii.indexOf(oFile.mime)!=-1) {
			$("#fbpreview").html(sLangPreviewText);
			$.ajax({type:"POST", url:oSettings.php, data:"a=mizu&folder="+aPath.join("")+"&file="+sFile, dataType:"json", success:function(data, status){
					if (typeof(data.error)!="undefined") {
					if (data.error!="") {
						trace("sfb error: "+data.error);
						alert(data.error);
					} else {
						trace(data.msg);
						$("#fbpreview").html("<pre><div>"+sLangPreviewPart.replace("#1",iPreviewBytes)+"</div>\n"+data.data.text.replace(/\>/g,"&gt;").replace(/\</g,"&lt;")+"</pre>");
					}
				}
			}});
		}
		return false;
	}
	// duplicate file
	function duplicateFile(el) {
		var mSelected = el?el:$("#sfbrowser tbody>tr.selected:first");
		var mStd = mSelected.find("td:eq(0)");
		var oFile = oContents[mSelected.attr("id")];
		var sFile = oFile.file;
		//
		trace("sfb Sending duplication request...");
		$.ajax({type:"POST", url:oSettings.php, data:"a=kung&folder="+aPath.join("")+"&file="+sFile, dataType:"json", success:function(data, status){
			if (typeof(data.error)!="undefined") {
				if (data.error!="") {
					trace(data.error);
					alert(data.error);
				} else {
					trace(data.msg);
					listAdd(data.data).trigger('click');
				}
			}
		}});
	}
	// resize Image
	function resizeImage(el) {
		var mSelected = el?el:$("#sfbrowser tbody>tr.selected:first");
		var mStd = mSelected.find("td:eq(0)");
		var oFile = oContents[mSelected.attr("id")];
		var sFile = oFile.file;
		//
		var iRW = oFile.width;
		var iRH = oFile.height;
		var iMaxW = 400;
		var iMaxH = 360;
		var fScaleW = iMaxW/oFile.width;
		var fScaleH = iMaxH/oFile.height;
		fRzsScale = Math.min(1,Math.min(fScaleW,fScaleH));
		fRzsAspR = iRW/iRH;
		var iSW = fRzsScale*iRW;
		var iSH = fRzsScale*iRH;
		//
		// set image sizes
		var sFuri = oSettings.sfbpath+aPath.join("")+sFile; // $$ cleanup img path
		var mImg = $("#sfbimgresize>div.fbcontent>div#sfbrsimg>img");
		mImg.attr("src",sFuri);
		mImg.width(iSW+"px");
		mImg.height(iSH+"px");
		var mCrop = $("#sfbimgresize>div.fbcontent>div#sfbrsimg>div#crop");
		mCrop.width(iSW+"px");
		mCrop.height(iSH+"px");
		$("#rszperc").text(" "+Math.round(100*fRzsScale)+"%");
		$("div#sfbButRszBut").css({left:(iSW-6)+"px",top:(iSH-6)+"px"});
		//
		// set form
		$("form#sfbsize>input[name=rszW]").val(iRW);
		$("form#sfbsize>input[name=rszH]").val(iRH);
		//$("form#sfbsize>input[name=crpX]").val(0);
		//$("form#sfbsize>input[name=crpY]").val(0);
		//$("form#sfbsize>input[name=crpW]").val(iRW);
		//$("form#sfbsize>input[name=crpH]").val(iRH);
		//$("form#sfbsize>span#rsW").text(iRW);
		//$("form#sfbsize>span#rsH").text(iRH);
		//$("#sfbimgresize>div.fbcontent").append(sLangResize+" "+fScaleW+" "+fScaleW);
		//
		// show resize window
		$("#sfbimgresize").show();
	}
	function resizeMove(e) {
		var mElm = $("#sfbButRszBut");
		var mPrn = mElm.parent();
		var iXps = e.pageX-mPrn.offset().left;
		var iYps = e.pageY-mPrn.offset().top;
		resizeDo(iXps,iYps);
	}
	function resizeDo(w,h) {
		var mSelected = $("#sfbrowser tbody>tr.selected:first");
		var oFile = oContents[mSelected.attr("id")];
		var sFile = oFile.file;
		//
		w = Math.max(1,Math.min(w,fRzsScale*oFile.width));
		h = Math.max(1,Math.min(h,fRzsScale*oFile.height));
		//
		if (w/h>fRzsAspR)	h = w/fRzsAspR;
		else				w = h*fRzsAspR;
		//
		var mElm = $("#sfbButRszBut");
		mElm.css({left:(w-6)+"px",top:(h-6)+"px"});
		var mImg = $("#sfbimgresize>div.fbcontent>div#sfbrsimg>img");
		mImg.width(w+"px");
		mImg.height(h+"px");
		//
		var iRW = Math.round(w/fRzsScale);
		var iRH = Math.round(h/fRzsScale);
		$("form#sfbsize>input[name=rszW]").val(iRW);
		$("form#sfbsize>input[name=rszH]").val(iRH);
	}
	function resizeSend() {
		trace("sfb resizeSend");
		var mSelected = $("#sfbrowser tbody>tr.selected:first");
		var oFile = oContents[mSelected.attr("id")];
		var sFile = oFile.file;
		var iW = $("form#sfbsize>input[name=rszW]").val();
		var iH = $("form#sfbsize>input[name=rszH]").val();

		if (iW==oFile.width&&iH==oFile.height) {
			trace("sfb Will not resize to same size."); // $$ alert something?
		} else {
			trace("sfb Sending resize request...");
			$.ajax({type:"POST", url:oSettings.php, data:"a=bar&folder="+aPath.join("")+"&file="+sFile+"&w="+iW+"&h="+iH, dataType:"json", success:function(data, status){
				if (typeof(data.error)!="undefined") {
					if (data.error!="") {
						trace(data.error);
						alert(data.error);
					} else {
						oFile.width  = iW;
						oFile.height = iH;
						mSelected.find("td:eq(4)").attr("abbr",iW*iH).text(iW+" x "+iH+" px");
						//
						$("#sfbimgresize").hide();
					}
				}
			}});
		}
	}
	// rename
	function renameSelected(el) {
		var mSelected = el?el:$("#sfbrowser tbody>tr.selected:first");
		var mStd = mSelected.find("td:eq(0)");
		var oFile = oContents[mSelected.attr("id")];
		mStd.html("");
		$("<input type=\"text\" value=\""+oFile.file+"\" />").appendTo(mStd).click(function(){return false;});
	}
	function checkRename() {
		var aRenamed = $("#sfbrowser tbody>tr>td>input");
		if (aRenamed.length>0) {
			var mInput = $(aRenamed[0]);
			var mTd = mInput.parent();
			var mTr = mTd.parent();
			var oFile = oContents[mTr.attr("id")];
			var sFile = oFile.file;
			var sNFile = mInput.val();

			if (sFile==sNFile) {
				mInput.parent().html(sFile.length>20?sFile.substr(0,15)+"(...)":sFile);
			} else {
				$.ajax({type:"POST", url:oSettings.php, data:"a=ho&folder="+aPath.join("")+"&file="+sFile+"&nfile="+sNFile, dataType:"json", success:function(data, status){
					if (typeof(data.error)!="undefined") {
						if (data.error!="") {
							trace(data.error);
							alert(data.error);
						} else {
							trace(data.msg);
							mTd.html(sNFile.length>20?sNFile.substr(0,15)+"(...)":sNFile).attr("title",sNFile).attr("abbr",sNFile);
							oFile.file = sNFile;
						}
					}
				}});
			}
		}
		return mTr?mTr:false;
	}
	// add folder
	function addFolder() {
		trace("sfb addFolder");
		$.ajax({type:"POST", url:oSettings.php, data:"a=tsuchi&folder="+aPath.join(""), dataType:"json", success:function(data, status){
			if (typeof(data.error)!="undefined") {
				if (data.error!="") {
					trace(data.error);
					alert(data.error);
				} else {
					trace(data.msg);
					listAdd(data.data).trigger('click').trigger('click');
					sortFbTable(); // todo: fix scrolltop below because because of
					$("#sfbrowser #fbtable").scrollTop(0);	// IE and Safari
					$("#sfbrowser tbody").scrollTop(0);		// Firefox
				}
			}
		}});
	}
	// loading
	function loading() {
		var iPrgMove = Math.ceil((new Date()).getTime()*.3)%512;
		$("#loadbar>div").css("backgroundPosition", "0px "+iPrgMove+"px");
		$("#loadbar:visible").each(function(){setTimeout(loading,20);});
	}
	// fileUpload
	function fileUpload() {
		trace("sfb fileUpload");
		
		$("#loadbar").ajaxStart(function(){
			$(this).show();
			loading();
		}).ajaxComplete(function(){
			$(this).hide();
		});

		ajaxFileUpload({ // fu
			url:			oSettings.php,
			secureuri:		false,
			fileElementId:	"fileToUpload",
			dataType:		"json",
			success: function (data, status) {
				if (typeof(data.error)!="undefined") {
					if (data.error!="") {
						trace("sfb error: "+data.error);
						alert(data.error);
					} else {
						trace(data.msg);
						listAdd(data.data).trigger('click');
						sortFbTable(); // todo: fix scrolltop below because because of
						$("#sfbrowser #fbtable").scrollTop(0);	// IE and Safari
						$("#sfbrowser tbody").scrollTop(0);		// Firefox
					}
					return false; // otherwise upload stays open...
				}
			},
			error: function (data, status, e){
				trace(e);
			}
		});
		return false;
	}
	// is numeric
	function isNum(n) {
		return (parseFloat(n)+"")==n;
	}
	// trace
	function trace(o) {
		if (window.console&&window.console.log) {
			if (typeof(o)=="string")	window.console.log(o);
			else						for (var prop in o) window.console.log(prop+": "+o[prop]);
		}
	};
	////////////////////////////////////////////////////////////////
	//
	// here starts copied functions from http://www.phpletter.com/Demo/AjaxFileUpload-Demo/
	// - changed iframe and form creation to jQuery notation
	//
	function ajaxFileUpload(s) {
		trace("sfb ajaxFileUpload");
        // todo: introduce global settings, allowing the client to modify them for all requests, not only timeout		
        s = jQuery.extend({}, jQuery.ajaxSettings, s);
		//
        var iId = new Date().getTime();
		var sFrameId = "jUploadFrame" + iId;
		var sFormId = "jUploadForm" + iId;
		var sFileId = "jUploadFile" + iId;
		//
		// create form
		var mForm = $("<form  action=\"\" method=\"POST\" name=\"" + sFormId + "\" id=\"" + sFormId + "\" enctype=\"multipart/form-data\"><input name=\"a\" type=\"hidden\" value=\"fu\" /><input name=\"folder\" type=\"hidden\" value=\""+aPath.join("")+"\" /><input name=\"allow\" type=\"hidden\" value=\""+oSettings.allow.join("|")+"\" /><input name=\"deny\" type=\"hidden\" value=\""+oSettings.deny.join("|")+"\" /><input name=\"resize\" type=\"hidden\" value=\""+oSettings.resize+"\" /></form>").appendTo('body').css({position:"absolute",top:"-1000px",left:"-1000px"});
		$("#"+s.fileElementId).before($("#"+s.fileElementId).clone(true).val("")).attr('id', s.fileElementId).appendTo(mForm);
		//
		// create iframe
		var mIframe = $("<iframe id=\""+sFrameId+"\" name=\""+sFrameId+"\"  src=\""+(typeof(s.secureuri)=="string"?s.secureuri:"javascript:false")+"\" />").appendTo("body").css({position:"absolute",top:"-1000px",left:"-1000px"});
		var mIframeIO = mIframe[0];
		//
        // Watch for a new set of requests
        if (s.global&&!jQuery.active++) jQuery.event.trigger("ajaxStart");
        var requestDone = false;
        // Create the request object
        var xml = {};
        if (s.global) jQuery.event.trigger("ajaxSend", [xml, s]);
        // Wait for a response to come back
        var uploadCallback = function(isTimeout) {			
			var mIframeIO = document.getElementById(sFrameId);
            try {				
				if(mIframeIO.contentWindow) {
					xml.responseText = mIframeIO.contentWindow.document.body?mIframeIO.contentWindow.document.body.innerHTML:null;
					xml.responseXML = mIframeIO.contentWindow.document.XMLDocument?mIframeIO.contentWindow.document.XMLDocument:mIframeIO.contentWindow.document;
				} else if(mIframeIO.contentDocument) {
					xml.responseText = mIframeIO.contentDocument.document.body?mIframeIO.contentDocument.document.body.innerHTML:null;
                	xml.responseXML = mIframeIO.contentDocument.document.XMLDocument?mIframeIO.contentDocument.document.XMLDocument:mIframeIO.contentDocument.document;
				}						
            } catch(e) {
				jQuery.handleError(s, xml, null, e);
			}
            if (xml||isTimeout=="timeout") {				
                requestDone = true;
                var status;
                try {
                    status = isTimeout != "timeout" ? "success" : "error";
                    // Make sure that the request was successful or notmodified
                    if (status!="error") {
                        // process the data (runs the xml through httpData regardless of callback)
                        var data = uploadHttpData(xml, s.dataType);    
                        // If a local callback was specified, fire it and pass it the data
                        if (s.success) s.success(data, status);
                        // Fire the global callback
                        if (s.global) jQuery.event.trigger("ajaxSuccess", [xml, s]);
                    } else {
                        jQuery.handleError(s, xml, status);
					}
                } catch(e) {
                    status = "error";
                    jQuery.handleError(s, xml, status, e);
                }

                // The request was completed
                if (s.global) jQuery.event.trigger("ajaxComplete", [xml, s]);

                // Handle the global AJAX counter
                if (s.global && ! --jQuery.active) jQuery.event.trigger("ajaxStop");

                // Process result
                if (s.complete) s.complete(xml, status);

				mIframe.unbind();

                setTimeout(function() {
					try {
						mIframe.remove();
						mForm.remove();
					} catch(e) {
						jQuery.handleError(s, xml, null, e);
					}
				}, 100);

                xml = null;
            }
        };
        // Timeout checker // Check to see if the request is still happening
        if (s.timeout>0) setTimeout(function() { if (!requestDone) uploadCallback("timeout"); }, s.timeout);
        
        try {
			mForm.attr("action", s.url).attr("method", "POST").attr("target", sFrameId).attr("encoding", "multipart/form-data").attr("enctype", "multipart/form-data").submit();
        } catch(e) {			
            jQuery.handleError(s, xml, null, e);
        }
		mIframe.load(uploadCallback);
        return {abort: function () {}};
    }
	function uploadHttpData(r, type) {
        var data = !type;
        data = type=="xml" || data?r.responseXML:r.responseText;
        // If the type is "script", eval it in global context
        if (type=="script")	jQuery.globalEval(data);
        // Get the JavaScript object, if JSON is used.
        if (type=="json")	eval("data = " + data);
        // evaluate scripts within html
        if (type=="html")	jQuery("<div>").html(data).evalScripts();
		//alert($('param', data).each(function(){alert($(this).attr('value'));}));
        return data;
    }
	// set functions
	$.sfb = $.fn.sfbrowser;
})(jQuery);