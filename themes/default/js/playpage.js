var loading_config = {
    'indicatorZIndex': 990,
    'overlayZIndex': 990
};

var progressbar = {
    pr: null,
    init: function(jqueryId) {
        this.pr = $(jqueryId);
        var $pr = this.pr;
        progressLabel = $pr.find(".progress-label");
        $pr.progressbar({
            value: false,
            change: function() {
                progressLabel.text($pr.progressbar("value") + "%");
            }
        });
        return;
    },
    progress: function(value) {
        var $pr = this.pr;
        var lastval = $pr.progressbar("value") || 0;
        var newvalue = value;
        var adjust;
        if (newvalue > 100)
            newvalue = 100;
        if (newvalue > lastval) {
            adjust = 1;
        }
        else if (newvalue < lastval) {
            adjust = -1;
        }


        var $intrv = setInterval(function() {
            lastval = $pr.progressbar("value") || 0;
            if (newvalue != lastval) {
                $pr.progressbar("value", lastval + adjust);
                return;
            } else if (newvalue == 0) {
                $pr.progressbar("value", 0);
            }
            clearInterval($intrv);
        }, 10);
        return;
    }
};

$(document).ready(function() {
    $("#submitobroken").button();

    //captcha
    var renewCaptcha = function() {
        $('#captcha2').attr('src', plugin_url + '/cool-php-captcha-0.3.1/call.php?name=captcha2&' + Math.random());
    };
    renewCaptcha();
    $('#change-captcha').click(function() {
        renewCaptcha();
        $('#broken input[name="broken_captcha"]').focus();
    });

    $('#broken').on('submit', function() {
        $('#broken').showLoading(loading_config);
        data = $.deparam($('#broken').serialize());
        // encode and slashes
        $.each(data, function(k, v) {
            data[k] = encodeURIComponent(v);
        });
        $.ajax({
            type: 'POST',
            url: ajaxurl + '&action=submitbroken',
            data: data,
            success: function(res) {
                $('#broken').hideLoading();
                eval(res);
            }
        });
        return false;
    });

    progressbar.init('#progressbar');

    $("#addtofav").button({
        icons: {
            primary: "ui-icon-heart"
        }});


    $('.btnrate').click(function() {
        $('#gamerate_loading').slideDown('fast');
        $('input.btnrate').attr('disabled', 'disabled');
        var _gid = $('#gameid').val();
        var _vote = $(this).data('val');
        $.ajax({
            type: "POST",
            data: {gameid: _gid, vote: _vote},
            url: ajaxurl + '&action=rate',
            success: function(data) {
                $('#gamerate_msg').fadeOut('slow', function() {
                    $('input.btnrate').removeAttr('disabled');
                    eval(data);
                    showrate();
                });


            }
        });
    });

    if ($('#gamerate_msg').length > 0) {
        $('#gamerate_msg').fadeOut('slow', function() {
            showrate();
        });
        $('#gamerate_msg').fadeIn('slow');
    }

    /*-- zoom --*/
    var step = 10;
    $('.zoom_out').click(function() {
        var val_slider = $("#slider").slider('value');
        $("#slider").slider("value", val_slider - step);
        resizeMovieClip(val_slider - step);
    });
    $('.zoom_in').click(function() {
        var val_slider = $("#slider").slider('value');
        $("#slider").slider("value", val_slider + step);
        resizeMovieClip(val_slider + step);
    });

    $("#slider").slider({
        value: 0,
        min: -20,
        max: 27,
        step: 1,
        slide: function(event, ui) {
            resizeMovieClip(ui.value);
        }

    });


    var widthOld = $('#GameFileWrapper').width();
    var heightOld = $('#GameFileWrapper').height();

    var gameWrapperWidth = $('#game_wrapper').width();
    function resizeMovieClip(value) {
        var widthNew = widthOld + (value * step);
        var heightNew = (widthNew * heightOld) / widthOld; 
        $('#GameFileWrapper').css({"width": widthNew + "px", "height": heightNew + "px"});
        if ($('#GameFileWrapper').width() > gameWrapperWidth) {
            $('#game_wrapper').css({width: $('#GameFileWrapper').width()});
            $('.side_ad').fadeOut(500);
        } else {
            $('#game_wrapper').css({width: gameWrapperWidth});
            $('.side_ad').fadeIn(500);
        }
    }




    /*-- full-screen --*/
    // open in fullscreen
    $('.requestfullscreen').click(function() {
        //.css({width: '100%', height: '100%'})
        $('#GameFileWrapper').fullscreen();
        return false;
    });

    // exit fullscreen
    $('.exitfullscreen').click(function() {
        $.fullscreen.exit();
        return false;
    });






    // document's event
    $(document).bind('fscreenchange', function(e, state, elem) {
        // if we currently in fullscreen mode
        if ($.fullscreen.isFullScreen()) {
            $('GameFileWrapper .requestfullscreen').hide();
            $('GameFileWrapper .exitfullscreen').show();
        } else {
            $('GameFileWrapper .requestfullscreen').show();
            $('GameFileWrapper .exitfullscreen').hide();
        }

        $('#state').text($.fullscreen.isFullScreen() ? '' : 'not');
    });
});


function addtofavorit(gameid)
{
    $('#adfav_loading').slideDown('fast');
    $('#adfav_msg').fadeOut('slow');
    $.ajax({
        type: "GET",
        dataType:'json',
        data: {gid: gameid, json: true},
        url: addtofavUrl,
        success: function(data) {
             eval(data.script);
            $('#adfav_msg').fadeIn('slow');
            $('#adfav_loading').slideUp('fast');
            if (data.result) {
                $('#addtofav').addClass('added');
            }
        }
    });
}

function showrate()
{
    var _gid = $('#gameid').val();
    $.ajax({
        type: "POST",
        data: {gameid: _gid},
        url: ajaxurl + '&action=showrate',
        success: function(data) {
            eval(data);
            $('#gamerate_msg').fadeIn('slow');
            $('#gamerate_loading').slideUp('fast');
        }
    })
}




