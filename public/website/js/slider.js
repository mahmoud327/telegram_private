jQuery.fn.rbtSlider = function(a) {
    return this.each(function() {
        function f(a, c) {
            c ? nextItem = b.find(".slItem").eq(c - 1) : ("prev" == a ? b.find(".slItem.active").prev().length ? nextItem = b.find(".slItem.active").prev() : nextItem = b.find(".slItem").last() : b.find(".slItem.active").next().length ? nextItem = b.find(".slItem.active").next() : nextItem = b.find(".slItem").first(), b.find(".slDots > .active").removeClass("active").parent().find(".slDotsSingle").eq(nextItem.index()).addClass("active")), nextItem.addClass(a + "Item").delay(500).queue(function() {
                b.find(".slItems > .active").addClass(a).delay(700).queue(function() {
                    $(this).removeClass(a + " active").dequeue()
                }), $(this).addClass(a).delay(500).queue(function() {
                    $('.slItem .slText').css('display', 'none')
                    $(this).find('.slText').slideDown(1000)
                    $(this).removeClass(a + " " + a + "Item").addClass("active").clearQueue()
                }), $(this).dequeue()
            })
        }
        var b = $(this);
        if (a.height && b.css("height", a.height), b.find(".slItem").first().addClass("active"), a.dots) {
            var c = b.find(".slItem").length;
            b.append($("<div/>", {
                "class": "slDots",
                html: $("<div/>", {
                    "class": "slDotsSingle active"
                })
            }));
            for (var d = 1; c > d; d++) b.find(".slDotsSingle.active").clone().removeClass("active").appendTo($(this).find(".slDots"));
            b.find(".slDotsSingle").on("click", function() {
                curIndex = $(this).parents(".slDots").find(".active").removeClass("active").index() + 1, index = $(this).addClass("active").index() + 1, index != curIndex && (index > curIndex ? f("next", index) : f("prev", index))
            })
        }
        if (a.arrows && b.append($("<div/>", {
                "class": "ctrlPrev",
                html: "&lsaquo;"
            }).on("click", function() {
                f("prev")
            })).append($("<div/>", {
                "class": "ctrlNext",
                html: "&rsaquo;"
            }).on("click", function() {
                f("next")
            })), a.auto) {
            var e = setInterval(function() {
                f("next")
            }, 7000);
            
        }
    })
};