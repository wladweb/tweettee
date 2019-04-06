<?php

namespace Wladweb\Tweettee\Includes\Core;

use Wladweb\Tweettee\Includes\TweetteeLoader;
use Wladweb\Tweettee\Includes\TweetteeLocale;
use Wladweb\Tweettee\PublicPart\TweetteePublic;
use Wladweb\Tweettee\AdminPart\TweetteeAdmin;
use Wladweb\Tweettee\Includes\Core\TweetteeSettings;
use Wladweb\Tweettee\Includes\Core\TwitterAccess;
use Wladweb\Tweettee\Includes\TweetteeSession;
use Wladweb\Tweettee\Includes\Core\Builders\TweetteeBuilderMain;
use Wladweb\Tweettee\Includes\Core\Builders\TweetteeBuilderWidget;
use Wladweb\Tweettee\Includes\Core\TweetteeCache;

class TweetteeApp
{

    protected static $plugin_name = 'tweettee';
    protected static $version = '1.1.0';
    protected $loader;
    protected $twitter_access;
    protected $session;
    protected $builder_main;
    public static $plugin_dir_path;
    public static $builder_widget;
    public static $tweettee_settings;
    public static $cache;

    public function __construct()
    {
        self::$plugin_dir_path = dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR;
        
        $this->loader = new TweetteeLoader;
        self::$tweettee_settings = new TweetteeSettings;
        //self::$cache = TweetteeCache::getCache(self::$tweettee_settings);
        $this->session = new TweetteeSession;
        $this->twitter_access = new TwitterAccess($this->session);
        $this->builder_main = new TweetteeBuilderMain;
        self::$builder_widget = new TweetteeBuilderWidget;
        
        $this->set_locale();
        $this->admin_hooks();
        $this->public_hooks();
    }

    private function set_locale()
    {
        $plugin_locale = new TweetteeLocale(self::$plugin_dir_path);
        $plugin_locale->set_domain(self::get_plugin_name());
        $this->loader->add_action('plugins_loaded', $plugin_locale, 'load_plugin_textdomain');
    }

    private function admin_hooks()
    {
        $plugin_admin = new TweetteeAdmin(self::$tweettee_settings, $this->twitter_access, self::get_plugin_name(), self::get_version());

        $this->loader->add_action('init', $this->session, 'delSessionCookie');
        $this->loader->add_action('admin_menu', $plugin_admin, 'add_settings_page');
    }

    private function public_hooks()
    {
        $plugin_public = new TweetteePublic($this->builder_main, self::get_plugin_name(), self::get_version());

        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
        $this->loader->add_action('widgets_init', $plugin_public, 'tweettee_widget_register');
        $this->loader->add_action('loop_start', $plugin_public, 'tweettee_main_block');
    }

    public function plugin_start()
    {
        $this->loader->load_start();
    }

    public static function get_plugin_name()
    {
        return self::$plugin_name;
    }

    public static function get_version()
    {
        return self::$version;
    }

}
