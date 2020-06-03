<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Manila');
		
	}

	public function index()
	{
		$this->form_validation->set_rules('username','Username','trim|required');
		$this->form_validation->set_rules('password','Username','trim|required');

		if($this->form_validation->run() == FALSE)
		{
			$this->load->view('login');
		}
		else
		{
			$username = $this->input->post('username');
			$password = $this->input->post('password');

			$user = $this->user_model->admin_userpass($username,$password);

			if($user)
			{
				$user_data = array(
					'id' => $user->id,
					'emp_no_id'     => $user->emp_no_id,
					'fullname'      => $user->fullname,
					'username' 	    => $user->username,
					'password'      => $user->password,
					'user_level'    => $user->user_level,
					'department_id' => $user->department_id,
					'branch_id'     => $user->branch_id,
					'company'       => $user->company,
					'supervisor_id' => $user->supervisor_id,
					'head_id'       => $user->head_id,
					'is_hr'		    => $user->is_hr,
					'is_gm'         => $user->is_gm,
					'is_cfo'        => $user->is_cfo,
					'is_rfa'        => $user->is_rfa,
					'is_fa'         => $user->is_fa,
					'is_noted'      => $user->is_noted,
					'is_oichead'    => $user->is_oichead,
					'is_verify'     => $user->is_verify,
					'is_rfv'        => $user->is_rfv,
					'is_branch'     => $user->is_branch,
					'logged_in'     => TRUE
				);

				$this->session->set_userdata($user_data);

			
				redirect('dashboard/dashboard');
			}
			else
			{
				$this->session->set_flashdata('login_failed', 'Incorrect Username & Password.');
				redirect('user/index');
			}
		}
	}

	public function change_password()
	{
		$this->form_validation->set_rules('old_password', 'Old Password', 'trim|required');
		$this->form_validation->set_rules('new_password', 'New Password', 'trim|required|min_length[8]|max_length[12]');

		if($this->form_validation->run() == FALSE)
		{
			$data['main_content'] = 'change_password';
			$this->load->view('layouts/main', $data);
		}
		else
		{
			$old_password = $this->input->post('old_password');
			$password = $this->session->userdata('password'); 
			if($old_password == $password)
			{
				$this->user_model->change_password();
				$this->session->set_flashdata('update_msg', 'Change Password Successfully Updated!');
			}
			else
			{
				$this->session->set_flashdata('error_msg', 'Invalid Old Password!');
			}
			redirect('user/change_password');
		}
	}

	public function logout()
	{
    $this->session->unset_userdata('id');
    $this->session->unset_userdata('username');
    $this->session->unset_userdata('password');
   	$this->session->sess_destroy();

    redirect('user/index');
 	}	
}