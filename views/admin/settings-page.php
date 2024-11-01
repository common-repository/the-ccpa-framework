<div class="wrap ccpa-framework-wrap">
	<h2>
		<?php echo esc_html_x( 'The CCPA Framework By Data443', '(Admin)', 'ccpa-framework' ); ?>
	</h2>

	<?php if ( ! empty( $_GET['updated'] ) ) : ?>
		<div id="setting-error-settings_updated" class="updated settings-error notice is-dismissible">
			<p><strong><?php _ex( 'CCPA settings saved!', '(Admin)', 'ccpa-framework' ); ?></strong></p>
		</div>
	<?php endif; ?>

	<?php if ( count( $tabs ) ) : ?>
		<nav class="nav-tab-wrapper">
			<?php foreach ( $tabs as $slug => $tab ) : ?>
				<a href="<?php echo esc_url( $tab['url'] ); ?>" class="nav-tab <?php echo $tab['slug'].' '; echo $tab['active'] ? 'nav-tab-active' : ''; ?>">
					<?php echo esc_html( $tab['title'] ); ?>
				</a>
			<?php endforeach; ?>
		</nav>
	<?php endif; ?>

	<form action="options.php" method="POST" class="ccpa_frame">
	  <?php echo $currentTabContents; ?>
	</form>

	<?php if ( $signature ) : ?>
		<hr>
		<p>
			<em>
				<?php
				echo sprintf(
					esc_html_x( 'The CCPA Framework. Built with &#9829; by %1$sData443%2$s.', '(Admin)', 'ccpa-framework' ),
					'<a href="https://www.data443.com/" target="_blank">',
					'</a>'
				);
				?>
				 &nbsp;
				|
				&nbsp;
				<?php
				echo sprintf(
					esc_html_x( 'Need help? Open a support ticket %1$shere%2$s.', '(Admin)', 'ccpa-framework' ),
					'<a href="https://data443.atlassian.net/servicedesk/customer/portal/2" target="_blank">',
					'</a>'
				);
				?>
				&nbsp;
				|
				&nbsp;
				<?php
				echo sprintf(
					esc_html_x( 'Support our development efforts! leave a %1$s5-star rating%2$s.', '(Admin)', 'ccpa-framework' ),
					'<a href="https://wordpress.org/plugins/the-ccpa-framework/#reviews" target="_blank">',
					'</a>'
				);
				?>
			</em>
		</p>
	<?php endif; ?>
</div>
