$.fn.addtocart = function(obj) {
    var tthis = this;
    //
    var _ids = '',_ido = '';
    $(this).each(function(){
        if ($(this).attr("data-cartid") > 0) {
            $(this).addClass("selshadow");
            _ido+= $(this).attr("data-id") + ',';
        }
        _ids+= $(this).attr("data-id") + ',';
    });
    $.ajax({
        type: "POST",
        url: $("body").attr("data-url")+"&do=cart_reset&vtime=" + Math.round(new Date().getTime()),
        data: {"ids":_ids,"ido":_ido},
        dataType: "json",
        success: function (data) {
            if (data.success == "1"){
                if (data.carts) {
                    $.each(data.carts, function(index, content) {
                        $("a[data-id="+index+"]").addClass("selshadow").attr("data-cartid", content);
                    });
                }
                if (data.cartn) {
                    $.each(data.cartn, function(index, content) {
                        $("a[data-id="+index+"]").removeClass("selshadow").attr("data-cartid", 0);
                    });
                }
                if (data.cartnum || data.cartnum === 0) {
                    var _a_shop = $(obj).find("em");
                    _a_shop.text(data.cartnum).hide();
                    if (data.cartnum > 0) _a_shop.show();
                }
            }
        },
        cache: false
    });
    //
    $(this).click(function(){
        var eve = $(this);
        if (eve.attr("data-cartid") > 0) {
            eve.removeClass("selshadow");
            $.ajax({
                type: "GET",
                url: $("body").attr("data-url")+"&do=delcart&id=" + eve.attr("data-cartid"),
                dataType: "json",
                success: function (data) {
                    eve.attr("data-cartid", "0");
                    if (data.success != "1"){
                        $.alertk(data.message);
                    }
                    if (data.cartnum || data.cartnum === 0) {
                        var _a_shop = $(obj).find("em");
                        _a_shop.text(data.cartnum).hide();
                        if (data.cartnum > 0) _a_shop.show();
                    }
                },
                error: function (msg) {
                    $.alertk("提交数据出错！");
                }
            });
            return;
        }
        /** 弹出属性 */
        if (eve.attr("data-isattr") > 0) {
            $.alert("正在加载...",0,1);
            tthis._attr(eve);
            return;
        }
        /** 加入购物车*/
        eve.addClass("selshadow");
        tthis._flyelm(eve);
        //提交数据
        var _s = "dosubmit=put&goods_id=" + eve.attr("data-id") + "&carttype=add";
        if (eve.attr("data-isattr").indexOf('{**}') != -1) {
            _s+= "&attr=" + eve.attr("data-isattr");
        }
        $.ajax({
            type: "POST",
            url: $("body").attr("data-url")+"&do=cart",
            data: _s,
            dataType: "json",
            success: function (data) {
                eve.attr("data-cartid", data.id);
                if (data.success != "1" && data.message != "加入购物车成功！"){
                    eve.removeClass("selshadow");
                    $.alertk(data.message);
                }
                if (data.cartnum || data.cartnum === 0) {
                    var _a_shop = $(obj).find("em");
                    _a_shop.text(data.cartnum).hide();
                    if (data.cartnum > 0) _a_shop.show();
                }
            },
            error: function (msg) {
                eve.removeClass("selshadow");
                $.alertk("提交数据出错！");
            }
        });

    });
    //
    this._attr = function (eve) {
        var tthis = this;
        var goodsid = eve.attr("data-id");
        var hash = window.location.hash;
        var href = window.location.href.replace(new RegExp(hash,"g"), "");
        window.location.href = href + '#gc/' + goodsid;
        $.ajax({
            type: "GET",
            url: $("body").attr("data-url")+"&do=goodsattr&id=" + goodsid,
            dataType: "html",
            success: function (html) {
                $.alert(0);
                $intemp = $(html);
                $("body").append($intemp);
                /* 显示 */
                setTimeout(function(){$intemp.addClass("show");},100);
                /* 点击关闭 */
                $intemp.find(".back").click(function () {
                    window.history.go(-1);
                    /*$intemp.removeClass("show");
                     setTimeout(function(){$intemp.remove();},200);*/
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
                /* 点击加入购物车操作 */
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
                        _s+= "&number=" + tthis._runNum($intemp.find("#quantity").val());
                    }
                    $.alert("请稍等...",0,1)
                    eve.addClass("selshadow");
                    $.ajax({
                        type: "POST",
                        url: $("body").attr("data-url")+"&do=cart",
                        data: _s,
                        dataType: "json",
                        success: function (data) {
                            $.alert(0);
                            eve.attr("data-cartid", data.id);
                            if (data.success != "1" && data.message != "加入购物车成功！"){
                                eve.removeClass("selshadow");
                                $.alertk(data.message);
                            }
                            if (data.cartnum || data.cartnum === 0) {
                                var _a_shop = $(obj).find("em");
                                _a_shop.text(data.cartnum).hide();
                                if (data.cartnum > 0) _a_shop.show();
                            }
                            $intemp.find(".back").click();
                            tthis._flyelm(eve);
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
    };
    this._runNum = function(str) {
        var _s = Number(str);
        if (_s+"" == "NaN") {
            _s = 0;
        }
        return _s;
    }
    this._flyelm = function(eve) {
        var flyElm = eve.find("img").clone().css('opacity','0.8');
        flyElm.css({
            'z-index': 9000,
            'display': 'block',
            'position': 'absolute',
            'top': eve.offset().top +'px',
            'left': eve.offset().left +'px',
            'width': eve.width() +'px',
            'height': eve.height() +'px'
        });
        $('body').append(flyElm);
        flyElm.animate({
            top:$(obj).offset().top,
            left:$(obj).offset().left,
            width:50,
            height:50
        },'slow',function(){
            flyElm.remove();
        });
    }
};
var _cart_hashchange = function(){
    var hash = window.location.hash;
    if (!/^\#gc\//.test(hash)) {
        $("div.selectattrval").removeClass("show");
        setTimeout(function(){$("div.selectattrval").remove();},200);
    }
};
window.addEventListener("hashchange", _cart_hashchange, false);
