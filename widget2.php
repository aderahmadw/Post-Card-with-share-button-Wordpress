<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class C_Post_List_Related extends \Elementor\Widget_Base
{

    // Your widget's name, title, icon and category
    public function get_name()
    {
        return 'C_Post_List_Related';
    }

    public function get_title()
    {
        return esc_html__('C Post List Related', 'c-post-list-related-related');
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

        $options = ['' => __('All', 'c-post-list-related')]; // Include an option to display all posts

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
                'label' => __('Content', 'c-post-list-related'),
            ]
        );

        // Add a control for the number of posts to display
        $this->add_control(
            'posts_to_display',
            [
                'label' => __('Number of Posts to Display', 'c-post-list-related'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 0,
                // Display all posts by default
                'min' => 0,
                // Minimum value, 0 means display all
                'step' => 1,
            ]
        );
        // Add control displaying filter or not
        $this->add_control(
            'enable_filtering',
            [
                'label' => __('Enable Filtering', 'c-post-list-related'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'c-post-list-related'),
                'label_off' => __('No', 'c-post-list-related'),
                'default' => 'yes',
                // Enable filtering by default
            ]
        );

        // Add a control for filter category
        $this->add_control(
            'post_filter',
            [
                'label' => __('Filter by Category hehehe', 'c-post-list-related'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $this->get_categories_for_select(),
                // Define this function to get category options
            ]
        );
        // post offset control
        $this->add_control(
            'post_offset',
            [
                'label' => __('Post Offset xixixi', 'c-post-list-related'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 0,
                // Default offset is 0 (no offset)
                'min' => 0,
                'step' => 1,
            ]
        );

        $this->add_control(
            'enable_related_posts',
            [
                'label' => __('Enable Related Posts', 'c-post-list-related'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'no',
                // Disable related posts by default
                'label_on' => __('Yes', 'c-post-list-related'),
                'label_off' => __('No', 'c-post-list-related'),
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
        $posts_to_display = $settings['posts_to_display'];
        $offset = $settings['post_offset'];
        $post_filter = $settings['post_filter'];
        $enable_filtering = $settings['enable_filtering'];
        $enable_related_posts = $settings['enable_related_posts'];

        // Define your custom query parameters
        $query_args = array(
            'post_type' => 'post',
            'posts_per_page' => $posts_to_display,
            'order' => 'DESC',
            'offset' => $offset,
        );

        if (!empty($post_filter)) {
            $query_args['category__in'] = $post_filter; // Apply the category filter
        }

        // Perform the query
        $query = new WP_Query($query_args);

        ?>
        <div class="cr-post-wrapper">
            <style>
                .cr-post-wrapper {
                    display: flex;
                    flex-direction: column;
                    flex-wrap: wrap;
                    justify-content: center;
                    align-items: center;
                }

                .cr-post-filter-wrapper {
                    display: flex;
                    gap: 1.5rem;
                    flex-wrap: wrap;
                }

                .cr-post-filter {
                    padding: 1rem;
                }

                .cr-post-filter.active {
                    background-color: #333;
                    color: #fff;
                    /* Add any other styling you want for active tabs */
                }

                .cr-post-body {
                    display: flex;
                    flex-wrap: wrap;
                    justify-content: center;
                    align-items: center;
                }

                .cr-post-card {
                    border: 1px solid #ccc;
                    border-radius: 5px;
                    padding: 10px;
                    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                    margin: 10px;
                    max-width: 340px;
                    min-height: 450px;
                    display: flex;
                    justify-content: space-between;
                    align-items: flex-start;
                    flex-direction: column;
                }

                .cr-post-feature-img img {
                    max-width: 100%;
                    height: auto;
                }

                .cr-post-title h3 {
                    font-size: 1.2rem;
                    font-weight: bold;
                    margin-top: 10px;
                }

                .cr-post-meta span {
                    font-size: 0.9rem;
                    color: #888;
                }

                .cr-post-button span {
                    background-color: #333;
                    color: #fff;
                    border: none;
                    padding: 10px 20px;
                    border-radius: 5px;
                    margin-top: 10px;
                    cursor: pointer;
                    display: inline-block;
                }

                .cr-social-share p {
                    margin-top: 10px;
                    margin-bottom: 5px;
                }

                .cr-share-btn-wrapper a {
                    margin-right: 10px;
                }
            </style>
            <div class="cr-post-header">
                <?php
                if ('yes' === $enable_filtering) {
                    // Render the filtering code here
                    // This is where your category filter code, including the filter wrapper, should go
                    // The code for the filter control you provided earlier
                    ?>
                    <div class="cr-post-filter-wrapper">
                        <div class="cr-post-filter active" data-category="all">All</div>
                        <?php
                        // Get the categories for generating filter options
                        $categories = get_categories();
                        foreach ($categories as $category) {
                            echo '<div class="cr-post-filter" data-category="' . $category->slug . '">' . $category->name . '</div>';
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
                            <div class="cr-post-feature-img">
                                <img src="<?php echo esc_url($post_image); ?>" alt="Post Image">
                            </div>
                            <div class="cr-post-title">
                                <h3>
                                    <?php echo esc_html($post_title); ?>
                                </h3>
                            </div>
                            <div class="cr-post-meta">
                                <span>
                                    <?php echo esc_html($post_author); ?> |
                                    <?php echo esc_html($post_date); ?>
                                </span>
                            </div>
                            <a class="cr-post-button" href="<?php the_permalink(); ?>">
                                <span>
                                    Read More
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
                                    <!-- Add more share buttons for other platforms as needed -->
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
        </div>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                // When a filter is clicked
                $('.cr-post-filter').click(function () {
                    // Remove the "active" class from all filters
                    $('.cr-post-filter').removeClass('active');

                    var category = $(this).data('category');

                    // Add the "active" class to the selected filter
                    $(this).addClass('active');

                    // Log the selected category to the console
                    console.log('Selected Category: ' + category);

                    // Show posts that have the selected category in their data-category attribute
                    $('.cr-post-card').each(function () {
                        if (category === 'all' || $(this).data('category').includes(category)) {
                            $(this).show();
                        } else {
                            $(this).hide();
                        }
                    });
                });

                // Initially, show all posts
                $('.cr-post-card').show();
            });


        </script>
        <?php
    }

}
