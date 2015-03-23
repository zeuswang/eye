/*stat.js test*/


(function(){
	var S=function(a){return document.getElementById(a)};
	var U=function(a){return document.getElementsByTagName(a)};
	var B=function(a){a=a||window.event;this.target=a.target||a.srcElement};
	B.add=function(a,b,c){if(window.addEventListener){a.addEventListener(b,c,false)}else{a.attachEvent("on"+b,c)}};
	var E=function(){return new Date().getTime()};
	var K=function(a){var b=new B(a);if(J==-1){J=0}J++;tmo=E();mopos=O(a)};
	var V=function(a){if(a.type=="mousedown"){R=E();mdpos=O(a)}else{R=E()-R}};
	var F=function(a){N==-1?N=a.clientX:N=N;P==-1?P=a.clientY:P=P};
	var D=function(){if(X==-1){X=E()}C=E()-X};
	var G=function(a){L=a.clientX;M=a.clientY};
	var O=function(a){if(a.pageX||a.pageY){return{x:a.pageX,y:a.pageY}}return{x:a.clientX+document.body.scrollLeft-document.body.clientLeft,y:a.clientY+document.body.scrollTop-document.body.clientTop}};
	var w=function(a){x[i++]=1;};
	var o=function(){A=E();};
	var W=function(b){
		var a=b.target.innerHTML;
		if(b.target.href.indexOf("&ml")==-1){
			ma_plus=(A-tmo)+","+history.length+","+mopos.x+","+mopos.y+","+mdpos.x+","+mdpos.y;
			mr_href="ml="+i+"&mc="+(E()-A)+"&ma="+ma_plus;
			mr_href+="&ak="+J+"."+R+"."+L+"."+M+"."+N+"."+P+"."+C;
			//alert(mr_href);
			(new Image()).src = "http://123.56.111.132/cl.gif?"+ mr_href;
		}
		if((a.match(/(www\.)|(.*@.*)/i)!=null)&&document.all){
			a.match(/\<.*\>/i)==null?b.target.innerHTML=a:b.target.innerTEXT=a;
			//alert(b.target.innerTEXT);
		}
	};
	var Q=function(a){
		var b=new B(a);
		var c=0;
		while(b.target.tagName.toLowerCase()!="a"&&c<10){ 
			b.target=b.target.parentNode;c++
		}
		G(a);D();
		if("star"=="star"){W(b)}
	};

	var x=new Array(1000);
	var i=0,A=0;
	var L=-1,M=-1,N=-1,P=-1,R=-1,X=-1,C=-1,J=-1,H=-1;
	//var T=S("mr");
	//slots=T.getElementsByTagName("a");
	slots=document.getElementsByTagName("a");
	//alert(slots.length);//mr_test
	B.add(document,"mousemove",w);
	B.add(document,"mousedown",o);
	
	//B.add(T,"mouseover",F);
	//B.add(T,"mouseover",D);
	
	for(var I=0;I<slots.length;I++){
		node=slots[I];
		B.add(node,"mousedown",V);
		B.add(node,"mouseup",V);
		B.add(node,"click",Q);
		B.add(node,"mouseover",K)
	}
	(new Image()).src = "http://123.56.111.132/pv.gif";	
})();
