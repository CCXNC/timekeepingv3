<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Attendance extends CI_Controller {

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

	public function index_attendance()
	{
		if($this->input->server('REQUEST_METHOD') == 'POST')
		{ 
			$data['start_date'] = $this->input->post('start_date');
			$data['end_date'] = $this->input->post('end_date');
			$data['branch'] = $this->input->post('branch');
		}
		else 
		{
			$data['start_date'] = date('Y-m-d');
			$data['end_date'] = date('Y-m-d');
			$data['branch'] = $this->input->post('branch');
		}
		$data['branches'] = $this->master_model->get_branches();
		$data['holidays'] = $this->payroll_model->get_holiday($data['start_date'], $data['end_date']);
		$data['obs'] = $this->payroll_model->get_ob($data['start_date'], $data['end_date']);
		$data['cut_off'] = $this->payroll_model->get_cut_off_date();
		$data['employee'] = $this->payroll_model->get_allAttendance($data['start_date'], $data['end_date'], $data['branch']);
		$data['schedules'] = $this->payroll_model->employee_sched();
		$data['hfpm_sl'] = $this->payroll_model->get_hfpm($data['start_date'], $data['end_date']);
		$data['hfam_sl'] = $this->payroll_model->get_hfam($data['start_date'], $data['end_date']);
		$data['slvls'] = $this->payroll_model->get_slvl_all1($data['start_date'], $data['end_date']);
		$data['holidays'] = $this->master_model->gget_holidays($data['start_date'], $data['end_date']);
		$data['rots'] = $this->payroll_model->get_regular_ot1($data['start_date'], $data['end_date']); 
		$data['lots'] = $this->payroll_model->get_legal_ot1($data['start_date'], $data['end_date']); 
		$data['shots'] = $this->payroll_model->get_special_ot1($data['start_date'], $data['end_date']); 
		$data['rdots'] = $this->payroll_model->get_restday_ot1($data['start_date'], $data['end_date']); 
		$data['vls'] = $this->payroll_model->get_total_vl1($data['start_date'], $data['end_date']);
		$data['sls'] = $this->payroll_model->get_total_sl1($data['start_date'], $data['end_date']);
		$data['abs'] = $this->payroll_model->get_total_ab1($data['start_date'], $data['end_date']);
		$data['remarks'] = $this->payroll_model->get_remarks($data['start_date'], $data['end_date']);
		$data['employees'] = $this->master_model->get_employee_all();
		$data['main_content'] = 'payroll/attendance/index';

		$this->load->view('layouts/main', $data);
	}

	public function process_time()
	{
		$this->payroll_model->process_time_keeping();
		redirect('attendance/index_attendance');
	}

	public function get_all_dates()
	{
		$this->db->select('start_date,end_date,total_days');
		$this->db->order_by('id','DESC');
		$query = $this->db->get('tbl_cut_off_date');
		$total_days = $query->row()->total_days;
		$start_date = $query->row()->start_date;
		$end_date = $query->row()->end_date;
		$cur_date = $start_date;

		$holiday_date = $this->input->post('dates');
		$a=0;
		$b=0;
		for($k = 1; $k <= $total_days; $k++)
		{
			$week_date = date('w', strtotime($cur_date));
			//if($week_date != 6 && $week_date != 0 && $holiday_date != $cur_date)
			//{
				//if($week_date != 6 && $week_date != 0 && $holiday_date != $cur_date)
				//{
					print_r($week_date.'---------');
					print_r($cur_date);
					print_r($holiday_date);
					echo '<br>';
				//}
			//}	
			$b++;
			$conv_date = strtotime($start_date);
			$cur_date = date('Y-m-d', strtotime('+' . $k .' days', $conv_date));	
		}	
		
	}
}	