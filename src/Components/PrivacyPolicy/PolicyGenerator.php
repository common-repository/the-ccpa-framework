<?php

namespace Data443\CCPA\Components\PrivacyPolicy;

class PolicyGenerator {

	public function generate() {
		return ccpa( 'view' )->render(
			'policy/policy',
			$this->getData()
		);
	}

	public function getData() {
		$location = ccpa( 'options' )->get( 'company_location' );
		$date     = date( get_option( 'date_format' ) );

		return array(
			'companyName'                => ccpa( 'options' )->get( 'company_name' ),
			'companyLocation'            => $location,
			'contactEmail'               => ccpa( 'options' )->get( 'contact_email' ) ?
				ccpa( 'options' )->get( 'contact_email' ) :
				get_option( 'admin_email' ),

			'hasRepresentative'          => ccpa( 'helpers' )->countryNeedsRepresentative( $location ),
			'representativeContactName'  => ccpa( 'options' )->get( 'representative_contact_name' ),
			'representativeContactEmail' => ccpa( 'options' )->get( 'representative_contact_email' ),
			'representativeContactPhone' => ccpa( 'options' )->get( 'representative_contact_phone' ),

			'dpaWebsite'                 => ccpa( 'options' )->get( 'dpa_website' ),
			'dpaEmail'                   => ccpa( 'options' )->get( 'dpa_email' ),
			'dpaPhone'                   => ccpa( 'options' )->get( 'dpa_phone' ),

			'hasDpo'                     => ccpa( 'options' )->get( 'has_dpo' ),
			'dpoName'                    => ccpa( 'options' )->get( 'dpo_name' ),
			'dpoEmail'                   => ccpa( 'options' )->get( 'dpo_email' ),

			'hasTerms'                   => ccpa( 'options' )->get( 'terms_page' ),

			'date'                       => $date,
		);
	}
}
