<hr>

<h3><?php echo esc_html_x( 'Default consent types', '(Admin)', 'ccpa-framework' ); ?></h3>
<p><?php echo esc_html_x( 'These are the consent types that have been automatically registered by the framework or a plugin.', '(Admin)', 'ccpa-framework' ); ?></p>
<?php if ( count( $defaultConsentTypes ) ) : ?>
	<table class="ccpa-consent">
		<th><?php echo esc_html_x( 'Slug', '(Admin)', 'ccpa-framework' ); ?></th>
		<th><?php echo esc_html_x( 'Title', '(Admin)', 'ccpa-framework' ); ?></th>
		<th><?php echo esc_html_x( 'Description', '(Admin)', 'ccpa-framework' ); ?></th>
		<th><?php echo esc_html_x( 'Visibility', '(Admin)', 'ccpa-framework' ); ?></th>
	<?php foreach ( $defaultConsentTypes as $consentType ) : ?>
		<tr>
			<td class="ccpa-consent-table-input"><?php echo $consentType['slug']; ?></td>
			<td class="ccpa-consent-table-input"><?php echo $consentType['title']; ?></td>
			<td class="ccpa-consent-table-desc"><?php echo $consentType['description']; ?></td>
			<td>
				<?php if ( $consentType['visible'] ) : ?>
					<?php echo esc_html_x( 'Visible', '(Admin)', 'ccpa-framework' ); ?>
				<?php else : ?>
					<?php echo esc_html_x( 'Hidden', '(Admin)', 'ccpa-framework' ); ?>
				<?php endif; ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>
<br>
<hr>
<h3><?php echo esc_html_x( 'Custom consent types', '(Admin)', 'ccpa-framework' ); ?></h3>
<p><?php echo esc_html_x( 'Here you can add custom consent types to track. They will not be used anywhere by default - you will need to build an integration for each of them.', '(Admin)', 'ccpa-framework' ); ?></p>
<div class="js-ccpa-repeater" data-name="ccpa_consent_types">
	<table class="ccpa-consent-admin ccpa-show-hide ccpa-hidden" data-repeater-list="ccpa_consent_types">
		<thead>
			<th>
				<?php echo esc_html_x( 'Machine-readable slug', '(Admin)', 'ccpa-framework' ); ?>*
			</th>
			<th>
				<?php echo esc_html_x( 'Title', '(Admin)', 'ccpa-framework' ); ?>*
			</th>
			<th>
				<?php echo esc_html_x( 'Description', '(Admin)', 'ccpa-framework' ); ?>
			</th>
			<th>
				<?php echo esc_html_x( 'Visible?', '(Admin)', 'ccpa-framework' ); ?>
			</th>
		</thead>
		<tr data-repeater-item>
			<td class="ccpa-consent-table-input">
				<input
						type="text"
						name="slug"
						class="ccpa_custom_consent_types"
						placeholder="<?php echo esc_html_x( 'Slug', '(Admin)', 'ccpa-framework' ); ?>"
						pattern="^[A-Za-z0-9_-]+$"
						oninvalid="setCustomValidity('Please fill in this field using alphanumeric characters, dashes and underscores.')"
						oninput="setCustomValidity('')"
						required
				/>
			</td>
			<td class="ccpa-consent-table-input">
				<input type="text" name="title" class="ccpa_custom_consent_types" placeholder="<?php echo esc_html_x( 'Title', '(Admin)', 'ccpa-framework' ); ?>" required />
			</td>
			<td class="ccpa-consent-table-desc">
				<textarea type="text" name="description" placeholder="<?php echo esc_html_x( 'Description', '(Admin)', 'ccpa-framework' ); ?>"></textarea>
			</td>
			<td>
				<label>
					<input type="checkbox" name="visible" value="1"/>
					<?php echo esc_html_x( 'Visible?', '(Admin)', 'ccpa-framework' ); ?>
				</label>
			</td>
			<td>
			  <input data-repeater-delete class="button button-primary" type="button" value="<?php echo esc_html_x( 'Remove', '(Admin)', 'ccpa-framework' ); ?>"/>
			</td>
		</tr>

	</table>
	<div class="ccpa-consent-add-button">
	  <input data-enable-repeater class="button button-primary show_form_consent_ccpa" type="button" value="<?php echo esc_html_x( 'Show Consent types', '(Admin)', 'ccpa-framework' ); ?>"/>
	  <input data-repeater-create class="button button-primary ccpa-show-hide ccpa-hidden" type="button" value="<?php echo esc_html_x( 'Add consent type', '(Admin)', 'ccpa-framework' ); ?>"/>
	  <input data-enable-repeater class="button button-primary hide_form_consent_ccpa ccpa-show-hide ccpa-hidden" type="button" value="<?php echo esc_html_x( 'Hide consent types', '(Admin)', 'ccpa-framework' ); ?>"/>
	</div>
	<input type="hidden" name="ccpa_nonce" value="<?php echo $nonce; ?>" />
	<input type="hidden" name="ccpa_action" value="update_consent_data" />
</div>

<?php if ( count( $customConsentTypes ) ) : ?>
	<script>
		window.repeaterData = [];
		window.repeaterData['ccpa_consent_types'] = <?php echo json_encode( $customConsentTypes ); ?>;
	</script>
<?php endif; ?>
<br>
<hr>
<h3><?php echo esc_html_x( 'Additional info', '(Admin)', 'ccpa-framework' ); ?></h3>
<p>
	<?php echo esc_html_x( 'This text will be displayed to your data subjects on the Privacy Tools page.', '(Admin)', 'ccpa-framework' ); ?>
</p>
<?php
wp_editor(
	wp_kses_post( $consentInfo ),
	'ccpa_consent_info',
	array(
		'textarea_rows' => 4,
	)
);
?>
<br>
<hr>
