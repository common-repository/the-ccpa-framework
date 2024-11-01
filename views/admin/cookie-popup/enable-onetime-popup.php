<input
	type="checkbox"
	id="ccpa_onetime_popup"
	name="ccpa_onetime_popup"
	value="1"
	<?php echo checked( $enabled, true ); ?>
/>
<label for="ccpa_onetime_popup">
	<?php echo esc_html_x( 'Enable One Time Cookie Acceptance Popup', '(Admin)', 'ccpa-framework' ); ?>
</label>
