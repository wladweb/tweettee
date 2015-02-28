<?php

namespace Tweettee\AdminPart;

class Tweettee_Admin{
    private $plugin_name;
    private $version;
    
    public function __construct($plugin_name, $version){
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }
    
    public function enqueue_scripts(){
        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/tweettee_admin.js', array('jquery'), $this->version, false);
    }
    
    public function enqueue_styles(){
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/tweettee_admin.css', array(), $this->version, 'all');
    }
}

