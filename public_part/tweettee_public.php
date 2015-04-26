<?php

namespace Tweettee\Public_Part;

use Tweettee\Includes\Core\Tweettee_Builder_Main;
use Tweettee\Includes\Core\Tweettee_Exception;

class Tweettee_Public{
    private $plugin_name;
    private $version;
    private $tweettee_builder_main;
    
    public function __construct($plugin_name, $version){
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }
    
    public function enqueue_scripts(){
        wp_enqueue_script('masonry');
        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/tweettee_public.js', array('jquery'), $this->version, true);
    }
    
    public function enqueue_styles(){
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/tweettee_public.css', array(), $this->version, 'all');
    }
    
    public function tweettee_widget_register(){
        register_widget('Tweettee\Includes\Core\Tweettee_Widget');
    }
    
    public function tweettee_main_block($wp_q){
        if (!is_home()){
            return;
        }
        try{
            $this->tweettee_builder_main = new Tweettee_Builder_Main($wp_q);
        } catch (Tweettee_Exception $tw_e){
            
            $message = $tw_e->getMessage();
            //maybe писать лог
            
            return;
        }
        
        
        if (!$this->tweettee_builder_main->have_main_block()){
            return;
        }
        
        $this->tweettee_builder_main->draw_tweettee();
    }
    
    public function tweettee_main_block_erase() {
        if (!is_home()){
            return;
        }
        $this->tweettee_builder_main->erase_tweettee();
    }
    
}

