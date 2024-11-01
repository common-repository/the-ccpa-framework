<?php


namespace Data443\CCPA\Installer\Steps;

use Data443\CCPA\Installer\InstallerStep;
use Data443\CCPA\Installer\InstallerStepInterface;

class PolicyContents extends InstallerStep implements InstallerStepInterface {

	protected $slug = 'policy-contents';

	protected $type = 'wizard';

	protected $template = 'installer/steps/policy-contents';

	protected $activeSteps = 2;

	protected function renderContent() {
		$policyUrl = get_permalink( ccpa( 'options' )->get( 'policy_page' ) );
		add_filter( 'ccpa_custom_policy_link', 'ccpafPrivacyPolicyurl' );
		$policyUrl       = apply_filters( 'ccpa_custom_policy_link', $policyUrl );
		$editPolicyUrl   = get_edit_post_link( ccpa( 'options' )->get( 'policy_page' ) );
		$policyGenerated = ccpa( 'options' )->get( 'policy_generated' );

		echo ccpa( 'view' )->render(
			$this->template,
			compact( 'policyUrl', 'editPolicyUrl', 'policyGenerated' )
		);
	}
}
