<h1>
	Privacy Policy (1/2)
</h1>

<h2>Privacy Policy page</h2>
<p>
	The second major requirement of CCPA is a thorough Privacy Policy that explains all of the rights your customers
	have and describes how exactly their data is used. We've put together a CCPA-compliant privacy policy template for you.
	Fill in the fields below and a privacy policy will be generated automatically. Note that you will need to modify it later to suit your website and business. <br>
	
	If you already have a CCPA-compliant Privacy Policy, simply select the page where it is displayed and skip the rest.
	<br>
</p>

<fieldset>
	<label>
		<input type="radio" name="ccpa_create_policy_page" value="yes" class="js-ccpa-conditional" <?php echo ! $policyPage ? 'checked' : ''; ?>>
		Automatically create a new page for Privacy Policy
	</label>

	<label>
		<input type="radio" name="ccpa_create_policy_page" value="no" class="js-ccpa-conditional" data-show=".ccpa-select-policy-page" <?php echo $policyPage ? 'checked' : ''; ?>> I want to use an existing page
	</label>
</fieldset>

	<p class="ccpa-select-policy-page hidden">
		<label for="ccpa_policy_page">Select the page where your Privacy Policy will be displayed</label>
		<?php echo $policyPageSelector; ?>
		<strong>OR</strong>
		<label for="ccpa_custom_policy_page">Enter the page URL where your Privacy Policy will be displayed</label>
		<input 
			type="url" 
			name="ccpa_custom_policy_page" 
			id="ccpa_custom_policy_page" 
			value="<?php echo esc_attr( $policy_page_url ); ?>"
		>
		<span class="notice_ccpa">(Leave blank if policy page selected above or make it blank if policy page exist in above page lists.)</span>
	</p>
<p>
	We can generate a somewhat personalized Privacy Policy template for you based on some information you can fill in below.
	Note that if you're using an existing page, this will overwrite the page contents.

	<label for="ccpa_generate_policy">
		<input
			type="checkbox"
			name="ccpa_generate_policy"
			id="ccpa_generate_policy"
			class="js-ccpa-conditional"
			data-show=".ccpa-generator-fields"
			value="yes"
		>
		Generate Privacy Policy
	</label>
	<em>Heads up - this will take some time to configure!</em>
</p>

<hr>

<div class="ccpa-generator-fields hidden">

	<h2>Company information</h2>
	<label for="ccpa_company_name">Company legal name</label>
	<input type="text" id="ccpa_company_name" name="ccpa_company_name" value="<?php echo esc_attr( $companyName ); ?>"/>
	<em>Enter the name of your company. If you are not registered as a company, enter your full name.</em>

	<label for="ccpa_contact_email">Contact email</label>
	<input type="email" id="ccpa_contact_email" name="ccpa_contact_email" value="<?php echo esc_attr( $contactEmail ); ?>"/>
	<em>
		Enter the contact email which should be used for CCPA and personal data related inquiries.<br>
		This email will be displayed in the Privacy Policy.
	</em>

	<hr>

	<h2>Company location</h2>
	<label for="ccpa_company_location">Company location</label>
	<select id="ccpa_company_location" name="ccpa_company_location" class="js-ccpa-select2 ccpa-select js-ccpa-conditional js-ccpa-country-selector">
		<?php echo $countryOptions; ?>
	</select>
	<em>
		Select the country where your company is registered. <br>
		If you are not registered as a company, enter your country of residence.
	</em>
	<div class="ccpa-representative hidden">
		<p>

		   
		</p>
		<p>
			If you have a representative contact, enter the contact details below.

			<label for="ccpa_representative_contact_name">Representative contact name</label>
			<input type="text" value="<?php echo esc_attr( $representativeContactName ); ?>" id="ccpa_representative_contact_name" name="ccpa_representative_contact_name" data />

			<label for="ccpa_representative_contact_email">Representative contact email</label>
			<input type="email" value="<?php echo esc_attr( $representativeContactEmail ); ?>" id="ccpa_representative_contact_email" name="ccpa_representative_contact_email" />

			<label for="ccpa_representative_contact_phone">Representative contact phone</label>
			<input type="text" value="<?php echo esc_attr( $representativeContactPhone ); ?>" id="ccpa_representative_contact_phone" name="ccpa_representative_contact_phone" />

		</p>
	</div>
	<br>
	<hr>

	
	<script>
		window.ccpaDpaData = <?php echo $dpaData; ?>;
	</script>

</div>

<h2>Terms & Conditions page</h2>
<p>
	If you have a Terms & Conditions page, we will need to know where it is located. If you don't have a Terms & Conditions page, you can safely skip this step.<br>
	
	<label for="ccpa_has_terms_page">
		<input
				type="checkbox"
				name="ccpa_has_terms_page"
				id="ccpa_has_terms_page"
				class="js-ccpa-conditional"
				data-show=".ccpa-terms-page"
				value="yes"
			<?php echo checked( $hasTermsPage, 'yes' ); ?>
		>
		I have a Terms & Conditions page
	</label>
</p>
<p>
	<span class="ccpa-terms-page hidden">
	<label for="ccpa_terms_page">Select the page where your Terms & Conditions are displayed</label>
		<?php if ( $termsPageNote ) : ?>
			<em><?php echo esc_html( $termsPageNote ); ?></em>
		<?php endif; ?>
		<?php echo $termsPageSelector; ?>
		<br>
	</span>
</p>

<hr>
<br>
<input type="submit" class="button button-ccpa button-right" value="Save &raquo;" />
