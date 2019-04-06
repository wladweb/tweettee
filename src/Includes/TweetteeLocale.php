<?php

namespace Wladweb\Tweettee\Includes;

class TweetteeLocale
{
    private $plugin_dir_path;
    private $domain;
    
    public function __construct($path)
    {
        $this->plugin_dir_path = $path;
    }

    public function set_domain($d)
    {
        $this->domain = $d;
    }

    public function load_plugin_textdomain()
    {
        load_plugin_textdomain(
                $this->domain,
                false,
                $this->plugin_dir_path . 'languages/'
        );
    }

}
