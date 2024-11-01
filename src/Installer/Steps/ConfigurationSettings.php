<?php

namespace Data443\CCPA\Installer\Steps;

use Data443\CCPA\Installer\InstallerStep;
use Data443\CCPA\Installer\InstallerStepInterface;

class ConfigurationSettings extends InstallerStep implements InstallerStepInterface {

	protected $slug = 'configuration-settings';

	protected $type = 'wizard';

	protected $template = 'installer/steps/configuration-settings';

	protected $activeSteps = 1;

	protected function renderContent() {
		$privacyToolsPageUrl = get_permalink( ccpa( 'options' )->get( 'tools_page' ) );
		$deleteAction        = ccpa( 'options' )->get( 'delete_action' );
		$deleteActionEmail   = ccpa( 'options' )->get( 'delete_action_email' );

		$exportAction      = ccpa( 'options' )->get( 'export_action' );
		$exportActionEmail = ccpa( 'options' )->get( 'export_action_email' );

		$reassign     = ccpa( 'options' )->get( 'delete_action_reassign' );
		$reassignUser = ccpa( 'options' )->get( 'delete_action_reassign_user' );

		echo ccpa( 'view' )->render(
			$this->template,
			compact(
				'deleteAction',
				'deleteActionEmail',
				'exportAction',
				'exportActionEmail',
				'privacyToolsPageUrl',
				'reassign',
				'reassignUser'
			)
		);
	}

	public function submit() {
		if ( isset( $_POST['ccpa_export_action'] ) ) {
			ccpa( 'options' )->set( 'export_action', sanitize_text_field( $_POST['ccpa_export_action'] ) );
		}

		if ( isset( $_POST['ccpa_export_action_email'] ) ) {
			ccpa( 'options' )->set( 'export_action_email', sanitize_email( $_POST['ccpa_export_action_email'] ) );
		}

		if ( isset( $_POST['ccpa_delete_action'] ) ) {
			ccpa( 'options' )->set( 'delete_action', sanitize_text_field( $_POST['ccpa_delete_action'] ) );
		}

		if ( isset( $_POST['ccpa_delete_action_email'] ) ) {
			ccpa( 'options' )->set( 'delete_action_email', sanitize_email( $_POST['ccpa_delete_action_email'] ) );
		}

		if ( isset( $_POST['ccpa_delete_action_reassign'] ) ) {
			ccpa( 'options' )->set( 'delete_action_reassign', sanitize_text_field( $_POST['ccpa_delete_action_reassign'] ) );
		}

		if ( isset( $_POST['ccpa_delete_action_reassign_user'] ) ) {
			ccpa( 'options' )->set( 'delete_action_reassign_user', sanitize_text_field( $_POST['ccpa_delete_action_reassign_user'] ) );
		}
	}
}
