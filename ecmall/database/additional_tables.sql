-- Additional Tables for Insurance ERP System
-- Generated: 2025-11-12
-- Purpose: Add missing modules (HRM, Settings, Notifications, Insurance, etc.)

-- ============================================
-- NOTIFICATIONS MODULE
-- ============================================

CREATE TABLE IF NOT EXISTS `notifications` (
  `notification_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `type` enum('info','success','warning','danger') DEFAULT 'info',
  `icon` varchar(50) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`notification_id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_is_read` (`is_read`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Sample notifications
INSERT INTO `notifications` (`user_id`, `title`, `message`, `type`, `icon`, `link`, `is_read`) VALUES
(1, 'New Invoice Created', 'Invoice #INV-001 has been created for AED 15,500', 'success', 'file-text', '/sales/view/1', 0),
(1, 'Payment Received', 'Payment of AED 5,000 received from ABC Motors', 'success', 'dollar-sign', '/receipts/view/1', 0),
(1, 'Policy Expiring Soon', 'Policy #POL-123 expiring in 15 days', 'warning', 'shield', '/insurance/policies/view/1', 0),
(1, 'Attendance Alert', '5 employees marked absent today', 'warning', 'users', '/hr/attendance', 0),
(1, 'Low Stock Alert', 'Product XYZ running low on stock', 'danger', 'package', '/products/view/1', 0);

-- ============================================
-- SETTINGS MODULE
-- ============================================

CREATE TABLE IF NOT EXISTS `settings` (
  `setting_id` int(11) NOT NULL AUTO_INCREMENT,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text,
  `setting_group` varchar(50) DEFAULT 'general',
  `setting_type` enum('text','number','boolean','json','date') DEFAULT 'text',
  `description` varchar(255) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`setting_id`),
  UNIQUE KEY `idx_setting_key` (`setting_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Default settings
INSERT INTO `settings` (`setting_key`, `setting_value`, `setting_group`, `setting_type`, `description`) VALUES
('company_name', 'NA-FIX Insurance Solutions', 'company', 'text', 'Company Name'),
('company_address', 'Dubai, UAE', 'company', 'text', 'Company Address'),
('company_phone', '+971-XX-XXXXXXX', 'company', 'text', 'Company Phone'),
('company_email', 'info@nafix.com', 'company', 'text', 'Company Email'),
('company_website', 'www.nafix.com', 'company', 'text', 'Company Website'),
('company_logo', '', 'company', 'text', 'Company Logo Path'),
('currency_code', 'AED', 'general', 'text', 'Default Currency Code'),
('currency_symbol', 'AED', 'general', 'text', 'Currency Symbol'),
('date_format', 'd-m-Y', 'general', 'text', 'Date Format'),
('time_format', 'H:i:s', 'general', 'text', 'Time Format'),
('timezone', 'Asia/Dubai', 'general', 'text', 'Timezone'),
('financial_year_start', '01-01', 'accounting', 'text', 'Financial Year Start (MM-DD)'),
('invoice_prefix', 'INV-', 'invoicing', 'text', 'Invoice Number Prefix'),
('quotation_prefix', 'QUO-', 'invoicing', 'text', 'Quotation Number Prefix'),
('receipt_prefix', 'RCP-', 'invoicing', 'text', 'Receipt Number Prefix'),
('payment_prefix', 'PAY-', 'invoicing', 'text', 'Payment Number Prefix'),
('vat_rate', '5', 'accounting', 'number', 'Default VAT Rate (%)'),
('enable_vat', '1', 'accounting', 'boolean', 'Enable VAT Calculation'),
('backup_frequency', 'daily', 'system', 'text', 'Backup Frequency'),
('notifications_enabled', '1', 'system', 'boolean', 'Enable Notifications');

-- ============================================
-- HR & PAYROLL MODULE
-- ============================================

CREATE TABLE IF NOT EXISTS `employees` (
  `employee_id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_code` varchar(50) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `mobile` varchar(20) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `gender` enum('male','female','other') DEFAULT NULL,
  `address` text,
  `city` varchar(100) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `country` varchar(100) DEFAULT 'UAE',
  `passport_no` varchar(50) DEFAULT NULL,
  `visa_no` varchar(50) DEFAULT NULL,
  `visa_expiry` date DEFAULT NULL,
  `emirates_id` varchar(50) DEFAULT NULL,
  `department` varchar(100) DEFAULT NULL,
  `designation` varchar(100) DEFAULT NULL,
  `joining_date` date DEFAULT NULL,
  `employment_type` enum('permanent','contract','temporary','intern') DEFAULT 'permanent',
  `salary` decimal(15,2) DEFAULT 0.00,
  `bank_name` varchar(100) DEFAULT NULL,
  `bank_account` varchar(50) DEFAULT NULL,
  `iban` varchar(50) DEFAULT NULL,
  `status` enum('active','inactive','terminated') DEFAULT 'active',
  `photo` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`employee_id`),
  UNIQUE KEY `idx_employee_code` (`employee_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Sample employees
INSERT INTO `employees` (`employee_code`, `first_name`, `last_name`, `email`, `mobile`, `date_of_birth`, `gender`, `department`, `designation`, `joining_date`, `salary`, `status`) VALUES
('EMP001', 'Ahmed', 'Hassan', 'ahmed.hassan@nafix.com', '+971-50-1234567', '1985-05-15', 'male', 'Sales', 'Sales Manager', '2020-01-10', 8000.00, 'active'),
('EMP002', 'Fatima', 'Ali', 'fatima.ali@nafix.com', '+971-50-2345678', '1990-08-20', 'female', 'Accounts', 'Accountant', '2020-03-15', 6000.00, 'active'),
('EMP003', 'Mohammed', 'Rashid', 'mohammed.rashid@nafix.com', '+971-50-3456789', '1988-12-10', 'male', 'Operations', 'Operations Head', '2019-06-01', 9000.00, 'active'),
('EMP004', 'Sara', 'Ahmed', 'sara.ahmed@nafix.com', '+971-50-4567890', '1992-03-25', 'female', 'HR', 'HR Executive', '2021-01-20', 5500.00, 'active'),
('EMP005', 'Khalid', 'Ibrahim', 'khalid.ibrahim@nafix.com', '+971-50-5678901', '1987-07-14', 'male', 'IT', 'IT Support', '2021-06-10', 5000.00, 'active');

CREATE TABLE IF NOT EXISTS `departments` (
  `department_id` int(11) NOT NULL AUTO_INCREMENT,
  `department_name` varchar(100) NOT NULL,
  `department_head` int(11) DEFAULT NULL,
  `description` text,
  `status` enum('active','inactive') DEFAULT 'active',
  PRIMARY KEY (`department_id`),
  KEY `idx_department_head` (`department_head`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `departments` (`department_name`, `description`, `status`) VALUES
('Sales', 'Sales and Marketing Department', 'active'),
('Accounts', 'Accounts and Finance Department', 'active'),
('Operations', 'Operations Department', 'active'),
('HR', 'Human Resources Department', 'active'),
('IT', 'Information Technology Department', 'active'),
('Customer Service', 'Customer Service Department', 'active');

CREATE TABLE IF NOT EXISTS `attendance` (
  `attendance_id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `attendance_date` date NOT NULL,
  `check_in` time DEFAULT NULL,
  `check_out` time DEFAULT NULL,
  `status` enum('present','absent','half_day','leave','holiday') DEFAULT 'present',
  `working_hours` decimal(5,2) DEFAULT 0.00,
  `overtime_hours` decimal(5,2) DEFAULT 0.00,
  `remarks` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`attendance_id`),
  KEY `idx_employee_id` (`employee_id`),
  KEY `idx_attendance_date` (`attendance_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `leave_types` (
  `leave_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `leave_type_name` varchar(100) NOT NULL,
  `days_per_year` int(11) DEFAULT 0,
  `carry_forward` tinyint(1) DEFAULT 0,
  `description` text,
  PRIMARY KEY (`leave_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `leave_types` (`leave_type_name`, `days_per_year`, `carry_forward`, `description`) VALUES
('Annual Leave', 30, 1, 'Annual paid leave'),
('Sick Leave', 15, 0, 'Sick leave with medical certificate'),
('Emergency Leave', 5, 0, 'Emergency leave'),
('Maternity Leave', 60, 0, 'Maternity leave for female employees'),
('Paternity Leave', 5, 0, 'Paternity leave for male employees');

CREATE TABLE IF NOT EXISTS `leave_applications` (
  `leave_id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `leave_type_id` int(11) NOT NULL,
  `from_date` date NOT NULL,
  `to_date` date NOT NULL,
  `total_days` int(11) NOT NULL,
  `reason` text,
  `status` enum('pending','approved','rejected','cancelled') DEFAULT 'pending',
  `approved_by` int(11) DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `remarks` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`leave_id`),
  KEY `idx_employee_id` (`employee_id`),
  KEY `idx_leave_type_id` (`leave_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `payroll` (
  `payroll_id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `month` int(11) NOT NULL,
  `year` int(11) NOT NULL,
  `basic_salary` decimal(15,2) DEFAULT 0.00,
  `allowances` decimal(15,2) DEFAULT 0.00,
  `overtime_pay` decimal(15,2) DEFAULT 0.00,
  `gross_salary` decimal(15,2) DEFAULT 0.00,
  `deductions` decimal(15,2) DEFAULT 0.00,
  `net_salary` decimal(15,2) DEFAULT 0.00,
  `payment_date` date DEFAULT NULL,
  `payment_method` enum('bank_transfer','cash','cheque') DEFAULT 'bank_transfer',
  `status` enum('draft','processed','paid') DEFAULT 'draft',
  `remarks` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`payroll_id`),
  KEY `idx_employee_id` (`employee_id`),
  KEY `idx_month_year` (`month`,`year`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `salary_components` (
  `component_id` int(11) NOT NULL AUTO_INCREMENT,
  `component_name` varchar(100) NOT NULL,
  `component_type` enum('earning','deduction') NOT NULL,
  `calculation_type` enum('fixed','percentage') DEFAULT 'fixed',
  `amount` decimal(15,2) DEFAULT 0.00,
  `percentage` decimal(5,2) DEFAULT 0.00,
  `description` text,
  `status` enum('active','inactive') DEFAULT 'active',
  PRIMARY KEY (`component_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `salary_components` (`component_name`, `component_type`, `calculation_type`, `amount`, `status`) VALUES
('Housing Allowance', 'earning', 'fixed', 2000.00, 'active'),
('Transport Allowance', 'earning', 'fixed', 500.00, 'active'),
('Food Allowance', 'earning', 'fixed', 300.00, 'active'),
('Mobile Allowance', 'earning', 'fixed', 200.00, 'active'),
('Provident Fund', 'deduction', 'percentage', 0.00, 'active'),
('Professional Tax', 'deduction', 'fixed', 0.00, 'active');

-- ============================================
-- INSURANCE MODULE
-- ============================================

CREATE TABLE IF NOT EXISTS `insurance_policies` (
  `policy_id` int(11) NOT NULL AUTO_INCREMENT,
  `policy_number` varchar(100) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `broker_id` int(11) DEFAULT NULL,
  `agent_id` int(11) DEFAULT NULL,
  `product_id` int(11) NOT NULL,
  `policy_type` varchar(100) DEFAULT NULL,
  `sum_insured` decimal(15,2) DEFAULT 0.00,
  `premium_amount` decimal(15,2) DEFAULT 0.00,
  `vat_amount` decimal(15,2) DEFAULT 0.00,
  `total_premium` decimal(15,2) DEFAULT 0.00,
  `issue_date` date NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `payment_frequency` enum('annual','semi_annual','quarterly','monthly') DEFAULT 'annual',
  `status` enum('active','expired','cancelled','renewed') DEFAULT 'active',
  `insurance_company` varchar(200) DEFAULT NULL,
  `policy_document` varchar(255) DEFAULT NULL,
  `remarks` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`policy_id`),
  UNIQUE KEY `idx_policy_number` (`policy_number`),
  KEY `idx_customer_id` (`customer_id`),
  KEY `idx_broker_id` (`broker_id`),
  KEY `idx_agent_id` (`agent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `insurance_claims` (
  `claim_id` int(11) NOT NULL AUTO_INCREMENT,
  `claim_number` varchar(100) NOT NULL,
  `policy_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `claim_date` date NOT NULL,
  `incident_date` date DEFAULT NULL,
  `claim_type` varchar(100) DEFAULT NULL,
  `claim_amount` decimal(15,2) DEFAULT 0.00,
  `approved_amount` decimal(15,2) DEFAULT 0.00,
  `status` enum('submitted','under_review','approved','rejected','settled') DEFAULT 'submitted',
  `description` text,
  `documents` text,
  `settled_date` date DEFAULT NULL,
  `remarks` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`claim_id`),
  UNIQUE KEY `idx_claim_number` (`claim_number`),
  KEY `idx_policy_id` (`policy_id`),
  KEY `idx_customer_id` (`customer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `policy_renewals` (
  `renewal_id` int(11) NOT NULL AUTO_INCREMENT,
  `policy_id` int(11) NOT NULL,
  `old_policy_number` varchar(100) DEFAULT NULL,
  `new_policy_number` varchar(100) NOT NULL,
  `renewal_date` date NOT NULL,
  `new_start_date` date NOT NULL,
  `new_end_date` date NOT NULL,
  `old_premium` decimal(15,2) DEFAULT 0.00,
  `new_premium` decimal(15,2) DEFAULT 0.00,
  `premium_change` decimal(15,2) DEFAULT 0.00,
  `status` enum('pending','completed','cancelled') DEFAULT 'pending',
  `remarks` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`renewal_id`),
  KEY `idx_policy_id` (`policy_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `policy_endorsements` (
  `endorsement_id` int(11) NOT NULL AUTO_INCREMENT,
  `policy_id` int(11) NOT NULL,
  `endorsement_number` varchar(100) NOT NULL,
  `endorsement_date` date NOT NULL,
  `endorsement_type` varchar(100) DEFAULT NULL,
  `description` text,
  `premium_impact` decimal(15,2) DEFAULT 0.00,
  `effective_date` date DEFAULT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`endorsement_id`),
  KEY `idx_policy_id` (`policy_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- ADDITIONAL ACCOUNTING TABLES
-- ============================================

CREATE TABLE IF NOT EXISTS `bank_accounts` (
  `bank_account_id` int(11) NOT NULL AUTO_INCREMENT,
  `account_name` varchar(200) NOT NULL,
  `bank_name` varchar(200) NOT NULL,
  `account_number` varchar(50) NOT NULL,
  `iban` varchar(50) DEFAULT NULL,
  `swift_code` varchar(50) DEFAULT NULL,
  `branch` varchar(200) DEFAULT NULL,
  `currency` varchar(10) DEFAULT 'AED',
  `opening_balance` decimal(15,2) DEFAULT 0.00,
  `current_balance` decimal(15,2) DEFAULT 0.00,
  `account_type` enum('savings','current','fixed_deposit') DEFAULT 'current',
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`bank_account_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `bank_accounts` (`account_name`, `bank_name`, `account_number`, `iban`, `opening_balance`, `current_balance`, `status`) VALUES
('ADCB Current Account', 'Abu Dhabi Commercial Bank', '1234567890', 'AE070331234567890123456', 50000.00, 75000.00, 'active'),
('Emirates NBD', 'Emirates NBD', '9876543210', 'AE070331234567890987654', 30000.00, 45000.00, 'active');

CREATE TABLE IF NOT EXISTS `bank_reconciliation` (
  `reconciliation_id` int(11) NOT NULL AUTO_INCREMENT,
  `bank_account_id` int(11) NOT NULL,
  `reconciliation_date` date NOT NULL,
  `statement_date` date NOT NULL,
  `book_balance` decimal(15,2) DEFAULT 0.00,
  `bank_balance` decimal(15,2) DEFAULT 0.00,
  `difference` decimal(15,2) DEFAULT 0.00,
  `status` enum('in_progress','reconciled','discrepancy') DEFAULT 'in_progress',
  `reconciled_by` int(11) DEFAULT NULL,
  `remarks` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`reconciliation_id`),
  KEY `idx_bank_account_id` (`bank_account_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `credit_notes` (
  `credit_note_id` int(11) NOT NULL AUTO_INCREMENT,
  `credit_note_number` varchar(50) NOT NULL,
  `invoice_id` int(11) DEFAULT NULL,
  `customer_id` int(11) NOT NULL,
  `credit_date` date NOT NULL,
  `reason` text,
  `amount` decimal(15,2) DEFAULT 0.00,
  `vat_amount` decimal(15,2) DEFAULT 0.00,
  `total_amount` decimal(15,2) DEFAULT 0.00,
  `status` enum('draft','issued','applied') DEFAULT 'draft',
  `remarks` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`credit_note_id`),
  UNIQUE KEY `idx_credit_note_number` (`credit_note_number`),
  KEY `idx_customer_id` (`customer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `debit_notes` (
  `debit_note_id` int(11) NOT NULL AUTO_INCREMENT,
  `debit_note_number` varchar(50) NOT NULL,
  `purchase_id` int(11) DEFAULT NULL,
  `supplier_id` int(11) NOT NULL,
  `debit_date` date NOT NULL,
  `reason` text,
  `amount` decimal(15,2) DEFAULT 0.00,
  `vat_amount` decimal(15,2) DEFAULT 0.00,
  `total_amount` decimal(15,2) DEFAULT 0.00,
  `status` enum('draft','issued','applied') DEFAULT 'draft',
  `remarks` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`debit_note_id`),
  UNIQUE KEY `idx_debit_note_number` (`debit_note_number`),
  KEY `idx_supplier_id` (`supplier_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- USER MANAGEMENT
-- ============================================

CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(150) NOT NULL,
  `full_name` varchar(200) NOT NULL,
  `role` enum('admin','manager','accountant','user') DEFAULT 'user',
  `employee_id` int(11) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `last_login` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `idx_username` (`username`),
  UNIQUE KEY `idx_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Default admin user (password: admin123)
INSERT INTO `users` (`username`, `password`, `email`, `full_name`, `role`, `status`) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@nafix.com', 'System Administrator', 'admin', 'active');

-- ============================================
-- ACTIVITY LOGS
-- ============================================

CREATE TABLE IF NOT EXISTS `activity_logs` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `module` varchar(100) DEFAULT NULL,
  `action` varchar(100) DEFAULT NULL,
  `record_id` int(11) DEFAULT NULL,
  `description` text,
  `ip_address` varchar(50) DEFAULT NULL,
  `user_agent` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`log_id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_module` (`module`),
  KEY `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- COMPANY & BRANCH MANAGEMENT
-- ============================================

CREATE TABLE IF NOT EXISTS `companies` (
  `company_id` int(11) NOT NULL AUTO_INCREMENT,
  `company_code` varchar(50) NOT NULL,
  `company_name` varchar(200) NOT NULL,
  `legal_name` varchar(200) DEFAULT NULL,
  `registration_number` varchar(100) DEFAULT NULL,
  `tax_number` varchar(100) DEFAULT NULL,
  `address` text,
  `city` varchar(100) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `country` varchar(100) DEFAULT 'UAE',
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `website` varchar(200) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`company_id`),
  UNIQUE KEY `idx_company_code` (`company_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `companies` (`company_code`, `company_name`, `legal_name`, `phone`, `email`, `status`) VALUES
('COMP001', 'NA-FIX Insurance Solutions', 'NA-FIX Insurance Solutions LLC', '+971-XX-XXXXXXX', 'info@nafix.com', 'active');

CREATE TABLE IF NOT EXISTS `branches` (
  `branch_id` int(11) NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NOT NULL,
  `branch_code` varchar(50) NOT NULL,
  `branch_name` varchar(200) NOT NULL,
  `address` text,
  `city` varchar(100) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `country` varchar(100) DEFAULT 'UAE',
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `manager_id` int(11) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`branch_id`),
  UNIQUE KEY `idx_branch_code` (`branch_code`),
  KEY `idx_company_id` (`company_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `branches` (`company_id`, `branch_code`, `branch_name`, `city`, `status`) VALUES
(1, 'BR001', 'Head Office', 'Dubai', 'active'),
(1, 'BR002', 'Abu Dhabi Branch', 'Abu Dhabi', 'active'),
(1, 'BR003', 'Sharjah Branch', 'Sharjah', 'active');

-- ============================================
-- INDEXES FOR PERFORMANCE
-- ============================================

ALTER TABLE `invoice` ADD INDEX `idx_date` (`date`);
ALTER TABLE `invoice` ADD INDEX `idx_payment_status` (`payment_status`);
ALTER TABLE `product_purchase` ADD INDEX `idx_purchase_date` (`purchase_date`);
ALTER TABLE `receipt` ADD INDEX `idx_receipt_date` (`receipt_date`);
ALTER TABLE `daybook` ADD INDEX `idx_date` (`date`);
ALTER TABLE `daybook` ADD INDEX `idx_account_code` (`account_code`);

-- End of SQL file
