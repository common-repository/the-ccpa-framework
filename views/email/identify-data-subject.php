<p>
	<?php echo esc_html__( 'Someone has requested access to your data on', 'ccpa-framework' ); ?> <?php echo esc_html( $siteName ); ?> <br/>
	<?php echo esc_html__( 'If this was a mistake, just ignore this email and nothing will happen.', 'ccpa-framework' ); ?> <br/>
	<?php echo esc_html__( 'To manage your data, visit the following address:', 'ccpa-framework' ); ?> <br/>
	<a href="<?php echo esc_url( $identificationUrl ); ?>">
		<?php echo esc_url( $identificationUrl ); ?>
	</a>
</p>
<p>
	<?php echo esc_html__( 'This link is valid for 15 minutes.', 'ccpa-framework' ); ?>
</p>
