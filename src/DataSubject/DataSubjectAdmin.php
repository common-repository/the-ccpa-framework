<?php

namespace Data443\CCPA\DataSubject;

class DataSubjectAdmin {

	public function __construct() {
		add_filter( 'ccpa/admin/tabs', array( $this, 'registerTab' ), 30 );
	}

	public function registerTab( $tabs ) {
        global $ccpa;

        $tabs['data-subject'] = new AdminTabDataSubject($ccpa->DataSubject);

        return $tabs;
	}
}
