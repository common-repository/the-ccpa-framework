<input
	type="checkbox"
	id="ccpa_enable_stylesheet"
	name="ccpa_enable_stylesheet"
	value="1"
	<?php echo checked( $enabled, true ); ?>
/>
<label for="ccpa_enable_stylesheet">
	<?php esc_html( _ex( 'Enable basic styling for Privacy Tools page.', '(Admin)', 'ccpa-framework' ) ); ?>
</label>
