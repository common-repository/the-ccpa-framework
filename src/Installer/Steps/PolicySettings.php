<?php

namespace Data443\CCPA\Installer\Steps;

use Data443\CCPA\Installer\InstallerStep;
use Data443\CCPA\Installer\InstallerStepInterface;

class PolicySettings extends InstallerStep implements InstallerStepInterface {

	protected $slug = 'policy-settings';

	protected $type = 'wizard';

	protected $template = 'installer/steps/policy-settings';

	protected $activeSteps = 2;

	protected function renderContent() {
		$policyPage         = ccpa( 'options' )->get( 'policy_page' );
		$policyPageSelector = wp_dropdown_pages(
			array(
				'name'              => 'ccpa_policy_page',
				'show_option_none'  => _x( '&mdash; Create a new page &mdash;', '(Admin)', 'ccpa-framework' ),
				'option_none_value' => 'new',
				'selected'          => $policyPage ? $policyPage : 'new',
				'echo'              => false,
				'class'             => 'ccpa-select js-ccpa-select2',
			)
		);

		$hasTermsPage    = ccpa( 'options' )->get( 'has_terms_page' );
		$termsPage       = ccpa( 'options' )->get( 'terms_page' );
		$policy_page_url = ccpa( 'options' )->get( 'custom_policy_page' );
		// Woo compatibility
		if ( ! $termsPage && get_option( 'woocommerce_terms_page_id' ) ) {
			$hasTermsPage  = 'yes';
			$termsPage     = get_option( 'woocommerce_terms_page_id' );
			$termsPageNote = _x(
				'We have automatically selected your WooCommerce Terms & Conditions page.',
				'(Admin)',
				'ccpa-framework'
			);
		} else {
			$termsPageNote = false;
		}

		$termsPageSelector = wp_dropdown_pages(
			array(
				'name'              => 'ccpa_terms_page',
				'show_option_none'  => _x( '&mdash; Create a new page &mdash;', '(Admin)', 'ccpa-framework' ),
				'option_none_value' => 'new',
				'selected'          => $termsPage ? $termsPage : 'new',
				'echo'              => false,
				'class'             => 'ccpa-select js-ccpa-select2',
			)
		);

		$companyName     = ccpa( 'options' )->get( 'company_name' );
		$companyLocation = ccpa( 'options' )->get( 'company_location' );
		$countryOptions  = ccpa( 'helpers' )->getCountrySelectOptions( $companyLocation );
		$contactEmail    = ccpa( 'options' )->get( 'contact_email' ) ?
			ccpa( 'options' )->get( 'contact_email' ) :
			get_option( 'admin_email' );

		$representativeContactName  = ccpa( 'options' )->get( 'representative_contact_name' );
		$representativeContactEmail = ccpa( 'options' )->get( 'representative_contact_email' );
		$representativeContactPhone = ccpa( 'options' )->get( 'representative_contact_phone' );

		$dpaWebsite = ccpa( 'options' )->get( 'dpa_website' );
		$dpaEmail   = ccpa( 'options' )->get( 'dpa_email' );
		$dpaPhone   = ccpa( 'options' )->get( 'dpa_phone' );
		$dpaData    = json_encode( ccpa( 'helpers' )->getDataProtectionAuthorities() );

		$hasDPO   = ccpa( 'options' )->get( 'has_dpo' );
		$dpoName  = ccpa( 'options' )->get( 'dpo_name' );
		$dpoEmail = ccpa( 'options' )->get( 'dpo_email' );

		echo ccpa( 'view' )->render(
			$this->template,
			compact(
				'policyPage',
				'policyPageSelector',
				'policy_page_url',
				'companyName',
				'companyLocation',
				'contactEmail',
				'countryOptions',
				'hasDPO',
				'dpoEmail',
				'dpoName',
				'representativeContactName',
				'representativeContactEmail',
				'representativeContactPhone',
				'dpaWebsite',
				'dpaEmail',
				'dpaPhone',
				'dpaData',
				'hasTermsPage',
				'termsPage',
				'termsPageSelector',
				'termsPageNote'
			)
		);
	}

	/*
	public function validate()
	{
		if (!is_email($_POST['ccpa_contact_email'])) {
			$this->errors = 'Company email is not a valid email!';
			return false;
		}

		return true;

		//filter_var($url, FILTER_VALIDATE_URL) === FALSE
	}
	*/

