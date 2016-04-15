/*绑定touchstart事件*/
//var btn = $(".btn");
//document.body.addEventListener('touchstart', function () { }); 
$("body").on('touchstart', function () { }); 
var h = document.documentElement.clientHeight;
var w = document.documentElement.clientWidth;
$("#page0").css('min-height',h);
$(".top-con").css('height',h);
$(".slideToppic").css('min-height',h);
$("#paiming").css('width',w);
$(window).resize(function(){
	var h = document.documentElement.clientHeight;
	var w = document.documentElement.clientWidth;
	$("#page0").css('min-height',h);
	$(".slideToppic").css('min-height',h);
	$("#paiming").css('width',w);
	//alert("窗口尺寸变了");
	})
//提示弹出及关闭
	$(".tanchu").css('height',h);
	$(".dt-btn").on("click",function(){
		//$(".tanchu").show();
		$(".tanchu").show('');
		/*$(".tanchu").animate({
  opacity: 0.75, left: '50px',
  color: '#abcdef',
  rotateZ: '45deg', translate3d: '0,10px,0'
}, 500, 'ease-in')*///动画
	});
$("#yanshi").on("click",function(){
		$("#shenqing").fadeIn();
		/*$("#sqform").animate({
			  opacity: 0.75, height:'toggle'
			}, 500, 'ease-in')*/
	});
$(".close").on("click",function(){
		$("#shenqing").fadeOut();
	});