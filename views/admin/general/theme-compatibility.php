<input
	type="checkbox"
	id="ccpa_enable_theme_compatibility"
	name="ccpa_enable_theme_compatibility"
	value="1"
	<?php echo checked( $enabled, true ); ?>
/>
<label for="ccpa_enable_theme_compatibility">
	<?php echo esc_html_x( 'Automatically add Privacy Policy and Privacy Tools links to your site footer.', '(Admin)', 'ccpa-framework' ); ?>
</label>
