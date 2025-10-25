<?php

namespace App\Controllers;

use Core\Controller;
use Core\Validator;
use Core\Uploader;
use App\Models\Exposure;
use App\Models\Hedge;
use App\Models\AuditLog;

class HedgeController extends Controller
{
    public function index(): string
    {
        $this->ensureAuthenticated();
        $model = new Hedge($this->db);
        $exposureModel = new Exposure($this->db);
        $user = $_SESSION['user'];
        $hedges = $model->allForUser($user);

        return $this->view('hedges/index', [
            'title' => 'Daftar Hedging',
            'hedges' => $hedges,
            'user' => $user,
            'exposureOptions' => $exposureModel->optionsForUser($user),
            'akadOptions' => Hedge::AKAD_TYPES,
        ]);
    }

    public function create(): string
    {
        $this->ensureAuthenticated();
        $exposureModel = new Exposure($this->db);
        $user = $_SESSION['user'];

        return $this->view('hedges/form', [
            'title' => 'Tambah Hedging',
            'hedge' => null,
            'action' => '/hedges/store',
            'exposureOptions' => $exposureModel->optionsForUser($user),
            'akadOptions' => Hedge::AKAD_TYPES,
        ]);
    }

    public function store(): string
    {
        $this->ensureAuthenticated();
        $validator = new Validator();
        $validator->required($_POST, 'exposure_id', 'Eksposur wajib dipilih');
        $validator->required($_POST, 'akad_type', 'Akad wajib dipilih');
        $validator->numeric($_POST, 'notional', 'Notional harus angka');
        $validator->required($_POST, 'cost_breakdown', 'Biaya wajib dijelaskan');
        $validator->required($_POST, 'no_riba_clause', 'Konfirmasi tanpa denda riba wajib diisi');

        $isValid = $validator->passes();
        $errors = $validator->errors();
        if (($_POST['no_riba_clause'] ?? '') !== '1') {
            $errors['no_riba_clause'][] = 'Hedging harus menyatakan tanpa denda riba';
            $isValid = false;
        }

        $exposureModel = new Exposure($this->db);
        $exposure = $exposureModel->find((int)($_POST['exposure_id'] ?? 0));
        if (!$exposure) {
            $errors['exposure_id'][] = 'Eksposur tidak ditemukan';
            $isValid = false;
        }

        if ($exposure) {
            $maxNotional = $exposure['notional'] * 1.05;
            if ((float)($_POST['notional'] ?? 0) > $maxNotional) {
                $errors['notional'][] = 'Notional hedging maksimal 105% dari eksposur';
                $isValid = false;
            }
        }

        $document = null;
        try {
            $document = Uploader::upload('supporting_document', UPLOAD_PATH);
        } catch (\RuntimeException $e) {
            $errors['supporting_document'][] = $e->getMessage();
            $isValid = false;
        }

        if (!$document) {
            $errors['supporting_document'][] = 'Dokumen pendukung wajib diunggah';
            $isValid = false;
        }

        if (!$isValid) {
            return $this->view('hedges/form', [
                'title' => 'Tambah Hedging',
                'errors' => $errors,
                'hedge' => $_POST,
                'action' => '/hedges/store',
                'exposureOptions' => $exposureModel->optionsForUser($_SESSION['user']),
                'akadOptions' => Hedge::AKAD_TYPES,
            ]);
        }

        $model = new Hedge($this->db);
        $user = $_SESSION['user'];
        try {
            $id = $model->create(array_merge($_POST, [
                'document_path' => $document,
                'created_by' => $user['id'],
                'no_riba_clause' => 1,
            ]), $exposure);
        } catch (\RuntimeException $e) {
            $errors['akad_type'][] = $e->getMessage();
            return $this->view('hedges/form', [
                'title' => 'Tambah Hedging',
                'errors' => $errors,
                'hedge' => $_POST,
                'action' => '/hedges/store',
                'exposureOptions' => $exposureModel->optionsForUser($_SESSION['user']),
                'akadOptions' => Hedge::AKAD_TYPES,
            ]);
        }

        (new AuditLog($this->db))->record($user['id'], 'create', 'hedge', $id, 'Membuat transaksi hedging');

        $this->redirect('/hedges');
        return '';
    }

