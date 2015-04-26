<?php

namespace Tweettee\Includes\Core;
use Tweettee\Includes\Oauth\TwitterOAuth;

abstract class Tweettee_Builder{
    
    protected $twitteroauth = null;
    protected $option;
    protected $prefix;
    protected $gmt_offset = 0;
    
    public function __construct(){
        
        $this->prefix = static::who();
        $this->gmt_offset = get_option('gmt_offset');
        $this->option = get_option('tweettee');
        if (!is_null($this->option['access_token']) && !is_null($this->option['access_secret'])){
            $this->twitteroauth = new TwitterOAuth(
                        $this->option['consumer_key'],
                        $this->option['consumer_secret'],
                        $this->option['access_token'],
                        $this->option['access_secret']
                    );
        }
    }
    
    abstract public function draw_tweettee();
    
    protected function get_tweetts(){
        
        $mode = $this->option[$this->prefix .'content_type']; 
        switch($mode){
            case 1: 
                $params = $this->build_params($mode);
                $data = $this->get_data('statuses/user_timeline', $params);
                break;
            case 2: 
                $params = $this->build_params($mode);
                $data = $this->get_data('statuses/home_timeline', $params);
                break;
            case 3: 
                $params = $this->build_params($mode);
                $data = $this->get_data('statuses/mentions_timeline', $params);
                break;
            case 4: 
                $params = $this->build_params($mode);
                $data = $this->get_data('statuses/user_timeline', $params);
                break;
            case 5: 
                $params = $this->build_params($mode);
                $data = $this->get_data('search/tweets', $params);
                break;
            default:
                $params = $this->build_params($mode);
                $data = $this->get_data('statuses/user_timeline', $params);
        }
        
        return $data;
    }
    
    private function build_params($mode){
        
        $params = array();
        $params['count'] = $this->clear_int($this->option[$this->prefix.'count']);
        
        switch ($mode){
            case 1:
                $params['exclude_replies'] = TRUE;
                $params['screen_name'] = $this->option['screen_name'];
                break;
            case 2:
                $params['exclude_replies'] = TRUE;
                break;
            case 3:
                break;
            case 4:
                $params['exclude_replies'] = TRUE;
                $params['screen_name'] = $this->option[$this->prefix.'another_timeline'];
                break;
            case 5:
                $params['q'] = $this->get_search_value($this->option[$this->prefix.'search_type']);
                $params['lang'] = $this->option[$this->prefix.'language'];
                $params['result_type'] = $this->option[$this->prefix.'result_type'];
                break;
            default:
                $params['exclude_replies'] = TRUE;
                $params['screen_name'] = $this->option['screen_name'];
        }
        
        return $params;
    }
    
    private function get_search_value($search_mode){
        global $post;
        $id = $post->ID;
        switch ($search_mode){
            case 1:
                $tags_arr = wp_get_post_tags($id, array('fields' => 'names'));
                if (empty($tags_arr)){
                    throw new Tweettee_Exception('Нет меток');
                }
                
                $search_word = $tags_arr[0];
                break;
            case 2:
                $cat_arr = get_the_category($id);
                
                if (empty($cat_arr)){
                    throw new Tweettee_Exception('Нет категории');
                }
                $search_word = $cat_arr[0]->name;
                break;
            case 3:
                break;
            case 4:
                $search_word = $this->option[$this->prefix.'search_word'] ? $this->option[$this->prefix.'search_word'] : 'twitter' ;
                break;
            default:
                $tags_arr = wp_get_post_tags($id, array('fields' => 'names'));
                if (empty($tags_arr)){
                    throw new Tweettee_Exception('Нет меток');
                }
                
                $search_word = $tags_arr[0];
        }
        
        return urlencode($search_word);
    }
    
    private function get_data($request_string, array $params){
        
        $data = $this->twitteroauth->get($request_string, $params);
//var_dump($data);
        if (is_object($data) && !empty($data->errors[0]->message)){
            throw new Tweettee_Exception($data->errors[0]->message);
        }
        
        if(is_object($data) && isset($data->statuses)){
            if (!empty($data->statuses)){
               $this->prepare_text($data->statuses); 
               return $data->statuses; 
            }else{
                throw new Tweettee_Exception("По запросу <span class='user-request'>" . urldecode($params['q']) . "</span> ничего не найдено");
            }
            
        }
        
        $this->prepare_text($data);
        return $data;
    }
    
    protected function build_link($url, $text){
        $rel_nofollow = '';
        
        if ($this->option[$this->prefix.'rel_nofollow']){
            $rel_nofollow = ' rel="nofollow" ';
        }
        
        $link = "<a href='{$url}'{$rel_nofollow} target='_blank'>{$text}</a>";
        return $link; 
    }
    
    private function prepare_text(array &$data){
        foreach ($data as $twit){
            
            $hashtags = array();
            $hashtags_replace = array();
            foreach ($twit->entities->hashtags as $hashtag){
                $hashtags[] = '#' . $hashtag->text;
                $hashtags_replace[] = $this->build_link('https://twitter.com/hashtag/'. urlencode($hashtag->text) .'/?src=hash', '#' . $hashtag->text);
            }
            $twit->text = str_replace($hashtags, $hashtags_replace, $twit->text);
            
            $links = array();
            $links_replace = array();
            foreach ($twit->entities->urls as $url_obj){
                $links[] = $url_obj->url;
                $links_replace[] = $this->build_link($url_obj->url, $url_obj->url);
            }
            $twit->text = str_replace($links, $links_replace, $twit->text);
            
            $users = array();
            $users_replace = array();
            foreach ($twit->entities->user_mentions as $url_obj){
                $users[] = $url_obj->screen_name;
                $users_replace[] = $this->build_link('https://twitter.com/'. $url_obj->screen_name, $url_obj->screen_name);
            }
            $twit->text = str_replace($users, $users_replace, $twit->text);
        }
    }
    
    protected function get_correct_time($time_string){
        
       $time = new \DateTime($time_string);
       
       substr($this->gmt_offset, 0, 1) === '-' ? $modify = $this->gmt_offset : $modify = '+' . $this->gmt_offset ;
       
       $time->modify($modify . ' hour');
       
       return $time->format('d M Y H:i:s');
    }
    
    protected function clear_str($str){
        return trim(strip_tags($str));
    }
    
    protected function clear_int($str){
        return abs((int)$str);
    }
    
}

