<h1>
	Forms and Consent
</h1>

<h2>Introduction</h2>

<p>
	CCPA brings very strict rules for gathering and processing personal data. The most important thing to remember is that
	you need to have <strong>legal grounds</strong> for each and every type of data you gather and process. If you are not
	familiar with what this means, we strongly recommend you read this post from the guide: <br>
	<a href="<?php echo esc_url(ccpa('helpers')->legalGrounds()); ?>" target="_blank">Consent and other legal grounds for processing data</a>
</p>

<p>
	Under CCPA, <strong>all forms</strong> require special attention. You'll need to explain why you gather the data and
	what you do with it. And if there are no other legal grounds, you'll need to ask for consent.
	Note that your customers must also be able to <strong>withdraw</strong> each consent they have given.
</p>

<h2>WordPress forms</h2>

<p>
	For <strong>posting comments</strong> and <strong>registering accounts</strong>,
	you will need to ask your customers consent to the Privacy Policy. If your site allows comments or registration,
	the CCPA Framework will automatically add consent checkboxes to the respective forms.
</p>

<h2>Withdrawing consent</h2>

<p>
	Your customers can withdraw their given consents on the <a href="<?php echo $privacyToolsPageUrl; ?>" target="blank">Privacy Tools Page</a>.
</p>
<br>
<hr>

<?php 
if ( $hasCF7 ) :
?>
	<h2>&#10004; Contact Form 7</h2>
	<p>
		We have detected that Contact Form 7 is enabled on your site. The CCPA Framework is
		compatible with Contact Form 7.
	</p>
	<p>
		Each of the forms requires either a <strong>disclaimer</strong> or a <strong>consent checkbox</strong>.
		We have created some tools to help you out!
	</p>
	<p>
		<a href="<?php echo esc_url(ccpa('helpers')->legalGrounds()); ?>" target="_blank">Read about making your Contact Form 7 forms CCPA compliant.</a>
	</p>
	<p class="ccpa_cf7_notice">
	   <b>NOTE :</b> Your Contact Form 7's data will only store on the website if "<a href="https://wordpress.org/plugins/flamingo/" target="_blank">Flamingo</a>" plugin is installed else no data will be stored on the website, it will only send email. If Flamingo will be activated then there will be new privacy tab appear on each form. You need to setup settings there to make data trackable so that your contact form 7 will become CCPA Complience.
	</p>
	<hr>
<?php endif; ?>

<h2>Custom forms</h2>
<p>
	<label for="ccpa_has_contact_forms">
		<input
			type="checkbox"
			name="ccpa_has_contact_forms"
			id="ccpa_has_contact_forms"
			class="js-ccpa-conditional"
			data-show=".ccpa-contact-custom"
			value="yes"
		>
		I have custom forms on my website (e.g. contact forms, newsletter signups, lottery signups)
	</label>
</p>
<p class="ccpa-contact-custom hidden">
	Each of the forms on your site requires either a <strong>disclaimer</strong> or a <strong>consent checkbox</strong>. <br>
	We cannot do this automatically - you need to do it yourself or request help from a developer. However, we have created some tools to help you out!
	<br>
	<a href="<?php echo esc_url(ccpa('helpers')->legalGrounds()); ?>" target="_blank">Read about integrating custom forms</a>
</p>

<hr>
<br>
<input type="submit" class="button button-ccpa button-right" value="Continue &raquo;" />
