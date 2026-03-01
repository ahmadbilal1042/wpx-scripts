<?php
if (!defined('ABSPATH')) exit;

// Add meta boxes
function wpx_add_meta_boxes() {
    $screens = ['post', 'page']; // Add custom post types if needed
    foreach ($screens as $screen) {
        add_meta_box(
            'wpx_custom_code',
            'WPX Custom CSS & JS',
            'wpx_meta_box_callback',
            $screen,
            'normal',
            'high'
        );
    }
}
add_action('add_meta_boxes', 'wpx_add_meta_boxes');

// Meta box HTML
function wpx_meta_box_callback($post) {
    wp_nonce_field('wpx_save_meta_box_data', 'wpx_meta_box_nonce');

    $css = get_post_meta($post->ID, '_wpx_custom_css', true);
    $js = get_post_meta($post->ID, '_wpx_custom_js', true);
    $js_location = get_post_meta($post->ID, '_wpx_js_location', true);
    ?>

    <p>
        <label><strong>Custom CSS:</strong></label><br>
        <textarea style="width:100%;height:150px;" name="wpx_custom_css"><?php echo esc_textarea($css); ?></textarea>
    </p>

    <p>
        <label><strong>Custom JS:</strong></label><br>
        <textarea style="width:100%;height:150px;" name="wpx_custom_js"><?php echo esc_textarea($js); ?></textarea>
    </p>

    <p>
        <label><strong>JS Location:</strong></label><br>
        <select name="wpx_js_location">
            <option value="head" <?php selected($js_location, 'head'); ?>>Head</option>
            <option value="footer" <?php selected($js_location, 'footer'); ?>>Footer</option>
        </select>
    </p>

    <?php
}

// Save meta box data
function wpx_save_meta_box_data($post_id) {
    if (!isset($_POST['wpx_meta_box_nonce'])) return;
    if (!wp_verify_nonce($_POST['wpx_meta_box_nonce'], 'wpx_save_meta_box_data')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

    if (isset($_POST['wpx_custom_css'])) {
        update_post_meta($post_id, '_wpx_custom_css', sanitize_textarea_field($_POST['wpx_custom_css']));
    }

    if (isset($_POST['wpx_custom_js'])) {
        update_post_meta($post_id, '_wpx_custom_js', sanitize_textarea_field($_POST['wpx_custom_js']));
    }

    if (isset($_POST['wpx_js_location'])) {
        update_post_meta($post_id, '_wpx_js_location', sanitize_text_field($_POST['wpx_js_location']));
    }
}
add_action('save_post', 'wpx_save_meta_box_data');