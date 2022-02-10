<?php
/*
 * Plugin Name: Extended Trial Coupon for WC Subscription
 * Plugin URI: https://codeheaven.ca
 * Description: WCS Trial Coupon will add option in WooCommerce coupon filed. With this plugin you can provide extra amount of time on trial period while purchasing a subscription from your store.
 * Version: 1.0
 * Author: Code Heaven
 * Author URI: https://codeheaven.ca/
 * License: GPLv2
 * License URI: https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html
 * Text Domain: wcs-trial-coupon
 * Domain Path: /languages
 * Tags: Woocommerce Subscription trial coupon, Trial Coupon, Free Trial Coupon, Woo subscription free trial coupon, Extends Free trial
 */

if ( !defined( 'ABSPATH' ) ) {
    exit;
}

require_once __DIR__ . '/vendor/autoload.php';
/*
 * Main plugin class
 */
final class WCS_Trial_Coupon {

    /*
     * Plugin version
     *
     * $var string
     */
    const version = '1.0';

    /*
     * Plugin constructor
     */
    private function __construct() {
        $this->define_constants();

        register_activation_hook( __FILE__, [ $this, 'activate' ] );

        add_action( 'plugins_loaded', [ $this, 'init_plugin' ] );
    }

    /**
     * Initializes a singleton instance
     *
     * @return \WCS_Trial_Coupon
     */
    public static function init() {
        static $instance = false;

        if ( ! $instance ) {
            $instance = new self();
        }

        return $instance;
    }

    /**
     * Define the required plugin constants
     *
     * @return void
     */
    public function define_constants() {
        define( 'WCS_TRIAL_COUPON_VERSION', self::version );
        define( 'WCS_TRIAL_COUPON_FILE', __FILE__ );
        define( 'WCS_TRIAL_COUPON_PATH', __DIR__ );
        define( 'WCS_TRIAL_COUPON_URL', plugins_url( '', WCS_TRIAL_COUPON_FILE ) );
        define( 'WCS_TRIAL_COUPON_ASSETS', WCS_TRIAL_COUPON_URL . '/assets' );
    }

    /**
     * Initialize the plugin
     *
     * @return void
     */
    public function init_plugin() {

        $checkWC   = in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option('active_plugins') ) );
        $checkWCS  = in_array( 'woocommerce-subscriptions/woocommerce-subscriptions.php', apply_filters( 'active_plugins', get_option('active_plugins') ) );

        if ( ! $checkWC || ! $checkWCS ) {
            $admin_notice = new WCS\Trial\Coupon\Admin_Notice();
            add_action( 'admin_notices', [ $admin_notice, 'check_require_plugin_notice' ] );
        }

        new WCS\Trial\Coupon\Assets();
        new WCS\Trial\Coupon\Trial_Coupon_Actions();
    }

    /**
     * Do stuff upon plugin activation
     *
     * @return void
     */
    public function activate() {

        $installer = new WCS\Trial\Coupon\Installer();
        $installer->run();
    }
}

/**
 * Initializes the main plugin
 *
 * @return \WCS_Trial_Coupon
 */
function wcs_trial_coupon() {
    return WCS_Trial_Coupon::init();
}

// kick-off the plugin
wcs_trial_coupon();