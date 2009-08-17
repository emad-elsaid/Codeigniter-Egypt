// see license.txt for licensing
var kfm_file_bits={
	dragDisplay:function(){
		window.dragAddedFileToSelection=false;
		if(!kfm_isFileSelected(this.file_id)){
			kfm_addToSelection(this.file_id);
			window.dragAddedFileToSelection=true;
		}
		var drag_wrapper=new Element('div',{
			'id':'kfm_drag_wrapper',
			styles:{
				'min-width':100,
				'opacity':'.7'
			}
		});
		for(var i=0;i<10&&i<selectedFiles.length;++i)kfm.addEl(drag_wrapper,[File_getInstance(selectedFiles[i]).name,new Element('br')]);
		if(selectedFiles.length>10)kfm.addEl(
			drag_wrapper,
			(new Element('i')).setHTML(kfm.lang.AndNMore(selectedFiles.length-10))
		);
		return drag_wrapper;
	},
	padding:0
}
function kfm_buildFileDetailsTable(res){
	if(!res)return kfm_log('error: missing file details?');
	var table=new Element('table'),r;
	if(res.name){ // filename
		r=kfm.addRow(table);
		kfm.addCell(r,0,0,(new Element('strong')).setHTML(kfm.lang.Filename));
		kfm.addCell(r,1,0,res.name);
	}
	if(res.filesize){ // filesize
		r=kfm.addRow(table);
		kfm.addCell(r,0,0,(new Element('strong')).setHTML(kfm.lang.Filesize));
		kfm.addCell(r,1,0,res.filesize);
	}
	if(res.tags&&res.tags.length){ // tags
		r=kfm.addRow(table);
		kfm.addEl(kfm.addCell(r,0),(new Element('strong')).setHTML(kfm.lang.Tags));
		var arr=[],c=kfm.addCell(r,1);
		for(var i=0;i<res.tags.length;++i){
			kfm.addEl(c,kfm_tagDraw(res.tags[i]));
			if(i!=res.tags.length-1)kfm.addEl(c,', ');
		}
	}
	if(res.mimetype){ // mimetype
		r=kfm.addRow(table);
		kfm.addEl(kfm.addCell(r,0),(new Element('strong')).setHTML(kfm.lang.Mimetype));
		kfm.addEl(kfm.addCell(r,1),res.mimetype);
		switch(res.mimetype.replace(/\/.*/,'')){
			case 'image':{
				if(res.caption){ // caption
					r=kfm.addRow(table);
					kfm.addCell(r,0,0,(new Element('strong')).setHTML(kfm.lang.Caption));
					kfm.addCell(r,1).innerHTML=(res.caption).replace(/\n/g,'<br \/>');
				}
				break;
			}
		}
	}
	if(res.ctime){ // last change time
		r=kfm.addRow(table);
		kfm.addEl(kfm.addCell(r,0),(new Element('strong')).setHTML(kfm.lang.LastModified));
		var d=(new Date(res.ctime*1000)).toGMTString();
		kfm.addEl(kfm.addCell(r,1),d);
	}
	if(res.width) {
		r=kfm.addRow(table);
		kfm.addCell(r,0,0,(new Element('strong')).setHTML(kfm.lang.ImageDimensions));
		kfm.addCell(r,1,0,res.width+" x "+res.height);	  
	}
	return table;
}
function kfm_deleteFile(id){
	if(!kfm_vars.permissions.file.rm)return kfm.alert(kfm.lang.PermissionDeniedCannotDeleteFile);
	var filename=File_getInstance(id).name;
	if(kfm.confirm(kfm.lang.DelFileMessage(filename))){
		x_kfm_rm([id],kfm_removeFilesFromView);
	}
}
function kfm_deleteSelectedFiles(){
	if(!kfm_vars.permissions.file.rm)return kfm.alert('permission denied: cannot delete files');
	kfm_deleteFiles(selectedFiles);
}
function kfm_deleteFiles(files){
	var names=[],m='';
	 var dfiles=[]; // break reference system
	 for(var j=0;j<files.length;j++)dfiles.push(files[j]);//breaking reference to selectedFiles
	if(dfiles.length>10){
		for(var i=0;i<9;++i)names.push(File_getInstance(dfiles[i]).name);
		m='\n'+kfm.lang.AndNMore(dfiles.length-9);
	}
	else for(var i=0;i<dfiles.length;++i)names.push(File_getInstance(dfiles[i]).name);
	if(kfm.confirm(kfm.lang.DelMultipleFilesMessage+names.join('\n')+m))x_kfm_rm(dfiles,kfm_removeFilesFromView);
}
function kfm_downloadFileFromUrl(filename,msg){
	if(filename.toString()!==filename)filename='';
	var url=$('kfm_url').value;
	if(url.substring(0,4)!='http'){
		kfm_log(kfm.lang.UrlNotValidLog);
		return;
	}
	if(!filename)filename=url.replace(kfm_regexps.all_up_to_last_slash,'');
	var not_ok=0,o;
	kfm_prompt(kfm.lang.FileSavedAsMessage+msg,filename,function(filename){
		if(!filename)return;
		if(kfm_isFileInCWD(filename)){
			o=kfm.confirm(kfm.lang.AskIfOverwrite(filename));
			if(!o)not_ok=1;
		}
		if(filename.indexOf('/')>-1){
			msg=kfm.lang.NoForwardslash;
			not_ok=1;
		}
		if(not_ok)return kfm_downloadFileFromUrl(filename,msg);
		x_kfm_downloadFileFromUrl(url,filename,kfm_refreshFiles);
		$('kfm_url').value='';
	});
}
function kfm_downloadSelectedFiles(id){
	var wrapper=$('kfm_download_wrapper');
	if(!wrapper){
		wrapper=new Element('div',{
			'id':'kfm_download_wrapper',
			'styles':{
				'display':'none'
			}
		});
		kfm.addEl(document.body,wrapper);
	}
	wrapper.empty();
	if(id)kfm_downloadSelectedFiles_addIframe(wrapper,id);
	else for(var i=0;i<selectedFiles.length;++i)kfm_downloadSelectedFiles_addIframe(wrapper,selectedFiles[i]);
}
function kfm_downloadSelectedFiles_addIframe(wrapper,id){
	var iframe=new Element('iframe');
	iframe.src='get.php?id='+id+'&forcedownload=1';
	kfm.addEl(wrapper,iframe);
}
function kfm_extractZippedFile(id){
	x_kfm_extractZippedFile(id,kfm_refreshFiles);
}
function kfm_files_reflowIcons(){
	var panel=$('documents_body');
	if(panel.contentMode!='file_icons')return;
	var els=$ES('.kfm_file_icon',panel);
	for(var i=0;i<els.length;++i){
		var el=els[i];
		el.setStyle('clear','none');
		if(i&&els[i-1].offsetLeft>=el.offsetLeft)el.setStyle('clear','left');
	}
}
function kfm_isFileInCWD(id){
	var i,files=$('documents_body').fileids;
	for(i=0;i<files.length;++i)if(files[i]==id)return true;
	return false;
}
function kfm_incrementalFileDisplay(){
	var b=window.kfm_incrementalFileDisplay_vars,fsdata=b.data.files,wrapper=$('documents_body');
	var icon=new Element('div',{
		'class':'kfm_file '+(kfm_listview?'kfm_file_listview':'kfm_file_icon'),
		'styles':{
			'cursor':(window.ie?'hand':'pointer')
		},
		'events':{
			'click':kfm_toggleSelectedFile,
			'dblclick':function(e){
				e=new Event(e);
				var el=e.target;
				while(!el.file_id && el)el=el.parentNode;
				if(!el)return;
				var id=el.file_id;
				kfm_selectNone();
				kfm_addToSelection(id);
				var openingHook=kfm_getDefaultOpener(id);
				if(!window.opener && openingHook)openingHook.doFunction([id]);
				else kfm_chooseFile();
			}
		}
	});
	if(!kfm_listview){
		icon.addEvent('mouseover',function(e){ // initialise info tooltip
			if(window.kfm_tooltipInit)$clear(window.kfm_tooltipInit);
			if(window.kdnd_dragging)return; // don't open if currently dragging files
			e=new Event(e);
			window.kfm_tooltipInit=setTimeout('kfm_showToolTip('+e.target.file_id+')',1000);
		});
		icon.addEvent('mouseout',function(){ // remove info tooltip
			if(window.kfm_tooltipInit)$clear(window.kfm_tooltipInit);
			var o=$('kfm_tooltip');
			if(o)o.remove();
		});
	}
	kfm_addContextMenu(icon,function(e){
		var el=e.target;
		while(el.parentNode&&!el.file_id)el=el.parentNode;
		if(!el.parentNode)return;
		{ // variables
			var i,id=el.file_id;
			var F=File_getInstance(id);
			var extension=F.name.replace(/.*\./,'').toLowerCase();
			var writable=F.writable;
		}
		{ // add the links
			var links=[],a,b,c;
			if(selectedFiles.length>1)links=kfm_getLinks(selectedFiles);
			else{
				a=kfm_getDefaultOpener(id);
				if(a){
					b=$j.extend({},a);
					b.name='open'; // TODO: string
					b.title='open'; // TODO: string
					links.push(b);
				}
				var a=kfm_getLinks([id]);
				for(b=0;b<a.length;++b)links.push(a[b]); 
			}
			if(selectedFiles.length<=1 && F.width)links.push(['kfm_changeCaption('+id+')',kfm.lang.ChangeCaption,'edit',!kfm_vars.permissions.file.ed]);
			links.push(['kfm_tagAdd('+id+')',kfm.lang.AddTagsToFiles,'add_tags',!kfm_vars.permissions.file.ed]);
			links.push(['kfm_tagRemove('+id+')',kfm.lang.RemoveTagsFromFiles,'',!kfm_vars.permissions.file.ed]);
			kfm_createContextMenu(e.page,links);
		}
	});
	do{
		var a,fdata,name,F,nameEl,el,fullfilename,id;
		a=b.at;
		fdata=fsdata[a];
		if(wrapper.contentMode!='file_icons')return (window.kfm_incrementalFileDisplay_vars=null);
		name=fdata.name;
		fullfilename=kfm_cwd_name+'/'+name;
		id=fdata.id;
		F=File_getInstance(id,fdata);
		ext=fdata.ext;
		nameEl=F.getText('name');
		el=icon.cloneNode(true);
		el.cloneEvents(icon);
		if(!kfm_listview)$j(el).addClass('kfm_icontype_'+ext);
		el.id='kfm_file_icon_'+id;
		el.dragDisplay=kfm_file_bits.dragDisplay;
		var writable=fdata.writable;
		{ // file attributes
			el.file_id=id;
			if(!kfm_listview && fdata.width)F.setThumbnailBackground(el);
			wrapper.files[a]=el;
		}
		kfm.addEl(wrapper,el);
		if(kfm_listview){
			var cs=0,cell;
			var listview_table=$j('#kfm_files_listview_table tbody').get(0);
			var rows=listview_table.rows.length;
			var row=listview_table.insertRow(rows);
			row.fileid=F.id;
			cell=row.insertCell(cs++);
			cell.className='listview_icon listview_icon_'+ext;
			if(window.ie)cell.innerHTML='&nbsp;';
			else cell.setHTML('&nbsp;');
			row.insertCell(cs++).appendChild(el);
			{ // file size
				cell=row.insertCell(cs++);
				var hidden=document.createElement('span');
				hidden.style.display='none';
				hidden.appendChild(document.createTextNode(F.filesize_raw));
				cell.appendChild(hidden);
				cell.appendChild(F.getText('filesize'));
			}
			row.insertCell(cs++).appendChild(F.getText('ext'));
			{ // modified time
				cell=row.insertCell(cs++);
				var hidden=document.createElement('span');
				hidden.style.display='none';
				hidden.appendChild(document.createTextNode(F.ctime));
				cell.appendChild(hidden);
				cell.appendChild(F.getText('modified'));
			}
		}
		else wrapper.appendChild(el);
		el.appendChild(nameEl);
		if(a&&$('kfm_file_icon_'+fsdata[a-1].id).offsetLeft>=el.offsetLeft)el.setStyle('clear','left');
		window.kfm_incrementalFileDisplay_vars.at=a+1;
	}while(a+1<fsdata.length && (a+1)%kfm_show_files_in_groups_of);
	if(a+1<fsdata.length)window.kfm_incrementalFileDisplay_loader=setTimeout('kfm_incrementalFileDisplay()',1);
	else{
		kdnd_makeDraggable('kfm_file');
		if(kfm_listview){
			$j('#kfm_tooltip').remove();
			$j('#kfm_files_listview_table').columnSizing();
			$j('#kfm_files_listview_table').tablesorter({
				sortList:[[1,0]],
				headers:{
					1:{
						sorter:'kfmobject'
					}
				},
				widgets:['zebra']
			});
		}
		$j('#documents_loader').empty().html('&nbsp;');
		if(kfm_vars.startup_selectedFiles){
			for(var i=0;i<kfm_vars.startup_selectedFiles.length;++i)kfm_addToSelection(kfm_vars.startup_selectedFiles[i]);
			kfm_vars.startup_selectedFiles=false;
		}
	}
}
function kfm_refreshFiles(res){
	if(!res.files)return;
	$j('#folder_info').text(res.files.length+(res.files.length==1?' file':' files')); //TODO new string
	kdnd_addDropHandler('kfm_file','.kfm_directory_link',function(e){
		dir_over=e.targetElement.node_id;
		var mv='x_kfm_moveFiles(['+selectedFiles.join(',')+'],'+dir_over+',function(e){if($type(e)=="string")return alert(kfm.lang.CouldNotMoveFiles);kfm_removeFilesFromView(['+selectedFiles.join(',')+'])});kfm_selectNone()';
		var cp='x_kfm_copyFiles(['+selectedFiles.join(',')+'],'+dir_over+',kfm_showMessage);kfm_selectNone()';
		if(kfm_vars.files.drags_move_or_copy == 1)eval(mv); // always Move
		else if(kfm_vars.files.drags_move_or_copy == 2)eval(cp); // always Copy
		else{
			var links=[];
			links.push([cp,kfm.lang.CopyFiles]);
			links.push([mv,kfm.lang.MoveFiles,0,!kfm_vars.permissions.file.mv]);
			kfm_createContextMenu(e.page,links);
		}
	});
	if(window.kfm_incrementalFileDisplay_loader){
		$clear(window.kfm_incrementalFileDisplay_loader);
		window.kfm_incrementalFileDisplay_vars=null;
	}
	kfm_selectNone();
	if(!res)return;
	if(res.parent)kfm_cwd_id=res.parent;
	if(res.toString()===res)return kfm_log(res);
	window.kfm_incrementalFileDisplay_vars={at:0,data:res};
	var a,b,lowest_name,lowest_index,wrapper=$('documents_body').empty();
	$extend(wrapper,{contentMode:'file_icons',fileids:[],files:[]});
	$j('#cwd_display').text(kfm.lang.CurrentWorkingDir(res.reqdir));
	{ // order files by name
		if(!res.files)res.files=[];
		for(a=0;a<res.files.length-1;++a){
			lowest_name=res.files[a].name;
			lowest_index=a;
			for(b=a+1;b<res.files.length;++b){
				if(res.files[b].name<lowest_name){
					lowest_index=b;
					lowest_name=res.files[b].name;
				}
			}
			if(lowest_index!=a){
				b=res.files[a];
				res.files[a]=res.files[lowest_index];
				res.files[lowest_index]=b;
			}
		}
	}
	for(a=0;a<res.files.length;++a)wrapper.fileids[a]=res.files[a].id;
	document.title='KFM: '+res.reqdir;
	kfm_lastClicked=null;
	kfm_log(kfm.lang.FilesRefreshed);
	if(res.uploads_allowed)kfm_addPanel('kfm_left_column','kfm_file_upload_panel');
	else kfm_removePanel('kfm_left_column','kfm_file_upload_panel');
	kfm_refreshPanels('kfm_left_column');
	if(!res.files.length){
		$j('#documents_loader').empty().html('&nbsp;');
		kfm.addEl(wrapper,(new Element('span',{
			'class':'kfm_empty'
		})).setHTML(kfm.lang.DirEmpty(res.reqdir)));
	 }else{
		if(kfm_listview){
			var listview_table=new Element('table',{
				'id':'kfm_files_listview_table'
			});
			wrapper.appendChild(listview_table);
			$j(listview_table).html('<thead><tr class="listview_headers"><th>&nbsp;</th><th>Name</th><th style="width:72px">Size</th><th style="width:72px">Type</th><th style="width:142px">Modified</th></tr></thead><tbody></tbody>');
				$j(listview_table).css('width','99%');
		}
		kfm_incrementalFileDisplay();
	}
}
function kfm_removeFilesFromView(files){
	kfm_selectNone();
	if($type(files)!='array' || !files.length)return;
	var i=0,right_column=$('documents_body');
	for(var i=0;i<files.length;++i){
		var el=$('kfm_file_icon_'+files[i]);
		if(el){
			if(kfm_listview){
							el.parentNode.parentNode.remove();
							var trows=$ES('#kfm_files_listview_table tr');
						}
			else el.remove();
		}
		right_column.fileids.remove(files[i]);
	}
	kfm_files_reflowIcons();
}
function kfm_renameFile(id){
	var filename=File_getInstance(id).name;
	kfm_prompt(kfm.lang.RenameFileToWhat(filename),filename,function(newName){
		if(!newName||newName==filename)return;
		kfm_log(kfm.lang.RenamedFile(filename,newName));
		x_kfm_renameFile(id,newName,kfm_refreshFiles);
	});
}
function kfm_renameFiles(nameTemplate){
	if(nameTemplate && nameTemplate.toString()!==nameTemplate)nameTemplate='';
	var ok=false;
	kfm_prompt(kfm.lang.HowWouldYouLikeToRenameTheseFiles,nameTemplate,function(nameTemplate){
		var asterisks=nameTemplate.replace(/[^*]/g,'').length;
		if(!nameTemplate)return;
		if(!/\*/.test(nameTemplate))alert(kfm.lang.YouMustPlaceTheWildcard);
		else if(/\*[^*]+\*/.test(nameTemplate))alert(kfm.lang.IfYouUseMultipleWildcards);
		else if(asterisks<(''+selectedFiles.length).length)alert(kfm.lang.YouNeedMoreThan(asterisks,selectedFiles.length));
		else ok=true;
		if(!ok)return kfm_renameFiles(nameTemplate);
		x_kfm_renameFiles(selectedFiles,nameTemplate,kfm_refreshFiles);
	});
}
function kfm_showFileDetails(id){
	var res=File_getInstance(id);
	var fd=$('kfm_file_details_panel'),el=$('kfm_left_column');
		if(!el)return false;
	if(!fd){
		kfm_addPanel('kfm_left_column','kfm_file_details_panel');
		kfm_refreshPanels(el);
	}
	var body=$E('#kfm_file_details_panel div.kfm_panel_body').empty();
	if(!res){
		body.innerHTML=kfm.lang.NoFilesSelected;
		return;
	}
	var table=kfm_buildFileDetailsTable(res);
	kfm.addEl(body,table);
}
function kfm_showToolTip(id){
	if(!id || kfm_listview)return;
	var F=File_getInstance(id);
	var table=kfm_buildFileDetailsTable(F),icon=$('kfm_file_icon_'+id);
	if(!icon||contextmenu)return;
	table.id='kfm_tooltip';
	kfm.addEl(document.body,table);
	var l=getOffset(icon,'Left'),t=getOffset(icon,'Top'),w=icon.offsetWidth,h=icon.offsetHeight,ws=window.getSize().size;
	l=(l+(w/2)>ws.x/2)?l-table.offsetWidth:l+w;
	table.setStyles('position:absolute;top:'+t+'px;left:'+l+'px;visibility:visible;opacity:.9');
}
function kfm_zip(name){
	if(!name || name.toString()!==name)name='zipped.zip';
	var ok=false;
	kfm_prompt(kfm.lang.WhatFilenameDoYouWantToUse,name,function(name){
		if(!name)return;
		if(/\.zip$/.test(name))ok=true;
		else kfm.alert(kfm.lang.TheFilenameShouldEndWithN('.zip'));
		if(!ok)return kfm_zip(name);
		x_kfm_zip(name,selectedFiles,kfm_refreshFiles);
	});
}
function kfm_fileLoader(id){
	if($type(id)!='array')return $j('#kfm_file_icon_'+id).css('background-image','url(themes/'+kfm_theme+'/icons/64x64/loader.gif)');
	id.each(kfm_fileLoader);
}
function kfm_filesLoader(){
	$j('<img src="themes/'+kfm_theme+'/small_loader.gif" alt=""/>').appendTo('#documents_loader');
}
