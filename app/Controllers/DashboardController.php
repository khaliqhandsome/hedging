<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\Exposure;
use App\Models\Hedge;
use DateTimeImmutable;

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
        $hedgeStats = $hedgeModel->summary($user);
        $statusBreakdown = $exposureModel->statusBreakdown($user);
        $recentExposures = $exposureModel->recentByRole($user);
        $recentHedges = $hedgeModel->recentByRole($user);
        $hedgeTotals = $hedgeModel->totalsByRole($user);

        $monthsWindow = 6;
        $exposureTrend = $exposureModel->monthlyNotionalByRole($user, $monthsWindow);
        $hedgeTrend = $hedgeModel->monthlyNotionalByRole($user, $monthsWindow);

        $trendLabels = [];
        $trendExposureSeries = [];
        $trendHedgeSeries = [];

        $cursor = (new DateTimeImmutable('first day of this month'))
            ->modify(sprintf('-%d months', $monthsWindow - 1));

        for ($i = 0; $i < $monthsWindow; $i++) {
            $month = $cursor->modify(sprintf('+%d months', $i));
            $key = $month->format('Y-m');
            $trendLabels[] = $month->format('M Y');
            $trendExposureSeries[] = (float)($exposureTrend[$key] ?? 0.0);
            $trendHedgeSeries[] = (float)($hedgeTrend[$key] ?? 0.0);
        }

        return $this->view('dashboard', [
            'title' => 'Dashboard',
            'user' => $user,
            'summary' => $summary,
            'pendingReviews' => $pendingReviews,
            'hedgeStats' => $hedgeStats,
            'statusBreakdown' => $statusBreakdown,
            'recentExposures' => $recentExposures,
            'recentHedges' => $recentHedges,
            'hedgeTotals' => $hedgeTotals,
            'trendLabels' => $trendLabels,
            'trendExposureSeries' => $trendExposureSeries,
            'trendHedgeSeries' => $trendHedgeSeries,
        ]);
    }
}
