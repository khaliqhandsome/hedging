<?php

namespace App\Models;

use PDO;

class Review
{
    public function __construct(private PDO $db)
    {
    }

    public function pending(): array
    {
        $stmt = $this->db->query('SELECT e.* FROM exposures e WHERE e.shariah_status IS NULL ORDER BY e.created_at ASC');
        return $stmt->fetchAll();
    }

    public function review(array $exposure, int $reviewerId, string $status, ?string $notes): void
    {
        $status = in_array($status, ['approved', 'rejected'], true) ? $status : 'rejected';

        $stmt = $this->db->prepare('INSERT INTO sharia_reviews (exposure_id, reviewer_id, status, notes, created_at) VALUES (:exposure_id, :reviewer_id, :status, :notes, NOW())');
        $stmt->execute([
            'exposure_id' => $exposure['id'],
            'reviewer_id' => $reviewerId,
            'status' => $status,
            'notes' => $notes,
        ]);

        (new Exposure($this->db))->setShariaStatus($exposure['id'], $status, $notes);
    }
}
