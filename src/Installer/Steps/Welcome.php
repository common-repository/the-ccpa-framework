<?php

namespace Data443\CCPA\Installer\Steps;

use Data443\CCPA\Installer\InstallerStep;
use Data443\CCPA\Installer\InstallerStepInterface;

/**
 * Handle the first step on installer screen
 *
 * Class Welcome
 *
 * @package Data443\CCPA\Installer\Steps
 */
class Welcome extends InstallerStep implements InstallerStepInterface {

	protected $slug = 'welcome';

	protected $type = 'wizard';

	protected $template = 'installer/steps/welcome';

	protected $activeSteps = 0;

	protected function renderFooter() {
		echo ccpa( 'view' )->render( 'installer/footer', array( 'disableBackButton' => true ) );
	}
}
