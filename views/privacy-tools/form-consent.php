<?php if ( $consentData or $consentInfo ) : ?>
	<h2><?php echo __( 'Consent', 'ccpa-framework' ); ?></h2>

	<?php if ( $consentData ) : ?>
		<p>
			<?php echo __( 'Here you can withdraw any consents you have given.', 'ccpa-framework' ); ?>
		</p>
		<table class="ccpa-consent">
			<th colspan="4"><?php echo __( 'Consent types', 'ccpa-framework' ); ?></th>
			<?php foreach ( $consentData as $item ) : ?>
				<tr>
					<td>
						&#10004;
					</td>
					<td>
						<?php echo $item['title']; ?>
					</td>
					<td>
						<em><?php echo $item['description']; ?></em>
					</td>
					<td>
						<?php if ( 'privacy-policy' !== $item['slug'] ) : ?>
							<a href="<?php echo esc_url( $item['withdraw_url'] ); ?>" class="button button-primary">
								<?php echo __( 'Withdraw', 'ccpa-framework' ); ?>
							</a>
						<?php endif; ?>
					</td>
				</tr>
			<?php endforeach; ?>
		</table>
	<?php endif; ?>

	<?php if ( $consentInfo ) : ?>
		<div class="ccpa-consent-disclaimer">
			<?php echo do_shortcode( $consentInfo ); ?>
		</div>
	<?php endif; ?>
	<hr>
<?php endif; ?>
