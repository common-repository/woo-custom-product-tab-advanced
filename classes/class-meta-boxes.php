<?php
//Prevent direct access
if( !defined('ABSPATH') ) {
    exit;
}

if( !class_exists('Wtab_Metaboxes') ) {

    class Wtab_Metaboxes {

        function __construct() {
            add_action( 'add_meta_boxes_wtab', array( $this, 'global_tab_metabox' ), 10 );
            add_action( 'add_meta_boxes_wtab', array( $this, 'category_tab_metabox' ), 10 );
            add_action( 'save_post',      array( $this, 'wtab_metabox_save' ), 10, 2 );
        }

        function global_tab_metabox() {
            add_meta_box( 'wtab-global-tab', __( 'Global Tab', 'wtab' ), array( $this, 'global_tab_metabox_func' ) );
        }
        
        function category_tab_metabox(){
            add_meta_box( 'wtab-cat-tag', __( 'Assign Category or Tag', 'wtab' ), array( $this, 'category_tab_metabox_func' ) );
        }

        //Create Global Tab Metabox.
        function global_tab_metabox_func() {

            wp_nonce_field( 'wtab_global_tab', 'wtab_global_tab' );
            wtab_checkbox( array( 'id'=>'wtab-global-tab', 'name'=>'wtab_global_tab' ) );
            wtab_labels( array( 'for'=>'wtab-global-tab', 'text'=> __( 'Is this tab global', 'wtab' ) ) );
        }

        /*
         * Category tag metabox
         */
        function category_tab_metabox_func( $post ) {

            wp_nonce_field( 'wtab_cat_tag', 'wtab_cat_tag' );
            $product_cats = get_terms( array( 'taxonomy'=>'product_cat', 'hierarchical'=>true, 'orderby'=>'term_group', 'order'=>'ASC' ) );
            $product_tags = get_terms( array( 'taxonomy'=>'product_tag', 'hierarchical'=>true ) );
            
            $selected_categories    =   get_post_meta( $post->ID, 'wtab_cat_tabs', true );
            $selected_tags          =   get_post_meta( $post->ID, 'wtab_tag_tabs', true );

            //Create product categories select box.
            $cat_arr = array();
            if( !empty($product_cats) && !is_wp_error($product_cats) ){
                
                foreach( $product_cats as $k=>$v ){
                    
                    $cat_arr[$k]['value'] = $v->term_id;
                    $cat_arr[$k]['label'] = $v->name;
                    $cat_arr[$k]['level'] = 0;

                    if( $v->parent != 0 ){
                        $ancestor = get_ancestors( $v->term_id, 'hierarchical taxonomy', 'taxonomy' );
                        $cat_arr[$k]['level'] = count($ancestor) + 1; 
                    }
                } ?>

                <p><?php
                    wtab_labels( array( 'for'=>'cat-select-tab', 'text'=> __( 'Select product category for which this tab should be assigned:', 'wtab' ) ) );
                    wtab_multiselectbox(array( 'name'=>'cat_select_tab', 'id'=>'cat-select-tab', 'options'=>$cat_arr, 'selected'=> (array)$selected_categories )); ?>
                </p><?php
            }

            //Create product tags select box.
            $tag_arr = array();
            if( !empty($product_tags) && !is_wp_error($product_tags) ){
                
                foreach( $product_tags as $k=>$v ){
                    
                    $tag_arr[$k]['level'] = 0;
                    $tag_arr[$k]['value'] = $v->term_id;
                    $tag_arr[$k]['label'] = $v->name;
                    
                    if( $v->parent !== 0 ){
                        $ancestor = get_ancestors( $v->term_id, 'hierarchical taxonomy', 'taxonomy' );
                        $tag_arr[$k]['level'] = count($ancestor) + 1; 
                    }

                }?>
                
                <p><?php
                    wtab_labels( array( 'for'=>'tag-select-tab', 'text'=> __( 'Select product category for which this tab should be assigned:', 'wtab' ) ) );
                    wtab_multiselectbox(array( 'name'=>'tag_select_tab', 'id'=>'tag-select-tab', 'options'=>$tag_arr, 'selected'=>(array)$selected_tags ));?>
                </p><?php
            }

        }
        
        /*
         * 
         */
        function wtab_metabox_save( $post_id, $post ){

            $global_nonce   =     isset( $_POST['wtab_global_tab'] ) ? $_POST['wtab_global_tab'] : '';
            $tag_nonce      =   isset( $_POST['wtab_cat_tag'] ) ? $_POST['wtab_cat_tag'] : '';

            //check if nonces are present
            if( empty($global_nonce) || empty( $tag_nonce ) ){
                return;
            }

            if ( ! wp_verify_nonce( $global_nonce, 'wtab_global_tab' ) || ! wp_verify_nonce( $tag_nonce, 'wtab_cat_tag' ) ) {
                return;
            }

            // Check if user has permissions to save data.
            if ( ! current_user_can( 'edit_post', $post_id ) ) {
                return;
            }
            
            // Check if not an autosave.
            if ( wp_is_post_autosave( $post_id ) ) {
                return;
            }

            // Check if not a revision.
            if ( wp_is_post_revision( $post_id ) ) {
                return;
            }

            //Everything is fine, now we can save.
            if( isset( $_POST['wtab_global_tab'] ) ){
                update_post_meta ( $post_id, 'wtab_is_global', 1 );
            }else{
                delete_post_meta( $post_id, 'wtab_is_global' );
            }
            
            if( !empty($_POST['cat_select_tab']) ){
                update_post_meta( $post_id, 'wtab_cat_tabs', $_POST['cat_select_tab'] );
            }else{
                delete_post_meta( $post_id, 'wtab_cat_tabs' );
            }

            if( !empty($_POST['tag_select_tab']) ){
                update_post_meta( $post_id, 'wtab_tag_tabs', $_POST['tag_select_tab'] );
            }else{
                delete_post_meta( $post_id, 'wtab_tag_tabs' );
            }
            
        }
        
    }

    new Wtab_Metaboxes();
}