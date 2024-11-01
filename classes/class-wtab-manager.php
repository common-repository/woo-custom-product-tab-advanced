<?php
if( !defined('ABSPATH') ){
    exit;
}

if( !class_exists('Wtab_Manager') ){

    class Wtab_Manager {

        /*
         * Holds tab for current product.
         */
        public $product_tabs = array();

        /*
         * Constructor.
         */
        function __construct() {
            add_action( 'wp', array( $this, 'set_tabs' ) );
            add_filter( 'wtab_tax_key', array( $this, 'wtab_tax_key' ) );
            add_filter( 'woocommerce_product_tabs', array($this, 'add_tabs_to_product') );
        }

        /*
         * Set tab objects in $tabs property.
         */
        function set_tabs(){

            if( is_singular( 'product' ) ){

                //Custom tab args
                $c_args = apply_filters( 'wtab_fetch_custom_tabs_args', 
                                            array(
                                                'post_type'=> 'wtab_custom_tab',
                                                'posts_per_page'=> -1,
                                                'post_status'=> 'publish',
                                                'post_parent'=> get_the_ID(),
                                                'order'=>'ASC',
                                                'orderby'=>'menu_order'
                                            )
                                        );

                //Saved tab args
                $args = apply_filters( 'wtab_fetch_tabs_args',
                                            array(
                                                'post_type'=> 'wtab',
                                                'post_status'=>'publish',
                                                'posts_per_page'=> -1,
                                                )
                                    );

                $saved_tabs     =   new WP_Query( $args );

                if( $saved_tabs->have_posts() ){

                    $id_collector = array();

                    while( $saved_tabs->have_posts() ){

                        $saved_tabs->the_post();

                        $this->add_global_tabs( get_the_ID(), $saved_tabs->post );
                        $this->add_tabs_by_taxonomy( get_the_ID(), $saved_tabs->post, 'product_cat' );
                        $this->add_tabs_by_taxonomy( get_the_ID(), $saved_tabs->post, 'product_tag' );
                        array_push( $id_collector, get_the_ID() );
                    }
                    wp_reset_postdata();              
                }

                $custom_tabs    =   new WP_Query( $c_args );

                if( $custom_tabs->have_posts() ){
                    
                    while( $custom_tabs->have_posts() ){

                        $custom_tabs->the_post();
                        array_push( $this->product_tabs, array( 'tab_id'=>  get_the_ID(), 'post_obj'=>$custom_tabs->post, 'type'=>'custom' ) );
                    }
                    wp_reset_postdata();
                }
            }
        }

        /*
         * Add tabs to products by category.
         */
        function add_tabs_by_taxonomy( $tab_id, WP_Post $postObj, $tax = 'product_cat' ){
            global $wpdb;
            
            $tab_ids = wp_list_pluck( $this->product_tabs, 'tab_id' );

            //If tab is already added with global check, just return the execution.
            if( in_array( $tab_id, $tab_ids ) ){
                return;
            }

            $meta_key = apply_filters( 'wtab_tax_key', $tax );
            $post_terms = wp_get_object_terms( get_the_ID(), $tax, array('fields'=>'ids') );
            $tab_cats = get_post_meta( $tab_id, $meta_key, true );

            if( !empty($post_terms) && !is_wp_error($post_terms) && !empty($tab_cats) ){
                $cats_to_assign = array_intersect( $post_terms, $tab_cats );

                if( !empty($cats_to_assign) ){
                    $this->product_tabs = array( 'tab_id'=>$tab_id, 'post_obj'=>$postObj, 'type'=>'saved' );
                }
            }
        }

        /*
         * Add global tabs to product_tabs variable.
         */
        function add_global_tabs( $tab_id, WP_Post $postObj ){

            if( $this->is_global_tab($tab_id) ){
                array_push( $this->product_tabs, array( 'tab_id'=>$tab_id, 'post_obj'=>$postObj, 'type'=>'saved' ) );
            }
        }

        /*
         * 
         */
        function wtab_tax_key( $tax ){

            if ( $tax == 'product_cat' ) return 'wtab_cat_tabs';
            if ( $tax == 'product_tag' ) return 'wtab_tag_tabs';

            return $tax;
        }

        /*
         * Check if meta value exists.
         */
        function is_global_tab( $tab_id ){
            return get_post_meta( $tab_id, 'wtab_is_global', true );
        }
        
        /*
         * Add tabs to individual product page.
         */
        function add_tabs_to_product( $tabs ){

            if( !empty($this->product_tabs) ){

                $iterator = 40;

                foreach( $this->product_tabs as $tab ){

                    $tabs['wtab-'. $tab['tab_id']] = array(
                        'title' => $tab['post_obj']->post_title,
                        'priority' => $iterator,
                        'data' => $tab['post_obj'],
                        'callback' => array($this, 'wtab_add_saved_tab_callback')
                    );

                    $iterator += 10;
                }
            }
            wp_reset_postdata();

            return apply_filters( 'wtab_tab_reordering', $tabs );
        }

        /*
         * Callback function to add tabs
         */
        function wtab_add_saved_tab_callback( $key, $tab ){
            echo apply_filters( 'the_content', $tab['data']->post_content );
        }   
    }

    $GLOBALS['wtab_manager'] = new Wtab_Manager();
}