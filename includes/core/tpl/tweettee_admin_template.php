
<h1>Tweettee</h1>

    <?php if (is_null($this->option['access_token']) || is_null($this->option['access_secret'])) : ?>
    <form method="POST" action="<?php $SERVER['REQUEST_URI']; ?>">
        <?php wp_nonce_field('auth_form', 'oauth_nonce'); ?>
        <table class="oauth_data">
            <tr>
                <td><label for="ck">Consumer Key</label></td>
                <td><input type="text" name="consumer_key" id="ck" value="<?php echo $this->option['consumer_key']; ?>"></td>
            </tr>
            <tr>
                <td><label for="cs">Consumer Secret</label></td>
                <td><input type="text" name="consumer_secret" id="cs" value="<?php echo $this->option['consumer_secret']; ?>"></td>
            </tr>
            <tr>
                <td colspan="2"><button type="submit" name="consumer_submit">Отправить</button></td>
            </tr>
        </table>
    </form>
<?php else : ?>
    <?php $account_info = $this->option['account_info']; ?>
    <?php if (!isset($account_info->errors)) : ?>

        <div class="account-wrapper">
            <?php 
                (bool)$account_info->profile_use_background_image ? $background = "url('{$account_info->profile_background_image_url}')" : $background = "#C0DEED" ; 
                $profile_image = str_replace('_normal', '_200x200', $account_info->profile_image_url);  
            ?>
            <div class="account-body" style="background: <?php echo $background; ?>">
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

        <h2>Settings</h2>
        
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
                <legend>Блок на главной</legend>
                
                <input class="checkbox" type="checkbox" id="show-main-page-settings" name="show_main_page_settings" value="checked" <?php echo $this->option['show_main_page_settings'] ?>>
                <label for="show-main-page-settings">Выводить блок с твитами на главной</label>

                <div id="settings-main-page-block">
                    <div>
                        
                        <input type="text" name="m_after_which_post" id="m-after-post" value="<?php echo $this->option['m_after_which_post'] ?>" size="2" maxlength="2">
                        <label for="m-after-post">После какого поста выводить блок с твитами</label>
                        <br>
                        
                        <input type="radio" name="m_content_type" id="m-tweettee-my-twits" value="1" <?php echo $m_content_1; ?>>
                        <label for="m-tweettee-my-twits">Мои твиты</label>
                        <br>
                        
                        <input type="radio" name="m_content_type" id="m-tweettee-my-timeline" value="2" <?php echo $m_content_2; ?>>
                        <label for="m-tweettee-my-timeline">Моя лента</label>
                        <br>
                        
                        <input type="radio" name="m_content_type" id="m-tweettee-about-my-twitter" value="3" <?php echo $m_content_3; ?>>
                        <label for="m-tweettee-about-my-twitter">Упоминания</label>
                        <br>
                        
                        <input type="radio" name="m_content_type" id="m-tweettee-another-timeline" value="4" <?php echo $m_content_4; ?>>
                        <label for="m-tweettee-another-timeline">Твиты другого твитттер аккаунта:</label>
                        <input type="text" id="m-tweettee-another-timeline-name" name="m_another_timeline" size="20" value="<?php echo $this->option['m_another_timeline'] ?>">
                        <span id="m-tweettee-another-timeline-error"></span>
                        <br>
                        
                        <input type="radio" name="m_content_type" id="m-tweettee-search-result" value="5" <?php echo $m_content_5; ?>>
                        <label for="m-tweettee-search-result">Результат поиска по: </label>
                            <div id="m-search-result-for">
                                <input type="radio" name="m_search_type" id="m-tweettee-search-free-word" value="4" <?php echo $m_search_content_4; ?>>
                                <label for="m-tweettee-search-free-word">произвольной фразе</label>
                                <input type="text" id="m-tweettee-free-word-value" name="m_search_word" size="20" value="<?php echo $this->option['m_search_word']; ?>">
                                <span id="m-tweettee-free-word-error"></span>

                                <div class="result-type">
                                    <label for="m-result-type">Результаты поиска</label><br>

                                    <select name="m_result_type" id="m-result-type">
                                        <option value="popular" <?php echo $m_tweettee_popular; ?>>Популярные</option>
                                        <option value="recent" <?php echo $m_tweettee_recent; ?>>Последние</option>
                                        <option value="mixed" <?php echo $m_tweettee_mixed; ?>>Смешанные</option>
                                    </select>    

                                </div>
                                
                                <div class="tweettee-language">
                                    <label for="m-search-language">Language</label><br>

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
                
                <label for="m-tweettee-twit-count">Количество твитов</label>
                <input type="text" id="m-tweettee-twit-count" name="m_count" size="3" value="<?php echo $this->option['m_count']; ?>">
                <hr>
                
                <input type="checkbox" id="m-tweettee-only-text" name="m_only_text" value="checked" <?php echo $this->option['m_only_text'] ?>>
                <label for="m-tweettee-only-text">Выводить только текст твитов</label>
                <hr>
                
                <input type="checkbox" id="m-rel-nofollow" name="m_rel_nofollow" value="checked" <?php echo $this->option['m_rel_nofollow'] ?>>
                <label for="m-rel-nofollow">Seo</label>
                <hr>
                
                <input type="checkbox" id="m-noindex" name="m_noindex" value="checked" <?php echo $this->option['m_noindex'] ?>>
                <label for="m-noindex">Noindex</label>
                </div>
            </fieldset>
