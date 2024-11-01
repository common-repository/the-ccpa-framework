<input type="url" name="ccpa_custom_tools_page" value="<?php if($content!=""){?>
<?= esc_html_x($content, 'ccpa-framework');?>
<?php } ?>" />
<p class="description">
    <?= esc_html_x('Leave blank if privacy tools page already selected', '(Admin)', 'ccpa-framework'); ?>
</p>
