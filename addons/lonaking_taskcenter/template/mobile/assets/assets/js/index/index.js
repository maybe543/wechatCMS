/**
 * Created by leon on 8/19/15.
 */
var index = {
    init: function () {
        // 初始化首页的轮播图片
        index.functions.swiperInit();
        
        index.functions.checkFollow();

    },
    event: function () {
    	//点击文章
    	$$('div.pages').on('click','div.page[data-page=index] #share-task a.task-item',function(e){
			var name = $$('#ucenter .user-name').text();
			if(name == "" || null == name || name == undefined){
				taskFramework.confirm('您还没有登记成为推广员,点击确定进入登记页面', function () {
					//确定
					index.functions.loadUserInfo(null,function(){
						//回调函数
					});
				}, function () {
					//取消
				});
			}else{
				var current = $$(this);
				window.location.href = current.data('url');
			}
    	});
    	
    	// 点击我的资料
    	$$('div.pages').on('click','div.page[data-page=index] .user-info',function(e){
    		e.stopPropagation();
    		var name = $$('#ucenter .user-name').text();
    		if(name == "" || null == name || name == undefined){
    			
    		}
    		index.functions.loadUserInfo(name,function(){
    			//回调函数
    		});
    	});

    	//点击推广排名
    	$$('div.pages').on('click','div.page[data-page=index] .user-sort',function(e){
    		e.stopPropagation();
    		taskFramework.showIndicator();
    		var url = $$('html').attr('data-user-sort-url');
    		$$.post(url,function(result){
    			taskFramework.hideIndicator();
				var json = eval('('+result+')');
				if(json.status == 200){
					mainView.router.loadPage({
		    			url : '../addons/lonaking_taskcenter/template/mobile/assets/pages/share-order.html',
		    			context : json.data
		    		});
				}else{
					taskFramework.alert(json.message);
				}
    		});
    	});
    	
    	//提交资料
    	$$('div.pages').on('click','div.page[data-page=user-info] a.submit-user-info',function(e){
    		e.stopPropagation();
    		taskFramework.showIndicator();
    		var data = {
				agree_duty : 1,
				name : $("#buy-gift-user-info input[name=name]").val()
			};
    		if(data.name == "" || data.name == null){
    			taskFramework.hideIndicator();
    			taskFramework.alert('姓名不能为空!');
				return ;
			}
    		$.post($("html").attr("data-url-adduserapi"),data, function(result){
    			taskFramework.hideIndicator();
				var json = eval('('+result+')');
				if(json.status!=200){
					taskFramework.alert(json.message);
					return ;
				}
				if(json.status==200){
					taskFramework.alert('注册成功,恭喜你成功推广人');
					setTimeout(function(e){
						mainView.router.load({
							url : $$('html').attr('data-index-url')
						});
					},1000);
				}
			});
    	});
    	
        // 点击礼物
        $$('div.pages').on('click','div.page[data-page=index] #gift-shop .row div.gift', function (e) {
            e.stopPropagation();
            var btn = $$(this);
			var url =
            mainView.router.loadPage({
                url : '../addons/lonaking_taskcenter/template/mobile/assets/pages/gift-detail.html',
                context : {
                	id : btn.attr('data-id'),
                	name : btn.attr('data-name'),
                	price : btn.attr('data-price'),
                	score : btn.attr('data-score'),
                	image : btn.find('div.gift-img img').attr('src'),
					mode : btn.data('mode'),
					send_price : btn.data('send-price'),
					description : btn.find('.description').html(),
					num : btn.data('num'),
					sold : btn.data('sold'),
					limit_num : btn.data('limit-num')
                }
            });
        });

		//点击我的礼品
		$$('div.pages').on('click','div.page[data-page=index] .item-link.my-gifts', function (e) {
			e.stopPropagation();
			taskFramework.showIndicator();
			var btn = $$(this);
			var url = $$('html').data('mygifts-api-url');
			$.post(url, function(result){
				taskFramework.hideIndicator();
				var json = JSON.parse(result);
				if(json.status!=200){
					taskFramework.alert(json.message);
					return ;
				}
				if(json.status==200){
					//家在新页面
					mainView.router.loadPage({
						url : '../addons/lonaking_taskcenter/template/mobile/assets/pages/my-gifts.html',
						context : {
							'new_gifts' : json.data.new_gifts,
							'success_gifts' : json.data.success_gifts,
							'attachurl' : $$('html').data('attachurl')
						}
					});
				}
			});
		});

        // 邀请好友加入点击
        $$('div.pages').on('click','div.page[data-page=index] .invite-friends',function(e){
        	e.stopPropagation();
        	var btn = $$(this);
        	mainView.router.loadPage({
        		url : '../addons/lonaking_taskcenter/template/mobile/assets/pages/invite-qrcode.html',
        		context : {
        			user_name : $$('#ucenter .user-name').text(),
        			follow_score : btn.attr('data-follow-sroce'),
        			unfollow_score : btn.attr('data-unfollow-score'),
        			second_shareman_score : btn.attr('data-second-score'),
        			qrcode : btn.attr('data-qrcode')
        		}
        	});
        });
    },
    functions: {
        // 初始化首页广告图片，这里应该请求的
        swiperInit : function () {
            var indexSwiper = taskFramework.swiper('div.pages div.page[data-page=index] div.swiper-container', {
            	preloadImages: false,
            	pagination: '.swiper-pagination',
                lazyLoading: true,
            });
        },

        loadGiftDetail : function(id,callback){
            // 请求id为id的url
            mainView.router.loadPage({
                url : 'pages/gift-detail.html'
            });
            callback(this);
        },
        
        /*跳转到用户信息页面*/
        loadUserInfo : function(name,callback){
        	var context = {
        		
        	};
        	if(name != null && ''!=name ){
        		context.name = name;
        	}
        	mainView.router.loadPage({
				url : '../addons/lonaking_taskcenter/template/mobile/assets/pages/user-info.html',
				context : context
			});
			callback();
        },
        
		checkFollow : function () {
			var follow_status = $$('html').data('follow-status');
			if(follow_status == 0){
				taskFramework.confirm("您还没有关注本微信公众平台,点击确定进入引导关注页面", function () {
					var follow_url = $$('html').data('follow-url');
					window.location.href = follow_url;
					return ;
				}, function () {
					
				});
			}else{
				index.functions.checkRegister();
			}
		}
		,

        checkRegister : function(){
        	//判断用户是否未注册
            var name = $$('#ucenter div.user-name').text().trim();
            if('' == name || null == name || undefined == name){
                taskFramework.confirm('您还没有登记成为推广员,点击确定进入登记页面', function () {
                    //确定
                    index.functions.loadUserInfo(null,function(){
                        //回调函数
                    });
                }, function () {
                	//取消
                });
            }
        }
    }
}
/* 初始化页面 */
$(function(e){
    index.init();
    index.event();
})
