<?php

namespace Data443\CCPA\Components\Consent;

/**
 * Handles getting, saving and removing consent based on a whitelist
 *
 * Class ConsentManager
 *
 * @package Data443\CCPA\Components\Consent
 */
class ConsentManager {

	/* @var UserConsentModel */
	protected $model;

	/* @var array */
	protected $defaultConsentTypes = array();

	/* @var array */
	protected $customConsentTypes = array();

	/**
	 * ConsentManager constructor.
	 *
	 * @param UserConsentModel $model
	 */
	public function __construct( UserConsentModel $model ) {
		$this->model = $model;

		add_action( 'init', array( $this, 'registerCustomConsentTypes' ), 0 );
		add_action( 'init', array( $this, 'registerDefaultConsentTypes' ), 0 );

		add_filter( 'ccpa/data-subject/data', array( $this, 'getdata' ), 20, 2 );
		add_action( 'ccpa/data-subject/delete', array( $this, 'delete' ) );
		add_action( 'ccpa/data-subject/anonymize', array( $this, 'anonymize' ), 10, 2 );
	}

	public function registerDefaultConsentTypes() {
		/****
		 * privacy-policy default consent
		 */
		$policyPageUrl = get_permalink( ccpa( 'options' )->get( 'policy_page' ) );
		add_filter( 'ccpa_custom_policy_link', 'ccpafPrivacyPolicyurl' );

		$policyPageUrl = apply_filters( 'ccpa_custom_policy_link', $policyPageUrl );

		ccpa( 'consent' )->register(
			'privacy-policy',
			sprintf(
				__( 'I accept the %1$sPrivacy Policy%2$s', 'ccpa-framework' ),
				"<a href='{$policyPageUrl}' target='_blank'>",
				'</a>'
			),
			_x( 'This consent is not visible by default. If someone wishes to withdraw it, they should simply request to delete all their data.', '(Admin)', 'ccpa-framework' ),
			false
		);

		/****
		 * privacy-policy do-not-sell-request form default consent
		 */

		$this->register(
			'do-not-sell-info',
			sprintf(
				__( 'Do Not Sell My Data', 'ccpa-framework' )
			),
			_x( 'I do not consent to having my information sold by ' , '(Admin)', 'ccpa-framework' ) .get_bloginfo('name'). '.',
			true
		);

		$this->register(
			'receive-communications',
			sprintf(
				__( 'Receive Communications from ', 'ccpa-framework' )  . get_bloginfo('name')
			),
			_x( 'I agree to receive other communications from ' , '(Admin)', 'ccpa-framework' ) .get_bloginfo('name'). '.',
			true
		);

		/****
		 * terms-conditions default consent
		 */
		if ( ccpa( 'options' )->get( 'custom_terms_page' ) ) {
			$termsPage = ccpa( 'options' )->get( 'custom_terms_page' );
			if ( $termsPage ) {
				$termsPageUrl = $termsPage;
			} else {
				$termsPageUrl = false;
			}
		} else {
			$termsPage = ccpa( 'options' )->get( 'terms_page' );
			if ( $termsPage ) {
				$termsPageUrl = get_permalink( $termsPage );
			} else {
				$termsPageUrl = false;
			}
		}

		if ( $termsPageUrl ) {
			ccpa( 'consent' )->register(
				'terms-conditions',
				sprintf(
					__( 'I accept the %1$sTerms & Conditions%2$s', 'ccpa-framework' ),
					"<a href='{$termsPageUrl}' target='_blank'>",
					'</a>'
				),
				_x( 'This consent is not visible by default. If someone wishes to withdraw it, they should simply request to delete all their data.', '(Admin)', 'ccpa-framework' ),
				false
			);
		}

		$this->register(
			'ccpa_cookie_consent',
			_x( 'Site Cookie Consent', '(Admin)', 'ccpa-framework' ),
			_x( 'This consent is not visible by default. If someone wishes to withdraw it, they should simply request to delete all their data.', '(Admin)', 'ccpa-framework' ),
			false
		);

		/****
		 * Woocommerce Policy consent
		 */
		if ( class_exists( 'WooCommerce' ) ) {
			ccpa( 'consent' )->register(
				'ccpa_woo_consent',
				_x( 'Woocommerce Policy Consent', '(Admin)', 'ccpa-framework' ),
				_x( 'This consent is visible by default on woocommerce checkout page. If someone wishes to withdraw it, they should simply request to delete all their data.', '(Admin)', 'ccpa-framework' ),
				true
			);
		}
	}

