<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\Activity;
use App\Models\ActivityMedia;
use App\Models\AuditLog;
use App\Helpers\FileUpload;
use App\Helpers\Sanitizer;

class ActivityAdminController extends Controller
{
    private Activity $activityModel;
    private ActivityMedia $mediaModel;

    public function __construct()
    {
        $this->activityModel = new Activity();
        $this->mediaModel = new ActivityMedia();
    }

    public function index(): void
    {
        $activities = $this->activityModel->all('tanggal_kegiatan DESC, id DESC');

        $this->view('admin/activities/index', [
            'title'      => 'Kelola Kegiatan & Dokumentasi - K\'mplang Salatiga',
            'activities' => $activities,
            'enableAos'  => false
        ], 'admin');
    }

    public function create(): void
    {
        $this->view('admin/activities/create', [
            'title'     => 'Tambah Kegiatan Baru - K\'mplang Salatiga',
            'enableAos' => false
        ], 'admin');
    }

    public function store(): void
    {
        $judul = trim($_POST['judul'] ?? '');
        $deskripsi = trim($_POST['deskripsi'] ?? '');
        $tanggal = $_POST['tanggal_kegiatan'] ?? date('Y-m-d');
        $lokasi = trim($_POST['lokasi'] ?? '');

        if (empty($judul)) {
            $this->setFlash('error', 'Judul kegiatan wajib diisi.');
            $this->redirect('/admin/activities/create');
        }

        $slug = Sanitizer::slugify($judul);
        // Ensure slug uniqueness
        $check = $this->activityModel->findBy('slug', $slug);
        if ($check) {
            $slug .= '-' . time();
        }

        // Handle cover image
        $coverName = null;
        if (!empty($_FILES['cover']['name'])) {
            $uploadResult = FileUpload::upload($_FILES['cover'], 'activities');
            if ($uploadResult['success']) {
                $coverName = $uploadResult['file_name'];
            }
        }

        $activityId = $this->activityModel->create([
            'judul'            => $judul,
            'slug'             => $slug,
            'deskripsi'        => $deskripsi,
            'tanggal_kegiatan' => $tanggal,
            'lokasi'           => $lokasi,
            'cover_image'      => $coverName,
            'author_id'        => $this->userId()
        ]);

        // Handle multiple media uploads for the Google Drive Album!
        $this->handleMultiMediaUpload($activityId);

        AuditLog::record($this->userId(), 'CREATE_ACTIVITY', 'activities', $activityId, "Created activity: $judul");

        $this->setFlash('success', 'Kegiatan & album dokumentasi berhasil dipublikasikan!');
        $this->redirect('/admin/activities');
    }

    public function edit(string $id): void
    {
        $actId = (int) $id;
        $activity = $this->activityModel->find($actId);

        if (!$activity) {
            $this->setFlash('error', 'Kegiatan tidak ditemukan.');
            $this->redirect('/admin/activities');
        }

        $mediaList = $this->mediaModel->getByActivityId($actId);

        $this->view('admin/activities/edit', [
            'title'     => 'Edit Kegiatan - K\'mplang Salatiga',
            'activity'  => $activity,
            'mediaList' => $mediaList,
            'enableAos' => false
        ], 'admin');
    }

    public function update(string $id): void
    {
        $actId = (int) $id;
        $activity = $this->activityModel->find($actId);

        if (!$activity) {
            $this->setFlash('error', 'Kegiatan tidak ditemukan.');
            $this->redirect('/admin/activities');
        }

        $judul = trim($_POST['judul'] ?? '');
        $deskripsi = trim($_POST['deskripsi'] ?? '');
        $tanggal = $_POST['tanggal_kegiatan'] ?? date('Y-m-d');
        $lokasi = trim($_POST['lokasi'] ?? '');

        $coverName = $activity['cover_image'];
        if (!empty($_FILES['cover']['name'])) {
            $uploadResult = FileUpload::upload($_FILES['cover'], 'activities');
            if ($uploadResult['success']) {
                $coverName = $uploadResult['file_name'];
            }
        }

        $this->activityModel->update($actId, [
            'judul'            => $judul,
            'deskripsi'        => $deskripsi,
            'tanggal_kegiatan' => $tanggal,
            'lokasi'           => $lokasi,
            'cover_image'      => $coverName
        ]);

        // Add any newly uploaded media files
        $this->handleMultiMediaUpload($actId);

        AuditLog::record($this->userId(), 'UPDATE_ACTIVITY', 'activities', $actId, "Updated activity: $judul");

        $this->setFlash('success', 'Kegiatan dan media album berhasil diperbarui.');
        $this->redirect('/admin/activities/edit/' . $actId);
    }

    public function deleteMedia(string $mediaId): void
    {
        $id = (int) $mediaId;
        $media = $this->mediaModel->find($id);

        if ($media) {
            $actId = $media['activity_id'];
            $file = realpath(__DIR__ . '/../../../storage/uploads/activities/' . $media['file_path']);
            if ($file && file_exists($file)) {
                @unlink($file);
            }
            $this->mediaModel->delete($id);
            AuditLog::record($this->userId(), 'DELETE_MEDIA', 'activity_media', $id, "Deleted media: {$media['original_name']}");
            $this->setFlash('success', 'Berkas media berhasil dihapus dari album.');
            $this->redirect('/admin/activities/edit/' . $actId);
        }

        $this->redirect('/admin/activities');
    }

    public function delete(string $id): void
    {
        $actId = (int) $id;
        $mediaList = $this->mediaModel->getByActivityId($actId);

        foreach ($mediaList as $item) {
            $file = realpath(__DIR__ . '/../../../storage/uploads/activities/' . $item['file_path']);
            if ($file && file_exists($file)) {
                @unlink($file);
            }
        }

        $activity = $this->activityModel->find($actId);
        if ($activity && !empty($activity['cover_image'])) {
            $coverFile = realpath(__DIR__ . '/../../../storage/uploads/activities/' . $activity['cover_image']);
            if ($coverFile && file_exists($coverFile)) {
                @unlink($coverFile);
            }
        }

        $this->activityModel->delete($actId);
        AuditLog::record($this->userId(), 'DELETE_ACTIVITY', 'activities', $actId, "Deleted activity ID: $actId");

        $this->setFlash('success', 'Kegiatan dan seluruh berkas album berhasil dihapus.');
        $this->redirect('/admin/activities');
    }

    private function handleMultiMediaUpload(int $activityId): void
    {
        if (empty($_FILES['media']['name']) || !is_array($_FILES['media']['name'])) {
            return;
        }

        $count = count($_FILES['media']['name']);
        for ($i = 0; $i < $count; $i++) {
            if (empty($_FILES['media']['name'][$i])) {
                continue;
            }

            $singleFile = [
                'name'     => $_FILES['media']['name'][$i],
                'type'     => $_FILES['media']['type'][$i],
                'tmp_name' => $_FILES['media']['tmp_name'][$i],
                'error'    => $_FILES['media']['error'][$i],
                'size'     => $_FILES['media']['size'][$i]
            ];

            $res = FileUpload::upload($singleFile, 'activities');
            if ($res['success']) {
                $this->mediaModel->create([
                    'activity_id'   => $activityId,
                    'file_path'     => $res['file_name'],
                    'original_name' => $res['original_name'],
                    'file_type'     => $res['file_type'],
                    'mime_type'     => $res['mime_type'],
                    'file_size'     => $res['file_size']
                ]);
            }
        }
    }
}
