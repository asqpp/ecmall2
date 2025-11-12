<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Insurance_policy_model extends CI_Model {

    private $table = 'insurance_policies';
    private $primary_key = 'policy_id';

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // Get all policies
    public function get_all($status = 'active') {
        $this->db->select('ip.*, ci.customer_name, b.broker_name, a.agent_name, pi.product_name');
        $this->db->from($this->table . ' ip');
        $this->db->join('customer_information ci', 'ci.customer_id = ip.customer_id', 'left');
        $this->db->join('broker b', 'b.broker_id = ip.broker_id', 'left');
        $this->db->join('agent a', 'a.agent_id = ip.agent_id', 'left');
        $this->db->join('product_information pi', 'pi.product_id = ip.product_id', 'left');

        if ($status !== 'all') {
            $this->db->where('ip.status', $status);
        }

        $this->db->order_by('ip.issue_date', 'DESC');
        return $this->db->get()->result();
    }

    // Get policy by ID
    public function get_by_id($id) {
        $this->db->select('ip.*, ci.customer_name, ci.customer_mobile, ci.email as customer_email,
                          b.broker_name, b.mobile as broker_mobile,
                          a.agent_name, a.mobile as agent_mobile,
                          pi.product_name, pi.category_id');
        $this->db->from($this->table . ' ip');
        $this->db->join('customer_information ci', 'ci.customer_id = ip.customer_id', 'left');
        $this->db->join('broker b', 'b.broker_id = ip.broker_id', 'left');
        $this->db->join('agent a', 'a.agent_id = ip.agent_id', 'left');
        $this->db->join('product_information pi', 'pi.product_id = ip.product_id', 'left');
        $this->db->where('ip.' . $this->primary_key, $id);
        return $this->db->get()->row();
    }

    // Get policy by policy number
    public function get_by_policy_number($policy_number) {
        $this->db->where('policy_number', $policy_number);
        return $this->db->get($this->table)->row();
    }

    // Get paginated policies
    public function get_paginated($limit, $offset, $search = '', $status = 'active') {
        $this->db->select('ip.*, ci.customer_name, b.broker_name, a.agent_name, pi.product_name');
        $this->db->from($this->table . ' ip');
        $this->db->join('customer_information ci', 'ci.customer_id = ip.customer_id', 'left');
        $this->db->join('broker b', 'b.broker_id = ip.broker_id', 'left');
        $this->db->join('agent a', 'a.agent_id = ip.agent_id', 'left');
        $this->db->join('product_information pi', 'pi.product_id = ip.product_id', 'left');

        if ($search != '') {
            $this->db->group_start();
            $this->db->like('ip.policy_number', $search);
            $this->db->or_like('ci.customer_name', $search);
            $this->db->or_like('ip.insurance_company', $search);
            $this->db->or_like('pi.product_name', $search);
            $this->db->group_end();
        }

        if ($status !== 'all') {
            $this->db->where('ip.status', $status);
        }

        $this->db->order_by('ip.issue_date', 'DESC');
        $this->db->limit($limit, $offset);

        return $this->db->get()->result();
    }

    // Count policies
    public function count_all($search = '', $status = 'active') {
        $this->db->from($this->table . ' ip');
        $this->db->join('customer_information ci', 'ci.customer_id = ip.customer_id', 'left');
        $this->db->join('product_information pi', 'pi.product_id = ip.product_id', 'left');

        if ($search != '') {
            $this->db->group_start();
            $this->db->like('ip.policy_number', $search);
            $this->db->or_like('ci.customer_name', $search);
            $this->db->or_like('ip.insurance_company', $search);
            $this->db->or_like('pi.product_name', $search);
            $this->db->group_end();
        }

        if ($status !== 'all') {
            $this->db->where('ip.status', $status);
        }

        return $this->db->count_all_results();
    }

    // Insert policy
    public function insert($data) {
        // Generate policy number if not provided
        if (empty($data['policy_number'])) {
            $data['policy_number'] = $this->generate_policy_number();
        }

        return $this->db->insert($this->table, $data);
    }

    // Update policy
    public function update($id, $data) {
        $this->db->where($this->primary_key, $id);
        return $this->db->update($this->table, $data);
    }

    // Delete policy
    public function delete($id) {
        $this->db->where($this->primary_key, $id);
        return $this->db->delete($this->table);
    }

    // Generate policy number
    public function generate_policy_number() {
        $this->db->select('policy_number');
        $this->db->order_by('policy_id', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get($this->table);

        if ($query->num_rows() > 0) {
            $last_number = $query->row()->policy_number;
            $number = (int)substr($last_number, 4);
            $new_number = $number + 1;
            return 'POL-' . str_pad($new_number, 5, '0', STR_PAD_LEFT);
        } else {
            return 'POL-00001';
        }
    }

    // Get expiring policies (next X days)
    public function get_expiring($days = 30) {
        $this->db->select('ip.*, ci.customer_name, ci.customer_mobile');
        $this->db->from($this->table . ' ip');
        $this->db->join('customer_information ci', 'ci.customer_id = ip.customer_id', 'left');
        $this->db->where('ip.end_date >=', date('Y-m-d'));
        $this->db->where('ip.end_date <=', date('Y-m-d', strtotime('+' . $days . ' days')));
        $this->db->where('ip.status', 'active');
        $this->db->order_by('ip.end_date', 'ASC');
        return $this->db->get()->result();
    }

    // Get policies by customer
    public function get_by_customer($customer_id) {
        $this->db->select('ip.*, pi.product_name');
        $this->db->from($this->table . ' ip');
        $this->db->join('product_information pi', 'pi.product_id = ip.product_id', 'left');
        $this->db->where('ip.customer_id', $customer_id);
        $this->db->order_by('ip.issue_date', 'DESC');
        return $this->db->get()->result();
    }

    // Get policy statistics
    public function get_statistics() {
        $stats = array();

        // Total active policies
        $this->db->where('status', 'active');
        $stats['active_policies'] = $this->db->count_all_results($this->table);

        // Total premium
        $this->db->select_sum('total_premium');
        $this->db->where('status', 'active');
        $query = $this->db->get($this->table);
        $stats['total_premium'] = $query->row()->total_premium ?? 0;

        // Expiring soon (30 days)
        $this->db->where('end_date >=', date('Y-m-d'));
        $this->db->where('end_date <=', date('Y-m-d', strtotime('+30 days')));
        $this->db->where('status', 'active');
        $stats['expiring_soon'] = $this->db->count_all_results($this->table);

        // Expired policies
        $this->db->where('end_date <', date('Y-m-d'));
        $this->db->where('status', 'active');
        $stats['expired'] = $this->db->count_all_results($this->table);

        return $stats;
    }

    // Check if policy number exists
    public function policy_number_exists($policy_number, $exclude_id = null) {
        $this->db->where('policy_number', $policy_number);
        if ($exclude_id) {
            $this->db->where($this->primary_key . ' !=', $exclude_id);
        }
        return $this->db->count_all_results($this->table) > 0;
    }
}
