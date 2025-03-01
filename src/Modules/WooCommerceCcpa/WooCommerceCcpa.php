<?php
namespace Data443\CCPA\Modules\WooCommerceCcpa;

use Data443\CCPA\Components\Consent\ConsentManager;
use Data443\CCPA\DataSubject\DataSubjectManager;
require_once WC_ABSPATH . 'includes/class-wc-privacy-exporters.php';
require_once WC_ABSPATH . 'includes/class-wc-privacy-erasers.php';

class WooCommerceCcpa {

	public function __construct( DataSubjectManager $dataSubjectManager, ConsentManager $consentManager ) {
		$this->dataSubjectManager = $dataSubjectManager;
		$this->consentManager     = $consentManager;

		if ( ! ccpa( 'options' )->get( 'enable_woo_compatibility' ) ) {
			return;
		}
		if ( ! class_exists( 'WooCommerce' ) ) {
			return;
		}
		add_filter( 'ccpa/data-subject/data', array( $this, 'getWoocommerceExportData' ), 20, 2 );
		add_action( 'ccpa/data-subject/delete', array( $this, 'deleteWoocommerceEntries' ) );
		add_action( 'ccpa/data-subject/anonymize', array( $this, 'anonymizeWoocommerceEntries' ) );
		if ( ! ccpa( 'options' )->get( 'disable_checkbox_woo_compatibility' ) ) {
			add_action( 'woocommerce_review_order_before_submit', array( $this, 'ccpa_woo_add_checkout_privacy_policy' ), 10 );
			add_action( 'woocommerce_checkout_process', array( $this, 'ccpa_woo_not_approved_privacy' ) );
		}
		if ( ! ccpa( 'options' )->get( 'disable_register_checkbox_woo_compatibility' ) ) {
			add_action( 'woocommerce_register_form', array( $this, 'ccpa_woo_add_checkout_privacy_policy' ), 11 );
			add_filter( 'woocommerce_registration_errors', array( $this, 'ccpaf_validate_privacy_registration' ), 10, 3 );
		}
	}
	/*
	*   Fatch all order with details have following status. 'wc-pending','wc-on-hold','wc-processing', 'wc-completed','wc-cancelled','wc-refunded','wc-failed'
	*
	*/
	public function getWoocommerceExportData( array $data, $email ) {
		$customer_information = \WC_Privacy_Exporters::customer_data_exporter( $email );
		if ( ! empty( $customer_information['data'] ) ) {
			$title               = __( 'Customer Information', 'ccpa' );
			$data[ $title ]['0'] = $customer_information;
		}
		$export_items = \WC_Privacy_Exporters::order_data_exporter( $email, -1 );
		if ( empty( $export_items['data'] ) ) {
			return $data;
		}
		$ordertitle          = __( 'Order Information', 'ccpa' );
		$data[ $ordertitle ] = $export_items;
		return json_decode( json_encode( $data ), true );
	}
	/*
	*   'wc-pending','wc-on-hold','wc-completed','wc-cancelled','wc-refunded','wc-failed'
	*   will change wc-completed order's user to anonymize
	*   delete other all orders.
	*/
	public function deleteWoocommerceEntries( $email ) {
		$order_statuses  = wc_get_order_statuses();
		$customer_orders = wc_get_orders(
			array(
				'meta_key'    => '_billing_email',
				'meta_value'  => $email,
				'post_status' => array_keys( $order_statuses ),
				'numberposts' => -1,
			)
		);
		if ( $customer_orders ) {
			foreach ( $customer_orders as $order ) {
				$order_id = method_exists( $order, 'get_id' ) ? $order->get_id() : $order->id;
				if ( $order->get_status() != 'completed' || $order->get_status() != 'processing' ) {
					$this->wc_ccpa_delete_orders( $order_id );
				}
				if ( $order->get_status() == 'completed' ) {
					$this->anonymizeWoocommerceEntries( $email );
				}
			}
		}
	}
	/*
	*   Anonymize user information from order by email address.
	*   processing order will not get anonymized
	*/
	public function anonymizeWoocommerceEntries( $email ) {
		$order_statuses  = wc_get_order_statuses();
		$customer_orders = wc_get_orders(
			array(
				'meta_key'    => '_billing_email',
				'meta_value'  => $email,
				'post_status' => array_keys( $order_statuses ),
				'numberposts' => -1,
			)
		);
		if ( $customer_orders ) {
			foreach ( $customer_orders as $order ) {
				if ( $order->get_status() != 'processing' ) {
					\WC_Privacy_Erasers::remove_order_personal_data( $order );
				}
			}
		}
	}
	/*
	*   Delete all order infromation from order ID.
	*/
	public function wc_ccpa_delete_orders( $order_id ) {
		/*
		*   delete order with all information
		*/
		global $wpdb;

		$delete_order_itemmeta = $wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->prefix}woocommerce_order_itemmeta WHERE order_item_id IN (SELECT  order_item_id FROM {$wpdb->prefix}woocommerce_order_items WHERE order_id = %d)", $order_id ) );

