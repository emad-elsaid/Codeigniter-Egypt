function kfm_modal_close(msg){
	$('shader').remove();
	$('formWrapper').remove();
	if(msg)alert(msg);
}
function kfm_modal_open(form,title,actions){
	window.inPrompt=1;
	var body=document.body,scrollAt=window.ie?getWindowScrollAt():{x:0,y:0},a=window.getSize().size,wx=0,wy=0,pos=window.ie?'absolute':'fixed',i;
	if(window.ie)body.setStyles({
		'overflow':'hidden'
	});
	{ // shader
		var shader=new Element('div',{
			'id':'shader',
			'styles':{
				'background':'#fff',
				'opacity':'.5',
				'position':pos,
				'top':scrollAt.y,
				'left':scrollAt.x,
				'z-index':2,
				'width':a.x,
				'height':a.y
			}
		});
		body.appendChild(shader);
	}
	{ // wrapper
		var wrapper=new Element('div',{
			'id'   :'formWrapper',
			'class':'modal_dialog drag_this'
		});
		var h2=(new Element('h2')).setHTML(title);
		h2.className='prompt';
		form.setStyles({
			'position':'relative',
			'margin':0,
			'text-align':'left',
			'padding':0,
			'clear':'left'
		});
		wrapper.appendChild(h2);
		wrapper.appendChild(form);
		{ // link row
			var row=new Element('div');
			var link=new Element('a',{
				'href':'javascript:kfm_modal_close()'
			}).appendText(kfm.lang.Cancel);
			link.className='button';
			row.appendChild(link);
			if(actions&&actions.length)for(i=0;i<actions.length;++i){
				var v=actions[i];
				link=new Element('a',{
					'href':'#',
					'events':{
						'click':v[1]
					}
				}).appendText(v[0]);
				link.className='button';
				row.appendChild(link);
			}
			wrapper.appendChild(row);
		}
		row.setStyles({
			'background':'#eee',
			'border-top':'1px solid #ddd',
			'text-align':'right',
			'padding':'2px',
			'z-index':'3'
		});
		body.appendChild(wrapper);
		wrapper.style.width=(form.offsetWidth+10)+'px';
		var w=wrapper.offsetWidth;
		if(w<200||w>a.x*.9){
			w=w<200?200:parseInt(a.x*.9);
			wrapper.setStyles({
				'width':w
			});
		}
		var h=window.ie?wrapper.offsetHeight:h2.offsetHeight+form.offsetHeight+row.offsetHeight,q=window.ie?1:0,r=window.ie?0:4;
		if(parseFloat(h)>parseFloat(a.y*.9)){
			h=parseInt(a.y*.9);
			var h3=h-row.offsetHeight-h2.offsetHeight-q;
			form.setStyles({
				'margin':'0 auto',
				'overflow':'auto',
				'height':h3,
				'max-height':h3
			});
		}else{
			var h3=h-row.offsetHeight-h2.offsetHeight-q;
			form.setStyles({
				'overflow':'auto',
				'width':'100%',
				'max-height':h3
			});
		}
		wrapper.setStyles({
			'position':pos,
			'left':scrollAt.x+a.x/2-w/2,
			'top':scrollAt.y+a.y/2-h/2,
			'z-index':3
		});
	}
	wrapper.addEvent('keyup',function(e){
		e=new Event(e);
		e.stop();
	});
	kfm_resizeHandler_add('shader');
	kdnd_makeDraggable('modal_dialog');
}
