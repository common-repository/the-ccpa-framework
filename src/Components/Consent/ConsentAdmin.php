<?php

namespace Data443\CCPA\Components\Consent;

class ConsentAdmin {

	public function __construct() {
		add_filter( 'ccpa/admin/tabs', array( $this, 'registerAdminTab' ), 20 );
	}

	public function registerAdminTab( $tabs ) {
        global $ccpa;
        $tabs['consent'] = new AdminTabConsent($ccpa->Consent);
        return $tabs;
    }
}
