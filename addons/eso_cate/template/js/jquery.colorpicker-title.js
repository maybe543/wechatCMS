var ColorHex=new Array('00','33','66','99','CC','FF')
var SpColorHex=new Array('FF0000','00FF00','0000FF','FFFF00','00FFFF','FF00FF')
var current=null
var colorTable=''

function colorpicker(obj) {
	for (i=0;i<2;i++) {
	  for (j=0;j<6;j++) {
		colorTable=colorTable+'<tr height=12>'
		colorTable=colorTable+'<td width=11 onmouseover="onmouseover_color(\'000\')" style="background-color:#000">'
		if (i==0){
		colorTable=colorTable+'<td width=11 onmouseover="onmouseover_color(\''+ColorHex[j]+ColorHex[j]+ColorHex[j]+'\')" style="background-color:#'+ColorHex[j]+ColorHex[j]+ColorHex[j]+'" data-color="'+ColorHex[j]+ColorHex[j]+ColorHex[j]+'">'
		}  else {
		colorTable=colorTable+'<td width=11 onmouseover="onmouseover_color(\''+SpColorHex[j]+'\')" style="background-color:#'+SpColorHex[j]+'" data-color="'+SpColorHex[j]+'">'} 
	
		colorTable=colorTable+'<td width=11 onmouseover="onmouseover_color(\'000\')" style="background-color:#000" data-color="000">'
		for (k=0;k<3;k++) {
		   for (l=0;l<6;l++) {
			colorTable=colorTable+'<td width=11 onmouseover="onmouseover_color(\''+ColorHex[k+i*3]+ColorHex[l]+ColorHex[j]+'\')" style="background-color:#'+ColorHex[k+i*3]+ColorHex[l]+ColorHex[j]+'" data-color="'+ColorHex[k+i*3]+ColorHex[l]+ColorHex[j]+'">'
		   }
		 }
	  }
	}
	colorTable='<div style="position:relative;width:253px; height:176px"><a href="javascript:;" onclick="closeBox();" class="close-own" style="right: 8px;  top: 8px; position: absolute; font-size: 12px;">X</a><table width=253 border="0" cellspacing="0" cellpadding="0" style="border:1px #000 solid;border-bottom:none;border-collapse: collapse" bordercolor="000000">'
			   +'<tr height=30><td colspan=21 bgcolor=#eeeeee>'
			   +'<table cellpadding="0" cellspacing="1" border="0" style="border-collapse: collapse">'
			   +'<tr><td width="3"><td><input type="text" name="DisColor" size="6" id="background_colorId" disabled style="border:solid 1px #000000;background-color:#ffff00;padding:1px;"></td>'
			   +'<td width="3"><td><input type="text" name="HexColor" size="7" id="input_colorId" style="border:inset 1px;font-family:Arial;padding:1px;font-size:12px;" value="#000000"></td><td>'
			   +'<a href="javascript:;" id="_clear_title" style="padding-left:5px;font-size:12px;">清除</a></td></tr></table></td></table>'
			   +'<table width=253 border="1" cellspacing="0" cellpadding="0" style="border-collapse: collapse" bordercolor="000000" style="cursor:hand;" id="_color_colorlist">'
			   +colorTable+'</table></div>';
	
	//显示
	if ($('#colorpanel_n')) $('#colorpanel_n').remove();
	$("body").append('<div id="colorpanel_n" style="position: absolute; display: none; z-index: 9999;"></div>');
	$('#colorpanel_n').html(colorTable); colorTable = '';
	
	//赋点击
	$('#_color_colorlist td').each(function(){
		$(this).click(function(){
			var color = $(this).css("background-color");
			$(obj).prev().css("color", color);
			$(obj).next().val(_rgb2hex(color));
            $(obj).next().blur();
			$('#colorpanel_n').hide();
		});
	});
	$("#_clear_title").click(function(){
		$(obj).prev().css("color", "");
		$(obj).next().val("");
        $(obj).next().blur();
		$('#colorpanel_n').hide();
	});
	
    //定位
    var ttop  = $(obj).offset().top;     //控件的定位点高
    var thei  = $(obj).height();  //控件本身的高
    var tleft = $(obj).offset().left;    //控件的定位点宽
	if (tleft + 253 > $(window).width()) tleft = $(window).width() - 253;
    $("#colorpanel_n").css({
        top:ttop+thei+5,
        left:tleft
    }).show();
}
function onmouseover_color(color) {
	var color = '#'+color;
	$('#background_colorId').css('background-color',color);
	$('#input_colorId').val(color);
  
}
function closeBox(){
	$("#colorpanel_n").hide();
}
function _zero_fill_hex(num, digits) {
  var s = num.toString(16);
  while (s.length < digits)
    s = "0" + s;
  return s;
}
function _rgb2hex(rgb) {
	  if (rgb.charAt(0) == '#')
	    return rgb;
	 
	  var ds = rgb.split(/\D+/);
	  var decimal = Number(ds[1]) * 65536 + Number(ds[2]) * 256 + Number(ds[3]);
	  return "#" + _zero_fill_hex(decimal, 6);
	}