//ajax请求函数
var XMLHttpReq;  
function createXMLHttpRequest() {  
    try {  
        XMLHttpReq = new ActiveXObject("Msxml2.XMLHTTP");//IE高版本创建XMLHTTP  
    }  
    catch(E) {  
        try {  
            XMLHttpReq = new ActiveXObject("Microsoft.XMLHTTP");//IE低版本创建XMLHTTP  
        }  
        catch(E) {  
            XMLHttpReq = new XMLHttpRequest();//兼容非IE浏览器，直接创建XMLHTTP对象  
        }  
    }  
}  
function sendAjaxRequest(url,callback) {  

    createXMLHttpRequest();                                //创建XMLHttpRequest对象  
    XMLHttpReq.open("post", url, true);  
    XMLHttpReq.onreadystatechange = callback; //指定响应函数  
    XMLHttpReq.send(null);  
}  

function fyt$(id){
	return document.getElementById(id);
}
//滚动条在Y轴上的滚动距离
function getScrollTop(){
　　var scrollTop = 0, bodyScrollTop = 0, documentScrollTop = 0;
　　if(document.body){
　　　　bodyScrollTop = document.body.scrollTop;
　　}
　　if(document.documentElement){
　　　　documentScrollTop = document.documentElement.scrollTop;
　　}
　　scrollTop = (bodyScrollTop - documentScrollTop > 0) ? bodyScrollTop : documentScrollTop;
　　return scrollTop;
}
//文档的总高度
function getScrollHeight(){
　　var scrollHeight = 0, bodyScrollHeight = 0, documentScrollHeight = 0;
　　if(document.body){
　　　　bodyScrollHeight = document.body.scrollHeight;
　　}
　　if(document.documentElement){
　　　　documentScrollHeight = document.documentElement.scrollHeight;
　　}
　　scrollHeight = (bodyScrollHeight - documentScrollHeight > 0) ? bodyScrollHeight : documentScrollHeight;
　　return scrollHeight;
}
//浏览器视口的高度
function getWindowHeight(){
　　var windowHeight = 0;
　　if(document.compatMode == "CSS1Compat"){
　　　　windowHeight = document.documentElement.clientHeight;
　　}else{
　　　　windowHeight = document.body.clientHeight;
　　}
　　return windowHeight;
}

//点击分类的时候加载新闻
function shownews(id){
	cid=id;
	page=1;
	sendAjaxRequest("/app/index.php?c=entry&do=ajax_newslist&m=nets_bd_news&i="+uniacid+"&page="+page+"&cid="+cid,initNewsList);  
}
//回调函数 ，点击分类后的初始化新闻列表
function initNewsList() {  
    if (XMLHttpReq.readyState == 4) {  
        if (XMLHttpReq.status == 200) {  
            var text = XMLHttpReq.responseText;  
			var body = fyt$(append_divid);
			body.innerHTML=text;
			total_page=$("#page_"+page).attr("total_page");
			page=parseInt(page)+1;
			
        }  
    }  
}
//看新闻返回的时候预加载新闻
function shownews_init(id){
	//alert(id);
	cid=id;
	sendAjaxRequest("/app/index.php?c=entry&do=ajax_newslist&m=nets_bd_news&i="+uniacid+"&page="+page+"&cid="+cid,initNewsList_init);  
}
//回调函数 ，点击分类后的初始化新闻列表
function initNewsList_init() {  
	
    if (XMLHttpReq.readyState == 4) {  
        if (XMLHttpReq.status == 200) {  
			var text = XMLHttpReq.responseText;  
			var body = fyt$(append_divid);
			
			if(page==1){
				body.innerHTML=text;
			}else{
				var div = document.createElement("div");
				div.innerHTML = text;
				body.appendChild(div);
			}
			var newpage=page+1;
			total_page=$("#page_"+newpage).attr("total_page");
			
			page=parseInt(page)+1;
			//alert(cookie_id);
			var objnew=document.getElementById("new_"+cookie_id);
			//alert(objnew);
			if(objnew==null){
				page=parseInt(page)+1;
				shownews_init(cookie_cid);
			}else{
				var t = objnew.offsetTop;
				//alert("TOP::"+t);
				$(window).scrollTop(t);//滚动到锚点位置
			}
			
        }
    }  
}
//回调函数  追加分页后的新闻列表
function appendNewsList() {  
    if (XMLHttpReq.readyState == 4) {  
        if (XMLHttpReq.status == 200) {  
            var text = XMLHttpReq.responseText;  
			//alert(text);
			var body = fyt$(append_divid);
			var div = document.createElement("div");
			div.innerHTML = text;
			body.appendChild(div);
			var newpage=page+1;
			total_page=$("#page_"+newpage).attr("total_page");
			page=parseInt(page)+1;
        }  
    }  
}
//回调函数  追加分页后的我的积分列表
function appendMyMoneyList() {  
    if (XMLHttpReq.readyState == 4) {  
        if (XMLHttpReq.status == 200) {  
            var text = XMLHttpReq.responseText;  
			var body = fyt$(append_divid);
			var div = document.createElement("ul");
			div.innerHTML = text;
			body.appendChild(div);
			page=parseInt(page)+1;
        }  
    }  
}
//点击分类的时候加载新闻
function loadNums(){
	sendAjaxRequest("/app/index.php?c=entry&do=ajax_headnum&m=nets_bd_news&i="+uniacid,initLoadNums);  
}
//新闻打开的时候处理
function click_news(sourceid,reuid){
	var url="/app/index.php?i="+uniacid+"&c=entry&do=ajax_common&m=nets_bd_news&type=click&source="+sourceid+"&re="+reuid;
	sendAjaxRequest(url,callback_clicknews);  
}

