<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\User;
use App\Models\WorkProgram;
use App\Models\Activity;
use App\Models\Article;
use App\Models\AuditLog;

class DashboardController extends Controller
{
    public function index(): void
    {
        $userModel = new User();
        $prokerModel = new WorkProgram();
        $activityModel = new Activity();
        $articleModel = new Article();
        $auditModel = new AuditLog();

        $db = \App\Config\Database::getConnection();

        $totalMembers = $userModel->count();
        $pendingMembers = (int) $db->query("SELECT COUNT(*) FROM users WHERE status = 'pending'")->fetchColumn();
        $totalProker = $prokerModel->count();
        $totalActivities = $activityModel->count();
        $totalArticles = $articleModel->count();

        $recentMembers = $db->query("SELECT * FROM users ORDER BY id DESC LIMIT 5")->fetchAll();
        $recentLogs = $auditModel->getRecentLogs(8);

        $this->view('admin/dashboard', [
            'title'          => 'Dashboard Admin - K\'mplang Salatiga',
            'totalMembers'   => $totalMembers,
            'pendingMembers' => $pendingMembers,
            'totalProker'    => $totalProker,
            'totalActivities'=> $totalActivities,
            'totalArticles'  => $totalArticles,
            'recentMembers'  => $recentMembers,
            'recentLogs'     => $recentLogs,
            'enableAos'      => false // Sub-page: NO AOS
        ], 'admin');
    }
}
