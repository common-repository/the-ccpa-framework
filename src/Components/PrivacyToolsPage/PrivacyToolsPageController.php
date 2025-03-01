<?php

namespace Data443\CCPA\Components\PrivacyToolsPage;

use Data443\CCPA\DataSubject\DataSubject;
use Data443\CCPA\DataSubject\DataSubjectAuthenticator;
use Data443\CCPA\DataSubject\DataSubjectIdentificator;
use Data443\CCPA\DataSubject\DataSubjectManager;
use Data443\CCPA\DataSubject\DataExporter;
use Data443\CCPA\Components\Consent\UserConsentModel;

/**
 * Handle the data page on front-end
 *
 * Class DataPageController
 *
 * @package Data443\CCPA\Components\DataPage
 */
class PrivacyToolsPageController {

	/* @var DataSubjectAuthenticator */
	protected $dataSubjectAuthenticator;

	/* @var DataSubjectIdentificator */
	protected $dataSubjectIdentificator;

	/* @var DataSubjectManager */
	protected $dataSubjectManager;

	protected $UserConsentModel;

	/**
	 * DataPageController constructor.
	 *
	 * @param DataSubjectIdentificator $dataSubjectIdentificator
	 * @param DataSubjectManager       $dataSubjectManager
	 */
	public function __construct(
		DataSubjectAuthenticator $dataSubjectAuthenticator,
		DataSubjectIdentificator $dataSubjectIdentificator,
		DataSubjectManager $dataSubjectManager,
		DataExporter $dataExporter,
		UserConsentModel $UserConsentModel
	) {
		$this->dataSubjectAuthenticator = $dataSubjectAuthenticator;
		$this->dataSubjectIdentificator = $dataSubjectIdentificator;
		$this->dataSubjectManager       = $dataSubjectManager;
		$this->dataExporter             = $dataExporter;

		$this->UserConsentModel = $UserConsentModel;

		if ( ! ccpa( 'options' )->get( 'enable' ) ) {
			return;
		}

		$this->setup();
	}

	protected function setup() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_donotsell' ) );

		// Listen to 'identify' action and send an email
		add_action( 'ccpa/frontend/action/identify', array( $this, 'sendIdentificationEmail' ) );

		add_action( 'ccpa/frontend/privacy-tools-page/content', array( $this, 'renderConsentForm' ), 10, 2 );
		add_action( 'ccpa/frontend/privacy-tools-page/content', array( $this, 'renderExportForm' ), 20, 2 );

		add_action( 'ccpa/frontend/privacy-tools-page/content', array( $this, 'renderDeleteForm' ), 30, 2 );

