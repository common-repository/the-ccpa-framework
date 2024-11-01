<h2><?php echo ( ccpa( 'options' )->get( 'ccpa_delete_text' ) != '' ) ? ccpa( 'options' )->get( 'ccpa_delete_text' ) : __( 'Delete my user and data', 'ccpa-framework' ); ?></h2>
<br/>
<p class="description">
	<?php echo __( 'Delete all data we have gathered about you.', 'ccpa-framework' ); ?> <br/>
	<?php echo __( 'If you have a user account on our site, it will also be deleted.', 'ccpa-framework' ); ?> <br/>
	<?php echo __( 'Be careful - this action is permanent and CANNOT be undone.', 'ccpa-framework' ); ?>
	<?php if ( ccpa( 'options' )->get( 'enable_woo_compatibility' ) && class_exists( 'Woocommerce' ) ) { ?>
		<br/><strong class="ccpa_woo_note"><?php echo __( 'Note Regarding Order:', 'ccpa-framework' ); ?></strong><br/>
		<?php echo __( 'Your order with status Processing will not get deleted until status change.', 'ccpa-framework' ); ?><br/>
		<?php echo __( 'Your order with status Completed will get anonymize.', 'ccpa-framework' ); ?><br/>
		<?php echo __( "If you delete Completed order you can't apply for refund.", 'ccpa-framework' ); ?><br/>
	<?php } ?>
</p>
<br/>
<div class="ccpa-delete-button">
<?php add_thickbox(); ?>

<a href="#TB_inline?width=600&height=239&inlineId=ccpamodal-window-id" class="thickbox button button-primary"><?php echo __( 'Delete my data', 'ccpa-framework' ); ?></a>

<div id="ccpamodal-window-id" style="display:none;">
	<center>
	<form method="GET">
		<p class="description">
			<?php echo __( 'Delete all data we have gathered about you.', 'ccpa-framework' ); ?> <br/>
			<?php echo __( 'If you have a user account on our site, it will also be deleted.', 'ccpa-framework' ); ?> <br/>
			<?php echo __( 'Be careful - this action is permanent and CANNOT be undone.', 'ccpa-framework' ); ?>
		</p>
			<input type="hidden" name="ccpa_nonce" value="<?php echo esc_attr( $nonce ); ?>"/>
			<input type="hidden" name="ccpa_action" value="<?php echo esc_attr( $action ); ?>"/>
			<input type="submit" class="button button-primary" value="<?php echo __( 'Delete my data', 'ccpa-framework' ); ?>"/>
	</form>
	<center>
</div>

   
</div>


<hr>
