<?php

require_once(dirname(__FILE__).'/src/Singleton.php');

function ccpa($name)
{
    global $ccpa;
    if ($name === 'admin-notice') {
        return $ccpa->AdminNotice;
    } elseif ($name === 'themes') {
        return $ccpa->Themes;
    } elseif ($name === 'view') {
        return $ccpa->View;
    } elseif ($name === 'helpers') {
        return $ccpa->Helpers;
    } elseif ($name === 'admin-error') {
        return $ccpa->AdminError;
    } elseif ($name === 'options') {
        return $ccpa->Options;
    } elseif ($name === 'consent') {
        return $ccpa->Consent;
    } elseif ($name === 'data-subject') {
        return $ccpa->DataSubject;
    } elseif ($name === 'controller') {
        return $ccpa->Controller;
    } elseif ($name === 'config') {
        return $ccpa->Configuration;
    } elseif ($name === 'privacy-safe') {
        return $ccpa->PrivacySafe;
    }
    die("Unknown name in ccpa: " . $name);
}

add_action('init', function() {

    if (!is_admin()) {
        return;
    }

    new \Data443\CCPA\SetupAdmin();
}, 0);

include_once(dirname(__FILE__).'/src/Updater/Updater.php');

/**
 * Start the plugin on plugins_loaded at priority 0.
 */
add_action('plugins_loaded', function () use ($ccpa_error) {
    
    new \Data443\CCPA\Updater\Updater();

    global $ccpa;
    $ccpa = new \Data443\CCPA\Singleton();
    $ccpa->init(__FILE__);

}, 0);
