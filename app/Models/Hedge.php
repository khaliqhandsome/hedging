<?php

namespace App\Models;

use PDO;

class Hedge
{
    public const AKAD_TYPES = [
        'waad_fx' => 'Waad FX',
        'murabahah' => 'Murabahah',
        'tawarruq' => 'Tawarruq',
        'salam' => 'Salam',
        'istishna' => 'Istishna',
    ];

    public function __construct(private PDO $db)
    {
    }

    public function allForUser(array $user): array
    {
        if (in_array($user['role'], ['admin', 'auditor'], true)) {
            $stmt = $this->db->query('SELECT h.*, e.description AS exposure_description FROM hedges h JOIN exposures e ON e.id = h.exposure_id ORDER BY h.created_at DESC');
            return $stmt->fetchAll();
        }

        $stmt = $this->db->prepare('SELECT h.*, e.description AS exposure_description FROM hedges h JOIN exposures e ON e.id = h.exposure_id WHERE h.created_by = :user_id ORDER BY h.created_at DESC');
        $stmt->execute(['user_id' => $user['id']]);
        return $stmt->fetchAll();
    }

    public function create(array $data, array $exposure): int
    {
        $validator = $this->syariahValidator($exposure, $data);
        if ($validator !== true) {
            throw new \RuntimeException($validator);
        }

        $stmt = $this->db->prepare('INSERT INTO hedges (exposure_id, akad_type, notional, rate, start_date, end_date, cost_breakdown, no_riba_clause, document_path, created_by, created_at, updated_at) VALUES (:exposure_id, :akad_type, :notional, :rate, :start_date, :end_date, :cost_breakdown, :no_riba_clause, :document_path, :created_by, NOW(), NOW())');
        $stmt->execute([
            'exposure_id' => $data['exposure_id'],
            'akad_type' => $data['akad_type'],
            'notional' => $data['notional'],
            'rate' => $data['rate'] ?? null,
            'start_date' => $data['start_date'] ?? null,
            'end_date' => $data['end_date'] ?? null,
            'cost_breakdown' => $data['cost_breakdown'],
            'no_riba_clause' => isset($data['no_riba_clause']) ? 1 : 0,
            'document_path' => $data['document_path'],
            'created_by' => $data['created_by'],
        ]);

        return (int)$this->db->lastInsertId();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM hedges WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function update(int $id, array $data, array $exposure): void
    {
        $validator = $this->syariahValidator($exposure, array_merge($data, ['exposure_id' => $exposure['id']]));
        if ($validator !== true) {
            throw new \RuntimeException($validator);
        }

        $stmt = $this->db->prepare('UPDATE hedges SET akad_type = :akad_type, notional = :notional, rate = :rate, start_date = :start_date, end_date = :end_date, cost_breakdown = :cost_breakdown, no_riba_clause = :no_riba_clause, document_path = :document_path, updated_at = NOW() WHERE id = :id');
        $stmt->execute([
            'akad_type' => $data['akad_type'],
            'notional' => $data['notional'],
            'rate' => $data['rate'] ?? null,
            'start_date' => $data['start_date'] ?? null,
            'end_date' => $data['end_date'] ?? null,
            'cost_breakdown' => $data['cost_breakdown'],
            'no_riba_clause' => isset($data['no_riba_clause']) ? 1 : 0,
            'document_path' => $data['document_path'],
            'id' => $id,
        ]);
    }

    public function delete(int $id): void
    {
        $stmt = $this->db->prepare('DELETE FROM hedges WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }

    public function canEdit(array $user, array $hedge): bool
    {
        return $user['role'] === 'admin' || $hedge['created_by'] == $user['id'];
    }

    private function syariahValidator(array $exposure, array $hedge): bool|string
    {
        if (empty($exposure['underlying_file'])) {
            return 'Eksposur tidak memiliki bukti underlying';
        }

        if (strtolower($exposure['purpose']) !== 'tahawwut') {
            return 'Tujuan eksposur harus tahawwut';
        }

        if (!(int)$exposure['cost_transparent']) {
            return 'Biaya eksposur harus transparan';
        }

        if (!empty($exposure['penalty_clause'])) {
            return 'Eksposur tidak boleh memiliki denda riba';
        }

        $maxNotional = $exposure['notional'] * 1.05;
        if ($hedge['notional'] > $maxNotional) {
            return 'Notional hedging melebihi batas 105% eksposur';
        }

        if (empty($hedge['no_riba_clause'])) {
            return 'Hedging harus mengkonfirmasi tanpa denda riba';
        }

        return true;
    }

    public function summary(): array
    {
        $stmt = $this->db->query('SELECT akad_type, COUNT(*) AS total, SUM(notional) AS notional FROM hedges GROUP BY akad_type');
        return $stmt->fetchAll();
    }

    public function allForReport(): array
    {
        $stmt = $this->db->query('SELECT * FROM hedges ORDER BY created_at DESC');
        return $stmt->fetchAll();
    }
}
