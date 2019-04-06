<?php

namespace Wladweb\Tweettee\Includes\Core;

/**
 * Twit object
 */
class Tweet
{
    private $keys = [
        'id',
        'profile_image_url',
        'screen_name',
        'text',
        'created_at'
    ];
    
    private $storage = [];
    
    public function __set($name, $value)
    {
        if (in_array($name, $this->keys)){
            $this->storage[$name] = $value;
        }
    }
    
    public function __get($name)
    {
        if (array_key_exists($name, $this->storage)){
            return $this->storage[$name];
        }
    }
}
