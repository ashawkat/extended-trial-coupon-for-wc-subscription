<?php

namespace WCS\Trial\Coupon;

/**
 * Assets handlers class
 */
class Assets {

    /**
     * Class constructor
     */
    function __construct() {
        add_action( 'wp_enqueue_scripts', [ $this, 'register_assets' ] );
        add_action( 'admin_enqueue_scripts', [ $this, 'register_assets' ] );
    }

    /**
     * All available scripts
     *
     * @return array
     */
    public function get_scripts() {
        return [
            'wcs-coupon-metabox' => [
                'src'     => WCS_TRIAL_COUPON_ASSETS . '/js/coupon-metabox.js',
                'version' => filemtime( WCS_TRIAL_COUPON_PATH . '/assets/js/coupon-metabox.js' ),
                'deps'    => [ 'jquery' ]
            ],
        ];
    }

    /**
     * All available styles
     *
     * @return array
     */
    public function get_styles() {
        return [
            'wcs-coupon-admin-style' => [
                'src'     => WCS_TRIAL_COUPON_ASSETS . '/css/admin.css',
                'version' => filemtime( WCS_TRIAL_COUPON_PATH . '/assets/css/admin.css' ),
            ]
        ];
    }

    /**
     * Register scripts and styles
     *
     * @return void
     */
    public function register_assets() {
        $scripts = $this->get_scripts();
        $styles  = $this->get_styles();

        foreach ( $scripts as $handle => $script ) {
            $deps = isset( $script['deps'] ) ? $script['deps'] : false;

            wp_register_script( $handle, $script['src'], $deps, $script['version'], true );
        }

        foreach ( $styles as $handle => $style ) {
            $deps = isset( $style['deps'] ) ? $style['deps'] : false;

            wp_register_style( $handle, $style['src'], $deps, $style['version'] );
        }
    }
}