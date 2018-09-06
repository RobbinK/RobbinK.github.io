/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: abs_common.js
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:55
##########################################################
 */
function is_touch_device() {
    return !!('ontouchstart' in window);
}
function ABSetCookie(name, val, minutes) {
    var date = new Date();
    date.setTime(date.getTime() + (minutes * 60 * 1000));
    $.cookie(name, val, {expires: date});
}

function encodeURIComponentUnicode(str) {
    return encodeURIComponent(str).replace(/%([0-9A-F]{2})/g, function (match, p1) {
        return String.fromCharCode('0x' + p1);
    });
}
function encodePostData(obj) {
    return base64.encode(JSON.stringify(obj));
}

$(function () {
    //* accordion change actions
    $('#side_accordion').on('hidden shown', function () {
        abs_sidebar.make_active();
        abs_sidebar.scrollbar();
    });
    $('#side_accordion .accordion-group').each(function () {
        if ($(this).find('.label-important').length)
            $(this).find('.accordion-heading').addClass('sdb_h_active_red');
    });
    //* resize elements on window resize
    var lastWindowHeight = $(window).height();
    var lastWindowWidth = $(window).width();
    $(window).on("debouncedresize", function () {
        if ($(window).height() != lastWindowHeight || $(window).width() != lastWindowWidth) {
            lastWindowHeight = $(window).height();
            lastWindowWidth = $(window).width();
            if (!is_touch_device()) {
                $('.sidebar_switch').qtip('hide');
            }
        }
    });

    $('.systemMessagess .close').unbind('click').click(function () {
        $(this).closest('.alert').fadeOut(600, function () {
            $(this).remove()
        });
        $cookieName = $($('.systemMessagess').children()[1]).data('cookiename');
        ABSetCookie($cookieName, 1, 24 * 60);
        return false;
    });

    //* tooltips
    abs_tips.init();
    if (!is_touch_device()) {
        //* popovers
        abs_popOver.init();
    }
    //* sidebar
    abs_sidebar.init();
    abs_sidebar.make_active();
    //* breadcrumbs
    abs_crumbs.init();
    //* pre block prettify
    if (typeof prettyPrint == 'function') {
        prettyPrint();
    }
    //* external links
    abs_external_links.init();
    //* accordion icons
    abs_acc_icons.init();
    //* colorbox single
    abs_colorbox_single.init();
    //* main menu mouseover
    abs_nav_mouseover.init();
    //* top submenu
    abs_submenu.init();
    //* top submenu
    abs_auto_expand.init();

    //* mobile navigation
    selectnav('mobile-nav', {
        indent: '-'
    });

    abs_sidebar.scrollbar();

    //* style switcher
    abs_style_sw.init();

    //* fix for dropdown menu (touch devices)
    $('body').on('touchstart.dropdown', '.dropdown-menu', function (e) {
        e.stopPropagation();
    });

    // correct checkbox
    $('form input:checkbox').each(function () {
        if (!$(this).next('.hiddenCheckbox').length) {
            $(this).after("<input  type='hidden' class='hiddenCheckbox' value='' name='" + $(this).attr('name') + "'>");
        }
        var hiddenCheckbox = function (el) {
            if ($(el).is(':checked'))
                $(el).next('.hiddenCheckbox').attr('disabled', true);
            else
                $(el).next('.hiddenCheckbox').attr('disabled', false);
        };
        $(this).unbind('click').bind('click', function () {
            hiddenCheckbox(this);
        });
        hiddenCheckbox(this);
    });

});

