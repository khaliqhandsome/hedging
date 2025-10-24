<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\Exposure;
use App\Models\Hedge;

class DashboardController extends Controller
{
    public function index(): string
    {
        $this->ensureAuthenticated();
        $user = $_SESSION['user'];

        $exposureModel = new Exposure($this->db);
        $hedgeModel = new Hedge($this->db);

        $summary = $exposureModel->summaryByRole($user);
        $pendingReviews = $exposureModel->pendingReviewsCount();
        $hedgeStats = $hedgeModel->summary();

        return $this->view('dashboard', [
            'title' => 'Dashboard',
            'user' => $user,
            'summary' => $summary,
            'pendingReviews' => $pendingReviews,
            'hedgeStats' => $hedgeStats,
        ]);
    }
}
