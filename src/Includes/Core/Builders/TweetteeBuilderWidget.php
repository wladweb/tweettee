<?php

namespace Wladweb\Tweettee\Includes\Core\Builders;

use Wladweb\Tweettee\Includes\Core\Exceptions\TweetteePublicException;
use Wladweb\Tweettee\Includes\Core\Log\Logger;

/**
 * Buid Tweettee widget
 */
class TweetteeBuilderWidget extends TweetteeBuilder
{
    const PREFIX = 'w_';

    private $args;
    private $instance;

    public function __construct()
    {
        parent::__construct();
    }

    public function setArgs($args, $instance)
    {
        $this->args = $args;
        $this->instance = $instance;
    }
    
    /**
     * Draw widget header
     */
    private function draw_header()
    {
        !empty($this->instance['title']) ? $title = $this->instance['title'] : $title = $this->args['widget_name'];

        $noindex = '';

        if ($this->options[$this->prefix . 'noindex']) {
            $noindex = '<!--noindex-->';
        }

        $before_widget = $noindex . $this->args['before_widget'];

        echo $before_widget, $this->args['before_title'] . $title . $this->args['after_title'];
    }
    
    /**
     * Draw widget footer
     */
    private function draw_footer()
    {
        $end_noindex = '';

        if ($this->options[$this->prefix . 'noindex']) {
            $end_noindex = '<!--/noindex-->';
        }

        print $this->args['after_widget'] . $end_noindex;
    }
    
    /**
     * Request twits & draw widget body
     */
    private function draw_body()
    {
        try {
            $data = $this->get_tweetts();
        } catch (TweetteePublicException $e) {
            Logger::handle($e);
            require_once 'tpl/bad_template.php';
            return;
        }

        require_once 'tpl/good_template.php';
    }
    
    /**
     * Start drawing widget
     */
    public function draw_tweettee()
    {
        echo "\r\n<!-- Begin $this->plugin_name plugin widget, version: $this->version -->\r\n";
        $this->draw_header();
        $this->draw_body();
        $this->draw_footer();
        echo "\r\n<!-- End $this->plugin_name plugin widget -->\r\n";
    }
    
    /**
     * Return prefix for widget option pack
     * @return string
     */
    protected static function who()
    {
        return self::PREFIX;
    }

}
