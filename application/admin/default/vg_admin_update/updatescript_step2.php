<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: updatescript_step2.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:56
##########################################################
 */

### call header
abs_admin_inc(l_basic);
abs_admin_inc(l_sticky);
get_header();
#**************  
?> 
<style>
    .input-append .add-on {
        margin-left: -1px !important; 
        -webkit-border-radius: 0 4px 4px 0 !important;
        -moz-border-radius: 0 4px 4px 0 !important;
        border-radius: 0 4px 4px 0 !important;
        direction: ltr;
    }
    .input-append input  { 
        float: left;
        -webkit-border-radius: 4px 0 0 4px !important;
        -moz-border-radius: 4px 0 0 4px !important;
        border-radius: 4px 0 0 4px !important;
    }

    .hint{
        font: 11px arial;
        line-height: 14px;
        color: #030303;
        background: #E2F2FC;
        border: solid 1px #B9CCEE;
        padding: 2px 5px;
        margin: 0 10px 5px 10px;
        border-radius: 3px;
    }
    .hint a{ 
        font-weight: bold;
        text-decoration: underline;
    }
    .hint b{ 
        color:#B81818;
    }
</style>
<div id="contentwrapper">
    <div class="main_content"> 
        <!-- Navigation Menu -->
        <nav>
            <div id="jCrumbs" class="breadCrumb module">
                <ul>
                    <li>
                        <a href="<?= url::router('admindashboard'); ?>"><i class="icon-home"></i></a>
                    </li>
                    <li>
                        <?= L::forms_updating_script; ?> 
                    </li>
                    <li>
                        <?= L::forms_updating_to_new_version; ?> 
                    </li>
                </ul>
            </div>
        </nav>
        <!-- /Navigation Menu --> 
        <div class="row-fluid">
            <div>
                <h3 class="heading"><?= L::forms_updating_to_new_version; ?></h3> 
                <div class="alert alert-danger"><?= L::forms_dont_leave; ?></div>
                <form class="well form-inline">
                    <p class="f_legend"><?= L::forms_update_process; ?></p> 
                    <!--progressbar-->
                    <div class="BarContainer sepH_b progress progress-danger progress-striped active">
                        <div style="width: 0%" class="bar"></div>
                    </div>
                    <!--//progressbar-->
                    <div id='messages'>

                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<?php
get_sidebar();
get_footer('_script');
?>
<script type="text/javascript">
    function showST(txt) {
        var al = $("<p><p>");
        $('#messages').append(al).find('p:last').html(txt).hide().fadeIn(1000);
    }
    function finish(s) {
        if (s) {
            function ChangeClass(ClassName) {
                $('.BarContainer').removeClass('progress-info progress-success progress-warning progress-danger').addClass(ClassName);
            }
            ChangeClass('progress-success');
        }
        $('.BarContainer .bar').css('width', '100%');
        setTimeout(function() {
            $('.BarContainer').removeClass('active');
        }, 500);
    }

    function progress() {
        max = 6;
        Pfull = $('.BarContainer').width();
        Bwidth = $('.BarContainer .bar').width();
        step = Pfull / max;

        function ChangeClass(ClassName) {
            $('.BarContainer').removeClass('progress-info progress-success progress-warning progress-danger').addClass(ClassName);
        }

        if (Bwidth > Pfull * 0.10)
            ChangeClass('progress-warning');

        if (Bwidth > Pfull * 0.4)
            ChangeClass('progress-info');

        if (Bwidth > Pfull * 0.8)
            ChangeClass('progress-success');

        $('.BarContainer .bar').width(Bwidth + step);
        setTimeout(function() {
            if ($('.BarContainer .bar').width() + step >= Pfull) {
                $('.BarContainer .bar').css('width', '100%');
                $('.BarContainer').removeClass('active');
            }
        }, 500);

    }
    function ajaxProcess(params) {
        $.ajax({
            type: 'POST',
            data: params,
            url: "<?= url::itself()->url_nonqry(array('step' => 2)) ?>",
            success: function(result) {
                eval(result);
            }
        });
    }
    $(function() {
        setTimeout(function() {
            progress();
            showST('<?= addslashes(L::alert_checking_ftp);?>');
            ajaxProcess({act: 'ftpcon'});
        }, 3000);
    });


</script>
<?php
get_footer();
?>