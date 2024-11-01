<p>
	<?php echo esc_html_x( 'Heads up! The CCPA Framework is not properly configured, so it will not work just yet.', '(Admin)', 'ccpa-framework' ); ?> <br>
	<?php
	echo sprintf(
		esc_html_x( 'Go to %1$sTools > Data443 CCPA%2$s and make sure all fields are filled in.', '(Admin)', 'ccpa-framework' ),
		"<a href='" . esc_url( ccpa( 'helpers' )->getAdminUrl() ) . "'>",
		'</a>'
	);
	?>
</p>
