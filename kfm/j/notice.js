var Notice=new Class({
	getWrapper:function(){
		var w=$('notice_wrapper');
		if(w)return w;
		w=new Element('div',{
			'id':'notice_wrapper',
			'styles':{
				'position':'absolute',
				'top':5,
				'right':5,
				'z-index':222
			}
		});
		document.body.appendChild(w);
		return w;
	},
	initialize:function(message){
		var id=_Notices++;
		this.id=id;
		var notice_message=new Element('div',{
			'id':'notice_message_'+id,
			'class':'notice'
		});
		notice_message.setHTML(message);
		this.getWrapper().appendChild(notice_message);
		var myFx=new Fx.Style(notice_message,'opacity',{'duration':3500});
		myFx.start(1,0).chain(function(){
			var myFx2=new Fx.Style(notice_message,'height');
			myFx2.start(notice_message.offsetHeight-parseInt(notice_message.getStyle('padding-top'))-parseInt(notice_message.getStyle('padding-bottom')),0).chain(function(){
				notice_message.remove();
				var w=$('notice_wrapper');
				if(!w.childNodes.length)w.remove();
			});
		});
	}
});
var _Notices=0;
