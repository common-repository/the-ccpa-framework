<label for="ccpa_has_dpo">
	<input
		type="checkbox"
		name="ccpa_has_dpo"
		id="ccpa_has_dpo"
		class="js-ccpa-conditional"
		data-show=".ccpa-dpo"
		value="yes"
		<?php echo checked( $hasDPO, 'yes' ); ?>
	>
	<?php echo esc_html_x( 'I have appointed a Data Protection Officer (DPO)', '(Admin)', 'ccpa-framework' ); ?>
</label>
