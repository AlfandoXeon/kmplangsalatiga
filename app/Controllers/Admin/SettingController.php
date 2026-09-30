<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\SiteSetting;
use App\Models\AuditLog;
use App\Helpers\Sanitizer;

class SettingController extends Controller
{
    private SiteSetting $settingModel;

    public function __construct()
    {
        $this->settingModel = new SiteSetting();
    }

    public function index(): void
    {
        $settings = $this->settingModel->getAllKeyValue();

        $this->view('admin/settings', [
            'title'     => 'Kelola Konten & Struktur Web - K\'mplang Salatiga',
            'settings'  => $settings,
            'enableAos' => false
        ], 'admin');
    }

    public function update(): void
    {
        $data = Sanitizer::cleanInput($_POST);
        unset($data['_csrf_token']);

        foreach ($data as $key => $value) {
            $group = match (true) {
                str_starts_with($key, 'hero_') => 'hero',
                str_starts_with($key, 'about_') => 'about',
                str_starts_with($key, 'social_') || str_starts_with($key, 'contact_') || $key === 'address' => 'contact',
                default => 'general'
            };
            $this->settingModel->set($key, $value, $group);
        }

        AuditLog::record($this->userId(), 'UPDATE_SETTINGS', 'site_settings', null, 'Admin updated website content settings');

        $this->setFlash('success', 'Konten website berhasil diperbarui.');
        $this->redirect('/admin/settings');
    }
}
