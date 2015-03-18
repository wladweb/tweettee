<?php

namespace Tweettee\Public_Part;
use Tweettee\Includes\Oauth\TwitterOAuth;

class Tweettee_Widget extends \WP_Widget{
    
    private $mode = 1;
    private $result = null;
    private $option = null;
    
    public function __construct(){
        parent::__construct('tweettee_plugin', 'Tweettee', array('description' => 'Description here'));
        $this->option = get_option('tweettee');
    }
    
    public function widget($args, $instance){
        $this->get_twitts();
        
        !empty($instance['title']) ? $title = $instance['title'] : $title = $args['widget_name'];
        
        $noindex = $end_noindex ='';
        
        if ($this->option['noindex']){
            $noindex = '<!--noindex-->';
            $end_noindex = '<!--/noindex-->';
        }
        
        $before_widget = $noindex . $args['before_widget'];
        $after_widget = $args['after_widget'] . $end_noindex;
            
        printf($before_widget, $args['widget_name'], $args['widget_name']);
        print $args['before_title'] . $title . $args['after_title'];

        if(is_null($this->result)){
            require_once('tpl/bad_template.php');
        }else{
            $this->prepare_text();
            require_once('tpl/good_template.php');
        }
        
        print $after_widget;
        
        /**********************************************/
        var_dump($args);
        print '<hr>';
        var_dump($instance);
    }
    
    public function update($new_instance, $old_instance){
        var_dump($new_instance);
        var_dump($old_instance);
        $instance = array();
        $instance['title'] = strip_tags($new_instance['title']);
        return $instance;
    }
    
    public function form($instance){
        print '<label>Name</label>'
                . '<input type="text" id="'.$this->get_field_id('title').'" name="'.$this->get_field_name('title').'" value="'. $instance['title'] .'">'
                . '';
        var_dump($instance);
    }
    
    private function get_twitts(){
        
        if (!isset($this->option['access_token']) || !isset($this->option['access_secret'])){
            return false;
        }
        
        $request_string = '';
        
        switch($this->mode){
            case 1:
                $request_string = 'statuses/user_timeline';break;
            case 2:
                $request_string = '';break;
            default:
                $request_string = '';
        }
        
        $connection = new TwitterOAuth(
            $this->option['consumer_key'], 
            $this->option['consumer_secret'], 
            $this->option['access_token'], 
            $this->option['access_secret']
        );

        $this->result = $connection->get($request_string);
        
    }
    
    private function prepare_text(){
        foreach ($this->result as $twit){
            
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
    
    private function build_link($url, $text){
        $rel_nofollow = '';
        
        if ($this->option['seo']){
            $rel_nofollow = ' rel="nofollow" ';
        }
        
        $link = "<a href='{$url}'{$rel_nofollow} target='_blank'>{$text}</a>";
        return $link; 
    }
}

