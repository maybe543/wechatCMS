var center = {

	init : function(){
		center.funcs.duty_validate();
	},

	event : function(){
		/*推广页面按钮点击*/
		$("#share-button").on("click",function(){
			$flag = center.funcs.duty_validate();
			if($flag){
				var input_name = $("input[name='name']").val();
				if( input_name== "" || input_name == null){
					$("#reg-share-man").modal('show');
				}else{
					$("#share-task-list").modal('show');
				}
			}
		});

		/*同意免责条款*/
		$("button.agree-duty").on("click",function(){
			$("input[name='agree_duty']").val(1);
			$("#duty-free").modal('hide');
		});
		/*提交登记*/
		$("button.submit-regist").click(function(){
			center.funcs.modal_hide();
			$flag = center.funcs.duty_validate();
			if($flag == true){
				var data = {
					agree_duty : 1,
					name : $("#reg-share-man input[name='name']").val()
				};
				if(data.name == "" || data.name == null){
					center.funcs.warning_modal("非法操作",'<div class="alert alert-danger">姓名不能为空!</div>');
					return ;
				} 
				$.post($("html").attr("data-url-adduserapi"),data, function(result){
					var json = eval('('+result+')');
					if(json.status!=200){
						$("#reg-share-man").modal('hide');
						center.funcs.warning_modal("非法操作",'<div class="alert alert-danger">'+json.message+'!</div>');
					}
					if(json.status==200){
						center.funcs.warning_modal("注册成功","恭喜您成为推广人");
						setTimeout(window.location.href = window.location.href,3000);
					}
				});
			}else{
				$(".modal").modal('hide');
				//$("#myshare_web").modal('show');
			}
			
		});

		/*查看排名按钮*/
		$("#look-up-rank").on("click",function(){
			$(".modal").modal('hide');
			$("#rank-modal").modal('show');
		});	

		/*兑换 按钮 点击后 积分商城弹出*/
		$(".convert").click(function(){
			$(".modal").modal('hide');
			$("#gift-modal").modal('show');
		});
		/* 制作按钮 跳转到我的二维码 */

		/*礼品兑换记录按钮*/
		$("button.history-gift").on("click",function(){
			$("#gift-modal").modal('hide');
			$("#history-gift").modal('show');	

		});
		/*返回积分商城按钮*/
		$("button.return-gift").on("click",function(){
			$("#history-gift").modal('hide');
			$("#gift-modal").modal('show');
		});

		/*兑换按钮*/
		$(".buy-gift").on("click",function(){
			var id = parseInt($(this).attr("data-id"));
			var score = parseInt($("span.my-score").attr("data-score"));
			var price = parseInt($(this).attr("data-price"));
			center.funcs.modal_hide();
			if(score < price ){
				center.funcs.warning_modal("积分不足",'<div class="alert alert-danger">您的积分不足!请再接再厉</div>');
			}else{
				var id_obj = $("input[name='order_gift_id']");
				id_obj.val(id);
				$("#order-info-modal").modal('show');
			}
		});

		/*提交兑换 提交用户信息*/
		$(".submit-order-info").on("click",function(){
			//准备数据
			var data = {
				'gift_id' : $("input[name='order_gift_id']").val(),
				'name' : $("input[name='order_name']").val(),
				'mobile' : $("input[name='order_mobile']").val(),
				'target' : $("input[name='order_target']").val()
			};
			//alert(data.gift_id+"==="+data.name+"==="+data.mobile+"==="+data.target);
			$(".modal").modal('hide');
			$.post($("html").attr("data-url-giftorderapi"),data,function(result){
				if(result == null || result == undefined || result == ""){
					center.funcs.warning_modal("兑换失败",'<div class="alert alert-warning">系统异常</div>');
					return ;
				}
				var json = eval('('+result+')');
				if(json.status != 200 || json == null || json == undefined || json == ""){
					center.funcs.warning_modal("兑换失败",'<div class="alert alert-warning">'+json.message+'</div>');
					return ;
				}else{
					center.funcs.warning_modal("兑换成功",'<div class="alert alert-success">恭喜你，获得此礼品，工作人员将会与您取得联系</div>');
					return ;
				}
			});
		});
	},

	funcs : {
		/*校验*/
		duty_validate :  function(){
			$reg = $("input[name='agree_duty']").val();
			if($reg != 1){
				$(".modal").modal('hide');
				$("#duty-free").modal('show');
				return false;
			}else{
				return true;
			}
		},

		/*警告模态框*/
		warning_modal : function(title,content){
			$("#warning h4").text(title);
			$("#warning .modal-body").html(content);
			$(".modal").modal('hide');
			$("#warning").modal('show');
		},

		modal_hide : function(){
			$(".modal").modal('hide');
		},



	}

};
$(function(){
	center.init();
	center.event();
});