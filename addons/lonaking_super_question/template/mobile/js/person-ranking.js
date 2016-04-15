var personRanking = {
    data : {

    },
    init : function(){
        personRanking.functions.loadPersonRanking(1, function (json) {
            var tr = '';
            var baseRank = 1;
            $.each(json.data.data, function (index, e) {
                if($("html").data('openid') == e.openid){
                    tr = tr + '<tr class="bg-info"><td name="headimg"><img src="'+ e.headimgurl+'" width="35px" height="35px" class="img-circle"><span class="nickname">'+ e.nickname+'</span></td><td name="score">'+ e.total_score +'</td><td name="seconds">'+e.play_times+'次</td><td name="ranking"><span class="badge">'+baseRank+'</span></td></tr>';
                }else{
                    tr = tr + '<tr class=""><td name="headimg"><img src="'+ e.headimgurl+'" width="35px" height="35px" class="img-circle"><span class="nickname">'+ e.nickname+'</span></td><td name="score">'+ e.total_score +'</td><td name="seconds">'+e.play_times+'次</td><td name="ranking"><span class="badge">'+baseRank+'</span></td></tr>';
                }
                baseRank = baseRank + 1;
            });
            $("tbody.rank-tbody").append(tr);
        });
    },
    event : function(){
        //点击加载更多按钮
        $("button.load-more").on("click", function () {
            var btn = $("button.load-more");
            var page = btn.attr('data-page');
            personRanking.functions.loadPersonRanking(page, function (json) {
                var tr = '';
                var baseRank = 1;
                if(parseInt(page) > 1){
                    baseRank = parseInt(page)*10+1;
                }
                if(json.data.data.length > 0){
                    $.each(json.data.data, function (index, e) {
                        if($("html").data('openid') == e.openid){
                            tr = tr + '<tr class="bg-info"><td name="headimg"><img src="'+ e.headimgurl+'" width="35px" height="35px" class="img-circle"><span class="nickname">'+ e.nickname+'</span></td><td name="score">'+ e.score +'</td><td name="seconds">'+e.answer_seconds+'秒</td><td name="ranking"><span class="badge">'+baseRank+'</span></td></tr>';
                        }else{
                            tr = tr + '<tr class=""><td name="headimg"><img src="'+ e.headimgurl+'" width="35px" height="35px" class="img-circle"><span class="nickname">'+ e.nickname+'</span></td><td name="score">'+ e.score +'</td><td name="seconds">'+e.answer_seconds+'秒</td><td name="ranking"><span class="badge">'+baseRank+'</span></td></tr>';
                        }
                        baseRank = baseRank + 1;
                    });
                    $("tbody.rank-tbody").append(tr);
                    btn.attr('data-page',parseInt(page)+1);
                }else{
                    btn.hide();
                    $(".no-more").show();
                }

            });
        });
    },
    functions : {
        loadPersonRanking : function(page,callback){
            var url = $("html").data("load-person-ranking-url");
            var postData = {
                "page" : page,
            };
            $.post(url, postData, function (e) {
                var json = JSON.parse(e);
                callback(json);
            });
        }
    }
};
$(function () {
   personRanking.init();
    personRanking.event();
});