<?php

namespace ERM\Admin;

if (!defined('ABSPATH')) {
    exit;
}

class ResourcesDeleteAction
{
    public const ACTION = 'erm_resource_delete';

    public function register(): void
    {
        add_action('admin_post_' . self::ACTION, [$this, 'handle']);
    }

    public function handle(): void
    {
        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized', 403);
        }

        $id = isset($_GET['id']) ? absint($_GET['id']) : 0;

        if (!$id) {
            wp_safe_redirect(admin_url('admin.php?page=erm-resources&error=1'));
            exit;
        }

        check_admin_referer('erm_resource_delete_' . $id);

        $repo = new ResourcesRepository();
        $repo->delete($id);

        wp_safe_redirect(admin_url('admin.php?page=erm-resources&deleted=1'));
        exit;
    }
}