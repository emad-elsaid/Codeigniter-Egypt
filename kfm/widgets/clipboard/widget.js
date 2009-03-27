function Clipboard(){
	this.istarget=0;
	this.name='Clipboard'; // TODO: new string
	this.files=[];
	this.folders=[];
	this.display=function(){
		el=new Element('div',{
			'id':'kfm_widget_clipboard_container',
			'class':'widget_clipboard',
			'title':this.name,
			'styles':{
				'float':'left',
				'padding':'5px',
				'width':'70px',
				'height':'70px',
				'background-image':'url(\'widgets/clipboard/clipboard_empty.png\')',
				'background-repeat':'no-repeat',
				'font-size':'10px'
			},
			'events':{
				'mouseover':function(){
					if($j('#kdnd_drag_wrapper').length){
						if(kfm_theme=='blt') this.style.backgroundImage='url(\'widgets/clipboard/clipboard_add_blt.png\')';
						else this.style.backgroundImage='url(\'widgets/clipboard/clipboard_add.png\')';
					}
				},
				'mouseout':function(){this.setAppearance();},
				'click':function(){
					if(selectedFiles.length){
						this.action(selectedFiles,[]);
						kfm_selectNone();
					}
				}
			}
		});
		if(kfm_theme=='blt'){
			el.empty_url='widgets/clipboard/clipboard_empty_blt.png';
			el.full_url='widgets/clipboard/clipboard_full_blt.png';
			$j(el).css('background-image','url('+el.empty_url+')');
		}else{
			el.empty_url='widgets/clipboard/clipboard_empty.png';
			el.full_url='widgets/clipboard/clipboard_full.png';
		}
		el.files=[];
		el.folders=[];
		el.setAppearance=function(){
			var html='';
			if(this.files.length || this.folders.length){
				this.style.backgroundImage='url(\''+this.full_url+'\')';
				if(this.files.length)html+=this.files.length+' file'; // TODO: new string
				if(this.files.length>1)html+='s';
				if(this.folders.length)html+='<br/>'+this.folders.length+' folders'; // TODO: new string
			}
			else this.style.backgroundImage='url(\''+this.empty_url+'\')';
			this.innerHTML=html;
		}
		el.action=function(files,folders){
			for(var i=0;i<files.length;i++){
				if(!this.files.contains(files[i]))this.files.push(files[i]);
			}
			for(var i=0;i<folders.length;i++){
				if(!this.folders.contains(folders[i]))this.folders.push(folders[i]);
			}
			this.setAppearance();
		};
		el.clearContents=function(){
			this.files=[];
			this.folders=[];
			this.setAppearance();
		};
		el.pasteContents=function(){
			if(this.files.length)x_kfm_copyFiles(this.files,kfm_cwd_id,function(m){
				kfm_showMessage(m);
				x_kfm_loadFiles(kfm_cwd_id,kfm_refreshFiles);
			});
			if(this.folders.length)kfm.alert('paste of folders is not complete'); //TODO: complete
			this.clearContents();
		};
		kfm_addContextMenu(el,function(e){
			e=new Event(e);
			var el=$('kfm_widget_clipboard_container');
			var links=[];
			var plugins=kfm_getLinks(el.files);
			{ // add the links
				plugins.push(['$("kfm_widget_clipboard_container").clearContents()','clear clipboard']); // TODO: new string
				plugins.push(['$("kfm_widget_clipboard_container").pasteContents()','paste clipboard contents']); // TODO: new string
			}
			kfm_createContextMenu(e.page,plugins);
		});
		setTimeout("kdnd_makeDraggable('widget_clipboard');",1);
		return el;
	}
	return this;
}
kfm_addWidget(new Clipboard());
kdnd_addDropHandler('widget_clipboard','#kfm_right_column',function(e){
	e.sourceElement.pasteContents();
});
kdnd_addDropHandler('kfm_file','.widget_clipboard',function(e){
	if(!selectedFiles.length)kfm_addToSelection(e.sourceElement.id.replace(/.*_/,''));
	e.targetElement.action(selectedFiles,[]);
});
kdnd_addDropHandler('kfm_dir_name','.widget_clipboard',function(e){
	var dir_from=parseInt($E('.kfm_directory_link',e.sourceElement).node_id);
	e.targetElement.action([],[dir_from]);
});
kdnd_addDropHandler('widget_clipboard','.kfm_dir_name',function(e){
	if(!e.sourceElement.files.length)return;
		//dir_over=e.targetElement.node_id;
	var dir_over=parseInt($E('.kfm_directory_link',e.targetElement).node_id);
		var links=[];
		links.push(['x_kfm_copyFiles(['+e.sourceElement.files.join(',')+'],'+dir_over+',kfm_showMessage);kfm_selectNone()','copy files']);
		links.push(['x_kfm_moveFiles(['+e.sourceElement.files.join(',')+'],'+dir_over+',function(e){if($type(e)=="string")return alert("error: could not move file[s]");kfm_removeFilesFromView(['+selectedFiles.join(',')+'])});kfm_selectNone()','move files',0,!kfm_vars.permissions.file.mv]); // TODO: new string
		kfm_createContextMenu(e.page,links);
	e.sourceElement.clearContents();
});
kdnd_addDropHandler('widget_clipboard','.widget_trash',function(e){
	if(!e.sourceElement.files.length)return;
	e.targetElement.action(e.sourceElement.files);
	e.sourceElement.clearContents();
});
