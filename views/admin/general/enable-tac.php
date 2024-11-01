<input
	type="checkbox"
	id="ccpa_enable_tac"
	name="ccpa_enable_tac"
	value="1"
	<?php echo checked( $enabled, true ); ?>
/>
<label for="ccpa_enable_tac">
	<?php echo esc_html_x( 'Enable the term and condition page.', '(Admin)', 'ccpa-framework' ); ?>
</label>
