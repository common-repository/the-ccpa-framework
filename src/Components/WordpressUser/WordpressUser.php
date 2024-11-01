<?php

namespace Data443\CCPA\Components\WordpressUser;

use Data443\CCPA\DataSubject\DataSubjectManager;
use Data443\CCPA\Components\WordpressUser\Controllers\DashboardDataPageController;
use Data443\CCPA\Components\WordpressUser\Controllers\DashboardProfilePageController;

/**
 * Handles everything related to a WordPress user account
 *
 * Class WordpressUser
 *
 * @package Data443\CCPA\Modules\WordpressUser
 */
class WordpressUser {

	/* @var string */
	protected $name = 'wordpress-user';

	/* @var DataManager */
	protected $dataManager;

	/* @var DataSubjectManager */
	protected $dataSubjectManager;

	/**
	 * WordpressUser constructor.
	 *
	 * @param DataSubjectManager $dataSubjectManager
	 * @param DataManager        $dataManager
	 */
	public function __construct( DataSubjectManager $dataSubjectManager, DataManager $dataManager ) {
        global $ccpa;
		$this->dataSubjectManager = $dataSubjectManager;
		$this->dataManager        = $dataManager;

        new DashboardProfilePageController($dataSubjectManager, $ccpa->DataExporter);
        new RegistrationForm($dataSubjectManager);

		if ( ccpa( 'options' )->get( 'enable' ) ) {
			new DashboardDataPageController($ccpa->DataExporter, $ccpa->DataSubjectAuthenticator);

			// Register Privacy Tools page in admin
			add_action( 'admin_menu', array( $this, 'registerDashboardDataPage' ) );
		}

		// Register render action on Profile edit page
		add_action( 'show_user_profile', array( $this, 'triggerProfileRenderAction' ), PHP_INT_MAX );
		add_action( 'edit_user_profile', array( $this, 'triggerProfileRenderAction' ), PHP_INT_MAX );

		add_filter( 'ccpa/data-subject/data', array( $this, 'getExportData' ), 1, 2 );
		add_action( 'ccpa/data-subject/delete', array( $this, 'deleteUser' ), 100 );
		add_action( 'ccpa/data-subject/anonymize', array( $this, 'anonymizeUser' ), 100, 2 );
	}

	/**
	 * Register Privacy Tools dashboard page under Users
	 */
	public function registerDashboardDataPage() {
		add_users_page(
			_x( 'Privacy Tools', '(Admin)', 'ccpa-framework' ),
			_x( 'Privacy Tools', '(Admin)', 'ccpa-framework' ),
			'read',
			'ccpa-profile',
			array( $this, 'renderDashboardDataPage' )
		);
	}

	/**
	 * Render the contents of Privacy Tools dashboard page
	 */
	public function renderDashboardDataPage() {
		$dataSubject = $this->dataSubjectManager->getByLoggedInUser();

		if ( $dataSubject ) {
			do_action( 'ccpa/dashboard/privacy-tools/content', $dataSubject );
		}
	}

	/**
	 * On profile page, trigger an action with the same format as the Router provides
	 * so that we have consistency with the rest of the hooks.
	 */
	public function triggerProfileRenderAction( \WP_User $user ) {
		if ( current_user_can( 'edit_users' ) || current_user_can( 'delete_users' ) ) {
			$dataSubject = $this->dataSubjectManager->getByEmail( $user->user_email );
			do_action( 'ccpa/dashboard/profile-page/content', $dataSubject );
			do_action( 'ccpa/dashboard/profile-page/userlogs', $dataSubject );
		} else {
			$dataSubject = $this->dataSubjectManager->getByEmail( $user->user_email );
			do_action( 'ccpa/dashboard/profile-page/contentuser', $dataSubject );
			do_action( 'ccpa/dashboard/profile-page/userlogs', $dataSubject );
		}
	}

	public function getExportData( $data, $email ) {
		return $data + $this->dataManager->getData( $this->dataSubjectManager->getByEmail( $email ) );
	}

	public function deleteUser( $email ) {
		add_filter( 'send_email_change_email', '__return_false' );
		add_filter( 'send_password_change_email', '__return_false' );
		$dataSubject = $this->dataSubjectManager->getByEmail( $email );
		$this->dataManager->deleteUser( $dataSubject );
	}

	public function anonymizeUser( $email, $anonymizedId ) {
		add_filter( 'send_email_change_email', '__return_false' );
		add_filter( 'send_password_change_email', '__return_false' );
		$dataSubject = $this->dataSubjectManager->getByEmail( $email );
		$this->dataManager->anonymizeUser( $dataSubject, $anonymizedId );
	}
}
