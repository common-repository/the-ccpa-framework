<p class="ccpa-terms-container">
<span for="ccpa_terms">
		<input type="checkbox" required name="ccpa_terms" id="ccpa_terms" value="1" />
		<?php $enabled = ccpa( 'options' )->get( 'enable_tac' ); ?>
		<?php
		wp_register_style( 'ccpa-consent-until-css', ccpa( 'config' )->get( 'plugin.url' ) . 'assets/css/consentuntil.min.css', array(), true );
		wp_register_script( 'ccpa-consent-until-js', ccpa( 'config' )->get( 'plugin.url' ) . 'assets/js/consentuntil.min.js', array(), true, true );
		wp_enqueue_script( 'ccpa-consent-until-js' );
		wp_enqueue_style( 'ccpa-consent-until-css' );
		wp_register_style('ccpa-consent-until-dashicons', includes_url() . '/css/dashicons.min.css', array(), true);
		wp_enqueue_style('ccpa-consent-until-dashicons');

		if ( ! isset( $ccpa_value ) ) :
			$ccpa_value = '';
		endif;
		if ( ! isset( $ccpa_arg2 ) ) :
			$ccpa_arg2 = '';
		endif;
		if ( ! isset( $ccpa_arg3 ) ) :
			$ccpa_arg3 = '';
		endif;
		if ( $termsUrl && $enabled ) :
			add_filter( 'ccpa-framework-consent-policy-with-terms', 'CCPATermAndConditionWithPrivacyContent' );
			$ccpa_text_policy_with_terms = apply_filters( 'ccpa-framework-consent-policy-with-terms', $ccpa_value, $ccpa_arg2, $ccpa_arg3 );
			?>
			<?php
			echo sprintf(
				__( $ccpa_text_policy_with_terms, 'ccpa-framework' ),
				"<a href='{$termsUrl}' target='_blank'>",
				'</a>',
				"<a href='{$privacyPolicyUrl}' target='_blank'>",
				'</a>'
			);
			?>
		<?php else : ?>
		
			<?php
			add_filter( 'ccpa-framework-consent-policy', 'ccpafPrivacyPolicy' );
			$ccpa_text_policy = apply_filters( 'ccpa-framework-consent-policy', $ccpa_value, $ccpa_arg2, $ccpa_arg3 );
			?>
			<?php
			echo sprintf(
				__( $ccpa_text_policy, 'ccpa-framework' ),
				"<a href='{$privacyPolicyUrl}' target='_blank'>",
				'</a>'
			);
			?>
		<?php endif; ?><?php if( get_option( 'ccpa_consent_until_display' ) === '1' ){ ?>* for<?php } ?>
	</span>
	<?php if( get_option( 'ccpa_consent_until_display' ) === '1' ){ ?>
		<span class="ccpa-consent-until-wrap">
			<span class="dashicons dashicons-calendar-alt ccpa-consent-until-cal">
				<span class="tooltiptext">Click to select the duration you give consent until.</span>
			</span>
			<select id="ccpa-consent-until" class="ccpa-consent-until" name="ccpa-consent-until">
				<option value="" default>Infinite</option>
				<option value="1">1 Month</option>
				<option value="3">3 Months</option>
				<option value="6">6 Months</option>
			</select>
		</span>
	<?php } ?>
</p>
