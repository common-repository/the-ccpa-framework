<select class="ccpa-select js-ccpa-conditional" name="ccpa_popup_link_target">
<option value="_blank" <?php echo selected( $content, '_blank' ); ?>>
	<?php echo esc_html_x( 'Next Tab', '(Admin)', 'ccpa-framework' ); ?>
</option>
<option value="_self" <?php echo selected( $content, '_self' ); ?>>
	<?php echo esc_html_x( 'Self', '(Admin)', 'ccpa-framework' ); ?>
</option>
</select>
