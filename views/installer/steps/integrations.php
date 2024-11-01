<h1>
	Integrations
</h1>

<h2>Theme compatibility</h2>
<p>
	The links to Privacy Policy and Privacy Tools should be visible somewhere on your site.
	A good place would be your site's footer.
</p>
<?php if ( $isThemeSupported ) : ?>
	<p>
		We have detected that you are running <strong><?php echo esc_html( ucfirst( $currentTheme ) ); ?> theme</strong>. We can automatically add the links to your site's footer if you'd like.
		<label for="ccpa_enable_theme_compatibility">
			<input
				type="checkbox"
				id="ccpa_enable_theme_compatibility"
				name="ccpa_enable_theme_compatibility"
				value="yes"
				<?php echo checked( $enableThemeCompatibility, true ); ?>
			/>
			<?php echo esc_html_x( 'Automatically add Privacy Policy and Privacy Tools links to your site footer.', '(Admin)', 'ccpa-framework' ); ?>
		</label>
	</p>
<?php endif; ?>
<hr>

<?php if ( isset( $hasSendgrid ) ) : ?>
	<h2>IMPORTANT: Sendgrid compatibility</h2>
	<p>
		It looks like you are using Sendgrid to send emails. Note that the links in identification emails will not work properly unless you have click tracking turned off in Sendgrid.
	</p>
	<hr>
<?php endif; ?>

<?php if ( $hasWooCommerce ) : ?>
	<h2>WooCommerce compatibility</h2>
	<p>
		TBD
	</p>
	<hr>
<?php endif; ?>

<?php if ( $hasEDD ) : ?>
	<h2>Easy Digital Downloads compatibility</h2>
	<p>
		TBD
	</p>
	<hr>
<?php endif; ?>

<h2>Custom development</h2>
<p>
	If you've had a developer build any custom features for your site, you should also make sure that everything is properly CCPA-compliant.
	<br>
</p>

<hr>
<br>
<input type="submit" class="button button-ccpa button-right" value="Continue &raquo;" />