		$delete_order_items = $wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->prefix}woocommerce_order_items WHERE order_id =%d", $order_id ) );

		$delete_order_comment = $wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->prefix}comments WHERE comment_type = %s AND comment_post_ID =%d", 'order_note', $order_id ) );

		$delete_order_meta = $wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->prefix}postmeta WHERE post_id =%d", $order_id ) );

		$delete_order = $wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->prefix}posts WHERE post_type = %s AND ID = %d", 'shop_order', $order_id ) );
	}
	/*
	*   Add checkout FCCPA content
	*/
	public function ccpa_woo_add_checkout_privacy_policy() {
		$policyPage    = ccpa( 'options' )->get( 'policy_page' );
		$policyPageUrl = get_permalink( $policyPage );
		if ( isset( $policyPageUrl ) && $policyPage != '0' ) {
			woocommerce_form_field(
				'ccpa_woo_consent',
				array(
					'type'        => 'checkbox',
					'class'       => array( 'form-row privacy' ),
					'label_class' => array( 'woocommerce-form__label woocommerce-form__label-for-checkbox checkbox' ),
					'input_class' => array( 'woocommerce-form__input woocommerce-form__input-checkbox input-checkbox' ),
					'required'    => true,
					'label'       => sprintf(
						__( 'I accept the %1$sPrivacy Policy%2$s', 'ccpa-framework' ),
						"<a href='{$policyPageUrl}' target='_blank'>",
						'</a>'
					),
				)
			);
		}
	}
	/*
	*   Track consent and check for Privacy Policy consent.
	*/
	public function ccpa_woo_not_approved_privacy() {
		$policyPage    = ccpa( 'options' )->get( 'policy_page' );
		$policyPageUrl = get_permalink( $policyPage );
		if ( ! (int) isset( $_POST['ccpa_woo_consent'] ) ) {
			if ( isset( $policyPageUrl ) && $policyPage != '0' ) {
				wc_add_notice( __( 'Please acknowledge the Privacy Policy', 'ccpa-framework' ), 'error' );
			}
		} else {
			if ( isset( $_POST['billing_email'] ) || isset( $_POST['ccpa_woo_consent'] ) ) {
				$billing_email = sanitize_email( $_POST['billing_email'] );
				$dataSubject   = $this->dataSubjectManager->getByEmail( $billing_email );
				$dataSubject->giveConsent( 'ccpa_woo_consent' );
			}
		}
	}

	public function ccpaf_validate_privacy_registration( $errors, $username, $email ) {
		if ( ! is_checkout() ) {
			if ( ! (int) isset( $_POST['ccpa_woo_consent'] ) ) {
				$errors->add( 'ccpa_woo_consent_error', __( 'Privacy Policy consent is required!', 'woocommerce' ) );
			} else {
				if ( isset( $email ) && isset( $_POST['ccpa_woo_consent'] ) ) {
					$dataSubject = $this->dataSubjectManager->getByEmail( $email );
					$dataSubject->giveConsent( 'ccpa_woo_consent' );
				}
			}
		}
		return $errors;
	}

}
