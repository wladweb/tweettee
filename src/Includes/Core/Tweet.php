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
    
    public function set($data)
    {
        if (is_array($data)){ //from cache
            
            foreach ($data as $key => $val){
                $this->$key = $val;
            }
        } elseif (is_object($data)){ //from twitter
            $this->id = $data->id_str;
            $this->profile_image_url = $data->user->profile_image_url;
            $this->screen_name = $data->user->screen_name;
            $this->text = $data->text;
            $this->created_at = $data->created_at;
        }
    }
}
