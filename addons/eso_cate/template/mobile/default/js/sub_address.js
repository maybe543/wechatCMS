$().ready(function () {
    $(".dropdown_intro").removeClass("intro_down").eq(0).addClass("intro_down");
    $(".dropdown_t").click(function () {
        $(this).siblings().children().filter(".more").removeClass("down");
        $(this).siblings().removeClass("intro_down");
        var a = $(this).find(":nth-child(1)"); - 1 !== a.attr("className").indexOf("down") ? (a.removeClass("down"), $(this).next().removeClass("intro_down")) : (a.addClass("down"), $(this).next().addClass("intro_down"));
        /*
         $("html, body").animate({
         scrollTop: $(this).offset().top
         }, 0)
         */
    });
    var c = $(".buynow > .button"),
        b = c.next(".mult");
    0 < b.length && (c.click(function () {
        /*
         $("html, body").animate({
         scrollTop: $(this).offset().top
         }, 0);
         */
        $(this).next(".mult").toggle();
        return !1
    }), b.find("input[type=checkbox]").bind("click", function () {
        $(this).attr("checked") && (b.find("input[type=checkbox]").attr("checked", !1), $(this).attr("checked", !0), location.href = $(this).val());
        e.preventDefault()
    }), b.find("table").find("td:not(:has(input))").bind("click", function () {
        b.find("input[type=checkbox]").attr("checked", !1);
        var a = $(this).parent().find(":nth-child(1)").find("input[type=checkbox]");
        a.is(":not(:disabled)") && (a.attr("checked", !0), location.href = a.val());
        return !1
    }));
    /* 参数筛选 */
    $(".selectattr").click(function () {
        $(".selectattrval").addClass("show");
    });
    $(".selectattrval").find(".back").click(function () {
        $(".selectattrval").removeClass("show");
    });
    $(".selectattrval").find("input").click(function () {
        var price = 0;
        $(".sku-control").find("input").each(function(){
            if ($(this).attr("checked")){
                if ($(this).attr("data-price")!="") price+= runNum($(this).attr("data-price"));
            }
        });
        if (price > 0){
            $(".selectprice").text("￥"+runNum(runNum($(".selectprice").attr("data-price"))+price));
        }
        //动态取库存
        var attrval = '';
        $(".sku-control").find("ul li").each(function(){
            _n = false;
            $(this).find("input").each(function(){
                if ($(this).attr("checked")) {
                    _n = true;
                    attrval+= $(this).val();
                }
            });
            if (!_n) return false;
        });
        if (_n) {
            $.ajax({
                type: "POST",
                url: $("#intro").attr("data-url")+"&do=stock&id="+$("#intro").attr("data-id"),
                data: "val="+attrval,
                dataType: "json",
                success: function (data) {
                    if (data.success == "1"){
                        $(".selectattrval").find("#quantity").attr("max", data.num).keyup();
                        $(".selectattrval").find(".goodsv").html(data.goodsv);
                        if (data.num > 0){
                            $("button#buynow").removeClass("be");
                        }else{
                            $("button#buynow").addClass("be");
                        }
                    }
                }
            });
        }
    });
    /* 购买数量操作 */
    $(".selectattrval").find(".decrease").click(function () {
        var _num = runNum($(this).next("input").val());
        if (_num > 1) {
            $(this).next("input").val(_num - 1);
        }
    });
    $(".selectattrval").find(".increase").click(function () {
        var _num = runNum($(this).prev("input").val());
        if (_num < runNum($(this).prev("input").attr("max"))) {
            $(this).prev("input").val(_num + 1);
        }else{
            $.alert("已经达到购买数量极限！");
        }
    });
    $(".selectattrval").find("#quantity").keyup(function(){
        var _num = runNum($(this).val());
        var _max = runNum($(this).attr("max"));
        if (_num < 1) $(this).val(1);
        if (_num > _max) $(this).val(_max);
    });
    /* 点击购买 */
    $("a#buynow,button#buynow").click(function () {
        var _n = false;
        var _t = "";
        var _s = "dosubmit=put&goods_id=" + runNum($("#intro").attr("data-id"));
        if ($(".sku-control").length > 0){
            if ($(this).is("a")){
                $(".selectattr").click();
                return;
            }
            if (runNum($(".selectattrval").find("#quantity").attr("max")) < 1){
                $.alert("你的选择不符合！");
                return;
            }
            _s+= "&attr=";
            $(".sku-control").find("ul li").each(function(){
                _n = false;
                _t = $(this).find("h2").text();
                $(this).find("input").each(function(){
                    if ($(this).attr("checked")) {
                        _n = true;
                        _s+= "{**}"+_t+"{::}"+$(this).val()+"{||}";
                    }
                });
                if (!_n) return false;
            });
            if (!_n) {
                if ($(".selectattrval").is(":hidden")){
                    $(".selectattr").click();
                }
                $.alert("请选择" + _t);
                return ;
            }
            _s+= "&number=" + runNum($(".selectattrval").find("#quantity").val());
        }
        $.ajax({
            type: "POST",
            url: $("#intro").attr("data-url")+"&do=cart",
            data: _s,
            dataType: "json",
            success: function (data) {
                if (data.success == "1"){
                    window.location.href = $("#intro").attr("data-url")+"&do=buy&id="+data.id;
                }else{
                    $.alert(data.message);
                }
            },
            error: function (msg) {
                $.alert("提交数据出错！");
            }
        });


    });


});
var myScroll;

function loaded() {
    $("#scroller").css("width", parseInt($("#scroller").width(), 10) * parseInt($(".img_slide").children().size(), 10));
    myScroll = new iScroll("wrapper", {
        snap: "li",
        momentum: !1,
        hScrollbar: !1,
        onScrollEnd: function () {
            document.querySelector(".dot > li.on").className = "";
            document.querySelector(".dot > li:nth-child(" + (this.currPageX + 1) + ")").className = "on"
        }
    })
}

function runNum(str) {
    var _s = Number(str);
    if (_s+"" == "NaN") {
        _s = 0;
    }
    return _s;
}
document.addEventListener("DOMContentLoaded", loaded, !1);