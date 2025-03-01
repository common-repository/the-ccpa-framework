<?php

namespace Data443\CCPA\DataSubject;

use Data443\CCPA\Components\Consent\ConsentManager;

/**
 * Handles finding data subjects by ID or email
 *
 * Class DataSubjectManager
 *
 * @package Data443\CCPA\DataSubject
 */
class DataSubjectManager {

	/* @var ConsentManager */
	protected $consentManager;

	/**
	 * DataSubjectManager constructor.
	 *
	 * @param ConsentManager $consentManager
	 */
	public function __construct( ConsentManager $consentManager ) {
		$this->consentManager = $consentManager;
	}

	/**
	 * @param $email
	 * @return DataSubject
	 */
    public function getByEmail( $email ) {
        $email = sanitize_email($email);
        $user = get_user_by('email', $email);

        $dataSubject = new DataSubject($email, $user?$user:null, $this->consentManager);
        return $dataSubject;
    }

	 /**
	  * @param $email
	  * @return DataSubject
	  */
	public function getuserlogs( $email ) {
		$user = get_user_by( 'email', sanitize_email( $email ) );

	}

	/**
	 * @param $id
	 * @return DataSubject|false
	 */
    public function getById($id)
    {
        $user = get_user_by('id', $id);

        if (!$user) {
            return false;
        }

        $email = sanitize_email($user->user_email);
        $dataSubject = new DataSubject($email, $user, $this->consentManager);
        return $dataSubject;
    }

	/**
	 * @return bool|DataSubject
	 */
	public function getByLoggedInUser() {
		if ( ! is_user_logged_in() ) {
			return false;
		}

		return $this->getById( get_current_user_id() );
	}
}
