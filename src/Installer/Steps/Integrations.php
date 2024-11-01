<?php

namespace Data443\CCPA\Installer\Steps;

use Data443\CCPA\Installer\InstallerStep;
use Data443\CCPA\Installer\InstallerStepInterface;

class Integrations extends InstallerStep implements InstallerStepInterface {

	protected $slug = 'integrations';

	protected $type = 'wizard';

	protected $template = 'installer/steps/integrations';

	protected $activeSteps = 4;

	protected function renderContent() {
		$enableThemeCompatibility = ccpa( 'options' )->get( 'enable_theme_compatibility' );
		$currentTheme             = ccpa( 'themes' )->getCurrentThemeName();
		$isThemeSupported         = ccpa( 'themes' )->isCurrentThemeSupported();

		$hasWooCommerce = false;
		$hasEDD         = false;
		$hasSendGrid    = class_exists( '\Sendgrid_Tools' );

		echo ccpa( 'view' )->render(
			$this->template,
			compact(
				'enableThemeCompatibility',
				'hasEDD',
				'hasWooCommerce',
				'currentTheme',
				'isThemeSupported',
				'hasSendGrid'
			)
		);
	}

	public function submit() {
		if ( isset( $_POST['ccpa_enable_theme_compatibility'] ) && 'yes' === $_POST['ccpa_enable_theme_compatibility'] ) {
			ccpa( 'options' )->set( 'enable_theme_compatibility', true );
		} else {
			ccpa( 'options' )->set( 'enable_theme_compatibility', false );
		}
	}
}
