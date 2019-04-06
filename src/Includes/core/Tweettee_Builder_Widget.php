<?php

namespace Tweettee\Includes\Core;


class Tweettee_Builder_Widget extends Tweettee_Builder{
    
    private $args;
    private $instance;
    private $error_message;
    
    public function __construct($args, $instance){
        parent::__construct();
        $this->args = $args;
        $this->instance = $instance;
        $this->error_message = __('Something went wrong. Check plugin settings.', 'tweettee');
    }
    
    private function draw_header(){
        
        !empty($this->instance['title']) ? $title = $this->instance['title'] : $title = $this->args['widget_name'];
        
        $noindex = '';
        
        if ($this->option[$this->prefix.'noindex']){
            $noindex = '<!--noindex-->';
        }
        
        $before_widget = $noindex . $this->args['before_widget'];
        
        printf($before_widget, $this->args['widget_name'], $this->args['widget_name']);
        print $this->args['before_title'] . $title . $this->args['after_title'];
    }
    
    private function draw_footer(){
        
        $end_noindex ='';
        
        if ($this->option[$this->prefix.'noindex']){
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
            $data = $this->get_tweetts();
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
    
    protected static function who(){
        return 'w_';
    }
    
}

