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
                'label' => __('Posts Per Page', 'c-post-list'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 10,
                // Set a default number of posts per page
            ]
        );
        // Add a control for the pagination
        $this->add_control(
            'current_page',
            [
                'label' => __('Current Page', 'c-post-list'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 1,
                // Set a default current page
            ]
        );
        // Add control displaying filter or not
        $this->add_control(
            'enable_filtering',
            [
                'label' => __('Enable Filtering', 'c-post-list'),
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
                'label' => __('Selected Categories', 'c-post-list'),
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
        $current_page = $settings['current_page'];

        // Define the custom query parameters
        $query_args = array(
            'post_type' => 'post',
            'posts_per_page' => $posts_per_page,
            'order' => 'DESC',
            'offset' => ($current_page - 1) * $posts_per_page,
            // Calculate the offset
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
                    padding: .6rem 1rem;
                    cursor: pointer;
                    background-color: #ddd;
                }

                .cr-post-filter.active {
                    background-color: #333;
                    color: #fff;
                }

                .cr-post-body {
                    display: flex;
                    flex-wrap: wrap;
                    justify-content: center;
                    align-items: start;
                }

                .cr-post-card {
                    background-color: #fff;
                    border-radius: 20px;
                    padding: 5px;
                    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                    margin: 10px;
                    max-width: 340px;
                    min-height: 470px;
                    display: flex;
                    justify-content: space-between;
                    align-items: flex-start;
                    flex-direction: column;
                }

                .cr-post-feature-img {
                    position: relative;
                    min-height: 12.5rem;
                    width: 20.5rem;
                    background-color: #333;
                    overflow: hidden;
                    border-radius: 20px;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                }

                .cr-post-feature-img:after {
                    background-image: url("data:image/svg+xml,<svg width='50' height='50' viewBox='0 0 50 50' fill='none' xmlns='http://www.w3.org/2000/svg'><path fill-rule='evenodd' clip-rule='evenodd' d='M25 50C38.8071 50 50 38.8071 50 25C50 11.1929 38.8071 0 25 0C11.1929 0 0 11.1929 0 25C0 38.8071 11.1929 50 25 50ZM37.6871 23.2519C37.3614 22.711 36.8943 22.2687 36.3359 21.9724L21.4405 13.8888C19.042 12.5883 16.0959 14.2809 16.0959 16.9607V33.1267C16.0959 35.8042 19.042 37.498 21.4405 36.1963L36.3359 28.1139C36.8943 27.8176 37.3614 27.3753 37.6871 26.8343C38.0129 26.2934 38.1849 25.6742 38.1849 25.0431C38.1849 24.4121 38.0129 23.7929 37.6871 23.2519Z' fill='white'/></svg>");
                    background-repeat: no-repeat;
                    content: "";
                    display: inline-block;
                    height: 50px;
                    position: absolute;
                    vertical-align: -0.125em;
                    width: 50px;
                    left: 50%;
                    top: 80px;
                    transform: translate(-50%, -20%);
                }

                .cr-post-img {
                    position: relative;
                    width: 100%;
                    height: 12.5rem;
                }

                .cr-post-img img {
                    max-width: 100%;
                    height: 100%;
                    object-fit: cover;
                }

                .cr-post-title h3 {
                    font-size: 1.5rem;
                    margin: 20px 0 10px;
                    padding: 0;
                    line-height: 28px;
                }

                .cr-post-title h3 a {
                    font-weight: bold;
                    color: #000;
                    transition: .2s;
                }

                .cr-post-title h3 a:hover {
                    font-weight: 800;
                    text-decoration: none;
                }

                .cr-post-title h3:after {
                    content: none;
                }

                .cr-post-meta span {
                    font-size: 0.9rem;
                    color: #888;
                }

                .cr-post-button span {
                    background-color: #333;
                    color: #fff;
                    border: none;
                    padding: 5px 10px;
                    border-radius: 10px;
                    margin-top: 15px;
                    cursor: pointer;
                    display: inline-block;
                }

                .cr-post-button span {
                    box-shadow: inset -400px 0 0 0 #d81818;
                    -webkit-transition: ease-out .7s;
                    -moz-transition: ease-out .7s;
                    transition: ease-out .7s;
                    border: 1px solid #d81818 !important;
                }

                .cr-post-button {
                    font-family: "Bai Jamjuree", Sans-serif;
                }

                .cr-post-button span:hover {
                    background-color: transparent !important;
                    box-shadow: inset 0 0 0 0 #d81818;
                    color: #d81818 !important;
                    border: 1px solid #d81818;
                }

                .cr-social-share p {
                    font-size: 1.2rem;
                    font-weight: 800;
                    font-family: 'Bai Jamjuree';
                    margin: 1rem 0;
                    color: #000;
                }

                .cr-share-btn-wrapper a {
                    margin-right: 10px;
                    font-size: 1.6rem;
                    transition: .5s;
                    color: #888;
                }

                .cr-share-btn-wrapper a:hover {
                    text-decoration: none;
                }

                .cr-post-content-wrapper {
                    padding: 0 20px 20px;
                }
            </style>
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
                    <?php
                    // Calculate the total number of pages
                    $total_pages = max(1, ceil($query->found_posts / $posts_per_page));

                    // Generate the "Previous" link
                    $prev_link = ($current_page > 1) ? get_pagenum_link($current_page - 1) : '';

                    // Generate the "Next" link
                    $next_link = ($current_page < $total_pages) ? get_pagenum_link($current_page + 1) : '';

                    // Generate the numbered page links
                    $page_links = '';
                    for ($i = 1; $i <= $total_pages; $i++) {
                        $page_links .= '<a href="' . get_pagenum_link($i) . '" class="' . (($i == $current_page) ? 'current' : '') . '">' . $i . '</a>';
                    }
                    ?>
                    <a class="prev" href="<?php echo esc_url($prev_link); ?>">Previous</a>
                    <a class="next" href="<?php echo esc_url($next_link); ?>">Next</a>
                    <?php echo $page_links; ?>
                    <?php
                    ?>
                </div>
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
