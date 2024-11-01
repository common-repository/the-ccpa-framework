<?php

namespace Data443\CCPA\Components\CookiePopup;

use Data443\CCPA\Admin\AdminTab;

class AdminTabCookiePopup extends AdminTab {

	/* @var string */
	protected $slug = 'cookie-popup';

	/* @var PolicyGenerator */
	protected $policyGenerator;

	public function __construct() {
		$this->title = _x( 'Cookie Popup', '(Admin)', 'ccpa-framework' );
		$this->registerSetting( 'ccpa_enable_popup' );
		$this->registerSetting( 'ccpa_onetime_popup' );
		$this->registerSetting( 'ccpa_policy_popup' );
		$this->registerSetting( 'ccpa_popup_content' );
		$this->registerSetting( 'ccpa_header' );
		$this->registerSetting( 'ccpa_popup_position' );
		$this->registerSetting( 'ccpa_popup_theme' );
		$this->registerSetting( 'ccpa_popup_allow_text' );
		$this->registerSetting( 'ccpa_popup_dismiss_text' );
		$this->registerSetting( 'ccpa_popup_learnmore_text' );
		$this->registerSetting( 'ccpa_popup_background' );
		$this->registerSetting( 'ccpa_popup_text' );
		$this->registerSetting( 'ccpa_popup_link_target' );
		$this->registerSetting( 'ccpa_popup_button_background' );
		$this->registerSetting( 'ccpa_popup_button_text' );
		$this->registerSetting( 'ccpa_popup_border_text' );
		add_action( 'ccpa/admin/action/CookiePopup/generate', array( $this, 'generateCookiePopup' ) );
	}

	public function init() {
		/**
		 * Cookie Popup settings
		 */
		$this->registerSettingSection(
			'ccpa_cookie_popup_setting',
			_x( 'Cookie Popup Settings', '(Admin)', 'ccpa-framework' )
		);
		$this->registerSettingField(
			'ccpa_enable_popup',
			_x( 'Enable Cookie Acceptance Popup', '(Admin)', 'ccpa-framework' ),
			array( $this, 'renderEnableCheckboxpopup' ),
			'ccpa_cookie_popup_setting'
		);
		$this->registerSettingField(
			'ccpa_onetime_popup',
			_x( 'Enable One Time Cookie Acceptance Popup', '(Admin)', 'ccpa-framework' ),
			array( $this, 'renderEnableOneTimeCheckboxpopup' ),
			'ccpa_cookie_popup_setting'
		);
		$this->registerSettingField(
			'ccpa_policy_popup',
			_x( 'Enable Privacy policy on Popup', '(Admin)', 'ccpa-framework' ),
			array( $this, 'renderEnablePolicyOnPopup' ),
			'ccpa_cookie_popup_setting'
		);
		$this->registerSettingField(
			'ccpa_header',
			_x( 'Cookie Acceptance Popup header', '(Admin)', 'ccpa-framework' ),
			array( $this, 'renderheaderCheckboxpopup' ),
			'ccpa_cookie_popup_setting'
		);
		$this->registerSettingField(
			'ccpa_popup_content',
			_x( 'Cookie Acceptance Popup Content', '(Admin)', 'ccpa-framework' ),
			array( $this, 'rendercontentCheckboxpopup' ),
			'ccpa_cookie_popup_setting'
		);
		/**
		 * CCPA Popup setting
		 */
		$this->registerSettingSection(
			'ccpa_popup_section',
			_x( 'Acceptance Popup Setting', '(Admin)', 'ccpa-framework' )
		);

		$this->registerSettingField(
			'ccpa_popup_position',
			_x( 'Popup Position', '(Admin)', 'ccpa-framework' ),
			array( $this, 'renderPopupPositionSelector' ),
			'ccpa_popup_section'
		);

		$this->registerSettingField(
			'ccpa_popup_theme',
			_x( 'Popup theme', '(Admin)', 'ccpa-framework' ),
			array( $this, 'renderPopupThemeSelector' ),
			'ccpa_popup_section'
		);

		$this->registerSettingField(
			'ccpa_popup_allow_text',
			_x( 'Popup Allow Text', '(Admin)', 'ccpa-framework' ),
			array( $this, 'renderAllowContentPopup' ),
			'ccpa_popup_section'
		);

		$this->registerSettingField(
			'ccpa_popup_dismiss_text',
			_x( 'Popup Dismiss Text', '(Admin)', 'ccpa-framework' ),
			array( $this, 'renderDismissContentPopup' ),
			'ccpa_popup_section'
		);

		$this->registerSettingField(
			'ccpa_popup_learnmore_text',
			_x( 'Popup Learn More Text', '(Admin)', 'ccpa-framework' ),
			array( $this, 'renderlearnmorePopup' ),
			'ccpa_popup_section'
		);

		$this->registerSettingField(
			'ccpa_popup_link_target',
			_x( 'Cookie Acceptance link target', '(Admin)', 'ccpa-framework' ),
			array( $this, 'renderpopuplinktarget' ),
			'ccpa_popup_section'
		);

		$this->registerSettingField(
			'ccpa_popup_background',
			_x( 'Cookie Acceptance Background Color', '(Admin)', 'ccpa-framework' ),
			array( $this, 'renderpopupBackgroundcolor' ),
			'ccpa_popup_section'
		);

		$this->registerSettingField(
			'ccpa_popup_text',
			_x( 'Cookie Acceptance Text Color', '(Admin)', 'ccpa-framework' ),
			array( $this, 'renderpopupTextcolor' ),
			'ccpa_popup_section'
		);

		$this->registerSettingField(
			'ccpa_popup_button_background',
			_x( 'Cookie Acceptance Button Backgroung Color', '(Admin)', 'ccpa-framework' ),
			array( $this, 'renderbuttonBackgroundcolor' ),
			'ccpa_popup_section'
		);

		$this->registerSettingField(
			'ccpa_popup_button_text',
			_x( 'Cookie Acceptance Button Color', '(Admin)', 'ccpa-framework' ),
			array( $this, 'renderbuttonTextcolor' ),
			'ccpa_popup_section'
		);

		$this->registerSettingField(
			'ccpa_popup_border_text',
			_x( 'Cookie Acceptance Border Color', '(Admin)', 'ccpa-framework' ),
			array( $this, 'renderborderTextcolor' ),
			'ccpa_popup_section'
		);
	}

