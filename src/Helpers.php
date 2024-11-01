<?php

namespace Data443\CCPA;

/**
 * General helper functions
 *
 * Class Helpers
 *
 * @package Data443\CCPA
 */
class Helpers {

	public function supportUrl( $url = '' ) {
		return ccpa( 'config' )->get( 'help.url' ) . $url;
	}

	/**
	 * Get an associative array of EU countries
	 *
	 * @return array
	 */
	public function getEUCountryList() {
		return array(
			'AT' => _x( 'Austria', '(Admin)', 'ccpa-framework' ),
			'BE' => _x( 'Belgium', '(Admin)', 'ccpa-framework' ),
			'BG' => _x( 'Bulgaria', '(Admin)', 'ccpa-framework' ),
			'HR' => _x( 'Croatia', '(Admin)', 'ccpa-framework' ),
			'CY' => _x( 'Cyprus', '(Admin)', 'ccpa-framework' ),
			'CZ' => _x( 'Czech Republic', '(Admin)', 'ccpa-framework' ),
			'DK' => _x( 'Denmark', '(Admin)', 'ccpa-framework' ),
			'EE' => _x( 'Estonia', '(Admin)', 'ccpa-framework' ),
			'FI' => _x( 'Finland', '(Admin)', 'ccpa-framework' ),
			'FR' => _x( 'France', '(Admin)', 'ccpa-framework' ),
			'DE' => _x( 'Germany', '(Admin)', 'ccpa-framework' ),
			'GR' => _x( 'Greece', '(Admin)', 'ccpa-framework' ),
			'HU' => _x( 'Hungary', '(Admin)', 'ccpa-framework' ),
			'IE' => _x( 'Ireland', '(Admin)', 'ccpa-framework' ),
			'IT' => _x( 'Italy', '(Admin)', 'ccpa-framework' ),
			'LV' => _x( 'Latvia', '(Admin)', 'ccpa-framework' ),
			'LT' => _x( 'Lithuania', '(Admin)', 'ccpa-framework' ),
			'LU' => _x( 'Luxembourg', '(Admin)', 'ccpa-framework' ),
			'MT' => _x( 'Malta', '(Admin)', 'ccpa-framework' ),
			'NL' => _x( 'Netherlands', '(Admin)', 'ccpa-framework' ),
			'PL' => _x( 'Poland', '(Admin)', 'ccpa-framework' ),
			'PT' => _x( 'Portugal', '(Admin)', 'ccpa-framework' ),
			'RO' => _x( 'Romania', '(Admin)', 'ccpa-framework' ),
			'SK' => _x( 'Slovakia', '(Admin)', 'ccpa-framework' ),
			'SI' => _x( 'Slovenia', '(Admin)', 'ccpa-framework' ),
			'ES' => _x( 'Spain', '(Admin)', 'ccpa-framework' ),
			'SE' => _x( 'Sweden', '(Admin)', 'ccpa-framework' ),
			'UK' => _x( 'United Kingdom', '(Admin)', 'ccpa-framework' ),
			// All country list
			'AF' => _x( 'Afghanistan ', '(Admin)', 'ccpa-framework' ),
			'AX' => _x( 'Åland Islands', '(Admin)', 'ccpa-framework' ),
			'AL' => _x( 'Albania', '(Admin)', 'ccpa-framework' ),
			'DZ' => _x( 'Algeria', '(Admin)', 'ccpa-framework' ),
			'AS' => _x( 'American Samoa  ', '(Admin)', 'ccpa-framework' ),
			'AD' => _x( 'Andorra', '(Admin)', 'ccpa-framework' ),
			'AO' => _x( 'Angola', '(Admin)', 'ccpa-framework' ),
			'AI' => _x( 'Anguilla', '(Admin)', 'ccpa-framework' ),
			'AQ' => _x( 'Antarctica', '(Admin)', 'ccpa-framework' ),
			'AG' => _x( 'Antigua and Barbuda', '(Admin)', 'ccpa-framework' ),
			'AR' => _x( 'Argentina', '(Admin)', 'ccpa-framework' ),
			'AM' => _x( 'Armenia', '(Admin)', 'ccpa-framework' ),
			'AW' => _x( 'Aruba', '(Admin)', 'ccpa-framework' ),
			'AU' => _x( 'Australia', '(Admin)', 'ccpa-framework' ),
			'AZ' => _x( 'Azerbaijan', '(Admin)', 'ccpa-framework' ),
			'BH' => _x( 'Bahrain', '(Admin)', 'ccpa-framework' ),
			'BS' => _x( 'Bahamas', '(Admin)', 'ccpa-framework' ),
			'BD' => _x( 'Bangladesh', '(Admin)', 'ccpa-framework' ),
			'BB' => _x( 'Barbados', '(Admin)', 'ccpa-framework' ),
			'BY' => _x( 'Belarus', '(Admin)', 'ccpa-framework' ),
			'BZ' => _x( 'Belize', '(Admin)', 'ccpa-framework' ),
			'BJ' => _x( 'Benin', '(Admin)', 'ccpa-framework' ),
			'BM' => _x( 'Bermuda', '(Admin)', 'ccpa-framework' ),
			'BT' => _x( 'Bhutan', '(Admin)', 'ccpa-framework' ),
			'BO' => _x( 'Bolivia, Plurinational State of', '(Admin)', 'ccpa-framework' ),
			'BQ' => _x( 'Bonaire, Sint Eustatius and Saba', '(Admin)', 'ccpa-framework' ),
			'BA' => _x( 'Bosnia and Herzegovina', '(Admin)', 'ccpa-framework' ),
			'BW' => _x( 'Botswana', '(Admin)', 'ccpa-framework' ),
			'BV' => _x( 'Bouvet Island', '(Admin)', 'ccpa-framework' ),
			'BR' => _x( 'Brazil', '(Admin)', 'ccpa-framework' ),
			'IO' => _x( 'British Indian Ocean Territory', '(Admin)', 'ccpa-framework' ),
			'BN' => _x( 'Brunei Darussalam', '(Admin)', 'ccpa-framework' ),
			'BF' => _x( 'Burkina Faso', '(Admin)', 'ccpa-framework' ),
			'BI' => _x( 'Burundi', '(Admin)', 'ccpa-framework' ),
			'KH' => _x( 'Cambodia', '(Admin)', 'ccpa-framework' ),
			'CM' => _x( 'Cameroon', '(Admin)', 'ccpa-framework' ),
			'CA' => _x( 'Canada', '(Admin)', 'ccpa-framework' ),
			'CV' => _x( 'Cape Verde', '(Admin)', 'ccpa-framework' ),
			'KY' => _x( 'Cayman Islands', '(Admin)', 'ccpa-framework' ),
			'CF' => _x( 'Central African Republic', '(Admin)', 'ccpa-framework' ),
			'TD' => _x( 'Chad', '(Admin)', 'ccpa-framework' ),
			'CL' => _x( 'Chile', '(Admin)', 'ccpa-framework' ),
			'CN' => _x( 'China', '(Admin)', 'ccpa-framework' ),
			'CX' => _x( 'Christmas Island', '(Admin)', 'ccpa-framework' ),
			'CC' => _x( 'Cocos (Keeling) Islands', '(Admin)', 'ccpa-framework' ),
			'CO' => _x( 'Colombia', '(Admin)', 'ccpa-framework' ),
			'KM' => _x( 'Comoros', '(Admin)', 'ccpa-framework' ),
			'CG' => _x( 'Congo', '(Admin)', 'ccpa-framework' ),
			'CD' => _x( 'Congo, the Democratic Republic of the', '(Admin)', 'ccpa-framework' ),
			'CK' => _x( 'Cook Islands', '(Admin)', 'ccpa-framework' ),
			'CR' => _x( 'Costa Rica', '(Admin)', 'ccpa-framework' ),
			'CI' => _x( 'Côte dIvoire', '(Admin)', 'ccpa-framework' ),
			'CU' => _x( 'Cuba', '(Admin)', 'ccpa-framework' ),
			'CW' => _x( 'Curaçao', '(Admin)', 'ccpa-framework' ),
			'DJ' => _x( 'Djibouti', '(Admin)', 'ccpa-framework' ),
			'DM' => _x( 'Dominica', '(Admin)', 'ccpa-framework' ),
			'DO' => _x( 'Dominican Republic', '(Admin)', 'ccpa-framework' ),
			'EC' => _x( 'Ecuador', '(Admin)', 'ccpa-framework' ),
			'EG' => _x( 'Egypt', '(Admin)', 'ccpa-framework' ),
			'SV' => _x( 'El Salvador', '(Admin)', 'ccpa-framework' ),
			'GQ' => _x( 'Equatorial Guinea', '(Admin)', 'ccpa-framework' ),
			'ER' => _x( 'Eritrea', '(Admin)', 'ccpa-framework' ),
			'ET' => _x( 'Ethiopia', '(Admin)', 'ccpa-framework' ),
			'FK' => _x( 'Falkland Islands (Malvinas)', '(Admin)', 'ccpa-framework' ),
			'FO' => _x( 'Faroe Islands', '(Admin)', 'ccpa-framework' ),
			'FJ' => _x( 'Fiji', '(Admin)', 'ccpa-framework' ),
			'GF' => _x( 'French Guiana', '(Admin)', 'ccpa-framework' ),
			'PF' => _x( 'French Polynesia', '(Admin)', 'ccpa-framework' ),
			'TF' => _x( 'French Southern Territories', '(Admin)', 'ccpa-framework' ),
			'GA' => _x( 'Gabon', '(Admin)', 'ccpa-framework' ),
			'GM' => _x( 'Gambia', '(Admin)', 'ccpa-framework' ),
			'GE' => _x( 'Georgia', '(Admin)', 'ccpa-framework' ),
			'GE' => _x( 'Georgia ', '(Admin)', 'ccpa-framework' ),
			'GH' => _x( 'Ghana', '(Admin)', 'ccpa-framework' ),
			'GI' => _x( 'Gibraltar', '(Admin)', 'ccpa-framework' ),
			'GL' => _x( 'Greenland', '(Admin)', 'ccpa-framework' ),
			'GD' => _x( 'Grenada ', '(Admin)', 'ccpa-framework' ),
			'GP' => _x( 'Guadeloupe  ', '(Admin)', 'ccpa-framework' ),
			'GU' => _x( 'Guam', '(Admin)', 'ccpa-framework' ),
			'GT' => _x( 'Guatemala', '(Admin)', 'ccpa-framework' ),
			'GG' => _x( 'Guernsey', '(Admin)', 'ccpa-framework' ),
			'GN' => _x( 'Guinea  ', '(Admin)', 'ccpa-framework' ),
			'GW' => _x( 'Guinea-Bissau   ', '(Admin)', 'ccpa-framework' ),
			'GY' => _x( 'Guyana  ', '(Admin)', 'ccpa-framework' ),
			'HT' => _x( 'Haiti   ', '(Admin)', 'ccpa-framework' ),
			'HM' => _x( 'Heard Island and McDonald Islands   ', '(Admin)', 'ccpa-framework' ),
			'VA' => _x( 'Holy See (Vatican City State)   ', '(Admin)', 'ccpa-framework' ),
			'HN' => _x( 'Honduras    ', '(Admin)', 'ccpa-framework' ),
			'HK' => _x( 'Hong Kong   ', '(Admin)', 'ccpa-framework' ),
			'IN' => _x( 'India   ', '(Admin)', 'ccpa-framework' ),
			'ID' => _x( 'Indonesia   ', '(Admin)', 'ccpa-framework' ),
			'IR' => _x( 'Iran, Islamic Republic of   ', '(Admin)', 'ccpa-framework' ),
			'IQ' => _x( 'Iraq    ', '(Admin)', 'ccpa-framework' ),
			'IM' => _x( 'Isle of Man ', '(Admin)', 'ccpa-framework' ),
			'IL' => _x( 'Israel  ', '(Admin)', 'ccpa-framework' ),
			'JM' => _x( 'Jamaica ', '(Admin)', 'ccpa-framework' ),
			'JP' => _x( 'Japan   ', '(Admin)', 'ccpa-framework' ),
			'JE' => _x( 'Jersey  ', '(Admin)', 'ccpa-framework' ),
			'JO' => _x( 'Jordan  ', '(Admin)', 'ccpa-framework' ),
			'KZ' => _x( 'Kazakhstan  ', '(Admin)', 'ccpa-framework' ),
			'KE' => _x( 'Kenya   ', '(Admin)', 'ccpa-framework' ),
			'KI' => _x( 'Kiribati    ', '(Admin)', 'ccpa-framework' ),
			'KP' => _x( 'Korea, Democratic Peoples Republic of   ', '(Admin)', 'ccpa-framework' ),
			'KR' => _x( 'Korea, Republic of  ', '(Admin)', 'ccpa-framework' ),
			'KW' => _x( 'Kuwait  ', '(Admin)', 'ccpa-framework' ),
			'KG' => _x( 'Kyrgyzstan  ', '(Admin)', 'ccpa-framework' ),
			'LA' => _x( 'Lao Peoples Democratic Republic ', '(Admin)', 'ccpa-framework' ),
			'LB' => _x( 'Lebanon ', '(Admin)', 'ccpa-framework' ),
			'LS' => _x( 'Lesotho ', '(Admin)', 'ccpa-framework' ),
			'LR' => _x( 'Liberia ', '(Admin)', 'ccpa-framework' ),
			'LY' => _x( 'Libya   ', '(Admin)', 'ccpa-framework' ),
			'MO' => _x( 'Macao   ', '(Admin)', 'ccpa-framework' ),
			'MK' => _x( 'Macedonia, the Former Yugoslav Republic of  ', '(Admin)', 'ccpa-framework' ),
			'MG' => _x( 'Madagascar  ', '(Admin)', 'ccpa-framework' ),
			'MW' => _x( 'Malawi  ', '(Admin)', 'ccpa-framework' ),
			'MY' => _x( 'Malaysia    ', '(Admin)', 'ccpa-framework' ),
			'MV' => _x( 'Maldives    ', '(Admin)', 'ccpa-framework' ),
			'ML' => _x( 'Mali    ', '(Admin)', 'ccpa-framework' ),
			'MH' => _x( 'Marshall Islands    ', '(Admin)', 'ccpa-framework' ),
			'MQ' => _x( 'Martinique  ', '(Admin)', 'ccpa-framework' ),
			'MR' => _x( 'Mauritania  ', '(Admin)', 'ccpa-framework' ),
			'MU' => _x( 'Mauritius   ', '(Admin)', 'ccpa-framework' ),
			'YT' => _x( 'Mayotte ', '(Admin)', 'ccpa-framework' ),
			'MX' => _x( 'Mexico  ', '(Admin)', 'ccpa-framework' ),
			'FM' => _x( 'Micronesia, Federated States of ', '(Admin)', 'ccpa-framework' ),
			'MD' => _x( 'Moldova, Republic of    ', '(Admin)', 'ccpa-framework' ),
			'MC' => _x( 'Monaco  ', '(Admin)', 'ccpa-framework' ),
			'MN' => _x( 'Mongolia    ', '(Admin)', 'ccpa-framework' ),
			'ME' => _x( 'Montenegro  ', '(Admin)', 'ccpa-framework' ),
			'MS' => _x( 'Montserrat  ', '(Admin)', 'ccpa-framework' ),
			'MA' => _x( 'Morocco ', '(Admin)', 'ccpa-framework' ),
			'MZ' => _x( 'Mozambique  ', '(Admin)', 'ccpa-framework' ),
			'MM' => _x( 'Myanmar ', '(Admin)', 'ccpa-framework' ),
			'NA' => _x( 'Namibia ', '(Admin)', 'ccpa-framework' ),
			'NR' => _x( 'Nauru   ', '(Admin)', 'ccpa-framework' ),
			'NP' => _x( 'Nepal   ', '(Admin)', 'ccpa-framework' ),
			'NC' => _x( 'New Caledonia   ', '(Admin)', 'ccpa-framework' ),
			'NZ' => _x( 'New Zealand ', '(Admin)', 'ccpa-framework' ),
			'NI' => _x( 'Nicaragua   ', '(Admin)', 'ccpa-framework' ),
			'NE' => _x( 'Niger   ', '(Admin)', 'ccpa-framework' ),
			'NG' => _x( 'Nigeria ', '(Admin)', 'ccpa-framework' ),
			'NU' => _x( 'Niue    ', '(Admin)', 'ccpa-framework' ),
			'NF' => _x( 'Norfolk Island  ', '(Admin)', 'ccpa-framework' ),
			'MP' => _x( 'Northern Mariana Islands    ', '(Admin)', 'ccpa-framework' ),
			'OM' => _x( 'Oman    ', '(Admin)', 'ccpa-framework' ),
			'PK' => _x( 'Pakistan    ', '(Admin)', 'ccpa-framework' ),
			'PW' => _x( 'Palau   ', '(Admin)', 'ccpa-framework' ),
			'PS' => _x( 'Palestine, State of ', '(Admin)', 'ccpa-framework' ),
			'PA' => _x( 'Panama  ', '(Admin)', 'ccpa-framework' ),
			'PG' => _x( 'Papua New Guinea    ', '(Admin)', 'ccpa-framework' ),
			'PY' => _x( 'Paraguay    ', '(Admin)', 'ccpa-framework' ),
			'PE' => _x( 'Peru    ', '(Admin)', 'ccpa-framework' ),
			'PH' => _x( 'Philippines ', '(Admin)', 'ccpa-framework' ),
			'PN' => _x( 'Pitcairn    ', '(Admin)', 'ccpa-framework' ),
			'PR' => _x( 'Puerto Rico ', '(Admin)', 'ccpa-framework' ),
			'QA' => _x( 'Qatar   ', '(Admin)', 'ccpa-framework' ),
			'RE' => _x( 'Réunion ', '(Admin)', 'ccpa-framework' ),
			'RU' => _x( 'Russian Federation  ', '(Admin)', 'ccpa-framework' ),
			'RW' => _x( 'Rwanda  ', '(Admin)', 'ccpa-framework' ),
			'BL' => _x( 'Saint Barthélemy    ', '(Admin)', 'ccpa-framework' ),
			'SH' => _x( 'Saint Helena, Ascension and Tristan da Cunha    ', '(Admin)', 'ccpa-framework' ),
			'KN' => _x( 'Saint Kitts and Nevis   ', '(Admin)', 'ccpa-framework' ),
			'LC' => _x( 'Saint Lucia ', '(Admin)', 'ccpa-framework' ),
			'MF' => _x( 'Saint Martin (French part)  ', '(Admin)', 'ccpa-framework' ),
			'PM' => _x( 'Saint Pierre and Miquelon   ', '(Admin)', 'ccpa-framework' ),
			'VC' => _x( 'Saint Vincent and the Grenadines    ', '(Admin)', 'ccpa-framework' ),
			'WS' => _x( 'Samoa   ', '(Admin)', 'ccpa-framework' ),
			'SM' => _x( 'San Marino  ', '(Admin)', 'ccpa-framework' ),
			'ST' => _x( 'Sao Tome and Principe   ', '(Admin)', 'ccpa-framework' ),
			'SA' => _x( 'Saudi Arabia    ', '(Admin)', 'ccpa-framework' ),
			'SN' => _x( 'Senegal ', '(Admin)', 'ccpa-framework' ),
			'RS' => _x( 'Serbia  ', '(Admin)', 'ccpa-framework' ),
			'SC' => _x( 'Seychelles  ', '(Admin)', 'ccpa-framework' ),
			'SL' => _x( 'Sierra Leone    ', '(Admin)', 'ccpa-framework' ),
			'SG' => _x( 'Singapore   ', '(Admin)', 'ccpa-framework' ),
			'SX' => _x( 'Sint Maarten (Dutch part)   ', '(Admin)', 'ccpa-framework' ),
			'SB' => _x( 'Solomon Islands ', '(Admin)', 'ccpa-framework' ),
			'SO' => _x( 'Somalia ', '(Admin)', 'ccpa-framework' ),
			'ZA' => _x( 'South Africa    ', '(Admin)', 'ccpa-framework' ),
			'GS' => _x( 'South Georgia and the South Sandwich Islands    ', '(Admin)', 'ccpa-framework' ),
			'SS' => _x( 'South Sudan ', '(Admin)', 'ccpa-framework' ),
			'LK' => _x( 'Sri Lanka   ', '(Admin)', 'ccpa-framework' ),
			'SD' => _x( 'Sudan   ', '(Admin)', 'ccpa-framework' ),
			'SR' => _x( 'Suriname    ', '(Admin)', 'ccpa-framework' ),
			'SJ' => _x( 'Svalbard and Jan Mayen  ', '(Admin)', 'ccpa-framework' ),
			'SZ' => _x( 'Swaziland   ', '(Admin)', 'ccpa-framework' ),
			'SY' => _x( 'Syrian Arab Republic    ', '(Admin)', 'ccpa-framework' ),
			'TW' => _x( 'Taiwan   ', '(Admin)', 'ccpa-framework' ),
			'TJ' => _x( 'Tajikistan  ', '(Admin)', 'ccpa-framework' ),
			'TZ' => _x( 'Tanzania, United Republic of    ', '(Admin)', 'ccpa-framework' ),
			'TH' => _x( 'Thailand    ', '(Admin)', 'ccpa-framework' ),
			'TL' => _x( 'Timor-Leste ', '(Admin)', 'ccpa-framework' ),
			'TG' => _x( 'Togo    ', '(Admin)', 'ccpa-framework' ),
			'TK' => _x( 'Tokelau ', '(Admin)', 'ccpa-framework' ),
			'TO' => _x( 'Tonga   ', '(Admin)', 'ccpa-framework' ),
			'TT' => _x( 'Trinidad and Tobago ', '(Admin)', 'ccpa-framework' ),
			'TN' => _x( 'Tunisia ', '(Admin)', 'ccpa-framework' ),
			'TR' => _x( 'Turkey  ', '(Admin)', 'ccpa-framework' ),
			'TM' => _x( 'Turkmenistan    ', '(Admin)', 'ccpa-framework' ),
			'TC' => _x( 'Turks and Caicos Islands    ', '(Admin)', 'ccpa-framework' ),
			'TV' => _x( 'Tuvalu  ', '(Admin)', 'ccpa-framework' ),
			'UG' => _x( 'Uganda  ', '(Admin)', 'ccpa-framework' ),
			'UA' => _x( 'Ukraine ', '(Admin)', 'ccpa-framework' ),
			'AE' => _x( 'United Arab Emirates    ', '(Admin)', 'ccpa-framework' ),
			'UM' => _x( 'United States Minor Outlying Islands    ', '(Admin)', 'ccpa-framework' ),
			'UY' => _x( 'Uruguay ', '(Admin)', 'ccpa-framework' ),
			'UZ' => _x( 'Uzbekistan  ', '(Admin)', 'ccpa-framework' ),
			'VU' => _x( 'Vanuatu ', '(Admin)', 'ccpa-framework' ),
			'VE' => _x( 'Venezuela, Bolivarian Republic of   ', '(Admin)', 'ccpa-framework' ),
			'VN' => _x( 'Viet Nam    ', '(Admin)', 'ccpa-framework' ),
			'VG' => _x( 'Virgin Islands, British ', '(Admin)', 'ccpa-framework' ),
			'VI' => _x( 'Virgin Islands, U.S.    ', '(Admin)', 'ccpa-framework' ),
			'WF' => _x( 'Wallis and Futuna   ', '(Admin)', 'ccpa-framework' ),
			'EH' => _x( 'Western Sahara  ', '(Admin)', 'ccpa-framework' ),
			'YE' => _x( 'Yemen   ', '(Admin)', 'ccpa-framework' ),
			'ZM' => _x( 'Zambia  ', '(Admin)', 'ccpa-framework' ),
			'ZW' => _x( 'Zimbabwe    ', '(Admin)', 'ccpa-framework' ),
		);
	}

