<div class="cr-post-wrapper">
    <div class="cr-post-header">
        <?php if ('yes' === $enable_filtering): ?>
            <div class="cr-post-filter-wrapper">
                <div class="cr-post-filter active" data-category="all">All</div>
                <?php
                $categories = get_categories();
                foreach ($categories as $category) {
                    if (in_array($category->term_id, $selected_categories)) {
                        echo '<div class="cr-post-filter" data-category="' . $category->slug . '">' . $category->name . '</div>';
                    }
                }
                ?>
            </div>
        <?php endif; ?>
    </div>
    <div class="cr-post-body">
        <?php if ($query->have_posts()):
            while ($query->have_posts()):
                $query->the_post();
                $post_title = get_the_title();
                $post_author = get_the_author();
                $post_date = get_the_date('F j, Y');
                $post_image = get_the_post_thumbnail_url();
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
            <?php endwhile;
            wp_reset_postdata(); else: ?>
            <p>No posts found.</p>
        <?php endif; ?>
    </div>
    <div class="cr-post-footer">
        <div class="pagination">
            <?php if ($prev_link): ?>
                <a class="prev" href="<?php echo esc_url($prev_link); ?>">Previous</a>
            <?php endif; ?>
            <?php echo $page_links; ?>
            <?php if ($next_link): ?>
                <a class="next" href="<?php echo esc_url($next_link); ?>">Next</a>
            <?php endif; ?>
        </div>
    </div>
</div>