<?php
//prevent direct access of file.
if( !defined('ABSPATH') ) {
    exit;
}

if( !class_exists('Wtab') ) {

    class Wtab {

        /*
         * Version of plugin
         */
        public $version = '1.0.1';
        
        /*
         * Single instance of the class
         */
        protected static $_instance = null;

        //Constructor
        function __construct() {

            $this->includes();
            $this->load_plugin_textdomain();

            add_action( 'init', array( 'Wtab_Post_Type', 'wtab_reg_post_type' ) );
            add_filter( 'enter_title_here', array( $this, 'wtab_title_here' ), 10, 2 );
            add_filter( 'edit_form_after_title', array( $this, 'wtab_after_form_title' ), 10, 2 );
        }

        //Instantiate the class
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
        
        function load_plugin_textdomain(){
            load_plugin_textdomain( 'wtab', false, WTAB_BASE . DIRECTORY_SEPARATOR .'languages' );
        }

        /*
         * Includes necessary classes and libraries
         */
        function includes(){

            include_once trailingslashit(WTAB_BASE).'includes/wtab-markup.php';
            include_once trailingslashit(WTAB_BASE).'classes/class-post-type.php';
            include_once trailingslashit(WTAB_BASE).'classes/class-meta-boxes.php';
            include_once trailingslashit(WTAB_BASE).'classes/class-wtab-manager.php';
            include_once trailingslashit(WTAB_BASE).'admin/classes/class-wtab-product-data-tabs.php';
        }

        /*
         * Title placeholder
         */
        function wtab_title_here( $title, $post ){

            if( $post->post_type === 'wtab' ){
                return __( 'Enter custom tab title here' );
            }

            return $title;
        }

        /*
         * 
         */
        function wtab_after_form_title( $post ){

            if( $post->post_type === 'wtab' ){ ?>
                <h3 style="margin-bottom: 0;"><?php _e( 'Enter Custom Tab Description', 'wtab' ); ?></h3><?php
            }
        }
    }
}