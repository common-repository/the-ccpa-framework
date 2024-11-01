<hr>

<table class="form-table">
	<tr>
		<th>
			<label>
				<?php echo __( 'Download your data', 'ccpa-framework' ); ?>
			</label>
		</th>
		<td>
			<a class="button button-primary" href="<?php echo esc_url( $exportHTMLUrl ); ?>">
				<?php echo __( 'Download as table', 'ccpa-framework' ); ?>
			</a>
			<a class="button button-primary" href="<?php echo esc_url( $exportJSONUrl ); ?>">
				<?php echo __( 'Export as JSON', 'ccpa-framework' ); ?>
			</a>
			<br />
			<p class="description">
				<?php echo __( 'You can download all your data formatted as a table for viewing.', 'ccpa-framework' ); ?> <br>
				<?php echo __( 'Alternatively, you can export it in machine-readable JSON format.', 'ccpa-framework' ); ?>
			</p>
		</td>
	</tr>
</table>
