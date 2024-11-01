<?php

namespace Data443\CCPA\Installer;

/**
 * Handle the installer wizard pages
 *
 * Class InstallerWizard
 *
 * @package Data443\CCPA\Installer
 */
class InstallerWizard {

	/**
	 * InstallerWizard constructor.
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'registerWizardPage' ) );
	}

	/**
	 * Register the installer page with WordPress
	 */
	public function registerWizardPage() {
		add_dashboard_page( '', '', 'manage_options', 'ccpa-setup', '' );
	}

	/**
	 * Check if we are already on the installer page
	 *
	 * @return bool
	 */
	public function isWizardPage() {
		return isset( $_GET['page'] ) && 'ccpa-setup' === $_GET['page'];
	}
}
