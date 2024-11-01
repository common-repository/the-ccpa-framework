<input
	type="checkbox"
	id="ccpa_enable_popup"
	name="ccpa_enable_popup"
	value="1"
	<?php echo checked( $enabled, true ); ?>
/>
<label for="ccpa_enable_popup">
	<?php echo esc_html_x( 'Enable Cookie Acceptance Popup', '(Admin)', 'ccpa-framework' ); ?>
</label>
<p class="description">
	<?php echo _x( '<b>Note:</b> Need to add custom content <b>ccpa_cookie_consent</b> its accepted on popup accept button.', '(Admin)', 'ccpa-framework' ); ?>
</p>
