<p>
	<?php echo esc_html_x(
		sprintf( 'A data subject (%s) has requested to remove their data.', esc_html( $email ) ),
		'(Admin)',
		'ccpa-framework'
	); ?>
	<br>
	<?php
	echo esc_html_x(
		sprintf( 'To access the data subject\'s data, %sclick here%s', "<a href='{$adminTabLink}'>", '</a>' ),
		'(Admin)',
		'ccpa-framework'
	);
	?>
</p>
<p>
	<?php echo esc_html_x( 'As a reminder: according to CCPA, you have 30 days to comply.', '(Admin)', 'ccpa-framework' ); ?>
</p>
