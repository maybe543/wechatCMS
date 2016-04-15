// JavaScript Document
$(function () {
    $(".item_group").change(function () {
        var tt = $(this).val();
        if (tt != "-1" ) {
            $(this).parent().find(".ui-btn-inner").css("background-color", "#7ABD54");
        } else {
            $(this).parent().find(".ui-btn-inner").css("background-color", "transparent");
        }
    });
    $(".item_group").parent().parent().find(".ui-btn-inner").append("<div class='bt_help'></div>");
    $(".item_group").parent().parent().find(".ui-btn-inner").append("<div style='display:none;' class='bt_help_box'></div>");

    $(".item_group").change(function () {
        $(".text_price").html("——");
        $(".inquiry_result").slideUp();
        $("#get_new_price").show();
        $("#sale_now").hide();
    });


    $(".bt_help").click(function () {
        if ($(this).next().css("display") == "none") {
            var a_id = $(this).parent().parent().find("select").attr("i_id");
            $.ajax({
                type: "post",
                url: "AjaxData/Product/",
                data: { action: "get_help", a_id: a_id },
                dataType: "json",
                success: function (data) {
                    $(".bt_help_box").html(data.remark);
                }

            });
            $(".bt_help_box").fadeOut();
            $(this).next().fadeIn();

        } else {
            $(this).next().fadeOut();
        }
    });
    //��������
    $("#sale_now").click(function () {

        var address = $("select[name=address] option:selected").val()
        location.href = "Inquiry_setup.aspx?area="+address;
    })

    $("#get_new_price").click(function () {
/*        var items = "";
        var sels = $(".item_group");
        for (var i = 0; i < sels.length; i++) {
            if ($(sels[i]).val() == "0") {
                alert($(sels[i]).attr("tips"));
                return;
            }
            if ($(sels[i]).val() != "" && $(sels[i]).val() != null) {
                items += $(sels[i]).val() + ",";
            }
        }
        var address = $("select[name=address] option:selected").val()
        if (address == "") {
            alert("��ѡ�����ڳ���");
            return;
        }
				alert("123");

        $.ajax({
            type: "post",
            url: "AjaxData/Product/",
            data: { action: "appr", items: items, mobile_id: $("#mobile_id").val() },
            dataType: "json",
            success: function (data) {
                if (data.succ) {
                    $(".amount").html("&yen;" + data.appraisal);
                    $(".freight").html("&yen;" + data.freight);
                    $(".inquiry_result").slideDown();
                    $("#get_new_price").hide();
                    $("#sale_now").show();
                } else {
                    alert(data.msg);
                }
            },
            error: function (req) {
                alert("����ʧ�ܣ�����ϵ�ͷ���Ա�����");
            },
            beforeSend: function () {
                $(".ui-loader").show();
            },
            complete: function () {
                $(".ui-loader").hide();
            }

        });*/
    });
})