function plugin_copy_to_clipboard(){
	this.name='copy_to_clipboard';
	this.title='copy to clipboard'; // TODO: String
	this.mode=2;//single files
	this.writable=2;//writable files
	this.category='returning';
	this.extensions='all';
	this.doFunction=function(files){
		kfm_copyToClipboard(files);
	}
}
kfm_addHook(new plugin_copy_to_clipboard());

function kfm_copyToClipboard(files){
	x_kfm_getFileUrls(files,function(urls){
		if(!urls.length)return;
		copy_to_clipboard(urls.join("\n"));
		new Notice("File URLs copied to browser clipboard"); // TODO: String
	});
}
function copy_to_clipboard(text2copy) {
	// function found here: http://webchicanery.com/2006/11/14/clipboard-copy-javascript/
	if (window.clipboardData) {
		window.clipboardData.setData("Text",text2copy);
	} else {
		var flashcopier = 'flashcopier';
		if(!document.getElementById(flashcopier)) {
			var divholder = document.createElement('div');
			divholder.id = flashcopier;
			document.body.appendChild(divholder);
		}
		document.getElementById(flashcopier).innerHTML = '';
		var divinfo = '<embed src="plugins/copy_to_clipboard/_clipboard.swf" FlashVars="clipboard='+escape(text2copy)+'" width="0" height="0" type="application/x-shockwave-flash"></embed>';
		document.getElementById(flashcopier).innerHTML = divinfo;
	}
}
