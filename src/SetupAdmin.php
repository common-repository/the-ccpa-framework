<?php

namespace Data443\CCPA;

use Data443\CCPA\Admin\AdminError;
use Data443\CCPA\Admin\AdminNotice;
use Data443\CCPA\Admin\Modal;
use Data443\CCPA\Admin\WordpressAdmin;
use Data443\CCPA\Admin\WordpressAdminPage;
use Data443\CCPA\Components\Consent\ConsentAdmin;
use Data443\CCPA\Components\CookiePopup\CookiePopup;
use Data443\CCPA\Installer\Installer;
use Data443\CCPA\Installer\AdminInstallerNotice;
use Data443\CCPA\Admin\AdminPrivacySafe;
use Data443\CCPA\DataSubject\DataSubjectAdmin;
use Data443\CCPA\Components\PrivacyPolicy\PrivacyPolicy;
use Data443\CCPA\Components\DoNotSell\DoNotSell;
use Data443\CCPA\Components\Support\Support;
use Data443\CCPA\Components\AdvancedIntegration\AdvancedIntegration;
use Data443\CCPA\Components\PrivacySafe\PrivacySafe;

/**
 * Register and set up admin components.
 * This class is instantiated at admin_init priority 0
 *
 * Class SetupAdmin
 *
 * @package Data443\CCPA
 */
class SetupAdmin
{
    public function __construct()
    {
        $this->registerComponents();
        $this->runComponents();
    }

    /**
     * Register components in the container
     */
    protected function registerComponents()
    {
        global $ccpa;
        $ccpa->AdminNotice = new AdminNotice();
        $ccpa->AdminError = new AdminError();
        $ccpa->AdminInstallerNotice = new AdminInstallerNotice();
        $ccpa->PrivacySafe = new AdminPrivacySafe();
        $ccpa->AdminModal = new Modal();
        $ccpa->AdminPage = new WordpressAdminPage();
        $ccpa->AdminTabGeneral = new Admin\AdminTabGeneral();
    }

    protected function runComponents()
    {
        global $ccpa;
        new WordpressAdmin($ccpa->AdminPage);
        new Installer($ccpa->AdminTabGeneral);
        new CookiePopup();
        new ConsentAdmin();
        new DataSubjectAdmin();
        new PrivacyPolicy();
        new DoNotSell();
        new Support();
        new AdvancedIntegration();
        new PrivacySafe();
    }
}
