<?php

namespace Data443\CCPA\Modules\ContactForm7;

use Data443\CCPA\Components\Consent\ConsentManager;
use Data443\CCPA\DataSubject\DataSubjectManager;

class ContactForm7 {

	public function __construct( DataSubjectManager $dataSubjectManager, ConsentManager $consentManager ) {
		$this->dataSubjectManager = $dataSubjectManager;
		$this->consentManager     = $consentManager;

		// add_action('wpcf7_init', [$this, 'addFormTags'], 10, 3);
		// add_action('wpcf7_admin_init', [$this, 'addFormTagGenerators'], 9999, 3);

		add_action( 'wpcf7_before_send_mail', array( $this, 'processFormSubmission' ), 10, 3 );
	}

	public function addFormTags() {
		wpcf7_add_form_tag(
			array( 'ccpa_consent_text' ),
			array( $this, 'renderPrivacyTag' )
		);
	}

	public function addFormTagGenerators() {
		$generator = \WPCF7_TagGenerator::get_instance();

		$generator->add(
			'ccpa_privacy',
			_x( 'ccpa terms txt', '(Admin)', 'ccpa-framework' ),
			array( $this, 'generatePrivacyTag' )
		);
	}

	public function generatePrivacyTag( $contactForm, $args ) {
		// $args = wp_parse_args( $args, array() );
		// $description = _x( "Generate the default text for your Privacy Policy acceptance checkbox. For more details, see %s.", '(Admin)', 'ccpa-framework' );
		// $descLink = wpcf7_link( _x( 'https://TODO', '(Admin)', 'ccpa-framework' ), _x( 'CCPA Terms', '(Admin)', 'ccpa-framework' ) );
		// $content = $this->renderPrivacyTag();

		echo ccpa( 'view' )->render(
			'modules/contact-form-7/generator-privacy',
			compact( 'description', 'descLink', 'args', 'content' )
		);
	}

	public function renderPrivacyTag() {
		$privacyPolicyUrl = get_permalink( ccpa( 'options' )->get( 'policy_page' ) );
		add_filter( 'ccpa_custom_policy_link', 'ccpafPrivacyPolicyurl' );
		$privacyPolicyUrl = apply_filters( 'ccpa_custom_policy_link', $privacyPolicyUrl );
		return ccpa( 'view' )->render(
			'modules/contact-form-7/content-privacy',
			compact( 'privacyPolicyUrl' )
		);
	}

	public function processFormSubmission( \WPCF7_ContactForm $form, $abort, \WPCF7_Submission $submission ) {
		$consents = $this->findConsents( $form, $submission );

		if ( ! count( $consents ) ) {
			return;
		}

		$email = $this->findEmail( $form, $submission );

		if ( ! $email ) {
			return;
		}

		$dataSubject = $this->dataSubjectManager->getByEmail( $email );

		foreach ( $consents as $consent ) {
			$dataSubject->giveConsent( $consent );
		}
	}

	public function findConsents( \WPCF7_ContactForm $form, \WPCF7_Submission $submission ) {
		$consents = array();

		foreach ( $form->scan_form_tags() as $tag ) {
			/* @var $tag \WPCF7_FormTag */
			if ( 'acceptance' === $tag->type && $submission->get_posted_data()[ $tag->name ] && $this->consentManager->isRegisteredConsent( $tag->name ) ) {
				$consents[] = $tag->name;
			}
		}

		return $consents;
	}

	public function findEmail( $form_id, \WPCF7_Submission $submission ) {
		$email_key = get_post_meta( $form_id->id(), 'ccpa_cf7_email_field', true );
		if ( isset( $submission->get_posted_data()['your-email'] ) ) {
			return $submission->get_posted_data()['your-email'];
		}
		if ( isset( $email_key ) ) {
			if ( $email_key != '' ) {
				if ( isset( $submission->get_posted_data()[ $email_key ] ) ) {
					return $submission->get_posted_data()[ $email_key ];
				}
			}
		}
	}
}
