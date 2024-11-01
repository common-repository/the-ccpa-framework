<?php

namespace Data443\CCPA\Updater;

class Updater 
{
	public function __construct()
    {
        update_option('ccpa_plugin_version', CCPA_FRAMEWORK_VERSION);
	}
}