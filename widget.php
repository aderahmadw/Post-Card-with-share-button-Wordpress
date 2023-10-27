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




    // Your widget's sidebar settings
    protected function _register_controls()
    {

    }





    // What your widget displays on the front-end
    protected function render()
    {
        $settings = $this->get_settings_for_display();

        // Define your custom query parameters
        $query_args = array(
            'post_type' => 'post',
            // You can change the post type as needed
            'posts_per_page' => 3,
            // Number of posts to display (example: 3 posts)
            'order' => 'DESC',
            // Order of posts (DESC for most recent first)
        );

        // Perform the query
        $query = new WP_Query($query_args);

        ?>
        <div class="cr-post-wrapper">
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
                    <style>
                        .cr-post-wrapper {
                            display: flex;
                            justify-content: center;
                            align-items: center;
                        }

                        .cr-post-card {
                            border: 1px solid #ccc;
                            border-radius: 5px;
                            padding: 10px;
                            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                            margin: 10px;
                            max-width: 300px
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
                    <div class="cr-post-card">
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
                                <a href="https://api.whatsapp.com/send?text=<?php echo esc_url(the_permalink()); ?>" target="_blank">
                                    <i class="fab fa-whatsapp"></i>
                                </a>
                                <!-- Facebook share button -->
                                <a href="https://www.facebook.com/sharer.php?u=<?php echo esc_url(the_permalink()); ?>" target="_blank">
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
            }
            ?>
        </div>
        <?php
    }

}
