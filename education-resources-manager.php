<?php
/**
 * Plugin Name: Education Resources Manager
 * Plugin URI: https://github.com/alejo21ny/education-resources-manager
 * Description: Educational resource management system.
 * Version: 1.0.0
 * Author: David Gomez
 * License: GPL2+
 */

if (!defined('ABSPATH')) {
    exit;
}

require_once plugin_dir_path(__FILE__) . 'includes/Core/Plugin.php';

require_once plugin_dir_path(__FILE__) . 'includes/Core/Activator.php';

register_activation_hook(__FILE__, ['ERM\Core\Activator', 'activate']);

require_once plugin_dir_path(__FILE__) . 'includes/Core/AdminMenu.php';

ERM\Core\Plugin::get_instance();