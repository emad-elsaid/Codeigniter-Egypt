function plugin_image_resize_multiple(){
	this.name='image_resize',
	this.title='Resize Images',
	this.mode=1;//single files
	this.writable=1;//writable files
	this.category='edit';
	this.extensions=['jpg','png','gif'];
	this.doFunction=function(files){
		var id=files[0];
		var imgs=0,width=0;height=0,imgfiles=[];
		{ // figure out average width/height
			for(var i=0;i<files.length;++i){
				var data=File_getInstance(files[i]);
				if(!data.width || !data.height)continue;
				width+=data.width;
				height+=data.height;
				++imgs;
				imgfiles.push(files[i]);
			}
			if(!imgs)return;
			width=+(width/imgs);
			height=+(height/imgs);
		}
		var txt=kfm.lang.CurrentSize(width,height);
		kfm_prompt(txt+kfm.lang.NewWidth,width,function(x){
			x=parseInt(x);
			if(!x)return;
			txt+=kfm.lang.NewWidthConfirmTxt(x);
			kfm_prompt(txt+kfm.lang.NewHeight,Math.ceil(height*(x/width)),function(y){
				y=parseInt(y);
				if(!y)return;
				if(kfm.confirm(txt+kfm.lang.NewHeightConfirmTxt(y))){
					kfm_fileLoader(imgfiles);
					x_kfm_resizeImages(imgfiles,x,y,function(){
						imgfiles.each(function(id){
							var data=File_getInstance(id);
							x_kfm_getFileDetails(id,File_setData);
							$j('#kfm_file_icon_'+id).css('background-image','url('+data.icon_url+')');
						});
					});
				}
			});
		});
	}
}
if(kfm_vars.permissions.file.ed&&kfm_vars.permissions.image.manip) kfm_addHook(new plugin_image_resize_multiple());
