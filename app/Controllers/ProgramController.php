<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\WorkProgram;

class ProgramController extends Controller
{
    private WorkProgram $prokerModel;

    public function __construct()
    {
        $this->prokerModel = new WorkProgram();
    }

    public function index(): void
    {
        $divisiFilter = $_GET['divisi'] ?? null;
        $divisiList = $this->prokerModel->getAllDivisi();

        if ($divisiFilter && in_array($divisiFilter, $divisiList, true)) {
            $prokers = $this->prokerModel->getByDivisi($divisiFilter);
        } else {
            $prokers = $this->prokerModel->all('id ASC');
            $divisiFilter = 'Semua';
        }

        $stats = $this->prokerModel->getStats();

        $this->view('programs/index', [
            'title'        => 'Transparansi Program Kerja - K\'mplang Salatiga',
            'prokers'      => $prokers,
            'divisiList'   => $divisiList,
            'activeDivisi' => $divisiFilter,
            'stats'        => $stats,
            'enableAos'    => false // Sub-page: NO AOS
        ], 'main');
    }
}
