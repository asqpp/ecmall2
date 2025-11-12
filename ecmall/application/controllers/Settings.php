<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Settings extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Settings_model');
        $this->load->library('session');

        // Check if user is logged in
        if(!$this->session->userdata('user_id')) {
            redirect('login');
        }
    }

    // Settings index
    public function index() {
        $data['page_title'] = 'System Settings';
        $data['breadcrumbs'] = array(
            array('title' => 'Dashboard', 'url' => base_url('dashboard')),
            array('title' => 'Settings')
        );

        // Get settings by groups
        $data['company_settings'] = $this->Settings_model->get_by_group('company');
        $data['general_settings'] = $this->Settings_model->get_by_group('general');
        $data['accounting_settings'] = $this->Settings_model->get_by_group('accounting');
        $data['invoicing_settings'] = $this->Settings_model->get_by_group('invoicing');
        $data['system_settings'] = $this->Settings_model->get_by_group('system');

        // Get all settings as array for the form
        $data['all_settings'] = $this->Settings_model->get_all_as_array();

        if ($this->input->post()) {
            $post_data = $this->input->post();

            // Update each setting
            foreach ($post_data as $key => $value) {
                if ($key != 'submit') {
                    $this->Settings_model->set($key, $value);
                }
            }

            $this->session->set_flashdata('success', 'Settings updated successfully');
            redirect('settings');
        }

        $data['main_content'] = 'settings/index';
        $this->load->view('templates/modern_layout', $data);
    }

    // Company settings
    public function company() {
        $data['page_title'] = 'Company Settings';
        $data['breadcrumbs'] = array(
            array('title' => 'Dashboard', 'url' => base_url('dashboard')),
            array('title' => 'Settings', 'url' => base_url('settings')),
            array('title' => 'Company')
        );

        $data['settings'] = $this->Settings_model->get_by_group('company');

        $data['main_content'] = 'settings/company';
        $this->load->view('templates/modern_layout', $data);
    }

    // User preferences
    public function preferences() {
        $data['page_title'] = 'User Preferences';
        $data['breadcrumbs'] = array(
            array('title' => 'Dashboard', 'url' => base_url('dashboard')),
            array('title' => 'Settings', 'url' => base_url('settings')),
            array('title' => 'Preferences')
        );

        $data['main_content'] = 'settings/preferences';
        $this->load->view('templates/modern_layout', $data);
    }

    // Backup settings
    public function backup() {
        $data['page_title'] = 'Backup & Restore';
        $data['breadcrumbs'] = array(
            array('title' => 'Dashboard', 'url' => base_url('dashboard')),
            array('title' => 'Settings', 'url' => base_url('settings')),
            array('title' => 'Backup')
        );

        $data['main_content'] = 'settings/backup';
        $this->load->view('templates/modern_layout', $data);
    }
}
