<?php

namespace Tweettee\Includes\Core;

class Tweettee_Widget extends \WP_Widget{
    
    private $tweettee_builder_widget;
    
    public function __construct(){
        parent::__construct('tweettee_plugin', 'Tweettee', array('description' => __('Displays a list of tweets in accordance with the settings', 'tweettee')));
    }
    
    public function widget($args, $instance){ 
        
        $this->tweettee_builder_widget = new Tweettee_Builder_Widget($args, $instance);
        $this->tweettee_builder_widget->draw_tweettee();
    }
    
    public function update($new_instance, $old_instance){
        $instance = array();
        $instance['title'] = strip_tags($new_instance['title']);
        return $instance;
    }
    
    public function form($instance){
        print '<label>' .  __('Title', 'tweettee') . '</label> '
                . '<input type="text" id="'.$this->get_field_id('title').'" name="'.$this->get_field_name('title').'" value="'. $instance['title'] .'">';
    }
}

