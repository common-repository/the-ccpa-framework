<?php

namespace Data443\CCPA\Installer;

use Data443\CCPA\Admin\AdminTabGeneral;

/**
 * Handle all installation activities
 *
 * Class Installer
 *
 * @package Data443\CCPA\Installer
 */
class Installer {

	/* @var array */
	protected $defaultSteps = array(
		'Data443\CCPA\Installer\Steps\Welcome',
		'Data443\CCPA\Installer\Steps\Disclaimer',
		'Data443\CCPA\Installer\Steps\ConfigurationPages',
		'Data443\CCPA\Installer\Steps\ConfigurationSettings',
		'Data443\CCPA\Installer\Steps\PolicySettings',
		'Data443\CCPA\Installer\Steps\PolicyContents',
		'Data443\CCPA\Installer\Steps\Consent',
		'Data443\CCPA\Installer\Steps\Integrations',
		'Data443\CCPA\Installer\Steps\PrivacySafe',
		'Data443\CCPA\Installer\Steps\Finish',
	);

	/* @var array */
	protected $steps = array();

	/* @var InstallerWizard */
	protected $wizard;

	/* @var InstallerRouter */
	protected $router;

	/**
	 * Check if the installer is enabled and ensure the user has correct permissions to run it
	 */
	public function __construct( AdminTabGeneral $adminTab ) {
		if ( ! $this->isInstallerEnabled() ) {
			return;
		}

		if ( ! $this->userHasPermissions() ) {
			return;
		}

		$this->adminTab = $adminTab;

		$this->maybeDisplayDisclaimer();
		$this->setupHooks();

		if ( ! $this->isInstalled() ) {
			$this->setupSteps();
			$this->runInstaller();
		}

		if(!$this->isPrivcySafeNotified()) {
			$this->run_privacysafe_promo();
		}
	}

	/**
	 * Setup actions and admin tab components
	 */
	protected function setupHooks() {
		add_action( 'admin_init', array( $this, 'setupAdminGeneralTabButtons' ), 0 );

		add_action( 'ccpa/admin/action/accept_disclaimer', array( $this, 'acceptDisclaimer' ) );

		add_action( 'ccpa/admin/action/restart_wizard', array( $this, 'restartWizard' ) );

		add_action( 'ccpa/admin/action/auto_install', array( $this, 'autoInstall' ) );
		add_action( 'ccpa/admin/action/skip_install', array( $this, 'skipInstall' ) );
		add_action('ccpa/admin/action/skip_notice', [$this, 'skipNotice']);
	}

	protected function runInstaller() {
		$this->wizard = new InstallerWizard();
		$this->router = new InstallerRouter( $this->steps );

		// If we're currently on one of the installer steps, let the router handle it
		if ( $this->router->isInstallerStep() ) {
			return;
		}

		if ( $this->getCurrentStepSlug() ) {
			// If the current step is set, display continue notice
			$step = $this->router->findStep( $this->getCurrentStepSlug() );
			// If step doesn't exist, then it means the step slugs have changed. Do nothing.
			if ( ! $step ) {
				return;
			}
			$this->displayContinueNotice( $step->getUrl() );
		} else {
			// If the current step is not set, it means the installer hasn't been started yet
			$this->displayWelcomeNotice();
		}
	}

	/**
	 * If the admin has not accepted the disclaimer, render it
	 */
	public function maybeDisplayDisclaimer() {
		if ( ! ccpa( 'options' )->get( 'plugin_disclaimer_accepted' ) && ( isset( $_GET['page'] ) && 'ccpa_privacy' === $_GET['page'] ) ) {
			$acceptUrl = add_query_arg(
				array(
					'ccpa_action' => 'accept_disclaimer',
					'ccpa_nonce'  => wp_create_nonce( 'ccpa/admin/action/accept_disclaimer' ),
				)
			);
			ccpa( 'admin-notice' )->add( 'admin/notices/disclaimer', compact( 'acceptUrl' ) );
		}
	}

	/**
	 * Mark the disclaimer as accepted
	 */
	public function acceptDisclaimer() {
		ccpa( 'options' )->set( 'plugin_disclaimer_accepted', 'yes' );
		wp_safe_redirect( ccpa( 'helpers' )->getAdminUrl() );
		exit;
	}

	/**
	 * Display installer section in admin page
	 */
	public function setupAdminGeneralTabButtons() {
		/**
		 * Display wizard buttons
		 */
		$this->adminTab->registerSettingSection(
			'ccpa-section-wizard',
			_x( 'Setup Wizard', '(Admin)', 'ccpa-framework' ),
			array( $this, 'renderWizardButtons' )
		);
	}

	/**
	 * Render the installer section
	 */
	public function renderWizardButtons() {
		$restartUrl = add_query_arg(
			array(
				'ccpa_action' => 'restart_wizard',
				'ccpa_nonce'  => wp_create_nonce( 'ccpa/admin/action/restart_wizard' ),
			)
		);

		echo ccpa( 'view' )->render(
			'admin/wizard-buttons',
			compact( 'restartUrl' )
		);
	}

	/**
	 * Restart and redirect to first step
	 */
	public function restartWizard() {
		ccpa( 'options' )->delete( 'installer_step' );
		ccpa( 'options' )->delete( 'is_installed' );
		ccpa( 'options' )->delete( 'is_privacysafe_notified' );

		wp_safe_redirect( self_admin_url() );
		exit;
	}

