<hr>


<section class="section">
	<h3 class="align-center">
		<?php echo esc_html_x( 'Need info?', '(Admin)', 'ccpa-framework' ); ?>
	</h3>
	<div class="row">
		        <div class="col">
          <div class="col_image" style="background-image:url('<?= ccpa('config')->get('plugin.url'); ?>assets/2.png');"></div>
            <a class="button button-primary" href="<?= ccpa('helpers')->knowledgeBase() ?>" target="_blank">
                <?= esc_html_x('Knowledge base', '(Admin)', 'ccpa-framework'); ?>
            </a>
            <p>
                <?= esc_html_x('Check out the knowledge base for common questions and answers.', '(Admin)', 'ccpa-framework'); ?>
            </p>
        </div>		
		<div class="col">
          <div class="col_image" style="background-image:url('<?= ccpa('config')->get('plugin.url'); ?>/assets/3.png');"></div>
            <a class="button button-primary" href="<?= ccpa('helpers')->developerDocs() ?>" target="_blank">
                <?= esc_html_x('Developer\'s guide to CCPA', '(Admin)', 'ccpa-framework'); ?>
            </a>
            <p>
                <?= esc_html_x('We have a thorough guide to help making custom sites compliant.', '(Admin)', 'ccpa-framework'); ?>
            </p>
        </div>
	</div>
</section>

<section class="section">
    <h3 class="align-center">
        <?= esc_html_x('Need help?', '(Admin)', 'ccpa-framework'); ?>
    </h3>
    <div class="row">
        <div class="col">
          <div class="col_image" style="background-image:url('<?= ccpa('config')->get('plugin.url'); ?>/assets/4.png');"></div>
            <a class="button button-primary" href="https://data443.atlassian.net/servicedesk/customer/portal/2" target="_blank">
                <?= esc_html_x('Submit a support request', '(Admin)', 'ccpa-framework'); ?>
            </a>
            <p>
                <?= esc_html_x('Found a bug or have a question about the plugin? Submit a support request and we’ll get right on it!', '(Admin)', 'ccpa-framework'); ?>
            </p>
        </div>
        <div class="col">
		  <div class="col_image" style="background-image:url('<?php echo ccpa( 'config' )->get( 'plugin.url' ); ?>/assets/images/5.png');"></div>
			<a class="button button-primary" href="<?php echo ccpa( 'helpers' )->docs( 'contact/' ); ?>" target="_blank">
				<?php echo esc_html_x( 'Request a consultation', '(Admin)', 'ccpa-framework' ); ?>
			</a>
			<p>
				<?php echo esc_html_x( 'Need assistance in making your site compliant? We can help!', '(Admin)', 'ccpa-framework' ); ?>
			</p>
		</div>
    </div>
</section>
