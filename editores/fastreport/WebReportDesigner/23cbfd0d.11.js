(window.webpackJsonp = window.webpackJsonp || []).push([[11], {
    578: function(e, n, t) {
        "use strict";
        var a, b, v, h, m, i, g, x, y, o;
        t.r(n),
        a = t(0),
        b = t.n(a),
        v = t(4),
        h = t(1),
        m = t(233),
        i = t(2),
        g = function(e) {
            return '\n        <div class="fr-expr">\n            <div class="fr-modal-body">\n                <div class="expr-body">\n                    <textarea>' + e + '</textarea>\n                </div>\n                <div class="expr-menu"></div>\n            </div>\n\n            <div class="fr-modal-footer content-right">\n                <button type="button" disabled="disabled" class="fr-btn fr-btn-primary add-to-expr">\n                    ' + i.a.tr("Style Add") + '\n                </button>\n                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\n                <button type="button" class="fr-btn fr-btn-primary edit-expr">\n                    ' + i.a.tr("Buttons Ok") + "\n                </button>\n            </div>\n        </div>\n    "
        }
        ,
        x = t(580),
        t.d(n, "expression", function() {
            return o
        }),
        y = {},
        o = {
            edit: function(t, n, e) {
                function a(e) {
                    var n = "string" != typeof t ? t.prop("Brackets") : v.a.get("brackets");
                    return n[0] + e + n[2]
                }
                function i(e) {
                    e.preventDefault(),
                    e.stopPropagation()
                }
                var o, r, s, l, c, d, p, u, f;
                return void 0 === t ? null : (o = new m.a("ExpressionEditor"),
                r = "string" == typeof t ? t : void 0 === t.prop(n) ? "" : t.prop(n),
                s = b()(g(r)),
                l = void 0,
                l = !!e && this.getMenu({
                    track: !1
                }),
                d = b()(".expr-menu", s),
                p = b()("textarea", s),
                u = b()(".add-to-expr", s),
                f = {
                    $popup: o,
                    $textarea: p,
                    onClick: function() {
                        var e = p.val();
                        t && t.prop(n) !== e && (t.prop(n, e, !0),
                        h.a.trigger("update-properties-panel", t),
                        t.render()),
                        o.close()
                    }
                },
                l ? (c = b()(".rt-content[data-component]", l),
                d.html(l),
                c.attr("draggable", !0),
                c.css("cursor", "move"),
                c.on("dragstart", function(e) {
                    var n = b()(e.target)
                      , t = n.parent()[0].element;
                    e.originalEvent.dataTransfer.setData("text", t.view || t.getView(n))
                }),
                p.on("dragover", i),
                p.on("dragenter", i),
                p.on("drop", function(e) {
                    var n = b()(e.target)
                      , t = e.originalEvent.dataTransfer.getData("text");
                    e.preventDefault(),
                    n.val(n.val() + a(t))
                }),
                l.on("click", ".rt-content", function() {
                    b()(this).is(".selected[data-component]") ? u.removeAttr("disabled") : u.attr("disabled", !0)
                }),
                u.on("click", function() {
                    var e = b()(".selected", l)
                      , n = e.parent()[0].element
                      , t = n.view || n.getView(e);
                    t && p.val(p.val() + a(t))
                })) : (p.parent().css("width", "100%"),
                u.remove(),
                d.remove()),
                s.on("click", ".edit-expr", function(e) {
                    return f.onClick.apply(this, [e, p.val(), o])
                }),
                b()(".fr-modal-content", o).html(s),
                f)
            },
            getMenu: function(e) {
                return this._getMenu(e || {})
            },
            _getMenu: function(e) {
                var n, t = e.track, a = e.openConnections, i = e.openSystemVariables, o = e.openTotals, r = e.openParameters, s = e.openFunctions, l = !1 === t ? {} : y, c = window.DSG.currentReport, d = c.connections, p = c.dataSources, u = c.systemVariables, f = c.totals, b = c.parameters, v = c.functions, m = [];
                return d && m.push({
                    label: "Dictionary DataSources",
                    icon: "icon-053",
                    fields: [].concat(d.all(), p.all()),
                    editable: [function(e) {
                        h.a.trigger("new-connection-wizard", e)
                    }
                    , null],
                    openedInTree: a
                }),
                u && m.push({
                    label: "Dictionary SystemVariables",
                    icon: u.attr("icon"),
                    fields: u.entities,
                    openedInTree: i
                }),
                f && m.push({
                    label: "Dictionary Totals",
                    icon: "icon-132",
                    fields: f.all(),
                    editable: [function(e) {
                        h.a.trigger("create-total", e)
                    }
                    , null],
                    openedInTree: o
                }),
                b && m.push({
                    label: "Dictionary Parameters",
                    icon: "icon-234",
                    fields: b.all(),
                    editable: [function(e) {
                        h.a.trigger("create-parameter", e)
                    }
                    , null],
                    openedInTree: r
                }),
                v && m.push({
                    label: "Dictionary Functions",
                    icon: v.icon,
                    fields: v.entities,
                    className: "functions",
                    openedInTree: s
                }),
                n = !0,
                Object(x.a)({
                    nodes: m,
                    selectable: !0,
                    openedInTree: l,
                    editable: n
                })
            }
        }
    },
    580: function(e, n, t) {
        "use strict";
        function c(e, n, t) {
            var a, i = y()("<ul>");
            return i.addClass("root"),
            e && (e.className && i.addClass("functions"),
            a = function t(n, e, a) {
                var i, o, r, s, l, c, d, p, u, f, b, v, m, h, g = 1 < arguments.length && void 0 !== e ? e : {}, x = 2 < arguments.length && void 0 !== a ? a : null;
                if (n) {
                    if (u = n.label && C.a.tr(n.label) || n.toString(),
                    (i = y()("<li>")).addClass("rt-node"),
                    (r = y()("<div>")).addClass("rt-expand"),
                    (l = y()("<div>")).addClass("rt-icon"),
                    null !== n.icon && l.addClass(n.icon || n.prop && n.prop("DataType") && n.prop("DataType").icon || n.attr && n.attr("icon") || w.a.first().icon),
                    (c = y()("<div>")).addClass("rt-content"),
                    c.text(u),
                    "function" == typeof n.attr && !0 === n.attr("checkbox") && ((s = y()('<input type="checkbox">')).prop("checked", n.attr("checked")),
                    s.addClass("rt-checkbox pull-left"),
                    "function" == typeof x && s.change(n, x)),
                    n.editable && (n.editable[0] && (c.append('<a href class="add"><i class="fa fa-plus-circle"></i></i></a>'),
                    c.on("click", ".add:not([disabled])", function() {
                        var e = y()(this).parents(".rt-node:first")[0].element;
                        return e && "function" == typeof n.editable[0] && n.editable[0](e),
                        !1
                    })),
                    n.editable[1] && (c.append('<a href class="remove"><i class="fa fa-minus-circle"></i></i></a>'),
                    c.on("click", ".remove:not([disabled])", function() {
                        var e = y()(this).parents(".rt-node:first")[0].element;
                        return e && "function" == typeof n.editable[1] && n.editable[1](e),
                        !1
                    }))),
                    n.interface && c.attr("data-interface", n.interface),
                    n.bindableControl && c.attr("data-component", n.bindableControl),
                    (d = y()("<ul>")).addClass("rt-container"),
                    n.fields ? v = n.fields : n.parameters && 0 < n.parameters.count() ? v = n.parameters.all() : n.columns || n.dataSources ? (n.columns && 0 < n.columns.count() && (v = n.columns.all()),
                    n.dataSources && 0 < n.dataSources.count() && (v = v ? v.concat(n.dataSources.all()) : [].concat(n.dataSources.all()))) : n.isRelation && n.isRelation() && (m = n.attr("_ParentDataSource")) && (v = m.columns.all(),
                    v = [].concat(m.asChildIn || [], v, m.dataSources.all())),
                    h = function(e) {
                        n.openedInTree || g[n._id || n.label] ? (r.addClass("icon-exp_minus"),
                        g[n._id || n.label] = !0) : (r.addClass("icon-exp_plus"),
                        d.addClass("d-hidden")),
                        y.a.each(e, function(e, n) {
                            o = t(n, g, x),
                            d.append(o)
                        }),
                        e.length && n.isColumn && n.isColumn() && l.attr("class", "rt-icon icon-233")
                    }
                    ,
                    v && ("function" == typeof v ? v().then(function() {
                        h.apply(this, arguments)
                    }).catch(function(e) {
                        "1" !== e.error.message && Object(k.a)(e.entity + " could not be loaded", {
                            danger: !0
                        })
                    }) : h(v)),
                    n.isDataSource && n.isDataSource() && (v || i.hide(),
                    n.asChildIn && n.asChildIn.length))
                        for (f = 0,
                        b = n.asChildIn.length; f < b; f += 1)
                            p = t(n.asChildIn[f], g, x),
                            d.prepend(p);
                    i[0].element = n,
                    i.append(r, s, l, c, d)
                }
                return i
            }(e, n, t),
            e.fields && "function" != typeof e.fields && (0 === e.fields.length || y.a.isPlainObject(e.fields) && y.a.isEmptyObject(e.fields)) && a.find(".rt-expand").css("opacity", 0),
            i.append(a)),
            i
        }
        function a(e) {
            var n = e.nodes
              , t = e.selectable
              , a = e.checkable
              , i = e.editable
              , o = e.openedInTree
              , r = void 0 === o ? {} : o
              , s = e.onCheckboxChange
              , l = y()("<div>");
            return l.addClass("data-main-container"),
            n.forEach(function(e) {
                l.append(c(e, r, s))
            }),
            l.on("click", ".rt-expand, .rt-icon", function() {
                var e, n = y()(this);
                return n.is(".rt-icon") && (n = n.prev(".rt-expand")),
                e = n.parent()[0].element,
                n.is(".icon-exp_minus") ? (delete r[e._id || e.label],
                n.parent().find(".rt-container:first").slideUp(100, function() {
                    n.removeClass("icon-exp_minus").addClass("icon-exp_plus")
                })) : n.is(".icon-exp_plus") && (r[e._id || e.label] = !0,
                n.parent().find(".rt-container:first").slideDown(100, function() {
                    n.removeClass("icon-exp_plus").addClass("icon-exp_minus")
                })),
                !1
            }),
            !0 === t && l.on("click", ".rt-content", function() {
                var e = y()(this)
                  , n = e.parents(".root")
                  , t = e.data("interface")
                  , a = y()("<div>");
                n.is(".functions") && t && (a.append(t),
                Object(k.a)(a, {
                    inEmptyList: !0,
                    limitWidth: !1,
                    delay: !1
                })),
                y()(".selected", l).removeClass("selected"),
                e.addClass("selected")
            }),
            a || l.find(".rt-checkbox").remove(),
            i || (l.find(".add").remove(),
            l.find(".remove").remove()),
            l
        }
        var i, o, y, C, k, w;
        t.d(n, "a", function() {
            return a
        }),
        i = t(581),
        o = t(0),
        y = t.n(o),
        C = t(2),
        k = t(27),
        w = t(29)
    },
    581: function(e, n, t) {}
}]);
