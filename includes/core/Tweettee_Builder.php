<?php

namespace Tweettee\Includes\Core;
use Tweettee\Includes\Oauth\TwitterOAuth;

abstract class Tweettee_Builder{
    
    protected $twitteroauth = null;
    protected $option_general;
    protected $option_widget;
    protected $option_main;
    
    public function __construct(){
        
        $option = get_option('tweettee');
        $this->option_general = $option['tweettee_general'];
        $this->option_widget = $option['tweettee_widget'];
        $this->option_main = $option['tweettee_main'];
        unset($option);
        
        if (!is_null($this->option_general['access_token']) && !is_null($this->option_general['access_secret'])){
            $this->twitteroauth = new TwitterOAuth(
                        $this->option_general['consumer_key'],
                        $this->option_general['consumer_secret'],
                        $this->option_general['access_token'],
                        $this->option_general['access_secret']
                    );
        }
    }
    
    abstract public function draw_tweettee();
    
    protected function get_tweetts($mode, $prefix = ''){
        
        switch($mode){
            case 1: 
                $params = $this->build_params($mode, $prefix);
                $data = $this->get_data('statuses/user_timeline', $params);
                break;
            case 2: 
                
                break;
            case 3: 
                $params = $this->build_params($mode, $prefix);
                $this->get_data('statuses/mentions_timeline', $params);
                break;
            case 4: 
                $params = $this->build_params($mode, $prefix);
                $data = $this->get_data('statuses/user_timeline', $params);
                break;
            case 5: 
                $this->data = $this->get_search_result($this->get_quest_value());
                break;
        }
        
        
        
        return $data;
    }
    
    private function build_params($mode, $prefix){
        
        $params = array();
        $params['count'] = $this->option[$prefix . 'tweettee_count'];
        
        //$params['since_id'] = 0; //next version
        
        switch ($mode){
            case 1:
                $params['exclude_replies'] = TRUE;
                $params['screen_name'] = $this->option[$prefix . 'screen_name'];
                break;
            case 2:
                break;
            case 3:
                break;
            case 4:
                $params['exclude_replies'] = TRUE;
                $params['screen_name'] = $this->option[$prefix . 'another-timeline-name'];
                break;
            case 5:
                break;
        }
        
        return $params;
    }
    
    private function get_data($request_string, array $params){
        
        $data = $this->twitteroauth->get($request_string, $params);
        
        if (is_object($data) && !empty($data->errors[0]->message)){
            throw new Tweettee_Exception($data->errors[0]->message);
        }
        
        return $data;
    }
    
    private function build_link($url, $text){
        $rel_nofollow = '';
        
        if ($this->option['rel_nofollow']){
            $rel_nofollow = ' rel="nofollow" ';
        }
        
        $link = "<a href='{$url}'{$rel_nofollow} target='_blank'>{$text}</a>";
        return $link; 
    }
    
    private function prepare_text(){
        foreach ($this->result->statuses as $twit){
            
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
        }
    }
    
    private function clear_str($str){
        return trim(strip_tags($str));
    }
}

