(function($) {
    $.fn.unveil = function(threshold, callback) {
        var $w = $(window), th = threshold || 0, retina = window.devicePixelRatio > 1, attrib = retina ? "data-src-retina" : "data-original", images = this, loaded;
        this.one("unveil", function() {
            var source = this.getAttribute(attrib);
            source = source || this.getAttribute("data-original") || this.getAttribute("data-src") || this.getAttribute("data-url");
            if (this.not_new == "yes") {
                return false
            }
            if (source) {
                this.jumei_bg = this.src;
                this.setAttribute("src", source);
                this.onerror = function() {
                    this.not_new = "yes";
                    this.setAttribute("src", this.jumei_bg);
                    $(this).css({"opacity": 1, "-webkit-transition": "all 600ms ease"})
                };
                this.onload = function() {
                    this.not_new = "yes";
                    $(this).css({"opacity": 1, "-webkit-transition": "opacity 600ms ease"})
                };
                if (typeof callback === "function") {
                    callback.call(this)
                }
            }
        });
        function unveil() {
            var inview = images.filter(function() {
                var $e = $(this), wt = $w.scrollTop(), wb = wt + $w.height(), et = $e.offset().top, eb = et + $e.height();
                return eb >= wt - th && et <= wb + th
            });
            loaded = inview.trigger("unveil");
            images = images.not(loaded)
        }
        $w.bind("scroll", function() {
            unveil()
        });
        $w.bind("resize", function() {
            unveil()
        });
        unveil();
        return this
    }
})(window.jQuery || window.Zepto);
var JM = window.JM || {}, JM = $.extend(JM, {namespace: function() {
        var e = arguments, t = null, n, r, i;
        for (n = 0; n < e.length; n++) {
            i = e[n].split("."), t = JM;
            for (r = "JM" == i[0] ? 1 : 0; r < i.length; r++) {
                t[i[r]] = t[i[r]] || {}, t = t[i[r]]
            }
        }
        return t
    }});
