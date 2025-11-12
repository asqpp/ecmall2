<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reports extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Account_model');
        $this->load->model('Daybook_model');
        $this->load->model('Invoice_model');
        $this->load->model('Purchase_model');

        if (!$this->session->userdata('user_id')) {
            redirect('login');
        }
    }

    /**
     * Trial Balance Report
     */
    public function trial_balance() {
        $as_of_date = $this->input->get('as_of_date') ?: date('Y-m-d');

        // Get trial balance from Account_model (already has the method!)
        $accounts = $this->Account_model->get_trial_balance($as_of_date);

        // Calculate totals
        $total_debit = 0;
        $total_credit = 0;

        foreach ($accounts as $account) {
            $total_debit += $account->debit_balance;
            $total_credit += $account->credit_balance;
        }

        $data = [
            'page_title' => 'Trial Balance',
            'active_menu' => 'reports',
            'main_content' => 'reports/trial_balance',
            'accounts' => $accounts,
            'total_debit' => $total_debit,
            'total_credit' => $total_credit,
            'as_of_date' => $as_of_date,
            'is_balanced' => abs($total_debit - $total_credit) < 0.01
        ];

        $this->load->view('templates/modern_layout', $data);
    }

    /**
     * Cash Book Report
     */
    public function cash_book() {
        $from_date = $this->input->get('from_date') ?: date('Y-m-01');
        $to_date = $this->input->get('to_date') ?: date('Y-m-d');

        // Get cash book from Daybook_model (already has the method!)
        $entries = $this->Daybook_model->get_cash_book($from_date, $to_date);

        $data = [
            'page_title' => 'Cash Book',
            'active_menu' => 'reports',
            'main_content' => 'reports/cash_book',
            'entries' => $entries,
            'from_date' => $from_date,
            'to_date' => $to_date
        ];

        $this->load->view('templates/modern_layout', $data);
    }

    /**
     * Bank Book Report
     */
    public function bank_book() {
        $from_date = $this->input->get('from_date') ?: date('Y-m-01');
        $to_date = $this->input->get('to_date') ?: date('Y-m-d');
        $bank_account = $this->input->get('bank_account') ?: 'BANK';

        // Get bank book from Daybook_model
        $entries = $this->Daybook_model->get_bank_book($bank_account, $from_date, $to_date);

        $data = [
            'page_title' => 'Bank Book',
            'active_menu' => 'reports',
            'main_content' => 'reports/bank_book',
            'entries' => $entries,
            'from_date' => $from_date,
            'to_date' => $to_date,
            'bank_account' => $bank_account
        ];

        $this->load->view('templates/modern_layout', $data);
    }

    /**
     * Day Book Report
     */
    public function day_book() {
        $from_date = $this->input->get('from_date') ?: date('Y-m-d');
        $to_date = $this->input->get('to_date') ?: date('Y-m-d');

        $filters = [
            'from_date' => $from_date,
            'to_date' => $to_date
        ];

        $result = $this->Daybook_model->get_paginated(1000, 1, $filters);

        $data = [
            'page_title' => 'Day Book',
            'active_menu' => 'reports',
            'main_content' => 'reports/day_book',
            'entries' => $result->data,
            'from_date' => $from_date,
            'to_date' => $to_date
        ];

        $this->load->view('templates/modern_layout', $data);
    }

    /**
     * General Ledger Report
     */
    public function general_ledger() {
        $account_code = $this->input->get('account_code');
        $from_date = $this->input->get('from_date') ?: date('Y-m-01');
        $to_date = $this->input->get('to_date') ?: date('Y-m-d');

        $ledger = [];
        $account = null;

        if ($account_code) {
            $account = $this->Account_model->get_by_code($account_code);
            $ledger = $this->Account_model->get_ledger($account_code, $from_date, $to_date);
        }

        // Get all accounts for dropdown
        $accounts = $this->Account_model->get_all();

        $data = [
            'page_title' => 'General Ledger',
            'active_menu' => 'reports',
            'main_content' => 'reports/general_ledger',
            'ledger' => $ledger,
            'account' => $account,
            'accounts' => $accounts,
            'account_code' => $account_code,
            'from_date' => $from_date,
            'to_date' => $to_date
        ];

        $this->load->view('templates/modern_layout', $data);
    }

    /**
     * Profit & Loss Statement
     */
    public function profit_loss() {
        $from_date = $this->input->get('from_date') ?: date('Y-01-01');
        $to_date = $this->input->get('to_date') ?: date('Y-m-d');

        // Get income accounts
        $this->db->select('a.account_code, a.account_name,
            COALESCE(SUM(d.credit - d.debit), 0) as amount');
        $this->db->from('accounts a');
        $this->db->join('daybook d', 'a.account_code = d.account_code', 'left');
        $this->db->where('a.account_type', 'income');
        $this->db->where('d.date >=', $from_date);
        $this->db->where('d.date <=', $to_date);
        $this->db->group_by('a.account_id');
        $income_accounts = $this->db->get()->result();

        $total_income = array_sum(array_column($income_accounts, 'amount'));

        // Get expense accounts
        $this->db->select('a.account_code, a.account_name,
            COALESCE(SUM(d.debit - d.credit), 0) as amount');
        $this->db->from('accounts a');
        $this->db->join('daybook d', 'a.account_code = d.account_code', 'left');
        $this->db->where('a.account_type', 'expense');
        $this->db->where('d.date >=', $from_date);
        $this->db->where('d.date <=', $to_date);
        $this->db->group_by('a.account_id');
        $expense_accounts = $this->db->get()->result();

        $total_expenses = array_sum(array_column($expense_accounts, 'amount'));
        $net_profit = $total_income - $total_expenses;

        $data = [
            'page_title' => 'Profit & Loss Statement',
            'active_menu' => 'reports',
            'main_content' => 'reports/profit_loss',
            'income_accounts' => $income_accounts,
            'expense_accounts' => $expense_accounts,
            'total_income' => $total_income,
            'total_expenses' => $total_expenses,
            'net_profit' => $net_profit,
            'from_date' => $from_date,
            'to_date' => $to_date
        ];

        $this->load->view('templates/modern_layout', $data);
    }

    /**
     * Sales Report
     */
    public function sales_report() {
        $from_date = $this->input->get('from_date') ?: date('Y-m-01');
        $to_date = $this->input->get('to_date') ?: date('Y-m-d');

        $filters = [
            'from_date' => $from_date,
            'to_date' => $to_date
        ];

        $result = $this->Invoice_model->get_paginated(1000, 1, '', $filters);

        // Calculate totals
        $total_sales = 0;
        $total_vat = 0;
        $total_paid = 0;
        $total_outstanding = 0;

        foreach ($result->data as $invoice) {
            $total_sales += $invoice->grand_total;
            $total_vat += $invoice->vat;
        }

        $data = [
            'page_title' => 'Sales Report',
            'active_menu' => 'reports',
            'main_content' => 'reports/sales_report',
            'invoices' => $result->data,
            'total_sales' => $total_sales,
            'total_vat' => $total_vat,
            'from_date' => $from_date,
            'to_date' => $to_date
        ];

        $this->load->view('templates/modern_layout', $data);
    }

    /**
     * Purchase Report
     */
    public function purchase_report() {
        $from_date = $this->input->get('from_date') ?: date('Y-m-01');
        $to_date = $this->input->get('to_date') ?: date('Y-m-d');

        $filters = [
            'from_date' => $from_date,
            'to_date' => $to_date
        ];

        $result = $this->Purchase_model->get_paginated(1000, 1, '', $filters);

        // Calculate totals
        $total_purchases = 0;
        $total_vat = 0;

        foreach ($result->data as $purchase) {
            $total_purchases += $purchase->grand_total_amount;
            $total_vat += ($purchase->vat ?? 0);
        }

        $data = [
            'page_title' => 'Purchase Report',
            'active_menu' => 'reports',
            'main_content' => 'reports/purchase_report',
            'purchases' => $result->data,
            'total_purchases' => $total_purchases,
            'total_vat' => $total_vat,
            'from_date' => $from_date,
            'to_date' => $to_date
        ];

        $this->load->view('templates/modern_layout', $data);
    }

    // ============================================
    // FINANCIAL REPORTS
    // ============================================

    public function balance_sheet() {
        $data['page_title'] = 'Balance Sheet';
        $data['breadcrumbs'] = [
            ['title' => 'Dashboard', 'url' => base_url('dashboard')],
            ['title' => 'Reports'],
            ['title' => 'Balance Sheet']
        ];
        $data['main_content'] = 'reports/balance_sheet';
        $this->load->view('templates/modern_layout', $data);
    }

    public function cash_flow() {
        $data['page_title'] = 'Cash Flow Statement';
        $data['breadcrumbs'] = [
            ['title' => 'Dashboard', 'url' => base_url('dashboard')],
            ['title' => 'Reports'],
            ['title' => 'Cash Flow']
        ];
        $data['main_content'] = 'reports/cash_flow';
        $this->load->view('templates/modern_layout', $data);
    }

    public function receivable() {
        $data['page_title'] = 'Accounts Receivable';
        $data['breadcrumbs'] = [
            ['title' => 'Dashboard', 'url' => base_url('dashboard')],
            ['title' => 'Reports'],
            ['title' => 'Accounts Receivable']
        ];
        $data['main_content'] = 'reports/receivable';
        $this->load->view('templates/modern_layout', $data);
    }

    public function payable() {
        $data['page_title'] = 'Accounts Payable';
        $data['breadcrumbs'] = [
            ['title' => 'Dashboard', 'url' => base_url('dashboard')],
            ['title' => 'Reports'],
            ['title' => 'Accounts Payable']
        ];
        $data['main_content'] = 'reports/payable';
        $this->load->view('templates/modern_layout', $data);
    }

    // ============================================
    // BOOKS OF ACCOUNTS
    // ============================================

    public function sales_book() {
        $data['page_title'] = 'Sales Book';
        $data['breadcrumbs'] = [
            ['title' => 'Dashboard', 'url' => base_url('dashboard')],
            ['title' => 'Reports'],
            ['title' => 'Sales Book']
        ];
        $data['main_content'] = 'reports/sales_book';
        $this->load->view('templates/modern_layout', $data);
    }

    public function purchase_book() {
        $data['page_title'] = 'Purchase Book';
        $data['breadcrumbs'] = [
            ['title' => 'Dashboard', 'url' => base_url('dashboard')],
            ['title' => 'Reports'],
            ['title' => 'Purchase Book']
        ];
        $data['main_content'] = 'reports/purchase_book';
        $this->load->view('templates/modern_layout', $data);
    }

    public function quotation_book() {
        $data['page_title'] = 'Quotation Book';
        $data['breadcrumbs'] = [
            ['title' => 'Dashboard', 'url' => base_url('dashboard')],
            ['title' => 'Reports'],
            ['title' => 'Quotation Book']
        ];
        $data['main_content'] = 'reports/quotation_book';
        $this->load->view('templates/modern_layout', $data);
    }

    // ============================================
    // SALES REPORTS
    // ============================================

    public function sales_summary() {
        $data['page_title'] = 'Sales Summary';
        $data['breadcrumbs'] = [
            ['title' => 'Dashboard', 'url' => base_url('dashboard')],
            ['title' => 'Reports'],
            ['title' => 'Sales Summary']
        ];
        $data['main_content'] = 'reports/sales_summary';
        $this->load->view('templates/modern_layout', $data);
    }

    public function customer_sales() {
        $data['page_title'] = 'Customer Wise Sales';
        $data['breadcrumbs'] = [
            ['title' => 'Dashboard', 'url' => base_url('dashboard')],
            ['title' => 'Reports'],
            ['title' => 'Customer Wise Sales']
        ];
        $data['main_content'] = 'reports/customer_sales';
        $this->load->view('templates/modern_layout', $data);
    }

    public function product_sales() {
        $data['page_title'] = 'Product Wise Sales';
        $data['breadcrumbs'] = [
            ['title' => 'Dashboard', 'url' => base_url('dashboard')],
            ['title' => 'Reports'],
            ['title' => 'Product Wise Sales']
        ];
        $data['main_content'] = 'reports/product_sales';
        $this->load->view('templates/modern_layout', $data);
    }

    public function salesman_sales() {
        $data['page_title'] = 'Salesman Wise Sales';
        $data['breadcrumbs'] = [
            ['title' => 'Dashboard', 'url' => base_url('dashboard')],
            ['title' => 'Reports'],
            ['title' => 'Salesman Wise Sales']
        ];
        $data['main_content'] = 'reports/salesman_sales';
        $this->load->view('templates/modern_layout', $data);
    }

    public function credit_sales() {
        $data['page_title'] = 'Credit Sales Report';
        $data['breadcrumbs'] = [
            ['title' => 'Dashboard', 'url' => base_url('dashboard')],
            ['title' => 'Reports'],
            ['title' => 'Credit Sales']
        ];
        $data['main_content'] = 'reports/credit_sales';
        $this->load->view('templates/modern_layout', $data);
    }

    // ============================================
    // PURCHASE REPORTS
    // ============================================

    public function purchase_summary() {
        $data['page_title'] = 'Purchase Summary';
        $data['breadcrumbs'] = [
            ['title' => 'Dashboard', 'url' => base_url('dashboard')],
            ['title' => 'Reports'],
            ['title' => 'Purchase Summary']
        ];
        $data['main_content'] = 'reports/purchase_summary';
        $this->load->view('templates/modern_layout', $data);
    }

    public function supplier_purchase() {
        $data['page_title'] = 'Supplier Wise Purchase';
        $data['breadcrumbs'] = [
            ['title' => 'Dashboard', 'url' => base_url('dashboard')],
            ['title' => 'Reports'],
            ['title' => 'Supplier Wise Purchase']
        ];
        $data['main_content'] = 'reports/supplier_purchase';
        $this->load->view('templates/modern_layout', $data);
    }

    public function product_purchase() {
        $data['page_title'] = 'Product Wise Purchase';
        $data['breadcrumbs'] = [
            ['title' => 'Dashboard', 'url' => base_url('dashboard')],
            ['title' => 'Reports'],
            ['title' => 'Product Wise Purchase']
        ];
        $data['main_content'] = 'reports/product_purchase';
        $this->load->view('templates/modern_layout', $data);
    }

    // ============================================
    // INSURANCE REPORTS
    // ============================================

    public function policy_register() {
        $data['page_title'] = 'Policy Register';
        $data['breadcrumbs'] = [
            ['title' => 'Dashboard', 'url' => base_url('dashboard')],
            ['title' => 'Reports'],
            ['title' => 'Policy Register']
        ];
        $data['main_content'] = 'reports/policy_register';
        $this->load->view('templates/modern_layout', $data);
    }

    public function claims_register() {
        $data['page_title'] = 'Claims Register';
        $data['breadcrumbs'] = [
            ['title' => 'Dashboard', 'url' => base_url('dashboard')],
            ['title' => 'Reports'],
            ['title' => 'Claims Register']
        ];
        $data['main_content'] = 'reports/claims_register';
        $this->load->view('templates/modern_layout', $data);
    }

    public function premium_analysis() {
        $data['page_title'] = 'Premium Analysis';
        $data['breadcrumbs'] = [
            ['title' => 'Dashboard', 'url' => base_url('dashboard')],
            ['title' => 'Reports'],
            ['title' => 'Premium Analysis']
        ];
        $data['main_content'] = 'reports/premium_analysis';
        $this->load->view('templates/modern_layout', $data);
    }

    public function commission() {
        $data['page_title'] = 'Commission Report';
        $data['breadcrumbs'] = [
            ['title' => 'Dashboard', 'url' => base_url('dashboard')],
            ['title' => 'Reports'],
            ['title' => 'Commission Report']
        ];
        $data['main_content'] = 'reports/commission';
        $this->load->view('templates/modern_layout', $data);
    }

    public function loss_ratio() {
        $data['page_title'] = 'Loss Ratio Analysis';
        $data['breadcrumbs'] = [
            ['title' => 'Dashboard', 'url' => base_url('dashboard')],
            ['title' => 'Reports'],
            ['title' => 'Loss Ratio Analysis']
        ];
        $data['main_content'] = 'reports/loss_ratio';
        $this->load->view('templates/modern_layout', $data);
    }

    public function renewals() {
        $data['page_title'] = 'Renewal Tracker';
        $data['breadcrumbs'] = [
            ['title' => 'Dashboard', 'url' => base_url('dashboard')],
            ['title' => 'Reports'],
            ['title' => 'Renewal Tracker']
        ];
        $data['main_content'] = 'reports/renewals';
        $this->load->view('templates/modern_layout', $data);
    }

    // ============================================
    // CUSTOMER REPORTS
    // ============================================

    public function customer_list() {
        $data['page_title'] = 'Customer List';
        $data['breadcrumbs'] = [
            ['title' => 'Dashboard', 'url' => base_url('dashboard')],
            ['title' => 'Reports'],
            ['title' => 'Customer List']
        ];
        $data['main_content'] = 'reports/customer_list';
        $this->load->view('templates/modern_layout', $data);
    }

    public function customer_ledger() {
        $data['page_title'] = 'Customer Ledger';
        $data['breadcrumbs'] = [
            ['title' => 'Dashboard', 'url' => base_url('dashboard')],
            ['title' => 'Reports'],
            ['title' => 'Customer Ledger']
        ];
        $data['main_content'] = 'reports/customer_ledger';
        $this->load->view('templates/modern_layout', $data);
    }

    public function customer_ageing() {
        $data['page_title'] = 'Customer Ageing';
        $data['breadcrumbs'] = [
            ['title' => 'Dashboard', 'url' => base_url('dashboard')],
            ['title' => 'Reports'],
            ['title' => 'Customer Ageing']
        ];
        $data['main_content'] = 'reports/customer_ageing';
        $this->load->view('templates/modern_layout', $data);
    }

    public function outstanding() {
        $data['page_title'] = 'Outstanding Report';
        $data['breadcrumbs'] = [
            ['title' => 'Dashboard', 'url' => base_url('dashboard')],
            ['title' => 'Reports'],
            ['title' => 'Outstanding Report']
        ];
        $data['main_content'] = 'reports/outstanding';
        $this->load->view('templates/modern_layout', $data);
    }

    // ============================================
    // HR REPORTS
    // ============================================

    public function attendance_report() {
        $data['page_title'] = 'Attendance Report';
        $data['breadcrumbs'] = [
            ['title' => 'Dashboard', 'url' => base_url('dashboard')],
            ['title' => 'Reports'],
            ['title' => 'Attendance Report']
        ];
        $data['main_content'] = 'reports/attendance_report';
        $this->load->view('templates/modern_layout', $data);
    }

    public function leave_report() {
        $data['page_title'] = 'Leave Report';
        $data['breadcrumbs'] = [
            ['title' => 'Dashboard', 'url' => base_url('dashboard')],
            ['title' => 'Reports'],
            ['title' => 'Leave Report']
        ];
        $data['main_content'] = 'reports/leave_report';
        $this->load->view('templates/modern_layout', $data);
    }

    public function payroll_report() {
        $data['page_title'] = 'Payroll Report';
        $data['breadcrumbs'] = [
            ['title' => 'Dashboard', 'url' => base_url('dashboard')],
            ['title' => 'Reports'],
            ['title' => 'Payroll Report']
        ];
        $data['main_content'] = 'reports/payroll_report';
        $this->load->view('templates/modern_layout', $data);
    }

    public function salary_register() {
        $data['page_title'] = 'Salary Register';
        $data['breadcrumbs'] = [
            ['title' => 'Dashboard', 'url' => base_url('dashboard')],
            ['title' => 'Reports'],
            ['title' => 'Salary Register']
        ];
        $data['main_content'] = 'reports/salary_register';
        $this->load->view('templates/modern_layout', $data);
    }
}
