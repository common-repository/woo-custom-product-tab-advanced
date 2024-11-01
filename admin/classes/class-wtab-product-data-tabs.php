<?php
//prevent direct access
if( !defined('ABSPATH') ){
    exit;
}

if( !class_exists('Wtab_Product_Data_Tabs') ){

    class Wtab_Product_Data_Tabs {

        /*
         * Holds custom tabs for products
         */
        public $custom_tabs;

        /*
         * Constructor
         */
        function __construct() {
            add_action( 'admin_enqueue_scripts', array($this, 'add_scripts_styles') );
            add_action( 'admin_footer', array($this, 'saved_tab_dialog') );
            add_filter( 'woocommerce_product_data_tabs', array($this, 'wtab_admin_custom_tab') );
            add_action( 'woocommerce_product_data_panels', array( $this, 'wtab_custom_tab_panel' ) );
            add_action( 'save_post', array( $this, 'save_tab_content' ), 10, 3 );
        }

        /*
         * 
         */
        function add_scripts_styles(){
            global $wtab;

            wp_enqueue_editor();
            wp_enqueue_script('jquery-ui-dialog');
            wp_enqueue_style ('jquery-ui-dialog');

            wp_enqueue_script(  'wtab-admin-shared', trailingslashit(WTAB_BASE_URL).'admin/assets/js/wtab-admin-shared.js', array('jquery', 'jquery-ui-dialog'), $wtab->version, false );
            wp_register_script( 'wtab-admin-script', trailingslashit(WTAB_BASE_URL).'admin/assets/js/woocommerce-custom-tab-advanced-admin.js', array('jquery', 'jquery-ui-dialog', 'wtab-admin-shared'), $wtab->version, false );
            $markup = $this->generate_tab_html();
            wp_localize_script( 'wtab-admin-script', 'wtab_markup', $markup );
            wp_enqueue_script('wtab-admin-script');

            //Enqueue css
            wp_enqueue_style(   'wtab-admin-style',  trailingslashit(WTAB_BASE_URL).'admin/assets/css/admin-style.css' );
        }

        /*
         * Add tab to product data metabox
         */
         function wtab_admin_custom_tab( $product_data_tabs ){

            $product_data_tabs['wtab-admin-custom-tab'] = array(
                        'label' => __( 'Custom Tabs', 'wtab' ), 
                        'target' => 'wtab_custom_product_data', 
                        'class' => array( 'show_if_variable', 'show_if_simple' ),
                        'priority' => rand(PHP_INT_MAX-1, PHP_INT_MAX)
            );

             return $product_data_tabs;
         }
         
         /*
          * Get custom tabs for any product
          */
         function get_custom_tabs( $product_id ){
             
             return $product_tabs = new WP_Query( array( 'post_type'=>'wtab_custom_tab', 'post_parent'=> $post->ID, 'order'=>'ASC', 'orderby'=>'menu_order' ) ); 
         }

         /*
          * Add markup to the product data panel in tab.
          */
         function wtab_custom_tab_panel(){
             global $post;

             $product_tabs = $this->get_custom_tabs( $post->ID ); ?>

                <div id="wtab_custom_product_data" class="panel woocommerce_options_panel">
                    <div id="wtab-panel-tabs" class="options_group clone-wrapper"><?php

                        if( $product_tabs->have_posts() ){
                            
                            $iterator = 0;
                            
                            while( $product_tabs->have_posts() ){
                                
                                $product_tabs->the_post();
                                $markup = $this->generate_tab_html();

                                $title      =   get_the_title();
                                $content    =   apply_filters( 'the_content', get_the_content() );

                                $markup = str_replace( '{tab_title}', $title, $markup);
                                $markup = str_replace( '{tab_desc}', $content, $markup);
                                $markup = str_replace( '{length}', $iterator, $markup);
                                
                                echo $markup;
                                
                                $iterator++;
                            }
                            wp_reset_postdata();
                        }?>

                    </div>
                    
                    <div class="wtab-no-tab aligncenter">
                        <a href="#" class="button button-primary wtab-button wtab-add-row"><?php _e( 'Add Tab', 'wtab' ); ?></a>
                        <!--<a href="#" class="button button-primary wtab-button wtab-add-saved"><?php //_e( 'Add Saved Tab', 'wtab' ); ?></a>-->
                    </div>
                    
                </div><?php
         }

         function generate_tab_html(){
            ob_start(); ?>

            <div data-length="{length}" class="wtab-row toclone">
                <p class="form-field wtab_text_field_{length}_field ">
                    <label for="wtab_text_field_{length}"><?php _e( 'Tab Title', 'wtab' ); ?></label>
                    <input type="text" class="wtab-text-title" name="wtab_text_field[]" id="wtab_text_field_{length}" value="{tab_title}" placeholder="<?php _e('Enter custom tab title here', 'wtab'); ?>"
                </p>

                <p class="form-field wtab_desc_field_{length}_field ">
                    <label for="wtab_desc_field_{length}"><?php _e( 'Tab Description', 'wtab' ); ?></label>
                    <textarea class="wtab-desc-textarea" name="wtab_desc_field[]" id="wtab_desc_field_{length}" placeholder="<?php _e('Enter cusom tab description', 'wtab'); ?>" rows="2" cols="20">{tab_desc}</textarea>
                </p>
                
                <div class="wtab-add-remove">
                    <a href="#" data-length="{length}" class="button wtab-button wtab-remove-row"><?php _e( 'Remove', 'wtab' ); ?></a>
                    <a href="#" data-length="{length}" class="button wtab-button wtab-move-up"><?php _e( 'Move Up', 'wtab' ); ?></a>
                    <a href="#" data-length="{length}" class="button wtab-button wtab-move-down"><?php _e( 'Move Down', 'wtab' ); ?></a>
                </div>

            </div><?php
            
            $markup = ob_get_clean();
            
            return $markup;
         }

         /*
          * 
          */
         function saved_tab_dialog(){

            $args = apply_filters( 'wtab_fetch_tabs_args', array( 'post_type'=> 'wtab', 'post_status'=>'publish', 'posts_per_page'=> -1 ) );
            $tabs = new WP_Query( $args );?>

            <div id="wtab-dialog" class="wtab-hide" title="<?php _e( 'Select saved tabs', 'wtab' ); ?>"><?php

                if( !empty( $tabs->have_posts() ) ){?>
                
                <form action="/" method="post">

                    <select style="width: 90%; text-align: center;" name="set_saved_tab"><?php

                        while( $tabs->have_posts() ){

                            $tabs->the_post();?>

                            <option value="<?php echo get_the_ID(); ?>"><?php echo get_the_title(); ?></option><?php

                        }
                        wp_reset_postdata();?>

                    </select>

                    <a style="display: inline-block;" class="button wtab-add-row-saved wtab-add-row" href="#"><?php _e( 'Add Tab', 'wtab' ); ?></a>

                </form><?php
                } ?>
            </div><?php
         }
         
         /*
          * Save tab content.
          */
         function save_tab_content( $post_id, $post, $update ){

             $post_type = get_post_type($post_id);

             //Check if user has capability to edit posts
             if( !current_user_can('edit_posts') ){
                 return;
             }

             //Check if post type is product
             if( 'product' !== $post_type ){
                 return;
             }

             //Ensure that post is not being auto-saved
             if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ){
                 return;
             }

             //Ensure that post is not revision
             if( wp_is_post_revision($post_id) ){
                 return;
             }

             //Delete every tab before updating new ones
             $product_tabs = $this->get_custom_tabs($post_id);

             if( $product_tabs->have_posts() ){
                 while( $product_tabs->have_posts() ){
                     $product_tabs->the_post();
                     wp_delete_post(get_the_ID());
                 }
                 wp_reset_postdata();
             }

             //Everything seems alright, save meta data
             if( !empty($_POST['wtab_text_field']) && is_array($_POST['wtab_text_field']) ){

                 foreach( $_POST['wtab_text_field'] as $key=>$value ){

                     $value = trim($value);

                     if( empty($value) ){
                         continue;
                     }

                     $data = array();

                     $data['post_title'] = $value;
                     $data['post_name'] = sanitize_title($value);
                     $data['post_content'] = wp_kses_post($_POST['wtab_desc_field'][$key]);
                     $data['post_type'] = 'wtab_custom_tab';
                     $data['post_status'] = 'publish';
                     $data['menu_order'] = $key;
                     $data['comment_status'] = 'closed';
                     $data['post_parent'] = $post_id;                     

                     wp_insert_post( $data );
                 }
             }
         }
    }

    $GLOBALS['wtab_product_data_tabs'] = new Wtab_Product_Data_Tabs();
}