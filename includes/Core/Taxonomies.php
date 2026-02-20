<?php

namespace ERM\Core;

if (!defined('ABSPATH')) {
    exit;
}

class Taxonomies
{
    public const TAX_CATEGORY = 'erm_resource_category';
    public const TAX_SKILLS   = 'erm_skill_tag';

    public function register(): void
    {
        add_action('init', [$this, 'register_taxonomies']);
    }

    public function register_taxonomies(): void
    {
        // Categories
        register_taxonomy(
            self::TAX_CATEGORY,
            [PostType::POST_TYPE],
            [
                'label'        => 'Resource Categories',
                'hierarchical' => true,
                'show_ui'      => true,
                'show_in_rest' => true,
                'rewrite'      => ['slug' => 'resource-category'],
            ]
        );

        // Skill tags
        register_taxonomy(
            self::TAX_SKILLS,
            [PostType::POST_TYPE],
            [
                'label'        => 'Skill Tags',
                'hierarchical' => false,
                'show_ui'      => true,
                'show_in_rest' => true,
                'rewrite'      => ['slug' => 'skill-tag'],
            ]
        );
    }
}