/**
 * Created by leon on 8/19/15.
 */
var index = {
    init: function () {
        //删除一行垃圾代码
        $$('#index span.tab-link-highlight').remove();

    },
    event: function () {

        /**
         * 添加标签
         */
        $('#index').on(clickEvent,'div.tag-list p a.unselected', function () {
            var btn = $$(this);
            index.functions.check_follow(function () {
                var tag = btn.attr('data-name');
                index.functions.add_tag(tag, function () {
                    //添加到我的标签中
                    btn.removeClass('unselected');
                    btn.addClass('selected');
                });
            });
        });

        /**
         * 删除标签
         */
        $$('#index').on(clickEvent,'div.tag-list p a.selected', function (e) {
            e.stopPropagation();
            var btn = $$(this);
            index.functions.check_follow(function () {
                var tag = btn.text();
                index.functions.remove_tag(tag, function () {
                    //添加到我的标签中
                    btn.removeClass('selected');
                    btn.addClass('unselected');
                });
            });
        });
        /**
         * 匹配
         */
        $$('#index').on(clickEvent,'a.pipei', function () {
            var btn = $$(this);
            index.functions.check_follow(function () {
                mainFramework.showPreloader("匹配中...");
                btn.addClass('hide');
                index.functions.pipei(function (user) {
                    mainFramework.hidePreloader();
                    $$('#index a.pipei-success').removeClass('hide');
                    //将拿到的数据展示出来
                    var sex_color = 'color-pink';
                    if(user.tag.sex = 1){
                        sex_color = 'color-blue';
                    }
                    var name = user.tag.nickname;
                    if(name == null || name == '' || name == undefined){
                        name = '未知';
                    }
                    var pic = user.tag.avatar;
                    if(pic == null || pic == '' || pic == undefined){
                        pic = '../addons/lonaking_bb/template/mobile/images/head.jpg';
                    }
                    var over_minute = user.over_minute;
                    var over_minute_notice = "未知时间后结束";
                    if(over_minute != null && over_minute != '' && over_minute != undefined){
                        var over_minute_notice = '聊天将在'+over_minute+'分种后结束';
                    }
                    var current_chat_user = '<li><div class="item-content"><div class="item-media"><img src="'+pic+'" width="44"></div><div class="item-inner"><div class="item-title-row"><div class="item-title">'+name+' &nbsp;<i class="icon-user '+sex_color+'"></i></div><div class="item-after">'+over_minute_notice+'</div></div><div class="item-subtitle">'+user.tag.province +''+user.tag.city +'['+user.tag.country+']'+'</div></div></div></li>';
                    $('div.current-chat-info ul').empty();

                    $$('div.current-chat-info ul').append(current_chat_user);
                }, function () {
                    mainFramework.hidePreloader();
                    btn.removeClass('hide');
                });
            });
            $$('div.ripple-wave.ripple-wave-fill').remove();
        });

        /**
         * 挂断
         */
        $$('#index').on(clickEvent,'a.hang-up', function () {
            mainFramework.showPreloader("正在挂断...");
            var btn = $$(this);
            index.functions.check_follow(function () {
                btn.addClass('hide');
                $$('#index a.load.hide').removeClass('hide');
                index.functions.hang_up(function () {
                    mainFramework.hidePreloader();
                    $$('#index a.load').addClass('hide');
                    $$('#index a.pipei').removeClass('hide');
                    $$('#index a.hang-up').addClass('hide');
                    $('div.current-chat-info ul').empty();

                });
            });
            $$('div.ripple-wave.ripple-wave-fill').remove();
        });

    },
    functions: {
        pipei : function(callback,error_callback){
            $$('#index a.load.hide').removeClass('hide');
            var url = $$('html').attr('data-pipei-url');
            $$.post(url, function (e) {
                $$('#index a.load').addClass('hide');
                var json = JSON.parse(e);
                if(json.status == 200){
                    mainFramework.alert(json.message,callback(json.data));
                }else{
                    mainFramework.alert(json.message,error_callback());
                }
            });
        },
        /**
         * 挂断
         * @param callback
         */
        hang_up : function (callback) {
            var url = $$('html').attr('data-hang-up-url');
            $$.post(url, function (e) {
                var json = JSON.parse(e);
                if(json.status == 200){
                    $$('#index a.hang-up').addClass('hide');
                    callback();
                }else{
                    mainFramework.alert(json.message);
                }
            })
        },
        /**
         * 添加标签
         * @param callback
         */
        add_tag : function (tag,callback) {
            var url = $$('html').attr('data-add-tag-url');
            var post_data = {
                tag : tag
            }
            $$.post(url, post_data, function (e) {
                var json = JSON.parse(e);
                if(json.status == 200){
                    callback();
                }else{
                    mainFramework.alert(json.message);
                }
            });
        },
        /**
         * 删除标签
         * @param id
         * @param callback
         */
        remove_tag : function (tag, callback) {
            var url = $$('html').attr('data-remove-tag-url');
            var post_data = {
                tag : tag
            }
            $$.post(url, post_data, function (e) {
                var json = JSON.parse(e);
                if(json.status == 200){
                    callback();
                }else{
                    mainFramework.alert(json.message);
                }
            });
        },
        /**
         * 检查用户是否关注
         * @returns {boolean}
         */
        check_follow : function (callback) {
            var follow_status = $$('html').data('follow-status');
            var follow_url = $$('html').attr('data-follow-url');
            if(follow_status == 0){
                mainFramework.confirm('您没有关注本微信平台,点击确认前往关注', function () {
                    window.location.href = follow_url;
                }, function () {
                    return ;
                });
            }else{
                callback();
            }
        }
    }
}
/*初始化页面*/
$(function(e){
    index.init();
    index.event();
})
