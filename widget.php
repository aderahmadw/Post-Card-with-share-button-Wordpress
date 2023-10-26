<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

class My_Custom_Widget extends \Elementor\Widget_Base {

	// Your widget's name, title, icon and category
    public function get_name() {
        return 'my_custom_widget';
    }

    public function get_title() {
        return __( 'My Custom Widget', 'my-custom-widget' );
    }

    public function get_icon() {
        return 'eicon-posts-ticker';
    }

    public function get_categories() {
        return [ 'general' ];
    }




	// Your widget's sidebar settings
    protected function _register_controls() {

    }





	// What your widget displays on the front-end
    protected function render() {
		$settings = $this->get_settings_for_display();

        echo 'Hello World';
    }

}
