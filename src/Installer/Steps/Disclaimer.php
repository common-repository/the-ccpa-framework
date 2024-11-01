<?php


namespace Data443\CCPA\Installer\Steps;

use Data443\CCPA\Installer\InstallerStep;
use Data443\CCPA\Installer\InstallerStepInterface;

class Disclaimer extends InstallerStep implements InstallerStepInterface {

	protected $slug = 'disclaimer';

	protected $type = 'wizard';

	protected $template = 'installer/steps/disclaimer';

	protected $activeSteps = 0;

	public function submit() {
		ccpa( 'options' )->set( 'plugin_disclaimer_accepted', 'yes' );
	}
}
