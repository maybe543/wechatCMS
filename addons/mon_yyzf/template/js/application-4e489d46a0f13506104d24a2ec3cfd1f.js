!function (e, t) {
    "object" == typeof module && "object" == typeof module.exports ? module.exports = e.document ? t(e, !0) : function (e) {
        if (!e.document)throw new Error("jQuery requires a window with a document");
        return t(e)
    } : t(e)
}("undefined" != typeof window ? window : this, function (e, t) {
    function n(e) {
        var t = e.length, n = rt.type(e);
        return "function" === n || rt.isWindow(e) ? !1 : 1 === e.nodeType && t ? !0 : "array" === n || 0 === t || "number" == typeof t && t > 0 && t - 1 in e
    }

    function i(e, t, n) {
        if (rt.isFunction(t))return rt.grep(e, function (e, i) {
            return !!t.call(e, i, e) !== n
        });
        if (t.nodeType)return rt.grep(e, function (e) {
            return e === t !== n
        });
        if ("string" == typeof t) {
            if (pt.test(t))return rt.filter(t, e, n);
            t = rt.filter(t, e)
        }
        return rt.grep(e, function (e) {
            return rt.inArray(e, t) >= 0 !== n
        })
    }

    function r(e, t) {
        do e = e[t]; while (e && 1 !== e.nodeType);
        return e
    }

    function o(e) {
        var t = xt[e] = {};
        return rt.each(e.match(bt) || [], function (e, n) {
            t[n] = !0
        }), t
    }

    function a() {
        ht.addEventListener ? (ht.removeEventListener("DOMContentLoaded", s, !1), e.removeEventListener("load", s, !1)) : (ht.detachEvent("onreadystatechange", s), e.detachEvent("onload", s))
    }

    function s() {
        (ht.addEventListener || "load" === event.type || "complete" === ht.readyState) && (a(), rt.ready())
    }

    function l(e, t, n) {
        if (void 0 === n && 1 === e.nodeType) {
            var i = "data-" + t.replace(_t, "-$1").toLowerCase();
            if (n = e.getAttribute(i), "string" == typeof n) {
                try {
                    n = "true" === n ? !0 : "false" === n ? !1 : "null" === n ? null : +n + "" === n ? +n : St.test(n) ? rt.parseJSON(n) : n
                } catch (r) {
                }
                rt.data(e, t, n)
            } else n = void 0
        }
        return n
    }

    function u(e) {
        var t;
        for (t in e)if (("data" !== t || !rt.isEmptyObject(e[t])) && "toJSON" !== t)return !1;
        return !0
    }

    function c(e, t, n, i) {
        if (rt.acceptData(e)) {
            var r, o, a = rt.expando, s = e.nodeType, l = s ? rt.cache : e, u = s ? e[a] : e[a] && a;
            if (u && l[u] && (i || l[u].data) || void 0 !== n || "string" != typeof t)return u || (u = s ? e[a] = X.pop() || rt.guid++ : a), l[u] || (l[u] = s ? {} : {toJSON: rt.noop}), ("object" == typeof t || "function" == typeof t) && (i ? l[u] = rt.extend(l[u], t) : l[u].data = rt.extend(l[u].data, t)), o = l[u], i || (o.data || (o.data = {}), o = o.data), void 0 !== n && (o[rt.camelCase(t)] = n), "string" == typeof t ? (r = o[t], null == r && (r = o[rt.camelCase(t)])) : r = o, r
        }
    }

    function d(e, t, n) {
        if (rt.acceptData(e)) {
            var i, r, o = e.nodeType, a = o ? rt.cache : e, s = o ? e[rt.expando] : rt.expando;
            if (a[s]) {
                if (t && (i = n ? a[s] : a[s].data)) {
                    rt.isArray(t) ? t = t.concat(rt.map(t, rt.camelCase)) : t in i ? t = [t] : (t = rt.camelCase(t), t = t in i ? [t] : t.split(" ")), r = t.length;
                    for (; r--;)delete i[t[r]];
                    if (n ? !u(i) : !rt.isEmptyObject(i))return
                }
                (n || (delete a[s].data, u(a[s]))) && (o ? rt.cleanData([e], !0) : nt.deleteExpando || a != a.window ? delete a[s] : a[s] = null)
            }
        }
    }

    function p() {
        return !0
    }

    function f() {
        return !1
    }

    function h() {
        try {
            return ht.activeElement
        } catch (e) {
        }
    }

    function m(e) {
        var t = Mt.split("|"), n = e.createDocumentFragment();
        if (n.createElement)for (; t.length;)n.createElement(t.pop());
        return n
    }

    function g(e, t) {
        var n, i, r = 0, o = typeof e.getElementsByTagName !== Tt ? e.getElementsByTagName(t || "*") : typeof e.querySelectorAll !== Tt ? e.querySelectorAll(t || "*") : void 0;
        if (!o)for (o = [], n = e.childNodes || e; null != (i = n[r]); r++)!t || rt.nodeName(i, t) ? o.push(i) : rt.merge(o, g(i, t));
        return void 0 === t || t && rt.nodeName(e, t) ? rt.merge([e], o) : o
    }

    function v(e) {
        Ot.test(e.type) && (e.defaultChecked = e.checked)
    }

    function y(e, t) {
        return rt.nodeName(e, "table") && rt.nodeName(11 !== t.nodeType ? t : t.firstChild, "tr") ? e.getElementsByTagName("tbody")[0] || e.appendChild(e.ownerDocument.createElement("tbody")) : e
    }

    function b(e) {
        return e.type = (null !== rt.find.attr(e, "type")) + "/" + e.type, e
    }

    function x(e) {
        var t = Gt.exec(e.type);
        return t ? e.type = t[1] : e.removeAttribute("type"), e
    }

    function w(e, t) {
        for (var n, i = 0; null != (n = e[i]); i++)rt._data(n, "globalEval", !t || rt._data(t[i], "globalEval"))
    }

    function E(e, t) {
        if (1 === t.nodeType && rt.hasData(e)) {
            var n, i, r, o = rt._data(e), a = rt._data(t, o), s = o.events;
            if (s) {
                delete a.handle, a.events = {};
                for (n in s)for (i = 0, r = s[n].length; r > i; i++)rt.event.add(t, n, s[n][i])
            }
            a.data && (a.data = rt.extend({}, a.data))
        }
    }

    function T(e, t) {
        var n, i, r;
        if (1 === t.nodeType) {
            if (n = t.nodeName.toLowerCase(), !nt.noCloneEvent && t[rt.expando]) {
                r = rt._data(t);
                for (i in r.events)rt.removeEvent(t, i, r.handle);
                t.removeAttribute(rt.expando)
            }
            "script" === n && t.text !== e.text ? (b(t).text = e.text, x(t)) : "object" === n ? (t.parentNode && (t.outerHTML = e.outerHTML), nt.html5Clone && e.innerHTML && !rt.trim(t.innerHTML) && (t.innerHTML = e.innerHTML)) : "input" === n && Ot.test(e.type) ? (t.defaultChecked = t.checked = e.checked, t.value !== e.value && (t.value = e.value)) : "option" === n ? t.defaultSelected = t.selected = e.defaultSelected : ("input" === n || "textarea" === n) && (t.defaultValue = e.defaultValue)
        }
    }

    function S(t, n) {
        var i, r = rt(n.createElement(t)).appendTo(n.body), o = e.getDefaultComputedStyle && (i = e.getDefaultComputedStyle(r[0])) ? i.display : rt.css(r[0], "display");
        return r.detach(), o
    }

    function _(e) {
        var t = ht, n = Zt[e];
        return n || (n = S(e, t), "none" !== n && n || (Kt = (Kt || rt("<iframe frameborder='0' width='0' height='0'/>")).appendTo(t.documentElement), t = (Kt[0].contentWindow || Kt[0].contentDocument).document, t.write(), t.close(), n = S(e, t), Kt.detach()), Zt[e] = n), n
    }

    function R(e, t) {
        return {
            get: function () {
                var n = e();
                if (null != n)return n ? void delete this.get : (this.get = t).apply(this, arguments)
            }
        }
    }

    function C(e, t) {
        if (t in e)return t;
        for (var n = t.charAt(0).toUpperCase() + t.slice(1), i = t, r = fn.length; r--;)if (t = fn[r] + n, t in e)return t;
        return i
    }

    function D(e, t) {
        for (var n, i, r, o = [], a = 0, s = e.length; s > a; a++)i = e[a], i.style && (o[a] = rt._data(i, "olddisplay"), n = i.style.display, t ? (o[a] || "none" !== n || (i.style.display = ""), "" === i.style.display && Dt(i) && (o[a] = rt._data(i, "olddisplay", _(i.nodeName)))) : (r = Dt(i), (n && "none" !== n || !r) && rt._data(i, "olddisplay", r ? n : rt.css(i, "display"))));
        for (a = 0; s > a; a++)i = e[a], i.style && (t && "none" !== i.style.display && "" !== i.style.display || (i.style.display = t ? o[a] || "" : "none"));
        return e
    }

    function N(e, t, n) {
        var i = un.exec(t);
        return i ? Math.max(0, i[1] - (n || 0)) + (i[2] || "px") : t
    }

    function O(e, t, n, i, r) {
        for (var o = n === (i ? "border" : "content") ? 4 : "width" === t ? 1 : 0, a = 0; 4 > o; o += 2)"margin" === n && (a += rt.css(e, n + Ct[o], !0, r)), i ? ("content" === n && (a -= rt.css(e, "padding" + Ct[o], !0, r)), "margin" !== n && (a -= rt.css(e, "border" + Ct[o] + "Width", !0, r))) : (a += rt.css(e, "padding" + Ct[o], !0, r), "padding" !== n && (a += rt.css(e, "border" + Ct[o] + "Width", !0, r)));
        return a
    }

    function A(e, t, n) {
        var i = !0, r = "width" === t ? e.offsetWidth : e.offsetHeight, o = en(e), a = nt.boxSizing && "border-box" === rt.css(e, "boxSizing", !1, o);
        if (0 >= r || null == r) {
            if (r = tn(e, t, o), (0 > r || null == r) && (r = e.style[t]), rn.test(r))return r;
            i = a && (nt.boxSizingReliable() || r === e.style[t]), r = parseFloat(r) || 0
        }
        return r + O(e, t, n || (a ? "border" : "content"), i, o) + "px"
    }

    function k(e, t, n, i, r) {
        return new k.prototype.init(e, t, n, i, r)
    }

    function I() {
        return setTimeout(function () {
            hn = void 0
        }), hn = rt.now()
    }

    function F(e, t) {
        var n, i = {height: e}, r = 0;
        for (t = t ? 1 : 0; 4 > r; r += 2 - t)n = Ct[r], i["margin" + n] = i["padding" + n] = e;
        return t && (i.opacity = i.width = e), i
    }

    function L(e, t, n) {
        for (var i, r = (xn[t] || []).concat(xn["*"]), o = 0, a = r.length; a > o; o++)if (i = r[o].call(n, t, e))return i
    }

    function M(e, t, n) {
        var i, r, o, a, s, l, u, c, d = this, p = {}, f = e.style, h = e.nodeType && Dt(e), m = rt._data(e, "fxshow");
        n.queue || (s = rt._queueHooks(e, "fx"), null == s.unqueued && (s.unqueued = 0, l = s.empty.fire, s.empty.fire = function () {
            s.unqueued || l()
        }), s.unqueued++, d.always(function () {
            d.always(function () {
                s.unqueued--, rt.queue(e, "fx").length || s.empty.fire()
            })
        })), 1 === e.nodeType && ("height"in t || "width"in t) && (n.overflow = [f.overflow, f.overflowX, f.overflowY], u = rt.css(e, "display"), c = "none" === u ? rt._data(e, "olddisplay") || _(e.nodeName) : u, "inline" === c && "none" === rt.css(e, "float") && (nt.inlineBlockNeedsLayout && "inline" !== _(e.nodeName) ? f.zoom = 1 : f.display = "inline-block")), n.overflow && (f.overflow = "hidden", nt.shrinkWrapBlocks() || d.always(function () {
            f.overflow = n.overflow[0], f.overflowX = n.overflow[1], f.overflowY = n.overflow[2]
        }));
        for (i in t)if (r = t[i], gn.exec(r)) {
            if (delete t[i], o = o || "toggle" === r, r === (h ? "hide" : "show")) {
                if ("show" !== r || !m || void 0 === m[i])continue;
                h = !0
            }
            p[i] = m && m[i] || rt.style(e, i)
        } else u = void 0;
        if (rt.isEmptyObject(p))"inline" === ("none" === u ? _(e.nodeName) : u) && (f.display = u); else {
            m ? "hidden"in m && (h = m.hidden) : m = rt._data(e, "fxshow", {}), o && (m.hidden = !h), h ? rt(e).show() : d.done(function () {
                rt(e).hide()
            }), d.done(function () {
                var t;
                rt._removeData(e, "fxshow");
                for (t in p)rt.style(e, t, p[t])
            });
            for (i in p)a = L(h ? m[i] : 0, i, d), i in m || (m[i] = a.start, h && (a.end = a.start, a.start = "width" === i || "height" === i ? 1 : 0))
        }
    }

    function H(e, t) {
        var n, i, r, o, a;
        for (n in e)if (i = rt.camelCase(n), r = t[i], o = e[n], rt.isArray(o) && (r = o[1], o = e[n] = o[0]), n !== i && (e[i] = o, delete e[n]), a = rt.cssHooks[i], a && "expand"in a) {
            o = a.expand(o), delete e[i];
            for (n in o)n in e || (e[n] = o[n], t[n] = r)
        } else t[i] = r
    }

    function P(e, t, n) {
        var i, r, o = 0, a = bn.length, s = rt.Deferred().always(function () {
            delete l.elem
        }), l = function () {
            if (r)return !1;
            for (var t = hn || I(), n = Math.max(0, u.startTime + u.duration - t), i = n / u.duration || 0, o = 1 - i, a = 0, l = u.tweens.length; l > a; a++)u.tweens[a].run(o);
            return s.notifyWith(e, [u, o, n]), 1 > o && l ? n : (s.resolveWith(e, [u]), !1)
        }, u = s.promise({
            elem: e,
            props: rt.extend({}, t),
            opts: rt.extend(!0, {specialEasing: {}}, n),
            originalProperties: t,
            originalOptions: n,
            startTime: hn || I(),
            duration: n.duration,
            tweens: [],
            createTween: function (t, n) {
                var i = rt.Tween(e, u.opts, t, n, u.opts.specialEasing[t] || u.opts.easing);
                return u.tweens.push(i), i
            },
            stop: function (t) {
                var n = 0, i = t ? u.tweens.length : 0;
                if (r)return this;
                for (r = !0; i > n; n++)u.tweens[n].run(1);
                return t ? s.resolveWith(e, [u, t]) : s.rejectWith(e, [u, t]), this
            }
        }), c = u.props;
        for (H(c, u.opts.specialEasing); a > o; o++)if (i = bn[o].call(u, e, c, u.opts))return i;
        return rt.map(c, L, u), rt.isFunction(u.opts.start) && u.opts.start.call(e, u), rt.fx.timer(rt.extend(l, {
            elem: e,
            anim: u,
            queue: u.opts.queue
        })), u.progress(u.opts.progress).done(u.opts.done, u.opts.complete).fail(u.opts.fail).always(u.opts.always)
    }

    function j(e) {
        return function (t, n) {
            "string" != typeof t && (n = t, t = "*");
            var i, r = 0, o = t.toLowerCase().match(bt) || [];
            if (rt.isFunction(n))for (; i = o[r++];)"+" === i.charAt(0) ? (i = i.slice(1) || "*", (e[i] = e[i] || []).unshift(n)) : (e[i] = e[i] || []).push(n)
        }
    }

    function B(e, t, n, i) {
        function r(s) {
            var l;
            return o[s] = !0, rt.each(e[s] || [], function (e, s) {
                var u = s(t, n, i);
                return "string" != typeof u || a || o[u] ? a ? !(l = u) : void 0 : (t.dataTypes.unshift(u), r(u), !1)
            }), l
        }

        var o = {}, a = e === Wn;
        return r(t.dataTypes[0]) || !o["*"] && r("*")
    }

    function $(e, t) {
        var n, i, r = rt.ajaxSettings.flatOptions || {};
        for (i in t)void 0 !== t[i] && ((r[i] ? e : n || (n = {}))[i] = t[i]);
        return n && rt.extend(!0, e, n), e
    }

    function z(e, t, n) {
        for (var i, r, o, a, s = e.contents, l = e.dataTypes; "*" === l[0];)l.shift(), void 0 === r && (r = e.mimeType || t.getResponseHeader("Content-Type"));
        if (r)for (a in s)if (s[a] && s[a].test(r)) {
            l.unshift(a);
            break
        }
        if (l[0]in n)o = l[0]; else {
            for (a in n) {
                if (!l[0] || e.converters[a + " " + l[0]]) {
                    o = a;
                    break
                }
                i || (i = a)
            }
            o = o || i
        }
        return o ? (o !== l[0] && l.unshift(o), n[o]) : void 0
    }

    function q(e, t, n, i) {
        var r, o, a, s, l, u = {}, c = e.dataTypes.slice();
        if (c[1])for (a in e.converters)u[a.toLowerCase()] = e.converters[a];
        for (o = c.shift(); o;)if (e.responseFields[o] && (n[e.responseFields[o]] = t), !l && i && e.dataFilter && (t = e.dataFilter(t, e.dataType)), l = o, o = c.shift())if ("*" === o)o = l; else if ("*" !== l && l !== o) {
            if (a = u[l + " " + o] || u["* " + o], !a)for (r in u)if (s = r.split(" "), s[1] === o && (a = u[l + " " + s[0]] || u["* " + s[0]])) {
                a === !0 ? a = u[r] : u[r] !== !0 && (o = s[0], c.unshift(s[1]));
                break
            }
            if (a !== !0)if (a && e["throws"])t = a(t); else try {
                t = a(t)
            } catch (d) {
                return {state: "parsererror", error: a ? d : "No conversion from " + l + " to " + o}
            }
        }
        return {state: "success", data: t}
    }

    function W(e, t, n, i) {
        var r;
        if (rt.isArray(t))rt.each(t, function (t, r) {
            n || Xn.test(e) ? i(e, r) : W(e + "[" + ("object" == typeof r ? t : "") + "]", r, n, i)
        }); else if (n || "object" !== rt.type(t))i(e, t); else for (r in t)W(e + "[" + r + "]", t[r], n, i)
    }

    function U() {
        try {
            return new e.XMLHttpRequest
        } catch (t) {
        }
    }

    function V() {
        try {
            return new e.ActiveXObject("Microsoft.XMLHTTP")
        } catch (t) {
        }
    }

    function G(e) {
        return rt.isWindow(e) ? e : 9 === e.nodeType ? e.defaultView || e.parentWindow : !1
    }

    var X = [], J = X.slice, Y = X.concat, Q = X.push, K = X.indexOf, Z = {}, et = Z.toString, tt = Z.hasOwnProperty, nt = {}, it = "1.11.1", rt = function (e, t) {
        return new rt.fn.init(e, t)
    }, ot = /^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g, at = /^-ms-/, st = /-([\da-z])/gi, lt = function (e, t) {
        return t.toUpperCase()
    };
    rt.fn = rt.prototype = {
        jquery: it, constructor: rt, selector: "", length: 0, toArray: function () {
            return J.call(this)
        }, get: function (e) {
            return null != e ? 0 > e ? this[e + this.length] : this[e] : J.call(this)
        }, pushStack: function (e) {
            var t = rt.merge(this.constructor(), e);
            return t.prevObject = this, t.context = this.context, t
        }, each: function (e, t) {
            return rt.each(this, e, t)
        }, map: function (e) {
            return this.pushStack(rt.map(this, function (t, n) {
                return e.call(t, n, t)
            }))
        }, slice: function () {
            return this.pushStack(J.apply(this, arguments))
        }, first: function () {
            return this.eq(0)
        }, last: function () {
            return this.eq(-1)
        }, eq: function (e) {
            var t = this.length, n = +e + (0 > e ? t : 0);
            return this.pushStack(n >= 0 && t > n ? [this[n]] : [])
        }, end: function () {
            return this.prevObject || this.constructor(null)
        }, push: Q, sort: X.sort, splice: X.splice
    }, rt.extend = rt.fn.extend = function () {
        var e, t, n, i, r, o, a = arguments[0] || {}, s = 1, l = arguments.length, u = !1;
        for ("boolean" == typeof a && (u = a, a = arguments[s] || {}, s++), "object" == typeof a || rt.isFunction(a) || (a = {}), s === l && (a = this, s--); l > s; s++)if (null != (r = arguments[s]))for (i in r)e = a[i], n = r[i], a !== n && (u && n && (rt.isPlainObject(n) || (t = rt.isArray(n))) ? (t ? (t = !1, o = e && rt.isArray(e) ? e : []) : o = e && rt.isPlainObject(e) ? e : {}, a[i] = rt.extend(u, o, n)) : void 0 !== n && (a[i] = n));
        return a
    }, rt.extend({
        expando: "jQuery" + (it + Math.random()).replace(/\D/g, ""), isReady: !0, error: function (e) {
            throw new Error(e)
        }, noop: function () {
        }, isFunction: function (e) {
            return "function" === rt.type(e)
        }, isArray: Array.isArray || function (e) {
            return "array" === rt.type(e)
        }, isWindow: function (e) {
            return null != e && e == e.window
        }, isNumeric: function (e) {
            return !rt.isArray(e) && e - parseFloat(e) >= 0
        }, isEmptyObject: function (e) {
            var t;
            for (t in e)return !1;
            return !0
        }, isPlainObject: function (e) {
            var t;
            if (!e || "object" !== rt.type(e) || e.nodeType || rt.isWindow(e))return !1;
            try {
                if (e.constructor && !tt.call(e, "constructor") && !tt.call(e.constructor.prototype, "isPrototypeOf"))return !1
            } catch (n) {
                return !1
            }
            if (nt.ownLast)for (t in e)return tt.call(e, t);
            for (t in e);
            return void 0 === t || tt.call(e, t)
        }, type: function (e) {
            return null == e ? e + "" : "object" == typeof e || "function" == typeof e ? Z[et.call(e)] || "object" : typeof e
        }, globalEval: function (t) {
            t && rt.trim(t) && (e.execScript || function (t) {
                e.eval.call(e, t)
            })(t)
        }, camelCase: function (e) {
            return e.replace(at, "ms-").replace(st, lt)
        }, nodeName: function (e, t) {
            return e.nodeName && e.nodeName.toLowerCase() === t.toLowerCase()
        }, each: function (e, t, i) {
            var r, o = 0, a = e.length, s = n(e);
            if (i) {
                if (s)for (; a > o && (r = t.apply(e[o], i), r !== !1); o++); else for (o in e)if (r = t.apply(e[o], i), r === !1)break
            } else if (s)for (; a > o && (r = t.call(e[o], o, e[o]), r !== !1); o++); else for (o in e)if (r = t.call(e[o], o, e[o]), r === !1)break;
            return e
        }, trim: function (e) {
            return null == e ? "" : (e + "").replace(ot, "")
        }, makeArray: function (e, t) {
            var i = t || [];
            return null != e && (n(Object(e)) ? rt.merge(i, "string" == typeof e ? [e] : e) : Q.call(i, e)), i
        }, inArray: function (e, t, n) {
            var i;
            if (t) {
                if (K)return K.call(t, e, n);
                for (i = t.length, n = n ? 0 > n ? Math.max(0, i + n) : n : 0; i > n; n++)if (n in t && t[n] === e)return n
            }
            return -1
        }, merge: function (e, t) {
            for (var n = +t.length, i = 0, r = e.length; n > i;)e[r++] = t[i++];
            if (n !== n)for (; void 0 !== t[i];)e[r++] = t[i++];
            return e.length = r, e
        }, grep: function (e, t, n) {
            for (var i, r = [], o = 0, a = e.length, s = !n; a > o; o++)i = !t(e[o], o), i !== s && r.push(e[o]);
            return r
        }, map: function (e, t, i) {
            var r, o = 0, a = e.length, s = n(e), l = [];
            if (s)for (; a > o; o++)r = t(e[o], o, i), null != r && l.push(r); else for (o in e)r = t(e[o], o, i), null != r && l.push(r);
            return Y.apply([], l)
        }, guid: 1, proxy: function (e, t) {
            var n, i, r;
            return "string" == typeof t && (r = e[t], t = e, e = r), rt.isFunction(e) ? (n = J.call(arguments, 2), i = function () {
                return e.apply(t || this, n.concat(J.call(arguments)))
            }, i.guid = e.guid = e.guid || rt.guid++, i) : void 0
        }, now: function () {
            return +new Date
        }, support: nt
    }), rt.each("Boolean Number String Function Array Date RegExp Object Error".split(" "), function (e, t) {
        Z["[object " + t + "]"] = t.toLowerCase()
    });
    var ut = function (e) {
        function t(e, t, n, i) {
            var r, o, a, s, l, u, d, f, h, m;
            if ((t ? t.ownerDocument || t : B) !== k && A(t), t = t || k, n = n || [], !e || "string" != typeof e)return n;
            if (1 !== (s = t.nodeType) && 9 !== s)return [];
            if (F && !i) {
                if (r = yt.exec(e))if (a = r[1]) {
                    if (9 === s) {
                        if (o = t.getElementById(a), !o || !o.parentNode)return n;
                        if (o.id === a)return n.push(o), n
                    } else if (t.ownerDocument && (o = t.ownerDocument.getElementById(a)) && P(t, o) && o.id === a)return n.push(o), n
                } else {
                    if (r[2])return Z.apply(n, t.getElementsByTagName(e)), n;
                    if ((a = r[3]) && w.getElementsByClassName && t.getElementsByClassName)return Z.apply(n, t.getElementsByClassName(a)), n
                }
                if (w.qsa && (!L || !L.test(e))) {
                    if (f = d = j, h = t, m = 9 === s && e, 1 === s && "object" !== t.nodeName.toLowerCase()) {
                        for (u = _(e), (d = t.getAttribute("id")) ? f = d.replace(xt, "\\$&") : t.setAttribute("id", f), f = "[id='" + f + "'] ", l = u.length; l--;)u[l] = f + p(u[l]);
                        h = bt.test(e) && c(t.parentNode) || t, m = u.join(",")
                    }
                    if (m)try {
                        return Z.apply(n, h.querySelectorAll(m)), n
                    } catch (g) {
                    } finally {
                        d || t.removeAttribute("id")
                    }
                }
            }
            return C(e.replace(lt, "$1"), t, n, i)
        }

        function n() {
            function e(n, i) {
                return t.push(n + " ") > E.cacheLength && delete e[t.shift()], e[n + " "] = i
            }

            var t = [];
            return e
        }

        function i(e) {
            return e[j] = !0, e
        }

        function r(e) {
            var t = k.createElement("div");
            try {
                return !!e(t)
            } catch (n) {
                return !1
            } finally {
                t.parentNode && t.parentNode.removeChild(t), t = null
            }
        }

        function o(e, t) {
            for (var n = e.split("|"), i = e.length; i--;)E.attrHandle[n[i]] = t
        }

        function a(e, t) {
            var n = t && e, i = n && 1 === e.nodeType && 1 === t.nodeType && (~t.sourceIndex || X) - (~e.sourceIndex || X);
            if (i)return i;
            if (n)for (; n = n.nextSibling;)if (n === t)return -1;
            return e ? 1 : -1
        }

        function s(e) {
            return function (t) {
                var n = t.nodeName.toLowerCase();
                return "input" === n && t.type === e
            }
        }

        function l(e) {
            return function (t) {
                var n = t.nodeName.toLowerCase();
                return ("input" === n || "button" === n) && t.type === e
            }
        }

        function u(e) {
            return i(function (t) {
                return t = +t, i(function (n, i) {
                    for (var r, o = e([], n.length, t), a = o.length; a--;)n[r = o[a]] && (n[r] = !(i[r] = n[r]))
                })
            })
        }

        function c(e) {
            return e && typeof e.getElementsByTagName !== G && e
        }

        function d() {
        }

        function p(e) {
            for (var t = 0, n = e.length, i = ""; n > t; t++)i += e[t].value;
            return i
        }

        function f(e, t, n) {
            var i = t.dir, r = n && "parentNode" === i, o = z++;
            return t.first ? function (t, n, o) {
                for (; t = t[i];)if (1 === t.nodeType || r)return e(t, n, o)
            } : function (t, n, a) {
                var s, l, u = [$, o];
                if (a) {
                    for (; t = t[i];)if ((1 === t.nodeType || r) && e(t, n, a))return !0
                } else for (; t = t[i];)if (1 === t.nodeType || r) {
                    if (l = t[j] || (t[j] = {}), (s = l[i]) && s[0] === $ && s[1] === o)return u[2] = s[2];
                    if (l[i] = u, u[2] = e(t, n, a))return !0
                }
            }
        }

        function h(e) {
            return e.length > 1 ? function (t, n, i) {
                for (var r = e.length; r--;)if (!e[r](t, n, i))return !1;
                return !0
            } : e[0]
        }

        function m(e, n, i) {
            for (var r = 0, o = n.length; o > r; r++)t(e, n[r], i);
            return i
        }

        function g(e, t, n, i, r) {
            for (var o, a = [], s = 0, l = e.length, u = null != t; l > s; s++)(o = e[s]) && (!n || n(o, i, r)) && (a.push(o), u && t.push(s));
            return a
        }

        function v(e, t, n, r, o, a) {
            return r && !r[j] && (r = v(r)), o && !o[j] && (o = v(o, a)), i(function (i, a, s, l) {
                var u, c, d, p = [], f = [], h = a.length, v = i || m(t || "*", s.nodeType ? [s] : s, []), y = !e || !i && t ? v : g(v, p, e, s, l), b = n ? o || (i ? e : h || r) ? [] : a : y;
                if (n && n(y, b, s, l), r)for (u = g(b, f), r(u, [], s, l), c = u.length; c--;)(d = u[c]) && (b[f[c]] = !(y[f[c]] = d));
                if (i) {
                    if (o || e) {
                        if (o) {
                            for (u = [], c = b.length; c--;)(d = b[c]) && u.push(y[c] = d);
                            o(null, b = [], u, l)
                        }
                        for (c = b.length; c--;)(d = b[c]) && (u = o ? tt.call(i, d) : p[c]) > -1 && (i[u] = !(a[u] = d))
                    }
                } else b = g(b === a ? b.splice(h, b.length) : b), o ? o(null, a, b, l) : Z.apply(a, b)
            })
        }

        function y(e) {
            for (var t, n, i, r = e.length, o = E.relative[e[0].type], a = o || E.relative[" "], s = o ? 1 : 0, l = f(function (e) {
                return e === t
            }, a, !0), u = f(function (e) {
                return tt.call(t, e) > -1
            }, a, !0), c = [function (e, n, i) {
                return !o && (i || n !== D) || ((t = n).nodeType ? l(e, n, i) : u(e, n, i))
            }]; r > s; s++)if (n = E.relative[e[s].type])c = [f(h(c), n)]; else {
                if (n = E.filter[e[s].type].apply(null, e[s].matches), n[j]) {
                    for (i = ++s; r > i && !E.relative[e[i].type]; i++);
                    return v(s > 1 && h(c), s > 1 && p(e.slice(0, s - 1).concat({value: " " === e[s - 2].type ? "*" : ""})).replace(lt, "$1"), n, i > s && y(e.slice(s, i)), r > i && y(e = e.slice(i)), r > i && p(e))
                }
                c.push(n)
            }
            return h(c)
        }

        function b(e, n) {
            var r = n.length > 0, o = e.length > 0, a = function (i, a, s, l, u) {
                var c, d, p, f = 0, h = "0", m = i && [], v = [], y = D, b = i || o && E.find.TAG("*", u), x = $ += null == y ? 1 : Math.random() || .1, w = b.length;
                for (u && (D = a !== k && a); h !== w && null != (c = b[h]); h++) {
                    if (o && c) {
                        for (d = 0; p = e[d++];)if (p(c, a, s)) {
                            l.push(c);
                            break
                        }
                        u && ($ = x)
                    }
                    r && ((c = !p && c) && f--, i && m.push(c))
                }
                if (f += h, r && h !== f) {
                    for (d = 0; p = n[d++];)p(m, v, a, s);
                    if (i) {
                        if (f > 0)for (; h--;)m[h] || v[h] || (v[h] = Q.call(l));
                        v = g(v)
                    }
                    Z.apply(l, v), u && !i && v.length > 0 && f + n.length > 1 && t.uniqueSort(l)
                }
                return u && ($ = x, D = y), m
            };
            return r ? i(a) : a
        }

        var x, w, E, T, S, _, R, C, D, N, O, A, k, I, F, L, M, H, P, j = "sizzle" + -new Date, B = e.document, $ = 0, z = 0, q = n(), W = n(), U = n(), V = function (e, t) {
            return e === t && (O = !0), 0
        }, G = "undefined", X = 1 << 31, J = {}.hasOwnProperty, Y = [], Q = Y.pop, K = Y.push, Z = Y.push, et = Y.slice, tt = Y.indexOf || function (e) {
                for (var t = 0, n = this.length; n > t; t++)if (this[t] === e)return t;
                return -1
            }, nt = "checked|selected|async|autofocus|autoplay|controls|defer|disabled|hidden|ismap|loop|multiple|open|readonly|required|scoped", it = "[\\x20\\t\\r\\n\\f]", rt = "(?:\\\\.|[\\w-]|[^\\x00-\\xa0])+", ot = rt.replace("w", "w#"), at = "\\[" + it + "*(" + rt + ")(?:" + it + "*([*^$|!~]?=)" + it + "*(?:'((?:\\\\.|[^\\\\'])*)'|\"((?:\\\\.|[^\\\\\"])*)\"|(" + ot + "))|)" + it + "*\\]", st = ":(" + rt + ")(?:\\((('((?:\\\\.|[^\\\\'])*)'|\"((?:\\\\.|[^\\\\\"])*)\")|((?:\\\\.|[^\\\\()[\\]]|" + at + ")*)|.*)\\)|)", lt = new RegExp("^" + it + "+|((?:^|[^\\\\])(?:\\\\.)*)" + it + "+$", "g"), ut = new RegExp("^" + it + "*," + it + "*"), ct = new RegExp("^" + it + "*([>+~]|" + it + ")" + it + "*"), dt = new RegExp("=" + it + "*([^\\]'\"]*?)" + it + "*\\]", "g"), pt = new RegExp(st), ft = new RegExp("^" + ot + "$"), ht = {
            ID: new RegExp("^#(" + rt + ")"),
            CLASS: new RegExp("^\\.(" + rt + ")"),
            TAG: new RegExp("^(" + rt.replace("w", "w*") + ")"),
            ATTR: new RegExp("^" + at),
            PSEUDO: new RegExp("^" + st),
            CHILD: new RegExp("^:(only|first|last|nth|nth-last)-(child|of-type)(?:\\(" + it + "*(even|odd|(([+-]|)(\\d*)n|)" + it + "*(?:([+-]|)" + it + "*(\\d+)|))" + it + "*\\)|)", "i"),
            bool: new RegExp("^(?:" + nt + ")$", "i"),
            needsContext: new RegExp("^" + it + "*[>+~]|:(even|odd|eq|gt|lt|nth|first|last)(?:\\(" + it + "*((?:-\\d)?\\d*)" + it + "*\\)|)(?=[^-]|$)", "i")
        }, mt = /^(?:input|select|textarea|button)$/i, gt = /^h\d$/i, vt = /^[^{]+\{\s*\[native \w/, yt = /^(?:#([\w-]+)|(\w+)|\.([\w-]+))$/, bt = /[+~]/, xt = /'|\\/g, wt = new RegExp("\\\\([\\da-f]{1,6}" + it + "?|(" + it + ")|.)", "ig"), Et = function (e, t, n) {
            var i = "0x" + t - 65536;
            return i !== i || n ? t : 0 > i ? String.fromCharCode(i + 65536) : String.fromCharCode(i >> 10 | 55296, 1023 & i | 56320)
        };
        try {
            Z.apply(Y = et.call(B.childNodes), B.childNodes), Y[B.childNodes.length].nodeType
        } catch (Tt) {
            Z = {
                apply: Y.length ? function (e, t) {
                    K.apply(e, et.call(t))
                } : function (e, t) {
                    for (var n = e.length, i = 0; e[n++] = t[i++];);
                    e.length = n - 1
                }
            }
        }
        w = t.support = {}, S = t.isXML = function (e) {
            var t = e && (e.ownerDocument || e).documentElement;
            return t ? "HTML" !== t.nodeName : !1
        }, A = t.setDocument = function (e) {
            var t, n = e ? e.ownerDocument || e : B, i = n.defaultView;
            return n !== k && 9 === n.nodeType && n.documentElement ? (k = n, I = n.documentElement, F = !S(n), i && i !== i.top && (i.addEventListener ? i.addEventListener("unload", function () {
                A()
            }, !1) : i.attachEvent && i.attachEvent("onunload", function () {
                A()
            })), w.attributes = r(function (e) {
                return e.className = "i", !e.getAttribute("className")
            }), w.getElementsByTagName = r(function (e) {
                return e.appendChild(n.createComment("")), !e.getElementsByTagName("*").length
            }), w.getElementsByClassName = vt.test(n.getElementsByClassName) && r(function (e) {
                return e.innerHTML = "<div class='a'></div><div class='a i'></div>", e.firstChild.className = "i", 2 === e.getElementsByClassName("i").length
            }), w.getById = r(function (e) {
                return I.appendChild(e).id = j, !n.getElementsByName || !n.getElementsByName(j).length
            }), w.getById ? (E.find.ID = function (e, t) {
                if (typeof t.getElementById !== G && F) {
                    var n = t.getElementById(e);
                    return n && n.parentNode ? [n] : []
                }
            }, E.filter.ID = function (e) {
                var t = e.replace(wt, Et);
                return function (e) {
                    return e.getAttribute("id") === t
                }
            }) : (delete E.find.ID, E.filter.ID = function (e) {
                var t = e.replace(wt, Et);
                return function (e) {
                    var n = typeof e.getAttributeNode !== G && e.getAttributeNode("id");
                    return n && n.value === t
                }
            }), E.find.TAG = w.getElementsByTagName ? function (e, t) {
                return typeof t.getElementsByTagName !== G ? t.getElementsByTagName(e) : void 0
            } : function (e, t) {
                var n, i = [], r = 0, o = t.getElementsByTagName(e);
                if ("*" === e) {
                    for (; n = o[r++];)1 === n.nodeType && i.push(n);
                    return i
                }
                return o
            }, E.find.CLASS = w.getElementsByClassName && function (e, t) {
                return typeof t.getElementsByClassName !== G && F ? t.getElementsByClassName(e) : void 0
            }, M = [], L = [], (w.qsa = vt.test(n.querySelectorAll)) && (r(function (e) {
                e.innerHTML = "<select msallowclip=''><option selected=''></option></select>", e.querySelectorAll("[msallowclip^='']").length && L.push("[*^$]=" + it + "*(?:''|\"\")"), e.querySelectorAll("[selected]").length || L.push("\\[" + it + "*(?:value|" + nt + ")"), e.querySelectorAll(":checked").length || L.push(":checked")
            }), r(function (e) {
                var t = n.createElement("input");
                t.setAttribute("type", "hidden"), e.appendChild(t).setAttribute("name", "D"), e.querySelectorAll("[name=d]").length && L.push("name" + it + "*[*^$|!~]?="), e.querySelectorAll(":enabled").length || L.push(":enabled", ":disabled"), e.querySelectorAll("*,:x"), L.push(",.*:")
            })), (w.matchesSelector = vt.test(H = I.matches || I.webkitMatchesSelector || I.mozMatchesSelector || I.oMatchesSelector || I.msMatchesSelector)) && r(function (e) {
                w.disconnectedMatch = H.call(e, "div"), H.call(e, "[s!='']:x"), M.push("!=", st)
            }), L = L.length && new RegExp(L.join("|")), M = M.length && new RegExp(M.join("|")), t = vt.test(I.compareDocumentPosition), P = t || vt.test(I.contains) ? function (e, t) {
                var n = 9 === e.nodeType ? e.documentElement : e, i = t && t.parentNode;
                return e === i || !(!i || 1 !== i.nodeType || !(n.contains ? n.contains(i) : e.compareDocumentPosition && 16 & e.compareDocumentPosition(i)))
            } : function (e, t) {
                if (t)for (; t = t.parentNode;)if (t === e)return !0;
                return !1
            }, V = t ? function (e, t) {
                if (e === t)return O = !0, 0;
                var i = !e.compareDocumentPosition - !t.compareDocumentPosition;
                return i ? i : (i = (e.ownerDocument || e) === (t.ownerDocument || t) ? e.compareDocumentPosition(t) : 1, 1 & i || !w.sortDetached && t.compareDocumentPosition(e) === i ? e === n || e.ownerDocument === B && P(B, e) ? -1 : t === n || t.ownerDocument === B && P(B, t) ? 1 : N ? tt.call(N, e) - tt.call(N, t) : 0 : 4 & i ? -1 : 1)
            } : function (e, t) {
                if (e === t)return O = !0, 0;
                var i, r = 0, o = e.parentNode, s = t.parentNode, l = [e], u = [t];
                if (!o || !s)return e === n ? -1 : t === n ? 1 : o ? -1 : s ? 1 : N ? tt.call(N, e) - tt.call(N, t) : 0;
                if (o === s)return a(e, t);
                for (i = e; i = i.parentNode;)l.unshift(i);
                for (i = t; i = i.parentNode;)u.unshift(i);
                for (; l[r] === u[r];)r++;
                return r ? a(l[r], u[r]) : l[r] === B ? -1 : u[r] === B ? 1 : 0
            }, n) : k
        }, t.matches = function (e, n) {
            return t(e, null, null, n)
        }, t.matchesSelector = function (e, n) {
            if ((e.ownerDocument || e) !== k && A(e), n = n.replace(dt, "='$1']"), !(!w.matchesSelector || !F || M && M.test(n) || L && L.test(n)))try {
                var i = H.call(e, n);
                if (i || w.disconnectedMatch || e.document && 11 !== e.document.nodeType)return i
            } catch (r) {
            }
            return t(n, k, null, [e]).length > 0
        }, t.contains = function (e, t) {
            return (e.ownerDocument || e) !== k && A(e), P(e, t)
        }, t.attr = function (e, t) {
            (e.ownerDocument || e) !== k && A(e);
            var n = E.attrHandle[t.toLowerCase()], i = n && J.call(E.attrHandle, t.toLowerCase()) ? n(e, t, !F) : void 0;
            return void 0 !== i ? i : w.attributes || !F ? e.getAttribute(t) : (i = e.getAttributeNode(t)) && i.specified ? i.value : null
        }, t.error = function (e) {
            throw new Error("Syntax error, unrecognized expression: " + e)
        }, t.uniqueSort = function (e) {
            var t, n = [], i = 0, r = 0;
            if (O = !w.detectDuplicates, N = !w.sortStable && e.slice(0), e.sort(V), O) {
                for (; t = e[r++];)t === e[r] && (i = n.push(r));
                for (; i--;)e.splice(n[i], 1)
            }
            return N = null, e
        }, T = t.getText = function (e) {
            var t, n = "", i = 0, r = e.nodeType;
            if (r) {
                if (1 === r || 9 === r || 11 === r) {
                    if ("string" == typeof e.textContent)return e.textContent;
                    for (e = e.firstChild; e; e = e.nextSibling)n += T(e)
                } else if (3 === r || 4 === r)return e.nodeValue
            } else for (; t = e[i++];)n += T(t);
            return n
        }, E = t.selectors = {
            cacheLength: 50,
            createPseudo: i,
            match: ht,
            attrHandle: {},
            find: {},
            relative: {
                ">": {dir: "parentNode", first: !0},
                " ": {dir: "parentNode"},
                "+": {dir: "previousSibling", first: !0},
                "~": {dir: "previousSibling"}
            },
            preFilter: {
                ATTR: function (e) {
                    return e[1] = e[1].replace(wt, Et), e[3] = (e[3] || e[4] || e[5] || "").replace(wt, Et), "~=" === e[2] && (e[3] = " " + e[3] + " "), e.slice(0, 4)
                }, CHILD: function (e) {
                    return e[1] = e[1].toLowerCase(), "nth" === e[1].slice(0, 3) ? (e[3] || t.error(e[0]), e[4] = +(e[4] ? e[5] + (e[6] || 1) : 2 * ("even" === e[3] || "odd" === e[3])), e[5] = +(e[7] + e[8] || "odd" === e[3])) : e[3] && t.error(e[0]), e
                }, PSEUDO: function (e) {
                    var t, n = !e[6] && e[2];
                    return ht.CHILD.test(e[0]) ? null : (e[3] ? e[2] = e[4] || e[5] || "" : n && pt.test(n) && (t = _(n, !0)) && (t = n.indexOf(")", n.length - t) - n.length) && (e[0] = e[0].slice(0, t), e[2] = n.slice(0, t)), e.slice(0, 3))
                }
            },
            filter: {
                TAG: function (e) {
                    var t = e.replace(wt, Et).toLowerCase();
                    return "*" === e ? function () {
                        return !0
                    } : function (e) {
                        return e.nodeName && e.nodeName.toLowerCase() === t
                    }
                }, CLASS: function (e) {
                    var t = q[e + " "];
                    return t || (t = new RegExp("(^|" + it + ")" + e + "(" + it + "|$)")) && q(e, function (e) {
                            return t.test("string" == typeof e.className && e.className || typeof e.getAttribute !== G && e.getAttribute("class") || "")
                        })
                }, ATTR: function (e, n, i) {
                    return function (r) {
                        var o = t.attr(r, e);
                        return null == o ? "!=" === n : n ? (o += "", "=" === n ? o === i : "!=" === n ? o !== i : "^=" === n ? i && 0 === o.indexOf(i) : "*=" === n ? i && o.indexOf(i) > -1 : "$=" === n ? i && o.slice(-i.length) === i : "~=" === n ? (" " + o + " ").indexOf(i) > -1 : "|=" === n ? o === i || o.slice(0, i.length + 1) === i + "-" : !1) : !0
                    }
                }, CHILD: function (e, t, n, i, r) {
                    var o = "nth" !== e.slice(0, 3), a = "last" !== e.slice(-4), s = "of-type" === t;
                    return 1 === i && 0 === r ? function (e) {
                        return !!e.parentNode
                    } : function (t, n, l) {
                        var u, c, d, p, f, h, m = o !== a ? "nextSibling" : "previousSibling", g = t.parentNode, v = s && t.nodeName.toLowerCase(), y = !l && !s;
                        if (g) {
                            if (o) {
                                for (; m;) {
                                    for (d = t; d = d[m];)if (s ? d.nodeName.toLowerCase() === v : 1 === d.nodeType)return !1;
                                    h = m = "only" === e && !h && "nextSibling"
                                }
                                return !0
                            }
                            if (h = [a ? g.firstChild : g.lastChild], a && y) {
                                for (c = g[j] || (g[j] = {}), u = c[e] || [], f = u[0] === $ && u[1], p = u[0] === $ && u[2], d = f && g.childNodes[f]; d = ++f && d && d[m] || (p = f = 0) || h.pop();)if (1 === d.nodeType && ++p && d === t) {
                                    c[e] = [$, f, p];
                                    break
                                }
                            } else if (y && (u = (t[j] || (t[j] = {}))[e]) && u[0] === $)p = u[1]; else for (; (d = ++f && d && d[m] || (p = f = 0) || h.pop()) && ((s ? d.nodeName.toLowerCase() !== v : 1 !== d.nodeType) || !++p || (y && ((d[j] || (d[j] = {}))[e] = [$, p]), d !== t)););
                            return p -= r, p === i || p % i === 0 && p / i >= 0
                        }
                    }
                }, PSEUDO: function (e, n) {
                    var r, o = E.pseudos[e] || E.setFilters[e.toLowerCase()] || t.error("unsupported pseudo: " + e);
                    return o[j] ? o(n) : o.length > 1 ? (r = [e, e, "", n], E.setFilters.hasOwnProperty(e.toLowerCase()) ? i(function (e, t) {
                        for (var i, r = o(e, n), a = r.length; a--;)i = tt.call(e, r[a]), e[i] = !(t[i] = r[a])
                    }) : function (e) {
                        return o(e, 0, r)
                    }) : o
                }
            },
            pseudos: {
                not: i(function (e) {
                    var t = [], n = [], r = R(e.replace(lt, "$1"));
                    return r[j] ? i(function (e, t, n, i) {
                        for (var o, a = r(e, null, i, []), s = e.length; s--;)(o = a[s]) && (e[s] = !(t[s] = o))
                    }) : function (e, i, o) {
                        return t[0] = e, r(t, null, o, n), !n.pop()
                    }
                }), has: i(function (e) {
                    return function (n) {
                        return t(e, n).length > 0
                    }
                }), contains: i(function (e) {
                    return function (t) {
                        return (t.textContent || t.innerText || T(t)).indexOf(e) > -1
                    }
                }), lang: i(function (e) {
                    return ft.test(e || "") || t.error("unsupported lang: " + e), e = e.replace(wt, Et).toLowerCase(), function (t) {
                        var n;
                        do if (n = F ? t.lang : t.getAttribute("xml:lang") || t.getAttribute("lang"))return n = n.toLowerCase(), n === e || 0 === n.indexOf(e + "-"); while ((t = t.parentNode) && 1 === t.nodeType);
                        return !1
                    }
                }), target: function (t) {
                    var n = e.location && e.location.hash;
                    return n && n.slice(1) === t.id
                }, root: function (e) {
                    return e === I
                }, focus: function (e) {
                    return e === k.activeElement && (!k.hasFocus || k.hasFocus()) && !!(e.type || e.href || ~e.tabIndex)
                }, enabled: function (e) {
                    return e.disabled === !1
                }, disabled: function (e) {
                    return e.disabled === !0
                }, checked: function (e) {
                    var t = e.nodeName.toLowerCase();
                    return "input" === t && !!e.checked || "option" === t && !!e.selected
                }, selected: function (e) {
                    return e.parentNode && e.parentNode.selectedIndex, e.selected === !0
                }, empty: function (e) {
                    for (e = e.firstChild; e; e = e.nextSibling)if (e.nodeType < 6)return !1;
                    return !0
                }, parent: function (e) {
                    return !E.pseudos.empty(e)
                }, header: function (e) {
                    return gt.test(e.nodeName)
                }, input: function (e) {
                    return mt.test(e.nodeName)
                }, button: function (e) {
                    var t = e.nodeName.toLowerCase();
                    return "input" === t && "button" === e.type || "button" === t
                }, text: function (e) {
                    var t;
                    return "input" === e.nodeName.toLowerCase() && "text" === e.type && (null == (t = e.getAttribute("type")) || "text" === t.toLowerCase())
                }, first: u(function () {
                    return [0]
                }), last: u(function (e, t) {
                    return [t - 1]
                }), eq: u(function (e, t, n) {
                    return [0 > n ? n + t : n]
                }), even: u(function (e, t) {
                    for (var n = 0; t > n; n += 2)e.push(n);
                    return e
                }), odd: u(function (e, t) {
                    for (var n = 1; t > n; n += 2)e.push(n);
                    return e
                }), lt: u(function (e, t, n) {
                    for (var i = 0 > n ? n + t : n; --i >= 0;)e.push(i);
                    return e
                }), gt: u(function (e, t, n) {
                    for (var i = 0 > n ? n + t : n; ++i < t;)e.push(i);
                    return e
                })
            }
        }, E.pseudos.nth = E.pseudos.eq;
        for (x in{radio: !0, checkbox: !0, file: !0, password: !0, image: !0})E.pseudos[x] = s(x);
        for (x in{submit: !0, reset: !0})E.pseudos[x] = l(x);
        return d.prototype = E.filters = E.pseudos, E.setFilters = new d, _ = t.tokenize = function (e, n) {
            var i, r, o, a, s, l, u, c = W[e + " "];
            if (c)return n ? 0 : c.slice(0);
            for (s = e, l = [], u = E.preFilter; s;) {
                (!i || (r = ut.exec(s))) && (r && (s = s.slice(r[0].length) || s), l.push(o = [])), i = !1, (r = ct.exec(s)) && (i = r.shift(), o.push({
                    value: i,
                    type: r[0].replace(lt, " ")
                }), s = s.slice(i.length));
                for (a in E.filter)!(r = ht[a].exec(s)) || u[a] && !(r = u[a](r)) || (i = r.shift(), o.push({
                    value: i,
                    type: a,
                    matches: r
                }), s = s.slice(i.length));
                if (!i)break
            }
            return n ? s.length : s ? t.error(e) : W(e, l).slice(0)
        }, R = t.compile = function (e, t) {
            var n, i = [], r = [], o = U[e + " "];
            if (!o) {
                for (t || (t = _(e)), n = t.length; n--;)o = y(t[n]), o[j] ? i.push(o) : r.push(o);
                o = U(e, b(r, i)), o.selector = e
            }
            return o
        }, C = t.select = function (e, t, n, i) {
            var r, o, a, s, l, u = "function" == typeof e && e, d = !i && _(e = u.selector || e);
            if (n = n || [], 1 === d.length) {
                if (o = d[0] = d[0].slice(0), o.length > 2 && "ID" === (a = o[0]).type && w.getById && 9 === t.nodeType && F && E.relative[o[1].type]) {
                    if (t = (E.find.ID(a.matches[0].replace(wt, Et), t) || [])[0], !t)return n;
                    u && (t = t.parentNode), e = e.slice(o.shift().value.length)
                }
                for (r = ht.needsContext.test(e) ? 0 : o.length; r-- && (a = o[r], !E.relative[s = a.type]);)if ((l = E.find[s]) && (i = l(a.matches[0].replace(wt, Et), bt.test(o[0].type) && c(t.parentNode) || t))) {
                    if (o.splice(r, 1), e = i.length && p(o), !e)return Z.apply(n, i), n;
                    break
                }
            }
            return (u || R(e, d))(i, t, !F, n, bt.test(e) && c(t.parentNode) || t), n
        }, w.sortStable = j.split("").sort(V).join("") === j, w.detectDuplicates = !!O, A(), w.sortDetached = r(function (e) {
            return 1 & e.compareDocumentPosition(k.createElement("div"))
        }), r(function (e) {
            return e.innerHTML = "<a href='#'></a>", "#" === e.firstChild.getAttribute("href")
        }) || o("type|href|height|width", function (e, t, n) {
            return n ? void 0 : e.getAttribute(t, "type" === t.toLowerCase() ? 1 : 2)
        }), w.attributes && r(function (e) {
            return e.innerHTML = "<input/>", e.firstChild.setAttribute("value", ""), "" === e.firstChild.getAttribute("value")
        }) || o("value", function (e, t, n) {
            return n || "input" !== e.nodeName.toLowerCase() ? void 0 : e.defaultValue
        }), r(function (e) {
            return null == e.getAttribute("disabled")
        }) || o(nt, function (e, t, n) {
            var i;
            return n ? void 0 : e[t] === !0 ? t.toLowerCase() : (i = e.getAttributeNode(t)) && i.specified ? i.value : null
        }), t
    }(e);
    rt.find = ut, rt.expr = ut.selectors, rt.expr[":"] = rt.expr.pseudos, rt.unique = ut.uniqueSort, rt.text = ut.getText, rt.isXMLDoc = ut.isXML, rt.contains = ut.contains;
    var ct = rt.expr.match.needsContext, dt = /^<(\w+)\s*\/?>(?:<\/\1>|)$/, pt = /^.[^:#\[\.,]*$/;
    rt.filter = function (e, t, n) {
        var i = t[0];
        return n && (e = ":not(" + e + ")"), 1 === t.length && 1 === i.nodeType ? rt.find.matchesSelector(i, e) ? [i] : [] : rt.find.matches(e, rt.grep(t, function (e) {
            return 1 === e.nodeType
        }))
    }, rt.fn.extend({
        find: function (e) {
            var t, n = [], i = this, r = i.length;
            if ("string" != typeof e)return this.pushStack(rt(e).filter(function () {
                for (t = 0; r > t; t++)if (rt.contains(i[t], this))return !0
            }));
            for (t = 0; r > t; t++)rt.find(e, i[t], n);
            return n = this.pushStack(r > 1 ? rt.unique(n) : n), n.selector = this.selector ? this.selector + " " + e : e, n
        }, filter: function (e) {
            return this.pushStack(i(this, e || [], !1))
        }, not: function (e) {
            return this.pushStack(i(this, e || [], !0))
        }, is: function (e) {
            return !!i(this, "string" == typeof e && ct.test(e) ? rt(e) : e || [], !1).length
        }
    });
    var ft, ht = e.document, mt = /^(?:\s*(<[\w\W]+>)[^>]*|#([\w-]*))$/, gt = rt.fn.init = function (e, t) {
        var n, i;
        if (!e)return this;
        if ("string" == typeof e) {
            if (n = "<" === e.charAt(0) && ">" === e.charAt(e.length - 1) && e.length >= 3 ? [null, e, null] : mt.exec(e), !n || !n[1] && t)return !t || t.jquery ? (t || ft).find(e) : this.constructor(t).find(e);
            if (n[1]) {
                if (t = t instanceof rt ? t[0] : t, rt.merge(this, rt.parseHTML(n[1], t && t.nodeType ? t.ownerDocument || t : ht, !0)), dt.test(n[1]) && rt.isPlainObject(t))for (n in t)rt.isFunction(this[n]) ? this[n](t[n]) : this.attr(n, t[n]);
                return this
            }
            if (i = ht.getElementById(n[2]), i && i.parentNode) {
                if (i.id !== n[2])return ft.find(e);
                this.length = 1, this[0] = i
            }
            return this.context = ht, this.selector = e, this
        }
        return e.nodeType ? (this.context = this[0] = e, this.length = 1, this) : rt.isFunction(e) ? "undefined" != typeof ft.ready ? ft.ready(e) : e(rt) : (void 0 !== e.selector && (this.selector = e.selector, this.context = e.context), rt.makeArray(e, this))
    };
    gt.prototype = rt.fn, ft = rt(ht);
    var vt = /^(?:parents|prev(?:Until|All))/, yt = {children: !0, contents: !0, next: !0, prev: !0};
    rt.extend({
        dir: function (e, t, n) {
            for (var i = [], r = e[t]; r && 9 !== r.nodeType && (void 0 === n || 1 !== r.nodeType || !rt(r).is(n));)1 === r.nodeType && i.push(r), r = r[t];
            return i
        }, sibling: function (e, t) {
            for (var n = []; e; e = e.nextSibling)1 === e.nodeType && e !== t && n.push(e);
            return n
        }
    }), rt.fn.extend({
        has: function (e) {
            var t, n = rt(e, this), i = n.length;
            return this.filter(function () {
                for (t = 0; i > t; t++)if (rt.contains(this, n[t]))return !0
            })
        }, closest: function (e, t) {
            for (var n, i = 0, r = this.length, o = [], a = ct.test(e) || "string" != typeof e ? rt(e, t || this.context) : 0; r > i; i++)for (n = this[i]; n && n !== t; n = n.parentNode)if (n.nodeType < 11 && (a ? a.index(n) > -1 : 1 === n.nodeType && rt.find.matchesSelector(n, e))) {
                o.push(n);
                break
            }
            return this.pushStack(o.length > 1 ? rt.unique(o) : o)
        }, index: function (e) {
            return e ? "string" == typeof e ? rt.inArray(this[0], rt(e)) : rt.inArray(e.jquery ? e[0] : e, this) : this[0] && this[0].parentNode ? this.first().prevAll().length : -1
        }, add: function (e, t) {
            return this.pushStack(rt.unique(rt.merge(this.get(), rt(e, t))))
        }, addBack: function (e) {
            return this.add(null == e ? this.prevObject : this.prevObject.filter(e))
        }
    }), rt.each({
        parent: function (e) {
            var t = e.parentNode;
            return t && 11 !== t.nodeType ? t : null
        }, parents: function (e) {
            return rt.dir(e, "parentNode")
        }, parentsUntil: function (e, t, n) {
            return rt.dir(e, "parentNode", n)
        }, next: function (e) {
            return r(e, "nextSibling")
        }, prev: function (e) {
            return r(e, "previousSibling")
        }, nextAll: function (e) {
            return rt.dir(e, "nextSibling")
        }, prevAll: function (e) {
            return rt.dir(e, "previousSibling")
        }, nextUntil: function (e, t, n) {
            return rt.dir(e, "nextSibling", n)
        }, prevUntil: function (e, t, n) {
            return rt.dir(e, "previousSibling", n)
        }, siblings: function (e) {
            return rt.sibling((e.parentNode || {}).firstChild, e)
        }, children: function (e) {
            return rt.sibling(e.firstChild)
        }, contents: function (e) {
            return rt.nodeName(e, "iframe") ? e.contentDocument || e.contentWindow.document : rt.merge([], e.childNodes)
        }
    }, function (e, t) {
        rt.fn[e] = function (n, i) {
            var r = rt.map(this, t, n);
            return "Until" !== e.slice(-5) && (i = n), i && "string" == typeof i && (r = rt.filter(i, r)), this.length > 1 && (yt[e] || (r = rt.unique(r)), vt.test(e) && (r = r.reverse())), this.pushStack(r)
        }
    });
    var bt = /\S+/g, xt = {};
    rt.Callbacks = function (e) {
        e = "string" == typeof e ? xt[e] || o(e) : rt.extend({}, e);
        var t, n, i, r, a, s, l = [], u = !e.once && [], c = function (o) {
            for (n = e.memory && o, i = !0, a = s || 0, s = 0, r = l.length, t = !0; l && r > a; a++)if (l[a].apply(o[0], o[1]) === !1 && e.stopOnFalse) {
                n = !1;
                break
            }
            t = !1, l && (u ? u.length && c(u.shift()) : n ? l = [] : d.disable())
        }, d = {
            add: function () {
                if (l) {
                    var i = l.length;
                    !function o(t) {
                        rt.each(t, function (t, n) {
                            var i = rt.type(n);
                            "function" === i ? e.unique && d.has(n) || l.push(n) : n && n.length && "string" !== i && o(n)
                        })
                    }(arguments), t ? r = l.length : n && (s = i, c(n))
                }
                return this
            }, remove: function () {
                return l && rt.each(arguments, function (e, n) {
                    for (var i; (i = rt.inArray(n, l, i)) > -1;)l.splice(i, 1), t && (r >= i && r--, a >= i && a--)
                }), this
            }, has: function (e) {
                return e ? rt.inArray(e, l) > -1 : !(!l || !l.length)
            }, empty: function () {
                return l = [], r = 0, this
            }, disable: function () {
                return l = u = n = void 0, this
            }, disabled: function () {
                return !l
            }, lock: function () {
                return u = void 0, n || d.disable(), this
            }, locked: function () {
                return !u
            }, fireWith: function (e, n) {
                return !l || i && !u || (n = n || [], n = [e, n.slice ? n.slice() : n], t ? u.push(n) : c(n)), this
            }, fire: function () {
                return d.fireWith(this, arguments), this
            }, fired: function () {
                return !!i
            }
        };
        return d
    }, rt.extend({
        Deferred: function (e) {
            var t = [["resolve", "done", rt.Callbacks("once memory"), "resolved"], ["reject", "fail", rt.Callbacks("once memory"), "rejected"], ["notify", "progress", rt.Callbacks("memory")]], n = "pending", i = {
                state: function () {
                    return n
                }, always: function () {
                    return r.done(arguments).fail(arguments), this
                }, then: function () {
                    var e = arguments;
                    return rt.Deferred(function (n) {
                        rt.each(t, function (t, o) {
                            var a = rt.isFunction(e[t]) && e[t];
                            r[o[1]](function () {
                                var e = a && a.apply(this, arguments);
                                e && rt.isFunction(e.promise) ? e.promise().done(n.resolve).fail(n.reject).progress(n.notify) : n[o[0] + "With"](this === i ? n.promise() : this, a ? [e] : arguments)
                            })
                        }), e = null
                    }).promise()
                }, promise: function (e) {
                    return null != e ? rt.extend(e, i) : i
                }
            }, r = {};
            return i.pipe = i.then, rt.each(t, function (e, o) {
                var a = o[2], s = o[3];
                i[o[1]] = a.add, s && a.add(function () {
                    n = s
                }, t[1 ^ e][2].disable, t[2][2].lock), r[o[0]] = function () {
                    return r[o[0] + "With"](this === r ? i : this, arguments), this
                }, r[o[0] + "With"] = a.fireWith
            }), i.promise(r), e && e.call(r, r), r
        }, when: function (e) {
            var t, n, i, r = 0, o = J.call(arguments), a = o.length, s = 1 !== a || e && rt.isFunction(e.promise) ? a : 0, l = 1 === s ? e : rt.Deferred(), u = function (e, n, i) {
                return function (r) {
                    n[e] = this, i[e] = arguments.length > 1 ? J.call(arguments) : r, i === t ? l.notifyWith(n, i) : --s || l.resolveWith(n, i)
                }
            };
            if (a > 1)for (t = new Array(a), n = new Array(a), i = new Array(a); a > r; r++)o[r] && rt.isFunction(o[r].promise) ? o[r].promise().done(u(r, i, o)).fail(l.reject).progress(u(r, n, t)) : --s;
            return s || l.resolveWith(i, o), l.promise()
        }
    });
    var wt;
    rt.fn.ready = function (e) {
        return rt.ready.promise().done(e), this
    }, rt.extend({
        isReady: !1, readyWait: 1, holdReady: function (e) {
            e ? rt.readyWait++ : rt.ready(!0)
        }, ready: function (e) {
            if (e === !0 ? !--rt.readyWait : !rt.isReady) {
                if (!ht.body)return setTimeout(rt.ready);
                rt.isReady = !0, e !== !0 && --rt.readyWait > 0 || (wt.resolveWith(ht, [rt]), rt.fn.triggerHandler && (rt(ht).triggerHandler("ready"), rt(ht).off("ready")))
            }
        }
    }), rt.ready.promise = function (t) {
        if (!wt)if (wt = rt.Deferred(), "complete" === ht.readyState)setTimeout(rt.ready); else if (ht.addEventListener)ht.addEventListener("DOMContentLoaded", s, !1), e.addEventListener("load", s, !1); else {
            ht.attachEvent("onreadystatechange", s), e.attachEvent("onload", s);
            var n = !1;
            try {
                n = null == e.frameElement && ht.documentElement
            } catch (i) {
            }
            n && n.doScroll && !function r() {
                if (!rt.isReady) {
                    try {
                        n.doScroll("left")
                    } catch (e) {
                        return setTimeout(r, 50)
                    }
                    a(), rt.ready()
                }
            }()
        }
        return wt.promise(t)
    };
    var Et, Tt = "undefined";
    for (Et in rt(nt))break;
    nt.ownLast = "0" !== Et, nt.inlineBlockNeedsLayout = !1, rt(function () {
        var e, t, n, i;
        n = ht.getElementsByTagName("body")[0], n && n.style && (t = ht.createElement("div"), i = ht.createElement("div"), i.style.cssText = "position:absolute;border:0;width:0;height:0;top:0;left:-9999px", n.appendChild(i).appendChild(t), typeof t.style.zoom !== Tt && (t.style.cssText = "display:inline;margin:0;border:0;padding:1px;width:1px;zoom:1", nt.inlineBlockNeedsLayout = e = 3 === t.offsetWidth, e && (n.style.zoom = 1)), n.removeChild(i))
    }), function () {
        var e = ht.createElement("div");
        if (null == nt.deleteExpando) {
            nt.deleteExpando = !0;
            try {
                delete e.test
            } catch (t) {
                nt.deleteExpando = !1
            }
        }
        e = null
    }(), rt.acceptData = function (e) {
        var t = rt.noData[(e.nodeName + " ").toLowerCase()], n = +e.nodeType || 1;
        return 1 !== n && 9 !== n ? !1 : !t || t !== !0 && e.getAttribute("classid") === t
    };
    var St = /^(?:\{[\w\W]*\}|\[[\w\W]*\])$/, _t = /([A-Z])/g;
    rt.extend({
        cache: {},
        noData: {"applet ": !0, "embed ": !0, "object ": "clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"},
        hasData: function (e) {
            return e = e.nodeType ? rt.cache[e[rt.expando]] : e[rt.expando], !!e && !u(e)
        },
        data: function (e, t, n) {
            return c(e, t, n)
        },
        removeData: function (e, t) {
            return d(e, t)
        },
        _data: function (e, t, n) {
            return c(e, t, n, !0)
        },
        _removeData: function (e, t) {
            return d(e, t, !0)
        }
    }), rt.fn.extend({
        data: function (e, t) {
            var n, i, r, o = this[0], a = o && o.attributes;
            if (void 0 === e) {
                if (this.length && (r = rt.data(o), 1 === o.nodeType && !rt._data(o, "parsedAttrs"))) {
                    for (n = a.length; n--;)a[n] && (i = a[n].name, 0 === i.indexOf("data-") && (i = rt.camelCase(i.slice(5)), l(o, i, r[i])));
                    rt._data(o, "parsedAttrs", !0)
                }
                return r
            }
            return "object" == typeof e ? this.each(function () {
                rt.data(this, e)
            }) : arguments.length > 1 ? this.each(function () {
                rt.data(this, e, t)
            }) : o ? l(o, e, rt.data(o, e)) : void 0
        }, removeData: function (e) {
            return this.each(function () {
                rt.removeData(this, e)
            })
        }
    }), rt.extend({
        queue: function (e, t, n) {
            var i;
            return e ? (t = (t || "fx") + "queue", i = rt._data(e, t), n && (!i || rt.isArray(n) ? i = rt._data(e, t, rt.makeArray(n)) : i.push(n)), i || []) : void 0
        }, dequeue: function (e, t) {
            t = t || "fx";
            var n = rt.queue(e, t), i = n.length, r = n.shift(), o = rt._queueHooks(e, t), a = function () {
                rt.dequeue(e, t)
            };
            "inprogress" === r && (r = n.shift(), i--), r && ("fx" === t && n.unshift("inprogress"), delete o.stop, r.call(e, a, o)), !i && o && o.empty.fire()
        }, _queueHooks: function (e, t) {
            var n = t + "queueHooks";
            return rt._data(e, n) || rt._data(e, n, {
                    empty: rt.Callbacks("once memory").add(function () {
                        rt._removeData(e, t + "queue"), rt._removeData(e, n)
                    })
                })
        }
    }), rt.fn.extend({
        queue: function (e, t) {
            var n = 2;
            return "string" != typeof e && (t = e, e = "fx", n--), arguments.length < n ? rt.queue(this[0], e) : void 0 === t ? this : this.each(function () {
                var n = rt.queue(this, e, t);
                rt._queueHooks(this, e), "fx" === e && "inprogress" !== n[0] && rt.dequeue(this, e)
            })
        }, dequeue: function (e) {
            return this.each(function () {
                rt.dequeue(this, e)
            })
        }, clearQueue: function (e) {
            return this.queue(e || "fx", [])
        }, promise: function (e, t) {
            var n, i = 1, r = rt.Deferred(), o = this, a = this.length, s = function () {
                --i || r.resolveWith(o, [o])
            };
            for ("string" != typeof e && (t = e, e = void 0), e = e || "fx"; a--;)n = rt._data(o[a], e + "queueHooks"), n && n.empty && (i++, n.empty.add(s));
            return s(), r.promise(t)
        }
    });
    var Rt = /[+-]?(?:\d*\.|)\d+(?:[eE][+-]?\d+|)/.source, Ct = ["Top", "Right", "Bottom", "Left"], Dt = function (e, t) {
        return e = t || e, "none" === rt.css(e, "display") || !rt.contains(e.ownerDocument, e)
    }, Nt = rt.access = function (e, t, n, i, r, o, a) {
        var s = 0, l = e.length, u = null == n;
        if ("object" === rt.type(n)) {
            r = !0;
            for (s in n)rt.access(e, t, s, n[s], !0, o, a)
        } else if (void 0 !== i && (r = !0, rt.isFunction(i) || (a = !0), u && (a ? (t.call(e, i), t = null) : (u = t, t = function (e, t, n) {
                return u.call(rt(e), n)
            })), t))for (; l > s; s++)t(e[s], n, a ? i : i.call(e[s], s, t(e[s], n)));
        return r ? e : u ? t.call(e) : l ? t(e[0], n) : o
    }, Ot = /^(?:checkbox|radio)$/i;
    !function () {
        var e = ht.createElement("input"), t = ht.createElement("div"), n = ht.createDocumentFragment();
        if (t.innerHTML = "  <link/><table></table><a href='/a'>a</a><input type='checkbox'/>", nt.leadingWhitespace = 3 === t.firstChild.nodeType, nt.tbody = !t.getElementsByTagName("tbody").length, nt.htmlSerialize = !!t.getElementsByTagName("link").length, nt.html5Clone = "<:nav></:nav>" !== ht.createElement("nav").cloneNode(!0).outerHTML, e.type = "checkbox", e.checked = !0, n.appendChild(e), nt.appendChecked = e.checked, t.innerHTML = "<textarea>x</textarea>", nt.noCloneChecked = !!t.cloneNode(!0).lastChild.defaultValue, n.appendChild(t), t.innerHTML = "<input type='radio' checked='checked' name='t'/>", nt.checkClone = t.cloneNode(!0).cloneNode(!0).lastChild.checked, nt.noCloneEvent = !0, t.attachEvent && (t.attachEvent("onclick", function () {
                nt.noCloneEvent = !1
            }), t.cloneNode(!0).click()), null == nt.deleteExpando) {
            nt.deleteExpando = !0;
            try {
                delete t.test
            } catch (i) {
                nt.deleteExpando = !1
            }
        }
    }(), function () {
        var t, n, i = ht.createElement("div");
        for (t in{
            submit: !0,
            change: !0,
            focusin: !0
        })n = "on" + t, (nt[t + "Bubbles"] = n in e) || (i.setAttribute(n, "t"), nt[t + "Bubbles"] = i.attributes[n].expando === !1);
        i = null
    }();
    var At = /^(?:input|select|textarea)$/i, kt = /^key/, It = /^(?:mouse|pointer|contextmenu)|click/, Ft = /^(?:focusinfocus|focusoutblur)$/, Lt = /^([^.]*)(?:\.(.+)|)$/;
    rt.event = {
        global: {},
        add: function (e, t, n, i, r) {
            var o, a, s, l, u, c, d, p, f, h, m, g = rt._data(e);
            if (g) {
                for (n.handler && (l = n, n = l.handler, r = l.selector), n.guid || (n.guid = rt.guid++), (a = g.events) || (a = g.events = {}), (c = g.handle) || (c = g.handle = function (e) {
                    return typeof rt === Tt || e && rt.event.triggered === e.type ? void 0 : rt.event.dispatch.apply(c.elem, arguments)
                }, c.elem = e), t = (t || "").match(bt) || [""], s = t.length; s--;)o = Lt.exec(t[s]) || [], f = m = o[1], h = (o[2] || "").split(".").sort(), f && (u = rt.event.special[f] || {}, f = (r ? u.delegateType : u.bindType) || f, u = rt.event.special[f] || {}, d = rt.extend({
                    type: f,
                    origType: m,
                    data: i,
                    handler: n,
                    guid: n.guid,
                    selector: r,
                    needsContext: r && rt.expr.match.needsContext.test(r),
                    namespace: h.join(".")
                }, l), (p = a[f]) || (p = a[f] = [], p.delegateCount = 0, u.setup && u.setup.call(e, i, h, c) !== !1 || (e.addEventListener ? e.addEventListener(f, c, !1) : e.attachEvent && e.attachEvent("on" + f, c))), u.add && (u.add.call(e, d), d.handler.guid || (d.handler.guid = n.guid)), r ? p.splice(p.delegateCount++, 0, d) : p.push(d), rt.event.global[f] = !0);
                e = null
            }
        },
        remove: function (e, t, n, i, r) {
            var o, a, s, l, u, c, d, p, f, h, m, g = rt.hasData(e) && rt._data(e);
            if (g && (c = g.events)) {
                for (t = (t || "").match(bt) || [""], u = t.length; u--;)if (s = Lt.exec(t[u]) || [], f = m = s[1], h = (s[2] || "").split(".").sort(), f) {
                    for (d = rt.event.special[f] || {}, f = (i ? d.delegateType : d.bindType) || f, p = c[f] || [], s = s[2] && new RegExp("(^|\\.)" + h.join("\\.(?:.*\\.|)") + "(\\.|$)"), l = o = p.length; o--;)a = p[o], !r && m !== a.origType || n && n.guid !== a.guid || s && !s.test(a.namespace) || i && i !== a.selector && ("**" !== i || !a.selector) || (p.splice(o, 1), a.selector && p.delegateCount--, d.remove && d.remove.call(e, a));
                    l && !p.length && (d.teardown && d.teardown.call(e, h, g.handle) !== !1 || rt.removeEvent(e, f, g.handle), delete c[f])
                } else for (f in c)rt.event.remove(e, f + t[u], n, i, !0);
                rt.isEmptyObject(c) && (delete g.handle, rt._removeData(e, "events"))
            }
        },
        trigger: function (t, n, i, r) {
            var o, a, s, l, u, c, d, p = [i || ht], f = tt.call(t, "type") ? t.type : t, h = tt.call(t, "namespace") ? t.namespace.split(".") : [];
            if (s = c = i = i || ht, 3 !== i.nodeType && 8 !== i.nodeType && !Ft.test(f + rt.event.triggered) && (f.indexOf(".") >= 0 && (h = f.split("."), f = h.shift(), h.sort()), a = f.indexOf(":") < 0 && "on" + f, t = t[rt.expando] ? t : new rt.Event(f, "object" == typeof t && t), t.isTrigger = r ? 2 : 3, t.namespace = h.join("."), t.namespace_re = t.namespace ? new RegExp("(^|\\.)" + h.join("\\.(?:.*\\.|)") + "(\\.|$)") : null, t.result = void 0, t.target || (t.target = i), n = null == n ? [t] : rt.makeArray(n, [t]), u = rt.event.special[f] || {}, r || !u.trigger || u.trigger.apply(i, n) !== !1)) {
                if (!r && !u.noBubble && !rt.isWindow(i)) {
                    for (l = u.delegateType || f, Ft.test(l + f) || (s = s.parentNode); s; s = s.parentNode)p.push(s), c = s;
                    c === (i.ownerDocument || ht) && p.push(c.defaultView || c.parentWindow || e)
                }
                for (d = 0; (s = p[d++]) && !t.isPropagationStopped();)t.type = d > 1 ? l : u.bindType || f, o = (rt._data(s, "events") || {})[t.type] && rt._data(s, "handle"), o && o.apply(s, n), o = a && s[a], o && o.apply && rt.acceptData(s) && (t.result = o.apply(s, n), t.result === !1 && t.preventDefault());
                if (t.type = f, !r && !t.isDefaultPrevented() && (!u._default || u._default.apply(p.pop(), n) === !1) && rt.acceptData(i) && a && i[f] && !rt.isWindow(i)) {
                    c = i[a], c && (i[a] = null), rt.event.triggered = f;
                    try {
                        i[f]()
                    } catch (m) {
                    }
                    rt.event.triggered = void 0, c && (i[a] = c)
                }
                return t.result
            }
        },
        dispatch: function (e) {
            e = rt.event.fix(e);
            var t, n, i, r, o, a = [], s = J.call(arguments), l = (rt._data(this, "events") || {})[e.type] || [], u = rt.event.special[e.type] || {};
            if (s[0] = e, e.delegateTarget = this, !u.preDispatch || u.preDispatch.call(this, e) !== !1) {
                for (a = rt.event.handlers.call(this, e, l), t = 0; (r = a[t++]) && !e.isPropagationStopped();)for (e.currentTarget = r.elem, o = 0; (i = r.handlers[o++]) && !e.isImmediatePropagationStopped();)(!e.namespace_re || e.namespace_re.test(i.namespace)) && (e.handleObj = i, e.data = i.data, n = ((rt.event.special[i.origType] || {}).handle || i.handler).apply(r.elem, s), void 0 !== n && (e.result = n) === !1 && (e.preventDefault(), e.stopPropagation()));
                return u.postDispatch && u.postDispatch.call(this, e), e.result
            }
        },
        handlers: function (e, t) {
            var n, i, r, o, a = [], s = t.delegateCount, l = e.target;
            if (s && l.nodeType && (!e.button || "click" !== e.type))for (; l != this; l = l.parentNode || this)if (1 === l.nodeType && (l.disabled !== !0 || "click" !== e.type)) {
                for (r = [], o = 0; s > o; o++)i = t[o], n = i.selector + " ", void 0 === r[n] && (r[n] = i.needsContext ? rt(n, this).index(l) >= 0 : rt.find(n, this, null, [l]).length), r[n] && r.push(i);
                r.length && a.push({elem: l, handlers: r})
            }
            return s < t.length && a.push({elem: this, handlers: t.slice(s)}), a
        },
        fix: function (e) {
            if (e[rt.expando])return e;
            var t, n, i, r = e.type, o = e, a = this.fixHooks[r];
            for (a || (this.fixHooks[r] = a = It.test(r) ? this.mouseHooks : kt.test(r) ? this.keyHooks : {}), i = a.props ? this.props.concat(a.props) : this.props, e = new rt.Event(o), t = i.length; t--;)n = i[t], e[n] = o[n];
            return e.target || (e.target = o.srcElement || ht), 3 === e.target.nodeType && (e.target = e.target.parentNode), e.metaKey = !!e.metaKey, a.filter ? a.filter(e, o) : e
        },
        props: "altKey bubbles cancelable ctrlKey currentTarget eventPhase metaKey relatedTarget shiftKey target timeStamp view which".split(" "),
        fixHooks: {},
        keyHooks: {
            props: "char charCode key keyCode".split(" "), filter: function (e, t) {
                return null == e.which && (e.which = null != t.charCode ? t.charCode : t.keyCode), e
            }
        },
        mouseHooks: {
            props: "button buttons clientX clientY fromElement offsetX offsetY pageX pageY screenX screenY toElement".split(" "),
            filter: function (e, t) {
                var n, i, r, o = t.button, a = t.fromElement;
                return null == e.pageX && null != t.clientX && (i = e.target.ownerDocument || ht, r = i.documentElement, n = i.body, e.pageX = t.clientX + (r && r.scrollLeft || n && n.scrollLeft || 0) - (r && r.clientLeft || n && n.clientLeft || 0), e.pageY = t.clientY + (r && r.scrollTop || n && n.scrollTop || 0) - (r && r.clientTop || n && n.clientTop || 0)), !e.relatedTarget && a && (e.relatedTarget = a === e.target ? t.toElement : a), e.which || void 0 === o || (e.which = 1 & o ? 1 : 2 & o ? 3 : 4 & o ? 2 : 0), e
            }
        },
        special: {
            load: {noBubble: !0}, focus: {
                trigger: function () {
                    if (this !== h() && this.focus)try {
                        return this.focus(), !1
                    } catch (e) {
                    }
                }, delegateType: "focusin"
            }, blur: {
                trigger: function () {
                    return this === h() && this.blur ? (this.blur(), !1) : void 0
                }, delegateType: "focusout"
            }, click: {
                trigger: function () {
                    return rt.nodeName(this, "input") && "checkbox" === this.type && this.click ? (this.click(), !1) : void 0
                }, _default: function (e) {
                    return rt.nodeName(e.target, "a")
                }
            }, beforeunload: {
                postDispatch: function (e) {
                    void 0 !== e.result && e.originalEvent && (e.originalEvent.returnValue = e.result)
                }
            }
        },
        simulate: function (e, t, n, i) {
            var r = rt.extend(new rt.Event, n, {type: e, isSimulated: !0, originalEvent: {}});
            i ? rt.event.trigger(r, null, t) : rt.event.dispatch.call(t, r), r.isDefaultPrevented() && n.preventDefault()
        }
    }, rt.removeEvent = ht.removeEventListener ? function (e, t, n) {
        e.removeEventListener && e.removeEventListener(t, n, !1)
    } : function (e, t, n) {
        var i = "on" + t;
        e.detachEvent && (typeof e[i] === Tt && (e[i] = null), e.detachEvent(i, n))
    }, rt.Event = function (e, t) {
        return this instanceof rt.Event ? (e && e.type ? (this.originalEvent = e, this.type = e.type, this.isDefaultPrevented = e.defaultPrevented || void 0 === e.defaultPrevented && e.returnValue === !1 ? p : f) : this.type = e, t && rt.extend(this, t), this.timeStamp = e && e.timeStamp || rt.now(), void(this[rt.expando] = !0)) : new rt.Event(e, t)
    }, rt.Event.prototype = {
        isDefaultPrevented: f,
        isPropagationStopped: f,
        isImmediatePropagationStopped: f,
        preventDefault: function () {
            var e = this.originalEvent;
            this.isDefaultPrevented = p, e && (e.preventDefault ? e.preventDefault() : e.returnValue = !1)
        },
        stopPropagation: function () {
            var e = this.originalEvent;
            this.isPropagationStopped = p, e && (e.stopPropagation && e.stopPropagation(), e.cancelBubble = !0)
        },
        stopImmediatePropagation: function () {
            var e = this.originalEvent;
            this.isImmediatePropagationStopped = p, e && e.stopImmediatePropagation && e.stopImmediatePropagation(), this.stopPropagation()
        }
    }, rt.each({
        mouseenter: "mouseover",
        mouseleave: "mouseout",
        pointerenter: "pointerover",
        pointerleave: "pointerout"
    }, function (e, t) {
        rt.event.special[e] = {
            delegateType: t, bindType: t, handle: function (e) {
                var n, i = this, r = e.relatedTarget, o = e.handleObj;
                return (!r || r !== i && !rt.contains(i, r)) && (e.type = o.origType, n = o.handler.apply(this, arguments), e.type = t), n
            }
        }
    }), nt.submitBubbles || (rt.event.special.submit = {
        setup: function () {
            return rt.nodeName(this, "form") ? !1 : void rt.event.add(this, "click._submit keypress._submit", function (e) {
                var t = e.target, n = rt.nodeName(t, "input") || rt.nodeName(t, "button") ? t.form : void 0;
                n && !rt._data(n, "submitBubbles") && (rt.event.add(n, "submit._submit", function (e) {
                    e._submit_bubble = !0
                }), rt._data(n, "submitBubbles", !0))
            })
        }, postDispatch: function (e) {
            e._submit_bubble && (delete e._submit_bubble, this.parentNode && !e.isTrigger && rt.event.simulate("submit", this.parentNode, e, !0))
        }, teardown: function () {
            return rt.nodeName(this, "form") ? !1 : void rt.event.remove(this, "._submit")
        }
    }), nt.changeBubbles || (rt.event.special.change = {
        setup: function () {
            return At.test(this.nodeName) ? (("checkbox" === this.type || "radio" === this.type) && (rt.event.add(this, "propertychange._change", function (e) {
                "checked" === e.originalEvent.propertyName && (this._just_changed = !0)
            }), rt.event.add(this, "click._change", function (e) {
                this._just_changed && !e.isTrigger && (this._just_changed = !1), rt.event.simulate("change", this, e, !0)
            })), !1) : void rt.event.add(this, "beforeactivate._change", function (e) {
                var t = e.target;
                At.test(t.nodeName) && !rt._data(t, "changeBubbles") && (rt.event.add(t, "change._change", function (e) {
                    !this.parentNode || e.isSimulated || e.isTrigger || rt.event.simulate("change", this.parentNode, e, !0)
                }), rt._data(t, "changeBubbles", !0))
            })
        }, handle: function (e) {
            var t = e.target;
            return this !== t || e.isSimulated || e.isTrigger || "radio" !== t.type && "checkbox" !== t.type ? e.handleObj.handler.apply(this, arguments) : void 0
        }, teardown: function () {
            return rt.event.remove(this, "._change"), !At.test(this.nodeName)
        }
    }), nt.focusinBubbles || rt.each({focus: "focusin", blur: "focusout"}, function (e, t) {
        var n = function (e) {
            rt.event.simulate(t, e.target, rt.event.fix(e), !0)
        };
        rt.event.special[t] = {
            setup: function () {
                var i = this.ownerDocument || this, r = rt._data(i, t);
                r || i.addEventListener(e, n, !0), rt._data(i, t, (r || 0) + 1)
            }, teardown: function () {
                var i = this.ownerDocument || this, r = rt._data(i, t) - 1;
                r ? rt._data(i, t, r) : (i.removeEventListener(e, n, !0), rt._removeData(i, t))
            }
        }
    }), rt.fn.extend({
        on: function (e, t, n, i, r) {
            var o, a;
            if ("object" == typeof e) {
                "string" != typeof t && (n = n || t, t = void 0);
                for (o in e)this.on(o, t, n, e[o], r);
                return this
            }
            if (null == n && null == i ? (i = t, n = t = void 0) : null == i && ("string" == typeof t ? (i = n, n = void 0) : (i = n, n = t, t = void 0)), i === !1)i = f; else if (!i)return this;
            return 1 === r && (a = i, i = function (e) {
                return rt().off(e), a.apply(this, arguments)
            }, i.guid = a.guid || (a.guid = rt.guid++)), this.each(function () {
                rt.event.add(this, e, i, n, t)
            })
        }, one: function (e, t, n, i) {
            return this.on(e, t, n, i, 1)
        }, off: function (e, t, n) {
            var i, r;
            if (e && e.preventDefault && e.handleObj)return i = e.handleObj, rt(e.delegateTarget).off(i.namespace ? i.origType + "." + i.namespace : i.origType, i.selector, i.handler), this;
            if ("object" == typeof e) {
                for (r in e)this.off(r, t, e[r]);
                return this
            }
            return (t === !1 || "function" == typeof t) && (n = t, t = void 0), n === !1 && (n = f), this.each(function () {
                rt.event.remove(this, e, n, t)
            })
        }, trigger: function (e, t) {
            return this.each(function () {
                rt.event.trigger(e, t, this)
            })
        }, triggerHandler: function (e, t) {
            var n = this[0];
            return n ? rt.event.trigger(e, t, n, !0) : void 0
        }
    });
    var Mt = "abbr|article|aside|audio|bdi|canvas|data|datalist|details|figcaption|figure|footer|header|hgroup|mark|meter|nav|output|progress|section|summary|time|video", Ht = / jQuery\d+="(?:null|\d+)"/g, Pt = new RegExp("<(?:" + Mt + ")[\\s/>]", "i"), jt = /^\s+/, Bt = /<(?!area|br|col|embed|hr|img|input|link|meta|param)(([\w:]+)[^>]*)\/>/gi, $t = /<([\w:]+)/, zt = /<tbody/i, qt = /<|&#?\w+;/, Wt = /<(?:script|style|link)/i, Ut = /checked\s*(?:[^=]|=\s*.checked.)/i, Vt = /^$|\/(?:java|ecma)script/i, Gt = /^true\/(.*)/, Xt = /^\s*<!(?:\[CDATA\[|--)|(?:\]\]|--)>\s*$/g, Jt = {
        option: [1, "<select multiple='multiple'>", "</select>"],
        legend: [1, "<fieldset>", "</fieldset>"],
        area: [1, "<map>", "</map>"],
        param: [1, "<object>", "</object>"],
        thead: [1, "<table>", "</table>"],
        tr: [2, "<table><tbody>", "</tbody></table>"],
        col: [2, "<table><tbody></tbody><colgroup>", "</colgroup></table>"],
        td: [3, "<table><tbody><tr>", "</tr></tbody></table>"],
        _default: nt.htmlSerialize ? [0, "", ""] : [1, "X<div>", "</div>"]
    }, Yt = m(ht), Qt = Yt.appendChild(ht.createElement("div"));
    Jt.optgroup = Jt.option, Jt.tbody = Jt.tfoot = Jt.colgroup = Jt.caption = Jt.thead, Jt.th = Jt.td, rt.extend({
        clone: function (e, t, n) {
            var i, r, o, a, s, l = rt.contains(e.ownerDocument, e);
            if (nt.html5Clone || rt.isXMLDoc(e) || !Pt.test("<" + e.nodeName + ">") ? o = e.cloneNode(!0) : (Qt.innerHTML = e.outerHTML, Qt.removeChild(o = Qt.firstChild)), !(nt.noCloneEvent && nt.noCloneChecked || 1 !== e.nodeType && 11 !== e.nodeType || rt.isXMLDoc(e)))for (i = g(o), s = g(e), a = 0; null != (r = s[a]); ++a)i[a] && T(r, i[a]);
            if (t)if (n)for (s = s || g(e), i = i || g(o), a = 0; null != (r = s[a]); a++)E(r, i[a]); else E(e, o);
            return i = g(o, "script"), i.length > 0 && w(i, !l && g(e, "script")), i = s = r = null, o
        }, buildFragment: function (e, t, n, i) {
            for (var r, o, a, s, l, u, c, d = e.length, p = m(t), f = [], h = 0; d > h; h++)if (o = e[h], o || 0 === o)if ("object" === rt.type(o))rt.merge(f, o.nodeType ? [o] : o); else if (qt.test(o)) {
                for (s = s || p.appendChild(t.createElement("div")), l = ($t.exec(o) || ["", ""])[1].toLowerCase(), c = Jt[l] || Jt._default, s.innerHTML = c[1] + o.replace(Bt, "<$1></$2>") + c[2], r = c[0]; r--;)s = s.lastChild;
                if (!nt.leadingWhitespace && jt.test(o) && f.push(t.createTextNode(jt.exec(o)[0])), !nt.tbody)for (o = "table" !== l || zt.test(o) ? "<table>" !== c[1] || zt.test(o) ? 0 : s : s.firstChild, r = o && o.childNodes.length; r--;)rt.nodeName(u = o.childNodes[r], "tbody") && !u.childNodes.length && o.removeChild(u);
                for (rt.merge(f, s.childNodes), s.textContent = ""; s.firstChild;)s.removeChild(s.firstChild);
                s = p.lastChild
            } else f.push(t.createTextNode(o));
            for (s && p.removeChild(s), nt.appendChecked || rt.grep(g(f, "input"), v), h = 0; o = f[h++];)if ((!i || -1 === rt.inArray(o, i)) && (a = rt.contains(o.ownerDocument, o), s = g(p.appendChild(o), "script"), a && w(s), n))for (r = 0; o = s[r++];)Vt.test(o.type || "") && n.push(o);
            return s = null, p
        }, cleanData: function (e, t) {
            for (var n, i, r, o, a = 0, s = rt.expando, l = rt.cache, u = nt.deleteExpando, c = rt.event.special; null != (n = e[a]); a++)if ((t || rt.acceptData(n)) && (r = n[s], o = r && l[r])) {
                if (o.events)for (i in o.events)c[i] ? rt.event.remove(n, i) : rt.removeEvent(n, i, o.handle);
                l[r] && (delete l[r], u ? delete n[s] : typeof n.removeAttribute !== Tt ? n.removeAttribute(s) : n[s] = null, X.push(r))
            }
        }
    }), rt.fn.extend({
        text: function (e) {
            return Nt(this, function (e) {
                return void 0 === e ? rt.text(this) : this.empty().append((this[0] && this[0].ownerDocument || ht).createTextNode(e))
            }, null, e, arguments.length)
        }, append: function () {
            return this.domManip(arguments, function (e) {
                if (1 === this.nodeType || 11 === this.nodeType || 9 === this.nodeType) {
                    var t = y(this, e);
                    t.appendChild(e)
                }
            })
        }, prepend: function () {
            return this.domManip(arguments, function (e) {
                if (1 === this.nodeType || 11 === this.nodeType || 9 === this.nodeType) {
                    var t = y(this, e);
                    t.insertBefore(e, t.firstChild)
                }
            })
        }, before: function () {
            return this.domManip(arguments, function (e) {
                this.parentNode && this.parentNode.insertBefore(e, this)
            })
        }, after: function () {
            return this.domManip(arguments, function (e) {
                this.parentNode && this.parentNode.insertBefore(e, this.nextSibling)
            })
        }, remove: function (e, t) {
            for (var n, i = e ? rt.filter(e, this) : this, r = 0; null != (n = i[r]); r++)t || 1 !== n.nodeType || rt.cleanData(g(n)), n.parentNode && (t && rt.contains(n.ownerDocument, n) && w(g(n, "script")), n.parentNode.removeChild(n));
            return this
        }, empty: function () {
            for (var e, t = 0; null != (e = this[t]); t++) {
                for (1 === e.nodeType && rt.cleanData(g(e, !1)); e.firstChild;)e.removeChild(e.firstChild);
                e.options && rt.nodeName(e, "select") && (e.options.length = 0)
            }
            return this
        }, clone: function (e, t) {
            return e = null == e ? !1 : e, t = null == t ? e : t, this.map(function () {
                return rt.clone(this, e, t)
            })
        }, html: function (e) {
            return Nt(this, function (e) {
                var t = this[0] || {}, n = 0, i = this.length;
                if (void 0 === e)return 1 === t.nodeType ? t.innerHTML.replace(Ht, "") : void 0;
                if (!("string" != typeof e || Wt.test(e) || !nt.htmlSerialize && Pt.test(e) || !nt.leadingWhitespace && jt.test(e) || Jt[($t.exec(e) || ["", ""])[1].toLowerCase()])) {
                    e = e.replace(Bt, "<$1></$2>");
                    try {
                        for (; i > n; n++)t = this[n] || {}, 1 === t.nodeType && (rt.cleanData(g(t, !1)), t.innerHTML = e);
                        t = 0
                    } catch (r) {
                    }
                }
                t && this.empty().append(e)
            }, null, e, arguments.length)
        }, replaceWith: function () {
            var e = arguments[0];
            return this.domManip(arguments, function (t) {
                e = this.parentNode, rt.cleanData(g(this)), e && e.replaceChild(t, this)
            }), e && (e.length || e.nodeType) ? this : this.remove()
        }, detach: function (e) {
            return this.remove(e, !0)
        }, domManip: function (e, t) {
            e = Y.apply([], e);
            var n, i, r, o, a, s, l = 0, u = this.length, c = this, d = u - 1, p = e[0], f = rt.isFunction(p);
            if (f || u > 1 && "string" == typeof p && !nt.checkClone && Ut.test(p))return this.each(function (n) {
                var i = c.eq(n);
                f && (e[0] = p.call(this, n, i.html())), i.domManip(e, t)
            });
            if (u && (s = rt.buildFragment(e, this[0].ownerDocument, !1, this), n = s.firstChild, 1 === s.childNodes.length && (s = n), n)) {
                for (o = rt.map(g(s, "script"), b), r = o.length; u > l; l++)i = s, l !== d && (i = rt.clone(i, !0, !0), r && rt.merge(o, g(i, "script"))), t.call(this[l], i, l);
                if (r)for (a = o[o.length - 1].ownerDocument, rt.map(o, x), l = 0; r > l; l++)i = o[l], Vt.test(i.type || "") && !rt._data(i, "globalEval") && rt.contains(a, i) && (i.src ? rt._evalUrl && rt._evalUrl(i.src) : rt.globalEval((i.text || i.textContent || i.innerHTML || "").replace(Xt, "")));
                s = n = null
            }
            return this
        }
    }), rt.each({
        appendTo: "append",
        prependTo: "prepend",
        insertBefore: "before",
        insertAfter: "after",
        replaceAll: "replaceWith"
    }, function (e, t) {
        rt.fn[e] = function (e) {
            for (var n, i = 0, r = [], o = rt(e), a = o.length - 1; a >= i; i++)n = i === a ? this : this.clone(!0), rt(o[i])[t](n), Q.apply(r, n.get());
            return this.pushStack(r)
        }
    });
    var Kt, Zt = {};
    !function () {
        var e;
        nt.shrinkWrapBlocks = function () {
            if (null != e)return e;
            e = !1;
            var t, n, i;
            return n = ht.getElementsByTagName("body")[0], n && n.style ? (t = ht.createElement("div"), i = ht.createElement("div"), i.style.cssText = "position:absolute;border:0;width:0;height:0;top:0;left:-9999px", n.appendChild(i).appendChild(t), typeof t.style.zoom !== Tt && (t.style.cssText = "-webkit-box-sizing:content-box;-moz-box-sizing:content-box;box-sizing:content-box;display:block;margin:0;border:0;padding:1px;width:1px;zoom:1", t.appendChild(ht.createElement("div")).style.width = "5px", e = 3 !== t.offsetWidth), n.removeChild(i), e) : void 0
        }
    }();
    var en, tn, nn = /^margin/, rn = new RegExp("^(" + Rt + ")(?!px)[a-z%]+$", "i"), on = /^(top|right|bottom|left)$/;
    e.getComputedStyle ? (en = function (e) {
        return e.ownerDocument.defaultView.getComputedStyle(e, null)
    }, tn = function (e, t, n) {
        var i, r, o, a, s = e.style;
        return n = n || en(e), a = n ? n.getPropertyValue(t) || n[t] : void 0, n && ("" !== a || rt.contains(e.ownerDocument, e) || (a = rt.style(e, t)), rn.test(a) && nn.test(t) && (i = s.width, r = s.minWidth, o = s.maxWidth, s.minWidth = s.maxWidth = s.width = a, a = n.width, s.width = i, s.minWidth = r, s.maxWidth = o)), void 0 === a ? a : a + ""
    }) : ht.documentElement.currentStyle && (en = function (e) {
        return e.currentStyle
    }, tn = function (e, t, n) {
        var i, r, o, a, s = e.style;
        return n = n || en(e), a = n ? n[t] : void 0, null == a && s && s[t] && (a = s[t]), rn.test(a) && !on.test(t) && (i = s.left, r = e.runtimeStyle, o = r && r.left, o && (r.left = e.currentStyle.left), s.left = "fontSize" === t ? "1em" : a, a = s.pixelLeft + "px", s.left = i, o && (r.left = o)), void 0 === a ? a : a + "" || "auto"
    }), function () {
        function t() {
            var t, n, i, r;
            n = ht.getElementsByTagName("body")[0], n && n.style && (t = ht.createElement("div"), i = ht.createElement("div"), i.style.cssText = "position:absolute;border:0;width:0;height:0;top:0;left:-9999px", n.appendChild(i).appendChild(t), t.style.cssText = "-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box;display:block;margin-top:1%;top:1%;border:1px;padding:1px;width:4px;position:absolute", o = a = !1, l = !0, e.getComputedStyle && (o = "1%" !== (e.getComputedStyle(t, null) || {}).top, a = "4px" === (e.getComputedStyle(t, null) || {width: "4px"}).width, r = t.appendChild(ht.createElement("div")), r.style.cssText = t.style.cssText = "-webkit-box-sizing:content-box;-moz-box-sizing:content-box;box-sizing:content-box;display:block;margin:0;border:0;padding:0", r.style.marginRight = r.style.width = "0", t.style.width = "1px", l = !parseFloat((e.getComputedStyle(r, null) || {}).marginRight)), t.innerHTML = "<table><tr><td></td><td>t</td></tr></table>", r = t.getElementsByTagName("td"), r[0].style.cssText = "margin:0;border:0;padding:0;display:none", s = 0 === r[0].offsetHeight, s && (r[0].style.display = "", r[1].style.display = "none", s = 0 === r[0].offsetHeight), n.removeChild(i))
        }

        var n, i, r, o, a, s, l;
        n = ht.createElement("div"), n.innerHTML = "  <link/><table></table><a href='/a'>a</a><input type='checkbox'/>", r = n.getElementsByTagName("a")[0], i = r && r.style, i && (i.cssText = "float:left;opacity:.5", nt.opacity = "0.5" === i.opacity, nt.cssFloat = !!i.cssFloat, n.style.backgroundClip = "content-box", n.cloneNode(!0).style.backgroundClip = "", nt.clearCloneStyle = "content-box" === n.style.backgroundClip, nt.boxSizing = "" === i.boxSizing || "" === i.MozBoxSizing || "" === i.WebkitBoxSizing, rt.extend(nt, {
            reliableHiddenOffsets: function () {
                return null == s && t(), s
            }, boxSizingReliable: function () {
                return null == a && t(), a
            }, pixelPosition: function () {
                return null == o && t(), o
            }, reliableMarginRight: function () {
                return null == l && t(), l
            }
        }))
    }(), rt.swap = function (e, t, n, i) {
        var r, o, a = {};
        for (o in t)a[o] = e.style[o], e.style[o] = t[o];
        r = n.apply(e, i || []);
        for (o in t)e.style[o] = a[o];
        return r
    };
    var an = /alpha\([^)]*\)/i, sn = /opacity\s*=\s*([^)]*)/, ln = /^(none|table(?!-c[ea]).+)/, un = new RegExp("^(" + Rt + ")(.*)$", "i"), cn = new RegExp("^([+-])=(" + Rt + ")", "i"), dn = {
        position: "absolute",
        visibility: "hidden",
        display: "block"
    }, pn = {letterSpacing: "0", fontWeight: "400"}, fn = ["Webkit", "O", "Moz", "ms"];
    rt.extend({
        cssHooks: {
            opacity: {
                get: function (e, t) {
                    if (t) {
                        var n = tn(e, "opacity");
                        return "" === n ? "1" : n
                    }
                }
            }
        },
        cssNumber: {
            columnCount: !0,
            fillOpacity: !0,
            flexGrow: !0,
            flexShrink: !0,
            fontWeight: !0,
            lineHeight: !0,
            opacity: !0,
            order: !0,
            orphans: !0,
            widows: !0,
            zIndex: !0,
            zoom: !0
        },
        cssProps: {"float": nt.cssFloat ? "cssFloat" : "styleFloat"},
        style: function (e, t, n, i) {
            if (e && 3 !== e.nodeType && 8 !== e.nodeType && e.style) {
                var r, o, a, s = rt.camelCase(t), l = e.style;
                if (t = rt.cssProps[s] || (rt.cssProps[s] = C(l, s)), a = rt.cssHooks[t] || rt.cssHooks[s], void 0 === n)return a && "get"in a && void 0 !== (r = a.get(e, !1, i)) ? r : l[t];
                if (o = typeof n, "string" === o && (r = cn.exec(n)) && (n = (r[1] + 1) * r[2] + parseFloat(rt.css(e, t)), o = "number"), null != n && n === n && ("number" !== o || rt.cssNumber[s] || (n += "px"), nt.clearCloneStyle || "" !== n || 0 !== t.indexOf("background") || (l[t] = "inherit"), !(a && "set"in a && void 0 === (n = a.set(e, n, i)))))try {
                    l[t] = n
                } catch (u) {
                }
            }
        },
        css: function (e, t, n, i) {
            var r, o, a, s = rt.camelCase(t);
            return t = rt.cssProps[s] || (rt.cssProps[s] = C(e.style, s)), a = rt.cssHooks[t] || rt.cssHooks[s], a && "get"in a && (o = a.get(e, !0, n)), void 0 === o && (o = tn(e, t, i)), "normal" === o && t in pn && (o = pn[t]), "" === n || n ? (r = parseFloat(o), n === !0 || rt.isNumeric(r) ? r || 0 : o) : o
        }
    }), rt.each(["height", "width"], function (e, t) {
        rt.cssHooks[t] = {
            get: function (e, n, i) {
                return n ? ln.test(rt.css(e, "display")) && 0 === e.offsetWidth ? rt.swap(e, dn, function () {
                    return A(e, t, i)
                }) : A(e, t, i) : void 0
            }, set: function (e, n, i) {
                var r = i && en(e);
                return N(e, n, i ? O(e, t, i, nt.boxSizing && "border-box" === rt.css(e, "boxSizing", !1, r), r) : 0)
            }
        }
    }), nt.opacity || (rt.cssHooks.opacity = {
        get: function (e, t) {
            return sn.test((t && e.currentStyle ? e.currentStyle.filter : e.style.filter) || "") ? .01 * parseFloat(RegExp.$1) + "" : t ? "1" : ""
        }, set: function (e, t) {
            var n = e.style, i = e.currentStyle, r = rt.isNumeric(t) ? "alpha(opacity=" + 100 * t + ")" : "", o = i && i.filter || n.filter || "";
            n.zoom = 1, (t >= 1 || "" === t) && "" === rt.trim(o.replace(an, "")) && n.removeAttribute && (n.removeAttribute("filter"), "" === t || i && !i.filter) || (n.filter = an.test(o) ? o.replace(an, r) : o + " " + r)
        }
    }), rt.cssHooks.marginRight = R(nt.reliableMarginRight, function (e, t) {
        return t ? rt.swap(e, {display: "inline-block"}, tn, [e, "marginRight"]) : void 0
    }), rt.each({margin: "", padding: "", border: "Width"}, function (e, t) {
        rt.cssHooks[e + t] = {
            expand: function (n) {
                for (var i = 0, r = {}, o = "string" == typeof n ? n.split(" ") : [n]; 4 > i; i++)r[e + Ct[i] + t] = o[i] || o[i - 2] || o[0];
                return r
            }
        }, nn.test(e) || (rt.cssHooks[e + t].set = N)
    }), rt.fn.extend({
        css: function (e, t) {
            return Nt(this, function (e, t, n) {
                var i, r, o = {}, a = 0;
                if (rt.isArray(t)) {
                    for (i = en(e), r = t.length; r > a; a++)o[t[a]] = rt.css(e, t[a], !1, i);
                    return o
                }
                return void 0 !== n ? rt.style(e, t, n) : rt.css(e, t)
            }, e, t, arguments.length > 1)
        }, show: function () {
            return D(this, !0)
        }, hide: function () {
            return D(this)
        }, toggle: function (e) {
            return "boolean" == typeof e ? e ? this.show() : this.hide() : this.each(function () {
                Dt(this) ? rt(this).show() : rt(this).hide()
            })
        }
    }), rt.Tween = k, k.prototype = {
        constructor: k, init: function (e, t, n, i, r, o) {
            this.elem = e, this.prop = n, this.easing = r || "swing", this.options = t, this.start = this.now = this.cur(), this.end = i, this.unit = o || (rt.cssNumber[n] ? "" : "px")
        }, cur: function () {
            var e = k.propHooks[this.prop];
            return e && e.get ? e.get(this) : k.propHooks._default.get(this)
        }, run: function (e) {
            var t, n = k.propHooks[this.prop];
            return this.pos = t = this.options.duration ? rt.easing[this.easing](e, this.options.duration * e, 0, 1, this.options.duration) : e, this.now = (this.end - this.start) * t + this.start, this.options.step && this.options.step.call(this.elem, this.now, this), n && n.set ? n.set(this) : k.propHooks._default.set(this), this
        }
    }, k.prototype.init.prototype = k.prototype, k.propHooks = {
        _default: {
            get: function (e) {
                var t;
                return null == e.elem[e.prop] || e.elem.style && null != e.elem.style[e.prop] ? (t = rt.css(e.elem, e.prop, ""), t && "auto" !== t ? t : 0) : e.elem[e.prop]
            }, set: function (e) {
                rt.fx.step[e.prop] ? rt.fx.step[e.prop](e) : e.elem.style && (null != e.elem.style[rt.cssProps[e.prop]] || rt.cssHooks[e.prop]) ? rt.style(e.elem, e.prop, e.now + e.unit) : e.elem[e.prop] = e.now
            }
        }
    }, k.propHooks.scrollTop = k.propHooks.scrollLeft = {
        set: function (e) {
            e.elem.nodeType && e.elem.parentNode && (e.elem[e.prop] = e.now)
        }
    }, rt.easing = {
        linear: function (e) {
            return e
        }, swing: function (e) {
            return .5 - Math.cos(e * Math.PI) / 2
        }
    }, rt.fx = k.prototype.init, rt.fx.step = {};
    var hn, mn, gn = /^(?:toggle|show|hide)$/, vn = new RegExp("^(?:([+-])=|)(" + Rt + ")([a-z%]*)$", "i"), yn = /queueHooks$/, bn = [M], xn = {
        "*": [function (e, t) {
            var n = this.createTween(e, t), i = n.cur(), r = vn.exec(t), o = r && r[3] || (rt.cssNumber[e] ? "" : "px"), a = (rt.cssNumber[e] || "px" !== o && +i) && vn.exec(rt.css(n.elem, e)), s = 1, l = 20;
            if (a && a[3] !== o) {
                o = o || a[3], r = r || [], a = +i || 1;
                do s = s || ".5", a /= s, rt.style(n.elem, e, a + o); while (s !== (s = n.cur() / i) && 1 !== s && --l)
            }
            return r && (a = n.start = +a || +i || 0, n.unit = o, n.end = r[1] ? a + (r[1] + 1) * r[2] : +r[2]), n
        }]
    };
    rt.Animation = rt.extend(P, {
        tweener: function (e, t) {
            rt.isFunction(e) ? (t = e, e = ["*"]) : e = e.split(" ");
            for (var n, i = 0, r = e.length; r > i; i++)n = e[i], xn[n] = xn[n] || [], xn[n].unshift(t)
        }, prefilter: function (e, t) {
            t ? bn.unshift(e) : bn.push(e)
        }
    }), rt.speed = function (e, t, n) {
        var i = e && "object" == typeof e ? rt.extend({}, e) : {
            complete: n || !n && t || rt.isFunction(e) && e,
            duration: e,
            easing: n && t || t && !rt.isFunction(t) && t
        };
        return i.duration = rt.fx.off ? 0 : "number" == typeof i.duration ? i.duration : i.duration in rt.fx.speeds ? rt.fx.speeds[i.duration] : rt.fx.speeds._default, (null == i.queue || i.queue === !0) && (i.queue = "fx"), i.old = i.complete, i.complete = function () {
            rt.isFunction(i.old) && i.old.call(this), i.queue && rt.dequeue(this, i.queue)
        }, i
    }, rt.fn.extend({
        fadeTo: function (e, t, n, i) {
            return this.filter(Dt).css("opacity", 0).show().end().animate({opacity: t}, e, n, i)
        }, animate: function (e, t, n, i) {
            var r = rt.isEmptyObject(e), o = rt.speed(t, n, i), a = function () {
                var t = P(this, rt.extend({}, e), o);
                (r || rt._data(this, "finish")) && t.stop(!0)
            };
            return a.finish = a, r || o.queue === !1 ? this.each(a) : this.queue(o.queue, a)
        }, stop: function (e, t, n) {
            var i = function (e) {
                var t = e.stop;
                delete e.stop, t(n)
            };
            return "string" != typeof e && (n = t, t = e, e = void 0), t && e !== !1 && this.queue(e || "fx", []), this.each(function () {
                var t = !0, r = null != e && e + "queueHooks", o = rt.timers, a = rt._data(this);
                if (r)a[r] && a[r].stop && i(a[r]); else for (r in a)a[r] && a[r].stop && yn.test(r) && i(a[r]);
                for (r = o.length; r--;)o[r].elem !== this || null != e && o[r].queue !== e || (o[r].anim.stop(n), t = !1, o.splice(r, 1));
                (t || !n) && rt.dequeue(this, e)
            })
        }, finish: function (e) {
            return e !== !1 && (e = e || "fx"), this.each(function () {
                var t, n = rt._data(this), i = n[e + "queue"], r = n[e + "queueHooks"], o = rt.timers, a = i ? i.length : 0;
                for (n.finish = !0, rt.queue(this, e, []), r && r.stop && r.stop.call(this, !0), t = o.length; t--;)o[t].elem === this && o[t].queue === e && (o[t].anim.stop(!0), o.splice(t, 1));
                for (t = 0; a > t; t++)i[t] && i[t].finish && i[t].finish.call(this);
                delete n.finish
            })
        }
    }), rt.each(["toggle", "show", "hide"], function (e, t) {
        var n = rt.fn[t];
        rt.fn[t] = function (e, i, r) {
            return null == e || "boolean" == typeof e ? n.apply(this, arguments) : this.animate(F(t, !0), e, i, r)
        }
    }), rt.each({
        slideDown: F("show"),
        slideUp: F("hide"),
        slideToggle: F("toggle"),
        fadeIn: {opacity: "show"},
        fadeOut: {opacity: "hide"},
        fadeToggle: {opacity: "toggle"}
    }, function (e, t) {
        rt.fn[e] = function (e, n, i) {
            return this.animate(t, e, n, i)
        }
    }), rt.timers = [], rt.fx.tick = function () {
        var e, t = rt.timers, n = 0;
        for (hn = rt.now(); n < t.length; n++)e = t[n], e() || t[n] !== e || t.splice(n--, 1);
        t.length || rt.fx.stop(), hn = void 0
    }, rt.fx.timer = function (e) {
        rt.timers.push(e), e() ? rt.fx.start() : rt.timers.pop()
    }, rt.fx.interval = 13, rt.fx.start = function () {
        mn || (mn = setInterval(rt.fx.tick, rt.fx.interval))
    }, rt.fx.stop = function () {
        clearInterval(mn), mn = null
    }, rt.fx.speeds = {slow: 600, fast: 200, _default: 400}, rt.fn.delay = function (e, t) {
        return e = rt.fx ? rt.fx.speeds[e] || e : e, t = t || "fx", this.queue(t, function (t, n) {
            var i = setTimeout(t, e);
            n.stop = function () {
                clearTimeout(i)
            }
        })
    }, function () {
        var e, t, n, i, r;
        t = ht.createElement("div"), t.setAttribute("className", "t"), t.innerHTML = "  <link/><table></table><a href='/a'>a</a><input type='checkbox'/>", i = t.getElementsByTagName("a")[0], n = ht.createElement("select"), r = n.appendChild(ht.createElement("option")), e = t.getElementsByTagName("input")[0], i.style.cssText = "top:1px", nt.getSetAttribute = "t" !== t.className, nt.style = /top/.test(i.getAttribute("style")), nt.hrefNormalized = "/a" === i.getAttribute("href"), nt.checkOn = !!e.value, nt.optSelected = r.selected, nt.enctype = !!ht.createElement("form").enctype, n.disabled = !0, nt.optDisabled = !r.disabled, e = ht.createElement("input"), e.setAttribute("value", ""), nt.input = "" === e.getAttribute("value"), e.value = "t", e.setAttribute("type", "radio"), nt.radioValue = "t" === e.value
    }();
    var wn = /\r/g;
    rt.fn.extend({
        val: function (e) {
            var t, n, i, r = this[0];
            {
                if (arguments.length)return i = rt.isFunction(e), this.each(function (n) {
                    var r;
                    1 === this.nodeType && (r = i ? e.call(this, n, rt(this).val()) : e, null == r ? r = "" : "number" == typeof r ? r += "" : rt.isArray(r) && (r = rt.map(r, function (e) {
                        return null == e ? "" : e + ""
                    })), t = rt.valHooks[this.type] || rt.valHooks[this.nodeName.toLowerCase()], t && "set"in t && void 0 !== t.set(this, r, "value") || (this.value = r))
                });
                if (r)return t = rt.valHooks[r.type] || rt.valHooks[r.nodeName.toLowerCase()], t && "get"in t && void 0 !== (n = t.get(r, "value")) ? n : (n = r.value, "string" == typeof n ? n.replace(wn, "") : null == n ? "" : n)
            }
        }
    }), rt.extend({
        valHooks: {
            option: {
                get: function (e) {
                    var t = rt.find.attr(e, "value");
                    return null != t ? t : rt.trim(rt.text(e))
                }
            }, select: {
                get: function (e) {
                    for (var t, n, i = e.options, r = e.selectedIndex, o = "select-one" === e.type || 0 > r, a = o ? null : [], s = o ? r + 1 : i.length, l = 0 > r ? s : o ? r : 0; s > l; l++)if (n = i[l], !(!n.selected && l !== r || (nt.optDisabled ? n.disabled : null !== n.getAttribute("disabled")) || n.parentNode.disabled && rt.nodeName(n.parentNode, "optgroup"))) {
                        if (t = rt(n).val(), o)return t;
                        a.push(t)
                    }
                    return a
                }, set: function (e, t) {
                    for (var n, i, r = e.options, o = rt.makeArray(t), a = r.length; a--;)if (i = r[a], rt.inArray(rt.valHooks.option.get(i), o) >= 0)try {
                        i.selected = n = !0
                    } catch (s) {
                        i.scrollHeight
                    } else i.selected = !1;
                    return n || (e.selectedIndex = -1), r
                }
            }
        }
    }), rt.each(["radio", "checkbox"], function () {
        rt.valHooks[this] = {
            set: function (e, t) {
                return rt.isArray(t) ? e.checked = rt.inArray(rt(e).val(), t) >= 0 : void 0
            }
        }, nt.checkOn || (rt.valHooks[this].get = function (e) {
            return null === e.getAttribute("value") ? "on" : e.value
        })
    });
    var En, Tn, Sn = rt.expr.attrHandle, _n = /^(?:checked|selected)$/i, Rn = nt.getSetAttribute, Cn = nt.input;
    rt.fn.extend({
        attr: function (e, t) {
            return Nt(this, rt.attr, e, t, arguments.length > 1)
        }, removeAttr: function (e) {
            return this.each(function () {
                rt.removeAttr(this, e)
            })
        }
    }), rt.extend({
        attr: function (e, t, n) {
            var i, r, o = e.nodeType;
            if (e && 3 !== o && 8 !== o && 2 !== o)return typeof e.getAttribute === Tt ? rt.prop(e, t, n) : (1 === o && rt.isXMLDoc(e) || (t = t.toLowerCase(), i = rt.attrHooks[t] || (rt.expr.match.bool.test(t) ? Tn : En)), void 0 === n ? i && "get"in i && null !== (r = i.get(e, t)) ? r : (r = rt.find.attr(e, t), null == r ? void 0 : r) : null !== n ? i && "set"in i && void 0 !== (r = i.set(e, n, t)) ? r : (e.setAttribute(t, n + ""), n) : void rt.removeAttr(e, t))
        }, removeAttr: function (e, t) {
            var n, i, r = 0, o = t && t.match(bt);
            if (o && 1 === e.nodeType)for (; n = o[r++];)i = rt.propFix[n] || n, rt.expr.match.bool.test(n) ? Cn && Rn || !_n.test(n) ? e[i] = !1 : e[rt.camelCase("default-" + n)] = e[i] = !1 : rt.attr(e, n, ""), e.removeAttribute(Rn ? n : i)
        }, attrHooks: {
            type: {
                set: function (e, t) {
                    if (!nt.radioValue && "radio" === t && rt.nodeName(e, "input")) {
                        var n = e.value;
                        return e.setAttribute("type", t), n && (e.value = n), t
                    }
                }
            }
        }
    }), Tn = {
        set: function (e, t, n) {
            return t === !1 ? rt.removeAttr(e, n) : Cn && Rn || !_n.test(n) ? e.setAttribute(!Rn && rt.propFix[n] || n, n) : e[rt.camelCase("default-" + n)] = e[n] = !0, n
        }
    }, rt.each(rt.expr.match.bool.source.match(/\w+/g), function (e, t) {
        var n = Sn[t] || rt.find.attr;
        Sn[t] = Cn && Rn || !_n.test(t) ? function (e, t, i) {
            var r, o;
            return i || (o = Sn[t], Sn[t] = r, r = null != n(e, t, i) ? t.toLowerCase() : null, Sn[t] = o), r
        } : function (e, t, n) {
            return n ? void 0 : e[rt.camelCase("default-" + t)] ? t.toLowerCase() : null
        }
    }), Cn && Rn || (rt.attrHooks.value = {
        set: function (e, t, n) {
            return rt.nodeName(e, "input") ? void(e.defaultValue = t) : En && En.set(e, t, n)
        }
    }), Rn || (En = {
        set: function (e, t, n) {
            var i = e.getAttributeNode(n);
            return i || e.setAttributeNode(i = e.ownerDocument.createAttribute(n)), i.value = t += "", "value" === n || t === e.getAttribute(n) ? t : void 0
        }
    }, Sn.id = Sn.name = Sn.coords = function (e, t, n) {
        var i;
        return n ? void 0 : (i = e.getAttributeNode(t)) && "" !== i.value ? i.value : null
    }, rt.valHooks.button = {
        get: function (e, t) {
            var n = e.getAttributeNode(t);
            return n && n.specified ? n.value : void 0
        }, set: En.set
    }, rt.attrHooks.contenteditable = {
        set: function (e, t, n) {
            En.set(e, "" === t ? !1 : t, n)
        }
    }, rt.each(["width", "height"], function (e, t) {
        rt.attrHooks[t] = {
            set: function (e, n) {
                return "" === n ? (e.setAttribute(t, "auto"), n) : void 0
            }
        }
    })), nt.style || (rt.attrHooks.style = {
        get: function (e) {
            return e.style.cssText || void 0
        }, set: function (e, t) {
            return e.style.cssText = t + ""
        }
    });
    var Dn = /^(?:input|select|textarea|button|object)$/i, Nn = /^(?:a|area)$/i;
    rt.fn.extend({
        prop: function (e, t) {
            return Nt(this, rt.prop, e, t, arguments.length > 1)
        }, removeProp: function (e) {
            return e = rt.propFix[e] || e, this.each(function () {
                try {
                    this[e] = void 0, delete this[e]
                } catch (t) {
                }
            })
        }
    }), rt.extend({
        propFix: {"for": "htmlFor", "class": "className"}, prop: function (e, t, n) {
            var i, r, o, a = e.nodeType;
            if (e && 3 !== a && 8 !== a && 2 !== a)return o = 1 !== a || !rt.isXMLDoc(e), o && (t = rt.propFix[t] || t, r = rt.propHooks[t]), void 0 !== n ? r && "set"in r && void 0 !== (i = r.set(e, n, t)) ? i : e[t] = n : r && "get"in r && null !== (i = r.get(e, t)) ? i : e[t]
        }, propHooks: {
            tabIndex: {
                get: function (e) {
                    var t = rt.find.attr(e, "tabindex");
                    return t ? parseInt(t, 10) : Dn.test(e.nodeName) || Nn.test(e.nodeName) && e.href ? 0 : -1
                }
            }
        }
    }), nt.hrefNormalized || rt.each(["href", "src"], function (e, t) {
        rt.propHooks[t] = {
            get: function (e) {
                return e.getAttribute(t, 4)
            }
        }
    }), nt.optSelected || (rt.propHooks.selected = {
        get: function (e) {
            var t = e.parentNode;
            return t && (t.selectedIndex, t.parentNode && t.parentNode.selectedIndex), null
        }
    }), rt.each(["tabIndex", "readOnly", "maxLength", "cellSpacing", "cellPadding", "rowSpan", "colSpan", "useMap", "frameBorder", "contentEditable"], function () {
        rt.propFix[this.toLowerCase()] = this
    }), nt.enctype || (rt.propFix.enctype = "encoding");
    var On = /[\t\r\n\f]/g;
    rt.fn.extend({
        addClass: function (e) {
            var t, n, i, r, o, a, s = 0, l = this.length, u = "string" == typeof e && e;
            if (rt.isFunction(e))return this.each(function (t) {
                rt(this).addClass(e.call(this, t, this.className))
            });
            if (u)for (t = (e || "").match(bt) || []; l > s; s++)if (n = this[s], i = 1 === n.nodeType && (n.className ? (" " + n.className + " ").replace(On, " ") : " ")) {
                for (o = 0; r = t[o++];)i.indexOf(" " + r + " ") < 0 && (i += r + " ");
                a = rt.trim(i), n.className !== a && (n.className = a)
            }
            return this
        }, removeClass: function (e) {
            var t, n, i, r, o, a, s = 0, l = this.length, u = 0 === arguments.length || "string" == typeof e && e;
            if (rt.isFunction(e))return this.each(function (t) {
                rt(this).removeClass(e.call(this, t, this.className))
            });
            if (u)for (t = (e || "").match(bt) || []; l > s; s++)if (n = this[s], i = 1 === n.nodeType && (n.className ? (" " + n.className + " ").replace(On, " ") : "")) {
                for (o = 0; r = t[o++];)for (; i.indexOf(" " + r + " ") >= 0;)i = i.replace(" " + r + " ", " ");
                a = e ? rt.trim(i) : "", n.className !== a && (n.className = a)
            }
            return this
        }, toggleClass: function (e, t) {
            var n = typeof e;
            return "boolean" == typeof t && "string" === n ? t ? this.addClass(e) : this.removeClass(e) : this.each(rt.isFunction(e) ? function (n) {
                rt(this).toggleClass(e.call(this, n, this.className, t), t)
            } : function () {
                if ("string" === n)for (var t, i = 0, r = rt(this), o = e.match(bt) || []; t = o[i++];)r.hasClass(t) ? r.removeClass(t) : r.addClass(t); else(n === Tt || "boolean" === n) && (this.className && rt._data(this, "__className__", this.className), this.className = this.className || e === !1 ? "" : rt._data(this, "__className__") || "")
            })
        }, hasClass: function (e) {
            for (var t = " " + e + " ", n = 0, i = this.length; i > n; n++)if (1 === this[n].nodeType && (" " + this[n].className + " ").replace(On, " ").indexOf(t) >= 0)return !0;
            return !1
        }
    }), rt.each("blur focus focusin focusout load resize scroll unload click dblclick mousedown mouseup mousemove mouseover mouseout mouseenter mouseleave change select submit keydown keypress keyup error contextmenu".split(" "), function (e, t) {
        rt.fn[t] = function (e, n) {
            return arguments.length > 0 ? this.on(t, null, e, n) : this.trigger(t)
        }
    }), rt.fn.extend({
        hover: function (e, t) {
            return this.mouseenter(e).mouseleave(t || e)
        }, bind: function (e, t, n) {
            return this.on(e, null, t, n)
        }, unbind: function (e, t) {
            return this.off(e, null, t)
        }, delegate: function (e, t, n, i) {
            return this.on(t, e, n, i)
        }, undelegate: function (e, t, n) {
            return 1 === arguments.length ? this.off(e, "**") : this.off(t, e || "**", n)
        }
    });
    var An = rt.now(), kn = /\?/, In = /(,)|(\[|{)|(}|])|"(?:[^"\\\r\n]|\\["\\\/bfnrt]|\\u[\da-fA-F]{4})*"\s*:?|true|false|null|-?(?!0\d)\d+(?:\.\d+|)(?:[eE][+-]?\d+|)/g;
    rt.parseJSON = function (t) {
        if (e.JSON && e.JSON.parse)return e.JSON.parse(t + "");
        var n, i = null, r = rt.trim(t + "");
        return r && !rt.trim(r.replace(In, function (e, t, r, o) {
            return n && t && (i = 0), 0 === i ? e : (n = r || t, i += !o - !r, "")
        })) ? Function("return " + r)() : rt.error("Invalid JSON: " + t)
    }, rt.parseXML = function (t) {
        var n, i;
        if (!t || "string" != typeof t)return null;
        try {
            e.DOMParser ? (i = new DOMParser, n = i.parseFromString(t, "text/xml")) : (n = new ActiveXObject("Microsoft.XMLDOM"), n.async = "false", n.loadXML(t))
        } catch (r) {
            n = void 0
        }
        return n && n.documentElement && !n.getElementsByTagName("parsererror").length || rt.error("Invalid XML: " + t), n
    };
    var Fn, Ln, Mn = /#.*$/, Hn = /([?&])_=[^&]*/, Pn = /^(.*?):[ \t]*([^\r\n]*)\r?$/gm, jn = /^(?:about|app|app-storage|.+-extension|file|res|widget):$/, Bn = /^(?:GET|HEAD)$/, $n = /^\/\//, zn = /^([\w.+-]+:)(?:\/\/(?:[^\/?#]*@|)([^\/?#:]*)(?::(\d+)|)|)/, qn = {}, Wn = {}, Un = "*/".concat("*");
    try {
        Ln = location.href
    } catch (Vn) {
        Ln = ht.createElement("a"), Ln.href = "", Ln = Ln.href
    }
    Fn = zn.exec(Ln.toLowerCase()) || [], rt.extend({
        active: 0,
        lastModified: {},
        etag: {},
        ajaxSettings: {
            url: Ln,
            type: "GET",
            isLocal: jn.test(Fn[1]),
            global: !0,
            processData: !0,
            async: !0,
            contentType: "application/x-www-form-urlencoded; charset=UTF-8",
            accepts: {
                "*": Un,
                text: "text/plain",
                html: "text/html",
                xml: "application/xml, text/xml",
                json: "application/json, text/javascript"
            },
            contents: {xml: /xml/, html: /html/, json: /json/},
            responseFields: {xml: "responseXML", text: "responseText", json: "responseJSON"},
            converters: {"* text": String, "text html": !0, "text json": rt.parseJSON, "text xml": rt.parseXML},
            flatOptions: {url: !0, context: !0}
        },
        ajaxSetup: function (e, t) {
            return t ? $($(e, rt.ajaxSettings), t) : $(rt.ajaxSettings, e)
        },
        ajaxPrefilter: j(qn),
        ajaxTransport: j(Wn),
        ajax: function (e, t) {
            function n(e, t, n, i) {
                var r, c, v, y, x, E = t;
                2 !== b && (b = 2, s && clearTimeout(s), u = void 0, a = i || "", w.readyState = e > 0 ? 4 : 0, r = e >= 200 && 300 > e || 304 === e, n && (y = z(d, w, n)), y = q(d, y, w, r), r ? (d.ifModified && (x = w.getResponseHeader("Last-Modified"), x && (rt.lastModified[o] = x), x = w.getResponseHeader("etag"), x && (rt.etag[o] = x)), 204 === e || "HEAD" === d.type ? E = "nocontent" : 304 === e ? E = "notmodified" : (E = y.state, c = y.data, v = y.error, r = !v)) : (v = E, (e || !E) && (E = "error", 0 > e && (e = 0))), w.status = e, w.statusText = (t || E) + "", r ? h.resolveWith(p, [c, E, w]) : h.rejectWith(p, [w, E, v]), w.statusCode(g), g = void 0, l && f.trigger(r ? "ajaxSuccess" : "ajaxError", [w, d, r ? c : v]), m.fireWith(p, [w, E]), l && (f.trigger("ajaxComplete", [w, d]), --rt.active || rt.event.trigger("ajaxStop")))
            }

            "object" == typeof e && (t = e, e = void 0), t = t || {};
            var i, r, o, a, s, l, u, c, d = rt.ajaxSetup({}, t), p = d.context || d, f = d.context && (p.nodeType || p.jquery) ? rt(p) : rt.event, h = rt.Deferred(), m = rt.Callbacks("once memory"), g = d.statusCode || {}, v = {}, y = {}, b = 0, x = "canceled", w = {
                readyState: 0,
                getResponseHeader: function (e) {
                    var t;
                    if (2 === b) {
                        if (!c)for (c = {}; t = Pn.exec(a);)c[t[1].toLowerCase()] = t[2];
                        t = c[e.toLowerCase()]
                    }
                    return null == t ? null : t
                },
                getAllResponseHeaders: function () {
                    return 2 === b ? a : null
                },
                setRequestHeader: function (e, t) {
                    var n = e.toLowerCase();
                    return b || (e = y[n] = y[n] || e, v[e] = t), this
                },
                overrideMimeType: function (e) {
                    return b || (d.mimeType = e), this
                },
                statusCode: function (e) {
                    var t;
                    if (e)if (2 > b)for (t in e)g[t] = [g[t], e[t]]; else w.always(e[w.status]);
                    return this
                },
                abort: function (e) {
                    var t = e || x;
                    return u && u.abort(t), n(0, t), this
                }
            };
            if (h.promise(w).complete = m.add, w.success = w.done, w.error = w.fail, d.url = ((e || d.url || Ln) + "").replace(Mn, "").replace($n, Fn[1] + "//"), d.type = t.method || t.type || d.method || d.type, d.dataTypes = rt.trim(d.dataType || "*").toLowerCase().match(bt) || [""], null == d.crossDomain && (i = zn.exec(d.url.toLowerCase()), d.crossDomain = !(!i || i[1] === Fn[1] && i[2] === Fn[2] && (i[3] || ("http:" === i[1] ? "80" : "443")) === (Fn[3] || ("http:" === Fn[1] ? "80" : "443")))), d.data && d.processData && "string" != typeof d.data && (d.data = rt.param(d.data, d.traditional)), B(qn, d, t, w), 2 === b)return w;
            l = d.global, l && 0 === rt.active++ && rt.event.trigger("ajaxStart"), d.type = d.type.toUpperCase(), d.hasContent = !Bn.test(d.type), o = d.url, d.hasContent || (d.data && (o = d.url += (kn.test(o) ? "&" : "?") + d.data, delete d.data), d.cache === !1 && (d.url = Hn.test(o) ? o.replace(Hn, "$1_=" + An++) : o + (kn.test(o) ? "&" : "?") + "_=" + An++)), d.ifModified && (rt.lastModified[o] && w.setRequestHeader("If-Modified-Since", rt.lastModified[o]), rt.etag[o] && w.setRequestHeader("If-None-Match", rt.etag[o])), (d.data && d.hasContent && d.contentType !== !1 || t.contentType) && w.setRequestHeader("Content-Type", d.contentType), w.setRequestHeader("Accept", d.dataTypes[0] && d.accepts[d.dataTypes[0]] ? d.accepts[d.dataTypes[0]] + ("*" !== d.dataTypes[0] ? ", " + Un + "; q=0.01" : "") : d.accepts["*"]);
            for (r in d.headers)w.setRequestHeader(r, d.headers[r]);
            if (d.beforeSend && (d.beforeSend.call(p, w, d) === !1 || 2 === b))return w.abort();
            x = "abort";
            for (r in{success: 1, error: 1, complete: 1})w[r](d[r]);
            if (u = B(Wn, d, t, w)) {
                w.readyState = 1, l && f.trigger("ajaxSend", [w, d]), d.async && d.timeout > 0 && (s = setTimeout(function () {
                    w.abort("timeout")
                }, d.timeout));
                try {
                    b = 1, u.send(v, n)
                } catch (E) {
                    if (!(2 > b))throw E;
                    n(-1, E)
                }
            } else n(-1, "No Transport");
            return w
        },
        getJSON: function (e, t, n) {
            return rt.get(e, t, n, "json")
        },
        getScript: function (e, t) {
            return rt.get(e, void 0, t, "script")
        }
    }), rt.each(["get", "post"], function (e, t) {
        rt[t] = function (e, n, i, r) {
            return rt.isFunction(n) && (r = r || i, i = n, n = void 0), rt.ajax({
                url: e,
                type: t,
                dataType: r,
                data: n,
                success: i
            })
        }
    }), rt.each(["ajaxStart", "ajaxStop", "ajaxComplete", "ajaxError", "ajaxSuccess", "ajaxSend"], function (e, t) {
        rt.fn[t] = function (e) {
            return this.on(t, e)
        }
    }), rt._evalUrl = function (e) {
        return rt.ajax({url: e, type: "GET", dataType: "script", async: !1, global: !1, "throws": !0})
    }, rt.fn.extend({
        wrapAll: function (e) {
            if (rt.isFunction(e))return this.each(function (t) {
                rt(this).wrapAll(e.call(this, t))
            });
            if (this[0]) {
                var t = rt(e, this[0].ownerDocument).eq(0).clone(!0);
                this[0].parentNode && t.insertBefore(this[0]), t.map(function () {
                    for (var e = this; e.firstChild && 1 === e.firstChild.nodeType;)e = e.firstChild;
                    return e
                }).append(this)
            }
            return this
        }, wrapInner: function (e) {
            return this.each(rt.isFunction(e) ? function (t) {
                rt(this).wrapInner(e.call(this, t))
            } : function () {
                var t = rt(this), n = t.contents();
                n.length ? n.wrapAll(e) : t.append(e)
            })
        }, wrap: function (e) {
            var t = rt.isFunction(e);
            return this.each(function (n) {
                rt(this).wrapAll(t ? e.call(this, n) : e)
            })
        }, unwrap: function () {
            return this.parent().each(function () {
                rt.nodeName(this, "body") || rt(this).replaceWith(this.childNodes)
            }).end()
        }
    }), rt.expr.filters.hidden = function (e) {
        return e.offsetWidth <= 0 && e.offsetHeight <= 0 || !nt.reliableHiddenOffsets() && "none" === (e.style && e.style.display || rt.css(e, "display"))
    }, rt.expr.filters.visible = function (e) {
        return !rt.expr.filters.hidden(e)
    };
    var Gn = /%20/g, Xn = /\[\]$/, Jn = /\r?\n/g, Yn = /^(?:submit|button|image|reset|file)$/i, Qn = /^(?:input|select|textarea|keygen)/i;
    rt.param = function (e, t) {
        var n, i = [], r = function (e, t) {
            t = rt.isFunction(t) ? t() : null == t ? "" : t, i[i.length] = encodeURIComponent(e) + "=" + encodeURIComponent(t)
        };
        if (void 0 === t && (t = rt.ajaxSettings && rt.ajaxSettings.traditional), rt.isArray(e) || e.jquery && !rt.isPlainObject(e))rt.each(e, function () {
            r(this.name, this.value)
        }); else for (n in e)W(n, e[n], t, r);
        return i.join("&").replace(Gn, "+")
    }, rt.fn.extend({
        serialize: function () {
            return rt.param(this.serializeArray())
        }, serializeArray: function () {
            return this.map(function () {
                var e = rt.prop(this, "elements");
                return e ? rt.makeArray(e) : this
            }).filter(function () {
                var e = this.type;
                return this.name && !rt(this).is(":disabled") && Qn.test(this.nodeName) && !Yn.test(e) && (this.checked || !Ot.test(e))
            }).map(function (e, t) {
                var n = rt(this).val();
                return null == n ? null : rt.isArray(n) ? rt.map(n, function (e) {
                    return {name: t.name, value: e.replace(Jn, "\r\n")}
                }) : {name: t.name, value: n.replace(Jn, "\r\n")}
            }).get()
        }
    }), rt.ajaxSettings.xhr = void 0 !== e.ActiveXObject ? function () {
        return !this.isLocal && /^(get|post|head|put|delete|options)$/i.test(this.type) && U() || V()
    } : U;
    var Kn = 0, Zn = {}, ei = rt.ajaxSettings.xhr();
    e.ActiveXObject && rt(e).on("unload", function () {
        for (var e in Zn)Zn[e](void 0, !0)
    }), nt.cors = !!ei && "withCredentials"in ei, ei = nt.ajax = !!ei, ei && rt.ajaxTransport(function (e) {
        if (!e.crossDomain || nt.cors) {
            var t;
            return {
                send: function (n, i) {
                    var r, o = e.xhr(), a = ++Kn;
                    if (o.open(e.type, e.url, e.async, e.username, e.password), e.xhrFields)for (r in e.xhrFields)o[r] = e.xhrFields[r];
                    e.mimeType && o.overrideMimeType && o.overrideMimeType(e.mimeType), e.crossDomain || n["X-Requested-With"] || (n["X-Requested-With"] = "XMLHttpRequest");
                    for (r in n)void 0 !== n[r] && o.setRequestHeader(r, n[r] + "");
                    o.send(e.hasContent && e.data || null), t = function (n, r) {
                        var s, l, u;
                        if (t && (r || 4 === o.readyState))if (delete Zn[a], t = void 0, o.onreadystatechange = rt.noop, r)4 !== o.readyState && o.abort(); else {
                            u = {}, s = o.status, "string" == typeof o.responseText && (u.text = o.responseText);
                            try {
                                l = o.statusText
                            } catch (c) {
                                l = ""
                            }
                            s || !e.isLocal || e.crossDomain ? 1223 === s && (s = 204) : s = u.text ? 200 : 404
                        }
                        u && i(s, l, u, o.getAllResponseHeaders())
                    }, e.async ? 4 === o.readyState ? setTimeout(t) : o.onreadystatechange = Zn[a] = t : t()
                }, abort: function () {
                    t && t(void 0, !0)
                }
            }
        }
    }), rt.ajaxSetup({
        accepts: {script: "text/javascript, application/javascript, application/ecmascript, application/x-ecmascript"},
        contents: {script: /(?:java|ecma)script/},
        converters: {
            "text script": function (e) {
                return rt.globalEval(e), e
            }
        }
    }), rt.ajaxPrefilter("script", function (e) {
        void 0 === e.cache && (e.cache = !1), e.crossDomain && (e.type = "GET", e.global = !1)
    }), rt.ajaxTransport("script", function (e) {
        if (e.crossDomain) {
            var t, n = ht.head || rt("head")[0] || ht.documentElement;
            return {
                send: function (i, r) {
                    t = ht.createElement("script"), t.async = !0, e.scriptCharset && (t.charset = e.scriptCharset), t.src = e.url, t.onload = t.onreadystatechange = function (e, n) {
                        (n || !t.readyState || /loaded|complete/.test(t.readyState)) && (t.onload = t.onreadystatechange = null, t.parentNode && t.parentNode.removeChild(t), t = null, n || r(200, "success"))
                    }, n.insertBefore(t, n.firstChild)
                }, abort: function () {
                    t && t.onload(void 0, !0)
                }
            }
        }
    });
    var ti = [], ni = /(=)\?(?=&|$)|\?\?/;
    rt.ajaxSetup({
        jsonp: "callback", jsonpCallback: function () {
            var e = ti.pop() || rt.expando + "_" + An++;
            return this[e] = !0, e
        }
    }), rt.ajaxPrefilter("json jsonp", function (t, n, i) {
        var r, o, a, s = t.jsonp !== !1 && (ni.test(t.url) ? "url" : "string" == typeof t.data && !(t.contentType || "").indexOf("application/x-www-form-urlencoded") && ni.test(t.data) && "data");
        return s || "jsonp" === t.dataTypes[0] ? (r = t.jsonpCallback = rt.isFunction(t.jsonpCallback) ? t.jsonpCallback() : t.jsonpCallback, s ? t[s] = t[s].replace(ni, "$1" + r) : t.jsonp !== !1 && (t.url += (kn.test(t.url) ? "&" : "?") + t.jsonp + "=" + r), t.converters["script json"] = function () {
            return a || rt.error(r + " was not called"), a[0]
        }, t.dataTypes[0] = "json", o = e[r], e[r] = function () {
            a = arguments
        }, i.always(function () {
            e[r] = o, t[r] && (t.jsonpCallback = n.jsonpCallback, ti.push(r)), a && rt.isFunction(o) && o(a[0]), a = o = void 0
        }), "script") : void 0
    }), rt.parseHTML = function (e, t, n) {
        if (!e || "string" != typeof e)return null;
        "boolean" == typeof t && (n = t, t = !1), t = t || ht;
        var i = dt.exec(e), r = !n && [];
        return i ? [t.createElement(i[1])] : (i = rt.buildFragment([e], t, r), r && r.length && rt(r).remove(), rt.merge([], i.childNodes))
    };
    var ii = rt.fn.load;
    rt.fn.load = function (e, t, n) {
        if ("string" != typeof e && ii)return ii.apply(this, arguments);
        var i, r, o, a = this, s = e.indexOf(" ");
        return s >= 0 && (i = rt.trim(e.slice(s, e.length)), e = e.slice(0, s)), rt.isFunction(t) ? (n = t, t = void 0) : t && "object" == typeof t && (o = "POST"), a.length > 0 && rt.ajax({
            url: e,
            type: o,
            dataType: "html",
            data: t
        }).done(function (e) {
            r = arguments, a.html(i ? rt("<div>").append(rt.parseHTML(e)).find(i) : e)
        }).complete(n && function (e, t) {
            a.each(n, r || [e.responseText, t, e])
        }), this
    }, rt.expr.filters.animated = function (e) {
        return rt.grep(rt.timers, function (t) {
            return e === t.elem
        }).length
    };
    var ri = e.document.documentElement;
    rt.offset = {
        setOffset: function (e, t, n) {
            var i, r, o, a, s, l, u, c = rt.css(e, "position"), d = rt(e), p = {};
            "static" === c && (e.style.position = "relative"), s = d.offset(), o = rt.css(e, "top"), l = rt.css(e, "left"), u = ("absolute" === c || "fixed" === c) && rt.inArray("auto", [o, l]) > -1, u ? (i = d.position(), a = i.top, r = i.left) : (a = parseFloat(o) || 0, r = parseFloat(l) || 0), rt.isFunction(t) && (t = t.call(e, n, s)), null != t.top && (p.top = t.top - s.top + a), null != t.left && (p.left = t.left - s.left + r), "using"in t ? t.using.call(e, p) : d.css(p)
        }
    }, rt.fn.extend({
        offset: function (e) {
            if (arguments.length)return void 0 === e ? this : this.each(function (t) {
                rt.offset.setOffset(this, e, t)
            });
            var t, n, i = {top: 0, left: 0}, r = this[0], o = r && r.ownerDocument;
            if (o)return t = o.documentElement, rt.contains(t, r) ? (typeof r.getBoundingClientRect !== Tt && (i = r.getBoundingClientRect()), n = G(o), {
                top: i.top + (n.pageYOffset || t.scrollTop) - (t.clientTop || 0),
                left: i.left + (n.pageXOffset || t.scrollLeft) - (t.clientLeft || 0)
            }) : i
        }, position: function () {
            if (this[0]) {
                var e, t, n = {top: 0, left: 0}, i = this[0];
                return "fixed" === rt.css(i, "position") ? t = i.getBoundingClientRect() : (e = this.offsetParent(), t = this.offset(), rt.nodeName(e[0], "html") || (n = e.offset()), n.top += rt.css(e[0], "borderTopWidth", !0), n.left += rt.css(e[0], "borderLeftWidth", !0)), {
                    top: t.top - n.top - rt.css(i, "marginTop", !0),
                    left: t.left - n.left - rt.css(i, "marginLeft", !0)
                }
            }
        }, offsetParent: function () {
            return this.map(function () {
                for (var e = this.offsetParent || ri; e && !rt.nodeName(e, "html") && "static" === rt.css(e, "position");)e = e.offsetParent;
                return e || ri
            })
        }
    }), rt.each({scrollLeft: "pageXOffset", scrollTop: "pageYOffset"}, function (e, t) {
        var n = /Y/.test(t);
        rt.fn[e] = function (i) {
            return Nt(this, function (e, i, r) {
                var o = G(e);
                return void 0 === r ? o ? t in o ? o[t] : o.document.documentElement[i] : e[i] : void(o ? o.scrollTo(n ? rt(o).scrollLeft() : r, n ? r : rt(o).scrollTop()) : e[i] = r)
            }, e, i, arguments.length, null)
        }
    }), rt.each(["top", "left"], function (e, t) {
        rt.cssHooks[t] = R(nt.pixelPosition, function (e, n) {
            return n ? (n = tn(e, t), rn.test(n) ? rt(e).position()[t] + "px" : n) : void 0
        })
    }), rt.each({Height: "height", Width: "width"}, function (e, t) {
        rt.each({padding: "inner" + e, content: t, "": "outer" + e}, function (n, i) {
            rt.fn[i] = function (i, r) {
                var o = arguments.length && (n || "boolean" != typeof i), a = n || (i === !0 || r === !0 ? "margin" : "border");
                return Nt(this, function (t, n, i) {
                    var r;
                    return rt.isWindow(t) ? t.document.documentElement["client" + e] : 9 === t.nodeType ? (r = t.documentElement, Math.max(t.body["scroll" + e], r["scroll" + e], t.body["offset" + e], r["offset" + e], r["client" + e])) : void 0 === i ? rt.css(t, n, a) : rt.style(t, n, i, a)
                }, t, o ? i : void 0, o, null)
            }
        })
    }), rt.fn.size = function () {
        return this.length
    }, rt.fn.andSelf = rt.fn.addBack, "function" == typeof define && define.amd && define("jquery", [], function () {
        return rt
    });
    var oi = e.jQuery, ai = e.$;
    return rt.noConflict = function (t) {
        return e.$ === rt && (e.$ = ai), t && e.jQuery === rt && (e.jQuery = oi), rt
    }, typeof t === Tt && (e.jQuery = e.$ = rt), rt
}), function (e, t) {
    e.rails !== t && e.error("jquery-ujs has already been loaded!");
    var n, i = e(document);
    e.rails = n = {
        linkClickSelector: "a[data-confirm], a[data-method], a[data-remote], a[data-disable-with], a[data-disable]",
        buttonClickSelector: "button[data-remote]:not(form button), button[data-confirm]:not(form button)",
        inputChangeSelector: "select[data-remote], input[data-remote], textarea[data-remote]",
        formSubmitSelector: "form",
        formInputClickSelector: "form input[type=submit], form input[type=image], form button[type=submit], form button:not([type]), input[type=submit][form], input[type=image][form], button[type=submit][form], button[form]:not([type])",
        disableSelector: "input[data-disable-with]:enabled, button[data-disable-with]:enabled, textarea[data-disable-with]:enabled, input[data-disable]:enabled, button[data-disable]:enabled, textarea[data-disable]:enabled",
        enableSelector: "input[data-disable-with]:disabled, button[data-disable-with]:disabled, textarea[data-disable-with]:disabled, input[data-disable]:disabled, button[data-disable]:disabled, textarea[data-disable]:disabled",
        requiredInputSelector: "input[name][required]:not([disabled]),textarea[name][required]:not([disabled])",
        fileInputSelector: "input[type=file]",
        linkDisableSelector: "a[data-disable-with], a[data-disable]",
        buttonDisableSelector: "button[data-remote][data-disable-with], button[data-remote][data-disable]",
        CSRFProtection: function (t) {
            var n = e('meta[name="csrf-token"]').attr("content");
            n && t.setRequestHeader("X-CSRF-Token", n)
        },
        refreshCSRFTokens: function () {
            var t = e("meta[name=csrf-token]").attr("content"), n = e("meta[name=csrf-param]").attr("content");
            e('form input[name="' + n + '"]').val(t)
        },
        fire: function (t, n, i) {
            var r = e.Event(n);
            return t.trigger(r, i), r.result !== !1
        },
        confirm: function (e) {
            return confirm(e)
        },
        ajax: function (t) {
            return e.ajax(t)
        },
        href: function (e) {
            return e.attr("href")
        },
        handleRemote: function (i) {
            var r, o, a, s, l, u, c, d;
            if (n.fire(i, "ajax:before")) {
                if (s = i.data("cross-domain"), l = s === t ? null : s, u = i.data("with-credentials") || null, c = i.data("type") || e.ajaxSettings && e.ajaxSettings.dataType, i.is("form")) {
                    r = i.attr("method"), o = i.attr("action"), a = i.serializeArray();
                    var p = i.data("ujs:submit-button");
                    p && (a.push(p), i.data("ujs:submit-button", null))
                } else i.is(n.inputChangeSelector) ? (r = i.data("method"), o = i.data("url"), a = i.serialize(), i.data("params") && (a = a + "&" + i.data("params"))) : i.is(n.buttonClickSelector) ? (r = i.data("method") || "get", o = i.data("url"), a = i.serialize(), i.data("params") && (a = a + "&" + i.data("params"))) : (r = i.data("method"), o = n.href(i), a = i.data("params") || null);
                return d = {
                    type: r || "GET", data: a, dataType: c, beforeSend: function (e, r) {
                        return r.dataType === t && e.setRequestHeader("accept", "*/*;q=0.5, " + r.accepts.script), n.fire(i, "ajax:beforeSend", [e, r]) ? void i.trigger("ajax:send", e) : !1
                    }, success: function (e, t, n) {
                        i.trigger("ajax:success", [e, t, n])
                    }, complete: function (e, t) {
                        i.trigger("ajax:complete", [e, t])
                    }, error: function (e, t, n) {
                        i.trigger("ajax:error", [e, t, n])
                    }, crossDomain: l
                }, u && (d.xhrFields = {withCredentials: u}), o && (d.url = o), n.ajax(d)
            }
            return !1
        },
        handleMethod: function (i) {
            var r = n.href(i), o = i.data("method"), a = i.attr("target"), s = e("meta[name=csrf-token]").attr("content"), l = e("meta[name=csrf-param]").attr("content"), u = e('<form method="post" action="' + r + '"></form>'), c = '<input name="_method" value="' + o + '" type="hidden" />';
            l !== t && s !== t && (c += '<input name="' + l + '" value="' + s + '" type="hidden" />'), a && u.attr("target", a), u.hide().append(c).appendTo("body"), u.submit()
        },
        formElements: function (t, n) {
            return t.is("form") ? e(t[0].elements).filter(n) : t.find(n)
        },
        disableFormElements: function (t) {
            n.formElements(t, n.disableSelector).each(function () {
                n.disableFormElement(e(this))
            })
        },
        disableFormElement: function (e) {
            var n, i;
            n = e.is("button") ? "html" : "val", i = e.data("disable-with"), e.data("ujs:enable-with", e[n]()), i !== t && e[n](i), e.prop("disabled", !0)
        },
        enableFormElements: function (t) {
            n.formElements(t, n.enableSelector).each(function () {
                n.enableFormElement(e(this))
            })
        },
        enableFormElement: function (e) {
            var t = e.is("button") ? "html" : "val";
            e.data("ujs:enable-with") && e[t](e.data("ujs:enable-with")), e.prop("disabled", !1)
        },
        allowAction: function (e) {
            var t, i = e.data("confirm"), r = !1;
            return i ? (n.fire(e, "confirm") && (r = n.confirm(i), t = n.fire(e, "confirm:complete", [r])), r && t) : !0
        },
        blankInputs: function (t, n, i) {
            var r, o, a = e(), s = n || "input,textarea", l = t.find(s);
            return l.each(function () {
                if (r = e(this), o = r.is("input[type=checkbox],input[type=radio]") ? r.is(":checked") : r.val(), !o == !i) {
                    if (r.is("input[type=radio]") && l.filter('input[type=radio]:checked[name="' + r.attr("name") + '"]').length)return !0;
                    a = a.add(r)
                }
            }), a.length ? a : !1
        },
        nonBlankInputs: function (e, t) {
            return n.blankInputs(e, t, !0)
        },
        stopEverything: function (t) {
            return e(t.target).trigger("ujs:everythingStopped"), t.stopImmediatePropagation(), !1
        },
        disableElement: function (e) {
            var i = e.data("disable-with");
            e.data("ujs:enable-with", e.html()), i !== t && e.html(i), e.bind("click.railsDisable", function (e) {
                return n.stopEverything(e)
            })
        },
        enableElement: function (e) {
            e.data("ujs:enable-with") !== t && (e.html(e.data("ujs:enable-with")), e.removeData("ujs:enable-with")), e.unbind("click.railsDisable")
        }
    }, n.fire(i, "rails:attachBindings") && (e.ajaxPrefilter(function (e, t, i) {
        e.crossDomain || n.CSRFProtection(i)
    }), i.delegate(n.linkDisableSelector, "ajax:complete", function () {
        n.enableElement(e(this))
    }), i.delegate(n.buttonDisableSelector, "ajax:complete", function () {
        n.enableFormElement(e(this))
    }), i.delegate(n.linkClickSelector, "click.rails", function (i) {
        var r = e(this), o = r.data("method"), a = r.data("params"), s = i.metaKey || i.ctrlKey;
        if (!n.allowAction(r))return n.stopEverything(i);
        if (!s && r.is(n.linkDisableSelector) && n.disableElement(r), r.data("remote") !== t) {
            if (s && (!o || "GET" === o) && !a)return !0;
            var l = n.handleRemote(r);
            return l === !1 ? n.enableElement(r) : l.error(function () {
                n.enableElement(r)
            }), !1
        }
        return r.data("method") ? (n.handleMethod(r), !1) : void 0
    }), i.delegate(n.buttonClickSelector, "click.rails", function (t) {
        var i = e(this);
        if (!n.allowAction(i))return n.stopEverything(t);
        i.is(n.buttonDisableSelector) && n.disableFormElement(i);
        var r = n.handleRemote(i);
        return r === !1 ? n.enableFormElement(i) : r.error(function () {
            n.enableFormElement(i)
        }), !1
    }), i.delegate(n.inputChangeSelector, "change.rails", function (t) {
        var i = e(this);
        return n.allowAction(i) ? (n.handleRemote(i), !1) : n.stopEverything(t)
    }), i.delegate(n.formSubmitSelector, "submit.rails", function (i) {
        var r, o, a = e(this), s = a.data("remote") !== t;
        if (!n.allowAction(a))return n.stopEverything(i);
        if (a.attr("novalidate") == t && (r = n.blankInputs(a, n.requiredInputSelector), r && n.fire(a, "ajax:aborted:required", [r])))return n.stopEverything(i);
        if (s) {
            if (o = n.nonBlankInputs(a, n.fileInputSelector)) {
                setTimeout(function () {
                    n.disableFormElements(a)
                }, 13);
                var l = n.fire(a, "ajax:aborted:file", [o]);
                return l || setTimeout(function () {
                    n.enableFormElements(a)
                }, 13), l
            }
            return n.handleRemote(a), !1
        }
        setTimeout(function () {
            n.disableFormElements(a)
        }, 13)
    }), i.delegate(n.formInputClickSelector, "click.rails", function (t) {
        var i = e(this);
        if (!n.allowAction(i))return n.stopEverything(t);
        var r = i.attr("name"), o = r ? {name: r, value: i.val()} : null;
        i.closest("form").data("ujs:submit-button", o)
    }), i.delegate(n.formSubmitSelector, "ajax:send.rails", function (t) {
        this == t.target && n.disableFormElements(e(this))
    }), i.delegate(n.formSubmitSelector, "ajax:complete.rails", function (t) {
        this == t.target && n.enableFormElements(e(this))
    }), e(function () {
        n.refreshCSRFTokens()
    }))
}(jQuery), +function (e) {
    "use strict";
    function t() {
        var e = document.createElement("bootstrap"), t = {
            WebkitTransition: "webkitTransitionEnd",
            MozTransition: "transitionend",
            OTransition: "oTransitionEnd otransitionend",
            transition: "transitionend"
        };
        for (var n in t)if (void 0 !== e.style[n])return {end: t[n]};
        return !1
    }

    e.fn.emulateTransitionEnd = function (t) {
        var n = !1, i = this;
        e(this).one("bsTransitionEnd", function () {
            n = !0
        });
        var r = function () {
            n || e(i).trigger(e.support.transition.end)
        };
        return setTimeout(r, t), this
    }, e(function () {
        e.support.transition = t(), e.support.transition && (e.event.special.bsTransitionEnd = {
            bindType: e.support.transition.end,
            delegateType: e.support.transition.end,
            handle: function (t) {
                return e(t.target).is(this) ? t.handleObj.handler.apply(this, arguments) : void 0
            }
        })
    })
}(jQuery), +function (e) {
    "use strict";
    function t(t) {
        return this.each(function () {
            var n = e(this), r = n.data("bs.alert");
            r || n.data("bs.alert", r = new i(this)), "string" == typeof t && r[t].call(n)
        })
    }

    var n = '[data-dismiss="alert"]', i = function (t) {
        e(t).on("click", n, this.close)
    };
    i.VERSION = "3.2.0", i.prototype.close = function (t) {
        function n() {
            o.detach().trigger("closed.bs.alert").remove()
        }

        var i = e(this), r = i.attr("data-target");
        r || (r = i.attr("href"), r = r && r.replace(/.*(?=#[^\s]*$)/, ""));
        var o = e(r);
        t && t.preventDefault(), o.length || (o = i.hasClass("alert") ? i : i.parent()), o.trigger(t = e.Event("close.bs.alert")), t.isDefaultPrevented() || (o.removeClass("in"), e.support.transition && o.hasClass("fade") ? o.one("bsTransitionEnd", n).emulateTransitionEnd(150) : n())
    };
    var r = e.fn.alert;
    e.fn.alert = t, e.fn.alert.Constructor = i, e.fn.alert.noConflict = function () {
        return e.fn.alert = r, this
    }, e(document).on("click.bs.alert.data-api", n, i.prototype.close)
}(jQuery), +function (e) {
    "use strict";
    function t(t, i) {
        return this.each(function () {
            var r = e(this), o = r.data("bs.modal"), a = e.extend({}, n.DEFAULTS, r.data(), "object" == typeof t && t);
            o || r.data("bs.modal", o = new n(this, a)), "string" == typeof t ? o[t](i) : a.show && o.show(i)
        })
    }

    var n = function (t, n) {
        this.options = n, this.$body = e(document.body), this.$element = e(t), this.$backdrop = this.isShown = null, this.scrollbarWidth = 0, this.options.remote && this.$element.find(".modal-content").load(this.options.remote, e.proxy(function () {
            this.$element.trigger("loaded.bs.modal")
        }, this))
    };
    n.VERSION = "3.2.0", n.DEFAULTS = {backdrop: !0, keyboard: !0, show: !0}, n.prototype.toggle = function (e) {
        return this.isShown ? this.hide() : this.show(e)
    }, n.prototype.show = function (t) {
        var n = this, i = e.Event("show.bs.modal", {relatedTarget: t});
        this.$element.trigger(i), this.isShown || i.isDefaultPrevented() || (this.isShown = !0, this.checkScrollbar(), this.$body.addClass("modal-open"), this.setScrollbar(), this.escape(), this.$element.on("click.dismiss.bs.modal", '[data-dismiss="modal"]', e.proxy(this.hide, this)), this.backdrop(function () {
            var i = e.support.transition && n.$element.hasClass("fade");
            n.$element.parent().length || n.$element.appendTo(n.$body), n.$element.show().scrollTop(0), i && n.$element[0].offsetWidth, n.$element.addClass("in").attr("aria-hidden", !1), n.enforceFocus();
            var r = e.Event("shown.bs.modal", {relatedTarget: t});
            i ? n.$element.find(".modal-dialog").one("bsTransitionEnd", function () {
                n.$element.trigger("focus").trigger(r)
            }).emulateTransitionEnd(300) : n.$element.trigger("focus").trigger(r)
        }))
    }, n.prototype.hide = function (t) {
        t && t.preventDefault(), t = e.Event("hide.bs.modal"), this.$element.trigger(t), this.isShown && !t.isDefaultPrevented() && (this.isShown = !1, this.$body.removeClass("modal-open"), this.resetScrollbar(), this.escape(), e(document).off("focusin.bs.modal"), this.$element.removeClass("in").attr("aria-hidden", !0).off("click.dismiss.bs.modal"), e.support.transition && this.$element.hasClass("fade") ? this.$element.one("bsTransitionEnd", e.proxy(this.hideModal, this)).emulateTransitionEnd(300) : this.hideModal())
    }, n.prototype.enforceFocus = function () {
        e(document).off("focusin.bs.modal").on("focusin.bs.modal", e.proxy(function (e) {
            this.$element[0] === e.target || this.$element.has(e.target).length || this.$element.trigger("focus")
        }, this))
    }, n.prototype.escape = function () {
        this.isShown && this.options.keyboard ? this.$element.on("keyup.dismiss.bs.modal", e.proxy(function (e) {
            27 == e.which && this.hide()
        }, this)) : this.isShown || this.$element.off("keyup.dismiss.bs.modal")
    }, n.prototype.hideModal = function () {
        var e = this;
        this.$element.hide(), this.backdrop(function () {
            e.$element.trigger("hidden.bs.modal")
        })
    }, n.prototype.removeBackdrop = function () {
        this.$backdrop && this.$backdrop.remove(), this.$backdrop = null
    }, n.prototype.backdrop = function (t) {
        var n = this, i = this.$element.hasClass("fade") ? "fade" : "";
        if (this.isShown && this.options.backdrop) {
            var r = e.support.transition && i;
            if (this.$backdrop = e('<div class="modal-backdrop ' + i + '" />').appendTo(this.$body), this.$element.on("click.dismiss.bs.modal", e.proxy(function (e) {
                    e.target === e.currentTarget && ("static" == this.options.backdrop ? this.$element[0].focus.call(this.$element[0]) : this.hide.call(this))
                }, this)), r && this.$backdrop[0].offsetWidth, this.$backdrop.addClass("in"), !t)return;
            r ? this.$backdrop.one("bsTransitionEnd", t).emulateTransitionEnd(150) : t()
        } else if (!this.isShown && this.$backdrop) {
            this.$backdrop.removeClass("in");
            var o = function () {
                n.removeBackdrop(), t && t()
            };
            e.support.transition && this.$element.hasClass("fade") ? this.$backdrop.one("bsTransitionEnd", o).emulateTransitionEnd(150) : o()
        } else t && t()
    }, n.prototype.checkScrollbar = function () {
        document.body.clientWidth >= window.innerWidth || (this.scrollbarWidth = this.scrollbarWidth || this.measureScrollbar())
    }, n.prototype.setScrollbar = function () {
        var e = parseInt(this.$body.css("padding-right") || 0, 10);
        this.scrollbarWidth && this.$body.css("padding-right", e + this.scrollbarWidth)
    }, n.prototype.resetScrollbar = function () {
        this.$body.css("padding-right", "")
    }, n.prototype.measureScrollbar = function () {
        var e = document.createElement("div");
        e.className = "modal-scrollbar-measure", this.$body.append(e);
        var t = e.offsetWidth - e.clientWidth;
        return this.$body[0].removeChild(e), t
    };
    var i = e.fn.modal;
    e.fn.modal = t, e.fn.modal.Constructor = n, e.fn.modal.noConflict = function () {
        return e.fn.modal = i, this
    }, e(document).on("click.bs.modal.data-api", '[data-toggle="modal"]', function (n) {
        var i = e(this), r = i.attr("href"), o = e(i.attr("data-target") || r && r.replace(/.*(?=#[^\s]+$)/, "")), a = o.data("bs.modal") ? "toggle" : e.extend({remote: !/#/.test(r) && r}, o.data(), i.data());
        i.is("a") && n.preventDefault(), o.one("show.bs.modal", function (e) {
            e.isDefaultPrevented() || o.one("hidden.bs.modal", function () {
                i.is(":visible") && i.trigger("focus")
            })
        }), t.call(o, a, this)
    })
}(jQuery), +function (e) {
    "use strict";
    function t(t) {
        t && 3 === t.which || (e(r).remove(), e(o).each(function () {
            var i = n(e(this)), r = {relatedTarget: this};
            i.hasClass("open") && (i.trigger(t = e.Event("hide.bs.dropdown", r)), t.isDefaultPrevented() || i.removeClass("open").trigger("hidden.bs.dropdown", r))
        }))
    }

    function n(t) {
        var n = t.attr("data-target");
        n || (n = t.attr("href"), n = n && /#[A-Za-z]/.test(n) && n.replace(/.*(?=#[^\s]*$)/, ""));
        var i = n && e(n);
        return i && i.length ? i : t.parent()
    }

    function i(t) {
        return this.each(function () {
            var n = e(this), i = n.data("bs.dropdown");
            i || n.data("bs.dropdown", i = new a(this)), "string" == typeof t && i[t].call(n)
        })
    }

    var r = ".dropdown-backdrop", o = '[data-toggle="dropdown"]', a = function (t) {
        e(t).on("click.bs.dropdown", this.toggle)
    };
    a.VERSION = "3.2.0", a.prototype.toggle = function (i) {
        var r = e(this);
        if (!r.is(".disabled, :disabled")) {
            var o = n(r), a = o.hasClass("open");
            if (t(), !a) {
                "ontouchstart"in document.documentElement && !o.closest(".navbar-nav").length && e('<div class="dropdown-backdrop"/>').insertAfter(e(this)).on("click", t);
                var s = {relatedTarget: this};
                if (o.trigger(i = e.Event("show.bs.dropdown", s)), i.isDefaultPrevented())return;
                r.trigger("focus"), o.toggleClass("open").trigger("shown.bs.dropdown", s)
            }
            return !1
        }
    }, a.prototype.keydown = function (t) {
        if (/(38|40|27)/.test(t.keyCode)) {
            var i = e(this);
            if (t.preventDefault(), t.stopPropagation(), !i.is(".disabled, :disabled")) {
                var r = n(i), a = r.hasClass("open");
                if (!a || a && 27 == t.keyCode)return 27 == t.which && r.find(o).trigger("focus"), i.trigger("click");
                var s = " li:not(.divider):visible a", l = r.find('[role="menu"]' + s + ', [role="listbox"]' + s);
                if (l.length) {
                    var u = l.index(l.filter(":focus"));
                    38 == t.keyCode && u > 0 && u--, 40 == t.keyCode && u < l.length - 1 && u++, ~u || (u = 0), l.eq(u).trigger("focus")
                }
            }
        }
    };
    var s = e.fn.dropdown;
    e.fn.dropdown = i, e.fn.dropdown.Constructor = a, e.fn.dropdown.noConflict = function () {
        return e.fn.dropdown = s, this
    }, e(document).on("click.bs.dropdown.data-api", t).on("click.bs.dropdown.data-api", ".dropdown form", function (e) {
        e.stopPropagation()
    }).on("click.bs.dropdown.data-api", o, a.prototype.toggle).on("keydown.bs.dropdown.data-api", o + ', [role="menu"], [role="listbox"]', a.prototype.keydown)
}(jQuery), +function (e) {
    "use strict";
    function t(n, i) {
        var r = e.proxy(this.process, this);
        this.$body = e("body"), this.$scrollElement = e(e(n).is("body") ? window : n), this.options = e.extend({}, t.DEFAULTS, i), this.selector = (this.options.target || "") + " .nav li > a", this.offsets = [], this.targets = [], this.activeTarget = null, this.scrollHeight = 0, this.$scrollElement.on("scroll.bs.scrollspy", r), this.refresh(), this.process()
    }

    function n(n) {
        return this.each(function () {
            var i = e(this), r = i.data("bs.scrollspy"), o = "object" == typeof n && n;
            r || i.data("bs.scrollspy", r = new t(this, o)), "string" == typeof n && r[n]()
        })
    }

    t.VERSION = "3.2.0", t.DEFAULTS = {offset: 10}, t.prototype.getScrollHeight = function () {
        return this.$scrollElement[0].scrollHeight || Math.max(this.$body[0].scrollHeight, document.documentElement.scrollHeight)
    }, t.prototype.refresh = function () {
        var t = "offset", n = 0;
        e.isWindow(this.$scrollElement[0]) || (t = "position", n = this.$scrollElement.scrollTop()), this.offsets = [], this.targets = [], this.scrollHeight = this.getScrollHeight();
        var i = this;
        this.$body.find(this.selector).map(function () {
            var i = e(this), r = i.data("target") || i.attr("href"), o = /^#./.test(r) && e(r);
            return o && o.length && o.is(":visible") && [[o[t]().top + n, r]] || null
        }).sort(function (e, t) {
            return e[0] - t[0]
        }).each(function () {
            i.offsets.push(this[0]), i.targets.push(this[1])
        })
    }, t.prototype.process = function () {
        var e, t = this.$scrollElement.scrollTop() + this.options.offset, n = this.getScrollHeight(), i = this.options.offset + n - this.$scrollElement.height(), r = this.offsets, o = this.targets, a = this.activeTarget;
        if (this.scrollHeight != n && this.refresh(), t >= i)return a != (e = o[o.length - 1]) && this.activate(e);
        if (a && t <= r[0])return a != (e = o[0]) && this.activate(e);
        for (e = r.length; e--;)a != o[e] && t >= r[e] && (!r[e + 1] || t <= r[e + 1]) && this.activate(o[e])
    }, t.prototype.activate = function (t) {
        this.activeTarget = t, e(this.selector).parentsUntil(this.options.target, ".active").removeClass("active");
        var n = this.selector + '[data-target="' + t + '"],' + this.selector + '[href="' + t + '"]', i = e(n).parents("li").addClass("active");
        i.parent(".dropdown-menu").length && (i = i.closest("li.dropdown").addClass("active")), i.trigger("activate.bs.scrollspy")
    };
    var i = e.fn.scrollspy;
    e.fn.scrollspy = n, e.fn.scrollspy.Constructor = t, e.fn.scrollspy.noConflict = function () {
        return e.fn.scrollspy = i, this
    }, e(window).on("load.bs.scrollspy.data-api", function () {
        e('[data-spy="scroll"]').each(function () {
            var t = e(this);
            n.call(t, t.data())
        })
    })
}(jQuery), +function (e) {
    "use strict";
    function t(t) {
        return this.each(function () {
            var i = e(this), r = i.data("bs.tab");
            r || i.data("bs.tab", r = new n(this)), "string" == typeof t && r[t]()
        })
    }

    var n = function (t) {
        this.element = e(t)
    };
    n.VERSION = "3.2.0", n.prototype.show = function () {
        var t = this.element, n = t.closest("ul:not(.dropdown-menu)"), i = t.data("target");
        if (i || (i = t.attr("href"), i = i && i.replace(/.*(?=#[^\s]*$)/, "")), !t.parent("li").hasClass("active")) {
            var r = n.find(".active:last a")[0], o = e.Event("show.bs.tab", {relatedTarget: r});
            if (t.trigger(o), !o.isDefaultPrevented()) {
                var a = e(i);
                this.activate(t.closest("li"), n), this.activate(a, a.parent(), function () {
                    t.trigger({type: "shown.bs.tab", relatedTarget: r})
                })
            }
        }
    }, n.prototype.activate = function (t, n, i) {
        function r() {
            o.removeClass("active").find("> .dropdown-menu > .active").removeClass("active"), t.addClass("active"), a ? (t[0].offsetWidth, t.addClass("in")) : t.removeClass("fade"), t.parent(".dropdown-menu") && t.closest("li.dropdown").addClass("active"), i && i()
        }

        var o = n.find("> .active"), a = i && e.support.transition && o.hasClass("fade");
        a ? o.one("bsTransitionEnd", r).emulateTransitionEnd(150) : r(), o.removeClass("in")
    };
    var i = e.fn.tab;
    e.fn.tab = t, e.fn.tab.Constructor = n, e.fn.tab.noConflict = function () {
        return e.fn.tab = i, this
    }, e(document).on("click.bs.tab.data-api", '[data-toggle="tab"], [data-toggle="pill"]', function (n) {
        n.preventDefault(), t.call(e(this), "show")
    })
}(jQuery), +function (e) {
    "use strict";
    function t(t) {
        return this.each(function () {
            var i = e(this), r = i.data("bs.tooltip"), o = "object" == typeof t && t;
            (r || "destroy" != t) && (r || i.data("bs.tooltip", r = new n(this, o)), "string" == typeof t && r[t]())
        })
    }

    var n = function (e, t) {
        this.type = this.options = this.enabled = this.timeout = this.hoverState = this.$element = null, this.init("tooltip", e, t)
    };
    n.VERSION = "3.2.0", n.DEFAULTS = {
        animation: !0,
        placement: "top",
        selector: !1,
        template: '<div class="tooltip" role="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner"></div></div>',
        trigger: "hover focus",
        title: "",
        delay: 0,
        html: !1,
        container: !1,
        viewport: {selector: "body", padding: 0}
    }, n.prototype.init = function (t, n, i) {
        this.enabled = !0, this.type = t, this.$element = e(n), this.options = this.getOptions(i), this.$viewport = this.options.viewport && e(this.options.viewport.selector || this.options.viewport);
        for (var r = this.options.trigger.split(" "), o = r.length; o--;) {
            var a = r[o];
            if ("click" == a)this.$element.on("click." + this.type, this.options.selector, e.proxy(this.toggle, this)); else if ("manual" != a) {
                var s = "hover" == a ? "mouseenter" : "focusin", l = "hover" == a ? "mouseleave" : "focusout";
                this.$element.on(s + "." + this.type, this.options.selector, e.proxy(this.enter, this)), this.$element.on(l + "." + this.type, this.options.selector, e.proxy(this.leave, this))
            }
        }
        this.options.selector ? this._options = e.extend({}, this.options, {
            trigger: "manual",
            selector: ""
        }) : this.fixTitle()
    }, n.prototype.getDefaults = function () {
        return n.DEFAULTS
    }, n.prototype.getOptions = function (t) {
        return t = e.extend({}, this.getDefaults(), this.$element.data(), t), t.delay && "number" == typeof t.delay && (t.delay = {
            show: t.delay,
            hide: t.delay
        }), t
    }, n.prototype.getDelegateOptions = function () {
        var t = {}, n = this.getDefaults();
        return this._options && e.each(this._options, function (e, i) {
            n[e] != i && (t[e] = i)
        }), t
    }, n.prototype.enter = function (t) {
        var n = t instanceof this.constructor ? t : e(t.currentTarget).data("bs." + this.type);
        return n || (n = new this.constructor(t.currentTarget, this.getDelegateOptions()), e(t.currentTarget).data("bs." + this.type, n)), clearTimeout(n.timeout), n.hoverState = "in", n.options.delay && n.options.delay.show ? void(n.timeout = setTimeout(function () {
            "in" == n.hoverState && n.show()
        }, n.options.delay.show)) : n.show()
    }, n.prototype.leave = function (t) {
        var n = t instanceof this.constructor ? t : e(t.currentTarget).data("bs." + this.type);
        return n || (n = new this.constructor(t.currentTarget, this.getDelegateOptions()), e(t.currentTarget).data("bs." + this.type, n)), clearTimeout(n.timeout), n.hoverState = "out", n.options.delay && n.options.delay.hide ? void(n.timeout = setTimeout(function () {
            "out" == n.hoverState && n.hide()
        }, n.options.delay.hide)) : n.hide()
    }, n.prototype.show = function () {
        var t = e.Event("show.bs." + this.type);
        if (this.hasContent() && this.enabled) {
            this.$element.trigger(t);
            var n = e.contains(document.documentElement, this.$element[0]);
            if (t.isDefaultPrevented() || !n)return;
            var i = this, r = this.tip(), o = this.getUID(this.type);
            this.setContent(), r.attr("id", o), this.$element.attr("aria-describedby", o), this.options.animation && r.addClass("fade");
            var a = "function" == typeof this.options.placement ? this.options.placement.call(this, r[0], this.$element[0]) : this.options.placement, s = /\s?auto?\s?/i, l = s.test(a);
            l && (a = a.replace(s, "") || "top"), r.detach().css({
                top: 0,
                left: 0,
                display: "block"
            }).addClass(a).data("bs." + this.type, this), this.options.container ? r.appendTo(this.options.container) : r.insertAfter(this.$element);
            var u = this.getPosition(), c = r[0].offsetWidth, d = r[0].offsetHeight;
            if (l) {
                var p = a, f = this.$element.parent(), h = this.getPosition(f);
                a = "bottom" == a && u.top + u.height + d - h.scroll > h.height ? "top" : "top" == a && u.top - h.scroll - d < 0 ? "bottom" : "right" == a && u.right + c > h.width ? "left" : "left" == a && u.left - c < h.left ? "right" : a, r.removeClass(p).addClass(a)
            }
            var m = this.getCalculatedOffset(a, u, c, d);
            this.applyPlacement(m, a);
            var g = function () {
                i.$element.trigger("shown.bs." + i.type), i.hoverState = null
            };
            e.support.transition && this.$tip.hasClass("fade") ? r.one("bsTransitionEnd", g).emulateTransitionEnd(150) : g()
        }
    }, n.prototype.applyPlacement = function (t, n) {
        var i = this.tip(), r = i[0].offsetWidth, o = i[0].offsetHeight, a = parseInt(i.css("margin-top"), 10), s = parseInt(i.css("margin-left"), 10);
        isNaN(a) && (a = 0), isNaN(s) && (s = 0), t.top = t.top + a, t.left = t.left + s, e.offset.setOffset(i[0], e.extend({
            using: function (e) {
                i.css({top: Math.round(e.top), left: Math.round(e.left)})
            }
        }, t), 0), i.addClass("in");
        var l = i[0].offsetWidth, u = i[0].offsetHeight;
        "top" == n && u != o && (t.top = t.top + o - u);
        var c = this.getViewportAdjustedDelta(n, t, l, u);
        c.left ? t.left += c.left : t.top += c.top;
        var d = c.left ? 2 * c.left - r + l : 2 * c.top - o + u, p = c.left ? "left" : "top", f = c.left ? "offsetWidth" : "offsetHeight";
        i.offset(t), this.replaceArrow(d, i[0][f], p)
    }, n.prototype.replaceArrow = function (e, t, n) {
        this.arrow().css(n, e ? 50 * (1 - e / t) + "%" : "")
    }, n.prototype.setContent = function () {
        var e = this.tip(), t = this.getTitle();
        e.find(".tooltip-inner")[this.options.html ? "html" : "text"](t), e.removeClass("fade in top bottom left right")
    }, n.prototype.hide = function () {
        function t() {
            "in" != n.hoverState && i.detach(), n.$element.trigger("hidden.bs." + n.type)
        }

        var n = this, i = this.tip(), r = e.Event("hide.bs." + this.type);
        return this.$element.removeAttr("aria-describedby"), this.$element.trigger(r), r.isDefaultPrevented() ? void 0 : (i.removeClass("in"), e.support.transition && this.$tip.hasClass("fade") ? i.one("bsTransitionEnd", t).emulateTransitionEnd(150) : t(), this.hoverState = null, this)
    }, n.prototype.fixTitle = function () {
        var e = this.$element;
        (e.attr("title") || "string" != typeof e.attr("data-original-title")) && e.attr("data-original-title", e.attr("title") || "").attr("title", "")
    }, n.prototype.hasContent = function () {
        return this.getTitle()
    }, n.prototype.getPosition = function (t) {
        t = t || this.$element;
        var n = t[0], i = "BODY" == n.tagName;
        return e.extend({}, "function" == typeof n.getBoundingClientRect ? n.getBoundingClientRect() : null, {
            scroll: i ? document.documentElement.scrollTop || document.body.scrollTop : t.scrollTop(),
            width: i ? e(window).width() : t.outerWidth(),
            height: i ? e(window).height() : t.outerHeight()
        }, i ? {top: 0, left: 0} : t.offset())
    }, n.prototype.getCalculatedOffset = function (e, t, n, i) {
        return "bottom" == e ? {
            top: t.top + t.height,
            left: t.left + t.width / 2 - n / 2
        } : "top" == e ? {
            top: t.top - i,
            left: t.left + t.width / 2 - n / 2
        } : "left" == e ? {top: t.top + t.height / 2 - i / 2, left: t.left - n} : {
            top: t.top + t.height / 2 - i / 2,
            left: t.left + t.width
        }
    }, n.prototype.getViewportAdjustedDelta = function (e, t, n, i) {
        var r = {top: 0, left: 0};
        if (!this.$viewport)return r;
        var o = this.options.viewport && this.options.viewport.padding || 0, a = this.getPosition(this.$viewport);
        if (/right|left/.test(e)) {
            var s = t.top - o - a.scroll, l = t.top + o - a.scroll + i;
            s < a.top ? r.top = a.top - s : l > a.top + a.height && (r.top = a.top + a.height - l)
        } else {
            var u = t.left - o, c = t.left + o + n;
            u < a.left ? r.left = a.left - u : c > a.width && (r.left = a.left + a.width - c)
        }
        return r
    }, n.prototype.getTitle = function () {
        var e, t = this.$element, n = this.options;
        return e = t.attr("data-original-title") || ("function" == typeof n.title ? n.title.call(t[0]) : n.title)
    }, n.prototype.getUID = function (e) {
        do e += ~~(1e6 * Math.random()); while (document.getElementById(e));
        return e
    }, n.prototype.tip = function () {
        return this.$tip = this.$tip || e(this.options.template)
    }, n.prototype.arrow = function () {
        return this.$arrow = this.$arrow || this.tip().find(".tooltip-arrow")
    }, n.prototype.validate = function () {
        this.$element[0].parentNode || (this.hide(), this.$element = null, this.options = null)
    }, n.prototype.enable = function () {
        this.enabled = !0
    }, n.prototype.disable = function () {
        this.enabled = !1
    }, n.prototype.toggleEnabled = function () {
        this.enabled = !this.enabled
    }, n.prototype.toggle = function (t) {
        var n = this;
        t && (n = e(t.currentTarget).data("bs." + this.type), n || (n = new this.constructor(t.currentTarget, this.getDelegateOptions()), e(t.currentTarget).data("bs." + this.type, n))), n.tip().hasClass("in") ? n.leave(n) : n.enter(n)
    }, n.prototype.destroy = function () {
        clearTimeout(this.timeout), this.hide().$element.off("." + this.type).removeData("bs." + this.type)
    };
    var i = e.fn.tooltip;
    e.fn.tooltip = t, e.fn.tooltip.Constructor = n, e.fn.tooltip.noConflict = function () {
        return e.fn.tooltip = i, this
    }
}(jQuery), +function (e) {
    "use strict";
    function t(t) {
        return this.each(function () {
            var i = e(this), r = i.data("bs.popover"), o = "object" == typeof t && t;
            (r || "destroy" != t) && (r || i.data("bs.popover", r = new n(this, o)), "string" == typeof t && r[t]())
        })
    }

    var n = function (e, t) {
        this.init("popover", e, t)
    };
    if (!e.fn.tooltip)throw new Error("Popover requires tooltip.js");
    n.VERSION = "3.2.0", n.DEFAULTS = e.extend({}, e.fn.tooltip.Constructor.DEFAULTS, {
        placement: "right",
        trigger: "click",
        content: "",
        template: '<div class="popover" role="tooltip"><div class="arrow"></div><h3 class="popover-title"></h3><div class="popover-content"></div></div>'
    }), n.prototype = e.extend({}, e.fn.tooltip.Constructor.prototype), n.prototype.constructor = n, n.prototype.getDefaults = function () {
        return n.DEFAULTS
    }, n.prototype.setContent = function () {
        var e = this.tip(), t = this.getTitle(), n = this.getContent();
        e.find(".popover-title")[this.options.html ? "html" : "text"](t), e.find(".popover-content").empty()[this.options.html ? "string" == typeof n ? "html" : "append" : "text"](n), e.removeClass("fade top bottom left right in"), e.find(".popover-title").html() || e.find(".popover-title").hide()
    }, n.prototype.hasContent = function () {
        return this.getTitle() || this.getContent()
    }, n.prototype.getContent = function () {
        var e = this.$element, t = this.options;
        return e.attr("data-content") || ("function" == typeof t.content ? t.content.call(e[0]) : t.content)
    }, n.prototype.arrow = function () {
        return this.$arrow = this.$arrow || this.tip().find(".arrow")
    }, n.prototype.tip = function () {
        return this.$tip || (this.$tip = e(this.options.template)), this.$tip
    };
    var i = e.fn.popover;
    e.fn.popover = t, e.fn.popover.Constructor = n, e.fn.popover.noConflict = function () {
        return e.fn.popover = i, this
    }
}(jQuery), +function (e) {
    "use strict";
    function t(t) {
        return this.each(function () {
            var i = e(this), r = i.data("bs.button"), o = "object" == typeof t && t;
            r || i.data("bs.button", r = new n(this, o)), "toggle" == t ? r.toggle() : t && r.setState(t)
        })
    }

    var n = function (t, i) {
        this.$element = e(t), this.options = e.extend({}, n.DEFAULTS, i), this.isLoading = !1
    };
    n.VERSION = "3.2.0", n.DEFAULTS = {loadingText: "loading..."}, n.prototype.setState = function (t) {
        var n = "disabled", i = this.$element, r = i.is("input") ? "val" : "html", o = i.data();
        t += "Text", null == o.resetText && i.data("resetText", i[r]()), i[r](null == o[t] ? this.options[t] : o[t]), setTimeout(e.proxy(function () {
            "loadingText" == t ? (this.isLoading = !0, i.addClass(n).attr(n, n)) : this.isLoading && (this.isLoading = !1, i.removeClass(n).removeAttr(n))
        }, this), 0)
    }, n.prototype.toggle = function () {
        var e = !0, t = this.$element.closest('[data-toggle="buttons"]');
        if (t.length) {
            var n = this.$element.find("input");
            "radio" == n.prop("type") && (n.prop("checked") && this.$element.hasClass("active") ? e = !1 : t.find(".active").removeClass("active")), e && n.prop("checked", !this.$element.hasClass("active")).trigger("change")
        }
        e && this.$element.toggleClass("active")
    };
    var i = e.fn.button;
    e.fn.button = t, e.fn.button.Constructor = n, e.fn.button.noConflict = function () {
        return e.fn.button = i, this
    }, e(document).on("click.bs.button.data-api", '[data-toggle^="button"]', function (n) {
        var i = e(n.target);
        i.hasClass("btn") || (i = i.closest(".btn")), t.call(i, "toggle"), n.preventDefault()
    })
}(jQuery), +function (e) {
    "use strict";
    function t(t) {
        return this.each(function () {
            var i = e(this), r = i.data("bs.collapse"), o = e.extend({}, n.DEFAULTS, i.data(), "object" == typeof t && t);
            !r && o.toggle && "show" == t && (t = !t), r || i.data("bs.collapse", r = new n(this, o)), "string" == typeof t && r[t]()
        })
    }

    var n = function (t, i) {
        this.$element = e(t), this.options = e.extend({}, n.DEFAULTS, i), this.transitioning = null, this.options.parent && (this.$parent = e(this.options.parent)), this.options.toggle && this.toggle()
    };
    n.VERSION = "3.2.0", n.DEFAULTS = {toggle: !0}, n.prototype.dimension = function () {
        var e = this.$element.hasClass("width");
        return e ? "width" : "height"
    }, n.prototype.show = function () {
        if (!this.transitioning && !this.$element.hasClass("in")) {
            var n = e.Event("show.bs.collapse");
            if (this.$element.trigger(n), !n.isDefaultPrevented()) {
                var i = this.$parent && this.$parent.find("> .panel > .in");
                if (i && i.length) {
                    var r = i.data("bs.collapse");
                    if (r && r.transitioning)return;
                    t.call(i, "hide"), r || i.data("bs.collapse", null)
                }
                var o = this.dimension();
                this.$element.removeClass("collapse").addClass("collapsing")[o](0), this.transitioning = 1;
                var a = function () {
                    this.$element.removeClass("collapsing").addClass("collapse in")[o](""), this.transitioning = 0, this.$element.trigger("shown.bs.collapse")
                };
                if (!e.support.transition)return a.call(this);
                var s = e.camelCase(["scroll", o].join("-"));
                this.$element.one("bsTransitionEnd", e.proxy(a, this)).emulateTransitionEnd(350)[o](this.$element[0][s])
            }
        }
    }, n.prototype.hide = function () {
        if (!this.transitioning && this.$element.hasClass("in")) {
            var t = e.Event("hide.bs.collapse");
            if (this.$element.trigger(t), !t.isDefaultPrevented()) {
                var n = this.dimension();
                this.$element[n](this.$element[n]())[0].offsetHeight, this.$element.addClass("collapsing").removeClass("collapse").removeClass("in"), this.transitioning = 1;
                var i = function () {
                    this.transitioning = 0, this.$element.trigger("hidden.bs.collapse").removeClass("collapsing").addClass("collapse")
                };
                return e.support.transition ? void this.$element[n](0).one("bsTransitionEnd", e.proxy(i, this)).emulateTransitionEnd(350) : i.call(this)
            }
        }
    }, n.prototype.toggle = function () {
        this[this.$element.hasClass("in") ? "hide" : "show"]()
    };
    var i = e.fn.collapse;
    e.fn.collapse = t, e.fn.collapse.Constructor = n, e.fn.collapse.noConflict = function () {
        return e.fn.collapse = i, this
    }, e(document).on("click.bs.collapse.data-api", '[data-toggle="collapse"]', function (n) {
        var i, r = e(this), o = r.attr("data-target") || n.preventDefault() || (i = r.attr("href")) && i.replace(/.*(?=#[^\s]+$)/, ""), a = e(o), s = a.data("bs.collapse"), l = s ? "toggle" : r.data(), u = r.attr("data-parent"), c = u && e(u);
        s && s.transitioning || (c && c.find('[data-toggle="collapse"][data-parent="' + u + '"]').not(r).addClass("collapsed"), r[a.hasClass("in") ? "addClass" : "removeClass"]("collapsed")), t.call(a, l)
    })
}(jQuery), +function (e) {
    "use strict";
    function t(t) {
        return this.each(function () {
            var i = e(this), r = i.data("bs.carousel"), o = e.extend({}, n.DEFAULTS, i.data(), "object" == typeof t && t), a = "string" == typeof t ? t : o.slide;
            r || i.data("bs.carousel", r = new n(this, o)), "number" == typeof t ? r.to(t) : a ? r[a]() : o.interval && r.pause().cycle()
        })
    }

    var n = function (t, n) {
        this.$element = e(t).on("keydown.bs.carousel", e.proxy(this.keydown, this)), this.$indicators = this.$element.find(".carousel-indicators"), this.options = n, this.paused = this.sliding = this.interval = this.$active = this.$items = null, "hover" == this.options.pause && this.$element.on("mouseenter.bs.carousel", e.proxy(this.pause, this)).on("mouseleave.bs.carousel", e.proxy(this.cycle, this))
    };
    n.VERSION = "3.2.0", n.DEFAULTS = {interval: 5e3, pause: "hover", wrap: !0}, n.prototype.keydown = function (e) {
        switch (e.which) {
            case 37:
                this.prev();
                break;
            case 39:
                this.next();
                break;
            default:
                return
        }
        e.preventDefault()
    }, n.prototype.cycle = function (t) {
        return t || (this.paused = !1), this.interval && clearInterval(this.interval), this.options.interval && !this.paused && (this.interval = setInterval(e.proxy(this.next, this), this.options.interval)), this
    }, n.prototype.getItemIndex = function (e) {
        return this.$items = e.parent().children(".item"), this.$items.index(e || this.$active)
    }, n.prototype.to = function (t) {
        var n = this, i = this.getItemIndex(this.$active = this.$element.find(".item.active"));
        return t > this.$items.length - 1 || 0 > t ? void 0 : this.sliding ? this.$element.one("slid.bs.carousel", function () {
            n.to(t)
        }) : i == t ? this.pause().cycle() : this.slide(t > i ? "next" : "prev", e(this.$items[t]))
    }, n.prototype.pause = function (t) {
        return t || (this.paused = !0), this.$element.find(".next, .prev").length && e.support.transition && (this.$element.trigger(e.support.transition.end), this.cycle(!0)), this.interval = clearInterval(this.interval), this
    }, n.prototype.next = function () {
        return this.sliding ? void 0 : this.slide("next")
    }, n.prototype.prev = function () {
        return this.sliding ? void 0 : this.slide("prev")
    }, n.prototype.slide = function (t, n) {
        var i = this.$element.find(".item.active"), r = n || i[t](), o = this.interval, a = "next" == t ? "left" : "right", s = "next" == t ? "first" : "last", l = this;
        if (!r.length) {
            if (!this.options.wrap)return;
            r = this.$element.find(".item")[s]()
        }
        if (r.hasClass("active"))return this.sliding = !1;
        var u = r[0], c = e.Event("slide.bs.carousel", {relatedTarget: u, direction: a});
        if (this.$element.trigger(c), !c.isDefaultPrevented()) {
            if (this.sliding = !0, o && this.pause(), this.$indicators.length) {
                this.$indicators.find(".active").removeClass("active");
                var d = e(this.$indicators.children()[this.getItemIndex(r)]);
                d && d.addClass("active")
            }
            var p = e.Event("slid.bs.carousel", {relatedTarget: u, direction: a});
            return e.support.transition && this.$element.hasClass("slide") ? (r.addClass(t), r[0].offsetWidth, i.addClass(a), r.addClass(a), i.one("bsTransitionEnd", function () {
                r.removeClass([t, a].join(" ")).addClass("active"), i.removeClass(["active", a].join(" ")), l.sliding = !1, setTimeout(function () {
                    l.$element.trigger(p)
                }, 0)
            }).emulateTransitionEnd(1e3 * i.css("transition-duration").slice(0, -1))) : (i.removeClass("active"), r.addClass("active"), this.sliding = !1, this.$element.trigger(p)), o && this.cycle(), this
        }
    };
    var i = e.fn.carousel;
    e.fn.carousel = t, e.fn.carousel.Constructor = n, e.fn.carousel.noConflict = function () {
        return e.fn.carousel = i, this
    }, e(document).on("click.bs.carousel.data-api", "[data-slide], [data-slide-to]", function (n) {
        var i, r = e(this), o = e(r.attr("data-target") || (i = r.attr("href")) && i.replace(/.*(?=#[^\s]+$)/, ""));
        if (o.hasClass("carousel")) {
            var a = e.extend({}, o.data(), r.data()), s = r.attr("data-slide-to");
            s && (a.interval = !1), t.call(o, a), s && o.data("bs.carousel").to(s), n.preventDefault()
        }
    }), e(window).on("load", function () {
        e('[data-ride="carousel"]').each(function () {
            var n = e(this);
            t.call(n, n.data())
        })
    })
}(jQuery), +function (e) {
    "use strict";
    function t(t) {
        return this.each(function () {
            var i = e(this), r = i.data("bs.affix"), o = "object" == typeof t && t;
            r || i.data("bs.affix", r = new n(this, o)), "string" == typeof t && r[t]()
        })
    }

    var n = function (t, i) {
        this.options = e.extend({}, n.DEFAULTS, i), this.$target = e(this.options.target).on("scroll.bs.affix.data-api", e.proxy(this.checkPosition, this)).on("click.bs.affix.data-api", e.proxy(this.checkPositionWithEventLoop, this)), this.$element = e(t), this.affixed = this.unpin = this.pinnedOffset = null, this.checkPosition()
    };
    n.VERSION = "3.2.0", n.RESET = "affix affix-top affix-bottom", n.DEFAULTS = {
        offset: 0,
        target: window
    }, n.prototype.getPinnedOffset = function () {
        if (this.pinnedOffset)return this.pinnedOffset;
        this.$element.removeClass(n.RESET).addClass("affix");
        var e = this.$target.scrollTop(), t = this.$element.offset();
        return this.pinnedOffset = t.top - e
    }, n.prototype.checkPositionWithEventLoop = function () {
        setTimeout(e.proxy(this.checkPosition, this), 1)
    }, n.prototype.checkPosition = function () {
        if (this.$element.is(":visible")) {
            var t = e(document).height(), i = this.$target.scrollTop(), r = this.$element.offset(), o = this.options.offset, a = o.top, s = o.bottom;
            "object" != typeof o && (s = a = o), "function" == typeof a && (a = o.top(this.$element)), "function" == typeof s && (s = o.bottom(this.$element));
            var l = null != this.unpin && i + this.unpin <= r.top ? !1 : null != s && r.top + this.$element.height() >= t - s ? "bottom" : null != a && a >= i ? "top" : !1;
            if (this.affixed !== l) {
                null != this.unpin && this.$element.css("top", "");
                var u = "affix" + (l ? "-" + l : ""), c = e.Event(u + ".bs.affix");
                this.$element.trigger(c), c.isDefaultPrevented() || (this.affixed = l, this.unpin = "bottom" == l ? this.getPinnedOffset() : null, this.$element.removeClass(n.RESET).addClass(u).trigger(e.Event(u.replace("affix", "affixed"))), "bottom" == l && this.$element.offset({top: t - this.$element.height() - s}))
            }
        }
    };
    var i = e.fn.affix;
    e.fn.affix = t, e.fn.affix.Constructor = n, e.fn.affix.noConflict = function () {
        return e.fn.affix = i, this
    }, e(window).on("load", function () {
        e('[data-spy="affix"]').each(function () {
            var n = e(this), i = n.data();
            i.offset = i.offset || {}, i.offsetBottom && (i.offset.bottom = i.offsetBottom), i.offsetTop && (i.offset.top = i.offsetTop), t.call(n, i)
        })
    })
}(jQuery), function (e) {
    "use strict";
    e.ajaxPrefilter(function (e) {
        return e.iframe ? "iframe" : void 0
    }), e.ajaxTransport("iframe", function (t, n, i) {
        function r() {
            c.prop("disabled", !1), a.remove(), s.bind("load", function () {
                s.remove()
            }), s.attr("src", "javascript:false;")
        }

        var o, a = null, s = null, l = "iframe-" + e.now(), u = e(t.files).filter(":file:enabled"), c = null;
        return t.dataTypes.shift(), u.length ? (a = e("<form enctype='multipart/form-data' method='post'></form>").hide().attr({
            action: t.url,
            target: l
        }), "string" == typeof t.data && t.data.length > 0 && e.error("data must not be serialized"), e.each(t.data || {}, function (t, n) {
            e.isPlainObject(n) && (t = n.name, n = n.value), e("<input type='hidden' />").attr({
                name: t,
                value: n
            }).appendTo(a)
        }), e("<input type='hidden' value='IFrame' name='X-Requested-With' />").appendTo(a), o = t.dataTypes[0] && t.accepts[t.dataTypes[0]] ? t.accepts[t.dataTypes[0]] + ("*" !== t.dataTypes[0] ? ", */*; q=0.01" : "") : t.accepts["*"], e("<input type='hidden' name='X-Http-Accept'>").attr("value", o).appendTo(a), c = u.after(function () {
            return e(this).clone().prop("disabled", !0)
        }).next(), u.appendTo(a), {
            send: function (t, n) {
                s = e("<iframe src='javascript:false;' name='" + l + "' id='" + l + "' style='display:none'></iframe>"), s.bind("load", function () {
                    s.unbind("load").bind("load", function () {
                        var e = this.contentWindow ? this.contentWindow.document : this.contentDocument ? this.contentDocument : this.document, t = e.documentElement ? e.documentElement : e.body, o = t.getElementsByTagName("textarea")[0], a = o && o.getAttribute("data-type") || null, s = o && o.getAttribute("data-status") || 200, l = o && o.getAttribute("data-statusText") || "OK", u = {
                            html: t.innerHTML,
                            text: a ? o.value : t ? t.textContent || t.innerText : null
                        };
                        r(), i.responseText || (i.responseText = u.text), n(s, l, u, a ? "Content-Type: " + a : null)
                    }), a[0].submit()
                }), e("body").append(a, s)
            }, abort: function () {
                null !== s && (s.unbind("load").attr("src", "javascript:false;"), r())
            }
        }) : void 0
    })
}(jQuery), function (e) {
    var t;
    e.remotipart = t = {
        setup: function (n) {
            var i = n.data("ujs:submit-button"), r = e('meta[name="csrf-param"]').attr("content"), o = e('meta[name="csrf-token"]').attr("content"), a = n.find('input[name="' + r + '"]').length;
            n.one("ajax:beforeSend.remotipart", function (s, l, u) {
                return delete u.beforeSend, u.iframe = !0, u.files = e(e.rails.fileInputSelector, n), u.data = n.serializeArray(), i && u.data.push(i), u.files.each(function (e, t) {
                    for (var n = u.data.length - 1; n >= 0; n--)u.data[n].name == t.name && u.data.splice(n, 1)
                }), u.processData = !1, void 0 === u.dataType && (u.dataType = "script *"), u.data.push({
                    name: "remotipart_submitted",
                    value: !0
                }), o && r && !a && u.data.push({
                    name: r,
                    value: o
                }), e.rails.fire(n, "ajax:remotipartSubmit", [l, u]) && (e.rails.ajax(u), setTimeout(function () {
                    e.rails.disableFormElements(n)
                }, 20)), t.teardown(n), !1
            }).data("remotipartSubmitted", !0)
        }, teardown: function (e) {
            e.unbind("ajax:beforeSend.remotipart").removeData("remotipartSubmitted")
        }
    }, e(document).on("ajax:aborted:file", "form", function () {
        var n = e(this);
        return t.setup(n), e.rails.handleRemote(n), !1
    })
}(jQuery), function (e) {
    var t, n = {
        className: "autosizejs",
        id: "autosizejs",
        append: "\n",
        callback: !1,
        resizeDelay: 10,
        placeholder: !0
    }, i = '<textarea tabindex="-1" style="position:absolute; top:-999px; left:0; right:auto; bottom:auto; border:0; padding: 0; -moz-box-sizing:content-box; -webkit-box-sizing:content-box; box-sizing:content-box; word-wrap:break-word; height:0 !important; min-height:0 !important; overflow:hidden; transition:none; -webkit-transition:none; -moz-transition:none;"/>', r = ["fontFamily", "fontSize", "fontWeight", "fontStyle", "letterSpacing", "textTransform", "wordSpacing", "textIndent"], o = e(i).data("autosize", !0)[0];
    o.style.lineHeight = "99px", "99px" === e(o).css("lineHeight") && r.push("lineHeight"), o.style.lineHeight = "", e.fn.autosize = function (i) {
        return this.length ? (i = e.extend({}, n, i || {}), o.parentNode !== document.body && e(document.body).append(o), this.each(function () {
            function n() {
                var t, n = window.getComputedStyle ? window.getComputedStyle(p, null) : !1;
                n ? (t = p.getBoundingClientRect().width, (0 === t || "number" != typeof t) && (t = parseInt(n.width, 10)), e.each(["paddingLeft", "paddingRight", "borderLeftWidth", "borderRightWidth"], function (e, i) {
                    t -= parseInt(n[i], 10)
                })) : t = Math.max(f.width(), 0), o.style.width = t + "px"
            }

            function a() {
                var a = {};
                if (t = p, o.className = i.className, o.id = i.id, u = parseInt(f.css("maxHeight"), 10), e.each(r, function (e, t) {
                        a[t] = f.css(t)
                    }), e(o).css(a).attr("wrap", f.attr("wrap")), n(), window.chrome) {
                    var s = p.style.width;
                    p.style.width = "0px";
                    {
                        p.offsetWidth
                    }
                    p.style.width = s
                }
            }

            function s() {
                var e, r;
                t !== p ? a() : n(), o.value = !p.value && i.placeholder ? (f.attr("placeholder") || "") + i.append : p.value + i.append, o.style.overflowY = p.style.overflowY, r = parseInt(p.style.height, 10), o.scrollTop = 0, o.scrollTop = 9e4, e = o.scrollTop, u && e > u ? (p.style.overflowY = "scroll", e = u) : (p.style.overflowY = "hidden", c > e && (e = c)), e += h, r !== e && (p.style.height = e + "px", m && i.callback.call(p, p))
            }

            function l() {
                clearTimeout(d), d = setTimeout(function () {
                    var e = f.width();
                    e !== v && (v = e, s())
                }, parseInt(i.resizeDelay, 10))
            }

            var u, c, d, p = this, f = e(p), h = 0, m = e.isFunction(i.callback), g = {
                height: p.style.height,
                overflow: p.style.overflow,
                overflowY: p.style.overflowY,
                wordWrap: p.style.wordWrap,
                resize: p.style.resize
            }, v = f.width(), y = f.css("resize");
            f.data("autosize") || (f.data("autosize", !0), ("border-box" === f.css("box-sizing") || "border-box" === f.css("-moz-box-sizing") || "border-box" === f.css("-webkit-box-sizing")) && (h = f.outerHeight() - f.height()), c = Math.max(parseInt(f.css("minHeight"), 10) - h || 0, f.height()), f.css({
                overflow: "hidden",
                overflowY: "hidden",
                wordWrap: "break-word"
            }), "vertical" === y ? f.css("resize", "none") : "both" === y && f.css("resize", "horizontal"), "onpropertychange"in p ? "oninput"in p ? f.on("input.autosize keyup.autosize", s) : f.on("propertychange.autosize", function () {
                "value" === event.propertyName && s()
            }) : f.on("input.autosize", s), i.resizeDelay !== !1 && e(window).on("resize.autosize", l), f.on("autosize.resize", s), f.on("autosize.resizeIncludeStyle", function () {
                t = null, s()
            }), f.on("autosize.destroy", function () {
                t = null, clearTimeout(d), e(window).off("resize", l), f.off("autosize").off(".autosize").css(g).removeData("autosize")
            }), s())
        })) : this
    }
}(window.jQuery || window.$), function () {
    var e = !0;
    !function (t) {
        var n = this || (0, eval)("this"), i = n.document, r = n.navigator, o = n.jQuery, a = n.JSON;
        !function (e) {
            if ("function" == typeof require && "object" == typeof exports && "object" == typeof module) {
                var t = module.exports || exports;
                e(t, require)
            } else"function" == typeof define && define.amd ? define(["exports", "require"], e) : e(n.ko = {})
        }(function (s, l) {
            function u(e, t) {
                var n = null === e || typeof e in g;
                return n ? e === t : !1
            }

            function c(e, n) {
                var i;
                return function () {
                    i || (i = setTimeout(function () {
                        i = t, e()
                    }, n))
                }
            }

            function d(e, t) {
                var n;
                return function () {
                    clearTimeout(n), n = setTimeout(e, t)
                }
            }

            function p(e) {
                var t = this;
                return e && m.utils.objectForEach(e, function (e, n) {
                    var i = m.extenders[e];
                    "function" == typeof i && (t = i(t, n) || t)
                }), t
            }

            function f(e) {
                m.bindingHandlers[e] = {
                    init: function (t, n, i, r, o) {
                        var a = function () {
                            var t = {};
                            return t[e] = n(), t
                        };
                        return m.bindingHandlers.event.init.call(this, t, a, i, r, o)
                    }
                }
            }

            function h(e, t, n, i) {
                m.bindingHandlers[e] = {
                    init: function (e, r, o, a, s) {
                        var l, u;
                        return m.computed(function () {
                            var o = m.utils.unwrapObservable(r()), a = !n != !o, c = !u, d = c || t || a !== l;
                            d && (c && m.computedContext.getDependenciesCount() && (u = m.utils.cloneNodes(m.virtualElements.childNodes(e), !0)), a ? (c || m.virtualElements.setDomNodeChildren(e, m.utils.cloneNodes(u)), m.applyBindingsToDescendants(i ? i(s, o) : s, e)) : m.virtualElements.emptyNode(e), l = a)
                        }, null, {disposeWhenNodeIsRemoved: e}), {controlsDescendantBindings: !0}
                    }
                }, m.expressionRewriting.bindingRewriteValidators[e] = !1, m.virtualElements.allowedBindings[e] = !0
            }

            var m = "undefined" != typeof s ? s : {};
            m.exportSymbol = function (e, t) {
                for (var n = e.split("."), i = m, r = 0; r < n.length - 1; r++)i = i[n[r]];
                i[n[n.length - 1]] = t
            }, m.exportProperty = function (e, t, n) {
                e[t] = n
            }, m.version = "3.2.0", m.exportSymbol("version", m.version), m.utils = function () {
                function e(e, t) {
                    for (var n in e)e.hasOwnProperty(n) && t(n, e[n])
                }

                function s(e, t) {
                    if (t)for (var n in t)t.hasOwnProperty(n) && (e[n] = t[n]);
                    return e
                }

                function l(e, t) {
                    return e.__proto__ = t, e
                }

                function u(e, t) {
                    if ("input" !== m.utils.tagNameLower(e) || !e.type)return !1;
                    if ("click" != t.toLowerCase())return !1;
                    var n = e.type;
                    return "checkbox" == n || "radio" == n
                }

                var c = {__proto__: []}instanceof Array, d = {}, p = {}, f = r && /Firefox\/2/i.test(r.userAgent) ? "KeyboardEvent" : "UIEvents";
                d[f] = ["keyup", "keydown", "keypress"], d.MouseEvents = ["click", "dblclick", "mousedown", "mouseup", "mousemove", "mouseover", "mouseout", "mouseenter", "mouseleave"], e(d, function (e, t) {
                    if (t.length)for (var n = 0, i = t.length; i > n; n++)p[t[n]] = e
                });
                var h = {propertychange: !0}, g = i && function () {
                        for (var e = 3, n = i.createElement("div"), r = n.getElementsByTagName("i"); n.innerHTML = "<!--[if gt IE " + ++e + "]><i></i><![endif]-->", r[0];);
                        return e > 4 ? e : t
                    }(), v = 6 === g, y = 7 === g;
                return {
                    fieldsIncludedWithJsonPost: ["authenticity_token", /^__RequestVerificationToken(_.*)?$/],
                    arrayForEach: function (e, t) {
                        for (var n = 0, i = e.length; i > n; n++)t(e[n], n)
                    },
                    arrayIndexOf: function (e, t) {
                        if ("function" == typeof Array.prototype.indexOf)return Array.prototype.indexOf.call(e, t);
                        for (var n = 0, i = e.length; i > n; n++)if (e[n] === t)return n;
                        return -1
                    },
                    arrayFirst: function (e, t, n) {
                        for (var i = 0, r = e.length; r > i; i++)if (t.call(n, e[i], i))return e[i];
                        return null
                    },
                    arrayRemoveItem: function (e, t) {
                        var n = m.utils.arrayIndexOf(e, t);
                        n > 0 ? e.splice(n, 1) : 0 === n && e.shift()
                    },
                    arrayGetDistinctValues: function (e) {
                        e = e || [];
                        for (var t = [], n = 0, i = e.length; i > n; n++)m.utils.arrayIndexOf(t, e[n]) < 0 && t.push(e[n]);
                        return t
                    },
                    arrayMap: function (e, t) {
                        e = e || [];
                        for (var n = [], i = 0, r = e.length; r > i; i++)n.push(t(e[i], i));
                        return n
                    },
                    arrayFilter: function (e, t) {
                        e = e || [];
                        for (var n = [], i = 0, r = e.length; r > i; i++)t(e[i], i) && n.push(e[i]);
                        return n
                    },
                    arrayPushAll: function (e, t) {
                        if (t instanceof Array)e.push.apply(e, t); else for (var n = 0, i = t.length; i > n; n++)e.push(t[n]);
                        return e
                    },
                    addOrRemoveItem: function (e, t, n) {
                        var i = m.utils.arrayIndexOf(m.utils.peekObservable(e), t);
                        0 > i ? n && e.push(t) : n || e.splice(i, 1)
                    },
                    canSetPrototype: c,
                    extend: s,
                    setPrototypeOf: l,
                    setPrototypeOfOrExtend: c ? l : s,
                    objectForEach: e,
                    objectMap: function (e, t) {
                        if (!e)return e;
                        var n = {};
                        for (var i in e)e.hasOwnProperty(i) && (n[i] = t(e[i], i, e));
                        return n
                    },
                    emptyDomNode: function (e) {
                        for (; e.firstChild;)m.removeNode(e.firstChild)
                    },
                    moveCleanedNodesToContainerElement: function (e) {
                        for (var t = m.utils.makeArray(e), n = i.createElement("div"), r = 0, o = t.length; o > r; r++)n.appendChild(m.cleanNode(t[r]));
                        return n
                    },
                    cloneNodes: function (e, t) {
                        for (var n = 0, i = e.length, r = []; i > n; n++) {
                            var o = e[n].cloneNode(!0);
                            r.push(t ? m.cleanNode(o) : o)
                        }
                        return r
                    },
                    setDomNodeChildren: function (e, t) {
                        if (m.utils.emptyDomNode(e), t)for (var n = 0, i = t.length; i > n; n++)e.appendChild(t[n])
                    },
                    replaceDomNodes: function (e, t) {
                        var n = e.nodeType ? [e] : e;
                        if (n.length > 0) {
                            for (var i = n[0], r = i.parentNode, o = 0, a = t.length; a > o; o++)r.insertBefore(t[o], i);
                            for (var o = 0, a = n.length; a > o; o++)m.removeNode(n[o])
                        }
                    },
                    fixUpContinuousNodeArray: function (e, t) {
                        if (e.length) {
                            for (t = 8 === t.nodeType && t.parentNode || t; e.length && e[0].parentNode !== t;)e.shift();
                            if (e.length > 1) {
                                var n = e[0], i = e[e.length - 1];
                                for (e.length = 0; n !== i;)if (e.push(n), n = n.nextSibling, !n)return;
                                e.push(i)
                            }
                        }
                        return e
                    },
                    setOptionNodeSelectionState: function (e, t) {
                        7 > g ? e.setAttribute("selected", t) : e.selected = t
                    },
                    stringTrim: function (e) {
                        return null === e || e === t ? "" : e.trim ? e.trim() : e.toString().replace(/^[\s\xa0]+|[\s\xa0]+$/g, "")
                    },
                    stringStartsWith: function (e, t) {
                        return e = e || "", t.length > e.length ? !1 : e.substring(0, t.length) === t
                    },
                    domNodeIsContainedBy: function (e, t) {
                        if (e === t)return !0;
                        if (11 === e.nodeType)return !1;
                        if (t.contains)return t.contains(3 === e.nodeType ? e.parentNode : e);
                        if (t.compareDocumentPosition)return 16 == (16 & t.compareDocumentPosition(e));
                        for (; e && e != t;)e = e.parentNode;
                        return !!e
                    },
                    domNodeIsAttachedToDocument: function (e) {
                        return m.utils.domNodeIsContainedBy(e, e.ownerDocument.documentElement)
                    },
                    anyDomNodeIsAttachedToDocument: function (e) {
                        return !!m.utils.arrayFirst(e, m.utils.domNodeIsAttachedToDocument)
                    },
                    tagNameLower: function (e) {
                        return e && e.tagName && e.tagName.toLowerCase()
                    },
                    registerEventHandler: function (e, t, n) {
                        var i = g && h[t];
                        if (!i && o)o(e).bind(t, n); else if (i || "function" != typeof e.addEventListener) {
                            if ("undefined" == typeof e.attachEvent)throw new Error("Browser doesn't support addEventListener or attachEvent");
                            var r = function (t) {
                                n.call(e, t)
                            }, a = "on" + t;
                            e.attachEvent(a, r), m.utils.domNodeDisposal.addDisposeCallback(e, function () {
                                e.detachEvent(a, r)
                            })
                        } else e.addEventListener(t, n, !1)
                    },
                    triggerEvent: function (e, t) {
                        if (!e || !e.nodeType)throw new Error("element must be a DOM node when calling triggerEvent");
                        var r = u(e, t);
                        if (o && !r)o(e).trigger(t); else if ("function" == typeof i.createEvent) {
                            if ("function" != typeof e.dispatchEvent)throw new Error("The supplied element doesn't support dispatchEvent");
                            var a = p[t] || "HTMLEvents", s = i.createEvent(a);
                            s.initEvent(t, !0, !0, n, 0, 0, 0, 0, 0, !1, !1, !1, !1, 0, e), e.dispatchEvent(s)
                        } else if (r && e.click)e.click(); else {
                            if ("undefined" == typeof e.fireEvent)throw new Error("Browser doesn't support triggering events");
                            e.fireEvent("on" + t)
                        }
                    },
                    unwrapObservable: function (e) {
                        return m.isObservable(e) ? e() : e
                    },
                    peekObservable: function (e) {
                        return m.isObservable(e) ? e.peek() : e
                    },
                    toggleDomNodeCssClass: function (e, t, n) {
                        if (t) {
                            var i = /\S+/g, r = e.className.match(i) || [];
                            m.utils.arrayForEach(t.match(i), function (e) {
                                m.utils.addOrRemoveItem(r, e, n)
                            }), e.className = r.join(" ")
                        }
                    },
                    setTextContent: function (e, n) {
                        var i = m.utils.unwrapObservable(n);
                        (null === i || i === t) && (i = "");
                        var r = m.virtualElements.firstChild(e);
                        !r || 3 != r.nodeType || m.virtualElements.nextSibling(r) ? m.virtualElements.setDomNodeChildren(e, [e.ownerDocument.createTextNode(i)]) : r.data = i, m.utils.forceRefresh(e)
                    },
                    setElementName: function (e, t) {
                        if (e.name = t, 7 >= g)try {
                            e.mergeAttributes(i.createElement("<input name='" + e.name + "'/>"), !1)
                        } catch (n) {
                        }
                    },
                    forceRefresh: function (e) {
                        if (g >= 9) {
                            var t = 1 == e.nodeType ? e : e.parentNode;
                            t.style && (t.style.zoom = t.style.zoom)
                        }
                    },
                    ensureSelectElementIsRenderedCorrectly: function (e) {
                        if (g) {
                            var t = e.style.width;
                            e.style.width = 0, e.style.width = t
                        }
                    },
                    range: function (e, t) {
                        e = m.utils.unwrapObservable(e), t = m.utils.unwrapObservable(t);
                        for (var n = [], i = e; t >= i; i++)n.push(i);
                        return n
                    },
                    makeArray: function (e) {
                        for (var t = [], n = 0, i = e.length; i > n; n++)t.push(e[n]);
                        return t
                    },
                    isIe6: v,
                    isIe7: y,
                    ieVersion: g,
                    getFormFields: function (e, t) {
                        for (var n = m.utils.makeArray(e.getElementsByTagName("input")).concat(m.utils.makeArray(e.getElementsByTagName("textarea"))), i = "string" == typeof t ? function (e) {
                            return e.name === t
                        } : function (e) {
                            return t.test(e.name)
                        }, r = [], o = n.length - 1; o >= 0; o--)i(n[o]) && r.push(n[o]);
                        return r
                    },
                    parseJson: function (e) {
                        return "string" == typeof e && (e = m.utils.stringTrim(e)) ? a && a.parse ? a.parse(e) : new Function("return " + e)() : null
                    },
                    stringifyJson: function (e, t, n) {
                        if (!a || !a.stringify)throw new Error("Cannot find JSON.stringify(). Some browsers (e.g., IE < 8) don't support it natively, but you can overcome this by adding a script reference to json2.js, downloadable from http://www.json.org/json2.js");
                        return a.stringify(m.utils.unwrapObservable(e), t, n)
                    },
                    postJson: function (t, n, r) {
                        r = r || {};
                        var o = r.params || {}, a = r.includeFields || this.fieldsIncludedWithJsonPost, s = t;
                        if ("object" == typeof t && "form" === m.utils.tagNameLower(t)) {
                            var l = t;
                            s = l.action;
                            for (var u = a.length - 1; u >= 0; u--)for (var c = m.utils.getFormFields(l, a[u]), d = c.length - 1; d >= 0; d--)o[c[d].name] = c[d].value
                        }
                        n = m.utils.unwrapObservable(n);
                        var p = i.createElement("form");
                        p.style.display = "none", p.action = s, p.method = "post";
                        for (var f in n) {
                            var h = i.createElement("input");
                            h.type = "hidden", h.name = f, h.value = m.utils.stringifyJson(m.utils.unwrapObservable(n[f])), p.appendChild(h)
                        }
                        e(o, function (e, t) {
                            var n = i.createElement("input");
                            n.type = "hidden", n.name = e, n.value = t, p.appendChild(n)
                        }), i.body.appendChild(p), r.submitter ? r.submitter(p) : p.submit(), setTimeout(function () {
                            p.parentNode.removeChild(p)
                        }, 0)
                    }
                }
            }(), m.exportSymbol("utils", m.utils), m.exportSymbol("utils.arrayForEach", m.utils.arrayForEach), m.exportSymbol("utils.arrayFirst", m.utils.arrayFirst), m.exportSymbol("utils.arrayFilter", m.utils.arrayFilter), m.exportSymbol("utils.arrayGetDistinctValues", m.utils.arrayGetDistinctValues), m.exportSymbol("utils.arrayIndexOf", m.utils.arrayIndexOf), m.exportSymbol("utils.arrayMap", m.utils.arrayMap), m.exportSymbol("utils.arrayPushAll", m.utils.arrayPushAll), m.exportSymbol("utils.arrayRemoveItem", m.utils.arrayRemoveItem), m.exportSymbol("utils.extend", m.utils.extend), m.exportSymbol("utils.fieldsIncludedWithJsonPost", m.utils.fieldsIncludedWithJsonPost), m.exportSymbol("utils.getFormFields", m.utils.getFormFields), m.exportSymbol("utils.peekObservable", m.utils.peekObservable), m.exportSymbol("utils.postJson", m.utils.postJson), m.exportSymbol("utils.parseJson", m.utils.parseJson), m.exportSymbol("utils.registerEventHandler", m.utils.registerEventHandler), m.exportSymbol("utils.stringifyJson", m.utils.stringifyJson), m.exportSymbol("utils.range", m.utils.range), m.exportSymbol("utils.toggleDomNodeCssClass", m.utils.toggleDomNodeCssClass), m.exportSymbol("utils.triggerEvent", m.utils.triggerEvent), m.exportSymbol("utils.unwrapObservable", m.utils.unwrapObservable), m.exportSymbol("utils.objectForEach", m.utils.objectForEach), m.exportSymbol("utils.addOrRemoveItem", m.utils.addOrRemoveItem), m.exportSymbol("unwrap", m.utils.unwrapObservable), Function.prototype.bind || (Function.prototype.bind = function (e) {
                var t = this, n = Array.prototype.slice.call(arguments), e = n.shift();
                return function () {
                    return t.apply(e, n.concat(Array.prototype.slice.call(arguments)))
                }
            }), m.utils.domData = new function () {
                function e(e, o) {
                    var a = e[i], s = a && "null" !== a && r[a];
                    if (!s) {
                        if (!o)return t;
                        a = e[i] = "ko" + n++, r[a] = {}
                    }
                    return r[a]
                }

                var n = 0, i = "__ko__" + (new Date).getTime(), r = {};
                return {
                    get: function (n, i) {
                        var r = e(n, !1);
                        return r === t ? t : r[i]
                    }, set: function (n, i, r) {
                        if (r !== t || e(n, !1) !== t) {
                            var o = e(n, !0);
                            o[i] = r
                        }
                    }, clear: function (e) {
                        var t = e[i];
                        return t ? (delete r[t], e[i] = null, !0) : !1
                    }, nextKey: function () {
                        return n++ + i
                    }
                }
            }, m.exportSymbol("utils.domData", m.utils.domData), m.exportSymbol("utils.domData.clear", m.utils.domData.clear), m.utils.domNodeDisposal = new function () {
                function e(e, n) {
                    var i = m.utils.domData.get(e, a);
                    return i === t && n && (i = [], m.utils.domData.set(e, a, i)), i
                }

                function n(e) {
                    m.utils.domData.set(e, a, t)
                }

                function i(t) {
                    var n = e(t, !1);
                    if (n) {
                        n = n.slice(0);
                        for (var i = 0; i < n.length; i++)n[i](t)
                    }
                    m.utils.domData.clear(t), m.utils.domNodeDisposal.cleanExternalData(t), l[t.nodeType] && r(t)
                }

                function r(e) {
                    for (var t, n = e.firstChild; t = n;)n = t.nextSibling, 8 === t.nodeType && i(t)
                }

                var a = m.utils.domData.nextKey(), s = {1: !0, 8: !0, 9: !0}, l = {1: !0, 9: !0};
                return {
                    addDisposeCallback: function (t, n) {
                        if ("function" != typeof n)throw new Error("Callback must be a function");
                        e(t, !0).push(n)
                    }, removeDisposeCallback: function (t, i) {
                        var r = e(t, !1);
                        r && (m.utils.arrayRemoveItem(r, i), 0 == r.length && n(t))
                    }, cleanNode: function (e) {
                        if (s[e.nodeType] && (i(e), l[e.nodeType])) {
                            var t = [];
                            m.utils.arrayPushAll(t, e.getElementsByTagName("*"));
                            for (var n = 0, r = t.length; r > n; n++)i(t[n])
                        }
                        return e
                    }, removeNode: function (e) {
                        m.cleanNode(e), e.parentNode && e.parentNode.removeChild(e)
                    }, cleanExternalData: function (e) {
                        o && "function" == typeof o.cleanData && o.cleanData([e])
                    }
                }
            }, m.cleanNode = m.utils.domNodeDisposal.cleanNode, m.removeNode = m.utils.domNodeDisposal.removeNode, m.exportSymbol("cleanNode", m.cleanNode), m.exportSymbol("removeNode", m.removeNode), m.exportSymbol("utils.domNodeDisposal", m.utils.domNodeDisposal), m.exportSymbol("utils.domNodeDisposal.addDisposeCallback", m.utils.domNodeDisposal.addDisposeCallback), m.exportSymbol("utils.domNodeDisposal.removeDisposeCallback", m.utils.domNodeDisposal.removeDisposeCallback), function () {
                function e(e) {
                    var t = m.utils.stringTrim(e).toLowerCase(), r = i.createElement("div"), o = t.match(/^<(thead|tbody|tfoot)/) && [1, "<table>", "</table>"] || !t.indexOf("<tr") && [2, "<table><tbody>", "</tbody></table>"] || (!t.indexOf("<td") || !t.indexOf("<th")) && [3, "<table><tbody><tr>", "</tr></tbody></table>"] || [0, "", ""], a = "ignored<div>" + o[1] + e + o[2] + "</div>";
                    for ("function" == typeof n.innerShiv ? r.appendChild(n.innerShiv(a)) : r.innerHTML = a; o[0]--;)r = r.lastChild;
                    return m.utils.makeArray(r.lastChild.childNodes)
                }

                function r(e) {
                    if (o.parseHTML)return o.parseHTML(e) || [];
                    var t = o.clean([e]);
                    if (t && t[0]) {
                        for (var n = t[0]; n.parentNode && 11 !== n.parentNode.nodeType;)n = n.parentNode;
                        n.parentNode && n.parentNode.removeChild(n)
                    }
                    return t
                }

                m.utils.parseHtmlFragment = function (t) {
                    return o ? r(t) : e(t)
                }, m.utils.setHtml = function (e, n) {
                    if (m.utils.emptyDomNode(e), n = m.utils.unwrapObservable(n), null !== n && n !== t)if ("string" != typeof n && (n = n.toString()), o)o(e).html(n); else for (var i = m.utils.parseHtmlFragment(n), r = 0; r < i.length; r++)e.appendChild(i[r])
                }
            }(), m.exportSymbol("utils.parseHtmlFragment", m.utils.parseHtmlFragment), m.exportSymbol("utils.setHtml", m.utils.setHtml), m.memoization = function () {
                function e() {
                    return (4294967296 * (1 + Math.random()) | 0).toString(16).substring(1)
                }

                function n() {
                    return e() + e()
                }

                function i(e, t) {
                    if (e)if (8 == e.nodeType) {
                        var n = m.memoization.parseMemoText(e.nodeValue);
                        null != n && t.push({domNode: e, memoId: n})
                    } else if (1 == e.nodeType)for (var r = 0, o = e.childNodes, a = o.length; a > r; r++)i(o[r], t)
                }

                var r = {};
                return {
                    memoize: function (e) {
                        if ("function" != typeof e)throw new Error("You can only pass a function to ko.memoization.memoize()");
                        var t = n();
                        return r[t] = e, "<!--[ko_memo:" + t + "]-->"
                    }, unmemoize: function (e, n) {
                        var i = r[e];
                        if (i === t)throw new Error("Couldn't find any memo with ID " + e + ". Perhaps it's already been unmemoized.");
                        try {
                            return i.apply(null, n || []), !0
                        } finally {
                            delete r[e]
                        }
                    }, unmemoizeDomNodeAndDescendants: function (e, t) {
                        var n = [];
                        i(e, n);
                        for (var r = 0, o = n.length; o > r; r++) {
                            var a = n[r].domNode, s = [a];
                            t && m.utils.arrayPushAll(s, t), m.memoization.unmemoize(n[r].memoId, s), a.nodeValue = "", a.parentNode && a.parentNode.removeChild(a)
                        }
                    }, parseMemoText: function (e) {
                        var t = e.match(/^\[ko_memo\:(.*?)\]$/);
                        return t ? t[1] : null
                    }
                }
            }(), m.exportSymbol("memoization", m.memoization), m.exportSymbol("memoization.memoize", m.memoization.memoize), m.exportSymbol("memoization.unmemoize", m.memoization.unmemoize), m.exportSymbol("memoization.parseMemoText", m.memoization.parseMemoText), m.exportSymbol("memoization.unmemoizeDomNodeAndDescendants", m.memoization.unmemoizeDomNodeAndDescendants), m.extenders = {
                throttle: function (e, t) {
                    e.throttleEvaluation = t;
                    var n = null;
                    return m.dependentObservable({
                        read: e, write: function (i) {
                            clearTimeout(n), n = setTimeout(function () {
                                e(i)
                            }, t)
                        }
                    })
                }, rateLimit: function (e, t) {
                    var n, i, r;
                    "number" == typeof t ? n = t : (n = t.timeout, i = t.method), r = "notifyWhenChangesStop" == i ? d : c, e.limit(function (e) {
                        return r(e, n)
                    })
                }, notify: function (e, t) {
                    e.equalityComparer = "always" == t ? null : u
                }
            };
            var g = {undefined: 1, "boolean": 1, number: 1, string: 1};
            m.exportSymbol("extenders", m.extenders), m.subscription = function (e, t, n) {
                this.target = e, this.callback = t, this.disposeCallback = n, this.isDisposed = !1, m.exportProperty(this, "dispose", this.dispose)
            }, m.subscription.prototype.dispose = function () {
                this.isDisposed = !0, this.disposeCallback()
            }, m.subscribable = function () {
                m.utils.setPrototypeOfOrExtend(this, m.subscribable.fn), this._subscriptions = {}
            };
            var v = "change", y = {
                subscribe: function (e, t, n) {
                    var i = this;
                    n = n || v;
                    var r = t ? e.bind(t) : e, o = new m.subscription(i, r, function () {
                        m.utils.arrayRemoveItem(i._subscriptions[n], o), i.afterSubscriptionRemove && i.afterSubscriptionRemove(n)
                    });
                    return i.beforeSubscriptionAdd && i.beforeSubscriptionAdd(n), i._subscriptions[n] || (i._subscriptions[n] = []), i._subscriptions[n].push(o), o
                }, notifySubscribers: function (e, t) {
                    if (t = t || v, this.hasSubscriptionsForEvent(t))try {
                        m.dependencyDetection.begin();
                        for (var n, i = this._subscriptions[t].slice(0), r = 0; n = i[r]; ++r)n.isDisposed || n.callback(e)
                    } finally {
                        m.dependencyDetection.end()
                    }
                }, limit: function (e) {
                    var t, n, i, r = this, o = m.isObservable(r), a = "beforeChange";
                    r._origNotifySubscribers || (r._origNotifySubscribers = r.notifySubscribers, r.notifySubscribers = function (e, t) {
                        t && t !== v ? t === a ? r._rateLimitedBeforeChange(e) : r._origNotifySubscribers(e, t) : r._rateLimitedChange(e)
                    });
                    var s = e(function () {
                        o && i === r && (i = r()), t = !1, r.isDifferent(n, i) && r._origNotifySubscribers(n = i)
                    });
                    r._rateLimitedChange = function (e) {
                        t = !0, i = e, s()
                    }, r._rateLimitedBeforeChange = function (e) {
                        t || (n = e, r._origNotifySubscribers(e, a))
                    }
                }, hasSubscriptionsForEvent: function (e) {
                    return this._subscriptions[e] && this._subscriptions[e].length
                }, getSubscriptionsCount: function () {
                    var e = 0;
                    return m.utils.objectForEach(this._subscriptions, function (t, n) {
                        e += n.length
                    }), e
                }, isDifferent: function (e, t) {
                    return !this.equalityComparer || !this.equalityComparer(e, t)
                }, extend: p
            };
            m.exportProperty(y, "subscribe", y.subscribe), m.exportProperty(y, "extend", y.extend), m.exportProperty(y, "getSubscriptionsCount", y.getSubscriptionsCount), m.utils.canSetPrototype && m.utils.setPrototypeOf(y, Function.prototype), m.subscribable.fn = y, m.isSubscribable = function (e) {
                return null != e && "function" == typeof e.subscribe && "function" == typeof e.notifySubscribers
            }, m.exportSymbol("subscribable", m.subscribable), m.exportSymbol("isSubscribable", m.isSubscribable), m.computedContext = m.dependencyDetection = function () {
                function e() {
                    return ++o
                }

                function t(e) {
                    r.push(i), i = e
                }

                function n() {
                    i = r.pop()
                }

                var i, r = [], o = 0;
                return {
                    begin: t, end: n, registerDependency: function (t) {
                        if (i) {
                            if (!m.isSubscribable(t))throw new Error("Only subscribable things can act as dependencies");
                            i.callback(t, t._id || (t._id = e()))
                        }
                    }, ignore: function (e, i, r) {
                        try {
                            return t(), e.apply(i, r || [])
                        } finally {
                            n()
                        }
                    }, getDependenciesCount: function () {
                        return i ? i.computed.getDependenciesCount() : void 0
                    }, isInitial: function () {
                        return i ? i.isInitial : void 0
                    }
                }
            }(), m.exportSymbol("computedContext", m.computedContext), m.exportSymbol("computedContext.getDependenciesCount", m.computedContext.getDependenciesCount), m.exportSymbol("computedContext.isInitial", m.computedContext.isInitial), m.exportSymbol("computedContext.isSleeping", m.computedContext.isSleeping), m.observable = function (t) {
                function n() {
                    return arguments.length > 0 ? (n.isDifferent(i, arguments[0]) && (n.valueWillMutate(), i = arguments[0], e && (n._latestValue = i), n.valueHasMutated()), this) : (m.dependencyDetection.registerDependency(n), i)
                }

                var i = t;
                return m.subscribable.call(n), m.utils.setPrototypeOfOrExtend(n, m.observable.fn), e && (n._latestValue = i), n.peek = function () {
                    return i
                }, n.valueHasMutated = function () {
                    n.notifySubscribers(i)
                }, n.valueWillMutate = function () {
                    n.notifySubscribers(i, "beforeChange")
                }, m.exportProperty(n, "peek", n.peek), m.exportProperty(n, "valueHasMutated", n.valueHasMutated), m.exportProperty(n, "valueWillMutate", n.valueWillMutate), n
            }, m.observable.fn = {equalityComparer: u};
            var b = m.observable.protoProperty = "__ko_proto__";
            m.observable.fn[b] = m.observable, m.utils.canSetPrototype && m.utils.setPrototypeOf(m.observable.fn, m.subscribable.fn), m.hasPrototype = function (e, n) {
                return null === e || e === t || e[b] === t ? !1 : e[b] === n ? !0 : m.hasPrototype(e[b], n)
            }, m.isObservable = function (e) {
                return m.hasPrototype(e, m.observable)
            }, m.isWriteableObservable = function (e) {
                return "function" == typeof e && e[b] === m.observable ? !0 : "function" == typeof e && e[b] === m.dependentObservable && e.hasWriteFunction ? !0 : !1
            }, m.exportSymbol("observable", m.observable), m.exportSymbol("isObservable", m.isObservable), m.exportSymbol("isWriteableObservable", m.isWriteableObservable), m.exportSymbol("isWritableObservable", m.isWriteableObservable), m.observableArray = function (e) {
                if (e = e || [], "object" != typeof e || !("length"in e))throw new Error("The argument passed when initializing an observable array must be an array, or null, or undefined.");
                var t = m.observable(e);
                return m.utils.setPrototypeOfOrExtend(t, m.observableArray.fn), t.extend({trackArrayChanges: !0})
            }, m.observableArray.fn = {
                remove: function (e) {
                    for (var t = this.peek(), n = [], i = "function" != typeof e || m.isObservable(e) ? function (t) {
                        return t === e
                    } : e, r = 0; r < t.length; r++) {
                        var o = t[r];
                        i(o) && (0 === n.length && this.valueWillMutate(), n.push(o), t.splice(r, 1), r--)
                    }
                    return n.length && this.valueHasMutated(), n
                }, removeAll: function (e) {
                    if (e === t) {
                        var n = this.peek(), i = n.slice(0);
                        return this.valueWillMutate(), n.splice(0, n.length), this.valueHasMutated(), i
                    }
                    return e ? this.remove(function (t) {
                        return m.utils.arrayIndexOf(e, t) >= 0
                    }) : []
                }, destroy: function (e) {
                    var t = this.peek(), n = "function" != typeof e || m.isObservable(e) ? function (t) {
                        return t === e
                    } : e;
                    this.valueWillMutate();
                    for (var i = t.length - 1; i >= 0; i--) {
                        var r = t[i];
                        n(r) && (t[i]._destroy = !0)
                    }
                    this.valueHasMutated()
                }, destroyAll: function (e) {
                    return e === t ? this.destroy(function () {
                        return !0
                    }) : e ? this.destroy(function (t) {
                        return m.utils.arrayIndexOf(e, t) >= 0
                    }) : []
                }, indexOf: function (e) {
                    var t = this();
                    return m.utils.arrayIndexOf(t, e)
                }, replace: function (e, t) {
                    var n = this.indexOf(e);
                    n >= 0 && (this.valueWillMutate(), this.peek()[n] = t, this.valueHasMutated())
                }
            }, m.utils.arrayForEach(["pop", "push", "reverse", "shift", "sort", "splice", "unshift"], function (e) {
                m.observableArray.fn[e] = function () {
                    var t = this.peek();
                    this.valueWillMutate(), this.cacheDiffForKnownOperation(t, e, arguments);
                    var n = t[e].apply(t, arguments);
                    return this.valueHasMutated(), n
                }
            }), m.utils.arrayForEach(["slice"], function (e) {
                m.observableArray.fn[e] = function () {
                    var t = this();
                    return t[e].apply(t, arguments)
                }
            }), m.utils.canSetPrototype && m.utils.setPrototypeOf(m.observableArray.fn, m.observable.fn), m.exportSymbol("observableArray", m.observableArray);
            var x = "arrayChange";
            m.extenders.trackArrayChanges = function (e) {
                function t() {
                    if (!i) {
                        i = !0;
                        var t = e.notifySubscribers;
                        e.notifySubscribers = function (e, n) {
                            return n && n !== v || ++o, t.apply(this, arguments)
                        };
                        var a = [].concat(e.peek() || []);
                        r = null, e.subscribe(function (t) {
                            if (t = [].concat(t || []), e.hasSubscriptionsForEvent(x)) {
                                var i = n(a, t);
                                i.length && e.notifySubscribers(i, x)
                            }
                            a = t, r = null, o = 0
                        })
                    }
                }

                function n(e, t) {
                    return (!r || o > 1) && (r = m.utils.compareArrays(e, t, {sparse: !0})), r
                }

                if (!e.cacheDiffForKnownOperation) {
                    var i = !1, r = null, o = 0, a = e.subscribe;
                    e.subscribe = e.subscribe = function (e, n, i) {
                        return i === x && t(), a.apply(this, arguments)
                    }, e.cacheDiffForKnownOperation = function (e, t, n) {
                        function a(e, t, n) {
                            return s[s.length] = {status: e, value: t, index: n}
                        }

                        if (i && !o) {
                            var s = [], l = e.length, u = n.length, c = 0;
                            switch (t) {
                                case"push":
                                    c = l;
                                case"unshift":
                                    for (var d = 0; u > d; d++)a("added", n[d], c + d);
                                    break;
                                case"pop":
                                    c = l - 1;
                                case"shift":
                                    l && a("deleted", e[c], c);
                                    break;
                                case"splice":
                                    for (var p = Math.min(Math.max(0, n[0] < 0 ? l + n[0] : n[0]), l), f = 1 === u ? l : Math.min(p + (n[1] || 0), l), h = p + u - 2, g = Math.max(f, h), v = [], y = [], d = p, b = 2; g > d; ++d, ++b)f > d && y.push(a("deleted", e[d], d)), h > d && v.push(a("added", n[b], d));
                                    m.utils.findMovesInArrayComparison(y, v);
                                    break;
                                default:
                                    return
                            }
                            r = s
                        }
                    }
                }
            }, m.computed = m.dependentObservable = function (n, i, r) {
                function o(e, t) {
                    C[t] || (C[t] = e.subscribe(l), ++D)
                }

                function a() {
                    m.utils.objectForEach(C, function (e, t) {
                        t.dispose()
                    }), C = {}
                }

                function s() {
                    a(), D = 0, y = !0, h = !1
                }

                function l() {
                    var e = c.throttleEvaluation;
                    e && e >= 0 ? (clearTimeout(N), N = setTimeout(u, e)) : c._evalRateLimited ? c._evalRateLimited() : u()
                }

                function u(n) {
                    if (g) {
                        if (x)throw Error("A 'pure' computed must not be called recursively")
                    } else if (!y) {
                        if (_ && _()) {
                            if (!v)return void R()
                        } else v = !1;
                        if (g = !0, w)try {
                            var r = {};
                            m.dependencyDetection.begin({
                                callback: function (e, t) {
                                    r[t] || (r[t] = 1, ++D)
                                }, computed: c, isInitial: t
                            }), D = 0, f = b.call(i)
                        } finally {
                            m.dependencyDetection.end(), g = !1
                        } else try {
                            var a = C, s = D;
                            m.dependencyDetection.begin({
                                callback: function (e, t) {
                                    y || (s && a[t] ? (C[t] = a[t], ++D, delete a[t], --s) : o(e, t))
                                }, computed: c, isInitial: x ? t : !D
                            }), C = {}, D = 0;
                            try {
                                var l = i ? b.call(i) : b()
                            } finally {
                                m.dependencyDetection.end(), s && m.utils.objectForEach(a, function (e, t) {
                                    t.dispose()
                                }), h = !1
                            }
                            c.isDifferent(f, l) && (c.notifySubscribers(f, "beforeChange"), f = l, e && (c._latestValue = f), n !== !0 && c.notifySubscribers(f))
                        } finally {
                            g = !1
                        }
                        D || R()
                    }
                }

                function c() {
                    if (arguments.length > 0) {
                        if ("function" != typeof E)throw new Error("Cannot write a value to a ko.computed unless you specify a 'write' option. If you wish to read the current value, don't pass any parameters.");
                        return E.apply(i, arguments), this
                    }
                    return m.dependencyDetection.registerDependency(c), h && u(!0), f
                }

                function d() {
                    return h && !D && u(!0), f
                }

                function p() {
                    return h || D > 0
                }

                var f, h = !0, g = !1, v = !1, y = !1, b = n, x = !1, w = !1;
                if (b && "object" == typeof b ? (r = b, b = r.read) : (r = r || {}, b || (b = r.read)), "function" != typeof b)throw new Error("Pass a function that returns the value of the ko.computed");
                var E = r.write, T = r.disposeWhenNodeIsRemoved || r.disposeWhenNodeIsRemoved || null, S = r.disposeWhen || r.disposeWhen, _ = S, R = s, C = {}, D = 0, N = null;
                i || (i = r.owner), m.subscribable.call(c), m.utils.setPrototypeOfOrExtend(c, m.dependentObservable.fn), c.peek = d, c.getDependenciesCount = function () {
                    return D
                }, c.hasWriteFunction = "function" == typeof r.write, c.dispose = function () {
                    R()
                }, c.isActive = p;
                var O = c.limit;
                return c.limit = function (e) {
                    O.call(c, e), c._evalRateLimited = function () {
                        c._rateLimitedBeforeChange(f), h = !0, c._rateLimitedChange(c)
                    }
                }, r.pure ? (x = !0, w = !0, c.beforeSubscriptionAdd = function () {
                    w && (w = !1, u(!0))
                }, c.afterSubscriptionRemove = function () {
                    c.getSubscriptionsCount() || (a(), w = h = !0)
                }) : r.deferEvaluation && (c.beforeSubscriptionAdd = function () {
                    d(), delete c.beforeSubscriptionAdd
                }), m.exportProperty(c, "peek", c.peek), m.exportProperty(c, "dispose", c.dispose), m.exportProperty(c, "isActive", c.isActive), m.exportProperty(c, "getDependenciesCount", c.getDependenciesCount), T && (v = !0, T.nodeType && (_ = function () {
                    return !m.utils.domNodeIsAttachedToDocument(T) || S && S()
                })), w || r.deferEvaluation || u(), T && p() && T.nodeType && (R = function () {
                    m.utils.domNodeDisposal.removeDisposeCallback(T, R), s()
                }, m.utils.domNodeDisposal.addDisposeCallback(T, R)), c
            }, m.isComputed = function (e) {
                return m.hasPrototype(e, m.dependentObservable)
            };
            var w = m.observable.protoProperty;
            m.dependentObservable[w] = m.observable, m.dependentObservable.fn = {equalityComparer: u}, m.dependentObservable.fn[w] = m.dependentObservable, m.utils.canSetPrototype && m.utils.setPrototypeOf(m.dependentObservable.fn, m.subscribable.fn), m.exportSymbol("dependentObservable", m.dependentObservable), m.exportSymbol("computed", m.dependentObservable), m.exportSymbol("isComputed", m.isComputed), m.pureComputed = function (e, t) {
                return "function" == typeof e ? m.computed(e, t, {pure: !0}) : (e = m.utils.extend({}, e), e.pure = !0, m.computed(e, t))
            }, m.exportSymbol("pureComputed", m.pureComputed), function () {
                function e(r, o, a) {
                    a = a || new i, r = o(r);
                    var s = !("object" != typeof r || null === r || r === t || r instanceof Date || r instanceof String || r instanceof Number || r instanceof Boolean);
                    if (!s)return r;
                    var l = r instanceof Array ? [] : {};
                    return a.save(r, l), n(r, function (n) {
                        var i = o(r[n]);
                        switch (typeof i) {
                            case"boolean":
                            case"number":
                            case"string":
                            case"function":
                                l[n] = i;
                                break;
                            case"object":
                            case"undefined":
                                var s = a.get(i);
                                l[n] = s !== t ? s : e(i, o, a)
                        }
                    }), l
                }

                function n(e, t) {
                    if (e instanceof Array) {
                        for (var n = 0; n < e.length; n++)t(n);
                        "function" == typeof e.toJSON && t("toJSON")
                    } else for (var i in e)t(i)
                }

                function i() {
                    this.keys = [], this.values = []
                }

                var r = 10;
                m.toJS = function (t) {
                    if (0 == arguments.length)throw new Error("When calling ko.toJS, pass the object you want to convert.");
                    return e(t, function (e) {
                        for (var t = 0; m.isObservable(e) && r > t; t++)e = e();
                        return e
                    })
                }, m.toJSON = function (e, t, n) {
                    var i = m.toJS(e);
                    return m.utils.stringifyJson(i, t, n)
                }, i.prototype = {
                    constructor: i, save: function (e, t) {
                        var n = m.utils.arrayIndexOf(this.keys, e);
                        n >= 0 ? this.values[n] = t : (this.keys.push(e), this.values.push(t))
                    }, get: function (e) {
                        var n = m.utils.arrayIndexOf(this.keys, e);
                        return n >= 0 ? this.values[n] : t
                    }
                }
            }(), m.exportSymbol("toJS", m.toJS), m.exportSymbol("toJSON", m.toJSON), function () {
                var e = "__ko__hasDomDataOptionValue__";
                m.selectExtensions = {
                    readValue: function (n) {
                        switch (m.utils.tagNameLower(n)) {
                            case"option":
                                return n[e] === !0 ? m.utils.domData.get(n, m.bindingHandlers.options.optionValueDomDataKey) : m.utils.ieVersion <= 7 ? n.getAttributeNode("value") && n.getAttributeNode("value").specified ? n.value : n.text : n.value;
                            case"select":
                                return n.selectedIndex >= 0 ? m.selectExtensions.readValue(n.options[n.selectedIndex]) : t;
                            default:
                                return n.value
                        }
                    }, writeValue: function (n, i, r) {
                        switch (m.utils.tagNameLower(n)) {
                            case"option":
                                switch (typeof i) {
                                    case"string":
                                        m.utils.domData.set(n, m.bindingHandlers.options.optionValueDomDataKey, t), e in n && delete n[e], n.value = i;
                                        break;
                                    default:
                                        m.utils.domData.set(n, m.bindingHandlers.options.optionValueDomDataKey, i), n[e] = !0, n.value = "number" == typeof i ? i : ""
                                }
                                break;
                            case"select":
                                ("" === i || null === i) && (i = t);
                                for (var o, a = -1, s = 0, l = n.options.length; l > s; ++s)if (o = m.selectExtensions.readValue(n.options[s]), o == i || "" == o && i === t) {
                                    a = s;
                                    break
                                }
                                (r || a >= 0 || i === t && n.size > 1) && (n.selectedIndex = a);
                                break;
                            default:
                                (null === i || i === t) && (i = ""), n.value = i
                        }
                    }
                }
            }(), m.exportSymbol("selectExtensions", m.selectExtensions), m.exportSymbol("selectExtensions.readValue", m.selectExtensions.readValue), m.exportSymbol("selectExtensions.writeValue", m.selectExtensions.writeValue), m.expressionRewriting = function () {
                function e(e) {
                    if (m.utils.arrayIndexOf(i, e) >= 0)return !1;
                    var t = e.match(r);
                    return null === t ? !1 : t[1] ? "Object(" + t[1] + ")" + t[2] : e
                }

                function t(e) {
                    var t = m.utils.stringTrim(e);
                    123 === t.charCodeAt(0) && (t = t.slice(1, -1));
                    var n, i, r = [], o = t.match(d), a = 0;
                    if (o) {
                        o.push(",");
                        for (var s, l = 0; s = o[l]; ++l) {
                            var u = s.charCodeAt(0);
                            if (44 === u) {
                                if (0 >= a) {
                                    n && r.push(i ? {key: n, value: i.join("")} : {unknown: n}), n = i = a = 0;
                                    continue
                                }
                            } else if (58 === u) {
                                if (!i)continue
                            } else if (47 === u && l && s.length > 1) {
                                var c = o[l - 1].match(p);
                                c && !f[c[0]] && (t = t.substr(t.indexOf(s) + 1), o = t.match(d), o.push(","), l = -1, s = "/")
                            } else if (40 === u || 123 === u || 91 === u)++a; else if (41 === u || 125 === u || 93 === u)--a; else if (!n && !i) {
                                n = 34 === u || 39 === u ? s.slice(1, -1) : s;
                                continue
                            }
                            i ? i.push(s) : i = [s]
                        }
                    }
                    return r
                }

                function n(n, i) {
                    function r(t, n) {
                        function i(e) {
                            return e && e.preprocess ? n = e.preprocess(n, t, r) : !0
                        }

                        var u;
                        if (!l) {
                            if (!i(m.getBindingHandler(t)))return;
                            h[t] && (u = e(n)) && a.push("'" + t + "':function(_z){" + u + "=_z}")
                        }
                        s && (n = "function(){return " + n + " }"), o.push("'" + t + "':" + n)
                    }

                    i = i || {};
                    var o = [], a = [], s = i.valueAccessors, l = i.bindingParams, u = "string" == typeof n ? t(n) : n;
                    return m.utils.arrayForEach(u, function (e) {
                        r(e.key || e.unknown, e.value)
                    }), a.length && r("_ko_property_writers", "{" + a.join(",") + " }"), o.join(",")
                }

                var i = ["true", "false", "null", "undefined"], r = /^(?:[$_a-z][$\w]*|(.+)(\.\s*[$_a-z][$\w]*|\[.+\]))$/i, o = '"(?:[^"\\\\]|\\\\.)*"', a = "'(?:[^'\\\\]|\\\\.)*'", s = "/(?:[^/\\\\]|\\\\.)*/w*", l = ",\"'{}()/:[\\]", u = "[^\\s:,/][^" + l + "]*[^\\s" + l + "]", c = "[^\\s]", d = RegExp(o + "|" + a + "|" + s + "|" + u + "|" + c, "g"), p = /[\])"'A-Za-z0-9_$]+$/, f = {
                    "in": 1,
                    "return": 1,
                    "typeof": 1
                }, h = {};
                return {
                    bindingRewriteValidators: [],
                    twoWayBindings: h,
                    parseObjectLiteral: t,
                    preProcessBindings: n,
                    keyValueArrayContainsKey: function (e, t) {
                        for (var n = 0; n < e.length; n++)if (e[n].key == t)return !0;
                        return !1
                    },
                    writeValueToProperty: function (e, t, n, i, r) {
                        if (e && m.isObservable(e))!m.isWriteableObservable(e) || r && e.peek() === i || e(i); else {
                            var o = t.get("_ko_property_writers");
                            o && o[n] && o[n](i)
                        }
                    }
                }
            }(), m.exportSymbol("expressionRewriting", m.expressionRewriting), m.exportSymbol("expressionRewriting.bindingRewriteValidators", m.expressionRewriting.bindingRewriteValidators), m.exportSymbol("expressionRewriting.parseObjectLiteral", m.expressionRewriting.parseObjectLiteral), m.exportSymbol("expressionRewriting.preProcessBindings", m.expressionRewriting.preProcessBindings), m.exportSymbol("expressionRewriting._twoWayBindings", m.expressionRewriting.twoWayBindings), m.exportSymbol("jsonExpressionRewriting", m.expressionRewriting), m.exportSymbol("jsonExpressionRewriting.insertPropertyAccessorsIntoJson", m.expressionRewriting.preProcessBindings), function () {
                function e(e) {
                    return 8 == e.nodeType && s.test(a ? e.text : e.nodeValue)
                }

                function t(e) {
                    return 8 == e.nodeType && l.test(a ? e.text : e.nodeValue)
                }

                function n(n, i) {
                    for (var r = n, o = 1, a = []; r = r.nextSibling;) {
                        if (t(r) && (o--, 0 === o))return a;
                        a.push(r), e(r) && o++
                    }
                    if (!i)throw new Error("Cannot find closing comment tag to match: " + n.nodeValue);
                    return null
                }

                function r(e, t) {
                    var i = n(e, t);
                    return i ? i.length > 0 ? i[i.length - 1].nextSibling : e.nextSibling : null
                }

                function o(n) {
                    var i = n.firstChild, o = null;
                    if (i)do if (o)o.push(i); else if (e(i)) {
                        var a = r(i, !0);
                        a ? i = a : o = [i]
                    } else t(i) && (o = [i]); while (i = i.nextSibling);
                    return o
                }

                var a = i && "<!--test-->" === i.createComment("test").text, s = a ? /^<!--\s*ko(?:\s+([\s\S]+))?\s*-->$/ : /^\s*ko(?:\s+([\s\S]+))?\s*$/, l = a ? /^<!--\s*\/ko\s*-->$/ : /^\s*\/ko\s*$/, u = {
                    ul: !0,
                    ol: !0
                };
                m.virtualElements = {
                    allowedBindings: {}, childNodes: function (t) {
                        return e(t) ? n(t) : t.childNodes
                    }, emptyNode: function (t) {
                        if (e(t))for (var n = m.virtualElements.childNodes(t), i = 0, r = n.length; r > i; i++)m.removeNode(n[i]); else m.utils.emptyDomNode(t)
                    }, setDomNodeChildren: function (t, n) {
                        if (e(t)) {
                            m.virtualElements.emptyNode(t);
                            for (var i = t.nextSibling, r = 0, o = n.length; o > r; r++)i.parentNode.insertBefore(n[r], i)
                        } else m.utils.setDomNodeChildren(t, n)
                    }, prepend: function (t, n) {
                        e(t) ? t.parentNode.insertBefore(n, t.nextSibling) : t.firstChild ? t.insertBefore(n, t.firstChild) : t.appendChild(n)
                    }, insertAfter: function (t, n, i) {
                        i ? e(t) ? t.parentNode.insertBefore(n, i.nextSibling) : i.nextSibling ? t.insertBefore(n, i.nextSibling) : t.appendChild(n) : m.virtualElements.prepend(t, n)
                    }, firstChild: function (n) {
                        return e(n) ? !n.nextSibling || t(n.nextSibling) ? null : n.nextSibling : n.firstChild
                    }, nextSibling: function (n) {
                        return e(n) && (n = r(n)), n.nextSibling && t(n.nextSibling) ? null : n.nextSibling
                    }, hasBindingValue: e, virtualNodeBindingValue: function (e) {
                        var t = (a ? e.text : e.nodeValue).match(s);
                        return t ? t[1] : null
                    }, normaliseVirtualElementDomStructure: function (e) {
                        if (u[m.utils.tagNameLower(e)]) {
                            var t = e.firstChild;
                            if (t)do if (1 === t.nodeType) {
                                var n = o(t);
                                if (n)for (var i = t.nextSibling, r = 0; r < n.length; r++)i ? e.insertBefore(n[r], i) : e.appendChild(n[r])
                            } while (t = t.nextSibling)
                        }
                    }
                }
            }(), m.exportSymbol("virtualElements", m.virtualElements), m.exportSymbol("virtualElements.allowedBindings", m.virtualElements.allowedBindings), m.exportSymbol("virtualElements.emptyNode", m.virtualElements.emptyNode), m.exportSymbol("virtualElements.insertAfter", m.virtualElements.insertAfter), m.exportSymbol("virtualElements.prepend", m.virtualElements.prepend), m.exportSymbol("virtualElements.setDomNodeChildren", m.virtualElements.setDomNodeChildren), function () {
                function e(e, n, i) {
                    var r = e + (i && i.valueAccessors || "");
                    return n[r] || (n[r] = t(e, i))
                }

                function t(e, t) {
                    var n = m.expressionRewriting.preProcessBindings(e, t), i = "with($context){with($data||{}){return{" + n + "}}}";
                    return new Function("$context", "$element", i)
                }

                var n = "data-bind";
                m.bindingProvider = function () {
                    this.bindingCache = {}
                }, m.utils.extend(m.bindingProvider.prototype, {
                    nodeHasBindings: function (e) {
                        switch (e.nodeType) {
                            case 1:
                                return null != e.getAttribute(n) || m.components.getComponentNameForNode(e);
                            case 8:
                                return m.virtualElements.hasBindingValue(e);
                            default:
                                return !1
                        }
                    }, getBindings: function (e, t) {
                        var n = this.getBindingsString(e, t), i = n ? this.parseBindingsString(n, t, e) : null;
                        return m.components.addBindingsForCustomElement(i, e, t, !1)
                    }, getBindingAccessors: function (e, t) {
                        var n = this.getBindingsString(e, t), i = n ? this.parseBindingsString(n, t, e, {valueAccessors: !0}) : null;
                        return m.components.addBindingsForCustomElement(i, e, t, !0)
                    }, getBindingsString: function (e) {
                        switch (e.nodeType) {
                            case 1:
                                return e.getAttribute(n);
                            case 8:
                                return m.virtualElements.virtualNodeBindingValue(e);
                            default:
                                return null
                        }
                    }, parseBindingsString: function (t, n, i, r) {
                        try {
                            var o = e(t, this.bindingCache, r);
                            return o(n, i)
                        } catch (a) {
                            throw a.message = "Unable to parse bindings.\nBindings value: " + t + "\nMessage: " + a.message, a
                        }
                    }
                }), m.bindingProvider.instance = new m.bindingProvider
            }(), m.exportSymbol("bindingProvider", m.bindingProvider), function () {
                function e(e) {
                    return function () {
                        return e
                    }
                }

                function i(e) {
                    return e()
                }

                function r(e) {
                    return m.utils.objectMap(m.dependencyDetection.ignore(e), function (t, n) {
                        return function () {
                            return e()[n]
                        }
                    })
                }

                function a(t, n, i) {
                    return "function" == typeof t ? r(t.bind(null, n, i)) : m.utils.objectMap(t, e)
                }

                function s(e, t) {
                    return r(this.getBindings.bind(this, e, t))
                }

                function l(e) {
                    var t = m.virtualElements.allowedBindings[e];
                    if (!t)throw new Error("The binding '" + e + "' cannot be used with virtual elements")
                }

                function u(e, t, n) {
                    var i, r = m.virtualElements.firstChild(t), o = m.bindingProvider.instance, a = o.preprocessNode;
                    if (a) {
                        for (; i = r;)r = m.virtualElements.nextSibling(i), a.call(o, i);
                        r = m.virtualElements.firstChild(t)
                    }
                    for (; i = r;)r = m.virtualElements.nextSibling(i), c(e, i, n)
                }

                function c(e, t, n) {
                    var i = !0, r = 1 === t.nodeType;
                    r && m.virtualElements.normaliseVirtualElementDomStructure(t);
                    var o = r && n || m.bindingProvider.instance.nodeHasBindings(t);
                    o && (i = p(t, null, e, n).shouldBindDescendants), i && !h[m.utils.tagNameLower(t)] && u(e, t, !r)
                }

                function d(e) {
                    var t = [], n = {}, i = [];
                    return m.utils.objectForEach(e, function r(o) {
                        if (!n[o]) {
                            var a = m.getBindingHandler(o);
                            a && (a.after && (i.push(o), m.utils.arrayForEach(a.after, function (t) {
                                if (e[t]) {
                                    if (-1 !== m.utils.arrayIndexOf(i, t))throw Error("Cannot combine the following bindings, because they have a cyclic dependency: " + i.join(", "));
                                    r(t)
                                }
                            }), i.length--), t.push({key: o, handler: a})), n[o] = !0
                        }
                    }), t
                }

                function p(e, n, r, o) {
                    function a() {
                        return m.utils.objectMap(h ? h() : c, i)
                    }

                    var u = m.utils.domData.get(e, g);
                    if (!n) {
                        if (u)throw Error("You cannot apply bindings multiple times to the same element.");
                        m.utils.domData.set(e, g, !0)
                    }
                    !u && o && m.storedBindingContextForNode(e, r);
                    var c;
                    if (n && "function" != typeof n)c = n; else {
                        var p = m.bindingProvider.instance, f = p.getBindingAccessors || s, h = m.dependentObservable(function () {
                            return c = n ? n(r, e) : f.call(p, e, r), c && r._subscribable && r._subscribable(), c
                        }, null, {disposeWhenNodeIsRemoved: e});
                        c && h.isActive() || (h = null)
                    }
                    var v;
                    if (c) {
                        var y = h ? function (e) {
                            return function () {
                                return i(h()[e])
                            }
                        } : function (e) {
                            return c[e]
                        };
                        a.get = function (e) {
                            return c[e] && i(y(e))
                        }, a.has = function (e) {
                            return e in c
                        };
                        var b = d(c);
                        m.utils.arrayForEach(b, function (n) {
                            var i = n.handler.init, o = n.handler.update, s = n.key;
                            8 === e.nodeType && l(s);
                            try {
                                "function" == typeof i && m.dependencyDetection.ignore(function () {
                                    var n = i(e, y(s), a, r.$data, r);
                                    if (n && n.controlsDescendantBindings) {
                                        if (v !== t)throw new Error("Multiple bindings (" + v + " and " + s + ") are trying to control descendant bindings of the same element. You cannot use these bindings together on the same element.");
                                        v = s
                                    }
                                }), "function" == typeof o && m.dependentObservable(function () {
                                    o(e, y(s), a, r.$data, r)
                                }, null, {disposeWhenNodeIsRemoved: e})
                            } catch (u) {
                                throw u.message = 'Unable to process binding "' + s + ": " + c[s] + '"\nMessage: ' + u.message, u
                            }
                        })
                    }
                    return {shouldBindDescendants: v === t}
                }

                function f(e) {
                    return e && e instanceof m.bindingContext ? e : new m.bindingContext(e)
                }

                m.bindingHandlers = {};
                var h = {script: !0};
                m.getBindingHandler = function (e) {
                    return m.bindingHandlers[e]
                }, m.bindingContext = function (e, n, i, r) {
                    function o() {
                        var t = u ? e() : e, o = m.utils.unwrapObservable(t);
                        return n ? (n._subscribable && n._subscribable(), m.utils.extend(l, n), c && (l._subscribable = c)) : (l.$parents = [], l.$root = o, l.ko = m), l.$rawData = t, l.$data = o, i && (l[i] = o), r && r(l, n, o), l.$data
                    }

                    function a() {
                        return s && !m.utils.anyDomNodeIsAttachedToDocument(s)
                    }

                    var s, l = this, u = "function" == typeof e && !m.isObservable(e), c = m.dependentObservable(o, null, {
                        disposeWhen: a,
                        disposeWhenNodeIsRemoved: !0
                    });
                    c.isActive() && (l._subscribable = c, c.equalityComparer = null, s = [], c._addNode = function (e) {
                        s.push(e), m.utils.domNodeDisposal.addDisposeCallback(e, function (e) {
                            m.utils.arrayRemoveItem(s, e), s.length || (c.dispose(), l._subscribable = c = t)
                        })
                    })
                }, m.bindingContext.prototype.createChildContext = function (e, t, n) {
                    return new m.bindingContext(e, this, t, function (e, t) {
                        e.$parentContext = t, e.$parent = t.$data, e.$parents = (t.$parents || []).slice(0), e.$parents.unshift(e.$parent), n && n(e)
                    })
                }, m.bindingContext.prototype.extend = function (e) {
                    return new m.bindingContext(this._subscribable || this.$data, this, null, function (t, n) {
                        t.$rawData = n.$rawData, m.utils.extend(t, "function" == typeof e ? e() : e)
                    })
                };
                var g = m.utils.domData.nextKey(), v = m.utils.domData.nextKey();
                m.storedBindingContextForNode = function (e, t) {
                    return 2 != arguments.length ? m.utils.domData.get(e, v) : (m.utils.domData.set(e, v, t), void(t._subscribable && t._subscribable._addNode(e)))
                }, m.applyBindingAccessorsToNode = function (e, t, n) {
                    return 1 === e.nodeType && m.virtualElements.normaliseVirtualElementDomStructure(e), p(e, t, f(n), !0)
                }, m.applyBindingsToNode = function (e, t, n) {
                    var i = f(n);
                    return m.applyBindingAccessorsToNode(e, a(t, i, e), i)
                }, m.applyBindingsToDescendants = function (e, t) {
                    (1 === t.nodeType || 8 === t.nodeType) && u(f(e), t, !0)
                }, m.applyBindings = function (e, t) {
                    if (!o && n.jQuery && (o = n.jQuery), t && 1 !== t.nodeType && 8 !== t.nodeType)throw new Error("ko.applyBindings: first parameter should be your view model; second parameter should be a DOM node");
                    t = t || n.document.body, c(f(e), t, !0)
                }, m.contextFor = function (e) {
                    switch (e.nodeType) {
                        case 1:
                        case 8:
                            var n = m.storedBindingContextForNode(e);
                            if (n)return n;
                            if (e.parentNode)return m.contextFor(e.parentNode)
                    }
                    return t
                }, m.dataFor = function (e) {
                    var n = m.contextFor(e);
                    return n ? n.$data : t
                }, m.exportSymbol("bindingHandlers", m.bindingHandlers), m.exportSymbol("applyBindings", m.applyBindings), m.exportSymbol("applyBindingsToDescendants", m.applyBindingsToDescendants), m.exportSymbol("applyBindingAccessorsToNode", m.applyBindingAccessorsToNode), m.exportSymbol("applyBindingsToNode", m.applyBindingsToNode), m.exportSymbol("contextFor", m.contextFor), m.exportSymbol("dataFor", m.dataFor)
            }(), function (e) {
                function t(t, n) {
                    return t.hasOwnProperty(n) ? t[n] : e
                }

                function n(e, n) {
                    var r, s = t(o, e);
                    s || (s = o[e] = new m.subscribable, i(e, function (t) {
                        a[e] = t, delete o[e], r ? s.notifySubscribers(t) : setTimeout(function () {
                            s.notifySubscribers(t)
                        }, 0)
                    }), r = !0), s.subscribe(n)
                }

                function i(e, t) {
                    r("getConfig", [e], function (n) {
                        n ? r("loadComponent", [e, n], function (e) {
                            t(e)
                        }) : t(null)
                    })
                }

                function r(t, n, i, o) {
                    o || (o = m.components.loaders.slice(0));
                    var a = o.shift();
                    if (a) {
                        var s = a[t];
                        if (s) {
                            var l = !1, u = s.apply(a, n.concat(function (e) {
                                l ? i(null) : null !== e ? i(e) : r(t, n, i, o)
                            }));
                            if (u !== e && (l = !0, !a.suppressLoaderExceptions))throw new Error("Component loaders must supply values by invoking the callback, not by returning values synchronously.")
                        } else r(t, n, i, o)
                    } else i(null)
                }

                var o = {}, a = {};
                m.components = {
                    get: function (e, i) {
                        var r = t(a, e);
                        r ? setTimeout(function () {
                            i(r)
                        }, 0) : n(e, i)
                    }, clearCachedDefinition: function (e) {
                        delete a[e]
                    }, _getFirstResultFromLoaders: r
                }, m.components.loaders = [], m.exportSymbol("components", m.components), m.exportSymbol("components.get", m.components.get), m.exportSymbol("components.clearCachedDefinition", m.components.clearCachedDefinition)
            }(), function () {
                function e(e, t, n, i) {
                    var r = {}, o = 2, a = function () {
                        0 === --o && i(r)
                    }, s = n.template, l = n.viewModel;
                    s ? u(t, s, function (t) {
                        m.components._getFirstResultFromLoaders("loadTemplate", [e, t], function (e) {
                            r.template = e, a()
                        })
                    }) : a(), l ? u(t, l, function (t) {
                        m.components._getFirstResultFromLoaders("loadViewModel", [e, t], function (e) {
                            r[p] = e, a()
                        })
                    }) : a()
                }

                function t(e, t, n) {
                    if ("string" == typeof t)n(m.utils.parseHtmlFragment(t)); else if (t instanceof Array)n(t); else if (s(t))n(m.utils.makeArray(t.childNodes)); else if (t.element) {
                        var r = t.element;
                        if (a(r))n(o(r)); else if ("string" == typeof r) {
                            var l = i.getElementById(r);
                            l ? n(o(l)) : e("Cannot find element with ID " + r)
                        } else e("Unknown element type: " + r)
                    } else e("Unknown template value: " + t)
                }

                function r(e, t, n) {
                    if ("function" == typeof t)n(function (e) {
                        return new t(e)
                    }); else if ("function" == typeof t[p])n(t[p]); else if ("instance"in t) {
                        var i = t.instance;
                        n(function () {
                            return i
                        })
                    } else"viewModel"in t ? r(e, t.viewModel, n) : e("Unknown viewModel value: " + t)
                }

                function o(e) {
                    switch (m.utils.tagNameLower(e)) {
                        case"script":
                            return m.utils.parseHtmlFragment(e.text);
                        case"textarea":
                            return m.utils.parseHtmlFragment(e.value);
                        case"template":
                            if (s(e.content))return m.utils.cloneNodes(e.content.childNodes)
                    }
                    return m.utils.cloneNodes(e.childNodes)
                }

                function a(e) {
                    return n.HTMLElement ? e instanceof HTMLElement : e && e.tagName && 1 === e.nodeType
                }

                function s(e) {
                    return n.DocumentFragment ? e instanceof DocumentFragment : e && 11 === e.nodeType
                }

                function u(e, t, i) {
                    "string" == typeof t.require ? l || n.require ? (l || n.require)([t.require], i) : e("Uses require, but no AMD loader is present") : i(t)
                }

                function c(e) {
                    return function (t) {
                        throw new Error("Component '" + e + "': " + t)
                    }
                }

                var d = {};
                m.components.register = function (e, t) {
                    if (!t)throw new Error("Invalid configuration for " + e);
                    if (m.components.isRegistered(e))throw new Error("Component " + e + " is already registered");
                    d[e] = t
                }, m.components.isRegistered = function (e) {
                    return e in d
                }, m.components.unregister = function (e) {
                    delete d[e], m.components.clearCachedDefinition(e)
                }, m.components.defaultLoader = {
                    getConfig: function (e, t) {
                        var n = d.hasOwnProperty(e) ? d[e] : null;
                        t(n)
                    }, loadComponent: function (t, n, i) {
                        var r = c(t);
                        u(r, n, function (n) {
                            e(t, r, n, i)
                        })
                    }, loadTemplate: function (e, n, i) {
                        t(c(e), n, i)
                    }, loadViewModel: function (e, t, n) {
                        r(c(e), t, n)
                    }
                };
                var p = "createViewModel";
                m.exportSymbol("components.register", m.components.register), m.exportSymbol("components.isRegistered", m.components.isRegistered), m.exportSymbol("components.unregister", m.components.unregister), m.exportSymbol("components.defaultLoader", m.components.defaultLoader), m.components.loaders.push(m.components.defaultLoader), m.components._allRegisteredComponents = d
            }(), function () {
                function e(e, n) {
                    var i = e.getAttribute("params");
                    if (i) {
                        var r = t.parseBindingsString(i, n, e, {
                            valueAccessors: !0,
                            bindingParams: !0
                        }), o = m.utils.objectMap(r, function (t) {
                            return m.computed(t, null, {disposeWhenNodeIsRemoved: e})
                        }), a = m.utils.objectMap(o, function (t) {
                            return t.isActive() ? m.computed(function () {
                                return m.utils.unwrapObservable(t())
                            }, null, {disposeWhenNodeIsRemoved: e}) : t.peek()
                        });
                        return a.hasOwnProperty("$raw") || (a.$raw = o), a
                    }
                    return {$raw: {}}
                }

                m.components.getComponentNameForNode = function (e) {
                    var t = m.utils.tagNameLower(e);
                    return m.components.isRegistered(t) && t
                }, m.components.addBindingsForCustomElement = function (t, n, i, r) {
                    if (1 === n.nodeType) {
                        var o = m.components.getComponentNameForNode(n);
                        if (o) {
                            if (t = t || {}, t.component)throw new Error('Cannot use the "component" binding on a custom element matching a component');
                            var a = {name: o, params: e(n, i)};
                            t.component = r ? function () {
                                return a
                            } : a
                        }
                    }
                    return t
                };
                var t = new m.bindingProvider;
                m.utils.ieVersion < 9 && (m.components.register = function (e) {
                    return function (t) {
                        return i.createElement(t), e.apply(this, arguments)
                    }
                }(m.components.register), i.createDocumentFragment = function (e) {
                    return function () {
                        var t = e(), n = m.components._allRegisteredComponents;
                        for (var i in n)n.hasOwnProperty(i) && t.createElement(i);
                        return t
                    }
                }(i.createDocumentFragment))
            }(), function () {
                function e(e, t, n) {
                    var i = t.template;
                    if (!i)throw new Error("Component '" + e + "' has no template");
                    var r = m.utils.cloneNodes(i);
                    m.virtualElements.setDomNodeChildren(n, r)
                }

                function t(e, t, n) {
                    var i = e.createViewModel;
                    return i ? i.call(e, n, {element: t}) : n
                }

                var n = 0;
                m.bindingHandlers.component = {
                    init: function (i, r, o, a, s) {
                        var l, u, c = function () {
                            var e = l && l.dispose;
                            "function" == typeof e && e.call(l), u = null
                        };
                        return m.utils.domNodeDisposal.addDisposeCallback(i, c), m.computed(function () {
                            var o, a, d = m.utils.unwrapObservable(r());
                            if ("string" == typeof d ? o = d : (o = m.utils.unwrapObservable(d.name), a = m.utils.unwrapObservable(d.params)), !o)throw new Error("No component name specified");
                            var p = u = ++n;
                            m.components.get(o, function (n) {
                                if (u === p) {
                                    if (c(), !n)throw new Error("Unknown component '" + o + "'");
                                    e(o, n, i);
                                    var r = t(n, i, a), d = s.createChildContext(r);
                                    l = r, m.applyBindingsToDescendants(d, i)
                                }
                            })
                        }, null, {disposeWhenNodeIsRemoved: i}), {controlsDescendantBindings: !0}
                    }
                }, m.virtualElements.allowedBindings.component = !0
            }();
            var E = {"class": "className", "for": "htmlFor"};
            m.bindingHandlers.attr = {
                update: function (e, n) {
                    var i = m.utils.unwrapObservable(n()) || {};
                    m.utils.objectForEach(i, function (n, i) {
                        i = m.utils.unwrapObservable(i);
                        var r = i === !1 || null === i || i === t;
                        r && e.removeAttribute(n), m.utils.ieVersion <= 8 && n in E ? (n = E[n], r ? e.removeAttribute(n) : e[n] = i) : r || e.setAttribute(n, i.toString()), "name" === n && m.utils.setElementName(e, r ? "" : i.toString())
                    })
                }
            }, function () {
                m.bindingHandlers.checked = {
                    after: ["value", "attr"], init: function (e, n, i) {
                        function r() {
                            var t = e.checked, r = d ? a() : t;
                            if (!m.computedContext.isInitial() && (!l || t)) {
                                var o = m.dependencyDetection.ignore(n);
                                u ? c !== r ? (t && (m.utils.addOrRemoveItem(o, r, !0), m.utils.addOrRemoveItem(o, c, !1)), c = r) : m.utils.addOrRemoveItem(o, r, t) : m.expressionRewriting.writeValueToProperty(o, i, "checked", r, !0)
                            }
                        }

                        function o() {
                            var t = m.utils.unwrapObservable(n());
                            e.checked = u ? m.utils.arrayIndexOf(t, a()) >= 0 : s ? t : a() === t
                        }

                        var a = m.pureComputed(function () {
                            return i.has("checkedValue") ? m.utils.unwrapObservable(i.get("checkedValue")) : i.has("value") ? m.utils.unwrapObservable(i.get("value")) : e.value
                        }), s = "checkbox" == e.type, l = "radio" == e.type;
                        if (s || l) {
                            var u = s && m.utils.unwrapObservable(n())instanceof Array, c = u ? a() : t, d = l || u;
                            l && !e.name && m.bindingHandlers.uniqueName.init(e, function () {
                                return !0
                            }), m.computed(r, null, {disposeWhenNodeIsRemoved: e}), m.utils.registerEventHandler(e, "click", r), m.computed(o, null, {disposeWhenNodeIsRemoved: e})
                        }
                    }
                }, m.expressionRewriting.twoWayBindings.checked = !0, m.bindingHandlers.checkedValue = {
                    update: function (e, t) {
                        e.value = m.utils.unwrapObservable(t())
                    }
                }
            }();
            var T = "__ko__cssValue";
            m.bindingHandlers.css = {
                update: function (e, t) {
                    var n = m.utils.unwrapObservable(t());
                    "object" == typeof n ? m.utils.objectForEach(n, function (t, n) {
                        n = m.utils.unwrapObservable(n), m.utils.toggleDomNodeCssClass(e, t, n)
                    }) : (n = String(n || ""), m.utils.toggleDomNodeCssClass(e, e[T], !1), e[T] = n, m.utils.toggleDomNodeCssClass(e, n, !0))
                }
            }, m.bindingHandlers.enable = {
                update: function (e, t) {
                    var n = m.utils.unwrapObservable(t());
                    n && e.disabled ? e.removeAttribute("disabled") : n || e.disabled || (e.disabled = !0)
                }
            }, m.bindingHandlers.disable = {
                update: function (e, t) {
                    m.bindingHandlers.enable.update(e, function () {
                        return !m.utils.unwrapObservable(t())
                    })
                }
            }, m.bindingHandlers.event = {
                init: function (e, t, n, i, r) {
                    var o = t() || {};
                    m.utils.objectForEach(o, function (o) {
                        "string" == typeof o && m.utils.registerEventHandler(e, o, function (e) {
                            var a, s = t()[o];
                            if (s) {
                                try {
                                    var l = m.utils.makeArray(arguments);
                                    i = r.$data, l.unshift(i), a = s.apply(i, l)
                                } finally {
                                    a !== !0 && (e.preventDefault ? e.preventDefault() : e.returnValue = !1)
                                }
                                var u = n.get(o + "Bubble") !== !1;
                                u || (e.cancelBubble = !0, e.stopPropagation && e.stopPropagation())
                            }
                        })
                    })
                }
            }, m.bindingHandlers.foreach = {
                makeTemplateValueAccessor: function (e) {
                    return function () {
                        var t = e(), n = m.utils.peekObservable(t);
                        return n && "number" != typeof n.length ? (m.utils.unwrapObservable(t), {
                            foreach: n.data,
                            as: n.as,
                            includeDestroyed: n.includeDestroyed,
                            afterAdd: n.afterAdd,
                            beforeRemove: n.beforeRemove,
                            afterRender: n.afterRender,
                            beforeMove: n.beforeMove,
                            afterMove: n.afterMove,
                            templateEngine: m.nativeTemplateEngine.instance
                        }) : {foreach: t, templateEngine: m.nativeTemplateEngine.instance}
                    }
                }, init: function (e, t) {
                    return m.bindingHandlers.template.init(e, m.bindingHandlers.foreach.makeTemplateValueAccessor(t))
                }, update: function (e, t, n, i, r) {
                    return m.bindingHandlers.template.update(e, m.bindingHandlers.foreach.makeTemplateValueAccessor(t), n, i, r)
                }
            }, m.expressionRewriting.bindingRewriteValidators.foreach = !1, m.virtualElements.allowedBindings.foreach = !0;
            var S = "__ko_hasfocusUpdating", _ = "__ko_hasfocusLastValue";
            m.bindingHandlers.hasfocus = {
                init: function (e, t, n) {
                    var i = function (i) {
                        e[S] = !0;
                        var r = e.ownerDocument;
                        if ("activeElement"in r) {
                            var o;
                            try {
                                o = r.activeElement
                            } catch (a) {
                                o = r.body
                            }
                            i = o === e
                        }
                        var s = t();
                        m.expressionRewriting.writeValueToProperty(s, n, "hasfocus", i, !0), e[_] = i, e[S] = !1
                    }, r = i.bind(null, !0), o = i.bind(null, !1);
                    m.utils.registerEventHandler(e, "focus", r), m.utils.registerEventHandler(e, "focusin", r), m.utils.registerEventHandler(e, "blur", o), m.utils.registerEventHandler(e, "focusout", o)
                }, update: function (e, t) {
                    var n = !!m.utils.unwrapObservable(t());
                    e[S] || e[_] === n || (n ? e.focus() : e.blur(), m.dependencyDetection.ignore(m.utils.triggerEvent, null, [e, n ? "focusin" : "focusout"]))
                }
            }, m.expressionRewriting.twoWayBindings.hasfocus = !0, m.bindingHandlers.hasFocus = m.bindingHandlers.hasfocus, m.expressionRewriting.twoWayBindings.hasFocus = !0, m.bindingHandlers.html = {
                init: function () {
                    return {controlsDescendantBindings: !0}
                }, update: function (e, t) {
                    m.utils.setHtml(e, t())
                }
            }, h("if"), h("ifnot", !1, !0), h("with", !0, !1, function (e, t) {
                return e.createChildContext(t)
            });
            var R = {};
            m.bindingHandlers.options = {
                init: function (e) {
                    if ("select" !== m.utils.tagNameLower(e))throw new Error("options binding applies only to SELECT elements");
                    for (; e.length > 0;)e.remove(0);
                    return {controlsDescendantBindings: !0}
                }, update: function (e, n, i) {
                    function r() {
                        return m.utils.arrayFilter(e.options, function (e) {
                            return e.selected
                        })
                    }

                    function o(e, t, n) {
                        var i = typeof t;
                        return "function" == i ? t(e) : "string" == i ? e[t] : n
                    }

                    function a(n, r, a) {
                        a.length && (c = a[0].selected ? [m.selectExtensions.readValue(a[0])] : [], v = !0);
                        var s = e.ownerDocument.createElement("option");
                        if (n === R)m.utils.setTextContent(s, i.get("optionsCaption")), m.selectExtensions.writeValue(s, t); else {
                            var l = o(n, i.get("optionsValue"), n);
                            m.selectExtensions.writeValue(s, m.utils.unwrapObservable(l));
                            var u = o(n, i.get("optionsText"), l);
                            m.utils.setTextContent(s, u)
                        }
                        return [s]
                    }

                    function s(t, n) {
                        if (c.length) {
                            var i = m.utils.arrayIndexOf(c, m.selectExtensions.readValue(n[0])) >= 0;
                            m.utils.setOptionNodeSelectionState(n[0], i), v && !i && m.dependencyDetection.ignore(m.utils.triggerEvent, null, [e, "change"])
                        }
                    }

                    var l, u, c, d = 0 == e.length, p = !d && e.multiple ? e.scrollTop : null, f = m.utils.unwrapObservable(n()), h = i.get("optionsIncludeDestroyed"), g = {};
                    c = e.multiple ? m.utils.arrayMap(r(), m.selectExtensions.readValue) : e.selectedIndex >= 0 ? [m.selectExtensions.readValue(e.options[e.selectedIndex])] : [], f && ("undefined" == typeof f.length && (f = [f]), u = m.utils.arrayFilter(f, function (e) {
                        return h || e === t || null === e || !m.utils.unwrapObservable(e._destroy)
                    }), i.has("optionsCaption") && (l = m.utils.unwrapObservable(i.get("optionsCaption")), null !== l && l !== t && u.unshift(R)));
                    var v = !1;
                    g.beforeRemove = function (t) {
                        e.removeChild(t)
                    };
                    var y = s;
                    i.has("optionsAfterRender") && (y = function (e, n) {
                        s(e, n), m.dependencyDetection.ignore(i.get("optionsAfterRender"), null, [n[0], e !== R ? e : t])
                    }), m.utils.setDomNodeChildrenFromArrayMapping(e, u, a, g, y), m.dependencyDetection.ignore(function () {
                        if (i.get("valueAllowUnset") && i.has("value"))m.selectExtensions.writeValue(e, m.utils.unwrapObservable(i.get("value")), !0); else {
                            var t;
                            t = e.multiple ? c.length && r().length < c.length : c.length && e.selectedIndex >= 0 ? m.selectExtensions.readValue(e.options[e.selectedIndex]) !== c[0] : c.length || e.selectedIndex >= 0, t && m.utils.triggerEvent(e, "change")
                        }
                    }), m.utils.ensureSelectElementIsRenderedCorrectly(e), p && Math.abs(p - e.scrollTop) > 20 && (e.scrollTop = p)
                }
            }, m.bindingHandlers.options.optionValueDomDataKey = m.utils.domData.nextKey(), m.bindingHandlers.selectedOptions = {
                after: ["options", "foreach"],
                init: function (e, t, n) {
                    m.utils.registerEventHandler(e, "change", function () {
                        var i = t(), r = [];
                        m.utils.arrayForEach(e.getElementsByTagName("option"), function (e) {
                            e.selected && r.push(m.selectExtensions.readValue(e))
                        }), m.expressionRewriting.writeValueToProperty(i, n, "selectedOptions", r)
                    })
                },
                update: function (e, t) {
                    if ("select" != m.utils.tagNameLower(e))throw new Error("values binding applies only to SELECT elements");
                    var n = m.utils.unwrapObservable(t());
                    n && "number" == typeof n.length && m.utils.arrayForEach(e.getElementsByTagName("option"), function (e) {
                        var t = m.utils.arrayIndexOf(n, m.selectExtensions.readValue(e)) >= 0;
                        m.utils.setOptionNodeSelectionState(e, t)
                    })
                }
            }, m.expressionRewriting.twoWayBindings.selectedOptions = !0, m.bindingHandlers.style = {
                update: function (e, n) {
                    var i = m.utils.unwrapObservable(n() || {});
                    m.utils.objectForEach(i, function (n, i) {
                        i = m.utils.unwrapObservable(i), (null === i || i === t || i === !1) && (i = ""), e.style[n] = i
                    })
                }
            }, m.bindingHandlers.submit = {
                init: function (e, t, n, i, r) {
                    if ("function" != typeof t())throw new Error("The value for a submit binding must be a function");
                    m.utils.registerEventHandler(e, "submit", function (n) {
                        var i, o = t();
                        try {
                            i = o.call(r.$data, e)
                        } finally {
                            i !== !0 && (n.preventDefault ? n.preventDefault() : n.returnValue = !1)
                        }
                    })
                }
            }, m.bindingHandlers.text = {
                init: function () {
                    return {controlsDescendantBindings: !0}
                }, update: function (e, t) {
                    m.utils.setTextContent(e, t())
                }
            }, m.virtualElements.allowedBindings.text = !0, function () {
                if (n && n.navigator)var i = function (e) {
                    return e ? parseFloat(e[1]) : void 0
                }, r = n.opera && n.opera.version && parseInt(n.opera.version()), o = n.navigator.userAgent, a = i(o.match(/^(?:(?!chrome).)*version\/([^ ]*) safari/i)), s = i(o.match(/Firefox\/([^ ]*)/));
                if (m.utils.ieVersion < 10)var l = m.utils.domData.nextKey(), u = m.utils.domData.nextKey(), c = function (e) {
                    var t = this.activeElement, n = t && m.utils.domData.get(t, u);
                    n && n(e)
                }, d = function (e, t) {
                    var n = e.ownerDocument;
                    m.utils.domData.get(n, l) || (m.utils.domData.set(n, l, !0), m.utils.registerEventHandler(n, "selectionchange", c)), m.utils.domData.set(e, u, t)
                };
                m.bindingHandlers.textInput = {
                    init: function (n, i, o) {
                        var l, u, c = n.value, p = function (r) {
                            clearTimeout(l), u = l = t;
                            var a = n.value;
                            c !== a && (e && r && (n._ko_textInputProcessedEvent = r.type), c = a, m.expressionRewriting.writeValueToProperty(i(), o, "textInput", a))
                        }, f = function (t) {
                            if (!l) {
                                u = n.value;
                                var i = e ? p.bind(n, {type: t.type}) : p;
                                l = setTimeout(i, 4)
                            }
                        }, h = function () {
                            var e = m.utils.unwrapObservable(i());
                            return (null === e || e === t) && (e = ""), u !== t && e === u ? void setTimeout(h, 4) : void(n.value !== e && (c = e, n.value = e))
                        }, g = function (e, t) {
                            m.utils.registerEventHandler(n, e, t)
                        };
                        e && m.bindingHandlers.textInput._forceUpdateOn ? m.utils.arrayForEach(m.bindingHandlers.textInput._forceUpdateOn, function (e) {
                            "after" == e.slice(0, 5) ? g(e.slice(5), f) : g(e, p)
                        }) : m.utils.ieVersion < 10 ? (g("propertychange", function (e) {
                            "value" === e.propertyName && p(e)
                        }), 8 == m.utils.ieVersion && (g("keyup", p), g("keydown", p)), m.utils.ieVersion >= 8 && (d(n, p), g("dragend", f))) : (g("input", p), 5 > a && "textarea" === m.utils.tagNameLower(n) ? (g("keydown", f), g("paste", f), g("cut", f)) : 11 > r ? g("keydown", f) : 4 > s && (g("DOMAutoComplete", p), g("dragdrop", p), g("drop", p))), g("change", p), m.computed(h, null, {disposeWhenNodeIsRemoved: n})
                    }
                }, m.expressionRewriting.twoWayBindings.textInput = !0, m.bindingHandlers.textinput = {
                    preprocess: function (e, t, n) {
                        n("textInput", e)
                    }
                }
            }(), m.bindingHandlers.uniqueName = {
                init: function (e, t) {
                    if (t()) {
                        var n = "ko_unique_" + ++m.bindingHandlers.uniqueName.currentIndex;
                        m.utils.setElementName(e, n)
                    }
                }
            }, m.bindingHandlers.uniqueName.currentIndex = 0, m.bindingHandlers.value = {
                after: ["options", "foreach"],
                init: function (e, t, n) {
                    if ("input" == e.tagName.toLowerCase() && ("checkbox" == e.type || "radio" == e.type))return void m.applyBindingAccessorsToNode(e, {checkedValue: t});
                    var i = ["change"], r = n.get("valueUpdate"), o = !1, a = null;
                    r && ("string" == typeof r && (r = [r]), m.utils.arrayPushAll(i, r), i = m.utils.arrayGetDistinctValues(i));
                    var s = function () {
                        a = null, o = !1;
                        var i = t(), r = m.selectExtensions.readValue(e);
                        m.expressionRewriting.writeValueToProperty(i, n, "value", r)
                    }, l = m.utils.ieVersion && "input" == e.tagName.toLowerCase() && "text" == e.type && "off" != e.autocomplete && (!e.form || "off" != e.form.autocomplete);
                    l && -1 == m.utils.arrayIndexOf(i, "propertychange") && (m.utils.registerEventHandler(e, "propertychange", function () {
                        o = !0
                    }), m.utils.registerEventHandler(e, "focus", function () {
                        o = !1
                    }), m.utils.registerEventHandler(e, "blur", function () {
                        o && s()
                    })), m.utils.arrayForEach(i, function (t) {
                        var n = s;
                        m.utils.stringStartsWith(t, "after") && (n = function () {
                            a = m.selectExtensions.readValue(e), setTimeout(s, 0)
                        }, t = t.substring("after".length)), m.utils.registerEventHandler(e, t, n)
                    });
                    var u = function () {
                        var i = m.utils.unwrapObservable(t()), r = m.selectExtensions.readValue(e);
                        if (null !== a && i === a)return void setTimeout(u, 0);
                        var o = i !== r;
                        if (o)if ("select" === m.utils.tagNameLower(e)) {
                            var s = n.get("valueAllowUnset"), l = function () {
                                m.selectExtensions.writeValue(e, i, s)
                            };
                            l(), s || i === m.selectExtensions.readValue(e) ? setTimeout(l, 0) : m.dependencyDetection.ignore(m.utils.triggerEvent, null, [e, "change"])
                        } else m.selectExtensions.writeValue(e, i)
                    };
                    m.computed(u, null, {disposeWhenNodeIsRemoved: e})
                },
                update: function () {
                }
            }, m.expressionRewriting.twoWayBindings.value = !0, m.bindingHandlers.visible = {
                update: function (e, t) {
                    var n = m.utils.unwrapObservable(t()), i = !("none" == e.style.display);
                    n && !i ? e.style.display = "" : !n && i && (e.style.display = "none")
                }
            }, f("click"), m.templateEngine = function () {
            }, m.templateEngine.prototype.renderTemplateSource = function () {
                throw new Error("Override renderTemplateSource")
            }, m.templateEngine.prototype.createJavaScriptEvaluatorBlock = function () {
                throw new Error("Override createJavaScriptEvaluatorBlock")
            }, m.templateEngine.prototype.makeTemplateSource = function (e, t) {
                if ("string" == typeof e) {
                    t = t || i;
                    var n = t.getElementById(e);
                    if (!n)throw new Error("Cannot find template with ID " + e);
                    return new m.templateSources.domElement(n)
                }
                if (1 == e.nodeType || 8 == e.nodeType)return new m.templateSources.anonymousTemplate(e);
                throw new Error("Unknown template type: " + e)
            }, m.templateEngine.prototype.renderTemplate = function (e, t, n, i) {
                var r = this.makeTemplateSource(e, i);
                return this.renderTemplateSource(r, t, n)
            }, m.templateEngine.prototype.isTemplateRewritten = function (e, t) {
                return this.allowTemplateRewriting === !1 ? !0 : this.makeTemplateSource(e, t).data("isRewritten")
            }, m.templateEngine.prototype.rewriteTemplate = function (e, t, n) {
                var i = this.makeTemplateSource(e, n), r = t(i.text());
                i.text(r), i.data("isRewritten", !0)
            }, m.exportSymbol("templateEngine", m.templateEngine), m.templateRewriting = function () {
                function e(e) {
                    for (var t = m.expressionRewriting.bindingRewriteValidators, n = 0; n < e.length; n++) {
                        var i = e[n].key;
                        if (t.hasOwnProperty(i)) {
                            var r = t[i];
                            if ("function" == typeof r) {
                                var o = r(e[n].value);
                                if (o)throw new Error(o)
                            } else if (!r)throw new Error("This template engine does not support the '" + i + "' binding within its templates")
                        }
                    }
                }

                function t(t, n, i, r) {
                    var o = m.expressionRewriting.parseObjectLiteral(t);
                    e(o);
                    var a = m.expressionRewriting.preProcessBindings(o, {valueAccessors: !0}), s = "ko.__tr_ambtns(function($context,$element){return(function(){return{ " + a + " } })()},'" + i.toLowerCase() + "')";
                    return r.createJavaScriptEvaluatorBlock(s) + n
                }

                var n = /(<([a-z]+\d*)(?:\s+(?!data-bind\s*=\s*)[a-z0-9\-]+(?:=(?:\"[^\"]*\"|\'[^\']*\'))?)*\s+)data-bind\s*=\s*(["'])([\s\S]*?)\3/gi, i = /<!--\s*ko\b\s*([\s\S]*?)\s*-->/g;
                return {
                    ensureTemplateIsRewritten: function (e, t, n) {
                        t.isTemplateRewritten(e, n) || t.rewriteTemplate(e, function (e) {
                            return m.templateRewriting.memoizeBindingAttributeSyntax(e, t)
                        }, n)
                    }, memoizeBindingAttributeSyntax: function (e, r) {
                        return e.replace(n, function () {
                            return t(arguments[4], arguments[1], arguments[2], r)
                        }).replace(i, function () {
                            return t(arguments[1], "<!-- ko -->", "#comment", r)
                        })
                    }, applyMemoizedBindingsToNextSibling: function (e, t) {
                        return m.memoization.memoize(function (n, i) {
                            var r = n.nextSibling;
                            r && r.nodeName.toLowerCase() === t && m.applyBindingAccessorsToNode(r, e, i)
                        })
                    }
                }
            }(), m.exportSymbol("__tr_ambtns", m.templateRewriting.applyMemoizedBindingsToNextSibling), function () {
                m.templateSources = {}, m.templateSources.domElement = function (e) {
                    this.domElement = e
                }, m.templateSources.domElement.prototype.text = function () {
                    var e = m.utils.tagNameLower(this.domElement), t = "script" === e ? "text" : "textarea" === e ? "value" : "innerHTML";
                    if (0 == arguments.length)return this.domElement[t];
                    var n = arguments[0];
                    "innerHTML" === t ? m.utils.setHtml(this.domElement, n) : this.domElement[t] = n
                };
                var e = m.utils.domData.nextKey() + "_";
                m.templateSources.domElement.prototype.data = function (t) {
                    return 1 === arguments.length ? m.utils.domData.get(this.domElement, e + t) : void m.utils.domData.set(this.domElement, e + t, arguments[1])
                };
                var n = m.utils.domData.nextKey();
                m.templateSources.anonymousTemplate = function (e) {
                    this.domElement = e
                }, m.templateSources.anonymousTemplate.prototype = new m.templateSources.domElement, m.templateSources.anonymousTemplate.prototype.constructor = m.templateSources.anonymousTemplate, m.templateSources.anonymousTemplate.prototype.text = function () {
                    if (0 == arguments.length) {
                        var e = m.utils.domData.get(this.domElement, n) || {};
                        return e.textData === t && e.containerData && (e.textData = e.containerData.innerHTML), e.textData
                    }
                    var i = arguments[0];
                    m.utils.domData.set(this.domElement, n, {textData: i})
                }, m.templateSources.domElement.prototype.nodes = function () {
                    if (0 == arguments.length) {
                        var e = m.utils.domData.get(this.domElement, n) || {};
                        return e.containerData
                    }
                    var t = arguments[0];
                    m.utils.domData.set(this.domElement, n, {containerData: t})
                }, m.exportSymbol("templateSources", m.templateSources), m.exportSymbol("templateSources.domElement", m.templateSources.domElement), m.exportSymbol("templateSources.anonymousTemplate", m.templateSources.anonymousTemplate)
            }(), function () {
                function e(e, t, n) {
                    for (var i, r = e, o = m.virtualElements.nextSibling(t); r && (i = r) !== o;)r = m.virtualElements.nextSibling(i), n(i, r)
                }

                function n(t, n) {
                    if (t.length) {
                        var i = t[0], r = t[t.length - 1], o = i.parentNode, a = m.bindingProvider.instance, s = a.preprocessNode;
                        if (s) {
                            if (e(i, r, function (e, t) {
                                    var n = e.previousSibling, o = s.call(a, e);
                                    o && (e === i && (i = o[0] || t), e === r && (r = o[o.length - 1] || n))
                                }), t.length = 0, !i)return;
                            i === r ? t.push(i) : (t.push(i, r), m.utils.fixUpContinuousNodeArray(t, o))
                        }
                        e(i, r, function (e) {
                            (1 === e.nodeType || 8 === e.nodeType) && m.applyBindings(n, e)
                        }), e(i, r, function (e) {
                            (1 === e.nodeType || 8 === e.nodeType) && m.memoization.unmemoizeDomNodeAndDescendants(e, [n])
                        }), m.utils.fixUpContinuousNodeArray(t, o)
                    }
                }

                function i(e) {
                    return e.nodeType ? e : e.length > 0 ? e[0] : null
                }

                function r(e, t, r, o, a) {
                    a = a || {};
                    var l = e && i(e), u = l && l.ownerDocument, c = a.templateEngine || s;
                    m.templateRewriting.ensureTemplateIsRewritten(r, c, u);
                    var d = c.renderTemplate(r, o, a, u);
                    if ("number" != typeof d.length || d.length > 0 && "number" != typeof d[0].nodeType)throw new Error("Template engine must return an array of DOM nodes");
                    var p = !1;
                    switch (t) {
                        case"replaceChildren":
                            m.virtualElements.setDomNodeChildren(e, d), p = !0;
                            break;
                        case"replaceNode":
                            m.utils.replaceDomNodes(e, d), p = !0;
                            break;
                        case"ignoreTargetNode":
                            break;
                        default:
                            throw new Error("Unknown renderMode: " + t)
                    }
                    return p && (n(d, o), a.afterRender && m.dependencyDetection.ignore(a.afterRender, null, [d, o.$data])), d
                }

                function o(e, t, n) {
                    return m.isObservable(e) ? e() : "function" == typeof e ? e(t, n) : e
                }

                function a(e, n) {
                    var i = m.utils.domData.get(e, l);
                    i && "function" == typeof i.dispose && i.dispose(), m.utils.domData.set(e, l, n && n.isActive() ? n : t)
                }

                var s;
                m.setTemplateEngine = function (e) {
                    if (e != t && !(e instanceof m.templateEngine))throw new Error("templateEngine must inherit from ko.templateEngine");
                    s = e
                }, m.renderTemplate = function (e, n, a, l, u) {
                    if (a = a || {}, (a.templateEngine || s) == t)throw new Error("Set a template engine before calling renderTemplate");
                    if (u = u || "replaceChildren", l) {
                        var c = i(l), d = function () {
                            return !c || !m.utils.domNodeIsAttachedToDocument(c)
                        }, p = c && "replaceNode" == u ? c.parentNode : c;
                        return m.dependentObservable(function () {
                            var t = n && n instanceof m.bindingContext ? n : new m.bindingContext(m.utils.unwrapObservable(n)), s = o(e, t.$data, t), d = r(l, u, s, t, a);
                            "replaceNode" == u && (l = d, c = i(l))
                        }, null, {disposeWhen: d, disposeWhenNodeIsRemoved: p})
                    }
                    return m.memoization.memoize(function (t) {
                        m.renderTemplate(e, n, a, t, "replaceNode")
                    })
                }, m.renderTemplateForEach = function (e, i, a, s, l) {
                    var u, c = function (t, n) {
                        u = l.createChildContext(t, a.as, function (e) {
                            e.$index = n
                        });
                        var i = o(e, t, u);
                        return r(null, "ignoreTargetNode", i, u, a)
                    }, d = function (e, t) {
                        n(t, u), a.afterRender && a.afterRender(t, e)
                    };
                    return m.dependentObservable(function () {
                        var e = m.utils.unwrapObservable(i) || [];
                        "undefined" == typeof e.length && (e = [e]);
                        var n = m.utils.arrayFilter(e, function (e) {
                            return a.includeDestroyed || e === t || null === e || !m.utils.unwrapObservable(e._destroy)
                        });
                        m.dependencyDetection.ignore(m.utils.setDomNodeChildrenFromArrayMapping, null, [s, n, c, a, d])
                    }, null, {disposeWhenNodeIsRemoved: s})
                };
                var l = m.utils.domData.nextKey();
                m.bindingHandlers.template = {
                    init: function (e, t) {
                        var n = m.utils.unwrapObservable(t());
                        if ("string" == typeof n || n.name)m.virtualElements.emptyNode(e); else {
                            var i = m.virtualElements.childNodes(e), r = m.utils.moveCleanedNodesToContainerElement(i);
                            new m.templateSources.anonymousTemplate(e).nodes(r)
                        }
                        return {controlsDescendantBindings: !0}
                    }, update: function (e, t, n, i, r) {
                        var o, s, l = t(), u = m.utils.unwrapObservable(l), c = !0, d = null;
                        if ("string" == typeof u ? (s = l, u = {}) : (s = u.name, "if"in u && (c = m.utils.unwrapObservable(u["if"])), c && "ifnot"in u && (c = !m.utils.unwrapObservable(u.ifnot)), o = m.utils.unwrapObservable(u.data)), "foreach"in u) {
                            var p = c && u.foreach || [];
                            d = m.renderTemplateForEach(s || e, p, u, e, r)
                        } else if (c) {
                            var f = "data"in u ? r.createChildContext(o, u.as) : r;
                            d = m.renderTemplate(s || e, f, u, e)
                        } else m.virtualElements.emptyNode(e);
                        a(e, d)
                    }
                }, m.expressionRewriting.bindingRewriteValidators.template = function (e) {
                    var t = m.expressionRewriting.parseObjectLiteral(e);
                    return 1 == t.length && t[0].unknown ? null : m.expressionRewriting.keyValueArrayContainsKey(t, "name") ? null : "This template engine does not support anonymous templates nested within its templates"
                }, m.virtualElements.allowedBindings.template = !0
            }(), m.exportSymbol("setTemplateEngine", m.setTemplateEngine), m.exportSymbol("renderTemplate", m.renderTemplate), m.utils.findMovesInArrayComparison = function (e, t, n) {
                if (e.length && t.length) {
                    var i, r, o, a, s;
                    for (i = r = 0; (!n || n > i) && (a = e[r]); ++r) {
                        for (o = 0; s = t[o]; ++o)if (a.value === s.value) {
                            a.moved = s.index, s.moved = a.index, t.splice(o, 1), i = o = 0;
                            break
                        }
                        i += o
                    }
                }
            }, m.utils.compareArrays = function () {
                function e(e, r, o) {
                    return o = "boolean" == typeof o ? {dontLimitMoves: o} : o || {}, e = e || [], r = r || [], e.length <= r.length ? t(e, r, n, i, o) : t(r, e, i, n, o)
                }

                function t(e, t, n, i, r) {
                    var o, a, s, l, u, c, d = Math.min, p = Math.max, f = [], h = e.length, g = t.length, v = g - h || 1, y = h + g + 1;
                    for (o = 0; h >= o; o++)for (l = s, f.push(s = []), u = d(g, o + v), c = p(0, o - 1), a = c; u >= a; a++)if (a)if (o)if (e[o - 1] === t[a - 1])s[a] = l[a - 1]; else {
                        var b = l[a] || y, x = s[a - 1] || y;
                        s[a] = d(b, x) + 1
                    } else s[a] = a + 1; else s[a] = o + 1;
                    var w, E = [], T = [], S = [];
                    for (o = h, a = g; o || a;)w = f[o][a] - 1, a && w === f[o][a - 1] ? T.push(E[E.length] = {
                        status: n,
                        value: t[--a],
                        index: a
                    }) : o && w === f[o - 1][a] ? S.push(E[E.length] = {
                        status: i,
                        value: e[--o],
                        index: o
                    }) : (--a, --o, r.sparse || E.push({status: "retained", value: t[a]}));
                    return m.utils.findMovesInArrayComparison(T, S, 10 * h), E.reverse()
                }

                var n = "added", i = "deleted";
                return e
            }(), m.exportSymbol("utils.compareArrays", m.utils.compareArrays), function () {
                function e(e, n, i, r, o) {
                    var a = [], s = m.dependentObservable(function () {
                        var t = n(i, o, m.utils.fixUpContinuousNodeArray(a, e)) || [];
                        a.length > 0 && (m.utils.replaceDomNodes(a, t), r && m.dependencyDetection.ignore(r, null, [i, t, o])), a.length = 0, m.utils.arrayPushAll(a, t)
                    }, null, {
                        disposeWhenNodeIsRemoved: e, disposeWhen: function () {
                            return !m.utils.anyDomNodeIsAttachedToDocument(a)
                        }
                    });
                    return {mappedNodes: a, dependentObservable: s.isActive() ? s : t}
                }

                var n = m.utils.domData.nextKey();
                m.utils.setDomNodeChildrenFromArrayMapping = function (i, r, o, a, s) {
                    function l(e, t) {
                        c = h[t], x !== t && (S[e] = c), c.indexObservable(x++), m.utils.fixUpContinuousNodeArray(c.mappedNodes, i), y.push(c), E.push(c)
                    }

                    function u(e, t) {
                        if (e)for (var n = 0, i = t.length; i > n; n++)t[n] && m.utils.arrayForEach(t[n].mappedNodes, function (i) {
                            e(i, n, t[n].arrayEntry)
                        })
                    }

                    r = r || [], a = a || {};
                    for (var c, d, p, f = m.utils.domData.get(i, n) === t, h = m.utils.domData.get(i, n) || [], g = m.utils.arrayMap(h, function (e) {
                        return e.arrayEntry
                    }), v = m.utils.compareArrays(g, r, a.dontLimitMoves), y = [], b = 0, x = 0, w = [], E = [], T = [], S = [], _ = [], R = 0; d = v[R]; R++)switch (p = d.moved, d.status) {
                        case"deleted":
                            p === t && (c = h[b], c.dependentObservable && c.dependentObservable.dispose(), w.push.apply(w, m.utils.fixUpContinuousNodeArray(c.mappedNodes, i)), a.beforeRemove && (T[R] = c, E.push(c))), b++;
                            break;
                        case"retained":
                            l(R, b++);
                            break;
                        case"added":
                            p !== t ? l(R, p) : (c = {
                                arrayEntry: d.value,
                                indexObservable: m.observable(x++)
                            }, y.push(c), E.push(c), f || (_[R] = c))
                    }
                    u(a.beforeMove, S), m.utils.arrayForEach(w, a.beforeRemove ? m.cleanNode : m.removeNode);
                    for (var C, D, R = 0, N = m.virtualElements.firstChild(i); c = E[R]; R++) {
                        c.mappedNodes || m.utils.extend(c, e(i, o, c.arrayEntry, s, c.indexObservable));
                        for (var O = 0; D = c.mappedNodes[O]; N = D.nextSibling, C = D, O++)D !== N && m.virtualElements.insertAfter(i, D, C);
                        !c.initialized && s && (s(c.arrayEntry, c.mappedNodes, c.indexObservable), c.initialized = !0)
                    }
                    u(a.beforeRemove, T), u(a.afterMove, S), u(a.afterAdd, _), m.utils.domData.set(i, n, y)
                }
            }(), m.exportSymbol("utils.setDomNodeChildrenFromArrayMapping", m.utils.setDomNodeChildrenFromArrayMapping), m.nativeTemplateEngine = function () {
                this.allowTemplateRewriting = !1
            }, m.nativeTemplateEngine.prototype = new m.templateEngine, m.nativeTemplateEngine.prototype.constructor = m.nativeTemplateEngine, m.nativeTemplateEngine.prototype.renderTemplateSource = function (e) {
                var t = !(m.utils.ieVersion < 9), n = t ? e.nodes : null, i = n ? e.nodes() : null;
                if (i)return m.utils.makeArray(i.cloneNode(!0).childNodes);
                var r = e.text();
                return m.utils.parseHtmlFragment(r)
            }, m.nativeTemplateEngine.instance = new m.nativeTemplateEngine, m.setTemplateEngine(m.nativeTemplateEngine.instance), m.exportSymbol("nativeTemplateEngine", m.nativeTemplateEngine), function () {
                m.jqueryTmplTemplateEngine = function () {
                    function e() {
                        if (2 > n)throw new Error("Your version of jQuery.tmpl is too old. Please upgrade to jQuery.tmpl 1.0.0pre or later.")
                    }

                    function t(e, t, n) {
                        return o.tmpl(e, t, n)
                    }

                    var n = this.jQueryTmplVersion = function () {
                        if (!o || !o.tmpl)return 0;
                        try {
                            if (o.tmpl.tag.tmpl.open.toString().indexOf("__") >= 0)return 2
                        } catch (e) {
                        }
                        return 1
                    }();
                    this.renderTemplateSource = function (n, r, a) {
                        a = a || {}, e();
                        var s = n.data("precompiled");
                        if (!s) {
                            var l = n.text() || "";
                            l = "{{ko_with $item.koBindingContext}}" + l + "{{/ko_with}}", s = o.template(null, l), n.data("precompiled", s)
                        }
                        var u = [r.$data], c = o.extend({koBindingContext: r}, a.templateOptions), d = t(s, u, c);
                        return d.appendTo(i.createElement("div")), o.fragments = {}, d
                    }, this.createJavaScriptEvaluatorBlock = function (e) {
                        return "{{ko_code ((function() { return " + e + " })()) }}"
                    }, this.addTemplate = function (e, t) {
                        i.write("<script type='text/html' id='" + e + "'>" + t + "</script>")
                    }, n > 0 && (o.tmpl.tag.ko_code = {open: "__.push($1 || '');"}, o.tmpl.tag.ko_with = {
                        open: "with($1) {",
                        close: "} "
                    })
                }, m.jqueryTmplTemplateEngine.prototype = new m.templateEngine, m.jqueryTmplTemplateEngine.prototype.constructor = m.jqueryTmplTemplateEngine;
                var e = new m.jqueryTmplTemplateEngine;
                e.jQueryTmplVersion > 0 && m.setTemplateEngine(e), m.exportSymbol("jqueryTmplTemplateEngine", m.jqueryTmplTemplateEngine)
            }()
        })
    }()
}(), function (e) {
    "function" == typeof require && "object" == typeof exports && "object" == typeof module ? e(require("knockout"), exports) : "function" == typeof define && define.amd ? define(["knockout", "exports"], e) : e(ko, ko.mapping = {})
}(function (e, t) {
    function n(e, t) {
        for (var n = {}, i = e.length - 1; i >= 0; --i)n[e[i]] = e[i];
        for (var i = t.length - 1; i >= 0; --i)n[t[i]] = t[i];
        var r = [];
        for (var o in n)r.push(n[o]);
        return r
    }

    function i(e, r) {
        var o;
        for (var a in r)if (r.hasOwnProperty(a) && r[a])if (o = t.getType(e[a]), a && e[a] && "array" !== o && "string" !== o)i(e[a], r[a]); else {
            var s = "array" === t.getType(e[a]) && "array" === t.getType(r[a]);
            e[a] = s ? n(e[a], r[a]) : r[a]
        }
    }

    function r(e, t) {
        var n = {};
        return i(n, e), i(n, t), n
    }

    function o(e, t) {
        for (var n = r({}, e), i = S.length - 1; i >= 0; i--) {
            var o = S[i];
            n[o] && (n[""]instanceof Object || (n[""] = {}), n[""][o] = n[o], delete n[o])
        }
        return t && (n.ignore = a(t.ignore, n.ignore), n.include = a(t.include, n.include), n.copy = a(t.copy, n.copy), n.observe = a(t.observe, n.observe)), n.ignore = a(n.ignore, C.ignore), n.include = a(n.include, C.include), n.copy = a(n.copy, C.copy), n.observe = a(n.observe, C.observe), n.mappedProperties = n.mappedProperties || {}, n.copiedProperties = n.copiedProperties || {}, n
    }

    function a(n, i) {
        return "array" !== t.getType(n) && (n = "undefined" === t.getType(n) ? [] : [n]), "array" !== t.getType(i) && (i = "undefined" === t.getType(i) ? [] : [i]), e.utils.arrayGetDistinctValues(n.concat(i))
    }

    function s(t, n) {
        var i = e.dependentObservable;
        e.dependentObservable = function (n, i, r) {
            r = r || {}, n && "object" == typeof n && (r = n);
            var o = r.deferEvaluation, a = !1, s = function (n) {
                var i = e.dependentObservable;
                e.dependentObservable = E;
                var r = e.isWriteableObservable(n);
                e.dependentObservable = i;
                var o = E({
                    read: function () {
                        return a || (e.utils.arrayRemoveItem(t, n), a = !0), n.apply(n, arguments)
                    }, write: r && function (e) {
                        return n(e)
                    }, deferEvaluation: !0
                });
                return x && (o._wrapper = !0), o.__DO = n, o
            };
            r.deferEvaluation = !0;
            var l = new E(n, i, r);
            return o || (l = s(l), t.push(l)), l
        }, e.dependentObservable.fn = E.fn, e.computed = e.dependentObservable;
        var r = n();
        return e.dependentObservable = i, e.computed = e.dependentObservable, r
    }

    function l(n, i, o, a, c, m, g) {
        var v = "array" === t.getType(e.utils.unwrapObservable(i));
        if (m = m || "", t.isMapped(n)) {
            var x = e.utils.unwrapObservable(n)[w];
            o = r(x, o)
        }
        var E = {data: i, parent: g || c}, T = function () {
            return o[a] && o[a].create instanceof Function
        }, S = function (t) {
            return s(y, function () {
                return o[a].create(e.utils.unwrapObservable(c)instanceof Array ? {
                    data: t || E.data,
                    parent: E.parent,
                    skip: _
                } : {data: t || E.data, parent: E.parent})
            })
        }, R = function () {
            return o[a] && o[a].update instanceof Function
        }, C = function (t, n) {
            var i = {data: n || E.data, parent: E.parent, target: e.utils.unwrapObservable(t)};
            return e.isWriteableObservable(t) && (i.observable = t), o[a].update(i)
        }, D = b.get(i);
        if (D)return D;
        if (a = a || "", v) {
            var N = [], O = !1, A = function (e) {
                return e
            };
            o[a] && o[a].key && (A = o[a].key, O = !0), e.isObservable(n) || (n = e.observableArray([]), n.mappedRemove = function (e) {
                var t = "function" == typeof e ? e : function (t) {
                    return t === A(e)
                };
                return n.remove(function (e) {
                    return t(A(e))
                })
            }, n.mappedRemoveAll = function (t) {
                var i = p(t, A);
                return n.remove(function (t) {
                    return -1 != e.utils.arrayIndexOf(i, A(t))
                })
            }, n.mappedDestroy = function (e) {
                var t = "function" == typeof e ? e : function (t) {
                    return t === A(e)
                };
                return n.destroy(function (e) {
                    return t(A(e))
                })
            }, n.mappedDestroyAll = function (t) {
                var i = p(t, A);
                return n.destroy(function (t) {
                    return -1 != e.utils.arrayIndexOf(i, A(t))
                })
            }, n.mappedIndexOf = function (t) {
                var i = p(n(), A), r = A(t);
                return e.utils.arrayIndexOf(i, r)
            }, n.mappedGet = function (e) {
                return n()[n.mappedIndexOf(e)]
            }, n.mappedCreate = function (t) {
                if (-1 !== n.mappedIndexOf(t))throw new Error("There already is an object with the key that you specified.");
                var i = T() ? S(t) : t;
                if (R()) {
                    var r = C(i, t);
                    e.isWriteableObservable(i) ? i(r) : i = r
                }
                return n.push(i), i
            });
            var k = p(e.utils.unwrapObservable(n), A).sort(), I = p(i, A);
            O && I.sort();
            var F, L, M = e.utils.compareArrays(k, I), H = {}, P = e.utils.unwrapObservable(i), j = {}, B = !0;
            for (F = 0, L = P.length; L > F; F++) {
                var $ = A(P[F]);
                if (void 0 === $ || $ instanceof Object) {
                    B = !1;
                    break
                }
                j[$] = P[F]
            }
            var z = [], q = 0;
            for (F = 0, L = M.length; L > F; F++) {
                var W, $ = M[F], U = m + "[" + F + "]";
                switch ($.status) {
                    case"added":
                        var V = B ? j[$.value] : d(e.utils.unwrapObservable(i), $.value, A);
                        W = l(void 0, V, o, a, n, U, c), T() || (W = e.utils.unwrapObservable(W));
                        var G = u(e.utils.unwrapObservable(i), V, H);
                        W === _ ? q++ : z[G - q] = W, H[G] = !0;
                        break;
                    case"retained":
                        var V = B ? j[$.value] : d(e.utils.unwrapObservable(i), $.value, A);
                        W = d(n, $.value, A), l(W, V, o, a, n, U, c);
                        var G = u(e.utils.unwrapObservable(i), V, H);
                        z[G] = W, H[G] = !0;
                        break;
                    case"deleted":
                        W = d(n, $.value, A)
                }
                N.push({event: $.status, item: W})
            }
            n(z), o[a] && o[a].arrayChanged && e.utils.arrayForEach(N, function (e) {
                o[a].arrayChanged(e.event, e.item)
            })
        } else if (h(i)) {
            if (n = e.utils.unwrapObservable(n), !n) {
                if (T()) {
                    var X = S();
                    return R() && (X = C(X)), X
                }
                if (R())return C(X);
                n = {}
            }
            if (R() && (n = C(n)), b.save(i, n), R())return n;
            f(i, function (t) {
                var r = m.length ? m + "." + t : t;
                if (-1 == e.utils.arrayIndexOf(o.ignore, r)) {
                    if (-1 != e.utils.arrayIndexOf(o.copy, r))return void(n[t] = i[t]);
                    if ("object" != typeof i[t] && "array" != typeof i[t] && o.observe.length > 0 && -1 == e.utils.arrayIndexOf(o.observe, r))return n[t] = i[t], void(o.copiedProperties[r] = !0);
                    var a = b.get(i[t]), s = l(n[t], i[t], o, t, n, r, n), u = a || s;
                    if (o.observe.length > 0 && -1 == e.utils.arrayIndexOf(o.observe, r))return n[t] = u(), void(o.copiedProperties[r] = !0);
                    e.isWriteableObservable(n[t]) ? (u = e.utils.unwrapObservable(u), n[t]() !== u && n[t](u)) : (u = void 0 === n[t] ? u : e.utils.unwrapObservable(u), n[t] = u), o.mappedProperties[r] = !0
                }
            })
        } else switch (t.getType(i)) {
            case"function":
                R() ? e.isWriteableObservable(i) ? (i(C(i)), n = i) : n = C(i) : n = i;
                break;
            default:
                if (e.isWriteableObservable(n)) {
                    if (R()) {
                        var J = C(n);
                        return n(J), J
                    }
                    var J = e.utils.unwrapObservable(i);
                    return n(J), J
                }
                var Y = T() || R();
                if (n = T() ? S() : e.observable(e.utils.unwrapObservable(i)), R() && n(C(n)), Y)return n
        }
        return n
    }

    function u(e, t, n) {
        for (var i = 0, r = e.length; r > i; i++)if (n[i] !== !0 && e[i] === t)return i;
        return null
    }

    function c(n, i) {
        var r;
        return i && (r = i(n)), "undefined" === t.getType(r) && (r = n), e.utils.unwrapObservable(r)
    }

    function d(t, n, i) {
        t = e.utils.unwrapObservable(t);
        for (var r = 0, o = t.length; o > r; r++) {
            var a = t[r];
            if (c(a, i) === n)return a
        }
        throw new Error("When calling ko.update*, the key '" + n + "' was not found!")
    }

    function p(t, n) {
        return e.utils.arrayMap(e.utils.unwrapObservable(t), function (e) {
            return n ? c(e, n) : e
        })
    }

    function f(e, n) {
        if ("array" === t.getType(e))for (var i = 0; i < e.length; i++)n(i); else for (var r in e)n(r)
    }

    function h(e) {
        var n = t.getType(e);
        return ("object" === n || "array" === n) && null !== e
    }

    function m(e, n, i) {
        var r = e || "";
        return "array" === t.getType(n) ? e && (r += "[" + i + "]") : (e && (r += "."), r += i), r
    }

    function g() {
        var t = [], n = [];
        this.save = function (i, r) {
            var o = e.utils.arrayIndexOf(t, i);
            o >= 0 ? n[o] = r : (t.push(i), n.push(r))
        }, this.get = function (i) {
            var r = e.utils.arrayIndexOf(t, i), o = r >= 0 ? n[r] : void 0;
            return o
        }
    }

    function v() {
        var e = {}, t = function (t) {
            var n;
            try {
                n = t
            } catch (i) {
                n = "$$$"
            }
            var r = e[n];
            return void 0 === r && (r = new g, e[n] = r), r
        };
        this.save = function (e, n) {
            t(e).save(e, n)
        }, this.get = function (e) {
            return t(e).get(e)
        }
    }

    var y, b, x = !0, w = "__ko_mapping__", E = e.dependentObservable, T = 0, S = ["create", "update", "key", "arrayChanged"], _ = {}, R = {
        include: ["_destroy"],
        ignore: [],
        copy: [],
        observe: []
    }, C = R;
    t.isMapped = function (t) {
        var n = e.utils.unwrapObservable(t);
        return n && n[w]
    }, t.fromJS = function (e) {
        if (0 == arguments.length)throw new Error("When calling ko.fromJS, pass the object you want to convert.");
        try {
            T++ || (y = [], b = new v);
            var t, n;
            2 == arguments.length && (arguments[1][w] ? n = arguments[1] : t = arguments[1]), 3 == arguments.length && (t = arguments[1], n = arguments[2]), n && (t = r(t, n[w])), t = o(t);
            var i = l(n, e, t);
            if (n && (i = n), !--T)for (; y.length;) {
                var a = y.pop();
                a && (a(), a.__DO.throttleEvaluation = a.throttleEvaluation)
            }
            return i[w] = r(i[w], t), i
        } catch (s) {
            throw T = 0, s
        }
    }, t.fromJSON = function (n) {
        var i = e.utils.parseJson(n);
        return arguments[0] = i, t.fromJS.apply(this, arguments)
    }, t.updateFromJS = function () {
        throw new Error("ko.mapping.updateFromJS, use ko.mapping.fromJS instead. Please note that the order of parameters is different!")
    }, t.updateFromJSON = function () {
        throw new Error("ko.mapping.updateFromJSON, use ko.mapping.fromJSON instead. Please note that the order of parameters is different!")
    }, t.toJS = function (n, i) {
        if (C || t.resetDefaultOptions(), 0 == arguments.length)throw new Error("When calling ko.mapping.toJS, pass the object you want to convert.");
        if ("array" !== t.getType(C.ignore))throw new Error("ko.mapping.defaultOptions().ignore should be an array.");
        if ("array" !== t.getType(C.include))throw new Error("ko.mapping.defaultOptions().include should be an array.");
        if ("array" !== t.getType(C.copy))throw new Error("ko.mapping.defaultOptions().copy should be an array.");
        return i = o(i, n[w]), t.visitModel(n, function (t) {
            return e.utils.unwrapObservable(t)
        }, i)
    }, t.toJSON = function (n, i) {
        var r = t.toJS(n, i);
        return e.utils.stringifyJson(r)
    }, t.defaultOptions = function () {
        return arguments.length > 0 ? void(C = arguments[0]) : C
    }, t.resetDefaultOptions = function () {
        C = {include: R.include.slice(0), ignore: R.ignore.slice(0), copy: R.copy.slice(0)}
    }, t.getType = function (e) {
        if (e && "object" == typeof e) {
            if (e.constructor === Date)return "date";
            if (e.constructor === Array)return "array"
        }
        return typeof e
    }, t.visitModel = function (n, i, r) {
        r = r || {}, r.visitedObjects = r.visitedObjects || new v;
        var a, s = e.utils.unwrapObservable(n);
        if (!h(s))return i(n, r.parentName);
        r = o(r, s[w]), i(n, r.parentName), a = "array" === t.getType(s) ? [] : {}, r.visitedObjects.save(n, a);
        var l = r.parentName;
        return f(s, function (n) {
            if (!r.ignore || -1 == e.utils.arrayIndexOf(r.ignore, n)) {
                var o = s[n];
                if (r.parentName = m(l, s, n), -1 !== e.utils.arrayIndexOf(r.copy, n) || -1 !== e.utils.arrayIndexOf(r.include, n) || !s[w] || !s[w].mappedProperties || s[w].mappedProperties[n] || !s[w].copiedProperties || s[w].copiedProperties[n] || "array" === t.getType(s)) {
                    switch (t.getType(e.utils.unwrapObservable(o))) {
                        case"object":
                        case"array":
                        case"undefined":
                            var u = r.visitedObjects.get(o);
                            a[n] = "undefined" !== t.getType(u) ? u : t.visitModel(o, i, r);
                            break;
                        default:
                            a[n] = i(o, r.parentName)
                    }
                }
            }
        }), a
    }
}), function () {
    window.Blink = window.Blink || {}, Blink.waitFor = function (e, t, n) {
        var i;
        return n = n || 100, i = setInterval(function () {
            return e() ? (clearInterval(i), t()) : void 0
        }, n)
    }
}.call(this), function (e, t) {
    "use strict";
    function n(e, t) {
        for (var n, i = [], o = 0; o < e.length; ++o) {
            if (n = a[e[o]] || r(e[o]), !n)throw"module definition dependecy not found: " + e[o];
            i.push(n)
        }
        t.apply(null, i)
    }

    function i(e, i, r) {
        if ("string" != typeof e)throw"invalid module definition, module id must be defined and be a string";
        if (i === t)throw"invalid module definition, dependencies must be specified";
        if (r === t)throw"invalid module definition, definition function must be specified";
        n(i, function () {
            a[e] = r.apply(null, arguments)
        })
    }

    function r(t) {
        for (var n = e, i = t.split(/[.\/]/), r = 0; r < i.length; ++r) {
            if (!n[i[r]])return;
            n = n[i[r]]
        }
        return n
    }

    function o(n) {
        for (var i = 0; i < n.length; i++) {
            for (var r = e, o = n[i], s = o.split(/[.\/]/), l = 0; l < s.length - 1; ++l)r[s[l]] === t && (r[s[l]] = {}), r = r[s[l]];
            r[s[s.length - 1]] = a[o]
        }
    }

    var a = {};
    i("moxie/core/utils/Basic", [], function () {
        var e = function (e) {
            var t;
            return e === t ? "undefined" : null === e ? "null" : e.nodeType ? "node" : {}.toString.call(e).match(/\s([a-z|A-Z]+)/)[1].toLowerCase()
        }, t = function (i) {
            var r;
            return n(arguments, function (o, s) {
                s > 0 && n(o, function (n, o) {
                    n !== r && (e(i[o]) === e(n) && ~a(e(n), ["array", "object"]) ? t(i[o], n) : i[o] = n)
                })
            }), i
        }, n = function (e, t) {
            var n, i, r, o;
            if (e) {
                try {
                    n = e.length
                } catch (a) {
                    n = o
                }
                if (n === o) {
                    for (i in e)if (e.hasOwnProperty(i) && t(e[i], i) === !1)return
                } else for (r = 0; n > r; r++)if (t(e[r], r) === !1)return
            }
        }, i = function (t) {
            var n;
            if (!t || "object" !== e(t))return !0;
            for (n in t)return !1;
            return !0
        }, r = function (t, n) {
            function i(r) {
                "function" === e(t[r]) && t[r](function (e) {
                    ++r < o && !e ? i(r) : n(e)
                })
            }

            var r = 0, o = t.length;
            "function" !== e(n) && (n = function () {
            }), t && t.length || n(), i(r)
        }, o = function (e, t) {
            var i = 0, r = e.length, o = new Array(r);
            n(e, function (e, n) {
                e(function (e) {
                    if (e)return t(e);
                    var a = [].slice.call(arguments);
                    a.shift(), o[n] = a, i++, i === r && (o.unshift(null), t.apply(this, o))
                })
            })
        }, a = function (e, t) {
            if (t) {
                if (Array.prototype.indexOf)return Array.prototype.indexOf.call(t, e);
                for (var n = 0, i = t.length; i > n; n++)if (t[n] === e)return n
            }
            return -1
        }, s = function (t, n) {
            var i = [];
            "array" !== e(t) && (t = [t]), "array" !== e(n) && (n = [n]);
            for (var r in t)-1 === a(t[r], n) && i.push(t[r]);
            return i.length ? i : !1
        }, l = function (e, t) {
            var i = [];
            return n(e, function (e) {
                -1 !== a(e, t) && i.push(e)
            }), i.length ? i : null
        }, u = function (e) {
            var t, n = [];
            for (t = 0; t < e.length; t++)n[t] = e[t];
            return n
        }, c = function () {
            var e = 0;
            return function (t) {
                var n, i = (new Date).getTime().toString(32);
                for (n = 0; 5 > n; n++)i += Math.floor(65535 * Math.random()).toString(32);
                return (t || "o_") + i + (e++).toString(32)
            }
        }(), d = function (e) {
            return e ? String.prototype.trim ? String.prototype.trim.call(e) : e.toString().replace(/^\s*/, "").replace(/\s*$/, "") : e
        }, p = function (e) {
            if ("string" != typeof e)return e;
            var t, n = {t: 1099511627776, g: 1073741824, m: 1048576, k: 1024};
            return e = /^([0-9]+)([mgk]?)$/.exec(e.toLowerCase().replace(/[^0-9mkg]/g, "")), t = e[2], e = +e[1], n.hasOwnProperty(t) && (e *= n[t]), e
        };
        return {
            guid: c,
            typeOf: e,
            extend: t,
            each: n,
            isEmptyObj: i,
            inSeries: r,
            inParallel: o,
            inArray: a,
            arrayDiff: s,
            arrayIntersect: l,
            toArray: u,
            trim: d,
            parseSizeStr: p
        }
    }), i("moxie/core/I18n", ["moxie/core/utils/Basic"], function (e) {
        var t = {};
        return {
            addI18n: function (n) {
                return e.extend(t, n)
            }, translate: function (e) {
                return t[e] || e
            }, _: function (e) {
                return this.translate(e)
            }, sprintf: function (t) {
                var n = [].slice.call(arguments, 1);
                return t.replace(/%[a-z]/g, function () {
                    var t = n.shift();
                    return "undefined" !== e.typeOf(t) ? t : ""
                })
            }
        }
    }), i("moxie/core/utils/Mime", ["moxie/core/utils/Basic", "moxie/core/I18n"], function (e, t) {
        var n = "application/msword,doc dot,application/pdf,pdf,application/pgp-signature,pgp,application/postscript,ps ai eps,application/rtf,rtf,application/vnd.ms-excel,xls xlb,application/vnd.ms-powerpoint,ppt pps pot,application/zip,zip,application/x-shockwave-flash,swf swfl,application/vnd.openxmlformats-officedocument.wordprocessingml.document,docx,application/vnd.openxmlformats-officedocument.wordprocessingml.template,dotx,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,xlsx,application/vnd.openxmlformats-officedocument.presentationml.presentation,pptx,application/vnd.openxmlformats-officedocument.presentationml.template,potx,application/vnd.openxmlformats-officedocument.presentationml.slideshow,ppsx,application/x-javascript,js,application/json,json,audio/mpeg,mp3 mpga mpega mp2,audio/x-wav,wav,audio/x-m4a,m4a,audio/ogg,oga ogg,audio/aiff,aiff aif,audio/flac,flac,audio/aac,aac,audio/ac3,ac3,audio/x-ms-wma,wma,image/bmp,bmp,image/gif,gif,image/jpeg,jpg jpeg jpe,image/photoshop,psd,image/png,png,image/svg+xml,svg svgz,image/tiff,tiff tif,text/plain,asc txt text diff log,text/html,htm html xhtml,text/css,css,text/csv,csv,text/rtf,rtf,video/mpeg,mpeg mpg mpe m2v,video/quicktime,qt mov,video/mp4,mp4,video/x-m4v,m4v,video/x-flv,flv,video/x-ms-wmv,wmv,video/avi,avi,video/webm,webm,video/3gpp,3gpp 3gp,video/3gpp2,3g2,video/vnd.rn-realvideo,rv,video/ogg,ogv,video/x-matroska,mkv,application/vnd.oasis.opendocument.formula-template,otf,application/octet-stream,exe", i = {
            mimes: {},
            extensions: {},
            addMimeType: function (e) {
                var t, n, i, r = e.split(/,/);
                for (t = 0; t < r.length; t += 2) {
                    for (i = r[t + 1].split(/ /), n = 0; n < i.length; n++)this.mimes[i[n]] = r[t];
                    this.extensions[r[t]] = i
                }
            },
            extList2mimes: function (t, n) {
                var i, r, o, a, s = this, l = [];
                for (r = 0; r < t.length; r++)for (i = t[r].extensions.split(/\s*,\s*/), o = 0; o < i.length; o++) {
                    if ("*" === i[o])return [];
                    if (a = s.mimes[i[o]])-1 === e.inArray(a, l) && l.push(a); else {
                        if (!n || !/^\w+$/.test(i[o]))return [];
                        l.push("." + i[o])
                    }
                }
                return l
            },
            mimes2exts: function (t) {
                var n = this, i = [];
                return e.each(t, function (t) {
                    if ("*" === t)return i = [], !1;
                    var r = t.match(/^(\w+)\/(\*|\w+)$/);
                    r && ("*" === r[2] ? e.each(n.extensions, function (e, t) {
                        new RegExp("^" + r[1] + "/").test(t) && [].push.apply(i, n.extensions[t])
                    }) : n.extensions[t] && [].push.apply(i, n.extensions[t]))
                }), i
            },
            mimes2extList: function (n) {
                var i = [], r = [];
                return "string" === e.typeOf(n) && (n = e.trim(n).split(/\s*,\s*/)), r = this.mimes2exts(n), i.push({
                    title: t.translate("Files"),
                    extensions: r.length ? r.join(",") : "*"
                }), i.mimes = n, i
            },
            getFileExtension: function (e) {
                var t = e && e.match(/\.([^.]+)$/);
                return t ? t[1].toLowerCase() : ""
            },
            getFileMime: function (e) {
                return this.mimes[this.getFileExtension(e)] || ""
            }
        };
        return i.addMimeType(n), i
    }), i("moxie/core/utils/Env", ["moxie/core/utils/Basic"], function (e) {
        function t(e, t, n) {
            var i = 0, r = 0, o = 0, a = {
                dev: -6,
                alpha: -5,
                a: -5,
                beta: -4,
                b: -4,
                RC: -3,
                rc: -3,
                "#": -2,
                p: 1,
                pl: 1
            }, s = function (e) {
                return e = ("" + e).replace(/[_\-+]/g, "."), e = e.replace(/([^.\d]+)/g, ".$1.").replace(/\.{2,}/g, "."), e.length ? e.split(".") : [-8]
            }, l = function (e) {
                return e ? isNaN(e) ? a[e] || -7 : parseInt(e, 10) : 0
            };
            for (e = s(e), t = s(t), r = Math.max(e.length, t.length), i = 0; r > i; i++)if (e[i] != t[i]) {
                if (e[i] = l(e[i]), t[i] = l(t[i]), e[i] < t[i]) {
                    o = -1;
                    break
                }
                if (e[i] > t[i]) {
                    o = 1;
                    break
                }
            }
            if (!n)return o;
            switch (n) {
                case">":
                case"gt":
                    return o > 0;
                case">=":
                case"ge":
                    return o >= 0;
                case"<=":
                case"le":
                    return 0 >= o;
                case"==":
                case"=":
                case"eq":
                    return 0 === o;
                case"<>":
                case"!=":
                case"ne":
                    return 0 !== o;
                case"":
                case"<":
                case"lt":
                    return 0 > o;
                default:
                    return null
            }
        }

        var n = function (e) {
            var t = "", n = "?", i = "function", r = "undefined", o = "object", a = "major", s = "name", l = "version", u = {
                has: function (e, t) {
                    return -1 !== t.toLowerCase().indexOf(e.toLowerCase())
                }, lowerize: function (e) {
                    return e.toLowerCase()
                }
            }, c = {
                rgx: function () {
                    for (var t, n, a, s, l, u, c, d = 0, p = arguments; d < p.length; d += 2) {
                        var f = p[d], h = p[d + 1];
                        if (typeof t === r) {
                            t = {};
                            for (s in h)l = h[s], typeof l === o ? t[l[0]] = e : t[l] = e
                        }
                        for (n = a = 0; n < f.length; n++)if (u = f[n].exec(this.getUA())) {
                            for (s = 0; s < h.length; s++)c = u[++a], l = h[s], typeof l === o && l.length > 0 ? 2 == l.length ? t[l[0]] = typeof l[1] == i ? l[1].call(this, c) : l[1] : 3 == l.length ? t[l[0]] = typeof l[1] !== i || l[1].exec && l[1].test ? c ? c.replace(l[1], l[2]) : e : c ? l[1].call(this, c, l[2]) : e : 4 == l.length && (t[l[0]] = c ? l[3].call(this, c.replace(l[1], l[2])) : e) : t[l] = c ? c : e;
                            break
                        }
                        if (u)break
                    }
                    return t
                }, str: function (t, i) {
                    for (var r in i)if (typeof i[r] === o && i[r].length > 0) {
                        for (var a = 0; a < i[r].length; a++)if (u.has(i[r][a], t))return r === n ? e : r
                    } else if (u.has(i[r], t))return r === n ? e : r;
                    return t
                }
            }, d = {
                browser: {
                    oldsafari: {
                        major: {1: ["/8", "/1", "/3"], 2: "/4", "?": "/"},
                        version: {
                            "1.0": "/8",
                            1.2: "/1",
                            1.3: "/3",
                            "2.0": "/412",
                            "2.0.2": "/416",
                            "2.0.3": "/417",
                            "2.0.4": "/419",
                            "?": "/"
                        }
                    }
                },
                device: {sprint: {model: {"Evo Shift 4G": "7373KT"}, vendor: {HTC: "APA", Sprint: "Sprint"}}},
                os: {
                    windows: {
                        version: {
                            ME: "4.90",
                            "NT 3.11": "NT3.51",
                            "NT 4.0": "NT4.0",
                            2000: "NT 5.0",
                            XP: ["NT 5.1", "NT 5.2"],
                            Vista: "NT 6.0",
                            7: "NT 6.1",
                            8: "NT 6.2",
                            8.1: "NT 6.3",
                            RT: "ARM"
                        }
                    }
                }
            }, p = {
                browser: [[/(opera\smini)\/((\d+)?[\w\.-]+)/i, /(opera\s[mobiletab]+).+version\/((\d+)?[\w\.-]+)/i, /(opera).+version\/((\d+)?[\w\.]+)/i, /(opera)[\/\s]+((\d+)?[\w\.]+)/i], [s, l, a], [/\s(opr)\/((\d+)?[\w\.]+)/i], [[s, "Opera"], l, a], [/(kindle)\/((\d+)?[\w\.]+)/i, /(lunascape|maxthon|netfront|jasmine|blazer)[\/\s]?((\d+)?[\w\.]+)*/i, /(avant\s|iemobile|slim|baidu)(?:browser)?[\/\s]?((\d+)?[\w\.]*)/i, /(?:ms|\()(ie)\s((\d+)?[\w\.]+)/i, /(rekonq)((?:\/)[\w\.]+)*/i, /(chromium|flock|rockmelt|midori|epiphany|silk|skyfire|ovibrowser|bolt|iron)\/((\d+)?[\w\.-]+)/i], [s, l, a], [/(trident).+rv[:\s]((\d+)?[\w\.]+).+like\sgecko/i], [[s, "IE"], l, a], [/(yabrowser)\/((\d+)?[\w\.]+)/i], [[s, "Yandex"], l, a], [/(comodo_dragon)\/((\d+)?[\w\.]+)/i], [[s, /_/g, " "], l, a], [/(chrome|omniweb|arora|[tizenoka]{5}\s?browser)\/v?((\d+)?[\w\.]+)/i], [s, l, a], [/(dolfin)\/((\d+)?[\w\.]+)/i], [[s, "Dolphin"], l, a], [/((?:android.+)crmo|crios)\/((\d+)?[\w\.]+)/i], [[s, "Chrome"], l, a], [/((?:android.+))version\/((\d+)?[\w\.]+)\smobile\ssafari/i], [[s, "Android Browser"], l, a], [/version\/((\d+)?[\w\.]+).+?mobile\/\w+\s(safari)/i], [l, a, [s, "Mobile Safari"]], [/version\/((\d+)?[\w\.]+).+?(mobile\s?safari|safari)/i], [l, a, s], [/webkit.+?(mobile\s?safari|safari)((\/[\w\.]+))/i], [s, [a, c.str, d.browser.oldsafari.major], [l, c.str, d.browser.oldsafari.version]], [/(konqueror)\/((\d+)?[\w\.]+)/i, /(webkit|khtml)\/((\d+)?[\w\.]+)/i], [s, l, a], [/(navigator|netscape)\/((\d+)?[\w\.-]+)/i], [[s, "Netscape"], l, a], [/(swiftfox)/i, /(icedragon|iceweasel|camino|chimera|fennec|maemo\sbrowser|minimo|conkeror)[\/\s]?((\d+)?[\w\.\+]+)/i, /(firefox|seamonkey|k-meleon|icecat|iceape|firebird|phoenix)\/((\d+)?[\w\.-]+)/i, /(mozilla)\/((\d+)?[\w\.]+).+rv\:.+gecko\/\d+/i, /(uc\s?browser|polaris|lynx|dillo|icab|doris|amaya|w3m|netsurf|qqbrowser)[\/\s]?((\d+)?[\w\.]+)/i, /(links)\s\(((\d+)?[\w\.]+)/i, /(gobrowser)\/?((\d+)?[\w\.]+)*/i, /(ice\s?browser)\/v?((\d+)?[\w\._]+)/i, /(mosaic)[\/\s]((\d+)?[\w\.]+)/i], [s, l, a]],
                engine: [[/(presto)\/([\w\.]+)/i, /(webkit|trident|netfront|netsurf|amaya|lynx|w3m)\/([\w\.]+)/i, /(khtml|tasman|links)[\/\s]\(?([\w\.]+)/i, /(icab)[\/\s]([23]\.[\d\.]+)/i], [s, l], [/rv\:([\w\.]+).*(gecko)/i], [l, s]],
                os: [[/(windows)\snt\s6\.2;\s(arm)/i, /(windows\sphone(?:\sos)*|windows\smobile|windows)[\s\/]?([ntce\d\.\s]+\w)/i], [s, [l, c.str, d.os.windows.version]], [/(win(?=3|9|n)|win\s9x\s)([nt\d\.]+)/i], [[s, "Windows"], [l, c.str, d.os.windows.version]], [/\((bb)(10);/i], [[s, "BlackBerry"], l], [/(blackberry)\w*\/?([\w\.]+)*/i, /(tizen)\/([\w\.]+)/i, /(android|webos|palm\os|qnx|bada|rim\stablet\sos|meego)[\/\s-]?([\w\.]+)*/i], [s, l], [/(symbian\s?os|symbos|s60(?=;))[\/\s-]?([\w\.]+)*/i], [[s, "Symbian"], l], [/mozilla.+\(mobile;.+gecko.+firefox/i], [[s, "Firefox OS"], l], [/(nintendo|playstation)\s([wids3portablevu]+)/i, /(mint)[\/\s\(]?(\w+)*/i, /(joli|[kxln]?ubuntu|debian|[open]*suse|gentoo|arch|slackware|fedora|mandriva|centos|pclinuxos|redhat|zenwalk)[\/\s-]?([\w\.-]+)*/i, /(hurd|linux)\s?([\w\.]+)*/i, /(gnu)\s?([\w\.]+)*/i], [s, l], [/(cros)\s[\w]+\s([\w\.]+\w)/i], [[s, "Chromium OS"], l], [/(sunos)\s?([\w\.]+\d)*/i], [[s, "Solaris"], l], [/\s([frentopc-]{0,4}bsd|dragonfly)\s?([\w\.]+)*/i], [s, l], [/(ip[honead]+)(?:.*os\s*([\w]+)*\slike\smac|;\sopera)/i], [[s, "iOS"], [l, /_/g, "."]], [/(mac\sos\sx)\s?([\w\s\.]+\w)*/i], [s, [l, /_/g, "."]], [/(haiku)\s(\w+)/i, /(aix)\s((\d)(?=\.|\)|\s)[\w\.]*)*/i, /(macintosh|mac(?=_powerpc)|plan\s9|minix|beos|os\/2|amigaos|morphos|risc\sos)/i, /(unix)\s?([\w\.]+)*/i], [s, l]]
            }, f = function (e) {
                var n = e || (window && window.navigator && window.navigator.userAgent ? window.navigator.userAgent : t);
                this.getBrowser = function () {
                    return c.rgx.apply(this, p.browser)
                }, this.getEngine = function () {
                    return c.rgx.apply(this, p.engine)
                }, this.getOS = function () {
                    return c.rgx.apply(this, p.os)
                }, this.getResult = function () {
                    return {ua: this.getUA(), browser: this.getBrowser(), engine: this.getEngine(), os: this.getOS()}
                }, this.getUA = function () {
                    return n
                }, this.setUA = function (e) {
                    return n = e, this
                }, this.setUA(n)
            };
            return (new f).getResult()
        }(), i = function () {
            var t = {
                define_property: function () {
                    return !1
                }(), create_canvas: function () {
                    var e = document.createElement("canvas");
                    return !(!e.getContext || !e.getContext("2d"))
                }(), return_response_type: function (t) {
                    try {
                        if (-1 !== e.inArray(t, ["", "text", "document"]))return !0;
                        if (window.XMLHttpRequest) {
                            var n = new XMLHttpRequest;
                            if (n.open("get", "/"), "responseType"in n)return n.responseType = t, n.responseType !== t ? !1 : !0
                        }
                    } catch (i) {
                    }
                    return !1
                }, use_data_uri: function () {
                    var e = new Image;
                    return e.onload = function () {
                        t.use_data_uri = 1 === e.width && 1 === e.height
                    }, setTimeout(function () {
                        e.src = "data:image/gif;base64,R0lGODlhAQABAIAAAP8AAAAAACH5BAAAAAAALAAAAAABAAEAAAICRAEAOw=="
                    }, 1), !1
                }(), use_data_uri_over32kb: function () {
                    return t.use_data_uri && ("IE" !== r.browser || r.version >= 9)
                }, use_data_uri_of: function (e) {
                    return t.use_data_uri && 33e3 > e || t.use_data_uri_over32kb()
                }, use_fileinput: function () {
                    var e = document.createElement("input");
                    return e.setAttribute("type", "file"), !e.disabled
                }
            };
            return function (n) {
                var i = [].slice.call(arguments);
                return i.shift(), "function" === e.typeOf(t[n]) ? t[n].apply(this, i) : !!t[n]
            }
        }(), r = {
            can: i,
            browser: n.browser.name,
            version: parseFloat(n.browser.major),
            os: n.os.name,
            osVersion: n.os.version,
            verComp: t,
            swf_url: "../flash/Moxie.swf",
            xap_url: "../silverlight/Moxie.xap",
            global_event_dispatcher: "moxie.core.EventTarget.instance.dispatchEvent"
        };
        return r.OS = r.os, r
    }), i("moxie/core/utils/Dom", ["moxie/core/utils/Env"], function (e) {
        var t = function (e) {
            return "string" != typeof e ? e : document.getElementById(e)
        }, n = function (e, t) {
            if (!e.className)return !1;
            var n = new RegExp("(^|\\s+)" + t + "(\\s+|$)");
            return n.test(e.className)
        }, i = function (e, t) {
            n(e, t) || (e.className = e.className ? e.className.replace(/\s+$/, "") + " " + t : t)
        }, r = function (e, t) {
            if (e.className) {
                var n = new RegExp("(^|\\s+)" + t + "(\\s+|$)");
                e.className = e.className.replace(n, function (e, t, n) {
                    return " " === t && " " === n ? " " : ""
                })
            }
        }, o = function (e, t) {
            return e.currentStyle ? e.currentStyle[t] : window.getComputedStyle ? window.getComputedStyle(e, null)[t] : void 0
        }, a = function (t, n) {
            function i(e) {
                var t, n, i = 0, r = 0;
                return e && (n = e.getBoundingClientRect(), t = "CSS1Compat" === u.compatMode ? u.documentElement : u.body, i = n.left + t.scrollLeft, r = n.top + t.scrollTop), {
                    x: i,
                    y: r
                }
            }

            var r, o, a, s = 0, l = 0, u = document;
            if (t = t, n = n || u.body, t && t.getBoundingClientRect && "IE" === e.browser && (!u.documentMode || u.documentMode < 8))return o = i(t), a = i(n), {
                x: o.x - a.x,
                y: o.y - a.y
            };
            for (r = t; r && r != n && r.nodeType;)s += r.offsetLeft || 0, l += r.offsetTop || 0, r = r.offsetParent;
            for (r = t.parentNode; r && r != n && r.nodeType;)s -= r.scrollLeft || 0, l -= r.scrollTop || 0, r = r.parentNode;
            return {x: s, y: l}
        }, s = function (e) {
            return {w: e.offsetWidth || e.clientWidth, h: e.offsetHeight || e.clientHeight}
        };
        return {get: t, hasClass: n, addClass: i, removeClass: r, getStyle: o, getPos: a, getSize: s}
    }), i("moxie/core/Exceptions", ["moxie/core/utils/Basic"], function (e) {
        function t(e, t) {
            var n;
            for (n in e)if (e[n] === t)return n;
            return null
        }

        return {
            RuntimeError: function () {
                function n(e) {
                    this.code = e, this.name = t(i, e), this.message = this.name + ": RuntimeError " + this.code
                }

                var i = {NOT_INIT_ERR: 1, NOT_SUPPORTED_ERR: 9, JS_ERR: 4};
                return e.extend(n, i), n.prototype = Error.prototype, n
            }(), OperationNotAllowedException: function () {
                function t(e) {
                    this.code = e, this.name = "OperationNotAllowedException"
                }

                return e.extend(t, {NOT_ALLOWED_ERR: 1}), t.prototype = Error.prototype, t
            }(), ImageError: function () {
                function n(e) {
                    this.code = e, this.name = t(i, e), this.message = this.name + ": ImageError " + this.code
                }

                var i = {WRONG_FORMAT: 1, MAX_RESOLUTION_ERR: 2};
                return e.extend(n, i), n.prototype = Error.prototype, n
            }(), FileException: function () {
                function n(e) {
                    this.code = e, this.name = t(i, e), this.message = this.name + ": FileException " + this.code
                }

                var i = {
                    NOT_FOUND_ERR: 1,
                    SECURITY_ERR: 2,
                    ABORT_ERR: 3,
                    NOT_READABLE_ERR: 4,
                    ENCODING_ERR: 5,
                    NO_MODIFICATION_ALLOWED_ERR: 6,
                    INVALID_STATE_ERR: 7,
                    SYNTAX_ERR: 8
                };
                return e.extend(n, i), n.prototype = Error.prototype, n
            }(), DOMException: function () {
                function n(e) {
                    this.code = e, this.name = t(i, e), this.message = this.name + ": DOMException " + this.code
                }

                var i = {
                    INDEX_SIZE_ERR: 1,
                    DOMSTRING_SIZE_ERR: 2,
                    HIERARCHY_REQUEST_ERR: 3,
                    WRONG_DOCUMENT_ERR: 4,
                    INVALID_CHARACTER_ERR: 5,
                    NO_DATA_ALLOWED_ERR: 6,
                    NO_MODIFICATION_ALLOWED_ERR: 7,
                    NOT_FOUND_ERR: 8,
                    NOT_SUPPORTED_ERR: 9,
                    INUSE_ATTRIBUTE_ERR: 10,
                    INVALID_STATE_ERR: 11,
                    SYNTAX_ERR: 12,
                    INVALID_MODIFICATION_ERR: 13,
                    NAMESPACE_ERR: 14,
                    INVALID_ACCESS_ERR: 15,
                    VALIDATION_ERR: 16,
                    TYPE_MISMATCH_ERR: 17,
                    SECURITY_ERR: 18,
                    NETWORK_ERR: 19,
                    ABORT_ERR: 20,
                    URL_MISMATCH_ERR: 21,
                    QUOTA_EXCEEDED_ERR: 22,
                    TIMEOUT_ERR: 23,
                    INVALID_NODE_TYPE_ERR: 24,
                    DATA_CLONE_ERR: 25
                };
                return e.extend(n, i), n.prototype = Error.prototype, n
            }(), EventException: function () {
                function t(e) {
                    this.code = e, this.name = "EventException"
                }

                return e.extend(t, {UNSPECIFIED_EVENT_TYPE_ERR: 0}), t.prototype = Error.prototype, t
            }()
        }
    }), i("moxie/core/EventTarget", ["moxie/core/Exceptions", "moxie/core/utils/Basic"], function (e, t) {
        function n() {
            var n = {};
            t.extend(this, {
                uid: null, init: function () {
                    this.uid || (this.uid = t.guid("uid_"))
                }, addEventListener: function (e, i, r, o) {
                    var a, s = this;
                    return e = t.trim(e), /\s/.test(e) ? void t.each(e.split(/\s+/), function (e) {
                        s.addEventListener(e, i, r, o)
                    }) : (e = e.toLowerCase(), r = parseInt(r, 10) || 0, a = n[this.uid] && n[this.uid][e] || [], a.push({
                        fn: i,
                        priority: r,
                        scope: o || this
                    }), n[this.uid] || (n[this.uid] = {}), void(n[this.uid][e] = a))
                }, hasEventListener: function (e) {
                    return e ? !(!n[this.uid] || !n[this.uid][e]) : !!n[this.uid]
                }, removeEventListener: function (e, i) {
                    e = e.toLowerCase();
                    var r, o = n[this.uid] && n[this.uid][e];
                    if (o) {
                        if (i) {
                            for (r = o.length - 1; r >= 0; r--)if (o[r].fn === i) {
                                o.splice(r, 1);
                                break
                            }
                        } else o = [];
                        o.length || (delete n[this.uid][e], t.isEmptyObj(n[this.uid]) && delete n[this.uid])
                    }
                }, removeAllEventListeners: function () {
                    n[this.uid] && delete n[this.uid]
                }, dispatchEvent: function (i) {
                    var r, o, a, s, l, u = {}, c = !0;
                    if ("string" !== t.typeOf(i)) {
                        if (s = i, "string" !== t.typeOf(s.type))throw new e.EventException(e.EventException.UNSPECIFIED_EVENT_TYPE_ERR);
                        i = s.type, s.total !== l && s.loaded !== l && (u.total = s.total, u.loaded = s.loaded), u.async = s.async || !1
                    }
                    if (-1 !== i.indexOf("::") ? !function (e) {
                            r = e[0], i = e[1]
                        }(i.split("::")) : r = this.uid, i = i.toLowerCase(), o = n[r] && n[r][i]) {
                        o.sort(function (e, t) {
                            return t.priority - e.priority
                        }), a = [].slice.call(arguments), a.shift(), u.type = i, a.unshift(u);
                        var d = [];
                        t.each(o, function (e) {
                            a[0].target = e.scope, d.push(u.async ? function (t) {
                                setTimeout(function () {
                                    t(e.fn.apply(e.scope, a) === !1)
                                }, 1)
                            } : function (t) {
                                t(e.fn.apply(e.scope, a) === !1)
                            })
                        }), d.length && t.inSeries(d, function (e) {
                            c = !e
                        })
                    }
                    return c
                }, bind: function () {
                    this.addEventListener.apply(this, arguments)
                }, unbind: function () {
                    this.removeEventListener.apply(this, arguments)
                }, unbindAll: function () {
                    this.removeAllEventListeners.apply(this, arguments)
                }, trigger: function () {
                    return this.dispatchEvent.apply(this, arguments)
                }, convertEventPropsToHandlers: function (e) {
                    var n;
                    "array" !== t.typeOf(e) && (e = [e]);
                    for (var i = 0; i < e.length; i++)n = "on" + e[i], "function" === t.typeOf(this[n]) ? this.addEventListener(e[i], this[n]) : "undefined" === t.typeOf(this[n]) && (this[n] = null)
                }
            })
        }

        return n.instance = new n, n
    }), i("moxie/core/utils/Encode", [], function () {
        var e = function (e) {
            return unescape(encodeURIComponent(e))
        }, t = function (e) {
            return decodeURIComponent(escape(e))
        }, n = function (e, n) {
            if ("function" == typeof window.atob)return n ? t(window.atob(e)) : window.atob(e);
            var i, r, o, a, s, l, u, c, d = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=", p = 0, f = 0, h = "", m = [];
            if (!e)return e;
            e += "";
            do a = d.indexOf(e.charAt(p++)), s = d.indexOf(e.charAt(p++)), l = d.indexOf(e.charAt(p++)), u = d.indexOf(e.charAt(p++)), c = a << 18 | s << 12 | l << 6 | u, i = c >> 16 & 255, r = c >> 8 & 255, o = 255 & c, m[f++] = 64 == l ? String.fromCharCode(i) : 64 == u ? String.fromCharCode(i, r) : String.fromCharCode(i, r, o); while (p < e.length);
            return h = m.join(""), n ? t(h) : h
        }, i = function (t, n) {
            if (n && e(t), "function" == typeof window.btoa)return window.btoa(t);
            var i, r, o, a, s, l, u, c, d = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=", p = 0, f = 0, h = "", m = [];
            if (!t)return t;
            do i = t.charCodeAt(p++), r = t.charCodeAt(p++), o = t.charCodeAt(p++), c = i << 16 | r << 8 | o, a = c >> 18 & 63, s = c >> 12 & 63, l = c >> 6 & 63, u = 63 & c, m[f++] = d.charAt(a) + d.charAt(s) + d.charAt(l) + d.charAt(u); while (p < t.length);
            h = m.join("");
            var g = t.length % 3;
            return (g ? h.slice(0, g - 3) : h) + "===".slice(g || 3)
        };
        return {utf8_encode: e, utf8_decode: t, atob: n, btoa: i}
    }), i("moxie/runtime/Runtime", ["moxie/core/utils/Basic", "moxie/core/utils/Dom", "moxie/core/EventTarget"], function (e, t, n) {
        function i(n, r, a, s, l) {
            var u, c = this, d = e.guid(r + "_"), p = l || "browser";
            n = n || {}, o[d] = this, a = e.extend({
                access_binary: !1,
                access_image_binary: !1,
                display_media: !1,
                do_cors: !1,
                drag_and_drop: !1,
                filter_by_extension: !0,
                resize_image: !1,
                report_upload_progress: !1,
                return_response_headers: !1,
                return_response_type: !1,
                return_status_code: !0,
                send_custom_headers: !1,
                select_file: !1,
                select_folder: !1,
                select_multiple: !0,
                send_binary_string: !1,
                send_browser_cookies: !0,
                send_multipart: !0,
                slice_blob: !1,
                stream_upload: !1,
                summon_file_dialog: !1,
                upload_filesize: !0,
                use_http_method: !0
            }, a), n.preferred_caps && (p = i.getMode(s, n.preferred_caps, p)), u = function () {
                var t = {};
                return {
                    exec: function (e, n, i, r) {
                        return u[n] && (t[e] || (t[e] = {
                            context: this,
                            instance: new u[n]
                        }), t[e].instance[i]) ? t[e].instance[i].apply(this, r) : void 0
                    }, removeInstance: function (e) {
                        delete t[e]
                    }, removeAllInstances: function () {
                        var n = this;
                        e.each(t, function (t, i) {
                            "function" === e.typeOf(t.instance.destroy) && t.instance.destroy.call(t.context), n.removeInstance(i)
                        })
                    }
                }
            }(), e.extend(this, {
                initialized: !1,
                uid: d,
                type: r,
                mode: i.getMode(s, n.required_caps, p),
                shimid: d + "_container",
                clients: 0,
                options: n,
                can: function (t, n) {
                    var r = arguments[2] || a;
                    if ("string" === e.typeOf(t) && "undefined" === e.typeOf(n) && (t = i.parseCaps(t)), "object" === e.typeOf(t)) {
                        for (var o in t)if (!this.can(o, t[o], r))return !1;
                        return !0
                    }
                    return "function" === e.typeOf(r[t]) ? r[t].call(this, n) : n === r[t]
                },
                getShimContainer: function () {
                    var n, i = t.get(this.shimid);
                    return i || (n = this.options.container ? t.get(this.options.container) : document.body, i = document.createElement("div"), i.id = this.shimid, i.className = "moxie-shim moxie-shim-" + this.type, e.extend(i.style, {
                        position: "absolute",
                        top: "0px",
                        left: "0px",
                        width: "1px",
                        height: "1px",
                        overflow: "hidden"
                    }), n.appendChild(i), n = null), i
                },
                getShim: function () {
                    return u
                },
                shimExec: function (e, t) {
                    var n = [].slice.call(arguments, 2);
                    return c.getShim().exec.call(this, this.uid, e, t, n)
                },
                exec: function (e, t) {
                    var n = [].slice.call(arguments, 2);
                    return c[e] && c[e][t] ? c[e][t].apply(this, n) : c.shimExec.apply(this, arguments)
                },
                destroy: function () {
                    if (c) {
                        var e = t.get(this.shimid);
                        e && e.parentNode.removeChild(e), u && u.removeAllInstances(), this.unbindAll(), delete o[this.uid], this.uid = null, d = c = u = e = null
                    }
                }
            }), this.mode && n.required_caps && !this.can(n.required_caps) && (this.mode = !1)
        }

        var r = {}, o = {};
        return i.order = "html5,flash,silverlight,html4", i.getRuntime = function (e) {
            return o[e] ? o[e] : !1
        }, i.addConstructor = function (e, t) {
            t.prototype = n.instance, r[e] = t
        }, i.getConstructor = function (e) {
            return r[e] || null
        }, i.getInfo = function (e) {
            var t = i.getRuntime(e);
            return t ? {
                uid: t.uid, type: t.type, mode: t.mode, can: function () {
                    return t.can.apply(t, arguments)
                }
            } : null
        }, i.parseCaps = function (t) {
            var n = {};
            return "string" !== e.typeOf(t) ? t || {} : (e.each(t.split(","), function (e) {
                n[e] = !0
            }), n)
        }, i.can = function (e, t) {
            var n, r, o = i.getConstructor(e);
            return o ? (n = new o({required_caps: t}), r = n.mode, n.destroy(), !!r) : !1
        }, i.thatCan = function (e, t) {
            var n = (t || i.order).split(/\s*,\s*/);
            for (var r in n)if (i.can(n[r], e))return n[r];
            return null
        }, i.getMode = function (t, n, i) {
            var r = null;
            if ("undefined" === e.typeOf(i) && (i = "browser"), n && !e.isEmptyObj(t)) {
                if (e.each(n, function (n, i) {
                        if (t.hasOwnProperty(i)) {
                            var o = t[i](n);
                            if ("string" == typeof o && (o = [o]), r) {
                                if (!(r = e.arrayIntersect(r, o)))return r = !1
                            } else r = o
                        }
                    }), r)return -1 !== e.inArray(i, r) ? i : r[0];
                if (r === !1)return !1
            }
            return i
        }, i.capTrue = function () {
            return !0
        }, i.capFalse = function () {
            return !1
        }, i.capTest = function (e) {
            return function () {
                return !!e
            }
        }, i
    }), i("moxie/runtime/RuntimeClient", ["moxie/core/Exceptions", "moxie/core/utils/Basic", "moxie/runtime/Runtime"], function (e, t, n) {
        return function () {
            var i;
            t.extend(this, {
                connectRuntime: function (r) {
                    function o(t) {
                        var a, l;
                        return t.length ? (a = t.shift(), (l = n.getConstructor(a)) ? (i = new l(r), i.bind("Init", function () {
                            i.initialized = !0, setTimeout(function () {
                                i.clients++, s.trigger("RuntimeInit", i)
                            }, 1)
                        }), i.bind("Error", function () {
                            i.destroy(), o(t)
                        }), i.mode ? void i.init() : void i.trigger("Error")) : void o(t)) : (s.trigger("RuntimeError", new e.RuntimeError(e.RuntimeError.NOT_INIT_ERR)), void(i = null))
                    }

                    var a, s = this;
                    if ("string" === t.typeOf(r) ? a = r : "string" === t.typeOf(r.ruid) && (a = r.ruid), a) {
                        if (i = n.getRuntime(a))return i.clients++, i;
                        throw new e.RuntimeError(e.RuntimeError.NOT_INIT_ERR)
                    }
                    o((r.runtime_order || n.order).split(/\s*,\s*/))
                }, getRuntime: function () {
                    return i && i.uid ? i : (i = null, null)
                }, disconnectRuntime: function () {
                    i && --i.clients <= 0 && (i.destroy(), i = null)
                }
            })
        }
    }), i("moxie/file/Blob", ["moxie/core/utils/Basic", "moxie/core/utils/Encode", "moxie/runtime/RuntimeClient"], function (e, t, n) {
        function i(o, a) {
            function s(t, n, o) {
                var a, s = r[this.uid];
                return "string" === e.typeOf(s) && s.length ? (a = new i(null, {
                    type: o,
                    size: n - t
                }), a.detach(s.substr(t, a.size)), a) : null
            }

            n.call(this), o && this.connectRuntime(o), a ? "string" === e.typeOf(a) && (a = {data: a}) : a = {}, e.extend(this, {
                uid: a.uid || e.guid("uid_"),
                ruid: o,
                size: a.size || 0,
                type: a.type || "",
                slice: function (e, t, n) {
                    return this.isDetached() ? s.apply(this, arguments) : this.getRuntime().exec.call(this, "Blob", "slice", this.getSource(), e, t, n)
                },
                getSource: function () {
                    return r[this.uid] ? r[this.uid] : null
                },
                detach: function (e) {
                    this.ruid && (this.getRuntime().exec.call(this, "Blob", "destroy", r[this.uid]), this.disconnectRuntime(), this.ruid = null), e = e || "";
                    var n = e.match(/^data:([^;]*);base64,/);
                    n && (this.type = n[1], e = t.atob(e.substring(e.indexOf("base64,") + 7))), this.size = e.length, r[this.uid] = e
                },
                isDetached: function () {
                    return !this.ruid && "string" === e.typeOf(r[this.uid])
                },
                destroy: function () {
                    this.detach(), delete r[this.uid]
                }
            }), a.data ? this.detach(a.data) : r[this.uid] = a
        }

        var r = {};
        return i
    }), i("moxie/file/File", ["moxie/core/utils/Basic", "moxie/core/utils/Mime", "moxie/file/Blob"], function (e, t, n) {
        function i(i, r) {
            var o, a;
            if (r || (r = {}), a = r.type && "" !== r.type ? r.type : t.getFileMime(r.name), r.name)o = r.name.replace(/\\/g, "/"), o = o.substr(o.lastIndexOf("/") + 1); else {
                var s = a.split("/")[0];
                o = e.guid(("" !== s ? s : "file") + "_"), t.extensions[a] && (o += "." + t.extensions[a][0])
            }
            n.apply(this, arguments), e.extend(this, {
                type: a || "",
                name: o || e.guid("file_"),
                lastModifiedDate: r.lastModifiedDate || (new Date).toLocaleString()
            })
        }

        return i.prototype = n.prototype, i
    }), i("moxie/file/FileInput", ["moxie/core/utils/Basic", "moxie/core/utils/Mime", "moxie/core/utils/Dom", "moxie/core/Exceptions", "moxie/core/EventTarget", "moxie/core/I18n", "moxie/file/File", "moxie/runtime/Runtime", "moxie/runtime/RuntimeClient"], function (e, t, n, i, r, o, a, s, l) {
        function u(r) {
            var u, d, p, f = this;
            if (-1 !== e.inArray(e.typeOf(r), ["string", "node"]) && (r = {browse_button: r}), d = n.get(r.browse_button), !d)throw new i.DOMException(i.DOMException.NOT_FOUND_ERR);
            p = {
                accept: [{title: o.translate("All Files"), extensions: "*"}],
                name: "file",
                multiple: !1,
                required_caps: !1,
                container: d.parentNode || document.body
            }, r = e.extend({}, p, r), "string" == typeof r.required_caps && (r.required_caps = s.parseCaps(r.required_caps)), "string" == typeof r.accept && (r.accept = t.mimes2extList(r.accept)), u = n.get(r.container), u || (u = document.body), "static" === n.getStyle(u, "position") && (u.style.position = "relative"), u = d = null, l.call(f), e.extend(f, {
                uid: e.guid("uid_"),
                ruid: null,
                shimid: null,
                files: null,
                init: function () {
                    f.convertEventPropsToHandlers(c), f.bind("RuntimeInit", function (t, i) {
                        f.ruid = i.uid, f.shimid = i.shimid, f.bind("Ready", function () {
                            f.trigger("Refresh")
                        }, 999), f.bind("Change", function () {
                            var t = i.exec.call(f, "FileInput", "getFiles");
                            f.files = [], e.each(t, function (e) {
                                return 0 === e.size ? !0 : void f.files.push(new a(f.ruid, e))
                            })
                        }, 999), f.bind("Refresh", function () {
                            var t, o, a, s;
                            a = n.get(r.browse_button), s = n.get(i.shimid), a && (t = n.getPos(a, n.get(r.container)), o = n.getSize(a), s && e.extend(s.style, {
                                top: t.y + "px",
                                left: t.x + "px",
                                width: o.w + "px",
                                height: o.h + "px"
                            })), s = a = null
                        }), i.exec.call(f, "FileInput", "init", r)
                    }), f.connectRuntime(e.extend({}, r, {required_caps: {select_file: !0}}))
                },
                disable: function (t) {
                    var n = this.getRuntime();
                    n && n.exec.call(this, "FileInput", "disable", "undefined" === e.typeOf(t) ? !0 : t)
                },
                refresh: function () {
                    f.trigger("Refresh")
                },
                destroy: function () {
                    var t = this.getRuntime();
                    t && (t.exec.call(this, "FileInput", "destroy"), this.disconnectRuntime()), "array" === e.typeOf(this.files) && e.each(this.files, function (e) {
                        e.destroy()
                    }), this.files = null
                }
            })
        }

        var c = ["ready", "change", "cancel", "mouseenter", "mouseleave", "mousedown", "mouseup"];
        return u.prototype = r.instance, u
    }), i("moxie/file/FileDrop", ["moxie/core/I18n", "moxie/core/utils/Dom", "moxie/core/Exceptions", "moxie/core/utils/Basic", "moxie/file/File", "moxie/runtime/RuntimeClient", "moxie/core/EventTarget", "moxie/core/utils/Mime"], function (e, t, n, i, r, o, a, s) {
        function l(n) {
            var a, l = this;
            "string" == typeof n && (n = {drop_zone: n}), a = {
                accept: [{
                    title: e.translate("All Files"),
                    extensions: "*"
                }], required_caps: {drag_and_drop: !0}
            }, n = "object" == typeof n ? i.extend({}, a, n) : a, n.container = t.get(n.drop_zone) || document.body, "static" === t.getStyle(n.container, "position") && (n.container.style.position = "relative"), "string" == typeof n.accept && (n.accept = s.mimes2extList(n.accept)), o.call(l), i.extend(l, {
                uid: i.guid("uid_"),
                ruid: null,
                files: null,
                init: function () {
                    l.convertEventPropsToHandlers(u), l.bind("RuntimeInit", function (e, t) {
                        l.ruid = t.uid, l.bind("Drop", function () {
                            var e = t.exec.call(l, "FileDrop", "getFiles");
                            l.files = [], i.each(e, function (e) {
                                l.files.push(new r(l.ruid, e))
                            })
                        }, 999), t.exec.call(l, "FileDrop", "init", n), l.dispatchEvent("ready")
                    }), l.connectRuntime(n)
                },
                destroy: function () {
                    var e = this.getRuntime();
                    e && (e.exec.call(this, "FileDrop", "destroy"), this.disconnectRuntime()), this.files = null
                }
            })
        }

        var u = ["ready", "dragenter", "dragleave", "drop", "error"];
        return l.prototype = a.instance, l
    }), i("moxie/runtime/RuntimeTarget", ["moxie/core/utils/Basic", "moxie/runtime/RuntimeClient", "moxie/core/EventTarget"], function (e, t, n) {
        function i() {
            this.uid = e.guid("uid_"), t.call(this), this.destroy = function () {
                this.disconnectRuntime(), this.unbindAll()
            }
        }

        return i.prototype = n.instance, i
    }), i("moxie/file/FileReader", ["moxie/core/utils/Basic", "moxie/core/utils/Encode", "moxie/core/Exceptions", "moxie/core/EventTarget", "moxie/file/Blob", "moxie/file/File", "moxie/runtime/RuntimeTarget"], function (e, t, n, i, r, o, a) {
        function s() {
            function i(e, i) {
                function c(e) {
                    u.readyState = s.DONE, u.error = e, u.trigger("error"), d()
                }

                function d() {
                    o.destroy(), o = null, u.trigger("loadend")
                }

                function p(t) {
                    o.bind("Error", function (e, t) {
                        c(t)
                    }), o.bind("Progress", function (e) {
                        u.result = t.exec.call(o, "FileReader", "getResult"), u.trigger(e)
                    }), o.bind("Load", function (e) {
                        u.readyState = s.DONE, u.result = t.exec.call(o, "FileReader", "getResult"), u.trigger(e), d()
                    }), t.exec.call(o, "FileReader", "read", e, i)
                }

                if (o = new a, this.convertEventPropsToHandlers(l), this.readyState === s.LOADING)return c(new n.DOMException(n.DOMException.INVALID_STATE_ERR));
                if (this.readyState = s.LOADING, this.trigger("loadstart"), i instanceof r)if (i.isDetached()) {
                    var f = i.getSource();
                    switch (e) {
                        case"readAsText":
                        case"readAsBinaryString":
                            this.result = f;
                            break;
                        case"readAsDataURL":
                            this.result = "data:" + i.type + ";base64," + t.btoa(f)
                    }
                    this.readyState = s.DONE, this.trigger("load"), d()
                } else p(o.connectRuntime(i.ruid)); else c(new n.DOMException(n.DOMException.NOT_FOUND_ERR))
            }

            var o, u = this;
            e.extend(this, {
                uid: e.guid("uid_"),
                readyState: s.EMPTY,
                result: null,
                error: null,
                readAsBinaryString: function (e) {
                    i.call(this, "readAsBinaryString", e)
                },
                readAsDataURL: function (e) {
                    i.call(this, "readAsDataURL", e)
                },
                readAsText: function (e) {
                    i.call(this, "readAsText", e)
                },
                abort: function () {
                    this.result = null, -1 === e.inArray(this.readyState, [s.EMPTY, s.DONE]) && (this.readyState === s.LOADING && (this.readyState = s.DONE), o && o.getRuntime().exec.call(this, "FileReader", "abort"), this.trigger("abort"), this.trigger("loadend"))
                },
                destroy: function () {
                    this.abort(), o && (o.getRuntime().exec.call(this, "FileReader", "destroy"), o.disconnectRuntime()), u = o = null
                }
            })
        }

        var l = ["loadstart", "progress", "load", "abort", "error", "loadend"];
        return s.EMPTY = 0, s.LOADING = 1, s.DONE = 2, s.prototype = i.instance, s
    }), i("moxie/core/utils/Url", [], function () {
        var e = function (t, n) {
            for (var i = ["source", "scheme", "authority", "userInfo", "user", "pass", "host", "port", "relative", "path", "directory", "file", "query", "fragment"], r = i.length, o = {
                http: 80,
                https: 443
            }, a = {}, s = /^(?:([^:\/?#]+):)?(?:\/\/()(?:(?:()(?:([^:@]*):?([^:@]*))?@)?([^:\/?#]*)(?::(\d*))?))?()(?:(()(?:(?:[^?#\/]*\/)*)()(?:[^?#]*))(?:\\?([^#]*))?(?:#(.*))?)/, l = s.exec(t || ""); r--;)l[r] && (a[i[r]] = l[r]);
            if (!a.scheme) {
                n && "string" != typeof n || (n = e(n || document.location.href)), a.scheme = n.scheme, a.host = n.host, a.port = n.port;
                var u = "";
                /^[^\/]/.test(a.path) && (u = n.path, /(\/|\/[^\.]+)$/.test(u) ? u += "/" : u = u.replace(/\/[^\/]+$/, "/")), a.path = u + (a.path || "")
            }
            return a.port || (a.port = o[a.scheme] || 80), a.port = parseInt(a.port, 10), a.path || (a.path = "/"), delete a.source, a
        }, t = function (t) {
            var n = {http: 80, https: 443}, i = e(t);
            return i.scheme + "://" + i.host + (i.port !== n[i.scheme] ? ":" + i.port : "") + i.path + (i.query ? i.query : "")
        }, n = function (t) {
            function n(e) {
                return [e.scheme, e.host, e.port].join("/")
            }

            return "string" == typeof t && (t = e(t)), n(e()) === n(t)
        };
        return {parseUrl: e, resolveUrl: t, hasSameOrigin: n}
    }), i("moxie/file/FileReaderSync", ["moxie/core/utils/Basic", "moxie/runtime/RuntimeClient", "moxie/core/utils/Encode"], function (e, t, n) {
        return function () {
            function i(e, t) {
                if (!t.isDetached()) {
                    var i = this.connectRuntime(t.ruid).exec.call(this, "FileReaderSync", "read", e, t);
                    return this.disconnectRuntime(), i
                }
                var r = t.getSource();
                switch (e) {
                    case"readAsBinaryString":
                        return r;
                    case"readAsDataURL":
                        return "data:" + t.type + ";base64," + n.btoa(r);
                    case"readAsText":
                        for (var o = "", a = 0, s = r.length; s > a; a++)o += String.fromCharCode(r[a]);
                        return o
                }
            }

            t.call(this), e.extend(this, {
                uid: e.guid("uid_"), readAsBinaryString: function (e) {
                    return i.call(this, "readAsBinaryString", e)
                }, readAsDataURL: function (e) {
                    return i.call(this, "readAsDataURL", e)
                }, readAsText: function (e) {
                    return i.call(this, "readAsText", e)
                }
            })
        }
    }), i("moxie/xhr/FormData", ["moxie/core/Exceptions", "moxie/core/utils/Basic", "moxie/file/Blob"], function (e, t, n) {
        function i() {
            var e, i = [];
            t.extend(this, {
                append: function (r, o) {
                    var a = this, s = t.typeOf(o);
                    o instanceof n ? e = {name: r, value: o} : "array" === s ? (r += "[]", t.each(o, function (e) {
                        a.append(r, e)
                    })) : "object" === s ? t.each(o, function (e, t) {
                        a.append(r + "[" + t + "]", e)
                    }) : "null" === s || "undefined" === s || "number" === s && isNaN(o) ? a.append(r, "false") : i.push({
                        name: r,
                        value: o.toString()
                    })
                }, hasBlob: function () {
                    return !!this.getBlob()
                }, getBlob: function () {
                    return e && e.value || null
                }, getBlobName: function () {
                    return e && e.name || null
                }, each: function (n) {
                    t.each(i, function (e) {
                        n(e.value, e.name)
                    }), e && n(e.value, e.name)
                }, destroy: function () {
                    e = null, i = []
                }
            })
        }

        return i
    }), i("moxie/xhr/XMLHttpRequest", ["moxie/core/utils/Basic", "moxie/core/Exceptions", "moxie/core/EventTarget", "moxie/core/utils/Encode", "moxie/core/utils/Url", "moxie/runtime/Runtime", "moxie/runtime/RuntimeTarget", "moxie/file/Blob", "moxie/file/FileReaderSync", "moxie/xhr/FormData", "moxie/core/utils/Env", "moxie/core/utils/Mime"], function (e, t, n, i, r, o, a, s, l, u, c, d) {
        function p() {
            this.uid = e.guid("uid_")
        }

        function f() {
            function n(e, t) {
                return R.hasOwnProperty(e) ? 1 === arguments.length ? c.can("define_property") ? R[e] : _[e] : void(c.can("define_property") ? R[e] = t : _[e] = t) : void 0
            }

            function l(t) {
                function i() {
                    T.destroy(), T = null, s.dispatchEvent("loadend"), s = null
                }

                function r(r) {
                    T.bind("LoadStart", function (e) {
                        n("readyState", f.LOADING), s.dispatchEvent("readystatechange"), s.dispatchEvent(e), I && s.upload.dispatchEvent(e)
                    }), T.bind("Progress", function (e) {
                        n("readyState") !== f.LOADING && (n("readyState", f.LOADING), s.dispatchEvent("readystatechange")), s.dispatchEvent(e)
                    }), T.bind("UploadProgress", function (e) {
                        I && s.upload.dispatchEvent({
                            type: "progress",
                            lengthComputable: !1,
                            total: e.total,
                            loaded: e.loaded
                        })
                    }), T.bind("Load", function (t) {
                        n("readyState", f.DONE), n("status", Number(r.exec.call(T, "XMLHttpRequest", "getStatus") || 0)), n("statusText", h[n("status")] || ""), n("response", r.exec.call(T, "XMLHttpRequest", "getResponse", n("responseType"))), ~e.inArray(n("responseType"), ["text", ""]) ? n("responseText", n("response")) : "document" === n("responseType") && n("responseXML", n("response")), B = r.exec.call(T, "XMLHttpRequest", "getAllResponseHeaders"), s.dispatchEvent("readystatechange"), n("status") > 0 ? (I && s.upload.dispatchEvent(t), s.dispatchEvent(t)) : (L = !0, s.dispatchEvent("error")), i()
                    }), T.bind("Abort", function (e) {
                        s.dispatchEvent(e), i()
                    }), T.bind("Error", function (e) {
                        L = !0, n("readyState", f.DONE), s.dispatchEvent("readystatechange"), F = !0, s.dispatchEvent(e), i()
                    }), r.exec.call(T, "XMLHttpRequest", "send", {
                        url: v,
                        method: y,
                        async: C,
                        user: b,
                        password: x,
                        headers: D,
                        mimeType: O,
                        encoding: N,
                        responseType: s.responseType,
                        withCredentials: s.withCredentials,
                        options: j
                    }, t)
                }

                var s = this;
                w = (new Date).getTime(), T = new a, "string" == typeof j.required_caps && (j.required_caps = o.parseCaps(j.required_caps)), j.required_caps = e.extend({}, j.required_caps, {return_response_type: s.responseType}), t instanceof u && (j.required_caps.send_multipart = !0), M || (j.required_caps.do_cors = !0), j.ruid ? r(T.connectRuntime(j)) : (T.bind("RuntimeInit", function (e, t) {
                    r(t)
                }), T.bind("RuntimeError", function (e, t) {
                    s.dispatchEvent("RuntimeError", t)
                }), T.connectRuntime(j))
            }

            function g() {
                n("responseText", ""), n("responseXML", null), n("response", null), n("status", 0), n("statusText", ""), w = E = null
            }

            var v, y, b, x, w, E, T, S, _ = this, R = {
                timeout: 0,
                readyState: f.UNSENT,
                withCredentials: !1,
                status: 0,
                statusText: "",
                responseType: "",
                responseXML: null,
                responseText: null,
                response: null
            }, C = !0, D = {}, N = null, O = null, A = !1, k = !1, I = !1, F = !1, L = !1, M = !1, H = null, P = null, j = {}, B = "";
            e.extend(this, R, {
                uid: e.guid("uid_"), upload: new p, open: function (o, a, s, l, u) {
                    var c;
                    if (!o || !a)throw new t.DOMException(t.DOMException.SYNTAX_ERR);
                    if (/[\u0100-\uffff]/.test(o) || i.utf8_encode(o) !== o)throw new t.DOMException(t.DOMException.SYNTAX_ERR);
                    if (~e.inArray(o.toUpperCase(), ["CONNECT", "DELETE", "GET", "HEAD", "OPTIONS", "POST", "PUT", "TRACE", "TRACK"]) && (y = o.toUpperCase()), ~e.inArray(y, ["CONNECT", "TRACE", "TRACK"]))throw new t.DOMException(t.DOMException.SECURITY_ERR);
                    if (a = i.utf8_encode(a), c = r.parseUrl(a), M = r.hasSameOrigin(c), v = r.resolveUrl(a), (l || u) && !M)throw new t.DOMException(t.DOMException.INVALID_ACCESS_ERR);
                    if (b = l || c.user, x = u || c.pass, C = s || !0, C === !1 && (n("timeout") || n("withCredentials") || "" !== n("responseType")))throw new t.DOMException(t.DOMException.INVALID_ACCESS_ERR);
                    A = !C, k = !1, D = {}, g.call(this), n("readyState", f.OPENED), this.convertEventPropsToHandlers(["readystatechange"]), this.dispatchEvent("readystatechange")
                }, setRequestHeader: function (r, o) {
                    var a = ["accept-charset", "accept-encoding", "access-control-request-headers", "access-control-request-method", "connection", "content-length", "cookie", "cookie2", "content-transfer-encoding", "date", "expect", "host", "keep-alive", "origin", "referer", "te", "trailer", "transfer-encoding", "upgrade", "user-agent", "via"];
                    if (n("readyState") !== f.OPENED || k)throw new t.DOMException(t.DOMException.INVALID_STATE_ERR);
                    if (/[\u0100-\uffff]/.test(r) || i.utf8_encode(r) !== r)throw new t.DOMException(t.DOMException.SYNTAX_ERR);
                    return r = e.trim(r).toLowerCase(), ~e.inArray(r, a) || /^(proxy\-|sec\-)/.test(r) ? !1 : (D[r] ? D[r] += ", " + o : D[r] = o, !0)
                }, getAllResponseHeaders: function () {
                    return B || ""
                }, getResponseHeader: function (t) {
                    return t = t.toLowerCase(), L || ~e.inArray(t, ["set-cookie", "set-cookie2"]) ? null : B && "" !== B && (S || (S = {}, e.each(B.split(/\r\n/), function (t) {
                        var n = t.split(/:\s+/);
                        2 === n.length && (n[0] = e.trim(n[0]), S[n[0].toLowerCase()] = {
                            header: n[0],
                            value: e.trim(n[1])
                        })
                    })), S.hasOwnProperty(t)) ? S[t].header + ": " + S[t].value : null
                }, overrideMimeType: function (i) {
                    var r, o;
                    if (~e.inArray(n("readyState"), [f.LOADING, f.DONE]))throw new t.DOMException(t.DOMException.INVALID_STATE_ERR);
                    if (i = e.trim(i.toLowerCase()), /;/.test(i) && (r = i.match(/^([^;]+)(?:;\scharset\=)?(.*)$/)) && (i = r[1], r[2] && (o = r[2])), !d.mimes[i])throw new t.DOMException(t.DOMException.SYNTAX_ERR);
                    H = i, P = o
                }, send: function (n, r) {
                    if (j = "string" === e.typeOf(r) ? {ruid: r} : r ? r : {}, this.convertEventPropsToHandlers(m), this.upload.convertEventPropsToHandlers(m), this.readyState !== f.OPENED || k)throw new t.DOMException(t.DOMException.INVALID_STATE_ERR);
                    if (n instanceof s)j.ruid = n.ruid, O = n.type || "application/octet-stream"; else if (n instanceof u) {
                        if (n.hasBlob()) {
                            var o = n.getBlob();
                            j.ruid = o.ruid, O = o.type || "application/octet-stream"
                        }
                    } else"string" == typeof n && (N = "UTF-8", O = "text/plain;charset=UTF-8", n = i.utf8_encode(n));
                    this.withCredentials || (this.withCredentials = j.required_caps && j.required_caps.send_browser_cookies && !M), I = !A && this.upload.hasEventListener(), L = !1, F = !n, A || (k = !0), l.call(this, n)
                }, abort: function () {
                    if (L = !0, A = !1, ~e.inArray(n("readyState"), [f.UNSENT, f.OPENED, f.DONE]))n("readyState", f.UNSENT); else {
                        if (n("readyState", f.DONE), k = !1, !T)throw new t.DOMException(t.DOMException.INVALID_STATE_ERR);
                        T.getRuntime().exec.call(T, "XMLHttpRequest", "abort", F), F = !0
                    }
                }, destroy: function () {
                    T && ("function" === e.typeOf(T.destroy) && T.destroy(), T = null), this.unbindAll(), this.upload && (this.upload.unbindAll(), this.upload = null)
                }
            })
        }

        var h = {
            100: "Continue",
            101: "Switching Protocols",
            102: "Processing",
            200: "OK",
            201: "Created",
            202: "Accepted",
            203: "Non-Authoritative Information",
            204: "No Content",
            205: "Reset Content",
            206: "Partial Content",
            207: "Multi-Status",
            226: "IM Used",
            300: "Multiple Choices",
            301: "Moved Permanently",
            302: "Found",
            303: "See Other",
            304: "Not Modified",
            305: "Use Proxy",
            306: "Reserved",
            307: "Temporary Redirect",
            400: "Bad Request",
            401: "Unauthorized",
            402: "Payment Required",
            403: "Forbidden",
            404: "Not Found",
            405: "Method Not Allowed",
            406: "Not Acceptable",
            407: "Proxy Authentication Required",
            408: "Request Timeout",
            409: "Conflict",
            410: "Gone",
            411: "Length Required",
            412: "Precondition Failed",
            413: "Request Entity Too Large",
            414: "Request-URI Too Long",
            415: "Unsupported Media Type",
            416: "Requested Range Not Satisfiable",
            417: "Expectation Failed",
            422: "Unprocessable Entity",
            423: "Locked",
            424: "Failed Dependency",
            426: "Upgrade Required",
            500: "Internal Server Error",
            501: "Not Implemented",
            502: "Bad Gateway",
            503: "Service Unavailable",
            504: "Gateway Timeout",
            505: "HTTP Version Not Supported",
            506: "Variant Also Negotiates",
            507: "Insufficient Storage",
            510: "Not Extended"
        };
        p.prototype = n.instance;
        var m = ["loadstart", "progress", "abort", "error", "load", "timeout", "loadend"];
        return f.UNSENT = 0, f.OPENED = 1, f.HEADERS_RECEIVED = 2, f.LOADING = 3, f.DONE = 4, f.prototype = n.instance, f
    }), i("moxie/runtime/Transporter", ["moxie/core/utils/Basic", "moxie/core/utils/Encode", "moxie/runtime/RuntimeClient", "moxie/core/EventTarget"], function (e, t, n, i) {
        function r() {
            function i() {
                c = d = 0, u = this.result = null
            }

            function o(t, n) {
                var i = this;
                l = n, i.bind("TransportingProgress", function (t) {
                    d = t.loaded, c > d && -1 === e.inArray(i.state, [r.IDLE, r.DONE]) && a.call(i)
                }, 999), i.bind("TransportingComplete", function () {
                    d = c, i.state = r.DONE, u = null, i.result = l.exec.call(i, "Transporter", "getAsBlob", t || "")
                }, 999), i.state = r.BUSY, i.trigger("TransportingStarted"), a.call(i)
            }

            function a() {
                var e, n = this, i = c - d;
                p > i && (p = i), e = t.btoa(u.substr(d, p)), l.exec.call(n, "Transporter", "receive", e, c)
            }

            var s, l, u, c, d, p;
            n.call(this), e.extend(this, {
                uid: e.guid("uid_"),
                state: r.IDLE,
                result: null,
                transport: function (t, n, r) {
                    var a = this;
                    if (r = e.extend({chunk_size: 204798}, r), (s = r.chunk_size % 3) && (r.chunk_size += 3 - s), p = r.chunk_size, i.call(this), u = t, c = t.length, "string" === e.typeOf(r) || r.ruid)o.call(a, n, this.connectRuntime(r)); else {
                        var l = function (e, t) {
                            a.unbind("RuntimeInit", l), o.call(a, n, t)
                        };
                        this.bind("RuntimeInit", l), this.connectRuntime(r)
                    }
                },
                abort: function () {
                    var e = this;
                    e.state = r.IDLE, l && (l.exec.call(e, "Transporter", "clear"), e.trigger("TransportingAborted")), i.call(e)
                },
                destroy: function () {
                    this.unbindAll(), l = null, this.disconnectRuntime(), i.call(this)
                }
            })
        }

        return r.IDLE = 0, r.BUSY = 1, r.DONE = 2, r.prototype = i.instance, r
    }), i("moxie/image/Image", ["moxie/core/utils/Basic", "moxie/core/utils/Dom", "moxie/core/Exceptions", "moxie/file/FileReaderSync", "moxie/xhr/XMLHttpRequest", "moxie/runtime/Runtime", "moxie/runtime/RuntimeClient", "moxie/runtime/Transporter", "moxie/core/utils/Env", "moxie/core/EventTarget", "moxie/file/Blob", "moxie/file/File", "moxie/core/utils/Encode"], function (e, t, n, i, r, o, a, s, l, u, c, d, p) {
        function f() {
            function i(e) {
                e || (e = this.getRuntime().exec.call(this, "Image", "getInfo")), this.size = e.size, this.width = e.width, this.height = e.height, this.type = e.type, this.meta = e.meta, "" === this.name && (this.name = e.name)
            }

            function u(t) {
                var i = e.typeOf(t);
                try {
                    if (t instanceof f) {
                        if (!t.size)throw new n.DOMException(n.DOMException.INVALID_STATE_ERR);
                        m.apply(this, arguments)
                    } else if (t instanceof c) {
                        if (!~e.inArray(t.type, ["image/jpeg", "image/png"]))throw new n.ImageError(n.ImageError.WRONG_FORMAT);
                        g.apply(this, arguments)
                    } else if (-1 !== e.inArray(i, ["blob", "file"]))u.call(this, new d(null, t), arguments[1]); else if ("string" === i)/^data:[^;]*;base64,/.test(t) ? u.call(this, new c(null, {data: t}), arguments[1]) : v.apply(this, arguments); else {
                        if ("node" !== i || "img" !== t.nodeName.toLowerCase())throw new n.DOMException(n.DOMException.TYPE_MISMATCH_ERR);
                        u.call(this, t.src, arguments[1])
                    }
                } catch (r) {
                    this.trigger("error", r)
                }
            }

            function m(t, n) {
                var i = this.connectRuntime(t.ruid);
                this.ruid = i.uid, i.exec.call(this, "Image", "loadFromImage", t, "undefined" === e.typeOf(n) ? !0 : n)
            }

            function g(t, n) {
                function i(e) {
                    r.ruid = e.uid, e.exec.call(r, "Image", "loadFromBlob", t)
                }

                var r = this;
                r.name = t.name || "", t.isDetached() ? (this.bind("RuntimeInit", function (e, t) {
                    i(t)
                }), n && "string" == typeof n.required_caps && (n.required_caps = o.parseCaps(n.required_caps)), this.connectRuntime(e.extend({
                    required_caps: {
                        access_image_binary: !0,
                        resize_image: !0
                    }
                }, n))) : i(this.connectRuntime(t.ruid))
            }

            function v(e, t) {
                var n, i = this;
                n = new r, n.open("get", e), n.responseType = "blob", n.onprogress = function (e) {
                    i.trigger(e)
                }, n.onload = function () {
                    g.call(i, n.response, !0)
                }, n.onerror = function (e) {
                    i.trigger(e)
                }, n.onloadend = function () {
                    n.destroy()
                }, n.bind("RuntimeError", function (e, t) {
                    i.trigger("RuntimeError", t)
                }), n.send(null, t)
            }

            a.call(this), e.extend(this, {
                uid: e.guid("uid_"),
                ruid: null,
                name: "",
                size: 0,
                width: 0,
                height: 0,
                type: "",
                meta: {},
                clone: function () {
                    this.load.apply(this, arguments)
                },
                load: function () {
                    this.bind("Load Resize", function () {
                        i.call(this)
                    }, 999), this.convertEventPropsToHandlers(h), u.apply(this, arguments)
                },
                downsize: function (t, i, r, o) {
                    try {
                        if (!this.size)throw new n.DOMException(n.DOMException.INVALID_STATE_ERR);
                        if (this.width > f.MAX_RESIZE_WIDTH || this.height > f.MAX_RESIZE_HEIGHT)throw new n.ImageError(n.ImageError.MAX_RESOLUTION_ERR);
                        (!t && !i || "undefined" === e.typeOf(r)) && (r = !1), t = t || this.width, i = i || this.height, o = "undefined" === e.typeOf(o) ? !0 : !!o, this.getRuntime().exec.call(this, "Image", "downsize", t, i, r, o)
                    } catch (a) {
                        this.trigger("error", a)
                    }
                },
                crop: function (e, t, n) {
                    this.downsize(e, t, !0, n)
                },
                getAsCanvas: function () {
                    if (!l.can("create_canvas"))throw new n.RuntimeError(n.RuntimeError.NOT_SUPPORTED_ERR);
                    var e = this.connectRuntime(this.ruid);
                    return e.exec.call(this, "Image", "getAsCanvas")
                },
                getAsBlob: function (e, t) {
                    if (!this.size)throw new n.DOMException(n.DOMException.INVALID_STATE_ERR);
                    return e || (e = "image/jpeg"), "image/jpeg" !== e || t || (t = 90), this.getRuntime().exec.call(this, "Image", "getAsBlob", e, t)
                },
                getAsDataURL: function (e, t) {
                    if (!this.size)throw new n.DOMException(n.DOMException.INVALID_STATE_ERR);
                    return this.getRuntime().exec.call(this, "Image", "getAsDataURL", e, t)
                },
                getAsBinaryString: function (e, t) {
                    var n = this.getAsDataURL(e, t);
                    return p.atob(n.substring(n.indexOf("base64,") + 7))
                },
                embed: function (i) {
                    function r() {
                        if (l.can("create_canvas")) {
                            var t = o.getAsCanvas();
                            if (t)return i.appendChild(t), t = null, o.destroy(), void h.trigger("embedded")
                        }
                        var r = o.getAsDataURL(a, u);
                        if (!r)throw new n.ImageError(n.ImageError.WRONG_FORMAT);
                        if (l.can("use_data_uri_of", r.length))i.innerHTML = '<img src="' + r + '" width="' + o.width + '" height="' + o.height + '" />', o.destroy(), h.trigger("embedded"); else {
                            var c = new s;
                            c.bind("TransportingComplete", function () {
                                d = h.connectRuntime(this.result.ruid), h.bind("Embedded", function () {
                                    e.extend(d.getShimContainer().style, {
                                        top: "0px",
                                        left: "0px",
                                        width: o.width + "px",
                                        height: o.height + "px"
                                    }), d = null
                                }, 999), d.exec.call(h, "ImageView", "display", this.result.uid, g, v), o.destroy()
                            }), c.transport(p.atob(r.substring(r.indexOf("base64,") + 7)), a, e.extend({}, m, {
                                required_caps: {display_media: !0},
                                runtime_order: "flash,silverlight",
                                container: i
                            }))
                        }
                    }

                    var o, a, u, c, d, h = this, m = arguments[1] || {}, g = this.width, v = this.height;
                    try {
                        if (!(i = t.get(i)))throw new n.DOMException(n.DOMException.INVALID_NODE_TYPE_ERR);
                        if (!this.size)throw new n.DOMException(n.DOMException.INVALID_STATE_ERR);
                        if (this.width > f.MAX_RESIZE_WIDTH || this.height > f.MAX_RESIZE_HEIGHT)throw new n.ImageError(n.ImageError.MAX_RESOLUTION_ERR);
                        if (a = m.type || this.type || "image/jpeg", u = m.quality || 90, c = "undefined" !== e.typeOf(m.crop) ? m.crop : !1, m.width)g = m.width, v = m.height || g; else {
                            var y = t.getSize(i);
                            y.w && y.h && (g = y.w, v = y.h)
                        }
                        return o = new f, o.bind("Resize", function () {
                            r.call(h)
                        }), o.bind("Load", function () {
                            o.downsize(g, v, c, !1)
                        }), o.clone(this, !1), o
                    } catch (b) {
                        this.trigger("error", b)
                    }
                },
                destroy: function () {
                    this.ruid && (this.getRuntime().exec.call(this, "Image", "destroy"), this.disconnectRuntime()), this.unbindAll()
                }
            })
        }

        var h = ["progress", "load", "error", "resize", "embedded"];
        return f.MAX_RESIZE_WIDTH = 6500, f.MAX_RESIZE_HEIGHT = 6500, f.prototype = u.instance, f
    }), i("moxie/runtime/html5/Runtime", ["moxie/core/utils/Basic", "moxie/core/Exceptions", "moxie/runtime/Runtime", "moxie/core/utils/Env"], function (e, t, n, i) {
        function r(t) {
            var r = this, s = n.capTest, l = n.capTrue, u = e.extend({
                access_binary: s(window.FileReader || window.File && window.File.getAsDataURL),
                access_image_binary: function () {
                    return r.can("access_binary") && !!a.Image
                },
                display_media: s(i.can("create_canvas") || i.can("use_data_uri_over32kb")),
                do_cors: s(window.XMLHttpRequest && "withCredentials"in new XMLHttpRequest),
                drag_and_drop: s(function () {
                    var e = document.createElement("div");
                    return ("draggable"in e || "ondragstart"in e && "ondrop"in e) && ("IE" !== i.browser || i.version > 9)
                }()),
                filter_by_extension: s(function () {
                    return "Chrome" === i.browser && i.version >= 28 || "IE" === i.browser && i.version >= 10
                }()),
                return_response_headers: l,
                return_response_type: function (e) {
                    return "json" === e && window.JSON ? !0 : i.can("return_response_type", e)
                },
                return_status_code: l,
                report_upload_progress: s(window.XMLHttpRequest && (new XMLHttpRequest).upload),
                resize_image: function () {
                    return r.can("access_binary") && i.can("create_canvas")
                },
                select_file: function () {
                    return i.can("use_fileinput") && window.File
                },
                select_folder: function () {
                    return r.can("select_file") && "Chrome" === i.browser && i.version >= 21
                },
                select_multiple: function () {
                    return !(!r.can("select_file") || "Safari" === i.browser && "Windows" === i.os || "iOS" === i.os && i.verComp(i.osVersion, "7.0.4", "<"))
                },
                send_binary_string: s(window.XMLHttpRequest && ((new XMLHttpRequest).sendAsBinary || window.Uint8Array && window.ArrayBuffer)),
                send_custom_headers: s(window.XMLHttpRequest),
                send_multipart: function () {
                    return !!(window.XMLHttpRequest && (new XMLHttpRequest).upload && window.FormData) || r.can("send_binary_string")
                },
                slice_blob: s(window.File && (File.prototype.mozSlice || File.prototype.webkitSlice || File.prototype.slice)),
                stream_upload: function () {
                    return r.can("slice_blob") && r.can("send_multipart")
                },
                summon_file_dialog: s(function () {
                    return "Firefox" === i.browser && i.version >= 4 || "Opera" === i.browser && i.version >= 12 || "IE" === i.browser && i.version >= 10 || !!~e.inArray(i.browser, ["Chrome", "Safari"])
                }()),
                upload_filesize: l
            }, arguments[2]);
            n.call(this, t, arguments[1] || o, u), e.extend(this, {
                init: function () {
                    this.trigger("Init")
                }, destroy: function (e) {
                    return function () {
                        e.call(r), e = r = null
                    }
                }(this.destroy)
            }), e.extend(this.getShim(), a)
        }

        var o = "html5", a = {};
        return n.addConstructor(o, r), a
    }), i("moxie/runtime/html5/file/Blob", ["moxie/runtime/html5/Runtime", "moxie/file/Blob"], function (e, t) {
        function n() {
            function e(e, t, n) {
                var i;
                if (!window.File.prototype.slice)return (i = window.File.prototype.webkitSlice || window.File.prototype.mozSlice) ? i.call(e, t, n) : null;
                try {
                    return e.slice(), e.slice(t, n)
                } catch (r) {
                    return e.slice(t, n - t)
                }
            }

            this.slice = function () {
                return new t(this.getRuntime().uid, e.apply(this, arguments))
            }
        }

        return e.Blob = n
    }), i("moxie/core/utils/Events", ["moxie/core/utils/Basic"], function (e) {
        function t() {
            this.returnValue = !1
        }

        function n() {
            this.cancelBubble = !0
        }

        var i = {}, r = "moxie_" + e.guid(), o = function (o, a, s, l) {
            var u, c;
            a = a.toLowerCase(), o.addEventListener ? (u = s, o.addEventListener(a, u, !1)) : o.attachEvent && (u = function () {
                var e = window.event;
                e.target || (e.target = e.srcElement), e.preventDefault = t, e.stopPropagation = n, s(e)
            }, o.attachEvent("on" + a, u)), o[r] || (o[r] = e.guid()), i.hasOwnProperty(o[r]) || (i[o[r]] = {}), c = i[o[r]], c.hasOwnProperty(a) || (c[a] = []), c[a].push({
                func: u,
                orig: s,
                key: l
            })
        }, a = function (t, n, o) {
            var a, s;
            if (n = n.toLowerCase(), t[r] && i[t[r]] && i[t[r]][n]) {
                a = i[t[r]][n];
                for (var l = a.length - 1; l >= 0 && (a[l].orig !== o && a[l].key !== o || (t.removeEventListener ? t.removeEventListener(n, a[l].func, !1) : t.detachEvent && t.detachEvent("on" + n, a[l].func), a[l].orig = null, a[l].func = null, a.splice(l, 1), o === s)); l--);
                if (a.length || delete i[t[r]][n], e.isEmptyObj(i[t[r]])) {
                    delete i[t[r]];
                    try {
                        delete t[r]
                    } catch (u) {
                        t[r] = s
                    }
                }
            }
        }, s = function (t, n) {
            t && t[r] && e.each(i[t[r]], function (e, i) {
                a(t, i, n)
            })
        };
        return {addEvent: o, removeEvent: a, removeAllEvents: s}
    }), i("moxie/runtime/html5/file/FileInput", ["moxie/runtime/html5/Runtime", "moxie/core/utils/Basic", "moxie/core/utils/Dom", "moxie/core/utils/Events", "moxie/core/utils/Mime", "moxie/core/utils/Env"], function (e, t, n, i, r, o) {
        function a() {
            var e, a = [];
            t.extend(this, {
                init: function (s) {
                    var l, u, c, d, p, f, h = this, m = h.getRuntime();
                    e = s, a = [], c = e.accept.mimes || r.extList2mimes(e.accept, m.can("filter_by_extension")), u = m.getShimContainer(), u.innerHTML = '<input id="' + m.uid + '" type="file" style="font-size:999px;opacity:0;"' + (e.multiple && m.can("select_multiple") ? "multiple" : "") + (e.directory && m.can("select_folder") ? "webkitdirectory directory" : "") + (c ? ' accept="' + c.join(",") + '"' : "") + " />", l = n.get(m.uid), t.extend(l.style, {
                        position: "absolute",
                        top: 0,
                        left: 0,
                        width: "100%",
                        height: "100%"
                    }), d = n.get(e.browse_button), m.can("summon_file_dialog") && ("static" === n.getStyle(d, "position") && (d.style.position = "relative"), p = parseInt(n.getStyle(d, "z-index"), 10) || 1, d.style.zIndex = p, u.style.zIndex = p - 1, i.addEvent(d, "click", function (e) {
                        var t = n.get(m.uid);
                        t && !t.disabled && t.click(), e.preventDefault()
                    }, h.uid)), f = m.can("summon_file_dialog") ? d : u, i.addEvent(f, "mouseover", function () {
                        h.trigger("mouseenter")
                    }, h.uid), i.addEvent(f, "mouseout", function () {
                        h.trigger("mouseleave")
                    }, h.uid), i.addEvent(f, "mousedown", function () {
                        h.trigger("mousedown")
                    }, h.uid), i.addEvent(n.get(e.container), "mouseup", function () {
                        h.trigger("mouseup")
                    }, h.uid), l.onchange = function g() {
                        if (a = [], e.directory ? t.each(this.files, function (e) {
                                "." !== e.name && a.push(e)
                            }) : a = [].slice.call(this.files), "IE" !== o.browser)this.value = ""; else {
                            var n = this.cloneNode(!0);
                            this.parentNode.replaceChild(n, this), n.onchange = g
                        }
                        h.trigger("change")
                    }, h.trigger({type: "ready", async: !0}), u = null
                }, getFiles: function () {
                    return a
                }, disable: function (e) {
                    var t, i = this.getRuntime();
                    (t = n.get(i.uid)) && (t.disabled = !!e)
                }, destroy: function () {
                    var t = this.getRuntime(), r = t.getShim(), o = t.getShimContainer();
                    i.removeAllEvents(o, this.uid), i.removeAllEvents(e && n.get(e.container), this.uid), i.removeAllEvents(e && n.get(e.browse_button), this.uid), o && (o.innerHTML = ""), r.removeInstance(this.uid), a = e = o = r = null
                }
            })
        }

        return e.FileInput = a
    }), i("moxie/runtime/html5/file/FileDrop", ["moxie/runtime/html5/Runtime", "moxie/core/utils/Basic", "moxie/core/utils/Dom", "moxie/core/utils/Events", "moxie/core/utils/Mime"], function (e, t, n, i, r) {
        function o() {
            function e(e) {
                for (var n = [], i = 0; i < e.length; i++)[].push.apply(n, e[i].extensions.split(/\s*,\s*/));
                return -1 === t.inArray("*", n) ? n : []
            }

            function o(e) {
                var n = r.getFileExtension(e.name);
                return !n || !p.length || -1 !== t.inArray(n, p)
            }

            function a(e, n) {
                var i = [];
                t.each(e, function (e) {
                    var t = e.webkitGetAsEntry();
                    if (t)if (t.isFile) {
                        var n = e.getAsFile();
                        o(n) && d.push(n)
                    } else i.push(t)
                }), i.length ? s(i, n) : n()
            }

            function s(e, n) {
                var i = [];
                t.each(e, function (e) {
                    i.push(function (t) {
                        l(e, t)
                    })
                }), t.inSeries(i, function () {
                    n()
                })
            }

            function l(e, t) {
                e.isFile ? e.file(function (e) {
                    o(e) && d.push(e), t()
                }, function () {
                    t()
                }) : e.isDirectory ? u(e, t) : t()
            }

            function u(e, t) {
                function n(e) {
                    r.readEntries(function (t) {
                        t.length ? ([].push.apply(i, t), n(e)) : e()
                    }, e)
                }

                var i = [], r = e.createReader();
                n(function () {
                    s(i, t)
                })
            }

            var c, d = [], p = [];
            t.extend(this, {
                init: function (n) {
                    var r, s = this;
                    c = n, p = e(c.accept), r = c.container, i.addEvent(r, "dragover", function (e) {
                        e.preventDefault(), e.stopPropagation(), e.dataTransfer.dropEffect = "copy"
                    }, s.uid), i.addEvent(r, "drop", function (e) {
                        e.preventDefault(), e.stopPropagation(), d = [], e.dataTransfer.items && e.dataTransfer.items[0].webkitGetAsEntry ? a(e.dataTransfer.items, function () {
                            s.trigger("drop")
                        }) : (t.each(e.dataTransfer.files, function (e) {
                            o(e) && d.push(e)
                        }), s.trigger("drop"))
                    }, s.uid), i.addEvent(r, "dragenter", function (e) {
                        e.preventDefault(), e.stopPropagation(), s.trigger("dragenter")
                    }, s.uid), i.addEvent(r, "dragleave", function (e) {
                        e.preventDefault(), e.stopPropagation(), s.trigger("dragleave")
                    }, s.uid)
                }, getFiles: function () {
                    return d
                }, destroy: function () {
                    i.removeAllEvents(c && n.get(c.container), this.uid), d = p = c = null
                }
            })
        }

        return e.FileDrop = o
    }), i("moxie/runtime/html5/file/FileReader", ["moxie/runtime/html5/Runtime", "moxie/core/utils/Encode", "moxie/core/utils/Basic"], function (e, t, n) {
        function i() {
            function e(e) {
                return t.atob(e.substring(e.indexOf("base64,") + 7))
            }

            var i, r = !1;
            n.extend(this, {
                read: function (e, t) {
                    var o = this;
                    i = new window.FileReader, i.addEventListener("progress", function (e) {
                        o.trigger(e)
                    }), i.addEventListener("load", function (e) {
                        o.trigger(e)
                    }), i.addEventListener("error", function (e) {
                        o.trigger(e, i.error)
                    }), i.addEventListener("loadend", function () {
                        i = null
                    }), "function" === n.typeOf(i[e]) ? (r = !1, i[e](t.getSource())) : "readAsBinaryString" === e && (r = !0, i.readAsDataURL(t.getSource()))
                }, getResult: function () {
                    return i && i.result ? r ? e(i.result) : i.result : null
                }, abort: function () {
                    i && i.abort()
                }, destroy: function () {
                    i = null
                }
            })
        }

        return e.FileReader = i
    }), i("moxie/runtime/html5/xhr/XMLHttpRequest", ["moxie/runtime/html5/Runtime", "moxie/core/utils/Basic", "moxie/core/utils/Mime", "moxie/core/utils/Url", "moxie/file/File", "moxie/file/Blob", "moxie/xhr/FormData", "moxie/core/Exceptions", "moxie/core/utils/Env"], function (e, t, n, i, r, o, a, s, l) {
        function u() {
            function e(e, t) {
                var n, i, r = this;
                n = t.getBlob().getSource(), i = new window.FileReader, i.onload = function () {
                    t.append(t.getBlobName(), new o(null, {type: n.type, data: i.result})), h.send.call(r, e, t)
                }, i.readAsBinaryString(n)
            }

            function u() {
                return !window.XMLHttpRequest || "IE" === l.browser && l.version < 8 ? function () {
                    for (var e = ["Msxml2.XMLHTTP.6.0", "Microsoft.XMLHTTP"], t = 0; t < e.length; t++)try {
                        return new ActiveXObject(e[t])
                    } catch (n) {
                    }
                }() : new window.XMLHttpRequest
            }

            function c(e) {
                var t = e.responseXML, n = e.responseText;
                return "IE" === l.browser && n && t && !t.documentElement && /[^\/]+\/[^\+]+\+xml/.test(e.getResponseHeader("Content-Type")) && (t = new window.ActiveXObject("Microsoft.XMLDOM"), t.async = !1, t.validateOnParse = !1, t.loadXML(n)), t && ("IE" === l.browser && 0 !== t.parseError || !t.documentElement || "parsererror" === t.documentElement.tagName) ? null : t
            }

            function d(e) {
                var t = "----moxieboundary" + (new Date).getTime(), n = "--", i = "\r\n", r = "", a = this.getRuntime();
                if (!a.can("send_binary_string"))throw new s.RuntimeError(s.RuntimeError.NOT_SUPPORTED_ERR);
                return p.setRequestHeader("Content-Type", "multipart/form-data; boundary=" + t), e.each(function (e, a) {
                    r += e instanceof o ? n + t + i + 'Content-Disposition: form-data; name="' + a + '"; filename="' + unescape(encodeURIComponent(e.name || "blob")) + '"' + i + "Content-Type: " + (e.type || "application/octet-stream") + i + i + e.getSource() + i : n + t + i + 'Content-Disposition: form-data; name="' + a + '"' + i + i + unescape(encodeURIComponent(e)) + i
                }), r += n + t + n + i
            }

            var p, f, h = this;
            t.extend(this, {
                send: function (n, r) {
                    var s = this, c = "Mozilla" === l.browser && l.version >= 4 && l.version < 7, h = "Android Browser" === l.browser, m = !1;
                    if (f = n.url.replace(/^.+?\/([\w\-\.]+)$/, "$1").toLowerCase(), p = u(), p.open(n.method, n.url, n.async, n.user, n.password), r instanceof o)r.isDetached() && (m = !0), r = r.getSource(); else if (r instanceof a) {
                        if (r.hasBlob())if (r.getBlob().isDetached())r = d.call(s, r), m = !0; else if ((c || h) && "blob" === t.typeOf(r.getBlob().getSource()) && window.FileReader)return void e.call(s, n, r);
                        if (r instanceof a) {
                            var g = new window.FormData;
                            r.each(function (e, t) {
                                e instanceof o ? g.append(t, e.getSource()) : g.append(t, e)
                            }), r = g
                        }
                    }
                    p.upload ? (n.withCredentials && (p.withCredentials = !0), p.addEventListener("load", function (e) {
                        s.trigger(e)
                    }), p.addEventListener("error", function (e) {
                        s.trigger(e)
                    }), p.addEventListener("progress", function (e) {
                        s.trigger(e)
                    }), p.upload.addEventListener("progress", function (e) {
                        s.trigger({type: "UploadProgress", loaded: e.loaded, total: e.total})
                    })) : p.onreadystatechange = function () {
                        switch (p.readyState) {
                            case 1:
                                break;
                            case 2:
                                break;
                            case 3:
                                var e, t;
                                try {
                                    i.hasSameOrigin(n.url) && (e = p.getResponseHeader("Content-Length") || 0), p.responseText && (t = p.responseText.length)
                                } catch (r) {
                                    e = t = 0
                                }
                                s.trigger({type: "progress", lengthComputable: !!e, total: parseInt(e, 10), loaded: t});
                                break;
                            case 4:
                                p.onreadystatechange = function () {
                                }, s.trigger(0 === p.status ? "error" : "load")
                        }
                    }, t.isEmptyObj(n.headers) || t.each(n.headers, function (e, t) {
                        p.setRequestHeader(t, e)
                    }), "" !== n.responseType && "responseType"in p && (p.responseType = "json" !== n.responseType || l.can("return_response_type", "json") ? n.responseType : "text"), m ? p.sendAsBinary ? p.sendAsBinary(r) : !function () {
                        for (var e = new Uint8Array(r.length), t = 0; t < r.length; t++)e[t] = 255 & r.charCodeAt(t);
                        p.send(e.buffer)
                    }() : p.send(r), s.trigger("loadstart")
                }, getStatus: function () {
                    try {
                        if (p)return p.status
                    } catch (e) {
                    }
                    return 0
                }, getResponse: function (e) {
                    var t = this.getRuntime();
                    try {
                        switch (e) {
                            case"blob":
                                var i = new r(t.uid, p.response), o = p.getResponseHeader("Content-Disposition");
                                if (o) {
                                    var a = o.match(/filename=([\'\"'])([^\1]+)\1/);
                                    a && (f = a[2])
                                }
                                return i.name = f, i.type || (i.type = n.getFileMime(f)), i;
                            case"json":
                                return l.can("return_response_type", "json") ? p.response : 200 === p.status && window.JSON ? JSON.parse(p.responseText) : null;
                            case"document":
                                return c(p);
                            default:
                                return "" !== p.responseText ? p.responseText : null
                        }
                    } catch (s) {
                        return null
                    }
                }, getAllResponseHeaders: function () {
                    try {
                        return p.getAllResponseHeaders()
                    } catch (e) {
                    }
                    return ""
                }, abort: function () {
                    p && p.abort()
                }, destroy: function () {
                    h = f = null
                }
            })
        }

        return e.XMLHttpRequest = u
    }), i("moxie/runtime/html5/utils/BinaryReader", [], function () {
        return function () {
            function e(e, t) {
                var n, i = o ? 0 : -8 * (t - 1), a = 0;
                for (n = 0; t > n; n++)a |= r.charCodeAt(e + n) << Math.abs(i + 8 * n);
                return a
            }

            function n(e, t, n) {
                n = 3 === arguments.length ? n : r.length - t - 1, r = r.substr(0, t) + e + r.substr(n + t)
            }

            function i(e, t, i) {
                var r, a = "", s = o ? 0 : -8 * (i - 1);
                for (r = 0; i > r; r++)a += String.fromCharCode(t >> Math.abs(s + 8 * r) & 255);
                n(a, e, i)
            }

            var r, o = !1;
            return {
                II: function (e) {
                    return e === t ? o : void(o = e)
                }, init: function (e) {
                    o = !1, r = e
                }, SEGMENT: function (e, t, i) {
                    switch (arguments.length) {
                        case 1:
                            return r.substr(e, r.length - e - 1);
                        case 2:
                            return r.substr(e, t);
                        case 3:
                            n(i, e, t);
                            break;
                        default:
                            return r
                    }
                }, BYTE: function (t) {
                    return e(t, 1)
                }, SHORT: function (t) {
                    return e(t, 2)
                }, LONG: function (n, r) {
                    return r === t ? e(n, 4) : void i(n, r, 4)
                }, SLONG: function (t) {
                    var n = e(t, 4);
                    return n > 2147483647 ? n - 4294967296 : n
                }, STRING: function (t, n) {
                    var i = "";
                    for (n += t; n > t; t++)i += String.fromCharCode(e(t, 1));
                    return i
                }
            }
        }
    }), i("moxie/runtime/html5/image/JPEGHeaders", ["moxie/runtime/html5/utils/BinaryReader"], function (e) {
        return function t(n) {
            var i, r, o, a = [], s = 0;
            if (i = new e, i.init(n), 65496 === i.SHORT(0)) {
                for (r = 2; r <= n.length;)if (o = i.SHORT(r), o >= 65488 && 65495 >= o)r += 2; else {
                    if (65498 === o || 65497 === o)break;
                    s = i.SHORT(r + 2) + 2, o >= 65505 && 65519 >= o && a.push({
                        hex: o,
                        name: "APP" + (15 & o),
                        start: r,
                        length: s,
                        segment: i.SEGMENT(r, s)
                    }), r += s
                }
                return i.init(null), {
                    headers: a, restore: function (e) {
                        var t, n;
                        for (i.init(e), r = 65504 == i.SHORT(2) ? 4 + i.SHORT(4) : 2, n = 0, t = a.length; t > n; n++)i.SEGMENT(r, 0, a[n].segment), r += a[n].length;
                        return e = i.SEGMENT(), i.init(null), e
                    }, strip: function (e) {
                        var n, r, o;
                        for (r = new t(e), n = r.headers, r.purge(), i.init(e), o = n.length; o--;)i.SEGMENT(n[o].start, n[o].length, "");
                        return e = i.SEGMENT(), i.init(null), e
                    }, get: function (e) {
                        for (var t = [], n = 0, i = a.length; i > n; n++)a[n].name === e.toUpperCase() && t.push(a[n].segment);
                        return t
                    }, set: function (e, t) {
                        var n, i, r, o = [];
                        for ("string" == typeof t ? o.push(t) : o = t, n = i = 0, r = a.length; r > n && (a[n].name === e.toUpperCase() && (a[n].segment = o[i], a[n].length = o[i].length, i++), !(i >= o.length)); n++);
                    }, purge: function () {
                        a = [], i.init(null), i = null
                    }
                }
            }
        }
    }), i("moxie/runtime/html5/image/ExifParser", ["moxie/core/utils/Basic", "moxie/runtime/html5/utils/BinaryReader"], function (e, n) {
        return function () {
            function i(e, n) {
                var i, r, o, s, l, d, p, f, h = a.SHORT(e), m = [], g = {};
                for (i = 0; h > i; i++)if (p = d = e + 12 * i + 2, o = n[a.SHORT(p)], o !== t) {
                    switch (s = a.SHORT(p += 2), l = a.LONG(p += 2), p += 4, m = [], s) {
                        case 1:
                        case 7:
                            for (l > 4 && (p = a.LONG(p) + c.tiffHeader), r = 0; l > r; r++)m[r] = a.BYTE(p + r);
                            break;
                        case 2:
                            l > 4 && (p = a.LONG(p) + c.tiffHeader), g[o] = a.STRING(p, l - 1);
                            continue;
                        case 3:
                            for (l > 2 && (p = a.LONG(p) + c.tiffHeader), r = 0; l > r; r++)m[r] = a.SHORT(p + 2 * r);
                            break;
                        case 4:
                            for (l > 1 && (p = a.LONG(p) + c.tiffHeader), r = 0; l > r; r++)m[r] = a.LONG(p + 4 * r);
                            break;
                        case 5:
                            for (p = a.LONG(p) + c.tiffHeader, r = 0; l > r; r++)m[r] = a.LONG(p + 4 * r) / a.LONG(p + 4 * r + 4);
                            break;
                        case 9:
                            for (p = a.LONG(p) + c.tiffHeader, r = 0; l > r; r++)m[r] = a.SLONG(p + 4 * r);
                            break;
                        case 10:
                            for (p = a.LONG(p) + c.tiffHeader, r = 0; l > r; r++)m[r] = a.SLONG(p + 4 * r) / a.SLONG(p + 4 * r + 4);
                            break;
                        default:
                            continue
                    }
                    f = 1 == l ? m[0] : m, g[o] = u.hasOwnProperty(o) && "object" != typeof f ? u[o][f] : f
                }
                return g
            }

            function r() {
                var e = c.tiffHeader;
                return a.II(18761 == a.SHORT(e)), 42 !== a.SHORT(e += 2) ? !1 : (c.IFD0 = c.tiffHeader + a.LONG(e += 2), l = i(c.IFD0, s.tiff), "ExifIFDPointer"in l && (c.exifIFD = c.tiffHeader + l.ExifIFDPointer, delete l.ExifIFDPointer), "GPSInfoIFDPointer"in l && (c.gpsIFD = c.tiffHeader + l.GPSInfoIFDPointer, delete l.GPSInfoIFDPointer), !0)
            }

            function o(e, t, n) {
                var i, r, o, l = 0;
                if ("string" == typeof t) {
                    var u = s[e.toLowerCase()];
                    for (var d in u)if (u[d] === t) {
                        t = d;
                        break
                    }
                }
                i = c[e.toLowerCase() + "IFD"], r = a.SHORT(i);
                for (var p = 0; r > p; p++)if (o = i + 12 * p + 2, a.SHORT(o) == t) {
                    l = o + 8;
                    break
                }
                return l ? (a.LONG(l, n), !0) : !1
            }

            var a, s, l, u, c = {};
            return a = new n, s = {
                tiff: {
                    274: "Orientation",
                    270: "ImageDescription",
                    271: "Make",
                    272: "Model",
                    305: "Software",
                    34665: "ExifIFDPointer",
                    34853: "GPSInfoIFDPointer"
                },
                exif: {
                    36864: "ExifVersion",
                    40961: "ColorSpace",
                    40962: "PixelXDimension",
                    40963: "PixelYDimension",
                    36867: "DateTimeOriginal",
                    33434: "ExposureTime",
                    33437: "FNumber",
                    34855: "ISOSpeedRatings",
                    37377: "ShutterSpeedValue",
                    37378: "ApertureValue",
                    37383: "MeteringMode",
                    37384: "LightSource",
                    37385: "Flash",
                    37386: "FocalLength",
                    41986: "ExposureMode",
                    41987: "WhiteBalance",
                    41990: "SceneCaptureType",
                    41988: "DigitalZoomRatio",
                    41992: "Contrast",
                    41993: "Saturation",
                    41994: "Sharpness"
                },
                gps: {0: "GPSVersionID", 1: "GPSLatitudeRef", 2: "GPSLatitude", 3: "GPSLongitudeRef", 4: "GPSLongitude"}
            }, u = {
                ColorSpace: {1: "sRGB", 0: "Uncalibrated"},
                MeteringMode: {
                    0: "Unknown",
                    1: "Average",
                    2: "CenterWeightedAverage",
                    3: "Spot",
                    4: "MultiSpot",
                    5: "Pattern",
                    6: "Partial",
                    255: "Other"
                },
                LightSource: {
                    1: "Daylight",
                    2: "Fliorescent",
                    3: "Tungsten",
                    4: "Flash",
                    9: "Fine weather",
                    10: "Cloudy weather",
                    11: "Shade",
                    12: "Daylight fluorescent (D 5700 - 7100K)",
                    13: "Day white fluorescent (N 4600 -5400K)",
                    14: "Cool white fluorescent (W 3900 - 4500K)",
                    15: "White fluorescent (WW 3200 - 3700K)",
                    17: "Standard light A",
                    18: "Standard light B",
                    19: "Standard light C",
                    20: "D55",
                    21: "D65",
                    22: "D75",
                    23: "D50",
                    24: "ISO studio tungsten",
                    255: "Other"
                },
                Flash: {
                    0: "Flash did not fire.",
                    1: "Flash fired.",
                    5: "Strobe return light not detected.",
                    7: "Strobe return light detected.",
                    9: "Flash fired, compulsory flash mode",
                    13: "Flash fired, compulsory flash mode, return light not detected",
                    15: "Flash fired, compulsory flash mode, return light detected",
                    16: "Flash did not fire, compulsory flash mode",
                    24: "Flash did not fire, auto mode",
                    25: "Flash fired, auto mode",
                    29: "Flash fired, auto mode, return light not detected",
                    31: "Flash fired, auto mode, return light detected",
                    32: "No flash function",
                    65: "Flash fired, red-eye reduction mode",
                    69: "Flash fired, red-eye reduction mode, return light not detected",
                    71: "Flash fired, red-eye reduction mode, return light detected",
                    73: "Flash fired, compulsory flash mode, red-eye reduction mode",
                    77: "Flash fired, compulsory flash mode, red-eye reduction mode, return light not detected",
                    79: "Flash fired, compulsory flash mode, red-eye reduction mode, return light detected",
                    89: "Flash fired, auto mode, red-eye reduction mode",
                    93: "Flash fired, auto mode, return light not detected, red-eye reduction mode",
                    95: "Flash fired, auto mode, return light detected, red-eye reduction mode"
                },
                ExposureMode: {0: "Auto exposure", 1: "Manual exposure", 2: "Auto bracket"},
                WhiteBalance: {0: "Auto white balance", 1: "Manual white balance"},
                SceneCaptureType: {0: "Standard", 1: "Landscape", 2: "Portrait", 3: "Night scene"},
                Contrast: {0: "Normal", 1: "Soft", 2: "Hard"},
                Saturation: {0: "Normal", 1: "Low saturation", 2: "High saturation"},
                Sharpness: {0: "Normal", 1: "Soft", 2: "Hard"},
                GPSLatitudeRef: {N: "North latitude", S: "South latitude"},
                GPSLongitudeRef: {E: "East longitude", W: "West longitude"}
            }, {
                init: function (e) {
                    return c = {tiffHeader: 10}, e !== t && e.length ? (a.init(e), 65505 === a.SHORT(0) && "EXIF\x00" === a.STRING(4, 5).toUpperCase() ? r() : !1) : !1
                }, TIFF: function () {
                    return l
                }, EXIF: function () {
                    var t;
                    if (t = i(c.exifIFD, s.exif), t.ExifVersion && "array" === e.typeOf(t.ExifVersion)) {
                        for (var n = 0, r = ""; n < t.ExifVersion.length; n++)r += String.fromCharCode(t.ExifVersion[n]);
                        t.ExifVersion = r
                    }
                    return t
                }, GPS: function () {
                    var t;
                    return t = i(c.gpsIFD, s.gps), t.GPSVersionID && "array" === e.typeOf(t.GPSVersionID) && (t.GPSVersionID = t.GPSVersionID.join(".")), t
                }, setExif: function (e, t) {
                    return "PixelXDimension" !== e && "PixelYDimension" !== e ? !1 : o("exif", e, t)
                }, getBinary: function () {
                    return a.SEGMENT()
                }, purge: function () {
                    a.init(null), a = l = null, c = {}
                }
            }
        }
    }), i("moxie/runtime/html5/image/JPEG", ["moxie/core/utils/Basic", "moxie/core/Exceptions", "moxie/runtime/html5/image/JPEGHeaders", "moxie/runtime/html5/utils/BinaryReader", "moxie/runtime/html5/image/ExifParser"], function (e, t, n, i, r) {
        function o(o) {
            function a() {
                for (var e, t, n = 0; n <= l.length;) {
                    if (e = u.SHORT(n += 2), e >= 65472 && 65475 >= e)return n += 5, {
                        height: u.SHORT(n),
                        width: u.SHORT(n += 2)
                    };
                    t = u.SHORT(n += 2), n += t - 2
                }
                return null
            }

            function s() {
                d && c && u && (d.purge(), c.purge(), u.init(null), l = p = c = d = u = null)
            }

            var l, u, c, d, p, f;
            if (l = o, u = new i, u.init(l), 65496 !== u.SHORT(0))throw new t.ImageError(t.ImageError.WRONG_FORMAT);
            c = new n(o), d = new r, f = !!d.init(c.get("app1")[0]), p = a.call(this), e.extend(this, {
                type: "image/jpeg",
                size: l.length,
                width: p && p.width || 0,
                height: p && p.height || 0,
                setExif: function (t, n) {
                    return f ? ("object" === e.typeOf(t) ? e.each(t, function (e, t) {
                        d.setExif(t, e)
                    }) : d.setExif(t, n), void c.set("app1", d.getBinary())) : !1
                },
                writeHeaders: function () {
                    return arguments.length ? c.restore(arguments[0]) : l = c.restore(l)
                },
                stripHeaders: function (e) {
                    return c.strip(e)
                },
                purge: function () {
                    s.call(this)
                }
            }), f && (this.meta = {tiff: d.TIFF(), exif: d.EXIF(), gps: d.GPS()})
        }

        return o
    }), i("moxie/runtime/html5/image/PNG", ["moxie/core/Exceptions", "moxie/core/utils/Basic", "moxie/runtime/html5/utils/BinaryReader"], function (e, t, n) {
        function i(i) {
            function r() {
                var e, t;
                return e = a.call(this, 8), "IHDR" == e.type ? (t = e.start, {
                    width: l.LONG(t),
                    height: l.LONG(t += 4)
                }) : null
            }

            function o() {
                l && (l.init(null), s = d = u = c = l = null)
            }

            function a(e) {
                var t, n, i, r;
                return t = l.LONG(e), n = l.STRING(e += 4, 4), i = e += 4, r = l.LONG(e + t), {
                    length: t,
                    type: n,
                    start: i,
                    CRC: r
                }
            }

            var s, l, u, c, d;
            s = i, l = new n, l.init(s), function () {
                var t = 0, n = 0, i = [35152, 20039, 3338, 6666];
                for (n = 0; n < i.length; n++, t += 2)if (i[n] != l.SHORT(t))throw new e.ImageError(e.ImageError.WRONG_FORMAT)
            }(), d = r.call(this), t.extend(this, {
                type: "image/png",
                size: s.length,
                width: d.width,
                height: d.height,
                purge: function () {
                    o.call(this)
                }
            }), o.call(this)
        }

        return i
    }), i("moxie/runtime/html5/image/ImageInfo", ["moxie/core/utils/Basic", "moxie/core/Exceptions", "moxie/runtime/html5/image/JPEG", "moxie/runtime/html5/image/PNG"], function (e, t, n, i) {
        return function (r) {
            var o, a = [n, i];
            o = function () {
                for (var e = 0; e < a.length; e++)try {
                    return new a[e](r)
                } catch (n) {
                }
                throw new t.ImageError(t.ImageError.WRONG_FORMAT)
            }(), e.extend(this, {
                type: "", size: 0, width: 0, height: 0, setExif: function () {
                }, writeHeaders: function (e) {
                    return e
                }, stripHeaders: function (e) {
                    return e
                }, purge: function () {
                }
            }), e.extend(this, o), this.purge = function () {
                o.purge(), o = null
            }
        }
    }), i("moxie/runtime/html5/image/MegaPixel", [], function () {
        function e(e, i, r) {
            var o = e.naturalWidth, a = e.naturalHeight, s = r.width, l = r.height, u = r.x || 0, c = r.y || 0, d = i.getContext("2d");
            t(e) && (o /= 2, a /= 2);
            var p = 1024, f = document.createElement("canvas");
            f.width = f.height = p;
            for (var h = f.getContext("2d"), m = n(e, o, a), g = 0; a > g;) {
                for (var v = g + p > a ? a - g : p, y = 0; o > y;) {
                    var b = y + p > o ? o - y : p;
                    h.clearRect(0, 0, p, p), h.drawImage(e, -y, -g);
                    var x = y * s / o + u << 0, w = Math.ceil(b * s / o), E = g * l / a / m + c << 0, T = Math.ceil(v * l / a / m);
                    d.drawImage(f, 0, 0, b, v, x, E, w, T), y += p
                }
                g += p
            }
            f = h = null
        }

        function t(e) {
            var t = e.naturalWidth, n = e.naturalHeight;
            if (t * n > 1048576) {
                var i = document.createElement("canvas");
                i.width = i.height = 1;
                var r = i.getContext("2d");
                return r.drawImage(e, -t + 1, 0), 0 === r.getImageData(0, 0, 1, 1).data[3]
            }
            return !1
        }

        function n(e, t, n) {
            var i = document.createElement("canvas");
            i.width = 1, i.height = n;
            var r = i.getContext("2d");
            r.drawImage(e, 0, 0);
            for (var o = r.getImageData(0, 0, 1, n).data, a = 0, s = n, l = n; l > a;) {
                var u = o[4 * (l - 1) + 3];
                0 === u ? s = l : a = l, l = s + a >> 1
            }
            i = null;
            var c = l / n;
            return 0 === c ? 1 : c
        }

        return {isSubsampled: t, renderTo: e}
    }), i("moxie/runtime/html5/image/Image", ["moxie/runtime/html5/Runtime", "moxie/core/utils/Basic", "moxie/core/Exceptions", "moxie/core/utils/Encode", "moxie/file/File", "moxie/runtime/html5/image/ImageInfo", "moxie/runtime/html5/image/MegaPixel", "moxie/core/utils/Mime", "moxie/core/utils/Env"], function (e, t, n, i, r, o, a, s, l) {
        function u() {
            function e() {
                if (!b && !v)throw new n.ImageError(n.DOMException.INVALID_STATE_ERR);
                return b || v
            }

            function u(e) {
                return i.atob(e.substring(e.indexOf("base64,") + 7))
            }

            function c(e, t) {
                return "data:" + (t || "") + ";base64," + i.btoa(e)
            }

            function d(e) {
                var t = this;
                v = new Image, v.onerror = function () {
                    g.call(this), t.trigger("error", new n.ImageError(n.ImageError.WRONG_FORMAT))
                }, v.onload = function () {
                    t.trigger("load")
                }, v.src = /^data:[^;]*;base64,/.test(e) ? e : c(e, w.type)
            }

            function p(e, t) {
                var i, r = this;
                return window.FileReader ? (i = new FileReader, i.onload = function () {
                    t(this.result)
                }, i.onerror = function () {
                    r.trigger("error", new n.FileException(n.FileException.NOT_READABLE_ERR))
                }, i.readAsDataURL(e), void 0) : t(e.getAsDataURL())
            }

            function f(n, i, r, o) {
                var a, s, l, u, c, d, p = this, f = 0, g = 0;
                if (S = o, d = this.meta && this.meta.tiff && this.meta.tiff.Orientation || 1, -1 !== t.inArray(d, [5, 6, 7, 8])) {
                    var v = n;
                    n = i, i = v
                }
                return l = e(), s = r ? Math.max : Math.min, a = s(n / l.width, i / l.height), a > 1 && (!r || o) ? void this.trigger("Resize") : (b || (b = document.createElement("canvas")), u = Math.round(l.width * a), c = Math.round(l.height * a), r ? (b.width = n, b.height = i, u > n && (f = Math.round((u - n) / 2)), c > i && (g = Math.round((c - i) / 2))) : (b.width = u, b.height = c), S || m(b.width, b.height, d), h.call(this, l, b, -f, -g, u, c), this.width = b.width, this.height = b.height, T = !0, void p.trigger("Resize"))
            }

            function h(e, t, n, i, r, o) {
                if ("iOS" === l.OS)a.renderTo(e, t, {width: r, height: o, x: n, y: i}); else {
                    var s = t.getContext("2d");
                    s.drawImage(e, n, i, r, o)
                }
            }

            function m(e, t, n) {
                switch (n) {
                    case 5:
                    case 6:
                    case 7:
                    case 8:
                        b.width = t, b.height = e;
                        break;
                    default:
                        b.width = e, b.height = t
                }
                var i = b.getContext("2d");
                switch (n) {
                    case 2:
                        i.translate(e, 0), i.scale(-1, 1);
                        break;
                    case 3:
                        i.translate(e, t), i.rotate(Math.PI);
                        break;
                    case 4:
                        i.translate(0, t), i.scale(1, -1);
                        break;
                    case 5:
                        i.rotate(.5 * Math.PI), i.scale(1, -1);
                        break;
                    case 6:
                        i.rotate(.5 * Math.PI), i.translate(0, -t);
                        break;
                    case 7:
                        i.rotate(.5 * Math.PI), i.translate(e, -t), i.scale(-1, 1);
                        break;
                    case 8:
                        i.rotate(-.5 * Math.PI), i.translate(-e, 0)
                }
            }

            function g() {
                y && (y.purge(), y = null), x = v = b = w = null, T = !1
            }

            var v, y, b, x, w, E = this, T = !1, S = !0;
            t.extend(this, {
                loadFromBlob: function (e) {
                    var t = this, i = t.getRuntime(), r = arguments.length > 1 ? arguments[1] : !0;
                    if (!i.can("access_binary"))throw new n.RuntimeError(n.RuntimeError.NOT_SUPPORTED_ERR);
                    return w = e, e.isDetached() ? (x = e.getSource(), void d.call(this, x)) : void p.call(this, e.getSource(), function (e) {
                        r && (x = u(e)), d.call(t, e)
                    })
                }, loadFromImage: function (e, t) {
                    this.meta = e.meta, w = new r(null, {
                        name: e.name,
                        size: e.size,
                        type: e.type
                    }), d.call(this, t ? x = e.getAsBinaryString() : e.getAsDataURL())
                }, getInfo: function () {
                    var t, n = this.getRuntime();
                    return !y && x && n.can("access_image_binary") && (y = new o(x)), t = {
                        width: e().width || 0,
                        height: e().height || 0,
                        type: w.type || s.getFileMime(w.name),
                        size: x && x.length || w.size || 0,
                        name: w.name || "",
                        meta: y && y.meta || this.meta || {}
                    }
                }, downsize: function () {
                    f.apply(this, arguments)
                }, getAsCanvas: function () {
                    return b && (b.id = this.uid + "_canvas"), b
                }, getAsBlob: function (e, t) {
                    return e !== this.type && f.call(this, this.width, this.height, !1), new r(null, {
                        name: w.name || "",
                        type: e,
                        data: E.getAsBinaryString.call(this, e, t)
                    })
                }, getAsDataURL: function (e) {
                    var t = arguments[1] || 90;
                    if (!T)return v.src;
                    if ("image/jpeg" !== e)return b.toDataURL("image/png");
                    try {
                        return b.toDataURL("image/jpeg", t / 100)
                    } catch (n) {
                        return b.toDataURL("image/jpeg")
                    }
                }, getAsBinaryString: function (e, t) {
                    if (!T)return x || (x = u(E.getAsDataURL(e, t))), x;
                    if ("image/jpeg" !== e)x = u(E.getAsDataURL(e, t)); else {
                        var n;
                        t || (t = 90);
                        try {
                            n = b.toDataURL("image/jpeg", t / 100)
                        } catch (i) {
                            n = b.toDataURL("image/jpeg")
                        }
                        x = u(n), y && (x = y.stripHeaders(x), S && (y.meta && y.meta.exif && y.setExif({
                            PixelXDimension: this.width,
                            PixelYDimension: this.height
                        }), x = y.writeHeaders(x)), y.purge(), y = null)
                    }
                    return T = !1, x
                }, destroy: function () {
                    E = null, g.call(this), this.getRuntime().getShim().removeInstance(this.uid)
                }
            })
        }

        return e.Image = u
    }), i("moxie/runtime/flash/Runtime", ["moxie/core/utils/Basic", "moxie/core/utils/Env", "moxie/core/utils/Dom", "moxie/core/Exceptions", "moxie/runtime/Runtime"], function (e, t, n, i, r) {
        function o() {
            var e;
            try {
                e = navigator.plugins["Shockwave Flash"], e = e.description
            } catch (t) {
                try {
                    e = new ActiveXObject("ShockwaveFlash.ShockwaveFlash").GetVariable("$version")
                } catch (n) {
                    e = "0.0"
                }
            }
            return e = e.match(/\d+/g), parseFloat(e[0] + "." + e[1])
        }

        function a(a) {
            var u, c = this;
            a = e.extend({swf_url: t.swf_url}, a), r.call(this, a, s, {
                access_binary: function (e) {
                    return e && "browser" === c.mode
                },
                access_image_binary: function (e) {
                    return e && "browser" === c.mode
                },
                display_media: r.capTrue,
                do_cors: r.capTrue,
                drag_and_drop: !1,
                report_upload_progress: function () {
                    return "client" === c.mode
                },
                resize_image: r.capTrue,
                return_response_headers: !1,
                return_response_type: function (t) {
                    return "json" === t && window.JSON ? !0 : !e.arrayDiff(t, ["", "text", "document"]) || "browser" === c.mode
                },
                return_status_code: function (t) {
                    return "browser" === c.mode || !e.arrayDiff(t, [200, 404])
                },
                select_file: r.capTrue,
                select_multiple: r.capTrue,
                send_binary_string: function (e) {
                    return e && "browser" === c.mode
                },
                send_browser_cookies: function (e) {
                    return e && "browser" === c.mode
                },
                send_custom_headers: function (e) {
                    return e && "browser" === c.mode
                },
                send_multipart: r.capTrue,
                slice_blob: r.capTrue,
                stream_upload: function (e) {
                    return e && "browser" === c.mode
                },
                summon_file_dialog: !1,
                upload_filesize: function (t) {
                    return e.parseSizeStr(t) <= 2097152 || "client" === c.mode
                },
                use_http_method: function (t) {
                    return !e.arrayDiff(t, ["GET", "POST"])
                }
            }, {
                access_binary: function (e) {
                    return e ? "browser" : "client"
                }, access_image_binary: function (e) {
                    return e ? "browser" : "client"
                }, report_upload_progress: function (e) {
                    return e ? "browser" : "client"
                }, return_response_type: function (t) {
                    return e.arrayDiff(t, ["", "text", "json", "document"]) ? "browser" : ["client", "browser"]
                }, return_status_code: function (t) {
                    return e.arrayDiff(t, [200, 404]) ? "browser" : ["client", "browser"]
                }, send_binary_string: function (e) {
                    return e ? "browser" : "client"
                }, send_browser_cookies: function (e) {
                    return e ? "browser" : "client"
                }, send_custom_headers: function (e) {
                    return e ? "browser" : "client"
                }, stream_upload: function (e) {
                    return e ? "client" : "browser"
                }, upload_filesize: function (t) {
                    return e.parseSizeStr(t) >= 2097152 ? "client" : "browser"
                }
            }, "client"), o() < 10 && (this.mode = !1), e.extend(this, {
                getShim: function () {
                    return n.get(this.uid)
                }, shimExec: function (e, t) {
                    var n = [].slice.call(arguments, 2);
                    return c.getShim().exec(this.uid, e, t, n)
                }, init: function () {
                    var n, r, o;
                    o = this.getShimContainer(), e.extend(o.style, {
                        position: "absolute",
                        top: "-8px",
                        left: "-8px",
                        width: "9px",
                        height: "9px",
                        overflow: "hidden"
                    }), n = '<object id="' + this.uid + '" type="application/x-shockwave-flash" data="' + a.swf_url + '" ', "IE" === t.browser && (n += 'classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" '), n += 'width="100%" height="100%" style="outline:0"><param name="movie" value="' + a.swf_url + '" /><param name="flashvars" value="uid=' + escape(this.uid) + "&target=" + t.global_event_dispatcher + '" /><param name="wmode" value="transparent" /><param name="allowscriptaccess" value="always" /></object>', "IE" === t.browser ? (r = document.createElement("div"), o.appendChild(r), r.outerHTML = n, r = o = null) : o.innerHTML = n, u = setTimeout(function () {
                        c && !c.initialized && c.trigger("Error", new i.RuntimeError(i.RuntimeError.NOT_INIT_ERR))
                    }, 5e3)
                }, destroy: function (e) {
                    return function () {
                        e.call(c), clearTimeout(u), a = u = e = c = null
                    }
                }(this.destroy)
            }, l)
        }

        var s = "flash", l = {};
        return r.addConstructor(s, a), l
    }), i("moxie/runtime/flash/file/Blob", ["moxie/runtime/flash/Runtime", "moxie/file/Blob"], function (e, t) {
        var n = {
            slice: function (e, n, i, r) {
                var o = this.getRuntime();
                return 0 > n ? n = Math.max(e.size + n, 0) : n > 0 && (n = Math.min(n, e.size)), 0 > i ? i = Math.max(e.size + i, 0) : i > 0 && (i = Math.min(i, e.size)), e = o.shimExec.call(this, "Blob", "slice", n, i, r || ""), e && (e = new t(o.uid, e)), e
            }
        };
        return e.Blob = n
    }), i("moxie/runtime/flash/file/FileInput", ["moxie/runtime/flash/Runtime"], function (e) {
        var t = {
            init: function (e) {
                this.getRuntime().shimExec.call(this, "FileInput", "init", {
                    name: e.name,
                    accept: e.accept,
                    multiple: e.multiple
                }), this.trigger("ready")
            }
        };
        return e.FileInput = t
    }), i("moxie/runtime/flash/file/FileReader", ["moxie/runtime/flash/Runtime", "moxie/core/utils/Encode"], function (e, t) {
        function n(e, n) {
            switch (n) {
                case"readAsText":
                    return t.atob(e, "utf8");
                case"readAsBinaryString":
                    return t.atob(e);
                case"readAsDataURL":
                    return e
            }
            return null
        }

        var i = "", r = {
            read: function (e, t) {
                var r = this, o = r.getRuntime();
                return "readAsDataURL" === e && (i = "data:" + (t.type || "") + ";base64,"), r.bind("Progress", function (t, r) {
                    r && (i += n(r, e))
                }), o.shimExec.call(this, "FileReader", "readAsBase64", t.uid)
            }, getResult: function () {
                return i
            }, destroy: function () {
                i = null
            }
        };
        return e.FileReader = r
    }), i("moxie/runtime/flash/file/FileReaderSync", ["moxie/runtime/flash/Runtime", "moxie/core/utils/Encode"], function (e, t) {
        function n(e, n) {
            switch (n) {
                case"readAsText":
                    return t.atob(e, "utf8");
                case"readAsBinaryString":
                    return t.atob(e);
                case"readAsDataURL":
                    return e
            }
            return null
        }

        var i = {
            read: function (e, t) {
                var i, r = this.getRuntime();
                return (i = r.shimExec.call(this, "FileReaderSync", "readAsBase64", t.uid)) ? ("readAsDataURL" === e && (i = "data:" + (t.type || "") + ";base64," + i), n(i, e, t.type)) : null
            }
        };
        return e.FileReaderSync = i
    }), i("moxie/runtime/flash/xhr/XMLHttpRequest", ["moxie/runtime/flash/Runtime", "moxie/core/utils/Basic", "moxie/file/Blob", "moxie/file/File", "moxie/file/FileReaderSync", "moxie/xhr/FormData", "moxie/runtime/Transporter"], function (e, t, n, i, r, o, a) {
        var s = {
            send: function (e, i) {
                function r() {
                    e.transport = c.mode, c.shimExec.call(u, "XMLHttpRequest", "send", e, i)
                }

                function s(e, t) {
                    c.shimExec.call(u, "XMLHttpRequest", "appendBlob", e, t.uid), i = null, r()
                }

                function l(e, t) {
                    var n = new a;
                    n.bind("TransportingComplete", function () {
                        t(this.result)
                    }), n.transport(e.getSource(), e.type, {ruid: c.uid})
                }

                var u = this, c = u.getRuntime();
                if (t.isEmptyObj(e.headers) || t.each(e.headers, function (e, t) {
                        c.shimExec.call(u, "XMLHttpRequest", "setRequestHeader", t, e.toString())
                    }), i instanceof o) {
                    var d;
                    if (i.each(function (e, t) {
                            e instanceof n ? d = t : c.shimExec.call(u, "XMLHttpRequest", "append", t, e)
                        }), i.hasBlob()) {
                        var p = i.getBlob();
                        p.isDetached() ? l(p, function (e) {
                            p.destroy(), s(d, e)
                        }) : s(d, p)
                    } else i = null, r()
                } else i instanceof n ? i.isDetached() ? l(i, function (e) {
                    i.destroy(), i = e.uid, r()
                }) : (i = i.uid, r()) : r()
            }, getResponse: function (e) {
                var n, o, a = this.getRuntime();
                if (o = a.shimExec.call(this, "XMLHttpRequest", "getResponseAsBlob")) {
                    if (o = new i(a.uid, o), "blob" === e)return o;
                    try {
                        if (n = new r, ~t.inArray(e, ["", "text"]))return n.readAsText(o);
                        if ("json" === e && window.JSON)return JSON.parse(n.readAsText(o))
                    } finally {
                        o.destroy()
                    }
                }
                return null
            }, abort: function () {
                var e = this.getRuntime();
                e.shimExec.call(this, "XMLHttpRequest", "abort"), this.dispatchEvent("readystatechange"), this.dispatchEvent("abort")
            }
        };
        return e.XMLHttpRequest = s
    }), i("moxie/runtime/flash/runtime/Transporter", ["moxie/runtime/flash/Runtime", "moxie/file/Blob"], function (e, t) {
        var n = {
            getAsBlob: function (e) {
                var n = this.getRuntime(), i = n.shimExec.call(this, "Transporter", "getAsBlob", e);
                return i ? new t(n.uid, i) : null
            }
        };
        return e.Transporter = n
    }), i("moxie/runtime/flash/image/Image", ["moxie/runtime/flash/Runtime", "moxie/core/utils/Basic", "moxie/runtime/Transporter", "moxie/file/Blob", "moxie/file/FileReaderSync"], function (e, t, n, i, r) {
        var o = {
            loadFromBlob: function (e) {
                function t(e) {
                    r.shimExec.call(i, "Image", "loadFromBlob", e.uid), i = r = null
                }

                var i = this, r = i.getRuntime();
                if (e.isDetached()) {
                    var o = new n;
                    o.bind("TransportingComplete", function () {
                        t(o.result.getSource())
                    }), o.transport(e.getSource(), e.type, {ruid: r.uid})
                } else t(e.getSource())
            }, loadFromImage: function (e) {
                var t = this.getRuntime();
                return t.shimExec.call(this, "Image", "loadFromImage", e.uid)
            }, getAsBlob: function (e, t) {
                var n = this.getRuntime(), r = n.shimExec.call(this, "Image", "getAsBlob", e, t);
                return r ? new i(n.uid, r) : null
            }, getAsDataURL: function () {
                var e, t = this.getRuntime(), n = t.Image.getAsBlob.apply(this, arguments);
                return n ? (e = new r, e.readAsDataURL(n)) : null
            }
        };
        return e.Image = o
    }), i("moxie/runtime/silverlight/Runtime", ["moxie/core/utils/Basic", "moxie/core/utils/Env", "moxie/core/utils/Dom", "moxie/core/Exceptions", "moxie/runtime/Runtime"], function (e, t, n, i, r) {
        function o(e) {
            var t, n, i, r, o, a = !1, s = null, l = 0;
            try {
                try {
                    s = new ActiveXObject("AgControl.AgControl"), s.IsVersionSupported(e) && (a = !0), s = null
                } catch (u) {
                    var c = navigator.plugins["Silverlight Plug-In"];
                    if (c) {
                        for (t = c.description, "1.0.30226.2" === t && (t = "2.0.30226.2"), n = t.split("."); n.length > 3;)n.pop();
                        for (; n.length < 4;)n.push(0);
                        for (i = e.split("."); i.length > 4;)i.pop();
                        do r = parseInt(i[l], 10), o = parseInt(n[l], 10), l++; while (l < i.length && r === o);
                        o >= r && !isNaN(r) && (a = !0)
                    }
                }
            } catch (d) {
                a = !1
            }
            return a
        }

        function a(a) {
            var u, c = this;
            a = e.extend({xap_url: t.xap_url}, a), r.call(this, a, s, {
                access_binary: r.capTrue,
                access_image_binary: r.capTrue,
                display_media: r.capTrue,
                do_cors: r.capTrue,
                drag_and_drop: !1,
                report_upload_progress: r.capTrue,
                resize_image: r.capTrue,
                return_response_headers: function (e) {
                    return e && "client" === c.mode
                },
                return_response_type: function (e) {
                    return "json" !== e ? !0 : !!window.JSON
                },
                return_status_code: function (t) {
                    return "client" === c.mode || !e.arrayDiff(t, [200, 404])
                },
                select_file: r.capTrue,
                select_multiple: r.capTrue,
                send_binary_string: r.capTrue,
                send_browser_cookies: function (e) {
                    return e && "browser" === c.mode
                },
                send_custom_headers: function (e) {
                    return e && "client" === c.mode
                },
                send_multipart: r.capTrue,
                slice_blob: r.capTrue,
                stream_upload: !0,
                summon_file_dialog: !1,
                upload_filesize: r.capTrue,
                use_http_method: function (t) {
                    return "client" === c.mode || !e.arrayDiff(t, ["GET", "POST"])
                }
            }, {
                return_response_headers: function (e) {
                    return e ? "client" : "browser"
                }, return_status_code: function (t) {
                    return e.arrayDiff(t, [200, 404]) ? "client" : ["client", "browser"]
                }, send_browser_cookies: function (e) {
                    return e ? "browser" : "client"
                }, send_custom_headers: function (e) {
                    return e ? "client" : "browser"
                }, use_http_method: function (t) {
                    return e.arrayDiff(t, ["GET", "POST"]) ? "client" : ["client", "browser"]
                }
            }), o("2.0.31005.0") && "Opera" !== t.browser || (this.mode = !1), e.extend(this, {
                getShim: function () {
                    return n.get(this.uid).content.Moxie
                }, shimExec: function (e, t) {
                    var n = [].slice.call(arguments, 2);
                    return c.getShim().exec(this.uid, e, t, n)
                }, init: function () {
                    var e;
                    e = this.getShimContainer(), e.innerHTML = '<object id="' + this.uid + '" data="data:application/x-silverlight," type="application/x-silverlight-2" width="100%" height="100%" style="outline:none;"><param name="source" value="' + a.xap_url + '"/><param name="background" value="Transparent"/><param name="windowless" value="true"/><param name="enablehtmlaccess" value="true"/><param name="initParams" value="uid=' + this.uid + ",target=" + t.global_event_dispatcher + '"/></object>', u = setTimeout(function () {
                        c && !c.initialized && c.trigger("Error", new i.RuntimeError(i.RuntimeError.NOT_INIT_ERR))
                    }, "Windows" !== t.OS ? 1e4 : 5e3)
                }, destroy: function (e) {
                    return function () {
                        e.call(c), clearTimeout(u), a = u = e = c = null
                    }
                }(this.destroy)
            }, l)
        }

        var s = "silverlight", l = {};
        return r.addConstructor(s, a), l
    }), i("moxie/runtime/silverlight/file/Blob", ["moxie/runtime/silverlight/Runtime", "moxie/core/utils/Basic", "moxie/runtime/flash/file/Blob"], function (e, t, n) {
        return e.Blob = t.extend({}, n)
    }), i("moxie/runtime/silverlight/file/FileInput", ["moxie/runtime/silverlight/Runtime"], function (e) {
        var t = {
            init: function (e) {
                function t(e) {
                    for (var t = "", n = 0; n < e.length; n++)t += ("" !== t ? "|" : "") + e[n].title + " | *." + e[n].extensions.replace(/,/g, ";*.");
                    return t
                }

                this.getRuntime().shimExec.call(this, "FileInput", "init", t(e.accept), e.name, e.multiple), this.trigger("ready")
            }
        };
        return e.FileInput = t
    }), i("moxie/runtime/silverlight/file/FileDrop", ["moxie/runtime/silverlight/Runtime", "moxie/core/utils/Dom", "moxie/core/utils/Events"], function (e, t, n) {
        var i = {
            init: function () {
                var e, i = this, r = i.getRuntime();
                return e = r.getShimContainer(), n.addEvent(e, "dragover", function (e) {
                    e.preventDefault(), e.stopPropagation(), e.dataTransfer.dropEffect = "copy"
                }, i.uid), n.addEvent(e, "dragenter", function (e) {
                    e.preventDefault();
                    var n = t.get(r.uid).dragEnter(e);
                    n && e.stopPropagation()
                }, i.uid), n.addEvent(e, "drop", function (e) {
                    e.preventDefault();
                    var n = t.get(r.uid).dragDrop(e);
                    n && e.stopPropagation()
                }, i.uid), r.shimExec.call(this, "FileDrop", "init")
            }
        };
        return e.FileDrop = i
    }), i("moxie/runtime/silverlight/file/FileReader", ["moxie/runtime/silverlight/Runtime", "moxie/core/utils/Basic", "moxie/runtime/flash/file/FileReader"], function (e, t, n) {
        return e.FileReader = t.extend({}, n)
    }), i("moxie/runtime/silverlight/file/FileReaderSync", ["moxie/runtime/silverlight/Runtime", "moxie/core/utils/Basic", "moxie/runtime/flash/file/FileReaderSync"], function (e, t, n) {
        return e.FileReaderSync = t.extend({}, n)
    }), i("moxie/runtime/silverlight/xhr/XMLHttpRequest", ["moxie/runtime/silverlight/Runtime", "moxie/core/utils/Basic", "moxie/runtime/flash/xhr/XMLHttpRequest"], function (e, t, n) {
        return e.XMLHttpRequest = t.extend({}, n)
    }), i("moxie/runtime/silverlight/runtime/Transporter", ["moxie/runtime/silverlight/Runtime", "moxie/core/utils/Basic", "moxie/runtime/flash/runtime/Transporter"], function (e, t, n) {
        return e.Transporter = t.extend({}, n)
    }), i("moxie/runtime/silverlight/image/Image", ["moxie/runtime/silverlight/Runtime", "moxie/core/utils/Basic", "moxie/runtime/flash/image/Image"], function (e, t, n) {
        return e.Image = t.extend({}, n, {
            getInfo: function () {
                var e = this.getRuntime(), n = ["tiff", "exif", "gps"], i = {meta: {}}, r = e.shimExec.call(this, "Image", "getInfo");
                return r.meta && t.each(n, function (e) {
                    var t, n, o, a, s = r.meta[e];
                    if (s && s.keys)for (i.meta[e] = {}, n = 0, o = s.keys.length; o > n; n++)t = s.keys[n], a = s[t], a && (/^(\d|[1-9]\d+)$/.test(a) ? a = parseInt(a, 10) : /^\d*\.\d+$/.test(a) && (a = parseFloat(a)), i.meta[e][t] = a)
                }), i.width = parseInt(r.width, 10), i.height = parseInt(r.height, 10), i.size = parseInt(r.size, 10), i.type = r.type, i.name = r.name, i
            }
        })
    }), i("moxie/runtime/html4/Runtime", ["moxie/core/utils/Basic", "moxie/core/Exceptions", "moxie/runtime/Runtime", "moxie/core/utils/Env"], function (e, t, n, i) {
        function r(t) {
            var r = this, s = n.capTest, l = n.capTrue;
            n.call(this, t, o, {
                access_binary: s(window.FileReader || window.File && File.getAsDataURL),
                access_image_binary: !1,
                display_media: s(a.Image && (i.can("create_canvas") || i.can("use_data_uri_over32kb"))),
                do_cors: !1,
                drag_and_drop: !1,
                filter_by_extension: s(function () {
                    return "Chrome" === i.browser && i.version >= 28 || "IE" === i.browser && i.version >= 10
                }()),
                resize_image: function () {
                    return a.Image && r.can("access_binary") && i.can("create_canvas")
                },
                report_upload_progress: !1,
                return_response_headers: !1,
                return_response_type: function (t) {
                    return "json" === t && window.JSON ? !0 : !!~e.inArray(t, ["text", "document", ""])
                },
                return_status_code: function (t) {
                    return !e.arrayDiff(t, [200, 404])
                },
                select_file: function () {
                    return i.can("use_fileinput")
                },
                select_multiple: !1,
                send_binary_string: !1,
                send_custom_headers: !1,
                send_multipart: !0,
                slice_blob: !1,
                stream_upload: function () {
                    return r.can("select_file")
                },
                summon_file_dialog: s(function () {
                    return "Firefox" === i.browser && i.version >= 4 || "Opera" === i.browser && i.version >= 12 || !!~e.inArray(i.browser, ["Chrome", "Safari"])
                }()),
                upload_filesize: l,
                use_http_method: function (t) {
                    return !e.arrayDiff(t, ["GET", "POST"])
                }
            }), e.extend(this, {
                init: function () {
                    this.trigger("Init")
                }, destroy: function (e) {
                    return function () {
                        e.call(r), e = r = null
                    }
                }(this.destroy)
            }), e.extend(this.getShim(), a)
        }

        var o = "html4", a = {};
        return n.addConstructor(o, r), a
    }), i("moxie/runtime/html4/file/FileInput", ["moxie/runtime/html4/Runtime", "moxie/core/utils/Basic", "moxie/core/utils/Dom", "moxie/core/utils/Events", "moxie/core/utils/Mime", "moxie/core/utils/Env"], function (e, t, n, i, r, o) {
        function a() {
            function e() {
                var r, c, d, p, f, h, m = this, g = m.getRuntime();
                h = t.guid("uid_"), r = g.getShimContainer(), a && (d = n.get(a + "_form"), d && t.extend(d.style, {top: "100%"})), p = document.createElement("form"), p.setAttribute("id", h + "_form"), p.setAttribute("method", "post"), p.setAttribute("enctype", "multipart/form-data"), p.setAttribute("encoding", "multipart/form-data"), t.extend(p.style, {
                    overflow: "hidden",
                    position: "absolute",
                    top: 0,
                    left: 0,
                    width: "100%",
                    height: "100%"
                }), f = document.createElement("input"), f.setAttribute("id", h), f.setAttribute("type", "file"), f.setAttribute("name", s.name || "Filedata"), f.setAttribute("accept", u.join(",")), t.extend(f.style, {
                    fontSize: "999px",
                    opacity: 0
                }), p.appendChild(f), r.appendChild(p), t.extend(f.style, {
                    position: "absolute",
                    top: 0,
                    left: 0,
                    width: "100%",
                    height: "100%"
                }), "IE" === o.browser && o.version < 10 && t.extend(f.style, {filter: "progid:DXImageTransform.Microsoft.Alpha(opacity=0)"}), f.onchange = function () {
                    var t;
                    this.value && (t = this.files ? this.files[0] : {name: this.value}, l = [t], this.onchange = function () {
                    }, e.call(m), m.bind("change", function i() {
                        var e, t = n.get(h), r = n.get(h + "_form");
                        m.unbind("change", i), m.files.length && t && r && (e = m.files[0], t.setAttribute("id", e.uid), r.setAttribute("id", e.uid + "_form"), r.setAttribute("target", e.uid + "_iframe")), t = r = null
                    }, 998), f = p = null, m.trigger("change"))
                }, g.can("summon_file_dialog") && (c = n.get(s.browse_button), i.removeEvent(c, "click", m.uid), i.addEvent(c, "click", function (e) {
                    f && !f.disabled && f.click(), e.preventDefault()
                }, m.uid)), a = h, r = d = c = null
            }

            var a, s, l = [], u = [];
            t.extend(this, {
                init: function (t) {
                    var o, a = this, l = a.getRuntime();
                    s = t, u = t.accept.mimes || r.extList2mimes(t.accept, l.can("filter_by_extension")), o = l.getShimContainer(), function () {
                        var e, r, s;
                        e = n.get(t.browse_button), l.can("summon_file_dialog") && ("static" === n.getStyle(e, "position") && (e.style.position = "relative"), r = parseInt(n.getStyle(e, "z-index"), 10) || 1, e.style.zIndex = r, o.style.zIndex = r - 1), s = l.can("summon_file_dialog") ? e : o, i.addEvent(s, "mouseover", function () {
                            a.trigger("mouseenter")
                        }, a.uid), i.addEvent(s, "mouseout", function () {
                            a.trigger("mouseleave")
                        }, a.uid), i.addEvent(s, "mousedown", function () {
                            a.trigger("mousedown")
                        }, a.uid), i.addEvent(n.get(t.container), "mouseup", function () {
                            a.trigger("mouseup")
                        }, a.uid), e = null
                    }(), e.call(this), o = null, a.trigger({type: "ready", async: !0})
                }, getFiles: function () {
                    return l
                }, disable: function (e) {
                    var t;
                    (t = n.get(a)) && (t.disabled = !!e)
                }, destroy: function () {
                    var e = this.getRuntime(), t = e.getShim(), r = e.getShimContainer();
                    i.removeAllEvents(r, this.uid), i.removeAllEvents(s && n.get(s.container), this.uid), i.removeAllEvents(s && n.get(s.browse_button), this.uid), r && (r.innerHTML = ""), t.removeInstance(this.uid), a = l = u = s = r = t = null
                }
            })
        }

        return e.FileInput = a
    }), i("moxie/runtime/html4/file/FileReader", ["moxie/runtime/html4/Runtime", "moxie/runtime/html5/file/FileReader"], function (e, t) {
        return e.FileReader = t
    }), i("moxie/runtime/html4/xhr/XMLHttpRequest", ["moxie/runtime/html4/Runtime", "moxie/core/utils/Basic", "moxie/core/utils/Dom", "moxie/core/utils/Url", "moxie/core/Exceptions", "moxie/core/utils/Events", "moxie/file/Blob", "moxie/xhr/FormData"], function (e, t, n, i, r, o, a, s) {
        function l() {
            function e(e) {
                var t, i, r, a, s = this, l = !1;
                if (c) {
                    if (t = c.id.replace(/_iframe$/, ""), i = n.get(t + "_form")) {
                        for (r = i.getElementsByTagName("input"), a = r.length; a--;)switch (r[a].getAttribute("type")) {
                            case"hidden":
                                r[a].parentNode.removeChild(r[a]);
                                break;
                            case"file":
                                l = !0
                        }
                        r = [], l || i.parentNode.removeChild(i), i = null
                    }
                    setTimeout(function () {
                        o.removeEvent(c, "load", s.uid), c.parentNode && c.parentNode.removeChild(c);
                        var t = s.getRuntime().getShimContainer();
                        t.children.length || t.parentNode.removeChild(t), t = c = null, e()
                    }, 1)
                }
            }

            var l, u, c;
            t.extend(this, {
                send: function (d, p) {
                    function f() {
                        var n = b.getShimContainer() || document.body, r = document.createElement("div");
                        r.innerHTML = '<iframe id="' + h + '_iframe" name="' + h + '_iframe" src="javascript:&quot;&quot;" style="display:none"></iframe>', c = r.firstChild, n.appendChild(c), o.addEvent(c, "load", function () {
                            var n;
                            try {
                                n = c.contentWindow.document || c.contentDocument || window.frames[c.id].document, /^4(0[0-9]|1[0-7]|2[2346])\s/.test(n.title) ? l = n.title.replace(/^(\d+).*$/, "$1") : (l = 200, u = t.trim(n.body.innerHTML), y.trigger({
                                    type: "progress",
                                    loaded: u.length,
                                    total: u.length
                                }), v && y.trigger({
                                    type: "uploadprogress",
                                    loaded: v.size || 1025,
                                    total: v.size || 1025
                                }))
                            } catch (r) {
                                if (!i.hasSameOrigin(d.url))return void e.call(y, function () {
                                    y.trigger("error")
                                });
                                l = 404
                            }
                            e.call(y, function () {
                                y.trigger("load")
                            })
                        }, y.uid)
                    }

                    var h, m, g, v, y = this, b = y.getRuntime();
                    if (l = u = null, p instanceof s && p.hasBlob()) {
                        if (v = p.getBlob(), h = v.uid, g = n.get(h), m = n.get(h + "_form"), !m)throw new r.DOMException(r.DOMException.NOT_FOUND_ERR)
                    } else h = t.guid("uid_"), m = document.createElement("form"), m.setAttribute("id", h + "_form"), m.setAttribute("method", d.method), m.setAttribute("enctype", "multipart/form-data"), m.setAttribute("encoding", "multipart/form-data"), m.setAttribute("target", h + "_iframe"), b.getShimContainer().appendChild(m);
                    p instanceof s && p.each(function (e, n) {
                        if (e instanceof a)g && g.setAttribute("name", n); else {
                            var i = document.createElement("input");
                            t.extend(i, {
                                type: "hidden",
                                name: n,
                                value: e
                            }), g ? m.insertBefore(i, g) : m.appendChild(i)
                        }
                    }), m.setAttribute("action", d.url), f(), m.submit(), y.trigger("loadstart")
                }, getStatus: function () {
                    return l
                }, getResponse: function (e) {
                    if ("json" === e && "string" === t.typeOf(u) && window.JSON)try {
                        return JSON.parse(u.replace(/^\s*<pre[^>]*>/, "").replace(/<\/pre>\s*$/, ""))
                    } catch (n) {
                        return null
                    }
                    return u
                }, abort: function () {
                    var t = this;
                    c && c.contentWindow && (c.contentWindow.stop ? c.contentWindow.stop() : c.contentWindow.document.execCommand ? c.contentWindow.document.execCommand("Stop") : c.src = "about:blank"), e.call(this, function () {
                        t.dispatchEvent("abort")
                    })
                }
            })
        }

        return e.XMLHttpRequest = l
    }), i("moxie/runtime/html4/image/Image", ["moxie/runtime/html4/Runtime", "moxie/runtime/html5/image/Image"], function (e, t) {
        return e.Image = t
    }), o(["moxie/core/utils/Basic", "moxie/core/I18n", "moxie/core/utils/Mime", "moxie/core/utils/Env", "moxie/core/utils/Dom", "moxie/core/Exceptions", "moxie/core/EventTarget", "moxie/core/utils/Encode", "moxie/runtime/Runtime", "moxie/runtime/RuntimeClient", "moxie/file/Blob", "moxie/file/File", "moxie/file/FileInput", "moxie/file/FileDrop", "moxie/runtime/RuntimeTarget", "moxie/file/FileReader", "moxie/core/utils/Url", "moxie/file/FileReaderSync", "moxie/xhr/FormData", "moxie/xhr/XMLHttpRequest", "moxie/runtime/Transporter", "moxie/image/Image", "moxie/core/utils/Events"])
}(this), function () {
    "use strict";
    var e = {}, t = moxie.core.utils.Basic.inArray;
    return function n(i) {
        var r, o;
        for (r in i)o = typeof i[r], "object" !== o || ~t(r, ["Exceptions", "Env", "Mime"]) ? "function" === o && (e[r] = i[r]) : n(i[r])
    }(window.moxie), e.Env = window.moxie.core.utils.Env, e.Mime = window.moxie.core.utils.Mime, e.Exceptions = window.moxie.core.Exceptions, window.mOxie = e, window.o || (window.o = e), e
}(), function (e, t, n) {
    function i(e) {
        function t(e, t, n) {
            var r = {
                chunks: "slice_blob",
                jpgresize: "send_binary_string",
                pngresize: "send_binary_string",
                progress: "report_upload_progress",
                multi_selection: "select_multiple",
                dragdrop: "drag_and_drop",
                drop_element: "drag_and_drop",
                headers: "send_custom_headers",
                canSendBinary: "send_binary",
                triggerDialog: "summon_file_dialog"
            };
            r[e] ? i[r[e]] = t : n || (i[e] = t)
        }

        var n = e.required_features, i = {};
        return "string" == typeof n ? a.each(n.split(/\s*,\s*/), function (e) {
            t(e, !0)
        }) : "object" == typeof n ? a.each(n, function (e, n) {
            t(n, e)
        }) : n === !0 && (e.multipart || (i.send_binary_string = !0), e.chunk_size > 0 && (i.slice_blob = !0), e.resize.enabled && (i.send_binary_string = !0), a.each(e, function (e, n) {
            t(n, !!e, !0)
        })), i
    }

    var r = e.setTimeout, o = {}, a = {
        VERSION: "2.1.1",
        STOPPED: 1,
        STARTED: 2,
        QUEUED: 1,
        UPLOADING: 2,
        FAILED: 4,
        DONE: 5,
        GENERIC_ERROR: -100,
        HTTP_ERROR: -200,
        IO_ERROR: -300,
        SECURITY_ERROR: -400,
        INIT_ERROR: -500,
        FILE_SIZE_ERROR: -600,
        FILE_EXTENSION_ERROR: -601,
        FILE_DUPLICATE_ERROR: -602,
        IMAGE_FORMAT_ERROR: -700,
        IMAGE_MEMORY_ERROR: -701,
        IMAGE_DIMENSIONS_ERROR: -702,
        mimeTypes: t.mimes,
        ua: t.ua,
        typeOf: t.typeOf,
        extend: t.extend,
        guid: t.guid,
        get: function (e) {
            var n, i = [];
            "array" !== t.typeOf(e) && (e = [e]);
            for (var r = e.length; r--;)n = t.get(e[r]), n && i.push(n);
            return i.length ? i : null
        },
        each: t.each,
        getPos: t.getPos,
        getSize: t.getSize,
        xmlEncode: function (e) {
            var t = {"<": "lt", ">": "gt", "&": "amp", '"': "quot", "'": "#39"}, n = /[<>&\"\']/g;
            return e ? ("" + e).replace(n, function (e) {
                return t[e] ? "&" + t[e] + ";" : e
            }) : e
        },
        toArray: t.toArray,
        inArray: t.inArray,
        addI18n: t.addI18n,
        translate: t.translate,
        isEmptyObj: t.isEmptyObj,
        hasClass: t.hasClass,
        addClass: t.addClass,
        removeClass: t.removeClass,
        getStyle: t.getStyle,
        addEvent: t.addEvent,
        removeEvent: t.removeEvent,
        removeAllEvents: t.removeAllEvents,
        cleanName: function (e) {
            var t, n;
            for (n = [/[\300-\306]/g, "A", /[\340-\346]/g, "a", /\307/g, "C", /\347/g, "c", /[\310-\313]/g, "E", /[\350-\353]/g, "e", /[\314-\317]/g, "I", /[\354-\357]/g, "i", /\321/g, "N", /\361/g, "n", /[\322-\330]/g, "O", /[\362-\370]/g, "o", /[\331-\334]/g, "U", /[\371-\374]/g, "u"], t = 0; t < n.length; t += 2)e = e.replace(n[t], n[t + 1]);
            return e = e.replace(/\s+/g, "_"), e = e.replace(/[^a-z0-9_\-\.]+/gi, "")
        },
        buildUrl: function (e, t) {
            var n = "";
            return a.each(t, function (e, t) {
                n += (n ? "&" : "") + encodeURIComponent(t) + "=" + encodeURIComponent(e)
            }), n && (e += (e.indexOf("?") > 0 ? "&" : "?") + n), e
        },
        formatSize: function (e) {
            function t(e, t) {
                return Math.round(e * Math.pow(10, t)) / Math.pow(10, t)
            }

            if (e === n || /\D/.test(e))return a.translate("N/A");
            var i = Math.pow(1024, 4);
            return e > i ? t(e / i, 1) + " " + a.translate("tb") : e > (i /= 1024) ? t(e / i, 1) + " " + a.translate("gb") : e > (i /= 1024) ? t(e / i, 1) + " " + a.translate("mb") : e > 1024 ? Math.round(e / 1024) + " " + a.translate("kb") : e + " " + a.translate("b")
        },
        parseSize: t.parseSizeStr,
        predictRuntime: function (e, n) {
            var i, r;
            return i = new a.Uploader(e), r = t.Runtime.thatCan(i.getOption().required_features, n || e.runtimes), i.destroy(), r
        },
        addFileFilter: function (e, t) {
            o[e] = t
        }
    };
    a.addFileFilter("mime_types", function (e, t, n) {
        e.length && !e.regexp.test(t.name) ? (this.trigger("Error", {
            code: a.FILE_EXTENSION_ERROR,
            message: a.translate("File extension error."),
            file: t
        }), n(!1)) : n(!0)
    }), a.addFileFilter("max_file_size", function (e, t, n) {
        var i;
        e = a.parseSize(e), t.size !== i && e && t.size > e ? (this.trigger("Error", {
            code: a.FILE_SIZE_ERROR,
            message: a.translate("File size error."),
            file: t
        }), n(!1)) : n(!0)
    }), a.addFileFilter("prevent_duplicates", function (e, t, n) {
        if (e)for (var i = this.files.length; i--;)if (t.name === this.files[i].name && t.size === this.files[i].size)return this.trigger("Error", {
            code: a.FILE_DUPLICATE_ERROR,
            message: a.translate("Duplicate file error."),
            file: t
        }), void n(!1);
        n(!0)
    }), a.Uploader = function (e) {
        function s() {
            var e, t, n = 0;
            if (this.state == a.STARTED) {
                for (t = 0; t < O.length; t++)e || O[t].status != a.QUEUED ? n++ : (e = O[t], this.trigger("BeforeUpload", e) && (e.status = a.UPLOADING, this.trigger("UploadFile", e)));
                n == O.length && (this.state !== a.STOPPED && (this.state = a.STOPPED, this.trigger("StateChanged")), this.trigger("UploadComplete", O))
            }
        }

        function l(e) {
            e.percent = e.size > 0 ? Math.ceil(e.loaded / e.size * 100) : 100, u()
        }

        function u() {
            var e, t;
            for (C.reset(), e = 0; e < O.length; e++)t = O[e], t.size !== n ? (C.size += t.origSize, C.loaded += t.loaded * t.origSize / t.size) : C.size = n, t.status == a.DONE ? C.uploaded++ : t.status == a.FAILED ? C.failed++ : C.queued++;
            C.size === n ? C.percent = O.length > 0 ? Math.ceil(C.uploaded / O.length * 100) : 0 : (C.bytesPerSec = Math.ceil(C.loaded / ((+new Date - R || 1) / 1e3)), C.percent = C.size > 0 ? Math.ceil(C.loaded / C.size * 100) : 0)
        }

        function c() {
            var e = k[0] || I[0];
            return e ? e.getRuntime().uid : !1
        }

        function d(e, n) {
            if (e.ruid) {
                var i = t.Runtime.getInfo(e.ruid);
                if (i)return i.can(n)
            }
            return !1
        }

        function p() {
            this.bind("FilesAdded", g), this.bind("CancelUpload", w), this.bind("BeforeUpload", v), this.bind("UploadFile", y), this.bind("UploadProgress", b), this.bind("StateChanged", x), this.bind("QueueChanged", u), this.bind("Error", T), this.bind("FileUploaded", E), this.bind("Destroy", S)
        }

        function f(e, n) {
            var i = this, r = 0, o = [], s = {
                accept: e.filters.mime_types,
                runtime_order: e.runtimes,
                required_caps: e.required_features,
                preferred_caps: A,
                swf_url: e.flash_swf_url,
                xap_url: e.silverlight_xap_url
            };
            a.each(e.runtimes.split(/\s*,\s*/), function (t) {
                e[t] && (s[t] = e[t])
            }), e.browse_button && a.each(e.browse_button, function (n) {
                o.push(function (o) {
                    var l = new t.FileInput(a.extend({}, s, {
                        name: e.file_data_name,
                        multiple: e.multi_selection,
                        container: e.container,
                        browse_button: n
                    }));
                    l.onready = function () {
                        var e = t.Runtime.getInfo(this.ruid);
                        t.extend(i.features, {
                            chunks: e.can("slice_blob"),
                            multipart: e.can("send_multipart"),
                            multi_selection: e.can("select_multiple")
                        }), r++, k.push(this), o()
                    }, l.onchange = function () {
                        i.addFile(this.files)
                    }, l.bind("mouseenter mouseleave mousedown mouseup", function (i) {
                        F || (e.browse_button_hover && ("mouseenter" === i.type ? t.addClass(n, e.browse_button_hover) : "mouseleave" === i.type && t.removeClass(n, e.browse_button_hover)), e.browse_button_active && ("mousedown" === i.type ? t.addClass(n, e.browse_button_active) : "mouseup" === i.type && t.removeClass(n, e.browse_button_active)))
                    }), l.bind("error runtimeerror", function () {
                        l = null, o()
                    }), l.init()
                })
            }), e.drop_element && a.each(e.drop_element, function (e) {
                o.push(function (n) {
                    var o = new t.FileDrop(a.extend({}, s, {drop_zone: e}));
                    o.onready = function () {
                        var e = t.Runtime.getInfo(this.ruid);
                        i.features.dragdrop = e.can("drag_and_drop"), r++, I.push(this), n()
                    }, o.ondrop = function () {
                        i.addFile(this.files)
                    }, o.bind("error runtimeerror", function () {
                        o = null, n()
                    }), o.init()
                })
            }), t.inSeries(o, function () {
                "function" == typeof n && n(r)
            })
        }

        function h(e, n, i) {
            var r = new t.Image;
            try {
                r.onload = function () {
                    r.downsize(n.width, n.height, n.crop, n.preserve_headers)
                }, r.onresize = function () {
                    i(this.getAsBlob(e.type, n.quality)), this.destroy()
                }, r.onerror = function () {
                    i(e)
                }, r.load(e)
            } catch (o) {
                i(e)
            }
        }

        function m(e, n, r) {
            function o(e, t, n) {
                var i = _[e];
                switch (e) {
                    case"max_file_size":
                        "max_file_size" === e && (_.max_file_size = _.filters.max_file_size = t);
                        break;
                    case"chunk_size":
                        (t = a.parseSize(t)) && (_[e] = t);
                        break;
                    case"filters":
                        "array" === a.typeOf(t) && (t = {mime_types: t}), n ? a.extend(_.filters, t) : _.filters = t, t.mime_types && (_.filters.mime_types.regexp = function (e) {
                            var t = [];
                            return a.each(e, function (e) {
                                a.each(e.extensions.split(/,/), function (e) {
                                    t.push(/^\s*\*\s*$/.test(e) ? "\\.*" : "\\." + e.replace(new RegExp("[" + "/^$.*+?|()[]{}\\".replace(/./g, "\\$&") + "]", "g"), "\\$&"))
                                })
                            }), new RegExp("(" + t.join("|") + ")$", "i")
                        }(_.filters.mime_types));
                        break;
                    case"resize":
                        n ? a.extend(_.resize, t, {enabled: !0}) : _.resize = t;
                        break;
                    case"prevent_duplicates":
                        _.prevent_duplicates = _.filters.prevent_duplicates = !!t;
                        break;
                    case"browse_button":
                    case"drop_element":
                        t = a.get(t);
                    case"container":
                    case"runtimes":
                    case"multi_selection":
                    case"flash_swf_url":
                    case"silverlight_xap_url":
                        _[e] = t, n || (l = !0);
                        break;
                    default:
                        _[e] = t
                }
                n || s.trigger("OptionChanged", e, t, i)
            }

            var s = this, l = !1;
            "object" == typeof e ? a.each(e, function (e, t) {
                o(t, e, r)
            }) : o(e, n, r), r ? (_.required_features = i(a.extend({}, _)), A = i(a.extend({}, _, {required_features: !0}))) : l && (s.trigger("Destroy"), f.call(s, _, function (e) {
                e ? (s.runtime = t.Runtime.getInfo(c()).type, s.trigger("Init", {runtime: s.runtime}), s.trigger("PostInit")) : s.trigger("Error", {
                    code: a.INIT_ERROR,
                    message: a.translate("Init error.")
                })
            }))
        }

        function g(e, t) {
            [].push.apply(O, t), e.trigger("QueueChanged"), e.refresh()
        }

        function v(e, t) {
            if (_.unique_names) {
                var n = t.name.match(/\.([^.]+)$/), i = "part";
                n && (i = n[1]), t.target_name = t.id + "." + i
            }
        }

        function y(e, n) {
            function i() {
                c-- > 0 ? r(o, 1e3) : (n.loaded = f, e.trigger("Error", {
                    code: a.HTTP_ERROR,
                    message: a.translate("HTTP Error."),
                    file: n,
                    response: D.responseText,
                    status: D.status,
                    responseHeaders: D.getAllResponseHeaders()
                }))
            }

            function o() {
                var d, h, m, g;
                n.status != a.DONE && n.status != a.FAILED && e.state != a.STOPPED && (m = {name: n.target_name || n.name}, u && p.chunks && s.size > u ? (g = Math.min(u, s.size - f), d = s.slice(f, f + g)) : (g = s.size, d = s), u && p.chunks && (e.settings.send_chunk_number ? (m.chunk = Math.ceil(f / u), m.chunks = Math.ceil(s.size / u)) : (m.offset = f, m.total = s.size)), D = new t.XMLHttpRequest, D.upload && (D.upload.onprogress = function (t) {
                    n.loaded = Math.min(n.size, f + t.loaded), e.trigger("UploadProgress", n)
                }), D.onload = function () {
                    return D.status >= 400 ? void i() : (c = e.settings.max_retries, g < s.size ? (d.destroy(), f += g, n.loaded = Math.min(f, s.size), e.trigger("ChunkUploaded", n, {
                        offset: n.loaded,
                        total: s.size,
                        response: D.responseText,
                        status: D.status,
                        responseHeaders: D.getAllResponseHeaders()
                    }), "Android Browser" === t.Env.browser && e.trigger("UploadProgress", n)) : n.loaded = n.size, d = h = null, void(!f || f >= s.size ? (n.size != n.origSize && (s.destroy(), s = null), e.trigger("UploadProgress", n), n.status = a.DONE, e.trigger("FileUploaded", n, {
                        response: D.responseText,
                        status: D.status,
                        responseHeaders: D.getAllResponseHeaders()
                    })) : r(o, 1)))
                }, D.onerror = function () {
                    i()
                }, D.onloadend = function () {
                    this.destroy(), D = null
                }, e.settings.multipart && p.multipart ? (m.name = n.target_name || n.name, D.open("post", l, !0), a.each(e.settings.headers, function (e, t) {
                    D.setRequestHeader(t, e)
                }), h = new t.FormData, a.each(a.extend(m, e.settings.multipart_params), function (e, t) {
                    h.append(t, e)
                }), h.append(e.settings.file_data_name, d), D.send(h, {
                    runtime_order: e.settings.runtimes,
                    required_caps: e.settings.required_features,
                    preferred_caps: A,
                    swf_url: e.settings.flash_swf_url,
                    xap_url: e.settings.silverlight_xap_url
                })) : (l = a.buildUrl(e.settings.url, a.extend(m, e.settings.multipart_params)), D.open("post", l, !0), D.setRequestHeader("Content-Type", "application/octet-stream"), a.each(e.settings.headers, function (e, t) {
                    D.setRequestHeader(t, e)
                }), D.send(d, {
                    runtime_order: e.settings.runtimes,
                    required_caps: e.settings.required_features,
                    preferred_caps: A,
                    swf_url: e.settings.flash_swf_url,
                    xap_url: e.settings.silverlight_xap_url
                })))
            }

            var s, l = e.settings.url, u = e.settings.chunk_size, c = e.settings.max_retries, p = e.features, f = 0;
            n.loaded && (f = n.loaded = u * Math.floor(n.loaded / u)), s = n.getSource(), e.settings.resize.enabled && d(s, "send_binary_string") && ~t.inArray(s.type, ["image/jpeg", "image/png"]) ? h.call(this, s, e.settings.resize, function (e) {
                s = e, n.size = e.size, o()
            }) : o()
        }

        function b(e, t) {
            l(t)
        }

        function x(e) {
            if (e.state == a.STARTED)R = +new Date; else if (e.state == a.STOPPED)for (var t = e.files.length - 1; t >= 0; t--)e.files[t].status == a.UPLOADING && (e.files[t].status = a.QUEUED, u())
        }

        function w() {
            D && D.abort()
        }

        function E(e) {
            u(), r(function () {
                s.call(e)
            }, 1)
        }

        function T(e, t) {
            t.file && (t.file.status = a.FAILED, l(t.file), e.state == a.STARTED && (e.trigger("CancelUpload"), r(function () {
                s.call(e)
            }, 1)))
        }

        function S(e) {
            e.stop(), a.each(O, function (e) {
                e.destroy()
            }), O = [], k.length && (a.each(k, function (e) {
                e.destroy()
            }), k = []), I.length && (a.each(I, function (e) {
                e.destroy()
            }), I = []), A = {}, F = !1, R = D = null, C.reset()
        }

        var _, R, C, D, N = a.guid(), O = [], A = {}, k = [], I = [], F = !1;
        _ = {
            runtimes: t.Runtime.order,
            max_retries: 0,
            chunk_size: 0,
            multipart: !0,
            multi_selection: !0,
            file_data_name: "file",
            flash_swf_url: "js/Moxie.swf",
            silverlight_xap_url: "js/Moxie.xap",
            filters: {mime_types: [], prevent_duplicates: !1, max_file_size: 0},
            resize: {enabled: !1, preserve_headers: !0, crop: !1},
            send_chunk_number: !0
        }, m.call(this, e, null, !0), C = new a.QueueProgress, a.extend(this, {
            id: N,
            uid: N,
            state: a.STOPPED,
            features: {},
            runtime: null,
            files: O,
            settings: _,
            total: C,
            init: function () {
                var e = this;
                return "function" == typeof _.preinit ? _.preinit(e) : a.each(_.preinit, function (t, n) {
                    e.bind(n, t)
                }), _.browse_button && _.url ? (p.call(this), void f.call(this, _, function (n) {
                    "function" == typeof _.init ? _.init(e) : a.each(_.init, function (t, n) {
                        e.bind(n, t)
                    }), n ? (e.runtime = t.Runtime.getInfo(c()).type, e.trigger("Init", {runtime: e.runtime}), e.trigger("PostInit")) : e.trigger("Error", {
                        code: a.INIT_ERROR,
                        message: a.translate("Init error.")
                    })
                })) : void this.trigger("Error", {code: a.INIT_ERROR, message: a.translate("Init error.")})
            },
            setOption: function (e, t) {
                m.call(this, e, t, !this.runtime)
            },
            getOption: function (e) {
                return e ? _[e] : _
            },
            refresh: function () {
                k.length && a.each(k, function (e) {
                    e.trigger("Refresh")
                }), this.trigger("Refresh")
            },
            start: function () {
                this.state != a.STARTED && (this.state = a.STARTED, this.trigger("StateChanged"), s.call(this))
            },
            stop: function () {
                this.state != a.STOPPED && (this.state = a.STOPPED, this.trigger("StateChanged"), this.trigger("CancelUpload"))
            },
            disableBrowse: function () {
                F = arguments[0] !== n ? arguments[0] : !0, k.length && a.each(k, function (e) {
                    e.disable(F)
                }), this.trigger("DisableBrowse", F)
            },
            getFile: function (e) {
                var t;
                for (t = O.length - 1; t >= 0; t--)if (O[t].id === e)return O[t]
            },
            addFile: function (e, n) {
                function i(e, n) {
                    var i = [];
                    t.each(u.settings.filters, function (t, n) {
                        o[n] && i.push(function (i) {
                            o[n].call(u, t, e, function (e) {
                                i(!e)
                            })
                        })
                    }), t.inSeries(i, n)
                }

                function s(e) {
                    var o = t.typeOf(e);
                    if (e instanceof t.File) {
                        if (!e.ruid && !e.isDetached()) {
                            if (!l)return !1;
                            e.ruid = l, e.connectRuntime(l)
                        }
                        s(new a.File(e))
                    } else e instanceof t.Blob ? (s(e.getSource()), e.destroy()) : e instanceof a.File ? (n && (e.name = n), d.push(function (t) {
                        i(e, function (n) {
                            n || (p.push(e), u.trigger("FileFiltered", e)), r(t, 1)
                        })
                    })) : -1 !== t.inArray(o, ["file", "blob"]) ? s(new t.File(null, e)) : "node" === o && "filelist" === t.typeOf(e.files) ? t.each(e.files, s) : "array" === o && (n = null, t.each(e, s))
                }

                var l, u = this, d = [], p = [];
                l = c(), s(e), d.length && t.inSeries(d, function () {
                    p.length && u.trigger("FilesAdded", p)
                })
            },
            removeFile: function (e) {
                for (var t = "string" == typeof e ? e : e.id, n = O.length - 1; n >= 0; n--)if (O[n].id === t)return this.splice(n, 1)[0]
            },
            splice: function (e, t) {
                var i = O.splice(e === n ? 0 : e, t === n ? O.length : t), r = !1;
                return this.state == a.STARTED && (r = !0, this.stop()), this.trigger("FilesRemoved", i), a.each(i, function (e) {
                    e.destroy()
                }), this.trigger("QueueChanged"), this.refresh(), r && this.start(), i
            },
            bind: function (e, t, n) {
                var i = this;
                a.Uploader.prototype.bind.call(this, e, function () {
                    var e = [].slice.call(arguments);
                    return e.splice(0, 1, i), t.apply(this, e)
                }, 0, n)
            },
            destroy: function () {
                this.trigger("Destroy"), _ = C = null, this.unbindAll()
            }
        })
    }, a.Uploader.prototype = t.EventTarget.instance, a.File = function () {
        function e(e) {
            a.extend(this, {
                id: a.guid(),
                name: e.name || e.fileName,
                type: e.type || "",
                size: e.size || e.fileSize,
                origSize: e.size || e.fileSize,
                loaded: 0,
                percent: 0,
                status: a.QUEUED,
                lastModifiedDate: e.lastModifiedDate || (new Date).toLocaleString(),
                getNative: function () {
                    var e = this.getSource().getSource();
                    return -1 !== t.inArray(t.typeOf(e), ["blob", "file"]) ? e : null
                },
                getSource: function () {
                    return n[this.id] ? n[this.id] : null
                },
                destroy: function () {
                    var e = this.getSource();
                    e && (e.destroy(), delete n[this.id])
                }
            }), n[this.id] = e
        }

        var n = {};
        return e
    }(), a.QueueProgress = function () {
        var e = this;
        e.size = 0, e.loaded = 0, e.uploaded = 0, e.failed = 0, e.queued = 0, e.percent = 0, e.bytesPerSec = 0, e.reset = function () {
            e.size = e.loaded = e.uploaded = e.failed = e.queued = e.percent = e.bytesPerSec = 0
        }
    }, e.plupload = a
}(window, mOxie);