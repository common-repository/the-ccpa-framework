<textarea name="ccpa_header" rows="5" cols="40">
<?php if ( $content != '' ) { ?>
	<?php echo esc_html_x( $content, 'ccpa-framework' ); ?>
<?php } ?>
</textarea>
<p class="description">
<?php echo esc_html_x( "Leave blank if don't want header to get display.", 'ccpa-framework' ); ?>
</p>
