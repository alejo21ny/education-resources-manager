<?php

namespace ERM\Admin;

if (!defined('ABSPATH')) {
    exit;
}

class ResourcesPage
{
    public function render(): void
    {
        $repo = new ResourcesRepository();
        $resources = $repo->all();

        echo '<div class="wrap">';
        echo '<h1 class="wp-heading-inline">Education Resources</h1>';
        echo ' <a href="' . esc_url(admin_url('admin.php?page=erm-resources-add')) . '" class="page-title-action">Add New</a>';
        echo '<hr class="wp-header-end">';

        if (empty($resources)) {
            echo '<p>No resources found.</p>';
            echo '</div>';
            return;
        }

        echo '<table class="widefat fixed striped">';
        echo '<thead><tr>';
        echo '<th>ID</th><th>Title</th><th>Type</th><th>Created</th>';
        echo '</tr></thead><tbody>';

        foreach ($resources as $r) {
            echo '<tr>';
            echo '<td>' . esc_html($r['id']) . '</td>';
            echo '<td>' . esc_html($r['title']) . '</td>';
            echo '<td>' . esc_html($r['type']) . '</td>';
            echo '<td>' . esc_html($r['created_at']) . '</td>';
            echo '</tr>';
        }

        echo '</tbody></table>';
        echo '</div>';
    }
}