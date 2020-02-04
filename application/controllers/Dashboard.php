<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		date_default_timezone_set('Asia/Manila');
		
		if($this->session->userdata('logged_in') == FALSE)
		{
			$this->session->set_flashdata('no_access', 'Sorry, you are not logged in');
			
			redirect('user/index');
		}
		
	} 

	public function dashboard()
	{
		if($this->input->server('REQUEST_METHOD') == 'POST')
		{
			$data['start_date'] = $this->input->post('start_date');
			$data['end_date'] = $this->input->post('end_date');
		}
		else 
		{
			$data['start_date'] = date('Y-m-d');
			$data['end_date'] = date('Y-m-d');
		}
		$data['main_content'] = 'user_dashboard';
		$data['slvl'] = $this->users_model->get_slvl_emp($data['start_date'],$data['end_date']);
		$data['ob'] = $this->users_model->get_ob_emp($data['start_date'],$data['end_date']);
		$data['ot'] = $this->users_model->get_ot_emp($data['start_date'],$data['end_date']);
		$data['undertime'] = $this->users_model->get_undertime_emp($data['start_date'],$data['end_date']);
		$data['employee'] = $this->payroll_model->get_user();
		$data['leave_credit'] = $this->users_model->get_emp_leave_credits();
		$data['cut_off'] = $this->payroll_model->get_cut_off_date();
		$this->load->view('layouts/main', $data);
	}

}		