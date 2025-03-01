<?php

namespace Data443\CCPA\DataSubject;

/**
 * Handle authenticating the data subject either by logged in user or by email/cookie
 *
 * Class DataSubjectAuthenticator
 *
 * @package Data443\CCPA\DataSubject
 */
class DataSubjectAuthenticator {

	/**
	 * DataSubjectAuthenticator constructor.
	 *
	 * @param DataSubjectManager       $dataSubjectManager
	 * @param DataSubjectIdentificator $dataSubjectIdentificator
	 */
	public function __construct( DataSubjectManager $dataSubjectManager, DataSubjectIdentificator $dataSubjectIdentificator ) {
		$this->dataSubjectManager       = $dataSubjectManager;
		$this->dataSubjectIdentificator = $dataSubjectIdentificator;
	}

	/**
	 * Attempt to authenticate the data subject
	 *
	 * @return bool|\Data443\CCPA\DataSubject\DataSubject
	 */
	public function authenticate() {
		// If the user is logged in, authenticate them
		if ( is_user_logged_in() ) {
			return apply_filters( 'ccpa/authenticate', $this->dataSubjectManager->getByLoggedInUser() );
		}

		// If the request contains the identification cookie, validate it and identify the
		// current user
		$cookieData = $this->getIdentificationCookieData();
		if ( $cookieData && $this->dataSubjectIdentificator->isKeyValid( $cookieData[0], $cookieData[1] ) ) {
			return apply_filters( 'ccpa/authenticate', $this->dataSubjectManager->getByEmail( $cookieData[0] ) );
		}

		// Otherwise, we are not authenticated
		return apply_filters( 'ccpa/authenticate', false );
	}

	/**
	 * If the request contains a new identification key, validate it, then set a new key
	 * to make the previous link obsolete.
	 */
	public function identify() {
		// Do not attempt to identify logged in users
		if ( is_user_logged_in() ) {
			return;
		}

		if ( isset( $_REQUEST['ccpa_key'] ) && isset( $_REQUEST['email'] ) ) {
			$ccpa_email          = sanitize_email( $_REQUEST['email'] );
			$privacyToolsPageUrl = get_permalink( ccpa( 'options' )->get( 'tools_page' ) );
			$privacyToolsPageUrl = apply_filters( 'privacy_tools_ccpaf_page_url', $privacyToolsPageUrl );
			if ( $this->dataSubjectIdentificator->isKeyValid( $ccpa_email, $_REQUEST['ccpa_key'] ) ) {
				$this->setIdentificationCookie( $ccpa_email );
				$url = $privacyToolsPageUrl;
			} else {
				$url = add_query_arg(
					array(
						'ccpa_notice' => 'invalid_key',
					),
					$privacyToolsPageUrl
				);
			}

			wp_redirect( $url );
			exit;
		}
	}

	/**
	 * Set the identification cookie with the given key
	 *
	 * @param $key
	 */
	public function setIdentificationCookie( $email ) {
		$key = $this->dataSubjectIdentificator->generateKey( $email );

		setcookie(
			'ccpa_key',
			$email . '|' . $key,
			time() + ( 15 * 60 ),
			COOKIEPATH,
			COOKIE_DOMAIN,
			false,
			true
		);
	}

	/**
	 * @return string
	 */
	public function getIdentificationCookieData() {
		return isset( $_COOKIE['ccpa_key'] ) ? explode( '|', $_COOKIE['ccpa_key'] ) : null;
	}

	/**
	 * Remove the cookie
	 */
	public function deleteSession( $logout = true ) {
		unset( $_COOKIE['ccpa_key'] );

		setcookie(
			'ccpa_key',
			'',
			time() - 3600,
			COOKIEPATH,
			COOKIE_DOMAIN,
			false,
			true
		);

		if ( $logout ) {
			wp_logout();
		} else {
			wp_destroy_current_session();
			wp_clear_auth_cookie();
		}
	}
}
