<table class="form-table">
	<tr>
		<th>
			<label>
				<?php echo _x( 'Delete this user and all data', '(Admin)', 'ccpa-framework' ); ?>
			</label>
		</th>
		<td>
			<?php if ( $showDelete ) : ?>
				<a class="button" href="<?php echo esc_url( $url ); ?>">
					<?php echo _x( 'Delete my data', '(Admin)', 'ccpa-framework' ); ?>
				</a>
				<br/>
				<p class="description">
					<?php echo __( 'Delete all data we have gathered about you.', 'ccpa-framework' ); ?> <br/>
					<?php echo __( 'If you have a user account on our site, it will also be deleted.', 'ccpa-framework' ); ?> <br/>
					<?php echo __( 'Be careful - this action is permanent and CANNOT be undone.', 'ccpa-framework' ); ?>
					<?php if ( ccpa( 'options' )->get( 'enable_woo_compatibility' ) && class_exists( 'Woocommerce' ) ) { ?>
						<br/><strong class="ccpa_woo_note"><?php echo __( 'Note Regarding Order:', 'ccpa-framework' ); ?></strong><br/>
						<?php echo __( 'Your order with status Processing will not get deleted until status change.', 'ccpa-framework' ); ?><br/>
						<?php echo __( 'Your order with status Completed will get anonymize.', 'ccpa-framework' ); ?><br/>
						<?php echo __( "If you delete Completed order you can't apply for refund.", 'ccpa-framework' ); ?><br/>
					<?php } ?>
				</p>
			<?php else : ?>
				<p>
					<em>
						<?php echo _x( 'You seem to have an administrator or equivalent role, so deleting/anonymizing via this page is disabled.', '(Admin)', 'ccpa-framework' ); ?>
					</em>
				</p>
			<?php endif; ?>
		</td>
	</tr>
</table>