	/**
	 * Get a list of <option> values for the country selector
	 *
	 * @param null $current
	 *
	 * @return mixed
	 */
	public function getCountrySelectOptions( $current = null ) {
		$eu      = $this->getEUCountryList();
		$outside = array(
			'IS' => _x( 'Iceland', '(Admin)', 'ccpa-framework' ),
			'NO' => _x( 'Norway', '(Admin)', 'ccpa-framework' ),
			'LI' => _x( 'Liechtenstein', '(Admin)', 'ccpa-framework' ),
			'CH' => _x( 'Switzerland', '(Admin)', 'ccpa-framework' ),
			'US' => _x( 'United States', '(Admin)', 'ccpa-framework' ),
			// "other" => _x('Rest of the world', '(Admin)', 'ccpa-framework'),
		);

		return ccpa( 'view' )->render( 'global/country-options', compact( 'eu', 'outside', 'current' ) );
	}

	/**
	 * Check if a controller from the given country needs a representative in the EU
	 *
	 * @param $code
	 * @return bool
	 */
	public function countryNeedsRepresentative( $code ) {
		return in_array( $code, array( 'US', 'other' ) );
	}

	/**
	 * Get the data protection authority information for a given country
	 *
	 * @param null $countryCode
	 * @return array
	 */
	public function getDataProtectionAuthorityInfo( $countryCode = null ) {
		if ( ! $countryCode ) {
			$countryCode = ccpa( 'options' )->get( 'company_location' );
		}

		$dpaData = require ccpa( 'config' )->get( 'plugin.path' ) . 'assets/data-protection-authorities.php';

		if ( isset( $dpaData[ $countryCode ] ) ) {
			return $dpaData[ $countryCode ];
		}

		return array();
	}