	/**
	 * Allow plugins to modify the steps
	 */
	protected function setupSteps() {
		$steps = apply_filters( 'ccpa/installer/steps', $this->defaultSteps );

		foreach ( $steps as $index => $step ) {
			$this->steps[ $index ] = new $step();
		}
	}

	/**
	 * The installer can be disabled by filter.
	 * Check if it's enabled
	 *
	 * @return bool
	 */
	protected function isInstallerEnabled() {
		return apply_filters( 'ccpa/installer/enabled', true );
	}

	/**
	 * Check if the current user has correct permissions to run the installer
	 *
	 * @return bool
	 */
	protected function userHasPermissions() {
		return current_user_can( apply_filters( 'ccpa/installer/permissions', 'manage_options' ) );
	}

	/**
	 * Check if the installer is already ran
	 *
	 * @return bool
	 */
	protected function isInstalled() {
		return ccpa( 'options' )->get( 'is_installed' );
	}
	/**
     * Check if the Privacy Safe notice is already ran
     *
     * @return bool
     */
    protected function isPrivcySafeNotified()
    {
        return ccpa('options')->get('is_privacysafe_notified');
    }

	/**
	 * @return string
	 */
	public function getCurrentStepSlug() {
		return ccpa( 'options' )->get( 'installer_step' );
	}

	/**
	 * Render an admin notice that will display the welcome message
	 */
	protected function displayWelcomeNotice() {
		// Make sure we display the notice only to admins
		if ( ! current_user_can( apply_filters( 'ccpa/capability', 'manage_options' ) ) ) {
			return;
		}

		$installerUrl   = $this->steps[0]->getUrl();
		$autoInstallUrl = add_query_arg(
			array(
				'ccpa_action' => 'auto_install',
				'ccpa_nonce'  => wp_create_nonce( 'ccpa/admin/action/auto_install' ),
			)
		);
		$skipUrl        = add_query_arg(
			array(
				'ccpa_action' => 'skip_install',
				'ccpa_nonce'  => wp_create_nonce( 'ccpa/admin/action/skip_install' ),
			)
		);

		ccpa( 'admin-notice' )->add(
			'installer/welcome-notice',
			compact( 'installerUrl', 'autoInstallUrl', 'skipUrl' )
		);
	}
	/**
	 * 
	 * 
	 */
    public function run_privacysafe_promo() {
		$skipNoticeUrl = add_query_arg([
            'ccpa_action' => 'skip_notice',
            'ccpa_nonce'  => wp_create_nonce("ccpa/admin/action/skip_notice"),
        ]);
        // Make sure we display the notice only to admins
		ccpa('privacy-safe')->add(
            'installer/privacy-safe-notice',
            compact('skipNoticeUrl')
        );
    }

	/**
	 * Render an admin notice that will display the continue button
	 *
	 * @param $url
	 */
	protected function displayContinueNotice( $url ) {
		// Make sure we display the notice only to admins
		if ( ! current_user_can( apply_filters( 'ccpa/capability', 'manage_options' ) ) ) {
			return;
		}

		$skipUrl = add_query_arg(
			array(
				'ccpa_action' => 'skip_install',
				'ccpa_nonce'  => wp_create_nonce( 'ccpa/admin/action/skip_install' ),
			)
		);

		ccpa( 'admin-notice' )->add(
			'installer/continue-notice',
			array(
				'buttonUrl' => $url,
				'skipUrl'   => $skipUrl,
			)
		);
	}

	/**
	 * Automatically create pages for Privacy Policy and set the corresponding options
	 */
	public function autoInstall() {
		$policyPageId = wp_insert_post(
			array(
				'post_title'  => __( 'Privacy Policy', 'ccpa-framework' ),
				'post_type'   => 'page',
				'post_status' => 'publish',
			)
		);

		ccpa( 'options' )->set( 'policy_page', $policyPageId );

		$toolsPageId = wp_insert_post(
			array(
				'post_content' => '<!-- wp:shortcode -->[ccpa_privacy_tools]<!-- /wp:shortcode -->',
				'post_title'   => __( 'Privacy Tools', 'ccpa-framework' ),
				'post_type'    => 'page',
				'post_status'  => 'publish',
			)
		);
		ccpa( 'options' )->set( 'tools_page', $toolsPageId );

		// Woocommerce compatibility - automatically add their terms page
		if ( get_option( 'woocommerce_terms_page_id' ) ) {
			ccpa( 'options' )->set( 'terms_page', get_option( 'woocommerce_terms_page_id' ) );
		}

		ccpa( 'options' )->set( 'is_installed', 'yes' );
		wp_safe_redirect( ccpa( 'helpers' )->getAdminUrl( '&ccpa-tab=privacy-policy&ccpa-notice=autoinstall' ) );
		exit;
	}

	/**
	 * Do nothing, but mark the installer as completed
	 */
	public function skipInstall() {
		ccpa( 'options' )->set( 'is_installed', 'yes' );
		wp_safe_redirect( ccpa( 'helpers' )->getAdminUrl() );
		exit;
	}

	public function skipNotice()
    {
        ccpa('options')->set('is_privacysafe_notified', 'yes');
        wp_safe_redirect(ccpa('helpers')->getAdminUrl()); 
        exit;
    }
}
