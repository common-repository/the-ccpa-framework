
<input type="text" name="ccpa_unknown_user_message" value="<?php if($content!=""){?>
<?= esc_html_x($content, 'ccpa-framework');?>
<?php } ?>" />
<p class="description">
    <?= esc_html_x('This message is displayed if the email entered on the privacy tools page is not found.', '(Admin)', 'ccpa-framework'); ?>
</p>