    public function edit(): string
    {
        $this->ensureAuthenticated();
        $model = new Hedge($this->db);
        $exposureModel = new Exposure($this->db);
        $id = (int)($_GET['id'] ?? 0);
        $hedge = $model->find($id);
        $user = $_SESSION['user'];
        if (!$hedge || !$model->canEdit($user, $hedge)) {
            http_response_code(403);
            return 'Forbidden';
        }

        return $this->view('hedges/form', [
            'title' => 'Ubah Hedging',
            'hedge' => $hedge,
            'action' => '/hedges/update',
            'exposureOptions' => $exposureModel->optionsForUser($user),
            'akadOptions' => Hedge::AKAD_TYPES,
        ]);
    }

    public function update(): string
    {
        $this->ensureAuthenticated();
        $model = new Hedge($this->db);
        $exposureModel = new Exposure($this->db);
        $id = (int)($_POST['id'] ?? 0);
        $hedge = $model->find($id);
        $user = $_SESSION['user'];
        if (!$hedge || !$model->canEdit($user, $hedge)) {
            http_response_code(403);
            return 'Forbidden';
        }

        $validator = new Validator();
        $validator->required($_POST, 'akad_type', 'Akad wajib dipilih');
        $validator->numeric($_POST, 'notional', 'Notional harus angka');
        $validator->required($_POST, 'cost_breakdown', 'Biaya wajib dijelaskan');
        $validator->required($_POST, 'no_riba_clause', 'Konfirmasi tanpa denda riba wajib diisi');

        $isValid = $validator->passes();
        $errors = $validator->errors();
        if (($_POST['no_riba_clause'] ?? '') !== '1') {
            $errors['no_riba_clause'][] = 'Hedging harus menyatakan tanpa denda riba';
            $isValid = false;
        }

        $exposure = $exposureModel->find((int)$hedge['exposure_id']);
        if ($exposure) {
            $maxNotional = $exposure['notional'] * 1.05;
            if ((float)($_POST['notional'] ?? 0) > $maxNotional) {
                $errors['notional'][] = 'Notional hedging maksimal 105% dari eksposur';
                $isValid = false;
            }
        }

        $document = $hedge['document_path'];
        try {
            $uploaded = Uploader::upload('supporting_document', UPLOAD_PATH);
            if ($uploaded) {
                $document = $uploaded;
            }
        } catch (\RuntimeException $e) {
            $errors['supporting_document'][] = $e->getMessage();
            $isValid = false;
        }

        if (!$document) {
            $errors['supporting_document'][] = 'Dokumen pendukung wajib tersedia';
            $isValid = false;
        }

        if (!$isValid) {
            return $this->view('hedges/form', [
                'title' => 'Ubah Hedging',
                'errors' => $errors,
                'hedge' => array_merge($hedge, $_POST),
                'action' => '/hedges/update',
                'exposureOptions' => $exposureModel->optionsForUser($user),
                'akadOptions' => Hedge::AKAD_TYPES,
            ]);
        }

        try {
            $model->update($id, array_merge($_POST, [
                'document_path' => $document,
                'no_riba_clause' => 1,
            ]), $exposure);
        } catch (\RuntimeException $e) {
            $errors['akad_type'][] = $e->getMessage();
            return $this->view('hedges/form', [
                'title' => 'Ubah Hedging',
                'errors' => $errors,
                'hedge' => array_merge($hedge, $_POST),
                'action' => '/hedges/update',
                'exposureOptions' => $exposureModel->optionsForUser($user),
                'akadOptions' => Hedge::AKAD_TYPES,
            ]);
        }

        (new AuditLog($this->db))->record($user['id'], 'update', 'hedge', $id, 'Memperbarui transaksi hedging');

        $this->redirect('/hedges');
        return '';
    }

    public function delete(): string
    {
        $this->ensureAuthenticated();
        $model = new Hedge($this->db);
        $id = (int)($_POST['id'] ?? 0);
        $hedge = $model->find($id);
        $user = $_SESSION['user'];
        if ($hedge && $model->canEdit($user, $hedge)) {
            $model->delete($id);
            (new AuditLog($this->db))->record($user['id'], 'delete', 'hedge', $id, 'Menghapus transaksi hedging');
        }
        $this->redirect('/hedges');
        return '';
    }
}
