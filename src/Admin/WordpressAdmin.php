<?php

namespace Data443\CCPA\Admin;

/**
 * Handles general admin functionality
 *
 * Class WordpressAdmin
 *
 * @package Data443\CCPA\Admin
 */
class WordpressAdmin {

	public function __construct( WordpressAdminPage $adminPage ) {
		$this->adminPage = $adminPage;

		// Allow turning off helpers
		if ( apply_filters( 'ccpa/admin/helpers/enabled', true ) ) {
			new AdminHelper();
		}

		$this->setup();

	}

	/**
	 * Set up hooks
	 */
	protected function setup() {
		// Register the main CCPA options page
		add_action( 'admin_menu', array( $this, 'registerCCPAOptionsPage' ) );

		// Register General admin tab
		add_filter( 'ccpa/admin/tabs', array( $this, 'registerAdminTabGeneral' ), 0 );

		// Enqueue assets
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ) );

		// Register post states
		add_filter( 'display_post_states', array( $this, 'registerPostStates' ), 10, 2 );

		// Show help notice
		add_action( 'current_screen', array( $this, 'maybeShowHelpNotice' ), 999 );

				add_action( 'delete_user', array( $this, 'ccpaf_delete_userlogs' ) );
	}


	public function maybeShowHelpNotice() {
		if ( 'tools_page_ccpa_privacy' === get_current_screen()->base ) {
			// ccpa('admin-notice')->add('admin/notices/help');
		}
	}

	/**
	 * Register the CCPA options page in WP admin
	 */
	public function registerCCPAOptionsPage() {
		add_management_page(
			_x( 'Privacy & CCPA Settings', '(Admin)', 'ccpa-framework' ),
			_x( 'Data443 CCPA', '(Admin)', 'ccpa-framework' ),
			'manage_options',
			'ccpa_privacy',
			array( $this->adminPage, 'renderPage' )
		);
	}

	/**
	 * Register General admin tab
	 *
	 * @param $tabs
	 * @return array
	 */
    public function registerAdminTabGeneral( $tabs ) {
        global $ccpa;
        $tabs['general'] = $ccpa->AdminTabGeneral;
        return $tabs;
    }

	/**
	 * Enqueue all admin scripts and styles
	 */
	public function enqueue() {
		/**
		 * General admin styles
		 */
		wp_enqueue_style(
			'ccpa-admin',
			ccpa( 'config' )->get( 'plugin.url' ) . 'assets/css/ccpa-admin.css'
		);

		$screen = get_current_screen();
		if ( $screen->base == 'tools_page_ccpa_privacy' ) {
			/**
			 * jQuery UI dialog for modals
			 */
			// wp_enqueue_style('wp-jquery-ui-dialog');
			wp_enqueue_script(
				'ccpa-admin',
				ccpa( 'config' )->get( 'plugin.url' ) . 'assets/js/ccpa-admin.js',
				array( 'jquery-ui-dialog' )
			);
			/**
			 * jQuery Repeater
			 */
			wp_enqueue_script(
				'jquery-repeater',
				ccpa( 'config' )->get( 'plugin.url' ) . 'assets/js/jquery.repeater.min.js',
				array( 'jquery' )
			);

			/**
			 * Select2
			 */

			wp_dequeue_script( 'select2css' );
			wp_dequeue_script( 'select2' );

			wp_enqueue_style(
				'select2css',
				ccpa( 'config' )->get( 'plugin.url' ) . 'assets/css/select2-4.0.5.css'
			);

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
			/**
			 * Color Picker
			 */
			// wp_enqueue_script( 'iris',ccpa('config')->get('plugin.url') .'assets/js/iris.min.js' );
			wp_enqueue_script( 'iris' );
			wp_enqueue_script( 'iris-init', ccpa( 'config' )->get( 'plugin.url' ) . 'assets/js/iris-init.js', array( 'iris' ), false, true );
		}
	}

	/**
	 * Add a new Post State for our super important system pages
	 */
	public function registerPostStates( $postStates, $post ) {
		if ( ccpa( 'options' )->get( 'policy_page' ) == $post->ID ) {
			$postStates['ccpa_policy_page'] = _x( 'Privacy Policy Page', '(Admin)', 'ccpa-framework' );
		}

		if ( ccpa( 'options' )->get( 'tools_page' ) == $post->ID ) {
			$postStates['ccpa_tools_page'] = _x( 'Privacy Tools Page', '(Admin)', 'ccpa-framework' );
		}

		return $postStates;
	}
	// Delete userlogs if user deleted from admin panel.
	public function ccpaf_delete_userlogs( $user_id ) {
		global $wpdb;

		$this->logtableName = $wpdb->prefix . 'ccpa_userlogs';

		return $wpdb->delete(
			$this->logtableName,
			array(
				'user_id' => $user_id,
			)
		);
	}

}
