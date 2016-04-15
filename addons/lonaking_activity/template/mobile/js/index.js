var index = {
    data : {
        page : 1,
        size : 10
    },
    init : function(){

    },
    event : function(){
        $("body").on("click", function (e) {
            e.stopPropagation();
            index.functions.dropDownFormTable();
        }),
        $("#dt_join_bar_title_mb").on("click", function (e) {
            e.stopPropagation();
            index.functions.upFormTable();
        });
        $("#dt_join_bar_title_mb_ok").on("click", function () {
            var status = $("html").data("status");
            if(status == 0){
                swal({
                    title: "<small>请出示下面二维码签到</small>!",
                    text: '<img src="'+$("html").data("qrcode")+'" width="180px" height="180px">',
                    imageUrl: "",
                    confirmButtonText: "好的",
                    html: true
                });
            }else if(status == 1){
                swal({
                    title: "<small>已签到</small>!",
                    confirmButtonText: "好的",
                    html: true
                });
            }else if(status == 2){
                swal({
                    title: "<small>已取消报名</small>!",
                    confirmButtonText: "好的",
                    html: true
                });
            }

        });

        $("#close_join_box").on("click", function (e) {
            e.stopPropagation();
            index.functions.dropDownFormTable();
        });

        $("#a_submit_nopay").on("click", function (e) {
            //check name
            if($("input[name=name]").val() == null || $("input[name=name]").val() == ""){
                index.functions.showTip($("input[name=name]").data("tip"));
                return false;
            }
            //check mobile
            if($("input[name=mobile]").val() == null || $("input[name=mobile]").val() == ""){
                index.functions.showTip($("input[name=mobile]").data("tip"));
                return false;
            }

            //提交
            index.functions.submitEnroll(function (json) {
                index.functions.showTip(json.message);
                index.functions.dropDownFormTable();
                swal({
                    title: "<small>请出示下面二维码签到</small>!",
                    text: '<img src="'+json.data.qrcode+'" width="180px" height="180px">',
                    imageUrl: "",
                    confirmButtonText: "好的",
                    html: true
                });
            }, function (json) {
                index.functions.showTip(json.message);
            });

        });

        $("#join_box").on("click", function (e) {
            e.stopPropagation();
        });
    },
    functions : {
        fetchUserList : function(success, error){
            var url = $("html").data("user-list-api");
            var data = {
                page : index.data.page,
                size : index.data.size
            };
            $.post(url, data, function (e) {
                var json = JSON.parse(e);
                if(json.status == 200){
                    success(json);
                }else{
                    error(json);
                }
            });
        },
        upFormTable : function () {
            $("#join_box").show();
            $("#cover2").show();
            $("#join_box").animate({bottom:"0px"},200);
        },
        dropDownFormTable : function () {
            $("#join_box").animate({bottom:"-500px"},200);
            $("#join_box").show();
            $("#cover2").hide();
        },
        showTip : function(text){
            $("#toast").text(text);
            var left = ($(window).width()- $("#toast").outerWidth()) / 2 + "px";
            $("#toast").css({
                "bottom": "80px",
                "left": left
            });
            $("#toast").show();
            setTimeout(function () {
                $("#toast").hide();
                $("#toast").text("");
            },2000);
        },
        submitEnroll : function (success, error) {
            var url = $("html").data("enroll-api");
            var data = {
                name : $("input[name=name]").val(),
                mobile : $("input[name=mobile]").val()
            }
            $.post(url, data, function (e) {
                var json = JSON.parse(e);
                if(json.status == 200){
                    success(json);
                }else{
                    error(json);
                }
            })
        }

    }
};
$(function () {
   index.init();
    index.event();
});