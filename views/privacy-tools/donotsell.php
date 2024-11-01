<form id="form-new-post">
		<fieldset>
			
			<h4 class="mt-0"><?php echo __( 'Do Not Sell Request', 'ccpa-framework' ); ?></h4>
			<span id="donotsellmsg" class="msg"></span>
			<!-- <span id="donotsell-error-msg" class="msg"></span> -->
			<div class="form_row">
				<div class="col_6">
					<div class="form-group">
						<input type="text" class="form-control" placeholder="First Name" name="donotsell_first_name" value="<?php echo ( $first_name != '' ) ? esc_html( $first_name ) : ''; ?>" required/>
					</div>
				</div>
				<div class="col_6">
					<div class="form-group">
						<input type="text" class="form-control" placeholder="Last Name" name="donotsell_last_name" value="<?php echo ( $last_name != '' ) ? esc_html( $last_name ) : ''; ?>" required/>
						
					</div>
				</div>
				<div class="col_12">
					<div class="form-group">
						<input type="email" class="form-control" placeholder="Email" name="donotsell_email" value="<?php echo ( $user_email != '' ) ? esc_html( $user_email ) : ''; ?>" required/>
					</div>
				</div>
			</div>
			<?php if ( ! empty( $defaultConsentTypes ) ) { ?>
				<div class="form-group">
					<label for="donotsell_consent" class="form_p">
						<input type="checkbox" name="donotsell_consent" id="donotsell_consent" class="js-ccpa-conditional form_check" data-show=".ccpa-terms-page" value="yes" required>
						<?php echo __( esc_html( $defaultConsentTypes['title'] ), 'ccpa-framework' ); ?>
					</label>
				</div>
				<div class="form-group">
					<p class="form_p"><?php echo __( esc_html( $defaultConsentTypes['description'] ), 'ccpa-framework' ); ?></p>
				</div>
			<?php } else { ?>
				<div class="form-group">
					<label for="donotsell_consent" class="form_p">
						<input type="checkbox" name="donotsell_consent" id="donotsell_consent" class="js-ccpa-conditional form_check" data-show=".ccpa-terms-page" value="yes" <?php echo ( $user_email != '' ) ? 'checked="checked"' : ''; ?> required>
						<?= esc_html__('I agree to receive other communications from ', 'ccpa-framework') . get_bloginfo('name') ?>
					</label>
				</div>
				<div class="form-group"><p class="form_p"></p></div>
			<?php } ?>
			<button type="submit"
					class="submit"
					data-is-updated="false"
					data-is-update-text="UPDATE"><?= esc_html_x('Submit', '(Admin)', 'ccpa-framework') ?>
			</button>
		</fieldset>
	</form>
