<div class="ccpa-framework-privacy-tools">
	<?php do_action( 'ccpa/frontend/privacy-tools-page/content/before', $dataSubject ); ?>

	<p>
		<?php echo __( 'You are identified as', 'ccpa-framework' ); ?> <strong><?php echo esc_html( $email ); ?></strong>
	</p>

	<hr>

	<?php do_action( 'ccpa/frontend/privacy-tools-page/content', $dataSubject ); ?>

	<?php do_action( 'ccpa/frontend/privacy-tools-page/content/after', $dataSubject ); ?>
</div>
