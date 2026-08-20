<?php

namespace WCS\Trial\Coupon;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Installer class
 */
class Installer {

    /**
     * Run the installer
     *
     * @return void
     */
    public function run() {
        $this->add_version();
    }

    /**
     * Add time and version on DB
     *
     * @return void
     */
    public function add_version() {
        $installed = get_option( 'wcs_trial_coupon_installed' );

        if ( ! $installed ) {
            update_option( 'wcs_trial_coupon_installed', time() );
        }

        update_option( 'wcs_trial_coupon_version', WCS_TRIAL_COUPON_VERSION );
    }
}
