var wxshare = {
    data : {

    },
    init : function(){
        wxshare.functions.ready();
    },
    event : function(){

    },
    functions : {
        ready : function () {
            wx.ready(function () {
                wx.onMenuShareAppMessage({
                    title: document.getElementsByName("share_title")[0].getAttribute("content"),
                    desc:  document.getElementsByName("share_content")[0].getAttribute("content"),
                    link:  document.getElementsByName("share_url")[0].getAttribute("content"),
                    imgUrl: document.getElementsByName("share_logo")[0].getAttribute("content"),
                    trigger: function (res) {},
                    success: function (res) {
                        shareCallback();
                    },
                    cancel: function (res) {},
                    fail: function (res) {

                    }
                });
                wx.onMenuShareTimeline({
                    title: document.getElementsByName("share_title")[0].getAttribute("content"),
                    desc:  document.getElementsByName("share_content")[0].getAttribute("content"),
                    link:  document.getElementsByName("share_url")[0].getAttribute("content"),
                    imgUrl: document.getElementsByName("share_logo")[0].getAttribute("content"),
                    trigger: function (res) {},
                    success: function (res) {
                        shareCallback();
                    },
                    cancel: function (res) {},
                    fail: function (res) {}
                });
                wx.onMenuShareQQ({
                    title: document.getElementsByName("share_title")[0].getAttribute("content"),
                    desc:  document.getElementsByName("share_content")[0].getAttribute("content"),
                    link:  document.getElementsByName("share_url")[0].getAttribute("content"),
                    imgUrl: document.getElementsByName("share_logo")[0].getAttribute("content"),
                    trigger: function (res) {},
                    success: function (res) {
                        //shareCallback();
                    },
                    cancel: function (res) {},
                    fail: function (res) {}
                });
                wx.onMenuShareWeibo({
                    title: document.getElementsByName("share_title")[0].getAttribute("content"),
                    desc:  document.getElementsByName("share_content")[0].getAttribute("content"),
                    link:  document.getElementsByName("share_url")[0].getAttribute("content"),
                    imgUrl: document.getElementsByName("share_logo")[0].getAttribute("content"),
                    trigger: function (res) {},
                    success: function (res) {
                        //shareCallback();
                    },
                    cancel: function (res) {},
                    fail: function (res) {}
                });
            });
        }
    }

}
$(function () {
    wxshare.init();
    wxshare.event();
});
/**
 * 分享回调方法
 */
function shareCallback(){

    var url = $("html").data("share-callback");
    var postData = {
        "activity_id" : $("html").data("activity-id"),
        "record_id" : index.data.record.id,
    };
    $.post(url,postData, function () {

    });
}