	/**
	 * Get the info regarding all DPAs
	 */
	public function getDataProtectionAuthorities() {
		return require ccpa( 'config' )->get( 'plugin.path' ) . 'assets/data-protection-authorities.php';
	}

	public function getAdminUrl( $suffix = '' ) {
		return admin_url( 'tools.php?page=ccpa_privacy' . $suffix );
	}

	public function getDashboardDataPageUrl( $suffix = '' ) {
		return admin_url( 'users.php?page=ccpa-profile' . $suffix );
	}

	public function getPrivacyToolsPageUrl() {
		if(ccpa('options')->get('custom_tools_page')){
			$privacyToolsUrl = ccpa('options')->get('custom_tools_page');
			return $privacyToolsUrl;			
		}else{
			$toolsPageId = ccpa('options')->get('tools_page');
        	return $toolsPageId ? get_permalink($toolsPageId) : '';
		}
	}

	public function getPrivacyPolicyPageUrl() {
		$policyPageId  = ccpa( 'options' )->get( 'policy_page' );
		$policyPageurl = get_permalink( $policyPageId );
		add_filter( 'ccpa_custom_policy_link', 'ccpafPrivacyPolicyurl' );
		$policyPageurl = apply_filters( 'ccpa_custom_policy_link', $policyPageurl );
		return $policyPageurl ? $policyPageurl : '';
	}