	/**
	 * Get a list of all registered consent types
	 *
	 * @return array
	 */
	public function getConsentTypes() {
		return apply_filters( 'ccpa/consent/types', $this->getDefaultConsentTypes() + $this->getCustomConsentTypes() );
	}

	/**
	 * Get all consent types registered by external sources, i.e. not stored in the database
	 *
	 * @return array
	 */
	public function getDefaultConsentTypes() {
		return apply_filters( 'ccpa/consent/types/default', $this->defaultConsentTypes );
	}

	/**
	 * Get all consent types registered by the admin, i.e. stored in the database
	 *
	 * @return array
	 */
	public function getCustomConsentTypes() {
		return apply_filters( 'ccpa/consent/types/custom', $this->customConsentTypes );
	}

	/**
	 * Register a *default* consent in the list of valid consents
	 *
	 * @param $consent
	 */
	public function register( $slug, $title, $description, $visible = true ) {
		$this->defaultConsentTypes[ $slug ] = array(
			'slug'        => $slug,
			'title'       => $title,
			'description' => $description,
			'visible'     => $visible,
		);
	}

	/**
	 * Register consent types saved via WP admin
	 */
	public function registerCustomConsentTypes() {
		$savedConsentTypes = ccpa( 'options' )->get( 'consent_types' );

		if (is_array($savedConsentTypes) && count($savedConsentTypes)) {
			foreach ($savedConsentTypes as $consentType) {
				/* FRAM-128 check for the slug key before the reference */
				if (!empty($consentType['slug'])) {
					$this->customConsentTypes[$consentType['slug']] = array(
						'slug'        => isset($consentType['slug']) ? $consentType['slug'] : '',
						'title'       => isset($consentType['title']) ? $consentType['title'] : '',
						'description' => isset($consentType['description']) ? $consentType['description'] : '',
						'visible'     => isset($consentType['visible']) ? $consentType['visible'] : '',
					);
				}
			}
		}
	}

	/**
	 * Save the given consent types to database
	 *
	 * @param $consentTypes
	 */
	public function saveCustomConsentTypes( $consentTypes ) {
		// Todo: validate to make sure something broken is not saved to DB
		ccpa( 'options' )->set( 'consent_types', $consentTypes );
	}

	/**
	 * Check if a consent is valid so that we don't write random stuff in the database by accident
	 *
	 * @param $consent
	 * @return bool
	 */
	public function isRegisteredConsent( $consent ) {
		return isset( $this->getConsentTypes()[ $consent ] );
	}

	/**
	 * Set a consent as 'given' for the data subject
	 *
	 * @param $email
	 * @param $consent
	 */
	public function giveConsent( $email, $consent, $valid_until = null )
	{
		if ($this->isRegisteredConsent($consent)) {
			$validation = apply_filters( 'ccpa/consent/give', true, $email, $consent );

			// If the data subject has already given this consent, do nothing
			if ( $this->model->given( $email, $consent ) || ! $validation ) {
				return;
			}

			$this->model->give( $email, $consent, $valid_until );
			do_action( 'ccpa/consent/given', $email, $consent );
		}
	}

	/**
	 * Set a consent as withdrawn for the data subject
	 *
	 * @param $email
	 * @param $consent
	 */
	public function withdrawConsent( $email, $consent )
	{
		if ($this->isRegisteredConsent($consent)) {
			$validation = apply_filters( 'ccpa/consent/withdraw', true, $email, $consent );

			// If the consent has never been given or if data subject has already withdrawn this consent, do nothing
			if ( ! $this->model->exists( $email, $consent ) || $this->model->withdrawn( $email, $consent ) || ! $validation ) {
				return;
			}

			$this->model->withdraw( $email, $consent );
			do_action( 'ccpa/consent/withdrawn', $email, $consent, 'withdrawn' );
		}
	}

	/**
	 * Remove consent given by subject
	 *
	 * @param $email
	 * @param $consent
	 */
	public function deleteConsent( $email, $consent )
	{
		if ($this->isRegisteredConsent($consent)) {
			if ( $this->model->given( $email, $consent ) ) {
				do_action( 'ccpa/consent/withdrawn', $email, $consent, 'deleted' );
			}

			$this->model->delete( $email, $consent );
		}
	}

