<?php

namespace ERM\Core;

if (!defined('ABSPATH')) {
    exit;
}

class PostType
{
    public const POST_TYPE = 'erm_resource';

    public function register(): void
    {
        add_action('init', [$this, 'register_post_type']);
    }

    public function register_post_type(): void
    {
        $labels = [
            'name'                  => 'Educational Resources',
            'singular_name'         => 'Educational Resource',
            'menu_name'             => 'Resources (CPT)',
            'name_admin_bar'        => 'Educational Resource',
            'add_new'               => 'Add New',
            'add_new_item'          => 'Add New Resource',
            'new_item'              => 'New Resource',
            'edit_item'             => 'Edit Resource',
            'view_item'             => 'View Resource',
            'all_items'             => 'All Resources',
            'search_items'          => 'Search Resources',
            'not_found'             => 'No resources found.',
            'not_found_in_trash'    => 'No resources found in Trash.',
        ];

        $args = [
            'labels'             => $labels,
            'public'             => true,
            'show_ui'            => true,
            'show_in_menu'       => true, // lo deja visible en admin
            'menu_icon'          => 'dashicons-welcome-learn-more',
            'supports'           => ['title', 'editor', 'thumbnail'],
            'has_archive'        => true,
            'rewrite'            => ['slug' => 'resources'],
            'show_in_rest'       => true, // clave para REST / Gutenberg
            'capability_type'    => 'post',
        ];

        register_post_type(self::POST_TYPE, $args);
    }
}