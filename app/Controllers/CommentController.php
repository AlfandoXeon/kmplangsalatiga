<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Comment;
use App\Models\AuditLog;
use App\Helpers\Sanitizer;

/**
 * ============================================================================
 * Class CommentController - Pengendali Diskusi & Tanggapan Komunitas
 * ============================================================================
 * Menangani pengiriman komentar baru serta penghapusan komentar oleh pemilik
 * komentar atau Pengurus/Admin (moderasi konten).
 * ============================================================================
 */
class CommentController extends Controller
{
    private Comment $commentModel;

    public function __construct()
    {
        $this->commentModel = new Comment();
    }

    /**
     * Menyimpan komentar baru pada Kegiatan atau Artikel.
     */
    public function store(): void
    {
        if (empty($_SESSION['user'])) {
            $this->setFlash('error', 'Silakan masuk terlebih dahulu untuk memberikan tanggapan.');
            $this->redirect('/login');
        }

        $userId = (int) $_SESSION['user']['id'];
        $postType = in_array($_POST['post_type'] ?? '', ['activity', 'article'], true)
            ? $_POST['post_type']
            : 'activity';
        $postId = (int) ($_POST['post_id'] ?? 0);
        $redirectUrl = $_POST['redirect_url'] ?? '/';
        $content = Sanitizer::cleanPlainText($_POST['content'] ?? '');

        // Anti-Spam Cooldown: minimal jeda 5 detik antar pengiriman komentar
        $lastCommentAt = (int) ($_SESSION['_last_comment_at'] ?? 0);
        if (time() - $lastCommentAt < 5) {
            $this->setFlash('error', 'Mohon tunggu beberapa detik sebelum mengirim tanggapan berikutnya.');
            $this->redirect($redirectUrl);
        }

        if ($postId <= 0 || $content === '') {
            $this->setFlash('error', 'Isi tanggapan tidak boleh kosong.');
            $this->redirect($redirectUrl);
        }

        if (mb_strlen($content) > 1000) {
            $this->setFlash('error', 'Isi tanggapan maksimal 1000 karakter.');
            $this->redirect($redirectUrl);
        }

        $_SESSION['_last_comment_at'] = time();

        $commentId = $this->commentModel->create([
            'post_type' => $postType,
            'post_id'   => $postId,
            'user_id'   => $userId,
            'content'   => Sanitizer::escape($content),
            'status'    => 'approved'
        ]);

        AuditLog::record($userId, 'CREATE_COMMENT', 'comments', $commentId, "Commented on {$postType} #{$postId}");

        $this->setFlash('success', 'Tanggapan Anda berhasil dipublikasikan.');
        $this->redirect($redirectUrl);
    }

    /**
     * Menghapus komentar (hanya diizinkan bagi pemilik komentar atau Admin/Pengurus).
     */
    public function delete(string $id): void
    {
        $commentId = (int) $id;
        $userId = $this->userId();
        $role = $_SESSION['user']['role'] ?? 'anggota';
        $redirectUrl = $_POST['redirect_url'] ?? '/';

        $comment = $this->commentModel->find($commentId);
        if (!$comment) {
            $this->redirect($redirectUrl);
        }

        $isOwner = ((int) $comment['user_id'] === $userId);
        $isModerator = in_array($role, ['admin', 'pengurus'], true);

        if (!$isOwner && !$isModerator) {
            $this->setFlash('error', 'Anda tidak memiliki izin untuk menghapus tanggapan ini.');
            $this->redirect($redirectUrl);
        }

        $this->commentModel->delete($commentId);
        AuditLog::record($userId, 'DELETE_COMMENT', 'comments', $commentId, "Deleted comment #{$commentId}");

        $this->setFlash('success', 'Tanggapan berhasil dihapus.');
        $this->redirect($redirectUrl);
    }
}
