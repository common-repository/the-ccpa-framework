<input type="text" class="ccpa-text-field" name="ccpa_popup_dismiss_text" value="<?php if ( $content != '' ) { ?>
	<?php echo esc_html_x( $content, 'ccpa-framework' ); ?>
	<?php
																				 } else {
																						?>
																						<?php echo esc_html_x( 'Decline', 'ccpa-framework' ); ?><?php } ?>" />