abs_sidebar = {
    init: function () {
        // sidebar onload state
        if ($(window).width() > 979) {
            if (!$('body').hasClass('sidebar_hidden')) {
                if ($.cookie('abs_sidebar') == "hidden") {
                    $('body').addClass('sidebar_hidden');
                    $('.sidebar_switch').toggleClass('on_switch off_switch').attr('title', 'Show Sidebar');
                }
            } else {
                $('.sidebar_switch').toggleClass('on_switch off_switch').attr('title', 'Show Sidebar');
            }
        } else {
            $('body').addClass('sidebar_hidden');
            $('.sidebar_switch').removeClass('on_switch').addClass('off_switch');
        }

        abs_sidebar.info_box();
        //* sidebar visibility switch
        $('.sidebar_switch').click(function () {
            $('.sidebar_switch').removeClass('on_switch off_switch');
            if ($('body').hasClass('sidebar_hidden')) {
                $.cookie('abs_sidebar', null);
                $('body').removeClass('sidebar_hidden');
                $('.sidebar_switch').addClass('on_switch').show();
                $('.sidebar_switch').attr('title', "Hide Sidebar");
            } else {
                $.cookie('abs_sidebar', 'hidden');
                $('body').addClass('sidebar_hidden');
                $('.sidebar_switch').addClass('off_switch');
                $('.sidebar_switch').attr('title', "Show Sidebar");
            }
            abs_sidebar.info_box();
            $(window).resize();
        });
        //* prevent accordion link click
        $('.sidebar .accordion-toggle').click(function (e) {
            e.preventDefault()
        });
        $(window).on("debouncedresize", function (event) {
            abs_sidebar.scrollbar();
        });
    },
    info_box: function () {
        var s_box = $('.sidebar_info');
        var s_box_height = s_box.actual('height');
        s_box.css({
            'height': s_box_height
        });
        $('.push').height(s_box_height);
        $('.sidebar_inner').css({
            'margin-bottom': '-' + s_box_height + 'px',
            'min-height': '100%'
        });
    },
    make_active: function () {
        var thisAccordion = $('#side_accordion');
        thisAccordion.find('.accordion-heading').removeClass('sdb_h_active');
        var thisHeading = thisAccordion.find('.accordion-body.in').prev('.accordion-heading');
        if (thisHeading.length) {
            thisHeading.addClass('sdb_h_active');
        }
    },
    scrollbar: function () {
        if ($('.sidebar_inner_scroll').length) {
            $('.sidebar_inner_scroll').slimScroll({
                position: 'left',
                height: 'auto',
                alwaysVisible: true,
                opacity: '0.2',
                wheelStep: is_touch_device() ? 40 : 10
            });
        }
    }
};

//* tooltips
abs_tips = {
    init: function () {
        if (!is_touch_device()) {
            var shared = {
                style: {
                    classes: 'ui-tooltip-shadow ui-tooltip-tipsy'
                },
                show: {
                    delay: 100
                },
                hide: {
                    delay: 0
                }
            };
            if ($('.ttip_b').length) {
                $('.ttip_b').qtip($.extend({}, shared, {
                    position: {
                        my: 'top center',
                        at: 'bottom center',
                        viewport: $(window)
                    }
                }));
            }
            if ($('.ttip_t').length) {
                $('.ttip_t').qtip($.extend({}, shared, {
                    position: {
                        my: 'bottom center',
                        at: 'top center',
                        viewport: $(window)
                    }
                }));
            }
            if ($('.ttip_l').length) {
                $('.ttip_l').qtip($.extend({}, shared, {
                    position: {
                        my: 'right center',
                        at: 'left center',
                        viewport: $(window)
                    }
                }));
            }
            if ($('.ttip_r').length) {
                $('.ttip_r').qtip($.extend({}, shared, {
                    position: {
                        my: 'left center',
                        at: 'right center',
                        viewport: $(window)
                    }
                }));
            }
            ;
        }
    }
};

//* popovers
abs_popOver = {
    init: function () {
        $(".pop_over").each(function () {
            $(this).popover({
                trigger: $(this).data('trigger') || 'hover',
                html: true,
                template: '<div class="abspopover popover hidden-phone"><div class="arrow"></div><div class="popover-inner"><h3 class="popover-title"></h3><div class="popover-content"><p></p></div></div></div>'
            }).on({
                init: function (e) {
                    $('.abspopover').css({
                        right: $('.abspopover').css('left') * -1,
                        left: ''
                    });
                }
            });
            if ($(this).hasClass('notlink')) {
                $(this).click(function (e) {
                    e.preventDefault();
                });
            }
        });
    }
};

