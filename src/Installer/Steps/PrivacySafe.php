<?php


namespace Data443\CCPA\Installer\Steps; 

use Data443\CCPA\Installer\InstallerStep;
use Data443\CCPA\Installer\InstallerStepInterface;

class PrivacySafe extends InstallerStep implements InstallerStepInterface {
	protected $slug = 'privacy-safe';

	protected $type = 'wizard';

	protected $template = 'installer/steps/privacy-safe';

	protected $activeSteps = 0;

	public function submit() {
		if ( isset( $_POST['ccpa_privacy_safe_params'] ) ) {
			$seal_code  = sanitize_text_field( wp_unslash( $_POST['ccpa_privacy_safe_params'] ) );
			$image_code = sanitize_text_field( wp_unslash( $_POST['ccpa_privacy_safe_imagecode'] ) );
			if ( ! get_option( 'ccpa_privacy_safe_params' ) ) {
				ccpa( 'options' )->set( 'ccpa_privacy_safe_params', $seal_code );
			} else {
				update_option( 'ccpa_privacy_safe_params', $seal_code );
			}
			if ( ! get_option( 'ccpa_privacy_safe_imagecode' ) ) {
				ccpa( 'options' )->set( 'ccpa_privacy_safe_imagecode', $image_code );
			} else {
				update_option( 'ccpa_privacy_safe_imagecode', $image_code );
			}
		}

	}
}
