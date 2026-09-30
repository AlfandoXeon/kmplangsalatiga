<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Activity;
use App\Models\ActivityMedia;
use App\Models\Comment;

class ActivityController extends Controller
{
    private Activity $activityModel;
    private ActivityMedia $mediaModel;
    private Comment $commentModel;

    public function __construct()
    {
        $this->activityModel = new Activity();
        $this->mediaModel = new ActivityMedia();
        $this->commentModel = new Comment();
    }

    public function index(): void
    {
        $activities = $this->activityModel->getAllWithMediaCount();

        $this->view('activities/index', [
            'title'      => 'Dokumentasi & Kegiatan - K\'mplang Salatiga',
            'activities' => $activities,
            'enableAos'  => false // Sub-page: NO AOS
        ], 'main');
    }

    public function detail(string $slug): void
    {
        $activity = $this->activityModel->findBySlug($slug);

        if (!$activity) {
            http_response_code(404);
            $this->view('errors/404', ['title' => 'Kegiatan Tidak Ditemukan', 'enableAos' => false], 'main');
            return;
        }

        // Increment view count
        $this->activityModel->incrementViews($activity['id']);

        // Fetch all media files in this album (Google Drive view)
        $mediaList = $this->mediaModel->getByActivityId($activity['id']);

        // Fetch comments
        $comments = $this->commentModel->getComments('activity', $activity['id']);

        $this->view('activities/detail', [
            'title'     => $activity['judul'] . ' - K\'mplang Salatiga',
            'activity'  => $activity,
            'mediaList' => $mediaList,
            'comments'  => $comments,
            'enableAos' => false // Sub-page: NO AOS
        ], 'main');
    }
}