function callback_clicknews(){
	if (XMLHttpReq.readyState == 4) {  
        if (XMLHttpReq.status == 200) {  
            var text = XMLHttpReq.responseText;  
			if(text=="1"){
				
			}else if(text=="-101"){
				//location.reload();
				//alert("登录过期，请重新登录！");
			}
        }  
    }
}
//登录的时候处理积分
function login(){
	var url="/app/index.php?i="+uniacid+"&c=entry&do=ajax_common&m=nets_bd_news&type=login";
	sendAjaxRequest(url,callback_login);  
}
function callback_login(){
	if (XMLHttpReq.readyState == 4) {  
        if (XMLHttpReq.status == 200) {  
            var text = XMLHttpReq.responseText;  
			if(text=="1"){
				//alert(1)
			}else if(text=="-101"){
				location.reload();
				alert("登录过期，请重新登录！");
			}
        }  
    }
}
//评论新闻
function comment_news(sourceid){
	var comment=fyt$("comment").value;
	if(comment==""){
		return;
	}
	var url="/app/index.php?i="+uniacid+"&c=entry&do=ajax_common&m=nets_bd_news&type=comment&source="+sourceid+"&comment="+comment;
	sendAjaxRequest(url,callback_commentnews);  
}
function callback_commentnews(){
	if (XMLHttpReq.readyState == 4) {  
        if (XMLHttpReq.status == 200) {  
            var text = XMLHttpReq.responseText;  
			//alert(text);
			if(text=="1"){
				location.reload();
				fyt$("comment").value="";
			}else if(text=="-101"){
				var url=location.href;
				if(url.indexOf("aouth=true")==-1){
					url+="&aouth=true";
				}
				location.href=url;
				alert("登录过期，请重新登录！");
			}else{
				location.reload();
			}
        }  
    }
}
//赞新闻
function like_news(sourceid){
	fyt$("like_name").style.color="#FF0000";
	var old_num=fyt$("like_name").innerHTML;
	var new_num=parseInt(old_num)+1;
	fyt$("like_name").innerHTML=new_num;
	//fyt$("like_icon").style.border="solid 1px #FF0000";
	var url="/app/index.php?i="+uniacid+"&c=entry&do=ajax_common&m=nets_bd_news&type=like&source="+sourceid;
	sendAjaxRequest(url,callback_likenews);  
}
function callback_likenews(){
	if (XMLHttpReq.readyState == 4) {  
        if (XMLHttpReq.status == 200) {  
            var text = XMLHttpReq.responseText;
			//alert(text);
			if(text=="1"){
				
			}else if(text=="-101"){
				var url=location.href;
				if(url.indexOf("aouth=true")==-1){
					url+="&aouth=true";
				}
				location.href=url;
				alert("登录过期，请重新登录！");
			}
        }  
    }
}
//赞评论
function like_newscomment(sourceid){
	fyt$("zan_icon_"+sourceid).style.color="#FF0000";
	//fyt$("like_icon").style.border="solid 1px #FF0000";
	var url="/app/index.php?i="+uniacid+"&c=entry&do=ajax_common&m=nets_bd_news&type=comment_click&source="+sourceid;
	sendAjaxRequest(url,callback_likenewscomment);  
}
function callback_likenewscomment(){
	if (XMLHttpReq.readyState == 4) {  
        if (XMLHttpReq.status == 200) {  
            var text = XMLHttpReq.responseText;  
			if(text=="1"){
			}else if(text=="-101"){
				var url=location.href;
				if(url.indexOf("aouth=true")==-1){
					url+="&aouth=true";
				}
				location.href=url;
				alert("登录过期，请重新登录！");
			}
        }  
    }
}
//参与活动
function partin_game(sourceid){
	var url="/app/index.php?i="+uniacid+"&c=entry&do=ajax_common&m=nets_bd_news&type=partin_game&source="+sourceid;
	sendAjaxRequest(url,callback_partin_game);  
}
function callback_partin_game(){
	if (XMLHttpReq.readyState == 4) {  
        if (XMLHttpReq.status == 200) {  
            var text = XMLHttpReq.responseText;  
			if(text=="1"){
				location.href=location.href+"&mygame=1";
				alert("参与成功，请等待开奖");
			}else if(text=="-10001"){
				alert("余额不足，努力去赚积分吧！");
			}else if(text=="-101"){
				location.reload();
				alert("登录过期，请重新登录！");
			}else if(text=="0"){
				alert("您已参与该活动，请等待开奖");
				location.href=location.href+"&mygame=1";
			}
        }  
    }
}
//余额提现
function moneycash(){
	var money=fyt$("mycash").value;
	var cash=fyt$("cash").value;
	if(money=="" || money==0 || parseInt(cash)<parseInt(money)){alert("提现金额不合法");return;}
	
	var url="/app/index.php?i="+uniacid+"&c=entry&do=ajax_common&m=nets_bd_news&type=money_cash&money="+money;
	sendAjaxRequest(url,callback_moneycash);  
}
function callback_moneycash(){
	if (XMLHttpReq.readyState == 4) {  
        if (XMLHttpReq.status == 200) {  
            var text = XMLHttpReq.responseText;  
			if(text=="1"){
				location.reload();
				alert("申请成功，请等待审核");
			}else if(text=="-101"){
				var url=location.href;
				if(url.indexOf("aouth=true")==-1){
					url+="&aouth=true";
				}
				location.href=url;
				alert("登录过期，请重新登录！");
			}else if(text=="-20001"){
				alert("余额不足！");
			}else if(text=="-20002"){
				alert("非法提现！");
			}else if(text=="-20003"){
				alert("最小提现不能小于"+min_cashmoney+"元！");
			}
        }  
    }
}

