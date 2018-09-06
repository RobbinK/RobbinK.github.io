<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: seo.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:56
##########################################################
 */

### call header
abs_admin_inc(l_basic);
abs_admin_inc(l_sticky);
abs_admin_inc(l_validate);
abs_admin_inc(l_unserializeForm);
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
                        <?= L::sidebar_seo_set; ?>
                    </li>
                </ul>
            </div>
        </nav>
        <style>
            .code1{
                margin: 3px 0 2px 0
            }
            .form_validation_reg input[type='text'],textarea{direction: ltr;}
        </style>
        <!-- /Navigation Menu -->
        <div class="row-fluid">
            <div>
                <h3 class="heading"><?= L::forms_seo_setting; ?></h3> 

                <form class="form_validation_reg" id="myform" method="post" action="<?= url::itself()->fulluri() ?>"  novalidate="novalidate" enctype="multipart/form-data" onsubmit="return false">

                    <dl class="dl-horizontal">
                        <dt><label>Meta description source </label></dt>
                        <dd>
                            <?php
                            $data = setting::get_data('meta_description_source');
                            echo $comment = getcomment($data);
                            $meta_description_source = @$data['val'];
                            ?>
                            <select  name="seo[meta_description_source]" id="meta_description_source" class="input-xlarge">
                                <option value="fdescription">Use Game Description as Meta Description</option>
                                <option value="new">Use New Description</option>
                            </select>
                            <?php if ($comment) echo '</a>'; ?> 
                        </dd> 
                        <dt><label>Meta description maximum length </label></dt>
                        <dd>
                            <?php
                            $data = setting::get_data('meta_description_length');
                            $comment = getcomment($data);
                            ?> 
                            <div>
                                <?= $comment ?>
                                <input type="text"   name="seo[meta_description_length]" id="meta_description_length"  class="input-mini" value="<?= is_numeric($data['val']) ? $data['val'] : 175 ?>" /> 
                                <span class="help-inline">Characters</span>  
                                <?php if ($comment) echo '</a>'; ?> 
                                <em></em>
                            </div>
                        </dd>  
                        <dt><label>Canonical Link</label></dt>
                        <dd>
                            <?php
                            $data = setting::get_data('canonical_link');
                            echo $comment = getcomment($data);
                            $canonical = @$data['val'];
                            ?>
                            <select  name="seo[canonical_link]" id="canonical_link">
                                <option value="0"><?= L::global_disable; ?></option>
                                <option value="pre">Point to PrePage</option>
                                <option value="play">Point to PlayPage</option>
                            </select>
                            <?php if ($comment) echo '</a>'; ?> 
                        </dd> 
                        <dt><label><?= L::forms_files_names; ?></label></dt>
                        <dd>
                            <?php
                            $data = setting::get_data('file_names_mode');
                            echo $comment = getcomment($data);
                            $file_names_mode = @$data['val'];
                            ?>
                            <select  name="seo[file_names_mode]" id="file_names_mode" class="input-xlarge">
                                <option value="seo"><?= L::forms_seo_filename; ?></option>
                                <option value="random"><?= L::forms_random_filename; ?></option>
                            </select>
                            <span class="help-inline"><?= L::forms_filename_hint ?></span>
                            <?php if ($comment) echo '</a>'; ?> 
                        </dd>
                    </dl>

                    <div class="formSep"></div>
                    <div class="well well-small">
                        <h5><?= L::forms_seo_users_guide; ?></h5>
                        <br/>
                        <div class="row-fluid">
                            <div class="span5">  
                                <ul class="list_b">
                                    <li><?= L::forms_to_get_site_name_use; ?> <code>{site_name}</code></li> 
                                    <li><?= L::forms_to_get_game_name_use; ?> <code>{game_title}</code></li> 
                                    <li><?= L::forms_to_get_game_description_use; ?> <code>{game_desc}</code></li> 
                                    <li><?= L::forms_to_get_game_keywords_use; ?> <code>{game_keywords}</code></li> 
                                </ul> 
                            </div>

                            <div class="span5">  
                                <ul class="list_b">
                                    <li><?= L::forms_to_get_category_title_use; ?> <code>{category_title}</code></li> 
                                    <li><?= L::forms_to_get_category_description_use; ?> <code>{category_desc}</code></li>
                                    <li><?= L::forms_to_get_page_number; ?> <code>{page_number}</code></li> 
                                    <li><?= L::forms_to_get_tag_name; ?> <code>{tag_name}</code></li> 
                                </ul> 
                            </div>
                        </div> 
                    </div> 
 
                    <div id="accordion1" class="accordion">
                        <div class="accordion-group">
                            <div class="accordion-heading">
                                <a href="#collapseOne1" data-parent="#accordion1" data-toggle="collapse" class="accordion-toggle">
                                    <?= L::forms_page_title; ?>
                                </a>
                            </div>
                            <div class="accordion-body collapse" id="collapseOne1">
                                <div class="accordion-inner">
                                    <dl class="dl-horizontal">
                                        <dt><label><?= L::global_homepage; ?></label></dt>
                                        <dd>
                                            <?php
                                            $data = setting::get_data('seo_homepage_title');
                                            echo $comment = getcomment($data);
                                            ?>
                                            <input type="text" name="seo[seo_homepage_title]"  value="<?= @htmlspecialchars($data['val']) ?>" class="input-xxlarge" />
                                            <?php if ($comment) echo '</a>'; ?> 
                                        </dd>  
                                        <dt><label><?= L::forms_new_games; ?></label></dt>
                                        <dd>
                                            <?php
                                            $data = setting::get_data('seo_new_games_page_title');
                                            echo $comment = getcomment($data);
                                            ?>
                                            <input type="text" name="seo[seo_new_games_page_title]"  value="<?= @htmlspecialchars($data['val']) ?>" class="input-xxlarge"  />
                                            <?php if ($comment) echo '</a>'; ?> 
                                        </dd>
                                        <dt><label><?= L::forms_category_page; ?></label></dt>
                                        <dd> 
                                            <?php
                                            $data = setting::get_data('seo_category_page_title');
                                            echo $comment = getcomment($data);
                                            ?>
                                            <input type="text" name="seo[seo_category_page_title]"  value="<?= @htmlspecialchars($data['val']) ?>" class="input-xxlarge"  />
                                            <?php if ($comment) echo '</a>'; ?> 
                                        </dd>
                                        <dt><label><?= L::forms_tag_page; ?></label></dt>
                                        <dd> 
                                            <?php
                                            $data = setting::get_data('seo_tag_page_title');
                                            echo $comment = getcomment($data);
                                            ?>
                                            <input type="text" name="seo[seo_tag_page_title]"  value="<?= @htmlspecialchars($data['val']) ?>" class="input-xxlarge"  />
                                            <?php if ($comment) echo '</a>'; ?> 
                                        </dd>
                                        <dt><label><?= L::forms_popular_games; ?></label></dt>
                                        <dd>
                                            <?php
                                            $data = setting::get_data('seo_popular_games_page_title');
                                            echo $comment = getcomment($data);
                                            ?>
                                            <input type="text" name="seo[seo_popular_games_page_title]"  value="<?= @htmlspecialchars($data['val']) ?>" class="input-xxlarge"  />
                                            <?php if ($comment) echo '</a>'; ?> 
                                        </dd>
                                        <dt><label><?= L::forms_top_rated_games; ?></label></dt>
                                        <dd>
                                            <?php
                                            $data = setting::get_data('seo_top_rated_games_page_title');
                                            echo $comment = getcomment($data);
                                            ?>
                                            <input type="text" name="seo[seo_top_rated_games_page_title]"  value="<?= @htmlspecialchars($data['val']) ?>" class="input-xxlarge"  />
                                            <?php if ($comment) echo '</a>'; ?> 
                                        </dd>
                                        <dt><label><?= L::forms_pre_play_page; ?></label></dt>
                                        <dd>
                                            <?php
                                            $data = setting::get_data('seo_pre-play_page_title');
                                            echo $comment = getcomment($data);
                                            ?>
                                            <input type="text" name="seo[seo_Pre-Play_page_title]"  value="<?= @htmlspecialchars($data['val']) ?>"  class="input-xxlarge" />
                                            <?php if ($comment) echo '</a>'; ?> 
                                        </dd>
                                        <dt><label><?= L::forms_play_page; ?></label></dt>
                                        <dd> 
                                            <?php
                                            $data = setting::get_data('seo_play_page_title');
                                            echo $comment = getcomment($data);
                                            ?>
                                            <input type="text" name="seo[seo_play_page_title]"  value="<?= @htmlspecialchars($data['val']) ?>" class="input-xxlarge"  />
                                            <?php if ($comment) echo '</a>'; ?> 
                                        </dd>
                                        <dt><label><?= L::forms_search_results; ?></label></dt>
                                        <dd> 
                                            <?php
                                            $data = setting::get_data('seo_search_page_title');
                                            echo $comment = getcomment($data);
                                            ?>
                                            <input type="text" name="seo[seo_search_page_title]"  value="<?= @htmlspecialchars($data['val']) ?>" class="input-xxlarge"  />
                                            <?php if ($comment) echo '</a>'; ?> 
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-group">
                            <div class="accordion-heading">
                                <a href="#collapseTwo1" data-parent="#accordion1" data-toggle="collapse" class="accordion-toggle">
                                    <?= L::forms_meta_keywords; ?>
                                </a>
                            </div>
                            <div class="accordion-body collapse" id="collapseTwo1">
                                <div class="accordion-inner">
                                    <dl class="dl-horizontal">
                                        <dt><label><?= L::global_homepage; ?></label></dt>
                                        <dd>
                                            <?php
                                            $data = setting::get_data('seo_homepage_keywords');
                                            echo $comment = getcomment($data);
                                            ?>
                                            <input type="text" name="seo[seo_homepage_keywords]"  value="<?= @htmlspecialchars($data['val']) ?>"  class="input-xxlarge" />
                                            <?php if ($comment) echo '</a>'; ?> 
                                        </dd>   
                                        <dt><label><?= L::forms_new_games; ?></label></dt>
                                        <dd>
                                            <?php
                                            $data = setting::get_data('seo_new_games_page_keywords');
                                            echo $comment = getcomment($data);
                                            ?>
                                            <input type="text" name="seo[seo_new_games_page_keywords]"  value="<?= @htmlspecialchars($data['val']) ?>" class="input-xxlarge"  />
                                            <?php if ($comment) echo '</a>'; ?> 
                                        </dd>
                                        <dt><label><?= L::forms_category_page; ?></label></dt>
                                        <dd>
                                            <?php
                                            $data = setting::get_data('seo_category_page_keywords');
                                            echo $comment = getcomment($data);
                                            ?>
                                            <input type="text" name="seo[seo_category_page_keywords]"  value="<?= @htmlspecialchars($data['val']) ?>" class="input-xxlarge"  />
                                            <?php if ($comment) echo '</a>'; ?> 
                                        </dd>
                                        <dt><label><?= L::forms_tag_page; ?></label></dt>
                                        <dd>
                                            <?php
                                            $data = setting::get_data('seo_tag_page_keywords');
                                            echo $comment = getcomment($data);
                                            ?>
                                            <input type="text" name="seo[seo_tag_page_keywords]"  value="<?= @htmlspecialchars($data['val']) ?>" class="input-xxlarge"  />
                                            <?php if ($comment) echo '</a>'; ?> 
                                        </dd>
                                        <dt><label><?= L::forms_popular_games; ?></label></dt>
                                        <dd>
                                            <?php
                                            $data = setting::get_data('seo_popular_games_page_keywords');
                                            echo $comment = getcomment($data);
                                            ?>
                                            <input type="text" name="seo[seo_popular_games_page_keywords]"  value="<?= @htmlspecialchars($data['val']) ?>"  class="input-xxlarge" />
                                            <?php if ($comment) echo '</a>'; ?> 
                                        </dd>
                                        <dt><label><?= L::forms_top_rated_games; ?></label></dt>
                                        <dd>
                                            <?php
                                            $data = setting::get_data('seo_top_rated_games_page_keywords');
                                            echo $comment = getcomment($data);
                                            ?>
                                            <input type="text" name="seo[seo_top_rated_games_page_keywords]"  value="<?= @htmlspecialchars($data['val']) ?>" class="input-xxlarge" />
                                            <?php if ($comment) echo '</a>'; ?> 
                                        </dd>
                                        <dt><label><?= L::forms_pre_play_page; ?></label></dt>
                                        <dd>
                                            <?php
                                            $data = setting::get_data('seo_pre-play_page_keywords');
                                            echo $comment = getcomment($data);
                                            ?>
                                            <input type="text" name="seo[seo_Pre-Play_page_keywords]"  value="<?= @htmlspecialchars($data['val']) ?>" class="input-xxlarge"  />
                                            <?php if ($comment) echo '</a>'; ?> 
                                        </dd>
                                        <dt><label><?= L::forms_play_page; ?></label></dt>
                                        <dd>
                                            <?php
                                            $data = setting::get_data('seo_play_page_keywords');
                                            echo $comment = getcomment($data);
                                            ?>
                                            <input type="text" name="seo[seo_play_page_keywords]"  value="<?= @htmlspecialchars($data['val']) ?>"  class="input-xxlarge" />
                                            <?php if ($comment) echo '</a>'; ?> 
                                        </dd>
                                        <dt><label><?= L::forms_search_results; ?></label></dt>
                                        <dd>
                                            <?php
                                            $data = setting::get_data('seo_search_page_keywords');
                                            echo $comment = getcomment($data);
                                            ?>
                                            <input type="text" name="seo[seo_search_page_keywords]"  value="<?= @htmlspecialchars($data['val']) ?>"  class="input-xxlarge" />
                                            <?php if ($comment) echo '</a>'; ?> 
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-group">
                            <div class="accordion-heading">
                                <a href="#collapseThree1" data-parent="#accordion1" data-toggle="collapse" class="accordion-toggle">
                                    <?= L::forms_meta_description; ?>
                                </a>
                            </div>
                            <div class="accordion-body collapse" id="collapseThree1">
                                <div class="accordion-inner">
                                    <dl class="dl-horizontal">
                                        <dt><label><?= L::global_homepage; ?></label></dt>
                                        <dd>
                                            <?php
                                            $data = setting::get_data('seo_homepage_description');
                                            echo $comment = getcomment($data);
                                            ?>
                                            <textarea class="auto_expand input-xxlarge"  rows="2" cols="1" name="seo[seo_homepage_description]"><?= @htmlspecialchars($data['val']) ?></textarea>
                                            <?php if ($comment) echo '</a>'; ?> 
                                        </dd>   
                                        <dt><label><?= L::forms_new_games; ?></label></dt>
                                        <dd>
                                            <?php
                                            $data = setting::get_data('seo_new_games_page_description');
                                            echo $comment = getcomment($data);
                                            ?>
                                            <textarea class="auto_expand input-xxlarge" rows="2" cols="1" name="seo[seo_new_games_page_description]"><?= @htmlspecialchars($data['val']) ?></textarea>
                                            <?php if ($comment) echo '</a>'; ?> 
                                        </dd>
                                        <dt><label><?= L::forms_category_page; ?></label></dt>
                                        <dd>
                                            <?php
                                            $data = setting::get_data('seo_category_page_description');
                                            echo $comment = getcomment($data);
                                            ?>
                                            <textarea class="auto_expand input-xxlarge" rows="2" cols="1" name="seo[seo_category_page_description]"><?= @htmlspecialchars($data['val']) ?></textarea>
                                            <?php if ($comment) echo '</a>'; ?> 
                                        </dd>
                                        <dt><label><?= L::forms_tag_page; ?></label></dt>
                                        <dd>
                                            <?php
                                            $data = setting::get_data('seo_tag_page_description');
                                            echo $comment = getcomment($data);
                                            ?>
                                            <textarea class="auto_expand input-xxlarge" rows="2" cols="1" name="seo[seo_tag_page_description]"><?= @htmlspecialchars($data['val']) ?></textarea>
                                            <?php if ($comment) echo '</a>'; ?> 
                                        </dd>
                                        <dt><label><?= L::forms_popular_games; ?></label></dt>
                                        <dd>
                                            <?php
                                            $data = setting::get_data('seo_popular_games_page_description');
                                            echo $comment = getcomment($data);
                                            ?>
                                            <textarea class="auto_expand input-xxlarge" rows="2" cols="1" name="seo[seo_popular_games_page_description]"><?= @htmlspecialchars($data['val']) ?></textarea>
                                            <?php if ($comment) echo '</a>'; ?> 
                                        </dd>
                                        <dt><label><?= L::forms_top_rated_games; ?></label></dt>
                                        <dd>
                                            <?php
                                            $data = setting::get_data('seo_top_rated_games_page_description');
                                            echo $comment = getcomment($data);
                                            ?>
                                            <textarea class="auto_expand input-xxlarge" rows="2" cols="1" name="seo[seo_top_rated_games_page_description]"><?= @htmlspecialchars($data['val']) ?></textarea>
                                            <?php if ($comment) echo '</a>'; ?> 
                                        </dd>
                                        <dt><label><?= L::forms_pre_play_page; ?></label></dt>
                                        <dd>
                                            <?php
                                            $data = setting::get_data('seo_pre-play_page_description');
                                            echo $comment = getcomment($data);
                                            ?>
                                            <textarea class="auto_expand input-xxlarge" rows="2" cols="1" name="seo[seo_Pre-Play_page_description]"><?= @htmlspecialchars($data['val']) ?></textarea>
                                            <?php if ($comment) echo '</a>'; ?> 
                                        </dd>
                                        <dt><label><?= L::forms_play_page; ?></label></dt>
                                        <dd>
                                            <?php
                                            $data = setting::get_data('seo_play_page_description');
                                            echo $comment = getcomment($data);
                                            ?>
                                            <textarea class="auto_expand input-xxlarge" rows="2" cols="1" name="seo[seo_play_page_description]"><?= @htmlspecialchars($data['val']) ?></textarea>
                                            <?php if ($comment) echo '</a>'; ?> 
                                        </dd>
                                        <dt><label><?= L::forms_search_results; ?></label></dt>
                                        <dd>
                                            <?php
                                            $data = setting::get_data('seo_search_page_description');
                                            echo $comment = getcomment($data);
                                            ?>
                                            <textarea class="auto_expand input-xxlarge" rows="2" cols="1" name="seo[seo_search_page_description]"><?= @htmlspecialchars($data['val']) ?></textarea>
                                            <?php if ($comment) echo '</a>'; ?> 
                                        </dd>

                                    </dl>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-group">
                            <div class="accordion-heading">
                                <a href="#collapseFour1" data-parent="#accordion1" data-toggle="collapse" class="accordion-toggle">
                                    <?= L::forms_heading; ?>
                                </a>
                            </div>
                            <div class="accordion-body collapse" id="collapseFour1">
                                <div class="accordion-inner">
                                    <dl class="dl-horizontal">
                                        <dt><label><?= L::forms_new_games; ?></label></dt>
                                        <dd>
                                            <?php
                                            $data = setting::get_data('seo_new_games_page_heading');
                                            echo $comment = getcomment($data);
                                            ?>
                                            <input type="text" name="seo[seo_new_games_page_heading]"  value="<?= @htmlspecialchars($data['val']) ?>" class="input-xxlarge"  />
                                            <?php if ($comment) echo '</a>'; ?> 
                                        </dd>
                                        <dt><label><?= L::forms_category_new_games; ?></label></dt>
                                        <dd>
                                            <?php
                                            $data = setting::get_data('seo_category_new_games_heading');
                                            echo $comment = getcomment($data);
                                            ?>
                                            <input type="text" name="seo[seo_category_new_games_heading]"  value="<?= @htmlspecialchars($data['val']) ?>" class="input-xxlarge"  />
                                            <?php if ($comment) echo '</a>'; ?> 
                                        </dd>
                                        <dt><label><?= L::forms_popular_games; ?></label></dt>
                                        <dd>
                                            <?php
                                            $data = setting::get_data('seo_popular_games_page_heading');
                                            echo $comment = getcomment($data);
                                            ?>
                                            <input type="text" name="seo[seo_popular_games_page_heading]"  value="<?= @htmlspecialchars($data['val']) ?>" class="input-xxlarge"  />
                                            <?php if ($comment) echo '</a>'; ?> 
                                        </dd>
                                        <dt><label><?= L::forms_category_popular_games; ?></label></dt>
                                        <dd>
                                            <?php
                                            $data = setting::get_data('seo_category_popular_games_heading');
                                            echo $comment = getcomment($data);
                                            ?>
                                            <input type="text" name="seo[seo_category_popular_games_heading]"  value="<?= @htmlspecialchars($data['val']) ?>" class="input-xxlarge"  />
                                            <?php if ($comment) echo '</a>'; ?> 
                                        </dd>
                                        <dt><label><?= L::forms_top_rated_games; ?></label></dt>
                                        <dd>
                                            <?php
                                            $data = setting::get_data('seo_top_rated_games_page_heading');
                                            echo $comment = getcomment($data);
                                            ?>
                                            <input type="text" name="seo[seo_top_rated_games_page_heading]"  value="<?= @htmlspecialchars($data['val']) ?>" class="input-xxlarge" />
                                            <?php if ($comment) echo '</a>'; ?> 
                                        </dd>
                                        <dt><label><?= L::forms_category_top_rated_games; ?></label></dt>
                                        <dd>
                                            <?php
                                            $data = setting::get_data('seo_category_top_rated_games_heading');
                                            echo $comment = getcomment($data);
                                            ?>
                                            <input type="text" name="seo[seo_category_top_rated_games_heading]"  value="<?= @htmlspecialchars($data['val']) ?>" class="input-xxlarge"  />
                                            <?php if ($comment) echo '</a>'; ?> 
                                        </dd>
                                        <dt><label><?= L::forms_category_more_games; ?></label></dt>
                                        <dd>
                                            <?php
                                            $data = setting::get_data('seo_category_more_games');
                                            echo $comment = getcomment($data);
                                            ?>
                                            <input type="text" name="seo[seo_category_more_games]"  value="<?= @htmlspecialchars($data['val']) ?>" class="input-xxlarge"  />
                                            <?php if ($comment) echo '</a>'; ?> 
                                        </dd>
                                        <dt><label><?= L::forms_pre_play_page; ?></label></dt>
                                        <dd>
                                            <?php
                                            $data = setting::get_data('seo_pre-play_page_heading');
                                            echo $comment = getcomment($data);
                                            ?>
                                            <input type="text" name="seo[seo_Pre-play_page_heading]"  value="<?= @htmlspecialchars($data['val']) ?>" class="input-xxlarge" />
                                            <?php if ($comment) echo '</a>'; ?> 
                                        </dd>
                                        <dt><label><?= L::forms_play_page; ?></label></dt>
                                        <dd>
                                            <?php
                                            $data = setting::get_data('seo_play_page_heading');
                                            echo $comment = getcomment($data);
                                            ?>
                                            <input type="text" name="seo[seo_play_page_heading]"  value="<?= @htmlspecialchars($data['val']) ?>" class="input-xxlarge"  />
                                            <?php if ($comment) echo '</a>'; ?> 
                                        </dd>
                                        <dt><label><?= L::forms_search_results; ?></label></dt>
                                        <dd>
                                            <?php
                                            $data = setting::get_data('seo_search_page_heading');
                                            echo $comment = getcomment($data);
                                            ?>
                                            <input type="text" name="seo[seo_search_page_heading]"  value="<?= @htmlspecialchars($data['val']) ?>" class="input-xxlarge"  />
                                            <?php if ($comment) echo '</a>'; ?> 
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="controls">
                        <a class="btn btn-abs" onclick="$('#myform').submit()"><?= L::forms_save_changes; ?></a>
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
    var fValidation;
    var loading_config = {
        'indicatorZIndex': 990,
        'overlayZIndex': 990
    };

    selectOptionByValue('meta_description_source', '<?= @$meta_description_source ?>');
    selectOptionByValue('file_names_mode', '<?= @$file_names_mode ?>');
    selectOptionByValue('canonical_link', '<?= @$canonical ?>');


    $(function () {
        $("#meta_description_length").spinner({
            min: 10
        });
    });

    // Validation Options
    fValidation = $("#myform").validate({
        debug: false,
        onfocusout: false,
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
</script>
<?php
get_footer();
?>