<input
	type="checkbox"
	id="ccpa_policy_popup"
	name="ccpa_policy_popup"
	value="1"
	<?php echo checked( $enabled, true ); ?>
/>
<label for="ccpa_policy_popup">
	<?php echo esc_html_x( 'Enable Policy Link On Popup', '(Admin)', 'ccpa-framework' ); ?>
</label>
