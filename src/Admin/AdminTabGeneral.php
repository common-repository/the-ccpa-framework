<?php

namespace Data443\CCPA\Admin;

class AdminTabGeneral extends AdminTab {

	protected $slug = 'general';

	public function __construct() {
		$this->title = _x( 'General', '(Admin)', 'ccpa-framework' );
		/**
		 * Register settings
		 */
		$this->registerSetting( 'ccpa_enable' );
		$this->registerSetting( 'ccpa_enable_tac' );
		$this->registerSetting( 'ccpa_comment_checkbox' );
		$this->registerSetting( 'ccpa_register_checkbox' );

		$this->registerSetting( 'ccpa_tools_page' );
		$this->registerSetting( 'ccpa_custom_tools_page' );
		$this->registerSetting( 'ccpa_custom_terms_page' );
		$this->registerSetting( 'ccpa_policy_page' );
		$this->registerSetting( 'ccpa_custom_policy_page' );
		$this->registerSetting( 'ccpa_terms_page' );
		$this->registerSetting( 'ccpa_name_from' );
		$this->registerSetting( 'ccpa_email_from' );
		$this->registerSetting( 'ccpa_export_action' );
		$this->registerSetting( 'ccpa_export_action_email' );
        $this->registerSetting( 'ccpa_unknown_user_message' );

		$this->registerSetting( 'ccpa_delete_action' );
		$this->registerSetting( 'ccpa_delete_action_reassign' );
		$this->registerSetting( 'ccpa_delete_action_reassign_user' );
		$this->registerSetting( 'ccpa_delete_action_email' );

		$this->registerSetting( 'ccpa_enable_stylesheet' );
		$this->registerSetting( 'ccpa_enable_theme_compatibility' );
		if ( class_exists( 'WooCommerce' ) ) {
			$this->registerSetting( 'ccpa_enable_woo_compatibility' );
			$this->registerSetting( 'ccpa_disable_checkbox_woo_compatibility' );
			$this->registerSetting( 'ccpa_disable_register_checkbox_woo_compatibility' );
		}
		if ( class_exists( 'Easy_Digital_Downloads' ) ) {
			$this->registerSetting( 'ccpa_enable_edd_compatibility' );
		}
	}

