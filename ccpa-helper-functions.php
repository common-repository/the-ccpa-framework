<?php
/**
 * Description:       Tools to help make your website CCPA-compliant. Fully documented, extendable and developer-friendly.
 * @package WordPress
 */

add_action( 'wp_ajax_ccpa_add_consent_accept_cookies', 'ccpa_add_consent_accept_cookies' );
add_action( 'wp_ajax_nopriv_ccpa_add_consent_accept_cookies', 'ccpa_add_consent_accept_cookies' );

/**
 * Ajax function on accept cookie button
 */
function ccpa_add_consent_accept_cookies()
{
    $referer = isset( $_SERVER['HTTP_REFERER'] );
    $address = isset( $_SERVER['SERVER_NAME'] );
    if ( $referer ) {
        if ( strpos( $address, $referer ) !== 0 ) {
            global $wpdb;
            $table_name    = $wpdb->prefix . 'ccpa_consent';
            $current_user = wp_get_current_user();
            $user_email   = sanitize_email( $current_user->user_email );
            if ( '' == $user_email && isset( $_COOKIE['ccpa_key'] ) ) {
                $email      = explode( '|', sanitize_text_field( wp_unslash( $_COOKIE['ccpa_key'] ) ) );
                $user_email = sanitize_email( $email['0'] );
            }

            if (!empty($user_email)) {
                $future_date = '8999-12-31 23:59:59';
                $consent = 'ccpa_cookie_consent';

                $n = count(
                            $wpdb->get_results(
                                $wpdb->prepare(
                                    "SELECT * FROM {$table_name} WHERE email = %s AND consent = %s;",
                                    $user_email,
                                    $consent
                                )
                            )
                        );

                if ($n > 0) {
                    $wpdb->update(
                        $table_name,
                        [
                            'status'      => 1,
                            'updated_at'  => current_time( 'mysql' ),
                            'ip'          => $_SERVER['REMOTE_ADDR'],
                            'valid_until' => $future_date,
                        ],
                        [
                            'email'   => $user_email,
                            'consent' => $consent,
                        ]
                    );
                } else {
                    $wpdb->insert(
                        $table_name,
                        array(
                            'email'      => $user_email,
                            'version'    => 1,
                            'consent'    => $consent,
                            'status'     => 1,
                            'updated_at' => current_time( 'mysql' ),
                            'ip'         => $_SERVER['REMOTE_ADDR'],
                            'valid_until' => $future_date,
                        )
                    );
                }
                do_action( 'ccpa_consent_accept_cookies' );
            }
            wp_die();
        } else {
            echo 'Error !!!';
            wp_die();
        }
    } else {
        echo 'ERROR !!';
        wp_die();
    }
}
add_action( 'wp_ajax_ccpa_add_consent_deny_cookies', 'ccpa_add_consent_deny_cookies' );
add_action( 'wp_ajax_nopriv_ccpa_add_consent_deny_cookies', 'ccpa_add_consent_deny_cookies' );

/**
 * ajax function on deny cookie button
 */
function ccpa_add_consent_deny_cookies()
{
    $referer = $_SERVER['HTTP_REFERER'];
    $address = $_SERVER['SERVER_NAME'];
    if ( $referer ) {
        if ( strpos( $address, $referer ) !== 0 ) {
            global $wpdb;
            $table_name    = $wpdb->prefix . 'ccpa_consent';
            $current_user = wp_get_current_user();
            $user_email   = sanitize_email( $current_user->user_email );
            if ( '' == $user_email && isset( $_COOKIE['ccpa_key'] ) ) {
                $email      = explode( '|', sanitize_text_field( wp_unslash( $_COOKIE['ccpa_key'] ) ) );
                $user_email = sanitize_email( $email['0'] );
            }

            if (!empty($user_email)) {
                $future_date = '7999-12-31 23:59:59';
                $consent = 'ccpa_cookie_consent';
    
                $n = count(
                            $wpdb->get_results(
                                $wpdb->prepare(
                                    "SELECT * FROM {$table_name} WHERE email = %s AND consent = %s;",
                                    $user_email,
                                    $consent
                                )
                            )
                        );
    
                if ($n > 0) {
                    $wpdb->update(
                        $table_name,
                        [
                            'version'     => 1,
                            'status'      => 0,
                            'updated_at'  => current_time( 'mysql' ),
                            'ip'          => $_SERVER['REMOTE_ADDR'],
                            'valid_until' => $future_date,
                        ],
                        [
                            'email'   => $user_email,
                            'consent' => $consent,
                        ]
                    );
                } else {
                    $wpdb->insert(
                        $table_name,
                        array(
                            'email'      => $user_email,
                            'version'    => 1,
                            'consent'    => $consent,
                            'status'     => 0,
                            'updated_at' => current_time( 'mysql' ),
                            'ip'         => $_SERVER['REMOTE_ADDR'],
                            'valid_until' => $future_date,
                        )
                    );
                }
                do_action( 'ccpa_consent_deny_cookies' );
            }
            wp_die();
        } else {
            echo 'Error !!!';
            wp_die();
        }
    } else {
        echo 'ERROR !!';
        wp_die();
    }
}

