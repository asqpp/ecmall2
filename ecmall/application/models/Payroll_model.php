<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payroll_model extends CI_Model {

    private $table = 'payroll';
    private $primary_key = 'payroll_id';

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // Get payroll by month and year
    public function get_by_month_year($month, $year) {
        $this->db->select('p.*, e.employee_code, e.first_name, e.last_name, e.department');
        $this->db->from($this->table . ' p');
        $this->db->join('employees e', 'e.employee_id = p.employee_id', 'left');
        $this->db->where('p.month', $month);
        $this->db->where('p.year', $year);
        $this->db->order_by('e.employee_code', 'ASC');
        return $this->db->get()->result();
    }

    // Get payroll by employee and month/year
    public function get_by_employee($employee_id, $month, $year) {
        $this->db->where('employee_id', $employee_id);
        $this->db->where('month', $month);
        $this->db->where('year', $year);
        return $this->db->get($this->table)->row();
    }

    // Get payroll by ID
    public function get_by_id($id) {
        $this->db->select('p.*, e.employee_code, e.first_name, e.last_name, e.department, e.bank_name, e.bank_account');
        $this->db->from($this->table . ' p');
        $this->db->join('employees e', 'e.employee_id = p.employee_id', 'left');
        $this->db->where('p.' . $this->primary_key, $id);
        return $this->db->get()->row();
    }

    // Insert payroll
    public function insert($data) {
        return $this->db->insert($this->table, $data);
    }

    // Update payroll
    public function update($id, $data) {
        $this->db->where($this->primary_key, $id);
        return $this->db->update($this->table, $data);
    }

    // Delete payroll
    public function delete($id) {
        $this->db->where($this->primary_key, $id);
        return $this->db->delete($this->table);
    }

    // Generate payroll for all employees
    public function generate_for_month($month, $year) {
        // Get all active employees
        $this->db->where('status', 'active');
        $employees = $this->db->get('employees')->result();

        $generated = 0;
        foreach ($employees as $employee) {
            // Check if payroll already exists
            $existing = $this->get_by_employee($employee->employee_id, $month, $year);

            if (!$existing) {
                $data = array(
                    'employee_id' => $employee->employee_id,
                    'month' => $month,
                    'year' => $year,
                    'basic_salary' => $employee->salary,
                    'allowances' => 0,
                    'overtime_pay' => 0,
                    'gross_salary' => $employee->salary,
                    'deductions' => 0,
                    'net_salary' => $employee->salary,
                    'status' => 'draft'
                );

                if ($this->insert($data)) {
                    $generated++;
                }
            }
        }

        return $generated;
    }

    // Get payroll summary
    public function get_summary($month, $year) {
        $stats = array();

        // Total employees
        $this->db->where('month', $month);
        $this->db->where('year', $year);
        $stats['total_employees'] = $this->db->count_all_results($this->table);

        // Total gross salary
        $this->db->select_sum('gross_salary');
        $this->db->where('month', $month);
        $this->db->where('year', $year);
        $query = $this->db->get($this->table);
        $stats['total_gross'] = $query->row()->gross_salary ?? 0;

        // Total deductions
        $this->db->select_sum('deductions');
        $this->db->where('month', $month);
        $this->db->where('year', $year);
        $query = $this->db->get($this->table);
        $stats['total_deductions'] = $query->row()->deductions ?? 0;

        // Total net salary
        $this->db->select_sum('net_salary');
        $this->db->where('month', $month);
        $this->db->where('year', $year);
        $query = $this->db->get($this->table);
        $stats['total_net'] = $query->row()->net_salary ?? 0;

        // Paid count
        $this->db->where('month', $month);
        $this->db->where('year', $year);
        $this->db->where('status', 'paid');
        $stats['paid_count'] = $this->db->count_all_results($this->table);

        return $stats;
    }
}
