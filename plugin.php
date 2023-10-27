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
    require_once plugin_dir_path( __FILE__ ) . 'widget2.php';

    \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new C_Post_List() );
    \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new C_Post_List_Related() );
}
add_action( 'elementor/widgets/widgets_registered', 'register_custom_widget' );
