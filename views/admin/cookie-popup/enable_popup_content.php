<textarea name="ccpa_popup_content" rows="5" cols="40">
<?php if ( $content != '' ) { ?>
	<?php echo esc_html_x( $content, 'ccpa-framework' ); ?>
<?php } else { ?>
	<?php echo esc_html_x( 'This website uses cookies to ensure you get the best experience on our website.', 'ccpa-framework' ); ?>
<?php } ?>
</textarea>

