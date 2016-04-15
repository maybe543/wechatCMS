
function saveAddress() {
    if (!Check_Consignee()) {
        return false;
    }
    var url = "";
    var addressID = $("#AddressID").val();
    var tempSeleAreaFouth = $("#seleAreaFouth").find("option:selected").text();
    //表示没有区县
    if (tempSeleAreaFouth == "选择区/县") {
        tempSeleAreaFouth = "";
    }
    var addressinfo = $("#seleAreaNext").find("option:selected").text() + $("#seleAreaThird").find("option:selected").text() + tempSeleAreaFouth + $("#AddressInfo").val();
    var AreaCode = "1," + $("#seleAreaNext").val() + "," + $("#seleAreaThird").val() + "," + $("#seleAreaFouth").val();
    if (addressID == "-1") {
        url = "action=saveAddress&name=" + $("#Name").val() + "&addressinfo=" + addressinfo + "&moblie=" + $("#Moblie").val() + "&AreaCode=" + AreaCode + "&Remark=" + $("#AddressInfo").val();
    } else {
        url = "action=saveAddress&name=" + $("#Name").val() + "&addressinfo=" + addressinfo + "&moblie=" + $("#Moblie").val() + "&AreaCode=" + AreaCode + "&Remark=" + $("#AddressInfo").val() + "&addressid=" + addressID;
    }
    $.ajax({
        type: "POST",
        dataType: "text",
        url: "ajax/GateWay.aspx",
        data: url,
        success: function (txt) {
            //alert(txt);
            if (txt == "成功") {

                var pageurl = request("url");
                if (pageurl == "") {
                    window.location.href = "addresslist.aspx"
                } else {
                    window.location.href = "../" + pageurl;
                }
            } else {
                ShowMsg(txt);
            }
        }
    });
}

function Check_Consignee() {
    var reg = /^\d{11}$/;
    if ($("#Name").val() == "") {
        ShowMsg("收货人姓名不能为空");
        $("#Name").focus();
        return false;
    } else if ($("#Moblie").val() == "") {
        ShowMsg("手机号码不能为空");
        $("#Moblie").focus();
        return false;
    } else if (!reg.test($("#Moblie").val())) {
        ShowMsg("请输入正确的手机号码");
        $("#Moblie").focus();
        return false;
    } else if ($("#AddressInfo").val() == "") {
        ShowMsg("详细地址不能为空");
        $("#AddressInfo").focus();
        return false;
    } else if ($("#seleAreaFouth").val() < 0 || $("#seleAreaFouth").val() == "") {
        if ($("#seleAreaNext").val() == "") {
            ShowMsg("请选择省份");
            $("#seleAreaNext").focus();
            return false;
        }
        if ($("#seleAreaThird").val() == "") {
            ShowMsg("请选择市");
            $("#seleAreaThird").focus();
            return false;
        }

        if ($("#seleAreaFouth")[0].length == 1 && $("#seleAreaThird")[0].length > 1 && $("#seleAreaThird").val() > -1) {
            return true;
        } else {
            ShowMsg("请选择区/县");
            $("#seleAreaFouth").focus();
            return false;
        }
    } else {
        return true;
    }
}