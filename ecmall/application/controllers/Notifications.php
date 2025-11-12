<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notifications extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Notification_model');
        $this->load->library('session');

        // Check if user is logged in
        if(!$this->session->userdata('user_id')) {
            redirect('login');
        }
    }

    // List all notifications
    public function index() {
        $user_id = $this->session->userdata('user_id');

        $data['page_title'] = 'Notifications';
        $data['breadcrumbs'] = array(
            array('title' => 'Dashboard', 'url' => base_url('dashboard')),
            array('title' => 'Notifications')
        );

        // Get all notifications for the user
        $data['notifications'] = $this->Notification_model->get_by_user($user_id);
        $data['unread_count'] = $this->Notification_model->count_unread($user_id);

        $data['main_content'] = 'notifications/index';
        $this->load->view('templates/modern_layout', $data);
    }

    // Mark notification as read
    public function mark_as_read($id) {
        $this->Notification_model->mark_as_read($id);

        // Get the notification to redirect to its link
        $notification = $this->Notification_model->get_by_id($id);
        if ($notification && $notification->link) {
            redirect($notification->link);
        } else {
            redirect('notifications');
        }
    }

    // Mark all notifications as read
    public function mark_all_read() {
        $user_id = $this->session->userdata('user_id');
        $this->Notification_model->mark_all_read($user_id);

        $this->session->set_flashdata('success', 'All notifications marked as read');
        redirect('notifications');
    }

    // Delete notification
    public function delete($id) {
        if ($this->Notification_model->delete($id)) {
            $this->session->set_flashdata('success', 'Notification deleted successfully');
        } else {
            $this->session->set_flashdata('error', 'Failed to delete notification');
        }
        redirect('notifications');
    }

    // AJAX: Get unread count
    public function get_unread_count() {
        $user_id = $this->session->userdata('user_id');
        $count = $this->Notification_model->count_unread($user_id);

        header('Content-Type: application/json');
        echo json_encode(array('count' => $count));
    }

    // AJAX: Get latest notifications
    public function get_latest() {
        $user_id = $this->session->userdata('user_id');
        $notifications = $this->Notification_model->get_unread($user_id, 5);

        header('Content-Type: application/json');
        echo json_encode($notifications);
    }
}
