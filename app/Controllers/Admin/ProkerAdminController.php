<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\WorkProgram;
use App\Models\AuditLog;
use App\Helpers\Sanitizer;

/**
 * ============================================================================
 * Class ProkerAdminController - Manajemen CRUD Program Kerja & Progres
 * ============================================================================
 * Mengelola penambahan, penyuntingan (termasuk persentase progres 0-100%,
 * indikator kualitas/kuantitas, anggaran, dan status), serta penghapusan proker.
 * ============================================================================
 */
class ProkerAdminController extends Controller
{
    private WorkProgram $prokerModel;

    public function __construct()
    {
        $this->prokerModel = new WorkProgram();
    }

    public function index(): void
    {
        $prokers = $this->prokerModel->all('divisi ASC, id ASC');
        $divisiList = $this->prokerModel->getAllDivisi();
        $stats = $this->prokerModel->getStats();

        $this->view('admin/proker', [
            'title'      => 'Kelola Program Kerja — K\'mplang Salatiga',
            'prokers'    => $prokers,
            'divisiList' => $divisiList,
            'stats'      => $stats
        ], 'admin');
    }

    public function store(): void
    {
        $data = Sanitizer::cleanInput($_POST);

        $rules = [
            'divisi'       => 'required|max:100',
            'nama_program' => 'required|min:3|max:255'
        ];

        $errors = $this->validate($data, $rules);
        if (!empty($errors)) {
            $this->setFlash('error', reset($errors));
            $this->redirect('/admin/proker');
        }

        $status = in_array($data['status'] ?? '', ['rencana', 'berlangsung', 'selesai', 'evaluasi'], true)
            ? $data['status']
            : 'rencana';

        // Sinkronisasi progres_persen otomatis jika status 'selesai'
        $progres = isset($data['progres_persen']) ? max(0, min(100, (int) $data['progres_persen'])) : 0;
        if ($status === 'selesai') {
            $progres = 100;
        }

        $id = $this->prokerModel->create([
            'divisi'              => $data['divisi'],
            'periode'             => !empty($data['periode']) ? $data['periode'] : '2026/2027',
            'nama_program'        => $data['nama_program'],
            'tujuan'              => $data['tujuan'] ?? '',
            'indikator_kualitas'  => $data['indikator_kualitas'] ?? '',
            'indikator_kuantitas' => $data['indikator_kuantitas'] ?? '',
            'gambaran_kegiatan'   => $data['gambaran_kegiatan'] ?? '',
            'waktu_kegiatan'      => $data['waktu_kegiatan'] ?? '',
            'anggaran'            => !empty($data['anggaran']) ? $data['anggaran'] : 'Rp 0',
            'penanggung_jawab'    => $data['penanggung_jawab'] ?? '',
            'status'              => $status,
            'progres_persen'      => $progres
        ]);

        AuditLog::record($this->userId(), 'CREATE_PROKER', 'work_programs', $id, "Added proker: {$data['nama_program']}");

        $this->setFlash('success', 'Program kerja baru berhasil ditambahkan.');
        $this->redirect('/admin/proker');
    }

    public function update(string $id): void
    {
        $prokerId = (int) $id;
        if (!$this->prokerModel->exists($prokerId)) {
            $this->setFlash('error', 'Data program kerja tidak ditemukan.');
            $this->redirect('/admin/proker');
        }

        $data = Sanitizer::cleanInput($_POST);
        $status = in_array($data['status'] ?? '', ['rencana', 'berlangsung', 'selesai', 'evaluasi'], true)
            ? $data['status']
            : 'rencana';

        $progres = isset($data['progres_persen']) ? max(0, min(100, (int) $data['progres_persen'])) : 0;
        if ($status === 'selesai') {
            $progres = 100;
        }

        $this->prokerModel->update($prokerId, [
            'divisi'              => $data['divisi'],
            'periode'             => !empty($data['periode']) ? $data['periode'] : '2026/2027',
            'nama_program'        => $data['nama_program'],
            'tujuan'              => $data['tujuan'] ?? '',
            'indikator_kualitas'  => $data['indikator_kualitas'] ?? '',
            'indikator_kuantitas' => $data['indikator_kuantitas'] ?? '',
            'gambaran_kegiatan'   => $data['gambaran_kegiatan'] ?? '',
            'waktu_kegiatan'      => $data['waktu_kegiatan'] ?? '',
            'anggaran'            => !empty($data['anggaran']) ? $data['anggaran'] : 'Rp 0',
            'penanggung_jawab'    => $data['penanggung_jawab'] ?? '',
            'status'              => $status,
            'progres_persen'      => $progres
        ]);

        AuditLog::record($this->userId(), 'UPDATE_PROKER', 'work_programs', $prokerId, "Updated proker #{$prokerId}: {$data['nama_program']}");

        $this->setFlash('success', 'Data program kerja dan progres berhasil diperbarui.');
        $this->redirect('/admin/proker');
    }

    public function delete(string $id): void
    {
        $prokerId = (int) $id;
        $this->prokerModel->delete($prokerId);
        AuditLog::record($this->userId(), 'DELETE_PROKER', 'work_programs', $prokerId, "Deleted proker ID: {$prokerId}");

        $this->setFlash('success', 'Program kerja berhasil dihapus.');
        $this->redirect('/admin/proker');
    }
}
