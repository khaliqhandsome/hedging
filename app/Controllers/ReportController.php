<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\Exposure;
use App\Models\Hedge;

class ReportController extends Controller
{
    public function index(): string
    {
        $this->authorize(['admin', 'auditor']);
        $exposureModel = new Exposure($this->db);
        $hedgeModel = new Hedge($this->db);

        return $this->view('reports/index', [
            'title' => 'Laporan',
            'exposures' => $exposureModel->allForReport(),
            'hedges' => $hedgeModel->allForReport(),
        ]);
    }

    public function csv(): string
    {
        $this->authorize(['admin', 'auditor']);
        $exposureModel = new Exposure($this->db);
        $hedgeModel = new Hedge($this->db);

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="hedging_report.csv"');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['Jenis', 'ID', 'Deskripsi/ Akad', 'Notional', 'Status Syariah']);

        foreach ($exposureModel->allForReport() as $exposure) {
            fputcsv($output, ['Eksposur', $exposure['id'], $exposure['description'], $exposure['notional'], $exposure['shariah_status'] ?? 'pending']);
        }

        foreach ($hedgeModel->allForReport() as $hedge) {
            fputcsv($output, ['Hedging', $hedge['id'], $hedge['akad_type'], $hedge['notional'], $hedge['status'] ?? 'pending']);
        }

        fclose($output);
        exit;
    }
}
