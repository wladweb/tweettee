<?php

namespace Tweettee\Includes;

class Tweettee_Activator{
    public static function activate(){
        
        $value = array(
            'consumer_key' => 'Не задан',
            'consumer_secret' => 'Не задан',
            'access_token' => NULL,
            'access_secret' => NULL,
            'account_info' => NULL,
            'show-main-page-settings' => NULL,
            'w_content_type' => 1,
            'w_another_timeline' => NULL,
            'w_search_type' => 1,
            'w_search_word' => NULL,
            'w_count' => 10,
            'w_rel_nofollow' => NULL,
            'w_noindex' => NULL,
            'w_only_text' => NULL,
            'w_language' => 'all',
            'm_content_type' => 1,
            'm_another_timeline' => NULL,
            'm_search_type' => 1,
            'm_search_word' => NULL,
            'm_count' => 10,
            'm_rel_nofollow' => NULL,
            'm_noindex' => NULL,
            'm_only_text' => NULL,
            'm_language' => 'all'
        );
        
        add_option('tweettee', $value);
    }
}

