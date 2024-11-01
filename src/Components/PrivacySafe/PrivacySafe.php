<?php

namespace Data443\CCPA\Components\PrivacySafe;

class PrivacySafe {

	public function __construct() {
		 add_filter( 'ccpa/admin/tabs', array( $this, 'registerAdminTab' ), 80 );
	}

	public function registerAdminTab( $tabs ) {
		 $tabs['privacy-safe'] = new AdminTabPrivacySafe();
		return $tabs;
	}
}
