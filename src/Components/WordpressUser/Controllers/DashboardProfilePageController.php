<?php

namespace Data443\CCPA\Components\WordpressUser\Controllers;

use Data443\CCPA\DataSubject\DataExporter;
use Data443\CCPA\DataSubject\DataSubject;
use Data443\CCPA\DataSubject\DataSubjectManager;

class DashboardProfilePageController {

	public function __construct( DataSubjectManager $dataSubjectManager, DataExporter $dataExporter ) {
		$this->dataSubjectManager = $dataSubjectManager;
		$this->dataExporter       = $dataExporter;

		add_action( 'ccpa/dashboard/profile-page/content', array( $this, 'renderHeader' ), 10 );
		add_action( 'ccpa/dashboard/profile-page/content', array( $this, 'renderConsentTable' ), 20 );
		add_action( 'ccpa/dashboard/profile-page/content', array( $this, 'renderExportForm' ), 30 );
		add_action( 'ccpa/dashboard/profile-page/content', array( $this, 'renderDeleteForm' ), 40 );
		add_action( 'ccpa/dashboard/profile-page/contentuser', array( $this, 'renderHeader' ), 10 );
		add_action( 'ccpa/dashboard/profile-page/contentuser', array( $this, 'renderConsentTable' ), 20 );
		add_action( 'ccpa/dashboard/profile-page/userlogs', array( $this, 'ccpa_user_logs' ), 50 );

		add_action( 'ccpa/admin/action/export', array( $this, 'export' ) );
		add_action( 'ccpa/admin/action/forget', array( $this, 'forget' ) );
	}

	protected function isUserAnonymized( DataSubject $dataSubject ) {
		return ! $dataSubject->getEmail();
	}

	public function renderHeader( DataSubject $dataSubject ) {
		$isAnonymized = $this->isUserAnonymized( $dataSubject );

		echo ccpa( 'view' )->render(
			'modules/wordpress-user/dashboard/profile-page/header',
			compact( 'isAnonymized' )
		);
	}


	public function ccpa_user_logs( DataSubject $dataSubject ) {
		if ( $this->isUserAnonymized( $dataSubject ) ) {
			return;
		}

		$userlogData = $dataSubject->getuserlogsData();
		echo ccpa( 'view' )->render(
			'modules/wordpress-user/dashboard/profile-page/user-logs',
			compact( 'userlogData' )
		);
	}

	public function renderConsentTable( DataSubject $dataSubject ) {
		if ( $this->isUserAnonymized( $dataSubject ) ) {
			return;
		}

		$consentData = $dataSubject->getConsentData();

		echo ccpa( 'view' )->render(
			'modules/wordpress-user/dashboard/profile-page/table-consent',
			compact( 'consentData' )
		);
	}

	public function renderExportForm( DataSubject $dataSubject ) {
		if ( $this->isUserAnonymized( $dataSubject ) ) {
			return;
		}

		$exportHTMLUrl = add_query_arg(
			array(
				'ccpa_action' => 'export',
				'ccpa_format' => 'html',
				'ccpa_email'  => $dataSubject->getEmail(),
				'ccpa_nonce'  => wp_create_nonce( 'ccpa/admin/action/export' ),
			)
		);

		$exportJSONUrl = add_query_arg(
			array(
				'ccpa_action' => 'export',
				'ccpa_format' => 'json',
				'ccpa_email'  => $dataSubject->getEmail(),
				'ccpa_nonce'  => wp_create_nonce( 'ccpa/admin/action/export' ),
			)
		);

		echo ccpa( 'view' )->render(
			'modules/wordpress-user/dashboard/form-export',
			compact( 'exportHTMLUrl', 'exportJSONUrl' )
		);
	}

	public function renderDeleteForm( DataSubject $dataSubject ) {
		if ( $this->isUserAnonymized( $dataSubject ) ) {
			return;
		}

		// Hide the delete button away from site admins on their own profile page to avoid accidents
		$showDelete = ! ( current_user_can( 'manage_options' ) && wp_get_current_user()->ID === $dataSubject->getUserId() );

		$anonymizeUrl = add_query_arg(
			array(
				'ccpa_email'        => $dataSubject->getEmail(),
				'ccpa_action'       => 'forget',
				'ccpa_force_action' => 'anonymize',
				'ccpa_nonce'        => wp_create_nonce( 'ccpa/admin/action/forget' ),
			)
		);

		$deleteUrl = add_query_arg(
			array(
				'ccpa_email'        => $dataSubject->getEmail(),
				'ccpa_action'       => 'forget',
				'ccpa_force_action' => 'delete',
				'ccpa_nonce'        => wp_create_nonce( 'ccpa/admin/action/forget' ),
			)
		);

		echo ccpa( 'view' )->render(
			'modules/wordpress-user/dashboard/profile-page/form-delete',
			compact( 'anonymizeUrl', 'deleteUrl', 'showDelete' )
		);
	}

	public function export() {
		$ccpa_email  = sanitize_email( $_REQUEST['ccpa_email'] );
		$ccpa_format = sanitize_key( $_REQUEST['ccpa_format'] );
		$dataSubject = $this->dataSubjectManager->getByEmail( $ccpa_email );
		$data        = $dataSubject->export( $ccpa_format, true );
		$this->dataExporter->export( $data, $dataSubject, $ccpa_format );
	}

	public function forget() {
		$ccpa_email        = sanitize_email( $_REQUEST['ccpa_email'] );
		$ccpa_force_action = sanitize_key( $_REQUEST['ccpa_force_action'] );
		$dataSubject       = $this->dataSubjectManager->getByEmail( $ccpa_email );
		$dataSubject->forget( $ccpa_force_action );

		wp_safe_redirect( admin_url( 'users.php' ) );
	}
}
