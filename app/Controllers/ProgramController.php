<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\WorkProgram;

/**
 * Class ProgramController
 * -------------------------------------------------------------------------
 * Menampilkan halaman Transparansi Program Kerja organisasi beserta
 * statistik progres pelaksanaan dan filter berdasarkan divisi.
 * -------------------------------------------------------------------------
 */
class ProgramController extends Controller
{
    private WorkProgram $prokerModel;

    public function __construct()
    {
        $this->prokerModel = new WorkProgram();
    }

    /**
     * Merender daftar program kerja dengan filter divisi opsional.
     */
    public function index(): void
    {
        $divisiFilter = isset($_GET['divisi']) ? trim((string) $_GET['divisi']) : null;
        $divisiList = $this->prokerModel->getAllDivisi();

        // Validasi apakah divisi yang dipilih termasuk dalam daftar divisi valid
        if ($divisiFilter && in_array($divisiFilter, $divisiList, true)) {
            $prokers = $this->prokerModel->getByDivisi($divisiFilter);
        } else {
            $prokers = $this->prokerModel->all('id ASC');
            $divisiFilter = 'Semua';
        }

        $stats = $this->prokerModel->getStats();

        $this->view('programs/index', [
            'title'        => 'Transparansi Program Kerja — K\'mplang Salatiga',
            'prokers'      => $prokers,
            'divisiList'   => $divisiList,
            'activeDivisi' => $divisiFilter,
            'stats'        => $stats,
            'enableAos'    => false
        ], 'main');
    }
}
