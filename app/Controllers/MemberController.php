<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;

class MemberController extends Controller
{
    private User $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function index(): void
    {
        $search = trim($_GET['q'] ?? '');

        if (!empty($search)) {
            $stmt = \App\Config\Database::getConnection()->prepare("
                SELECT id, nama_lengkap, nim, fakultas, asal_daerah, role, status, created_at
                FROM users
                WHERE status = 'active' AND (
                    nama_lengkap LIKE :q1 OR 
                    nim LIKE :q2 OR 
                    fakultas LIKE :q3 OR 
                    asal_daerah LIKE :q4
                )
                ORDER BY CASE WHEN role = 'admin' THEN 0 ELSE 1 END, id DESC
            ");
            $like = "%$search%";
            $stmt->execute(['q1' => $like, 'q2' => $like, 'q3' => $like, 'q4' => $like]);
            $members = $stmt->fetchAll();
        } else {
            $stmt = \App\Config\Database::getConnection()->query("
                SELECT id, nama_lengkap, nim, fakultas, asal_daerah, role, status, created_at
                FROM users
                WHERE status = 'active'
                ORDER BY CASE WHEN role = 'admin' THEN 0 ELSE 1 END, id DESC
            ");
            $members = $stmt->fetchAll();
        }

        $this->view('members/index', [
            'title'     => 'Daftar Anggota - K\'mplang Salatiga',
            'members'   => $members,
            'search'    => $search,
            'enableAos' => false // Sub-page: NO AOS
        ], 'main');
    }

    public function profile(): void
    {
        $userId = $this->userId();
        if (!$userId) {
            $this->redirect('/login');
        }

        $user = $this->userModel->find($userId);

        $this->view('members/profile', [
            'title'     => 'Profil Saya - K\'mplang Salatiga',
            'user'      => $user,
            'enableAos' => false
        ], 'main');
    }
}
