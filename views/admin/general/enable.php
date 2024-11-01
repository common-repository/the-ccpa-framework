<input
	type="checkbox"
	id="ccpa_enable"
	name="ccpa_enable"
	value="1"
	<?php echo checked( $enabled, true ); ?>
/>
<label for="ccpa_enable">
	<?php echo esc_html_x( 'Enable the view, export and forget functionality for users and visitors', '(Admin)', 'ccpa-framework' ); ?>
</label>
<p class="description">
	<?php echo esc_html_x( 'Enable the Privacy Tools page on front-end and dashboard. This allows visitors to request viewing and deleting their personal data and withdraw consents.', '(Admin)', 'ccpa-framework' ); ?>
</p>
