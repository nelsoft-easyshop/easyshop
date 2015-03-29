(function ($) {

    // Avoid `console` errors in browsers that lack a console.
    (function() {
        var e;
        var d = function d() {};
        var b = ["assert", "clear", "count", "debug", "dir", "dirxml", "error", "exception", "group", "groupCollapsed", "groupEnd", "info", "log", "markTimeline", "profile", "profileEnd", "table", "time", "timeEnd", "timeStamp", "trace", "warn"];
        var c = b.length;
        var a = (window.console = window.console || {});
        while (c--) {
            e = b[c];
            if (!a[e]) {
                a[e] = d
            }
        }
    }());

    (function(e) {
        function t() {
            var e = location.href;
            hashtag = e.indexOf("#prettyPhoto") !== -1 ? decodeURI(e.substring(e.indexOf("#prettyPhoto") + 1, e.length)) : false;
            return hashtag
        }

        function n() {
            if (typeof theRel == "undefined") return;
            location.hash = theRel + "/" + rel_index + "/"
        }

        function r() {
            if (location.href.indexOf("#prettyPhoto") !== -1) location.hash = "prettyPhoto"
        }

        function i(e, t) {
            e = e.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
            var n = "[\\?&]" + e + "=([^&#]*)";
            var r = new RegExp(n);
            var i = r.exec(t);
            return i == null ? "" : i[1]
        }
        e.prettyPhoto = {
            version: "3.1.5"
        };
        e.fn.prettyPhoto = function(s) {
            function g() {
                e(".pp_loaderIcon").hide();
                projectedTop = scroll_pos["scrollTop"] + (d / 2 - a["containerHeight"] / 2);
                if (projectedTop < 0) projectedTop = 0;
                $ppt.fadeTo(settings.animation_speed, 1);
                $pp_pic_holder.find(".pp_content").animate({
                    height: a["contentHeight"],
                    width: a["contentWidth"]
                }, settings.animation_speed);
                $pp_pic_holder.animate({
                    top: projectedTop,
                    left: v / 2 - a["containerWidth"] / 2 < 0 ? 0 : v / 2 - a["containerWidth"] / 2,
                    width: a["containerWidth"]
                }, settings.animation_speed, function() {
                    $pp_pic_holder.find(".pp_hoverContainer,#fullResImage").height(a["height"]).width(a["width"]);
                    $pp_pic_holder.find(".pp_fade").fadeIn(settings.animation_speed);
                    if (isSet && S(pp_images[set_position]) == "image") {
                        $pp_pic_holder.find(".pp_hoverContainer").show()
                    } else {
                        $pp_pic_holder.find(".pp_hoverContainer").hide()
                    }
                    if (settings.allow_expand) {
                        if (a["resized"]) {
                            e("a.pp_expand,a.pp_contract").show()
                        } else {
                            e("a.pp_expand").hide()
                        }
                    }
                    if (settings.autoplay_slideshow && !m && !f) e.prettyPhoto.startSlideshow();
                    settings.changepicturecallback();
                    f = true
                });
                C();
                s.ajaxcallback()
            }

            function y(t) {
                $pp_pic_holder.find("#pp_full_res object,#pp_full_res embed").css("visibility", "hidden");
                $pp_pic_holder.find(".pp_fade").fadeOut(settings.animation_speed, function() {
                    e(".pp_loaderIcon").show();
                    t()
                })
            }

            function b(t) {
                t > 1 ? e(".pp_nav").show() : e(".pp_nav").hide()
            }

            function w(e, t) {
                resized = false;
                E(e, t);
                imageWidth = e, imageHeight = t;
                if ((p > v || h > d) && doresize && settings.allow_resize && !u) {
                    resized = true, fitting = false;
                    while (!fitting) {
                        if (p > v) {
                            imageWidth = v - 200;
                            imageHeight = t / e * imageWidth
                        } else if (h > d) {
                            imageHeight = d - 200;
                            imageWidth = e / t * imageHeight
                        } else {
                            fitting = true
                        }
                        h = imageHeight, p = imageWidth
                    }
                    if (p > v || h > d) {
                        w(p, h)
                    }
                    E(imageWidth, imageHeight)
                }
                return {
                    width: Math.floor(imageWidth),
                    height: Math.floor(imageHeight),
                    containerHeight: Math.floor(h),
                    containerWidth: Math.floor(p) + settings.horizontal_padding * 2,
                    contentHeight: Math.floor(l),
                    contentWidth: Math.floor(c),
                    resized: resized
                }
            }

            function E(t, n) {
                t = parseFloat(t);
                n = parseFloat(n);
                $pp_details = $pp_pic_holder.find(".pp_details");
                $pp_details.width(t);
                detailsHeight = parseFloat($pp_details.css("marginTop")) + parseFloat($pp_details.css("marginBottom"));
                $pp_details = $pp_details.clone().addClass(settings.theme).width(t).appendTo(e("body")).css({
                    position: "absolute",
                    top: -1e4
                });
                detailsHeight += $pp_details.height();
                detailsHeight = detailsHeight <= 34 ? 36 : detailsHeight;
                $pp_details.remove();
                $pp_title = $pp_pic_holder.find(".ppt");
                $pp_title.width(t);
                titleHeight = parseFloat($pp_title.css("marginTop")) + parseFloat($pp_title.css("marginBottom"));
                $pp_title = $pp_title.clone().appendTo(e("body")).css({
                    position: "absolute",
                    top: -1e4
                });
                titleHeight += $pp_title.height();
                $pp_title.remove();
                l = n + detailsHeight;
                c = t;
                h = l + titleHeight + $pp_pic_holder.find(".pp_top").height() + $pp_pic_holder.find(".pp_bottom").height();
                p = t
            }

            function S(e) {
                if (e.match(/youtube\.com\/watch/i) || e.match(/youtu\.be/i)) {
                    return "youtube"
                } else if (e.match(/vimeo\.com/i)) {
                    return "vimeo"
                } else if (e.match(/\b.mov\b/i)) {
                    return "quicktime"
                } else if (e.match(/\b.swf\b/i)) {
                    return "flash"
                } else if (e.match(/\biframe=true\b/i)) {
                    return "iframe"
                } else if (e.match(/\bajax=true\b/i)) {
                    return "ajax"
                } else if (e.match(/\bcustom=true\b/i)) {
                    return "custom"
                } else if (e.substr(0, 1) == "#") {
                    return "inline"
                } else {
                    return "image"
                }
            }

            function x() {
                if (doresize && typeof $pp_pic_holder != "undefined") {
                    scroll_pos = T();
                    contentHeight = $pp_pic_holder.height(), contentwidth = $pp_pic_holder.width();
                    projectedTop = d / 2 + scroll_pos["scrollTop"] - contentHeight / 2;
                    if (projectedTop < 0) projectedTop = 0;
                    if (contentHeight > d) return;
                    $pp_pic_holder.css({
                        top: projectedTop,
                        left: v / 2 + scroll_pos["scrollLeft"] - contentwidth / 2
                    })
                }
            }

            function T() {
                if (self.pageYOffset) {
                    return {
                        scrollTop: self.pageYOffset,
                        scrollLeft: self.pageXOffset
                    }
                } else if (document.documentElement && document.documentElement.scrollTop) {
                    return {
                        scrollTop: document.documentElement.scrollTop,
                        scrollLeft: document.documentElement.scrollLeft
                    }
                } else if (document.body) {
                    return {
                        scrollTop: document.body.scrollTop,
                        scrollLeft: document.body.scrollLeft
                    }
                }
            }

            function N() {
                d = e(window).height(), v = e(window).width();
                if (typeof $pp_overlay != "undefined") $pp_overlay.height(e(document).height()).width(v)
            }

            function C() {
                if (isSet && settings.overlay_gallery && S(pp_images[set_position]) == "image") {
                    itemWidth = 52 + 5;
                    navWidth = settings.theme == "facebook" || settings.theme == "pp_default" ? 50 : 30;
                    itemsPerPage = Math.floor((a["containerWidth"] - 100 - navWidth) / itemWidth);
                    itemsPerPage = itemsPerPage < pp_images.length ? itemsPerPage : pp_images.length;
                    totalPage = Math.ceil(pp_images.length / itemsPerPage) - 1;
                    if (totalPage == 0) {
                        navWidth = 0;
                        $pp_gallery.find(".pp_arrow_next,.pp_arrow_previous").hide()
                    } else {
                        $pp_gallery.find(".pp_arrow_next,.pp_arrow_previous").show()
                    }
                    galleryWidth = itemsPerPage * itemWidth;
                    fullGalleryWidth = pp_images.length * itemWidth;
                    $pp_gallery.css("margin-left", -(galleryWidth / 2 + navWidth / 2)).find("div:first").width(galleryWidth + 5).find("ul").width(fullGalleryWidth).find("li.selected").removeClass("selected");
                    goToPage = Math.floor(set_position / itemsPerPage) < totalPage ? Math.floor(set_position / itemsPerPage) : totalPage;
                    e.prettyPhoto.changeGalleryPage(goToPage);
                    $pp_gallery_li.filter(":eq(" + set_position + ")").addClass("selected")
                } else {
                    $pp_pic_holder.find(".pp_content").unbind("mouseenter mouseleave")
                }
            }

            function k(t) {
                if (settings.social_tools) facebook_like_link = settings.social_tools.replace("{location_href}", encodeURIComponent(location.href));
                settings.markup = settings.markup.replace("{pp_social}", "");
                e("body").append(settings.markup);
                $pp_pic_holder = e(".pp_pic_holder"), $ppt = e(".ppt"), $pp_overlay = e("div.pp_overlay");
                if (isSet && settings.overlay_gallery) {
                    currentGalleryPage = 0;
                    toInject = "";
                    for (var n = 0; n < pp_images.length; n++) {
                        if (!pp_images[n].match(/\b(jpg|jpeg|png|gif)\b/gi)) {
                            classname = "default";
                            img_src = ""
                        } else {
                            classname = "";
                            img_src = pp_images[n]
                        }
                        toInject += "<li class='" + classname + "'><a href='#'><img src='" + img_src + "' width='50' alt='' /></a></li>"
                    }
                    toInject = settings.gallery_markup.replace(/{gallery}/g, toInject);
                    $pp_pic_holder.find("#pp_full_res").after(toInject);
                    $pp_gallery = e(".pp_pic_holder .pp_gallery"), $pp_gallery_li = $pp_gallery.find("li");
                    $pp_gallery.find(".pp_arrow_next").click(function() {
                        e.prettyPhoto.changeGalleryPage("next");
                        e.prettyPhoto.stopSlideshow();
                        return false
                    });
                    $pp_gallery.find(".pp_arrow_previous").click(function() {
                        e.prettyPhoto.changeGalleryPage("previous");
                        e.prettyPhoto.stopSlideshow();
                        return false
                    });
                    $pp_pic_holder.find(".pp_content").hover(function() {
                        $pp_pic_holder.find(".pp_gallery:not(.disabled)").fadeIn()
                    }, function() {
                        $pp_pic_holder.find(".pp_gallery:not(.disabled)").fadeOut()
                    });
                    itemWidth = 52 + 5;
                    $pp_gallery_li.each(function(t) {
                        e(this).find("a").click(function() {
                            e.prettyPhoto.changePage(t);
                            e.prettyPhoto.stopSlideshow();
                            return false
                        })
                    })
                }
                if (settings.slideshow) {
                    $pp_pic_holder.find(".pp_nav").prepend('<a href="#" class="pp_play">Play</a>');
                    $pp_pic_holder.find(".pp_nav .pp_play").click(function() {
                        e.prettyPhoto.startSlideshow();
                        return false
                    })
                }
                $pp_pic_holder.attr("class", "pp_pic_holder " + settings.theme);
                $pp_overlay.css({
                    opacity: 0,
                    height: e(document).height(),
                    width: e(window).width()
                }).bind("click", function() {
                    if (!settings.modal) e.prettyPhoto.close()
                });
                e("a.pp_close").bind("click", function() {
                    e.prettyPhoto.close();
                    return false
                });
                if (settings.allow_expand) {
                    e("a.pp_expand").bind("click", function(t) {
                        if (e(this).hasClass("pp_expand")) {
                            e(this).removeClass("pp_expand").addClass("pp_contract");
                            doresize = false
                        } else {
                            e(this).removeClass("pp_contract").addClass("pp_expand");
                            doresize = true
                        }
                        y(function() {
                            e.prettyPhoto.open()
                        });
                        return false
                    })
                }
                $pp_pic_holder.find(".pp_previous, .pp_nav .pp_arrow_previous").bind("click", function() {
                    e.prettyPhoto.changePage("previous");
                    e.prettyPhoto.stopSlideshow();
                    return false
                });
                $pp_pic_holder.find(".pp_next, .pp_nav .pp_arrow_next").bind("click", function() {
                    e.prettyPhoto.changePage("next");
                    e.prettyPhoto.stopSlideshow();
                    return false
                });
                x()
            }
            s = jQuery.extend({
                hook: "rel",
                animation_speed: "fast",
                ajaxcallback: function() {},
                slideshow: 5e3,
                autoplay_slideshow: false,
                opacity: .8,
                show_title: true,
                allow_resize: true,
                allow_expand: true,
                default_width: 500,
                default_height: 344,
                counter_separator_label: "/",
                theme: "pp_default",
                horizontal_padding: 20,
                hideflash: false,
                wmode: "opaque",
                autoplay: true,
                modal: false,
                deeplinking: true,
                overlay_gallery: true,
                overlay_gallery_max: 30,
                keyboard_shortcuts: true,
                changepicturecallback: function() {},
                callback: function() {},
                ie6_fallback: true,
                markup: '<div class="pp_pic_holder"> 						<div class="ppt"> </div> 						<div class="pp_top"> 							<div class="pp_left"></div> 							<div class="pp_middle"></div> 							<div class="pp_right"></div> 						</div> 						<div class="pp_content_container"> 							<div class="pp_left"> 							<div class="pp_right"> 								<div class="pp_content"> 									<div class="pp_loaderIcon"></div> 									<div class="pp_fade"> 										<a href="#" class="pp_expand" title="Expand the image">Expand</a> 										<div class="pp_hoverContainer"> 											<a class="pp_next" href="#">next</a> 											<a class="pp_previous" href="#">previous</a> 										</div> 										<div id="pp_full_res"></div> 										<div class="pp_details"> 											<div class="pp_nav"> 												<a href="#" class="pp_arrow_previous">Previous</a> 												<p class="currentTextHolder">0/0</p> 												<a href="#" class="pp_arrow_next">Next</a> 											</div> 											<p class="pp_description"></p> 											<div class="pp_social">{pp_social}</div> 											<a class="pp_close" href="#">Close</a> 										</div> 									</div> 								</div> 							</div> 							</div> 						</div> 						<div class="pp_bottom"> 							<div class="pp_left"></div> 							<div class="pp_middle"></div> 							<div class="pp_right"></div> 						</div> 					</div> 					<div class="pp_overlay"></div>',
                gallery_markup: '<div class="pp_gallery"> 								<a href="#" class="pp_arrow_previous">Previous</a> 								<div> 									<ul> 										{gallery} 									</ul> 								</div> 								<a href="#" class="pp_arrow_next">Next</a> 							</div>',
                image_markup: '<img id="fullResImage" src="{path}" />',
                flash_markup: '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="{width}" height="{height}"><param name="wmode" value="{wmode}" /><param name="allowfullscreen" value="true" /><param name="allowscriptaccess" value="always" /><param name="movie" value="{path}" /><embed src="{path}" type="application/x-shockwave-flash" allowfullscreen="true" allowscriptaccess="always" width="{width}" height="{height}" wmode="{wmode}"></embed></object>',
                quicktime_markup: '<object classid="clsid:02BF25D5-8C17-4B23-BC80-D3488ABDDC6B" codebase="http://www.apple.com/qtactivex/qtplugin.cab" height="{height}" width="{width}"><param name="src" value="{path}"><param name="autoplay" value="{autoplay}"><param name="type" value="video/quicktime"><embed src="{path}" height="{height}" width="{width}" autoplay="{autoplay}" type="video/quicktime" pluginspage="http://www.apple.com/quicktime/download/"></embed></object>',
                iframe_markup: '<iframe src ="{path}" width="{width}" height="{height}" frameborder="no"></iframe>',
                inline_markup: '<div class="pp_inline">{content}</div>',
                custom_markup: "",
                // social_tools: '<div class="twitter"><a href="http://twitter.com/share" class="twitter-share-button" data-count="none">Tweet</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script></div><div class="facebook"><iframe src="//www.facebook.com/plugins/like.php?locale=en_US&href={location_href}&layout=button_count&show_faces=true&width=500&action=like&font&colorscheme=light&height=23" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:500px; height:23px;" allowTransparency="true"></iframe></div>'
            }, s);
            var o = this,
                u = false,
                a, f, l, c, h, p, d = e(window).height(),
                v = e(window).width(),
                m;
            doresize = true, scroll_pos = T();
            e(window).unbind("resize.prettyphoto").bind("resize.prettyphoto", function() {
                x();
                N()
            });
            if (s.keyboard_shortcuts) {
                e(document).unbind("keydown.prettyphoto").bind("keydown.prettyphoto", function(t) {
                    if (typeof $pp_pic_holder != "undefined") {
                        if ($pp_pic_holder.is(":visible")) {
                            switch (t.keyCode) {
                                case 37:
                                    e.prettyPhoto.changePage("previous");
                                    t.preventDefault();
                                    break;
                                case 39:
                                    e.prettyPhoto.changePage("next");
                                    t.preventDefault();
                                    break;
                                case 27:
                                    if (!settings.modal) e.prettyPhoto.close();
                                    t.preventDefault();
                                    break
                            }
                        }
                    }
                })
            }
            e.prettyPhoto.initialize = function() {
                settings = s;
                if (settings.theme == "pp_default") settings.horizontal_padding = 16;
                theRel = e(this).attr(settings.hook);
                galleryRegExp = /\[(?:.*)\]/;
                isSet = galleryRegExp.exec(theRel) ? true : false;
                pp_images = isSet ? jQuery.map(o, function(t, n) {
                    if (e(t).attr(settings.hook).indexOf(theRel) != -1) return e(t).attr("href")
                }) : e.makeArray(e(this).attr("href"));
                pp_titles = isSet ? jQuery.map(o, function(t, n) {
                    if (e(t).attr(settings.hook).indexOf(theRel) != -1) return e(t).find("img").attr("alt") ? e(t).find("img").attr("alt") : ""
                }) : e.makeArray(e(this).find("img").attr("alt"));
                pp_descriptions = isSet ? jQuery.map(o, function(t, n) {
                    if (e(t).attr(settings.hook).indexOf(theRel) != -1) return e(t).attr("title") ? e(t).attr("title") : ""
                }) : e.makeArray(e(this).attr("title"));
                if (pp_images.length > settings.overlay_gallery_max) settings.overlay_gallery = false;
                set_position = jQuery.inArray(e(this).attr("href"), pp_images);
                rel_index = isSet ? set_position : e("a[" + settings.hook + "^='" + theRel + "']").index(e(this));
                k(this);
                if (settings.allow_resize) e(window).bind("scroll.prettyphoto", function() {
                    x()
                });
                e.prettyPhoto.open();
                return false
            };
            e.prettyPhoto.open = function(t) {
                if (typeof settings == "undefined") {
                    settings = s;
                    pp_images = e.makeArray(arguments[0]);
                    pp_titles = arguments[1] ? e.makeArray(arguments[1]) : e.makeArray("");
                    pp_descriptions = arguments[2] ? e.makeArray(arguments[2]) : e.makeArray("");
                    isSet = pp_images.length > 1 ? true : false;
                    set_position = arguments[3] ? arguments[3] : 0;
                    k(t.target)
                }
                if (settings.hideflash) e("object,embed,iframe[src*=youtube],iframe[src*=vimeo]").css("visibility", "hidden");
                b(e(pp_images).size());
                e(".pp_loaderIcon").show();
                if (settings.deeplinking) n();
                if (settings.social_tools) {
                    facebook_like_link = settings.social_tools.replace("{location_href}", encodeURIComponent(location.href));
                    $pp_pic_holder.find(".pp_social").html(facebook_like_link)
                }
                if ($ppt.is(":hidden")) $ppt.css("opacity", 0).show();
                $pp_overlay.show().fadeTo(settings.animation_speed, settings.opacity);
                $pp_pic_holder.find(".currentTextHolder").text(set_position + 1 + settings.counter_separator_label + e(pp_images).size());
                if (typeof pp_descriptions[set_position] != "undefined" && pp_descriptions[set_position] != "") {
                    $pp_pic_holder.find(".pp_description").show().html(unescape(pp_descriptions[set_position]))
                } else {
                    $pp_pic_holder.find(".pp_description").hide()
                }
                movie_width = parseFloat(i("width", pp_images[set_position])) ? i("width", pp_images[set_position]) : settings.default_width.toString();
                movie_height = parseFloat(i("height", pp_images[set_position])) ? i("height", pp_images[set_position]) : settings.default_height.toString();
                u = false;
                if (movie_height.indexOf("%") != -1) {
                    movie_height = parseFloat(e(window).height() * parseFloat(movie_height) / 100 - 150);
                    u = true
                }
                if (movie_width.indexOf("%") != -1) {
                    movie_width = parseFloat(e(window).width() * parseFloat(movie_width) / 100 - 150);
                    u = true
                }
                $pp_pic_holder.fadeIn(function() {
                    settings.show_title && pp_titles[set_position] != "" && typeof pp_titles[set_position] != "undefined" ? $ppt.html(unescape(pp_titles[set_position])) : $ppt.html(" ");
                    imgPreloader = "";
                    skipInjection = false;
                    switch (S(pp_images[set_position])) {
                        case "image":
                            imgPreloader = new Image;
                            nextImage = new Image;
                            if (isSet && set_position < e(pp_images).size() - 1) nextImage.src = pp_images[set_position + 1];
                            prevImage = new Image;
                            if (isSet && pp_images[set_position - 1]) prevImage.src = pp_images[set_position - 1];
                            $pp_pic_holder.find("#pp_full_res")[0].innerHTML = settings.image_markup.replace(/{path}/g, pp_images[set_position]);
                            imgPreloader.onload = function() {
                                a = w(imgPreloader.width, imgPreloader.height);
                                g()
                            };
                            imgPreloader.onerror = function() {
                                alert("Image cannot be loaded. Make sure the path is correct and image exist.");
                                e.prettyPhoto.close()
                            };
                            imgPreloader.src = pp_images[set_position];
                            break;
                        case "youtube":
                            a = w(movie_width, movie_height);
                            movie_id = i("v", pp_images[set_position]);
                            if (movie_id == "") {
                                movie_id = pp_images[set_position].split("youtu.be/");
                                movie_id = movie_id[1];
                                if (movie_id.indexOf("?") > 0) movie_id = movie_id.substr(0, movie_id.indexOf("?"));
                                if (movie_id.indexOf("&") > 0) movie_id = movie_id.substr(0, movie_id.indexOf("&"))
                            }
                            movie = "http://www.youtube.com/embed/" + movie_id;
                            i("rel", pp_images[set_position]) ? movie += "?rel=" + i("rel", pp_images[set_position]) : movie += "?rel=1";
                            if (settings.autoplay) movie += "&autoplay=1";
                            toInject = settings.iframe_markup.replace(/{width}/g, a["width"]).replace(/{height}/g, a["height"]).replace(/{wmode}/g, settings.wmode).replace(/{path}/g, movie);
                            break;
                        case "vimeo":
                            a = w(movie_width, movie_height);
                            movie_id = pp_images[set_position];
                            var t = /http(s?):\/\/(www\.)?vimeo.com\/(\d+)/;
                            var n = movie_id.match(t);
                            movie = "http://player.vimeo.com/video/" + n[3] + "?title=0&byline=0&portrait=0";
                            if (settings.autoplay) movie += "&autoplay=1;";
                            vimeo_width = a["width"] + "/embed/?moog_width=" + a["width"];
                            toInject = settings.iframe_markup.replace(/{width}/g, vimeo_width).replace(/{height}/g, a["height"]).replace(/{path}/g, movie);
                            break;
                        case "quicktime":
                            a = w(movie_width, movie_height);
                            a["height"] += 15;
                            a["contentHeight"] += 15;
                            a["containerHeight"] += 15;
                            toInject = settings.quicktime_markup.replace(/{width}/g, a["width"]).replace(/{height}/g, a["height"]).replace(/{wmode}/g, settings.wmode).replace(/{path}/g, pp_images[set_position]).replace(/{autoplay}/g, settings.autoplay);
                            break;
                        case "flash":
                            a = w(movie_width, movie_height);
                            flash_vars = pp_images[set_position];
                            flash_vars = flash_vars.substring(pp_images[set_position].indexOf("flashvars") + 10, pp_images[set_position].length);
                            filename = pp_images[set_position];
                            filename = filename.substring(0, filename.indexOf("?"));
                            toInject = settings.flash_markup.replace(/{width}/g, a["width"]).replace(/{height}/g, a["height"]).replace(/{wmode}/g, settings.wmode).replace(/{path}/g, filename + "?" + flash_vars);
                            break;
                        case "iframe":
                            a = w(movie_width, movie_height);
                            frame_url = pp_images[set_position];
                            frame_url = frame_url.substr(0, frame_url.indexOf("iframe") - 1);
                            toInject = settings.iframe_markup.replace(/{width}/g, a["width"]).replace(/{height}/g, a["height"]).replace(/{path}/g, frame_url);
                            break;
                        case "ajax":
                            doresize = false;
                            a = w(movie_width, movie_height);
                            doresize = true;
                            skipInjection = true;
                            e.get(pp_images[set_position], function(e) {
                                toInject = settings.inline_markup.replace(/{content}/g, e);
                                $pp_pic_holder.find("#pp_full_res")[0].innerHTML = toInject;
                                g()
                            });
                            break;
                        case "custom":
                            a = w(movie_width, movie_height);
                            toInject = settings.custom_markup;
                            break;
                        case "inline":
                            myClone = e(pp_images[set_position]).clone().append('<br clear="all" />').css({
                                width: settings.default_width
                            }).wrapInner('<div id="pp_full_res"><div class="pp_inline"></div></div>').appendTo(e("body")).show();
                            doresize = false;
                            a = w(e(myClone).width(), e(myClone).height());
                            doresize = true;
                            e(myClone).remove();
                            toInject = settings.inline_markup.replace(/{content}/g, e(pp_images[set_position]).html());
                            break
                    }
                    if (!imgPreloader && !skipInjection) {
                        $pp_pic_holder.find("#pp_full_res")[0].innerHTML = toInject;
                        g()
                    }
                });
                return false
            };
            e.prettyPhoto.changePage = function(t) {
                currentGalleryPage = 0;
                if (t == "previous") {
                    set_position--;
                    if (set_position < 0) set_position = e(pp_images).size() - 1
                } else if (t == "next") {
                    set_position++;
                    if (set_position > e(pp_images).size() - 1) set_position = 0
                } else {
                    set_position = t
                }
                rel_index = set_position;
                if (!doresize) doresize = true;
                if (settings.allow_expand) {
                    e(".pp_contract").removeClass("pp_contract").addClass("pp_expand")
                }
                y(function() {
                    e.prettyPhoto.open()
                })
            };
            e.prettyPhoto.changeGalleryPage = function(e) {
                if (e == "next") {
                    currentGalleryPage++;
                    if (currentGalleryPage > totalPage) currentGalleryPage = 0
                } else if (e == "previous") {
                    currentGalleryPage--;
                    if (currentGalleryPage < 0) currentGalleryPage = totalPage
                } else {
                    currentGalleryPage = e
                }
                slide_speed = e == "next" || e == "previous" ? settings.animation_speed : 0;
                slide_to = currentGalleryPage * itemsPerPage * itemWidth;
                $pp_gallery.find("ul").animate({
                    left: -slide_to
                }, slide_speed)
            };
            e.prettyPhoto.startSlideshow = function() {
                if (typeof m == "undefined") {
                    $pp_pic_holder.find(".pp_play").unbind("click").removeClass("pp_play").addClass("pp_pause").click(function() {
                        e.prettyPhoto.stopSlideshow();
                        return false
                    });
                    m = setInterval(e.prettyPhoto.startSlideshow, settings.slideshow)
                } else {
                    e.prettyPhoto.changePage("next")
                }
            };
            e.prettyPhoto.stopSlideshow = function() {
                $pp_pic_holder.find(".pp_pause").unbind("click").removeClass("pp_pause").addClass("pp_play").click(function() {
                    e.prettyPhoto.startSlideshow();
                    return false
                });
                clearInterval(m);
                m = undefined
            };
            e.prettyPhoto.close = function() {
                if ($pp_overlay.is(":animated")) return;
                e.prettyPhoto.stopSlideshow();
                $pp_pic_holder.stop().find("object,embed").css("visibility", "hidden");
                e("div.pp_pic_holder,div.ppt,.pp_fade").fadeOut(settings.animation_speed, function() {
                    e(this).remove()
                });
                $pp_overlay.fadeOut(settings.animation_speed, function() {
                    if (settings.hideflash) e("object,embed,iframe[src*=youtube],iframe[src*=vimeo]").css("visibility", "visible");
                    e(this).remove();
                    e(window).unbind("scroll.prettyphoto");
                    r();
                    settings.callback();
                    doresize = true;
                    f = false;
                    delete settings
                })
            };
            if (!pp_alreadyInitialized && t()) {
                pp_alreadyInitialized = true;
                hashIndex = t();
                hashRel = hashIndex;
                hashIndex = hashIndex.substring(hashIndex.indexOf("/") + 1, hashIndex.length - 1);
                hashRel = hashRel.substring(0, hashRel.indexOf("/"));
                setTimeout(function() {
                    e("a[" + s.hook + "^='" + hashRel + "']:eq(" + hashIndex + ")").trigger("click")
                }, 50)
            }
            return this.unbind("click.prettyphoto").bind("click.prettyphoto", e.prettyPhoto.initialize)
        };
    })(jQuery);
    var pp_alreadyInitialized = false


        ! function(e) {
        "use strict";
        e(function() {
            e.support.transition = function() {
                var e = function() {
                    var e = document.createElement("bootstrap"),
                        t = {
                            WebkitTransition: "webkitTransitionEnd",
                            MozTransition: "transitionend",
                            OTransition: "oTransitionEnd otransitionend",
                            transition: "transitionend"
                        },
                        n;
                    for (n in t)
                        if (e.style[n] !== undefined) return t[n]
                }();
                return e && {
                    end: e
                }
            }()
        })
    }(window.jQuery), ! function(e) {
        "use strict";
        var t = '[data-dismiss="alert"]',
            n = function(n) {
                e(n).on("click", t, this.close)
            };
        n.prototype.close = function(t) {
            function s() {
                i.trigger("closed").remove()
            }
            var n = e(this),
                r = n.attr("data-target"),
                i;
            r || (r = n.attr("href"), r = r && r.replace(/.*(?=#[^\s]*$)/, "")), i = e(r), t && t.preventDefault(), i.length || (i = n.hasClass("alert") ? n : n.parent()), i.trigger(t = e.Event("close"));
            if (t.isDefaultPrevented()) return;
            i.removeClass("in"), e.support.transition && i.hasClass("fade") ? i.on(e.support.transition.end, s) : s()
        };
        var r = e.fn.alert;
        e.fn.alert = function(t) {
            return this.each(function() {
                var r = e(this),
                    i = r.data("alert");
                i || r.data("alert", i = new n(this)), typeof t == "string" && i[t].call(r)
            })
        }, e.fn.alert.Constructor = n, e.fn.alert.noConflict = function() {
            return e.fn.alert = r, this
        }, e(document).on("click.alert.data-api", t, n.prototype.close)
    }(window.jQuery), ! function(e) {
        "use strict";
        var t = function(t, n) {
            this.$element = e(t), this.options = e.extend({}, e.fn.button.defaults, n)
        };
        t.prototype.setState = function(e) {
            var t = "disabled",
                n = this.$element,
                r = n.data(),
                i = n.is("input") ? "val" : "html";
            e += "Text", r.resetText || n.data("resetText", n[i]()), n[i](r[e] || this.options[e]), setTimeout(function() {
                e == "loadingText" ? n.addClass(t).attr(t, t) : n.removeClass(t).removeAttr(t)
            }, 0)
        }, t.prototype.toggle = function() {
            var e = this.$element.closest('[data-toggle="buttons-radio"]');
            e && e.find(".active").removeClass("active"), this.$element.toggleClass("active")
        };
        var n = e.fn.button;
        e.fn.button = function(n) {
            return this.each(function() {
                var r = e(this),
                    i = r.data("button"),
                    s = typeof n == "object" && n;
                i || r.data("button", i = new t(this, s)), n == "toggle" ? i.toggle() : n && i.setState(n)
            })
        }, e.fn.button.defaults = {
            loadingText: "loading..."
        }, e.fn.button.Constructor = t, e.fn.button.noConflict = function() {
            return e.fn.button = n, this
        }, e(document).on("click.button.data-api", "[data-toggle^=button]", function(t) {
            var n = e(t.target);
            n.hasClass("btn") || (n = n.closest(".btn")), n.button("toggle")
        })
    }(window.jQuery), ! function(e) {
        "use strict";
        var t = function(t, n) {
            this.$element = e(t), this.$indicators = this.$element.find(".carousel-indicators"), this.options = n, this.options.pause == "hover" && this.$element.on("mouseenter", e.proxy(this.pause, this)).on("mouseleave", e.proxy(this.cycle, this))
        };
        t.prototype = {
            cycle: function(t) {
                return t || (this.paused = !1), this.interval && clearInterval(this.interval), this.options.interval && !this.paused && (this.interval = setInterval(e.proxy(this.next, this), this.options.interval)), this
            },
            getActiveIndex: function() {
                return this.$active = this.$element.find(".item.active"), this.$items = this.$active.parent().children(), this.$items.index(this.$active)
            },
            to: function(t) {
                var n = this.getActiveIndex(),
                    r = this;
                if (t > this.$items.length - 1 || t < 0) return;
                return this.sliding ? this.$element.one("slid", function() {
                    r.to(t)
                }) : n == t ? this.pause().cycle() : this.slide(t > n ? "next" : "prev", e(this.$items[t]))
            },
            pause: function(t) {
                return t || (this.paused = !0), this.$element.find(".next, .prev").length && e.support.transition.end && (this.$element.trigger(e.support.transition.end), this.cycle()), clearInterval(this.interval), this.interval = null, this
            },
            next: function() {
                if (this.sliding) return;
                return this.slide("next")
            },
            prev: function() {
                if (this.sliding) return;
                return this.slide("prev")
            },
            slide: function(t, n) {
                var r = this.$element.find(".item.active"),
                    i = n || r[t](),
                    s = this.interval,
                    o = t == "next" ? "left" : "right",
                    u = t == "next" ? "first" : "last",
                    a = this,
                    f;
                this.sliding = !0, s && this.pause(), i = i.length ? i : this.$element.find(".item")[u](), f = e.Event("slide", {
                    relatedTarget: i[0],
                    direction: o
                });
                if (i.hasClass("active")) return;
                this.$indicators.length && (this.$indicators.find(".active").removeClass("active"), this.$element.one("slid", function() {
                    var t = e(a.$indicators.children()[a.getActiveIndex()]);
                    t && t.addClass("active")
                }));
                if (e.support.transition && this.$element.hasClass("slide")) {
                    this.$element.trigger(f);
                    if (f.isDefaultPrevented()) return;
                    i.addClass(t), i[0].offsetWidth, r.addClass(o), i.addClass(o), this.$element.one(e.support.transition.end, function() {
                        i.removeClass([t, o].join(" ")).addClass("active"), r.removeClass(["active", o].join(" ")), a.sliding = !1, setTimeout(function() {
                            a.$element.trigger("slid")
                        }, 0)
                    })
                } else {
                    this.$element.trigger(f);
                    if (f.isDefaultPrevented()) return;
                    r.removeClass("active"), i.addClass("active"), this.sliding = !1, this.$element.trigger("slid")
                }
                return s && this.cycle(), this
            }
        };
        var n = e.fn.carousel;
        e.fn.carousel = function(n) {
            return this.each(function() {
                var r = e(this),
                    i = r.data("carousel"),
                    s = e.extend({}, e.fn.carousel.defaults, typeof n == "object" && n),
                    o = typeof n == "string" ? n : s.slide;
                i || r.data("carousel", i = new t(this, s)), typeof n == "number" ? i.to(n) : o ? i[o]() : s.interval && i.pause().cycle()
            })
        }, e.fn.carousel.defaults = {
            interval: 5e3,
            pause: "hover"
        }, e.fn.carousel.Constructor = t, e.fn.carousel.noConflict = function() {
            return e.fn.carousel = n, this
        }, e(document).on("click.carousel.data-api", "[data-slide], [data-slide-to]", function(t) {
            var n = e(this),
                r, i = e(n.attr("data-target") || (r = n.attr("href")) && r.replace(/.*(?=#[^\s]+$)/, "")),
                s = e.extend({}, i.data(), n.data()),
                o;
            i.carousel(s), (o = n.attr("data-slide-to")) && i.data("carousel").pause().to(o).cycle(), t.preventDefault()
        })
    }(window.jQuery), ! function(e) {
        "use strict";
        var t = function(t, n) {
            this.$element = e(t), this.options = e.extend({}, e.fn.collapse.defaults, n), this.options.parent && (this.$parent = e(this.options.parent)), this.options.toggle && this.toggle()
        };
        t.prototype = {
            constructor: t,
            dimension: function() {
                var e = this.$element.hasClass("width");
                return e ? "width" : "height"
            },
            show: function() {
                var t, n, r, i;
                if (this.transitioning || this.$element.hasClass("in")) return;
                t = this.dimension(), n = e.camelCase(["scroll", t].join("-")), r = this.$parent && this.$parent.find("> .accordion-group > .in");
                if (r && r.length) {
                    i = r.data("collapse");
                    if (i && i.transitioning) return;
                    r.collapse("hide"), i || r.data("collapse", null)
                }
                this.$element[t](0), this.transition("addClass", e.Event("show"), "shown"), e.support.transition && this.$element[t](this.$element[0][n])
            },
            hide: function() {
                var t;
                if (this.transitioning || !this.$element.hasClass("in")) return;
                t = this.dimension(), this.reset(this.$element[t]()), this.transition("removeClass", e.Event("hide"), "hidden"), this.$element[t](0)
            },
            reset: function(e) {
                var t = this.dimension();
                return this.$element.removeClass("collapse")[t](e || "auto")[0].offsetWidth, this.$element[e !== null ? "addClass" : "removeClass"]("collapse"), this
            },
            transition: function(t, n, r) {
                var i = this,
                    s = function() {
                        n.type == "show" && i.reset(), i.transitioning = 0, i.$element.trigger(r)
                    };
                this.$element.trigger(n);
                if (n.isDefaultPrevented()) return;
                this.transitioning = 1, this.$element[t]("in"), e.support.transition && this.$element.hasClass("collapse") ? this.$element.one(e.support.transition.end, s) : s()
            },
            toggle: function() {
                this[this.$element.hasClass("in") ? "hide" : "show"]()
            }
        };
        var n = e.fn.collapse;
        e.fn.collapse = function(n) {
            return this.each(function() {
                var r = e(this),
                    i = r.data("collapse"),
                    s = e.extend({}, e.fn.collapse.defaults, r.data(), typeof n == "object" && n);
                i || r.data("collapse", i = new t(this, s)), typeof n == "string" && i[n]()
            })
        }, e.fn.collapse.defaults = {
            toggle: !0
        }, e.fn.collapse.Constructor = t, e.fn.collapse.noConflict = function() {
            return e.fn.collapse = n, this
        }, e(document).on("click.collapse.data-api", "[data-toggle=collapse]", function(t) {
            var n = e(this),
                r, i = n.attr("data-target") || t.preventDefault() || (r = n.attr("href")) && r.replace(/.*(?=#[^\s]+$)/, ""),
                s = e(i).data("collapse") ? "toggle" : n.data();
            n[e(i).hasClass("in") ? "addClass" : "removeClass"]("collapsed"), e(i).collapse(s)
        })
    }(window.jQuery), ! function(e) {
        "use strict";

        function r() {
            e(t).each(function() {
                i(e(this)).removeClass("open")
            })
        }

        function i(t) {
            var n = t.attr("data-target"),
                r;
            n || (n = t.attr("href"), n = n && /#/.test(n) && n.replace(/.*(?=#[^\s]*$)/, "")), r = n && e(n);
            if (!r || !r.length) r = t.parent();
            return r
        }
        var t = "[data-toggle=dropdown]",
            n = function(t) {
                var n = e(t).on("click.dropdown.data-api", this.toggle);
                e("html").on("click.dropdown.data-api", function() {
                    n.parent().removeClass("open")
                })
            };
        n.prototype = {
            constructor: n,
            toggle: function(t) {
                var n = e(this),
                    s, o;
                if (n.is(".disabled, :disabled")) return;
                return s = i(n), o = s.hasClass("open"), r(), o || s.toggleClass("open"), n.focus(), !1
            },
            keydown: function(n) {
                var r, s, o, u, a, f;
                if (!/(38|40|27)/.test(n.keyCode)) return;
                r = e(this), n.preventDefault(), n.stopPropagation();
                if (r.is(".disabled, :disabled")) return;
                u = i(r), a = u.hasClass("open");
                if (!a || a && n.keyCode == 27) return n.which == 27 && u.find(t).focus(), r.click();
                s = e("[role=menu] li:not(.divider):visible a", u);
                if (!s.length) return;
                f = s.index(s.filter(":focus")), n.keyCode == 38 && f > 0 && f--, n.keyCode == 40 && f < s.length - 1 && f++, ~f || (f = 0), s.eq(f).focus()
            }
        };
        var s = e.fn.dropdown;
        e.fn.dropdown = function(t) {
            return this.each(function() {
                var r = e(this),
                    i = r.data("dropdown");
                i || r.data("dropdown", i = new n(this)), typeof t == "string" && i[t].call(r)
            })
        }, e.fn.dropdown.Constructor = n, e.fn.dropdown.noConflict = function() {
            return e.fn.dropdown = s, this
        }, e(document).on("click.dropdown.data-api", r).on("click.dropdown.data-api", ".dropdown form", function(e) {
            e.stopPropagation()
        }).on(".dropdown-menu", function(e) {
            e.stopPropagation()
        }).on("click.dropdown.data-api", t, n.prototype.toggle).on("keydown.dropdown.data-api", t + ", [role=menu]", n.prototype.keydown)
    }(window.jQuery), ! function(e) {
        "use strict";
        var t = function(t, n) {
            this.options = n, this.$element = e(t).delegate('[data-dismiss="modal"]', "click.dismiss.modal", e.proxy(this.hide, this)), this.options.remote && this.$element.find(".modal-body").load(this.options.remote)
        };
        t.prototype = {
            constructor: t,
            toggle: function() {
                return this[this.isShown ? "hide" : "show"]()
            },
            show: function() {
                var t = this,
                    n = e.Event("show");
                this.$element.trigger(n);
                if (this.isShown || n.isDefaultPrevented()) return;
                this.isShown = !0, this.escape(), this.backdrop(function() {
                    var n = e.support.transition && t.$element.hasClass("fade");
                    t.$element.parent().length || t.$element.appendTo(document.body), t.$element.show(), n && t.$element[0].offsetWidth, t.$element.addClass("in").attr("aria-hidden", !1), t.enforceFocus(), n ? t.$element.one(e.support.transition.end, function() {
                        t.$element.focus().trigger("shown")
                    }) : t.$element.focus().trigger("shown")
                })
            },
            hide: function(t) {
                t && t.preventDefault();
                var n = this;
                t = e.Event("hide"), this.$element.trigger(t);
                if (!this.isShown || t.isDefaultPrevented()) return;
                this.isShown = !1, this.escape(), e(document).off("focusin.modal"), this.$element.removeClass("in").attr("aria-hidden", !0), e.support.transition && this.$element.hasClass("fade") ? this.hideWithTransition() : this.hideModal()
            },
            enforceFocus: function() {
                var t = this;
                e(document).on("focusin.modal", function(e) {
                    t.$element[0] !== e.target && !t.$element.has(e.target).length && t.$element.focus()
                })
            },
            escape: function() {
                var e = this;
                this.isShown && this.options.keyboard ? this.$element.on("keyup.dismiss.modal", function(t) {
                    t.which == 27 && e.hide()
                }) : this.isShown || this.$element.off("keyup.dismiss.modal")
            },
            hideWithTransition: function() {
                var t = this,
                    n = setTimeout(function() {
                        t.$element.off(e.support.transition.end), t.hideModal()
                    }, 500);
                this.$element.one(e.support.transition.end, function() {
                    clearTimeout(n), t.hideModal()
                })
            },
            hideModal: function() {
                var e = this;
                this.$element.hide(), this.backdrop(function() {
                    e.removeBackdrop(), e.$element.trigger("hidden")
                })
            },
            removeBackdrop: function() {
                this.$backdrop.remove(), this.$backdrop = null
            },
            backdrop: function(t) {
                var n = this,
                    r = this.$element.hasClass("fade") ? "fade" : "";
                if (this.isShown && this.options.backdrop) {
                    var i = e.support.transition && r;
                    this.$backdrop = e('<div class="modal-backdrop ' + r + '" />').appendTo(document.body), this.$backdrop.click(this.options.backdrop == "static" ? e.proxy(this.$element[0].focus, this.$element[0]) : e.proxy(this.hide, this)), i && this.$backdrop[0].offsetWidth, this.$backdrop.addClass("in");
                    if (!t) return;
                    i ? this.$backdrop.one(e.support.transition.end, t) : t()
                } else !this.isShown && this.$backdrop ? (this.$backdrop.removeClass("in"), e.support.transition && this.$element.hasClass("fade") ? this.$backdrop.one(e.support.transition.end, t) : t()) : t && t()
            }
        };
        var n = e.fn.modal;
        e.fn.modal = function(n) {
            return this.each(function() {
                var r = e(this),
                    i = r.data("modal"),
                    s = e.extend({}, e.fn.modal.defaults, r.data(), typeof n == "object" && n);
                i || r.data("modal", i = new t(this, s)), typeof n == "string" ? i[n]() : s.show && i.show()
            })
        }, e.fn.modal.defaults = {
            backdrop: !0,
            keyboard: !0,
            show: !0
        }, e.fn.modal.Constructor = t, e.fn.modal.noConflict = function() {
            return e.fn.modal = n, this
        }, e(document).on("click.modal.data-api", '[data-toggle="modal"]', function(t) {
            var n = e(this),
                r = n.attr("href"),
                i = e(n.attr("data-target") || r && r.replace(/.*(?=#[^\s]+$)/, "")),
                s = i.data("modal") ? "toggle" : e.extend({
                    remote: !/#/.test(r) && r
                }, i.data(), n.data());
            t.preventDefault(), i.modal(s).one("hide", function() {
                n.focus()
            })
        })
    }(window.jQuery), ! function(e) {
        "use strict";
        var t = function(e, t) {
            this.init("tooltip", e, t)
        };
        t.prototype = {
            constructor: t,
            init: function(t, n, r) {
                var i, s, o, u, a;
                this.type = t, this.$element = e(n), this.options = this.getOptions(r), this.enabled = !0, o = this.options.trigger.split(" ");
                for (a = o.length; a--;) u = o[a], u == "click" ? this.$element.on("click." + this.type, this.options.selector, e.proxy(this.toggle, this)) : u != "manual" && (i = u == "hover" ? "mouseenter" : "focus", s = u == "hover" ? "mouseleave" : "blur", this.$element.on(i + "." + this.type, this.options.selector, e.proxy(this.enter, this)), this.$element.on(s + "." + this.type, this.options.selector, e.proxy(this.leave, this)));
                this.options.selector ? this._options = e.extend({}, this.options, {
                    trigger: "manual",
                    selector: ""
                }) : this.fixTitle()
            },
            getOptions: function(t) {
                return t = e.extend({}, e.fn[this.type].defaults, this.$element.data(), t), t.delay && typeof t.delay == "number" && (t.delay = {
                    show: t.delay,
                    hide: t.delay
                }), t
            },
            enter: function(t) {
                var n = e(t.currentTarget)[this.type](this._options).data(this.type);
                if (!n.options.delay || !n.options.delay.show) return n.show();
                clearTimeout(this.timeout), n.hoverState = "in", this.timeout = setTimeout(function() {
                    n.hoverState == "in" && n.show()
                }, n.options.delay.show)
            },
            leave: function(t) {
                var n = e(t.currentTarget)[this.type](this._options).data(this.type);
                this.timeout && clearTimeout(this.timeout);
                if (!n.options.delay || !n.options.delay.hide) return n.hide();
                n.hoverState = "out", this.timeout = setTimeout(function() {
                    n.hoverState == "out" && n.hide()
                }, n.options.delay.hide)
            },
            show: function() {
                var t, n, r, i, s, o, u = e.Event("show");
                if (this.hasContent() && this.enabled) {
                    this.$element.trigger(u);
                    if (u.isDefaultPrevented()) return;
                    t = this.tip(), this.setContent(), this.options.animation && t.addClass("fade"), s = typeof this.options.placement == "function" ? this.options.placement.call(this, t[0], this.$element[0]) : this.options.placement, t.detach().css({
                        top: 0,
                        left: 0,
                        display: "block"
                    }), this.options.container ? t.appendTo(this.options.container) : t.insertAfter(this.$element), n = this.getPosition(), r = t[0].offsetWidth, i = t[0].offsetHeight;
                    switch (s) {
                        case "bottom":
                            o = {
                                top: n.top + n.height,
                                left: n.left + n.width / 2 - r / 2
                            };
                            break;
                        case "top":
                            o = {
                                top: n.top - i,
                                left: n.left + n.width / 2 - r / 2
                            };
                            break;
                        case "left":
                            o = {
                                top: n.top + n.height / 2 - i / 2,
                                left: n.left - r
                            };
                            break;
                        case "right":
                            o = {
                                top: n.top + n.height / 2 - i / 2,
                                left: n.left + n.width
                            }
                    }
                    this.applyPlacement(o, s), this.$element.trigger("shown")
                }
            },
            applyPlacement: function(e, t) {
                var n = this.tip(),
                    r = n[0].offsetWidth,
                    i = n[0].offsetHeight,
                    s, o, u, a;
                n.offset(e).addClass(t).addClass("in"), s = n[0].offsetWidth, o = n[0].offsetHeight, t == "top" && o != i && (e.top = e.top + i - o, a = !0), t == "bottom" || t == "top" ? (u = 0, e.left < 0 && (u = e.left * -2, e.left = 0, n.offset(e), s = n[0].offsetWidth, o = n[0].offsetHeight), this.replaceArrow(u - r + s, s, "left")) : this.replaceArrow(o - i, o, "top"), a && n.offset(e)
            },
            replaceArrow: function(e, t, n) {
                this.arrow().css(n, e ? 50 * (1 - e / t) + "%" : "")
            },
            setContent: function() {
                var e = this.tip(),
                    t = this.getTitle();
                e.find(".tooltip-inner")[this.options.html ? "html" : "text"](t), e.removeClass("fade in top bottom left right")
            },
            hide: function() {
                function i() {
                    var t = setTimeout(function() {
                        n.off(e.support.transition.end).detach()
                    }, 500);
                    n.one(e.support.transition.end, function() {
                        clearTimeout(t), n.detach()
                    })
                }
                var t = this,
                    n = this.tip(),
                    r = e.Event("hide");
                this.$element.trigger(r);
                if (r.isDefaultPrevented()) return;
                return n.removeClass("in"), e.support.transition && this.$tip.hasClass("fade") ? i() : n.detach(), this.$element.trigger("hidden"), this
            },
            fixTitle: function() {
                var e = this.$element;
                (e.attr("title") || typeof e.attr("data-original-title") != "string") && e.attr("data-original-title", e.attr("title") || "").attr("title", "")
            },
            hasContent: function() {
                return this.getTitle()
            },
            getPosition: function() {
                var t = this.$element[0];
                return e.extend({}, typeof t.getBoundingClientRect == "function" ? t.getBoundingClientRect() : {
                    width: t.offsetWidth,
                    height: t.offsetHeight
                }, this.$element.offset())
            },
            getTitle: function() {
                var e, t = this.$element,
                    n = this.options;
                return e = t.attr("data-original-title") || (typeof n.title == "function" ? n.title.call(t[0]) : n.title), e
            },
            tip: function() {
                return this.$tip = this.$tip || e(this.options.template)
            },
            arrow: function() {
                return this.$arrow = this.$arrow || this.tip().find(".tooltip-arrow")
            },
            validate: function() {
                this.$element[0].parentNode || (this.hide(), this.$element = null, this.options = null)
            },
            enable: function() {
                this.enabled = !0
            },
            disable: function() {
                this.enabled = !1
            },
            toggleEnabled: function() {
                this.enabled = !this.enabled
            },
            toggle: function(t) {
                var n = t ? e(t.currentTarget)[this.type](this._options).data(this.type) : this;
                n.tip().hasClass("in") ? n.hide() : n.show()
            },
            destroy: function() {
                this.hide().$element.off("." + this.type).removeData(this.type)
            }
        };
        var n = e.fn.tooltip;
        e.fn.tooltip = function(n) {
            return this.each(function() {
                var r = e(this),
                    i = r.data("tooltip"),
                    s = typeof n == "object" && n;
                i || r.data("tooltip", i = new t(this, s)), typeof n == "string" && i[n]()
            })
        }, e.fn.tooltip.Constructor = t, e.fn.tooltip.defaults = {
            animation: !0,
            placement: "top",
            selector: !1,
            template: '<div class="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner"></div></div>',
            trigger: "hover focus",
            title: "",
            delay: 0,
            html: !1,
            container: !1
        }, e.fn.tooltip.noConflict = function() {
            return e.fn.tooltip = n, this
        }
    }(window.jQuery), ! function(e) {
        "use strict";
        var t = function(e, t) {
            this.init("popover", e, t)
        };
        t.prototype = e.extend({}, e.fn.tooltip.Constructor.prototype, {
            constructor: t,
            setContent: function() {
                var e = this.tip(),
                    t = this.getTitle(),
                    n = this.getContent();
                e.find(".popover-title")[this.options.html ? "html" : "text"](t), e.find(".popover-content")[this.options.html ? "html" : "text"](n), e.removeClass("fade top bottom left right in")
            },
            hasContent: function() {
                return this.getTitle() || this.getContent()
            },
            getContent: function() {
                var e, t = this.$element,
                    n = this.options;
                return e = (typeof n.content == "function" ? n.content.call(t[0]) : n.content) || t.attr("data-content"), e
            },
            tip: function() {
                return this.$tip || (this.$tip = e(this.options.template)), this.$tip
            },
            destroy: function() {
                this.hide().$element.off("." + this.type).removeData(this.type)
            }
        });
        var n = e.fn.popover;
        e.fn.popover = function(n) {
            return this.each(function() {
                var r = e(this),
                    i = r.data("popover"),
                    s = typeof n == "object" && n;
                i || r.data("popover", i = new t(this, s)), typeof n == "string" && i[n]()
            })
        }, e.fn.popover.Constructor = t, e.fn.popover.defaults = e.extend({}, e.fn.tooltip.defaults, {
            placement: "right",
            trigger: "click",
            content: "",
            template: '<div class="popover"><div class="arrow"></div><h3 class="popover-title"></h3><div class="popover-content"></div></div>'
        }), e.fn.popover.noConflict = function() {
            return e.fn.popover = n, this
        }
    }(window.jQuery), ! function(e) {
        "use strict";

        function t(t, n) {
            var r = e.proxy(this.process, this),
                i = e(t).is("body") ? e(window) : e(t),
                s;
            this.options = e.extend({}, e.fn.scrollspy.defaults, n), this.$scrollElement = i.on("scroll.scroll-spy.data-api", r), this.selector = (this.options.target || (s = e(t).attr("href")) && s.replace(/.*(?=#[^\s]+$)/, "") || "") + " .nav li > a", this.$body = e("body"), this.refresh(), this.process()
        }
        t.prototype = {
            constructor: t,
            refresh: function() {
                var t = this,
                    n;
                this.offsets = e([]), this.targets = e([]), n = this.$body.find(this.selector).map(function() {
                    var n = e(this),
                        r = n.data("target") || n.attr("href"),
                        i = /^#\w/.test(r) && e(r);
                    return i && i.length && [
                        [i.position().top + (!e.isWindow(t.$scrollElement.get(0)) && t.$scrollElement.scrollTop()), r]
                    ] || null
                }).sort(function(e, t) {
                    return e[0] - t[0]
                }).each(function() {
                    t.offsets.push(this[0]), t.targets.push(this[1])
                })
            },
            process: function() {
                var e = this.$scrollElement.scrollTop() + this.options.offset,
                    t = this.$scrollElement[0].scrollHeight || this.$body[0].scrollHeight,
                    n = t - this.$scrollElement.height(),
                    r = this.offsets,
                    i = this.targets,
                    s = this.activeTarget,
                    o;
                if (e >= n) return s != (o = i.last()[0]) && this.activate(o);
                for (o = r.length; o--;) s != i[o] && e >= r[o] && (!r[o + 1] || e <= r[o + 1]) && this.activate(i[o])
            },
            activate: function(t) {
                var n, r;
                this.activeTarget = t, e(this.selector).parent(".active").removeClass("active"), r = this.selector + '[data-target="' + t + '"],' + this.selector + '[href="' + t + '"]', n = e(r).parent("li").addClass("active"), n.parent(".dropdown-menu").length && (n = n.closest("li.dropdown").addClass("active")), n.trigger("activate")
            }
        };
        var n = e.fn.scrollspy;
        e.fn.scrollspy = function(n) {
            return this.each(function() {
                var r = e(this),
                    i = r.data("scrollspy"),
                    s = typeof n == "object" && n;
                i || r.data("scrollspy", i = new t(this, s)), typeof n == "string" && i[n]()
            })
        }, e.fn.scrollspy.Constructor = t, e.fn.scrollspy.defaults = {
            offset: 10
        }, e.fn.scrollspy.noConflict = function() {
            return e.fn.scrollspy = n, this
        }, e(window).on("load", function() {
            e('[data-spy="scroll"]').each(function() {
                var t = e(this);
                t.scrollspy(t.data())
            })
        })
    }(window.jQuery), ! function(e) {
        "use strict";
        var t = function(t) {
            this.element = e(t)
        };
        t.prototype = {
            constructor: t,
            show: function() {
                var t = this.element,
                    n = t.closest("ul:not(.dropdown-menu)"),
                    r = t.attr("data-target"),
                    i, s, o;
                r || (r = t.attr("href"), r = r && r.replace(/.*(?=#[^\s]*$)/, ""));
                if (t.parent("li").hasClass("active")) return;
                i = n.find(".active:last a")[0], o = e.Event("show", {
                    relatedTarget: i
                }), t.trigger(o);
                if (o.isDefaultPrevented()) return;
                s = e(r), this.activate(t.parent("li"), n), this.activate(s, s.parent(), function() {
                    t.trigger({
                        type: "shown",
                        relatedTarget: i
                    })
                })
            },
            activate: function(t, n, r) {
                function o() {
                    i.removeClass("active").find("> .dropdown-menu > .active").removeClass("active"), t.addClass("active"), s ? (t[0].offsetWidth, t.addClass("in")) : t.removeClass("fade"), t.parent(".dropdown-menu") && t.closest("li.dropdown").addClass("active"), r && r()
                }
                var i = n.find("> .active"),
                    s = r && e.support.transition && i.hasClass("fade");
                s ? i.one(e.support.transition.end, o) : o(), i.removeClass("in")
            }
        };
        var n = e.fn.tab;
        e.fn.tab = function(n) {
            return this.each(function() {
                var r = e(this),
                    i = r.data("tab");
                i || r.data("tab", i = new t(this)), typeof n == "string" && i[n]()
            })
        }, e.fn.tab.Constructor = t, e.fn.tab.noConflict = function() {
            return e.fn.tab = n, this
        }, e(document).on("click.tab.data-api", '[data-toggle="tab"], [data-toggle="pill"]', function(t) {
            t.preventDefault(), e(this).tab("show")
        })
    }(window.jQuery), ! function(e) {
        "use strict";
        var t = function(t, n) {
            this.$element = e(t), this.options = e.extend({}, e.fn.typeahead.defaults, n), this.matcher = this.options.matcher || this.matcher, this.sorter = this.options.sorter || this.sorter, this.highlighter = this.options.highlighter || this.highlighter, this.updater = this.options.updater || this.updater, this.source = this.options.source, this.$menu = e(this.options.menu), this.shown = !1, this.listen()
        };
        t.prototype = {
            constructor: t,
            select: function() {
                var e = this.$menu.find(".active").attr("data-value");
                return this.$element.val(this.updater(e)).change(), this.hide()
            },
            updater: function(e) {
                return e
            },
            show: function() {
                var t = e.extend({}, this.$element.position(), {
                    height: this.$element[0].offsetHeight
                });
                return this.$menu.insertAfter(this.$element).css({
                    top: t.top + t.height,
                    left: t.left
                }).show(), this.shown = !0, this
            },
            hide: function() {
                return this.$menu.hide(), this.shown = !1, this
            },
            lookup: function(t) {
                var n;
                return this.query = this.$element.val(), !this.query || this.query.length < this.options.minLength ? this.shown ? this.hide() : this : (n = e.isFunction(this.source) ? this.source(this.query, e.proxy(this.process, this)) : this.source, n ? this.process(n) : this)
            },
            process: function(t) {
                var n = this;
                return t = e.grep(t, function(e) {
                    return n.matcher(e)
                }), t = this.sorter(t), t.length ? this.render(t.slice(0, this.options.items)).show() : this.shown ? this.hide() : this
            },
            matcher: function(e) {
                return ~e.toLowerCase().indexOf(this.query.toLowerCase())
            },
            sorter: function(e) {
                var t = [],
                    n = [],
                    r = [],
                    i;
                while (i = e.shift()) i.toLowerCase().indexOf(this.query.toLowerCase()) ? ~i.indexOf(this.query) ? n.push(i) : r.push(i) : t.push(i);
                return t.concat(n, r)
            },
            highlighter: function(e) {
                var t = this.query.replace(/[\-\[\]{}()*+?.,\\\^$|#\s]/g, "\\$&");
                return e.replace(new RegExp("(" + t + ")", "ig"), function(e, t) {
                    return "<strong>" + t + "</strong>"
                })
            },
            render: function(t) {
                var n = this;
                return t = e(t).map(function(t, r) {
                    return t = e(n.options.item).attr("data-value", r), t.find("a").html(n.highlighter(r)), t[0]
                }), t.first().addClass("active"), this.$menu.html(t), this
            },
            next: function(t) {
                var n = this.$menu.find(".active").removeClass("active"),
                    r = n.next();
                r.length || (r = e(this.$menu.find("li")[0])), r.addClass("active")
            },
            prev: function(e) {
                var t = this.$menu.find(".active").removeClass("active"),
                    n = t.prev();
                n.length || (n = this.$menu.find("li").last()), n.addClass("active")
            },
            listen: function() {
                this.$element.on("focus", e.proxy(this.focus, this)).on("blur", e.proxy(this.blur, this)).on("keypress", e.proxy(this.keypress, this)).on("keyup", e.proxy(this.keyup, this)), this.eventSupported("keydown") && this.$element.on("keydown", e.proxy(this.keydown, this)), this.$menu.on("click", e.proxy(this.click, this)).on("mouseenter", "li", e.proxy(this.mouseenter, this)).on("mouseleave", "li", e.proxy(this.mouseleave, this))
            },
            eventSupported: function(e) {
                var t = e in this.$element;
                return t || (this.$element.setAttribute(e, "return;"), t = typeof this.$element[e] == "function"), t
            },
            move: function(e) {
                if (!this.shown) return;
                switch (e.keyCode) {
                    case 9:
                    case 13:
                    case 27:
                        e.preventDefault();
                        break;
                    case 38:
                        e.preventDefault(), this.prev();
                        break;
                    case 40:
                        e.preventDefault(), this.next()
                }
                e.stopPropagation()
            },
            keydown: function(t) {
                this.suppressKeyPressRepeat = ~e.inArray(t.keyCode, [40, 38, 9, 13, 27]), this.move(t)
            },
            keypress: function(e) {
                if (this.suppressKeyPressRepeat) return;
                this.move(e)
            },
            keyup: function(e) {
                switch (e.keyCode) {
                    case 40:
                    case 38:
                    case 16:
                    case 17:
                    case 18:
                        break;
                    case 9:
                    case 13:
                        if (!this.shown) return;
                        this.select();
                        break;
                    case 27:
                        if (!this.shown) return;
                        this.hide();
                        break;
                    default:
                        this.lookup()
                }
                e.stopPropagation(), e.preventDefault()
            },
            focus: function(e) {
                this.focused = !0
            },
            blur: function(e) {
                this.focused = !1, !this.mousedover && this.shown && this.hide()
            },
            click: function(e) {
                e.stopPropagation(), e.preventDefault(), this.select(), this.$element.focus()
            },
            mouseenter: function(t) {
                this.mousedover = !0, this.$menu.find(".active").removeClass("active"), e(t.currentTarget).addClass("active")
            },
            mouseleave: function(e) {
                this.mousedover = !1, !this.focused && this.shown && this.hide()
            }
        };
        var n = e.fn.typeahead;
        e.fn.typeahead = function(n) {
            return this.each(function() {
                var r = e(this),
                    i = r.data("typeahead"),
                    s = typeof n == "object" && n;
                i || r.data("typeahead", i = new t(this, s)), typeof n == "string" && i[n]()
            })
        }, e.fn.typeahead.defaults = {
            source: [],
            items: 8,
            menu: '<ul class="typeahead dropdown-menu"></ul>',
            item: '<li><a href="#"></a></li>',
            minLength: 1
        }, e.fn.typeahead.Constructor = t, e.fn.typeahead.noConflict = function() {
            return e.fn.typeahead = n, this
        }, e(document).on("focus.typeahead.data-api", '[data-provide="typeahead"]', function(t) {
            var n = e(this);
            if (n.data("typeahead")) return;
            n.typeahead(n.data())
        })
    }(window.jQuery), ! function(e) {
        "use strict";
        var t = function(t, n) {
            this.options = e.extend({}, e.fn.affix.defaults, n), this.$window = e(window).on("scroll.affix.data-api", e.proxy(this.checkPosition, this)).on("click.affix.data-api", e.proxy(function() {
                setTimeout(e.proxy(this.checkPosition, this), 1)
            }, this)), this.$element = e(t), this.checkPosition()
        };
        t.prototype.checkPosition = function() {
            if (!this.$element.is(":visible")) return;
            var t = e(document).height(),
                n = this.$window.scrollTop(),
                r = this.$element.offset(),
                i = this.options.offset,
                s = i.bottom,
                o = i.top,
                u = "affix affix-top affix-bottom",
                a;
            typeof i != "object" && (s = o = i), typeof o == "function" && (o = i.top()), typeof s == "function" && (s = i.bottom()), a = this.unpin != null && n + this.unpin <= r.top ? !1 : s != null && r.top + this.$element.height() >= t - s ? "bottom" : o != null && n <= o ? "top" : !1;
            if (this.affixed === a) return;
            this.affixed = a, this.unpin = a == "bottom" ? r.top - n : null, this.$element.removeClass(u).addClass("affix" + (a ? "-" + a : ""))
        };
        var n = e.fn.affix;
        e.fn.affix = function(n) {
            return this.each(function() {
                var r = e(this),
                    i = r.data("affix"),
                    s = typeof n == "object" && n;
                i || r.data("affix", i = new t(this, s)), typeof n == "string" && i[n]()
            })
        }, e.fn.affix.Constructor = t, e.fn.affix.defaults = {
            offset: 0
        }, e.fn.affix.noConflict = function() {
            return e.fn.affix = n, this
        }, e(window).on("load", function() {
            e('[data-spy="affix"]').each(function() {
                var t = e(this),
                    n = t.data();
                n.offset = n.offset || {}, n.offsetBottom && (n.offset.bottom = n.offsetBottom), n.offsetTop && (n.offset.top = n.offsetTop), t.affix(n)
            })
        })
    }(window.jQuery);


    jQuery.easing.jswing = jQuery.easing.swing;
    jQuery.extend(jQuery.easing, {
        def: "easeOutQuad",
        swing: function(e, f, a, h, g) {
            return jQuery.easing[jQuery.easing.def](e, f, a, h, g)
        },
        easeInQuad: function(e, f, a, h, g) {
            return h * (f /= g) * f + a
        },
        easeOutQuad: function(e, f, a, h, g) {
            return -h * (f /= g) * (f - 2) + a
        },
        easeInOutQuad: function(e, f, a, h, g) {
            if ((f /= g / 2) < 1) {
                return h / 2 * f * f + a
            }
            return -h / 2 * ((--f) * (f - 2) - 1) + a
        },
        easeInCubic: function(e, f, a, h, g) {
            return h * (f /= g) * f * f + a
        },
        easeOutCubic: function(e, f, a, h, g) {
            return h * ((f = f / g - 1) * f * f + 1) + a
        },
        easeInOutCubic: function(e, f, a, h, g) {
            if ((f /= g / 2) < 1) {
                return h / 2 * f * f * f + a
            }
            return h / 2 * ((f -= 2) * f * f + 2) + a
        },
        easeInQuart: function(e, f, a, h, g) {
            return h * (f /= g) * f * f * f + a
        },
        easeOutQuart: function(e, f, a, h, g) {
            return -h * ((f = f / g - 1) * f * f * f - 1) + a
        },
        easeInOutQuart: function(e, f, a, h, g) {
            if ((f /= g / 2) < 1) {
                return h / 2 * f * f * f * f + a
            }
            return -h / 2 * ((f -= 2) * f * f * f - 2) + a
        },
        easeInQuint: function(e, f, a, h, g) {
            return h * (f /= g) * f * f * f * f + a
        },
        easeOutQuint: function(e, f, a, h, g) {
            return h * ((f = f / g - 1) * f * f * f * f + 1) + a
        },
        easeInOutQuint: function(e, f, a, h, g) {
            if ((f /= g / 2) < 1) {
                return h / 2 * f * f * f * f * f + a
            }
            return h / 2 * ((f -= 2) * f * f * f * f + 2) + a
        },
        easeInSine: function(e, f, a, h, g) {
            return -h * Math.cos(f / g * (Math.PI / 2)) + h + a
        },
        easeOutSine: function(e, f, a, h, g) {
            return h * Math.sin(f / g * (Math.PI / 2)) + a
        },
        easeInOutSine: function(e, f, a, h, g) {
            return -h / 2 * (Math.cos(Math.PI * f / g) - 1) + a
        },
        easeInExpo: function(e, f, a, h, g) {
            return (f == 0) ? a : h * Math.pow(2, 10 * (f / g - 1)) + a
        },
        easeOutExpo: function(e, f, a, h, g) {
            return (f == g) ? a + h : h * (-Math.pow(2, -10 * f / g) + 1) + a
        },
        easeInOutExpo: function(e, f, a, h, g) {
            if (f == 0) {
                return a
            }
            if (f == g) {
                return a + h
            }
            if ((f /= g / 2) < 1) {
                return h / 2 * Math.pow(2, 10 * (f - 1)) + a
            }
            return h / 2 * (-Math.pow(2, -10 * --f) + 2) + a
        },
        easeInCirc: function(e, f, a, h, g) {
            return -h * (Math.sqrt(1 - (f /= g) * f) - 1) + a
        },
        easeOutCirc: function(e, f, a, h, g) {
            return h * Math.sqrt(1 - (f = f / g - 1) * f) + a
        },
        easeInOutCirc: function(e, f, a, h, g) {
            if ((f /= g / 2) < 1) {
                return -h / 2 * (Math.sqrt(1 - f * f) - 1) + a
            }
            return h / 2 * (Math.sqrt(1 - (f -= 2) * f) + 1) + a
        },
        easeInElastic: function(f, h, e, l, k) {
            var i = 1.70158;
            var j = 0;
            var g = l;
            if (h == 0) {
                return e
            }
            if ((h /= k) == 1) {
                return e + l
            }
            if (!j) {
                j = k * 0.3
            }
            if (g < Math.abs(l)) {
                g = l;
                var i = j / 4
            } else {
                var i = j / (2 * Math.PI) * Math.asin(l / g)
            }
            return -(g * Math.pow(2, 10 * (h -= 1)) * Math.sin((h * k - i) * (2 * Math.PI) / j)) + e
        },
        easeOutElastic: function(f, h, e, l, k) {
            var i = 1.70158;
            var j = 0;
            var g = l;
            if (h == 0) {
                return e
            }
            if ((h /= k) == 1) {
                return e + l
            }
            if (!j) {
                j = k * 0.3
            }
            if (g < Math.abs(l)) {
                g = l;
                var i = j / 4
            } else {
                var i = j / (2 * Math.PI) * Math.asin(l / g)
            }
            return g * Math.pow(2, -10 * h) * Math.sin((h * k - i) * (2 * Math.PI) / j) + l + e
        },
        easeInOutElastic: function(f, h, e, l, k) {
            var i = 1.70158;
            var j = 0;
            var g = l;
            if (h == 0) {
                return e
            }
            if ((h /= k / 2) == 2) {
                return e + l
            }
            if (!j) {
                j = k * (0.3 * 1.5)
            }
            if (g < Math.abs(l)) {
                g = l;
                var i = j / 4
            } else {
                var i = j / (2 * Math.PI) * Math.asin(l / g)
            }
            if (h < 1) {
                return -0.5 * (g * Math.pow(2, 10 * (h -= 1)) * Math.sin((h * k - i) * (2 * Math.PI) / j)) + e
            }
            return g * Math.pow(2, -10 * (h -= 1)) * Math.sin((h * k - i) * (2 * Math.PI) / j) * 0.5 + l + e
        },
        easeInBack: function(e, f, a, i, h, g) {
            if (g == undefined) {
                g = 1.70158
            }
            return i * (f /= h) * f * ((g + 1) * f - g) + a
        },
        easeOutBack: function(e, f, a, i, h, g) {
            if (g == undefined) {
                g = 1.70158
            }
            return i * ((f = f / h - 1) * f * ((g + 1) * f + g) + 1) + a
        },
        easeInOutBack: function(e, f, a, i, h, g) {
            if (g == undefined) {
                g = 1.70158
            }
            if ((f /= h / 2) < 1) {
                return i / 2 * (f * f * (((g *= (1.525)) + 1) * f - g)) + a
            }
            return i / 2 * ((f -= 2) * f * (((g *= (1.525)) + 1) * f + g) + 2) + a
        },
        easeInBounce: function(e, f, a, h, g) {
            return h - jQuery.easing.easeOutBounce(e, g - f, 0, h, g) + a
        },
        easeOutBounce: function(e, f, a, h, g) {
            if ((f /= g) < (1 / 2.75)) {
                return h * (7.5625 * f * f) + a
            } else {
                if (f < (2 / 2.75)) {
                    return h * (7.5625 * (f -= (1.5 / 2.75)) * f + 0.75) + a
                } else {
                    if (f < (2.5 / 2.75)) {
                        return h * (7.5625 * (f -= (2.25 / 2.75)) * f + 0.9375) + a
                    } else {
                        return h * (7.5625 * (f -= (2.625 / 2.75)) * f + 0.984375) + a
                    }
                }
            }
        },
        easeInOutBounce: function(e, f, a, h, g) {
            if (f < g / 2) {
                return jQuery.easing.easeInBounce(e, f * 2, 0, h, g) * 0.5 + a
            }
            return jQuery.easing.easeOutBounce(e, f * 2 - g, 0, h, g) * 0.5 + h * 0.5 + a
        }
    });


    (function(d) {
        d.flexslider = function(j, l) {
            var a = d(j),
                c = d.extend({}, d.flexslider.defaults, l),
                e = c.namespace,
                q = "ontouchstart" in window || window.DocumentTouch && document instanceof DocumentTouch,
                u = q ? "touchend" : "click",
                m = "vertical" === c.direction,
                n = c.reverse,
                h = 0 < c.itemWidth,
                s = "fade" === c.animation,
                t = "" !== c.asNavFor,
                f = {};
            d.data(j, "flexslider", a);
            f = {
                init: function() {
                    a.animating = !1;
                    a.currentSlide = c.startAt;
                    a.animatingTo = a.currentSlide;
                    a.atEnd = 0 === a.currentSlide || a.currentSlide === a.last;
                    a.containerSelector = c.selector.substr(0, c.selector.search(" "));
                    a.slides = d(c.selector, a);
                    a.container = d(a.containerSelector, a);
                    a.count = a.slides.length;
                    a.syncExists = 0 < d(c.sync).length;
                    "slide" === c.animation && (c.animation = "swing");
                    a.prop = m ? "top" : "marginLeft";
                    a.args = {};
                    a.manualPause = !1;
                    var b = a,
                        g;
                    if (g = !c.video)
                        if (g = !s)
                            if (g = c.useCSS) a: {
                                g = document.createElement("div");
                                var p = ["perspectiveProperty", "WebkitPerspective", "MozPerspective", "OPerspective", "msPerspective"],
                                    e;
                                for (e in p)
                                    if (void 0 !== g.style[p[e]]) {
                                        a.pfx = p[e].replace("Perspective", "").toLowerCase();
                                        a.prop = "-" + a.pfx + "-transform";
                                        g = !0;
                                        break a
                                    }
                                g = !1
                            }
                            b.transitions = g;
                    "" !== c.controlsContainer && (a.controlsContainer = 0 < d(c.controlsContainer).length && d(c.controlsContainer));
                    "" !== c.manualControls && (a.manualControls = 0 < d(c.manualControls).length && d(c.manualControls));
                    c.randomize && (a.slides.sort(function() {
                        return Math.round(Math.random()) - 0.5
                    }), a.container.empty().append(a.slides));
                    a.doMath();
                    t && f.asNav.setup();
                    a.setup("init");
                    c.controlNav && f.controlNav.setup();
                    c.directionNav && f.directionNav.setup();
                    c.keyboard && (1 === d(a.containerSelector).length || c.multipleKeyboard) && d(document).bind("keyup", function(b) {
                        b = b.keyCode;
                        if (!a.animating && (39 === b || 37 === b)) b = 39 === b ? a.getTarget("next") : 37 === b ? a.getTarget("prev") : !1, a.flexAnimate(b, c.pauseOnAction)
                    });
                    c.mousewheel && a.bind("mousewheel", function(b, g) {
                        b.preventDefault();
                        var d = 0 > g ? a.getTarget("next") : a.getTarget("prev");
                        a.flexAnimate(d, c.pauseOnAction)
                    });
                    c.pausePlay && f.pausePlay.setup();
                    c.slideshow && (c.pauseOnHover && a.hover(function() {
                        !a.manualPlay && !a.manualPause && a.pause()
                    }, function() {
                        !a.manualPause && !a.manualPlay && a.play()
                    }), 0 < c.initDelay ? setTimeout(a.play, c.initDelay) : a.play());
                    q && c.touch && f.touch();
                    (!s || s && c.smoothHeight) && d(window).bind("resize focus", f.resize);
                    setTimeout(function() {
                        c.start(a)
                    }, 200)
                },
                asNav: {
                    setup: function() {
                        a.asNav = !0;
                        a.animatingTo = Math.floor(a.currentSlide / a.move);
                        a.currentItem = a.currentSlide;
                        a.slides.removeClass(e + "active-slide").eq(a.currentItem).addClass(e + "active-slide");
                        a.slides.click(function(b) {
                            b.preventDefault();
                            b = d(this);
                            var g = b.index();
                            !d(c.asNavFor).data("flexslider").animating && !b.hasClass("active") && (a.direction = a.currentItem < g ? "next" : "prev", a.flexAnimate(g, c.pauseOnAction, !1, !0, !0))
                        })
                    }
                },
                controlNav: {
                    setup: function() {
                        a.manualControls ? f.controlNav.setupManual() : f.controlNav.setupPaging()
                    },
                    setupPaging: function() {
                        var b = 1,
                            g;
                        a.controlNavScaffold = d('<ol class="' + e + "control-nav " + e + ("thumbnails" === c.controlNav ? "control-thumbs" : "control-paging") + '"></ol>');
                        if (1 < a.pagingCount)
                            for (var p = 0; p < a.pagingCount; p++) g = "thumbnails" === c.controlNav ? '<img src="' + a.slides.eq(p).attr("data-thumb") + '"/>' : "<a>" + b + "</a>", a.controlNavScaffold.append("<li>" + g + "</li>"), b++;
                        a.controlsContainer ? d(a.controlsContainer).append(a.controlNavScaffold) : a.append(a.controlNavScaffold);
                        f.controlNav.set();
                        f.controlNav.active();
                        a.controlNavScaffold.delegate("a, img", u, function(b) {
                            b.preventDefault();
                            b = d(this);
                            var g = a.controlNav.index(b);
                            b.hasClass(e + "active") || (a.direction = g > a.currentSlide ? "next" : "prev", a.flexAnimate(g, c.pauseOnAction))
                        });
                        q && a.controlNavScaffold.delegate("a", "click touchstart", function(a) {
                            a.preventDefault()
                        })
                    },
                    setupManual: function() {
                        a.controlNav = a.manualControls;
                        f.controlNav.active();
                        a.controlNav.live(u, function(b) {
                            b.preventDefault();
                            b = d(this);
                            var g = a.controlNav.index(b);
                            b.hasClass(e + "active") || (g > a.currentSlide ? a.direction = "next" : a.direction = "prev", a.flexAnimate(g, c.pauseOnAction))
                        });
                        q && a.controlNav.live("click touchstart", function(a) {
                            a.preventDefault()
                        })
                    },
                    set: function() {
                        a.controlNav = d("." + e + "control-nav li " + ("thumbnails" === c.controlNav ? "img" : "a"), a.controlsContainer ? a.controlsContainer : a)
                    },
                    active: function() {
                        a.controlNav.removeClass(e + "active").eq(a.animatingTo).addClass(e + "active")
                    },
                    update: function(b, c) {
                        1 < a.pagingCount && "add" === b ? a.controlNavScaffold.append(d("<li><a>" + a.count + "</a></li>")) : 1 === a.pagingCount ? a.controlNavScaffold.find("li").remove() : a.controlNav.eq(c).closest("li").remove();
                        f.controlNav.set();
                        1 < a.pagingCount && a.pagingCount !== a.controlNav.length ? a.update(c, b) : f.controlNav.active()
                    }
                },
                directionNav: {
                    setup: function() {
                        var b = d('<ul class="' + e + 'direction-nav"><li><a class="' + e + 'prev" href="#">' + c.prevText + '</a></li><li><a class="' + e + 'next" href="#">' + c.nextText + "</a></li></ul>");
                        a.controlsContainer ? (d(a.controlsContainer).append(b), a.directionNav = d("." + e + "direction-nav li a", a.controlsContainer)) : (a.append(b), a.directionNav = d("." + e + "direction-nav li a", a));
                        f.directionNav.update();
                        a.directionNav.bind(u, function(b) {
                            b.preventDefault();
                            b = d(this).hasClass(e + "next") ? a.getTarget("next") : a.getTarget("prev");
                            a.flexAnimate(b, c.pauseOnAction)
                        });
                        q && a.directionNav.bind("click touchstart", function(a) {
                            a.preventDefault()
                        })
                    },
                    update: function() {
                        var b = e + "disabled";
                        1 === a.pagingCount ? a.directionNav.addClass(b) : c.animationLoop ? a.directionNav.removeClass(b) : 0 === a.animatingTo ? a.directionNav.removeClass(b).filter("." + e + "prev").addClass(b) : a.animatingTo === a.last ? a.directionNav.removeClass(b).filter("." + e + "next").addClass(b) : a.directionNav.removeClass(b)
                    }
                },
                pausePlay: {
                    setup: function() {
                        var b = d('<div class="' + e + 'pauseplay"><a></a></div>');
                        a.controlsContainer ? (a.controlsContainer.append(b), a.pausePlay = d("." + e + "pauseplay a", a.controlsContainer)) : (a.append(b), a.pausePlay = d("." + e + "pauseplay a", a));
                        f.pausePlay.update(c.slideshow ? e + "pause" : e + "play");
                        a.pausePlay.bind(u, function(b) {
                            b.preventDefault();
                            d(this).hasClass(e + "pause") ? (a.manualPause = !0, a.manualPlay = !1, a.pause()) : (a.manualPause = !1, a.manualPlay = !0, a.play())
                        });
                        q && a.pausePlay.bind("click touchstart", function(a) {
                            a.preventDefault()
                        })
                    },
                    update: function(b) {
                        "play" === b ? a.pausePlay.removeClass(e + "pause").addClass(e + "play").text(c.playText) : a.pausePlay.removeClass(e + "play").addClass(e + "pause").text(c.pauseText)
                    }
                },
                touch: function() {
                    function b(b) {
                        k = m ? d - b.touches[0].pageY : d - b.touches[0].pageX;
                        q = m ? Math.abs(k) < Math.abs(b.touches[0].pageX - e) : Math.abs(k) < Math.abs(b.touches[0].pageY - e);
                        if (!q || 500 < Number(new Date) - l) b.preventDefault(), !s && a.transitions && (c.animationLoop || (k /= 0 === a.currentSlide && 0 > k || a.currentSlide === a.last && 0 < k ? Math.abs(k) / r + 2 : 1), a.setProps(f + k, "setTouch"))
                    }

                    function g() {
                        j.removeEventListener("touchmove", b, !1);
                        if (a.animatingTo === a.currentSlide && !q && null !== k) {
                            var h = n ? -k : k,
                                m = 0 < h ? a.getTarget("next") : a.getTarget("prev");
                            a.canAdvance(m) && (550 > Number(new Date) - l && 50 < Math.abs(h) || Math.abs(h) > r / 2) ? a.flexAnimate(m, c.pauseOnAction) : s || a.flexAnimate(a.currentSlide, c.pauseOnAction, !0)
                        }
                        j.removeEventListener("touchend", g, !1);
                        f = k = e = d = null
                    }
                    var d, e, f, r, k, l, q = !1;
                    j.addEventListener("touchstart", function(k) {
                        a.animating ? k.preventDefault() : 1 === k.touches.length && (a.pause(), r = m ? a.h : a.w, l = Number(new Date), f = h && n && a.animatingTo === a.last ? 0 : h && n ? a.limit - (a.itemW + c.itemMargin) * a.move * a.animatingTo : h && a.currentSlide === a.last ? a.limit : h ? (a.itemW + c.itemMargin) * a.move * a.currentSlide : n ? (a.last - a.currentSlide + a.cloneOffset) * r : (a.currentSlide + a.cloneOffset) * r, d = m ? k.touches[0].pageY : k.touches[0].pageX, e = m ? k.touches[0].pageX : k.touches[0].pageY, j.addEventListener("touchmove", b, !1), j.addEventListener("touchend", g, !1))
                    }, !1)
                },
                resize: function() {
                    !a.animating && a.is(":visible") && (h || a.doMath(), s ? f.smoothHeight() : h ? (a.slides.width(a.computedW), a.update(a.pagingCount), a.setProps()) : m ? (a.viewport.height(a.h), a.setProps(a.h, "setTotal")) : (c.smoothHeight && f.smoothHeight(), a.newSlides.width(a.computedW), a.setProps(a.computedW, "setTotal")))
                },
                smoothHeight: function(b) {
                    if (!m || s) {
                        var c = s ? a : a.viewport;
                        b ? c.animate({
                            height: a.slides.eq(a.animatingTo).height()
                        }, b) : c.height(a.slides.eq(a.animatingTo).height())
                    }
                },
                sync: function(b) {
                    var g = d(c.sync).data("flexslider"),
                        e = a.animatingTo;
                    switch (b) {
                        case "animate":
                            g.flexAnimate(e, c.pauseOnAction, !1, !0);
                            break;
                        case "play":
                            !g.playing && !g.asNav && g.play();
                            break;
                        case "pause":
                            g.pause()
                    }
                }
            };
            a.flexAnimate = function(b, g, p, j, l) {
                t && 1 === a.pagingCount && (a.direction = a.currentItem < b ? "next" : "prev");
                if (!a.animating && (a.canAdvance(b, l) || p) && a.is(":visible")) {
                    if (t && j)
                        if (p = d(c.asNavFor).data("flexslider"), a.atEnd = 0 === b || b === a.count - 1, p.flexAnimate(b, !0, !1, !0, l), a.direction = a.currentItem < b ? "next" : "prev", p.direction = a.direction, Math.ceil((b + 1) / a.visible) - 1 !== a.currentSlide && 0 !== b) a.currentItem = b, a.slides.removeClass(e + "active-slide").eq(b).addClass(e + "active-slide"), b = Math.floor(b / a.visible);
                        else return a.currentItem = b, a.slides.removeClass(e + "active-slide").eq(b).addClass(e + "active-slide"), !1;
                    a.animating = !0;
                    a.animatingTo = b;
                    c.before(a);
                    g && a.pause();
                    a.syncExists && !l && f.sync("animate");
                    c.controlNav && f.controlNav.active();
                    h || a.slides.removeClass(e + "active-slide").eq(b).addClass(e + "active-slide");
                    a.atEnd = 0 === b || b === a.last;
                    c.directionNav && f.directionNav.update();
                    b === a.last && (c.end(a), c.animationLoop || a.pause());
                    if (s) q ? (a.slides.eq(a.currentSlide).css({
                        opacity: 0,
                        zIndex: 1
                    }), a.slides.eq(b).css({
                        opacity: 1,
                        zIndex: 2
                    }), a.slides.unbind("webkitTransitionEnd transitionend"), a.slides.eq(a.currentSlide).bind("webkitTransitionEnd transitionend", function() {
                        c.after(a)
                    }), a.animating = !1, a.currentSlide = a.animatingTo) : (a.slides.eq(a.currentSlide).fadeOut(c.animationSpeed, c.easing), a.slides.eq(b).fadeIn(c.animationSpeed, c.easing, a.wrapup));
                    else {
                        var r = m ? a.slides.filter(":first").height() : a.computedW;
                        h ? (b = c.itemWidth > a.w ? 2 * c.itemMargin : c.itemMargin, b = (a.itemW + b) * a.move * a.animatingTo, b = b > a.limit && 1 !== a.visible ? a.limit : b) : b = 0 === a.currentSlide && b === a.count - 1 && c.animationLoop && "next" !== a.direction ? n ? (a.count + a.cloneOffset) * r : 0 : a.currentSlide === a.last && 0 === b && c.animationLoop && "prev" !== a.direction ? n ? 0 : (a.count + 1) * r : n ? (a.count - 1 - b + a.cloneOffset) * r : (b + a.cloneOffset) * r;
                        a.setProps(b, "", c.animationSpeed);
                        if (a.transitions) {
                            if (!c.animationLoop || !a.atEnd) a.animating = !1, a.currentSlide = a.animatingTo;
                            a.container.unbind("webkitTransitionEnd transitionend");
                            a.container.bind("webkitTransitionEnd transitionend", function() {
                                a.wrapup(r)
                            })
                        } else a.container.animate(a.args, c.animationSpeed, c.easing, function() {
                            a.wrapup(r)
                        })
                    }
                    c.smoothHeight && f.smoothHeight(c.animationSpeed)
                }
            };
            a.wrapup = function(b) {
                !s && !h && (0 === a.currentSlide && a.animatingTo === a.last && c.animationLoop ? a.setProps(b, "jumpEnd") : a.currentSlide === a.last && (0 === a.animatingTo && c.animationLoop) && a.setProps(b, "jumpStart"));
                a.animating = !1;
                a.currentSlide = a.animatingTo;
                c.after(a)
            };
            a.animateSlides = function() {
                a.animating || a.flexAnimate(a.getTarget("next"))
            };
            a.pause = function() {
                clearInterval(a.animatedSlides);
                a.playing = !1;
                c.pausePlay && f.pausePlay.update("play");
                a.syncExists && f.sync("pause")
            };
            a.play = function() {
                a.animatedSlides = setInterval(a.animateSlides, c.slideshowSpeed);
                a.playing = !0;
                c.pausePlay && f.pausePlay.update("pause");
                a.syncExists && f.sync("play")
            };
            a.canAdvance = function(b, g) {
                var d = t ? a.pagingCount - 1 : a.last;
                return g ? !0 : t && a.currentItem === a.count - 1 && 0 === b && "prev" === a.direction ? !0 : t && 0 === a.currentItem && b === a.pagingCount - 1 && "next" !== a.direction ? !1 : b === a.currentSlide && !t ? !1 : c.animationLoop ? !0 : a.atEnd && 0 === a.currentSlide && b === d && "next" !== a.direction ? !1 : a.atEnd && a.currentSlide === d && 0 === b && "next" === a.direction ? !1 : !0
            };
            a.getTarget = function(b) {
                a.direction = b;
                return "next" === b ? a.currentSlide === a.last ? 0 : a.currentSlide + 1 : 0 === a.currentSlide ? a.last : a.currentSlide - 1
            };
            a.setProps = function(b, g, d) {
                var e, f = b ? b : (a.itemW + c.itemMargin) * a.move * a.animatingTo;
                e = -1 * function() {
                    if (h) return "setTouch" === g ? b : n && a.animatingTo === a.last ? 0 : n ? a.limit - (a.itemW + c.itemMargin) * a.move * a.animatingTo : a.animatingTo === a.last ? a.limit : f;
                    switch (g) {
                        case "setTotal":
                            return n ? (a.count - 1 - a.currentSlide + a.cloneOffset) * b : (a.currentSlide + a.cloneOffset) * b;
                        case "setTouch":
                            return b;
                        case "jumpEnd":
                            return n ? b : a.count * b;
                        case "jumpStart":
                            return n ? a.count * b : b;
                        default:
                            return b
                    }
                }() + "px";
                a.transitions && (e = m ? "translate3d(0," + e + ",0)" : "translate3d(" + e + ",0,0)", d = void 0 !== d ? d / 1E3 + "s" : "0s", a.container.css("-" + a.pfx + "-transition-duration", d));
                a.args[a.prop] = e;
                (a.transitions || void 0 === d) && a.container.css(a.args)
            };
            a.setup = function(b) {
                if (s) a.slides.css({
                    width: "100%",
                    "float": "left",
                    marginRight: "-100%",
                    position: "relative"
                }), "init" === b && (q ? a.slides.css({
                    opacity: 0,
                    display: "block",
                    webkitTransition: "opacity " + c.animationSpeed / 1E3 + "s ease",
                    zIndex: 1
                }).eq(a.currentSlide).css({
                    opacity: 1,
                    zIndex: 2
                }) : a.slides.eq(a.currentSlide).fadeIn(c.animationSpeed, c.easing)), c.smoothHeight && f.smoothHeight();
                else {
                    var g, p;
                    "init" === b && (a.viewport = d('<div class="' + e + 'viewport"></div>').css({
                        overflow: "hidden",
                        position: "relative"
                    }).appendTo(a).append(a.container), a.cloneCount = 0, a.cloneOffset = 0, n && (p = d.makeArray(a.slides).reverse(), a.slides = d(p), a.container.empty().append(a.slides)));
                    c.animationLoop && !h && (a.cloneCount = 2, a.cloneOffset = 1, "init" !== b && a.container.find(".clone").remove(), a.container.append(a.slides.first().clone().addClass("clone")).prepend(a.slides.last().clone().addClass("clone")));
                    a.newSlides = d(c.selector, a);
                    g = n ? a.count - 1 - a.currentSlide + a.cloneOffset : a.currentSlide + a.cloneOffset;
                    m && !h ? (a.container.height(200 * (a.count + a.cloneCount) + "%").css("position", "absolute").width("100%"), setTimeout(function() {
                        a.newSlides.css({
                            display: "block"
                        });
                        a.doMath();
                        a.viewport.height(a.h);
                        a.setProps(g * a.h, "init")
                    }, "init" === b ? 100 : 0)) : (a.container.width(200 * (a.count + a.cloneCount) + "%"), a.setProps(g * a.computedW, "init"), setTimeout(function() {
                        a.doMath();
                        a.newSlides.css({
                            width: a.computedW,
                            "float": "left",
                            display: "block"
                        });
                        c.smoothHeight && f.smoothHeight()
                    }, "init" === b ? 100 : 0))
                }
                h || a.slides.removeClass(e + "active-slide").eq(a.currentSlide).addClass(e + "active-slide")
            };
            a.doMath = function() {
                var b = a.slides.first(),
                    d = c.itemMargin,
                    e = c.minItems,
                    f = c.maxItems;
                a.w = a.width();
                a.h = b.height();
                a.boxPadding = b.outerWidth() - b.width();
                h ? (a.itemT = c.itemWidth + d, a.minW = e ? e * a.itemT : a.w, a.maxW = f ? f * a.itemT : a.w, a.itemW = a.minW > a.w ? (a.w - d * e) / e : a.maxW < a.w ? (a.w - d * f) / f : c.itemWidth > a.w ? a.w : c.itemWidth, a.visible = Math.floor(a.w / (a.itemW + d)), a.move = 0 < c.move && c.move < a.visible ? c.move : a.visible, a.pagingCount = Math.ceil((a.count - a.visible) / a.move + 1), a.last = a.pagingCount - 1, a.limit = 1 === a.pagingCount ? 0 : c.itemWidth > a.w ? (a.itemW + 2 * d) * a.count - a.w - d : (a.itemW + d) * a.count - a.w - d) : (a.itemW = a.w, a.pagingCount = a.count, a.last = a.count - 1);
                a.computedW = a.itemW - a.boxPadding
            };
            a.update = function(b, d) {
                a.doMath();
                h || (b < a.currentSlide ? a.currentSlide += 1 : b <= a.currentSlide && 0 !== b && (a.currentSlide -= 1), a.animatingTo = a.currentSlide);
                if (c.controlNav && !a.manualControls)
                    if ("add" === d && !h || a.pagingCount > a.controlNav.length) f.controlNav.update("add");
                    else if ("remove" === d && !h || a.pagingCount < a.controlNav.length) h && a.currentSlide > a.last && (a.currentSlide -= 1, a.animatingTo -= 1), f.controlNav.update("remove", a.last);
                c.directionNav && f.directionNav.update()
            };
            a.addSlide = function(b, e) {
                var f = d(b);
                a.count += 1;
                a.last = a.count - 1;
                m && n ? void 0 !== e ? a.slides.eq(a.count - e).after(f) : a.container.prepend(f) : void 0 !== e ? a.slides.eq(e).before(f) : a.container.append(f);
                a.update(e, "add");
                a.slides = d(c.selector + ":not(.clone)", a);
                a.setup();
                c.added(a)
            };
            a.removeSlide = function(b) {
                var e = isNaN(b) ? a.slides.index(d(b)) : b;
                a.count -= 1;
                a.last = a.count - 1;
                isNaN(b) ? d(b, a.slides).remove() : m && n ? a.slides.eq(a.last).remove() : a.slides.eq(b).remove();
                a.doMath();
                a.update(e, "remove");
                a.slides = d(c.selector + ":not(.clone)", a);
                a.setup();
                c.removed(a)
            };
            f.init()
        };
        d.flexslider.defaults = {
            namespace: "flex-",
            selector: ".slides > li",
            animation: "fade",
            easing: "swing",
            direction: "horizontal",
            reverse: !1,
            animationLoop: !0,
            smoothHeight: !1,
            startAt: 0,
            slideshow: !0,
            slideshowSpeed: 7E3,
            animationSpeed: 600,
            initDelay: 0,
            randomize: !1,
            pauseOnAction: !0,
            pauseOnHover: !1,
            useCSS: !0,
            touch: !0,
            video: !1,
            controlNav: !0,
            directionNav: !0,
            prevText: "Previous",
            nextText: "Next",
            keyboard: !0,
            multipleKeyboard: !1,
            mousewheel: !1,
            pausePlay: !1,
            pauseText: "Pause",
            playText: "Play",
            controlsContainer: "",
            manualControls: "",
            sync: "",
            asNavFor: "",
            itemWidth: 0,
            itemMargin: 0,
            minItems: 0,
            maxItems: 0,
            move: 0,
            start: function() {},
            before: function() {},
            after: function() {},
            end: function() {},
            added: function() {},
            removed: function() {}
        };
        d.fn.flexslider = function(j) {
            void 0 === j && (j = {});
            if ("object" === typeof j) return this.each(function() {
                var a = d(this),
                    c = a.find(j.selector ? j.selector : ".slides > li");
                1 === c.length ? (c.fadeIn(400), j.start && j.start(a)) : void 0 == a.data("flexslider") && new d.flexslider(this, j)
            });
            var l = d(this).data("flexslider");
            switch (j) {
                case "play":
                    l.play();
                    break;
                case "pause":
                    l.pause();
                    break;
                case "next":
                    l.flexAnimate(l.getTarget("next"), !0);
                    break;
                case "prev":
                case "previous":
                    l.flexAnimate(l.getTarget("prev"), !0);
                    break;
                default:
                    "number" === typeof j && l.flexAnimate(j, !0)
            }
        }
    })(jQuery);

    (function() {
        var t = [].indexOf || function(t) {
                for (var e = 0, n = this.length; e < n; e++) {
                    if (e in this && this[e] === t) return e
                }
                return -1
            },
            e = [].slice;
        (function(t, e) {
            if (typeof define === "function" && define.amd) {
                return define("waypoints", ["jquery"], function(n) {
                    return e(n, t)
                })
            } else {
                return e(t.jQuery, t)
            }
        })(this, function(n, r) {
            var i, o, l, s, f, u, a, c, h, d, p, y, v, w, g, m;
            i = n(r);
            c = t.call(r, "ontouchstart") >= 0;
            s = {
                horizontal: {},
                vertical: {}
            };
            f = 1;
            a = {};
            u = "waypoints-context-id";
            p = "resize.waypoints";
            y = "scroll.waypoints";
            v = 1;
            w = "waypoints-waypoint-ids";
            g = "waypoint";
            m = "waypoints";
            o = function() {
                function t(t) {
                    var e = this;
                    this.$element = t;
                    this.element = t[0];
                    this.didResize = false;
                    this.didScroll = false;
                    this.id = "context" + f++;
                    this.oldScroll = {
                        x: t.scrollLeft(),
                        y: t.scrollTop()
                    };
                    this.waypoints = {
                        horizontal: {},
                        vertical: {}
                    };
                    t.data(u, this.id);
                    a[this.id] = this;
                    t.bind(y, function() {
                        var t;
                        if (!(e.didScroll || c)) {
                            e.didScroll = true;
                            t = function() {
                                e.doScroll();
                                return e.didScroll = false
                            };
                            return r.setTimeout(t, n[m].settings.scrollThrottle)
                        }
                    });
                    t.bind(p, function() {
                        var t;
                        if (!e.didResize) {
                            e.didResize = true;
                            t = function() {
                                n[m]("refresh");
                                return e.didResize = false
                            };
                            return r.setTimeout(t, n[m].settings.resizeThrottle)
                        }
                    })
                }
                t.prototype.doScroll = function() {
                    var t, e = this;
                    t = {
                        horizontal: {
                            newScroll: this.$element.scrollLeft(),
                            oldScroll: this.oldScroll.x,
                            forward: "right",
                            backward: "left"
                        },
                        vertical: {
                            newScroll: this.$element.scrollTop(),
                            oldScroll: this.oldScroll.y,
                            forward: "down",
                            backward: "up"
                        }
                    };
                    if (c && (!t.vertical.oldScroll || !t.vertical.newScroll)) {
                        n[m]("refresh")
                    }
                    n.each(t, function(t, r) {
                        var i, o, l;
                        l = [];
                        o = r.newScroll > r.oldScroll;
                        i = o ? r.forward : r.backward;
                        n.each(e.waypoints[t], function(t, e) {
                            var n, i;
                            if (r.oldScroll < (n = e.offset) && n <= r.newScroll) {
                                return l.push(e)
                            } else if (r.newScroll < (i = e.offset) && i <= r.oldScroll) {
                                return l.push(e)
                            }
                        });
                        l.sort(function(t, e) {
                            return t.offset - e.offset
                        });
                        if (!o) {
                            l.reverse()
                        }
                        return n.each(l, function(t, e) {
                            if (e.options.continuous || t === l.length - 1) {
                                return e.trigger([i])
                            }
                        })
                    });
                    return this.oldScroll = {
                        x: t.horizontal.newScroll,
                        y: t.vertical.newScroll
                    }
                };
                t.prototype.refresh = function() {
                    var t, e, r, i = this;
                    r = n.isWindow(this.element);
                    e = this.$element.offset();
                    this.doScroll();
                    t = {
                        horizontal: {
                            contextOffset: r ? 0 : e.left,
                            contextScroll: r ? 0 : this.oldScroll.x,
                            contextDimension: this.$element.width(),
                            oldScroll: this.oldScroll.x,
                            forward: "right",
                            backward: "left",
                            offsetProp: "left"
                        },
                        vertical: {
                            contextOffset: r ? 0 : e.top,
                            contextScroll: r ? 0 : this.oldScroll.y,
                            contextDimension: r ? n[m]("viewportHeight") : this.$element.height(),
                            oldScroll: this.oldScroll.y,
                            forward: "down",
                            backward: "up",
                            offsetProp: "top"
                        }
                    };
                    return n.each(t, function(t, e) {
                        return n.each(i.waypoints[t], function(t, r) {
                            var i, o, l, s, f;
                            i = r.options.offset;
                            l = r.offset;
                            o = n.isWindow(r.element) ? 0 : r.$element.offset()[e.offsetProp];
                            if (n.isFunction(i)) {
                                i = i.apply(r.element)
                            } else if (typeof i === "string") {
                                i = parseFloat(i);
                                if (r.options.offset.indexOf("%") > -1) {
                                    i = Math.ceil(e.contextDimension * i / 100)
                                }
                            }
                            r.offset = o - e.contextOffset + e.contextScroll - i;
                            if (r.options.onlyOnScroll && l != null || !r.enabled) {
                                return
                            }
                            if (l !== null && l < (s = e.oldScroll) && s <= r.offset) {
                                return r.trigger([e.backward])
                            } else if (l !== null && l > (f = e.oldScroll) && f >= r.offset) {
                                return r.trigger([e.forward])
                            } else if (l === null && e.oldScroll >= r.offset) {
                                return r.trigger([e.forward])
                            }
                        })
                    })
                };
                t.prototype.checkEmpty = function() {
                    if (n.isEmptyObject(this.waypoints.horizontal) && n.isEmptyObject(this.waypoints.vertical)) {
                        this.$element.unbind([p, y].join(" "));
                        return delete a[this.id]
                    }
                };
                return t
            }();
            l = function() {
                function t(t, e, r) {
                    var i, o;
                    r = n.extend({}, n.fn[g].defaults, r);
                    if (r.offset === "bottom-in-view") {
                        r.offset = function() {
                            var t;
                            t = n[m]("viewportHeight");
                            if (!n.isWindow(e.element)) {
                                t = e.$element.height()
                            }
                            return t - n(this).outerHeight()
                        }
                    }
                    this.$element = t;
                    this.element = t[0];
                    this.axis = r.horizontal ? "horizontal" : "vertical";
                    this.callback = r.handler;
                    this.context = e;
                    this.enabled = r.enabled;
                    this.id = "waypoints" + v++;
                    this.offset = null;
                    this.options = r;
                    e.waypoints[this.axis][this.id] = this;
                    s[this.axis][this.id] = this;
                    i = (o = t.data(w)) != null ? o : [];
                    i.push(this.id);
                    t.data(w, i)
                }
                t.prototype.trigger = function(t) {
                    if (!this.enabled) {
                        return
                    }
                    if (this.callback != null) {
                        this.callback.apply(this.element, t)
                    }
                    if (this.options.triggerOnce) {
                        return this.destroy()
                    }
                };
                t.prototype.disable = function() {
                    return this.enabled = false
                };
                t.prototype.enable = function() {
                    this.context.refresh();
                    return this.enabled = true
                };
                t.prototype.destroy = function() {
                    delete s[this.axis][this.id];
                    delete this.context.waypoints[this.axis][this.id];
                    return this.context.checkEmpty()
                };
                t.getWaypointsByElement = function(t) {
                    var e, r;
                    r = n(t).data(w);
                    if (!r) {
                        return []
                    }
                    e = n.extend({}, s.horizontal, s.vertical);
                    return n.map(r, function(t) {
                        return e[t]
                    })
                };
                return t
            }();
            d = {
                init: function(t, e) {
                    var r;
                    if (e == null) {
                        e = {}
                    }
                    if ((r = e.handler) == null) {
                        e.handler = t
                    }
                    this.each(function() {
                        var t, r, i, s;
                        t = n(this);
                        i = (s = e.context) != null ? s : n.fn[g].defaults.context;
                        if (!n.isWindow(i)) {
                            i = t.closest(i)
                        }
                        i = n(i);
                        r = a[i.data(u)];
                        if (!r) {
                            r = new o(i)
                        }
                        return new l(t, r, e)
                    });
                    n[m]("refresh");
                    return this
                },
                disable: function() {
                    return d._invoke(this, "disable")
                },
                enable: function() {
                    return d._invoke(this, "enable")
                },
                destroy: function() {
                    return d._invoke(this, "destroy")
                },
                prev: function(t, e) {
                    return d._traverse.call(this, t, e, function(t, e, n) {
                        if (e > 0) {
                            return t.push(n[e - 1])
                        }
                    })
                },
                next: function(t, e) {
                    return d._traverse.call(this, t, e, function(t, e, n) {
                        if (e < n.length - 1) {
                            return t.push(n[e + 1])
                        }
                    })
                },
                _traverse: function(t, e, i) {
                    var o, l;
                    if (t == null) {
                        t = "vertical"
                    }
                    if (e == null) {
                        e = r
                    }
                    l = h.aggregate(e);
                    o = [];
                    this.each(function() {
                        var e;
                        e = n.inArray(this, l[t]);
                        return i(o, e, l[t])
                    });
                    return this.pushStack(o)
                },
                _invoke: function(t, e) {
                    t.each(function() {
                        var t;
                        t = l.getWaypointsByElement(this);
                        return n.each(t, function(t, n) {
                            n[e]();
                            return true
                        })
                    });
                    return this
                }
            };
            n.fn[g] = function() {
                var t, r;
                r = arguments[0], t = 2 <= arguments.length ? e.call(arguments, 1) : [];
                if (d[r]) {
                    return d[r].apply(this, t)
                } else if (n.isFunction(r)) {
                    return d.init.apply(this, arguments)
                } else if (n.isPlainObject(r)) {
                    return d.init.apply(this, [null, r])
                } else if (!r) {
                    return n.error("jQuery Waypoints needs a callback function or handler option.")
                } else {
                    return n.error("The " + r + " method does not exist in jQuery Waypoints.")
                }
            };
            n.fn[g].defaults = {
                context: r,
                continuous: true,
                enabled: true,
                horizontal: false,
                offset: 0,
                triggerOnce: false
            };
            h = {
                refresh: function() {
                    return n.each(a, function(t, e) {
                        return e.refresh()
                    })
                },
                viewportHeight: function() {
                    var t;
                    return (t = r.innerHeight) != null ? t : i.height()
                },
                aggregate: function(t) {
                    var e, r, i;
                    e = s;
                    if (t) {
                        e = (i = a[n(t).data(u)]) != null ? i.waypoints : void 0
                    }
                    if (!e) {
                        return []
                    }
                    r = {
                        horizontal: [],
                        vertical: []
                    };
                    n.each(r, function(t, i) {
                        n.each(e[t], function(t, e) {
                            return i.push(e)
                        });
                        i.sort(function(t, e) {
                            return t.offset - e.offset
                        });
                        r[t] = n.map(i, function(t) {
                            return t.element
                        });
                        return r[t] = n.unique(r[t])
                    });
                    return r
                },
                above: function(t) {
                    if (t == null) {
                        t = r
                    }
                    return h._filter(t, "vertical", function(t, e) {
                        return e.offset <= t.oldScroll.y
                    })
                },
                below: function(t) {
                    if (t == null) {
                        t = r
                    }
                    return h._filter(t, "vertical", function(t, e) {
                        return e.offset > t.oldScroll.y
                    })
                },
                left: function(t) {
                    if (t == null) {
                        t = r
                    }
                    return h._filter(t, "horizontal", function(t, e) {
                        return e.offset <= t.oldScroll.x
                    })
                },
                right: function(t) {
                    if (t == null) {
                        t = r
                    }
                    return h._filter(t, "horizontal", function(t, e) {
                        return e.offset > t.oldScroll.x
                    })
                },
                enable: function() {
                    return h._invoke("enable")
                },
                disable: function() {
                    return h._invoke("disable")
                },
                destroy: function() {
                    return h._invoke("destroy")
                },
                extendFn: function(t, e) {
                    return d[t] = e
                },
                _invoke: function(t) {
                    var e;
                    e = n.extend({}, s.vertical, s.horizontal);
                    return n.each(e, function(e, n) {
                        n[t]();
                        return true
                    })
                },
                _filter: function(t, e, r) {
                    var i, o;
                    i = a[n(t).data(u)];
                    if (!i) {
                        return []
                    }
                    o = [];
                    n.each(i.waypoints[e], function(t, e) {
                        if (r(i, e)) {
                            return o.push(e)
                        }
                    });
                    o.sort(function(t, e) {
                        return t.offset - e.offset
                    });
                    return n.map(o, function(t) {
                        return t.element
                    })
                }
            };
            n[m] = function() {
                var t, n;
                n = arguments[0], t = 2 <= arguments.length ? e.call(arguments, 1) : [];
                if (h[n]) {
                    return h[n].apply(null, t)
                } else {
                    return h.aggregate.call(null, n)
                }
            };
            n[m].settings = {
                resizeThrottle: 100,
                scrollThrottle: 30
            };
            return i.load(function() {
                return n[m]("refresh")
            })
        })
    }).call(this);
    
}(jQuery));