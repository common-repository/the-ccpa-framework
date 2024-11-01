<?php

namespace Data443\CCPA\Admin;

class Modal {

	protected $id;

	protected $template;

	protected $data;

	/**
	 * AdminNotice constructor.
	 */
	public function __construct() {
		add_action( 'admin_footer', array( $this, 'render' ) );
	}

	public function add( $id, $template, $data = array() ) {
		$this->id         = $id;
		$this->template   = $template;
		$this->data       = $data;
		$this->data['id'] = $this->id;
	}

	public function render() {
		if ( ! $this->template ) {
            return;
		}

		echo ccpa( 'view' )->render( 'admin/modals/header', $this->data );
		echo ccpa( 'view' )->render( $this->template, $this->data );
		echo ccpa( 'view' )->render( 'admin/modals/footer', $this->data );
	}
}