	public function init() {
		/**
		 * General
		 */
		$this->registerSettingSection(
			'ccpa_section_general',
			_x( 'General Settings', '(Admin)', 'ccpa-framework' )
		);

		$this->registerSettingField(
			'ccpa_enable',
			_x( 'Enable Privacy Tools', '(Admin)', 'ccpa-framework' ),
			array( $this, 'renderEnableCheckbox' ),
			'ccpa_section_general'
		);

		$this->registerSettingField(
			'ccpa_enable_tac',
			_x( 'Enable Term and Conditions', '(Admin)', 'ccpa-framework' ),
			array( $this, 'renderEnableCheckboxtac' ),
			'ccpa_section_general'
		);

		$this->registerSettingField(
			'ccpa_comment_checkbox',
			_x( 'Disable Comment Checkbox', '(Admin)', 'ccpa-framework' ),
			array( $this, 'renderCommentCheckbox' ),
			'ccpa_section_general'
		);

		$this->registerSettingField(
			'ccpa_register_checkbox',
			_x( 'Disable Register Form Checkbox', '(Admin)', 'ccpa-framework' ),
			array( $this, 'renderRegisterCheckbox' ),
			'ccpa_section_general'
		);

		/**
		 * CCPA Email setting
		 */
		$this->registerSettingSection(
			'ccpa_email_section',
			_x( 'Email Setting', '(Admin)', 'ccpa-framework' )
		);

		$this->registerSettingField(
			'ccpa_name_from',
			_x( 'From Name', '(Admin)', 'ccpa-framework' ),
			array( $this, 'renderNameFrom' ),
			'ccpa_email_section'
		);

		$this->registerSettingField(
			'ccpa_email_from',
			_x( 'From Email', '(Admin)', 'ccpa-framework' ),
			array( $this, 'renderEmailFrom' ),
			'ccpa_email_section'
		);
		/**
		 * CCPA system pages
		 */
		$this->registerSettingSection(
			'ccpa_section_pages',
			_x( 'Pages', '(Admin)', 'ccpa-framework' )
		);

		$this->registerSettingField(
			'ccpa_tools_page',
			_x( 'Privacy Tools Page', '(Admin)', 'ccpa-framework' ) . '*',
			array( $this, 'renderPrivacyToolsPageSelector' ),
			'ccpa_section_pages'
		);

		$this->registerSettingField(
			'ccpa_custom_tools_page',
			_x( 'Privacy Tools Custom URL', '(Admin)', 'ccpa-framework'),
			array( $this, 'renderToolsCustomPageSelector' ),
			'ccpa_section_pages'
		);

		$this->registerSettingField(
			'ccpa_policy_page',
			_x( 'Privacy Policy Page', '(Admin)', 'ccpa-framework' ) . '*',
			array( $this, 'renderPolicyPageSelector' ),
			'ccpa_section_pages'
		);

		$this->registerSettingField(
			'ccpa_custom_policy_page',
			_x( 'Privacy Policy Custom URL', '(Admin)', 'ccpa-framework' ),
			array( $this, 'renderPolicyCustomPageSelector' ),
			'ccpa_section_pages'
		);

		$this->registerSettingField(
			'ccpa_terms_page',
			_x( 'Terms & Conditions Page', '(Admin)', 'ccpa-framework' ),
			array( $this, 'renderTermsPageSelector' ),
			'ccpa_section_pages'
		);

		$this->registerSettingField(
			'ccpa_custom_terms_page',
			_x('Terms & Conditions Custom URL', '(Admin)', 'ccpa-framework'),
			array( $this, 'renderCustomTermsPageSelector' ),
			'ccpa_section_pages'
		);

		/**
		 * View & Export
		 */
		$this->registerSettingSection(
			'ccpa_section_export',
			_x( 'View & Export Data', '(Admin)', 'ccpa-framework' )
		);

		$this->registerSettingField(
			'ccpa_export_action',
			_x( 'Export action', '(Admin)', 'ccpa-framework' ),
			array( $this, 'renderExportActionSelector' ),
			'ccpa_section_export'
		);

		$this->registerSettingField(
			'ccpa_export_action_email',
			_x( 'Email to notify', '(Admin)', 'ccpa-framework' ),
			array( $this, 'renderExportActionEmail' ),
			'ccpa_section_export',
			array( 'class' => 'js-ccpa-export-action-email hidden' )
		);

        $this->registerSettingField(
            'ccpa_unknown_user_message',
            _x('Unknown Data Subject Message', '(Admin)', 'ccpa-framework'),
            [$this, 'renderUnknownUserField'],
            'ccpa_section_export'
        );

		/**
		 * Delete data
		 */
		$this->registerSettingSection(
			'ccpa_section_delete',
			_x( 'Delete & Anonymize Data', '(Admin)', 'ccpa-framework' )
		);

		$this->registerSettingField(
			'ccpa_delete_action',
			_x( 'Delete action', '(Admin)', 'ccpa-framework' ),
			array( $this, 'renderDeleteActionSelector' ),
			'ccpa_section_delete'
		);

		$this->registerSettingField(
			'ccpa_delete_action_reassign',
			_x( 'Delete or reassign content?', '(Admin)', 'ccpa-framework' ),
			array( $this, 'renderDeleteActionReassign' ),
			'ccpa_section_delete',
			array( 'class' => 'js-ccpa-delete-action-reassign hidden' )
		);

		$this->registerSettingField(
			'ccpa_delete_action_reassign_user',
			_x( 'Reassign content to', '(Admin)', 'ccpa-framework' ),
			array( $this, 'renderDeleteActionReassignUser' ),
			'ccpa_section_delete',
			array( 'class' => 'js-ccpa-delete-action-reassign-user hidden' )
		);

		$this->registerSettingField(
			'ccpa_delete_action_email',
			_x( 'Email to notify', '(Admin)', 'ccpa-framework' ),
			array( $this, 'renderDeleteActionEmail' ),
			'ccpa_section_delete',
			array( 'class' => 'js-ccpa-delete-action-email hidden' )
		);

		/**
		 * Stylesheet
		 */

		$this->registerSettingSection(
			'ccpa_section_stylesheet',
			_x( 'Styling', '(Admin)', 'ccpa-framework' )
		);

		$this->registerSettingField(
			'ccpa_enable_theme_compatibility',
			_x( 'Enable basic styling on Privacy Tools page', '(Admin)', 'ccpa-framework' ),
			array( $this, 'renderStylesheetSelector' ),
			'ccpa_section_stylesheet'
		);

		if ( ccpa( 'themes' )->isCurrentThemeSupported() ) {

			/**
			 * Compatibility settings
			 */
			$this->registerSettingSection(
				'ccpa_section_compatibility',
				_x( 'Compatibility', '(Admin)', 'ccpa-framework' )
			);

			$this->registerSettingField(
				'ccpa_enable_theme_compatibility',
				_x( 'Enable automatic theme compatibility', '(Admin)', 'ccpa-framework' ),
				array( $this, 'renderThemeCompatibilitySelector' ),
				'ccpa_section_compatibility'
			);
		}
		if ( class_exists( 'WooCommerce' ) ) {

			/**
			 * Woocommerce Compatibility settings
			 */
			$this->registerSettingSection(
				'ccpa_woo_compatibility',
				_x( 'Woocommerce Integration', '(Admin)', 'ccpa-framework' )
			);

			$this->registerSettingField(
				'ccpa_enable_woo_compatibility',
				_x( 'Enable WooCommerce Compatibility', '(Admin)', 'ccpa-framework' ),
				array( $this, 'renderwooCompatibilitySelector' ),
				'ccpa_woo_compatibility'
			);

			$this->registerSettingField(
				'ccpa_disable_checkbox_woo_compatibility',
				_x( 'Disable WooCommerce Privacy Checkbox', '(Admin)', 'ccpa-framework' ),
				array( $this, 'renderwoodisablewooSelector' ),
				'ccpa_woo_compatibility'
			);

			$this->registerSettingField(
				'ccpa_disable_register_checkbox_woo_compatibility',
				_x( 'Disable WooCommerce Register Privacy Checkbox', '(Admin)', 'ccpa-framework' ),
				array( $this, 'renderwooregisterdisablewooSelector' ),
				'ccpa_woo_compatibility'
			);
		}

		if ( class_exists( 'Easy_Digital_Downloads' ) ) {
			/**
			 * Easy Digital Downloads Compatibility settings
			 */
			$this->registerSettingSection(
				'ccpa_edd_compatibility',
				_x( 'Easy Digital Download Integration', '(Admin)', 'ccpa-framework' )
			);

			$this->registerSettingField(
				'ccpa_enable_edd_compatibility',
				_x( 'Enable EDD Compatibility', '(Admin)', 'ccpa-framework' ),
				array( $this, 'rendereddCompatibilitySelector' ),
				'ccpa_edd_compatibility'
			);
		}
	}

