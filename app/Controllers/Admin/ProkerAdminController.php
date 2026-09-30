<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\WorkProgram;
use App\Models\AuditLog;
use App\Helpers\Sanitizer;

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

        $this->view('admin/proker', [
            'title'      => 'Kelola Program Kerja - K\'mplang Salatiga',
            'prokers'    => $prokers,
            'divisiList' => $divisiList,
            'enableAos'  => false
        ], 'admin');
    }

    public function store(): void
    {
        $data = Sanitizer::cleanInput($_POST);

        $rules = [
            'divisi'       => 'required',
            'nama_program' => 'required|min:3|max:255'
        ];

        $errors = $this->validate($data, $rules);
        if (!empty($errors)) {
            $this->setFlash('error', reset($errors));
            $this->redirect('/admin/proker');
        }

        $id = $this->prokerModel->create([
            'divisi'              => $data['divisi'],
            'nama_program'        => $data['nama_program'],
            'tujuan'              => $data['tujuan'] ?? '',
            'indikator_kualitas'  => $data['indikator_kualitas'] ?? '',
            'indikator_kuantitas' => $data['indikator_kuantitas'] ?? '',
            'gambaran_kegiatan'   => $data['gambaran_kegiatan'] ?? '',
            'waktu_kegiatan'      => $data['waktu_kegiatan'] ?? '',
            'anggaran'            => $data['anggaran'] ?? 'Rp 0',
            'penanggung_jawab'    => $data['penanggung_jawab'] ?? '',
            'status'              => $data['status'] ?? 'rencana'
        ]);

        AuditLog::record($this->userId(), 'CREATE_PROKER', 'work_programs', $id, "Added proker: {$data['nama_program']}");

        $this->setFlash('success', 'Program kerja berhasil ditambahkan.');
        $this->redirect('/admin/proker');
    }

    public function update(string $id): void
    {
        $prokerId = (int) $id;
        $data = Sanitizer::cleanInput($_POST);

        $this->prokerModel->update($prokerId, [
            'divisi'              => $data['divisi'],
            'nama_program'        => $data['nama_program'],
            'tujuan'              => $data['tujuan'] ?? '',
            'indikator_kualitas'  => $data['indikator_kualitas'] ?? '',
            'indikator_kuantitas' => $data['indikator_kuantitas'] ?? '',
            'gambaran_kegiatan'   => $data['gambaran_kegiatan'] ?? '',
            'waktu_kegiatan'      => $data['waktu_kegiatan'] ?? '',
            'anggaran'            => $data['anggaran'] ?? 'Rp 0',
            'penanggung_jawab'    => $data['penanggung_jawab'] ?? '',
            'status'              => $data['status'] ?? 'rencana'
        ]);

        AuditLog::record($this->userId(), 'UPDATE_PROKER', 'work_programs', $prokerId, "Updated proker ID: $prokerId");

        $this->setFlash('success', 'Program kerja berhasil diperbarui.');
        $this->redirect('/admin/proker');
    }

    public function delete(string $id): void
    {
        $prokerId = (int) $id;
        $this->prokerModel->delete($prokerId);
        AuditLog::record($this->userId(), 'DELETE_PROKER', 'work_programs', $prokerId, "Deleted proker ID: $prokerId");

        $this->setFlash('success', 'Program kerja berhasil dihapus.');
        $this->redirect('/admin/proker');
    }
}
