// jQuery(document).ready(function ($) {
//     // When a filter is clicked
//     $('.cr-post-filter').click(function () {
//         // Remove the "active" class from all filters
//         $('.cr-post-filter').removeClass('active');

//         var category = $(this).data('category');

//         // Add the "active" class to the selected filter
//         $(this).addClass('active');

//         // Log the selected category to the console
//         console.log('Selected Category: ' + category);

//         // Show posts that have the selected category in their data-category attribute
//         $('.cr-post-card').each(function () {
//             if (category === 'all' || $(this).data('category').includes(category)) {
//                 $(this).show();
//             } else {
//                 $(this).hide();
//             }
//         });
//     });

//     // Initially, show all posts
//     $('.cr-post-card').show();
// });

jQuery(document).ready(function ($) {
    // When a filter is clicked
    $('.cr-post-filter').click(function () {
        // Get the selected category
        var category = $(this).data('category');
        // Make an AJAX request
        $.ajax({
            url: ajaxurl,  // This is a global variable that contains the URL to admin-ajax.php
            type: 'POST',
            data: {
                action: 'filter_posts',
                category: category
            },
            success: function (response) {
                // Handle the response and replace the posts in your widget
                // You can update the widget content with the new posts here
                $('.cr-post-body').html(response);
            },
            error: function(xhr, status, error) {
                console.log('AJAX Error: ' + error);
            }            
        });
    });
});
