<?php

namespace App\Controllers;

use Core\Controller;
use Core\Validator;
use App\Models\Exposure;
use App\Models\Review;
use App\Models\AuditLog;

class ReviewController extends Controller
{
    public function index(): string
    {
        $this->authorize(['admin', 'auditor']);
        $model = new Review($this->db);
        $reviews = $model->pending();

        return $this->view('reviews/index', [
            'title' => 'Review Syariah',
            'reviews' => $reviews,
        ]);
    }

    public function submit(): string
    {
        $this->authorize(['admin', 'auditor']);
        $validator = new Validator();
        $validator->required($_POST, 'exposure_id', 'Eksposur wajib dipilih');
        $validator->required($_POST, 'status', 'Status wajib diisi');

        if (!$validator->passes()) {
            $this->redirect('/reviews');
        }

        $model = new Review($this->db);
        $exposureModel = new Exposure($this->db);
        $exposure = $exposureModel->find((int)$_POST['exposure_id']);
        if (!$exposure) {
            $this->redirect('/reviews');
        }

        $model->review($exposure, $_SESSION['user']['id'], $_POST['status'], $_POST['notes'] ?? null);

        (new AuditLog($this->db))->record($_SESSION['user']['id'], 'review', 'exposure', $exposure['id'], 'Review syariah: ' . $_POST['status']);

        $this->redirect('/reviews');
        return '';
    }
}
