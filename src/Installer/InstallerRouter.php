<?php

namespace Data443\CCPA\Installer;

/**
 * Handle redirecting and routing the installer steps
 *
 * Class InstallerRouter
 *
 * @package Data443\CCPA\Installer
 */
class InstallerRouter {

	/**
	 * Set up the router
	 *
	 * @param array $steps
	 */
	public function __construct( array $steps ) {
		$this->steps = $steps;

		if ( isset( $_GET['ccpa-step'] ) ) {
			add_action( 'admin_init', array( $this, 'route' ) );
		}
	}

	/**
	 * Do the magic
	 */
	public function route() {
		/* @var $step InstallerStepInterface */
		$step = $this->findStep( $_GET['ccpa-step'] );

		if ( ! $step ) {
            return;
		}

		if ( 'POST' === $_SERVER['REQUEST_METHOD'] && isset( $_POST['ccpa-installer'] ) ) {

			// Handle the previous step button
			if ( 'previous' === esc_html( $_POST['ccpa-installer'] ) ) {
				$this->setCurrentStep( $this->getPreviousStep( $step )->getSlug() );
				wp_safe_redirect( $this->getPreviousStep( $step )->getUrl(), 302 );
				exit;
			}

			// Handle form submission
			if ( 'next' === esc_html( $_POST['ccpa-installer'] ) ) {

				if ( ! $step->validateNonce() ) {
					wp_safe_redirect( $step->getUrl() . '&ccpa-error=nonce', 302 );
					exit;
				}

				// Handle successful validation
				if ( $step->validate() ) {
					$step->submit();
					$nextStep = $this->getNextStep( $step );
					if ( $nextStep ) {
						$this->setCurrentStep( $nextStep->getSlug() );
						wp_safe_redirect( $nextStep->getUrl(), 302 );
						exit;
					} else {
						// If no next step then we're done
						wp_safe_redirect( admin_url(), 302 );
						exit;
					}
				}

				// Handle form submission with failed validation
				wp_safe_redirect( $step->getUrl() . '&ccpa-error=' . $step->getErrors(), 302 );
				exit;
			}

			trigger_error( 'Installer action not defined!', E_USER_NOTICE );

		} else {

			// Run wizard page step
			if ( 'wizard' === $step->getType() ) {
				ob_start();
				$step->run();
				exit;
			}

			// Run regular admin page step
			$step->run();
		}
	}

	/**
	 * Find a step by slug
	 *
	 * @param $slug
	 * @return InstallerStepInterface|bool
	 */
	public function findStep( $slug ) {
		foreach ( $this->steps as $i => $step ) {
			if ( $slug === $step->getSlug() ) {
				return $step;
			}
		}

		return false;
	}

	/**
	 * Find the index of a step by its slug
	 *
	 * @param $slug
	 * @return bool|int|string
	 */
	protected function findStepIndex( $slug ) {
		foreach ( $this->steps as $i => $step ) {
			if ( $slug === $step->getSlug() ) {
				return $i;
			}
		}

		return false;
	}

	/**
	 * Get the URL of the previous step or false if it's the first step
	 *
	 * @param InstallerStepInterface $step
	 * @return InstallerStepInterface|bool
	 */
	public function getPreviousStep( InstallerStepInterface $step ) {
		$currentStepNumber = $this->findStepIndex( $step->getSlug() );

		if ( false === $currentStepNumber or 0 === $currentStepNumber ) {
			$prevStep = false;
		} else {
			$prevStep = $this->steps[ $currentStepNumber - 1 ];
		}

		return apply_filters( 'ccpa/installer/prev-step', $prevStep, $currentStepNumber );
	}

	/**
	 * Get the URL of the next step or admin dashboard if it's the last step
	 *
	 * @param InstallerStepInterface $step
	 * @return InstallerStepInterface|bool
	 */
	public function getNextStep( InstallerStepInterface $step ) {
		$currentStepNumber = $this->findStepIndex( $step->getSlug() );

		if ( false === $currentStepNumber or count( $this->steps ) === $currentStepNumber + 1 ) {
			$nextStep = false;
		} else {
			$nextStep = $this->steps[ $currentStepNumber + 1 ];
		}

		return apply_filters( 'ccpa/installer/next-step-url', $nextStep, $currentStepNumber );
	}

	/**
	 * Save the current step slug in the database to enable continue functionality
	 *
	 * @param $steps
	 */
	protected function setCurrentStep( $slug ) {
		ccpa( 'options' )->set( 'installer_step', $slug );
	}

	/**
	 * Check if we are currently on any of the installer steps
	 *
	 * @return array|bool
	 */
	public function isInstallerStep() {
		return isset( $_GET['ccpa-step'] ) && $this->findStep( $_GET['ccpa-step'] );
	}
}
