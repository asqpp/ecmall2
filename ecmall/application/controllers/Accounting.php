<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Accounting extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Account_model');
        $this->load->model('Journal_model');
        $this->load->model('Daybook_model');
        $this->load->library('session');

        // Check if user is logged in
        if(!$this->session->userdata('user_id')) {
            redirect('login');
        }
    }

    // Journal Entries
    public function journal() {
        $data['page_title'] = 'Journal Entries';
        $data['breadcrumbs'] = array(
            array('title' => 'Dashboard', 'url' => base_url('dashboard')),
            array('title' => 'Accounting'),
            array('title' => 'Journal Entries')
        );

        $data['journals'] = $this->Journal_model->get_all();
        $data['main_content'] = 'accounting/journal/index';
        $this->load->view('templates/modern_layout', $data);
    }

    // Add Journal Entry
    public function add_journal() {
        $data['page_title'] = 'Add Journal Entry';
        $data['breadcrumbs'] = array(
            array('title' => 'Dashboard', 'url' => base_url('dashboard')),
            array('title' => 'Accounting'),
            array('title' => 'Journal Entries', 'url' => base_url('accounting/journal')),
            array('title' => 'Add')
        );

        $data['accounts'] = $this->Account_model->get_all();

        if ($this->input->post()) {
            // Handle journal entry submission
            $post_data = $this->input->post();

            $journal_data = array(
                'date' => $post_data['date'],
                'reference' => $post_data['reference'],
                'description' => $post_data['description'],
                'created_by' => $this->session->userdata('user_id')
            );

            if ($this->Journal_model->insert($journal_data)) {
                $this->session->set_flashdata('success', 'Journal entry added successfully');
                redirect('accounting/journal');
            } else {
                $this->session->set_flashdata('error', 'Failed to add journal entry');
            }
        }

        $data['main_content'] = 'accounting/journal/form';
        $this->load->view('templates/modern_layout', $data);
    }

    // General Ledger
    public function ledger() {
        $data['page_title'] = 'General Ledger';
        $data['breadcrumbs'] = array(
            array('title' => 'Dashboard', 'url' => base_url('dashboard')),
            array('title' => 'Accounting'),
            array('title' => 'General Ledger')
        );

        $data['accounts'] = $this->Account_model->get_all();
        $data['main_content'] = 'accounting/ledger/index';
        $this->load->view('templates/modern_layout', $data);
    }

    // View Account Ledger
    public function view_ledger($account_id) {
        $data['page_title'] = 'Account Ledger';
        $data['breadcrumbs'] = array(
            array('title' => 'Dashboard', 'url' => base_url('dashboard')),
            array('title' => 'Accounting'),
            array('title' => 'General Ledger', 'url' => base_url('accounting/ledger')),
            array('title' => 'View Ledger')
        );

        $data['account'] = $this->Account_model->get_by_id($account_id);
        $data['ledger'] = $this->Account_model->get_ledger($account_id);

        $data['main_content'] = 'accounting/ledger/view';
        $this->load->view('templates/modern_layout', $data);
    }

    // Daybook
    public function daybook() {
        $data['page_title'] = 'Day Book';
        $data['breadcrumbs'] = array(
            array('title' => 'Dashboard', 'url' => base_url('dashboard')),
            array('title' => 'Accounting'),
            array('title' => 'Day Book')
        );

        $from_date = $this->input->get('from_date') ?? date('Y-m-01');
        $to_date = $this->input->get('to_date') ?? date('Y-m-d');

        $data['from_date'] = $from_date;
        $data['to_date'] = $to_date;
        $data['entries'] = $this->Daybook_model->get_by_date_range($from_date, $to_date);

        $data['main_content'] = 'accounting/daybook/index';
        $this->load->view('templates/modern_layout', $data);
    }

    // Bank Reconciliation
    public function bank_reconciliation() {
        $data['page_title'] = 'Bank Reconciliation';
        $data['breadcrumbs'] = array(
            array('title' => 'Dashboard', 'url' => base_url('dashboard')),
            array('title' => 'Accounting'),
            array('title' => 'Bank Reconciliation')
        );

        $data['main_content'] = 'accounting/bank_reconciliation/index';
        $this->load->view('templates/modern_layout', $data);
    }

    // Credit Note
    public function credit_note() {
        $data['page_title'] = 'Credit Notes';
        $data['breadcrumbs'] = array(
            array('title' => 'Dashboard', 'url' => base_url('dashboard')),
            array('title' => 'Accounting'),
            array('title' => 'Credit Notes')
        );

        $data['main_content'] = 'accounting/credit_note/index';
        $this->load->view('templates/modern_layout', $data);
    }

    // Add Credit Note
    public function add_credit_note() {
        $data['page_title'] = 'Add Credit Note';
        $data['breadcrumbs'] = array(
            array('title' => 'Dashboard', 'url' => base_url('dashboard')),
            array('title' => 'Accounting'),
            array('title' => 'Credit Notes', 'url' => base_url('accounting/credit-note')),
            array('title' => 'Add')
        );

        if ($this->input->post()) {
            // Handle credit note submission
            $this->session->set_flashdata('success', 'Credit note added successfully');
            redirect('accounting/credit-note');
        }

        $data['main_content'] = 'accounting/credit_note/form';
        $this->load->view('templates/modern_layout', $data);
    }

    // Debit Note
    public function debit_note() {
        $data['page_title'] = 'Debit Notes';
        $data['breadcrumbs'] = array(
            array('title' => 'Dashboard', 'url' => base_url('dashboard')),
            array('title' => 'Accounting'),
            array('title' => 'Debit Notes')
        );

        $data['main_content'] = 'accounting/debit_note/index';
        $this->load->view('templates/modern_layout', $data);
    }

    // Add Debit Note
    public function add_debit_note() {
        $data['page_title'] = 'Add Debit Note';
        $data['breadcrumbs'] = array(
            array('title' => 'Dashboard', 'url' => base_url('dashboard')),
            array('title' => 'Accounting'),
            array('title' => 'Debit Notes', 'url' => base_url('accounting/debit-note')),
            array('title' => 'Add')
        );

        if ($this->input->post()) {
            // Handle debit note submission
            $this->session->set_flashdata('success', 'Debit note added successfully');
            redirect('accounting/debit-note');
        }

        $data['main_content'] = 'accounting/debit_note/form';
        $this->load->view('templates/modern_layout', $data);
    }

    // Chart of Accounts (redirect to Accounts controller)
    public function chart() {
        redirect('accounts');
    }
}
