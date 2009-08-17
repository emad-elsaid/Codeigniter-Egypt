// see ../license.txt for licensing
function kfm_createEmptyFile(filename,msg){
	if(!filename || filename.toString()!==filename){
		filename='';
		msg='';
	}
	var not_ok=0;
	kfm_prompt(kfm.lang.WhatFilenameToCreateAs+msg,filename,function(filename){
		if(!filename)return;
		if(kfm_isFileInCWD(filename)){
			var o=kfm.confirm(kfm.lang.AskIfOverwrite(filename));
			if(!o)not_ok=1;
		}
		if(filename.indexOf('/')>-1){
			msg=kfm.lang.NoForwardslash;
			not_ok=1;
		}
		if(not_ok)return kfm_createEmptyFile(filename,msg);
		x_kfm_createEmptyFile(kfm_cwd_id,filename,kfm_refreshFiles);
	});
}
function kfm_leftColumn_disable(){
	var left_column=$('kfm_left_column');
	document.body.appendChild(new Element('div',{
		'id':'kfm_left_column_hider',
		'styles':{
			'position':'absolute',
			'left':0,
			'top':0,
			'width':left_column.offsetWidth,
			'height':left_column.offsetHeight,
			'opacity':'.7',
			'background':'#fff'
		}
	}));
	kfm_resizeHandler_addMaxHeight('kfm_left_column_hider');
}
function kfm_leftColumn_enable(){
	if(!$("kfm_left_column_hider"))return;
	$("kfm_left_column_hider").remove();
	kfm_resizeHandler_removeMaxHeight('kfm_left_column_hider');
}
function kfm_textfile_attachKeyBinding(){
	if(!codepress.editor||!codepress.editor.body)return setTimeout('kfm_textfile_attachKeyBinding();',1);
	var doc=codepress.contentWindow.document;
	if(doc.attachEvent)doc.attachEvent('onkeypress',kfm_textfile_keybinding);
	else doc.addEventListener('keypress',kfm_textfile_keybinding,false);
}
function kfm_textfile_close(){
	if($("edit-start").value!=codepress.getCode() && !kfm.confirm( kfm.lang.CloseWithoutSavingQuestion))return;
	kfm_leftColumn_enable();
	kfm_changeDirectory("kfm_directory_icon_"+kfm_cwd_id);
	$('kfm_right_column').removeEvent('keyup',kfm_textfile_keybinding);
}
function kfm_textfile_createEditor(){
	CodePress.run();
	if($("kfm_tooltip"))$("kfm_tooltip").remove();
	kfm_textfile_attachKeyBinding();
}
function kfm_textfile_initEditor(res,readonly){
	if(!$('kfm_left_column_hider'))kfm_leftColumn_disable();
	var t=new Element('table',{
		'id':'kfm_editFileTable',
		'styles':{
			'width':'100%'
		}
	});
	var right_column=$('kfm_right_column').empty();
	right_column.addEvent('keyup',kfm_textfile_keybinding);
	right_column.contentMode='codepress';
	right_column.appendChild(t);
	var r2=kfm.addRow(t),c=0;
	kfm.addCell(r2,c++,1,res.name);
	if(!readonly){ /* show option to save edits */
		kfm.addCell(r2,c++,1,newLink('javascript:new Notice("saving file...");$("edit-start").value=codepress.getCode();x_kfm_saveTextFile('+res.id+',$("edit-start").value,kfm_showMessage);','Save',0,'button'));
	}
	kfm.addCell(r2,c++,1,newLink('javascript:kfm_textfile_close()',kfm.lang.Close,0,'button'));
	var row=$(kfm.addRow(t));
	r3=kfm.addCell(row,0,c);
	r3.id='kfm_codepressTableCell';
	var className='codepress '+res.language+(readonly?' readonly-on':'');
	var h=window.getSize().size.y-t.offsetHeight-2;
	if(window.ie)h-=13;
	var codeEl=new Element('textarea',{
		'id':'codepress',
		'class':className,
		'value':res.content,
		'title':res.name,
		'styles':{
			'width':t.offsetWidth-25,
			'height':h
		}
	});
	changeCheckEl=newInput('edit-start','textarea',res.content);
	changeCheckEl.setStyle('display','none');
	r3.appendChild(codeEl);
	r3.appendChild(changeCheckEl);
	if(window.CodePress)kfm_textfile_createEditor();
	else loadJS('j/codepress-0.9.6/codepress.js','cp-script','en-us','kfm_textfile_createEditor();');
}
function kfm_textfile_keybinding(e){
	e=new Event(e);
	if(e.code!=27)return;
	e.stopPropagation();
	kfm_textfile_close();
}
