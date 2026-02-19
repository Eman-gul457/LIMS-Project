CREATE DATABASE IF NOT EXISTS mini_lims CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE mini_lims;

CREATE TABLE IF NOT EXISTS samples (
    sample_id INT AUTO_INCREMENT PRIMARY KEY,
    patient_name VARCHAR(120) NOT NULL,
    sample_type ENUM('Blood', 'Water', 'Soil', 'Urine') NOT NULL,
    test_name VARCHAR(120) NOT NULL,
    date_received DATE NOT NULL,
    status ENUM('Pending', 'In Progress', 'Completed') NOT NULL DEFAULT 'Pending',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS tests (
    test_id INT AUTO_INCREMENT PRIMARY KEY,
    sample_id INT NOT NULL,
    test_name VARCHAR(120) NOT NULL,
    technician_name VARCHAR(120) NOT NULL,
    status ENUM('Pending', 'In Progress', 'Completed') NOT NULL DEFAULT 'Pending',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_tests_sample_id (sample_id),
    CONSTRAINT fk_tests_sample FOREIGN KEY (sample_id)
        REFERENCES samples (sample_id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
);

CREATE TABLE IF NOT EXISTS results (
    result_id INT AUTO_INCREMENT PRIMARY KEY,
    sample_id INT NOT NULL,
    result_value TEXT NOT NULL,
    result_date DATE NOT NULL,
    technician_name VARCHAR(120) NOT NULL,
    approval_status ENUM('Pending', 'Approved', 'Rejected') NOT NULL DEFAULT 'Pending',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_results_sample_id (sample_id),
    CONSTRAINT fk_results_sample FOREIGN KEY (sample_id)
        REFERENCES samples (sample_id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
);
