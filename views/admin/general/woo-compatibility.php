<input
	type="checkbox"
	id="ccpa_enable_woo_compatibility"
	name="ccpa_enable_woo_compatibility"
	value="1"
	<?php echo checked( $enabled, true ); ?>
/>
<label for="ccpa_enable_woo_compatibility">
	<?php echo esc_html_x( 'Enable WooCommerce data on CCPA tool.', '(Admin)', 'ccpa-framework' ); ?>
</label>
<p class="description">
	<?php echo esc_html_x( 'Will work for WooCommerce Version 3.4.0 or later.', '(Admin)', 'ccpa-framework' ); ?>
</p>
