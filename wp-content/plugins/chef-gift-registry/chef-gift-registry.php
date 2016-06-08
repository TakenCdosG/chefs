<?php
/*
Plugin Name: Chef Gift Registry
Plugin URI: http://adrian-morelos.github.io
Description: A shortcodes plugin with support of Gift Registry for Chef
Author: Adrián Morelos
Author URI: http://adrian-morelos.github.io
Version: 1.0.0
License: GNU General Public License version 2.0
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

if ( ! class_exists( 'ChefGiftRegistry' ) ) {

    class ChefGiftRegistry {

        /**
         * Main Constructor
         *
         * @since  1.0.0
         * @access public
         */
        function __construct() {

            // Define path
            $this->dir_path = plugin_dir_path( __FILE__ );

            // Register de-activation hook
            register_deactivation_hook( __FILE__, array( $this, 'on_deactivation' ) );

            // Actions
            add_action( 'wp_enqueue_scripts', array( $this, 'load_scripts' ) );
            add_action( 'plugins_loaded', array( $this, 'load_text_domain' ) );
            add_action( 'plugins_loaded', array( $this, 'constants' ) );
            add_action( 'plugins_loaded', array( $this, 'init_query_variables') );

            add_filter( 'query_vars', array( $this, 'init_query_variables'), 10, 1 );

            // Includes (useful functions and classes)
            require_once( $this->dir_path .'/inc/commons.php' );

            // The actual shortcodes
            require_once( $this->dir_path .'/shortcodes/shortcodes.php' );
   

        }


        /**
         * Init Query Variables
         *
         * @since  1.0.0
         * @access public
         */
        function init_query_variables($vars) {
            $vars[] = 'registrant-name'; // faq is the name of variable you want to add
            $vars[] = 'co-registrant-name';
            $vars[] = 'registrant-email';
            $vars[] = 'co-registrant-email';
            $vars[] = 'event-name';
            $vars[] = 'event-type';
            $vars[] = 'registry-no';
            $vars[] = 'event-date';
            return $vars;
        }

        /**
         * Load Text Domain for translations
         *
         * @since  1.0.0
         * @access public
         */
        function load_text_domain() {
            load_plugin_textdomain( 'chef_gift_registry', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/');
        }

        /**
         * Define Constants
         *
         * @since   1.0.0
         * @access  public
         */
        public function constants() {
            //define( 'CONSTANT', 'VALUE' );
        }


        /**
         * Registers/Enqueues all scripts and styles
         *
         * @since  2.0.0
         * @access public
         */
        function load_scripts() {

            // Define js directory
            $js_dir = plugin_dir_url( __FILE__ ) . 'shortcodes/js/';

            // Define CSS directory
            $css_dir = plugin_dir_url( __FILE__ ) . 'shortcodes/css/';

            // JS
            wp_register_script( 'chef-gift-registry', $js_dir . 'chef-gift-registry.js', array ( 'jquery'), '1.0', true );

            // CSS
            wp_enqueue_style( 'chef-gift-registry', $css_dir . 'chef-gift-registry.css' );
        }

        /**
         * Run on plugin de-activation
         *
         * @since 1.0.0
         */
        public function on_deactivation() {


        }


        /**
         * Adds classes to the body tag
         *
         * @since 1.0.0
         */
        public function body_class( $classes ) {
            $classes[] = 'chef-gift-registry ';
            $responsive = apply_filters( 'chef_gift_registry', true );
            if ( $responsive ) {
                $classes[] = 'chef-gift-registry';
            }
            return $classes;
        }
    }

    // Start things up
    $ChefGiftRegistry = new ChefGiftRegistry();
}