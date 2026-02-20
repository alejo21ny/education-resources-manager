<?php

namespace ERM\Admin;

use ERM\Database\ResourcesTable;

if (!defined('ABSPATH')) {
    exit;
}

class ResourcesRepository
{
    public function all(int $limit = 20, int $offset = 0): array
    {
        global $wpdb;
        $table = ResourcesTable::table_name();

        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
        return $wpdb->get_results(
            $wpdb->prepare("SELECT * FROM $table ORDER BY id DESC LIMIT %d OFFSET %d", $limit, $offset),
            ARRAY_A
        );
    }

    public function count_all(): int
    {
        global $wpdb;
        $table = ResourcesTable::table_name();

        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
        return (int) $wpdb->get_var("SELECT COUNT(*) FROM $table");
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

    public function delete(int $id): bool
    {
        global $wpdb;
        $table = ResourcesTable::table_name();

        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
        $result = $wpdb->delete($table, ['id' => $id], ['%d']);

        return $result !== false;
    }

    public function find(int $id): ?array
    {
        global $wpdb;
        $table = ResourcesTable::table_name();

        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
        $row = $wpdb->get_row(
            $wpdb->prepare("SELECT * FROM $table WHERE id = %d", $id),
            ARRAY_A
        );

        return $row ?: null;
    }

    public function update(int $id, array $data): bool
    {
        global $wpdb;
        $table = ResourcesTable::table_name();

        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
        $result = $wpdb->update(
            $table,
            [
                'title' => $data['title'],
                'description' => $data['description'],
                'type' => $data['type'],
            ],
            ['id' => $id],
            ['%s', '%s', '%s'],
            ['%d']
        );

        return $result !== false;
    }

}