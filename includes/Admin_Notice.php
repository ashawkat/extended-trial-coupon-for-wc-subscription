<?php

namespace WCS\Trial\Coupon;

class Admin_Notice {

    /**
     * Print notices if required plugins are not installed or active
     * @return void
     */
    public function check_require_plugin_notice(){

        $wc_title = __('WooCommerce', 'wcs-trial-coupon' );
        $wc_url   = wp_nonce_url( 'https://wordpress.org/plugins/woocommerce/' );

        $wcs_title = __('WooCommerce Subscription', 'happy-elementor-addons');
        $wcs_url   = wp_nonce_url( 'https://woocommerce.com/products/woocommerce-subscriptions/' );

        $notice = sprintf(
            /* translators: 1: Plugin name 2: WC title & installation link 3: WCS title & installation link */
            __('%1$s requires %2$s & %3$s to be installed and activated to function properly.', 'wcs-trial-coupon'),
            '<strong>' . __( 'Trial Coupon for WooCommerce Subscription', 'wcs-trial-coupon' ) . '</strong>',
            '<a href="' . esc_url( $wc_url ) . '" target="_blank">' . $wc_title . '</a>',
            '<a href="' . esc_url( $wcs_url ) . '" target="_blank">' . $wcs_title . '</a>'
        );

        printf('<div class="notice notice-warning is-dismissible"><p style="padding: 13px 0">%1$s</p></div>', $notice);
    }
}