	/**
	 * Withdraw and anonymize a consent
	 *
	 * @param $email
	 * @param $consent
	 * @param $anonymizedId
	 */
	public function anonymizeConsent( $email, $consent, $anonymizedId )
	{
		if ($this->isRegisteredConsent($consent)) {
			if ( $this->model->given( $email, $consent ) ) {
				do_action( 'ccpa/consent/withdrawn', $email, $consent, 'anonymized' );
			}

			$this->model->anonymize( $email, $consent, $anonymizedId );
		}
	}

	/**
	 * Get all consent given by subject
	 *
	 * @param $email
	 */
	public function getAllConsents( $email ) {
		return $this->model->getAll( $email );
	}

	/**
	 * Get all consent given by subject with other data
	 *
	 * @param $email
	 */
	public function getAllConsentswithdetails( $email ) {
		return $this->model->getAllwithdetails( $email );
	}

	/**
	 * Get all logs deleted for user
	 *
	 * @param $id
	 */
	public function ccpa_delete_log( $id ) {
		return $this->model->deletelog( $id );
	}

	/**
	 * Get all user logs
	 *
	 * @param $email
	 */
	public function getuserlogsData( $email ) {
		$usefromemail = get_user_by( 'email', $email );
		return $this->model->getuserlogs( $usefromemail->data->ID );
	}

	/**
	 * Get the registered consent types and add 'given' field depending
	 * on whether or not the user has given this particular consent
	 *
	 * @param $dataSubjectConsents
	 * @return array
	 */
	public function getConsentData( $dataSubjectConsents ) {

		$consentTypes = $this->getConsentTypes();
		$consents     = array();

		if ( $dataSubjectConsents ) {
			foreach ( $dataSubjectConsents as $consent => $subjectConsentType ) {
				$subjectConsentTypeArray[ $subjectConsentType->consent ]  = $subjectConsentType->consent;
				$subjectConsentUntilArray[ $subjectConsentType->consent ] = $subjectConsentType->valid_until;

			}
			foreach ( $consentTypes as $slug => $consentType ) {
				if ( in_array( $slug, $subjectConsentTypeArray ) ) {
					$consents[ $slug ]                = $consentType;
					$consents[ $slug ]['valid_until'] = $subjectConsentUntilArray[ $slug ];
				}
			}
		}
		return $consents;
	}
	public function getbySlugConsent( $dataSubjectConsents ) {
		if ( isset( $dataSubjectConsents ) && ! empty( $dataSubjectConsents ) ) {
			$dataSubjectConsents = sanitize_key( $dataSubjectConsents );
			$consentTypes        = $this->getConsentTypes();
			foreach ( $consentTypes as $slug => $consentType ) {
				if ( $slug === $dataSubjectConsents ) {
					return $consentType;
				}
			}
		}

	}

	/**
	 * Return a list of all data subjects who have given a particular consent
	 *
	 * @param $consent
	 */
	public function getAllDataSubjectsByConsent( $consent ) {
		// Todo
	}

	/**
	 * Return a list of all consent with other data
	 *
	 * @param $data and $email
	 */
	public function getdata( array $data, $email ) {
		$consents = $this->getAllConsentswithdetails( $email );
		if ( ! empty( $consents ) ) {
			$title = __( 'Consent Information', 'ccpa' );
			foreach ( $consents  as $i => $consent ) {
				$data[ $title ][ $i ]['consent']    = $consent->consent;
				$data[ $title ][ $i ]['updated_at'] = $consent->updated_at;
				$data[ $title ][ $i ]['ip']         = $consent->ip;
			}
		}
		return $data;
	}
	/**
	 * Withdraw and delete all consents given by a data subject
	 *
	 * @param $email
	 */
	public function delete( $email ) {
		$consents = $this->getAllConsents( $email );
		foreach ( $consents as $consent ) {
			$this->deleteConsent( $email, $consent->consent );
		}
	}

	/**
	 * Withdraw and anonymize all consents given by a data subject
	 *
	 * @param             $email
	 * @param             $anonymizedId
	 */
	public function anonymize( $email, $anonymizedId ) {
		$consents = $this->getAllConsents( $email );
		foreach ( $consents as $consent ) {
			$this->anonymizeConsent( $email, $consent->consent, $anonymizedId );
		}
	}
}
