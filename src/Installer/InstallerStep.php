<?php

namespace Data443\CCPA\Installer;

/**
 * Handle the plumbing of an installer step
 *
 * Class InstallerStep
 *
 * @package Data443\CCPA\Installer
 */
abstract class InstallerStep {

	/* @var string */
	protected $stepType;

	/* @var string */
	protected $slug;

	/* @var string */
	protected $type;

	/* @var string */
	protected $template;

	/* @var int */
	protected $activeSteps;

	/**
	 * Render a step for viewing
	 */
	public function run() {
		$this->enqueue();
		$this->renderHeader();
		$this->renderContent();
		$this->renderNonce();
		$this->renderFooter();
	}

	/**
	 * Validate the form submission
	 *
	 * @return bool
	 */
	public function validate() {
		return true;
	}

	/**
	 * Validate the nonce
	 *
	 * @return bool
	 */
	public function validateNonce() {
		return isset( $_POST['ccpa_nonce'] ) && wp_verify_nonce( $_POST['ccpa_nonce'], $this->slug );
	}

	/**
	 * Process the form submission
	 */
	public function submit() {

	}


	/**
	 * Display error notice or something
	 */
	public function getErrors() {
		return $this->errors;
	}

	/**
	 * Register WP's default assets and plugin installer assets
	 */
	protected function enqueue() {
		wp_enqueue_style( 'common' );
		wp_enqueue_style( 'buttons' );

		/**
		 * CCPA installer custom styles
		 */
		wp_enqueue_style(
			'ccpa-installer',
			ccpa( 'config' )->get( 'plugin.url' ) . 'assets/css/ccpa-installer.css'
		);

		wp_enqueue_style(
			'select2css',
			ccpa( 'config' )->get( 'plugin.url' ) . 'assets/css/select2-4.0.5.css'
		);

		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'jquery-ui-core' );
		wp_enqueue_script( 'jquery-ui-widget' );
		wp_enqueue_script( 'jquery-ui-mouse' );
		wp_enqueue_script( 'jquery-ui-sortable' );
		wp_enqueue_script( 'jquery-ui-tabs' );
		wp_enqueue_script(
			'select2',
			ccpa( 'config' )->get( 'plugin.url' ) . 'assets/js/select2-4.0.3.js',
			array( 'jquery' )
		);
		wp_enqueue_script(
			'conditional-show',
			ccpa( 'config' )->get( 'plugin.url' ) . 'assets/js/conditional-show.js',
			array( 'jquery' )
		);

		// global $wp_scripts;
		// $ui = $wp_scripts->query('jquery-ui-core');
		// wp_enqueue_style('jquery-ui-smoothness', "//ajax.googleapis.com/ajax/libs/jqueryui/{$ui->ver}/themes/smoothness/jquery-ui.min.css", false, null);

		wp_enqueue_script(
			'jquery-repeater',
			ccpa( 'config' )->get( 'plugin.url' ) . 'assets/js/jquery.repeater.min.js',
			array( 'jquery' )
		);

		/**
		 * Installer javascript
		 */
		wp_enqueue_script(
			'ccpa-installer',
			ccpa( 'config' )->get( 'plugin.url' ) . 'assets/js/ccpa-installer.js',
			array( 'jquery', 'select2' )
		);
	}

	/**
	 * Render the installer page header - html head, form, logo
	 */
	protected function renderHeader() {
		echo ccpa( 'view' )->render( 'installer/header', array( 'activeSteps' => $this->activeSteps ) );
	}

	/**
	 * Render the installer page content - should be overridden by child class
	 */
	protected function renderContent() {
		echo ccpa( 'view' )->render( $this->template );
	}

	/**
	 * Create and render the nonce based on the name of the current step
	 */
	protected function renderNonce() {
		$nonce = wp_create_nonce( $this->slug );
		echo ccpa( 'view' )->render( 'installer/nonce', compact( 'nonce' ) );
	}

	/**
	 * Render the footer - nav buttons and closing tags
	 */
	protected function renderFooter() {
		echo ccpa( 'view' )->render( 'installer/footer' );
	}

	/**
	 * @return string
	 */
	public function getUrl() {
		return ccpa( 'config' )->get( 'installer.wizardUrl' ) . $this->slug;
	}

	/**
	 * @return string
	 */
	public function getSlug() {
		if ( is_null( $this->slug ) ) {
            return;
		}

		return $this->slug;
	}

	/**
	 * @return string
	 */
	public function getType() {
		if ( is_null( $this->type ) ) {
			trigger_error( "CCPA: Type not defined for step {$this->slug}", E_USER_ERROR );
		}

		return $this->type;
	}
}
