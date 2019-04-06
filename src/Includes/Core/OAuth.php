<?php

namespace Wladweb\Tweettee\Includes\Core;

use Abraham\TwitterOAuth\TwitterOAuth;

/**
 * Wrapper on TwitterOauth, singleton cause it could be created in two different places
 */
class OAuth
{
    private static $twitter_oauth;
    
    private function __construct(){}
    private function __clone(){}
    private function __wakeup(){}
    
    public static function getOauth(array $params)
    {
        if (!(self::$twitter_oauth instanceof TwitterOAuth)){
            self::$twitter_oauth = new TwitterOAuth($params['consumer_key'], $params['consumer_secret'], $params['oauth_token'], $params['oauth_token_secret']);
        }
        
        return self::$twitter_oauth;
    }
}
