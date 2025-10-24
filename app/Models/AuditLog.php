<?php

namespace App\Models;

use PDO;

class AuditLog
{
    public function __construct(private PDO $db)
    {
    }

    public function record(int $userId, string $action, string $entityType, int $entityId, string $details): void
    {
        $stmt = $this->db->prepare('INSERT INTO audit_logs (user_id, action, entity_type, entity_id, details, created_at) VALUES (:user_id, :action, :entity_type, :entity_id, :details, NOW())');
        $stmt->execute([
            'user_id' => $userId,
            'action' => $action,
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'details' => $details,
        ]);
    }

    public function all(): array
    {
        $stmt = $this->db->query('SELECT a.*, u.username FROM audit_logs a LEFT JOIN users u ON u.id = a.user_id ORDER BY a.created_at DESC');
        return $stmt->fetchAll();
    }
}
