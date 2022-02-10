<?php

namespace WCS\Trial\Coupon;

/**
 * Installer class
 */
class Installer {

    /**
     * Run the installer
     * @return void
     */
    public function run() {
        $this->add_version();
        $this->load_plugin_textdomain();
    }

    /**
     * Add time and version on DB
     */
    public function add_version(){
        $installed = get_option( 'wcs_trial_coupon_installed' );

        if ( ! $installed ) {
            update_option( 'wcs_trial_coupon_installed', strtotime("now" ) );
        }

        update_option( 'wcs_trial_coupon_version', WCS_TRIAL_COUPON_VERSION );
    }

    /**
     * Load plugin text domain
     * @return void
     */
    public function load_plugin_textdomain() {

        load_plugin_textdomain(
            'wcs-trial-coupon',
            false,
            WCS_TRIAL_COUPON_URL . '/languages/'
        );

    }
}
