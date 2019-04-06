<?php

namespace Tweettee\Includes;

class Tweettee_Locale{
    
    private $domain;
    
    public function set_domain($d){
        $this->domain = $d;
    }
    
    public function load_plugin_textdomain(){
        load_plugin_textdomain(
                    $this->domain,
                    false,
                    dirname(dirname(plugin_basename(__FILE__))) . '/languages/'
                );
    }
}

