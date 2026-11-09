<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\Article;
use App\Models\AuditLog;
use App\Helpers\FileUpload;
use App\Helpers\Formatter;
use App\Helpers\Sanitizer;

/**
 * ============================================================================
 * Class ArticleAdminController - Manajemen Artikel Budaya, Sejarah & Berita
 * ============================================================================
 * Secara otomatis menghitung `waktu_baca_menit` berdasarkan jumlah kata dan
 * membangun `ringkasan` editorial apabila tidak diisi manual.
 * ============================================================================
 */
class ArticleAdminController extends Controller
{
    private Article $articleModel;

    public function __construct()
    {
        $this->articleModel = new Article();
    }

    public function index(): void
    {
        $articles = $this->articleModel->all('id DESC');

        $this->view('admin/articles/index', [
            'title'    => 'Kelola Artikel & Sejarah — K\'mplang Salatiga',
            'articles' => $articles
        ], 'admin');
    }

    public function create(): void
    {
        $this->view('admin/articles/create', [
            'title' => 'Tulis Artikel Baru — K\'mplang Salatiga'
        ], 'admin');
    }

    public function store(): void
    {
        $judul = trim($_POST['judul'] ?? '');
        $kategori = trim($_POST['kategori'] ?? 'Budaya');
        $ringkasan = trim($_POST['ringkasan'] ?? '');
        $konten = trim($_POST['konten'] ?? '');
        $status = in_array($_POST['status'] ?? '', ['published', 'draft'], true) ? $_POST['status'] : 'published';

        if ($judul === '' || $konten === '') {
            $this->setFlash('error', 'Judul dan isi artikel wajib diisi.');
            $this->redirect('/admin/articles/create');
        }

        // Bangun ringkasan otomatis jika dikosongkan
        if ($ringkasan === '') {
            $ringkasan = Formatter::potongTeks($konten, 180);
        }

        // Hitung estimasi waktu baca (menit)
        $readingTime = Article::estimateReadingTime($konten);

        $slug = Sanitizer::slugify($judul);
        if ($this->articleModel->findBy('slug', $slug)) {
            $slug .= '-' . time();
        }

        $coverName = null;
        if (!empty($_FILES['cover']['name'])) {
            $upload = FileUpload::upload($_FILES['cover'], 'articles');
            if ($upload['success']) {
                $coverName = $upload['file_name'];
            }
        }

        $id = $this->articleModel->create([
            'judul'            => $judul,
            'slug'             => $slug,
            'kategori'         => $kategori,
            'ringkasan'        => $ringkasan,
            'konten'           => $konten,
            'cover_image'      => $coverName,
            'author_id'        => $this->userId(),
            'status'           => $status,
            'waktu_baca_menit' => $readingTime
        ]);

        AuditLog::record($this->userId(), 'CREATE_ARTICLE', 'articles', $id, "Created article: {$judul}");

        $this->setFlash('success', 'Artikel berhasil disimpan.');
        $this->redirect('/admin/articles');
    }

    public function edit(string $id): void
    {
        $artId = (int) $id;
        $article = $this->articleModel->find($artId);

        if (!$article) {
            $this->setFlash('error', 'Artikel tidak ditemukan.');
            $this->redirect('/admin/articles');
        }

        $this->view('admin/articles/edit', [
            'title'   => 'Edit Artikel — K\'mplang Salatiga',
            'article' => $article
        ], 'admin');
    }

    public function update(string $id): void
    {
        $artId = (int) $id;
        $article = $this->articleModel->find($artId);

        if (!$article) {
            $this->setFlash('error', 'Artikel tidak ditemukan.');
            $this->redirect('/admin/articles');
        }

        $judul = trim($_POST['judul'] ?? '');
        $kategori = trim($_POST['kategori'] ?? 'Budaya');
        $ringkasan = trim($_POST['ringkasan'] ?? '');
        $konten = trim($_POST['konten'] ?? '');
        $status = in_array($_POST['status'] ?? '', ['published', 'draft'], true) ? $_POST['status'] : 'published';

        if ($ringkasan === '') {
            $ringkasan = Formatter::potongTeks($konten, 180);
        }

        $readingTime = Article::estimateReadingTime($konten);

        $coverName = $article['cover_image'];
        if (!empty($_FILES['cover']['name'])) {
            $upload = FileUpload::upload($_FILES['cover'], 'articles');
            if ($upload['success']) {
                $coverName = $upload['file_name'];
            }
        }

        $this->articleModel->update($artId, [
            'judul'            => $judul,
            'kategori'         => $kategori,
            'ringkasan'        => $ringkasan,
            'konten'           => $konten,
            'cover_image'      => $coverName,
            'status'           => $status,
            'waktu_baca_menit' => $readingTime
        ]);

        AuditLog::record($this->userId(), 'UPDATE_ARTICLE', 'articles', $artId, "Updated article: {$judul}");

        $this->setFlash('success', 'Artikel berhasil diperbarui.');
        $this->redirect('/admin/articles');
    }

    public function delete(string $id): void
    {
        $artId = (int) $id;
        $this->articleModel->delete($artId);
        AuditLog::record($this->userId(), 'DELETE_ARTICLE', 'articles', $artId, "Deleted article ID: {$artId}");

        $this->setFlash('success', 'Artikel berhasil dihapus.');
        $this->redirect('/admin/articles');
    }
}
