<hr>
<?php if ( count( $consentData ) ) : ?>
	<table class="ccpa-consent">
		<th colspan="2"><?php echo _x( 'Consents given', '(Admin)', 'ccpa-framework' ); ?></th>
		<?php foreach ( $consentData as $item ) : ?>
			<tr>
				<td>
					&#10004;
				</td>
				<td>
				<?= $item['title']; ?> Valid until <?= $item['valid_until']; ?>
				</td>
			</tr>
		<?php endforeach; ?>
	</table>
<?php else : ?>
	<p><?php echo _x( 'No consents given', '(Admin)', 'ccpa-framework' ); ?>.</p>
<?php endif; ?>
