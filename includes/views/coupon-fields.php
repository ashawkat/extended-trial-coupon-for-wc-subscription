<?php wp_enqueue_script( 'wcs-coupon-metabox' ); ?>
<p class="form-field subscription_coupon_trial_length_field">
    <label for="_wcs_coupon_trial_length"><?php esc_html_e( 'Free trial', 'wcs-trial-coupon' ); ?></label>
    <span class="wrap">
        <input type="number" id="_wcs_coupon_trial_length" name="_wcs_trial_coupon_length" class="wc_input_subscription_trial_length" value="<?php echo esc_attr( $coupon->get_meta($this->coupon_trial_length) ); ?>" />
        <label for="_wcs_coupon_trial_period" style="display:none" class="wcs_hidden_label"><?php esc_html_e( 'Subscription Trial Period', 'wcs-trial-coupon' ); ?></label>
        <select id="_wcs_coupon_trial_period" name="_wcs_trial_coupon_period" class="wc_input_subscription_trial_period last" >
        <?php foreach ( wcs_get_available_time_periods() as $value => $label ) { ?>
            <option value="<?php echo esc_attr( $value ); ?>" <?php selected( $value, esc_attr( $coupon->get_meta($this->coupon_trial_period) ), true ) ?>><?php echo esc_html( $label ); ?></option>
        <?php } ?>
        </select>
    </span>
    <?php echo wcs_help_tip( __( 'An optional period of time to wait before charging the first recurring payment. Any sign up fee will still be charged at the outset of the subscription', 'wcs-trial-coupon' ) ); ?>
</p>