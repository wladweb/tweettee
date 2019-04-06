<?php

namespace Wladweb\Tweettee\AdminPart;

use Wladweb\Tweettee\Includes\Core\I\SettingsInterface;
use Wladweb\Tweettee\Includes\Core\I\AccessInterface;
use Wladweb\Tweettee\Includes\Core\Log\Logger;
use Wladweb\Tweettee\Includes\Core\Exceptions\TweetteeAdminException;

/**
 * Admin part
 */
class TweetteeAdmin
{
    /**
     * Plugin name
     * @var string 
     */
    private $plugin_name;
    
    /**
     * Plugin version
     * @var string 
     */
    private $version;
    
    /**
     * Settings object
     * @var SettingsInterface 
     */
    private $settings;
    
    /**
     * Tiwtter access object
     * @var AccessInterface 
     */
    private $access;
    
    /**
     * Languages collection
     * @var array 
     */
    private $language;
    
    /**
     * Error string
     * @var string 
     */
    private $error_message = '';

    public function __construct(SettingsInterface $settings, AccessInterface $twitter_access, $plugin_name, $version)
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->settings = $settings;
        $this->access = $twitter_access;
        $this->language = $settings->getLanguage();
    }
    
    /**
     * Add plugin scripts
     */
    public function enqueue_scripts()
    {
        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/tweettee_admin.js', array('jquery'), $this->version, false);
    }
    
    /**
     * Add plugin styles
     */
    public function enqueue_styles()
    {
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/tweettee_admin.css', array(), $this->version, 'all');
    }
    
    /**
     * Add settings page
     */
    public function add_settings_page()
    {
        $opt_page = add_options_page('Tweettee Options', 'Tweettee', 'manage_options', 'tweettee', array($this, 'show_settings_page'));

        \add_action('admin_print_scripts-' . $opt_page, [$this, 'enqueue_scripts']);
        \add_action('admin_print_styles-' . $opt_page, [$this, 'enqueue_styles']);
    }
    
    /**
     * Check if verification done or not
     * @return boolean
     */
    protected function canShowStartForm()
    {
        if ($this->access->isVerified()) {
            return false;
        } else {
            return true;
        }
    }
    /**
     * Show settings page
     */
    public function show_settings_page()
    {
        try {
            $this->access->process($this->settings);
        } catch (TweetteeAdminException $e) {
            $this->error_message = Logger::handle($e);
        }
        $options = $this->settings->getOptions();
        require_once dirname(__FILE__) . '/tpl/tweettee_admin_template.php';
    }
}
