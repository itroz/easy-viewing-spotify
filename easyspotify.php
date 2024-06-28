<?php
/*
Plugin Name: Easy Viewing for Spotify
Plugin URI: https://amostofi.com
Description: Easy viewing of Spotify audio files in WordPress through display in Gutenberg, Classic editors and Elementor.
Version: 1.2.1
Author: itroz
Author URI: https://itroz.com
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html
Domain Path: /languages
Text Domain: easy-viewing-spotify
*/

final class Easy_Spotify {

    // Plugin version
    const VERSION = '1.2.1';

    // Minimum Elementor Version
    const MINIMUM_ELEMENTOR_VERSION = '2.0.0';

    // Minimum PHP Version
    const MINIMUM_PHP_VERSION = '7.0';

    // Instance
    private static $_instance = null;

    /**
     * Singleton Instance Method
     */
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct() {
        // Define Constants
        $this->define_constants();

        // Initialize Plugin
        add_action( 'plugins_loaded', [ $this, 'init' ] );
    }

    /**
     * Define Plugin Constants
     */
    public function define_constants() {
        define( 'EASY_SPOTIFY_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
        define( 'EASY_SPOTIFY_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
    }

    /**
     * Initialize the plugin
     */
    public function init() {
        // Check if Elementor is active
        if ( ! did_action( 'elementor/loaded' ) ) {
            add_action( 'admin_notices', [ $this, 'admin_notice_missing_main_plugin' ] );
            return;
        }

        // Check Elementor version
        if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
            add_action( 'admin_notices', [ $this, 'admin_notice_minimum_elementor_version' ] );
            return;
        }

        // Check PHP version
        if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
            add_action( 'admin_notices', [ $this, 'admin_notice_minimum_php_version' ] );
            return;
        }

        // Initialize Widgets
        add_action( 'elementor/widgets/widgets_registered', [ $this, 'init_widgets' ] );
    }

    /**
     * Initialize Widgets
     */
    public function init_widgets() {
        require_once EASY_SPOTIFY_PLUGIN_PATH . 'widgets/spotify-widget.php';
    }

    /**
     * Admin Notice - Missing Main Plugin
     */
    public function admin_notice_missing_main_plugin() {
        if ( isset( $_GET['activate'] ) ) {
            unset( $_GET['activate'] );
        }

        $message = sprintf(
            esc_html__( '%1$s requires %2$s to be installed and activated. %3$s', 'easy-viewing-spotify' ),
            '<strong>' . esc_html__( 'Easy Viewing for Spotify', 'easy-viewing-spotify' ) . '</strong>',
            '<strong>' . esc_html__( 'Elementor', 'easy-viewing-spotify' ) . '</strong>',
            '<strong><br><a href="' . wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=elementor' ), 'install-plugin_elementor' ) . '" class="button-primary">' . esc_html__( 'Install Elementor', 'easy-viewing-spotify' ) . '</a></strong>'
        );

        printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
    }

    /**
     * Admin Notice - Minimum Elementor Version
     */
    public function admin_notice_minimum_elementor_version() {
        if ( isset( $_GET['activate'] ) ) {
            unset( $_GET['activate'] );
        }

        $message = sprintf(
            esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'easy-viewing-spotify' ),
            '<strong>' . esc_html__( 'Easy Viewing for Spotify', 'easy-viewing-spotify' ) . '</strong>',
            '<strong>' . esc_html__( 'Elementor', 'easy-viewing-spotify' ) . '</strong>',
            self::MINIMUM_ELEMENTOR_VERSION
        );

        printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
    }

    /**
     * Admin Notice - Minimum PHP Version
     */
    public function admin_notice_minimum_php_version() {
        if ( isset( $_GET['activate'] ) ) {
            unset( $_GET['activate'] );
        }

        $message = sprintf(
            esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'easy-viewing-spotify' ),
            '<strong>' . esc_html__( 'Easy Viewing for Spotify', 'easy-viewing-spotify' ) . '</strong>',
            '<strong>' . esc_html__( 'PHP', 'easy-viewing-spotify' ) . '</strong>',
            self::MINIMUM_PHP_VERSION
        );

        printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
    }

}

// Initialize the plugin
Easy_Spotify::instance();
