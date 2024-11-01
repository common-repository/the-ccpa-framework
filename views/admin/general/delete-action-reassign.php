<select id="ccpa_delete_action_reassign" name="ccpa_delete_action_reassign" class="ccpa-select js-ccpa-conditional">
	<option value="delete" <?php echo selected( $reassign, 'delete' ); ?>>
		<?php echo esc_html_x( 'Delete content', '(Admin)', 'ccpa-framework' ); ?>
	</option>
	<option value="reassign" <?php echo selected( $reassign, 'reassign' ); ?> data-show=".js-ccpa-delete-action-reassign-user">
		<?php echo esc_html_x( 'Reassign content to a user', '(Admin)', 'ccpa-framework' ); ?>
	</option>
</select>
<p class="description">
	<?php echo esc_html_x( 'If the user has submitted any content on your site, should it be deleted or reassigned to another user?', '(Admin)', 'ccpa-framework' ); ?>
</p>
