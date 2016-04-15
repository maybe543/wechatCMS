$(function(){
	FastClick.attach(document.body);
	App.controller('index_page', function (page) {
		
		// 解密详情页初始化
		App.controller('detail_page', function (page) {
			this.transition = 'scale-in';
		});
		// 获取线索页初始化
		App.controller('clue_find_page', function (page) {
			this.transition = 'scale-in';

			var list=$(page).find('#cf_list');
			var cf_list_load=$(page).find('#cf_list_load');
			var cf_list_tpl=baidu.template($(page).find('#cf_list_tpl').html());
			var loadList = function(){
				if(0==list.data('more')){
					return;
				}
				cf_list_load.removeClass('loading more');
				cf_list_load.addClass('loading');
				cf_list_load.find('.text').html('正在加载...');
				cf_list_load.show();
				$.get(list.data('url'),{
					start:list.data('start')
				}).done(function(resp) {
					cf_list_load.hide();
					if(!resp){
						alert('加载失败，请检查网络后重试');
						return;
					}
					if(resp.status!=1){
						alert('加载失败，请检查网络后重试');
						return;
					}
					var data=resp.data;
					list.data('start',data.start);
					list.data('more',data.more);
					var html=cf_list_tpl(data);
					list.html(list.html()+html);
				});
			};
			cf_list_load.on('tap',function(){
				loadList();
			});

			loadList();
		});

		// 显示解密详情页
		$(page).find('#puzzle_banner').on('tap',function(){
			 App.load('detail_page');
		});
		// 显示获取线索页
		$(page).find('#to_exchange_btn').on('tap',function(){
			App.load('clue_find_page');
		});
		
		
		// 如果解密进行中
		if($('#is_puzzle_end').val()==0){

			// 互换线索
			$(page).find('#clue_exchange_btn').on('tap',function(){
				var loading = $.dialog({icon:'load',content: '正在互换线索...'});
				$.post($(this).data('url'),{
				},function( data ) {
					loading.close();
					if(data.status==0){
						$.dialog({
							content : data.info,
							title : '失败',
							okText: '确定',
							ok : function() {
								return true;
							},
							lock : true
						});
					}else{
						$.dialog({
							content : '即将为您显示对方线索',
							title : '互换成功',
							okText: '确定',
							ok : function() {
								location.href=location.href;
							},
							lock : true
						});
					}
				});
			});
			
			/** 输入型提交答案 **/
			// 提交解答
			$(page).find('#puzzle_answer_btn').on('tap',function(){
				if($(this).hasClass('disabled')){
					$.dialog({
						content : '至少获得'+$(this).data('keys_least')+'条线索才能解答',
						title : '线索不足',
						okText: '确定',
						ok : function() {
							return true;
						},
						lock : true
					});
				}else{
					var answer=$(page).find('#puzzle_answer_text').val();
					if(''==answer){
						return $.dialog({
							content : '您还没有填写解答内容',
							title : '提示',
							okText: '确定',
							ok : function() {
								return true;
							},
							lock : true
						});
					}
					// 提交解答
					var loading = $.dialog({icon:'load',content: '正在提交解答...'});
					$.post($(this).data('url'),{
						answer:answer
					},function( data ) {
						loading.close();
						if(data.status==0){
							$.dialog({
								content : data.info,
								title : '提交失败',
								okText: '确定',
								ok : function() {
									return true;
								},
								lock : true
							});
						}else{
							$.dialog({
								content : '请耐心等待答案揭晓',
								title : '提交成功',
								okText: '确定',
								ok : function() {
									location.href=location.href;
								},
								lock : true
							});
						}
					});
				}
			});

			// 重新提交答案(输入型)
			$(page).find('#puzzle_answered').on('tap',function(){
				$.dialog({
					content : '如果重新解答，原解答内容将被覆盖，且解答时间以最后一次提交为准',
					title : '确定要重新解答吗？',
					okText: '确定重答',
					ok : function() {
						$(page).find('#puzzle_answered').hide();
						$(page).find('#puzzle_hint').show();
						$(page).find('#puzzle_answer').show();
					},
					cancelText: '取消',
					cancel : function() {
						return true;
					},
					lock : true
				});
			});
			/** 输入型提交答案 **/

			/** 选择型提交答案 **/
			// 显示选项
			$(page).find('#puzzle_answer_select').on('tap',function(){
				if($(this).hasClass('disabled')){
					$.dialog({
						content : '至少获得'+$(this).data('keys_least')+'条线索才能解答',
						title : '线索不足',
						okText: '确定',
						ok : function() {
							return true;
						},
						lock : true
					});
				}else{
					if($(this).hasClass('droped')){
						$(this).removeClass('droped');
						$(page).find('#puzzle_answer_options').slideUp();
					}else{
						$(this).addClass('droped');
						$(page).find('#puzzle_answer_options').slideDown();
					}
				}
			});
			// 提交答案
			$(page).find('#puzzle_answer_options li').on('tap',function(){
				$(this).addClass('selected');
				var answer=$(this).data('value');
				// 提交解答
				var loading = $.dialog({icon:'load',content: '正在提交解答...'});
				$.post($(page).find('#puzzle_answer_select').data('url'),{
					answer:answer
				},function( data ) {
					loading.close();
					if(data.status==0){
						$.dialog({
							content : data.info,
							title : '提交失败',
							okText: '确定',
							ok : function() {
								return true;
							},
							lock : true
						});
					}else{
						$.dialog({
							content : '请耐心等待答案揭晓',
							title : '提交成功',
							okText: '确定',
							ok : function() {
								location.href=location.href;
							},
							lock : true
						});
					}
				});
			});
			
			/** 选择型提交答案 **/
			

			// 解密活动倒计时
			var puzzle_timer = setInterval(function(){
				var limit=$(page).find('#puzzle_timer').data('end_time') - new Date().getTime()/1000;
				if(limit<=0){
					clearInterval(puzzle_timer);
					location.href=location.href;
				}else{
					var text='';
					if(limit>3600){
						text+=parseInt(limit/3600)+'小时';
						limit=limit%3600;
					}
					if(limit>60){
						text+=parseInt(limit/60)+'分钟';
						limit=limit%60;
					}
					if(limit>0){
						text+=parseInt(limit)+'秒';
					}
					$(page).find('#puzzle_timer_disp').text(text+'后揭晓！');
				}
			},1000);
		}

		// 如果解密已结束
		if($('#is_puzzle_end').val()==1){
			// 前往解密解释页
			App.controller('truth_page', function (page) {
				this.transition = 'scale-in';
			});
			$(page).find('#puzzle_truth').on('tap',function(){
				 App.load('truth_page');
			});
			// 前往宝藏引导页
			App.controller('award_page', function (page) {
				this.transition = 'scale-in';
			});
			$(page).find('#puzzle_to_award').on('tap',function(){
				 App.load('award_page');
			});

			// 解密活动排行榜
			var list=$(page).find('#rank_list');
			var rank_list_load=$(page).find('#rank_list_load');
			var rank_list_tpl=baidu.template($(page).find('#rank_list_tpl').html());
			var loadRankList = function(){
				if(0==list.data('more')){
					return;
				}
				rank_list_load.removeClass();
				rank_list_load.addClass('loading');
				rank_list_load.find('.text').html('正在加载...');
				rank_list_load.show();
				$.get(list.data('url'),{
					start:list.data('start')
				}).done(function(resp) {
					rank_list_load.hide();
					if(!resp){
						Toast.error('加载失败，请检查网络后重试');
						return;
					}
					if(resp.status!=1){
						Toast.error(resp.info);
						return;
					}
					var data=resp.data;
					data.index=list.data('start');
					list.data('start',data.start);
					list.data('more',data.more);
					var html=rank_list_tpl(data);
					list.html(list.html()+html);
				});
			};
			$(page).find('#rank_list_load').on('tap',function(){
				loadRankList();
			});

			// 显示排行榜
			//$(page).find('#rank_to_bang').on('tap',function(){
			//	$(this).hide();
			//	$(page).find('#rank_bang').show();
				loadRankList();
			//});
			
			
		}
	});
	
	App.load('index_page');
});