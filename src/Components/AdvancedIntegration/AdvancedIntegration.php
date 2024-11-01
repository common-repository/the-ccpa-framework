<?php

namespace Data443\CCPA\Components\AdvancedIntegration;

/**
 * Handles putting together and rendering the privacy policy page
 *
 * Class AdvancedIntegration
 *
 * @package Data443\CCPA\Components\AdvancedIntegration
 */
class AdvancedIntegration {

	public function __construct() {
		add_filter( 'ccpa/admin/tabs', array( $this, 'registerAdminTab' ), 70 );
	}

	public function registerAdminTab( $tabs ) {
		$tabs['advanced-integration'] = new AdminTabAdvancedIntegration();
		return $tabs;
	}
}
