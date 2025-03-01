<h2>
	Privacy Safe 
</h2>
<hr>
<p class="ccpa-disclaimer">
	<img src="<?php echo ccpa('config')->get('plugin.url');?>src/Components/PrivacySafe/Privacy-Safe-Brand.png"
		style="float:right;margin:15px;" />
	<p>Strengthen your reputation. The privacy safe seal assures your customers that your business is in compliance with
		privacy laws and regulations. The privacy safe seal will verify that the CCPA Framework plugin is installed.</p>
	<p>Register now to activate your Privacy Safe seal. Visit the link below, complete the complete the checkout
		process. Once approved you will recieve notice to get your seal code and image code. Enter those here and save.
		You can then place the seal where you would like on your site.</p>
	<p><a href="https://orders.data443.com/cart.php?a=add&pid=31&carttpl=standard_cart" target="_blank"
			class="button button-primary">Register Here</a></p>
	<p>Embed the shortcode provided to display your privacy safe seal.</p> 
	<p><label for="ccpa_privacy_safe_params">Seal Code</label>
	<input type='text' name='ccpa_privacy_safe_params'
			placeholder='' value=''></p>
	<p><label for="ccpa_privacy_safe_params">Image Code</label>
	<input type='text' name='ccpa_privacy_safe_imagecode'
			placeholder='' value=''></p>
	<p><code>[data443_privacy_safe]</code></p>
	<p><code>echo do_shortcode('[data443_privacy_safe]');</code></p>
</p>
<hr>
<br>
<input type="submit" class="button button-ccpa button-right" value="Save &raquo;" />