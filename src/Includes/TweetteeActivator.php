<?php

namespace Tweettee\Includes;

class Tweettee_Activator{
    public static function activate(){
        
        $value = array(
            'consumer_key' => NULL,
            'consumer_secret' => NULL,
            'access_token' => NULL,
            'access_secret' => NULL,
            'account_info' => NULL,
            'show_main_page_settings' => NULL,
            'w_content_type' => 1,
            'w_another_timeline' => NULL,
            'w_search_type' => 1,
            'w_search_word' => NULL,
            'w_count' => 10,
            'w_rel_nofollow' => NULL,
            'w_noindex' => NULL,
            'w_only_text' => NULL,
            'w_result_type' => 'mixed',
            'w_language' => 'all',
            'm_after_which_post' => 1,
            'm_content_type' => 1,
            'm_another_timeline' => NULL,
            'm_search_type' => 1,
            'm_search_word' => NULL,
            'm_count' => 10,
            'm_rel_nofollow' => NULL,
            'm_noindex' => NULL,
            'm_only_text' => NULL,
            'm_result_type' => 'mixed',
            'm_language' => 'all'
        );
        
        add_option('tweettee', $value);
    }
}

