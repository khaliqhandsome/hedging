<?php

return function (PDO $db) {
    $db->exec('CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        password_hash VARCHAR(255) NOT NULL,
        role ENUM("admin", "auditor", "user") NOT NULL DEFAULT "user",
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4');

    $db->exec('CREATE TABLE IF NOT EXISTS exposures (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        description VARCHAR(255) NOT NULL,
        counterparty VARCHAR(255) NOT NULL,
        currency VARCHAR(10) NOT NULL,
        notional DECIMAL(18,2) NOT NULL,
        exposure_date DATE NULL,
        purpose VARCHAR(100) NOT NULL,
        cost_transparent TINYINT(1) NOT NULL DEFAULT 0,
        penalty_clause TINYINT(1) NOT NULL DEFAULT 0,
        underlying_file VARCHAR(255) NULL,
        shariah_status ENUM("approved", "rejected") NULL,
        shariah_notes TEXT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP NULL,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4');

    $db->exec('CREATE TABLE IF NOT EXISTS hedges (
        id INT AUTO_INCREMENT PRIMARY KEY,
        exposure_id INT NOT NULL,
        akad_type VARCHAR(50) NOT NULL,
        notional DECIMAL(18,2) NOT NULL,
        rate DECIMAL(10,4) NULL,
        start_date DATE NULL,
        end_date DATE NULL,
        cost_breakdown TEXT NOT NULL,
        no_riba_clause TINYINT(1) NOT NULL DEFAULT 0,
        document_path VARCHAR(255) NULL,
        created_by INT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP NULL,
        FOREIGN KEY (exposure_id) REFERENCES exposures(id) ON DELETE CASCADE,
        FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4');

    $db->exec('CREATE TABLE IF NOT EXISTS sharia_reviews (
        id INT AUTO_INCREMENT PRIMARY KEY,
        exposure_id INT NOT NULL,
        reviewer_id INT NOT NULL,
        status ENUM("approved", "rejected") NOT NULL,
        notes TEXT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (exposure_id) REFERENCES exposures(id) ON DELETE CASCADE,
        FOREIGN KEY (reviewer_id) REFERENCES users(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4');

    $db->exec('CREATE TABLE IF NOT EXISTS audit_logs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NULL,
        action VARCHAR(50) NOT NULL,
        entity_type VARCHAR(50) NOT NULL,
        entity_id INT NOT NULL,
        details TEXT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4');
};
