<?php
namespace Data443\CCPA\Modules\EddCcpa;

use Data443\CCPA\Components\Consent\ConsentManager;
use Data443\CCPA\DataSubject\DataSubjectManager;

require_once EDD_PLUGIN_DIR . 'includes/class-edd-customer.php';
require_once EDD_PLUGIN_DIR . 'includes/class-edd-download.php';
require_once EDD_PLUGIN_DIR . 'includes/privacy-functions.php';

class EddCcpa {

	public function __construct( DataSubjectManager $dataSubjectManager, ConsentManager $consentManager ) {
		$this->dataSubjectManager = $dataSubjectManager;
		$this->consentManager     = $consentManager;
		if ( ! ccpa( 'options' )->get( 'enable_edd_compatibility' ) ) {
			return;
		}
		if ( ! class_exists( 'Easy_Digital_Downloads' ) ) {
			return;
		}
		$show_agree_to_privacy = edd_get_option( 'show_agree_to_privacy_policy', false );
		add_filter( 'ccpa/data-subject/data', array( $this, 'getEddExportData' ), 20, 2 );
		add_action( 'ccpa/data-subject/delete', array( $this, 'deleteEddEntries' ) );
		add_action( 'ccpa/data-subject/anonymize', array( $this, 'anonymizeEddEntries' ) );
		if ( $show_agree_to_privacy > 0 ) {
			add_action( 'edd_complete_purchase', array( $this, 'edd_ccpa_consent' ) );
		}
	}

	public function getEddExportData( array $data, $email ) {
		if ( function_exists( 'edd_privacy_prefetch_customer_id' ) ) {
			edd_privacy_prefetch_customer_id( $email, 1 );
		}
		if ( function_exists( 'edd_privacy_customer_record_exporter' ) ) {
			$customer_information = edd_privacy_customer_record_exporter( $email, $page = 1 );
			if ( empty( $customer_information['data'] ) ) {
				return $data;
			}
			$title               = __( 'EDD Customer Information', 'ccpa-framework' );
			$data[ $title ]['0'] = $customer_information;
		}
		if ( function_exists( 'edd_privacy_billing_information_exporter' ) ) {
			$billing_information = edd_privacy_billing_information_exporter( $email, 1 );
			if ( empty( $billing_information['data'] ) ) {
				return $data;
			}
			$ordertitle          = __( 'EDD Order Information', 'ccpa-framework' );
			$data[ $ordertitle ] = $billing_information;
		}
		if ( function_exists( 'edd_privacy_file_download_log_exporter' ) ) {
			$file_download = edd_privacy_file_download_log_exporter( $email, 1 );
			if ( empty( $file_download['data'] ) ) {
				return $data;
			}
			$ordertitle          = __( 'EDD File Downloads', 'ccpa-framework' );
			$data[ $ordertitle ] = $file_download;
		}
		if ( function_exists( 'edd_privacy_api_access_log_exporter' ) ) {
			$api_access_log = edd_privacy_api_access_log_exporter( $email, 1 );
			if ( empty( $api_access_log['data'] ) ) {
				return $data;
			}
			$ordertitle          = __( 'EDD API Access Logs', 'ccpa-framework' );
			$data[ $ordertitle ] = $api_access_log;
		}
		return json_decode( json_encode( $data ), true );
	}
	/*
	*   Delete user information from order by email address.
	*/
	public function deleteEddEntries( $email ) {
		$this->edd_ccpa_delete_orders( $email );
	}
	/*
	*   Anonymize user information from order by email address.
	*/
	public function anonymizeEddEntries( $email ) {
		if ( function_exists( 'edd_privacy_customer_anonymizer' ) ) {
			edd_privacy_customer_anonymizer( $email, 1 );
		}
		if ( function_exists( 'edd_privacy_payment_eraser' ) ) {
			edd_privacy_payment_eraser( $email, 1 );
		}
		if ( function_exists( 'edd_privacy_file_download_logs_eraser' ) ) {
			edd_privacy_file_download_logs_eraser( $email, 1 );
		}
		if ( function_exists( 'edd_privacy_api_access_logs_eraser' ) ) {
			edd_privacy_api_access_logs_eraser( $email, 1 );
		}
	}
	/*
	*   Delete all order infromation from email.
	*/
	private function edd_ccpa_delete_orders( $email ) {
		/*
		*    delete order with all information
		*/
		global $wpdb;

		$delete_customermeta = $wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->prefix}edd_customermeta WHERE customer_id IN (SELECT  user_id FROM {$wpdb->prefix}edd_customers WHERE email = %s)", $email ) );

		$delete_customer = $wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->prefix}edd_customers WHERE email =%s", $email ) );

		$delete_purchase = $wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->prefix}posts WHERE ID IN (SELECT  post_id FROM {$wpdb->prefix}postmeta WHERE meta_key = %s AND meta_value = %s)", '_edd_payment_user_email', $email ) );

		$delete_purchasemeta = $wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->prefix}postmeta WHERE post_id IN (select edd_id from(SELECT  post_id as edd_id FROM {$wpdb->prefix}postmeta WHERE meta_key = %s AND meta_value = %d) post_ids)", '_edd_payment_user_email', $email ) );
	}
	/*
	*   Add checkout CCPA content
	*/
	public function edd_ccpa_consent( $payment_id ) {
		if ( function_exists( 'edd_get_payment_meta' ) ) {
			$payment_meta = edd_get_payment_meta( $payment_id );
			$dataSubject  = $this->dataSubjectManager->getByEmail( $payment_meta['email'] );
			$dataSubject->giveConsent( 'privacy-policy' );
		}
	}

}
