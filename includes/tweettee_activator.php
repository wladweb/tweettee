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
            'widget-content-type' => 1,
            'another-timeline-name' => NULL,
            'search-content-type' => 1,
            'show-main-page-settings' => NULL,
            'tweettee-search-free-word' => NULL,
            'tweettee_count' => 10,
            'user_id' => NULL,
            'screen_name' => NULL,
            'rel_nofollow' => NULL,
            'noindex' => NULL,
            'tweettee-language' => 'all'
        );
        add_option('tweettee', $value);
    }
}

