<?php

namespace WCS\Trial\Coupon;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Trial_Coupon_Actions {

    /**
     * Coupon trial length meta key.
     *
     * @var string
     */
    public $coupon_trial_length = '_wcs_trial_coupon_length';

    /**
     * Coupon trial period meta key.
     *
     * @var string
     */
    public $coupon_trial_period = '_wcs_trial_coupon_period';

    /**
     * Custom coupon type slug.
     *
     * @var string
     */
    const DISCOUNT_TYPE = 'subscription_trials';

    /**
     * Class constructor
     */
    public function __construct() {
        add_filter( 'woocommerce_coupon_discount_types', [ $this, 'create_discount_type' ], 10, 1 );
        add_filter( 'woocommerce_cart_coupon_types', [ $this, 'register_cart_coupon_type' ] );
        add_action( 'woocommerce_coupon_options', [ $this, 'add_discount_fields' ], 20, 1 );
        add_action( 'woocommerce_coupon_options_save', [ $this, 'save_coupon_fields' ], 10, 1 );
        add_filter( 'woocommerce_subscriptions_validate_coupon_type', [ $this, 'trial_coupon_validation' ], 5, 3 );
        add_filter( 'woocommerce_coupon_is_valid', [ $this, 'validate_trial_coupon' ], 20, 2 );
        add_filter( 'woocommerce_subscriptions_product_trial_length', [ $this, 'filter_product_trial_length' ], 20, 2 );
        add_filter( 'woocommerce_subscriptions_product_trial_period', [ $this, 'filter_product_trial_period' ], 20, 2 );
    }

    /**
     * Add discount type subscription trial
     *
     * @param array $discount_types Discount types.
     * @return array
     */
    public function create_discount_type( $discount_types ) {
        $discount_types[ self::DISCOUNT_TYPE ] = __( 'Subscription Trial', 'wcs-trial-coupon' );

        return $discount_types;
    }

    /**
     * Treat the trial coupon as a cart coupon so WooCommerce accepts it.
     *
     * @param array $types Cart coupon types.
     * @return array
     */
    public function register_cart_coupon_type( $types ) {
        $types[] = self::DISCOUNT_TYPE;

        return $types;
    }

    /**
     * Add coupon fields.
     *
     * @param int $coupon_id Coupon ID.
     * @return void
     */
    public function add_discount_fields( $coupon_id ) {
        $coupon   = new \WC_Coupon( $coupon_id );
        $template = __DIR__ . '/views/coupon-fields.php';

        if ( file_exists( $template ) ) {
            include $template;
        }
    }

    /**
     * Save coupon fields.
     *
     * @param int $coupon_id Coupon ID.
     * @return void
     */
    public function save_coupon_fields( $coupon_id ) {
        $coupon_id = absint( $coupon_id );

        if ( ! $coupon_id || ! current_user_can( 'edit_post', $coupon_id ) ) {
            return;
        }

        $nonce = isset( $_POST['woocommerce_meta_nonce'] )
            ? sanitize_text_field( wp_unslash( $_POST['woocommerce_meta_nonce'] ) )
            : '';

        if ( ! $nonce || ! wp_verify_nonce( $nonce, 'woocommerce_save_data' ) ) {
            return;
        }

        if ( ! isset( $_POST['_wcs_trial_coupon_length'], $_POST['_wcs_trial_coupon_period'] ) ) {
            return;
        }

        $coupon = new \WC_Coupon( $coupon_id );
        $coupon->update_meta_data( $this->coupon_trial_length, $this->sanitize_trial_length( wp_unslash( $_POST['_wcs_trial_coupon_length'] ) ) );
        $coupon->update_meta_data( $this->coupon_trial_period, $this->sanitize_trial_period( wp_unslash( $_POST['_wcs_trial_coupon_period'] ) ) );
        $coupon->save();
    }

    /**
     * Skip WooCommerce Subscriptions coupon-type validation for trial coupons.
     *
     * @param bool       $should_validate Whether WCS should validate this coupon type.
     * @param \WC_Coupon $coupon          Coupon object.
     * @param bool       $valid           Current validity.
     * @return bool
     */
    public function trial_coupon_validation( $should_validate, $coupon, $valid ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed
        if ( $coupon->is_type( self::DISCOUNT_TYPE ) && $this->coupon_has_trial( $coupon ) ) {
            return false;
        }

        return $should_validate;
    }

    /**
     * Ensure trial coupons are only applied to subscription carts and have trial data.
     *
     * @param bool       $valid  Whether the coupon is valid.
     * @param \WC_Coupon $coupon Coupon object.
     * @return bool
     */
    public function validate_trial_coupon( $valid, $coupon ) {
        if ( ! $valid || ! $coupon->is_type( self::DISCOUNT_TYPE ) ) {
            return $valid;
        }

        if ( ! $this->coupon_has_trial( $coupon ) ) {
            throw new \Exception( __( 'This trial coupon is missing a trial length or period.', 'wcs-trial-coupon' ) );
        }

        if ( ! function_exists( 'WC' ) || ! WC()->cart ) {
            return $valid;
        }

        if ( ! $this->cart_contains_subscription() ) {
            throw new \Exception( __( 'Sorry, this coupon is only valid for subscription products.', 'wcs-trial-coupon' ) );
        }

        return $valid;
    }

