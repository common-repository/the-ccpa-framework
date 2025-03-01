<?php

namespace Data443\CCPA\Components\WordpressComments;

use Data443\CCPA\DataSubject\DataSubject;
use Data443\CCPA\DataSubject\DataSubjectManager;

class WordpressComments {

	/* @var DataSubjectManager */
	protected $dataSubjectManager;

	public function __construct( DataSubjectManager $dataSubjectManager ) {
		$this->dataSubjectManager = $dataSubjectManager;

		$this->setup();
	}

	public function setup() {
		if ( ccpa( 'options' )->get( 'policy_page' ) || ccpa( 'options' )->get( 'custom_policy_page' ) ) {
			$ccpa_check = ! ccpa( 'options' )->get( 'custom_policy_page' ) ? ccpa( 'options' )->get( 'policy_page' ) : ccpa( 'options' )->get( 'custom_policy_page' );
		} else {
			$ccpa_check = get_option( 'ccpa_policy_page' );
		}
		if ( ! ccpa( 'options' )->get( 'comment_checkbox' ) ) {
			if ( $ccpa_check ) {
				add_action( 'comment_form_after_fields', array( $this, 'maybeAddCommentFormCheckbox' ) );
				add_action( 'comment_form_logged_in_after', array( $this, 'maybeAddCommentFormCheckbox' ) );
				add_filter( 'preprocess_comment', array( $this, 'validate' ) );
			}
		}

		add_filter( 'ccpa/data-subject/data', array( $this, 'getExportData' ), 1, 2 );
		add_action( 'ccpa/data-subject/delete', array( $this, 'deleteComments' ) );
		add_action( 'ccpa/data-subject/anonymize', array( $this, 'deleteComments' ) );

	}

	/**
	 * Check if consent is needed
	 *
	 * @return bool
	 */
	public function needsConsent( $email = null ) {
		if ( $email ) {
			$dataSubject = $this->dataSubjectManager->getByEmail( $email );
		} else {
			$dataSubject = $this->dataSubjectManager->getByLoggedInUser();
		}

		return ! ( $dataSubject && $dataSubject->hasConsented( 'privacy-policy' ) );
	}

	/**
	 * If consent is needed, render the checkbox
	 *
	 * @param $fields
	 */
	public function maybeAddCommentFormCheckbox() {
		$email = isset( $_POST['email'] ) ? sanitize_email( $_POST['email'] ) : null;

		if ( ! $this->needsConsent( $email ) ) {
			return;
		}

		$privacyPolicyUrl = get_permalink( ccpa( 'options' )->get( 'policy_page' ) );
		add_filter( 'ccpa_custom_policy_link', 'ccpafPrivacyPolicyurl' );
		$privacyPolicyUrl = apply_filters( 'ccpa_custom_policy_link',$privacyPolicyUrl);

		if(ccpa('options')->get('custom_terms_page')){
			$termsPage = ccpa('options')->get('custom_terms_page');
			if ($termsPage) {
				$termsUrl = $termsPage;
			} else {
				$termsUrl = false;
			}
		}else{
			$termsPage = ccpa('options')->get('terms_page');
			if ($termsPage) {
				$termsUrl = get_permalink($termsPage);
			} else {
				$termsUrl = false;
			}
		}

		echo ccpa( 'view' )->render(
			'modules/wordpress-comments/terms-checkbox',
			compact( 'termsUrl', 'privacyPolicyUrl' )
		);
	}

	/**
	 * If consent is needed, validate it
	 */
	public function validate( $commentData ) {
		if ( is_user_logged_in() && is_admin() ) {
			$allowedRoles = apply_filters( 'ccpa/roles/comments', array( 'administrator', 'editor', 'shop_manager' ) );

			foreach ( wp_get_current_user()->roles as $userRole ) {
				if ( in_array( $userRole, $allowedRoles ) ) {
					return $commentData;
				}
			}
		}

		$email = isset( $_POST['email'] ) ? sanitize_email( $_POST['email'] ) : null;

		if ( is_user_logged_in() ) {
			if ( ! $this->needsConsent( $email ) ) {
				return $commentData;
			}
		}

		include_once ABSPATH . 'wp-admin/includes/plugin.php';
		if ( ! is_plugin_active( 'jetpack/jetpack.php' ) && ! is_plugin_active( 'wpdiscuz/class.WpdiscuzCore.php' ) ) {
			if ( ! isset( $_POST['ccpa_terms'] ) || ! $_POST['ccpa_terms'] ) {

				$ccpa_error_message = '%sERROR:%s You need to accept the privacy policy to post a comment.';
				add_filter( 'ccpa_custom_policy_error', 'ccpa_privacy_accpetance' );
				$privacyPolicyerror = apply_filters( 'ccpa_custom_policy_error', $ccpa_error_message );
				wp_die(
					sprintf(
						__( $privacyPolicyerror, 'ccpa-framework' ),
						'<strong>',
						'</strong>'
					)
				);
			} else {
				if ( is_user_logged_in() ) {
					$dataSubject = $this->dataSubjectManager->getByLoggedInUser();
				} else {
					$dataSubject = $this->dataSubjectManager->getByEmail( $email );
				}
				if( isset( $_POST['ccpa-consent-until'] ) ){
					$valid_until = esc_attr( wp_unslash( $_POST['ccpa-consent-until'] ) );
					$dataSubject->giveConsent( 'privacy-policy', $valid_until );
				}else{
					$dataSubject->giveConsent( 'privacy-policy');
				}
			}
		}

		return $commentData;
	}

	/**
	 * Add comments as well as comment meta to export data
	 *
	 * @param $data
	 * @param $email
	 * @param $dataSubject
	 * @return mixed
	 */
	public function getExportData( $data, $email ) {
		$comments = $this->getCommentsByEmail( $email );

		if ( count( $comments ) ) {

			foreach ( $comments as $comment ) {
				/* @var $comment \WP_Comment */

				$commentData = array(
					'comment_author'       => $comment->comment_author,
					'comment_author_email' => $comment->comment_author_email,
					'comment_author_url'   => $comment->comment_author_url,
					'comment_author_IP'    => $comment->comment_author_IP,
					'comment_date'         => $comment->comment_date,
					'comment_date_gmt'     => $comment->comment_date_gmt,
					'comment_content'      => $comment->comment_content,
					'comment_approved'     => $comment->comment_approved,
					'comment_agent'        => $comment->comment_agent,
					'comment_consent'      => 'privacy-policy',
				);
				if ( isset( $comment->comment_ID ) ) {
					$commentMeta = get_comment_meta( $comment->comment_ID );
					if ( ! empty( $commentMeta ) ) {
						$commentData['comment_meta'] = $commentMeta;
					}

					$data['comments'][] = $commentData;
				}
			}
		}

		return $data;
	}

	public function deleteComments( $email ) {
		$comments = $this->getCommentsByEmail( $email );

		if ( ! count( $comments ) ) {
			return;
		}

		foreach ( $comments as $comment ) {
			/* @var $comment \WP_Comment */
			wp_delete_comment( $comment->comment_ID, true );
		}
	}

	/**
	 * Todo: this currently doesn't include spam or trashed comments
	 *
	 * @param $email
	 * @return array|int
	 */
	public function getCommentsByEmail( $email ) {
		if ( ! $email || ! is_email( $email ) ) {
			return array();
		}

		$query = new \WP_Comment_Query();

		$comments = $query->query(
			array(
				'author_email'       => $email,
				'include_unapproved' => true,
				'status'             => 'all',
			)
		);

		return $comments;
	}
}
