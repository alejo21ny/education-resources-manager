<?php

namespace ERM\Core;

if (!defined('ABSPATH')) {
    exit;
}

class Plugin {

    private static $instance = null;

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        $this->load_dependencies();
        $this->init_hooks();
    }

    private function init_hooks() {
        add_action('init', [$this, 'init_plugin']);
    }

    public function init_plugin() {

        (new \ERM\Core\AdminMenu())->register();

    }

    private function load_dependencies(): void
    {
        // Core / DB
        require_once ERM_PLUGIN_PATH . 'includes/Core/AdminMenu.php';
        require_once ERM_PLUGIN_PATH . 'includes/Database/ResourcesTable.php';

        // Admin pages / repository
        require_once ERM_PLUGIN_PATH . 'includes/Admin/ResourcesRepository.php';
        require_once ERM_PLUGIN_PATH . 'includes/Admin/ResourcesPage.php';
        require_once ERM_PLUGIN_PATH . 'includes/Admin/ResourcesAddPage.php';
    }

}