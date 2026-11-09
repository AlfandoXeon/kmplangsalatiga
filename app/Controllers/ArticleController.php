<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Article;
use App\Models\Comment;

/**
 * ============================================================================
 * Class ArticleController - Pengendali Artikel Budaya, Sejarah & Berita
 * ============================================================================
 */
class ArticleController extends Controller
{
    private Article $articleModel;
    private Comment $commentModel;

    public function __construct()
    {
        $this->articleModel = new Article();
        $this->commentModel = new Comment();
    }

    /**
     * Menampilkan katalog artikel publik dengan dukungan filter kategori.
     */
    public function index(): void
    {
        $activeCategory = trim($_GET['kategori'] ?? '');
        $categories = $this->articleModel->getCategories();

        $articles = $this->articleModel->getPublished(24, $activeCategory !== '' ? $activeCategory : null);

        $this->view('articles/index', [
            'title'          => 'Artikel & Khazanah Budaya Lampung — K\'mplang Salatiga',
            'articles'       => $articles,
            'categories'     => $categories,
            'activeCategory' => $activeCategory !== '' ? $activeCategory : 'Semua'
        ], 'main');
    }

    /**
     * Menampilkan halaman baca artikel lengkap beserta estimasi waktu baca dan diskusi.
     */
    public function detail(string $slug): void
    {
        $article = $this->articleModel->findBySlug($slug);

        if (!$article) {
            http_response_code(404);
            $this->view('errors/404', ['title' => 'Artikel Tidak Ditemukan'], 'main');
            return;
        }

        // Tambah penghitung pembaca secara atomik
        $this->articleModel->incrementViews((int) $article['id']);

        $comments = $this->commentModel->getComments('article', (int) $article['id']);
        $relatedArticles = $this->articleModel->getPublished(4);

        $this->view('articles/detail', [
            'title'           => $article['judul'] . ' — K\'mplang Salatiga',
            'article'         => $article,
            'comments'        => $comments,
            'relatedArticles' => $relatedArticles
        ], 'main');
    }
}
