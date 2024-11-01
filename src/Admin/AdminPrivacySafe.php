<?php

namespace Data443\CCPA\Admin;

use Data443\CCPA\Admin\AdminNotice;

class AdminPrivacySafe extends AdminNotice
{
    public function render()
    {
        if (!$this->template) {
            return;
        }

        echo ccpa('view')->render('admin/notices/header-privacy-safe');
        echo ccpa('view')->render($this->template, $this->data);
        echo ccpa('view')->render('admin/notices/footer-step');
    }
}