/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: abs_dashboard.js
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:55
##########################################################
 */
$(document).ready(function () {

//* small charts
    abs_sparkline.init();
    if (window.generatingStats)
        abs_charts.fl_1();
    abs_charts.fl_2();
    feed_grid.small();
    abs_marketplace.init();
    news.init();
    //* resize elements on window resize
    var lastWindowHeight = $(window).height();
    var lastWindowWidth = $(window).width();
    $(window).on("debouncedresize", function () {
        if ($(window).height() != lastWindowHeight || $(window).width() != lastWindowWidth) {
            lastWindowHeight = $(window).height();
            lastWindowWidth = $(window).width();
            //* On resize do someting

        }
    });
    //* to top
    $().UItoTop({inDelay: 200, outDelay: 200, scrollSpeed: 500});

    $('.update-dismiss').click(function () {
        $(this).closest('.alert').slideUp(600, function () {
            $(this).remove()
        });
        ABSetCookie('dismissUpdate', 1, 24 * 60);
        return false;
    });

    $('.msg-dismiss').click(function () {
        $(this).closest('.alert').fadeOut(600, function () {
            $(this).remove()
        });
        ABSetCookie("dismissMsg" + $(this).closest('.alert').find('.msgid').data('id'), 1, 30 * 24 * 60);
        return false;
    });

    $('.news-dismiss').click(function () {
        $(this).closest('.alert').fadeOut(600, function () {
            $(this).remove()
        });
        ABSetCookie("dismissNews" + $(this).data('newsid'), 1, 365 * 24 * 60);
        return false;
    });

    /*
    // show switcher menu for first time
    if (!$.cookie('ab_show_switchermenu'))
    {
        setTimeout(function(){
            $(".ssw_trigger").trigger('click');
            setTimeout(function(){
                $(".ssw_trigger").trigger('click');
            },2500);
        },2000);
        $.cookie('ab_show_switchermenu',1);
    }
    */
});
//* small charts
abs_sparkline = {
    init: function () {

        sparkline_create = function (id, param) {
            $(id).each(function () {
                $(this).sparkline(eval($(this).html()), param);
            })

        };
        sparkline_create('.p_bar_up', {
            type: 'bar',
            height: '32',
            barWidth: 6,
            barColor: '#5fbf00'
        });
        sparkline_create('.p_bar_down', {
            type: 'bar',
            height: '32',
            barWidth: 6,
            zeroAxis: false,
            barColor: '#E11B28'
        });
        sparkline_create('.p_line_up', {
            type: 'line',
            width: '50',
            height: '32',
            lineColor: '#4ca2c4',
            fillColor: '#b4dbeb'
        });
        sparkline_create('.p_line_down', {
            type: 'line',
            width: '50',
            height: '32',
            lineColor: '#E11B28',
            fillColor: '#F7BFC3'
        });
        $('.p_line_up,.p_line_down').parent().css({width: $('.p_bar_up,.p_line_down').parent().width() * 0.755});
        $('.p_bar_up,.p_bar_down').parent().css({width: $('.p_bar_up,.p_bar_down').parent().width() * 0.87});
    }


};
//* charts
function showTooltip(x, y, contents) {
    $('<div id="tooltip" style="border-radius:3px !important">' + contents + '</div>').css({
        position: 'absolute',
        display: 'none',
        top: y + 5,
        left: x + 5,
        padding: '4px 7px',
        'background-color': '#000',
        color: 'white',
        opacity: 0.80
    }).appendTo("body").fadeIn(200);
}

