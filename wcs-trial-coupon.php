<?php
/**
 * Plugin Name: Extended Trial Coupon for WC Subscription
 * Plugin URI: https://wordpress.org/plugins/extended-trial-coupon-for-wc-subscription/
 * Description: WCS Trial Coupon will add option in WooCommerce coupon filed. With this plugin you can provide extra amount of time on trial period while purchasing a subscription from your store.
 * Version: 1.7.1
 * Author: Betatech
 * Author URI: https://betatech.co/
 * License: GPLv2
 * License URI: https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html
 * Text Domain: extended-trial-coupon-for-wc-subscription
 * Domain Path: /languages
 * Requires at least: 5.7
 * Tested up to: 7.1
 * Requires PHP: 8.0
 * Requires Plugins: woocommerce
 * WC requires at least: 8.0
 * WC tested up to: 11.0.1
 * Tags: Woocommerce Subscription trial coupon, Trial Coupon, Free Trial Coupon, Woo subscription free trial coupon, Extends Free trial
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Declare WooCommerce feature compatibility before WooCommerce initializes.
 * Coupons remain a CPT; this plugin does not query orders via post meta.
 */
add_action(
    'before_woocommerce_init',
    function () {
        if ( ! class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
            return;
        }

        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'cart_checkout_blocks', __FILE__, true );
    }
);

require_once __DIR__ . '/vendor/autoload.php';

/**
 * Main plugin class
 */
final class WCS_Trial_Coupon {

    /**
     * Plugin version
     *
     * @var string
     */
    const version = '1.7.1';

    /**
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
        load_plugin_textdomain(
            'extended-trial-coupon-for-wc-subscription',
            false,
            dirname( plugin_basename( WCS_TRIAL_COUPON_FILE ) ) . '/languages/'
        );

        // Check if WooCommerce is active.
        if ( ! class_exists( 'WooCommerce' ) ) {
            add_action(
                'admin_notices',
                function () {
                    echo '<div class="notice notice-error is-dismissible"><p>';
                    esc_html_e( 'Extended Trial Coupon for WC Subscription requires WooCommerce to be activated.', 'extended-trial-coupon-for-wc-subscription' );
                    echo '</p></div>';
                }
            );
            return;
        }

        // Check if WooCommerce Subscriptions is active.
        if ( ! class_exists( 'WC_Subscriptions' ) ) {
            add_action(
                'admin_notices',
                function () {
                    echo '<div class="notice notice-error is-dismissible"><p>';
                    esc_html_e( 'Extended Trial Coupon for WC Subscription requires WooCommerce Subscriptions to be activated.', 'extended-trial-coupon-for-wc-subscription' );
                    echo '</p></div>';
                }
            );
            return;
        }

        $appsero = new WCS\Trial\Coupon\Appsero_Tracker();
        $appsero->init();

        new WCS\Trial\Coupon\Assets();
        new WCS\Trial\Coupon\Trial_Coupon_Actions();
        new WCS\Trial\Coupon\Reviews();
    }

    /**
     * Do stuff upon plugin activation
     *
     * @return void
     */
    public function activate() {
        // Check if WooCommerce is active.
        if ( ! class_exists( 'WooCommerce' ) ) {
            wp_die(
                esc_html__( 'Extended Trial Coupon for WC Subscription requires WooCommerce to be activated before you can activate this plugin.', 'extended-trial-coupon-for-wc-subscription' ),
                esc_html__( 'Plugin Activation Error', 'extended-trial-coupon-for-wc-subscription' ),
                [ 'back_link' => true ]
            );
        }

        // Check if WooCommerce Subscriptions is active.
        if ( ! class_exists( 'WC_Subscriptions' ) ) {
            wp_die(
                esc_html__( 'Extended Trial Coupon for WC Subscription requires WooCommerce Subscriptions to be activated before you can activate this plugin.', 'extended-trial-coupon-for-wc-subscription' ),
                esc_html__( 'Plugin Activation Error', 'extended-trial-coupon-for-wc-subscription' ),
                [ 'back_link' => true ]
            );
        }

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
