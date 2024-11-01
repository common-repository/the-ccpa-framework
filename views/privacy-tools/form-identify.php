<?php do_action( 'ccpa/privacy-tools-page/identify/before' ); ?>

<?php if ( isset( $_REQUEST['ccpa_notice'] ) && in_array( $_REQUEST['ccpa_notice'], array( 'data_deleted', 'request_sent' ) ) ) : ?>
	<p>
		<br>

		<a href="<?php echo esc_url( get_home_url() ); ?>">
			<?php echo __( 'Back to front page', 'ccpa-framework' ); ?>
		</a>
	</p>
<?php else : ?>

	<h3>
		<?php echo __( 'Please identify yourself via e-mail', 'ccpa-framework' ); ?>
	</h3>
	<form>
		<?php do_action( 'ccpa/privacy-tools-page/identify/fields/before' ); ?>
		<label for="ccpa_email"><?php echo __( 'Enter your email address', 'ccpa-framework' ); ?></label>
		<input type="hidden" name="ccpa_action" value="identify" />
		<input type="hidden" name="ccpa_nonce" value="<?php echo $nonce; ?>" />
		<input type="email" id="ccpa_email" name="email" placeholder="<?php echo __( 'Enter your email address', 'ccpa-framework' ); ?>" />
		<?php do_action( 'ccpa/privacy-tools-page/identify/fields/after' ); ?>
		<?php $gdprbutton = apply_filters( 'gdpr_tool_button', 'Send email' ); ?>
		<input type="submit" value="<?php echo __( $gdprbutton, 'ccpa-framework' ); ?>" id="ccpa-submit"/>
	</form>

<?php endif; ?>

<?php do_action( 'ccpa/privacy-tools-page/identify/after' ); ?>
