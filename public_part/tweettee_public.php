<?php

namespace Tweettee\Public_Part;

class Tweettee_Public{
    private $plugin_name;
    private $version;
    
    public function __construct($plugin_name, $version){
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }
    
    public function enqueue_scripts(){
        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/tweettee_public.js', array('jquery'), $this->version, false);
    }
    
    public function enqueue_styles(){
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/tweettee_public.css', array(), $this->version, 'all');
    }
    
    public function tweettee_widget_register(){
        register_widget('Tweettee\Includes\Core\Tweettee_Widget');
    }
}

