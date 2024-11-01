<?php

namespace Data443\CCPA\Components\PrivacyManager;

use Data443\CCPA\Admin\AdminTab;

class AdminTabPrivacyManager extends AdminTab {

	/* @var string */
	protected $slug = 'privacy-manager';

	/* @var PolicyGenerator */
	protected $policyGenerator;

	public function __construct() {
		$this->title = _x( 'Global Privacy Manager', '(Admin)', 'ccpa-framework' );

		add_action( 'ccpa/admin/action/PrivacyManager/generate', array( $this, 'generatePrivacyManager' ) );
	}

	public function init() {
		/**
		 * General settings
		 */
		$this->registerSettingSection(
			'ccpa_section_privacy_policy',
			_x( 'Global Privacy Manager', '(Admin)', 'ccpa-framework' ),
			array( $this, 'renderHeader' )
		);

	}

	public function renderHeader() {
		echo ccpa( 'view' )->render( 'admin/privacy-manager/header' );
	}

	public function renderSubmitButton() {
		// submit_button(_x('Save', '(Admin)', 'ccpa-framework'));
	}

}
