<?php
/*
Plugin Name:  Easy Viewing for Spotify
Plugin URI:   https://itroz.com
Description:  Easy viewing of Spotify audio files in WordPress through display in Gutenberg, Classic editors and Elementor .
Version:      0.1
Author:       itroz
Author URI:   https://amostofi.com
License:      GPLv2
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
Domain Path: /languages
Text Domain:  easy-viewing-spotify
*/

final class easy_spotify {

    // Plugin version
    const VERSION = '1.1.0';

    // Minimum Elementor Version
    const MINIMUM_ELEMENTOR_VERSION = '2.0.0';

    // Minimum PHP Version
    const MINIMUM_PHP_VERSION = '5.0';

    // Instance
    private static $_instance = null;

    /**
     * SIngletone Instance Method
     * @since 1.1.0
     */
    public static function instance() {
        if( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct() {
        // Call Constants Method
        $this->define_constants();
        // add_action( 'wp_enqueue_scripts', [ $this, 'scripts_styles' ] );
        add_action( 'plugins_loaded', [ $this, 'init' ] );
    }

    /**
     * Define Plugin Constants
     * @since 1.1.0
     */
    public function define_constants() {
        define( 'EASY_SPOTIFY_PLUGIN_URL', trailingslashit( plugins_url( '/', __FILE__ ) ) );
        define( 'EASY_SPOTIFY_PLUGIN_PATH', trailingslashit( plugin_dir_path( __FILE__ ) ) );
    }

    public function init() {
        // Check if the ELementor installed and activated
        if( ! did_action( 'elementor/loaded' ) ) {
            add_action( 'admin_notices', [ $this, 'admin_notice_missing_main_plugin' ] );
            return;
        }

        if( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
            add_action( 'admin_notices', [ $this, 'admin_notice_minimum_elementor_version' ] );
            return;
        }

        if( ! version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '>=' ) ) {
            add_action( 'admin_notices', [ $this, 'admin_notice_minimum_php_version' ] );
            return;
        }
        // add_action( 'admin_notices', [ $this, 'admin_notice_pay' ] );
        // add_action( 'elementor/init', [ $this, 'init_category' ] );
        add_action( 'elementor/widgets/widgets_registered', [ $this, 'init_widgets' ] );
    }

    /**
     * Init Widgets
     * @since 1.0.0
     */
    public function init_widgets() {
        require_once EASY_SPOTIFY_PLUGIN_PATH . '/widgets/spotify-widget.php';
    }

    public function admin_notice_missing_main_plugin() {

        $plugin = 'elementor/elementor.php';
        $elementorPath = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=elementor' ), 'install-plugin_elementor' );
        if( isset( $_GET[ 'activate' ] ) ) unset( $_GET[ 'activate' ] );
        $message = sprintf(
            e_( '%1$s requires %2$s to be installed and activated %3$s'),
            '<strong>'.e_( 'Easy spotify').'</strong>',
            '<strong>'.e_( 'Elementor' ).'</strong>',
            '<strong>'.e_(' <br><a href="'. $elementorPath .'" class="button-primary"> install Elementor</a>'). '</strong>',
        );

        printf( '<div class="notice notice-warning is-dimissible"><p>%1$s</p></div>', $message );
    }

    /**
     * Admin Notice
     * Warning when the site doesn't have a minimum required Elementor version.
     * @since 1.0.0
     */
    public function admin_notice_minimum_elementor_version() {
        if( isset( $_GET[ 'activate' ] ) ) unset( $_GET[ 'activate' ] );
        $message = sprintf(
            e_( '"%1$s" requires "%2$s" version %3$s or greater'),
            '<strong>'.e_( 'Easy Viewing for Spotify').'</strong>',
            '<strong>'.e_( 'Elementor').'</strong>',
            self::MINIMUM_ELEMENTOR_VERSION
        );

        printf( '<div class="notice notice-warning is-dimissible"><p>%1$s</p></div>', $message );
    }

    /**
     * Admin Notice
     * Warning when the site doesn't have a minimum required PHP version.
     * @since 1.0.0
     */
    public function admin_notice_minimum_php_version() {
        if( isset( $_GET[ 'activate' ] ) ) unset( $_GET[ 'activate' ] );
        $message = sprintf(
            e_( '"%1$s" requires "%2$s" version %3$s or greater', 'easy-viewing-spotify' ),
            '<strong>'.e_( 'easy-spotify').'</strong>',
            '<strong>'.e_( 'PHP').'</strong>',
            self::MINIMUM_PHP_VERSION
        );

        printf( '<div class="notice notice-warning is-dimissible"><p>%1$s</p></div>', $message );
    }

}
include_once 'admin/admin.php';
?>