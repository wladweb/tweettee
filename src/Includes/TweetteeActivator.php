<?php

namespace Wladweb\Tweettee\Includes;

use Wladweb\Tweettee\Includes\Core\TweetteeCache;

class TweetteeActivator
{

    public static function activate()
    {

        $value = array(
            'consumer_key' => null,
            'consumer_secret' => null,
            'oauth_token' => null,
            'oauth_token_secret' => null,
            'user_id' => null,
            'screen_name' => null,
            'account_info' => null,
            'show_main_page_settings' => null,
            'w_content_type' => 1,
            'w_another_timeline' => null,
            'w_search_type' => 1,
            'w_search_word' => null,
            'w_count' => 10,
            'w_rel_nofollow' => null,
            'w_noindex' => null,
            'w_only_text' => null,
            'w_result_type' => 'mixed',
            'w_language' => 'all',
            'm_position' => 1,
            'm_content_type' => 1,
            'm_another_timeline' => null,
            'm_search_type' => 1,
            'm_search_word' => null,
            'm_count' => 10,
            'm_rel_nofollow' => null,
            'm_noindex' => null,
            'm_only_text' => null,
            'm_result_type' => 'mixed',
            'm_language' => 'all',
            'cache_enabled' => null,
            'cache_interval' => null,
            'cache_previous_state' => null,
            'cache_begin_timestamp' => null
        );

        add_option('tweettee', $value);
        
        TweetteeCache::createTable();
    }

}
