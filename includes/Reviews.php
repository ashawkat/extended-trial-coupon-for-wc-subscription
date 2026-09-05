<?php

namespace WCS\Trial\Coupon;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Reviews {

    /**
     * Review notice action nonce name.
     *
     * @var string
     */
    const NONCE_ACTION = 'wcs_trial_coupon_review';

    /**
     * Hook into WordPress.
     */
    public function __construct() {
        self::init();
    }

    /**
     * Register admin hooks once.
     *
     * @return void
     */
    public static function init() {
        static $initialized = false;

        if ( $initialized ) {
            return;
        }

        $initialized = true;

        add_action( 'admin_init', [ __CLASS__, 'wcs_void_check_installation_time' ] );
        add_action( 'admin_init', [ __CLASS__, 'wcs_void_spare_me' ], 5 );
    }

    /**
     * Check if review notice should be shown.
     *
     * @return void
     */
    public static function wcs_void_check_installation_time() {
        if ( ! self::user_can_manage() ) {
            return;
        }

        $letalone = get_option( 'wcs_spare_me', '0' );

        if ( '1' === $letalone || '3' === $letalone ) {
            return;
        }

        $install_date = get_option( 'wcs_trial_coupon_installed', time() );
        $past_date    = strtotime( '-10 days' );

        $remind_time = get_option( 'wcs_remind_me', time() );
        $remind_due  = strtotime( '+15 days', (int) $remind_time );
        $now         = time();

        if ( $now >= $remind_due ) {
            add_action( 'admin_notices', [ __CLASS__, 'wcs_void_grid_display_admin_notice' ] );
        } elseif ( ( $past_date >= (int) $install_date ) && '2' !== $letalone ) {
            add_action( 'admin_notices', [ __CLASS__, 'wcs_void_grid_display_admin_notice' ] );
        }
    }

    /**
     * Display admin notice, asking for a review.
     *
     * @return void
     */
    public static function wcs_void_grid_display_admin_notice() {
        if ( ! self::user_can_manage() ) {
            return;
        }

        global $pagenow;

        $exclude = [
            'themes.php',
            'users.php',
            'tools.php',
            'options-general.php',
            'options-writing.php',
            'options-reading.php',
            'options-discussion.php',
            'options-media.php',
            'options-permalink.php',
            'options-privacy.php',
            'edit-comments.php',
            'upload.php',
            'media-new.php',
            'admin.php',
            'import.php',
            'export.php',
            'site-health.php',
            'export-personal-data.php',
            'erase-personal-data.php',
        ];

        if ( in_array( $pagenow, $exclude, true ) ) {
            return;
        }

        wp_enqueue_style( 'wcs-coupon-admin-style' );

        $dont_disturb = wp_nonce_url( add_query_arg( 'wcs_tc_spare', '1', self::wcs_current_admin_url() ), self::NONCE_ACTION );
        $remind_me    = wp_nonce_url( add_query_arg( 'wcs_tc_remind', '1', self::wcs_current_admin_url() ), self::NONCE_ACTION );
        $rated        = wp_nonce_url( add_query_arg( 'wcs_tc_rated', '1', self::wcs_current_admin_url() ), self::NONCE_ACTION );
        $reviewurl    = 'https://wordpress.org/support/plugin/extended-trial-coupon-for-wc-subscription/reviews/?rate=5#new-post';

        echo '<div class="notice wcs-review-notice wcs-review-notice--extended">';
        echo '<div class="wcs-review-notice__content">';
        echo '<h3>' . esc_html__( 'Enjoying Extended Trial Coupon for WC Subscription?', 'extended-trial-coupon-for-wc-subscription' ) . '</h3>';
        echo '<p>' . esc_html__( 'Thank you for choosing Extended Trial Coupon for WC Subscription. If you have found our plugin useful and makes you smile, please consider giving us a 5-star rating on WordPress.org. It would mean the world to us.', 'extended-trial-coupon-for-wc-subscription' ) . '</p>';
        echo '<div class="wcs-review-notice__actions">';
        echo '<a href="' . esc_url( $reviewurl ) . '" class="wcs-review-button wcs-review-button--cta" target="_blank" rel="noopener noreferrer"><span>' . esc_html__( '👍 Yes, You Deserve It!', 'extended-trial-coupon-for-wc-subscription' ) . '</span></a>';
        echo '<a href="' . esc_url( $rated ) . '" class="wcs-review-button wcs-review-button--cta wcs-review-button--outline"><span>' . esc_html__( '🙌 Already Rated!', 'extended-trial-coupon-for-wc-subscription' ) . '</span></a>';
        echo '<a href="' . esc_url( $remind_me ) . '" class="wcs-review-button wcs-review-button--cta wcs-review-button--outline"><span>' . esc_html__( '🔔 Remind Me Later', 'extended-trial-coupon-for-wc-subscription' ) . '</span></a>';
        echo '<a href="' . esc_url( $dont_disturb ) . '" class="wcs-review-button wcs-review-button--cta wcs-review-button--error wcs-review-button--outline"><span>' . esc_html__( '💔 No Thanks', 'extended-trial-coupon-for-wc-subscription' ) . '</span></a>';
        echo '</div></div></div>';
    }

    /**
     * Handle review notice actions.
     *
     * @return void
     */
    public static function wcs_void_spare_me() {
        if ( ! self::user_can_manage() ) {
            return;
        }

        if ( ! isset( $_GET['wcs_tc_spare'] ) && ! isset( $_GET['wcs_tc_remind'] ) && ! isset( $_GET['wcs_tc_rated'] ) ) {
            return;
        }

        if ( ! isset( $_GET['_wpnonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ), self::NONCE_ACTION ) ) {
            return;
        }

        $spare  = isset( $_GET['wcs_tc_spare'] ) ? sanitize_text_field( wp_unslash( $_GET['wcs_tc_spare'] ) ) : '';
        $remind = isset( $_GET['wcs_tc_remind'] ) ? sanitize_text_field( wp_unslash( $_GET['wcs_tc_remind'] ) ) : '';
        $rated  = isset( $_GET['wcs_tc_rated'] ) ? sanitize_text_field( wp_unslash( $_GET['wcs_tc_rated'] ) ) : '';

        if ( '1' === $spare ) {
            update_option( 'wcs_spare_me', '1' );
        }

        if ( '1' === $remind ) {
            update_option( 'wcs_remind_me', time() );
            update_option( 'wcs_spare_me', '2' );
        }

        if ( '1' === $rated ) {
            update_option( 'wcs_rated', 'yes' );
            update_option( 'wcs_spare_me', '3' );
            wp_safe_redirect( admin_url( 'plugins.php' ) );
            exit;
        }
    }

    /**
     * Whether the current user can manage this plugin's notices and options.
     *
     * @return bool
     */
    protected static function user_can_manage() {
        return current_user_can( 'manage_woocommerce' ) || current_user_can( 'manage_options' );
    }

    /**
     * Current admin URL without sensitive query args.
     *
     * @return string
     */
    protected static function wcs_current_admin_url() {
        $uri = isset( $_SERVER['REQUEST_URI'] ) ? esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';
        $uri = preg_replace( '|^.*/wp-admin/|i', '', $uri );

        if ( ! $uri ) {
            return admin_url();
        }

        return remove_query_arg(
            [ '_wpnonce', '_wc_notice_nonce', 'wc_db_update', 'wc_db_update_nonce', 'wc-hide-notice', 'wcs_tc_spare', 'wcs_tc_remind', 'wcs_tc_rated' ],
            admin_url( $uri )
        );
    }
}
