<?php
/**
 * Plugin Name: Matière Noire Blocks Library
 * Description: Des bloc créés par l'équipe de Matière Noire
 * Author: matière Noire
 * Version: 1.0.0
 * Author URI: https://matierenoire.io
 */


/**
 * Enqueue blocks script
 */
function mn_blocks_enqueue_block_editor_assets()
{

    wp_enqueue_script(
        'mn-blocks-scripts',
        plugins_url('build/index.js', __FILE__),
        ['wp-components', 'wp-blocks', 'wp-element', 'wp-block-editor', 'wp-data', 'wp-compose',  'wp-dom-ready', 'wp-edit-post', 'wp-api-fetch'],
        '1.0.0'
    );
}

add_action('enqueue_block_editor_assets', 'mn_blocks_enqueue_block_editor_assets');


/**
 * Registers the plugin's blocks
 *
 * @since 1.0.0
 */
function mn_blocks_register_blocks()
{
    register_block_type('mn-blocks/post-type-archive', [
        'render_callback' => 'mn_blocks_post_type_archive',
        'attributes'      => [
            'numberOfPosts'    => [
                'type'      => 'integer',
                'default'   => 3,
            ],
            'postType'    => [
                'type'      => 'string',
                'default'   => 'post',
            ]

        ]
    ]);
}

add_action('init', 'mn_blocks_register_blocks');


/**
 * Register MN Blocks catégory
 *
 * @param $categories
 * @param $post
 * @return array
 */
function mn_blocks_library_block_categories($categories, $post)
{

    $cat = array_merge(
        $categories,
        array(
            array(
                'slug'  => 'mn-blocks',
                'title' => 'MN Blocs',
                'icon'  => null
            )
        )
    );

    return $cat;
}
add_filter('block_categories', 'mn_blocks_library_block_categories', 10, 2);


/**
 *
 */
function mn_blocks_post_type_archive($attributes)
{

    $post_type = new WP_Query([
        'posts_per_page'    => $attributes['numberOfPosts'],
        'post_type'    => $attributes['postType']
    ]);
    $return = '';
    if ($post_type->have_posts()) {
        $return .= '<div class="card-grid">';
        while ($post_type->have_posts()) {
            $post_type->the_post();
            $return .= Hybrid\View\render('entry/archive', Hybrid\Post\hierarchy());
        }
        $return .= '</div>';
    }
    return $return;
}
