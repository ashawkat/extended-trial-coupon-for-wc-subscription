<?php

namespace WCS\Trial\Coupon;

use Appsero\Client;

/**
 * Appsero Tracker class
 */
class Appsero_Tracker {

    /**
     * Initialize the tracker
     *
     * @return void
     */
    public function init() {
        // Don't track if the Appsero Client class doesn't exist
        if ( ! class_exists( 'Appsero\Client' ) ) {
            return;
        }

        $client = new Client(
            '987bfed3-d32e-499b-ba77-d9750f808811',  // Your Hash/Project ID from Appsero
            'Extended Trial Coupon for WC Subscription',  // Plugin Name
            WCS_TRIAL_COUPON_FILE  // Main plugin file
        );

        // Active insights
        $client->insights()->init();

        // Optional: Active automatic updates (if you have pro/premium version)
        // $client->updater();

        // Optional: Active license management (if you have pro/premium version)
        // $client->license()->add_settings_page();
    }
}
