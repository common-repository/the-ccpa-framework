<option disabled>-- Choose --</option>

<optgroup label="Outside EU">
	<?php foreach ( $outside as $code => $name ) : ?>
		<option
			value="<?php echo esc_attr( $code ); ?>"
			<?php echo selected( $code, $current ); ?>
			<?php if ( in_array( $code, array( 'UK', 'US', 'other' ) ) ) : ?>
				data-show=".ccpa-representative"
			<?php endif; ?>
		>
			<?php echo esc_html( $name ); ?>
		</option>
	<?php endforeach; ?>
</optgroup>

<optgroup label="European Union">
	<?php foreach ( $eu as $code => $name ) : ?>
		<option value="<?php echo esc_attr( $code ); ?>" <?php echo selected( $code, $current ); ?>>
			<?php echo esc_html( $name ); ?>
		</option>
	<?php endforeach; ?>
</optgroup>
