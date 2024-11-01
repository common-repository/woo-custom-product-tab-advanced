<?php
/**
 * Plugin Name: WooCommerce custom product tab advanced
 * Plugin URI: http://sharethingz.com/
 * Description: A WooCommerce add-on to add custom product tabs to products page in most efficient way.
 * Version: 1.0.1
 * Author: Ankit Gade
 * Author URI: https://sharethingz.com/
 * Text Domain: wtab
 * Domain Path: /i18n/languages/
 *
 * @package WooCommerce
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if( !defined( 'WTAB_BASE' ) ){
    define( 'WTAB_BASE', dirname(__FILE__) );
}

if( !defined( 'WTAB_BASE_URL' ) ){
    define( 'WTAB_BASE_URL', plugins_url( basename( dirname(__FILE__) ) ) );
}

//Include main class
if( !class_exists('Wtab') ) {
    include_once dirname( __FILE__ ) . '/classes/class-wtab.php';
}

function Wtab(){
    return Wtab::instance();
}

// Global for backwards compatibility.
function wtab_instantiate(){
    $GLOBALS['wtab'] = Wtab();
}
add_action( 'plugins_loaded', 'wtab_instantiate' );