function gotoPay(obj) {
    var ordernumber = $(obj).attr("ordernumber");
    var shopCode = $(obj).attr("shopCode");
    if (shopCode == undefined) {
        shopCode = "";
    }
    $.get("./ajax/GateWay.aspx?action=gotoPay&ordernumber=" + ordernumber + "&shopCode=" + shopCode, function (data) {
        $("#pay").html(data);
    });
}
function orderCancel(obj) {
    var ordernumber = $(obj).attr("ordernumber");
    $.get("./ajax/GateWay.aspx?action=orderCancel&ordernumber=" + ordernumber, function (data) {
        if (data == "成功") {
            location.reload();
        }
    });
}
function okDelivery(obj) {
    var ordernumber = $(obj).attr("ordernumber");
    $.get("./ajax/GateWay.aspx?action=okDelivery&ordernumber=" + ordernumber, function (data) {
        if (data == "成功") {
            location.reload();
        }
    });
}