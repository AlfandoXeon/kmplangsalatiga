<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\SiteSetting;
use App\Models\WorkProgram;
use App\Models\Activity;
use App\Models\ActivityMedia;
use App\Models\Article;
use App\Models\User;

class HomeController extends Controller
{
    public function index(): void
    {
        $settingModel = new SiteSetting();
        $prokerModel = new WorkProgram();
        $activityModel = new Activity();
        $mediaModel = new ActivityMedia();
        $articleModel = new Article();
        $userModel = new User();

        $settings = $settingModel->getAllKeyValue();
        $prokerList = $prokerModel->all('id ASC');
        $recentActivities = $activityModel->getRecent(6);
        $galleryPhotos = $mediaModel->getRecentPhotos(6);
        $recentArticles = $articleModel->getPublished(3);
        $stats = [
            'total_anggota' => $userModel->count(),
            'total_proker'  => $prokerModel->count(),
            'total_kegiatan'=> $activityModel->count()
        ];

        $this->view('home/index', [
            'title'            => "K'mplang Salatiga - Komunitas Mahasiswa Lampung",
            'settings'         => $settings,
            'prokerList'       => $prokerList,
            'featuredProkers'  => array_slice($prokerList, 0, 6),
            'recentActivities' => $recentActivities,
            'galleryPhotos'    => $galleryPhotos,
            'recentArticles'   => $recentArticles,
            'stats'            => $stats,
            'enableAos'        => false
        ], 'main');
    }
}
