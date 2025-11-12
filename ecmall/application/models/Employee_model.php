<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Employee_model extends CI_Model {

    private $table = 'employees';
    private $primary_key = 'employee_id';

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // Get all employees
    public function get_all($status = 'active') {
        if ($status !== 'all') {
            $this->db->where('status', $status);
        }
        $this->db->order_by('employee_code', 'ASC');
        return $this->db->get($this->table)->result();
    }

    // Get employee by ID
    public function get_by_id($id) {
        $this->db->where($this->primary_key, $id);
        return $this->db->get($this->table)->row();
    }

    // Get employee by code
    public function get_by_code($code) {
        $this->db->where('employee_code', $code);
        return $this->db->get($this->table)->row();
    }

    // Get paginated employees with search
    public function get_paginated($limit, $offset, $search = '', $status = 'active') {
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('employee_code', $search);
            $this->db->or_like('first_name', $search);
            $this->db->or_like('last_name', $search);
            $this->db->or_like('email', $search);
            $this->db->or_like('mobile', $search);
            $this->db->or_like('department', $search);
            $this->db->or_like('designation', $search);
            $this->db->group_end();
        }

        if ($status !== 'all') {
            $this->db->where('status', $status);
        }

        $this->db->order_by('employee_code', 'ASC');
        $this->db->limit($limit, $offset);

        return $this->db->get($this->table)->result();
    }

    // Count employees
    public function count_all($search = '', $status = 'active') {
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('employee_code', $search);
            $this->db->or_like('first_name', $search);
            $this->db->or_like('last_name', $search);
            $this->db->or_like('email', $search);
            $this->db->or_like('mobile', $search);
            $this->db->group_end();
        }

        if ($status !== 'all') {
            $this->db->where('status', $status);
        }

        return $this->db->count_all_results($this->table);
    }

    // Insert employee
    public function insert($data) {
        // Generate employee code if not provided
        if (empty($data['employee_code'])) {
            $data['employee_code'] = $this->generate_code();
        }

        return $this->db->insert($this->table, $data);
    }

    // Update employee
    public function update($id, $data) {
        $this->db->where($this->primary_key, $id);
        return $this->db->update($this->table, $data);
    }

    // Delete employee
    public function delete($id) {
        $this->db->where($this->primary_key, $id);
        return $this->db->delete($this->table);
    }

    // Generate employee code
    public function generate_code() {
        $this->db->select('employee_code');
        $this->db->order_by('employee_id', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get($this->table);

        if ($query->num_rows() > 0) {
            $last_code = $query->row()->employee_code;
            $number = (int)substr($last_code, 3);
            $new_number = $number + 1;
            return 'EMP' . str_pad($new_number, 3, '0', STR_PAD_LEFT);
        } else {
            return 'EMP001';
        }
    }

    // Get employees by department
    public function get_by_department($department) {
        $this->db->where('department', $department);
        $this->db->where('status', 'active');
        return $this->db->get($this->table)->result();
    }

    // Get employees count by status
    public function count_by_status() {
        $this->db->select('status, COUNT(*) as count');
        $this->db->group_by('status');
        $result = $this->db->get($this->table)->result();

        $data = array();
        foreach ($result as $row) {
            $data[$row->status] = $row->count;
        }
        return $data;
    }

    // Check if employee code exists
    public function code_exists($code, $exclude_id = null) {
        $this->db->where('employee_code', $code);
        if ($exclude_id) {
            $this->db->where($this->primary_key . ' !=', $exclude_id);
        }
        return $this->db->count_all_results($this->table) > 0;
    }

    // Get birthday list (current month)
    public function get_birthdays() {
        $this->db->where('MONTH(date_of_birth)', date('m'));
        $this->db->where('status', 'active');
        $this->db->order_by('DAY(date_of_birth)', 'ASC');
        return $this->db->get($this->table)->result();
    }

    // Get employees with expiring visas (next 30 days)
    public function get_expiring_visas($days = 30) {
        $this->db->where('visa_expiry >=', date('Y-m-d'));
        $this->db->where('visa_expiry <=', date('Y-m-d', strtotime('+' . $days . ' days')));
        $this->db->where('status', 'active');
        $this->db->order_by('visa_expiry', 'ASC');
        return $this->db->get($this->table)->result();
    }
}
