<h2><?php echo __( 'Download your data', 'ccpa-framework' ); ?></h2>

<p class="description">
	<?php echo __( 'You can download all your data formatted as a table for viewing.', 'ccpa-framework' ); ?> <br>
	<?php echo __( 'Alternatively, you can export it in machine-readable JSON format.', 'ccpa-framework' ); ?>
</p>

<div class="ccpa-download-button">
	<form method="POST">
		<input type="hidden" name="ccpa_nonce" value="<?php echo esc_attr( $nonce ); ?>" />
		<input type="hidden" name="ccpa_action" value="export" />
		<input type="hidden" name="ccpa_format" value="html" />
		<input type="submit" class="button button-primary" value="<?php echo __( 'Download as table', 'ccpa-framework' ); ?>" />
	</form>
</div>

<div class="ccpa-export-button">
	<form method="POST">
		<input type="hidden" name="ccpa_nonce" value="<?php echo esc_attr( $nonce ); ?>" />
		<input type="hidden" name="ccpa_action" value="export" />
		<input type="hidden" name="ccpa_format" value="json" />
		<input type="submit" class="button button-primary" value="<?php echo __( 'Export as JSON', 'ccpa-framework' ); ?>" />
	</form>
</div>

<hr>
