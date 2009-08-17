// see license.txt for licensing
function kfm_resizeHandler(){
	var w=window.getSize().size;
	for(var i=0;i<kfm_resizeHandler_maxHeights.length;++i)if($(kfm_resizeHandler_maxHeights[i]))$(kfm_resizeHandler_maxHeights[i]).setStyle('height',w.y);
	for(var i=0;i<kfm_resizeHandler_maxWidths.length;++i)if($(kfm_resizeHandler_maxWidths[i]))$(kfm_resizeHandler_maxWidths[i]).setStyle('width',w.x);
	var el=$('kfm_codepressTableCell');
	if(el){
		var iframe=$E('iframe',el);
		if(iframe){
			iframe.style.height=0;
			iframe.style.width=0;
			iframe.style.height=(el.offsetHeight-10)+'px';
			iframe.style.width=(el.offsetWidth-10)+'px';
		}
	}
	kfm_refreshPanels('kfm_left_column');
	var els=$ES('body *');
	els.each(function(el){
		if(el.parentResized)el.parentResized();
	});
}
function kfm_resizeHandler_add(name){
	kfm_resizeHandler_addMaxWidth(name);
	kfm_resizeHandler_addMaxHeight(name);
}
function kfm_resizeHandler_addMaxHeight(name){
	if(!kfm_resizeHandler_maxHeights.contains(name))kfm_resizeHandler_maxHeights.push(name);
}
function kfm_resizeHandler_addMaxWidth(name){
	if(!kfm_resizeHandler_maxWidths.contains(name))kfm_resizeHandler_maxWidths.push(name);
}
function kfm_resizeHandler_remove(name){
	kfm_resizeHandler_removeMaxWidth(name);
	kfm_resizeHandler_removeMaxHeight(name);
}
function kfm_resizeHandler_removeMaxHeight(name){
	if(!kfm_resizeHandler_maxHeights.contains(name))kfm_resizeHandler_maxHeights.remove(name);
}
function kfm_resizeHandler_removeMaxWidth(name){
	if(!kfm_resizeHandler_maxWidths.contains(name))kfm_resizeHandler_maxWidths.remove(name);
}
var kfm_resizeHandler_maxHeights=[];
var kfm_resizeHandler_maxWidths=[];
