<?php

namespace Data443\CCPA\Components\WordpressUser;

use Data443\CCPA\DataSubject\DataSubject;
use Data443\CCPA\DataSubject\DataSubjectManager;

class RegistrationForm {

	/* @var DataSubjectManager */
	protected $dataSubjectManager;

	public function __construct( DataSubjectManager $dataSubjectManager ) {
		$this->dataSubjectManager = $dataSubjectManager;
		if ( ! ccpa( 'options' )->get( 'register_checkbox' ) ) {
			if ( ccpa( 'options' )->get( 'policy_page' ) || ccpa( 'options' )->get( 'custom_policy_page' ) ) {
				add_action( 'register_form', array( $this, 'addRegisterFormCheckbox' ) );
				add_filter( 'registration_errors', array( $this, 'validate' ), PHP_INT_MAX );
			}
		}
	}

	public function addRegisterFormCheckbox() {
		$privacyPolicyUrl = ! get_permalink( ccpa( 'options' )->get( 'custom_policy_page' ) ) ? get_permalink( ccpa( 'options' )->get( 'policy_page' ) ) : get_permalink( ccpa( 'options' )->get( 'custom_policy_page' ) );
		add_filter( 'ccpa_custom_policy_link', 'ccpafPrivacyPolicyurl' );
		$privacyPolicyUrl = apply_filters( 'ccpa_custom_policy_link', $privacyPolicyUrl );
		$termsPage = ! ccpa( 'options' )->get( 'custom_terms_page' ) ? ccpa( 'options' )->get( 'terms_page' ) : ccpa( 'options' )->get( 'custom_terms_page' );

		if ( $termsPage ) {
			$termsUrl = get_permalink( $termsPage ) ? get_permalink( $termsPage ) : $termsPage;
		} else {
			$termsUrl = false;
		}

		echo ccpa( 'view' )->render(
			'modules/wordpress-user/registration-terms-checkbox',
			compact( 'privacyPolicyUrl', 'termsUrl' )
		);
	}

	public function validate( \WP_Error $errors ) {
		if ( empty( $_POST['ccpa_terms'] ) || ! $_POST['ccpa_terms'] ) {
			$errors->add( 'ccpa_error', __( '<strong>ERROR</strong>: You must accept the terms and conditions.', 'ccpa-framework' ) );
		} else {
			$dataSubject = $this->dataSubjectManager->getByEmail( $_POST['user_email'] );
			$dataSubject->giveConsent( 'privacy-policy' );
		}

		return $errors;
	}
}
