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