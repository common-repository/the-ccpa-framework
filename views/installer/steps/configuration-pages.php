<h1>
	Configuration (1/2)
</h1>

<h2>Plugin settings</h2>
<p>In WordPress admin, there is now a new page under the Tools menu item called "Data443 CCPA." Once you've finished the wizard, you can modify the plugin settings there.</p>
<div>
</div>


<h2>Privacy Policy page</h2>
<p>
	The first major requirement of CCPA is that your customers need to be in control of their data. They have the
	right to view, edit and request deleting their personal data. Note that this also
	applies to visitors who do not have accounts on your website.
</p>
<p>
	For this, we will designate a page where customers will be able to authenticate via login or email and automatically do all of the above.
	<a href="<?= ccpa('helpers')->controllingPersonalData() ?>" target="_blank">Read more about the Privacy Tools page</a>
</p>
<hr>

<h4>Set up the Privacy Tools page</h4>
<fieldset>
	<label>
		<input type="radio" name="ccpa_create_tools_page" value="yes" class="js-ccpa-conditional" <?php echo ! $privacyToolsPage ? 'checked' : ''; ?>>
		Automatically create a new page for Privacy Tools
	</label>

	<label>
		<input type="radio" name="ccpa_create_tools_page" value="no" class="js-ccpa-conditional" data-show=".ccpa-select-privacy-tools-page" <?php echo $privacyToolsPage ? 'checked' : ''; ?>> Select an existing page
	</label>
</fieldset>

<p class="ccpa-select-privacy-tools-page hidden">
	<label for="ccpa_tools_page">Select the page for Privacy Tools</label>
	<?php echo $privacyToolsPageSelector; ?>
	<strong>Important:</strong> Make sure that the page contains the <strong>[ccpa_privacy_tools]</strong> shortcode.
</p>

<hr>
<br>
<input type="submit" class="button button-ccpa button-right" value="Save &raquo;"/>
