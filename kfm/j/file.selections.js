// see ../license.txt for licensing
function kfm_addToSelection(id){
	id=parseInt(id);
	if(!id || selectedFiles.contains(id))return;
	selectedFiles.push(id);
	$('kfm_file_icon_'+id).className+=' selected';
	if(kfm_log_level>0)kfm_log(kfm.lang.FileSelected(id));
	kfm_selectionCheck();
}
function kfm_chooseFile(){
	if(selectedFiles.length>1 && !kfm_vars.files.allow_multiple_returns)return kfm.alert(kfm.lang.NotMoreThanOneFile);
	if(kfm_vars.files.return_id_to_cms){
		window.opener.SetUrl(selectedFiles.join(','));
		setTimeout('window.close()',1);
	}
	else {
		x_kfm_getFileUrls(selectedFiles,function(urls){
			if(copy_to_clipboard)copy_to_clipboard(urls.join("\n"));
			if(!window.opener || kfm_file_handler=='download'){
				var allImages=1;
				for(var i=0;i<selectedFiles.length;++i)if(!File_getInstance(selectedFiles[i]).width)allImages=0;
				if(!allImages){
					for(var i=0;i<urls.length;++i){
						var url=urls[i];
						if(/get.php/.test(url))url+='&forcedownload=1';
						document.location=url;
					}
				}
				else kfm_img_startLightbox(selectedFiles)
				return;
			}
			if(selectedFiles.length==1&&File_getInstance(selectedFiles[0]).width)window.opener.SetUrl(urls[0].replace(/([^:]\/)\//g,'$1'),0,0,File_getInstance(selectedFiles[0]).caption);
			else{
				if(selectedFiles.length==1)window.opener.SetUrl(urls[0]);
				else window.opener.SetUrl('"'+urls.join('","')+'"');
			}
			setTimeout('window.close()',1);
		});
	}
}
function kfm_isFileSelected(filename){
	return kfm_inArray(filename,selectedFiles);
}
function kfm_removeFromSelection(id){
	if(!id)return;
	var i;
	for(i=0;i<selectedFiles.length;++i){
		if(selectedFiles[i]==id){
			var el=$('kfm_file_icon_'+id);
			if(el)el.removeClass('selected');
			kfm_selectionCheck();
			return selectedFiles.splice(i,1);
		}
	}
}
function kfm_selectAll(){
	kfm_selectNone();
	var a,b=$('documents_body').fileids;
	for(a=0;a<b.length;++a)kfm_addToSelection(b[a]);
}
function kfm_selectInvert(){
	var a,b=$('documents_body').fileids;
	for(a=0;a<b.length;++a)if(kfm_isFileSelected(b[a]))kfm_removeFromSelection(b[a]);
	else kfm_addToSelection(b[a]);
}
function kfm_selectNone(){
	if(kfm_lastClicked){
		var el=$('kfm_file_icon_'+kfm_lastClicked);
		if(el)el.removeClass('last_clicked');
	}
	for(var i=selectedFiles.length;i>-1;--i)kfm_removeFromSelection(selectedFiles[i]);
	kfm_lastClicked=0;
	kfm_selectionCheck();
}
function kfm_selectionCheck(){
	if(selectedFiles.length==1){
		var el=$E('#kfm_file_details_panel div.kfm_panel_body');
		if(el)el.innerHTML='loading';
		kfm_run_delayed('file_details','if(selectedFiles.length==1)kfm_showFileDetails(selectedFiles[0]);');
	}
	else kfm_run_delayed('file_details','if(!selectedFiles.length)kfm_showFileDetails();');
}
function kfm_selection_drag(e){
	e=new Event(e);
	if(!window.dragType||window.dragType!=2||!window.drag_wrapper)return;
	clearSelections();
	var p1=e.page,p2=window.drag_wrapper.orig;
	var x1=p1.x>p2.x?p2.x:p1.x;
	var x2=p2.x>p1.x?p2.x:p1.x;
	var y1=p1.y>p2.y?p2.y:p1.y;
	var y2=p2.y>p1.y?p2.y:p1.y;
	window.drag_wrapper.setStyles('display:block;left:'+x1+'px;top:'+y1+'px;width:'+(x2-x1)+'px;height:'+(y2-y1)+'px;zIndex:4');
}
function kfm_selection_dragFinish(e){
	e=new Event(e);
	$clear(window.dragSelectionTrigger);
	if(!window.drag_wrapper)return;
	var right_column=$('documents_body'),p1=e.page,p2=window.drag_wrapper.orig,offset=right_column.scrollTop;
	var x1=p1.x>p2.x?p2.x:p1.x, x2=p2.x>p1.x?p2.x:p1.x, y1=p1.y>p2.y?p2.y:p1.y, y2=p2.y>p1.y?p2.y:p1.y;
//	y1+=offset;
//	y2+=offset;
	setTimeout('window.dragType=0;',1); // pause needed for IE
	window.drag_wrapper.remove();
	window.drag_wrapper=null;
	document.removeEvent('mousemove',kfm_selection_drag);
	document.removeEvent('mouseup',kfm_selection_dragFinish);
	var fileids=right_column.fileids;
	for(var i=0;i<fileids.length;++i){
		var curIcon=$('kfm_file_icon_'+fileids[i]);
		var curTop=getOffset(curIcon,'Top');
		var curLeft=getOffset(curIcon,'Left');
		if((curLeft+curIcon.offsetWidth)>x1&&curLeft<x2&&(curTop+curIcon.offsetHeight)>y1&&curTop<y2)kfm_addToSelection(fileids[i]);
	}
	kfm_selectionCheck();
}
function kfm_selection_dragStart(e){
	if(window.dragType)return;
	if (!kfm_vars.use_templates && window.mouseAt.x > $('kfm_right_column').scrollWidth + $('kfm_left_column').scrollWidth - 15) return;
	window.dragType=2;
	var w=window.getSize().size;
	document.addEvent('mouseup',kfm_selection_dragFinish);
	window.drag_wrapper=new Element('div',{
		'id':'kfm_selection_drag_wrapper',
		'styles':{
			'display':'none'
		}
	});
	window.drag_wrapper.orig=window.mouseAt;
	kfm.addEl(document.body,window.drag_wrapper);
	document.addEvent('mousemove',kfm_selection_drag);
}
function kfm_shiftFileSelectionLR(dir){
	if(selectedFiles.length>1)return;
	var na=$('documents_body').fileids,a=0,ns=na.length;
	if(selectedFiles.length){
		for(;a<ns;++a)if(na[a]==selectedFiles[0])break;
		if(dir>0){if(a==ns-1)a=-1}
		else if(!a)a=ns;
	}
	else a=dir>0?-1:ns;
	kfm_selectSingleFile(na[a+dir]);
}
function kfm_shiftFileSelectionUD(dir){
	if(selectedFiles.length>1)return;
	var na=$('documents_body').fileids,a=0,ns=na.length,icons_per_line=0,topOffset=$('kfm_file_icon_'+na[0]).offsetTop;
	if(selectedFiles.length){
		if(topOffset==$('kfm_file_icon_'+na[ns-1]).offsetTop)return; // only one line of icons
		for(;$('kfm_file_icon_'+na[icons_per_line]).offsetTop==topOffset;++icons_per_line);
		for(;a<ns;++a)if(na[a]==selectedFiles[0])break; // what is the selected file
		a+=icons_per_line*dir;
		if(a>=ns)a=ns-1;
		if(a<0)a=0;
	}
	else a=dir>0?0:ns-1;
	kfm_selectSingleFile(na[a]);
}
function kfm_toggleSelectedFile(e){
	var row;
	e=new Event(e);
	if(e.rightClick)return;
	e.stopPropagation();
	if(window.dragAddedFileToSelection){
		window.dragAddedFileToSelection=false;
		return;
	}
	var el=e.target;
	while(el.tagName!='DIV')el=el.parentNode;
	var id=el.file_id;
	if(kfm_listview){
		row=el;
		while(row.nodeName!='TR')row=row.parentNode;
		rowInd=row.rowIndex;
	}
	if(kfm_lastClicked){
		var el=$('kfm_file_icon_'+kfm_lastClicked);
		if(el)el.removeClass('last_clicked');
		else kfm_lastClicked=0;
	}
	if(kfm_lastClicked&&e.shift){
		var e=kfm_lastClicked;
		if(kfm_listview){
			row=el;
			while(row.nodeName!='TR')row=row.parentNode;
			smalRow=Math.min(row.rowIndex,rowInd);
			bigRow=Math.max(row.rowIndex,rowInd);
			$j('#kfm_files_listview_table tbody tr:lt('+bigRow+')').each(function(){
				if(this.rowIndex>=smalRow)kfm_addToSelection(this.fileid);
			});
		}else{
			clearSelections(e);
			kfm_selectNone();
			var a=$('documents_body').fileids,b,c,d;
			for(b=0;b<a.length;++b){
				if(a[b]==e)c=b;
				if(a[b]==id)d=parseInt(b);
			}
			if(c>d){
				b=c;
				c=d;
				d=b;
			}
			for(;c<=d;++c)kfm_addToSelection(a[c]);
		}
	}
	else{
		if(kfm_isFileSelected(id)){
			if(!e.control)kfm_selectNone();
			else kfm_removeFromSelection(id);
		}
		else{
			if(!e.control&&!e.meta)kfm_selectNone();
			kfm_addToSelection(id);
		}
	}
	kfm_lastClicked=id;
	$('kfm_file_icon_'+id).className+=' last_clicked';
}
function kfm_selectSingleFile(id){
	kfm_selectNone();
	kfm_addToSelection(id);
	var panel=$('kfm_right_column'),el=$('kfm_file_icon_'+id);
	var offset=panel.scrollTop,panelHeight=panel.offsetHeight,elTop=getOffset(el,'Top'),elHeight=el.offsetHeight;
	if(elTop+elHeight-offset>panelHeight)panel.scrollTop=elTop-panelHeight+elHeight;
	else if(elTop<offset)panel.scrollTop=elTop;
}
