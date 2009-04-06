/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


if(!dojo._hasResource["docs.widget.CompoundViewer"]){
dojo._hasResource["docs.widget.CompoundViewer"]=true;
dojo.provide("docs.widget.CompoundViewer");
dojo.require("dojox.highlight");
dojo.require("dojox.highlight.languages._all");
dojo.require("dojox.layout.ContentPane");
dojo.require("docs.widget.CodeViewer");
dojo.require("dijit._Templated");
dojo.require("dijit._Widget");
dojo.require("dijit.form.CheckBox");
dojo.declare("docs.widget.Blank",null,{});
dojo.declare("docs.widget.CompoundViewer",[dijit._Widget,dijit._Templated],{templateString:"<div dojoAttachPoint=\"outerNode\" class=\"compoundContainer\">\n\t<div class=\"themeSelector\" dojoAttachPoint=\"themeSelector\"></div>\n\t<div dojoAttachPoint=\"executedNode\"></div>\n\t<div dojoAttachPoint=\"contentNode\"></div>\n</div>\n",widgetsInTemplate:false,preview:{},createFullSource:true,themes:["tundra","nihilo","soria"],defaultTheme:"nihilo",dojoPath:"../../../../",def:{javascript:{displayCode:false,sourceCode:true},css:{displayCode:false,sourceCode:true},html:{displayCode:true,sourceCode:true}},hideCode:true,constructor:function(){
this.arrNodes=[];
this.arrExecuted=[];
this.arrViews=[];
},postMixInProperties:function(){
var el=this.srcNodeRef.firstChild;
var _2=[];
var _3={};
_3.content=[];
var _4=0;
while(el){
if(dojo.attr(el,"dojoType")!=null){
var _5="";
if(dojo.attr(el,"label")){
_5=dojo.attr(el,"label");
}
_3={"content":_3.content,"label":_5,"lang":el.lang,"code":el,"index":_4};
this.arrNodes[el.lang]=_3;
_3={"lang":"","content":[],"code":""};
el.removeAttribute("dojoType");
_4++;
}else{
_3.content.push(el);
}
el=el.nextSibling;
}
},postCreate:function(){
if(this.arrNodes.css){
this._executeCode("css");
this._buildCodeView("css");
}
if(this.arrNodes.javascript){
this._executeCode("javascript");
this._buildCodeView("javascript");
}
for(node in this.arrNodes){
this._buildCodeView(node);
}
if(this.preview.code){
var _6=dojo.doc.createElement("div");
dojo.addClass(_6,"compoundElement");
dojo.addClass(_6,"compoundPreview");
this.contentNode.appendChild(_6);
var _7=dojo.doc.createElement("div");
dojo.addClass(_7,"iconpreview");
_6.appendChild(_7);
var _8=dojo.doc.createElement("div");
var _9=dojo.doc.createElement("div");
_8.appendChild(_9);
_6.appendChild(_8);
_9.appendChild(this.preview.code);
dojo.connect(_7,"onclick",dojo.hitch(this,function(_a){
if(dojo.style(_a,"display")=="none"){
dojo.style(_a,"display","");
}else{
dojo.style(_a,"display","none");
}
},_8));
dojo.forEach(this.themes,function(_b){
this[_b+"_themeSelector"]=new dijit.form.RadioButton({name:this.id+"_ThemeSelector",id:_b+"_"+this.id+"_themeSelector"});
var _c=dojo.doc.createElement("label");
dojo.attr(_c,"for",_b+"_"+this.id+"_themeSelector");
_c.innerHTML=_b;
dojo.connect(this[_b+"_themeSelector"],"onClick",this,function(e){
(function(_e,_f){
window.location="?t="+_e.id.split("_")[0];
})(this[_b+"_themeSelector"],this);
});
this.themeSelector.appendChild(this[_b+"_themeSelector"].domNode);
this.themeSelector.appendChild(_c);
},this);
this.setTheme();
}
dojo.forEach(this.arrViews,function(_10){
var _11=dojo.doc.createElement("div");
dojo.addClass(_11,"compoundElement");
this.contentNode.appendChild(_11);
var _12=dojo.doc.createElement("div");
dojo.addClass(_12,"icon"+_10.type);
_11.appendChild(_12);
var _13=dojo.doc.createElement("div");
_11.appendChild(_13);
if(this.hideCode){
dojo.style(_13,"display","none");
}
dojo.connect(_12,"onclick",dojo.hitch(this,function(_14){
if(dojo.style(_14,"display")=="none"){
dojo.style(_14,"display","");
}else{
dojo.style(_14,"display","none");
}
},_13));
if(_10.label){
var _15=dojo.doc.createElement("h3");
_15.innerHTML=_10.label;
dojo.addClass(_15,"compoundLabel");
_13.appendChild(_15);
}
if(_10.content){
var _16=dojo.doc.createElement("div");
dojo.addClass(_16,"compoundText");
dojo.forEach(_10.content,function(_17){
_16.appendChild(_17);
},this);
_13.appendChild(_16);
}
dojo.addClass(_10.code.domNode,"compoundCode");
_13.appendChild(_10.code.domNode);
},this);
if(this.createFullSource){
this._buildFullSource();
}
if(_9){
var _18=new docs.widget.CodeViewer({codeClass:"html",displayCode:true,sourceCode:false,sourceIsOpen:true},_9);
}
delete this.arrNodes;
},setTheme:function(){
this[this.defaultTheme+"_themeSelector"].attr("checked",true);
if(window.location.href.indexOf("?")>-1){
var str=window.location.href.substr(window.location.href.indexOf("?")+1).split(/#/);
var ary=str[0].split(/&/);
for(var i=0;i<ary.length;i++){
var _1c=ary[i].split(/=/),key=_1c[0],_1e=_1c[1];
switch(key){
case "t":
dojo.attr(dojo.byId("themeCss"),"href",this.dojoPath+"dijit/themes/"+_1e+"/"+_1e+".css");
dojo.body().className=_1e;
this[_1e+"_themeSelector"].attr("checked",true);
break;
}
}
}
},_executeCode:function(_1f){
var _20=dojo.doc.createElement("div");
this.executedNode.appendChild(_20);
var ret,_22=document.createElement("textarea");
if(dojo.isIE){
_22.innerText=dojo.query("pre",this.arrNodes[_1f].code)[0].innerText;
}else{
_22.innerHTML=dojo.query("pre",this.arrNodes[_1f].code)[0].innerHTML;
}
ret=_22.value;
this.arrExecuted[_1f]=new dojox.layout.ContentPane({"class":"codeHidden",renderStyles:"true"},_20);
this.arrExecuted[_1f].attr("content",ret);
this.arrExecuted[_1f].onLoadDeferred.addCallback(dojo.hitch(this,function(){
dojo.addOnLoad(dojo.hitch(this,function(){
dojo.parser.parse(this.arrExecuted[_1f].domNode);
}));
}));
},_buildCodeView:function(_23){
if(_23=="html"){
this.preview.code=this.arrNodes[_23].code;
this.def.html.displayCode=false;
}
var _24=dojo.doc.createElement("div");
_24.appendChild(dojo.clone(this.arrNodes[_23].code));
var a=this.arrViews[this.arrNodes[_23].index]=[];
a.type=_23;
a.label=this.arrNodes[_23].label;
a.content=this.arrNodes[_23].content;
a.code=new docs.widget.CodeViewer({codeClass:_23,displayCode:this.def[_23].displayCode,sourceCode:this.def[_23].sourceCode,sourceIsOpen:true},_24);
},_buildFullSource:function(){
var _26=["<html>","<head>","<title>Dojo example</title>","<style type=\"text/css\">","  @import \"pathtodojo/dijit/themes/nihilo/nihilo.css\";","</style>","${extraCSS}","<script type=\"text/javascript\" src=\"pathtodojo/dojo/dojo.js\" djConfig=\"parseOnLoad:true, isDebug: true\"></script>","${extraJs}","<body class=\"nihilo\">","${extraHTML}","</body>","</html>"].join("\n");
messages=[];
messages.extraCSS="";
messages.extraJs="";
messages.extraHTML="";
if(this.arrNodes.css&&this.arrNodes.css.code){
var ret,_28=dojo.doc.createElement("textarea");
if(dojo.isIE){
_28.innerText=dojo.query("pre",this.arrNodes.css.code)[0].innerText;
}else{
_28.innerHTML=dojo.query("pre",this.arrNodes.css.code)[0].innerHTML;
}
ret=_28.value;
messages.extraCSS=ret;
}
if(this.arrNodes.javascript&&this.arrNodes.javascript.code){
var ret,_28=dojo.doc.createElement("textarea");
if(dojo.isIE){
_28.innerText=dojo.query("pre",this.arrNodes.javascript.code)[0].innerText;
}else{
_28.innerHTML=dojo.query("pre",this.arrNodes.javascript.code)[0].innerHTML;
}
ret=_28.value;
messages.extraJs=ret;
}
if(this.arrNodes.html&&this.arrNodes.html.code){
var ret,_28=dojo.doc.createElement("textarea");
if(dojo.isIE){
_28.innerText=dojo.query("pre",this.arrNodes.html.code)[0].innerText;
}else{
_28.innerHTML=dojo.query("pre",this.arrNodes.html.code)[0].innerHTML;
}
ret=_28.value;
messages.extraHTML=ret;
}
var _26=dojo.string.substitute(_26,messages);
var _29=dojo.doc.createElement("div");
dojo.addClass(_29,"compoundElement");
this.contentNode.appendChild(_29);
var _2a=dojo.doc.createElement("div");
dojo.addClass(_2a,"iconcode");
_29.appendChild(_2a);
var _2b=dojo.doc.createElement("div");
_29.appendChild(_2b);
if(this.hideCode){
dojo.style(_2b,"display","none");
}
dojo.connect(_2a,"onclick",dojo.hitch(this,function(_2c){
if(dojo.style(_2c,"display")=="none"){
dojo.style(_2c,"display","");
}else{
dojo.style(_2c,"display","none");
}
},_2b));
var _2d=dojo.doc.createElement("h3");
_2d.innerHTML="Copy the full source";
dojo.addClass(_2d,"compoundLabel");
_2b.appendChild(_2d);
var _2e=dojo.doc.createElement("div");
dojo.addClass(_2e,"compoundText");
var _2f=dojo.doc.createElement("textarea");
dojo.attr(_2f,"rows",10);
dojo.attr(_2f,"cols",100);
_2f.value=_26;
_2e.appendChild(_2f);
_2b.appendChild(_2e);
}});
}
