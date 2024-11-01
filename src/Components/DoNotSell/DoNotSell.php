<?php

namespace Data443\CCPA\Components\DoNotSell;

class DoNotSell {

	public function __construct() {
		 add_filter( 'ccpa/admin/tabs', array( $this, 'registerAdminTab' ), 36 );
	}

	public function registerAdminTab( $tabs ) {
		 $tabs['do-not-sell'] = new AdminTabDoNotSell();
		return $tabs;
	}
}
