<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Article;
use App\Models\Comment;

class ArticleController extends Controller
{
    private Article $articleModel;
    private Comment $commentModel;

    public function __construct()
    {
        $this->articleModel = new Article();
        $this->commentModel = new Comment();
    }

    public function index(): void
    {
        $articles = $this->articleModel->getPublished(20);

        $this->view('articles/index', [
            'title'     => 'Artikel & Wawasan Budaya - K\'mplang Salatiga',
            'articles'  => $articles,
            'enableAos' => false // Sub-page: NO AOS
        ], 'main');
    }

    public function detail(string $slug): void
    {
        $article = $this->articleModel->findBySlug($slug);

        if (!$article) {
            http_response_code(404);
            $this->view('errors/404', ['title' => 'Artikel Tidak Ditemukan', 'enableAos' => false], 'main');
            return;
        }

        $this->articleModel->incrementViews($article['id']);
        $comments = $this->commentModel->getComments('article', $article['id']);
        $relatedArticles = $this->articleModel->getPublished(4);

        $this->view('articles/detail', [
            'title'           => $article['judul'] . ' - K\'mplang Salatiga',
            'article'         => $article,
            'comments'        => $comments,
            'relatedArticles' => $relatedArticles,
            'enableAos'       => false // Sub-page: NO AOS
        ], 'main');
    }
}