	public function renderEnableCheckboxpopup() {
		$enabled = ccpa( 'options' )->get( 'enable_popup' );
		echo ccpa( 'view' )->render( 'admin/cookie-popup/enable-popup', compact( 'enabled' ) );
	}
	public function renderEnableOneTimeCheckboxpopup() {
		$enabled = ccpa( 'options' )->get( 'onetime_popup' );
		echo ccpa( 'view' )->render( 'admin/cookie-popup/enable-onetime-popup', compact( 'enabled' ) );
	}
	public function renderEnablePolicyOnPopup() {
		$enabled = ccpa( 'options' )->get( 'policy_popup' );
		echo ccpa( 'view' )->render( 'admin/cookie-popup/enable-policy-popup', compact( 'enabled' ) );
	}
	public function renderheaderCheckboxpopup() {
		$content = ccpa( 'options' )->get( 'header' );
		echo ccpa( 'view' )->render( 'admin/cookie-popup/enable_popup_header', compact( 'content' ) );
	}
	public function rendercontentCheckboxpopup() {
		$content = ccpa( 'options' )->get( 'popup_content' );
		echo ccpa( 'view' )->render( 'admin/cookie-popup/enable_popup_content', compact( 'content' ) );
	}
	public function renderPopupPositionSelector() {
		$positionAction = ccpa( 'options' )->get( 'popup_position' );
		echo ccpa( 'view' )->render( 'admin/cookie-popup/position-action', compact( 'positionAction' ) );
		echo ccpa( 'view' )->render( 'admin/cookie-popup/description-position-action' );
	}
	public function renderPopupThemeSelector() {
		$themeAction = ccpa( 'options' )->get( 'popup_theme' );
		echo ccpa( 'view' )->render( 'admin/cookie-popup/theme-action', compact( 'themeAction' ) );
		echo ccpa( 'view' )->render( 'admin/cookie-popup/description-theme-action' );
	}
	public function renderAllowContentPopup() {
		$content = ccpa( 'options' )->get( 'popup_allow_text' );
		echo ccpa( 'view' )->render( 'admin/cookie-popup/enable_popup_allow_content', compact( 'content' ) );
	}
	public function renderDismissContentPopup() {
		$content = ccpa( 'options' )->get( 'popup_dismiss_text' );
		echo ccpa( 'view' )->render( 'admin/cookie-popup/enable_popup_dismiss_content', compact( 'content' ) );
	}
	public function renderlearnmorePopup() {
		$content = ccpa( 'options' )->get( 'popup_learnmore_text' );
		echo ccpa( 'view' )->render( 'admin/cookie-popup/enable_popup_learnmore_content', compact( 'content' ) );
	}
	public function renderpopuplinktarget() {
		$content = ccpa( 'options' )->get( 'popup_link_target' );
		echo ccpa( 'view' )->render( 'admin/cookie-popup/popup_link_target', compact( 'content' ) );
	}
	public function renderpopupBackgroundcolor() {
		$content['value']  = ccpa( 'options' )->get( 'popup_background' );
		$content['option'] = 'background';
		echo ccpa( 'view' )->render( 'admin/cookie-popup/popup_background_color_picker', compact( 'content' ) );
	}
	public function renderpopupTextcolor() {
		$content['value']  = ccpa( 'options' )->get( 'popup_text' );
		$content['option'] = 'text';
		echo ccpa( 'view' )->render( 'admin/cookie-popup/popup_background_color_picker', compact( 'content' ) );
	}
	public function renderbuttonBackgroundcolor() {
		$content['value']  = ccpa( 'options' )->get( 'popup_button_background' );
		$content['option'] = 'button_background';
		echo ccpa( 'view' )->render( 'admin/cookie-popup/popup_background_color_picker', compact( 'content' ) );
	}
	public function renderbuttonTextcolor() {
		$content['value']  = ccpa( 'options' )->get( 'popup_button_text' );
		$content['option'] = 'button_text';
		echo ccpa( 'view' )->render( 'admin/cookie-popup/popup_background_color_picker', compact( 'content' ) );
	}
	public function renderborderTextcolor() {
		$content['value']  = ccpa( 'options' )->get( 'popup_border_text' );
		$content['option'] = 'border_text';
		echo ccpa( 'view' )->render( 'admin/cookie-popup/popup_background_color_picker', compact( 'content' ) );
	}
}
