$.fn.editcart = function() {
    var tthis = this;
    this._runNum = function(str) {
        var _s = Number(str);
        if (_s+"" == "NaN") {
            _s = 0;
        }
        return _s;
    }
    $(this).click(function(){
        var cartid = $(this).parents("li").attr("data-id");
        var goodsid = $(this).parents("li").attr("data-goodsid");
        var hash = window.location.hash;
        var href = window.location.href.replace(new RegExp(hash,"g"), "");
        window.location.href = href + '#gattr/' + cartid;
        $.alert("正在加载...",0,1);
        $.ajax({
            type: "GET",
            url: $("body").attr("data-url")+"&do=cartattr&id=" + cartid,
            dataType: "html",
            success: function (html) {
                $.alert(0);
                $intemp = $(html);
                $("body").append($intemp);
                /* 显示 */
                setTimeout(function(){ $intemp.addClass("show"); },100);
                /* 点击关闭 */
                $intemp.find(".back").click(function () {
                    window.history.go(-1);
                    $intemp.removeClass("show");
                    setTimeout(function(){ $intemp.remove(); },200);
                });
                /* 购买数量操作 */
                $intemp.find(".decrease").click(function () {
                    var _num = tthis._runNum($(this).next("input").val());
                    if (_num > 1) {
                        $(this).next("input").val(_num - 1);
                    }
                });
                $intemp.find(".increase").click(function () {
                    var _num = tthis._runNum($(this).prev("input").val());
                    if (_num < tthis._runNum($(this).prev("input").attr("max"))) {
                        $(this).prev("input").val(_num + 1);
                    }else{
                        $.alertk("已经达到购买数量极限！");
                    }
                });
                $intemp.find("#quantity").keyup(function(){
                    var _num = tthis._runNum($(this).val());
                    var _max = tthis._runNum($(this).attr("max"));
                    if (_num < 1) $(this).val(1);
                    if (_num > _max) $(this).val(_max);
                });
                /* 选择参数操作 */
                $intemp.find("input").click(function () {
                    if ($(this).is("#quantity")) {
                        return ;
                    }
                    var price = 0;
                    $intemp.find(".sku-control").find("input").each(function(){
                        if ($(this).attr("checked")){
                            if ($(this).attr("data-price")!="") price+= tthis._runNum($(this).attr("data-price"));
                        }
                    });
                    $intemp.find(".selectprice").text("￥"+tthis._runNum(tthis._runNum($intemp.find(".selectprice").attr("data-price"))+price));
                    //动态取库存
                    var attrval = '';
                    var _n = false;
                    $intemp.find(".sku-control").find("ul li").each(function(){
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
                        $.alert("请稍等...",0,1)
                        $.ajax({
                            type: "POST",
                            url: $("body").attr("data-url")+"&do=stock&id="+goodsid,
                            data: "val="+attrval,
                            dataType: "json",
                            success: function (data) {
                                $.alert(0)
                                if (data.success == "1"){
                                    $intemp.find("#quantity").attr("max", data.num).keyup();
                                    $intemp.find(".goodsv").html(data.goodsv);
                                    if (data.num > 0){
                                        $("button#buynow").removeClass("be");
                                    }else{
                                        $("button#buynow").addClass("be");
                                    }
                                }
                            },
                            error : function () {
                                window.location.reload();
                            }
                        });
                    }
                });
                /* 点击确定操作 */
                $intemp.find("button#buynow").click(function () {
                    var _type = $(this).attr("data-type");
                    var _n = false;
                    var _t = "";
                    var _s = "dosubmit=put&goods_id=" + goodsid + "&carttype=" + _type;
                    if ($intemp.find(".sku-control").length > 0){
                        if (tthis._runNum($intemp.find("#quantity").attr("max")) < 1){
                            $.alertk("你的选择不符合！");
                            return;
                        }
                        _s+= "&attr=";
                        $intemp.find(".sku-control").find("ul li").each(function(){
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
                            $.alertk("请选择：" + _t);
                            return ;
                        }
                    }
                    _s+= "&number=" + tthis._runNum($intemp.find("#quantity").val());
                    $.alert("请稍等...",0,1)
                    $.ajax({
                        type: "POST",
                        url: $("body").attr("data-url")+"&do=cart",
                        data: _s,
                        dataType: "json",
                        success: function (data) {
                            //$.alert(0);
                            if (data.success != "1" && data.message != "加入购物车成功！"){
                                $.alertk(data.message);
                            }else{
                                if (!_n) {
                                    //无参数仅更改数量
                                    window.location.href = href;
                                }else{
                                    //加入新的进入购物车以后删除旧的
                                    $.ajax({
                                        type: "GET",
                                        url: $("body").attr("data-url")+"&do=delcart&id=" + cartid + "&type=only",
                                        dataType: "json",
                                        success: function (data) {
                                            window.location.href = href;
                                        },
                                        error: function (msg) {
                                            window.location.href = href;
                                        }
                                    });
                                }
                            }
                        },
                        error: function (msg) {
                            $.alertk("提交数据出错！");
                        }
                    });
                });
            },
            error: function (msg) {
                $.alertk("加载失败！");
            }
        });
    });
    this._cart_hashchange = function(){
        var hash = window.location.hash;
        if (!/^\#gattr\//.test(hash)) {
            $("div.selectattrval").removeClass("show");
            setTimeout(function(){$("div.selectattrval").remove();},200);
        }
    };
    window.addEventListener("hashchange", tthis._cart_hashchange, false);
};