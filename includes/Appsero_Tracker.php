<?php

namespace WCS\Trial\Coupon;

use Appsero\Client;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

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
            '987bfed3-d32e-499b-ba77-d9750f808811',
            'Extended Trial Coupon for WC Subscription',
            WCS_TRIAL_COUPON_FILE
        );

        // Active insights
        $client->insights()->init();
    }
}
