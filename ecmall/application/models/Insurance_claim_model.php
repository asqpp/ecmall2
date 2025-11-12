<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Insurance_claim_model extends CI_Model {

    private $table = 'insurance_claims';
    private $primary_key = 'claim_id';

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // Get all claims
    public function get_all($status = null) {
        $this->db->select('ic.*, ip.policy_number, ci.customer_name');
        $this->db->from($this->table . ' ic');
        $this->db->join('insurance_policies ip', 'ip.policy_id = ic.policy_id', 'left');
        $this->db->join('customer_information ci', 'ci.customer_id = ic.customer_id', 'left');

        if ($status) {
            $this->db->where('ic.status', $status);
        }

        $this->db->order_by('ic.claim_date', 'DESC');
        return $this->db->get()->result();
    }

    // Get claim by ID
    public function get_by_id($id) {
        $this->db->select('ic.*, ip.policy_number, ip.policy_type, ci.customer_name, ci.customer_mobile');
        $this->db->from($this->table . ' ic');
        $this->db->join('insurance_policies ip', 'ip.policy_id = ic.policy_id', 'left');
        $this->db->join('customer_information ci', 'ci.customer_id = ic.customer_id', 'left');
        $this->db->where('ic.' . $this->primary_key, $id);
        return $this->db->get()->row();
    }

    // Insert claim
    public function insert($data) {
        if (empty($data['claim_number'])) {
            $data['claim_number'] = $this->generate_claim_number();
        }
        return $this->db->insert($this->table, $data);
    }

    // Update claim
    public function update($id, $data) {
        $this->db->where($this->primary_key, $id);
        return $this->db->update($this->table, $data);
    }

    // Delete claim
    public function delete($id) {
        $this->db->where($this->primary_key, $id);
        return $this->db->delete($this->table);
    }

    // Generate claim number
    public function generate_claim_number() {
        $this->db->select('claim_number');
        $this->db->order_by('claim_id', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get($this->table);

        if ($query->num_rows() > 0) {
            $last_number = $query->row()->claim_number;
            $number = (int)substr($last_number, 4);
            $new_number = $number + 1;
            return 'CLM-' . str_pad($new_number, 5, '0', STR_PAD_LEFT);
        } else {
            return 'CLM-00001';
        }
    }

    // Get claims by policy
    public function get_by_policy($policy_id) {
        $this->db->where('policy_id', $policy_id);
        $this->db->order_by('claim_date', 'DESC');
        return $this->db->get($this->table)->result();
    }

    // Get claim statistics
    public function get_statistics() {
        $stats = array();

        // Total claims
        $stats['total_claims'] = $this->db->count_all($this->table);

        // Pending claims
        $this->db->where('status', 'submitted');
        $this->db->or_where('status', 'under_review');
        $stats['pending_claims'] = $this->db->count_all_results($this->table);

        // Total claim amount
        $this->db->select_sum('claim_amount');
        $query = $this->db->get($this->table);
        $stats['total_claim_amount'] = $query->row()->claim_amount ?? 0;

        // Settled amount
        $this->db->select_sum('approved_amount');
        $this->db->where('status', 'settled');
        $query = $this->db->get($this->table);
        $stats['settled_amount'] = $query->row()->approved_amount ?? 0;

        return $stats;
    }
}
