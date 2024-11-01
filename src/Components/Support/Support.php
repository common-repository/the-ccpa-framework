<?php

namespace Data443\CCPA\Components\Support;

class Support {

	public function __construct() {
		add_filter( 'ccpa/admin/tabs', array( $this, 'registerTab' ), 40 );
	}

	public function registerTab( $tabs ) {
		$tabs['support'] = new AdminTabSupport();

		return $tabs;
	}
}
