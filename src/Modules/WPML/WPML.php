<?php

namespace Data443\CCPA\Modules\WPML;

class WPML {

	protected $prefix = 'ccpa_';

	protected $translatableOptions = array(
		'tools_page',
		'policy_page',
		'terms_page',
		'consent_info',
	);

	public function __construct() {
		if ( ! class_exists( 'Sitepress' ) ) {
			return;
		}

		$this->setupOptionFilters();
	}

	protected function setupOptionFilters() {
		foreach ( $this->translatableOptions as $option ) {
			add_filter( "ccpa/options/get/{$option}", array( $this, 'getTranslatedOption' ) );

			$option = $this->prefix( $option );
			add_filter( "pre_update_option_{$option}", array( $this, 'setTranslatedOption' ), 10, 2 );
		}

		add_filter( 'ccpa/options/get/consent_types', array( $this, 'getConsentTypes' ) );
		add_filter( 'ccpa/options/set/consent_types', array( $this, 'saveConsentTypes' ) );
	}

	public function getTranslatedOption( $option ) {
		if ( ! defined( 'ICL_LANGUAGE_CODE' ) ) {
			if ( is_array( $option ) ) {
				return '';
			} else {
				return $option;
			}
		}

		if ( isset( $option[ (string) ICL_LANGUAGE_CODE ] ) ) {
			return $option[ (string) ICL_LANGUAGE_CODE ];
		} else {
			return '';
		}
	}

	public function setTranslatedOption( $newValue, $oldValue ) {
		if ( ! defined( 'ICL_LANGUAGE_CODE' ) ) {
			return $newValue;
		}

		if ( is_array( $oldValue ) ) {
			$value = $oldValue;
		} else {
			$value = array();
		}

		$value[ (string) ICL_LANGUAGE_CODE ] = $newValue;

		return $value;
	}

	public function getConsentTypes( $consentTypes ) {
		if ( ! defined( 'ICL_LANGUAGE_CODE' ) ) {
			return $consentTypes;
		}

		$code                 = (string) ICL_LANGUAGE_CODE;
		$filteredConsentTypes = array();

		if ( isset( $consentTypes ) && ! empty( $consentTypes ) && count( $consentTypes ) ) {
			foreach ( $consentTypes as $consentType ) {

				if ( isset( $consentType['slug'] ) && ( 'privacy-policy' === $consentType['slug'] or 'terms-condition' === $consentType['slug'] ) ) {
					$filteredConsentTypes[] = array(
						'slug'        => isset( $consentType['slug'] ) ? $consentType['slug'] : '',
						'visible'     => isset( $consentType['visible'] ) ? $consentType['visible'] : 0,
						'title'       => isset( $consentType['title'] ) ? $consentType['title'] : '',
						'description' => isset( $consentType['description'] ) ? $consentType['description'] : '',
					);
				} else {
					$filteredConsentTypes[] = array(
						'slug'        => isset( $consentType['slug'] ) ? $consentType['slug'] : '',
						'visible'     => isset( $consentType['visible'] ) ? $consentType['visible'] : 0,
						'title'       => isset( $consentType[ "{$code}_title" ] ) ? $consentType[ "{$code}_title" ] : '',
						'description' => isset( $consentType[ "{$code}_description" ] ) ? $consentType[ "{$code}_description" ] : '',
					);
				}
			}
		}

		return $filteredConsentTypes;
	}

	public function saveConsentTypes( $newConsentTypes ) {
		if ( ! defined( 'ICL_LANGUAGE_CODE' ) ) {
			return $newConsentTypes;
		}

		$code                   = (string) ICL_LANGUAGE_CODE;
		$translatedConsentTypes = array();
		$currentConsentTypes    = ccpa( 'options' )->get( 'consent_types', null, false );

		if ( count( $newConsentTypes ) ) {
			foreach ( $newConsentTypes as &$newConsentType ) {

				// Match an existing consent type with the new one
				$slug                = $newConsentType['slug'];
				$existingConsentType = current(
					array_filter(
						$currentConsentTypes,
						function( $value ) use ( $slug ) {
							return $value['slug'] === $slug;
						}
					)
				);

				if ( $existingConsentType ) {
					// We have a matching existing consent - update translations, keep the rest
					$existingConsentType[ "{$code}_title" ]       = sanitize_text_field( $newConsentType['title'] );
					$existingConsentType[ "{$code}_description" ] = sanitize_text_field( $newConsentType['description'] );

					$translatedConsentTypes[] = $existingConsentType;
				} else {
					// We do not have a matching existin consent - just adjust the keys for language
					$newConsentType[ "{$code}_title" ]       = sanitize_text_field( $newConsentType['title'] );
					$newConsentType[ "{$code}_description" ] = sanitize_text_field( $newConsentType['description'] );
					unset( $newConsentType['title'] );
					unset( $newConsentType['description'] );

					$translatedConsentTypes[] = $newConsentType;
				}
			}
		}

		return $translatedConsentTypes;
	}

	public function prefix( $name ) {
		if ( 0 === strpos( $name, $this->prefix ) ) {
			return $name;
		}

		return $this->prefix . $name;
	}
}
