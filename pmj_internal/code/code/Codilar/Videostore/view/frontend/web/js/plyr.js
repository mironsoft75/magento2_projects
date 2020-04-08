define([
    "jquery",
], function ($) {
    console.log("hello 2");
    "object" == typeof navigator && function(e, t) {
    "object" == typeof exports && "undefined" != typeof module ? module.exports = t() : "function" == typeof define && define.amd ? define("Plyr", t) : (e = e || self).Plyr = t()
}(this, function() {
    "use strict";

    function e(e, t) {
        if (!(e instanceof t)) throw new TypeError("Cannot call a class as a function")
    }

    function t(e, t) {
        for (var n = 0; n < t.length; n++) {
            var i = t[n];
            i.enumerable = i.enumerable || !1, i.configurable = !0, "value" in i && (i.writable = !0), Object.defineProperty(e, i.key, i)
        }
    }

    function n(e, n, i) {
        return n && t(e.prototype, n), i && t(e, i), e
    }

    function i(e, t, n) {
        return t in e ? Object.defineProperty(e, t, {
            value: n,
            enumerable: !0,
            configurable: !0,
            writable: !0
        }) : e[t] = n, e
    }

    function a(e, t) {
        return function(e) {
            if (Array.isArray(e)) return e
        }(e) || function(e, t) {
            var n = [],
                i = !0,
                a = !1,
                s = void 0;
            try {
                for (var o, r = e[Symbol.iterator](); !(i = (o = r.next()).done) && (n.push(o.value), !t || n.length !== t); i = !0);
            } catch (e) {
                a = !0, s = e
            } finally {
                try {
                    i || null == r.return || r.return()
                } finally {
                    if (a) throw s
                }
            }
            return n
        }(e, t) || function() {
            throw new TypeError("Invalid attempt to destructure non-iterable instance")
        }()
    }

    function s(e) {
        return function(e) {
            if (Array.isArray(e)) {
                for (var t = 0, n = new Array(e.length); t < e.length; t++) n[t] = e[t];
                return n
            }
        }(e) || function(e) {
            if (Symbol.iterator in Object(e) || "[object Arguments]" === Object.prototype.toString.call(e)) return Array.from(e)
        }(e) || function() {
            throw new TypeError("Invalid attempt to spread non-iterable instance")
        }()
    }
    var o = {
        addCSS: !0,
        thumbWidth: 15,
        watch: !0
    };
    var r = function(e) {
            return null != e ? e.constructor : null
        },
        l = function(e, t) {
            return Boolean(e && t && e instanceof t)
        },
        c = function(e) {
            return null == e
        },
        u = function(e) {
            return r(e) === Object
        },
        d = function(e) {
            return r(e) === String
        },
        h = function(e) {
            return Array.isArray(e)
        },
        m = function(e) {
            return l(e, NodeList)
        },
        p = {
            nullOrUndefined: c,
            object: u,
            number: function(e) {
                return r(e) === Number && !Number.isNaN(e)
            },
            string: d,
            boolean: function(e) {
                return r(e) === Boolean
            },
            function: function(e) {
                return r(e) === Function
            },
            array: h,
            nodeList: m,
            element: function(e) {
                return l(e, Element)
            },
            event: function(e) {
                return l(e, Event)
            },
            empty: function(e) {
                return c(e) || (d(e) || h(e) || m(e)) && !e.length || u(e) && !Object.keys(e).length
            }
        };

    function f(e, t) {
        if (t < 1) {
            var n = (i = "".concat(t).match(/(?:\.(\d+))?(?:[eE]([+-]?\d+))?$/)) ? Math.max(0, (i[1] ? i[1].length : 0) - (i[2] ? +i[2] : 0)) : 0;
            return parseFloat(e.toFixed(n))
        }
        var i;
        return Math.round(e / t) * t
    }
    var g = function() {
            function t(n, i) {
                e(this, t), p.element(n) ? this.element = n : p.string(n) && (this.element = document.querySelector(n)), p.element(this.element) && p.empty(this.element.rangeTouch) && (this.config = Object.assign({}, o, i), this.init())
            }
            return n(t, [{
                key: "init",
                value: function() {
                    t.enabled && (this.config.addCSS && (this.element.style.userSelect = "none", this.element.style.webKitUserSelect = "none", this.element.style.touchAction = "manipulation"), this.listeners(!0), this.element.rangeTouch = this)
                }
            }, {
                key: "destroy",
                value: function() {
                    t.enabled && (this.listeners(!1), this.element.rangeTouch = null)
                }
            }, {
                key: "listeners",
                value: function(e) {
                    var t = this,
                        n = e ? "addEventListener" : "removeEventListener";
                    ["touchstart", "touchmove", "touchend"].forEach(function(e) {
                        t.element[n](e, function(e) {
                            return t.set(e)
                        }, !1)
                    })
                }
            }, {
                key: "get",
                value: function(e) {
                    if (!t.enabled || !p.event(e)) return null;
                    var n, i = e.target,
                        a = e.changedTouches[0],
                        s = parseFloat(i.getAttribute("min")) || 0,
                        o = parseFloat(i.getAttribute("max")) || 100,
                        r = parseFloat(i.getAttribute("step")) || 1,
                        l = o - s,
                        c = i.getBoundingClientRect(),
                        u = 100 / c.width * (this.config.thumbWidth / 2) / 100;
                    return (n = 100 / c.width * (a.clientX - c.left)) < 0 ? n = 0 : n > 100 && (n = 100), n < 50 ? n -= (100 - 2 * n) * u : n > 50 && (n += 2 * (n - 50) * u), s + f(l * (n / 100), r)
                }
            }, {
                key: "set",
                value: function(e) {
                    t.enabled && p.event(e) && !e.target.disabled && (e.preventDefault(), e.target.value = this.get(e), function(e, t) {
                        if (e && t) {
                            var n = new Event(t);
                            e.dispatchEvent(n)
                        }
                    }(e.target, "touchend" === e.type ? "change" : "input"))
                }
            }], [{
                key: "setup",
                value: function(e) {
                    var n = arguments.length > 1 && void 0 !== arguments[1] ? arguments[1] : {},
                        i = null;
                    if (p.empty(e) || p.string(e) ? i = Array.from(document.querySelectorAll(p.string(e) ? e : 'input[type="range"]')) : p.element(e) ? i = [e] : p.nodeList(e) ? i = Array.from(e) : p.array(e) && (i = e.filter(p.element)), p.empty(i)) return null;
                    var a = Object.assign({}, o, n);
                    p.string(e) && a.watch && new MutationObserver(function(n) {
                        Array.from(n).forEach(function(n) {
                            Array.from(n.addedNodes).forEach(function(n) {
                                if (p.element(n) && function() {
                                        return Array.from(document.querySelectorAll(i)).includes(this)
                                    }.call(n, i = e)) {
                                    var i;
                                    new t(n, a)
                                }
                            })
                        })
                    }).observe(document.body, {
                        childList: !0,
                        subtree: !0
                    });
                    return i.map(function(e) {
                        return new t(e, n)
                    })
                }
            }, {
                key: "enabled",
                get: function() {
                    return "ontouchstart" in document.documentElement
                }
            }]), t
        }(),
        y = function(e) {
            return null != e ? e.constructor : null
        },
        v = function(e, t) {
            return Boolean(e && t && e instanceof t)
        },
        b = function(e) {
            return null == e
        },
        k = function(e) {
            return y(e) === Object
        },
        w = function(e) {
            return y(e) === String
        },
        T = function(e) {
            return Array.isArray(e)
        },
        C = function(e) {
            return v(e, NodeList)
        },
        A = function(e) {
            return b(e) || (w(e) || T(e) || C(e)) && !e.length || k(e) && !Object.keys(e).length
        },
        E = {
            nullOrUndefined: b,
            object: k,
            number: function(e) {
                return y(e) === Number && !Number.isNaN(e)
            },
            string: w,
            boolean: function(e) {
                return y(e) === Boolean
            },
            function: function(e) {
                return y(e) === Function
            },
            array: T,
            weakMap: function(e) {
                return v(e, WeakMap)
            },
            nodeList: C,
            element: function(e) {
                return v(e, Element)
            },
            textNode: function(e) {
                return y(e) === Text
            },
            event: function(e) {
                return v(e, Event)
            },
            keyboardEvent: function(e) {
                return v(e, KeyboardEvent)
            },
            cue: function(e) {
                return v(e, window.TextTrackCue) || v(e, window.VTTCue)
            },
            track: function(e) {
                return v(e, TextTrack) || !b(e) && w(e.kind)
            },
            promise: function(e) {
                return v(e, Promise)
            },
            url: function(e) {
                if (v(e, window.URL)) return !0;
                if (!w(e)) return !1;
                var t = e;
                e.startsWith("http://") && e.startsWith("https://") || (t = "http://".concat(e));
                try {
                    return !A(new URL(t).hostname)
                } catch (e) {
                    return !1
                }
            },
            empty: A
        },
        S = function() {
            var e = !1;
            try {
                var t = Object.defineProperty({}, "passive", {
                    get: function() {
                        return e = !0, null
                    }
                });
                window.addEventListener("test", null, t), window.removeEventListener("test", null, t)
            } catch (e) {}
            return e
        }();

    function P(e, t, n) {
        var i = this,
            a = arguments.length > 3 && void 0 !== arguments[3] && arguments[3],
            s = !(arguments.length > 4 && void 0 !== arguments[4]) || arguments[4],
            o = arguments.length > 5 && void 0 !== arguments[5] && arguments[5];
        if (e && "addEventListener" in e && !E.empty(t) && E.function(n)) {
            var r = t.split(" "),
                l = o;
            S && (l = {
                passive: s,
                capture: o
            }), r.forEach(function(t) {
                i && i.eventListeners && a && i.eventListeners.push({
                    element: e,
                    type: t,
                    callback: n,
                    options: l
                }), e[a ? "addEventListener" : "removeEventListener"](t, n, l)
            })
        }
    }

    function N(e) {
        var t = arguments.length > 1 && void 0 !== arguments[1] ? arguments[1] : "",
            n = arguments.length > 2 ? arguments[2] : void 0,
            i = !(arguments.length > 3 && void 0 !== arguments[3]) || arguments[3],
            a = arguments.length > 4 && void 0 !== arguments[4] && arguments[4];
        P.call(this, e, t, n, !0, i, a)
    }

    function M(e) {
        var t = arguments.length > 1 && void 0 !== arguments[1] ? arguments[1] : "",
            n = arguments.length > 2 ? arguments[2] : void 0,
            i = !(arguments.length > 3 && void 0 !== arguments[3]) || arguments[3],
            a = arguments.length > 4 && void 0 !== arguments[4] && arguments[4];
        P.call(this, e, t, n, !1, i, a)
    }

    function x(e) {
        var t = this,
            n = arguments.length > 1 && void 0 !== arguments[1] ? arguments[1] : "",
            i = arguments.length > 2 ? arguments[2] : void 0,
            a = !(arguments.length > 3 && void 0 !== arguments[3]) || arguments[3],
            s = arguments.length > 4 && void 0 !== arguments[4] && arguments[4];
        P.call(this, e, n, function o() {
            M(e, n, o, a, s);
            for (var r = arguments.length, l = new Array(r), c = 0; c < r; c++) l[c] = arguments[c];
            i.apply(t, l)
        }, !0, a, s)
    }

    function L(e) {
        var t = arguments.length > 1 && void 0 !== arguments[1] ? arguments[1] : "",
            n = arguments.length > 2 && void 0 !== arguments[2] && arguments[2],
            i = arguments.length > 3 && void 0 !== arguments[3] ? arguments[3] : {};
        if (E.element(e) && !E.empty(t)) {
            var a = new CustomEvent(t, {
                bubbles: n,
                detail: Object.assign({}, i, {
                    plyr: this
                })
            });
            e.dispatchEvent(a)
        }
    }

    function I(e, t) {
        var n = e.length ? e : [e];
        Array.from(n).reverse().forEach(function(e, n) {
            var i = n > 0 ? t.cloneNode(!0) : t,
                a = e.parentNode,
                s = e.nextSibling;
            i.appendChild(e), s ? a.insertBefore(i, s) : a.appendChild(i)
        })
    }

    function _(e, t) {
        E.element(e) && !E.empty(t) && Object.entries(t).filter(function(e) {
            var t = a(e, 2)[1];
            return !E.nullOrUndefined(t)
        }).forEach(function(t) {
            var n = a(t, 2),
                i = n[0],
                s = n[1];
            return e.setAttribute(i, s)
        })
    }

    function O(e, t, n) {
        var i = document.createElement(e);
        return E.object(t) && _(i, t), E.string(n) && (i.innerText = n), i
    }

    function j(e, t, n, i) {
        E.element(t) && t.appendChild(O(e, n, i))
    }

    function q(e) {
        E.nodeList(e) || E.array(e) ? Array.from(e).forEach(q) : E.element(e) && E.element(e.parentNode) && e.parentNode.removeChild(e)
    }

    function D(e) {
        if (E.element(e))
            for (var t = e.childNodes.length; t > 0;) e.removeChild(e.lastChild), t -= 1
    }

    function F(e, t) {
        return E.element(t) && E.element(t.parentNode) && E.element(e) ? (t.parentNode.replaceChild(e, t), e) : null
    }

    function H(e, t) {
        if (!E.string(e) || E.empty(e)) return {};
        var n = {},
            i = t;
        return e.split(",").forEach(function(e) {
            var t = e.trim(),
                a = t.replace(".", ""),
                s = t.replace(/[[\]]/g, "").split("="),
                o = s[0],
                r = s.length > 1 ? s[1].replace(/["']/g, "") : "";
            switch (t.charAt(0)) {
                case ".":
                    E.object(i) && E.string(i.class) && (i.class += " ".concat(a)), n.class = a;
                    break;
                case "#":
                    n.id = t.replace("#", "");
                    break;
                case "[":
                    n[o] = r
            }
        }), n
    }

    function R(e, t) {
        if (E.element(e)) {
            var n = t;
            E.boolean(n) || (n = !e.hidden), n ? e.setAttribute("hidden", "") : e.removeAttribute("hidden")
        }
    }

    function B(e, t, n) {
        if (E.nodeList(e)) return Array.from(e).map(function(e) {
            return B(e, t, n)
        });
        if (E.element(e)) {
            var i = "toggle";
            return void 0 !== n && (i = n ? "add" : "remove"), e.classList[i](t), e.classList.contains(t)
        }
        return !1
    }

    function V(e, t) {
        return E.element(e) && e.classList.contains(t)
    }

    function U(e, t) {
        return function() {
            return Array.from(document.querySelectorAll(t)).includes(this)
        }.call(e, t)
    }

    function W(e) {
        return this.elements.container.querySelectorAll(e)
    }

    function z(e) {
        return this.elements.container.querySelector(e)
    }

    function K() {
        var e = arguments.length > 0 && void 0 !== arguments[0] ? arguments[0] : null,
            t = arguments.length > 1 && void 0 !== arguments[1] && arguments[1];
        E.element(e) && (e.focus({
            preventScroll: !0
        }), t && B(e, this.config.classNames.tabFocus))
    }
    var Y, Q, X, J = (Y = document.createElement("span"), Q = {
        WebkitTransition: "webkitTransitionEnd",
        MozTransition: "transitionend",
        OTransition: "oTransitionEnd otransitionend",
        transition: "transitionend"
    }, X = Object.keys(Q).find(function(e) {
        return void 0 !== Y.style[e]
    }), !!E.string(X) && Q[X]);

    function $(e) {
        setTimeout(function() {
            try {
                R(e, !0), e.offsetHeight, R(e, !1)
            } catch (e) {}
        }, 0)
    }
    var G, Z = {
            isIE: !!document.documentMode,
            isEdge: window.navigator.userAgent.includes("Edge"),
            isWebkit: "WebkitAppearance" in document.documentElement.style && !/Edge/.test(navigator.userAgent),
            isIPhone: /(iPhone|iPod)/gi.test(navigator.platform),
            isIos: /(iPad|iPhone|iPod)/gi.test(navigator.platform)
        },
        ee = {
            "audio/ogg": "vorbis",
            "audio/wav": "1",
            "video/webm": "vp8, vorbis",
            "video/mp4": "avc1.42E01E, mp4a.40.2",
            "video/ogg": "theora"
        },
        te = {
            audio: "canPlayType" in document.createElement("audio"),
            video: "canPlayType" in document.createElement("video"),
            check: function(e, t, n) {
                var i = Z.isIPhone && n && te.playsinline,
                    a = te[e] || "html5" !== t;
                return {
                    api: a,
                    ui: a && te.rangeInput && ("video" !== e || !Z.isIPhone || i)
                }
            },
            pip: !(Z.isIPhone || !E.function(O("video").webkitSetPresentationMode) && (!document.pictureInPictureEnabled || O("video").disablePictureInPicture)),
            airplay: E.function(window.WebKitPlaybackTargetAvailabilityEvent),
            playsinline: "playsInline" in document.createElement("video"),
            mime: function(e) {
                if (E.empty(e)) return !1;
                var t = a(e.split("/"), 1)[0],
                    n = e;
                if (!this.isHTML5 || t !== this.type) return !1;
                Object.keys(ee).includes(n) && (n += '; codecs="'.concat(ee[e], '"'));
                try {
                    return Boolean(n && this.media.canPlayType(n).replace(/no/, ""))
                } catch (e) {
                    return !1
                }
            },
            textTracks: "textTracks" in document.createElement("video"),
            rangeInput: (G = document.createElement("input"), G.type = "range", "range" === G.type),
            touch: "ontouchstart" in document.documentElement,
            transitions: !1 !== J,
            reducedMotion: "matchMedia" in window && window.matchMedia("(prefers-reduced-motion)").matches
        };

    function ne(e) {
        return !!(E.array(e) || E.string(e) && e.includes(":")) && (E.array(e) ? e : e.split(":")).map(Number).every(E.number)
    }

    function ie(e) {
        var t = function(e) {
                return ne(e) ? e.split(":").map(Number) : null
            },
            n = t(e);
        return null === n && (n = t(this.config.ratio)), null === n && !E.empty(this.embed) && E.string(this.embed.ratio) && (n = t(this.embed.ratio)), n
    }

    function ae(e) {
        if (!this.isVideo) return {};
        var t = ie.call(this, e),
            n = a(E.array(t) ? t : [0, 0], 2),
            i = 100 / n[0] * n[1];
        if (this.elements.wrapper.style.paddingBottom = "".concat(i, "%"), this.isVimeo && this.supported.ui) {
            var s = (240 - i) / 4.8;
            this.media.style.transform = "translateY(-".concat(s, "%)")
        } else this.isHTML5 && this.elements.wrapper.classList.toggle(this.config.classNames.videoFixedRatio, null !== t);
        return {
            padding: i,
            ratio: t
        }
    }
    var se = {
        getSources: function() {
            var e = this;
            return this.isHTML5 ? Array.from(this.media.querySelectorAll("source")).filter(function(t) {
                var n = t.getAttribute("type");
                return !!E.empty(n) || te.mime.call(e, n)
            }) : []
        },
        getQualityOptions: function() {
            return se.getSources.call(this).map(function(e) {
                return Number(e.getAttribute("size"))
            }).filter(Boolean)
        },
        extend: function() {
            if (this.isHTML5) {
                var e = this;
                ae.call(e), Object.defineProperty(e.media, "quality", {
                    get: function() {
                        var t = se.getSources.call(e).find(function(t) {
                            return t.getAttribute("src") === e.source
                        });
                        return t && Number(t.getAttribute("size"))
                    },
                    set: function(t) {
                        var n = se.getSources.call(e).find(function(e) {
                            return Number(e.getAttribute("size")) === t
                        });
                        if (n) {
                            var i = e.media,
                                a = i.currentTime,
                                s = i.paused,
                                o = i.preload,
                                r = i.readyState;
                            e.media.src = n.getAttribute("src"), ("none" !== o || r) && (e.once("loadedmetadata", function() {
                                e.currentTime = a, s || e.play()
                            }), e.media.load()), L.call(e, e.media, "qualitychange", !1, {
                                quality: t
                            })
                        }
                    }
                })
            }
        },
        cancelRequests: function() {
            this.isHTML5 && (q(se.getSources.call(this)), this.media.setAttribute("src", this.config.blankVideo), this.media.load(), this.debug.log("Cancelled network requests"))
        }
    };

    function oe(e) {
        return E.array(e) ? e.filter(function(t, n) {
            return e.indexOf(t) === n
        }) : e
    }

    function re(e, t) {
        return t.split(".").reduce(function(e, t) {
            return e && e[t]
        }, e)
    }

    function le() {
        for (var e = arguments.length > 0 && void 0 !== arguments[0] ? arguments[0] : {}, t = arguments.length, n = new Array(t > 1 ? t - 1 : 0), a = 1; a < t; a++) n[a - 1] = arguments[a];
        if (!n.length) return e;
        var s = n.shift();
        return E.object(s) ? (Object.keys(s).forEach(function(t) {
            E.object(s[t]) ? (Object.keys(e).includes(t) || Object.assign(e, i({}, t, {})), le(e[t], s[t])) : Object.assign(e, i({}, t, s[t]))
        }), le.apply(void 0, [e].concat(n))) : e
    }

    function ce(e) {
        for (var t = arguments.length, n = new Array(t > 1 ? t - 1 : 0), i = 1; i < t; i++) n[i - 1] = arguments[i];
        return E.empty(e) ? e : e.toString().replace(/{(\d+)}/g, function(e, t) {
            return n[t].toString()
        })
    }

    function ue() {
        var e = arguments.length > 0 && void 0 !== arguments[0] ? arguments[0] : "",
            t = arguments.length > 1 && void 0 !== arguments[1] ? arguments[1] : "",
            n = arguments.length > 2 && void 0 !== arguments[2] ? arguments[2] : "";
        return e.replace(new RegExp(t.toString().replace(/([.*+?^=!:${}()|[\]\/\\])/g, "\\$1"), "g"), n.toString())
    }

    function de() {
        return (arguments.length > 0 && void 0 !== arguments[0] ? arguments[0] : "").toString().replace(/\w\S*/g, function(e) {
            return e.charAt(0).toUpperCase() + e.substr(1).toLowerCase()
        })
    }

    function he() {
        var e = (arguments.length > 0 && void 0 !== arguments[0] ? arguments[0] : "").toString();
        return (e = function() {
            var e = (arguments.length > 0 && void 0 !== arguments[0] ? arguments[0] : "").toString();
            return e = ue(e, "-", " "), e = ue(e, "_", " "), ue(e = de(e), " ", "")
        }(e)).charAt(0).toLowerCase() + e.slice(1)
    }

    function me(e) {
        var t = document.createElement("div");
        return t.appendChild(e), t.innerHTML
    }
    var pe = {
            pip: "PIP",
            airplay: "AirPlay",
            html5: "HTML5",
            vimeo: "Vimeo",
            youtube: "YouTube"
        },
        fe = function() {
            var e = arguments.length > 0 && void 0 !== arguments[0] ? arguments[0] : "",
                t = arguments.length > 1 && void 0 !== arguments[1] ? arguments[1] : {};
            if (E.empty(e) || E.empty(t)) return "";
            var n = re(t.i18n, e);
            if (E.empty(n)) return Object.keys(pe).includes(e) ? pe[e] : "";
            var i = {
                "{seektime}": t.seekTime,
                "{title}": t.title
            };
            return Object.entries(i).forEach(function(e) {
                var t = a(e, 2),
                    i = t[0],
                    s = t[1];
                n = ue(n, i, s)
            }), n
        },
        ge = function() {
            function t(n) {
                e(this, t), this.enabled = n.config.storage.enabled, this.key = n.config.storage.key
            }
            return n(t, [{
                key: "get",
                value: function(e) {
                    if (!t.supported || !this.enabled) return null;
                    var n = window.localStorage.getItem(this.key);
                    if (E.empty(n)) return null;
                    var i = JSON.parse(n);
                    return E.string(e) && e.length ? i[e] : i
                }
            }, {
                key: "set",
                value: function(e) {
                    if (t.supported && this.enabled && E.object(e)) {
                        var n = this.get();
                        E.empty(n) && (n = {}), le(n, e), window.localStorage.setItem(this.key, JSON.stringify(n))
                    }
                }
            }], [{
                key: "supported",
                get: function() {
                    try {
                        if (!("localStorage" in window)) return !1;
                        return window.localStorage.setItem("___test", "___test"), window.localStorage.removeItem("___test"), !0
                    } catch (e) {
                        return !1
                    }
                }
            }]), t
        }();

    function ye(e) {
        var t = arguments.length > 1 && void 0 !== arguments[1] ? arguments[1] : "text";
        return new Promise(function(n, i) {
            try {
                var a = new XMLHttpRequest;
                if (!("withCredentials" in a)) return;
                a.addEventListener("load", function() {
                    if ("text" === t) try {
                        n(JSON.parse(a.responseText))
                    } catch (e) {
                        n(a.responseText)
                    } else n(a.response)
                }), a.addEventListener("error", function() {
                    throw new Error(a.status)
                }), a.open("GET", e, !0), a.responseType = t, a.send()
            } catch (e) {
                i(e)
            }
        })
    }

    function ve(e, t) {
        if (E.string(e)) {
            var n = E.string(t),
                i = function() {
                    return null !== document.getElementById(t)
                },
                a = function(e, t) {
                    e.innerHTML = t, n && i() || document.body.insertAdjacentElement("afterbegin", e)
                };
            if (!n || !i()) {
                var s = ge.supported,
                    o = document.createElement("div");
                if (o.setAttribute("hidden", ""), n && o.setAttribute("id", t), s) {
                    var r = window.localStorage.getItem("".concat("cache", "-").concat(t));
                    if (null !== r) {
                        var l = JSON.parse(r);
                        a(o, l.content)
                    }
                }
                ye(e).then(function(e) {
                    E.empty(e) || (s && window.localStorage.setItem("".concat("cache", "-").concat(t), JSON.stringify({
                        content: e
                    })), a(o, e))
                }).catch(function() {})
            }
        }
    }
    var be = function(e) {
            return Math.trunc(e / 60 / 60 % 60, 10)
        },
        ke = function(e) {
            return Math.trunc(e / 60 % 60, 10)
        },
        we = function(e) {
            return Math.trunc(e % 60, 10)
        };

    function Te() {
        var e = arguments.length > 0 && void 0 !== arguments[0] ? arguments[0] : 0,
            t = arguments.length > 1 && void 0 !== arguments[1] && arguments[1],
            n = arguments.length > 2 && void 0 !== arguments[2] && arguments[2];
        if (!E.number(e)) return Te(null, t, n);
        var i = function(e) {
                return "0".concat(e).slice(-2)
            },
            a = be(e),
            s = ke(e),
            o = we(e);
        return a = t || a > 0 ? "".concat(a, ":") : "", "".concat(n && e > 0 ? "-" : "").concat(a).concat(i(s), ":").concat(i(o))
    }
    var Ce = {
        getIconUrl: function() {
            var e = new URL(this.config.iconUrl, window.location).host !== window.location.host || Z.isIE && !window.svg4everybody;
            return {
                url: this.config.iconUrl,
                cors: e
            }
        },
        findElements: function() {
            try {
                return this.elements.controls = z.call(this, this.config.selectors.controls.wrapper), this.elements.buttons = {
                    play: W.call(this, this.config.selectors.buttons.play),
                    pause: z.call(this, this.config.selectors.buttons.pause),
                    restart: z.call(this, this.config.selectors.buttons.restart),
                    rewind: z.call(this, this.config.selectors.buttons.rewind),
                    fastForward: z.call(this, this.config.selectors.buttons.fastForward),
                    mute: z.call(this, this.config.selectors.buttons.mute),
                    pip: z.call(this, this.config.selectors.buttons.pip),
                    airplay: z.call(this, this.config.selectors.buttons.airplay),
                    settings: z.call(this, this.config.selectors.buttons.settings),
                    captions: z.call(this, this.config.selectors.buttons.captions),
                    fullscreen: z.call(this, this.config.selectors.buttons.fullscreen)
                }, this.elements.progress = z.call(this, this.config.selectors.progress), this.elements.inputs = {
                    seek: z.call(this, this.config.selectors.inputs.seek),
                    volume: z.call(this, this.config.selectors.inputs.volume)
                }, this.elements.display = {
                    buffer: z.call(this, this.config.selectors.display.buffer),
                    currentTime: z.call(this, this.config.selectors.display.currentTime),
                    duration: z.call(this, this.config.selectors.display.duration)
                }, E.element(this.elements.progress) && (this.elements.display.seekTooltip = this.elements.progress.querySelector(".".concat(this.config.classNames.tooltip))), !0
            } catch (e) {
                return this.debug.warn("It looks like there is a problem with your custom controls HTML", e), this.toggleNativeControls(!0), !1
            }
        },
        createIcon: function(e, t) {
            var n = Ce.getIconUrl.call(this),
                i = "".concat(n.cors ? "" : n.url, "#").concat(this.config.iconPrefix),
                a = document.createElementNS("http://www.w3.org/2000/svg", "svg");
            _(a, le(t, {
                role: "presentation",
                focusable: "false"
            }));
            var s = document.createElementNS("http://www.w3.org/2000/svg", "use"),
                o = "".concat(i, "-").concat(e);
            return "href" in s && s.setAttributeNS("http://www.w3.org/1999/xlink", "href", o), s.setAttributeNS("http://www.w3.org/1999/xlink", "xlink:href", o), a.appendChild(s), a
        },
        createLabel: function(e) {
            var t = arguments.length > 1 && void 0 !== arguments[1] ? arguments[1] : {},
                n = fe(e, this.config);
            return O("span", Object.assign({}, t, {
                class: [t.class, this.config.classNames.hidden].filter(Boolean).join(" ")
            }), n)
        },
        createBadge: function(e) {
            if (E.empty(e)) return null;
            var t = O("span", {
                class: this.config.classNames.menu.value
            });
            return t.appendChild(O("span", {
                class: this.config.classNames.menu.badge
            }, e)), t
        },
        createButton: function(e, t) {
            var n = Object.assign({}, t),
                i = he(e),
                a = {
                    element: "button",
                    toggle: !1,
                    label: null,
                    icon: null,
                    labelPressed: null,
                    iconPressed: null
                };
            switch (["element", "icon", "label"].forEach(function(e) {
                Object.keys(n).includes(e) && (a[e] = n[e], delete n[e])
            }), "button" !== a.element || Object.keys(n).includes("type") || (n.type = "button"), Object.keys(n).includes("class") ? n.class.includes(this.config.classNames.control) || (n.class += " ".concat(this.config.classNames.control)) : n.class = this.config.classNames.control, e) {
                case "play":
                    a.toggle = !0, a.label = "play", a.labelPressed = "pause", a.icon = "play", a.iconPressed = "pause";
                    break;
                case "mute":
                    a.toggle = !0, a.label = "mute", a.labelPressed = "unmute", a.icon = "volume", a.iconPressed = "muted";
                    break;
                case "captions":
                    a.toggle = !0, a.label = "enableCaptions", a.labelPressed = "disableCaptions", a.icon = "captions-off", a.iconPressed = "captions-on";
                    break;
                case "fullscreen":
                    a.toggle = !0, a.label = "enterFullscreen", a.labelPressed = "exitFullscreen", a.icon = "enter-fullscreen", a.iconPressed = "exit-fullscreen";
                    break;
                case "play-large":
                    n.class += " ".concat(this.config.classNames.control, "--overlaid"), i = "play", a.label = "play", a.icon = "play";
                    break;
                default:
                    E.empty(a.label) && (a.label = i), E.empty(a.icon) && (a.icon = e)
            }
            var s = O(a.element);
            return a.toggle ? (s.appendChild(Ce.createIcon.call(this, a.iconPressed, {
                class: "icon--pressed"
            })), s.appendChild(Ce.createIcon.call(this, a.icon, {
                class: "icon--not-pressed"
            })), s.appendChild(Ce.createLabel.call(this, a.labelPressed, {
                class: "label--pressed"
            })), s.appendChild(Ce.createLabel.call(this, a.label, {
                class: "label--not-pressed"
            }))) : (s.appendChild(Ce.createIcon.call(this, a.icon)), s.appendChild(Ce.createLabel.call(this, a.label))), le(n, H(this.config.selectors.buttons[i], n)), _(s, n), "play" === i ? (E.array(this.elements.buttons[i]) || (this.elements.buttons[i] = []), this.elements.buttons[i].push(s)) : this.elements.buttons[i] = s, s
        },
        createRange: function(e, t) {
            var n = O("input", le(H(this.config.selectors.inputs[e]), {
                type: "range",
                min: 0,
                max: 100,
                step: .01,
                value: 0,
                autocomplete: "off",
                role: "slider",
                "aria-label": fe(e, this.config),
                "aria-valuemin": 0,
                "aria-valuemax": 100,
                "aria-valuenow": 0
            }, t));
            return this.elements.inputs[e] = n, Ce.updateRangeFill.call(this, n), g.setup(n), n
        },
        createProgress: function(e, t) {
            var n = O("progress", le(H(this.config.selectors.display[e]), {
                min: 0,
                max: 100,
                value: 0,
                role: "progressbar",
                "aria-hidden": !0
            }, t));
            if ("volume" !== e) {
                n.appendChild(O("span", null, "0"));
                var i = {
                        played: "played",
                        buffer: "buffered"
                    }[e],
                    a = i ? fe(i, this.config) : "";
                n.innerText = "% ".concat(a.toLowerCase())
            }
            return this.elements.display[e] = n, n
        },
        createTime: function(e) {
            var t = H(this.config.selectors.display[e]),
                n = O("div", le(t, {
                    class: "".concat(this.config.classNames.display.time, " ").concat(t.class ? t.class : "").trim(),
                    "aria-label": fe(e, this.config)
                }), "00:00");
            return this.elements.display[e] = n, n
        },
        bindMenuItemShortcuts: function(e, t) {
            var n = this;
            N(e, "keydown keyup", function(i) {
                if ([32, 38, 39, 40].includes(i.which) && (i.preventDefault(), i.stopPropagation(), "keydown" !== i.type)) {
                    var a, s = U(e, '[role="menuitemradio"]');
                    if (!s && [32, 39].includes(i.which)) Ce.showMenuPanel.call(n, t, !0);
                    else 32 !== i.which && (40 === i.which || s && 39 === i.which ? (a = e.nextElementSibling, E.element(a) || (a = e.parentNode.firstElementChild)) : (a = e.previousElementSibling, E.element(a) || (a = e.parentNode.lastElementChild)), K.call(n, a, !0))
                }
            }, !1), N(e, "keyup", function(e) {
                13 === e.which && Ce.focusFirstMenuItem.call(n, null, !0)
            })
        },
        createMenuItem: function(e) {
            var t = this,
                n = e.value,
                i = e.list,
                a = e.type,
                s = e.title,
                o = e.badge,
                r = void 0 === o ? null : o,
                l = e.checked,
                c = void 0 !== l && l,
                u = H(this.config.selectors.inputs[a]),
                d = O("button", le(u, {
                    type: "button",
                    role: "menuitemradio",
                    class: "".concat(this.config.classNames.control, " ").concat(u.class ? u.class : "").trim(),
                    "aria-checked": c,
                    value: n
                })),
                h = O("span");
            h.innerHTML = s, E.element(r) && h.appendChild(r), d.appendChild(h), Object.defineProperty(d, "checked", {
                enumerable: !0,
                get: function() {
                    return "true" === d.getAttribute("aria-checked")
                },
                set: function(e) {
                    e && Array.from(d.parentNode.children).filter(function(e) {
                        return U(e, '[role="menuitemradio"]')
                    }).forEach(function(e) {
                        return e.setAttribute("aria-checked", "false")
                    }), d.setAttribute("aria-checked", e ? "true" : "false")
                }
            }), this.listeners.bind(d, "click keyup", function(e) {
                if (!E.keyboardEvent(e) || 32 === e.which) {
                    switch (e.preventDefault(), e.stopPropagation(), d.checked = !0, a) {
                        case "language":
                            t.currentTrack = Number(n);
                            break;
                        case "quality":
                            t.quality = n;
                            break;
                        case "speed":
                            t.speed = parseFloat(n)
                    }
                    Ce.showMenuPanel.call(t, "home", E.keyboardEvent(e))
                }
            }, a, !1), Ce.bindMenuItemShortcuts.call(this, d, a), i.appendChild(d)
        },
        formatTime: function() {
            var e = arguments.length > 0 && void 0 !== arguments[0] ? arguments[0] : 0,
                t = arguments.length > 1 && void 0 !== arguments[1] && arguments[1];
            return E.number(e) ? Te(e, be(this.duration) > 0, t) : e
        },
        updateTimeDisplay: function() {
            var e = arguments.length > 0 && void 0 !== arguments[0] ? arguments[0] : null,
                t = arguments.length > 1 && void 0 !== arguments[1] ? arguments[1] : 0,
                n = arguments.length > 2 && void 0 !== arguments[2] && arguments[2];
            E.element(e) && E.number(t) && (e.innerText = Ce.formatTime(t, n))
        },
        updateVolume: function() {
            this.supported.ui && (E.element(this.elements.inputs.volume) && Ce.setRange.call(this, this.elements.inputs.volume, this.muted ? 0 : this.volume), E.element(this.elements.buttons.mute) && (this.elements.buttons.mute.pressed = this.muted || 0 === this.volume))
        },
        setRange: function(e) {
            var t = arguments.length > 1 && void 0 !== arguments[1] ? arguments[1] : 0;
            E.element(e) && (e.value = t, Ce.updateRangeFill.call(this, e))
        },
        updateProgress: function(e) {
            var t = this;
            if (this.supported.ui && E.event(e)) {
                var n, i, a = 0;
                if (e) switch (e.type) {
                    case "timeupdate":
                    case "seeking":
                    case "seeked":
                        n = this.currentTime, i = this.duration, a = 0 === n || 0 === i || Number.isNaN(n) || Number.isNaN(i) ? 0 : (n / i * 100).toFixed(2), "timeupdate" === e.type && Ce.setRange.call(this, this.elements.inputs.seek, a);
                        break;
                    case "playing":
                    case "progress":
                        ! function(e, n) {
                            var i = E.number(n) ? n : 0,
                                a = E.element(e) ? e : t.elements.display.buffer;
                            if (E.element(a)) {
                                a.value = i;
                                var s = a.getElementsByTagName("span")[0];
                                E.element(s) && (s.childNodes[0].nodeValue = i)
                            }
                        }(this.elements.display.buffer, 100 * this.buffered)
                }
            }
        },
        updateRangeFill: function(e) {
            var t = E.event(e) ? e.target : e;
            if (E.element(t) && "range" === t.getAttribute("type")) {
                if (U(t, this.config.selectors.inputs.seek)) {
                    t.setAttribute("aria-valuenow", this.currentTime);
                    var n = Ce.formatTime(this.currentTime),
                        i = Ce.formatTime(this.duration),
                        a = fe("seekLabel", this.config);
                    t.setAttribute("aria-valuetext", a.replace("{currentTime}", n).replace("{duration}", i))
                } else if (U(t, this.config.selectors.inputs.volume)) {
                    var s = 100 * t.value;
                    t.setAttribute("aria-valuenow", s), t.setAttribute("aria-valuetext", "".concat(s.toFixed(1), "%"))
                } else t.setAttribute("aria-valuenow", t.value);
                Z.isWebkit && t.style.setProperty("--value", "".concat(t.value / t.max * 100, "%"))
            }
        },
        updateSeekTooltip: function(e) {
            var t = this;
            if (this.config.tooltips.seek && E.element(this.elements.inputs.seek) && E.element(this.elements.display.seekTooltip) && 0 !== this.duration) {
                var n = 0,
                    i = this.elements.progress.getBoundingClientRect(),
                    a = "".concat(this.config.classNames.tooltip, "--visible"),
                    s = function(e) {
                        B(t.elements.display.seekTooltip, a, e)
                    };
                if (this.touch) s(!1);
                else {
                    if (E.event(e)) n = 100 / i.width * (e.pageX - i.left);
                    else {
                        if (!V(this.elements.display.seekTooltip, a)) return;
                        n = parseFloat(this.elements.display.seekTooltip.style.left, 10)
                    }
                    n < 0 ? n = 0 : n > 100 && (n = 100), Ce.updateTimeDisplay.call(this, this.elements.display.seekTooltip, this.duration / 100 * n), this.elements.display.seekTooltip.style.left = "".concat(n, "%"), E.event(e) && ["mouseenter", "mouseleave"].includes(e.type) && s("mouseenter" === e.type)
                }
            }
        },
        timeUpdate: function(e) {
            var t = !E.element(this.elements.display.duration) && this.config.invertTime;
            Ce.updateTimeDisplay.call(this, this.elements.display.currentTime, t ? this.duration - this.currentTime : this.currentTime, t), e && "timeupdate" === e.type && this.media.seeking || Ce.updateProgress.call(this, e)
        },
        durationUpdate: function() {
            if (this.supported.ui && (this.config.invertTime || !this.currentTime)) {
                if (this.duration >= Math.pow(2, 32)) return R(this.elements.display.currentTime, !0), void R(this.elements.progress, !0);
                E.element(this.elements.inputs.seek) && this.elements.inputs.seek.setAttribute("aria-valuemax", this.duration);
                var e = E.element(this.elements.display.duration);
                !e && this.config.displayDuration && this.paused && Ce.updateTimeDisplay.call(this, this.elements.display.currentTime, this.duration), e && Ce.updateTimeDisplay.call(this, this.elements.display.duration, this.duration), Ce.updateSeekTooltip.call(this)
            }
        },
        toggleMenuButton: function(e, t) {
            R(this.elements.settings.buttons[e], !t)
        },
        updateSetting: function(e, t, n) {
            var i = this.elements.settings.panels[e],
                a = null,
                s = t;
            if ("captions" === e) a = this.currentTrack;
            else {
                if (a = E.empty(n) ? this[e] : n, E.empty(a) && (a = this.config[e].default), !E.empty(this.options[e]) && !this.options[e].includes(a)) return void this.debug.warn("Unsupported value of '".concat(a, "' for ").concat(e));
                if (!this.config[e].options.includes(a)) return void this.debug.warn("Disabled value of '".concat(a, "' for ").concat(e))
            }
            if (E.element(s) || (s = i && i.querySelector('[role="menu"]')), E.element(s)) {
                this.elements.settings.buttons[e].querySelector(".".concat(this.config.classNames.menu.value)).innerHTML = Ce.getLabel.call(this, e, a);
                var o = s && s.querySelector('[value="'.concat(a, '"]'));
                E.element(o) && (o.checked = !0)
            }
        },
        getLabel: function(e, t) {
            switch (e) {
                case "speed":
                    return 1 === t ? fe("normal", this.config) : "".concat(t, "&times;");
                case "quality":
                    if (E.number(t)) {
                        var n = fe("qualityLabel.".concat(t), this.config);
                        return n.length ? n : "".concat(t, "p")
                    }
                    return de(t);
                case "captions":
                    return Se.getLabel.call(this);
                default:
                    return null
            }
        },
        setQualityMenu: function(e) {
            var t = this;
            if (E.element(this.elements.settings.panels.quality)) {
                var n = this.elements.settings.panels.quality.querySelector('[role="menu"]');
                E.array(e) && (this.options.quality = oe(e).filter(function(e) {
                    return t.config.quality.options.includes(e)
                }));
                var i = !E.empty(this.options.quality) && this.options.quality.length > 1;
                if (Ce.toggleMenuButton.call(this, "quality", i), D(n), Ce.checkMenu.call(this), i) {
                    var a = function(e) {
                        var n = fe("qualityBadge.".concat(e), t.config);
                        return n.length ? Ce.createBadge.call(t, n) : null
                    };
                    this.options.quality.sort(function(e, n) {
                        var i = t.config.quality.options;
                        return i.indexOf(e) > i.indexOf(n) ? 1 : -1
                    }).forEach(function(e) {
                        Ce.createMenuItem.call(t, {
                            value: e,
                            list: n,
                            type: "quality",
                            title: Ce.getLabel.call(t, "quality", e),
                            badge: a(e)
                        })
                    }), Ce.updateSetting.call(this, "quality", n)
                }
            }
        },
        setCaptionsMenu: function() {
            var e = this;
            if (E.element(this.elements.settings.panels.captions)) {
                var t = this.elements.settings.panels.captions.querySelector('[role="menu"]'),
                    n = Se.getTracks.call(this),
                    i = Boolean(n.length);
                if (Ce.toggleMenuButton.call(this, "captions", i), D(t), Ce.checkMenu.call(this), i) {
                    var a = n.map(function(n, i) {
                        return {
                            value: i,
                            checked: e.captions.toggled && e.currentTrack === i,
                            title: Se.getLabel.call(e, n),
                            badge: n.language && Ce.createBadge.call(e, n.language.toUpperCase()),
                            list: t,
                            type: "language"
                        }
                    });
                    a.unshift({
                        value: -1,
                        checked: !this.captions.toggled,
                        title: fe("disabled", this.config),
                        list: t,
                        type: "language"
                    }), a.forEach(Ce.createMenuItem.bind(this)), Ce.updateSetting.call(this, "captions", t)
                }
            }
        },
        setSpeedMenu: function(e) {
            var t = this;
            if (E.element(this.elements.settings.panels.speed)) {
                var n = this.elements.settings.panels.speed.querySelector('[role="menu"]');
                E.array(e) ? this.options.speed = e : (this.isHTML5 || this.isVimeo) && (this.options.speed = [.5, .75, 1, 1.25, 1.5, 1.75, 2]), this.options.speed = this.options.speed.filter(function(e) {
                    return t.config.speed.options.includes(e)
                });
                var i = !E.empty(this.options.speed) && this.options.speed.length > 1;
                Ce.toggleMenuButton.call(this, "speed", i), D(n), Ce.checkMenu.call(this), i && (this.options.speed.forEach(function(e) {
                    Ce.createMenuItem.call(t, {
                        value: e,
                        list: n,
                        type: "speed",
                        title: Ce.getLabel.call(t, "speed", e)
                    })
                }), Ce.updateSetting.call(this, "speed", n))
            }
        },
        checkMenu: function() {
            var e = this.elements.settings.buttons,
                t = !E.empty(e) && Object.values(e).some(function(e) {
                    return !e.hidden
                });
            R(this.elements.settings.menu, !t)
        },
        focusFirstMenuItem: function(e) {
            var t = arguments.length > 1 && void 0 !== arguments[1] && arguments[1];
            if (!this.elements.settings.popup.hidden) {
                var n = e;
                E.element(n) || (n = Object.values(this.elements.settings.panels).find(function(e) {
                    return !e.hidden
                }));
                var i = n.querySelector('[role^="menuitem"]');
                K.call(this, i, t)
            }
        },
        toggleMenu: function(e) {
            var t = this.elements.settings.popup,
                n = this.elements.buttons.settings;
            if (E.element(t) && E.element(n)) {
                var i = t.hidden,
                    a = i;
                if (E.boolean(e)) a = e;
                else if (E.keyboardEvent(e) && 27 === e.which) a = !1;
                else if (E.event(e)) {
                    var s = t.contains(e.target);
                    if (s || !s && e.target !== n && a) return
                }
                n.setAttribute("aria-expanded", a), R(t, !a), B(this.elements.container, this.config.classNames.menu.open, a), a && E.keyboardEvent(e) ? Ce.focusFirstMenuItem.call(this, null, !0) : a || i || K.call(this, n, E.keyboardEvent(e))
            }
        },
        getMenuSize: function(e) {
            var t = e.cloneNode(!0);
            t.style.position = "absolute", t.style.opacity = 0, t.removeAttribute("hidden"), e.parentNode.appendChild(t);
            var n = t.scrollWidth,
                i = t.scrollHeight;
            return q(t), {
                width: n,
                height: i
            }
        },
        showMenuPanel: function() {
            var e = this,
                t = arguments.length > 0 && void 0 !== arguments[0] ? arguments[0] : "",
                n = arguments.length > 1 && void 0 !== arguments[1] && arguments[1],
                i = document.getElementById("plyr-settings-".concat(this.id, "-").concat(t));
            if (E.element(i)) {
                var a = i.parentNode,
                    s = Array.from(a.children).find(function(e) {
                        return !e.hidden
                    });
                if (te.transitions && !te.reducedMotion) {
                    a.style.width = "".concat(s.scrollWidth, "px"), a.style.height = "".concat(s.scrollHeight, "px");
                    var o = Ce.getMenuSize.call(this, i);
                    N.call(this, a, J, function t(n) {
                        n.target === a && ["width", "height"].includes(n.propertyName) && (a.style.width = "", a.style.height = "", M.call(e, a, J, t))
                    }), a.style.width = "".concat(o.width, "px"), a.style.height = "".concat(o.height, "px")
                }
                R(s, !0), R(i, !1), Ce.focusFirstMenuItem.call(this, i, n)
            }
        },
        setDownloadLink: function() {
            var e = this.elements.buttons.download;
            E.element(e) && e.setAttribute("href", this.download)
        },
        create: function(e) {
            var t = this,
                n = O("div", H(this.config.selectors.controls.wrapper));
            if (this.config.controls.includes("restart") && n.appendChild(Ce.createButton.call(this, "restart")), this.config.controls.includes("rewind") && n.appendChild(Ce.createButton.call(this, "rewind")), this.config.controls.includes("play") && n.appendChild(Ce.createButton.call(this, "play")), this.config.controls.includes("fast-forward") && n.appendChild(Ce.createButton.call(this, "fast-forward")), this.config.controls.includes("progress")) {
                var i = O("div", H(this.config.selectors.progress));
                if (i.appendChild(Ce.createRange.call(this, "seek", {
                        id: "plyr-seek-".concat(e.id)
                    })), i.appendChild(Ce.createProgress.call(this, "buffer")), this.config.tooltips.seek) {
                    var a = O("span", {
                        class: this.config.classNames.tooltip
                    }, "00:00");
                    i.appendChild(a), this.elements.display.seekTooltip = a
                }
                this.elements.progress = i, n.appendChild(this.elements.progress)
            }
            if (this.config.controls.includes("current-time") && n.appendChild(Ce.createTime.call(this, "currentTime")), this.config.controls.includes("duration") && n.appendChild(Ce.createTime.call(this, "duration")), this.config.controls.includes("mute") || this.config.controls.includes("volume")) {
                var s = O("div", {
                    class: "plyr__volume"
                });
                if (this.config.controls.includes("mute") && s.appendChild(Ce.createButton.call(this, "mute")), this.config.controls.includes("volume")) {
                    var o = {
                        max: 1,
                        step: .05,
                        value: this.config.volume
                    };
                    s.appendChild(Ce.createRange.call(this, "volume", le(o, {
                        id: "plyr-volume-".concat(e.id)
                    }))), this.elements.volume = s
                }
                n.appendChild(s)
            }
            if (this.config.controls.includes("captions") && n.appendChild(Ce.createButton.call(this, "captions")), this.config.controls.includes("settings") && !E.empty(this.config.settings)) {
                var r = O("div", {
                    class: "plyr__menu",
                    hidden: ""
                });
                r.appendChild(Ce.createButton.call(this, "settings", {
                    "aria-haspopup": !0,
                    "aria-controls": "plyr-settings-".concat(e.id),
                    "aria-expanded": !1
                }));
                var l = O("div", {
                        class: "plyr__menu__container",
                        id: "plyr-settings-".concat(e.id),
                        hidden: ""
                    }),
                    c = O("div"),
                    u = O("div", {
                        id: "plyr-settings-".concat(e.id, "-home")
                    }),
                    d = O("div", {
                        role: "menu"
                    });
                u.appendChild(d), c.appendChild(u), this.elements.settings.panels.home = u, this.config.settings.forEach(function(n) {
                    var i = O("button", le(H(t.config.selectors.buttons.settings), {
                        type: "button",
                        class: "".concat(t.config.classNames.control, " ").concat(t.config.classNames.control, "--forward"),
                        role: "menuitem",
                        "aria-haspopup": !0,
                        hidden: ""
                    }));
                    Ce.bindMenuItemShortcuts.call(t, i, n), N(i, "click", function() {
                        Ce.showMenuPanel.call(t, n, !1)
                    });
                    var a = O("span", null, fe(n, t.config)),
                        s = O("span", {
                            class: t.config.classNames.menu.value
                        });
                    s.innerHTML = e[n], a.appendChild(s), i.appendChild(a), d.appendChild(i);
                    var o = O("div", {
                            id: "plyr-settings-".concat(e.id, "-").concat(n),
                            hidden: ""
                        }),
                        r = O("button", {
                            type: "button",
                            class: "".concat(t.config.classNames.control, " ").concat(t.config.classNames.control, "--back")
                        });
                    r.appendChild(O("span", {
                        "aria-hidden": !0
                    }, fe(n, t.config))), r.appendChild(O("span", {
                        class: t.config.classNames.hidden
                    }, fe("menuBack", t.config))), N(o, "keydown", function(e) {
                        37 === e.which && (e.preventDefault(), e.stopPropagation(), Ce.showMenuPanel.call(t, "home", !0))
                    }, !1), N(r, "click", function() {
                        Ce.showMenuPanel.call(t, "home", !1)
                    }), o.appendChild(r), o.appendChild(O("div", {
                        role: "menu"
                    })), c.appendChild(o), t.elements.settings.buttons[n] = i, t.elements.settings.panels[n] = o
                }), l.appendChild(c), r.appendChild(l), n.appendChild(r), this.elements.settings.popup = l, this.elements.settings.menu = r
            }
            if (this.config.controls.includes("pip") && te.pip && n.appendChild(Ce.createButton.call(this, "pip")), this.config.controls.includes("airplay") && te.airplay && n.appendChild(Ce.createButton.call(this, "airplay")), this.config.controls.includes("download")) {
                var h = {
                        element: "a",
                        href: this.download,
                        target: "_blank"
                    },
                    m = this.config.urls.download;
                !E.url(m) && this.isEmbed && le(h, {
                    icon: "logo-".concat(this.provider),
                    label: this.provider
                }), n.appendChild(Ce.createButton.call(this, "download", h))
            }
            return this.config.controls.includes("fullscreen") && n.appendChild(Ce.createButton.call(this, "fullscreen")), this.config.controls.includes("play-large") && this.elements.container.appendChild(Ce.createButton.call(this, "play-large")), this.elements.controls = n, this.isHTML5 && Ce.setQualityMenu.call(this, se.getQualityOptions.call(this)), Ce.setSpeedMenu.call(this), n
        },
        inject: function() {
            var e = this;
            if (this.config.loadSprite) {
                var t = Ce.getIconUrl.call(this);
                t.cors && ve(t.url, "sprite-plyr")
            }
            this.id = Math.floor(1e4 * Math.random());
            var n = null;
            this.elements.controls = null;
            var i = {
                    id: this.id,
                    seektime: this.config.seekTime,
                    title: this.config.title
                },
                s = !0;
            E.function(this.config.controls) && (this.config.controls = this.config.controls.call(this, i)), this.config.controls || (this.config.controls = []), E.element(this.config.controls) || E.string(this.config.controls) ? n = this.config.controls : (n = Ce.create.call(this, {
                id: this.id,
                seektime: this.config.seekTime,
                speed: this.speed,
                quality: this.quality,
                captions: Se.getLabel.call(this)
            }), s = !1);
            var o, r = function(e) {
                var t = e;
                return Object.entries(i).forEach(function(e) {
                    var n = a(e, 2),
                        i = n[0],
                        s = n[1];
                    t = ue(t, "{".concat(i, "}"), s)
                }), t
            };
            if (s && (E.string(this.config.controls) ? n = r(n) : E.element(n) && (n.innerHTML = r(n.innerHTML))), E.string(this.config.selectors.controls.container) && (o = document.querySelector(this.config.selectors.controls.container)), E.element(o) || (o = this.elements.container), o[E.element(n) ? "insertAdjacentElement" : "insertAdjacentHTML"]("afterbegin", n), E.element(this.elements.controls) || Ce.findElements.call(this), !E.empty(this.elements.buttons)) {
                var l = function(t) {
                    var n = e.config.classNames.controlPressed;
                    Object.defineProperty(t, "pressed", {
                        enumerable: !0,
                        get: function() {
                            return V(t, n)
                        },
                        set: function() {
                            var e = arguments.length > 0 && void 0 !== arguments[0] && arguments[0];
                            B(t, n, e)
                        }
                    })
                };
                Object.values(this.elements.buttons).filter(Boolean).forEach(function(e) {
                    E.array(e) || E.nodeList(e) ? Array.from(e).filter(Boolean).forEach(l) : l(e)
                })
            }
            if (Z.isEdge && $(o), this.config.tooltips.controls) {
                var c = this.config,
                    u = c.classNames,
                    d = c.selectors,
                    h = "".concat(d.controls.wrapper, " ").concat(d.labels, " .").concat(u.hidden),
                    m = W.call(this, h);
                Array.from(m).forEach(function(t) {
                    B(t, e.config.classNames.hidden, !1), B(t, e.config.classNames.tooltip, !0)
                })
            }
        }
    };

    function Ae(e) {
        var t = e;
        if (!(arguments.length > 1 && void 0 !== arguments[1]) || arguments[1]) {
            var n = document.createElement("a");
            n.href = t, t = n.href
        }
        try {
            return new URL(t)
        } catch (e) {
            return null
        }
    }

    function Ee(e) {
        var t = new URLSearchParams;
        return E.object(e) && Object.entries(e).forEach(function(e) {
            var n = a(e, 2),
                i = n[0],
                s = n[1];
            t.set(i, s)
        }), t
    }
    var Se = {
            setup: function() {
                if (this.supported.ui)
                    if (!this.isVideo || this.isYouTube || this.isHTML5 && !te.textTracks) E.array(this.config.controls) && this.config.controls.includes("settings") && this.config.settings.includes("captions") && Ce.setCaptionsMenu.call(this);
                    else {
                        var e, t;
                        if (E.element(this.elements.captions) || (this.elements.captions = O("div", H(this.config.selectors.captions)), e = this.elements.captions, t = this.elements.wrapper, E.element(e) && E.element(t) && t.parentNode.insertBefore(e, t.nextSibling)), Z.isIE && window.URL) {
                            var n = this.media.querySelectorAll("track");
                            Array.from(n).forEach(function(e) {
                                var t = e.getAttribute("src"),
                                    n = Ae(t);
                                null !== n && n.hostname !== window.location.href.hostname && ["http:", "https:"].includes(n.protocol) && ye(t, "blob").then(function(t) {
                                    e.setAttribute("src", window.URL.createObjectURL(t))
                                }).catch(function() {
                                    q(e)
                                })
                            })
                        }
                        var i = oe((navigator.languages || [navigator.language || navigator.userLanguage || "en"]).map(function(e) {
                                return e.split("-")[0]
                            })),
                            s = (this.storage.get("language") || this.config.captions.language || "auto").toLowerCase();
                        if ("auto" === s) s = a(i, 1)[0];
                        var o = this.storage.get("captions");
                        if (E.boolean(o) || (o = this.config.captions.active), Object.assign(this.captions, {
                                toggled: !1,
                                active: o,
                                language: s,
                                languages: i
                            }), this.isHTML5) {
                            var r = this.config.captions.update ? "addtrack removetrack" : "removetrack";
                            N.call(this, this.media.textTracks, r, Se.update.bind(this))
                        }
                        setTimeout(Se.update.bind(this), 0)
                    }
            },
            update: function() {
                var e = this,
                    t = Se.getTracks.call(this, !0),
                    n = this.captions,
                    i = n.active,
                    a = n.language,
                    s = n.meta,
                    o = n.currentTrackNode,
                    r = Boolean(t.find(function(e) {
                        return e.language === a
                    }));
                this.isHTML5 && this.isVideo && t.filter(function(e) {
                    return !s.get(e)
                }).forEach(function(t) {
                    e.debug.log("Track added", t), s.set(t, {
                        default: "showing" === t.mode
                    }), t.mode = "hidden", N.call(e, t, "cuechange", function() {
                        return Se.updateCues.call(e)
                    })
                }), (r && this.language !== a || !t.includes(o)) && (Se.setLanguage.call(this, a), Se.toggle.call(this, i && r)), B(this.elements.container, this.config.classNames.captions.enabled, !E.empty(t)), (this.config.controls || []).includes("settings") && this.config.settings.includes("captions") && Ce.setCaptionsMenu.call(this)
            },
            toggle: function(e) {
                var t = !(arguments.length > 1 && void 0 !== arguments[1]) || arguments[1];
                if (this.supported.ui) {
                    var n = this.captions.toggled,
                        i = this.config.classNames.captions.active,
                        a = E.nullOrUndefined(e) ? !n : e;
                    if (a !== n) {
                        if (t || (this.captions.active = a, this.storage.set({
                                captions: a
                            })), !this.language && a && !t) {
                            var o = Se.getTracks.call(this),
                                r = Se.findTrack.call(this, [this.captions.language].concat(s(this.captions.languages)), !0);
                            return this.captions.language = r.language, void Se.set.call(this, o.indexOf(r))
                        }
                        this.elements.buttons.captions && (this.elements.buttons.captions.pressed = a), B(this.elements.container, i, a), this.captions.toggled = a, Ce.updateSetting.call(this, "captions"), L.call(this, this.media, a ? "captionsenabled" : "captionsdisabled")
                    }
                }
            },
            set: function(e) {
                var t = !(arguments.length > 1 && void 0 !== arguments[1]) || arguments[1],
                    n = Se.getTracks.call(this);
                if (-1 !== e)
                    if (E.number(e))
                        if (e in n) {
                            if (this.captions.currentTrack !== e) {
                                this.captions.currentTrack = e;
                                var i = n[e],
                                    a = (i || {}).language;
                                this.captions.currentTrackNode = i, Ce.updateSetting.call(this, "captions"), t || (this.captions.language = a, this.storage.set({
                                    language: a
                                })), this.isVimeo && this.embed.enableTextTrack(a), L.call(this, this.media, "languagechange")
                            }
                            Se.toggle.call(this, !0, t), this.isHTML5 && this.isVideo && Se.updateCues.call(this)
                        } else this.debug.warn("Track not found", e);
                else this.debug.warn("Invalid caption argument", e);
                else Se.toggle.call(this, !1, t)
            },
            setLanguage: function(e) {
                var t = !(arguments.length > 1 && void 0 !== arguments[1]) || arguments[1];
                if (E.string(e)) {
                    var n = e.toLowerCase();
                    this.captions.language = n;
                    var i = Se.getTracks.call(this),
                        a = Se.findTrack.call(this, [n]);
                    Se.set.call(this, i.indexOf(a), t)
                } else this.debug.warn("Invalid language argument", e)
            },
            getTracks: function() {
                var e = this,
                    t = arguments.length > 0 && void 0 !== arguments[0] && arguments[0];
                return Array.from((this.media || {}).textTracks || []).filter(function(n) {
                    return !e.isHTML5 || t || e.captions.meta.has(n)
                }).filter(function(e) {
                    return ["captions", "subtitles"].includes(e.kind)
                })
            },
            findTrack: function(e) {
                var t, n = this,
                    i = arguments.length > 1 && void 0 !== arguments[1] && arguments[1],
                    a = Se.getTracks.call(this),
                    s = function(e) {
                        return Number((n.captions.meta.get(e) || {}).default)
                    },
                    o = Array.from(a).sort(function(e, t) {
                        return s(t) - s(e)
                    });
                return e.every(function(e) {
                    return !(t = o.find(function(t) {
                        return t.language === e
                    }))
                }), t || (i ? o[0] : void 0)
            },
            getCurrentTrack: function() {
                return Se.getTracks.call(this)[this.currentTrack]
            },
            getLabel: function(e) {
                var t = e;
                return !E.track(t) && te.textTracks && this.captions.toggled && (t = Se.getCurrentTrack.call(this)), E.track(t) ? E.empty(t.label) ? E.empty(t.language) ? fe("enabled", this.config) : e.language.toUpperCase() : t.label : fe("disabled", this.config)
            },
            updateCues: function(e) {
                if (this.supported.ui)
                    if (E.element(this.elements.captions))
                        if (E.nullOrUndefined(e) || Array.isArray(e)) {
                            var t = e;
                            if (!t) {
                                var n = Se.getCurrentTrack.call(this);
                                t = Array.from((n || {}).activeCues || []).map(function(e) {
                                    return e.getCueAsHTML()
                                }).map(me)
                            }
                            var i = t.map(function(e) {
                                return e.trim()
                            }).join("\n");
                            if (i !== this.elements.captions.innerHTML) {
                                D(this.elements.captions);
                                var a = O("span", H(this.config.selectors.caption));
                                a.innerHTML = i, this.elements.captions.appendChild(a), L.call(this, this.media, "cuechange")
                            }
                        } else this.debug.warn("updateCues: Invalid input", e);
                else this.debug.warn("No captions element to render to")
            }
        },
        Pe = {
            enabled: !0,
            title: "",
            debug: !1,
            autoplay: !1,
            autopause: !0,
            playsinline: !0,
            seekTime: 10,
            volume: 1,
            muted: !1,
            duration: null,
            displayDuration: !0,
            invertTime: !0,
            toggleInvert: !0,
            ratio: null,
            clickToPlay: !0,
            hideControls: !0,
            resetOnEnd: !1,
            disableContextMenu: !0,
            loadSprite: !0,
            iconPrefix: "plyr",
            iconUrl: "https://cdn.plyr.io/3.5.3/plyr.svg",
            blankVideo: "https://cdn.plyr.io/static/blank.mp4",
            quality: {
                default: 576,
                options: [4320, 2880, 2160, 1440, 1080, 720, 576, 480, 360, 240]
            },
            loop: {
                active: !1
            },
            speed: {
                selected: 1,
                options: [.5, .75, 1, 1.25, 1.5, 1.75, 2]
            },
            keyboard: {
                focused: !0,
                global: !1
            },
            tooltips: {
                controls: !1,
                seek: !0
            },
            captions: {
                active: !1,
                language: "auto",
                update: !1
            },
            fullscreen: {
                enabled: !0,
                fallback: !0,
                iosNative: !1
            },
            storage: {
                enabled: !0,
                key: "plyr"
            },
            controls: ["play-large", "play", "progress", "current-time", "mute", "volume", "captions", "settings", "pip", "airplay", "fullscreen"],
            settings: ["captions", "quality", "speed"],
            i18n: {
                restart: "Restart",
                rewind: "Rewind {seektime}s",
                play: "Play",
                pause: "Pause",
                fastForward: "Forward {seektime}s",
                seek: "Seek",
                seekLabel: "{currentTime} of {duration}",
                played: "Played",
                buffered: "Buffered",
                currentTime: "Current time",
                duration: "Duration",
                volume: "Volume",
                mute: "Mute",
                unmute: "Unmute",
                enableCaptions: "Enable captions",
                disableCaptions: "Disable captions",
                download: "Download",
                enterFullscreen: "Enter fullscreen",
                exitFullscreen: "Exit fullscreen",
                frameTitle: "Player for {title}",
                captions: "Captions",
                settings: "Settings",
                menuBack: "Go back to previous menu",
                speed: "Speed",
                normal: "Normal",
                quality: "Quality",
                loop: "Loop",
                start: "Start",
                end: "End",
                all: "All",
                reset: "Reset",
                disabled: "Disabled",
                enabled: "Enabled",
                advertisement: "Ad",
                qualityBadge: {
                    2160: "4K",
                    1440: "HD",
                    1080: "HD",
                    720: "HD",
                    576: "SD",
                    480: "SD"
                }
            },
            urls: {
                download: null,
                vimeo: {
                    sdk: "https://player.vimeo.com/api/player.js",
                    iframe: "https://player.vimeo.com/video/{0}?{1}",
                    api: "https://vimeo.com/api/v2/video/{0}.json"
                },
                youtube: {
                    sdk: "https://www.youtube.com/iframe_api",
                    api: "https://www.googleapis.com/youtube/v3/videos?id={0}&key={1}&fields=items(snippet(title))&part=snippet"
                },
                googleIMA: {
                    sdk: "https://imasdk.googleapis.com/js/sdkloader/ima3.js"
                }
            },
            listeners: {
                seek: null,
                play: null,
                pause: null,
                restart: null,
                rewind: null,
                fastForward: null,
                mute: null,
                volume: null,
                captions: null,
                download: null,
                fullscreen: null,
                pip: null,
                airplay: null,
                speed: null,
                quality: null,
                loop: null,
                language: null
            },
            events: ["ended", "progress", "stalled", "playing", "waiting", "canplay", "canplaythrough", "loadstart", "loadeddata", "loadedmetadata", "timeupdate", "volumechange", "play", "pause", "error", "seeking", "seeked", "emptied", "ratechange", "cuechange", "download", "enterfullscreen", "exitfullscreen", "captionsenabled", "captionsdisabled", "languagechange", "controlshidden", "controlsshown", "ready", "statechange", "qualitychange", "adsloaded", "adscontentpause", "adscontentresume", "adstarted", "adsmidpoint", "adscomplete", "adsallcomplete", "adsimpression", "adsclick"],
            selectors: {
                editable: "input, textarea, select, [contenteditable]",
                container: ".plyr",
                controls: {
                    container: null,
                    wrapper: ".plyr__controls"
                },
                labels: "[data-plyr]",
                buttons: {
                    play: '[data-plyr="play"]',
                    pause: '[data-plyr="pause"]',
                    restart: '[data-plyr="restart"]',
                    rewind: '[data-plyr="rewind"]',
                    fastForward: '[data-plyr="fast-forward"]',
                    mute: '[data-plyr="mute"]',
                    captions: '[data-plyr="captions"]',
                    download: '[data-plyr="download"]',
                    fullscreen: '[data-plyr="fullscreen"]',
                    pip: '[data-plyr="pip"]',
                    airplay: '[data-plyr="airplay"]',
                    settings: '[data-plyr="settings"]',
                    loop: '[data-plyr="loop"]'
                },
                inputs: {
                    seek: '[data-plyr="seek"]',
                    volume: '[data-plyr="volume"]',
                    speed: '[data-plyr="speed"]',
                    language: '[data-plyr="language"]',
                    quality: '[data-plyr="quality"]'
                },
                display: {
                    currentTime: ".plyr__time--current",
                    duration: ".plyr__time--duration",
                    buffer: ".plyr__progress__buffer",
                    loop: ".plyr__progress__loop",
                    volume: ".plyr__volume--display"
                },
                progress: ".plyr__progress",
                captions: ".plyr__captions",
                caption: ".plyr__caption",
                menu: {
                    quality: ".js-plyr__menu__list--quality"
                }
            },
            classNames: {
                type: "plyr--{0}",
                provider: "plyr--{0}",
                video: "plyr__video-wrapper",
                embed: "plyr__video-embed",
                videoFixedRatio: "plyr__video-wrapper--fixed-ratio",
                embedContainer: "plyr__video-embed__container",
                poster: "plyr__poster",
                posterEnabled: "plyr__poster-enabled",
                ads: "plyr__ads",
                control: "plyr__control",
                controlPressed: "plyr__control--pressed",
                playing: "plyr--playing",
                paused: "plyr--paused",
                stopped: "plyr--stopped",
                loading: "plyr--loading",
                hover: "plyr--hover",
                tooltip: "plyr__tooltip",
                cues: "plyr__cues",
                hidden: "plyr__sr-only",
                hideControls: "plyr--hide-controls",
                isIos: "plyr--is-ios",
                isTouch: "plyr--is-touch",
                uiSupported: "plyr--full-ui",
                noTransition: "plyr--no-transition",
                display: {
                    time: "plyr__time"
                },
                menu: {
                    value: "plyr__menu__value",
                    badge: "plyr__badge",
                    open: "plyr--menu-open"
                },
                captions: {
                    enabled: "plyr--captions-enabled",
                    active: "plyr--captions-active"
                },
                fullscreen: {
                    enabled: "plyr--fullscreen-enabled",
                    fallback: "plyr--fullscreen-fallback"
                },
                pip: {
                    supported: "plyr--pip-supported",
                    active: "plyr--pip-active"
                },
                airplay: {
                    supported: "plyr--airplay-supported",
                    active: "plyr--airplay-active"
                },
                tabFocus: "plyr__tab-focus",
                previewThumbnails: {
                    thumbContainer: "plyr__preview-thumb",
                    thumbContainerShown: "plyr__preview-thumb--is-shown",
                    imageContainer: "plyr__preview-thumb__image-container",
                    timeContainer: "plyr__preview-thumb__time-container",
                    scrubbingContainer: "plyr__preview-scrubbing",
                    scrubbingContainerShown: "plyr__preview-scrubbing--is-shown"
                }
            },
            attributes: {
                embed: {
                    provider: "data-plyr-provider",
                    id: "data-plyr-embed-id"
                }
            },
            keys: {
                google: null
            },
            ads: {
                enabled: !1,
                publisherId: "",
                tagUrl: ""
            },
            previewThumbnails: {
                enabled: !1,
                src: ""
            },
            vimeo: {
                byline: !1,
                portrait: !1,
                title: !1,
                speed: !0,
                transparent: !1
            },
            youtube: {
                noCookie: !1,
                rel: 0,
                showinfo: 0,
                iv_load_policy: 3,
                modestbranding: 1
            }
        },
        Ne = "picture-in-picture",
        Me = "inline",
        xe = {
            html5: "html5",
            youtube: "youtube",
            vimeo: "vimeo"
        },
        Le = {
            audio: "audio",
            video: "video"
        };
    var Ie = function() {},
        _e = function() {
            function t() {
                var n = arguments.length > 0 && void 0 !== arguments[0] && arguments[0];
                e(this, t), this.enabled = window.console && n, this.enabled && this.log("Debugging enabled")
            }
            return n(t, [{
                key: "log",
                get: function() {
                    return this.enabled ? Function.prototype.bind.call(console.log, console) : Ie
                }
            }, {
                key: "warn",
                get: function() {
                    return this.enabled ? Function.prototype.bind.call(console.warn, console) : Ie
                }
            }, {
                key: "error",
                get: function() {
                    return this.enabled ? Function.prototype.bind.call(console.error, console) : Ie
                }
            }]), t
        }();

    function Oe() {
        if (this.enabled) {
            var e = this.player.elements.buttons.fullscreen;
            E.element(e) && (e.pressed = this.active), L.call(this.player, this.target, this.active ? "enterfullscreen" : "exitfullscreen", !0), Z.isIos || function() {
                var e = arguments.length > 0 && void 0 !== arguments[0] ? arguments[0] : null,
                    t = arguments.length > 1 && void 0 !== arguments[1] && arguments[1];
                if (E.element(e)) {
                    var n = W.call(this, "button:not(:disabled), input:not(:disabled), [tabindex]"),
                        i = n[0],
                        a = n[n.length - 1];
                    P.call(this, this.elements.container, "keydown", function(e) {
                        if ("Tab" === e.key && 9 === e.keyCode) {
                            var t = document.activeElement;
                            t !== a || e.shiftKey ? t === i && e.shiftKey && (a.focus(), e.preventDefault()) : (i.focus(), e.preventDefault())
                        }
                    }, t, !1)
                }
            }.call(this.player, this.target, this.active)
        }
    }

    function je() {
        var e = this,
            t = arguments.length > 0 && void 0 !== arguments[0] && arguments[0];
        if (t ? this.scrollPosition = {
                x: window.scrollX || 0,
                y: window.scrollY || 0
            } : window.scrollTo(this.scrollPosition.x, this.scrollPosition.y), document.body.style.overflow = t ? "hidden" : "", B(this.target, this.player.config.classNames.fullscreen.fallback, t), Z.isIos) {
            var n = document.head.querySelector('meta[name="viewport"]'),
                i = "viewport-fit=cover";
            n || (n = document.createElement("meta")).setAttribute("name", "viewport");
            var a = E.string(n.content) && n.content.includes(i);
            t ? (this.cleanupViewport = !a, a || (n.content += ",".concat(i))) : this.cleanupViewport && (n.content = n.content.split(",").filter(function(e) {
                return e.trim() !== i
            }).join(",")), setTimeout(function() {
                return $(e.target)
            }, 100)
        }
        Oe.call(this)
    }
    var qe = function() {
        function t(n) {
            var i = this;
            e(this, t), this.player = n, this.prefix = t.prefix, this.property = t.property, this.scrollPosition = {
                x: 0,
                y: 0
            }, this.forceFallback = "force" === n.config.fullscreen.fallback, N.call(this.player, document, "ms" === this.prefix ? "MSFullscreenChange" : "".concat(this.prefix, "fullscreenchange"), function() {
                Oe.call(i)
            }), N.call(this.player, this.player.elements.container, "dblclick", function(e) {
                E.element(i.player.elements.controls) && i.player.elements.controls.contains(e.target) || i.toggle()
            }), this.update()
        }
        return n(t, [{
            key: "update",
            value: function() {
                var e;
                this.enabled ? (e = this.forceFallback ? "Fallback (forced)" : t.native ? "Native" : "Fallback", this.player.debug.log("".concat(e, " fullscreen enabled"))) : this.player.debug.log("Fullscreen not supported and fallback disabled");
                B(this.player.elements.container, this.player.config.classNames.fullscreen.enabled, this.enabled)
            }
        }, {
            key: "enter",
            value: function() {
                this.enabled && (Z.isIos && this.player.config.fullscreen.iosNative ? this.target.webkitEnterFullscreen() : !t.native || this.forceFallback ? je.call(this, !0) : this.prefix ? E.empty(this.prefix) || this.target["".concat(this.prefix, "Request").concat(this.property)]() : this.target.requestFullscreen())
            }
        }, {
            key: "exit",
            value: function() {
                if (this.enabled)
                    if (Z.isIos && this.player.config.fullscreen.iosNative) this.target.webkitExitFullscreen(), this.player.play();
                    else if (!t.native || this.forceFallback) je.call(this, !1);
                else if (this.prefix) {
                    if (!E.empty(this.prefix)) {
                        var e = "moz" === this.prefix ? "Cancel" : "Exit";
                        document["".concat(this.prefix).concat(e).concat(this.property)]()
                    }
                } else(document.cancelFullScreen || document.exitFullscreen).call(document)
            }
        }, {
            key: "toggle",
            value: function() {
                this.active ? this.exit() : this.enter()
            }
        }, {
            key: "usingNative",
            get: function() {
                return t.native && !this.forceFallback
            }
        }, {
            key: "enabled",
            get: function() {
                return (t.native || this.player.config.fullscreen.fallback) && this.player.config.fullscreen.enabled && this.player.supported.ui && this.player.isVideo
            }
        }, {
            key: "active",
            get: function() {
                return !!this.enabled && (!t.native || this.forceFallback ? V(this.target, this.player.config.classNames.fullscreen.fallback) : (this.prefix ? document["".concat(this.prefix).concat(this.property, "Element")] : document.fullscreenElement) === this.target)
            }
        }, {
            key: "target",
            get: function() {
                return Z.isIos && this.player.config.fullscreen.iosNative ? this.player.media : this.player.elements.container
            }
        }], [{
            key: "native",
            get: function() {
                return !!(document.fullscreenEnabled || document.webkitFullscreenEnabled || document.mozFullScreenEnabled || document.msFullscreenEnabled)
            }
        }, {
            key: "prefix",
            get: function() {
                if (E.function(document.exitFullscreen)) return "";
                var e = "";
                return ["webkit", "moz", "ms"].some(function(t) {
                    return !(!E.function(document["".concat(t, "ExitFullscreen")]) && !E.function(document["".concat(t, "CancelFullScreen")])) && (e = t, !0)
                }), e
            }
        }, {
            key: "property",
            get: function() {
                return "moz" === this.prefix ? "FullScreen" : "Fullscreen"
            }
        }]), t
    }();

    function De(e) {
        var t = arguments.length > 1 && void 0 !== arguments[1] ? arguments[1] : 1;
        return new Promise(function(n, i) {
            var a = new Image,
                s = function() {
                    delete a.onload, delete a.onerror, (a.naturalWidth >= t ? n : i)(a)
                };
            Object.assign(a, {
                onload: s,
                onerror: s,
                src: e
            })
        })
    }
    var Fe = {
            addStyleHook: function() {
                B(this.elements.container, this.config.selectors.container.replace(".", ""), !0), B(this.elements.container, this.config.classNames.uiSupported, this.supported.ui)
            },
            toggleNativeControls: function() {
                arguments.length > 0 && void 0 !== arguments[0] && arguments[0] && this.isHTML5 ? this.media.setAttribute("controls", "") : this.media.removeAttribute("controls")
            },
            build: function() {
                var e = this;
                if (this.listeners.media(), !this.supported.ui) return this.debug.warn("Basic support only for ".concat(this.provider, " ").concat(this.type)), void Fe.toggleNativeControls.call(this, !0);
                E.element(this.elements.controls) || (Ce.inject.call(this), this.listeners.controls()), Fe.toggleNativeControls.call(this), this.isHTML5 && Se.setup.call(this), this.volume = null, this.muted = null, this.speed = null, this.loop = null, this.quality = null, Ce.updateVolume.call(this), Ce.timeUpdate.call(this), Fe.checkPlaying.call(this), B(this.elements.container, this.config.classNames.pip.supported, te.pip && this.isHTML5 && this.isVideo), B(this.elements.container, this.config.classNames.airplay.supported, te.airplay && this.isHTML5), B(this.elements.container, this.config.classNames.isIos, Z.isIos), B(this.elements.container, this.config.classNames.isTouch, this.touch), this.ready = !0, setTimeout(function() {
                    L.call(e, e.media, "ready")
                }, 0), Fe.setTitle.call(this), this.poster && Fe.setPoster.call(this, this.poster, !1).catch(function() {}), this.config.duration && Ce.durationUpdate.call(this)
            },
            setTitle: function() {
                var e = fe("play", this.config);
                if (E.string(this.config.title) && !E.empty(this.config.title) && (e += ", ".concat(this.config.title)), Array.from(this.elements.buttons.play || []).forEach(function(t) {
                        t.setAttribute("aria-label", e)
                    }), this.isEmbed) {
                    var t = z.call(this, "iframe");
                    if (!E.element(t)) return;
                    var n = E.empty(this.config.title) ? "video" : this.config.title,
                        i = fe("frameTitle", this.config);
                    t.setAttribute("title", i.replace("{title}", n))
                }
            },
            togglePoster: function(e) {
                B(this.elements.container, this.config.classNames.posterEnabled, e)
            },
            setPoster: function(e) {
                var t = this;
                return arguments.length > 1 && void 0 !== arguments[1] && !arguments[1] || !this.poster ? (this.media.setAttribute("poster", e), function() {
                    var e = this;
                    return new Promise(function(t) {
                        return e.ready ? setTimeout(t, 0) : N.call(e, e.elements.container, "ready", t)
                    }).then(function() {})
                }.call(this).then(function() {
                    return De(e)
                }).catch(function(n) {
                    throw e === t.poster && Fe.togglePoster.call(t, !1), n
                }).then(function() {
                    if (e !== t.poster) throw new Error("setPoster cancelled by later call to setPoster")
                }).then(function() {
                    return Object.assign(t.elements.poster.style, {
                        backgroundImage: "url('".concat(e, "')"),
                        backgroundSize: ""
                    }), Fe.togglePoster.call(t, !0), e
                })) : Promise.reject(new Error("Poster already set"))
            },
            checkPlaying: function(e) {
                var t = this;
                B(this.elements.container, this.config.classNames.playing, this.playing), B(this.elements.container, this.config.classNames.paused, this.paused), B(this.elements.container, this.config.classNames.stopped, this.stopped), Array.from(this.elements.buttons.play || []).forEach(function(e) {
                    e.pressed = t.playing
                }), E.event(e) && "timeupdate" === e.type || Fe.toggleControls.call(this)
            },
            checkLoading: function(e) {
                var t = this;
                this.loading = ["stalled", "waiting"].includes(e.type), clearTimeout(this.timers.loading), this.timers.loading = setTimeout(function() {
                    B(t.elements.container, t.config.classNames.loading, t.loading), Fe.toggleControls.call(t)
                }, this.loading ? 250 : 0)
            },
            toggleControls: function(e) {
                var t = this.elements.controls;
                if (t && this.config.hideControls) {
                    var n = this.touch && this.lastSeekTime + 2e3 > Date.now();
                    this.toggleControls(Boolean(e || this.loading || this.paused || t.pressed || t.hover || n))
                }
            }
        },
        He = function() {
            function t(n) {
                e(this, t), this.player = n, this.lastKey = null, this.focusTimer = null, this.lastKeyDown = null, this.handleKey = this.handleKey.bind(this), this.toggleMenu = this.toggleMenu.bind(this), this.setTabFocus = this.setTabFocus.bind(this), this.firstTouch = this.firstTouch.bind(this)
            }
            return n(t, [{
                key: "handleKey",
                value: function(e) {
                    var t = this.player,
                        n = t.elements,
                        i = e.keyCode ? e.keyCode : e.which,
                        a = "keydown" === e.type,
                        s = a && i === this.lastKey;
                    if (!(e.altKey || e.ctrlKey || e.metaKey || e.shiftKey) && E.number(i)) {
                        if (a) {
                            var o = document.activeElement;
                            if (E.element(o)) {
                                var r = t.config.selectors.editable;
                                if (o !== n.inputs.seek && U(o, r)) return;
                                if (32 === e.which && U(o, 'button, [role^="menuitem"]')) return
                            }
                            switch ([32, 37, 38, 39, 40, 48, 49, 50, 51, 52, 53, 54, 56, 57, 67, 70, 73, 75, 76, 77, 79].includes(i) && (e.preventDefault(), e.stopPropagation()), i) {
                                case 48:
                                case 49:
                                case 50:
                                case 51:
                                case 52:
                                case 53:
                                case 54:
                                case 55:
                                case 56:
                                case 57:
                                    s || (t.currentTime = t.duration / 10 * (i - 48));
                                    break;
                                case 32:
                                case 75:
                                    s || t.togglePlay();
                                    break;
                                case 38:
                                    t.increaseVolume(.1);
                                    break;
                                case 40:
                                    t.decreaseVolume(.1);
                                    break;
                                case 77:
                                    s || (t.muted = !t.muted);
                                    break;
                                case 39:
                                    t.forward();
                                    break;
                                case 37:
                                    t.rewind();
                                    break;
                                case 70:
                                    t.fullscreen.toggle();
                                    break;
                                case 67:
                                    s || t.toggleCaptions();
                                    break;
                                case 76:
                                    t.loop = !t.loop
                            }
                            27 === i && !t.fullscreen.usingNative && t.fullscreen.active && t.fullscreen.toggle(), this.lastKey = i
                        } else this.lastKey = null
                    }
                }
            }, {
                key: "toggleMenu",
                value: function(e) {
                    Ce.toggleMenu.call(this.player, e)
                }
            }, {
                key: "firstTouch",
                value: function() {
                    var e = this.player,
                        t = e.elements;
                    e.touch = !0, B(t.container, e.config.classNames.isTouch, !0)
                }
            }, {
                key: "setTabFocus",
                value: function(e) {
                    var t = this.player,
                        n = t.elements;
                    if (clearTimeout(this.focusTimer), "keydown" !== e.type || 9 === e.which) {
                        "keydown" === e.type && (this.lastKeyDown = e.timeStamp);
                        var i, a = e.timeStamp - this.lastKeyDown <= 20;
                        if ("focus" !== e.type || a) i = t.config.classNames.tabFocus, B(W.call(t, ".".concat(i)), i, !1), this.focusTimer = setTimeout(function() {
                            var e = document.activeElement;
                            n.container.contains(e) && B(document.activeElement, t.config.classNames.tabFocus, !0)
                        }, 10)
                    }
                }
            }, {
                key: "global",
                value: function() {
                    var e = !(arguments.length > 0 && void 0 !== arguments[0]) || arguments[0],
                        t = this.player;
                    t.config.keyboard.global && P.call(t, window, "keydown keyup", this.handleKey, e, !1), P.call(t, document.body, "click", this.toggleMenu, e), x.call(t, document.body, "touchstart", this.firstTouch), P.call(t, document.body, "keydown focus blur", this.setTabFocus, e, !1, !0)
                }
            }, {
                key: "container",
                value: function() {
                    var e = this,
                        t = this.player,
                        n = t.config,
                        i = t.elements,
                        s = t.timers;
                    !n.keyboard.global && n.keyboard.focused && N.call(t, i.container, "keydown keyup", this.handleKey, !1), N.call(t, i.container, "mousemove mouseleave touchstart touchmove enterfullscreen exitfullscreen", function(e) {
                        var n = i.controls;
                        n && "enterfullscreen" === e.type && (n.pressed = !1, n.hover = !1);
                        var a = 0;
                        ["touchstart", "touchmove", "mousemove"].includes(e.type) && (Fe.toggleControls.call(t, !0), a = t.touch ? 3e3 : 2e3), clearTimeout(s.controls), s.controls = setTimeout(function() {
                            return Fe.toggleControls.call(t, !1)
                        }, a)
                    });
                    var o = function(e) {
                            if (!e) return ae.call(t);
                            var n = i.container.getBoundingClientRect(),
                                a = n.width,
                                s = n.height;
                            return ae.call(t, "".concat(a, ":").concat(s))
                        },
                        r = function() {
                            window.clearTimeout(s.resized), s.resized = window.setTimeout(o, 50)
                        };
                    N.call(t, i.container, "enterfullscreen exitfullscreen", function(n) {
                        var s = t.fullscreen,
                            l = s.target,
                            c = s.usingNative;
                        if (t.isEmbed && l === i.container) {
                            var u = "enterfullscreen" === n.type,
                                d = o(u);
                            d.padding;
                            ! function(n, i, s) {
                                if (t.isVimeo) {
                                    var o = t.elements.wrapper.firstChild,
                                        r = a(n, 2)[1],
                                        l = a(ie.call(e), 2),
                                        c = l[0],
                                        u = l[1];
                                    o.style.maxWidth = s ? "".concat(r / u * c, "px") : null, o.style.margin = s ? "0 auto" : null
                                }
                            }(d.ratio, 0, u), c || (u ? N.call(t, window, "resize", r) : M.call(t, window, "resize", r))
                        }
                    })
                }
            }, {
                key: "media",
                value: function() {
                    var e = this,
                        t = this.player,
                        n = t.elements;
                    if (N.call(t, t.media, "timeupdate seeking seeked", function(e) {
                            return Ce.timeUpdate.call(t, e)
                        }), N.call(t, t.media, "durationchange loadeddata loadedmetadata", function(e) {
                            return Ce.durationUpdate.call(t, e)
                        }), N.call(t, t.media, "canplay loadeddata", function() {
                            R(n.volume, !t.hasAudio), R(n.buttons.mute, !t.hasAudio)
                        }), N.call(t, t.media, "ended", function() {
                            t.isHTML5 && t.isVideo && t.config.resetOnEnd && t.restart()
                        }), N.call(t, t.media, "progress playing seeking seeked", function(e) {
                            return Ce.updateProgress.call(t, e)
                        }), N.call(t, t.media, "volumechange", function(e) {
                            return Ce.updateVolume.call(t, e)
                        }), N.call(t, t.media, "playing play pause ended emptied timeupdate", function(e) {
                            return Fe.checkPlaying.call(t, e)
                        }), N.call(t, t.media, "waiting canplay seeked playing", function(e) {
                            return Fe.checkLoading.call(t, e)
                        }), t.supported.ui && t.config.clickToPlay && !t.isAudio) {
                        var i = z.call(t, ".".concat(t.config.classNames.video));
                        if (!E.element(i)) return;
                        N.call(t, n.container, "click", function(a) {
                            ([n.container, i].includes(a.target) || i.contains(a.target)) && (t.touch && t.config.hideControls || (t.ended ? (e.proxy(a, t.restart, "restart"), e.proxy(a, t.play, "play")) : e.proxy(a, t.togglePlay, "play")))
                        })
                    }
                    t.supported.ui && t.config.disableContextMenu && N.call(t, n.wrapper, "contextmenu", function(e) {
                        e.preventDefault()
                    }, !1), N.call(t, t.media, "volumechange", function() {
                        t.storage.set({
                            volume: t.volume,
                            muted: t.muted
                        })
                    }), N.call(t, t.media, "ratechange", function() {
                        Ce.updateSetting.call(t, "speed"), t.storage.set({
                            speed: t.speed
                        })
                    }), N.call(t, t.media, "qualitychange", function(e) {
                        Ce.updateSetting.call(t, "quality", null, e.detail.quality)
                    }), N.call(t, t.media, "ready qualitychange", function() {
                        Ce.setDownloadLink.call(t)
                    });
                    var a = t.config.events.concat(["keyup", "keydown"]).join(" ");
                    N.call(t, t.media, a, function(e) {
                        var i = e.detail,
                            a = void 0 === i ? {} : i;
                        "error" === e.type && (a = t.media.error), L.call(t, n.container, e.type, !0, a)
                    })
                }
            }, {
                key: "proxy",
                value: function(e, t, n) {
                    var i = this.player,
                        a = i.config.listeners[n],
                        s = !0;
                    E.function(a) && (s = a.call(i, e)), s && E.function(t) && t.call(i, e)
                }
            }, {
                key: "bind",
                value: function(e, t, n, i) {
                    var a = this,
                        s = !(arguments.length > 4 && void 0 !== arguments[4]) || arguments[4],
                        o = this.player,
                        r = o.config.listeners[i],
                        l = E.function(r);
                    N.call(o, e, t, function(e) {
                        return a.proxy(e, n, i)
                    }, s && !l)
                }
            }, {
                key: "controls",
                value: function() {
                    var e = this,
                        t = this.player,
                        n = t.elements,
                        i = Z.isIE ? "change" : "input";
                    if (n.buttons.play && Array.from(n.buttons.play).forEach(function(n) {
                            e.bind(n, "click", t.togglePlay, "play")
                        }), this.bind(n.buttons.restart, "click", t.restart, "restart"), this.bind(n.buttons.rewind, "click", t.rewind, "rewind"), this.bind(n.buttons.fastForward, "click", t.forward, "fastForward"), this.bind(n.buttons.mute, "click", function() {
                            t.muted = !t.muted
                        }, "mute"), this.bind(n.buttons.captions, "click", function() {
                            return t.toggleCaptions()
                        }), this.bind(n.buttons.download, "click", function() {
                            L.call(t, t.media, "download")
                        }, "download"), this.bind(n.buttons.fullscreen, "click", function() {
                            t.fullscreen.toggle()
                        }, "fullscreen"), this.bind(n.buttons.pip, "click", function() {
                            t.pip = "toggle"
                        }, "pip"), this.bind(n.buttons.airplay, "click", t.airplay, "airplay"), this.bind(n.buttons.settings, "click", function(e) {
                            e.stopPropagation(), Ce.toggleMenu.call(t, e)
                        }), this.bind(n.buttons.settings, "keyup", function(e) {
                            var n = e.which;
                            [13, 32].includes(n) && (13 !== n ? (e.preventDefault(), e.stopPropagation(), Ce.toggleMenu.call(t, e)) : Ce.focusFirstMenuItem.call(t, null, !0))
                        }, null, !1), this.bind(n.settings.menu, "keydown", function(e) {
                            27 === e.which && Ce.toggleMenu.call(t, e)
                        }), this.bind(n.inputs.seek, "mousedown mousemove", function(e) {
                            var t = n.progress.getBoundingClientRect(),
                                i = 100 / t.width * (e.pageX - t.left);
                            e.currentTarget.setAttribute("seek-value", i)
                        }), this.bind(n.inputs.seek, "mousedown mouseup keydown keyup touchstart touchend", function(e) {
                            var n = e.currentTarget,
                                i = e.keyCode ? e.keyCode : e.which;
                            if (!E.keyboardEvent(e) || 39 === i || 37 === i) {
                                t.lastSeekTime = Date.now();
                                var a = n.hasAttribute("play-on-seeked"),
                                    s = ["mouseup", "touchend", "keyup"].includes(e.type);
                                a && s ? (n.removeAttribute("play-on-seeked"), t.play()) : !s && t.playing && (n.setAttribute("play-on-seeked", ""), t.pause())
                            }
                        }), Z.isIos) {
                        var s = W.call(t, 'input[type="range"]');
                        Array.from(s).forEach(function(t) {
                            return e.bind(t, i, function(e) {
                                return $(e.target)
                            })
                        })
                    }
                    this.bind(n.inputs.seek, i, function(e) {
                        var n = e.currentTarget,
                            i = n.getAttribute("seek-value");
                        E.empty(i) && (i = n.value), n.removeAttribute("seek-value"), t.currentTime = i / n.max * t.duration
                    }, "seek"), this.bind(n.progress, "mouseenter mouseleave mousemove", function(e) {
                        return Ce.updateSeekTooltip.call(t, e)
                    }), this.bind(n.progress, "mousemove touchmove", function(e) {
                        var n = t.previewThumbnails;
                        n && n.loaded && n.startMove(e)
                    }), this.bind(n.progress, "mouseleave click", function() {
                        var e = t.previewThumbnails;
                        e && e.loaded && e.endMove(!1, !0)
                    }), this.bind(n.progress, "mousedown touchstart", function(e) {
                        var n = t.previewThumbnails;
                        n && n.loaded && n.startScrubbing(e)
                    }), this.bind(n.progress, "mouseup touchend", function(e) {
                        var n = t.previewThumbnails;
                        n && n.loaded && n.endScrubbing(e)
                    }), Z.isWebkit && Array.from(W.call(t, 'input[type="range"]')).forEach(function(n) {
                        e.bind(n, "input", function(e) {
                            return Ce.updateRangeFill.call(t, e.target)
                        })
                    }), t.config.toggleInvert && !E.element(n.display.duration) && this.bind(n.display.currentTime, "click", function() {
                        0 !== t.currentTime && (t.config.invertTime = !t.config.invertTime, Ce.timeUpdate.call(t))
                    }), this.bind(n.inputs.volume, i, function(e) {
                        t.volume = e.target.value
                    }, "volume"), this.bind(n.controls, "mouseenter mouseleave", function(e) {
                        n.controls.hover = !t.touch && "mouseenter" === e.type
                    }), this.bind(n.controls, "mousedown mouseup touchstart touchend touchcancel", function(e) {
                        n.controls.pressed = ["mousedown", "touchstart"].includes(e.type)
                    }), this.bind(n.controls, "focusin", function() {
                        var n = t.config,
                            i = t.elements,
                            a = t.timers;
                        B(i.controls, n.classNames.noTransition, !0), Fe.toggleControls.call(t, !0), setTimeout(function() {
                            B(i.controls, n.classNames.noTransition, !1)
                        }, 0);
                        var s = e.touch ? 3e3 : 4e3;
                        clearTimeout(a.controls), a.controls = setTimeout(function() {
                            return Fe.toggleControls.call(t, !1)
                        }, s)
                    }), this.bind(n.inputs.volume, "wheel", function(e) {
                        var n = e.webkitDirectionInvertedFromDevice,
                            i = a([e.deltaX, -e.deltaY].map(function(e) {
                                return n ? -e : e
                            }), 2),
                            s = i[0],
                            o = i[1],
                            r = Math.sign(Math.abs(s) > Math.abs(o) ? s : o);
                        t.increaseVolume(r / 50);
                        var l = t.media.volume;
                        (1 === r && l < 1 || -1 === r && l > 0) && e.preventDefault()
                    }, "volume", !1)
                }
            }]), t
        }();
    "undefined" != typeof globalThis ? globalThis : "undefined" != typeof window ? window : "undefined" != typeof global ? global : "undefined" != typeof self && self;
    var Re, Be = (function(e, t) {
        e.exports = function() {
            var e = function() {},
                t = {},
                n = {},
                i = {};

            function a(e, t) {
                if (e) {
                    var a = i[e];
                    if (n[e] = t, a)
                        for (; a.length;) a[0](e, t), a.splice(0, 1)
                }
            }

            function s(t, n) {
                t.call && (t = {
                    success: t
                }), n.length ? (t.error || e)(n) : (t.success || e)(t)
            }

            function o(t, n, i, a) {
                var s, r, l = document,
                    c = i.async,
                    u = (i.numRetries || 0) + 1,
                    d = i.before || e,
                    h = t.replace(/^(css|img)!/, "");
                a = a || 0, /(^css!|\.css$)/.test(t) ? ((r = l.createElement("link")).rel = "stylesheet", r.href = h, (s = "hideFocus" in r) && r.relList && (s = 0, r.rel = "preload", r.as = "style")) : /(^img!|\.(png|gif|jpg|svg)$)/.test(t) ? (r = l.createElement("img")).src = h : ((r = l.createElement("script")).src = t, r.async = void 0 === c || c), r.onload = r.onerror = r.onbeforeload = function(e) {
                    var l = e.type[0];
                    if (s) try {
                        r.sheet.cssText.length || (l = "e")
                    } catch (e) {
                        18 != e.code && (l = "e")
                    }
                    if ("e" == l) {
                        if ((a += 1) < u) return o(t, n, i, a)
                    } else if ("preload" == r.rel && "style" == r.as) return r.rel = "stylesheet";
                    n(t, l, e.defaultPrevented)
                }, !1 !== d(t, r) && l.head.appendChild(r)
            }

            function r(e, n, i) {
                var r, l;
                if (n && n.trim && (r = n), l = (r ? i : n) || {}, r) {
                    if (r in t) throw "LoadJS";
                    t[r] = !0
                }

                function c(t, n) {
                    ! function(e, t, n) {
                        var i, a, s = (e = e.push ? e : [e]).length,
                            r = s,
                            l = [];
                        for (i = function(e, n, i) {
                                if ("e" == n && l.push(e), "b" == n) {
                                    if (!i) return;
                                    l.push(e)
                                }--s || t(l)
                            }, a = 0; a < r; a++) o(e[a], i, n)
                    }(e, function(e) {
                        s(l, e), t && s({
                            success: t,
                            error: n
                        }, e), a(r, e)
                    }, l)
                }
                if (l.returnPromise) return new Promise(c);
                c()
            }
            return r.ready = function(e, t) {
                return function(e, t) {
                    e = e.push ? e : [e];
                    var a, s, o, r = [],
                        l = e.length,
                        c = l;
                    for (a = function(e, n) {
                            n.length && r.push(e), --c || t(r)
                        }; l--;) s = e[l], (o = n[s]) ? a(s, o) : (i[s] = i[s] || []).push(a)
                }(e, function(e) {
                    s(t, e)
                }), r
            }, r.done = function(e) {
                a(e, [])
            }, r.reset = function() {
                t = {}, n = {}, i = {}
            }, r.isDefined = function(e) {
                return e in t
            }, r
        }()
    }(Re = {
        exports: {}
    }, Re.exports), Re.exports);

    function Ve(e) {
        return new Promise(function(t, n) {
            Be(e, {
                success: t,
                error: n
            })
        })
    }

    function Ue(e) {
        e && !this.embed.hasPlayed && (this.embed.hasPlayed = !0), this.media.paused === e && (this.media.paused = !e, L.call(this, this.media, e ? "play" : "pause"))
    }
    var We = {
        setup: function() {
            var e = this;
            B(this.elements.wrapper, this.config.classNames.embed, !0), ae.call(this), E.object(window.Vimeo) ? We.ready.call(this) : Ve(this.config.urls.vimeo.sdk).then(function() {
                We.ready.call(e)
            }).catch(function(t) {
                e.debug.warn("Vimeo API failed to load", t)
            })
        },
        ready: function() {
            var e = this,
                t = this,
                n = t.config.vimeo,
                i = Ee(le({}, {
                    loop: t.config.loop.active,
                    autoplay: t.autoplay,
                    muted: t.muted,
                    gesture: "media",
                    playsinline: !this.config.fullscreen.iosNative
                }, n)),
                s = t.media.getAttribute("src");
            E.empty(s) && (s = t.media.getAttribute(t.config.attributes.embed.id));
            var o, r = (o = s, E.empty(o) ? null : E.number(Number(o)) ? o : o.match(/^.*(vimeo.com\/|video\/)(\d+).*/) ? RegExp.$2 : o),
                l = O("iframe"),
                c = ce(t.config.urls.vimeo.iframe, r, i);
            l.setAttribute("src", c), l.setAttribute("allowfullscreen", ""), l.setAttribute("allowtransparency", ""), l.setAttribute("allow", "autoplay");
            var u = O("div", {
                poster: t.poster,
                class: t.config.classNames.embedContainer
            });
            u.appendChild(l), t.media = F(u, t.media), ye(ce(t.config.urls.vimeo.api, r), "json").then(function(e) {
                if (!E.empty(e)) {
                    var n = new URL(e[0].thumbnail_large);
                    n.pathname = "".concat(n.pathname.split("_")[0], ".jpg"), Fe.setPoster.call(t, n.href).catch(function() {})
                }
            }), t.embed = new window.Vimeo.Player(l, {
                autopause: t.config.autopause,
                muted: t.muted
            }), t.media.paused = !0, t.media.currentTime = 0, t.supported.ui && t.embed.disableTextTrack(), t.media.play = function() {
                return Ue.call(t, !0), t.embed.play()
            }, t.media.pause = function() {
                return Ue.call(t, !1), t.embed.pause()
            }, t.media.stop = function() {
                t.pause(), t.currentTime = 0
            };
            var d = t.media.currentTime;
            Object.defineProperty(t.media, "currentTime", {
                get: function() {
                    return d
                },
                set: function(e) {
                    var n = t.embed,
                        i = t.media,
                        a = t.paused,
                        s = t.volume,
                        o = a && !n.hasPlayed;
                    i.seeking = !0, L.call(t, i, "seeking"), Promise.resolve(o && n.setVolume(0)).then(function() {
                        return n.setCurrentTime(e)
                    }).then(function() {
                        return o && n.pause()
                    }).then(function() {
                        return o && n.setVolume(s)
                    }).catch(function() {})
                }
            });
            var h = t.config.speed.selected;
            Object.defineProperty(t.media, "playbackRate", {
                get: function() {
                    return h
                },
                set: function(e) {
                    t.embed.setPlaybackRate(e).then(function() {
                        h = e, L.call(t, t.media, "ratechange")
                    }).catch(function(e) {
                        "Error" === e.name && Ce.setSpeedMenu.call(t, [])
                    })
                }
            });
            var m = t.config.volume;
            Object.defineProperty(t.media, "volume", {
                get: function() {
                    return m
                },
                set: function(e) {
                    t.embed.setVolume(e).then(function() {
                        m = e, L.call(t, t.media, "volumechange")
                    })
                }
            });
            var p = t.config.muted;
            Object.defineProperty(t.media, "muted", {
                get: function() {
                    return p
                },
                set: function(e) {
                    var n = !!E.boolean(e) && e;
                    t.embed.setVolume(n ? 0 : t.config.volume).then(function() {
                        p = n, L.call(t, t.media, "volumechange")
                    })
                }
            });
            var f, g = t.config.loop;
            Object.defineProperty(t.media, "loop", {
                get: function() {
                    return g
                },
                set: function(e) {
                    var n = E.boolean(e) ? e : t.config.loop.active;
                    t.embed.setLoop(n).then(function() {
                        g = n
                    })
                }
            }), t.embed.getVideoUrl().then(function(e) {
                f = e, Ce.setDownloadLink.call(t)
            }).catch(function(t) {
                e.debug.warn(t)
            }), Object.defineProperty(t.media, "currentSrc", {
                get: function() {
                    return f
                }
            }), Object.defineProperty(t.media, "ended", {
                get: function() {
                    return t.currentTime === t.duration
                }
            }), Promise.all([t.embed.getVideoWidth(), t.embed.getVideoHeight()]).then(function(n) {
                var i = a(n, 2),
                    s = i[0],
                    o = i[1];
                t.embed.ratio = "".concat(s, ":").concat(o), ae.call(e)
            }), t.embed.setAutopause(t.config.autopause).then(function(e) {
                t.config.autopause = e
            }), t.embed.getVideoTitle().then(function(n) {
                t.config.title = n, Fe.setTitle.call(e)
            }), t.embed.getCurrentTime().then(function(e) {
                d = e, L.call(t, t.media, "timeupdate")
            }), t.embed.getDuration().then(function(e) {
                t.media.duration = e, L.call(t, t.media, "durationchange")
            }), t.embed.getTextTracks().then(function(e) {
                t.media.textTracks = e, Se.setup.call(t)
            }), t.embed.on("cuechange", function(e) {
                var n = e.cues,
                    i = (void 0 === n ? [] : n).map(function(e) {
                        return t = e.text, n = document.createDocumentFragment(), i = document.createElement("div"), n.appendChild(i), i.innerHTML = t, n.firstChild.innerText;
                        var t, n, i
                    });
                Se.updateCues.call(t, i)
            }), t.embed.on("loaded", function() {
                (t.embed.getPaused().then(function(e) {
                    Ue.call(t, !e), e || L.call(t, t.media, "playing")
                }), E.element(t.embed.element) && t.supported.ui) && t.embed.element.setAttribute("tabindex", -1)
            }), t.embed.on("play", function() {
                Ue.call(t, !0), L.call(t, t.media, "playing")
            }), t.embed.on("pause", function() {
                Ue.call(t, !1)
            }), t.embed.on("timeupdate", function(e) {
                t.media.seeking = !1, d = e.seconds, L.call(t, t.media, "timeupdate")
            }), t.embed.on("progress", function(e) {
                t.media.buffered = e.percent, L.call(t, t.media, "progress"), 1 === parseInt(e.percent, 10) && L.call(t, t.media, "canplaythrough"), t.embed.getDuration().then(function(e) {
                    e !== t.media.duration && (t.media.duration = e, L.call(t, t.media, "durationchange"))
                })
            }), t.embed.on("seeked", function() {
                t.media.seeking = !1, L.call(t, t.media, "seeked")
            }), t.embed.on("ended", function() {
                t.media.paused = !0, L.call(t, t.media, "ended")
            }), t.embed.on("error", function(e) {
                t.media.error = e, L.call(t, t.media, "error")
            }), setTimeout(function() {
                return Fe.build.call(t)
            }, 0)
        }
    };

    function ze(e) {
        e && !this.embed.hasPlayed && (this.embed.hasPlayed = !0), this.media.paused === e && (this.media.paused = !e, L.call(this, this.media, e ? "play" : "pause"))
    }

    function Ke(e) {
        return e.noCookie ? "https://www.youtube-nocookie.com" : "http:" === window.location.protocol ? "http://www.youtube.com" : void 0
    }
    var Ye, Qe = {
            setup: function() {
                var e = this;
                B(this.elements.wrapper, this.config.classNames.embed, !0), ae.call(this), E.object(window.YT) && E.function(window.YT.Player) ? Qe.ready.call(this) : (Ve(this.config.urls.youtube.sdk).catch(function(t) {
                    e.debug.warn("YouTube API failed to load", t)
                }), window.onYouTubeReadyCallbacks = window.onYouTubeReadyCallbacks || [], window.onYouTubeReadyCallbacks.push(function() {
                    Qe.ready.call(e)
                }), window.onYouTubeIframeAPIReady = function() {
                    window.onYouTubeReadyCallbacks.forEach(function(e) {
                        e()
                    })
                })
            },
            getTitle: function(e) {
                var t = this;
                if (E.function(this.embed.getVideoData)) {
                    var n = this.embed.getVideoData().title;
                    if (E.empty(n)) return this.config.title = n, void Fe.setTitle.call(this)
                }
                var i = this.config.keys.google;
                E.string(i) && !E.empty(i) && ye(ce(this.config.urls.youtube.api, e, i)).then(function(e) {
                    E.object(e) && (t.config.title = e.items[0].snippet.title, Fe.setTitle.call(t))
                }).catch(function() {})
            },
            ready: function() {
                var e = this,
                    t = e.media.getAttribute("id");
                if (E.empty(t) || !t.startsWith("youtube-")) {
                    var n = e.media.getAttribute("src");
                    E.empty(n) && (n = e.media.getAttribute(this.config.attributes.embed.id));
                    var i, a, s = (i = n, E.empty(i) ? null : i.match(/^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|&v=)([^#&?]*).*/) ? RegExp.$2 : i),
                        o = (a = e.provider, "".concat(a, "-").concat(Math.floor(1e4 * Math.random()))),
                        r = O("div", {
                            id: o,
                            poster: e.poster
                        });
                    e.media = F(r, e.media);
                    var l = function(e) {
                        return "https://i.ytimg.com/vi/".concat(s, "/").concat(e, "default.jpg")
                    };
                    De(l("maxres"), 121).catch(function() {
                        return De(l("sd"), 121)
                    }).catch(function() {
                        return De(l("hq"))
                    }).then(function(t) {
                        return Fe.setPoster.call(e, t.src)
                    }).then(function(t) {
                        t.includes("maxres") || (e.elements.poster.style.backgroundSize = "cover")
                    }).catch(function() {});
                    var c = e.config.youtube;
                    e.embed = new window.YT.Player(o, {
                        videoId: s,
                        host: Ke(c),
                        playerVars: le({}, {
                            autoplay: e.config.autoplay ? 1 : 0,
                            hl: e.config.hl,
                            controls: e.supported.ui ? 0 : 1,
                            disablekb: 1,
                            playsinline: e.config.fullscreen.iosNative ? 0 : 1,
                            cc_load_policy: e.captions.active ? 1 : 0,
                            cc_lang_pref: e.config.captions.language,
                            widget_referrer: window ? window.location.href : null
                        }, c),
                        events: {
                            onError: function(t) {
                                if (!e.media.error) {
                                    var n = t.data,
                                        i = {
                                            2: "The request contains an invalid parameter value. For example, this error occurs if you specify a video ID that does not have 11 characters, or if the video ID contains invalid characters, such as exclamation points or asterisks.",
                                            5: "The requested content cannot be played in an HTML5 player or another error related to the HTML5 player has occurred.",
                                            100: "The video requested was not found. This error occurs when a video has been removed (for any reason) or has been marked as private.",
                                            101: "The owner of the requested video does not allow it to be played in embedded players.",
                                            150: "The owner of the requested video does not allow it to be played in embedded players."
                                        }[n] || "An unknown error occured";
                                    e.media.error = {
                                        code: n,
                                        message: i
                                    }, L.call(e, e.media, "error")
                                }
                            },
                            onPlaybackRateChange: function(t) {
                                var n = t.target;
                                e.media.playbackRate = n.getPlaybackRate(), L.call(e, e.media, "ratechange")
                            },
                            onReady: function(t) {
                                if (!E.function(e.media.play)) {
                                    var n = t.target;
                                    Qe.getTitle.call(e, s), e.media.play = function() {
                                        ze.call(e, !0), n.playVideo()
                                    }, e.media.pause = function() {
                                        ze.call(e, !1), n.pauseVideo()
                                    }, e.media.stop = function() {
                                        n.stopVideo()
                                    }, e.media.duration = n.getDuration(), e.media.paused = !0, e.media.currentTime = 0, Object.defineProperty(e.media, "currentTime", {
                                        get: function() {
                                            return Number(n.getCurrentTime())
                                        },
                                        set: function(t) {
                                            e.paused && !e.embed.hasPlayed && e.embed.mute(), e.media.seeking = !0, L.call(e, e.media, "seeking"), n.seekTo(t)
                                        }
                                    }), Object.defineProperty(e.media, "playbackRate", {
                                        get: function() {
                                            return n.getPlaybackRate()
                                        },
                                        set: function(e) {
                                            n.setPlaybackRate(e)
                                        }
                                    });
                                    var i = e.config.volume;
                                    Object.defineProperty(e.media, "volume", {
                                        get: function() {
                                            return i
                                        },
                                        set: function(t) {
                                            i = t, n.setVolume(100 * i), L.call(e, e.media, "volumechange")
                                        }
                                    });
                                    var a = e.config.muted;
                                    Object.defineProperty(e.media, "muted", {
                                        get: function() {
                                            return a
                                        },
                                        set: function(t) {
                                            var i = E.boolean(t) ? t : a;
                                            a = i, n[i ? "mute" : "unMute"](), L.call(e, e.media, "volumechange")
                                        }
                                    }), Object.defineProperty(e.media, "currentSrc", {
                                        get: function() {
                                            return n.getVideoUrl()
                                        }
                                    }), Object.defineProperty(e.media, "ended", {
                                        get: function() {
                                            return e.currentTime === e.duration
                                        }
                                    }), e.options.speed = n.getAvailablePlaybackRates(), e.supported.ui && e.media.setAttribute("tabindex", -1), L.call(e, e.media, "timeupdate"), L.call(e, e.media, "durationchange"), clearInterval(e.timers.buffering), e.timers.buffering = setInterval(function() {
                                        e.media.buffered = n.getVideoLoadedFraction(), (null === e.media.lastBuffered || e.media.lastBuffered < e.media.buffered) && L.call(e, e.media, "progress"), e.media.lastBuffered = e.media.buffered, 1 === e.media.buffered && (clearInterval(e.timers.buffering), L.call(e, e.media, "canplaythrough"))
                                    }, 200), setTimeout(function() {
                                        return Fe.build.call(e)
                                    }, 50)
                                }
                            },
                            onStateChange: function(t) {
                                var n = t.target;
                                switch (clearInterval(e.timers.playing), e.media.seeking && [1, 2].includes(t.data) && (e.media.seeking = !1, L.call(e, e.media, "seeked")), t.data) {
                                    case -1:
                                        L.call(e, e.media, "timeupdate"), e.media.buffered = n.getVideoLoadedFraction(), L.call(e, e.media, "progress");
                                        break;
                                    case 0:
                                        ze.call(e, !1), e.media.loop ? (n.stopVideo(), n.playVideo()) : L.call(e, e.media, "ended");
                                        break;
                                    case 1:
                                        e.config.autoplay || !e.media.paused || e.embed.hasPlayed ? (ze.call(e, !0), L.call(e, e.media, "playing"), e.timers.playing = setInterval(function() {
                                            L.call(e, e.media, "timeupdate")
                                        }, 50), e.media.duration !== n.getDuration() && (e.media.duration = n.getDuration(), L.call(e, e.media, "durationchange"))) : e.media.pause();
                                        break;
                                    case 2:
                                        e.muted || e.embed.unMute(), ze.call(e, !1)
                                }
                                L.call(e, e.elements.container, "statechange", !1, {
                                    code: t.data
                                })
                            }
                        }
                    })
                }
            }
        },
        Xe = {
            setup: function() {
                this.media ? (B(this.elements.container, this.config.classNames.type.replace("{0}", this.type), !0), B(this.elements.container, this.config.classNames.provider.replace("{0}", this.provider), !0), this.isEmbed && B(this.elements.container, this.config.classNames.type.replace("{0}", "video"), !0), this.isVideo && (this.elements.wrapper = O("div", {
                    class: this.config.classNames.video
                }), I(this.media, this.elements.wrapper), this.elements.poster = O("div", {
                    class: this.config.classNames.poster
                }), this.elements.wrapper.appendChild(this.elements.poster)), this.isHTML5 ? se.extend.call(this) : this.isYouTube ? Qe.setup.call(this) : this.isVimeo && We.setup.call(this)) : this.debug.warn("No media element found!")
            }
        },
        Je = function() {
            function t(n) {
                var i = this;
                e(this, t), this.player = n, this.config = n.config.ads, this.playing = !1, this.initialized = !1, this.elements = {
                    container: null,
                    displayContainer: null
                }, this.manager = null, this.loader = null, this.cuePoints = null, this.events = {}, this.safetyTimer = null, this.countdownTimer = null, this.managerPromise = new Promise(function(e, t) {
                    i.on("loaded", e), i.on("error", t)
                }), this.load()
            }
            return n(t, [{
                key: "load",
                value: function() {
                    var e = this;
                    this.enabled && (E.object(window.google) && E.object(window.google.ima) ? this.ready() : Ve(this.player.config.urls.googleIMA.sdk).then(function() {
                        e.ready()
                    }).catch(function() {
                        e.trigger("error", new Error("Google IMA SDK failed to load"))
                    }))
                }
            }, {
                key: "ready",
                value: function() {
                    var e = this;
                    this.startSafetyTimer(12e3, "ready()"), this.managerPromise.then(function() {
                        e.clearSafetyTimer("onAdsManagerLoaded()")
                    }), this.listeners(), this.setupIMA()
                }
            }, {
                key: "setupIMA",
                value: function() {
                    this.elements.container = O("div", {
                        class: this.player.config.classNames.ads
                    }), this.player.elements.container.appendChild(this.elements.container), google.ima.settings.setVpaidMode(google.ima.ImaSdkSettings.VpaidMode.ENABLED), google.ima.settings.setLocale(this.player.config.ads.language), google.ima.settings.setDisableCustomPlaybackForIOS10Plus(this.player.config.playsinline), this.elements.displayContainer = new google.ima.AdDisplayContainer(this.elements.container, this.player.media), this.requestAds()
                }
            }, {
                key: "requestAds",
                value: function() {
                    var e = this,
                        t = this.player.elements.container;
                    try {
                        this.loader = new google.ima.AdsLoader(this.elements.displayContainer), this.loader.addEventListener(google.ima.AdsManagerLoadedEvent.Type.ADS_MANAGER_LOADED, function(t) {
                            return e.onAdsManagerLoaded(t)
                        }, !1), this.loader.addEventListener(google.ima.AdErrorEvent.Type.AD_ERROR, function(t) {
                            return e.onAdError(t)
                        }, !1);
                        var n = new google.ima.AdsRequest;
                        n.adTagUrl = this.tagUrl, n.linearAdSlotWidth = t.offsetWidth, n.linearAdSlotHeight = t.offsetHeight, n.nonLinearAdSlotWidth = t.offsetWidth, n.nonLinearAdSlotHeight = t.offsetHeight, n.forceNonLinearFullSlot = !1, n.setAdWillPlayMuted(!this.player.muted), this.loader.requestAds(n)
                    } catch (e) {
                        this.onAdError(e)
                    }
                }
            }, {
                key: "pollCountdown",
                value: function() {
                    var e = this;
                    if (!(arguments.length > 0 && void 0 !== arguments[0] && arguments[0])) return clearInterval(this.countdownTimer), void this.elements.container.removeAttribute("data-badge-text");
                    this.countdownTimer = setInterval(function() {
                        var t = Te(Math.max(e.manager.getRemainingTime(), 0)),
                            n = "".concat(fe("advertisement", e.player.config), " - ").concat(t);
                        e.elements.container.setAttribute("data-badge-text", n)
                    }, 100)
                }
            }, {
                key: "onAdsManagerLoaded",
                value: function(e) {
                    var t = this;
                    if (this.enabled) {
                        var n = new google.ima.AdsRenderingSettings;
                        n.restoreCustomPlaybackStateOnAdBreakComplete = !0, n.enablePreloading = !0, this.manager = e.getAdsManager(this.player, n), this.cuePoints = this.manager.getCuePoints(), this.manager.setVolume(this.player.volume), this.manager.addEventListener(google.ima.AdErrorEvent.Type.AD_ERROR, function(e) {
                            return t.onAdError(e)
                        }), Object.keys(google.ima.AdEvent.Type).forEach(function(e) {
                            t.manager.addEventListener(google.ima.AdEvent.Type[e], function(e) {
                                return t.onAdEvent(e)
                            })
                        }), this.trigger("loaded")
                    }
                }
            }, {
                key: "addCuePoints",
                value: function() {
                    var e = this;
                    E.empty(this.cuePoints) || this.cuePoints.forEach(function(t) {
                        if (0 !== t && -1 !== t && t < e.player.duration) {
                            var n = e.player.elements.progress;
                            if (E.element(n)) {
                                var i = 100 / e.player.duration * t,
                                    a = O("span", {
                                        class: e.player.config.classNames.cues
                                    });
                                a.style.left = "".concat(i.toString(), "%"), n.appendChild(a)
                            }
                        }
                    })
                }
            }, {
                key: "onAdEvent",
                value: function(e) {
                    var t = this,
                        n = this.player.elements.container,
                        i = e.getAd(),
                        a = e.getAdData(),
                        s = function(e) {
                            var n = "ads".concat(e.replace(/_/g, "").toLowerCase());
                            L.call(t.player, t.player.media, n)
                        };
                    switch (e.type) {
                        case google.ima.AdEvent.Type.LOADED:
                            this.trigger("loaded"), s(e.type), this.pollCountdown(!0), i.isLinear() || (i.width = n.offsetWidth, i.height = n.offsetHeight);
                            break;
                        case google.ima.AdEvent.Type.ALL_ADS_COMPLETED:
                            s(e.type), this.loadAds();
                            break;
                        case google.ima.AdEvent.Type.CONTENT_PAUSE_REQUESTED:
                            s(e.type), this.pauseContent();
                            break;
                        case google.ima.AdEvent.Type.CONTENT_RESUME_REQUESTED:
                            s(e.type), this.pollCountdown(), this.resumeContent();
                            break;
                        case google.ima.AdEvent.Type.STARTED:
                        case google.ima.AdEvent.Type.MIDPOINT:
                        case google.ima.AdEvent.Type.COMPLETE:
                        case google.ima.AdEvent.Type.IMPRESSION:
                        case google.ima.AdEvent.Type.CLICK:
                            s(e.type);
                            break;
                        case google.ima.AdEvent.Type.LOG:
                            a.adError && this.player.debug.warn("Non-fatal ad error: ".concat(a.adError.getMessage()))
                    }
                }
            }, {
                key: "onAdError",
                value: function(e) {
                    this.cancel(), this.player.debug.warn("Ads error", e)
                }
            }, {
                key: "listeners",
                value: function() {
                    var e, t = this,
                        n = this.player.elements.container;
                    this.player.on("canplay", function() {
                        t.addCuePoints()
                    }), this.player.on("ended", function() {
                        t.loader.contentComplete()
                    }), this.player.on("timeupdate", function() {
                        e = t.player.currentTime
                    }), this.player.on("seeked", function() {
                        var n = t.player.currentTime;
                        E.empty(t.cuePoints) || t.cuePoints.forEach(function(i, a) {
                            e < i && i < n && (t.manager.discardAdBreak(), t.cuePoints.splice(a, 1))
                        })
                    }), window.addEventListener("resize", function() {
                        t.manager && t.manager.resize(n.offsetWidth, n.offsetHeight, google.ima.ViewMode.NORMAL)
                    })
                }
            }, {
                key: "play",
                value: function() {
                    var e = this,
                        t = this.player.elements.container;
                    this.managerPromise || this.resumeContent(), this.managerPromise.then(function() {
                        e.elements.displayContainer.initialize();
                        try {
                            e.initialized || (e.manager.init(t.offsetWidth, t.offsetHeight, google.ima.ViewMode.NORMAL), e.manager.start()), e.initialized = !0
                        } catch (t) {
                            e.onAdError(t)
                        }
                    }).catch(function() {})
                }
            }, {
                key: "resumeContent",
                value: function() {
                    this.elements.container.style.zIndex = "", this.playing = !1, this.player.media.play()
                }
            }, {
                key: "pauseContent",
                value: function() {
                    this.elements.container.style.zIndex = 3, this.playing = !0, this.player.media.pause()
                }
            }, {
                key: "cancel",
                value: function() {
                    this.initialized && this.resumeContent(), this.trigger("error"), this.loadAds()
                }
            }, {
                key: "loadAds",
                value: function() {
                    var e = this;
                    this.managerPromise.then(function() {
                        e.manager && e.manager.destroy(), e.managerPromise = new Promise(function(t) {
                            e.on("loaded", t), e.player.debug.log(e.manager)
                        }), e.requestAds()
                    }).catch(function() {})
                }
            }, {
                key: "trigger",
                value: function(e) {
                    for (var t = this, n = arguments.length, i = new Array(n > 1 ? n - 1 : 0), a = 1; a < n; a++) i[a - 1] = arguments[a];
                    var s = this.events[e];
                    E.array(s) && s.forEach(function(e) {
                        E.function(e) && e.apply(t, i)
                    })
                }
            }, {
                key: "on",
                value: function(e, t) {
                    return E.array(this.events[e]) || (this.events[e] = []), this.events[e].push(t), this
                }
            }, {
                key: "startSafetyTimer",
                value: function(e, t) {
                    var n = this;
                    this.player.debug.log("Safety timer invoked from: ".concat(t)), this.safetyTimer = setTimeout(function() {
                        n.cancel(), n.clearSafetyTimer("startSafetyTimer()")
                    }, e)
                }
            }, {
                key: "clearSafetyTimer",
                value: function(e) {
                    E.nullOrUndefined(this.safetyTimer) || (this.player.debug.log("Safety timer cleared from: ".concat(e)), clearTimeout(this.safetyTimer), this.safetyTimer = null)
                }
            }, {
                key: "enabled",
                get: function() {
                    var e = this.config;
                    return this.player.isHTML5 && this.player.isVideo && e.enabled && (!E.empty(e.publisherId) || E.url(e.tagUrl))
                }
            }, {
                key: "tagUrl",
                get: function() {
                    var e = this.config;
                    if (E.url(e.tagUrl)) return e.tagUrl;
                    var t = {
                        AV_PUBLISHERID: "58c25bb0073ef448b1087ad6",
                        AV_CHANNELID: "5a0458dc28a06145e4519d21",
                        AV_URL: window.location.hostname,
                        cb: Date.now(),
                        AV_WIDTH: 640,
                        AV_HEIGHT: 480,
                        AV_CDIM2: this.publisherId
                    };
                    return "".concat("https://go.aniview.com/api/adserver6/vast/", "?").concat(Ee(t))
                }
            }]), t
        }(),
        $e = function() {
            function t(n) {
                e(this, t), this.player = n, this.thumbnails = [], this.loaded = !1, this.lastMouseMoveTime = Date.now(), this.mouseDown = !1, this.loadedImages = [], this.elements = {
                    thumb: {},
                    scrubbing: {}
                }, this.load()
            }
            return n(t, [{
                key: "load",
                value: function() {
                    var e = this;
                    this.player.elements.display.seekTooltip && (this.player.elements.display.seekTooltip.hidden = this.enabled), this.enabled && this.getThumbnails().then(function() {
                        e.render(), e.determineContainerAutoSizing(), e.loaded = !0
                    })
                }
            }, {
                key: "getThumbnails",
                value: function() {
                    var e = this;
                    return new Promise(function(t) {
                        var n = e.player.config.previewThumbnails.src;
                        if (E.empty(n)) throw new Error("Missing previewThumbnails.src config attribute");
                        var i = (E.string(n) ? [n] : n).map(function(t) {
                            return e.getThumbnail(t)
                        });
                        Promise.all(i).then(function() {
                            e.thumbnails.sort(function(e, t) {
                                return e.height - t.height
                            }), e.player.debug.log("Preview thumbnails", e.thumbnails), t()
                        })
                    })
                }
            }, {
                key: "getThumbnail",
                value: function(e) {
                    var t = this;
                    return new Promise(function(n) {
                        ye(e).then(function(i) {
                            var s, o, r = {
                                frames: (s = i, o = [], s.split(/\r\n\r\n|\n\n|\r\r/).forEach(function(e) {
                                    var t = {};
                                    e.split(/\r\n|\n|\r/).forEach(function(e) {
                                        if (E.number(t.startTime)) {
                                            if (!E.empty(e.trim()) && E.empty(t.text)) {
                                                var n = e.trim().split("#xywh="),
                                                    i = a(n, 1);
                                                if (t.text = i[0], n[1]) {
                                                    var s = a(n[1].split(","), 4);
                                                    t.x = s[0], t.y = s[1], t.w = s[2], t.h = s[3]
                                                }
                                            }
                                        } else {
                                            var o = e.match(/([0-9]{2})?:?([0-9]{2}):([0-9]{2}).([0-9]{2,3})( ?--> ?)([0-9]{2})?:?([0-9]{2}):([0-9]{2}).([0-9]{2,3})/);
                                            o && (t.startTime = 60 * Number(o[1] || 0) * 60 + 60 * Number(o[2]) + Number(o[3]) + Number("0.".concat(o[4])), t.endTime = 60 * Number(o[6] || 0) * 60 + 60 * Number(o[7]) + Number(o[8]) + Number("0.".concat(o[9])))
                                        }
                                    }), t.text && o.push(t)
                                }), o),
                                height: null,
                                urlPrefix: ""
                            };
                            r.frames[0].text.startsWith("/") || r.frames[0].text.startsWith("http://") || r.frames[0].text.startsWith("https://") || (r.urlPrefix = e.substring(0, e.lastIndexOf("/") + 1));
                            var l = new Image;
                            l.onload = function() {
                                r.height = l.naturalHeight, r.width = l.naturalWidth, t.thumbnails.push(r), n()
                            }, l.src = r.urlPrefix + r.frames[0].text
                        })
                    })
                }
            }, {
                key: "startMove",
                value: function(e) {
                    if (this.loaded && E.event(e) && ["touchmove", "mousemove"].includes(e.type) && this.player.media.duration) {
                        if ("touchmove" === e.type) this.seekTime = this.player.media.duration * (this.player.elements.inputs.seek.value / 100);
                        else {
                            var t = this.player.elements.progress.getBoundingClientRect(),
                                n = 100 / t.width * (e.pageX - t.left);
                            this.seekTime = this.player.media.duration * (n / 100), this.seekTime < 0 && (this.seekTime = 0), this.seekTime > this.player.media.duration - 1 && (this.seekTime = this.player.media.duration - 1), this.mousePosX = e.pageX, this.elements.thumb.time.innerText = Te(this.seekTime)
                        }
                        this.showImageAtCurrentTime()
                    }
                }
            }, {
                key: "endMove",
                value: function() {
                    this.toggleThumbContainer(!1, !0)
                }
            }, {
                key: "startScrubbing",
                value: function(e) {
                    !1 !== e.button && 0 !== e.button || (this.mouseDown = !0, this.player.media.duration && (this.toggleScrubbingContainer(!0), this.toggleThumbContainer(!1, !0), this.showImageAtCurrentTime()))
                }
            }, {
                key: "endScrubbing",
                value: function() {
                    var e = this;
                    this.mouseDown = !1, Math.ceil(this.lastTime) === Math.ceil(this.player.media.currentTime) ? this.toggleScrubbingContainer(!1) : x.call(this.player, this.player.media, "timeupdate", function() {
                        e.mouseDown || e.toggleScrubbingContainer(!1)
                    })
                }
            }, {
                key: "listeners",
                value: function() {
                    var e = this;
                    this.player.on("play", function() {
                        e.toggleThumbContainer(!1, !0)
                    }), this.player.on("seeked", function() {
                        e.toggleThumbContainer(!1)
                    }), this.player.on("timeupdate", function() {
                        e.lastTime = e.player.media.currentTime
                    })
                }
            }, {
                key: "render",
                value: function() {
                    this.elements.thumb.container = O("div", {
                        class: this.player.config.classNames.previewThumbnails.thumbContainer
                    }), this.elements.thumb.imageContainer = O("div", {
                        class: this.player.config.classNames.previewThumbnails.imageContainer
                    }), this.elements.thumb.container.appendChild(this.elements.thumb.imageContainer);
                    var e = O("div", {
                        class: this.player.config.classNames.previewThumbnails.timeContainer
                    });
                    this.elements.thumb.time = O("span", {}, "00:00"), e.appendChild(this.elements.thumb.time), this.elements.thumb.container.appendChild(e), this.player.elements.progress.appendChild(this.elements.thumb.container), this.elements.scrubbing.container = O("div", {
                        class: this.player.config.classNames.previewThumbnails.scrubbingContainer
                    }), this.player.elements.wrapper.appendChild(this.elements.scrubbing.container)
                }
            }, {
                key: "showImageAtCurrentTime",
                value: function() {
                    var e = this;
                    this.mouseDown ? this.setScrubbingContainerSize() : this.setThumbContainerSizeAndPos();
                    var t = this.thumbnails[0].frames.findIndex(function(t) {
                            return e.seekTime >= t.startTime && e.seekTime <= t.endTime
                        }),
                        n = t >= 0,
                        i = 0;
                    this.mouseDown || this.toggleThumbContainer(n), n && (this.thumbnails.forEach(function(n, a) {
                        e.loadedImages.includes(n.frames[t].text) && (i = a)
                    }), t !== this.showingThumb && (this.showingThumb = t, this.loadImage(i)))
                }
            }, {
                key: "loadImage",
                value: function() {
                    var e = this,
                        t = arguments.length > 0 && void 0 !== arguments[0] ? arguments[0] : 0,
                        n = this.showingThumb,
                        i = this.thumbnails[t],
                        a = i.urlPrefix,
                        s = i.frames[n],
                        o = i.frames[n].text,
                        r = a + o;
                    if (this.currentImageElement && this.currentImageElement.dataset.filename === o) this.showImage(this.currentImageElement, s, t, n, o, !1), this.currentImageElement.dataset.index = n, this.removeOldImages(this.currentImageElement);
                    else {
                        this.loadingImage && this.usingSprites && (this.loadingImage.onload = null);
                        var l = new Image;
                        l.src = r, l.dataset.index = n, l.dataset.filename = o, this.showingThumbFilename = o, this.player.debug.log("Loading image: ".concat(r)), l.onload = function() {
                            return e.showImage(l, s, t, n, o, !0)
                        }, this.loadingImage = l, this.removeOldImages(l)
                    }
                }
            }, {
                key: "showImage",
                value: function(e, t, n, i, a) {
                    var s = !(arguments.length > 5 && void 0 !== arguments[5]) || arguments[5];
                    this.player.debug.log("Showing thumb: ".concat(a, ". num: ").concat(i, ". qual: ").concat(n, ". newimg: ").concat(s)), this.setImageSizeAndOffset(e, t), s && (this.currentImageContainer.appendChild(e), this.currentImageElement = e, this.loadedImages.includes(a) || this.loadedImages.push(a)), this.preloadNearby(i, !0).then(this.preloadNearby(i, !1)).then(this.getHigherQuality(n, e, t, a))
                }
            }, {
                key: "removeOldImages",
                value: function(e) {
                    var t = this;
                    Array.from(this.currentImageContainer.children).forEach(function(n) {
                        if ("img" === n.tagName.toLowerCase()) {
                            var i = t.usingSprites ? 500 : 1e3;
                            if (n.dataset.index !== e.dataset.index && !n.dataset.deleting) {
                                n.dataset.deleting = !0;
                                var a = t.currentImageContainer;
                                setTimeout(function() {
                                    a.removeChild(n), t.player.debug.log("Removing thumb: ".concat(n.dataset.filename))
                                }, i)
                            }
                        }
                    })
                }
            }, {
                key: "preloadNearby",
                value: function(e) {
                    var t = this,
                        n = !(arguments.length > 1 && void 0 !== arguments[1]) || arguments[1];
                    return new Promise(function(i) {
                        setTimeout(function() {
                            var a = t.thumbnails[0].frames[e].text;
                            if (t.showingThumbFilename === a) {
                                var s;
                                s = n ? t.thumbnails[0].frames.slice(e) : t.thumbnails[0].frames.slice(0, e).reverse();
                                var o = !1;
                                s.forEach(function(e) {
                                    var n = e.text;
                                    if (n !== a && !t.loadedImages.includes(n)) {
                                        o = !0, t.player.debug.log("Preloading thumb filename: ".concat(n));
                                        var s = t.thumbnails[0].urlPrefix + n,
                                            r = new Image;
                                        r.src = s, r.onload = function() {
                                            t.player.debug.log("Preloaded thumb filename: ".concat(n)), t.loadedImages.includes(n) || t.loadedImages.push(n), i()
                                        }
                                    }
                                }), o || i()
                            }
                        }, 300)
                    })
                }
            }, {
                key: "getHigherQuality",
                value: function(e, t, n, i) {
                    var a = this;
                    if (e < this.thumbnails.length - 1) {
                        var s = t.naturalHeight;
                        this.usingSprites && (s = n.h), s < this.thumbContainerHeight && setTimeout(function() {
                            a.showingThumbFilename === i && (a.player.debug.log("Showing higher quality thumb for: ".concat(i)), a.loadImage(e + 1))
                        }, 300)
                    }
                }
            }, {
                key: "toggleThumbContainer",
                value: function() {
                    var e = arguments.length > 0 && void 0 !== arguments[0] && arguments[0],
                        t = arguments.length > 1 && void 0 !== arguments[1] && arguments[1],
                        n = this.player.config.classNames.previewThumbnails.thumbContainerShown;
                    this.elements.thumb.container.classList.toggle(n, e), !e && t && (this.showingThumb = null, this.showingThumbFilename = null)
                }
            }, {
                key: "toggleScrubbingContainer",
                value: function() {
                    var e = arguments.length > 0 && void 0 !== arguments[0] && arguments[0],
                        t = this.player.config.classNames.previewThumbnails.scrubbingContainerShown;
                    this.elements.scrubbing.container.classList.toggle(t, e), e || (this.showingThumb = null, this.showingThumbFilename = null)
                }
            }, {
                key: "determineContainerAutoSizing",
                value: function() {
                    this.elements.thumb.imageContainer.clientHeight > 20 && (this.sizeSpecifiedInCSS = !0)
                }
            }, {
                key: "setThumbContainerSizeAndPos",
                value: function() {
                    if (!this.sizeSpecifiedInCSS) {
                        var e = Math.floor(this.thumbContainerHeight * this.thumbAspectRatio);
                        this.elements.thumb.imageContainer.style.height = "".concat(this.thumbContainerHeight, "px"), this.elements.thumb.imageContainer.style.width = "".concat(e, "px")
                    }
                    this.setThumbContainerPos()
                }
            }, {
                key: "setThumbContainerPos",
                value: function() {
                    var e = this.player.elements.progress.getBoundingClientRect(),
                        t = this.player.elements.container.getBoundingClientRect(),
                        n = this.elements.thumb.container,
                        i = t.left - e.left + 10,
                        a = t.right - e.left - n.clientWidth - 10,
                        s = this.mousePosX - e.left - n.clientWidth / 2;
                    s < i && (s = i), s > a && (s = a), n.style.left = "".concat(s, "px")
                }
            }, {
                key: "setScrubbingContainerSize",
                value: function() {
                    this.elements.scrubbing.container.style.width = "".concat(this.player.media.clientWidth, "px"), this.elements.scrubbing.container.style.height = "".concat(this.player.media.clientWidth / this.thumbAspectRatio, "px")
                }
            }, {
                key: "setImageSizeAndOffset",
                value: function(e, t) {
                    if (this.usingSprites) {
                        var n = this.thumbContainerHeight / t.h;
                        e.style.height = "".concat(Math.floor(e.naturalHeight * n), "px"), e.style.width = "".concat(Math.floor(e.naturalWidth * n), "px"), e.style.left = "-".concat(t.x * n, "px"), e.style.top = "-".concat(t.y * n, "px")
                    }
                }
            }, {
                key: "enabled",
                get: function() {
                    return this.player.isHTML5 && this.player.isVideo && this.player.config.previewThumbnails.enabled
                }
            }, {
                key: "currentImageContainer",
                get: function() {
                    return this.mouseDown ? this.elements.scrubbing.container : this.elements.thumb.imageContainer
                }
            }, {
                key: "usingSprites",
                get: function() {
                    return Object.keys(this.thumbnails[0].frames[0]).includes("w")
                }
            }, {
                key: "thumbAspectRatio",
                get: function() {
                    return this.usingSprites ? this.thumbnails[0].frames[0].w / this.thumbnails[0].frames[0].h : this.thumbnails[0].width / this.thumbnails[0].height
                }
            }, {
                key: "thumbContainerHeight",
                get: function() {
                    return this.mouseDown ? Math.floor(this.player.media.clientWidth / this.thumbAspectRatio) : Math.floor(this.player.media.clientWidth / this.thumbAspectRatio / 4)
                }
            }, {
                key: "currentImageElement",
                get: function() {
                    return this.mouseDown ? this.currentScrubbingImageElement : this.currentThumbnailImageElement
                },
                set: function(e) {
                    this.mouseDown ? this.currentScrubbingImageElement = e : this.currentThumbnailImageElement = e
                }
            }]), t
        }(),
        Ge = {
            insertElements: function(e, t) {
                var n = this;
                E.string(t) ? j(e, this.media, {
                    src: t
                }) : E.array(t) && t.forEach(function(t) {
                    j(e, n.media, t)
                })
            },
            change: function(e) {
                var t = this;
                re(e, "sources.length") ? (se.cancelRequests.call(this), this.destroy.call(this, function() {
                    t.options.quality = [], q(t.media), t.media = null, E.element(t.elements.container) && t.elements.container.removeAttribute("class");
                    var n = e.sources,
                        i = e.type,
                        s = a(n, 1)[0],
                        o = s.provider,
                        r = void 0 === o ? xe.html5 : o,
                        l = s.src,
                        c = "html5" === r ? i : "div",
                        u = "html5" === r ? {} : {
                            src: l
                        };
                    Object.assign(t, {
                        provider: r,
                        type: i,
                        supported: te.check(i, r, t.config.playsinline),
                        media: O(c, u)
                    }), t.elements.container.appendChild(t.media), E.boolean(e.autoplay) && (t.config.autoplay = e.autoplay), t.isHTML5 && (t.config.crossorigin && t.media.setAttribute("crossorigin", ""), t.config.autoplay && t.media.setAttribute("autoplay", ""), E.empty(e.poster) || (t.poster = e.poster), t.config.loop.active && t.media.setAttribute("loop", ""), t.config.muted && t.media.setAttribute("muted", ""), t.config.playsinline && t.media.setAttribute("playsinline", "")), Fe.addStyleHook.call(t), t.isHTML5 && Ge.insertElements.call(t, "source", n), t.config.title = e.title, Xe.setup.call(t), t.isHTML5 && Object.keys(e).includes("tracks") && Ge.insertElements.call(t, "track", e.tracks), (t.isHTML5 || t.isEmbed && !t.supported.ui) && Fe.build.call(t), t.isHTML5 && t.media.load(), t.previewThumbnails && t.previewThumbnails.load(), t.fullscreen.update()
                }, !0)) : this.debug.warn("Invalid source format")
            }
        },
        Ze = function() {
            function t(n, i) {
                var a = this;
                if (e(this, t), this.timers = {}, this.ready = !1, this.loading = !1, this.failed = !1, this.touch = te.touch, this.media = n, E.string(this.media) && (this.media = document.querySelectorAll(this.media)), (window.jQuery && this.media instanceof jQuery || E.nodeList(this.media) || E.array(this.media)) && (this.media = this.media[0]), this.config = le({}, Pe, t.defaults, i || {}, function() {
                        try {
                            return JSON.parse(a.media.getAttribute("data-plyr-config"))
                        } catch (e) {
                            return {}
                        }
                    }()), this.elements = {
                        container: null,
                        captions: null,
                        buttons: {},
                        display: {},
                        progress: {},
                        inputs: {},
                        settings: {
                            popup: null,
                            menu: null,
                            panels: {},
                            buttons: {}
                        }
                    }, this.captions = {
                        active: null,
                        currentTrack: -1,
                        meta: new WeakMap
                    }, this.fullscreen = {
                        active: !1
                    }, this.options = {
                        speed: [],
                        quality: []
                    }, this.debug = new _e(this.config.debug), this.debug.log("Config", this.config), this.debug.log("Support", te), !E.nullOrUndefined(this.media) && E.element(this.media))
                    if (this.media.plyr) this.debug.warn("Target already setup");
                    else if (this.config.enabled)
                    if (te.check().api) {
                        var s = this.media.cloneNode(!0);
                        s.autoplay = !1, this.elements.original = s;
                        var o = this.media.tagName.toLowerCase(),
                            r = null,
                            l = null;
                        switch (o) {
                            case "div":
                                if (r = this.media.querySelector("iframe"), E.element(r)) {
                                    if (l = Ae(r.getAttribute("src")), this.provider = function(e) {
                                            return /^(https?:\/\/)?(www\.)?(youtube\.com|youtube-nocookie\.com|youtu\.?be)\/.+$/.test(e) ? xe.youtube : /^https?:\/\/player.vimeo.com\/video\/\d{0,9}(?=\b|\/)/.test(e) ? xe.vimeo : null
                                        }(l.toString()), this.elements.container = this.media, this.media = r, this.elements.container.className = "", l.search.length) {
                                        var c = ["1", "true"];
                                        c.includes(l.searchParams.get("autoplay")) && (this.config.autoplay = !0), c.includes(l.searchParams.get("loop")) && (this.config.loop.active = !0), this.isYouTube ? (this.config.playsinline = c.includes(l.searchParams.get("playsinline")), this.config.youtube.hl = l.searchParams.get("hl")) : this.config.playsinline = !0
                                    }
                                } else this.provider = this.media.getAttribute(this.config.attributes.embed.provider), this.media.removeAttribute(this.config.attributes.embed.provider);
                                if (E.empty(this.provider) || !Object.keys(xe).includes(this.provider)) return void this.debug.error("Setup failed: Invalid provider");
                                this.type = Le.video;
                                break;
                            case "video":
                            case "audio":
                                this.type = o, this.provider = xe.html5, this.media.hasAttribute("crossorigin") && (this.config.crossorigin = !0), this.media.hasAttribute("autoplay") && (this.config.autoplay = !0), (this.media.hasAttribute("playsinline") || this.media.hasAttribute("webkit-playsinline")) && (this.config.playsinline = !0), this.media.hasAttribute("muted") && (this.config.muted = !0), this.media.hasAttribute("loop") && (this.config.loop.active = !0);
                                break;
                            default:
                                return void this.debug.error("Setup failed: unsupported type")
                        }
                        this.supported = te.check(this.type, this.provider, this.config.playsinline), this.supported.api ? (this.eventListeners = [], this.listeners = new He(this), this.storage = new ge(this), this.media.plyr = this, E.element(this.elements.container) || (this.elements.container = O("div", {
                            tabindex: 0
                        }), I(this.media, this.elements.container)), Fe.addStyleHook.call(this), Xe.setup.call(this), this.config.debug && N.call(this, this.elements.container, this.config.events.join(" "), function(e) {
                            a.debug.log("event: ".concat(e.type))
                        }), (this.isHTML5 || this.isEmbed && !this.supported.ui) && Fe.build.call(this), this.listeners.container(), this.listeners.global(), this.fullscreen = new qe(this), this.config.ads.enabled && (this.ads = new Je(this)), this.isHTML5 && this.config.autoplay && setTimeout(function() {
                            return a.play()
                        }, 10), this.lastSeekTime = 0, this.config.previewThumbnails.enabled && (this.previewThumbnails = new $e(this))) : this.debug.error("Setup failed: no support")
                    } else this.debug.error("Setup failed: no support");
                else this.debug.error("Setup failed: disabled by config");
                else this.debug.error("Setup failed: no suitable element passed")
            }
            return n(t, [{
                key: "play",
                value: function() {
                    var e = this;
                    return E.function(this.media.play) ? (this.ads && this.ads.enabled && this.ads.managerPromise.then(function() {
                        return e.ads.play()
                    }).catch(function() {
                        return e.media.play()
                    }), this.media.play()) : null
                }
            }, {
                key: "pause",
                value: function() {
                    this.playing && E.function(this.media.pause) && this.media.pause()
                }
            }, {
                key: "togglePlay",
                value: function(e) {
                    (E.boolean(e) ? e : !this.playing) ? this.play(): this.pause()
                }
            }, {
                key: "stop",
                value: function() {
                    this.isHTML5 ? (this.pause(), this.restart()) : E.function(this.media.stop) && this.media.stop()
                }
            }, {
                key: "restart",
                value: function() {
                    this.currentTime = 0
                }
            }, {
                key: "rewind",
                value: function(e) {
                    this.currentTime = this.currentTime - (E.number(e) ? e : this.config.seekTime)
                }
            }, {
                key: "forward",
                value: function(e) {
                    this.currentTime = this.currentTime + (E.number(e) ? e : this.config.seekTime)
                }
            }, {
                key: "increaseVolume",
                value: function(e) {
                    var t = this.media.muted ? 0 : this.volume;
                    this.volume = t + (E.number(e) ? e : 0)
                }
            }, {
                key: "decreaseVolume",
                value: function(e) {
                    this.increaseVolume(-e)
                }
            }, {
                key: "toggleCaptions",
                value: function(e) {
                    Se.toggle.call(this, e, !1)
                }
            }, {
                key: "airplay",
                value: function() {
                    te.airplay && this.media.webkitShowPlaybackTargetPicker()
                }
            }, {
                key: "toggleControls",
                value: function(e) {
                    if (this.supported.ui && !this.isAudio) {
                        var t = V(this.elements.container, this.config.classNames.hideControls),
                            n = void 0 === e ? void 0 : !e,
                            i = B(this.elements.container, this.config.classNames.hideControls, n);
                        if (i && this.config.controls.includes("settings") && !E.empty(this.config.settings) && Ce.toggleMenu.call(this, !1), i !== t) {
                            var a = i ? "controlshidden" : "controlsshown";
                            L.call(this, this.media, a)
                        }
                        return !i
                    }
                    return !1
                }
            }, {
                key: "on",
                value: function(e, t) {
                    N.call(this, this.elements.container, e, t)
                }
            }, {
                key: "once",
                value: function(e, t) {
                    x.call(this, this.elements.container, e, t)
                }
            }, {
                key: "off",
                value: function(e, t) {
                    M(this.elements.container, e, t)
                }
            }, {
                key: "destroy",
                value: function(e) {
                    var t = this,
                        n = arguments.length > 1 && void 0 !== arguments[1] && arguments[1];
                    if (this.ready) {
                        var i = function() {
                            document.body.style.overflow = "", t.embed = null, n ? (Object.keys(t.elements).length && (q(t.elements.buttons.play), q(t.elements.captions), q(t.elements.controls), q(t.elements.wrapper), t.elements.buttons.play = null, t.elements.captions = null, t.elements.controls = null, t.elements.wrapper = null), E.function(e) && e()) : (function() {
                                this && this.eventListeners && (this.eventListeners.forEach(function(e) {
                                    var t = e.element,
                                        n = e.type,
                                        i = e.callback,
                                        a = e.options;
                                    t.removeEventListener(n, i, a)
                                }), this.eventListeners = [])
                            }.call(t), F(t.elements.original, t.elements.container), L.call(t, t.elements.original, "destroyed", !0), E.function(e) && e.call(t.elements.original), t.ready = !1, setTimeout(function() {
                                t.elements = null, t.media = null
                            }, 200))
                        };
                        this.stop(), clearTimeout(this.timers.loading), clearTimeout(this.timers.controls), clearTimeout(this.timers.resized), this.isHTML5 ? (Fe.toggleNativeControls.call(this, !0), i()) : this.isYouTube ? (clearInterval(this.timers.buffering), clearInterval(this.timers.playing), null !== this.embed && E.function(this.embed.destroy) && this.embed.destroy(), i()) : this.isVimeo && (null !== this.embed && this.embed.unload().then(i), setTimeout(i, 200))
                    }
                }
            }, {
                key: "supports",
                value: function(e) {
                    return te.mime.call(this, e)
                }
            }, {
                key: "isHTML5",
                get: function() {
                    return Boolean(this.provider === xe.html5)
                }
            }, {
                key: "isEmbed",
                get: function() {
                    return Boolean(this.isYouTube || this.isVimeo)
                }
            }, {
                key: "isYouTube",
                get: function() {
                    return Boolean(this.provider === xe.youtube)
                }
            }, {
                key: "isVimeo",
                get: function() {
                    return Boolean(this.provider === xe.vimeo)
                }
            }, {
                key: "isVideo",
                get: function() {
                    return Boolean(this.type === Le.video)
                }
            }, {
                key: "isAudio",
                get: function() {
                    return Boolean(this.type === Le.audio)
                }
            }, {
                key: "playing",
                get: function() {
                    return Boolean(this.ready && !this.paused && !this.ended)
                }
            }, {
                key: "paused",
                get: function() {
                    return Boolean(this.media.paused)
                }
            }, {
                key: "stopped",
                get: function() {
                    return Boolean(this.paused && 0 === this.currentTime)
                }
            }, {
                key: "ended",
                get: function() {
                    return Boolean(this.media.ended)
                }
            }, {
                key: "currentTime",
                set: function(e) {
                    if (this.duration) {
                        var t = E.number(e) && e > 0;
                        this.media.currentTime = t ? Math.min(e, this.duration) : 0, this.debug.log("Seeking to ".concat(this.currentTime, " seconds"))
                    }
                },
                get: function() {
                    return Number(this.media.currentTime)
                }
            }, {
                key: "buffered",
                get: function() {
                    var e = this.media.buffered;
                    return E.number(e) ? e : e && e.length && this.duration > 0 ? e.end(0) / this.duration : 0
                }
            }, {
                key: "seeking",
                get: function() {
                    return Boolean(this.media.seeking)
                }
            }, {
                key: "duration",
                get: function() {
                    var e = parseFloat(this.config.duration),
                        t = (this.media || {}).duration,
                        n = E.number(t) && t !== 1 / 0 ? t : 0;
                    return e || n
                }
            }, {
                key: "volume",
                set: function(e) {
                    var t = e;
                    E.string(t) && (t = Number(t)), E.number(t) || (t = this.storage.get("volume")), E.number(t) || (t = this.config.volume), t > 1 && (t = 1), t < 0 && (t = 0), this.config.volume = t, this.media.volume = t, !E.empty(e) && this.muted && t > 0 && (this.muted = !1)
                },
                get: function() {
                    return Number(this.media.volume)
                }
            }, {
                key: "muted",
                set: function(e) {
                    var t = e;
                    E.boolean(t) || (t = this.storage.get("muted")), E.boolean(t) || (t = this.config.muted), this.config.muted = t, this.media.muted = t
                },
                get: function() {
                    return Boolean(this.media.muted)
                }
            }, {
                key: "hasAudio",
                get: function() {
                    return !this.isHTML5 || (!!this.isAudio || (Boolean(this.media.mozHasAudio) || Boolean(this.media.webkitAudioDecodedByteCount) || Boolean(this.media.audioTracks && this.media.audioTracks.length)))
                }
            }, {
                key: "speed",
                set: function(e) {
                    var t = null;
                    E.number(e) && (t = e), E.number(t) || (t = this.storage.get("speed")), E.number(t) || (t = this.config.speed.selected), t < .1 && (t = .1), t > 2 && (t = 2), this.config.speed.options.includes(t) ? (this.config.speed.selected = t, this.media.playbackRate = t) : this.debug.warn("Unsupported speed (".concat(t, ")"))
                },
                get: function() {
                    return Number(this.media.playbackRate)
                }
            }, {
                key: "quality",
                set: function(e) {
                    var t = this.config.quality,
                        n = this.options.quality;
                    if (n.length) {
                        var i = [!E.empty(e) && Number(e), this.storage.get("quality"), t.selected, t.default].find(E.number),
                            a = !0;
                        if (!n.includes(i)) {
                            var s = function(e, t) {
                                return E.array(e) && e.length ? e.reduce(function(e, n) {
                                    return Math.abs(n - t) < Math.abs(e - t) ? n : e
                                }) : null
                            }(n, i);
                            this.debug.warn("Unsupported quality option: ".concat(i, ", using ").concat(s, " instead")), i = s, a = !1
                        }
                        t.selected = i, this.media.quality = i, a && this.storage.set({
                            quality: i
                        })
                    }
                },
                get: function() {
                    return this.media.quality
                }
            }, {
                key: "loop",
                set: function(e) {
                    var t = E.boolean(e) ? e : this.config.loop.active;
                    this.config.loop.active = t, this.media.loop = t
                },
                get: function() {
                    return Boolean(this.media.loop)
                }
            }, {
                key: "source",
                set: function(e) {
                    Ge.change.call(this, e)
                },
                get: function() {
                    return this.media.currentSrc
                }
            }, {
                key: "download",
                get: function() {
                    var e = this.config.urls.download;
                    return E.url(e) ? e : this.source
                }
            }, {
                key: "poster",
                set: function(e) {
                    this.isVideo ? Fe.setPoster.call(this, e, !1).catch(function() {}) : this.debug.warn("Poster can only be set for video")
                },
                get: function() {
                    return this.isVideo ? this.media.getAttribute("poster") : null
                }
            }, {
                key: "ratio",
                get: function() {
                    var e = function(e) {
                        if (!E.array(e) || !e.every(E.number)) return null;
                        var t = a(e, 2),
                            n = t[0],
                            i = t[1],
                            s = function e(t, n) {
                                return 0 === n ? t : e(n, t % n)
                            }(n, i);
                        return [n / s, i / s]
                    }(ie.call(this));
                    return E.array(e) ? e.join(":") : e
                },
                set: function(e) {
                    this.isVideo ? E.string(e) && ne(e) ? (this.config.ratio = e, ae.call(this)) : this.debug.error("Invalid aspect ratio specified (".concat(e, ")")) : this.debug.warn("Aspect ratio can only be set for video")
                }
            }, {
                key: "autoplay",
                set: function(e) {
                    var t = E.boolean(e) ? e : this.config.autoplay;
                    this.config.autoplay = t
                },
                get: function() {
                    return Boolean(this.config.autoplay)
                }
            }, {
                key: "currentTrack",
                set: function(e) {
                    Se.set.call(this, e, !1)
                },
                get: function() {
                    var e = this.captions,
                        t = e.toggled,
                        n = e.currentTrack;
                    return t ? n : -1
                }
            }, {
                key: "language",
                set: function(e) {
                    Se.setLanguage.call(this, e, !1)
                },
                get: function() {
                    return (Se.getCurrentTrack.call(this) || {}).language
                }
            }, {
                key: "pip",
                set: function(e) {
                    if (te.pip) {
                        var t = E.boolean(e) ? e : !this.pip;
                        E.function(this.media.webkitSetPresentationMode) && this.media.webkitSetPresentationMode(t ? Ne : Me), E.function(this.media.requestPictureInPicture) && (!this.pip && t ? this.media.requestPictureInPicture() : this.pip && !t && document.exitPictureInPicture())
                    }
                },
                get: function() {
                    return te.pip ? E.empty(this.media.webkitPresentationMode) ? this.media === document.pictureInPictureElement : this.media.webkitPresentationMode === Ne : null
                }
            }], [{
                key: "supported",
                value: function(e, t, n) {
                    return te.check(e, t, n)
                }
            }, {
                key: "loadSprite",
                value: function(e, t) {
                    return ve(e, t)
                }
            }, {
                key: "setup",
                value: function(e) {
                    var n = arguments.length > 1 && void 0 !== arguments[1] ? arguments[1] : {},
                        i = null;
                    return E.string(e) ? i = Array.from(document.querySelectorAll(e)) : E.nodeList(e) ? i = Array.from(e) : E.array(e) && (i = e.filter(E.element)), E.empty(i) ? null : i.map(function(e) {
                        return new t(e, n)
                    })
                }
            }]), t
        }();
    return Ze.defaults = (Ye = Pe, JSON.parse(JSON.stringify(Ye))), Ze
});
//# sourceMappingURL=plyr.js.map
});