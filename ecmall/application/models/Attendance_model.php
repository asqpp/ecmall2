<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Attendance_model extends CI_Model {

    private $table = 'attendance';
    private $primary_key = 'attendance_id';

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // Get attendance by date
    public function get_by_date($date) {
        $this->db->select('a.*, e.employee_code, e.first_name, e.last_name, e.department');
        $this->db->from($this->table . ' a');
        $this->db->join('employees e', 'e.employee_id = a.employee_id', 'left');
        $this->db->where('a.attendance_date', $date);
        $this->db->order_by('e.employee_code', 'ASC');
        return $this->db->get()->result();
    }

    // Get attendance by employee and date range
    public function get_by_employee_date_range($employee_id, $from_date, $to_date) {
        $this->db->where('employee_id', $employee_id);
        $this->db->where('attendance_date >=', $from_date);
        $this->db->where('attendance_date <=', $to_date);
        $this->db->order_by('attendance_date', 'ASC');
        return $this->db->get($this->table)->result();
    }

    // Insert attendance
    public function insert($data) {
        return $this->db->insert($this->table, $data);
    }

    // Update attendance
    public function update($id, $data) {
        $this->db->where($this->primary_key, $id);
        return $this->db->update($this->table, $data);
    }

    // Mark attendance
    public function mark_attendance($employee_id, $date, $status, $check_in = null, $check_out = null) {
        // Check if attendance already exists
        $this->db->where('employee_id', $employee_id);
        $this->db->where('attendance_date', $date);
        $query = $this->db->get($this->table);

        $data = array(
            'status' => $status,
            'check_in' => $check_in,
            'check_out' => $check_out
        );

        // Calculate working hours if both check_in and check_out are provided
        if ($check_in && $check_out) {
            $start = strtotime($check_in);
            $end = strtotime($check_out);
            $hours = ($end - $start) / 3600;
            $data['working_hours'] = $hours;

            // Calculate overtime (if more than 8 hours)
            if ($hours > 8) {
                $data['overtime_hours'] = $hours - 8;
            }
        }

        if ($query->num_rows() > 0) {
            // Update existing
            $this->db->where('employee_id', $employee_id);
            $this->db->where('attendance_date', $date);
            return $this->db->update($this->table, $data);
        } else {
            // Insert new
            $data['employee_id'] = $employee_id;
            $data['attendance_date'] = $date;
            return $this->db->insert($this->table, $data);
        }
    }

    // Get today's attendance summary
    public function get_today_summary() {
        $today = date('Y-m-d');
        $stats = array();

        // Total employees
        $this->db->where('status', 'active');
        $stats['total_employees'] = $this->db->count_all_results('employees');

        // Present
        $this->db->where('attendance_date', $today);
        $this->db->where('status', 'present');
        $stats['present'] = $this->db->count_all_results($this->table);

        // Absent
        $this->db->where('attendance_date', $today);
        $this->db->where('status', 'absent');
        $stats['absent'] = $this->db->count_all_results($this->table);

        // On Leave
        $this->db->where('attendance_date', $today);
        $this->db->where('status', 'leave');
        $stats['on_leave'] = $this->db->count_all_results($this->table);

        // Half Day
        $this->db->where('attendance_date', $today);
        $this->db->where('status', 'half_day');
        $stats['half_day'] = $this->db->count_all_results($this->table);

        return $stats;
    }
}
