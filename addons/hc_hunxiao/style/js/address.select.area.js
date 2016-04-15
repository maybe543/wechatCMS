
$(document).ready(function () {
    selectNextarea(1);
    if ($("#SeletctAreaId").val() != "" && $("#SeletctAreaId").val() != undefined) {
        var areacode = $("#AreaCode").val().split(',');
        $("#seleAreaNext").val(areacode[1]);
        selectThirarea(areacode[1]);
        $("#seleAreaThird").val(areacode[2]);
        selectFoutharea(areacode[2]);
        $("#seleAreaFouth").val(areacode[3]);

        //        seleAreaNext(areacode[1]);
        //        selectThirarea(areacode[2]);
        //        selectFoutharea(areacode[3])
    } else {

    }
});

////区域选择
//function selectFirstarea() {
//    $.ajax({ url: "/MemberShip/ajax/selearealist.ashx?fatherid=0",
//        success: function (xml) {
//            $("#selFirstarea").empty();
//            $("#selFirstarea").prepend("<option value=''>请选择</option>");
//            var strs = "";
//            $(xml).find("node").each(function () {
//                var name = $(this).attr("name"); //this->
//                var thisid = $(this).attr("id");

//                strs += "<option value='" + thisid + "'>" + name + "</option>";

//            });

//            $("#selFirstarea").append(strs);
//            $("#selFirstarea").change(function () {

//                $("#SeletctAreaId").val(this.value);
//                selectNextarea(this.value); 
//            });
//        }
//    });
//}

//二级区域选择
function selectNextarea(objvalue) {
    $("#SeletctAreaId").val(objvalue);
    if (objvalue != "") {
        ;
        $.ajax({ url: "ajax/selearealist.ashx?fatherid=" + objvalue,
            async: false,
            success: function (xml) {
                var strs = "";
                $(xml).find("node").each(function () {
                    var name = $(this).attr("name"); //this->
                    var thisid = $(this).attr("id");
                    strs += "<option value='" + thisid + "'>" + name + "</option>";
                });
                $("#seleAreaNext").empty();
                $("#seleAreaNext").prepend("<option value=''>选择省</option>");
                $("#seleAreaNext").append(strs);
                $("#seleAreaNext").change(function () {
                    //                    if (areacode[1] != undefined) {
                    //                        this.value = areacode[1];
                    //                    };
                    $("#SeletctAreaId").val(this.value);
                    $("#seleAreaFouth").empty();
                    selectThirarea(this.value);
                   
                   // $("#seleAreaFouth").prepend("<option value=''>选择区/县</option>");
                });
            }
        });
    }
    else {
        $("#seleAreaNext").empty();
        $("#seleAreaThird").empty(); ;
        $("#seleAreaFouth").empty();
    }
}


//三级区域选择
function selectThirarea(objvalue) {
    $("#seleAreaThird").empty();
    if (objvalue != "") {
        $.ajax({ url: "ajax/selearealist.ashx?fatherid=" + objvalue,
            async: false,
            success: function (xml) {
                var strs = "";
                $(xml).find("node").each(function () {
                    var name = $(this).attr("name"); //this->
                    var thisid = $(this).attr("id");
                    strs += "<option value='" + thisid + "'>" + name + "</option>";
                });
                $("#seleAreaThird").empty();
                $("#seleAreaThird").prepend("<option value=''>选择市</option>");
                $("#seleAreaFouth").prepend("<option value=''>选择区/县</option>");
                $("#seleAreaThird").append(strs);
                $("#seleAreaThird").change(function () {
                    $("#SeletctAreaId").val(this.value);
                    selectFoutharea(this.value);
                });
            }
        });
    }
    else {
        $("#seleAreaThird").empty();
        $("#seleAreaFouth").empty();
    }
}



//四级区域选择
function selectFoutharea(objvalue) {
    if (objvalue != "") {
        $.ajax({ url: "ajax/selearealist.ashx?fatherid=" + objvalue,
            async: false,
            success: function (xml) {
                var strs = "";
                $(xml).find("node").each(function () {
                    var name = $(this).attr("name"); //this->
                    var thisid = $(this).attr("id");

                    strs += "<option value='" + thisid + "'>" + name + "</option>";

                });
                $("#seleAreaFouth").empty();
                $("#seleAreaFouth").prepend("<option value=''>选择区/县</option>");
                if (strs == "") {
                }
                else {
                    $("#seleAreaFouth").append(strs);
                    $("#seleAreaFouth").change(function () {
                        $("#SeletctAreaId").val(this.value);
                    });
                }
            }
        });
    }
    else {
        $("#seleAreaFouth").empty();
    }
}