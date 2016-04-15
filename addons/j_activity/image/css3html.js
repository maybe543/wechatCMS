// JavaScript Document
/*
/* css3+jquery动画使用
*/
;(function($, window, document) {
	//=============//
	//公共参数  //
	//=============//
	var defaults={
		'_wwidth': $(window).width(),
		'_wheight': $(window).height(),
		'_dwidth': $(document).width(),
		'_dheight': $(document).height(),
	}
	//=============//
	//loading效果//
	//=============//
	var LoadingMasker = function(opt) {
		this.defaults = {
			'hmtl':opt.html ? opt.html :'<div class="spinnerbox"><div class="spinner"><div class="spinner-container container1"><div class="circle1"></div><div class="circle2"></div><div class="circle3"></div><div class="circle4"></div></div><div class="spinner-container container2"><div class="circle1"></div><div class="circle2"></div><div class="circle3"></div><div class="circle4"></div></div><div class="spinner-container container3"><div class="circle1"></div><div class="circle2"></div><div class="circle3"></div><div class="circle4"></div></div></div><div style="padding-top:10px"><small>加载中</small></div></div>',
			'timeout':opt.timeout?parseInt(opt.timeout):30000,
		},
		this.options = $.extend({}, this.defaults, opt)
	}
	LoadingMasker.prototype = {
		init: function() {
			if($(".jetsumMasker").size()==0){
				var html=$("<div class='jetsumMasker' style='position:absolute;z-index:999;left:0;top:0;width:"+defaults._wwidth+"px;height:"+defaults._dheight+"px;background:rgba(0,0,0,0.5);'></div>");
				$('body').append(html);
			}
			$(".jetsumMasker").height($(document).height());
			$(".jetsumMasker").html(this.defaults.hmtl);
			
		},
		showed: function() {
			this.init();
			$(".jetsumMasker").css('display','');
			$(".jetsumMasker").css("-webkit-animation","opacity_show 0.3s");
			$(window).scroll (function(){
				var _h=($(window).height() - $('.jetsumMasker').children()[0].outerHeight())/2 + $(document).scrollTop() ;
				$('.jetsumMasker').children()[0].css('top',_h);
			});
			setTimeout(function(){
				if($(".jetsumMasker").is(":visible")){
					$(".jetsumMasker").css("-webkit-animation","opacity_hide 0.3s").css('display','none');
					var masker = new jalert("抱歉加载错误");
					masker.showed();
				}
			},this.options.timeout);
			
		},
		hideed: function() {
			if($(".jetsumMasker").size()>0){
				setTimeout(function(){
					$(".jetsumMasker").css("-webkit-animation","opacity_hide 0.3s").css('display','none');
				},1000);
			}
		},
	}
	//=============//
	//alert窗口end//
	//=============//
	var jalert = function(content) {
		this.defaults = {
			'content':content,
		}
	}
	jalert.prototype = {
		init: function() {
			this.defaults.id=$(".jetsumAlert").size()+1;
			var html=$("<div class='jetsumAlert' id='jetsumAlert"+this.defaults.id+"' style='text-align:center;position:absolute;z-index:9999;width:60%;line-height:16px;padding:10px;background:rgba(0,0,0,0.6);color:#FFF;visibility:hidden;display:inline-block;border-radius:5px'></div>");
			$('body').append(html);
			$(".jetsumAlert").css({
				'left':defaults._dwidth/2-defaults._dwidth*0.3,
				'top':($(window).height())/2 + $(document).scrollTop(),
			}).html(this.defaults.content);
		},
		showed: function() {
			this.init();
			id=this.defaults.id;
			
			$("#jetsumAlert"+id).css('visibility','visible').css('opacity','1');
			$("#jetsumAlert"+id).css("-webkit-animation","opacity_show 0.5s");
			setTimeout(function(){
				$("#jetsumAlert"+id).css('opacity','0').css("-webkit-animation","opacity_hide 0.3s");
				if($(".jetsumAlert").size()>1){
					setTimeout(function(){$(".jetsumAlert").remove();},400);
				}else{
					setTimeout(function(){$("#jetsumAlert"+id).remove();},400);
				}
				
			},1000);
		},
	}
	//=============//
	//使用方法//
	//=============//
	$.fn.Jetsum = function(options) {}
	
	$.fn.Jetsum.loadbox = function(options) {
		var masker = new LoadingMasker(options);
		if(options.do){
			masker.showed();
		}else{
			masker.hideed();
		}
	}
	$.fn.Jetsum.alert = function(options) {
		var masker = new jalert(options.content);
		masker.showed();
	}
})(jQuery, window, document);