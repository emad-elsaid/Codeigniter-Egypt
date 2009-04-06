/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


if(!dojo._hasResource["docs.widget.CodeViewer"]){
dojo._hasResource["docs.widget.CodeViewer"]=true;
dojo.provide("docs.widget.CodeViewer");
dojo.require("dojox.highlight");
dojo.require("dojox.highlight.languages._all");
dojo.require("dojox.layout.ContentPane");
dojo.require("dijit._Templated");
dojo.require("dijit._Widget");
dojo.declare("docs.widget.CodeViewer",[dijit._Widget,dijit._Templated],{templateString:"<div dojoAttachPoint=\"outer\" class=\"codeBlock\">\n\t<div class=\"codeHidden\" dojoType=\"dojox.layout.ContentPane\" parseOnLoad=\"false\" dojoAttachPoint=\"codeExecuted\"></div>\n\t<div style=\"position: relative;\">\n\t\t<div class=\"iconpreview\"></div>\n\t\t<div class=\"codeExample\" dojoType=\"dojox.layout.ContentPane\" parseOnLoad=\"false\" renderStyles=\"true\" dojoAttachPoint=\"codeView\"></div>\t\t\t\t\n\t</div>\n\t<div class=\"sourceElement\">\n\t\t<div dojoAttachPoint=\"toggleSource\" class=\"iconsource\"></div>\n\t\t<div dojoAttachPoint=\"codeSource\" class=\"codeSource\" >\n\t\t\t<pre><code class=\"${codeClass}\" dojoAttachPoint=\"containerNode\"></code></pre>\n\t\t</div>\n\t</div>\n<div>\n",widgetsInTemplate:true,codeClass:"javascript",displayCode:true,sourceCode:true,sourceIsOpen:false,postCreate:function(){
var _1=dojo.query("pre",this.containerNode);
var w=dojo.isIE?"innerText":"innerHTML";
var _3=(_1[0]?_1[0][w]:this.containerNode[w]);
if(this.displayCode){
var _4,_5=dojo.doc.createElement("textarea");
(dojo.isIE?_5.innerText=_3:_5.innerHTML=_3);
_4=_5.value;
this.codeView.attr("content",_4);
this.codeView.onLoadDeferred.addCallback(dojo.hitch(this,function(){
dojo.addOnLoad(dojo.hitch(this,function(){
dojo.parser.parse(this.codeView.domNode);
}));
}));
}else{
dojo.style(this.codeView.domNode,"display","none");
}
if(this.sourceCode){
dojox.highlight.init(this.containerNode);
dojo.connect(this.toggleSource,"onclick",this,"toggleSourceView");
if(!this.sourceIsOpen){
this.toggleSourceView();
}
}else{
dojo.style(this.codeSource,"display","none");
dojo.style(this.toggleSource,"display","none");
}
if(!this.displayCode&&this.sourceCode){
dojo.style(this.toggleSource,"display","none");
}
},toggleSourceView:function(){
if(dojo.style(this.codeSource,"display")=="none"){
dojo.style(this.codeSource,"display","");
dojo.style(this.toggleSource,"borderBottomWidth","0px");
}else{
dojo.style(this.codeSource,"display","none");
dojo.style(this.toggleSource,"borderBottomWidth","1px");
}
}});
}