//* breadcrumbs
abs_crumbs = {
    init: function () {
        if ($('#jCrumbs').length) {
            $('#jCrumbs').jBreadCrumb({
                endElementsToLeaveOpen: 0,
                beginingElementsToLeaveOpen: 0,
                timeExpansionAnimation: 500,
                timeCompressionAnimation: 500,
                timeInitialCollapse: 500,
                previewWidth: 30
            });
        }
    }
};
//* textarea autosize
abs_auto_expand = {
    init: function () {
        $('.auto_expand').each(function () {
            $(this).data('default-height', $(this).height());
        });
        $('.auto_expand').autosize();
    }
};
//* external links
abs_external_links = {
    init: function () {
        $("a[href^='http']").not('.thumbnail>a,.ext_disabled,.btn').each(function () {
            $(this).attr('target', '_blank').addClass('external_link');
        })
    }
};

//* accordion icons
abs_acc_icons = {
    init: function () {
        var accordions = $('.main_content .accordion');

        accordions.find('.accordion-group').each(function () {
            var acc_active = $(this).find('.accordion-body').filter('.in');
            acc_active.prev('.accordion-heading').find('.accordion-toggle').addClass('acc-in');
        });
        accordions.on('show', function (option) {
            $(this).find('.accordion-toggle').removeClass('acc-in');
            $(option.target).prev('.accordion-heading').find('.accordion-toggle').addClass('acc-in');
        });
        accordions.on('hide', function (option) {
            $(option.target).prev('.accordion-heading').find('.accordion-toggle').removeClass('acc-in');
        });
    }
};

//* main menu mouseover
abs_nav_mouseover = {
    init: function () {
        $('header li.dropdown').mouseenter(function () {
            if ($('body').hasClass('menu_hover')) {
                $(this).addClass('navHover')
            }
        }).mouseleave(function () {
            if ($('body').hasClass('menu_hover')) {
                $(this).removeClass('navHover open')
            }
        });
    }
};

//* single image colorbox
abs_colorbox_single = {
    init: function () {
        if ($('.cbox_single').length) {
            $('.cbox_single').colorbox({
                maxWidth: '80%',
                maxHeight: '80%',
                opacity: '0.2',
                fixed: true
            });
        }
    }
};

//* submenu
abs_submenu = {
    init: function () {
        $('.dropdown-menu li').each(function () {
            var $this = $(this);
            if ($this.children('ul').length) {
                $this.addClass('sub-dropdown');
                $this.children('ul').addClass('sub-menu');
            }
        });

        $('.sub-dropdown').on('mouseenter', function () {
            $(this).addClass('active').children('ul').addClass('sub-open');
        }).on('mouseleave', function () {
            $(this).removeClass('active').children('ul').removeClass('sub-open');
        })

    }
};

//* style switcher
abs_style_sw = {
    init: function () {
        if ($('.style_switcher').length) {
            $('body').append('<a class="ssw_trigger" href="javascript:void(0)"><i class="icon-cog icon-white"></i></a>');

            $(".ssw_trigger").click(function () {
                $(".style_switcher").toggle("fast");
                $(this).toggleClass("active");
                return false;
            });
        }
    }
};

//* Cache Ajax Functions
abs_cache = {
    clean_mysql: function () {
        st1 = $.sticky(window.alert_deleting_cache, {
            autoclose: false,
            position: "top-right",
            type: "st-info",
            speed: "fast"
        });
        $.ajax({
            type: 'get',
            url: window.myself_url_nonqry + '?delmysqlcaches',
            success: function (result) {
                $.stickyhide(st1.id);
                if (result == 1) {
                    $.sticky(window.alert_cache_removed, {
                        autoclose: 5000,
                        position: 'top-right',
                        type: 'st-success',
                        speed: 'fast'
                    });
                    return true;
                } else {
                    $.sticky(window.alert_failed_cache, {
                        autoclose: 5000,
                        position: 'top-right',
                        type: 'st-error',
                        speed: 'fast'
                    });
                    return true;
                }
            }
        });
        return false;
    }
};


