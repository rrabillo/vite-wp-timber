<?php

/*  Upscale images
/* ------------------------------------ */
function thumbnail_upscale(  $default, $orig_w, $orig_h, $new_w, $new_h, $crop ){
    if ( !$crop ) return null;

    $aspect_ratio = $orig_w / $orig_h;
    $size_ratio = max($new_w / $orig_w, $new_h / $orig_h);

    $crop_w = round($new_w / $size_ratio);
    $crop_h = round($new_h / $size_ratio);

    $s_x = floor( ($orig_w - $crop_w) / 2 );
    $s_y = floor( ($orig_h - $crop_h) / 2 );

    return array( 0, 0, (int) $s_x, (int) $s_y, (int) $new_w, (int) $new_h, (int) $crop_w, (int) $crop_h );
}

add_filter( 'image_resize_dimensions', 'thumbnail_upscale', 10, 6 );

//Disable gutenberg
add_filter('use_block_editor_for_post', '__return_false', 10);

//Disable author page
function disable_author_page( $wp_query ) {
    global $wp_query;
    if(is_author()){
        $wp_query->set_404();
        status_header(404);
    }
}
add_action( 'template_redirect', 'disable_author_page' );

// Disable content editor on specific templates
function disable_content_editor() {
    $post_id = $_GET['post'] ? : $_POST['post_ID'] ;
    if(isset($post_id)){
        $template_file = get_post_meta($post_id, '_wp_page_template', true);
        if ($template_file == 'template-content.php'
            || $template_file == 'template-homepage.php'
        ) {
            remove_post_type_support('page', 'editor');
        }
    }
//    remove_post_type_support('post', 'editor');
}
add_action('admin_init', 'disable_content_editor');

//Disable comments
function disable_comments(){
    add_action('admin_init', function () {
        // Redirect any user trying to access comments page
        global $pagenow;

        if ($pagenow === 'edit-comments.php') {
            wp_safe_redirect(admin_url());
            exit;
        }

        // Remove comments metabox from dashboard
        remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');

        // Disable support for comments and trackbacks in post types
        foreach (get_post_types() as $post_type) {
            if (post_type_supports($post_type, 'comments')) {
                remove_post_type_support($post_type, 'comments');
                remove_post_type_support($post_type, 'trackbacks');
            }
        }
    });

// Close comments on the front-end
    add_filter('comments_open', '__return_false', 20, 2);
    add_filter('pings_open', '__return_false', 20, 2);

// Hide existing comments
    add_filter('comments_array', '__return_empty_array', 10, 2);

// Remove comments page in menu
    add_action('admin_menu', function () {
        remove_menu_page('edit-comments.php');
    });

// Remove comments links from admin bar
    add_action('init', function () {
        if (is_admin_bar_showing()) {
            remove_action('admin_bar_menu', 'wp_admin_bar_comments_menu', 60);
        }
    });
}
disable_comments();

// Add style for tinymce
if(defined('ENV_DEV') && ENV_DEV){
    add_editor_style( 'http://localhost:5173/scss/tinystyles.scss' );
}
else{
    add_editor_style( get_template_directory_uri() . '/vite/dist/css/tinystyles.css' );
}

// Custom Tinymce styles
add_filter( 'mce_buttons_2', function( $buttons ) {
    array_unshift( $buttons, 'styleselect' );
    return $buttons;
});
add_filter( 'tiny_mce_before_init', function($settings) {

    $style_formats = array (
        array(
            'title'	=> __('Large'),
            'items'	=> array(
                array (
                    'title' => 'Bold',
                    'selector' => 'p',
                    'classes' => 'bigger-text bold',
                ),
                array (
                    'title' => 'Regular',
                    'selector' => 'p',
                    'classes' => 'bigger-text',
                ),
            )
        ),
        array(
            'title'	=> __('Small'),
            'items'	=> array(
                array (
                    'title' => 'Bold',
                    'selector' => 'p',
                    'classes' => 'smaller-text bold',
                ),
                array (
                    'title' => 'Regular',
                    'selector' => 'p',
                    'classes' => 'smaller-text',
                ),
            )
        ),
    );

    $settings['style_formats'] = wp_json_encode( $style_formats );

    return $settings;

});