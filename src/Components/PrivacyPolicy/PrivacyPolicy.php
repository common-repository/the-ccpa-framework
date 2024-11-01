<?php

namespace Data443\CCPA\Components\PrivacyPolicy;

/**
 * Handles putting together and rendering the privacy policy page
 *
 * Class PrivacyPolicy
 *
 * @package Data443\CCPA\Components\PrivacyPolicy
 */
class PrivacyPolicy {

	public function __construct() {
		add_filter( 'ccpa/admin/tabs', array( $this, 'registerAdminTab' ), 35 );

		add_shortcode( 'ccpa_privacy', array( $this, 'doShortcode' ) );
		add_shortcode( 'ccpa_privacy_policy_url', array( $this, 'renderUrlShortcode' ) );
		add_shortcode( 'ccpa_privacy_policy_link', array( $this, 'renderLinkShortcode' ) );
	}

	public function registerAdminTab( $tabs ) {
		$tabs['privacy-policy'] = new AdminTabPrivacyPolicy(new PolicyGenerator());

		return $tabs;
	}

	public function doShortcode( $attributes ) {
		$attributes = shortcode_atts(
			array(
				'item' => null,
			),
			$attributes
		);

		switch ( $attributes['item'] ) {
			case 'company_name':
				return esc_html( ccpa( 'options' )->get( 'company_name' ) );
			case 'company_email':
				return esc_html( ccpa( 'options' )->get( 'contact_email' ) );
			case 'company_email_link':
				$email = antispambot( ccpa( 'options' )->get( 'contact_email' ) );
				return "<a href='mailto:{$email}'>{$email}</a>";
			case 'dpo_name':
				return esc_html( ccpa( 'options' )->get( 'dpo_name' ) );
			case 'dpo_email':
				return esc_html( ccpa( 'options' )->get( 'dpo_email' ) );
			case 'dpo_email_link':
				$email = antispambot( ccpa( 'options' )->get( 'dpo_email' ) );
				return "<a href='mailto:{$email}'>{$email}</a>";
			case 'rep_name':
				return esc_html( ccpa( 'options' )->get( 'representative_contact_name' ) );
			case 'rep_email':
				return esc_html( ccpa( 'options' )->get( 'representative_contact_email' ) );
			case 'rep_email_link':
				$email = antispambot( ccpa( 'options' )->get( 'representative_contact_email' ) );
				return "<a href='mailto:{$email}'>{$email}</a>";
			case 'authority_website':
				return esc_html( ccpa( 'options' )->get( 'dpa_website' ) );
			case 'authority_email':
				return esc_html( ccpa( 'options' )->get( 'dpa_email' ) );
			case 'authority_email_link':
				$email = antispambot( ccpa( 'options' )->get( 'dpa_email' ) );
				return "<a href='mailto:{$email}'>{$email}</a>";
			case 'authority_phone':
				return esc_html( ccpa( 'options' )->get( 'dpa_phone' ) );
			case null:
				return '';
		}

		return '';
	}

	public function renderUrlShortcode() {
		return ccpa( 'helpers' )->getPrivacyPolicyPageUrl();
	}

	public function renderLinkShortcode( $attributes ) {
		$attributes = shortcode_atts(
			array(
				'title' => __( 'Privacy Policy', 'ccpa-framework' ),
			),
			$attributes
		);

		$url = ccpa( 'helpers' )->getPrivacyPolicyPageUrl();

		return "<a href='{$url}'>" .
			esc_html( $attributes['title'] ) .
			'</a>';
	}
}
