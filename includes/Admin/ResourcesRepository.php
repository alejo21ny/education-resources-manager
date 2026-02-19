<?php

namespace ERM\Admin;

use ERM\Database\ResourcesTable;

if (!defined('ABSPATH')) {
    exit;
}

class ResourcesRepository
{
    public function all(int $limit = 50): array
    {
        global $wpdb;
        $table = ResourcesTable::table_name();

        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
        return $wpdb->get_results(
            $wpdb->prepare("SELECT * FROM $table ORDER BY id DESC LIMIT %d", $limit),
            ARRAY_A
        );
    }

    public function insert(array $data): int
    {
        global $wpdb;
        $table = ResourcesTable::table_name();

        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
        $wpdb->insert($table, [
            'title' => $data['title'],
            'description' => $data['description'],
            'type' => $data['type'],
        ], ['%s', '%s', '%s']);

        return (int) $wpdb->insert_id;
    }
}