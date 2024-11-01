<?php

namespace Data443\CCPA;

/**
 * Handles locating templates from either the theme or plugin,
 * injecting and extracting data and rendering them.
 *
 * Class View
 *
 * @package Data443\CCPA
 */
class View {

	/**
	 * View constructor.
	 */
	public function __construct() {
		$this->dirs = $this->getTemplateDirectories();
	}

	/**
	 * Render a given template.
	 *
	 * @param       $template
	 * @param array    $data
	 * @param null     $templateDir
	 *
	 * @return string
	 */
	public function render( $template, $data = array(), $templateDir = null ) {
		if ( is_null( $templateDir ) ) {
			foreach ( $this->dirs as $dir ) {
				if ( file_exists( $dir . $template . '.php' ) ) {
					$templateDir = $dir;
					break;
				}
			}
		}

		extract( $data );
		ob_start();
		require $templateDir . $template . '.php';

		return ob_get_clean();
	}

	/**
	 * Get valid template directories and pass them through a filter
	 *
	 * @return array
	 */
	protected function getTemplateDirectories() {
		$directories = array_filter(
			array(
				get_stylesheet_directory() . '/ccpa-framework/',
				get_template_directory() . '/ccpa-framework/',
				ccpa( 'config' )->get( 'plugin.template_path' ),
			),
			'is_dir'
		);

		return array_unique( apply_filters( 'ccpa/views', $directories ) );
	}
}
