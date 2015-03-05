<?php

namespace Tweettee\Includes;

class Tweettee_Activator{
    public static function activate(){
        $value = array(
            'consumer_key' => 'Не задан',
            'consumer_secret' => 'Не задан',
            'access_token' => NULL,
            'access_secret' => NULL
        );
        add_option('tweettee', $value);
    }
}

