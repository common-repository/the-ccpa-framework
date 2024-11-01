<h2 class="align-center">
	All done!
</h2>
<p class="align-center">
	Congrats! You've just taken a huge step towards CCPA compliance!
</p>


<section class="section">
	<h3 class="align-center">
		<?php echo esc_html_x( 'Need help?', '(Admin)', 'ccpa-framework' ); ?>
	</h3>
	<div class="row">
		<div class="col">
		  <div class="col_image" style="background-image:url('<?php echo ccpa( 'config' )->get( 'plugin.url' ); ?>/assets/images/4.png');"></div>
			<a class="button button-primary" href="https://data443.atlassian.net/servicedesk/customer/portal/2" target="_blank">
				<?php echo esc_html_x( 'Submit a support request', '(Admin)', 'ccpa-framework' ); ?>
			</a>
			<p>
				<?php echo esc_html_x( 'Found a bug or have a question about the plugin? Submit a support request and we’ll get right on it!', '(Admin)', 'ccpa-framework' ); ?>
			</p>
		</div>
		<div class="col">
		  <div class="col_image" style="background-image:url('<?php echo esc_url( ccpa( 'config' )->get( 'plugin.url' ) ); ?>/assets/images/5.png');"></div>
			<a class="button button-primary" href="<?php echo esc_url( ccpa( 'helpers' )->docs( 'contact/' ) ); ?>"  target="_blank">
				<?php echo esc_html_x( 'Request a consultation', '(Admin)', 'ccpa-framework' ); ?>
			</a>
			<p>
				<?php echo esc_html_x( 'Need assistance in making your site compliant? We can help!', '(Admin)', 'ccpa-framework' ); ?>
			</p>
		</div>
	</div>
</section>

<input type="submit" class="button button-ccpa button-large button-ccpa-large button-center" value="Back to dashboard"" />
