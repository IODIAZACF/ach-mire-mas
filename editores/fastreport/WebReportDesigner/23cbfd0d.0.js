(window.webpackJsonp = window.webpackJsonp || []).push([
    [0], {
        584: function(n, t, e) {},
        594: function(n, t, e) {
            "use strict";

            function r() {
                var n = 0 < arguments.length && void 0 !== arguments[0] ? arguments[0] : {},
                    r = n.name,
                    a = n.message;
                return Object(c.a)().then(function(n) {
                    var t = new i.a(r, {
                            danger: !0
                        }),
                        e = d()(l());
                    return t.find(".fr-modal-content").html(e), e.find(".alert-text").text(a), t.on("click", ".js-alert-ok-btn", function() {
                        return t.close()
                    }), n.append(t), window.DSG.head.put(n), t
                })
            }
            var a, d, i, o, l, c;
            e.r(t), e(584), a = e(0), d = e.n(a), i = e(233), o = e(2), l = function() {
                return '\n        <div>\n            <div class="fr-modal-body fr-alert-dialog">\n                <table>\n                    <tr>\n                        <td><div class="alert-mark">!</div></td>\n                        <td><div class="alert-text"></div></td>\n                    </tr>\n                </table>\n            </div>\n\n            <div class="fr-modal-footer">\n                <div class="pull-right">\n                    <button type="button" class="fr-btn fr-btn-danger js-alert-ok-btn">\n                        ' + o.a.tr("Ok") + "\n                    </button>\n                </div>\n            </div>\n        </div>\n    "
            }, c = e(161), e.d(t, "create", function() {
                return r
            })
        }
    }
]);