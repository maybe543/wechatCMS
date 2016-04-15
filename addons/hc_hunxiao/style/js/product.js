/*快速购买*/
$(document).ready(function () {

    $("div[name='specvals']").each(function () {
        if (this.children.length === 1) {
            change(this.children[0]);
        }
    })

    $('#submit_ok').bind("click", function () {

        var trueId = "";
        var count = "";
        var $uiskuprop = $(".s-buy-ul .right span");
        var $uiskupropCount = $(".s-buy-ul .s-buy-li").length - 1;
        var flag = 0;
        $($uiskuprop).each(function () {
            flag = $(this).attr("checked") == "checked" ? flag + 1 : flag; //判断所有规格是否都选完整了
        });

        if ($uiskupropCount === flag) {
            var purchaseSum = $("#purchaseSum").val() * 1;
            var num = $("#num").val() * 1;
            var nummax = $("#num").attr('max') * 1;
            if (num >= 1) {
                if (num <= nummax) {
                    if (num <= purchaseSum || purchaseSum == 0) {
                        $("#loading").show();
                        trueId = $("#hiddPDetailID").val();
                        count = $("#num").val();
                        $.ajax({
                            url: "cart.aspx?act=fastbuy&TureProductId=" + trueId + "&BuyNumber=" + count,
                            async: true,
                            type: "post",
                            cache: false,
                            success: function (data) {
                                if (!CheckInt(data)) {
                                    $.ajax({
                                        url: "cart.aspx?act=fastbuy&TureProductId=" + trueId + "&BuyNumber=" + count,
                                        async: true,
                                        type: "post",
                                        cache: false,
                                        success: function (data2) {
                                            data = data2;
                                        }
                                    })
                                }

                                if (data == 1) {
                                    $("#countcart").show();
                                }
                                if ($("#submit_ok").attr("tag") == "addCart") {
                                    var tempCoun = purchaseSum - count;
                                    if (tempCoun == 0) {
                                        $("#purchaseSum").val(-1);
                                    } else {
                                        $("#purchaseSum").val(purchaseSum - count);
                                    }
                                    $("#s_buy").slideUp();
                                    $("#mask").hide();
                                    $("#addcart_way").show();
                                    $("#addcart_way").addClass("addcart-way");
                                    setTimeout(function () {
                                        $("#success_tip_line").fadeIn();
                                    }, 1000);
                                    setTimeout(function () {
                                        $("#success_tip_line").fadeOut();
                                    }, 3000);

                                } else {

                                    window.location.href = "cart.aspx";
                                }
                                $("#countcart").text(data);
                                $("#loading").hide();

                            }
                        });
                    } else {
                        if (purchaseSum <= 0) {
                            purchaseSum = 0;
                        }
                        showBox("此商品限购，您最多还可购买" + purchaseSum + "件");
                    }
                } else {
                    showBox("库存不足");
                }
            } else {
                showBox("商品的数量至少为1");
            }
        } else {
            showBox("请选择完整的商品规格");
        }

    });



    function CheckInt(obj) {
        var pattern = /^[1-9]\d*|0$/; //匹配非负整数
        if (!pattern.test(obj)) {
            return false;
        } else {
            return true;
        }
    }



    function preventNo(e) {
        e.preventDefault();
    }

    $('#btnShare').bind("click", function () {
        var topheight = document.body.scrollTop;
        var scrollHeight = document.body.scrollHeight;
        $("#mask-bg").attr("style", "height:" + (scrollHeight + topheight) + "px");
        $("#mask-content").attr("style", "padding-top:" + topheight + "px");
        $("#mask-bg").show();
        $("#mask-content").show();
        document.addEventListener('touchmove', preventNo, false);

    });
    $('#mask-bg').bind("click", function () {
        $("#mask-bg").hide();
        $("#mask-content").hide();
        document.removeEventListener('touchmove', preventNo, false);
    });
    $('#mask-content').bind("click", function () {
        $("#mask-bg").hide();
        $("#mask-content").hide();
        document.removeEventListener('touchmove', preventNo, false);
    });


    //huxl
    $("#distribution-apply").click(function (event) {
        event.preventDefault();
        $("#distribution-tip").fadeIn();
        setTimeout(function () {
            $("#distribution-tip").fadeOut();
        }, 4000)
    }
	);
    //close advertisement
    $("#advertisement-close").click(function () {
        $("#advertisement-apptip").hide();
        $("#fromesb-wechat").animate({
            top: 0
        });
    })
    //contact float
    $("#contFloat").click(function (event) {
        event.preventDefault();
        $("#contFloat-detail").show();
    })
    $("#contFloat-detail-close").click(function () {
        $("#contFloat-detail").hide();
    })

    //shopping progress
    $("#addCart").click(function (e) {
        $("#mask").show();
        $('body').css("overflow", "hidden");
        $("#s_buy").slideDown();
        $("#addcart_way").removeClass("addcart-way")
        $("#submit_ok").attr("tag", e.target.id);
		$("#flag").val(0);
    })
	 $("#buyBtn1").click(function (e) {
        $("#mask").show();
        $('body').css("overflow", "hidden");
        $("#s_buy").slideDown();
        $("#addcart_way").removeClass("addcart-way")
        $("#submit_ok").attr("tag", e.target.id);
		$("#flag").val(1);
    })
    $("#mask,#icon_close").click(function () {
        $("#s_buy").slideUp();
        $("#mask").hide();
        $('body').css("overflow", "auto");
    })
    //$("#submit_ok").click(function () {
    //    $("#s_buy").slideUp();
    //    $("#mask").hide();
    //    $("#addcart_way").show();
    //    $("#addcart_way").addClass("addcart-way");
    //    setTimeout(function () {
    //        $("#success_tip_line").fadeIn();
    //    }, 1000);
    //    setTimeout(function () {
    //        $("#success_tip_line").fadeOut();
    //    }, 3000);

    //})

});

