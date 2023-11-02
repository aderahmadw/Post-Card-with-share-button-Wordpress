<?php
// Include WordPress core functions
require_once($_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');

// Define the function to handle the AJAX request
function filter_posts() {
    // Get the selected category from the AJAX request
    $category = sanitize_text_field($_POST['category']);

    // Use WP_Query to fetch posts based on the selected category
    // You should define your WP_Query arguments here

    // Example WP_Query:
    $query_args = array(
        'post_type' => 'post',
        'posts_per_page' => -1,  // Fetch all posts
        'category_name' => $category,
    );
    $query = new WP_Query($query_args);

    // Prepare an array of posts
    $posts = array();

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();

            // Retrieve post data
            // You can customize what post data to include

            $post_data = array(
                'title' => get_the_title(),
                'content' => get_the_content(),
                'permalink' => get_permalink(),
                // Add more data as needed
            );

            $posts[] = $post_data;
        }

        wp_reset_postdata();
    }

    // Return the posts as JSON
    wp_send_json($posts);
}

// Hook your AJAX handler to WordPress
add_action('wp_ajax_filter_posts', 'filter_posts');
add_action('wp_ajax_nopriv_filter_posts', 'filter_posts');
