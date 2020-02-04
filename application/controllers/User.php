<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

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
					'is_hr'					=> $user->is_hr,
					'is_gm'         => $user->is_gm,
					'is_cfo'        => $user->is_cfo,
					'is_rfa'        => $user->is_rfa,
					'is_fa'         => $user->is_fa,
					'is_noted'      => $user->is_noted,
					'is_oichead'    => $user->is_oichead,
					'is_verify'     => $user->is_verify,
					'is_rfv'        => $user->is_rfv,
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

	public function logout()
	{
    $this->session->unset_userdata('id');
    $this->session->unset_userdata('username');
    $this->session->unset_userdata('password');
   	$this->session->sess_destroy();

    redirect('user/index');
 	}	
}