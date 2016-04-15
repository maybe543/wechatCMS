/**
 * Created by leon on 15/10/15.
 */
var index = {
    data : {
        record : null,
        time_count : 0,
        time_cost : 0,
        answered_count : 0,
        question_count : 0,
        right_answer : 0,
        wrong_answer : 0,
        score : 0 ,
        help_score : 0,
        right_ids : [],
        wrong_ids : [],
    },
    init : function(){
        //背景
        $("#backgound").height($(window).height());
        $("#backgound").width($(window).width());
        //题目数初始化
        index.data.question_count = parseInt($('#question-count').text());
        index.functions.checkFollow(function(){
        	index.functions.checkPlayLimit(function (limit,message) {
                //TODO  do nothing
            }, function (limit,message) {
                $.alert({
                    title : "提示",
                    content: message,
                    confirmButton : "确认",
                    confirmButtonClass: 'btn-success',
                    confirm : function () {

                    }
                });
            });
        },function(){
        	$.alert({
        	    title: '未关注!',
        	    content: '关注后才能继续哦～！请点击关注!',
        	    confirmButton : "立即关注",
        	    confirm: function(){
        	        window.location.href=$("html").data("follow-url");
        	    }
        	});
        });
        
    },
    event : function () {
        //开始答题按钮
        $('.start-button').on('click', function () {
            var btn = $(this);
            index.functions.checkFollow(function(){
            	index.functions.checkPlayLimit(function (limit,message) {
                    btn.parents('.option').hide();
                    $('.footer').show();
                    $('.question.option').first().show();
                    //$("body").css("background-image","url("+$('.question.option').first().data("bg-pic")+")");
                    $("#background-img").attr("src",$('.question.option').first().data("bg-pic"));
                    $("#background-img").height($(document).height());
                    // 开始计时
                    index.functions.second_start();
                }, function (limit,message) {
                    $.alert({
                        title : "提示",
                        content: message,
                        confirmButton : "确认",
                        confirmButtonClass: 'btn-success',
                        confirm : function () {

                        }
                    });
                });
            },function(){
            	$.alert({
            	    title: '未关注!',
            	    content: '关注后才能继续哦～！请点击关注!',
            	    confirmButton : "立即关注",
            	    confirm: function(){
            	        window.location.href=$("html").data("follow-url");
            	    }
            	});
            });
            
        });
        //点击规则说明
        $('.rule-button').on('click', function () {
            $(this).parents('.option').hide();
            //规则展示
            $('.rule-context.option').show();
        });
        //点击规则返回答题
        $('.rule-context').on('click', function () {
            $(this).hide();
            $('.index.option').show();
        });
        //题目选项点击
        $('.question.option').on('click','.select-option', function () {
            var current_btn = $(this);
            index.functions.answer_one_question(current_btn);
            setTimeout(function () {
                var current_question = current_btn.parents('.question');
                current_question.hide();

                var next = current_question.next();
                if(index.data.time_count > $("html").data("limit-seconds")){
                    index.data.help_score = index.data.question_count - index.data.right_answer;
                    current_question.siblings(".question.option").remove();
                    next = $(".last-page");
                }
                next.show();
                //$("body").css("background-image","url("+next.data("bg-pic")+")");
                $("#background-img").attr("src",next.data("bg-pic"));
                $("#background-img").height($(document).height());
                
                if(next.hasClass('last-page')){
                    index.data.time_cost = index.data.time_count;
                    $(".score").text(parseInt(index.data.score));
                    $(".help-score").text(parseInt(index.data.help_score));
                    $(".wrong").text(parseInt(index.data.wrong_answer));
                    $(".right").text(parseInt(index.data.right_answer));
                    $("#second").text(parseInt(index.data.time_cost));
                    $('.footer').hide();
                    if(index.data.help_score == 0){
                        $(".result-tip-content").text("恭喜你，答对了所有题目，转发给好友让他们来挑战你吧！");
                    }
                    //发送成绩
                    index.functions.send_results(function (json) {
                        $(".result-context-desc").html(json.data.result_analyse);
                        $(".result-tip-content").html(json.data.tip);
                    });
                }
            },300);
        });

    },
    functions : {
    	//检测是否关注
    	checkFollow : function(success, error){
    		var follow = $("html").data("follow");
    		if(follow == 1){
    			success();
    		}else{
    			error();
    		}
    	},
    	
    	//检测游戏次数
        checkPlayLimit : function(success,error){
            var checkUrl = $("html").data("check-limit-url");
            var postData = {
                "activity_id" : $("html").data("activity-id")
            };

            $.post(checkUrl,postData, function (e) {
                var json = JSON.parse(e);
                if(json.status == 200){
                    if(json.data.limit <= 0){
                        error(json.data.limit,json.data.message);
                    }else{
                        success(json.data.limit,json.data.message);
                    }
                }
            });
        },
        //答了一道题
        answer_one_question : function (current_btn) {
            index.data.answered_count = index.data.answered_count + 1;
            $('#answered-count').text(index.data.answered_count);
            var is_right = current_btn.data('right');
            if(is_right == 1){
                index.functions.answer_right(current_btn);
            }else if(is_right == 0){
                index.functions.answer_wrong(current_btn);
            }
        },
        //答对一道题
        answer_right : function (current_btn) {
            var current_question = current_btn.parents('.question');
            var question_id = current_question.data('question-id');
            current_btn.addClass('selected-success');
            index.data.right_answer = index.data.right_answer + 1;
            index.data.right_ids.push(question_id);
            index.data.score = index.data.score + parseInt(current_question.data('score'));

        },
        //答错一道题
        answer_wrong : function(current_btn){
            var current_question = current_btn.parents('.question');
            var question_id = current_question.data('question-id');
            current_btn.addClass('selected-error');
            index.data.wrong_answer = index.data.wrong_answer + 1;
            index.data.wrong_ids.push(question_id);
            index.data.score = index.data.score - parseInt(current_question.data('descore'));
            index.data.help_score = index.data.help_score + parseInt(current_question.data('score'));
        },
        //开始计时
        second_start : function(){
            setTimeout(function () {
                index.data.time_count = index.data.time_count + 1;
                $('#time-count').text(index.data.time_count);
                index.functions.second_start();
            },1000);
        },
        //发送成绩
        send_results : function(success){
            var post_url = $('html').data('send-result-url');
            var post_data = {
                right : index.data.right_answer,
                wrong : index.data.wrong_answer,
                answer_seconds : index.data.time_cost,
                right_ids : index.data.right_ids,
                wrong_ids : index.data.wrong_ids,
                score : index.data.score,
                activity_id : $("html").data('activity-id'),
                question_ids : $("html").data('question-ids')
            }
            $.post(post_url,post_data, function (e) {
                var json = JSON.parse(e);
                index.data.record = json.data.record;
                //做一些操作 修改分享参数
                $("#share-title-id").attr("content",json.data.share.share_title);
                $("#share-content-id").attr("content",json.data.share.share_description);
                $("#share-url-id").attr("content",json.data.share.share_url);
                wxshare.functions.ready();
                success(json);
            });
        },

    }
};
$(function () {
    index.init();
    index.event();
})