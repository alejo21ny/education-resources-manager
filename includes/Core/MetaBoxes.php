<?php

namespace ERM\Core;

if (!defined('ABSPATH')) {
    exit;
}

class MetaBoxes
{
    // Metas
    private const META_TYPE       = '_erm_type';
    private const META_LEVEL      = '_erm_level';
    private const META_DURATION   = '_erm_duration';
    private const META_URL        = '_erm_url';
    private const META_INSTRUCTOR = '_erm_instructor';
    private const META_PRICE      = '_erm_price';
    private const META_STATUS     = '_erm_status';

    private const NONCE_ACTION = 'erm_resource_details_save';
    private const NONCE_NAME   = 'erm_resource_details_nonce';

    public function register(): void
    {
        add_action('add_meta_boxes', [$this, 'add_meta_boxes']);
        add_action('save_post', [$this, 'save_meta'], 10, 2);
    }

    public function add_meta_boxes(): void
    {
        add_meta_box(
            'erm_resource_details',
            'Resource Details',
            [$this, 'render_box'],
            PostType::POST_TYPE,
            'normal',
            'default'
        );
    }

    public function render_box(\WP_Post $post): void
    {
        wp_nonce_field(self::NONCE_ACTION, self::NONCE_NAME);

        $type       = get_post_meta($post->ID, self::META_TYPE, true);
        $level      = get_post_meta($post->ID, self::META_LEVEL, true);
        $duration   = get_post_meta($post->ID, self::META_DURATION, true);
        $url        = get_post_meta($post->ID, self::META_URL, true);
        $instructor = get_post_meta($post->ID, self::META_INSTRUCTOR, true);
        $price      = get_post_meta($post->ID, self::META_PRICE, true);
        $status     = get_post_meta($post->ID, self::META_STATUS, true);

        // Defaults
        if ($status === '') { $status = 'published'; }
        ?>
        <table class="form-table" role="presentation">
            <tbody>
                <tr>
                    <th><label for="erm_type">Type</label></th>
                    <td>
                        <select name="erm_type" id="erm_type">
                            <?php
                            $types = ['course' => 'Course', 'tutorial' => 'Tutorial', 'ebook' => 'Ebook', 'video' => 'Video'];
                            foreach ($types as $k => $label) {
                                printf(
                                    '<option value="%s" %s>%s</option>',
                                    esc_attr($k),
                                    selected($type, $k, false),
                                    esc_html($label)
                                );
                            }
                            ?>
                        </select>
                    </td>
                </tr>

                <tr>
                    <th><label for="erm_level">Difficulty</label></th>
                    <td>
                        <select name="erm_level" id="erm_level">
                            <?php
                            $levels = ['beginner' => 'Beginner', 'intermediate' => 'Intermediate', 'advanced' => 'Advanced'];
                            foreach ($levels as $k => $label) {
                                printf(
                                    '<option value="%s" %s>%s</option>',
                                    esc_attr($k),
                                    selected($level, $k, false),
                                    esc_html($label)
                                );
                            }
                            ?>
                        </select>
                    </td>
                </tr>

                <tr>
                    <th><label for="erm_duration">Duration (minutes)</label></th>
                    <td>
                        <input type="number" min="0" name="erm_duration" id="erm_duration" value="<?php echo esc_attr($duration); ?>" />
                    </td>
                </tr>

                <tr>
                    <th><label for="erm_url">Resource URL</label></th>
                    <td>
                        <input type="url" class="regular-text" name="erm_url" id="erm_url" value="<?php echo esc_attr($url); ?>" />
                    </td>
                </tr>

                <tr>
                    <th><label for="erm_instructor">Instructor / Author</label></th>
                    <td>
                        <input type="text" class="regular-text" name="erm_instructor" id="erm_instructor" value="<?php echo esc_attr($instructor); ?>" />
                    </td>
                </tr>

                <tr>
                    <th><label for="erm_price">Price (0 = free)</label></th>
                    <td>
                        <input type="number" step="0.01" min="0" name="erm_price" id="erm_price" value="<?php echo esc_attr($price); ?>" />
                    </td>
                </tr>

                <tr>
                    <th><label for="erm_status">Status</label></th>
                    <td>
                        <select name="erm_status" id="erm_status">
                            <?php
                            $statuses = ['draft' => 'Draft', 'published' => 'Published', 'archived' => 'Archived'];
                            foreach ($statuses as $k => $label) {
                                printf(
                                    '<option value="%s" %s>%s</option>',
                                    esc_attr($k),
                                    selected($status, $k, false),
                                    esc_html($label)
                                );
                            }
                            ?>
                        </select>
                    </td>
                </tr>
            </tbody>
        </table>
        <?php
    }

    public function save_meta(int $post_id, \WP_Post $post): void
    {
        // Only our CPT
        if ($post->post_type !== PostType::POST_TYPE) {
            return;
        }

        // Autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        if (wp_is_post_revision($post_id)) {
            return;
        }

        // permissions
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        // Nonce
        if (!isset($_POST[self::NONCE_NAME]) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST[self::NONCE_NAME])), self::NONCE_ACTION)) {
            return;
        }

        // Sanitization and storage
        $type = isset($_POST['erm_type']) ? sanitize_text_field(wp_unslash($_POST['erm_type'])) : '';
        $level = isset($_POST['erm_level']) ? sanitize_text_field(wp_unslash($_POST['erm_level'])) : '';
        $duration = isset($_POST['erm_duration']) ? absint($_POST['erm_duration']) : 0;
        $url = isset($_POST['erm_url']) ? esc_url_raw(wp_unslash($_POST['erm_url'])) : '';
        $instructor = isset($_POST['erm_instructor']) ? sanitize_text_field(wp_unslash($_POST['erm_instructor'])) : '';
        $price = isset($_POST['erm_price']) ? (string) floatval($_POST['erm_price']) : '0';
        $status = isset($_POST['erm_status']) ? sanitize_text_field(wp_unslash($_POST['erm_status'])) : 'published';

        update_post_meta($post_id, self::META_TYPE, $type);
        update_post_meta($post_id, self::META_LEVEL, $level);
        update_post_meta($post_id, self::META_DURATION, $duration);
        update_post_meta($post_id, self::META_URL, $url);
        update_post_meta($post_id, self::META_INSTRUCTOR, $instructor);
        update_post_meta($post_id, self::META_PRICE, $price);
        update_post_meta($post_id, self::META_STATUS, $status);
    }
}