	/**
	 * Rendering Views
	 */

	public function renderEnableCheckbox() {
		$enabled = ccpa( 'options' )->get( 'enable' );
		echo ccpa( 'view' )->render( 'admin/general/enable', compact( 'enabled' ) );
	}

	public function renderEnableCheckboxtac() {
		$enabled = ccpa( 'options' )->get( 'enable_tac' );
		echo ccpa( 'view' )->render( 'admin/general/enable-tac', compact( 'enabled' ) );
	}

	public function renderCommentCheckbox() {
		$content['option_name'] = 'comment_checkbox';
		$content['value']       = ccpa( 'options' )->get( 'comment_checkbox' );
		$content['option']      = _x( 'Disable Checkbox For Comments', '(Admin)', 'ccpa-framework' );
		echo ccpa( 'view' )->render( 'admin/general/disble-checkbox', compact( 'content' ) );
	}

	public function renderRegisterCheckbox() {
		$content['option_name'] = 'register_checkbox';
		$content['value']       = ccpa( 'options' )->get( 'register_checkbox' );
		$content['option']      = _x( 'Disable Checkbox For Register Form', '(Admin)', 'ccpa-framework' );
		echo ccpa( 'view' )->render( 'admin/general/disble-checkbox', compact( 'content' ) );
	}

	public function renderEnableCheckboxpopup() {
		$enabled = ccpa( 'options' )->get( 'enable_popup' );
		echo ccpa( 'view' )->render( 'admin/general/enable-popup', compact( 'enabled' ) );
	}

	public function renderEnableOneTimeCheckboxpopup() {
		$enabled = ccpa( 'options' )->get( 'onetime_popup' );
		echo ccpa( 'view' )->render( 'admin/general/enable-onetime-popup', compact( 'enabled' ) );
	}

	public function renderheaderCheckboxpopup() {
		$content = ccpa( 'options' )->get( 'header' );
		echo ccpa( 'view' )->render( 'admin/general/enable_popup_header', compact( 'content' ) );
	}
	public function rendercontentCheckboxpopup() {
		$content = ccpa( 'options' )->get( 'popup_content' );
		echo ccpa( 'view' )->render( 'admin/general/enable_popup_content', compact( 'content' ) );
	}

