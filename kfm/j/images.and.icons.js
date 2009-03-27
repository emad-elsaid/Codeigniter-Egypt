// see ../license.txt for licensing
function kfm_changeCaption(id){
	kfm_prompt(kfm.lang.ChangeCaption,File_getInstance(id).caption,function(newCaption){
		x_kfm_changeCaption(id,newCaption,function(res){
			File_getInstance(id).caption=newCaption;
		});
	});
	/*
	var table=$extend(new Element('table',{
		'id':'kfm_newCaptionDetails'
	}),{kfm_caption_for:id});
	var row=table.insertRow(0),textarea=newInput('kfm_new_caption','textarea',File_getInstance(id).caption);
	textarea.setStyles('height:50px;width:200px');
	row.insertCell(0).appendChild(newText(kfm.lang.NewCaption));
	row.insertCell(1).appendChild(textarea);
	kfm_modal_open(table,kfm.lang.ChangeCaption,[[kfm.lang.ChangeCaption,kfm_changeCaption_set]]);
	$('kfm_new_caption').focus();
	*/
}
function kfm_changeCaption_set(){
	var id=$('kfm_newCaptionDetails').kfm_caption_for,newCaption=$('kfm_new_caption').value;
	if(!newCaption||newCaption==File_getInstance(id).caption)return;
	kfm_modal_close();
	if(kfm.confirm(kfm.lang.NewCaptionIsThisCorrect(newCaption))){
		kfm_log(kfm.lang.Log_ChangeCaption(id,newCaption));
		x_kfm_changeCaption(id,newCaption,kfm_refreshFiles);
	}
}
function kfm_resizeImage(id){
	var data=File_getInstance(id);
	var txt=kfm.lang.CurrentSize(data.width,data.height);
	kfm_prompt(txt+kfm.lang.NewWidth,data.width,function(x){
		x=parseInt(x);
		if(!x)return;
		txt+=kfm.lang.NewWidthConfirmTxt(x);
		kfm_prompt(txt+kfm.lang.NewHeight,Math.ceil(data.height*(x/data.width)),function(y){
			y=parseInt(y);
			if(!y)return;
			if(kfm.confirm(txt+kfm.lang.NewHeightConfirmTxt(y)))x_kfm_resizeImage(id,x,y,kfm_refreshFiles);
		});
	});
}
/* should have been moved to the cropper plugin
function kfm_cropImage(id){
	var data=File_getInstance(id);
	var div=document.createElement('DIV');
	div.style.position='absolute';
	div.id='cropperdiv';
	div.style.top=0;
	div.style.left=0;
	div.style.width='100%';
	div.style.height='100%';
	div.style.backgroundColor='#ddf';
	div.onclick=function(){this.style.display='none';}

	var ifr = document.createElement('IFRAME');
	ifr.src = 'plugins/cropper/croparea.php?id='+id+'&width='+data.width+'&height='+data.height;
	ifr.style.width = '100%';
	ifr.style.height = '100%'; //100% - 25px
	div.appendChild(ifr);
	document.body.appendChild(div);
}
function kfm_cropToOriginal(id,coords,dimensions){
	var F=File_getInstance(id);
	document.getElementById('cropperdiv').style.display = 'none';
	x_kfm_cropToOriginal(id, coords.x1, coords.y1, dimensions.width, dimensions.height, function(id){
		if($type(id)=='string')return kfm_log(id);
		F.setThumbnailBackground($('kfm_file_icon_'+id),true);
	});
}
function kfm_cropToNew(id, coords, dimensions){
	var filename=File_getInstance(id).name;
	kfm_prompt(kfm.lang.RenameFileToWhat(filename),filename,function(newName){
		if(!newName||newName==filename)return;
		document.getElementById('cropperdiv').style.display = 'none';
		x_kfm_cropToNew(id, coords.x1, coords.y1, dimensions.width, dimensions.height, newName, kfm_refreshFiles);
	});
}
*/
function kfm_returnThumbnail(id,size){
	if(!size)size='64x64';
	valid=1;
	kfm_prompt(kfm.lang.WhatMaximumSize,size,function(size){
		if(!size)return;
		if(!/^[0-9]+x[0-9]+$/.test(size)){
			alert('The size must be in the format XXxYY, where X is the width and Y is the height');
			valid=0;
		}
		if(!valid)return kfm_returnThumbnail(id,size);
		var x=size.replace(/x.*/,''),y=size.replace(/.*x/,'');
		x_kfm_getFileUrl(id,x,y,function(url){
			if(kfm_file_handler=='return'||kfm_file_handler=='fckeditor'){
				window.opener.SetUrl(url,0,0,File_getInstance(id).caption);
				window.close();
			}
			else if(kfm_file_handler=='download'){
				if(/get.php/.test(url))url+='&forcedownload=1';
				document.location=url;
			}
		});
	});
}
