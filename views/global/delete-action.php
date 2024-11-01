<option value="anonymize" <?php echo selected( $deleteAction, 'anonymize' ); ?>>
	<?php echo esc_html_x( 'Automatically anonymize data', '(Admin)', 'ccpa-framework' ); ?>
</option>
<option value="delete" <?php echo selected( $deleteAction, 'delete' ); ?> data-show=".js-ccpa-delete-action-reassign">
	<?php echo esc_html_x( 'Automatically delete data', '(Admin)', 'ccpa-framework' ); ?>
</option>
<option value="anonymize_and_notify" <?php echo selected( $deleteAction, 'anonymize_and_notify' ); ?>
		data-show=".js-ccpa-delete-action-email">
	<?php echo esc_html_x( 'Automatically anonymize data and notify me via email', '(Admin)', 'ccpa-framework' ); ?>
</option>
<option value="delete_and_notify" <?php echo selected( $deleteAction, 'delete_and_notify' ); ?>
		data-show=".js-ccpa-delete-action-email, .js-ccpa-delete-action-reassign">
	<?php echo esc_html_x( 'Automatically delete data and notify me via email', '(Admin)', 'ccpa-framework' ); ?>
</option>
<option value="notify" <?php echo selected( $deleteAction, 'notify' ); ?> data-show=".js-ccpa-delete-action-email">
	<?php echo esc_html_x( 'Only notify me via email', '(Admin)', 'ccpa-framework' ); ?>
</option>



