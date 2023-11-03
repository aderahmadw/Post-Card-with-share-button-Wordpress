<?php
/**
 * Plugin Name: Test Plugin - Elementor
 * Description: A custom Elementor widget that displays "Hello World"
 * Version: 1.0
 * Author: Rahmad
 * Author URI: https://www.google.com
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function register_custom_widget() {
    require_once plugin_dir_path( __FILE__ ) . 'widget.php';

    \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new C_Post_List() );
}
add_action( 'elementor/widgets/widgets_registered', 'register_custom_widget' );

function enqueue_plugin_assets() {
    wp_enqueue_style('plugin-styles', plugin_dir_url(__FILE__) . 'cr-plugin/view/cr-style.css');
    wp_enqueue_script('plugin-script', plugin_dir_url(__FILE__) . 'cr-plugin/js/jqueryfiltering.js', array('jquery'), null, true);
}

add_action('wp_enqueue_scripts', 'enqueue_plugin_assets');
