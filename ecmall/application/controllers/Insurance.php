<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Insurance extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Insurance_policy_model');
        $this->load->model('Insurance_claim_model');
        $this->load->model('Customer_model');
        $this->load->model('Product_model');
        $this->load->model('Broker_model');
        $this->load->model('Agent_model');
        $this->load->library('session');

        // Check if user is logged in
        if(!$this->session->userdata('user_id')) {
            redirect('login');
        }
    }

    // POLICIES SECTION
    public function policies() {
        $data['page_title'] = 'Policy Management';
        $data['breadcrumbs'] = array(
            array('title' => 'Dashboard', 'url' => base_url('dashboard')),
            array('title' => 'Insurance'),
            array('title' => 'Policies')
        );

        $data['policies'] = $this->Insurance_policy_model->get_all('all');
        $data['stats'] = $this->Insurance_policy_model->get_statistics();

        $data['main_content'] = 'insurance/policies/index';
        $this->load->view('templates/modern_layout', $data);
    }

    // View single policy
    public function view_policy($id) {
        $data['page_title'] = 'Policy Details';
        $data['breadcrumbs'] = array(
            array('title' => 'Dashboard', 'url' => base_url('dashboard')),
            array('title' => 'Insurance'),
            array('title' => 'Policies', 'url' => base_url('insurance/policies')),
            array('title' => 'View')
        );

        $data['policy'] = $this->Insurance_policy_model->get_by_id($id);

        if (!$data['policy']) {
            $this->session->set_flashdata('error', 'Policy not found');
            redirect('insurance/policies');
        }

        $data['main_content'] = 'insurance/policies/view';
        $this->load->view('templates/modern_layout', $data);
    }

    // Add/Edit policy
    public function add_policy() {
        $data['page_title'] = 'Add New Policy';
        $data['breadcrumbs'] = array(
            array('title' => 'Dashboard', 'url' => base_url('dashboard')),
            array('title' => 'Insurance'),
            array('title' => 'Policies', 'url' => base_url('insurance/policies')),
            array('title' => 'Add New')
        );

        $data['customers'] = $this->Customer_model->get_all();
        $data['products'] = $this->Product_model->get_all();
        $data['brokers'] = $this->Broker_model->get_all();
        $data['agents'] = $this->Agent_model->get_all();

        if ($this->input->post()) {
            $post_data = $this->input->post();

            $policy_data = array(
                'policy_number' => $post_data['policy_number'],
                'customer_id' => $post_data['customer_id'],
                'broker_id' => $post_data['broker_id'] ?? null,
                'agent_id' => $post_data['agent_id'] ?? null,
                'product_id' => $post_data['product_id'],
                'policy_type' => $post_data['policy_type'],
                'sum_insured' => $post_data['sum_insured'],
                'premium_amount' => $post_data['premium_amount'],
                'vat_amount' => $post_data['vat_amount'] ?? 0,
                'total_premium' => $post_data['total_premium'],
                'issue_date' => $post_data['issue_date'],
                'start_date' => $post_data['start_date'],
                'end_date' => $post_data['end_date'],
                'payment_frequency' => $post_data['payment_frequency'],
                'insurance_company' => $post_data['insurance_company'],
                'status' => 'active',
                'remarks' => $post_data['remarks'] ?? null
            );

            if ($this->Insurance_policy_model->insert($policy_data)) {
                $this->session->set_flashdata('success', 'Policy created successfully');
                redirect('insurance/policies');
            } else {
                $this->session->set_flashdata('error', 'Failed to create policy');
            }
        }

        $data['main_content'] = 'insurance/policies/form';
        $this->load->view('templates/modern_layout', $data);
    }

    // CLAIMS SECTION
    public function claims() {
        $data['page_title'] = 'Claims Management';
        $data['breadcrumbs'] = array(
            array('title' => 'Dashboard', 'url' => base_url('dashboard')),
            array('title' => 'Insurance'),
            array('title' => 'Claims')
        );

        $data['claims'] = $this->Insurance_claim_model->get_all();
        $data['stats'] = $this->Insurance_claim_model->get_statistics();

        $data['main_content'] = 'insurance/claims/index';
        $this->load->view('templates/modern_layout', $data);
    }

    // View single claim
    public function view_claim($id) {
        $data['page_title'] = 'Claim Details';
        $data['breadcrumbs'] = array(
            array('title' => 'Dashboard', 'url' => base_url('dashboard')),
            array('title' => 'Insurance'),
            array('title' => 'Claims', 'url' => base_url('insurance/claims')),
            array('title' => 'View')
        );

        $data['claim'] = $this->Insurance_claim_model->get_by_id($id);

        if (!$data['claim']) {
            $this->session->set_flashdata('error', 'Claim not found');
            redirect('insurance/claims');
        }

        $data['main_content'] = 'insurance/claims/view';
        $this->load->view('templates/modern_layout', $data);
    }

    // Premium collection
    public function premium() {
        $data['page_title'] = 'Premium Collection';
        $data['breadcrumbs'] = array(
            array('title' => 'Dashboard', 'url' => base_url('dashboard')),
            array('title' => 'Insurance'),
            array('title' => 'Premium')
        );

        // Get policies that are due for premium
        $data['policies'] = $this->Insurance_policy_model->get_all('active');

        $data['main_content'] = 'insurance/premium/index';
        $this->load->view('templates/modern_layout', $data);
    }

    // Commission processing
    public function commission() {
        $data['page_title'] = 'Commission Processing';
        $data['breadcrumbs'] = array(
            array('title' => 'Dashboard', 'url' => base_url('dashboard')),
            array('title' => 'Insurance'),
            array('title' => 'Commission')
        );

        $data['main_content'] = 'insurance/commission/index';
        $this->load->view('templates/modern_layout', $data);
    }

    // Renewals
    public function renewals() {
        $data['page_title'] = 'Policy Renewals';
        $data['breadcrumbs'] = array(
            array('title' => 'Dashboard', 'url' => base_url('dashboard')),
            array('title' => 'Insurance'),
            array('title' => 'Renewals')
        );

        // Get policies expiring in next 60 days
        $data['expiring_policies'] = $this->Insurance_policy_model->get_expiring(60);

        $data['main_content'] = 'insurance/renewals/index';
        $this->load->view('templates/modern_layout', $data);
    }

    // Endorsements
    public function endorsements() {
        $data['page_title'] = 'Policy Endorsements';
        $data['breadcrumbs'] = array(
            array('title' => 'Dashboard', 'url' => base_url('dashboard')),
            array('title' => 'Insurance'),
            array('title' => 'Endorsements')
        );

        $data['main_content'] = 'insurance/endorsements/index';
        $this->load->view('templates/modern_layout', $data);
    }
}
