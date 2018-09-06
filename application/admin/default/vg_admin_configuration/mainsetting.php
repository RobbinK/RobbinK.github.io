<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: mainsetting.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:56
##########################################################
 */

### call header
abs_admin_inc(l_basic);
abs_admin_inc(l_sticky);
abs_admin_inc(l_validate);
abs_admin_inc(l_unserializeForm);
abs_admin_inc(l_colorbox);
get_header();
#************** 
css::load(template_url() . '/css/configuration_style' . (lang_isrtl() ? '_rtl' : null) . '.css');
?> 
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
                        <?= L::sidebar_main_set; ?>
                    </li>
                </ul>
            </div>
        </nav>
        <!-- /Navigation Menu -->
        <div class="row-fluid">
            <div>
                <h3 class="heading"> <?= L::sidebar_main_set; ?></h3> 
                <form class="form_validation_reg" id="myform" method="post" action="<?= url::itself()->fulluri() ?>"  novalidate="novalidate" enctype="multipart/form-data" onsubmit="return false">
                    <dl class="dl-horizontal">
                        <dt ><label><?= L::forms_site_name; ?></label></dt>
                        <dd >
                            <?php
                            $data = setting::get_data('site_name');
                            echo $comment = getcomment($data);
                            ?>
                            <input type="text" name="main[site_name]" value="<?= @$data['val'] ?>"  />
                            <?php if ($comment) echo '</a>'; ?> 
                        </dd>
                        <dt><label><?= L::forms_site_theme; ?></label></dt>
                        <dd class="sepH_b">
                            <?php
                            $data = setting::get_data('site_template');
                            echo $comment = getcomment($data);
                            $sitethemeval = @$data['val'];
                            ?>
                            <table>
                                <tr> 
                                    <td colspan="2">
                                        <select name="main[site_template]"  id="site_theme" class="input-medium">
                                            <?php
                                            if (isset($themes))
                                                while (current($themes)) {
                                                    ?>
                                                    <option value="<?= current($themes)->base ?>"><?= ucfirst(current($themes)->base) ?></option>
                                                    <?php
                                                    next($themes);
                                                }
                                            ?>
                                        </select>
                                        <span class="help-inline"><a href="<?= master_url ?>/shop/showallthemes.html">Template Shop</a></span>
                                        <span style="help-block">
                                            <?php
                                            if (isset($themes)) {
                                                reset($themes);
                                                while (current($themes)) {
                                                    $t = ROOT_PATH . '/' . DEFAUT_THEMES_DIR . '/' . current($themes)->base . '/screenshot.png';
                                                    if (!file_exists($t))
                                                        $t = static_path() . '/images/no-img2.jpg';
                                                    ?>
                                                    <p style="display: none"  id="theme_<?= preg_replace('/[^\w]|\-/', '_', current($themes)->base) ?>"><?= pengu_image::resize($t, null, 80)->ShowIMGTag() ?></p>
                                                    <?php
                                                    next($themes);
                                                }
                                            }
                                            ?>
                                            <div id="thumb"></div>
                                            <div style="margin-top: 10px">
                                                <div class="btn-group" id='preview'>
                                                    <button data-toggle="dropdown" class="btn btn-mini dropdown-toggle"><?= L::forms_preview ?> <span class="caret"></span></button>
                                                    <ul class="dropdown-menu">
                                                        <li><a href="javascript:void(0);" data-width="90%" data-height="80%" data-extparams="">Desktop Mode</a></li>
                                                        <li><a href="javascript:void(0);" data-width="272px" data-height="536px" data-extparams="&abandroid">Android Mode</a></li>
                                                        <li><a href="javascript:void(0);" data-width="253px" data-height="521px" data-extparams="&abios">IOS mode</a></li>
                                                    </ul>
                                                </div>
                                                <!--<a href='#' class='btn btn-mini' id='preview'><?= L::forms_preview ?></a>-->
                                                <a href="<?= url::router('admin-themesettings') ?>" class="btn btn-mini"><?= L::sidebar_theme_set; ?></a>
                                            </div>
                                        </span>
                                    </td>
                                </tr>
                            </table>
                            <?php if ($comment) echo '</a>'; ?> 
                        </dd>  
                        <dt><label>Time zone</label></dt>
                        <dd class="sepH_b">
                            <?php
                            $timezones = array(
                                'Pacific/Midway' => "(GMT-11:00) Midway Island",
                                'US/Samoa' => "(GMT-11:00) Samoa",
                                'US/Hawaii' => "(GMT-10:00) Hawaii",
                                'US/Alaska' => "(GMT-09:00) Alaska",
                                'US/Pacific' => "(GMT-08:00) Pacific Time (US &amp; Canada)",
                                'America/Tijuana' => "(GMT-08:00) Tijuana",
                                'US/Arizona' => "(GMT-07:00) Arizona",
                                'US/Mountain' => "(GMT-07:00) Mountain Time (US &amp; Canada)",
                                'America/Chihuahua' => "(GMT-07:00) Chihuahua",
                                'America/Mazatlan' => "(GMT-07:00) Mazatlan",
                                'America/Mexico_City' => "(GMT-06:00) Mexico City",
                                'America/Monterrey' => "(GMT-06:00) Monterrey",
                                'Canada/Saskatchewan' => "(GMT-06:00) Saskatchewan",
                                'US/Central' => "(GMT-06:00) Central Time (US &amp; Canada)",
                                'US/Eastern' => "(GMT-05:00) Eastern Time (US &amp; Canada)",
                                'US/East-Indiana' => "(GMT-05:00) Indiana (East)",
                                'America/Bogota' => "(GMT-05:00) Bogota",
                                'America/Lima' => "(GMT-05:00) Lima",
                                'America/Caracas' => "(GMT-04:30) Caracas",
                                'Canada/Atlantic' => "(GMT-04:00) Atlantic Time (Canada)",
                                'America/La_Paz' => "(GMT-04:00) La Paz",
                                'America/Santiago' => "(GMT-04:00) Santiago",
                                'Canada/Newfoundland' => "(GMT-03:30) Newfoundland",
                                'America/Buenos_Aires' => "(GMT-03:00) Buenos Aires",
                                'Greenland' => "(GMT-03:00) Greenland",
                                'Atlantic/Stanley' => "(GMT-02:00) Stanley",
                                'Atlantic/Azores' => "(GMT-01:00) Azores",
                                'Atlantic/Cape_Verde' => "(GMT-01:00) Cape Verde Is.",
                                'Africa/Casablanca' => "(GMT) Casablanca",
                                'Europe/Dublin' => "(GMT) Dublin",
                                'Europe/Lisbon' => "(GMT) Lisbon",
                                'Europe/London' => "(GMT) London",
                                'Africa/Monrovia' => "(GMT) Monrovia",
                                'Europe/Amsterdam' => "(GMT+01:00) Amsterdam",
                                'Europe/Belgrade' => "(GMT+01:00) Belgrade",
                                'Europe/Berlin' => "(GMT+01:00) Berlin",
                                'Europe/Bratislava' => "(GMT+01:00) Bratislava",
                                'Europe/Brussels' => "(GMT+01:00) Brussels",
                                'Europe/Budapest' => "(GMT+01:00) Budapest",
                                'Europe/Copenhagen' => "(GMT+01:00) Copenhagen",
                                'Europe/Ljubljana' => "(GMT+01:00) Ljubljana",
                                'Europe/Madrid' => "(GMT+01:00) Madrid",
                                'Europe/Paris' => "(GMT+01:00) Paris",
                                'Europe/Prague' => "(GMT+01:00) Prague",
                                'Europe/Rome' => "(GMT+01:00) Rome",
                                'Europe/Sarajevo' => "(GMT+01:00) Sarajevo",
                                'Europe/Skopje' => "(GMT+01:00) Skopje",
                                'Europe/Stockholm' => "(GMT+01:00) Stockholm",
                                'Europe/Vienna' => "(GMT+01:00) Vienna",
                                'Europe/Warsaw' => "(GMT+01:00) Warsaw",
                                'Europe/Zagreb' => "(GMT+01:00) Zagreb",
                                'Europe/Athens' => "(GMT+02:00) Athens",
                                'Europe/Bucharest' => "(GMT+02:00) Bucharest",
                                'Africa/Cairo' => "(GMT+02:00) Cairo",
                                'Africa/Harare' => "(GMT+02:00) Harare",
                                'Europe/Helsinki' => "(GMT+02:00) Helsinki",
                                'Europe/Istanbul' => "(GMT+02:00) Istanbul",
                                'Asia/Jerusalem' => "(GMT+02:00) Jerusalem",
                                'Europe/Kiev' => "(GMT+02:00) Kyiv",
                                'Europe/Minsk' => "(GMT+02:00) Minsk",
                                'Europe/Riga' => "(GMT+02:00) Riga",
                                'Europe/Sofia' => "(GMT+02:00) Sofia",
                                'Europe/Tallinn' => "(GMT+02:00) Tallinn",
                                'Europe/Vilnius' => "(GMT+02:00) Vilnius",
                                'Asia/Baghdad' => "(GMT+03:00) Baghdad",
                                'Asia/Kuwait' => "(GMT+03:00) Kuwait",
                                'Africa/Nairobi' => "(GMT+03:00) Nairobi",
                                'Asia/Riyadh' => "(GMT+03:00) Riyadh",
                                'Asia/Tehran' => "(GMT+03:30) Tehran",
                                'Europe/Moscow' => "(GMT+04:00) Moscow",
                                'Asia/Baku' => "(GMT+04:00) Baku",
                                'Europe/Volgograd' => "(GMT+04:00) Volgograd",
                                'Asia/Muscat' => "(GMT+04:00) Muscat",
                                'Asia/Tbilisi' => "(GMT+04:00) Tbilisi",
                                'Asia/Yerevan' => "(GMT+04:00) Yerevan",
                                'Asia/Kabul' => "(GMT+04:30) Kabul",
                                'Asia/Karachi' => "(GMT+05:00) Karachi",
                                'Asia/Tashkent' => "(GMT+05:00) Tashkent",
                                'Asia/Kolkata' => "(GMT+05:30) Kolkata",
                                'Asia/Kathmandu' => "(GMT+05:45) Kathmandu",
                                'Asia/Yekaterinburg' => "(GMT+06:00) Ekaterinburg",
                                'Asia/Almaty' => "(GMT+06:00) Almaty",
                                'Asia/Dhaka' => "(GMT+06:00) Dhaka",
                                'Asia/Novosibirsk' => "(GMT+07:00) Novosibirsk",
                                'Asia/Bangkok' => "(GMT+07:00) Bangkok",
                                'Asia/Jakarta' => "(GMT+07:00) Jakarta",
                                'Asia/Krasnoyarsk' => "(GMT+08:00) Krasnoyarsk",
                                'Asia/Chongqing' => "(GMT+08:00) Chongqing",
                                'Asia/Hong_Kong' => "(GMT+08:00) Hong Kong",
                                'Asia/Kuala_Lumpur' => "(GMT+08:00) Kuala Lumpur",
                                'Australia/Perth' => "(GMT+08:00) Perth",
                                'Asia/Singapore' => "(GMT+08:00) Singapore",
                                'Asia/Taipei' => "(GMT+08:00) Taipei",
                                'Asia/Ulaanbaatar' => "(GMT+08:00) Ulaan Bataar",
                                'Asia/Urumqi' => "(GMT+08:00) Urumqi",
                                'Asia/Irkutsk' => "(GMT+09:00) Irkutsk",
                                'Asia/Seoul' => "(GMT+09:00) Seoul",
                                'Asia/Tokyo' => "(GMT+09:00) Tokyo",
                                'Australia/Adelaide' => "(GMT+09:30) Adelaide",
                                'Australia/Darwin' => "(GMT+09:30) Darwin",
                                'Asia/Yakutsk' => "(GMT+10:00) Yakutsk",
                                'Australia/Brisbane' => "(GMT+10:00) Brisbane",
                                'Australia/Canberra' => "(GMT+10:00) Canberra",
                                'Pacific/Guam' => "(GMT+10:00) Guam",
                                'Australia/Hobart' => "(GMT+10:00) Hobart",
                                'Australia/Melbourne' => "(GMT+10:00) Melbourne",
                                'Pacific/Port_Moresby' => "(GMT+10:00) Port Moresby",
                                'Australia/Sydney' => "(GMT+10:00) Sydney",
                                'Asia/Vladivostok' => "(GMT+11:00) Vladivostok",
                                'Asia/Magadan' => "(GMT+12:00) Magadan",
                                'Pacific/Auckland' => "(GMT+12:00) Auckland",
                                'Pacific/Fiji' => "(GMT+12:00) Fiji",
                            );
                            $data = setting::get_data('default_time_zone');
                            echo $comment = getcomment($data);
                            $defaultTimeZone = @$data['val'];
                            ?>
                            <table>
                                <tr> 
                                    <td colspan="2">
                                        <select name="main[default_time_zone]"  id="default_time_zone" class="input-xlarge">
                                            <?php
                                            foreach ($timezones as $k => $v) {
                                                ?>
                                                <option value="<?= $k ?>"><?= $v ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select> 
                                    </td>
                                </tr>
                            </table>
                            <?php if ($comment) echo '</a>'; ?> 
                        </dd>  
                        <!--dt><label>Thumbs Size</label></dt>
                        <dd>
                        <?php
                        $dataw = setting::get_data('thumbs_width');
                        $commentw = getcomment($dataw);
                        $datah = setting::get_data('thumbs_height');
                        $commenth = getcomment($datah);
                        ?>
                            <div>
                        <?php echo $commentw; ?>
                                <input type="text" name="main[thumbs_width]"  class="input-mini"  value="<?= @$dataw['val'] ?>"  />
                        <?php if ($commentw) echo '</a>'; ?> 

                                x
                        <?php echo $commenth; ?>
                                <input type="text" name="main[thumbs_height]"  class="input-mini"  value="<?= @$datah['val'] ?>"  />
                        <?php if ($commenth) echo '</a>'; ?> 
                                Pixel
                                <em></em>
                            </div>

                        </dd-->  
                        <dt><label><?= L::forms_smtp_email_from; ?></label></dt>
                        <dd>
                            <?php
                            $data = setting::get_data('smtp_email_from');
                            $comment = getcomment($data);
                            ?>
                            <div>
                                <?php echo $comment ?>
                                <input type="text" name="main[smtp_email_from]"   value="<?= @$data['val'] ?>"  />
                                <?php if ($comment) echo '</a>'; ?> 
                                <em></em>
                            </div>

                        </dd>  
                        <dt><label><?= L::forms_smtp_email_sender_name; ?></label></dt>
                        <dd>
                            <?php
                            $data = setting::get_data('smtp_email_from_name');
                            $comment = getcomment($data);
                            ?>
                            <div>
                                <?php echo $comment ?>
                                <input type="text" name="main[smtp_email_from_name]"   value="<?= @$data['val'] ?>"  />
                                <?php if ($comment) echo '</a>'; ?> 
                                <em></em>
                            </div>

                        </dd> 
                        <dt><label><?= L::forms_site_language; ?></label></dt>
                        <dd>
                            <?php
                            $data = setting::get_data('site_language');
                            echo $comment = getcomment($data);
                            $lanval = @$data['val'];
                            ?>
                            <select  name="main[site_language]" id="site_lan">
                                <option value="<?= agent::lang_to_code('english') ?>">English</option>
                                <option value="<?= agent::lang_to_code('persian') ?>">Persian</option>
                                <option value="<?= agent::lang_to_code('urdu') ?>">اردو</option>
                                <option value="<?= agent::lang_to_code('portuguese') ?>">Português</option>
                                <option value="<?= agent::lang_to_code('turkish') ?>">Türkçe</option>
                                <option value="<?= agent::lang_to_code('russian') ?>">Russian</option>
                                <option value="<?= agent::lang_to_code('romanian') ?>">Romanian</option>
                                <option value="<?= agent::lang_to_code('french') ?>">French</option>
                                <option value="<?= agent::lang_to_code('hindi') ?>">Hindi</option>
                                <option value="<?= agent::lang_to_code('french') ?>">French</option>
                                <option value="<?= agent::lang_to_code('spanish') ?>">español</option>
                                <option value="<?= agent::lang_to_code('german') ?>">Deutsch</option> 
                                <option value="<?= agent::lang_to_code('italian') ?>">Italian</option>
                                <option value="<?= agent::lang_to_code('chinese') ?>">Chinese</option> 
                                <option value="<?= agent::lang_to_code('arabic') ?>">Arabic</option>
                            </select>
                            <?php if ($comment) echo '</a>'; ?> 
                        </dd>
                    
                        <dt><label><?= L::forms_show_prepage; ?></label></dt>
                        <dd>
                            <?php
                            $data = setting::get_data('show_prepage');
                            echo $comment = getcomment($data);
                            $Show_PrePage = @$data['val'];
                            ?>
                            <select  name="main[show_prepage]" id="Show_PrePage">
                                <option value="1"><?= L::global_state_yes; ?></option>
                                <option value="0"><?= L::global_state_no; ?></option>
                            </select>
                            <?php if ($comment) echo '</a>'; ?> 
                        </dd>                       
                        <dt><label><?= L::forms_generate_geo_stats; ?></label></dt>
                        <dd>
                            <?php
                            $data = setting::get_data('geo_stats');
                            echo $comment = getcomment($data);
                            $geo_stats = @$data['val'];
                            ?>
                            <select  name="main[geo_stats]" id="geo_stats">
                                <option value="1"><?= L::global_enable; ?></option>
                                <option value="0"><?= L::global_disable; ?></option>
                            </select>
                            <?php if ($comment) echo '</a>'; ?> 
                        </dd>
                        <dt><label>Number of rows in data tables</label></dt>
                        <dd>
                            <?php
                            $data = setting::get_data('datatable_ipp');
                            echo $comment = getcomment($data);
                            $datatable_ipp = @$data['val'];
                            ?>
                            <select  name="main[datatable_ipp]" id="datatable_ipp">
                                <option value="10">10</option>
                                <option value="20">20</option>
                                <option value="30">30</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                                <option value="150">150</option>
                                <option value="200">200</option>
                            </select>
                            <?php if ($comment) echo '</a>'; ?>
                        </dd>
                        <dt><label><?= L::forms_close_for_maintenance; ?></label></dt>
                        <dd>
                            <?php
                            $data = setting::get_data('close_site');
                            echo $comment = getcomment($data);
                            $Close_Site = @$data['val'];
                            ?>
                            <select  name="main[close_site]" id="Close_Site">
                                <option value="1"><?= L::global_state_yes; ?></option>
                                <option value="0"><?= L::global_state_no; ?></option>
                            </select>
                            <?php if ($comment) echo '</a>'; ?> 
                        </dd> 
                        <div id="close_site_messages_area" style="display: none">
                            <dt><label>Maintenance message text</label></dt>
                            <dd>
                                <?php
                                $data = setting::get_data('close_site_messages');
                                echo $comment = getcomment($data);
                                ?>
                                <textarea class="input-xlarge" name="main[close_site_messages]"  style="height: 70px;"><?= @$data['val']; ?></textarea>
                                <?php if ($comment) echo '</a>'; ?> 
                            </dd> 
                        </div>
                        <dt></dt>
                        <dd>
                            <?php
                            $data = setting::get_data('getdimension_after_uploading');
                            $comment = getcomment($data);
                            $get_dimension = convert::to_bool($data['val']);
                            ?>
                            <label class="uni-checkbox">
                                <?= $comment ?>
                                <input type="checkbox" name="main[getdimension_after_uploading]" id="getdimension_after_uploading" value="1" <?= $get_dimension ? 'checked="true"' : '' ?> />
                                <?= L::forms_get_dimension; ?>
                                <?php if ($comment) echo '</a>'; ?> 
                            </label>
                        </dd> 
                        <dt><label>My server IPs</label></dt>
                        <dd>
                            <?php
                            $data = setting::get_data('myserver_ips');
                            $comment = getcomment($data);
                            ?>
                            <div>
                                <?php echo $comment ?>
                                <input type="text" name="main[myserver_ips]"   value="<?= @$data['val'] ?>"  />
                                <span class="help-inline"> to access to cronjobs e.g. 127.0.0.1 (separate with comma)</span>
                                <?php if ($comment) echo '</a>'; ?> 
                                <em></em>
                            </div>
                        </dd> 
                        <dt></dt>
                        <!--dd>
                        <?php
                        $data = setting::get_data('right_to_left');
                        $comment = getcomment($data);
                        ?>
                            <label class="uni-checkbox">
                        <?= $comment ?>
                                <input type="checkbox" name="main[right_to_left]" <?= @convert::to_bool($data['val']) ? 'checked="true"' : null ?> value="1"/> 
                                Right To Left
                        <?php if ($comment) echo '</a>'; ?> 
                            </label>
                        </dd-->
                        <div class="formSep"></div>
                        <dt></dt>
                        <dd> 
                            <div class="controls">
                                <a class="btn btn-abs" onclick="$('#myform').submit()"><?= L::forms_save_changes; ?></a>
                            </div>
                        </dd>
                    </dl>
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
    selectOptionByValue('site_theme', '<?= @$sitethemeval ?>');
    selectOptionByValue('site_lan', '<?= @$lanval ?>');
    selectOptionByValue('Show_PrePage', '<?= @$Show_PrePage ?>');
    selectOptionByValue('Close_Site', '<?= @$Close_Site ?>');
    selectOptionByValue('geo_stats', '<?= @$geo_stats ?>');
    selectOptionByValue('default_time_zone', '<?= @$defaultTimeZone ?>');
    selectOptionByValue('datatable_ipp', '<?= @$datatable_ipp ?>');
    var fValidation;
    var loading_config = {
        'indicatorZIndex': 990,
        'overlayZIndex': 990
    };
    // Validation Options
    fValidation = $("#myform").validate({
        rules: {
            'main[thumbs_width]': {
                number: true
            },
            'main[thumbs_height]': {
                number: true
            },
            'main[site_url]': {
                url: true
            },
            'main[smtp_email_from]': {
                email: true
            }
        },
        messages: {
            'main[thumbs_width]': {
                number: 'Please enter a valid number'
            },
            'main[thumbs_height]': {
                number: 'Please enter a valid number'
            },
            'main[site_url]': {
                url: "Please enter a valid url",
            },
            'main[smtp_email_from]': {
                email: "Please enter a valid email",
            }
        },
        debug: false,
        highlight: function (element) {
            if ($(element).closest('dd').find('em').length)
                $(element).closest('dd').find('em').closest('div,dd').addClass("f_error");
            else
                $(element).closest('div').addClass("f_error");
        },
        unhighlight: function (element) {
            if ($(element).closest('dd').find('em').length)
                $(element).closest('dd').find('em').closest('div,dd').removeClass("f_error");
            else
                $(element).closest('div').removeClass("f_error");
        },
        errorPlacement: function (error, element) {
            if ($(element).closest('dd').find('em').length)
                error.appendTo($(element).closest('dd').find('em'));
            else
                error.insertAfter(element);
        },
        submitHandler: function (form) {
            $('#myform').showLoading(loading_config);
            data = $.deparam($('#myform').serialize());
            //tinymce
            if (typeof (tinyMCE) != 'undefined') {
                $('textarea.tinymce').each(function () {
                    $tinyval = tinyMCE.get($(this).attr('id')).getContent();
                    eval("$.extend(data || {}, {" + $(this).attr('name') + ":$tinyval});");
                });
            }

            // encode and slashes
            /*
             serialize = function (obj, prefix) {
             var str = [];
             for (var p in obj) {
             var k = prefix ? prefix + "[" + p + "]" : p, v = obj[p];
             str.push(typeof v == "object" ?
             serialize(v, k) :
             encodeURIComponent(k) + "=" + encodeURIComponent(v));
             }
             return str.join("&");
             };
             data = serialize(data);
             */
            $.ajax({
                type: 'POST',
                data:{'encodedData':encodePostData(data)},
                url: "<?= url::itself()->url_nonqry(array('save' => 1)) ?>",
                success: function (result) {
                    $('#myform').hideLoading();
                    obj = JSON.parse(result);
                    if (obj.save_code === 1) {
                        $.sticky(obj.save_txt, {autoclose: 5000, position: "top-right", type: "st-success", speed: "fast"});
                        return true;
                    }
                    else {
                        $.sticky("<?= addslashes(L::global_error); ?>! " + obj.save_txt, {autoclose: 5000, position: "top-right", type: "st-error", speed: "fast"});
                        return false;
                    }
                }
            });
        }
    });
    $(document).ready(function () {
        $('#Close_Site').change(function () {
            if ($(this).val() == 1)
                $('#close_site_messages_area').slideDown();
            else
                $('#close_site_messages_area').slideUp();
        });
        $('#Close_Site').trigger('change');

        $('#preview a').click(function () {
            $.colorbox({
                iframe: true,
                innerWidth: $(this).data('width'),
                innerHeight: $(this).data('height'),
                href: '<?= url::router('homepage') ?>?abtheme=' + $('#site_theme').val() + $(this).data('extparams'),
                maxWidth: '90%',
                maxHeight: '90%',
                opacity: '0.2',
                loop: false,
                fixed: true
            });
            return false;
        });

        $('input:checkbox').click(function () {
            if ($(this).is(':checked'))
                $(this).val(1);
            else
                $(this).val(0);
        });
        $('#site_theme').change(function () {

            $('#thumb').fadeOut('slow', function () {
                name = $('#site_theme').val().replace(/[^\w]|\-/, '_');
                var thm = $('#theme_' + name).html();
                $('#thumb').html(thm);
                $('#thumb').fadeIn('slow');
            });
        });
        $('#site_theme').trigger('change');
    });



</script>
<?php
get_footer();
?>