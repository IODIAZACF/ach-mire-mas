! function(l) {
    function t(t) {
        for (var e, n, a = t[0], i = t[1], r = t[2], o = 0, s = []; o < a.length; o++) n = a[o], Object.prototype.hasOwnProperty.call(h, n) && h[n] && s.push(h[n][0]), h[n] = 0;
        for (e in i) Object.prototype.hasOwnProperty.call(i, e) && (l[e] = i[e]);
        for (p && p(t); s.length;) s.shift()();
        return f.push.apply(f, r || []), c()
    }

    function c() {
        var t, e, n, a, i, r;
        for (e = 0; e < f.length; e++) {
            for (n = f[e], a = !0, i = 1; i < n.length; i++) r = n[i], 0 !== h[r] && (a = !1);
            a && (f.splice(e--, 1), t = d(d.s = n[0]))
        }
        return t
    }

    function d(t) {
        if (i[t]) return i[t].exports;
        var e = i[t] = {
            i: t,
            l: !1,
            exports: {}
        };
        return l[t].call(e.exports, e, e.exports, d), e.l = !0, e.exports
    }
    var e, n, a, p, i = {},
        u = {
            5: 0
        },
        h = {
            5: 0
        },
        f = [];
    for (d.e = function(p) {
            var n, t, i, e, r, o, a = [],
                s = {
                    0: 1,
                    3: 1,
                    4: 1,
                    7: 1,
                    8: 1,
                    9: 1,
                    10: 1,
                    11: 1,
                    12: 1,
                    13: 1
                };
            return u[p] ? a.push(u[p]) : 0 !== u[p] && s[p] && a.push(u[p] = new Promise(function(t, a) {
                var e, n, i, r, o, s = p + "." + {
                        0: "7a1757984c1e2133d865",
                        3: "4a1b8983301c6a62b0b3",
                        4: "892a8e22dd43601885a2",
                        7: "28765460a15b83b3909c",
                        8: "5e89e9a53ae786ca3963",
                        9: "5316a9700fa6a89cb22e",
                        10: "c011adaf8c67e6a756bf",
                        11: "28765460a15b83b3909c",
                        12: "b11a4600f724a5bedcb8",
                        13: "b7832c21b7471e5576da",
                        14: "31d6cfe0d16ae931b73c",
                        15: "31d6cfe0d16ae931b73c",
                        16: "31d6cfe0d16ae931b73c",
                        17: "31d6cfe0d16ae931b73c"
                    } [p] + ".css",
                    l = d.p + s,
                    c = document.getElementsByTagName("link");
                for (e = 0; e < c.length; e++)
                    if (i = (n = c[e]).getAttribute("data-href") || n.getAttribute("href"), "stylesheet" === n.rel && (i === s || i === l)) return t();
                for (r = document.getElementsByTagName("style"), e = 0; e < r.length; e++)
                    if ((i = (n = r[e]).getAttribute("data-href")) === s || i === l) return t();
                (o = document.createElement("link")).rel = "stylesheet", o.type = "text/css", o.onload = t, o.onerror = function(t) {
                    var e = t && t.target && t.target.src || l,
                        n = new Error("Loading CSS chunk " + p + " failed.\n(" + e + ")");
                    n.code = "CSS_CHUNK_LOAD_FAILED", n.request = e, delete u[p], o.parentNode.removeChild(o), a(n)
                }, o.href = l, document.getElementsByTagName("head")[0].appendChild(o)
            }).then(function() {
                u[p] = 0
            })), 0 !== (n = h[p]) && (n ? a.push(n[2]) : (t = new Promise(function(t, e) {
                n = h[p] = [t, e]
            }), a.push(n[2] = t), (i = document.createElement("script")).charset = "utf-8", i.timeout = 120, d.nc && i.setAttribute("nonce", d.nc), i.src = function(t) {
                return d.p + "23cbfd0d." + ({
                    3: "edit-font-dialog",
                    4: "edit-highlight-dialog"
                } [t] || t) + ".js"
            }(p), r = new Error, e = function(t) {
                var e, n, a;
                i.onerror = i.onload = null, clearTimeout(o), 0 !== (e = h[p]) && (e && (n = t && ("load" === t.type ? "missing" : t.type), a = t && t.target && t.target.src, r.message = "Loading chunk " + p + " failed.\n(" + n + ": " + a + ")", r.name = "ChunkLoadError", r.type = n, r.request = a, e[1](r)), h[p] = void 0)
            }, o = setTimeout(function() {
                e({
                    type: "timeout",
                    target: i
                })
            }, 12e4), i.onerror = i.onload = e, document.head.appendChild(i))), Promise.all(a)
        }, d.m = l, d.c = i, d.d = function(t, e, n) {
            d.o(t, e) || Object.defineProperty(t, e, {
                enumerable: !0,
                get: n
            })
        }, d.r = function(t) {
            "undefined" != typeof Symbol && Symbol.toStringTag && Object.defineProperty(t, Symbol.toStringTag, {
                value: "Module"
            }), Object.defineProperty(t, "__esModule", {
                value: !0
            })
        }, d.t = function(e, t) {
            var n, a;
            if (1 & t && (e = d(e)), 8 & t) return e;
            if (4 & t && "object" == typeof e && e && e.__esModule) return e;
            if (n = Object.create(null), d.r(n), Object.defineProperty(n, "default", {
                    enumerable: !0,
                    value: e
                }), 2 & t && "string" != typeof e)
                for (a in e) d.d(n, a, function(t) {
                    return e[t]
                }.bind(null, a));
            return n
        }, d.n = function(t) {
            var e = t && t.__esModule ? function() {
                return t.default
            } : function() {
                return t
            };
            return d.d(e, "a", e), e
        }, d.o = function(t, e) {
            return Object.prototype.hasOwnProperty.call(t, e)
        }, d.p = "", d.oe = function(t) {
            throw console.error(t), t
        }, n = (e = window.webpackJsonp = window.webpackJsonp || []).push.bind(e), e.push = t, e = e.slice(), a = 0; a < e.length; a++) t(e[a]);
    p = n, f.push([574, 1]), c()
}([, function(t, e, n) {
    "use strict";
    var a, r, i, o, s;
    n.d(e, "a", function() {
        return s
    }), a = n(0), r = n.n(a), i = n(7), o = {}, s = Object(i.a)({}, {
        _init: function() {},
        create: function() {
            var t = Object(i.a)(this);
            return t._init.apply(t, arguments), t
        },
        clear: function() {
            for (var t in o) delete o[t];
            return this
        },
        bind: function(t, e) {
            (o[t] || (o[t] = [])).unshift(e)
        },
        unbind: function(t) {
            delete o[t]
        },
        trigger: function(t) {
            for (var e = o[t] || [], n = [], a = e.length, i = void 0; a--;) void 0 !== (i = e[a].apply(this, [].slice.call(arguments, 1))) && n.push(i);
            return n.length ? r.a.when.apply(null, n) : this
        }
    })
}, function(t, e, n) {
    "use strict";

    function a() {
        return new Promise(function(e) {
            function n(t) {
                return d[u = t] = {}, e(u)
            }
            if (u = Object(o.a)("lang")) return n(u);
            l.a.getItem("lang").then(function(t) {
                return u = t || ((u = /[a-z]{2}/i.exec(navigator.language)) ? u[0] : p), n(u)
            }).catch(function() {
                return n(p)
            })
        })
    }

    function i(e) {
        e = e.toLowerCase(), r.a.set("locale", e);
        var t = n.p;
        return t && (t = t.replace(/\/+$/, ""), t += "/"), fetch(t + "locales/" + e + ".json", {
            credentials: "same-origin"
        }).then(function(t) {
            return t.json()
        }).then(function(t) {
            d[e] = t, c.info(e + " translation file was loaded")
        })
    }
    var r = n(4),
        o = n(74),
        s = n(23),
        l = n(129),
        c = new s.a("l10n"),
        p = "en",
        d = {},
        u = void 0;
    e.a = {
        init: function() {
            return new Promise(function(e) {
                a().then(function(t) {
                    i(t).then(e).catch(function() {
                        i(p).then(e).catch(e)
                    })
                })
            })
        },
        getCurrentLang: function() {
            return u
        },
        tr: function(t) {
            var e = d[u][t];
            return void 0 === e ? t : (1 < arguments.length && (e = function(t, e) {
                for (var n = e.length; n--;) t = t.replace(new RegExp("\\{" + n + "\\}", "g"), e[n]);
                return t
            }(e, Array.prototype.slice.call(arguments, 1))), e)
        }
    }
}, function(t, e, n) {
    "use strict";
    var i = n(5),
        r = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function(t) {
            return typeof t
        } : function(t) {
            return t && "function" == typeof Symbol && t.constructor === Symbol && t !== Symbol.prototype ? "symbol" : typeof t
        };
    e.a = function(t, e) {
        var n, a;
        if (t = document.createElementNS("http://www.w3.org/2000/svg", t), e && "object" === (void 0 === e ? "undefined" : r(e)))
            for (n = (a = Object.keys(e)).length; n--;) Object(i.a)(t, a[n], e[a[n]]);
        return t
    }
}, function(t, e, n) {
    "use strict";
    var s = n(21),
        a = n(74),
        i = n(23),
        r = new i.a("config"),
        l = window.config;
    e.a = {
        init: function() {
            var n = this;
            return new Promise(function(e) {
                var t = Object(a.a)("uuid");
                return t ? fetch(n.get("getCustomConfig", {
                    id: t
                }), {
                    credentials: "same-origin"
                }).then(function(t) {
                    return t.json()
                }).then(function(t) {
                    r.info("custom config was loaded"), Object.assign(l, t), e()
                }).catch(function(t) {
                    r.warn("custom config was not loaded due to:", t), e()
                }) : e()
            })
        },
        set: function(t, e) {
            var n, a = 0,
                i = l;
            for (n = (t = t.split(":")).length - 1; a <= n; a += 1) i[t[a]] || (i[t[a]] = {}), t[a + 1] || (i[t[a]] = e), i = i[t[a]];
            return this
        },
        get: function(t, e) {
            var n, a, i, r = 0,
                o = l;
            if (s.a)
                for (i = (t + "-mobile").split(":"), a = (t = t.split(":")).length - 1; r <= a; r += 1) {
                    if (!o[i[r]] && !o[t[r]]) return o[t[r]];
                    o = o[i[r]] || o[t[r]]
                } else
                    for (a = (t = t.split(":")).length - 1; r <= a; r += 1) {
                        if (!o[t[r]]) return o[t[r]];
                        o = o[t[r]]
                    }
            if (e)
                for (r = (n = Object.keys(e)).length; r--;) t = n[r], o = o.replace(new RegExp("#{" + t + "}", "g"), e[t]);
            return o
        }
    }
}, function(t, e, n) {
    "use strict";
    var a = n(90),
        i = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function(t) {
            return typeof t
        } : function(t) {
            return t && "function" == typeof Symbol && t.constructor === Symbol && t !== Symbol.prototype ? "symbol" : typeof t
        };
    e.a = function() {
        var t = arguments;
        if (2 === t.length) {
            if ("string" == typeof t[1]) return a.a.apply(this, t);
            if ("object" === i(t[1])) return a.c.apply(this, t)
        } else if (3 === t.length) return a.b.apply(this, t);
        throw new Error("something went wrong")
    }
}, function(t, e, n) {
    "use strict";
    var l = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function(t) {
        return typeof t
    } : function(t) {
        return t && "function" == typeof Symbol && t.constructor === Symbol && t !== Symbol.prototype ? "symbol" : typeof t
    };
    e.a = function t(e, n, a) {
        var i, r = Object.keys(n),
            o = void 0,
            s = void 0;
        for (e = e || {}, i = r.length; i--;) s = n[o = r[i]], a && "object" === (void 0 === s ? "undefined" : l(s)) && s.constructor !== Array ? e[o] = t(e[o], s, a) : e[o] = s;
        return e
    }
}, function(t, e, n) {
    "use strict";
    var i = n(6);
    e.a = function(t, e) {
        var n = Object.create(t, {
                _super: {
                    value: t
                }
            }),
            a = [].slice.apply(arguments).slice(2);
        return e && Object(i.a)(n, e), n.init && n.init.apply(n, a), n
    }
}, , function(t, e, n) {
    "use strict";
    var a, f, g, i, r, o, u, s, l, c, p, d, m, h, b, v, y, C, S, x, w;
    n.r(e), a = n(0), f = n.n(a), g = n(4), i = n(13), r = n(128), o = n(1), u = n(2), s = n(181), l = n(32), c = n(15), p = n(155), d = n(7), m = n(3), h = n(17), b = n(53), v = n(5), y = n(39), C = n(30), S = n(21), x = n(40), w = n(43), e.default = Object(d.a)(i.a, {
        SM: new r.a,
        disabled: !0,
        _init: function(t) {
            this._id = t || "ct" + Object(h.a)(), this.prop({
                Width: 50,
                Height: 50
            }), this.attr({
                opacity: 1,
                activated: !1,
                removeable: !0,
                copyable: !0,
                minWidth: 10,
                minHeight: 10,
                resizableX: !0,
                resizableY: !0,
                resizableXY: !0,
                movable: !0,
                selectable: !0
            })
        },
        fillMap: function() {
            this.fieldMap = l.a.factory(["Design:Name", "Design:Restrictions", "Layout:Anchor", "Layout:Dock", "Layout:Height", "Layout:Left", "Layout:Padding", "Layout:Top", "Layout:Width"])
        },
        create: function() {
            var t = this.createObject(this);
            return t._init.apply(t, arguments), t.prop("Name", t.formName()), this.SM.add(t), o.a.trigger("component-created", t), t
        },
        clone: function() {
            var t = i.a.clone.apply(this, arguments);
            return t.attr("activated", !1), t.deleteAttr("group"), t.deleteProp("GroupIndex"), t.collection = this.collection, t
        },
        _createCircle: function(t) {
            t = t || {};
            var e = g.a.get("circleButtonWidth"),
                n = g.a.get("circleButtonHeight"),
                a = f()(Object(m.a)("g", {
                    class: "fr-hidden"
                })),
                i = f()(Object(m.a)("rect")),
                r = Object(m.a)("use");
            return void 0 !== t.addGroupClass && a.addClass(t.addGroupClass), void 0 !== t.addBackClass && i.addClass(t.addBackClass), Object(b.a)(r, "xlink:href", "#d-button-circle"), Array.isArray(t.transform) && (Object(v.a)(r, "transform", "translate({0},{1})".format(t.transform[0], t.transform[1])), Object(v.a)(i[0], "transform", "translate({0},{1})".format(t.transform[0], t.transform[1]))), a.append(r, i), i.css("fill", "transparent"), i.attr({
                width: 2 * e,
                height: 2 * n
            }), a
        },
        resizingComponents: function() {
            var t, e, n, a, i = this,
                r = g.a.get("circleButtonWidth"),
                o = g.a.get("circleButtonHeight"),
                s = this.prop("Width"),
                l = this.prop("Height"),
                c = this.attr("resizableX"),
                p = this.attr("resizableY"),
                d = this.attr("resizableXY");
            if (this.resizingElements = [{
                    className: "nw-resize",
                    enabled: d,
                    coord: [0, 0],
                    vert: !0,
                    horz: !0,
                    hidden: !1
                }, {
                    className: "n-resize",
                    enabled: p,
                    coord: [s / 2, 0],
                    vert: !0,
                    horz: !1,
                    hidden: !1
                }, {
                    className: "ne-resize",
                    enabled: d,
                    coord: [s, 0],
                    vert: !0,
                    horz: !0,
                    hidden: !1
                }, {
                    className: "e-resize",
                    enabled: c,
                    coord: [s, l / 2],
                    vert: !1,
                    horz: !0,
                    hidden: !1
                }, {
                    className: "se-resize",
                    enabled: d,
                    coord: [s, l],
                    vert: !0,
                    horz: !0,
                    hidden: !1
                }, {
                    className: "s-resize",
                    enabled: p,
                    coord: [s / 2, l],
                    vert: !0,
                    horz: !1,
                    hidden: !1
                }, {
                    className: "sw-resize",
                    enabled: d,
                    coord: [0, l],
                    vert: !0,
                    horz: !0,
                    hidden: !1
                }, {
                    className: "w-resize",
                    enabled: c,
                    coord: [0, l / 2],
                    vert: !1,
                    horz: !0,
                    hidden: !1
                }], a = this.resizingElements.length, !this.$resizing)
                for (this.$resizing = f()(), n = 0; n < a; n += 1)(t = this._createCircle({
                    addGroupClass: "resizing-component",
                    addBackClass: this.resizingElements[n].className
                })).attr("title", u.a.tr("Zoom")), i.$resizing.push(t), i.$controlElements.append(t);
            for (n = 0; n < a; n += 1) e = this.resizingElements[n], t = this.$resizing[n], e.enabled ? Object(v.a)(t[0], "transform", "translate({0},{1})".format(e.coord[0] - r, e.coord[1] - o)) : t.remove();
            return this
        },
        eachResizingItem: function(t, e) {
            var n, a, i = this.$resizing || [],
                r = 0,
                o = i.length,
                s = this.resizingElements || [];
            for (t = t || function() {}, r = 0; r < o; r += 1) a = s[r], (n = i[r]) && (e || !a || !a.hidden && a.enabled) && t.call(this, n, a);
            return this
        },
        addUpControl: function(t) {
            var e;
            if (t && (t.$upClone = t.clone(), t.$upClone.$group = t, e = this.getPage())) return this.$upControls || (this.$upControls = f()(Object(m.a)("g", {
                class: "component component-up-controls"
            })), this.$upControls[0].component = this, e.$upControlElements.append(this.$upControls)), this.syncUpControlsPos(), this.$upControls.append(t.$upClone), this
        },
        removeUpControl: function(t, e) {
            if (t && t.$upClone) return t.$upClone.remove(), delete t.$upClone, !1 !== e && this.$upControls && this.$upControls.empty(), this
        },
        hideUpControl: function(t) {
            if (t && t.$upClone) return t.$upClone.addClass("fr-hidden"), this
        },
        showUpControl: function(t) {
            if (t && t.$upClone) return t.$upClone.removeClass("fr-hidden"), this
        },
        hideUpControls: function() {
            this.hideUpControl(this.$angleSlider), this.eachResizingItem(function(t) {
                this.hideUpControl(t)
            })
        },
        showUpControls: function() {
            this.showUpControl(this.$angleSlider), this.eachResizingItem(function(t) {
                this.showUpControl(t)
            })
        },
        syncUpControls: function() {
            this.removeUpControl(this.$angleSlider), this.addUpControl(this.$angleSlider), this.eachResizingItem(function(t, e) {
                this.removeUpControl(t, !1), e && !e.hidden && e.enabled && this.addUpControl(t)
            }, !0)
        },
        syncUpControlsPos: function() {
            var t, e, n = 0;
            if (this.$upControls && (t = this.getPage())) return (e = this.getContainer()).isBand() || e.isDialog() || (n = 1), Object(v.a)(this.$upControls[0], "transform", "translate({0}, {1})".format(this.absoluteLeft() + (t.attr("margin") || 0) + (t.attr("padding") || 0) + n, this.absoluteTop() + t.attr("padding") + n)), this
        },
        showAngleSlider: function() {
            this.$angleSlider && this.canModify() && this.canEdit() && (this.$angleSlider.removeClass("fr-hidden"), this.addUpControl(this.$angleSlider))
        },
        hideAngleSlider: function() {
            this.$angleSlider && (this.$angleSlider.addClass("fr-hidden"), this.removeUpControl(this.$angleSlider))
        },
        showCustomComponents: function() {},
        hideCustomComponents: function() {},
        hideResizingComponents: function() {
            this.eachResizingItem(function(t) {
                t.addClass("fr-hidden"), this.removeUpControl(t)
            })
        },
        showResizingComponents: function(t) {
            var n = this.prop("Height"),
                a = this.prop("Width");
            this.eachResizingItem(function(t) {
                t.removeClass("fr-hidden"), this.addUpControl(t)
            }), t && this.eachResizingItem(function(t, e) {
                e && (e.horz && !e.vert && n < g.a.get("minComponentHeightForResizingElements") || e.vert && !e.horz && a < g.a.get("minComponentWidthForResizingElements")) && (t.addClass("fr-hidden"), this.removeUpControl(t, !1))
            })
        },
        appendAngleSlider: function() {
            var t;
            return this.$angleSlider || (this.$angleSlider = this._createCircle({
                addGroupClass: "fr-angle-slider",
                transform: [0, -15]
            }), this.$angleSlider.css("cursor", "col-resize"), g.a.get("selectedPolylineStrokeWidth"), t = Object(m.a)("line", {
                x1: 6,
                y1: -10,
                x2: 6,
                y2: 10,
                stroke: g.a.get("colors")["angle-slider"],
                "stroke-width": 1
            }), this.$angleSlider.append(t), this.$controlElements.append(this.$angleSlider)), Object(v.a)(this.$angleSlider[0], "transform", "translate(" + (this.prop("Width") / 2 - 6) + ", " + -10 + ")"), this
        },
        appendEdges: function() {
            var i = this,
                t = g.a.get("polylineWidth"),
                e = this.prop("Width") - t,
                n = this.prop("Height") - t;
            return this.$edges || (this.$edges = f()(), f.a.each(["0 " + t + ", 0 0, " + t + " 0", "0 0, " + t + " 0, " + t + " " + t, "0 0, 0 " + t + ", " + t + " " + t, "0 " + t + ", " + t + " " + t + ", " + t + " 0"], function(t, e) {
                var n = f()(Object(m.a)("g")),
                    a = f()(Object(m.a)("polyline", {
                        fill: g.a.get("polylineFill"),
                        stroke: g.a.get("polylineStroke"),
                        "stroke-width": g.a.get("polylineStrokeWidth")
                    }));
                n.append(a), a.attr("points", e), i.$edges.push(n), i.$controlElements.append(n)
            })), Object(v.a)(this.$edges[0][0], "transform", "translate(0, 0)"), Object(v.a)(this.$edges[1][0], "transform", "translate(" + e + ", 0)"), Object(v.a)(this.$edges[2][0], "transform", "translate(0, " + n + ")"), Object(v.a)(this.$edges[3][0], "transform", "translate(" + e + ", " + n + ")"), this
        },
        appendBorders: function() {
            function t(t) {
                a.$borders[t] && (a.$borders[t].remove(), delete a.$borders[t])
            }
            var e, n, a = this,
                i = this.prop("Border.Lines"),
                r = this.prop("Border.Color"),
                o = this.prop("Border.Width"),
                s = this.prop("Border.Style"),
                l = this.prop("Border.Shadow"),
                c = this.prop("Border.ShadowWidth"),
                p = this.prop("Border.ShadowColor"),
                d = [],
                u = this.prop("Width"),
                h = this.prop("Height");
            return this.$borders || (this.$borders = {}), i && "None" !== i && (d = i.split(/,[\s]?/)), d.length ? f.a.each((e = {}, n = g.a.get("dasharrays"), (d.includes("All") || d.includes("Top")) && (e.top = {
                x1: .5,
                x2: u,
                y1: .5,
                y2: .5,
                color: a.prop("Border.TopLine.Color") || r,
                width: a.prop("Border.TopLine.Width") || o,
                dasharray: n[a.prop("Border.TopLine.Style") || s]
            }), (d.includes("All") || d.includes("Right")) && (e.right = {
                x1: u,
                x2: u,
                y1: .5,
                y2: h,
                color: a.prop("Border.RightLine.Color") || r,
                width: a.prop("Border.RightLine.Width") || o,
                dasharray: n[a.prop("Border.RightLine.Style") || s]
            }), (d.includes("All") || d.includes("Bottom")) && (e.bottom = {
                x1: .5,
                x2: u,
                y1: h,
                y2: h,
                color: a.prop("Border.BottomLine.Color") || r,
                width: a.prop("Border.BottomLine.Width") || o,
                dasharray: n[a.prop("Border.BottomLine.Style") || s]
            }), (d.includes("All") || d.includes("Left")) && (e.left = {
                x1: .5,
                x2: .5,
                y1: .5,
                y2: h,
                color: a.prop("Border.LeftLine.Color") || r,
                width: a.prop("Border.LeftLine.Width") || o,
                dasharray: n[a.prop("Border.LeftLine.Style") || s]
            }), e), function(t, e) {
                a.$borders[t] || (a.$borders[t] = f()(Object(m.a)("line")), a.$controlElements.before(a.$borders[t])), a.$borders[t].attr({
                    x1: e.x1,
                    y1: e.y1,
                    x2: e.x2,
                    y2: e.y2
                }).css({
                    stroke: e.color,
                    "stroke-width": e.width,
                    "stroke-dasharray": e.dasharray
                })
            }) : (f.a.each(this.$borders, function() {
                f()(this).remove()
            }), this.$borders = {}), d.includes("All") || (!d.includes("Left") && this.$borders.left && t("left"), !d.includes("Top") && this.$borders.top && t("top"), !d.includes("Right") && this.$borders.right && t("right"), !d.includes("Bottom") && this.$borders.bottom && t("bottom")), l ? (this.$shadow && this.$shadow.length || (this.$shadow = f()(Object(m.a)("rect"))), this.$shadow.attr({
                width: u,
                height: h,
                x: c,
                y: c
            }), this.$shadow.css("fill", p), "transparent" === this.prop("Fill.Color") && this.$moveBlock.css("fill", "#ffffff"), this.$g.prepend(this.$shadow)) : this.$shadow && this.$shadow.length && this.$shadow.remove(), this
        },
        appendPadding: function() {
            var t, e, n, a, i, r = this.prop("Width"),
                o = this.prop("Height");
            return this.attr("withPadding") ? (n = (i = this.prop("Padding")) ? (a = +(i = i.split(", ") || [])[0] || 0, t = +i[1] || 0, e = +i[2] || 0, +i[3] || 0) : (a = this.prop("Padding.Left") || 0, t = this.prop("Padding.Top") || 0, e = this.prop("Padding.Right") || 0, this.prop("Padding.Bottom") || 0), this.prop("Padding.Left", Math.round(a)), this.prop("Padding.Top", Math.round(t)), this.prop("Padding.Right", Math.round(e)), this.prop("Padding.Bottom", Math.round(n)), this.deleteProp("Padding"), (r = r - a - e) < 0 && (r = 0), (o = o - t - n) < 0 && (o = 0), Object(v.a)(this.body, "transform", "translate({0},{1})".format(a, t))) : Object(v.a)(this.$contentGroup[0], "transform", "translate(1,1)"), "TableObject" !== this.type && "MatrixObject" !== this.type || (r += 2, o += 2), this.attr("innerWidth", r), this.attr("innerHeight", o), this.$content && this.$content.attr({
                width: r,
                height: o
            }), this
        },
        setPosition: function(t, e, n) {
            var a = this.getContainer(),
                i = 0;
            return Object(w.a)(t) || (t = this.prop("Left")), Object(w.a)(e) || (e = this.prop("Top")), a && a.isBand() && (t < 0 && (t = 0), e + (i = a.prop("Top") - a.attr("padding")) < 0 && (e = -i)), this.prop("Left", t, n), this.prop("Top", e, n), this.attr("right", t + this.prop("Width")), this.attr("bottom", e + this.prop("Height")), Object(v.a)(this.g, "transform", "translate({0},{1})".format(t, e)), this.updateCoords && this.updateCoords(), this
        },
        isActivated: function() {
            return !!this.attr("activated")
        },
        isSelectable: function() {
            return !!this.attr("selectable")
        },
        activate: function() {
            var t, e, n, a;
            if (!this.isSelectable()) return this;
            if (!this.isActivated() && (Object(y.a)(this.g, "selected"), this.attr("activated", !0), this.$edges && (t = g.a.get("selectedPolylineStrokeWidth"), this.$edges.each(function() {
                    Object(v.a)(f()(this).find("polyline")[0], {
                        "stroke-width": t
                    })
                })), this.showResizingComponents(!0), this.showAngleSlider(), this.showCustomComponents(), e = this.prop("GroupIndex"), n = this.attr("group"), e && n))
                for (a = n.length; a--;) n[a] !== this && n[a].activate();
            return this
        },
        deactivate: function() {
            var t;
            return this.isActivated() && (Object(C.a)(this.g, "selected"), this.attr("activated", !1), this.$edges && (t = g.a.get("polylineStrokeWidth"), this.$edges.each(function() {
                Object(v.a)(f()(this).find("polyline")[0], {
                    "stroke-width": t
                })
            })), this.hideResizingComponents(), this.hideAngleSlider(), this.hideCustomComponents()), this
        },
        reactivate: function() {
            return this.deactivate().activate()
        },
        show: function() {
            Object(C.a)(this.g, "fr-hidden")
        },
        hide: function() {
            Object(y.a)(this.g, "fr-hidden")
        },
        createMoveBlock: function() {
            var t = g.a.get("rectButtonWidth"),
                e = g.a.get("rectButtonHeight"),
                n = f()(Object(m.a)("g", {
                    class: "move"
                })),
                a = f()(Object(m.a)("rect")),
                i = Object(m.a)("use");
            return Object(b.a)(i, "xlink:href", "#d-button-rect"), a.attr({
                width: 2 * t,
                height: 2 * e,
                x: -t / 2,
                y: -e / 2
            }), Object(v.a)(n[0], "transform", "translate(1," + -(e - 1) + ")"), n.append(i, a), n
        },
        renderContainer: function(t, e) {
            return this.$g || (this.g = Object(m.a)("g", {
                class: "component " + (this.type || "")
            }), this.$g = f()(this.g), this.controlElements = Object(m.a)("g"), this.$controlElements = f()(this.controlElements), this.body = Object(m.a)("g"), this.$body = f()(this.body), this.content = Object(m.a)("svg", {
                class: "move"
            }), this.$content = f()(this.content), this.$contentGroup = f()(Object(m.a)("g")), this.$sticks = f()(Object(m.a)("g")), this.$content.append(this.$contentGroup), this.$body.append(this.$content), this.$g.append(this.$body, this.$sticks, this.$controlElements), this.g.component = this), this.setPosition(t, e), this.$g
        },
        render: function(t) {
            var e = this.prop("Width"),
                n = this.prop("Height");
            return t = t || {}, this.renderContainer(t.left, t.top), this.touched && (this.$moveBlock || (this.$moveBlock = this.$workspace = f()(Object(m.a)("rect", {
                class: "move move-decor"
            })), this.$g.prepend(this.$moveBlock)), this.$moveBlock.attr({
                width: e,
                height: n
            }), this.$moveBlock.css({
                opacity: this.attr("opacity"),
                fill: this.getFillColor()
            }), this.appendBorders(), this.appendEdges(), this.resizingComponents(), this.appendPadding(), S.a || void 0 === this.prop("Angle") || this.appendAngleSlider(), this.syncUpControlsPos(), this.attr("bottom", this.prop("Top") + n), this.touched = !1, this.attr("removed") && (this.attr("removed", !1), this.show())), this.$g
        },
        remove: function() {
            return !!i.a.remove.call(this) && (this.deactivate(), this.attr("removed", !0), this.hide(), !0)
        },
        isComponent: function() {
            return !0
        },
        isDialogControl: function() {
            return !1
        },
        canBeRemoved: function() {
            return !0
        },
        absoluteLeft: function() {
            var t = this.getContainer(),
                e = this.prop("Left");
            return t && (t.isComponent() ? (t.getTable && (e += t.prop("Left"), t = t.getTable()), e += t.absoluteLeft()) : e += t.prop("Left")), e
        },
        absoluteTop: function() {
            var t = this.getContainer(),
                e = this.prop("Top");
            return t && (t.isComponent() ? (t.getTable && (e += t.prop("Top"), t = t.getTable()), e += t.absoluteTop()) : e += t.prop("Top") - t.attr("padding") || 0), e
        },
        getContextMenuTitle: function() {
            return this.toString()
        },
        getContextMenuItems: function() {
            function t(t) {
                return e.onChangeCM(t)
            }
            var e = this;
            return [{
                name: u.a.tr("ComponentMenu Hyperlink"),
                type: "default",
                closeAfter: !0,
                onClick: function() {
                    o.a.trigger("hyperlink-editor", e)
                }
            }, {
                type: "separator"
            }, {
                name: u.a.tr("ComponentMenu CanGrow"),
                type: "checkbox",
                curVal: e.prop("CanGrow"),
                prop: "CanGrow",
                onChange: t
            }, {
                name: u.a.tr("ComponentMenu CanShrink"),
                type: "checkbox",
                curVal: e.prop("CanShrink"),
                prop: "CanShrink",
                onChange: t
            }, {
                name: u.a.tr("ComponentMenu GrowToBottom"),
                type: "checkbox",
                curVal: e.prop("GrowToBottom"),
                prop: "GrowToBottom",
                onChange: t
            }, {
                type: "separator"
            }, {
                name: u.a.tr("Menu Edit Cut"),
                type: "default",
                closeAfter: !0,
                shortcut: "Ctrl + X",
                onClick: function() {
                    o.a.trigger("cut", f()(e))
                }
            }, {
                name: u.a.tr("Menu Edit Copy"),
                type: "default",
                closeAfter: !0,
                shortcut: "Ctrl + C",
                onClick: function() {
                    o.a.trigger("copy", f()(e))
                }
            }, {
                name: u.a.tr("Menu Edit Paste"),
                type: "default",
                disabled: !window.DSG.currentReport.getCurrentPage().buffer.length,
                closeAfter: !0,
                shortcut: "Ctrl + V",
                onClick: function() {
                    setTimeout(function() {
                        o.a.trigger("paste")
                    }, 100)
                }
            }, {
                name: u.a.tr("Menu Edit Delete"),
                type: "default",
                disabled: !e.canBeRemoved(),
                closeAfter: !0,
                shortcut: "Del",
                onClick: function() {
                    o.a.trigger("remove", e)
                }
            }, {
                type: "separator"
            }, {
                name: u.a.tr("Layout BringToFront"),
                type: "default",
                closeAfter: !0,
                onClick: function() {
                    o.a.trigger("bring-to-front", e)
                }
            }, {
                name: u.a.tr("Layout SendToBack"),
                type: "default",
                closeAfter: !0,
                onClick: function() {
                    o.a.trigger("send-to-back", e)
                }
            }]
        },
        rightClick: function(t) {
            return new s.a(t, this.getContextMenuTitle(), this.getContextMenuItems(), this.getContextMenuEventName()), !1
        },
        createAspectRatio: function() {
            this.attr("aspectRatio", this.prop("Width") / this.prop("Height"))
        },
        calculateARSize: function(t, e) {
            var n = this.attr("minWidth"),
                a = this.attr("minHeight"),
                i = this.attr("aspectRatio");
            return i < 1 ? t = e * i : e = t / i, n < t && a < e ? (this.prop("Width", t), this.prop("Height", e), [t, e]) : [n, a]
        },
        rotating: function() {
            this.render()
        },
        rotatingEnd: function(t, e) {
            var n = e.original.angle;
            this.prop("Angle") !== n && (c.a.push({
                context: this,
                func: function(t) {
                    this.prop("Angle", t), this.render()
                },
                undoData: [n],
                redoData: [this.prop("Angle")]
            }), o.a.trigger("update-menu", this))
        },
        resizingStart: function() {
            this.attr("withAspectRatio") && this.createAspectRatio()
        },
        getResizing: function(e) {
            function t(t) {
                return t = ".{0}-resize".format(t), e.is(t) || e.parents(t).length
            }
            var n, l = this.attr("minWidth"),
                c = this.attr("minHeight"),
                p = this.attr("withAspectRatio");
            return e.is(".resizing-component") || e.parents(".resizing-component").length ? t("nw") ? (n = function(t, e) {
                if (this.canResizeXY()) {
                    var n = this.prop("Left"),
                        a = this.prop("Top"),
                        i = Object(x.a)([t - n, e - a]),
                        r = this.prop("Width"),
                        o = this.prop("Height"),
                        s = [];
                    n < t ? (t = n + i[0], s[0] = r - i[0]) : (t = n - i[0], s[0] = r + i[0]), a < e ? (e = a + i[1], s[1] = o - i[1]) : (e = a - i[1], s[1] = o + i[1]), p ? (s = this.calculateARSize(s[0], s[1]))[0] > l && s[1] > c && this.setPosition(n + r - s[0], a + o - s[1]) : (s[0] >= l && (this.setPosition(t), 0 === Math.round(this.prop("Left") - t) && this.prop("Width", s[0])), s[1] >= c && (this.setPosition(null, e), 0 === Math.round(this.prop("Top") - e) && this.prop("Height", s[1])))
                }
            }).dir = "nw" : t("n") ? (n = function(t, e) {
                if (this.canResizeY()) {
                    var n, a = this.prop("Top"),
                        i = Object(x.a)([null, e - a]);
                    n = a < e ? (e = a + i[1], this.prop("Height") - i[1]) : (e = a - i[1], this.prop("Height") + i[1]), c <= n && (this.setPosition(null, e), 0 === Math.round(this.prop("Top") - e) && this.prop("Height", n))
                }
            }).dir = "n" : t("ne") ? (n = function(t, e) {
                if (this.canResizeXY()) {
                    var n, a = this.attr("right"),
                        i = this.prop("Top"),
                        r = Object(x.a)([t - a, e - i]),
                        o = this.prop("Height");
                    n = i < e ? (e = i + r[1], o - r[1]) : (e = i - r[1], o + r[1]), t = a < t ? a + r[0] : a - r[0], t -= this.prop("Left"), p ? (n = this.calculateARSize(t, n)[1], c < n && this.setPosition(null, i + o - n)) : (c <= n && (this.setPosition(null, e), 0 === Math.round(this.prop("Top") - e) && this.prop("Height", n)), l < t && this.prop("Width", t))
                }
            }).dir = "ne" : t("e") ? (n = function(t) {
                if (this.canResizeX()) {
                    var e = this.attr("right"),
                        n = Object(x.a)([t - e]);
                    t = e < t ? e + n[0] : e - n[0], t -= this.prop("Left"), l < t && this.prop("Width", t)
                }
            }).dir = "e" : t("se") ? (n = function(t, e) {
                if (this.canResizeXY()) {
                    var n = this.attr("right"),
                        a = this.attr("bottom"),
                        i = Object(x.a)([t - n, e - a]);
                    t = n < t ? n + i[0] : n - i[0], t -= this.prop("Left"), e = a < e ? a + i[1] : a - i[1], e -= this.prop("Top"), p ? this.calculateARSize(t, e) : (this.prop("Width", t < l ? l : t), this.prop("Height", e < c ? c : e))
                }
            }).dir = "se" : t("s") ? (n = function(t, e) {
                if (this.canResizeY()) {
                    var n = this.attr("bottom"),
                        a = Object(x.a)([null, e - n]);
                    e = n < e ? n + a[1] : n - a[1], e -= this.prop("Top"), this.prop("Height", e < c ? c : e)
                }
            }).dir = "s" : t("sw") ? (n = function(t, e) {
                if (this.canResizeXY()) {
                    var n, a = this.prop("Left"),
                        i = this.attr("bottom"),
                        r = this.prop("Width"),
                        o = Object(x.a)([t - a, e - i]);
                    n = a < t ? (t = a + o[0], r - o[0]) : (t = a - o[0], r + o[0]), e = i < e ? i + o[1] : i - o[1], e -= this.prop("Top"), p ? (n = this.calculateARSize(n, e)[0], l < n && this.setPosition(a + r - n)) : (l < n && (this.setPosition(t), 0 === Math.round(this.prop("Left") - t) && this.prop("Width", n)), this.prop("Height", e < c ? c : e))
                }
            }).dir = "sw" : t("w") && ((n = function(t) {
                if (this.canResizeX()) {
                    var e, n = this.prop("Left"),
                        a = Object(x.a)([t - n]);
                    e = n < t ? (n += a[0], this.prop("Width") - a[0]) : (n -= a[0], this.prop("Width") + a[0]), l < e && (this.setPosition(n), 0 === Math.round(this.prop("Left") - n) && this.prop("Width", e))
                }
            }).dir = "w") : (e.is(".resizing-line") || e.parents(".resizing-line").length) && t("se") && ((n = function(t, e) {
                this.canResizeX() && (t -= this.prop("Left"), this.prop("Width", t)), this.canResizeY() && (e -= this.prop("Top"), this.prop("Height", e))
            }).dir = "se"), n
        },
        fillPropsVCL: function() {
            var t, e, n;
            i.a.fillPropsVCL.apply(this, arguments), this.prop("Frame.Width") && (this.prop("Border.Width", this.prop("Frame.Width")), this.deleteProp("Frame.Width")), (t = this.prop("Frame.Typ")) && ((e = p.a.borderLinesToNET(t)) && this.prop("Border.Lines", e), this.deleteProp("Frame.Typ")), (n = this.prop("Frame.Style")) && ((n = p.a.borderStyleToNET(n)) && this.prop("Border.Style", n), this.deleteProp("Frame.Style")), this.prop("Frame.DropShadow") && (this.prop("Border.Shadow", this.prop("Frame.DropShadow")), this.prop("Border.ShadowWidth", this.prop("Frame.ShadowWidth")), this.prop("Border.ShadowColor", this.prop("Frame.ShadowColor")), this.deleteProp("Frame.DropShadow"), this.deleteProp("Frame.ShadowWidth"), this.deleteProp("Frame.ShadowColor"))
        },
        toXMLVCL: function(t) {
            var n = this;
            return new Promise(function(e) {
                i.a.toXMLVCL.call(n, t).then(function(t) {
                    return t = f()(t), e(t[0])
                })
            })
        }
    })
}, function(t, e, n) {
    "use strict";
    var a, g, m, i, r, l, o, s, c, p, d, u, b, h, f, v, y, C, S, x;
    n.r(e), a = n(0), g = n.n(a), m = n(4), i = n(2), r = n(128), l = n(36), o = n(85), s = n(180), c = n(1), p = n(181), d = n(7), u = n(17), b = n(3), h = n(21), f = n(53), v = n(5), y = n(39), C = n(30), S = n(67), x = function() {
        return S.a.apply(null, arguments).w
    }, e.default = Object(d.a)(l.a, {
        SM: new r.a,
        disabled: !0,
        _init: function() {
            this._id = "b" + Object(u.a)(), this.prop({
                Width: 718.2,
                Height: 170.08
            }), this.attr({
                title: "title",
                threshold: 0,
                "title-font-size": "1em",
                "title-font-family": window.DSG.head.$main.css("font-family"),
                "title-font-weight": "normal",
                "title-angle": "-90",
                "title-color": "black",
                "fill-blanket": "rgb(90, 90, 90)",
                removeable: !0,
                activated: !1,
                margin: 0,
                padding: 0,
                "margin-left": 0,
                layer_defect: 0,
                placeAboveParent: !1,
                "separator.color": m.a.get("colors")["default-band-separator"],
                "separator.style": "",
                "separator.width": m.a.get("band-indent-top") || 2,
                "separator.opacity": m.a.get("band-indent-opacity") || 1
            })
        },
        create: function() {
            var t = this.createObject(this);
            return t._init.apply(t, arguments), t.prop("Name", t.formName()), t.unparsed = [], t.components = o.a.create(t), t.bands = s.a.create(t), this.SM.add(t), t
        },
        getFillTitleColor: function() {
            return "#91D4FF"
        },
        canHaveChildren: function(t) {
            return ["ChildBand"].includes(t.type || t)
        },
        canBeSorted: function() {
            return !1
        },
        has: function(t) {
            return 0 < this.bands.findAmongChildrenBy({
                type: t.type || t
            }).length
        },
        canBeAdded: function(t) {
            return 0 === t.bands.findAmongChildrenBy({
                type: this.type
            }).length
        },
        canBeRemoved: function() {
            var t = this.collection.getMainCollection().container;
            return !(1 === t.bands.count() && t.bands.first() === this)
        },
        applyRule: function() {
            return !1
        },
        moveUp: function() {
            var t, e, n;
            this.canBeSorted() && (e = (t = this.bands.container._parent).bands.prevBefore(this)) && e.canBeSorted() && (n = t.bands.findAmongChildren(this._id, !0)) && (n = n[1], t.bands.remove(e, !1), t.addBand(e, n), t.touch().render(), t.updateExts())
        },
        moveDown: function() {
            var t, e, n;
            this.canBeSorted() && (e = (t = this.bands.container._parent).bands.nextAfter(this)) && e.canBeSorted() && (n = t.bands.findAmongChildren(this._id, !0)) && (n = n[1], t.bands.remove(e, !1), t.addBand(e, n), t.touch().render(), t.updateExts())
        },
        addResizingBandComponent: m.a.get("resize-bands") ? function() {
            var t, e, n = g()(Object(b.a)("g")),
                a = g()(Object(b.a)("rect", {
                    class: "resizing-band s-resize"
                }));
            return h.a ? (t = Object(b.a)("use"), Object(f.a)(t, "xlink:href", "#d-resizing-band"), n.append(t, a)) : n.append(a), this.$resize.append(n), e = {
                width: m.a.get("resizingBandBlockWidth"),
                height: m.a.get("resizingBandBlockHeight")
            }, a.attr({
                width: e.width,
                height: 2 * e.height,
                x: 0,
                y: -e.height / 2
            }), this.addResizingBandComponent = function() {
                return Object(v.a)(n[0], "transform", "translate(-79, " + Math.abs((this.prop("Height") || e.height) - e.height) + ")"), this
            }, this.addResizingBandComponent()
        } : function() {},
        separate: function() {
            var t, e = this.prop("Width"),
                n = this.prop("Columns.Count"),
                a = this.prop("Columns.Width"),
                i = this.getPage(),
                r = i.prop("Columns.Count"),
                o = i.prop("Columns.Width");
            return void 0 === n && void 0 === a ? null : (a = void 0 !== r && (!n || n in [0, 1]) ? (n = r, o) : a || e / n, this.$blanket || (this.$blanket = g()(Object(b.a)("rect"))), n && 1 < n ? ((t = e - a) < 0 && (t = 0), this.$blanket.attr({
                width: t,
                height: this.prop("Height"),
                x: a,
                y: 0
            }), this.$blanket.css({
                fill: this.attr("fill-blanket")
            }), this.$background.append(this.$blanket)) : (this.prop("Width", e), this.$blanket.remove()), this)
        },
        appendSeparator: m.a.get("resize-bands") ? function() {
            var t, e, n = [{
                    color: this.attr("separator.color"),
                    width: this.attr("separator.width"),
                    dasharray: this.attr("separator.style"),
                    opacity: this.attr("separator.opacity")
                }],
                a = 0,
                i = n.length;
            for (this.$borders || (this.$borders = []); a < i; a += 1) t = n[a], this.$borders[a] || (this.$borders[a] = g()(Object(b.a)("g"))), (e = g()("line:first", this.$borders[a])).length || (e = g()(Object(b.a)("line", {
                class: "band-separator"
            }))), this.$borders[a].append(e), Object(v.a)(this.$borders[a][0], "transform", "translate(0," + (this.prop("Height") + t.width / 2 - t.width) + ")"), e.attr({
                x1: 0,
                y1: t.width,
                x2: this.prop("Width") + this.attr("padding"),
                y2: t.width
            }), e.css({
                stroke: t.color,
                "stroke-width": t.width,
                "stroke-dasharray": t.dasharray,
                opacity: t.opacity
            }), this.$resize.append(this.$borders[a]);
            return this
        } : function() {},
        getBandLayer: function() {
            for (var t = 0, e = this.collection;
                "ReportPage" !== e.container.type;) t += 1, e = e.container.collection;
            return t
        },
        getBandMarginLeft: function() {
            return (this.getBandLayer() + this.attr("layer_defect")) * (this.attr("padding") / 2)
        },
        appendTitle: function() {
            var l, c, p, d, u, h, f;
            return m.a.get("show-band-title") ? (l = g()(Object(b.a)("svg")), c = g()(Object(b.a)("g")), p = g()(Object(b.a)("g")), d = g()(Object(b.a)("text")), u = g()(Object(b.a)("rect")), h = g()(Object(b.a)("line")), f = g()(Object(b.a)("line")), p.append(d), c.append(p), l.append(c), this.$title.append(h, l), this.$g.prepend(u, this.$title), this.appendTitle = function() {
                var t, e = this.attr("separator.color"),
                    n = 0,
                    a = this.attr("separator.style"),
                    i = this.prop("Height"),
                    r = this.attr("padding"),
                    o = this.attr("margin") + r,
                    s = void 0;
                return m.a.get("resize-bands") && (n = this.attr("separator.width"), i += m.a.get("band-indent-top") || 0), t = this.getBandMarginLeft(), this.attr("margin-left", t), (s = o - t) < 0 && (s = 0), u.attr({
                    width: s,
                    height: i + this.bands.getAllBandsHeight(),
                    transform: "translate(" + t + ", 0)"
                }).css("fill", this.getFillTitleColor()), d.css({
                    "font-size": this.attr("title-font-size"),
                    "font-family": this.attr("title-font-family"),
                    "font-weight": this.attr("title-font-weight"),
                    "text-anchor": "middle"
                }), h.attr({
                    x1: 0,
                    y1: i - n / 2,
                    x2: s,
                    y2: i - n / 2
                }).css({
                    stroke: e,
                    "stroke-width": n,
                    "stroke-dasharray": a
                }), f.attr({
                    x1: 0,
                    y1: 0,
                    x2: 0,
                    y2: i + this.bands.getBottomBandsHeight(),
                    class: "band-separator"
                }).css({
                    stroke: e,
                    "stroke-width": 2,
                    "stroke-dasharray": "8 5"
                }), d.text(this.toString()), Object(v.a)(d[0], {
                    x: 0,
                    y: 0,
                    fill: this.attr("title-color")
                }), l.attr({
                    width: s,
                    height: i + this.bands.getAllBandsHeight()
                }), x(this.toString(), {
                    "font-size": this.attr("title-font-size"),
                    "font-family": this.attr("title-font-family"),
                    "font-weight": this.attr("title-font-weight")
                }) < i ? (Object(v.a)(c[0], "transform", "translate(20," + i / 2 + ")"), Object(v.a)(p[0], "transform", "rotate(" + this.attr("title-angle") + ")")) : (p.removeAttr("transform"), Object(v.a)(c[0], "transform", "translate(0," + i / 2 + ")"), d.css("text-anchor", "start")), Object(v.a)(this.$title[0], "transform", "translate(" + t + ", " + this.bands.getTopBandsHeight() + ")"), 0 !== t && this.$title.append(f), this
            }, this.appendTitle()) : (this.appendTitle = function() {}, null)
        },
        setPosition: function(t, e) {
            return Object(v.a)(this.g, "transform", "translate(" + t + ", " + e + ")"), this
        },
        put: function(t) {
            return t.isComponent() ? this.components.add(t.g.component) : t.isBand() && l.a.put.apply(this, arguments), c.a.trigger("put-element", t), this
        },
        activate: function() {
            return this.attr("activated") || (Object(y.a)(this.g, "selected"), this.attr("separator.color", m.a.get("colors")["selected-band-separator"]), this.appendSeparator(), this.appendTitle(), this.attr("activated", !0)), this
        },
        deactivate: function() {
            return this.attr("activated") && (Object(C.a)(this.g, "selected"), this.attr("separator.color", m.a.get("colors")["default-band-separator"]), this.appendSeparator(), this.appendTitle(), this.attr("activated", !1)), this
        },
        updateThreshold: function() {
            var e, n = this,
                a = this.prop("Top") || 0;
            return this.attr("threshold", 0), this.components.eachEntity(function(t) {
                (e = t.attr("bottom") + a) > n.attr("threshold") && n.attr("threshold", e), t.setPosition()
            }), this.touch(), this
        },
        updateComponentsCoords: function() {
            this.components.everyEntity(function(t) {
                t.isActivated() && t.syncUpControlsPos()
            })
        },
        adjust: function() {
            var t, e = this.bands.getHeightTo(this, this.attr("padding"));
            return this.prop("Top", e), m.a.get("resize-bands") ? (this.updateThreshold(), (t = this.attr("threshold")) > e + this.prop("Height") && this.prop("Height", t - e)) : this.touch(), this
        },
        balance: function(t) {
            var e, n = this.getPage();
            return t = void 0 !== t ? t : this.prop("Height"), this.adjust(), this.render(), t !== (e = this.prop("Height")) && void 0 !== e && (n.bands.after(this, function(t) {
                t.updateThreshold()
            }), n.balance()), this
        },
        renderContainer: function() {
            return this.$g || (this.g = Object(b.a)("g", {
                class: "band"
            }), this.$g = g()(this.g), (this.g.band = this).$body = g()(Object(b.a)("g")), this.$bandsTop = g()(Object(b.a)("g")), this.$bandsBottom = g()(Object(b.a)("g")), this.$title = g()(Object(b.a)("g")), this.$resize = m.a.get("resize-bands") ? g()(Object(b.a)("g", {
                class: "resizing-band s-resize"
            })) : g()(), this.$components = g()(Object(b.a)("g")), this.$background = g()(Object(b.a)("g")), this.$rect = g()(Object(b.a)("rect")), this.$net = g()(Object(b.a)("rect", {
                class: "fr-net"
            })), this.$background.append(this.$rect, this.$net), this.$workspace = this.$background, this.$body.append(this.$background, this.$resize, this.$components), this.$g.append(this.$title, this.$body, this.$bandsTop, this.$bandsBottom)), this.$g
        },
        render: function() {
            var t, e, n, a, i;
            return this.touched && (this.renderContainer(), t = this.attr("padding"), e = this.attr("margin"), a = this.prop("Height"), n = this.prop("Width"), Object(v.a)(this.$background[0], "transform", "translate(" + t + ", 0)"), Object(v.a)(this.$components[0], "transform", "translate(" + t + ", 0)"), Object(v.a)(this.$body[0], "transform", "translate(" + e + ", " + this.bands.getTopBandsHeight() + ")"), Object(v.a)(this.$bandsTop[0], "transform", "translate(0, 0)"), Object(v.a)(this.$bandsBottom[0], "transform", "translate(0, " + (m.a.get("resize-bands") ? a + this.attr("separator.width") : 0) + ")"), this.balanceChildBands(), this.$rect.attr({
                width: n,
                height: a
            }), this.$net.attr({
                width: n,
                height: a
            }), this.$rect.css("fill", this.getFillColor()), (i = this.getPage()).report.attr("grid") ? this.$net.css("fill", "url(#" + i.netId + ")") : this.$net.css("fill", ""), this.appendSeparator(), this.addResizingBandComponent(), this.appendTitle(), this.separate(), this.touched = !1, l.a.render.call(this)), this.$g
        },
        balanceChildBands: function() {
            var t, e = 0,
                n = 0;
            m.a.get("resize-bands") && (n = m.a.get("band-indent-top") || 0), t = function() {
                g()(this).hasClass("fr-hidden") || (Object(v.a)(this.band.g, "transform", "translate(" + this.band.prop("Left") + ", " + e + ")"), e += this.band.prop("Height") + n)
            }, this.$bandsTop.children(".band").each(t), e = 0, this.$bandsBottom.children(".band").each(t)
        },
        toXMLNET: function(o) {
            var s = this;
            return new Promise(function(r) {
                l.a.toXMLNET.call(s, o).then(function(e) {
                    var n, t, a, i;
                    e = g()(e), o = Object.assign({
                        parentNode: e[0]
                    }, o), e.removeAttr("Top"), "0" === e.attr("Left") && e.removeAttr("Left"), n = [], s.components.eachEntity(function(t) {
                        n.push(t.toXMLNET(o))
                    }), t = Promise.all(n).then(function(t) {
                        t.forEach(function(t) {
                            e.append(t)
                        })
                    }), a = [], 0 < s.bands.count() && s.bands.eachEntity(function(t) {
                        a.push(t.toXMLNET(o))
                    }), i = Promise.all(a).then(function(t) {
                        t.forEach(function(t) {
                            t && e.append(t)
                        })
                    }), Promise.all([t, i]).then(function() {
                        return g.a.each(s.unparsed, function() {
                            e.append(this)
                        }), r(e[0])
                    })
                })
            })
        },
        toXMLVCL: function(o) {
            var s = this;
            return new Promise(function(r) {
                l.a.toXMLVCL.call(s, o).then(function(e) {
                    var n, t, a, i;
                    e = g()(e), o = Object.assign({
                        parentNode: e[0]
                    }, o), n = [], s.components.eachEntity(function(t) {
                        n.push(t.toXMLVCL(o))
                    }), t = Promise.all(n).then(function(t) {
                        t.forEach(function(t) {
                            t && e.append(t)
                        })
                    }), a = [], 0 < s.bands.count() && s.bands.eachEntity(function(t) {
                        a.push(t.toXMLVCL(o))
                    }), i = Promise.all(a).then(function(t) {
                        t.forEach(function(t) {
                            t && e.append(t)
                        })
                    }), Promise.all([t, i]).then(function() {
                        return g.a.each(s.unparsed, function() {
                            e.append(this)
                        }), r(e[0])
                    })
                })
            })
        },
        isBand: function() {
            return !0
        },
        getContextMenuTitle: function() {
            return this.toString()
        },
        getContextMenuItems: function() {
            function t(t) {
                return e.onChangeCM(t)
            }
            var e = this;
            return [{
                name: i.a.tr("Band AddChildBand"),
                type: "default",
                closeAfter: !0,
                disabled: e.has("ChildBand"),
                onClick: function() {
                    c.a.trigger("add-band", "ChildBand", e)
                }
            }, {
                name: i.a.tr("ComponentMenu Hyperlink"),
                type: "default",
                closeAfter: !0,
                onClick: function() {
                    c.a.trigger("hyperlink-editor", e)
                }
            }, {
                type: "separator"
            }, {
                name: i.a.tr("ComponentMenu CanGrow"),
                type: "checkbox",
                curVal: e.prop("CanGrow"),
                prop: "CanGrow",
                onChange: t
            }, {
                name: i.a.tr("ComponentMenu CanShrink"),
                type: "checkbox",
                curVal: e.prop("CanShrink"),
                prop: "CanShrink",
                onChange: t
            }, {
                name: i.a.tr("ComponentMenu CanBreak"),
                type: "checkbox",
                curVal: e.prop("CanBreak"),
                prop: "CanBreak",
                onChange: t
            }, {
                type: "separator"
            }, {
                name: i.a.tr("Menu Edit Paste"),
                type: "default",
                disabled: !window.DSG.currentReport.getCurrentPage().buffer.length,
                closeAfter: !0,
                shortcut: "Ctrl + V",
                onClick: function() {
                    setTimeout(function() {
                        c.a.trigger("paste")
                    }, 100)
                }
            }, {
                name: i.a.tr("Menu Edit Delete"),
                type: "default",
                disabled: !e.canBeRemoved(),
                closeAfter: !0,
                shortcut: "Del",
                onClick: function() {
                    c.a.trigger("remove", e)
                }
            }]
        },
        rightClick: function(t) {
            return new p.a(t, this.getContextMenuTitle(), this.getContextMenuItems(), this.getContextMenuEventName()), !1
        },
        fillPropsNET: function(t) {
            return l.a.fillPropsNET.apply(this, arguments), void 0 === t.attr("Height") && this.prop("Height", 0), this
        }
    })
}, function(t, e, n) {
    "use strict";
    var a = n(0),
        i = n.n(a),
        r = n(15),
        o = n(32),
        s = n(1),
        l = n(6),
        c = function() {
            var t = window.DSG.currentReport;
            return c = function() {
                return t
            }, t
        };
    Object(l.a)(o.a.data, {
        Appearance: {
            label: "Properties Appearance",
            propName: "Appearance",
            fields: {
                Appearance: {
                    label: "Appearance",
                    type: "select",
                    collection: ["Normal", "Button"]
                },
                Angle: {
                    label: "Angle",
                    type: "number"
                },
                AsBitmap: {
                    label: "AsBitmap",
                    type: "checkbox"
                },
                BackColor: {
                    label: "BackColor",
                    type: "color"
                },
                ForeColor: {
                    label: "ForeColor",
                    type: "color"
                },
                Checked: {
                    label: "Checked",
                    type: "checkbox",
                    afterSetValue: function(t, e) {
                        this.canHaveProp("Appearance:CheckState") && (this.prop("CheckState", e ? "Checked" : "Unchecked"), this.render(), s.a.trigger("update-properties-panel", this))
                    }
                },
                CheckState: {
                    label: "CheckState",
                    type: "select",
                    collection: ["Checked", "Indeterminate", "Unchecked"]
                },
                Image: {
                    label: "Image",
                    type: "file"
                },
                CheckAlign: {
                    label: "CheckAlign",
                    type: "select",
                    collection: ["TopLeft", "TopCenter", "TopRight", "MiddleLeft", "MiddleCenter", "MiddleRight", "BottomLeft", "BottomCenter", "BottomRight"]
                },
                TextAlign: {
                    label: "TextAlign",
                    type: "select",
                    collection: ["TopLeft", "TopCenter", "TopRight", "MiddleLeft", "MiddleCenter", "MiddleRight", "BottomLeft", "BottomCenter", "BottomRight"]
                },
                ImageAlign: {
                    label: "ImageAlign",
                    type: "select",
                    collection: ["TopLeft", "TopCenter", "TopRight", "MiddleLeft", "MiddleCenter", "MiddleRight", "BottomLeft", "BottomCenter", "BottomRight"]
                },
                TextImageRelation: {
                    label: "TextImageRelation",
                    type: "select",
                    collection: ["Overlay", "ImageAboveText", "TextAboveImage", "ImageBeforeText", "TextBeforeImage"]
                },
                ThreeState: {
                    label: "ThreeState",
                    type: "checkbox"
                },
                DrawMode: {
                    label: "DrawMode",
                    type: "select",
                    collection: ["Normal", "OwnerDrawFixed", "OwnerDrawVariable"]
                },
                ItemHeight: {
                    label: "ItemHeight",
                    type: "number"
                },
                DropDownHeight: {
                    label: "DropDownHeight",
                    type: "number"
                },
                DropDownStyle: {
                    label: "DropDownStyle",
                    type: "select",
                    collection: ["Simple", "DropDown", "DropDownList"]
                },
                DropDownWidth: {
                    label: "DropDownWidth",
                    type: "number"
                },
                MaxDropDownItems: {
                    label: "MaxDropDownItems",
                    type: "number"
                },
                Cursor: {
                    label: "Cursor",
                    type: "select",
                    collection: ["AppStarting", "Arrow", "Cross", "Default", "IBeam", "No", "SizeAll", "SizeNESW", "SizeNS", "SizeNWSE", "SizeWE", "UpArrow", "WaitCursor", "Help", "HSplit", "VSplit", "NoMove2D", "NoMoveHoriz", "NoMoveVert", "PanEast", "PanNE", "PanNorth", "PanNW", "PanSE", "PanSouth", "PanSW", "PanWest", "Hand"]
                },
                Curve: {
                    label: "Curve",
                    type: "number"
                },
                EvenStyle: {
                    label: "EvenStyle",
                    type: "select",
                    collection: function() {
                        var t = c().styles,
                            e = [""];
                        return t.eachEntity(function(t) {
                            e.push(t.prop("Name"))
                        }), e
                    }
                },
                EvenStylePriority: {
                    label: "EvenStylePriority",
                    type: "select",
                    collection: ["UseFill", "UseAll"]
                },
                FirstTabOffset: {
                    label: "FirstTabOffset",
                    type: "number"
                },
                FormBorderStyle: {
                    label: "FormBorderStyle",
                    type: "select",
                    collection: ["None", "FixedSingle", "Fixed3D", "FixedDialog", "Sizable", "FixedToolWindow", "SizableToolWindow"]
                },
                RightToLeft: {
                    label: "RightToLeft",
                    type: "select",
                    collection: ["No", "Yes", "Inherit"]
                },
                FontWidthRatio: {
                    label: "FontWidthRatio",
                    type: "number"
                },
                Grayscale: {
                    type: "checkbox",
                    label: "Grayscale"
                },
                HoverStyle: {
                    type: "text",
                    label: "HoverStyle"
                },
                Tile: {
                    type: "checkbox",
                    label: "Tile"
                },
                Transparency: {
                    type: "number",
                    label: "Transparency",
                    attrs: {
                        min: 0,
                        max: 1,
                        step: .1
                    }
                },
                TransparentColor: {
                    type: "color",
                    label: "TransparentColor"
                },
                LineHeight: {
                    type: "unit",
                    label: "LineHeight"
                },
                ParagraphOffset: {
                    type: "unit",
                    label: "ParagraphOffset"
                },
                LinearPointer: {
                    label: "Pointer",
                    fields: {
                        "Pointer.BorderColor": {
                            label: "BorderColor",
                            type: "color"
                        },
                        "Pointer.Color": {
                            label: "Color",
                            type: "color"
                        }
                    }
                },
                ProgressPointer: {
                    label: "Pointer",
                    fields: {
                        "Pointer.BorderColor": {
                            label: "BorderColor",
                            type: "color"
                        },
                        "Pointer.Color": {
                            label: "Color",
                            type: "color"
                        },
                        "Pointer.Type": {
                            label: "Type",
                            type: "select",
                            collection: ["Full", "Small"]
                        },
                        "Pointer.SmallPointerWidth": {
                            label: "SmallPointerWidth",
                            type: "number",
                            attrs: {
                                min: 0,
                                max: 1,
                                step: .1
                            },
                            setValue: function(t, e) {
                                0 <= e && e <= 1 ? this.prop(t, e) : (this.prop(t, .1), s.a.trigger("update-properties-panel", this))
                            }
                        }
                    }
                },
                Label: {
                    label: "Label",
                    fields: {
                        "Label.Color": {
                            label: "Color",
                            type: "color"
                        },
                        "Label.Decimals": {
                            label: "Decimals",
                            type: "number",
                            attrs: {
                                min: 0,
                                max: 1,
                                step: .1
                            }
                        },
                        "Label.Font": o.a._font("Label.")
                    }
                },
                Inverted: {
                    label: "Inverted",
                    type: "checkbox"
                },
                Scale: {
                    label: "Scale",
                    fields: {
                        FirstSubScale: {
                            label: "FirstSubScale",
                            fields: {
                                "Scale.FirstSubScale.Enabled": {
                                    type: "checkbox",
                                    label: "Enabled"
                                },
                                "Scale.FirstSubScale.ShowCaption": {
                                    type: "checkbox",
                                    label: "ShowCaption"
                                }
                            }
                        },
                        SecondSubScale: {
                            label: "SecondSubScale",
                            fields: {
                                "Scale.SecondSubScale.Enabled": {
                                    type: "checkbox",
                                    label: "Enabled"
                                },
                                "Scale.SecondSubScale.ShowCaption": {
                                    type: "checkbox",
                                    label: "ShowCaption"
                                }
                            }
                        },
                        Font: o.a._font("Scale."),
                        MajorTicks: {
                            label: "MajorTicks",
                            fields: {
                                "Scale.MajorTicks.Color": {
                                    label: "Color",
                                    type: "color"
                                },
                                "Scale.MajorTicks.Width": {
                                    label: "Width",
                                    type: "number"
                                }
                            }
                        },
                        MinorTicks: {
                            label: "MinorTicks",
                            fields: {
                                "Scale.MinorTicks.Color": {
                                    label: "Color",
                                    type: "color"
                                },
                                "Scale.MinorTicks.Width": {
                                    label: "Width",
                                    type: "number"
                                }
                            }
                        }
                    }
                },
                Style: {
                    type: "select",
                    label: "Style",
                    collection: function() {
                        var t = c().styles,
                            e = [""];
                        return t.eachEntity(function(t) {
                            e.push(t.prop("Name"))
                        }), e
                    },
                    setValue: function(t, e) {
                        function n(t, n) {
                            var e = i.findOneBy({
                                Name: t
                            });
                            ! function(e) {
                                var t = e.prop("Style");
                                t && ((t = c().styles.findOneBy({
                                    Name: t
                                })) && (t.eachProp(function(t) {
                                    "Name" !== t && e.deleteProp(t)
                                }), t.eachAttr(function(t) {
                                    e.deleteAttr(t)
                                })), e.setDefaultFont && e.setDefaultFont())
                            }(n), e && (e.eachProp(function(t, e) {
                                "Name" !== t && n.prop(t, e)
                            }), e.eachAttr(function(t, e) {
                                n.attr(t, e)
                            })), n.prop("Style", t), n.render()
                        }
                        var a = this.prop("Style"),
                            i = c().styles;
                        a !== e && (e = e || "", r.a.push({
                            func: n,
                            undoData: [a || "", this],
                            redoData: [e, this]
                        }), n(e, this))
                    }
                },
                ScrollBars: {
                    label: "ScrollBars",
                    type: "select",
                    collection: ["None", "Horizontal", "Vertical", "Both"]
                },
                TabWidth: {
                    type: "number",
                    label: "TabWidth"
                },
                Watermark: {
                    label: "Watermark",
                    fields: {
                        "Watermark.Enabled": {
                            label: "Enabled",
                            type: "checkbox"
                        },
                        "Watermark.Font": o.a._font("Watermark."),
                        "Watermark.Image": {
                            label: "Image",
                            type: "file"
                        },
                        "Watermark.ImageSize": {
                            label: "ImageSize",
                            type: "select",
                            collection: ["Normal", "Center", "Stretch", "Zoom", "Tile"]
                        },
                        "Watermark.ImageTransparency": {
                            label: "ImageTransparency",
                            type: "number",
                            attrs: {
                                min: 0,
                                max: 1,
                                step: .1
                            }
                        },
                        "Watermark.ShowImageOnTop": {
                            label: "ShowImageOnTop",
                            type: "checkbox"
                        },
                        "Watermark.ShowTextOnTop": {
                            label: "ShowTextOnTop",
                            type: "checkbox"
                        },
                        "Watermark.Text": {
                            label: "Text",
                            type: "text"
                        },
                        "Watermark.TextFill": {
                            label: "TextFill",
                            fields: {
                                "Watermark.TextFill.Color": {
                                    type: "color",
                                    label: "Color"
                                }
                            }
                        },
                        "Watermark.TextRotation": {
                            label: "TextRotation",
                            type: "select",
                            collection: ["Horizontal", "Vertical", "ForwardDiagonal", "BackwardDiagonal"]
                        }
                    }
                }
            }
        },
        Build: {
            label: "Properties Build",
            propName: "Build",
            fields: {}
        },
        Behavior: {
            label: "Properties Behavior",
            propName: "Behavior",
            fields: {
                AcceptsReturn: {
                    label: "AcceptsReturn",
                    type: "checkbox"
                },
                AcceptsTab: {
                    label: "AcceptsTab",
                    type: "checkbox"
                },
                ColumnWidth: {
                    label: "ColumnWidth",
                    type: "number"
                },
                MultiColumn: {
                    label: "MultiColumn",
                    type: "checkbox"
                },
                SelectionMode: {
                    label: "SelectionMode",
                    type: "select",
                    collection: ["None", "One", "MultiSimple", "MultiExtended"]
                },
                Sorted: {
                    label: "Sorted",
                    type: "checkbox"
                },
                UseTabStops: {
                    label: "UseTabStops",
                    type: "checkbox"
                },
                CharacterCasing: {
                    label: "CharacterCasing",
                    type: "select",
                    collection: ["Normal", "Upper", "Lower"]
                },
                BackPage: {
                    label: "BackPage",
                    type: "checkbox"
                },
                MirrorMargins: {
                    label: "MirrorMargins",
                    type: "checkbox"
                },
                PrintOnPreviousPage: {
                    label: "PrintOnPreviousPage",
                    type: "checkbox"
                },
                Enabled: {
                    label: "Enabled",
                    type: "checkbox"
                },
                FirstRowStartsNewPage: {
                    label: "FirstRowStartsNewPage",
                    type: "checkbox"
                },
                Layout: {
                    label: "Layout",
                    type: "select",
                    collection: ["AcrossThenDown", "DownThenAcross", "Wrapped"]
                },
                KeepChild: {
                    label: "KeepChild",
                    type: "checkbox"
                },
                KeepDetail: {
                    label: "KeepDetail",
                    type: "checkbox"
                },
                KeepTogether: {
                    label: "KeepTogether",
                    type: "checkbox"
                },
                KeepWithData: {
                    label: "KeepWithData",
                    type: "checkbox"
                },
                MaxLength: {
                    label: "MaxLength",
                    type: "number"
                },
                Multiline: {
                    label: "Multiline",
                    type: "checkbox"
                },
                ResetPageNumber: {
                    label: "ResetPageNumber",
                    type: "checkbox"
                },
                ReadOnly: {
                    label: "ReadOnly",
                    type: "checkbox"
                },
                StartNewPage: {
                    type: "checkbox",
                    label: "StartNewPage"
                },
                StartOnOddPage: {
                    type: "checkbox",
                    label: "StartOnOddPage"
                },
                TitleBeforeHeader: {
                    type: "checkbox",
                    label: "TitleBeforeHeader"
                },
                AutoShrink: {
                    type: "select",
                    label: "AutoShrink",
                    collection: ["None", "FontSize", "FontWidth"]
                },
                AutoShrinkMinSize: {
                    type: "number",
                    label: "AutoShrinkMinSize"
                },
                AutoSize: {
                    type: "checkbox",
                    label: "AutoSize"
                },
                AutoWidth: {
                    type: "checkbox",
                    label: "AutoWidth"
                },
                CanBreak: {
                    type: "checkbox",
                    label: "CanBreak"
                },
                CanGrow: {
                    type: "checkbox",
                    label: "CanGrow"
                },
                CanShrink: {
                    type: "checkbox",
                    label: "CanShrink"
                },
                CompleteToNRows: {
                    type: "number",
                    label: "CompleteToNRows"
                },
                DialogResult: {
                    label: "DialogResult",
                    type: "select",
                    collection: ["None", "OK", "Cancel", "Abort", "Retry", "Ignore", "Yes", "No"]
                },
                FillUnusedSpace: {
                    type: "checkbox",
                    label: "FillUnusedSpace"
                },
                HideIfNoData: {
                    type: "checkbox",
                    label: "HideIfNoData"
                },
                ShowText: {
                    type: "checkbox",
                    label: "ShowText"
                },
                Clip: {
                    type: "checkbox",
                    label: "Clip"
                },
                Duplicates: {
                    label: "Duplicates",
                    type: "select",
                    collection: ["Show", "Hide", "Clear", "Merge"]
                },
                CollectChildRows: {
                    label: "CollectChildRows",
                    type: "checkbox"
                },
                Exportable: {
                    type: "checkbox",
                    label: "Exportable"
                },
                Editable: {
                    type: "checkbox",
                    label: "Editable"
                },
                GrowToBottom: {
                    type: "checkbox",
                    label: "GrowToBottom"
                },
                HideValue: {
                    type: "text",
                    label: "HideValue"
                },
                HideZeros: {
                    type: "checkbox",
                    label: "HideZeros"
                },
                TextRenderType: {
                    type: "select",
                    label: "TextRenderType",
                    collection: ["Default", "HtmlTags", "HtmlParagraph"]
                },
                NullValue: {
                    type: "text",
                    label: "NullValue"
                },
                Printable: {
                    type: "checkbox",
                    label: "Printable"
                },
                PrintIfDatasourceEmpty: {
                    type: "checkbox",
                    label: "PrintIfDatasourceEmpty"
                },
                PrintIfDetailEmpty: {
                    type: "checkbox",
                    label: "PrintIfDetailEmpty"
                },
                PrintOnBottom: {
                    type: "checkbox",
                    label: "PrintOnBottom"
                },
                PrintOnParent: {
                    type: "checkbox",
                    label: "PrintOnParent"
                },
                RepeatOnEveryPage: {
                    label: "RepeatOnEveryPage",
                    type: "checkbox"
                },
                ProcessAt: {
                    label: "ProcessAt",
                    type: "select",
                    collection: ["Default", "ReportFinished", "ReportPageFinished", "PageFinished", "ColumnFinished", "DataFinished", "GroupFinished", "Custom"]
                },
                RightToLeft: {
                    label: "RightToLeft",
                    type: "checkbox"
                },
                ShiftMode: {
                    label: "ShiftMode",
                    type: "select",
                    collection: ["Never", "Always", "WhenOverlapped"]
                },
                Trimming: {
                    label: "Trimming",
                    type: "select",
                    collection: ["None", "Character", "Word", "EllipsisCharacter", "EllipsisWord", "EllipsisPath"]
                },
                TabIndex: {
                    label: "TabIndex",
                    type: "number"
                },
                TabStop: {
                    label: "TabStop",
                    type: "checkbox"
                },
                Visible: {
                    label: "Visible",
                    type: "checkbox"
                },
                WordWrap: {
                    label: "WordWrap",
                    type: "checkbox"
                },
                Wysiwyg: {
                    label: "Wysiwyg",
                    type: "checkbox"
                },
                SortOrder: {
                    label: "SortOrder",
                    type: "select",
                    collection: ["None", "Ascending", "Descending"]
                },
                ShowErrorImage: {
                    label: "ShowErrorImage",
                    type: "checkbox"
                },
                SizeMode: {
                    label: "SizeMode",
                    type: "select",
                    collection: ["Normal", "StretchImage", "AutoSize", "CenterImage", "Zoom"]
                },
                UseSystemPasswordChar: {
                    label: "UseSystemPasswordChar",
                    type: "checkbox"
                }
            }
        },
        Email: {
            label: "Properties Email",
            propName: "Email",
            fields: {
                EmailSettings: {
                    label: "EmailSettings",
                    fields: {
                        "EmailSettings.Message": {
                            label: "Message",
                            type: "text"
                        },
                        "EmailSettings.Recipients": {
                            label: "Recipients",
                            type: "textarea"
                        },
                        "EmailSettings.Subject": {
                            label: "Subject",
                            type: "text"
                        }
                    }
                }
            }
        },
        Engine: {
            label: "Properties Engine",
            propName: "Engine",
            fields: {
                ConvertNulls: {
                    label: "ConvertNulls",
                    type: "checkbox"
                },
                DoublePass: {
                    label: "DoublePass",
                    type: "checkbox"
                },
                InitialPageNumber: {
                    label: "InitialPageNumber",
                    type: "number"
                },
                UseFillCache: {
                    label: "UseFillCache",
                    type: "checkbox"
                }
            }
        },
        Misc: {
            label: "Properties Misc",
            fields: {
                ReportPage: {
                    label: "ReportPage",
                    type: "select",
                    collection: []
                },
                Description: {
                    label: "Description",
                    type: "text"
                },
                AutoFillDataSet: {
                    label: "AutoFillDataSet",
                    type: "checkbox"
                },
                Compressed: {
                    label: "Compressed",
                    type: "checkbox"
                },
                MaxPages: {
                    label: "MaxPages",
                    type: "number"
                },
                MaxSvgHeight: {
                    label: "MaxSvgHeight",
                    type: "number"
                },
                MaxSvgWidth: {
                    label: "MaxSvgWidth",
                    type: "number"
                },
                SvgDocument: {
                    label: "SvgDocument",
                    type: "text",
                    attrs: {
                        disabled: !0
                    }
                },
                SvgGrayscale: {
                    label: "SvgGrayscale",
                    type: "text",
                    attrs: {
                        disabled: !0
                    }
                },
                SmoothGraphics: {
                    label: "SmoothGraphics",
                    type: "checkbox"
                },
                TextQuality: {
                    label: "TextQuality",
                    type: "select",
                    collection: ["Default", "Regular", "ClearType", "AntiAlias"]
                },
                ForceLoadData: {
                    label: "ForceLoadData",
                    type: "checkbox"
                },
                AcceptButton: {
                    label: "AcceptButton",
                    type: "select",
                    collection: function() {
                        return ["None"].concat(this.components.all(["ButtonControl"]))
                    }
                },
                CancelButton: {
                    label: "CancelButton",
                    type: "select",
                    collection: function() {
                        return ["None"].concat(this.components.all(["ButtonControl"]))
                    }
                }
            }
        },
        Data: {
            label: "Properties Data",
            propName: "Data",
            fields: {
                Calculated: {
                    label: "Calculated",
                    type: "checkbox"
                },
                DataSource: {
                    type: "select",
                    label: "DataSource",
                    getValue: function(t) {
                        var e = this.prop(t);
                        return e ? e._id : null
                    },
                    collection: function() {
                        var t = c(),
                            a = [],
                            i = function(t, e) {
                                return {
                                    key: t._id,
                                    value: Array(e + 1).join(" - ") + t.toString()
                                }
                            };
                        return t.dataSources && t.dataSources.everyEntity(function(t, e, n) {
                            a.push(i(t, n))
                        }), t.connections && t.connections.everyEntity(function(t) {
                            t.dataSources.everyEntity(function(t, e, n) {
                                a.push(i(t, n))
                            })
                        }), a.unshift({
                            key: "",
                            value: ""
                        }), a
                    },
                    setValue: function(t, e) {
                        var n = c(),
                            a = void 0;
                        e && (a = n.connections && n.connections.findOneDSAmongAll({
                            _id: e
                        }) || n.dataSources && n.dataSources.findOneAmongAll({
                            _id: e
                        })), this.prop(t, a || ""), this.render()
                    }
                },
                Expression: {
                    type: "text",
                    label: "Expression",
                    expression: !0,
                    exprMenu: !0
                },
                Filter: {
                    type: "text",
                    label: "Filter",
                    expression: !0,
                    exprMenu: !0
                },
                MaxRows: {
                    type: "number",
                    label: "MaxRows",
                    attrs: {
                        min: 0
                    }
                },
                Relation: {
                    label: "Relation",
                    type: "select",
                    collection: function() {
                        var t = c(),
                            e = t.relations && t.relations.all() || [];
                        return e.unshift(""), e
                    }
                },
                RowCount: {
                    label: "RowCount",
                    type: "number"
                },
                AllowExpressions: {
                    type: "checkbox",
                    label: "AllowExpressions"
                },
                Brackets: {
                    type: "text",
                    label: "Brackets",
                    isValid: function(t) {
                        return /^[\(\[]{1}[,]{1}[\)\]]{1}$/.test(i.a.trim(t))
                    }
                },
                OutlineExpression: {
                    type: "text",
                    label: "OutlineExpression"
                },
                Format: {
                    label: "Format",
                    type: "text",
                    expression: !0,
                    expressionEventName: "format"
                },
                NoDataText: {
                    type: "text",
                    label: "NoDataText"
                },
                Condition: {
                    type: "text",
                    label: "Condition",
                    expression: !0,
                    exprMenu: !0
                },
                DataColumn: {
                    type: "text",
                    label: "DataColumn"
                },
                SelectCommand: {
                    type: "text",
                    label: "SelectCommand"
                },
                StoreData: {
                    type: "checkbox",
                    label: "StoreData"
                },
                TableName: {
                    type: "text",
                    label: "TableName"
                },
                CommandTimeout: {
                    type: "number",
                    label: "CommandTimeout"
                },
                ConnectionString: {
                    type: "text",
                    label: "ConnectionString",
                    readonly: !0
                },
                ConnectionStringExpression: {
                    type: "text",
                    label: "ConnectionStringExpression",
                    expression: !0,
                    exprMenu: !0
                },
                ImageSourceExpression: {
                    type: "text",
                    label: "ImageSourceExpression",
                    expression: !0,
                    exprMenu: !0
                },
                LoginPrompt: {
                    type: "checkbox",
                    label: "LoginPrompt"
                }
            }
        },
        "Data Filtering": {
            label: "Data Filtering",
            fields: {
                AutoFill: {
                    label: "AutoFill",
                    type: "checkbox"
                },
                AutoFilter: {
                    label: "AutoFilter",
                    type: "checkbox"
                },
                DataColumn: {
                    label: "DataColumn",
                    type: "text"
                },
                DetailControl: {
                    label: "DetailControl",
                    type: "select",
                    collection: function() {
                        return ["None"].concat(this.collection.container.components.all())
                    }
                },
                FilterOperation: {
                    label: "FilterOperation",
                    type: "select",
                    collection: ["Equal", "NotEqual", "LessThan", "LessThanOrEqual", "GreaterThan", "GreaterThanOrEqual", "Contains", "NotContains", "StartsWith", "NotStartsWith", "EndsWith", "NotEndsWith"]
                },
                ReportParameter: {
                    label: "ReportParameter",
                    type: "text"
                }
            }
        },
        Design: {
            label: "Properties Design",
            propName: "Design",
            fields: {
                BindableControl: {
                    label: "BindableControl",
                    type: "select",
                    collection: ["Text", "Rich", "Picture", "CheckBox", "Custom"],
                    afterSetValue: function() {
                        this.updateBindableControl(), s.a.trigger("update-data-panel")
                    }
                },
                CustomBindableControl: {
                    type: "text",
                    label: "CustomBindableControl"
                },
                Format: {
                    label: "Format",
                    type: "select",
                    collection: ["Auto", "General", "Number", "Currency", "Date", "Time", "Percent", "Boolean"]
                },
                Tag: {
                    label: "Tag",
                    type: "text"
                },
                ReportInfo: {
                    label: "ReportInfo",
                    fields: {
                        "ReportInfo.Author": {
                            label: "Author",
                            type: "text"
                        },
                        "ReportInfo.Created": {
                            label: "Created",
                            type: "datetime"
                        },
                        "ReportInfo.CreatorVersion": {
                            label: "CreatorVersion",
                            type: "text"
                        },
                        "ReportInfo.Description": {
                            label: "Description",
                            type: "textarea"
                        },
                        "ReportInfo.Modified": {
                            label: "Modified",
                            type: "datetime"
                        },
                        "ReportInfo.Name": {
                            label: "Name",
                            type: "text"
                        },
                        "ReportInfo.Picture": {
                            label: "Picture",
                            type: "file"
                        },
                        "ReportInfo.PreviewPictureRatio": {
                            label: "PreviewPictureRatio",
                            type: "number",
                            attrs: {
                                min: .05,
                                step: .1
                            }
                        },
                        "ReportInfo.SavePreviewPicture": {
                            label: "SavePreviewPicture",
                            type: "checkbox"
                        },
                        "ReportInfo.Version": {
                            label: "Version",
                            type: "text"
                        },
                        "ReportInfo.Tag": {
                            label: "Tag",
                            type: "json"
                        },
                        "ReportInfo.SaveMode": {
                            label: "SaveMode",
                            type: "select",
                            collection: ["All", "Original", "User", "Role", "Security", "Deny", "Custom"]
                        }
                    }
                },
                StoreInResources: {
                    label: "StoreInResources",
                    type: "checkbox"
                },
                ExtraDesignWidth: {
                    label: "ExtraDesignWidth",
                    type: "checkbox"
                }
            }
        },
        Layout: {
            label: "Properties Layout",
            propName: "Layout",
            fields: {
                AutoSize: {
                    label: "AutoSize",
                    type: "checkbox"
                },
                Maximum: {
                    label: "Maximum",
                    type: "number",
                    setValue: function(t, e) {
                        e > this.prop("Minimum") ? (this.prop(t, e), this.prop("Value") > e && (this.prop("Value", this.prop("Minimum")), s.a.trigger("update-properties-panel", this))) : s.a.trigger("update-properties-panel", this)
                    }
                },
                Minimum: {
                    label: "Minimum",
                    type: "number",
                    setValue: function(t, e) {
                        e < this.prop("Maximum") ? (this.prop(t, e), this.prop("Value") < e && (this.prop("Value", e), s.a.trigger("update-properties-panel", this))) : s.a.trigger("update-properties-panel", this)
                    }
                },
                Value: {
                    label: "Value",
                    type: "number",
                    setValue: function(t, e) {
                        e >= this.prop("Minimum") && e <= this.prop("Maximum") ? this.prop(t, e) : s.a.trigger("update-properties-panel", this)
                    }
                },
                MaxHeight: {
                    type: "unit",
                    label: "MaxHeight",
                    attrs: {
                        min: 0
                    }
                },
                MaxWidth: {
                    type: "unit",
                    label: "MaxWidth",
                    attrs: {
                        min: 0
                    }
                },
                MinHeight: {
                    type: "unit",
                    label: "MinHeight",
                    attrs: {
                        min: 0
                    }
                },
                MinWidth: {
                    type: "unit",
                    label: "MinWidth",
                    attrs: {
                        min: 0
                    }
                }
            }
        },
        Paper: {
            label: "Properties Paper",
            propName: "Paper",
            fields: {
                BottomMargin: {
                    type: "unit",
                    label: "BottomMargin",
                    attrs: {
                        min: 0
                    }
                },
                Landscape: {
                    type: "checkbox",
                    label: "Landscape"
                },
                LeftMargin: {
                    type: "unit",
                    label: "LeftMargin",
                    attrs: {
                        min: 0
                    }
                },
                PaperHeight: {
                    type: "unit",
                    label: "PaperHeight",
                    attrs: {
                        min: 0,
                        step: .1
                    }
                },
                PaperWidth: {
                    type: "unit",
                    label: "PaperWidth",
                    attrs: {
                        min: 0,
                        step: .1
                    }
                },
                RawPaperSize: {
                    type: "text",
                    label: "RawPaperSize"
                },
                RightMargin: {
                    type: "unit",
                    label: "RightMargin",
                    attrs: {
                        min: 0
                    }
                },
                TopMargin: {
                    type: "unit",
                    label: "TopMargin",
                    attrs: {
                        min: 0
                    }
                }
            }
        },
        Print: {
            label: "Properties Print",
            propName: "Print",
            fields: {
                Duplex: {
                    type: "text",
                    label: "Duplex"
                },
                FirstPageSource: {
                    type: "number",
                    label: "FirstPageSource"
                },
                OtherPagesSource: {
                    type: "number",
                    label: "OtherPagesSource"
                },
                PrintSettings: {
                    label: "PrintSettings",
                    fields: {
                        "PrintSettings.Collate": {
                            label: "Collate",
                            type: "checkbox"
                        },
                        "PrintSettings.Copies": {
                            label: "Copies",
                            type: "number"
                        },
                        "PrintSettings.CopyNames": {
                            label: "CopyNames",
                            type: "textarea"
                        },
                        "PrintSettings.Duplex": {
                            label: "Duplex",
                            type: "select",
                            collection: ["Default", "Simplex", "Vertical", "Horizontal"]
                        },
                        "PrintSettings.PageNumbers": {
                            label: "PageNumbers",
                            type: "text"
                        },
                        "PrintSettings.PageRange": {
                            label: "PageRange",
                            type: "select",
                            collection: ["All", "Current", "PageNumbers"]
                        },
                        "PrintSettings.PagesOnSheet": {
                            label: "PagesOnSheet",
                            type: "select",
                            collection: ["One", "Two", "Four", "Eight"]
                        },
                        "PrintSettings.PaperSource": {
                            label: "PaperSource",
                            type: "number"
                        },
                        "PrintSettings.Printer": {
                            label: "Printer",
                            type: "select",
                            collection: ["Default"]
                        },
                        "PrintSettings.PrintMode": {
                            label: "PrintMode",
                            type: "select",
                            collection: ["Default", "Split", "Scale"]
                        },
                        "PrintSettings.PrintOnSheetHeight": {
                            label: "PrintOnSheetHeight",
                            type: "number"
                        },
                        "PrintSettings.PrintOnSheetRawPaperSize": {
                            label: "PrintOnSheetRawPaperSize",
                            type: "number"
                        },
                        "PrintSettings.PrintOnSheetWidth": {
                            label: "PrintOnSheetWidth",
                            type: "number"
                        },
                        "PrintSettings.PrintPages": {
                            label: "PrintPages",
                            type: "select",
                            collection: ["All", "Odd", "Even"]
                        },
                        "PrintSettings.PrintToFile": {
                            label: "PrintToFile",
                            type: "checkbox"
                        },
                        "PrintSettings.PrintToFileName": {
                            label: "PrintToFileName",
                            type: "text"
                        },
                        "PrintSettings.Reverse": {
                            label: "Reverse",
                            type: "checkbox"
                        },
                        "PrintSettings.SavePrinterWithReport": {
                            label: "SavePrinterWithReport",
                            type: "checkbox"
                        },
                        "PrintSettings.ShowDialog": {
                            label: "ShowDialog",
                            type: "checkbox"
                        }
                    }
                }
            }
        },
        Hierarchy: {
            label: "Properties Hierarchy",
            propName: "Hierarchy",
            fields: {
                IdColumn: {
                    type: "select",
                    label: "IdColumn"
                },
                Indent: {
                    type: "unit",
                    label: "Indent"
                },
                ParentIdColumn: {
                    type: "select",
                    label: "ParentIdColumn"
                }
            }
        },
        Navigation: {
            label: "Properties Navigation",
            propName: "Navigation",
            fields: {
                Bookmark: {
                    label: "Bookmark",
                    type: "text",
                    expression: !0
                },
                Hyperlink: {
                    label: "Hyperlink",
                    expression: !0,
                    expressionEventName: "hyperlink-editor",
                    fields: {
                        "Hyperlink.DetailPageName": {
                            type: "text",
                            label: "DetailPageName"
                        },
                        "Hyperlink.DetailReportName": {
                            type: "text",
                            label: "DetailReportName"
                        },
                        "Hyperlink.Expression": {
                            type: "text",
                            label: "Expression",
                            expression: !0,
                            exprMenu: !0
                        },
                        "Hyperlink.Kind": {
                            type: "select",
                            label: "Kind",
                            collection: ["URL", "PageNumber", "Bookmark", "DetailReport", "DetailPage", "Custom"]
                        },
                        "Hyperlink.ReportParameter": {
                            type: "text",
                            label: "ReportParameter"
                        },
                        "Hyperlink.Value": {
                            type: "text",
                            label: "Value"
                        },
                        "Hyperlink.ValueSeparator": {
                            type: "text",
                            label: "ValueSeparator"
                        },
                        "Hyperlink.OpenLinkInNewTab": {
                            type: "checkbox",
                            label: "OpenLinkInNewTab"
                        }
                    }
                }
            }
        },
        Script: {
            label: "Properties Script",
            propName: "Script",
            fields: {
                ReferencedAssemblies: {
                    label: "ReferencedAssemblies",
                    type: "textarea",
                    attrs: {
                        disabled: !0
                    }
                },
                ScriptLanguage: {
                    label: "ScriptLanguage",
                    type: "select",
                    collection: ["CSharp", "Vb"],
                    afterSetValue: function(t, e) {
                        var n = c().code;
                        n && n.setMode(e)
                    }
                }
            }
        }
    }, !0), e.a = o.a
}, function(t, e, n) {
    "use strict";
    var a, i, r, o, s, l, c;
    n.r(e), a = n(0), i = n.n(a), r = n(16), o = n(2), s = n(1), l = n(7), c = n(17), e.default = Object(l.a)(r.a, {
        _init: function() {
            r.a._init.apply(this, arguments), this._id = "cnt" + Object(c.a)()
        },
        isDialogControl: function() {
            return !0
        },
        getContextMenuItems: function() {
            var t = this;
            return [{
                name: o.a.tr("ComponentMenu Edit"),
                type: "default",
                closeAfter: !0,
                onClick: function() {
                    s.a.trigger("activate", t), t.dblclick()
                }
            }, {
                type: "separator"
            }, {
                name: o.a.tr("Menu Edit Cut"),
                type: "default",
                closeAfter: !0,
                shortcut: "Ctrl + X",
                onClick: function() {
                    s.a.trigger("cut", i()(t))
                }
            }, {
                name: o.a.tr("Menu Edit Copy"),
                type: "default",
                closeAfter: !0,
                shortcut: "Ctrl + C",
                onClick: function() {
                    s.a.trigger("copy", i()(t))
                }
            }, {
                name: o.a.tr("Menu Edit Paste"),
                type: "default",
                disabled: !window.DSG.currentReport.getCurrentPage().buffer.length,
                closeAfter: !0,
                shortcut: "Ctrl + V",
                onClick: function() {
                    setTimeout(function() {
                        s.a.trigger("paste")
                    }, 100)
                }
            }, {
                name: o.a.tr("Menu Edit Delete"),
                type: "default",
                disabled: !t.canBeRemoved(),
                closeAfter: !0,
                shortcut: "Del",
                onClick: function() {
                    s.a.trigger("remove", t)
                }
            }, {
                type: "separator"
            }, {
                name: o.a.tr("Layout BringToFront"),
                type: "default",
                closeAfter: !0,
                onClick: function() {
                    s.a.trigger("bring-to-front", t)
                }
            }, {
                name: o.a.tr("Layout SendToBack"),
                type: "default",
                closeAfter: !0,
                onClick: function() {
                    s.a.trigger("send-to-back", t)
                }
            }]
        }
    })
}, function(t, e, n) {
    "use strict";

    function s(t, e, n) {
        function a() {
            this[t].apply(this, arguments), this.render && this.render(), this.activate && this.activate()
        }
        p.a.push({
            context: this,
            undo: function() {
                a.apply(this, arguments)
            },
            redo: function() {
                a.apply(this, arguments)
            },
            undoData: [e, this[t](e)],
            redoData: [e, n]
        })
    }
    var a, i, r, o = n(0),
        l = n.n(o),
        c = n(4),
        p = n(15),
        d = n(7),
        u = function(t, e) {
            var n, a;
            if (!t || t.constructor !== Array || !e || e.constructor !== Array) return !1;
            if (t === e) return !0;
            if ((n = t.length) !== e.length) return !1;
            for (a = 0; a < n; ++a)
                if (t[a] !== e[a]) return !1;
            return !0
        },
        h = n(88),
        f = n(178),
        g = n(127),
        m = function(t) {
            var e, n = /^#?([a-f\d])([a-f\d])([a-f\d])$/i;
            return t = t.replace(n, function(t, e, n, a) {
                return e + e + n + n + a + a
            }), (e = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(t)) ? {
                r: parseInt(e[1], 16),
                g: parseInt(e[2], 16),
                b: parseInt(e[3], 16)
            } : null
        },
        b = function(t, e) {
            var n, a = m(t),
                i = m(e);
            if (a && i) return n = {
                r: parseInt((a.r + i.r) / 2, 10),
                g: parseInt((a.g + i.g) / 2, 10),
                b: parseInt((a.b + i.b) / 2, 10)
            }, Object(g.a)(n.r, n.g, n.b)
        },
        v = n(62),
        y = n(84),
        C = n(28),
        S = n(154),
        x = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function(t) {
            return typeof t
        } : function(t) {
            return t && "function" == typeof Symbol && t.constructor === Symbol && t !== Symbol.prototype ? "symbol" : typeof t
        },
        w = null,
        T = Object(d.a)({}, {
            init: function() {},
            _init: function() {},
            attributes: {},
            properties: {},
            defaultValues: {
                Left: 0,
                Top: 0,
                "Border.Color": "#000",
                "Border.Width": 1,
                "Border.Style": c.a.get("default-dasharray"),
                "Border.Lines": "None",
                "Border.Shadow": !1,
                "Border.ShadowColor": "#000",
                "Border.ShadowWidth": 4,
                Cursor: "Default",
                EvenStyle: "",
                EvenStylePriority: "UseFill",
                "Fill.Color": "transparent",
                "Fill.Hatch": !1,
                "Fill.Blend": 0,
                Restrictions: [],
                FirstRowStartsNewPage: !1,
                KeepChild: !1,
                ResetPageNumber: !1,
                StartNewPage: !1,
                AutoSize: !1,
                Exportable: !0,
                Printable: !0,
                PrintOnBottom: !1,
                ShiftMode: "Always",
                PrintOn: ["FirstPage", "LastPage", "OddPages", "EvenPages", "RepeatedBand", "SinglePage"],
                RepeatOnEveryPage: !1,
                Visible: !0,
                Anchor: ["Top", "Left"],
                "Padding.All": -1,
                Brackets: "[,]",
                "Hyperlink.ValueSeparator": ";",
                "Hyperlink.OpenLinkInNewTab": !0
            },
            createObject: function(t, e) {
                return (e = e || {}).attributes = {}, e.properties = {}, e.defaultValues = {}, Object(d.a)(t || this, e)
            },
            clone: function() {
                var t, e, n, a, i = this._super,
                    r = i.create.apply(i, arguments);
                if (r) {
                    for (a = n = e = t = void 0, n = this.properties, t = (e = Object.keys(n)).length; t--;) "Name" !== (a = e[t]) && r.prop(a, n[a]);
                    for (n = this.attributes, t = (e = Object.keys(n)).length; t--;) a = e[t], r.attr(a, n[a])
                }
                return r
            },
            attr: function(t, e, n) {
                var a, i, r;
                if ("string" == typeof t) return void 0 === e ? void 0 !== this.attributes[t] ? this.attributes[t] : this._super.attr && this._super.attr(t) : (this.attributes[t] !== e && (this.touch(), n && s.call(this, "attr", t, e), this.attributes[t] = e), e);
                if ("object" === (void 0 === t ? "undefined" : x(t)))
                    for (a = (i = Object.keys(t)).length; a--;) r = i[a], this.attr(r, t[r]);
                return null
            },
            prop: function(t, e, n) {
                var a, i, r, o;
                if ("string" == typeof t) return a = void 0 !== this.defaultValues[t] ? this.defaultValues[t] : T.defaultValues[t], void 0 === e ? void 0 !== this.properties[t] ? this.properties[t] : void 0 !== a ? a : this._super.prop && this._super.prop(t) : (this.properties[t] !== e && (this.touch(), n && s.call(this, "prop", t, e), a && (a === e || u(a, e)) ? this.deleteProp(t) : this.properties[t] = e), e);
                if ("object" === (void 0 === t ? "undefined" : x(t)))
                    for (i = (r = Object.keys(t)).length; i--;) o = r[i], this.prop(o, t[o]);
                return null
            },
            findAttrs: function(t) {
                for (var e, n = {}, a = this.attributes, i = Object.keys(a), r = i.length; r--;) e = i[r], t.test(e) && (n[e] = a[e]);
                return n
            },
            findProps: function(t) {
                for (var e, n = {}, a = this.properties, i = Object.keys(a), r = i.length; r--;) e = i[r], t.test(e) && (n[e] = a[e]);
                return n
            },
            onChangeCM: function(t, e) {
                var n;
                t.prop && (n = t.curVal, t.val && (n = n ? t.val : ""), e ? e[t.prop] = n : this.prop(t.prop, n))
            },
            deleteAttr: function(t) {
                return this.touch(), delete this.attributes[t]
            },
            deleteProp: function(t) {
                return this.touch(), delete this.properties[t]
            },
            eachProp: function(t) {
                for (var e, n, a = this.properties, i = Object.keys(a), r = i.length; r-- && (void 0 === (n = a[e = i[r]]) || "" === n || null === n || (Object(h.a)(n) ? n = n.toFixed(2) : n instanceof Date && (n = Object(S.a)(n)), !1 !== t.call(this, e, n))););
            },
            eachAttr: function(t) {
                for (var e, n, a = this.attributes, i = Object.keys(a), r = i.length; r-- && (!(n = a[e = i[r]]) || (Object(h.a)(n) && (n = n.toFixed(2)), !1 !== t.call(this, e, n))););
            },
            counter: (r = {}, {
                incr: function(t) {
                    return r[t] || (r[t] = 0), ++r[t]
                },
                clear: function() {
                    r = {}
                }
            }),
            names: (i = {}, {
                save: function(t, e, n) {
                    return n ? (i[t] = e, !0) : !i[t] && (i[t] = e, !0)
                },
                remove: function(t) {
                    return delete i[t], !0
                }
            }),
            _fillProps: function(t) {
                var e, n, a, i = t[0].attributes,
                    r = !1;
                for (this._original || (this._original = t, this._originalAttrs = {}, r = !0), e = void 0, n = i.length, a = void 0; n--;) i.hasOwnProperty(n) && (e = (a = i[n]).value, "Text" !== a.name && (e = Object(f.a)(e)), "Name" === a.name && this.names.save(e, this, !0), this.prop(a.name, e, void 0, !0), r && (this._originalAttrs[a.name] = e));
                return this
            },
            fillPropsNET: function(t) {
                var e, n = window.DSG.currentReport;
                return this._fillProps(t), this.prop("data-entity-id") && (this._id = this.prop("data-entity-id"), this.deleteProp("data-entity-id")), (e = this.prop("DataSource")) && (n.connections || n.dataSources) && this.prop("DataSource", n.connections.pullDSByView(e) || n.dataSources.pullByView(e)), this
            },
            fillPropsVCL: function(t) {
                return this._fillProps(t)
            },
            getFillColor: function() {
                var t, e, n, a = this.prop("Fill.Color");
                return a && "transparent" !== a ? n = a : (t = this.prop("Fill.StartColor"), e = this.prop("Fill.EndColor"), t && e || (t = this.prop("Fill.CenterColor"), e = this.prop("Fill.EdgeColor"), t && e || (t = this.prop("Fill.BackColor"), e = this.prop("Fill.ForeColor"))), t && e && (n = b(Object(v.a)(t), Object(v.a)(e)))), n || a
            },
            state: (a = {}, ["normal", "in_move", "in_resize"].forEach(function(t) {
                a[t] = []
            }), {
                set: function(t) {
                    var e = this;
                    return e.attr("state", t), l.a.each(a, function() {
                        Object(C.a)(this, e)
                    }), a[t].push(e), e
                },
                get: function(t) {
                    return a[t]
                }
            }),
            setState: function(t) {
                return this.state.set.call(this, t)
            },
            createName: function() {
                this.prop("Name", this.formName())
            },
            formName: function(t) {
                var e = (t || this.prop("Name") || this.type) + this.counter.incr(t || this.type);
                return this.names.save(e, this) ? e : this.formName.apply(this, arguments)
            },
            formGroupInx: function() {
                return this.counter.incr("GroupIndex")
            },
            applyForBorder: function(t, e) {
                var n = this.prop("Border.Lines") || "";
                n = n.split(/,[\s]?/), Object(C.a)(n, ""), -1 < n.indexOf("All") && (Object(C.a)(n, "All"), n.push("Left"), n.push("Top"), n.push("Right"), n.push("Bottom")), Object(C.a)(n, "None"), "None" === t ? n = ["None"] : !e && n.includes(t) ? (Object(C.a)(n, t), n.length || (n = ["None"])) : "All" === t ? n = ["All"] : (n.push(t), (n = Object(y.a)(n)).includes("Left") && n.includes("Top") && n.includes("Right") && n.includes("Bottom") && (n = ["All"])), this.prop("Border.Lines", n.join(", "), !0)
            },
            getProp: function(t) {
                var e, n, a = this.fieldMap,
                    i = 0,
                    r = 1;
                for (t = t.split(":"), e = (a || []).length, n = t.length; i < e; i += 1)
                    if (a[i].propName === t[0] || a[i].label === t[0]) {
                        for (a = a[i].fields; r < n; r += 1) try {
                            if (a = a[t[r]], r + 1 < n) a = a.fields;
                            else if (a) return a
                        } catch (t) {
                            return null
                        }
                        break
                    } return null
            },
            canHaveProp: function(t) {
                return !!this.getProp(t)
            },
            render: function() {},
            mouseStart: function() {},
            resizingStart: function() {},
            resizing: function() {},
            resizingEnd: function() {},
            movingStart: function() {},
            moving: function() {},
            movingEnd: function() {},
            rotatingStart: function() {},
            rotating: function() {},
            rotatingEnd: function() {},
            creatingComponentOver: function() {},
            deleteComponentOver: function() {},
            creatingComponentEnd: function() {},
            getContextMenuTitle: function() {},
            getContextMenuItems: function() {},
            getContextMenuEventName: function() {},
            rightClick: function() {},
            mouseover: function() {},
            mouseout: function() {},
            afterAlign: function() {},
            afterInitShow: function() {},
            remove: function() {
                return !!this.canDelete() && (this === w && T.deactivate(), !0)
            },
            touch: function() {
                return this.touched = !0, this
            },
            activate: function() {
                w = this
            },
            deactivate: function() {
                w = null
            },
            getSelected: function() {
                return w
            },
            canMove: function() {
                var t;
                return !!c.a.get("movable-components") && (!!this.attr("movable") && !("DontMove" === (t = this.prop("Restrictions")) || -1 < t.indexOf("DontMove")))
            },
            canResizeX: function() {
                var t, e = this.attr("resizableX");
                return !!c.a.get("resizable-components") && (!e && void 0 !== e || (e = !("DontResize" === (t = this.prop("Restrictions")) || -1 < t.indexOf("DontResize"))), e)
            },
            canResizeY: function() {
                var t, e = this.attr("resizableY");
                return !!c.a.get("resizable-components") && (!e && void 0 !== e || (e = !("DontResize" === (t = this.prop("Restrictions")) || -1 < t.indexOf("DontResize"))), e)
            },
            canResizeXY: function() {
                var t, e = this.attr("resizableXY");
                return !!c.a.get("resizable-components") && (!e && void 0 !== e || (e = !("DontResize" === (t = this.prop("Restrictions")) || -1 < t.indexOf("DontResize"))), e)
            },
            canModify: function() {
                var t = this.prop("Restrictions");
                return !("DontModify" === t || -1 < t.indexOf("DontModify"))
            },
            canEdit: function() {
                var t = this.prop("Restrictions");
                return !("DontEdit" === t || -1 < t.indexOf("DontEdit"))
            },
            canDelete: function() {
                var t = this.prop("Restrictions");
                return !("DontDelete" === t || -1 < t.indexOf("DontDelete"))
            },
            hasHiddenProperties: function() {
                var t = this.prop("Restrictions");
                return "HideAllProperties" === t || -1 < t.indexOf("HideAllProperties")
            },
            isEntity: function() {
                return !0
            },
            isPage: function() {
                return !1
            },
            isDialog: function() {
                return !1
            },
            isStyle: function() {
                return !1
            },
            isBand: function() {
                return !1
            },
            isComponent: function() {
                return !1
            },
            isReport: function() {
                return !1
            },
            isConnection: function() {
                return !1
            },
            isDataSource: function() {
                return !1
            },
            canHaveChildren: function() {
                return !1
            },
            getContainer: function() {
                return this.getTable ? this.getTable() : this.collection && this.collection.container
            },
            getPage: function() {
                var t;
                return this.isPage() ? this : (t = this.getContainer()) ? t.isPage() ? t : (t.getTable ? t = t.getTable().getContainer() : t.isComponent() && (t = t.getContainer()), t ? t.collection && t.collection.mainCollection.container : null) : null
            },
            _toXML: function(t) {
                var n = this.attr("inherited"),
                    e = "vcl" === window.DSG.currentReport.attr("report-type") ? this.typevcl : this.type,
                    a = n ? "inherited" : e,
                    i = l()((t.parentNode.ownerDocument || t.parentNode).createElement(a));
                return t.includeId && i.attr("data-entity-id", this._id), this.eachProp(function(t, e) {
                    "Name" !== t && n && this._originalAttrs[t] === e || i.attr(t, e)
                }), i
            },
            toXMLNET: function(i) {
                var r = this;
                return new Promise(function(t) {
                    var e, n, a = r._toXML(i);
                    return "None" === r.prop("Border.Lines") && a.removeAttr("Border.Lines").removeAttr("Border.Color").removeAttr("Border.Width").removeAttr("Border.Style"), !1 === r.prop("Border.Shadow") && a.removeAttr("Border.Shadow").removeAttr("Border.ShadowWidth").removeAttr("Border.ShadowColor"), "transparent" === r.prop("Fill.Color") && a.removeAttr("Fill.Color"), (r.prop("Padding.Top") || r.prop("Padding.Right") || r.prop("Padding.Bottom") || r.prop("Padding.Left")) && (e = "{0}, {1}, {2}, {3}".format(Math.round(r.prop("Padding.Left") || 0), Math.round(r.prop("Padding.Top") || 0), Math.round(r.prop("Padding.Right") || 0), Math.round(r.prop("Padding.Bottom") || 0)), a.removeAttr("Padding.Left").removeAttr("Padding.Top").removeAttr("Padding.Right").removeAttr("Padding.Bottom"), a.attr("Padding", e), a.removeAttr("Padding.All")), "object" === (void 0 === (n = r.prop("DataSource")) ? "undefined" : x(n)) && a.attr("DataSource", n.prop("Name")), t(a[0])
                })
            },
            toXMLVCL: function(e) {
                var n = this;
                return new Promise(function(t) {
                    return t(n._toXML(e)[0])
                })
            },
            toString: function() {
                return this.prop("Name")
            }
        });
    e.a = T
}, function(t, e, n) {
    "use strict";
    var a = n(142),
        i = {
            cm: "Centimeters",
            mm: "Millimeters",
            in: "Inches",
            hi: "Hundredths of inch"
        },
        r = "cm",
        o = {
            cm: {
                toUnit: function(t) {
                    return 2.54 * t / 96
                },
                toPixels: function(t) {
                    return 96 * t / 2.54
                }
            },
            mm: {
                toUnit: function(t) {
                    return 2.54 * t / 9.6
                },
                toPixels: function(t) {
                    return 9.6 * t / 2.54
                }
            },
            hi: {
                toUnit: function(t) {
                    return +t
                },
                toPixels: function(t) {
                    return +t
                }
            },
            in: {
                toUnit: function(t) {
                    return t / 96
                },
                toPixels: function(t) {
                    return 96 * t
                }
            }
        };
    e.a = {
        all: function() {
            return Object.assign({}, i)
        },
        setCurrent: function(t) {
            r = t || r
        },
        getCurrent: function() {
            return r
        },
        toPx: function(t, e) {
            return "number" == typeof(t = Object(a.a)(t)) ? o[e || r].toPixels(t).toFixed(2) : null
        },
        toUnit: function(t, e) {
            return "number" == typeof(t = Object(a.a)(t)) ? o[e || r].toUnit(t).toFixed(2) : null
        }
    }
}, function(t, e, n) {
    "use strict";
    var a, i, r, o;
    n.d(e, "a", function() {
        return o
    }), a = 100, i = [], r = 0, o = {
        prev: function() {
            return 0 === r ? null : i[r -= 1]
        },
        next: function() {
            var t;
            return r === i.length ? null : (t = i[r], r += 1, t)
        },
        push: function(t) {
            (i = i.slice(0, r)).push(t), i.length > a && i.shift(), r = i.length
        },
        clear: function() {
            i.length = 0, r = i.length
        },
        canUndo: function() {
            return 0 < r && 0 < i.length
        },
        canRedo: function() {
            return r < i.length
        }
    }
}, function(t, e, n) {
    "use strict";
    var a = n(0),
        r = n.n(a),
        o = n(9),
        i = n(183),
        s = n(155),
        l = n(7),
        c = n(75),
        p = n(76),
        d = n(58);
    e.a = Object(l.a)(o.default, {
        applyFontStyles: function(t, e) {
            var n = "";
            e = e || "", t.css({
                "font-weight": this.attr(e + "Font.Bold") ? "bold" : "",
                "font-style": this.attr(e + "Font.Italic") ? "italic" : ""
            }), this.attr(e + "Font.Strikeout") && (n += "line-through", n += " "), this.attr(e + "Font.Underline") && (n += "underline"), t.css("text-decoration", n)
        },
        fillMap: function() {
            o.default.fillMap.call(this), this.fieldMap.append(["Data:Text"])
        },
        _trim: function(t) {
            return t && t.trim()
        },
        parseApplyFontNET: function(t, e) {
            var n, a, i, r, o, s = [];
            if (e = e || "", t) {
                for (i = 0, r = (t = t.split(",")).length; i < r; i += 1) {
                    if (o = t[i].trim(), /^style=/.test(o)) {
                        s.push(o.substr(6)), s = s.concat(t.slice(i + 1).map(this._trim));
                        break
                    }
                    isNaN(parseInt(o, 10)) ? n = o : a = o
                }
                n && this.attr(e + "Font.Name", n), a && this.attr(e + "Font.Size", a), s.length && (~s.indexOf("Bold") && this.attr(e + "Font.Bold", !0), ~s.indexOf("Italic") && this.attr(e + "Font.Italic", !0), ~s.indexOf("Underline") && this.attr(e + "Font.Underline", !0), ~s.indexOf("Strikeout") && this.attr(e + "Font.Strikeout", !0))
            }
        },
        fillFontNET: function(t, e) {
            e = e || "", this.parseApplyFontNET(t.attr(e + "Font"), e), this.deleteProp(e + "Font")
        },
        parseApplyFontVCL: function(t) {
            var e, n, a;
            t && (e = t["Font.Name"], n = Math.abs(t["Font.Height"]), e && this.attr("Font.Name", e), n && this.attr("Font.Size", n + "pt"), (a = s.a.fontStyleToNet(+t["Font.Style"] || 0)) && a.length && (~a.indexOf("Bold") && this.attr("Font.Bold", !0), ~a.indexOf("Italic") && this.attr("Font.Italic", !0), ~a.indexOf("Underline") && this.attr("Font.Underline", !0), ~a.indexOf("Strikeout") && this.attr("Font.Strikeout", !0)))
        },
        fillFontVCL: function(t) {
            var e = {};
            Array.prototype.forEach.call(t[0].attributes, function(t) {
                t.name.startsWith("Font.") && (e[t.name] = t.value)
            }), this.parseApplyFontVCL(e), Object.keys(e).forEach(function(t) {
                this.deleteProp(t)
            }.bind(this))
        },
        horzAlignNET2VCL: function(t) {
            return "Center" === t ? "haCenter" : "Right" === t ? "haRight" : "Justify" === t ? "haBlock" : "haLeft"
        },
        horzAlignVCL2NET: function(t) {
            return "haCenter" === t ? "Center" : "haRight" === t ? "Right" : "haBlock" === t ? "Justify" : "Left"
        },
        vertAlignNET2VCL: function(t) {
            return "Center" === t ? "vaCenter" : "Bottom" === t ? "vaBottom" : "vaTop"
        },
        vertAlignVCL2NET: function(t) {
            return "vaCenter" === t ? "Center" : "vaBottom" === t ? "Bottom" : "Top"
        },
        fillPropsNET: function(t) {
            o.default.fillPropsNET.apply(this, arguments), this.fillFontNET(t), this.prop("Text") && this.prop("Text", Object(c.a)(this.prop("Text").toString()));
            var e, n = t.find("> Highlight"),
                a = this;
            return n.length && this.highlights && r.a.each(n, function() {
                (e = i.a.create()).fillPropsNET(r()(this)), a.highlights.add(e)
            }), this
        },
        fillPropsVCL: function(t) {
            return o.default.fillPropsVCL.apply(this, arguments), this.fillFontVCL(t), this.prop("Text") && this.prop("Text", Object(c.a)(this.prop("Text").toString())), this.prop("HAlign") && (this.prop("HorzAlign", this.horzAlignVCL2NET(this.prop("HAlign"))), this.deleteProp("HAlign")), this.prop("VAlign") && (this.prop("VertAlign", this.vertAlignVCL2NET(this.prop("VAlign"))), this.deleteProp("VAlign")), this
        },
        toXMLNET: function(i) {
            var t = this;
            return new Promise(function(a) {
                o.default.toXMLNET.call(t, i).then(function(e) {
                    e = r()(e), i = Object.assign({
                        parentNode: e[0]
                    }, i), "#000000" === t.prop("TextFill.Color") && e.removeAttr("TextFill.Color"), t.prop("Text") && e.attr("Text", Object(p.a)(t.prop("Text").replace(/\n/gm, "\r\n"))), Object(d.a)(e, t);
                    var n = [];
                    t.highlights && t.highlights.eachEntity(function(t) {
                        n.push(t.toXMLNET(i))
                    }), Promise.all(n).then(function(t) {
                        return t.forEach(function(t) {
                            return e.append(t)
                        }), a(e.get(0))
                    })
                })
            })
        },
        toXMLVCL: function(t) {
            var n = this;
            return new Promise(function(e) {
                o.default.toXMLVCL.call(n, t).then(function(t) {
                    return t = r()(t), n.prop("Text") && t.attr("Text", Object(p.a)(n.prop("Text").replace(/\n/gm, "\r\n"))), n.prop("HorzAlign") && (t.attr("HAlign", n.horzAlignNET2VCL(n.prop("HorzAlign"))), t.removeAttr("HorzAlign")), n.prop("VertAlign") && (t.attr("VAlign", n.vertAlignNET2VCL(n.prop("VertAlign"))), t.removeAttr("VertAlign")), e(t[0])
                })
            })
        }
    })
}, function(t, e, n) {
    "use strict";
    e.a = function() {
        return Math.random().toString(36).substr(2, 9)
    }
}, , , , function(t, e, n) {
    "use strict";
    var a = !!/iphone|android|webos|blackberry|ipad|ipod|iemobile|opera mini/i.test(navigator.userAgent);
    e.a = a
}, , function(t, e, n) {
    "use strict";
    n.d(e, "a", function() {
        return a
    });
    var a = (i.prototype._writeLog = function(t, e, n, a) {
        var i, r;
        if (this.enabled) {
            if (function() {
                    var t, e, n = window.navigator.userAgent,
                        a = n.indexOf("MSIE ");
                    return 0 < a ? parseInt(n.substring(a + 5, n.indexOf(".", a)), 10) : 0 < n.indexOf("Trident/") ? (t = n.indexOf("rv:"), parseInt(n.substring(t + 3, n.indexOf(".", t)), 10)) : 0 < (e = n.indexOf("Edge/")) && parseInt(n.substring(e + 5, n.indexOf(".", e)), 10)
                }()) return console[e].apply(console, t);
            i = "", t.forEach(function(t) {
                i += "string" != typeof t && "number" != typeof t ? " %o " : " %s "
            }), r = "", n && (r += "color:" + n + ";"), a && (r += "background-color:" + a + ";"), t = this.logDate ? ["%c%s - %s: " + i, r, this.name, (new Date).toISOString()].concat(t) : ["%c%s: " + i, r, this.name].concat(t), console[e].apply(null, t)
        }
    }, i.prototype.enable = function() {
        this.enabled = !0
    }, i.prototype.disable = function() {
        this.enabled = !1
    }, i.prototype.enableLogDate = function() {
        this.logDate = !0
    }, i.prototype.disableLogDate = function() {
        this.logDate = !1
    }, i.prototype.log = function() {
        return this._writeLog(Array.from(arguments), "log")
    }, i.prototype.info = function() {
        return this._writeLog(Array.from(arguments), "info", "#2B579A")
    }, i.prototype.warn = function() {
        return this._writeLog(Array.from(arguments), "warn")
    }, i.prototype.error = function() {
        return this._writeLog(Array.from(arguments), "error")
    }, i.prototype.dirxml = function() {
        return this._writeLog(Array.from(arguments), "dirxml")
    }, i);

    function i() {
        var t = 0 < arguments.length && void 0 !== arguments[0] ? arguments[0] : "main",
            e = !(1 < arguments.length && void 0 !== arguments[1]) || arguments[1],
            n = !(2 < arguments.length && void 0 !== arguments[2]) || arguments[2];
        ! function(t, e) {
            if (!(t instanceof e)) throw new TypeError("Cannot call a class as a function")
        }(this, i), this.name = t, this.enabled = e, this.logDate = n
    }
}, function(t, e, n) {
    "use strict";
    var a = n(0),
        i = n.n(a),
        r = n(7),
        o = n(28);
    e.a = Object(r.a)({}, {
        container: null,
        entities: [],
        create: function(t) {
            var e = Object(r.a)(this);
            return e.entities = [], e.container = t || null, e
        },
        all: function(e) {
            return e && Array.isArray(e) ? this.entities.filter(function(t) {
                return !t.attr("removed") && -1 < e.indexOf(t.type) ? t : null
            }) : this.entities.filter(function(t) {
                return !t.attr("removed")
            })
        },
        allWithChildren: function(e) {
            var n = [];
            return e && Array.isArray(e) ? this.everyEntity(function(t) {
                !t.attr("removed") && -1 < e.indexOf(t.type) && n.push(t)
            }) : this.everyEntity(function(t) {
                t.attr("removed") || n.push(t)
            }), n
        },
        eachEntity: function(t, e) {
            var n, a = e ? this.entities : this.all();
            for (n = 0; n < a.length && !1 !== t.call(this, a[n], n); n += 1);
            return this
        },
        everyEntity: function() {
            return this.eachEntity.apply(this, arguments)
        },
        count: function(t) {
            return this.all(t).length
        },
        add: function(t) {
            if (t) return t.collection && t.collection !== this && t.collection.remove(t), (t.collection = this).entities.includes(t) || this.entities.push(t), this
        },
        find: function(n) {
            var a = void 0;
            return this.everyEntity(function(t, e) {
                if (t._id === n) return !(a = [t, e])
            }), a
        },
        findEntity: function() {
            var t = this.find.apply(this, arguments);
            return t && t[0]
        },
        findAmongChildren: function(n, t) {
            var a = void 0;
            return this.eachEntity(function(t, e) {
                if (t._id === n) return !(a = [t, e])
            }, t), a
        },
        nextAfter: function(n) {
            var a = void 0,
                i = void 0;
            return this.eachEntity(function(t, e) {
                if (n._id === t._id) i = e;
                else if (void 0 !== i && !t.attr("removed")) return a = t, !1
            }, !0), a
        },
        prevBefore: function(e) {
            var n = void 0,
                a = !1;
            return this.eachEntity(function(t) {
                if (e._id === t._id) return !(a = !0);
                t.attr("removed") || (n = t)
            }, !0), a && n
        },
        findBy: function(e) {
            var n = [],
                a = Object.keys(e),
                i = a.length,
                r = void 0,
                o = void 0,
                s = void 0;
            return this.everyEntity(function(t) {
                for (r = i; r--;)
                    if (o = a[r], s = e[o], t[o] !== s && t.attributes[o] !== s && t.properties[o] !== s) return;
                n.push(t)
            }), n
        },
        findOneBy: function() {
            return this.findBy.apply(this, arguments)[0]
        },
        findAmongChildrenBy: function(t) {
            var e = 0 < arguments.length && void 0 !== t ? t : {},
                n = [],
                a = Object.keys(e),
                i = a.length,
                r = void 0,
                o = void 0,
                s = void 0;
            return this.eachEntity(function(t) {
                for (r = i; r--;)
                    if (o = a[r], s = e[o], t[o] !== s && t.attributes[o] !== s && t.properties[o] !== s) return;
                n.push(t)
            }), n
        },
        findOneAmongChildrenBy: function() {
            return this.findAmongChildrenBy.apply(this, arguments)[0]
        },
        findAmongAll: function(e) {
            var n = [],
                a = Object.keys(e),
                i = a.length,
                r = void 0,
                o = void 0,
                s = void 0;
            return this.everyEntity(function(t) {
                for (r = i; r--;)
                    if (o = a[r], s = e[o], t[o] !== s && t.attributes[o] !== s && t.properties[o] !== s) return;
                n.push(t)
            }, !0), n
        },
        findOneAmongAll: function() {
            return this.findAmongAll.apply(this, arguments)[0]
        },
        first: function(t) {
            return this.all(t)[0] || null
        },
        last: function(t) {
            var e = this.all(t);
            return e[e.length - 1]
        },
        after: function(e, n) {
            var a = !1;
            return this.everyEntity(function(t) {
                e._id !== t._id ? a && n.call(t, t) : a = !0
            }), this
        },
        remove: function(t) {
            return t ? (Object(o.a)(this.entities, t), this) : null
        },
        clear: function() {
            return this.entities.length = 0, this
        },
        isEmpty: function() {
            return !this.entities.length
        },
        getMainCollection: function() {
            return this.mainCollection || this.container.collection.mainCollection
        },
        getSelectedComponents: function(t) {
            var e = ".component.selected";
            return t && (e = e + "." + t), i()(e, this.container.$workspace).map(function() {
                return this.component.attr("removed") ? null : this.component
            })
        },
        getComponents: function(t, e) {
            var n = ".component";
            return t && (n = "{0}.{1}".format(n, t)), i()(n, this.container.$workspace).map(function() {
                return !e && this.component.attr("removed") ? null : this.component
            })
        },
        getSelectedBand: function() {
            return i()(".band.selected", this.container.$workspace).map(function() {
                return this.band.attr("removed") ? null : this.band
            })[0]
        }
    })
}, function(t, e, n) {
    "use strict";
    var a, S, o, s, i, r, l, d, x, w, c, p, T, u;
    n.r(e), a = n(0), S = n.n(a), o = n(4), s = n(16), i = n(24), r = n(2), l = n(1), d = n(182), x = n(3), w = n(5), c = n(39), p = n(30), T = n(67), u = n(43), e.default = s.a.createObject(s.a, {
        title: "Objects TextObject",
        info: "TextObjectInfo",
        icon: "icon-102",
        pos: 10,
        type: "TextObject",
        typevcl: "TfrxMemoView",
        disabled: !1,
        _init: function() {
            s.a._init.apply(this, arguments), this.highlights = i.a.create(this), this.defaultValues = {
                Angle: 0,
                VertAlign: "Top",
                HorzAlign: "Left",
                "TextFill.Color": "#000",
                "Padding.Left": 2,
                "Padding.Right": 2,
                AutoShrinkMinSize: 0,
                Editable: !1,
                CanBreak: !0,
                Clip: !0,
                FirstTabOffset: 0,
                FontWidthRatio: 1,
                LineHeight: 0,
                ParagraphOffset: 0,
                TabWidth: 58,
                ProcessAt: "Default",
                WordWrap: !0,
                AllowExpressions: !0,
                TextRenderType: "Default"
            }, this.attr({
                "droppable-view": !0,
                "droppable-component": !0,
                withPadding: !0,
                "text-anchor": "start"
            }), this.prop({
                Name: "Text",
                Width: 94.5,
                Height: 18.9,
                Text: ""
            }), this.setDefaultFont()
        },
        setDefaultFont: function() {
            this.attr({
                "Font.Name": o.a.get("default-font-name"),
                "Font.Size": "10pt",
                "Font.Bold": !1,
                "Font.Italic": !1,
                "Font.Underline": !1,
                "Font.Strikeout": !1
            })
        },
        getPxFontSize: function() {
            var t = this.attr("Font.Size");
            return /pt$/.test(t) && (t = +(parseFloat(t, 10) / .75).toFixed(2)), parseFloat(t, 10)
        },
        getHorzAlign: function() {
            var t = this.prop("HorzAlign"),
                e = this.attr("innerWidth"),
                n = this.getPxFontSize(),
                a = 0;
            return this.attr("minPadding", n / 5), "Center" === t || "Justify" === t ? (a = e / 2, this.attr("text-anchor", "middle")) : "Left" === t ? (a = this.attr("minPadding"), this.attr("text-anchor", "start")) : "Right" === t && (a = e - this.attr("minPadding"), this.attr("text-anchor", "end")), parseInt(a, 10)
        },
        getVertAlign: function() {
            var t = this.prop("VertAlign"),
                e = this.attr("innerHeight"),
                n = this.attr("contentHeight"),
                a = this.getPxFontSize(),
                i = 0,
                r = 0;
            return "Center" === t ? r = e / 2 - n / 2 : "Bottom" === t && (r = e - n), (i = Math.floor(a / 10) - 1) < 0 && (i = 0), r + a - i
        },
        dblclick: function() {
            l.a.trigger("show-expression-editor", {
                entity: this,
                prop: "Text"
            })
        },
        getContextMenuItems: function() {
            function t(t) {
                return e.onChangeCM(t)
            }
            var e = this;
            return [{
                name: r.a.tr("ComponentMenu Edit"),
                type: "default",
                closeAfter: !0,
                onClick: function() {
                    l.a.trigger("activate", e), e.dblclick()
                }
            }, {
                name: r.a.tr("TextObject Format"),
                type: "default",
                closeAfter: !0,
                onClick: function() {
                    l.a.trigger("activate", e), l.a.trigger("format", e)
                }
            }, {
                name: r.a.tr("ComponentMenu Clear"),
                type: "default",
                closeAfter: !0,
                onClick: function() {
                    e.prop("Text", "", !0), e.render(), l.a.trigger("activate", e)
                }
            }, {
                name: r.a.tr("ComponentMenu Hyperlink"),
                type: "default",
                closeAfter: !0,
                onClick: function() {
                    l.a.trigger("hyperlink-editor", e)
                }
            }, {
                type: "separator"
            }, {
                name: r.a.tr("ComponentMenu CanGrow"),
                type: "checkbox",
                curVal: e.prop("CanGrow"),
                prop: "CanGrow",
                onChange: t
            }, {
                name: r.a.tr("ComponentMenu CanShrink"),
                type: "checkbox",
                curVal: e.prop("CanShrink"),
                prop: "CanShrink",
                onChange: t
            }, {
                name: r.a.tr("ComponentMenu CanBreak"),
                type: "checkbox",
                curVal: e.prop("CanBreak"),
                prop: "CanBreak",
                onChange: t
            }, {
                name: r.a.tr("ComponentMenu GrowToBottom"),
                type: "checkbox",
                curVal: e.prop("GrowToBottom"),
                prop: "GrowToBottom",
                onChange: t
            }, {
                type: "separator"
            }, {
                name: r.a.tr("ComponentMenu AutoWidth"),
                type: "checkbox",
                curVal: e.prop("AutoWidth"),
                prop: "AutoWidth",
                onChange: t
            }, {
                name: r.a.tr("ComponentMenu WordWrap"),
                type: "checkbox",
                curVal: e.prop("WordWrap"),
                prop: "WordWrap",
                onChange: t
            }, {
                name: r.a.tr("Allow Expressions"),
                type: "checkbox",
                curVal: e.prop("AllowExpressions"),
                prop: "AllowExpressions",
                onChange: t
            }, {
                type: "separator"
            }, {
                name: r.a.tr("Menu Edit Cut"),
                type: "default",
                closeAfter: !0,
                shortcut: "Ctrl + X",
                onClick: function() {
                    l.a.trigger("cut", S()(e))
                }
            }, {
                name: r.a.tr("Menu Edit Copy"),
                type: "default",
                closeAfter: !0,
                shortcut: "Ctrl + C",
                onClick: function() {
                    l.a.trigger("copy", S()(e))
                }
            }, {
                name: r.a.tr("Menu Edit Paste"),
                type: "default",
                disabled: !window.DSG.currentReport.getCurrentPage().buffer.length,
                closeAfter: !0,
                shortcut: "Ctrl + V",
                onClick: function() {
                    setTimeout(function() {
                        l.a.trigger("paste")
                    }, 100)
                }
            }, {
                name: r.a.tr("Menu Edit Delete"),
                type: "default",
                disabled: !e.canBeRemoved(),
                closeAfter: !0,
                shortcut: "Del",
                onClick: function() {
                    l.a.trigger("remove", e)
                }
            }, {
                type: "separator"
            }, {
                name: r.a.tr("Layout BringToFront"),
                type: "default",
                closeAfter: !0,
                onClick: function() {
                    l.a.trigger("bring-to-front", e)
                }
            }, {
                name: r.a.tr("Layout SendToBack"),
                type: "default",
                closeAfter: !0,
                onClick: function() {
                    l.a.trigger("send-to-back", e)
                }
            }]
        },
        mouseover: function() {
            var t, e, n = this.$moveBlock[0].getBoundingClientRect(),
                a = this.prop("Text"),
                i = this.attr("Font.Size"),
                r = window.DSG.head,
                o = this.prop("Width"),
                s = this.prop("Height"),
                l = this.prop("HorzAlign"),
                c = this.prop("VertAlign"),
                p = "";
            a && "in_move" !== this.attr("state") && "HtmlTags" === this.prop("TextRenderType") && (this.$tooltip || (this.$tooltip = new d.a, r.put(this.$tooltip)), (e = this.$tooltip).css({
                left: n.left,
                top: n.top + n.height + 10,
                width: o,
                height: s,
                "text-align": l.toLowerCase(),
                "background-color": this.$moveBlock.css("fill")
            }), this.attr("Font.Underline") && (p += "underline "), this.attr("Font.Strikeout") && (p += "line-through"), (t = e.find("span")).css({
                "vertical-align": "Center" === c ? "middle" : c.toLowerCase(),
                transform: "rotate(" + this.prop("Angle") + "deg)",
                "font-size": i + (Object(u.a)(i) ? "pt" : ""),
                "font-family": this.attr("Font.Name"),
                color: this.prop("TextFill.Color"),
                "font-weight": this.attr("Font.Bold") ? 700 : 400,
                "font-style": this.attr("Font.Italic") ? "italic" : "normal"
            }), p && t.css("text-decoration", p), a = a.replace(/\n/g, "<br/>"), t.html(a), r.$node.height() < e.offset().top + e.height() && e.css("top", n.top - e.innerHeight() - 10))
        },
        mouseout: function() {
            this.$tooltip && (this.$tooltip.remove(), delete this.$tooltip)
        },
        movingStart: function() {
            s.a.movingStart.apply(this, arguments), this.mouseout()
        },
        creatingComponentEnd: function(t, e, n) {
            this.prop("Text", n)
        },
        linesAmount: function() {
            return this.$textNode.find("tspan").length || 1
        },
        _getLineHeight: function(t) {
            var e = this.prop("LineHeight");
            return e || t
        },
        _createMultiline: function() {
            function t(t) {
                return Object(T.a)(t, {
                    "font-size": h,
                    "font-family": f,
                    "font-weight": g
                })
            }
            var e, n, a, i, r, o, s, l, c, p = this.prop("Text"),
                d = this.$textNode,
                u = p.split(/[\n]/g),
                h = d.css("font-size"),
                f = d.css("font-family"),
                g = d.css("font-weight"),
                m = this.attr("innerWidth"),
                b = 0,
                v = !1,
                y = void 0,
                C = void 0;
            for (d.empty(), e = 0; e < u.length; e += 1) {
                if (n = u[e], a = Object(x.a)("tspan"), i = S()(a), "" === n.trim()) n = "&nbsp;", i.append(n);
                else
                    for (r = n.split(" "), i.append(document.createTextNode(r[0])), d.append(i), o = 1; o < r.length; o += 1) s = r[o], l = i.text().length, i.text(i.text() + " " + s), (y = t(i.text())).w > m && (a.firstChild.data = a.firstChild.data.slice(0, l), C = this._getLineHeight(y.h), 0 === e || v || Object(w.a)(a, {
                        x: 0,
                        dy: C
                    }), a = Object(x.a)("tspan"), i = S()(a), Object(w.a)(a, {
                        x: 0,
                        dy: C
                    }), b += C, i.append(s), d.append(i), v = !0);
                y = y || t(n), b += C = this._getLineHeight(y.h), c = 0 !== e || v ? C : 0, Object(w.a)(a, {
                    x: 0,
                    dy: c
                }), d.append(i), y = null, v = !1
            }
            this.attr("contentHeight", b || 0)
        },
        render: function() {
            var t, e, n = this.prop("Angle"),
                a = this.attr("Font.Size"),
                i = this.attr("Font.Name"),
                r = this.prop("Text");
            return s.a.render.apply(this, arguments), this.$contentGroup.children().length || (this.$nestedG1 = S()(Object(x.a)("g")), this.$nestedG2 = S()(Object(x.a)("g")), this.$textNode = S()(Object(x.a)("text")), this.$nestedG2.append(this.$textNode), this.$nestedG1.append(this.$nestedG2), this.$contentGroup.append(this.$nestedG1)), this.attr("droppable-component") ? Object(c.a)(this.g, "droppable-component") : Object(p.a)(this.g, "droppable-component"), this.attr("droppable-view") ? Object(c.a)(this.g, "droppable-view") : Object(p.a)(this.g, "droppable-view"), r !== this.$textNode.text() && this.$textNode.text(r), this.$textNode.css({
                "font-size": a + (Object(u.a)(a) ? "pt" : ""),
                fill: this.prop("TextFill.Color"),
                "font-family": i
            }), this.prop("TextOutline.Enabled") ? this.$textNode.css({
                stroke: this.prop("TextOutline.Color") || "#000",
                "stroke-width": this.prop("TextOutline.Width") || 1,
                "stroke-dasharray": o.a.get("dasharrays")[this.prop("TextOutline.Style") || "Solid"]
            }) : this.$textNode.css({
                stroke: "",
                "stroke-width": "",
                "stroke-dasharray": ""
            }), this.applyFontStyles(this.$textNode), this._createMultiline(), t = this.getHorzAlign(), e = this.getVertAlign(), this.$textNode.css("text-anchor", this.attr("text-anchor")), Object(w.a)(this.$nestedG2[0], "transform", "translate(" + t + ", " + e + ")"), void 0 !== n && Object(w.a)(this.$nestedG1[0], "transform", "translate({0}, {1}) rotate({2} {3} {4})".format(this.prop("Width") / 2 - this.attr("innerWidth") / 2, this.prop("Height") / 2 - this.attr("innerHeight") / 2, n, this.attr("innerWidth") / 2, this.attr("innerHeight") / 2)), this.prop("Clip") ? this.body.firstChild.style = "overflow: hidden;" : this.body.firstChild.style = "overflow: visible;", this.$g
        }
    })
}, , function(t, e, n) {
    "use strict";
    var a, i, r, o, s, l, c, p, d, u, h, f, g, m, b;
    n.d(e, "a", function() {
        return d
    }), a = n(309), i = n(0), r = n.n(i), o = n(4), s = n(2), l = n(1), c = 300, p = 5e3, u = o.a.get("notifications"), d = u ? "html5" === u ? (h = 0, f = window.webkitNotifications || window.Notification || null, g = !0, r()(document).on("click", function() {
        f && g && f.requestPermission()
    }), function(t, e) {
        var n, a = {};
        return f ? (a.tag = h, a.icon = null, a.name = "", a.body = "string" != typeof t ? t.text().replace(/\n/, "") : e.trans ? s.a.tr(t) : t, n = f.createNotification ? f.createNotification(a.icon, a.name, a.body) : new f(a.name, a), h += 1, n.onshow = function() {
            !1 !== e.delay && setTimeout(function() {
                n.close()
            }, e.delay || p)
        }, n.onerror = function() {
            g = !1, f = null, l.a.trigger("info", {
                message: '\n                    HTML5 Notifications are blocked. Change property "notifications"\n                    in config on "default" or switch it off.\n                '
            })
        }, n) : null
    }) : (m = r()("<div>"), b = function(t, e) {
        var n = r()("<div>"),
            a = r()("<div>");
        return n.hide(), n.addClass("ntf"), a.addClass("ntf-message"), n.append(a), n.on("click", function() {
            n.fadeOut(c)
        }), e.success && n.addClass("ntf-success"), e.danger && n.addClass("ntf-danger"), e.warning && n.addClass("ntf-warning"), e.info && n.addClass("ntf-info"), !1 === e.limitWidth && n.css("max-width", "none"), "string" != typeof t ? a.html(t) : a.html(e.trans ? s.a.tr(t) : t), n
    }, m.addClass("ntf-panel"), function(t, e) {
        var n, a = window.DSG.head,
            i = window.DSG.currentReport;
        if ((e = e || {}).debug) return !1;
        m.parent().length || a.put(m), m.css({
            top: i.attr("Top"),
            right: "10px"
        }), n = b(t, e), e.inEmptyList ? m.html(n) : m.prepend(n), n.fadeIn(300, function() {
            !1 !== e.delay && setTimeout(function() {
                n.fadeOut(c, function() {
                    n.removeClass("success danger warning info")
                })
            }, e.delay || p)
        })
    }) : function() {}
}, function(t, e, n) {
    "use strict";
    var a = n(179);
    e.a = function(t, e) {
        for (var n = 0; n < t.length; n += 1)
            if (t[n] === e) return Object(a.a)(t, n);
        return !1
    }
}, function(t, e, n) {
    "use strict";
    var a = n(0),
        i = n.n(a),
        r = [{
            dataType: "null",
            label: " ",
            icon: "icon-224"
        }, {
            dataType: "System.Boolean",
            icon: "icon-228",
            label: "Boolean"
        }, {
            dataType: "System.Byte",
            icon: "icon-224",
            label: "Byte"
        }, {
            dataType: "System.Char",
            icon: "icon-223",
            label: "Char"
        }, {
            dataType: "System.DateTime",
            icon: "icon-227",
            label: "DateTime"
        }, {
            dataType: "System.Decimal",
            icon: "icon-226",
            label: "Decimal"
        }, {
            dataType: "System.Double",
            icon: "icon-225",
            label: "Double"
        }, {
            dataType: "System.Int16",
            icon: "icon-224",
            label: "Int16"
        }, {
            dataType: "System.Int32",
            icon: "icon-224",
            label: "Int32"
        }, {
            dataType: "System.Int64",
            icon: "icon-224",
            label: "Int64"
        }, {
            dataType: "System.SByte",
            icon: "icon-224",
            label: "SByte"
        }, {
            dataType: "System.Single",
            icon: "icon-225",
            label: "Single"
        }, {
            dataType: "System.String",
            icon: "icon-223",
            label: "String"
        }, {
            dataType: "System.TimeSpan",
            icon: "icon-227",
            label: "TimeSpan"
        }, {
            dataType: "System.UInt16",
            icon: "icon-224",
            label: "UInt16"
        }, {
            dataType: "System.UInt32",
            icon: "icon-224",
            label: "UInt32"
        }, {
            dataType: "System.UInt64",
            icon: "icon-224",
            label: "UInt64"
        }, {
            dataType: "System.Byte[]",
            icon: "icon-229",
            label: "Byte[]"
        }];
    e.a = {
        first: function() {
            return r[0]
        },
        get: function(e) {
            return i.a.grep(r, function(t) {
                return t.dataType === e
            })[0]
        },
        getByName: function(e) {
            return i.a.grep(r, function(t) {
                return t.label === e
            })[0]
        },
        all: function() {
            return r
        }
    }
}, function(t, e, n) {
    "use strict";
    var i = n(5);
    e.a = function(t, e) {
        var n, a;
        return t.classList ? t.classList.remove(e) : (n = new RegExp("(^|\\s)" + e + "(\\s|$)", "g"), a = (a = Object(i.a)(t, "class")).replace(n, "$1").replace(/\s+/g, " ").replace(/(^ | $)/g, ""), Object(i.a)(t, "class", a)), t
    }
}, function(t, p, d) {
    "use strict";
    (function(n) {
        var t = d(36),
            e = d(128),
            a = d(278),
            i = d(279),
            r = d(280),
            o = d(281),
            s = d(5),
            l = d(3),
            c = d(17);
        p.a = t.a.createObject(t.a, {
            SM: new e.a,
            init: function() {
                this.buffer = [], this.attr({
                    padding: 0,
                    margin: 0
                })
            },
            isPage: function() {
                return !0
            },
            isReportPage: function() {
                return !1
            },
            isDialog: function() {
                return !1
            },
            isCode: function() {
                return !1
            },
            createWorkspace: function(t) {
                this.g = this.workspace = Object(l.a)("svg", {
                    class: "d-rw " + t,
                    viewBox: "0 0 0 0"
                }), this.$g = this.$workspace = n(this.g), this.$workspace.addClass("fr-hidden"), this.updateFilters().updateDefs()
            },
            getNetID: function() {
                return this._netID = this._netID || "d-net-" + Object(c.a)(), this._netID
            },
            updateDefs: function() {
                var t = Object(l.a)("defs");
                return this.$workspace.find("defs").remove(), this.netId = this.getNetID(), n(t).append(new a.a, new i.a, new r.a, new o.a(this.netId)), this.$workspace.append(t), this
            },
            updateFilters: function() {
                var t, e;
                return this.$filters && this.$filters.remove(), this.$filters = n(Object(l.a)("g")), t = n(Object(l.a)("filter", {
                    id: "grayscale"
                })), e = n(Object(l.a)("feColorMatrix", {
                    type: "matrix",
                    values: "0.393 0.769 0.189 0 0 0.349 0.686 0.168 0 0 0.272 0.534 0.131 0 0 0 0 0 1 0"
                })), t.append(e), this.$filters.append(t), this.$workspace.append(this.$filters), this
            },
            show: function() {
                this.report.$workspace.removeClass("fr-hidden"), this.$workspace.removeClass("fr-hidden"), this.attr({
                    isHidden: !1,
                    removed: !1,
                    activated: !0
                }), this.report.pages.current = this
            },
            hide: function() {
                this.$workspace.addClass("fr-hidden"), this.attr({
                    isHidden: !0,
                    activated: !1
                })
            },
            activate: function() {
                return this.attr("isHidden") && this.show(), this
            },
            deactivate: function() {
                return this.attr("isHidden") || this.hide(), this
            },
            getWidth: function(t) {
                var e = this.attr("Width") || this.prop("Width") || 0;
                return e += this.attr("margin"), e += 2 * this.attr("padding"), e *= t
            },
            getHeight: function(t) {
                var e = this.attr("Height") || this.prop("Height") || 0;
                return e += 2 * this.attr("padding"), e *= t
            },
            getViewBox: function(t, e, n) {
                var a = Object(s.a)(this.workspace, "viewBox").split(" ");
                return void 0 !== e && (a[2] = e / t), void 0 !== n && (a[3] = n / t), a.join(" ")
            },
            setWidth: function() {
                var t = this.report.attr("data-scale"),
                    e = this.getWidth(t),
                    n = this.getViewBox(t, e);
                Object(s.a)(this.workspace, "viewBox", n), Object(s.a)(this.workspace, "width", e)
            },
            setHeight: function() {
                var t = this.report.attr("data-scale"),
                    e = this.getHeight(t),
                    n = this.getViewBox(t, void 0, e);
                Object(s.a)(this.workspace, "viewBox", n), Object(s.a)(this.workspace, "height", e)
            },
            updateSize: function() {
                this.setWidth(), this.setHeight()
            },
            createMini: function() {
                var t = this.$g.clone();
                return t.removeAttr("class"), t.removeAttr("width"), t.removeAttr("height"), Object(s.a)(t.get(0), "viewBox", this.getViewBox(1, this.getWidth(1), this.getHeight(1))), t.find(".ruler").remove(), t.removeClass("fr-hidden"), t
            }
        })
    }).call(this, d(0))
}, function(t, e, n) {
    "use strict";
    var a = n(4),
        i = n(78),
        r = n(1),
        o = n(15),
        s = n(29),
        l = n(7),
        c = a.a.get("dasharrays"),
        p = Object(l.a)(i.a, {
            data: {
                Appearance: {
                    label: "Appearance",
                    fields: {
                        Border: {
                            label: "Border",
                            fields: {
                                "Border.Color": {
                                    label: "Color",
                                    type: "color"
                                },
                                "Border.Width": {
                                    label: "Width",
                                    type: "number"
                                },
                                "Border.Style": {
                                    label: "Style",
                                    type: "select",
                                    collection: c
                                },
                                BottomLine: {
                                    label: "BottomLine",
                                    fields: {
                                        "Border.BottomLine.Color": {
                                            label: "Color",
                                            type: "color"
                                        },
                                        "Border.BottomLine.Style": {
                                            label: "Style",
                                            type: "select",
                                            collection: c
                                        },
                                        "Border.BottomLine.Width": {
                                            label: "Width",
                                            type: "number",
                                            defaultValue: 1
                                        }
                                    }
                                },
                                LeftLine: {
                                    label: "LeftLine",
                                    fields: {
                                        "Border.LeftLine.Color": {
                                            label: "Color",
                                            type: "color"
                                        },
                                        "Border.LeftLine.Style": {
                                            label: "Style",
                                            type: "select",
                                            collection: c
                                        },
                                        "Border.LeftLine.Width": {
                                            label: "Width",
                                            type: "number",
                                            defaultValue: 1
                                        }
                                    }
                                },
                                RightLine: {
                                    label: "RightLine",
                                    fields: {
                                        "Border.RightLine.Color": {
                                            label: "Color",
                                            type: "color"
                                        },
                                        "Border.RightLine.Style": {
                                            label: "Style",
                                            type: "select",
                                            collection: c
                                        },
                                        "Border.RightLine.Width": {
                                            label: "Width",
                                            type: "number",
                                            defaultValue: 1
                                        }
                                    }
                                },
                                TopLine: {
                                    label: "TopLine",
                                    fields: {
                                        "Border.TopLine.Color": {
                                            label: "Color",
                                            type: "color"
                                        },
                                        "Border.TopLine.Style": {
                                            label: "Style",
                                            type: "select",
                                            collection: c
                                        },
                                        "Border.TopLine.Width": {
                                            label: "Width",
                                            type: "number",
                                            defaultValue: 1
                                        }
                                    }
                                },
                                "Border.Shadow": {
                                    label: "Shadow",
                                    type: "checkbox"
                                },
                                "Border.ShadowColor": {
                                    label: "ShadowColor",
                                    type: "color"
                                },
                                "Border.ShadowWidth": {
                                    label: "ShadowWidth",
                                    type: "number"
                                }
                            },
                            expression: !0,
                            expressionEventName: "edit-border"
                        },
                        Fill: {
                            label: "Fill",
                            fields: {
                                "Fill.Blend": {
                                    type: "number",
                                    label: "Blend",
                                    attrs: {
                                        min: 0,
                                        max: 1,
                                        step: .1
                                    }
                                },
                                "Fill.Color": {
                                    type: "color",
                                    label: "Color",
                                    getValue: function() {
                                        return this.prop("Fill.Color") || this.$moveBlock.css("fill")
                                    }
                                },
                                "Fill.Hatch": {
                                    type: "checkbox",
                                    label: "Hatch"
                                }
                            }
                        },
                        Font: i.a._font(),
                        HorzAlign: {
                            type: "select",
                            label: "HorzAlign",
                            collection: ["Left", "Center", "Right", "Justify"]
                        },
                        TextFill: {
                            label: "TextFill",
                            fields: {
                                "TextFill.Color": {
                                    type: "color",
                                    label: "Color",
                                    getValue: function() {
                                        return this.prop("TextFill.Color") || this.$textNode.css("fill")
                                    }
                                }
                            }
                        },
                        TextOutline: {
                            type: "text",
                            label: "TextOutline",
                            expression: !0,
                            expressionEventName: "text-outline",
                            readonly: !0,
                            readonlyValue: "(TextOutline)"
                        },
                        Underlines: {
                            label: "Underlines",
                            type: "checkbox"
                        },
                        VertAlign: {
                            label: "VertAlign",
                            type: "select",
                            collection: ["Top", "Center", "Bottom"]
                        },
                        Columns: {
                            label: "Columns",
                            fields: {
                                "Columns.Count": {
                                    label: "Count",
                                    extraLabel: "Columns count",
                                    type: "number",
                                    attrs: {
                                        min: 0
                                    }
                                },
                                "Columns.Layout": {
                                    label: "Layout",
                                    type: "select",
                                    collection: ["AcrossThenDown", "DownThenAcross"]
                                },
                                "Columns.MinRowCount": {
                                    label: "MinRowCount",
                                    type: "number"
                                },
                                "Columns.Position": {
                                    label: "Position",
                                    type: "number"
                                },
                                "Columns.Width": {
                                    label: "Width",
                                    extraLabel: "Columns width",
                                    type: "unit",
                                    attrs: {
                                        min: 0
                                    }
                                }
                            }
                        }
                    }
                },
                Behavior: {
                    label: "Behavior",
                    fields: {
                        BreakTo: {
                            type: "select",
                            label: "BreakTo",
                            collection: function() {
                                function n(t) {
                                    e !== t && a.push(t)
                                }
                                var t, e = this,
                                    a = [""],
                                    i = ["TableObject", "MatrixObject"];
                                return "TableCell" === this.type ? (t = this.getTable().collection.container).components.eachEntity(function(t) {
                                    -1 < i.indexOf(t.type) && t._eachCell(function(t, e) {
                                        n(e)
                                    })
                                }) : ("TableCell" === (t = this.collection.container).type && (t = t.getTable().collection.container), t.components.eachEntity(function(t) {
                                    -1 < i.indexOf(t.type) ? t._eachCell(function(t, e) {
                                        n(e)
                                    }) : t.type === e.type && n(t)
                                })), a
                            }
                        },
                        PrintOn: {
                            type: "select",
                            label: "PrintOn",
                            multiple: !0,
                            collection: ["FirstPage", "LastPage", "OddPages", "EvenPages", "RepeatedBand", "SinglePage"]
                        }
                    }
                },
                Data: {
                    label: "Data",
                    fields: {
                        Text: {
                            type: "textarea",
                            label: "Text",
                            expression: !0,
                            exprMenu: !0
                        },
                        Highlight: {
                            type: "text",
                            label: "Highlight",
                            expression: !0,
                            expressionEventName: "edit-highlight",
                            readonly: !0,
                            getValue: function() {
                                return this.highlights.count() ? "[...]" : "[]"
                            }
                        },
                        Image: {
                            type: "file",
                            label: "Image"
                        },
                        ImageLocation: {
                            type: "url",
                            label: "ImageLocation"
                        },
                        DataType: {
                            label: "DataType",
                            type: "select",
                            collection: function() {
                                return s.a.all()
                            },
                            setValue: function(e, t) {
                                var n = s.a.getByName(t);
                                o.a.push({
                                    context: this,
                                    func: function(t) {
                                        this.prop(e, t), r.a.trigger("update-data-panel")
                                    },
                                    undoData: [this.prop(e)],
                                    redoData: [n]
                                }), this.prop(e, n), r.a.trigger("update-data-panel")
                            }
                        }
                    }
                },
                Design: {
                    label: "Design",
                    fields: {
                        Name: {
                            label: "(Name)",
                            extraLabel: "Name",
                            type: "text",
                            afterSetValue: function() {
                                r.a.trigger("update-info"), this.updateTreeView(), r.a.trigger("update-data-panel")
                            },
                            isValid: function(t) {
                                return !/\s/.test(t)
                            }
                        },
                        Alias: {
                            label: "Alias",
                            type: "text",
                            getValue: function(t) {
                                return this.prop(t) || this.prop("Name")
                            },
                            setValue: function(t, e) {
                                this.prop(t, e === this.prop("Name") ? "" : e, !0)
                            },
                            afterSetValue: function() {
                                r.a.trigger("update-data-panel")
                            }
                        },
                        Restrictions: {
                            label: "Restrictions",
                            type: "select",
                            multiple: !0,
                            collection: ["DontMove", "DontResize", "DontModify", "DontEdit", "DontDelete", "HideAllProperties"],
                            setValue: function(e, t) {
                                function n() {
                                    i.hasHiddenProperties() ? i.fieldMap = p.factory([]) : i.canModify() ? i.fillMap() : i.fieldMap = p.factory(["Design:Restrictions"]), r.a.trigger("update-properties-panel", i)
                                }
                                var a = this.prop(e),
                                    i = this;
                                o.a.push({
                                    func: function(t) {
                                        i.prop(e, t), n()
                                    },
                                    undoData: [a],
                                    redoData: [t]
                                }), this.prop(e, t), n()
                            },
                            afterSetValue: function() {
                                r.a.trigger("update-properties-panel", this)
                            }
                        }
                    }
                },
                Layout: {
                    label: "Layout",
                    fields: {
                        Anchor: {
                            label: "Anchor",
                            type: "select",
                            multiple: !0,
                            collection: ["Top", "Left", "Right", "Bottom"]
                        },
                        Dock: {
                            label: "Dock",
                            type: "select",
                            collection: ["None", "Top", "Left", "Fill", "Right", "Bottom"]
                        },
                        Height: Object.assign(i.a._topProps(), {
                            label: "Height",
                            type: "unit"
                        }),
                        Left: Object.assign(i.a._leftProps(), {
                            label: "Left",
                            type: "unit"
                        }),
                        Padding: {
                            label: "Padding",
                            fields: {
                                "Padding.All": {
                                    type: "number",
                                    label: "All",
                                    afterSetValue: function(t, e) {
                                        this.prop({
                                            "Padding.Top": e,
                                            "Padding.Right": e,
                                            "Padding.Bottom": e,
                                            "Padding.Left": e
                                        }), r.a.trigger("update-properties-panel", this)
                                    }
                                },
                                "Padding.Bottom": {
                                    type: "number",
                                    label: "Bottom"
                                },
                                "Padding.Left": {
                                    type: "number",
                                    label: "Left"
                                },
                                "Padding.Right": {
                                    type: "number",
                                    label: "Right"
                                },
                                "Padding.Top": {
                                    type: "number",
                                    label: "Top"
                                }
                            }
                        },
                        Top: Object.assign(i.a._topProps(), {
                            type: "unit",
                            label: "Top"
                        }),
                        Width: Object.assign(i.a._leftProps(), {
                            type: "unit",
                            label: "Width"
                        })
                    }
                }
            }
        });
    e.a = p
}, function(t, e, n) {
    "use strict";
    var a, f, g, i, r, o, s, l, c;
    n.r(e), a = n(0), f = n.n(a), g = n(9), i = n(2), r = n(1), o = n(116), s = n(117), l = n(89), c = n(30), e.default = g.default.createObject(g.default, {
        title: "Objects TableObject",
        info: "TableObjectInfo",
        icon: "icon-127",
        pos: 60,
        type: "TableObject",
        disabled: !1,
        _init: function() {
            g.default._init.apply(this, arguments), this.rows = [], this.columns = [], this.defaultValues = {
                Layout: "AcrossThenDown",
                WrappedGap: 0,
                AdjustSpannedCellsWidth: !1,
                RepeatHeaders: !0,
                ManualBuildAutoSpans: !0,
                FixedColumns: 0,
                FixedRows: 0
            }, this.prop("Name", "Table"), this.attr({
                RowCount: 3,
                ColumnCount: 3
            })
        },
        createCell: function(t) {
            return l.a.create(t)
        },
        clone: function() {
            var i, r = g.default.clone.apply(this);
            return this._eachRow(function(n, a) {
                r.rows[n] = a.clone(r), this._eachColumn(function(t, e) {
                    r.columns[t] || (r.columns[t] = e.clone(r)), i = a.cells[t].clone(r.rows[n]), r.rows[n].cells[t] = i
                })
            }), r
        },
        getContextMenuItems: function() {
            var t = this,
                e = t.onChangeCM.bind(t);
            return [{
                name: i.a.tr("TableObject RepeatHeaders"),
                type: "checkbox",
                curVal: t.prop("RepeatHeaders"),
                prop: "RepeatHeaders",
                onChange: e
            }, {
                name: i.a.tr("BreakableComponent CanBreak"),
                type: "checkbox",
                curVal: t.prop("CanBreak"),
                prop: "CanBreak",
                onChange: e
            }, {
                name: i.a.tr("ComponentMenu GrowToBottom"),
                type: "checkbox",
                curVal: t.prop("GrowToBottom"),
                prop: "GrowToBottom",
                onChange: e
            }, {
                type: "separator"
            }, {
                name: i.a.tr("Menu Edit Delete"),
                type: "default",
                disabled: !t.canBeRemoved(),
                closeAfter: !0,
                onClick: function() {
                    r.a.trigger("remove", t)
                }
            }, {
                type: "separator"
            }, {
                name: i.a.tr("Layout BringToFront"),
                type: "default",
                closeAfter: !0,
                onClick: function() {
                    r.a.trigger("bring-to-front", t)
                }
            }, {
                name: i.a.tr("Layout SendToBack"),
                type: "default",
                closeAfter: !0,
                onClick: function() {
                    r.a.trigger("send-to-back", t)
                }
            }]
        },
        attr: function(t, n) {
            var e = g.default.attr.apply(this, arguments);
            return void 0 !== n && ("RowCount" === t ? this.rows = this.rows.slice(0, n) : "ColumnCount" === t && (this.columns = this.columns.slice(0, n), this._eachRow(function(t, e) {
                e.cells = e.cells.slice(0, n)
            }))), e
        },
        resizingComponents: function() {
            var t;
            return g.default.resizingComponents.apply(this, arguments), ~(t = this.resizingElements.map(function(t) {
                return t.className
            }).indexOf("nw-resize")) && (this.resizingElements[t].hidden = !0), this
        },
        appendRow: function(t) {
            var e = s.a.create(this);
            return this.attr("RowCount", this.attr("RowCount") + 1), f.a.isNumeric(t) || (t = this.rows.length), this.rows.splice(t, 0, e), this._eachColumn(function() {
                e.cells.push(this.createCell(e))
            }), e
        },
        removeRow: function(t) {
            return this.rows.splice(t, 1), this.attr("RowCount", this.attr("RowCount") - 1), this
        },
        appendColumn: function(n) {
            var t = o.a.create(this);
            return this.attr("ColumnCount", this.attr("ColumnCount") + 1), f.a.isNumeric(n) || (n = this.columns.length), this.columns.splice(n, 0, t), this._eachRow(function(t, e) {
                this._eachColumn(function(t) {
                    t === n && e.cells.splice(t, 0, this.createCell(e))
                })
            }), t
        },
        removeColumn: function(t) {
            return this.columns.splice(t, 1), this.attr("ColumnCount", this.attr("ColumnCount") - 1), this
        },
        getCell: function(t, e) {
            return this.rows[t].cells[e]
        },
        _eachRow: function(t) {
            var e = 0,
                n = this.attr("RowCount");
            for (t = t || function() {}; e < n; e += 1) t.call(this, e, this.rows[e] || (this.rows[e] = s.a.create(this)))
        },
        _eachColumn: function(t) {
            var e = 0,
                n = this.attr("ColumnCount");
            for (t = t || function() {}; e < n; e += 1) t.call(this, e, this.columns[e] || (this.columns[e] = o.a.create(this)))
        },
        _eachCell: function(a) {
            var i;
            a = a || function() {}, this._eachRow(function(t, n) {
                this._eachColumn(function(t, e) {
                    i = n.cells[t] || (n.cells[t] = this.createCell(n)), a.call(this, t, i, n, e)
                })
            })
        },
        _percentage: function() {
            var n = this.prop("Width"),
                a = this.prop("Height");
            this._eachColumn(function(t, e) {
                e.attr("percentage", e.prop("Width") / (n / 100))
            }), this._eachRow(function(t, e) {
                e.attr("percentage", e.prop("Height") / (a / 100))
            })
        },
        _recalculate: function() {
            var n = this.prop("Width"),
                a = this.prop("Height");
            this._eachRow(function(t, e) {
                e.prop("Height", a / 100 * e.attr("percentage"))
            }), this._eachColumn(function(t, e) {
                e.prop("Width", n / 100 * e.attr("percentage"))
            })
        },
        _processSpanCell: function() {
            var a, i, r = [];
            return this._processSpanCell = function(t, e, n) {
                if (i = t.prop("RowSpan"), r[n] && r[n].self !== t) {
                    if (r[n].counter += 1, r[n].counter <= r[n].need && (r[n].self.prop("Height", r[n].self.prop("Height") + this.rows[e].prop("Height")), t.$g.addClass("fr-hidden"), r[n].self.render(), 1 < (i = r[n].self.prop("ColSpan")) && (r[n].self.getPosInRow() + i > this.attr("ColumnCount") && (i = Math.abs(r[n].self.getPosInRow() - this.attr("ColumnCount"))), a = {
                            self: t,
                            counter: 1,
                            need: i
                        })), r[n].counter > r[n].need) return t.$g.removeClass("fr-hidden"), r[n].self.render(), r[n] = void 0, this._processSpanCell(t, e, n);
                    if (e + 1 === this.attr("RowCount")) return r[n].self.render(), void(r[n] = void 0)
                } else 1 < i && (r[n] = {
                    self: t,
                    counter: 1,
                    need: i
                });
                if (i = t.prop("ColSpan"), a && a.self !== t) {
                    if (a.counter += 1, a.counter <= a.need && (a.self.prop("Width", a.self.prop("Width") + this.columns[n].prop("Width")), t.$g.addClass("fr-hidden"), a.self.render()), a.counter > a.need) return t.$g.removeClass("fr-hidden"), a.self.render(), a = null, this._processSpanCell(t, e, n);
                    if (a.self.parent !== t.parent) return t.$g.removeClass("fr-hidden"), a.self.render(), void(a = null)
                } else 1 < i && (t.getPosInRow() + i > this.attr("ColumnCount") && (i = Math.abs(t.getPosInRow() - this.attr("ColumnCount"))), a = {
                    self: t,
                    counter: 1,
                    need: i
                });
                a || r[n] || t.$g.removeClass("fr-hidden")
            }, this._processSpanCell.apply(this, arguments)
        },
        _generate: function() {
            var i, r, o, s, l = 0;
            this.$contentGroup && this.$contentGroup.children().length > this.attr("RowCount") * this.attr("ColumnCount") && this.$contentGroup.empty(), this._eachRow(function(n, a) {
                r = 0, s = a.prop("Height"), this._eachColumn(function(t, e) {
                    i = a.cells[t] || (a.cells[t] = this.createCell(a)), o = e.prop("Width"), i.prop({
                        Width: o,
                        Height: s,
                        Left: r,
                        Top: l
                    }), r += o, i.render(), this._processSpanCell(i, n, t), this.$contentGroup && this.$contentGroup.append(i.$g)
                }), l += s
            }), r && l && (this.prop("Width", r), this.prop("Height", l))
        },
        appendEdges: function() {},
        update: function() {
            return this._generate(), this._percentage(), this.render()
        },
        render: function() {
            return this.render = function() {
                return this.attr("ColumnCount") === this.columns.length && this.attr("RowCount") === this.rows.length || this.update(), g.default.render.apply(this, arguments), this._recalculate(), this._generate(), this.$g
            }, g.default.render.apply(this, arguments), this.$additionalMoveBlock = this.createMoveBlock(), Object(c.a)(this.content, "move"), Object(c.a)(this.$moveBlock[0], "move"), Object(c.a)(this.$moveBlock[0], "move-decor"), this.$g.append(this.$additionalMoveBlock), this.update(), this.$g
        },
        fillPropsNET: function(t) {
            function e(t, n) {
                t.each(function() {
                    var t = f()(this),
                        e = window.DSG.components[t.prop("tagName")];
                    e && ((e = e.create()).fillPropsNET(t), e.render(), n.put(e))
                })
            }
            var n, a, i, r, o, s, l, c, p, d, u, h;
            for (g.default.fillPropsNET.apply(this, arguments), n = f()("TableRow", t), i = 0, r = (a = f()("TableColumn", t)).length, this.attr("RowCount", n.length), this.attr("ColumnCount", r), this._generate(); i < r; i += 1) this.columns[i].fillPropsNET(f()(a[i]));
            for (i = 0, r = n.length; i < r; i += 1)
                for (s = (o = f()(n[i])).children(), (u = this.rows[i]).fillPropsNET(o), p = 0, d = s.length; p < d; p += 1) l = f()(s[p]), (h = u.cells[p]).fillPropsNET(l), (c = l.children()).length && e(c, h)
        },
        toXMLNET: function(o) {
            var e = this;
            return new Promise(function(r) {
                g.default.toXMLNET.call(e, o).then(function(i) {
                    i = f()(i), o = Object.assign({
                        parentNode: i[0]
                    }, o);
                    var n = [];
                    e._eachColumn(function(t, e) {
                        n.push(e.toXMLNET(o))
                    }), Promise.all(n).then(function(t) {
                        t.forEach(function(t) {
                            t && i.append(t)
                        });
                        var n = [];
                        e._eachRow(function(t, a) {
                            var e = new Promise(function(n) {
                                o.parentNode = i[0], a.toXMLNET(o).then(function(e) {
                                    if (!e) return n();
                                    e = f()(e), i.append(e);
                                    var t = [];
                                    f.a.each(a.cells, function() {
                                        o.parentNode = e[0], t.push(this.toXMLNET(o))
                                    }), Promise.all(t).then(function(t) {
                                        return t.forEach(function(t) {
                                            return e.append(t)
                                        }), n()
                                    })
                                })
                            });
                            n.push(e), Promise.all(n).then(function() {
                                return r(i[0])
                            })
                        })
                    })
                })
            })
        }
    })
}, , , function(t, e, n) {
    "use strict";
    var a = n(4),
        i = n(13),
        r = n(7);
    e.a = Object(r.a)(i.a, {
        updateExts: function() {},
        render: function(t) {
            var e, n;
            return t = t || {}, this.attr("removed") && (this.attr("removed", !1), this.$g.removeClass("fr-hidden")), this.bands && (e = t.top || 0, n = 0, a.a.get("resize-bands") && (n = a.a.get("band-indent-top") || 0), this.bands.everyOwnEntity(function(t) {
                t.balance(), t.setPosition(t.prop("Left"), e), e += t.prop("Height") + n + t.bands.getAllBandsHeight()
            })), this
        },
        remove: function() {
            return !!i.a.remove.call(this) && (this.deactivate(), this.attr("removed", !0), this.$g && this.$g.addClass("fr-hidden"), !0)
        },
        addBand: function(t, e) {
            var n = void 0;
            if (this.isPage()) n = this;
            else {
                if (!this.isBand()) return !1;
                n = this.getPage()
            }
            return !(!t || t.canBeAdded && !1 === t.canBeAdded(this)) && (t.bands.initMainCollection(n.bands), t.renderContainer(), this.put(t, e), t)
        },
        put: function(t, e) {
            this.isPage() ? this.$bands.append(t.$g) : !0 === t.attr("placeAboveParent") ? this.$bandsTop.append(t.$g) : this.$bandsBottom.append(t.$g), t._parent = this, t.attr("margin", this.attr("margin")), t.attr("padding", this.attr("padding")), t.prop("Width", this.prop("Width") || this.attr("Width")), this.bands.add(t, e)
        }
    })
}, function(t, e, n) {
    "use strict";
    var a, i, r, o, s, l, c, p;
    n.d(e, "d", function() {
        return a
    }), n.d(e, "c", function() {
        return i
    }), n.d(e, "g", function() {
        return r
    }), n.d(e, "f", function() {
        return o
    }), n.d(e, "b", function() {
        return s
    }), n.d(e, "a", function() {
        return l
    }), n.d(e, "h", function() {
        return c
    }), n.d(e, "e", function() {
        return p
    }), a = 50, i = "200", r = 100, o = 20, l = s = 200, c = 0, p = 1
}, , function(t, e, n) {
    "use strict";
    var i = n(5);
    e.a = function(t, e) {
        var n, a;
        if (t.classList) t.classList.add(e);
        else {
            if (n = new RegExp("(^|\\s)" + e + "(\\s|$)", "g"), a = Object(i.a)(t, "class"), n.test(a)) return null;
            a = ((a || "") + " " + e).replace(/\s+/g, " ").replace(/(^ | $)/g, ""), Object(i.a)(t, "class", a)
        }
        return t
    }
}, function(t, e, n) {
    "use strict";
    var a = n(4);
    e.a = function(t, e) {
        return e = a.a.get("sticky-grid") ? e || a.a.get("grid") : 1, [Math.round(Math.abs(t[0] || 0) / e) * e, Math.round(Math.abs(t[1] || 0) / e) * e]
    }
}, , function(t, e, n) {
    "use strict";
    var a, c, p, o, s, l, i, r, d, u, h, f;
    n.r(e), a = n(0), c = n.n(a), p = n(4), o = n(31), s = n(14), l = n(114), i = n(180), r = n(7), d = n(17), u = n(3), h = n(21), f = n(5), e.default = Object(r.a)(o.a, {
        create: function() {
            return this.createObject(this, {
                init: function() {
                    this.SM.add(this), this._id = "rp" + Object(d.a)(), this.unparsed = [], this.attr({
                        title: "Report",
                        info: "Report info",
                        icon: "icon-000"
                    }), this.attr({
                        isHidden: !0,
                        activated: !1,
                        removed: !1,
                        Height: 100,
                        padding: 18.89,
                        margin: p.a.get("show-band-title") ? h.a ? 40 : 100 : 0,
                        borderLines: [4, 2]
                    }), this.defaultValues = {
                        "Columns.Count": 1,
                        "Columns.Position": 0,
                        "Columns.Width": 718.11,
                        "Watermark.Enabled": !1,
                        "Watermark.Image": "",
                        "Watermark.ImageSize": "Zoom",
                        "Watermark.ImageTransparency": 0,
                        "Watermark.ShowImageOnTop": !1,
                        "Watermark.ShowTextOnTop": !0,
                        "Watermark.Text": "",
                        "Watermark.TextFill.Color": "#CCC",
                        "Watermark.TextRotation": "ForwardDiagonal",
                        "Fill.Color": "#fff",
                        Landscape: !1,
                        PaperWidth: 793.8,
                        PaperHeight: 1122.51,
                        LeftMargin: 37.8,
                        RightMargin: 37.8,
                        TopMargin: 37.8,
                        BottomMargin: 37.8,
                        RawPaperSize: 0,
                        FirstPageSource: 7,
                        OtherPagesSource: 7,
                        TitleBeforeHeader: !0
                    }, this.prop("Name", "Page"), this.initNodes(), this.bands = i.a.create(this), this.bands.initMainCollection(this.bands), this.attr = function(t, e) {
                        var n = o.a.attr.apply(this, arguments);
                        if (void 0 !== e) switch (t) {
                            case "margin":
                            case "padding":
                            case "Width":
                                this.update();
                                break;
                            case "Height":
                                this.setHeight()
                        }
                        return n
                    }
                }
            })
        },
        isReportPage: function() {
            return !0
        },
        init: function() {
            this.type = "ReportPage", this.typevcl = "TfrxReportPage", this.report = null
        },
        initNodes: function() {
            this.createWorkspace("page"), this.$bands = c()(Object(u.a)("g")), this.$extraWidthLines = c()(Object(u.a)("g", {
                class: "fr-extra-design-width-lines"
            })), this.$upControlElements = c()(Object(u.a)("g")), this.$g.data("page", this), this.$g.append(this.$bands, this.$extraWidthLines, this.$upControlElements)
        },
        updateExtraWidth: function() {
            var t, e, n, a, i, r;
            if (this.prop("ExtraDesignWidth")) {
                if (t = 5, e = this.attributes.Width, this.attributes.Width *= t, this.$extraWidthLines.is(":empty"))
                    for (n = this.bands.getAllBandsHeight(), a = this.attr("margin"), i = this.attr("padding"); 0 < t; t--) r = new l.a({
                        x1: e * t + a + i,
                        x2: e * t + a + i,
                        y1: 0,
                        y2: n + a + i
                    }), this.$extraWidthLines.append(r), r.removeClass("fr-hidden")
            } else this.$extraWidthLines && this.$extraWidthLines.children().length && this.$extraWidthLines.empty();
            return this
        },
        update: function() {
            var e = this;
            this.attributes.Width = this.prop("PaperWidth") - this.prop("LeftMargin") - this.prop("RightMargin"), this.attr("Width") < 0 && (this.attributes.Width = 0), this.updateExtraWidth(), this.updateSize(), this.bands.everyEntity(function(t) {
                t.attr({
                    margin: e.attr("margin"),
                    padding: e.attr("padding")
                }), t.prop("Width", e.attr("Width"))
            }), this.render()
        },
        balance: function() {
            this.attr("Height", this.bands.getAllBandsHeight()), this.showLines()
        },
        show: function() {
            o.a.show.apply(this, arguments), this.balance(), this.update()
        },
        activate: function() {
            var t;
            return o.a.activate.apply(this, arguments), (t = this.bands.getSelectedBand()) && t.deactivate(), this
        },
        clear: function() {
            return this.bands.clear(), this
        },
        showLines: function() {
            var t, a, i, r = this.attr("borderLines"),
                e = this.attr("padding"),
                n = this.attr("margin"),
                o = this.attr("Width"),
                s = this.attr("Height"),
                l = n + e;
            return !(!r || !Array.isArray(r)) && (t = [{
                x1: l,
                y1: e,
                x2: o + l,
                y2: e
            }, {
                x1: o + l,
                y1: e,
                x2: o + l,
                y2: s + e
            }, {
                x1: l,
                y1: e,
                x2: l,
                y2: e + s
            }], a = this.$workspace, i = c()("> line", a), p.a.get("resize-bands") || t.push({
                x1: l,
                y1: e + s,
                x2: o + l,
                y2: e + s
            }), t.forEach(function(t, e) {
                var n = i[e] || Object(u.a)("line");
                Object(f.a)(n, {
                    x1: t.x1,
                    y1: t.y1,
                    x2: t.x2,
                    y2: t.y2
                }), 2 === r.length && Object(f.a)(n, "style", "stroke-dasharray: " + r[0] + ", " + r[1] + ";"), a.append(c()(n))
            }), this)
        },
        render: function() {
            return this.$workspace.css("fill", "transparent"), o.a.render.call(this, {
                top: this.attr("padding")
            }), this.balance(), this
        },
        canHaveChildren: function() {
            return !0
        },
        fillPropsNET: function(t) {
            return o.a.fillPropsNET.apply(this, arguments), t.attr("PaperWidth") && this.prop("PaperWidth", parseFloat(s.a.toPx(t.attr("PaperWidth"), "mm"), 10)), t.attr("PaperHeight") && this.prop("PaperHeight", parseFloat(s.a.toPx(t.attr("PaperHeight"), "mm"), 10)), t.attr("LeftMargin") && this.prop("LeftMargin", parseFloat(s.a.toPx(t.attr("LeftMargin"), "mm"), 10)), t.attr("RightMargin") && this.prop("RightMargin", parseFloat(s.a.toPx(t.attr("RightMargin"), "mm"), 10)), t.attr("TopMargin") && this.prop("TopMargin", parseFloat(s.a.toPx(t.attr("TopMargin"), "mm"), 10)), t.attr("BottomMargin") && this.prop("BottomMargin", parseFloat(s.a.toPx(t.attr("BottomMargin"), "mm"), 10)), t.attr("Columns.Width") && this.prop("Columns.Width", parseFloat(s.a.toPx(t.attr("Columns.Width"), "mm"), 10)), t.attr("Watermark.Image") && this.prop("Watermark.Image", "data:image/png;base64," + t.attr("Watermark.Image")), this
        },
        fillPropsVCL: function(t) {
            return o.a.fillPropsVCL.apply(this, arguments), t.attr("PaperWidth") && this.prop("PaperWidth", parseFloat(s.a.toPx(t.attr("PaperWidth"), "mm"), 10)), t.attr("PaperHeight") && this.prop("PaperHeight", parseFloat(s.a.toPx(t.attr("PaperHeight"), "mm"), 10)), t.attr("LeftMargin") && this.prop("LeftMargin", parseFloat(s.a.toPx(t.attr("LeftMargin"), "mm"), 10)), t.attr("RightMargin") && this.prop("RightMargin", parseFloat(s.a.toPx(t.attr("RightMargin"), "mm"), 10)), t.attr("TopMargin") && this.prop("TopMargin", parseFloat(s.a.toPx(t.attr("TopMargin"), "mm"), 10)), t.attr("BottomMargin") && this.prop("BottomMargin", parseFloat(s.a.toPx(t.attr("BottomMargin"), "mm"), 10)), this
        },
        toXMLNET: function(i) {
            var r = this;
            return new Promise(function(a) {
                o.a.toXMLNET.call(r, i).then(function(e) {
                    e = c()(e), i = Object.assign({
                        parentNode: e[0]
                    }, i), e.attr("PaperWidth") && e.attr("PaperWidth", parseFloat(s.a.toUnit(e.attr("PaperWidth"), "mm"), 10)), e.attr("PaperHeight") && e.attr("PaperHeight", parseFloat(s.a.toUnit(e.attr("PaperHeight"), "mm"), 10)), e.attr("RightMargin") && e.attr("RightMargin", parseFloat(s.a.toUnit(e.attr("RightMargin"), "mm"), 10)), e.attr("LeftMargin") && e.attr("LeftMargin", parseFloat(s.a.toUnit(e.attr("LeftMargin"), "mm"), 10)), e.attr("TopMargin") && e.attr("TopMargin", parseFloat(s.a.toUnit(e.attr("TopMargin"), "mm"), 10)), e.attr("BottomMargin") && e.attr("BottomMargin", parseFloat(s.a.toUnit(e.attr("BottomMargin"), "mm"), 10)), e.attr("Columns.Width") && e.attr("Columns.Width", parseFloat(s.a.toUnit(e.attr("Columns.Width"), "mm"), 10)), e.attr("Watermark.Image") && e.attr("Watermark.Image", e.attr("Watermark.Image").replace(/^([\w:\/;]+base64,){1}/, ""));
                    var n = [];
                    r.bands.eachEntity(function(t) {
                        n.push(t.toXMLNET(i))
                    }), Promise.all(n).then(function(t) {
                        return t.forEach(function(t) {
                            t && e.append(t)
                        }), c.a.each(r.unparsed, function() {
                            e.append(this)
                        }), a(e[0])
                    })
                })
            })
        },
        toXMLVCL: function(i) {
            var r = this;
            return new Promise(function(a) {
                o.a.toXMLVCL.call(r, i).then(function(e) {
                    e = c()(e), i = Object.assign({
                        parentNode: e[0]
                    }, i), e.attr("PaperWidth") && e.attr("PaperWidth", parseFloat(s.a.toUnit(e.attr("PaperWidth"), "mm"), 10)), e.attr("PaperHeight") && e.attr("PaperHeight", parseFloat(s.a.toUnit(e.attr("PaperHeight"), "mm"), 10)), e.attr("RightMargin") && e.attr("RightMargin", parseFloat(s.a.toUnit(e.attr("RightMargin"), "mm"), 10)), e.attr("LeftMargin") && e.attr("LeftMargin", parseFloat(s.a.toUnit(e.attr("LeftMargin"), "mm"), 10)), e.attr("TopMargin") && e.attr("TopMargin", parseFloat(s.a.toUnit(e.attr("TopMargin"), "mm"), 10)), e.attr("BottomMargin") && e.attr("BottomMargin", parseFloat(s.a.toUnit(e.attr("BottomMargin"), "mm"), 10));
                    var n = [];
                    r.bands.eachEntity(function(t) {
                        n.push(t.toXMLVCL(i))
                    }), Promise.all(n).then(function(t) {
                        return t.forEach(function(t) {
                            t && e.append(t)
                        }), c.a.each(r.unparsed, function() {
                            e.append(this)
                        }), a(e[0])
                    })
                })
            })
        }
    })
}, function(t, e, n) {
    "use strict";
    e.a = function(t) {
        return !isNaN(parseFloat(t)) && isFinite(t)
    }
}, function(t, e, n) {
    "use strict";
    var a = n(0),
        c = n.n(a),
        i = n(4),
        p = n(2),
        r = n(1),
        o = n(82),
        l = n(160),
        s = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function(t) {
            return typeof t
        } : function(t) {
            return t && "function" == typeof Symbol && t.constructor === Symbol && t !== Symbol.prototype ? "symbol" : typeof t
        },
        d = {},
        u = {
            _afterFilter: function(t, e, n) {
                n.element.render && n.element.render(), "function" == typeof n.afterSetValue && n.afterSetValue.apply(n.element, arguments)
            },
            _beforeFilter: function(t) {
                "function" == typeof t.beforeSetValue && t.beforeSetValue.apply(t.element, arguments)
            },
            _applyProp: function(t, e, n, a) {
                var i, r = n.field;
                if (this._beforeFilter(r), r.value = e, "function" == typeof r.setValue) return r.setValue.apply(r.element, arguments), void this._afterFilter(t, e, r);
                i = void 0 !== r.element.attr(t) ? "attr" : "prop", r.element[i](t, e, a), this._afterFilter(t, e, r)
            },
            _processProp: function(i, r) {
                var o, t, s, l = c.a.Deferred();
                return i.is(".js-subcontrol-value") && (t = i.parents(".d-fc-json-field:first")).length && (i = t), o = i.data("control"), s = o.field, c.a.when(o.getProp(), o.getValue(i)).done(function(t, e) {
                    function n() {
                        i.removeClass("has-error"), u._applyProp(t, e, o, r), l.resolve(s)
                    }

                    function a() {
                        return i.addClass("has-error"), l.resolve(!1), !1
                    }
                    s.isValid ? s.isValid.call(s.element, e) ? n() : a() : o.isValid(e) || o.field.defaultValue && e === o.field.defaultValue ? n() : a()
                }), l.promise()
            },
            _generateGroupHeader: function(t, e) {
                if (!t.fields) return !1;

                function n() {
                    s.removeClass("icon-plus").addClass("icon-minus")
                }

                function a() {
                    s.removeClass("icon-minus").addClass("icon-plus"), o.hide()
                }
                var i = c()("<div>"),
                    r = c()("<div>"),
                    o = c()("<div>"),
                    s = c()("<div>"),
                    l = c()("<span>");
                return o.attr("tabIndex", -1), i.addClass("cstn-panel"), r.addClass("cstn-panel-header"), o.addClass("cstn-panel-body"), s.addClass("fa cstn-toogle"), e && (i.addClass("cstn-root"), o.addClass("fgs-opened")), l.text(p.a.tr(t.label)), r.append(s, l), i.append(r, o), void 0 !== d[t.label] ? !1 === d[t.label] ? a() : n() : t.opened || e ? n() : a(), i.data("field", t), {
                    $main: i,
                    $header: r,
                    $body: o,
                    $toggle: s,
                    $headerTitle: l
                }
            },
            _bindMainProps: function(t, e) {
                return e.prop = t, e.element = this, e.getValue ? e.value = e.getValue.call(this, t, e) : (e.value = this.prop(t), void 0 === e.value && (e.value = this.attr(t), void 0 === e.value && (e.value = e.defaultValue))), e
            },
            _buildMap: function() {
                return function n(t, a, i) {
                    var r, o, s = this;
                    c.a.each(t, function(t, e) {
                        if (r = u._generateGroupHeader(e, i)) return a.append(r.$main), n.call(s, e.fields, r.$body), r;
                        u._bindMainProps.call(s, t, e), e.control = l.a.getFor(e), (o = e.control.self.$control).is(".d-fc-json-field") ? (o.on("change keyup", "input", u._changeControl), o.on("focus", "input", u._focusOnControl), o.on("blur", "input", u._blurFromControl)) : (o.on("change keyup", u._changeControl), o.is(":not(select[multiple])") && (o.on("focus", u._focusOnControl), o.on("blur", u._blurFromControl))), a.append(e.control.$main)
                    })
                }.apply(this, arguments)
            },
            _clickOnHeader: function(t) {
                var e = c()(this),
                    n = e.find(".cstn-toogle"),
                    a = e.parent(),
                    i = a.data("field");
                if (c()(t.target).is(".d-fc-exp-but")) return !1;
                n.is(".icon-minus") ? (i.opened = !1, e.next(".cstn-panel-body").slideUp(100, function() {
                    c()(this).removeClass("fgs-opened"), n.removeClass("icon-minus").addClass("icon-plus")
                })) : (i.opened = !0, e.next(".cstn-panel-body").slideDown(100, function() {
                    c()(this).addClass("fgs-opened"), n.removeClass("icon-plus").addClass("icon-minus")
                })), a.is(".cstn-root") && (d[i.label] = i.opened)
            },
            _focusOnControl: function() {
                i.a.set("hotkeyProhibited", !0)
            },
            _updateMenu: Object(o.a)(function() {
                r.a.trigger("update-menu")
            }, 500),
            _setControlValue: function(a, t) {
                var i = this._updateMenu;
                c.a.when(this._processProp(a, t)).done(function(t) {
                    var e, n = a.data("ref");
                    t && (n ? (e = t.control.self.getValue(a, !0), Array.isArray(e) || "object" === (void 0 === e ? "undefined" : s(e)) || ("checkbox" === t.type ? n.prop("checked", e) : n.val(e))) : i())
                })
            },
            _changeControl: function(t) {
                var e = !0;
                t.keyCode && 13 !== t.keyCode && (e = !1), u._setControlValue(c()(this), e)
            },
            _blurFromControl: function() {
                i.a.set("hotkeyProhibited", !1), u._setControlValue(c()(this), !0)
            },
            build: function(t, e) {
                var n = c()("<form>");
                return n.addClass("form-horizontal"), n.attr("role", "form"), e && (this._buildMap.call(t, e, n, !0), n.on("click", ".cstn-panel-header", this._clickOnHeader)), n
            }
        };
    e.a = u
}, , , , , , , , , function(t, e, n) {
    "use strict";
    var a = n(90),
        i = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function(t) {
            return typeof t
        } : function(t) {
            return t && "function" == typeof Symbol && t.constructor === Symbol && t !== Symbol.prototype ? "symbol" : typeof t
        };
    e.a = function() {
        var t = [].slice.call(arguments);
        if (2 === t.length) {
            if (t.push("http://www.w3.org/1999/xlink"), "string" == typeof t[1]) return a.a.apply(this, t);
            if ("object" === i(t[1])) return a.c.apply(this, t)
        } else if (3 === t.length) return t.push("http://www.w3.org/1999/xlink"), a.b.apply(this, t);
        throw new Error("something went wrong")
    }
}, function(t, e, a) {
    "use strict";

    function f(t, e) {
        for (var n = t.length; n--;) t[n][e]()
    }

    function h(t, e) {
        return Math.sqrt(Math.pow(t[0] - e[0], 2) + Math.pow(t[1] - e[1], 2))
    }

    function i(t) {
        var n, e, a, l = window.DSG.head,
            d = !1,
            u = this.data = (n = {}, e = null, a = {}, {
                $svg: t.$workspace,
                report: t,
                pageX: 0,
                pageY: 0,
                addAction: function(t, e) {
                    return n[t] = e, this
                },
                getAction: function(t) {
                    return n[t]
                },
                activate: function(t) {
                    return e = t, this
                },
                deactivate: function() {
                    return e && (a[e] = null, e = null), this
                },
                getActive: function() {
                    return e
                },
                set: function(t) {
                    return a[e] = t, this
                },
                get: function() {
                    return a[e]
                }
            });
        this.getHandlers = function(t) {
            function c(t) {
                Object(I.a)(t, function(t) {
                    var e = u.report.$workspace[0],
                        n = e.getBoundingClientRect(),
                        a = e.scrollTop - (e.clientTop || 0) - n.top,
                        i = e.scrollLeft - (e.clientLeft || 0) - n.left,
                        r = u.report.attr("data-scale"),
                        o = t.originalEvent,
                        s = o.changedTouches,
                        l = o.touches;
                    return void 0 !== t.clientY ? a += t.clientY : a += l && l[0] ? l[0].clientY : s[0].clientY, void 0 !== t.clientX ? i += t.clientX : i += l && l[0] ? l[0].clientX : s[0].clientX, {
                        x: Math.round(i / r),
                        y: Math.round(a / r)
                    }
                }(t)), Object(z.a)(t);
                var e = l.$main.offset();
                return t.pageX -= e.left, t.pageY -= e.top, t
            }

            function e(t) {
                var e = _(C()(t.target)),
                    n = !0;
                return x.a.trigger("remove-context-menus"), e && e.rightClick && (n = e.rightClick(t)), n
            }
            var n, a, i, r = void 0,
                o = void 0,
                p = (i = null, {
                    start: function(t) {
                        (n = s(t)) && (a = [n.pageX, n.pageY], i = setTimeout(function() {
                            return e.call(this, t)
                        }.bind(this), 1500))
                    },
                    move: function(t) {
                        (n = s(t)) && (!a || n.pageX > a[0] + 10 || n.pageX < a[0] - 10 || n.pageY > a[1] + 10 || n.pageY < a[1] - 10) && (clearTimeout(i), a = i = null)
                    },
                    cancel: function() {
                        clearTimeout(i), i = null
                    }
                });

            function s(t) {
                if (T.a) {
                    var e = t.originalEvent && t.originalEvent.touches;
                    if (e) return e[0]
                }
            }
            return "function" == typeof t && t.call(u), {
                rightClick: e,
                mouseover: function(t) {
                    var e, n = C()(t.target),
                        a = _(n);
                    a && (e = a.getPage()) && e.attr("activated") && a.mouseover && a.mouseover(t)
                },
                mouseout: function(t) {
                    var e, n = C()(t.target),
                        a = _(n);
                    a && (e = a.getPage()) && e.attr("activated") && a.mouseover && a.mouseout(t)
                },
                dblclick: function(t) {
                    var e = C()(t.target),
                        n = _(e);
                    n && n.dblclick && n.dblclick(t)
                },
                start: function(t) {
                    var e, n = C()(t.target),
                        a = _(n),
                        i = ".d-mc:not([disabled])[data-component],.rt-content[data-component]",
                        r = ".resizing-component,.resizing-line",
                        o = ".fr-angle-slider",
                        s = ".resizing-band",
                        l = ".fr-workspace";
                    return n.is(".js-disable-common") ? null : n.parents(".fr-lock_window").length || u.getActive() || n.parents(".d-cm").length ? null : (p.start.apply(this, arguments), c(t), x.a.trigger("remove-context-menus"), a && !1 === a.mouseStart(t) ? null : (n.is(i) || n.parents(i).length ? (n.is(i) || (n = n.parents(i)), Object(I.a)(t, {
                        target: n[0]
                    }), u.activate("creating-component"), e = u.getAction("creating-component")) : n.is(".move") || n.parents(".move").length ? (u.activate("moving-component"), e = u.getAction("moving-component")) : n.is(r) || n.parents(r).length ? (u.activate("resizing-component"), e = u.getAction("resizing-component")) : n.is(o) || n.parents(o).length ? (u.activate("angle-component"), e = u.getAction("angle-component")) : n.is(s) || n.parents(s).length ? (u.activate("resizing-band"), e = u.getAction("resizing-band")) : (n.is(l) || n.parents(l).length) && (u.activate("selection"), e = u.getAction("selection")), g.a.set("showHelpers", !1), e && e.start ? e.start.call(u, t, a) : void 0))
                },
                move: function(t) {
                    var e = u.getActive();
                    if (u.pageX = t.pageX, u.pageY = t.pageY, p.move.apply(this, arguments), e && !d) {
                        if (!(e = u.getAction(e))) return;
                        if (e = e.move) return c(t), e.call(u, t)
                    }
                },
                end: function(t) {
                    var e, n = u.getActive();
                    return p.cancel.apply(this, arguments), (n = n && u.getAction(n)) && ((n = n.end) && (c(t), e = n.call(u, t)), u.deactivate()), g.a.set("showHelpers", !0), e
                },
                zoomstart: function(t) {
                    var e, n = t.touches;
                    return n && 2 === n.length ? (d = !0, e = Math.sqrt(Math.pow(n[0].clientX - n[1].clientX, 2) + Math.pow(n[0].clientY - n[1].clientY, 2)), o = r = e, !1) : null
                },
                zoommove: function(t) {
                    if (!d) return null;
                    var e = t.touches,
                        n = h([e[0].clientX, e[0].clientY], [e[1].clientX, e[1].clientY]),
                        a = 0;
                    return 50 < Math.abs(o - n) && (a = r < n ? .1 : -.1, a = u.report.attr("data-scale") + a, x.a.trigger("scale-page", a), o = n), r = n, !1
                },
                zoomend: function() {
                    d = !1
                }
            }
        }
    }
    var n, r, o, s, y, l, c = a(0),
        C = a.n(c),
        g = a(4),
        u = a(13),
        p = a(42),
        d = a(16),
        m = a(7),
        b = a(17),
        v = Object(m.a)(d.a, {
            type: "Style",
            init: function() {
                this.report = null
            },
            create: function() {
                return this.createObject(this, {
                    init: function() {
                        this._id = "s" + Object(b.a)(), this.prop({
                            Name: "Style"
                        })
                    }
                })
            },
            isStyle: function() {
                return !0
            }
        }),
        S = a(15),
        x = a(1),
        w = a(24),
        T = (a(350), a(21)),
        B = a(39),
        k = a(30),
        $ = a(156),
        P = 500,
        D = T.a ? 30 : 15,
        O = "component-over",
        M = function() {
            function r(t, e) {
                e.css({
                    left: t.pageX - e.outerWidth(!0) - D,
                    top: t.pageY - e.outerHeight(!0) - D
                })
            }

            function l(t, e) {
                var n = e.find(".d-pulse-dot")[0].getBoundingClientRect(),
                    a = p.attr("data-scale"),
                    i = p.getCurrentPage(),
                    r = t.x - i.attr("margin") - i.attr("padding"),
                    o = t.y;
                return {
                    x: r -= D / a,
                    y: o -= D / a,
                    left: n.left,
                    top: n.top
                }
            }
            var o = this,
                c = void 0,
                e = window.DSG.head,
                p = this.report;
            C()(document).on("keydown", function(t) {
                var e = o.get();
                e && e.$container && 27 === t.keyCode && e.$container.remove()
            }), this.addAction("creating-component", {
                start: function(n) {
                    function t() {
                        var t = o.get(),
                            e = a.parent()[0].element;
                        r(n, t.$container), i.css("visibility", "visible"), t.component = a.data("component"), e && (t.view = e.view || e.getView && e.getView(a), t.bindableControl = e.prop && e.prop("BindableControl") || "Text"), c = null
                    }
                    var a = C()(n.target),
                        i = a.clone();
                    C()(".will-be-created").remove(), i.removeClass("d-wb selected"), i.addClass("will-be-created"), i.css({
                        left: n.pageX,
                        top: n.pageY
                    }), i.append('<div class="d-pulse-dot"><div class="d-dot"></div><div class="d-pulse"></div></div>'), C()(".add,.remove", i).remove(), e.put(i), this.set({
                        $container: i
                    }), T.a || !a.is(".d-mc") ? c = setTimeout(t, P) : t()
                },
                move: function(t) {
                    var e, n, a, i = this.get();
                    return i && i.component ? (e = l(t, i.$container), i.$container.addClass("fr-hidden"), n = C()(document.elementFromPoint(e.left, e.top)), i.$container.removeClass("fr-hidden"), r(t, i.$container), (a = C()("." + O)).length && C.a.each(a, function() {
                        Object(k.a)(this, O)
                    }), i.existingComponent && i.existingComponent.deleteComponentOver(t, e), i.existingComponent = n.parents(".component.droppable-component:first"), i.existingComponent.length && i.view && i.existingComponent.is(".droppable-view") && ~["Text", "Custom"].indexOf(i.bindableControl) ? (i.existingComponent = i.existingComponent[0].component, Object(B.a)(i.existingComponent.$moveBlock[0], O), i.existingComponent.creatingComponentOver(t, e)) : delete i.existingComponent, !1) : !c
                },
                end: function(t) {
                    var e, n, a = this.get(),
                        i = a.$container,
                        r = p.getCurrentPage(),
                        o = C.a.contains(document.documentElement, i[0]),
                        s = l(t, i);
                    i.remove(), c ? clearTimeout(c) : (o && (a.existingComponent ? (a.existingComponent.creatingComponentEnd(t, s, Object($.a)(a.view)), Object(k.a)(a.existingComponent.$moveBlock[0], O), a.existingComponent.render()) : (e = null, n = this.report.$node[0].getBoundingClientRect(), t.clientX >= n.left && t.clientX <= n.right && t.clientY >= n.top && t.clientY <= n.bottom && (r.bands ? (e = r.bands.findInsideCoord([s.x, s.y])) && (s.y -= e.prop("Top")) : e = r), e || (e = r.bands ? r.bands.getSelectedBand() || r.bands.first() : r, s.x = 0, s.y = r.attr("padding")), x.a.trigger("add-component", a.component, e, {
                        remember: !0,
                        view: a.view,
                        left: s.x,
                        top: s.y
                    }).done(function(t) {
                        t && "SubreportObject" !== t.type && p.drop(t)
                    }), e.creatingComponentEnd(t, s, Object($.a)(a.view)))), this.set(null))
                }
            })
        },
        E = a(40),
        A = a(84),
        j = function() {
            function s(t) {
                x.a.trigger("put-component", t.container, t.component, t.leftLastCoord, t.topLastCoord);
                var e = t.container.prop("Height");
                return t.container.prop("Height", t.bandHeight), x.a.trigger("balance-band", t.container, e), !0
            }

            function n(t, e) {
                for (var n, a, i = 1 < t.length || e && e.ctrlKey ? function(t) {
                        t.component.activate(), x.a.trigger("update-menu"), x.a.trigger("update-info")
                    } : function(t) {
                        x.a.trigger("activate", t.component)
                    }, r = [], o = 0; o < t.length; o += 1) a = (n = t[o]).component.getContainer(), n.container ? (s(n), r.push(n.container)) : (n.container = d.drop(n.component), n.container && r.push(n.container)), i(n), n.component.movingEnd(e, n), n.container !== a && a.isBand() && r.push(a);
                for (r = Object(A.a)(r), o = 0; o < r.length; o += 1) x.a.trigger("balance-band", r[o])
            }

            function c(t, e, n) {
                var a = t.prop("Left"),
                    i = t.prop("Top");
                return {
                    component: t,
                    prevX: e,
                    prevY: n,
                    leftCoord: a,
                    topCoord: i,
                    leftLastCoord: a,
                    topLastCoord: i
                }
            }

            function p(t, e) {
                var n, a, i, r, o, s = e.component;
                if (s.canMove() && e.prevX && e.prevY) return n = e.leftCoord + (t.x - e.prevX), a = e.topCoord + (t.y - e.prevY), i = s.prop("Left"), r = s.prop("Top"), o = void 0, o = s.isDialogControl() ? Object(E.a)([n - i, a - r], 4) : Object(E.a)([n - i, a - r]), e.leftLastCoord = i < n ? i + o[0] : i - o[0], e.topLastCoord = r < a ? r + o[1] : r - o[1], s.setPosition.apply(s, [e.leftLastCoord, e.topLastCoord])
            }

            function a(e) {
                var n = [];
                return (u.a.state.get("in_move") || []).forEach(function(t) {
                    !1 !== t.movingStart(e) && n.push(c.call(this, t, e.x, e.y))
                }.bind(this)), n
            }
            var d = this.report;
            this.addAction("moving-component", {
                start: function(t, e) {
                    for (var n, a = [], i = [], r = d.getCurrentPage(), o = r.bands || r.components, s = e.attr("activated") ? o.getSelectedComponents() : [e], l = s.length; l--;)(o = (e = s[l]).getContainer()) && !1 !== e.movingStart(t) && (e.deactivate(), e.setState("in_move"), n = c.call(this, e, t.x, t.y), i.push(n), a.push(Object.assign({
                        container: o,
                        bandHeight: o.prop("Height")
                    }, n)), p(t, n));
                    this.set({
                        data: i,
                        init: a
                    })
                },
                move: function(t) {
                    var e, n = this.get();
                    if (n && n.data.length) {
                        if (n.data)
                            for (n.moved = !0, n = n.data, e = 0; e < n.length; e += 1) p(t, n[e]) && (n[e].component.moving(t, n[e]), n[e].moved = !0)
                    } else n = a.call(this, t), this.set({
                        data: n
                    });
                    return !1
                },
                end: function(t) {
                    var e = this.get();
                    e && (e.init && e.moved && S.a.push({
                        context: this,
                        func: n,
                        undoData: [e.init, t],
                        redoData: [e.data, t]
                    }), (e = e.data) && e.length || (e = a.call(this, t)), n.call(this, e, t), this.set(null))
                }
            })
        },
        F = function() {
            var l = this.report,
                c = window.DSG.head.$main;
            this.addAction("resizing-component", {
                start: function(t, e) {
                    var n = C()(t.target),
                        a = e.getResizing(n);
                    if ((a.component = e).collection) a.container = e.collection.container, a.oldWidth = e.prop("Width"), a.oldHeight = e.prop("Height"), a.oldLeft = e.prop("Left"), a.oldTop = e.prop("Top"), a.oldBandHeight = a.container.prop("Height");
                    else {
                        if (!e.getTable) return;
                        a.container = e.getTable()
                    }
                    e.setState("in_resize"), this.set(a), e.hideUpControls(), e.resizingStart(t, this.get())
                },
                move: function(t) {
                    var e, n, a, i, r, o = this.get(),
                        s = l.attr("data-scale");
                    return o && (e = o.component, n = o.container.$workspace[0].getBoundingClientRect(), a = c[0].getBoundingClientRect(), n = {
                        left: n.left - a.left,
                        top: n.top - a.top
                    }, i = (t.pageX - n.left) / s, r = (t.pageY - n.top) / s, o.call(o.component, i, r), o.changed = !0, e.resizing(t, o), e.render()), !1
                },
                end: function(t) {
                    var e, n, a = this.get();
                    a && (e = a.component, n = a.container, e.setState("normal"), e.resizingEnd(t, a), n.isBand() && x.a.trigger("balance-band", n), e.showUpControls(), e.render(), x.a.trigger("activate", e), x.a.trigger("update-properties-panel", e), a.changed && a.oldBandHeight && (S.a.push({
                        context: e,
                        func: function(t, e, n, a, i) {
                            var r, o = this.collection.container;
                            this.deactivate(), this.prop({
                                Width: e,
                                Height: n
                            }), this.setPosition(a, i), this.render(), x.a.trigger("activate", this), x.a.trigger("update-properties-panel", this), r = o.prop("Height"), o.prop("Height", t), x.a.trigger("balance-band", o, r)
                        },
                        undoData: [a.oldBandHeight, a.oldWidth, a.oldHeight, a.oldLeft, a.oldTop],
                        redoData: [e.collection.container.prop("Height"), e.prop("Width"), e.prop("Height"), e.prop("Left"), e.prop("Top")]
                    }), x.a.trigger("update-menu")), this.set(null))
                }
            })
        },
        L = function() {
            this.addAction("angle-component", {
                start: function(t, e) {
                    var n = {
                        component: e,
                        direction: +(t.target.dataset.direction || 0),
                        original: {
                            angle: e.prop("Angle")
                        },
                        prevX: t.x
                    };
                    this.set(n), e.hideUpControls(), e.rotatingStart(t, n)
                },
                move: function(t) {
                    var e, n, a = this.get();
                    return a && (e = a.component, 10 < Math.abs(a.prevX - t.x) && (n = void 0, n = t.x > a.prevX ? 10 : -10, 1 === a.direction && (console.log(123), n = -n), e.prop("Angle", e.prop("Angle") + n), a.prevX = t.x, e.rotating(t, a))), !1
                },
                end: function(t) {
                    var e, n = this.get();
                    n && ((e = n.component).rotatingEnd(t, n), e.showUpControls(), x.a.trigger("activate", e), x.a.trigger("update-properties-panel", e), this.set(null))
                }
            })
        },
        R = a(114),
        N = function() {
            var o, a = this.report;
            this.addAction("resizing-band", {
                start: function(t, e) {
                    e.resizingStart(t), o = new R.a({
                        x1: 0,
                        x2: e.prop("Width") + e.attr("margin") + e.attr("padding")
                    }), e.$g.append(o), this.set({
                        band: e,
                        oldHeight: e.prop("Height"),
                        changed: !1
                    })
                },
                move: function(t) {
                    var e, n = this.get(),
                        a = n.band,
                        i = t.y,
                        r = a.prop("Height");
                    return a.canResizeY() ? (a.attr("threshold") >= i && (i = a.attr("threshold")), i -= a.prop("Top"), e = Object(E.a)([null, i - r])[1], (e = (e = r < i ? r + e : r - e) < 0 ? 0 : e) !== r && (n.changed = !0, a.prop("Height", e), o.removeClass("fr-hidden"), o.attr({
                        y1: e + a.bands.getTopBandsHeight(),
                        y2: e + a.bands.getTopBandsHeight()
                    })), !1) : null
                },
                end: function(t) {
                    var e = this.get(),
                        n = e.band;
                    n.resizingEnd(t), x.a.trigger("balance-band", n, e.oldHeight), x.a.trigger("activate", n), x.a.trigger("update-properties-panel", n), e.changed && (S.a.push({
                        context: this,
                        func: function(t, e) {
                            var n = t.prop("Height");
                            t.prop("Height", e), x.a.trigger("balance-band", t, n), x.a.trigger("activate", t), x.a.trigger("update-properties-panel", t), a.getCurrentPage().render()
                        },
                        undoData: [n, e.oldHeight],
                        redoData: [n, n.prop("Height")]
                    }), x.a.trigger("update-menu")), o && o.remove(), this.set(null)
                }
            })
        },
        H = a(3),
        W = a(5),
        V = function() {
            var h = this.report;
            this.addAction("selection", {
                start: function(t, e) {
                    if (T.a) return e && this.set({
                        element: e
                    }), !0;
                    var n = h.getCurrentPage();
                    n.$selection ? n.$selection.find("rect").attr({
                        width: 0,
                        height: 0
                    }) : (n.$selection = C()(Object(H.a)("g")), n.$selection.append(C()(Object(H.a)("rect")))), n.$workspace.append(n.$selection), Object(W.a)(n.$selection[0], {
                        transform: "translate(" + t.x + "," + t.y + ")",
                        class: "selection",
                        left: t.x,
                        top: t.y
                    }), this.set({
                        initLeft: t.x,
                        initTop: t.y,
                        element: e
                    })
                },
                move: function(t) {
                    var e, n, a, i, r, o, s = this.get();
                    return !s || !s.initLeft && !s.initTop || ((e = h.getCurrentPage()).$selection.removeClass("fr-hidden"), n = e.$selection.find("rect"), a = s.initLeft, i = s.initTop, r = t.x - a, o = t.y - i, r < 0 && (r = Math.abs(r), a = t.x), o < 0 && (o = Math.abs(o), i = t.y), n.attr({
                        width: 0 === r ? 1 : r,
                        height: 0 === o ? 1 : o
                    }), Object(W.a)(e.$selection[0], {
                        transform: "translate(" + a + "," + i + ")",
                        left: a,
                        top: i
                    }), !1)
                },
                end: function(t) {
                    var e, n, a, i, r, o, s, l = this.get(),
                        c = h.getCurrentPage(),
                        p = c.$selection,
                        d = void 0,
                        u = void 0;
                    c && !c.isCode() ? (e = c.attr("margin") + c.attr("padding"), n = c.bands || c.components, l && (l.element && !p ? x.a.trigger("activate", l.element) : p && l.initLeft && l.initTop && (u = p.find("rect"), a = parseInt(p.attr("left"), 10) - e, i = parseInt(p.attr("top"), 10), r = parseInt(u.attr("width"), 10), o = parseInt(u.attr("height"), 10), p.addClass("fr-hidden"), f(n.getSelectedComponents(), "deactivate"), 1 === (s = n.componentsIn([a, a + r || a], [i, i + o || i])).length ? x.a.trigger("activate", s[0]) : (d = 10 < r && 10 < o && n.findInsideCoord ? n.findInsideCoord([t.x - e, t.y]) : l.element, x.a.trigger("activate", d || c), f(s, "activate"), x.a.trigger("update-info"), x.a.trigger("update-menu")), d && d.isBand() && x.a.trigger("balance-band", d)))) : p.addClass("fr-hidden")
                }
            })
        },
        z = a(134),
        I = a(6),
        _ = function(t) {
            var e, n = t.parents(".component:first");
            return n.length ? n[0].component : (n = t.parents(".band:first")).length ? n[0].band : (n = t.parents(".rt-node").data("entity"), e = window.DSG.currentReport, n || e && e.getCurrentPage())
        };
    C.a.event.special.dblclick = {
        setup: function() {
            C()(this).on(T.a ? "touchend.dblclick" : "click.dblclick", C.a.event.special.dblclick.handler)
        },
        teardown: function() {
            C()(this).off(T.a ? "touchend.dblclick" : "click.dblclick", C.a.event.special.dblclick.handler)
        },
        handler: function(t) {
            if (t.pageX && t.pageY) {
                var e, n = C()(document.elementFromPoint(t.pageX, t.pageY)),
                    a = n.data("lt"),
                    i = +new Date,
                    r = i;
                a && (r -= a.time || 0, e = h([a.pageX, a.pageY], [t.pageX, t.pageY])), 20 < r && r < 500 && e < 10 ? (n.data("lt", null), n.trigger("dblclick")) : n.data("lt", {
                    time: i,
                    pageX: t.pageX,
                    pageY: t.pageY
                })
            }
        }
    }, n = function(n) {
        var e = window.DSG.head.$node,
            t = n.movements,
            a = void 0;
        t ? a = t.handlers : (t = new i(n), a = t.getHandlers(function() {
            M.call(this), j.call(this), F.call(this), L.call(this), N.call(this), V.call(this)
        }), n.movements = {
            data: t.data,
            handlers: a
        }), e.off("touchstart touchmove touchend mousedown mousemove mouseup dblclick").on("touchstart mousedown", function(t) {
            if ("touchstart" === t.type && e.off("mousedown"), !(0 === t.button && g.a.get("scroll-on-space") || 2 === t.button)) return a.start.call(this, t)
        }).on("touchmove mousemove", function(t) {
            if ("touchmove" === t.type && e.off("mousemove"), !g.a.get("scroll-on-space")) return a.move.call(this, t)
        }).on("touchend mouseup", function(t) {
            if ("touchend" === t.type && e.off("mouseup"), !g.a.get("scroll-on-space")) return a.end.call(this, t)
        }).on("dblclick", a.dblclick).on("mousedown", ".d-report", function(t) {
            if (g.a.get("scroll-on-space")) {
                var e = C()(this);
                n.$node.addClass("fr-grabbing-page"), e.data("start-scrolling-x", t.pageX).data("start-scrolling-y", t.pageY).data("start-scrolling-pos-left", e.scrollLeft()).data("start-scrolling-pos-top", e.scrollTop())
            }
        }).on("mousemove", ".d-report", function(t) {
            var e, n, a = C()(this),
                i = a.data("start-scrolling-x"),
                r = a.data("start-scrolling-y"),
                o = a.data("start-scrolling-pos-left"),
                s = a.data("start-scrolling-pos-top");
            if (void 0 !== i && void 0 !== r) return e = i - t.pageX, n = r - t.pageY, a.scrollLeft(o + e), a.scrollTop(s + n), !1
        }).on("mouseup", ".d-report", function() {
            n.$node.removeClass("fr-grabbing-page"), C()(this).removeData("start-scrolling-x").removeData("start-scrolling-y").removeData("start-scrolling-pos-left").removeData("start-scrolling-pos-top")
        }), T.a ? (e.on("touchstart", a.zoomstart), e.on("touchmove", a.zoommove), e.on("touchend", a.zoomend)) : (e.off("contextmenu").on("contextmenu", function(t) {
            if (2 === t.button) return a.rightClick.call(this, t)
        }), e.off("mouseover").on("mouseover", a.mouseover), e.off("mouseout").on("mouseout", a.mouseout), e.on("mousewheel DOMMouseScroll", function(t) {
            var e;
            if (t.ctrlKey) return e = t.originalEvent.detail && t.originalEvent.detail < 0 || t.originalEvent.wheelDelta && 0 < t.originalEvent.wheelDelta ? .1 : -.1, x.a.trigger("scale-page", n.attr("data-scale") + e), !1
        }))
    }, r = a(118), o = a(154), s = a(23), y = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function(t) {
        return typeof t
    } : function(t) {
        return t && "function" == typeof Symbol && t.constructor === Symbol && t !== Symbol.prototype ? "symbol" : typeof t
    }, l = new s.a, e.a = Object(m.a)(u.a, {
        type: "Report",
        typevcl: "TfrxReport",
        _init: function() {
            this.prop("Name", "Report"), this._id = "report" + Object(b.a)(), this.pages = w.a.create(this), this.styles = w.a.create(this), this.movements = null, this.unparsedDict = [], this.unparsed = [], this.attr({
                grid: !0,
                "data-scale": g.a.get("scale") || 1,
                title: "Report",
                info: "Report info",
                icon: "icon-134",
                Top: 0,
                Bottom: 0,
                Left: 0
            }), this.defaultValues = {
                "ReportInfo.PreviewPictureRatio": .1,
                "ReportInfo.SavePreviewPicture": !1,
                "ReportInfo.SaveMode": "All",
                ScriptLanguage: "CSharp",
                StoreInResources: !0,
                ConvertNulls: !0,
                DoublePass: !1,
                InitialPageNumber: 1,
                UseFillCache: !1,
                AutoFillDataSet: !0,
                Compressed: !1,
                MaxPages: 0,
                SmoothGraphics: !1,
                TextQuality: "Default",
                "PrintSettings.Collate": !0,
                "PrintSettings.Copies": 1,
                "PrintSettings.Duplex": "Default",
                "PrintSettings.PageRange": "All",
                "PrintSettings.PagesOnSheet": "One",
                "PrintSettings.PaperSource": 7,
                "PrintSettings.Printer": "Default",
                "PrintSettings.PrintMode": "Default",
                "PrintSettings.PrintOnSheetHeight": 297,
                "PrintSettings.PrintOnSheetRawPaperSize": 0,
                "PrintSettings.PrintOnSheetWidth": 210,
                "PrintSettings.PrintPages": "All",
                "PrintSettings.PrintToFile": !1,
                "PrintSettings.Reverse": !1,
                "PrintSettings.SavePrinterWithReport": !1,
                "PrintSettings.ShowDialog": !0
            }, this.prop({
                "ReportInfo.Created": new Date,
                "ReportInfo.Modified": new Date,
                "ReportInfo.CreatorVersion": "1.0.0.0"
            }), this.$node = C()("<div>"), this.$wrap = C()("<div>"), this.$node.append(this.$wrap), this.hide(), this.$node.addClass("d-report"), this.$wrap.addClass("fr-workspace"), this.$workspace = C()("<div>"), this.workspace = this.$workspace.get(0), this.$workspace.data("report", this), this.$wrap.append(this.$workspace), this.createCode(), this.$node.on("dragstart", function() {
                return !1
            }), n(this), this.attr = function(t, e) {
                var n = this._super.attr.apply(this, arguments);
                if ("left" === t || "top" === t) {
                    if (void 0 === e) return this.workspace.getBoundingClientRect()[t];
                    this.$node.css(t, e)
                }
                return n
            }
        },
        isReport: function() {
            return !0
        },
        canNotBeSaved: function() {
            return "Deny" === this.prop("ReportInfo.SaveMode")
        },
        create: function() {
            var t = this.createObject(this);
            return t._init(), t
        },
        show: function() {
            return this.$node.removeClass("fr-hidden"), this
        },
        hide: function() {
            return this.$node.addClass("fr-hidden"), this
        },
        afterInitShow: function() {
            return this.pages.eachEntity(function(t) {
                t.afterInitShow(), void 0 !== t.bands && t.bands.eachEntity(function(t) {
                    t.afterInitShow(), void 0 !== t.components && t.components.eachEntity(function(t) {
                        t.afterInitShow()
                    })
                }), void 0 !== t.components && t.components.eachEntity(function(t) {
                    t.afterInitShow()
                })
            }), this
        },
        updateFilters: function() {
            return this.pages.eachEntity(function(t) {
                t.updateFilters()
            }), this
        },
        updateDefs: function() {
            return this.pages.eachEntity(function(t) {
                t.updateDefs()
            }), this
        },
        findContainerForDropping: function(t, e, a) {
            var i = C()(document.elementFromPoint(t + 1, e + 1)),
                n = function t(e) {
                    var n = ".component.droppable-component:first";
                    return (e = e || i.parents(n)).length ? e[0].component.canHaveChildren(a.type) ? e[0].component : t(e.parents(n)) : null
                },
                r = n();
            return r = r || ((i = i.parents(".band:first")).length ? i[0].band : null)
        },
        drop: function(t) {
            var e, n, a, i, r = t.$workspace[0].getBoundingClientRect(),
                o = this.attr("left"),
                s = this.attr("top"),
                l = this.attr("data-scale");
            return t.$g.addClass("fr-hidden"), e = this.findContainerForDropping(r.left, r.top, t) || t.collection.container, t.$g.removeClass("fr-hidden"), t.setState("normal"), n = e.$workspace[0].getBoundingClientRect(), a = r.left - o - (n.left - o), i = r.top - s - (n.top - s), a /= l, i /= l, x.a.trigger("put-component", e, t, a, i), e
        },
        createPage: function(t) {
            var e, n = p.default.create();
            return this.pages.add(n), n.report = this, n.$wrap = C()("<div>"), n.$wrap.append(n.$workspace), this.$workspace.append(n.$wrap), n.update(), n.render(), n.prop("Name", n.formName("Page")), t || ((e = [a(101).default, a(102).default, a(83).default, a(103).default])[0] && n.addBand(e[0].create()), e[1] && n.addBand(e[1].create()), e[2] && n.addBand(e[2].create()), e[3] && n.addBand(e[3].create())), n
        },
        createStyle: function() {
            var t = v.create();
            return this.styles.add(t), t.report = this, t
        },
        createDialog: function() {
            var t = a(100).default;
            return t = t.create(), this.pages.add(t), (t.report = this).$workspace.append(t.$workspace), t.render(), t.prop("Name", t.formName("Form")), t
        },
        createDialogDefaultSet: function(n) {
            return C.a.when(x.a.trigger("add-component", "ButtonControl", n, {
                remember: !1,
                left: 128,
                top: 240
            }), x.a.trigger("add-component", "ButtonControl", n, {
                remember: !1,
                left: 211,
                top: 240
            })).done(function(t, e) {
                t.prop("Text", "OK"), e.prop("Text", "Cancel"), t.prop("Name", t.formName("btnOk")), e.prop("Name", e.formName("btnCancel")), t.render(), e.render(), n.prop("AcceptButton", t.toString()), n.prop("CancelButton", e.toString())
            })
        },
        createCode: function() {
            var t = a(228).default;
            return this.code = t.create(), this.pages.add(this.code), (this.code.report = this).$node.append(this.code.$workspace), this.code
        },
        removePage: function(t) {
            if (!t) return !1;
            var e = this.pages.all(["ReportPage", "DialogPage"]),
                n = e[e.indexOf(t) - 1];
            return n = n || this.pages.last(), t.remove(), x.a.trigger("activate", n), this
        },
        getCurrentPage: function() {
            return this.pages.current
        },
        clear: function() {
            for (var t = [].concat(this.pages.all()), e = t.length; e--;) this.removePage(t[e]);
            this.pages.clear(), S.a.clear(), this.counter.clear()
        },
        remove: function() {
            this.clear(), this.$node.remove()
        },
        getComponents: function(e) {
            var n = C()();
            return this.pages.eachEntity(function(t) {
                t.isReportPage() && (n = n.add(t.bands.getComponents(e)))
            }), n
        },
        getCurPageComponents: function(t, e) {
            var n = this.getCurrentPage(),
                a = C()();
            return n.isReportPage() && (a = a.add(n.bands.getComponents(t, e))), a
        },
        findEntity: function(n) {
            function a(e, n) {
                var a;
                return Object.keys(n).every(function(t) {
                    return a = n[t], e[t] === a || e.attributes[t] === a || e.properties[t] === a
                })
            }
            var i;
            if (n) return a(this, n) ? this : (this.pages.eachEntity(function(t) {
                var e;
                if (a(t, n)) i = t;
                else if (t.bands) t.bands.eachEntity(function(t) {
                    return a(t, n) ? (i = t, !1) : (e = t.components.findOneBy(n)) ? (i = e, !1) : void 0
                });
                else if (t.components && (e = t.components.findOneBy(n))) return i = e, !1
            }), i)
        },
        getSelected: function() {
            var t, e, n = this.getCurrentPage();
            if (!n) return null;
            if (t = u.a.getSelected()) return t;
            if (e = n.bands || n.components, !n.isCode()) {
                if (1 < (t = e.getSelectedComponents()).length) return null;
                !(t = t[0]) && e.getSelectedBand && (t = e.getSelectedBand())
            }
            return t || n.attr("activated") ? n : this
        },
        fillPropsNET: function(t) {
            var e, n, a;
            if (u.a.fillPropsNET.apply(this, arguments), (e = t.attr("ReportInfo.Created")) && this.prop("ReportInfo.Created", Object(o.b)(e)), (n = t.attr("ReportInfo.Modified")) && this.prop("ReportInfo.Modified", Object(o.b)(n)), a = t.attr("ReportInfo.Tag")) try {
                this.prop("ReportInfo.Tag", JSON.parse(a))
            } catch (t) {
                l.warn("ReportInfo.Tag:", t)
            }
            return this
        },
        fillPropsVCL: function() {
            return u.a.fillPropsVCL.apply(this, arguments), this.code && this.prop("ScriptText.Text") && this.code.setCode(Object(r.a)(this.prop("ScriptText.Text"))), this
        },
        toXMLNET: function(b) {
            var v = this;
            return new Promise(function(h) {
                var f = C.a.parseXML("<{0}/>".format(v.type)),
                    g = C()(f.createElement("Styles")),
                    m = C()(f.createElement("Dictionary"));
                b = Object.assign({
                    parentNode: f,
                    type: v.type
                }, b), u.a.toXMLNET.call(v, b).then(function(n) {
                    var e, t, a, i, r, o, s, l, c, p, d, u;
                    if ((n = C()(n)).attr("ReportInfo.Picture") && n.attr("ReportInfo.Picture", n.attr("ReportInfo.Picture").replace(/^([\w:\/;]+base64,){1}/, "")), v.prop("ReportInfo.Tag") && "object" === y(v.prop("ReportInfo.Tag"))) try {
                        n.attr("ReportInfo.Tag", JSON.stringify(v.prop("ReportInfo.Tag")))
                    } catch (t) {}
                    e = [], v.styles && v.styles.count() && (b.parentNode = g[0], v.styles.eachEntity(function(t) {
                        e.push(t.toXMLNET(b))
                    }), n.append(g)), t = Promise.all(e).then(function(t) {
                        t.forEach(function(t) {
                            return g.append(t)
                        })
                    }), b.parentNode = m[0], a = [], v.connections && v.connections.eachEntity(function(t) {
                        a.push(t.toXMLNET(b))
                    }), i = Promise.all(a).then(function(t) {
                        t.forEach(function(t) {
                            return m.append(t)
                        })
                    }), r = [], v.dataSources && v.dataSources.eachEntity(function(t) {
                        r.push(t.toXMLNET(b))
                    }), o = Promise.all(r).then(function(t) {
                        t.forEach(function(t) {
                            return m.append(t)
                        })
                    }), s = [], v.relations && v.relations.eachEntity(function(t) {
                        s.push(t.toXMLNET(b))
                    }), l = Promise.all(s).then(function(t) {
                        t.forEach(function(t) {
                            return m.append(t)
                        })
                    }), c = [], v.parameters && v.parameters.eachEntity(function(t) {
                        c.push(t.toXMLNET(b))
                    }), p = Promise.all(c).then(function(t) {
                        t.forEach(function(t) {
                            return m.append(t)
                        })
                    }), d = [], v.totals && v.totals.eachEntity(function(t) {
                        d.push(t.toXMLNET(b))
                    }), u = Promise.all(d).then(function(t) {
                        t.forEach(function(t) {
                            return m.append(t)
                        })
                    }), Promise.all([t, i, o, l, p, u]).then(function() {
                        C.a.each(v.unparsedDict, function() {
                            m.append(C()(this))
                        }), n.append(m), b.parentNode = n[0];
                        var e = [];
                        v.pages.eachEntity(function(t) {
                            e.push(t.toXMLNET(b))
                        }), Promise.all(e).then(function(t) {
                            return t.forEach(function(t) {
                                return n.append(t)
                            }), C.a.each(v.unparsed, function() {
                                n.append(C()(this))
                            }), f.documentElement.appendChild(n[0]), h(n[0])
                        })
                    })
                })
            })
        },
        toXMLVCL: function(i) {
            var r = this;
            return new Promise(function(a) {
                var t = C.a.parseXML("<{0}/>".format(r.typevcl));
                i = Object.assign({
                    parentNode: t
                }, i), u.a.toXMLVCL.call(r, i).then(function(e) {
                    e = C()(e), i.parentNode = e[0];
                    var n = [];
                    r.pages.eachEntity(function(t) {
                        n.push(t.toXMLVCL(i))
                    }), Promise.all(n).then(function(t) {
                        return t.forEach(function(t) {
                            t && e.append(t)
                        }), C.a.each(r.unparsed, function() {
                            e.append(C()(this))
                        }), a(e[0])
                    })
                })
            })
        }
    })
}, , , , function(t, e, n) {
    "use strict";

    function a(t, e) {
        var n, a = 2 < arguments.length && void 0 !== arguments[2] ? arguments[2] : "",
            i = a + "Font.Name",
            r = a + "Font.Size",
            o = a + "Font.Bold",
            s = a + "Font.Italic",
            l = a + "Font.Underline",
            c = a + "Font.Strikeout";
        return !!e.attr(i) && (n = "", n += e.attr(i), e.attr(r) && (n += ", " + e.attr(r)), (e.attr(o) || e.attr(s) || e.attr(l) || e.attr(c)) && (n += ", style=", e.attr(o) && (n += "Bold,"), e.attr(s) && (n += "Italic,"), e.attr(l) && (n += "Underline,"), e.attr(c) && (n += "Strikeout"), "," === n[n.length - 1] && (n = n.substring(0, n.length - 1))), !!n && (t.attr(a + "Font", n), !0))
    }
    n.d(e, "a", function() {
        return a
    })
}, , , , function(t, e, n) {
    "use strict";
    var a = n(0),
        i = n.n(a),
        r = n(127),
        o = function(t) {
            return {
                aliceblue: "#f0f8ff",
                antiquewhite: "#faebd7",
                aqua: "#00ffff",
                aquamarine: "#7fffd4",
                azure: "#f0ffff",
                beige: "#f5f5dc",
                bisque: "#ffe4c4",
                black: "#000000",
                blanchedalmond: "#ffebcd",
                blue: "#0000ff",
                blueviolet: "#8a2be2",
                brown: "#a52a2a",
                burlywood: "#deb887",
                cadetblue: "#5f9ea0",
                chartreuse: "#7fff00",
                chocolate: "#d2691e",
                coral: "#ff7f50",
                cornflowerblue: "#6495ed",
                cornsilk: "#fff8dc",
                crimson: "#dc143c",
                cyan: "#00ffff",
                darkblue: "#00008b",
                darkcyan: "#008b8b",
                darkgoldenrod: "#b8860b",
                darkgray: "#a9a9a9",
                darkgreen: "#006400",
                darkkhaki: "#bdb76b",
                darkmagenta: "#8b008b",
                darkolivegreen: "#556b2f",
                darkorange: "#ff8c00",
                darkorchid: "#9932cc",
                darkred: "#8b0000",
                darksalmon: "#e9967a",
                darkseagreen: "#8fbc8f",
                darkslateblue: "#483d8b",
                darkslategray: "#2f4f4f",
                darkturquoise: "#00ced1",
                darkviolet: "#9400d3",
                deeppink: "#ff1493",
                deepskyblue: "#00bfff",
                dimgray: "#696969",
                dodgerblue: "#1e90ff",
                firebrick: "#b22222",
                floralwhite: "#fffaf0",
                forestgreen: "#228b22",
                fuchsia: "#ff00ff",
                gainsboro: "#dcdcdc",
                ghostwhite: "#f8f8ff",
                gold: "#ffd700",
                goldenrod: "#daa520",
                gray: "#808080",
                green: "#008000",
                greenyellow: "#adff2f",
                honeydew: "#f0fff0",
                hotpink: "#ff69b4",
                indianred: "#cd5c5c",
                indigo: "#4b0082",
                ivory: "#fffff0",
                khaki: "#f0e68c",
                lavender: "#e6e6fa",
                lavenderblush: "#fff0f5",
                lawngreen: "#7cfc00",
                lemonchiffon: "#fffacd",
                lightblue: "#add8e6",
                lightcoral: "#f08080",
                lightcyan: "#e0ffff",
                lightgoldenrodyellow: "#fafad2",
                lightgrey: "#d3d3d3",
                lightgreen: "#90ee90",
                lightpink: "#ffb6c1",
                lightsalmon: "#ffa07a",
                lightseagreen: "#20b2aa",
                lightskyblue: "#87cefa",
                lightslategray: "#778899",
                lightsteelblue: "#b0c4de",
                lightyellow: "#ffffe0",
                lime: "#00ff00",
                limegreen: "#32cd32",
                linen: "#faf0e6",
                magenta: "#ff00ff",
                maroon: "#800000",
                mediumaquamarine: "#66cdaa",
                mediumblue: "#0000cd",
                mediumorchid: "#ba55d3",
                mediumpurple: "#9370d8",
                mediumseagreen: "#3cb371",
                mediumslateblue: "#7b68ee",
                mediumspringgreen: "#00fa9a",
                mediumturquoise: "#48d1cc",
                mediumvioletred: "#c71585",
                midnightblue: "#191970",
                mintcream: "#f5fffa",
                mistyrose: "#ffe4e1",
                moccasin: "#ffe4b5",
                navajowhite: "#ffdead",
                navy: "#000080",
                oldlace: "#fdf5e6",
                olive: "#808000",
                olivedrab: "#6b8e23",
                orange: "#ffa500",
                orangered: "#ff4500",
                orchid: "#da70d6",
                palegoldenrod: "#eee8aa",
                palegreen: "#98fb98",
                paleturquoise: "#afeeee",
                palevioletred: "#d87093",
                papayawhip: "#ffefd5",
                peachpuff: "#ffdab9",
                peru: "#cd853f",
                pink: "#ffc0cb",
                plum: "#dda0dd",
                powderblue: "#b0e0e6",
                purple: "#800080",
                red: "#ff0000",
                rosybrown: "#bc8f8f",
                royalblue: "#4169e1",
                saddlebrown: "#8b4513",
                salmon: "#fa8072",
                sandybrown: "#f4a460",
                seagreen: "#2e8b57",
                seashell: "#fff5ee",
                sienna: "#a0522d",
                silver: "#c0c0c0",
                skyblue: "#87ceeb",
                slateblue: "#6a5acd",
                slategray: "#708090",
                snow: "#fffafa",
                springgreen: "#00ff7f",
                steelblue: "#4682b4",
                tan: "#d2b48c",
                teal: "#008080",
                thistle: "#d8bfd8",
                tomato: "#ff6347",
                turquoise: "#40e0d0",
                violet: "#ee82ee",
                wheat: "#f5deb3",
                white: "#ffffff",
                whitesmoke: "#f5f5f5",
                yellow: "#ffff00",
                yellowgreen: "#9acd32"
            } [t.toLowerCase()] || null
        };
    e.a = function(t) {
        var e, n, a;
        return !!t && (function(t) {
            var e = !1;
            return "transparent" === t ? e = !0 : (t = t.match(/\s*(\d{1,3})\s*,\s*(\d{1,3})\s*,\s*(\d{1,3})\s*,\s*([\d\.]+)\s*/)) && t[4] && 0 === Number(t[4]) && (e = !0), e
        }(t) ? "#ffffff" : (e = /rgb\(?\s*(\d{1,3})\s*,\s*(\d{1,3})\s*,\s*(\d{1,3})\s*\)?$/, n = /^#(?:[0-9a-f]{3}){1,2}$/i, a = i()("<div>").appendTo(window.DSG.head.$reusable).css("fill", t), t = getComputedStyle(a[0]).getPropertyValue("fill"), a.remove(), n.test(t) ? t : e.test(t) ? (t = t.match(e).slice(1), r.a.apply(null, t)) : o(t) || t))
    }
}, function(t, e, n) {
    "use strict";
    var a, o, i, s, l, c;
    n.r(e), a = n(0), o = n.n(a), i = n(4), s = n(12), l = n(3), c = n(5), e.default = s.default.createObject(s.default, {
        title: "DateTimePickerControl",
        info: "DateTimePickerControl info",
        icon: "icon-120",
        pos: 45,
        type: "DateTimePickerControl",
        disabled: !1,
        _init: function() {
            s.default._init.apply(this, arguments), this.defaultValues = {
                ForeColor: "#000",
                BackColor: "#FFF",
                DropDownAlign: "Left",
                ShowCheckBox: !1,
                ShowUpDown: !1,
                Checked: !0,
                Enabled: !0,
                TabIndex: 3,
                TabStop: !0,
                Visible: !0,
                CustomFormat: "",
                Format: "Long",
                AutoFill: !0,
                AutoFilter: !0,
                FilterOperation: "Equal"
            }, this.attr({
                "Font.Name": i.a.get("default-font-name"),
                "Font.Size": "8pt",
                "Font.Bold": !1,
                "Font.Italic": !1,
                "Font.Underline": !1,
                "Font.Strikeout": !1,
                resizableXY: !1,
                resizableY: !1
            }), this.prop({
                Name: "DateTimePicker",
                MaxDate: new Date("12.31.9998"),
                MinDate: new Date("01.01.1753"),
                Value: new Date,
                Width: 200,
                Height: 20
            })
        },
        showResizingComponents: function() {
            return s.default.showResizingComponents.call(this, !1)
        },
        format: function(t, e, n) {
            var a, i, r, o;
            return "string" == typeof t && (t = new Date(t)), e = e || "/", a = t.getMonth() + 1 + e + t.getDate() + e + t.getFullYear(), n && (i = t.getHours(), r = t.getMinutes(), o = t.getSeconds(), i || r || o || (i = (t = new Date).getHours(), r = t.getMinutes(), o = t.getSeconds()), a = a + " " + i + n + r + n + o), a
        },
        render: function() {
            var t = this.attr("Font.Size"),
                e = this.attr("Font.Name"),
                n = new Date(this.format(this.prop("Value"), "/", ":")).toLocaleString(),
                a = this.prop("Width"),
                i = this.prop("Height"),
                r = parseFloat(t, 10);
            return s.default.render.apply(this, arguments), this.prop("Text", n), this.$contentGroup.children().length || (this.$nestedG1 = o()(Object(l.a)("g")), this.$nestedG2 = o()(Object(l.a)("g")), this.$textNode = o()(Object(l.a)("text")), this.$arrow = o()(Object(l.a)("text")), this.$nestedG2.append(this.$textNode), this.$nestedG1.append(this.$nestedG2), this.$textNode[0].setAttributeNS("http://www.w3.org/XML/1998/namespace", "xml:space", "preserve"), this.$arrow.text("▼"), this.$back = o()(Object(l.a)("rect", {
                stroke: "gray",
                x: 0,
                y: 0
            })), this.$contentGroup.append(this.$back, this.$nestedG1, this.$arrow)), this.$back.attr({
                width: a - 2,
                height: i - 2,
                fill: this.prop("BackColor")
            }), this.$textNode.text() !== n && this.$textNode.text(n), this.$moveBlock.css("fill", this.prop("BackColor")), this.$textNode.css({
                "font-size": t + (o.a.isNumeric(t) ? "pt" : ""),
                fill: this.prop("ForeColor"),
                "font-family": e
            }), this.applyFontStyles(this.$textNode), Object(c.a)(this.$back[0], "transform", "translate(0, 0)"), Object(c.a)(this.$nestedG1[0], "transform", "translate(2, " + (r + 2) + ")"), Object(c.a)(this.$arrow[0], "transform", "translate(" + (a - 15) + ", 12)"), this.$g
        },
        fillPropsNET: function() {
            var t, e, n;
            return s.default.fillPropsNET.apply(this, arguments), t = this.prop("value"), e = this.prop("MaxDate"), n = this.prop("MinDate"), t && this.prop("value", new Date(t)), e && this.prop("MaxDate", new Date(e)), n && this.prop("MinDate", new Date(n)), this
        },
        toXMLNET: function(t) {
            var r = this;
            return new Promise(function(i) {
                s.default.toXMLNET.call(r, t).then(function(t) {
                    var e, n, a;
                    return t = o()(t), (e = r.prop("Value")) && t.attr("Value", r.format(e, "/", ":")), (n = r.prop("MinDate")) && t.attr("MinDate", r.format(n, "-")), (a = r.prop("MaxDate")) && t.attr("MaxDate", r.format(a, "-")), i(t[0])
                })
            })
        }
    })
}, , , , function(t, e, n) {
    "use strict";

    function s(t, e) {
        var n = (e["font-style"] || "") + " " + (e["font-weight"] || "") + " " + (e["font-size"] || "") + " " + (e["font-family"] || ""),
            a = s.canvas || (s.canvas = document.createElement("canvas")),
            i = a.getContext("2d");
        return i.font = n, i.measureText(t).width
    }
    var a = n(0),
        l = n.n(a);
    e.a = function(t, e, n) {
        var a, i, r, o = l()("body");
        return e = {
            "font-size": (e = e || {})["font-size"] || "12px",
            "font-family": e["font-family"] || "Tahoma",
            "font-weight": e["font-weight"] || "100",
            "font-style": e["font-style"] || "normal"
        }, n && (t = t.replace(/[\n\r]/g, "<br>&nbsp;")), (a = l()("<div>" + t + "</div>")).css({
            position: "absolute",
            float: "left",
            "white-space": "nowrap",
            visibility: "hidden",
            "font-size": e["font-size"],
            "font-family": e["font-family"],
            "font-weight": e["font-weight"],
            "font-style": e["font-style"]
        }).appendTo(o), i = n ? a.width() : s(t, e), r = a.height(), a.remove(), {
            w: i,
            h: r
        }
    }
}, function(t, e, n) {
    "use strict";
    var a, r, i, o, s, l;
    n.r(e), a = n(0), r = n.n(a), i = n(4), o = n(12), s = n(3), l = n(5), e.default = o.default.createObject(o.default, {
        title: "CheckBoxControl",
        info: "CheckBoxControl info",
        icon: "icon-116",
        pos: 20,
        type: "CheckBoxControl",
        disabled: !1,
        CHECKBOXWH: 12,
        _init: function() {
            o.default._init.apply(this, arguments), this.defaultValues = {
                Appearance: "Normal",
                ForeColor: "#000",
                BackColor: "#F0F0F0",
                CheckAlign: "MiddleLeft",
                Checked: !1,
                CheckState: "Unchecked",
                ImageAlign: "MiddleCenter",
                TextAlign: "MiddleLeft",
                TextImageRelation: "Overlay",
                Enabled: !0,
                AutoSize: !0,
                TabStop: !0,
                RightToLeft: "No",
                AutoFilter: !0,
                FilterOpeartion: "Equal",
                DetailControl: "None"
            }, this.prop({
                Name: "CheckBox",
                Width: 73,
                Height: 17,
                Text: "CheckBox"
            }), this.attr({
                "Font.Name": i.a.get("default-font-name"),
                "Font.Size": "8pt",
                "Font.Bold": !1,
                "Font.Italic": !1,
                "Font.Underline": !1,
                "Font.Strikeout": !1,
                resizableX: !1,
                resizableY: !1,
                resizableXY: !1
            })
        },
        resizingComponents: function() {},
        generateCheckbox: function() {
            var t = r()(Object(s.a)("g")),
                e = r()(Object(s.a)("rect", {
                    width: this.CHECKBOXWH,
                    height: this.CHECKBOXWH,
                    fill: "#fff",
                    stroke: "gray",
                    x: 5,
                    y: 0
                })),
                n = r()(Object(s.a)("text"));
            return t.append(e, n), t
        },
        render: function() {
            var t = this.attr("Font.Size"),
                e = this.attr("Font.Name"),
                n = this.prop("Text"),
                a = this.prop("CheckState"),
                i = this.prop("Height") / 2 + parseFloat(t, 10) / 3;
            return o.default.render.apply(this, arguments), this.$contentGroup.children().length || (this.$nestedG1 = r()(Object(s.a)("g")), this.$nestedG2 = r()(Object(s.a)("g")), this.$textNode = r()(Object(s.a)("text")), this.$nestedG2.append(this.$textNode), this.$nestedG1.append(this.$nestedG2), this.$checkbox = this.generateCheckbox(), this.$contentGroup.append(this.$checkbox, this.$nestedG1)), n !== this.$textNode.text() && this.$textNode.text(n), "Checked" === a ? this.$checkbox.find("text").text("✓").attr({
                x: 7,
                y: 10
            }) : "Indeterminate" === a ? this.$checkbox.find("text").text("■").attr({
                x: 8,
                y: 9
            }) : this.$checkbox.find("text").text(""), this.$moveBlock.css("fill", this.prop("BackColor")), this.$textNode.css({
                "font-size": t + (r.a.isNumeric(t) ? "pt" : ""),
                fill: this.prop("ForeColor"),
                "font-family": e
            }), this.applyFontStyles(this.$textNode), Object(l.a)(this.$checkbox[0], "transform", "translate(0, 2)"), Object(l.a)(this.$nestedG1[0], "transform", "translate(" + (0 + 1.7 * this.CHECKBOXWH) + ", " + i + ")"), this.$g
        }
    })
}, function(t, e, n) {
    "use strict";

    function a(t) {
        return t = function(t) {
            return t.replace(/<ScriptText>([\s\S]+)<\/ScriptText>/, function(t, e) {
                return "<ScriptText>{0}</ScriptText>".format(Object(b.a)(e))
            })
        }(t), t = Object(v.a)(t), p.a.parseXML(t)
    }

    function i(e, t) {
        var n = p.a.Deferred();
        return p.a.ajax({
            url: e,
            dataType: t || "text",
            crossDomain: !0,
            headers: {
                "Access-Control-Allow-Origin": "*"
            },
            beforeSend: function(t) {
                i.cache[e] && (t.abort(), n.resolve(i.cache[e]))
            },
            success: function(t) {
                i.cache[e] = t, n.resolve(i.cache[e])
            },
            error: function() {
                n.reject.apply(null, arguments)
            }
        }), n.promise()
    }
    var r, o, s = n(0),
        p = n.n(s),
        d = n(27),
        l = n(7),
        c = Object(l.a)({}, {
            init: function(t, e) {
                return r = t, o = e, this
            },
            _getComponent: function(t) {
                var e, n, a, i;
                if (t)
                    for (e = window.DSG.components, a = void 0, i = (n = Object.keys(e)).length - 1; 0 <= i; i--)
                        if ((a = e[n[i]]).type === t || a.typevcl === t) return a
            },
            _getBand: function(t) {
                var e, n, a, i;
                if (t)
                    for (e = window.DSG.bands, a = void 0, i = (n = Object.keys(e)).length - 1; 0 <= i; i--)
                        if ((a = e[n[i]]).type === t || a.typevcl === t) return a
            },
            preCall: function() {
                if (!r) throw new Error("Report wasn't initialized")
            },
            getCurrentReport: function() {
                return r
            },
            getState: function() {
                return o
            }
        }),
        u = n(118),
        h = n(23),
        f = (new h.a("parser net"), Object(l.a)(c, {
            _createParameter: function(t, e) {
                var n, a, i = this.getCurrentReport();
                return !!i.createParameter && (n = i.createParameter(e), a = t.children(), n.fillPropsNET(t), a.length ? this._processingParameters(a, n) : n)
            },
            _processingTotal: function(t) {
                var e, n = this.getCurrentReport();
                return !!n.createTotal && ((e = n.createTotal()).fillPropsNET(t), e)
            },
            _createColumn: function(t, e) {
                var n = e.createColumn(),
                    a = t.children();
                n.fillPropsNET(t), n.updateBindableControl(), a.length && a.each(function(t, e) {
                    (e = p()(e)).is("column") ? this._createColumn(e, n) : this._processingDataSource(e, n)
                }.bind(this))
            },
            _createRelation: function(t) {
                var e, n = this.getCurrentReport();
                return !!n.createRelation && ((e = n.createRelation()).fillPropsNET(t), e.update(), e)
            },
            _processingPage: function(t) {
                var e = this.getCurrentReport().createPage(!0);
                return !!e && (e.fillPropsNET(t), e.update(), this._processingBands(t.children(), e))
            },
            _processingDialog: function(t) {
                var e, n = this.getCurrentReport().createDialog(!0),
                    a = this;
                return !!n && (n.fillPropsNET(t), p.a.each(t.children(), function() {
                    e = p()(this), window.DSG.controls[e.prop("tagName")] ? a._processingControl(e, n) : n.unparsed.push(e)
                }), !0)
            },
            _processingStyles: function(t) {
                var e = this.getCurrentReport();
                return p.a.each(t.children(), function() {
                    e.createStyle().fillPropsNET(p()(this))
                }), !0
            },
            _processingParameters: function(t, n) {
                var a = !1;
                return p.a.each(t, function(t, e) {
                    this._createParameter(p()(e), n) && (a = !0)
                }.bind(this)), a
            },
            _processingDataSource: function(t, e) {
                var n = this.getCurrentReport(),
                    a = this,
                    i = !1,
                    r = t.prop("tagName"),
                    o = /DataSource$/i;
                return r.match(o) && n.createDataSource ? i = n.createDataSource(r, e) : r.match(/Connection$/i) && n.createConnection && (i = n.createConnection(r, e)), i && (i.fillPropsNET(t), t.children().each(function() {
                    var t = p()(this);
                    t.is("Column") ? a._createColumn(t, i) : t.prop("tagName").match(o) ? a._processingDataSource(t, i) : i.unparsed.push(t)
                })), i
            },
            _getControl: function(t) {
                return window.DSG.controls[t.prop("tagName")]
            },
            _processingComponent: function(t, e) {
                var n = this._getComponent(t.prop("tagName")),
                    a = this.getState();
                return n && ((n = n.create()).fillPropsNET(t), "TextObject" === n.type && a && a.texts[n.prop("mission")] && n.prop(a.texts[n.prop("mission")]), n.render({
                    action: "init"
                }), e.put(n)), n
            },
            _processingControl: function(t, e) {
                var n = this._getControl(t);
                return n && ((n = n.create()).fillPropsNET(t), n.render({
                    action: "init"
                }), e.put(n)), n
            },
            _processingBand: function(t, e) {
                var n = this._getBand(t.prop("tagName"));
                return !!n && ((n = n.create()).fillPropsNET(t), e.addBand(n) ? n : null)
            },
            _processingBands: function(t, a) {
                var r = function(t, e) {
                        e = e || a;
                        var n = this._processingBand(t, e);
                        return !!n && i(t.children(), n)
                    }.bind(this),
                    i = function(t, e) {
                        for (var n, a = 0, i = t.length; a < i; a += 1)(n = p()(t[a])).prop("tagName"), this._processingComponent(n, e) || r(n, e) || e && e.unparsed && e.unparsed.push(n);
                        return e && e.balance(), e
                    }.bind(this);
                return i(t)
            },
            _processingDictionary: function(t) {
                for (var e, n, a, i = this.getCurrentReport(), r = 0, o = t.children(), s = o.length; r < s; r += 1) n = (e = p()(o[r])).prop("tagName"), a = !1, e.is("Parameter") ? a = this._processingParameters(e) : n.match(/(DataSource|Connection)$/i) ? a = this._processingDataSource(e) : e.is("Total") ? a = this._processingTotal(e) : e.is("Relation") && (a = !0), !1 === a && i.unparsedDict.push(e);
                return !0
            },
            _getRelations: function(t) {
                for (var e, n = 0, a = t.children(), i = a.length, r = []; n < i; n += 1)(e = p()(a[n])).is("Relation") && r.push(e);
                return r
            },
            _processingRelations: function(t) {
                for (var e, n = this.getCurrentReport(), a = this._getRelations(t), i = 0, r = a.length; i < r; i += 1) e = p()(a[i]), this._createRelation(e) || n.unparsedDict.push(e)
            },
            getType: function() {
                return "net"
            },
            parse: function(t) {
                var e, n, a, i, r, o, s = this.getCurrentReport(),
                    l = p()(t).find("Report");
                for (this.preCall(), s.fillPropsNET(l), a = 0, i = (r = l.children()).length; a < i; a += 1) o = !1, (e = p()(r[a])).is("ScriptText") ? s.code && (s.code.setCode(Object(u.a)(e.text())), o = !0) : o = e.is("Dictionary") ? (n = e, this._processingDictionary(n), !0) : e.is("ReportPage") ? this._processingPage(e) : e.is("DialogPage") ? this._processingDialog(e) : !!e.is("Styles") && this._processingStyles(e), !1 === o && s.unparsed.push(e);
                for (n && this._processingRelations(n), a = 0, i = (r = s.pages.all(["ReportPage", "DialogPage"])).length; a < i; a += 1) r[a].render({
                    action: "init"
                });
                return s
            },
            extendCurrent: function(t, e) {
                var n, a, i, r = p()(t),
                    o = r.children(),
                    s = 0,
                    l = o.length,
                    c = this.getCurrentReport();
                if (e = e || c, r.is("[BaseReport]")) c.fillPropsNET(r), (i = c).attr("inherited", !0);
                else if (r.is("Dictionary")) n = r, this._processingDictionary(r);
                else {
                    if (r.is("ScriptText")) return void(c.code && c.code.setCode(Object(u.a)(r.text())));
                    if (r.is("inherited")) {
                        if (!(i = c.findEntity({
                                Name: r.attr("Name")
                            }))) return void Object(d.a)("inherited element with the Name {0} was not found".format(r.attr("Name")), {
                            danger: !0,
                            delay: !1
                        });
                        i.fillPropsNET(r), i.attr("inherited", !0), i.render()
                    } else i = (i = this._processingComponent(r, e)) || ((i = this._processingBand(r, e)) || e)
                }
                if (n) this._processingRelations(n);
                else if (i)
                    for (; s < l; s += 1) a = o[s], this.extendCurrent(a, i)
            },
            toXML: function(t) {
                return t = t || {}, this.preCall(), this.getCurrentReport().toXMLNET(t)
            }
        })),
        g = (new h.a("parser vcl"), Object(l.a)(c, {
            _processingPage: function(t) {
                var e = this.getCurrentReport().createPage(!0);
                return !!e && (e.fillPropsVCL(t), e.update(), this._processingBands(t.children(), e))
            },
            _processingBand: function(t, e) {
                var n = this._getBand(t.prop("tagName"));
                return !!n && ((n = n.create()).fillPropsVCL(t), e.addBand(n) ? n : null)
            },
            _processingComponent: function(t, e) {
                var n = this._getComponent(t.prop("tagName")),
                    a = this.getState();
                return n && ((n = n.create()).fillPropsVCL(t), "TextObject" === n.type && a && a.texts[n.prop("mission")] && n.prop(a.texts[n.prop("mission")]), n.render({
                    action: "init"
                }), e.put(n)), n
            },
            _processingBands: function(t, a) {
                var r = function(t, e) {
                        e = e || a;
                        var n = this._processingBand(t, e);
                        return !!n && i(t.children(), n)
                    }.bind(this),
                    i = function(t, e) {
                        for (var n, a = 0, i = t.length; a < i; a += 1)(n = p()(t[a])).prop("tagName"), this._processingComponent(n, e) || r(n, e) || e && e.unparsed && e.unparsed.push(n);
                        return e && e.balance(), e
                    }.bind(this);
                return i(t)
            },
            getType: function() {
                return "vcl"
            },
            parse: function(t) {
                var e, n, a, i, r = this.getCurrentReport(),
                    o = p()(t).find("TfrxReport");
                for (this.preCall(), r.fillPropsVCL(o), n = 0, a = (i = o.children()).length; n < a; n += 1) !1 === (!!(e = p()(i[n])).is("TfrxReportPage") && this._processingPage(e)) && r.unparsed.push(e);
                return r
            },
            extendCurrent: function() {},
            toXML: function(t) {
                return t = t || {}, this.preCall(), this.getCurrentReport().toXMLVCL(t)
            }
        })),
        m = {
            init: c.init,
            parse: function(t) {
                var e = c.getCurrentReport(),
                    n = function(t) {
                        return (t = p()(t)).find("Report").length ? f : t.find("TfrxReport").length ? g : void Object(d.a)("there is no root object inside report", {
                            danger: !0,
                            delay: !1
                        })
                    }(t);
                return n && (n.parse(t), e.attr("report-type", n.getType())), e
            },
            extendCurrent: function(t) {
                return "vcl" === c.getCurrentReport().attr("report-type") ? g.extendCurrent(t) : f.extendCurrent(t)
            },
            toXML: function(t) {
                return "vcl" === c.getCurrentReport().attr("report-type") ? g.toXML(t) : f.toXML(t)
            }
        },
        b = n(119),
        v = n(185);
    i.cache = {}, n.d(e, "c", function() {
        return m
    }), n.d(e, "b", function() {
        return i
    }), n.d(e, "a", function() {
        return a
    })
}, , , , , function(t, e, n) {
    "use strict";
    e.a = function(t) {
        for (var e, n = window.location.search.substring(1).split("&"), a = 0, i = n.length, r = []; a < i; a += 1)(e = n[a].split("="))[0] === t && r.push(e[1]);
        return r.length < 2 ? r[0] : r
    }
}, function(t, e, n) {
    "use strict";
    e.a = function(t) {
        return t.replace(/&#(\d+);/g, function(t, e) {
            return String.fromCharCode(e)
        })
    }
}, function(t, e, n) {
    "use strict";
    e.a = function(t) {
        return t.replace(/(\n|\r|\t+)/g, function(t, e) {
            return "&#" + e.charCodeAt() + ";"
        })
    }
}, function(t, e, n) {
    "use strict";
    var a = n(0),
        c = n.n(a),
        i = n(13),
        r = n(29),
        o = n(7);
    e.a = Object(o.a)(i.a, {
        _init: function() {},
        create: function() {
            var t = this.createObject(this);
            return t._init.apply(t, arguments), t
        },
        remove: function() {
            this.attr("removed", !0)
        },
        restore: function() {
            this.attr("removed", !1)
        },
        getView: function(t) {
            for (var e, n = t.parents(".rt-node:not(:last)"), a = 0, i = n.length, r = ""; a < i; a += 1)(e = n[a].element).isConnection() || (r = e.toString() + "." + r);
            return r = r.replace(/\.$/, "")
        },
        isParameter: function() {
            return !1
        },
        isColumn: function() {
            return !1
        },
        isDataSource: function() {
            return !1
        },
        isTotal: function() {
            return !1
        },
        createColumn: function(t) {
            var e = (t || this).create();
            return this.columns.add(e), e
        },
        updateBindableControl: function() {
            var t = this.prop("BindableControl") || "Text";
            "Custom" === t && (t = "Text"), this.bindableControl = t + "Object"
        },
        fillPropsNET: function(t) {
            return i.a.fillPropsNET.apply(this, arguments), t.attr("DataType") && this.prop("DataType", r.a.get(t.attr("DataType"))), this
        },
        toXMLNET: function(s) {
            var l = this;
            return new Promise(function(t) {
                var e, n, a, i, r = c()(s.parentNode.ownerDocument.createElement(l.type)),
                    o = l.prop("DataType");
                (s = Object.assign({
                    parentNode: r[0]
                }, s)).includeId && r.attr("data-entity-id", l._id), l.deleteProp("DataType"), l.eachProp(function(t, e) {
                    r.attr(t, e)
                }), o && (r.attr("DataType", "string" == typeof o ? o : o.dataType), l.prop("DataType", o)), e = [], l.columns && l.columns.eachEntity(function(t) {
                    e.push(t.toXMLNET(s))
                }), n = Promise.all(e).then(function(t) {
                    t.forEach(function(t) {
                        return r.append(t)
                    })
                }), a = [], l.dataSources && l.dataSources.eachEntity(function(t) {
                    a.push(t.toXMLNET(s))
                }), i = Promise.all(a).then(function(t) {
                    t.forEach(function(t) {
                        return r.append(t)
                    })
                }), Promise.all([n, i]).then(function() {
                    l.unparsed && c.a.each(l.unparsed, function() {
                        r.append(this)
                    });
                    var e = [];
                    l.parameters && l.parameters.eachEntity(function(t) {
                        e.push(t.toXMLNET(s))
                    }), Promise.all(e).then(function(t) {
                        t.forEach(function(t) {
                            return r.append(t)
                        })
                    }), t(r[0])
                })
            })
        },
        toString: function() {
            return this.prop("Alias") || this.prop("Name")
        }
    })
}, function(t, e, n) {
    "use strict";

    function l(t) {
        return t.split(":").filter(function(t) {
            return !/^[\s]*$/.test(t) && t
        })
    }

    function i(t, n) {
        var a, i, r, e = {},
            o = t,
            s = {};
        if (c.a.isPlainObject(t)) o = (t = Object.assign({}, t)).prop, a = l(t.prop), delete t.prop, e = t;
        else {
            if ("string" != typeof t) return null;
            a = l(t)
        }
        return a.forEach(function(t, e) {
            if (0 === e) return i = c.a.extend(!0, {}, n[t]), s[t] = i, void(r = s[t]);
            i = i && (i[t] || i.fields && i.fields[t]), r.fields = {}, r.fields[t] = i, r.fields[t] || (a[e + 1] ? r.fields[t] = {
                label: t,
                fields: {}
            } : r.fields[t] = {
                type: "text",
                label: t
            }), r = r.fields[t]
        }), Object(h.a)(r, e, !0), r.origin = o, s
    }

    function a(t, e) {
        var n, a;
        for (n = e.length; n--;)
            for (a = 0; a < t.length; a += 1)
                if (t[a] === e[n] || t[a].prop === e[n]) {
                    Object(f.a)(t, a);
                    break
                } return t
    }

    function r() {}
    var o, s = n(0),
        c = n.n(s),
        p = n(4),
        d = n(1),
        u = n(14),
        h = n(6),
        f = n(179),
        g = Array.prototype.push;
    r.prototype = Object.create(Array.prototype), o = {
        _topProps: function() {
            return {
                attrs: {
                    min: 0,
                    step: function() {
                        return u.a.toUnit(p.a.get("grid"))
                    }
                },
                afterSetValue: function() {
                    var t = this.collection && this.collection.container;
                    t && d.a.trigger("balance-band", t)
                }
            }
        },
        _leftProps: function() {
            return {
                attrs: {
                    min: 0,
                    step: function() {
                        return u.a.toUnit(p.a.get("grid"))
                    }
                }
            }
        },
        _font: function(n) {
            var t, e, a, i, r, o, s, l;
            return t = (n = n || "") + "Font.Name", e = n + "Font.Size", a = n + "Font.Bold", i = n + "Font.Italic", r = n + "Font.Strikeout", o = n + "Font.Underline", l = (s = {
                label: "Font",
                fields: {}
            }).fields, n || (s.expression = !0, s.expressionEventName = "edit-font"), l[t] = {
                type: "select",
                label: "Name",
                collection: function() {
                    return p.a.get("font-names")
                },
                defaultValue: p.a.get("default-font-name"),
                setValue: function(t, e) {
                    d.a.trigger("font-name", e, this, n)
                }
            }, l[e] = {
                type: "text",
                label: "Size",
                defaultValue: 10,
                getValue: function() {
                    return this.attr(e)
                }
            }, l[a] = {
                type: "checkbox",
                label: "Bold",
                defaultValue: !1
            }, l[i] = {
                type: "checkbox",
                label: "Italic",
                defaultValue: !1
            }, l[r] = {
                type: "checkbox",
                label: "Strikeout",
                defaultValue: !1
            }, l[o] = {
                type: "checkbox",
                label: "Underline",
                defaultValue: !1
            }, s
        },
        factory: function(t, e) {
            var n = new r;
            return e = e || this.data, n.raw = function(t, e) {
                var n, a = [];
                if (t)
                    for (n = 0; n < t.length; n += 1) a.push(i(t[n], e));
                return a
            }(t, e), g.apply(n, function(t) {
                var e, n, a = [],
                    i = [],
                    r = void 0,
                    o = void 0,
                    s = void 0,
                    l = void 0;
                for (e = 0; e < t.length; e += 1)
                    for (r = t[e], o = Object.keys(r), n = 0; n < o.length; n += 1) s = o[n], -1 < (l = i.indexOf(s)) ? Object(h.a)(a[l], r[s], !0) : (i.push(s), a.push(r[s]));
                return a
            }(n.raw)), n.origin = [].concat(t), n.data = e, n
        }
    }, r.prototype.append = function(t) {
        this.length = 0, g.apply(this, o.factory(c.a.merge(this.origin || [], t), this.data))
    }, r.prototype.remove = function(t) {
        this.length = 0, g.apply(this, o.factory(a(this.origin, t), this.data))
    }, r.prototype.rebuild = function(t, e) {
        this.origin = a(c.a.merge(this.origin || [], t), e), this.length = 0, g.apply(this, o.factory(this.origin, this.data))
    }, e.a = o
}, , , , function(t, e, n) {
    "use strict";
    e.a = function(n, a) {
        var i;
        return function() {
            var t = arguments,
                e = this;
            clearTimeout(i), i = setTimeout(function() {
                return n.apply(e, t)
            }, a || 100)
        }
    }
}, function(t, e, n) {
    "use strict";

    function a(e) {
        n.e(9).then(n.bind(null, 575)).then(function(t) {
            t.create(e)
        })
    }
    var i, l, r, o, s, c, p;
    n.r(e), i = n(0), l = n.n(i), r = n(10), o = n(2), s = n(1), c = n(178), p = n(43), e.default = r.default.createObject(r.default, {
        title: "Bands Data",
        info: "DataBandInfo",
        icon: "icon-162",
        pos: 80,
        type: "DataBand",
        disabled: !1,
        _init: function() {
            r.default._init.call(this), this.defaultValues = {
                "Columns.Count": 0,
                "Columns.Width": 0,
                "Columns.MinRowCount": 0,
                "Columns.Layout": "AcrossThenDown",
                KeepDetail: !1,
                KeepTogether: !1,
                CollectChildRows: !1,
                PrintIfDatasourceEmpty: !1,
                PrintIfDetailEmpty: !1,
                MaxRows: 0,
                RowCount: 1
            }, this.prop({
                Name: "Data",
                Height: 207.874
            }), this.attr({
                Sort: [],
                pos: 35
            })
        },
        getFillTitleColor: function() {
            return "#FFA500"
        },
        canHaveChildren: function(t) {
            var e = t.type || t;
            return r.default.canHaveChildren.apply(this, arguments) || ["DataBand", "DataHeaderBand", "DataFooterBand", "GroupHeaderBand"].includes(e)
        },
        canBeAdded: function() {
            return !0
        },
        canBeSorted: function() {
            return !0
        },
        creatingComponentEnd: function(t, e, n) {
            var a, i = window.DSG.currentReport;
            n && !this.prop("DataSource") && (a = i.connections.pullDSByView(n) || i.dataSources.pullByView(n)) && (this.prop("DataSource", a), this.render())
        },
        dblclick: function() {
            return a(this)
        },
        getContextMenuItems: function() {
            var e = this,
                t = function(t) {
                    return e.onChangeCM(t)
                };
            return [{
                name: o.a.tr("ComponentMenu Edit"),
                type: "default",
                closeAfter: !0,
                onClick: function() {
                    s.a.trigger("activate", e), a(e)
                }
            }, {
                name: o.a.tr("Band AddChildBand"),
                type: "default",
                closeAfter: !0,
                disabled: e.has("ChildBand"),
                onClick: function() {
                    s.a.trigger("add-band", "ChildBand", e)
                }
            }, {
                name: o.a.tr("DataBand AddDetailDataBand"),
                type: "default",
                closeAfter: !0,
                disabled: !!e.has("DataBand"),
                onClick: function() {
                    s.a.trigger("add-band", "DataBand", e)
                }
            }, {
                name: o.a.tr("ComponentMenu Hyperlink"),
                type: "default",
                closeAfter: !0,
                onClick: function() {
                    s.a.trigger("hyperlink-editor", e)
                }
            }, {
                type: "separator"
            }, {
                name: o.a.tr("ComponentMenu CanGrow"),
                type: "checkbox",
                curVal: e.prop("CanGrow"),
                prop: "CanGrow",
                onChange: t
            }, {
                name: o.a.tr("ComponentMenu CanShrink"),
                type: "checkbox",
                curVal: e.prop("CanShrink"),
                prop: "CanShrink",
                onChange: t
            }, {
                name: o.a.tr("ComponentMenu CanBreak"),
                type: "checkbox",
                curVal: e.prop("CanBreak"),
                prop: "CanBreak",
                onChange: t
            }, {
                type: "separator"
            }, {
                name: o.a.tr("ComponentMenu KeepTogether"),
                type: "checkbox",
                curVal: e.prop("KeepTogether"),
                prop: "KeepTogether",
                onChange: t
            }, {
                name: o.a.tr("ComponentMenu KeepDetail"),
                type: "checkbox",
                curVal: e.prop("KeepDetail"),
                prop: "KeepDetail",
                onChange: t
            }, {
                name: o.a.tr("Band StartNewPage"),
                type: "checkbox",
                curVal: e.prop("StartNewPage"),
                prop: "StartNewPage",
                onChange: t
            }, {
                name: o.a.tr("ComponentMenu PrintOnBottom"),
                type: "checkbox",
                curVal: e.prop("PrintOnBottom"),
                prop: "PrintOnBottom",
                onChange: t
            }, {
                type: "separator"
            }, {
                name: o.a.tr("Menu Edit Paste"),
                type: "default",
                disabled: !window.DSG.currentReport.getCurrentPage().buffer.length,
                closeAfter: !0,
                shortcut: "Ctrl + V",
                onClick: function() {
                    setTimeout(function() {
                        s.a.trigger("paste")
                    }, 100)
                }
            }, {
                name: o.a.tr("Menu Edit Delete"),
                type: "default",
                disabled: !e.canBeRemoved(),
                closeAfter: !0,
                shortcut: "Del",
                onClick: function() {
                    s.a.trigger("remove", e)
                }
            }]
        },
        fillPropsNET: function(t) {
            var e, n, a = this;
            r.default.fillPropsNET.apply(this, arguments), e = t.find("> Sort"), n = void 0, e.length && l.a.each(e, function() {
                l.a.each(l()(this).children(), function() {
                    n = {}, l.a.each(this.attributes, function() {
                        n[this.name] = Object(c.a)(this.value)
                    }), a.attr("Sort").push(n)
                }), l()(this).remove()
            })
        },
        toXMLNET: function(t) {
            var s = this;
            return new Promise(function(o) {
                r.default.toXMLNET.call(s, t).then(function(t) {
                    var e, n, a, i, r;
                    return e = +(t = l()(t)).attr("Columns.Count"), n = +t.attr("Columns.Width"), a = s.attr("Sort"), r = i = void 0, Object(p.a)(e) || (e = 0), Object(p.a)(n) || (n = 0), 0 === e ? (t.removeAttr("Columns.Count"), t.removeAttr("Columns.Width")) : 0 !== n ? t.attr("Width", n) : e && (t.attr("Width", s.prop("Width") / s.prop("Columns.Count")), t.removeAttr("Columns.Width")), l.a.each(a, function() {
                        this && this.Expression && (i || (i = l()(t[0].ownerDocument.createElement("Sort")), t.append(i)), i && (r = l()(i[0].ownerDocument.createElement("Sort")), l.a.each(this, function(t, e) {
                            r.attr(t, e)
                        }), i.append(r)))
                    }), o(t.get(0))
                })
            })
        },
        toString: function() {
            var t = this.prop("Name"),
                e = this.prop("DataSource");
            return e && (t += ": " + e), t
        }
    })
}, function(t, e, n) {
    "use strict";
    e.a = function(t) {
        var e, n = [];
        for (e = 0; e < t.length; e += 1) n.includes(t[e]) || n.push(t[e]);
        return n
    }
}, function(t, e, n) {
    "use strict";
    var a = n(24),
        i = n(7),
        r = n(186),
        o = n(28);
    e.a = Object(i.a)(a.a, {
        add: function(t) {
            t && (t.collection && t.collection !== this && t.collection.remove(t), t.collection = this, Object(o.a)(this.entities, t), this.entities.push(t), this.container.$components.append(t.$g), this.container.selfTree && t.selfTree && this.container.selfTree.$container.append(t.selfTree.$main))
        },
        addInStart: function(t) {
            t && (t.collection && t.collection !== this && t.collection.remove(t), t.collection = this, Object(o.a)(this.entities, t), this.entities.unshift(t), this.container.$components.prepend(t.$g), this.container.selfTree && t.selfTree && this.container.selfTree.$container.prepend(t.selfTree.$main))
        },
        addInPos: function(t, e) {
            var n;
            t && (t.collection && t.collection !== this && t.collection.remove(t), t.collection = this, e < 0 && (e = 0), Object(o.a)(this.entities, t), n = this.entities[e - 1], this.entities.splice(e, 0, t), n ? n.type === t.type ? (n.$g.after(t.$g), n.selfTree && t.selfTree && n.selfTree.$main.after(t.selfTree.$main)) : (Object(o.a)(this.entities, t), this.entities.splice(e - 1, 0, t)) : (this.container.$components.prepend(t.$g), this.container.selfTree && t.selfTree && this.container.selfTree.$container.prepend(t.selfTree.$main)))
        },
        find: function(n) {
            var a;
            return this.eachEntity(function(t, e) {
                if (t._id === n) return !(a = [t, e])
            }), a
        },
        findLastByType: function(n) {
            var a;
            return this.eachEntity(function(t, e) {
                t.type === n && (a = [t, e])
            }), a
        },
        componentsIn: function(e, n) {
            var a = [];
            return this.eachEntity(function(t) {
                t.attr("selectable") && Object(r.a)(e, n, [t.prop("Left"), t.attr("right")], [t.prop("Top"), t.attr("bottom")]) && a.push(t)
            }), a
        }
    })
}, function(t, e, n) {
    "use strict";
    var a, r, i, o, s, l, c, p;
    n.r(e), a = n(0), r = n.n(a), i = n(2), o = n(25), s = n(1), l = n(85), c = n(9), p = n(3), e.default = o.default.createObject(o.default, {
        type: "ContainerObject",
        title: "Objects ContainerObject",
        info: "ContainerObjectInfo",
        icon: "icon-135",
        pos: 130,
        _init: function(t) {
            o.default._init.call(this), this.parent = t, this.prop({
                Name: "Container",
                Width: 112.36,
                Height: 35.59
            }), this.attr({
                DefaultBorderColor: "#C0C0C0",
                removeable: !0,
                copyable: !0,
                movable: !0,
                resizableX: !0,
                resizableY: !0,
                resizableXY: !0
            }), this.components = l.a.create(this), this.render(), this.$components = r()(Object(p.a)("g")), this.$g.append(this.$components)
        },
        clone: function() {
            var e = o.default.clone.apply(this, arguments);
            return this.components.isEmpty() || this.components.everyEntity(function(t) {
                (t = t.clone()) && (t.render(), e.put(t))
            }), e
        },
        getContextMenuItems: function() {
            function t(t) {
                return e.onChangeCM(t)
            }
            var e = this;
            return [{
                name: i.a.tr("ComponentMenu Hyperlink"),
                type: "default",
                closeAfter: !0,
                onClick: function() {
                    s.a.trigger("hyperlink-editor", e)
                }
            }, {
                type: "separator"
            }, {
                name: i.a.tr("ComponentMenu CanGrow"),
                type: "checkbox",
                curVal: e.prop("CanGrow"),
                prop: "CanGrow",
                onChange: t
            }, {
                name: i.a.tr("ComponentMenu CanShrink"),
                type: "checkbox",
                curVal: e.prop("CanShrink"),
                prop: "CanShrink",
                onChange: t
            }, {
                name: i.a.tr("ComponentMenu GrowToBottom"),
                type: "checkbox",
                curVal: e.prop("GrowToBottom"),
                prop: "GrowToBottom",
                onChange: t
            }, {
                type: "separator"
            }, {
                name: i.a.tr("Menu Edit Cut"),
                type: "default",
                closeAfter: !0,
                shortcut: "Ctrl + X",
                onClick: function() {
                    s.a.trigger("cut", r()(e))
                }
            }, {
                name: i.a.tr("Menu Edit Copy"),
                type: "default",
                closeAfter: !0,
                shortcut: "Ctrl + C",
                onClick: function() {
                    s.a.trigger("copy", r()(e))
                }
            }, {
                name: i.a.tr("Menu Edit Paste"),
                type: "default",
                disabled: !window.DSG.currentReport.getCurrentPage().buffer.length,
                closeAfter: !0,
                shortcut: "Ctrl + V",
                onClick: function() {
                    setTimeout(function() {
                        s.a.trigger("paste")
                    }, 100)
                }
            }, {
                name: i.a.tr("Menu Edit Delete"),
                type: "default",
                disabled: !e.canBeRemoved(),
                closeAfter: !0,
                shortcut: "Del",
                onClick: function() {
                    s.a.trigger("remove", e)
                }
            }, {
                type: "separator"
            }, {
                name: i.a.tr("Layout BringToFront"),
                type: "default",
                closeAfter: !0,
                onClick: function() {
                    s.a.trigger("bring-to-front", e)
                }
            }, {
                name: i.a.tr("Layout SendToBack"),
                type: "default",
                closeAfter: !0,
                onClick: function() {
                    s.a.trigger("send-to-back", e)
                }
            }]
        },
        appendEdges: function() {
            this.$moveBlock.css({
                stroke: this.attr("DefaultBorderColor"),
                "stroke-dasharray": "",
                "stroke-width": "1px"
            })
        },
        appendAngleSlider: function() {},
        activate: function() {
            o.default.activate.call(this), this.$blanket || (this.$blanket = r()(Object(p.a)("rect"))), this.$blanket.attr({
                width: this.prop("Width"),
                height: this.prop("Height")
            }), this.$blanket.attr("fill", "rgba(160, 195, 255, 0.5)")
        },
        deactivate: function() {
            return o.default.deactivate.call(this), this.$blanket && this.$blanket.removeAttr("fill"), this
        },
        put: function(t) {
            this.components.add(t.g.component)
        },
        canHaveChildren: function() {
            return !0
        },
        fillPropsNET: function(t) {
            c.default.fillPropsNET.apply(this, arguments);
            var n, e = t.children();
            n = this, e.each(function() {
                var t = r()(this),
                    e = window.DSG.components[t.prop("tagName")];
                e && ((e = e.create()).fillPropsNET(t), e.render(), n.put(e))
            })
        },
        toXMLNET: function(i) {
            var t = this;
            return new Promise(function(a) {
                o.default.toXMLNET.call(t, i).then(function(e) {
                    e = r()(e), i = Object.assign({
                        parentNode: e[0]
                    }, i);
                    var n = [];
                    t.components.everyEntity(function(t) {
                        n.push(t.toXMLNET(i))
                    }), Promise.all(n).then(function(t) {
                        return t.forEach(function(t) {
                            e.append(t)
                        }), a(e[0])
                    })
                })
            })
        }
    })
}, function(t, e, n) {
    "use strict";
    var a = n(24),
        i = n(7);
    e.a = Object(i.a)(a.a, {
        everyEntity: function(t, e) {
            var n, a, i = 1 < arguments.length && void 0 !== e ? e : 0,
                r = this.all();
            for (n = 0, a = r.length; n < a; n += 1)
                if (!1 === t.call(this, r[n], n, i) || r[n].dataSources.count() && !1 === r[n].dataSources.everyEntity(t, i + 1)) return !1;
            return this
        },
        pullByView: function(t) {
            if (void 0 === t) throw new Error("view must be defined");
            var e = t.match(/^\[([^.]+)./);
            return (e = e && e[1] ? e[1] : t) ? this.container.dataSources.findOneAmongAll({
                Alias: e
            }) || this.container.dataSources.findOneAmongAll({
                Name: e
            }) || this.container.dataSources.findOneAmongAll({
                _id: e
            }) : null
        }
    })
}, function(t, e, n) {
    "use strict";
    e.a = function(t) {
        return t === +t && t !== (0 | t)
    }
}, function(t, e, n) {
    "use strict";
    var a = n(0),
        r = n.n(a),
        i = n(2),
        o = n(25),
        s = n(1),
        l = n(85),
        c = n(3),
        p = n(39),
        d = n(30);
    e.a = o.default.createObject(o.default, {
        icon: "icon-214",
        type: "TableCell",
        _init: function(t) {
            o.default._init.call(this), this.parent = t, this.prop({
                Name: "Cell",
                Text: "",
                ColSpan: 1,
                RowSpan: 1
            }), this.attr({
                DefaultBorderColor: "#C0C0C0",
                removeable: !1,
                copyable: !1,
                movable: !1,
                resizableX: !0,
                resizableY: !0,
                resizableXY: !1
            }), this.components = l.a.create(this), this.render(), Object(d.a)(this.$moveBlock[0], "move"), Object(d.a)(this.$moveBlock[0], "move-decor"), Object(d.a)(this.content, "move"), this.$components = r()(Object(c.a)("g")), this.$g.append(this.$components), Object(p.a)(this.g, "cell")
        },
        clone: function() {
            var e = o.default.clone.apply(this, arguments);
            return this.components.isEmpty() || this.components.everyEntity(function(t) {
                (t = t.clone()) && (t.render(), e.put(t))
            }), e
        },
        getContextMenuItems: function() {
            var t = this;
            return [{
                name: i.a.tr("ComponentMenu Edit"),
                type: "default",
                closeAfter: !0,
                onClick: function() {
                    s.a.trigger("activate", t), t.dblclick()
                }
            }, {
                name: i.a.tr("TextObject Format"),
                type: "default",
                closeAfter: !0,
                onClick: function() {
                    s.a.trigger("activate", t), s.a.trigger("format", t)
                }
            }]
        },
        getTable: function() {
            return this.parent.parent.matrix || this.parent.parent
        },
        appendEdges: function() {
            this.$moveBlock.css({
                stroke: this.attr("DefaultBorderColor"),
                "stroke-dasharray": "",
                "stroke-width": "1px"
            })
        },
        resizingComponents: function() {
            var t, n;
            return o.default.resizingComponents.apply(this, arguments), t = this.resizingElements.map(function(t) {
                return t.className
            }), n = [t.indexOf("e-resize"), t.indexOf("s-resize")], this.resizingElements.forEach(function(t, e) {
                ~n.indexOf(e) || (t.hidden = !0)
            }), this
        },
        appendAngleSlider: function() {},
        getPosInRow: function() {
            return this.parent.cells.indexOf(this)
        },
        getPosInColumn: function() {
            return this.getTable().rows.indexOf(this.parent)
        },
        prev: function() {
            return this.parent.cells[this.getPosInRow() - 1]
        },
        next: function() {
            return this.parent.cells[this.getPosInRow() + this.prop("ColSpan")]
        },
        under: function() {
            var t = this.getTable().rows[this.getPosInColumn() + this.prop("RowSpan")];
            return t ? t.cells[this.getPosInRow()] : null
        },
        over: function() {
            var t = this.getTable().rows[this.getPosInColumn() - 1];
            return t ? t.cells[this.getPosInRow()] : null
        },
        afterInRow: function(t) {
            return this.parent.cells.slice(this.getPosInRow() + (t ? 0 : 1), this.parent.cells.length)
        },
        afterInColumn: function(t) {
            var n = [],
                a = this.getPosInRow(),
                i = this.getPosInColumn() + (t ? 0 : 1);
            return this.getTable()._eachRow(function(t, e) {
                i <= t && n.push(e.cells[a])
            }), n
        },
        activate: function() {
            o.default.activate.call(this), this.$blanket || (this.$blanket = r()(Object(c.a)("rect")), this.$moveBlock.after(this.$blanket)), this.$blanket.attr({
                width: this.prop("Width"),
                height: this.prop("Height")
            }), this.$blanket.attr("fill", "rgba(160, 195, 255, 0.5)"), this.showResizingComponents()
        },
        deactivate: function() {
            return o.default.deactivate.call(this), this.$blanket && this.$blanket.removeAttr("fill"), this
        },
        put: function(t) {
            this.components.add(t.g.component)
        },
        mouseStart: function(t) {
            return s.a.trigger("activate", this), !!r()(t.target).is(".resizing-component > ") && null
        },
        resizing: function(t, e) {
            var n, a = this.getTable();
            "e" === e.dir ? (n = this.getPosInRow(), a.columns[n].prop("Width", this.prop("Width")), a.update()) : "s" === e.dir && (n = this.getPosInColumn(), a.rows[n].prop("Height", this.prop("Height")), a.update())
        },
        resizingEnd: function() {
            s.a.trigger("balance-band", this.getTable().getContainer())
        },
        canHaveChildren: function(t) {
            return "TableObject" !== t && "MatrixObject" !== t
        },
        toXMLNET: function(i) {
            var t = this;
            return new Promise(function(a) {
                o.default.toXMLNET.call(t, i).then(function(e) {
                    e = r()(e), i = Object.assign({
                        parentNode: e[0]
                    }, i);
                    var n = [];
                    t.components.everyEntity(function(t) {
                        n.push(t.toXMLNET(i))
                    }), Promise.all(n).then(function(t) {
                        return t.forEach(function(t) {
                            e.append(t)
                        }), e.removeAttr("Left"), e.removeAttr("Top"), e.removeAttr("Width"), e.removeAttr("Height"), a(e[0])
                    })
                })
            })
        }
    })
}, function(t, e, n) {
    "use strict";

    function o(t, e, n, a) {
        return t.setAttributeNS(a || null, e, n)
    }

    function a(t, e, n) {
        return t.getAttributeNS(n || null, e)
    }

    function i(t, e, n) {
        for (var a, i = Object.keys(e), r = i.length; r--;) o(t, a = i[r], e[a], n)
    }
    n.d(e, "b", function() {
        return o
    }), n.d(e, "a", function() {
        return a
    }), n.d(e, "c", function() {
        return i
    })
}, , , , , , , , , , function(t, e, n) {
    "use strict";
    var a, o, s, i, r, l, c;
    n.r(e), a = n(0), o = n.n(a), s = n(31), i = n(85), r = n(7), l = n(17), c = n(3), e.default = Object(r.a)(s.a, {
        title: "Form",
        info: "Form info",
        icon: "icon-136",
        type: "DialogPage",
        init: function() {
            this.report = null
        },
        create: function() {
            return this.createObject(this, {
                init: function() {
                    this.SM.add(this), this._id = "f" + Object(l.a)(), this.unparsed = [], this.attr({
                        isHidden: !0,
                        activated: !1,
                        removed: !1
                    }), this.prop({
                        Name: "Form",
                        Width: 300,
                        Height: 300
                    }), this.createWorkspace("form"), this.$g.data("form", this), this.defaultValues = {
                        FormBorderStyle: "FixedDialog",
                        BackColor: "#F0F0F0",
                        RightToLeft: "No"
                    }, this.components = i.a.create(this)
                }
            })
        },
        show: function() {
            s.a.show.apply(this, arguments), this.render()
        },
        updateSize: function() {
            s.a.updateSize.apply(this, arguments), this.$background.attr({
                width: this.prop("Width"),
                height: this.prop("Height")
            })
        },
        render: function() {
            s.a.render.call(this), this.$background || (this.$background = o()(Object(c.a)("rect")), this.$workspace.append(this.$background)), this.$components || (this.$components = o()(Object(c.a)("g")), this.$workspace.append(this.$components)), this.updateSize(), this.$background.css("fill", this.prop("BackColor")), this.$upControlElements || (this.$upControlElements = o()(Object(c.a)("g")), this.$workspace.append(this.$upControlElements))
        },
        put: function(t) {
            return this.components.add(t), this
        },
        fillPropsNET: function() {
            return s.a.fillPropsNET.apply(this, arguments), this
        },
        toXMLNET: function(i) {
            var r = this;
            return new Promise(function(a) {
                s.a.toXMLNET.call(r, i).then(function(e) {
                    e = o()(e), i = Object.assign({
                        parentNode: e[0]
                    }, i);
                    var n = [];
                    r.components.eachEntity(function(t) {
                        n.push(t.toXMLNET(i)), e.append(t.toXMLNET(i))
                    }), Promise.all(n).then(function(t) {
                        return t.forEach(function(t) {
                            return e.append(t)
                        }), o.a.each(r.unparsed, function() {
                            e.append(this)
                        }), a(e[0])
                    })
                })
            })
        },
        isDialog: function() {
            return !0
        }
    })
}, function(t, e, n) {
    "use strict";
    n.r(e);
    var a = n(10);
    e.default = a.default.createObject(a.default, {
        title: "Bands ReportTitle",
        info: "ReportTitleBandInfo",
        icon: "icon-154",
        pos: 10,
        type: "ReportTitleBand",
        typevcl: "TfrxReportTitle",
        disabled: !1,
        _init: function() {
            a.default._init.call(this), this.prop("Name", "ReportTitle"), this.attr("pos", 10)
        }
    })
}, function(t, e, n) {
    "use strict";
    n.r(e);
    var a = n(10);
    e.default = a.default.createObject(a.default, {
        title: "Bands PageHeader",
        info: "PageHeaderBandInfo",
        icon: "icon-156",
        pos: 30,
        type: "PageHeaderBand",
        disabled: !1,
        _init: function() {
            a.default._init.call(this), this.prop("Name", "PageHeader"), this.attr("pos", 20)
        }
    })
}, function(t, e, n) {
    "use strict";
    n.r(e);
    var a = n(10);
    e.default = a.default.createObject(a.default, {
        title: "Bands PageFooter",
        info: "PageFooterBandInfo",
        icon: "icon-157",
        pos: 40,
        type: "PageFooterBand",
        disabled: !1,
        _init: function() {
            a.default._init.call(this), this.prop("Name", "PageFooter"), this.attr("pos", 70)
        }
    })
}, function(t, e, n) {
    "use strict";
    var a, i, r, o, s, l, c;
    n.r(e), a = n(0), i = n.n(a), r = n(2), o = n(1), s = n(9), l = n(3), c = n(5), e.default = s.default.createObject(s.default, {
        title: "Objects SubreportObject",
        info: "SubreportObjectInfo",
        icon: "icon-104",
        pos: 50,
        type: "SubreportObject",
        disabled: !1,
        _init: function() {
            s.default._init.apply(this, arguments), this.defaultValues = {
                PrintOnParent: !1
            }, this.prop({
                Name: "Subreport",
                Width: 70,
                Height: 40,
                "Fill.Color": "#C9C9C9"
            })
        },
        getReportPage: function() {
            return window.DSG.currentReport.pages.findOneAmongAll({
                Name: this.prop("ReportPage"),
                isSubreport: !0
            })
        },
        getContextMenuItems: function() {
            var e = this;
            return [{
                name: r.a.tr("SubreportObject PrintOnParent"),
                type: "checkbox",
                curVal: e.prop("PrintOnParent"),
                prop: "PrintOnParent",
                onChange: function(t) {
                    return e.onChangeCM(t)
                }
            }, {
                type: "separator"
            }, {
                name: r.a.tr("Menu Edit Cut"),
                type: "default",
                disabled: !0,
                closeAfter: !0,
                shortcut: "Ctrl + X",
                onClick: function() {
                    o.a.trigger("cut", i()(e))
                }
            }, {
                name: r.a.tr("Menu Edit Copy"),
                type: "default",
                disabled: !0,
                closeAfter: !0,
                shortcut: "Ctrl + C",
                onClick: function() {
                    o.a.trigger("copy", i()(e))
                }
            }, {
                name: r.a.tr("Menu Edit Paste"),
                type: "default",
                disabled: !window.DSG.currentReport.getCurrentPage().buffer.length,
                closeAfter: !0,
                shortcut: "Ctrl + V",
                onClick: function() {
                    setTimeout(function() {
                        o.a.trigger("paste")
                    }, 100)
                }
            }, {
                name: r.a.tr("Menu Edit Delete"),
                type: "default",
                disabled: !e.canBeRemoved(),
                closeAfter: !0,
                shortcut: "Del",
                onClick: function() {
                    o.a.trigger("remove", e)
                }
            }, {
                type: "separator"
            }, {
                name: r.a.tr("Layout BringToFront"),
                type: "default",
                closeAfter: !0,
                onClick: function() {
                    o.a.trigger("bring-to-front", e)
                }
            }, {
                name: r.a.tr("Layout SendToBack"),
                type: "default",
                closeAfter: !0,
                onClick: function() {
                    o.a.trigger("send-to-back", e)
                }
            }]
        },
        render: function() {
            var t, e, n = this.getReportPage();
            return s.default.render.apply(this, arguments), this.$contentGroup.empty(), t = i()(Object(l.a)("g")), e = i()(Object(l.a)("text", {
                class: "text"
            })), t.append(e), this.$contentGroup.append(t), e.text(this.prop("Name")), e.css({
                "font-size": 10,
                fill: "#000000",
                "text-anchor": "start"
            }), Object(c.a)(t[0], "transform", "translate(0,10)"), n && n.attr("removed") && n.attr("removed", !1), this.$g
        },
        remove: function() {
            s.default.remove.apply(this, arguments), window.DSG.currentReport.removePage(this.getReportPage())
        },
        fillPropsNET: function() {
            s.default.fillPropsNET.apply(this, arguments), i.a.each(window.DSG.currentReport.pages.findBy({
                Name: this.prop("ReportPage")
            }), function() {
                this.attr("isSubreport", !0)
            })
        }
    })
}, function(t, e, n) {
    "use strict";

    function i(t, e) {
        var n, a = this.getTable(),
            i = a.prop("Left"),
            r = a.prop("Top") + a.collection.container.prop("Top"),
            o = e.x - (i + this.prop("Left")),
            s = i + this.attr("right") - e.x,
            l = e.y - (r + this.prop("Top")),
            c = r + this.attr("bottom") - e.y,
            p = this.prop("Width"),
            d = this.prop("Height"),
            u = this.attr("droppable-dir");
        this.$droppableBorder || (this.$droppableBorder = f()(Object(b.a)("polyline", {
            class: "droppable-border"
        })), this.$g.append(this.$droppableBorder)), this.$droppableBorder.addClass("fr-hidden"), Object(y.a)(this.$moveBlock[0], "drop-in-left"), Object(y.a)(this.$moveBlock[0], "drop-in-right"), Object(y.a)(this.$moveBlock[0], "drop-in-top"), Object(y.a)(this.$moveBlock[0], "drop-in-bottom"), u && (o < s && o < l && o < c && "1" === u[0] && (n = "0,0 0," + d, this.$droppableBorder.attr("points", n), Object(v.a)(this.$droppableBorder[0], "drop-in-left")), s < o && s < l && s < c && "1" === u[2] && (n = p + ",0 " + p + "," + d, this.$droppableBorder.attr("points", n), Object(v.a)(this.$droppableBorder[0], "drop-in-right")), l < o && l < s && l < c && "1" === u[1] && (n = "0,0 " + p + ",0", this.$droppableBorder.attr("points", n), Object(v.a)(this.$droppableBorder[0], "drop-in-top")), c < o && c < s && c < l && "1" === u[3] && (n = "0," + d + " " + p + "," + d, this.$droppableBorder.attr("points", n), Object(v.a)(this.$droppableBorder[0], "drop-in-bottom")), this.$droppableBorder.removeClass("fr-hidden"))
    }

    function r() {
        this.$droppableBorder && (this.$droppableBorder.remove(), this.$droppableBorder = null)
    }

    function u(t, e, n) {
        var a;
        this.expr ? -1 === (a = t.indexOf(this.expr)) ? t.push(e) : (n && -1 !== f.a.inArray(n, [1, 2]) || (a += 1), t.splice(a, 0, e)) : t.push(e)
    }

    function o(t, e, n) {
        var a, i = this.attr("m-type"),
            r = this.getTable(),
            o = {
                Expression: n
            },
            s = function(t) {
                return t.$droppableBorder.is(".drop-in-left") ? 1 : t.$droppableBorder.is(".drop-in-top") ? 2 : t.$droppableBorder.is(".drop-in-right") ? 3 : t.$droppableBorder.is(".drop-in-bottom") ? 4 : 0
            }(this),
            l = r.attr("MatrixRows"),
            c = r.attr("MatrixColumns"),
            p = r.attr("MatrixCells"),
            d = window.DSG.currentReport;
        switch (n && !r.prop("DataSource") && (a = d.connections.pullDSByView(n) || d.dataSources.pullByView(n)) && (r.prop("DataSource", a), m.a.trigger("update-properties-panel", r)), i) {
            case 1:
                u.call(this, l, o, s), r.droppedOnRow(this, s, o);
                break;
            case 0:
                u.call(this, c, o, s), r.droppedOnColumn(this, s, o);
                break;
            case 2:
                u.call(this, p, o, s), r.droppedOnCell(this, s, o)
        }
        this.deleteComponentOver(), m.a.trigger("balance-band", r.collection.container)
    }

    function s(e) {
        e.expr && (e.getContextMenuItems = function() {
            function t(t) {
                e.onChangeCM(t, e.expr)
            }
            return [{
                name: g.a.tr("ComponentMenu Edit"),
                type: "default",
                closeAfter: !0,
                onClick: function() {
                    m.a.trigger("activate", e), e.dblclick()
                }
            }, {
                name: g.a.tr("TextObject Format"),
                type: "default",
                closeAfter: !0,
                onClick: function() {
                    m.a.trigger("activate", e), m.a.trigger("format", e)
                }
            }, {
                name: g.a.tr("MatrixCell Function"),
                type: "list",
                items: [{
                    name: g.a.tr("Misc None"),
                    type: "checkbox",
                    prop: "Function",
                    curVal: "None" === e.expr.Function,
                    val: "None",
                    onChange: t,
                    closeAfter: !0
                }, {
                    name: g.a.tr("TotalEditor Sum"),
                    type: "checkbox",
                    prop: "Function",
                    curVal: "Sum" === e.expr.Function || void 0 === e.expr.Function,
                    val: "Sum",
                    onChange: t,
                    closeAfter: !0
                }, {
                    name: g.a.tr("TotalEditor Min"),
                    type: "checkbox",
                    prop: "Function",
                    curVal: "Min" === e.expr.Function,
                    val: "Min",
                    onChange: t,
                    closeAfter: !0
                }, {
                    name: g.a.tr("TotalEditor Max"),
                    type: "checkbox",
                    prop: "Function",
                    curVal: "Max" === e.expr.Function,
                    val: "Max",
                    onChange: t,
                    closeAfter: !0
                }, {
                    name: g.a.tr("TotalEditor Avg"),
                    type: "checkbox",
                    prop: "Function",
                    curVal: "Average" === e.expr.Function,
                    val: "Average",
                    onChange: t,
                    closeAfter: !0
                }, {
                    name: g.a.tr("TotalEditor Count"),
                    type: "checkbox",
                    prop: "Function",
                    curVal: "Count" === e.expr.Function,
                    val: "Count",
                    onChange: t,
                    closeAfter: !0
                }, {
                    name: g.a.tr("TotalEditor Custom"),
                    type: "checkbox",
                    prop: "Function",
                    curVal: "Custom" === e.expr.Function,
                    val: "Custom",
                    onChange: t,
                    closeAfter: !0
                }]
            }, {
                name: g.a.tr("MatrixCell Percent"),
                type: "list",
                items: [{
                    name: g.a.tr("Misc None"),
                    type: "checkbox",
                    prop: "Percent",
                    curVal: "None" === e.expr.Percent || void 0 === e.expr.Percent,
                    val: "None",
                    onChange: t,
                    closeAfter: !0
                }, {
                    name: g.a.tr("MatrixCell PercentColumnTotal"),
                    type: "checkbox",
                    prop: "Percent",
                    curVal: "ColumnTotal" === e.expr.Percent,
                    val: "ColumnTotal",
                    onChange: t,
                    closeAfter: !0
                }, {
                    name: g.a.tr("MatrixCell PercentRowTotal"),
                    type: "checkbox",
                    prop: "Percent",
                    curVal: "RowTotal" === e.expr.Percent,
                    val: "RowTotal",
                    onChange: t,
                    closeAfter: !0
                }, {
                    name: g.a.tr("MatrixCell PercentGrandTotal"),
                    type: "checkbox",
                    prop: "Percent",
                    curVal: "GrandTotal" === e.expr.Percent,
                    val: "GrandTotal",
                    onChange: t,
                    closeAfter: !0
                }]
            }]
        })
    }

    function h(t, e, n, a) {
        e && f.a.isPlainObject(e) && t.prop(e), t.attr("droppable-dir", n), t.attr("m-type", a), t.creatingComponentOver = i, t.deleteComponentOver = r, t.creatingComponentEnd = o, t.attr("droppable-component", !0), t.attr("droppable-view", !0), 2 === a ? s(t) : function(t) {
            var o;
            t.expr && (t.getContextMenuTitle = function() {
                return "{0}: {1}".format(this.toString(), this.prop("Text"))
            }, t.getContextMenuItems = function() {
                var r = this;
                return o = function(t) {
                    r.onChangeCM(t, r.expr)
                }, [{
                    name: g.a.tr("DataBandEditor Sort"),
                    type: "list",
                    items: [{
                        name: g.a.tr("GroupBandEditor Ascending"),
                        type: "checkbox",
                        prop: "Sort",
                        curVal: "Ascending" === r.expr.Sort || void 0 === r.expr.Sort,
                        val: "Ascending",
                        onChange: o,
                        closeAfter: !0
                    }, {
                        name: g.a.tr("GroupBandEditor Descending"),
                        type: "checkbox",
                        prop: "Sort",
                        curVal: "Descending" === r.expr.Sort,
                        val: "Descending",
                        onChange: o,
                        closeAfter: !0
                    }, {
                        name: g.a.tr("GroupBandEditor NoSort"),
                        type: "checkbox",
                        prop: "Sort",
                        curVal: "None" === r.expr.Sort,
                        val: "None",
                        onChange: o,
                        closeAfter: !0
                    }]
                }, {
                    name: g.a.tr("Dictionary Totals"),
                    type: "checkbox",
                    curVal: void 0 === r.expr.Totals || !1 !== r.expr.Totals,
                    prop: "Totals",
                    onChange: function(t) {
                        function e(t, e, n) {
                            for (var a = t; a < t + e; a += 1) i[n](a)
                        }
                        var n, i = r.getTable(),
                            a = i.attr("MatrixCells");
                        o(t), !1 === t.curVal ? 1 === r.attr("m-type") ? (n = r.under()) && e(n.getPosInColumn(), n.prop("RowSpan"), "removeRow") : (n = r.next()) && e(n.getPosInRow(), n.prop("ColSpan"), "removeColumn") : 1 === r.attr("m-type") ? e(r.getPosInColumn() + 1, a.vert && a.length || 1, "appendRow") : e(r.getPosInRow() + 1, a.horz && a.length || 1, "appendColumn"), i.adjust(), i.update()
                    }
                }, {
                    name: g.a.tr("Band StartNewPage"),
                    type: "checkbox",
                    curVal: r.expr.PageBreak,
                    prop: "PageBreak",
                    onChange: o
                }, {
                    name: g.a.tr("MatrixCell SuppressTotals"),
                    type: "checkbox",
                    curVal: r.expr.SuppressTotals,
                    prop: "SuppressTotals",
                    onChange: o
                }, {
                    name: g.a.tr("MatrixCell TotalsFirst"),
                    type: "checkbox",
                    curVal: r.expr.TotalsFirst,
                    prop: "TotalsFirst",
                    onChange: o
                }]
            })
        }(t), t.render()
    }
    var a, f, g, m, d, b, v, y, c, C;
    n.r(e), a = n(0), f = n.n(a), g = n(2), m = n(1), d = n(33), b = n(3), v = n(39), y = n(30), c = n(6), C = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function(t) {
        return typeof t
    } : function(t) {
        return t && "function" == typeof Symbol && t.constructor === Symbol && t !== Symbol.prototype ? "symbol" : typeof t
    }, e.default = d.default.createObject(d.default, {
        title: "Objects MatrixObject",
        info: "MatrixObjectInfo",
        icon: "icon-142",
        pos: 70,
        type: "MatrixObject",
        disabled: !1,
        _init: function() {
            d.default._init.apply(this, arguments), this.defaultValues = {
                Layout: "AcrossThenDown",
                WrappedGap: 0,
                AdjustSpannedCellsWidth: !1,
                RepeatHeaders: !0,
                AutoSize: !1,
                CellsSideBySide: !1,
                KeepCellsSideBySide: !1,
                MatrixEventStylePriority: "Rows",
                ShowTitle: !1,
                Style: "No style"
            }, this.prop("Name", "Matrix"), this.attr({
                RowCount: 2,
                ColumnCount: 2,
                MatrixColumns: [],
                MatrixRows: [],
                MatrixCells: [],
                Styles: {
                    "No style": {
                        cr: "transparent",
                        cells: "transparent",
                        bc: "#C0C0C0"
                    },
                    White: {
                        cr: "#FFFFFF",
                        cells: "#FFFFFF",
                        bc: "#C0C0C0"
                    },
                    Gray: {
                        cr: "#DCDCDC",
                        cells: "#F5F5F5",
                        bc: "#FFFFFF"
                    },
                    Orange: {
                        cr: "#FFDA46",
                        cells: "#FFEB9B",
                        bc: "#FFFFFF"
                    },
                    Green: {
                        cr: "#7BA400",
                        cells: "#9ED200",
                        bc: "#FFFFFF"
                    },
                    "Green and Orange": {
                        cr: "#98CC00",
                        cells: "#FFCC00",
                        bc: "#FFFFFF"
                    },
                    Blue: {
                        cr: "#97BDFD",
                        cells: "#BAD3FE",
                        bc: "#FFFFFF"
                    },
                    "Blue and White": {
                        cr: "#97BDFD",
                        cells: "#FFFFFF",
                        bc: "#FFFFFF"
                    },
                    "Gray and Orange": {
                        cr: "#808080",
                        cells: "#FFCC00",
                        bc: "#FFFFFF"
                    },
                    "Blue and Orange": {
                        cr: "#97BDFD",
                        cells: "#FFCF6A",
                        bc: "#FFFFFF"
                    },
                    "Orange and White": {
                        cr: "#FFCC00",
                        cells: "#FFFFFF",
                        bc: "#FFFFFF"
                    }
                }
            })
        },
        createCell: function(t) {
            var e = d.default.createCell.call(this, t);
            return e.attr("droppable-view", !1), e
        },
        showResizingComponents: function() {
            this.prop("AutoSize") ? this.hideResizingComponents() : d.default.showResizingComponents.apply(this, arguments)
        },
        clone: function() {
            var t, e, n = d.default.clone.apply(this, arguments),
                a = this.attr("MatrixColumns"),
                i = this.attr("MatrixRows"),
                r = this.attr("MatrixCells"),
                o = [],
                s = [],
                l = [];
            for (t = 0, e = a.length; t < e; t += 1) o.push(Object(c.a)({}, a[t]));
            for (t = 0, e = i.length; t < e; t += 1) s.push(Object(c.a)({}, i[t]));
            for (t = 0, e = r.length; t < e; t += 1) l.push(Object(c.a)({}, r[t]));
            return n.attr("MatrixColumns", o), n.attr("MatrixRows", s), n.attr("MatrixCells", l), n.bindEvents(), n
        },
        getContextMenuItems: function() {
            var i = this,
                r = i.onChangeCM.bind(i);
            return [{
                name: g.a.tr("MatrixObject ShowTitle"),
                type: "checkbox",
                curVal: i.prop("ShowTitle"),
                prop: "ShowTitle",
                onChange: function(t) {
                    r(t), i.onShowTitleChange(t.prop, t.curVal)
                }
            }, {
                name: g.a.tr("TableObject RepeatHeaders"),
                type: "checkbox",
                curVal: i.prop("RepeatHeaders"),
                prop: "RepeatHeaders",
                onChange: r
            }, {
                name: g.a.tr("MatrixObject CellsSideBySide"),
                type: "checkbox",
                curVal: i.prop("CellsSideBySide"),
                prop: "CellsSideBySide",
                onChange: r
            }, {
                type: "separator"
            }, {
                name: g.a.tr("MatrixObject Style"),
                type: "list",
                items: function() {
                    function e(t) {
                        r(t), i.colorize()
                    }
                    var n = [],
                        a = i.prop("Style");
                    return f.a.each(i.attr("Styles"), function(t) {
                        n.push({
                            name: g.a.tr(t),
                            type: "checkbox",
                            prop: "Style",
                            curVal: a === t,
                            val: t,
                            closeAfter: !0,
                            onChange: e
                        })
                    }), n
                }
            }]
        },
        getCurStyle: function() {
            return this.attr("Styles")[this.prop("Style")]
        },
        _setDefaultCellValues: function(t, e, n) {
            var a = e;
            a && ((a = a.match(/.([^.]+)\]$/)) && a[1] ? (a = a[1], n || (a = "[" + a + "]")) : a = e), t.prop("Text", a || n || ""), t.prop("TextFill.Color", t.defaultValues["TextFill.Color"]), t.prop("Fill.Color", t.defaultValues["Fill.Color"]), t.prop("HorzAlign", "Center"), t.prop("VertAlign", "Center")
        },
        colorize: function(t) {
            function n(t) {
                a._setDefaultCellValues(t, null, t.prop("Text")), t.getPosInRow() < e[0] || t.getPosInColumn() < e[1] ? t.prop("Fill.Color", i.cr) : t.prop("Fill.Color", i.cells), t.prop("Border.Lines", "All"), t.prop("Border.Width", 1), t.prop("Border.Style", "Solid"), t.prop("Border.Color", i.bc || t.prop("Border.Color")), t.render()
            }
            var e, a = this,
                i = this.getCurStyle();
            e = this.getFirstCellPos(), t ? n(t) : this._eachCell(function(t, e) {
                n(e)
            })
        },
        onShowTitleChange: function(t, e) {
            if (e && !this.attr("hasTitle")) {
                var n = this.appendRow(0),
                    a = 0,
                    i = n.cells.length;
                for (this.attr("hasTitle", !0); a < i; a += 1) this.colorize(n.cells[a])
            } else !e && this.attr("hasTitle") && (this.removeRow(0), this.attr("hasTitle", !1));
            this.adjustTitle(), this.update()
        },
        afterAlign: function() {
            var t = this.rows[0].cells[1],
                e = this.rows[1].cells[0],
                n = this.rows[1].cells[1];
            h(t, {
                Text: g.a.tr("Matrix NewColumn"),
                "TextFill.Color": "gray"
            }, null, 0), h(e, {
                Text: g.a.tr("Matrix NewRow"),
                "TextFill.Color": "gray"
            }, null, 1), h(n, {
                Text: g.a.tr("Matrix NewCell"),
                "TextFill.Color": "gray"
            }, null, 2)
        },
        _eachCR: function(t, e) {
            var n, a, i, r, o, s, l, c, p, d, u = this.attr("MatrixCells");
            for (s = "ColSpan" === e ? (o = "horz", r = "RowSpan", "next") : (o = "vert", r = "ColSpan", "under"), l = t.length - 1; 0 <= l; l -= 1) {
                for (p = t[l].cell, a = (a || 0) + (i = u[o] && u.length ? u.length : 1), d = p[s](), c = a - 1; 0 < c; c -= 1) d && (d.prop("ColSpan", 1), d.prop("RowSpan", 1), this._setDefaultCellValues(d), d = d[s]());
                p.prop(e, a), n = p[s](), !1 !== t[l].Totals ? n && (n.prop(r, t.length - l), n.prop(e, i), this._setDefaultCellValues(n, g.a.tr("TotalEditor Total"))) : a -= 1, this._setDefaultCellValues(p, t[l].Expression)
            }
        },
        adjust: function() {
            this.adjustCells(), this._eachCR(this.attr("MatrixColumns"), "ColSpan"), this._eachCR(this.attr("MatrixRows"), "RowSpan"), this.adjustTitle(), this.colorize()
        },
        adjustTitle: function() {
            var t = this.attr("MatrixRows").length,
                e = this.rows[0].cells[0],
                n = 1;
            this.prop("ShowTitle") && (t && (n = t, this.attr("MatrixCells").vert && (n += 1), e.prop("ColSpan", n)), e = this.rows[0].cells[n], n = this.attr("ColumnCount") - e.getPosInRow(), e.prop("ColSpan", n))
        },
        getFirstCellPos: function() {
            var t = this.attr("MatrixColumns"),
                e = this.attr("MatrixRows"),
                n = this.attr("MatrixCells"),
                a = [1, 1];
            return e.length ? a[0] = e[e.length - 1].cell.getPosInRow() + (n.vert && 1 < n.length ? 2 : 1) : n.vert && (a[0] += 1), t.length ? a[1] = t[t.length - 1].cell.getPosInColumn() + (n.horz && 1 < n.length ? 2 : 1) : (this.prop("ShowTitle") && (a[1] += 1), n.horz && (a[1] += 1)), a
        },
        adjustCells: function() {
            var t, e, n, a, i, r = this.getFirstCellPos(),
                o = this.attr("MatrixColumns"),
                s = this.attr("MatrixRows"),
                l = this.attr("MatrixCells"),
                c = o.length,
                p = s.length,
                d = l.length,
                u = [];
            if (t = e = this.rows[r[1]].cells[r[0]], l.horz) {
                for (a = c * d + d || 1, i = c - 1; 0 <= i; i -= 1) !1 === o[i].Totals && (a -= d);
                t.afterInRow().length < a && (this.attr("ColumnCount", t.getPosInRow() + a), this.update())
            } else if (l.vert) {
                for (a = p * d + d || 1, i = p - 1; 0 <= i; i -= 1) !1 === s[i].Totals && (a -= d);
                t.afterInColumn().length < a && (this.attr("RowCount", t.getPosInColumn() + a), this.update())
            }
            for (i = 0; i < d; i += 1)(l[i].cell = t).expr = l[i], this._setDefaultCellValues(t, l[i].Expression), l.horz ? (h(t, null, "1010", 2), t = t.next()) : l.vert && (h(t, null, "0101", 2), t = t.under());
            if (t && l.horz) u = t.afterInRow(!0), t = e.over(), n = "next";
            else {
                if (!t || !l.vert) return;
                u = t.afterInColumn(!0), t = e.prev(), n = "under"
            }
            for (1 < u.length && f.a.each(u, function(t, e) {
                    this._setDefaultCellValues(e, "")
                }.bind(this)), i = 0; t;) this._setDefaultCellValues(t, l[i % d].Expression, !0), t = t[n](), i += 1
        },
        droppedOnColumn: function(t, e, n) {
            1 === this.attr("MatrixColumns").length ? this.appendColumn(this.attr("ColumnCount")) : t = 2 === e ? (this.appendColumn(t.getPosInRow() + 2), this.appendRow(t.getPosInColumn() + 0), t.over()) : (this.appendColumn(t.getPosInRow() + 1), this.appendRow(t.getPosInColumn() + 1), t.under()), (n.cell = t).expr = n, h(t, null, "0101", 0), this.adjust(), this.update()
        },
        droppedOnRow: function(t, e, n) {
            1 === this.attr("MatrixRows").length ? this.appendRow(this.attr("RowCount")) : t = 1 === e ? (this.appendRow(t.getPosInColumn() + 2), this.appendColumn(t.getPosInRow() + 0), t.prev()) : (this.appendRow(t.getPosInColumn() + 1), this.appendColumn(t.getPosInRow() + 1), t.next()), (n.cell = t).expr = n, h(t, null, "1010", 1), this.adjust(), this.update()
        },
        droppedOnCell: function(t, e, n) {
            var a = this.attr("MatrixCells");
            (n.cell = t).expr = n, 1 < a.length ? 2 === a.length && (1 === e || 3 === e ? (a.horz = !0, this.appendRow(t.getPosInColumn())) : (a.vert = !0, this.appendColumn(t.getPosInRow()))) : h(t, null, "1111", 2), this.adjust(), this.update()
        },
        bindEvents: function() {
            var t, e, n = this.attr("MatrixCells"),
                a = this.attr("MatrixRows"),
                i = this.attr("MatrixColumns"),
                r = a.length || 1,
                o = n.length || 1,
                s = i.length || 1,
                l = [0, 1],
                c = [1, 0],
                p = [0, 0];
            for (1 === o ? (l = [0, r], c = [s, 0]) : 0 < o && (this.attr("RowCount") > this.attr("ColumnCount") ? (n.vert = !0, l = [0, r + 1], c = [s, 0], p[1] = 1) : (n.horz = !0, l = [0, r], c = [s + 1, 0], p[0] = 1)), this.prop("ShowTitle") && (this.attr("hasTitle", !0), l[0] += 1, c[0] += 1), e = this.rows[l[0]].cells[l[1]], t = 0; e && t < s;) i[t] && ((i[t].cell = e).expr = i[t]), h(e, null, "0101", 0), e = e.under(), t += 1;
            for (p[0] += e.getPosInColumn(), e = this.rows[c[0]].cells[c[1]], t = 0; e && t < r;) a[t] && ((a[t].cell = e).expr = a[t]), h(e, null, "1010", 1), e = e.next(), t += 1;
            for (p[1] += e.getPosInRow(), e = this.rows[p[0]].cells[p[1]], t = 0; e && t < o;) e = n.horz ? (h(e, null, "1010", 2), e.next()) : n.vert ? (h(e, null, "0101", 2), e.under()) : (h(e, null, "1111", 2), e.next()), t += 1
        },
        fillPropsNET: function(e) {
            var t, n = this.attr("MatrixCells"),
                a = this.attr("MatrixRows"),
                i = this.attr("MatrixColumns");
            d.default.fillPropsNET.apply(this, arguments), (t = function(t, n) {
                var a, i;
                e.find(t).each(function(t, e) {
                    a = {}, f.a.each(e.attributes, function(t, e) {
                        i = "false" !== e.value && ("true" === e.value || e.value), a[e.name] = i
                    }), n.push(a)
                })
            })("MatrixColumns >", i), t("MatrixRows >", a), t("MatrixCells >", n), this.bindEvents()
        },
        toXMLNET: function(c) {
            var p = this;
            return new Promise(function(l) {
                d.default.toXMLNET.call(p, c).then(function(t) {
                    var n, e, a, i, r, o, s;
                    return t = f()(t), n = void 0, e = f()(c.parentNode.ownerDocument.createElement("MatrixColumns")), a = p.attr("MatrixColumns"), f.a.each(a, function() {
                        n = f()(c.parentNode.ownerDocument.createElement("Header")), f.a.each(this, function(t, e) {
                            "object" !== (void 0 === e ? "undefined" : C(e)) && n.attr(t, e)
                        }), e.append(n)
                    }), i = f()(c.parentNode.ownerDocument.createElement("MatrixRows")), r = p.attr("MatrixRows"), f.a.each(r, function() {
                        n = f()(c.parentNode.ownerDocument.createElement("Header")), f.a.each(this, function(t, e) {
                            "object" !== (void 0 === e ? "undefined" : C(e)) && n.attr(t, e)
                        }), i.append(n)
                    }), o = f()(c.parentNode.ownerDocument.createElement("MatrixCells")), s = p.attr("MatrixCells"), f.a.each(s, function() {
                        n = f()(c.parentNode.ownerDocument.createElement("Cell")), f.a.each(this, function(t, e) {
                            "object" !== (void 0 === e ? "undefined" : C(e)) && n.attr(t, e)
                        }), o.append(n)
                    }), t.prepend(o), t.prepend(i), t.prepend(e), l(t[0])
                })
            })
        }
    })
}, function(t, e, n) {
    "use strict";
    var a, r, i, o, s, l;
    n.r(e), a = n(0), r = n.n(a), i = n(4), o = n(12), s = n(3), l = n(5), e.default = o.default.createObject(o.default, {
        title: "ButtonControl",
        info: "ButtonControl info",
        icon: "icon-115",
        pos: 10,
        type: "ButtonControl",
        disabled: !1,
        _init: function() {
            o.default._init.apply(this, arguments), this.defaultValues = {
                ForeColor: "#000",
                BackColor: "#F0F0F0",
                ImageAlign: "MiddleCenter",
                TextAlign: "MiddleCenter",
                TextImageRelation: "Overlay",
                RightToLeft: "No",
                AutoSize: !1,
                Enabled: !0,
                TabIndex: 4,
                TabStop: !0
            }, this.prop({
                Name: "Button",
                Width: 75,
                Height: 23,
                Text: "Button"
            }), this.attr({
                "text-anchor": "middle",
                "Font.Name": i.a.get("default-font-name"),
                "Font.Size": "8pt",
                "Font.Bold": !1,
                "Font.Italic": !1,
                "Font.Underline": !1,
                "Font.Strikeout": !1
            })
        },
        render: function() {
            var t = this.attr("Font.Size"),
                e = this.attr("Font.Name"),
                n = this.prop("Text"),
                a = this.prop("Width") / 2,
                i = this.prop("Height") / 2 + parseFloat(t, 10) / 3;
            return o.default.render.apply(this, arguments), this.$contentGroup.children().length || (this.$nestedG1 = r()(Object(s.a)("g")), this.$nestedG2 = r()(Object(s.a)("g")), this.$textNode = r()(Object(s.a)("text")), this.$nestedG2.append(this.$textNode), this.$nestedG1.append(this.$nestedG2), this.$contentGroup.append(this.$nestedG1)), n !== this.$textNode.text() && this.$textNode.text(n), this.$moveBlock.css({
                fill: this.prop("BackColor"),
                stroke: "silver"
            }), this.$textNode.css({
                "font-size": t + (r.a.isNumeric(t) ? "pt" : ""),
                fill: this.prop("ForeColor"),
                "font-family": e,
                "text-anchor": this.attr("text-anchor")
            }), this.applyFontStyles(this.$textNode), Object(l.a)(this.$nestedG1[0], "transform", "translate(" + a + ", " + i + ")"), this.$g
        }
    })
}, function(t, e, n) {
    "use strict";
    var a, u, i, h, f, g, m, r, o, b;
    n.r(e), a = n(0), u = n.n(a), i = n(4), h = n(12), f = n(68), g = n(3), m = n(5), r = n(75), o = n(76), b = n(67), e.default = h.default.createObject(h.default, {
        title: "CheckedListBoxControl",
        info: "CheckedListBoxControl info",
        icon: "icon-148",
        pos: 30,
        type: "CheckedListBoxControl",
        disabled: !1,
        _init: function() {
            h.default._init.apply(this, arguments), this.defaultValues = {
                ForeColor: "#000",
                BackColor: "#FFF",
                ColumnWidth: 0,
                Enabled: !0,
                MultiColumn: !1,
                SelectionMode: "One",
                Sorted: !1,
                TabIndex: 2,
                TabStop: !0,
                UseTabStops: !0,
                AutoFill: !0,
                AutoFilter: !0,
                CheckOnClick: !1
            }, this.attr({
                "Font.Name": i.a.get("default-font-name"),
                "Font.Size": "8pt",
                "Font.Bold": !1,
                "Font.Italic": !1,
                "Font.Underline": !1,
                "Font.Strikeout": !1
            }), this.prop({
                Name: "CheckedListBox",
                ItemsText: "",
                Width: 120,
                Height: 96
            })
        },
        render: function() {
            var t, e, n, a, i, r = this.attr("Font.Size"),
                o = this.attr("Font.Name"),
                s = this.prop("ItemsText"),
                l = this.prop("Width"),
                c = this.prop("Height"),
                p = parseFloat(r, 10) + 2,
                d = -9;
            if (h.default.render.apply(this, arguments), this.$contentGroup.children().length || (this.$nestedG1 = u()(Object(g.a)("g")), this.$nestedG2 = u()(Object(g.a)("g")), this.$textNode = u()(Object(g.a)("text")), this.$checkboxes = u()(Object(g.a)("g")), this.$nestedG2.append(this.$textNode, this.$checkboxes), this.$nestedG1.append(this.$nestedG2), this.$textNode[0].setAttributeNS("http://www.w3.org/XML/1998/namespace", "xml:space", "preserve"), this.$back = u()(Object(g.a)("rect", {
                    stroke: "gray",
                    x: 0,
                    y: 0
                })), this.$contentGroup.append(this.$back, this.$nestedG1)), this.$back.attr({
                    width: l - 2,
                    height: c - 2,
                    fill: this.prop("BackColor")
                }), this.$textNode.empty(), this.$checkboxes.empty(), i = Object(b.a)(s, {
                    "font-size": r,
                    "font-family": o,
                    "font-weight": r
                }), s = s.split(/\n\r|\n|\r/g), i.h += 2, n = 0, 1 < (a = s.length) || s[0])
                for (; n < a; n += 1)(t = u()(Object(g.a)("tspan"))).text(s[n] || " "), Object(m.a)(t[0], "x", 20), 0 < n && Object(m.a)(t[0], "dy", i.h), this.$textNode.append(t), e = f.default.generateCheckbox(), Object(m.a)(e[0], "transform", "translate(0, " + d + ")"), d += i.h, this.$checkboxes.append(e);
            return this.$moveBlock.css("fill", this.prop("BackColor")), this.$textNode.css({
                "font-size": r + (u.a.isNumeric(r) ? "pt" : ""),
                fill: this.prop("ForeColor"),
                "font-family": o
            }), this.applyFontStyles(this.$textNode), Object(m.a)(this.$back[0], "transform", "translate(0, 0)"), Object(m.a)(this.$nestedG1[0], "transform", "translate(0, " + p + ")"), this.$g
        },
        fillPropsNET: function() {
            return h.default.fillPropsNET.apply(this, arguments), this.prop("ItemsText", Object(r.a)(this.prop("ItemsText"))), this
        },
        toXMLNET: function(t) {
            var a = this;
            return new Promise(function(n) {
                h.default.toXMLNET.call(a, t).then(function(t) {
                    t = u()(t);
                    var e = Object(o.a)(a.prop("ItemsText").replace(/\n/g, "\r\n"));
                    return e && t.attr("ItemsText", e), n(t[0])
                })
            })
        }
    })
}, function(t, e, n) {
    "use strict";
    var a, o, i, s, l, c, r, p;
    n.r(e), a = n(0), o = n.n(a), i = n(4), s = n(12), l = n(3), c = n(5), r = n(75), p = n(76), e.default = s.default.createObject(s.default, {
        title: "ComboBoxControl",
        info: "ComboBoxControl info",
        icon: "icon-119",
        pos: 40,
        type: "ComboBoxControl",
        disabled: !1,
        _init: function() {
            s.default._init.apply(this, arguments), this.defaultValues = {
                ForeColor: "#000",
                BackColor: "#FFF",
                DrawMode: "Normal",
                DropDownHeight: 106,
                DropDownStyle: "DropDown",
                DropDownWidth: "121",
                ItemHeight: 13,
                MaxDropDownItems: 8,
                Sorted: !1,
                Enabled: !0,
                TabIndex: 2,
                TabStop: !0,
                AutoFill: !0,
                AutoFilter: !0
            }, this.attr({
                "Font.Name": i.a.get("default-font-name"),
                "Font.Size": "8pt",
                "Font.Bold": !1,
                "Font.Italic": !1,
                "Font.Underline": !1,
                "Font.Strikeout": !1,
                resizableY: !1,
                resizableXY: !1
            }), this.prop({
                Name: "ComboBox",
                ItemsText: "",
                Text: "ComboBox",
                Width: 121,
                Height: 21
            })
        },
        showResizingComponents: function() {
            return s.default.showResizingComponents.call(this, !1)
        },
        render: function() {
            var t = this.attr("Font.Size"),
                e = this.attr("Font.Name"),
                n = this.prop("Text"),
                a = this.prop("Width"),
                i = this.prop("Height"),
                r = parseFloat(t, 10);
            return s.default.render.apply(this, arguments), this.$contentGroup.children().length || (this.$nestedG1 = o()(Object(l.a)("g")), this.$nestedG2 = o()(Object(l.a)("g")), this.$textNode = o()(Object(l.a)("text")), this.$arrow = o()(Object(l.a)("text")), this.$nestedG2.append(this.$textNode), this.$nestedG1.append(this.$nestedG2), this.$textNode[0].setAttributeNS("http://www.w3.org/XML/1998/namespace", "xml:space", "preserve"), this.$arrow.text("▼"), this.$back = o()(Object(l.a)("rect", {
                stroke: "gray",
                x: 0,
                y: 0
            })), this.$contentGroup.append(this.$back, this.$nestedG1, this.$arrow)), this.$back.attr({
                width: a - 2,
                height: i - 2,
                fill: this.prop("BackColor")
            }), this.$textNode.text() !== n && this.$textNode.text(n), this.$moveBlock.css("fill", this.prop("BackColor")), this.$textNode.css({
                "font-size": t + (o.a.isNumeric(t) ? "pt" : ""),
                fill: this.prop("ForeColor"),
                "font-family": e
            }), this.applyFontStyles(this.$textNode), Object(c.a)(this.$back[0], "transform", "translate(0, 0)"), Object(c.a)(this.$nestedG1[0], "transform", "translate(2, " + (r + 2) + ")"), Object(c.a)(this.$arrow[0], "transform", "translate(" + (a - 15) + ", 12)"), this.$g
        },
        fillPropsNET: function() {
            return s.default.fillPropsNET.apply(this, arguments), this.prop("ItemsText", Object(r.a)(this.prop("ItemsText"))), this
        },
        toXMLNET: function(t) {
            var a = this;
            return new Promise(function(n) {
                s.default.toXMLNET.call(a, t).then(function(t) {
                    t = o()(t);
                    var e = Object(p.a)(a.prop("ItemsText").replace(/\n/g, "\r\n"));
                    return e && t.attr("ItemsText", e), n(t[0])
                })
            })
        }
    })
}, function(t, e, n) {
    "use strict";
    var a, i, r, o, s, l;
    n.r(e), a = n(0), i = n.n(a), r = n(4), o = n(12), s = n(3), l = n(5), e.default = o.default.createObject(o.default, {
        title: "LabelControl",
        info: "LabelControl info",
        icon: "icon-112",
        pos: 50,
        type: "LabelControl",
        disabled: !1,
        _init: function() {
            o.default._init.apply(this, arguments), this.defaultValues = {
                ForeColor: "#000",
                BackColor: "#F0F0F0",
                TextAlign: "TopLeft",
                Enabled: !0,
                AutoSize: !0,
                TabStop: !1,
                TabIndex: 2,
                RightToLeft: "No"
            }, this.prop({
                Name: "Label",
                Width: 32,
                Height: 13,
                Text: "Label"
            }), this.attr({
                "Font.Name": r.a.get("default-font-name"),
                "Font.Size": "8pt",
                "Font.Bold": !1,
                "Font.Italic": !1,
                "Font.Underline": !1,
                "Font.Strikeout": !1,
                resizableX: !1,
                resizableY: !1,
                resizableXY: !1
            })
        },
        resizingComponents: function() {},
        render: function() {
            var t = this.attr("Font.Size"),
                e = this.attr("Font.Name"),
                n = this.prop("Text"),
                a = this.prop("Height") / 2 + parseFloat(t, 10) / 3;
            return o.default.render.apply(this, arguments), this.$contentGroup.children().length || (this.$nestedG1 = i()(Object(s.a)("g")), this.$nestedG2 = i()(Object(s.a)("g")), this.$textNode = i()(Object(s.a)("text")), this.$nestedG2.append(this.$textNode), this.$nestedG1.append(this.$nestedG2), this.$contentGroup.append(this.$nestedG1)), n !== this.$textNode.text() && this.$textNode.text(n), this.$moveBlock.css("fill", this.prop("BackColor")), this.$textNode.css({
                "font-size": t + (i.a.isNumeric(t) ? "pt" : ""),
                fill: this.prop("ForeColor"),
                "font-family": e
            }), this.applyFontStyles(this.$textNode), Object(l.a)(this.$nestedG1[0], "transform", "translate(0, " + a + ")"), this.$g
        }
    })
}, function(t, e, n) {
    "use strict";
    var a, p, i, d, u, h, r, o, f;
    n.r(e), a = n(0), p = n.n(a), i = n(4), d = n(12), u = n(3), h = n(5), r = n(75), o = n(76), f = n(67), e.default = d.default.createObject(d.default, {
        title: "ListBoxControl",
        info: "ListBoxControl info",
        icon: "icon-118",
        pos: 60,
        type: "ListBoxControl",
        disabled: !1,
        _init: function() {
            d.default._init.apply(this, arguments), this.defaultValues = {
                ForeColor: "#000",
                BackColor: "#FFF",
                ColumnWidth: 0,
                DrawMode: "Normal",
                ItemHeight: 13,
                MultiColumn: !1,
                SelectionMode: "One",
                Sorted: !1,
                UseTabStops: !0,
                Enabled: !0,
                TabIndex: 2,
                TabStop: !0,
                AutoFill: !0,
                AutoFilter: !0
            }, this.attr({
                "Font.Name": i.a.get("default-font-name"),
                "Font.Size": "8pt",
                "Font.Bold": !1,
                "Font.Italic": !1,
                "Font.Underline": !1,
                "Font.Strikeout": !1
            }), this.prop({
                Name: "ListBox",
                ItemsText: "",
                Width: 120,
                Height: 96
            })
        },
        render: function() {
            var t, e, n, a, i = this.attr("Font.Size"),
                r = this.attr("Font.Name"),
                o = this.prop("ItemsText"),
                s = this.prop("Width"),
                l = this.prop("Height"),
                c = parseFloat(i, 10);
            for (d.default.render.apply(this, arguments), this.$contentGroup.children().length || (this.$nestedG1 = p()(Object(u.a)("g")), this.$nestedG2 = p()(Object(u.a)("g")), this.$textNode = p()(Object(u.a)("text")), this.$nestedG2.append(this.$textNode), this.$nestedG1.append(this.$nestedG2), this.$textNode[0].setAttributeNS("http://www.w3.org/XML/1998/namespace", "xml:space", "preserve"), this.$back = p()(Object(u.a)("rect", {
                    stroke: "gray",
                    x: 0,
                    y: 0
                })), this.$contentGroup.append(this.$back, this.$nestedG1)), this.$back.attr({
                    width: s - 2,
                    height: l - 2,
                    fill: this.prop("BackColor")
                }), this.$textNode.empty(), a = Object(f.a)(o, {
                    "font-size": i,
                    "font-family": r,
                    "font-weight": i
                }), e = 0, n = (o = o.split(/\n\r|\n|\r/g)).length; e < n; e += 1)(t = p()(Object(u.a)("tspan"))).text(o[e] || " "), 0 < e && Object(h.a)(t[0], {
                x: 0,
                dy: a.h
            }), this.$textNode.append(t);
            return this.$moveBlock.css("fill", this.prop("BackColor")), this.$textNode.css({
                "font-size": i + (p.a.isNumeric(i) ? "pt" : ""),
                fill: this.prop("ForeColor"),
                "font-family": r
            }), this.applyFontStyles(this.$textNode), Object(h.a)(this.$back[0], "transform", "translate(0, 0)"), Object(h.a)(this.$nestedG1[0], "transform", "translate(0, " + c + ")"), this.$g
        },
        fillPropsNET: function() {
            return d.default.fillPropsNET.apply(this, arguments), this.prop("ItemsText", Object(r.a)(this.prop("ItemsText"))), this
        },
        toXMLNET: function(t) {
            var a = this;
            return new Promise(function(n) {
                d.default.toXMLNET.call(a, t).then(function(t) {
                    t = p()(t);
                    var e = Object(o.a)(a.prop("ItemsText").replace(/\n/g, "\r\n"));
                    return e && t.attr("ItemsText", e), n(t[0])
                })
            })
        }
    })
}, function(t, e, n) {
    "use strict";
    var a, o, i, s, l, r, c;
    n.r(e), a = n(0), o = n.n(a), i = n(4), s = n(12), l = n(63), r = n(3), c = n(5), e.default = s.default.createObject(s.default, {
        title: "MonthCalendarControl",
        info: "MonthCalendarControl info",
        icon: "icon-145",
        pos: 65,
        type: "MonthCalendarControl",
        disabled: !1,
        _init: function() {
            s.default._init.apply(this, arguments), this.defaultValues = {
                BackColor: "#FFF",
                ForeColor: "#000",
                ShowToday: !0,
                ShowTodayCircle: !0,
                ShowWeekNumbers: !1,
                RightToLeft: "No",
                FirstDayOfWeek: "Default",
                Enabled: !0,
                TabIndex: 3,
                TabStop: !0,
                Visible: !0,
                AutoFill: !0,
                AutoFilter: !0,
                FilterOperation: "Equal",
                CalendarDimensions: "1; 1",
                MaxSelectionCount: 7
            }, this.attr({
                "Font.Name": i.a.get("default-font-name"),
                "Font.Size": "8pt",
                "Font.Bold": !1,
                "Font.Italic": !1,
                "Font.Underline": !1,
                "Font.Strikeout": !1,
                resizableX: !1,
                resizableY: !1,
                resizableXY: !1
            }), this.prop({
                Name: "MonthCalendar",
                Text: "MonthCalendar",
                MaxDate: new Date("12.31.9998"),
                MinDate: new Date("01.01.1753"),
                TodayDate: new Date,
                Width: 164,
                Height: 162
            })
        },
        resizingComponents: function() {},
        render: function() {
            var t = this.prop("Width"),
                e = this.prop("Height");
            return s.default.render.apply(this, arguments), this.$contentGroup.children().length || (this.$back = o()(Object(r.a)("rect", {
                stroke: "gray",
                x: 0,
                y: 0
            })), this.$contentGroup.append(this.$back)), this.$back.attr({
                width: t - 2,
                height: e - 2,
                fill: this.prop("BackColor")
            }), this.$moveBlock.css("fill", this.prop("BackColor")), Object(c.a)(this.$back[0], "transform", "translate(0, 0)"), this.$g
        },
        fillPropsNET: function() {
            var t, e, n;
            return s.default.fillPropsNET.apply(this, arguments), t = this.prop("TodayDate"), e = this.prop("MaxDate"), n = this.prop("MinDate"), t && this.prop("TodayDate", new Date(t)), e && this.prop("MaxDate", new Date(e)), n && this.prop("MinDate", new Date(n)), this
        },
        toXMLNET: function(t) {
            var r = this;
            return new Promise(function(i) {
                s.default.toXMLNET.call(r, t).then(function(t) {
                    var e, n, a;
                    return t = o()(t), (e = r.prop("TodayDate")) && t.attr("TodayDate", l.default.format(e, "-")), (n = r.prop("MinDate")) && t.attr("MinDate", l.default.format(n, "-")), (a = r.prop("MaxDate")) && t.attr("MaxDate", l.default.format(a, "-")), i(t[0])
                })
            })
        }
    })
}, function(t, e, n) {
    "use strict";
    var a, s, i, l, c, p;
    n.r(e), a = n(0), s = n.n(a), i = n(4), l = n(12), c = n(3), p = n(5), e.default = l.default.createObject(l.default, {
        title: "RadioButtonControl",
        info: "RadioButtonControl info",
        icon: "icon-117",
        pos: 70,
        type: "RadioButtonControl",
        disabled: !1,
        _init: function() {
            l.default._init.apply(this, arguments), this.defaultValues = {
                ForeColor: "#000",
                BackColor: "#F0F0F0",
                CheckAlign: "MiddleLeft",
                Checked: !1,
                ImageAlign: "MiddleCenter",
                TextAlign: "MiddleLeft",
                TextImageRelation: "Overlay",
                Enabled: !0,
                AutoSize: !0,
                TabIndex: 2,
                TabStop: !1,
                RightToLeft: "No",
                AutoFilter: !0,
                FilterOpeartion: "Equal",
                DetailControl: "None"
            }, this.prop({
                Name: "RadioButton",
                Width: 84,
                Height: 17,
                Text: "RadioButton"
            }), this.attr({
                radioCX: 15,
                radioCY: 7,
                r: 7,
                "Font.Name": i.a.get("default-font-name"),
                "Font.Size": "8pt",
                "Font.Bold": !1,
                "Font.Italic": !1,
                "Font.Underline": !1,
                "Font.Strikeout": !1,
                resizableX: !1,
                resizableY: !1,
                resizableXY: !1
            })
        },
        resizingComponents: function() {},
        render: function() {
            var t = this.attr("Font.Size"),
                e = this.attr("Font.Name"),
                n = this.attr("radioCX"),
                a = this.attr("radioCY"),
                i = this.attr("r"),
                r = this.prop("Text"),
                o = this.prop("Height") / 2 + parseFloat(t, 10) / 3;
            return l.default.render.apply(this, arguments), this.$contentGroup.children().length || (this.$nestedG1 = s()(Object(c.a)("g")), this.$nestedG2 = s()(Object(c.a)("g")), this.$textNode = s()(Object(c.a)("text")), this.$nestedG2.append(this.$textNode), this.$nestedG1.append(this.$nestedG2), this.$radio = s()(Object(c.a)("g")), this.$radioCirc = s()(Object(c.a)("circle", {
                cx: n,
                cy: a,
                r: i,
                fill: "#fff",
                stroke: "gray"
            })), this.$radioDot = s()(Object(c.a)("text")), this.$radio.append(this.$radioCirc, this.$radioDot), this.$contentGroup.append(this.$radio, this.$nestedG1)), r !== this.$textNode.text() && this.$textNode.text(r), this.prop("Checked") ? (this.$radioDot.text("●"), this.$radioDot.attr({
                x: 12,
                y: 10
            })) : this.$radioDot.text(""), this.$moveBlock.css("fill", this.prop("BackColor")), this.$textNode.css({
                "font-size": t + (s.a.isNumeric(t) ? "pt" : ""),
                fill: this.prop("ForeColor"),
                "font-family": e
            }), this.applyFontStyles(this.$textNode), Object(p.a)(this.$radio[0], "transform", "translate(0, 0)"), Object(p.a)(this.$nestedG1[0], "transform", "translate(" + (0 + 1.7 * n) + ", " + o + ")"), this.$g
        }
    })
}, function(t, e, n) {
    "use strict";
    var a, s, i, l, c, p;
    n.r(e), a = n(0), s = n.n(a), i = n(4), l = n(12), c = n(3), p = n(5), e.default = l.default.createObject(l.default, {
        title: "TextBoxControl",
        info: "TextBoxControl info",
        icon: "icon-113",
        pos: 80,
        type: "TextBoxControl",
        disabled: !1,
        _init: function() {
            l.default._init.apply(this, arguments), this.defaultValues = {
                ForeColor: "#000",
                BackColor: "#fff",
                TextAlign: "Left",
                AcceptsReturn: !1,
                AcceptsTab: !1,
                CharacterCasing: "Normal",
                Enabled: !0,
                MaxLength: 32767,
                Multiline: !1,
                AutoSize: !0,
                TabStop: !0,
                RightToLeft: "No",
                AutoFill: !0,
                AutoFilter: !0,
                FilterOpeartion: "Equal",
                DetailControl: "None"
            }, this.attr({
                "text-anchor": "start",
                mulFHeight: 20,
                mulTHeight: 20,
                "Font.Name": i.a.get("default-font-name"),
                "Font.Size": "8pt",
                "Font.Bold": !1,
                "Font.Italic": !1,
                "Font.Underline": !1,
                "Font.Strikeout": !1,
                resizableXY: !1,
                resizableY: !1
            }), this.prop({
                Name: "TextBox",
                Text: "TextBox",
                Width: 100,
                Height: this.attr("mulFHeight")
            })
        },
        getHorzAlign: function() {
            var t, e = this.prop("TextAlign"),
                n = this.prop("Width");
            return "Center" === e ? (t = n / 2, this.attr("text-anchor", "middle")) : "Left" === e ? (t = 0, this.attr("text-anchor", "start")) : "Right" === e && (t = n, this.attr("text-anchor", "end")), parseInt(t, 10)
        },
        showResizingComponents: function() {
            return l.default.showResizingComponents.call(this, !1)
        },
        render: function() {
            var t = this.attr("Font.Size"),
                e = this.attr("Font.Name"),
                n = this.prop("Text"),
                a = this.prop("Width"),
                i = this.prop("Height"),
                r = this.getHorzAlign(),
                o = parseFloat(t, 10);
            return l.default.render.apply(this, arguments), this.$contentGroup.children().length || (this.$nestedG1 = s()(Object(c.a)("g")), this.$nestedG2 = s()(Object(c.a)("g")), this.$textNode = s()(Object(c.a)("text")), this.$nestedG2.append(this.$textNode), this.$nestedG1.append(this.$nestedG2), this.$back = s()(Object(c.a)("rect", {
                stroke: "gray",
                x: 0,
                y: 0
            })), this.$contentGroup.append(this.$back, this.$nestedG1)), this.prop("Multiline") && this.attr("mulTHeight", i), this.$back.attr({
                width: a - 2,
                height: i - 2,
                fill: this.prop("BackColor")
            }), n !== this.$textNode.text() && this.$textNode.text(n), this.$moveBlock.css("fill", this.prop("BackColor")), this.$textNode.css({
                "font-size": t + (s.a.isNumeric(t) ? "pt" : ""),
                fill: this.prop("ForeColor"),
                "font-family": e,
                "text-anchor": this.attr("text-anchor")
            }), this.applyFontStyles(this.$textNode), Object(p.a)(this.$back[0], "transform", "translate(0, 0)"), Object(p.a)(this.$nestedG1[0], "transform", "translate(" + r + ", " + (o + 2) + ")"), this.$g
        }
    })
}, function(t, e, n) {
    "use strict";
    var a = n(0),
        i = n.n(a),
        r = n(3);
    e.a = function(t) {
        return (t = t || {}).class = "illusion fr-hidden", i()(Object(r.a)("line", t))
    }
}, function(t, e, n) {
    "use strict";

    function a(t, e) {
        return Math.atan2(e, t) * (180 / Math.PI)
    }
    var i, p, l, r, d, u, c;
    n.r(e), i = n(0), p = n.n(i), l = n(4), r = n(9), d = n(3), u = n(5), c = n(53), e.default = r.default.createObject(r.default, {
        title: "Objects Shapes Line",
        info: "LineObjectInfo",
        icon: "icon-105",
        pos: 40,
        type: "LineObject",
        disabled: !1,
        tips: {
            Normal: {
                label: "Normal",
                render: function() {
                    return null
                }
            },
            Arrow: {
                label: "Arrow",
                render: function(t, e, n, a) {
                    n = 2 * (n || 10);
                    var i = p()(Object(d.a)("g", {
                            transform: "translate(" + t + "," + e + ")"
                        })),
                        r = p()(Object(d.a)("g")),
                        o = p()(Object(d.a)("line")),
                        s = p()(Object(d.a)("line")),
                        l = {
                            x1: 0,
                            y1: 0,
                            x2: n,
                            y2: 0
                        };
                    return o.attr(l), l.x2 = 0, l.y2 = n, s.attr(l), o.css({
                        "stroke-width": this.prop("Border.Width"),
                        stroke: this.prop("Border.Color")
                    }), s.css({
                        "stroke-width": this.prop("Border.Width"),
                        stroke: this.prop("Border.Color")
                    }), r.append(o), r.append(s), Object(u.a)(r[0], "transform", "rotate(" + a + ")"), i.append(r), i
                }
            },
            Circle: {
                label: "Circle",
                render: function(t, e, n, a) {
                    var i = p()(Object(d.a)("g", {
                            transform: "translate(" + t + "," + e + ")"
                        })),
                        r = p()(Object(d.a)("g")),
                        o = p()(Object(d.a)("ellipse", {
                            cx: n / 2,
                            cy: n / 2,
                            rx: n,
                            ry: n
                        }));
                    return o.css({
                        fill: this.attr("fill_tip"),
                        stroke: this.prop("Border.Color"),
                        "stroke-width": this.prop("Border.Width")
                    }), r.append(o), Object(u.a)(r[0], "transform", "rotate(" + a + ")"), i.append(r), i
                }
            },
            Square: {
                label: "Square",
                render: function(t, e, n, a) {
                    n = 3 * (n || 10);
                    var i = p()(Object(d.a)("g", {
                            transform: "translate(" + t + "," + e + ")"
                        })),
                        r = p()(Object(d.a)("g")),
                        o = n / 2 - 18,
                        s = n / 2 - 18,
                        l = n - 16,
                        c = p()(Object(d.a)("polygon", {
                            points: o + ", -20 -20, " + s + " " + o + ", " + l + " " + l + ", " + s
                        }));
                    return c.css({
                        fill: this.attr("fill_tip"),
                        stroke: this.prop("Border.Color"),
                        "stroke-width": this.prop("Border.Width")
                    }), r.append(c), Object(u.a)(r[0], "transform", "rotate(" + a + ")"), i.append(r), i
                }
            },
            Diamond: {
                label: "Diamond",
                render: function(t, e, n, a) {
                    var i = p()(Object(d.a)("g", {
                            transform: "translate(" + t + "," + e + ")"
                        })),
                        r = p()(Object(d.a)("g")),
                        o = p()(Object(d.a)("polygon", {
                            points: "25,10 0,0 10,25 32,32"
                        }));
                    return o.css({
                        fill: this.attr("fill_tip"),
                        stroke: this.prop("Border.Color"),
                        "stroke-width": this.prop("Border.Width")
                    }), r.append(o), Object(u.a)(r[0], "transform", "rotate(" + a + ")"), i.append(r), i
                }
            }
        },
        _init: function() {
            r.default._init.apply(this, arguments), this.defaultValues = {
                "EndCap.Style": "None",
                "EndCap.Height": 8,
                "EndCap.Width": 8,
                "StartCap.Style": "None",
                "StartCap.Height": 8,
                "StartCap.Width": 8,
                Diagonal: !1,
                "Border.Width": 1,
                "Border.Color": "#000000"
            }, this.prop({
                Name: "Line",
                Width: 0,
                Height: 0
            }), this.attr({
                dasharray: "",
                fill_tip: "#ffffff",
                defaultValueWidth: 100
            })
        },
        resizingComponents: function() {
            var t, e, n = l.a.get("circleButtonWidth"),
                a = l.a.get("circleButtonHeight"),
                i = this.prop("Width") - n,
                r = this.prop("Height") - a,
                o = i,
                s = r;
            return this.$resizing || (t = Object(d.a)("use"), e = p()(Object(d.a)("rect", {
                class: "se-resize"
            })), this.$resizing = p()(), this.$resizing.push(null, null, p()(Object(d.a)("g", {
                class: "resizing-line fr-hidden"
            }))), Object(c.a)(t, "xlink:href", "#d-button-circle"), e.css("fill", "transparent"), e.attr({
                width: 2 * n,
                height: 2 * a
            }), this.$resizing[2].append(t, e), this.$controlElements.append(this.$resizing[2])), this.prop("Diagonal") || (r < i ? s = 0 : o = 0), Object(u.a)(this.$resizing[2][0], "transform", "translate(" + o + "," + s + ")"), this
        },
        addStartCap: function(t) {
            var e;
            this.prop("StartCap.Style") && this.tips[this.prop("StartCap.Style")] && (e = this.tips[this.prop("StartCap.Style")].render.call(this, t.x1, t.y1, this.prop("StartCap.Height"), a(t.x2 - t.x1, t.y2 - t.y1) - 45)) && this.$lineContainer.append(e)
        },
        addEndCap: function(t) {
            var e;
            this.prop("EndCap.Style") && this.tips[this.prop("EndCap.Style")] && (e = this.tips[this.prop("EndCap.Style")].render.call(this, t.x2, t.y2, this.prop("EndCap.Height"), a(t.x2 - t.x1, t.y2 - t.y1) + 135)) && this.$lineContainer.append(e)
        },
        setPosition: function() {
            return r.default.setPosition.apply(this, arguments), this.attr("isHorizontal") ? (this.attr("bottom", this.prop("Top") + this.prop("Border.Width")), this.attr("right", this.prop("Left") + this.prop("Width"))) : this.attr("isVertical") && (this.attr("bottom", this.prop("Top") + this.prop("Height")), this.attr("right", this.prop("Left") + this.prop("Border.Width"))), this
        },
        render: function() {
            return 0 === this.prop("Width") && 0 === this.prop("Height") && this.prop("Width", this.attr("defaultValueWidth")), r.default.renderContainer.apply(this, arguments), this.$content = p()(Object(d.a)("g", {
                class: "move"
            })), this.$body.html(this.$content), this.$moveBlock = p()(Object(d.a)("line")), this.$content.prepend(this.$moveBlock), this.$lineContainer = p()(Object(d.a)("g")), this.$content.prepend(this.$lineContainer), Object(u.a)(this.$lineContainer[0], {
                transform: "translate(0, 0)"
            }), this.render = function() {
                var t = this.prop("Width"),
                    e = this.prop("Height"),
                    n = {};
                return n.style = l.a.get("dasharrays")[this.prop("Border.Style")], this.$lineContainer.empty(), this.$workspace = p()(Object(d.a)("rect")), this.$line = this.$line || p()(Object(d.a)("line")), this.$moveBlock.css({
                    "stroke-width": +this.prop("Border.Width") + l.a.get("lineMovingScope"),
                    stroke: "transparent"
                }), this.$line.css({
                    "stroke-dasharray": n.style,
                    "stroke-width": this.prop("Border.Width"),
                    stroke: this.prop("Border.Color")
                }), this.$lineContainer.append(this.$workspace, this.$line), this.attr("bottom", this.prop("Top")), this.prop("Diagonal") ? (n = {
                    x1: 0,
                    y1: 0,
                    x2: t,
                    y2: e
                }, 0 < e && this.attr("bottom", this.attr("bottom") + e), this.attr("isVertical", !1), this.attr("isHorizontal", !1)) : e < t ? (n = {
                    x1: 0,
                    y1: 0,
                    x2: t,
                    y2: 0
                }, this.attr("isHorizontal", !0), this.attr("isVertical", !1)) : (n = {
                    x1: 0,
                    y1: 0,
                    x2: 0,
                    y2: e
                }, this.attr("isVertical", !0), this.attr("isHorizontal", !1)), Object(u.a)(this.$line[0], n), Object(u.a)(this.$moveBlock[0], n), this.addEndCap(n), this.addStartCap(n), this.resizingComponents(), this.setPosition(), this.$g
            }, this.render()
        },
        resizingEnd: function() {
            r.default.resizingEnd.apply(this, arguments), this.prop("Diagonal") || this.prop(this.attr("isHorizontal") ? "Height" : "Width", 0)
        },
        toXMLNET: function(t) {
            var n = this;
            return new Promise(function(e) {
                r.default.toXMLNET.call(n, t).then(function(t) {
                    return t = p()(t), n.prop("Border.Color") && t.attr("Border.Color", n.prop("Border.Color")), n.prop("Border.Width") && t.attr("Border.Width", n.prop("Border.Width")), n.prop("Border.Style") && t.attr("Border.Style", n.prop("Border.Style")), e(t[0])
                })
            })
        }
    })
}, function(t, e, n) {
    "use strict";
    var a = n(0),
        i = n.n(a),
        r = n(13);
    e.a = r.a.createObject(null, {
        icon: "icon-215",
        type: "TableColumn",
        _init: function() {
            this.prop("Name", "Column"), this.prop({
                Name: this.formName(),
                MaxWidth: 500,
                MinWidth: 0,
                Width: 66
            })
        },
        create: function(t) {
            var e = this.createObject(this);
            return e.parent = t, e._init(), e
        },
        _getInx: function() {
            return this.getTable().columns.indexOf(this)
        },
        getTable: function() {
            return this.parent.matrix || this.parent
        },
        activate: function() {
            var n = this._getInx();
            return this.getTable()._eachRow(function(t, e) {
                return e.cells[n].activate()
            }), r.a.activate.call(this), this
        },
        deactivate: function() {
            var n = this._getInx();
            return this.getTable()._eachRow(function(t, e) {
                return e.cells[n].deactivate()
            }), this
        },
        toXMLNET: function(e) {
            var a = this;
            return new Promise(function(t) {
                var n = i()(e.parentNode.ownerDocument.createElement(a.type));
                return a.eachProp(function(t, e) {
                    return n.attr(t, e)
                }), t(n[0])
            })
        }
    })
}, function(t, e, n) {
    "use strict";
    var a = n(0),
        i = n.n(a),
        r = n(13);
    e.a = r.a.createObject(r.a, {
        icon: "icon-216",
        type: "TableRow",
        _init: function() {
            this.cells = [], this.prop("Name", "Row"), this.prop({
                Name: this.formName(),
                MaxHeight: 500,
                MinHeight: 0,
                Height: 18
            })
        },
        create: function(t) {
            var e = this.createObject(this);
            return e.parent = t, e._init(), e
        },
        getTable: function() {
            return this.parent.matrix || this.parent
        },
        _getInx: function() {
            return this.getTable().rows.indexOf(this)
        },
        activate: function() {
            var e = this._getInx();
            return this.getTable()._eachColumn(function(t) {
                this.rows[e].cells[t].activate()
            }), r.a.activate.call(this), this
        },
        deactivate: function() {
            var e = this._getInx();
            return this.getTable()._eachColumn(function(t) {
                this.rows[e].cells[t].deactivate()
            }), this
        },
        toXMLNET: function(e) {
            var a = this;
            return new Promise(function(t) {
                var n = i()(e.parentNode.ownerDocument.createElement(a.type));
                return a.eachProp(function(t, e) {
                    return n.attr(t, e)
                }), t(n[0])
            })
        }
    })
}, function(t, e, n) {
    "use strict";
    e.a = function(t) {
        return t.replace(/&amp;/g, "&").replace(/&lt;/g, "<").replace(/&gt;/g, ">").replace(/&quot;/g, '"').replace(/&#039;/g, "'")
    }
}, function(t, e, n) {
    "use strict";
    e.a = function(t) {
        return t.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;")
    }
}, , , , , , , function(t, e, n) {
    "use strict";
    n(368);
    var l, a = n(0),
        c = n.n(a),
        p = c()(".fr-preload-blanket"),
        d = +p.css("z-index") || 20;
    e.a = {
        show: function(t, e, n, a, i) {
            var r = c.a.Deferred(),
                o = n ? p.clone() : p,
                s = o.find(".sp-h");
            return d += 1, o.css("z-index", i || d), (l = l || window.DSG.head).$node.css("cursor", "wait"), t = t || s.text(), s.text(t), e && o.css("background-color", "rgba(255,255,255,.5)"), o.fadeIn(void 0 !== a ? a : 300, function() {
                r.resolve(o)
            }), l.$main.append(o), r.promise()
        },
        show1: function() {
            this.show(" ", !0, null, null, 1e3)
        },
        hide: function(t) {
            var e = c.a.Deferred();
            return t = t || p, (l = l || window.DSG.head).$node.css("cursor", ""), t.fadeOut(300, function() {
                e.resolve()
            }), e.promise()
        }
    }
}, function(t, e, n) {
    "use strict";

    function a(t) {
        var e = (+t).toString(16);
        return 1 === e.length ? "0" + e : e
    }
    e.a = function(t, e, n) {
        return "#" + a(t) + a(e) + a(n)
    }
}, function(t, e, n) {
    "use strict";

    function a() {}
    var r = n(13),
        i = n(28);
    a.prototype.add = function(t) {
        var a = this,
            i = this._callbacks || (this._callbacks = []);
        return i.push(t), t.active = function() {
            var t, e, n;
            for (e = 0, n = i.length; e < n; e += 1) this === i[e] ? t = this : i[e].deactivate();
            return t && (t.activate(), r.a.activate.apply(t, arguments), a.current = t), this
        }, t
    }, a.prototype.remove = function(t) {
        Object(i.a)(this._callbacks, t)
    }, a.prototype.clear = function() {
        return this._callbacks ? this._callbacks.length = 0 : this._callbacks = [], this
    }, a.prototype.all = function() {
        return this._callbacks || []
    }, e.a = a
}, function(t, e, n) {
    "use strict";
    var a, i, r;
    n.d(e, "a", function() {
        return r
    }), a = n(120), i = n.n(a), r = {
        init: function() {
            i.a.config({
                driver: [i.a.INDEXEDDB, i.a.WEBSQL, i.a.LOCALSTORAGE],
                name: "frdb",
                storeName: "fr_store",
                version: 3
            })
        },
        getItem: function(t) {
            return i.a.getItem(t)
        },
        setItem: function(t, e) {
            return i.a.setItem(t, e)
        }
    }
}, function(t, e, o) {
    "use strict";

    function n(e) {
        o.e(16).then(o.bind(null, 576)).then(function(t) {
            return t.create(e)
        })
    }
    var a, i, s, l;
    o.r(e), a = o(10), i = o(2), s = o(1), l = o(15), e.default = a.default.createObject(a.default, {
        title: "Bands GroupHeader",
        info: "GroupHeaderBandInfo",
        icon: "icon-163",
        pos: 100,
        type: "GroupHeaderBand",
        disabled: !1,
        _init: function() {
            a.default._init.call(this), this.defaultValues = {
                KeepTogether: !1,
                KeepWithData: !1
            }, this.prop("Name", "GroupHeader"), this.attr("pos", 35)
        },
        getFillTitleColor: function() {
            return "#01DF3A"
        },
        canHaveChildren: function(t) {
            var e = t.type || t;
            return a.default.canHaveChildren.apply(this, arguments) || ["DataBand", "DataHeaderBand", "DataFooterBand", "GroupHeaderBand", "GroupFooterBand"].includes(e)
        },
        canBeAdded: function() {
            return !0
        },
        canBeSorted: function() {
            return !0
        },
        applyRule: function() {
            var t, e = ["GroupHeaderBand", "DataBand"],
                n = this._parent,
                a = void 0,
                i = void 0,
                r = void 0;
            return "DataBand" === n.type ? n = (a = n)._parent : (n.isPage() && (e = ["DataBand"]), (a = n.bands.all(e)[0]) ? r = a.collection.entities.indexOf(a) : (a = (a = o.c[83]) && (a = a.exports.default).create(), i = !0)), t = (t = o.c[177]) && (t = t.exports.default).create(), n.addBand(this, r), a && (this.addBand(a), l.a.push({
                context: s.a,
                undo: function(t, e, n) {
                    this.trigger("remove-band", n), i || (t.addBand(e), this.trigger("render-band", e)), t.updateExts()
                },
                redo: function(t, e, n) {
                    this.trigger("remove-band", e), n.addBand(e), t.addBand(n), this.trigger("render-band", n), this.trigger("render-band", e), t.updateExts()
                },
                data: [n, a, this]
            })), t && this.addBand(t), !0
        },
        dblclick: function() {
            return n(this)
        },
        getContextMenuItems: function() {
            function t(t) {
                return e.onChangeCM(t)
            }
            var e = this;
            return [{
                name: i.a.tr("ComponentMenu Edit"),
                type: "default",
                closeAfter: !0,
                onClick: function() {
                    return s.a.trigger("activate", e), n(e)
                }
            }, {
                name: i.a.tr("Band AddChildBand"),
                type: "default",
                closeAfter: !0,
                disabled: !!e.has("ChildBand"),
                onClick: function() {
                    s.a.trigger("add-band", "ChildBand", e)
                }
            }, {
                name: i.a.tr("ComponentMenu Hyperlink"),
                type: "default",
                closeAfter: !0,
                onClick: function() {
                    s.a.trigger("hyperlink-editor", e)
                }
            }, {
                type: "separator"
            }, {
                name: i.a.tr("ComponentMenu CanGrow"),
                type: "checkbox",
                curVal: e.prop("CanGrow"),
                prop: "CanGrow",
                onChange: t
            }, {
                name: i.a.tr("ComponentMenu CanShrink"),
                type: "checkbox",
                curVal: e.prop("CanShrink"),
                prop: "CanShrink",
                onChange: t
            }, {
                name: i.a.tr("ComponentMenu CanBreak"),
                type: "checkbox",
                curVal: e.prop("CanBreak"),
                prop: "CanBreak",
                onChange: t
            }, {
                type: "separator"
            }, {
                name: i.a.tr("ComponentMenu KeepWithData"),
                type: "checkbox",
                curVal: e.prop("KeepWithData"),
                prop: "KeepWithData",
                onChange: t
            }, {
                name: i.a.tr("ComponentMenu RepeatOnEveryPage"),
                type: "checkbox",
                curVal: e.prop("RepeatOnEveryPage"),
                prop: "RepeatOnEveryPage",
                onChange: t
            }, {
                name: i.a.tr("ComponentMenu KeepTogether"),
                type: "checkbox",
                curVal: e.prop("KeepTogether"),
                prop: "KeepTogether",
                onChange: t
            }, {
                name: i.a.tr("ComponentMenu ResetPageNumber"),
                type: "checkbox",
                curVal: e.prop("ResetPageNumber"),
                prop: "ResetPageNumber",
                onChange: t
            }, {
                name: i.a.tr("Band StartNewPage"),
                type: "checkbox",
                curVal: e.prop("StartNewPage"),
                prop: "StartNewPage",
                onChange: t
            }, {
                name: i.a.tr("ComponentMenu PrintOnBottom"),
                type: "checkbox",
                curVal: e.prop("PrintOnBottom"),
                prop: "PrintOnBottom",
                onChange: t
            }, {
                type: "separator"
            }, {
                name: i.a.tr("Menu Edit Paste"),
                type: "default",
                disabled: !window.DSG.currentReport.getCurrentPage().buffer.length,
                closeAfter: !0,
                shortcut: "Ctrl + V",
                onClick: function() {
                    setTimeout(function() {
                        s.a.trigger("paste")
                    }, 100)
                }
            }, {
                name: i.a.tr("Menu Edit Delete"),
                type: "default",
                disabled: !e.canBeRemoved(),
                closeAfter: !0,
                shortcut: "Del",
                onClick: function() {
                    s.a.trigger("remove", e)
                }
            }]
        },
        toString: function() {
            return this.prop("Condition") ? this.prop("Name") + ": " + this.prop("Condition") : this.prop("Name")
        }
    })
}, function(t, e, n) {
    "use strict";
    n.r(e);
    var a = n(10);
    e.default = a.default.createObject(a.default, {
        title: "Bands Child",
        info: "ChildBandInfo",
        icon: "icon-165",
        pos: 120,
        type: "ChildBand",
        disabled: !1,
        _init: function() {
            a.default._init.call(this), this.defaultValues = {
                CompleteToNRows: 0,
                FillUnusedSpace: !1
            }, this.prop("Name", "Child"), this.attr("pos", 32)
        },
        getFillTitleColor: function() {
            return this._parent && this._parent.getFillTitleColor()
        }
    })
}, function(t, e, n) {
    "use strict";
    var a, i, r, o, s, l;
    n.r(e), a = n(0), i = n.n(a), r = n(4), o = n(9), s = n(3), l = n(5), e.default = o.default.createObject(o.default, {
        title: "Objects Shapes",
        info: "ShapeObjectInfo",
        icon: "icon-108",
        pos: 30,
        type: "ShapeObject",
        disabled: !1,
        figures: {
            Rectangle: {
                label: "rectangle",
                render: function(t, e) {
                    return i()(Object(s.a)("rect", {
                        width: t,
                        height: e
                    }))
                }
            },
            RoundRectangle: {
                label: "round rectangle",
                render: function(t, e) {
                    return i()(Object(s.a)("rect", {
                        width: t,
                        height: e,
                        rx: 10,
                        ry: 10
                    }))
                }
            },
            Ellipse: {
                label: "ellipse",
                render: function(t, e) {
                    var n = t / 2,
                        a = e / 2;
                    return i()(Object(s.a)("ellipse", {
                        cx: n,
                        cy: a,
                        rx: n,
                        ry: a
                    }))
                }
            },
            Triangle: {
                label: "triangle",
                render: function(t, e) {
                    return i()(Object(s.a)("polygon", {
                        points: t / 2 + ", 0 0, " + e + " " + t + ", " + e
                    }))
                }
            },
            Diamond: {
                label: "diamond",
                render: function(t, e) {
                    var n = t / 2,
                        a = e / 2;
                    return i()(Object(s.a)("polygon", {
                        points: n + ",0 0," + a + " " + n + "," + e + " " + t + "," + a
                    }))
                }
            }
        },
        _init: function() {
            o.default._init.apply(this, arguments), this.defaultValues = {
                Curve: 0
            }, this.prop({
                Name: "Shape",
                Width: 94.5,
                Height: 18.9,
                Shape: "Rectangle",
                "Fill.Color": "transparent",
                "Border.Lines": "All"
            })
        },
        appendBorders: function() {},
        appendPadding: function() {},
        canHaveProp: function(t) {
            return "Appearance:Border" !== t && o.default.canHaveProp.apply(this, arguments)
        },
        render: function() {
            var t, e = this.prop("Border.Width") || 0,
                n = this.prop("Width") - e,
                a = this.prop("Height") - e;
            return o.default.render.apply(this, arguments), this.$shape || (this.$shape = i()(Object(s.a)("g"))), this.$shape.empty(), (t = this.figures[this.prop("Shape")].render.call(this, n < 0 ? 0 : n, a < 0 ? 0 : a)).css({
                fill: this.prop("Fill.Color"),
                stroke: this.prop("Border.Color"),
                "stroke-width": e,
                "stroke-dasharray": r.a.get("dasharrays")[this.prop("Border.Style")]
            }), Object(l.a)(this.$shape[0], "transform", "translate(" + e / 2 + "," + e / 2 + ")"), this.$shape.append(t), this.$contentGroup.append(this.$shape), this.$moveBlock.css("fill", "transparent"), this.$g
        },
        toXMLNET: function(t) {
            var n = this;
            return new Promise(function(e) {
                o.default.toXMLNET.call(n, t).then(function(t) {
                    return (t = i()(t)).removeAttr("Border.Lines"), e(t[0])
                })
            })
        }
    })
}, function(t, e, n) {
    "use strict";
    var a, D, r, o, O, M, i;
    n.r(e), a = n(0), D = n.n(a), r = n(4), o = n(16), O = n(3), M = n(5), i = n(58), e.default = o.a.createObject(o.a, {
        title: "Objects Gauge Radial",
        info: "RadialGaugeInfo",
        icon: "icon-135",
        pos: 130,
        type: "RadialGauge",
        groupType: "Gauge",
        types: {
            Circle: {
                label: "circle",
                render: function(t, e) {
                    var n = t / 2,
                        a = e / 2,
                        i = D()(Object(O.a)("ellipse"));
                    return i.attr("cx", n), i.attr("cy", a), i.attr("rx", n), i.attr("ry", a), this.appendPointer(264, 15.6, 40, 223, 41, 8), this.createScale(11, 4, 300, 112, -113, 18.89), i
                }
            },
            Semicircle: {
                label: "semicircle",
                render: function(t, e, n) {
                    var a = t / 2,
                        i = e / 2,
                        r = D()(Object(O.a)("path"));
                    return r.attr("d", "\n                    M" + (a + t / 2) + " " + (i + e / 22) + "\n                    A" + a + " " + i + " 0 0 0 " + (a - t / 2) + " " + (i + e / 22) + "\n                    L" + (a - t / 2) + " " + (i + e / 22 * n) + "\n                    L" + (a + t / 2) + " " + (i + e / 22 * n) + " Z\n\n                "), this.appendPointer(180, 15.6, 40, 182, -2, 10), this.createScale(5, 3, 230, 135, -135, 28), r
                }
            },
            Quadrant: {
                label: "quadrant",
                render: function(t, e, n) {
                    var a = t / 2,
                        i = e / 2,
                        r = D()(Object(O.a)("path"));
                    return r.attr("d", "\n                    M" + (a + 8 * t / 100 / 2 * n) + " " + (i + 8 * e / 100 / 2 * n) + "\n                    L" + (t - t / 2 * 2) + " " + (i + 8 * e / 100 / 2 * n) + "\n                    L" + (a - t / 2) + " " + i + "\n                    A" + a + " " + i + " 0 0 1 " + a + " " + (e - e / 2 * 2) + "\n                    L" + (a + 8 * t / 100 / 2 * n) + " " + (e - e / 2 * 2) + " Z\n\n                "), this.appendPointer(89, 15.6, 40, 91, -2, 8), this.createScale(3, 3, 136, 136, -135, 23), r
                }
            }
        },
        disabled: !1,
        _init: function() {
            o.a._init.apply(this, arguments), this.defaultValues = {
                Maximum: 100,
                Minimum: 0,
                Value: 10,
                Inverted: !1,
                Type: "Circle",
                SemicircleOffset: 1,
                "Pointer.BorderColor": "#000",
                "Pointer.Color": "#FFA500",
                "Scale.MajorTicks.Color": "#000",
                "Scale.MajorTicks.Width": 2,
                "Scale.MinorTicks.Color": "#000",
                "Scale.MinorTicks.Width": 1
            }, this.prop({
                Name: "RadialGauge",
                Width: 200.36,
                Height: 200.36
            }), this.attr({
                resizableX: !1,
                resizableY: !1,
                "Scale.Font.Name": r.a.get("default-font-name"),
                "Scale.Font.Size": "9pt",
                "Scale.Font.Bold": !1,
                "Scale.Font.Italic": !1,
                "Scale.Font.Underline": !1,
                "Scale.Font.Strikeout": !1
            })
        },
        getRange: function() {
            return this.prop("Maximum") - this.prop("Minimum")
        },
        getPace: function(t) {
            return this.getRange() / (t - 1)
        },
        appendPointer: function(t, e, n, a, i, r) {
            var o = void 0,
                s = void 0,
                l = this.prop("Width"),
                c = this.prop("Height"),
                p = this.prop("Value"),
                d = this.getRange(),
                u = (p - this.prop("Minimum")) * (t / d),
                h = (this.prop("Width") - this.prop("Border.Width")) / 2,
                f = (this.prop("Height") - this.prop("Border.Width")) / 2;
            return this.$pointer || (this.$pointerContainer = D()(Object(O.a)("g")), this.$pointer = D()(Object(O.a)("path")), this.$pointerEllipse = D()(Object(O.a)("ellipse"))), s = this.prop("Pointer.Width", l * e / 100), o = this.prop("Pointer.Height", c * n / 100), this.$pointerEllipse.attr("cx", h), this.$pointerEllipse.attr("cy", f), this.$pointerEllipse.attr("rx", .2 * s), this.$pointerEllipse.attr("ry", .2 * s), Object(M.a)(this.$pointer[0], "d", "\n            M" + (h - s / 3) + " " + (f - o / 20) + "\n            L" + (s + r) + " " + f + "\n            L" + (s + r) + " " + (f + o / 24) + "\n            L" + (h - s / 3) + " " + (f + o / 20) + " Z\n        "), this.$pointer.css({
                stroke: this.prop("Pointer.BorderColor"),
                fill: this.prop("Pointer.Color")
            }), this.$pointerEllipse.css({
                stroke: this.prop("Pointer.BorderColor"),
                fill: this.prop("Pointer.Color")
            }), this.prop("Inverted") ? Object(M.a)(this.$pointer[0], "transform", "rotate(" + (a - u) + ", " + h + ", " + f + ")") : Object(M.a)(this.$pointer[0], "transform", "rotate(" + (u - i) + ", " + h + ", " + f + ")"), this.$pointerContainer.append(this.$pointerEllipse), this.$pointerContainer.append(this.$pointer), this.$gauge.append(this.$pointerContainer), this.$pointer
        },
        createScale: function(t, e, n, a, i, r) {
            var o, s = void 0,
                l = void 0,
                c = [],
                p = r,
                d = void 0,
                u = this.prop("Inverted") ? this.prop("Minimum") : this.prop("Maximum"),
                h = this.prop("Width"),
                f = this.prop("Height"),
                g = (h - this.prop("Border.Width")) / 2,
                m = (f - this.prop("Border.Width")) / 2,
                b = g - p,
                v = ~~(b / 4),
                y = ~~(b / 8),
                C = Math.PI / 180,
                S = b - v,
                x = b,
                w = S,
                T = b - y / 2,
                B = T - y,
                k = this.getPace(t),
                $ = f - 2 * r,
                P = $ / (t - 1);
            for (p += $, this.$pacesCont && !this.attr("isHorizontal") || (this.$pacesCont = D()(Object(O.a)("g")), c = []), this.$pacesCont.empty(), this.$pacesCont.attr("transform", "rotate(" + a + ", " + g + ", " + m + ")"), s = 0; s < t; s += 1)
                if (c[s] || (c[s] = {
                        $self: D()(Object(O.a)("g")),
                        $minorTicks: [],
                        $majorTick: D()(Object(O.a)("line")),
                        $paceText: D()(Object(O.a)("text"))
                    }, c[s].$self.append(c[s].$majorTick, c[s].$paceText), this.$pacesCont.append(c[s].$self)), c[s].$majorTick.css({
                        "stroke-width": this.prop("Scale.MajorTicks.Width"),
                        stroke: this.prop("Scale.MajorTicks.Color")
                    }), o = (n / t - 1) * (t - s), c[s].$majorTick.attr({
                        x1: g + x * Math.cos(o * C),
                        y1: m + x * Math.sin(o * C),
                        x2: g + w * Math.cos(o * C),
                        y2: m + w * Math.sin(o * C)
                    }), c[s].$paceText.text(s === (t - 1) / 2 ? u : Math.round(u)), c[s].$paceText.css({
                        "font-size": this.attr("Scale.Font.Size") + (D.a.isNumeric(this.attr("Scale.Font.Size")) ? "pt" : ""),
                        fill: "#000",
                        "font-family": this.attr("Scale.Font.Name"),
                        "text-anchor": "middle"
                    }), this.applyFontStyles(c[s].$paceText, "Scale."), p -= P, Object(M.a)(c[s].$paceText[0], "transform", "translate(" + (g + (g - .9 * r) * Math.cos(o * C)) + ", " + (m + (g - .9 * r) * Math.sin(o * C)) + "),\n                rotate(" + i + ")"), u = this.prop("Inverted") ? Math.round(10 * (u + k)) / 10 : Math.round(10 * (u - k)) / 10, 0 !== s)
                    for (l = 0, d = n / t / (e + 1); l < e; l += 1) c[s].$minorTicks[l] || (c[s].$minorTicks[l] = D()(Object(O.a)("line")), c[s].$self.append(c[s].$minorTicks[l])), c[s].$minorTicks[l].css({
                        "stroke-width": this.prop("Scale.MinorTicks.Width"),
                        stroke: this.prop("Scale.MinorTicks.Color")
                    }), c[s].$minorTicks[l].attr({
                        x1: g + T * Math.cos((o + d) * C),
                        y1: m + T * Math.sin((o + d) * C),
                        x2: g + B * Math.cos((o + d) * C),
                        y2: m + B * Math.sin((o + d) * C)
                    }), d += n / t / (e + 1);
            this.$gauge.append(this.$pacesCont)
        },
        createEllipse: function() {},
        render: function() {
            var t = this.prop("Border.Width") || 0,
                e = this.prop("SemicircleOffset"),
                n = this.prop("Height") - t,
                a = this.prop("Height") - t,
                i = void 0;
            return this.prop("Width", this.prop("Height")), o.a.render.apply(this, arguments), this.$gauge || (this.$gauge = D()(Object(O.a)("g"))), this.$gauge.empty(), this.attr("isVertical", !0), this.attr("isHorizontal", !1), (i = void 0 !== this.types[this.prop("Type")] ? this.types[this.prop("Type")].render.call(this, n < 0 ? 0 : n, a < 0 ? 0 : a, e < 1 ? 1 : e) : this.types.Circle.render.call(this, n < 0 ? 0 : n, a < 0 ? 0 : a, e < 1 ? 1 : e)).css({
                fill: this.prop("Fill.Color"),
                stroke: this.prop("Border.Color"),
                "stroke-width": 1,
                "stroke-dasharray": r.a.get("dasharrays")[this.prop("Border.Style")]
            }), this.$gauge.append(i), this.$contentGroup.append(this.$gauge), this.$g
        },
        fillPropsNET: function(t) {
            return o.a.fillPropsNET.apply(this, arguments), this.fillFontNET(t, "Scale."), this
        },
        toXMLNET: function(t) {
            var n = this;
            return new Promise(function(e) {
                o.a.toXMLNET.call(n, t).then(function(t) {
                    return t = D()(t), Object(i.a)(t, n, "Scale."), e(t.get(0))
                })
            })
        }
    })
}, function(t, e, n) {
    "use strict";
    e.a = function(t) {
        var e = t.touches,
            n = t.changedTouches;
        return t.pageX = t.pageX || e && e[0] && e[0].pageX || n && n[0].pageX || 0, t.pageY = t.pageY || e && e[0] && e[0].pageY || n && n[0].pageY || 0, t
    }
}, , , , , , , , function(t, e, n) {
    "use strict";
    var a = n(43);
    e.a = function(t) {
        var e;
        return "string" != typeof t || !Object(a.a)(t) && !Object(a.a)(t.replace(",", ".")) || !(e = parseFloat(t.replace(",", "."), 10)) && 0 !== e ? t : e
    }
}, function(t, e, n) {
    "use strict";
    var a, i, r, o, s, l;
    n.r(e), a = n(0), i = n.n(a), r = n(9), o = n(3), s = n(5), l = n(53), e.default = r.default.createObject(r.default, {
        title: "Objects PictureObject",
        info: "PictureObjectInfo",
        icon: "icon-103",
        pos: 20,
        type: "PictureObject",
        disabled: !1,
        _init: function() {
            r.default._init.apply(this, arguments), this.defaultValues = {
                Angle: 0,
                Tile: !1,
                Transparency: 0,
                TransparentColor: "transparent",
                Image: "",
                ShowErrorImage: !1,
                SizeMode: "Zoom",
                Grayscale: !1
            }, this.prop({
                Name: "Picture",
                Width: 75.6,
                Height: 75.6
            }), this.attr({
                prefix: "data:image/png;base64,",
                withPadding: !0
            })
        },
        dblclick: function() {
            var e = this;
            n.e(14).then(n.bind(null, 577)).then(function(t) {
                return t.create(e)
            })
        },
        render: function() {
            var t = this.prop("Angle");
            return r.default.render.apply(this, arguments), this.$image && this.$rGroup || (this.$rGroup = i()(Object(o.a)("g")), this.$image = i()(Object(o.a)("image", {
                class: "move"
            })), this.$rGroup.append(this.$image), this.$contentGroup.append(this.$rGroup)), this.$image.attr({
                width: this.prop("Width") + "px",
                height: this.prop("Height") + "px",
                x: 0,
                y: 0
            }), Object(l.a)(this.$image[0], "href", this.prop("ImageLocation") || this.prop("Image")), this.prop("Grayscale") ? Object(s.a)(this.$image[0], "filter", "url(#grayscale)") : Object(s.a)(this.$image[0], "filter", ""), void 0 !== t && Object(s.a)(this.$rGroup[0], "transform", "rotate($angle $x $y)".replace(/\$angle/g, t).replace(/\$x/g, this.prop("Width") / 2).replace(/\$y/g, this.prop("Height") / 2)), this.$g
        },
        fillPropsNET: function() {
            var t = "";
            return r.default.fillPropsNET.apply(this, arguments), this.prop("Image") && (t = this.attr("prefix") + this.prop("Image")), this.prop("Image", t), this
        },
        toXMLNET: function(t) {
            var a = this;
            return new Promise(function(n) {
                r.default.toXMLNET.call(a, t).then(function(t) {
                    if (a.prop("ImageLocation")) i()(t).removeAttr("Image");
                    else {
                        var e = a.prop("Image").replace(/^([\w:\/;]+base64,){1}/, "");
                        e && i()(t).attr("Image", e)
                    }
                    return n(t)
                })
            })
        }
    })
}, function(t, e, n) {
    "use strict";
    n.r(e);
    var a = n(9);
    e.default = a.default.createObject(a.default, {
        title: "Objects RichObject",
        info: "RichObjectInfo",
        icon: "icon-126",
        pos: 90,
        type: "RichObject",
        disabled: !1,
        _init: function() {
            a.default._init.apply(this, arguments), this.defaultValues = {
                "Padding.Left": 2,
                "Padding.Right": 2,
                CanBreak: !0,
                ProcessAt: "Default",
                AllowExpressions: !0,
                Editable: !1,
                OldBreakStyle: !1
            }, this.prop({
                Name: "Rich",
                Width: 94.5,
                Height: 18.9,
                Text: ""
            }), this.attr({
                withPadding: !0
            })
        }
    })
}, function(t, e, n) {
    "use strict";
    var a, d, u, h, f;
    n.r(e), a = n(0), d = n.n(a), u = n(9), h = n(3), f = n(5), e.default = u.default.createObject(u.default, {
        title: "Objects CheckBoxObject",
        info: "CheckBoxObjectInfo",
        icon: "icon-116",
        pos: 100,
        type: "CheckBoxObject",
        disabled: !1,
        _init: function() {
            u.default._init.apply(this, arguments), this.defaultValues = {
                UncheckedSymbol: "None",
                CheckWithRatio: 1,
                CheckedSymbol: "Check",
                Editable: !1,
                HideIfUnchecked: !1
            }, this.prop({
                Name: "CheckBox",
                Width: 18.9,
                Height: 18.9,
                CheckColor: "#000000",
                CheckedSymbol: "Check",
                CheckWithRatio: 1,
                UncheckedSymbol: "None",
                Checked: !0,
                DataColumn: "",
                expression: ""
            })
        },
        render: function() {
            var t, e, n, a, i, r, o = this.prop("Width"),
                s = this.prop("Height"),
                l = this.prop("Left"),
                c = this.prop("Top"),
                p = this.prop("CheckWithRatio");
            if (u.default.render.apply(this, arguments), this.$contentGroup.children().length || (this.$nestedG1 = d()(Object(h.a)("g")), this.$nestedG2 = d()(Object(h.a)("g")), this.$textNode = d()(Object(h.a)("text", {
                    class: "text"
                })), this.$nestedG2.append(this.$textNode), this.$nestedG1.append(this.$nestedG2), this.$contentGroup.append(this.$nestedG1)), this.prop("Checked")) switch (t = this.prop("CheckedSymbol")) {
                case "Check":
                    t = "✓";
                    break;
                case "Cross":
                    t = "x";
                    break;
                case "Plus":
                    t = "+";
                    break;
                case "Fill":
                    t = "-";
                    break;
                default:
                    t = "✓"
            } else switch (t = this.prop("UncheckedSymbol")) {
                case "None":
                    t = "";
                    break;
                case "Cross":
                    t = "x";
                    break;
                case "Minus":
                    t = "-";
                    break;
                default:
                    t = ""
            }
            return this.$textNode.text(t), e = [l, c], n = [l + o, c + s], (a = o / 2 - (r = Math.sqrt(Math.pow(n[0] - e[0], 2) + Math.pow(n[1] - e[1], 2)) / 2) / 2) < 0 && (a = 0), (i = s / 2 - r / 2) < r && (i = r), Object(f.a)(this.$nestedG1[0], "transform", "translate(" + a + "," + i + ")"), this.$textNode.css({
                "font-size": r,
                fill: this.prop("CheckColor"),
                "font-weight": p < 1 ? "normal" : "bold"
            }), this.$g
        }
    })
}, function(t, e, n) {
    "use strict";
    var a, m, c, b, i, r, o, v, y;
    n.r(e), a = n(0), m = n.n(a), c = n(4), b = n(16), i = n(24), r = n(2), o = n(1), v = n(3), y = n(5), e.default = b.a.createObject(b.a, {
        title: "Objects CellularTextObject",
        info: "CellularTextObjectInfo",
        icon: "icon-cellular_text",
        pos: 120,
        type: "CellularTextObject",
        disabled: !1,
        _init: function() {
            b.a._init.apply(this, arguments), this.highlights = i.a.create(this), this.defaultValues = {
                VertAlign: "Top",
                HorzAlign: "Left",
                "TextFill.Color": "#000000",
                ParagraphOffset: 0,
                ProcessAt: "Default",
                WordWrap: !0,
                AllowExpressions: !0,
                Editable: !1
            }, this.prop({
                Name: "CellularText",
                Text: "",
                Width: 226.8,
                Height: 28.35,
                CellWidth: 0,
                CellHeight: 0,
                HorzSpacing: 0,
                VertSpacing: 0,
                "Border.Lines": "All"
            }), this.attr({
                "Font.Name": c.a.get("default-font-name"),
                "Font.Size": "10pt",
                "Font.Bold": !1,
                "Font.Italic": !1,
                "Font.Underline": !1,
                "Font.Strikeout": !1,
                "text-anchor": "middle",
                cellWidth: 28.34,
                cellHeight: 28.34
            }), this.setMinWH()
        },
        setMinWH: function() {
            this.prop("CellWidth") && this.prop("CellHeight") ? (this.attr("minWidth", this.prop("CellWidth") + this.prop("HorzSpacing")), this.attr("minHeight", this.prop("CellHeight") + this.prop("VertSpacing"))) : (this.attr("minWidth", this.attr("cellWidth") + this.prop("HorzSpacing")), this.attr("minHeight", this.attr("cellHeight") + this.prop("VertSpacing")))
        },
        _createLine: function(t) {
            var e = m()(Object(v.a)("line", {
                x1: t.x1,
                y1: t.y1,
                x2: t.x2,
                y2: t.y2
            }));
            return e.css({
                stroke: t.Color,
                "stroke-width": t.Width,
                "stroke-dasharray": t.Style
            }), e
        },
        appendEdges: function() {},
        appendBorders: function() {},
        appendBordersForCell: function(t, e, n) {
            var a = c.a.get("dasharrays"),
                i = this.prop("Border.Lines").split(/,[\s]?/),
                r = this.prop("Border.Color"),
                o = this.prop("Border.Width"),
                s = this.prop("Border.Style"),
                l = m()(); - 1 === m.a.inArray("None", i) && ((-1 < m.a.inArray("All", i) || -1 < m.a.inArray("Top", i)) && l.push(this._createLine({
                x1: 0,
                y1: 0,
                x2: e,
                y2: 0,
                Color: this.prop("Border.TopLine.Color") || r,
                Width: this.prop("Border.TopLine.Width") || o,
                Style: a[this.prop("Border.TopLine.Style") || s]
            })), (-1 < m.a.inArray("All", i) || -1 < m.a.inArray("Right", i)) && l.push(this._createLine({
                x1: e,
                y1: 0,
                x2: e,
                y2: n,
                Color: this.prop("Border.RightLine.Color") || r,
                Width: this.prop("Border.RightLine.Width") || o,
                Style: a[this.prop("Border.RightLine.Style") || s]
            })), (-1 < m.a.inArray("All", i) || -1 < m.a.inArray("Bottom", i)) && l.push(this._createLine({
                x1: e,
                y1: n,
                x2: 0,
                y2: n,
                Color: this.prop("Border.BottomLine.Color") || r,
                Width: this.prop("Border.BottomLine.Width") || o,
                Style: a[this.prop("Border.BottomLine.Style") || s]
            })), (-1 < m.a.inArray("All", i) || -1 < m.a.inArray("Left", i)) && l.push(this._createLine({
                x1: 0,
                y1: n,
                x2: 0,
                y2: 0,
                Color: this.prop("Border.LeftLine.Color") || r,
                Width: this.prop("Border.LeftLine.Width") || o,
                Style: a[this.prop("Border.LeftLine.Style") || s]
            }))), l.each(function() {
                t.append(this)
            })
        },
        generateCell: function(t, e, n, a) {
            var i = m()(Object(v.a)("g")),
                r = m()(Object(v.a)("rect", {
                    width: t,
                    height: e
                }));
            return r.css("fill", this.prop("Fill.Color")), Object(y.a)(i[0], "transform", "translate(" + n + "," + a + ")"), i.append(r), this.appendBordersForCell(i, t, e), i
        },
        dblclick: function() {
            o.a.trigger("show-expression-editor", {
                entity: this,
                prop: "Text"
            })
        },
        getContextMenuItems: function() {
            function t(t) {
                return e.onChangeCM(t)
            }
            var e = this;
            return [{
                name: r.a.tr("Edit..."),
                type: "default",
                closeAfter: !0,
                onClick: function() {
                    o.a.trigger("activate", e), e.dblclick()
                }
            }, {
                name: r.a.tr("TextObject Format"),
                type: "default",
                closeAfter: !0,
                onClick: function() {
                    o.a.trigger("activate", e), o.a.trigger("format", e)
                }
            }, {
                name: r.a.tr("Clear"),
                type: "default",
                closeAfter: !0,
                onClick: function() {
                    e.prop("Text", "", !0), e.render(), o.a.trigger("activate", e)
                }
            }, {
                name: r.a.tr("ComponentMenu Hyperlink"),
                type: "default",
                closeAfter: !0,
                onClick: function() {
                    o.a.trigger("hyperlink-editor", e)
                }
            }, {
                type: "separator"
            }, {
                name: r.a.tr("Can Grow"),
                type: "checkbox",
                curVal: e.prop("CanGrow"),
                prop: "CanGrow",
                onChange: t
            }, {
                name: r.a.tr("Can Shrink"),
                type: "checkbox",
                curVal: e.prop("CanShrink"),
                prop: "CanShrink",
                onChange: t
            }, {
                name: r.a.tr("Can Break"),
                type: "checkbox",
                curVal: e.prop("CanBreak"),
                prop: "CanBreak",
                onChange: t
            }, {
                name: r.a.tr("Grow To Bottom"),
                type: "checkbox",
                curVal: e.prop("GrowToBottom"),
                prop: "GrowToBottom",
                onChange: t
            }, {
                type: "separator"
            }, {
                name: r.a.tr("Auto Width"),
                type: "checkbox",
                curVal: e.prop("AutoWidth"),
                prop: "AutoWidth",
                onChange: t
            }, {
                name: r.a.tr("WordWrap"),
                type: "checkbox",
                curVal: e.prop("WordWrap"),
                prop: "WordWrap",
                onChange: t
            }, {
                name: r.a.tr("Allow Expressions"),
                type: "checkbox",
                curVal: e.prop("AllowExpressions"),
                prop: "AllowExpressions",
                onChange: t
            }, {
                type: "separator"
            }, {
                name: r.a.tr("Delete"),
                type: "default",
                disabled: !e.canBeRemoved(),
                closeAfter: !0,
                onClick: function() {
                    o.a.trigger("remove", e)
                }
            }, {
                type: "separator"
            }, {
                name: r.a.tr("Bring to Front"),
                type: "default",
                closeAfter: !0,
                onClick: function() {
                    o.a.trigger("bring-to-front", e)
                }
            }, {
                name: r.a.tr("Send to back"),
                type: "default",
                closeAfter: !0,
                onClick: function() {
                    o.a.trigger("send-to-back", e)
                }
            }]
        },
        render: function() {
            b.a.render.apply(this, arguments);
            var t, e, n, a = this.prop("Text").toString(),
                i = this.attr("cellWidth"),
                r = this.attr("cellHeight"),
                o = this.prop("HorzSpacing"),
                s = this.prop("VertSpacing"),
                l = this.attr("Font.Size"),
                c = this.attr("Font.Name"),
                p = 0,
                d = 0,
                u = 0,
                h = m()(Object(v.a)("g")),
                f = this.prop("Width"),
                g = this.prop("Height");
            for (this.$contentGroup.empty(), this.prop("CellWidth") && this.prop("CellHeight") && (i = this.prop("CellWidth"), r = this.prop("CellHeight")), t = Math.floor(f / i) * Math.floor(g / r); p < t && (f + 1 < d + i && (d = 0, u += r + s), !(g + 1 < u + r)); p += 1) e = this.generateCell(i, r, d, u), (n = m()(Object(v.a)("text"))).text(a[p]), n.css({
                "font-size": l + (m.a.isNumeric(l) ? "pt" : ""),
                fill: this.prop("TextFill.Color"),
                "font-family": c,
                "text-anchor": this.attr("text-anchor")
            }), Object(y.a)(n[0], "transform", "translate(" + i / 2 + "," + r / 1.3 + ")"), this.applyFontStyles(n), e.append(n), h.append(e), d += i + o;
            return this.$moveBlock.css("fill", "transparent"), this.$contentGroup.append(h), this.$g
        }
    })
}, function(t, e, n) {
    "use strict";
    var a, p, i, r, d, u, o, h, f, s, l, g;
    n.r(e), a = n(0), p = n.n(a), i = n(4), r = n(16), d = n(3), u = n(5), o = n(58), h = 6, f = 3, s = 3.6, l = 40, g = 18.89, e.default = r.a.createObject(r.a, {
        title: "Objects Gauge Linear",
        info: "LinearGaugeInfo",
        icon: "icon-135",
        pos: 130,
        type: "LinearGauge",
        groupType: "Gauge",
        disabled: !1,
        _init: function() {
            r.a._init.apply(this, arguments), this.defaultValues = {
                Maximum: 100,
                Minimum: 0,
                Value: 10,
                Inverted: !1,
                "Pointer.BorderColor": "#000",
                "Pointer.Color": "#FFA500",
                "Scale.MajorTicks.Color": "#000",
                "Scale.MajorTicks.Width": 2,
                "Scale.MinorTicks.Color": "#000",
                "Scale.MinorTicks.Width": 2
            }, this.prop({
                Name: "LinearGauge",
                Width: 302.36,
                Height: 75.59,
                "Border.Lines": "All"
            }), this.attr({
                "Scale.Font.Name": i.a.get("default-font-name"),
                "Scale.Font.Size": "8pt",
                "Scale.Font.Bold": !1,
                "Scale.Font.Italic": !1,
                "Scale.Font.Underline": !1,
                "Scale.Font.Strikeout": !1
            })
        },
        getRange: function() {
            return this.prop("Maximum") - this.prop("Minimum")
        },
        getPace: function() {
            return Math.floor(this.getRange() / (h - 1))
        },
        appendPointer: function() {
            var t, e, n = this.prop("Width"),
                a = this.prop("Height"),
                i = 100 * this.prop("Value") / this.getRange(),
                r = n - 2 * g,
                o = a - 2 * g;
            return this.$pointer || (this.$pointerContainer = p()(Object(d.a)("g")), this.$pointer = p()(Object(d.a)("path"))), e = this.prop("Pointer.Width", n * s / 100), t = this.prop("Pointer.Height", a * l / 100), Object(u.a)(this.$pointer[0], "d", "M" + e / 2 + " 0 L0 10 L0 " + t + " L" + e + " " + t + " L" + e + " 10 L" + e / 2 + " 0 Z"), this.$pointer.css({
                stroke: this.prop("Pointer.BorderColor"),
                fill: this.prop("Pointer.Color")
            }), this.prop("Inverted") ? this.attr("isHorizontal") ? (Object(u.a)(this.$pointer[0], "transform", "rotate(180, " + e / 2 + ", " + t / 2 + ")"), Object(u.a)(this.$pointerContainer[0], "transform", "translate(" + (r * i / 100 + g - e / 2) + ", " + g + ")")) : (Object(u.a)(this.$pointer[0], "transform", "rotate(90, " + e / 2 + ", " + t / 2 + ")"), Object(u.a)(this.$pointerContainer[0], "transform", "translate(" + 2 * g + ", " + (o + g - t / 2 - o * i / 100) + ")")) : this.attr("isHorizontal") ? (Object(u.a)(this.$pointer[0], "transform", "rotate(0)"), Object(u.a)(this.$pointerContainer[0], "transform", "translate(" + (r * i / 100 + g - e / 2) + ", " + a / 2 + ")")) : (Object(u.a)(this.$pointer[0], "transform", "rotate(270, " + e / 2 + ", " + t / 2 + ")"), Object(u.a)(this.$pointerContainer[0], "transform", "translate(" + n / 2 + ", " + (o + g - t / 2 - o * i / 100) + ")")), this.$pointerContainer.append(this.$pointer), this.$contentGroup.append(this.$pointerContainer), this.$pointer
        },
        createHorz: function() {
            var t, e, n, a = g,
                i = this.prop("Width"),
                r = this.prop("Height"),
                o = this.getPace(),
                s = this.prop("Minimum"),
                l = (i - 2 * g) / (h - 1);
            for (this.paces && !this.attr("isVertical") || (this.$contentGroup.empty(), this.paces = []), t = 0; t < h; t += 1) {
                if (this.paces[t] || (this.paces[t] = {
                        $self: p()(Object(d.a)("g")),
                        $minorTicks: [],
                        $majorTick: p()(Object(d.a)("line")),
                        $paceText: p()(Object(d.a)("text"))
                    }, this.paces[t].$self.append(this.paces[t].$majorTick, this.paces[t].$paceText), this.$contentGroup.append(this.paces[t].$self)), this.paces[t].$majorTick.css({
                        "stroke-width": this.prop("Scale.MajorTicks.Width"),
                        stroke: this.prop("Scale.MajorTicks.Color")
                    }), this.paces[t].$majorTick.attr({
                        x1: 0,
                        y1: 1,
                        x2: 0,
                        y2: r - 40
                    }), this.paces[t].$paceText.text(s), this.applyFontStyles(this.paces[t].$paceText, "Scale."), this.paces[t].$paceText.css({
                        "font-size": this.attr("Scale.Font.Size") + (p.a.isNumeric(this.attr("Scale.Font.Size")) ? "pt" : ""),
                        fill: "#000",
                        "font-family": this.attr("Scale.Font.Name"),
                        "text-anchor": "middle"
                    }), Object(u.a)(this.paces[t].$self[0], "transform", "translate(" + a + ", 20)"), a += l, t + 1 !== h)
                    for (n = e = 0; e < f; e += 1) this.paces[t].$minorTicks[e] || (this.paces[t].$minorTicks[e] = p()(Object(d.a)("line")), this.paces[t].$self.append(this.paces[t].$minorTicks[e])), this.paces[t].$minorTicks[e].css({
                        "stroke-width": this.prop("Scale.MinorTicks.Width"),
                        stroke: this.prop("Scale.MinorTicks.Color")
                    }), this.paces[t].$minorTicks[e].attr({
                        x1: 0,
                        y1: 0,
                        x2: 0,
                        y2: r - 60
                    }), n += l / (f + 1), Object(u.a)(this.paces[t].$minorTicks[e][0], "transform", "translate(" + n + ", 10)");
                s += o, this.prop("Inverted") ? Object(u.a)(this.paces[t].$paceText[0], "transform", "translate(0, " + (r - 20 - 5) + ")") : Object(u.a)(this.paces[t].$paceText[0], "transform", "translate(0, 0)")
            }
        },
        createVert: function() {
            var t, e, n, a = g,
                i = this.prop("Width"),
                r = this.prop("Height"),
                o = this.getPace(),
                s = this.prop("Minimum"),
                l = r - 2 * g,
                c = l / (h - 1);
            for (a += l, this.paces && !this.attr("isHorizontal") || (this.$contentGroup.empty(), this.paces = []), t = 0; t < h; t += 1)
                if (this.paces[t] || (this.paces[t] = {
                        $self: p()(Object(d.a)("g")),
                        $minorTicks: [],
                        $majorTick: p()(Object(d.a)("line")),
                        $paceText: p()(Object(d.a)("text"))
                    }, this.paces[t].$self.append(this.paces[t].$majorTick, this.paces[t].$paceText), this.$contentGroup.append(this.paces[t].$self)), this.paces[t].$majorTick.css({
                        "stroke-width": this.prop("Scale.MajorTicks.Width"),
                        stroke: this.prop("Scale.MajorTicks.Color")
                    }), this.paces[t].$majorTick.attr({
                        x1: 0,
                        y1: 1,
                        x2: i - 40,
                        y2: 0
                    }), this.paces[t].$paceText.text(s), this.paces[t].$paceText.css({
                        "font-size": this.attr("Scale.Font.Size") + (p.a.isNumeric(this.attr("Scale.Font.Size")) ? "pt" : ""),
                        fill: "#000",
                        "font-family": this.attr("Scale.Font.Name"),
                        "text-anchor": "middle"
                    }), this.applyFontStyles(this.paces[t].$paceText, "Scale."), Object(u.a)(this.paces[t].$self[0], "transform", "translate(20, " + a + ")"), a -= c, s += o, this.prop("Inverted") ? Object(u.a)(this.paces[t].$paceText[0], "transform", "translate(" + (i - 20 - 10) + ", 5)") : Object(u.a)(this.paces[t].$paceText[0], "transform", "translate(-10, 5)"), 0 !== t)
                    for (n = e = 0; e < f; e += 1) this.paces[t].$minorTicks[e] || (this.paces[t].$minorTicks[e] = p()(Object(d.a)("line")), this.paces[t].$self.append(this.paces[t].$minorTicks[e])), this.paces[t].$minorTicks[e].css({
                        "stroke-width": this.prop("Scale.MinorTicks.Width"),
                        stroke: this.prop("Scale.MinorTicks.Color")
                    }), this.paces[t].$minorTicks[e].attr({
                        x1: 0,
                        y1: 0,
                        x2: i - 60,
                        y2: 0
                    }), n += c / (f + 1), Object(u.a)(this.paces[t].$minorTicks[e][0], "transform", "translate(10, " + n + ")")
        },
        render: function() {
            return r.a.render.apply(this, arguments), this.prop("Width") > this.prop("Height") ? (this.createHorz(), this.attr("isHorizontal", !0), this.attr("isVertical", !1)) : (this.createVert(), this.attr("isVertical", !0), this.attr("isHorizontal", !1)), this.appendPointer(), this.$g
        },
        fillPropsNET: function(t) {
            return r.a.fillPropsNET.apply(this, arguments), this.fillFontNET(t, "Scale."), this
        },
        toXMLNET: function(t) {
            var n = this;
            return new Promise(function(e) {
                r.a.toXMLNET.call(n, t).then(function(t) {
                    return t = p()(t), Object(o.a)(t, n, "Scale."), e(t.get(0))
                })
            })
        }
    })
}, function(t, e, n) {
    "use strict";
    var a, m, i, r, b, v, o, y, C, S, x, w;
    n.r(e), a = n(0), m = n.n(a), i = n(4), r = n(16), b = n(3), v = n(5), o = n(58), y = 6, C = 3, S = 10, x = 18.89, w = 14.89, e.default = r.a.createObject(r.a, {
        title: "Objects Gauge Simple",
        info: "SimpleGaugeInfo",
        icon: "icon-135",
        pos: 140,
        type: "SimpleGauge",
        groupType: "Gauge",
        disabled: !1,
        _init: function() {
            r.a._init.apply(this, arguments), this.defaultValues = {
                Maximum: 100,
                Minimum: 0,
                Value: 75,
                Inverted: !1,
                "Pointer.BorderColor": "#000",
                "Pointer.Color": "#FFA500",
                "Scale.FirstSubScale.Enabled": !0,
                "Scale.FirstSubScale.ShowCaption": !0,
                "Scale.SecondSubScale.Enabled": !0,
                "Scale.SecondSubScale.ShowCaption": !0,
                "Scale.MajorTicks.Color": "#000",
                "Scale.MajorTicks.Width": 2,
                "Scale.MinorTicks.Color": "#000",
                "Scale.MinorTicks.Width": 2,
                "Pointer.Height": 6
            }, this.prop({
                Name: "SimpleGauge",
                Width: 302.36,
                Height: 75.59,
                "Border.Lines": "All"
            }), this.attr({
                "Scale.Font.Name": i.a.get("default-font-name"),
                "Scale.Font.Size": "8pt",
                "Scale.Font.Bold": !1,
                "Scale.Font.Italic": !1,
                "Scale.Font.Underline": !1,
                "Scale.Font.Strikeout": !1
            })
        },
        getRange: function() {
            return this.prop("Maximum") - this.prop("Minimum")
        },
        getPace: function() {
            return Math.floor(this.getRange() / (y - 1))
        },
        appendPointer: function() {
            var t, e = this.prop("Pointer.Height"),
                n = this.prop("Width"),
                a = this.prop("Height"),
                i = 100 * this.prop("Value") / this.getRange(),
                r = n - 2 * x,
                o = a - 2 * w;
            this.$pointer || (this.$pointerContainer = m()(Object(b.a)("g")), this.$pointer = m()(Object(b.a)("rect"))), this.$pointer.css({
                stroke: this.prop("Pointer.BorderColor"),
                fill: this.prop("Pointer.Color")
            }), this.$pointerContainer.append(this.$pointer), this.$contentGroup.append(this.$pointerContainer), this.attr("isHorizontal") ? (t = this.prop("Pointer.Width", r * i / 100), this.$pointer.attr({
                width: t,
                height: e
            }), Object(v.a)(this.$pointerContainer[0], "transform", "translate(" + x + ", " + (a / 2 - e / 2) + ")")) : (t = this.prop("Pointer.Width", o * i / 100), this.$pointer.attr({
                width: e,
                height: t
            }), Object(v.a)(this.$pointerContainer[0], "transform", "translate(" + (n / 2 - e / 2) + ", " + (a - w - t) + ")"))
        },
        createHorz: function() {
            function t(t, e) {
                e.text(c), i.applyFontStyles(e, "Scale."), e.css({
                    "font-size": d + (m.a.isNumeric(i.attr("Scale.Font.Size")) ? "pt" : ""),
                    fill: "#000",
                    "font-family": i.attr("Scale.Font.Name"),
                    "text-anchor": "middle"
                })
            }
            var e, n, a, i = this,
                r = x,
                o = this.prop("Width"),
                s = this.prop("Height"),
                l = this.getPace(),
                c = this.prop("Minimum"),
                p = (o - 2 * x) / (y - 1),
                d = parseInt(this.attr("Scale.Font.Size"), 10),
                u = s - 2 * w - S,
                h = 50 * u / 100,
                f = h / 2;
            for (this.paces && !this.attr("isVertical") || (this.$contentGroup.empty(), this.paces = []), e = 0; e < y; e += 1) {
                if (this.paces[e] || (this.paces[e] = {
                        $self: m()(Object(b.a)("g")),
                        $minorTicksTop: [],
                        $majorTickTop: m()(Object(b.a)("line")),
                        $paceTextTop: m()(Object(b.a)("text")),
                        $majorTickBottom: m()(Object(b.a)("line")),
                        $minorTicksBottom: [],
                        $paceTextBottom: m()(Object(b.a)("text"))
                    }, this.paces[e].$self.append(this.paces[e].$majorTickTop, this.paces[e].$paceTextTop, this.paces[e].$majorTickBottom, this.paces[e].$paceTextBottom), this.$contentGroup.append(this.paces[e].$self)), this.paces[e].$majorTickTop.css({
                        "stroke-width": this.prop("Scale.MajorTicks.Width"),
                        stroke: this.prop("Scale.MajorTicks.Color")
                    }), this.paces[e].$majorTickBottom.css({
                        "stroke-width": this.prop("Scale.MajorTicks.Width"),
                        stroke: this.prop("Scale.MajorTicks.Color")
                    }), this.paces[e].$majorTickTop.attr({
                        x1: 0,
                        y1: 1,
                        x2: 0,
                        y2: h
                    }), this.paces[e].$majorTickBottom.attr({
                        x1: 0,
                        y1: h + S,
                        x2: 0,
                        y2: h + S + h
                    }), m.a.each([this.paces[e].$paceTextTop, this.paces[e].$paceTextBottom], t), Object(v.a)(this.paces[e].$paceTextBottom[0], "transform", "translate(0, " + (u + w + 2) + ")"), Object(v.a)(this.paces[e].$self[0], "transform", "translate(" + r + ", " + w + ")"), r += p, e + 1 !== y)
                    for (a = n = 0; n < C; n += 1) this.paces[e].$minorTicksTop[n] || (this.paces[e].$minorTicksTop[n] = m()(Object(b.a)("line")), this.paces[e].$self.append(this.paces[e].$minorTicksTop[n])), this.paces[e].$minorTicksBottom[n] || (this.paces[e].$minorTicksBottom[n] = m()(Object(b.a)("line")), this.paces[e].$self.append(this.paces[e].$minorTicksBottom[n])), this.paces[e].$minorTicksTop[n].css({
                        "stroke-width": this.prop("Scale.MinorTicks.Width"),
                        stroke: this.prop("Scale.MinorTicks.Color")
                    }), this.paces[e].$minorTicksBottom[n].css({
                        "stroke-width": this.prop("Scale.MinorTicks.Width"),
                        stroke: this.prop("Scale.MinorTicks.Color")
                    }), this.paces[e].$minorTicksTop[n].attr({
                        x1: 0,
                        y1: 0,
                        x2: 0,
                        y2: f
                    }), this.paces[e].$minorTicksBottom[n].attr({
                        x1: 0,
                        y1: f + h + S,
                        x2: 0,
                        y2: f + h + S + f
                    }), a += p / (C + 1), Object(v.a)(this.paces[e].$minorTicksTop[n][0], "transform", "translate(" + a + ", " + f + ")"), Object(v.a)(this.paces[e].$minorTicksBottom[n][0], "transform", "translate(" + a + ", " + -f + ")");
                c += l
            }
        },
        createVert: function() {
            function t(t, e) {
                e.text(c), i.applyFontStyles(e, "Scale."), e.css({
                    "font-size": u + (m.a.isNumeric(i.attr("Scale.Font.Size")) ? "pt" : ""),
                    fill: "#000",
                    "font-family": i.attr("Scale.Font.Name"),
                    "text-anchor": "middle"
                })
            }
            var e, n, a, i = this,
                r = w,
                o = this.prop("Width"),
                s = this.prop("Height"),
                l = this.getPace(),
                c = this.prop("Minimum"),
                p = s - 2 * w,
                d = p / (y - 1),
                u = parseInt(this.attr("Scale.Font.Size"), 10),
                h = o - 2 * x - S,
                f = 50 * h / 100,
                g = f / 2;
            for (r += p, this.paces && !this.attr("isHorizontal") || (this.$contentGroup.empty(), this.paces = []), e = 0; e < y; e += 1) {
                if (this.paces[e] || (this.paces[e] = {
                        $self: m()(Object(b.a)("g")),
                        $minorTicksLeft: [],
                        $majorTickLeft: m()(Object(b.a)("line")),
                        $paceTextLeft: m()(Object(b.a)("text")),
                        $majorTickRight: m()(Object(b.a)("line")),
                        $minorTicksRight: [],
                        $paceTextRight: m()(Object(b.a)("text"))
                    }, this.paces[e].$self.append(this.paces[e].$majorTickLeft, this.paces[e].$paceTextLeft, this.paces[e].$majorTickRight, this.paces[e].$paceTextRight), this.$contentGroup.append(this.paces[e].$self)), this.paces[e].$majorTickLeft.css({
                        "stroke-width": this.prop("Scale.MajorTicks.Width"),
                        stroke: this.prop("Scale.MajorTicks.Color")
                    }), this.paces[e].$majorTickRight.css({
                        "stroke-width": this.prop("Scale.MajorTicks.Width"),
                        stroke: this.prop("Scale.MajorTicks.Color")
                    }), this.paces[e].$majorTickLeft.attr({
                        x1: 1,
                        y1: 0,
                        x2: f,
                        y2: 0
                    }), this.paces[e].$majorTickRight.attr({
                        x1: f + S,
                        y1: 0,
                        x2: f + S + f,
                        y2: 0
                    }), m.a.each([this.paces[e].$paceTextLeft, this.paces[e].$paceTextRight], t), Object(v.a)(this.paces[e].$paceTextRight[0], "transform", "translate(" + (h + x) + ", 0)"), Object(v.a)(this.paces[e].$self[0], "transform", "translate(" + x + ", " + r + ")"), r -= d, 0 < e)
                    for (a = n = 0; n < C; n += 1) this.paces[e].$minorTicksLeft[n] || (this.paces[e].$minorTicksLeft[n] = m()(Object(b.a)("line")), this.paces[e].$self.append(this.paces[e].$minorTicksLeft[n])), this.paces[e].$minorTicksRight[n] || (this.paces[e].$minorTicksRight[n] = m()(Object(b.a)("line")), this.paces[e].$self.append(this.paces[e].$minorTicksRight[n])), this.paces[e].$minorTicksLeft[n].css({
                        "stroke-width": this.prop("Scale.MinorTicks.Width"),
                        stroke: this.prop("Scale.MinorTicks.Color")
                    }), this.paces[e].$minorTicksRight[n].css({
                        "stroke-width": this.prop("Scale.MinorTicks.Width"),
                        stroke: this.prop("Scale.MinorTicks.Color")
                    }), this.paces[e].$minorTicksLeft[n].attr({
                        x1: 0,
                        y1: 0,
                        x2: g,
                        y2: 0
                    }), this.paces[e].$minorTicksRight[n].attr({
                        x1: g + f + S,
                        y1: 0,
                        x2: g + f + S + g,
                        y2: 0
                    }), a += d / (C + 1), Object(v.a)(this.paces[e].$minorTicksLeft[n][0], "transform", "translate(" + g + ", " + a + ")"), Object(v.a)(this.paces[e].$minorTicksRight[n][0], "transform", "translate(" + -g + ", " + a + ")");
                c += l
            }
        },
        render: function() {
            return r.a.render.apply(this, arguments), this.prop("Width") > this.prop("Height") ? (this.createHorz(), this.attr("isHorizontal", !0), this.attr("isVertical", !1)) : (this.createVert(), this.attr("isVertical", !0), this.attr("isHorizontal", !1)), this.appendPointer(), this.$g
        },
        fillPropsNET: function(t) {
            return r.a.fillPropsNET.apply(this, arguments), this.fillFontNET(t, "Scale."), this
        },
        toXMLNET: function(t) {
            var n = this;
            return new Promise(function(e) {
                r.a.toXMLNET.call(n, t).then(function(t) {
                    return t = m()(t), Object(o.a)(t, n, "Scale."), e(t.get(0))
                })
            })
        }
    })
}, function(t, e, n) {
    "use strict";
    var a, r, i, o, s, l, c, p, d, u, h;
    n.r(e), a = n(0), r = n.n(a), i = n(4), o = n(16), s = n(58), l = n(3), c = n(5), p = n(43), d = n(67), u = "full", h = "Small", e.default = o.a.createObject(o.a, {
        title: "Objects Gauge SimpleProgress",
        info: "SimpleProgressGaugeInfo",
        icon: "icon-135",
        pos: 140,
        type: "SimpleProgressGauge",
        groupType: "Gauge",
        disabled: !1,
        _init: function() {
            o.a._init.apply(this, arguments), this.defaultValues = {
                Maximum: 100,
                Minimum: 0,
                Value: 75,
                Inverted: !1,
                "Pointer.BorderColor": "transparent",
                "Pointer.Color": "#FFA500",
                "Pointer.SmallPointerWidth": .1,
                "Pointer.Type": "full"
            }, this.prop({
                Name: "SimpleProgressGauge",
                Width: 302.36,
                Height: 75.59,
                "Border.Lines": "All",
                "Label.Color": "#000000",
                "Label.Decimals": 0
            }), this.attr({
                "Label.Font.Name": i.a.get("default-font-name"),
                "Label.Font.Size": "8pt",
                "Label.Font.Bold": !1,
                "Label.Font.Italic": !1,
                "Label.Font.Underline": !1,
                "Label.Font.Strikeout": !1
            })
        },
        getPercentage: function() {
            var t = this.prop("Value"),
                e = this.prop("Minimum"),
                n = this.prop("Maximum");
            return (t - e) / (n - e) * 100
        },
        appendPointer: function() {
            var t, e, n, a = this.prop("Pointer.Type");
            return this.$pointer || (this.$pointerContainer = r()(Object(l.a)("g")), this.$pointer = r()(Object(l.a)("rect"))), this.$pointer.css("fill", this.prop("Pointer.Color")), this.$pointer.attr("height", this.prop("Height")), t = this.prop("Width"), e = this.getPercentage(), a === u ? this.$pointer.attr("width", t * e / 100) : a === h && (n = t * this.prop("Pointer.SmallPointerWidth"), this.$pointer.attr("width", n), Object(c.a)(this.$pointer.get(0), "transform", "translate({0},0)".format(t * e / 100 - n / 2))), this.$pointer.css({
                stroke: this.prop("Pointer.BorderColor"),
                "stroke-width": 1
            }), this.$pointerContainer.append(this.$pointer), this.$contentGroup.append(this.$pointerContainer), this.$pointer
        },
        appendLabel: function() {
            var t, e, n, a, i;
            return this.$label || (this.$labelContainer = r()(Object(l.a)("g")), this.$label = r()(Object(l.a)("text"))), t = this.attr("Label.Font.Size"), e = this.attr("Label.Font.Name"), n = "{0}%".format(this.getPercentage()), this.$label.text(n), i = 30 < (a = Object(d.a)(n, {
                "font-size": t,
                "font-family": e,
                "font-weight": this.attr("Font.Bold") ? "500" : "100"
            })).h ? a.h / 1.5 : this.prop("Height") / 2, Object(c.a)(this.$label.get(0), "transform", "translate({0},{1})".format(this.prop("Width") / 2 - a.w / 2, i)), this.$label.css({
                "font-size": t + (Object(p.a)(t) ? "pt" : ""),
                fill: this.prop("Label.Color"),
                "font-family": e
            }), this.applyFontStyles(this.$label, "Label."), this.$labelContainer.append(this.$label), this.$contentGroup.append(this.$labelContainer), this.$label
        },
        render: function() {
            return o.a.render.apply(this, arguments), this.appendPointer(), this.appendLabel(), this.$g
        },
        fillPropsNET: function(t) {
            return o.a.fillPropsNET.apply(this, arguments), this.fillFontNET(t, "Label."), this
        },
        toXMLNET: function(t) {
            var n = this;
            return new Promise(function(e) {
                o.a.toXMLNET.call(n, t).then(function(t) {
                    return t = r()(t), Object(s.a)(t, n, "Label."), e(t.get(0))
                })
            })
        }
    })
}, function(t, e, n) {
    "use strict";
    n.r(e);
    var a = n(25);
    e.default = a.default.createObject(a.default, {
        title: "Objects HtmlObject",
        info: "HtmlObjectInfo",
        icon: "icon-135",
        pos: 160,
        type: "HtmlObject",
        disabled: !1,
        _init: function() {
            a.default._init.apply(this, arguments), this.defaultValues = {
                "Padding.Left": 2,
                "Padding.Right": 2,
                CanBreak: !0,
                TabWidth: 58,
                ProcessAt: "Default",
                AllowExpressions: !0,
                "TextFill.Color": "#000"
            }, this.attr({
                "droppable-view": !0,
                "droppable-component": !0,
                withPadding: !0
            }), this.prop("Name", "Html")
        }
    })
}, function(t, e, n) {
    "use strict";
    var a, i, r, o, s, l, c, p;
    n.r(e), a = n(0), i = n.n(a), r = n(9), o = n(3), s = n(5), l = n(53), c = n(23), p = new c.a("SVGObject"), e.default = r.default.createObject(r.default, {
        title: "Objects SVGObject",
        info: "SVGObjectInfo",
        icon: "icon-103",
        pos: 170,
        type: "SVGObject",
        disabled: !1,
        _init: function() {
            r.default._init.apply(this, arguments), this.defaultValues = {
                Angle: 0,
                Tile: !1,
                Transparency: 0,
                TransparentColor: "transparent",
                Tag: "",
                ShowErrorImage: !1,
                SizeMode: "Zoom",
                Grayscale: !1,
                MaxSvgHeight: 0,
                MaxSvgWidth: 0,
                SvgDocument: "",
                SvgGrayscale: ""
            }, this.prop({
                Name: "SVG",
                Width: 75.6,
                Height: 75.6
            }), this.attr("withPadding", !0)
        },
        render: function() {
            var t = this.prop("Angle"),
                e = this.prop("ImageLocation"),
                n = this.prop("SvgData");
            return r.default.render.apply(this, arguments), this.$rGroup || (this.$rGroup = i()(Object(o.a)("g")), this.$contentGroup.append(this.$rGroup)), e ? (this.$image || (this.$image = i()(Object(o.a)("image", {
                class: "move"
            })), this.$rGroup.append(this.$image)), this.$image.attr({
                width: this.prop("Width") + "px",
                height: this.prop("Height") + "px",
                x: 0,
                y: 0
            }), Object(l.a)(this.$image[0], "href", e)) : n && this.$rGroup.append(n), this.prop("Grayscale") ? Object(s.a)(this.$rGroup.get(0), "filter", "url(#grayscale)") : Object(s.a)(this.$rGroup.get(0), "filter", ""), void 0 !== t && Object(s.a)(this.$rGroup[0], "transform", "rotate($angle $x $y)".replace(/\$angle/g, t).replace(/\$x/g, this.prop("Width") / 2).replace(/\$y/g, this.prop("Height") / 2)), this.$g
        },
        fillPropsNET: function() {
            if (r.default.fillPropsNET.apply(this, arguments), this.prop("SvgData")) try {
                this.prop("SvgData", atob(this.prop("SvgData")))
            } catch (t) {
                p.error(t)
            }
            return this
        },
        toXMLNET: function(e) {
            var n = this;
            return new Promise(function(t) {
                !n.prop("ImageLocation") && n.prop("SvgData") && n.prop("SvgData", btoa(n.prop("SvgData"))), r.default.toXMLNET.call(n, e).then(t)
            })
        }
    })
}, function(t, e, n) {
    "use strict";
    var a, i, r, o, s;
    n.r(e), a = n(0), i = n.n(a), r = n(9), o = n(2), s = n(3), e.default = r.default.createObject(r.default, {
        title: "Objects DigitalSignatureObject",
        info: "DigitalSignatureObjectInfo",
        icon: "icon-133",
        pos: 131,
        type: "DigitalSignatureObject",
        disabled: !1,
        _init: function() {
            r.default._init.apply(this, arguments), this.defaultValues = {
                Tile: !1,
                Transparency: 0,
                TransparentColor: "transparent"
            }, this.prop({
                Name: "DigitalSignature",
                Width: 132.6,
                Height: 45.6
            })
        },
        render: function() {
            return r.default.render.apply(this, arguments), this.$rGroup || (this.$rGroup = i()(Object(s.a)("g")), this.$text = i()(Object(s.a)("text")), this.$text.text(o.a.tr("Objects DigitalSignatureObject")), this.$text.css({
                "font-size": "10pt",
                fill: "#000",
                "font-family": "Arial",
                "text-anchor": "middle"
            }), this.$text.attr("transform", "translate(60, 15)"), this.$rGroup.append(this.$text), this.$contentGroup.append(this.$rGroup)), this.$g
        }
    })
}, function(t, e, n) {
    "use strict";
    var a, i, r, o, s, l, c, p, d, u, h, f, g, m, b, v, y, C, S, x, w, T, B, k, $, P, D, O, M, E, A, j, F, L, R, N, H, W, V, z, I, _, G, U, X, Y, K, J, q, Z, Q, tt;

    function et(t, e) {
        ! function(t, e) {
            if (!(t instanceof e)) throw new TypeError("Cannot call a class as a function")
        }(this, et), this.self = t, this.barcodeType = e, this.props = Z[this.barcodeType], this.isExist() || Q.error("there is no such barcode type")
    }
    n.r(e), a = n(0), i = n.n(a), r = n(9), o = n(27), n(310), s = n(23), l = n(3), c = n(5), p = n(53), d = n(82), u = n(282), h = n.n(u), f = n(283), g = n.n(f), m = n(284), b = n.n(m), v = n(285), y = n.n(v), C = n(286), S = n.n(C), x = n(287), w = n.n(x), T = n(288), B = n.n(T), k = n(289), $ = n.n(k), P = n(290), D = n.n(P), O = n(291), M = n.n(O), E = n(230), A = n.n(E), j = n(292), F = n.n(j), L = n(293), R = n.n(L), N = n(294), H = n.n(N), W = n(295), V = n.n(W), z = n(296), I = n.n(z), _ = n(297), G = n.n(_), U = n(298), X = n.n(U), Y = n(299), K = n.n(Y), J = n(300), q = n.n(J), Z = {
        "2/5 Interleaved": {
            defaultWidth: 80,
            defaultHeight: 94.5,
            canBeRenderedViaJs: !1,
            isTwoDimensional: !1,
            path: b.a
        },
        "2/5 Industrial": {
            defaultWidth: 155,
            defaultHeight: 94.5,
            canBeRenderedViaJs: !1,
            isTwoDimensional: !1,
            path: g.a
        },
        "2/5 Matrix": {
            defaultWidth: 115,
            defaultHeight: 94.5,
            canBeRenderedViaJs: !1,
            isTwoDimensional: !1,
            path: y.a
        },
        Codabar: {
            defaultWidth: 126,
            defaultHeight: 94.5,
            canBeRenderedViaJs: !1,
            isTwoDimensional: !1,
            path: S.a
        },
        Code128: {
            defaultWidth: 98.6,
            defaultHeight: 94.5,
            canBeRenderedViaJs: !1,
            isTwoDimensional: !1,
            path: w.a
        },
        Code39: {
            defaultWidth: 177.6,
            defaultHeight: 94.5,
            format: "CODE39",
            canBeRenderedViaJs: !0,
            isTwoDimensional: !1
        },
        "Code39 Extended": {
            defaultWidth: 177.6,
            defaultHeight: 94.5,
            canBeRenderedViaJs: !1,
            isTwoDimensional: !1,
            path: B.a
        },
        Code93: {
            defaultWidth: 136.06,
            defaultHeight: 94.5,
            canBeRenderedViaJs: !1,
            isTwoDimensional: !1,
            path: $.a
        },
        "Code93 Extended": {
            defaultWidth: 136.06,
            defaultHeight: 94.5,
            canBeRenderedViaJs: !1,
            isTwoDimensional: !1,
            path: D.a
        },
        EAN8: {
            defaultWidth: 83.9,
            defaultHeight: 94.5,
            format: "EAN8",
            canBeRenderedViaJs: !0,
            isTwoDimensional: !1
        },
        EAN13: {
            defaultWidth: 128.8,
            defaultHeight: 94.5,
            format: "EAN13",
            canBeRenderedViaJs: !0,
            isTwoDimensional: !1
        },
        MSI: {
            defaultWidth: 143.6,
            defaultHeight: 94.5,
            format: "MSI",
            canBeRenderedViaJs: !0,
            isTwoDimensional: !1
        },
        PostNet: {
            defaultWidth: 154.9,
            defaultHeight: 94.5,
            canBeRenderedViaJs: !1,
            isTwoDimensional: !1,
            path: M.a
        },
        "UPC-A": {
            defaultWidth: 138.7,
            defaultHeight: 94.5,
            format: "UPC",
            canBeRenderedViaJs: !0,
            isTwoDimensional: !1
        },
        "UPC-E0": {
            defaultWidth: 83.9,
            defaultHeight: 94.5,
            canBeRenderedViaJs: !1,
            isTwoDimensional: !1,
            path: A.a
        },
        "UPC-E1": {
            defaultWidth: 83.9,
            defaultHeight: 94.5,
            canBeRenderedViaJs: !1,
            isTwoDimensional: !1,
            path: A.a
        },
        "Supplement 2": {
            defaultWidth: 24.9,
            defaultHeight: 94.5,
            canBeRenderedViaJs: !1,
            isTwoDimensional: !1,
            path: F.a
        },
        "Supplement 5": {
            defaultWidth: 58.5,
            defaultHeight: 94.5,
            canBeRenderedViaJs: !1,
            isTwoDimensional: !1,
            path: R.a
        },
        PDF417: {
            defaultWidth: 171.9,
            defaultHeight: 122.07,
            canBeRenderedViaJs: !1,
            isTwoDimensional: !0,
            path: H.a
        },
        Datamatrix: {
            defaultWidth: 35.9,
            defaultHeight: 54.04,
            canBeRenderedViaJs: !1,
            isTwoDimensional: !0,
            path: V.a
        },
        "QR Code": {
            defaultWidth: 116.03,
            defaultHeight: 133.79,
            canBeRenderedViaJs: !1,
            isTwoDimensional: !0,
            path: h.a
        },
        Plessey: {
            defaultWidth: 183.68,
            defaultHeight: 94.5,
            canBeRenderedViaJs: !1,
            isTwoDimensional: !1,
            path: I.a
        },
        "GS1-128 (UCC/EAN-128)": {
            defaultWidth: 98.64,
            defaultHeight: 94.5,
            canBeRenderedViaJs: !1,
            isTwoDimensional: !1,
            path: G.a
        },
        Aztec: {
            defaultWidth: 60.09,
            defaultHeight: 77.85,
            canBeRenderedViaJs: !1,
            isTwoDimensional: !0,
            path: X.a
        },
        Pharmacode: {
            defaultWidth: 148.9,
            defaultHeight: 94.5,
            format: "pharmacode",
            canBeRenderedViaJs: !0,
            isTwoDimensional: !1
        },
        MaxiCode: {
            defaultWidth: 181.79,
            defaultHeight: 193.5,
            canBeRenderedViaJs: !1,
            isTwoDimensional: !0,
            path: K.a
        },
        "Intelligent Mail (USPS)": {
            defaultWidth: 94.5,
            defaultHeight: 94.5,
            canBeRenderedViaJs: !1,
            isTwoDimensional: !1,
            path: q.a
        }
    }, Q = new s.a("Barcode"), et.prototype.init = function() {
        var t = this.self,
            e = this.props.defaultWidth || 0,
            n = this.props.defaultHeight || 0;
        0 < e && t.prop("Width", e), 0 < n && t.prop("Height", n)
    }, et.prototype.isExist = function() {
        return Boolean(this.props)
    }, et.prototype.getType = function() {
        return this.barcodeType
    }, et.prototype.updateAutoSizeWidth = function() {
        var t, e, n = this.self;
        n.$preview && (t = n.prop("Width") || 0, e = n.prop("Height") || 0, 0 < t && n.prop("Width", t), 0 < e && n.prop("Height", e))
    }, et.prototype.updateAutoSizeProp = function() {
        var t = this.self;
        t.prop("AutoSize") ? (this.updateAutoSizeWidth(), t.attr({
            resizableX: !1,
            resizableY: !this.props.isTwoDimensional,
            resizableXY: !1
        })) : t.attr({
            resizableX: !0,
            resizableY: !0,
            resizableXY: !0
        })
    }, et.prototype.render = function() {
        var t = this.self;
        return t.$preview || (t.$preview = i()(Object(l.a)("svg", {
            class: "move"
        }))), this.props.canBeRenderedViaJs ? (t.$image && (t.$preview.empty(), delete t.$image), this.renderJS()) : (t.$image || (t.$preview.css("overflow", "visible"), t.$preview.empty(), t.$image = i()(Object(l.a)("image", {
            class: "move"
        })), t.$preview.append(t.$image)), this.renderPreview()), this.updateAutoSizeProp(), t.$preview
    }, et.prototype.renderPreview = function() {
        Object(p.a)(this.self.$image.get(0), "href", this.props.path), this.self.$image.css({
            width: this.self.prop("Width"),
            height: this.self.prop("Height")
        })
    }, et.prototype.renderJS = function() {
        var e = this.self;
        window.JsBarcode(e.$preview.get(0), e.prop("Text"), {
            format: this.props.format,
            width: e.prop("Barcode.WideBarRatio") || 1,
            height: e.prop("Height") - 30,
            lineColor: e.prop("Barcode.Color") || "#000",
            displayValue: e.prop("ShowText"),
            margin: 0,
            valid: function(t) {
                return t || e.notifyInvalidCode(), !0
            }
        })
    }, tt = et, e.default = r.default.createObject(r.default, {
        title: "Objects BarcodeObject",
        info: "BarcodeObjectInfo",
        icon: "icon-123",
        pos: 80,
        type: "BarcodeObject",
        defaultWidth: 160.6,
        defaultHeight: 94.5,
        disabled: !1,
        _init: function() {
            r.default._init.apply(this, arguments), this.defaultValues = {
                AutoSize: !0,
                HideIfNoData: !0,
                ShowText: !0,
                Zoom: 1,
                Text: "12345678",
                "Barcode.Color": "#000000"
            }, this.prop({
                Name: "Barcode",
                Barcode: "Code39",
                Width: this.defaultWidth,
                Height: this.defaultHeight,
                AsBitmap: !1
            }), this.attr({
                withPadding: !0,
                lastWidth: this.defaultWidth,
                bufZoom: 1
            })
        },
        notifyInvalidCode: Object(d.a)(function() {
            Object(o.a)('Text "{0}" is invalid for "{1}" barcode type'.format(this.prop("Text"), this.prop("Barcode")), {
                danger: !0
            })
        }, 300),
        render: function() {
            var t, e, n = this.prop("Barcode");
            if (!this.currentBarcode || this.currentBarcode.getType() !== n) {
                if (this.currentBarcode = new tt(this, n), !this.currentBarcode.isExist()) return delete this.currentBarcode, r.default.render.apply(this, arguments);
                this.currentBarcode.init(), this.$previewContainer || (this.$previewContainer = i()(Object(l.a)("g", {
                    transform: "scale(1)"
                })))
            }
            return t = this.currentBarcode.render(), r.default.render.apply(this, arguments), e = this.attr("lastWidth"), e = this.prop("Zoom") > this.bufZoom ? this.attr("lastWidth") * (this.prop("Barcode.WideBarRatio") || 1) * this.prop("Zoom") : this.prop("Zoom") < this.bufZoom ? this.attr("lastWidth") * (this.prop("Barcode.WideBarRatio") || 1) / this.bufZoom : this.prop("AutoSize") ? this.attr("lastWidth") * (this.prop("Barcode.WideBarRatio") || 1) : this.prop("Width"), Object(c.a)(this.$previewContainer[0], "transform", "scale(" + this.prop("Zoom") + ", 1)"), this.prop("Width", e), this.attr("lastWidth", this.prop("Width") / (this.prop("Barcode.WideBarRatio") || 1)), this.bufZoom = this.prop("Zoom"), this.$previewContainer.append(t), this.$contentGroup.append(this.$previewContainer), this.syncUpControls(), this.$g
        }
    })
}, function(t, e, n) {
    "use strict";

    function a(t) {
        var e, n, a, i, r, o, s, l, c, p = void 0;
        return !/[^\d.]/i.test(t) && 0 < (p = new Date(parseFloat(t))).getTime() ? p : (p = Date.parse(t)) ? p : (n = (e = t.split(" "))[0].split("/"), a = e[1].split(":"), i = +n[0], r = n[1] - 1, o = +n[2], s = +a[0], l = +a[1], c = +a[2], new Date(o, r, i, s, l, c))
    }

    function i(t) {
        return t && t instanceof Date ? t.getDate() + "/" + (t.getMonth() + 1) + "/" + t.getFullYear() + " " + t.getHours() + ":" + t.getMinutes() + ":" + t.getSeconds() : ""
    }
    n.d(e, "b", function() {
        return a
    }), n.d(e, "a", function() {
        return i
    })
}, function(t, e, n) {
    "use strict";
    e.a = {
        fontStyleToNet: function(t) {
            switch (t) {
                case 1:
                    return ["Bold"];
                case 2:
                    return ["Italic"];
                case 3:
                    return ["Bold", "Italic"];
                case 4:
                    return ["Underline"];
                case 5:
                    return ["Bold", "Underline"];
                case 6:
                    return ["Italic", "Underline"];
                case 7:
                    return ["Bold", "Italic", "Underline"];
                case 8:
                    return ["Strikeout"];
                case 9:
                    return ["Bold", "Strikeout"];
                case 10:
                    return ["Italic", "Strikeout"];
                case 11:
                    return ["Bold", "Italic", "Strikeout"];
                case 12:
                    return ["Underline", "Strikeout"];
                case 13:
                    return ["Bold", "Underline", "Strikeout"];
                case 14:
                    return ["Italic", "Underline", "Strikeout"];
                case 15:
                    return ["Bold", "Italic", "Underline", "Strikeout"];
                default:
                    return []
            }
        },
        borderLinesToNET: function(t) {
            switch (t) {
                case 1:
                    return "Left";
                case 2:
                    return "Right";
                case 3:
                    return "Left, Right";
                case 4:
                    return "Top";
                case 5:
                    return "Top, Left";
                case 6:
                    return "Top, Right";
                case 7:
                    return "Top, Left, Right";
                case 8:
                    return "Bottom";
                case 9:
                    return "Bottom, Left";
                case 10:
                    return "Bottom, Right";
                case 11:
                    return "Bottom, Left, Right";
                case 12:
                    return "Top, Bottom";
                case 13:
                    return "Top, Bottom, Left";
                case 14:
                    return "Top, Bottom, Right";
                case 15:
                    return "All"
            }
        },
        borderStyleToNET: function(t) {
            switch (t) {
                case "fsDot":
                    return "Dot";
                case "fsDash":
                    return "Dash";
                case "fsDashDot":
                    return "DashDot";
                case "fsDashDotDot":
                    return "DashDotDot";
                default:
                    return "Solid"
            }
        }
    }
}, function(t, e, n) {
    "use strict";
    var a = n(4);
    e.a = function(t) {
        return t = t && a.a.get("brackets").replace(",", t)
    }
}, function(t, e, n) {
    "use strict";
    var a = n(1),
        i = n(77),
        r = n(29),
        o = n(24),
        s = n(87),
        l = n(32),
        c = n(7),
        p = n(17);
    e.a = Object(c.a)(i.a, {
        type: "Column",
        _init: function() {
            this._id = "tdscol" + Object(p.a)(), this.columns = o.a.create(this), this.dataSources = s.a.create(this), this.defaultValues = {
                Calculated: !1,
                BindableControl: "Text"
            }, this.prop({
                Name: "Column",
                Alias: "",
                DataType: r.a.get("System.Int32"),
                Expression: ""
            }), this.updateBindableControl()
        },
        fillMap: function() {
            this.fieldMap = l.a.factory(["Data:DataType", {
                prop: "Design:Name",
                afterSetValue: function() {
                    a.a.trigger("update-data-panel")
                }
            }, "Design:Alias", "Design:Restrictions"]), this.mainFields = [
                ["Design:Name", "Data:DataType"]
            ]
        },
        isColumn: function() {
            return !0
        }
    })
}, function(t, e, n) {
    "use strict";
    var a = n(1),
        i = n(184),
        r = n(77),
        o = n(29),
        s = n(32),
        l = n(7),
        c = n(17);
    e.a = Object(l.a)(r.a, {
        type: "Parameter",
        _init: function() {
            this._id = "p" + Object(c.a)(), this.parameters = i.a.create(this), this.editable = [function(t) {
                a.a.trigger("create-parameter", t)
            }, function(t) {
                a.a.trigger("remove-parameter", t)
            }], this.defaultValues = {
                Expression: ""
            }, this.prop({
                Name: "Parameter",
                DataType: o.a.get("System.String")
            }), this.bindableControl = "TextObject"
        },
        fillMap: function() {
            this.fieldMap = s.a.factory(["Data:DataType", {
                prop: "Design:Name",
                afterSetValue: function() {
                    a.a.trigger("update-data-panel")
                }
            }]), this.mainFields = [
                ["Design:Name"],
                ["Data:DataType"]
            ]
        },
        isParameter: function() {
            return !0
        }
    })
}, function(t, e, n) {
    "use strict";

    function a() {
        return new Promise(function(e) {
            n.e(11).then(n.bind(null, 578)).then(function(t) {
                return e(t.expression)
            })
        })
    }
    var i;
    n.d(e, "a", function() {
        return a
    }), i = n(366)
}, function(t, e, n) {
    "use strict";

    function o(e, t) {
        var n = s()('<input type="button" value="..."/>');
        return t && t.addClass("d-fc-exp-input"), n.addClass("d-fc-exp-but"), n.on("click", function(t) {
            t.preventDefault(), e.expressionEventName = e.expressionEventName || "show-expression-editor", "show-expression-editor" === e.expressionEventName ? c.a.trigger(e.expressionEventName, {
                entity: e.element,
                prop: e.prop,
                menu: void 0 === e.exprMenu || e.exprMenu
            }) : c.a.trigger(e.expressionEventName, e.element, e.prop)
        }), n
    }

    function a(t) {
        var e, n = E[t.type],
            a = s()("<div>"),
            i = s()("<label>"),
            r = s()("<div>");
        return i.html(l.a.tr(t.label)), a.append(i, r), e = n.build(t, i, r), r.children().length || r.append(e.$control), e.$control.data("control", e), e.$control.addClass("d-fc"), r.addClass("fg-body"), a.addClass("fg"),
            function(t, e) {
                var n, a, i, r;
                if (!t) return;
                for (n = (r = Object.keys(t)).length; n--;) i = t[a = r[n]], e.attr(a, "function" == typeof i ? i() : i)
            }(t.attrs, e.$control), t.expression && r.append(o(t, e.$control)), {
                self: e,
                $main: a,
                $body: r,
                $label: i
            }
    }
    var i, r = n(0),
        s = n.n(r),
        l = n(2),
        c = n(1),
        p = n(7),
        d = Object(p.a)(null, {
            build: function(t, e, n) {
                var a = Object(p.a)(this);
                return a.field = t, a.$label = e, a.$body = n, a.create.apply(a, arguments), a
            },
            isValid: function() {
                return !0
            },
            getValue: function(t) {
                return t = t || this.$control, Number(t.val()) || t.val()
            },
            getProp: function() {
                return this.field.prop
            }
        }),
        u = Object(p.a)(d, {
            create: function(t) {
                var n, a, i, r = this,
                    e = t.collection,
                    o = t.value && t.value.label || t.value;
                this.$control = s()("<select>"), "function" == typeof e && (e = e.call(t.element, t)), Array.isArray(e) || s.a.isPlainObject(e) || (e = []), t.multiple && (o && "string" == typeof o && (o = o.split(",")), this.$control.attr("multiple", !0)), i = a = n = void 0, s.a.each(e, function(t, e) {
                    i = "string" == typeof t ? a = t : "string" == typeof e ? a = e : "string" == typeof e.key && "string" == typeof e.value ? (a = e.key, e.value) : a = (a = e).label || a, (n = s()("<option>")).val(a), n.text(i), a.toString() !== o && -1 === (o || []).indexOf(a) || n.attr("selected", !0), r.$control.append(n)
                })
            },
            getValue: function(t) {
                var e = [];
                return t = t || this.$control, s()("option:selected", t).each(function() {
                    e.push(s()(this).val())
                }), 1 === e.length ? e[0] : e
            }
        }),
        h = Object(p.a)(d, {
            create: function(t) {
                this.$control = s()("<textarea>"), this.$control.val(t.value), this.$control.attr("type", t.type), t.readonly && this.$control.attr("readonly", !0), this.$control.val(t.value || "")
            },
            getValue: function(t) {
                var e;
                return e = (e = (t = t || this.$control).val()) && e.toString && e.toString()
            }
        }),
        f = Object(p.a)(d, {
            create: function(t) {
                this.$control = s()("<input/>"), this.$control.attr({
                    type: "checkbox",
                    checked: t.value
                })
            },
            getValue: function(t) {
                return (t = t || this.$control).prop("checked")
            }
        }),
        g = n(14),
        m = n(88),
        b = function(t) {
            return t === +t && t === (0 | t)
        },
        v = Object(p.a)(d, {
            create: function(t) {
                this.$control = s()("<input/>"), this.$control.attr("type", "number"), this.$control.val(g.a.toUnit(t.value) || t.defaultValue)
            },
            isValid: function(t) {
                return Object(m.a)(t) || b(t)
            },
            getValue: function(t, e) {
                var n;
                return t = t || this.$control, n = parseFloat(g.a.toPx(t.val()), 10), e && (n = g.a.toUnit(n)), n
            }
        }),
        y = (n(345), n(244)),
        C = Object(p.a)(d, {
            create: function(t) {
                i = s.a.Deferred(), this.$control = s()("<input/>"), this.$control.attr("type", t.type), this.$control.on("change", function() {
                    Object(y.a)(this, function(t) {
                        i.resolve(t.target.result)
                    })
                })
            },
            getValue: function() {
                return i.promise()
            }
        }),
        S = Object(p.a)(d, {
            create: function(t, e, n) {
                this.$control = s()("<input/>"), this.$control.attr("type", "text"), this.$control.val(t.value), this.$control.css({
                    width: "80%",
                    display: "inline-block"
                }), this.$control.addClass("d-fc-exp-input"), n.append(this.$control);
                var a = s()("<input/>");
                a.attr("type", "button"), a.addClass("d-fc-exp-but"), a.attr("tabindex", "-1"), a.val("..."), a.on("click", function() {
                    c.a.trigger("procedure", t.element, t)
                }), n.append(a)
            }
        }),
        x = (n(346), n(348), n(349), Object(p.a)(d, {
            create: function(t) {
                var e = t.value;
                this.$control = s()("<input/>"), this.$control.datepicker(), this.$control.datepicker("setDate", e)
            },
            getValue: function(t) {
                var e;
                return e = (t = t || this.$control).val(), new Date(e)
            }
        })),
        w = n(62),
        T = n(165),
        B = (n(234), n(235), Object(p.a)(d, {
            create: function(t, e, n) {
                this.$control = s()("<input/>"), this.$control.attr("type", t.type), Object(T.a)("color") ? this.$control.val(Object(w.a)(t.value)) : (n.append(this.$control), this.$control.spectrum({
                    color: Object(w.a)(t.value),
                    preferredFormat: "hex",
                    change: function() {
                        s()(this).trigger("change keyup")
                    }
                }))
            },
            isValid: function(t) {
                return !!/#[0-9a-f]{6}|#[0-9a-f]{3}/gi.test(t)
            }
        })),
        k = Object(p.a)(d, {
            create: function(t) {
                this.$control = s()("<input/>"), this.$control.attr("type", t.type), this.$control.val(t.value)
            }
        }),
        $ = Object(p.a)(d, {
            create: function(t) {
                this.$control = s()("<input/>"), this.$control.attr("type", t.type), t.readonly && this.$control.attr("readonly", !0), this.$control.val(t.value)
            },
            getValue: function(t) {
                var e;
                return e = (e = (t = t || this.$control).val()) && e.toString && e.toString()
            }
        }),
        P = n(43),
        D = Object(p.a)(d, {
            create: function(t) {
                this.$control = s()("<input/>"), this.$control.attr("type", t.type), this.$control.val(t.value)
            },
            isValid: function(t) {
                return Object(m.a)(t) || b(t)
            },
            getValue: function(t) {
                var e = (t = t || this.$control).val();
                return Object(P.a)(e) && (e = +e), Object(m.a)(e) ? parseFloat(e, 10) : parseInt(e, 10)
            }
        }),
        O = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function(t) {
            return typeof t
        } : function(t) {
            return t && "function" == typeof Symbol && t.constructor === Symbol && t !== Symbol.prototype ? "symbol" : typeof t
        },
        M = Object(p.a)(d, {
            create: function(e) {
                var n, a, i;
                if ("object" !== O(e.value)) return (n = s()('<textarea class="d-fc js-subcontrol-value">')).val(e.value), void(this.$control = n);
                n = s()('<div class="d-fc-json-field">'), Object.keys(e.value).forEach(function(t) {
                    "string" == typeof(i = e.value[t]) || "number" == typeof i || "boolean" == typeof i || null === i ? (a = s()('<input class="d-fc js-subcontrol-value"/>'), "boolean" == typeof i ? a.attr({
                        type: "checkbox",
                        checked: i
                    }) : (a.attr("type", "text"), a.addClass("d-fc--with-border"), a.val(null === i ? "" : i)), a.data("default-value", i)) : (a = s()('<input type="text" class="d-fc js-subcontrol-value">')).val(JSON.stringify(i)), n.append(s()('<label class="d-fc-json-subcontrol js-subcontrol"><span class="js-subcontrol-key">' + t + "</span></label>").append(a)), n.append("<hr/>")
                }), this.$control = n
            },
            getValue: function(t) {
                var e, n, a, i, r;
                return (t = t || this.$control).is(".d-fc-json-field") ? (e = {}, r = i = a = n = void 0, t.find(".js-subcontrol").each(function() {
                    n = s()(this), a = n.find(".js-subcontrol-value"), i = n.find(".js-subcontrol-key").text(), a.is('[type="checkbox"]') ? e[i] = a.prop("checked") : (r = a.val()) || null !== a.data("default-value") ? e[i] = r : e[i] = null
                }), e) : t.val()
            }
        }),
        E = {
            select: u,
            textarea: h,
            checkbox: f,
            unit: v,
            file: C,
            event: S,
            datetime: x,
            color: B,
            url: k,
            text: $,
            number: D,
            json: M
        };
    e.a = {
        getFor: function(t) {
            return a(t)
        },
        createExpression: o
    }
}, function(t, e, n) {
    "use strict";

    function a() {
        return new Promise(function(t) {
            var e = o()('<div class="fr-lock_window fr-popup-container"></div>');
            return e.on("popup-closed", function() {
                e.children().length || e.remove()
            }), t(e)
        })
    }
    var i, r, o;
    n.d(e, "a", function() {
        return a
    }), i = n(377), r = n(0), o = n.n(r)
}, , , , function(t, e, n) {
    "use strict";

    function a(t) {
        var e = a.cache[t];
        return e || ((e = document.createElement("input")).setAttribute("type", t), e = e.type, a.cache[t] = e), e === t
    }
    a.cache = {}, e.a = a
}, , , , , , , , , , , , function(t, e, n) {
    "use strict";
    n.r(e);
    var a = n(10);
    e.default = a.default.createObject(a.default, {
        title: "Bands GroupFooter",
        info: "GroupFooterBandInfo",
        icon: "icon-164",
        pos: 110,
        type: "GroupFooterBand",
        disabled: !1,
        _init: function() {
            a.default._init.call(this), this.attr({
                pos: 36,
                layer_defect: -1
            }), this.prop("Name", "GroupFooter")
        },
        getFillTitleColor: function() {
            return "#01DF3A"
        }
    })
}, function(t, e, n) {
    "use strict";

    function a(t) {
        var e, n;
        if ("string" == typeof t) {
            if ("true" === (e = t.toLowerCase())) return !0;
            if ("false" === e) return !1;
            if ("null" === e) return null;
            if ("number" == typeof(n = Object(i.a)(t))) return n
        }
        return t
    }
    n.d(e, "a", function() {
        return a
    });
    var i = n(142)
}, function(t, e, n) {
    "use strict";

    function a(t, e, n) {
        var a = t.slice((n || e) + 1 || t.length);
        return t.length = e < 0 ? t.length + e : e, t.push.apply(t, a)
    }
    n.d(e, "a", function() {
        return a
    })
}, function(t, e, n) {
    "use strict";
    var o = n(4),
        a = n(24),
        i = n(7),
        p = n(186),
        c = n(84),
        r = n(28);
    e.a = Object(i.a)(a.a, {
        initMainCollection: function(t) {
            return this.mainCollection = t, this
        },
        everyEntity: function(t) {
            var e, n = this.all();
            for (e = 0; e < n.length; e += 1)
                if (!1 === t.call(this, n[e], e) || n[e].bands.count() && !1 === n[e].bands.everyEntity(t)) return !1;
            return this
        },
        everyOwnEntity: function(t) {
            var e, n = this.all();
            for (e = 0; e < n.length; e++)
                if (!1 === t.call(this, n[e], e)) return !1;
            return this
        },
        everyBand: function(t) {
            var e, n, a, i = this.getTopBands();
            for (e = 0; e < i.length; e++)
                if (!1 === i[e].bands.everyBand(t)) return !1;
            if (this.container.isBand() && !1 === t(this.container)) return !1;
            for (n = this.getBottomBands(), a = 0; a < n.length; a++)
                if (!1 === n[a].bands.everyBand(t)) return !1
        },
        getAllBandsHeight: function() {
            return this.getTopBandsHeight() + this.getBottomBandsHeight()
        },
        getTopBandsHeight: function() {
            var t, e, n = this.getTopBands(),
                a = n.length,
                i = 0,
                r = 0;
            for (o.a.get("resize-bands") && (r = o.a.get("band-indent-top") || 0), t = 0; t < a; t++) i += (e = n[t]).prop("Height") + r + e.bands.getAllBandsHeight();
            return i
        },
        getBottomBandsHeight: function() {
            var t, e, n = this.getBottomBands(),
                a = n.length,
                i = 0,
                r = 0;
            for (o.a.get("resize-bands") && (r = o.a.get("band-indent-top") || 0), t = 0; t < a; t++) i += (e = n[t]).prop("Height") + r + e.bands.getAllBandsHeight();
            return i
        },
        getHeightTo: function(e, t, n) {
            var a = 0,
                i = 0,
                r = 0;
            return n = n || this.mainCollection, o.a.get("resize-bands") && (i = o.a.get("band-indent-top") || 0), n.everyBand(function(t) {
                if (e === t) return r = a, !1;
                a += t.prop("Height") + i
            }), (t || 0) + r
        },
        updateThreshold: function() {
            return this.mainCollection.everyEntity(function(t) {
                t.updateThreshold()
            }), this
        },
        componentsIn: function(n, a) {
            var i = [],
                r = void 0,
                o = void 0,
                s = void 0,
                l = void 0,
                c = void 0;
            return this.everyEntity(function(e) {
                e.components.eachEntity(function(t) {
                    t.attr("selectable") && (r = e.prop("Top"), o = t.prop("Left"), s = t.attr("right"), l = t.prop("Top") + r, c = t.attr("bottom") + r, Object(p.a)(n, a, s < o ? [s, o] : [o, s], c < l ? [c, l] : [l, c]) && i.push(t))
                })
            }), i
        },
        add: function(t, e) {
            var n, a, i, r, o, s, l;
            for (t.collection && t.collection.remove(t), t.collection = this, -1 < e ? this.entities.splice(e, 0, t) : this.entities.push(t), Object(c.a)(this.entities), n = [], a = [], o = r = i = 0; o < this.entities.length; o++)(s = this.entities[o]) && (!0 === s.attr("placeAboveParent") ? (n.push({
                entity: s,
                inx: i
            }), i += 1) : (a.push({
                entity: s,
                inx: r
            }), r += 1));
            return l = function(t, e) {
                var n = t.entity.attr("pos"),
                    a = e.entity.attr("pos");
                return n < a ? -1 : a < n ? 1 : t.inx < e.inx ? -1 : t.inx > e.inx ? 1 : 0
            }, n.sort(l), a.sort(l), n = n.map(function(t) {
                return t.entity
            }), a = a.map(function(t) {
                return t.entity
            }), this.entities = n.concat(a), this
        },
        getTopBands: function() {
            var e = [];
            return this.everyOwnEntity(function(t) {
                t && !0 === t.attr("placeAboveParent") && e.push(t)
            }), e
        },
        getBottomBands: function() {
            var e = [];
            return this.everyOwnEntity(function(t) {
                t && !0 !== t.attr("placeAboveParent") && e.push(t)
            }), e
        },
        findInsideCoord: function(t) {
            var e = null,
                n = t[0],
                a = t[1];
            return this.everyBand(function(t) {
                n >= t.prop("Left") && a >= t.prop("Top") && n <= t.prop("Left") + t.prop("Width") && a <= t.prop("Top") + t.prop("Height") && (e = t)
            }), e
        },
        remove: function(t, e) {
            if (t) return (!(1 < arguments.length && void 0 !== e) || e) && t.bands.entities.forEach(function(t) {
                t.bands.remove(t)
            }), Object(r.a)(this.entities, t), this
        },
        clear: function() {
            return this.everyEntity(function(t) {
                return t.$g.remove()
            }), this.entities.length = 0, this
        }
    })
}, function(t, e, n) {
    "use strict";
    n(306);
    var m, a = n(0),
        b = n.n(a),
        v = n(1);
    b()(document.body).on("keydown", function() {
        "function" == typeof m && m.apply(this, arguments)
    }), e.a = function r(t, e, n, a) {
        var i, o, s, l, c, p, d, u = b()("<div>"),
            h = document.body,
            f = b()(h),
            g = window.DSG.head.$main[0].getBoundingClientRect();
        if (!n || !n.length) return null;
        for (d = n.length, u.addClass("d-cm"), m = function(t) {
                27 === t.keyCode && u.remove()
            }, o = t.pageX - g.left - h.scrollLeft, i = t.pageY - g.top - h.scrollTop, u.css({
                left: o,
                top: i
            }), e && ((l = b()("<div>")).addClass("d-cm-item d-cm-title"), l.text(e), u.append(l)), c = 0; c < d; c += 1) "separator" !== (p = n[c]).type ? ((s = b()("<div>")).addClass("d-cm-item"), s.addClass("d-cm-" + p.type), p.curVal && s.addClass("d-cm-checked"), p.disabled && s.addClass("disabled"), s.append('<span class="d-cm-text">' + p.name + "</span>"), s.data("item", p), p.shortcut && s.append('<span class="d-cm-shortcut">' + p.shortcut + "</span>"), u.append(s)) : u.append('<hr class="d-cm-separator"/>');
        return window.DSG.head.put(u), f.height() < u.height() + i && u.css("top", f.height() - u.height() - 5), f.width() < u.width() + o && u.css("left", f.width() - u.width() - 5), u.on(a || "mousedown", ".d-cm-item", function(t) {
            var e, n, a = b()(this),
                i = a.data("item");
            return i && a.is(":not(.disabled)") && (i.closeAfter && v.a.trigger("remove-context-menus"), "checkbox" === i.type ? (i.curVal = !i.curVal, a[i.curVal ? "addClass" : "removeClass"]("d-cm-checked"), i.onChange && i.onChange(i)) : "list" === i.type ? (e = a.data("cm")) ? (e.remove(), a.data("cm", null)) : (e = r(t, null, "function" == typeof i.items ? i.items(i) : i.items), n = a[0].getBoundingClientRect(), e.css({
                left: n.left + n.width,
                top: n.top
            }), a.data("cm", e), u.after(e)) : "default" === i.type && i.onClick && i.onClick(i)), !1
        }), u
    }
}, function(t, e, n) {
    "use strict";
    n(307);
    var a = n(0),
        i = n.n(a);
    e.a = function() {
        var t = i()("<div>"),
            e = i()("<span>");
        return t.addClass("d-tooltip"), t.append(e), t
    }
}, function(t, e, n) {
    "use strict";
    var a = n(0),
        r = n.n(a),
        o = n(13),
        i = n(236),
        s = n(24),
        l = n(7),
        c = n(17);
    e.a = Object(l.a)(o.a, {
        type: "Highlight",
        _init: function(t) {
            this._id = t || "hgh" + Object(c.a)(), this.conditions = s.a.create(this)
        },
        create: function() {
            var t = this.createObject(this);
            return t._init.apply(t, arguments), t
        },
        clone: function() {
            var e = o.a.clone.apply(this, arguments);
            return this.conditions.eachEntity(function(t) {
                return e.conditions.add(t.clone())
            }), e
        },
        fillPropsNET: function(t) {
            o.a.fillPropsNET.apply(this, arguments);
            var e, n = t.find("> Condition"),
                a = this;
            return n.length && r.a.each(n, function() {
                (e = i.a.create()).fillPropsNET(r()(this)), a.conditions.add(e)
            }), this
        },
        toXMLNET: function(i) {
            var t = this;
            return new Promise(function(a) {
                o.a.toXMLNET.call(t, i).then(function(e) {
                    e = r()(e), i = Object.assign({
                        parentNode: e[0]
                    }, i);
                    var n = [];
                    t.conditions && t.conditions.eachEntity(function(t) {
                        n.push(t.toXMLNET(i))
                    }), Promise.all(n).then(function(t) {
                        return t.forEach(function(t) {
                            e.append(t)
                        }), a(e[0])
                    })
                })
            })
        }
    })
}, function(t, e, n) {
    "use strict";
    var a = n(0),
        i = n.n(a),
        r = n(24),
        o = n(7),
        s = n(28);
    e.a = Object(o.a)(r.a, {
        everyEntity: function(t) {
            for (var e = this.all(), n = e.length, a = 0; a < n; a += 1)
                if (!1 === t.call(this, e[a], a) || e[a].parameters.count() && !1 === e[a].parameters.everyEntity(t)) return !1;
            return this
        },
        findBy: function(e) {
            var n, a, i, r = [],
                o = Object.keys(e),
                s = o.length;
            return this.everyEntity(function(t) {
                for (n = s; n--;)
                    if (a = o[n], i = e[a], t[a] !== i && t.attr(a) !== i) return;
                r.push(t)
            }), r
        },
        remove: function(t) {
            return !!t && (i.a.each(t.parameters.entities, function() {
                this.parameters.remove(this)
            }), Object(s.a)(this.entities, t), this)
        }
    })
}, function(t, e, n) {
    "use strict";

    function a(t) {
        return t.replace(/^[\s\S]*?(<Report |<inherited |<TfrxReport |<ReportFunctions>)/, function(t, e) {
            return e
        })
    }
    n.d(e, "a", function() {
        return a
    })
}, function(t, e, n) {
    "use strict";
    var i = function(t, e) {
            return 0 < Math.max(0, Math.min(t[1], e[1]) - Math.max(t[0], e[0]))
        },
        r = function(t, e) {
            return 0 < Math.max(0, Math.min(t[1], e[1]) - Math.max(t[0], e[0]))
        };
    e.a = function(t, e, n, a) {
        return i(t, n) && r(e, a)
    }
}, , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , function(t, e, n) {
    "use strict";
    n.r(e);
    var a = n(10);
    e.default = a.default.createObject(a.default, {
        title: "Bands ReportSummary",
        info: "ReportSummaryBandInfo",
        icon: "icon-155",
        pos: 20,
        type: "ReportSummaryBand",
        disabled: !1,
        _init: function() {
            a.default._init.call(this), this.prop("Name", "ReportSummary"), this.attr("pos", 60)
        }
    })
}, function(t, e, n) {
    "use strict";
    n.r(e);
    var a = n(10);
    e.default = a.default.createObject(a.default, {
        title: "Bands ColumnHeader",
        info: "ColumnHeaderBandInfo",
        icon: "icon-158",
        pos: 50,
        type: "ColumnHeaderBand",
        disabled: !1,
        _init: function() {
            a.default._init.call(this), this.prop("Name", "ColumnHeader"), this.attr("pos", 30)
        }
    })
}, function(t, e, n) {
    "use strict";
    n.r(e);
    var a = n(10);
    e.default = a.default.createObject(a.default, {
        title: "Bands ColumnFooter",
        info: "ColumnFooterBandInfo",
        icon: "icon-159",
        pos: 60,
        type: "ColumnFooterBand",
        disabled: !1,
        _init: function() {
            a.default._init.call(this), this.prop("Name", "ColumnFooter"), this.attr("pos", 50)
        }
    })
}, function(t, e, n) {
    "use strict";
    n.r(e);
    var a = n(10);
    e.default = a.default.createObject(a.default, {
        title: "Bands DataHeader",
        info: "DataHeaderBandInfo",
        icon: "icon-160",
        pos: 70,
        type: "DataHeaderBand",
        disabled: !1,
        _init: function() {
            a.default._init.call(this), this.defaultValues = {
                SortOrder: "Ascending"
            }, this.prop("Name", "DataHeader"), this.attr({
                pos: 34,
                layer_defect: -1,
                placeAboveParent: !0
            })
        },
        getFillTitleColor: function() {
            return this._parent && this._parent.getFillTitleColor()
        }
    })
}, function(t, e, n) {
    "use strict";
    n.r(e);
    var a = n(10);
    e.default = a.default.createObject(a.default, {
        title: "Bands DataFooter",
        info: "DataFooterBandInfo",
        icon: "icon-161",
        pos: 90,
        type: "DataFooterBand",
        disabled: !1,
        _init: function() {
            a.default._init.call(this), this.prop("Name", "DataFooter"), this.attr({
                pos: 37,
                layer_defect: -1
            })
        },
        getFillTitleColor: function() {
            return this._parent && this._parent.getFillTitleColor()
        }
    })
}, function(t, e, n) {
    "use strict";
    n.r(e);
    var a = n(10);
    e.default = a.default.createObject(a.default, {
        title: "Bands Overlay",
        info: "OverlayBandInfo",
        icon: "icon-166",
        pos: 130,
        type: "OverlayBand",
        disabled: !1,
        _init: function() {
            a.default._init.call(this), this.prop("Name", "Overlay"), this.attr("pos", 80)
        }
    })
}, function(t, e, n) {
    "use strict";
    var a, i, c, p, r, o, s;
    n.r(e), a = n(367), i = n(0), c = n.n(i), p = n(4), r = n(1), o = n(7), s = Object(o.a)({
        pos: 7,
        className: "preview",
        cache: {},
        create: function() {
            var e = this;
            return new Promise(function(t) {
                e.$body = c()("<div>"), t(e)
            })
        },
        bindEvents: function() {
            this.$body.on("click", ".d-preview-item-blanket", function(t) {
                return r.a.trigger("activate-page-by-id", c()(t.target).data("page-id")), !1
            }), r.a.bind("re-render", function() {
                s.update()
            })
        },
        update: function() {
            var t, e, n, a, i, r, o, s = window.DSG.currentReport,
                l = s.getCurrentPage();
            if (!this.$body || this.cache === l) return !1;
            for (i = c()("<div>"), n = 0, a = (t = s.pages.all(["ReportPage", "DialogPage"])).length; n < a; n += 1)(e = t[n]) !== l && (r = c()("<div>"), o = c()("<div>"), r.addClass("d-preview-item"), o.addClass("d-preview-item-blanket"), o.data("page-id", e._id), r.css("width", p.a.get("customization:preview:width")), r.append(e.createMini()), r.append(o), i.append(r));
            return this.$body && (this.$body.html(i), this.cache = l), this
        }
    }), e.default = s
}, function(t, e, n) {
    "use strict";
    var i = n(4),
        a = n(187),
        r = n.n(a),
        o = n(23),
        s = new o.a("webfonts"),
        l = {};
    e.a = {
        load: function(n) {
            return new Promise(function(e) {
                var t;
                void 0 === l[n] ? r.a.load(((t = {}).google = {
                    families: [n]
                }, t.active = function() {
                    l[n] = 1, e(1)
                }, t.timeout = 1e3, t.fontinactive = function(t) {
                    l[n] = 0, s.warn(t, "could not be loaded"), e(0)
                }, t)) : e(l[n])
            })
        },
        preload: function() {
            return new Promise(function(n) {
                var t, a = i.a.get("preload-fonts");
                a && a.length ? r.a.load(((t = {}).google = {
                    families: a
                }, t.active = function() {
                    for (var t = 0, e = a.length; t < e; t += 1) l[a[t]] = 1;
                    n()
                }, t.timeout = 2e3, t.inactive = function() {
                    n()
                }, t.fontinactive = function(t) {
                    l[t] = 0, s.warn(t, "could not be loaded")
                }, t)) : n()
            })
        },
        prefetch: function() {
            var t, n = i.a.get("prefetch-fonts");
            n && n.length && r.a.load(((t = {}).google = {
                families: n
            }, t.active = function() {
                for (var t = 0, e = n.length; t < e; t += 1) l[n[t]] = 1
            }, t.fontinactive = function(t) {
                l[t] = 0, s.warn(t, "could not be loaded")
            }, t))
        }
    }
}, function(t, e, n) {
    "use strict";

    function a() {
        this.render(), this.fieldMap.remove(["Appearance:Barcode"]), this.fieldMap.append([this.getBarcodeProps()]), l.a.trigger("update-properties-panel", this)
    }
    var i, r, o, s, l, c, p, d, u, h, f, g, m, b, v, y, C, S, x, w, T, B, k, $, P, D, O, M, E, A, j, F, L, R, N, H, W, V, z, I, _, G, U, X, Y, K, J, q, Z, Q, tt, et, nt, at, it, rt, ot, st, lt, ct, pt, dt, ut;
    n.r(e), i = n(0), r = n.n(i), o = n(2), s = n(44), l = n(1), c = n(7), p = n(11), d = n(54), u = n(6), h = n(82), Object(u.a)(d.a, {
        fillMap: function() {
            this.fieldMap = p.a.factory(["Design:ReportInfo", "Design:StoreInResources", "Email:EmailSettings", "Engine", "Misc:AutoFillDataSet", "Misc:MaxPages", "Misc:SmoothGraphics", "Misc:TextQuality", "Print:PrintSettings:PrintSettings.Collate", "Print:PrintSettings:PrintSettings.Copies", "Print:PrintSettings:PrintSettings.CopyNames", "Print:PrintSettings:PrintSettings.Duplex", "Print:PrintSettings:PrintSettings.PageNumbers", "Print:PrintSettings:PrintSettings.PageRange", "Print:PrintSettings:PrintSettings.PagesOnSheet", "Print:PrintSettings:PrintSettings.PaperSource", "Print:PrintSettings:PrintSettings.Printer", "Print:PrintSettings:PrintSettings.PrintMode", "Print:PrintSettings:PrintSettings.PrintOnSheetHeight", "Print:PrintSettings:PrintSettings.PrintOnSheetRawPaperSize", "Print:PrintSettings:PrintSettings.PrintOnSheetWidth", "Print:PrintSettings:PrintSettings.PrintPages", "Print:PrintSettings:PrintSettings.PrintToFile", "Print:PrintSettings:PrintSettings.PrintToFileName", "Print:PrintSettings:PrintSettings.Reverse", "Print:PrintSettings:PrintSettings.SavePrinterWithReport", "Print:PrintSettings:PrintSettings.ShowDialog", "Script"])
        }
    }), (f = n.c[42]) && Object(u.a)(f.exports.default, {
        fillMap: function() {
            var i = Object(h.a)(this.update, 250);
            this.fieldMap = p.a.factory(["Appearance:Border:Border.Shadow", "Appearance:Border:Border.ShadowColor", "Appearance:Border:Border.ShadowWidth", {
                prop: "Appearance:Columns:Columns.Count",
                afterSetValue: function() {
                    this.prop("Columns.Width", this.attr("Width") / this.prop("Columns.Count")), i.call(this)
                },
                attrs: {
                    min: 1
                },
                defaultValue: 1
            }, "Appearance:Columns:Columns.Position", {
                prop: "Appearance:Columns:Columns.Width",
                afterSetValue: i,
                getValue: function() {
                    return this.prop("Columns.Width") || this.attr("Width")
                }
            }, "Appearance:Fill:Fill.Color", "Appearance:Watermark", "Behavior:BackPage", "Behavior:MirrorMargins", "Behavior:PrintOnPreviousPage", "Behavior:ResetPageNumber", "Behavior:StartOnOddPage", "Behavior:TitleBeforeHeader", "Behavior:Visible", "Data:OutlineExpression", "Design:Name", {
                prop: "Design:ExtraDesignWidth",
                afterSetValue: i
            }, "Paper:BottomMargin", {
                prop: "Paper:Landscape",
                setValue: function(t, e) {
                    var n, a;
                    this.prop(t) !== e && (this.prop(t, e), n = this.prop("PaperWidth"), a = this.prop("PaperHeight"), this.prop("PaperWidth", a), this.prop("PaperHeight", n), i.call(this))
                }
            }, {
                prop: "Paper:LeftMargin",
                afterSetValue: i
            }, {
                prop: "Paper:PaperWidth",
                afterSetValue: i
            }, "Paper:PaperHeight", "Paper:RawPaperSize", {
                prop: "Paper:RightMargin",
                afterSetValue: i
            }, "Paper:TopMargin", {
                prop: "Paper:UnlimitedHeight",
                type: "checkbox"
            }, {
                prop: "Paper:UnlimitedWidth",
                type: "checkbox"
            }, "Print:Duplex", "Print:FirstPageSource", "Print:OtherPagesSource"]), this.mainFields = [
                ["Design:Name"],
                ["Paper:PaperWidth", "Paper:PaperHeight"]
            ]
        }
    }), (g = n.c[10]) && Object(u.a)(g.exports.default, {
        fillMap: function() {
            this.fieldMap = p.a.factory(["Appearance:Border", "Appearance:Cursor", "Appearance:EvenStyle", "Appearance:EvenStylePriority", "Appearance:Fill:Fill.Color", "Appearance:HoverStyle", "Appearance:Style", "Behavior:CanBreak", "Behavior:CanGrow", "Behavior:CanShrink", "Behavior:Exportable", "Behavior:FirstRowStartsNewPage", "Behavior:KeepChild", "Behavior:Printable", "Behavior:PrintOn", "Behavior:PrintOnBottom", "Behavior:RepeatOnEveryPage", "Behavior:StartNewPage", "Behavior:Visible", "Design:Name", "Design:Restrictions", "Layout:Height"]), this.mainFields = [
                ["Design:Name"],
                ["Layout:Height"]
            ]
        }
    }), (m = n.c[100]) && Object(u.a)(m.exports.default, {
        fillMap: function() {
            this.fieldMap = p.a.factory(["Appearance:BackColor", "Appearance:Font", "Appearance:FormBorderStyle", "Appearance:RightToLeft", "Appearance:Text", "Behavior:Visible", "Design:Name", {
                prop: "Layout:Width",
                type: "number",
                attrs: {
                    step: 1
                }
            }, {
                prop: "Layout:Height",
                type: "number",
                attrs: {
                    step: 1
                }
            }, "Misc:AcceptButton", "Misc:CancelButton"]), this.mainFields = [
                ["Design:Name"],
                ["Layout:Width", "Layout:Height"]
            ]
        }
    }), b = n(157), v = n(158), y = b.a.fillMap, Object(u.a)(b.a, {
        fillMap: function() {
            y.call(this), this.fieldMap.append(["Data:Calculated", "Data:Expression", "Design:BindableControl", "Design:CustomBindableControl", "Design:Format"]), this.mainFields.push(["Design:BindableControl"])
        }
    }), C = v.a.fillMap, Object(u.a)(v.a, {
        fillMap: function() {
            C.call(this), this.fieldMap.append(["Data:Expression", "Misc:Description"])
        }
    }), S = n(153), x = n(231), w = Object.assign || function(t) {
        var e, n, a;
        for (e = 1; e < arguments.length; e++)
            for (a in n = arguments[e]) Object.prototype.hasOwnProperty.call(n, a) && (t[a] = n[a]);
        return t
    }, Object(u.a)(S.default, {
        getBarcodeProps: function() {
            return {
                prop: "Appearance:Barcode",
                label: "Barcode",
                fields: w({
                    Barcode: {
                        label: "Type",
                        type: "select",
                        origin: "Appearance:Barcode:Barcode",
                        collection: Object.keys(x),
                        afterSetValue: a
                    }
                }, x[this.prop("Barcode")].fields)
            }
        },
        fillMap: function() {
            this.fieldMap = p.a.factory(["Appearance:Angle", "Appearance:AsBitmap", "Appearance:Border", "Appearance:Cursor", "Appearance:EvenStyle", "Appearance:EvenStylePriority", "Appearance:Fill:Fill.Color", "Appearance:Style", "Appearance:HoverStyle", {
                prop: "Appearance:Zoom",
                type: "number",
                attrs: {
                    min: 1
                },
                afterSetValue: function() {
                    this.render()
                }
            }, this.getBarcodeProps(), {
                prop: "Behavior:AutoSize",
                label: "AutoSize",
                afterSetValue: function() {
                    this.reactivate()
                }
            }, "Behavior:CanGrow", "Behavior:CanShrink", "Behavior:Exportable", "Behavior:GrowToBottom", "Behavior:HideIfNoData", "Behavior:Printable", "Behavior:PrintOn", "Behavior:ShiftMode", "Behavior:ShowText", "Behavior:Visible", "Data:AllowExpressions", "Data:Brackets", "Data:DataColumn", "Data:Expression", "Data:NoDataText", "Data:Text", "Design:Name", "Design:Restrictions", "Layout:Anchor", "Layout:Dock", "Layout:Height", "Layout:Left", "Layout:Padding", "Layout:Top", "Layout:Width", "Navigation:Hyperlink"]), this.mainFields = [
                ["Data:Text"],
                ["Appearance:Barcode:Barcode"]
            ]
        }
    }), T = n(146), Object(u.a)(T.default, {
        fillMap: function() {
            this.fieldMap = p.a.factory(["Appearance:Border", {
                prop: "Appearance:CellWidth",
                type: "unit",
                afterSetValue: this.setMinWH
            }, {
                prop: "Appearance:CellHeight",
                type: "unit",
                afterSetValue: this.setMinWH
            }, "Appearance:Cursor", "Appearance:EvenStyle", "Appearance:EvenStylePriority", "Appearance:Fill:Fill.Color", "Appearance:Font", "Appearance:HorzAlign", {
                prop: "Appearance:HorzSpacing",
                type: "unit"
            }, "Appearance:HoverStyle", "Appearance:ParagraphOffset", "Appearance:Style", "Appearance:TextFill", "Appearance:TextOutline", "Appearance:VertAlign", {
                prop: "Appearance:VertSpacing",
                type: "unit"
            }, "Behavior:CanGrow", "Behavior:CanShrink", "Behavior:Duplicates", "Behavior:Editable", "Behavior:Exportable", "Behavior:GrowToBottom", "Behavior:HideValue", "Behavior:HideZeros", "Behavior:NullValue", "Behavior:Printable", "Behavior:PrintOn", "Behavior:ProcessAt", "Behavior:ShiftMode", "Behavior:Visible", "Behavior:WordWrap", "Data:AllowExpressions", "Data:Brackets", "Data:Format", "Data:Highlight", "Data:Text", "Design:Name", "Design:Restrictions", "Layout:Anchor", "Layout:Dock", "Layout:Height", "Layout:Left", "Layout:Top", "Layout:Width", "Navigation:Hyperlink"]), this.mainFields = [
                ["Design:Name"],
                ["Data:Text"]
            ]
        }
    }), B = n(145), Object(u.a)(B.default, {
        fillMap: function() {
            this.fieldMap = p.a.factory(["Appearance:Border", {
                prop: "Appearance:CheckColor",
                type: "color",
                label: "CheckColor"
            }, {
                prop: "Appearance:CheckedSymbol",
                type: "select",
                label: "CheckedSymbol",
                collection: ["Check", "Cross", "Plus", "Fill"]
            }, {
                prop: "Appearance:CheckWithRatio",
                type: "number",
                attrs: {
                    min: .2,
                    max: 2,
                    step: .1
                }
            }, "Appearance:Cursor", "Appearance:EvenStyle", "Appearance:EvenStylePriority", "Appearance:Fill:Fill.Color", "Appearance:Style", "Appearance:HoverStyle", {
                prop: "Appearance:UncheckedSymbol",
                label: "UncheckedSymbol",
                type: "select",
                collection: ["None", "Cross", "Minus"]
            }, "Behavior:CanGrow", "Behavior:CanShrink", "Behavior:Editable", "Behavior:Exportable", "Behavior:GrowToBottom", {
                prop: "Behavior:HideIfUnchecked",
                type: "checkbox",
                label: "HideIfUnchecked"
            }, "Behavior:Printable", "Behavior:PrintOn", "Behavior:ShiftMode", "Behavior:Visible", {
                prop: "Data:Checked",
                type: "checkbox",
                label: "Checked"
            }, "Data:DataColumn", "Data:Expression", "Design:Name", "Design:Restrictions", "Layout:Anchor", "Layout:Dock", "Layout:Height", "Layout:Left", "Layout:Top", "Layout:Width", "Navigation:Hyperlink"]), this.mainFields = [
                ["Data:Checked", "Appearance:CheckColor"],
                ["Appearance:CheckedSymbol", "Appearance:UncheckedSymbol"]
            ]
        }
    }), k = n(150), Object(u.a)(k.default, {
        fillMap: function() {
            this.fieldMap = p.a.factory(["Appearance:Border", "Appearance:Cursor", "Appearance:EvenStyle", "Appearance:EvenStylePriority", "Appearance:Fill:Fill.Color", "Appearance:HoverStyle", "Appearance:Style", "Behavior:BreakTo", "Behavior:CanBreak", "Behavior:CanGrow", "Behavior:CanShrink", "Behavior:Duplicates", "Behavior:Exportable", "Behavior:GrowToBottom", "Behavior:HideValue", "Behavior:HideZeros", "Behavior:NullValue", "Behavior:Printable", "Behavior:PrintOn", "Behavior:ProcessAt", "Behavior:RightToLeft", "Behavior:ShiftMode", "Behavior:Visible", "Data:AllowExpressions", "Data:Brackets", "Data:Format", "Data:Text", "Design:Name", "Design:Restrictions", "Layout:Anchor", "Layout:Dock", "Layout:Height", "Layout:Left", "Layout:Padding", "Layout:Top", "Layout:Width", "Navigation:Hyperlink"]), this.mainFields = [
                ["Design:Name"],
                ["Data:Text"]
            ]
        }
    }), $ = n(115), Object(u.a)($.default, {
        fillMap: function() {
            this.fieldMap = p.a.factory(["Appearance:Border", "Appearance:Cursor", {
                prop: "Appearance:Diagonal",
                type: "checkbox",
                label: "Diagonal"
            }, {
                prop: "Appearance:StartCap:StartCap.Height",
                label: "Height",
                extraLabel: "StartCap height",
                type: "number"
            }, {
                prop: "Appearance:StartCap:StartCap.Style",
                label: "Style",
                extraLabel: "StartCap style",
                type: "select",
                collection: $.default.tips
            }, {
                prop: "Appearance:StartCap:StartCap.Width",
                label: "Width",
                extraLabel: "StartCap width",
                type: "number"
            }, {
                prop: "Appearance:EndCap:EndCap.Height",
                label: "Height",
                extraLabel: "EndCap height",
                type: "number"
            }, {
                prop: "Appearance:EndCap:EndCap.Style",
                label: "Style",
                extraLabel: "EndCap style",
                type: "select",
                collection: $.default.tips
            }, {
                prop: "Appearance:EndCap:EndCap.Width",
                label: "Width",
                extraLabel: "EndCap width",
                type: "number"
            }, "Appearance:EvenStyle", "Appearance:EvenStylePriority", "Appearance:Style", "Appearance:HoverStyle", "Behavior:CanGrow", "Behavior:CanShrink", "Behavior:Exportable", "Behavior:GrowToBottom", "Behavior:Printable", "Behavior:PrintOn", "Behavior:ShiftMode", "Behavior:Visible", "Design:Name", "Design:Restrictions", "Layout:Anchor", "Layout:Dock", "Layout:Height", "Layout:Left", "Layout:Top", "Layout:Width", "Navigation:Hyperlink"]), this.mainFields = [
                ["Appearance:StartCap:StartCap.Height", "Appearance:StartCap:StartCap.Style", "Appearance:StartCap:StartCap.Width"],
                ["Appearance:EndCap:EndCap.Height", "Appearance:EndCap:EndCap.Style", "Appearance:EndCap:EndCap.Width"]
            ]
        }
    }), P = n(105), Object(u.a)(P.default, {
        fillMap: function() {
            this.fieldMap = p.a.factory(["Appearance:Border", "Appearance:Cursor", "Appearance:EvenStyle", "Appearance:EvenStylePriority", "Appearance:Fill:Fill.Color", "Appearance:HoverStyle", "Appearance:Style", {
                prop: "Behavior:AdjustSpannedCellsWidth",
                type: "checkbox"
            }, {
                prop: "Behavior:AutoSize",
                afterSetValue: function() {
                    this.showResizingComponents()
                }
            }, {
                prop: "Behavior:CellsSideBySide",
                type: "checkbox"
            }, "Behavior:Exportable", {
                prop: "Behavior:KeepCellsSideBySide",
                type: "checkbox"
            }, "Behavior:Layout", {
                prop: "Behavior:MatrixEventStylePriority",
                type: "select",
                collection: ["Rows", "Columns"]
            }, "Behavior:Printable", "Behavior:PrintOn", {
                prop: "Behavior:RepeatHeaders",
                type: "checkbox"
            }, "Behavior:ShiftMode", {
                prop: "Behavior:ShowTitle",
                type: "checkbox",
                afterSetValue: this.onShowTitleChange
            }, "Behavior:Visible", {
                prop: "Behavior:WrappedGap",
                type: "unit"
            }, "Data:DataSource", "Data:Filter", "Design:Name", "Design:Restrictions", "Layout:Anchor", "Layout:Dock", "Layout:Height", "Layout:Left", "Layout:Top", "Layout:Width", "Navigation:Hyperlink"]), this.mainFields = [
                ["Design:Name"],
                ["Data:DataSource"]
            ]
        }
    }), D = n(143), Object(u.a)(D.default, {
        fillMap: function() {
            this.fieldMap = p.a.factory(["Appearance:Angle", "Appearance:Border", "Appearance:Cursor", "Appearance:EvenStyle", "Appearance:EvenStylePriority", "Appearance:Fill:Fill.Color", "Appearance:Grayscale", "Appearance:HoverStyle", "Appearance:Style", "Appearance:Tile", "Appearance:Transparency", "Appearance:TransparentColor", "Behavior:CanGrow", "Behavior:CanShrink", "Behavior:Exportable", "Behavior:GrowToBottom", "Behavior:Printable", "Behavior:PrintOn", "Behavior:ShiftMode", "Behavior:ShowErrorImage", "Behavior:SizeMode", "Behavior:Visible", "Data:DataColumn", "Data:Image", "Data:ImageLocation", "Design:Name", "Design:Restrictions", "Layout:Anchor", "Layout:Dock", "Layout:Height", "Layout:Left", "Layout:Top", "Layout:Width", "Layout:MaxHeight", "Layout:MaxWidth", "Layout:Padding", "Navigation:Bookmark", "Navigation:Hyperlink"]), this.mainFields = [
                ["Data:Image", "Data:ImageLocation"],
                ["Data:DataColumn"]
            ]
        }
    }), O = n(151), Object(u.a)(O.default, {
        fillMap: function() {
            this.fieldMap = p.a.factory(["Appearance:Angle", "Appearance:Border", "Appearance:Cursor", "Appearance:EvenStyle", "Appearance:EvenStylePriority", "Appearance:Fill:Fill.Color", "Appearance:Grayscale", "Appearance:HoverStyle", "Appearance:Style", "Appearance:Tile", "Appearance:Transparency", "Appearance:TransparentColor", "Behavior:CanGrow", "Behavior:CanShrink", "Behavior:Exportable", "Behavior:GrowToBottom", "Behavior:Printable", "Behavior:PrintOn", "Behavior:ShiftMode", "Behavior:ShowErrorImage", "Behavior:SizeMode", "Behavior:Visible", "Data:DataColumn", "Data:ImageLocation", "Design:Name", "Design:Restrictions", "Design:Tag", "Layout:Anchor", "Layout:Dock", "Layout:Height", "Layout:Left", "Layout:Top", "Layout:Width", "Layout:MaxHeight", "Layout:MaxWidth", "Layout:Padding", "Misc:MaxSvgHeight", "Misc:MaxSvgWidth", "Misc:SvgDocument", "Misc:SvgGrayscale", "Navigation:Bookmark", "Navigation:Hyperlink"]), this.mainFields = [
                ["Data:ImageLocation"],
                ["Data:DataColumn"]
            ]
        }
    }), M = n(144), Object(u.a)(M.default, {
        fillMap: function() {
            this.fieldMap = p.a.factory(["Appearance:Border", "Appearance:Cursor", "Appearance:EvenStyle", "Appearance:EvenStylePriority", "Appearance:Fill:Fill.Color", "Appearance:Style", "Appearance:HoverStyle", "Behavior:BreakTo", "Behavior:CanGrow", "Behavior:CanBreak", "Behavior:CanShrink", "Behavior:Duplicates", "Behavior:Editable", "Behavior:Exportable", "Behavior:GrowToBottom", "Behavior:HideValue", "Behavior:HideZeros", "Behavior:NullValue", "Behavior:Printable", "Behavior:PrintOn", "Behavior:ProcessAt", "Behavior:ShiftMode", "Behavior:Visible", "Data:AllowExpressions", "Data:Brackets", "Data:DataColumn", "Data:Format", "Data:Text", "Design:Name", "Design:Restrictions", "Layout:Anchor", "Layout:Dock", "Layout:Height", "Layout:Left", "Layout:Top", "Layout:Width", "Layout:Padding", {
                prop: "Misc:OldBreakStyle",
                type: "checkbox"
            }, "Navigation:Hyperlink"]), this.mainFields = [
                ["Design:Name"]
            ]
        }
    }), E = n(132), Object(u.a)(E.default, {
        fillMap: function() {
            this.fieldMap = p.a.factory(["Appearance:Border", "Appearance:Cursor", "Appearance:Curve", "Appearance:EvenStyle", "Appearance:EvenStylePriority", "Appearance:Fill:Fill.Color", "Appearance:HoverStyle", {
                prop: "Appearance:Shape",
                type: "select",
                label: "Shape",
                collection: E.default.figures
            }, "Appearance:Style", "Behavior:CanGrow", "Behavior:CanShrink", "Behavior:Exportable", "Behavior:GrowToBottom", "Behavior:Printable", "Behavior:PrintOn", "Behavior:ShiftMode", "Behavior:Visible", "Design:Name", "Design:Restrictions", "Layout:Anchor", "Layout:Dock", "Layout:Height", "Layout:Left", "Layout:Top", "Layout:Width", "Navigation:Hyperlink"]), this.mainFields = [
                ["Design:Name"],
                ["Appearance:Shape"]
            ]
        }
    }), A = n(147), Object(u.a)(A.default, {
        fillMap: function() {
            this.fieldMap = p.a.factory(["Appearance:Border", "Appearance:Cursor", "Appearance:EvenStyle", "Appearance:EvenStylePriority", "Appearance:Fill:Fill.Color", "Appearance:HoverStyle", "Appearance:Inverted", "Appearance:LinearPointer", "Appearance:Scale:Font", "Appearance:Scale:MajorTicks", "Appearance:Scale:MinorTicks", "Appearance:Style", "Behavior:CanGrow", "Behavior:CanShrink", "Behavior:Exportable", "Behavior:GrowToBottom", "Behavior:Printable", "Behavior:PrintOn", "Behavior:ShiftMode", "Behavior:Visible", "Data:Expression", "Design:Name", "Design:Restrictions", "Layout:Anchor", "Layout:Dock", "Layout:Height", "Layout:Left", "Layout:Top", "Layout:Width", "Layout:Maximum", "Layout:Minimum", "Layout:Value", "Navigation:Hyperlink"]), this.mainFields = [
                ["Design:Name"],
                ["Data:Expression"]
            ]
        }
    }), j = n(133), Object(u.a)(j.default, {
        fillMap: function() {
            this.fieldMap = p.a.factory(["Appearance:Border", "Appearance:Cursor", "Appearance:EvenStyle", "Appearance:EvenStylePriority", "Appearance:Fill:Fill.Color", "Appearance:HoverStyle", "Appearance:Inverted", {
                prop: "Appearance:Type",
                type: "select",
                label: "Type",
                collection: j.default.types
            }, "Appearance:SemicircleOffset", "Appearance:LinearPointer", "Appearance:Scale:Font", "Appearance:Scale:MajorTicks", "Appearance:Scale:MinorTicks", "Appearance:Style", "Behavior:CanGrow", "Behavior:CanShrink", "Behavior:Exportable", "Behavior:GrowToBottom", "Behavior:Printable", "Behavior:PrintOn", "Behavior:ShiftMode", "Behavior:Visible", "Data:Expression", "Design:Name", "Design:Restrictions", "Layout:Anchor", "Layout:Dock", "Layout:Height", "Layout:Left", "Layout:Top", "Layout:Width", "Layout:Maximum", "Layout:Minimum", "Layout:Value", "Navigation:Hyperlink"]), this.mainFields = [
                ["Design:Name", "Layout:Value", "Appearance:Type"],
                ["Data:Expression"]
            ]
        }
    }), F = n(148), Object(u.a)(F.default, {
        fillMap: function() {
            this.fieldMap = p.a.factory(["Appearance:Border", "Appearance:Cursor", "Appearance:EvenStyle", "Appearance:EvenStylePriority", "Appearance:Fill:Fill.Color", "Appearance:Style", "Appearance:HoverStyle", "Appearance:Pointer", "Appearance:Scale", "Behavior:CanGrow", "Behavior:CanShrink", "Behavior:Exportable", "Behavior:GrowToBottom", "Behavior:Printable", "Behavior:PrintOn", "Behavior:ShiftMode", "Behavior:Visible", "Data:Expression", "Design:Name", "Design:Restrictions", "Layout:Anchor", "Layout:Dock", "Layout:Height", "Layout:Left", "Layout:Top", "Layout:Width", "Layout:Maximum", "Layout:Minimum", "Layout:Value", "Navigation:Hyperlink"]), this.mainFields = [
                ["Design:Name"],
                ["Data:Expression"]
            ]
        }
    }), L = n(149), Object(u.a)(L.default, {
        fillMap: function() {
            this.fieldMap = p.a.factory(["Appearance:Border", "Appearance:Cursor", "Appearance:EvenStyle", "Appearance:EvenStylePriority", "Appearance:Fill:Fill.Color", "Appearance:HoverStyle", "Appearance:Label", "Appearance:ProgressPointer", "Appearance:Style", "Behavior:CanGrow", "Behavior:CanShrink", "Behavior:Exportable", "Behavior:GrowToBottom", "Behavior:Printable", "Behavior:PrintOn", "Behavior:ShiftMode", "Behavior:Visible", "Data:Expression", "Design:Name", "Design:Restrictions", "Layout:Anchor", "Layout:Dock", "Layout:Height", "Layout:Left", "Layout:Top", "Layout:Width", "Layout:Maximum", "Layout:Minimum", "Layout:Value", "Navigation:Hyperlink"]), this.mainFields = [
                ["Design:Name"],
                ["Data:Expression"]
            ]
        }
    }), R = n(104), Object(u.a)(R.default, {
        fillMap: function() {
            this.fieldMap = p.a.factory(["Appearance:EvenStyle", "Appearance:EvenStylePriority", "Appearance:HoverStyle", "Behavior:GrowToBottom", "Behavior:PrintOn", "Behavior:PrintOnParent", "Behavior:ShiftMode", "Behavior:Visible", "Design:Name", "Design:Restrictions", "Layout:Anchor", "Layout:Dock", "Layout:Height", "Layout:Left", "Layout:Top", "Layout:Width", {
                prop: "Misc:ReportPage",
                collection: function() {
                    return r.a.grep(window.DSG.currentReport.pages.all(["ReportPage"]), function(t) {
                        return t.attr("isSubreport")
                    })
                }
            }, "Navigation:Hyperlink"])
        }
    }), N = n(33), Object(u.a)(N.default, {
        fillMap: function() {
            this.fieldMap = p.a.factory(["Appearance:Border", {
                prop: "Appearance:ColumnCount",
                type: "number",
                attrs: {
                    min: 0,
                    max: 20
                },
                setValue: function(t, e) {
                    this.attr(t, e, 1), this.update()
                }
            }, {
                prop: "Appearance:RowCount",
                type: "number",
                attrs: {
                    min: 0,
                    max: 20
                },
                setValue: function(t, e) {
                    this.attr(t, e, 1), this.update(), l.a.trigger("balance-band", this.collection.container)
                }
            }, "Appearance:Cursor", "Appearance:EvenStyle", "Appearance:EvenStylePriority", "Appearance:Fill:Fill.Color", "Appearance:HoverStyle", "Appearance:Style", {
                prop: "Behavior:AdjustSpannedCellsWidth",
                type: "checkbox"
            }, "Behavior:CanBreak", "Behavior:Exportable", "Behavior:GrowToBottom", "Behavior:Layout", "Behavior:Printable", "Behavior:PrintOn", {
                prop: "Behavior:RepeatHeaders",
                type: "checkbox"
            }, "Behavior:ShiftMode", "Behavior:Visible", {
                prop: "Behavior:WrappedGap",
                type: "unit"
            }, {
                prop: "Build:ManualBuildAutoSpans",
                type: "checkbox"
            }, "Design:Name", "Design:Restrictions", "Layout:Anchor", "Layout:Dock", "Layout:Height", "Layout:Left", "Layout:Top", "Layout:Width", {
                prop: "Layout:FixedColumns",
                type: "number",
                attrs: {
                    min: 0,
                    max: 4
                }
            }, {
                prop: "Layout:FixedRows",
                type: "number",
                attrs: {
                    min: 0,
                    max: 4
                }
            }, "Navigation:Hyperlink"]), this.mainFields = [
                ["Design:Name"],
                ["Appearance:ColumnCount", "Appearance:RowCount"]
            ]
        }
    }), H = n(89), Object(u.a)(H.a, {
        fillMap: function() {
            this.fieldMap = p.a.factory(["Appearance:Angle", "Appearance:Border", {
                prop: "Appearance:ColSpan",
                type: "number",
                attrs: {
                    min: 1
                },
                afterSetValue: function() {
                    this.getTable().update(), l.a.trigger("activate-component", window.DSG.currentReport.getSelected())
                }
            }, {
                prop: "Appearance:RowSpan",
                type: "number",
                attrs: {
                    min: 1
                },
                afterSetValue: function() {
                    this.getTable().update(), l.a.trigger("activate-component", window.DSG.currentReport.getSelected())
                }
            }, "Appearance:Cursor", "Appearance:EvenStyle", "Appearance:EvenStylePriority", "Appearance:Fill:Fill.Color", "Appearance:FirstTabOffset", "Appearance:Font", "Appearance:FontWidthRatio", "Appearance:HorzAlign", "Appearance:HoverStyle", "Appearance:LineHeight", "Appearance:ParagraphOffset", "Appearance:Style", "Appearance:TabWidth", "Appearance:TextFill", "Appearance:Underlines", "Appearance:VertAlign", "Behavior:AutoShrink", "Behavior:AutoShrinkMinSize", "Behavior:BreakTo", "Behavior:CanBreak", "Behavior:Clip", "Behavior:Editable", "Behavior:Exportable", "Behavior:HideValue", "Behavior:HideZeros", "Behavior:TextRenderType", "Behavior:NullValue", "Behavior:Printable", "Behavior:PrintOn", "Behavior:ProcessAt", "Behavior:RightToLeft", "Behavior:Trimming", "Behavior:Visible", "Behavior:WordWrap", "Behavior:Wysiwyg", "Data:AllowExpressions", "Data:Brackets", "Data:Format", "Data:Highlight", "Data:Text", "Design:Name", "Design:Restrictions", "Layout:Padding", "Navigation:Hyperlink"]), this.mainFields = [
                ["Design:Name"],
                ["Data:Text"]
            ]
        }
    }), W = n(116), Object(u.a)(W.a, {
        fillMap: function() {
            this.fieldMap = p.a.factory([{
                prop: "Behavior:AutoSize",
                defaultValue: !1
            }, "Behavior:Visible", "Design:Name", "Design:Restrictions", "Layout:MaxWidth", "Layout:MinWidth", {
                prop: "Layout:Width",
                setValue: function(t, e) {
                    var n = void 0;
                    e > this.prop("MaxWidth") && (n = this.prop("MaxWidth")), e < this.prop("MinWidth") && (n = this.prop("MinWidth")), this.prop(t, n || e), n && l.a.trigger("update-properties-panel", this), this.getTable().update(), this.activate()
                }
            }, "Navigation:Hyperlink"])
        }
    }), V = n(117), Object(u.a)(V.a, {
        fillMap: function() {
            this.fieldMap = p.a.factory([{
                prop: "Behavior:AutoSize",
                defaultValue: !1
            }, "Behavior:Visible", "Design:Name", "Design:Restrictions", "Layout:MaxHeight", "Layout:MinHeight", {
                prop: "Layout:Height",
                setValue: function(t, e) {
                    var n = void 0;
                    e > this.prop("MaxHeight") && (n = this.prop("MaxHeight")), e < this.prop("MinHeight") && (n = this.prop("MinHeight")), this.prop(t, n || e), n && l.a.trigger("update-properties-panel", this), this.getTable().update(), l.a.trigger("balance-band", this.getTable().collection.container), this.activate()
                }
            }, "Navigation:Hyperlink"])
        }
    }), z = n(25), Object(u.a)(z.default, {
        fillMap: function() {
            this.fieldMap = p.a.factory(["Appearance:Angle", "Appearance:Border", "Appearance:Cursor", "Appearance:EvenStyle", "Appearance:EvenStylePriority", "Appearance:Fill:Fill.Color", "Appearance:FirstTabOffset", "Appearance:Font", "Appearance:FontWidthRatio", "Appearance:HorzAlign", "Appearance:VertAlign", "Appearance:HoverStyle", "Appearance:LineHeight", "Appearance:ParagraphOffset", "Appearance:Style", "Appearance:TabWidth", "Appearance:TextFill", "Appearance:TextOutline", "Appearance:Underlines", "Behavior:AutoShrink", "Behavior:AutoShrinkMinSize", "Behavior:AutoWidth", "Behavior:BreakTo", "Behavior:CanBreak", "Behavior:CanGrow", "Behavior:CanShrink", "Behavior:Clip", "Behavior:Duplicates", "Behavior:Editable", "Behavior:Exportable", "Behavior:GrowToBottom", "Behavior:HideValue", "Behavior:HideZeros", "Behavior:TextRenderType", "Behavior:NullValue", "Behavior:Printable", "Behavior:PrintOn", "Behavior:ProcessAt", "Behavior:RightToLeft", "Behavior:ShiftMode", "Behavior:Trimming", "Behavior:Visible", "Behavior:WordWrap", "Behavior:Wysiwyg", "Data:AllowExpressions", "Data:Brackets", "Data:Format", "Data:Highlight", "Data:Text", "Design:Name", "Design:Restrictions", "Layout:Anchor", "Layout:Dock", "Layout:Height", "Layout:Left", "Layout:Padding", "Layout:Top", "Layout:Width", "Navigation:Hyperlink"]), this.mainFields = [
                ["Design:Name"],
                ["Data:Text"]
            ]
        }
    }), I = n(152), Object(u.a)(I.default, {
        fillMap: function() {
            this.fieldMap = p.a.factory(["Appearance:Border", "Appearance:Cursor", "Appearance:EvenStyle", "Appearance:EvenStylePriority", "Appearance:Fill:Fill.Color", "Appearance:Grayscale", "Appearance:HoverStyle", "Appearance:ImageAlign", "Appearance:Style", "Behavior:CanGrow", "Behavior:CanShrink", "Behavior:Exportable", "Behavior:GrowToBottom", "Behavior:Printable", "Behavior:PrintOn", "Behavior:ShiftMode", "Behavior:ShowErrorImage", "Behavior:SizeMode", "Behavior:Visible", "Behavior:VisibleExpression", "Data:DataColumn", "Data:ImageSourceExpression", "Data:ImageLocation", "Design:Name", "Design:Restrictions", "Layout:Anchor", "Layout:Dock", "Layout:Height", "Layout:Left", "Layout:Top", "Layout:Width", "Layout:MaxHeight", "Layout:MaxWidth", "Layout:Padding", "Navigation:Bookmark", "Navigation:Hyperlink"]), this.mainFields = [
                ["Data:DataColumn", "Data:ImageLocation"],
                ["Data:ImageSourceExpression"]
            ]
        }
    }), _ = n(86), Object(u.a)(_.default, {
        fillMap: function() {
            this.fieldMap = p.a.factory(["Appearance:Border", "Appearance:Cursor", "Appearance:EvenStyle", "Appearance:EvenStylePriority", "Appearance:Fill:Fill.Color", "Appearance:HoverStyle", "Appearance:Style", "Behavior:CanGrow", "Behavior:CanShrink", "Behavior:GrowToBottom", "Behavior:Exportable", "Behavior:ExportableExpression", "Behavior:Printable", "Behavior:PrintableExpression", "Behavior:PrintOn", "Behavior:Visible", "Behavior:VisibleExpression", "Design:Name", "Design:Restrictions", "Design:Tag", "Layout:Anchor", "Layout:Dock", "Layout:Height", "Layout:Left", "Layout:Top", "Layout:Width", "Navigation:Hyperlink"]), this.mainFields = [
                ["Design:Name"],
                ["Data:Text"]
            ]
        }
    }), G = n(106), Object(u.a)(G.default, {
        fillMap: function() {
            this.fieldMap = p.a.factory(["Appearance:BackColor", "Appearance:Cursor", "Appearance:Font", "Appearance:ForeColor", "Appearance:Image", "Appearance:ImageAlign", "Appearance:RightToLeft", "Appearance:TextAlign", "Appearance:TextImageRelation", "Behavior:AutoSize", "Behavior:DialogResult", "Behavior:Enabled", "Behavior:TabIndex", "Behavior:TabStop", "Behavior:Visible", {
                prop: "Data:Text",
                exprMenu: !1
            }, "Data Filtering:DetailControl", "Data Filtering:ReportParameter", "Design:Name", "Design:Restrictions", "Layout:Anchor", "Layout:Dock", {
                prop: "Layout:Left",
                type: "number"
            }, {
                prop: "Layout:Top",
                type: "number"
            }, {
                prop: "Layout:Width",
                type: "number"
            }, {
                prop: "Layout:Height",
                type: "number"
            }])
        }
    }), U = n(68), Object(u.a)(U.default, {
        fillMap: function() {
            this.fieldMap = p.a.factory(["Appearance:Appearance", "Appearance:BackColor", "Appearance:CheckAlign", "Appearance:Checked", "Appearance:CheckState", "Appearance:Cursor", "Appearance:Font", "Appearance:ForeColor", "Appearance:Image", "Appearance:ImageAlign", "Appearance:RightToLeft", "Appearance:TextAlign", "Appearance:TextImageRelation", "Appearance:ThreeState", "Behavior:AutoSize", "Behavior:Enabled", "Behavior:TabIndex", "Behavior:TabStop", "Behavior:Visible", {
                prop: "Data:Text",
                afterSetValue: function() {
                    this.prop("Width", this.prop("Text").width({
                        "font-size": this.attr("Font.Size"),
                        "font-family": this.attr("Font.Name")
                    }) + 2 * U.default.CHECKBOXWH)
                }
            }, "Data Filtering:AutoFilter", "Data Filtering:DataColumn", "Data Filtering:DetailControl", "Data Filtering:FilterOperation", "Data Filtering:ReportParameter", "Design:Name", "Design:Restrictions", "Layout:Anchor", "Layout:Dock", {
                prop: "Layout:Left",
                type: "number"
            }, {
                prop: "Layout:Top",
                type: "number"
            }])
        }
    }), X = n(107), Object(u.a)(X.default, {
        fillMap: function() {
            this.fieldMap = p.a.factory(["Appearance:BackColor", "Appearance:Cursor", "Appearance:Font", "Appearance:ForeColor", "Appearance:RightToLeft", "Behavior:ColumnWidth", "Behavior:Enabled", "Behavior:MultiColumn", "Behavior:SelectionMode", "Behavior:Sorted", "Behavior:TabIndex", "Behavior:TabStop", "Behavior:UseTabStops", "Behavior:Visible", {
                prop: "Data:ItemsText",
                label: "Items",
                type: "text",
                expression: !0,
                exprMenu: !1
            }, "Data Filtering:AutoFill", "Data Filtering:AutoFilter", "Data Filtering:DataColumn", "Data Filtering:DetailControl", "Data Filtering:FilterOperation", "Data Filtering:ReportParameter", "Design:Name", "Design:Restrictions", "Layout:Anchor", "Layout:Dock", {
                prop: "Layout:Left",
                type: "number"
            }, {
                prop: "Layout:Top",
                type: "number"
            }, {
                prop: "Layout:Width",
                type: "number"
            }, {
                prop: "Layout:Height",
                type: "number"
            }, {
                prop: "Misc:CheckOnClick",
                type: "checkbox"
            }])
        }
    }), Y = n(108), Object(u.a)(Y.default, {
        fillMap: function() {
            this.fieldMap = p.a.factory(["Appearance:BackColor", "Appearance:Cursor", "Appearance:DrawMode", "Appearance:DropDownHeight", "Appearance:DropDownStyle", "Appearance:DropDownWidth", "Appearance:Font", "Appearance:ForeColor", "Appearance:ItemHeight", "Appearance:MaxDropDownItems", "Appearance:RightToLeft", "Behavior:Enabled", "Behavior:Sorted", "Behavior:TabIndex", "Behavior:TabStop", "Behavior:Visible", {
                prop: "Data:ItemsText",
                label: "Items",
                type: "text",
                expression: !0,
                exprMenu: !1
            }, {
                prop: "Data:Text",
                type: "text",
                expression: !0,
                exprMenu: !1
            }, "Data Filtering:AutoFill", "Data Filtering:AutoFilter", "Data Filtering:DataColumn", "Data Filtering:DetailControl", "Data Filtering:FilterOperation", "Data Filtering:ReportParameter", "Design:Name", "Design:Restrictions", "Layout:Anchor", "Layout:Dock", {
                prop: "Layout:Left",
                type: "number"
            }, {
                prop: "Layout:Top",
                type: "number"
            }, {
                prop: "Layout:Width",
                type: "number"
            }, {
                prop: "Layout:Height",
                type: "number"
            }])
        }
    }), K = n(63), Object(u.a)(K.default, {
        fillMap: function() {
            this.fieldMap = p.a.factory(["Appearance:BackColor", "Appearance:Cursor", {
                prop: "Appearance:DropDownAlign",
                type: "select",
                collection: ["Left", "Right"]
            }, "Appearance:Font", "Appearance:ForeColor", "Appearance:RightToLeft", {
                prop: "Appearance:ShowCheckBox",
                type: "checkbox"
            }, {
                prop: "Appearance:ShowUpDown",
                type: "checkbox"
            }, {
                prop: "Behavior:Checked",
                type: "checkbox"
            }, "Behavior:Enabled", "Behavior:TabIndex", "Behavior:TabStop", "Behavior:Visible", {
                prop: "Data:CustomFormat",
                label: "CustomFormat",
                type: "text"
            }, {
                prop: "Data:Format",
                label: "Format",
                type: "select",
                collection: ["Long", "Short", "Time", "Custom"]
            }, {
                prop: "Data:MaxDate",
                label: "MaxDate",
                type: "datetime"
            }, {
                prop: "Data:MinDate",
                label: "MinDate",
                type: "datetime"
            }, {
                prop: "Data:Value",
                label: "Value",
                type: "datetime"
            }, "Data Filtering:AutoFill", "Data Filtering:AutoFilter", "Data Filtering:DataColumn", "Data Filtering:DetailControl", "Data Filtering:FilterOperation", "Data Filtering:ReportParameter", "Design:Name", "Design:Restrictions", "Layout:Anchor", "Layout:Dock", {
                prop: "Layout:Left",
                type: "number"
            }, {
                prop: "Layout:Top",
                type: "number"
            }, {
                prop: "Layout:Width",
                type: "number"
            }, {
                prop: "Layout:Height",
                type: "number"
            }])
        }
    }), J = n(109), Object(u.a)(J.default, {
        fillMap: function() {
            this.fieldMap = p.a.factory(["Appearance:BackColor", "Appearance:Cursor", "Appearance:Font", "Appearance:ForeColor", "Appearance:RightToLeft", "Appearance:TextAlign", "Behavior:Enabled", "Behavior:TabIndex", "Behavior:TabStop", "Behavior:Visible", {
                prop: "Data:Text",
                afterSetValue: function() {
                    this.prop("Width", this.prop("Text").width({
                        "font-size": this.attr("Font.Size"),
                        "font-family": this.attr("Font.Name")
                    }) + 10)
                }
            }, "Design:Name", "Design:Restrictions", "Layout:Anchor", "Layout:AutoSize", "Layout:Dock", {
                prop: "Layout:Left",
                type: "number"
            }, {
                prop: "Layout:Top",
                type: "number"
            }])
        }
    }), q = n(110), Object(u.a)(q.default, {
        fillMap: function() {
            this.fieldMap = p.a.factory(["Appearance:BackColor", "Appearance:Cursor", "Appearance:DrawMode", "Appearance:Font", "Appearance:ForeColor", "Appearance:ItemHeight", "Appearance:RightToLeft", "Behavior:ColumnWidth", "Behavior:MultiColumn", "Behavior:SelectionMode", "Behavior:Sorted", "Behavior:UseTabStops", "Behavior:Enabled", "Behavior:TabIndex", "Behavior:TabStop", "Behavior:Visible", {
                prop: "Data:ItemsText",
                label: "Items",
                type: "text",
                expression: !0,
                exprMenu: !1
            }, "Data Filtering:AutoFill", "Data Filtering:AutoFilter", "Data Filtering:DataColumn", "Data Filtering:DetailControl", "Data Filtering:FilterOperation", "Data Filtering:ReportParameter", "Design:Name", "Design:Restrictions", "Layout:Anchor", "Layout:Dock", {
                prop: "Layout:Left",
                type: "number"
            }, {
                prop: "Layout:Top",
                type: "number"
            }, {
                prop: "Layout:Width",
                type: "number"
            }, {
                prop: "Layout:Height",
                type: "number"
            }])
        }
    }), Z = n(111), Object(u.a)(Z.default, {
        fillMap: function() {
            this.fieldMap = p.a.factory(["Appearance:BackColor", "Appearance:Cursor", "Appearance:Font", "Appearance:ForeColor", "Appearance:RightToLeft", "Appearance:ShowToday", "Appearance:ShowTodayCircle", "Appearance:ShowWeekNumbers", {
                prop: "Data:FirstDayOfWeek",
                type: "select",
                collection: ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday", "Default"]
            }, {
                prop: "Data:MaxDate",
                label: "MaxDate",
                type: "datetime"
            }, {
                prop: "Data:MinDate",
                label: "MinDate",
                type: "datetime"
            }, {
                prop: "Data:MaxSelectionCount",
                type: "number"
            }, {
                prop: "Data:TodayDate",
                label: "TodayDate",
                type: "datetime"
            }, {
                prop: "Data:Text",
                exprMenu: !1
            }, "Behavior:Enabled", "Behavior:TabIndex", "Behavior:TabStop", "Behavior:Visible", "Data Filtering:AutoFill", "Data Filtering:AutoFilter", "Data Filtering:DataColumn", "Data Filtering:DetailControl", "Data Filtering:FilterOperation", "Data Filtering:ReportParameter", "Design:Name", "Design:Restrictions", {
                prop: "Layout:CalendarDimensions",
                type: "text"
            }, "Layout:Anchor", "Layout:Dock", {
                prop: "Layout:Left",
                type: "number"
            }, {
                prop: "Layout:Top",
                type: "number"
            }])
        }
    }), Q = n(112), Object(u.a)(Q.default, {
        fillMap: function() {
            this.fieldMap = p.a.factory(["Appearance:BackColor", "Appearance:CheckAlign", "Appearance:Checked", "Appearance:Cursor", "Appearance:Font", "Appearance:ForeColor", "Appearance:Image", "Appearance:ImageAlign", "Appearance:RightToLeft", "Appearance:TextAlign", "Appearance:TextImageRelation", "Behavior:AutoSize", "Behavior:Enabled", "Behavior:TabIndex", "Behavior:TabStop", "Behavior:Visible", {
                prop: "Data:Text",
                afterSetValue: function() {
                    this.prop("Width", this.prop("Text").width({
                        "font-size": this.attr("Font.Size"),
                        "font-family": this.attr("Font.Name")
                    }) + 2 * this.attr("radioCX"))
                }
            }, "Data Filtering:AutoFilter", "Data Filtering:DataColumn", "Data Filtering:DetailControl", "Data Filtering:FilterOperation", "Data Filtering:ReportParameter", "Design:Name", "Design:Restrictions", "Layout:Anchor", "Layout:Dock", {
                prop: "Layout:Left",
                type: "number"
            }, {
                prop: "Layout:Top",
                type: "number"
            }])
        }
    }), n(364), tt = n(131), et = tt.default.fillMap, Object(u.a)(tt.default, {
        fillMap: function() {
            et && et.call(this), this.fieldMap.append(["Behavior:CompleteToNRows", "Behavior:FillUnusedSpace"])
        }
    }), nt = n(83), at = nt.default.fillMap, Object(u.a)(nt.default, {
        fillMap: function() {
            at && at.call(this), this.fieldMap.rebuild(["Appearance:Columns:Columns.Count", "Appearance:Columns:Columns.Layout", "Appearance:Columns:Columns.MinRowCount", "Appearance:Columns:Columns.Width", "Behavior:CollectChildRows", "Behavior:KeepDetail", "Behavior:KeepTogether", "Behavior:PrintIfDatasourceEmpty", "Behavior:PrintIfDetailEmpty", "Behavior:ResetPageNumber", "Data:DataSource", "Data:Filter", "Data:MaxRows", "Data:Relation", "Data:RowCount"], ["Behavior:RepeatOnEveryPage"]), this.mainFields = [
                ["Design:Name", "Layout:Height"],
                ["Data:DataSource"]
            ]
        }
    }), it = n(130), rt = it.default.fillMap, Object(u.a)(it.default, {
        fillMap: function() {
            rt && rt.call(this), this.fieldMap.append(["Behavior:KeepTogether", "Behavior:KeepWithData", "Behavior:ResetPageNumber", "Behavior:SortOrder", "Data:Condition"])
        }
    }), ot = n(103), st = ot.default.fillMap, Object(u.a)(ot.default, {
        fillMap: function() {
            st && st.call(this), this.fieldMap.remove(["Behavior:PrintOnBottom", "Behavior:RepeatOnEveryPage", "Behavior:StartNewPage"])
        }
    }), lt = n(102), ct = lt.default.fillMap, Object(u.a)(lt.default, {
        fillMap: function() {
            ct && ct.call(this), this.fieldMap.remove(["Behavior:PrintOnBottom", "Behavior:RepeatOnEveryPage", "Behavior:StartNewPage"])
        }
    }), pt = n(101), dt = pt.default.fillMap, Object(u.a)(pt.default, {
        fillMap: function() {
            dt && dt.call(this), this.fieldMap.remove(["Behavior:RepeatOnEveryPage"])
        }
    }), ut = Object(c.a)(s.a, {
        pos: 1,
        className: "properties",
        icon: "icon-078",
        create: function() {
            var e = this;
            return new Promise(function(t) {
                e.$title = r()("<div>"), e.$body = r()("<div>"), t(e)
            })
        },
        update: function(t) {
            var e;
            return !!t && (this.$title && (this.$titleMain || (this.$titleMain = r()("<strong>"), this.$titleExtra = r()("<span>"), this.$title.append(this.$titleMain, this.$titleExtra)), this.$titleMain.text(o.a.tr("Properties") + " "), this.$titleExtra.text(t.toString())), e = this.build(t, t.fieldMap), this.$body && (this.$body.replaceWith(e), this.$body = e), this)
        },
        clear: function() {
            delete this.$titleMain, delete this.$titleExtra
        },
        bindEvents: function() {
            l.a.bind("update-properties-panel", function(t) {
                var e = window.DSG.toolbar;
                e && (e.$extraFirstLine && e.$extraFirstLine.empty(), e.$extraSecondLine && e.$extraSecondLine.empty(), e.$extra && e.$extra.removeClass("fr-hidden")), t = t || window.DSG.currentReport.getSelected(), ut.update(t)
            })
        }
    }), e.default = ut
}, function(t, e, n) {
    "use strict";
    var a, i, r, o, s, l, c, p, d, u, h, f, g, m, b, v, y, C, S, x, w, T, B, k, $, P, D, O, M, E;
    n.r(e), a = n(0), i = n.n(a), r = n(2), o = n(44), s = n(1), l = n(7), c = n(54), p = n(78), d = {
        Build: {
            label: "Properties Build",
            fields: {
                FinishReportEvent: {
                    label: "FinishReport",
                    type: "event",
                    eventName: "FinishReport",
                    eventStatement: "private void %event%(object sender, EventArgs e)"
                },
                StartReportEvent: {
                    label: "StartReport",
                    type: "event",
                    eventName: "StartReport",
                    eventStatement: "private void %event%(object sender, EventArgs e)"
                },
                FinishPageEvent: {
                    label: "FinishPage",
                    type: "event",
                    eventName: "FinishPage",
                    eventStatement: "private void %event%(object sender, EventArgs e)"
                },
                ManualBuildEvent: {
                    label: "ManualBuild",
                    type: "event",
                    eventName: "ManualBuild",
                    eventStatement: "private void %event%(object sender, EventArgs e)"
                },
                StartPageEvent: {
                    label: "StartPage",
                    type: "event",
                    eventName: "StartPage",
                    eventStatement: "private void %event%(object sender, EventArgs e)"
                },
                AfterDataEvent: {
                    label: "AfterData",
                    type: "event",
                    eventName: "AfterData",
                    eventStatement: "private void %event%(object sender, EventArgs e)"
                },
                AfterLayoutEvent: {
                    label: "AfterLayout",
                    type: "event",
                    eventName: "AfterLayout",
                    eventStatement: "private void %event%(object sender, EventArgs e)"
                },
                AfterPrintEvent: {
                    label: "AfterPrint",
                    type: "event",
                    eventName: "AfterPrint",
                    eventStatement: "private void %event%(object sender, EventArgs e)"
                },
                BeforeLayoutEvent: {
                    label: "BeforeLayout",
                    type: "event",
                    eventName: "BeforeLayout",
                    eventStatement: "private void %event%(object sender, EventArgs e)"
                },
                BeforePrintEvent: {
                    label: "BeforePrint",
                    type: "event",
                    eventName: "BeforePrint",
                    eventStatement: "private void %event%(object sender, EventArgs e)"
                },
                ModifyResult: {
                    label: "ModifyResult",
                    type: "event",
                    eventName: "ModifyResult",
                    eventStatement: "private void %event%(object sender, EventArgs e)"
                }
            }
        },
        Events: {
            label: "Properties Events",
            fields: {
                CheckedChanged: {
                    label: "CheckedChanged",
                    type: "event",
                    eventName: "CheckedChanged",
                    eventStatement: "private void %event%(object sender, EventArgs e)"
                },
                FormClosedEvent: {
                    label: "FormClosed",
                    type: "event",
                    eventName: "FormClosed",
                    eventStatement: "private void %event%(object sender, FormClosedEventArgs e)"
                },
                FormClosingEvent: {
                    label: "FormClosing",
                    type: "event",
                    eventName: "FormClosing",
                    eventStatement: "private void %event%(object sender, FormClosingEventArgs e)"
                },
                LoadEvent: {
                    label: "Load",
                    type: "event",
                    eventName: "Load",
                    eventStatement: "private void %event%(object sender, EventArgs e)"
                },
                PaintEvent: {
                    label: "Paint",
                    type: "event",
                    eventName: "Paint",
                    eventStatement: "private void %event%(object sender, PaintEventArgs e)"
                },
                ResizeEvent: {
                    label: "Resize",
                    type: "event",
                    eventName: "Resize",
                    eventStatement: "private void %event%(object sender, EventArgs e)"
                },
                ShownEvent: {
                    label: "Shown",
                    type: "event",
                    eventName: "Shown",
                    eventStatement: "private void %event%(object sender, EventArgs e)"
                },
                Click: {
                    label: "Click",
                    type: "event",
                    eventName: "Click",
                    eventStatement: "private void %event%(object sender, EventArgs e)"
                },
                DataLoaded: {
                    label: "DataLoaded",
                    type: "event",
                    eventName: "DataLoaded",
                    eventStatement: "private void %event%(object sender, EventArgs e)"
                },
                DoubleClick: {
                    label: "DoubleClick",
                    type: "event",
                    eventName: "DoubleClick",
                    eventStatement: "private void %event%(object sender, EventArgs e)"
                },
                DrawItem: {
                    label: "DrawItem",
                    type: "event",
                    eventName: "DrawItem",
                    eventStatement: "private void %event%(object sender, DrawItemEventArgs e)"
                },
                Enter: {
                    label: "Enter",
                    type: "event",
                    eventName: "Enter",
                    eventStatement: "private void %event%(object sender, EventArgs e)"
                },
                ItemCheck: {
                    label: "ItemCheck",
                    type: "event",
                    eventName: "ItemCheck",
                    eventStatement: "private void %event%(object sender, ItemCheckEventArgs e)"
                },
                KeyDown: {
                    label: "KeyDown",
                    type: "event",
                    eventName: "KeyDown",
                    eventStatement: "private void %event%(object sender, KeyEventArgs e)"
                },
                KeyPress: {
                    label: "KeyPress",
                    type: "event",
                    eventName: "KeyPress",
                    eventStatement: "private void %event%(object sender, KeyPressEventArgs e)"
                },
                KeyUp: {
                    label: "KeyUp",
                    type: "event",
                    eventName: "KeyUp",
                    eventStatement: "private void %event%(object sender, KeyEventArgs e)"
                },
                Leave: {
                    label: "Leave",
                    type: "event",
                    eventName: "Leave",
                    eventStatement: "private void %event%(object sender, EventArgs e)"
                },
                MouseDown: {
                    label: "MouseDown",
                    type: "event",
                    eventName: "MouseDown",
                    eventStatement: "private void %event%(object sender, MouseEventArgs e)"
                },
                MouseEnter: {
                    label: "MouseEnter",
                    type: "event",
                    eventName: "MouseEnter",
                    eventStatement: "private void %event%(object sender, EventArgs e)"
                },
                MouseLeave: {
                    label: "MouseLeave",
                    type: "event",
                    eventName: "MouseLeave",
                    eventStatement: "private void %event%(object sender, EventArgs e)"
                },
                MouseMove: {
                    label: "MouseMove",
                    type: "event",
                    eventName: "MouseMove",
                    eventStatement: "private void %event%(object sender, MouseEventArgs e)"
                },
                MouseUp: {
                    label: "MouseUp",
                    type: "event",
                    eventName: "MouseUp",
                    eventStatement: "private void %event%(object sender, MouseEventArgs e)"
                },
                MeasureItem: {
                    label: "MeasureItem",
                    type: "event",
                    eventName: "MeasureItem",
                    eventStatement: "private void %event%(object sender, MeasureItemEventArgs e)"
                },
                Paint: {
                    label: "Paint",
                    type: "event",
                    eventName: "Paint",
                    eventStatement: "private void %event%(object sender, PaintEventArgs e)"
                },
                Resize: {
                    label: "Resize",
                    type: "event",
                    eventName: "Resize",
                    eventStatement: "private void %event%(object sender, EventArgs e)"
                },
                TextChanged: {
                    label: "TextChanged",
                    type: "event",
                    eventName: "TextChanged",
                    eventStatement: "private void %event%(object sender, EventArgs e)"
                },
                ValueChanged: {
                    label: "ValueChanged",
                    type: "event",
                    eventName: "ValueChanged",
                    eventStatement: "private void %event%(object sender, EventArgs e)"
                },
                SelectedIndexChanged: {
                    label: "SelectedIndexChanged",
                    type: "event",
                    eventName: "SelectedIndexChanged",
                    eventStatement: "private void %event%(object sender, EventArgs e)"
                }
            }
        },
        Preview: {
            label: "Properties Preview",
            fields: {
                ClickEvent: {
                    label: "Click",
                    type: "event",
                    eventName: "Click",
                    eventStatement: "private void %event%(object sender, EventArgs e)"
                },
                MouseDownEvent: {
                    label: "MouseDown",
                    type: "event",
                    eventName: "MouseDown",
                    eventStatement: "private void %event%(object sender, EventArgs e)"
                },
                MouseEnterEvent: {
                    label: "MouseEnter",
                    type: "event",
                    eventName: "MouseEnter",
                    eventStatement: "private void %event%(object sender, EventArgs e)"
                },
                MouseLeaveEvent: {
                    label: "MouseLeave",
                    type: "event",
                    eventName: "MouseLeave",
                    eventStatement: "private void %event%(object sender, EventArgs e)"
                },
                MouseMoveEvent: {
                    label: "MouseMove",
                    type: "event",
                    eventName: "MouseMove",
                    eventStatement: "private void %event%(object sender, EventArgs e)"
                },
                MouseUpEvent: {
                    label: "MouseUp",
                    type: "event",
                    eventName: "MouseUp",
                    eventStatement: "private void %event%(object sender, EventArgs e)"
                }
            }
        },
        Misc: {
            label: "Properties Misc",
            fields: {
                DateChanged: {
                    label: "DateChanged",
                    type: "event",
                    eventName: "DateChanged",
                    eventStatement: "private void %event%(object sender, DateRangeEventArgs e)"
                }
            }
        }
    }, u = Object(l.a)(p.a, {
        data: d
    }), h = n(6), f = n(105), g = n(33), Object(h.a)(f.default, {
        fillEventMap: function() {
            g.default.fillEventMap.call(this), this.eventMap.append(["Build:ManualBuildEvent", "Build:ModifyResult"])
        }
    }), m = n(9), b = n(104), Object(h.a)(b.default, {
        fillEventMap: function() {
            m.default.fillEventMap.apply(this, arguments), this.eventMap.remove(["Build:AfterPrintEvent", "Build:BeforePrintEvent", "Preview:ClickEvent"])
        }
    }), Object(h.a)(g.default, {
        fillEventMap: function() {
            m.default.fillEventMap.apply(this, arguments), this.eventMap.append(["Build:ManualBuildEvent"])
        }
    }), v = n(106), Object(h.a)(v.default, {
        dblclick: function() {
            this.eventMap[0].fields.Click.control.$body.find("[type=button]").trigger("click")
        },
        fillEventMap: function() {
            this.eventMap = u.factory(["Events:Click", "Events:DataLoaded", "Events:DoubleClick", "Events:Enter", "Events:KeyDown", "Events:KeyPress", "Events:KeyUp", "Events:Leave", "Events:MouseDown", "Events:MouseEnter", "Events:MouseLeave", "Events:MouseMove", "Events:MouseUp", "Events:Paint", "Events:Resize", "Events:TextChanged"])
        }
    }), y = n(68), Object(h.a)(y.default, {
        dblclick: function() {
            this.eventMap[0].fields.Click.control.$body.find("[type=button]").trigger("click")
        },
        fillEventMap: function() {
            this.eventMap = u.factory(["Events:CheckedChanged", "Events:Click", "Events:DataLoaded", "Events:DoubleClick", "Events:Enter", "Events:KeyDown", "Events:KeyPress", "Events:KeyUp", "Events:Leave", "Events:MouseDown", "Events:MouseEnter", "Events:MouseLeave", "Events:MouseMove", "Events:MouseUp", "Events:Paint", "Events:Resize", "Events:TextChanged"])
        }
    }), C = n(107), Object(h.a)(C.default, {
        dblclick: function() {
            this.eventMap[0].fields.Click.control.$body.find("[type=button]").trigger("click")
        },
        fillEventMap: function() {
            this.eventMap = u.factory(["Events:Click", "Events:DataLoaded", "Events:DoubleClick", "Events:DrawItem", "Events:Enter", "Events:ItemCheck", "Events:KeyDown", "Events:KeyPress", "Events:KeyUp", "Events:Leave", "Events:MeasureItem", "Events:MouseDown", "Events:MouseEnter", "Events:MouseLeave", "Events:MouseMove", "Events:MouseUp", "Events:Paint", "Events:Resize", "Events:SelectedIndexChanged", "Events:TextChanged"])
        }
    }), S = n(108), Object(h.a)(S.default, {
        dblclick: function() {
            this.eventMap[0].fields.Click.control.$body.find("[type=button]").trigger("click")
        },
        fillEventMap: function() {
            this.eventMap = u.factory(["Events:Click", "Events:DataLoaded", "Events:DoubleClick", "Events:DrawItem", "Events:Enter", "Events:KeyDown", "Events:KeyPress", "Events:KeyUp", "Events:Leave", "Events:MeasureItem", "Events:MouseDown", "Events:MouseEnter", "Events:MouseLeave", "Events:MouseMove", "Events:MouseUp", "Events:Paint", "Events:Resize", "Events:SelectedIndexChanged", "Events:TextChanged"])
        }
    }), x = n(63), Object(h.a)(x.default, {
        dblclick: function() {
            this.eventMap[0].fields.Click.control.$body.find("[type=button]").trigger("click")
        },
        fillEventMap: function() {
            this.eventMap = u.factory(["Events:Click", "Events:DataLoaded", "Events:DoubleClick", "Events:Enter", "Events:KeyDown", "Events:KeyPress", "Events:KeyUp", "Events:Leave", "Events:MouseDown", "Events:MouseEnter", "Events:MouseLeave", "Events:MouseMove", "Events:MouseUp", "Events:Paint", "Events:Resize", "Events:TextChanged", "Events:ValueChanged"])
        }
    }), w = n(109), Object(h.a)(w.default, {
        dblclick: function() {
            this.eventMap[0].fields.Click.control.$body.find("[type=button]").trigger("click")
        },
        fillEventMap: function() {
            this.eventMap = u.factory(["Events:Click", "Events:DoubleClick", "Events:Enter", "Events:KeyDown", "Events:KeyPress", "Events:KeyUp", "Events:Leave", "Events:MouseDown", "Events:MouseEnter", "Events:MouseLeave", "Events:MouseMove", "Events:MouseUp", "Events:Paint", "Events:Resize", "Events:TextChanged"])
        }
    }), T = n(110), Object(h.a)(T.default, {
        dblclick: function() {
            this.eventMap[0].fields.Click.control.$body.find("[type=button]").trigger("click")
        },
        fillEventMap: function() {
            this.eventMap = u.factory(["Events:Click", "Events:DataLoaded", "Events:DoubleClick", "Events:DrawItem", "Events:Enter", "Events:KeyDown", "Events:KeyPress", "Events:KeyUp", "Events:Leave", "Events:MeasureItem", "Events:MouseDown", "Events:MouseEnter", "Events:MouseLeave", "Events:MouseMove", "Events:MouseUp", "Events:Paint", "Events:Resize", "Events:SelectedIndexChanged", "Events:TextChanged"])
        }
    }), B = n(111), Object(h.a)(B.default, {
        dblclick: function() {
            this.eventMap[0].fields.Click.control.$body.find("[type=button]").trigger("click")
        },
        fillEventMap: function() {
            this.eventMap = u.factory(["Events:Click", "Events:DataLoaded", "Events:DoubleClick", "Events:Enter", "Events:KeyDown", "Events:KeyPress", "Events:KeyUp", "Events:Leave", "Events:MouseDown", "Events:MouseEnter", "Events:MouseLeave", "Events:MouseMove", "Events:MouseUp", "Events:Paint", "Events:Resize", "Events:TextChanged", "Misc:DateChanged"])
        }
    }), k = n(112), Object(h.a)(k.default, {
        dblclick: function() {
            this.eventMap[0].fields.Click.control.$body.find("[type=button]").trigger("click")
        },
        fillEventMap: function() {
            this.eventMap = u.factory(["Events:CheckedChanged", "Events:Click", "Events:DataLoaded", "Events:DoubleClick", "Events:Enter", "Events:KeyDown", "Events:KeyPress", "Events:KeyUp", "Events:Leave", "Events:MouseDown", "Events:MouseEnter", "Events:MouseLeave", "Events:MouseMove", "Events:MouseUp", "Events:Paint", "Events:Resize", "Events:TextChanged"])
        }
    }), $ = n(113), Object(h.a)($.default, {
        dblclick: function() {
            this.eventMap[0].fields.Click.control.$body.find("[type=button]").trigger("click")
        },
        fillEventMap: function() {
            this.eventMap = u.factory(["Events:Click", "Events:DataLoaded", "Events:DoubleClick", "Events:Enter", "Events:KeyDown", "Events:KeyPress", "Events:KeyUp", "Events:Leave", "Events:MouseDown", "Events:MouseEnter", "Events:MouseLeave", "Events:MouseMove", "Events:MouseUp", "Events:Paint", "Events:Resize", "Events:TextChanged"])
        }
    }), Object(h.a)(c.a, {
        fillEventMap: function() {
            this.eventMap = u.factory(["Build:FinishReportEvent", "Build:StartReportEvent"])
        }
    }), n.c[100] && (P = n.c[100], Object(h.a)(P.exports.default, {
        fillEventMap: function() {
            this.eventMap = u.factory(["Events:FormClosedEvent", "Events:FormClosingEvent", "Events:LoadEvent", "Events:PaintEvent", "Events:ResizeEvent", "Events:ShownEvent"])
        }
    })), n.c[9] && (D = n.c[9], Object(h.a)(D.exports.default, {
        fillEventMap: function() {
            this.eventMap = u.factory(["Build:AfterDataEvent", "Build:AfterPrintEvent", "Build:BeforePrintEvent", "Preview:ClickEvent", "Preview:MouseDownEvent", "Preview:MouseEnterEvent", "Preview:MouseLeaveEvent", "Preview:MouseMoveEvent", "Preview:MouseUpEvent"])
        }
    })), n.c[10] && (O = n.c[10], Object(h.a)(O.exports.default, {
        fillEventMap: function() {
            this.eventMap = u.factory(["Build:AfterDataEvent", "Build:AfterLayoutEvent", "Build:AfterPrintEvent", "Build:BeforeLayoutEvent", "Build:BeforePrintEvent", "Preview:ClickEvent", "Preview:MouseDownEvent", "Preview:MouseEnterEvent", "Preview:MouseLeaveEvent", "Preview:MouseMoveEvent", "Preview:MouseUpEvent"])
        }
    })), n.c[42] && (M = n.c[42], Object(h.a)(M.exports.default, {
        fillEventMap: function() {
            this.eventMap = u.factory(["Build:FinishPageEvent", "Build:ManualBuildEvent", "Build:StartPageEvent"])
        }
    })), E = Object(l.a)(o.a, {
        pos: 2,
        className: "events",
        icon: "icon-079",
        create: function() {
            var e = this;
            return new Promise(function(t) {
                if (window.DSG.currentReport.prop("CodeRestricted")) return t(null);
                e.$title = i()("<div>"), e.$body = i()("<div>"), t(e)
            })
        },
        update: function(t) {
            var e;
            return !!t && (this.$title && (this.$titleMain || (this.$titleMain = i()("<strong>"), this.$titleExtra = i()("<span>"), this.$title.append(this.$titleMain, this.$titleExtra)), this.$titleMain.text(r.a.tr("Events") + " "), this.$titleExtra.text(t.toString())), e = this.build(t, t.eventMap), this.$body && (this.$body.replaceWith(e), this.$body = e), this)
        },
        clear: function() {
            delete this.$titleMain, delete this.$titleExtra
        },
        bindEvents: function() {
            s.a.bind("update-events-panel", function(t) {
                E.update(t)
            })
        }
    }), e.default = E
}, function(t, e, n) {
    "use strict";

    function a() {
        var t = window.DSG.currentReport,
            e = t.dataSources.allWithChildren();
        return t.connections.everyEntity(function(t) {
            e = [].concat(e, t.dataSources.allWithChildren())
        }), e.unshift(" "), e
    }
    var i, s, r, o, l, c, p, d, u, h, f, g, m, b, v, y, C, S, x, w, T, B, k, $, P, D, O, M, E, A, j, F, L;
    n.r(e), i = n(0), s = n.n(i), r = n(1), o = n(2), l = n(159), c = n(15), p = n(7), d = n(54), u = n(24), h = Object(p.a)(u.a, {
        pullDSByView: function(e) {
            var n = void 0;
            return this.eachEntity(function(t) {
                if (n = t.dataSources.pullByView(e)) return !1
            }), n
        },
        findOneDSAmongAll: function(e) {
            var n = void 0;
            return this.eachEntity(function(t) {
                if (n = t.dataSources.findOneAmongAll(e)) return !1
            }), n
        }
    }), f = n(87), g = n(184), m = n(245), b = n(246), v = n(158), y = n(13), C = n(32), S = n(17), x = n(28), w = Object(p.a)(y.a, {
        type: "Relation",
        icon: "icon-058",
        _init: function() {
            this._id = "rel" + Object(S.a)(), this.defaultValues = {
                Restrictions: "none"
            }, this.prop({
                Name: "",
                Alias: ""
            }), this.attr({
                _ChildDataSource: null,
                _ParentDataSource: null
            })
        },
        create: function() {
            var t = this.createObject(this);
            return t._init.apply(t, arguments), t
        },
        fillMap: function() {
            this.fieldMap = C.a.factory([{
                prop: "Data:ChildColumns",
                type: "textarea",
                attrs: {
                    rows: 3
                }
            }, {
                prop: "Data:ChildDataSource",
                type: "select",
                collection: a,
                getValue: function() {
                    var t = this.attr("_ChildDataSource");
                    if (t) return t.toString()
                },
                setValue: function(t, e) {
                    var n = window.DSG.currentReport.dataSources.findOneBy({
                        Alias: e
                    });
                    n ? this.prop(t, n.prop("Name")) : this.prop(t, e), this.update(), r.a.trigger("update-data-panel")
                }
            }, {
                prop: "Data:ParentColumns",
                type: "textarea",
                attrs: {
                    rows: 3
                }
            }, {
                prop: "Data:ParentDataSource",
                type: "select",
                collection: a,
                getValue: function() {
                    var t = this.attr("_ParentDataSource");
                    if (t) return t.toString()
                },
                setValue: function(t, e) {
                    var n = window.DSG.currentReport.dataSources.findOneBy({
                        Alias: e
                    });
                    n ? this.prop(t, n.prop("Name")) : this.prop(t, e), this.update(), r.a.trigger("update-data-panel")
                }
            }, {
                prop: "Design:Name",
                afterSetValue: function() {
                    r.a.trigger("update-data-panel")
                }
            }, "Design:Alias", "Design:Restrictions"]), this.mainFields = [
                ["Design:Name", "Design:Alias"],
                ["Data:ChildDataSource", "Data:ParentDataSource"]
            ]
        },
        update: function() {
            var t, e, n = window.DSG.currentReport;
            t = n.dataSources.findOneBy({
                Name: this.prop("ChildDataSource")
            }), (e = this.attr("_ChildDataSource")) && (e.asChildIn = e.asChildIn || [], Object(x.a)(e.asChildIn, this)), t && (this.attr("_ChildDataSource", t), t.asChildIn = t.asChildIn || [], -1 === t.asChildIn.indexOf(this) && t.asChildIn.push(this)), t = n.dataSources.findOneBy({
                Name: this.prop("ParentDataSource")
            }), (e = this.attr("_ParentDataSource")) && (e.asParentIn = e.asParentIn || [], Object(x.a)(e.asParentIn, this)), t && (this.attr("_ParentDataSource", t), t.asParentIn = t.asParentIn || [], -1 === t.asParentIn.indexOf(this) && t.asParentIn.push(this))
        },
        isRelation: function() {
            return !0
        },
        toString: function() {
            var t, e = this.prop("Alias") || this.prop("Name");
            return e || (t = this.attr("_ParentDataSource")) && (e = t.toString()), e
        }
    }), T = n(77), B = Object(p.a)(T.a, {
        type: "Total",
        icon: "icon-132",
        _init: function() {
            this._id = "t" + Object(S.a)(), this.editable = [null, function(t) {
                r.a.trigger("remove-total", t)
            }], this.defaultValues = {
                IncludeInvisibleRows: !1,
                ResetAfterPrint: !0,
                ResetOnReprint: !0,
                TotalType: "Sum",
                Evaluator: "",
                PrintOn: ""
            }, this.prop("Name", "Total"), this.bindableControl = "TextObject"
        },
        fillMap: function() {
            this.fieldMap = C.a.factory([{
                prop: "Behavior:IncludeInvisibleRows",
                type: "checkbox"
            }, {
                prop: "Behavior:ResetAfterPrint",
                type: "checkbox"
            }, {
                prop: "Behavior:ResetOnReprint",
                type: "checkbox"
            }, {
                prop: "Design:Name",
                afterSetValue: function() {
                    r.a.trigger("update-data-panel")
                }
            }, {
                prop: "Data:EvaluateCondition",
                type: "text",
                expression: !0
            }, {
                prop: "Data:Evaluator",
                type: "select",
                collection: function() {
                    function t(t) {
                        return t.prop("Name")
                    }
                    var e, n, a = [],
                        i = window.DSG.currentReport.pages.all(["ReportPage"]);
                    for (e = 0, n = i.length; e < n; e += 1)[].push.apply(a, s.a.map(i[e].bands.allWithChildren(["DataBand"]), t));
                    return a.unshift(""), a
                }
            }, {
                prop: "Data:Expression",
                type: "text",
                expression: !0
            }, {
                prop: "Data:PrintOn",
                type: "select",
                collection: function() {
                    var t = s.a.map(window.DSG.currentReport.getCurrentPage().bands.allWithChildren(), function(t) {
                        return t.prop("Name")
                    });
                    return t.unshift(""), t
                }
            }, {
                prop: "Data:TotalType",
                type: "select",
                collection: ["Sum", "Min", "Max", "Avg", "Count"]
            }]), this.mainFields = [
                ["Design:Name"]
            ]
        },
        isTotal: function() {
            return !0
        }
    }), k = n(29), $ = Object(p.a)(y.a, {
        entities: [],
        create: function() {
            var t = this.createObject(this);
            return t.attr("icon", "icon-060"), s.a.each(function() {
                function t() {
                    return this.prop("Name")
                }
                var e = {},
                    n = C.a.factory(["Data:DataType", "Design:Name"]),
                    a = [
                        ["Design:Name"],
                        ["Data:DataType"]
                    ];
                return e.Date = {
                    init: function() {
                        this.prop({
                            Name: "Date",
                            DataType: k.a.get("System.DateTime")
                        })
                    },
                    bindableControl: "TextObject",
                    fieldMap: n,
                    mainFields: a,
                    getView: t
                }, e.Page = {
                    init: function() {
                        this.prop({
                            Name: "Page",
                            DataType: k.a.get("System.Char")
                        })
                    },
                    bindableControl: "TextObject",
                    fieldMap: n,
                    mainFields: a,
                    getView: t
                }, e.TotalPages = {
                    init: function() {
                        this.prop({
                            Name: "TotalPages",
                            DataType: k.a.get("System.Int32")
                        })
                    },
                    bindableControl: "TextObject",
                    fieldMap: n,
                    mainFields: a,
                    getView: t
                }, e.PageN = {
                    init: function() {
                        this.prop({
                            Name: "PageN",
                            DataType: k.a.get("System.String")
                        })
                    },
                    bindableControl: "TextObject",
                    fieldMap: n,
                    mainFields: a,
                    getView: t
                }, e.PageNofM = {
                    init: function() {
                        this.prop({
                            Name: "PageNofM",
                            DataType: k.a.get("System.String")
                        })
                    },
                    bindableControl: "TextObject",
                    fieldMap: n,
                    mainFields: a,
                    getView: t
                }, e["Row#"] = {
                    init: function() {
                        this.prop({
                            Name: "Row#",
                            DataType: k.a.get("System.Int32")
                        })
                    },
                    bindableControl: "TextObject",
                    fieldMap: n,
                    mainFields: a,
                    getView: t
                }, e["AbsRow#"] = {
                    init: function() {
                        this.prop({
                            Name: "AbsRow#",
                            DataType: k.a.get("System.Int32")
                        })
                    },
                    bindableControl: "TextObject",
                    fieldMap: n,
                    mainFields: a,
                    getView: t
                }, e["Page#"] = {
                    init: function() {
                        this.prop({
                            Name: "Page#",
                            DataType: k.a.get("System.Int32")
                        })
                    },
                    bindableControl: "TextObject",
                    fieldMap: n,
                    mainFields: a,
                    getView: t
                }, e["TotalPages#"] = {
                    init: function() {
                        this.prop({
                            Name: "TotalPages#",
                            DataType: k.a.get("System.Int32")
                        })
                    },
                    bindableControl: "TextObject",
                    fieldMap: n,
                    mainFields: a,
                    getView: t
                }, e["CopyName#"] = {
                    init: function() {
                        this.prop({
                            Name: "CopyName#",
                            DataType: k.a.get("System.String")
                        })
                    },
                    bindableControl: "TextObject",
                    fieldMap: n,
                    mainFields: a,
                    getView: t
                }, e.HierarchyLevel = {
                    init: function() {
                        this.prop({
                            Name: "HierarchyLevel",
                            DataType: k.a.get("System.Int32")
                        })
                    },
                    bindableControl: "TextObject",
                    fieldMap: n,
                    mainFields: a,
                    getView: t
                }, e["HierarchyRow#"] = {
                    init: function() {
                        this.prop({
                            Name: "HierarchyRow#",
                            DataType: k.a.get("System.String")
                        })
                    },
                    bindableControl: "TextObject",
                    fieldMap: n,
                    mainFields: a,
                    getView: t
                }, e
            }(), function() {
                t.add(y.a.createObject(t, this))
            }), t
        },
        add: function() {
            var t = this.entities,
                e = [].slice.call(arguments);
            s.a.each(e, function() {
                t.push(this)
            })
        },
        get: function(e) {
            return s.a.grep(this.entities, function(t) {
                return t.prop("Name") === e
            })[0]
        }
    }), P = n(4), D = n(118), O = n(185), M = n(23), E = new M.a("data functions"), A = function() {
        var a, t = 2,
            i = 0;
        this.bindableControl = "TextObject", this.icon = "icon-052", a = {}, this.entities = function() {
            return new Promise(function(e, n) {
                return t <= i ? n({
                    error: new Error(1),
                    entity: "Functions"
                }) : Object.keys(a).length ? e(a) : fetch(P.a.get("getFunctions", {
                    id: window.DSG.currentReport._id
                }), {
                    credentials: "same-origin"
                }).then(function(t) {
                    return t.text()
                }).then(function(t) {
                    t = s.a.parseXML(Object(O.a)(t)), s()(t).find("ReportFunctions").each(function() {
                        return function t(e, n) {
                            var a, i, r = e.children(),
                                o = e.attr("Name");
                            r.length ? o ? (n[o] = {
                                label: o,
                                icon: "icon-066",
                                fields: {}
                            }, r.each(function() {
                                t(s()(this), n[o].fields)
                            })) : r.each(function() {
                                t(s()(this), n)
                            }) : o && (n[o] = {
                                label: o,
                                icon: null,
                                interface: Object(D.a)(e.attr("Description")).replace(/(<br\/?>)*$/g, ""),
                                view: (a = o, i = /([\w]+){1}(\([\s\S]+\))?/gi.exec(a), i ? i[1] && !i[2] ? i[1] + "()" : i[1] + "({0})".format(new Array(i[2].substring(1, i[2].length - 1).split(",").length).join(",")) : a),
                                bindableControl: "TextObject"
                            })
                        }(s()(this), a), E.info("loaded"), e(a)
                    })
                }).catch(function(t) {
                    return i += 1, n({
                        error: t,
                        entity: "Functions"
                    })
                })
            })
        }
    }, j = n(6), F = d.a._init, Object(j.a)(d.a, {
        _init: function() {
            F.apply(this, arguments), this.connections = h.create(this), this.relations = u.a.create(this), this.dataSources = f.a.create(this), this.parameters = g.a.create(this), this.systemVariables = $.create(), this.functions = new A, this.totals = u.a.create(this)
        },
        createDataSource: function(t, e) {
            var n = b.a.create(t);
            return e ? e.dataSources.add(n) : this.dataSources.add(n), n
        },
        createRelation: function() {
            var t = w.create();
            return this.relations.add(t), t
        },
        createTotal: function() {
            var t = B.create();
            return this.totals.add(t), t.createName(), t
        },
        createConnection: function(t) {
            var e = m.a.create(t);
            return this.connections.add(e), e
        },
        createParameter: function(t) {
            var e = t && t.parameters || this.parameters,
                n = v.a.create();
            return e.add(n), n.createName(), n
        }
    }), L = Object(p.a)({
        pos: 4,
        className: "data",
        icon: "icon-053",
        create: function() {
            var a = this;
            return new Promise(function(n) {
                Object(l.a)().then(function(t) {
                    var e = t.getMenu();
                    a.$body = e, a.$title = s()("<div>"), a.$title.text(o.a.tr("Data")), n(a)
                })
            })
        },
        bindEvents: function() {
            this.$body.on("click", ".rt-content", function() {
                var t = s()(this).parent()[0].element;
                if (!t) return !1;
                !t.fieldMap && t.fillMap && t.fillMap(), t.isEntity && t.isEntity() && (r.a.trigger("update-properties-panel", t), r.a.trigger("update-events-panel", t))
            }), r.a.bind("update-data-panel", function(t) {
                L.update(t)
            }), r.a.bind("create-parameter", function(t) {
                var e = window.DSG.currentReport.createParameter(t);
                r.a.trigger("update-data-panel", {
                    openParameters: !0
                }), c.a.push({
                    undo: function() {
                        e.remove(), r.a.trigger("update-data-panel")
                    },
                    redo: function() {
                        e.restore(), r.a.trigger("update-data-panel")
                    }
                }), r.a.trigger("update-menu")
            }), r.a.bind("remove-parameter", function(t) {
                t.remove(), r.a.trigger("update-data-panel"), c.a.push({
                    undo: function() {
                        t.restore(), r.a.trigger("update-data-panel")
                    },
                    redo: function() {
                        t.remove(), r.a.trigger("update-data-panel")
                    }
                }), r.a.trigger("update-menu")
            }), r.a.bind("create-total", function() {
                var t = window.DSG.currentReport.createTotal();
                r.a.trigger("update-data-panel", {
                    openTotals: !0
                }), c.a.push({
                    undo: function() {
                        t.remove(), r.a.trigger("update-data-panel")
                    },
                    redo: function() {
                        t.restore(), r.a.trigger("update-data-panel")
                    }
                }), r.a.trigger("update-menu")
            }), r.a.bind("remove-total", function(t) {
                t.remove(), r.a.trigger("update-data-panel"), c.a.push({
                    undo: function() {
                        t.restore(), r.a.trigger("update-data-panel")
                    },
                    redo: function() {
                        t.remove(), r.a.trigger("update-data-panel")
                    }
                }), r.a.trigger("update-menu")
            }), r.a.bind("add-connection", function(t) {
                window.DSG.currentReport.connections.add(t), r.a.trigger("update-data-panel", {
                    openConnections: !0
                }), c.a.push({
                    undo: function() {
                        t.remove(), r.a.trigger("update-data-panel")
                    },
                    redo: function() {
                        t.restore(), r.a.trigger("update-data-panel")
                    }
                }), r.a.trigger("update-menu")
            }), r.a.bind("remove-connection", function(t) {
                t.remove(), r.a.trigger("update-data-panel"), c.a.push({
                    undo: function() {
                        t.restore(), r.a.trigger("update-data-panel")
                    },
                    redo: function() {
                        t.remove(), r.a.trigger("update-data-panel")
                    }
                }), r.a.trigger("update-menu")
            })
        },
        update: function(e) {
            var n = this;
            Object(l.a)().then(function(t) {
                n.$body.html(t.getMenu(e).children())
            })
        }
    }), e.default = L
}, function(t, e, n) {
    "use strict";
    var a, i, r, o, s, l, d, c, u, p, h, f, g, m, b;
    n.r(e), n(351), n(352), n(353), a = n(0), i = n.n(a), r = n(4), o = n(2), s = n(31), l = n(1), d = n(27), c = n(7), u = function(t, e, n, a) {
        return t.slice(0, e) + a + t.slice(e + Math.abs(n))
    }, p = Object(c.a)(l.a, {
        _init: function() {
            var p = this.trigger;
            this.bind("procedure", function(l, c) {
                var t = window.DSG.currentReport;
                t.code.isRestricted() || (p("activate", t.code), l = l || t.getSelected(), i.a.when(t.code.isReady()).done(function(t) {
                    var e, n = t.editor,
                        a = (!l.isReport() && l.prop("Name") || "") + "_" + c.eventName,
                        i = "ReportScript",
                        r = t.getCode() || n.getValue(),
                        o = r.search(a),
                        s = r.search(i);
                    if (o < 0) {
                        if (s < 0) return void Object(d.a)("Could not create event handler due to errors in source code", {
                            danger: !0,
                            trans: !0
                        });
                        e = c.eventStatement.replace("%event%", a), r = u(r, s + i.length + 10, 0, "\t\t" + e + "\n\t\t{\n\t\t\t\n\t\t}\n\n"), n.setValue(r), n.setCursor(19, 11), n.focus()
                    }
                    l.prop(c.prop, a), p("update-events-panel", l)
                }))
            })
        }
    }), h = n(17), f = n(119), g = function() {
        return '<div class="code-container"><textarea id="code-editor">\nusing System;\nusing System.Collections;\nusing System.Collections.Generic;\nusing System.ComponentModel;\nusing System.Windows.Forms;\nusing System.Drawing;\nusing System.Data;\nusing FastReport;\nusing FastReport.Data;\nusing FastReport.Dialog;\nusing FastReport.Barcode;\nusing FastReport.Table;\nusing FastReport.Utils;\n\nnamespace FastReport\n{\n    public class ReportScript\n    {\n        &nbsp;\n    }\n};\n</textarea></div>'
    }, m = n(52), b = n.n(m), n(354), n(355), n(356), n(357), n(358), n(359), n(361), n(189), e.default = s.a.createObject(s.a, {
        icon: "icon-061",
        type: "ScriptText",
        create: function() {
            return this.createObject(this, {
                defer: i.a.Deferred(),
                init: function() {
                    this.SM.add(this), this._id = "code" + Object(h.a)(), this.editor = null, this.attr({
                        isHidden: !0,
                        activated: !1,
                        code: null
                    }), this.createWorkspace("code"), this.report = null, p.create()
                }
            })
        },
        createWorkspace: function(t) {
            this.workspace = i()("<div>"), this.$workspace = i()(this.workspace), this.$workspace.addClass(t)
        },
        isReady: function() {
            return this.defer.promise()
        },
        initDefault: function() {
            var e = this,
                t = i()(g());
            this.$code = i()("#code-editor", t), this.$workspace.html(t), this.editor = b.a.fromTextArea(this.$code[0], {
                lineNumbers: !0,
                matchBrackets: !0,
                styleActiveLine: !0,
                highlightSelectionMatches: {
                    showToken: /\w/
                },
                mode: "text/x-csharp",
                theme: "neat"
            }), this.editor.on("change", function(t) {
                e.attr("code", t.getValue())
            }), this.setMode(this.report.prop("ScriptLanguage")), this.defer.resolve(this)
        },
        setMode: function(t) {
            switch (t.toLowerCase()) {
                case "vb":
                    t = "text/vbscript";
                    break;
                case "csharp":
                    t = "text/x-csharp";
                    break;
                case "pascalscript":
                    t = "text/pascal";
                    break;
                default:
                    throw new Error("wrong mode")
            }
            this.editor.setOption("mode", t)
        },
        isRestricted: function() {
            return this.report.prop("CodeRestricted") || r.a.get("CodeRestricted")
        },
        isCode: function() {
            return !0
        },
        _show: function() {
            this.attr("code") && this.editor.setValue(this.attr("code")), this.editor.setCursor(17, 9), this.editor.focus()
        },
        show: function() {
            this._super._super.show.apply(this, arguments), this.report.$workspace.addClass("fr-hidden"), "resolved" !== this.isReady().state() && this.initDefault(), this._show()
        },
        setCode: function(t) {
            this.attr("code", t)
        },
        getCode: function() {
            return this.attr("code")
        },
        render: function() {},
        remove: function() {},
        toXMLNET: function(t) {
            var n = this;
            return new Promise(function(e) {
                n._super._super.toXMLNET.call(n, t).then(function(t) {
                    return t = i()(t), n.getCode() ? (t.text(Object(f.a)(n.getCode())), e(t[0])) : e(null)
                })
            })
        },
        toXMLVCL: function(e) {
            var n = this;
            return new Promise(function(t) {
                return i()(e.parentNode).attr("ScriptText.Text", Object(f.a)(n.getCode())), t(i()(e.parentNode))
            })
        },
        toString: function() {
            return o.a.tr("Workspace Code")
        }
    })
}, function(t, e, n) {
    "use strict";
    var a, i, r, o, s, l, c, p, d, u, h, f, g, m, b, v, y, C, S, x, w, T, B, k, $, P, D, O, M, E, A, j;
    n.r(e), n(365), a = n(0), i = n.n(a), r = n(2), o = n(1), s = n(13), l = n(7), c = n(54), p = n(31), d = n(42), u = n(36), h = n(10), f = n(100), g = n(9), m = n(33), b = n(89), v = n(117), y = n(116), C = n(86), S = n(228), x = n(6), S.default.buildTree = function() {}, w = c.a.createDialog, T = c.a.createPage, B = u.a.put, k = u.a.render, $ = u.a.remove, P = h.default.put, D = g.default.render, O = g.default.remove, M = f.default.put, E = m.default.update, A = b.a.put, Object(x.a)(s.a, {
        selfTree: null,
        statusTree: 1,
        selectedInTree: null,
        buildHTMLTreeBlock: function(t) {
            var e = {
                $main: i()("<li>"),
                $expand: i()("<div>"),
                $icon: i()("<div>"),
                $content: i()("<div>"),
                $container: i()("<ul>")
            };
            return e.$main.addClass("rt-node"), e.$expand.addClass("rt-expand icon-leaf"), e.$icon.addClass("rt-icon"), e.$content.addClass("rt-content"), e.$container.addClass("rt-container"), e.$main.append(e.$expand, e.$icon, e.$content, e.$container), t && t.icon && e.$icon.addClass(t.icon), e
        },
        rebuildTree: function() {
            var t;
            return this.selfTree ? (t = this.selfTree.$main, this.selfTree = null, this.buildTree(), t.replaceWith(this.selfTree.$main), this.selfTree.$main) : this.buildTree()
        },
        buildTree: function() {
            return this.selfTree = this.selfTree || this.buildHTMLTreeBlock({
                icon: this.attr("icon") || this.icon
            }), this.statusTree = this.statusTree, this.selfTree.$main.data("entity", this), this.selfTree.$main
        },
        putInTree: function(t) {
            if (!this.selfTree || !t) return !1;
            t.find(this.selfTree.$container).length && t.replaceWith(this.selfTree.$main), this.selfTree.$container.find(t).length || this.selfTree.$container.append(t), t.removeClass("fr-hidden"), this.updateTreeView()
        },
        removeFromTree: function(t) {
            t.addClass("fr-hidden"), this.updateTreeView()
        },
        updateTreeView: function() {
            var t = this.selfTree;
            t && (t.$content.text(this), t.$container.children().length ? this.statusTree ? t.$expand.addClass("icon-exp_minus").removeClass("icon-leaf") : t.$expand.addClass("icon-exp_plus").removeClass("icon-leaf") : t.$expand.removeClass("icon-exp_minus icon-exp_plus").addClass("icon-leaf"))
        }
    }), Object(x.a)(c.a, {
        buildTree: function() {
            var e = this;
            return s.a.buildTree.call(this), this.pages.eachEntity(function(t) {
                e.putInTree(t.buildTree())
            }), this.updateTreeView(), this.selfTree.$main.addClass("rt-root"), this.selfTree.$main
        },
        createPage: function() {
            var t = T.apply(this, arguments);
            return this.putInTree(t.buildTree()), t
        },
        createDialog: function() {
            var t = w.apply(this, arguments);
            return t && this.putInTree(t.buildTree()), t
        }
    }), Object(x.a)(u.a, {
        updateExts: function() {
            this.rebuildTree()
        },
        put: function(t) {
            B.apply(this, arguments), this.putInTree(t.buildTree())
        },
        render: function() {
            return this.attr("removed") && this.collection.container.putInTree(this.buildTree()), k.apply(this, arguments)
        },
        remove: function() {
            return this.collection.container.removeFromTree(this.selfTree.$main), $.apply(this, arguments)
        }
    }), Object(x.a)(f.default, {
        put: function(t) {
            return M.apply(this, arguments), this.putInTree(t.buildTree()), this
        },
        buildTree: function() {
            return p.a.buildTree.call(this), this.updateTreeView(), this.selfTree.$main.addClass("rt-form"), this.selfTree.$main
        }
    }), d.default.buildTree = function() {
        var e = this;
        return p.a.buildTree.call(this), this.bands.eachEntity(function(t) {
            e.putInTree(t.buildTree())
        }), this.updateTreeView(), this.selfTree.$main.addClass("rt-page"), this.selfTree.$main
    }, Object(x.a)(h.default, {
        buildTree: function() {
            var e = this;
            return u.a.buildTree.call(this), this.bands.eachEntity(function(t) {
                e.putInTree(t.buildTree())
            }), this.components.eachEntity(function(t) {
                e.putInTree(t.buildTree())
            }), this.updateTreeView(), this.selfTree.$main
        },
        put: function(t) {
            var e = t.collection && t.collection.container;
            return this.putInTree(t.buildTree()), e && e.updateTreeView(), P.apply(this, arguments)
        }
    }), Object(x.a)(g.default, {
        render: function() {
            return this.attr("removed") && this.collection.container.putInTree(this.buildTree()), D.apply(this, arguments)
        },
        remove: function() {
            return this.collection.container.removeFromTree(this.selfTree.$main), O.apply(this, arguments)
        },
        buildTree: function() {
            return s.a.buildTree.call(this), this.updateTreeView(), this.selfTree.$main
        }
    }), m.default.update = function() {
        var t = E.apply(this, arguments);
        return this.rebuildTree(), t
    }, m.default.buildTree = function() {
        var t = void 0,
            e = void 0;
        for (g.default.buildTree.call(this), t = 0, e = this.columns.length; t < e; t += 1) this.putInTree(this.columns[t].buildTree());
        for (t = 0, e = this.rows.length; t < e; t += 1) this.putInTree(this.rows[t].rebuildTree());
        return this.updateTreeView(), this.selfTree.$main
    }, b.a.put = function(t) {
        return this.putInTree(t.buildTree()), A.apply(this, arguments)
    }, b.a.buildTree = function() {
        var e = this;
        return g.default.buildTree.call(this), this.components.eachEntity(function(t) {
            e.putInTree(t.buildTree())
        }), this.updateTreeView(), this.selfTree.$main
    }, v.a.buildTree = function() {
        var t, e = void 0;
        for (s.a.buildTree.call(this), e = 0, t = this.cells.length; e < t; e += 1) this.putInTree(this.cells[e].buildTree());
        return this.updateTreeView(), this.selfTree.$main
    }, y.a.buildTree = function() {
        return s.a.buildTree.call(this), this.updateTreeView(), this.selfTree.$main
    }, C.default.put = function(t) {
        return this.putInTree(t.buildTree()), A.apply(this, arguments)
    }, C.default.buildTree = function() {
        var e = this;
        return g.default.buildTree.call(this), this.components.eachEntity(function(t) {
            e.putInTree(t.buildTree())
        }), this.updateTreeView(), this.selfTree.$main
    }, j = {
        pos: 3,
        className: "report-tree",
        icon: "icon-189",
        create: function() {
            var n = this;
            return new Promise(function(t) {
                var e = window.DSG.currentReport;
                n.$body = i()("<ul>").append(e.buildTree()), n.$body.addClass("rt-container"), n.$title = i()("<div>"), n.$title.text(r.a.tr("ReportTree")), n.$body.addClass("rt-main-container"), t(n)
            })
        },
        bindEvents: function() {
            var t = window.DSG.currentReport;
            t.selfTree.$main.on("click", ".rt-content", function() {
                o.a.trigger("activate", i()(this).parent().data("entity"))
            }), t.selfTree.$main.on("click", ".rt-expand, .rt-icon", function() {
                var t, e = i()(this);
                return e.is(".rt-icon") && (e = e.prev(".rt-expand")), t = e.parent().data("entity"), e.is(".icon-exp_minus") ? t.selfTree.$container.slideUp(100, function() {
                    e.removeClass("icon-exp_minus").addClass("icon-exp_plus"), t.statusTree = 0
                }) : e.is(".icon-exp_plus") && t.selfTree.$container.slideDown(100, function() {
                    e.removeClass("icon-exp_plus").addClass("icon-exp_minus"), t.statusTree = 1
                }), !1
            }), o.a.bind("select-in-tree", function(t) {
                var e;
                return s.a.selectedInTree && s.a.selectedInTree.removeClass("selected"), !(!t || !t.selfTree) && (s.a.selectedInTree = t.selfTree.$content, s.a.selectedInTree.addClass("selected"), (e = s.a.selectedInTree.parents(".rt-page,.rt-form").data("entity")) && o.a.trigger("show-page", e), this)
            })
        }
    }, e.default = Object(l.a)(j)
}, function(t, e, n) {
    t.exports = n.p + "images/upc-e.af30393a6a96ebffe1bd4374946293ca.png"
}, function(t) {
    t.exports = JSON.parse('{"2/5 Interleaved":{"label":"2/5 Interleaved","fields":{"Barcode.CalcCheckSum":{"label":"CalcCheckSum","type":"checkbox","defaultValue":true},"Barcode.Color":{"label":"Color","type":"color","defaultValue":"#000000"},"Barcode.Trim":{"label":"Trim","type":"checkbox","defaultValue":true},"Barcode.WideBarRatio":{"label":"WideBarRatio","type":"number","defaultValue":2}}},"2/5 Industrial":{"label":"2/5 Industrial","fields":{"Barcode.CalcCheckSum":{"label":"CalcCheckSum","type":"checkbox","defaultValue":true},"Barcode.Color":{"label":"Color","type":"color","defaultValue":"#000000"},"Barcode.Trim":{"label":"Trim","type":"checkbox","defaultValue":true},"Barcode.WideBarRatio":{"label":"WideBarRatio","type":"number","defaultValue":2}}},"2/5 Matrix":{"label":"2/5 Matrix","fields":{"Barcode.CalcCheckSum":{"label":"CalcCheckSum","type":"checkbox","defaultValue":true},"Barcode.Color":{"label":"Color","type":"color","defaultValue":"#000000"},"Barcode.Trim":{"label":"Trim","type":"checkbox","defaultValue":true},"Barcode.WideBarRatio":{"label":"WideBarRatio","type":"number","defaultValue":2.5}}},"Codabar":{"label":"Codabar","fields":{"Barcode.CalcCheckSum":{"label":"CalcCheckSum","type":"checkbox","defaultValue":true},"Barcode.Color":{"label":"Color","type":"color","defaultValue":"#000000"},"Barcode.Trim":{"label":"Trim","type":"checkbox","defaultValue":true},"Barcode.WideBarRatio":{"label":"WideBarRatio","type":"number","defaultValue":2}}},"Code128":{"label":"Code128","fields":{"Barcode.AutoEncode":{"label":"AutoEncode","type":"checkbox","defaultValue":true},"Barcode.CalcCheckSum":{"label":"CalcCheckSum","type":"checkbox","defaultValue":true},"Barcode.Color":{"label":"Color","type":"color","defaultValue":"#000000"},"Barcode.Trim":{"label":"Trim","type":"checkbox","defaultValue":true},"Barcode.WideBarRatio":{"label":"WideBarRatio","type":"number","defaultValue":2}}},"Code39":{"label":"Code39","fields":{"Barcode.CalcCheckSum":{"label":"CalcCheckSum","type":"checkbox","defaultValue":true},"Barcode.Color":{"label":"Color","type":"color","defaultValue":"#000000"},"Barcode.Trim":{"label":"Trim","type":"checkbox","defaultValue":true},"Barcode.WideBarRatio":{"label":"WideBarRatio","type":"number","defaultValue":2}}},"Code39 Extended":{"label":"Code39 Extended","fields":{"Barcode.CalcCheckSum":{"label":"CalcCheckSum","type":"checkbox","defaultValue":true},"Barcode.Color":{"label":"Color","type":"color","defaultValue":"#000000"},"Barcode.Trim":{"label":"Trim","type":"checkbox","defaultValue":true},"Barcode.WideBarRatio":{"label":"WideBarRatio","type":"number","defaultValue":2}}},"Code93":{"label":"Code93","fields":{"Barcode.CalcCheckSum":{"label":"CalcCheckSum","type":"checkbox","defaultValue":true},"Barcode.Color":{"label":"Color","type":"color","defaultValue":"#000000"},"Barcode.Trim":{"label":"Trim","type":"checkbox","defaultValue":true},"Barcode.WideBarRatio":{"label":"WideBarRatio","type":"number","defaultValue":2}}},"Code93 Extended":{"label":"Code93 Extended","fields":{"Barcode.CalcCheckSum":{"label":"CalcCheckSum","type":"checkbox","defaultValue":true},"Barcode.Color":{"label":"Color","type":"color","defaultValue":"#000000"},"Barcode.Trim":{"label":"Trim","type":"checkbox","defaultValue":true},"Barcode.WideBarRatio":{"label":"WideBarRatio","type":"number","defaultValue":2}}},"EAN8":{"label":"EAN8","fields":{"Barcode.CalcCheckSum":{"label":"CalcCheckSum","type":"checkbox","defaultValue":true},"Barcode.Color":{"label":"Color","type":"color","defaultValue":"#000000"},"Barcode.Trim":{"label":"Trim","type":"checkbox","defaultValue":true},"Barcode.WideBarRatio":{"label":"WideBarRatio","type":"number","defaultValue":2}}},"EAN13":{"label":"EAN13","fields":{"Barcode.CalcCheckSum":{"label":"CalcCheckSum","type":"checkbox","defaultValue":true},"Barcode.Color":{"label":"Color","type":"color","defaultValue":"#000000"},"Barcode.Trim":{"label":"Trim","type":"checkbox","defaultValue":true},"Barcode.WideBarRatio":{"label":"WideBarRatio","type":"number","defaultValue":2}}},"MSI":{"label":"MSI","fields":{"Barcode.CalcCheckSum":{"label":"CalcCheckSum","type":"checkbox","defaultValue":true},"Barcode.Color":{"label":"Color","type":"color","defaultValue":"#000000"},"Barcode.Trim":{"label":"Trim","type":"checkbox","defaultValue":true},"Barcode.WideBarRatio":{"label":"WideBarRatio","type":"number","defaultValue":2}}},"PostNet":{"label":"PostNet","fields":{"Barcode.CalcCheckSum":{"label":"CalcCheckSum","type":"checkbox","defaultValue":true},"Barcode.Color":{"label":"Color","type":"color","defaultValue":"#000000"},"Barcode.Trim":{"label":"Trim","type":"checkbox","defaultValue":true},"Barcode.WideBarRatio":{"label":"WideBarRatio","type":"number","defaultValue":2}}},"UPC-A":{"label":"UPC-A","fields":{"Barcode.CalcCheckSum":{"label":"CalcCheckSum","type":"checkbox","defaultValue":true},"Barcode.Color":{"label":"Color","type":"color","defaultValue":"#000000"},"Barcode.Trim":{"label":"Trim","type":"checkbox","defaultValue":true},"Barcode.WideBarRatio":{"label":"WideBarRatio","type":"number","defaultValue":2}}},"UPC-E0":{"label":"UPC-E0","fields":{"Barcode.CalcCheckSum":{"label":"CalcCheckSum","type":"checkbox","defaultValue":true},"Barcode.Color":{"label":"Color","type":"color","defaultValue":"#000000"},"Barcode.Trim":{"label":"Trim","type":"checkbox","defaultValue":true},"Barcode.WideBarRatio":{"label":"WideBarRatio","type":"number","defaultValue":2}}},"UPC-E1":{"label":"UPC-E1","fields":{"Barcode.CalcCheckSum":{"label":"CalcCheckSum","type":"checkbox","defaultValue":true},"Barcode.Color":{"label":"Color","type":"color","defaultValue":"#000000"},"Barcode.Trim":{"label":"Trim","type":"checkbox","defaultValue":true},"Barcode.WideBarRatio":{"label":"WideBarRatio","type":"number","defaultValue":2}}},"Supplement 2":{"label":"Supplement 2","fields":{"Barcode.CalcCheckSum":{"label":"CalcCheckSum","type":"checkbox","defaultValue":true},"Barcode.Color":{"label":"Color","type":"color","defaultValue":"#000000"},"Barcode.Trim":{"label":"Trim","type":"checkbox","defaultValue":true},"Barcode.WideBarRatio":{"label":"WideBarRatio","type":"number","defaultValue":2}}},"Supplement 5":{"label":"Supplement 5","fields":{"Barcode.CalcCheckSum":{"label":"CalcCheckSum","type":"checkbox","defaultValue":true},"Barcode.Color":{"label":"Color","type":"color","defaultValue":"#000000"},"Barcode.Trim":{"label":"Trim","type":"checkbox","defaultValue":true},"Barcode.WideBarRatio":{"label":"WideBarRatio","type":"number","defaultValue":2}}},"PDF417":{"label":"PDF417","fields":{"Barcode.AspectRatio":{"label":"AspectRatio","type":"number","defaultValue":0.5},"Barcode.CodePage":{"label":"CodePage","type":"number","defaultValue":437},"Barcode.Color":{"label":"Color","type":"color","defaultValue":"#000000"},"Barcode.Columns":{"label":"Columns","type":"number","defaultValue":0},"Barcode.CompactionMode":{"label":"CompactionMode","type":"select","collection":["Auto","Text","Numeric","Binary"],"defaultValue":"Auto"},"Barcode.ErrorCorrection":{"label":"ErrorCorrection","type":"select","collection":["Auto","Level0","Level1","Level2","Level3","Level4","Level5","Level6","Level7","Level8"],"defaultValue":"Auto"},"Barcode.PixelSize":{"label":"PixelSize","type":"text","defaultValue":"2, 8"},"Barcode.Rows":{"label":"Rows","type":"number","defaultValue":0}}},"Datamatrix":{"label":"Datamatrix","fields":{"Barcode.CodePage":{"label":"CodePage","type":"number","defaultValue":1252},"Barcode.Color":{"label":"Color","type":"color","defaultValue":"#000000"},"Barcode.Encoding":{"label":"Encoding","type":"select","collection":["Auto","Ascii","C40","Text","Base256","X12","Edifact"],"defaultValue":"Auto"},"Barcode.PixelSize":{"label":"PixelSize","type":"number","defaultValue":3},"Barcode.SymbolSize":{"label":"SymbolSize","type":"select","collection":["Auto","Size10x10","Size12x12","Size8x18","Size14x14","Size8x32","Size16x16","Size12x26","Size22x22","Size16x36","Size24x24","Size26x26","Size16x48","Size32x32","Size36x36","Size40x40","Size44x44","Size48x48","Size52x52","Size64x64","Size72x72","Size80x80","Size88x88","Size96x96","Size104x104","Size120x120","Size132x132","Size144x144"],"defaultValue":"Auto"}}},"QR Code":{"label":"QR Code","fields":{"Barcode.Color":{"label":"Color","type":"color","defaultValue":"#000000"},"Barcode.Encoding":{"label":"Encoding","type":"select","collection":["Auto","Ascii","C40","Text","Base256","X12","Edifact"],"defaultValue":"Auto"},"Barcode.ErrorCorrection":{"label":"ErrorCorrection","type":"select","collection":["L","M","H","Q"],"defaultValue":"L"},"Barcode.QuietZone":{"label":"QuietZone","type":"checkbox","defaultValue":true}}},"Plessey":{"label":"Plessey","fields":{"Barcode.CalcCheckSum":{"label":"CalcCheckSum","type":"checkbox","defaultValue":true},"Barcode.Color":{"label":"Color","type":"color","defaultValue":"#000000"},"Barcode.Trim":{"label":"Trim","type":"checkbox","defaultValue":true},"Barcode.WideBarRatio":{"label":"WideBarRatio","type":"number","defaultValue":2}}},"GS1-128 (UCC/EAN-128)":{"label":"GS1-128 (UCC/EAN-128)","fields":{"Barcode.AutoEncode":{"label":"AutoEncode","type":"checkbox","defaultValue":true},"Barcode.CalcCheckSum":{"label":"CalcCheckSum","type":"checkbox","defaultValue":true},"Barcode.Color":{"label":"Color","type":"color","defaultValue":"#000000"},"Barcode.Trim":{"label":"Trim","type":"checkbox","defaultValue":true},"Barcode.WideBarRatio":{"label":"WideBarRatio","type":"number","defaultValue":2}}},"Aztec":{"label":"Aztec","fields":{"Barcode.Color":{"label":"Color","type":"color","defaultValue":"#000000"},"Barcode.ErrorCorrectionPercent":{"label":"ErrorCorrectionPercent","type":"number","defaultValue":33}}},"Pharmacode":{"label":"Pharmacode","fields":{"Barcode.CalcCheckSum":{"label":"CalcCheckSum","type":"checkbox","defaultValue":true},"Barcode.Color":{"label":"Color","type":"color","defaultValue":"#000000"},"Barcode.Trim":{"label":"Trim","type":"checkbox","defaultValue":true},"Barcode.WideBarRatio":{"label":"WideBarRatio","type":"number","defaultValue":2},"Barcode.QuietZone":{"label":"QuietZone","type":"checkbox","defaultValue":true}}},"MaxiCode":{"label":"MaxiCode","fields":{"Barcode.Color":{"label":"Color","type":"color","defaultValue":"#000000"},"Barcode.Mode":{"label":"Mode","type":"number","defaultValue":4}}},"Intelligent Mail (USPS)":{"label":"Intelligent Mail (USPS)","fields":{"Barcode.CalcCheckSum":{"label":"CalcCheckSum","type":"checkbox","defaultValue":true},"Barcode.Color":{"label":"Color","type":"color","defaultValue":"#000000"},"Barcode.Trim":{"label":"Trim","type":"checkbox","defaultValue":true},"Barcode.WideBarRatio":{"label":"WideBarRatio","type":"number","defaultValue":2},"Barcode.QuietZone":{"label":"QuietZone","type":"checkbox","defaultValue":true}}}}')
}, function(t, e, n) {
    "use strict";
    var a = n(0),
        l = n.n(a),
        c = n(4),
        i = n(1),
        p = n(15),
        r = n(7),
        d = n(165),
        u = n(62),
        h = n(27);
    n(234), n(235);
    e.a = Object(r.a)(i.a, {
        _init: function(i) {
            function a(t, e) {
                void 0 !== t && (i.unlock(e), t && i.select(e))
            }

            function t(a) {
                var t = o.$color,
                    i = s._getSelected(),
                    e = "Fill.Color" === a ? i[0].getFillColor() : i[0].prop(a);
                Object(d.a)("color") ? (l()(".fr-reusable").css({
                    left: "40%",
                    top: "16%"
                }), t.trigger("click"), t.val(Object(u.a)(e))) : (t.spectrum({
                    color: Object(u.a)(e),
                    preferredFormat: "hex",
                    change: function() {
                        l()(".sp-container:not(.sp-hidden)").remove(), l()(this).trigger("change keyup")
                    }
                }), t.next().trigger("click"), l()(".sp-container:not(.sp-hidden)").css({
                    left: "40%",
                    top: "16%"
                })), t.off("change keyup").on("change keyup", function() {
                    for (var t = l()(this).val(), e = void 0, n = i.length - 1; 0 <= n; n -= 1)(e = i[n]).canModify() && e.canEdit() && (e.prop(a, t, !0), e.render())
                })
            }
            var e = this.bind,
                n = this.trigger,
                r = this.report = window.DSG.currentReport,
                o = this.head = window.DSG.head,
                s = this;
            i && (e("menu-fill-color", function() {
                t("Fill.Color")
            }), e("menu-border-color", function() {
                t("Border.Color")
            }), e("menu-text-fill", function() {
                t("TextFill.Color")
            }), e("menu-font-name", function(t) {
                n("font-name", t)
            }), e("menu-font-width", function(t) {
                n("font-width", t)
            }), e("menu-font-bold", function() {
                n("font-bold")
            }), e("menu-font-italic", function() {
                n("font-italic")
            }), e("menu-font-underline", function() {
                n("font-underline")
            }), e("menu-font-strikeout", function() {
                n("font-strikeout")
            }), e("menu-horz-align-left", function() {
                n("horz-align-left")
            }), e("menu-horz-align-center", function() {
                n("horz-align-center")
            }), e("menu-horz-align-right", function() {
                n("horz-align-right")
            }), e("menu-horz-align-justify", function() {
                n("horz-align-justify")
            }), e("menu-border-all", function() {
                l.a.each(s._getSelected(), function() {
                    this.applyForBorder("All"), this.render()
                })
            }), e("menu-border-none", function() {
                l.a.each(s._getSelected(), function() {
                    this.applyForBorder("None"), this.render()
                })
            }), e("menu-border-left", function() {
                l.a.each(s._getSelected(), function() {
                    this.applyForBorder("Left"), this.render()
                })
            }), e("menu-border-top", function() {
                l.a.each(s._getSelected(), function() {
                    this.applyForBorder("Top"), this.render()
                })
            }), e("menu-border-right", function() {
                l.a.each(s._getSelected(), function() {
                    this.applyForBorder("Right"), this.render()
                })
            }), e("menu-border-bottom", function() {
                l.a.each(s._getSelected(), function() {
                    this.applyForBorder("Bottom"), this.render()
                })
            }), e("menu-edit-border", function() {
                n("edit-border", r.getCurrentPage().bands.getSelectedComponents())
            }), e("update-menu", function() {
                var t, e, n;
                i.unselect(["horz-align-left", "horz-align-center", "horz-align-right", "horz-align-justify", "vert-align-top", "vert-align-center", "vert-align-bottom", "font-bold", "font-italic", "font-underline", "font-strikeout"]), i.lock(["copy", "cut", "paste", "undo", "redo", "bring-to-front", "send-to-back", "group", "text-fill", "horz-align-left", "horz-align-center", "horz-align-right", "horz-align-justify", "vert-align-top", "vert-align-center", "vert-align-bottom", "font-bold", "font-italic", "font-underline", "font-strikeout", "font-name", "font-width", "fill-color", "border-color", "border-style", "border-width", "edit-border"]), (t = r.getCurrentPage()).isCode() || (p.a.canUndo() && i.unlock("undo"), p.a.canRedo() && i.unlock("redo"), t.buffer.length && i.unlock("paste"), (e = (t.bands || t.components).getSelectedComponents()).length && i.unlock(["copy", "cut", "bring-to-front", "send-to-back", "edit-border"]), 1 < e.length && i.unlock(["group"]), 1 === e.length ? ((n = (e = e[0]).prop("HorzAlign")) && (i.unlock(["horz-align-left", "horz-align-center", "horz-align-right", "horz-align-justify"]), i.select("horz-align-" + n.toLowerCase())), (n = e.prop("VertAlign")) && (i.unlock(["vert-align-top", "vert-align-center", "vert-align-bottom"]), i.select("vert-align-" + n.toLowerCase())), a(e.attr("Font.Bold"), "font-bold"), a(e.attr("Font.Italic"), "font-italic"), a(e.attr("Font.Underline"), "font-underline"), a(e.attr("Font.Strikeout"), "font-strikeout"), (n = e.attr("Font.Name")) && (c.a.get("font-names").includes(n) || e.attr("notified_about_wrong_font") || (Object(h.a)("Font is not in the system", {
                    danger: !0,
                    trans: !0
                }), e.attr("notified_about_wrong_font", 1)), i.unlock("font-name"), i.set("font-name", n)), (n = e.attr("Font.Size")) && (i.unlock("font-width"), i.set("font-width", n)), e.canHaveProp("Appearance:TextFill:TextFill.Color") && i.unlock("text-fill")) : e = e.length ? null : r.getSelected(), function(t) {
                    var e, n, a = ["border-all", "border-none", "border-left", "border-top", "border-right", "border-bottom"];
                    if (!t || !t.canHaveProp("Appearance:Border:TopLine")) return i.lock(a), i.unselect(a);
                    if (i.unlock(a), i.unselect(a), e = t.prop("Border.Lines")) {
                        for (n = (e = e.split(/,[\s]?/)).length; n--;) i.select("border-" + e[n].toLowerCase());
                        ~e.indexOf("All") && i.select(["border-left", "border-top", "border-right", "border-bottom"])
                    } else i.select("border-none")
                }(e), e && (e.canHaveProp("Appearance:Fill:Fill.Color") && i.unlock("fill-color"), e.canHaveProp("Appearance:Border:Border.Color") && i.unlock("border-color"), e.canHaveProp("Appearance:Border:Border.Style") && (i.unlock("border-style"), i.set("border-style", e.prop("Border.Style"))), e.canHaveProp("Appearance:Border:Border.Width") && (i.unlock("border-width"), i.set("border-width", e.prop("Border.Width")))))
            }))
        },
        _getSelected: function() {
            var t, e = this.report.getCurrentPage();
            return e.isReportPage() && ((t = e.bands.getSelectedComponents()).length || (t = [t = (t = e.bands.getSelectedBand()) || e])), Array.prototype.slice.apply(t)
        }
    })
}, function(t, e, n) {
    "use strict";
    n(369);
    var a = n(0),
        i = n.n(a),
        r = (n(370), n(376), n(4)),
        o = n(2),
        s = function() {
            var t = 0 < arguments.length && void 0 !== arguments[0] ? arguments[0] : "&nbsp;";
            return '\n        <div class="fr-modal fade fr-modal-transform">\n            <div class="fr-modal-header">\n                <button type="button" class="close">\n                    <i class="fa fa-times"></i>\n                </button>\n                <h4 class="fr-modal-title">' + o.a.tr(t) + '</h4>\n            </div>\n            <div class="fr-modal-dialog">\n                <div class="fr-modal-content"></div>\n            </div>\n        </div>\n    '
        },
        l = 0,
        c = void 0;
    i()(document.body).on("keydown", function() {
        "function" == typeof c && c.apply(this, arguments)
    }), e.a = function(t, e) {
        function n() {
            "function" == typeof e.onClose && e.onClose.apply(this, arguments);
            var t = a.parent();
            a.remove(), l -= 1, t.trigger("popup-closed")
        }
        e = e || {};
        var a = i()(s(t));
        return l += 1, a.css({
            position: "relative",
            "z-index": l
        }), c = function(t) {
            27 !== t.keyCode || r.a.get("hotkeyProhibited") || (n(), a.trigger("closed"))
        }, e.addClass && a.addClass(e.addClass), !0 === e.danger && a.find(".fr-modal-header").addClass("fr-modal-header-danger"), e.dontMove || a.draggable({
            handle: ".fr-modal-header"
        }), a.close = n, a.on("click", ".close", n), a
    }
}, , , function(t, o, s) {
    "use strict";
    (function(a) {
        var e = s(4),
            i = s(13),
            t = s(7),
            n = s(17),
            r = s(58);
        o.a = Object(t.a)(i.a, {
            type: "Condition",
            defaultValue: "Value == 0",
            _init: function(t) {
                this._id = t || "cnd" + Object(n.a)(), this.defaultValues = {
                    ApplyTextFill: !0,
                    "TextFill.Color": "#000",
                    Visible: !0
                }, this.attr({
                    "Font.Name": e.a.get("default-font-name"),
                    "Font.Size": "10pt",
                    "Font.Bold": !1,
                    "Font.Italic": !1,
                    "Font.Underline": !1,
                    "Font.Strikeout": !1
                })
            },
            create: function() {
                var t = this.createObject(this);
                return t._init.apply(t, arguments), t
            },
            toXMLNET: function(t) {
                var n = this;
                return new Promise(function(e) {
                    i.a.toXMLNET.call(n, t).then(function(t) {
                        return t = a(t), Object(r.a)(t, n), e(t.get(0))
                    })
                })
            }
        })
    }).call(this, s(0))
}, , , , , , , , function(t, e, n) {
    "use strict";

    function a(t, e) {
        var n;
        t.files && t.files[0] && (n = new FileReader, "function" == typeof e && (n.onload = e), n.readAsDataURL(t.files[0]))
    }
    n.d(e, "a", function() {
        return a
    })
}, function(t, e, n) {
    "use strict";
    var a = n(0),
        r = n.n(a),
        i = n(13),
        o = n(87),
        s = n(32),
        l = n(17),
        c = n(1);
    e.a = i.a.createObject(i.a, {
        icon: "icon-053",
        _init: function(t) {
            this._id = "conn" + Object(l.a)(), this.type = t, this.dataSources = o.a.create(this), this.defaultValues = {
                CommandTimeout: 30,
                ConnectionStringExpression: "",
                LoginPrompt: !1
            }, this.prop("Name", "Connection"), this.editable = [null, function(t) {
                c.a.trigger("remove-connection", t)
            }]
        },
        create: function() {
            var t = this.createObject(this);
            return t._init.apply(t, arguments), t
        },
        isConnection: function() {
            return !0
        },
        fillMap: function() {
            this.fieldMap = s.a.factory(["Design:Name", "Design:Alias", "Design:Restrictions", "Data:CommandTimeout", "Data:ConnectionString", "Data:ConnectionStringExpression", "Data:LoginPrompt"]), this.mainFields = [
                ["Design:Name"]
            ]
        },
        remove: function() {
            this.attr("removed", !0)
        },
        restore: function() {
            this.attr("removed", !1)
        },
        toXMLNET: function(i) {
            var t = this;
            return new Promise(function(e) {
                var n, a = r()(i.parentNode.ownerDocument.createElement(t.type));
                i = Object.assign({}, i), t.eachProp(function(t, e) {
                    a.attr(t, e)
                }), i.parentNode = a[0], n = [], t.dataSources.eachEntity(function(t) {
                    n.push(t.toXMLNET(i))
                }), Promise.all(n).then(function(t) {
                    return t.forEach(function(t) {
                        t && a.append(t)
                    }), e(a[0])
                })
            })
        },
        toString: function() {
            return this.prop("Alias") || this.prop("Name")
        }
    })
}, function(t, e, n) {
    "use strict";
    var a = n(24),
        i = n(87),
        r = n(77),
        o = n(157),
        s = n(29),
        l = n(32),
        c = n(7),
        p = n(17),
        d = {
            default: ["Data:Parameters", "Data:SelectCommand", "Data:StoreData", "Data:TableName", "Design:Name", "Design:Alias", "Design:Restrictions", "Misc:ForceLoadData"],
            BusinessObjectDataSource: ["Design:Name", "Design:Alias", "Design:Restrictions", "Misc:ForceLoadData"]
        };
    e.a = Object(c.a)(r.a, {
        icon: "icon-tds",
        _init: function(t) {
            this._id = "tds" + Object(p.a)(), this.type = t, this.dataSources = i.a.create(this), this.columns = a.a.create(this), this.defaultValues = {
                ForceLoadData: !1,
                StoreData: !1
            }, this.prop({
                Name: "DataSource",
                DataType: s.a.get("System.Int32")
            }), this.unparsed = []
        },
        fillMap: function() {
            this.fieldMap = l.a.factory(d[this.type] || d.default), this.mainFields = [
                ["Design:Name"]
            ]
        },
        isDataSource: function() {
            return !0
        },
        createColumn: function() {
            return r.a.createColumn.call(this, o.a)
        }
    })
}, , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , function(t, e, i) {
    "use strict";

    function r(t) {
        return '\n        <div class="d-mc d-lib d-wb" data-info="' + t.info + '" data-component="' + t.type + '">\n            <div class="' + t.icon + '"></div>\n            <span class="fr-component-title">' + u.a.tr(t.title) + '</span>\n            <div class="aqw"></div>\n        </div>\n    '
    }
    var n, p, d, u, h, a, o, s, f, g, l, c, m, b, v, y, C, S, x, w, T, B, k, $, P, D;
    i.r(e), i(385), n = i(0), p = i.n(n), d = i(4), u = i(2), h = i(1), a = i(7), o = i(82), s = Object(a.a)({}, {
        defer: p.a.Deferred(),
        create: function() {
            return Object(a.a)(this)
        },
        init: function() {
            this.$node || (this.$node = p()("<div>"), this.$node.addClass("fr-menu"), this.$node.on("click dragstart", "img,a", function() {
                return !1
            }))
        },
        _select: function(t) {
            p()(".d-mc.menu-" + t, this.$node).addClass("d-wsb")
        },
        _isSelected: function(t) {
            return p()(".d-mc.menu-" + t, this.$node).hasClass("d-wsb")
        },
        _unselect: function(t) {
            p()(".d-mc.menu-" + t, this.$node).removeClass("d-wsb")
        },
        _has: function(t) {
            return p()(".d-mc.menu-" + t, this.$node).length
        },
        _lock: function(t) {
            var e = p()(".d-mc.menu-" + t, this.$node);
            e.length || (e = p()(t + "", this.$node)), e.attr("disabled", !0)
        },
        _unlock: function(t) {
            var e = p()(".d-mc.menu-" + t, this.$node);
            e.length || (e = p()(t + "", this.$node)), e.removeAttr("disabled")
        },
        _set: function(t, e) {
            var n = p()(".d-mc.menu-" + t, this.$node);
            n.length || (n = p()(t + "", this.$node)), n.val(e), e && n.is(".d-mc-auto-update") && !n.find(":selected").length && n.prepend('<option value="{0}">{1}</option>'.format(e, e))
        },
        _bindMenuListeners: function(t) {
            var e = ".d-mc[class*=menu-]:not([disabled])",
                a = Object(o.a)(function() {
                    return h.a.trigger("update-properties-panel")
                }, 500),
                i = function(t) {
                    return t.split(" ").filter(function(t) {
                        return ~t.indexOf("menu-")
                    })[0]
                };
            t.on("click", e, function(t) {
                var e = p()(this),
                    n = i(e.attr("class"));
                e.is("select") ? p()(".menu-helper").remove() : n && (h.a.trigger(n, e.val(), t, "click"), h.a.trigger("update-menu"), a())
            }).on("change", e, function(t) {
                var e = p()(this),
                    n = i(e.attr("class"));
                n && (h.a.trigger(n, e.val(), t, "change"), h.a.trigger("update-menu"), a())
            }).on("input", e, function(t) {
                var e = p()(this),
                    n = i(e.attr("class"));
                n && (h.a.trigger(n, e.val(), t, "input"), a())
            }).on("focus", e, function(t) {
                var e = p()(this),
                    n = i(e.attr("class"));
                d.a.set("hotkeyProhibited", !0), n && h.a.trigger(n + "-start", e.val(), t, "focus")
            }).on("blur", e, function(t) {
                var e = p()(this),
                    n = i(e.attr("class"));
                d.a.set("hotkeyProhibited", !1), n && h.a.trigger(n + "-end", e.val(), t, "end")
            })
        },
        isReady: function() {
            return this.defer.promise()
        },
        lock: function(t) {
            var e, n = 0;
            for (p.a.isArray(t) || (t = [t]), e = t.length; n < e; n += 1) this._lock(t[n])
        },
        unlock: function(t) {
            var e, n = 0;
            for (p.a.isArray(t) || (t = [t]), e = t.length; n < e; n += 1) this._unlock(t[n])
        },
        set: function(t, e) {
            var n, a = 0;
            for (p.a.isArray(t) || (t = [t]), n = t.length; a < n; a += 1) this._set(t[a], e)
        },
        select: function(t) {
            var e, n = 0;
            for (p.a.isArray(t) || (t = [t]), e = t.length; n < e; n += 1) this._select(t[n])
        },
        unselect: function(t) {
            var e, n = 0;
            for (p.a.isArray(t) || (t = [t]), e = t.length; n < e; n += 1) this._unselect(t[n])
        },
        unselectAll: function() {
            p()(".d-wsb", this.$node).removeClass("d-wsb")
        },
        isSelected: function(t) {
            return this._isSelected(t)
        },
        has: function(t) {
            return this._has(t)
        },
        lockAll: function() {
            p()(".d-mc", this.$node).attr("disabled", !0)
        },
        getHeight: function() {
            return this.$node.outerHeight(!0)
        }
    }), f = i(21), g = function() {
        return '\n        <div class="topbar">\n          <ul class="fr-nav fr-nav-tabs">\n            <li class="m-item active">\n              <a class="d-tab" data-tab="home">\n                <i class="fa fa-home"></i>\n                <span>' + u.a.tr("Ribbon Home") + '</span>\n              </a>\n            </li>\n\n            <li class="m-item">\n              <a class="d-tab" data-tab="report">\n                <span>' + u.a.tr("Menu Report") + '</span>\n              </a>\n            </li>\n\n            <li class="m-item">\n              <a class="d-tab" data-tab="layout">\n                <span>' + u.a.tr("Toolbar Layout") + '</span>\n              </a>\n            </li>\n\n            <li class="m-item">\n              <a class="d-tab" data-tab="view">\n                <span>' + u.a.tr("Menu View") + '</span>\n              </a>\n            </li>\n\n            <li class="m-item">\n              <a class="d-tab" data-tab="components">\n                <span>' + u.a.tr("Components") + '</span>\n              </a>\n            </li>\n\n            <li class="m-item">\n              <a class="d-tab" data-tab="bands">\n                <span>' + u.a.tr("Objects Bands") + '</span>\n              </a>\n            </li>\n\n            <li class="pull-right">\n              <span class="fr-navbar-brand">' + u.a.tr("HTML5Designer") + '</span>\n            </li>\n          </ul>\n        </div>\n\n        <div class="fr-tab-content">\n          <div class="fr-tabs-container"></div>\n\n          <div class="helper-buttons">\n            <a class="fullscreen d-wb">\n              <i class="fa fa-arrows-alt"></i>\n            </a>\n\n            <a class="show-helper d-wb">\n              <i class="fa fa-info"></i>\n            </a>\n\n            <a class="hide-menu d-wb">\n              <i class="fa fa-chevron-up"></i>\n            </a>\n          </div>\n        </div>\n    '
    }, l = function() {
        return '\n        <div class="fr-tab-pane active" data-tab="home">\n            <ul class="fr-tab-pane-body">\n                <li class="fr-group">\n                    <div class="fr-container">\n                        <div class="fr-group-body">\n                            <div class="fr-group-body-subcontainer d-two-lines d-vertical">\n                                <a class="d-mc menu-undo d-sb d-wb" data-info="' + u.a.tr("Toolbar Standard Undo") + '">\n                                    <i class="fa fa-undo"></i>\n                                </a>\n                                <a class="d-mc menu-redo d-sb d-wb" data-info="' + u.a.tr("Toolbar Standard Redo") + '">\n                                    <i class="fa fa-repeat"></i>\n                                </a>\n                            </div>\n                        </div>\n                        <div class="fr-group-label">' + u.a.tr("Menu Edit Undo") + '</div>\n                    </div>\n                    <div class="fr-separator"></div>\n                </li>\n\n                <li class="fr-group">\n                    <div class="fr-container">\n                        <div class="fr-group-body">\n                            <div class="fr-group-body-subcontainer d-one-line d-vertical">\n                                <a class="d-mc menu-paste d-lb d-wb">\n                                    <div class="icon-007"></div>\n                                    <span>' + u.a.tr("Menu Edit Paste") + '</span>\n                                </a>\n                            </div>\n                            <div class="fr-group-body-subcontainer d-three-lines d-vertical">\n                                <a class="d-mc menu-copy d-sb d-wb">\n                                    <div class="icon-006 pull-left"></div>\n                                    <span>' + u.a.tr("Menu Edit Copy") + '</span>\n                                </a>\n                                <a class="d-mc menu-cut d-sb d-wb">\n                                    <div class="icon-005 pull-left"></div>\n                                    <span>' + u.a.tr("Menu Edit Cut") + '</span>\n                                </a>\n                                <a class="d-mc menu-remove d-sb d-wb">\n                                    <div class="icon-012 pull-left"></div>\n                                    <span>' + u.a.tr("Menu Edit Delete") + '</span>\n                                </a>\n                            </div>\n                        </div>\n                        <div class="fr-group-label">' + u.a.tr("Clipboard") + '</div>\n                  </div>\n                  <div class="fr-separator"></div>\n                </li>\n                <li class="fr-group">\n                    <div class="fr-container">\n                        <div class="fr-group-body">\n                            <div class="fr-group-body-subcontainer d-one-line d-horizontal">' + function() {
            var e = "",
                t = d.a.get("font-names");
            return t.forEach(function(t) {
                e += '<option value="' + t + '">' + t + "</option>"
            }), '\n        <select class="d-mc menu-font-name d-sb d-mc-auto-update" data-info="' + u.a.tr("Toolbar Text Name") + '">\n            ' + e + "\n        </select>\n    "
        }() + '</div>\n                            <div class="fr-group-body-subcontainer d-one-line d-horizontal">\n                                <a class="d-mc menu-font-bold d-sb d-wb pull-left" data-info="' + u.a.tr("Toolbar Text Bold") + '">\n                                    <div class="icon-020"></div>\n                                </a>\n                                <a class="d-mc menu-font-italic d-sb d-wb pull-left" data-info="' + u.a.tr("Toolbar Text Italic") + '">\n                                    <div class="icon-021"></div>\n                                </a>\n                                <a class="d-mc menu-font-underline d-sb d-wb pull-left" data-info="' + u.a.tr("Toolbar Text Underline") + '">\n                                    <div class="icon-022"></div>\n                                </a>\n                                <a class="d-mc menu-font-strikeout d-sb d-wb pull-left" data-info="' + u.a.tr("Toolbar Text Strikeout") + '">\n                                    <div class="icon-strikeout icon-16by16"></div>\n                                </a>\n                                ' + function() {
            var e = "",
                t = d.a.get("font-sizes");
            return t.forEach(function(t) {
                e += '<option value="' + t + 'pt">' + t + "</option>"
            }), '\n        <select class="d-mc menu-font-width d-sb" data-info="' + u.a.tr("Toolbar Text Size") + '">\n            ' + e + "\n        </select>\n    "
        }() + '\n                            </div>\n                        </div>\n                        <div class="fr-group-label">' + u.a.tr("HighlightEditor Font") + '</div>\n                    </div>\n                    <div class="fr-separator"></div>\n                </li>\n                <li class="fr-group">\n                    <div class="fr-container">\n                        <div class="fr-group-body">\n                            <div class="fr-group-body-subcontainer d-one-line d-horizontal">\n                                <a class="d-mc menu-text-fill d-sb d-wb" data-info="' + u.a.tr("Toolbar Text Color") + '">\n                                    <div class="icon-023"></div>\n                                </a>\n                                <a class="d-mc menu-vert-align-top d-sb d-wb" data-info="' + u.a.tr("Toolbar Text Top") + '">\n                                    <div class="icon-029"></div>\n                                </a>\n                                <a class="d-mc menu-vert-align-center d-sb d-wb" data-info="' + u.a.tr("Toolbar Text Middle") + '">\n                                    <div class="icon-030"></div>\n                                </a>\n                                <a class="d-mc menu-vert-align-bottom d-sb d-wb" data-info="' + u.a.tr("Toolbar Text Bottom") + '">\n                                    <div class="icon-031"></div>\n                                </a>\n                            </div>\n                            <div class="fr-group-body-subcontainer d-one-line d-horizontal">\n                                <a class="d-mc menu-horz-align-left d-sb d-wb" data-info="' + u.a.tr("Toolbar Text Left") + '">\n                                    <div class="icon-025"></div>\n                                </a>\n                                <a class="d-mc menu-horz-align-center d-sb d-wb" data-info="' + u.a.tr("Toolbar Text Center") + '">\n                                    <div class="icon-026"></div>\n                                </a>\n                                <a class="d-mc menu-horz-align-right d-sb d-wb" data-info="' + u.a.tr("Toolbar Text Right") + '">\n                                    <div class="icon-027"></div>\n                                </a>\n                                <a class="d-mc menu-horz-align-justify d-sb d-wb" data-info="' + u.a.tr("Toolbar Text Justify") + '">\n                                    <div class="icon-028"></div>\n                                </a>\n                            </div>\n                        </div>\n\n                        <div class="fr-group-label">' + u.a.tr("Alignment") + '</div>\n                    </div>\n                    <div class="fr-separator"></div>\n                </li>\n                <li class="fr-group">\n                    <div class="fr-container">\n                        <div class="fr-group-body">\n                            <div class="fr-group-body-subcontainer d-one-line d-horizontal">\n                                <a class="d-mc menu-border-all d-sb d-wb" data-info="' + u.a.tr("Toolbar Border All") + '">\n                                    <div class="icon-036"></div>\n                                </a>\n                                <a class="d-mc menu-border-none d-sb d-wb" data-info="' + u.a.tr("Toolbar Border None") + '">\n                                    <div class="icon-037"></div>\n                                </a>\n                                <a class="d-mc menu-border-left d-sb d-wb" data-info="' + u.a.tr("Toolbar Border Left") + '">\n                                    <div class="icon-034"></div>\n                                </a>\n                                <a class="d-mc menu-border-top d-sb d-wb" data-info="' + u.a.tr("Toolbar Border Top") + '">\n                                    <div class="icon-032"></div>\n                                </a>\n                                <a class="d-mc menu-border-right d-sb d-wb" data-info="' + u.a.tr("Toolbar Border Right") + '">\n                                    <div class="icon-035"></div>\n                                </a>\n                                <a class="d-mc menu-border-bottom d-sb d-wb" data-info="' + u.a.tr("Toolbar Border Bottom") + '">\n                                    <div class="icon-033"></div>\n                                </a>\n                                <a class="d-mc menu-edit-border d-sb d-wb" data-info="' + u.a.tr("Toolbar Border Props") + '">\n                                    <div class="icon-040"></div>\n                                </a>\n                            </div>\n                            <div class="fr-group-body-subcontainer d-one-line d-horizontal">\n                                <a class="d-mc menu-fill-color d-sb d-wb pull-left" data-info="' + u.a.tr("Toolbar Border FillColor") + '">\n                                    <div class="icon-038"></div>\n                                </a>\n                                <a class="d-mc menu-border-color d-sb d-wb pull-left" data-info="' + u.a.tr("Toolbar Border LineColor") + '">\n                                    <div class="icon-039"></div>\n                                </a>\n                                ' + function() {
            var e = "";
            return Object.keys(d.a.get("dasharrays")).forEach(function(t) {
                e += '<option value="' + t + '">' + t + "</option>"
            }), '\n        <select class="d-mc menu-border-style d-sb" data-info="' + u.a.tr("Toolbar Border Style") + '">\n            ' + e + "\n        </select>\n    "
        }() + '\n                                <select class="d-mc menu-border-width d-sb" data-info="' + u.a.tr("Toolbar Border Width") + '">\n                                    <option value="1">1</option>\n                                    <option value="2">2</option>\n                                    <option value="3">3</option>\n                                    <option value="4">4</option>\n                                    <option value="5">5</option>\n                                    <option value="6">6</option>\n                                    <option value="7">7</option>\n                                    <option value="8">8</option>\n                                    <option value="9">9</option>\n                                    <option value="10">10</option>\n                                    <option value="11">11</option>\n                                    <option value="12">12</option>\n                                </select>\n                            </div>\n                        </div>\n                        <div class="fr-group-label">' + u.a.tr("Toolbar Border") + '</div>\n                    </div>\n                    <div class="fr-separator"></div>\n                </li>\n                <li class="fr-group">\n                    <div class="fr-container">\n                        <div class="fr-group-body">\n                            <div class="fr-group-body-subcontainer d-two-lines d-vertical">\n                                <a class="d-mc menu-bring-to-front d-sb d-wb" data-info="' + u.a.tr("Layout BringToFront") + '">\n                                    <div class="icon-014"></div>\n                                </a>\n                                <a class="d-mc menu-send-to-back d-sb d-wb" data-info="' + u.a.tr("Layout SendToBack") + '">\n                                    <div class="icon-015"></div>\n                                </a>\n                            </div>\n                        </div>\n                        <div class="fr-group-label">&nbsp;</div>\n                    </div>\n                    <div class="fr-separator"></div>\n                </li>\n                <li class="fr-group d-extra fr-hidden">\n                    <div class="fr-container">\n                        <div class="fr-group-body">\n                            <div class="fr-group-body-subcontainer d-one-line d-horizontal"></div>\n                            <div class="fr-group-body-subcontainer d-one-line d-horizontal"></div>\n                        </div>\n                        <div class="fr-group-label">' + u.a.tr("Extra") + '</div>\n                    </div>\n                    <div class="fr-separator"></div>\n                </li>\n            </ul>\n        </div>\n    '
    }, c = function() {
        return '\n        <div class="fr-tab-pane" data-tab="layout">\n            <ul class="fr-tab-pane-body">\n                <li class="fr-group">\n                    <div class="fr-container">\n                        <div class="fr-group-body">\n                            <div class="fr-group-body-subcontainer d-one-line d-vertical">\n                                <a class="d-mc menu-group d-lb d-wb">\n                                    <div class="icon-Group"></div>\n                                    <span>' + u.a.tr("Edit Group") + '</span>\n                                </a>\n                            </div>\n                            <div class="fr-group-body-subcontainer d-one-line d-vertical">\n                                <a class="d-mc menu-ungroup d-lb d-wb">\n                                    <div class="icon-Ungroup"></div>\n                                    <span>' + u.a.tr("Edit Ungroup") + '</span>\n                                </a>\n                            </div>\n                        </div>\n                        <div class="fr-group-label">' + u.a.tr("Toolbar Layout") + '</div>\n                    </div>\n                    <div class="fr-separator"></div>\n                </li>\n            </ul>\n        </div>\n    '
    }, m = i(14), b = function() {
        return '\n        <div class="fr-tab-pane" data-tab="view">\n            <ul class="fr-tab-pane-body">\n                <li class="fr-group">\n                    <div class="fr-container">\n                        <div class="fr-group-body">\n                            <div class="fr-group-body-subcontainer d-one-line d-vertical">\n                                <a class="d-mc menu-grid d-lb d-wb">\n                                    <div class="icon-ViewGridlines"></div>\n                                    <span>' + u.a.tr("Menu View Grid") + '</span>\n                                </a>\n                            </div>\n                            <div class="fr-group-body-subcontainer d-two-lines d-vertical">\n                                ' + function() {
            var a = "";
            return Object.entries(m.a.all()).forEach(function(t) {
                var e = t[0],
                    n = t[1];
                a += '<option value="' + e + '">' + u.a.tr(n) + "</option>"
            }), '\n        <select class="d-mc menu-units d-sb" data-info="' + u.a.tr("Menu View Units") + '">\n            ' + a + "\n        </select>\n    "
        }() + '\n                                <input class="d-mc menu-snap-size d-sb" min="0" step="0.1" type="number" data-info="' + u.a.tr("SnapSize") + '"/>\n                            </div>\n                        </div>\n                        <div class="fr-group-label">' + u.a.tr("Menu View") + '</div>\n                    </div>\n                    <div class="fr-separator"></div>\n                </li>\n                <li class="fr-group">\n                    <div class="fr-container">\n                        <div class="fr-group-body">\n                            \n        <div class="fr-group-body-subcontainer d-one-line d-vertical">\n            <a class="d-mc menu-guides d-lb d-wb">\n                <div class="icon-ViewGuides"></div>\n                <span>' + u.a.tr("Menu View Guides") + '</span>\n            </a>\n        </div>\n    \n                            <div class="fr-group-body-subcontainer d-one-line d-vertical">\n                                ' + function() {
            var a = "",
                i = u.a.getCurrentLang();
            return Object.entries(d.a.get("languages")).forEach(function(t) {
                var e = t[0],
                    n = t[1];
                a += '<option value="' + e + '" ' + (i === e ? "selected" : "") + ">" + u.a.tr(n) + "</option>"
            }), '<select class="d-mc menu-language d-sb" data-info="' + u.a.tr("Menu SelectLanguage") + '" l10n>' + a + "</select>"
        }() + '\n                            </div>\n                        </div>\n                        <div class="fr-group-label">&nbsp;</div>\n                    </div>\n                    <div class="fr-separator"></div>\n                </li>\n            </ul>\n        </div>\n    '
    }, v = function(t) {
        return '\n        <div class="fr-tab-pane" data-tab="components">\n            <ul class="fr-tab-pane-body">\n                <li class="fr-group">\n                    <div class="fr-container">\n                        <div class="fr-group-body">' + function(t) {
            var n = "",
                a = {};
            return t.forEach(function(t) {
                if (!t.disabled)
                    if (t.groupType) {
                        a[t.groupType] || (n += '\n                    <div class="d-mc d-lib d-wb fr-component-group">\n                        <div class="' + t.icon + '"></div>\n                        <span class="fr-component-title">' + u.a.tr(t.groupType) + '</span>\n                        <div class="fr-component-group-switcher"></div>\n                        <div class="fr-component-group-list fr-hidden">%' + t.groupType + "%</div>\n                    </div>\n                ");
                        var e = a[t.groupType] || "";
                        e += r(t), a[t.groupType] = e
                    } else n += r(t)
            }), Object.keys(a).forEach(function(t) {
                n = n.replace("%" + t + "%", a[t])
            }), '<div class="fr-group-body-subcontainer d-horizontal">' + n + "</div>"
        }(t) + '</div>\n                        <div class="fr-group-label">' + u.a.tr("Components") + "</div>\n                    </div>\n                </li>\n            </ul>\n        </div>\n    "
    }, y = function(t) {
        return '\n        <div class="fr-tab-pane" data-tab="bands">\n            <ul class="fr-tab-pane-body">\n                ' + (d.a.get("sort-bands") ? '<li class="fr-group">\n                        <div class="fr-container">\n                            <div class="fr-group-body fr-group-body--no-margin-bottom">\n                                <div class="d-two-lines d-vertical">\n                                    <a class="d-mc menu-sort-band-up d-sb d-wb">\n                                        <div class="icon-208"></div>\n                                    </a>\n                                    <a class="d-mc menu-sort-band-down d-sb d-wb">\n                                        <div class="icon-209"></div>\n                                    </a>\n                                </div>\n                            </div>\n                            <div class="fr-group-label">&nbsp;</div>\n                        </div>\n                        <div class="fr-separator"></div>\n                    </li>' : "") + '\n                <li class="fr-group">\n                    <div class="fr-container">\n                        <div class="fr-group-body">' + function(t) {
            var e = "",
                n = void 0;
            return t.forEach(function(t) {
                t.disabled || (n = u.a.tr(t.info), e += '\n            <a class="d-mc d-lib d-wb" ' + (n ? 'data-info="' + n + '" ' : "") + ' data-band="' + t.type + '">\n                <div class="' + t.icon + '"></div>\n                <span class="fr-band-title">' + u.a.tr(t.title) + "</span>\n            </a>\n        ")
            }), '<div class="fr-group-body-subcontainer d-horizontal">' + e + "</div>"
        }(t) + '</div>\n                        <div class="fr-group-label">' + u.a.tr("Objects Bands") + "</div>\n                    </div>\n                </li>\n            </ul>\n        </div>\n    "
    }, C = function() {
        return '\n        <div class="fr-tab-pane" data-tab="report">\n            <ul class="fr-tab-pane-body">\n                <li class="fr-group">\n                    <div class="fr-container">\n                        <div class="fr-group-body"><div class="fr-group-body-subcontainer d-one-line d-vertical">\n                                <a class="d-mc menu-preview d-lb d-wb">\n                                    <div class="icon-report"></div>\n                                    <span>' + u.a.tr("Preview") + '</span>\n                                </a>\n                            </div> <div class="fr-group-body-subcontainer d-one-line d-vertical">\n                                <a class="d-mc menu-save d-lb d-wb">\n                                    <div class="icon-002"></div>\n                                    <span>' + u.a.tr("Save") + '</span>\n                                </a>\n                            </div>\n                        </div>\n                        <div class="fr-group-label">' + u.a.tr("Menu Report") + '</div>\n                    </div>\n                    <div class="fr-separator"></div>\n                </li>\n                <li class="fr-group">\n                    <div class="fr-container">\n                        <div class="fr-group-body">' + (i.c[12] ? '\n            <div class="fr-group-body-subcontainer d-vertical d-three-lines">\n                <a class="d-mc menu-new-report-page  d-sb d-wb">\n                    <div class="icon-010 pull-left"></div>\n                    <span>' + u.a.tr("Standard NewPage") + '</span>\n                </a>\n                <a class="d-mc menu-new-dialog d-sb d-wb">\n                    <div class="icon-011 pull-left"></div>\n                    <span>' + u.a.tr("Standard NewDialog") + '</span>\n                </a>\n                <a class="d-mc menu-delete-page d-sb d-wb">\n                    <div class="icon-012 pull-left"></div>\n                    <span>' + u.a.tr("Menu Edit DeletePage") + "</span>\n                </a>\n            </div>\n        " : '\n            <div class="fr-group-body-subcontainer d-vertical d-two-lines">\n              <a class="d-mc menu-new-report-page  d-sb d-wb">\n                <div class="icon-010 pull-left"></div>\n                <span>' + u.a.tr("Standard NewPage") + '</span>\n              </a>\n              <a class="d-mc menu-delete-page d-sb d-wb">\n                <div class="icon-012 pull-left"></div>\n                <span>' + u.a.tr("Menu Edit DeletePage") + "</span>\n              </a>\n            </div>\n        ") + '</div>\n                        <div class="fr-group-label">' + u.a.tr("PrinterSetup Numbers") + '</div>\n                    </div>\n                    <div class="fr-separator"></div>\n                </li>\n            </ul>\n        </div>\n    '
    }, S = 150, x = Object(a.a)(s, {
        homeTab: function() {
            var t = p()(l());
            return this._bindMenuListeners(t), this.$extra = p()(".d-extra", t), this.$extraFirstLine = p()(".fr-group-body-subcontainer:first", this.$extra), this.$extraSecondLine = p()(".fr-group-body-subcontainer:last", this.$extra), t
        },
        layoutTab: function() {
            var t = p()(c());
            return this._bindMenuListeners(t), t
        },
        viewTab: function() {
            var t = p()(b());
            return this._bindMenuListeners(t), t
        },
        componentsTab: function(t) {
            var e, n = this.$navTabs.find('[data-tab="components"]'),
                a = [];
            if (t && t.isDialog() ? (a.push(i(106).default), a.push(i(68).default), a.push(i(107).default), a.push(i(108).default), a.push(i(63).default), a.push(i(109).default), a.push(i(110).default), a.push(i(111).default), a.push(i(112).default), a.push(i(113).default)) : (a.push(i(25).default), a.push(i(143).default), a.push(i(132).default), a.push(i(115).default), a.push(i(104).default), a.push(i(33).default), a.push(i(105).default), a.push(i(153).default), a.push(i(144).default), a.push(i(145).default), a.push(i(146).default), a.push(i(147).default), a.push(i(148).default), a.push(i(133).default), a.push(i(149).default), a.push(i(150).default), a.push(i(151).default), a.push(i(86).default), a.push(i(152).default)), e = void 0, a.length) {
                if (t && t.isCode()) return n.attr("disabled", !0), null;
                a = a.sort(function(t, e) {
                    return t.pos - e.pos
                }), e = p()(v(a)), n.css("display", ""), e.on("click", ".fr-component-group", function(t) {
                    var e, n = p()(t.currentTarget),
                        a = n.find(".fr-component-group-list");
                    a.is(".fr-hidden") ? (a.removeClass("fr-hidden"), n.addClass("fr-component-group-active"), e = n.get(0).getBoundingClientRect(), a.css({
                        top: e.top + e.height - 10,
                        left: e.left - a.width() / 2 + e.width / 2 - 8
                    })) : (a.addClass("fr-hidden"), n.removeClass("fr-component-group-active"))
                })
            } else n.addClass("fr-hidden"), e = p()("<div>");
            return e
        },
        bandsTab: function(t) {
            var e, n = this.$navTabs.find('[data-tab="bands"]'),
                a = [];
            if (a.push(i(101).default), a.push(i(217).default), a.push(i(102).default), a.push(i(103).default), a.push(i(218).default), a.push(i(219).default), a.push(i(220).default), a.push(i(83).default), a.push(i(221).default), a.push(i(130).default), a.push(i(177).default), a.push(i(131).default), a.push(i(222).default), e = void 0, d.a.get("add-bands") && a.length) {
                if (t && !t.isReportPage()) return n.attr("disabled", !0), null;
                a = a.sort(function(t, e) {
                    return t.pos - e.pos
                }), (e = p()(y(a))).on("click", ".d-mc:not([disabled])", function() {
                    var t = p()(this);
                    n.css("display", ""), t.is("[data-band]") && h.a.trigger("add-band", t.data("band"))
                })
            } else n.addClass("fr-hidden"), e = p()("<div>");
            return this._bindMenuListeners(e), e
        },
        reportTab: function() {
            var t = p()(C());
            return this._bindMenuListeners(t), t
        },
        update: function(t) {
            var e, n, a, i, r, o, s, l = this,
                c = p()(g());
            this.$node.empty().append(c), this.$tabContent = p()(".fr-tab-content", this.$node), this.$navTabs = p()(".fr-nav-tabs", this.$node), this.$tabsContainer = p()(".fr-tabs-container", this.$tabContent), e = this.homeTab(t), n = this.reportTab(t), a = this.layoutTab(t), i = this.viewTab(t), r = this.componentsTab(t), o = this.bandsTab(t), s = this.currentTab || d.a.get("default-tab-menu"), this.$tabsContainer.append(e, n, a, i, r, o), this.$navTabs.on("click", ".d-tab:not([disabled])", function(t) {
                var e = p()(t.currentTarget);
                if (!e.is("a[data-tab]")) return !1;
                l.$navTabs.find(".active").removeClass("active"), e.parent().addClass("active"), l.currentTab = e.data("tab"), l.$tabContent.find(".fr-tab-pane.active").removeClass("active"), l.$tabContent.find(".fr-tab-pane[data-tab=" + l.currentTab + "]").addClass("active"), l.$tabContent.slideDown(S, function() {
                    h.a.trigger("align-workspace")
                })
            }), s && (s = this.$navTabs.find("a[data-tab=" + s + "]")).length && s.trigger("click"), this.$tabContent.on("click", ".hide-menu", function() {
                l.$tabContent.slideUp(S, function() {
                    return h.a.trigger("align-workspace")
                }), l.$navTabs.find(".active").removeClass("active")
            }), this.$tabContent.on("click", ".show-helper", function() {
                return h.a.trigger("show-hotkey-helper")
            }), this.$tabContent.on("click", ".fullscreen", function() {
                return h.a.trigger("toogle-fullscreen")
            }), this.$tabContent.on("mouseenter", ":not([disabled])[data-info]", function(t) {
                var e, n = p()(t.currentTarget),
                    a = u.a.tr(n.data("info"));
                return !f.a && (l.removeHelpers(), a && a.length && !1 !== d.a.get("showHelpers") ? ((e = p()("<div>")).addClass("menu-helper"), e.css({
                    left: n.offset().left,
                    top: l.$node.height() - 1
                }), e.html(a), void l.$node.append(e)) : null)
            }), this.$tabContent.on("mouseleave", ":not([disabled])[data-info]", function() {
                return l.removeHelpers()
            }), this.defer.resolve(this)
        },
        removeHelpers: function() {
            p()(".menu-helper", this.$node).remove()
        }
    }), w = function(t) {
        var e = t.band,
            n = t.component,
            a = t.amount,
            i = t.scaleValue;
        return "\n        <div>\n            " + function(t) {
            var e = "";
            return (t || []).forEach(function(t) {
                e += '\n            <a data-page="' + t._id + '" ' + (t.isCode() && t.isRestricted() ? "disabled" : "") + ' class="show-page ' + (t.attr("isHidden") ? "" : "active") + '">\n              <div class="' + (t.attr("icon") || t.icon) + '"></div>\n              <span class="fr-page-title">' + t.toString() + "</span>\n            </a>\n        "
            }), '\n        <div class="switcher">' + e + "</div>\n    "
        }(t.allPages) + '\n            <div class="info-data">\n                <span>' + (e || "") + "</span>&nbsp;&nbsp;\n                <span>" + (n || "") + "</span>&nbsp;&nbsp;\n                " + (a ? "<span>" + u.a.tr("Properties NObjectsSelected", a) + "</span>" : "") + '\n            </div>\n            <div class="scale-page">\n                <a class="d-ic">\n                    <span class="scale-minus">-</span>\n                </a>\n                ' + (i ? '<input type="range" class="d-ic d-icr" value="' + i + '" min="0.1" max="2" step="0.1"/>' : "") + '\n                <a class="d-ic">\n                    <span class="scale-plus">+</span>\n                </a>\n            </div>\n        </div>\n    '
    }, T = function() {
        var n = p.a.Deferred(),
            a = this;
        this.$node = p()("<div>"), this.$node.addClass("fr-info"), this.$node.on("click", ".show-page", function() {
            h.a.trigger("activate-page-by-id", p()(this).data("page"))
        }), this.$node.on("input change", "input[type=range]", function() {
            var t = p()(this),
                e = parseFloat(t.val(), 10);
            h.a.trigger("scale-page", e)
        }), this.$node.on("click", ".scale-page a", function(t) {
            var e = p()(t.currentTarget),
                n = parseFloat(p()("input[type=range]", a.$node).val(), 10);
            return e.find(".scale-plus").length ? n += .1 : n -= .1, h.a.trigger("scale-page", n), !1
        }), this.update = function(t) {
            t = t || {};
            var e = p()(w({
                allPages: t["all-pages"],
                band: t.band,
                component: t.component,
                amount: t.amount,
                scaleValue: t.scale
            }));
            a.$node.html(e),
                function(t) {
                    p()("input[type=range]", a.$node).val(t || d.a.get("scale"))
                }(t.scale), n.resolve(a)
        }, this.getHeight = function() {
            return this.$node.outerHeight(!0)
        }, this.isReady = function() {
            return n.promise()
        }
    }, B = i(301), k = i(44), $ = i(160), P = i(6), D = function(s) {
        function i(t, e, n, a) {
            var i, r = k.a._generateGroupHeader(a, e);
            if (k.a._bindMainProps.call(this, n, a), r) return t.append(r.$main), a.expression && r.$header.append($.a.createExpression(a)), k.a._buildMap.call(this, a.fields, r.$body), r;
            a.control = $.a.getFor(a), (i = a.control.self.$control).is(".d-fc-json-field") ? (i.on("change keyup", "input", k.a._changeControl), i.on("focus", "input", k.a._focusOnControl), i.on("blur", "input", k.a._blurFromControl)) : (i.on("change keyup", k.a._changeControl), i.is(":not(select[multiple])") && (i.on("focus", k.a._focusOnControl), i.on("blur", k.a._blurFromControl))), t.append(a.control.$main),
                function(t) {
                    var e, n, a, i, r, o;
                    t.origin && (i = a = void 0, (n = (e = t.element).mainFields || []).length ? (a = Boolean(n[0] && n[0].includes(t.origin)), i = Boolean(n[1] && n[1].includes(t.origin))) : s.$extra.addClass("fr-hidden"), (a || i) && (r = t.control.self.$body.clone(!0, !0), (o = p()('input:not([type="button"]),select,textarea', r)).addClass("d-mc d-sb"), o.data("ref", t.control.self.$control), t.control.self.$control.data("ref", o), r.find("span").remove(), a ? (r.width(100 / e.mainFields[0].length - 1 + "%"), s.$extraFirstLine.append(r)) : i && (r.width(100 / e.mainFields[1].length - 1 + "%"), s.$extraSecondLine.append(r)), o.attr("data-info", u.a.tr(t.extraLabel || t.label)), s.$extra.removeClass("fr-hidden")))
                }(a)
        }
        Object(P.a)(k.a, {
            _buildMap: function(e, t, n) {
                var a = i.bind(this, t, n);
                e instanceof Array ? e.forEach(function(t, e) {
                    return a(e, t)
                }) : Object.keys(e).forEach(function(t) {
                    return a(t, e[t])
                })
            }
        })
    }, e.default = function() {
        var n = window.DSG.head,
            t = window.DSG.toolbar = x.create(),
            e = window.DSG.info = new T;
        return e.update(), t.update(), p.a.when(t.isReady(), e.isReady()).done(function(t, e) {
            return n.$controls.append(t.$node), n.$controls.append(e.$node), new D(t), B.a.create(t, e), !0
        })
    }
}, function(t, e, n) {
    "use strict";
    var a = n(0),
        i = n.n(a),
        r = n(4),
        o = n(3);
    e.a = function() {
        var t = i()(Object(o.a)("g", {
                id: "d-button-rect"
            })),
            e = i()(Object(o.a)("rect"));
        return e.css("fill", r.a.get("rectButtonFill")), e.attr({
            width: r.a.get("rectButtonWidth"),
            height: r.a.get("rectButtonHeight")
        }), t.append(e), t
    }
}, function(t, e, n) {
    "use strict";
    var a = n(0),
        r = n.n(a),
        o = n(4),
        s = n(3);
    e.a = function() {
        for (var t, e = 2, n = 0, a = o.a.get("resizingBandBlockWidth"), i = r()(Object(s.a)("g", {
                id: "d-resizing-band"
            })); 0 < e; e -= 1)(t = r()(Object(s.a)("line"))).attr({
            x1: n,
            y1: 5 * e,
            x2: a,
            y2: 5 * e
        }).css({
            stroke: "#666666",
            "stroke-width": 1,
            "stroke-dasharray": "3, 2"
        }), n += 0, a -= 0, i.append(t);
        return i
    }
}, function(t, e, n) {
    "use strict";
    var a = n(0),
        i = n.n(a),
        r = n(4),
        o = n(3);
    e.a = function() {
        var t = i()(Object(o.a)("g", {
                id: "d-button-circle"
            })),
            e = i()(Object(o.a)("circle"));
        return e.attr({
            cx: r.a.get("circleButtonWidth"),
            cy: r.a.get("circleButtonHeight")
        }), e.attr("r", r.a.get("circleButtonRadius")), e.css("fill", r.a.get("colors")["button-circle"]), t.append(e), t
    }
}, function(t, e, n) {
    "use strict";
    var a = n(0),
        i = n.n(a),
        r = n(4),
        o = n(3);
    e.a = function(t) {
        var e, n, a = r.a.get("grid");
        return t = t || "d-net", e = i()(Object(o.a)("pattern", {
            id: t,
            patternUnits: "userSpaceOnUse",
            width: a,
            height: a
        })), n = i()(Object(o.a)("line", {
            x1: 0,
            y1: 0,
            x2: a,
            y2: 0,
            stroke: "gray",
            "stroke-width": 1,
            fill: "none",
            style: "stroke-dasharray: 1, " + (a - 1) + ";"
        })), e.append(n), e
    }
}, function(t, e, n) {
    t.exports = n.p + "images/qr-code.5a294511bca71aaf0c361cf0e7b616f2.png"
}, function(t, e, n) {
    t.exports = n.p + "images/2-5-industrial.0ce2279a9505d8518e950ea2044a5bbf.png"
}, function(t, e, n) {
    t.exports = n.p + "images/2-5-interleaved.49574e86680a4a06b9e9ea80d8dcf192.png"
}, function(t, e, n) {
    t.exports = n.p + "images/2-5-matrix.8874a4280b6713f2d3410bcb6c1bd33b.png"
}, function(t, e, n) {
    t.exports = n.p + "images/codabar.831cf693f20bd2d375752cbfe4609e18.png"
}, function(t, e, n) {
    t.exports = n.p + "images/code-128.8146fb314a480db7d4fd88fbda6db3a0.png"
}, function(t, e, n) {
    t.exports = n.p + "images/code-39-extended.f2326ee4a18442b382d023988b4b1c1d.png"
}, function(t, e, n) {
    t.exports = n.p + "images/code-93.33d970650ebfa8a0be35a6c2ac2797f9.png"
}, function(t, e, n) {
    t.exports = n.p + "images/code-93-extended.fdbfddd85d3bf9535f9000132aa375aa.png"
}, function(t, e, n) {
    t.exports = n.p + "images/postnet.323847dc1f7a2c0eb33fb800b80d68a0.png"
}, function(t, e, n) {
    t.exports = n.p + "images/supplement-2.7164fa3a970b65f3154916394f0463fc.png"
}, function(t, e, n) {
    t.exports = n.p + "images/supplement-5.9ce77a5f0ab44256bf2525d1968554fc.png"
}, function(t, e, n) {
    t.exports = n.p + "images/pdf-417.745d2656d28531252d070ea3591506e1.png"
}, function(t, e, n) {
    t.exports = n.p + "images/datamatrix.8bdfa8b774d9dffec5ba73a979823f00.png"
}, function(t, e, n) {
    t.exports = n.p + "images/plessey.d9b51921793775e87defd909d7a0bd38.png"
}, function(t, e, n) {
    t.exports = n.p + "images/gs1-128.6d2f98ced41d7e11f0340530130de63f.png"
}, function(t, e, n) {
    t.exports = n.p + "images/aztec.af9dd92a357b9464429ee0af6bb8725c.png"
}, function(t, e, n) {
    t.exports = n.p + "images/maxi-code.5a96aff791085d828f4d90e04d8f02d7.png"
}, function(t, e, n) {
    t.exports = n.p + "images/usps.f2eab2fc355019c4ffbec0c80eed84f7.png"
}, function(t, e, n) {
    "use strict";
    (function(o) {
        var s = n(4),
            l = n(232),
            c = n(15),
            p = n(14),
            t = n(7),
            d = n(129);
        e.a = Object(t.a)(l.a, {
            _init: function(a, n) {
                var i, t, e = this.bind,
                    r = this.trigger;
                l.a._init.apply(this, arguments), i = this.report, t = this.head, this.unbind("activate-page"), this.unbind("show-page"), this.unbind("update-properties-panel"), e("activate-page", function(t) {
                    var e = i.getCurrentPage();
                    t.active(), e !== t && a.update(t)
                }), e("show-page", function(t) {
                    var e = i.getCurrentPage();
                    t !== e && (e.deactivate(), t.show(), a.update(t))
                }), e("align-workspace", function() {
                    i.attr({
                        Top: a.getHeight(),
                        Bottom: n.getHeight()
                    }), t.$workspace.css({
                        top: i.attr("Top"),
                        bottom: i.attr("Bottom"),
                        height: "auto"
                    })
                }), e("update-info", function() {
                    var t = i.getCurrentPage(),
                        e = {
                            report: i,
                            page: t,
                            scale: i.attr("data-scale")
                        };
                    e["all-pages"] = i.pages.all(i.code.isRestricted() ? ["ReportPage", "DialogPage"] : null), t.isReportPage() && (e.band = t.bands.getSelectedBand(), e.component = t.bands.getSelectedComponents()[0], e.amount = t.bands.getSelectedComponents().length), n.update(e)
                }), e("menu-grid", function() {
                    r("grid"), r("re-render")
                }), e("menu-guides", function() {
                    s.a.set("guides", !s.a.get("guides")), r("re-render")
                }), e("menu-group", function() {
                    r("group")
                }), e("menu-ungroup", function() {
                    r("ungroup")
                }), e("menu-snap-size", function(t) {
                    var e = s.a.get("grid");
                    t = parseFloat(t), t = isNaN(t) || t <= 0 ? e : p.a.toPx(t), s.a.set("grid", t), t !== e && i.updateFilters().updateDefs()
                }), e("menu-language", function(t) {
                    d.a.setItem("lang", t).then(function() {
                        return location.reload()
                    })
                }), e("menu-new-report-page", function() {
                    var t = i.createPage();
                    r("activate", t), c.a.push({
                        undo: function(t) {
                            i.removePage(t)
                        },
                        redo: function(t) {
                            t.render(), r("activate", t)
                        },
                        data: [t]
                    })
                }), e("menu-new-dialog", function() {
                    var t = i.createDialog();
                    r("activate", t), c.a.push({
                        undo: function(t) {
                            i.removePage(t)
                        },
                        redo: function(t) {
                            t.render(), r("activate", t)
                        },
                        data: [t]
                    }), o.when(i.createDialogDefaultSet(t)).done(function() {
                        return r("update-properties-panel", t)
                    })
                }), e("menu-delete-page", function(t) {
                    t = t || i.getCurrentPage(), i.removePage(t), c.a.push({
                        undo: function(t) {
                            t.render(), r("activate", t)
                        },
                        redo: function(t) {
                            return i.removePage(t)
                        },
                        data: [t]
                    })
                }), e("menu-units", function(t) {
                    p.a.setCurrent(t), r("update-properties-panel", i.getSelected()), i.getCurrentPage().render()
                }), e("menu-remove", function(t) {
                    r("remove", t)
                }), e("menu-undo", function() {
                    return r("undo")
                }), e("menu-redo", function() {
                    return r("redo")
                }), e("menu-preview", function() {
                    return r("preview")
                }), e("menu-save", function() {
                    return r("save")
                }), e("menu-copy", function() {
                    return r("copy")
                }), e("menu-cut", function() {
                    return r("cut")
                }), e("menu-paste", function() {
                    return r("paste", !0)
                }), e("menu-vert-align-top", function() {
                    return r("vert-align-top")
                }), e("menu-vert-align-center", function() {
                    return r("vert-align-center")
                }), e("menu-vert-align-bottom", function() {
                    return r("vert-align-bottom")
                }), e("menu-send-to-back", function() {
                    return r("send-to-back")
                }), e("menu-bring-to-front", function() {
                    return r("bring-to-front")
                }), e("menu-border-style", function(t) {
                    return r("border-style", t)
                }), e("menu-border-width", function(t) {
                    return r("border-width", t)
                }), e("menu-sort-band-up", function() {
                    return r("sort-band-up")
                }), e("menu-sort-band-down", function() {
                    return r("sort-band-down")
                }), e("update-menu", function() {
                    var t, e, n = i.getCurrentPage();
                    if (n.isCode()) return a.lockAll(), void a.unlock(["preview", "new-report-page", "save", "new-dialog"]);
                    a.unlock("new-page"), i.canNotBeSaved() ? a.lock("save") : a.unlock("save"), 1 < i.pages.count(["ReportPage", "DialogPage"]) ? a.unlock("delete-page") : a.lock("delete-page"), (t = i.getSelected()) && ("function" == typeof t.remove ? (a.unlock("remove"), t.isPage() && a.lock("remove")) : a.lock("remove")), a[i.attr("grid") ? "select" : "unselect"]("grid"), a[s.a.get("guides") ? "select" : "unselect"]("guides"), a.set("units", p.a.getCurrent()), a.set("snap-size", p.a.toUnit(s.a.get("grid"))), n.isReportPage() && (a.lock(["[data-band]", "sort-band-up", "sort-band-down"]), t = (e = n.bands).getSelectedBand(), e.findBy({
                        type: "ReportTitleBand"
                    }).length || a.unlock("[data-band=ReportTitleBand]"), e.findBy({
                        type: "PageHeaderBand"
                    }).length || a.unlock("[data-band=PageHeaderBand]"), e.findBy({
                        type: "ColumnHeaderBand"
                    }).length || a.unlock("[data-band=ColumnHeaderBand]"), e.findBy({
                        type: "ColumnFooterBand"
                    }).length || a.unlock("[data-band=ColumnFooterBand]"), e.findBy({
                        type: "ReportSummaryBand"
                    }).length || a.unlock("[data-band=ReportSummaryBand]"), e.findBy({
                        type: "PageFooterBand"
                    }).length || a.unlock("[data-band=PageFooterBand]"), e.findBy({
                        type: "OverlayBand"
                    }).length || a.unlock("[data-band=OverlayBand]"), a.unlock(["[data-band=DataBand]", "[data-band=GroupHeaderBand]"]), t && (t.canHaveChildren("ChildBand") && !t.has("ChildBand") && a.unlock("[data-band=ChildBand]"), t.canHaveChildren("DataHeaderBand") && !t.has("DataHeaderBand") && a.unlock("[data-band=DataHeaderBand]"), t.canHaveChildren("DataFooterBand") && !t.has("DataFooterBand") && a.unlock("[data-band=DataFooterBand]"), t.canHaveChildren("GroupFooterBand") && !t.has("GroupFooterBand") && a.unlock("[data-band=GroupFooterBand]"), t.canBeSorted() && a.unlock(["sort-band-up", "sort-band-down"])))
                }), e("re-render", function() {
                    r("update-menu"), r("update-info")
                })
            }
        })
    }).call(this, n(0))
}, , , function(t, e, n) {}, function(t, e, n) {}, function(t, e, n) {}, function(t, e, n) {}, function(t, e, n) {}, function(t, e, n) {}, , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , function(t, e, n) {}, , , , , function(t, e, n) {}, , , function(t, e, n) {}, , , , , , , , , , , function(t, e, r) {
    "use strict";
    (function(n) {
        var t = r(113),
            a = r(1),
            e = r(11),
            i = r(6);
        Object(i.a)(t.default, {
            fillMap: function() {
                this.fieldMap = e.a.factory(["Appearance:BackColor", "Appearance:Cursor", "Appearance:Font", "Appearance:ForeColor", "Appearance:RightToLeft", "Appearance:ScrollBars", {
                    prop: "Appearance:TextAlign",
                    collection: ["Left", "Center", "Right"]
                }, "Behavior:AcceptsReturn", "Behavior:AcceptsTab", "Behavior:CharacterCasing", "Behavior:Enabled", "Behavior:MaxLength", {
                    prop: "Behavior:Multiline",
                    setValue: function(t, e) {
                        this.prop(t, e), this.$resizing.each(function() {
                            n(this).remove()
                        }), this.$resizing = null, this.attr("resizableY", e), this.attr("resizableXY", e), e ? this.prop("Height", this.attr("mulTHeight")) : this.prop("Height", this.attr("mulFHeight")), this.touch(), this.render(), a.a.trigger("activate", this)
                    }
                }, "Behavior:ReadOnly", "Behavior:TabIndex", "Behavior:TabStop", "Behavior:UseSystemPasswordChar", "Behavior:Visible", "Behavior:WordWrap", "Data:Text", "Data Filtering:AutoFill", "Data Filtering:AutoFilter", "Data Filtering:DataColumn", "Data Filtering:DetailControl", "Data Filtering:FilterOperation", "Data Filtering:ReportParameter", "Design:Name", "Design:Restrictions", "Layout:Anchor", "Layout:Dock", {
                    prop: "Layout:Left",
                    type: "number"
                }, {
                    prop: "Layout:Top",
                    type: "number"
                }])
            }
        })
    }).call(this, r(0))
}, function(t, e, n) {}, function(t, e, n) {}, function(t, e, n) {}, function(t, e, n) {}, function(t, e, n) {}, , , , , , , , function(t, e, n) {}, function(t, e, n) {}, function(t, e, n) {}, function(t, e, u) {
    "use strict";
    var n, a, h, i, r, f, g, m, b;
    u.r(e), n = u(381), a = u(0), h = u.n(a), i = u(382), r = u(384), f = u(4), g = u(1), m = u(134), b = u(37), e.default = function() {
        var s = this,
            c = function() {
                var t = h()("<div>"),
                    e = h()("<div>"),
                    n = h()("<div>");
                return t.html('<i class="fa fa-times"></i>'), e.html('<i class="fa fa-paperclip"></i>'), e.addClass("fr-hidden"), t.addClass("close-panel cstn-mb"), e.addClass("attach-panel cstn-mb"), n.addClass("cstn-header-mb"), n.append(e, t), n
            },
            r = function() {
                f.a.get("customization-toggler") && (s.$panelsToggler = s.$panelsToggler || h()('<div class="cstn-toggle-panels js-toggle-panels"></div>'), s.getOpened().length ? (s.$panelsToggler.removeClass("cstn-toggle-panels-right"), s.$panelsToggler.addClass("cstn-toggle-panels-left")) : (s.$panelsToggler.removeClass("cstn-toggle-panels-left"), s.$panelsToggler.addClass("cstn-toggle-panels-right")), s.$controls.append(s.$panelsToggler))
            },
            o = function() {
                var t, e = void 0,
                    n = void 0,
                    a = void 0,
                    i = void 0,
                    r = void 0,
                    o = void 0,
                    s = void 0,
                    l = void 0;
                for (t = 0; t < arguments.length; t += 1)(e = arguments[t]) && (a = e.className, (n = e.opt = f.a.get("customization:" + a)).enable && (n.button && (i = h()("<a>"), (r = h()("<div>")).addClass(e.icon), i.addClass(a), i.addClass("cstn-control"), i.append(r), this.$controls.append(i)), (o = h()("<div>")).addClass("cstn-panel"), o.toggleClass("has-border", n.hasBorder), o.addClass(a + "-pane"), n.background && o.css("background", n.background), (s = e.$title) && (n.header ? (s.addClass("cstn-panel-title"), n.movable && !e.$miniButtons && (s.addClass("cstn-panel-title-movable"), e.$miniButtons = c(), s.append(e.$miniButtons)), o.append(s)) : (s.remove(), s = null)), e.$body && ((l = h()("<div>")).addClass("cstn-panel-body"), l.append(e.$body), o.append(l)), o.data("panel", {
                    panel: e,
                    $button: i,
                    $icon: r,
                    $group: o,
                    $title: s,
                    $body: l
                }), this.$mainContainer.append(o), this.panels.push(e)))
            },
            e = function(t) {
                var e, n, a, i, r, o;
                Object(m.a)(t), e = t.pageX, n = t.pageY, a = s.$mainContainer[0].getBoundingClientRect(), (r = (i = h()(t.target).parents(".cstn-panel")).data("panel")).panel.opt.movable && (o = i[0].getBoundingClientRect(), s.isMoving = {
                    panel: r,
                    disToCursorX: e - o.left,
                    disToCursorY: n - o.top,
                    mainLeft: a.left,
                    mainTop: a.top,
                    initPageX: e,
                    initPageY: n
                })
            },
            l = function(t) {
                var e = t.$group,
                    n = t.panel.opt;
                e.hasClass("detached-panel") || (e.css("width", b.c), n.resizable && (n.direction ? e.resizable({
                    handles: "hors" === n.direction ? "s" : "e"
                }) : e.resizable({
                    handles: "se"
                })), e.addClass("detached-panel"), e.css("height", ""), t.$title && t.$title.find(".attach-panel").removeClass("fr-hidden"))
            },
            n = function(t) {
                var e = t.$group,
                    n = t.panel.opt;
                e.hasClass("detached-panel") && (t.$title && t.$title.find(".attach-panel").addClass("fr-hidden"), e.removeClass("detached-panel"), e.css({
                    height: "",
                    width: "",
                    top: "",
                    left: ""
                }), n.resizable && e.resizable("destroy"))
            },
            a = function(t, e) {
                var n = t.pageX,
                    a = t.pageY,
                    i = e.panel.$group;
                return (!e.isSoared && Math.abs(n - e.initPageX) > b.d || Math.abs(a - e.initPageY) > b.d) && (e.isSoared = !0, l(e.panel), s.balance()), (e.isSoared || i.is(".detached-panel")) && i.css({
                    left: n - e.mainLeft - e.disToCursorX,
                    top: a - e.mainTop - e.disToCursorY
                }), !1
            },
            p = function(t) {
                Object(m.a)(t), s.isResizing = {
                    initPageX: t.pageX
                }
            },
            d = function(t, e) {
                var n = e.initPageX - t.pageX,
                    a = e.initPageX - n - s.$mainContainer.get(0).getBoundingClientRect().left;
                return a < b.g ? (a <= b.f && s.hideAll(), a = b.g) : s.getOpened().length || s.showAll(), s.setContainerWidth(a), s.balance(), !1
            };
        this.isReady = function() {
            return this.dfd.promise()
        }, this.setContainerWidth = function(t) {
            this.setContainerWidth.lastW = t || this.setContainerWidth.lastW, this.getOpened().length ? this.$mainContainer.css({
                width: t || this.setContainerWidth.lastW,
                marginLeft: 5,
                marginRight: 5
            }) : this.$mainContainer.css({
                width: 0,
                marginLeft: "",
                marginRight: ""
            })
        }, this.setContainerWidth.lastW = b.c, this.init = function() {
            function i(t, e) {
                var n, a = t.children();
                for (n = a.length; n--;)
                    if (0 < h()(a[n])[e]()) return !0;
                return !1
            }
            var t, r = this;
            this.dfd = h.a.Deferred(), (t = []).push(u(225).default), t.push(u(226).default), t.push(u(229).default), t.push(u(227).default), t.push(u(223).default), this.panels = this.panels || [], this.$node = h()("<div>"), this.$controls = h()("<div>"), this.$mainContainer = h()("<div>"), this.$node.addClass("cstn"), this.$controls.addClass("cstn-controls"), this.$mainContainer.addClass("cstn-container"), this.setContainerWidth(b.c), this.$node.append(this.$controls, this.$mainContainer), f.a.get("customization-toggler") && (this.$widthHandler = h()("<div>"), this.$widthHandler.addClass("cstn-width-handler"), this.$node.append(this.$widthHandler)), this.append.apply(this, t).then(function() {
                return r.dfd.resolve()
            }), g.a.bind("update-workspace-indent", function() {
                var t = window.DSG.currentReport,
                    e = r.$node.outerWidth(!0),
                    n = r.$horzCntr,
                    a = r.$vertCntr;
                t.attr("left", e), n && (n.css("left", e), n.height(""), i(n, "height") ? (t.$node.css("bottom", b.a + "px"), n.height(b.a + "px")) : (t.$node.css("bottom", ""), n.height(""))), a && (a.width(""), i(a, "width") ? (t.$node.css("right", b.b + "px"), a.width(b.b + "px")) : (t.$node.css("right", ""), a.width("")))
            }), this.$controls.on("click", "> a", function(t, e) {
                var n = h()(t.currentTarget),
                    a = r.panels.filter(function(t) {
                        return n.hasClass(t.className)
                    })[0];
                a && a.$body && r.toggle(a.$body.parents(".cstn-panel:first"), n, e)
            }), this.$controls.on("click", ".js-toggle-panels", function() {
                r.getOpened().length ? r.$controls.find("> a.active").trigger("click", !1) : r.$controls.find("> a").trigger("click", !1)
            }), this.$node.on("mousedown", ".cstn-width-handler", function(t) {
                if (0 !== t.button) return null;
                p(t)
            }), this.$mainContainer.on("mousedown", ".cstn-panel-title-movable", function(t) {
                if (0 !== t.button || h()(t.target).is(".cstn-mb")) return null;
                e(t)
            }), this.$mainContainer.on("touchstart", ".cstn-panel-title", e), h()(document.body).on("mousemove touchmove", function(t) {
                return Object(m.a)(t), r.isMoving ? a(t, r.isMoving) : r.isResizing ? d(t, r.isResizing) : void 0
            }), h()(document.body).on("mouseup touchend", function() {
                delete r.isMoving, delete r.isResizing
            }), this.$mainContainer.on("click", ".close-panel", function(t) {
                var e = h()(t.target).parents(".cstn-panel"),
                    n = e.data("panel");
                r.hide(e, n.$button)
            }), this.$mainContainer.on("click", ".attach-panel", function(t) {
                n(h()(t.target).parents(".cstn-panel").data("panel")), r.balance()
            })
        }, this.append = function() {
            var t, e, n = this,
                a = [],
                i = [].slice.call(arguments).sort(function(t, e) {
                    return e.pos - t.pos
                });
            for (t = i.length; t--;) !1 === (e = i[t]).canBeRecreated && e.$body ? a.push(e) : a.push(e.create(this));
            return Promise.all(a).then(function(t) {
                t.filter(function(t) {
                    return t
                }).forEach(function(t) {
                    "function" == typeof t.bindEvents && t.bindEvents()
                }), o.apply(n, t), n.showDefaultPanels(), r()
            })
        }, this.clear = function() {
            this.panels.forEach(function(t) {
                "function" == typeof t.clear && t.clear()
            }), this.panels.length = 0, this.$mainContainer.empty(), this.$controls.empty()
        }, this.updateIndent = function() {
            return g.a.trigger("update-workspace-indent")
        }, this.getOpened = function() {
            return h()(".opened:not(.detached-panel)", this.$mainContainer)
        }, this.recalculate = function() {
            var t = this.getOpened(),
                e = 100 / t.length;
            t.height(e - .3 + "%")
        }, this.balance = function() {
            this.setContainerWidth(), this.recalculate(), this.updateIndent()
        }, this.show = function(t, e, n) {
            t.addClass("opened"), !1 !== n && t.addClass("appear"), e && e.addClass("active"), this.balance()
        }, this.hide = function(t, e) {
            t.removeClass("opened appear"), e && e.removeClass("active"), this.balance()
        }, this.toggle = function(t) {
            this[t.is(".opened") ? "hide" : "show"].apply(this, arguments), r()
        }, this.showAll = function() {
            this.$controls.find("> a").each(function() {
                h()(this).is(".active") || h()(this).trigger("click", !1)
            })
        }, this.hideAll = function() {
            this.$controls.find("> a").each(function() {
                h()(this).is(".active") && h()(this).trigger("click", !1)
            })
        }, this.showDefaultPanels = function() {
            for (var t = this.panels, e = t.length, n = void 0, a = void 0, i = void 0, r = void 0, o = this.$horzCntr, s = this.$vertCntr; e--;) n = (a = t[e]).className, i = a.opt, r = a.$body.parents(".cstn-panel:first"), void 0 !== i.position ? (r.css("height", ""), i.position === b.e ? (o || (this.$horzCntr = o = o || h()("<div>"), o.addClass("fr-position-cntr fr-position-cntr__horz"), i.table && o.addClass("fr-position-cntr--table"), window.DSG.currentReport.$node.after(o)), o.append(r)) : i.position === b.h && (s || (this.$vertCntr = s = s || h()("<div>"), s.addClass("fr-position-cntr fr-position-cntr__vert"), i.table && s.addClass("fr-position-cntr--table"), window.DSG.currentReport.$node.after(s)), s.append(r))) : void 0 !== i.x && void 0 !== i.y && (l(r.data("panel")), "number" == typeof i.x && r.css("left", i.x), "number" == typeof i.y && r.css("top", i.y)), i && i.shown && this.show(r, this.$controls.find("." + n), !1)
        }, this.init()
    }
}, function(t, e, n) {}, , , , function(t, e, n) {}, function(t, e, n) {
    "use strict";
    var o, a, i;
    n.r(e), o = n(15), a = n(1), i = n(7), e.default = Object(i.a)(a.a, {
        _init: function() {
            var i = window.DSG.currentReport,
                t = this.bind,
                r = this.trigger;
            t("balance-band", function(t, e) {
                t.bands && (t.balance(e), i.getCurrentPage().render())
            }), t("remove-band", function(t) {
                t.remove(), t.getPage().render()
            }), t("render-band", function(t) {
                t.render(), t.getPage().render()
            }), t("add-band", function(t, e, n) {
                var a = i.getCurrentPage();
                "string" == typeof t && (t = window.DSG.bands[t]), t && (t = t.create(), n = !1 !== n, (e = e || a.bands.getSelectedBand()) && e.canHaveChildren(t) || (e = a), (t = e.addBand(t)) && (t.applyRule() && (n = !1), n && o.a.push({
                    undo: function(t, e) {
                        r("remove-band", t), e.updateExts()
                    },
                    redo: function(t, e) {
                        r("render-band", t), e.updateExts()
                    },
                    data: [t, e]
                }), r("balance-band", t, 0), r("update-menu"), e.updateExts()))
            }), t("sort-band-up", function() {
                var t = i.getCurrentPage(),
                    e = t.bands.getSelectedBand();
                e && e.moveUp()
            }), t("sort-band-down", function() {
                var t = i.getCurrentPage(),
                    e = t.bands.getSelectedBand();
                e && e.moveDown()
            })
        }
    })
}, function(t, e, n) {
    "use strict";
    var a, o, s, b, i, v, y, l, r, c, p, d;
    n.r(e), a = n(0), o = n.n(a), s = n(224), b = n(15), i = n(1), v = n(27), y = n(2), l = n(21), r = n(7), c = n(156), p = n(40), d = n(84), e.default = Object(r.a)(i.a, {
        _init: function() {
            function i(t) {
                return t.filter(function() {
                    return this.attr("copyable")
                })
            }

            function f(t, e, n, a) {
                return t.render(), e.put(t), l.a || 0 === n && 0 === a ? g("align-component", t, 0, 0, !0) : (g("align-component", t, t.prop("Left") + n, t.prop("Top") + a, !0), t.setState("in_move"), m.movements.data.activate("moving-component")), t
            }

            function r(t) {
                var e, n, a = m.getCurrentPage(),
                    i = a.bands || a.components,
                    r = {
                        action: "change"
                    };
                if (a.isCode()) return null;
                if (n = (e = i.getSelectedComponents()).length) {
                    for (n -= 1; 0 <= n; n -= 1) t.call(e[n]), e[n].render(r);
                    n = e.length
                } else(e = m.getSelected()) && (t.call(e), e.render(r), n = 1);
                return n
            }
            var t = this.bind,
                g = this.trigger,
                m = window.DSG.currentReport;
            t("remove-component", function(t) {
                var e = t.getContainer();
                t.remove(), e.updateThreshold && e.updateThreshold()
            }), t("render-component", function(t) {
                var e = t.getContainer();
                t.render(), e.updateThreshold && e.updateThreshold()
            }), t("create-component", function(t, e) {
                var n = o.a.Deferred();
                return "string" == typeof t && (t = /Control$/.test(t) ? window.DSG.controls[t] : window.DSG.components[t]), t ? n.resolve(t.create(e)) : n.reject(), n.promise()
            }), t("add-component", function(t, e, n) {
                var a = o.a.Deferred();
                return n = n || {
                    left: 0,
                    top: 0,
                    remember: !0
                }, e = e || m.getCurrentPage().bands.first(), g("create-component", t, n.internalId).done(function(t) {
                    (!t.isDialogControl() && e.isBand() || t.isDialogControl() && e.isDialog()) && (n.view && (void 0 !== t.prop("Text") && t.prop("Text", Object(c.a)(n.view)), void 0 !== t.prop("DataColumn") && t.prop("DataColumn", n.view)), t.render({
                        action: "init",
                        left: n.left,
                        top: n.top
                    }), e.put(t), g("align-component", t, n.left, n.top), t.afterAlign(), !1 !== n.remember && b.a.push({
                        context: t,
                        undo: function() {
                            this.remove()
                        },
                        redo: function() {
                            this.render()
                        }
                    }), e.isBand() && g("balance-band", e), g("re-render"), Object(v.a)(t.toString() + " " + y.a.tr("created"), {
                        success: !0
                    }), "SubreportObject" !== t.type || t.prop("ReportPage") || g("new-subreport-page", t)), g("component-added", t, e), a.resolve(t)
                }).fail(function() {
                    a.resolve(null)
                }), a.promise()
            }), t("put-component", function(t, e, n, a) {
                t && (e.collection.container !== t && t.put(e), e.setPosition(n, a), e.render())
            }), t("drop-component", function(t, e) {
                var n, a, i = t.getContainer(),
                    r = [];
                for (e ? g("put-component", e, t) : e = m.drop(t), e && r.push(e), e !== i && i.isBand() && r.push(i), n = 0, a = Object(d.a)(r).length; n < a; n += 1) g("balance-band", r[n])
            }), t("align-component", function(t, e, n, a) {
                var i;
                t.isDialogControl() || (i = Object(p.a)([e, n]), t.setPosition(i[0], i[1]), a || (i = Object(p.a)([t.prop("Left") + t.prop("Width"), t.prop("Top") + t.prop("Height")]), t.prop("Width", +(i[0] - t.prop("Left")).toFixed(2)), t.prop("Height", +(i[1] - t.prop("Top")).toFixed(2)), t.render({
                    action: "align"
                })))
            }), t("new-subreport-page", function(t) {
                var e = m.createPage(!0);
                e.attr("isSubreport", !0), g("add-band", "DataBand", e, !1), t.prop("ReportPage", e.prop("Name")), e.render(), g("activate", e)
            }), t("copy", function(t) {
                var e = m.getCurrentPage(),
                    n = e.bands || e.components;
                e.buffer = i(t || n.getSelectedComponents()), e.buffer.length && (Object(v.a)(y.a.tr("copied") + " " + e.buffer.length, {
                    info: !0,
                    inEmptyList: !0
                }), g("update-menu"))
            }), t("cut", function(t) {
                function e(t) {
                    return n.buffer = i(t || a.getSelectedComponents()), !!n.buffer.length && (Object(v.a)(y.a.tr("cut") + " " + n.buffer.length, {
                        info: !0,
                        inEmptyList: !0
                    }), n.buffer.cut = !0, n.buffer.each(function() {
                        this.remove()
                    }), !0)
                }
                var n = m.getCurrentPage(),
                    a = n.bands || n.components;
                e(t) && (b.a.push({
                    context: this,
                    undo: function(t) {
                        o.a.each(t, function() {
                            this.render()
                        }), n.buffer.length = 0, g("update-menu")
                    },
                    redo: function(t) {
                        e(t), g("update-menu")
                    },
                    data: [n.buffer.slice(0)]
                }), g("update-menu"))
            }), t("paste", function(t) {
                var e, n, a, i, r, o, s, l, c = m.getCurrentPage(),
                    p = 0,
                    d = 0,
                    u = c.buffer.length,
                    h = [];
                if (u) {
                    if (!t) {
                        for (s = u; s--;) p = (l = c.buffer[s]).prop("Left"), d = l.prop("Top"), r = l.attr("right"), o = l.attr("bottom"), (!e || p < e) && (e = p), (!n || n < r) && (n = r), (!a || d < a) && (a = d), (!i || i < o) && (i = o);
                        p = -e + m.movements.data.pageX - 4 * c.attr("padding"), d = -a + m.movements.data.pageY - 6 * c.attr("padding")
                    }
                    if (c.buffer.cut)
                        for (s = u; s--;) f(l = c.buffer[s], l.getContainer(), p, d), h.push(l);
                    else
                        for (s = u; s--;) l = c.buffer[s].clone(), h.push(l), f(l, l.getContainer(), p, d);
                    b.a.push({
                        func: function(t, e) {
                            for (var n, a = t.length; a--;)(n = t[a]).setState("normal"), g(e, n);
                            m.movements.data.deactivate("moving-component")
                        },
                        undoData: [h, "remove-component"],
                        redoData: [h, "render-component"]
                    }), Object(v.a)(y.a.tr("pasted") + " " + h.length, {
                        success: !0,
                        inEmptyList: !0
                    }), c.buffer.cut && (c.buffer = []), g("update-properties-panel", m.getSelected()), g("update-events-panel", m.getSelected())
                }
            }), t("move-component", function(n, a, t) {
                var e = r(function() {
                    var t, e;
                    if (this.canMove()) {
                        switch (t = this.prop("Left"), e = this.prop("Top"), a = a || 1, n) {
                            case 1:
                                t -= a;
                                break;
                            case 2:
                                e -= a;
                                break;
                            case 3:
                                t += a;
                                break;
                            case 4:
                                e += a
                        }
                        this.setPosition(t, e, !0)
                    }
                });
                e && (1 === e && (g("update-properties-panel", m.getSelected()), g("update-events-panel", m.getSelected())), t && t())
            }), t("select-all-components", function() {
                m.getCurrentPage().bands.everyEntity(function(t) {
                    t.components.eachEntity(function(t) {
                        t.activate()
                    })
                }), g("update-info")
            }), t("vert-align-top", function() {
                r(function() {
                    this.canModify() && this.canEdit() && this.prop("VertAlign", "Top", !0)
                })
            }), t("vert-align-center", function() {
                r(function() {
                    this.canModify() && this.canEdit() && this.prop("VertAlign", "Center", !0)
                })
            }), t("vert-align-bottom", function() {
                r(function() {
                    this.canModify() && this.canEdit() && this.prop("VertAlign", "Bottom", !0)
                })
            }), t("horz-align-left", function() {
                r(function() {
                    this.canModify() && this.canEdit() && this.prop("HorzAlign", "Left", !0)
                })
            }), t("horz-align-center", function() {
                r(function() {
                    this.canModify() && this.canEdit() && this.prop("HorzAlign", "Center", !0)
                })
            }), t("horz-align-right", function() {
                r(function() {
                    this.canModify() && this.canEdit() && this.prop("HorzAlign", "Right", !0)
                })
            }), t("horz-align-justify", function() {
                r(function() {
                    this.canModify() && this.canEdit() && this.prop("HorzAlign", "Justify", !0)
                })
            }), t("font-bold", function() {
                r(function() {
                    this.canModify() && this.canEdit() && this.attr("Font.Bold", !this.attr("Font.Bold"), !0)
                })
            }), t("font-italic", function() {
                r(function() {
                    this.canModify() && this.canEdit() && this.attr("Font.Italic", !this.attr("Font.Italic"), !0)
                })
            }), t("font-underline", function() {
                r(function() {
                    this.canModify() && this.canEdit() && this.attr("Font.Underline", !this.attr("Font.Underline"), !0)
                })
            }), t("font-strikeout", function() {
                r(function() {
                    this.canModify() && this.canEdit() && this.attr("Font.Strikeout", !this.attr("Font.Strikeout"), !0)
                })
            }), t("lock", function(t) {
                var e, n, a, i;
                i = (t = t || ["DontMove", "DontResize", "DontModify", "DontEdit", "DontDelete"]).length, r(function() {
                    for ("string" == typeof(e = this.prop("Restrictions").slice(0)) && (e = e.split(",").map(function(t) {
                            return t.trim()
                        })), a = 0; a < i; a += 1) n = t[a], ~e.indexOf(n) || e.push(n);
                    this.prop("Restrictions", e)
                })
            }), t("unlock", function(t) {
                var e, n, a, i;
                a = (t = t || ["DontMove", "DontResize", "DontModify", "DontEdit", "DontDelete"]).length, r(function() {
                    for ("string" == typeof(i = this.prop("Restrictions").slice(0)) && (i = i.split(",").map(function(t) {
                            return t.trim()
                        })), n = 0; n < a; n += 1) ~(e = i.indexOf(t[n])) && i.splice(e, 1);
                    this.prop("Restrictions", i)
                })
            }), t("group", function() {
                var t, e = [];
                r(function() {
                    t = t || this.formGroupInx(), this.prop("GroupIndex", t), e.push(this)
                }), e.forEach(function(t) {
                    t.attr("group", e)
                })
            }), t("ungroup", function() {
                r(function() {
                    this.deleteProp("GroupIndex"), this.deleteAttr("group")
                })
            }), t("send-to-back", function(t) {
                var e;
                t ? (e = t.getContainer()) && e.components && e.components.addInStart(t) : r(function() {
                    (e = this.getContainer()) && e.components && e.components.addInStart(this)
                })
            }), t("bring-to-front", function(t) {
                var e;
                t ? (e = t.getContainer()) && e.components && e.components.add(t) : r(function() {
                    (e = this.getContainer()) && e.components && e.components.add(this)
                })
            }), t("border-style", function(t) {
                r(function() {
                    this.canModify() && this.canEdit() && this.prop("Border.Style", t, !0)
                })
            }), t("border-width", function(t) {
                r(function() {
                    this.canModify() && this.canEdit() && this.prop("Border.Width", t, !0)
                })
            }), t("font-name", function(t, e) {
                var n = 2 < arguments.length && void 0 !== arguments[2] ? arguments[2] : "";
                s.a.load(t).then(function() {
                    e ? (e.attr(n + "Font.Name", t, !0), e.render()) : r(function() {
                        this.canModify() && this.canEdit() && this.attr(n + "Font.Name", t, !0)
                    }), g("update-menu")
                })
            }), t("font-width", function(t) {
                r(function() {
                    this.canModify() && this.canEdit() && this.attr("Font.Size", t, !0)
                })
            })
        }
    })
}, function(t, e, n) {}, , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , , function(t, e, c) {
    "use strict";

    function n(i) {
        return {
            get: function(t) {
                var e = getComputedStyle(t).getPropertyValue(i);
                return e ? Object(s.a)(e) : null
            },
            set: function(t, e) {
                var n, a = /\s*(\d{1,3})\s*,\s*(\d{1,3})\s*,\s*(\d{1,3})\s*/;
                a.test(e) && (3 === (n = (n = e.match(a)).slice(1)).length && (e = l.a.apply(null, n)), e = e), t.style[i] = e
            }
        }
    }

    function a(t) {
        t.eachEntity(function(t) {
            t.removeFromCoords()
        })
    }

    function i() {
        Ht.a.trigger("info", {
            title: qt.a.tr("Hotkeys"),
            message: '\n        <div class="fr-helper">\n          <ul>\n            <li>\n              <strong>Esc</strong> - ' + qt.a.tr("RemoveAllPopups") + "\n            </li>\n            <li>\n              <strong>Del</strong> - " + qt.a.tr("DeleteSelectedComponentsOrBand") + "\n            </li>\n            <li>\n              <strong>Ctrl + A</strong> - " + qt.a.tr("SelectAllComponentsOnThePage") + "\n            </li>\n            <li>\n              <strong>Ctrl + C</strong> - " + qt.a.tr("CopySelectedComponents") + "\n            </li>\n            <li>\n              <strong>Ctrl + X</strong> - " + qt.a.tr("CutSelectedComponents") + "\n            </li>\n            <li>\n              <strong>Ctrl + V</strong> - " + qt.a.tr("PasteTheCopiedComponent") + "\n            </li>\n            <li>\n              <strong>Ctrl + D</strong> - " + qt.a.tr("Duplicate") + "\n            </li>\n            <li>\n              <strong>Ctrl + Z</strong> - " + qt.a.tr("Menu Edit Undo") + "\n            </li>\n            <li>\n              <strong>Ctrl + Y</strong> - " + qt.a.tr("Menu Edit Redo") + "\n            </li>\n            <li>\n              <strong>&lt;left&gt;</strong> - " + qt.a.tr("MoveSelectedComponentsLeft") + "\n            </li>\n            <li>\n              <strong>&lt;right&gt;</strong> - " + qt.a.tr("MoveSelectedComponentsRight") + "\n            </li>\n            <li>\n              <strong>&lt;up&gt;</strong> - " + qt.a.tr("MoveSelectedComponentsUp") + "\n            </li>\n            <li>\n              <strong>&lt;down&gt;</strong> - " + qt.a.tr("MoveSelectedComponentsDown") + "\n            </li>\n            <li>\n              <strong>Ctrl + &lt;left&gt;</strong> - " + qt.a.tr("MoveSelectedComponentsLeftOn1px") + "\n            </li>\n            <li>\n              <strong>Ctrl + &lt;right&gt;</strong> - " + qt.a.tr("MoveSelectedComponentsRightOn1px") + "\n            </li>\n            <li>\n              <strong>Ctrl + &lt;up&gt;</strong> - " + qt.a.tr("MoveSelectedComponentsUpOn1px") + "\n            </li>\n            <li>\n              <strong>Ctrl + &lt;down&gt;</strong> - " + qt.a.tr("MoveSelectedComponentsDownOn1px") + "\n            </li>\n            <li>\n              <strong>Ctrl + S</strong> - " + qt.a.tr("SaveCurrentReport") + "\n            </li>\n            <li>\n              <strong>Ctrl + P</strong> - " + qt.a.tr("PreviewCurrentReport") + "\n            </li>\n            <li>\n              <strong>Alt + Enter</strong> - " + qt.a.tr("ToogleFullscreen") + '\n            </li>\n            <li>\n              <strong>Ctrl + "+"</strong> - ' + qt.a.tr("Scale plus") + '\n            </li>\n            <li>\n              <strong>Ctrl + "-"</strong> - ' + qt.a.tr("Scale minus") + "\n            </li>\n            <li>\n              <strong>Ctrl + 0</strong> - " + qt.a.tr("Scale to original size") + "\n            </li>\n            <li>\n              <strong>Ctrl + G</strong> - " + qt.a.tr("Group") + "\n            </li>\n            <li>\n              <strong>Ctrl + Shift + G</strong> - " + qt.a.tr("Ungroup") + '\n            </li>\n          </ul>\n          <div class="fr-helper-product-info">' + qt.a.tr("HTML5Designer") + " (" + u.a.get("version") + ")</div>\n        </div>\n    "
        })
    }

    function p(t) {
        return null != t && "function" == typeof t[Symbol.iterator]
    }

    function r() {
        var t = ".fr-designer",
            e = d()("<div>");
        this.$color = d()('<input type="color"/>', '<input type="file"/>'), e.append(this.$color), this.$main = null, this.$main = d()(t), this.$main && this.$main.length || (this.$main = d()(document.body)), this.$node = d()("<div>"), this.$reusable = d()("<div>"), this.$mainContainer = d()("<div>"), this.$controls = d()("<div>"), this.$workspace = d()("<div>"), this.$mainContainer.append(this.$controls, this.$workspace), this.$node.addClass("fr-main"), this.$reusable.addClass("fr-reusable"), this.$mainContainer.addClass("fr-main-container"), this.$workspace.addClass("fr-body"), this.$reusable.append(e), this.$node.append(this.$mainContainer, this.$reusable), this.$main.prepend(this.$node), this.put = function(t) {
            this.$node.append(t)
        }
    }
    var o, d, s, l, h, u, f, g, m, b, v, y, C, S, x, w, T, B, k, $, P, D, O, M, E, A, j, F, L, R, N, H, W, V, z, I, _, G, U, X, Y, K, J, q, Z, Q, tt, et, nt, at, it, rt, ot, st, lt, ct, pt, dt, ut, ht, ft, gt, mt, bt, vt, yt, Ct, St, xt, wt, Tt, Bt, kt, $t, Pt, Dt, Ot, Mt, Et, At, jt, Ft, Lt, Rt, Nt, Ht, Wt, Vt, zt, It, _t, Gt, Ut, Xt, Yt, Kt, Jt, qt, Zt, Qt, te, ee, ne, ae, ie, re, oe, se, le, ce, pe, de, ue, he, fe;
    c.r(e), c(303), c(304), c(305), o = c(0), d = c.n(o), s = c(62), l = c(127), String.prototype.format || (String.prototype.format = function() {
            var n = arguments;
            return this.replace(/{(\d+)}/g, function(t, e) {
                return void 0 !== n[e] ? n[e] : t
            })
        }), HTMLCanvasElement.prototype.toBlob || Object.defineProperty(HTMLCanvasElement.prototype, "toBlob", {
            value: function(t, e, n) {
                var a, i = atob(this.toDataURL(e, n).split(",")[1]),
                    r = i.length,
                    o = new Uint8Array(r);
                for (a = 0; a < r; a++) o[a] = i.charCodeAt(a);
                t(new Blob([o], {
                    type: e || "image/png"
                }))
            }
        }), window.requestIdleCallback = window.requestIdleCallback || function(t) {
            var e = Date.now();
            return setTimeout(function() {
                t({
                    didTimeout: !1,
                    timeRemaining: function() {
                        return Math.max(0, 50 - (Date.now() - e))
                    }
                })
            }, 1)
        }, window.cancelIdleCallback = window.cancelIdleCallback || function(t) {
            clearTimeout(t)
        }, d.a.cssHooks.fill = n("fill"), d.a.cssHooks.stroke = n("stroke"), h = c(14), u = c(4), f = c(42), g = c(36), m = c(9), b = c(10), v = c(114), y = c(28), C = m.default._init, S = f.default.create, x = m.default.remove, w = m.default.moving, T = m.default.resizing, B = m.default.movingEnd, k = m.default.resizingEnd, $ = m.default.render, f.default.create = function() {
            var t = S.apply(this, arguments);
            return t.guides = {
                l: {},
                t: {},
                r: {},
                b: {}
            }, t
        }, b.default.remove = function() {
            return !!g.a.remove.apply(this, arguments) && (a(this.components), this.bands.everyEntity(function(t) {
                a(t.components)
            }), !0)
        }, m.default._init = function() {
            C.apply(this, arguments), this.inCoords = {
                l: [],
                t: [],
                r: [],
                b: []
            }
        }, m.default.remove = function() {
            return !!x.apply(this, arguments) && (this.removeFromCoords(), !0)
        }, m.default.render = function() {
            var t = $.apply(this, arguments);
            return this.touched && this.unstuck(), t
        }, m.default.moving = function() {
            w.apply(this, arguments), u.a.get("guides") && this.stuck()
        }, m.default.resizing = function() {
            T.apply(this, arguments), u.a.get("guides") && this.stuck()
        }, m.default.resizingEnd = function() {
            k.apply(this, arguments), this.unstuck()
        }, m.default.movingEnd = function() {
            B.apply(this, arguments), this.unstuck()
        }, m.default.removeFromCoords = function() {
            for (var t, e, n, a, i = this.inCoords, r = Object.keys(i), o = r.length; o--;) {
                for (e = 0, n = (a = i[t = r[o]]).length; e < n; e += 1) Object(y.a)(a[e], this);
                i[t].length = 0
            }
        }, m.default.putIntoCoords = function() {
            var t, e, n, a, i, r, o, s;
            if (this.attr("movable")) {
                if (t = this.getPage(), e = this.absoluteTop(), n = this.inCoords, a = t && t.guides, i = h.a.toUnit(this.prop("Left")), r = h.a.toUnit(e), o = h.a.toUnit(this.attr("right")), s = h.a.toUnit(e + this.prop("Height")), !a) return null;
                a.l[i] = a.l[i] || [], a.t[r] = a.t[r] || [], a.r[o] = a.r[o] || [], a.b[s] = a.b[s] || [], a.l[i].push(this), a.t[r].push(this), a.r[o].push(this), a.b[s].push(this), n.l.push(a.l[i]), n.t.push(a.t[r]), n.r.push(a.r[o]), n.b.push(a.b[s])
            }
        }, m.default.updateCoords = function() {
            this.removeFromCoords(), this.putIntoCoords()
        }, m.default._getFarest = function(t, e, n) {
            var a, i, r, o, s = {},
                l = 0;
            if (!t) return this;
            for (n = n || function() {}, i = t.length; l < i; l += 1)(a = t[l]) !== this && "in_move" !== a.attr("state") && (o = (r = e.call(this, a)) < 0 ? "a" : "b", r = Math.abs(r), (!s[o] || r > s[o].diff) && (s[o] = {
                diff: r,
                el: a
            }));
            return n(s), this
        }, m.default.stuck = function() {
            function t(t) {
                return d - t.absoluteTop()
            }

            function e(t) {
                return c - t.prop("Left")
            }

            function n(t) {
                var e;
                t.a && (t.a = a[i].la.call(s, t.a.el), (e = new v.a(t.a)).removeClass("fr-hidden"), s.$sticks.append(e)), t.b && (t.b = a[i].lb.call(s, t.b.el), (e = new v.a(t.b)).removeClass("fr-hidden"), s.$sticks.append(e))
            }
            var a, i, r, o = this.getPage(),
                s = this,
                l = o && o.guides,
                c = this.prop("Left"),
                p = this.attr("right"),
                d = this.absoluteTop(),
                u = d + this.prop("Height");
            if (!l) return null;
            for (a = [{
                    coords: l.l[h.a.toUnit(c)],
                    d: t,
                    la: function(t) {
                        return {
                            x1: 0,
                            y1: 0,
                            x2: 0,
                            y2: t.absoluteTop() - d
                        }
                    },
                    lb: function(t) {
                        return {
                            x1: 0,
                            y1: 0,
                            x2: 0,
                            y2: -(d - t.absoluteTop())
                        }
                    }
                }, {
                    coords: l.r[h.a.toUnit(c)],
                    d: t,
                    la: function(t) {
                        return {
                            x1: 0,
                            y1: 0,
                            x2: 0,
                            y2: t.absoluteTop() - d
                        }
                    },
                    lb: function(t) {
                        return {
                            x1: 0,
                            y1: 0,
                            x2: 0,
                            y2: -(d - t.absoluteTop())
                        }
                    }
                }, {
                    coords: l.l[h.a.toUnit(p)],
                    d: t,
                    la: function(t) {
                        var e = this.prop("Width");
                        return {
                            x1: e,
                            y1: 0,
                            x2: e,
                            y2: t.absoluteTop() - d
                        }
                    },
                    lb: function(t) {
                        var e = this.prop("Width");
                        return {
                            x1: e,
                            y1: 0,
                            x2: e,
                            y2: -(d - t.absoluteTop())
                        }
                    }
                }, {
                    coords: l.r[h.a.toUnit(p)],
                    d: t,
                    la: function(t) {
                        var e = this.prop("Width");
                        return {
                            x1: e,
                            y1: 0,
                            x2: e,
                            y2: t.absoluteTop() - d
                        }
                    },
                    lb: function(t) {
                        var e = this.prop("Width");
                        return {
                            x1: e,
                            y1: 0,
                            x2: e,
                            y2: -(d - t.absoluteTop())
                        }
                    }
                }, {
                    coords: l.t[h.a.toUnit(d)],
                    d: e,
                    la: function(t) {
                        return {
                            x1: 0,
                            y1: 0,
                            x2: t.prop("Left") - c,
                            y2: 0
                        }
                    },
                    lb: function(t) {
                        return {
                            x1: 0,
                            y1: 0,
                            x2: -(c - t.prop("Left")),
                            y2: 0
                        }
                    }
                }, {
                    coords: l.b[h.a.toUnit(d)],
                    d: e,
                    la: function(t) {
                        return {
                            x1: 0,
                            y1: 0,
                            x2: t.prop("Left") - c,
                            y2: 0
                        }
                    },
                    lb: function(t) {
                        return {
                            x1: 0,
                            y1: 0,
                            x2: -(c - t.prop("Left")),
                            y2: 0
                        }
                    }
                }, {
                    coords: l.t[h.a.toUnit(u)],
                    d: e,
                    la: function(t) {
                        var e = this.prop("Height");
                        return {
                            x1: 0,
                            y1: e,
                            x2: t.prop("Left") - c,
                            y2: e
                        }
                    },
                    lb: function(t) {
                        var e = this.prop("Height");
                        return {
                            x1: 0,
                            y1: e,
                            x2: -(c - t.prop("Left")),
                            y2: e
                        }
                    }
                }, {
                    coords: l.b[h.a.toUnit(u)],
                    d: e,
                    la: function(t) {
                        var e = this.prop("Height");
                        return {
                            x1: 0,
                            y1: e,
                            x2: t.prop("Left") - c,
                            y2: e
                        }
                    },
                    lb: function(t) {
                        var e = this.prop("Height");
                        return {
                            x1: 0,
                            y1: e,
                            x2: -(c - t.prop("Left")),
                            y2: e
                        }
                    }
                }], this.unstuck(), i = 0, r = a.length; i < r; i += 1) this._getFarest(a[i].coords, a[i].d, n);
            return this
        }, m.default.unstuck = function() {
            return this.$sticks.empty(), this
        }, P = c(182), D = c(6), O = m.default.moving, M = m.default.movingEnd, E = m.default.remove, Object(D.a)(m.default, {
            showPositionBlock: function(t) {
                var e, n = this.$moveBlock.get(0).getBoundingClientRect(),
                    a = this.prop("Left").toFixed(2),
                    i = this.absoluteTop().toFixed(2),
                    r = window.DSG.head,
                    o = r.$main,
                    s = o.get(0).getBoundingClientRect();
                return this.$posTip || (this.$posTip = new P.a, r.put(this.$posTip), this.$posTip.height(20)), this.$posTip.css({
                    left: n.left - s.left,
                    top: n.top - 25 - s.top
                }), e = this.$posTip.find("span"), t ? e.html(h.a.toUnit(a) + ", " + h.a.toUnit(i)) : e.html(a + ", " + i), this
            },
            hidePositionBlock: function() {
                return this.$posTip && (this.$posTip.remove(), delete this.$posTip), this
            },
            remove: function() {
                var t = E.apply(this, arguments);
                return t && this.hidePositionBlock(), t
            },
            moving: function() {
                O.apply(this, arguments), this.showPositionBlock(!0)
            },
            movingEnd: function() {
                M.apply(this, arguments), this.hidePositionBlock()
            }
        }), (A = c.c[12]) && (A.exports.default.moving = function() {
            this.showPositionBlock()
        }), c(308), j = c(3), F = c(5), L = function(l) {
            var c = {
                cm: 1,
                mm: 10,
                hi: 100,
                in: 1
            };
            this.width = 0, this.height = 0, this.render = function(t) {
                var e, n, a = c[h.a.getCurrent()],
                    i = parseFloat(h.a.toPx(a), 10),
                    r = i / 2,
                    o = 0,
                    s = 0;
                if (t = t || {}, this.width = t.left || 0, this.height = t.top || 0, this.$g || (this.$g = d()(Object(j.a)("g", {
                        class: "ruler"
                    })), this.$back = d()(Object(j.a)("rect", {
                        class: "ruler-back"
                    })), this.$rulerBack = d()(Object(j.a)("rect", {
                        class: "ruler-m-back"
                    })), this.$marks = d()(Object(j.a)("g")), this.$g.append(this.$back, this.$marks)), this.$marks.empty(), this.$marks.append(this.$rulerBack), l) {
                    for (Object(F.a)(this.$g[0], "text-anchor", "end"), Object(F.a)(this.$marks[0], "transform", "translate(5, 0)"); s < this.width; s += i, o += a) e = Object(j.a)("text"), n = Object(j.a)("text"), d()(e).text(o || " "), Object(F.a)(e, "transform", "translate(8, " + (s + 3) + ")"), this.$marks.append(e), d()(n).text("."), Object(F.a)(n, "transform", "translate(5, " + (s + r) + ")"), this.$marks.append(n);
                    this.$marks.find(":last").remove(), Object(F.a)(this.$back[0], {
                        width: this.height,
                        height: this.width
                    }), Object(F.a)(this.$rulerBack[0], {
                        width: this.height / 2,
                        height: this.width
                    })
                } else {
                    for (Object(F.a)(this.$g[0], "text-anchor", "middle"), Object(F.a)(this.$marks[0], "transform", "translate(0, 5)"); s < this.width; s += i, o += a) e = Object(j.a)("text"), n = Object(j.a)("text"), d()(e).text(o || " "), Object(F.a)(e, "transform", "translate(" + s + ", 8)"), this.$marks.append(e), d()(n).text("."), Object(F.a)(n, "transform", "translate(" + (s + r) + ", 5)"), this.$marks.append(n);
                    this.$marks.find(":last").remove(), Object(F.a)(this.$back[0], {
                        width: this.width,
                        height: this.height
                    }), Object(F.a)(this.$rulerBack[0], {
                        width: this.width,
                        height: this.height / 2
                    })
                }
                return this.$g
            }
        }, R = 18, N = f.default.render, H = b.default.render, f.default.render = function() {
            N.apply(this, arguments), this.ruler ? this.ruler.render({
                left: this.attr("Width"),
                top: R
            }) : (this.ruler = new L, this.$g.append(this.ruler.render({
                left: this.attr("Width"),
                top: R
            }))), Object(F.a)(this.ruler.$g.get(0), "transform", "translate(" + (this.attr("margin") + this.attr("padding")) + ",0)")
        }, b.default.render = function() {
            H.apply(this, arguments), this.ruler || (this.ruler = new L(!0), this.ruler.render({
                left: this.prop("Height"),
                top: R
            }), this.$title.after(this.ruler.$g)), this.ruler.render({
                left: this.prop("Height"),
                top: R
            }), Object(F.a)(this.ruler.$g.get(0), "transform", "translate(" + this.attr("margin") + ", " + this.bands.getTopBandsHeight() + ")")
        }, W = c(21), V = f.default.create, z = b.default.render, f.default.create = function() {
            var e, n = V.apply(this, arguments);
            return W.a || (e = void 0, n.$g.on("mousedown", ".js-band-resize", function(t) {
                e = {
                    x: t.pageX,
                    margin: n.attr("margin")
                }
            }), n.$g.on("mousemove", function(t) {
                void 0 !== e && (n.attr("margin", e.margin + (t.pageX - e.x)), n.update(), n.bands.everyEntity(function(t) {
                    return t.updateComponentsCoords()
                }))
            }), d()(document.body).on("mouseup", function() {
                void 0 !== e && (e = void 0)
            })), n
        }, b.default.render = function() {
            var t, e;
            return z.apply(this, arguments), W.a || (t = this.attr("separator.width"), this.$bandResizerCntr || (this.$bandResizerCntr = d()(Object(j.a)("g")), this.$bandResizer = d()(Object(j.a)("line", {
                class: "js-band-resize js-disable-common col-resize"
            })), this.$bandResizer.css({
                stroke: this.attr("separator.color"),
                "stroke-width": t,
                "stroke-dasharray": this.attr("separator.style"),
                opacity: this.attr("separator.opacity")
            }), this.$bandResizerCntr.append(this.$bandResizer), this.$title.append(this.$bandResizerCntr)), e = this.attr("margin") - t, e -= this.attr("margin-left") || 0, this.$bandResizer.attr({
                x1: e,
                y1: 0,
                x2: e,
                y2: this.prop("Height")
            })), this.$g
        }, I = c(129), _ = c(27), G = c(23), U = c(101), X = c(217), Y = c(102), K = c(103), J = c(218), q = c(219), Z = c(220), Q = c(83), tt = c(221), et = c(130), nt = c(177), at = c(131), it = c(222), rt = c(25), ot = c(143), st = c(132), lt = c(115), ct = c(104), pt = c(33), dt = c(105), ut = c(153), ht = c(144), ft = c(145), gt = c(146), mt = c(147), bt = c(148), vt = c(133), yt = c(149), Ct = c(150), St = c(151), xt = c(86), wt = c(152), Tt = c(106), Bt = c(68), kt = c(107), $t = c(108), Pt = c(63), Dt = c(109), Ot = c(110), Mt = c(111), Et = c(112), At = c(113), jt = c(225), Ft = c(226), Lt = c(229), Rt = c(227), Nt = c(223), Ht = c(1), new G.a("hotkey"), Wt = function(t, e) {
            var n = t.ctrlKey ? 1 : u.a.get("grid");
            Ht.a.trigger("move-component", e, n, function() {
                t.stopPropagation(), t.preventDefault()
            })
        }, Vt = function() {
            var t, e = window.DSG.currentReport;
            return !!e && (t = e.getCurrentPage(), !(u.a.get("hotkeyProhibited") || !t || t.isCode() || d()(".d-cm").length || d()(".fr-popup-container").length))
        },
        function() {
            if (W.a) return;
            d()(document.body).on("keydown", function(t) {
                var e = window.event ? t.which : t.keyCode,
                    n = t.ctrlKey,
                    a = t.shiftKey;
                if (Vt()) switch (e) {
                    case 46:
                    case 8:
                        return Ht.a.trigger("remove"), !1;
                    case 65:
                        if (n) return Ht.a.trigger("select-all-components"), !1;
                        break;
                    case 67:
                        if (n) return Ht.a.trigger("copy"), !1;
                        break;
                    case 86:
                        if (n) return Ht.a.trigger("paste"), !1;
                        break;
                    case 88:
                        if (n) return Ht.a.trigger("cut"), !1;
                        break;
                    case 90:
                        if (n) return a ? Ht.a.trigger("redo") : Ht.a.trigger("undo"), !1;
                        break;
                    case 89:
                        if (n) return Ht.a.trigger("redo"), !1;
                        break;
                    case 37:
                        if (a) return Ht.a.trigger("rotate-left"), !1;
                        Wt(t, 1);
                        break;
                    case 38:
                        Wt(t, 2);
                        break;
                    case 39:
                        if (a) return Ht.a.trigger("rotate-right"), !1;
                        Wt(t, 3);
                        break;
                    case 40:
                        Wt(t, 4);
                        break;
                    case 83:
                        if (n) return Ht.a.trigger("save"), !1;
                        break;
                    case 80:
                        if (n) return Ht.a.trigger("preview"), !1;
                        break;
                    case 72:
                        return Ht.a.trigger("show-hotkey-helper"), !1;
                    case 13:
                        if (t.altKey) return Ht.a.trigger("toogle-fullscreen"), !1;
                        break;
                    case 68:
                        if (n) return Ht.a.trigger("copy"), Ht.a.trigger("paste", !0), Ht.a.trigger("update-component-viewer-panel"), !1;
                        break;
                    case 222:
                        if (n) return a ? Ht.a.trigger("sticky-grid") : (Ht.a.trigger("grid"), Ht.a.trigger("re-render")), !1;
                        break;
                    case 71:
                        if (n) return a ? Ht.a.trigger("ungroup") : Ht.a.trigger("group"), Ht.a.trigger("update-menu"), !1;
                        break;
                    case 107:
                    case 187:
                        if (n) return Ht.a.trigger("scale-page-plus"), !1;
                        break;
                    case 109:
                    case 189:
                        if (n) return Ht.a.trigger("scale-page-minus"), !1;
                        break;
                    case 96:
                    case 48:
                        if (n) return Ht.a.trigger("scale-page-original"), !1;
                        break;
                    case 33:
                        if (n && a) return Ht.a.trigger("menu-higher"), !1;
                        break;
                    case 34:
                        if (n && a) return Ht.a.trigger("menu-lower"), !1;
                        break;
                    case 32:
                        return Ht.a.trigger("toggle-mouse-scroll-enable"), !1;
                    case 79:
                        if (n) return Ht.a.trigger("open-fs-report"), !1
                }
            }).on("keyup", function(t) {
                var e = window.event ? t.which : t.keyCode;
                if (Vt()) switch (e) {
                    case 32:
                        return Ht.a.trigger("toggle-mouse-scroll-disable"), !1
                }
            })
        }(), zt = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function(t) {
            return typeof t
        } : function(t) {
            return t && "function" == typeof Symbol && t.constructor === Symbol && t !== Symbol.prototype ? "symbol" : typeof t
        }, It = function(t) {
            function e() {
                this.init.apply(this, arguments)
            }
            return e.prototype.init = function() {}, e.include = function(t) {
                var e, n, a;
                if ("object" !== (void 0 === t ? "undefined" : zt(t)) && t.constructor !== Object) return !1;
                for (e = (n = Object.keys(t)).length; e--;) a = n[e], this.prototype[a] = t[a];
                return this
            }, t && e.include(t), e
        }, _t = c(54), Gt = c(69), Ut = c(126), Xt = c(7), Yt = Object(Xt.a)(Ht.a, {
            _init: function() {
                var o = window.DSG.currentReport,
                    e = window.DSG.head,
                    t = this.bind,
                    s = this.trigger,
                    n = this.unbind;
                t("init", function() {
                    s("activate", o.pages.first(["ReportPage"])), o.show().afterInitShow(), n("init")
                }), t("remove-popups", function(t) {
                    t ? d()(".fr-popup-container").remove() : d()(".fr-popup-container").fadeOut(150, function(t) {
                        d()(t.currentTarget).remove()
                    }), d()(".will-be-created", e.$node).remove()
                }), t("remove-context-menus", function() {
                    return d()(".d-cm").remove()
                }), t("activate", function(t) {
                    var e, n = 1 < arguments.length && void 0 !== arguments[1] ? arguments[1] : {},
                        a = n.force,
                        i = n.withoutRerender,
                        r = n.withoutUpdatingWorkspace;
                    t && ((e = o.getSelected()) && !e.isPage() && e.deactivate(), !t.fieldMap && t.fillMap && t.fillMap(), !t.eventMap && t.fillEventMap && t.fillEventMap(), t.isComponent() ? s("activate-component", t) : t.isBand() ? s("activate-band", t) : t.isPage() ? s("activate-page", t) : t.isReport() ? s("activate-report", t) : t.activate(), t === e && !a || (i || s("re-render", t), t.isPage() && !r && s("update-workspace-indent")))
                }), t("activate-report", function(t) {
                    return t.activate()
                }), t("activate-page", function(t) {
                    return t.active()
                }), t("activate-page-by-id", function(t, e) {
                    var n = o.pages.findOneBy({
                        _id: t
                    });
                    s("activate", n, e)
                }), t("show-page", function(t) {
                    var e = o.getCurrentPage();
                    t !== e && (e.deactivate(), t.show())
                }), t("activate-band", function(t) {
                    return t.active()
                }), t("activate-component", function(t) {
                    return t.active()
                }), t("scale-page", function(t) {
                    var e = o.getCurrentPage();
                    t < .1 && (t = .1), 2 < t && (t = 2), o.attr("data-scale", t), e.updateSize(), d()(".scale-page").find(".d-icr").val(t)
                }), t("scale-page-plus", function() {
                    return s("scale-page", o.attr("data-scale") + .1)
                }), t("scale-page-minus", function() {
                    return s("scale-page", o.attr("data-scale") - .1)
                }), t("scale-page-original", function() {
                    return s("scale-page", u.a.get("scale"))
                }), t("toggle-mouse-scroll-enable", function() {
                    o.$node.addClass("fr-grab-page"), u.a.set("scroll-on-space", !0)
                }), t("toggle-mouse-scroll-disable", function() {
                    o.$node.removeClass("fr-grab-page fr-grabbing-page"), u.a.set("scroll-on-space", !1)
                }), t("re-render", function(t) {
                    t = t || o.getSelected(), s("select-in-tree", t), s("update-properties-panel", t), s("update-events-panel", t)
                })
            }
        }), Kt = c(15), Jt = c(13), qt = c(2), Zt = c(233), Qt = c(161), te = c(119), ee = function(t) {
            for (var e, n = t + "=", a = document.cookie.split(";"), i = 0, r = a.length; i < r; i += 1) {
                for (e = a[i];
                    " " === e.charAt(0);) e = e.substring(1);
                if (0 === e.indexOf(n)) return e.substring(n.length, e.length)
            }
            return ""
        }, ne = c(74), ae = function() {
            var t, e = u.a.get("cookieName"),
                n = ee(e) || Object(ne.a)(e);
            return e && n && (t = e + "=" + n), t
        }, c(378), c(379), ie = new G.a, re = Object(Xt.a)(Ht.a, {
            _init: function() {
                var r = window.DSG.currentReport,
                    t = this.bind,
                    o = this.trigger;
                t("show-hotkey-helper", i), t("toogle-fullscreen", function() {
                    return function(t) {
                        var e, n, a = document.fullscreenElement || document.mozFullScreenElement || document.webkitFullscreenElement || document.msFullscreenElement;
                        t = t || document.documentElement, a ? (n = document.exitFullscreen || document.msExitFullscreen || document.mozCancelFullScreen || document.webkitExitFullscreen) && n.apply(document) : void 0 !== (e = t.requestFullscreen || t.msRequestFullscreen || t.mozRequestFullScreen || t.webkitRequestFullscreen) && e.apply(t, [Element.ALLOW_KEYBOARD_INPUT])
                    }(window.DSG.head.$main.get(0))
                }), t("remove", function(t, e) {
                    var n = r.getCurrentPage(),
                        a = n.bands || n.components,
                        i = 0;
                    if (t) void 0 === t.length && (t = [t]);
                    else if (!(t = a.getSelectedComponents()).length) {
                        if (!(t = a.getSelectedBand()) || !t.canBeRemoved()) return;
                        t = [t]
                    }
                    d.a.each(t, function() {
                        this.attr("removeable") && (o(this.isComponent() ? "remove-component" : "remove-band", this), i += 1)
                    }), e || Kt.a.push({
                        context: this,
                        func: function(t, e) {
                            d.a.each(t, function() {
                                o(e + (this.isComponent() ? "-component" : "-band"), this), this.isBand() && this.collection.container.updateExts()
                            }), n.balance && n.balance()
                        },
                        undoData: [t, "render"],
                        redoData: [t, "remove"]
                    }), Jt.a.deactivate(), o("remove-popups"), o("re-render", n), Object(_.a)(qt.a.tr("removed") + " " + i, {
                        info: !0
                    })
                }), t("undo", function() {
                    var t, e = Kt.a.prev();
                    if (e) {
                        if ("function" != typeof(t = e.undo || e.func)) return o("undo");
                        t.apply(e.context, e.undoData || e.data), o("re-render")
                    }
                }), t("redo", function() {
                    var t, e = Kt.a.next();
                    return e ? "function" != typeof(t = e.redo || e.func) ? o("redo") : (t.apply(e.context, e.redoData || e.data), void o("re-render")) : null
                }), t("sticky-grid", function() {
                    u.a.set("sticky-grid", !u.a.get("sticky-grid")), o("update-info")
                }), t("grid", function() {
                    r.attr("grid", !r.attr("grid")), r.getCurrentPage().render()
                }), t("preview", function() {
                    var a = arguments;
                    Gt.c.toXML().then(function(t) {
                        var e = Object(te.a)((new XMLSerializer).serializeToString(t)),
                            n = ae();
                        return ie.dirxml(t), d.a.when(Ut.a.show(void 0, 1)).done(function() {
                            return d.a.ajax({
                                url: u.a.get("makePreview", {
                                    id: r._id
                                }) + (n ? "&" + n : ""),
                                dataType: "text",
                                type: "POST",
                                data: e
                            }).done(function(n) {
                                Object(Qt.a)().then(function(t) {
                                    var e = new Zt.a(qt.a.tr("Preview"));
                                    e.find(".fr-modal-content").html("<span class='preview-cont'>" + n + "</span>"), t.append(e), Ut.a.hide(), window.DSG.head.put(t), o("preview_success"), window.parent !== window && window.parent.postMessage("preview_success", "*")
                                })
                            }).fail(function() {
                                Ut.a.hide(), ie.error(a), Object(_.a)("something went wrong", {
                                    danger: !0
                                }), o("preview_failure"), window.parent !== window && window.parent.postMessage("preview_failure", "*")
                            })
                        })
                    })
                }), t("save_success", function() {
                    Object(_.a)(qt.a.tr("Messages Saved"), {
                        success: !0,
                        inEmptyList: !0
                    })
                }), t("save_failure", function(t) {
                    t && Object.keys(t).length ? o("alert", {
                        title: t.status + ": " + t.statusText,
                        message: t.responseText
                    }) : Object(_.a)(qt.a.tr("Messages CantSaveReport"), {
                        danger: !0,
                        inEmptyList: !0
                    })
                }), t("save", function() {
                    Object(_.a)("saving available in the full version", {
                        danger: !0,
                        inEmptyList: !0,
                        trans: !0,
                        delay: !1
                    })
                }), t("open-fs-report", function() {
                    d()('<input type="file"/>').on("change", function(t) {
                        var e = t.target,
                            n = e.files[0];
                        n || ie.warn("file is not selected"),
                            function(r) {
                                return new Promise(function(e) {
                                    var t, n = 0,
                                        a = r.size - 1,
                                        i = new FileReader;
                                    i.onloadend = function(t) {
                                        t.target.readyState === FileReader.DONE && e(t.target.result)
                                    }, t = r.slice(n, a + 1), i.readAsBinaryString(t)
                                })
                            }(n).then(function(t) {
                                return window.DSG.app.openReport(t)
                            })
                    }).trigger("click")
                })
            }
        }), oe = c(159), se = c(183), le = Object(Xt.a)(Ht.a, {
            _init: function() {
                var t = this.bind,
                    l = this.trigger;
                t("show-expression-editor", function() {
                    var t = 0 < arguments.length && void 0 !== arguments[0] ? arguments[0] : {},
                        n = t.entity,
                        a = t.prop,
                        e = t.menu,
                        i = void 0 === e || e,
                        r = t.onSave;
                    Object(oe.a)().then(function(t) {
                        var e = t.edit(n, a, i);
                        "function" == typeof r && (e.onClick = r), Object(Qt.a)().then(function(t) {
                            t.append(e.$popup), window.DSG.head.put(t), e.$textarea.focus()
                        })
                    })
                }), t("edit-border", function(o, s) {
                    c.e(12).then(c.bind(null, 595)).then(function(t) {
                        var r, e = {};
                        "function" != typeof s && (s = function() {}), void 0 === o ? o = [] : p(o) || (o = [o]), r = function(t) {
                            var e = {
                                lines: (t.prop("Border.Lines") || "").split(", "),
                                width: t.prop("Border.Width"),
                                style: t.prop("Border.Style"),
                                color: t.prop("Border.Color")
                            };
                            return t.prop("Border.Shadow") && (e.shadow = {
                                width: t.prop("Border.ShadowWidth"),
                                color: t.prop("Border.ShadowColor")
                            }), e
                        }, 1 === o.length && (e = r(o[0])), t.create(e).then(function(i) {
                            i.on("ok", function(t, e) {
                                var n = function(t) {
                                        var e, n, a;
                                        for (e = 0; e < t.length; e += 1) n = t[e].entity, (a = t[e].data).lines && n.prop("Border.Lines", a.lines.join(", ")), a.color && n.prop("Border.Color", a.color), a.width && n.prop("Border.Width", a.width), a.style && n.prop("Border.Style", a.style), a.shadow ? (n.prop("Border.Shadow", !0), n.prop("Border.ShadowWidth", a.shadow.width), n.prop("Border.ShadowColor", a.shadow.color)) : (n.deleteProp("Border.Shadow"), n.deleteProp("Border.ShadowWidth"), n.deleteProp("Border.ShadowColor")), n.render();
                                        s()
                                    },
                                    a = d.a.map(o, function(t) {
                                        return {
                                            entity: t,
                                            data: e
                                        }
                                    });
                                Kt.a.push({
                                    context: null,
                                    func: n,
                                    undoData: [d.a.map(o, function(t) {
                                        return {
                                            entity: t,
                                            data: r(t)
                                        }
                                    })],
                                    redoData: [a]
                                }), n(a), i.close(), l("re-render")
                            })
                        })
                    })
                }), t("edit-highlight", function(i) {
                    c.e(4).then(c.bind(null, 596)).then(function(t) {
                        var n = void 0,
                            a = void 0;
                        n = i.highlights.count() ? (a = i.highlights.first()).clone() : se.a.create(), t.create(n).then(function(e) {
                            e.on("ok", function() {
                                function t(t, e) {
                                    i.highlights.remove(t), i.highlights.add(e)
                                }
                                t(a, n), Kt.a.push({
                                    context: null,
                                    func: t,
                                    undoData: [n, a],
                                    redoData: [a, n]
                                }), e.close(), l("re-render")
                            })
                        })
                    })
                }), t("text-outline", function(e) {
                    c.e(13).then(c.bind(null, 597)).then(function(t) {
                        t.create(e)
                    })
                }), t("format", function(e) {
                    c.e(10).then(c.bind(null, 592)).then(function(t) {
                        t.create(e)
                    })
                }), t("edit-font", function(r, o) {
                    c.e(3).then(c.bind(null, 598)).then(function(t) {
                        var e, n = {};
                        "function" != typeof o && (o = function() {}), void 0 === r ? r = [] : p(r) || (r = [r]), 1 === r.length && (e = r[0], n.name = e.attr("Font.Name") || "", n.size = e.attr("Font.Size"), n.isBold = !!e.attr("Font.Bold"), n.isItalic = !!e.attr("Font.Italic"), n.isUnderline = !!e.attr("Font.Underline"), n.isStrikeout = !!e.attr("Font.Strikeout")), t.create(n).then(function(i) {
                            i.on("ok", function(t, e) {
                                var n = function(t) {
                                        var e, n;
                                        for (e = 0; e < t.length; e += 1)(n = t[e].entity).attr(t[e].data), n.render();
                                        o()
                                    },
                                    a = r.map(function(t) {
                                        return {
                                            entity: t,
                                            data: {
                                                "Font.Name": e.name,
                                                "Font.Size": e.size,
                                                "Font.Bold": e.isBold,
                                                "Font.Italic": e.isItalic,
                                                "Font.Underline": e.isUnderline,
                                                "Font.Strikeout": e.isStrikeout
                                            }
                                        }
                                    });
                                Kt.a.push({
                                    context: null,
                                    func: n,
                                    undoData: [r.map(function(t) {
                                        return {
                                            entity: t,
                                            data: {
                                                "Font.Name": t.attr("Font.Name"),
                                                "Font.Size": t.attr("Font.Size"),
                                                "Font.Bold": t.attr("Font.Bold"),
                                                "Font.Italic": t.attr("Font.Italic"),
                                                "Font.Underline": t.attr("Font.Underline"),
                                                "Font.Strikeout": t.attr("Font.Strikeout")
                                            }
                                        }
                                    })],
                                    redoData: [a]
                                }), n(a), i.close(), l("re-render")
                            })
                        })
                    })
                }), t("hyperlink-editor", function(e) {
                    c.e(15).then(c.bind(null, 599)).then(function(t) {
                        return t.create(e)
                    })
                }), t("new-connection-wizard", function(e) {
                    c.e(7).then(c.bind(null, 600)).then(function(t) {
                        return t.create(e)
                    })
                }), t("edit-connection-string", function(e) {
                    c.e(8).then(c.bind(null, 593)).then(function(t) {
                        return t.create(e)
                    })
                }), t("alert", function(e) {
                    c.e(0).then(c.bind(null, 594)).then(function(t) {
                        return t.create(e)
                    })
                }), t("info", function(e) {
                    c.e(17).then(c.bind(null, 591)).then(function(t) {
                        return t.create(e)
                    })
                })
            }
        }), ce = c(17), pe = new G.a, u.a.set("key", "0d4c8e8f080ebba0f8ca13a2faaa390c"), de = It({
            version: u.a.get("version"),
            preInitSubSystems: function() {
                var a = this;
                this.initHandler.addPromise(new Promise(function(t) {
                    var e, n = window.DSG.customization;
                    n ? n.init() : (e = c(380).default, n = window.DSG.customization = new e), d.a.when(n.isReady()).done(function() {
                        window.DSG.head.$workspace.append(n.$node), a.initHandler.handlers.add(function() {
                            return n.updateIndent()
                        }), pe.info("customization panel initialised"), t()
                    })
                })), this.initHandler.addPromise(new Promise(function(t) {
                    var e = c(277).default;
                    d.a.when(e()).done(function() {
                        pe.info("theme loaded"), t()
                    })
                }))
            },
            initHandler: function() {
                var n, t = this,
                    e = this.initHandler = {
                        promises: [],
                        addPromise: function(t) {
                            this.promises.push(t)
                        },
                        getPromises: function() {
                            var t = this.promises.slice(0);
                            return this.promises.length = 0, t
                        },
                        handlers: (n = [function() {
                            Ht.a.trigger("align-workspace"), Ht.a.trigger("re-render")
                        }, function() {
                            Ut.a.hide(), Ht.a.trigger("initialized"), d()(window).trigger("resize")
                        }, function() {
                            d()(".fr-designer").trigger("initialized")
                        }], {
                            all: function() {
                                return n
                            },
                            add: function(t, e) {
                                n.splice(e || n.length - 1, 0, t)
                            }
                        }),
                        done: function() {
                            t.preInitSubSystems(), Promise.all(e.getPromises()).then(function() {
                                e.handlers.all().forEach(function(t) {
                                    t()
                                })
                            })
                        }
                    };
                return e
            },
            createReport: function() {
                var t = d.a.Deferred(),
                    e = _t.a.create();
                return window.DSG.currentReport = e, Gt.c.init(e, this.state), window.DSG.head.$workspace.empty(), window.DSG.head.$workspace.append(e.$node), Yt.create(), re.create(), le.create(), c(386).default.create(), c(387).default.create(), t.resolve(e), this.reportCreated = !0, t.promise()
            },
            createStandardReport: function() {
                var t = this,
                    e = d.a.Deferred();
                return d.a.when(this.clear()).then(function() {
                    return t.createReport()
                }).then(function(t) {
                    return t.createPage()
                }).done(function() {
                    Ht.a.trigger("init"), t.initHandler.done(), e.resolve(window.DSG.currentReport)
                }), e.promise()
            },
            openReport: function(t) {
                var e, n, a = this;
                try {
                    t = (e = (e = d.a.parseJSON(t)).reports).shift()
                } catch (t) {}
                try {
                    n = Object(Gt.a)(t)
                } catch (e) {
                    return c.e(0).then(c.bind(null, 594)).then(function(t) {
                        return t.create({
                            name: "Error happened during parsing report",
                            message: e.toString()
                        })
                    }), d.a.Deferred().reject("report was not parsed")
                }
                return d.a.when(this.clear()).then(function() {
                    return a.createReport()
                }).then(function() {
                    return Gt.c.parse(n)
                }).then(function(t) {
                    return e && e.length && e.forEach(function(t) {
                        Gt.c.extendCurrent(Object(Gt.a)(t))
                    }), t
                }).done(function(t) {
                    return t.pages.count(["ReportPage"]) || t.createPage(), Ht.a.trigger("init"), a.initHandler.done(), t
                })
            },
            _saveState: function(t) {
                var e = this;
                this.state = {
                    texts: {}
                }, t && window.DSG.currentReport.getComponents("TextObject").each(function() {
                    e.prop("mission") && (e.state.texts[e.prop("mission")] = {
                        Text: e.prop("Text"),
                        Width: e.prop("Width"),
                        Height: e.prop("Height")
                    })
                })
            },
            openReportByUUID: function(e, t) {
                var n = this,
                    a = d.a.Deferred(),
                    i = u.a.get("getReport", {
                        id: e,
                        rand_hash: Object(ce.a)()
                    });
                return this._saveState(!!t), d.a.when(Object(Gt.b)(i)).done(function(t) {
                    return n.openReport(t).done(function(t) {
                        t._id = e, a.resolve(t)
                    })
                }).fail(function() {
                    var e = arguments;
                    pe.error(arguments[2]), a.reject(), c.e(0).then(c.bind(null, 594)).then(function(t) {
                        return t.create({
                            name: e[1],
                            message: e[2]
                        })
                    })
                }), a.promise()
            },
            openDemoReport: function(t, e) {
                var n = this,
                    a = d.a.Deferred();
                //return this._saveState(!!e), d.a.when(Object(Gt.b)("https://dsg2014.fast-report.com:3000/api/demos/" + t, "jsonp")).done(function(t) {
                return this._saveState(!!e), d.a.when(Object(Gt.b)("https://desarrollo.sistemas24.com/fastreport/demos/" + t, "jsonp")).done(function(t) {
                    return n.openReport(t).done(function(t) {
                        a.resolve(t)
                    })
                }).fail(function() {
                    var e = arguments;
                    pe.log(arguments), pe.error(arguments[2]), a.reject(), c.e(0).then(c.bind(null, 594)).then(function(t) {
                        return t.create({
                            name: e[1],
                            message: e[2]
                        })
                    })
                }), a.promise()
            },
            clear: function() {
                var t;
                return !this.reportCreated || (t = d.a.Deferred(), Ht.a.clear(), window.DSG.customization && window.DSG.customization.clear(), window.DSG.currentReport.remove(), delete window.DSG.currentReport, t.resolve(), t.promise())
            }
        }), c(388), c(224), c(277), ue = arguments, he = window.DSG = {}, fe = new G.a, window.DSG.bands = {}, window.DSG.bands.ReportTitleBand = U.default, window.DSG.bands.ReportSummaryBand = X.default, window.DSG.bands.PageHeaderBand = Y.default, window.DSG.bands.PageFooterBand = K.default, window.DSG.bands.ColumnHeaderBand = J.default, window.DSG.bands.ColumnFooterBand = q.default, window.DSG.bands.DataHeaderBand = Z.default, window.DSG.bands.DataBand = Q.default, window.DSG.bands.DataFooterBand = tt.default, window.DSG.bands.GroupHeaderBand = et.default, window.DSG.bands.GroupFooterBand = nt.default, window.DSG.bands.ChildBand = at.default, window.DSG.bands.OverlayBand = it.default, window.DSG.components = {}, window.DSG.components.TextObject = rt.default, window.DSG.components.PictureObject = ot.default, window.DSG.components.ShapeObject = st.default, window.DSG.components.LineObject = lt.default, window.DSG.components.SubreportObject = ct.default, window.DSG.components.TableObject = pt.default, window.DSG.components.MatrixObject = dt.default, window.DSG.components.BarcodeObject = ut.default, window.DSG.components.RichObject = ht.default, window.DSG.components.CheckBoxObject = ft.default, window.DSG.components.CellularTextObject = gt.default, window.DSG.components.LinearGauge = mt.default, window.DSG.components.SimpleGauge = bt.default, window.DSG.components.RadialGauge = vt.default, window.DSG.components.SimpleProgressGauge = yt.default, window.DSG.components.HtmlObject = Ct.default, window.DSG.components.SVGObject = St.default, window.DSG.components.ContainerObject = xt.default, window.DSG.components.DigitalSignatureObject = wt.default, window.DSG.controls = {}, window.DSG.controls.ButtonControl = Tt.default, window.DSG.controls.CheckBoxControl = Bt.default, window.DSG.controls.CheckedListBoxControl = kt.default, window.DSG.controls.ComboBoxControl = $t.default, window.DSG.controls.DateTimePickerControl = Pt.default, window.DSG.controls.LabelControl = Dt.default, window.DSG.controls.ListBoxControl = Ot.default, window.DSG.controls.MonthCalendarControl = Mt.default, window.DSG.controls.RadioButtonControl = Et.default, window.DSG.controls.TextBoxControl = At.default, window.DSG.panels = {}, window.DSG.panels.Properties = jt.default, window.DSG.panels.Events = Ft.default, window.DSG.panels.ReportTree = Lt.default, window.DSG.panels.Data = Rt.default, window.DSG.panels.Preview = Nt.default, Promise.all([u.a.init(), qt.a.init(), I.a.init()]).then(function() {
            var t, e, n, a, i = d()(window);
            he.app = new de, he.head = new r, i.on("error", function() {
                return fe.error("error occured", ue)
            }), t = function() {
                navigator.onLine ? Object(_.a)("online", {
                    success: !0
                }) : Object(_.a)("offline", {
                    danger: !0
                })
            }, i.on("online", t), i.on("offline", t), he.app.initHandler(), (e = he.app, n = Object(ne.a)("uuid"), a = Object(ne.a)("demo"), n ? e.openReportByUUID(n).done(function() {
                return fe.info("report was opened")
            }) : a ? e.openDemoReport(a).done(function() {
                return fe.info("report was opened")
            }) : e.createStandardReport().done(function() {
                return fe.info("report was created")
            })).done(function() {
                fe.info("system initialised")
            })
        })
}]);