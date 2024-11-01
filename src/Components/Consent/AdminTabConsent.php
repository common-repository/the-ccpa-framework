<?php

namespace Data443\CCPA\Components\Consent;

use Data443\CCPA\Admin\AdminTab;

/**
 * Handle rendering and saving the Consent tab on CCPA Options page
 *
 * Class AdminTabConsent
 *
 * @package Data443\CCPA\Components\Consent
 */
class AdminTabConsent extends AdminTab {

	/* @var string */
	protected $slug = 'consent';

	/* @var ConsentManager */
	protected $consentManager;

	/**
	 * AdminTabConsent constructor.
	 *
	 * @param ConsentManager $consentManager
	 */
	public function __construct( ConsentManager $consentManager ) {
		$this->consentManager = $consentManager;

		$this->title = _x( 'Consent', '(Admin)', 'ccpa-framework' );

		// If we don't register the settings, WP will not allow this page to be submitted
		$this->registerSetting( 'consent_types' );
		$this->registerSetting( 'consent_info' );
		$this->registerSetting( 'ccpa_consent_until_display' );

		$this->renderErrors();

		// Register handler for this action
		add_action( 'ccpa/admin/action/update_consent_data', array( $this, 'updateConsentData' ) );
	}

	/**
	 * Initialize tab contents and register hooks
	 */
	public function init() {
		$this->registerSettingSection(
			'ccpa_section_consent',
			_x( 'Consent', '(Admin)', 'ccpa-framework' ),
			array( $this, 'renderConsentForm' )
		);
		$this->registerSettingSection(
            'ccpa_section_consent_until',
            _x('Additional Settings', '(Admin)', 'ccpa-framework'),
            [$this, 'renderConsentUntil']
		);
		$this->registerSettingField(
			'ccpa_consent_until_display',
			_x( 'Display Consent Calendar', '(Admin)', 'ccpa-framework' ),
			array( $this, 'consent_until_display' ),
			'ccpa_section_consent_until'
		);
	}

	/**
	 * Render the contents of the registered section
	 */
	public function renderConsentForm() {
		$consentInfo = ccpa( 'options' )->get( 'consent_info' );

		if ( is_null( $consentInfo ) ) {
			$consentInfo = $this->getDefaultConsentInfo();
		} elseif ( ! $consentInfo ) {
			$consentInfo = '';
		}

		$nonce               = wp_create_nonce( 'ccpa/admin/action/update_consent_data' );
		$defaultConsentTypes = $this->consentManager->getDefaultConsentTypes();
		$customConsentTypes  = $this->consentManager->getCustomConsentTypes();

		// todo: move to a filter
		if ( defined( 'ICL_LANGUAGE_CODE' ) ) {
			$prefix = ICL_LANGUAGE_CODE . '_';
		} else {
			$prefix = '';
		}

		echo ccpa( 'view' )->render( 'admin/consent', compact( 'nonce', 'customConsentTypes', 'defaultConsentTypes', 'consentInfo', 'prefix' ) );
	}

	/**
	 * Save the submitted consent types
	 */
	public function updateConsentData() {
		// Update additional information
		if ( isset( $_POST['ccpa_consent_info'] ) ) {
			ccpa( 'options' )->set( 'consent_info', wp_unslash( $_POST['ccpa_consent_info'] ) );
		}

		// Update consent types
		if ( isset( $_POST['ccpa_consent_types'] ) && is_array( $_POST['ccpa_consent_types'] ) ) {
			$consentTypes = $_POST['ccpa_consent_types'];
		} else {
			$consentTypes = array();
		}

		// Strip slashes which WP adds automatically
		if ( count( $consentTypes ) ) {
			foreach ( $consentTypes as &$type ) {
				foreach ( $type as $key => $item ) {
					if ( is_array( $item ) ) {
						$type[ $key ] = array_map( 'wp_unslash', $item );
					} else {
						$type[ $key ] = wp_unslash( $item );
					}

					if ( 'visible' === $key ) {
						$type[ $key ] = 1;
					}
				}
			}
		}

		$errors = array();

		if ( ! empty( $consentTypes ) ) {
			//$errors = $this->validate( $consentTypes );
		}

		if ( ! count( $errors ) ) {
			$this->consentManager->saveCustomConsentTypes( $consentTypes );
		} else {
			$errorQuery = http_build_query( $errors );
			wp_safe_redirect( ccpa( 'helpers' )->getAdminUrl( '&ccpa-tab=consent&' ) . $errorQuery );
			exit;
		}
	}

	protected function validate( $consentTypes ) {
		$errors = array();

		foreach ( $consentTypes as $consentType ) {
			if ( empty( $consentType['slug'] ) ) {
				$errors['errors[]'] = 'slug-empty';
			}

			if ( ! preg_match( '/^[A-Za-z0-9_-]+$/', $consentType['slug'] ) ) {
				$errors['errors[]'] = 'slug-invalid';
			}

			if ( empty( $consentType['title'] ) ) {
				$errors['errors[]'] = 'title-empty';
			}
		}

		return $errors;
	}

	public function renderErrors() {
		if ( isset( $_GET['errors'] ) && count( $_GET['errors'] ) ) {

			foreach ( $_GET['errors'] as $error ) {
				if ( 'slug-empty' === $error ) {
					$message = _x( 'Consent slug is a required field!', '(Admin)', 'ccpa-framework' );
					ccpa( 'admin-error' )->add( 'admin/notices/error', compact( 'message' ) );
				}

				if ( 'slug-invalid' === $error ) {
					$message = _x( 'You may only use alphanumeric characters, dash and underscore in the consent slug field.', '(Admin)', 'ccpa-framework' );
					ccpa( 'admin-error' )->add( 'admin/notices/error', compact( 'message' ) );
				}

				if ( 'title-empty' === $error ) {
					$message = _x( 'Consent title is a required field!', '(Admin)', 'ccpa-framework' );
					ccpa( 'admin-error' )->add( 'admin/notices/error', compact( 'message' ) );
				}
			}
		}
	}

	/**
	 * @return string
	 */
	public function getDefaultConsentInfo() {
		return __( 'To use this website, you accepted our Privacy Policy. If you wish to withdraw your acceptance, please use the "Delete my data" button below.', 'ccpa-framework' );
	}

	public function renderConsentUntil() {
		echo '<p>Enable this feature to allow users to submit a time limit on how many months their consent is given for their coments and registration.</p>';
	}

	public function consent_until_display(){
		if ( get_option( 'ccpa_consent_until_display' ) === '1' ) {
			$checked = get_option( 'ccpa_consent_until_display' );
		}else{
			$checked = 0;
		}		
		echo ccpa( 'view' )->render( 'admin/consent/enable-consent-until' , compact( 'checked' ) );
	}
}
