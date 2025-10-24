-- Dummy data dump for Hedging Syariah application
-- Generated to populate development environment with sample records.

SET FOREIGN_KEY_CHECKS = 0;
TRUNCATE TABLE audit_logs;
TRUNCATE TABLE sharia_reviews;
TRUNCATE TABLE hedges;
TRUNCATE TABLE exposures;
TRUNCATE TABLE users;
SET FOREIGN_KEY_CHECKS = 1;

INSERT INTO users (id, username, password_hash, role, created_at) VALUES
  (1, 'admin', '$2y$12$b6EqKK3Re5P5GNcoFuBU5ucHUOe3uUkFEwdkUeX1S80be9M8Gp4EO', 'admin', NOW()),
  (2, 'auditor', '$2y$12$b6EqKK3Re5P5GNcoFuBU5ucHUOe3uUkFEwdkUeX1S80be9M8Gp4EO', 'auditor', NOW()),
  (3, 'user', '$2y$12$b6EqKK3Re5P5GNcoFuBU5ucHUOe3uUkFEwdkUeX1S80be9M8Gp4EO', 'user', NOW());

INSERT INTO exposures (id, user_id, description, counterparty, currency, notional, exposure_date, purpose, cost_transparent, penalty_clause, underlying_file, shariah_status, shariah_notes, created_at, updated_at) VALUES
  (1, 3, 'USD receivable from export', 'PT Amanah Sejahtera', 'USD', 100000.00, '2024-01-15', 'tahawwut', 1, 0, 'invoice_export.pdf', 'approved', 'Underlying evidence verified; costs disclosed.', NOW(), NOW()),
  (2, 3, 'MYR payable for raw materials', 'Global Commodities Bhd', 'MYR', 250000.00, '2024-02-01', 'tahawwut', 1, 0, 'purchase_order.pdf', 'approved', 'Purpose hedged for tahawwut; documentation complete.', NOW(), NOW()),
  (3, 3, 'JPY loan repayment', 'Sakura Finance', 'JPY', 15000000.00, '2024-03-20', 'tahawwut', 0, 0, 'loan_agreement.pdf', 'rejected', 'Biaya tambahan belum transparan.', NOW(), NOW());

INSERT INTO hedges (id, exposure_id, akad_type, notional, rate, start_date, end_date, cost_breakdown, no_riba_clause, document_path, created_by, created_at, updated_at) VALUES
  (1, 1, 'waad_fx', 98000.00, 1.5250, '2024-01-20', '2024-04-20', 'Margin 0.8%, bank fee 0.2%', 1, 'contracts/waad_fx_20240120.pdf', 1, NOW(), NOW()),
  (2, 2, 'murabahah', 245000.00, 3.2500, '2024-02-10', '2024-08-10', 'Murabahah cost plus disclosed at 2.5%, admin fee 0.5%', 1, 'contracts/murabahah_20240210.pdf', 1, NOW(), NOW());

INSERT INTO sharia_reviews (id, exposure_id, reviewer_id, status, notes, created_at) VALUES
  (1, 1, 2, 'approved', 'Underlying valid and tujuan tahawwut terpenuhi.', NOW()),
  (2, 2, 2, 'approved', 'Transparansi biaya telah dikonfirmasi.', NOW()),
  (3, 3, 2, 'rejected', 'Dokumentasi biaya tidak lengkap.', NOW());

INSERT INTO audit_logs (id, user_id, action, entity_type, entity_id, details, created_at) VALUES
  (1, 1, 'create', 'exposure', 1, 'Admin created exposure USD receivable.', NOW()),
  (2, 1, 'create', 'hedge', 1, 'Admin recorded waad_fx hedge untuk exposure 1.', NOW()),
  (3, 2, 'review', 'exposure', 1, 'Auditor approved exposure 1.', NOW()),
  (4, 1, 'create', 'hedge', 2, 'Admin recorded murabahah hedge untuk exposure 2.', NOW()),
  (5, 2, 'review', 'exposure', 3, 'Auditor rejected exposure 3 karena biaya tidak transparan.', NOW());
