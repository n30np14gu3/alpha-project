
! function(a, b, c, d) {
    function e(b, c) {
        var d = this;
        return this.options = a.extend({}, g, c), this.currentSlide = 0, this.cssSupport = this.css.isSupported("transition") && this.css.isSupported("transform") ? !0 : !1, this.offset = this.options.circular ? 2 : 0, this.options.beforeInit.call(this), this.parent = b, this.init(), this.play(), this.options.afterInit.call(this), {
            current: function() {
                return -d.currentSlide + 1
            },
            reinit: function() {
                d.init()
            },
            play: function() {
                d.play()
            },
            pause: function() {
                d.pause()
            },
            next: function(a) {
                d.slide(1, !1, a)
            },
            prev: function(a) {
                d.slide(-1, !1, a)
            },
            jump: function(a, b) {
                d.slide(a - 1, !0, b)
            },
            nav: function(a) {
                d.navigation.wrapper && d.navigation.wrapper.remove(), d.options.navigation = a ? a : d.options.navigation, d.navigation()
            },
            arrows: function(a) {
                d.arrows.wrapper && d.arrows.wrapper.remove(), d.options.arrows = a ? a : d.options.arrows, d.arrows()
            }
        }
    }
    var f = "glide",
        g = {
            autoplay: 4e3,
            hoverpause: !0,
            circular: !0,
            animationDuration: 1000,
            animationTimingFunc: "cubic-bezier(0.165, 0.840, 0.440, 1.000)",
            arrows: !0,
            arrowsWrapperClass: "slider-arrows",
            arrowMainClass: "slider-arrow ui icon angle black",
            arrowRightClass: "slider-arrow--right right",
            arrowRightText: "next",
            arrowLeftClass: "slider-arrow--left left",
            arrowLeftText: "prev",
            navigation: !0,
            navigationCenter: !0,
            navigationClass: "slider-nav",
            navigationItemClass: "slider-nav__item",
            navigationCurrentItemClass: "slider-nav__item--current",
            keyboard: !0,
            touchDistance: 60,
            beforeInit: function() {},
            afterInit: function() {},
            beforeTransition: function() {},
            afterTransition: function() {}
        };
    e.prototype.build = function() {
        this.bindings(), this.slides.length > 1 && (this.options.circular && this.circular(), this.options.arrows && this.arrows(), this.options.navigation && this.navigation()), this.events()
    }, e.prototype.circular = function() {
        this.firstClone = this.slides.filter(":first-child").clone().width(this.slides.spread), this.lastClone = this.slides.filter(":last-child").clone().width(this.slides.spread), this.wrapper.append(this.firstClone).prepend(this.lastClone).width(this.parent.width() * (this.slides.length + 2)).trigger("clearTransition").trigger("setTranslate", [-this.slides.spread])
    }, e.prototype.navigation = function() {
        this.navigation.items = {}, this.navigation.wrapper = a("<div />", {
            "class": this.options.navigationClass
        }).appendTo(this.options.navigation === !0 ? this.parent : this.options.navigation);
        for (var b = 0; b < this.slides.length; b++) this.navigation.items[b] = a("<a />", {
            href: "#",
            "class": this.options.navigationItemClass,
            "data-distance": b
        }).appendTo(this.navigation.wrapper);
        this.navigation.items[0].addClass(this.options.navigationCurrentItemClass), this.options.navigationCenter && this.navigation.wrapper.css({
            left: "50%",
            width: this.navigation.wrapper.children().outerWidth(!0) * this.navigation.wrapper.children().length,
            "margin-left": -(this.navigation.wrapper.outerWidth(!0) / 2)
        })
    }, e.prototype.arrows = function() {
        this.arrows.wrapper = a("<div />", {
            "class": this.options.arrowsWrapperClass
        }).appendTo(this.options.arrows === !0 ? this.parent : this.options.arrows), this.arrows.right = a("<i />", {
            href: "#",
            "class": this.options.arrowMainClass + " " + this.options.arrowRightClass,
            "data-distance": "1",
            html: this.options.arrowRightText
        }).appendTo(this.arrows.wrapper), this.arrows.left = a("<i />", {
            href: "#",
            "class": this.options.arrowMainClass + " " + this.options.arrowLeftClass,
            "data-distance": "-1",
            html: this.options.arrowLeftText
        }).appendTo(this.arrows.wrapper)
    }, e.prototype.bindings = function() {
        var b = this,
            c = this.options,
            d = this.css.getPrefix();
        this.wrapper.bind({
            setTransition: function() {
                a(this).css(d + "transition", d + "transform " + c.animationDuration + "ms " + c.animationTimingFunc)
            },
            clearTransition: function() {
                a(this).css(d + "transition", "none")
            },
            setTranslate: function(c, e) {
                b.cssSupport ? a(this).css(d + "transform", "translate3d(" + e + "px, 0px, 0px)") : a(this).css("margin-left", e)
            }
        })
    }, e.prototype.events = function() {
        this.options.touchDistance && this.parent.on({
            "touchstart MSPointerDown": a.proxy(this.events.touchstart, this),
            "touchmove MSPointerMove": a.proxy(this.events.touchmove, this),
            "touchend MSPointerUp": a.proxy(this.events.touchend, this)
        }), this.arrows.wrapper && a(this.arrows.wrapper).children().on("click touchstart", a.proxy(this.events.arrows, this)), this.navigation.wrapper && a(this.navigation.wrapper).children().on("click touchstart", a.proxy(this.events.navigation, this)), this.options.keyboard && a(c).on("keyup.glideKeyup", a.proxy(this.events.keyboard, this)), this.options.hoverpause && this.parent.on("mouseover mouseout", a.proxy(this.events.hover, this)), a(b).on("resize", a.proxy(this.events.resize, this))
    }, e.prototype.events.navigation = function(b) {
        this.wrapper.attr("disabled") || (b.preventDefault(), this.slide(a(b.currentTarget).data("distance"), !0))
    }, e.prototype.events.arrows = function(b) {
        this.wrapper.attr("disabled") || (b.preventDefault(), this.slide(a(b.currentTarget).data("distance"), !1))
    }, e.prototype.events.keyboard = function(a) {
        this.wrapper.attr("disabled") || (39 === a.keyCode && this.slide(1), 37 === a.keyCode && this.slide(-1))
    }, e.prototype.events.hover = function(a) {
        this.pause(), "mouseout" === a.type && this.play()
    }, e.prototype.events.resize = function() {
        this.dimensions(), this.slide(0)
    }, e.prototype.disableEvents = function() {
        this.wrapper.attr("disabled", !0)
    }, e.prototype.enableEvents = function() {
        this.wrapper.attr("disabled", !1)
    }, e.prototype.events.touchstart = function(a) {
        var b = a.originalEvent.touches[0] || a.originalEvent.changedTouches[0];
        this.events.touchStartX = b.pageX, this.events.touchStartY = b.pageY, this.events.touchSin = null
    }, e.prototype.events.touchmove = function(a) {
        var b = a.originalEvent.touches[0] || a.originalEvent.changedTouches[0],
            c = b.pageX - this.events.touchStartX,
            d = b.pageY - this.events.touchStartY,
            e = Math.abs(c << 2),
            f = Math.abs(d << 2),
            g = Math.sqrt(e + f),
            h = Math.sqrt(f);
        this.events.touchSin = Math.asin(h / g), this.events.touchSin * (180 / Math.PI) < 45 && a.preventDefault()
    }, e.prototype.events.touchend = function(a) {
        var b = a.originalEvent.touches[0] || a.originalEvent.changedTouches[0],
            c = b.pageX - this.events.touchStartX;
        c > this.options.touchDistance && this.events.touchSin * (180 / Math.PI) < 45 ? this.slide(-1) : c < -this.options.touchDistance && this.events.touchSin * (180 / Math.PI) < 45 && this.slide(1)
    }, e.prototype.slide = function(b, c, d) {
        this.pause(), this.options.beforeTransition.call(this);
        var e = this,
            f = c ? 0 : this.currentSlide,
            g = -(this.slides.length - 1),
            h = !1,
            i = !1;
        0 === f && -1 === b ? (h = !0, f = g) : f === g && 1 === b ? (i = !0, f = 0) : f += -b;
        var j = this.slides.spread * f;
        this.options.circular && (j -= this.slides.spread, (i || h) && this.disableEvents(), i && (j = this.slides.spread * (g - 2)), h && (j = 0)), this.cssSupport ? this.wrapper.trigger("setTransition").trigger("setTranslate", [j]) : this.wrapper.stop().animate({
            "margin-left": j
        }, this.options.animationDuration), this.options.circular && ((h || i) && this.afterAnimation(function() {
            e.wrapper.trigger("clearTransition"), e.enableEvents()
        }), i && this.afterAnimation(function() {
            i = !1, e.wrapper.trigger("setTranslate", [-e.slides.spread])
        }), h && this.afterAnimation(function() {
            h = !1, e.wrapper.trigger("setTranslate", [e.slides.spread * (g - 1)])
        })), this.options.navigation && this.navigation.wrapper && a(this.parent).children("." + this.options.navigationClass).children().eq(-f).addClass(this.options.navigationCurrentItemClass).siblings().removeClass(this.options.navigationCurrentItemClass), this.currentSlide = f, this.afterAnimation(function() {
            e.options.afterTransition.call(e), "undefined" !== d && "function" == typeof d && d()
        }), this.play()
    }, e.prototype.play = function() {
        var a = this;
        this.options.autoplay && (this.auto = setInterval(function() {
            a.slide(1, !1)
        }, this.options.autoplay))
    }, e.prototype.pause = function() {
        this.options.autoplay && (this.auto = clearInterval(this.auto))
    }, e.prototype.afterAnimation = function(a) {
        setTimeout(function() {
            a()
        }, this.options.animationDuration + 10)
    }, e.prototype.dimensions = function() {
        this.slides.spread = this.parent.width(), this.wrapper.width(this.slides.spread * (this.slides.length + this.offset)), this.slides.add(this.firstClone).add(this.lastClone).width(this.slides.spread)
    }, e.prototype.init = function() {
        this.wrapper = this.parent.children(), this.slides = this.wrapper.children(), this.dimensions(), this.build()
    }, e.prototype.css = {
        isSupported: function(a) {
            var e = !1,
                f = "Khtml ms O Moz Webkit".split(" "),
                g = c.createElement("div"),
                h = null;
            if (a = a.toLowerCase(), g.style[a] !== d && (e = !0), e === !1) {
                h = a.charAt(0).toUpperCase() + a.substr(1);
                for (var i = 0; i < f.length; i++)
                    if (g.style[f[i] + h] !== d) {
                        e = !0;
                        break
                    }
            }
            return b.opera && b.opera.version() < 13 && (e = !1), ("undefined" === e || e === d) && (e = !1), e
        },
        getPrefix: function() {
            if (!b.getComputedStyle) return "";
            var a = b.getComputedStyle(c.documentElement, "");
            return "-" + (Array.prototype.slice.call(a).join("").match(/-(moz|webkit|ms)-/) || "" === a.OLink && ["", "o"])[1] + "-"
        }
    }, a.fn[f] = function(b) {
        return this.each(function() {
            a.data(this, "api_" + f) || a.data(this, "api_" + f, new e(a(this), b))
        })
    }
}(jQuery, window, document);