/**
 * popup cookieconsent scipts
 */
function popup_ccpa() {

	$nonce = wp_create_nonce( 'popup_ccpa' );
	wp_register_script( 'ccpa-framework-cookieconsent-min-js', ccpa( 'config' )->get( 'plugin.url' ) . 'assets/js/cookieconsent.min.js', array(), true, true );

	wp_localize_script( 
		'ccpa-framework-cookieconsent-min-js',
		'popup_ccpa',
		array(
			'nonce' => $nonce,
		)
	);
	wp_enqueue_script( 'ccpa-framework-cookieconsent-min-js', ccpa( 'config' )->get( 'plugin.url' ) . 'assets/js/cookieconsent.min.js' );

	wp_enqueue_style( 'ccpa-framework-cookieconsent-css', ccpa( 'config' )->get( 'plugin.url' ) . 'assets/css/cookieconsent.min.css' );
	

	$ccpa_policy_page_id = get_option( 'ccpa_policy_page' );
	if ( $ccpa_policy_page_id ) {
		$ccpa_policy_page_url = get_permalink( $ccpa_policy_page_id );
		/*
		* FIX FOR MULTILANG.
		*/
		if ( $ccpa_policy_page_url == '' ) {
			if ( isset( $ccpa_policy_page_id[ substr( get_bloginfo( 'language' ), 0, 2 ) ] ) ) {
				$ccpa_policy_page_url = get_permalink( $ccpa_policy_page_id[ substr( get_bloginfo( 'language' ), 0, 2 ) ] );
			}
		}
	} else {
		$ccpa_policy_page_url = '';
	}
	add_filter( 'ccpa_custom_policy_link', 'ccpafPrivacyPolicyurl' );

	$ccpa_policy_page_url = apply_filters( 'ccpa_custom_policy_link', $ccpa_policy_page_url );

	$ccpa_cookie_acceptance_content_url = get_option( 'ccpa_popup_content' );

	$ccpa_cookie_acceptance_content_url = do_shortcode( $ccpa_cookie_acceptance_content_url );

	if ( $ccpa_cookie_acceptance_content_url != '' ) {
		$ccpa_message = __( $ccpa_cookie_acceptance_content_url, 'ccpa-framework' );
	} else {
		$ccpa_message = __( 'This website uses cookies to ensure you get the best experience on our website.', 'ccpa-framework' );
	}

	$ccpa_cookie_dismiss_text_url = get_option( 'ccpa_popup_dismiss_text' );

	$ccpa_cookie_dismiss_text_url = do_shortcode( $ccpa_cookie_dismiss_text_url );

	if ( $ccpa_cookie_dismiss_text_url != '' ) {
		$ccpa_dismiss = __( $ccpa_cookie_dismiss_text_url, 'ccpa-framework' );
	} else {
		$ccpa_dismiss = __( 'Decline', 'ccpa-framework' );
	}

	$ccpa_cookie_allow_text_url = get_option( 'ccpa_popup_allow_text' );

	$ccpa_cookie_allow_text_url = do_shortcode( $ccpa_cookie_allow_text_url );

	if ( $ccpa_cookie_dismiss_text_url != '' ) {
		$ccpa_allow = __( $ccpa_cookie_allow_text_url, 'ccpa-framework' );
	} else {
		$ccpa_allow = __( 'Accept', 'ccpa-framework' );
	}

	$ccpa_cookie_learnmore_text_url = get_option( 'ccpa_popup_learnmore_text' );

	$ccpa_cookie_learnmore_text_url = do_shortcode( $ccpa_cookie_learnmore_text_url );

	if ( $ccpa_cookie_learnmore_text_url != '' ) {
		$ccpa_link = __( $ccpa_cookie_learnmore_text_url, 'ccpa-framework' );
	} else {
		$ccpa_link = __( 'Learn more', 'ccpa-framework' );
	}

	$position = get_option( 'ccpa_popup_position' ); // "bottom-left","top","bottom-right",""

	$static = false; // true

	$ccpa_header = get_option( 'ccpa_header' );

	$ccpa_header = do_shortcode( $ccpa_header );

	if ( $ccpa_header != '' ) {
		$ccpa_header = __( $ccpa_header, 'ccpa-framework' );
	}

	$ccpa_popup_background = get_option( 'ccpa_popup_background' );

	$ccpa_popup_text = get_option( 'ccpa_popup_text' );

	$ccpa_button_background = get_option( 'ccpa_popup_button_background' );

	$ccpa_button_text = get_option( 'ccpa_popup_button_text' );

	$ccpa_link_target = get_option( 'ccpa_popup_link_target' );

	if ( ! $ccpa_link_target ) {
		$ccpa_link_target = '_blank';
	}

	$ccpa_button_border = get_option( 'ccpa_popup_border_text' );

	if ( ! $ccpa_popup_background ) {
		$ccpa_popup_background = '#efefef';
	}
	if ( ! $ccpa_popup_text ) {
		$ccpa_popup_text = '#404040';
	}
	if ( ! $ccpa_button_background ) {
		$ccpa_button_background = 'transparent';
	}
	if ( ! $ccpa_button_text ) {
		$ccpa_button_text = '#8ec760';
	}
	if ( ! $ccpa_button_border ) {
		$ccpa_button_border = '#8ec760';
	}

	$ccpa_popup_theme = get_option( 'ccpa_popup_theme' );

	$ccpa_policy_popup = get_option( 'ccpa_policy_popup' );

	$ccpa_hide = get_option( 'ccpa_onetime_popup' );

	$type = 'opt-out'; // opt-in,opt-out,""

	$policy_text = __( 'Cookie Policy', 'ccpa-framework' );

	$get_ccpa_data = array(
		'ccpa_url'               => $ccpa_policy_page_url,

		'ccpa_message'           => $ccpa_message,

		'ccpa_dismiss'           => $ccpa_dismiss,

		'ccpa_allow'             => $ccpa_allow,

		'ccpa_header'            => $ccpa_header,

		'ccpa_link'              => $ccpa_link,

		'ccpa_popup_position'    => $position,

		'ccpa_popup_type'        => $type,

		'ccpa_popup_static'      => $static,

		'ccpa_popup_background'  => $ccpa_popup_background,

		'ccpa_popup_text'        => $ccpa_popup_text,

		'ccpa_button_background' => $ccpa_button_background,

		'ccpa_button_text'       => $ccpa_button_text,
		'ccpa_button_border'     => $ccpa_button_border,

		'ccpa_popup_theme'       => $ccpa_popup_theme,

		'ccpa_hide'              => $ccpa_hide,

		'ccpa_popup'             => $ccpa_policy_popup,

		'policy'                 => $policy_text,

		'ajaxurl'                => admin_url( 'admin-ajax.php' ),

		'ccpa_link_target'       => $ccpa_link_target,
	);

	wp_register_script( 'ccpa-framework-cookieconsent-js', ccpa( 'config' )->get( 'plugin.url' ) . 'assets/js/ajax-cookieconsent.js', array(), false, true );

	wp_localize_script( 'ccpa-framework-cookieconsent-js', 'ccpa_policy_page', $get_ccpa_data );

	wp_enqueue_script( 'ccpa-framework-cookieconsent-js', ccpa( 'config' )->get( 'plugin.url' ) . 'assets/js/ajax-cookieconsent.js' );

	
}
/**
 * Cookie acceptance Popup
 */
