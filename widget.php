<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class C_Post_List extends \Elementor\Widget_Base
{

    // Your widget's name, title, icon and category
    public function get_name()
    {
        return 'C_Post_List';
    }

    public function get_title()
    {
        return esc_html__('C Post List', 'c-post-list');
    }

    public function get_icon()
    {
        return 'eicon-hypster';
    }

    public function get_categories()
    {
        return ['basic'];
    }

    protected function get_categories_for_select()
    {
        $categories = get_categories();

        $options = ['' => __('All', 'c-post-list')]; // Include an option to display all posts

        foreach ($categories as $category) {
            $options[$category->term_id] = $category->name;
        }

        return $options;
    }


    // Your widget's sidebar settings
    protected function _register_controls()
    {
        // Start a controls section
        $this->start_controls_section(
            'section_content',
            [
                'label' => __('Content', 'c-post-list'),
            ]
        );

        // Add a control for the number of posts to display
        $this->add_control(
            'posts_per_page',
            [
                'label' => __('Mau nampilin berapa post?', 'c-post-list'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 10,
                // Set a default number of posts per page
            ]
        );
        // Add control displaying filter or not
        $this->add_control(
            'enable_filtering',
            [
                'label' => __('Mau pake filter kah?', 'c-post-list'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'c-post-list'),
                'label_off' => __('No', 'c-post-list'),
                'default' => 'yes',
                // Enable filtering by default
            ]
        );
        // Add control to filter the categories
        $this->add_control(
            'selected_categories',
            [
                'label' => __('MILIH KATEGORI', 'c-post-list'),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'options' => $this->get_categories_for_select(),
                'label_block' => true,
                'multiple' => true,
                // Allow multiple selections
                'default' => [],
                // Default is an empty array
            ]
        );


        // Add a control for filter category
        $this->add_control(
            'post_filter',
            [
                'label' => __('Filter by Category hehehe', 'c-post-list'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $this->get_categories_for_select(),
                // Define this function to get category options
            ]
        );

        // End the controls section
        $this->end_controls_section();

        // Other widget controls can be added here...
    }



    // What your widget displays on the front-end
    protected function render()
    {
        $settings = $this->get_settings_for_display();
        // Enqueue jQuery
        wp_enqueue_script('jquery');

        // Query setting
        $post_filter = $settings['post_filter'];
        $enable_filtering = $settings['enable_filtering'];
        $selected_categories = $settings['selected_categories'];
        $posts_per_page = $settings['posts_per_page'];

        $current_page = max(1, get_query_var('paged'));
        $offset = ($current_page - 1) * $posts_per_page;

        // Define the custom query parameters
        $query_args = array(
            'post_type' => 'post',
            'posts_per_page' => $posts_per_page,
            'order' => 'DESC',
            'offset' => $offset
        );

        if (!empty($post_filter)) {
            $query_args['category__in'] = $post_filter;
        }

        // Perform the query
        $query = new WP_Query($query_args);

        // Get the total number of pages
        $total_pages = max(1, ceil($query->found_posts / $posts_per_page));

        // Generate "Previous" and "Next" links
        $prev_link = '';
        if ($current_page > 1) {
            $prev_link = get_pagenum_link($current_page - 1);
        }
        $next_link = '';
        if ($current_page < $total_pages) {
            $next_link = get_pagenum_link($current_page + 1);
        }

        // Generate the numbered page links
        $page_links = '';
        for ($i = 1; $i <= $total_pages; $i++) {
            $page_links .= '<a href="' . get_pagenum_link($i) . '" class="' . ($i == $current_page ? 'current' : '') . '">' . $i . '</a>';
        }

        // Load the template
        include plugin_dir_path(__FILE__) . 'cr-plugin/view/template.php';

    }

}
