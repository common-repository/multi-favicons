<?php

abstract class favicon_Meta_Box
{
    public static function add_custom_favicon()
    {
        $screens = ['post', 'page'];
        foreach ($screens as $screen) {
            add_meta_box(
                'custom_favicon', // Unique custom post meta ID
                'Favicon', // Metabox title
                [self::class, 'favicon_upload_template'],   // Content callback, must be of type callable
                $screen    // Post type
            );
        }
    }
 
    public static function save_custom_favicon($post_id)
    {
        if (array_key_exists('custom_favicon', $_POST)) {
            update_post_meta(
                $post_id,
                '_custom_favicon',
                $_POST['custom_favicon']
            );
        }
    }
 
    public static function favicon_upload_template($post)
    {
        $value = get_post_meta($post->ID, '_custom_favicon', true);
        ?>
        <span class='upload'>
                <input type='text' id='custom_favicon' class='regular-text text-upload' name='custom_favicon' value='<?php echo esc_url( $value ); ?>'/>
                <input type='button' class='button button-upload' value='Upload an icon'/></br>
                <img style='max-width: 300px; display: block;' src='<?php echo esc_url( $value ); ?>' class='preview-upload' />
            </span>
        <?php
    }
}
 
add_action('add_meta_boxes', ['favicon_Meta_Box', 'add_custom_favicon']);
add_action('save_post', ['favicon_Meta_Box', 'save_custom_favicon']);