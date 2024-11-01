<?php
if ( ! isset( $ccpa_value ) ) :
	$ccpa_value = '';
	endif;
if ( ! isset( $ccpa_arg2 ) ) :
	$ccpa_arg2 = '';
	endif;
if ( ! isset( $ccpa_arg3 ) ) :
	$ccpa_arg3 = '';
	endif;
	add_filter( 'ccpa-framework-consent-policy', 'ccpafPrivacyPolicy' );
	$ccpa_text_policy = apply_filters( 'ccpa-framework-consent-policy', $ccpa_value, $ccpa_arg2, $ccpa_arg3 );
?>
	
<?php
echo sprintf(
	__( $ccpa_text_policy, 'ccpa-framework' ),
	"<a href='{$privacyPolicyUrl}' target='_blank'>",
	'</a>'
);
