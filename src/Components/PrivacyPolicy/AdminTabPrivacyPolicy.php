<?php

namespace Data443\CCPA\Components\PrivacyPolicy;

use Data443\CCPA\Admin\AdminTab;

class AdminTabPrivacyPolicy extends AdminTab {

	/* @var string */
	protected $slug = 'privacy-policy';

	/* @var PolicyGenerator */
	protected $policyGenerator;

	public function __construct( PolicyGenerator $policyGenerator ) {
		$this->policyGenerator = $policyGenerator;

		$this->title = _x( 'Privacy Policy', '(Admin)', 'ccpa-framework' );

		$this->registerSetting( 'ccpa_company_name' );
		$this->registerSetting( 'ccpa_contact_email' );
		$this->registerSetting( 'ccpa_company_location' );

		$this->registerSetting( 'ccpa_representative_contact_name' );
		$this->registerSetting( 'ccpa_representative_contact_email' );
		$this->registerSetting( 'ccpa_representative_contact_phone' );

		$this->registerSetting( 'ccpa_dpa_website' );
		$this->registerSetting( 'ccpa_dpa_email' );
		$this->registerSetting( 'ccpa_dpa_phone' );

		$this->registerSetting( 'ccpa_has_dpo' );
		$this->registerSetting( 'ccpa_dpo_name' );
		$this->registerSetting( 'ccpa_dpo_email' );
		$this->registerSetting( 'ccpa_delete_text' );

		add_action( 'ccpa/admin/action/privacy-policy/generate', array( $this, 'generatePolicy' ) );
	}

	public function init() {
		/**
		 * General settings
		 */
		$this->registerSettingSection(
			'ccpa_section_privacy_policy',
			_x( 'Privacy Policy', '(Admin)', 'ccpa-framework' ),
			array( $this, 'renderHeader' )
		);

		/**
		 * Company info
		 */
		$this->registerSettingSection(
			'ccpa_section_privacy_policy_company',
			_x( 'Company information', '(Admin)', 'ccpa-framework' )
		);

		$this->registerSettingField(
			'ccpa_company_name',
			_x( 'Company Name', '(Admin)', 'ccpa-framework' ),
			array( $this, 'renderCompanyNameHtml' ),
			'ccpa_section_privacy_policy_company'
		);

		$this->registerSettingField(
			'ccpa_company_email',
			_x( 'Company Email', '(Admin)', 'ccpa-framework' ),
			array( $this, 'renderCompanyEmailHtml' ),
			'ccpa_section_privacy_policy_company'
		);

		$this->registerSettingField(
			'ccpa_company_location',
			_x( 'Company Location', '(Admin)', 'ccpa-framework' ),
			array( $this, 'renderCompanyLocationHtml' ),
			'ccpa_section_privacy_policy_company'
		);

		/**
		 * Change Delete Text
		 */

		$this->registerSettingField(
			'ccpa_delete_text',
			_x( 'Delete Text', '(Admin)', 'ccpa-framework' ),
			array( $this, 'renderDeleteTextHtml' ),
			'ccpa_section_privacy_policy_company'
		);

	}

	public function renderHeader() {
		echo ccpa( 'view' )->render( 'admin/privacy-policy/header' );
	}

	/**
	 * Company info
	 */

	public function renderCompanyNameHtml() {
		$value       = ccpa( 'options' )->get( 'company_name' ) ? esc_attr( ccpa( 'options' )->get( 'company_name' ) ) : '';
		$placeholder = _x( 'Company Name', '(Admin)', 'ccpa-framework' );
		echo "<input name='ccpa_company_name' placeholder='{$placeholder}' value='{$value}'>";
	}

	public function renderCompanyEmailHtml() {
		$value       = ccpa( 'options' )->get( 'contact_email' ) ? esc_attr( ccpa( 'options' )->get( 'contact_email' ) ) : '';
		$placeholder = _x( 'Contact Email', '(Admin)', 'ccpa-framework' );
		echo "<input type='email' name='ccpa_contact_email' placeholder='{$placeholder}' value='{$value}'>";
	}

