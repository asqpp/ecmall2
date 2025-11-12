<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hr extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Employee_model');
        $this->load->model('Attendance_model');
        $this->load->model('Payroll_model');
        $this->load->library('session');

        // Check if user is logged in
        if(!$this->session->userdata('user_id')) {
            redirect('login');
        }
    }

    // EMPLOYEES SECTION
    public function employees() {
        $data['page_title'] = 'Employee Management';
        $data['breadcrumbs'] = array(
            array('title' => 'Dashboard', 'url' => base_url('dashboard')),
            array('title' => 'HR & Payroll'),
            array('title' => 'Employees')
        );

        $data['employees'] = $this->Employee_model->get_all('all');
        $data['stats'] = $this->Employee_model->count_by_status();

        $data['main_content'] = 'hr/employees/index';
        $this->load->view('templates/modern_layout', $data);
    }

    // View single employee
    public function view_employee($id) {
        $data['page_title'] = 'Employee Details';
        $data['breadcrumbs'] = array(
            array('title' => 'Dashboard', 'url' => base_url('dashboard')),
            array('title' => 'HR & Payroll'),
            array('title' => 'Employees', 'url' => base_url('hr/employees')),
            array('title' => 'View')
        );

        $data['employee'] = $this->Employee_model->get_by_id($id);

        if (!$data['employee']) {
            $this->session->set_flashdata('error', 'Employee not found');
            redirect('hr/employees');
        }

        $data['main_content'] = 'hr/employees/view';
        $this->load->view('templates/modern_layout', $data);
    }

    // Add/Edit employee
    public function add_employee() {
        $data['page_title'] = 'Add New Employee';
        $data['breadcrumbs'] = array(
            array('title' => 'Dashboard', 'url' => base_url('dashboard')),
            array('title' => 'HR & Payroll'),
            array('title' => 'Employees', 'url' => base_url('hr/employees')),
            array('title' => 'Add New')
        );

        if ($this->input->post()) {
            $post_data = $this->input->post();

            $employee_data = array(
                'employee_code' => $post_data['employee_code'] ?? $this->Employee_model->generate_code(),
                'first_name' => $post_data['first_name'],
                'last_name' => $post_data['last_name'],
                'email' => $post_data['email'],
                'mobile' => $post_data['mobile'],
                'date_of_birth' => $post_data['date_of_birth'] ?? null,
                'gender' => $post_data['gender'] ?? null,
                'address' => $post_data['address'] ?? null,
                'city' => $post_data['city'] ?? null,
                'state' => $post_data['state'] ?? null,
                'country' => $post_data['country'] ?? 'UAE',
                'passport_no' => $post_data['passport_no'] ?? null,
                'visa_no' => $post_data['visa_no'] ?? null,
                'visa_expiry' => $post_data['visa_expiry'] ?? null,
                'emirates_id' => $post_data['emirates_id'] ?? null,
                'department' => $post_data['department'] ?? null,
                'designation' => $post_data['designation'] ?? null,
                'joining_date' => $post_data['joining_date'] ?? date('Y-m-d'),
                'employment_type' => $post_data['employment_type'] ?? 'permanent',
                'salary' => $post_data['salary'] ?? 0,
                'bank_name' => $post_data['bank_name'] ?? null,
                'bank_account' => $post_data['bank_account'] ?? null,
                'iban' => $post_data['iban'] ?? null,
                'status' => 'active'
            );

            if ($this->Employee_model->insert($employee_data)) {
                $this->session->set_flashdata('success', 'Employee added successfully');
                redirect('hr/employees');
            } else {
                $this->session->set_flashdata('error', 'Failed to add employee');
            }
        }

        $data['main_content'] = 'hr/employees/form';
        $this->load->view('templates/modern_layout', $data);
    }

    // ATTENDANCE SECTION
    public function attendance() {
        $data['page_title'] = 'Attendance Management';
        $data['breadcrumbs'] = array(
            array('title' => 'Dashboard', 'url' => base_url('dashboard')),
            array('title' => 'HR & Payroll'),
            array('title' => 'Attendance')
        );

        // Get today's date or selected date
        $date = $this->input->get('date') ?? date('Y-m-d');
        $data['selected_date'] = $date;

        // Get attendance for the date
        $data['attendance'] = $this->Attendance_model->get_by_date($date);
        $data['stats'] = $this->Attendance_model->get_today_summary();

        $data['main_content'] = 'hr/attendance/index';
        $this->load->view('templates/modern_layout', $data);
    }

    // Mark attendance
    public function mark_attendance() {
        if ($this->input->post()) {
            $employee_id = $this->input->post('employee_id');
            $date = $this->input->post('date');
            $status = $this->input->post('status');
            $check_in = $this->input->post('check_in');
            $check_out = $this->input->post('check_out');

            if ($this->Attendance_model->mark_attendance($employee_id, $date, $status, $check_in, $check_out)) {
                $this->session->set_flashdata('success', 'Attendance marked successfully');
            } else {
                $this->session->set_flashdata('error', 'Failed to mark attendance');
            }
        }

        redirect('hr/attendance');
    }

    // LEAVE MANAGEMENT
    public function leave() {
        $data['page_title'] = 'Leave Management';
        $data['breadcrumbs'] = array(
            array('title' => 'Dashboard', 'url' => base_url('dashboard')),
            array('title' => 'HR & Payroll'),
            array('title' => 'Leave Management')
        );

        $data['main_content'] = 'hr/leave/index';
        $this->load->view('templates/modern_layout', $data);
    }

    // PAYROLL SECTION
    public function payroll() {
        $data['page_title'] = 'Payroll Processing';
        $data['breadcrumbs'] = array(
            array('title' => 'Dashboard', 'url' => base_url('dashboard')),
            array('title' => 'HR & Payroll'),
            array('title' => 'Payroll')
        );

        // Get current month/year or selected
        $month = $this->input->get('month') ?? date('n');
        $year = $this->input->get('year') ?? date('Y');

        $data['selected_month'] = $month;
        $data['selected_year'] = $year;

        $data['payroll'] = $this->Payroll_model->get_by_month_year($month, $year);
        $data['summary'] = $this->Payroll_model->get_summary($month, $year);

        $data['main_content'] = 'hr/payroll/index';
        $this->load->view('templates/modern_layout', $data);
    }

    // Generate payroll for month
    public function generate_payroll() {
        $month = $this->input->post('month') ?? date('n');
        $year = $this->input->post('year') ?? date('Y');

        $count = $this->Payroll_model->generate_for_month($month, $year);

        $this->session->set_flashdata('success', "Payroll generated for $count employees");
        redirect('hr/payroll?month=' . $month . '&year=' . $year);
    }

    // SALARY STRUCTURES
    public function salary() {
        $data['page_title'] = 'Salary Structures';
        $data['breadcrumbs'] = array(
            array('title' => 'Dashboard', 'url' => base_url('dashboard')),
            array('title' => 'HR & Payroll'),
            array('title' => 'Salary Structures')
        );

        $data['main_content'] = 'hr/salary/index';
        $this->load->view('templates/modern_layout', $data);
    }

    // PERFORMANCE
    public function performance() {
        $data['page_title'] = 'Performance Management';
        $data['breadcrumbs'] = array(
            array('title' => 'Dashboard', 'url' => base_url('dashboard')),
            array('title' => 'HR & Payroll'),
            array('title' => 'Performance')
        );

        $data['main_content'] = 'hr/performance/index';
        $this->load->view('templates/modern_layout', $data);
    }
}
