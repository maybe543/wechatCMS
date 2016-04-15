wx.config({
    debug: false, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
    appId: $("#appID").val(), // 必填，公众号的唯一标识
    timestamp: $("#jsTimesTamp").val(), // 必填，生成签名的时间戳
    nonceStr: $("#jsNonceStr").val(), // 必填，生成签名的随机串
    signature: $("#jsSignature").val(),// 必填，签名，见附录1
    jsApiList: ['onMenuShareTimeline', 'onMenuShareAppMessage', 'onMenuShareQQ', 'onMenuShareWeibo'] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
});
wx.ready(function () {
   
    var title = $("#title").val();
    var desc = $("#desc").val();
    var desc2 = desc.replace("<br />", "\r").replace("<br />", "\r") + "\r收藏热度：★★★★★";
   
    var url = $("#url").val();
    var img_url = $("#img_url").val();
        wx.onMenuShareAppMessage({
            title: title,
            desc: desc2,
            link: url,
            imgUrl: img_url,
            trigger: function (res) {
                //alert('用户点击发送给朋友');
            },
            success: function (res) {
                //alert('已分享');
            },
            cancel: function (res) {
                //alert('已取消');
            },
            fail: function (res) {
                //alert(JSON.stringify(res));
            }
        });
       

    // 2.2 监听“分享到朋友圈”按钮点击、自定义分享内容及分享结果接口
   
        wx.onMenuShareTimeline({
            title: title,
            link: url,
            imgUrl: img_url,
            trigger: function (res) {
                //alert('用户点击分享到朋友圈');
            },
            success: function (res) {
                //alert('已分享');
            },
            cancel: function (res) {
                //alert('已取消');
            },
            fail: function (res) {
                //alert(JSON.stringify(res));
            }
        });
      
   

    // 2.3 监听“分享到QQ”按钮点击、自定义分享内容及分享结果接口
  
        wx.onMenuShareQQ({
            title: title,
            desc: desc2,
            link: url,
            imgUrl: img_url,
            trigger: function (res) {
                //alert('用户点击分享到QQ');
            },
            complete: function (res) {
                //alert(JSON.stringify(res));
            },
            success: function (res) {
                //alert('已分享');
            },
            cancel: function (res) {
                //alert('已取消');
            },
            fail: function (res) {
                //alert(JSON.stringify(res));
            }
        });
       
   

    // 2.4 监听“分享到微博”按钮点击、自定义分享内容及分享结果接口
   
        wx.onMenuShareWeibo({
            title: title,
            desc: desc2,
            link: url,
            imgUrl: img_url,
            trigger: function (res) {
                //alert('用户点击分享到微博');
            },
            complete: function (res) {
                //alert(JSON.stringify(res));
            },
            success: function (res) {
                //alert('已分享');
            },
            cancel: function (res) {
                //alert('已取消');
            },
            fail: function (res) {
                //alert(JSON.stringify(res));
            }
        });
       
    
})