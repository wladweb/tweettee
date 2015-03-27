
<h1>Tweettee</h1>

<?php echo '<pre>'; print_r($this->option); echo '</pre>';?> 
<?php echo '<pre>'; var_dump($_POST); echo '</pre>';?> 

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
            $tweettee_language_1 = $tweettee_language_1 = $tweettee_language_1 = $tweettee_language_1 = $tweettee_language_1 = $tweettee_language_1 = $tweettee_language_1 = '';
            $w_c = 'widget_content_'. $this->option['w_content_type'];
            $s_c = 'search_content_'. $this->option['w_search_type'];
            $t_l = 'tweettee_language_'. $this->option['w_language'];
            $$w_c = $$s_c = $$t_l = 'checked';
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
                <p>
                    Выводить блок с твитами на главной
                </p>
                <label for="show-main-page-settings">Да</label>
                <input type="checkbox" id="show-main-page-settings" name="show-main-page-settings" value="checked" <?php echo $this->option['show-main-page-settings'] ?>>
                <div id="settings-main-page-block">
                    //
                </div>
            </fieldset>
            
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
                            <input type="radio" name="w_search_type" id="tweettee-search-keywords" value="3" <?php echo $search_content_3; ?>>
                            <label for="tweettee-search-keywords">ключевым словам</label>
                            <br>
                            <input type="radio" name="w_search_type" id="tweettee-search-free-word" value="4" <?php echo $search_content_4; ?>>
                            <label for="tweettee-search-free-word">произвольной фразе</label>
                            <input type="text" id="tweettee-free-word-value" name="w_search_word" size="20">
                            <span id="tweettee-free-word-error"></span>
                            <br>
                            
                            <div id="tweettee-language">
                                <h5>Language</h5>
                                <ul>
                                    <li>
                                        <input type="radio" name="w_language" id="tweettee-language-all" value="all" <?php echo $tweettee_language_all; ?>>
                                        <label for="tweettee-language-all" class="flag"></label>
                                    </li>
                                    <li>
                                        <input type="radio" name="w_language" id="tweettee-language-en" value="en" <?php echo $tweettee_language_en; ?>>
                                        <label for="tweettee-language-en" class="flag"></label>
                                    </li>
                                    <li>
                                        <input type="radio" name="w_language" id="tweettee-language-ru" value="ru" <?php echo $tweettee_language_ru; ?>>
                                        <label for="tweettee-language-ru" class="flag"></label>
                                    </li>
                                    <li>
                                        <input type="radio" name="w_language" id="tweettee-language-de" value="de" <?php echo $tweettee_language_de; ?>>
                                        <label for="tweettee-language-de" class="flag"></label>
                                    </li>
                                    <li>
                                        <input type="radio" name="w_language" id="tweettee-language-fr" value="fr" <?php echo $tweettee_language_fr; ?>>
                                        <label for="tweettee-language-fr" class="flag"></label>
                                    </li>
                                    <li>
                                        <input type="radio" name="w_language" id="tweettee-language-it" value="it" <?php echo $tweettee_language_it; ?>>
                                        <label for="tweettee-language-it" class="flag"></label>
                                    </li>
                                    <li>
                                        <input type="radio" name="w_language" id="tweettee-language-es" value="es" <?php echo $tweettee_language_es; ?>>
                                        <label for="tweettee-language-es" class="flag"></label>
                                    </li>
                                </ul>
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

            <button type="submit" name="tweettee_change_settings" id="tweettee_change_settings">
                Submit
            </button>
        </form>
    <?php else : ?>
        <?php $this->message = $account_info->errors[0]->message; ?>
    <?php endif; ?>
<?php endif; ?>
    
<p class="message">
    <?php echo $this->message; ?>
</p>

