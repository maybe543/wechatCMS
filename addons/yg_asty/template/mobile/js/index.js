/**
  *	version:2.0.1
  *	author:skdream
  *	dependence:none
  *	modify:''
*/
WXJsSDKInit = function(options){

	if(!options) options = {};
	var jssdk = "http://res.wx.qq.com/open/js/jweixin-1.0.0.js"; //微信sdk
	var jssdkGetSignature = "http://wyy.woniu.com/wyy/client/share/getSignature" // 获取jssdk签名接口

	var loadJs = function(){

		var firstScript = document.getElementsByTagName('script')[0],
			scriptParent = firstScript.parentNode,
			onload = 'onload',
			onreadystatechange = 'onreadystatechange',
			readyState = 'readyState',
			re = /ded|co/;

		var load = function(src, callback, charset){
			var script = document.createElement('script');
			script.charset = charset;

			script[onload] = script[onreadystatechange] = function(){
				if( !this[readyState] || re.test(this[readyState]) ){
					script[onload] = script[onreadystatechange] = null;
					callback && callback(script);
					script = null;
				}
			}
			script.async = true;
			script.src = src;
			scriptParent.insertBefore(script, firstScript)
		};

		return function(srcs, callback, charset){
			charset = charset || 'utf-8';
			if(typeof srcs === 'string'){
				load(srcs, callback, charset);
			}else{
				var src = srcs.shift();
				load(src,function(){
					if(srcs.length){
						loadJs(srcs, callback, charset);
					}else{
						callback && callback();
					}
				}, charset);
			}
		}
	}();

	var wnWXJsonp = {};
	window.wnWXJsonp = wnWXJsonp;

	var getJsonp = function(url, callback){
		var fn = 'fn_' + Math.floor(new Date().getTime() * Math.random()).toString(36);
		wnWXJsonp[fn] = callback;

		var head = document.getElementsByTagName("head")[0];
		var script = document.createElement("script");
		script.charset = "utf-8";
		script.onload = script.onreadystatechange = function(_, isAbort){
            if( isAbort || !script.readyState || /loaded|complete/.test( script.readyState ) ){
                script.onload = script.onreadystatechange = null;
                head.removeChild(script);
                script = null;
            }
        }
        script.src = url + ( (/\?/).test(url) ? '&':'?' ) + 'jsoncallback=wnWXJsonp.' + fn;
        head.insertBefore(script, head.firstChild)
	}

	var xhrFactory = function(){
		this.init.apply(this, arguments);
	}
	xhrFactory.prototype = {
		init:function(){
			this.xhr = this.create();
		},
		create:function(){
			var xhr = null
			try{
				if(window.XMLHttpRequest){
					xhr = new XMLHttpRequest();
				}else if(window.ActiveXObject){
					xhr = new ActiveXObject("Msxml2.Xmlhttp");
				}
			}
			catch(err){
				xhr = new ActiveXObject("Microsoft.Xmlhttp");
			}
			return xhr;
		},
		readystate:function(timeout, callback){
			var self = this;
			self.xhr.onreadystatechange = function(){
				if(this.readyState == 4 && this.status == 200){
					callback(eval("("+ this.responseText + ")"));
				}else{
					setTimeout(function(){
						self.xhr.abort();
					}, !timeout? 15000 : timeout);
				}
			}
		},
		parseData:function(data){
			return data;
		},
		get:function(url, data, callback, async, timeout){
			this.readystate(timeout, callback);
			var newurl = url;
			var datastr = this.parseData(data);
			newurl = url + "?" + datastr;
			this.xhr.open("get", newurl, !async? true: async);
			this.xhr.send(null);
		},
		post:function(){
			this.readystate(timeout, callback);
			var newurl = url;
			var datastr = this.parseData(data);
			this.xhr.open("post", newurl, !async? true: async);
			this.xhr.setRequestHeader("content-type", "x-www-form-urlencoded");
			this.xhr.send(null);
		}
	}

	/*
	  callback回调函数，传递wx对象
	  appId 可选，对应公众号appid,留空根据页面url寻找
	  jsApiList 可选，为api功能接口，留空取得全部，
	  ifDebug 可选，是否进行调试，留空不进行调试
	*/

	this.init = function(callback, appId, jsApiList, ifDebug){
		var appId  = appId;
		loadJs(jssdk,function(){

			getJsonp(jssdkGetSignature + "?url=" + encodeURIComponent( location.href.replace(/[\#][\s\S]*/,'') ) + ((appId)?"&appid="+appId:""),function(data){
				if(data.code === 0){
					var item = data.obj,
					timestamp = item.timestamp,
					nonceStr = item.nonceStr,
					signature = item.signature,
					appId = item.appId;
					if(!jsApiList){
						jsApiList=[
							'checkJsApi', 
							'onMenuShareTimeline',
							'onMenuShareAppMessage',
							'onMenuShareQQ',
							'onMenuShareWeibo',
							'hideMenuItems',
							'showMenuItems',
							'hideAllNonBaseMenuItem',
							'showAllNonBaseMenuItem',
							'translateVoice',
							'startRecord',
							'stopRecord',
							'onRecordEnd',
							'playVoice',
							'pauseVoice',
							'stopVoice',
							'uploadVoice',
							'downloadVoice',
							'chooseImage',
							'previewImage',
							'uploadImage',
							'downloadImage',
							'getNetworkType',
							'openLocation',
							'getLocation',
							'hideOptionMenu',
							'showOptionMenu',
							'closeWindow',
							'scanQRCode',
							'chooseWXPay',
							'openProductSpecificView',
							'addCard',
							'chooseCard',
							'openCard'
						];
					}
					var opts = {
						debug: ifDebug, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
						appId: appId, // 必填，公众号的唯一标识
						timestamp:timestamp , // 必填，生成签名的时间戳
						nonceStr: nonceStr, // 必填，生成签名的随机串
						signature: signature,// 必填，签名，见附录1
						jsApiList: jsApiList // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
					}
					wx.config(opts);
					wx.ready(function() {
						callback(wx);
					});
				}
				else{
					console.log(data.sMsg);
					if(ifDebug){
						alert(data.sMsg);
					}
				}
			});
		})
	}

}
wxShare = new WXJsSDKInit();