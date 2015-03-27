<?php

namespace Tweettee\Includes\Core;


class Tweettee_Builder_Widget extends Tweettee_Builder{
    
    private $args;
    private $instance;
    private $error_message = 'Something went wrong. Check plugin settings.';
    
    public function __construct($args, $instance){
        parent::__construct();
        $this->args = $args;
        $this->instance = $instance;
    }
    
    private function draw_header(){
        
        !empty($this->instance['title']) ? $title = $this->instance['title'] : $title = $this->args['widget_name'];
        
        $noindex = '';
        
        if ($this->option['noindex']){
            $noindex = '<!--noindex-->';
        }
        
        $before_widget = $noindex . $this->args['before_widget'];
        
        printf($before_widget, $this->args['widget_name'], $this->args['widget_name']);
        print $this->args['before_title'] . $title . $this->args['after_title'];
    }
    
    private function draw_footer(){
        
        $end_noindex ='';
        
        if ($this->option['noindex']){
            $end_noindex = '<!--/noindex-->';
        }
        
        print $this->args['after_widget'] . $end_noindex;
    }
    
    private function draw_body(){
        
        if(is_null($this->twitteroauth)){
            require_once 'tpl/bad_template.php';
            return;
        }
        
        try{
            $data = $this->get_tweetts($this->option['widget-content-type']);
        }  catch (Tweettee_Exception $e){
            $this->error_message = $e->getMessage();
            require_once 'tpl/bad_template.php';
            return;
        }
        
        require_once 'tpl/good_template.php';
    }
    
    public function draw_tweettee() {
        $this->draw_header();
        $this->draw_body();
        $this->draw_footer();
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
}

