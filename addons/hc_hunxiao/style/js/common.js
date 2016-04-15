function getobj(id) {
    return document.getElementById(id);
}

function selecttab(obj, act, def, special, areaname) {
    var node = childs(obj.parentNode.parentNode.childNodes);
    for (var i = 0; i < node.length; i++) {
        if (node[i].className != special) {
            node[i].className = def;
            if (getobj(areaname + "_" + i))
                getobj(areaname + "_" + i).style.display = "none";
        }
    }
    obj.parentNode.className = act;
    for (var i = 0; i < node.length; i++) {
        if (node[i].className != special) {
            if (node[i].className == act) {
                if (getobj(areaname + "_" + i))
                    getobj(areaname + "_" + i).style.display = "";
            }
        }
    }
}
//鍏煎FF涓嬬殑childNodes
function childs(nodes) {
    if (!nodes.length)
        return [];
    var ret = [];
    for (var i = 0; i < nodes.length; i++) {
        if (nodes[i].nodeType != 1)
            continue;
        ret.push(nodes[i]);
    }
    return ret;
}

//验证码
function ChangeVerifyImgNew(url) {
    document.getElementById("verifyimg").src = url + 'member/verifyimg.aspx?d=' + Date();
}

//头部搜索
function changeURL() {
    if (document.getElementById('SearchType').options[0].selected) {
        document.getElementById('searchform').action = spath + 'list.aspx';
    }
    if (document.getElementById('SearchType').options[1].selected) {
        document.getElementById('searchform').action = spath + 'articlelist.aspx';
    }
    if (document.getElementById('SearchType').options[2].selected) {
        document.getElementById('searchform').action = spath + 'shippingadvice.aspx';
    }
}

//头部搜索以后选中状态
function ChooseSelect() {

    var url = document.URL;
    if (url.indexOf("list.aspx") != -1) {
        document.getElementById('SearchType').selectedIndex = 0;
    }
    else if (url.indexOf("articlelist.aspx") != -1) {
        document.getElementById('SearchType').selectedIndex = 1;
    }
    else if (url.indexOf("shippingadvice.aspx") != -1) {
        document.getElementById('SearchType').selectedIndex = 2;
    }
}

//控制只能输入数字
function isNumber(e) {
    if (navigator.userAgent.indexOf("MSIE") != -1) {
        if (event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 37 || event.keyCode == 39
			|| (event.keyCode >= 48 && event.keyCode <= 57) || (event.keyCode >= 96 && event.keyCode <= 105)) {
            return true;
        } else {
            return false;
        }
    } else {
        if (e.which == 46 || e.which == 8 || e.which == 37 || e.which == 39
			|| (e.which >= 48 && e.which <= 57) || (e.which >= 96 && e.which <= 105)) {
            return true;
        } else {
            return false;
        }
    }
}

//头部搜索
$(document).ready(function () {
    $("#txtWd").click(function () {
        $("#txtWd").val("");
    });
    showmenu();
});
//切换头部菜单选中效果
function showmenu() {
    var url = document.URL;
    $(".Menu-ul li").removeClass();
    //    alert(url.indexOf("brand"));
    //商品详细页
    if (url.indexOf("product") > -1) {
        $($(".Menu-ul li").get(1)).addClass("current");
        return;
    }
    //文章详细页
    if (url.indexOf("article") > -1) {
        $($(".Menu-ul li").get(2)).addClass("current");
        return;
    }
    //品牌详细页
    if (url.indexOf("brand") > -1) {
        $($(".Menu-ul li").get(3)).addClass("current");
        return;
    }
    //默认首页
    if (url.indexOf(".aspx") == -1 && url.indexOf("http") > -1) {
        $($(".Menu-ul li").get(0)).addClass("current");
        return;
    }
    $(".Menu-ul li").each(function (i) {
        if (url.indexOf($(this).find("a").attr("href")) > -1) {
            $(this).addClass("current");
            return;
        }
    });
}
function jsqiehuan(obj, perobj) {
    //    for (var id = 0; id <= 6; id++) {
    //        if (id == num && document.getElementById("jsmynav" + id).className != "current") {
    //            document.getElementById("jsmynav" + id).className = "mouseover";
    //        }
    //        else {
    //            if (document.getElementById("jsmynav" + id).className != "current") {
    //                document.getElementById("jsmynav" + id).className = "";
    //            }
    //        }
    //    }


    if (obj.className != "current") {
        obj.className = "mouseover";

    }

    if (perobj != "") {
        if (document.getElementById(perobj).className != "current") {
            $("#" + perobj).removeClass("mouseout");
            $("#" + perobj).addClass("mouseclear");
        }
    }

}

function jsqingkong(obj, perobj) {
    //    for (var id = 0; id <= 6; id++) {

    //        if (id != null && document.getElementById("jsmynav" + id).className != "current") {
    //            document.getElementById("jsmynav" + id).className = "";
    //        } 
    //    }

    if (obj.className != "current") {
        obj.className = "mouseout";

    }

    if (perobj != "") {
        if (document.getElementById(perobj).className != "current") {
            $("#" + perobj).removeClass("mouseclear");
            $("#" + perobj).addClass("mouseout");
        }
    }

}


function searchproductName(e) {
    if ($(e).val() == "商品名称...") {
        $(e).val("");
    }
}
function blursearchproductName(e) {
    var value = $(e).val();
    if ($(e).val() == "" || $(e).val() == undefined) {
        $(e).val("商品名称...");
    }
}
function hideBox() {
    $("#popup").hide();
};
function showBox(text) {

    $("#popup").html("<p>" + text + "</p>");
    $("#popup").show();
    setTimeout("hideBox()", 2000); //3秒
};
