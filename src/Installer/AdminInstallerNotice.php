<?php

namespace Data443\CCPA\Installer;

use Data443\CCPA\Admin\AdminNotice;

class AdminInstallerNotice extends AdminNotice {

	public function render() {
		if ( ! $this->template ) {
            return;
		}

		echo ccpa( 'view' )->render( 'admin/notices/header-step' );
		echo ccpa( 'view' )->render( $this->template, $this->data );
		echo ccpa( 'view' )->render( 'admin/notices/footer-step' );
	}
}
