<?php

namespace ERM\Admin;

if (!defined('ABSPATH')) {
    exit;
}

class ResourcesPage
{
    public function render(): void
    {
        $per_page = 20;
        $paged = isset($_GET['paged']) ? max(1, (int) $_GET['paged']) : 1;
        $offset = ($paged - 1) * $per_page;

        $repo = new ResourcesRepository();
        $total = $repo->count_all();
        $resources = $repo->all($per_page, $offset);
        $total_pages = (int) ceil($total / $per_page);

        echo '<div class="wrap">';
        echo '<h1 class="wp-heading-inline">Education Resources</h1>';
        echo ' <a href="' . esc_url(admin_url('admin.php?page=erm-resources-add')) . '" class="page-title-action">Add New</a>';
        echo '<hr class="wp-header-end">';

        if (isset($_GET['created']) && $_GET['created'] === '1') {
            echo '<div class="notice notice-success is-dismissible"><p>Resource created successfully.</p></div>';
        }

        if (isset($_GET['error']) && $_GET['error'] === '1') {
            echo '<div class="notice notice-error is-dismissible"><p>Please fill in all fields.</p></div>';
        }

        if (isset($_GET['deleted']) && $_GET['deleted'] === '1') {
            echo '<div class="notice notice-success is-dismissible"><p>Resource deleted successfully.</p></div>';
        }

        if (isset($_GET['updated']) && $_GET['updated'] === '1') {
            echo '<div class="notice notice-success is-dismissible"><p>Resource updated successfully.</p></div>';
        }

        if (empty($resources)) {
            echo '<p>No resources found.</p>';
            echo '</div>';
            return;
        }

        echo '<table class="widefat fixed striped">';
        echo '<thead><tr>';
        echo '<th>ID</th><th>Title</th><th>Type</th><th>Created</th><th>Actions</th>';
        echo '</tr></thead><tbody>';

        foreach ($resources as $r) {
            echo '<tr>';
            echo '<td>' . esc_html($r['id']) . '</td>';
            echo '<td>' . esc_html($r['title']) . '</td>';
            echo '<td>' . esc_html($r['type']) . '</td>';
            echo '<td>' . esc_html($r['created_at']) . '</td>';

            $edit_url = admin_url('admin.php?page=erm-resources-edit&id=' . (int) $r['id']);

            $delete_url = wp_nonce_url(
                admin_url('admin-post.php?action=erm_resource_delete&id=' . (int) $r['id']),
                'erm_resource_delete_' . (int) $r['id']
            );

            echo '<td>';
            echo '<a href="' . esc_url($edit_url) . '">Edit</a> | ';
            echo '<a href="' . esc_url($delete_url) . '" onclick="return confirm(\'Delete this resource?\')">Delete</a>';
            echo '</td>';
            echo '</tr>';
        }

        echo '</tbody></table>';

        if ($total_pages > 1) {
            echo '<div class="tablenav"><div class="tablenav-pages">';
            echo paginate_links([
                'base'      => add_query_arg('paged', '%#%'),
                'format'    => '',
                'prev_text' => '«',
                'next_text' => '»',
                'total'     => $total_pages,
                'current'   => $paged,
            ]);
            echo '</div></div>';
        }

        echo '</div>';
    }
}