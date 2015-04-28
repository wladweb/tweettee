<div class="tweettee-admin-block">
    <?php //var_dump($this->option); ?>
    <h1>Tweettee</h1>

        <?php if (is_null($this->option['access_token']) || is_null($this->option['access_secret'])) : ?>

        <div class="instructions">
            <ol>
                <li><?php _e('Create new Application on ', 'tweettee'); ?><a href="https://apps.twitter.com/" target="_blank">https://apps.twitter.com/</a></li>
                <li><?php _e('Input <b>Callback URL</b> any correct url. Example: ', 'tweettee'); ?>http://test.loc</li>
                <li><?php _e('Copy <b>Consumer Key</b> and <b>Consuner Secret</b> in form.', 'tweettee'); ?></li>
                <li><?php _e('Send form and follow the instructions.', 'tweettee'); ?></li>
            </ol>
        </div>
        <div class="first-form">
            <form method="POST" action="<?php $SERVER['REQUEST_URI']; ?>">
                <?php wp_nonce_field('auth_form', 'oauth_nonce'); ?>
                <table class="oauth_data">
                    <tr>
                        <td><label for="ck">Consumer Key</label></td>
                        <td><input type="text" name="consumer_key" id="ck" value="<?php echo $this->option['consumer_key']; ?>" placeholder="Empty"></td>
                    </tr>
                    <tr>
                        <td><label for="cs">Consumer Secret</label></td>
                        <td><input type="text" name="consumer_secret" id="cs" value="<?php echo $this->option['consumer_secret']; ?>" placeholder="Empty"></td>
                    </tr>
                    <tr>
                        <td colspan="2"><button type="submit" name="consumer_submit" class="button-primary"><?php _e('Send', 'tweettee'); ?></button></td>
                    </tr>
                </table>
            </form>
        </div>    
    <?php else : ?>
        <?php $account_info = $this->option['account_info']; ?>
        <?php if (!isset($account_info->errors)) : ?>

            <div class="account-wrapper">
                <?php 
                    $account_info->profile_use_background_image ? $background = "url('{$account_info->profile_background_image_url}')" : $background = "#C0DEED" ;
                    $account_info->profile_background_tile ? $background_repeat = 'repeat' : $background_repeat = 'no-repeat';
                    $profile_image = str_replace('_normal', '_200x200', $account_info->profile_image_url);  
                ?>
                <div class="account-body" style="
                     background-image: <?php echo $background; ?>;
                     background-color: #<?php echo $account_info->profile_background_color; ?>;
                     background-repeat: <?php echo $background_repeat; ?>
                     ">
                    <img src="<?php echo $profile_image; ?>" class="account-image">
                    <div class="account-info">
                        <h4><a href="https://twitter.com/<?php echo $account_info->screen_name; ?>" target="_blank"><?php echo $account_info->name; ?></a></h4>
                        <h4><?php echo '@' . $account_info->screen_name; ?></h4>
                        <p class="account-description">
                            <?php echo $account_info->description; ?>
                        </p>
                    </div>
                </div>
            </div>

            <h2><?php _e('Settings', 'tweettee'); ?></h2>

            <?php
                $widget_content_1 = $widget_content_2 = $widget_content_3 = $widget_content_4 = '';
                $search_content_1 = $search_content_2 = $search_content_3 = $search_content_4 = '';
                $w_tweettee_popular = $w_tweettee_recent = $w_tweettee_mixed ='';

                $m_content_1 = $m_content_2 = $m_content_3 = $m_content_4 = '';
                $m_search_content_1 = $m_search_content_2 = $m_search_content_3 = $m_search_content_4 = '';
                $m_tweettee_popular = $m_tweettee_recent = $m_tweettee_mixed ='';

                $w_c = 'widget_content_'. $this->option['w_content_type'];
                $s_c = 'search_content_'. $this->option['w_search_type'];
                $m_c = 'm_content_'. $this->option['m_content_type'];
                $m_s = 'm_search_content_'. $this->option['m_search_type'];
                $w_type = 'w_tweettee_' . $this->option['w_result_type'];
                $m_type = 'm_tweettee_' . $this->option['m_result_type'];

                $$w_c = $$s_c = $$m_c = $$m_s = 'checked';
                $$w_type = $$m_type = 'selected';

                if ($this->get_admin_page_url()){
                    $str = '"' . $this->get_admin_page_url() . '"';
                }else{
                    $str = 'undefined';
                }

                print "<script>var tweettee_oauth_redir_url = " . $str . "</script>";
            ?>

            <form method="post" action="<?php $SERVER['REQUEST_URI'] ?>" name="">

                <fieldset>
                    <legend><?php _e('Home page block', 'tweettee'); ?></legend>

                    <input class="checkbox" type="checkbox" id="show-main-page-settings" name="show_main_page_settings" value="checked" <?php echo $this->option['show_main_page_settings'] ?>>
                    <label for="show-main-page-settings"><?php _e('Show tweettee unit on the home page. (Not for all themes)', 'tweettee'); ?></label>

                    <div id="settings-main-page-block">
                        <div>

                            <input type="text" name="m_after_which_post" id="m-after-post" value="<?php echo $this->option['m_after_which_post'] ?>" size="2" maxlength="2">
                            <label for="m-after-post"><?php _e('After which post show unit with tweets', 'tweettee'); ?></label>
                            <br>

                            <input type="radio" name="m_content_type" id="m-tweettee-my-twits" value="1" <?php echo $m_content_1; ?>>
                            <label for="m-tweettee-my-twits"><?php _e('User timeline', 'tweettee'); ?></label>
                            <br>

                            <input type="radio" name="m_content_type" id="m-tweettee-my-timeline" value="2" <?php echo $m_content_2; ?>>
                            <label for="m-tweettee-my-timeline"><?php _e('Home timeline', 'tweettee'); ?></label>
                            <br>

                            <input type="radio" name="m_content_type" id="m-tweettee-about-my-twitter" value="3" <?php echo $m_content_3; ?>>
                            <label for="m-tweettee-about-my-twitter"><?php _e('Mentions', 'tweettee'); ?></label>
                            <br>

                            <input type="radio" name="m_content_type" id="m-tweettee-another-timeline" value="4" <?php echo $m_content_4; ?>>
                            <label for="m-tweettee-another-timeline"><?php _e('Home timeline another account', 'tweettee'); ?></label>
                            <input type="text" id="m-tweettee-another-timeline-name" name="m_another_timeline" size="20" value="<?php echo $this->option['m_another_timeline'] ?>">
                            <span id="m-tweettee-another-timeline-error"></span>
                            <br>

                            <input type="radio" name="m_content_type" id="m-tweettee-search-result" value="5" <?php echo $m_content_5; ?>>
                            <label for="m-tweettee-search-result"><?php _e('Search by :', 'tweettee'); ?></label>
                                <div id="m-search-result-for">
                                    <input type="radio" name="m_search_type" id="m-tweettee-search-free-word" value="4" <?php echo $m_search_content_4; ?>>
                                    <label for="m-tweettee-search-free-word"><?php _e('free word', 'tweettee'); ?></label>
                                    <input type="text" id="m-tweettee-free-word-value" name="m_search_word" size="20" value="<?php echo $this->option['m_search_word']; ?>">
                                    <span id="m-tweettee-free-word-error"></span>

                                    <div class="result-type">
                                        <label for="m-result-type"><?php _e('Search result', 'tweettee'); ?></label><br>

                                        <select name="m_result_type" id="m-result-type">
                                            <option value="popular" <?php echo $m_tweettee_popular; ?>><?php _e('Popular', 'tweettee'); ?></option>
                                            <option value="recent" <?php echo $m_tweettee_recent; ?>><?php _e('Recent', 'tweettee'); ?></option>
                                            <option value="mixed" <?php echo $m_tweettee_mixed; ?>><?php _e('Mixed', 'tweettee'); ?></option>
                                        </select>    

                                    </div>

                                    <div class="tweettee-language">
                                        <label for="m-search-language"><?php _e('Language', 'tweettee'); ?></label><br>

                                        <select name="m_language" id="m-search-language">
                                            <?php
                                                $selected = '';
                                                foreach ($this->language as $key => $val){
                                                    $this->option['m_language'] === $key ? $selected = 'selected' : $selected = '';
                                                    echo sprintf("<option value='%s' " . $selected . ">%s</option>\r\n", $key, $val);
                                                }
                                            ?>
                                        </select>    

                                    </div>

                                </div>
                    </div>
                    <hr>

                    <label for="m-tweettee-twit-count"><?php _e('Number of tweets', 'tweettee'); ?></label>
                    <input type="text" id="m-tweettee-twit-count" name="m_count" size="3" value="<?php echo $this->option['m_count']; ?>">
                    <hr>

                    <input type="checkbox" id="m-tweettee-only-text" name="m_only_text" value="checked" <?php echo $this->option['m_only_text'] ?>>
                    <label for="m-tweettee-only-text"><?php _e('Show only text', 'tweettee'); ?></label>
                    <hr>

                    <input type="checkbox" id="m-rel-nofollow" name="m_rel_nofollow" value="checked" <?php echo $this->option['m_rel_nofollow'] ?>>
                    <label for="m-rel-nofollow"><?php _e('All tweettee links with "<b>rel=nofollow</b>"', 'tweettee'); ?></label>
                    <hr>

                    <input type="checkbox" id="m-noindex" name="m_noindex" value="checked" <?php echo $this->option['m_noindex'] ?>>
                    <label for="m-noindex"><?php _e('Wrap tweettee unit in "<b>&lt;!--noindex--&gt;</b>" (For SE Yandex)', 'tweettee'); ?></label>
                    </div>
                </fieldset>
    <!----------------------------------------------------------delimiter------------------------------------------------------------>            
                <fieldset>
                    <legend><?php _e('Widget', 'tweettee'); ?></legend>

                    <div>
                        <input type="radio" name="w_content_type" id="tweettee-my-twits" value="1" <?php echo $widget_content_1; ?>>
                        <label for="tweettee-my-twits"><?php _e('User timeline', 'tweettee'); ?></label>
                        <br>

                        <input type="radio" name="w_content_type" id="tweettee-my-timeline" value="2" <?php echo $widget_content_2; ?>>
                        <label for="tweettee-my-timeline"><?php _e('Home timeline', 'tweettee'); ?></label>
                        <br>

                        <input type="radio" name="w_content_type" id="tweettee-about-my-twitter" value="3" <?php echo $widget_content_3; ?>>
                        <label for="tweettee-about-my-twitter"><?php _e('Mentions', 'tweettee'); ?></label>
                        <br>

                        <input type="radio" name="w_content_type" id="tweettee-another-timeline" value="4" <?php echo $widget_content_4; ?>>
                        <label for="tweettee-another-timeline"><?php _e('Home timeline another account', 'tweettee'); ?></label>
                        <input type="text" id="tweettee-another-timeline-name" name="w_another_timeline" size="20" value="<?php echo $this->option['w_another_timeline'] ?>">
                        <span id="tweettee-another-timeline-error"></span>
                        <br>

                        <input type="radio" name="w_content_type" id="tweettee-search-result" value="5" <?php echo $widget_content_5; ?>>
                        <label for="tweettee-search-result"><?php _e('Search by :', 'tweettee'); ?></label>
                            <div id="search-result-for">
                                <input type="radio" name="w_search_type" id="tweettee-search-post-bookmark" value="1" <?php echo $search_content_1; ?>>
                                <label for="tweettee-search-post-bookmark"><?php _e('post tags', 'tweettee'); ?></label>
                                <br>
                                <input type="radio" name="w_search_type" id="tweettee-search-category-name" value="2" <?php echo $search_content_2; ?>>
                                <label for="tweettee-search-category-name"><?php _e('category name', 'tweettee'); ?></label>
                                <br>
                                <input type="radio" name="w_search_type" id="tweettee-search-free-word" value="4" <?php echo $search_content_4; ?>>
                                <label for="tweettee-search-free-word"><?php _e('free word', 'tweettee'); ?></label>
                                <input type="text" id="tweettee-free-word-value" name="w_search_word" size="20" value="<?php echo $this->option['w_search_word']; ?>">
                                <span id="tweettee-free-word-error"></span>

                                <div class="result-type">
                                        <label for="w-result-type"><?php _e('Search result', 'tweettee'); ?></label><br>

                                        <select name="w_result_type" id="w-result-type">
                                            <option value="popular" <?php echo $w_tweettee_popular; ?>><?php _e('Popular', 'tweettee'); ?></option>
                                            <option value="recent" <?php echo $w_tweettee_recent; ?>><?php _e('Recent', 'tweettee'); ?></option>
                                            <option value="mixed" <?php echo $w_tweettee_mixed; ?>><?php _e('Mixed', 'tweettee'); ?></option>
                                        </select>    

                                    </div>

                                <div class="tweettee-language">
                                    <label for="search-language"><?php _e('Language', 'tweettee'); ?></label><br>

                                    <select name="w_language" id="search-language">
                                        <?php
                                            $selected = '';
                                            foreach ($this->language as $key => $val){
                                                $this->option['w_language'] === $key ? $selected = 'selected' : $selected = '';
                                                echo sprintf("<option value='%s' " . $selected . ">%s</option>\r\n", $key, $val);
                                            }
                                        ?>
                                    </select>    

                                </div>

                            </div>
                    </div>
                    <hr>

                    <label for="tweettee-twit-count"><?php _e('Number of tweets', 'tweettee'); ?></label>
                    <input type="text" id="tweettee-twit-count" name="w_count" size="3" value="<?php echo $this->option['w_count']; ?>">
                    <hr>

                    <input type="checkbox" id="tweettee-only-text" name="w_only_text" value="checked" <?php echo $this->option['w_only_text'] ?>>
                    <label for="tweettee-only-text"><?php _e('Show only text', 'tweettee'); ?></label>
                    <hr>

                    <input type="checkbox" id="rel-nofollow" name="w_rel_nofollow" value="checked" <?php echo $this->option['w_rel_nofollow'] ?>>
                    <label for="rel-nofollow"><?php _e('All tweettee links with "<b>rel=nofollow</b>"', 'tweettee'); ?></label>
                    <hr>

                    <input type="checkbox" id="noindex" name="w_noindex" value="checked" <?php echo $this->option['w_noindex'] ?>>
                    <label for="noindex"><?php _e('Wrap tweettee unit in "<b>&lt;!--noindex--&gt;</b>" (For SE Yandex)', 'tweettee'); ?></label>
                </fieldset>

                <button type="submit" name="tweettee_change_settings" id="tweettee_change_settings" class="button-primary">
                    <?php _e('Save settings', 'tweettee'); ?>
                </button>
            </form>
        <?php else : ?>
            <?php $this->message = $account_info->errors[0]->message; ?>
        <?php endif; ?>
    <?php endif; ?>

    <p class="message">
        <?php echo $this->message; ?>
    </p>
</div>
