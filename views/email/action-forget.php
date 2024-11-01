<p>
	<?php echo esc_html_x(
		sprintf( 'A data subject (%s) has just removed all their data from your website.', esc_html( $email ) ),
		'(Admin)',
		'ccpa-framework'
	); ?> <br><br>

	<?php if ( $userId ) : ?>
		<?php echo esc_html_x( 'The data subject had a user account on your website.', '(Admin)', 'ccpa-framework' ); ?> (ID: <?php echo $userId; ?>).
	<?php endif; ?>
</p>
<p>
	<?php echo esc_html_x( 'This email is just for your information. You don\'t need to take any action', '(Admin)', 'ccpa-framework' ); ?>
</p>
