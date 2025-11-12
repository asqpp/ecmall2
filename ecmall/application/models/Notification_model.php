<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notification_model extends CI_Model {

    private $table = 'notifications';
    private $primary_key = 'notification_id';

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // Get all notifications for a user
    public function get_by_user($user_id, $limit = null) {
        $this->db->where('user_id', $user_id);
        $this->db->or_where('user_id IS NULL'); // Global notifications
        $this->db->order_by('created_at', 'DESC');

        if ($limit) {
            $this->db->limit($limit);
        }

        return $this->db->get($this->table)->result();
    }

    // Get unread notifications count
    public function count_unread($user_id) {
        $this->db->where('is_read', 0);
        $this->db->group_start();
        $this->db->where('user_id', $user_id);
        $this->db->or_where('user_id IS NULL');
        $this->db->group_end();
        return $this->db->count_all_results($this->table);
    }

    // Get unread notifications
    public function get_unread($user_id, $limit = null) {
        $this->db->where('is_read', 0);
        $this->db->group_start();
        $this->db->where('user_id', $user_id);
        $this->db->or_where('user_id IS NULL');
        $this->db->group_end();
        $this->db->order_by('created_at', 'DESC');

        if ($limit) {
            $this->db->limit($limit);
        }

        return $this->db->get($this->table)->result();
    }

    // Get notification by ID
    public function get_by_id($id) {
        $this->db->where($this->primary_key, $id);
        return $this->db->get($this->table)->row();
    }

    // Insert notification
    public function insert($data) {
        return $this->db->insert($this->table, $data);
    }

    // Mark as read
    public function mark_as_read($id) {
        $data = array('is_read' => 1);
        $this->db->where($this->primary_key, $id);
        return $this->db->update($this->table, $data);
    }

    // Mark all as read for user
    public function mark_all_read($user_id) {
        $data = array('is_read' => 1);
        $this->db->where('user_id', $user_id);
        $this->db->where('is_read', 0);
        return $this->db->update($this->table, $data);
    }

    // Delete notification
    public function delete($id) {
        $this->db->where($this->primary_key, $id);
        return $this->db->delete($this->table);
    }

    // Delete old read notifications (older than X days)
    public function delete_old($days = 30) {
        $date = date('Y-m-d H:i:s', strtotime('-' . $days . ' days'));
        $this->db->where('is_read', 1);
        $this->db->where('created_at <', $date);
        return $this->db->delete($this->table);
    }

    // Create notification helper
    public function create($user_id, $title, $message, $type = 'info', $icon = null, $link = null) {
        $data = array(
            'user_id' => $user_id,
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'icon' => $icon,
            'link' => $link,
            'is_read' => 0
        );
        return $this->insert($data);
    }

    // Create global notification (to all users)
    public function create_global($title, $message, $type = 'info', $icon = null, $link = null) {
        $data = array(
            'user_id' => null,
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'icon' => $icon,
            'link' => $link,
            'is_read' => 0
        );
        return $this->insert($data);
    }
}
