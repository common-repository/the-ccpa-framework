<?php
/**
 * Plugin Name:       The CCPA Framework
 * Plugin URI:        https://www.data443.com/
 * Description:       Tools to help make your website CCPA-compliant. Fully documented, extendable and developer-friendly.
 * Requires at least: 4.7
 * Requires PHP:      5.6
 * Version:           2.0.2
 * Author:            Data443
 * Author URI:        https://www.data443.com/
 * Text Domain:       the-ccpa-framework
 * Domain Path:       /languages
 *
 * @package WordPress
 */ 

if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'CCPA_FRAMEWORK_VERSION', '2.0.2' );

define('CCPA_DEFAULT_UNKNOWN_USER_MESSAGE', 'Message received.');

add_shortcode( 'ccpa_privacy_safe', 'render_ccpa_privacy_safe' ); // preserve backward compatibility
add_shortcode( 'data443_privacy_safe', 'render_ccpa_privacy_safe' );

/** 
 * Render WHMCS Seal Generator Addon Javascript
 */
function render_ccpa_privacy_safe() {
	wp_register_script( 'ccpa_whmcs_seal_generator', ccpa( 'config' )->get( 'plugin.url' ) . '/assets/js/showseal.js', null, true, true );
	wp_localize_script(
		'ccpa_whmcs_seal_generator', 
		'ccpa_seal_var',
		array(
			'ccpa_imageparams'   => esc_attr( get_option( 'ccpa_privacy_safe_params' ) ),
			'ccpa_imagecode'     => esc_attr( get_option( 'ccpa_privacy_safe_imagecode' ) ),
			'ccpa_showimagefunc' => 'showimage_' . esc_attr( get_option( 'ccpa_privacy_safe_imagecode' ) ),
		)
	);
	wp_enqueue_script( 'ccpa_whmcs_seal_generator', basename( dirname( __FILE__ ) ) . '/assets/js/showseal.js', null, true, true );

	$seal_code = '<div class="data443-privacy-safe" style="font-size:12px;text-align: left;">';

	if( get_option( 'gdpr_privacy_safe_imagecode' ) !== '' && get_option( 'gdpr_privacy_safe_params' ) !== '' ){
		$seal_code .= '<a href="javascript:;" onclick="openpopup_' . esc_attr( get_option( 'gdpr_privacy_safe_imagecode' ) ) . '();">
		<img id="data443-privacy-safe-image" src="https://orders.data443.com/seal/seal.php?params=' . esc_attr( get_option( 'gdpr_privacy_safe_params' ) ) . '" alt="Data443 Privacy Safe" />
		</a>';
	}
	if( get_option( 'gdpr_privacy_safe_backlink' ) === '1' ){
		$seal_code .= '<span style="display:block;">Privacy Management Service by <a href="https://data443.com/products/global-privacy-manager/" target="_blank">Data443</a></span>';
	}
	$seal_code .= '</div>';
	// scale the size of the link text based on the loaded scaled image
	$seal_code .= "<script>jQuery('#data443-privacy-safe-image').load(function(){var px='12px';var w=jQuery(this).width();if(w>0&&w<=150){px='6px'}else if(w<300){px='10px'};jQuery('.data443-privacy-safe').css({'font-size': px});})</script>";
	return $seal_code;
}

add_action('plugins_loaded', 'ccpa_framework_load_textdomain');
function ccpa_framework_load_textdomain() 
{
	load_plugin_textdomain('ccpa-framework', false, basename( dirname( __FILE__ ) ) . '/languages'); 
}

/**
 * Our custom post type function
 */
function ccpa_create_custom_post_type() {
	$args = array(
		'label'               => 'Do Not Sell Info',
		'public'              => true,
		'has_archive'         => false,
		'exclude_from_search' => true,
		'publicly_queryable'  => false,
		'show_in_menu'        => false,
		'menu_position'       => 20,
		'show_ui'             => true,
		'hierarchical'        => false,
		'rewrite'             => array( 'slug' => 'donotsellrequests' ),
		'query_var'           => true,
		'supports'            => array( 'title', 'editor', 'excerpt', 'custom-fields', 'post-formats' ),
	);
	register_post_type( 'donotsellrequests', $args );
}

/**
 * Hooking up our function to theme setup
 */
add_action( 'init', 'ccpa_create_custom_post_type' );

/**
 * Helper function for prettying up errors
 *
 * @param string $message
 * @param string $subtitle
 * @param string $title
 */
$ccpa_error = function ( $message, $subtitle = '', $title = '' ) {
	$title   = $title ? '' : _x( 'WordPress CCPA &rsaquo; Error', '(Admin)', 'ccpa-framework' );
	$message = "<h1>{$title}<br><small>{$subtitle}</small></h1><p>{$message}</p>";
	wp_die( esc_attr( $message ), esc_attr( $title ) );
};

/**
 * Ensure compatible version of PHP is used
 */
if ( version_compare( phpversion(), '5.6.0', '<' ) ) {
	$ccpa_error(
		_x( 'You must be using PHP 5.6.0 or greater.', '(Admin)', 'ccpa-framework' ),
		_x( 'Invalid PHP version', '(Admin)', 'ccpa-framework' )
	);
}

include_once(dirname(__FILE__).'/autoload.php');

/**
 * Install the database table and custom role
 */
register_activation_hook(
	__FILE__,
	function () {
		$model = new \Data443\CCPA\Components\Consent\UserConsentModel();
		$model->createTable();
		$model->createUserTable();
		if ( apply_filters( 'ccpa/data-subject/anonymize/change_role', true ) && ! get_role( 'anonymous' ) ) {
			add_role(
				'anonymous',
				_x( 'Anonymous', '(Admin)', 'ccpa-framework' ),
				array()
			);
		}
		update_option( 'ccpa_enable_stylesheet', true );
		update_option( 'ccpa_enable', true );
	}
);

require_once 'ccpa-helper-functions.php';
require_once 'ccpa-init.php';