	public function error() {
		wp_die(
			__( 'An error has occurred. Please contact the site administrator.', 'ccpa-framework' )
		);
	}

	public function docs( $url = '' ) {
		return 'https://www.data443.com/' . $url;
	}

	public function developerDocs()
    {
        return 'https://data443.atlassian.net/servicedesk/customer/portal/2/article/2082373645';
    }

	public function knowledgeBase()
    {
        return 'https://data443.atlassian.net/servicedesk/customer/portal/2/article/1914830898';
    }

	public function controllingPersonalData()
    {
        return 'https://data443.atlassian.net/servicedesk/customer/portal/2/article/2082439201';
    }

	public function legalGrounds()
    {
        return 'https://data443.atlassian.net/servicedesk/customer/portal/2/article/2079293576';
    }

	public function privacyTools()
	{
		return 'https://data443.atlassian.net/servicedesk/customer/portal/2/article/2082439201';
	}

	/**
	 * Wrapper around wp_mail() to filter the headers
	 * Example code for changing the sender email:
	 *
	 *  add_filter('ccpa/mail/headers', function($headers) {
	 *       $headers[] = 'From: Firstname Lastname <test@example.com>';
	 *      return $headers;
	 *  });
	 */
	public function mail( $to, $subject, $message, $headers = '', $attachments = array() ) {
		$ccpa_name_from  = get_option( 'ccpa_name_from' );
		$ccpa_email_from = get_option( 'ccpa_email_from' );
		if ( $ccpa_name_from == '' ) {
			$ccpa_name_from = 'Data443 CCPA';
		}
		if ( $ccpa_email_from == '' ) {
			$ccpa_email_from = get_option( 'admin_email' );
		}
		$headers   = apply_filters( 'ccpa/mail/headers', $headers );
		$headers[] = 'From: ' . $ccpa_name_from . ' <' . $ccpa_email_from . '>';

		wp_mail( $to, $subject, $message, $headers, $attachments );
	}
}
