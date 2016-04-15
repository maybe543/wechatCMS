var FixedBox=function(el){
	this.element=el;
	this.BoxY=getXY(this.element).y;
}
FixedBox.prototype={
	setCss:function(){
		var windowST=(document.compatMode && document.compatMode!="CSS1Compat")? document.body.scrollTop:document.documentElement.scrollTop||window.pageYOffset;
		if(windowST>this.BoxY){
			this.element.className="cis_fixed_nv";
		}else{
			this.element.className="";
		}
	}
};
function addEvent(elm, evType, fn, useCapture) {
	if (elm.addEventListener) {
		elm.addEventListener(evType, fn, useCapture);
	return true;
	}else if (elm.attachEvent) {
		var r = elm.attachEvent('on' + evType, fn);
		return r;
	}
	else {
		elm['on' + evType] = fn;
	}
}
function getXY(el) {
	return document.documentElement.getBoundingClientRect && (function() {
		var pos = el.getBoundingClientRect();
		return { x: pos.left + document.documentElement.scrollLeft, y: pos.top + document.documentElement.scrollTop };
	})() || (function() {
		var _x = 0, _y = 0;
		do {
				_x += el.offsetLeft;
				_y += el.offsetTop;
		} while (el = el.offsetParent);
		return { x: _x, y: _y };
	})();
}
var divA=new FixedBox(document.getElementById("cis_fixed_btn"));
	addEvent(window,"scroll",function(){
	divA.setCss();
});