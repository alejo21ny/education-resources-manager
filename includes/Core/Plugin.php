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
        $this->init_hooks();
    }

    private function init_hooks() {
        add_action('init', [$this, 'init_plugin']);
    }

    public function init_plugin() {
        // Initialization logic will go here
    }
}