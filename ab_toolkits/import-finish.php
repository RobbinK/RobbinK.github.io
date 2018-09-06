<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: import-finish.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:59
##########################################################
 */

include 'init.php';
include_once 'header.php';
$type = import_get_session('type');
if ($type == 'manual')
    $f = 'import-process.php';
else
    $f = 'import-process-s.php';
?> 
<div class="heading breadCrumb">
    <?php
    include "import-breadcrump.php"
    ?> 
</div>

<div>

    <fieldset>
        <?= alert('import-finish') ?> 
        <div  id="loading_text" style="display: none;" >
            Please be patient, it may take up to one hour to import all of files completely (depends on your files, db records,...)<br>
            <font style="font:16px  arial bold;">Importing...</font>
        </div>
        <img id="loading" src="images/loading.gif" style="display: none"/>  
        <button id="process" style="font:16px  arial bold;padding: 10px">Start Importing</button>

        <div id="fb"></div>
        <a href="import-test1.php">Games File Information<a>
    </fieldset>
</div> 
<script type="text/javascript">
    $(function() {
        $('#process').click(function() {
            $(this).fadeOut();
            showloading();
            $.ajax({
                url: '<?= $f ?>',
                type: 'get'
            });
            $(this).everyTime(1000, function(i) {
                var $this = $(this);
                $.ajax({
                    url: '<?= $f ?>?getst',
                    type: 'get',
                    dataType: 'json',
                    success: function(f) {
                        if (f.data != '')
                            $('#fb').append(f.data);
                        if (f.finish == 1) {
                            finish();
                            hideloading();
                            $this.stopTime();
                        }
                    }
                });

            });

        });

    });

    function showloading() {
        $('#loading').fadeIn();
        $('#loading_text').fadeIn();
    }

    function hideloading() {
        $('#loading').fadeOut();
        $('#loading_text').fadeOut();
    }

    function finish() {
        $('.breadCrumb li:last').find('.sep-pending').removeClass('sep-pending').addClass('sep-ok');
        $('.breadCrumb li:last').find('span:last').html('Done');
    }
</script>
<?php
include_once 'footer.php';
?>