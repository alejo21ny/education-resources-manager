<?php

namespace ERM\Admin;

if (!defined('ABSPATH')) {
    exit;
}

class ResourcesEditPage
{
    public function register_actions(): void
    {
        add_action('admin_post_erm_resource_update', [$this, 'handle_update']);
    }

    public function render(): void
    {
        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized', 403);
        }

        $id = isset($_GET['id']) ? absint($_GET['id']) : 0;
        if ($id <= 0) {
            wp_safe_redirect(admin_url('admin.php?page=erm-resources&error=1'));
            exit;
        }

        $repo = new ResourcesRepository();
        $resource = $repo->find($id);

        if (!$resource) {
            wp_safe_redirect(admin_url('admin.php?page=erm-resources&error=1'));
            exit;
        }

        echo '<div class="wrap">';
        echo '<h1>Edit Resource</h1>';

        if (isset($_GET['error']) && $_GET['error'] === '1') {
            echo '<div class="notice notice-error is-dismissible"><p>Please fill in all fields.</p></div>';
        }

        echo '<form method="post" action="' . esc_url(admin_url('admin-post.php')) . '">';
        echo '<input type="hidden" name="action" value="erm_resource_update">';
        echo '<input type="hidden" name="id" value="' . esc_attr((string) $id) . '">';

        wp_nonce_field('erm_resource_update_' . $id, 'erm_nonce');

        echo '<table class="form-table" role="presentation"><tbody>';

        echo '<tr><th><label for="title">Title</label></th><td>';
        echo '<input name="title" id="title" type="text" class="regular-text" required value="' . esc_attr($resource['title']) . '">';
        echo '</td></tr>';

        echo '<tr><th><label for="type">Type</label></th><td>';
        echo '<input name="type" id="type" type="text" class="regular-text" required value="' . esc_attr($resource['type']) . '">';
        echo '</td></tr>';

        echo '<tr><th><label for="description">Description</label></th><td>';
        echo '<textarea name="description" id="description" class="large-text" rows="5" required>' . esc_textarea($resource['description']) . '</textarea>';
        echo '</td></tr>';

        echo '</tbody></table>';

        submit_button('Update Resource');

        echo '</form>';
        echo '</div>';
    }

    public function handle_update(): void
    {
        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized', 403);
        }

        $id = isset($_POST['id']) ? absint($_POST['id']) : 0;
        if ($id <= 0) {
            wp_safe_redirect(admin_url('admin.php?page=erm-resources&error=1'));
            exit;
        }

        check_admin_referer('erm_resource_update_' . $id, 'erm_nonce');

        $title = isset($_POST['title']) ? sanitize_text_field(wp_unslash($_POST['title'])) : '';
        $type  = isset($_POST['type']) ? sanitize_text_field(wp_unslash($_POST['type'])) : '';
        $desc  = isset($_POST['description']) ? sanitize_textarea_field(wp_unslash($_POST['description'])) : '';

        if ($title === '' || $type === '' || $desc === '') {
            wp_safe_redirect(admin_url('admin.php?page=erm-resources-edit&id=' . $id . '&error=1'));
            exit;
        }

        $repo = new ResourcesRepository();
        $ok = $repo->update($id, [
            'title' => $title,
            'type' => $type,
            'description' => $desc,
        ]);

        if (!$ok) {
            wp_safe_redirect(admin_url('admin.php?page=erm-resources-edit&id=' . $id . '&error=1'));
            exit;
        }

        wp_safe_redirect(admin_url('admin.php?page=erm-resources&updated=1'));
        exit;
    }
}