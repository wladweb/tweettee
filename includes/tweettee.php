<?php

namespace Tweettee\Includes;
use Tweettee\Includes\Tweettee_Loader;
use Tweettee\Includes\Tweettee_Locale;

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
        require_once plugin_dir_path(dirname(__FILE__)) . 'public/tweettee_public.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/tweettee_admin.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/tweettee_locale.php';
        $this->loader = new Tweettee_Loader;
    }
    
    private function set_locale(){
        $plugin_locale = new Tweettee_Locale;
    }
    
    private function public_hooks(){
        $plugin_admin = new Tweette_Admin($this->get_plugin_name(), $this->get_version());
    }
    
    private function admin_hooks(){
        $plugin_public = new Tweettee_Public($this->get_plugin_name(), $this->get_version());
    }
    
    public function plugin_start(){
        //
    }
    
    private function get_plugin_name(){
        return $this->plugin_name;
    }
    
    private function get_version(){
        return $this->version;
    }
}

