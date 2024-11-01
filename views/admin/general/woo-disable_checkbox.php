<input
	type="checkbox"
	id="ccpa_disable_checkbox_woo_compatibility"
	name="ccpa_disable_checkbox_woo_compatibility"
	value="1"
	<?php echo checked( $enabled, true ); ?>
/> 

<label for="ccpa_disable_checkbox_woo_compatibility">
	<?php echo esc_html_x( 'Disable WooCommerce Privacy Checkbox', '(Admin)', 'ccpa-framework' ); ?>
</label>
