<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Comment;
use App\Helpers\Sanitizer;

class CommentController extends Controller
{
    private Comment $commentModel;

    public function __construct()
    {
        $this->commentModel = new Comment();
    }

    public function store(): void
    {
        if (empty($_SESSION['user'])) {
            $this->setFlash('error', 'Silakan login terlebih dahulu untuk memberikan komentar.');
            $this->redirect('/login');
        }

        $userId = (int) $_SESSION['user']['id'];
        $postType = $_POST['post_type'] ?? 'activity';
        $postId = (int) ($_POST['post_id'] ?? 0);
        $redirectUrl = $_POST['redirect_url'] ?? '/';
        $content = trim($_POST['content'] ?? '');

        if ($postId <= 0 || empty($content)) {
            $this->setFlash('error', 'Isi komentar tidak boleh kosong.');
            $this->redirect($redirectUrl);
        }

        $this->commentModel->create([
            'post_type' => $postType,
            'post_id'   => $postId,
            'user_id'   => $userId,
            'content'   => Sanitizer::escape($content),
            'status'    => 'approved'
        ]);

        $this->setFlash('success', 'Komentar Anda berhasil ditambahkan.');
        $this->redirect($redirectUrl);
    }
}
