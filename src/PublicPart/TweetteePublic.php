<?php

namespace Wladweb\Tweettee\PublicPart;

use Wladweb\Tweettee\Includes\Core\Builders\TweetteeBuilderMain;

class TweetteePublic
{

    private $plugin_name;
    private $version;
    private $tweettee_builder_main;

    public function __construct(TweetteeBuilderMain $builder_main, $plugin_name, $version)
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->tweettee_builder_main = $builder_main;
    }

    public function enqueue_scripts()
    {
        \wp_enqueue_script('jquery-masonry');
        \wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/tweettee_public.js', array('jquery'), $this->version, true);
    }

    public function enqueue_styles()
    {
        \wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/tweettee_public.css', array(), $this->version, 'all');
    }

    public function tweettee_widget_register()
    {
        \register_widget('Wladweb\Tweettee\Includes\Core\TweetteeWidget');
    }

    public function tweettee_main_block($query)
    {
        if (!is_home() || !$this->tweettee_builder_main->have_main_block() || !$query->is_main_query()) {
            return;
        }
        
        $this->tweettee_builder_main->prepare();
        
        add_action( 'the_post', [$this->tweettee_builder_main, 'draw_tweettee']);
        add_action( 'loop_end', [$this->tweettee_builder_main, 'erase_tweettee']);
    }
}
