<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Settings_model extends CI_Model {

    private $table = 'settings';
    private $primary_key = 'setting_id';

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // Get all settings
    public function get_all($group = null) {
        if ($group) {
            $this->db->where('setting_group', $group);
        }
        $this->db->order_by('setting_group', 'ASC');
        return $this->db->get($this->table)->result();
    }

    // Get setting by key
    public function get($key, $default = null) {
        $this->db->where('setting_key', $key);
        $query = $this->db->get($this->table);

        if ($query->num_rows() > 0) {
            $row = $query->row();
            return $this->cast_value($row->setting_value, $row->setting_type);
        }

        return $default;
    }

    // Get settings by group
    public function get_by_group($group) {
        $this->db->where('setting_group', $group);
        $query = $this->db->get($this->table);

        $settings = array();
        foreach ($query->result() as $row) {
            $settings[$row->setting_key] = $this->cast_value($row->setting_value, $row->setting_type);
        }

        return $settings;
    }

    // Get all settings as key-value array
    public function get_all_as_array() {
        $query = $this->db->get($this->table);

        $settings = array();
        foreach ($query->result() as $row) {
            $settings[$row->setting_key] = $this->cast_value($row->setting_value, $row->setting_type);
        }

        return $settings;
    }

    // Set setting value
    public function set($key, $value, $group = 'general', $type = 'text', $description = null) {
        // Check if setting exists
        $this->db->where('setting_key', $key);
        $query = $this->db->get($this->table);

        $data = array(
            'setting_value' => $value,
            'setting_group' => $group,
            'setting_type' => $type
        );

        if ($description) {
            $data['description'] = $description;
        }

        if ($query->num_rows() > 0) {
            // Update existing
            $this->db->where('setting_key', $key);
            return $this->db->update($this->table, $data);
        } else {
            // Insert new
            $data['setting_key'] = $key;
            return $this->db->insert($this->table, $data);
        }
    }

    // Update multiple settings
    public function update_batch($settings) {
        foreach ($settings as $key => $value) {
            $this->db->where('setting_key', $key);
            $this->db->update($this->table, array('setting_value' => $value));
        }
        return true;
    }

    // Delete setting
    public function delete($key) {
        $this->db->where('setting_key', $key);
        return $this->db->delete($this->table);
    }

    // Cast value based on type
    private function cast_value($value, $type) {
        switch ($type) {
            case 'number':
                return (float) $value;
            case 'boolean':
                return (bool) $value;
            case 'json':
                return json_decode($value, true);
            default:
                return $value;
        }
    }

    // Get groups list
    public function get_groups() {
        $this->db->select('setting_group');
        $this->db->distinct();
        $this->db->order_by('setting_group', 'ASC');
        $query = $this->db->get($this->table);

        $groups = array();
        foreach ($query->result() as $row) {
            $groups[] = $row->setting_group;
        }

        return $groups;
    }
}
