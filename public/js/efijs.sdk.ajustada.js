(() => {
    var t = {
            758: (t) => {
                t.exports = { lib: { version: "2.1.0" }, aF: { cS: { key: "a2fc7cc16f" } } };
            },
            963: (t) => {
                var e;
                window,
                    (e = () =>
                        (() => {
                            var t = {
                                    155: (t) => {
                                        var e,
                                            r,
                                            i = (t.exports = {});
                                        function n() {
                                            throw new Error("setTimeout has not been defined");
                                        }
                                        function s() {
                                            throw new Error("clearTimeout has not been defined");
                                        }
                                        function o(t) {
                                            if (e === setTimeout) return setTimeout(t, 0);
                                            if ((e === n || !e) && setTimeout) return (e = setTimeout), setTimeout(t, 0);
                                            try {
                                                return e(t, 0);
                                            } catch (r) {
                                                try {
                                                    return e.call(null, t, 0);
                                                } catch (r) {
                                                    return e.call(this, t, 0);
                                                }
                                            }
                                        }
                                        !(function () {
                                            try {
                                                e = "function" == typeof setTimeout ? setTimeout : n;
                                            } catch (t) {
                                                e = n;
                                            }
                                            try {
                                                r = "function" == typeof clearTimeout ? clearTimeout : s;
                                            } catch (t) {
                                                r = s;
                                            }
                                        })();
                                        var a,
                                            h = [],
                                            c = !1,
                                            u = -1;
                                        function l() {
                                            c && a && ((c = !1), a.length ? (h = a.concat(h)) : (u = -1), h.length && f());
                                        }
                                        function f() {
                                            if (!c) {
                                                var t = o(l);
                                                c = !0;
                                                for (var e = h.length; e; ) {
                                                    for (a = h, h = []; ++u < e; ) a && a[u].run();
                                                    (u = -1), (e = h.length);
                                                }
                                                (a = null),
                                                    (c = !1),
                                                    (function (t) {
                                                        if (r === clearTimeout) return clearTimeout(t);
                                                        if ((r === s || !r) && clearTimeout) return (r = clearTimeout), clearTimeout(t);
                                                        try {
                                                            r(t);
                                                        } catch (e) {
                                                            try {
                                                                return r.call(null, t);
                                                            } catch (e) {
                                                                return r.call(this, t);
                                                            }
                                                        }
                                                    })(t);
                                            }
                                        }
                                        function d(t, e) {
                                            (this.fun = t), (this.array = e);
                                        }
                                        function p() {}
                                        (i.nextTick = function (t) {
                                            var e = new Array(arguments.length - 1);
                                            if (arguments.length > 1) for (var r = 1; r < arguments.length; r++) e[r - 1] = arguments[r];
                                            h.push(new d(t, e)), 1 !== h.length || c || o(f);
                                        }),
                                            (d.prototype.run = function () {
                                                this.fun.apply(null, this.array);
                                            }),
                                            (i.title = "browser"),
                                            (i.browser = !0),
                                            (i.env = {}),
                                            (i.argv = []),
                                            (i.version = ""),
                                            (i.versions = {}),
                                            (i.on = p),
                                            (i.addListener = p),
                                            (i.once = p),
                                            (i.off = p),
                                            (i.removeListener = p),
                                            (i.removeAllListeners = p),
                                            (i.emit = p),
                                            (i.prependListener = p),
                                            (i.prependOnceListener = p),
                                            (i.listeners = function (t) {
                                                return [];
                                            }),
                                            (i.binding = function (t) {
                                                throw new Error("process.binding is not supported");
                                            }),
                                            (i.cwd = function () {
                                                return "/";
                                            }),
                                            (i.chdir = function (t) {
                                                throw new Error("process.chdir is not supported");
                                            }),
                                            (i.umask = function () {
                                                return 0;
                                            });
                                    },
                                },
                                e = {};
                            function r(i) {
                                var n = e[i];
                                if (void 0 !== n) return n.exports;
                                var s = (e[i] = { exports: {} });
                                return t[i](s, s.exports, r), s.exports;
                            }
                            (r.d = (t, e) => {
                                for (var i in e) r.o(e, i) && !r.o(t, i) && Object.defineProperty(t, i, { enumerable: !0, get: e[i] });
                            }),
                                (r.o = (t, e) => Object.prototype.hasOwnProperty.call(t, e));
                            var i = {};
                            return (
                                (() => {
                                    "use strict";
                                    function t(t) {
                                        return "0123456789abcdefghijklmnopqrstuvwxyz".charAt(t);
                                    }
                                    function e(t, e) {
                                        return t & e;
                                    }
                                    function n(t, e) {
                                        return t | e;
                                    }
                                    function s(t, e) {
                                        return t ^ e;
                                    }
                                    function o(t, e) {
                                        return t & ~e;
                                    }
                                    function a(t) {
                                        if (0 == t) return -1;
                                        var e = 0;
                                        return 0 == (65535 & t) && ((t >>= 16), (e += 16)), 0 == (255 & t) && ((t >>= 8), (e += 8)), 0 == (15 & t) && ((t >>= 4), (e += 4)), 0 == (3 & t) && ((t >>= 2), (e += 2)), 0 == (1 & t) && ++e, e;
                                    }
                                    function h(t) {
                                        for (var e = 0; 0 != t; ) (t &= t - 1), ++e;
                                        return e;
                                    }
                                    r.d(i, { default: () => ot });
                                    var c,
                                        u = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/";
                                    function l(t) {
                                        var e,
                                            r,
                                            i = "";
                                        for (e = 0; e + 3 <= t.length; e += 3) (r = parseInt(t.substring(e, e + 3), 16)), (i += u.charAt(r >> 6) + u.charAt(63 & r));
                                        for (
                                            e + 1 == t.length
                                                ? ((r = parseInt(t.substring(e, e + 1), 16)), (i += u.charAt(r << 2)))
                                                : e + 2 == t.length && ((r = parseInt(t.substring(e, e + 2), 16)), (i += u.charAt(r >> 2) + u.charAt((3 & r) << 4)));
                                            (3 & i.length) > 0;

                                        )
                                            i += "=";
                                        return i;
                                    }
                                    function f(e) {
                                        var r,
                                            i = "",
                                            n = 0,
                                            s = 0;
                                        for (r = 0; r < e.length && "=" != e.charAt(r); ++r) {
                                            var o = u.indexOf(e.charAt(r));
                                            o < 0 ||
                                            (0 == n
                                                ? ((i += t(o >> 2)), (s = 3 & o), (n = 1))
                                                : 1 == n
                                                    ? ((i += t((s << 2) | (o >> 4))), (s = 15 & o), (n = 2))
                                                    : 2 == n
                                                        ? ((i += t(s)), (i += t(o >> 2)), (s = 3 & o), (n = 3))
                                                        : ((i += t((s << 2) | (o >> 4))), (i += t(15 & o)), (n = 0)));
                                        }
                                        return 1 == n && (i += t(s << 2)), i;
                                    }
                                    var d,
                                        p = {
                                            decode: function (t) {
                                                var e;
                                                if (void 0 === d) {
                                                    for (d = Object.create(null), e = 0; e < 64; ++e) d["ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/".charAt(e)] = e;
                                                    for (d["-"] = 62, d._ = 63, e = 0; e < 9; ++e) d["= \f\n\r\t \u2028\u2029".charAt(e)] = -1;
                                                }
                                                var r = [],
                                                    i = 0,
                                                    n = 0;
                                                for (e = 0; e < t.length; ++e) {
                                                    var s = t.charAt(e);
                                                    if ("=" == s) break;
                                                    if (-1 != (s = d[s])) {
                                                        if (void 0 === s) throw new Error("Illegal character at offset " + e);
                                                        (i |= s), ++n >= 4 ? ((r[r.length] = i >> 16), (r[r.length] = (i >> 8) & 255), (r[r.length] = 255 & i), (i = 0), (n = 0)) : (i <<= 6);
                                                    }
                                                }
                                                switch (n) {
                                                    case 1:
                                                        throw new Error("Base64 encoding incomplete: at least 2 bits missing");
                                                    case 2:
                                                        r[r.length] = i >> 10;
                                                        break;
                                                    case 3:
                                                        (r[r.length] = i >> 16), (r[r.length] = (i >> 8) & 255);
                                                }
                                                return r;
                                            },
                                            re: /-----BEGIN [^-]+-----([A-Za-z0-9+\/=\s]+)-----END [^-]+-----|begin-base64[^\n]+\n([A-Za-z0-9+\/=\s]+)====/,
                                            unarmor: function (t) {
                                                var e = p.re.exec(t);
                                                if (e)
                                                    if (e[1]) t = e[1];
                                                    else {
                                                        if (!e[2]) throw new Error("RegExp out of sync");
                                                        t = e[2];
                                                    }
                                                return p.decode(t);
                                            },
                                        },
                                        g = 1e13,
                                        m = (function () {
                                            function t(t) {
                                                this.buf = [+t || 0];
                                            }
                                            return (
                                                (t.prototype.mulAdd = function (t, e) {
                                                    var r,
                                                        i,
                                                        n = this.buf,
                                                        s = n.length;
                                                    for (r = 0; r < s; ++r) (i = n[r] * t + e) < g ? (e = 0) : (i -= (e = 0 | (i / g)) * g), (n[r] = i);
                                                    e > 0 && (n[r] = e);
                                                }),
                                                    (t.prototype.sub = function (t) {
                                                        var e,
                                                            r,
                                                            i = this.buf,
                                                            n = i.length;
                                                        for (e = 0; e < n; ++e) (r = i[e] - t) < 0 ? ((r += g), (t = 1)) : (t = 0), (i[e] = r);
                                                        for (; 0 === i[i.length - 1]; ) i.pop();
                                                    }),
                                                    (t.prototype.toString = function (t) {
                                                        if (10 != (t || 10)) throw new Error("only base 10 is supported");
                                                        for (var e = this.buf, r = e[e.length - 1].toString(), i = e.length - 2; i >= 0; --i) r += (g + e[i]).toString().substring(1);
                                                        return r;
                                                    }),
                                                    (t.prototype.valueOf = function () {
                                                        for (var t = this.buf, e = 0, r = t.length - 1; r >= 0; --r) e = e * g + t[r];
                                                        return e;
                                                    }),
                                                    (t.prototype.simplify = function () {
                                                        var t = this.buf;
                                                        return 1 == t.length ? t[0] : this;
                                                    }),
                                                    t
                                            );
                                        })(),
                                        v = /^(\d\d)(0[1-9]|1[0-2])(0[1-9]|[12]\d|3[01])([01]\d|2[0-3])(?:([0-5]\d)(?:([0-5]\d)(?:[.,](\d{1,3}))?)?)?(Z|[-+](?:[0]\d|1[0-2])([0-5]\d)?)?$/,
                                        y = /^(\d\d\d\d)(0[1-9]|1[0-2])(0[1-9]|[12]\d|3[01])([01]\d|2[0-3])(?:([0-5]\d)(?:([0-5]\d)(?:[.,](\d{1,3}))?)?)?(Z|[-+](?:[0]\d|1[0-2])([0-5]\d)?)?$/;
                                    function b(t, e) {
                                        return t.length > e && (t = t.substring(0, e) + "…"), t;
                                    }
                                    var w,
                                        T = (function () {
                                            function t(e, r) {
                                                (this.hexDigits = "0123456789ABCDEF"), e instanceof t ? ((this.enc = e.enc), (this.pos = e.pos)) : ((this.enc = e), (this.pos = r));
                                            }
                                            return (
                                                (t.prototype.get = function (t) {
                                                    if ((void 0 === t && (t = this.pos++), t >= this.enc.length)) throw new Error("Requesting byte offset ".concat(t, " on a stream of length ").concat(this.enc.length));
                                                    return "string" == typeof this.enc ? this.enc.charCodeAt(t) : this.enc[t];
                                                }),
                                                    (t.prototype.hexByte = function (t) {
                                                        return this.hexDigits.charAt((t >> 4) & 15) + this.hexDigits.charAt(15 & t);
                                                    }),
                                                    (t.prototype.hexDump = function (t, e, r) {
                                                        for (var i = "", n = t; n < e; ++n)
                                                            if (((i += this.hexByte(this.get(n))), !0 !== r))
                                                                switch (15 & n) {
                                                                    case 7:
                                                                        i += "  ";
                                                                        break;
                                                                    case 15:
                                                                        i += "\n";
                                                                        break;
                                                                    default:
                                                                        i += " ";
                                                                }
                                                        return i;
                                                    }),
                                                    (t.prototype.isASCII = function (t, e) {
                                                        for (var r = t; r < e; ++r) {
                                                            var i = this.get(r);
                                                            if (i < 32 || i > 176) return !1;
                                                        }
                                                        return !0;
                                                    }),
                                                    (t.prototype.parseStringISO = function (t, e) {
                                                        for (var r = "", i = t; i < e; ++i) r += String.fromCharCode(this.get(i));
                                                        return r;
                                                    }),
                                                    (t.prototype.parseStringUTF = function (t, e) {
                                                        for (var r = "", i = t; i < e; ) {
                                                            var n = this.get(i++);
                                                            r +=
                                                                n < 128
                                                                    ? String.fromCharCode(n)
                                                                    : n > 191 && n < 224
                                                                        ? String.fromCharCode(((31 & n) << 6) | (63 & this.get(i++)))
                                                                        : String.fromCharCode(((15 & n) << 12) | ((63 & this.get(i++)) << 6) | (63 & this.get(i++)));
                                                        }
                                                        return r;
                                                    }),
                                                    (t.prototype.parseStringBMP = function (t, e) {
                                                        for (var r, i, n = "", s = t; s < e; ) (r = this.get(s++)), (i = this.get(s++)), (n += String.fromCharCode((r << 8) | i));
                                                        return n;
                                                    }),
                                                    (t.prototype.parseTime = function (t, e, r) {
                                                        var i = this.parseStringISO(t, e),
                                                            n = (r ? v : y).exec(i);
                                                        return n
                                                            ? (r && ((n[1] = +n[1]), (n[1] += +n[1] < 70 ? 2e3 : 1900)),
                                                                (i = n[1] + "-" + n[2] + "-" + n[3] + " " + n[4]),
                                                            n[5] && ((i += ":" + n[5]), n[6] && ((i += ":" + n[6]), n[7] && (i += "." + n[7]))),
                                                            n[8] && ((i += " UTC"), "Z" != n[8] && ((i += n[8]), n[9] && (i += ":" + n[9]))),
                                                                i)
                                                            : "Unrecognized time: " + i;
                                                    }),
                                                    (t.prototype.parseInteger = function (t, e) {
                                                        for (var r, i = this.get(t), n = i > 127, s = n ? 255 : 0, o = ""; i == s && ++t < e; ) i = this.get(t);
                                                        if (0 == (r = e - t)) return n ? -1 : 0;
                                                        if (r > 4) {
                                                            for (o = i, r <<= 3; 0 == (128 & (+o ^ s)); ) (o = +o << 1), --r;
                                                            o = "(" + r + " bit)\n";
                                                        }
                                                        n && (i -= 256);
                                                        for (var a = new m(i), h = t + 1; h < e; ++h) a.mulAdd(256, this.get(h));
                                                        return o + a.toString();
                                                    }),
                                                    (t.prototype.parseBitString = function (t, e, r) {
                                                        for (var i = this.get(t), n = "(" + (((e - t - 1) << 3) - i) + " bit)\n", s = "", o = t + 1; o < e; ++o) {
                                                            for (var a = this.get(o), h = o == e - 1 ? i : 0, c = 7; c >= h; --c) s += (a >> c) & 1 ? "1" : "0";
                                                            if (s.length > r) return n + b(s, r);
                                                        }
                                                        return n + s;
                                                    }),
                                                    (t.prototype.parseOctetString = function (t, e, r) {
                                                        if (this.isASCII(t, e)) return b(this.parseStringISO(t, e), r);
                                                        var i = e - t,
                                                            n = "(" + i + " byte)\n";
                                                        i > (r /= 2) && (e = t + r);
                                                        for (var s = t; s < e; ++s) n += this.hexByte(this.get(s));
                                                        return i > r && (n += "…"), n;
                                                    }),
                                                    (t.prototype.parseOID = function (t, e, r) {
                                                        for (var i = "", n = new m(), s = 0, o = t; o < e; ++o) {
                                                            var a = this.get(o);
                                                            if ((n.mulAdd(128, 127 & a), (s += 7), !(128 & a))) {
                                                                if ("" === i)
                                                                    if ((n = n.simplify()) instanceof m) n.sub(80), (i = "2." + n.toString());
                                                                    else {
                                                                        var h = n < 80 ? (n < 40 ? 0 : 1) : 2;
                                                                        i = h + "." + (n - 40 * h);
                                                                    }
                                                                else i += "." + n.toString();
                                                                if (i.length > r) return b(i, r);
                                                                (n = new m()), (s = 0);
                                                            }
                                                        }
                                                        return s > 0 && (i += ".incomplete"), i;
                                                    }),
                                                    t
                                            );
                                        })(),
                                        S = (function () {
                                            function t(t, e, r, i, n) {
                                                if (!(i instanceof E)) throw new Error("Invalid tag value.");
                                                (this.stream = t), (this.header = e), (this.length = r), (this.tag = i), (this.sub = n);
                                            }
                                            return (
                                                (t.prototype.typeName = function () {
                                                    switch (this.tag.tagClass) {
                                                        case 0:
                                                            switch (this.tag.tagNumber) {
                                                                case 0:
                                                                    return "EOC";
                                                                case 1:
                                                                    return "BOOLEAN";
                                                                case 2:
                                                                    return "INTEGER";
                                                                case 3:
                                                                    return "BIT_STRING";
                                                                case 4:
                                                                    return "OCTET_STRING";
                                                                case 5:
                                                                    return "NULL";
                                                                case 6:
                                                                    return "OBJECT_IDENTIFIER";
                                                                case 7:
                                                                    return "ObjectDescriptor";
                                                                case 8:
                                                                    return "EXTERNAL";
                                                                case 9:
                                                                    return "REAL";
                                                                case 10:
                                                                    return "ENUMERATED";
                                                                case 11:
                                                                    return "EMBEDDED_PDV";
                                                                case 12:
                                                                    return "UTF8String";
                                                                case 16:
                                                                    return "SEQUENCE";
                                                                case 17:
                                                                    return "SET";
                                                                case 18:
                                                                    return "NumericString";
                                                                case 19:
                                                                    return "PrintableString";
                                                                case 20:
                                                                    return "TeletexString";
                                                                case 21:
                                                                    return "VideotexString";
                                                                case 22:
                                                                    return "IA5String";
                                                                case 23:
                                                                    return "UTCTime";
                                                                case 24:
                                                                    return "GeneralizedTime";
                                                                case 25:
                                                                    return "GraphicString";
                                                                case 26:
                                                                    return "VisibleString";
                                                                case 27:
                                                                    return "GeneralString";
                                                                case 28:
                                                                    return "UniversalString";
                                                                case 30:
                                                                    return "BMPString";
                                                            }
                                                            return "Universal_" + this.tag.tagNumber.toString();
                                                        case 1:
                                                            return "Application_" + this.tag.tagNumber.toString();
                                                        case 2:
                                                            return "[" + this.tag.tagNumber.toString() + "]";
                                                        case 3:
                                                            return "Private_" + this.tag.tagNumber.toString();
                                                    }
                                                }),
                                                    (t.prototype.content = function (t) {
                                                        if (void 0 === this.tag) return null;
                                                        void 0 === t && (t = 1 / 0);
                                                        var e = this.posContent(),
                                                            r = Math.abs(this.length);
                                                        if (!this.tag.isUniversal()) return null !== this.sub ? "(" + this.sub.length + " elem)" : this.stream.parseOctetString(e, e + r, t);
                                                        switch (this.tag.tagNumber) {
                                                            case 1:
                                                                return 0 === this.stream.get(e) ? "false" : "true";
                                                            case 2:
                                                                return this.stream.parseInteger(e, e + r);
                                                            case 3:
                                                                return this.sub ? "(" + this.sub.length + " elem)" : this.stream.parseBitString(e, e + r, t);
                                                            case 4:
                                                                return this.sub ? "(" + this.sub.length + " elem)" : this.stream.parseOctetString(e, e + r, t);
                                                            case 6:
                                                                return this.stream.parseOID(e, e + r, t);
                                                            case 16:
                                                            case 17:
                                                                return null !== this.sub ? "(" + this.sub.length + " elem)" : "(no elem)";
                                                            case 12:
                                                                return b(this.stream.parseStringUTF(e, e + r), t);
                                                            case 18:
                                                            case 19:
                                                            case 20:
                                                            case 21:
                                                            case 22:
                                                            case 26:
                                                                return b(this.stream.parseStringISO(e, e + r), t);
                                                            case 30:
                                                                return b(this.stream.parseStringBMP(e, e + r), t);
                                                            case 23:
                                                            case 24:
                                                                return this.stream.parseTime(e, e + r, 23 == this.tag.tagNumber);
                                                        }
                                                        return null;
                                                    }),
                                                    (t.prototype.toString = function () {
                                                        return this.typeName() + "@" + this.stream.pos + "[header:" + this.header + ",length:" + this.length + ",sub:" + (null === this.sub ? "null" : this.sub.length) + "]";
                                                    }),
                                                    (t.prototype.toPrettyString = function (t) {
                                                        void 0 === t && (t = "");
                                                        var e = t + this.typeName() + " @" + this.stream.pos;
                                                        if (
                                                            (this.length >= 0 && (e += "+"),
                                                                (e += this.length),
                                                                this.tag.tagConstructed ? (e += " (constructed)") : !this.tag.isUniversal() || (3 != this.tag.tagNumber && 4 != this.tag.tagNumber) || null === this.sub || (e += " (encapsulates)"),
                                                                (e += "\n"),
                                                            null !== this.sub)
                                                        ) {
                                                            t += "  ";
                                                            for (var r = 0, i = this.sub.length; r < i; ++r) e += this.sub[r].toPrettyString(t);
                                                        }
                                                        return e;
                                                    }),
                                                    (t.prototype.posStart = function () {
                                                        return this.stream.pos;
                                                    }),
                                                    (t.prototype.posContent = function () {
                                                        return this.stream.pos + this.header;
                                                    }),
                                                    (t.prototype.posEnd = function () {
                                                        return this.stream.pos + this.header + Math.abs(this.length);
                                                    }),
                                                    (t.prototype.toHexString = function () {
                                                        return this.stream.hexDump(this.posStart(), this.posEnd(), !0);
                                                    }),
                                                    (t.decodeLength = function (t) {
                                                        var e = t.get(),
                                                            r = 127 & e;
                                                        if (r == e) return r;
                                                        if (r > 6) throw new Error("Length over 48 bits not supported at position " + (t.pos - 1));
                                                        if (0 === r) return null;
                                                        e = 0;
                                                        for (var i = 0; i < r; ++i) e = 256 * e + t.get();
                                                        return e;
                                                    }),
                                                    (t.prototype.getHexStringValue = function () {
                                                        var t = this.toHexString(),
                                                            e = 2 * this.header,
                                                            r = 2 * this.length;
                                                        return t.substr(e, r);
                                                    }),
                                                    (t.decode = function (e) {
                                                        var r;
                                                        r = e instanceof T ? e : new T(e, 0);
                                                        var i = new T(r),
                                                            n = new E(r),
                                                            s = t.decodeLength(r),
                                                            o = r.pos,
                                                            a = o - i.pos,
                                                            h = null,
                                                            c = function () {
                                                                var e = [];
                                                                if (null !== s) {
                                                                    for (var i = o + s; r.pos < i; ) e[e.length] = t.decode(r);
                                                                    if (r.pos != i) throw new Error("Content size is not correct for container starting at offset " + o);
                                                                } else
                                                                    try {
                                                                        for (;;) {
                                                                            var n = t.decode(r);
                                                                            if (n.tag.isEOC()) break;
                                                                            e[e.length] = n;
                                                                        }
                                                                        s = o - r.pos;
                                                                    } catch (t) {
                                                                        throw new Error("Exception while decoding undefined length content: " + t);
                                                                    }
                                                                return e;
                                                            };
                                                        if (n.tagConstructed) h = c();
                                                        else if (n.isUniversal() && (3 == n.tagNumber || 4 == n.tagNumber))
                                                            try {
                                                                if (3 == n.tagNumber && 0 != r.get()) throw new Error("BIT STRINGs with unused bits cannot encapsulate.");
                                                                h = c();
                                                                for (var u = 0; u < h.length; ++u) if (h[u].tag.isEOC()) throw new Error("EOC is not supposed to be actual content.");
                                                            } catch (t) {
                                                                h = null;
                                                            }
                                                        if (null === h) {
                                                            if (null === s) throw new Error("We can't skip over an invalid tag with undefined length at offset " + o);
                                                            r.pos = o + Math.abs(s);
                                                        }
                                                        return new t(i, a, s, n, h);
                                                    }),
                                                    t
                                            );
                                        })(),
                                        E = (function () {
                                            function t(t) {
                                                var e = t.get();
                                                if (((this.tagClass = e >> 6), (this.tagConstructed = 0 != (32 & e)), (this.tagNumber = 31 & e), 31 == this.tagNumber)) {
                                                    var r = new m();
                                                    do {
                                                        (e = t.get()), r.mulAdd(128, 127 & e);
                                                    } while (128 & e);
                                                    this.tagNumber = r.simplify();
                                                }
                                            }
                                            return (
                                                (t.prototype.isUniversal = function () {
                                                    return 0 === this.tagClass;
                                                }),
                                                    (t.prototype.isEOC = function () {
                                                        return 0 === this.tagClass && 0 === this.tagNumber;
                                                    }),
                                                    t
                                            );
                                        })(),
                                        D = [
                                            2,
                                            3,
                                            5,
                                            7,
                                            11,
                                            13,
                                            17,
                                            19,
                                            23,
                                            29,
                                            31,
                                            37,
                                            41,
                                            43,
                                            47,
                                            53,
                                            59,
                                            61,
                                            67,
                                            71,
                                            73,
                                            79,
                                            83,
                                            89,
                                            97,
                                            101,
                                            103,
                                            107,
                                            109,
                                            113,
                                            127,
                                            131,
                                            137,
                                            139,
                                            149,
                                            151,
                                            157,
                                            163,
                                            167,
                                            173,
                                            179,
                                            181,
                                            191,
                                            193,
                                            197,
                                            199,
                                            211,
                                            223,
                                            227,
                                            229,
                                            233,
                                            239,
                                            241,
                                            251,
                                            257,
                                            263,
                                            269,
                                            271,
                                            277,
                                            281,
                                            283,
                                            293,
                                            307,
                                            311,
                                            313,
                                            317,
                                            331,
                                            337,
                                            347,
                                            349,
                                            353,
                                            359,
                                            367,
                                            373,
                                            379,
                                            383,
                                            389,
                                            397,
                                            401,
                                            409,
                                            419,
                                            421,
                                            431,
                                            433,
                                            439,
                                            443,
                                            449,
                                            457,
                                            461,
                                            463,
                                            467,
                                            479,
                                            487,
                                            491,
                                            499,
                                            503,
                                            509,
                                            521,
                                            523,
                                            541,
                                            547,
                                            557,
                                            563,
                                            569,
                                            571,
                                            577,
                                            587,
                                            593,
                                            599,
                                            601,
                                            607,
                                            613,
                                            617,
                                            619,
                                            631,
                                            641,
                                            643,
                                            647,
                                            653,
                                            659,
                                            661,
                                            673,
                                            677,
                                            683,
                                            691,
                                            701,
                                            709,
                                            719,
                                            727,
                                            733,
                                            739,
                                            743,
                                            751,
                                            757,
                                            761,
                                            769,
                                            773,
                                            787,
                                            797,
                                            809,
                                            811,
                                            821,
                                            823,
                                            827,
                                            829,
                                            839,
                                            853,
                                            857,
                                            859,
                                            863,
                                            877,
                                            881,
                                            883,
                                            887,
                                            907,
                                            911,
                                            919,
                                            929,
                                            937,
                                            941,
                                            947,
                                            953,
                                            967,
                                            971,
                                            977,
                                            983,
                                            991,
                                            997,
                                        ],
                                        x = (1 << 26) / D[D.length - 1],
                                        R = (function () {
                                            function r(t, e, r) {
                                                null != t && ("number" == typeof t ? this.fromNumber(t, e, r) : null == e && "string" != typeof t ? this.fromString(t, 256) : this.fromString(t, e));
                                            }
                                            return (
                                                (r.prototype.toString = function (e) {
                                                    if (this.s < 0) return "-" + this.negate().toString(e);
                                                    var r;
                                                    if (16 == e) r = 4;
                                                    else if (8 == e) r = 3;
                                                    else if (2 == e) r = 1;
                                                    else if (32 == e) r = 5;
                                                    else {
                                                        if (4 != e) return this.toRadix(e);
                                                        r = 2;
                                                    }
                                                    var i,
                                                        n = (1 << r) - 1,
                                                        s = !1,
                                                        o = "",
                                                        a = this.t,
                                                        h = this.DB - ((a * this.DB) % r);
                                                    if (a-- > 0)
                                                        for (h < this.DB && (i = this[a] >> h) > 0 && ((s = !0), (o = t(i))); a >= 0; )
                                                            h < r ? ((i = (this[a] & ((1 << h) - 1)) << (r - h)), (i |= this[--a] >> (h += this.DB - r))) : ((i = (this[a] >> (h -= r)) & n), h <= 0 && ((h += this.DB), --a)),
                                                            i > 0 && (s = !0),
                                                            s && (o += t(i));
                                                    return s ? o : "0";
                                                }),
                                                    (r.prototype.negate = function () {
                                                        var t = V();
                                                        return r.ZERO.subTo(this, t), t;
                                                    }),
                                                    (r.prototype.abs = function () {
                                                        return this.s < 0 ? this.negate() : this;
                                                    }),
                                                    (r.prototype.compareTo = function (t) {
                                                        var e = this.s - t.s;
                                                        if (0 != e) return e;
                                                        var r = this.t;
                                                        if (0 != (e = r - t.t)) return this.s < 0 ? -e : e;
                                                        for (; --r >= 0; ) if (0 != (e = this[r] - t[r])) return e;
                                                        return 0;
                                                    }),
                                                    (r.prototype.bitLength = function () {
                                                        return this.t <= 0 ? 0 : this.DB * (this.t - 1) + L(this[this.t - 1] ^ (this.s & this.DM));
                                                    }),
                                                    (r.prototype.mod = function (t) {
                                                        var e = V();
                                                        return this.abs().divRemTo(t, null, e), this.s < 0 && e.compareTo(r.ZERO) > 0 && t.subTo(e, e), e;
                                                    }),
                                                    (r.prototype.modPowInt = function (t, e) {
                                                        var r;
                                                        return (r = t < 256 || e.isEven() ? new O(e) : new A(e)), this.exp(t, r);
                                                    }),
                                                    (r.prototype.clone = function () {
                                                        var t = V();
                                                        return this.copyTo(t), t;
                                                    }),
                                                    (r.prototype.intValue = function () {
                                                        if (this.s < 0) {
                                                            if (1 == this.t) return this[0] - this.DV;
                                                            if (0 == this.t) return -1;
                                                        } else {
                                                            if (1 == this.t) return this[0];
                                                            if (0 == this.t) return 0;
                                                        }
                                                        return ((this[1] & ((1 << (32 - this.DB)) - 1)) << this.DB) | this[0];
                                                    }),
                                                    (r.prototype.byteValue = function () {
                                                        return 0 == this.t ? this.s : (this[0] << 24) >> 24;
                                                    }),
                                                    (r.prototype.shortValue = function () {
                                                        return 0 == this.t ? this.s : (this[0] << 16) >> 16;
                                                    }),
                                                    (r.prototype.signum = function () {
                                                        return this.s < 0 ? -1 : this.t <= 0 || (1 == this.t && this[0] <= 0) ? 0 : 1;
                                                    }),
                                                    (r.prototype.toByteArray = function () {
                                                        var t = this.t,
                                                            e = [];
                                                        e[0] = this.s;
                                                        var r,
                                                            i = this.DB - ((t * this.DB) % 8),
                                                            n = 0;
                                                        if (t-- > 0)
                                                            for (i < this.DB && (r = this[t] >> i) != (this.s & this.DM) >> i && (e[n++] = r | (this.s << (this.DB - i))); t >= 0; )
                                                                i < 8 ? ((r = (this[t] & ((1 << i) - 1)) << (8 - i)), (r |= this[--t] >> (i += this.DB - 8))) : ((r = (this[t] >> (i -= 8)) & 255), i <= 0 && ((i += this.DB), --t)),
                                                                0 != (128 & r) && (r |= -256),
                                                                0 == n && (128 & this.s) != (128 & r) && ++n,
                                                                (n > 0 || r != this.s) && (e[n++] = r);
                                                        return e;
                                                    }),
                                                    (r.prototype.equals = function (t) {
                                                        return 0 == this.compareTo(t);
                                                    }),
                                                    (r.prototype.min = function (t) {
                                                        return this.compareTo(t) < 0 ? this : t;
                                                    }),
                                                    (r.prototype.max = function (t) {
                                                        return this.compareTo(t) > 0 ? this : t;
                                                    }),
                                                    (r.prototype.and = function (t) {
                                                        var r = V();
                                                        return this.bitwiseTo(t, e, r), r;
                                                    }),
                                                    (r.prototype.or = function (t) {
                                                        var e = V();
                                                        return this.bitwiseTo(t, n, e), e;
                                                    }),
                                                    (r.prototype.xor = function (t) {
                                                        var e = V();
                                                        return this.bitwiseTo(t, s, e), e;
                                                    }),
                                                    (r.prototype.andNot = function (t) {
                                                        var e = V();
                                                        return this.bitwiseTo(t, o, e), e;
                                                    }),
                                                    (r.prototype.not = function () {
                                                        for (var t = V(), e = 0; e < this.t; ++e) t[e] = this.DM & ~this[e];
                                                        return (t.t = this.t), (t.s = ~this.s), t;
                                                    }),
                                                    (r.prototype.shiftLeft = function (t) {
                                                        var e = V();
                                                        return t < 0 ? this.rShiftTo(-t, e) : this.lShiftTo(t, e), e;
                                                    }),
                                                    (r.prototype.shiftRight = function (t) {
                                                        var e = V();
                                                        return t < 0 ? this.lShiftTo(-t, e) : this.rShiftTo(t, e), e;
                                                    }),
                                                    (r.prototype.getLowestSetBit = function () {
                                                        for (var t = 0; t < this.t; ++t) if (0 != this[t]) return t * this.DB + a(this[t]);
                                                        return this.s < 0 ? this.t * this.DB : -1;
                                                    }),
                                                    (r.prototype.bitCount = function () {
                                                        for (var t = 0, e = this.s & this.DM, r = 0; r < this.t; ++r) t += h(this[r] ^ e);
                                                        return t;
                                                    }),
                                                    (r.prototype.testBit = function (t) {
                                                        var e = Math.floor(t / this.DB);
                                                        return e >= this.t ? 0 != this.s : 0 != (this[e] & (1 << t % this.DB));
                                                    }),
                                                    (r.prototype.setBit = function (t) {
                                                        return this.changeBit(t, n);
                                                    }),
                                                    (r.prototype.clearBit = function (t) {
                                                        return this.changeBit(t, o);
                                                    }),
                                                    (r.prototype.flipBit = function (t) {
                                                        return this.changeBit(t, s);
                                                    }),
                                                    (r.prototype.add = function (t) {
                                                        var e = V();
                                                        return this.addTo(t, e), e;
                                                    }),
                                                    (r.prototype.subtract = function (t) {
                                                        var e = V();
                                                        return this.subTo(t, e), e;
                                                    }),
                                                    (r.prototype.multiply = function (t) {
                                                        var e = V();
                                                        return this.multiplyTo(t, e), e;
                                                    }),
                                                    (r.prototype.divide = function (t) {
                                                        var e = V();
                                                        return this.divRemTo(t, e, null), e;
                                                    }),
                                                    (r.prototype.remainder = function (t) {
                                                        var e = V();
                                                        return this.divRemTo(t, null, e), e;
                                                    }),
                                                    (r.prototype.divideAndRemainder = function (t) {
                                                        var e = V(),
                                                            r = V();
                                                        return this.divRemTo(t, e, r), [e, r];
                                                    }),
                                                    (r.prototype.modPow = function (t, e) {
                                                        var r,
                                                            i,
                                                            n = t.bitLength(),
                                                            s = q(1);
                                                        if (n <= 0) return s;
                                                        (r = n < 18 ? 1 : n < 48 ? 3 : n < 144 ? 4 : n < 768 ? 5 : 6), (i = n < 8 ? new O(e) : e.isEven() ? new I(e) : new A(e));
                                                        var o = [],
                                                            a = 3,
                                                            h = r - 1,
                                                            c = (1 << r) - 1;
                                                        if (((o[1] = i.convert(this)), r > 1)) {
                                                            var u = V();
                                                            for (i.sqrTo(o[1], u); a <= c; ) (o[a] = V()), i.mulTo(u, o[a - 2], o[a]), (a += 2);
                                                        }
                                                        var l,
                                                            f,
                                                            d = t.t - 1,
                                                            p = !0,
                                                            g = V();
                                                        for (n = L(t[d]) - 1; d >= 0; ) {
                                                            for (n >= h ? (l = (t[d] >> (n - h)) & c) : ((l = (t[d] & ((1 << (n + 1)) - 1)) << (h - n)), d > 0 && (l |= t[d - 1] >> (this.DB + n - h))), a = r; 0 == (1 & l); ) (l >>= 1), --a;
                                                            if (((n -= a) < 0 && ((n += this.DB), --d), p)) o[l].copyTo(s), (p = !1);
                                                            else {
                                                                for (; a > 1; ) i.sqrTo(s, g), i.sqrTo(g, s), (a -= 2);
                                                                a > 0 ? i.sqrTo(s, g) : ((f = s), (s = g), (g = f)), i.mulTo(g, o[l], s);
                                                            }
                                                            for (; d >= 0 && 0 == (t[d] & (1 << n)); ) i.sqrTo(s, g), (f = s), (s = g), (g = f), --n < 0 && ((n = this.DB - 1), --d);
                                                        }
                                                        return i.revert(s);
                                                    }),
                                                    (r.prototype.modInverse = function (t) {
                                                        var e = t.isEven();
                                                        if ((this.isEven() && e) || 0 == t.signum()) return r.ZERO;
                                                        for (var i = t.clone(), n = this.clone(), s = q(1), o = q(0), a = q(0), h = q(1); 0 != i.signum(); ) {
                                                            for (; i.isEven(); ) i.rShiftTo(1, i), e ? ((s.isEven() && o.isEven()) || (s.addTo(this, s), o.subTo(t, o)), s.rShiftTo(1, s)) : o.isEven() || o.subTo(t, o), o.rShiftTo(1, o);
                                                            for (; n.isEven(); ) n.rShiftTo(1, n), e ? ((a.isEven() && h.isEven()) || (a.addTo(this, a), h.subTo(t, h)), a.rShiftTo(1, a)) : h.isEven() || h.subTo(t, h), h.rShiftTo(1, h);
                                                            i.compareTo(n) >= 0 ? (i.subTo(n, i), e && s.subTo(a, s), o.subTo(h, o)) : (n.subTo(i, n), e && a.subTo(s, a), h.subTo(o, h));
                                                        }
                                                        return 0 != n.compareTo(r.ONE) ? r.ZERO : h.compareTo(t) >= 0 ? h.subtract(t) : h.signum() < 0 ? (h.addTo(t, h), h.signum() < 0 ? h.add(t) : h) : h;
                                                    }),
                                                    (r.prototype.pow = function (t) {
                                                        return this.exp(t, new B());
                                                    }),
                                                    (r.prototype.gcd = function (t) {
                                                        var e = this.s < 0 ? this.negate() : this.clone(),
                                                            r = t.s < 0 ? t.negate() : t.clone();
                                                        if (e.compareTo(r) < 0) {
                                                            var i = e;
                                                            (e = r), (r = i);
                                                        }
                                                        var n = e.getLowestSetBit(),
                                                            s = r.getLowestSetBit();
                                                        if (s < 0) return e;
                                                        for (n < s && (s = n), s > 0 && (e.rShiftTo(s, e), r.rShiftTo(s, r)); e.signum() > 0; )
                                                            (n = e.getLowestSetBit()) > 0 && e.rShiftTo(n, e),
                                                            (n = r.getLowestSetBit()) > 0 && r.rShiftTo(n, r),
                                                                e.compareTo(r) >= 0 ? (e.subTo(r, e), e.rShiftTo(1, e)) : (r.subTo(e, r), r.rShiftTo(1, r));
                                                        return s > 0 && r.lShiftTo(s, r), r;
                                                    }),
                                                    (r.prototype.isProbablePrime = function (t) {
                                                        var e,
                                                            r = this.abs();
                                                        if (1 == r.t && r[0] <= D[D.length - 1]) {
                                                            for (e = 0; e < D.length; ++e) if (r[0] == D[e]) return !0;
                                                            return !1;
                                                        }
                                                        if (r.isEven()) return !1;
                                                        for (e = 1; e < D.length; ) {
                                                            for (var i = D[e], n = e + 1; n < D.length && i < x; ) i *= D[n++];
                                                            for (i = r.modInt(i); e < n; ) if (i % D[e++] == 0) return !1;
                                                        }
                                                        return r.millerRabin(t);
                                                    }),
                                                    (r.prototype.copyTo = function (t) {
                                                        for (var e = this.t - 1; e >= 0; --e) t[e] = this[e];
                                                        (t.t = this.t), (t.s = this.s);
                                                    }),
                                                    (r.prototype.fromInt = function (t) {
                                                        (this.t = 1), (this.s = t < 0 ? -1 : 0), t > 0 ? (this[0] = t) : t < -1 ? (this[0] = t + this.DV) : (this.t = 0);
                                                    }),
                                                    (r.prototype.fromString = function (t, e) {
                                                        var i;
                                                        if (16 == e) i = 4;
                                                        else if (8 == e) i = 3;
                                                        else if (256 == e) i = 8;
                                                        else if (2 == e) i = 1;
                                                        else if (32 == e) i = 5;
                                                        else {
                                                            if (4 != e) return void this.fromRadix(t, e);
                                                            i = 2;
                                                        }
                                                        (this.t = 0), (this.s = 0);
                                                        for (var n = t.length, s = !1, o = 0; --n >= 0; ) {
                                                            var a = 8 == i ? 255 & +t[n] : M(t, n);
                                                            a < 0
                                                                ? "-" == t.charAt(n) && (s = !0)
                                                                : ((s = !1),
                                                                    0 == o
                                                                        ? (this[this.t++] = a)
                                                                        : o + i > this.DB
                                                                            ? ((this[this.t - 1] |= (a & ((1 << (this.DB - o)) - 1)) << o), (this[this.t++] = a >> (this.DB - o)))
                                                                            : (this[this.t - 1] |= a << o),
                                                                (o += i) >= this.DB && (o -= this.DB));
                                                        }
                                                        8 == i && 0 != (128 & +t[0]) && ((this.s = -1), o > 0 && (this[this.t - 1] |= ((1 << (this.DB - o)) - 1) << o)), this.clamp(), s && r.ZERO.subTo(this, this);
                                                    }),
                                                    (r.prototype.clamp = function () {
                                                        for (var t = this.s & this.DM; this.t > 0 && this[this.t - 1] == t; ) --this.t;
                                                    }),
                                                    (r.prototype.dlShiftTo = function (t, e) {
                                                        var r;
                                                        for (r = this.t - 1; r >= 0; --r) e[r + t] = this[r];
                                                        for (r = t - 1; r >= 0; --r) e[r] = 0;
                                                        (e.t = this.t + t), (e.s = this.s);
                                                    }),
                                                    (r.prototype.drShiftTo = function (t, e) {
                                                        for (var r = t; r < this.t; ++r) e[r - t] = this[r];
                                                        (e.t = Math.max(this.t - t, 0)), (e.s = this.s);
                                                    }),
                                                    (r.prototype.lShiftTo = function (t, e) {
                                                        for (var r = t % this.DB, i = this.DB - r, n = (1 << i) - 1, s = Math.floor(t / this.DB), o = (this.s << r) & this.DM, a = this.t - 1; a >= 0; --a)
                                                            (e[a + s + 1] = (this[a] >> i) | o), (o = (this[a] & n) << r);
                                                        for (a = s - 1; a >= 0; --a) e[a] = 0;
                                                        (e[s] = o), (e.t = this.t + s + 1), (e.s = this.s), e.clamp();
                                                    }),
                                                    (r.prototype.rShiftTo = function (t, e) {
                                                        e.s = this.s;
                                                        var r = Math.floor(t / this.DB);
                                                        if (r >= this.t) e.t = 0;
                                                        else {
                                                            var i = t % this.DB,
                                                                n = this.DB - i,
                                                                s = (1 << i) - 1;
                                                            e[0] = this[r] >> i;
                                                            for (var o = r + 1; o < this.t; ++o) (e[o - r - 1] |= (this[o] & s) << n), (e[o - r] = this[o] >> i);
                                                            i > 0 && (e[this.t - r - 1] |= (this.s & s) << n), (e.t = this.t - r), e.clamp();
                                                        }
                                                    }),
                                                    (r.prototype.subTo = function (t, e) {
                                                        for (var r = 0, i = 0, n = Math.min(t.t, this.t); r < n; ) (i += this[r] - t[r]), (e[r++] = i & this.DM), (i >>= this.DB);
                                                        if (t.t < this.t) {
                                                            for (i -= t.s; r < this.t; ) (i += this[r]), (e[r++] = i & this.DM), (i >>= this.DB);
                                                            i += this.s;
                                                        } else {
                                                            for (i += this.s; r < t.t; ) (i -= t[r]), (e[r++] = i & this.DM), (i >>= this.DB);
                                                            i -= t.s;
                                                        }
                                                        (e.s = i < 0 ? -1 : 0), i < -1 ? (e[r++] = this.DV + i) : i > 0 && (e[r++] = i), (e.t = r), e.clamp();
                                                    }),
                                                    (r.prototype.multiplyTo = function (t, e) {
                                                        var i = this.abs(),
                                                            n = t.abs(),
                                                            s = i.t;
                                                        for (e.t = s + n.t; --s >= 0; ) e[s] = 0;
                                                        for (s = 0; s < n.t; ++s) e[s + i.t] = i.am(0, n[s], e, s, 0, i.t);
                                                        (e.s = 0), e.clamp(), this.s != t.s && r.ZERO.subTo(e, e);
                                                    }),
                                                    (r.prototype.squareTo = function (t) {
                                                        for (var e = this.abs(), r = (t.t = 2 * e.t); --r >= 0; ) t[r] = 0;
                                                        for (r = 0; r < e.t - 1; ++r) {
                                                            var i = e.am(r, e[r], t, 2 * r, 0, 1);
                                                            (t[r + e.t] += e.am(r + 1, 2 * e[r], t, 2 * r + 1, i, e.t - r - 1)) >= e.DV && ((t[r + e.t] -= e.DV), (t[r + e.t + 1] = 1));
                                                        }
                                                        t.t > 0 && (t[t.t - 1] += e.am(r, e[r], t, 2 * r, 0, 1)), (t.s = 0), t.clamp();
                                                    }),
                                                    (r.prototype.divRemTo = function (t, e, i) {
                                                        var n = t.abs();
                                                        if (!(n.t <= 0)) {
                                                            var s = this.abs();
                                                            if (s.t < n.t) return null != e && e.fromInt(0), void (null != i && this.copyTo(i));
                                                            null == i && (i = V());
                                                            var o = V(),
                                                                a = this.s,
                                                                h = t.s,
                                                                c = this.DB - L(n[n.t - 1]);
                                                            c > 0 ? (n.lShiftTo(c, o), s.lShiftTo(c, i)) : (n.copyTo(o), s.copyTo(i));
                                                            var u = o.t,
                                                                l = o[u - 1];
                                                            if (0 != l) {
                                                                var f = l * (1 << this.F1) + (u > 1 ? o[u - 2] >> this.F2 : 0),
                                                                    d = this.FV / f,
                                                                    p = (1 << this.F1) / f,
                                                                    g = 1 << this.F2,
                                                                    m = i.t,
                                                                    v = m - u,
                                                                    y = null == e ? V() : e;
                                                                for (o.dlShiftTo(v, y), i.compareTo(y) >= 0 && ((i[i.t++] = 1), i.subTo(y, i)), r.ONE.dlShiftTo(u, y), y.subTo(o, o); o.t < u; ) o[o.t++] = 0;
                                                                for (; --v >= 0; ) {
                                                                    var b = i[--m] == l ? this.DM : Math.floor(i[m] * d + (i[m - 1] + g) * p);
                                                                    if ((i[m] += o.am(0, b, i, v, 0, u)) < b) for (o.dlShiftTo(v, y), i.subTo(y, i); i[m] < --b; ) i.subTo(y, i);
                                                                }
                                                                null != e && (i.drShiftTo(u, e), a != h && r.ZERO.subTo(e, e)), (i.t = u), i.clamp(), c > 0 && i.rShiftTo(c, i), a < 0 && r.ZERO.subTo(i, i);
                                                            }
                                                        }
                                                    }),
                                                    (r.prototype.invDigit = function () {
                                                        if (this.t < 1) return 0;
                                                        var t = this[0];
                                                        if (0 == (1 & t)) return 0;
                                                        var e = 3 & t;
                                                        return (e = ((e = ((e = ((e = (e * (2 - (15 & t) * e)) & 15) * (2 - (255 & t) * e)) & 255) * (2 - (((65535 & t) * e) & 65535))) & 65535) * (2 - ((t * e) % this.DV))) % this.DV) > 0
                                                            ? this.DV - e
                                                            : -e;
                                                    }),
                                                    (r.prototype.isEven = function () {
                                                        return 0 == (this.t > 0 ? 1 & this[0] : this.s);
                                                    }),
                                                    (r.prototype.exp = function (t, e) {
                                                        if (t > 4294967295 || t < 1) return r.ONE;
                                                        var i = V(),
                                                            n = V(),
                                                            s = e.convert(this),
                                                            o = L(t) - 1;
                                                        for (s.copyTo(i); --o >= 0; )
                                                            if ((e.sqrTo(i, n), (t & (1 << o)) > 0)) e.mulTo(n, s, i);
                                                            else {
                                                                var a = i;
                                                                (i = n), (n = a);
                                                            }
                                                        return e.revert(i);
                                                    }),
                                                    (r.prototype.chunkSize = function (t) {
                                                        return Math.floor((Math.LN2 * this.DB) / Math.log(t));
                                                    }),
                                                    (r.prototype.toRadix = function (t) {
                                                        if ((null == t && (t = 10), 0 == this.signum() || t < 2 || t > 36)) return "0";
                                                        var e = this.chunkSize(t),
                                                            r = Math.pow(t, e),
                                                            i = q(r),
                                                            n = V(),
                                                            s = V(),
                                                            o = "";
                                                        for (this.divRemTo(i, n, s); n.signum() > 0; ) (o = (r + s.intValue()).toString(t).substr(1) + o), n.divRemTo(i, n, s);
                                                        return s.intValue().toString(t) + o;
                                                    }),
                                                    (r.prototype.fromRadix = function (t, e) {
                                                        this.fromInt(0), null == e && (e = 10);
                                                        for (var i = this.chunkSize(e), n = Math.pow(e, i), s = !1, o = 0, a = 0, h = 0; h < t.length; ++h) {
                                                            var c = M(t, h);
                                                            c < 0 ? "-" == t.charAt(h) && 0 == this.signum() && (s = !0) : ((a = e * a + c), ++o >= i && (this.dMultiply(n), this.dAddOffset(a, 0), (o = 0), (a = 0)));
                                                        }
                                                        o > 0 && (this.dMultiply(Math.pow(e, o)), this.dAddOffset(a, 0)), s && r.ZERO.subTo(this, this);
                                                    }),
                                                    (r.prototype.fromNumber = function (t, e, i) {
                                                        if ("number" == typeof e)
                                                            if (t < 2) this.fromInt(1);
                                                            else
                                                                for (this.fromNumber(t, i), this.testBit(t - 1) || this.bitwiseTo(r.ONE.shiftLeft(t - 1), n, this), this.isEven() && this.dAddOffset(1, 0); !this.isProbablePrime(e); )
                                                                    this.dAddOffset(2, 0), this.bitLength() > t && this.subTo(r.ONE.shiftLeft(t - 1), this);
                                                        else {
                                                            var s = [],
                                                                o = 7 & t;
                                                            (s.length = 1 + (t >> 3)), e.nextBytes(s), o > 0 ? (s[0] &= (1 << o) - 1) : (s[0] = 0), this.fromString(s, 256);
                                                        }
                                                    }),
                                                    (r.prototype.bitwiseTo = function (t, e, r) {
                                                        var i,
                                                            n,
                                                            s = Math.min(t.t, this.t);
                                                        for (i = 0; i < s; ++i) r[i] = e(this[i], t[i]);
                                                        if (t.t < this.t) {
                                                            for (n = t.s & this.DM, i = s; i < this.t; ++i) r[i] = e(this[i], n);
                                                            r.t = this.t;
                                                        } else {
                                                            for (n = this.s & this.DM, i = s; i < t.t; ++i) r[i] = e(n, t[i]);
                                                            r.t = t.t;
                                                        }
                                                        (r.s = e(this.s, t.s)), r.clamp();
                                                    }),
                                                    (r.prototype.changeBit = function (t, e) {
                                                        var i = r.ONE.shiftLeft(t);
                                                        return this.bitwiseTo(i, e, i), i;
                                                    }),
                                                    (r.prototype.addTo = function (t, e) {
                                                        for (var r = 0, i = 0, n = Math.min(t.t, this.t); r < n; ) (i += this[r] + t[r]), (e[r++] = i & this.DM), (i >>= this.DB);
                                                        if (t.t < this.t) {
                                                            for (i += t.s; r < this.t; ) (i += this[r]), (e[r++] = i & this.DM), (i >>= this.DB);
                                                            i += this.s;
                                                        } else {
                                                            for (i += this.s; r < t.t; ) (i += t[r]), (e[r++] = i & this.DM), (i >>= this.DB);
                                                            i += t.s;
                                                        }
                                                        (e.s = i < 0 ? -1 : 0), i > 0 ? (e[r++] = i) : i < -1 && (e[r++] = this.DV + i), (e.t = r), e.clamp();
                                                    }),
                                                    (r.prototype.dMultiply = function (t) {
                                                        (this[this.t] = this.am(0, t - 1, this, 0, 0, this.t)), ++this.t, this.clamp();
                                                    }),
                                                    (r.prototype.dAddOffset = function (t, e) {
                                                        if (0 != t) {
                                                            for (; this.t <= e; ) this[this.t++] = 0;
                                                            for (this[e] += t; this[e] >= this.DV; ) (this[e] -= this.DV), ++e >= this.t && (this[this.t++] = 0), ++this[e];
                                                        }
                                                    }),
                                                    (r.prototype.multiplyLowerTo = function (t, e, r) {
                                                        var i = Math.min(this.t + t.t, e);
                                                        for (r.s = 0, r.t = i; i > 0; ) r[--i] = 0;
                                                        for (var n = r.t - this.t; i < n; ++i) r[i + this.t] = this.am(0, t[i], r, i, 0, this.t);
                                                        for (n = Math.min(t.t, e); i < n; ++i) this.am(0, t[i], r, i, 0, e - i);
                                                        r.clamp();
                                                    }),
                                                    (r.prototype.multiplyUpperTo = function (t, e, r) {
                                                        --e;
                                                        var i = (r.t = this.t + t.t - e);
                                                        for (r.s = 0; --i >= 0; ) r[i] = 0;
                                                        for (i = Math.max(e - this.t, 0); i < t.t; ++i) r[this.t + i - e] = this.am(e - i, t[i], r, 0, 0, this.t + i - e);
                                                        r.clamp(), r.drShiftTo(1, r);
                                                    }),
                                                    (r.prototype.modInt = function (t) {
                                                        if (t <= 0) return 0;
                                                        var e = this.DV % t,
                                                            r = this.s < 0 ? t - 1 : 0;
                                                        if (this.t > 0)
                                                            if (0 == e) r = this[0] % t;
                                                            else for (var i = this.t - 1; i >= 0; --i) r = (e * r + this[i]) % t;
                                                        return r;
                                                    }),
                                                    (r.prototype.millerRabin = function (t) {
                                                        var e = this.subtract(r.ONE),
                                                            i = e.getLowestSetBit();
                                                        if (i <= 0) return !1;
                                                        var n = e.shiftRight(i);
                                                        (t = (t + 1) >> 1) > D.length && (t = D.length);
                                                        for (var s = V(), o = 0; o < t; ++o) {
                                                            s.fromInt(D[Math.floor(Math.random() * D.length)]);
                                                            var a = s.modPow(n, this);
                                                            if (0 != a.compareTo(r.ONE) && 0 != a.compareTo(e)) {
                                                                for (var h = 1; h++ < i && 0 != a.compareTo(e); ) if (0 == (a = a.modPowInt(2, this)).compareTo(r.ONE)) return !1;
                                                                if (0 != a.compareTo(e)) return !1;
                                                            }
                                                        }
                                                        return !0;
                                                    }),
                                                    (r.prototype.square = function () {
                                                        var t = V();
                                                        return this.squareTo(t), t;
                                                    }),
                                                    (r.prototype.gcda = function (t, e) {
                                                        var r = this.s < 0 ? this.negate() : this.clone(),
                                                            i = t.s < 0 ? t.negate() : t.clone();
                                                        if (r.compareTo(i) < 0) {
                                                            var n = r;
                                                            (r = i), (i = n);
                                                        }
                                                        var s = r.getLowestSetBit(),
                                                            o = i.getLowestSetBit();
                                                        if (o < 0) e(r);
                                                        else {
                                                            s < o && (o = s), o > 0 && (r.rShiftTo(o, r), i.rShiftTo(o, i));
                                                            var a = function () {
                                                                (s = r.getLowestSetBit()) > 0 && r.rShiftTo(s, r),
                                                                (s = i.getLowestSetBit()) > 0 && i.rShiftTo(s, i),
                                                                    r.compareTo(i) >= 0 ? (r.subTo(i, r), r.rShiftTo(1, r)) : (i.subTo(r, i), i.rShiftTo(1, i)),
                                                                    r.signum() > 0
                                                                        ? setTimeout(a, 0)
                                                                        : (o > 0 && i.lShiftTo(o, i),
                                                                            setTimeout(function () {
                                                                                e(i);
                                                                            }, 0));
                                                            };
                                                            setTimeout(a, 10);
                                                        }
                                                    }),
                                                    (r.prototype.fromNumberAsync = function (t, e, i, s) {
                                                        if ("number" == typeof e)
                                                            if (t < 2) this.fromInt(1);
                                                            else {
                                                                this.fromNumber(t, i), this.testBit(t - 1) || this.bitwiseTo(r.ONE.shiftLeft(t - 1), n, this), this.isEven() && this.dAddOffset(1, 0);
                                                                var o = this,
                                                                    a = function () {
                                                                        o.dAddOffset(2, 0),
                                                                        o.bitLength() > t && o.subTo(r.ONE.shiftLeft(t - 1), o),
                                                                            o.isProbablePrime(e)
                                                                                ? setTimeout(function () {
                                                                                    s();
                                                                                }, 0)
                                                                                : setTimeout(a, 0);
                                                                    };
                                                                setTimeout(a, 0);
                                                            }
                                                        else {
                                                            var h = [],
                                                                c = 7 & t;
                                                            (h.length = 1 + (t >> 3)), e.nextBytes(h), c > 0 ? (h[0] &= (1 << c) - 1) : (h[0] = 0), this.fromString(h, 256);
                                                        }
                                                    }),
                                                    r
                                            );
                                        })(),
                                        B = (function () {
                                            function t() {}
                                            return (
                                                (t.prototype.convert = function (t) {
                                                    return t;
                                                }),
                                                    (t.prototype.revert = function (t) {
                                                        return t;
                                                    }),
                                                    (t.prototype.mulTo = function (t, e, r) {
                                                        t.multiplyTo(e, r);
                                                    }),
                                                    (t.prototype.sqrTo = function (t, e) {
                                                        t.squareTo(e);
                                                    }),
                                                    t
                                            );
                                        })(),
                                        O = (function () {
                                            function t(t) {
                                                this.m = t;
                                            }
                                            return (
                                                (t.prototype.convert = function (t) {
                                                    return t.s < 0 || t.compareTo(this.m) >= 0 ? t.mod(this.m) : t;
                                                }),
                                                    (t.prototype.revert = function (t) {
                                                        return t;
                                                    }),
                                                    (t.prototype.reduce = function (t) {
                                                        t.divRemTo(this.m, null, t);
                                                    }),
                                                    (t.prototype.mulTo = function (t, e, r) {
                                                        t.multiplyTo(e, r), this.reduce(r);
                                                    }),
                                                    (t.prototype.sqrTo = function (t, e) {
                                                        t.squareTo(e), this.reduce(e);
                                                    }),
                                                    t
                                            );
                                        })(),
                                        A = (function () {
                                            function t(t) {
                                                (this.m = t), (this.mp = t.invDigit()), (this.mpl = 32767 & this.mp), (this.mph = this.mp >> 15), (this.um = (1 << (t.DB - 15)) - 1), (this.mt2 = 2 * t.t);
                                            }
                                            return (
                                                (t.prototype.convert = function (t) {
                                                    var e = V();
                                                    return t.abs().dlShiftTo(this.m.t, e), e.divRemTo(this.m, null, e), t.s < 0 && e.compareTo(R.ZERO) > 0 && this.m.subTo(e, e), e;
                                                }),
                                                    (t.prototype.revert = function (t) {
                                                        var e = V();
                                                        return t.copyTo(e), this.reduce(e), e;
                                                    }),
                                                    (t.prototype.reduce = function (t) {
                                                        for (; t.t <= this.mt2; ) t[t.t++] = 0;
                                                        for (var e = 0; e < this.m.t; ++e) {
                                                            var r = 32767 & t[e],
                                                                i = (r * this.mpl + (((r * this.mph + (t[e] >> 15) * this.mpl) & this.um) << 15)) & t.DM;
                                                            for (t[(r = e + this.m.t)] += this.m.am(0, i, t, e, 0, this.m.t); t[r] >= t.DV; ) (t[r] -= t.DV), t[++r]++;
                                                        }
                                                        t.clamp(), t.drShiftTo(this.m.t, t), t.compareTo(this.m) >= 0 && t.subTo(this.m, t);
                                                    }),
                                                    (t.prototype.mulTo = function (t, e, r) {
                                                        t.multiplyTo(e, r), this.reduce(r);
                                                    }),
                                                    (t.prototype.sqrTo = function (t, e) {
                                                        t.squareTo(e), this.reduce(e);
                                                    }),
                                                    t
                                            );
                                        })(),
                                        I = (function () {
                                            function t(t) {
                                                (this.m = t), (this.r2 = V()), (this.q3 = V()), R.ONE.dlShiftTo(2 * t.t, this.r2), (this.mu = this.r2.divide(t));
                                            }
                                            return (
                                                (t.prototype.convert = function (t) {
                                                    if (t.s < 0 || t.t > 2 * this.m.t) return t.mod(this.m);
                                                    if (t.compareTo(this.m) < 0) return t;
                                                    var e = V();
                                                    return t.copyTo(e), this.reduce(e), e;
                                                }),
                                                    (t.prototype.revert = function (t) {
                                                        return t;
                                                    }),
                                                    (t.prototype.reduce = function (t) {
                                                        for (
                                                            t.drShiftTo(this.m.t - 1, this.r2),
                                                            t.t > this.m.t + 1 && ((t.t = this.m.t + 1), t.clamp()),
                                                                this.mu.multiplyUpperTo(this.r2, this.m.t + 1, this.q3),
                                                                this.m.multiplyLowerTo(this.q3, this.m.t + 1, this.r2);
                                                            t.compareTo(this.r2) < 0;

                                                        )
                                                            t.dAddOffset(1, this.m.t + 1);
                                                        for (t.subTo(this.r2, t); t.compareTo(this.m) >= 0; ) t.subTo(this.m, t);
                                                    }),
                                                    (t.prototype.mulTo = function (t, e, r) {
                                                        t.multiplyTo(e, r), this.reduce(r);
                                                    }),
                                                    (t.prototype.sqrTo = function (t, e) {
                                                        t.squareTo(e), this.reduce(e);
                                                    }),
                                                    t
                                            );
                                        })();
                                    function V() {
                                        return new R(null);
                                    }
                                    function _(t, e) {
                                        return new R(t, e);
                                    }
                                    var N = "undefined" != typeof navigator;
                                    N && "Microsoft Internet Explorer" == navigator.appName
                                        ? ((R.prototype.am = function (t, e, r, i, n, s) {
                                            for (var o = 32767 & e, a = e >> 15; --s >= 0; ) {
                                                var h = 32767 & this[t],
                                                    c = this[t++] >> 15,
                                                    u = a * h + c * o;
                                                (n = ((h = o * h + ((32767 & u) << 15) + r[i] + (1073741823 & n)) >>> 30) + (u >>> 15) + a * c + (n >>> 30)), (r[i++] = 1073741823 & h);
                                            }
                                            return n;
                                        }),
                                            (w = 30))
                                        : N && "Netscape" != navigator.appName
                                            ? ((R.prototype.am = function (t, e, r, i, n, s) {
                                                for (; --s >= 0; ) {
                                                    var o = e * this[t++] + r[i] + n;
                                                    (n = Math.floor(o / 67108864)), (r[i++] = 67108863 & o);
                                                }
                                                return n;
                                            }),
                                                (w = 26))
                                            : ((R.prototype.am = function (t, e, r, i, n, s) {
                                                for (var o = 16383 & e, a = e >> 14; --s >= 0; ) {
                                                    var h = 16383 & this[t],
                                                        c = this[t++] >> 14,
                                                        u = a * h + c * o;
                                                    (n = ((h = o * h + ((16383 & u) << 14) + r[i] + n) >> 28) + (u >> 14) + a * c), (r[i++] = 268435455 & h);
                                                }
                                                return n;
                                            }),
                                                (w = 28)),
                                        (R.prototype.DB = w),
                                        (R.prototype.DM = (1 << w) - 1),
                                        (R.prototype.DV = 1 << w),
                                        (R.prototype.FV = Math.pow(2, 52)),
                                        (R.prototype.F1 = 52 - w),
                                        (R.prototype.F2 = 2 * w - 52);
                                    var P,
                                        C,
                                        j = [];
                                    for (P = "0".charCodeAt(0), C = 0; C <= 9; ++C) j[P++] = C;
                                    for (P = "a".charCodeAt(0), C = 10; C < 36; ++C) j[P++] = C;
                                    for (P = "A".charCodeAt(0), C = 10; C < 36; ++C) j[P++] = C;
                                    function M(t, e) {
                                        var r = j[t.charCodeAt(e)];
                                        return null == r ? -1 : r;
                                    }
                                    function q(t) {
                                        var e = V();
                                        return e.fromInt(t), e;
                                    }
                                    function L(t) {
                                        var e,
                                            r = 1;
                                        return (
                                            0 != (e = t >>> 16) && ((t = e), (r += 16)),
                                            0 != (e = t >> 8) && ((t = e), (r += 8)),
                                            0 != (e = t >> 4) && ((t = e), (r += 4)),
                                            0 != (e = t >> 2) && ((t = e), (r += 2)),
                                            0 != (e = t >> 1) && ((t = e), (r += 1)),
                                                r
                                        );
                                    }
                                    (R.ZERO = q(0)), (R.ONE = q(1));
                                    var H,
                                        k,
                                        $ = (function () {
                                            function t() {
                                                (this.i = 0), (this.j = 0), (this.S = []);
                                            }
                                            return (
                                                (t.prototype.init = function (t) {
                                                    var e, r, i;
                                                    for (e = 0; e < 256; ++e) this.S[e] = e;
                                                    for (r = 0, e = 0; e < 256; ++e) (r = (r + this.S[e] + t[e % t.length]) & 255), (i = this.S[e]), (this.S[e] = this.S[r]), (this.S[r] = i);
                                                    (this.i = 0), (this.j = 0);
                                                }),
                                                    (t.prototype.next = function () {
                                                        var t;
                                                        return (
                                                            (this.i = (this.i + 1) & 255),
                                                                (this.j = (this.j + this.S[this.i]) & 255),
                                                                (t = this.S[this.i]),
                                                                (this.S[this.i] = this.S[this.j]),
                                                                (this.S[this.j] = t),
                                                                this.S[(t + this.S[this.i]) & 255]
                                                        );
                                                    }),
                                                    t
                                            );
                                        })(),
                                        F = null;
                                    if (null == F) {
                                        (F = []), (k = 0);
                                        var U = void 0;
                                        if ("undefined" != typeof window && window.crypto && window.crypto.getRandomValues) {
                                            var K = new Uint32Array(256);
                                            for (window.crypto.getRandomValues(K), U = 0; U < K.length; ++U) F[k++] = 255 & K[U];
                                        }
                                        var z = 0,
                                            Z = function (t) {
                                                if ((z = z || 0) >= 256 || k >= 256) window.removeEventListener ? window.removeEventListener("mousemove", Z, !1) : window.detachEvent && window.detachEvent("onmousemove", Z);
                                                else
                                                    try {
                                                        var e = t.x + t.y;
                                                        (F[k++] = 255 & e), (z += 1);
                                                    } catch (t) {}
                                            };
                                        "undefined" != typeof window && (window.addEventListener ? window.addEventListener("mousemove", Z, !1) : window.attachEvent && window.attachEvent("onmousemove", Z));
                                    }
                                    function G() {
                                        if (null == H) {
                                            for (H = new $(); k < 256; ) {
                                                var t = Math.floor(65536 * Math.random());
                                                F[k++] = 255 & t;
                                            }
                                            for (H.init(F), k = 0; k < F.length; ++k) F[k] = 0;
                                            k = 0;
                                        }
                                        return H.next();
                                    }
                                    var Y = (function () {
                                            function t() {}
                                            return (
                                                (t.prototype.nextBytes = function (t) {
                                                    for (var e = 0; e < t.length; ++e) t[e] = G();
                                                }),
                                                    t
                                            );
                                        })(),
                                        J = (function () {
                                            function t() {
                                                (this.n = null), (this.e = 0), (this.d = null), (this.p = null), (this.q = null), (this.dmp1 = null), (this.dmq1 = null), (this.coeff = null);
                                            }
                                            return (
                                                (t.prototype.doPublic = function (t) {
                                                    return t.modPowInt(this.e, this.n);
                                                }),
                                                    (t.prototype.doPrivate = function (t) {
                                                        if (null == this.p || null == this.q) return t.modPow(this.d, this.n);
                                                        for (var e = t.mod(this.p).modPow(this.dmp1, this.p), r = t.mod(this.q).modPow(this.dmq1, this.q); e.compareTo(r) < 0; ) e = e.add(this.p);
                                                        return e.subtract(r).multiply(this.coeff).mod(this.p).multiply(this.q).add(r);
                                                    }),
                                                    (t.prototype.setPublic = function (t, e) {
                                                        null != t && null != e && t.length > 0 && e.length > 0 ? ((this.n = _(t, 16)), (this.e = parseInt(e, 16))) : console.error("Invalid RSA public key");
                                                    }),
                                                    (t.prototype.encrypt = function (t) {
                                                        var e = (this.n.bitLength() + 7) >> 3,
                                                            r = (function (t, e) {
                                                                if (e < t.length + 11) return console.error("Message too long for RSA"), null;
                                                                for (var r = [], i = t.length - 1; i >= 0 && e > 0; ) {
                                                                    var n = t.charCodeAt(i--);
                                                                    n < 128
                                                                        ? (r[--e] = n)
                                                                        : n > 127 && n < 2048
                                                                            ? ((r[--e] = (63 & n) | 128), (r[--e] = (n >> 6) | 192))
                                                                            : ((r[--e] = (63 & n) | 128), (r[--e] = ((n >> 6) & 63) | 128), (r[--e] = (n >> 12) | 224));
                                                                }
                                                                r[--e] = 0;
                                                                for (var s = new Y(), o = []; e > 2; ) {
                                                                    for (o[0] = 0; 0 == o[0]; ) s.nextBytes(o);
                                                                    r[--e] = o[0];
                                                                }
                                                                return (r[--e] = 2), (r[--e] = 0), new R(r);
                                                            })(t, e);
                                                        if (null == r) return null;
                                                        var i = this.doPublic(r);
                                                        if (null == i) return null;
                                                        for (var n = i.toString(16), s = n.length, o = 0; o < 2 * e - s; o++) n = "0" + n;
                                                        return n;
                                                    }),
                                                    (t.prototype.setPrivate = function (t, e, r) {
                                                        null != t && null != e && t.length > 0 && e.length > 0 ? ((this.n = _(t, 16)), (this.e = parseInt(e, 16)), (this.d = _(r, 16))) : console.error("Invalid RSA private key");
                                                    }),
                                                    (t.prototype.setPrivateEx = function (t, e, r, i, n, s, o, a) {
                                                        null != t && null != e && t.length > 0 && e.length > 0
                                                            ? ((this.n = _(t, 16)),
                                                                (this.e = parseInt(e, 16)),
                                                                (this.d = _(r, 16)),
                                                                (this.p = _(i, 16)),
                                                                (this.q = _(n, 16)),
                                                                (this.dmp1 = _(s, 16)),
                                                                (this.dmq1 = _(o, 16)),
                                                                (this.coeff = _(a, 16)))
                                                            : console.error("Invalid RSA private key");
                                                    }),
                                                    (t.prototype.generate = function (t, e) {
                                                        var r = new Y(),
                                                            i = t >> 1;
                                                        this.e = parseInt(e, 16);
                                                        for (var n = new R(e, 16); ; ) {
                                                            for (; (this.p = new R(t - i, 1, r)), 0 != this.p.subtract(R.ONE).gcd(n).compareTo(R.ONE) || !this.p.isProbablePrime(10); );
                                                            for (; (this.q = new R(i, 1, r)), 0 != this.q.subtract(R.ONE).gcd(n).compareTo(R.ONE) || !this.q.isProbablePrime(10); );
                                                            if (this.p.compareTo(this.q) <= 0) {
                                                                var s = this.p;
                                                                (this.p = this.q), (this.q = s);
                                                            }
                                                            var o = this.p.subtract(R.ONE),
                                                                a = this.q.subtract(R.ONE),
                                                                h = o.multiply(a);
                                                            if (0 == h.gcd(n).compareTo(R.ONE)) {
                                                                (this.n = this.p.multiply(this.q)), (this.d = n.modInverse(h)), (this.dmp1 = this.d.mod(o)), (this.dmq1 = this.d.mod(a)), (this.coeff = this.q.modInverse(this.p));
                                                                break;
                                                            }
                                                        }
                                                    }),
                                                    (t.prototype.decrypt = function (t) {
                                                        var e = _(t, 16),
                                                            r = this.doPrivate(e);
                                                        return null == r
                                                            ? null
                                                            : (function (t, e) {
                                                                for (var r = t.toByteArray(), i = 0; i < r.length && 0 == r[i]; ) ++i;
                                                                if (r.length - i != e - 1 || 2 != r[i]) return null;
                                                                for (++i; 0 != r[i]; ) if (++i >= r.length) return null;
                                                                for (var n = ""; ++i < r.length; ) {
                                                                    var s = 255 & r[i];
                                                                    s < 128
                                                                        ? (n += String.fromCharCode(s))
                                                                        : s > 191 && s < 224
                                                                            ? ((n += String.fromCharCode(((31 & s) << 6) | (63 & r[i + 1]))), ++i)
                                                                            : ((n += String.fromCharCode(((15 & s) << 12) | ((63 & r[i + 1]) << 6) | (63 & r[i + 2]))), (i += 2));
                                                                }
                                                                return n;
                                                            })(r, (this.n.bitLength() + 7) >> 3);
                                                    }),
                                                    (t.prototype.generateAsync = function (t, e, r) {
                                                        var i = new Y(),
                                                            n = t >> 1;
                                                        this.e = parseInt(e, 16);
                                                        var s = new R(e, 16),
                                                            o = this,
                                                            a = function () {
                                                                var e = function () {
                                                                        if (o.p.compareTo(o.q) <= 0) {
                                                                            var t = o.p;
                                                                            (o.p = o.q), (o.q = t);
                                                                        }
                                                                        var e = o.p.subtract(R.ONE),
                                                                            i = o.q.subtract(R.ONE),
                                                                            n = e.multiply(i);
                                                                        0 == n.gcd(s).compareTo(R.ONE)
                                                                            ? ((o.n = o.p.multiply(o.q)),
                                                                                (o.d = s.modInverse(n)),
                                                                                (o.dmp1 = o.d.mod(e)),
                                                                                (o.dmq1 = o.d.mod(i)),
                                                                                (o.coeff = o.q.modInverse(o.p)),
                                                                                setTimeout(function () {
                                                                                    r();
                                                                                }, 0))
                                                                            : setTimeout(a, 0);
                                                                    },
                                                                    h = function () {
                                                                        (o.q = V()),
                                                                            o.q.fromNumberAsync(n, 1, i, function () {
                                                                                o.q.subtract(R.ONE).gcda(s, function (t) {
                                                                                    0 == t.compareTo(R.ONE) && o.q.isProbablePrime(10) ? setTimeout(e, 0) : setTimeout(h, 0);
                                                                                });
                                                                            });
                                                                    },
                                                                    c = function () {
                                                                        (o.p = V()),
                                                                            o.p.fromNumberAsync(t - n, 1, i, function () {
                                                                                o.p.subtract(R.ONE).gcda(s, function (t) {
                                                                                    0 == t.compareTo(R.ONE) && o.p.isProbablePrime(10) ? setTimeout(h, 0) : setTimeout(c, 0);
                                                                                });
                                                                            });
                                                                    };
                                                                setTimeout(c, 0);
                                                            };
                                                        setTimeout(a, 0);
                                                    }),
                                                    (t.prototype.sign = function (t, e, r) {
                                                        var i = (function (t, e) {
                                                            if (e < t.length + 22) return console.error("Message too long for RSA"), null;
                                                            for (var r = e - t.length - 6, i = "", n = 0; n < r; n += 2) i += "ff";
                                                            return _("0001" + i + "00" + t, 16);
                                                        })((W[r] || "") + e(t).toString(), this.n.bitLength() / 4);
                                                        if (null == i) return null;
                                                        var n = this.doPrivate(i);
                                                        if (null == n) return null;
                                                        var s = n.toString(16);
                                                        return 0 == (1 & s.length) ? s : "0" + s;
                                                    }),
                                                    (t.prototype.verify = function (t, e, r) {
                                                        var i = _(e, 16),
                                                            n = this.doPublic(i);
                                                        return null == n
                                                            ? null
                                                            : (function (t) {
                                                            for (var e in W)
                                                                if (W.hasOwnProperty(e)) {
                                                                    var r = W[e],
                                                                        i = r.length;
                                                                    if (t.substr(0, i) == r) return t.substr(i);
                                                                }
                                                            return t;
                                                        })(n.toString(16).replace(/^1f+00/, "")) == r(t).toString();
                                                    }),
                                                    t
                                            );
                                        })(),
                                        W = {
                                            md2: "3020300c06082a864886f70d020205000410",
                                            md5: "3020300c06082a864886f70d020505000410",
                                            sha1: "3021300906052b0e03021a05000414",
                                            sha224: "302d300d06096086480165030402040500041c",
                                            sha256: "3031300d060960864801650304020105000420",
                                            sha384: "3041300d060960864801650304020205000430",
                                            sha512: "3051300d060960864801650304020305000440",
                                            ripemd160: "3021300906052b2403020105000414",
                                        },
                                        X = {};
                                    X.lang = {
                                        extend: function (t, e, r) {
                                            if (!e || !t) throw new Error("YAHOO.lang.extend failed, please check that all dependencies are included.");
                                            var i = function () {};
                                            if (
                                                ((i.prototype = e.prototype),
                                                    (t.prototype = new i()),
                                                    (t.prototype.constructor = t),
                                                    (t.superclass = e.prototype),
                                                e.prototype.constructor == Object.prototype.constructor && (e.prototype.constructor = e),
                                                    r)
                                            ) {
                                                var n;
                                                for (n in r) t.prototype[n] = r[n];
                                                var s = function () {},
                                                    o = ["toString", "valueOf"];
                                                try {
                                                    /MSIE/.test(navigator.userAgent) &&
                                                    (s = function (t, e) {
                                                        for (n = 0; n < o.length; n += 1) {
                                                            var r = o[n],
                                                                i = e[r];
                                                            "function" == typeof i && i != Object.prototype[r] && (t[r] = i);
                                                        }
                                                    });
                                                } catch (t) {}
                                                s(t.prototype, r);
                                            }
                                        },
                                    };
                                    var Q = {};
                                    (void 0 !== Q.asn1 && Q.asn1) || (Q.asn1 = {}),
                                        (Q.asn1.ASN1Util = new (function () {
                                            (this.integerToByteHex = function (t) {
                                                var e = t.toString(16);
                                                return e.length % 2 == 1 && (e = "0" + e), e;
                                            }),
                                                (this.bigIntToMinTwosComplementsHex = function (t) {
                                                    var e = t.toString(16);
                                                    if ("-" != e.substr(0, 1)) e.length % 2 == 1 ? (e = "0" + e) : e.match(/^[0-7]/) || (e = "00" + e);
                                                    else {
                                                        var r = e.substr(1).length;
                                                        r % 2 == 1 ? (r += 1) : e.match(/^[0-7]/) || (r += 2);
                                                        for (var i = "", n = 0; n < r; n++) i += "f";
                                                        e = new R(i, 16).xor(t).add(R.ONE).toString(16).replace(/^-/, "");
                                                    }
                                                    return e;
                                                }),
                                                (this.getPEMStringFromHex = function (t, e) {
                                                    return hextopem(t, e);
                                                }),
                                                (this.newObject = function (t) {
                                                    var e = Q.asn1,
                                                        r = e.DERBoolean,
                                                        i = e.DERInteger,
                                                        n = e.DERBitString,
                                                        s = e.DEROctetString,
                                                        o = e.DERNull,
                                                        a = e.DERObjectIdentifier,
                                                        h = e.DEREnumerated,
                                                        c = e.DERUTF8String,
                                                        u = e.DERNumericString,
                                                        l = e.DERPrintableString,
                                                        f = e.DERTeletexString,
                                                        d = e.DERIA5String,
                                                        p = e.DERUTCTime,
                                                        g = e.DERGeneralizedTime,
                                                        m = e.DERSequence,
                                                        v = e.DERSet,
                                                        y = e.DERTaggedObject,
                                                        b = e.ASN1Util.newObject,
                                                        w = Object.keys(t);
                                                    if (1 != w.length) throw "key of param shall be only one.";
                                                    var T = w[0];
                                                    if (-1 == ":bool:int:bitstr:octstr:null:oid:enum:utf8str:numstr:prnstr:telstr:ia5str:utctime:gentime:seq:set:tag:".indexOf(":" + T + ":")) throw "undefined key: " + T;
                                                    if ("bool" == T) return new r(t[T]);
                                                    if ("int" == T) return new i(t[T]);
                                                    if ("bitstr" == T) return new n(t[T]);
                                                    if ("octstr" == T) return new s(t[T]);
                                                    if ("null" == T) return new o(t[T]);
                                                    if ("oid" == T) return new a(t[T]);
                                                    if ("enum" == T) return new h(t[T]);
                                                    if ("utf8str" == T) return new c(t[T]);
                                                    if ("numstr" == T) return new u(t[T]);
                                                    if ("prnstr" == T) return new l(t[T]);
                                                    if ("telstr" == T) return new f(t[T]);
                                                    if ("ia5str" == T) return new d(t[T]);
                                                    if ("utctime" == T) return new p(t[T]);
                                                    if ("gentime" == T) return new g(t[T]);
                                                    if ("seq" == T) {
                                                        for (var S = t[T], E = [], D = 0; D < S.length; D++) {
                                                            var x = b(S[D]);
                                                            E.push(x);
                                                        }
                                                        return new m({ array: E });
                                                    }
                                                    if ("set" == T) {
                                                        for (S = t[T], E = [], D = 0; D < S.length; D++) (x = b(S[D])), E.push(x);
                                                        return new v({ array: E });
                                                    }
                                                    if ("tag" == T) {
                                                        var R = t[T];
                                                        if ("[object Array]" === Object.prototype.toString.call(R) && 3 == R.length) {
                                                            var B = b(R[2]);
                                                            return new y({ tag: R[0], explicit: R[1], obj: B });
                                                        }
                                                        var O = {};
                                                        if ((void 0 !== R.explicit && (O.explicit = R.explicit), void 0 !== R.tag && (O.tag = R.tag), void 0 === R.obj)) throw "obj shall be specified for 'tag'.";
                                                        return (O.obj = b(R.obj)), new y(O);
                                                    }
                                                }),
                                                (this.jsonToASN1HEX = function (t) {
                                                    return this.newObject(t).getEncodedHex();
                                                });
                                        })()),
                                        (Q.asn1.ASN1Util.oidHexToInt = function (t) {
                                            for (var e = "", r = parseInt(t.substr(0, 2), 16), i = ((e = Math.floor(r / 40) + "." + (r % 40)), ""), n = 2; n < t.length; n += 2) {
                                                var s = ("00000000" + parseInt(t.substr(n, 2), 16).toString(2)).slice(-8);
                                                (i += s.substr(1, 7)), "0" == s.substr(0, 1) && ((e = e + "." + new R(i, 2).toString(10)), (i = ""));
                                            }
                                            return e;
                                        }),
                                        (Q.asn1.ASN1Util.oidIntToHex = function (t) {
                                            var e = function (t) {
                                                    var e = t.toString(16);
                                                    return 1 == e.length && (e = "0" + e), e;
                                                },
                                                r = function (t) {
                                                    var r = "",
                                                        i = new R(t, 10).toString(2),
                                                        n = 7 - (i.length % 7);
                                                    7 == n && (n = 0);
                                                    for (var s = "", o = 0; o < n; o++) s += "0";
                                                    for (i = s + i, o = 0; o < i.length - 1; o += 7) {
                                                        var a = i.substr(o, 7);
                                                        o != i.length - 7 && (a = "1" + a), (r += e(parseInt(a, 2)));
                                                    }
                                                    return r;
                                                };
                                            if (!t.match(/^[0-9.]+$/)) throw "malformed oid string: " + t;
                                            var i = "",
                                                n = t.split("."),
                                                s = 40 * parseInt(n[0]) + parseInt(n[1]);
                                            (i += e(s)), n.splice(0, 2);
                                            for (var o = 0; o < n.length; o++) i += r(n[o]);
                                            return i;
                                        }),
                                        (Q.asn1.ASN1Object = function () {
                                            (this.getLengthHexFromValue = function () {
                                                if (void 0 === this.hV || null == this.hV) throw "this.hV is null or undefined.";
                                                if (this.hV.length % 2 == 1) throw "value hex must be even length: n=0,v=" + this.hV;
                                                var t = this.hV.length / 2,
                                                    e = t.toString(16);
                                                if ((e.length % 2 == 1 && (e = "0" + e), t < 128)) return e;
                                                var r = e.length / 2;
                                                if (r > 15) throw "ASN.1 length too long to represent by 8x: n = " + t.toString(16);
                                                return (128 + r).toString(16) + e;
                                            }),
                                                (this.getEncodedHex = function () {
                                                    return (
                                                        (null == this.hTLV || this.isModified) &&
                                                        ((this.hV = this.getFreshValueHex()), (this.hL = this.getLengthHexFromValue()), (this.hTLV = this.hT + this.hL + this.hV), (this.isModified = !1)),
                                                            this.hTLV
                                                    );
                                                }),
                                                (this.getValueHex = function () {
                                                    return this.getEncodedHex(), this.hV;
                                                }),
                                                (this.getFreshValueHex = function () {
                                                    return "";
                                                });
                                        }),
                                        (Q.asn1.DERAbstractString = function (t) {
                                            Q.asn1.DERAbstractString.superclass.constructor.call(this),
                                                (this.getString = function () {
                                                    return this.s;
                                                }),
                                                (this.setString = function (t) {
                                                    (this.hTLV = null), (this.isModified = !0), (this.s = t), (this.hV = stohex(this.s));
                                                }),
                                                (this.setStringHex = function (t) {
                                                    (this.hTLV = null), (this.isModified = !0), (this.s = null), (this.hV = t);
                                                }),
                                                (this.getFreshValueHex = function () {
                                                    return this.hV;
                                                }),
                                            void 0 !== t && ("string" == typeof t ? this.setString(t) : void 0 !== t.str ? this.setString(t.str) : void 0 !== t.hex && this.setStringHex(t.hex));
                                        }),
                                        X.lang.extend(Q.asn1.DERAbstractString, Q.asn1.ASN1Object),
                                        (Q.asn1.DERAbstractTime = function (t) {
                                            Q.asn1.DERAbstractTime.superclass.constructor.call(this),
                                                (this.localDateToUTC = function (t) {
                                                    return (utc = t.getTime() + 6e4 * t.getTimezoneOffset()), new Date(utc);
                                                }),
                                                (this.formatDate = function (t, e, r) {
                                                    var i = this.zeroPadding,
                                                        n = this.localDateToUTC(t),
                                                        s = String(n.getFullYear());
                                                    "utc" == e && (s = s.substr(2, 2));
                                                    var o = s + i(String(n.getMonth() + 1), 2) + i(String(n.getDate()), 2) + i(String(n.getHours()), 2) + i(String(n.getMinutes()), 2) + i(String(n.getSeconds()), 2);
                                                    if (!0 === r) {
                                                        var a = n.getMilliseconds();
                                                        if (0 != a) {
                                                            var h = i(String(a), 3);
                                                            o = o + "." + (h = h.replace(/[0]+$/, ""));
                                                        }
                                                    }
                                                    return o + "Z";
                                                }),
                                                (this.zeroPadding = function (t, e) {
                                                    return t.length >= e ? t : new Array(e - t.length + 1).join("0") + t;
                                                }),
                                                (this.getString = function () {
                                                    return this.s;
                                                }),
                                                (this.setString = function (t) {
                                                    (this.hTLV = null), (this.isModified = !0), (this.s = t), (this.hV = stohex(t));
                                                }),
                                                (this.setByDateValue = function (t, e, r, i, n, s) {
                                                    var o = new Date(Date.UTC(t, e - 1, r, i, n, s, 0));
                                                    this.setByDate(o);
                                                }),
                                                (this.getFreshValueHex = function () {
                                                    return this.hV;
                                                });
                                        }),
                                        X.lang.extend(Q.asn1.DERAbstractTime, Q.asn1.ASN1Object),
                                        (Q.asn1.DERAbstractStructured = function (t) {
                                            Q.asn1.DERAbstractString.superclass.constructor.call(this),
                                                (this.setByASN1ObjectArray = function (t) {
                                                    (this.hTLV = null), (this.isModified = !0), (this.asn1Array = t);
                                                }),
                                                (this.appendASN1Object = function (t) {
                                                    (this.hTLV = null), (this.isModified = !0), this.asn1Array.push(t);
                                                }),
                                                (this.asn1Array = new Array()),
                                            void 0 !== t && void 0 !== t.array && (this.asn1Array = t.array);
                                        }),
                                        X.lang.extend(Q.asn1.DERAbstractStructured, Q.asn1.ASN1Object),
                                        (Q.asn1.DERBoolean = function () {
                                            Q.asn1.DERBoolean.superclass.constructor.call(this), (this.hT = "01"), (this.hTLV = "0101ff");
                                        }),
                                        X.lang.extend(Q.asn1.DERBoolean, Q.asn1.ASN1Object),
                                        (Q.asn1.DERInteger = function (t) {
                                            Q.asn1.DERInteger.superclass.constructor.call(this),
                                                (this.hT = "02"),
                                                (this.setByBigInteger = function (t) {
                                                    (this.hTLV = null), (this.isModified = !0), (this.hV = Q.asn1.ASN1Util.bigIntToMinTwosComplementsHex(t));
                                                }),
                                                (this.setByInteger = function (t) {
                                                    var e = new R(String(t), 10);
                                                    this.setByBigInteger(e);
                                                }),
                                                (this.setValueHex = function (t) {
                                                    this.hV = t;
                                                }),
                                                (this.getFreshValueHex = function () {
                                                    return this.hV;
                                                }),
                                            void 0 !== t &&
                                            (void 0 !== t.bigint
                                                ? this.setByBigInteger(t.bigint)
                                                : void 0 !== t.int
                                                    ? this.setByInteger(t.int)
                                                    : "number" == typeof t
                                                        ? this.setByInteger(t)
                                                        : void 0 !== t.hex && this.setValueHex(t.hex));
                                        }),
                                        X.lang.extend(Q.asn1.DERInteger, Q.asn1.ASN1Object),
                                        (Q.asn1.DERBitString = function (t) {
                                            if (void 0 !== t && void 0 !== t.obj) {
                                                var e = Q.asn1.ASN1Util.newObject(t.obj);
                                                t.hex = "00" + e.getEncodedHex();
                                            }
                                            Q.asn1.DERBitString.superclass.constructor.call(this),
                                                (this.hT = "03"),
                                                (this.setHexValueIncludingUnusedBits = function (t) {
                                                    (this.hTLV = null), (this.isModified = !0), (this.hV = t);
                                                }),
                                                (this.setUnusedBitsAndHexValue = function (t, e) {
                                                    if (t < 0 || 7 < t) throw "unused bits shall be from 0 to 7: u = " + t;
                                                    var r = "0" + t;
                                                    (this.hTLV = null), (this.isModified = !0), (this.hV = r + e);
                                                }),
                                                (this.setByBinaryString = function (t) {
                                                    var e = 8 - ((t = t.replace(/0+$/, "")).length % 8);
                                                    8 == e && (e = 0);
                                                    for (var r = 0; r <= e; r++) t += "0";
                                                    var i = "";
                                                    for (r = 0; r < t.length - 1; r += 8) {
                                                        var n = t.substr(r, 8),
                                                            s = parseInt(n, 2).toString(16);
                                                        1 == s.length && (s = "0" + s), (i += s);
                                                    }
                                                    (this.hTLV = null), (this.isModified = !0), (this.hV = "0" + e + i);
                                                }),
                                                (this.setByBooleanArray = function (t) {
                                                    for (var e = "", r = 0; r < t.length; r++) 1 == t[r] ? (e += "1") : (e += "0");
                                                    this.setByBinaryString(e);
                                                }),
                                                (this.newFalseArray = function (t) {
                                                    for (var e = new Array(t), r = 0; r < t; r++) e[r] = !1;
                                                    return e;
                                                }),
                                                (this.getFreshValueHex = function () {
                                                    return this.hV;
                                                }),
                                            void 0 !== t &&
                                            ("string" == typeof t && t.toLowerCase().match(/^[0-9a-f]+$/)
                                                ? this.setHexValueIncludingUnusedBits(t)
                                                : void 0 !== t.hex
                                                    ? this.setHexValueIncludingUnusedBits(t.hex)
                                                    : void 0 !== t.bin
                                                        ? this.setByBinaryString(t.bin)
                                                        : void 0 !== t.array && this.setByBooleanArray(t.array));
                                        }),
                                        X.lang.extend(Q.asn1.DERBitString, Q.asn1.ASN1Object),
                                        (Q.asn1.DEROctetString = function (t) {
                                            if (void 0 !== t && void 0 !== t.obj) {
                                                var e = Q.asn1.ASN1Util.newObject(t.obj);
                                                t.hex = e.getEncodedHex();
                                            }
                                            Q.asn1.DEROctetString.superclass.constructor.call(this, t), (this.hT = "04");
                                        }),
                                        X.lang.extend(Q.asn1.DEROctetString, Q.asn1.DERAbstractString),
                                        (Q.asn1.DERNull = function () {
                                            Q.asn1.DERNull.superclass.constructor.call(this), (this.hT = "05"), (this.hTLV = "0500");
                                        }),
                                        X.lang.extend(Q.asn1.DERNull, Q.asn1.ASN1Object),
                                        (Q.asn1.DERObjectIdentifier = function (t) {
                                            var e = function (t) {
                                                    var e = t.toString(16);
                                                    return 1 == e.length && (e = "0" + e), e;
                                                },
                                                r = function (t) {
                                                    var r = "",
                                                        i = new R(t, 10).toString(2),
                                                        n = 7 - (i.length % 7);
                                                    7 == n && (n = 0);
                                                    for (var s = "", o = 0; o < n; o++) s += "0";
                                                    for (i = s + i, o = 0; o < i.length - 1; o += 7) {
                                                        var a = i.substr(o, 7);
                                                        o != i.length - 7 && (a = "1" + a), (r += e(parseInt(a, 2)));
                                                    }
                                                    return r;
                                                };
                                            Q.asn1.DERObjectIdentifier.superclass.constructor.call(this),
                                                (this.hT = "06"),
                                                (this.setValueHex = function (t) {
                                                    (this.hTLV = null), (this.isModified = !0), (this.s = null), (this.hV = t);
                                                }),
                                                (this.setValueOidString = function (t) {
                                                    if (!t.match(/^[0-9.]+$/)) throw "malformed oid string: " + t;
                                                    var i = "",
                                                        n = t.split("."),
                                                        s = 40 * parseInt(n[0]) + parseInt(n[1]);
                                                    (i += e(s)), n.splice(0, 2);
                                                    for (var o = 0; o < n.length; o++) i += r(n[o]);
                                                    (this.hTLV = null), (this.isModified = !0), (this.s = null), (this.hV = i);
                                                }),
                                                (this.setValueName = function (t) {
                                                    var e = Q.asn1.x509.OID.name2oid(t);
                                                    if ("" === e) throw "DERObjectIdentifier oidName undefined: " + t;
                                                    this.setValueOidString(e);
                                                }),
                                                (this.getFreshValueHex = function () {
                                                    return this.hV;
                                                }),
                                            void 0 !== t &&
                                            ("string" == typeof t
                                                ? t.match(/^[0-2].[0-9.]+$/)
                                                    ? this.setValueOidString(t)
                                                    : this.setValueName(t)
                                                : void 0 !== t.oid
                                                    ? this.setValueOidString(t.oid)
                                                    : void 0 !== t.hex
                                                        ? this.setValueHex(t.hex)
                                                        : void 0 !== t.name && this.setValueName(t.name));
                                        }),
                                        X.lang.extend(Q.asn1.DERObjectIdentifier, Q.asn1.ASN1Object),
                                        (Q.asn1.DEREnumerated = function (t) {
                                            Q.asn1.DEREnumerated.superclass.constructor.call(this),
                                                (this.hT = "0a"),
                                                (this.setByBigInteger = function (t) {
                                                    (this.hTLV = null), (this.isModified = !0), (this.hV = Q.asn1.ASN1Util.bigIntToMinTwosComplementsHex(t));
                                                }),
                                                (this.setByInteger = function (t) {
                                                    var e = new R(String(t), 10);
                                                    this.setByBigInteger(e);
                                                }),
                                                (this.setValueHex = function (t) {
                                                    this.hV = t;
                                                }),
                                                (this.getFreshValueHex = function () {
                                                    return this.hV;
                                                }),
                                            void 0 !== t && (void 0 !== t.int ? this.setByInteger(t.int) : "number" == typeof t ? this.setByInteger(t) : void 0 !== t.hex && this.setValueHex(t.hex));
                                        }),
                                        X.lang.extend(Q.asn1.DEREnumerated, Q.asn1.ASN1Object),
                                        (Q.asn1.DERUTF8String = function (t) {
                                            Q.asn1.DERUTF8String.superclass.constructor.call(this, t), (this.hT = "0c");
                                        }),
                                        X.lang.extend(Q.asn1.DERUTF8String, Q.asn1.DERAbstractString),
                                        (Q.asn1.DERNumericString = function (t) {
                                            Q.asn1.DERNumericString.superclass.constructor.call(this, t), (this.hT = "12");
                                        }),
                                        X.lang.extend(Q.asn1.DERNumericString, Q.asn1.DERAbstractString),
                                        (Q.asn1.DERPrintableString = function (t) {
                                            Q.asn1.DERPrintableString.superclass.constructor.call(this, t), (this.hT = "13");
                                        }),
                                        X.lang.extend(Q.asn1.DERPrintableString, Q.asn1.DERAbstractString),
                                        (Q.asn1.DERTeletexString = function (t) {
                                            Q.asn1.DERTeletexString.superclass.constructor.call(this, t), (this.hT = "14");
                                        }),
                                        X.lang.extend(Q.asn1.DERTeletexString, Q.asn1.DERAbstractString),
                                        (Q.asn1.DERIA5String = function (t) {
                                            Q.asn1.DERIA5String.superclass.constructor.call(this, t), (this.hT = "16");
                                        }),
                                        X.lang.extend(Q.asn1.DERIA5String, Q.asn1.DERAbstractString),
                                        (Q.asn1.DERUTCTime = function (t) {
                                            Q.asn1.DERUTCTime.superclass.constructor.call(this, t),
                                                (this.hT = "17"),
                                                (this.setByDate = function (t) {
                                                    (this.hTLV = null), (this.isModified = !0), (this.date = t), (this.s = this.formatDate(this.date, "utc")), (this.hV = stohex(this.s));
                                                }),
                                                (this.getFreshValueHex = function () {
                                                    return void 0 === this.date && void 0 === this.s && ((this.date = new Date()), (this.s = this.formatDate(this.date, "utc")), (this.hV = stohex(this.s))), this.hV;
                                                }),
                                            void 0 !== t &&
                                            (void 0 !== t.str
                                                ? this.setString(t.str)
                                                : "string" == typeof t && t.match(/^[0-9]{12}Z$/)
                                                    ? this.setString(t)
                                                    : void 0 !== t.hex
                                                        ? this.setStringHex(t.hex)
                                                        : void 0 !== t.date && this.setByDate(t.date));
                                        }),
                                        X.lang.extend(Q.asn1.DERUTCTime, Q.asn1.DERAbstractTime),
                                        (Q.asn1.DERGeneralizedTime = function (t) {
                                            Q.asn1.DERGeneralizedTime.superclass.constructor.call(this, t),
                                                (this.hT = "18"),
                                                (this.withMillis = !1),
                                                (this.setByDate = function (t) {
                                                    (this.hTLV = null), (this.isModified = !0), (this.date = t), (this.s = this.formatDate(this.date, "gen", this.withMillis)), (this.hV = stohex(this.s));
                                                }),
                                                (this.getFreshValueHex = function () {
                                                    return void 0 === this.date && void 0 === this.s && ((this.date = new Date()), (this.s = this.formatDate(this.date, "gen", this.withMillis)), (this.hV = stohex(this.s))), this.hV;
                                                }),
                                            void 0 !== t &&
                                            (void 0 !== t.str
                                                ? this.setString(t.str)
                                                : "string" == typeof t && t.match(/^[0-9]{14}Z$/)
                                                    ? this.setString(t)
                                                    : void 0 !== t.hex
                                                        ? this.setStringHex(t.hex)
                                                        : void 0 !== t.date && this.setByDate(t.date),
                                            !0 === t.millis && (this.withMillis = !0));
                                        }),
                                        X.lang.extend(Q.asn1.DERGeneralizedTime, Q.asn1.DERAbstractTime),
                                        (Q.asn1.DERSequence = function (t) {
                                            Q.asn1.DERSequence.superclass.constructor.call(this, t),
                                                (this.hT = "30"),
                                                (this.getFreshValueHex = function () {
                                                    for (var t = "", e = 0; e < this.asn1Array.length; e++) t += this.asn1Array[e].getEncodedHex();
                                                    return (this.hV = t), this.hV;
                                                });
                                        }),
                                        X.lang.extend(Q.asn1.DERSequence, Q.asn1.DERAbstractStructured),
                                        (Q.asn1.DERSet = function (t) {
                                            Q.asn1.DERSet.superclass.constructor.call(this, t),
                                                (this.hT = "31"),
                                                (this.sortFlag = !0),
                                                (this.getFreshValueHex = function () {
                                                    for (var t = new Array(), e = 0; e < this.asn1Array.length; e++) {
                                                        var r = this.asn1Array[e];
                                                        t.push(r.getEncodedHex());
                                                    }
                                                    return 1 == this.sortFlag && t.sort(), (this.hV = t.join("")), this.hV;
                                                }),
                                            void 0 !== t && void 0 !== t.sortflag && 0 == t.sortflag && (this.sortFlag = !1);
                                        }),
                                        X.lang.extend(Q.asn1.DERSet, Q.asn1.DERAbstractStructured),
                                        (Q.asn1.DERTaggedObject = function (t) {
                                            Q.asn1.DERTaggedObject.superclass.constructor.call(this),
                                                (this.hT = "a0"),
                                                (this.hV = ""),
                                                (this.isExplicit = !0),
                                                (this.asn1Object = null),
                                                (this.setASN1Object = function (t, e, r) {
                                                    (this.hT = e),
                                                        (this.isExplicit = t),
                                                        (this.asn1Object = r),
                                                        this.isExplicit
                                                            ? ((this.hV = this.asn1Object.getEncodedHex()), (this.hTLV = null), (this.isModified = !0))
                                                            : ((this.hV = null), (this.hTLV = r.getEncodedHex()), (this.hTLV = this.hTLV.replace(/^../, e)), (this.isModified = !1));
                                                }),
                                                (this.getFreshValueHex = function () {
                                                    return this.hV;
                                                }),
                                            void 0 !== t &&
                                            (void 0 !== t.tag && (this.hT = t.tag),
                                            void 0 !== t.explicit && (this.isExplicit = t.explicit),
                                            void 0 !== t.obj && ((this.asn1Object = t.obj), this.setASN1Object(this.isExplicit, this.hT, this.asn1Object)));
                                        }),
                                        X.lang.extend(Q.asn1.DERTaggedObject, Q.asn1.ASN1Object);
                                    var tt,
                                        et,
                                        rt =
                                            ((tt = function (t, e) {
                                                return (
                                                    (tt =
                                                        Object.setPrototypeOf ||
                                                        ({ __proto__: [] } instanceof Array &&
                                                            function (t, e) {
                                                                t.__proto__ = e;
                                                            }) ||
                                                        function (t, e) {
                                                            for (var r in e) Object.prototype.hasOwnProperty.call(e, r) && (t[r] = e[r]);
                                                        }),
                                                        tt(t, e)
                                                );
                                            }),
                                                function (t, e) {
                                                    if ("function" != typeof e && null !== e) throw new TypeError("Class extends value " + String(e) + " is not a constructor or null");
                                                    function r() {
                                                        this.constructor = t;
                                                    }
                                                    tt(t, e), (t.prototype = null === e ? Object.create(e) : ((r.prototype = e.prototype), new r()));
                                                }),
                                        it = (function (t) {
                                            function e(r) {
                                                var i = t.call(this) || this;
                                                return r && ("string" == typeof r ? i.parseKey(r) : (e.hasPrivateKeyProperty(r) || e.hasPublicKeyProperty(r)) && i.parsePropertiesFrom(r)), i;
                                            }
                                            return (
                                                rt(e, t),
                                                    (e.prototype.parseKey = function (t) {
                                                        try {
                                                            var e = 0,
                                                                r = 0,
                                                                i = /^\s*(?:[0-9A-Fa-f][0-9A-Fa-f]\s*)+$/.test(t)
                                                                    ? (function (t) {
                                                                        var e;
                                                                        if (void 0 === c) {
                                                                            var r = "0123456789ABCDEF";
                                                                            for (c = {}, e = 0; e < 16; ++e) c[r.charAt(e)] = e;
                                                                            for (r = r.toLowerCase(), e = 10; e < 16; ++e) c[r.charAt(e)] = e;
                                                                            for (e = 0; e < 8; ++e) c[" \f\n\r\t \u2028\u2029".charAt(e)] = -1;
                                                                        }
                                                                        var i = [],
                                                                            n = 0,
                                                                            s = 0;
                                                                        for (e = 0; e < t.length; ++e) {
                                                                            var o = t.charAt(e);
                                                                            if ("=" == o) break;
                                                                            if (-1 != (o = c[o])) {
                                                                                if (void 0 === o) throw new Error("Illegal character at offset " + e);
                                                                                (n |= o), ++s >= 2 ? ((i[i.length] = n), (n = 0), (s = 0)) : (n <<= 4);
                                                                            }
                                                                        }
                                                                        if (s) throw new Error("Hex encoding incomplete: 4 bits missing");
                                                                        return i;
                                                                    })(t)
                                                                    : p.unarmor(t),
                                                                n = S.decode(i);
                                                            if ((3 === n.sub.length && (n = n.sub[2].sub[0]), 9 === n.sub.length)) {
                                                                (e = n.sub[1].getHexStringValue()), (this.n = _(e, 16)), (r = n.sub[2].getHexStringValue()), (this.e = parseInt(r, 16));
                                                                var s = n.sub[3].getHexStringValue();
                                                                this.d = _(s, 16);
                                                                var o = n.sub[4].getHexStringValue();
                                                                this.p = _(o, 16);
                                                                var a = n.sub[5].getHexStringValue();
                                                                this.q = _(a, 16);
                                                                var h = n.sub[6].getHexStringValue();
                                                                this.dmp1 = _(h, 16);
                                                                var u = n.sub[7].getHexStringValue();
                                                                this.dmq1 = _(u, 16);
                                                                var l = n.sub[8].getHexStringValue();
                                                                this.coeff = _(l, 16);
                                                            } else {
                                                                if (2 !== n.sub.length) return !1;
                                                                if (n.sub[0].sub) {
                                                                    var f = n.sub[1].sub[0];
                                                                    (e = f.sub[0].getHexStringValue()), (this.n = _(e, 16)), (r = f.sub[1].getHexStringValue()), (this.e = parseInt(r, 16));
                                                                } else (e = n.sub[0].getHexStringValue()), (this.n = _(e, 16)), (r = n.sub[1].getHexStringValue()), (this.e = parseInt(r, 16));
                                                            }
                                                            return !0;
                                                        } catch (t) {
                                                            return !1;
                                                        }
                                                    }),
                                                    (e.prototype.getPrivateBaseKey = function () {
                                                        var t = {
                                                            array: [
                                                                new Q.asn1.DERInteger({ int: 0 }),
                                                                new Q.asn1.DERInteger({ bigint: this.n }),
                                                                new Q.asn1.DERInteger({ int: this.e }),
                                                                new Q.asn1.DERInteger({ bigint: this.d }),
                                                                new Q.asn1.DERInteger({ bigint: this.p }),
                                                                new Q.asn1.DERInteger({ bigint: this.q }),
                                                                new Q.asn1.DERInteger({ bigint: this.dmp1 }),
                                                                new Q.asn1.DERInteger({ bigint: this.dmq1 }),
                                                                new Q.asn1.DERInteger({ bigint: this.coeff }),
                                                            ],
                                                        };
                                                        return new Q.asn1.DERSequence(t).getEncodedHex();
                                                    }),
                                                    (e.prototype.getPrivateBaseKeyB64 = function () {
                                                        return l(this.getPrivateBaseKey());
                                                    }),
                                                    (e.prototype.getPublicBaseKey = function () {
                                                        var t = new Q.asn1.DERSequence({ array: [new Q.asn1.DERObjectIdentifier({ oid: "1.2.840.113549.1.1.1" }), new Q.asn1.DERNull()] }),
                                                            e = new Q.asn1.DERSequence({ array: [new Q.asn1.DERInteger({ bigint: this.n }), new Q.asn1.DERInteger({ int: this.e })] }),
                                                            r = new Q.asn1.DERBitString({ hex: "00" + e.getEncodedHex() });
                                                        return new Q.asn1.DERSequence({ array: [t, r] }).getEncodedHex();
                                                    }),
                                                    (e.prototype.getPublicBaseKeyB64 = function () {
                                                        return l(this.getPublicBaseKey());
                                                    }),
                                                    (e.wordwrap = function (t, e) {
                                                        if (!t) return t;
                                                        var r = "(.{1," + (e = e || 64) + "})( +|$\n?)|(.{1," + e + "})";
                                                        return t.match(RegExp(r, "g")).join("\n");
                                                    }),
                                                    (e.prototype.getPrivateKey = function () {
                                                        var t = "-----BEGIN RSA PRIVATE KEY-----\n";
                                                        return (t += e.wordwrap(this.getPrivateBaseKeyB64()) + "\n") + "-----END RSA PRIVATE KEY-----";
                                                    }),
                                                    (e.prototype.getPublicKey = function () {
                                                        var t = "-----BEGIN PUBLIC KEY-----\n";
                                                        return (t += e.wordwrap(this.getPublicBaseKeyB64()) + "\n") + "-----END PUBLIC KEY-----";
                                                    }),
                                                    (e.hasPublicKeyProperty = function (t) {
                                                        return (t = t || {}).hasOwnProperty("n") && t.hasOwnProperty("e");
                                                    }),
                                                    (e.hasPrivateKeyProperty = function (t) {
                                                        return (
                                                            (t = t || {}).hasOwnProperty("n") &&
                                                            t.hasOwnProperty("e") &&
                                                            t.hasOwnProperty("d") &&
                                                            t.hasOwnProperty("p") &&
                                                            t.hasOwnProperty("q") &&
                                                            t.hasOwnProperty("dmp1") &&
                                                            t.hasOwnProperty("dmq1") &&
                                                            t.hasOwnProperty("coeff")
                                                        );
                                                    }),
                                                    (e.prototype.parsePropertiesFrom = function (t) {
                                                        (this.n = t.n), (this.e = t.e), t.hasOwnProperty("d") && ((this.d = t.d), (this.p = t.p), (this.q = t.q), (this.dmp1 = t.dmp1), (this.dmq1 = t.dmq1), (this.coeff = t.coeff));
                                                    }),
                                                    e
                                            );
                                        })(J),
                                        nt = r(155),
                                        st = void 0 !== nt ? (null === (et = nt.env) || void 0 === et ? void 0 : "3.3.1") : void 0;
                                    const ot = (function () {
                                        function t(t) {
                                            void 0 === t && (t = {}),
                                                (t = t || {}),
                                                (this.default_key_size = t.default_key_size ? parseInt(t.default_key_size, 10) : 1024),
                                                (this.default_public_exponent = t.default_public_exponent || "010001"),
                                                (this.log = t.log || !1),
                                                (this.key = null);
                                        }
                                        return (
                                            (t.prototype.setKey = function (t) {
                                                this.log && this.key && console.warn("A key was already set, overriding existing."), (this.key = new it(t));
                                            }),
                                                (t.prototype.setPrivateKey = function (t) {
                                                    this.setKey(t);
                                                }),
                                                (t.prototype.setPublicKey = function (t) {
                                                    this.setKey(t);
                                                }),
                                                (t.prototype.decrypt = function (t) {
                                                    try {
                                                        return this.getKey().decrypt(f(t));
                                                    } catch (t) {
                                                        return !1;
                                                    }
                                                }),
                                                (t.prototype.encrypt = function (t) {
                                                    try {
                                                        return l(this.getKey().encrypt(t));
                                                    } catch (t) {
                                                        return !1;
                                                    }
                                                }),
                                                (t.prototype.sign = function (t, e, r) {
                                                    try {
                                                        return l(this.getKey().sign(t, e, r));
                                                    } catch (t) {
                                                        return !1;
                                                    }
                                                }),
                                                (t.prototype.verify = function (t, e, r) {
                                                    try {
                                                        return this.getKey().verify(t, f(e), r);
                                                    } catch (t) {
                                                        return !1;
                                                    }
                                                }),
                                                (t.prototype.getKey = function (t) {
                                                    if (!this.key) {
                                                        if (((this.key = new it()), t && "[object Function]" === {}.toString.call(t))) return void this.key.generateAsync(this.default_key_size, this.default_public_exponent, t);
                                                        this.key.generate(this.default_key_size, this.default_public_exponent);
                                                    }
                                                    return this.key;
                                                }),
                                                (t.prototype.getPrivateKey = function () {
                                                    return this.getKey().getPrivateKey();
                                                }),
                                                (t.prototype.getPrivateKeyB64 = function () {
                                                    return this.getKey().getPrivateBaseKeyB64();
                                                }),
                                                (t.prototype.getPublicKey = function () {
                                                    return this.getKey().getPublicKey();
                                                }),
                                                (t.prototype.getPublicKeyB64 = function () {
                                                    return this.getKey().getPublicBaseKeyB64();
                                                }),
                                                (t.version = st),
                                                t
                                        );
                                    })();
                                })(),
                                    i.default
                            );
                        })()),
                    (t.exports = e());
            },
            860: (t) => {
                "use strict";
                t.exports = require("jsdom");
            },
        },
        e = {};
    function r(i) {
        var n = e[i];
        if (void 0 !== n) return n.exports;
        var s = (e[i] = { exports: {} });
        return t[i](s, s.exports, r), s.exports;
    }
    (r.n = (t) => {
        var e = t && t.__esModule ? () => t.default : () => t;
        return r.d(e, { a: e }), e;
    }),
        (r.d = (t, e) => {
            for (var i in e) r.o(e, i) && !r.o(t, i) && Object.defineProperty(t, i, { enumerable: !0, get: e[i] });
        }),
        (r.g = (function () {
            if ("object" == typeof globalThis) return globalThis;
            try {
                return this || new Function("return this")();
            } catch (t) {
                if ("object" == typeof window) return window;
            }
        })()),
        (r.o = (t, e) => Object.prototype.hasOwnProperty.call(t, e)),
        (r.r = (t) => {
            "undefined" != typeof Symbol && Symbol.toStringTag && Object.defineProperty(t, Symbol.toStringTag, { value: "Module" }), Object.defineProperty(t, "__esModule", { value: !0 });
        });
    var i = {};
    (() => {
        "use strict";
        r.r(i), r.d(i, { CreditCard: () => p });
        class t extends Error {
            constructor(t) {
                super(t.error_description), (this.code = t.code), (this.error = t.error), (this.error_description = t.error_description);
            }
        }
        class e {
            static accountIdentifier(e) {
                if (!/^[a-zA-Z0-9]{32}$/i.test(e) || "string" != typeof e) throw new t({ error: "identificador_invalido", error_description: `Identificador de conta [${e}] inválido`, code: 400 });
                return e;
            }
            static environment(e) {
                if ("string" != typeof e) throw new t({ error: "ambiente_invalido", error_description: "O ambiente deve ser fornecido como string", code: 400 });
                const r = ["production", "sandbox"],
                    i = e.toLowerCase();
                if (!r.includes(i)) {
                    const e = { error: "ambiente_invalido", error_description: `Escolha um ambiente de integração entre "${r.join('" e "')}"`, code: 400 };
                    throw new t(e);
                }
                return i;
            }
            static creditCard(e) {
                if ("object" != typeof e) throw new t({ error: "dados_cartao_invalido", error_description: "O parâmetro em setCreditCardData deve ser um objeto", code: 400 });
                const r = ["brand", "number", "cvv", "expirationMonth", "expirationYear"];
                for (const i of r) if (!e.hasOwnProperty(i)) throw new t({ error: "dados_cartao_invalido", error_description: `O atributo [${i}] é obrigatório`, code: 400 });
                const { brand: i, number: n, cvv: s, expirationMonth: o, expirationYear: a, reuse: h } = e;
                return { brand: this.brand(i), number: this.cardNumber(n), cvv: this.cardCvv(s), expiration_month: this.expirationMonth(o), expiration_year: this.expirationYear(a, o), reuse: this.reusable(h) };
            }
            static brand(e) {
                if ("string" != typeof e) throw new t({ error: "bandeira_cartao_invalido", error_description: "A bandeira do cartão deve ser fornecido como string", code: 400 });
                const r = ["visa", "mastercard", "amex", "elo", "hipercard"],
                    i = e.toLowerCase();
                if (!r.includes(i)) {
                    const e = { error: "bandeira_invalida", error_description: `Escolha uma bandeira entre "${r.join('", "')}"`, code: 400 };
                    throw new t(e);
                }
                return i;
            }
            static brands(t) {
                const e = t.replace(/\D/g, "");
                let r = "";
                return (
                    (/(60420[1-9]|6042[1-9][0-9]|6043[0-9]{2}|604400)/.test(e) ||
                        /^3(?:0[0-5]|[68][0-9])[0-9]{0,11}$/.test(e) ||
                        /^6(?:011|5[0-9]{2})[0-9]{12}/.test(e) ||
                        /^2(?:014|149)[0-9]{0,11}$/.test(e) ||
                        /^(?:2131|1800|35\d{3})\d{0,11}$/.test(e) ||
                        /^627892|^636414/.test(e) ||
                        /^606444|^606458|^606482/.test(e) ||
                        /^8699[0-9]{0,11}$/.test(e) ||
                        /^50[0-9]{0,17}$/.test(e)) &&
                    (r = "unsupported"),
                        (r = /^4\d{0,12}(?:\d{3})?$/.test(e) ? "visa" : r),
                        (r = /^(?:5[1-5][0-9]{2}|222[1-9]|22[3-9][0-9]|2[3-6][0-9]{2}|27[01][0-9]|2720)\d{0,12}$/.test(e) ? "mastercard" : r),
                        (r = /^(4011(78|79)|43(1274|8935)|45(1416|7393|763(1|2))|50(4175|6699|67[0-7][0-9]|9000)|50(9[0-9][0-9][0-9])|627780|63(6297|6368)|650(03([^4])|04([0-9])|05(0|1)|05([7-9])|06([0-9])|07([0-9])|08([0-9])|4([0-3][0-9]|8[5-9]|9[0-9])|5([0-9][0-9]|3[0-8])|9([0-6][0-9]|7[0-8])|7([0-2][0-9])|541|700|720|727|901)|65165([2-9])|6516([6-7][0-9])|65500([0-9])|6550([0-5][0-9])|655021|65505([6-7])|6516([8-9][0-9])|65170([0-4]))/.test(
                            e
                        )
                            ? "elo"
                            : r),
                        (r = /^3[47][0-9]{0,13}$/.test(e) ? "amex" : r),
                        (r = /^(606282\d{10}(\d{3})?)|(3841\d{0,15})$/.test(e) ? "hipercard" : r),
                    r || "undefined"
                );
            }
            static total(e) {
                if (!Number.isInteger(e)) throw new t({ error: "valor_invalido", error_description: "O valor total deve ser fornecido como número inteiro", code: 400 });
                if (e < 300) throw new t({ error: "valor_invalido", error_description: `O valor [${e}] é inferior ao limite mínimo (R$3,00)`, code: 400 });
                return e;
            }
            static cardNumber(e) {
                if ("string" != typeof e) throw new t({ error: "numero_cartao_invalido", error_description: "O número do cartão deve ser fornecido como string", code: 400 });
                const r = e.replace(/\s+/g, "");
                if (!/^[0-9]+$/.test(r)) throw new t({ error: "numero_cartao_invalido", error_description: "O número do cartão deve conter somente números", code: 400 });
                return r;
            }
            static cardCvv(e) {
                if ("string" != typeof e) throw new t({ error: "cvv_cartao_invalido", error_description: "O número CVV do cartão deve ser fornecido como string", code: 400 });
                const r = e.replace(/\D/g, "");
                if (!/^\d{3,4}$/.test(r)) throw new t({ error: "cvv_cartao_invalido", error_description: `Número CVV do cartão [${e}] inválido`, code: 400 });
                return r;
            }
            static expirationMonth(e) {
                if ("string" != typeof e) throw new t({ error: "validade_cartao_invalida", error_description: "O mês de validade do cartão deve ser fornecido como string", code: 400 });
                const r = e.replace(/\D/g, ""),
                    i = parseInt(e);
                if (i < 1 || i > 12 || !/^\d{2}$/.test(r)) throw new t({ error: "validade_cartao_invalida", error_description: `Mês de validade do cartão [${e}] inválido`, code: 400 });
                return r;
            }
            static expirationYear(e, r) {
                if ("string" != typeof e) throw new t({ error: "validade_cartao_invalida", error_description: "O ano de validade do cartão deve ser fornecido como string", code: 400 });
                const i = new Date().getFullYear(),
                    n = new Date().getMonth() + 1,
                    s = parseInt(e.replace(/\D/g, ""), 10);
                if (isNaN(s) || s < i || s > i + 11) throw new t({ error: "validade_cartao_invalida", error_description: `Ano de validade do cartão [${e}] inválido`, code: 400 });
                if (s === i && r <= n) throw new t({ error: "validade_cartao_invalida", error_description: `Data de validade do cartão [${r}/${e}] expirada`, code: 400 });
                return s.toString();
            }
            static reusable(e = !1) {
                if ("boolean" != typeof e) throw new t({ error: "reuse_invalido", error_description: "O parâmetro [reuse] deve ser um booleano", code: 400 });
                return e;
            }
            static debugger(t) {
                return "boolean" == typeof t || ["true", "false"].includes(t.toString()) || (t = !1), t;
            }
        }
        class n {
            static async getBrand(t) {
                const r = e.cardNumber(t);
                return e.brands(r);
            }
        }
        class s {
            static async getInstallments(e, r, i, n, s = !1) {
                const o = `${"production" === n ? "https://api.gerencianet.com.br/v1/installments" : "https://sandbox.gerencianet.com.br/v1/installments"}/${e}/jsonp?brand=${r}&total=${i}`,
                    a = await fetch(o, { method: "GET", headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" } }),
                    h = await a.json();
                if (!a.ok || 200 !== h.code) {
                    s && console.error("RequestError_Installments", h);
                    const e = {
                        code: h.status ?? 400,
                        error: h.error ?? "request_error",
                        error_description: ((c = h.error_description), void 0 === c ? "Falha ao capturar payment_token no processo SaveCardData" : "object" == typeof c ? `Propriedade: ${c.property}. ${c.message}` : c),
                    };
                    throw new t(e);
                }
                var c;
                return h.data;
            }
        }
        class o {
            static isBrowser() {
                return "object" != typeof process && "function" != typeof importScripts && "object" == typeof window;
            }
        }
        class a {
            static async getSalt(e, r = !1) {
                const i = o.isBrowser();
                if (i && document.cookie.split(";").some((t) => t.trim().startsWith("P_expireAt="))) {
                    const t = new RegExp("(?:(?:^|.*;\\s*)P_expireAt\\s*=\\s*([^;]*).*$)|^.*$"),
                        e = document.cookie.replace(t, "$1");
                    if (new Date().setSeconds(1) < e) {
                        const t = new RegExp("(?:(?:^|.*;\\s*)P_salt\\s*=\\s*([^;]*).*$)|^.*$"),
                            e = decodeURIComponent(document.cookie.replace(t, "$1"));
                        if (e) return e;
                    }
                }
                const n = { method: "GET", headers: { "Account-code": e, "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" } },
                    s = await fetch("https://tokenizer.gerencianet.com.br/salt", n),
                    a = await s.json();
                if (!s.ok || 200 !== a.code) {
                    r && console.error("RequestError_Salt", a);
                    const e = { code: a.status ?? 400, error: a.error ?? "request_error", error_description: void 0 !== a.error_description ? a.error_description : "Falha ao capturar o salt no processo getSalt" };
                    throw new t(e);
                }
                const h = a.data;
                if (i) {
                    const t = new Date().setSeconds(55);
                    (document.cookie = `${encodeURIComponent("P_expireAt")}=${encodeURIComponent(t)}; max-age=60; expires=${new Date(Date.now() + 6e4).toUTCString()}; path=/; SameSite=Strict; Secure`),
                        (document.cookie = `${encodeURIComponent("P_salt")}=${encodeURIComponent(h)}; max-age=60; expires=${new Date(Date.now() + 6e4).toUTCString()}; path=/; SameSite=Strict; Secure`);
                }
                return h;
            }
        }
        class h {
            static async getPublicKey(e, r, i = !1) {
                const n = o.isBrowser(),
                    s = "production" === r,
                    a = s ? "P_" : "H_";
                if (n && document.cookie.split(";").some((t) => t.trim().startsWith(`${a}publicKey=`))) {
                    const t = new RegExp(`(?:(?:^|.*;\\s*)${a}publicKey\\s*=\\s*([^;]*).*$)|^.*$`),
                        e = decodeURIComponent(document.cookie.replace(t, "$1"));
                    if (e) return e;
                }
                const h = s ? "https://api.gerencianet.com.br/v1/pubkey" : "https://sandbox.gerencianet.com.br/v1/pubkey",
                    c = await fetch(`${h}?code=${e}`, { method: "GET" }),
                    u = await c.json();
                if (!c.ok || 200 !== u.code) {
                    i && console.error("RequestError_PublicKey", u);
                    const e = { code: u.status ?? 400, error: u.error ?? "request_error", error_description: void 0 !== u.error_description ? u.error_description : "Falha ao capturar a chave pública no processo getPublicKey" };
                    throw new t(e);
                }
                const l = u.data;
                return n && (document.cookie = `${encodeURIComponent(`${a}publicKey`)}=${encodeURIComponent(l)}; max-age=3600; path=/; SameSite=Strict; Secure`), l;
            }
        }
        class c {
            static async getPaymentToken(accountCode, sessionToken, cardData, environment, debug = false) {
                const isProduction = environment === "production";
                const url = isProduction
                    ? "https://tokenizer.gerencianet.com.br/card"
                    : `https://sandbox.gerencianet.com.br/v1/card/${accountCode}/jsonp?` + new URLSearchParams({ data: cardData });

                const headers = {
                    "Content-Type": "application/json; charset=UTF-8",
                    "Session-token": sessionToken
                };

                if (isProduction) {
                    headers["Account-code"] = accountCode;
                }

                const options = {
                    method: isProduction ? "POST" : "GET",
                    headers: headers,
                    body: isProduction ? JSON.stringify({ data: cardData }) : null
                };
                return {url: url, options: options}

                // try {
                //     const response = await fetch(url, options);
                //     const result = await response.json();
                //
                //     if (!response.ok || result.code !== 200) {
                //         if (debug) {
                //             console.error("RequestError_SaveCardData", result);
                //         }
                //         const error = {
                //             code: result.status ?? 400,
                //             error: result.error ?? "request_error",
                //             error_description: result.error_description !== undefined
                //                 ? result.error_description
                //                 : "Falha ao capturar payment_token no processo getPaymentToken"
                //         };
                //         throw new t(error);
                //     }
                //
                //     return result.data;
                // } catch (error) {
                //     if (debug) {
                //         console.error("Error_getPaymentToken", error);
                //     }
                //     throw new t(error);
                // }
            }

        }
        var u = r(758),
            l = r.n(u);
        class f {
            static async getFingerPrint(logDetails = false) {
                try {
                    const apiKey = l().aF.cS.key;
                    const sessionToken = this.generateSessionToken();
                    const isBrowser = o.isBrowser();

                    if (logDetails) {
                        console.log("Application_type:", isBrowser ? "Browser" : "Node");
                    }

                    return sessionToken;
                    if (isBrowser) {
                        await this.loadClearSaleScript(apiKey, sessionToken);
                    } else {
                        await this.loadClearSaleScriptNode(apiKey, sessionToken);

                    }
                } catch (error) {
                    if (logDetails) {
                        console.error("RequestError_FingerPrint:", error);
                    }
                    throw new t({
                        code: 500,
                        error: "erro_cs_token",
                        error_description: "Falha na execução do FingerPrint"
                    });
                }
            }
            static generateSessionToken() {
                const t = "0123456789abcdef";
                let e = "";
                for (let r = 0; r < 36; r++) e += 8 === r || 13 === r || 18 === r || 23 === r ? "-" : 14 === r ? "4" : 19 === r ? t[(4 * Math.random()) | 8] : t[(16 * Math.random()) | 0];
                return e;
            }
            static loadClearSaleScript(t, e) {
                return new Promise((r, i) => {
                    (window.CsdpObject = "csdp"),
                        (window.csdp =
                            window.csdp ||
                            function () {
                                (window.csdp.q = window.csdp.q || []).push(arguments);
                            }),
                        (window.csdp.l = 1 * new Date());
                    const n = document.createElement("script");
                    (n.async = !0),
                        (n.src = "https://device.clearsale.com.br/p/fp.js"),
                        (n.onload = () => {
                            window.csdp("app", t), window.csdp("sessionid", e), r();
                        }),
                        (n.onerror = (t) => {
                            i(t);
                        });
                    const s = document.getElementsByTagName("script")[0];
                    s.parentNode.insertBefore(n, s);
                });
            }
            static loadClearSaleScriptNode = (e, i) =>
                new Promise((n, s) => {
                    (r.g.CsdpObject = "csdp"),
                        (r.g.csdp =
                            r.g.csdp ||
                            function () {
                                (r.g.csdp.q = r.g.csdp.q || []).push(arguments);
                            }),
                        (r.g.csdp.l = 1 * new Date());
                    try {
                        const { JSDOM: t } = r(860),
                            o = new t("<!DOCTYPE html><body></body>", { runScripts: "dangerously" });
                        (o.window.onload = () => {
                            r.g.csdp("app", e), r.g.csdp("sessionid", i), n();
                        }),
                            (o.window.onerror = (t) => {
                                s(t);
                            }),
                            fetch("https://device.clearsale.com.br/p/fp.js")
                                .then((t) => {
                                    const e = o.window.document.createElement("script");
                                    (e.async = !0), (e.textContent = t.data), o.window.document.body.appendChild(e);
                                })
                                .catch((t) => {
                                    s(t);
                                });
                    } catch (e) {
                        throw new t({ code: 500, error: "library_error", error_description: 'Biblioteca "jsdom" não está instalada. Execute `npm i jsdom` ou `yarn add jsdom`' });
                    }
                });
        }
        class d {
            static async encryptCardData(e, i, n, s = !1, a = !1, h) {
                if (!o.isBrowser())
                    try {
                        const { JSDOM: t } = r(860),
                            e = new t("<!doctype html><html><body></body></html>"),
                            { window: i } = e;
                        r.g.window = i;
                    } catch (e) {
                        throw new t({ code: 500, error: "library_error", error_description: 'Biblioteca "jsdom" não está instalada. Execute `npm i jsdom` ou `yarn add jsdom`' });
                    }
                const c = r(963);
                try {
                    "production" === h && (e.salt = i), s && console.info("creditCardData", a ? e : "success");
                    const t = JSON.stringify(e),
                        r = new c();
                    return await r.setPublicKey(n), await r.encrypt(t);
                } catch (e) {
                    throw (s && console.error("Error_encryptCardData", e), new t({ code: 500, error: "encrypt_error", error_description: "Erro ao criptografar os dados do cartão" }));
                }
            }
        }
        class p {
            static debug;
            static result;
            static accountIdentifier;
            static environment;
            static creditCard;
            static brand;
            static total;
            static cardNumber;
            static salt;
            static publicKey;
            static debugger(t, r = !1) {
                try {
                    return (this.debug = e.debugger(t)), (this.result = e.debugger(r)), this.debug && (console.info("Debugger:", !0), console.info("Lib version:", l().lib.version)), this;
                } catch (t) {
                    (this.debug = !1), (this.result = !1);
                }
            }
            static setAccount(r) {
                try {
                    return (this.accountIdentifier = e.accountIdentifier(r)), this;
                } catch (e) {
                    throw (this.debug && console.error("Error_setAccount", e), new t(e));
                }
            }
            static setEnvironment(r) {
                try {
                    return (this.environment = e.environment(r)), this.debug && console.info("Environment", this.environment), this;
                } catch (e) {
                    throw (this.debug && console.error("Error_setEnvironment", e), new t(e));
                }
            }
            static setCreditCardData(r) {
                try {
                    return (this.creditCard = e.creditCard(r)), this;
                } catch (e) {
                    throw (this.debug && console.error("Error_setCreditCardData", e), new t(e));
                }
            }
            static setBrand(r) {
                try {
                    return (this.brand = e.brand(r)), this;
                } catch (e) {
                    throw (this.debug && console.error("Error_setBrand", e), new t(e));
                }
            }
            static setTotal(r) {
                try {
                    return (this.total = e.total(r)), this;
                } catch (e) {
                    throw (this.debug && console.error("Error_setTotal", e), new t(e));
                }
            }
            static setCardNumber(t) {
                return (this.cardNumber = t), this;
            }
            static async getPaymentToken() {
                try {
                    if (this.environment === "production") {
                        this.salt = await a.getSalt(this.accountIdentifier, this.debug);
                    } else {
                        this.salt = "sandbox_salt";
                    }

                    if (this.debug) {
                        console.log("salt:", this.result ? this.salt : "success");
                    }

                    this.publicKey = await h.getPublicKey(this.accountIdentifier, this.environment, this.debug);

                    if (this.debug) {
                        console.log("publicKey:", this.result ? this.publicKey : "success");
                    }

                    const encryptedCardData = await d.encryptCardData(this.creditCard, this.salt, this.publicKey, this.debug, this.result, this.environment);

                    if (this.debug) {
                        console.log("cardDataEncripted:", this.result ? encryptedCardData : "success");
                    }

                    const fingerPrint = await f.getFingerPrint(this.debug);

                    if (this.debug) {
                        console.log("getFingerPrint:", this.result ? fingerPrint : "success");
                    }

                    return await c.getPaymentToken(this.accountIdentifier, fingerPrint, encryptedCardData, this.environment, this.debug);
                } catch (error) {
                    if (this.debug) {
                        console.error("Error_getPaymentToken", error);
                    }
                    throw new t(error);
                }
            }

            static async getInstallments() {
                try {
                    const t = await s.getInstallments(this.accountIdentifier, this.brand, this.total, this.environment, this.debug);
                    return this.debug && console.log("installmentsData:", this.result ? t : "success"), t;
                } catch (e) {
                    throw (this.debug && console.error("Error_getInstallments", e), new t(e));
                }
            }
            static async verifyCardBrand() {
                try {
                    const t = await n.getBrand(this.cardNumber);
                    return this.debug && console.log("brand:", t), t;
                } catch (e) {
                    throw (this.debug && console.error("Error_verifyCardBrand", e), new t(e));
                }
            }
        }
    })(),
        (self.EfiJs = i);
})();
