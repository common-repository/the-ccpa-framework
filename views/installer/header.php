<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta name="viewport" content="width=device-width" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>
		<?php echo esc_html_x( 'WordPress CCPA &rsaquo; Setup Wizard', '(Admin)', 'ccpa-framework' ); ?>
	</title>
	<?php wp_print_scripts( array( 'jquery' ) ); ?>
	<?php do_action( 'admin_print_styles' ); ?>
	<?php do_action( 'admin_head' ); ?>
</head>

<body class="ccpa-installer wp-core-ui">

	<div class="container ccpa-installer-container">
		<div class="ccpa-header">
		  <div class="ccpa-header_left">
			<img class="ccpa-logo" src="<?php echo ccpa( 'config' )->get( 'plugin.url' ); ?>/assets/images/data443.png" />
		  </div>
		  <div class="ccpa-header_right">
			<h1>
			  <?php echo esc_html_x( 'The CCPA Framework', '(Admin)', 'ccpa-framework' ); ?>
			</h1>
		  </div>
		</div>
		<div class="ccpa-breadcrumbs">
		  <div class="ccpa-breadcrumbs_unit <?php echo $activeSteps > 0 ? 'active' : ''; ?>">
			<div class="ccpa-breadcrumbs_item">
			  <?php echo esc_html_x( 'Configuration', '(Admin)', 'ccpa-framework' ); ?>
			</div>
		  </div>
		  <div class="ccpa-breadcrumbs_unit <?php echo $activeSteps > 1 ? 'active' : ''; ?>">
			<div class="ccpa-breadcrumbs_item">
			  <?php echo esc_html_x( 'Privacy Policy', '(Admin)', 'ccpa-framework' ); ?>
			</div>
		  </div>
		  <div class="ccpa-breadcrumbs_unit <?php echo $activeSteps > 2 ? 'active' : ''; ?>">
			<div class="ccpa-breadcrumbs_item">
			  <?php echo esc_html_x( 'Forms & Consent', '(Admin)', 'ccpa-framework' ); ?>
			</div>
		  </div>
		  <div class="ccpa-breadcrumbs_unit <?php echo $activeSteps > 3 ? 'active' : ''; ?>">
			<div class="ccpa-breadcrumbs_item">
			  <?php echo esc_html_x( 'Integrations', '(Admin)', 'ccpa-framework' ); ?>
			</div>
		  </div>
		</div>

		<div class="ccpa-content">

			<?php if ( isset( $_GET['ccpa-error'] ) ) : ?>
				<p class="error">Failed to validate nonce! Please reload page and try again.</p>
			<?php endif; ?>

			<!-- Open the installer form -->
			<form method="POST">
				<input type="hidden" name="ccpa-installer" value="next" />