		add_action( 'ccpa/frontend/privacy-tools-page/action/withdraw_consent', array( $this, 'withdrawConsent' ), 10, 2 );
		add_action( 'ccpa/frontend/privacy-tools-page/action/export', array( $this, 'export' ), 10, 2 );
		add_action( 'ccpa/frontend/privacy-tools-page/action/forget', array( $this, 'forget' ), 10, 2 );
		add_action( 'wp_ajax_donot_sell_save_post', array( $this, 'donot_sell_save_post' ) );
		add_action( 'wp_ajax_nopriv_donot_sell_save_post', array( $this, 'donot_sell_save_post' ) );
		add_action( 'wp_ajax_nopriv_validation_privacysafe', array( $this, 'validation_privacysafe' ) );
	}

	public function enqueue_donotsell() {
		wp_enqueue_script(
			'donot-sell-form',
			ccpa( 'config' )->get( 'plugin.url' ) . 'assets/js/ccpa-donotsell.js',
			array( 'jquery' ),
			CCPA_FRAMEWORK_VERSION,
			true
		);
		wp_localize_script(
			'donot-sell-form',
			'localized_donot_sell_form',
			array(
				'admin_donot_sell_ajax_url' => admin_url( 'admin-ajax.php' ),
			)
		);
	}

	public function enqueue() {
		if ( ! ccpa( 'options' )->get( 'enable_stylesheet' ) || ! is_page( ccpa( 'options' )->get( 'tools_page' ) ) ) {
			return;
		}

		wp_enqueue_style( 'ccpa-framework-privacy-tools', ccpa( 'config' )->get( 'plugin.url' ) . 'assets/css/privacy-tools.css', array(), true );
	}

	public function validation_privacysafe() {
		return true;
		exit;
	}

	/**
	 * If the given email address exists as a data subject, send an authentication email to that address
	 */
    public function sendIdentificationEmail() {

        // Additional safety check
        if ( ! is_email( $_REQUEST['email'] ) ) {
            $this->redirect( array( 'ccpa_notice' => 'invalid_email' ) );
        } else {
            $requested_email = sanitize_email( $_REQUEST['email'] );
        }

        if ( $this->dataSubjectIdentificator->isDataSubject( $requested_email ) ) {
            $this->dataSubjectIdentificator->sendIdentificationEmail( $requested_email );
        } else {
            $user = get_user_by( 'email', $requested_email );
            if ( empty($user) ) {
                $this->redirect( array( 'ccpa_notice' => 'unregistered_user' ) );
            } else {
                $this->dataSubjectIdentificator->sendNoDataFoundEmail( $requested_email );
            }
        }

        $this->redirect( array( 'ccpa_notice' => 'email_sent' ) );
    }

	/**
	 * Render the page contents.
	 * This is only called via the shortcode.
	 */
	public function render() {
		$dataSubject = $this->dataSubjectAuthenticator->authenticate();
		$this->renderNotices();

		if ( $dataSubject ) {
			$this->renderPrivacyTools( $dataSubject );
		} else {
			$this->renderIdentificationForm();
		}
	}

	/**
	 * Display notices to the user.
	 * The contents of the notices are currently hardcoded inside the template.
	 */
	protected function renderNotices() {
		if ( ! isset( $_REQUEST['ccpa_notice'] ) ) {
			return;
		}

		echo ccpa( 'view' )->render( 'privacy-tools/notices' );
	}

	/**
	 * Render the contents of the identification form
	 */
	protected function renderIdentificationForm() {
		$nonce = wp_create_nonce( 'ccpa/frontend/action/identify' );
		// FRAM-148 Fix reference of an undefined variable 'notices'
		if (!isset($notices)) {
			$notices = "NOTICES PLACEHOLDER";
		}		
		echo ccpa( 'view' )->render( 'privacy-tools/form-identify', compact( 'nonce', 'notices' ) );
	}

	/**
	 * Render the contents of the Privacy Tools page
	 *
	 * @param DataSubject $dataSubject
	 */
	protected function renderPrivacyTools( DataSubject $dataSubject ) {
		$email = $dataSubject->getEmail();
		echo ccpa( 'view' )->render( 'privacy-tools/privacy-tools', compact( 'dataSubject', 'email' ) );
	}

	/**
	 * Render the form that allows withdrawing consent
	 *
	 * @param DataSubject $dataSubject
	 */
	public function renderConsentForm( DataSubject $dataSubject ) {
		$consentData = $dataSubject->getVisibleConsentData();
		if ( $consentData ) {
			foreach ( $consentData as &$item ) {
				$item['withdraw_url'] = add_query_arg(
					array(
						'ccpa_action' => 'withdraw_consent',
						'ccpa_nonce'  => wp_create_nonce( 'ccpa/frontend/privacy-tools-page/action/withdraw_consent' ),
						'email'       => $dataSubject->getEmail(),
						'consent'     => $item['slug'],
					)
				);
			}
		}

		$consentInfo = wpautop( ccpa( 'options' )->get( 'consent_info' ) );

		echo ccpa( 'view' )->render(
			'privacy-tools/form-consent',
			compact( 'consentData', 'consentInfo' )
		);
	}

	/**
	 * Render the form that allows the data subject to export their data
	 *
	 * @param DataSubject $dataSubject
	 */
	public function renderExportForm( DataSubject $dataSubject ) {
		$email = $dataSubject->getEmail();
		$nonce = wp_create_nonce( 'ccpa/frontend/privacy-tools-page/action/export' );

		echo ccpa( 'view' )->render(
			'privacy-tools/form-export',
			compact( 'email', 'nonce' )
		);
	}

	/**
	 * Render the form that allows the data subject to delete their data
	 *
	 * @param DataSubject $dataSubject
	 */
	public function renderDeleteForm( DataSubject $dataSubject ) {
		// Let's not allow admins to delete themselves
		if ( current_user_can( 'manage_options' ) ) {
			echo ccpa( 'view' )->render( 'privacy-tools/notice-admin-role' );
			return;
		}
		$email     = $dataSubject->getEmail();
		$ccpa_user = get_user_by( 'email', $email );
		if ( isset( $ccpa_user->data->ID ) ) {
			if ( user_can( $ccpa_user->data->ID, 'manage_options' ) ) {
				echo ccpa( 'view' )->render( 'privacy-tools/notice-admin-role' );
				return;
			}
		}
		$action = 'forget';
		$nonce  = wp_create_nonce( 'ccpa/frontend/privacy-tools-page/action/forget' );
		$user   = wp_get_current_user();
		echo ccpa( 'view' )->render(
			'privacy-tools/form-delete',
			compact( 'action', 'email', 'nonce' )
		);
	}

	/**
	 * Withdraw the consent
	 *
	 * @param DataSubject $dataSubject
	 */
	public function withdrawConsent( DataSubject $dataSubject ) {
		$consent = sanitize_key( $_REQUEST['consent'] );
		$dataSubject->withdrawConsent( $consent );
		$this->redirect( array( 'ccpa_notice' => 'consent_withdrawn' ) );
	}

	/**
	 * Trigger the export action.
	 *
	 * @param DataSubject $dataSubject
	 */
	public function export( DataSubject $dataSubject ) {
		$format = sanitize_key( $_REQUEST['ccpa_format'] );
		$data   = $dataSubject->export( $format );

		if ( ! is_null( $data ) ) {
			// If there is data, download it
			$this->dataExporter->export( $data, $dataSubject, $format );
		} else {
			// If there's no data, then show notification that your request has been sent.
			$this->redirect( array( 'ccpa_notice' => 'request_sent' ) );
		}
	}

	/**
	 * Trigger the forget action.
	 *
	 * @param DataSubject $dataSubject
	 */
	public function forget( DataSubject $dataSubject ) {
		$deleted = $dataSubject->forget();

		if ( $deleted ) {
			$this->dataSubjectAuthenticator->deleteSession();
			$this->redirect( array( 'ccpa_notice' => 'data_deleted' ) );
		} else {
			// If request was sent to admin, then show notification
			$this->redirect( array( 'ccpa_notice' => 'request_sent' ) );
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
			// If custom tools page URL is set
			if ( ccpa( 'options' )->get( 'custom_tools_page' ) ) {
				$privacyToolsUrl = ccpa( 'options' )->get( 'custom_tools_page' );
				$baseUrl         = apply_filters( 'redirect_after_ccpa_submit', $privacyToolsUrl );
			} else {
				$privacyToolsUrl = ccpa( 'options' )->get( 'tools_page' );
				$baseUrl         = $privacyToolsUrl ? get_permalink( $privacyToolsUrl ) : home_url();
				$baseUrl         = apply_filters( 'redirect_after_ccpa_submit', $baseUrl );
			}
			// Avoid infinite loop redirect
		}

		wp_safe_redirect( add_query_arg( $args, $baseUrl ) );
		exit;
	}

	public function ccpa_get_formatted_billing_name_and_address( $user_id ) {
		$address  = get_user_meta( $user_id, 'billing_address_1', true ) . ' ';
		$address .= get_user_meta( $user_id, 'billing_address_2', true ) . ' ';
		$address .= get_user_meta( $user_id, 'billing_city', true ) . ' ';
		$address .= get_user_meta( $user_id, 'billing_state', true ) . ' ';
		$address .= get_user_meta( $user_id, 'billing_postcode', true ) . ' ';
		$address .= get_user_meta( $user_id, 'billing_country', true ) . ' ';
		return $address;
	}
	public function donot_sell_save_post() {
		if ( ! empty( $_POST['form_data'] ) ) {
			$authorname = '';
			parse_str( $_POST['form_data'], $form_data );
			if ( is_user_logged_in() ) {
				// your code for logged in user
				$current_user = wp_get_current_user();
				$authorname   = esc_html( $current_user->user_login );
			}
			$postarr  = array(
				'ID'          => '', // If ID stays empty the post will be created.
				'post_author' => $authorname,
				'post_title'  => sanitize_email( $form_data['donotsell_email'] ),
				'post_status' => 'publish',
				'post_type'   => 'donotsellrequests',
			);
			$new_post = wp_insert_post(
				$postarr,
				true
			);
			$post_id  = intval( $new_post );
			if ( $post_id ) {
				add_post_meta( $post_id, 'donotsell_first_name', sanitize_text_field( $form_data['donotsell_first_name'] ) );
				add_post_meta( $post_id, 'donotsell_last_name', sanitize_text_field( $form_data['donotsell_last_name'] ) );
				add_post_meta( $post_id, 'donotsell_consent', sanitize_text_field( $form_data['donotsell_consent'] ) );
			}
			if ( ! empty( $form_data['donotsell_consent'] ) && $form_data['donotsell_consent'] ) {
				$email   = sanitize_email( $form_data['donotsell_email'] );
				$consent = 'do-not-sell-info';
				$output  = $this->UserConsentModel->give( $email, $consent, $valid_until = null );
				$this->UserConsentModel->give( $email, 'receive-communications', $valid_until = null );
			}
			// Post was not created/updated, so let's output the error message.
			if ( is_wp_error( $new_post ) ) {
				$r['error'] = $new_post->get_error_message();

				echo json_encode( $r );

				exit;
			}

			// Gets post info in array format as it's easier to debug via console if needed.
			$post_array = get_post( $post_id, ARRAY_A );

			if ( $post_array ) {
				$r['donotsellrequests'] = $post_array;
			}

			echo json_encode( $r );
		}
		exit;
	}
}
