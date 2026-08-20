<?php
/**
 * Uninstall handler.
 *
 * @package WCS_Trial_Coupon
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}

delete_option( 'wcs_trial_coupon_installed' );
delete_option( 'wcs_trial_coupon_version' );
delete_option( 'wcs_spare_me' );
delete_option( 'wcs_remind_me' );
delete_option( 'wcs_rated' );
