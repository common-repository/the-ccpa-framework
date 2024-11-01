<input
	type="checkbox"
	id=ccpa_<?php echo esc_attr( $content['option_name'] ); ?>
	name=ccpa_<?php echo esc_attr( $content['option_name'] ); ?>
	value="1"
	<?php echo checked( $content['value'], true ); ?>
/>
<label for="ccpa_<?php echo esc_attr( $content['option_name'] ); ?>">
	<?php echo esc_html_x( $content['option'] . '.', '(Admin)', 'ccpa-framework' ); ?>
</label>
