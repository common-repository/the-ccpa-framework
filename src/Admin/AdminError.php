<?php
namespace Data443\CCPA\Admin;

/**
 * AdmineError
 */
class AdminError extends AdminNotice {
	/**
	 * Render
	 *
	 * @return void
	 */
	public function render() {
		if ( ! $this->template ) {
            return;
		}
		echo esc_attr( ccpa( 'view' )->render( $this->template, $this->data ) );
	}
}
