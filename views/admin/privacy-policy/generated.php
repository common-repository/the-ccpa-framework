<h3><?php echo esc_html_x( 'Privacy Policy', '(Admin)', 'ccpa-framework' ); ?></h3>
<p>
	<?php echo esc_html_x( 'Your Privacy Policy has been generated.', '(Admin)', 'ccpa-framework' ); ?>
	<?php if ( $policyUrl ) : ?>
		<?php
		echo __(
			sprintf(
				'You can copy and paste it to your %sPrivacy Policy page%s.',
				"<a href='{$policyUrl}' target='_blank'>",
				'</a>'
			),
			'(Admin)',
			'ccpa-framework'
		);
		?>
	<?php endif; ?>
</p>

<?php echo $editor; ?>

<br>
<a href="<?php echo esc_url( $backUrl ); ?>" class="button button-secondary"><?php echo esc_html_x( '&laquo; Back', '(Admin)', 'ccpa-framework' ); ?></a>
<br><br>