JM.namespace("touch"), function(a, b, c) {
    if (c in b && b[c]) {
        var d, e = a.location, f = /^(a|html)$/i;
        a.addEventListener("click", function(a) {
            for (d = a.target; !f.test(d.nodeName); ) {
                d = d.parentNode
            }
            "href" in d && (chref = d.href).replace(e.href, "").indexOf("#") && (!/^[a-z\+\.\-]+:/i.test(chref) || 0 === chref.indexOf(e.protocol + "//" + e.host)) && (a.preventDefault(), e.href = d.href)
        }, !1)
    }
}(document, window.navigator, "standalone"), function() {
    JM.touch = {LoginRegx: function(e) {
            var t = e.find("#submit_button"), n = e.find("#account"), r = e.find("#password");
            t.click(function(t) {
                t.preventDefault(), n.val().trim() == "" || n.length < 0 ? alert("请输入用户名") : r.val().trim() == "" || r.length < 0 ? alert("请输入密码") : e.submit()
            })
        }, RegisterRegx: function(e) {
            var t = e.find("input[type=submit]"), n = e.find("#email"), r = e.find("#name"), i = e.find("#password"), s = /^([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/;
            t.click(function(t) {
                t.preventDefault(), n.val().trim() == "" || n.length < 0 || !s.test(n.val()) ? alert("请输入正确的邮箱地址") : r.val().trim() == "" || r.val().length < 4 ? alert("用户名不得少于4位") : i.val().trim() == "" || i.val().length < 4 ? alert("密码要在4-12位之间") : e.submit()
            })
        }, MyCart: {initialize: function() {
                var e = this;
                $(".add").click(function() {
                    e.addCount($(this).parent().children(".show_count"))
                }), $(".reduce").click(function() {
                    e.reduceCount($(this).parent().children(".show_count"))
                }), $(".delete").click(function() {
                    return e.deleteItem($(this).parents(".cart_list"))
                })
            }, addCount: function(e) {
                var t = Number(e.val()), n = Number(e.attr("data-restriction")), r = n > 0 ? n : 99;
                t < r && (e.val(++t), e.parents(".cart_update")[0].submit())
            }, reduceCount: function(e) {
                var t = Number(e.val());
                t > 1 && (e.val(--t), e.parents(".cart_update")[0].submit())
            }, deleteItem: function(e) {
                var t = e.find(".main_title"), n = e.find(".show_count"), r = this;
                return confirm('确认将"' + t.html().trim() + '"从购物车中移除?') ? !0 : !1
            }}, PwdReset: function(e) {
            var t = e.find("#email"), n = e.find("#checkcode"), r = e.find("#checkcode_img"), i = e.find("input[type=submit]"), s = {}, o = /^([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/;
            r.click(function() {
                $.ajax({type: "GET", url: "", data: s, dataType: "json", success: function(e) {
                        r.attr("src", e.code + "?" + Math.random())
                    }, error: function() {
                        alert("验证码获取失败,请稍后再试")
                    }})
            }), i.click(function(r) {
                r.preventDefault(), t.val().trim() == "" || t.length < 0 || !o.test(t.val()) ? alert("请输入正确的邮箱地址") : n.val().trim() == "" || n.length < 0 ? alert("请输入验证码") : e.submit()
            })
        }, FeedBack: function(e) {
            var t = e.find("#problemType"), n = e.find("#content"), r = e.find("#contact");
            $(".check_problem").click(function() {
                var e = $(this).children(".reason");
                $(".problem_img").removeClass("checked"), $(this).children(".problem_img").addClass("checked"), t.val(e.html())
            }), $("#send").click(function() {
                n.val().trim() == "" || n.length < 0 ? alert("请填写反馈内容") : e.submit()
            })
        }, Address: function() {
            $(".address").click(function() {
                $(".flag").children("div").removeClass("checked"), $(this).find(".flag").children("div").addClass("checked")
            })
        }}
}(window.jQuery || window.Zepto);
$(document).ready(function() {
    $("img.lazy,img.lazy_load").unveil(0);
});
(function($) {
    var shoping_car = {num: 0, };
    shoping_car.get_userid = function(objName) {
        var arrStr = document.cookie.split("; ");
        for (var i = 0; i < arrStr.length; i++) {
            var temp = arrStr[i].split("=");
            if (temp[0] == objName) {
                return unescape(temp[1])
            }
        }
    };
    shoping_car.set_num = function(car_class, num_class) {
        if (shoping_car.num != 0) {
            if ($("." + car_class).find("." + num_class).length > 0) {
                $("." + car_class).find("." + num_class).text(shoping_car.num);
                $("." + car_class).find("." + num_class).show()
            } else {
                $("." + car_class).append('<div class="' + num_class + '">' + shoping_car.num + "</div>")
            }
        } else {
            if ($("." + car_class).find("." + num_class).length > 0) {
                $("." + car_class).find("." + num_class).hide()
            }
        }
    };
    shoping_car.init = function(car_class, num_class, cart_total_quantity) {
                    shoping_car.num = cart_total_quantity;
                    shoping_car.set_num(car_class, num_class)
    };
    shoping_car.add_shoping = function(car_class, num_class, params, category, callback) {
        var url =  $('#add_to_cart_url').val();
        $.getJSON(url, function(s) {
              callback(s);
              if(s.result==0){
                alert("只能购买 " + s.maxbuy + " 件!");;
              } else {
                shoping_car.num = parseInt(shoping_car.num) + 1;
                shoping_car.set_num(car_class, num_class)
              }
          });
    };
    window.shoping_car = shoping_car
})(window.jQuery || window.Zepto);

function toggle(e) {
    var next_dom = null;
    next_dom = $(e).next('ul');
    $('.mall_sel').not(next_dom).hide();
    next_dom.toggle();

    // $('.dropdown-module ul').removeClass('show');
    // $('.close_tab').hide();
}
var _hmt = _hmt || [];
var Jumei = {};
Jumei.init = function() {
    (function() {
        var baidu_tongji = document.createElement('script');
        baidu_tongji.type = 'text/javascript';
        baidu_tongji.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'hm.baidu.com/h.js?884477732c15fb2f2416fb892282394b';
        var s = document.getElementsByTagName('script')[0];
        //s.parentNode.insertBefore(baidu_tongji, s);
    })();
    _hmt.push(["_setAccount", "UA-10208510-2"]);
    _hmt.push(["_trackPageview"]);
    _hmt.push(["_addIgnoredRef", "m.jumei.com/i/MobileWap/app_ga"]);
    var reg = new RegExp("^wap_touch*"), referer = Jumei.getCookie("referer_site");
    if (referer && referer.match(reg)) {
        _hmt.push(["_setCustomVar", 1, "site_type", "touch", 2])
    } else {
        _hmt.push(["_setCustomVar", 1, "site_type", "wap", 2])
    }
};
Jumei.setCookie = function(name, value, expires, path, domain, secure) {
    var today = new Date();
    today.setTime(today.getTime());
    if (expires) {
        expires = expires * 1000 * 60 * 60 * 24
    }
    var expires_date = new Date(today.getTime() + (expires));
    document.cookie = name + "=" + escape(value) + ((expires) ? ";expires=" + expires_date.toGMTString() : "") + ((path) ? ";path=" + path : "") + ((domain) ? ";domain=" + domain : "") + ((secure) ? ";secure" : "")
};
Jumei.getCookie = function(check_name) {
    var a_all_cookies = document.cookie.split(";");
    var a_temp_cookie = "";
    var cookie_name = "";
    var cookie_value = "";
    var b_cookie_found = false;
    for (var i = 0; i < a_all_cookies.length; i++) {
        a_temp_cookie = a_all_cookies[i].split("=");
        cookie_name = a_temp_cookie[0].replace(/^\s+|\s+$/g, "");
        if (cookie_name == check_name) {
            b_cookie_found = true;
            if (a_temp_cookie.length > 1) {
                cookie_value = unescape(a_temp_cookie[1].replace(/^\s+|\s+$/g, ""))
            }
            return cookie_value;
            break
        }
        a_temp_cookie = null;
        cookie_name = ""
    }
    if (!b_cookie_found) {
        return null
    }
};
Jumei.os = function() {
    if (this.getCookie("platform") === "wap") {
        return"wap"
    }
    var platform = navigator.userAgent.toLowerCase();
    if (/android/g.test(platform)) {
        return"android"
    } else {
        if (/(iphone|ipod|ipad)/g.test(platform)) {
            return"iphone"
        } else {
            return"qita"
        }
    }
}, Jumei.ja = function() {
    var ga = ["_trackEvent"], args = [];
    if (arguments.length > 0) {
        args = arguments;
        for (var i = 0; i < args.length; i++) {
            if (i < 3) {
                if (i == 0) {
                    var hrefs = location.href;
                    hrefs = hrefs.replace(/http:\/\/.*?\//, "");
                    var platform = this.os();
                    ga.push(hrefs + "_" + args[i] + "_" + platform)
                } else {
                    ga.push(args[i])
                }
            }
        }
        window._hmt.push(ga)
    }
};
Jumei.init();
Jumei.show_loading = function() {
    $('#ajax-loading').show();
}
Jumei.hide_loading = function() {
    $('#ajax-loading').hide();
}
/*
 author:hongxiaj
 updata:2014.12.8
 func_param.param is the post data
 func_param.method is post or get
 func_param.is_show decide if the status of ajax show to the custom;
 func_param.url is the post url
 func_param.callback is the function that will be execute when the ajax is end;
 */
Jumei.ajax = function(func_param) {
    var post_data = func_param.param ? func_param.param : '';
    var type = !!func_param.method == false ? 'get' : func_param.method;
    var is_json = !!func_param.is_json == false ? 'json' : func_param.is_json;
    if (func_param.is_show) {
        Jumei.show_loading();
    }
    $.ajax({
        "url": func_param.url,
        "success": function(data) {
            if (func_param.is_show) {
                Jumei.hide_loading();
            }
            if (typeof (func_param.callback) == 'function') {
                func_param.callback(data);
            }
        },
        "error": function() {
            if (func_param.is_show) {
                Jumei.hide_loading();
            }
            alert('网络故障，请再试一次')
        },
        "timeout": 60000,
        "type": type,
        "data": post_data,
        "dataType": is_json
    });
}
