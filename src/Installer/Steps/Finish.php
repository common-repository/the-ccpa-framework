<?php

namespace Data443\CCPA\Installer\Steps;

use Data443\CCPA\Installer\InstallerStep;
use Data443\CCPA\Installer\InstallerStepInterface;

class Finish extends InstallerStep implements InstallerStepInterface {

	protected $slug = 'finish';

	protected $type = 'wizard';

	protected $template = 'installer/steps/finish';

	protected $activeSteps = 4;

	public function submit() {
		ccpa( 'options' )->set( 'is_installed', true );
	}

	protected function renderFooter() {
		echo ccpa( 'view' )->render( 'installer/footer', array( 'disableBackButton' => true ) );
	}
}
