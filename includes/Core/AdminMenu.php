<?php

namespace ERM\Core;

use ERM\Admin\ResourcesPage;
use ERM\Admin\ResourcesAddPage;
use ERM\Admin\ResourcesDeleteAction;

if (!defined('ABSPATH')) {
    exit;
}

class AdminMenu
{
    public function register(): void
    {
        add_action('admin_menu', [$this, 'add_menu']);
        add_action('admin_init', [$this, 'register_actions']);
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

        add_submenu_page(
            'erm-resources',
            'Add New',
            'Add New',
            'manage_options',
            'erm-resources-add',
            [$this, 'render_add_page']
        );
    }

    public function register_actions(): void
    {
        (new ResourcesAddPage())->register_actions();
        (new ResourcesDeleteAction())->register();
    }

    public function render_list_page(): void
    {
        (new ResourcesPage())->render();
    }

    public function render_add_page(): void
    {
        (new ResourcesAddPage())->render();
    }

}