	public function renderNameFrom() {
		$content = ccpa( 'options' )->get( 'name_from' );
		echo ccpa( 'view' )->render( 'admin/general/name_from', compact( 'content' ) );
	}

	public function renderEmailFrom() {
		$content = ccpa( 'options' )->get( 'email_from' );
		echo ccpa( 'view' )->render( 'admin/general/email_from', compact( 'content' ) );
	}

	public function renderpopupBackgroundcolor() {
		$content['value']  = ccpa( 'options' )->get( 'popup_background' );
		$content['option'] = 'background';
		echo ccpa( 'view' )->render( 'admin/general/popup_background_color_picker', compact( 'content' ) );
	}

	public function renderpopupTextcolor() {
		$content['value']  = ccpa( 'options' )->get( 'popup_text' );
		$content['option'] = 'text';
		echo ccpa( 'view' )->render( 'admin/general/popup_background_color_picker', compact( 'content' ) );
	}

	public function renderbuttonBackgroundcolor() {

		$content['value']  = ccpa( 'options' )->get( 'popup_button_background' );
		$content['option'] = 'button_background';
		echo ccpa( 'view' )->render( 'admin/general/popup_background_color_picker', compact( 'content' ) );
	}
	public function renderbuttonTextcolor() {
		$content['value']  = ccpa( 'options' )->get( 'popup_button_text' );
		$content['option'] = 'button_text';
		echo ccpa( 'view' )->render( 'admin/general/popup_background_color_picker', compact( 'content' ) );
	}

	public function renderborderTextcolor() {
		$content['value']  = ccpa( 'options' )->get( 'popup_border_text' );
		$content['option'] = 'border_text';
		echo ccpa( 'view' )->render( 'admin/general/popup_background_color_picker', compact( 'content' ) );
	}

	public function renderPrivacyToolsPageSelector() {
		wp_dropdown_pages(
			array(
				'name'              => 'ccpa_tools_page',
				'show_option_none'  => _x( '&mdash; Select &mdash;', '(Admin)', 'ccpa-framework' ),
				'option_none_value' => '0',
				'selected'          => ccpa( 'options' )->get( 'tools_page' ),
				'class'             => 'js-ccpa-select2 ccpa-select',
				'post_status'       => 'publish,draft',
			)
		);
		echo ccpa( 'view' )->render( 'admin/general/description-data-page' );
	}

	public function renderToolsCustomPageSelector()	{
		$content = ccpa( 'options' )->get( 'custom_tools_page' );
		echo ccpa( 'view' )->render( 'admin/general/custom-tools-url', compact( 'content' ));
	}

	/**
	 * Render the CCPA policy page selector dropdown
	 */
	public function renderPolicyPageSelector() {
		wp_dropdown_pages(
			array(
				'name'              => 'ccpa_policy_page',
				'show_option_none'  => _x( '&mdash; Select &mdash;', '(Admin)', 'ccpa-framework' ),
				'option_none_value' => '0',
				'selected'          => ccpa( 'options' )->get( 'policy_page' ),
				'class'             => 'js-ccpa-select2 ccpa-select',
				'post_status'       => 'publish,draft',
			)
		);
		echo ccpa( 'view' )->render( 'admin/privacy-policy/description-policy-page' );
	}

	public function renderPolicyCustomPageSelector() {
		$content = ccpa( 'options' )->get( 'custom_policy_page' );
		echo ccpa( 'view' )->render( 'admin/general/custom-policy-url', compact( 'content' ) );
	}

	public function renderTermsPageSelector() {
		wp_dropdown_pages(
			array(
				'name'              => 'ccpa_terms_page',
				'show_option_none'  => _x( '&mdash; Select &mdash;', '(Admin)', 'ccpa-framework' ),
				'option_none_value' => '0',
				'selected'          => ccpa( 'options' )->get( 'terms_page' ),
				'class'             => 'js-ccpa-select2 ccpa-select',
				'post_status'       => 'publish,draft',
			)
		);
		echo ccpa( 'view' )->render( 'admin/general/description-terms-page' );
	}

	public function renderCustomTermsPageSelector()
	{
		$content = ccpa( 'options' )->get( 'custom_terms_page' );
		echo ccpa( 'view' )->render( 'admin/general/custom-terms-url', compact( 'content' ) );
	}