	public function submit() {
		/**
		 * Policy page
		 */
		if ( isset( $_POST['ccpa_create_policy_page'] ) && 'yes' === $_POST['ccpa_create_policy_page'] ) {
			$id = $this->createPolicyPage();
			ccpa( 'options' )->set( 'policy_page', intval( $id ) );
		} else {
			ccpa( 'options' )->set( 'policy_page', sanitize_text_field( $_POST['ccpa_policy_page'] ) );
		}

		/**
		 * Custom Policy page URL
		 */
		if ( isset( $_POST['ccpa_custom_policy_page'] ) && '' != $_POST['ccpa_custom_policy_page'] ) {
			ccpa( 'options' )->set( 'custom_policy_page', sanitize_text_field( $_POST['ccpa_custom_policy_page'] ) );
		} else {
			ccpa( 'options' )->set( 'custom_policy_page', '' );
		}

		/**
		 * 'Generate policy' checkbox
		 */
		if ( isset( $_POST['ccpa_generate_policy'] ) && 'yes' === $_POST['ccpa_generate_policy'] ) {
			$this->generatePolicy();
			ccpa( 'options' )->set( 'policy_generated', true );
		} else {
			ccpa( 'options' )->set( 'policy_generated', false );
		}

		/**
		 * Company information
		 */
		ccpa( 'options' )->set( 'company_name', sanitize_text_field( $_POST['ccpa_company_name'] ) );
		ccpa( 'options' )->set( 'company_location', sanitize_text_field( $_POST['ccpa_company_location'] ) );

		if ( is_email( $_POST['ccpa_contact_email'] ) ) {
			ccpa( 'options' )->set( 'contact_email', sanitize_email( $_POST['ccpa_contact_email'] ) );
		}

		/**
		 * Data Protection Officer
		 */
		/* FRAM-125 guard all accesses with isset */
		if ( isset( $_POST['ccpa_has_dpo'] ) ) {
			ccpa( 'options' )->set( 'has_dpo', sanitize_text_field( $_POST['ccpa_has_dpo'] ) );
		}

		if ( isset( $_POST['ccpa_dpo_name'] ) ) {
			ccpa( 'options' )->set( 'dpo_name', sanitize_text_field( $_POST['ccpa_dpo_name'] ) );
		}

		if ( isset( $_POST['ccpa_dpo_email'] ) && is_email( $_POST['ccpa_dpo_email'] ) ) {
			ccpa( 'options' )->set( 'dpo_email', sanitize_email( $_POST['ccpa_dpo_email'] ) );
		}

		/**
		 * Representative contact
		 */
		ccpa( 'options' )->set( 'representative_contact_name', sanitize_text_field( $_POST['ccpa_representative_contact_name'] ) );
		ccpa( 'options' )->set( 'representative_contact_phone', sanitize_text_field( $_POST['ccpa_representative_contact_phone'] ) );

		if ( is_email( $_POST['ccpa_representative_contact_email'] ) ) {
			ccpa( 'options' )->set( 'representative_contact_email', sanitize_email( $_POST['ccpa_representative_contact_email'] ) );
		}

		/**
		 * Data protection authority
		 */
		/* FRAM-125 guard all accesses with isset */
		if (isset($_POST['ccpa_dpa_website'])) {
			ccpa( 'options' )->set( 'dpa_website', sanitize_text_field( $_POST['ccpa_dpa_website'] ) );
		}

		if (isset($_POST['ccpa_dpa_phone'] )) {
			ccpa( 'options' )->set( 'dpa_phone', sanitize_text_field( $_POST['ccpa_dpa_phone'] ) );
		}

		if ( isset($_POST['ccpa_dpa_email'] ) && is_email( $_POST['ccpa_dpa_email'] ) ) {
			ccpa( 'options' )->set( 'dpa_email', sanitize_email( $_POST['ccpa_dpa_email'] ) );
		}

		/**
		 * Terms page
		 */
		if ( isset( $_POST['ccpa_has_terms_page'] ) ) {
			ccpa( 'options' )->set( 'has_terms_page', sanitize_text_field( $_POST['ccpa_has_terms_page'] ) );
		}

		if ( isset( $_POST['ccpa_has_terms_page'] ) && 'yes' === $_POST['ccpa_has_terms_page'] && isset( $_POST['ccpa_terms_page'] ) ) {
			ccpa( 'options' )->set( 'terms_page', sanitize_text_field( $_POST['ccpa_terms_page'] ) );
		} else {
			ccpa( 'options' )->delete( 'terms_page' );
		}
	}

	protected function createPolicyPage() {
		$id = wp_insert_post(
			array(
				'post_title' => __( 'Privacy Policy', 'ccpa-framework' ),
				'post_type'  => 'page',
			)
		);

		return intval( $id );
	}

	protected function generatePolicy() {
		wp_update_post(
			array(
				'ID'           => ccpa( 'options' )->get( 'policy_page' ),
				'post_content' => ccpa( 'view' )->render(
					'policy/policy',
					$this->getData()
				),
			)
		);
	}

	public function getData() {
		$location = ccpa( 'options' )->get( 'company_location' );
		$date     = date( get_option( 'date_format' ) );

		return array(
			'companyName'                => ccpa( 'options' )->get( 'company_name' ),
			'companyLocation'            => $location,
			'contactEmail'               => ccpa( 'options' )->get( 'contact_email' ) ?
				ccpa( 'options' )->get( 'contact_email' ) :
				get_option( 'admin_email' ),

			'hasRepresentative'          => ccpa( 'helpers' )->countryNeedsRepresentative( $location ),
			'representativeContactName'  => ccpa( 'options' )->get( 'representative_contact_name' ),
			'representativeContactEmail' => ccpa( 'options' )->get( 'representative_contact_email' ),
			'representativeContactPhone' => ccpa( 'options' )->get( 'representative_contact_phone' ),

			'dpaWebsite'                 => ccpa( 'options' )->get( 'dpa_website' ),
			'dpaEmail'                   => ccpa( 'options' )->get( 'dpa_email' ),
			'dpaPhone'                   => ccpa( 'options' )->get( 'dpa_phone' ),

			'hasDpo'                     => ccpa( 'options' )->get( 'has_dpo' ),
			'dpoName'                    => ccpa( 'options' )->get( 'dpo_name' ),
			'dpoEmail'                   => ccpa( 'options' )->get( 'dpo_email' ),

			'hasTerms'                   => ccpa( 'options' )->get( 'terms_page' ),

			'date'                       => $date,
		);
	}
}
