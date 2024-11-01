<?php

namespace Data443\CCPA;

/**
 * General Configuration Variables
 *
 * Class Configuration
 *
 * @package Data443\CCPA
 */
class Configuration
{
    public function get($name)
    {
        global $ccpa;
        if ($name === 'plugin.url') {
            return $ccpa->PluginUrl;
        } elseif ($name === 'plugin.path') {
            return $ccpa->PluginPath;
        } elseif ($name === 'plugin.template_path') {
            return $ccpa->PluginTemplatePath;
        } elseif ($name === 'installer.wizardUrl') {
            return $ccpa->InstallerWizardUrl;
        } elseif ($name === 'help.url') {
            return $ccpa->HelpUrl;
        }
        die ("Unknown configuration variable: {$name}");
    }
}
