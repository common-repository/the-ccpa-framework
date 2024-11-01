<?php

namespace Data443\CCPA\Admin;

/**
 * Handle registering and rendering the CCPA admin page contents
 *
 * Class WordpressAdminPage
 *
 * @package Data443\CCPA\Admin
 */
class WordpressAdminPage {

	protected $slug = 'ccpa';

	protected $tabs = array();

	public function __construct() {
		$this->setup();
	}

	protected function setup() {
		// Register the tabs
		add_action( 'admin_init', array( $this, 'registerTabs' ) );

		// todo
		// if (ccpa('options')->get('plugin_disclaimer_accepted')) {
			// Initialize the active tab
			add_action( 'admin_init', array( $this, 'initActiveTab' ) );
		// }

		// todo
		// ccpa('admin-modal')->add('ccpa-test', 'admin/modals/test', ['title' => 'Test modal']);
	}

	/**
	 * Render the main CCPA options page
	 */
	public function renderPage() {
		$tabs               = $this->getNavigationData();
		$currentTabContents = $this->getActiveTab()->renderContents();
		$signature          = apply_filters( 'ccpa/admin/show_signature', true );
		echo ccpa( 'view' )->render( 'admin/settings-page', compact( 'tabs', 'currentTabContents', 'signature' ) );
	}

	/**
	 * Allow modules to add tabs
	 */
	public function registerTabs() {
		$this->tabs = apply_filters( 'ccpa/admin/tabs', array() );
	}

	/**
	 * Get the active tab or the first tab if none are active
	 *
	 * @return AdminTabInterface
	 */
	public function getActiveTab() {
		foreach ( $this->tabs as $tab ) {
			if ( isset( $_GET['ccpa-tab'] ) && $_GET['ccpa-tab'] === $tab->getSlug() ) {
				return $tab;
			}
		}

		return reset( $this->tabs );
	}

	/**
	 * Check if the given tab is active
	 *
	 * @param $slug
	 * @return bool
	 */
	public function isTabActive( $slug ) {
		$activeTab = $this->getActiveTab();
		if ( $activeTab->getSlug() === $slug ) {
			return true;
		}

		// Hacky: if no tab set, the first tab is active
		if ( ! isset( $_GET['ccpa-tab'] ) ) {
			$firstTab = reset( $this->tabs );
			if ( $firstTab->getSlug() === $slug ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Initialize the active tab
	 */
	public function initActiveTab() {
		$activeTab = $this->getActiveTab();
		$activeTab->setup();
	}

	/**
	 * Get the tabbed navigation for CCPA options page
	 *
	 * @return array
	 */
	public function getNavigationData() {
		if ( ! count( $this->tabs ) ) {
			return array();
		}

		$navigation = array();

		foreach ( $this->tabs as $tab ) {
			/* @var $tab AdminTabInterface */
			$navigation[ $tab->getSlug() ] = array(
				'slug'   => $tab->getSlug(),
				'url'    => $this->getTabUrl( $tab->getSlug() ),
				'title'  => $tab->getTitle(),
				'active' => false,
			);

			if ( $this->isTabActive( $tab->getSlug() ) ) {
				$navigation[ $tab->getSlug() ]['active'] = true;
			}
		}

		return $navigation;
	}


	/**
	 * todo: move to helper?
	 *
	 * @param $slug
	 * @return string
	 */
	public function getTabUrl( $slug ) {
		return admin_url( 'tools.php?page=ccpa_privacy&ccpa-tab=' . $slug );
	}
}
