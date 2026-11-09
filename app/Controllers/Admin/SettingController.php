<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Config\Database;
use App\Models\SiteSetting;
use App\Models\AuditLog;
use App\Helpers\Sanitizer;

/**
 * ============================================================================
 * Class SettingController - Manajemen CMS Konten Website Dinamis
 * ============================================================================
 */
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
            'title'    => 'Kelola Konten Website (CMS) — K\'mplang Salatiga',
            'settings' => $settings
        ], 'admin');
    }

    public function update(): void
    {
        $data = Sanitizer::cleanInput($_POST);
        unset($data['_csrf_token']);

        // Gunakan transaksi atomik saat menyimpan seluruh pasangan key-value pengaturan
        Database::transaction(function () use ($data) {
            foreach ($data as $key => $value) {
                if (!is_string($key) || !is_scalar($value)) {
                    continue;
                }
                $group = match (true) {
                    str_starts_with($key, 'hero_')   => 'hero',
                    str_starts_with($key, 'about_')  => 'about',
                    str_starts_with($key, 'social_') || str_starts_with($key, 'contact_') || $key === 'address' => 'contact',
                    default                          => 'general'
                };
                $this->settingModel->set($key, (string) $value, $group);
            }
        });

        AuditLog::record($this->userId(), 'UPDATE_SETTINGS', 'site_settings', null, 'Admin updated website content settings');

        $this->setFlash('success', 'Seluruh perubahan konten website berhasil disimpan.');
        $this->redirect('/admin/settings');
    }
}
