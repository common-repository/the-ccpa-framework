<div>
	<h3>Results for: <?php echo esc_html( $email ); ?></h3>
	<?php if ( $hasData ) : ?>

		<?php if ( isset( $links['profile'] ) ) : ?>
			<p>
				<strong><?php echo esc_html_x( 'Username', '(Admin)', 'ccpa-framework' ); ?>:</strong>
				<a href="<?php echo esc_url( $links['profile'] ); ?>"><?php echo esc_html( $userName ); ?></a>
			</p>
		<?php else : ?>
			<p>
				<em>
					<?php echo esc_html_x( 'Data found.', '(Admin)', 'ccpa-framework' ); ?>
					<strong><?php echo esc_html( $email ); ?></strong> <?php echo esc_html_x( 'is not a registered user.', '(Admin)', 'ccpa-framework' ); ?>
				</em>
			</p>
		<?php endif; ?>

		<hr>

		<a class="button button-primary" href="<?php echo esc_url( $links['view'] ); ?>"><?php echo esc_html_x( 'Download data (html)', '(Admin)', 'ccpa-framework' ); ?></a>
		<a class="button button-primary" href="<?php echo esc_url( $links['export'] ); ?>"><?php echo esc_html_x( 'Export data (json)', '(Admin)', 'ccpa-framework' ); ?></a>

		<?php if ( $adminCap ) : ?>
			<p>
				<strong><?php echo esc_html_x( 'This user has admin capabilities. Deleting data via this interface is disabled.', '(Admin)', 'ccpa-framework' ); ?></strong>
			</p>
		<?php else : ?>
			<a class="button button-primary" href="<?php echo esc_url( $links['anonymize'] ); ?>"><?php echo esc_html_x( 'Anonymize data', '(Admin)', 'ccpa-framework' ); ?></a>
			<a class="button button-primary" href="<?php echo esc_url( $links['delete'] ); ?>"><?php echo esc_html_x( 'Delete data', '(Admin)', 'ccpa-framework' ); ?></a>
		<?php endif; ?>

	<?php else : ?>
		<p><?php echo esc_html_x( 'No data found!', '(Admin)', 'ccpa-framework' ); ?></p>
	<?php endif; ?>

	<hr>

	<?php if ( $consentData ) : ?>
		<table class="ccpa-consent">
			<th colspan="2"><?php echo esc_html_x( 'Consents given', '(Admin)', 'ccpa-framework' ); ?></th>
			<?php foreach ( $consentData as $item ) : ?>
				<tr>
					<td>
						&#10004;
					</td>
					<td>
						<?php echo _x( $item['title'], '(Admin)', 'ccpa-framework' ); ?>
					</td>
				</tr>
			<?php endforeach; ?>
		</table>
	<?php else : ?>
		<p><?php echo esc_html_x( 'No consents given!', '(Admin)', 'ccpa-framework' ); ?></p>
	<?php endif; ?>
	<hr>
</div>
