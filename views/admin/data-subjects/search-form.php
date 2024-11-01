<p>
	<?php echo esc_html_x( 'On this page, you can find which data subjects personal data you are storing and download, export or delete it.', '(Admin)', 'ccpa-framework' ); ?>
</p>

<hr>

<?php echo $results; ?>

<label>
	<h3><?php echo esc_html_x( 'Find data subject by email', '(Admin)', 'ccpa-framework' ); ?></h3>
	<input type="email" name="ccpa_email" placeholder="<?php echo esc_html_x( 'Email address', '(Admin)', 'ccpa-framework' ); ?>" />
</label>

<input type="hidden" name="ccpa_nonce" value="<?php echo $nonce; ?>" />
<input type="hidden" name="ccpa_action" value="search" />
<input class="button button-primary" type="submit" value="<?php echo esc_html_x( 'Search', '(Admin)', 'ccpa-framework' ); ?>" />

<br><br>
