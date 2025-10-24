<?php

namespace App\Models;

use PDO;

class Exposure
{
    public function __construct(private PDO $db)
    {
    }

    public function allForUser(array $user): array
    {
        if (in_array($user['role'], ['admin', 'auditor'], true)) {
            $stmt = $this->db->query('SELECT * FROM exposures ORDER BY created_at DESC');
            return $stmt->fetchAll();
        }

        $stmt = $this->db->prepare('SELECT * FROM exposures WHERE user_id = :user_id ORDER BY created_at DESC');
        $stmt->execute(['user_id' => $user['id']]);
        return $stmt->fetchAll();
    }

    public function optionsForUser(array $user): array
    {
        $exposures = $this->allForUser($user);
        $options = [];
        foreach ($exposures as $exposure) {
            $options[$exposure['id']] = $exposure['description'] . ' - ' . $exposure['currency'];
        }
        return $options;
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare('INSERT INTO exposures (user_id, description, counterparty, currency, notional, exposure_date, purpose, cost_transparent, penalty_clause, underlying_file, created_at, updated_at) VALUES (:user_id, :description, :counterparty, :currency, :notional, :exposure_date, :purpose, :cost_transparent, :penalty_clause, :underlying_file, NOW(), NOW())');
        $stmt->execute([
            'user_id' => $data['user_id'],
            'description' => $data['description'],
            'counterparty' => $data['counterparty'],
            'currency' => $data['currency'],
            'notional' => $data['notional'],
            'exposure_date' => $data['exposure_date'] ?? null,
            'purpose' => $data['purpose'],
            'cost_transparent' => isset($data['cost_transparent']) ? (int)$data['cost_transparent'] : 0,
            'penalty_clause' => isset($data['penalty_clause']) ? (int)$data['penalty_clause'] : 0,
            'underlying_file' => $data['underlying_file'],
        ]);

        return (int)$this->db->lastInsertId();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM exposures WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function update(int $id, array $data): void
    {
        $stmt = $this->db->prepare('UPDATE exposures SET description = :description, counterparty = :counterparty, currency = :currency, notional = :notional, exposure_date = :exposure_date, purpose = :purpose, cost_transparent = :cost_transparent, penalty_clause = :penalty_clause, underlying_file = :underlying_file, updated_at = NOW() WHERE id = :id');
        $stmt->execute([
            'description' => $data['description'],
            'counterparty' => $data['counterparty'],
            'currency' => $data['currency'],
            'notional' => $data['notional'],
            'exposure_date' => $data['exposure_date'] ?? null,
            'purpose' => $data['purpose'],
            'cost_transparent' => isset($data['cost_transparent']) ? (int)$data['cost_transparent'] : 0,
            'penalty_clause' => isset($data['penalty_clause']) ? (int)$data['penalty_clause'] : 0,
            'underlying_file' => $data['underlying_file'],
            'id' => $id,
        ]);
    }

    public function delete(int $id): void
    {
        $stmt = $this->db->prepare('DELETE FROM exposures WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }

    public function canEdit(array $user, array $exposure): bool
    {
        return $user['role'] === 'admin' || $exposure['user_id'] == $user['id'];
    }

    public function summaryByRole(array $user): array
    {
        if (in_array($user['role'], ['admin', 'auditor'], true)) {
            $stmt = $this->db->query('SELECT COUNT(*) AS total, SUM(notional) AS notional FROM exposures');
            return $stmt->fetch() ?: ['total' => 0, 'notional' => 0];
        }

        $stmt = $this->db->prepare('SELECT COUNT(*) AS total, SUM(notional) AS notional FROM exposures WHERE user_id = :user_id');
        $stmt->execute(['user_id' => $user['id']]);
        return $stmt->fetch() ?: ['total' => 0, 'notional' => 0];
    }

    public function pendingReviewsCount(): int
    {
        $stmt = $this->db->query("SELECT COUNT(*) AS total FROM exposures WHERE shariah_status IS NULL");
        $row = $stmt->fetch();
        return (int)($row['total'] ?? 0);
    }

    public function setShariaStatus(int $id, string $status, ?string $notes): void
    {
        $stmt = $this->db->prepare('UPDATE exposures SET shariah_status = :status, shariah_notes = :notes, updated_at = NOW() WHERE id = :id');
        $stmt->execute([
            'status' => $status,
            'notes' => $notes,
            'id' => $id,
        ]);
    }

    public function allForReport(): array
    {
        $stmt = $this->db->query('SELECT * FROM exposures ORDER BY created_at DESC');
        return $stmt->fetchAll();
    }
}
