! function(t) {
    function i(e) {
        if (o[e]) return o[e].exports;
        var n = o[e] = {
            i: e,
            l: !1,
            exports: {}
        };
        return t[e].call(n.exports, n, n.exports, i), n.l = !0, n.exports
    }
    var o = {};
    i.m = t, i.c = o, i.d = function(e, n, t) {
        i.o(e, n) || Object.defineProperty(e, n, {
            enumerable: !0,
            get: t
        })
    }, i.r = function(e) {
        "undefined" != typeof Symbol && Symbol.toStringTag && Object.defineProperty(e, Symbol.toStringTag, {
            value: "Module"
        }), Object.defineProperty(e, "__esModule", {
            value: !0
        })
    }, i.t = function(n, e) {
        var t, o;
        if (1 & e && (n = i(n)), 8 & e) return n;
        if (4 & e && "object" == typeof n && n && n.__esModule) return n;
        if (t = Object.create(null), i.r(t), Object.defineProperty(t, "default", {
                enumerable: !0,
                value: n
            }), 2 & e && "string" != typeof n)
            for (o in n) i.d(t, o, function(e) {
                return n[e]
            }.bind(null, o));
        return t
    }, i.n = function(e) {
        var n = e && e.__esModule ? function() {
            return e.default
        } : function() {
            return e
        };
        return i.d(n, "a", n), n
    }, i.o = function(e, n) {
        return Object.prototype.hasOwnProperty.call(e, n)
    }, i.p = "", i(i.s = 302)
}({
    302: function(e, n, t) {
        "use strict";
        var o, i, r;
        t.r(n), o = t(37), (r = {
            version: "2021.3.6",
            hostAPI: i = window.hostAPI || "",
            cookieName: "ARRAffinity",
            saveReport: i + "../FastReport.Export.axd?putReport=#{id}",
            makePreview: i + "../FastReport.Export.axd?makePreview=#{id}",
            getReport: i + "../FastReport.Export.axd?getReport=#{id}&v=#{rand_hash}",
            getFunctions: i + "../FastReport.Export.axd?getFunctions=#{id}",
            getCustomConfig: i + "../FastReport.Export.axd?getDesignerConfig=#{id}",
            getConnectionTypes: i + "../FastReport.Export.axd?getConnectionTypes=#{id}",
            getConnectionTables: i + "../FastReport.Export.axd?getConnectionTables=#{id}&connectionType=#{connectionType}&connectionString=#{connectionString}",
            getConnectionStringProperties: i + "../FastReport.Export.axd?getConnectionStringProperties=#{id}&connectionType=#{connectionType}&connectionString=#{connectionString}",
            makeConnectionString: i + "../FastReport.Export.axd?makeConnectionString=#{id}&connectionType=#{connectionType}",
            "scale-mobile": .6,
            scale: 1,
            grid: 9.45,
            "sticky-grid": !0,
            hotkeyProhibited: !1,
            dasharrays: {
                DashDot: "9, 2, 2, 2",
                Dot: "2, 2",
                Solid: "",
                Dash: "9, 3",
                DashDotDot: "9, 2, 2, 2, 2, 2"
            },
            "default-dasharray": "Solid",
            languages: {
                ar: "Arabic",
                cs: "Czech",
                da: "Danish",
                de: "German",
                el: "Greek",
                en: "English",
                es: "Spanish",
                fa: "Persian",
                fr: "French",
                hr: "Croatian",
                hu: "Hungarian",
                hy: "Armenian",
                it: "Italian",
                nl: "Dutch",
                pl: "Polish",
                pt: "Portuguese",
                "pt-br": "Portuguese (Brazil)",
                ro: "Romanian",
                ru: "Russian",
                sk: "Slovak",
                sl: "Slovenian",
                sr: "Croatian",
                sv: "Swedish",
                th: "Thai",
                tr: "Turkish",
                uk: "Ukrainian",
                zh: "Chinese (Simplified)",
                "zh-Hant": "Chinese (Traditional)"
            },
            "font-names": ["Calibri", "Calibri Light", "Comic Sans MS", "Consolas", "Constantia", "Courier New", "Georgia", "Impact", "Tahoma", "Times New Roman", "Trebuchet MS", "Verdana", "Droid Sans Mono", "Ubuntu Mono", "Microsoft Sans Serif", "Palatino Linotype", "Lucida Console", "Lucida Sans Unicode", "Segoe Print", "Segoe Script", "Segoe UI", "Segoe UI Symbol", "Sylfaen", "Symbol", "Webdings", "Wingbings", "Cambria", "Arial", "Candara", "Corbel", "Franklin Gothic", "Gabriola", "Cambria Math"],
            "default-font-name": "Arial",
            "font-sizes": [5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 28, 30, 34, 36, 40, 48, 56, 72],
            brackets: "[,]",
            "band-indent-top": 9.448,
            "band-indent-opacity": .3,
            minComponentWidthForResizingElements: 40,
            minComponentHeightForResizingElements: 40,
            rectButtonWidth: 15,
            rectButtonHeight: 15,
            rectButtonFill: "#c25853",
            circleButtonWidth: 6,
            circleButtonHeight: 6,
            circleButtonRadius: 5,
            "circleButtonWidth-mobile": 12,
            "circleButtonHeight-mobile": 12,
            "circleButtonRadius-mobile": 10,
            resizingBandBlockWidth: 100,
            resizingBandBlockHeight: 15,
            polylineStroke: "#c25853",
            polylineStrokeWidth: "1px",
            selectedPolylineStrokeWidth: "2.5px",
            polylineFill: "none",
            polylineWidth: 4,
            lineMovingScope: 30,
            guides: !0,
            colors: {
                "button-circle": "#c25853",
                "angle-slider": "#c25853",
                "default-band-separator": "#C0C0C0",
                "selected-band-separator": "#2B579A"
            },
            "show-band-title": !0,
            "add-bands": !0,
            "sort-bands": !0,
            "resize-bands": !0,
            "movable-components": !0,
            "resizable-components": !0,
            "customization-toggler": !0,
            customization: {
                properties: {
                    enable: !0,
                    button: !0,
                    shown: !1,
                    header: !0,
                    hasBorder: !0,
                    movable: !0,
                    resizable: !0
                },
                events: {
                    enable: !0,
                    button: !0,
                    shown: !1,
                    header: !0,
                    hasBorder: !0,
                    movable: !0,
                    resizable: !0
                },
                "report-tree": {
                    enable: !0,
                    button: !0,
                    shown: !1,
                    header: !0,
                    hasBorder: !0,
                    movable: !0,
                    resizable: !0
                },
                data: {
                    enable: !0,
                    button: !0,
                    shown: !1,
                    header: !0,
                    hasBorder: !0,
                    movable: !0,
                    resizable: !0
                },
                preview: {
                    enable: !0,
                    shown: !0,
                    button: !1,
                    header: !1,
                    background: "initial",
                    position: o.e,
                    width: 125,
                    table: !0
                }
            },
            "default-tab-menu": "home",
            "show-saving-progress": "default",
            notifications: !1,
            "notifications-mobile": !1
        }).notifications = "default", r["notifications-mobile"] = "default", window.config = r, n.default = r
    },
    37: function(e, n, t) {
        "use strict";
        var o, i, r, a, s, c, l, d;
        t.d(n, "d", function() {
            return o
        }), t.d(n, "c", function() {
            return i
        }), t.d(n, "g", function() {
            return r
        }), t.d(n, "f", function() {
            return a
        }), t.d(n, "b", function() {
            return s
        }), t.d(n, "a", function() {
            return c
        }), t.d(n, "h", function() {
            return l
        }), t.d(n, "e", function() {
            return d
        }), o = 50, i = "200", r = 100, a = 20, c = s = 200, l = 0, d = 1
    }
});