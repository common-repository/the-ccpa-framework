<?php

namespace Data443\CCPA\Components\PrivacySafe;

use Data443\CCPA\Admin\AdminTab;
use Data443\CCPA\Components\WHMCS\WHMCS;

class AdminTabPrivacySafe extends AdminTab {

	/* @var string */
	protected $slug = 'privacy-safe';

	/* @var PolicyGenerator */
	protected $policyGenerator;

	public function __construct() {
		 $this->title = _x( 'Privacy Safe', '(Admin)', 'ccpa-framework' );
		$this->registerSetting( 'ccpa_privacy_safe_params' );
		$this->registerSetting( 'ccpa_privacy_safe_imagecode' );
		$this->registerSetting( 'ccpa_privacy_safe_backlink_selected' );
		$this->registerSetting( 'ccpa_privacy_safe_backlink' );

		add_action( 'ccpa/admin/action/PrivacyManager/generate', array( $this, 'generatePrivacySafe' ) );

	}

	public function init() {
		/**
		 * Privacy Safe settings
		 */
		$this->registerSettingSection(
			'ccpa_about_privacy_safe_section',
			_x( 'Privacy Safe by Data443', '(Admin)', 'ccpa-framework' ),
			array( $this, 'renderAboutHeader' )
		);
		$this->registerSettingSection(
			'ccpa_link_privacy_safe_section',
			_x( 'Register for Privacy Safe', '(Admin)', 'ccpa-framework' ),
			array( $this, 'renderLinkHeader' )
		);

		$this->registerSettingSection(
			'ccpa_privacy_safe_section',
			_x( 'Privacy Safe Settings', '(Admin)', 'ccpa-framework' ),
			array( $this, 'renderGuideHeader' )
		);

		$this->registerSettingField(
			'ccpa_privacy_safe_params',
			_x( 'Seal Code', '(Admin)', 'ccpa-framework' ),
			array( $this, 'params' ),
			'ccpa_privacy_safe_section'
		);
		$this->registerSettingField(
			'ccpa_privacy_safe_imagecode',
			_x( 'Image Code', '(Admin)', 'ccpa-framework' ),
			array( $this, 'imagecode' ),
			'ccpa_privacy_safe_section'
		);
		$this->registerSettingField(
			'ccpa_privacy_safe_shortcode',
			_x( 'Shortcode', '(Admin)', 'ccpa-framework' ), 
			array( $this, 'shortcode' ),
			'ccpa_privacy_safe_section'
		);
		$this->registerSettingField(
			'ccpa_privacy_safe_shortcodephp',
			_x( 'Shortcode for PHP', '(Admin)', 'ccpa-framework' ),
			array( $this, 'shortcodephp' ),
			'ccpa_privacy_safe_section'
		);
		$this->registerSettingField(
			'ccpa_privacy_safe_backlink',
			_x( 'Support Data443', '(Admin)', 'ccpa-framework' ),
			array( $this, 'backlinkphp' ),
			'ccpa_privacy_safe_section'
		);

	}

	public function renderAboutHeader() {
		echo '<img src="' . plugins_url() . '/the-ccpa-framework/images/Privacy-Safe-Brand.png" style="float:right;margin:15px;"/><p>Strengthen your reputation. The privacy safe seal assures your customers that your business is in compliance with privacy laws and regulations. The privacy safe seal will verify that CCPA Framework plugin is installed.</p>';
	}
	public function renderLinkHeader() {
		echo '<p>Register now to activate your Privacy Safe seal. Visit the link below, complete the complete the checkout process. Once approved you will recieve notice to get your seal code and image code. Enter those here and save. You can then place the seal where you would like on your site.</p><p><a href="https://orders.data443.com/cart.php?a=add&pid=31&carttpl=standard_cart" target="_blank" class="button button-primary">Register Here</a></p>';
	}
	public function renderGuideHeader() {
		echo '<p>Embed the shortcode provided to display your privacy safe seal.</p>';
	}
	public function params() {
		echo "<input type='text' name='ccpa_privacy_safe_params' placeholder='' value='" . get_option( 'ccpa_privacy_safe_params' ) . "'>";
	}
	public function imagecode() {
		echo "<input type='text' name='ccpa_privacy_safe_imagecode' placeholder='' value='" . get_option( 'ccpa_privacy_safe_imagecode' ) . "'>";
	}
	public function shortcode() {
		echo "<code>[data443_privacy_safe]</code>";
	}
	public function shortcodephp() {
		echo "<code>echo do_shortcode('[data443_privacy_safe]');</code>";
	}
	public function backlinkphp() {

		if ( get_option( 'ccpa_privacy_safe_backlink_selected' ) === '1' ) {
			$checked = get_option( 'ccpa_privacy_safe_backlink' );
		}else{
			$checked = 1;
		}		
		echo ccpa( 'view' )->render( 'admin/privacy-safe/enable-backlink' , compact( 'checked' ) );
	}


	public function renderSubmitButton() {
		submit_button( _x( 'Save', '(Admin)', 'ccpa-framework' ) );
	}
}
