<?php

namespace App\Controllers;

use Core\Controller;
use Core\Validator;
use Core\Uploader;
use App\Models\Exposure;
use App\Models\AuditLog;

class ExposureController extends Controller
{
    public function index(): string
    {
        $this->ensureAuthenticated();
        $model = new Exposure($this->db);
        $user = $_SESSION['user'];
        $exposures = $model->allForUser($user);

        return $this->view('exposures/index', [
            'title' => 'Daftar Eksposur',
            'exposures' => $exposures,
            'user' => $user,
        ]);
    }

    public function create(): string
    {
        $this->ensureAuthenticated();
        return $this->view('exposures/form', [
            'title' => 'Tambah Eksposur',
            'exposure' => null,
            'action' => '/exposures/store',
        ]);
    }

    public function store(): string
    {
        $this->ensureAuthenticated();
        $validator = new Validator();
        $validator->required($_POST, 'description', 'Deskripsi wajib diisi');
        $validator->required($_POST, 'counterparty', 'Pihak lawan wajib diisi');
        $validator->required($_POST, 'currency', 'Mata uang wajib diisi');
        $validator->numeric($_POST, 'notional', 'Notional harus angka');
        $validator->required($_POST, 'purpose', 'Tujuan wajib diisi');
        $validator->required($_POST, 'cost_transparent', 'Informasi biaya wajib diisi');
        $validator->required($_POST, 'penalty_clause', 'Informasi denda wajib diisi');

        $isValid = $validator->passes();
        $errors = $validator->errors();

        if (isset($_POST['purpose']) && strtolower($_POST['purpose']) !== 'tahawwut') {
            $errors['purpose'][] = 'Tujuan harus tahawwut untuk hedging syariah';
            $isValid = false;
        }

        if (($_POST['cost_transparent'] ?? '') !== '1') {
            $errors['cost_transparent'][] = 'Biaya harus dinyatakan transparan';
            $isValid = false;
        }

        if (($_POST['penalty_clause'] ?? '') !== '0') {
            $errors['penalty_clause'][] = 'Eksposur tidak boleh mengandung denda riba';
            $isValid = false;
        }

        $fileName = null;
        try {
            $fileName = Uploader::upload('underlying_file', UPLOAD_PATH);
        } catch (\RuntimeException $e) {
            $errors['underlying_file'][] = $e->getMessage();
            $isValid = false;
        }

        if (!$fileName) {
            $errors['underlying_file'][] = 'Bukti underlying wajib diunggah';
            $isValid = false;
        }

        if (!$isValid) {
            return $this->view('exposures/form', [
                'title' => 'Tambah Eksposur',
                'errors' => $errors,
                'exposure' => $_POST,
                'action' => '/exposures/store',
            ]);
        }

        $model = new Exposure($this->db);
        $user = $_SESSION['user'];
        $id = $model->create(array_merge($_POST, [
            'user_id' => $user['id'],
            'underlying_file' => $fileName,
            'cost_transparent' => 1,
            'penalty_clause' => 0,
            'purpose' => strtolower($_POST['purpose']),
        ]));

        (new AuditLog($this->db))->record($user['id'], 'create', 'exposure', $id, 'Membuat eksposur baru');

        $this->redirect('/exposures');
        return '';
    }

    public function edit(): string
    {
        $this->ensureAuthenticated();
        $model = new Exposure($this->db);
        $id = $_GET['id'] ?? null;
        if (!$id) {
            $this->redirect('/exposures');
        }

        $exposure = $model->find((int)$id);
        $user = $_SESSION['user'];
        if (!$exposure || !$model->canEdit($user, $exposure)) {
            http_response_code(403);
            return 'Forbidden';
        }

        return $this->view('exposures/form', [
            'title' => 'Ubah Eksposur',
            'exposure' => $exposure,
            'action' => '/exposures/update',
        ]);
    }

    public function update(): string
    {
        $this->ensureAuthenticated();
        $model = new Exposure($this->db);
        $id = (int)($_POST['id'] ?? 0);
        $exposure = $model->find($id);
        $user = $_SESSION['user'];
        if (!$exposure || !$model->canEdit($user, $exposure)) {
            http_response_code(403);
            return 'Forbidden';
        }

        $validator = new Validator();
        $validator->required($_POST, 'description', 'Deskripsi wajib diisi');
        $validator->required($_POST, 'counterparty', 'Pihak lawan wajib diisi');
        $validator->required($_POST, 'currency', 'Mata uang wajib diisi');
        $validator->numeric($_POST, 'notional', 'Notional harus angka');
        $validator->required($_POST, 'purpose', 'Tujuan wajib diisi');
        $validator->required($_POST, 'cost_transparent', 'Informasi biaya wajib diisi');
        $validator->required($_POST, 'penalty_clause', 'Informasi denda wajib diisi');

        $isValid = $validator->passes();
        $errors = $validator->errors();

        if (isset($_POST['purpose']) && strtolower($_POST['purpose']) !== 'tahawwut') {
            $errors['purpose'][] = 'Tujuan harus tahawwut untuk hedging syariah';
            $isValid = false;
        }

        if (($_POST['cost_transparent'] ?? '') !== '1') {
            $errors['cost_transparent'][] = 'Biaya harus dinyatakan transparan';
            $isValid = false;
        }

        if (($_POST['penalty_clause'] ?? '') !== '0') {
            $errors['penalty_clause'][] = 'Eksposur tidak boleh mengandung denda riba';
            $isValid = false;
        }

        $fileName = $exposure['underlying_file'];
        try {
            $uploaded = Uploader::upload('underlying_file', UPLOAD_PATH);
            if ($uploaded) {
                $fileName = $uploaded;
            }
        } catch (\RuntimeException $e) {
            $errors['underlying_file'][] = $e->getMessage();
            $isValid = false;
        }

        if (!$fileName) {
            $errors['underlying_file'][] = 'Bukti underlying wajib tersedia';
            $isValid = false;
        }

        if (!$isValid) {
            return $this->view('exposures/form', [
                'title' => 'Ubah Eksposur',
                'errors' => $errors,
                'exposure' => array_merge($exposure, $_POST),
                'action' => '/exposures/update',
            ]);
        }

        $model->update($id, array_merge($_POST, [
            'underlying_file' => $fileName,
            'cost_transparent' => 1,
            'penalty_clause' => 0,
            'purpose' => strtolower($_POST['purpose']),
        ]));

        (new AuditLog($this->db))->record($user['id'], 'update', 'exposure', $id, 'Memperbarui eksposur');

        $this->redirect('/exposures');
        return '';
    }

    public function delete(): string
    {
        $this->ensureAuthenticated();
        $model = new Exposure($this->db);
        $id = (int)($_POST['id'] ?? 0);
        $exposure = $model->find($id);
        $user = $_SESSION['user'];
        if ($exposure && $model->canEdit($user, $exposure)) {
            $model->delete($id);
            (new AuditLog($this->db))->record($user['id'], 'delete', 'exposure', $id, 'Menghapus eksposur');
        }
        $this->redirect('/exposures');
        return '';
    }
}
