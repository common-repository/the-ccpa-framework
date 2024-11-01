<?php

namespace Data443\CCPA\Components\AdvancedIntegration;

use Data443\CCPA\Admin\AdminTab;

class AdminTabAdvancedIntegration extends AdminTab
{
    /* @var string */
    protected $slug = 'advanced-integration';

    /* @var PolicyGenerator */
    protected $policyGenerator;

    public function __construct()
    {
        $this->title = _x('Data Hound', '(Admin)', 'ccpa-framework');

        add_action('ccpa/admin/action/AdvancedIntegration/generate', [$this, 'generateAdvancedIntegration']);
    }

    public function init()
    {
        $this->registerSettingSection(
            'ccpa_section_privacy_policy',
            'Data Hound',
            [$this, 'renderHeader']
        );
    }

    public function renderHeader()
    {
        echo ccpa('view')->render('admin/advanced-integration/header');
    }

    public function renderSubmitButton()
    {
        // leave an empty method to prevent the placement of the default save button
    }
}
