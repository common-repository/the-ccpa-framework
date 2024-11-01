<div class="ccpa-notice">
	<mark>
		<?php if ( 'email_sent' === sanitize_key( $_REQUEST['ccpa_notice'] ) ) : ?>
			<?php echo __( 'We will send you an email with the link to access your data. Please check your spam folder as well!', 'ccpa-framework' ); ?>
		<?php endif; ?>

		<?php if ( 'invalid_email' === sanitize_key( $_REQUEST['ccpa_notice'] ) ) : ?>
			<?php echo __( 'The email you entered does not appear to be a valid email.', 'ccpa-framework' ); ?>
		<?php endif; ?>

		<?php if ( 'invalid_key' === sanitize_key( $_REQUEST['ccpa_notice'] ) ) : ?>
			<?php echo __( 'Sorry - the link seems to have expired. Please try again!', 'ccpa-framework' ); ?>
		<?php endif; ?>

		<?php if ( 'consent_withdrawn' === sanitize_key( $_REQUEST['ccpa_notice'] ) ) : ?>
			<?php echo __( 'Consent withdrawn.', 'ccpa-framework' ); ?>
		<?php endif; ?>

		<?php if ( 'request_sent' === sanitize_key( $_REQUEST['ccpa_notice'] ) ) : ?>
			<?php echo __( 'We have received your request and will reply within 30 days.', 'ccpa-framework' ); ?>
		<?php endif; ?>

		<?php if ( 'data_deleted' === sanitize_key( $_REQUEST['ccpa_notice'] ) ) : ?>
			<?php echo __( 'Your personal data has been removed!', 'ccpa-framework' ); ?>
		<?php endif; ?>

		<?php if ( 'unregistered_user' === sanitize_key( $_REQUEST['ccpa_notice'] ) ): ?>
			<?= __(sanitize_text_field(ccpa('options')->get('unknown_user_message', CCPA_DEFAULT_UNKNOWN_USER_MESSAGE)), 'ccpa-framework'); ?>
		<?php endif; ?>
	</mark>
</div>
