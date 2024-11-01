<input
	type="checkbox"
	id="ccpa_enable_edd_compatibility"
	name="ccpa_enable_edd_compatibility"
	value="1"
	<?php echo checked( $enabled, true ); ?>
/>
<label for="ccpa_enable_edd_compatibility">
	<?php echo esc_html_x( 'Enable EDD data on CCPA tool.', '(Admin)', 'ccpa-framework' ); ?>
</label>
<p class="description">
	<?php echo esc_html_x( 'Will work for EDD Version 2.0.0 or later.', '(Admin)', 'ccpa-framework' ); ?>
</p>
