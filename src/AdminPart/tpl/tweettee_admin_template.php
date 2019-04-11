<div class="tweettee-admin-block">

    <h1>Tweettee</h1>

    <?php if ($this->canShowStartForm()) : ?>

        <div class="instructions">
            <ol>
                <li><?php _e('Create new Application on ', 'tweettee'); ?><a href="https://developer.twitter.com/en/apps" target="_blank">https://developer.twitter.com/en/apps</a></li>
                <li><?php _e('Fill in the <b>Callback URL</b> field with ', 'tweettee'); echo '<b>', admin_url('options-general.php'), '</b>';?></li>
                <li><?php _e('Copy <b>Consumer Key</b> and <b>Consuner Secret</b>, and paste in form bellow.', 'tweettee'); ?></li>
                <li><?php _e('Send form.', 'tweettee'); ?></li>
                <li><?php _e('Accept authorisation.', 'tweettee'); ?></li>
            </ol>
        </div>
        <div class="first-form"> 
            <form method="POST" action="<?php admin_url('options-general.php?page=tweettee'); ?>">
                <?php wp_nonce_field('auth_form', 'oauth_nonce'); ?>
                <table class="oauth_data">
                    <tr>
                        <td><label for="ck">Consumer Key</label></td>
                        <td><input type="text" name="consumer_key" id="ck" value="<?= $options['consumer_key'] ?>" placeholder="Empty" required></td>
                    </tr>
                    <tr>
                        <td><label for="cs">Consumer Secret</label></td>
                        <td><input type="text" name="consumer_secret" id="cs" value="<?= $options['consumer_secret'] ?>" placeholder="Empty" required></td>
                    </tr>
                    <tr>
                        <td colspan="2"><button type="submit" name="consumer_submit" class="button-primary"><?php _e('Send', 'tweettee'); ?></button></td>
                    </tr>
                </table>
            </form>
        </div>    
    <?php else : ?>
        <?php $account_info = $options['account_info']; ?>

        <div class="account-wrapper">
            <?php
            $account_info->profile_use_background_image ? $background = "url('{$account_info->profile_background_image_url}')" : $background = "#C0DEED";
            $account_info->profile_background_tile ? $background_repeat = 'repeat' : $background_repeat = 'no-repeat';
            $profile_image = str_replace('_normal', '_200x200', $account_info->profile_image_url);
            ?>
            <div class="account-body" style="
                 background-image: <?= $background ?>;
                 background-color: #<?= $account_info->profile_background_color ?>;
                 background-repeat: <?= $background_repeat ?>
                 ">
                <img src="<?= $profile_image ?>" class="account-image">
                <div class="account-info">
                    <h4><a href="https://twitter.com/<?= $account_info->screen_name ?>" target="_blank"><?= $account_info->name ?></a></h4>
                    <h4><?= '@' . $account_info->screen_name ?></h4>
                    <p class="account-description">
                        <?= $account_info->description ?>
                    </p>
                </div>
            </div>
        </div>

        <h2><?php _e('Settings', 'tweettee'); ?></h2>

        <?php
        $widget_content_1 = $widget_content_2 = $widget_content_3 = $widget_content_4 = $widget_content_5 = '';
        $search_content_1 = $search_content_2 = $search_content_3 = $search_content_4 = '';
        $w_tweettee_popular = $w_tweettee_recent = $w_tweettee_mixed = '';

        $m_content_1 = $m_content_2 = $m_content_3 = $m_content_4 = $m_content_5 = '';
        $m_search_content_1 = $m_search_content_2 = $m_search_content_3 = $m_search_content_4 = '';
        $m_tweettee_popular = $m_tweettee_recent = $m_tweettee_mixed = '';

        $w_c = 'widget_content_' . $options['w_content_type'];
        $s_c = 'search_content_' . $options['w_search_type'];
        $m_c = 'm_content_' . $options['m_content_type'];
        $m_s = 'm_search_content_' . $options['m_search_type'];
        $w_type = 'w_tweettee_' . $options['w_result_type'];
        $m_type = 'm_tweettee_' . $options['m_result_type'];

        $$w_c = $$s_c = $$m_c = $$m_s = 'checked';
        $$w_type = $$m_type = 'selected';
        ?>

        <form method="post" action="<?php admin_url('options-general.php?page=tweettee'); ?>" name="">

            <fieldset>
                <legend><?php _e('Home page block', 'tweettee'); ?></legend>

                <input class="checkbox" type="checkbox" id="show-main-page-settings" name="show_main_page_settings" value="checked" <?= $options['show_main_page_settings'] ?>>
                <label for="show-main-page-settings"><?php _e('Show tweettee unit on the home page. (Experimental. 
Suitable not for every template)', 'tweettee'); ?></label>

                <div id="settings-main-page-block">
                    <div>

                        <input type="text" name="m_position" id="m-after-post" value="<?= $options['m_position'] ?>" size="2" maxlength="2">
                        <label for="m-after-post"><?php _e('Tweettee block position number in post list on home page', 'tweettee'); ?></label>
                        <br>

                        <input type="radio" name="m_content_type" id="m-tweettee-my-twits" value="1" <?= $m_content_1 ?>>
                        <label for="m-tweettee-my-twits"><?php _e('User timeline', 'tweettee'); ?></label>
                        <br>

                        <input type="radio" name="m_content_type" id="m-tweettee-my-timeline" value="2" <?= $m_content_2 ?>>
                        <label for="m-tweettee-my-timeline"><?php _e('Home timeline', 'tweettee'); ?></label>
                        <br>

                        <input type="radio" name="m_content_type" id="m-tweettee-about-my-twitter" value="3" <?= $m_content_3 ?>>
                        <label for="m-tweettee-about-my-twitter"><?php _e('Mentions', 'tweettee'); ?></label>
                        <br>

                        <input type="radio" name="m_content_type" id="m-tweettee-another-timeline" value="4" <?= $m_content_4 ?>>
                        <label for="m-tweettee-another-timeline"><?php _e('Home timeline another account', 'tweettee'); ?></label>
                        <input type="text" id="m-tweettee-another-timeline-name" name="m_another_timeline" size="20" value="<?= $options['m_another_timeline'] ?>">
                        <span id="m-tweettee-another-timeline-error"></span>
                        <br>

                        <input type="radio" name="m_content_type" id="m-tweettee-search-result" value="5" <?= $m_content_5 ?>>
                        <label for="m-tweettee-search-result"><?php _e('Search by ', 'tweettee'); ?></label>
                        <div id="m-search-result-for">
                            <input type="radio" name="m_search_type" id="m-tweettee-search-free-word" value="4" <?= $m_search_content_4 ?>>
                            <label for="m-tweettee-search-free-word"><?php _e('any word', 'tweettee'); ?></label>
                            <input type="text" id="m-tweettee-free-word-value" name="m_search_word" size="20" value="<?= $options['m_search_word'] ?>">
                            <span id="m-tweettee-free-word-error"></span>

                            <div class="result-type">
                                <label for="m-result-type"><?php _e('Search result', 'tweettee'); ?></label><br>

                                <select name="m_result_type" id="m-result-type">
                                    <option value="popular" <?= $m_tweettee_popular ?>><?php _e('Popular', 'tweettee'); ?></option>
                                    <option value="recent" <?= $m_tweettee_recent ?>><?php _e('Recent', 'tweettee'); ?></option>
                                    <option value="mixed" <?= $m_tweettee_mixed ?>><?php _e('Mixed', 'tweettee'); ?></option>
                                </select>    

                            </div>

                            <div class="tweettee-language">
                                <label for="m-search-language"><?php _e('Language', 'tweettee'); ?></label><br>

                                <select name="m_language" id="m-search-language">
                                    <?php
                                    $selected = '';
                                    foreach ($this->language as $key => $val) {
                                        $options['m_language'] === $key ? $selected = 'selected' : $selected = '';
                                        printf("<option value='%s' " . $selected . ">%s</option>\r\n", $key, $val);
                                    }
                                    ?>
                                </select>    

                            </div>

                        </div>
                    </div>
                    <hr>

                    <label for="m-tweettee-twit-count"><?php _e('Number of tweets', 'tweettee'); ?></label>
                    <input type="text" id="m-tweettee-twit-count" name="m_count" size="3" value="<?= $options['m_count'] ?>">
                    <hr>

                    <input type="checkbox" id="m-tweettee-only-text" name="m_only_text" value="checked" <?= $options['m_only_text'] ?>>
                    <label for="m-tweettee-only-text"><?php _e('Show only text', 'tweettee'); ?></label>
                    <hr>

                    <input type="checkbox" id="m-rel-nofollow" name="m_rel_nofollow" value="checked" <?= $options['m_rel_nofollow'] ?>>
                    <label for="m-rel-nofollow"><?php _e('All tweettee links with "<b>rel=nofollow</b>"', 'tweettee'); ?></label>
                    <hr>

                    <input type="checkbox" id="m-noindex" name="m_noindex" value="checked" <?= $options['m_noindex'] ?>>
                    <label for="m-noindex"><?php _e('Wrap tweettee unit in "<b>&lt;!--noindex--&gt;</b>" (For SE Yandex)', 'tweettee'); ?></label>
                </div>
            </fieldset>
            <!----------------------------------------------------------delimiter------------------------------------------------------------>            
            <fieldset>
                <legend><?php _e('Widget', 'tweettee'); ?></legend>

                <div>
                    <input type="radio" name="w_content_type" id="tweettee-my-twits" value="1" <?= $widget_content_1 ?>>
                    <label for="tweettee-my-twits"><?php _e('User timeline', 'tweettee'); ?></label>
                    <br>

                    <input type="radio" name="w_content_type" id="tweettee-my-timeline" value="2" <?= $widget_content_2 ?>>
                    <label for="tweettee-my-timeline"><?php _e('Home timeline', 'tweettee'); ?></label>
                    <br>

                    <input type="radio" name="w_content_type" id="tweettee-about-my-twitter" value="3" <?= $widget_content_3 ?>>
                    <label for="tweettee-about-my-twitter"><?php _e('Mentions', 'tweettee'); ?></label>
                    <br>

                    <input type="radio" name="w_content_type" id="tweettee-another-timeline" value="4" <?= $widget_content_4 ?>>
                    <label for="tweettee-another-timeline"><?php _e('Home timeline another account', 'tweettee'); ?></label>
                    <input type="text" id="tweettee-another-timeline-name" name="w_another_timeline" size="20" value="<?= $options['w_another_timeline'] ?>">
                    <span id="tweettee-another-timeline-error"></span>
                    <br>

                    <input type="radio" name="w_content_type" id="tweettee-search-result" value="5" <?= $widget_content_5 ?>>
                    <label for="tweettee-search-result"><?php _e('Search by ', 'tweettee'); ?></label>
                    <div id="search-result-for">
                        <input type="radio" name="w_search_type" id="tweettee-search-post-bookmark" value="1" <?= $search_content_1 ?>>
                        <label for="tweettee-search-post-bookmark"><?php _e('post tags', 'tweettee'); ?></label>
                        <br>
                        <input type="radio" name="w_search_type" id="tweettee-search-category-name" value="2" <?= $search_content_2 ?>>
                        <label for="tweettee-search-category-name"><?php _e('category name', 'tweettee'); ?></label>
                        <br>
                        <input type="radio" name="w_search_type" id="tweettee-search-free-word" value="4" <?= $search_content_4 ?>>
                        <label for="tweettee-search-free-word"><?php _e('any word', 'tweettee'); ?></label>
                        <input type="text" id="tweettee-free-word-value" name="w_search_word" size="20" value="<?= $options['w_search_word'] ?>">
                        <span id="tweettee-free-word-error"></span>

                        <div class="result-type">
                            <label for="w-result-type"><?php _e('Search result', 'tweettee'); ?></label><br>

                            <select name="w_result_type" id="w-result-type">
                                <option value="popular" <?= $w_tweettee_popular ?>><?php _e('Popular', 'tweettee'); ?></option>
                                <option value="recent" <?= $w_tweettee_recent ?>><?php _e('Recent', 'tweettee'); ?></option>
                                <option value="mixed" <?= $w_tweettee_mixed ?>><?php _e('Mixed', 'tweettee'); ?></option>
                            </select>    

                        </div>

                        <div class="tweettee-language">
                            <label for="search-language"><?php _e('Language', 'tweettee'); ?></label><br>

                            <select name="w_language" id="search-language">
                                <?php
                                $selected = '';
                                foreach ($this->language as $key => $val) {
                                    $options['w_language'] === $key ? $selected = 'selected' : $selected = '';
                                    printf("<option value='%s' " . $selected . ">%s</option>\r\n", $key, $val);
                                }
                                ?>
                            </select>    

                        </div>

                    </div>
                </div>
                <hr>

                <label for="tweettee-twit-count"><?php _e('Number of tweets', 'tweettee'); ?></label>
                <input type="text" id="tweettee-twit-count" name="w_count" size="3" value="<?= $options['w_count'] ?>">
                <hr>

                <input type="checkbox" id="tweettee-only-text" name="w_only_text" value="checked" <?= $options['w_only_text'] ?>>
                <label for="tweettee-only-text"><?php _e('Show only text', 'tweettee'); ?></label>
                <hr>

                <input type="checkbox" id="rel-nofollow" name="w_rel_nofollow" value="checked" <?= $options['w_rel_nofollow'] ?>>
                <label for="rel-nofollow"><?php _e('All tweettee links with "<b>rel=nofollow</b>"', 'tweettee'); ?></label>
                <hr>

                <input type="checkbox" id="noindex" name="w_noindex" value="checked" <?= $options['w_noindex'] ?>>
                <label for="noindex"><?php _e('Wrap tweettee unit in "<b>&lt;!--noindex--&gt;</b>" (For SE Yandex)', 'tweettee'); ?></label>
            </fieldset>

            <fieldset>
                <legend><?php _e('Cache', 'tweettee'); ?></legend>
                
                <input type="checkbox" id="cache" name="cache_enabled" value="checked" <?= $options['cache_enabled'] ?>>
                <label for="cache"><?php _e('Enable caching', 'tweettee'); ?></label>
                <hr>
                
                <label for="cache-time"><?php _e('Cache interval', 'tweettee'); ?></label>
                <input type="time" id="cache-time" name="cache_interval" step="60" value="<?= $options['cache_interval'] ?>">
                <span>(<?php _e('In this time cache will be updated', 'tweettee'); ?>)</span>
                
            </fieldset>

            <button type="submit" name="tweettee_change_settings" id="tweettee_change_settings" class="button-primary">
                <?php _e('Save settings', 'tweettee'); ?>
            </button>
        </form>
    <?php endif; ?>

    <?= $this->error_message ?>
</div>
