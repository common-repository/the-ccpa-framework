<?php

namespace Data443\CCPA\DataSubject;

/**
 * Class DataRepository
 *
 * @package Data443\CCPA\DataSubject
 */
class DataRepository {

	/**
	 * DataRepository constructor.
	 *
	 * @param $email
	 */
	public function __construct( $email ) {
		$this->email = $email;
	}

	/**
	 * Export all stored data. Triggers 'ccpa/data-subject/data' filter.
	 */
	public function getData( $email ) {
		return apply_filters( 'ccpa/data-subject/data', array(), $email );
	}

	/**
	 * Trigger the configured 'export' action
	 *
	 * @param $email
	 * @return array|null
	 */
	public function export( $email, $format, $force = false ) {
		$action = ccpa( 'options' )->get( 'export_action' );
		$data   = null;

		if ( $force ) {
			$action = 'download';
		}

		switch ( $action ) {
			case 'download':
				$data = $this->getData( $email );
				break;
			case 'download_and_notify':
				$data = $this->getData( $email );
				$this->notifyExportAction( $email, $format );
				break;
			case 'notify':
				$this->notifyExportRequest( $email, $format );
				break;
			default:
				$this->notifyExportRequest( $email, $format );
				break;
		}

		return $data;
	}

	/**
	 * Trigger the configured 'forget' action
	 *
	 * @param $email
	 *
	 * @return bool
	 */
	public function forget( $email, $forceAction = null ) {
		$action = ccpa( 'options' )->get( 'delete_action' );

		if ( $forceAction ) {
			$action = $forceAction;
		}

		switch ( $action ) {
			case 'delete':
				$this->delete( $email );
				return true;
			case 'delete_and_notify':
				$userId = $this->delete( $email );
				$this->notifyForgetAction( $email, $userId );
				return true;
			case 'anonymize':
				$this->anonymize( $email );
				return true;
			case 'anonymize_and_notify':
				$userId = $this->anonymize( $email );
				$this->notifyForgetAction( $email, $userId );
				return true;
			case 'notify':
				$this->notifyForgetRequest( $email );
				return false;
			default:
				$this->notifyForgetRequest( $email );
				return false;
		}
	}

	/**
	 * @param $email
	 */
	protected function anonymize( $email ) {
		$userId = null;

		if ( email_exists( $email ) ) {
			$userId = get_user_by( 'email', $email )->ID;
		}

		$anonymizedId = wp_generate_password( 12, false );
		do_action( 'ccpa/data-subject/anonymize', $email, $anonymizedId, $userId );

		return intval( $userId );
	}

	/**
	 * @param $email
	 */
	protected function delete( $email ) {
		$userId = null;

		if ( email_exists( $email ) ) {
			$userId = get_user_by( 'email', $email )->ID;
		}

		do_action( 'ccpa/data-subject/delete', $email, $userId );

		return intval( $userId );
	}

	/**
	 * @param $email
	 */
	protected function notifyExportAction( $email, $format ) {
		ccpa( 'helpers' )->mail(
			ccpa( 'options' )->get( 'export_action_email' ),
			__( 'Data exported', 'ccpa-framework' ),
			ccpa( 'view' )->render( 'email/action-export', compact( 'email', 'format' ) ),
			array( 'Content-Type: text/html; charset=UTF-8' )
		);
	}

	/**
	 * @param $email
	 */
	protected function notifyExportRequest( $email, $format ) {
		$adminTabLink = esc_url( ccpa( 'helpers' )->getAdminUrl( '&ccpa-tab=data-subject&search=' . $email ) );

		ccpa( 'helpers' )->mail(
			ccpa( 'options' )->get( 'export_action_email' ),
			__( 'Data export request', 'ccpa-framework' ),
			ccpa( 'view' )->render( 'email/request-export', compact( 'email', 'format', 'adminTabLink' ) ),
			array( 'Content-Type: text/html; charset=UTF-8' )
		);
	}

	/**
	 * @param $email
	 */
	protected function notifyForgetAction( $email, $userId = null ) {
		ccpa( 'helpers' )->mail(
			ccpa( 'options' )->get( 'delete_action_email' ),
			__( 'Data removed', 'ccpa-framework' ),
			ccpa( 'view' )->render( 'email/action-forget', compact( 'email', 'userId' ) ),
			array( 'Content-Type: text/html; charset=UTF-8' )
		);
	}

	/**
	 * @param $email
	 */
	protected function notifyForgetRequest( $email ) {
		$adminTabLink = esc_url( ccpa( 'helpers' )->getAdminUrl( '&ccpa-tab=data-subject&search=' . $email ) );

		ccpa( 'helpers' )->mail(
			ccpa( 'options' )->get( 'delete_action_email' ),
			__( 'Data removal request', 'ccpa-framework' ),
			ccpa( 'view' )->render( 'email/request-forget', compact( 'email', 'adminTabLink' ) ),
			array( 'Content-Type: text/html; charset=UTF-8' )
		);
	}
}
