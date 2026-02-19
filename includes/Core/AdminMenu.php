<?php

namespace ERM\Core;

if (!defined('ABSPATH')) {
    exit;
}

class AdminMenu
{
    public function register(): void
    {
        add_action('admin_menu', [$this, 'add_menu']);
    }

    public function add_menu(): void
    {
        add_menu_page(
            'Education Resources',
            'Resources',
            'manage_options',
            'erm-resources',
            [$this, 'render_list_page'],
            'dashicons-welcome-learn-more',
            25
        );
    }

    public function render_list_page(): void
    {
        echo '<div class="wrap"><h1>Education Resources</h1><p>Coming soon...</p></div>';
    }
}