<?php

namespace Data443\CCPA\Installer\Steps;

use Data443\CCPA\Installer\InstallerStep;
use Data443\CCPA\Installer\InstallerStepInterface;

class Consent extends InstallerStep implements InstallerStepInterface {

	protected $slug = 'consent';

	protected $type = 'wizard';

	protected $template = 'installer/steps/consent';

	protected $activeSteps = 3;

	protected function renderContent() {
		$isRegistrationOpen  = get_option( 'users_can_register' );
		$isCommentsEnabled   = class_exists( 'Disable_Comments' ) ? false : true;
		$privacyToolsPageUrl = get_permalink( ccpa( 'options' )->get( 'tools_page' ) );
		$hasGravityForms     = class_exists( '\GFForms' );
		$hasCF7              = class_exists( '\WPCF7' );
		$hasFrm              = class_exists( '\FrmHooksController' );
		echo ccpa( 'view' )->render(
			$this->template,
			compact( 'isRegistrationOpen', 'isCommentsEnabled', 'privacyToolsPageUrl', 'hasGravityForms', 'hasCF7', 'hasFrm' )
		);
	}
}
