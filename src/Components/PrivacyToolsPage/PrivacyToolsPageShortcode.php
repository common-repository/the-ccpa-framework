<?php

namespace Data443\CCPA\Components\PrivacyToolsPage;

use Data443\CCPA\Components\Consent\ConsentManager;
class PrivacyToolsPageShortcode {

	protected $consentManager;
	public function __construct( PrivacyToolsPageController $controller, ConsentManager $consentManager ) {
		$this->controller     = $controller;
		$this->consentManager = $consentManager;
		add_shortcode( 'ccpa_privacy_tools', array( $this, 'renderPage' ) );
		add_shortcode( 'ccpa_privacy_tools_url', array( $this, 'renderUrlShortcode' ) );
		add_shortcode( 'ccpa_privacy_tools_link', array( $this, 'renderLinkShortcode' ) );
		add_shortcode( 'ccpa_do_not_sell_form', array( $this, 'renderDoNotSellForm' ) );
		

	}

	public function renderPage() {
		if ( ! ccpa( 'options' )->get( 'enable' ) ) {
			return __( 'This page is currently disabled.', 'ccpa-framework' );
		}

		if ( ( ! ccpa( 'options' )->get( 'tools_page' ) || is_null( get_post( ccpa( 'options' )->get( 'tools_page' ) ) ) ) && ! ccpa( 'options' )->get( 'custom_tools_page' ) ) {
			return __( 'Please configure the Privacy Tools page in the admin interface.', 'ccpa-framework' );
		}

		ob_start();
		$this->controller->render();
		return ob_get_clean();
	}
	public function renderDoNotSellForm() {
		if ( ! ccpa( 'options' )->get( 'enable' ) ) {
			return __( 'This page is currently disabled.', 'ccpa-framework' );
		}

		if ( ! ccpa( 'options' )->get( 'tools_page' ) || is_null( get_post( ccpa( 'options' )->get( 'tools_page' ) ) ) ) {
			return __( 'Please configure the Privacy Tools page in the admin interface.', 'ccpa-framework' );
		}
		$slug                = 'do-not-sell-request';
		$defaultConsentTypes = $this->consentManager->getbySlugConsent( $slug );
		$first_name          = '';
		$last_name           = '';
		$user_email          = '';
		if ( is_user_logged_in() ) {
			// your code for logged in user
			$current_user = wp_get_current_user();
			$first_name   = get_user_meta( $current_user->ID, 'first_name', true );
			if ( $first_name === '' ) {
				$first_name = $current_user->user_nicename;
			}
			$last_name  = get_user_meta( $current_user->ID, 'last_name', true );
			$user_email = $current_user->user_email;
		}
		ob_start();
		// $this->controller->render();
		echo ccpa( 'view' )->render( 'privacy-tools/donotsell', compact( 'defaultConsentTypes', 'first_name', 'last_name', 'user_email' ) );
		return ob_get_clean();
	}
	public function renderUrlShortcode() {
		return ccpa( 'helpers' )->getPrivacyToolsPageUrl();
	}

	public function renderLinkShortcode( $attributes ) {
		$attributes = shortcode_atts(
			array(
				'title' => __( 'Privacy Tools', 'ccpa-framework' ),
			),
			$attributes
		);

		$url = ccpa( 'helpers' )->getPrivacyToolsPageUrl();

		return "<a href='{$url}'>" .
			esc_html( $attributes['title'] ) .
			'</a>';
	}
}
