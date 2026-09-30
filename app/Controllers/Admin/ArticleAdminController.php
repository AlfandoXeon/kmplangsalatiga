<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\Article;
use App\Models\AuditLog;
use App\Helpers\FileUpload;
use App\Helpers\Sanitizer;

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
            'title'     => 'Kelola Artikel & Sejarah - K\'mplang Salatiga',
            'articles'  => $articles,
            'enableAos' => false
        ], 'admin');
    }

    public function create(): void
    {
        $this->view('admin/articles/create', [
            'title'     => 'Tulis Artikel Baru - K\'mplang Salatiga',
            'enableAos' => false
        ], 'admin');
    }

    public function store(): void
    {
        $judul = trim($_POST['judul'] ?? '');
        $kategori = trim($_POST['kategori'] ?? 'Berita');
        $konten = $_POST['konten'] ?? '';
        $status = $_POST['status'] ?? 'published';

        if (empty($judul) || empty($konten)) {
            $this->setFlash('error', 'Judul dan isi artikel wajib diisi.');
            $this->redirect('/admin/articles/create');
        }

        $slug = Sanitizer::slugify($judul);
        $check = $this->articleModel->findBy('slug', $slug);
        if ($check) {
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
            'judul'       => $judul,
            'slug'        => $slug,
            'kategori'    => $kategori,
            'konten'      => $konten,
            'cover_image' => $coverName,
            'author_id'   => $this->userId(),
            'status'      => $status
        ]);

        AuditLog::record($this->userId(), 'CREATE_ARTICLE', 'articles', $id, "Created article: $judul");

        $this->setFlash('success', 'Artikel berhasil dipublikasikan!');
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
            'title'     => 'Edit Artikel - K\'mplang Salatiga',
            'article'   => $article,
            'enableAos' => false
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
        $kategori = trim($_POST['kategori'] ?? 'Berita');
        $konten = $_POST['konten'] ?? '';
        $status = $_POST['status'] ?? 'published';

        $coverName = $article['cover_image'];
        if (!empty($_FILES['cover']['name'])) {
            $upload = FileUpload::upload($_FILES['cover'], 'articles');
            if ($upload['success']) {
                $coverName = $upload['file_name'];
            }
        }

        $this->articleModel->update($artId, [
            'judul'       => $judul,
            'kategori'    => $kategori,
            'konten'      => $konten,
            'cover_image' => $coverName,
            'status'      => $status
        ]);

        AuditLog::record($this->userId(), 'UPDATE_ARTICLE', 'articles', $artId, "Updated article: $judul");

        $this->setFlash('success', 'Artikel berhasil diperbarui.');
        $this->redirect('/admin/articles');
    }

    public function delete(string $id): void
    {
        $artId = (int) $id;
        $this->articleModel->delete($artId);
        AuditLog::record($this->userId(), 'DELETE_ARTICLE', 'articles', $artId, "Deleted article ID: $artId");

        $this->setFlash('success', 'Artikel berhasil dihapus.');
        $this->redirect('/admin/articles');
    }
}
