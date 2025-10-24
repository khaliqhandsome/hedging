<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\AuditLog;

class AuditController extends Controller
{
    public function index(): string
    {
        $this->authorize(['admin', 'auditor']);
        $model = new AuditLog($this->db);
        $logs = $model->all();

        return $this->view('audit/index', [
            'title' => 'Audit Log',
            'logs' => $logs,
        ]);
    }
}
