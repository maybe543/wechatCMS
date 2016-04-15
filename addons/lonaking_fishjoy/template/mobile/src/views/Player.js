(function(){
	//获取url中的参数
	function getUrlParam(name) {
		var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
		var r = window.location.search.substr(1).match(reg);
		if (r != null){
			return unescape(r[2]);
		}
		return "";
	}
	var activityId = getUrlParam('aid');
	var hitType = getUrlParam('hitType');
	//分割结束
	var token = "";
	var localTime = "";//后台返回当前时间  yyyy-MM-dd
	var gameTime=0;

	var status,coinGets;
	//TODO 获取配置信息
	var responseData = $.ajax({
		url: $("html").data("config-url"),
		async: false,
		type : "GET",
		dataType : "json",
		data: {}
	}).responseText;
	responseData=eval("("+responseData+")");
	var playTimePrivilege = responseData.data.playTimePrivilege;// 可以玩的次数
	responseData = responseData.data;
	var baseImgUrl = "../attachment/";
	var paramLogo=baseImgUrl+responseData.param_logo;
	var paramText=baseImgUrl+responseData.param_infoImg;
	var paramBg1=baseImgUrl+responseData.param_bg1Img;
	var gameBg=baseImgUrl+responseData.param_gamebgImg;
	var paramBg3=baseImgUrl+responseData.param_awardBg;
	var paramAwardImg=baseImgUrl+responseData.param_awardImg;
	var list_array=responseData.array;
	gameTime=responseData.gameTime;

	var param_success_tip=responseData.bottomTip;
	var shareTitle=responseData.param_share_title;
	var shareContent=responseData.param_share_content;
	var shareImg=baseImgUrl+responseData.param_share_img;
	var title_=responseData.title;
	token=responseData.token;

	document.title=title_;
	var ns = Q.use("fish"), game = ns.game;
	var Player = ns.Player = function(props)
	{
		var page1=document.getElementById('page1');
		var page2=document.getElementById('page2');
		var page3=document.getElementById('page3');
		var alarmDom=document.getElementById('alarm');
		var awardDom=document.getElementById('awardDiv');
		var scoreDom=document.getElementById('score');
		var popUp=document.getElementById('popUp');
		var goToSubmit=document.getElementById('goToSubmit');
		var goToAgain=document.getElementById('goToAgain');

		this.id = null;
		this.coin = 0;
		this.numCapturedFishes = 0;

		this.cannon = null;
		this.cannonMinus = null;
		this.cannonPlus = null;
		this.coinNum = null;

		props = props || {};
		Q.merge(this, props, true);
		var this_=this;
		$('#paramLogo').html('<img src="'+paramLogo+'"/>');
		$('#paramText').html('<img src="'+paramText+'"/>');
		$('#paramBg1').html('<img src="'+paramBg1+'"/>');
		$('#paramBg3').html('<img src="'+paramBg3+'"/>');
		$('#paramAwardImg').attr('src',paramAwardImg);
		$('#param_success_tip').text(param_success_tip);

		$('#bg').css('background-image','url('+gameBg+')');
		var isAgain=$.cookies.get('again');
		isAgain = 0;
		if(isAgain==1){
			page1.style.display='none';
			init_this();
		}else{
			page1.style.display='block';
		}

		var start_game=document.getElementById('start');
		start_game.addEventListener('click',function(){
			checkPlayPrivilege(function () {
				playTimePrivilege  = playTimePrivilege - 1;
				page1.style.display='none';
				init_this();
			});

		});
		//判断是否还有权限玩
		function checkPlayPrivilege(callback){
			if(playTimePrivilege < 1){
				alert("今日游戏次数已经用完");
			}else{
				callback();
			}
		}
		function init_this(){
			this_.init();
			console.log(shareTitle+'hehe'+shareContent);
			var mix_url='http://liveapp.1paiclub.com/rest/lightApp/redirect?aid='+activityId+'&hitType='+hitType+'&shared=0';
			var shareData = {
				title:shareTitle,
				desc:shareContent,
				link: mix_url,
				imgUrl: 'http://liveapp.1paiclub.com'+shareImg,
				success: function (res) {

				}
			};
			wx.onMenuShareAppMessage(shareData);
			wx.onMenuShareTimeline(shareData);
			wx.onMenuShareQQ(shareData);
			wx.onMenuShareWeibo(shareData);

			var s = gameTime, t;
			function times(){
				s--;
				document.getElementById('count').innerText = s;
				t = setTimeout(times, 1000);
				if ( s <= 0 ){
					s = 10;
					clearTimeout(t);
					status=1;
					if(coinGets>0){
						var award,awardResponse,remainNumber,lowerLimit;
						//记录成绩 TODO
						var responseAwardData = $.ajax({
							url: $("html").data("score-lottery-url"),
							async: false,
							type : "POST",
							dataType : "json",
							data: {"token":token,"score":coinGets}
						}).responseText;
						awardResponse=eval("("+responseAwardData+")");
						///rest/lightApp/award/scoreLottery
						//token
						//activityId
						//score
						award=awardResponse.data.tips;
						var awardName=awardResponse.data.name;
						remainNumber=awardResponse.data.remainNumber;
						lowerLimit=awardResponse.data.lowerLimit;
						if(remainNumber<=0||lowerLimit==0){//没奖品了或者0分开始
							awardDom.style.display='none';
							alarmDom.innerText = award;
							goToSubmit.style.display='none';
						}else{
							awardDom.innerText = '恭喜获得：'+award;
							$('#prizeName').val(awardName)
						}
						scoreDom.innerText = coinGets;
						setTimeout(function(){
							page2.style.display='none';
							$('#goToAgain').bind('click',function(){
								checkPlayPrivilege(function () {
									$.cookies.set('again',1);
									window.location.reload();
									status=0;
									var s = gameTime, t;
									page2.style.display='block';
									times();
								});
							});
							//提交结果
							$('#goToSubmit').bind('click',function(){
								$.cookies.set('again',0);
								popUp.style.display='block';
								$('#activityId').val(activityId);
								$('#token').val(token);
								var Button1=document.getElementById('Button1');
								Button1.addEventListener('click',function(){
									var phone=$('#phone').val();
									var prizeName=$('#prizeName').val();
									if(phone ==''){
										alert('请填写手机号再提交');
										return false;
									}else{
										$('#submitRes').submit();
										$('.submit_success').show();
										$('.submit_').hide();
									}
								})
							})
						},1000)
					}
				}
			}
			times();
		}
	};




	Player.prototype.init = function()
	{
		var me = this, power = 1;
		var width_screen=window.outerWidth;
		//alert(width_screen)
		var height_screen=window.outerHeight;

		this.cannon = new ns.Cannon(ns.R.cannonTypes[power]);
		this.cannon.id = "cannon";
		this.cannon.x = game.bottom.x + 425;
		this.cannon.y = game.bottom.y + 60;
		this.cannon.y = game.height - 10;

		this.cannonMinus = new Q.Button(ns.R.cannonMinus);
		this.cannonMinus.id = "cannonMinus";
		this.cannonMinus.x = game.bottom.x + 340;
		this.cannonMinus.y = game.bottom.y + 36;
		this.cannonMinus.onEvent = function(e)
		{
			if(e.type == game.events[1])
			{
				me.cannon.setPower(-1, true);
			}
		};
		this.cannonPlus = new Q.Button(ns.R.cannonPlus);
		this.cannonPlus.id = "cannonPlus";
		this.cannonPlus.x = this.cannonMinus.x + 140;
		this.cannonPlus.y = this.cannonMinus.y;
		this.cannonPlus.onEvent = function(e)
		{
			if(e.type == game.events[1])
			{
				me.cannon.setPower(1, true);
			}
		};
		this.coinNum = new ns.Num({id:"coinNum", src:ns.R.numBlack, max:6, gap:3, autoAddZero:true});
		this.coinNum.x = game.bottom.x + 200;
		this.coinNum.y = game.bottom.y + 44;
		this.updateCoin(this.coin);

		game.stage.addChild(this.coinNum,this.cannon, this.cannonMinus, this.cannonPlus);
	};

	Player.prototype.fire = function(targetPoint)
	{
		var cannon = this.cannon, power = cannon.power, speed = 5;
		if(this.coin < power) return;
		if(status==1) return;
		//cannon fire
		var dir = ns.Utils.calcDirection(cannon, targetPoint), degree = dir.degree;
		if(degree == -90) degree = 0;
		else if(degree < 0 && degree > -90) degree = -degree;
		else if(degree >= 180 && degree <= 270) degree = 180 - degree;
		cannon.fire(degree);

		//fire a bullet
		var sin = Math.sin(degree*Q.DEG_TO_RAD), cos = Math.cos(degree*Q.DEG_TO_RAD);
		var bullet = new ns.Bullet(ns.R.bullets[power - 1]);
		bullet.x = cannon.x + (cannon.regY + 20) * sin;
		bullet.y = cannon.y - (cannon.regY + 20) * cos;
		bullet.rotation = degree;
		bullet.power = power;
		bullet.speedX = speed * sin;
		bullet.speedY = speed * cos;
		game.stage.addChild(bullet);

		//deduct coin
		//this.updateCoin(-power, true);
	}

	Player.prototype.captureFish = function(fish)
	{
		if(status!=1){
			this.updateCoin(fish.coin, true);
			this.numCapturedFishes++;
		}

	};

	Player.prototype.updateCoin = function(coin, increase)
	{
		if(increase) this.coin += coin;
		else this.coin = coin;
		if(this.coin > 999999) this.coin = 999999;
		this.coinNum.setValue(this.coin);
		//alert(this.coinNum);
		//alert(this.coin);
		coinGets=this.coin-100000;
	};

})();