	public function renderExportActionSelector() {
		$exportAction = ccpa( 'options' )->get( 'export_action' );
		echo ccpa( 'view' )->render( 'admin/general/export-action', compact( 'exportAction' ) );
		echo ccpa( 'view' )->render( 'admin/general/description-export-action' );
	}

    public function renderUnknownUserField()
    {
        $content = ccpa('options')->get('unknown_user_message', CCPA_DEFAULT_UNKNOWN_USER_MESSAGE);
        if (empty(trim($content))) {
            $content = CCPA_DEFAULT_UNKNOWN_USER_MESSAGE;
            ccpa('options')->set('unknown_user_message', $content);
        }
        echo ccpa('view')->render('admin/general/unknown-user-message', compact('content'));
    }

	public function renderPopupThemeSelector() {
		$themeAction = ccpa( 'options' )->get( 'popup_theme' );
		echo ccpa( 'view' )->render( 'admin/general/theme-action', compact( 'themeAction' ) );
		echo ccpa( 'view' )->render( 'admin/general/description-theme-action' );
	}
	public function renderPopupPositionSelector() {
		$positionAction = ccpa( 'options' )->get( 'popup_position' );
		echo ccpa( 'view' )->render( 'admin/general/position-action', compact( 'positionAction' ) );
		echo ccpa( 'view' )->render( 'admin/general/description-position-action' );
	}

	public function renderExportActionEmail() {
		$exportActionEmail = ccpa( 'options' )->get( 'export_action_email' );
		echo ccpa( 'view' )->render( 'admin/general/export-action-email', compact( 'exportActionEmail' ) );
	}

	public function renderDeleteActionSelector() {
		$deleteAction = ccpa( 'options' )->get( 'delete_action' );
		echo ccpa( 'view' )->render( 'admin/general/delete-action', compact( 'deleteAction' ) );
		echo ccpa( 'view' )->render( 'admin/general/description-delete-action' );
	}

	public function renderDeleteActionReassign() {
		$reassign = ccpa( 'options' )->get( 'delete_action_reassign' );
		echo ccpa( 'view' )->render( 'admin/general/delete-action-reassign', compact( 'reassign' ) );
	}

	public function renderDeleteActionReassignUser() {
		wp_dropdown_users(
			array(
				'name'              => 'ccpa_delete_action_reassign_user',
				'show_option_none'  => _x( '&mdash; Select &mdash;', '(Admin)', 'ccpa-framework' ),
				'option_none_value' => '0',
				'selected'          => ccpa( 'options' )->get( 'delete_action_reassign_user' ),
				'class'             => 'js-ccpa-select2 ccpa-select',
				'role__in'          => apply_filters( 'ccpa/options/reassign/roles', array( 'administrator', 'editor' ) ),
			)
		);
	}

	public function renderDeleteActionEmail() {
		$deleteActionEmail = ccpa( 'options' )->get( 'delete_action_email' );
		echo ccpa( 'view' )->render( 'admin/general/delete-action-email', compact( 'deleteActionEmail' ) );
	}

	public function renderStylesheetSelector() {
		$enabled = ccpa( 'options' )->get( 'enable_stylesheet' );
		echo ccpa( 'view' )->render( 'admin/general/stylesheet', compact( 'enabled' ) );
	}

	public function renderThemeCompatibilitySelector() {
		$enabled = ccpa( 'options' )->get( 'enable_theme_compatibility' );
		echo ccpa( 'view' )->render( 'admin/general/theme-compatibility', compact( 'enabled' ) );
	}

	public function renderwooCompatibilitySelector() {
		$enabled = ccpa( 'options' )->get( 'enable_woo_compatibility' );
		echo ccpa( 'view' )->render( 'admin/general/woo-compatibility', compact( 'enabled' ) );
	}
	public function renderwoodisablewooSelector() {
		$enabled = ccpa( 'options' )->get( 'disable_checkbox_woo_compatibility' );
		echo ccpa( 'view' )->render( 'admin/general/woo-disable_checkbox', compact( 'enabled' ) );
	}
	public function renderwooregisterdisablewooSelector() {
		$enabled = ccpa( 'options' )->get( 'disable_register_checkbox_woo_compatibility' );
		echo ccpa( 'view' )->render( 'admin/general/woo-disable_register_checkbox', compact( 'enabled' ) );
	}
	public function rendereddCompatibilitySelector() {
		$enabled = ccpa( 'options' )->get( 'enable_edd_compatibility' );
		echo ccpa( 'view' )->render( 'admin/general/edd-compatibility', compact( 'enabled' ) );
	}
}
