<?php

namespace Tweettee\Includes\Core;
use Tweettee\Includes\Oauth\TwitterOAuth;
class Tweettee_Builder_Widget extends Tweettee_Builder{
    
    private $args;
    private $instance;
    private $twitteroauth = NULL;
    private $data;
    
    public function __construct($args, $instance){
        parent::__construct();
        $this->args = $args;
        $this->instance = $instance;
        //$this->twitteroauth = new TwitterOAuth;
        $this->data = $this->get_tweetts();
    }
    
    private function draw_header(){
        echo '<h2>Header</h2>';
    }
    
    private function draw_footer(){
        echo '<h2>Footer</h2>';
    }
    
    private function draw_body(){
        echo '<h2>Body</h2>';
        if(is_null($this->twitteroauth)){
            require_once 'tpl/bad_template.php';
        }
        //print_r($this->data);
    }
    
    public function draw_tweettee() {
        $this->draw_header();
        $this->draw_body();
        $this->draw_footer();
    }
    
    private function get_tweetts(){
        
        $query_string = '';
        
        switch($this->option['widget-content-type']){
            case 1: 
                $this->get_timeline();
                break;
            case 2: 
                $query_string = '';
                break;
            case 3: 
                $this->get_timeline($name);
                break;
            case 4: 
                $this->data = $this->get_search_result($this->get_quest_value());
                break;
        }
        
        
        
        $data = array('twit1', 'twit2');
        return $data;
    }
    
    private function get_timeline($name = FALSE){
        if(!name){
            //
        }else{
            //
        }
    }
    
    private function get_search_result($quest){
        //
    }
    
    private function get_quest_value(){
        switch($this->option['search-content-type']){
            case 1: 
                return ;
            case 2: 
                return ;
            case 3: 
                return ;
            case 4: 
                return $this->clear_str($this->option['tweettee-search-free-word']);
        }
    }
    
    private function ask_twitter($request_string){
        
        $connection = new TwitterOAuth(
            $this->option['consumer_key'], 
            $this->option['consumer_secret'], 
            $this->option['access_token'], 
            $this->option['access_secret']
        );
        
        $this->result = $connection->get($request_string, array('q' => 'f1', 'lang' => 'es'));
    }
    
    private function clear_str($str){
        return trim(strip_tags($str));
    }
}

