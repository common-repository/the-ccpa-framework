<?php

namespace Data443\CCPA\Admin;

class AdminHelper {

	public function __construct() {
		$this->toolsHelper();
		$this->autoinstallHelper();
		$this->policyHelper();
		$this->settingsHelper();
	}

	protected function toolsHelper() {
		$toolsPage = ccpa( 'options' )->get( 'tools_page' );

		// Display the notice only on Tools page
		if ( ! $toolsPage || ! isset( $_GET['post'] ) || $_GET['post'] !== $toolsPage ) {
			return;
		}

		$post    = get_post( $toolsPage );
		$helpUrl = ccpa( 'helpers' )->docs();

		if ( ! stristr( $post->post_content, '<!-- wp:shortcode -->[ccpa_privacy_tools]<!-- /wp:shortcode -->' ) ) {
			ccpa( 'admin-notice' )->add( 'admin/notices/helper-tools', compact( 'helpUrl' ) );
		}
	}

	protected function autoinstallHelper() {
		if ( ! isset( $_GET['ccpa-notice'] ) || empty( $_GET['ccpa-notice'] ) || 'autoinstall' !== $_GET['ccpa-notice'] ) {
			return;
		}

		ccpa( 'admin-notice' )->add( 'admin/notices/helper-autoinstall');
	}

	protected function policyHelper() {
		$policyPage = ccpa( 'options' )->get( 'policy_page' );

		// Display the notice only on Policy page
		if ( ! $policyPage || ! isset( $_GET['post'] ) || $_GET['post'] !== $policyPage ) {
			return;
		}

		$post    = get_post( $policyPage );
		$helpUrl = ccpa( 'helpers' )->docs();

		if ( stristr( $post->post_content, '[TODO]' ) ) {
			ccpa( 'admin-notice' )->add( 'admin/notices/helper-policy', compact( 'helpUrl' ) );
		}
	}

	protected function settingsHelper() {
		if ( ccpa( 'options' )->get( 'is_installed' ) && ( ( ! ccpa( 'options' )->get( 'tools_page' ) || is_null( get_post( ccpa( 'options' )->get( 'tools_page' ) ) ) ) ) && ! ccpa( 'options' )->get( 'custom_tools_page' ) ) {
			$this->renderSettingsHelperNotice();
		}

		if ( 'download_and_notify' === ccpa( 'options' )->get( 'export_action' ) || 'notify' === ccpa( 'options' )->get( 'export_action' ) ) {
			if ( ! ccpa( 'options' )->get( 'export_action_email' ) ) {
				$this->renderSettingsHelperNotice();
			}
		}

		if ( 'anonymize_and_notify' === ccpa( 'options' )->get( 'delete_action' ) ||
			'delete_and_notify' === ccpa( 'options' )->get( 'delete_action' ) ||
			'notify' === ccpa( 'options' )->get( 'delete_action' )
		) {
			if ( ! ccpa( 'options' )->get( 'delete_action_email' ) ) {
				$this->renderSettingsHelperNotice();
			}
		}
	}

	protected function renderSettingsHelperNotice() {
		ccpa( 'admin-notice' )->add( 'admin/notices/helper-settings' );
	}
}