<!----------------------------------------------------------delimiter------------------------------------------------------------>            
            <fieldset>
                <legend>Виджет</legend>
                
                <div>
                    <input type="radio" name="w_content_type" id="tweettee-my-twits" value="1" <?php echo $widget_content_1; ?>>
                    <label for="tweettee-my-twits">Мои твиты</label>
                    <br>
                    
                    <input type="radio" name="w_content_type" id="tweettee-my-timeline" value="2" <?php echo $widget_content_2; ?>>
                    <label for="tweettee-my-timeline">Моя лента</label>
                    <br>
                    
                    <input type="radio" name="w_content_type" id="tweettee-about-my-twitter" value="3" <?php echo $widget_content_3; ?>>
                    <label for="tweettee-about-my-twitter">Упоминания</label>
                    <br>
                    
                    <input type="radio" name="w_content_type" id="tweettee-another-timeline" value="4" <?php echo $widget_content_4; ?>>
                    <label for="tweettee-another-timeline">Твиты другого твитттер аккаунта:</label>
                    <input type="text" id="tweettee-another-timeline-name" name="w_another_timeline" size="20" value="<?php echo $this->option['w_another_timeline'] ?>">
                    <span id="tweettee-another-timeline-error"></span>
                    <br>
                    
                    <input type="radio" name="w_content_type" id="tweettee-search-result" value="5" <?php echo $widget_content_5; ?>>
                    <label for="tweettee-search-result">Результат поиска по: </label>
                        <div id="search-result-for">
                            <input type="radio" name="w_search_type" id="tweettee-search-post-bookmark" value="1" <?php echo $search_content_1; ?>>
                            <label for="tweettee-search-post-bookmark">меткам поста</label>
                            <br>
                            <input type="radio" name="w_search_type" id="tweettee-search-category-name" value="2" <?php echo $search_content_2; ?>>
                            <label for="tweettee-search-category-name">имени категории поста</label>
                            <br>
                            <input type="radio" name="w_search_type" id="tweettee-search-free-word" value="4" <?php echo $search_content_4; ?>>
                            <label for="tweettee-search-free-word">произвольной фразе</label>
                            <input type="text" id="tweettee-free-word-value" name="w_search_word" size="20" value="<?php echo $this->option['w_search_word']; ?>">
                            <span id="tweettee-free-word-error"></span>
                            
                            <div class="result-type">
                                    <label for="w-result-type">Результаты поиска</label><br>

                                    <select name="w_result_type" id="w-result-type">
                                        <option value="popular" <?php echo $w_tweettee_popular; ?>>Популярные</option>
                                        <option value="recent" <?php echo $w_tweettee_recent; ?>>Последние</option>
                                        <option value="mixed" <?php echo $w_tweettee_mixed; ?>>Смешанные</option>
                                    </select>    

                                </div>
                            
                            <div class="tweettee-language">
                                <label for="search-language">Language</label><br>
                                
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
                
                <label for="tweettee-twit-count">Количество твитов</label>
                <input type="text" id="tweettee-twit-count" name="w_count" size="3" value="<?php echo $this->option['w_count']; ?>">
                <hr>
                
                <input type="checkbox" id="tweettee-only-text" name="w_only_text" value="checked" <?php echo $this->option['w_only_text'] ?>>
                <label for="tweettee-only-text">Выводить только текст твитов</label>
                <hr>
                
                <input type="checkbox" id="rel-nofollow" name="w_rel_nofollow" value="checked" <?php echo $this->option['w_rel_nofollow'] ?>>
                <label for="rel-nofollow">Seo</label>
                <hr>
                
                <input type="checkbox" id="noindex" name="w_noindex" value="checked" <?php echo $this->option['w_noindex'] ?>>
                <label for="noindex">Noindex</label>
            </fieldset>

            <button type="submit" name="tweettee_change_settings" id="tweettee_change_settings" class="button-primary">
                Сохранить
            </button>
        </form>
    <?php else : ?>
        <?php $this->message = $account_info->errors[0]->message; ?>
    <?php endif; ?>
<?php endif; ?>
    
<p class="message">
    <?php echo $this->message; ?>
</p>

