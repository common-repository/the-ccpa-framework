<p>
	<?php echo esc_html_x( 'Heads up - your Privacy Policy still requires some attention. Find the places marked with [TODO] and replace them with real content!', '(Admin)', 'ccpa-framework' ); ?>
</p>
<p>
	<?php
	echo esc_html_x(
		sprintf( 'Read more about editing your Privacy Policy %shere%s', "<a href='{$helpUrl}'>", '</a>' ),
		'(Admin)',
		'ccpa-framework'
	);
	?>
</p>