//加载到头部统计次数
function initLoadNums(){
	if (XMLHttpReq.readyState == 4) {  
        if (XMLHttpReq.status == 200) {  
            var text = XMLHttpReq.responseText;  
			var body = fyt$("head_menu");
			if(body==null){return;};
			var div = document.createElement("div");
			div.innerHTML = text;
			body.appendChild(div);
        }  
    }
}

//点击分类的时候加载新闻
function loadNums(){
	sendAjaxRequest("/app/index.php?c=entry&do=ajax_headnum&m=nets_bd_news&i="+uniacid,initLoadNums);  
}
//新闻打开的时候处理
function click_readlate(sourceid,reuid){
	var url="/app/index.php?i="+uniacid+"&c=entry&do=ajax_common&m=nets_bd_news&type=readlate&source="+sourceid;
	sendAjaxRequest(url,callback_readlatenews);  
}

function callback_readlatenews(){
	if (XMLHttpReq.readyState == 4) {  
        if (XMLHttpReq.status == 200) {  
            var text = XMLHttpReq.responseText;  
			if(text=="1"){
				alert("订阅成功");
			}
			else if(text=="0"){
				alert("您已订阅");
			}else if(text=="-101"){
				//location.reload();
				alert("登录过期，请重新登录！");
			}
        }  
    }
}

window.onload=function(){
	//loadNums();
}