var getArgs = (function () {
    var sc = document.getElementsByTagName('script');
    var paramsArr = sc[sc.length - 1].src.split('?')[1].split('&');
	//?v=1&t=2
    var args = {}, argsStr = [], param, t, name, value;
    for (var ii = 0, len = paramsArr.length; ii < len; ii++) {
        param = paramsArr[ii].split('=');
        name = param[0], value = param[1];
        if (typeof args[name] == "undefined") { //参数尚不存在
            args[name] = value;
        } else if (typeof args[name] == "string") { //参数已经存在则保存为数组
            args[name] = [args[name]]
            args[name].push(value);
        } else {  //已经是数组的
            args[name].push(value);
        }
    }
    return function () { return args; } //以json格式返回获取的所有参数
})();
function getNonceStr() {
    var $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    var maxPos = $chars.length;
    var noceStr = "";
    for (i = 0; i < 10; i++) {
        noceStr += $chars.charAt(Math.floor(Math.random() * maxPos));
    }
    return noceStr;
}

var cssItem = getArgs()["item"];//需要加载的样式集合
var cssrandomStr = getNonceStr();//随机字符串
var csst = "201411241620000000";//修改时间
var cssv = "-v4.4";//版本号
var csshost = "http://html.v5portal.com/distribution"+cssv;
    //csshost="http://192.168.1.35:8002/";
document.writeln("<script src='../addons/hc_hunxiao/style/js/ClassSub.js?_=" + cssrandomStr + "'><\/script>");