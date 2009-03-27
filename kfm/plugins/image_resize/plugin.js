function plugin_image_resize(){
	this.name='image_resize',
	this.title=kfm.lang.ResizeImage,
	this.mode=0;//single files
	this.writable=1;//writable files
	this.category='edit';
	this.extensions=['jpg','png','gif'];
	this.doFunction=function(files){
		var id=files[0];
		var data=File_getInstance(id);
		var txt=kfm.lang.CurrentSize(data.width,data.height);
		kfm_prompt(txt+kfm.lang.NewWidth,data.width,function(x){
			x=parseInt(x);
			if(!x)return;
			txt+=kfm.lang.NewWidthConfirmTxt(x);
			kfm_prompt(txt+kfm.lang.NewHeight,Math.ceil(data.height*(x/data.width)),function(y){
				y=parseInt(y);
				if(!y)return;
				if(kfm.confirm(txt+kfm.lang.NewHeightConfirmTxt(y))){
					kfm_fileLoader(id);
					//x_kfm_resizeImage(id,x,y,kfm_refreshFiles);
					x_kfm_resizeImage(id,x,y,function(){
						x_kfm_getFileDetails(id,File_setData);
						$j('#kfm_file_icon_'+data.id).css('background-image','url('+data.icon_url+')');
					});
				}
			});
		});
	}
}
if(kfm_vars.permissions.file.ed&&kfm_vars.permissions.image.manip) kfm_addHook(new plugin_image_resize());