$enabled_gdpf_cookie_popup = get_option( 'ccpa_enable_popup' );
if ( $enabled_gdpf_cookie_popup ) {
	add_action( 'wp_enqueue_scripts', 'ccpa_frontend_enqueue' );
	function ccpa_frontend_enqueue() {
		wp_enqueue_script( 'jquery' );
		if ( get_option( 'ccpa_onetime_popup' ) == '1' ) {
			if ( ! isset( $_COOKIE['cookieconsent_status'] ) ) {
				popup_ccpa();
			}
		} else {
			popup_ccpa();
		}
	}
}

function CCPATermAndConditionWithPrivacyContent() {
	 return __('I accept the %sTerms and Conditions%s and the %sPrivacy Policy%s', 'ccpa-framework');
}

function ccpafPrivacyPolicy() {
	 return __('I accept the %sPrivacy Policy%s', 'ccpa-framework');
}

function ccpafPrivacyPolicyurl( $policypage ) {
	$policypageURL = get_option( 'ccpa_custom_policy_page' );
	if ( $policypageURL == '' ) {
		return esc_url_raw( $policypage );
	} else {
		return esc_url_raw( $policypageURL );
	}
}

function ccpa_privacy_accpetance( $ccpa_error_massage ) {
	return $ccpa_error_massage;
}

/**
 * Save user logs
 */
add_action( 'profile_update', 'CCPA_my_profile_update', 10, 2 );

function CCPA_my_profile_update( $user_id, $old_user_data ) {
	$data              = (array) $old_user_data->data;
	$all_meta_for_user = get_user_meta( $user_id );
	if ( $all_meta_for_user['nickname']['0'] ) {
		$data['nickname'] = sanitize_text_field( $all_meta_for_user['nickname']['0'] );
	}
	if ( $all_meta_for_user['first_name']['0'] ) {
		$data['first_name'] = sanitize_email( $all_meta_for_user['first_name']['0'] );
	}
	if ( $all_meta_for_user['last_name']['0'] ) {
		$data['last_name'] = sanitize_text_field( $all_meta_for_user['last_name']['0'] );
	}
	if ( $all_meta_for_user['description']['0'] ) {
		$data['description'] = sanitize_text_field( $all_meta_for_user['description']['0'] );
	}
	$userdata = serialize( $data );
	$model    = new \Data443\CCPA\Components\Consent\UserConsentModel();
	$model->savelog( $user_id, $userdata );
}

