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
        // Add a control for the pagination
        // $this->add_control(
        //     'current_page',
        //     [
        //         'label' => __('Current Page', 'c-post-list'),
        //         'type' => \Elementor\Controls_Manager::NUMBER,
        //         'default' => 1,
        //         // Set a default current page
        //     ]
        // );
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

        // post offset control
        // $this->add_control(
        //     'post_offset',
        //     [
        //         'label' => __('Post Offset xixixi', 'c-post-list'),
        //         'type' => \Elementor\Controls_Manager::NUMBER,
        //         'default' => 0,
        //         // Default offset is 0 (no offset)
        //         'min' => 0,
        //         'step' => 1,
        //     ]
        // );

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
        // Get pagination settings from widget settings
        $posts_per_page = $settings['posts_per_page'];
        // $current_page = $settings['current_page'];
        // Get the current page number
        $current_page = max(1, get_query_var('paged'));

        // Calculate the offset based on the current page
        $offset = ($current_page - 1) * $posts_per_page;


        // Define the custom query parameters
        $query_args = array(
            'post_type' => 'post',
            'posts_per_page' => $posts_per_page,
            'order' => 'DESC',
            'offset' => $offset
            // Calculate the offset
        );

        if (!empty($post_filter)) {
            $query_args['category__in'] = $post_filter; // Apply the category filter
        }

        // Perform the query
        $query = new WP_Query($query_args);

        // Get the total number of pages
        $total_pages = max(1, ceil($query->found_posts / $posts_per_page));

        // Generate "Previous" and "Next" links
        $prev_link = ($current_page > 1) ? get_pagenum_link($current_page - 1) : '';
        $next_link = ($current_page < $total_pages) ? get_pagenum_link($current_page + 1) : '';

        // Generate the numbered page links
        $page_links = '';
        for ($i = 1; $i <= $total_pages; $i++) {
            $page_links .= '<a href="' . get_pagenum_link($i) . '" class="' . ($i == $current_page ? 'current' : '') . '">' . $i . '</a>';
        }

        ?>
        <div class="cr-post-wrapper">
            <div class="cr-post-header">
                <?php
                if ('yes' === $enable_filtering) {
                    // Render the filtering code here, but only for selected categories
                    ?>
                    <div class="cr-post-filter-wrapper">
                        <div class="cr-post-filter active" data-category="all">All</div>
                        <?php
                        // Get the categories for generating filter options
                        $categories = get_categories();

                        foreach ($categories as $category) {
                            if (in_array($category->term_id, $selected_categories)) { // Check if the category ID is in the selected categories array
                                echo '<div class="cr-post-filter" data-category="' . $category->slug . '">' . $category->name . '</div>';
                            }
                        }
                        ?>
                    </div>
                    <?php
                }
                ?>
            </div>
            <div class="cr-post-body">
                <?php
                if ($query->have_posts()) {
                    while ($query->have_posts()) {
                        $query->the_post();

                        // Get post data
                        $post_title = get_the_title();
                        $post_author = get_the_author();
                        $post_date = get_the_date('F j, Y'); // Format the date as desired
                        $post_image = get_the_post_thumbnail_url(); // Get the post's featured image URL
        
                        // Display post data within the widget
                        ?>
                        <?php
                        $post_categories = get_the_category();
                        $category_slugs = array();

                        foreach ($post_categories as $category) {
                            $category_slugs[] = $category->slug;
                        }

                        $categories_string = implode(' ', $category_slugs);
                        ?>
                        <div class="cr-post-card <?php echo esc_attr($categories_string); ?>"
                            data-category="<?php echo esc_attr($categories_string); ?>">
                            <a class="cr-post-feature-img" href="<?php the_permalink(); ?>">
                                <div class="cr-post-img">
                                    <img src="<?php echo esc_url($post_image); ?>" alt="Post Image">
                                </div>
                            </a>
                            <div class="cr-post-content-wrapper">
                                <div class="cr-post-title">
                                    <h3>
                                        <a href="<?php the_permalink(); ?>">
                                            <?php echo esc_html($post_title); ?>
                                        </a>
                                    </h3>
                                </div>
                                <div class="cr-post-meta">
                                    <span>
                                        <i aria-hidden="true" class="fa fa-user"></i>
                                        <?php echo esc_html($post_author); ?> |
                                        <i aria-hidden="true" class="fa fa-calendar"></i>
                                        <?php echo esc_html($post_date); ?>
                                    </span>
                                </div>
                                <a class="cr-post-button" href="<?php the_permalink(); ?>">
                                    <span>
                                        <i aria-hidden="true" class="fas fa-play"></i> WATCH NOW
                                    </span>
                                </a>
                                <div class="cr-social-share">
                                    <p>Share to</p>
                                    <div class="cr-share-btn-wrapper">
                                        <!-- WhatsApp share button -->
                                        <a href="https://api.whatsapp.com/send?text=<?php echo esc_url(the_permalink()); ?>"
                                            target="_blank">
                                            <i class="fab fa-whatsapp"></i>
                                        </a>
                                        <!-- Facebook share button -->
                                        <a href="https://www.facebook.com/sharer.php?u=<?php echo esc_url(the_permalink()); ?>"
                                            target="_blank">
                                            <i class="fab fa-facebook"></i>
                                        </a>
                                        <!-- Twitter share button -->
                                        <a href="https://twitter.com/share?url=<?php echo esc_url(the_permalink()); ?>&text=<?php echo esc_html(get_the_title()); ?>"
                                            target="_blank">
                                            <i class="fab fa-twitter"></i>
                                        </a>
                                        <!-- Linkedin share button -->
                                        <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo esc_url(the_permalink()); ?>&text=<?php echo esc_html(get_the_title()); ?>"
                                            target="_blank">
                                            <i class="fab fa-linkedin"></i>
                                        </a>
                                        <!-- Add more share buttons for other platforms as needed -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                    wp_reset_postdata(); // Reset the post data
                } else {
                    echo 'No posts found.';
                }
                ?>
            </div>
            <div class="cr-post-footer">
                <div class="pagination">
                    <a class="prev" href="<?php echo esc_url($prev_link); ?>">Previous</a>
                    <a class="next" href="<?php echo esc_url($next_link); ?>">Next</a>
                    <?php echo $page_links; ?>
                </div>
            </div>
        </div>
        <?php
    }

}