var specificationValueDatas = {};
var productDatas = {};
var obj = {
    Span1: "",
    Span2: "",
    Span3: "",
    Span4: ""
};

function change(span) {

    $('span[name=' + $(span).attr('name') + ']').each(function () {
        //        if (this.checked && this != span) {
        $(this).removeClass("current");
        $(this).attr("checked", false);
        //        } else {
        //            $(span).removeClass("current");
        //            this.checked = false;
        //        }
    });
    obj[$(span).attr('name')] = span.innerHTML;
    $(span).addClass("current");
    $(span).attr("checked", true);

    var specificationValueSelecteds = new Array();
    var $specificationValueSelected = $(".s-buy-ul .right span");
    $specificationValueSelected.each(function (i) {
        var $this = $(this);
        if ($this.attr("checked") === "checked") {
            specificationValueSelecteds.push($this.attr("id"));
        }
    });

    $.each(specificationValueDatas, function (i) {
        if (specificationValueDatas[i].sort().toString() == specificationValueSelecteds.sort().toString()) {
            //                       $productSn = $productSn.text(productDatas[i].productSn);
            $("#price").text("¥" + productDatas[i].SalesPrice);
            $("#num").attr("max", productDatas[i].Stock);
            $("#hiddPDetailID").val(productDatas[i].productDetailID);
            $("#Stock").html("剩余" + productDatas[i].Stock + "件");
        }
    });


    select();
}

function select() {
    var html = '';
    for (var i in obj) {
        if (obj[i] != '') {
            html += '<font color=orange>"' + obj[i] + '"</font> 、';
        }
    }
    //    html = '<b> 已选择:</b> ' + html.slice(0, html.length - 1);
    //    $('#resultSpan').html(html);

}

function imgview() {

    var arr = $("#imgs").val();
    var c = arr.substring(0, arr.length - 1).split(',');
    var index = $("#imgpage").text().split('/') - 1;
    if (typeof window.WeixinJSBridge != 'undefined') {
        WeixinJSBridge.invoke("imagePreview", {
            current: c[index],
            urls: c
        });
    }
}
function showPic() {
    $("#content").html(hdata);
    $("#p-detailoff").hide();
    $("#p-detail").show();

};
window.onload = function () {
    if (typeof window.WeixinJSBridge != 'undefined') {
        document.addEventListener("WeixinJSBridgeReady", onWeixinReady, false);
    } else {
        $("#p-detailoff").show();
    }
}
function onWeixinReady() {
    WeixinJSBridge.invoke('getNetworkType', {}, function (e) {
        WeixinJSBridge.log(e.err_msg);
        var state = e.err_msg.split(':')[1];
        if (state == "wifi") {
            $("#content").html(hdata);
            $("#p-detail").show();
        } else {
            $("#p-detailoff").show();
        }
    });
}
$(function () {
    $(".add").click(function () {
        var num = $("#num").val() * 1;
        var nummax = $("#num").attr('max') * 1;
        if (num < nummax) {
            num = num + 1;
        }
        $("#num").val(num);
    })
    $(".reduce").click(function () {
        var num = $("#num").val() * 1;
        if (num > 1) {
            num = num - 1;
        }
        $("#num").val(num);
    })
    $("#num").bind("input propertychange", function () {
        var num = $(this).val() * 1;
        var nummax = $(this).attr('max') * 1;
        if (num > nummax) {
            num = nummax;
        }
        if (isNaN(num)) {
            num = 1;
        }
        $(this).val(num);


    })
})
