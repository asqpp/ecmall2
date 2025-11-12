<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Account_model extends MY_Model {

    protected $table = 'accounts';
    protected $primary_key = 'account_id';
    protected $timestamps = false;

    /**
     * Get accounts with pagination and search
     */
    public function get_paginated($per_page = 25, $page = 1, $search = '', $account_type = null) {
        $offset = ($page - 1) * $per_page;

        $this->db->select('*');
        $this->db->from($this->table);

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('account_name', $search);
            $this->db->or_like('account_code', $search);
            $this->db->group_end();
        }

        if ($account_type) {
            $this->db->where('account_type', $account_type);
        }

        // Get total count
        $total = $this->db->count_all_results('', false);

        // Get paginated results
        $this->db->limit($per_page, $offset);
        $this->db->order_by('account_code', 'ASC');
        $data = $this->db->get()->result();

        return (object) [
            'data' => $data,
            'total' => $total,
            'per_page' => $per_page,
            'current_page' => $page,
            'total_pages' => ceil($total / $per_page)
        ];
    }

    /**
     * Get account by code
     */
    public function get_by_code($account_code) {
        $this->db->where('account_code', $account_code);
        return $this->db->get($this->table)->row();
    }

    /**
     * Get all accounts for dropdown
     */
    public function get_for_dropdown($account_type = null) {
        $this->db->select('account_id, account_code, account_name, account_type');
        $this->db->from($this->table);

        if ($account_type) {
            $this->db->where('account_type', $account_type);
        }

        $this->db->order_by('account_code', 'ASC');

        return $this->db->get()->result();
    }

    /**
     * Get account ledger with balance
     */
    public function get_ledger($account_code, $from_date = null, $to_date = null) {
        $this->db->select('*');
        $this->db->from('daybook');
        $this->db->where('account_code', $account_code);

        if ($from_date) {
            $this->db->where('date >=', $from_date);
        }

        if ($to_date) {
            $this->db->where('date <=', $to_date);
        }

        $this->db->order_by('date', 'ASC');
        $this->db->order_by('id', 'ASC');

        $entries = $this->db->get()->result();

        // Calculate running balance
        $running_balance = 0;
        foreach ($entries as $entry) {
            $running_balance += ($entry->debit - $entry->credit);
            $entry->running_balance = $running_balance;
        }

        return $entries;
    }

    /**
     * Get account balance
     */
    public function get_balance($account_code, $as_of_date = null) {
        $this->db->select_sum('debit');
        $this->db->select_sum('credit');
        $this->db->from('daybook');
        $this->db->where('account_code', $account_code);

        if ($as_of_date) {
            $this->db->where('date <=', $as_of_date);
        }

        $result = $this->db->get()->row();

        $debit_total = $result->debit ?? 0;
        $credit_total = $result->credit ?? 0;

        return $debit_total - $credit_total;
    }

    /**
     * Get trial balance (all accounts with balances)
     */
    public function get_trial_balance($as_of_date = null) {
        $this->db->select('a.account_code, a.account_name, a.account_type,
            COALESCE(SUM(d.debit), 0) as total_debit,
            COALESCE(SUM(d.credit), 0) as total_credit
        ');
        $this->db->from($this->table . ' a');
        $this->db->join('daybook d', 'a.account_code = d.account_code', 'left');

        if ($as_of_date) {
            $this->db->where('d.date <=', $as_of_date);
        }

        $this->db->group_by('a.account_id');
        $this->db->order_by('a.account_code', 'ASC');

        $accounts = $this->db->get()->result();

        // Calculate balance for each account
        foreach ($accounts as $account) {
            $account->balance = $account->total_debit - $account->total_credit;

            // Determine debit/credit side based on balance
            if ($account->balance >= 0) {
                $account->debit_balance = $account->balance;
                $account->credit_balance = 0;
            } else {
                $account->debit_balance = 0;
                $account->credit_balance = abs($account->balance);
            }
        }

        return $accounts;
    }

    /**
     * Get account types for dropdown
     */
    public function get_account_types() {
        return [
            'asset' => 'Asset',
            'liability' => 'Liability',
            'equity' => 'Equity',
            'income' => 'Income',
            'expense' => 'Expense'
        ];
    }

    /**
     * Check if account code already exists
     */
    public function code_exists($account_code, $exclude_id = null) {
        $this->db->where('account_code', $account_code);
        if ($exclude_id) {
            $this->db->where($this->primary_key . ' !=', $exclude_id);
        }
        return $this->db->count_all_results($this->table) > 0;
    }

    /**
     * Get account categories with accounts grouped by category
     */
    public function get_account_categories() {
        return [
            [
                'name' => 'Current Assets',
                'color' => 'green',
                'type' => 'asset',
                'accounts' => ['Cash', 'Bank Accounts', 'Accounts Receivable', 'Inventory', 'Prepaid Expenses']
            ],
            [
                'name' => 'Fixed Assets',
                'color' => 'blue',
                'type' => 'asset',
                'accounts' => ['Land & Building', 'Furniture & Fixtures', 'Vehicles', 'Equipment', 'Computers']
            ],
            [
                'name' => 'Current Liabilities',
                'color' => 'red',
                'type' => 'liability',
                'accounts' => ['Accounts Payable', 'Short Term Loans', 'Outstanding Expenses', 'Provisions']
            ],
            [
                'name' => 'Long Term Liabilities',
                'color' => 'orange',
                'type' => 'liability',
                'accounts' => ['Long Term Loans', 'Debentures', 'Mortgage Loans']
            ],
            [
                'name' => 'Capital',
                'color' => 'purple',
                'type' => 'equity',
                'accounts' => ['Owner\'s Capital', 'Retained Earnings', 'Reserves', 'Drawings']
            ],
            [
                'name' => 'Revenue/Income',
                'color' => 'emerald',
                'type' => 'income',
                'accounts' => ['Sales Revenue', 'Service Income', 'Commission Income', 'Other Income']
            ],
            [
                'name' => 'Direct Expenses',
                'color' => 'amber',
                'type' => 'expense',
                'accounts' => ['Purchase', 'Direct Labour', 'Manufacturing Expenses']
            ],
            [
                'name' => 'Indirect Expenses',
                'color' => 'rose',
                'type' => 'expense',
                'accounts' => ['Salaries', 'Rent', 'Utilities', 'Office Expenses', 'Depreciation', 'Interest']
            ]
        ];
    }

    /**
     * Get accounts grouped by category
     */
    public function get_accounts_by_category() {
        $categories = $this->get_account_categories();
        $result = [];

        foreach ($categories as $category) {
            $category_data = $category;
            $category_data['account_details'] = [];

            // Get actual accounts from database for this category type
            $this->db->where('account_type', $category['type']);
            $this->db->order_by('account_code', 'ASC');
            $accounts = $this->db->get($this->table)->result();

            $category_data['account_details'] = $accounts;
            $category_data['total_accounts'] = count($accounts);

            $result[] = $category_data;
        }

        return $result;
    }

    /**
     * Get account statistics by category
     */
    public function get_category_statistics() {
        $stats = [];

        foreach ($this->get_account_categories() as $category) {
            $this->db->where('account_type', $category['type']);
            $count = $this->db->count_all_results($this->table);

            $stats[] = [
                'name' => $category['name'],
                'color' => $category['color'],
                'type' => $category['type'],
                'count' => $count
            ];
        }

        return $stats;
    }

    /**
     * Create default accounts for a category
     */
    public function create_default_accounts($category_name) {
        $categories = $this->get_account_categories();
        $category = null;

        // Find the category
        foreach ($categories as $cat) {
            if ($cat['name'] === $category_name) {
                $category = $cat;
                break;
            }
        }

        if (!$category) {
            return false;
        }

        $created_count = 0;

        // Create each account in the category
        foreach ($category['accounts'] as $account_name) {
            // Generate account code
            $account_code = $this->generate_account_code($category['type']);

            // Check if account already exists by name
            $this->db->where('account_name', $account_name);
            $exists = $this->db->count_all_results($this->table);

            if ($exists == 0) {
                $data = [
                    'account_code' => $account_code,
                    'account_name' => $account_name,
                    'account_type' => $category['type'],
                    'category' => $category_name,
                    'opening_balance' => 0,
                    'current_balance' => 0
                ];

                if ($this->db->insert($this->table, $data)) {
                    $created_count++;
                }
            }
        }

        return $created_count;
    }

    /**
     * Generate account code based on type
     */
    private function generate_account_code($account_type) {
        // Define prefix ranges
        $prefixes = [
            'asset' => '1',
            'liability' => '2',
            'equity' => '3',
            'income' => '4',
            'expense' => '5'
        ];

        $prefix = $prefixes[$account_type] ?? '9';

        // Get last account code for this type
        $this->db->select('account_code');
        $this->db->like('account_code', $prefix, 'after');
        $this->db->order_by('account_code', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get($this->table);

        if ($query->num_rows() > 0) {
            $last_code = $query->row()->account_code;
            $number = intval(substr($last_code, 1)) + 1;
        } else {
            $number = 1;
        }

        return $prefix . str_pad($number, 4, '0', STR_PAD_LEFT);
    }
}
