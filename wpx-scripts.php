<?php
/*
Plugin Name: WPX Scripts
Description: Add per-page/custom post type CSS & JS with location control (head, body, footer)
Version: 1.0
Author: Ahmad Bilal
*/

if (!defined('ABSPATH')) exit;

// Include admin meta boxes
require_once plugin_dir_path(__FILE__) . 'admin/meta-boxes.php';

// Inject CSS & JS in head
function wpx_inject_head_code() {
    if (!is_singular()) return;

    global $post;
    $css = get_post_meta($post->ID, '_wpx_custom_css', true);
    $js = get_post_meta($post->ID, '_wpx_custom_js', true);
    $js_location = get_post_meta($post->ID, '_wpx_js_location', true);

    if ($css) {
        echo "<style>{$css}</style>";
    }

    if ($js && $js_location === 'head') {
        echo "<script>{$js}</script>";
    }
}
add_action('wp_head', 'wpx_inject_head_code');

// Inject JS in footer
function wpx_inject_footer_js() {
    if (!is_singular()) return;

    global $post;
    $js = get_post_meta($post->ID, '_wpx_custom_js', true);
    $js_location = get_post_meta($post->ID, '_wpx_js_location', true);

    if ($js && $js_location === 'footer') {
        echo "<script>{$js}</script>";
    }
}
add_action('wp_footer', 'wpx_inject_footer_js');