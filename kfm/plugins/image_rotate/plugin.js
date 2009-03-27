function plugin_image_rotate(){
	this.name='rotate',
	this.title='rotate',
	this.mode=0;//single files
	this.writable=1;//writable files
	this.category='edit';
	this.extensions=['jpg','png','gif'];
	this.doFunction=function(){}
}
kfm_addHook(new plugin_image_rotate(),{name:'rotate_cw',title:kfm.lang.RotateClockwise,doFunction:function(files){
		kfm_rotateImage(files[0],270);
	}
});
kfm_addHook(new plugin_image_rotate(),{name:'rotate_ccw',title:kfm.lang.RotateAntiClockwise,doFunction:function(files){
		kfm_rotateImage(files[0],90);
	}
});
function kfm_rotateImage(id,direction){
	var F=File_getInstance(id);
	kfm_fileLoader(id);
	x_kfm_rotateImage(id,direction,function(id){
		if($type(id)=='string')return kfm_log(id);
		F.setThumbnailBackground($('kfm_file_icon_'+id),true);
	});
}