    /**
     * Override product trial length from an applied coupon without writing product meta.
     *
     * @param int        $length  Current trial length.
     * @param \WC_Product $product Product object.
     * @return int
     */
    public function filter_product_trial_length( $length, $product ) {
        $override = $this->get_cart_trial_override( $product );

        return $override ? (int) $override['length'] : $length;
    }

    /**
     * Override product trial period from an applied coupon without writing product meta.
     *
     * @param string      $period  Current trial period.
     * @param \WC_Product $product Product object.
     * @return string
     */
    public function filter_product_trial_period( $period, $product ) {
        $override = $this->get_cart_trial_override( $product );

        return $override ? $override['period'] : $period;
    }

    /**
     * Get trial override from applied cart coupons for a product.
     *
     * @param mixed $product Product object or ID.
     * @return array{length:int,period:string}|null
     */
    private function get_cart_trial_override( $product ) {
        if ( ! $this->is_subscription_product( $product ) ) {
            return null;
        }

        if ( ! function_exists( 'WC' ) || ! WC()->cart ) {
            return null;
        }

        $codes = WC()->cart->get_applied_coupons();

        if ( empty( $codes ) ) {
            return null;
        }

        foreach ( $codes as $code ) {
            $coupon = new \WC_Coupon( $code );

            if ( ! $this->coupon_has_trial( $coupon ) ) {
                continue;
            }

            if ( ! $this->coupon_applies_to_product( $coupon, $product ) ) {
                continue;
            }

            return [
                'length' => absint( $coupon->get_meta( $this->coupon_trial_length ) ),
                'period' => $this->sanitize_trial_period( $coupon->get_meta( $this->coupon_trial_period ) ),
            ];
        }

        return null;
    }

    /**
     * Whether a coupon has a usable trial configuration.
     *
     * @param \WC_Coupon $coupon Coupon object.
     * @return bool
     */
    private function coupon_has_trial( $coupon ) {
        $length = absint( $coupon->get_meta( $this->coupon_trial_length ) );
        $period = $this->sanitize_trial_period( $coupon->get_meta( $this->coupon_trial_period ) );

        return $length > 0 && '' !== $period;
    }

    /**
     * Whether the coupon's product restrictions include this product.
     *
     * @param \WC_Coupon  $coupon  Coupon object.
     * @param \WC_Product $product Product object.
     * @return bool
     */
    private function coupon_applies_to_product( $coupon, $product ) {
        if ( ! is_a( $product, 'WC_Product' ) ) {
            $product = wc_get_product( $product );
        }

        if ( ! $product ) {
            return false;
        }

        $product_id = $product->get_id();
        $parent_id  = $product->get_parent_id();
        $ids        = array_filter( [ $product_id, $parent_id ] );

        $excluded = array_map( 'absint', (array) $coupon->get_excluded_product_ids() );
        if ( $excluded && array_intersect( $ids, $excluded ) ) {
            return false;
        }

        $included = array_map( 'absint', (array) $coupon->get_product_ids() );
        if ( $included && ! array_intersect( $ids, $included ) ) {
            return false;
        }

        $product_id   = $product->is_type( 'variation' ) ? $product->get_parent_id() : $product->get_id();
        $product_cats = function_exists( 'wc_get_product_cat_ids' ) ? wc_get_product_cat_ids( $product_id ) : [];
        $excluded_cats = array_map( 'absint', (array) $coupon->get_excluded_product_categories() );
        $included_cats = array_map( 'absint', (array) $coupon->get_product_categories() );

        if ( $excluded_cats && array_intersect( $product_cats, $excluded_cats ) ) {
            return false;
        }

        if ( $included_cats && ! array_intersect( $product_cats, $included_cats ) ) {
            return false;
        }

        return true;
    }

    /**
     * Whether a product is a subscription.
     *
     * @param mixed $product Product object or ID.
     * @return bool
     */
    private function is_subscription_product( $product ) {
        return class_exists( 'WC_Subscriptions_Product' ) && \WC_Subscriptions_Product::is_subscription( $product );
    }

    /**
     * Whether the current cart contains a subscription or renewal.
     *
     * @return bool
     */
    private function cart_contains_subscription() {
        if ( class_exists( 'WC_Subscriptions_Cart' ) && \WC_Subscriptions_Cart::cart_contains_subscription() ) {
            return true;
        }

        return function_exists( 'wcs_cart_contains_renewal' ) && wcs_cart_contains_renewal();
    }

    /**
     * Sanitize trial length.
     *
     * @param mixed $length Raw length.
     * @return int
     */
    private function sanitize_trial_length( $length ) {
        return absint( $length );
    }

    /**
     * Sanitize trial period against allowed WooCommerce Subscriptions periods.
     *
     * @param mixed $period Raw period.
     * @return string
     */
    private function sanitize_trial_period( $period ) {
        $period  = sanitize_key( (string) $period );
        $allowed = [ 'day', 'week', 'month', 'year' ];

        if ( function_exists( 'wcs_get_available_time_periods' ) ) {
            $allowed = array_keys( wcs_get_available_time_periods() );
        }

        return in_array( $period, $allowed, true ) ? $period : '';
    }
}
