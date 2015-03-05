<?php

/**
 * Plugin Name:       Tweettee
 * Plugin URI:        
 * Description:       This plugin can create widget with twits from timeline your twitter account or search result with different keywords
 * Version:           1.0.0
 * Author:            Vladimir Petrov
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       plugin-name
 * Domain Path:       /languages
 */

namespace Tweettee;
use Tweettee\Includes as Inc;

if (! defined('WPINC')){
	die;
}

require_once 'autoload.php';

$activate_tweettee = function(){
    require_once plugin_dir_path(__FILE__) . 'includes/tweettee_activator.php';
    Inc\Tweettee_Activator::activate();
};

$deactivate_tweettee = function(){
    require_once plugin_dir_path(__FILE__) . 'includes/tweettee_deactivator.php';
    Inc\Tweettee_Deactivator::deactivate();
};

register_activation_hook(__FILE__, $activate_tweettee);
register_deactivation_hook(__FILE__, $deactivate_tweettee);

require_once plugin_dir_path(__FILE__) . 'includes/tweettee.php';

$tweettee = new Inc\Tweettee;
$tweettee->plugin_start(); 