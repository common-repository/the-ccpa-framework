
<input type="url" name="ccpa_custom_policy_page" value="<?php if ( $content != '' ) { ?>
	<?php echo esc_html_x( $content, 'ccpa-framework' ); ?>
<?php } ?>" />
<p class="description">
	<?php echo esc_html_x( 'Leave blank if privacy policy page already selected', '(Admin)', 'ccpa-framework' ); ?>
</p>
