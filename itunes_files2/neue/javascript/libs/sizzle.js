/*!
 * Sizzle CSS Selector Engine
 *  Copyright 2012, The Dojo Foundation
 *  Released under the MIT, BSD, and GPL Licenses.
 *  More information: http://sizzlejs.com/
 */
(function(aa,s){var af,A,r,d,k,i=aa.document,l=i.documentElement,I="undefined",m=false,j=true,q=0,v=[].slice,ae=[].push,ai=("sizcache"+Math.random()).replace(".",""),L="[\\x20\\t\\r\\n\\f]",u="(?:\\\\.|[-\\w]|[^\\x00-\\xa0])",t="(?:[\\w#_-]|[^\\x00-\\xa0]|\\\\.)",an="([*^$|!~]?=)",X="\\["+L+"*("+u+"+)"+L+"*(?:"+an+L+"*(?:(['\"])((?:\\\\.|[^\\\\])*?)\\3|("+t+"+)|)|)"+L+"*\\]",ao=":("+u+"+)(?:\\((?:(['\"])((?:\\\\.|[^\\\\])*?)\\2|(.*))\\)|)",N=":(nth|eq|gt|lt|first|last|even|odd)(?:\\((\\d*)\\)|)(?=[^-]|$)",p=L+"*([\\x20\\t\\r\\n\\f>+~])"+L+"*",o="(?=[^\\x20\\t\\r\\n\\f])(?:\\\\.|"+X+"|"+ao.replace(2,7)+"|[^\\\\(),])+",ag=new RegExp("^"+L+"+|((?:^|[^\\\\])(?:\\\\.)*)"+L+"+$","g"),R=new RegExp("^"+p),F=new RegExp(o+"?(?="+L+"*,|$)","g"),V=new RegExp("^(?:(?!,)(?:(?:^|,)"+L+"*"+o+")*?|"+L+"*(.*?))(\\)|$)"),al=new RegExp(o.slice(19,-6)+"\\x20\\t\\r\\n\\f>+~])+|"+p,"g"),W=/^(?:#([\w\-]+)|(\w+)|\.([\w\-]+))$/,ab=/[\x20\t\r\n\f]*[+~]/,aj=/:not\($/,B=/h\d/i,Y=/input|select|textarea|button/i,E=/\\(?!\\)/g,Q={ID:new RegExp("^#("+u+"+)"),CLASS:new RegExp("^\\.("+u+"+)"),NAME:new RegExp("^\\[name=['\"]?("+u+"+)['\"]?\\]"),TAG:new RegExp("^("+u.replace("[-","[-\\*")+"+)"),ATTR:new RegExp("^"+X),PSEUDO:new RegExp("^"+ao),CHILD:new RegExp("^:(only|nth|last|first)-child(?:\\("+L+"*(even|odd|(([+-]|)(\\d*)n|)"+L+"*(?:([+-]|)"+L+"*(\\d+)|))"+L+"*\\)|)","i"),POS:new RegExp(N,"ig"),needsContext:new RegExp("^"+L+"*[>+~]|"+N,"i")},ad={},C=[],x={},G=[],ak=function(e){e.sizzleFilter=true;
return e},f=function(e){return function(ap){return ap.nodeName.toLowerCase()==="input"&&ap.type===e
}},D=function(e){return function(aq){var ap=aq.nodeName.toLowerCase();return(ap==="input"||ap==="button")&&aq.type===e
}},T=function(ap){var aq=false,at=i.createElement("div");try{aq=ap(at)}catch(ar){}at=null;
return aq},z=T(function(ap){ap.innerHTML="<select></select>";var e=typeof ap.lastChild.getAttribute("multiple");
return e!=="boolean"&&e!=="string"}),b=T(function(ap){ap.id=ai+0;ap.innerHTML="<a name='"+ai+"'></a><div name='"+ai+"'></div>";
l.insertBefore(ap,l.firstChild);var e=i.getElementsByName&&i.getElementsByName(ai).length===2+i.getElementsByName(ai+0).length;
k=!i.getElementById(ai);l.removeChild(ap);return e}),h=T(function(e){e.appendChild(i.createComment(""));
return e.getElementsByTagName("*").length===0}),P=T(function(e){e.innerHTML="<a href='#'></a>";
return e.firstChild&&typeof e.firstChild.getAttribute!==I&&e.firstChild.getAttribute("href")==="#"
}),O=T(function(e){e.innerHTML="<div class='hidden e'></div><div class='hidden'></div>";
if(!e.getElementsByClassName||e.getElementsByClassName("e").length===0){return false
}e.lastChild.className="e";return e.getElementsByClassName("e").length!==1});var Z=function(ar,e,au,ax){au=au||[];
e=e||i;var av,ap,aw,aq,at=e.nodeType;if(at!==1&&at!==9){return[]}if(!ar||typeof ar!=="string"){return au
}aw=w(e);if(!aw&&!ax){if((av=W.exec(ar))){if((aq=av[1])){if(at===9){ap=e.getElementById(aq);
if(ap&&ap.parentNode){if(ap.id===aq){au.push(ap);return au}}else{return au}}else{if(e.ownerDocument&&(ap=e.ownerDocument.getElementById(aq))&&M(e,ap)&&ap.id===aq){au.push(ap);
return au}}}else{if(av[2]){ae.apply(au,v.call(e.getElementsByTagName(ar),0));return au
}else{if((aq=av[3])&&O&&e.getElementsByClassName){ae.apply(au,v.call(e.getElementsByClassName(aq),0));
return au}}}}}return ah(ar,e,au,ax,aw)};var S=Z.selectors={cacheLength:50,match:Q,order:["ID","TAG"],attrHandle:{},createPseudo:ak,find:{ID:k?function(ar,aq,ap){if(typeof aq.getElementById!==I&&!ap){var e=aq.getElementById(ar);
return e&&e.parentNode?[e]:[]}}:function(ar,aq,ap){if(typeof aq.getElementById!==I&&!ap){var e=aq.getElementById(ar);
return e?e.id===ar||typeof e.getAttributeNode!==I&&e.getAttributeNode("id").value===ar?[e]:s:[]
}},TAG:h?function(e,ap){if(typeof ap.getElementsByTagName!==I){return ap.getElementsByTagName(e)
}}:function(e,at){var ar=at.getElementsByTagName(e);if(e==="*"){var au,aq=[],ap=0;
for(;(au=ar[ap]);ap++){if(au.nodeType===1){aq.push(au)}}return aq}return ar}},relative:{">":{dir:"parentNode",first:true}," ":{dir:"parentNode"},"+":{dir:"previousSibling",first:true},"~":{dir:"previousSibling"}},preFilter:{ATTR:function(e){e[1]=e[1].replace(E,"");
e[3]=(e[4]||e[5]||"").replace(E,"");if(e[2]==="~="){e[3]=" "+e[3]+" "}return e.slice(0,4)
},CHILD:function(e){e[1]=e[1].toLowerCase();if(e[1]==="nth"){if(!e[2]){Z.error(e[0])
}e[3]=+(e[3]?e[4]+(e[5]||1):2*(e[2]==="even"||e[2]==="odd"));e[4]=+((e[6]+e[7])||e[2]==="odd")
}else{if(e[2]){Z.error(e[0])}}return e},PSEUDO:function(e){var ap,aq=e[4];if(Q.CHILD.test(e[0])){return null
}if(aq&&(ap=V.exec(aq))&&ap.pop()){e[0]=e[0].slice(0,ap[0].length-aq.length-1);
aq=ap[0].slice(0,-1)}e.splice(2,3,aq||e[3]);return e}},filter:{ID:k?function(e){e=e.replace(E,"");
return function(ap){return ap.getAttribute("id")===e}}:function(e){e=e.replace(E,"");
return function(aq){var ap=typeof aq.getAttributeNode!==I&&aq.getAttributeNode("id");
return ap&&ap.value===e}},TAG:function(e){if(e==="*"){return function(){return true
}}e=e.replace(E,"").toLowerCase();return function(ap){return ap.nodeName&&ap.nodeName.toLowerCase()===e
}},CLASS:function(e){var ap=ad[e];if(!ap){ap=ad[e]=new RegExp("(^|"+L+")"+e+"("+L+"|$)");
C.push(e);if(C.length>S.cacheLength){delete ad[C.shift()]}}return function(aq){return ap.test(aq.className||(typeof aq.getAttribute!==I&&aq.getAttribute("class"))||"")
}},ATTR:function(aq,ap,e){if(!ap){return function(ar){return Z.attr(ar,aq)!=null
}}return function(at){var ar=Z.attr(at,aq),au=ar+"";if(ar==null){return ap==="!="
}switch(ap){case"=":return au===e;case"!=":return au!==e;case"^=":return e&&au.indexOf(e)===0;
case"*=":return e&&au.indexOf(e)>-1;case"$=":return e&&au.substr(au.length-e.length)===e;
case"~=":return(" "+au+" ").indexOf(e)>-1;case"|=":return au===e||au.substr(0,e.length+1)===e+"-"
}}},CHILD:function(ap,ar,at,aq){if(ap==="nth"){var e=q++;return function(ax){var au,ay,aw=0,av=ax;
if(at===1&&aq===0){return true}au=ax.parentNode;if(au&&(au[ai]!==e||!ax.sizset)){for(av=au.firstChild;
av;av=av.nextSibling){if(av.nodeType===1){av.sizset=++aw;if(av===ax){break}}}au[ai]=e
}ay=ax.sizset-aq;if(at===0){return ay===0}else{return(ay%at===0&&ay/at>=0)}}}return function(av){var au=av;
switch(ap){case"only":case"first":while((au=au.previousSibling)){if(au.nodeType===1){return false
}}if(ap==="first"){return true}au=av;case"last":while((au=au.nextSibling)){if(au.nodeType===1){return false
}}return true}}},PSEUDO:function(at,ar,ap,e){var aq=S.pseudos[at]||S.pseudos[at.toLowerCase()];
if(!aq){Z.error("unsupported pseudo: "+at)}if(!aq.sizzleFilter){return aq}return aq(ar,ap,e)
}},pseudos:{not:ak(function(e,aq,ap){var ar=n(e.replace(ag,"$1"),aq,ap);return function(at){return !ar(at)
}}),enabled:function(e){return e.disabled===false},disabled:function(e){return e.disabled===true
},checked:function(e){var ap=e.nodeName.toLowerCase();return(ap==="input"&&!!e.checked)||(ap==="option"&&!!e.selected)
},selected:function(e){if(e.parentNode){e.parentNode.selectedIndex}return e.selected===true
},parent:function(e){return !!e.firstChild},empty:function(e){return !e.firstChild
},contains:ak(function(e){return function(ap){return(ap.textContent||ap.innerText||a(ap)).indexOf(e)>-1
}}),has:ak(function(e){return function(ap){return Z(e,ap).length>0}}),header:function(e){return B.test(e.nodeName)
},text:function(aq){var ap,e;return aq.nodeName.toLowerCase()==="input"&&(ap=aq.type)==="text"&&((e=aq.getAttribute("type"))==null||e.toLowerCase()===ap)
},radio:f("radio"),checkbox:f("checkbox"),file:f("file"),password:f("password"),image:f("image"),submit:D("submit"),reset:D("reset"),button:function(ap){var e=ap.nodeName.toLowerCase();
return e==="input"&&ap.type==="button"||e==="button"},input:function(e){return Y.test(e.nodeName)
},focus:function(e){var ap=e.ownerDocument;return e===ap.activeElement&&(!ap.hasFocus||ap.hasFocus())&&!!(e.type||e.href)
},active:function(e){return e===e.ownerDocument.activeElement}},setFilters:{first:function(aq,ap,e){return e?aq.slice(1):[aq[0]]
},last:function(ar,aq,ap){var e=ar.pop();return ap?ar:[e]},even:function(au,at,ar){var aq=[],ap=ar?1:0,e=au.length;
for(;ap<e;ap=ap+2){aq.push(au[ap])}return aq},odd:function(au,at,ar){var aq=[],ap=ar?0:1,e=au.length;
for(;ap<e;ap=ap+2){aq.push(au[ap])}return aq},lt:function(aq,ap,e){return e?aq.slice(+ap):aq.slice(0,+ap)
},gt:function(aq,ap,e){return e?aq.slice(0,+ap+1):aq.slice(+ap+1)},eq:function(ar,aq,ap){var e=ar.splice(+aq,1);
return ap?ar:e}}};S.setFilters.nth=S.setFilters.eq;S.filters=S.pseudos;if(!P){S.attrHandle={href:function(e){return e.getAttribute("href",2)
},type:function(e){return e.getAttribute("type")}}}if(b){S.order.push("NAME");S.find.NAME=function(e,ap){if(typeof ap.getElementsByName!==I){return ap.getElementsByName(e)
}}}if(O){S.order.splice(1,0,"CLASS");S.find.CLASS=function(aq,ap,e){if(typeof ap.getElementsByClassName!==I&&!e){return ap.getElementsByClassName(aq)
}}}try{v.call(l.childNodes,0)[0].nodeType}catch(am){v=function(ap){var aq,e=[];
for(;(aq=this[ap]);ap++){e.push(aq)}return e}}var w=Z.isXML=function(e){var ap=e&&(e.ownerDocument||e).documentElement;
return ap?ap.nodeName!=="HTML":false};var M=Z.contains=l.compareDocumentPosition?function(ap,e){return !!(ap.compareDocumentPosition(e)&16)
}:l.contains?function(ap,e){var ar=ap.nodeType===9?ap.documentElement:ap,aq=e.parentNode;
return ap===aq||!!(aq&&aq.nodeType===1&&ar.contains&&ar.contains(aq))}:function(ap,e){while((e=e.parentNode)){if(e===ap){return true
}}return false};var a=Z.getText=function(at){var ar,ap="",aq=0,e=at.nodeType;if(e){if(e===1||e===9||e===11){if(typeof at.textContent==="string"){return at.textContent
}else{for(at=at.firstChild;at;at=at.nextSibling){ap+=a(at)}}}else{if(e===3||e===4){return at.nodeValue
}}}else{for(;(ar=at[aq]);aq++){ap+=a(ar)}}return ap};Z.attr=function(ar,aq){var e,ap=w(ar);
if(!ap){aq=aq.toLowerCase()}if(S.attrHandle[aq]){return S.attrHandle[aq](ar)}if(z||ap){return ar.getAttribute(aq)
}e=ar.getAttributeNode(aq);return e?typeof ar[aq]==="boolean"?ar[aq]?aq:null:e.specified?e.value:null:null
};Z.error=function(e){throw new Error("Syntax error, unrecognized expression: "+e)
};[0,0].sort(function(){return(j=0)});if(l.compareDocumentPosition){r=function(ap,e){if(ap===e){m=true;
return 0}return(!ap.compareDocumentPosition||!e.compareDocumentPosition?ap.compareDocumentPosition:ap.compareDocumentPosition(e)&4)?-1:1
}}else{r=function(ay,ax){if(ay===ax){m=true;return 0}else{if(ay.sourceIndex&&ax.sourceIndex){return ay.sourceIndex-ax.sourceIndex
}}var av,aq,ar=[],e=[],au=ay.parentNode,aw=ax.parentNode,az=au;if(au===aw){return d(ay,ax)
}else{if(!au){return -1}else{if(!aw){return 1}}}while(az){ar.unshift(az);az=az.parentNode
}az=aw;while(az){e.unshift(az);az=az.parentNode}av=ar.length;aq=e.length;for(var at=0;
at<av&&at<aq;at++){if(ar[at]!==e[at]){return d(ar[at],e[at])}}return at===av?d(ay,e[at],-1):d(ar[at],ax,1)
};d=function(ap,e,aq){if(ap===e){return aq}var ar=ap.nextSibling;while(ar){if(ar===e){return -1
}ar=ar.nextSibling}return 1}}Z.uniqueSort=function(ap){var aq,e=1;if(r){m=j;ap.sort(r);
if(m){for(;(aq=ap[e]);e++){if(aq===ap[e-1]){ap.splice(e--,1)}}}}return ap};function y(ap,au,at,aq){var ar=0,e=au.length;
for(;ar<e;ar++){Z(ap,au[ar],at,aq)}}function U(e,aq,av,aw,ap,au){var ar,at=S.setFilters[aq.toLowerCase()];
if(!at){Z.error(aq)}if(e||!(ar=ap)){y(e||"*",aw,(ar=[]),ap)}return ar.length>0?at(ar,av,au):[]
}function ac(az,e,ax,aq,aD){var au,ap,at,aF,aw,aE,ay,aC,aA=0,aB=aD.length,ar=Q.POS,av=new RegExp("^"+ar.source+"(?!"+L+")","i"),aG=function(){var aI=1,aH=arguments.length-2;
for(;aI<aH;aI++){if(arguments[aI]===s){au[aI]=s}}};for(;aA<aB;aA++){ar.exec("");
az=aD[aA];aF=[];at=0;aw=aq;while((au=ar.exec(az))){aC=ar.lastIndex=au.index+au[0].length;
if(aC>at){ay=az.slice(at,au.index);at=aC;aE=[e];if(R.test(ay)){if(aw){aE=aw}aw=aq
}if((ap=aj.test(ay))){ay=ay.slice(0,-5).replace(R,"$&*")}if(au.length>1){au[0].replace(av,aG)
}aw=U(ay,au[1],au[2],aE,aw,ap)}}if(aw){aF=aF.concat(aw);if((ay=az.slice(at))&&ay!==")"){y(ay,aF,ax,aq)
}else{ae.apply(ax,aF)}}else{Z(az,e,ax,aq)}}return aB===1?ax:Z.uniqueSort(ax)}function c(av,aq,ay){var aA,az,aB,at=[],aw=0,ax=V.exec(av),ap=!ax.pop()&&!ax.pop(),aC=ap&&av.match(F)||[""],e=S.preFilter,ar=S.filter,au=!ay&&aq!==i;
for(;(az=aC[aw])!=null&&ap;aw++){at.push(aA=[]);if(au){az=" "+az}while(az){ap=false;
if((ax=R.exec(az))){az=az.slice(ax[0].length);ap=aA.push({part:ax.pop().replace(ag," "),captures:ax})
}for(aB in ar){if((ax=Q[aB].exec(az))&&(!e[aB]||(ax=e[aB](ax,aq,ay)))){az=az.slice(ax.shift().length);
ap=aA.push({part:aB,captures:ax})}}if(!ap){break}}}if(!ap){Z.error(av)}return at
}function J(at,ar,aq){var e=ar.dir,ap=q++;if(!at){at=function(au){return au===aq
}}return ar.first?function(av,au){while((av=av[e])){if(av.nodeType===1){return at(av,au)&&av
}}}:function(aw,av){var au,ax=ap+"."+A,ay=ax+"."+af;while((aw=aw[e])){if(aw.nodeType===1){if((au=aw[ai])===ay){return false
}else{if(typeof au==="string"&&au.indexOf(ax)===0){if(aw.sizset){return aw}}else{aw[ai]=ay;
if(at(aw,av)){aw.sizset=true;return aw}aw.sizset=false}}}}}}function H(e,ap){return e?function(at,ar){var aq=ap(at,ar);
return aq&&e(aq===true?at:aq,ar)}:ap}function K(au,ar,e){var aq,at,ap=0;for(;(aq=au[ap]);
ap++){if(S.relative[aq.part]){at=J(at,S.relative[aq.part],ar)}else{aq.captures.push(ar,e);
at=H(at,S.filter[aq.part].apply(null,aq.captures))}}return at}function g(e){return function(ar,aq){var at,ap=0;
for(;(at=e[ap]);ap++){if(at(ar,aq)){return true}}return false}}var n=Z.compile=function(e,ar,ap){var av,au,aq,at=x[e];
if(at&&at.context===ar){at.dirruns++;return at}au=c(e,ar,ap);for(aq=0;(av=au[aq]);
aq++){au[aq]=K(av,ar,ap)}at=x[e]=g(au);at.context=ar;at.runs=at.dirruns=0;G.push(e);
if(G.length>S.cacheLength){delete x[G.shift()]}return at};Z.matches=function(ap,e){return Z(ap,null,null,e)
};Z.matchesSelector=function(e,ap){return Z(ap,null,null,[e]).length>0};var ah=function(at,ap,av,az,ay){at=at.replace(ag,"$1");
var e,aA,aw,aB,aq,ar,aD,aE,au,ax=at.match(F),aC=at.match(al),aF=ap.nodeType;if(Q.POS.test(at)){return ac(at,ap,av,az,ax)
}if(az){e=v.call(az,0)}else{if(ax&&ax.length===1){if(aC.length>1&&aF===9&&!ay&&(ax=Q.ID.exec(aC[0]))){ap=S.find.ID(ax[1],ap,ay)[0];
if(!ap){return av}at=at.slice(aC.shift().length)}aE=((ax=ab.exec(aC[0]))&&!ax.index&&ap.parentNode)||ap;
au=aC.pop();ar=au.split(":not")[0];for(aw=0,aB=S.order.length;aw<aB;aw++){aD=S.order[aw];
if((ax=Q[aD].exec(ar))){e=S.find[aD]((ax[1]||"").replace(E,""),aE,ay);if(e==null){continue
}if(ar===au){at=at.slice(0,at.length-au.length)+ar.replace(Q[aD],"");if(!at){ae.apply(av,v.call(e,0))
}}break}}}}if(at){aA=n(at,ap,ay);A=aA.dirruns;if(e==null){e=S.find.TAG("*",(ab.test(at)&&ap.parentNode)||ap)
}for(aw=0;(aq=e[aw]);aw++){af=aA.runs++;if(aA(aq,ap)){av.push(aq)}}}return av};
if(i.querySelectorAll){(function(){var au,av=ah,at=/'|\\/g,aq=/\=[\x20\t\r\n\f]*([^'"\]]*)[\x20\t\r\n\f]*\]/g,ap=[],e=[":active"],ar=l.matchesSelector||l.mozMatchesSelector||l.webkitMatchesSelector||l.oMatchesSelector||l.msMatchesSelector;
T(function(aw){aw.innerHTML="<select><option selected></option></select>";if(!aw.querySelectorAll("[selected]").length){ap.push("\\["+L+"*(?:checked|disabled|ismap|multiple|readonly|selected|value)")
}if(!aw.querySelectorAll(":checked").length){ap.push(":checked")}});T(function(aw){aw.innerHTML="<p test=''></p>";
if(aw.querySelectorAll("[test^='']").length){ap.push("[*^$]="+L+"*(?:\"\"|'')")
}aw.innerHTML="<input type='hidden'>";if(!aw.querySelectorAll(":enabled").length){ap.push(":enabled",":disabled")
}});ap=ap.length&&new RegExp(ap.join("|"));ah=function(aB,ax,aC,aE,aD){if(!aE&&!aD&&(!ap||!ap.test(aB))){if(ax.nodeType===9){try{ae.apply(aC,v.call(ax.querySelectorAll(aB),0));
return aC}catch(aA){}}else{if(ax.nodeType===1&&ax.nodeName.toLowerCase()!=="object"){var az=ax.getAttribute("id"),aw=az||ai,ay=ab.test(aB)&&ax.parentNode||ax;
if(az){aw=aw.replace(at,"\\$&")}else{ax.setAttribute("id",aw)}try{ae.apply(aC,v.call(ay.querySelectorAll(aB.replace(F,"[id='"+aw+"'] $&")),0));
return aC}catch(aA){}finally{if(!az){ax.removeAttribute("id")}}}}}return av(aB,ax,aC,aE,aD)
};if(ar){T(function(ax){au=ar.call(ax,"div");try{ar.call(ax,"[test!='']:sizzle");
e.push(S.match.PSEUDO)}catch(aw){}});e=new RegExp(e.join("|"));Z.matchesSelector=function(ax,az){az=az.replace(aq,"='$1']");
if(!w(ax)&&!e.test(az)&&(!ap||!ap.test(az))){try{var aw=ar.call(ax,az);if(aw||au||ax.document&&ax.document.nodeType!==11){return aw
}}catch(ay){}}return Z(az,null,null,[ax]).length>0}}})()}if(typeof define==="function"&&define.amd){define(function(){return Z
})}else{aa.Sizzle=Z}})(window);