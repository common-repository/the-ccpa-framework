<h1>
	Configuration (2/2)
</h1>
<h2>&#10004; Privacy Tools page configured!</h2>
<p>
	You can take a look at the Privacy Tools page <a href="<?php echo esc_url( $privacyToolsPageUrl ); ?>" target="_blank">here</a>. <br>
	<br>
 
</p>
<hr>

<h2>Right to view & export data</h2>
<p>
	Your customers have the right to review and export their personal data.

	<label for="ccpa_export_action">Select what happens if a customer wishes to view or export their personal data</label>

	<select class="ccpa-select js-ccpa-conditional" name="ccpa_export_action">
		<?php echo ccpa( 'view' )->render( 'global/export-action', compact( 'exportAction' ) ); ?>
	</select>
	<span class="hidden js-ccpa-export-action-email">
		<label for="export_action_email">
			<?php echo esc_html_x( 'Enter the email address to notify', '(Admin)', 'ccpa-framework' ); ?>
		</label>
		<input
				type="email"
				id="ccpa_export_action_email"
				name="ccpa_export_action_email"
				placeholder="<?php echo __( 'Email address', 'ccpa-framework' ); ?>"
				value="<?php echo esc_attr( $exportActionEmail ); ?>"
		/>
	</span>
</p>
<hr>

<h2>Right to be forgotten</h2>
<p>
	Your customers have the right to request deleting their personal data.

	<label for="ccpa_delete_action">Select what happens if a customer wishes to delete their personal data</label>

	<select class="ccpa-select js-ccpa-conditional" name="ccpa_delete_action">
		<?php echo ccpa( 'view' )->render( 'global/delete-action', compact( 'deleteAction' ) ); ?>
	</select>

	<span class="hidden js-ccpa-delete-action-reassign">
		<label for="ccpa_delete_action_reassign">If the user has created any content (posts or pages), should it be deleted or reassigned?</label>
		<select id="ccpa_delete_action_reassign" name="ccpa_delete_action_reassign" class="ccpa-select js-ccpa-conditional">
			<option value="delete" <?php echo selected( $reassign, 'delete' ); ?>>
				<?php echo esc_html_x( 'Delete content', '(Admin)', 'ccpa-framework' ); ?>
			</option>
			<option value="reassign" <?php echo selected( $reassign, 'reassign' ); ?> data-show=".js-ccpa-delete-action-reassign-user">
				<?php echo esc_html_x( 'Reassign content to a user', '(Admin)', 'ccpa-framework' ); ?>
			</option>
		</select>
	</span>

	<span class="hidden js-ccpa-delete-action-reassign-user">
		<label for="ccpa_delete_action_reassign_user">Select the user to reassign content to</label>
		<?php
		wp_dropdown_users(
			array(
				'name'              => 'ccpa_delete_action_reassign_user',
				'show_option_none'  => esc_html_x( '&mdash; Select &mdash;', '(Admin)', 'ccpa-framework' ),
				'option_none_value' => '0',
				'selected'          => $reassignUser,
				'class'             => 'js-ccpa-select2 ccpa-select',
				'id'                => 'ccpa_delete_action_reassign_user',
				'role__in'          => apply_filters( 'ccpa/options/reassign/roles', array( 'administrator', 'editor' ) ),
			)
		);
		?>
	</span>

	<span class="hidden js-ccpa-delete-action-email">
		<label for="delete_action_email">
			<?php echo esc_html_x( 'Enter the email address to notify', '(Admin)', 'ccpa-framework' ); ?>
		</label>
		<input
			type="email"
			id="ccpa_delete_action_email"
			name="ccpa_delete_action_email"
			placeholder="<?php echo __( 'Email address', 'ccpa-framework' ); ?>"
			value="<?php echo esc_attr( $deleteActionEmail ); ?>"
		/>
	</span>
</p>

<hr>
<br>
<input type="submit" class="button button-ccpa button-right" value="Save &raquo;"/>
