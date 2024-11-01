<?php

namespace Data443\CCPA\Installer\Steps;

use Data443\CCPA\Installer\InstallerStep;
use Data443\CCPA\Installer\InstallerStepInterface;

class ConfigurationPages extends InstallerStep implements InstallerStepInterface {

	protected $slug = 'configuration-pages';

	protected $type = 'wizard';

	protected $template = 'installer/steps/configuration-pages';

	protected $activeSteps = 1;

	protected function renderContent() {
		/* FRAM-116 define policy variables before they are referenced */
		$policyPage         = ccpa( 'options' )->get( 'policy_page' );
		$policyPageSelector = wp_dropdown_pages(
			array(
				'name'              => 'ccpa_policy_page',
				'show_option_none'  => _x( '&mdash; Create a new page &mdash;', '(Admin)', 'ccpa-framework' ),
				'option_none_value' => 'new',
				'selected'          => $policyPage ? $policyPage : 'new',
				'echo'              => false,
				'class'             => 'ccpa-select js-ccpa-select2',
			)
		);

		$privacyToolsPage         = ccpa( 'options' )->get( 'tools_page' );
		$privacyToolsPageSelector = wp_dropdown_pages(
			array(
				'name'              => 'ccpa_tools_page',
				'show_option_none'  => _x( '&mdash; Create a new page &mdash;', '(Admin)', 'ccpa-framework' ),
				'option_none_value' => 'new',
				'selected'          => $privacyToolsPage ? $privacyToolsPage : 'new',
				'echo'              => false,
				'class'             => 'ccpa-select js-ccpa-select2',
			)
		);

		echo ccpa( 'view' )->render(
			$this->template,
			compact(
				'policyPage',
				'policyPageSelector',
				'privacyToolsPage',
				'privacyToolsPageSelector'
			)
		);
	}

	public function submit() {
		if ( isset( $_POST['ccpa_create_tools_page'] ) && 'yes' === $_POST['ccpa_create_tools_page'] ) {
			$id = $this->createPrivacyToolsPage();
			ccpa( 'options' )->set( 'tools_page', $id );
		} else {
			ccpa( 'options' )->set( 'tools_page', $_POST['ccpa_tools_page'] );
		}
	}

	protected function createPrivacyToolsPage() {
		$id = wp_insert_post(
			array(
				'post_content' => '<!-- wp:shortcode -->[ccpa_privacy_tools]<!-- /wp:shortcode -->',
				'post_title'   => __( 'Privacy Tools', 'ccpa-framework' ),
				'post_type'    => 'page',
			)
		);

		return $id;
	}
}