abs_charts = {
    fl_1: function () {
// Setup the placeholder reference
        var elem = $('#fl_1');
        if (!elem.length) return false;

        var options = {
            series: {
                lines: {
                    show: true,
                    lineWidth: 1,
                    fill: true,
                    fillColor: {colors: [{opacity: 0.5}, {opacity: 0.015}]}
                },
                points: {
                    show: true,
                    lineWidth: 1
                },
                shadowSize: 0
            },
            grid: {
                hoverable: true,
                clickable: true,
                tickColor: "#f9f9f9",
                borderWidth: 0
            },
            legend: {
                show: false
            },
            colors: [ "#FFC200","#FA5833","#eae874","#bdea74", "#2FABE9","#66bd29"],
            yaxes: [
                {min: 0},
                {position: "right"}
            ],
            xaxis: {
                mode: "time",
                //ticks: 15,
                minTickSize: [2, "day"],
                autoscaleMargin: 0
            }
        };
        // Setup the placeholder reference
        var xy = [];
        s = elem.data('xy');
        eval("xy=[" + s + "];");
        for (var i = 0; i < xy.length; i++)
            xy[i][0] = new Date(xy[i][0]).getTime();
        // Setup the flot chart using our data
        $.plot(elem,
            [
                {data: xy, label: "Visit"}
            ], options);
        var previousPoint = null;
        elem.bind("plothover", function (event, pos, item) {
            $("#x").text(pos.x.toFixed(2));
            $("#y").text(pos.y.toFixed(2));

            if (item) {
                if (previousPoint != item.dataIndex) {
                    previousPoint = item.dataIndex;

                    $("#tooltip").remove();
                    var x = item.datapoint[0].toFixed(2),
                        visit = item.datapoint[1];

                    showTooltip(item.pageX, item.pageY, "Visit : " + visit);
                }
            }
            else {
                $("#tooltip").remove();
                previousPoint = null;
            }
        });
    },
    fl_2: function () {
        // Setup the placeholder reference
        var elem = $('#fl_2');
        if (!elem.length) return false;

        var dollari = function (val, axis) {
            return "$" + val + '.00';
        }
        var options = {
            series: {
                lines: {
                    show: true,
                    lineWidth: 2,
                    fill: true,
                    fillColor: {colors: [{opacity: 0.5}, {opacity: 0.015}]}
                },
                points: {
                    show: true,
                    lineWidth: 2
                },
                shadowSize: 0
            },
            yaxis: {ticks: 5, tickDecimals: 0, tickFormatter: dollari},
            xaxis: {
                mode: "time",
                //ticks: 15,
                minTickSize: [2, "day"],
                autoscaleMargin: 0
            },
            grid: {
                hoverable: true,
                clickable: true,
                tickColor: "#f9f9f9",
                borderWidth: 0
            },
            legend: {
                show: false
            },
            colors: ["#66bd29", "#2FABE9", "#bdea74", "#eae874", "#FA5833"],
            lines: {
                show: true,
                lineWidth: 1
            }
        };
        // Setup the placeholder reference 
        var xy = [];
        s = elem.data('xy');
        eval("xy=[" + s + "];");
        for (var i = 0; i < xy.length; i++)
            xy[i][0] = new Date(xy[i][0]).getTime();
        // Setup the flot chart using our data
        $.plot(elem,
            [
                {data: xy, label: "Earning ($)"}
            ], options);

        // Bind the plot hover
        var previousPoint = null;
        elem.bind("plothover", function (event, pos, item) {
            $("#x").text(pos.x.toFixed(2));
            $("#y").text(pos.y.toFixed(2));

            if (item) {
                if (previousPoint != item.dataIndex) {
                    previousPoint = item.dataIndex;

                    $("#tooltip").remove();
                    var x = item.datapoint[0].toFixed(2),
                        earning = item.datapoint[1].toFixed(2);

                    showTooltip(item.pageX, item.pageY, "Earning : $" + earning);
                }
            }
            else {
                $("#tooltip").remove();
                previousPoint = null;
            }
        });
    }
};
abs_marketplace = {
    init: function () {
        $('.post-title').click(function () {
            var eid = $(this).data('id');
            var tid = $(this).closest('.tab-pane').attr('id');
            switch (tid) {
                case 'tab_br1':
                    url = "<?=url::router('adminlinksale')?>";
                    break;
                case 'tab_br2':
                    url = "<?=url::router('adminsitesale')?>";
                    break;
                case 'tab_br3':
                    url = "<?=url::router('admindomainsale')?>";
                    break;
                case 'tab_br4':
                    url = "<?=url::router('admingamesponsorship')?>";
                    break;
                case 'tab_br5':
                    url = "<?=url::router('adminlinkexchangerequests')?>";
                    break;
                case 'tab_br6':
                    url = "<?=url::router('adminarcadediscussions')?>";
                    break;
            }

            $.colorbox({
                href: url + '?showid=' + eid,
                maxWidth: '70%',
                maxHeight: '60%',
                opacity: '0.2',
                loop: false,
                fixed: true
            });
            return false;
        });
    }
};
feed_grid = {
    small: function () {
//* small gallery grid
        $('#small_grid ul').fadeOut().imagesLoaded(function () {
            $(this).fadeIn(1000);
// Prepare layout options.
            var options = {
                autoResize: true, // This will auto-update the layout when the browser window is resized.
                container: $('#small_grid'), // Optional, used for some extra CSS styling
                offset: 6, // Optional, the distance between grid items
                itemWidth: $(this).parent().data('width'), // Optional, the width of a grid item (li)
                flexibleItemWidth: false,
            };
            // Get a reference to your grid items.
            var handler = $('#small_grid ul li');
            $('#small_grid ul li').css({width: options.itemWidth});
            // Call the layout function.
            handler.wookmark(options);
            $('#small_grid ul li > a').click(function () {
                var eid = $(this).data('id');
                $('body').modalmanager('loading');
                setTimeout(function () {
                    var $modal = $('#feed-modal');
                    $modal.find('.modal-body').load(window.openfeed_url + '?revshare&openg&id=' + eid, function () {
                        $modal.modal({dynamic: true, height: 330, width: 600});
                    });
                }, 100);
                return false;
            });
        });
    }
};
news = {
    init: function () {
        $('.news-block,.news-detail').unbind('click').click(function () {
            var id = $(this).data('id');
            $.colorbox({
                href: window.myself_url_nonqry + '?newsid=' + id,
                maxWidth: '70%',
                maxHeight: '60%',
                opacity: '0.2',
                loop: false,
                fixed: true
            });
            return false;
        });
    }
};

