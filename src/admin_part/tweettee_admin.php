<?php

namespace Tweettee\Admin_Part;

use Tweettee\Includes\Core\Tweettee_Settings;

class Tweettee_Admin{
    private $plugin_name;
    private $version;
    
    private $opt_page = '';
    
    public function __construct($plugin_name, $version){
        session_start();
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }
    
    public function enqueue_scripts(){
        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/tweettee_admin.js', array('jquery'), $this->version, false);
    }
    
    public function enqueue_styles(){
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/tweettee_admin.css', array(), $this->version, 'all');
    }
    
    public function add_settings_page(){
        $this->opt_page = add_options_page('Tweettee Options', 'Tweettee', 'manage_options', __FILE__, array($this, 'show_settings_page'));
        add_action('admin_print_scripts-' . $this->opt_page, array($this, 'enqueue_scripts'));
        add_action('admin_print_styles-' . $this->opt_page, array($this, 'enqueue_styles'));
    }
    
    public function show_settings_page(){
        
        $settings = new Tweettee_Settings;
        $settings->draw_settings_page(); 
        
    }
}

