<?php

/**
 * Plugin Name:       Tweettee
 * Plugin URI:        
 * Description:       Plugin show user timeline, home timeline, mentions, home timeline another twitter users, search result by post tags, category name, free word. Tweets can be show in widget and also between posts in main loop on home page.
 * Version:           1.1.0
 * Author:            Vladimir Petrov
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       tweettee
 * Domain Path:       /languages
 */

if (!defined('WPINC')) {
    die;
}

require_once 'vendor/autoload.php';

register_activation_hook(__FILE__, ['\Wladweb\Tweettee\Includes\TweetteeActivator', 'activate']);
register_deactivation_hook(__FILE__, ['\Wladweb\Tweettee\Includes\TweetteeDeactivator', 'deactivate']);

$tweettee = new Wladweb\Tweettee\Includes\Core\TweetteeApp;
$tweettee->plugin_start();