	public function renderCompanyLocationHtml() {
		$country              = ccpa( 'options' )->get( 'company_location' ) ? ccpa( 'options' )->get( 'company_location' ) : '';
		$countrySelectOptions = ccpa( 'helpers' )->getCountrySelectOptions( $country );
		echo ccpa( 'view' )->render( 'admin/privacy-policy/company-location', compact( 'countrySelectOptions' ) );
	}

	public function renderDeleteTextHtml() {
		$value       = ccpa( 'options' )->get( 'delete_text' ) ? esc_attr( ccpa( 'options' )->get( 'delete_text' ) ) : '';
		$placeholder = _x( 'Delete Text', '(Admin)', 'ccpa-framework' );
		echo "<input name='ccpa_delete_text' placeholder='{$placeholder}' value='{$value}'>";
	}

	public function generatePolicy() {
		$policyPage = ccpa( 'options' )->get( 'policy_page' );

		// todo: handle errors
		if ( ! $policyPage ) {
			return;
		}

		$policy = ccpa( 'view' )->render(
			'policy/policy'
		);

		wp_update_post(
			array(
				'ID'           => $policyPage,
				'post_content' => $policy,
			)
		);

		wp_safe_redirect( ccpa( 'helpers' )->getAdminUrl( '&ccpa-tab=privacy-policy&ccpa_notice=policy_generated' ) );
	}

	/**
	 * Render either the settings or the generated policy
	 */
	public function renderContents() {
		if ( isset( $_GET['generate'] ) && 'yes' == $_GET['generate'] ) {
			return $this->renderPolicy();
		} else {
			return $this->renderSettings();
		}
	}

	/**
	 * Render the contents including settings fields, sections and submit button.
	 * Trigger hooks for rendering content before and after the settings fields.
	 *
	 * @return string
	 */
	public function renderSettings() {
		ob_start();

		do_action( "ccpa/tabs/{$this->getSlug()}/before", $this );
		$this->settingsFields( $this->getOptionsGroupName() );
		do_settings_sections( $this->getOptionsGroupName() );
		do_action( "ccpa/tabs/{$this->getSlug()}/after", $this );

		$this->renderSubmitButton();

		return ob_get_clean();
	}

	public function renderPolicy() {
		$policyPageId = ccpa( 'options' )->get( 'policy_page' );
		if ( $policyPageId ) {
			$policyUrl = get_edit_post_link( $policyPageId );
		} else {
			$policyUrl = false;
		}

		$editor  = $this->getPolicyEditor();
		$backUrl = ccpa( 'helpers' )->getAdminUrl( '&ccpa-tab=privacy-policy' );

		return ccpa( 'view' )->render( 'admin/privacy-policy/generated', compact( 'editor', 'policyUrl', 'backUrl' ) );
	}

	protected function getPolicyEditor() {
		ob_start();

		wp_editor(
			wp_kses_post( $this->policyGenerator->generate() ),
			'ccpa_policy',
			array(
				'media_buttons' => false,
				'editor_height' => 600,
				'teeny'         => true,
				'editor_css'    => '<style>#mceu_16 { display: none; }</style>',
			)
		);

		return ob_get_clean();
	}

	/**
	 * Render WP's default submit button
	 */
	public function renderSubmitButton() {
		submit_button( _x( 'Save & Generate Policy', '(Admin)', 'ccpa-framework' ) );
	}

	/**
	 * In order to set up a proper redirect to the generated policy
	 * after saving settings, we modify the way wp_nonce_field is called and insert our own referer input.
	 *
	 * @param $optionGroup
	 */
	public function settingsFields( $optionGroup ) {
		echo "<input type='hidden' name='option_page' value='" . esc_attr( $optionGroup ) . "' />";
		echo '<input type="hidden" name="action" value="update" />';
		wp_nonce_field( "$optionGroup-options", '_wpnonce', false );
		echo '<input type="hidden" name="_wp_http_referer" value="' . esc_attr( wp_unslash( $_SERVER['REQUEST_URI'] ) . '&generate=yes' ) . '" />';
	}
}
