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
        //(new \ERM\Core\PostType())->register();
        //(new \ERM\Core\Taxonomies())->register();
        //(new \ERM\Core\MetaBoxes())->register();

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
        require_once ERM_PLUGIN_PATH . 'includes/Admin/ResourcesDeleteAction.php';
        require_once ERM_PLUGIN_PATH . 'includes/Admin/ResourcesEditPage.php';
        //require_once ERM_PLUGIN_PATH . 'includes/Core/PostType.php';
        //require_once ERM_PLUGIN_PATH . 'includes/Core/Taxonomies.php';
        //require_once ERM_PLUGIN_PATH . 'includes/Core/MetaBoxes.php';
    }

}