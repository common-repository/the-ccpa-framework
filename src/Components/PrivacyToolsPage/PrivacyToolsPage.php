<?php

namespace Data443\CCPA\Components\PrivacyToolsPage;

class PrivacyToolsPage {

    public function __construct() {
        global $ccpa;
        $controller = new PrivacyToolsPageController(
            $ccpa->DataSubjectAuthenticator
            , $ccpa->DataSubjectIdentificator
            , $ccpa->DataSubject
            , $ccpa->DataExporter
            , $ccpa->UserConsentModel
            );
        $ccpa->Controller = $controller;
        new PrivacyToolsPageShortcode($controller, $ccpa->Consent);
    }
}
