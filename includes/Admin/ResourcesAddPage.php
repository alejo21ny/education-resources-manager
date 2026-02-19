<?php

namespace ERM\Admin;

if (!defined('ABSPATH')) {
    exit;
}

class ResourcesAddPage
{
    public function register_actions(): void
    {
        add_action('admin_post_erm_resource_create', [$this, 'handle_create']);
    }

    public function render(): void
    {
        echo '<div class="wrap">';
        echo '<h1>Add New Resource</h1>';

        if (isset($_GET['error']) && $_GET['error'] === '1') {
            echo '<div class="notice notice-error is-dismissible"><p>Please fill in all fields.</p></div>';
        }

        echo '<form method="post" action="' . esc_url(admin_url('admin-post.php')) . '">';
        echo '<input type="hidden" name="action" value="erm_resource_create">';
        wp_nonce_field('erm_resource_create');

        echo '<table class="form-table" role="presentation"><tbody>';

        echo '<tr><th><label for="title">Title</label></th><td>';
        echo '<input name="title" id="title" type="text" class="regular-text" required>';
        echo '</td></tr>';

        echo '<tr><th><label for="type">Type</label></th><td>';
        echo '<input name="type" id="type" type="text" class="regular-text" required>';
        echo '</td></tr>';

        echo '<tr><th><label for="description">Description</label></th><td>';
        echo '<textarea name="description" id="description" class="large-text" rows="5" required></textarea>';
        echo '</td></tr>';

        echo '</tbody></table>';

        submit_button('Create Resource');

        echo '</form>';
        echo '</div>';
    }

    public function handle_create(): void
    {
        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized', 403);
        }

        check_admin_referer('erm_resource_create');

        $title = isset($_POST['title']) ? sanitize_text_field(wp_unslash($_POST['title'])) : '';
        $type  = isset($_POST['type']) ? sanitize_text_field(wp_unslash($_POST['type'])) : '';
        $desc  = isset($_POST['description']) ? sanitize_textarea_field(wp_unslash($_POST['description'])) : '';

        if ($title === '' || $type === '' || $desc === '') {
            wp_safe_redirect(add_query_arg(['page' => 'erm-resources-add', 'error' => '1'], admin_url('admin.php')));
            exit;
        }

        $repo = new ResourcesRepository();
        $repo->insert([
            'title' => $title,
            'type' => $type,
            'description' => $desc,
        ]);

        wp_safe_redirect(add_query_arg(['page' => 'erm-resources', 'created' => '1'], admin_url('admin.php')));
        exit;

    }
}