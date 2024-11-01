<?php

//prevent direct access of file.
if( !defined('ABSPATH') ) {
    exit;
}

if( !class_exists('Wtab_Post_Type') ) {

    class Wtab_Post_Type {
        
        //constructor
        function __construct() {
            
        }
        
        //register post type for global tab creation
        static function wtab_reg_post_type() {
            
            $labels = array(
                'name'                  => _x( 'Custom Tabs', 'Post type general name', 'wtab' ),
                'singular_name'         => _x( 'Custom Tab', 'Post type singular name', 'wtab' ),
                'menu_name'             => _x( 'Custom Tabs', 'Admin Menu text', 'wtab' ),
                'name_admin_bar'        => _x( 'Custom Tab', 'Add New on Toolbar', 'wtab' ),
                'add_new'               => __( 'Add New', 'wtab' ),
                'add_new_item'          => __( 'Add New Custom Tab', 'wtab' ),
                'new_item'              => __( 'New Custom Tab', 'wtab' ),
                'edit_item'             => __( 'Edit Custom Tab', 'wtab' ),
                'view_item'             => __( 'View Custom Tab', 'wtab' ),
                'all_items'             => __( 'All Custom Tabs', 'wtab' ),
                'search_items'          => __( 'Search Custom Tabs', 'wtab' ),
                'parent_item_colon'     => __( 'Parent Custom Tabs:', 'wtab' ),
                'not_found'             => __( 'No tabs found.', 'wtab' ),
                'not_found_in_trash'    => __( 'No tabs found in Trash.', 'wtab' ),
                'featured_image'        => _x( 'Custom Tab Cover Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'wtab' ),
                'set_featured_image'    => _x( 'Set Custom Tab image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'wtab' ),
                'remove_featured_image' => _x( 'Remove Custom Tab image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'wtab' ),
                'use_featured_image'    => _x( 'Use as custom tab image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'wtab' ),
                'archives'              => _x( 'Custom Tab archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'wtab' ),
                'insert_into_item'      => _x( 'Insert into custom tab', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'wtab' ),
                'uploaded_to_this_item' => _x( 'Uploaded to this custom tab', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'wtab' ),
                'filter_items_list'     => _x( 'Filter tabs list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'wtab' ),
                'items_list_navigation' => _x( 'Tabs list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'wtab' ),
                'items_list'            => _x( 'Tabs list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'wtab' ),
            );

            // Filter post type labels.
            $labels = apply_filters( 'wtab_post_type_labels', $labels );

            $args = array(
                        'labels'             => $labels,
                        'public'             => false,
                        'publicly_queryable' => false,
                        'show_ui'            => true,
                        'show_in_menu'       => true,
                        'query_var'          => true,
                        'rewrite'            => array( 'slug' => 'wtab' ),
                        'capability_type'    => 'post',
                        'has_archive'        => false,
                        'hierarchical'       => false,
                        'menu_position'      => null,
                        'supports'           => apply_filters( 'wtab_post_type_supports', array( 'title', 'editor' )),
                    );

            //Filter the post type args.
            $args = apply_filters( 'wtab_post_type_args', $args );
            
            //Register custom tab post type for global tab creations.
            register_post_type( 'wtab', $args );
            
            //Custom tab labels
            $labels = array(
                'name'                  => _x( 'Custom Tabs', 'Post type general name', 'wtab' ),
                'singular_name'         => _x( 'Custom Tab', 'Post type singular name', 'wtab' ),
                'menu_name'             => _x( 'Custom Tabs', 'Admin Menu text', 'wtab' ),
                'name_admin_bar'        => _x( 'Custom Tab', 'Add New on Toolbar', 'wtab' ),
                'add_new'               => __( 'Add New', 'wtab' ),
                'add_new_item'          => __( 'Add New Custom Tab', 'wtab' ),
                'new_item'              => __( 'New Custom Tab', 'wtab' ),
                'edit_item'             => __( 'Edit Custom Tab', 'wtab' ),
                'view_item'             => __( 'View Custom Tab', 'wtab' ),
                'all_items'             => __( 'All Custom Tabs', 'wtab' ),
                'search_items'          => __( 'Search Custom Tabs', 'wtab' ),
                'parent_item_colon'     => __( 'Parent Custom Tabs:', 'wtab' ),
                'not_found'             => __( 'No tabs found.', 'wtab' ),
                'not_found_in_trash'    => __( 'No tabs found in Trash.', 'wtab' ),
                'featured_image'        => _x( 'Custom Tab Cover Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'wtab' ),
                'set_featured_image'    => _x( 'Set Custom Tab image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'wtab' ),
                'remove_featured_image' => _x( 'Remove Custom Tab image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'wtab' ),
                'use_featured_image'    => _x( 'Use as custom tab image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'wtab' ),
                'archives'              => _x( 'Custom Tab archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'wtab' ),
                'insert_into_item'      => _x( 'Insert into custom tab', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'wtab' ),
                'uploaded_to_this_item' => _x( 'Uploaded to this custom tab', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'wtab' ),
                'filter_items_list'     => _x( 'Filter tabs list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'wtab' ),
                'items_list_navigation' => _x( 'Tabs list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'wtab' ),
                'items_list'            => _x( 'Tabs list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'wtab' ),
            );

            $args = array(
                        'labels'             => $labels,
                        'public'             => false,
                        'publicly_queryable' => false,
                        'show_ui'            => false,
                        'show_in_menu'       => false,
                        'query_var'          => true,
                        'rewrite'            => array( 'slug' => 'wtab_custom_tab' ),
                        'capability_type'    => 'post',
                        'has_archive'        => false,
                        'hierarchical'       => false,
                        'menu_position'      => null,
                        'supports'           => apply_filters( 'wtab_post_type_supports', array( 'title', 'editor' )),
                    );

            
            //Register post type for custom tab creations
            register_post_type( 'wtab_custom_tab', $args );
        }
        
    }
}
