<?php

namespace Tweettee\Includes;
use Tweettee\Includes\Tweettee_Loader;
use Tweettee\Includes\Tweettee_Locale;
use Tweettee\Public_Part\Tweettee_Public;
use Tweettee\Admin_Part\Tweettee_Admin;



class Tweettee{
    protected $version;
    protected $plugin_name;
    protected $loader;
    
    public function __construct(){
        $this->plugin_name = 'tweettee';
        $this->version = '1.0.0';
        
        $this->load_depend();
        $this->set_locale();
        $this->admin_hooks();
        $this->public_hooks();
    }
    
    private function load_depend(){
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/tweettee_loader.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'public_part/tweettee_public.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin_part/tweettee_admin.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/tweettee_locale.php';
        $this->loader = new Tweettee_Loader;
    }
    
    private function set_locale(){
        $plugin_locale = new Tweettee_Locale;
        $plugin_locale->set_domain($this->get_plugin_name());
        $this->loader->add_action('plugins_loaded', $plugin_locale, 'load_plugin_textdomain');
    }
    
    private function admin_hooks(){
        $plugin_admin = new Tweettee_Admin($this->get_plugin_name(), $this->get_version());
        
        $this->loader->add_action('admin_menu', $plugin_admin, 'add_settings_page');
    }
    
    private function public_hooks(){
        $plugin_public = new Tweettee_Public($this->get_plugin_name(), $this->get_version());
        
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
        $this->loader->add_action('widgets_init', $plugin_public, 'tweettee_widget_register');
        $this->loader->add_action('loop_start', $plugin_public, 'tweettee_main_block');
        $this->loader->add_action('loop_end', $plugin_public, 'tweettee_main_block_erase');
    }
    
    public function plugin_start(){
        $this->loader->load_start();
    }
    
    private function get_plugin_name(){
        return $this->plugin_name;
    }
    
    private function get_version(){
        return $this->version;
    }
}

