<?php

namespace Data443\CCPA\Components\WordpressUser\Controllers;

use Data443\CCPA\DataSubject\DataExporter;
use Data443\CCPA\DataSubject\DataSubject;
use Data443\CCPA\DataSubject\DataSubjectAuthenticator;

/**
 * Handles Users > Privacy Tools page
 *
 * Class DashboardDataPageController
 *
 * @package Data443\CCPA\Modules\WordpressUser\Controllers
 */
class DashboardDataPageController {

	/**
	 * DashboardDataPageController constructor.
	 *
	 * @param DataExporter $dataExporter
	 */
	public function __construct( DataExporter $dataExporter, DataSubjectAuthenticator $dataSubjectAuthenticator ) {
		$this->dataExporter             = $dataExporter;
		$this->dataSubjectAuthenticator = $dataSubjectAuthenticator;

		add_action( 'ccpa/dashboard/privacy-tools/content', array( $this, 'renderHeader' ), 10 );
		add_action( 'ccpa/dashboard/privacy-tools/content', array( $this, 'renderConsentForm' ), 20 );
		add_action( 'ccpa/dashboard/privacy-tools/content', array( $this, 'renderExportForm' ), 30 );
		add_action( 'ccpa/dashboard/privacy-tools/content', array( $this, 'renderDeleteForm' ), 40 );

		add_action( 'ccpa/dashboard/privacy-tools/action/withdraw_consent', array( $this, 'withdrawConsent' ) );
		add_action( 'ccpa/dashboard/privacy-tools/action/export', array( $this, 'export' ) );
		add_action( 'ccpa/dashboard/privacy-tools/action/forget', array( $this, 'forget' ) );

		add_action( 'admin_notices', array( $this, 'renderAdminNotices' ) );
	}

	/**
	 * Render success notices via admin_notice action
	 */
	public function renderAdminNotices() {
		if ( 'profile_page_ccpa-profile' !== get_current_screen()->base ) {
			return;
		}

		if ( ! isset( $_REQUEST['ccpa_notice'] ) ) {
			return;
		}

		if ( 'request_sent' === sanitize_key( $_REQUEST['ccpa_notice'] ) ) {
			$message = __( 'We have received your request and will reply within 30 days.', 'ccpa-framework' );
			$class   = 'notice notice-success';
		}

		if ( 'consent_withdrawn' === sanitize_key( $_REQUEST['ccpa_notice'] ) ) {
			$message = __( 'Consent withdrawn.', 'ccpa-framework' );
			$class   = 'notice notice-success';
		}

		echo ccpa( 'view' )->render( 'admin/notice', compact( 'message', 'class' ) );
	}

	/**
	 * Render page header
	 */
	public function renderHeader() {
		echo ccpa( 'view' )->render(
			'modules/wordpress-user/dashboard/data-page/header'
		);
	}

	/**
	 * Render the consent form
	 *
	 * @param DataSubject $dataSubject
	 */
	public function renderConsentForm( DataSubject $dataSubject ) {
		$consentData = $dataSubject->getVisibleConsentData();

		foreach ( $consentData as &$item ) {
			$item['withdraw_url'] = add_query_arg(
				array(
					'ccpa_action' => 'withdraw_consent',
					'ccpa_nonce'  => wp_create_nonce( 'ccpa/dashboard/privacy-tools/action/withdraw_consent' ),
					'email'       => $dataSubject->getEmail(),
					'consent'     => $item['slug'],
				)
			);
		}

		$consentInfo = wpautop( ccpa( 'options' )->get( 'consent_info' ) );

		echo ccpa( 'view' )->render(
			'modules/wordpress-user/dashboard/data-page/form-consent',
			compact( 'consentData', 'consentInfo' )
		);
	}

	/**
	 * Render the buttons that allow exporting data
	 */
	public function renderExportForm() {
		$exportHTMLUrl = add_query_arg(
			array(
				'ccpa_action' => 'export',
				'ccpa_format' => 'html',
				'ccpa_nonce'  => wp_create_nonce( 'ccpa/dashboard/privacy-tools/action/export' ),
			)
		);

		$exportJSONUrl = add_query_arg(
			array(
				'ccpa_action' => 'export',
				'ccpa_format' => 'json',
				'ccpa_nonce'  => wp_create_nonce( 'ccpa/dashboard/privacy-tools/action/export' ),
			)
		);

		echo ccpa( 'view' )->render(
			'modules/wordpress-user/dashboard/form-export',
			compact( 'exportHTMLUrl', 'exportJSONUrl' )
		);
	}

	/**
	 * Render the delete data button
	 */
	public function renderDeleteForm() {
		$showDelete = ! current_user_can( 'manage_options' );
		$url        = add_query_arg(
			array(
				'ccpa_action' => 'forget',
				'ccpa_nonce'  => wp_create_nonce( 'ccpa/dashboard/privacy-tools/action/forget' ),
			)
		);

		echo ccpa( 'view' )->render(
			'modules/wordpress-user/dashboard/data-page/form-delete',
			compact( 'url', 'showDelete' )
		);
	}

	/**
	 * @param DataSubject $dataSubject
	 */
	public function withdrawConsent( DataSubject $dataSubject ) {
		$consent = sanitize_key( $_REQUEST['consent'] );
		$dataSubject->withdrawConsent( $consent );
		$this->redirect( array( 'ccpa_notice' => 'consent_withdrawn' ) );
	}

	/**
	 * @param DataSubject $dataSubject
	 */
	public function export( DataSubject $dataSubject ) {
		$ccpa_format = sanitize_key( $_REQUEST['ccpa_format'] );
		$data        = $dataSubject->export( $ccpa_format );

		if ( ! is_null( $data ) ) {
			// If there is data, download it
			$this->dataExporter->export( $data, $dataSubject, $ccpa_format );
		} else {
			// If there's no data, then show notification that your request has been sent.
			$this->redirect( array( 'ccpa_notice' => 'request_sent' ) );
		}
	}

	/**
	 * @param DataSubject $dataSubject
	 */
	public function forget( DataSubject $dataSubject ) {
		$status = $dataSubject->forget();

		if ( ! $status ) {
			$this->redirect( array( 'ccpa_notice' => 'request_sent' ) );
		} else {
			$this->dataSubjectAuthenticator->deleteSession();
			$this->redirect( array(), '/' );
		}
	}

	/**
	 * Redirect the visitor to an appropriate location
	 *
	 * @param array $args
	 * @param null  $baseUrl
	 */
	protected function redirect( $args = array(), $baseUrl = null ) {
		if ( ! $baseUrl ) {
			$baseUrl = ccpa( 'helpers' )->getDashboardDataPageUrl();
		}

		wp_safe_redirect( add_query_arg( $args, $baseUrl ) );
		exit;
	}
}
