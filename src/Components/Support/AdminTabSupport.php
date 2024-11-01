<?php

namespace Data443\CCPA\Components\Support;

use Data443\CCPA\Admin\AdminTab;

class AdminTabSupport extends AdminTab {

	protected $slug = 'support';

	public function __construct() {
		$this->title = _x( 'Support', '(Admin)', 'ccpa-framework' );
	}

	public function init() {
		$this->registerSettingSection(
			'ccpa-section-support',
			_x( 'Support', '(Admin)', 'ccpa-framework' ),
			array( $this, 'renderTab' )
		);
	}

	public function renderTab() {
		echo ccpa( 'view' )->render( 'admin/support/contents' );
	}

	public function renderSubmitButton() {
		// Intentionally left blank
	}
}
