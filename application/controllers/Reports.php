<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reports extends CI_Controller {

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

	public function index_time_keeping()
	{
		$data['main_content'] = 'reports/timekeeping/index';
		$data['cut_off'] = $this->payroll_model->get_cut_off_date();
		$data['employees'] = $this->payroll_model->get_employees();
		$this->load->view('layouts/main', $data);
	} 

	public function employee_timekeeping()
	{
		$employee_no = $this->input->post('employee_no');
		$start_date = $this->input->post('start_date');
		$end_date = $this->input->post('end_date');
 
		  
		$data['employee_time'] = $this->payroll_model->employee_data($employee_no, $start_date, $end_date);
		$data['employee_name'] = $this->payroll_model->employee_name($employee_no);
		$data['employee_type'] = $this->payroll_model->employee_type($employee_no);
		$data['employee_leave'] = $this->payroll_model->employee_leave_credits($employee_no);
		$data['schedules'] = $this->payroll_model->employee_sched();
		$data['slvl'] = $this->payroll_model->get_slvl($employee_no,$start_date,$end_date);
		$data['vl_total'] = $this->payroll_model->get_total_vl_emp($employee_no,$start_date, $end_date);
		$data['ab_total'] = $this->payroll_model->get_total_ab_emp($employee_no,$start_date, $end_date);
		$data['sl_total'] = $this->payroll_model->get_total_sl_emp($employee_no,$start_date, $end_date);
		$data['sh_total'] = $this->payroll_model->get_total_sh_emp($employee_no,$start_date, $end_date);
		$data['ot'] = $this->payroll_model->get_ot($employee_no, $start_date, $end_date);
		$data['ot_total'] = $this->payroll_model->get_regular_ot_emp($employee_no,$start_date, $end_date);
		$data['lhot_total'] = $this->payroll_model->get_legal_ot_emp($employee_no,$start_date, $end_date);
		$data['shot_total'] = $this->payroll_model->get_special_ot_emp($employee_no,$start_date, $end_date);
		$data['rdot_total'] = $this->payroll_model->get_restday_ot_emp($employee_no,$start_date, $end_date);
		$data['ob'] = $this->payroll_model->get_ob1($employee_no,$start_date, $end_date);
		$data['undertime'] = $this->payroll_model->get_undertime_emp($employee_no,$start_date, $end_date);
		$data['ut_total'] = $this->payroll_model->get_total_ut($employee_no,$start_date, $end_date);
		$data['remarks'] = $this->payroll_model->get_remark($employee_no, $start_date, $end_date);
		$data['cwwut'] = $this->payroll_model->get_cwwut($employee_no,$start_date,$end_date);
		$data['cut_off'] = $this->payroll_model->get_cut_off_date();
		$data['holidays'] = $this->master_model->gget_holidays($start_date,$end_date);
		$data['main_content'] = 'reports/timekeeping/employee_attendance';

		//print_r($data['slvl_total']) ;
		$this->load->view('layouts/main', $data);
	}

	public function process_employee_time()
	{
		if($this->payroll_model->process_employee_timekeeping())
		{
			$this->session->set_flashdata('employee_process', 'PROCESS SUCCESSFULLY!');
			redirect('reports/index_time_keeping');
		}
		
	}

	public function in_out_index()
	{
		if($this->input->server('REQUEST_METHOD') == 'POST')
		{
			$data['start_date'] = $this->input->post('start_date');
			$data['end_date'] = $this->input->post('end_date');
			$data['status'] = $this->input->post('status');
		}
		else 
		{
			$data['start_date'] = date('Y-m-d');
			$data['end_date'] = date('Y-m-d');
			$data['status'] = ' ';
		}
		$data['cut_off'] = $this->payroll_model->get_cut_off_date();
		$data['in'] = $this->payroll_model->get_in($data['start_date'], $data['end_date'], $data['status']);
		$data['outs'] = $this->payroll_model->get_out($data['start_date'], $data['end_date'], $data['status']);
		//$data['holidays'] = $this->payroll_model->get_holiday(); 
		$data['main_content'] = 'reports/timekeeping/index_add_in_out';
		$this->load->view('layouts/main', $data);
	}

	public function in_process()
	{
		foreach($this->input->post('ins') as $in)
			{
				$data = explode('|', $in);

				$in_data = array(
					'times' => $data[2]. ' ' . '07:30:00',
					'status'		=> 'IN'
				);
				
				$this->db->where('id', $data[1]);
				$this->db->update('tbl_in_attendance', $in_data);
			}
			redirect('reports/in_out_index');
	}

	public function out_process()
	{
		foreach($this->input->post('outs') as $out)
			{
				$data = explode('|', $out);

				if($data[4] == 5)
				{
					$out_data = array(
						'times' => $data[2].' '.'16:30:00',
						'status'		=> 'OUT'
					);
				
					$this->db->where('id', $data[1]);
					$this->db->update('tbl_out_attendance', $out_data);
				}
				else
				{
					$out_data = array(
						'times' => $data[2].' '.'17:30:00',
						'status'		=> 'OUT'
					);
				
					$this->db->where('id', $data[1]);
					$this->db->update('tbl_out_attendance', $out_data);
				}
			}
			redirect('reports/in_out_index');
	}

	public function adj_employee_time()
	{
		$this->form_validation->set_rules('date', 'Date', 'required|trim');
		//$this->form_validation->set_rules('time', 'Time', 'required|trim');
		$this->form_validation->set_rules('in_out_type', 'Type', 'required|trim');

		if($this->form_validation->run() == FALSE)
		{
			$data['employees'] = $this->payroll_model->get_employees();
			$data['main_content'] = 'reports/timekeeping/adjustment';
			$this->load->view('layouts/main', $data);
		}
		else
		{
			if($this->payroll_model->adjustment())
			{
				$this->session->set_flashdata('update_msg', 'SET TIME SUCCESSFULLY UPDATED!');
				redirect('reports/adj_employee_time');
			}
		}
		
	}
 
	public function index_slvl()
	{
		if($this->input->server('REQUEST_METHOD') == 'POST')
		{
			$data['start_date'] = $this->input->post('start_date');
			$data['end_date'] = $this->input->post('end_date');
		  	$data['slvl_type'] = $this->input->post('slvl_type');
		}
		else 
		{
			$data['start_date'] = date('Y-m-d');
			$data['end_date'] = date('Y-m-d');
			$data['slvl_type'] = $this->input->post('slvl_type');
		} 
		$data['cut_off'] = $this->payroll_model->get_cut_off_date();
		$data['slvl'] = $this->payroll_model->get_slvl_all($data['start_date'],$data['end_date'],$data['slvl_type']); 

		$data['main_content'] = 'reports/slvl/index'; 
		$this->load->view('layouts/main', $data);	 
	}

	public function add_slvl()
	{
		$this->form_validation->set_rules('name', 'Employee Name', 'required|trim');
		$this->form_validation->set_rules('start_date', 'EFFECTIVE DATE OF LEAVE', 'required|trim');
		$this->form_validation->set_rules('reason', 'REASON', 'required|trim');

		if($this->form_validation->run() == FALSE)
		{
			$data['main_content'] = 'reports/slvl/add';
			$data['employees'] = $this->payroll_model->get_employees();
			$this->load->view('layouts/main', $data);
		}
		else 
		{
			if($this->payroll_model->add_slvl_by_hr())
			{
				$this->session->set_flashdata('add_msg', 'SL/VL SUCCESSFULLY ADDED!');
				redirect('reports/index_slvl');
			}
		}
		
	}

	public function edit_slvl($id)
	{
		$this->form_validation->set_rules('name', 'Employee Name', 'required|trim');
		$this->form_validation->set_rules('start_date', 'EFFECTIVE DATE OF LEAVE', 'required|trim');
		$this->form_validation->set_rules('reason', 'REASON', 'required|trim');

		if($this->form_validation->run() == FALSE)
		{
			$data['main_content'] = 'reports/slvl/edit'; 
			$data['slvl'] = $this->payroll_model->get_slvls($id);
			$data['employees'] = $this->payroll_model->get_employees();
			$this->load->view('layouts/main', $data);
		}
		else
		{
			if($this->payroll_model->update_slvl($id))
			{
				$this->session->set_flashdata('update_msg', 'SL/VL SUCCESSFULLY UPDATED!');
				redirect('users/slvl_list');
			}
		}
		
	}

	public function delete_slvl($id,$employee_number,$type)
	{
		$this->payroll_model->delete_slvl($id,$employee_number,$type);
		$this->session->set_flashdata('delete_msg_slvl', 'SL/VL SUCCESSFULLY DELETED!');
		redirect('reports/index_slvl');
	}

	public function process_slvl()
	{
		foreach($this->input->post('employee') as $slvl)
		{
			$explode_data = explode('|', $slvl);
			$data_w = date('w', strtotime($explode_data[3]));

			$data = array(
				'process_by'  	=> $this->session->userdata('username'),
				'process_date'  => date('Y-m-d h:i:s'),
				'status'        => 'PROCESSED'
			); 

			$this->db->where('id', $explode_data[0]);
			$this->db->update('tbl_slvl', $data);

			$data = array( 
				'process_by' 			=> $this->session->userdata('username'),
				'process_date' 		=> date('Y-m-d h:i:s'),
				'status'        	=> 'PROCESSED'
			);

			$this->db->where('for_id', $explode_data[0]);
			$this->db->where('employee_number', $explode_data[1]);
			$this->db->where('type', $explode_data[4]);
			$this->db->update('tbl_remarks', $data);
			

			$this->db->where('employee_number', $explode_data[1]);
			$this->db->where('date', $explode_data[3]);
			$in_att = $this->db->get('tbl_cwwut');

			if($in_att->num_rows() == 0)
			{
				if($explode_data[5] == 'WD' && $explode_data[4] == 'AB' && ($data_w <= 4))
				{
					$data = array(
						'for_id'               => $explode_data[0], 
						'employee_number'      => $explode_data[1],
						'name'                 => $explode_data[2],
						'date'								 => $explode_data[3],
						'type'								 => $explode_data[4],
						'undertime_hr'         => 60,
						'created_date'         => date('Y-m-d h:i:s'),
						'created_by'           => $this->session->userdata('username'),
						'status'               => 'PROCESSED'
					);
					$this->db->insert('tbl_cwwut', $data);
				
				}
				elseif($explode_data[5] == 'HFAM' && $explode_data[4] == 'AB' && ($data_w <= 4))
				{
					$data = array(
						'for_id'               => $explode_data[0], 
						'employee_number'      => $explode_data[1],
						'name'                 => $explode_data[2],
						'date'								 => $explode_data[3],
						'type'								 => $explode_data[4],
						'undertime_hr'         => 30,
						'created_date'         => date('Y-m-d h:i:s'),
						'created_by'           => $this->session->userdata('username'),
						'status'               => 'PROCESSED'
					);
					$this->db->insert('tbl_cwwut', $data);
				
				}
				elseif($explode_data[5] == 'HFPM' && $explode_data[4] == 'AB' && ($data_w <= 4))
				{
					$data = array(
						'for_id'               => $explode_data[0], 
						'employee_number'      => $explode_data[1],
						'name'                 => $explode_data[2],
						'date'								 => $explode_data[3],
						'type'								 => $explode_data[4],
						'undertime_hr'         => 30,
						'created_date'         => date('Y-m-d h:i:s'),
						'created_by'           => $this->session->userdata('username'),
						'status'               => 'PROCESSED'
					);
					$this->db->insert('tbl_cwwut', $data);
				}
			}	
		}
	
		redirect('reports/index_slvl');
	}

	public function index_ob()
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
    $data['cut_off'] = $this->payroll_model->get_cut_off_date();
		$data['obs'] = $this->payroll_model->get_ob($data['start_date'], $data['end_date']);         
	  $data['main_content'] = 'reports/ob/index';  
		$this->load->view('layouts/main', $data);	  
	}  

	public function process_ob()
	{
		$this->payroll_model->process_ob();
		redirect('reports/index_ob');
	}
 
	public function edit_ob($id)
	{
		$this->form_validation->set_rules('date', 'Date OF OB', 'required|trim');
		$this->form_validation->set_rules('site_from', 'SITE / DESIGNATION', 'required|trim');
		$this->form_validation->set_rules('site_to', 'SITE / DESIGNATION', 'required|trim');
		$this->form_validation->set_rules('purpose', 'Purpose', 'required|trim');
		$this->form_validation->set_rules('time_of_departure', 'TIME OF DEPARTURE', 'required|trim');
		$this->form_validation->set_rules('time_of_return', 'TIME OF RETURN', 'required|trim');

		if($this->form_validation->run() == FALSE)
		{
			$data['main_content'] = 'reports/ob/edit';
			$data['ob'] = $this->payroll_model->get_ob_by_id($id);
			$this->load->view('layouts/main', $data);
		}
		else
		{
			if($this->payroll_model->update_ob($id))
			{
				$this->session->set_flashdata('update_ob_msg', 'OB SUCCESSFULLY UPDATED!');
				redirect('users/ob_list');
			}
		}
	
	}

	public function add_ob()
	{
		$this->form_validation->set_rules('date', 'Date OF OB', 'required|trim');
		$this->form_validation->set_rules('site_from', 'SITE / DESIGNATION', 'required|trim');
		$this->form_validation->set_rules('site_to', 'SITE / DESIGNATION', 'required|trim');
		$this->form_validation->set_rules('purpose', 'Purpose', 'required|trim');
		$this->form_validation->set_rules('time_of_departure', 'TIME OF DEPARTURE', 'required|trim');
		$this->form_validation->set_rules('time_of_return', 'TIME OF RETURN', 'required|trim');


		if($this->form_validation->run() == FALSE)
		{
			$data['main_content'] = 'reports/ob/add';
			$data['employees'] = $this->payroll_model->get_employees();
			$this->load->view('layouts/main', $data);
		}
		
		else
		{
			if($this->payroll_model->add_ob_by_hr())
			{
				$this->session->set_flashdata('add_msg', 'OB SUCCESSFULLY ADDED!');
				redirect('reports/index_ob');
			}
		}
		
	}

	public function delete_ob($id,$employee_number,$type)
	{
		$this->payroll_model->delete_ob($id,$employee_number,$type);
		$this->session->set_flashdata('delete_msg', 'OB SUCCESSFULLY DELETED!');
		redirect('reports/index_ob');
	}


	public function index_ot()
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


		$data['cut_off'] = $this->payroll_model->get_cut_off_date();
		$data['ots'] = $this->payroll_model->get_ots($data['start_date'], $data['end_date']);
		$data['main_content'] = 'reports/ot/index';
		$this->load->view('layouts/main', $data);
	}

	public function add_ot()
	{
		$this->form_validation->set_rules('name', 'Employee Name', 'required|trim');
		$this->form_validation->set_rules('date', 'Date OF OB', 'required|trim');
		$this->form_validation->set_rules('time_in', 'Time In', 'required|trim');
		$this->form_validation->set_rules('time_out', 'Time Out', 'required|trim');
		$this->form_validation->set_rules('nature_of_work', 'Nature Of Work', 'required|trim');

		if($this->form_validation->run() == FALSE)
		{
			$data['main_content'] = 'reports/ot/add';
			$data['employees'] = $this->payroll_model->get_employees();
			$this->load->view('layouts/main', $data);
		}
		else 
		{
			if($this->payroll_model->add_ot_by_hr())
			{
				$this->session->set_flashdata('add_msg', 'OT SUCCESSFULLY ADDED!');
				redirect('reports/index_ot');
			}
		}

	}

	public function edit_ot($id)
	{
		$this->form_validation->set_rules('employee_number', 'Employee number', 'required|trim');
		$this->form_validation->set_rules('name', 'Employee Name', 'required|trim');
		$this->form_validation->set_rules('date', 'Date OF OB', 'required|trim');
		$this->form_validation->set_rules('time_in', 'Time In', 'required|trim');
		$this->form_validation->set_rules('time_out', 'Time Out', 'required|trim');
		$this->form_validation->set_rules('nature_of_work', 'Nature Of Work', 'required|trim');

		if($this->form_validation->run() == FALSE)
		{
			$data['ot'] = $this->payroll_model->get_ot_id($id);
			$data['main_content'] = 'reports/ot/edit';
			$this->load->view('layouts/main', $data);
		}
		else
		{
			if($this->payroll_model->update_ot($id))
			{
				$this->session->set_flashdata('update_msg', 'OT SUCCESSFULLY UPDATED!');
				redirect('users/ot_list');
			}
		}
	}

	public function delete_ot($id)
	{
		$this->payroll_model->delete_ot($id);
		$this->session->set_flashdata('delete_msg', 'OT SUCCESSFULLY DELETED!');

		redirect('reports/index_ot');
	}

	public function process_ot()
	{
		foreach($this->input->post('employee') as $ot)
		{
			$explode_data = explode('|', $ot);

			$data = array(
				'process_by' 	=> $this->session->userdata('username'),
				'process_date' => date('Y-m-d h:i:s'),
				'status'        => 'PROCESSED'
			);

			$this->db->where('id', $explode_data[0]);
			$this->db->update('tbl_ot', $data);
		}
		redirect('reports/index_ot');
	}

	public function index_total_compute()
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
		$data['main_content'] = 'reports/computetime/index';
		$data['cut_off'] = $this->payroll_model->get_cut_off_date(); 
		$data['employees'] = $this->payroll_model->get_summary_time();
		$data['holidays'] = $this->payroll_model->get_holiday($data['start_date'], $data['end_date']);
		$data['dailyhrs'] = $this->payroll_model->get_total_dailyhrs($data['start_date'], $data['end_date']);
		$data['total_dayss'] = $this->payroll_model->get_total_day($data['start_date'], $data['end_date']);
		$data['ots'] = $this->payroll_model->get_regular_ot($data['start_date'], $data['end_date']); 
		$data['legal_ots'] = $this->payroll_model->get_legal_ot($data['start_date'], $data['end_date']); 
		$data['special_ots'] = $this->payroll_model->get_special_ot($data['start_date'], $data['end_date']);
		$data['restday_ots'] = $this->payroll_model->get_restday_ot($data['start_date'], $data['end_date']); 
		$data['abs'] = $this->payroll_model->get_total_ab($data['start_date'], $data['end_date']);
		$data['sls'] = $this->payroll_model->get_total_sl($data['start_date'], $data['end_date']);
		$data['vls'] = $this->payroll_model->get_total_vl($data['start_date'], $data['end_date']);
		$data['tardiness'] = $this->payroll_model->get_total_tardiness($data['start_date'], $data['end_date']);
		$data['undertime'] = $this->payroll_model->get_total_undertime($data['start_date'], $data['end_date']);
		$data['nightdiff'] = $this->payroll_model->get_total_night_diff($data['start_date'], $data['end_date']);
		$this->load->view('layouts/main', $data);
	}

	public function delete_all_data()
	{
		$data['cut_off'] = $this->payroll_model->get_cut_off_date();
		$start_date = $data['cut_off']->start_date;
		$end_date = $data['cut_off']->end_date;
		$this->payroll_model->delete_all_data($start_date, $end_date);
	}

	public function excel1()
	{
		$data['cut_off'] = $this->payroll_model->get_cut_off_date();

		$start_date = $data['cut_off']->start_date; 
		$end_date = $data['cut_off']->end_date;

		$data['employees'] = $this->payroll_model->get_allAttendance1($start_date, $end_date);
		$data['employeesALAMINOS'] = $this->payroll_model->get_allAttendance2($start_date, $end_date);
		$data['employeesBaclaran'] = $this->payroll_model->get_allAttendance3($start_date, $end_date);
		$data['employeesBaguio'] = $this->payroll_model->get_allAttendance4($start_date, $end_date);
		$data['employeesBalagtas'] = $this->payroll_model->get_allAttendance5($start_date, $end_date);
		$data['employeesBambang'] = $this->payroll_model->get_allAttendance6($start_date, $end_date);
		$data['employeesBangued'] = $this->payroll_model->get_allAttendance7($start_date, $end_date);
		$data['employeesBatangas'] = $this->payroll_model->get_allAttendance8($start_date, $end_date);
		$data['employeesBontoc'] = $this->payroll_model->get_allAttendance9($start_date, $end_date);
		$data['employeesCandon'] = $this->payroll_model->get_allAttendance10($start_date, $end_date);
		$data['employeesDagupan'] = $this->payroll_model->get_allAttendance11($start_date, $end_date);
		$data['employeesDivisoria'] = $this->payroll_model->get_allAttendance12($start_date, $end_date);
		$data['employeesLaunion'] = $this->payroll_model->get_allAttendance13($start_date, $end_date);
		$data['employeesLegazpi'] = $this->payroll_model->get_allAttendance14($start_date, $end_date);
		$data['employeesNaga'] = $this->payroll_model->get_allAttendance15($start_date, $end_date);
		$data['employeesNovaliches'] = $this->payroll_model->get_allAttendance16($start_date, $end_date);
		$data['employeesRoxas'] = $this->payroll_model->get_allAttendance17($start_date, $end_date);
		$data['employeesSanjuan'] = $this->payroll_model->get_allAttendance18($start_date, $end_date);
		$data['employeesSanpablo'] = $this->payroll_model->get_allAttendance19($start_date, $end_date);
		$data['employeesSantiago'] = $this->payroll_model->get_allAttendance20($start_date, $end_date);
		$data['employeesSolano'] = $this->payroll_model->get_allAttendance21($start_date, $end_date);
		$data['employeesTabuk'] = $this->payroll_model->get_allAttendance22($start_date, $end_date);
		$data['employeesVigan'] = $this->payroll_model->get_allAttendance23($start_date, $end_date);
		$data['employeesZambales'] = $this->payroll_model->get_allAttendance24($start_date, $end_date);
		$data['rots'] = $this->payroll_model->get_regular_ot1($start_date, $end_date);
		$data['lots'] = $this->payroll_model->get_legal_ot1($start_date, $end_date);
		$data['shots'] = $this->payroll_model->get_special_ot1($start_date, $end_date);
		$data['rdots'] = $this->payroll_model->get_restday_ot1($start_date, $end_date);
		$data['vl'] = $this->payroll_model->get_total_vl1($start_date, $end_date);
		$data['sl'] = $this->payroll_model->get_total_sl1($start_date, $end_date);
		$data['ab'] = $this->payroll_model->get_total_ab1($start_date, $end_date);
		$data['remarks'] = $this->payroll_model->get_remarks($start_date, $end_date);
		$data['schedules'] = $this->payroll_model->employee_sched();


		require(APPPATH .'third_party/PHPExcel-1.8/Classes/PHPExcel.php');
		require(APPPATH.'third_party/PHPExcel-1.8/Classes/PHPExcel/Writer/Excel2007.php');

		$objPHPExcel = new PHPExcel();

		$objPHPExcel->getProperties()->setCreator("");
		$objPHPExcel->getProperties()->setLastModifiedBy("");
		$objPHPExcel->getProperties()->setTitle("");
		$objPHPExcel->getProperties()->setSubject("");
		$objPHPExcel->getProperties()->setDescription("");

		$objPHPExcel->setActiveSheetIndex(0); 
		 $sheet = $objPHPExcel->getActiveSheet();

		foreach(range('A','Q') as $columnID) {
    $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
        ->setAutoSize(true);
		} 

		
		$objPHPExcel->getActiveSheet()->SetCellValue('A1','EMPLOYEE NUMBER'); 
		$objPHPExcel->getActiveSheet()->SetCellValue('B1','EMPLOYEE NAME');
		$objPHPExcel->getActiveSheet()->SetCellValue('C1','DATES');
		$objPHPExcel->getActiveSheet()->SetCellValue('D1','TIME IN');
		$objPHPExcel->getActiveSheet()->SetCellValue('E1','TIME OUT');
		$objPHPExcel->getActiveSheet()->SetCellValue('F1','Daily Hours');
		$objPHPExcel->getActiveSheet()->SetCellValue('G1','TARDINESS');
		$objPHPExcel->getActiveSheet()->SetCellValue('H1','UNDERTIME');
		$objPHPExcel->getActiveSheet()->SetCellValue('I1','REGULAR OT');
		$objPHPExcel->getActiveSheet()->SetCellValue('J1','LEGAL HOLIDAY OT');
		$objPHPExcel->getActiveSheet()->SetCellValue('K1','SPECIAL HOLIDAY OT');
		$objPHPExcel->getActiveSheet()->SetCellValue('L1','RESTDAY OT');
		$objPHPExcel->getActiveSheet()->SetCellValue('M1','NSD');
		$objPHPExcel->getActiveSheet()->SetCellValue('N1','SICK LEAVE');
		$objPHPExcel->getActiveSheet()->SetCellValue('O1','VACATION LEAVE');
		$objPHPExcel->getActiveSheet()->SetCellValue('P1','ABSENCES');
		$objPHPExcel->getActiveSheet()->SetCellValue('Q1','REMARKS');

		$objPHPExcel->getActiveSheet()->SetCellValue('A2',' ');
		$objPHPExcel->getActiveSheet()->SetCellValue('B2',' ');
		$objPHPExcel->getActiveSheet()->SetCellValue('C2',' ');
		$objPHPExcel->getActiveSheet()->SetCellValue('D2',' ');
		$objPHPExcel->getActiveSheet()->SetCellValue('E2',' ');
		$objPHPExcel->getActiveSheet()->SetCellValue('F2','HOURS.MINS');
		$objPHPExcel->getActiveSheet()->SetCellValue('G2','HOURS.MINS');
		$objPHPExcel->getActiveSheet()->SetCellValue('H2','HOURS.MINS');
		$objPHPExcel->getActiveSheet()->SetCellValue('I2','HOURS.MINS');
		$objPHPExcel->getActiveSheet()->SetCellValue('J2','HOURS.MINS');
		$objPHPExcel->getActiveSheet()->SetCellValue('K2','HOURS.MINS');
		$objPHPExcel->getActiveSheet()->SetCellValue('L2','HOURS.MINS');
		$objPHPExcel->getActiveSheet()->SetCellValue('M2','HOURS.MINS');
		$objPHPExcel->getActiveSheet()->SetCellValue('N2','DAYS');
		$objPHPExcel->getActiveSheet()->SetCellValue('O2','DAYS');
		$objPHPExcel->getActiveSheet()->SetCellValue('P2','HOURS.MINS');
		$objPHPExcel->getActiveSheet()->SetCellValue('Q2',' ');

		$row = 3;

		foreach($data['employees'] as $key => $value)
		{
			$in_office	= $data['schedules']->daily_in; 
			$out_office   = $data['schedules']->daily_out;
			$friday_out = $data['schedules']->daily_friday_out;
			$night_diff = '22:00';
			$in_daily = $value->intime;
			$out_daily = $value->outtime;
			$week_date = date('w', strtotime($value->dates)); // Convert in days . friday (5)

			// EXPLODE DATE IN TIME IN / TIME OUT
			$explode_in_date_daily = explode(" ", $in_daily);
			$explode_out_date_daily = explode(" ", $out_daily);
			$date_date_in = $explode_in_date_daily[0];
			$date_date_out = $explode_out_date_daily[0];
			$date_in = $explode_in_date_daily[1];
			$date_out = $explode_out_date_daily[1];

			//NIGHT DIFF
			$explode_night_diff = explode(":", $night_diff);
			$night_diff_hr = $explode_night_diff[0]; 
			$night_diff_min = $explode_night_diff[1]; 
			$total_night_diff = intval($night_diff_hr*60) + $night_diff_min; // total night diff

			// EXPLODE IN AND OUT 
			$explode_in_office = explode(":", $in_office);
			$explode_out_office = explode(":", $out_office);
			$explode_friday_out_office = explode(":", $friday_out);
			$explode_in_daily = explode(":", $date_in);
			$explode_out_daily = explode(":", $date_out);
			$time_in_hr_daily = $explode_in_daily[0];
			$time_in_min_daily = $explode_in_daily[1];
			$time_out_hr_daily = $explode_out_daily[0];
			$time_out_min_daily = $explode_out_daily[1];
			$time_in_hr = $explode_in_office[0];
			$time_in_min = $explode_in_office[1];
			$time_out_hr = $explode_out_office[0];
			$time_out_min = $explode_out_office[1];
			$time_friday_out_hr = $explode_friday_out_office[0];
			$time_friday_out_min = $explode_friday_out_office[1];


			// Convert IN AND OUT
			$total_in_min = intval($time_in_hr*60) + $time_in_min; // DEFAULT IN
			$total_in_min_grace = intval($time_in_hr*60) + $time_in_min + 15; // DEFAULT IN WITH GRACE PERIOD!
			$total_out_min = intval($time_out_hr*60) + $time_out_min; // DEFAULT OUT
			$total_friday_out_min = intval($time_friday_out_hr*60) + $time_friday_out_min; // DEFAULT OUT IN FRIDAY
			$total_in_daily = intval($time_in_hr_daily*60) + $time_in_min_daily; // EMPLOYEE IN
			$total_out_daily = intval($time_out_hr_daily*60) + $time_out_min_daily; // EMPLOYEE OUT

			//COMPUTATION IN OFFICE IN AND OUT
			$total_min_diff = intval($total_out_min - $total_in_min);
			$hr_diff = intval($total_min_diff/60);
			$min_diff = intval($total_min_diff%60);
			

			// IN AND OUT OF EMPLOYEE
			$in = strtotime($value->intime);
			$out   = strtotime($value->outtime);
			$diff  = $out - $in;

			//CONVERT OF IN AND OUT
			$hours = floor($diff / (60 * 60));
			$minutes = $diff - $hours * (60 * 60); 
			$total_minutes = floor( $minutes / 60 );
			
			// COMPUTATION OF IN AND OUT
			$total_number_of_hours = $hours.".".$total_minutes; //
			$total_office_hours = $hr_diff.".".$min_diff; // 9:30 Fixed
			$number_hr_daily = $total_number_of_hours; // TOTAL HOURS DAILY!
			$start_halfday = 660; 
			$end_halfday = 750;

			$objPHPExcel->getActiveSheet()->SetCellValue('A'.$row,$value->employee_number);
			$objPHPExcel->getActiveSheet()->SetCellValue('B'.$row,$value->name);
			$objPHPExcel->getActiveSheet()->SetCellValue('C'.$row,$value->dates);
			$objPHPExcel->getActiveSheet()->SetCellValue('D'.$row,$date_in);
			$objPHPExcel->getActiveSheet()->SetCellValue('E'.$row,$date_out);

			//DAILY HOURS
			if($week_date >= 1 && $week_date <= 4)
			{
				if($date_date_in == $date_date_out && $total_in_daily == 0 && $total_out_daily == 0)
				{
					$objPHPExcel->getActiveSheet()->SetCellValue('F'.$row," ");
				}
				elseif($total_in_min_grace >= $total_in_daily && $total_out_daily >= $total_out_min)
				{
					$total_min_diff = intval($total_out_min - $total_in_min - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objPHPExcel->getActiveSheet()->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}			
				elseif($total_in_daily > $total_in_min_grace && $total_out_daily < $total_out_min)
				{
					$total_min_diff = intval($total_out_daily - $total_in_daily - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objPHPExcel->getActiveSheet()->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($total_in_daily > $total_in_min_grace )
				{
					$total_min_diff = intval($total_out_min - $total_in_daily - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objPHPExcel->getActiveSheet()->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($total_out_daily < $total_out_min)
				{
					$total_min_diff = intval($total_out_daily - $total_in_min - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objPHPExcel->getActiveSheet()->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($total_in_daily >= $total_in_min_grace)
				{
					$total_min_diff = intval($total_out_min - $total_in_daily - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$min_diff."";
					$dly_hrs = $total_min_diff;
					$objPHPExcel->getActiveSheet()->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($total_in_daily <= $total_in_min_grace)
				{
					$total_min_diff = intval($total_out_min - $total_in_min - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objPHPExcel->getActiveSheet()->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
			}
			elseif($week_date == 5)
			{
				if($date_date_in == $date_date_out && $total_in_daily == 0 && $total_out_daily == 0)
				{
					$objPHPExcel->getActiveSheet()->SetCellValue('F'.$row," ");
				}
				elseif($date_date_in == $date_date_out && $total_in_min_grace >= $total_in_daily && $total_out_daily >= $total_friday_out_min)
				{
					$total_min_diff = intval($total_friday_out_min - $total_in_min );
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objPHPExcel->getActiveSheet()->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");

				}	
				elseif($date_date_in == $date_date_out && $total_in_daily > $total_in_min_grace && $total_out_daily < $total_friday_out_min)
				{
					$total_min_diff = intval($total_out_daily - $total_in_daily);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objPHPExcel->getActiveSheet()->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($date_date_in == $date_date_out && $total_in_daily > $total_in_min_grace )
				{
					$total_min_diff = intval($total_friday_out_min - $total_in_daily);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objPHPExcel->getActiveSheet()->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($date_date_in == $date_date_out && $total_out_daily < $total_friday_out_min)
				{
					$total_min_diff = intval($total_out_daily - $total_in_min);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objPHPExcel->getActiveSheet()->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($date_date_in != $date_date_out && $total_in_daily >= $total_in_min_grace)
				{
					$total_min_diff = intval($total_friday_out_min - $total_in_daily);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objPHPExcel->getActiveSheet()->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($date_date_in != $date_date_out || $total_in_daily <= $total_in_min_grace)
				{
					$total_min_diff = intval($total_friday_out_min - $total_in_min);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objPHPExcel->getActiveSheet()->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
			}
			elseif($week_date == 6)
			{
				$objPHPExcel->getActiveSheet()->SetCellValue('F'.$row,"SATURDAY");
			}
			elseif($week_date == 0)
			{
				$objPHPExcel->getActiveSheet()->SetCellValue('F'.$row,"SUNDAY");
			}
			else
			{
				$objPHPExcel->getActiveSheet()->SetCellValue('F'.$row," ");
			}

			//TARDINESS
			if($week_date == 1 || $week_date == 2 || $week_date == 3 || $week_date == 4)
			{
				if($start_halfday <= $total_in_daily)
				{
					$objPHPExcel->getActiveSheet()->SetCellValue('G'.$row," ");
				}
				else
				{
					if($total_in_daily > $total_in_min_grace)
					{
						$late_hr = intval($total_in_daily - $total_in_min);
						$hr_diff = intval($late_hr/60);
						$min_diff = intval($late_hr%60);
						//echo $hr_diff.".".$min_diff."";
						$minn = sprintf("%02d", $min_diff);
						$number_of_late = $hr_diff.".".$minn;
						$objPHPExcel->getActiveSheet()->SetCellValue('G'.$row,$hr_diff.".".$minn."");
						$hr_lte = $late_hr;
					}
					else
					{
						$objPHPExcel->getActiveSheet()->SetCellValue('G'.$row," ");
					}
				}
			}	
			elseif($week_date == 5)
			{
				if($start_halfday <= $total_in_daily)
				{
					$objPHPExcel->getActiveSheet()->SetCellValue('G'.$row," ");
				}
				else
				{
					if($total_in_daily > $total_in_min_grace)
					{
						$late_hr = intval($total_in_daily - $total_in_min);
						$hr_diff = intval($late_hr/60);
						$min_diff = intval($late_hr%60);
						$objPHPExcel->getActiveSheet()->SetCellValue('G'.$row,$hr_diff.".".$min_diff."");
						$minn = sprintf("%02d", $min_diff);
					}
					else
					{
						$objPHPExcel->getActiveSheet()->SetCellValue('G'.$row," ");
					}
				}
				
			}
			else
			{
				$objPHPExcel->getActiveSheet()->SetCellValue('G'.$row," ");
			}
			
			//UNDERTIME
			$halfday_in = 810;
			if($week_date == 1 || $week_date == 2 || $week_date == 3 || $week_date == 4)
			{
				if($date_date_in == $date_date_out && $total_out_daily < $halfday_in)
				{
					$objPHPExcel->getActiveSheet()->SetCellValue('H'.$row," ");
				}
				elseif($total_out_min <= $total_out_daily)
				{
					$objPHPExcel->getActiveSheet()->SetCellValue('H'.$row," ");
				}
				elseif($total_out_daily > $halfday_in)
				{
					$undertime_hr = intval($total_out_min - $total_out_daily);
					$hr_diff = intval($undertime_hr/60);
					$min_diff = intval($undertime_hr%60);
					$minn = sprintf("%02d", $min_diff);
					$objPHPExcel->getActiveSheet()->SetCellValue('H'.$row,$hr_diff.".".$minn);
				}
				else
				{
					$objPHPExcel->getActiveSheet()->SetCellValue('H'.$row," ");
				}
			}
			elseif($week_date == 5)
			{
				if($date_date_in == $date_date_out && $total_out_daily < $halfday_in)
				{
					$objPHPExcel->getActiveSheet()->SetCellValue('H'.$row," ");
				}
				elseif($total_friday_out_min <= $total_out_daily)
				{
					$objPHPExcel->getActiveSheet()->SetCellValue('H'.$row," ");
				}
				elseif($date_date_in == $date_date_out && $halfday_in < $total_out_daily)
				{
					$undertime_hr = intval($total_friday_out_min - $total_out_daily);
					$hr_diff = intval($undertime_hr/60);
					$min_diff = intval($undertime_hr%60);
					$minn = sprintf("%02d", $min_diff);
					$objPHPExcel->getActiveSheet()->SetCellValue('H'.$row,$hr_diff.".".$minn);
				}
				else
				{
					$objPHPExcel->getActiveSheet()->SetCellValue('H'.$row," ");
				}
			}
			else
			{
				$objPHPExcel->getActiveSheet()->SetCellValue('H'.$row," ");
			}

			//REGULAR OT
			foreach($data['rots'] as $key => $value1)
			{
				if($value1->ot_employee_number == $value->employee_number && $value1->date_ot == $value->dates)
				{
					$reg_ot = $value1->total_ot;
					$hr_diff = intval($reg_ot/60);
					$min_diff = intval($reg_ot%60);
					$min_diff1 = sprintf("%02d", $min_diff);
					$objPHPExcel->getActiveSheet()->SetCellValue('I'.$row,$hr_diff.".".$min_diff1);
				}
			}	

			//LEGAL HOLIDAY OT
			foreach($data['lots'] as $key => $value2)
			{
				if($value2->legal_ot_employee_number == $value->employee_number && $value2->date_ot == $value->dates)
				{
					$reg_ot = $value2->total_ot;
					$hr_diff = intval($reg_ot/60);
					$min_diff = intval($reg_ot%60);
					$min_diff1 = sprintf("%02d", $min_diff);
					$objPHPExcel->getActiveSheet()->SetCellValue('J'.$row,$hr_diff.".".$min_diff1);
				}
			}

			//SPECIAL HOLIDAY OT
			foreach($data['shots'] as $key => $value3)
			{
				if($value3->special_ot_employee_number == $value->employee_number && $value3->date_ot == $value->dates)
				{
					$reg_ot = $value3->total_ot;
					$hr_diff = intval($reg_ot/60);
					$min_diff = intval($reg_ot%60);
					$min_diff1 = sprintf("%02d", $min_diff);
					$objPHPExcel->getActiveSheet()->SetCellValue('K'.$row,$hr_diff.".".$min_diff1);
				}
			}	

			//REST DAY OT
			foreach($data['rdots'] as $key => $value4)
			{
				if($value4->restday_ot_employee_number == $value->employee_number && $value4->date_ot == $value->dates)
				{
					$reg_ot = $value4->total_ot;
					$hr_diff = intval($reg_ot/60);
					$min_diff = intval($reg_ot%60);
					$min_diff1 = sprintf("%02d", $min_diff);
					$objPHPExcel->getActiveSheet()->SetCellValue('L'.$row,$hr_diff.".".$min_diff1);
				}
			}	

			//NIGHT DIFF
			$set_night_diff_morning = '6:00';
			$explode_night_diff_morning = explode(':', $set_night_diff_morning);
			$night_diff_morning = intval($explode_night_diff_morning[0]*60);
			$compute_night_diff_morning =$night_diff_morning - $total_in_daily;
			if($total_in_daily < $night_diff_morning)
			{
				$compute_night_diff_morning;
				$hr_diff = intval($compute_night_diff_morning/60);
				$min_diff = intval($compute_night_diff_morning%60);
				if($total_in_daily == 0 && $total_out_daily == 0)
				{
					$nd = ' ';
					//echo 0;
					$objPHPExcel->getActiveSheet()->SetCellValue('M'.$row, $nd);
				}
				elseif($min_diff > 30 || $min_diff == 0)
				{
					$nd = $hr_diff."."."30";
					//echo $hr_diff."."."30";
					$objPHPExcel->getActiveSheet()->SetCellValue('M'.$row, $nd);
				}
				elseif($min_diff < 30)
				{
					$nd = $hr_diff;
					$objPHPExcel->getActiveSheet()->SetCellValue('M'.$row, $nd);
					//echo $hr_diff;
				}
			}
			else
			{
				$nd = ' ';
				$objPHPExcel->getActiveSheet()->SetCellValue('M'.$row, $nd);
				//echo 0;
			}

			//SICK LEAVE
			foreach($data['sl'] as $key => $value5)
			{
				if($value5->slvl_employee_number == $value->employee_number && $value5->sl_date == $value->dates)
				{
					$sl_per_day = $value5->total_slvl;
					$objPHPExcel->getActiveSheet()->SetCellValue('N'.$row,$sl_per_day);
				}
			}

			//VACATION LEAVE
			foreach($data['vl'] as $key => $value6)
			{
				if($value6->slvl_employee_number == $value->employee_number && $value6->vl_date == $value->dates)
				{
					$vl_per_day = $value6->total_slvl;
					$objPHPExcel->getActiveSheet()->SetCellValue('O'.$row,$vl_per_day);
				}
			}

			//ABSENCES
			foreach($data['ab'] as $key => $value7)
			{
				if($value7->slvl_employee_number == $value->employee_number && $value7->ab_date == $value->dates && $value7->slvl_type == 'AB')
				{
					$ab_per_day = $value7->total_slvl;
					$reg_ab = $ab_per_day;
					$hr_diff = intval($reg_ab/60);
					$min_diff = intval($reg_ab%60);
					$min_diff1 = sprintf("%02d", $min_diff);
					$ab_total = $hr_diff.".".$min_diff1;
					$objPHPExcel->getActiveSheet()->SetCellValue('P'.$row,$ab_total);
				}
			}

			//REMARKS
			foreach($data['remarks'] as $key => $value8)
			{
				if($value8->date == $value->dates && $value8->remarks_employee_number == $value->employee_number)
    		{
    			$remarkss = $value8->type_name;
    			$objPHPExcel->getActiveSheet()->SetCellValue('Q'.$row,$remarkss);
    		}
			}

			$row++;

			$objPHPExcel->getActiveSheet()->setTitle("ALABANG");
		}	

		/////////////////////////////////////////////////////////// ALAMINOS \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

		$i=0;
    while ($i < 1) {

      // Add new sheet
    $objWorkSheet = $objPHPExcel->createSheet($i); //Setting index when creating

	
		foreach(range('A','Q') as $columnID) {
    $objWorkSheet->getColumnDimension($columnID)
        ->setAutoSize(true);
		} 
		
		$objWorkSheet->SetCellValue('A1','EMPLOYEE NUMBER'); 
		$objWorkSheet->SetCellValue('B1','EMPLOYEE NAME');
		$objWorkSheet->SetCellValue('C1','DATES');
		$objWorkSheet->SetCellValue('D1','TIME IN');
		$objWorkSheet->SetCellValue('E1','TIME OUT');
		$objWorkSheet->SetCellValue('F1','Daily Hours');
		$objWorkSheet->SetCellValue('G1','TARDINESS');
		$objWorkSheet->SetCellValue('H1','UNDERTIME');
		$objWorkSheet->SetCellValue('I1','REGULAR OT');
		$objWorkSheet->SetCellValue('J1','LEGAL HOLIDAY OT');
		$objWorkSheet->SetCellValue('K1','SPECIAL HOLIDAY OT');
		$objWorkSheet->SetCellValue('L1','RESTDAY OT');
		$objWorkSheet->SetCellValue('M1','NSD');
		$objWorkSheet->SetCellValue('N1','SICK LEAVE');
		$objWorkSheet->SetCellValue('O1','VACATION LEAVE');
		$objWorkSheet->SetCellValue('P1','ABSENCES');
		$objWorkSheet->SetCellValue('Q1','REMARKS');

		$objWorkSheet->SetCellValue('A2',' ');
		$objWorkSheet->SetCellValue('B2',' ');
		$objWorkSheet->SetCellValue('C2',' ');
		$objWorkSheet->SetCellValue('D2',' ');
		$objWorkSheet->SetCellValue('E2',' ');
		$objWorkSheet->SetCellValue('F2','HOURS.MINS');
		$objWorkSheet->SetCellValue('G2','HOURS.MINS');
		$objWorkSheet->SetCellValue('H2','HOURS.MINS');
		$objWorkSheet->SetCellValue('I2','HOURS.MINS');
		$objWorkSheet->SetCellValue('J2','HOURS.MINS');
		$objWorkSheet->SetCellValue('K2','HOURS.MINS');
		$objWorkSheet->SetCellValue('L2','HOURS.MINS');
		$objWorkSheet->SetCellValue('M2','HOURS.MINS');
		$objWorkSheet->SetCellValue('N2','DAYS');
		$objWorkSheet->SetCellValue('O2','DAYS');
		$objWorkSheet->SetCellValue('P2','HOURS.MINS');
		$objWorkSheet->SetCellValue('Q2',' ');

		$row = 3;

		foreach($data['employeesALAMINOS'] as $key => $value)
		{
			$in_office	= $data['schedules']->daily_in; 
			$out_office   = $data['schedules']->daily_out;
			$friday_out = $data['schedules']->daily_friday_out;
			$night_diff = '22:00';
			$in_daily = $value->intime;
			$out_daily = $value->outtime;
			$week_date = date('w', strtotime($value->dates)); // Convert in days . friday (5)

			// EXPLODE DATE IN TIME IN / TIME OUT
			$explode_in_date_daily = explode(" ", $in_daily);
			$explode_out_date_daily = explode(" ", $out_daily);
			$date_date_in = $explode_in_date_daily[0];
			$date_date_out = $explode_out_date_daily[0];
			$date_in = $explode_in_date_daily[1];
			$date_out = $explode_out_date_daily[1];

			//NIGHT DIFF
			$explode_night_diff = explode(":", $night_diff);
			$night_diff_hr = $explode_night_diff[0]; 
			$night_diff_min = $explode_night_diff[1]; 
			$total_night_diff = intval($night_diff_hr*60) + $night_diff_min; // total night diff

			// EXPLODE IN AND OUT 
			$explode_in_office = explode(":", $in_office);
			$explode_out_office = explode(":", $out_office);
			$explode_friday_out_office = explode(":", $friday_out);
			$explode_in_daily = explode(":", $date_in);
			$explode_out_daily = explode(":", $date_out);
			$time_in_hr_daily = $explode_in_daily[0];
			$time_in_min_daily = $explode_in_daily[1];
			$time_out_hr_daily = $explode_out_daily[0];
			$time_out_min_daily = $explode_out_daily[1];
			$time_in_hr = $explode_in_office[0];
			$time_in_min = $explode_in_office[1];
			$time_out_hr = $explode_out_office[0];
			$time_out_min = $explode_out_office[1];
			$time_friday_out_hr = $explode_friday_out_office[0];
			$time_friday_out_min = $explode_friday_out_office[1];


			// Convert IN AND OUT
			$total_in_min = intval($time_in_hr*60) + $time_in_min; // DEFAULT IN
			$total_in_min_grace = intval($time_in_hr*60) + $time_in_min + 15; // DEFAULT IN WITH GRACE PERIOD!
			$total_out_min = intval($time_out_hr*60) + $time_out_min; // DEFAULT OUT
			$total_friday_out_min = intval($time_friday_out_hr*60) + $time_friday_out_min; // DEFAULT OUT IN FRIDAY
			$total_in_daily = intval($time_in_hr_daily*60) + $time_in_min_daily; // EMPLOYEE IN
			$total_out_daily = intval($time_out_hr_daily*60) + $time_out_min_daily; // EMPLOYEE OUT

			//COMPUTATION IN OFFICE IN AND OUT
			$total_min_diff = intval($total_out_min - $total_in_min);
			$hr_diff = intval($total_min_diff/60);
			$min_diff = intval($total_min_diff%60);
			

			// IN AND OUT OF EMPLOYEE
			$in = strtotime($value->intime);
			$out   = strtotime($value->outtime);
			$diff  = $out - $in;

			//CONVERT OF IN AND OUT
			$hours = floor($diff / (60 * 60));
			$minutes = $diff - $hours * (60 * 60); 
			$total_minutes = floor( $minutes / 60 );
			
			// COMPUTATION OF IN AND OUT
			$total_number_of_hours = $hours.".".$total_minutes; //
			$total_office_hours = $hr_diff.".".$min_diff; // 9:30 Fixed
			$number_hr_daily = $total_number_of_hours; // TOTAL HOURS DAILY!
			$start_halfday = 660; 
			$end_halfday = 750;

			$objWorkSheet->SetCellValue('A'.$row,$value->employee_number);
			$objWorkSheet->SetCellValue('B'.$row,$value->name);
			$objWorkSheet->SetCellValue('C'.$row,$value->dates);
			$objWorkSheet->SetCellValue('D'.$row,$date_in);
			$objWorkSheet->SetCellValue('E'.$row,$date_out);

			//DAILY HOURS
			if($week_date >= 1 && $week_date <= 4)
			{
				if($date_date_in == $date_date_out && $total_in_daily == 0 && $total_out_daily == 0)
				{
					$objWorkSheet->SetCellValue('F'.$row," ");
				}
				elseif($total_in_min_grace >= $total_in_daily && $total_out_daily >= $total_out_min)
				{
					$total_min_diff = intval($total_out_min - $total_in_min - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}			
				elseif($total_in_daily > $total_in_min_grace && $total_out_daily < $total_out_min)
				{
					$total_min_diff = intval($total_out_daily - $total_in_daily - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($total_in_daily > $total_in_min_grace )
				{
					$total_min_diff = intval($total_out_min - $total_in_daily - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($total_out_daily < $total_out_min)
				{
					$total_min_diff = intval($total_out_daily - $total_in_min - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($total_in_daily >= $total_in_min_grace)
				{
					$total_min_diff = intval($total_out_min - $total_in_daily - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$min_diff."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($total_in_daily <= $total_in_min_grace)
				{
					$total_min_diff = intval($total_out_min - $total_in_min - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
			}
			elseif($week_date == 5)
			{
				if($date_date_in == $date_date_out && $total_in_daily == 0 && $total_out_daily == 0)
				{
					$objWorkSheet->SetCellValue('F'.$row," ");
				}
				elseif($date_date_in == $date_date_out && $total_in_min_grace >= $total_in_daily && $total_out_daily >= $total_friday_out_min)
				{
					$total_min_diff = intval($total_friday_out_min - $total_in_min );
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");

				}	
				elseif($date_date_in == $date_date_out && $total_in_daily > $total_in_min_grace && $total_out_daily < $total_friday_out_min)
				{
					$total_min_diff = intval($total_out_daily - $total_in_daily);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($date_date_in == $date_date_out && $total_in_daily > $total_in_min_grace )
				{
					$total_min_diff = intval($total_friday_out_min - $total_in_daily);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($date_date_in == $date_date_out && $total_out_daily < $total_friday_out_min)
				{
					$total_min_diff = intval($total_out_daily - $total_in_min);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($date_date_in != $date_date_out && $total_in_daily >= $total_in_min_grace)
				{
					$total_min_diff = intval($total_friday_out_min - $total_in_daily);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($date_date_in != $date_date_out || $total_in_daily <= $total_in_min_grace)
				{
					$total_min_diff = intval($total_friday_out_min - $total_in_min);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
			}
			elseif($week_date == 6)
			{
				$objWorkSheet->SetCellValue('F'.$row,"SATURDAY");
			}
			elseif($week_date == 0)
			{
				$objWorkSheet->SetCellValue('F'.$row,"SUNDAY");
			}
			else
			{
				$objWorkSheet->SetCellValue('F'.$row," ");
			}

			//TARDINESS
			if($week_date == 1 || $week_date == 2 || $week_date == 3 || $week_date == 4)
			{
				if($start_halfday <= $total_in_daily)
				{
					$objWorkSheet->SetCellValue('G'.$row," ");
				}
				else
				{
					if($total_in_daily > $total_in_min_grace)
					{
						$late_hr = intval($total_in_daily - $total_in_min);
						$hr_diff = intval($late_hr/60);
						$min_diff = intval($late_hr%60);
						//echo $hr_diff.".".$min_diff."";
						$minn = sprintf("%02d", $min_diff);
						$number_of_late = $hr_diff.".".$minn;
						$objWorkSheet->SetCellValue('G'.$row,$hr_diff.".".$minn."");
						$hr_lte = $late_hr;
					}
					else
					{
						$objWorkSheet->SetCellValue('G'.$row," ");
					}
				}
			}	
			elseif($week_date == 5)
			{
				if($start_halfday <= $total_in_daily)
				{
					$objWorkSheet->SetCellValue('G'.$row," ");
				}
				else
				{
					if($total_in_daily > $total_in_min_grace)
					{
						$late_hr = intval($total_in_daily - $total_in_min);
						$hr_diff = intval($late_hr/60);
						$min_diff = intval($late_hr%60);
						$objWorkSheet->SetCellValue('G'.$row,$hr_diff.".".$min_diff."");
						$minn = sprintf("%02d", $min_diff);
					}
					else
					{
						$objWorkSheet->SetCellValue('G'.$row," ");
					}
				}
				
			}
			else
			{
				$objWorkSheet->SetCellValue('G'.$row," ");
			}
			
			//UNDERTIME
			$halfday_in = 810;
			if($week_date == 1 || $week_date == 2 || $week_date == 3 || $week_date == 4)
			{
				if($date_date_in == $date_date_out && $total_out_daily < $halfday_in)
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
				elseif($total_out_min <= $total_out_daily)
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
				elseif($total_out_daily > $halfday_in)
				{
					$undertime_hr = intval($total_out_min - $total_out_daily);
					$hr_diff = intval($undertime_hr/60);
					$min_diff = intval($undertime_hr%60);
					$minn = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('H'.$row,$hr_diff.".".$minn);
				}
				else
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
			}
			elseif($week_date == 5)
			{
				if($date_date_in == $date_date_out && $total_out_daily < $halfday_in)
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
				elseif($total_friday_out_min <= $total_out_daily)
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
				elseif($date_date_in == $date_date_out && $halfday_in < $total_out_daily)
				{
					$undertime_hr = intval($total_friday_out_min - $total_out_daily);
					$hr_diff = intval($undertime_hr/60);
					$min_diff = intval($undertime_hr%60);
					$minn = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('H'.$row,$hr_diff.".".$minn);
				}
				else
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
			}
			else
			{
				$objWorkSheet->SetCellValue('H'.$row," ");
			}

			//REGULAR OT
			foreach($data['rots'] as $key => $value1)
			{
				if($value1->ot_employee_number == $value->employee_number && $value1->date_ot == $value->dates)
				{
					$reg_ot = $value1->total_ot;
					$hr_diff = intval($reg_ot/60);
					$min_diff = intval($reg_ot%60);
					$min_diff1 = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('I'.$row,$hr_diff.".".$min_diff1);
				}
			}	

			//LEGAL HOLIDAY OT
			foreach($data['lots'] as $key => $value2)
			{
				if($value2->legal_ot_employee_number == $value->employee_number && $value2->date_ot == $value->dates)
				{
					$reg_ot = $value2->total_ot;
					$hr_diff = intval($reg_ot/60);
					$min_diff = intval($reg_ot%60);
					$min_diff1 = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('J'.$row,$hr_diff.".".$min_diff1);
				}
			}

			//SPECIAL HOLIDAY OT
			foreach($data['shots'] as $key => $value3)
			{
				if($value3->special_ot_employee_number == $value->employee_number && $value3->date_ot == $value->dates)
				{
					$reg_ot = $value3->total_ot;
					$hr_diff = intval($reg_ot/60);
					$min_diff = intval($reg_ot%60);
					$min_diff1 = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('K'.$row,$hr_diff.".".$min_diff1);
				}
			}	

			//REST DAY OT
			foreach($data['rdots'] as $key => $value4)
			{
				if($value4->restday_ot_employee_number == $value->employee_number && $value4->date_ot == $value->dates)
				{
					$reg_ot = $value4->total_ot;
					$hr_diff = intval($reg_ot/60);
					$min_diff = intval($reg_ot%60);
					$min_diff1 = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('L'.$row,$hr_diff.".".$min_diff1);
				}
			}	

			//NIGHT DIFF
			$set_night_diff_morning = '6:00';
			$explode_night_diff_morning = explode(':', $set_night_diff_morning);
			$night_diff_morning = intval($explode_night_diff_morning[0]*60);
			$compute_night_diff_morning =$night_diff_morning - $total_in_daily;
			if($total_in_daily < $night_diff_morning)
			{
				$compute_night_diff_morning;
				$hr_diff = intval($compute_night_diff_morning/60);
				$min_diff = intval($compute_night_diff_morning%60);
				if($total_in_daily == 0 && $total_out_daily == 0)
				{
					$nd = ' ';
					//echo 0;
					$objWorkSheet->SetCellValue('M'.$row, $nd);
				}
				elseif($min_diff > 30 || $min_diff == 0)
				{
					$nd = $hr_diff."."."30";
					//echo $hr_diff."."."30";
					$objWorkSheet->SetCellValue('M'.$row, $nd);
				}
				elseif($min_diff < 30)
				{
					$nd = $hr_diff;
					$objWorkSheet->SetCellValue('M'.$row, $nd);
					//echo $hr_diff;
				}
			}
			else
			{
				$nd = ' ';
				$objWorkSheet->SetCellValue('M'.$row, $nd);
				//echo 0;
			}

			//SICK LEAVE
			foreach($data['sl'] as $key => $value5)
			{
				if($value5->slvl_employee_number == $value->employee_number && $value5->sl_date == $value->dates)
				{
					$sl_per_day = $value5->total_slvl;
					$objWorkSheet->SetCellValue('N'.$row,$sl_per_day);
				}
			}

			//VACATION LEAVE
			foreach($data['vl'] as $key => $value6)
			{
				if($value6->slvl_employee_number == $value->employee_number && $value6->vl_date == $value->dates)
				{
					$vl_per_day = $value6->total_slvl;
					$objWorkSheet->SetCellValue('O'.$row,$vl_per_day);
				}
			}

			//ABSENCES
			foreach($data['ab'] as $key => $value7)
			{
				if($value7->slvl_employee_number == $value->employee_number && $value7->ab_date == $value->dates && $value7->slvl_type == 'AB')
				{
					$ab_per_day = $value7->total_slvl;
					$reg_ab = $ab_per_day;
					$hr_diff = intval($reg_ab/60);
					$min_diff = intval($reg_ab%60);
					$min_diff1 = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('P'.$row,$hr_diff.".".$min_diff1);
				}
			}

			//REMARKS
			foreach($data['remarks'] as $key => $value8)
			{
				if($value8->date == $value->dates && $value8->remarks_employee_number == $value->employee_number)
    		{
    			$remarkss = $value8->type_name;
    			$objWorkSheet->SetCellValue('Q'.$row,$remarkss);
    		}
			}

			$row++;
		}	
    $objWorkSheet->setTitle("ALAMINOS");


    $i++;
    }

    /////////////////////////////////////////////////////////// BACLARAN ///////////////////////////////////////

    $i=0;
    while ($i < 1) {

      // Add new sheet
    $objWorkSheet = $objPHPExcel->createSheet($i); //Setting index when creating

	
		foreach(range('A','Q') as $columnID) {
    $objWorkSheet->getColumnDimension($columnID)
        ->setAutoSize(true);
		} 
		
		$objWorkSheet->SetCellValue('A1','EMPLOYEE NUMBER'); 
		$objWorkSheet->SetCellValue('B1','EMPLOYEE NAME');
		$objWorkSheet->SetCellValue('C1','DATES');
		$objWorkSheet->SetCellValue('D1','TIME IN');
		$objWorkSheet->SetCellValue('E1','TIME OUT');
		$objWorkSheet->SetCellValue('F1','Daily Hours');
		$objWorkSheet->SetCellValue('G1','TARDINESS');
		$objWorkSheet->SetCellValue('H1','UNDERTIME');
		$objWorkSheet->SetCellValue('I1','REGULAR OT');
		$objWorkSheet->SetCellValue('J1','LEGAL HOLIDAY OT');
		$objWorkSheet->SetCellValue('K1','SPECIAL HOLIDAY OT');
		$objWorkSheet->SetCellValue('L1','RESTDAY OT');
		$objWorkSheet->SetCellValue('M1','NSD');
		$objWorkSheet->SetCellValue('N1','SICK LEAVE');
		$objWorkSheet->SetCellValue('O1','VACATION LEAVE');
		$objWorkSheet->SetCellValue('P1','ABSENCES');
		$objWorkSheet->SetCellValue('Q1','REMARKS');

		$objWorkSheet->SetCellValue('A2',' ');
		$objWorkSheet->SetCellValue('B2',' ');
		$objWorkSheet->SetCellValue('C2',' ');
		$objWorkSheet->SetCellValue('D2',' ');
		$objWorkSheet->SetCellValue('E2',' ');
		$objWorkSheet->SetCellValue('F2','HOURS.MINS');
		$objWorkSheet->SetCellValue('G2','HOURS.MINS');
		$objWorkSheet->SetCellValue('H2','HOURS.MINS');
		$objWorkSheet->SetCellValue('I2','HOURS.MINS');
		$objWorkSheet->SetCellValue('J2','HOURS.MINS');
		$objWorkSheet->SetCellValue('K2','HOURS.MINS');
		$objWorkSheet->SetCellValue('L2','HOURS.MINS');
		$objWorkSheet->SetCellValue('M2','HOURS.MINS');
		$objWorkSheet->SetCellValue('N2','DAYS');
		$objWorkSheet->SetCellValue('O2','DAYS');
		$objWorkSheet->SetCellValue('P2','HOURS.MINS');
		$objWorkSheet->SetCellValue('Q2',' ');

		$row = 3;

		foreach($data['employeesBaclaran'] as $key => $value)
		{
			$in_office	= $data['schedules']->daily_in; 
			$out_office   = $data['schedules']->daily_out;
			$friday_out = $data['schedules']->daily_friday_out;
			$night_diff = '22:00';
			$in_daily = $value->intime;
			$out_daily = $value->outtime;
			$week_date = date('w', strtotime($value->dates)); // Convert in days . friday (5)

			// EXPLODE DATE IN TIME IN / TIME OUT
			$explode_in_date_daily = explode(" ", $in_daily);
			$explode_out_date_daily = explode(" ", $out_daily);
			$date_date_in = $explode_in_date_daily[0];
			$date_date_out = $explode_out_date_daily[0];
			$date_in = $explode_in_date_daily[1];
			$date_out = $explode_out_date_daily[1];

			//NIGHT DIFF
			$explode_night_diff = explode(":", $night_diff);
			$night_diff_hr = $explode_night_diff[0]; 
			$night_diff_min = $explode_night_diff[1]; 
			$total_night_diff = intval($night_diff_hr*60) + $night_diff_min; // total night diff

			// EXPLODE IN AND OUT 
			$explode_in_office = explode(":", $in_office);
			$explode_out_office = explode(":", $out_office);
			$explode_friday_out_office = explode(":", $friday_out);
			$explode_in_daily = explode(":", $date_in);
			$explode_out_daily = explode(":", $date_out);
			$time_in_hr_daily = $explode_in_daily[0];
			$time_in_min_daily = $explode_in_daily[1];
			$time_out_hr_daily = $explode_out_daily[0];
			$time_out_min_daily = $explode_out_daily[1];
			$time_in_hr = $explode_in_office[0];
			$time_in_min = $explode_in_office[1];
			$time_out_hr = $explode_out_office[0];
			$time_out_min = $explode_out_office[1];
			$time_friday_out_hr = $explode_friday_out_office[0];
			$time_friday_out_min = $explode_friday_out_office[1];


			// Convert IN AND OUT
			$total_in_min = intval($time_in_hr*60) + $time_in_min; // DEFAULT IN
			$total_in_min_grace = intval($time_in_hr*60) + $time_in_min + 15; // DEFAULT IN WITH GRACE PERIOD!
			$total_out_min = intval($time_out_hr*60) + $time_out_min; // DEFAULT OUT
			$total_friday_out_min = intval($time_friday_out_hr*60) + $time_friday_out_min; // DEFAULT OUT IN FRIDAY
			$total_in_daily = intval($time_in_hr_daily*60) + $time_in_min_daily; // EMPLOYEE IN
			$total_out_daily = intval($time_out_hr_daily*60) + $time_out_min_daily; // EMPLOYEE OUT

			//COMPUTATION IN OFFICE IN AND OUT
			$total_min_diff = intval($total_out_min - $total_in_min);
			$hr_diff = intval($total_min_diff/60);
			$min_diff = intval($total_min_diff%60);
			

			// IN AND OUT OF EMPLOYEE
			$in = strtotime($value->intime);
			$out   = strtotime($value->outtime);
			$diff  = $out - $in;

			//CONVERT OF IN AND OUT
			$hours = floor($diff / (60 * 60));
			$minutes = $diff - $hours * (60 * 60); 
			$total_minutes = floor( $minutes / 60 );
			
			// COMPUTATION OF IN AND OUT
			$total_number_of_hours = $hours.".".$total_minutes; //
			$total_office_hours = $hr_diff.".".$min_diff; // 9:30 Fixed
			$number_hr_daily = $total_number_of_hours; // TOTAL HOURS DAILY!
			$start_halfday = 660; 
			$end_halfday = 750;

			$objWorkSheet->SetCellValue('A'.$row,$value->employee_number);
			$objWorkSheet->SetCellValue('B'.$row,$value->name);
			$objWorkSheet->SetCellValue('C'.$row,$value->dates);
			$objWorkSheet->SetCellValue('D'.$row,$date_in);
			$objWorkSheet->SetCellValue('E'.$row,$date_out);

			//DAILY HOURS
			if($week_date >= 1 && $week_date <= 4)
			{
				if($date_date_in == $date_date_out && $total_in_daily == 0 && $total_out_daily == 0)
				{
					$objWorkSheet->SetCellValue('F'.$row," ");
				}
				elseif($total_in_min_grace >= $total_in_daily && $total_out_daily >= $total_out_min)
				{
					$total_min_diff = intval($total_out_min - $total_in_min - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}			
				elseif($total_in_daily > $total_in_min_grace && $total_out_daily < $total_out_min)
				{
					$total_min_diff = intval($total_out_daily - $total_in_daily - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($total_in_daily > $total_in_min_grace )
				{
					$total_min_diff = intval($total_out_min - $total_in_daily - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($total_out_daily < $total_out_min)
				{
					$total_min_diff = intval($total_out_daily - $total_in_min - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($total_in_daily >= $total_in_min_grace)
				{
					$total_min_diff = intval($total_out_min - $total_in_daily - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$min_diff."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($total_in_daily <= $total_in_min_grace)
				{
					$total_min_diff = intval($total_out_min - $total_in_min - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
			}
			elseif($week_date == 5)
			{
				if($date_date_in == $date_date_out && $total_in_daily == 0 && $total_out_daily == 0)
				{
					$objWorkSheet->SetCellValue('F'.$row," ");
				}
				elseif($date_date_in == $date_date_out && $total_in_min_grace >= $total_in_daily && $total_out_daily >= $total_friday_out_min)
				{
					$total_min_diff = intval($total_friday_out_min - $total_in_min );
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");

				}	
				elseif($date_date_in == $date_date_out && $total_in_daily > $total_in_min_grace && $total_out_daily < $total_friday_out_min)
				{
					$total_min_diff = intval($total_out_daily - $total_in_daily);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($date_date_in == $date_date_out && $total_in_daily > $total_in_min_grace )
				{
					$total_min_diff = intval($total_friday_out_min - $total_in_daily);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($date_date_in == $date_date_out && $total_out_daily < $total_friday_out_min)
				{
					$total_min_diff = intval($total_out_daily - $total_in_min);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($date_date_in != $date_date_out && $total_in_daily >= $total_in_min_grace)
				{
					$total_min_diff = intval($total_friday_out_min - $total_in_daily);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($date_date_in != $date_date_out || $total_in_daily <= $total_in_min_grace)
				{
					$total_min_diff = intval($total_friday_out_min - $total_in_min);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
			}
			elseif($week_date == 6)
			{
				$objWorkSheet->SetCellValue('F'.$row,"SATURDAY");
			}
			elseif($week_date == 0)
			{
				$objWorkSheet->SetCellValue('F'.$row,"SUNDAY");
			}
			else
			{
				$objWorkSheet->SetCellValue('F'.$row," ");
			}

			//TARDINESS
			if($week_date == 1 || $week_date == 2 || $week_date == 3 || $week_date == 4)
			{
				if($start_halfday <= $total_in_daily)
				{
					$objWorkSheet->SetCellValue('G'.$row," ");
				}
				else
				{
					if($total_in_daily > $total_in_min_grace)
					{
						$late_hr = intval($total_in_daily - $total_in_min);
						$hr_diff = intval($late_hr/60);
						$min_diff = intval($late_hr%60);
						//echo $hr_diff.".".$min_diff."";
						$minn = sprintf("%02d", $min_diff);
						$number_of_late = $hr_diff.".".$minn;
						$objWorkSheet->SetCellValue('G'.$row,$hr_diff.".".$minn."");
						$hr_lte = $late_hr;
					}
					else
					{
						$objWorkSheet->SetCellValue('G'.$row," ");
					}
				}
			}	
			elseif($week_date == 5)
			{
				if($start_halfday <= $total_in_daily)
				{
					$objWorkSheet->SetCellValue('G'.$row," ");
				}
				else
				{
					if($total_in_daily > $total_in_min_grace)
					{
						$late_hr = intval($total_in_daily - $total_in_min);
						$hr_diff = intval($late_hr/60);
						$min_diff = intval($late_hr%60);
						$objWorkSheet->SetCellValue('G'.$row,$hr_diff.".".$min_diff."");
						$minn = sprintf("%02d", $min_diff);
					}
					else
					{
						$objWorkSheet->SetCellValue('G'.$row," ");
					}
				}
				
			}
			else
			{
				$objWorkSheet->SetCellValue('G'.$row," ");
			}
			
			//UNDERTIME
			$halfday_in = 810;
			if($week_date == 1 || $week_date == 2 || $week_date == 3 || $week_date == 4)
			{
				if($date_date_in == $date_date_out && $total_out_daily < $halfday_in)
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
				elseif($total_out_min <= $total_out_daily)
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
				elseif($total_out_daily > $halfday_in)
				{
					$undertime_hr = intval($total_out_min - $total_out_daily);
					$hr_diff = intval($undertime_hr/60);
					$min_diff = intval($undertime_hr%60);
					$minn = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('H'.$row,$hr_diff.".".$minn);
				}
				else
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
			}
			elseif($week_date == 5)
			{
				if($date_date_in == $date_date_out && $total_out_daily < $halfday_in)
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
				elseif($total_friday_out_min <= $total_out_daily)
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
				elseif($date_date_in == $date_date_out && $halfday_in < $total_out_daily)
				{
					$undertime_hr = intval($total_friday_out_min - $total_out_daily);
					$hr_diff = intval($undertime_hr/60);
					$min_diff = intval($undertime_hr%60);
					$minn = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('H'.$row,$hr_diff.".".$minn);
				}
				else
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
			}
			else
			{
				$objWorkSheet->SetCellValue('H'.$row," ");
			}

			//REGULAR OT
			foreach($data['rots'] as $key => $value1)
			{
				if($value1->ot_employee_number == $value->employee_number && $value1->date_ot == $value->dates)
				{
					$reg_ot = $value1->total_ot;
					$hr_diff = intval($reg_ot/60);
					$min_diff = intval($reg_ot%60);
					$min_diff1 = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('I'.$row,$hr_diff.".".$min_diff1);
				}
			}	

			//LEGAL HOLIDAY OT
			foreach($data['lots'] as $key => $value2)
			{
				if($value2->legal_ot_employee_number == $value->employee_number && $value2->date_ot == $value->dates)
				{
					$reg_ot = $value2->total_ot;
					$hr_diff = intval($reg_ot/60);
					$min_diff = intval($reg_ot%60);
					$min_diff1 = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('J'.$row,$hr_diff.".".$min_diff1);
				}
			}

			//SPECIAL HOLIDAY OT
			foreach($data['shots'] as $key => $value3)
			{
				if($value3->special_ot_employee_number == $value->employee_number && $value3->date_ot == $value->dates)
				{
					$reg_ot = $value3->total_ot;
					$hr_diff = intval($reg_ot/60);
					$min_diff = intval($reg_ot%60);
					$min_diff1 = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('K'.$row,$hr_diff.".".$min_diff1);
				}
			}	

			//REST DAY OT
			foreach($data['rdots'] as $key => $value4)
			{
				if($value4->restday_ot_employee_number == $value->employee_number && $value4->date_ot == $value->dates)
				{
					$reg_ot = $value4->total_ot;
					$hr_diff = intval($reg_ot/60);
					$min_diff = intval($reg_ot%60);
					$min_diff1 = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('L'.$row,$hr_diff.".".$min_diff1);
				}
			}	

			//NIGHT DIFF
			$set_night_diff_morning = '6:00';
			$explode_night_diff_morning = explode(':', $set_night_diff_morning);
			$night_diff_morning = intval($explode_night_diff_morning[0]*60);
			$compute_night_diff_morning =$night_diff_morning - $total_in_daily;
			if($total_in_daily < $night_diff_morning)
			{
				$compute_night_diff_morning;
				$hr_diff = intval($compute_night_diff_morning/60);
				$min_diff = intval($compute_night_diff_morning%60);
				if($total_in_daily == 0 && $total_out_daily == 0)
				{
					$nd = ' ';
					//echo 0;
					$objWorkSheet->SetCellValue('M'.$row, $nd);
				}
				elseif($min_diff > 30 || $min_diff == 0)
				{
					$nd = $hr_diff."."."30";
					//echo $hr_diff."."."30";
					$objWorkSheet->SetCellValue('M'.$row, $nd);
				}
				elseif($min_diff < 30)
				{
					$nd = $hr_diff;
					$objWorkSheet->SetCellValue('M'.$row, $nd);
					//echo $hr_diff;
				}
			}
			else
			{
				$nd = ' ';
				$objWorkSheet->SetCellValue('M'.$row, $nd);
				//echo 0;
			}

			//SICK LEAVE
			foreach($data['sl'] as $key => $value5)
			{
				if($value5->slvl_employee_number == $value->employee_number && $value5->sl_date == $value->dates)
				{
					$sl_per_day = $value5->total_slvl;
					$objWorkSheet->SetCellValue('N'.$row,$sl_per_day);
				}
			}

			//VACATION LEAVE
			foreach($data['vl'] as $key => $value6)
			{
				if($value6->slvl_employee_number == $value->employee_number && $value6->vl_date == $value->dates)
				{
					$vl_per_day = $value6->total_slvl;
					$objWorkSheet->SetCellValue('O'.$row,$vl_per_day);
				}
			}

			//ABSENCES
			foreach($data['ab'] as $key => $value7)
			{
				if($value7->slvl_employee_number == $value->employee_number && $value7->ab_date == $value->dates && $value7->slvl_type == 'AB')
				{
					$ab_per_day = $value7->total_slvl;
					$reg_ab = $ab_per_day;
					$hr_diff = intval($reg_ab/60);
					$min_diff = intval($reg_ab%60);
					$min_diff1 = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('P'.$row,$hr_diff.".".$min_diff1);
				}
			}

			//REMARKS
			foreach($data['remarks'] as $key => $value8)
			{
				if($value8->date == $value->dates && $value8->remarks_employee_number == $value->employee_number)
    		{
    			$remarkss = $value8->type_name;
    			$objWorkSheet->SetCellValue('Q'.$row,$remarkss);
    		}
			}

			$row++;
		}	
    $objWorkSheet->setTitle("BACLARAN");


    ////////////////////////////////////////////////////// BAGUIO ////////////////////////////////////////////

    $i++;
    }

    $i=0;
    while ($i < 1) {

      // Add new sheet
    $objWorkSheet = $objPHPExcel->createSheet($i); //Setting index when creating

	
		foreach(range('A','Q') as $columnID) {
    $objWorkSheet->getColumnDimension($columnID)
        ->setAutoSize(true);
		} 
		
		$objWorkSheet->SetCellValue('A1','EMPLOYEE NUMBER'); 
		$objWorkSheet->SetCellValue('B1','EMPLOYEE NAME');
		$objWorkSheet->SetCellValue('C1','DATES');
		$objWorkSheet->SetCellValue('D1','TIME IN');
		$objWorkSheet->SetCellValue('E1','TIME OUT');
		$objWorkSheet->SetCellValue('F1','Daily Hours');
		$objWorkSheet->SetCellValue('G1','TARDINESS');
		$objWorkSheet->SetCellValue('H1','UNDERTIME');
		$objWorkSheet->SetCellValue('I1','REGULAR OT');
		$objWorkSheet->SetCellValue('J1','LEGAL HOLIDAY OT');
		$objWorkSheet->SetCellValue('K1','SPECIAL HOLIDAY OT');
		$objWorkSheet->SetCellValue('L1','RESTDAY OT');
		$objWorkSheet->SetCellValue('M1','NSD');
		$objWorkSheet->SetCellValue('N1','SICK LEAVE');
		$objWorkSheet->SetCellValue('O1','VACATION LEAVE');
		$objWorkSheet->SetCellValue('P1','ABSENCES');
		$objWorkSheet->SetCellValue('Q1','REMARKS');

		$objWorkSheet->SetCellValue('A2',' ');
		$objWorkSheet->SetCellValue('B2',' ');
		$objWorkSheet->SetCellValue('C2',' ');
		$objWorkSheet->SetCellValue('D2',' ');
		$objWorkSheet->SetCellValue('E2',' ');
		$objWorkSheet->SetCellValue('F2','HOURS.MINS');
		$objWorkSheet->SetCellValue('G2','HOURS.MINS');
		$objWorkSheet->SetCellValue('H2','HOURS.MINS');
		$objWorkSheet->SetCellValue('I2','HOURS.MINS');
		$objWorkSheet->SetCellValue('J2','HOURS.MINS');
		$objWorkSheet->SetCellValue('K2','HOURS.MINS');
		$objWorkSheet->SetCellValue('L2','HOURS.MINS');
		$objWorkSheet->SetCellValue('M2','HOURS.MINS');
		$objWorkSheet->SetCellValue('N2','DAYS');
		$objWorkSheet->SetCellValue('O2','DAYS');
		$objWorkSheet->SetCellValue('P2','HOURS.MINS');
		$objWorkSheet->SetCellValue('Q2',' ');

		$row = 3;

		foreach($data['employeesBaguio'] as $key => $value)
		{
			$in_office	= $data['schedules']->daily_in; 
			$out_office   = $data['schedules']->daily_out;
			$friday_out = $data['schedules']->daily_friday_out;
			$night_diff = '22:00';
			$in_daily = $value->intime;
			$out_daily = $value->outtime;
			$week_date = date('w', strtotime($value->dates)); // Convert in days . friday (5)

			// EXPLODE DATE IN TIME IN / TIME OUT
			$explode_in_date_daily = explode(" ", $in_daily);
			$explode_out_date_daily = explode(" ", $out_daily);
			$date_date_in = $explode_in_date_daily[0];
			$date_date_out = $explode_out_date_daily[0];
			$date_in = $explode_in_date_daily[1];
			$date_out = $explode_out_date_daily[1];

			//NIGHT DIFF
			$explode_night_diff = explode(":", $night_diff);
			$night_diff_hr = $explode_night_diff[0]; 
			$night_diff_min = $explode_night_diff[1]; 
			$total_night_diff = intval($night_diff_hr*60) + $night_diff_min; // total night diff

			// EXPLODE IN AND OUT 
			$explode_in_office = explode(":", $in_office);
			$explode_out_office = explode(":", $out_office);
			$explode_friday_out_office = explode(":", $friday_out);
			$explode_in_daily = explode(":", $date_in);
			$explode_out_daily = explode(":", $date_out);
			$time_in_hr_daily = $explode_in_daily[0];
			$time_in_min_daily = $explode_in_daily[1];
			$time_out_hr_daily = $explode_out_daily[0];
			$time_out_min_daily = $explode_out_daily[1];
			$time_in_hr = $explode_in_office[0];
			$time_in_min = $explode_in_office[1];
			$time_out_hr = $explode_out_office[0];
			$time_out_min = $explode_out_office[1];
			$time_friday_out_hr = $explode_friday_out_office[0];
			$time_friday_out_min = $explode_friday_out_office[1];


			// Convert IN AND OUT
			$total_in_min = intval($time_in_hr*60) + $time_in_min; // DEFAULT IN
			$total_in_min_grace = intval($time_in_hr*60) + $time_in_min + 15; // DEFAULT IN WITH GRACE PERIOD!
			$total_out_min = intval($time_out_hr*60) + $time_out_min; // DEFAULT OUT
			$total_friday_out_min = intval($time_friday_out_hr*60) + $time_friday_out_min; // DEFAULT OUT IN FRIDAY
			$total_in_daily = intval($time_in_hr_daily*60) + $time_in_min_daily; // EMPLOYEE IN
			$total_out_daily = intval($time_out_hr_daily*60) + $time_out_min_daily; // EMPLOYEE OUT

			//COMPUTATION IN OFFICE IN AND OUT
			$total_min_diff = intval($total_out_min - $total_in_min);
			$hr_diff = intval($total_min_diff/60);
			$min_diff = intval($total_min_diff%60);
			

			// IN AND OUT OF EMPLOYEE
			$in = strtotime($value->intime);
			$out   = strtotime($value->outtime);
			$diff  = $out - $in;

			//CONVERT OF IN AND OUT
			$hours = floor($diff / (60 * 60));
			$minutes = $diff - $hours * (60 * 60); 
			$total_minutes = floor( $minutes / 60 );
			
			// COMPUTATION OF IN AND OUT
			$total_number_of_hours = $hours.".".$total_minutes; //
			$total_office_hours = $hr_diff.".".$min_diff; // 9:30 Fixed
			$number_hr_daily = $total_number_of_hours; // TOTAL HOURS DAILY!
			$start_halfday = 660; 
			$end_halfday = 750;

			$objWorkSheet->SetCellValue('A'.$row,$value->employee_number);
			$objWorkSheet->SetCellValue('B'.$row,$value->name);
			$objWorkSheet->SetCellValue('C'.$row,$value->dates);
			$objWorkSheet->SetCellValue('D'.$row,$date_in);
			$objWorkSheet->SetCellValue('E'.$row,$date_out);

			//DAILY HOURS
			if($week_date >= 1 && $week_date <= 4)
			{
				if($date_date_in == $date_date_out && $total_in_daily == 0 && $total_out_daily == 0)
				{
					$objWorkSheet->SetCellValue('F'.$row," ");
				}
				elseif($total_in_min_grace >= $total_in_daily && $total_out_daily >= $total_out_min)
				{
					$total_min_diff = intval($total_out_min - $total_in_min - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}			
				elseif($total_in_daily > $total_in_min_grace && $total_out_daily < $total_out_min)
				{
					$total_min_diff = intval($total_out_daily - $total_in_daily - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($total_in_daily > $total_in_min_grace )
				{
					$total_min_diff = intval($total_out_min - $total_in_daily - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($total_out_daily < $total_out_min)
				{
					$total_min_diff = intval($total_out_daily - $total_in_min - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($total_in_daily >= $total_in_min_grace)
				{
					$total_min_diff = intval($total_out_min - $total_in_daily - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$min_diff."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($total_in_daily <= $total_in_min_grace)
				{
					$total_min_diff = intval($total_out_min - $total_in_min - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
			}
			elseif($week_date == 5)
			{
				if($date_date_in == $date_date_out && $total_in_daily == 0 && $total_out_daily == 0)
				{
					$objWorkSheet->SetCellValue('F'.$row," ");
				}
				elseif($date_date_in == $date_date_out && $total_in_min_grace >= $total_in_daily && $total_out_daily >= $total_friday_out_min)
				{
					$total_min_diff = intval($total_friday_out_min - $total_in_min );
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");

				}	
				elseif($date_date_in == $date_date_out && $total_in_daily > $total_in_min_grace && $total_out_daily < $total_friday_out_min)
				{
					$total_min_diff = intval($total_out_daily - $total_in_daily);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($date_date_in == $date_date_out && $total_in_daily > $total_in_min_grace )
				{
					$total_min_diff = intval($total_friday_out_min - $total_in_daily);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($date_date_in == $date_date_out && $total_out_daily < $total_friday_out_min)
				{
					$total_min_diff = intval($total_out_daily - $total_in_min);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($date_date_in != $date_date_out && $total_in_daily >= $total_in_min_grace)
				{
					$total_min_diff = intval($total_friday_out_min - $total_in_daily);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($date_date_in != $date_date_out || $total_in_daily <= $total_in_min_grace)
				{
					$total_min_diff = intval($total_friday_out_min - $total_in_min);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
			}
			elseif($week_date == 6)
			{
				$objWorkSheet->SetCellValue('F'.$row,"SATURDAY");
			}
			elseif($week_date == 0)
			{
				$objWorkSheet->SetCellValue('F'.$row,"SUNDAY");
			}
			else
			{
				$objWorkSheet->SetCellValue('F'.$row," ");
			}

			//TARDINESS
			if($week_date == 1 || $week_date == 2 || $week_date == 3 || $week_date == 4)
			{
				if($start_halfday <= $total_in_daily)
				{
					$objWorkSheet->SetCellValue('G'.$row," ");
				}
				else
				{
					if($total_in_daily > $total_in_min_grace)
					{
						$late_hr = intval($total_in_daily - $total_in_min);
						$hr_diff = intval($late_hr/60);
						$min_diff = intval($late_hr%60);
						//echo $hr_diff.".".$min_diff."";
						$minn = sprintf("%02d", $min_diff);
						$number_of_late = $hr_diff.".".$minn;
						$objWorkSheet->SetCellValue('G'.$row,$hr_diff.".".$minn."");
						$hr_lte = $late_hr;
					}
					else
					{
						$objWorkSheet->SetCellValue('G'.$row," ");
					}
				}
			}	
			elseif($week_date == 5)
			{
				if($start_halfday <= $total_in_daily)
				{
					$objWorkSheet->SetCellValue('G'.$row," ");
				}
				else
				{
					if($total_in_daily > $total_in_min_grace)
					{
						$late_hr = intval($total_in_daily - $total_in_min);
						$hr_diff = intval($late_hr/60);
						$min_diff = intval($late_hr%60);
						$objWorkSheet->SetCellValue('G'.$row,$hr_diff.".".$min_diff."");
						$minn = sprintf("%02d", $min_diff);
					}
					else
					{
						$objWorkSheet->SetCellValue('G'.$row," ");
					}
				}
				
			}
			else
			{
				$objWorkSheet->SetCellValue('G'.$row," ");
			}
			
			//UNDERTIME
			$halfday_in = 810;
			if($week_date == 1 || $week_date == 2 || $week_date == 3 || $week_date == 4)
			{
				if($date_date_in == $date_date_out && $total_out_daily < $halfday_in)
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
				elseif($total_out_min <= $total_out_daily)
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
				elseif($total_out_daily > $halfday_in)
				{
					$undertime_hr = intval($total_out_min - $total_out_daily);
					$hr_diff = intval($undertime_hr/60);
					$min_diff = intval($undertime_hr%60);
					$minn = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('H'.$row,$hr_diff.".".$minn);
				}
				else
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
			}
			elseif($week_date == 5)
			{
				if($date_date_in == $date_date_out && $total_out_daily < $halfday_in)
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
				elseif($total_friday_out_min <= $total_out_daily)
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
				elseif($date_date_in == $date_date_out && $halfday_in < $total_out_daily)
				{
					$undertime_hr = intval($total_friday_out_min - $total_out_daily);
					$hr_diff = intval($undertime_hr/60);
					$min_diff = intval($undertime_hr%60);
					$minn = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('H'.$row,$hr_diff.".".$minn);
				}
				else
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
			}
			else
			{
				$objWorkSheet->SetCellValue('H'.$row," ");
			}

			//REGULAR OT
			foreach($data['rots'] as $key => $value1)
			{
				if($value1->ot_employee_number == $value->employee_number && $value1->date_ot == $value->dates)
				{
					$reg_ot = $value1->total_ot;
					$hr_diff = intval($reg_ot/60);
					$min_diff = intval($reg_ot%60);
					$min_diff1 = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('I'.$row,$hr_diff.".".$min_diff1);
				}
			}	

			//LEGAL HOLIDAY OT
			foreach($data['lots'] as $key => $value2)
			{
				if($value2->legal_ot_employee_number == $value->employee_number && $value2->date_ot == $value->dates)
				{
					$reg_ot = $value2->total_ot;
					$hr_diff = intval($reg_ot/60);
					$min_diff = intval($reg_ot%60);
					$min_diff1 = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('J'.$row,$hr_diff.".".$min_diff1);
				}
			}

			//SPECIAL HOLIDAY OT
			foreach($data['shots'] as $key => $value3)
			{
				if($value3->special_ot_employee_number == $value->employee_number && $value3->date_ot == $value->dates)
				{
					$reg_ot = $value3->total_ot;
					$hr_diff = intval($reg_ot/60);
					$min_diff = intval($reg_ot%60);
					$min_diff1 = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('K'.$row,$hr_diff.".".$min_diff1);
				}
			}	

			//REST DAY OT
			foreach($data['rdots'] as $key => $value4)
			{
				if($value4->restday_ot_employee_number == $value->employee_number && $value4->date_ot == $value->dates)
				{
					$reg_ot = $value4->total_ot;
					$hr_diff = intval($reg_ot/60);
					$min_diff = intval($reg_ot%60);
					$min_diff1 = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('L'.$row,$hr_diff.".".$min_diff1);
				}
			}	

			//NIGHT DIFF
			$set_night_diff_morning = '6:00';
			$explode_night_diff_morning = explode(':', $set_night_diff_morning);
			$night_diff_morning = intval($explode_night_diff_morning[0]*60);
			$compute_night_diff_morning =$night_diff_morning - $total_in_daily;
			if($total_in_daily < $night_diff_morning)
			{
				$compute_night_diff_morning;
				$hr_diff = intval($compute_night_diff_morning/60);
				$min_diff = intval($compute_night_diff_morning%60);
				if($total_in_daily == 0 && $total_out_daily == 0)
				{
					$nd = ' ';
					//echo 0;
					$objWorkSheet->SetCellValue('M'.$row, $nd);
				}
				elseif($min_diff > 30 || $min_diff == 0)
				{
					$nd = $hr_diff."."."30";
					//echo $hr_diff."."."30";
					$objWorkSheet->SetCellValue('M'.$row, $nd);
				}
				elseif($min_diff < 30)
				{
					$nd = $hr_diff;
					$objWorkSheet->SetCellValue('M'.$row, $nd);
					//echo $hr_diff;
				}
			}
			else
			{
				$nd = ' ';
				$objWorkSheet->SetCellValue('M'.$row, $nd);
				//echo 0;
			}

			//SICK LEAVE
			foreach($data['sl'] as $key => $value5)
			{
				if($value5->slvl_employee_number == $value->employee_number && $value5->sl_date == $value->dates)
				{
					$sl_per_day = $value5->total_slvl;
					$objWorkSheet->SetCellValue('N'.$row,$sl_per_day);
				}
			}

			//VACATION LEAVE
			foreach($data['vl'] as $key => $value6)
			{
				if($value6->slvl_employee_number == $value->employee_number && $value6->vl_date == $value->dates)
				{
					$vl_per_day = $value6->total_slvl;
					$objWorkSheet->SetCellValue('O'.$row,$vl_per_day);
				}
			}

			//ABSENCES
			foreach($data['ab'] as $key => $value7)
			{
				if($value7->slvl_employee_number == $value->employee_number && $value7->ab_date == $value->dates && $value7->slvl_type == 'AB')
				{
					$ab_per_day = $value7->total_slvl;
					$reg_ab = $ab_per_day;
					$hr_diff = intval($reg_ab/60);
					$min_diff = intval($reg_ab%60);
					$min_diff1 = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('P'.$row,$hr_diff.".".$min_diff1);
				}
			}

			//REMARKS
			foreach($data['remarks'] as $key => $value8)
			{
				if($value8->date == $value->dates && $value8->remarks_employee_number == $value->employee_number)
    		{
    			$remarkss = $value8->type_name;
    			$objWorkSheet->SetCellValue('Q'.$row,$remarkss);
    		}
			}

			$row++;
		}	
    $objWorkSheet->setTitle("BAGUIO");


    $i++;
    }

    //////////////////////////////////////////////////BALAGTAS //////////////////////////////////////////

    $i=0;
    while ($i < 1) {

      // Add new sheet
    $objWorkSheet = $objPHPExcel->createSheet($i); //Setting index when creating

	
		foreach(range('A','Q') as $columnID) {
    $objWorkSheet->getColumnDimension($columnID)
        ->setAutoSize(true);
		} 
		
		$objWorkSheet->SetCellValue('A1','EMPLOYEE NUMBER'); 
		$objWorkSheet->SetCellValue('B1','EMPLOYEE NAME');
		$objWorkSheet->SetCellValue('C1','DATES');
		$objWorkSheet->SetCellValue('D1','TIME IN');
		$objWorkSheet->SetCellValue('E1','TIME OUT');
		$objWorkSheet->SetCellValue('F1','Daily Hours');
		$objWorkSheet->SetCellValue('G1','TARDINESS');
		$objWorkSheet->SetCellValue('H1','UNDERTIME');
		$objWorkSheet->SetCellValue('I1','REGULAR OT');
		$objWorkSheet->SetCellValue('J1','LEGAL HOLIDAY OT');
		$objWorkSheet->SetCellValue('K1','SPECIAL HOLIDAY OT');
		$objWorkSheet->SetCellValue('L1','RESTDAY OT');
		$objWorkSheet->SetCellValue('M1','NSD');
		$objWorkSheet->SetCellValue('N1','SICK LEAVE');
		$objWorkSheet->SetCellValue('O1','VACATION LEAVE');
		$objWorkSheet->SetCellValue('P1','ABSENCES');
		$objWorkSheet->SetCellValue('Q1','REMARKS');

		$objWorkSheet->SetCellValue('A2',' ');
		$objWorkSheet->SetCellValue('B2',' ');
		$objWorkSheet->SetCellValue('C2',' ');
		$objWorkSheet->SetCellValue('D2',' ');
		$objWorkSheet->SetCellValue('E2',' ');
		$objWorkSheet->SetCellValue('F2','HOURS.MINS');
		$objWorkSheet->SetCellValue('G2','HOURS.MINS');
		$objWorkSheet->SetCellValue('H2','HOURS.MINS');
		$objWorkSheet->SetCellValue('I2','HOURS.MINS');
		$objWorkSheet->SetCellValue('J2','HOURS.MINS');
		$objWorkSheet->SetCellValue('K2','HOURS.MINS');
		$objWorkSheet->SetCellValue('L2','HOURS.MINS');
		$objWorkSheet->SetCellValue('M2','HOURS.MINS');
		$objWorkSheet->SetCellValue('N2','DAYS');
		$objWorkSheet->SetCellValue('O2','DAYS');
		$objWorkSheet->SetCellValue('P2','HOURS.MINS');
		$objWorkSheet->SetCellValue('Q2',' ');

		$row = 3;

		foreach($data['employeesBalagtas'] as $key => $value)
		{
			$in_office	= $data['schedules']->daily_in; 
			$out_office   = $data['schedules']->daily_out;
			$friday_out = $data['schedules']->daily_friday_out;
			$night_diff = '22:00';
			$in_daily = $value->intime;
			$out_daily = $value->outtime;
			$week_date = date('w', strtotime($value->dates)); // Convert in days . friday (5)

			// EXPLODE DATE IN TIME IN / TIME OUT
			$explode_in_date_daily = explode(" ", $in_daily);
			$explode_out_date_daily = explode(" ", $out_daily);
			$date_date_in = $explode_in_date_daily[0];
			$date_date_out = $explode_out_date_daily[0];
			$date_in = $explode_in_date_daily[1];
			$date_out = $explode_out_date_daily[1];

			//NIGHT DIFF
			$explode_night_diff = explode(":", $night_diff);
			$night_diff_hr = $explode_night_diff[0]; 
			$night_diff_min = $explode_night_diff[1]; 
			$total_night_diff = intval($night_diff_hr*60) + $night_diff_min; // total night diff

			// EXPLODE IN AND OUT 
			$explode_in_office = explode(":", $in_office);
			$explode_out_office = explode(":", $out_office);
			$explode_friday_out_office = explode(":", $friday_out);
			$explode_in_daily = explode(":", $date_in);
			$explode_out_daily = explode(":", $date_out);
			$time_in_hr_daily = $explode_in_daily[0];
			$time_in_min_daily = $explode_in_daily[1];
			$time_out_hr_daily = $explode_out_daily[0];
			$time_out_min_daily = $explode_out_daily[1];
			$time_in_hr = $explode_in_office[0];
			$time_in_min = $explode_in_office[1];
			$time_out_hr = $explode_out_office[0];
			$time_out_min = $explode_out_office[1];
			$time_friday_out_hr = $explode_friday_out_office[0];
			$time_friday_out_min = $explode_friday_out_office[1];


			// Convert IN AND OUT
			$total_in_min = intval($time_in_hr*60) + $time_in_min; // DEFAULT IN
			$total_in_min_grace = intval($time_in_hr*60) + $time_in_min + 15; // DEFAULT IN WITH GRACE PERIOD!
			$total_out_min = intval($time_out_hr*60) + $time_out_min; // DEFAULT OUT
			$total_friday_out_min = intval($time_friday_out_hr*60) + $time_friday_out_min; // DEFAULT OUT IN FRIDAY
			$total_in_daily = intval($time_in_hr_daily*60) + $time_in_min_daily; // EMPLOYEE IN
			$total_out_daily = intval($time_out_hr_daily*60) + $time_out_min_daily; // EMPLOYEE OUT

			//COMPUTATION IN OFFICE IN AND OUT
			$total_min_diff = intval($total_out_min - $total_in_min);
			$hr_diff = intval($total_min_diff/60);
			$min_diff = intval($total_min_diff%60);
			

			// IN AND OUT OF EMPLOYEE
			$in = strtotime($value->intime);
			$out   = strtotime($value->outtime);
			$diff  = $out - $in;

			//CONVERT OF IN AND OUT
			$hours = floor($diff / (60 * 60));
			$minutes = $diff - $hours * (60 * 60); 
			$total_minutes = floor( $minutes / 60 );
			
			// COMPUTATION OF IN AND OUT
			$total_number_of_hours = $hours.".".$total_minutes; //
			$total_office_hours = $hr_diff.".".$min_diff; // 9:30 Fixed
			$number_hr_daily = $total_number_of_hours; // TOTAL HOURS DAILY!
			$start_halfday = 660; 
			$end_halfday = 750;

			$objWorkSheet->SetCellValue('A'.$row,$value->employee_number);
			$objWorkSheet->SetCellValue('B'.$row,$value->name);
			$objWorkSheet->SetCellValue('C'.$row,$value->dates);
			$objWorkSheet->SetCellValue('D'.$row,$date_in);
			$objWorkSheet->SetCellValue('E'.$row,$date_out);

			//DAILY HOURS
			if($week_date >= 1 && $week_date <= 4)
			{
				if($date_date_in == $date_date_out && $total_in_daily == 0 && $total_out_daily == 0)
				{
					$objWorkSheet->SetCellValue('F'.$row," ");
				}
				elseif($total_in_min_grace >= $total_in_daily && $total_out_daily >= $total_out_min)
				{
					$total_min_diff = intval($total_out_min - $total_in_min - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}			
				elseif($total_in_daily > $total_in_min_grace && $total_out_daily < $total_out_min)
				{
					$total_min_diff = intval($total_out_daily - $total_in_daily - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($total_in_daily > $total_in_min_grace )
				{
					$total_min_diff = intval($total_out_min - $total_in_daily - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($total_out_daily < $total_out_min)
				{
					$total_min_diff = intval($total_out_daily - $total_in_min - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($total_in_daily >= $total_in_min_grace)
				{
					$total_min_diff = intval($total_out_min - $total_in_daily - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$min_diff."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($total_in_daily <= $total_in_min_grace)
				{
					$total_min_diff = intval($total_out_min - $total_in_min - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
			}
			elseif($week_date == 5)
			{
				if($date_date_in == $date_date_out && $total_in_daily == 0 && $total_out_daily == 0)
				{
					$objWorkSheet->SetCellValue('F'.$row," ");
				}
				elseif($date_date_in == $date_date_out && $total_in_min_grace >= $total_in_daily && $total_out_daily >= $total_friday_out_min)
				{
					$total_min_diff = intval($total_friday_out_min - $total_in_min );
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");

				}	
				elseif($date_date_in == $date_date_out && $total_in_daily > $total_in_min_grace && $total_out_daily < $total_friday_out_min)
				{
					$total_min_diff = intval($total_out_daily - $total_in_daily);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($date_date_in == $date_date_out && $total_in_daily > $total_in_min_grace )
				{
					$total_min_diff = intval($total_friday_out_min - $total_in_daily);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($date_date_in == $date_date_out && $total_out_daily < $total_friday_out_min)
				{
					$total_min_diff = intval($total_out_daily - $total_in_min);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($date_date_in != $date_date_out && $total_in_daily >= $total_in_min_grace)
				{
					$total_min_diff = intval($total_friday_out_min - $total_in_daily);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($date_date_in != $date_date_out || $total_in_daily <= $total_in_min_grace)
				{
					$total_min_diff = intval($total_friday_out_min - $total_in_min);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
			}
			elseif($week_date == 6)
			{
				$objWorkSheet->SetCellValue('F'.$row,"SATURDAY");
			}
			elseif($week_date == 0)
			{
				$objWorkSheet->SetCellValue('F'.$row,"SUNDAY");
			}
			else
			{
				$objWorkSheet->SetCellValue('F'.$row," ");
			}

			//TARDINESS
			if($week_date == 1 || $week_date == 2 || $week_date == 3 || $week_date == 4)
			{
				if($start_halfday <= $total_in_daily)
				{
					$objWorkSheet->SetCellValue('G'.$row," ");
				}
				else
				{
					if($total_in_daily > $total_in_min_grace)
					{
						$late_hr = intval($total_in_daily - $total_in_min);
						$hr_diff = intval($late_hr/60);
						$min_diff = intval($late_hr%60);
						//echo $hr_diff.".".$min_diff."";
						$minn = sprintf("%02d", $min_diff);
						$number_of_late = $hr_diff.".".$minn;
						$objWorkSheet->SetCellValue('G'.$row,$hr_diff.".".$minn."");
						$hr_lte = $late_hr;
					}
					else
					{
						$objWorkSheet->SetCellValue('G'.$row," ");
					}
				}
			}	
			elseif($week_date == 5)
			{
				if($start_halfday <= $total_in_daily)
				{
					$objWorkSheet->SetCellValue('G'.$row," ");
				}
				else
				{
					if($total_in_daily > $total_in_min_grace)
					{
						$late_hr = intval($total_in_daily - $total_in_min);
						$hr_diff = intval($late_hr/60);
						$min_diff = intval($late_hr%60);
						$objWorkSheet->SetCellValue('G'.$row,$hr_diff.".".$min_diff."");
						$minn = sprintf("%02d", $min_diff);
					}
					else
					{
						$objWorkSheet->SetCellValue('G'.$row," ");
					}
				}
				
			}
			else
			{
				$objWorkSheet->SetCellValue('G'.$row," ");
			}
			
			//UNDERTIME
			$halfday_in = 810;
			if($week_date == 1 || $week_date == 2 || $week_date == 3 || $week_date == 4)
			{
				if($date_date_in == $date_date_out && $total_out_daily < $halfday_in)
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
				elseif($total_out_min <= $total_out_daily)
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
				elseif($total_out_daily > $halfday_in)
				{
					$undertime_hr = intval($total_out_min - $total_out_daily);
					$hr_diff = intval($undertime_hr/60);
					$min_diff = intval($undertime_hr%60);
					$minn = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('H'.$row,$hr_diff.".".$minn);
				}
				else
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
			}
			elseif($week_date == 5)
			{
				if($date_date_in == $date_date_out && $total_out_daily < $halfday_in)
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
				elseif($total_friday_out_min <= $total_out_daily)
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
				elseif($date_date_in == $date_date_out && $halfday_in < $total_out_daily)
				{
					$undertime_hr = intval($total_friday_out_min - $total_out_daily);
					$hr_diff = intval($undertime_hr/60);
					$min_diff = intval($undertime_hr%60);
					$minn = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('H'.$row,$hr_diff.".".$minn);
				}
				else
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
			}
			else
			{
				$objWorkSheet->SetCellValue('H'.$row," ");
			}

			//REGULAR OT
			foreach($data['rots'] as $key => $value1)
			{
				if($value1->ot_employee_number == $value->employee_number && $value1->date_ot == $value->dates)
				{
					$reg_ot = $value1->total_ot;
					$hr_diff = intval($reg_ot/60);
					$min_diff = intval($reg_ot%60);
					$min_diff1 = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('I'.$row,$hr_diff.".".$min_diff1);
				}
			}	

			//LEGAL HOLIDAY OT
			foreach($data['lots'] as $key => $value2)
			{
				if($value2->legal_ot_employee_number == $value->employee_number && $value2->date_ot == $value->dates)
				{
					$reg_ot = $value2->total_ot;
					$hr_diff = intval($reg_ot/60);
					$min_diff = intval($reg_ot%60);
					$min_diff1 = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('J'.$row,$hr_diff.".".$min_diff1);
				}
			}

			//SPECIAL HOLIDAY OT
			foreach($data['shots'] as $key => $value3)
			{
				if($value3->special_ot_employee_number == $value->employee_number && $value3->date_ot == $value->dates)
				{
					$reg_ot = $value3->total_ot;
					$hr_diff = intval($reg_ot/60);
					$min_diff = intval($reg_ot%60);
					$min_diff1 = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('K'.$row,$hr_diff.".".$min_diff1);
				}
			}	

			//REST DAY OT
			foreach($data['rdots'] as $key => $value4)
			{
				if($value4->restday_ot_employee_number == $value->employee_number && $value4->date_ot == $value->dates)
				{
					$reg_ot = $value4->total_ot;
					$hr_diff = intval($reg_ot/60);
					$min_diff = intval($reg_ot%60);
					$min_diff1 = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('L'.$row,$hr_diff.".".$min_diff1);
				}
			}	

			//NIGHT DIFF
			$set_night_diff_morning = '6:00';
			$explode_night_diff_morning = explode(':', $set_night_diff_morning);
			$night_diff_morning = intval($explode_night_diff_morning[0]*60);
			$compute_night_diff_morning =$night_diff_morning - $total_in_daily;
			if($total_in_daily < $night_diff_morning)
			{
				$compute_night_diff_morning;
				$hr_diff = intval($compute_night_diff_morning/60);
				$min_diff = intval($compute_night_diff_morning%60);
				if($total_in_daily == 0 && $total_out_daily == 0)
				{
					$nd = ' ';
					//echo 0;
					$objWorkSheet->SetCellValue('M'.$row, $nd);
				}
				elseif($min_diff > 30 || $min_diff == 0)
				{
					$nd = $hr_diff."."."30";
					//echo $hr_diff."."."30";
					$objWorkSheet->SetCellValue('M'.$row, $nd);
				}
				elseif($min_diff < 30)
				{
					$nd = $hr_diff;
					$objWorkSheet->SetCellValue('M'.$row, $nd);
					//echo $hr_diff;
				}
			}
			else
			{
				$nd = ' ';
				$objWorkSheet->SetCellValue('M'.$row, $nd);
				//echo 0;
			}

			//SICK LEAVE
			foreach($data['sl'] as $key => $value5)
			{
				if($value5->slvl_employee_number == $value->employee_number && $value5->sl_date == $value->dates)
				{
					$sl_per_day = $value5->total_slvl;
					$objWorkSheet->SetCellValue('N'.$row,$sl_per_day);
				}
			}

			//VACATION LEAVE
			foreach($data['vl'] as $key => $value6)
			{
				if($value6->slvl_employee_number == $value->employee_number && $value6->vl_date == $value->dates)
				{
					$vl_per_day = $value6->total_slvl;
					$objWorkSheet->SetCellValue('O'.$row,$vl_per_day);
				}
			}

			//ABSENCES
			foreach($data['ab'] as $key => $value7)
			{
				if($value7->slvl_employee_number == $value->employee_number && $value7->ab_date == $value->dates && $value7->slvl_type == 'AB')
				{
					$ab_per_day = $value7->total_slvl;
					$reg_ab = $ab_per_day;
					$hr_diff = intval($reg_ab/60);
					$min_diff = intval($reg_ab%60);
					$min_diff1 = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('P'.$row,$hr_diff.".".$min_diff1);
				}
			}

			//REMARKS
			foreach($data['remarks'] as $key => $value8)
			{
				if($value8->date == $value->dates && $value8->remarks_employee_number == $value->employee_number)
    		{
    			$remarkss = $value8->type_name;
    			$objWorkSheet->SetCellValue('Q'.$row,$remarkss);
    		}
			}

			$row++;
		}	
    $objWorkSheet->setTitle("BALAGTAS");


    $i++;
    }

    ///////////////////////////////////////////////////////// BAMBANG ////////////////////////////////////////

    $i=0;
    while ($i < 1) {

      // Add new sheet
    $objWorkSheet = $objPHPExcel->createSheet($i); //Setting index when creating

	
		foreach(range('A','Q') as $columnID) {
    $objWorkSheet->getColumnDimension($columnID)
        ->setAutoSize(true);
		} 
		
		$objWorkSheet->SetCellValue('A1','EMPLOYEE NUMBER'); 
		$objWorkSheet->SetCellValue('B1','EMPLOYEE NAME');
		$objWorkSheet->SetCellValue('C1','DATES');
		$objWorkSheet->SetCellValue('D1','TIME IN');
		$objWorkSheet->SetCellValue('E1','TIME OUT');
		$objWorkSheet->SetCellValue('F1','Daily Hours');
		$objWorkSheet->SetCellValue('G1','TARDINESS');
		$objWorkSheet->SetCellValue('H1','UNDERTIME');
		$objWorkSheet->SetCellValue('I1','REGULAR OT');
		$objWorkSheet->SetCellValue('J1','LEGAL HOLIDAY OT');
		$objWorkSheet->SetCellValue('K1','SPECIAL HOLIDAY OT');
		$objWorkSheet->SetCellValue('L1','RESTDAY OT');
		$objWorkSheet->SetCellValue('M1','NSD');
		$objWorkSheet->SetCellValue('N1','SICK LEAVE');
		$objWorkSheet->SetCellValue('O1','VACATION LEAVE');
		$objWorkSheet->SetCellValue('P1','ABSENCES');
		$objWorkSheet->SetCellValue('Q1','REMARKS');

		$objWorkSheet->SetCellValue('A2',' ');
		$objWorkSheet->SetCellValue('B2',' ');
		$objWorkSheet->SetCellValue('C2',' ');
		$objWorkSheet->SetCellValue('D2',' ');
		$objWorkSheet->SetCellValue('E2',' ');
		$objWorkSheet->SetCellValue('F2','HOURS.MINS');
		$objWorkSheet->SetCellValue('G2','HOURS.MINS');
		$objWorkSheet->SetCellValue('H2','HOURS.MINS');
		$objWorkSheet->SetCellValue('I2','HOURS.MINS');
		$objWorkSheet->SetCellValue('J2','HOURS.MINS');
		$objWorkSheet->SetCellValue('K2','HOURS.MINS');
		$objWorkSheet->SetCellValue('L2','HOURS.MINS');
		$objWorkSheet->SetCellValue('M2','HOURS.MINS');
		$objWorkSheet->SetCellValue('N2','DAYS');
		$objWorkSheet->SetCellValue('O2','DAYS');
		$objWorkSheet->SetCellValue('P2','HOURS.MINS');
		$objWorkSheet->SetCellValue('Q2',' ');

		$row = 3;

		foreach($data['employeesBambang'] as $key => $value)
		{
			$in_office	= $data['schedules']->daily_in; 
			$out_office   = $data['schedules']->daily_out;
			$friday_out = $data['schedules']->daily_friday_out;
			$night_diff = '22:00';
			$in_daily = $value->intime;
			$out_daily = $value->outtime;
			$week_date = date('w', strtotime($value->dates)); // Convert in days . friday (5)

			// EXPLODE DATE IN TIME IN / TIME OUT
			$explode_in_date_daily = explode(" ", $in_daily);
			$explode_out_date_daily = explode(" ", $out_daily);
			$date_date_in = $explode_in_date_daily[0];
			$date_date_out = $explode_out_date_daily[0];
			$date_in = $explode_in_date_daily[1];
			$date_out = $explode_out_date_daily[1];

			//NIGHT DIFF
			$explode_night_diff = explode(":", $night_diff);
			$night_diff_hr = $explode_night_diff[0]; 
			$night_diff_min = $explode_night_diff[1]; 
			$total_night_diff = intval($night_diff_hr*60) + $night_diff_min; // total night diff

			// EXPLODE IN AND OUT 
			$explode_in_office = explode(":", $in_office);
			$explode_out_office = explode(":", $out_office);
			$explode_friday_out_office = explode(":", $friday_out);
			$explode_in_daily = explode(":", $date_in);
			$explode_out_daily = explode(":", $date_out);
			$time_in_hr_daily = $explode_in_daily[0];
			$time_in_min_daily = $explode_in_daily[1];
			$time_out_hr_daily = $explode_out_daily[0];
			$time_out_min_daily = $explode_out_daily[1];
			$time_in_hr = $explode_in_office[0];
			$time_in_min = $explode_in_office[1];
			$time_out_hr = $explode_out_office[0];
			$time_out_min = $explode_out_office[1];
			$time_friday_out_hr = $explode_friday_out_office[0];
			$time_friday_out_min = $explode_friday_out_office[1];


			// Convert IN AND OUT
			$total_in_min = intval($time_in_hr*60) + $time_in_min; // DEFAULT IN
			$total_in_min_grace = intval($time_in_hr*60) + $time_in_min + 15; // DEFAULT IN WITH GRACE PERIOD!
			$total_out_min = intval($time_out_hr*60) + $time_out_min; // DEFAULT OUT
			$total_friday_out_min = intval($time_friday_out_hr*60) + $time_friday_out_min; // DEFAULT OUT IN FRIDAY
			$total_in_daily = intval($time_in_hr_daily*60) + $time_in_min_daily; // EMPLOYEE IN
			$total_out_daily = intval($time_out_hr_daily*60) + $time_out_min_daily; // EMPLOYEE OUT

			//COMPUTATION IN OFFICE IN AND OUT
			$total_min_diff = intval($total_out_min - $total_in_min);
			$hr_diff = intval($total_min_diff/60);
			$min_diff = intval($total_min_diff%60);
			

			// IN AND OUT OF EMPLOYEE
			$in = strtotime($value->intime);
			$out   = strtotime($value->outtime);
			$diff  = $out - $in;

			//CONVERT OF IN AND OUT
			$hours = floor($diff / (60 * 60));
			$minutes = $diff - $hours * (60 * 60); 
			$total_minutes = floor( $minutes / 60 );
			
			// COMPUTATION OF IN AND OUT
			$total_number_of_hours = $hours.".".$total_minutes; //
			$total_office_hours = $hr_diff.".".$min_diff; // 9:30 Fixed
			$number_hr_daily = $total_number_of_hours; // TOTAL HOURS DAILY!
			$start_halfday = 660; 
			$end_halfday = 750;

			$objWorkSheet->SetCellValue('A'.$row,$value->employee_number);
			$objWorkSheet->SetCellValue('B'.$row,$value->name);
			$objWorkSheet->SetCellValue('C'.$row,$value->dates);
			$objWorkSheet->SetCellValue('D'.$row,$date_in);
			$objWorkSheet->SetCellValue('E'.$row,$date_out);

			//DAILY HOURS
			if($week_date >= 1 && $week_date <= 4)
			{
				if($date_date_in == $date_date_out && $total_in_daily == 0 && $total_out_daily == 0)
				{
					$objWorkSheet->SetCellValue('F'.$row," ");
				}
				elseif($total_in_min_grace >= $total_in_daily && $total_out_daily >= $total_out_min)
				{
					$total_min_diff = intval($total_out_min - $total_in_min - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}			
				elseif($total_in_daily > $total_in_min_grace && $total_out_daily < $total_out_min)
				{
					$total_min_diff = intval($total_out_daily - $total_in_daily - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($total_in_daily > $total_in_min_grace )
				{
					$total_min_diff = intval($total_out_min - $total_in_daily - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($total_out_daily < $total_out_min)
				{
					$total_min_diff = intval($total_out_daily - $total_in_min - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($total_in_daily >= $total_in_min_grace)
				{
					$total_min_diff = intval($total_out_min - $total_in_daily - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$min_diff."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($total_in_daily <= $total_in_min_grace)
				{
					$total_min_diff = intval($total_out_min - $total_in_min - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
			}
			elseif($week_date == 5)
			{
				if($date_date_in == $date_date_out && $total_in_daily == 0 && $total_out_daily == 0)
				{
					$objWorkSheet->SetCellValue('F'.$row," ");
				}
				elseif($date_date_in == $date_date_out && $total_in_min_grace >= $total_in_daily && $total_out_daily >= $total_friday_out_min)
				{
					$total_min_diff = intval($total_friday_out_min - $total_in_min );
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");

				}	
				elseif($date_date_in == $date_date_out && $total_in_daily > $total_in_min_grace && $total_out_daily < $total_friday_out_min)
				{
					$total_min_diff = intval($total_out_daily - $total_in_daily);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($date_date_in == $date_date_out && $total_in_daily > $total_in_min_grace )
				{
					$total_min_diff = intval($total_friday_out_min - $total_in_daily);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($date_date_in == $date_date_out && $total_out_daily < $total_friday_out_min)
				{
					$total_min_diff = intval($total_out_daily - $total_in_min);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($date_date_in != $date_date_out && $total_in_daily >= $total_in_min_grace)
				{
					$total_min_diff = intval($total_friday_out_min - $total_in_daily);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($date_date_in != $date_date_out || $total_in_daily <= $total_in_min_grace)
				{
					$total_min_diff = intval($total_friday_out_min - $total_in_min);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
			}
			elseif($week_date == 6)
			{
				$objWorkSheet->SetCellValue('F'.$row,"SATURDAY");
			}
			elseif($week_date == 0)
			{
				$objWorkSheet->SetCellValue('F'.$row,"SUNDAY");
			}
			else
			{
				$objWorkSheet->SetCellValue('F'.$row," ");
			}

			//TARDINESS
			if($week_date == 1 || $week_date == 2 || $week_date == 3 || $week_date == 4)
			{
				if($start_halfday <= $total_in_daily)
				{
					$objWorkSheet->SetCellValue('G'.$row," ");
				}
				else
				{
					if($total_in_daily > $total_in_min_grace)
					{
						$late_hr = intval($total_in_daily - $total_in_min);
						$hr_diff = intval($late_hr/60);
						$min_diff = intval($late_hr%60);
						//echo $hr_diff.".".$min_diff."";
						$minn = sprintf("%02d", $min_diff);
						$number_of_late = $hr_diff.".".$minn;
						$objWorkSheet->SetCellValue('G'.$row,$hr_diff.".".$minn."");
						$hr_lte = $late_hr;
					}
					else
					{
						$objWorkSheet->SetCellValue('G'.$row," ");
					}
				}
			}	
			elseif($week_date == 5)
			{
				if($start_halfday <= $total_in_daily)
				{
					$objWorkSheet->SetCellValue('G'.$row," ");
				}
				else
				{
					if($total_in_daily > $total_in_min_grace)
					{
						$late_hr = intval($total_in_daily - $total_in_min);
						$hr_diff = intval($late_hr/60);
						$min_diff = intval($late_hr%60);
						$objWorkSheet->SetCellValue('G'.$row,$hr_diff.".".$min_diff."");
						$minn = sprintf("%02d", $min_diff);
					}
					else
					{
						$objWorkSheet->SetCellValue('G'.$row," ");
					}
				}
				
			}
			else
			{
				$objWorkSheet->SetCellValue('G'.$row," ");
			}
			
			//UNDERTIME
			$halfday_in = 810;
			if($week_date == 1 || $week_date == 2 || $week_date == 3 || $week_date == 4)
			{
				if($date_date_in == $date_date_out && $total_out_daily < $halfday_in)
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
				elseif($total_out_min <= $total_out_daily)
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
				elseif($total_out_daily > $halfday_in)
				{
					$undertime_hr = intval($total_out_min - $total_out_daily);
					$hr_diff = intval($undertime_hr/60);
					$min_diff = intval($undertime_hr%60);
					$minn = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('H'.$row,$hr_diff.".".$minn);
				}
				else
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
			}
			elseif($week_date == 5)
			{
				if($date_date_in == $date_date_out && $total_out_daily < $halfday_in)
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
				elseif($total_friday_out_min <= $total_out_daily)
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
				elseif($date_date_in == $date_date_out && $halfday_in < $total_out_daily)
				{
					$undertime_hr = intval($total_friday_out_min - $total_out_daily);
					$hr_diff = intval($undertime_hr/60);
					$min_diff = intval($undertime_hr%60);
					$minn = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('H'.$row,$hr_diff.".".$minn);
				}
				else
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
			}
			else
			{
				$objWorkSheet->SetCellValue('H'.$row," ");
			}

			//REGULAR OT
			foreach($data['rots'] as $key => $value1)
			{
				if($value1->ot_employee_number == $value->employee_number && $value1->date_ot == $value->dates)
				{
					$reg_ot = $value1->total_ot;
					$hr_diff = intval($reg_ot/60);
					$min_diff = intval($reg_ot%60);
					$min_diff1 = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('I'.$row,$hr_diff.".".$min_diff1);
				}
			}	

			//LEGAL HOLIDAY OT
			foreach($data['lots'] as $key => $value2)
			{
				if($value2->legal_ot_employee_number == $value->employee_number && $value2->date_ot == $value->dates)
				{
					$reg_ot = $value2->total_ot;
					$hr_diff = intval($reg_ot/60);
					$min_diff = intval($reg_ot%60);
					$min_diff1 = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('J'.$row,$hr_diff.".".$min_diff1);
				}
			}

			//SPECIAL HOLIDAY OT
			foreach($data['shots'] as $key => $value3)
			{
				if($value3->special_ot_employee_number == $value->employee_number && $value3->date_ot == $value->dates)
				{
					$reg_ot = $value3->total_ot;
					$hr_diff = intval($reg_ot/60);
					$min_diff = intval($reg_ot%60);
					$min_diff1 = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('K'.$row,$hr_diff.".".$min_diff1);
				}
			}	

			//REST DAY OT
			foreach($data['rdots'] as $key => $value4)
			{
				if($value4->restday_ot_employee_number == $value->employee_number && $value4->date_ot == $value->dates)
				{
					$reg_ot = $value4->total_ot;
					$hr_diff = intval($reg_ot/60);
					$min_diff = intval($reg_ot%60);
					$min_diff1 = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('L'.$row,$hr_diff.".".$min_diff1);
				}
			}	

			//NIGHT DIFF
			$set_night_diff_morning = '6:00';
			$explode_night_diff_morning = explode(':', $set_night_diff_morning);
			$night_diff_morning = intval($explode_night_diff_morning[0]*60);
			$compute_night_diff_morning =$night_diff_morning - $total_in_daily;
			if($total_in_daily < $night_diff_morning)
			{
				$compute_night_diff_morning;
				$hr_diff = intval($compute_night_diff_morning/60);
				$min_diff = intval($compute_night_diff_morning%60);
				if($total_in_daily == 0 && $total_out_daily == 0)
				{
					$nd = ' ';
					//echo 0;
					$objWorkSheet->SetCellValue('M'.$row, $nd);
				}
				elseif($min_diff > 30 || $min_diff == 0)
				{
					$nd = $hr_diff."."."30";
					//echo $hr_diff."."."30";
					$objWorkSheet->SetCellValue('M'.$row, $nd);
				}
				elseif($min_diff < 30)
				{
					$nd = $hr_diff;
					$objWorkSheet->SetCellValue('M'.$row, $nd);
					//echo $hr_diff;
				}
			}
			else
			{
				$nd = ' ';
				$objWorkSheet->SetCellValue('M'.$row, $nd);
				//echo 0;
			}

			//SICK LEAVE
			foreach($data['sl'] as $key => $value5)
			{
				if($value5->slvl_employee_number == $value->employee_number && $value5->sl_date == $value->dates)
				{
					$sl_per_day = $value5->total_slvl;
					$objWorkSheet->SetCellValue('N'.$row,$sl_per_day);
				}
			}

			//VACATION LEAVE
			foreach($data['vl'] as $key => $value6)
			{
				if($value6->slvl_employee_number == $value->employee_number && $value6->vl_date == $value->dates)
				{
					$vl_per_day = $value6->total_slvl;
					$objWorkSheet->SetCellValue('O'.$row,$vl_per_day);
				}
			}

			//ABSENCES
			foreach($data['ab'] as $key => $value7)
			{
				if($value7->slvl_employee_number == $value->employee_number && $value7->ab_date == $value->dates && $value7->slvl_type == 'AB')
				{
					$ab_per_day = $value7->total_slvl;
					$reg_ab = $ab_per_day;
					$hr_diff = intval($reg_ab/60);
					$min_diff = intval($reg_ab%60);
					$min_diff1 = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('P'.$row,$hr_diff.".".$min_diff1);
				}
			}

			//REMARKS
			foreach($data['remarks'] as $key => $value8)
			{
				if($value8->date == $value->dates && $value8->remarks_employee_number == $value->employee_number)
    		{
    			$remarkss = $value8->type_name;
    			$objWorkSheet->SetCellValue('Q'.$row,$remarkss);
    		}
			}

			$row++;
		}	
    $objWorkSheet->setTitle("BAMBANG");


    $i++;
    }

    /////////////////////////////////////////////////////// BANGUED ///////////////////////////////////////////////

    $i=0;
    while ($i < 1) {

      // Add new sheet
    $objWorkSheet = $objPHPExcel->createSheet($i); //Setting index when creating

	
		foreach(range('A','Q') as $columnID) {
    $objWorkSheet->getColumnDimension($columnID)
        ->setAutoSize(true);
		} 
		
		$objWorkSheet->SetCellValue('A1','EMPLOYEE NUMBER'); 
		$objWorkSheet->SetCellValue('B1','EMPLOYEE NAME');
		$objWorkSheet->SetCellValue('C1','DATES');
		$objWorkSheet->SetCellValue('D1','TIME IN');
		$objWorkSheet->SetCellValue('E1','TIME OUT');
		$objWorkSheet->SetCellValue('F1','Daily Hours');
		$objWorkSheet->SetCellValue('G1','TARDINESS');
		$objWorkSheet->SetCellValue('H1','UNDERTIME');
		$objWorkSheet->SetCellValue('I1','REGULAR OT');
		$objWorkSheet->SetCellValue('J1','LEGAL HOLIDAY OT');
		$objWorkSheet->SetCellValue('K1','SPECIAL HOLIDAY OT');
		$objWorkSheet->SetCellValue('L1','RESTDAY OT');
		$objWorkSheet->SetCellValue('M1','NSD');
		$objWorkSheet->SetCellValue('N1','SICK LEAVE');
		$objWorkSheet->SetCellValue('O1','VACATION LEAVE');
		$objWorkSheet->SetCellValue('P1','ABSENCES');
		$objWorkSheet->SetCellValue('Q1','REMARKS');

		$objWorkSheet->SetCellValue('A2',' ');
		$objWorkSheet->SetCellValue('B2',' ');
		$objWorkSheet->SetCellValue('C2',' ');
		$objWorkSheet->SetCellValue('D2',' ');
		$objWorkSheet->SetCellValue('E2',' ');
		$objWorkSheet->SetCellValue('F2','HOURS.MINS');
		$objWorkSheet->SetCellValue('G2','HOURS.MINS');
		$objWorkSheet->SetCellValue('H2','HOURS.MINS');
		$objWorkSheet->SetCellValue('I2','HOURS.MINS');
		$objWorkSheet->SetCellValue('J2','HOURS.MINS');
		$objWorkSheet->SetCellValue('K2','HOURS.MINS');
		$objWorkSheet->SetCellValue('L2','HOURS.MINS');
		$objWorkSheet->SetCellValue('M2','HOURS.MINS');
		$objWorkSheet->SetCellValue('N2','DAYS');
		$objWorkSheet->SetCellValue('O2','DAYS');
		$objWorkSheet->SetCellValue('P2','HOURS.MINS');
		$objWorkSheet->SetCellValue('Q2',' ');

		$row = 3;

		foreach($data['employeesBangued'] as $key => $value)
		{
			$in_office	= $data['schedules']->daily_in; 
			$out_office   = $data['schedules']->daily_out;
			$friday_out = $data['schedules']->daily_friday_out;
			$night_diff = '22:00';
			$in_daily = $value->intime;
			$out_daily = $value->outtime;
			$week_date = date('w', strtotime($value->dates)); // Convert in days . friday (5)

			// EXPLODE DATE IN TIME IN / TIME OUT
			$explode_in_date_daily = explode(" ", $in_daily);
			$explode_out_date_daily = explode(" ", $out_daily);
			$date_date_in = $explode_in_date_daily[0];
			$date_date_out = $explode_out_date_daily[0];
			$date_in = $explode_in_date_daily[1];
			$date_out = $explode_out_date_daily[1];

			//NIGHT DIFF
			$explode_night_diff = explode(":", $night_diff);
			$night_diff_hr = $explode_night_diff[0]; 
			$night_diff_min = $explode_night_diff[1]; 
			$total_night_diff = intval($night_diff_hr*60) + $night_diff_min; // total night diff

			// EXPLODE IN AND OUT 
			$explode_in_office = explode(":", $in_office);
			$explode_out_office = explode(":", $out_office);
			$explode_friday_out_office = explode(":", $friday_out);
			$explode_in_daily = explode(":", $date_in);
			$explode_out_daily = explode(":", $date_out);
			$time_in_hr_daily = $explode_in_daily[0];
			$time_in_min_daily = $explode_in_daily[1];
			$time_out_hr_daily = $explode_out_daily[0];
			$time_out_min_daily = $explode_out_daily[1];
			$time_in_hr = $explode_in_office[0];
			$time_in_min = $explode_in_office[1];
			$time_out_hr = $explode_out_office[0];
			$time_out_min = $explode_out_office[1];
			$time_friday_out_hr = $explode_friday_out_office[0];
			$time_friday_out_min = $explode_friday_out_office[1];


			// Convert IN AND OUT
			$total_in_min = intval($time_in_hr*60) + $time_in_min; // DEFAULT IN
			$total_in_min_grace = intval($time_in_hr*60) + $time_in_min + 15; // DEFAULT IN WITH GRACE PERIOD!
			$total_out_min = intval($time_out_hr*60) + $time_out_min; // DEFAULT OUT
			$total_friday_out_min = intval($time_friday_out_hr*60) + $time_friday_out_min; // DEFAULT OUT IN FRIDAY
			$total_in_daily = intval($time_in_hr_daily*60) + $time_in_min_daily; // EMPLOYEE IN
			$total_out_daily = intval($time_out_hr_daily*60) + $time_out_min_daily; // EMPLOYEE OUT

			//COMPUTATION IN OFFICE IN AND OUT
			$total_min_diff = intval($total_out_min - $total_in_min);
			$hr_diff = intval($total_min_diff/60);
			$min_diff = intval($total_min_diff%60);
			

			// IN AND OUT OF EMPLOYEE
			$in = strtotime($value->intime);
			$out   = strtotime($value->outtime);
			$diff  = $out - $in;

			//CONVERT OF IN AND OUT
			$hours = floor($diff / (60 * 60));
			$minutes = $diff - $hours * (60 * 60); 
			$total_minutes = floor( $minutes / 60 );
			
			// COMPUTATION OF IN AND OUT
			$total_number_of_hours = $hours.".".$total_minutes; //
			$total_office_hours = $hr_diff.".".$min_diff; // 9:30 Fixed
			$number_hr_daily = $total_number_of_hours; // TOTAL HOURS DAILY!
			$start_halfday = 660; 
			$end_halfday = 750;

			$objWorkSheet->SetCellValue('A'.$row,$value->employee_number);
			$objWorkSheet->SetCellValue('B'.$row,$value->name);
			$objWorkSheet->SetCellValue('C'.$row,$value->dates);
			$objWorkSheet->SetCellValue('D'.$row,$date_in);
			$objWorkSheet->SetCellValue('E'.$row,$date_out);

			//DAILY HOURS
			if($week_date >= 1 && $week_date <= 4)
			{
				if($date_date_in == $date_date_out && $total_in_daily == 0 && $total_out_daily == 0)
				{
					$objWorkSheet->SetCellValue('F'.$row," ");
				}
				elseif($total_in_min_grace >= $total_in_daily && $total_out_daily >= $total_out_min)
				{
					$total_min_diff = intval($total_out_min - $total_in_min - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}			
				elseif($total_in_daily > $total_in_min_grace && $total_out_daily < $total_out_min)
				{
					$total_min_diff = intval($total_out_daily - $total_in_daily - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($total_in_daily > $total_in_min_grace )
				{
					$total_min_diff = intval($total_out_min - $total_in_daily - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($total_out_daily < $total_out_min)
				{
					$total_min_diff = intval($total_out_daily - $total_in_min - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($total_in_daily >= $total_in_min_grace)
				{
					$total_min_diff = intval($total_out_min - $total_in_daily - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$min_diff."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($total_in_daily <= $total_in_min_grace)
				{
					$total_min_diff = intval($total_out_min - $total_in_min - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
			}
			elseif($week_date == 5)
			{
				if($date_date_in == $date_date_out && $total_in_daily == 0 && $total_out_daily == 0)
				{
					$objWorkSheet->SetCellValue('F'.$row," ");
				}
				elseif($date_date_in == $date_date_out && $total_in_min_grace >= $total_in_daily && $total_out_daily >= $total_friday_out_min)
				{
					$total_min_diff = intval($total_friday_out_min - $total_in_min );
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");

				}	
				elseif($date_date_in == $date_date_out && $total_in_daily > $total_in_min_grace && $total_out_daily < $total_friday_out_min)
				{
					$total_min_diff = intval($total_out_daily - $total_in_daily);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($date_date_in == $date_date_out && $total_in_daily > $total_in_min_grace )
				{
					$total_min_diff = intval($total_friday_out_min - $total_in_daily);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($date_date_in == $date_date_out && $total_out_daily < $total_friday_out_min)
				{
					$total_min_diff = intval($total_out_daily - $total_in_min);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($date_date_in != $date_date_out && $total_in_daily >= $total_in_min_grace)
				{
					$total_min_diff = intval($total_friday_out_min - $total_in_daily);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($date_date_in != $date_date_out || $total_in_daily <= $total_in_min_grace)
				{
					$total_min_diff = intval($total_friday_out_min - $total_in_min);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
			}
			elseif($week_date == 6)
			{
				$objWorkSheet->SetCellValue('F'.$row,"SATURDAY");
			}
			elseif($week_date == 0)
			{
				$objWorkSheet->SetCellValue('F'.$row,"SUNDAY");
			}
			else
			{
				$objWorkSheet->SetCellValue('F'.$row," ");
			}

			//TARDINESS
			if($week_date == 1 || $week_date == 2 || $week_date == 3 || $week_date == 4)
			{
				if($start_halfday <= $total_in_daily)
				{
					$objWorkSheet->SetCellValue('G'.$row," ");
				}
				else
				{
					if($total_in_daily > $total_in_min_grace)
					{
						$late_hr = intval($total_in_daily - $total_in_min);
						$hr_diff = intval($late_hr/60);
						$min_diff = intval($late_hr%60);
						//echo $hr_diff.".".$min_diff."";
						$minn = sprintf("%02d", $min_diff);
						$number_of_late = $hr_diff.".".$minn;
						$objWorkSheet->SetCellValue('G'.$row,$hr_diff.".".$minn."");
						$hr_lte = $late_hr;
					}
					else
					{
						$objWorkSheet->SetCellValue('G'.$row," ");
					}
				}
			}	
			elseif($week_date == 5)
			{
				if($start_halfday <= $total_in_daily)
				{
					$objWorkSheet->SetCellValue('G'.$row," ");
				}
				else
				{
					if($total_in_daily > $total_in_min_grace)
					{
						$late_hr = intval($total_in_daily - $total_in_min);
						$hr_diff = intval($late_hr/60);
						$min_diff = intval($late_hr%60);
						$objWorkSheet->SetCellValue('G'.$row,$hr_diff.".".$min_diff."");
						$minn = sprintf("%02d", $min_diff);
					}
					else
					{
						$objWorkSheet->SetCellValue('G'.$row," ");
					}
				}
				
			}
			else
			{
				$objWorkSheet->SetCellValue('G'.$row," ");
			}
			
			//UNDERTIME
			$halfday_in = 810;
			if($week_date == 1 || $week_date == 2 || $week_date == 3 || $week_date == 4)
			{
				if($date_date_in == $date_date_out && $total_out_daily < $halfday_in)
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
				elseif($total_out_min <= $total_out_daily)
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
				elseif($total_out_daily > $halfday_in)
				{
					$undertime_hr = intval($total_out_min - $total_out_daily);
					$hr_diff = intval($undertime_hr/60);
					$min_diff = intval($undertime_hr%60);
					$minn = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('H'.$row,$hr_diff.".".$minn);
				}
				else
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
			}
			elseif($week_date == 5)
			{
				if($date_date_in == $date_date_out && $total_out_daily < $halfday_in)
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
				elseif($total_friday_out_min <= $total_out_daily)
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
				elseif($date_date_in == $date_date_out && $halfday_in < $total_out_daily)
				{
					$undertime_hr = intval($total_friday_out_min - $total_out_daily);
					$hr_diff = intval($undertime_hr/60);
					$min_diff = intval($undertime_hr%60);
					$minn = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('H'.$row,$hr_diff.".".$minn);
				}
				else
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
			}
			else
			{
				$objWorkSheet->SetCellValue('H'.$row," ");
			}

			//REGULAR OT
			foreach($data['rots'] as $key => $value1)
			{
				if($value1->ot_employee_number == $value->employee_number && $value1->date_ot == $value->dates)
				{
					$reg_ot = $value1->total_ot;
					$hr_diff = intval($reg_ot/60);
					$min_diff = intval($reg_ot%60);
					$min_diff1 = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('I'.$row,$hr_diff.".".$min_diff1);
				}
			}	

			//LEGAL HOLIDAY OT
			foreach($data['lots'] as $key => $value2)
			{
				if($value2->legal_ot_employee_number == $value->employee_number && $value2->date_ot == $value->dates)
				{
					$reg_ot = $value2->total_ot;
					$hr_diff = intval($reg_ot/60);
					$min_diff = intval($reg_ot%60);
					$min_diff1 = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('J'.$row,$hr_diff.".".$min_diff1);
				}
			}

			//SPECIAL HOLIDAY OT
			foreach($data['shots'] as $key => $value3)
			{
				if($value3->special_ot_employee_number == $value->employee_number && $value3->date_ot == $value->dates)
				{
					$reg_ot = $value3->total_ot;
					$hr_diff = intval($reg_ot/60);
					$min_diff = intval($reg_ot%60);
					$min_diff1 = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('K'.$row,$hr_diff.".".$min_diff1);
				}
			}	

			//REST DAY OT
			foreach($data['rdots'] as $key => $value4)
			{
				if($value4->restday_ot_employee_number == $value->employee_number && $value4->date_ot == $value->dates)
				{
					$reg_ot = $value4->total_ot;
					$hr_diff = intval($reg_ot/60);
					$min_diff = intval($reg_ot%60);
					$min_diff1 = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('L'.$row,$hr_diff.".".$min_diff1);
				}
			}	

			//NIGHT DIFF
			$set_night_diff_morning = '6:00';
			$explode_night_diff_morning = explode(':', $set_night_diff_morning);
			$night_diff_morning = intval($explode_night_diff_morning[0]*60);
			$compute_night_diff_morning =$night_diff_morning - $total_in_daily;
			if($total_in_daily < $night_diff_morning)
			{
				$compute_night_diff_morning;
				$hr_diff = intval($compute_night_diff_morning/60);
				$min_diff = intval($compute_night_diff_morning%60);
				if($total_in_daily == 0 && $total_out_daily == 0)
				{
					$nd = ' ';
					//echo 0;
					$objWorkSheet->SetCellValue('M'.$row, $nd);
				}
				elseif($min_diff > 30 || $min_diff == 0)
				{
					$nd = $hr_diff."."."30";
					//echo $hr_diff."."."30";
					$objWorkSheet->SetCellValue('M'.$row, $nd);
				}
				elseif($min_diff < 30)
				{
					$nd = $hr_diff;
					$objWorkSheet->SetCellValue('M'.$row, $nd);
					//echo $hr_diff;
				}
			}
			else
			{
				$nd = ' ';
				$objWorkSheet->SetCellValue('M'.$row, $nd);
				//echo 0;
			}

			//SICK LEAVE
			foreach($data['sl'] as $key => $value5)
			{
				if($value5->slvl_employee_number == $value->employee_number && $value5->sl_date == $value->dates)
				{
					$sl_per_day = $value5->total_slvl;
					$objWorkSheet->SetCellValue('N'.$row,$sl_per_day);
				}
			}

			//VACATION LEAVE
			foreach($data['vl'] as $key => $value6)
			{
				if($value6->slvl_employee_number == $value->employee_number && $value6->vl_date == $value->dates)
				{
					$vl_per_day = $value6->total_slvl;
					$objWorkSheet->SetCellValue('O'.$row,$vl_per_day);
				}
			}

			//ABSENCES
			foreach($data['ab'] as $key => $value7)
			{
				if($value7->slvl_employee_number == $value->employee_number && $value7->ab_date == $value->dates && $value7->slvl_type == 'AB')
				{
					$ab_per_day = $value7->total_slvl;
					$reg_ab = $ab_per_day;
					$hr_diff = intval($reg_ab/60);
					$min_diff = intval($reg_ab%60);
					$min_diff1 = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('P'.$row,$hr_diff.".".$min_diff1);
				}
			}

			//REMARKS
			foreach($data['remarks'] as $key => $value8)
			{
				if($value8->date == $value->dates && $value8->remarks_employee_number == $value->employee_number)
    		{
    			$remarkss = $value8->type_name;
    			$objWorkSheet->SetCellValue('Q'.$row,$remarkss);
    		}
			}

			$row++;
		}	
    $objWorkSheet->setTitle("BANGUED");


    $i++;
    }

    ///////////////////////////////////////////////////////////// BATANGAS ////////////////////////////////////////////////

    $i=0;
    while ($i < 1) {

      // Add new sheet
    $objWorkSheet = $objPHPExcel->createSheet($i); //Setting index when creating

	
		foreach(range('A','Q') as $columnID) {
    $objWorkSheet->getColumnDimension($columnID)
        ->setAutoSize(true);
		} 
		
		$objWorkSheet->SetCellValue('A1','EMPLOYEE NUMBER'); 
		$objWorkSheet->SetCellValue('B1','EMPLOYEE NAME');
		$objWorkSheet->SetCellValue('C1','DATES');
		$objWorkSheet->SetCellValue('D1','TIME IN');
		$objWorkSheet->SetCellValue('E1','TIME OUT');
		$objWorkSheet->SetCellValue('F1','Daily Hours');
		$objWorkSheet->SetCellValue('G1','TARDINESS');
		$objWorkSheet->SetCellValue('H1','UNDERTIME');
		$objWorkSheet->SetCellValue('I1','REGULAR OT');
		$objWorkSheet->SetCellValue('J1','LEGAL HOLIDAY OT');
		$objWorkSheet->SetCellValue('K1','SPECIAL HOLIDAY OT');
		$objWorkSheet->SetCellValue('L1','RESTDAY OT');
		$objWorkSheet->SetCellValue('M1','NSD');
		$objWorkSheet->SetCellValue('N1','SICK LEAVE');
		$objWorkSheet->SetCellValue('O1','VACATION LEAVE');
		$objWorkSheet->SetCellValue('P1','ABSENCES');
		$objWorkSheet->SetCellValue('Q1','REMARKS');

		$objWorkSheet->SetCellValue('A2',' ');
		$objWorkSheet->SetCellValue('B2',' ');
		$objWorkSheet->SetCellValue('C2',' ');
		$objWorkSheet->SetCellValue('D2',' ');
		$objWorkSheet->SetCellValue('E2',' ');
		$objWorkSheet->SetCellValue('F2','HOURS.MINS');
		$objWorkSheet->SetCellValue('G2','HOURS.MINS');
		$objWorkSheet->SetCellValue('H2','HOURS.MINS');
		$objWorkSheet->SetCellValue('I2','HOURS.MINS');
		$objWorkSheet->SetCellValue('J2','HOURS.MINS');
		$objWorkSheet->SetCellValue('K2','HOURS.MINS');
		$objWorkSheet->SetCellValue('L2','HOURS.MINS');
		$objWorkSheet->SetCellValue('M2','HOURS.MINS');
		$objWorkSheet->SetCellValue('N2','DAYS');
		$objWorkSheet->SetCellValue('O2','DAYS');
		$objWorkSheet->SetCellValue('P2','HOURS.MINS');
		$objWorkSheet->SetCellValue('Q2',' ');

		$row = 3;

		foreach($data['employeesBatangas'] as $key => $value)
		{
			$in_office	= $data['schedules']->daily_in; 
			$out_office   = $data['schedules']->daily_out;
			$friday_out = $data['schedules']->daily_friday_out;
			$night_diff = '22:00';
			$in_daily = $value->intime;
			$out_daily = $value->outtime;
			$week_date = date('w', strtotime($value->dates)); // Convert in days . friday (5)

			// EXPLODE DATE IN TIME IN / TIME OUT
			$explode_in_date_daily = explode(" ", $in_daily);
			$explode_out_date_daily = explode(" ", $out_daily);
			$date_date_in = $explode_in_date_daily[0];
			$date_date_out = $explode_out_date_daily[0];
			$date_in = $explode_in_date_daily[1];
			$date_out = $explode_out_date_daily[1];

			//NIGHT DIFF
			$explode_night_diff = explode(":", $night_diff);
			$night_diff_hr = $explode_night_diff[0]; 
			$night_diff_min = $explode_night_diff[1]; 
			$total_night_diff = intval($night_diff_hr*60) + $night_diff_min; // total night diff

			// EXPLODE IN AND OUT 
			$explode_in_office = explode(":", $in_office);
			$explode_out_office = explode(":", $out_office);
			$explode_friday_out_office = explode(":", $friday_out);
			$explode_in_daily = explode(":", $date_in);
			$explode_out_daily = explode(":", $date_out);
			$time_in_hr_daily = $explode_in_daily[0];
			$time_in_min_daily = $explode_in_daily[1];
			$time_out_hr_daily = $explode_out_daily[0];
			$time_out_min_daily = $explode_out_daily[1];
			$time_in_hr = $explode_in_office[0];
			$time_in_min = $explode_in_office[1];
			$time_out_hr = $explode_out_office[0];
			$time_out_min = $explode_out_office[1];
			$time_friday_out_hr = $explode_friday_out_office[0];
			$time_friday_out_min = $explode_friday_out_office[1];


			// Convert IN AND OUT
			$total_in_min = intval($time_in_hr*60) + $time_in_min; // DEFAULT IN
			$total_in_min_grace = intval($time_in_hr*60) + $time_in_min + 15; // DEFAULT IN WITH GRACE PERIOD!
			$total_out_min = intval($time_out_hr*60) + $time_out_min; // DEFAULT OUT
			$total_friday_out_min = intval($time_friday_out_hr*60) + $time_friday_out_min; // DEFAULT OUT IN FRIDAY
			$total_in_daily = intval($time_in_hr_daily*60) + $time_in_min_daily; // EMPLOYEE IN
			$total_out_daily = intval($time_out_hr_daily*60) + $time_out_min_daily; // EMPLOYEE OUT

			//COMPUTATION IN OFFICE IN AND OUT
			$total_min_diff = intval($total_out_min - $total_in_min);
			$hr_diff = intval($total_min_diff/60);
			$min_diff = intval($total_min_diff%60);
			

			// IN AND OUT OF EMPLOYEE
			$in = strtotime($value->intime);
			$out   = strtotime($value->outtime);
			$diff  = $out - $in;

			//CONVERT OF IN AND OUT
			$hours = floor($diff / (60 * 60));
			$minutes = $diff - $hours * (60 * 60); 
			$total_minutes = floor( $minutes / 60 );
			
			// COMPUTATION OF IN AND OUT
			$total_number_of_hours = $hours.".".$total_minutes; //
			$total_office_hours = $hr_diff.".".$min_diff; // 9:30 Fixed
			$number_hr_daily = $total_number_of_hours; // TOTAL HOURS DAILY!
			$start_halfday = 660; 
			$end_halfday = 750;

			$objWorkSheet->SetCellValue('A'.$row,$value->employee_number);
			$objWorkSheet->SetCellValue('B'.$row,$value->name);
			$objWorkSheet->SetCellValue('C'.$row,$value->dates);
			$objWorkSheet->SetCellValue('D'.$row,$date_in);
			$objWorkSheet->SetCellValue('E'.$row,$date_out);

			//DAILY HOURS
			if($week_date >= 1 && $week_date <= 4)
			{
				if($date_date_in == $date_date_out && $total_in_daily == 0 && $total_out_daily == 0)
				{
					$objWorkSheet->SetCellValue('F'.$row," ");
				}
				elseif($total_in_min_grace >= $total_in_daily && $total_out_daily >= $total_out_min)
				{
					$total_min_diff = intval($total_out_min - $total_in_min - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}			
				elseif($total_in_daily > $total_in_min_grace && $total_out_daily < $total_out_min)
				{
					$total_min_diff = intval($total_out_daily - $total_in_daily - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($total_in_daily > $total_in_min_grace )
				{
					$total_min_diff = intval($total_out_min - $total_in_daily - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($total_out_daily < $total_out_min)
				{
					$total_min_diff = intval($total_out_daily - $total_in_min - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($total_in_daily >= $total_in_min_grace)
				{
					$total_min_diff = intval($total_out_min - $total_in_daily - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$min_diff."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($total_in_daily <= $total_in_min_grace)
				{
					$total_min_diff = intval($total_out_min - $total_in_min - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
			}
			elseif($week_date == 5)
			{
				if($date_date_in == $date_date_out && $total_in_daily == 0 && $total_out_daily == 0)
				{
					$objWorkSheet->SetCellValue('F'.$row," ");
				}
				elseif($date_date_in == $date_date_out && $total_in_min_grace >= $total_in_daily && $total_out_daily >= $total_friday_out_min)
				{
					$total_min_diff = intval($total_friday_out_min - $total_in_min );
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");

				}	
				elseif($date_date_in == $date_date_out && $total_in_daily > $total_in_min_grace && $total_out_daily < $total_friday_out_min)
				{
					$total_min_diff = intval($total_out_daily - $total_in_daily);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($date_date_in == $date_date_out && $total_in_daily > $total_in_min_grace )
				{
					$total_min_diff = intval($total_friday_out_min - $total_in_daily);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($date_date_in == $date_date_out && $total_out_daily < $total_friday_out_min)
				{
					$total_min_diff = intval($total_out_daily - $total_in_min);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($date_date_in != $date_date_out && $total_in_daily >= $total_in_min_grace)
				{
					$total_min_diff = intval($total_friday_out_min - $total_in_daily);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($date_date_in != $date_date_out || $total_in_daily <= $total_in_min_grace)
				{
					$total_min_diff = intval($total_friday_out_min - $total_in_min);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
			}
			elseif($week_date == 6)
			{
				$objWorkSheet->SetCellValue('F'.$row,"SATURDAY");
			}
			elseif($week_date == 0)
			{
				$objWorkSheet->SetCellValue('F'.$row,"SUNDAY");
			}
			else
			{
				$objWorkSheet->SetCellValue('F'.$row," ");
			}

			//TARDINESS
			if($week_date == 1 || $week_date == 2 || $week_date == 3 || $week_date == 4)
			{
				if($start_halfday <= $total_in_daily)
				{
					$objWorkSheet->SetCellValue('G'.$row," ");
				}
				else
				{
					if($total_in_daily > $total_in_min_grace)
					{
						$late_hr = intval($total_in_daily - $total_in_min);
						$hr_diff = intval($late_hr/60);
						$min_diff = intval($late_hr%60);
						//echo $hr_diff.".".$min_diff."";
						$minn = sprintf("%02d", $min_diff);
						$number_of_late = $hr_diff.".".$minn;
						$objWorkSheet->SetCellValue('G'.$row,$hr_diff.".".$minn."");
						$hr_lte = $late_hr;
					}
					else
					{
						$objWorkSheet->SetCellValue('G'.$row," ");
					}
				}
			}	
			elseif($week_date == 5)
			{
				if($start_halfday <= $total_in_daily)
				{
					$objWorkSheet->SetCellValue('G'.$row," ");
				}
				else
				{
					if($total_in_daily > $total_in_min_grace)
					{
						$late_hr = intval($total_in_daily - $total_in_min);
						$hr_diff = intval($late_hr/60);
						$min_diff = intval($late_hr%60);
						$objWorkSheet->SetCellValue('G'.$row,$hr_diff.".".$min_diff."");
						$minn = sprintf("%02d", $min_diff);
					}
					else
					{
						$objWorkSheet->SetCellValue('G'.$row," ");
					}
				}
				
			}
			else
			{
				$objWorkSheet->SetCellValue('G'.$row," ");
			}
			
			//UNDERTIME
			$halfday_in = 810;
			if($week_date == 1 || $week_date == 2 || $week_date == 3 || $week_date == 4)
			{
				if($date_date_in == $date_date_out && $total_out_daily < $halfday_in)
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
				elseif($total_out_min <= $total_out_daily)
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
				elseif($total_out_daily > $halfday_in)
				{
					$undertime_hr = intval($total_out_min - $total_out_daily);
					$hr_diff = intval($undertime_hr/60);
					$min_diff = intval($undertime_hr%60);
					$minn = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('H'.$row,$hr_diff.".".$minn);
				}
				else
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
			}
			elseif($week_date == 5)
			{
				if($date_date_in == $date_date_out && $total_out_daily < $halfday_in)
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
				elseif($total_friday_out_min <= $total_out_daily)
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
				elseif($date_date_in == $date_date_out && $halfday_in < $total_out_daily)
				{
					$undertime_hr = intval($total_friday_out_min - $total_out_daily);
					$hr_diff = intval($undertime_hr/60);
					$min_diff = intval($undertime_hr%60);
					$minn = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('H'.$row,$hr_diff.".".$minn);
				}
				else
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
			}
			else
			{
				$objWorkSheet->SetCellValue('H'.$row," ");
			}

			//REGULAR OT
			foreach($data['rots'] as $key => $value1)
			{
				if($value1->ot_employee_number == $value->employee_number && $value1->date_ot == $value->dates)
				{
					$reg_ot = $value1->total_ot;
					$hr_diff = intval($reg_ot/60);
					$min_diff = intval($reg_ot%60);
					$min_diff1 = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('I'.$row,$hr_diff.".".$min_diff1);
				}
			}	

			//LEGAL HOLIDAY OT
			foreach($data['lots'] as $key => $value2)
			{
				if($value2->legal_ot_employee_number == $value->employee_number && $value2->date_ot == $value->dates)
				{
					$reg_ot = $value2->total_ot;
					$hr_diff = intval($reg_ot/60);
					$min_diff = intval($reg_ot%60);
					$min_diff1 = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('J'.$row,$hr_diff.".".$min_diff1);
				}
			}

			//SPECIAL HOLIDAY OT
			foreach($data['shots'] as $key => $value3)
			{
				if($value3->special_ot_employee_number == $value->employee_number && $value3->date_ot == $value->dates)
				{
					$reg_ot = $value3->total_ot;
					$hr_diff = intval($reg_ot/60);
					$min_diff = intval($reg_ot%60);
					$min_diff1 = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('K'.$row,$hr_diff.".".$min_diff1);
				}
			}	

			//REST DAY OT
			foreach($data['rdots'] as $key => $value4)
			{
				if($value4->restday_ot_employee_number == $value->employee_number && $value4->date_ot == $value->dates)
				{
					$reg_ot = $value4->total_ot;
					$hr_diff = intval($reg_ot/60);
					$min_diff = intval($reg_ot%60);
					$min_diff1 = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('L'.$row,$hr_diff.".".$min_diff1);
				}
			}	

			//NIGHT DIFF
			$set_night_diff_morning = '6:00';
			$explode_night_diff_morning = explode(':', $set_night_diff_morning);
			$night_diff_morning = intval($explode_night_diff_morning[0]*60);
			$compute_night_diff_morning =$night_diff_morning - $total_in_daily;
			if($total_in_daily < $night_diff_morning)
			{
				$compute_night_diff_morning;
				$hr_diff = intval($compute_night_diff_morning/60);
				$min_diff = intval($compute_night_diff_morning%60);
				if($total_in_daily == 0 && $total_out_daily == 0)
				{
					$nd = ' ';
					//echo 0;
					$objWorkSheet->SetCellValue('M'.$row, $nd);
				}
				elseif($min_diff > 30 || $min_diff == 0)
				{
					$nd = $hr_diff."."."30";
					//echo $hr_diff."."."30";
					$objWorkSheet->SetCellValue('M'.$row, $nd);
				}
				elseif($min_diff < 30)
				{
					$nd = $hr_diff;
					$objWorkSheet->SetCellValue('M'.$row, $nd);
					//echo $hr_diff;
				}
			}
			else
			{
				$nd = ' ';
				$objWorkSheet->SetCellValue('M'.$row, $nd);
				//echo 0;
			}

			//SICK LEAVE
			foreach($data['sl'] as $key => $value5)
			{
				if($value5->slvl_employee_number == $value->employee_number && $value5->sl_date == $value->dates)
				{
					$sl_per_day = $value5->total_slvl;
					$objWorkSheet->SetCellValue('N'.$row,$sl_per_day);
				}
			}

			//VACATION LEAVE
			foreach($data['vl'] as $key => $value6)
			{
				if($value6->slvl_employee_number == $value->employee_number && $value6->vl_date == $value->dates)
				{
					$vl_per_day = $value6->total_slvl;
					$objWorkSheet->SetCellValue('O'.$row,$vl_per_day);
				}
			}

			//ABSENCES
			foreach($data['ab'] as $key => $value7)
			{
				if($value7->slvl_employee_number == $value->employee_number && $value7->ab_date == $value->dates && $value7->slvl_type == 'AB')
				{
					$ab_per_day = $value7->total_slvl;
					$reg_ab = $ab_per_day;
					$hr_diff = intval($reg_ab/60);
					$min_diff = intval($reg_ab%60);
					$min_diff1 = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('P'.$row,$hr_diff.".".$min_diff1);
				}
			}

			//REMARKS
			foreach($data['remarks'] as $key => $value8)
			{
				if($value8->date == $value->dates && $value8->remarks_employee_number == $value->employee_number)
    		{
    			$remarkss = $value8->type_name;
    			$objWorkSheet->SetCellValue('Q'.$row,$remarkss);
    		}
			}

			$row++;
		}	
    $objWorkSheet->setTitle("BATANGAS");


    $i++;
    }

    /////////////////////////////////////////////////////////////// BONTOC //////////////////////////////////////////////

    $i=0;
    while ($i < 1) {

      // Add new sheet
    $objWorkSheet = $objPHPExcel->createSheet($i); //Setting index when creating

	
		foreach(range('A','Q') as $columnID) {
    $objWorkSheet->getColumnDimension($columnID)
        ->setAutoSize(true);
		} 
		
		$objWorkSheet->SetCellValue('A1','EMPLOYEE NUMBER'); 
		$objWorkSheet->SetCellValue('B1','EMPLOYEE NAME');
		$objWorkSheet->SetCellValue('C1','DATES');
		$objWorkSheet->SetCellValue('D1','TIME IN');
		$objWorkSheet->SetCellValue('E1','TIME OUT');
		$objWorkSheet->SetCellValue('F1','Daily Hours');
		$objWorkSheet->SetCellValue('G1','TARDINESS');
		$objWorkSheet->SetCellValue('H1','UNDERTIME');
		$objWorkSheet->SetCellValue('I1','REGULAR OT');
		$objWorkSheet->SetCellValue('J1','LEGAL HOLIDAY OT');
		$objWorkSheet->SetCellValue('K1','SPECIAL HOLIDAY OT');
		$objWorkSheet->SetCellValue('L1','RESTDAY OT');
		$objWorkSheet->SetCellValue('M1','NSD');
		$objWorkSheet->SetCellValue('N1','SICK LEAVE');
		$objWorkSheet->SetCellValue('O1','VACATION LEAVE');
		$objWorkSheet->SetCellValue('P1','ABSENCES');
		$objWorkSheet->SetCellValue('Q1','REMARKS');

		$objWorkSheet->SetCellValue('A2',' ');
		$objWorkSheet->SetCellValue('B2',' ');
		$objWorkSheet->SetCellValue('C2',' ');
		$objWorkSheet->SetCellValue('D2',' ');
		$objWorkSheet->SetCellValue('E2',' ');
		$objWorkSheet->SetCellValue('F2','HOURS.MINS');
		$objWorkSheet->SetCellValue('G2','HOURS.MINS');
		$objWorkSheet->SetCellValue('H2','HOURS.MINS');
		$objWorkSheet->SetCellValue('I2','HOURS.MINS');
		$objWorkSheet->SetCellValue('J2','HOURS.MINS');
		$objWorkSheet->SetCellValue('K2','HOURS.MINS');
		$objWorkSheet->SetCellValue('L2','HOURS.MINS');
		$objWorkSheet->SetCellValue('M2','HOURS.MINS');
		$objWorkSheet->SetCellValue('N2','DAYS');
		$objWorkSheet->SetCellValue('O2','DAYS');
		$objWorkSheet->SetCellValue('P2','HOURS.MINS');
		$objWorkSheet->SetCellValue('Q2',' ');

		$row = 3;

		foreach($data['employeesBontoc'] as $key => $value)
		{
			$in_office	= $data['schedules']->daily_in; 
			$out_office   = $data['schedules']->daily_out;
			$friday_out = $data['schedules']->daily_friday_out;
			$night_diff = '22:00';
			$in_daily = $value->intime;
			$out_daily = $value->outtime;
			$week_date = date('w', strtotime($value->dates)); // Convert in days . friday (5)

			// EXPLODE DATE IN TIME IN / TIME OUT
			$explode_in_date_daily = explode(" ", $in_daily);
			$explode_out_date_daily = explode(" ", $out_daily);
			$date_date_in = $explode_in_date_daily[0];
			$date_date_out = $explode_out_date_daily[0];
			$date_in = $explode_in_date_daily[1];
			$date_out = $explode_out_date_daily[1];

			//NIGHT DIFF
			$explode_night_diff = explode(":", $night_diff);
			$night_diff_hr = $explode_night_diff[0]; 
			$night_diff_min = $explode_night_diff[1]; 
			$total_night_diff = intval($night_diff_hr*60) + $night_diff_min; // total night diff

			// EXPLODE IN AND OUT 
			$explode_in_office = explode(":", $in_office);
			$explode_out_office = explode(":", $out_office);
			$explode_friday_out_office = explode(":", $friday_out);
			$explode_in_daily = explode(":", $date_in);
			$explode_out_daily = explode(":", $date_out);
			$time_in_hr_daily = $explode_in_daily[0];
			$time_in_min_daily = $explode_in_daily[1];
			$time_out_hr_daily = $explode_out_daily[0];
			$time_out_min_daily = $explode_out_daily[1];
			$time_in_hr = $explode_in_office[0];
			$time_in_min = $explode_in_office[1];
			$time_out_hr = $explode_out_office[0];
			$time_out_min = $explode_out_office[1];
			$time_friday_out_hr = $explode_friday_out_office[0];
			$time_friday_out_min = $explode_friday_out_office[1];


			// Convert IN AND OUT
			$total_in_min = intval($time_in_hr*60) + $time_in_min; // DEFAULT IN
			$total_in_min_grace = intval($time_in_hr*60) + $time_in_min + 15; // DEFAULT IN WITH GRACE PERIOD!
			$total_out_min = intval($time_out_hr*60) + $time_out_min; // DEFAULT OUT
			$total_friday_out_min = intval($time_friday_out_hr*60) + $time_friday_out_min; // DEFAULT OUT IN FRIDAY
			$total_in_daily = intval($time_in_hr_daily*60) + $time_in_min_daily; // EMPLOYEE IN
			$total_out_daily = intval($time_out_hr_daily*60) + $time_out_min_daily; // EMPLOYEE OUT

			//COMPUTATION IN OFFICE IN AND OUT
			$total_min_diff = intval($total_out_min - $total_in_min);
			$hr_diff = intval($total_min_diff/60);
			$min_diff = intval($total_min_diff%60);
			

			// IN AND OUT OF EMPLOYEE
			$in = strtotime($value->intime);
			$out   = strtotime($value->outtime);
			$diff  = $out - $in;

			//CONVERT OF IN AND OUT
			$hours = floor($diff / (60 * 60));
			$minutes = $diff - $hours * (60 * 60); 
			$total_minutes = floor( $minutes / 60 );
			
			// COMPUTATION OF IN AND OUT
			$total_number_of_hours = $hours.".".$total_minutes; //
			$total_office_hours = $hr_diff.".".$min_diff; // 9:30 Fixed
			$number_hr_daily = $total_number_of_hours; // TOTAL HOURS DAILY!
			$start_halfday = 660; 
			$end_halfday = 750;

			$objWorkSheet->SetCellValue('A'.$row,$value->employee_number);
			$objWorkSheet->SetCellValue('B'.$row,$value->name);
			$objWorkSheet->SetCellValue('C'.$row,$value->dates);
			$objWorkSheet->SetCellValue('D'.$row,$date_in);
			$objWorkSheet->SetCellValue('E'.$row,$date_out);

			//DAILY HOURS
			if($week_date >= 1 && $week_date <= 4)
			{
				if($date_date_in == $date_date_out && $total_in_daily == 0 && $total_out_daily == 0)
				{
					$objWorkSheet->SetCellValue('F'.$row," ");
				}
				elseif($total_in_min_grace >= $total_in_daily && $total_out_daily >= $total_out_min)
				{
					$total_min_diff = intval($total_out_min - $total_in_min - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}			
				elseif($total_in_daily > $total_in_min_grace && $total_out_daily < $total_out_min)
				{
					$total_min_diff = intval($total_out_daily - $total_in_daily - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($total_in_daily > $total_in_min_grace )
				{
					$total_min_diff = intval($total_out_min - $total_in_daily - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($total_out_daily < $total_out_min)
				{
					$total_min_diff = intval($total_out_daily - $total_in_min - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($total_in_daily >= $total_in_min_grace)
				{
					$total_min_diff = intval($total_out_min - $total_in_daily - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$min_diff."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($total_in_daily <= $total_in_min_grace)
				{
					$total_min_diff = intval($total_out_min - $total_in_min - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
			}
			elseif($week_date == 5)
			{
				if($date_date_in == $date_date_out && $total_in_daily == 0 && $total_out_daily == 0)
				{
					$objWorkSheet->SetCellValue('F'.$row," ");
				}
				elseif($date_date_in == $date_date_out && $total_in_min_grace >= $total_in_daily && $total_out_daily >= $total_friday_out_min)
				{
					$total_min_diff = intval($total_friday_out_min - $total_in_min );
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");

				}	
				elseif($date_date_in == $date_date_out && $total_in_daily > $total_in_min_grace && $total_out_daily < $total_friday_out_min)
				{
					$total_min_diff = intval($total_out_daily - $total_in_daily);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($date_date_in == $date_date_out && $total_in_daily > $total_in_min_grace )
				{
					$total_min_diff = intval($total_friday_out_min - $total_in_daily);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($date_date_in == $date_date_out && $total_out_daily < $total_friday_out_min)
				{
					$total_min_diff = intval($total_out_daily - $total_in_min);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($date_date_in != $date_date_out && $total_in_daily >= $total_in_min_grace)
				{
					$total_min_diff = intval($total_friday_out_min - $total_in_daily);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($date_date_in != $date_date_out || $total_in_daily <= $total_in_min_grace)
				{
					$total_min_diff = intval($total_friday_out_min - $total_in_min);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
			}
			elseif($week_date == 6)
			{
				$objWorkSheet->SetCellValue('F'.$row,"SATURDAY");
			}
			elseif($week_date == 0)
			{
				$objWorkSheet->SetCellValue('F'.$row,"SUNDAY");
			}
			else
			{
				$objWorkSheet->SetCellValue('F'.$row," ");
			}

			//TARDINESS
			if($week_date == 1 || $week_date == 2 || $week_date == 3 || $week_date == 4)
			{
				if($start_halfday <= $total_in_daily)
				{
					$objWorkSheet->SetCellValue('G'.$row," ");
				}
				else
				{
					if($total_in_daily > $total_in_min_grace)
					{
						$late_hr = intval($total_in_daily - $total_in_min);
						$hr_diff = intval($late_hr/60);
						$min_diff = intval($late_hr%60);
						//echo $hr_diff.".".$min_diff."";
						$minn = sprintf("%02d", $min_diff);
						$number_of_late = $hr_diff.".".$minn;
						$objWorkSheet->SetCellValue('G'.$row,$hr_diff.".".$minn."");
						$hr_lte = $late_hr;
					}
					else
					{
						$objWorkSheet->SetCellValue('G'.$row," ");
					}
				}
			}	
			elseif($week_date == 5)
			{
				if($start_halfday <= $total_in_daily)
				{
					$objWorkSheet->SetCellValue('G'.$row," ");
				}
				else
				{
					if($total_in_daily > $total_in_min_grace)
					{
						$late_hr = intval($total_in_daily - $total_in_min);
						$hr_diff = intval($late_hr/60);
						$min_diff = intval($late_hr%60);
						$objWorkSheet->SetCellValue('G'.$row,$hr_diff.".".$min_diff."");
						$minn = sprintf("%02d", $min_diff);
					}
					else
					{
						$objWorkSheet->SetCellValue('G'.$row," ");
					}
				}
				
			}
			else
			{
				$objWorkSheet->SetCellValue('G'.$row," ");
			}
			
			//UNDERTIME
			$halfday_in = 810;
			if($week_date == 1 || $week_date == 2 || $week_date == 3 || $week_date == 4)
			{
				if($date_date_in == $date_date_out && $total_out_daily < $halfday_in)
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
				elseif($total_out_min <= $total_out_daily)
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
				elseif($total_out_daily > $halfday_in)
				{
					$undertime_hr = intval($total_out_min - $total_out_daily);
					$hr_diff = intval($undertime_hr/60);
					$min_diff = intval($undertime_hr%60);
					$minn = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('H'.$row,$hr_diff.".".$minn);
				}
				else
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
			}
			elseif($week_date == 5)
			{
				if($date_date_in == $date_date_out && $total_out_daily < $halfday_in)
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
				elseif($total_friday_out_min <= $total_out_daily)
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
				elseif($date_date_in == $date_date_out && $halfday_in < $total_out_daily)
				{
					$undertime_hr = intval($total_friday_out_min - $total_out_daily);
					$hr_diff = intval($undertime_hr/60);
					$min_diff = intval($undertime_hr%60);
					$minn = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('H'.$row,$hr_diff.".".$minn);
				}
				else
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
			}
			else
			{
				$objWorkSheet->SetCellValue('H'.$row," ");
			}

			//REGULAR OT
			foreach($data['rots'] as $key => $value1)
			{
				if($value1->ot_employee_number == $value->employee_number && $value1->date_ot == $value->dates)
				{
					$reg_ot = $value1->total_ot;
					$hr_diff = intval($reg_ot/60);
					$min_diff = intval($reg_ot%60);
					$min_diff1 = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('I'.$row,$hr_diff.".".$min_diff1);
				}
			}	

			//LEGAL HOLIDAY OT
			foreach($data['lots'] as $key => $value2)
			{
				if($value2->legal_ot_employee_number == $value->employee_number && $value2->date_ot == $value->dates)
				{
					$reg_ot = $value2->total_ot;
					$hr_diff = intval($reg_ot/60);
					$min_diff = intval($reg_ot%60);
					$min_diff1 = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('J'.$row,$hr_diff.".".$min_diff1);
				}
			}

			//SPECIAL HOLIDAY OT
			foreach($data['shots'] as $key => $value3)
			{
				if($value3->special_ot_employee_number == $value->employee_number && $value3->date_ot == $value->dates)
				{
					$reg_ot = $value3->total_ot;
					$hr_diff = intval($reg_ot/60);
					$min_diff = intval($reg_ot%60);
					$min_diff1 = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('K'.$row,$hr_diff.".".$min_diff1);
				}
			}	

			//REST DAY OT
			foreach($data['rdots'] as $key => $value4)
			{
				if($value4->restday_ot_employee_number == $value->employee_number && $value4->date_ot == $value->dates)
				{
					$reg_ot = $value4->total_ot;
					$hr_diff = intval($reg_ot/60);
					$min_diff = intval($reg_ot%60);
					$min_diff1 = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('L'.$row,$hr_diff.".".$min_diff1);
				}
			}	

			//NIGHT DIFF
			$set_night_diff_morning = '6:00';
			$explode_night_diff_morning = explode(':', $set_night_diff_morning);
			$night_diff_morning = intval($explode_night_diff_morning[0]*60);
			$compute_night_diff_morning =$night_diff_morning - $total_in_daily;
			if($total_in_daily < $night_diff_morning)
			{
				$compute_night_diff_morning;
				$hr_diff = intval($compute_night_diff_morning/60);
				$min_diff = intval($compute_night_diff_morning%60);
				if($total_in_daily == 0 && $total_out_daily == 0)
				{
					$nd = ' ';
					//echo 0;
					$objWorkSheet->SetCellValue('M'.$row, $nd);
				}
				elseif($min_diff > 30 || $min_diff == 0)
				{
					$nd = $hr_diff."."."30";
					//echo $hr_diff."."."30";
					$objWorkSheet->SetCellValue('M'.$row, $nd);
				}
				elseif($min_diff < 30)
				{
					$nd = $hr_diff;
					$objWorkSheet->SetCellValue('M'.$row, $nd);
					//echo $hr_diff;
				}
			}
			else
			{
				$nd = ' ';
				$objWorkSheet->SetCellValue('M'.$row, $nd);
				//echo 0;
			}

			//SICK LEAVE
			foreach($data['sl'] as $key => $value5)
			{
				if($value5->slvl_employee_number == $value->employee_number && $value5->sl_date == $value->dates)
				{
					$sl_per_day = $value5->total_slvl;
					$objWorkSheet->SetCellValue('N'.$row,$sl_per_day);
				}
			}

			//VACATION LEAVE
			foreach($data['vl'] as $key => $value6)
			{
				if($value6->slvl_employee_number == $value->employee_number && $value6->vl_date == $value->dates)
				{
					$vl_per_day = $value6->total_slvl;
					$objWorkSheet->SetCellValue('O'.$row,$vl_per_day);
				}
			}

			//ABSENCES
			foreach($data['ab'] as $key => $value7)
			{
				if($value7->slvl_employee_number == $value->employee_number && $value7->ab_date == $value->dates && $value7->slvl_type == 'AB')
				{
					$ab_per_day = $value7->total_slvl;
					$reg_ab = $ab_per_day;
					$hr_diff = intval($reg_ab/60);
					$min_diff = intval($reg_ab%60);
					$min_diff1 = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('P'.$row,$hr_diff.".".$min_diff1);
				}
			}

			//REMARKS
			foreach($data['remarks'] as $key => $value8)
			{
				if($value8->date == $value->dates && $value8->remarks_employee_number == $value->employee_number)
    		{
    			$remarkss = $value8->type_name;
    			$objWorkSheet->SetCellValue('Q'.$row,$remarkss);
    		}
			}

			$row++;
		}	
    $objWorkSheet->setTitle("BONTOC");


    $i++;
    }


    ////////////////////////////////////////////////////////// CANDON //////////////////////////////////////////////

    $i=0;
    while ($i < 1) {

      // Add new sheet
    $objWorkSheet = $objPHPExcel->createSheet($i); //Setting index when creating

	
		foreach(range('A','Q') as $columnID) {
    $objWorkSheet->getColumnDimension($columnID)
        ->setAutoSize(true);
		} 
		
		$objWorkSheet->SetCellValue('A1','EMPLOYEE NUMBER'); 
		$objWorkSheet->SetCellValue('B1','EMPLOYEE NAME');
		$objWorkSheet->SetCellValue('C1','DATES');
		$objWorkSheet->SetCellValue('D1','TIME IN');
		$objWorkSheet->SetCellValue('E1','TIME OUT');
		$objWorkSheet->SetCellValue('F1','Daily Hours');
		$objWorkSheet->SetCellValue('G1','TARDINESS');
		$objWorkSheet->SetCellValue('H1','UNDERTIME');
		$objWorkSheet->SetCellValue('I1','REGULAR OT');
		$objWorkSheet->SetCellValue('J1','LEGAL HOLIDAY OT');
		$objWorkSheet->SetCellValue('K1','SPECIAL HOLIDAY OT');
		$objWorkSheet->SetCellValue('L1','RESTDAY OT');
		$objWorkSheet->SetCellValue('M1','NSD');
		$objWorkSheet->SetCellValue('N1','SICK LEAVE');
		$objWorkSheet->SetCellValue('O1','VACATION LEAVE');
		$objWorkSheet->SetCellValue('P1','ABSENCES');
		$objWorkSheet->SetCellValue('Q1','REMARKS');

		$objWorkSheet->SetCellValue('A2',' ');
		$objWorkSheet->SetCellValue('B2',' ');
		$objWorkSheet->SetCellValue('C2',' ');
		$objWorkSheet->SetCellValue('D2',' ');
		$objWorkSheet->SetCellValue('E2',' ');
		$objWorkSheet->SetCellValue('F2','HOURS.MINS');
		$objWorkSheet->SetCellValue('G2','HOURS.MINS');
		$objWorkSheet->SetCellValue('H2','HOURS.MINS');
		$objWorkSheet->SetCellValue('I2','HOURS.MINS');
		$objWorkSheet->SetCellValue('J2','HOURS.MINS');
		$objWorkSheet->SetCellValue('K2','HOURS.MINS');
		$objWorkSheet->SetCellValue('L2','HOURS.MINS');
		$objWorkSheet->SetCellValue('M2','HOURS.MINS');
		$objWorkSheet->SetCellValue('N2','DAYS');
		$objWorkSheet->SetCellValue('O2','DAYS');
		$objWorkSheet->SetCellValue('P2','HOURS.MINS');
		$objWorkSheet->SetCellValue('Q2',' ');

		$row = 3;

		foreach($data['employeesCandon'] as $key => $value)
		{
			$in_office	= $data['schedules']->daily_in; 
			$out_office   = $data['schedules']->daily_out;
			$friday_out = $data['schedules']->daily_friday_out;
			$night_diff = '22:00';
			$in_daily = $value->intime;
			$out_daily = $value->outtime;
			$week_date = date('w', strtotime($value->dates)); // Convert in days . friday (5)

			// EXPLODE DATE IN TIME IN / TIME OUT
			$explode_in_date_daily = explode(" ", $in_daily);
			$explode_out_date_daily = explode(" ", $out_daily);
			$date_date_in = $explode_in_date_daily[0];
			$date_date_out = $explode_out_date_daily[0];
			$date_in = $explode_in_date_daily[1];
			$date_out = $explode_out_date_daily[1];

			//NIGHT DIFF
			$explode_night_diff = explode(":", $night_diff);
			$night_diff_hr = $explode_night_diff[0]; 
			$night_diff_min = $explode_night_diff[1]; 
			$total_night_diff = intval($night_diff_hr*60) + $night_diff_min; // total night diff

			// EXPLODE IN AND OUT 
			$explode_in_office = explode(":", $in_office);
			$explode_out_office = explode(":", $out_office);
			$explode_friday_out_office = explode(":", $friday_out);
			$explode_in_daily = explode(":", $date_in);
			$explode_out_daily = explode(":", $date_out);
			$time_in_hr_daily = $explode_in_daily[0];
			$time_in_min_daily = $explode_in_daily[1];
			$time_out_hr_daily = $explode_out_daily[0];
			$time_out_min_daily = $explode_out_daily[1];
			$time_in_hr = $explode_in_office[0];
			$time_in_min = $explode_in_office[1];
			$time_out_hr = $explode_out_office[0];
			$time_out_min = $explode_out_office[1];
			$time_friday_out_hr = $explode_friday_out_office[0];
			$time_friday_out_min = $explode_friday_out_office[1];


			// Convert IN AND OUT
			$total_in_min = intval($time_in_hr*60) + $time_in_min; // DEFAULT IN
			$total_in_min_grace = intval($time_in_hr*60) + $time_in_min + 15; // DEFAULT IN WITH GRACE PERIOD!
			$total_out_min = intval($time_out_hr*60) + $time_out_min; // DEFAULT OUT
			$total_friday_out_min = intval($time_friday_out_hr*60) + $time_friday_out_min; // DEFAULT OUT IN FRIDAY
			$total_in_daily = intval($time_in_hr_daily*60) + $time_in_min_daily; // EMPLOYEE IN
			$total_out_daily = intval($time_out_hr_daily*60) + $time_out_min_daily; // EMPLOYEE OUT

			//COMPUTATION IN OFFICE IN AND OUT
			$total_min_diff = intval($total_out_min - $total_in_min);
			$hr_diff = intval($total_min_diff/60);
			$min_diff = intval($total_min_diff%60);
			

			// IN AND OUT OF EMPLOYEE
			$in = strtotime($value->intime);
			$out   = strtotime($value->outtime);
			$diff  = $out - $in;

			//CONVERT OF IN AND OUT
			$hours = floor($diff / (60 * 60));
			$minutes = $diff - $hours * (60 * 60); 
			$total_minutes = floor( $minutes / 60 );
			
			// COMPUTATION OF IN AND OUT
			$total_number_of_hours = $hours.".".$total_minutes; //
			$total_office_hours = $hr_diff.".".$min_diff; // 9:30 Fixed
			$number_hr_daily = $total_number_of_hours; // TOTAL HOURS DAILY!
			$start_halfday = 660; 
			$end_halfday = 750;

			$objWorkSheet->SetCellValue('A'.$row,$value->employee_number);
			$objWorkSheet->SetCellValue('B'.$row,$value->name);
			$objWorkSheet->SetCellValue('C'.$row,$value->dates);
			$objWorkSheet->SetCellValue('D'.$row,$date_in);
			$objWorkSheet->SetCellValue('E'.$row,$date_out);

			//DAILY HOURS
			if($week_date >= 1 && $week_date <= 4)
			{
				if($date_date_in == $date_date_out && $total_in_daily == 0 && $total_out_daily == 0)
				{
					$objWorkSheet->SetCellValue('F'.$row," ");
				}
				elseif($total_in_min_grace >= $total_in_daily && $total_out_daily >= $total_out_min)
				{
					$total_min_diff = intval($total_out_min - $total_in_min - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}			
				elseif($total_in_daily > $total_in_min_grace && $total_out_daily < $total_out_min)
				{
					$total_min_diff = intval($total_out_daily - $total_in_daily - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($total_in_daily > $total_in_min_grace )
				{
					$total_min_diff = intval($total_out_min - $total_in_daily - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($total_out_daily < $total_out_min)
				{
					$total_min_diff = intval($total_out_daily - $total_in_min - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($total_in_daily >= $total_in_min_grace)
				{
					$total_min_diff = intval($total_out_min - $total_in_daily - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$min_diff."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($total_in_daily <= $total_in_min_grace)
				{
					$total_min_diff = intval($total_out_min - $total_in_min - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
			}
			elseif($week_date == 5)
			{
				if($date_date_in == $date_date_out && $total_in_daily == 0 && $total_out_daily == 0)
				{
					$objWorkSheet->SetCellValue('F'.$row," ");
				}
				elseif($date_date_in == $date_date_out && $total_in_min_grace >= $total_in_daily && $total_out_daily >= $total_friday_out_min)
				{
					$total_min_diff = intval($total_friday_out_min - $total_in_min );
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");

				}	
				elseif($date_date_in == $date_date_out && $total_in_daily > $total_in_min_grace && $total_out_daily < $total_friday_out_min)
				{
					$total_min_diff = intval($total_out_daily - $total_in_daily);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($date_date_in == $date_date_out && $total_in_daily > $total_in_min_grace )
				{
					$total_min_diff = intval($total_friday_out_min - $total_in_daily);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($date_date_in == $date_date_out && $total_out_daily < $total_friday_out_min)
				{
					$total_min_diff = intval($total_out_daily - $total_in_min);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($date_date_in != $date_date_out && $total_in_daily >= $total_in_min_grace)
				{
					$total_min_diff = intval($total_friday_out_min - $total_in_daily);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($date_date_in != $date_date_out || $total_in_daily <= $total_in_min_grace)
				{
					$total_min_diff = intval($total_friday_out_min - $total_in_min);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
			}
			elseif($week_date == 6)
			{
				$objWorkSheet->SetCellValue('F'.$row,"SATURDAY");
			}
			elseif($week_date == 0)
			{
				$objWorkSheet->SetCellValue('F'.$row,"SUNDAY");
			}
			else
			{
				$objWorkSheet->SetCellValue('F'.$row," ");
			}

			//TARDINESS
			if($week_date == 1 || $week_date == 2 || $week_date == 3 || $week_date == 4)
			{
				if($start_halfday <= $total_in_daily)
				{
					$objWorkSheet->SetCellValue('G'.$row," ");
				}
				else
				{
					if($total_in_daily > $total_in_min_grace)
					{
						$late_hr = intval($total_in_daily - $total_in_min);
						$hr_diff = intval($late_hr/60);
						$min_diff = intval($late_hr%60);
						//echo $hr_diff.".".$min_diff."";
						$minn = sprintf("%02d", $min_diff);
						$number_of_late = $hr_diff.".".$minn;
						$objWorkSheet->SetCellValue('G'.$row,$hr_diff.".".$minn."");
						$hr_lte = $late_hr;
					}
					else
					{
						$objWorkSheet->SetCellValue('G'.$row," ");
					}
				}
			}	
			elseif($week_date == 5)
			{
				if($start_halfday <= $total_in_daily)
				{
					$objWorkSheet->SetCellValue('G'.$row," ");
				}
				else
				{
					if($total_in_daily > $total_in_min_grace)
					{
						$late_hr = intval($total_in_daily - $total_in_min);
						$hr_diff = intval($late_hr/60);
						$min_diff = intval($late_hr%60);
						$objWorkSheet->SetCellValue('G'.$row,$hr_diff.".".$min_diff."");
						$minn = sprintf("%02d", $min_diff);
					}
					else
					{
						$objWorkSheet->SetCellValue('G'.$row," ");
					}
				}
				
			}
			else
			{
				$objWorkSheet->SetCellValue('G'.$row," ");
			}
			
			//UNDERTIME
			$halfday_in = 810;
			if($week_date == 1 || $week_date == 2 || $week_date == 3 || $week_date == 4)
			{
				if($date_date_in == $date_date_out && $total_out_daily < $halfday_in)
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
				elseif($total_out_min <= $total_out_daily)
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
				elseif($total_out_daily > $halfday_in)
				{
					$undertime_hr = intval($total_out_min - $total_out_daily);
					$hr_diff = intval($undertime_hr/60);
					$min_diff = intval($undertime_hr%60);
					$minn = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('H'.$row,$hr_diff.".".$minn);
				}
				else
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
			}
			elseif($week_date == 5)
			{
				if($date_date_in == $date_date_out && $total_out_daily < $halfday_in)
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
				elseif($total_friday_out_min <= $total_out_daily)
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
				elseif($date_date_in == $date_date_out && $halfday_in < $total_out_daily)
				{
					$undertime_hr = intval($total_friday_out_min - $total_out_daily);
					$hr_diff = intval($undertime_hr/60);
					$min_diff = intval($undertime_hr%60);
					$minn = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('H'.$row,$hr_diff.".".$minn);
				}
				else
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
			}
			else
			{
				$objWorkSheet->SetCellValue('H'.$row," ");
			}

			//REGULAR OT
			foreach($data['rots'] as $key => $value1)
			{
				if($value1->ot_employee_number == $value->employee_number && $value1->date_ot == $value->dates)
				{
					$reg_ot = $value1->total_ot;
					$hr_diff = intval($reg_ot/60);
					$min_diff = intval($reg_ot%60);
					$min_diff1 = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('I'.$row,$hr_diff.".".$min_diff1);
				}
			}	

			//LEGAL HOLIDAY OT
			foreach($data['lots'] as $key => $value2)
			{
				if($value2->legal_ot_employee_number == $value->employee_number && $value2->date_ot == $value->dates)
				{
					$reg_ot = $value2->total_ot;
					$hr_diff = intval($reg_ot/60);
					$min_diff = intval($reg_ot%60);
					$min_diff1 = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('J'.$row,$hr_diff.".".$min_diff1);
				}
			}

			//SPECIAL HOLIDAY OT
			foreach($data['shots'] as $key => $value3)
			{
				if($value3->special_ot_employee_number == $value->employee_number && $value3->date_ot == $value->dates)
				{
					$reg_ot = $value3->total_ot;
					$hr_diff = intval($reg_ot/60);
					$min_diff = intval($reg_ot%60);
					$min_diff1 = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('K'.$row,$hr_diff.".".$min_diff1);
				}
			}	

			//REST DAY OT
			foreach($data['rdots'] as $key => $value4)
			{
				if($value4->restday_ot_employee_number == $value->employee_number && $value4->date_ot == $value->dates)
				{
					$reg_ot = $value4->total_ot;
					$hr_diff = intval($reg_ot/60);
					$min_diff = intval($reg_ot%60);
					$min_diff1 = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('L'.$row,$hr_diff.".".$min_diff1);
				}
			}	

			//NIGHT DIFF
			$set_night_diff_morning = '6:00';
			$explode_night_diff_morning = explode(':', $set_night_diff_morning);
			$night_diff_morning = intval($explode_night_diff_morning[0]*60);
			$compute_night_diff_morning =$night_diff_morning - $total_in_daily;
			if($total_in_daily < $night_diff_morning)
			{
				$compute_night_diff_morning;
				$hr_diff = intval($compute_night_diff_morning/60);
				$min_diff = intval($compute_night_diff_morning%60);
				if($total_in_daily == 0 && $total_out_daily == 0)
				{
					$nd = ' ';
					//echo 0;
					$objWorkSheet->SetCellValue('M'.$row, $nd);
				}
				elseif($min_diff > 30 || $min_diff == 0)
				{
					$nd = $hr_diff."."."30";
					//echo $hr_diff."."."30";
					$objWorkSheet->SetCellValue('M'.$row, $nd);
				}
				elseif($min_diff < 30)
				{
					$nd = $hr_diff;
					$objWorkSheet->SetCellValue('M'.$row, $nd);
					//echo $hr_diff;
				}
			}
			else
			{
				$nd = ' ';
				$objWorkSheet->SetCellValue('M'.$row, $nd);
				//echo 0;
			}

			//SICK LEAVE
			foreach($data['sl'] as $key => $value5)
			{
				if($value5->slvl_employee_number == $value->employee_number && $value5->sl_date == $value->dates)
				{
					$sl_per_day = $value5->total_slvl;
					$objWorkSheet->SetCellValue('N'.$row,$sl_per_day);
				}
			}

			//VACATION LEAVE
			foreach($data['vl'] as $key => $value6)
			{
				if($value6->slvl_employee_number == $value->employee_number && $value6->vl_date == $value->dates)
				{
					$vl_per_day = $value6->total_slvl;
					$objWorkSheet->SetCellValue('O'.$row,$vl_per_day);
				}
			}

			//ABSENCES
			foreach($data['ab'] as $key => $value7)
			{
				if($value7->slvl_employee_number == $value->employee_number && $value7->ab_date == $value->dates && $value7->slvl_type == 'AB')
				{
					$ab_per_day = $value7->total_slvl;
					$reg_ab = $ab_per_day;
					$hr_diff = intval($reg_ab/60);
					$min_diff = intval($reg_ab%60);
					$min_diff1 = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('P'.$row,$hr_diff.".".$min_diff1);
				}
			}

			//REMARKS
			foreach($data['remarks'] as $key => $value8)
			{
				if($value8->date == $value->dates && $value8->remarks_employee_number == $value->employee_number)
    		{
    			$remarkss = $value8->type_name;
    			$objWorkSheet->SetCellValue('Q'.$row,$remarkss);
    		}
			}

			$row++;
		}	
    $objWorkSheet->setTitle("CANDON");


    $i++;
    
    }

    ////////////////////////////////////////////////////// DAGUPAN \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

    $i=0;
    while ($i < 1) {

      // Add new sheet
    $objWorkSheet = $objPHPExcel->createSheet($i); //Setting index when creating

	
		foreach(range('A','Q') as $columnID) {
    $objWorkSheet->getColumnDimension($columnID)
        ->setAutoSize(true);
		} 
		
		$objWorkSheet->SetCellValue('A1','EMPLOYEE NUMBER'); 
		$objWorkSheet->SetCellValue('B1','EMPLOYEE NAME');
		$objWorkSheet->SetCellValue('C1','DATES');
		$objWorkSheet->SetCellValue('D1','TIME IN');
		$objWorkSheet->SetCellValue('E1','TIME OUT');
		$objWorkSheet->SetCellValue('F1','Daily Hours');
		$objWorkSheet->SetCellValue('G1','TARDINESS');
		$objWorkSheet->SetCellValue('H1','UNDERTIME');
		$objWorkSheet->SetCellValue('I1','REGULAR OT');
		$objWorkSheet->SetCellValue('J1','LEGAL HOLIDAY OT');
		$objWorkSheet->SetCellValue('K1','SPECIAL HOLIDAY OT');
		$objWorkSheet->SetCellValue('L1','RESTDAY OT');
		$objWorkSheet->SetCellValue('M1','NSD');
		$objWorkSheet->SetCellValue('N1','SICK LEAVE');
		$objWorkSheet->SetCellValue('O1','VACATION LEAVE');
		$objWorkSheet->SetCellValue('P1','ABSENCES');
		$objWorkSheet->SetCellValue('Q1','REMARKS');

		$objWorkSheet->SetCellValue('A2',' ');
		$objWorkSheet->SetCellValue('B2',' ');
		$objWorkSheet->SetCellValue('C2',' ');
		$objWorkSheet->SetCellValue('D2',' ');
		$objWorkSheet->SetCellValue('E2',' ');
		$objWorkSheet->SetCellValue('F2','HOURS.MINS');
		$objWorkSheet->SetCellValue('G2','HOURS.MINS');
		$objWorkSheet->SetCellValue('H2','HOURS.MINS');
		$objWorkSheet->SetCellValue('I2','HOURS.MINS');
		$objWorkSheet->SetCellValue('J2','HOURS.MINS');
		$objWorkSheet->SetCellValue('K2','HOURS.MINS');
		$objWorkSheet->SetCellValue('L2','HOURS.MINS');
		$objWorkSheet->SetCellValue('M2','HOURS.MINS');
		$objWorkSheet->SetCellValue('N2','DAYS');
		$objWorkSheet->SetCellValue('O2','DAYS');
		$objWorkSheet->SetCellValue('P2','HOURS.MINS');
		$objWorkSheet->SetCellValue('Q2',' ');

		$row = 3;

		foreach($data['employeesDagupan'] as $key => $value)
		{
			$in_office	= $data['schedules']->daily_in; 
			$out_office   = $data['schedules']->daily_out;
			$friday_out = $data['schedules']->daily_friday_out;
			$night_diff = '22:00';
			$in_daily = $value->intime;
			$out_daily = $value->outtime;
			$week_date = date('w', strtotime($value->dates)); // Convert in days . friday (5)

			// EXPLODE DATE IN TIME IN / TIME OUT
			$explode_in_date_daily = explode(" ", $in_daily);
			$explode_out_date_daily = explode(" ", $out_daily);
			$date_date_in = $explode_in_date_daily[0];
			$date_date_out = $explode_out_date_daily[0];
			$date_in = $explode_in_date_daily[1];
			$date_out = $explode_out_date_daily[1];

			//NIGHT DIFF
			$explode_night_diff = explode(":", $night_diff);
			$night_diff_hr = $explode_night_diff[0]; 
			$night_diff_min = $explode_night_diff[1]; 
			$total_night_diff = intval($night_diff_hr*60) + $night_diff_min; // total night diff

			// EXPLODE IN AND OUT 
			$explode_in_office = explode(":", $in_office);
			$explode_out_office = explode(":", $out_office);
			$explode_friday_out_office = explode(":", $friday_out);
			$explode_in_daily = explode(":", $date_in);
			$explode_out_daily = explode(":", $date_out);
			$time_in_hr_daily = $explode_in_daily[0];
			$time_in_min_daily = $explode_in_daily[1];
			$time_out_hr_daily = $explode_out_daily[0];
			$time_out_min_daily = $explode_out_daily[1];
			$time_in_hr = $explode_in_office[0];
			$time_in_min = $explode_in_office[1];
			$time_out_hr = $explode_out_office[0];
			$time_out_min = $explode_out_office[1];
			$time_friday_out_hr = $explode_friday_out_office[0];
			$time_friday_out_min = $explode_friday_out_office[1];


			// Convert IN AND OUT
			$total_in_min = intval($time_in_hr*60) + $time_in_min; // DEFAULT IN
			$total_in_min_grace = intval($time_in_hr*60) + $time_in_min + 15; // DEFAULT IN WITH GRACE PERIOD!
			$total_out_min = intval($time_out_hr*60) + $time_out_min; // DEFAULT OUT
			$total_friday_out_min = intval($time_friday_out_hr*60) + $time_friday_out_min; // DEFAULT OUT IN FRIDAY
			$total_in_daily = intval($time_in_hr_daily*60) + $time_in_min_daily; // EMPLOYEE IN
			$total_out_daily = intval($time_out_hr_daily*60) + $time_out_min_daily; // EMPLOYEE OUT

			//COMPUTATION IN OFFICE IN AND OUT
			$total_min_diff = intval($total_out_min - $total_in_min);
			$hr_diff = intval($total_min_diff/60);
			$min_diff = intval($total_min_diff%60);
			

			// IN AND OUT OF EMPLOYEE
			$in = strtotime($value->intime);
			$out   = strtotime($value->outtime);
			$diff  = $out - $in;

			//CONVERT OF IN AND OUT
			$hours = floor($diff / (60 * 60));
			$minutes = $diff - $hours * (60 * 60); 
			$total_minutes = floor( $minutes / 60 );
			
			// COMPUTATION OF IN AND OUT
			$total_number_of_hours = $hours.".".$total_minutes; //
			$total_office_hours = $hr_diff.".".$min_diff; // 9:30 Fixed
			$number_hr_daily = $total_number_of_hours; // TOTAL HOURS DAILY!
			$start_halfday = 660; 
			$end_halfday = 750;

			$objWorkSheet->SetCellValue('A'.$row,$value->employee_number);
			$objWorkSheet->SetCellValue('B'.$row,$value->name);
			$objWorkSheet->SetCellValue('C'.$row,$value->dates);
			$objWorkSheet->SetCellValue('D'.$row,$date_in);
			$objWorkSheet->SetCellValue('E'.$row,$date_out);

			//DAILY HOURS
			if($week_date >= 1 && $week_date <= 4)
			{
				if($date_date_in == $date_date_out && $total_in_daily == 0 && $total_out_daily == 0)
				{
					$objWorkSheet->SetCellValue('F'.$row," ");
				}
				elseif($total_in_min_grace >= $total_in_daily && $total_out_daily >= $total_out_min)
				{
					$total_min_diff = intval($total_out_min - $total_in_min - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}			
				elseif($total_in_daily > $total_in_min_grace && $total_out_daily < $total_out_min)
				{
					$total_min_diff = intval($total_out_daily - $total_in_daily - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($total_in_daily > $total_in_min_grace )
				{
					$total_min_diff = intval($total_out_min - $total_in_daily - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($total_out_daily < $total_out_min)
				{
					$total_min_diff = intval($total_out_daily - $total_in_min - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($total_in_daily >= $total_in_min_grace)
				{
					$total_min_diff = intval($total_out_min - $total_in_daily - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$min_diff."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($total_in_daily <= $total_in_min_grace)
				{
					$total_min_diff = intval($total_out_min - $total_in_min - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
			}
			elseif($week_date == 5)
			{
				if($date_date_in == $date_date_out && $total_in_daily == 0 && $total_out_daily == 0)
				{
					$objWorkSheet->SetCellValue('F'.$row," ");
				}
				elseif($date_date_in == $date_date_out && $total_in_min_grace >= $total_in_daily && $total_out_daily >= $total_friday_out_min)
				{
					$total_min_diff = intval($total_friday_out_min - $total_in_min );
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");

				}	
				elseif($date_date_in == $date_date_out && $total_in_daily > $total_in_min_grace && $total_out_daily < $total_friday_out_min)
				{
					$total_min_diff = intval($total_out_daily - $total_in_daily);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($date_date_in == $date_date_out && $total_in_daily > $total_in_min_grace )
				{
					$total_min_diff = intval($total_friday_out_min - $total_in_daily);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($date_date_in == $date_date_out && $total_out_daily < $total_friday_out_min)
				{
					$total_min_diff = intval($total_out_daily - $total_in_min);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($date_date_in != $date_date_out && $total_in_daily >= $total_in_min_grace)
				{
					$total_min_diff = intval($total_friday_out_min - $total_in_daily);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($date_date_in != $date_date_out || $total_in_daily <= $total_in_min_grace)
				{
					$total_min_diff = intval($total_friday_out_min - $total_in_min);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
			}
			elseif($week_date == 6)
			{
				$objWorkSheet->SetCellValue('F'.$row,"SATURDAY");
			}
			elseif($week_date == 0)
			{
				$objWorkSheet->SetCellValue('F'.$row,"SUNDAY");
			}
			else
			{
				$objWorkSheet->SetCellValue('F'.$row," ");
			}

			//TARDINESS
			if($week_date == 1 || $week_date == 2 || $week_date == 3 || $week_date == 4)
			{
				if($start_halfday <= $total_in_daily)
				{
					$objWorkSheet->SetCellValue('G'.$row," ");
				}
				else
				{
					if($total_in_daily > $total_in_min_grace)
					{
						$late_hr = intval($total_in_daily - $total_in_min);
						$hr_diff = intval($late_hr/60);
						$min_diff = intval($late_hr%60);
						//echo $hr_diff.".".$min_diff."";
						$minn = sprintf("%02d", $min_diff);
						$number_of_late = $hr_diff.".".$minn;
						$objWorkSheet->SetCellValue('G'.$row,$hr_diff.".".$minn."");
						$hr_lte = $late_hr;
					}
					else
					{
						$objWorkSheet->SetCellValue('G'.$row," ");
					}
				}
			}	
			elseif($week_date == 5)
			{
				if($start_halfday <= $total_in_daily)
				{
					$objWorkSheet->SetCellValue('G'.$row," ");
				}
				else
				{
					if($total_in_daily > $total_in_min_grace)
					{
						$late_hr = intval($total_in_daily - $total_in_min);
						$hr_diff = intval($late_hr/60);
						$min_diff = intval($late_hr%60);
						$objWorkSheet->SetCellValue('G'.$row,$hr_diff.".".$min_diff."");
						$minn = sprintf("%02d", $min_diff);
					}
					else
					{
						$objWorkSheet->SetCellValue('G'.$row," ");
					}
				}
				
			}
			else
			{
				$objWorkSheet->SetCellValue('G'.$row," ");
			}
			
			//UNDERTIME
			$halfday_in = 810;
			if($week_date == 1 || $week_date == 2 || $week_date == 3 || $week_date == 4)
			{
				if($date_date_in == $date_date_out && $total_out_daily < $halfday_in)
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
				elseif($total_out_min <= $total_out_daily)
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
				elseif($total_out_daily > $halfday_in)
				{
					$undertime_hr = intval($total_out_min - $total_out_daily);
					$hr_diff = intval($undertime_hr/60);
					$min_diff = intval($undertime_hr%60);
					$minn = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('H'.$row,$hr_diff.".".$minn);
				}
				else
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
			}
			elseif($week_date == 5)
			{
				if($date_date_in == $date_date_out && $total_out_daily < $halfday_in)
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
				elseif($total_friday_out_min <= $total_out_daily)
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
				elseif($date_date_in == $date_date_out && $halfday_in < $total_out_daily)
				{
					$undertime_hr = intval($total_friday_out_min - $total_out_daily);
					$hr_diff = intval($undertime_hr/60);
					$min_diff = intval($undertime_hr%60);
					$minn = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('H'.$row,$hr_diff.".".$minn);
				}
				else
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
			}
			else
			{
				$objWorkSheet->SetCellValue('H'.$row," ");
			}

			//REGULAR OT
			foreach($data['rots'] as $key => $value1)
			{
				if($value1->ot_employee_number == $value->employee_number && $value1->date_ot == $value->dates)
				{
					$reg_ot = $value1->total_ot;
					$hr_diff = intval($reg_ot/60);
					$min_diff = intval($reg_ot%60);
					$min_diff1 = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('I'.$row,$hr_diff.".".$min_diff1);
				}
			}	

			//LEGAL HOLIDAY OT
			foreach($data['lots'] as $key => $value2)
			{
				if($value2->legal_ot_employee_number == $value->employee_number && $value2->date_ot == $value->dates)
				{
					$reg_ot = $value2->total_ot;
					$hr_diff = intval($reg_ot/60);
					$min_diff = intval($reg_ot%60);
					$min_diff1 = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('J'.$row,$hr_diff.".".$min_diff1);
				}
			}

			//SPECIAL HOLIDAY OT
			foreach($data['shots'] as $key => $value3)
			{
				if($value3->special_ot_employee_number == $value->employee_number && $value3->date_ot == $value->dates)
				{
					$reg_ot = $value3->total_ot;
					$hr_diff = intval($reg_ot/60);
					$min_diff = intval($reg_ot%60);
					$min_diff1 = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('K'.$row,$hr_diff.".".$min_diff1);
				}
			}	

			//REST DAY OT
			foreach($data['rdots'] as $key => $value4)
			{
				if($value4->restday_ot_employee_number == $value->employee_number && $value4->date_ot == $value->dates)
				{
					$reg_ot = $value4->total_ot;
					$hr_diff = intval($reg_ot/60);
					$min_diff = intval($reg_ot%60);
					$min_diff1 = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('L'.$row,$hr_diff.".".$min_diff1);
				}
			}	

			//NIGHT DIFF
			$set_night_diff_morning = '6:00';
			$explode_night_diff_morning = explode(':', $set_night_diff_morning);
			$night_diff_morning = intval($explode_night_diff_morning[0]*60);
			$compute_night_diff_morning =$night_diff_morning - $total_in_daily;
			if($total_in_daily < $night_diff_morning)
			{
				$compute_night_diff_morning;
				$hr_diff = intval($compute_night_diff_morning/60);
				$min_diff = intval($compute_night_diff_morning%60);
				if($total_in_daily == 0 && $total_out_daily == 0)
				{
					$nd = ' ';
					//echo 0;
					$objWorkSheet->SetCellValue('M'.$row, $nd);
				}
				elseif($min_diff > 30 || $min_diff == 0)
				{
					$nd = $hr_diff."."."30";
					//echo $hr_diff."."."30";
					$objWorkSheet->SetCellValue('M'.$row, $nd);
				}
				elseif($min_diff < 30)
				{
					$nd = $hr_diff;
					$objWorkSheet->SetCellValue('M'.$row, $nd);
					//echo $hr_diff;
				}
			}
			else
			{
				$nd = ' ';
				$objWorkSheet->SetCellValue('M'.$row, $nd);
				//echo 0;
			}

			//SICK LEAVE
			foreach($data['sl'] as $key => $value5)
			{
				if($value5->slvl_employee_number == $value->employee_number && $value5->sl_date == $value->dates)
				{
					$sl_per_day = $value5->total_slvl;
					$objWorkSheet->SetCellValue('N'.$row,$sl_per_day);
				}
			}

			//VACATION LEAVE
			foreach($data['vl'] as $key => $value6)
			{
				if($value6->slvl_employee_number == $value->employee_number && $value6->vl_date == $value->dates)
				{
					$vl_per_day = $value6->total_slvl;
					$objWorkSheet->SetCellValue('O'.$row,$vl_per_day);
				}
			}

			//ABSENCES
			foreach($data['ab'] as $key => $value7)
			{
				if($value7->slvl_employee_number == $value->employee_number && $value7->ab_date == $value->dates && $value7->slvl_type == 'AB')
				{
					$ab_per_day = $value7->total_slvl;
					$reg_ab = $ab_per_day;
					$hr_diff = intval($reg_ab/60);
					$min_diff = intval($reg_ab%60);
					$min_diff1 = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('P'.$row,$hr_diff.".".$min_diff1);
				}
			}

			//REMARKS
			foreach($data['remarks'] as $key => $value8)
			{
				if($value8->date == $value->dates && $value8->remarks_employee_number == $value->employee_number)
    		{
    			$remarkss = $value8->type_name;
    			$objWorkSheet->SetCellValue('Q'.$row,$remarkss);
    		}
			}

			$row++;
		}	
    $objWorkSheet->setTitle("DAGUPAN");


    $i++;
    
    }

    ////////////////////////////////////////////////////// DIVISORIA \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

    $i=0;
    while ($i < 1) {

      // Add new sheet
    $objWorkSheet = $objPHPExcel->createSheet($i); //Setting index when creating

	
		foreach(range('A','Q') as $columnID) {
    $objWorkSheet->getColumnDimension($columnID)
        ->setAutoSize(true);
		} 
		
		$objWorkSheet->SetCellValue('A1','EMPLOYEE NUMBER'); 
		$objWorkSheet->SetCellValue('B1','EMPLOYEE NAME');
		$objWorkSheet->SetCellValue('C1','DATES');
		$objWorkSheet->SetCellValue('D1','TIME IN');
		$objWorkSheet->SetCellValue('E1','TIME OUT');
		$objWorkSheet->SetCellValue('F1','Daily Hours');
		$objWorkSheet->SetCellValue('G1','TARDINESS');
		$objWorkSheet->SetCellValue('H1','UNDERTIME');
		$objWorkSheet->SetCellValue('I1','REGULAR OT');
		$objWorkSheet->SetCellValue('J1','LEGAL HOLIDAY OT');
		$objWorkSheet->SetCellValue('K1','SPECIAL HOLIDAY OT');
		$objWorkSheet->SetCellValue('L1','RESTDAY OT');
		$objWorkSheet->SetCellValue('M1','NSD');
		$objWorkSheet->SetCellValue('N1','SICK LEAVE');
		$objWorkSheet->SetCellValue('O1','VACATION LEAVE');
		$objWorkSheet->SetCellValue('P1','ABSENCES');
		$objWorkSheet->SetCellValue('Q1','REMARKS');

		$objWorkSheet->SetCellValue('A2',' ');
		$objWorkSheet->SetCellValue('B2',' ');
		$objWorkSheet->SetCellValue('C2',' ');
		$objWorkSheet->SetCellValue('D2',' ');
		$objWorkSheet->SetCellValue('E2',' ');
		$objWorkSheet->SetCellValue('F2','HOURS.MINS');
		$objWorkSheet->SetCellValue('G2','HOURS.MINS');
		$objWorkSheet->SetCellValue('H2','HOURS.MINS');
		$objWorkSheet->SetCellValue('I2','HOURS.MINS');
		$objWorkSheet->SetCellValue('J2','HOURS.MINS');
		$objWorkSheet->SetCellValue('K2','HOURS.MINS');
		$objWorkSheet->SetCellValue('L2','HOURS.MINS');
		$objWorkSheet->SetCellValue('M2','HOURS.MINS');
		$objWorkSheet->SetCellValue('N2','DAYS');
		$objWorkSheet->SetCellValue('O2','DAYS');
		$objWorkSheet->SetCellValue('P2','HOURS.MINS');
		$objWorkSheet->SetCellValue('Q2',' ');

		$row = 3;

		foreach($data['employeesDivisoria'] as $key => $value)
		{
			$in_office	= $data['schedules']->daily_in; 
			$out_office   = $data['schedules']->daily_out;
			$friday_out = $data['schedules']->daily_friday_out;
			$night_diff = '22:00';
			$in_daily = $value->intime;
			$out_daily = $value->outtime;
			$week_date = date('w', strtotime($value->dates)); // Convert in days . friday (5)

			// EXPLODE DATE IN TIME IN / TIME OUT
			$explode_in_date_daily = explode(" ", $in_daily);
			$explode_out_date_daily = explode(" ", $out_daily);
			$date_date_in = $explode_in_date_daily[0];
			$date_date_out = $explode_out_date_daily[0];
			$date_in = $explode_in_date_daily[1];
			$date_out = $explode_out_date_daily[1];

			//NIGHT DIFF
			$explode_night_diff = explode(":", $night_diff);
			$night_diff_hr = $explode_night_diff[0]; 
			$night_diff_min = $explode_night_diff[1]; 
			$total_night_diff = intval($night_diff_hr*60) + $night_diff_min; // total night diff

			// EXPLODE IN AND OUT 
			$explode_in_office = explode(":", $in_office);
			$explode_out_office = explode(":", $out_office);
			$explode_friday_out_office = explode(":", $friday_out);
			$explode_in_daily = explode(":", $date_in);
			$explode_out_daily = explode(":", $date_out);
			$time_in_hr_daily = $explode_in_daily[0];
			$time_in_min_daily = $explode_in_daily[1];
			$time_out_hr_daily = $explode_out_daily[0];
			$time_out_min_daily = $explode_out_daily[1];
			$time_in_hr = $explode_in_office[0];
			$time_in_min = $explode_in_office[1];
			$time_out_hr = $explode_out_office[0];
			$time_out_min = $explode_out_office[1];
			$time_friday_out_hr = $explode_friday_out_office[0];
			$time_friday_out_min = $explode_friday_out_office[1];


			// Convert IN AND OUT
			$total_in_min = intval($time_in_hr*60) + $time_in_min; // DEFAULT IN
			$total_in_min_grace = intval($time_in_hr*60) + $time_in_min + 15; // DEFAULT IN WITH GRACE PERIOD!
			$total_out_min = intval($time_out_hr*60) + $time_out_min; // DEFAULT OUT
			$total_friday_out_min = intval($time_friday_out_hr*60) + $time_friday_out_min; // DEFAULT OUT IN FRIDAY
			$total_in_daily = intval($time_in_hr_daily*60) + $time_in_min_daily; // EMPLOYEE IN
			$total_out_daily = intval($time_out_hr_daily*60) + $time_out_min_daily; // EMPLOYEE OUT

			//COMPUTATION IN OFFICE IN AND OUT
			$total_min_diff = intval($total_out_min - $total_in_min);
			$hr_diff = intval($total_min_diff/60);
			$min_diff = intval($total_min_diff%60);
			

			// IN AND OUT OF EMPLOYEE
			$in = strtotime($value->intime);
			$out   = strtotime($value->outtime);
			$diff  = $out - $in;

			//CONVERT OF IN AND OUT
			$hours = floor($diff / (60 * 60));
			$minutes = $diff - $hours * (60 * 60); 
			$total_minutes = floor( $minutes / 60 );
			
			// COMPUTATION OF IN AND OUT
			$total_number_of_hours = $hours.".".$total_minutes; //
			$total_office_hours = $hr_diff.".".$min_diff; // 9:30 Fixed
			$number_hr_daily = $total_number_of_hours; // TOTAL HOURS DAILY!
			$start_halfday = 660; 
			$end_halfday = 750;

			$objWorkSheet->SetCellValue('A'.$row,$value->employee_number);
			$objWorkSheet->SetCellValue('B'.$row,$value->name);
			$objWorkSheet->SetCellValue('C'.$row,$value->dates);
			$objWorkSheet->SetCellValue('D'.$row,$date_in);
			$objWorkSheet->SetCellValue('E'.$row,$date_out);

			//DAILY HOURS
			if($week_date >= 1 && $week_date <= 4)
			{
				if($date_date_in == $date_date_out && $total_in_daily == 0 && $total_out_daily == 0)
				{
					$objWorkSheet->SetCellValue('F'.$row," ");
				}
				elseif($total_in_min_grace >= $total_in_daily && $total_out_daily >= $total_out_min)
				{
					$total_min_diff = intval($total_out_min - $total_in_min - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}			
				elseif($total_in_daily > $total_in_min_grace && $total_out_daily < $total_out_min)
				{
					$total_min_diff = intval($total_out_daily - $total_in_daily - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($total_in_daily > $total_in_min_grace )
				{
					$total_min_diff = intval($total_out_min - $total_in_daily - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($total_out_daily < $total_out_min)
				{
					$total_min_diff = intval($total_out_daily - $total_in_min - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($total_in_daily >= $total_in_min_grace)
				{
					$total_min_diff = intval($total_out_min - $total_in_daily - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$min_diff."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($total_in_daily <= $total_in_min_grace)
				{
					$total_min_diff = intval($total_out_min - $total_in_min - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
			}
			elseif($week_date == 5)
			{
				if($date_date_in == $date_date_out && $total_in_daily == 0 && $total_out_daily == 0)
				{
					$objWorkSheet->SetCellValue('F'.$row," ");
				}
				elseif($date_date_in == $date_date_out && $total_in_min_grace >= $total_in_daily && $total_out_daily >= $total_friday_out_min)
				{
					$total_min_diff = intval($total_friday_out_min - $total_in_min );
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");

				}	
				elseif($date_date_in == $date_date_out && $total_in_daily > $total_in_min_grace && $total_out_daily < $total_friday_out_min)
				{
					$total_min_diff = intval($total_out_daily - $total_in_daily);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($date_date_in == $date_date_out && $total_in_daily > $total_in_min_grace )
				{
					$total_min_diff = intval($total_friday_out_min - $total_in_daily);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($date_date_in == $date_date_out && $total_out_daily < $total_friday_out_min)
				{
					$total_min_diff = intval($total_out_daily - $total_in_min);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($date_date_in != $date_date_out && $total_in_daily >= $total_in_min_grace)
				{
					$total_min_diff = intval($total_friday_out_min - $total_in_daily);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($date_date_in != $date_date_out || $total_in_daily <= $total_in_min_grace)
				{
					$total_min_diff = intval($total_friday_out_min - $total_in_min);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
			}
			elseif($week_date == 6)
			{
				$objWorkSheet->SetCellValue('F'.$row,"SATURDAY");
			}
			elseif($week_date == 0)
			{
				$objWorkSheet->SetCellValue('F'.$row,"SUNDAY");
			}
			else
			{
				$objWorkSheet->SetCellValue('F'.$row," ");
			}

			//TARDINESS
			if($week_date == 1 || $week_date == 2 || $week_date == 3 || $week_date == 4)
			{
				if($start_halfday <= $total_in_daily)
				{
					$objWorkSheet->SetCellValue('G'.$row," ");
				}
				else
				{
					if($total_in_daily > $total_in_min_grace)
					{
						$late_hr = intval($total_in_daily - $total_in_min);
						$hr_diff = intval($late_hr/60);
						$min_diff = intval($late_hr%60);
						//echo $hr_diff.".".$min_diff."";
						$minn = sprintf("%02d", $min_diff);
						$number_of_late = $hr_diff.".".$minn;
						$objWorkSheet->SetCellValue('G'.$row,$hr_diff.".".$minn."");
						$hr_lte = $late_hr;
					}
					else
					{
						$objWorkSheet->SetCellValue('G'.$row," ");
					}
				}
			}	
			elseif($week_date == 5)
			{
				if($start_halfday <= $total_in_daily)
				{
					$objWorkSheet->SetCellValue('G'.$row," ");
				}
				else
				{
					if($total_in_daily > $total_in_min_grace)
					{
						$late_hr = intval($total_in_daily - $total_in_min);
						$hr_diff = intval($late_hr/60);
						$min_diff = intval($late_hr%60);
						$objWorkSheet->SetCellValue('G'.$row,$hr_diff.".".$min_diff."");
						$minn = sprintf("%02d", $min_diff);
					}
					else
					{
						$objWorkSheet->SetCellValue('G'.$row," ");
					}
				}
				
			}
			else
			{
				$objWorkSheet->SetCellValue('G'.$row," ");
			}
			
			//UNDERTIME
			$halfday_in = 810;
			if($week_date == 1 || $week_date == 2 || $week_date == 3 || $week_date == 4)
			{
				if($date_date_in == $date_date_out && $total_out_daily < $halfday_in)
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
				elseif($total_out_min <= $total_out_daily)
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
				elseif($total_out_daily > $halfday_in)
				{
					$undertime_hr = intval($total_out_min - $total_out_daily);
					$hr_diff = intval($undertime_hr/60);
					$min_diff = intval($undertime_hr%60);
					$minn = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('H'.$row,$hr_diff.".".$minn);
				}
				else
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
			}
			elseif($week_date == 5)
			{
				if($date_date_in == $date_date_out && $total_out_daily < $halfday_in)
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
				elseif($total_friday_out_min <= $total_out_daily)
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
				elseif($date_date_in == $date_date_out && $halfday_in < $total_out_daily)
				{
					$undertime_hr = intval($total_friday_out_min - $total_out_daily);
					$hr_diff = intval($undertime_hr/60);
					$min_diff = intval($undertime_hr%60);
					$minn = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('H'.$row,$hr_diff.".".$minn);
				}
				else
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
			}
			else
			{
				$objWorkSheet->SetCellValue('H'.$row," ");
			}

			//REGULAR OT
			foreach($data['rots'] as $key => $value1)
			{
				if($value1->ot_employee_number == $value->employee_number && $value1->date_ot == $value->dates)
				{
					$reg_ot = $value1->total_ot;
					$hr_diff = intval($reg_ot/60);
					$min_diff = intval($reg_ot%60);
					$min_diff1 = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('I'.$row,$hr_diff.".".$min_diff1);
				}
			}	

			//LEGAL HOLIDAY OT
			foreach($data['lots'] as $key => $value2)
			{
				if($value2->legal_ot_employee_number == $value->employee_number && $value2->date_ot == $value->dates)
				{
					$reg_ot = $value2->total_ot;
					$hr_diff = intval($reg_ot/60);
					$min_diff = intval($reg_ot%60);
					$min_diff1 = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('J'.$row,$hr_diff.".".$min_diff1);
				}
			}

			//SPECIAL HOLIDAY OT
			foreach($data['shots'] as $key => $value3)
			{
				if($value3->special_ot_employee_number == $value->employee_number && $value3->date_ot == $value->dates)
				{
					$reg_ot = $value3->total_ot;
					$hr_diff = intval($reg_ot/60);
					$min_diff = intval($reg_ot%60);
					$min_diff1 = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('K'.$row,$hr_diff.".".$min_diff1);
				}
			}	

			//REST DAY OT
			foreach($data['rdots'] as $key => $value4)
			{
				if($value4->restday_ot_employee_number == $value->employee_number && $value4->date_ot == $value->dates)
				{
					$reg_ot = $value4->total_ot;
					$hr_diff = intval($reg_ot/60);
					$min_diff = intval($reg_ot%60);
					$min_diff1 = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('L'.$row,$hr_diff.".".$min_diff1);
				}
			}	

			//NIGHT DIFF
			$set_night_diff_morning = '6:00';
			$explode_night_diff_morning = explode(':', $set_night_diff_morning);
			$night_diff_morning = intval($explode_night_diff_morning[0]*60);
			$compute_night_diff_morning =$night_diff_morning - $total_in_daily;
			if($total_in_daily < $night_diff_morning)
			{
				$compute_night_diff_morning;
				$hr_diff = intval($compute_night_diff_morning/60);
				$min_diff = intval($compute_night_diff_morning%60);
				if($total_in_daily == 0 && $total_out_daily == 0)
				{
					$nd = ' ';
					//echo 0;
					$objWorkSheet->SetCellValue('M'.$row, $nd);
				}
				elseif($min_diff > 30 || $min_diff == 0)
				{
					$nd = $hr_diff."."."30";
					//echo $hr_diff."."."30";
					$objWorkSheet->SetCellValue('M'.$row, $nd);
				}
				elseif($min_diff < 30)
				{
					$nd = $hr_diff;
					$objWorkSheet->SetCellValue('M'.$row, $nd);
					//echo $hr_diff;
				}
			}
			else
			{
				$nd = ' ';
				$objWorkSheet->SetCellValue('M'.$row, $nd);
				//echo 0;
			}

			//SICK LEAVE
			foreach($data['sl'] as $key => $value5)
			{
				if($value5->slvl_employee_number == $value->employee_number && $value5->sl_date == $value->dates)
				{
					$sl_per_day = $value5->total_slvl;
					$objWorkSheet->SetCellValue('N'.$row,$sl_per_day);
				}
			}

			//VACATION LEAVE
			foreach($data['vl'] as $key => $value6)
			{
				if($value6->slvl_employee_number == $value->employee_number && $value6->vl_date == $value->dates)
				{
					$vl_per_day = $value6->total_slvl;
					$objWorkSheet->SetCellValue('O'.$row,$vl_per_day);
				}
			}

			//ABSENCES
			foreach($data['ab'] as $key => $value7)
			{
				if($value7->slvl_employee_number == $value->employee_number && $value7->ab_date == $value->dates && $value7->slvl_type == 'AB')
				{
					$ab_per_day = $value7->total_slvl;
					$reg_ab = $ab_per_day;
					$hr_diff = intval($reg_ab/60);
					$min_diff = intval($reg_ab%60);
					$min_diff1 = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('P'.$row,$hr_diff.".".$min_diff1);
				}
			}

			//REMARKS
			foreach($data['remarks'] as $key => $value8)
			{
				if($value8->date == $value->dates && $value8->remarks_employee_number == $value->employee_number)
    		{
    			$remarkss = $value8->type_name;
    			$objWorkSheet->SetCellValue('Q'.$row,$remarkss);
    		}
			}

			$row++;
		}	
    $objWorkSheet->setTitle("DIVISORIA");


    $i++;
    
    }

    ///////////////////////////////////////////////////// LA UNION \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

    $i=0;
    while ($i < 1) {

      // Add new sheet
    $objWorkSheet = $objPHPExcel->createSheet($i); //Setting index when creating

	
		foreach(range('A','Q') as $columnID) {
    $objWorkSheet->getColumnDimension($columnID)
        ->setAutoSize(true);
		} 
		
		$objWorkSheet->SetCellValue('A1','EMPLOYEE NUMBER'); 
		$objWorkSheet->SetCellValue('B1','EMPLOYEE NAME');
		$objWorkSheet->SetCellValue('C1','DATES');
		$objWorkSheet->SetCellValue('D1','TIME IN');
		$objWorkSheet->SetCellValue('E1','TIME OUT');
		$objWorkSheet->SetCellValue('F1','Daily Hours');
		$objWorkSheet->SetCellValue('G1','TARDINESS');
		$objWorkSheet->SetCellValue('H1','UNDERTIME');
		$objWorkSheet->SetCellValue('I1','REGULAR OT');
		$objWorkSheet->SetCellValue('J1','LEGAL HOLIDAY OT');
		$objWorkSheet->SetCellValue('K1','SPECIAL HOLIDAY OT');
		$objWorkSheet->SetCellValue('L1','RESTDAY OT');
		$objWorkSheet->SetCellValue('M1','NSD');
		$objWorkSheet->SetCellValue('N1','SICK LEAVE');
		$objWorkSheet->SetCellValue('O1','VACATION LEAVE');
		$objWorkSheet->SetCellValue('P1','ABSENCES');
		$objWorkSheet->SetCellValue('Q1','REMARKS');

		$objWorkSheet->SetCellValue('A2',' ');
		$objWorkSheet->SetCellValue('B2',' ');
		$objWorkSheet->SetCellValue('C2',' ');
		$objWorkSheet->SetCellValue('D2',' ');
		$objWorkSheet->SetCellValue('E2',' ');
		$objWorkSheet->SetCellValue('F2','HOURS.MINS');
		$objWorkSheet->SetCellValue('G2','HOURS.MINS');
		$objWorkSheet->SetCellValue('H2','HOURS.MINS');
		$objWorkSheet->SetCellValue('I2','HOURS.MINS');
		$objWorkSheet->SetCellValue('J2','HOURS.MINS');
		$objWorkSheet->SetCellValue('K2','HOURS.MINS');
		$objWorkSheet->SetCellValue('L2','HOURS.MINS');
		$objWorkSheet->SetCellValue('M2','HOURS.MINS');
		$objWorkSheet->SetCellValue('N2','DAYS');
		$objWorkSheet->SetCellValue('O2','DAYS');
		$objWorkSheet->SetCellValue('P2','HOURS.MINS');
		$objWorkSheet->SetCellValue('Q2',' ');

		$row = 3;

		foreach($data['employeesLaunion'] as $key => $value)
		{
			$in_office	= $data['schedules']->daily_in; 
			$out_office   = $data['schedules']->daily_out;
			$friday_out = $data['schedules']->daily_friday_out;
			$night_diff = '22:00';
			$in_daily = $value->intime;
			$out_daily = $value->outtime;
			$week_date = date('w', strtotime($value->dates)); // Convert in days . friday (5)

			// EXPLODE DATE IN TIME IN / TIME OUT
			$explode_in_date_daily = explode(" ", $in_daily);
			$explode_out_date_daily = explode(" ", $out_daily);
			$date_date_in = $explode_in_date_daily[0];
			$date_date_out = $explode_out_date_daily[0];
			$date_in = $explode_in_date_daily[1];
			$date_out = $explode_out_date_daily[1];

			//NIGHT DIFF
			$explode_night_diff = explode(":", $night_diff);
			$night_diff_hr = $explode_night_diff[0]; 
			$night_diff_min = $explode_night_diff[1]; 
			$total_night_diff = intval($night_diff_hr*60) + $night_diff_min; // total night diff

			// EXPLODE IN AND OUT 
			$explode_in_office = explode(":", $in_office);
			$explode_out_office = explode(":", $out_office);
			$explode_friday_out_office = explode(":", $friday_out);
			$explode_in_daily = explode(":", $date_in);
			$explode_out_daily = explode(":", $date_out);
			$time_in_hr_daily = $explode_in_daily[0];
			$time_in_min_daily = $explode_in_daily[1];
			$time_out_hr_daily = $explode_out_daily[0];
			$time_out_min_daily = $explode_out_daily[1];
			$time_in_hr = $explode_in_office[0];
			$time_in_min = $explode_in_office[1];
			$time_out_hr = $explode_out_office[0];
			$time_out_min = $explode_out_office[1];
			$time_friday_out_hr = $explode_friday_out_office[0];
			$time_friday_out_min = $explode_friday_out_office[1];


			// Convert IN AND OUT
			$total_in_min = intval($time_in_hr*60) + $time_in_min; // DEFAULT IN
			$total_in_min_grace = intval($time_in_hr*60) + $time_in_min + 15; // DEFAULT IN WITH GRACE PERIOD!
			$total_out_min = intval($time_out_hr*60) + $time_out_min; // DEFAULT OUT
			$total_friday_out_min = intval($time_friday_out_hr*60) + $time_friday_out_min; // DEFAULT OUT IN FRIDAY
			$total_in_daily = intval($time_in_hr_daily*60) + $time_in_min_daily; // EMPLOYEE IN
			$total_out_daily = intval($time_out_hr_daily*60) + $time_out_min_daily; // EMPLOYEE OUT

			//COMPUTATION IN OFFICE IN AND OUT
			$total_min_diff = intval($total_out_min - $total_in_min);
			$hr_diff = intval($total_min_diff/60);
			$min_diff = intval($total_min_diff%60);
			

			// IN AND OUT OF EMPLOYEE
			$in = strtotime($value->intime);
			$out   = strtotime($value->outtime);
			$diff  = $out - $in;

			//CONVERT OF IN AND OUT
			$hours = floor($diff / (60 * 60));
			$minutes = $diff - $hours * (60 * 60); 
			$total_minutes = floor( $minutes / 60 );
			
			// COMPUTATION OF IN AND OUT
			$total_number_of_hours = $hours.".".$total_minutes; //
			$total_office_hours = $hr_diff.".".$min_diff; // 9:30 Fixed
			$number_hr_daily = $total_number_of_hours; // TOTAL HOURS DAILY!
			$start_halfday = 660; 
			$end_halfday = 750;

			$objWorkSheet->SetCellValue('A'.$row,$value->employee_number);
			$objWorkSheet->SetCellValue('B'.$row,$value->name);
			$objWorkSheet->SetCellValue('C'.$row,$value->dates);
			$objWorkSheet->SetCellValue('D'.$row,$date_in);
			$objWorkSheet->SetCellValue('E'.$row,$date_out);

			//DAILY HOURS
			if($week_date >= 1 && $week_date <= 4)
			{
				if($date_date_in == $date_date_out && $total_in_daily == 0 && $total_out_daily == 0)
				{
					$objWorkSheet->SetCellValue('F'.$row," ");
				}
				elseif($total_in_min_grace >= $total_in_daily && $total_out_daily >= $total_out_min)
				{
					$total_min_diff = intval($total_out_min - $total_in_min - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}			
				elseif($total_in_daily > $total_in_min_grace && $total_out_daily < $total_out_min)
				{
					$total_min_diff = intval($total_out_daily - $total_in_daily - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($total_in_daily > $total_in_min_grace )
				{
					$total_min_diff = intval($total_out_min - $total_in_daily - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($total_out_daily < $total_out_min)
				{
					$total_min_diff = intval($total_out_daily - $total_in_min - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($total_in_daily >= $total_in_min_grace)
				{
					$total_min_diff = intval($total_out_min - $total_in_daily - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$min_diff."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($total_in_daily <= $total_in_min_grace)
				{
					$total_min_diff = intval($total_out_min - $total_in_min - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
			}
			elseif($week_date == 5)
			{
				if($date_date_in == $date_date_out && $total_in_daily == 0 && $total_out_daily == 0)
				{
					$objWorkSheet->SetCellValue('F'.$row," ");
				}
				elseif($date_date_in == $date_date_out && $total_in_min_grace >= $total_in_daily && $total_out_daily >= $total_friday_out_min)
				{
					$total_min_diff = intval($total_friday_out_min - $total_in_min );
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");

				}	
				elseif($date_date_in == $date_date_out && $total_in_daily > $total_in_min_grace && $total_out_daily < $total_friday_out_min)
				{
					$total_min_diff = intval($total_out_daily - $total_in_daily);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($date_date_in == $date_date_out && $total_in_daily > $total_in_min_grace )
				{
					$total_min_diff = intval($total_friday_out_min - $total_in_daily);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($date_date_in == $date_date_out && $total_out_daily < $total_friday_out_min)
				{
					$total_min_diff = intval($total_out_daily - $total_in_min);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($date_date_in != $date_date_out && $total_in_daily >= $total_in_min_grace)
				{
					$total_min_diff = intval($total_friday_out_min - $total_in_daily);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($date_date_in != $date_date_out || $total_in_daily <= $total_in_min_grace)
				{
					$total_min_diff = intval($total_friday_out_min - $total_in_min);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
			}
			elseif($week_date == 6)
			{
				$objWorkSheet->SetCellValue('F'.$row,"SATURDAY");
			}
			elseif($week_date == 0)
			{
				$objWorkSheet->SetCellValue('F'.$row,"SUNDAY");
			}
			else
			{
				$objWorkSheet->SetCellValue('F'.$row," ");
			}

			//TARDINESS
			if($week_date == 1 || $week_date == 2 || $week_date == 3 || $week_date == 4)
			{
				if($start_halfday <= $total_in_daily)
				{
					$objWorkSheet->SetCellValue('G'.$row," ");
				}
				else
				{
					if($total_in_daily > $total_in_min_grace)
					{
						$late_hr = intval($total_in_daily - $total_in_min);
						$hr_diff = intval($late_hr/60);
						$min_diff = intval($late_hr%60);
						//echo $hr_diff.".".$min_diff."";
						$minn = sprintf("%02d", $min_diff);
						$number_of_late = $hr_diff.".".$minn;
						$objWorkSheet->SetCellValue('G'.$row,$hr_diff.".".$minn."");
						$hr_lte = $late_hr;
					}
					else
					{
						$objWorkSheet->SetCellValue('G'.$row," ");
					}
				}
			}	
			elseif($week_date == 5)
			{
				if($start_halfday <= $total_in_daily)
				{
					$objWorkSheet->SetCellValue('G'.$row," ");
				}
				else
				{
					if($total_in_daily > $total_in_min_grace)
					{
						$late_hr = intval($total_in_daily - $total_in_min);
						$hr_diff = intval($late_hr/60);
						$min_diff = intval($late_hr%60);
						$objWorkSheet->SetCellValue('G'.$row,$hr_diff.".".$min_diff."");
						$minn = sprintf("%02d", $min_diff);
					}
					else
					{
						$objWorkSheet->SetCellValue('G'.$row," ");
					}
				}
				
			}
			else
			{
				$objWorkSheet->SetCellValue('G'.$row," ");
			}
			
			//UNDERTIME
			$halfday_in = 810;
			if($week_date == 1 || $week_date == 2 || $week_date == 3 || $week_date == 4)
			{
				if($date_date_in == $date_date_out && $total_out_daily < $halfday_in)
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
				elseif($total_out_min <= $total_out_daily)
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
				elseif($total_out_daily > $halfday_in)
				{
					$undertime_hr = intval($total_out_min - $total_out_daily);
					$hr_diff = intval($undertime_hr/60);
					$min_diff = intval($undertime_hr%60);
					$minn = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('H'.$row,$hr_diff.".".$minn);
				}
				else
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
			}
			elseif($week_date == 5)
			{
				if($date_date_in == $date_date_out && $total_out_daily < $halfday_in)
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
				elseif($total_friday_out_min <= $total_out_daily)
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
				elseif($date_date_in == $date_date_out && $halfday_in < $total_out_daily)
				{
					$undertime_hr = intval($total_friday_out_min - $total_out_daily);
					$hr_diff = intval($undertime_hr/60);
					$min_diff = intval($undertime_hr%60);
					$minn = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('H'.$row,$hr_diff.".".$minn);
				}
				else
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
			}
			else
			{
				$objWorkSheet->SetCellValue('H'.$row," ");
			}

			//REGULAR OT
			foreach($data['rots'] as $key => $value1)
			{
				if($value1->ot_employee_number == $value->employee_number && $value1->date_ot == $value->dates)
				{
					$reg_ot = $value1->total_ot;
					$hr_diff = intval($reg_ot/60);
					$min_diff = intval($reg_ot%60);
					$min_diff1 = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('I'.$row,$hr_diff.".".$min_diff1);
				}
			}	

			//LEGAL HOLIDAY OT
			foreach($data['lots'] as $key => $value2)
			{
				if($value2->legal_ot_employee_number == $value->employee_number && $value2->date_ot == $value->dates)
				{
					$reg_ot = $value2->total_ot;
					$hr_diff = intval($reg_ot/60);
					$min_diff = intval($reg_ot%60);
					$min_diff1 = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('J'.$row,$hr_diff.".".$min_diff1);
				}
			}

			//SPECIAL HOLIDAY OT
			foreach($data['shots'] as $key => $value3)
			{
				if($value3->special_ot_employee_number == $value->employee_number && $value3->date_ot == $value->dates)
				{
					$reg_ot = $value3->total_ot;
					$hr_diff = intval($reg_ot/60);
					$min_diff = intval($reg_ot%60);
					$min_diff1 = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('K'.$row,$hr_diff.".".$min_diff1);
				}
			}	

			//REST DAY OT
			foreach($data['rdots'] as $key => $value4)
			{
				if($value4->restday_ot_employee_number == $value->employee_number && $value4->date_ot == $value->dates)
				{
					$reg_ot = $value4->total_ot;
					$hr_diff = intval($reg_ot/60);
					$min_diff = intval($reg_ot%60);
					$min_diff1 = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('L'.$row,$hr_diff.".".$min_diff1);
				}
			}	

			//NIGHT DIFF
			$set_night_diff_morning = '6:00';
			$explode_night_diff_morning = explode(':', $set_night_diff_morning);
			$night_diff_morning = intval($explode_night_diff_morning[0]*60);
			$compute_night_diff_morning =$night_diff_morning - $total_in_daily;
			if($total_in_daily < $night_diff_morning)
			{
				$compute_night_diff_morning;
				$hr_diff = intval($compute_night_diff_morning/60);
				$min_diff = intval($compute_night_diff_morning%60);
				if($total_in_daily == 0 && $total_out_daily == 0)
				{
					$nd = ' ';
					//echo 0;
					$objWorkSheet->SetCellValue('M'.$row, $nd);
				}
				elseif($min_diff > 30 || $min_diff == 0)
				{
					$nd = $hr_diff."."."30";
					//echo $hr_diff."."."30";
					$objWorkSheet->SetCellValue('M'.$row, $nd);
				}
				elseif($min_diff < 30)
				{
					$nd = $hr_diff;
					$objWorkSheet->SetCellValue('M'.$row, $nd);
					//echo $hr_diff;
				}
			}
			else
			{
				$nd = ' ';
				$objWorkSheet->SetCellValue('M'.$row, $nd);
				//echo 0;
			}

			//SICK LEAVE
			foreach($data['sl'] as $key => $value5)
			{
				if($value5->slvl_employee_number == $value->employee_number && $value5->sl_date == $value->dates)
				{
					$sl_per_day = $value5->total_slvl;
					$objWorkSheet->SetCellValue('N'.$row,$sl_per_day);
				}
			}

			//VACATION LEAVE
			foreach($data['vl'] as $key => $value6)
			{
				if($value6->slvl_employee_number == $value->employee_number && $value6->vl_date == $value->dates)
				{
					$vl_per_day = $value6->total_slvl;
					$objWorkSheet->SetCellValue('O'.$row,$vl_per_day);
				}
			}

			//ABSENCES
			foreach($data['ab'] as $key => $value7)
			{
				if($value7->slvl_employee_number == $value->employee_number && $value7->ab_date == $value->dates && $value7->slvl_type == 'AB')
				{
					$ab_per_day = $value7->total_slvl;
					$reg_ab = $ab_per_day;
					$hr_diff = intval($reg_ab/60);
					$min_diff = intval($reg_ab%60);
					$min_diff1 = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('P'.$row,$hr_diff.".".$min_diff1);
				}
			}

			//REMARKS
			foreach($data['remarks'] as $key => $value8)
			{
				if($value8->date == $value->dates && $value8->remarks_employee_number == $value->employee_number)
    		{
    			$remarkss = $value8->type_name;
    			$objWorkSheet->SetCellValue('Q'.$row,$remarkss);
    		}
			}

			$row++;
		}	
    $objWorkSheet->setTitle("LA UNION");


    $i++;
    
    }

    ///////////////////////////////////////////////////// LEGAZPI \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

    $i=0;
    while ($i < 1) {

      // Add new sheet
    $objWorkSheet = $objPHPExcel->createSheet($i); //Setting index when creating

	
		foreach(range('A','Q') as $columnID) {
    $objWorkSheet->getColumnDimension($columnID)
        ->setAutoSize(true);
		} 
		
		$objWorkSheet->SetCellValue('A1','EMPLOYEE NUMBER'); 
		$objWorkSheet->SetCellValue('B1','EMPLOYEE NAME');
		$objWorkSheet->SetCellValue('C1','DATES');
		$objWorkSheet->SetCellValue('D1','TIME IN');
		$objWorkSheet->SetCellValue('E1','TIME OUT');
		$objWorkSheet->SetCellValue('F1','Daily Hours');
		$objWorkSheet->SetCellValue('G1','TARDINESS');
		$objWorkSheet->SetCellValue('H1','UNDERTIME');
		$objWorkSheet->SetCellValue('I1','REGULAR OT');
		$objWorkSheet->SetCellValue('J1','LEGAL HOLIDAY OT');
		$objWorkSheet->SetCellValue('K1','SPECIAL HOLIDAY OT');
		$objWorkSheet->SetCellValue('L1','RESTDAY OT');
		$objWorkSheet->SetCellValue('M1','NSD');
		$objWorkSheet->SetCellValue('N1','SICK LEAVE');
		$objWorkSheet->SetCellValue('O1','VACATION LEAVE');
		$objWorkSheet->SetCellValue('P1','ABSENCES');
		$objWorkSheet->SetCellValue('Q1','REMARKS');

		$objWorkSheet->SetCellValue('A2',' ');
		$objWorkSheet->SetCellValue('B2',' ');
		$objWorkSheet->SetCellValue('C2',' ');
		$objWorkSheet->SetCellValue('D2',' ');
		$objWorkSheet->SetCellValue('E2',' ');
		$objWorkSheet->SetCellValue('F2','HOURS.MINS');
		$objWorkSheet->SetCellValue('G2','HOURS.MINS');
		$objWorkSheet->SetCellValue('H2','HOURS.MINS');
		$objWorkSheet->SetCellValue('I2','HOURS.MINS');
		$objWorkSheet->SetCellValue('J2','HOURS.MINS');
		$objWorkSheet->SetCellValue('K2','HOURS.MINS');
		$objWorkSheet->SetCellValue('L2','HOURS.MINS');
		$objWorkSheet->SetCellValue('M2','HOURS.MINS');
		$objWorkSheet->SetCellValue('N2','DAYS');
		$objWorkSheet->SetCellValue('O2','DAYS');
		$objWorkSheet->SetCellValue('P2','HOURS.MINS');
		$objWorkSheet->SetCellValue('Q2',' ');

		$row = 3;

		foreach($data['employeesLegazpi'] as $key => $value)
		{
			$in_office	= $data['schedules']->daily_in; 
			$out_office   = $data['schedules']->daily_out;
			$friday_out = $data['schedules']->daily_friday_out;
			$night_diff = '22:00';
			$in_daily = $value->intime;
			$out_daily = $value->outtime;
			$week_date = date('w', strtotime($value->dates)); // Convert in days . friday (5)

			// EXPLODE DATE IN TIME IN / TIME OUT
			$explode_in_date_daily = explode(" ", $in_daily);
			$explode_out_date_daily = explode(" ", $out_daily);
			$date_date_in = $explode_in_date_daily[0];
			$date_date_out = $explode_out_date_daily[0];
			$date_in = $explode_in_date_daily[1];
			$date_out = $explode_out_date_daily[1];

			//NIGHT DIFF
			$explode_night_diff = explode(":", $night_diff);
			$night_diff_hr = $explode_night_diff[0]; 
			$night_diff_min = $explode_night_diff[1]; 
			$total_night_diff = intval($night_diff_hr*60) + $night_diff_min; // total night diff

			// EXPLODE IN AND OUT 
			$explode_in_office = explode(":", $in_office);
			$explode_out_office = explode(":", $out_office);
			$explode_friday_out_office = explode(":", $friday_out);
			$explode_in_daily = explode(":", $date_in);
			$explode_out_daily = explode(":", $date_out);
			$time_in_hr_daily = $explode_in_daily[0];
			$time_in_min_daily = $explode_in_daily[1];
			$time_out_hr_daily = $explode_out_daily[0];
			$time_out_min_daily = $explode_out_daily[1];
			$time_in_hr = $explode_in_office[0];
			$time_in_min = $explode_in_office[1];
			$time_out_hr = $explode_out_office[0];
			$time_out_min = $explode_out_office[1];
			$time_friday_out_hr = $explode_friday_out_office[0];
			$time_friday_out_min = $explode_friday_out_office[1];


			// Convert IN AND OUT
			$total_in_min = intval($time_in_hr*60) + $time_in_min; // DEFAULT IN
			$total_in_min_grace = intval($time_in_hr*60) + $time_in_min + 15; // DEFAULT IN WITH GRACE PERIOD!
			$total_out_min = intval($time_out_hr*60) + $time_out_min; // DEFAULT OUT
			$total_friday_out_min = intval($time_friday_out_hr*60) + $time_friday_out_min; // DEFAULT OUT IN FRIDAY
			$total_in_daily = intval($time_in_hr_daily*60) + $time_in_min_daily; // EMPLOYEE IN
			$total_out_daily = intval($time_out_hr_daily*60) + $time_out_min_daily; // EMPLOYEE OUT

			//COMPUTATION IN OFFICE IN AND OUT
			$total_min_diff = intval($total_out_min - $total_in_min);
			$hr_diff = intval($total_min_diff/60);
			$min_diff = intval($total_min_diff%60);
			

			// IN AND OUT OF EMPLOYEE
			$in = strtotime($value->intime);
			$out   = strtotime($value->outtime);
			$diff  = $out - $in;

			//CONVERT OF IN AND OUT
			$hours = floor($diff / (60 * 60));
			$minutes = $diff - $hours * (60 * 60); 
			$total_minutes = floor( $minutes / 60 );
			
			// COMPUTATION OF IN AND OUT
			$total_number_of_hours = $hours.".".$total_minutes; //
			$total_office_hours = $hr_diff.".".$min_diff; // 9:30 Fixed
			$number_hr_daily = $total_number_of_hours; // TOTAL HOURS DAILY!
			$start_halfday = 660; 
			$end_halfday = 750;

			$objWorkSheet->SetCellValue('A'.$row,$value->employee_number);
			$objWorkSheet->SetCellValue('B'.$row,$value->name);
			$objWorkSheet->SetCellValue('C'.$row,$value->dates);
			$objWorkSheet->SetCellValue('D'.$row,$date_in);
			$objWorkSheet->SetCellValue('E'.$row,$date_out);

			//DAILY HOURS
			if($week_date >= 1 && $week_date <= 4)
			{
				if($date_date_in == $date_date_out && $total_in_daily == 0 && $total_out_daily == 0)
				{
					$objWorkSheet->SetCellValue('F'.$row," ");
				}
				elseif($total_in_min_grace >= $total_in_daily && $total_out_daily >= $total_out_min)
				{
					$total_min_diff = intval($total_out_min - $total_in_min - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}			
				elseif($total_in_daily > $total_in_min_grace && $total_out_daily < $total_out_min)
				{
					$total_min_diff = intval($total_out_daily - $total_in_daily - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($total_in_daily > $total_in_min_grace )
				{
					$total_min_diff = intval($total_out_min - $total_in_daily - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($total_out_daily < $total_out_min)
				{
					$total_min_diff = intval($total_out_daily - $total_in_min - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($total_in_daily >= $total_in_min_grace)
				{
					$total_min_diff = intval($total_out_min - $total_in_daily - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$min_diff."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($total_in_daily <= $total_in_min_grace)
				{
					$total_min_diff = intval($total_out_min - $total_in_min - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
			}
			elseif($week_date == 5)
			{
				if($date_date_in == $date_date_out && $total_in_daily == 0 && $total_out_daily == 0)
				{
					$objWorkSheet->SetCellValue('F'.$row," ");
				}
				elseif($date_date_in == $date_date_out && $total_in_min_grace >= $total_in_daily && $total_out_daily >= $total_friday_out_min)
				{
					$total_min_diff = intval($total_friday_out_min - $total_in_min );
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");

				}	
				elseif($date_date_in == $date_date_out && $total_in_daily > $total_in_min_grace && $total_out_daily < $total_friday_out_min)
				{
					$total_min_diff = intval($total_out_daily - $total_in_daily);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($date_date_in == $date_date_out && $total_in_daily > $total_in_min_grace )
				{
					$total_min_diff = intval($total_friday_out_min - $total_in_daily);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($date_date_in == $date_date_out && $total_out_daily < $total_friday_out_min)
				{
					$total_min_diff = intval($total_out_daily - $total_in_min);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($date_date_in != $date_date_out && $total_in_daily >= $total_in_min_grace)
				{
					$total_min_diff = intval($total_friday_out_min - $total_in_daily);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($date_date_in != $date_date_out || $total_in_daily <= $total_in_min_grace)
				{
					$total_min_diff = intval($total_friday_out_min - $total_in_min);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
			}
			elseif($week_date == 6)
			{
				$objWorkSheet->SetCellValue('F'.$row,"SATURDAY");
			}
			elseif($week_date == 0)
			{
				$objWorkSheet->SetCellValue('F'.$row,"SUNDAY");
			}
			else
			{
				$objWorkSheet->SetCellValue('F'.$row," ");
			}

			//TARDINESS
			if($week_date == 1 || $week_date == 2 || $week_date == 3 || $week_date == 4)
			{
				if($start_halfday <= $total_in_daily)
				{
					$objWorkSheet->SetCellValue('G'.$row," ");
				}
				else
				{
					if($total_in_daily > $total_in_min_grace)
					{
						$late_hr = intval($total_in_daily - $total_in_min);
						$hr_diff = intval($late_hr/60);
						$min_diff = intval($late_hr%60);
						//echo $hr_diff.".".$min_diff."";
						$minn = sprintf("%02d", $min_diff);
						$number_of_late = $hr_diff.".".$minn;
						$objWorkSheet->SetCellValue('G'.$row,$hr_diff.".".$minn."");
						$hr_lte = $late_hr;
					}
					else
					{
						$objWorkSheet->SetCellValue('G'.$row," ");
					}
				}
			}	
			elseif($week_date == 5)
			{
				if($start_halfday <= $total_in_daily)
				{
					$objWorkSheet->SetCellValue('G'.$row," ");
				}
				else
				{
					if($total_in_daily > $total_in_min_grace)
					{
						$late_hr = intval($total_in_daily - $total_in_min);
						$hr_diff = intval($late_hr/60);
						$min_diff = intval($late_hr%60);
						$objWorkSheet->SetCellValue('G'.$row,$hr_diff.".".$min_diff."");
						$minn = sprintf("%02d", $min_diff);
					}
					else
					{
						$objWorkSheet->SetCellValue('G'.$row," ");
					}
				}
				
			}
			else
			{
				$objWorkSheet->SetCellValue('G'.$row," ");
			}
			
			//UNDERTIME
			$halfday_in = 810;
			if($week_date == 1 || $week_date == 2 || $week_date == 3 || $week_date == 4)
			{
				if($date_date_in == $date_date_out && $total_out_daily < $halfday_in)
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
				elseif($total_out_min <= $total_out_daily)
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
				elseif($total_out_daily > $halfday_in)
				{
					$undertime_hr = intval($total_out_min - $total_out_daily);
					$hr_diff = intval($undertime_hr/60);
					$min_diff = intval($undertime_hr%60);
					$minn = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('H'.$row,$hr_diff.".".$minn);
				}
				else
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
			}
			elseif($week_date == 5)
			{
				if($date_date_in == $date_date_out && $total_out_daily < $halfday_in)
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
				elseif($total_friday_out_min <= $total_out_daily)
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
				elseif($date_date_in == $date_date_out && $halfday_in < $total_out_daily)
				{
					$undertime_hr = intval($total_friday_out_min - $total_out_daily);
					$hr_diff = intval($undertime_hr/60);
					$min_diff = intval($undertime_hr%60);
					$minn = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('H'.$row,$hr_diff.".".$minn);
				}
				else
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
			}
			else
			{
				$objWorkSheet->SetCellValue('H'.$row," ");
			}

			//REGULAR OT
			foreach($data['rots'] as $key => $value1)
			{
				if($value1->ot_employee_number == $value->employee_number && $value1->date_ot == $value->dates)
				{
					$reg_ot = $value1->total_ot;
					$hr_diff = intval($reg_ot/60);
					$min_diff = intval($reg_ot%60);
					$min_diff1 = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('I'.$row,$hr_diff.".".$min_diff1);
				}
			}	

			//LEGAL HOLIDAY OT
			foreach($data['lots'] as $key => $value2)
			{
				if($value2->legal_ot_employee_number == $value->employee_number && $value2->date_ot == $value->dates)
				{
					$reg_ot = $value2->total_ot;
					$hr_diff = intval($reg_ot/60);
					$min_diff = intval($reg_ot%60);
					$min_diff1 = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('J'.$row,$hr_diff.".".$min_diff1);
				}
			}

			//SPECIAL HOLIDAY OT
			foreach($data['shots'] as $key => $value3)
			{
				if($value3->special_ot_employee_number == $value->employee_number && $value3->date_ot == $value->dates)
				{
					$reg_ot = $value3->total_ot;
					$hr_diff = intval($reg_ot/60);
					$min_diff = intval($reg_ot%60);
					$min_diff1 = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('K'.$row,$hr_diff.".".$min_diff1);
				}
			}	

			//REST DAY OT
			foreach($data['rdots'] as $key => $value4)
			{
				if($value4->restday_ot_employee_number == $value->employee_number && $value4->date_ot == $value->dates)
				{
					$reg_ot = $value4->total_ot;
					$hr_diff = intval($reg_ot/60);
					$min_diff = intval($reg_ot%60);
					$min_diff1 = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('L'.$row,$hr_diff.".".$min_diff1);
				}
			}	

			//NIGHT DIFF
			$set_night_diff_morning = '6:00';
			$explode_night_diff_morning = explode(':', $set_night_diff_morning);
			$night_diff_morning = intval($explode_night_diff_morning[0]*60);
			$compute_night_diff_morning =$night_diff_morning - $total_in_daily;
			if($total_in_daily < $night_diff_morning)
			{
				$compute_night_diff_morning;
				$hr_diff = intval($compute_night_diff_morning/60);
				$min_diff = intval($compute_night_diff_morning%60);
				if($total_in_daily == 0 && $total_out_daily == 0)
				{
					$nd = ' ';
					//echo 0;
					$objWorkSheet->SetCellValue('M'.$row, $nd);
				}
				elseif($min_diff > 30 || $min_diff == 0)
				{
					$nd = $hr_diff."."."30";
					//echo $hr_diff."."."30";
					$objWorkSheet->SetCellValue('M'.$row, $nd);
				}
				elseif($min_diff < 30)
				{
					$nd = $hr_diff;
					$objWorkSheet->SetCellValue('M'.$row, $nd);
					//echo $hr_diff;
				}
			}
			else
			{
				$nd = ' ';
				$objWorkSheet->SetCellValue('M'.$row, $nd);
				//echo 0;
			}

			//SICK LEAVE
			foreach($data['sl'] as $key => $value5)
			{
				if($value5->slvl_employee_number == $value->employee_number && $value5->sl_date == $value->dates)
				{
					$sl_per_day = $value5->total_slvl;
					$objWorkSheet->SetCellValue('N'.$row,$sl_per_day);
				}
			}

			//VACATION LEAVE
			foreach($data['vl'] as $key => $value6)
			{
				if($value6->slvl_employee_number == $value->employee_number && $value6->vl_date == $value->dates)
				{
					$vl_per_day = $value6->total_slvl;
					$objWorkSheet->SetCellValue('O'.$row,$vl_per_day);
				}
			}

			//ABSENCES
			foreach($data['ab'] as $key => $value7)
			{
				if($value7->slvl_employee_number == $value->employee_number && $value7->ab_date == $value->dates && $value7->slvl_type == 'AB')
				{
					$ab_per_day = $value7->total_slvl;
					$reg_ab = $ab_per_day;
					$hr_diff = intval($reg_ab/60);
					$min_diff = intval($reg_ab%60);
					$min_diff1 = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('P'.$row,$hr_diff.".".$min_diff1);
				}
			}

			//REMARKS
			foreach($data['remarks'] as $key => $value8)
			{
				if($value8->date == $value->dates && $value8->remarks_employee_number == $value->employee_number)
    		{
    			$remarkss = $value8->type_name;
    			$objWorkSheet->SetCellValue('Q'.$row,$remarkss);
    		}
			}

			$row++;
		}	
    $objWorkSheet->setTitle("LEGAZPI");


    $i++;
    
    }

    //////////////////////////////////////////////////// NAGA \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

    $i=0;
    while ($i < 1) {

      // Add new sheet
    $objWorkSheet = $objPHPExcel->createSheet($i); //Setting index when creating

	
		foreach(range('A','Q') as $columnID) {
    $objWorkSheet->getColumnDimension($columnID)
        ->setAutoSize(true);
		} 
		
		$objWorkSheet->SetCellValue('A1','EMPLOYEE NUMBER'); 
		$objWorkSheet->SetCellValue('B1','EMPLOYEE NAME');
		$objWorkSheet->SetCellValue('C1','DATES');
		$objWorkSheet->SetCellValue('D1','TIME IN');
		$objWorkSheet->SetCellValue('E1','TIME OUT');
		$objWorkSheet->SetCellValue('F1','Daily Hours');
		$objWorkSheet->SetCellValue('G1','TARDINESS');
		$objWorkSheet->SetCellValue('H1','UNDERTIME');
		$objWorkSheet->SetCellValue('I1','REGULAR OT');
		$objWorkSheet->SetCellValue('J1','LEGAL HOLIDAY OT');
		$objWorkSheet->SetCellValue('K1','SPECIAL HOLIDAY OT');
		$objWorkSheet->SetCellValue('L1','RESTDAY OT');
		$objWorkSheet->SetCellValue('M1','NSD');
		$objWorkSheet->SetCellValue('N1','SICK LEAVE');
		$objWorkSheet->SetCellValue('O1','VACATION LEAVE');
		$objWorkSheet->SetCellValue('P1','ABSENCES');
		$objWorkSheet->SetCellValue('Q1','REMARKS');

		$objWorkSheet->SetCellValue('A2',' ');
		$objWorkSheet->SetCellValue('B2',' ');
		$objWorkSheet->SetCellValue('C2',' ');
		$objWorkSheet->SetCellValue('D2',' ');
		$objWorkSheet->SetCellValue('E2',' ');
		$objWorkSheet->SetCellValue('F2','HOURS.MINS');
		$objWorkSheet->SetCellValue('G2','HOURS.MINS');
		$objWorkSheet->SetCellValue('H2','HOURS.MINS');
		$objWorkSheet->SetCellValue('I2','HOURS.MINS');
		$objWorkSheet->SetCellValue('J2','HOURS.MINS');
		$objWorkSheet->SetCellValue('K2','HOURS.MINS');
		$objWorkSheet->SetCellValue('L2','HOURS.MINS');
		$objWorkSheet->SetCellValue('M2','HOURS.MINS');
		$objWorkSheet->SetCellValue('N2','DAYS');
		$objWorkSheet->SetCellValue('O2','DAYS');
		$objWorkSheet->SetCellValue('P2','HOURS.MINS');
		$objWorkSheet->SetCellValue('Q2',' ');

		$row = 3;

		foreach($data['employeesNaga'] as $key => $value)
		{
			$in_office	= $data['schedules']->daily_in; 
			$out_office   = $data['schedules']->daily_out;
			$friday_out = $data['schedules']->daily_friday_out;
			$night_diff = '22:00';
			$in_daily = $value->intime;
			$out_daily = $value->outtime;
			$week_date = date('w', strtotime($value->dates)); // Convert in days . friday (5)

			// EXPLODE DATE IN TIME IN / TIME OUT
			$explode_in_date_daily = explode(" ", $in_daily);
			$explode_out_date_daily = explode(" ", $out_daily);
			$date_date_in = $explode_in_date_daily[0];
			$date_date_out = $explode_out_date_daily[0];
			$date_in = $explode_in_date_daily[1];
			$date_out = $explode_out_date_daily[1];

			//NIGHT DIFF
			$explode_night_diff = explode(":", $night_diff);
			$night_diff_hr = $explode_night_diff[0]; 
			$night_diff_min = $explode_night_diff[1]; 
			$total_night_diff = intval($night_diff_hr*60) + $night_diff_min; // total night diff

			// EXPLODE IN AND OUT 
			$explode_in_office = explode(":", $in_office);
			$explode_out_office = explode(":", $out_office);
			$explode_friday_out_office = explode(":", $friday_out);
			$explode_in_daily = explode(":", $date_in);
			$explode_out_daily = explode(":", $date_out);
			$time_in_hr_daily = $explode_in_daily[0];
			$time_in_min_daily = $explode_in_daily[1];
			$time_out_hr_daily = $explode_out_daily[0];
			$time_out_min_daily = $explode_out_daily[1];
			$time_in_hr = $explode_in_office[0];
			$time_in_min = $explode_in_office[1];
			$time_out_hr = $explode_out_office[0];
			$time_out_min = $explode_out_office[1];
			$time_friday_out_hr = $explode_friday_out_office[0];
			$time_friday_out_min = $explode_friday_out_office[1];


			// Convert IN AND OUT
			$total_in_min = intval($time_in_hr*60) + $time_in_min; // DEFAULT IN
			$total_in_min_grace = intval($time_in_hr*60) + $time_in_min + 15; // DEFAULT IN WITH GRACE PERIOD!
			$total_out_min = intval($time_out_hr*60) + $time_out_min; // DEFAULT OUT
			$total_friday_out_min = intval($time_friday_out_hr*60) + $time_friday_out_min; // DEFAULT OUT IN FRIDAY
			$total_in_daily = intval($time_in_hr_daily*60) + $time_in_min_daily; // EMPLOYEE IN
			$total_out_daily = intval($time_out_hr_daily*60) + $time_out_min_daily; // EMPLOYEE OUT

			//COMPUTATION IN OFFICE IN AND OUT
			$total_min_diff = intval($total_out_min - $total_in_min);
			$hr_diff = intval($total_min_diff/60);
			$min_diff = intval($total_min_diff%60);
			

			// IN AND OUT OF EMPLOYEE
			$in = strtotime($value->intime);
			$out   = strtotime($value->outtime);
			$diff  = $out - $in;

			//CONVERT OF IN AND OUT
			$hours = floor($diff / (60 * 60));
			$minutes = $diff - $hours * (60 * 60); 
			$total_minutes = floor( $minutes / 60 );
			
			// COMPUTATION OF IN AND OUT
			$total_number_of_hours = $hours.".".$total_minutes; //
			$total_office_hours = $hr_diff.".".$min_diff; // 9:30 Fixed
			$number_hr_daily = $total_number_of_hours; // TOTAL HOURS DAILY!
			$start_halfday = 660; 
			$end_halfday = 750;

			$objWorkSheet->SetCellValue('A'.$row,$value->employee_number);
			$objWorkSheet->SetCellValue('B'.$row,$value->name);
			$objWorkSheet->SetCellValue('C'.$row,$value->dates);
			$objWorkSheet->SetCellValue('D'.$row,$date_in);
			$objWorkSheet->SetCellValue('E'.$row,$date_out);

			//DAILY HOURS
			if($week_date >= 1 && $week_date <= 4)
			{
				if($date_date_in == $date_date_out && $total_in_daily == 0 && $total_out_daily == 0)
				{
					$objWorkSheet->SetCellValue('F'.$row," ");
				}
				elseif($total_in_min_grace >= $total_in_daily && $total_out_daily >= $total_out_min)
				{
					$total_min_diff = intval($total_out_min - $total_in_min - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}			
				elseif($total_in_daily > $total_in_min_grace && $total_out_daily < $total_out_min)
				{
					$total_min_diff = intval($total_out_daily - $total_in_daily - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($total_in_daily > $total_in_min_grace )
				{
					$total_min_diff = intval($total_out_min - $total_in_daily - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($total_out_daily < $total_out_min)
				{
					$total_min_diff = intval($total_out_daily - $total_in_min - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($total_in_daily >= $total_in_min_grace)
				{
					$total_min_diff = intval($total_out_min - $total_in_daily - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$min_diff."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($total_in_daily <= $total_in_min_grace)
				{
					$total_min_diff = intval($total_out_min - $total_in_min - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
			}
			elseif($week_date == 5)
			{
				if($date_date_in == $date_date_out && $total_in_daily == 0 && $total_out_daily == 0)
				{
					$objWorkSheet->SetCellValue('F'.$row," ");
				}
				elseif($date_date_in == $date_date_out && $total_in_min_grace >= $total_in_daily && $total_out_daily >= $total_friday_out_min)
				{
					$total_min_diff = intval($total_friday_out_min - $total_in_min );
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");

				}	
				elseif($date_date_in == $date_date_out && $total_in_daily > $total_in_min_grace && $total_out_daily < $total_friday_out_min)
				{
					$total_min_diff = intval($total_out_daily - $total_in_daily);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($date_date_in == $date_date_out && $total_in_daily > $total_in_min_grace )
				{
					$total_min_diff = intval($total_friday_out_min - $total_in_daily);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($date_date_in == $date_date_out && $total_out_daily < $total_friday_out_min)
				{
					$total_min_diff = intval($total_out_daily - $total_in_min);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($date_date_in != $date_date_out && $total_in_daily >= $total_in_min_grace)
				{
					$total_min_diff = intval($total_friday_out_min - $total_in_daily);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($date_date_in != $date_date_out || $total_in_daily <= $total_in_min_grace)
				{
					$total_min_diff = intval($total_friday_out_min - $total_in_min);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
			}
			elseif($week_date == 6)
			{
				$objWorkSheet->SetCellValue('F'.$row,"SATURDAY");
			}
			elseif($week_date == 0)
			{
				$objWorkSheet->SetCellValue('F'.$row,"SUNDAY");
			}
			else
			{
				$objWorkSheet->SetCellValue('F'.$row," ");
			}

			//TARDINESS
			if($week_date == 1 || $week_date == 2 || $week_date == 3 || $week_date == 4)
			{
				if($start_halfday <= $total_in_daily)
				{
					$objWorkSheet->SetCellValue('G'.$row," ");
				}
				else
				{
					if($total_in_daily > $total_in_min_grace)
					{
						$late_hr = intval($total_in_daily - $total_in_min);
						$hr_diff = intval($late_hr/60);
						$min_diff = intval($late_hr%60);
						//echo $hr_diff.".".$min_diff."";
						$minn = sprintf("%02d", $min_diff);
						$number_of_late = $hr_diff.".".$minn;
						$objWorkSheet->SetCellValue('G'.$row,$hr_diff.".".$minn."");
						$hr_lte = $late_hr;
					}
					else
					{
						$objWorkSheet->SetCellValue('G'.$row," ");
					}
				}
			}	
			elseif($week_date == 5)
			{
				if($start_halfday <= $total_in_daily)
				{
					$objWorkSheet->SetCellValue('G'.$row," ");
				}
				else
				{
					if($total_in_daily > $total_in_min_grace)
					{
						$late_hr = intval($total_in_daily - $total_in_min);
						$hr_diff = intval($late_hr/60);
						$min_diff = intval($late_hr%60);
						$objWorkSheet->SetCellValue('G'.$row,$hr_diff.".".$min_diff."");
						$minn = sprintf("%02d", $min_diff);
					}
					else
					{
						$objWorkSheet->SetCellValue('G'.$row," ");
					}
				}
				
			}
			else
			{
				$objWorkSheet->SetCellValue('G'.$row," ");
			}
			
			//UNDERTIME
			$halfday_in = 810;
			if($week_date == 1 || $week_date == 2 || $week_date == 3 || $week_date == 4)
			{
				if($date_date_in == $date_date_out && $total_out_daily < $halfday_in)
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
				elseif($total_out_min <= $total_out_daily)
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
				elseif($total_out_daily > $halfday_in)
				{
					$undertime_hr = intval($total_out_min - $total_out_daily);
					$hr_diff = intval($undertime_hr/60);
					$min_diff = intval($undertime_hr%60);
					$minn = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('H'.$row,$hr_diff.".".$minn);
				}
				else
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
			}
			elseif($week_date == 5)
			{
				if($date_date_in == $date_date_out && $total_out_daily < $halfday_in)
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
				elseif($total_friday_out_min <= $total_out_daily)
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
				elseif($date_date_in == $date_date_out && $halfday_in < $total_out_daily)
				{
					$undertime_hr = intval($total_friday_out_min - $total_out_daily);
					$hr_diff = intval($undertime_hr/60);
					$min_diff = intval($undertime_hr%60);
					$minn = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('H'.$row,$hr_diff.".".$minn);
				}
				else
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
			}
			else
			{
				$objWorkSheet->SetCellValue('H'.$row," ");
			}

			//REGULAR OT
			foreach($data['rots'] as $key => $value1)
			{
				if($value1->ot_employee_number == $value->employee_number && $value1->date_ot == $value->dates)
				{
					$reg_ot = $value1->total_ot;
					$hr_diff = intval($reg_ot/60);
					$min_diff = intval($reg_ot%60);
					$min_diff1 = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('I'.$row,$hr_diff.".".$min_diff1);
				}
			}	

			//LEGAL HOLIDAY OT
			foreach($data['lots'] as $key => $value2)
			{
				if($value2->legal_ot_employee_number == $value->employee_number && $value2->date_ot == $value->dates)
				{
					$reg_ot = $value2->total_ot;
					$hr_diff = intval($reg_ot/60);
					$min_diff = intval($reg_ot%60);
					$min_diff1 = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('J'.$row,$hr_diff.".".$min_diff1);
				}
			}

			//SPECIAL HOLIDAY OT
			foreach($data['shots'] as $key => $value3)
			{
				if($value3->special_ot_employee_number == $value->employee_number && $value3->date_ot == $value->dates)
				{
					$reg_ot = $value3->total_ot;
					$hr_diff = intval($reg_ot/60);
					$min_diff = intval($reg_ot%60);
					$min_diff1 = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('K'.$row,$hr_diff.".".$min_diff1);
				}
			}	

			//REST DAY OT
			foreach($data['rdots'] as $key => $value4)
			{
				if($value4->restday_ot_employee_number == $value->employee_number && $value4->date_ot == $value->dates)
				{
					$reg_ot = $value4->total_ot;
					$hr_diff = intval($reg_ot/60);
					$min_diff = intval($reg_ot%60);
					$min_diff1 = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('L'.$row,$hr_diff.".".$min_diff1);
				}
			}	

			//NIGHT DIFF
			$set_night_diff_morning = '6:00';
			$explode_night_diff_morning = explode(':', $set_night_diff_morning);
			$night_diff_morning = intval($explode_night_diff_morning[0]*60);
			$compute_night_diff_morning =$night_diff_morning - $total_in_daily;
			if($total_in_daily < $night_diff_morning)
			{
				$compute_night_diff_morning;
				$hr_diff = intval($compute_night_diff_morning/60);
				$min_diff = intval($compute_night_diff_morning%60);
				if($total_in_daily == 0 && $total_out_daily == 0)
				{
					$nd = ' ';
					//echo 0;
					$objWorkSheet->SetCellValue('M'.$row, $nd);
				}
				elseif($min_diff > 30 || $min_diff == 0)
				{
					$nd = $hr_diff."."."30";
					//echo $hr_diff."."."30";
					$objWorkSheet->SetCellValue('M'.$row, $nd);
				}
				elseif($min_diff < 30)
				{
					$nd = $hr_diff;
					$objWorkSheet->SetCellValue('M'.$row, $nd);
					//echo $hr_diff;
				}
			}
			else
			{
				$nd = ' ';
				$objWorkSheet->SetCellValue('M'.$row, $nd);
				//echo 0;
			}

			//SICK LEAVE
			foreach($data['sl'] as $key => $value5)
			{
				if($value5->slvl_employee_number == $value->employee_number && $value5->sl_date == $value->dates)
				{
					$sl_per_day = $value5->total_slvl;
					$objWorkSheet->SetCellValue('N'.$row,$sl_per_day);
				}
			}

			//VACATION LEAVE
			foreach($data['vl'] as $key => $value6)
			{
				if($value6->slvl_employee_number == $value->employee_number && $value6->vl_date == $value->dates)
				{
					$vl_per_day = $value6->total_slvl;
					$objWorkSheet->SetCellValue('O'.$row,$vl_per_day);
				}
			}

			//ABSENCES
			foreach($data['ab'] as $key => $value7)
			{
				if($value7->slvl_employee_number == $value->employee_number && $value7->ab_date == $value->dates && $value7->slvl_type == 'AB')
				{
					$ab_per_day = $value7->total_slvl;
					$reg_ab = $ab_per_day;
					$hr_diff = intval($reg_ab/60);
					$min_diff = intval($reg_ab%60);
					$min_diff1 = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('P'.$row,$hr_diff.".".$min_diff1);
				}
			}

			//REMARKS
			foreach($data['remarks'] as $key => $value8)
			{
				if($value8->date == $value->dates && $value8->remarks_employee_number == $value->employee_number)
    		{
    			$remarkss = $value8->type_name;
    			$objWorkSheet->SetCellValue('Q'.$row,$remarkss);
    		}
			}

			$row++;
		}	
    $objWorkSheet->setTitle("NAGA");


    $i++;
    
    }

    //////////////////////////////////////////////////////// NOVALICHES \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

    $i=0;
    while ($i < 1) {

      // Add new sheet
    $objWorkSheet = $objPHPExcel->createSheet($i); //Setting index when creating

	
		foreach(range('A','Q') as $columnID) {
    $objWorkSheet->getColumnDimension($columnID)
        ->setAutoSize(true);
		} 
		
		$objWorkSheet->SetCellValue('A1','EMPLOYEE NUMBER'); 
		$objWorkSheet->SetCellValue('B1','EMPLOYEE NAME');
		$objWorkSheet->SetCellValue('C1','DATES');
		$objWorkSheet->SetCellValue('D1','TIME IN');
		$objWorkSheet->SetCellValue('E1','TIME OUT');
		$objWorkSheet->SetCellValue('F1','Daily Hours');
		$objWorkSheet->SetCellValue('G1','TARDINESS');
		$objWorkSheet->SetCellValue('H1','UNDERTIME');
		$objWorkSheet->SetCellValue('I1','REGULAR OT');
		$objWorkSheet->SetCellValue('J1','LEGAL HOLIDAY OT');
		$objWorkSheet->SetCellValue('K1','SPECIAL HOLIDAY OT');
		$objWorkSheet->SetCellValue('L1','RESTDAY OT');
		$objWorkSheet->SetCellValue('M1','NSD');
		$objWorkSheet->SetCellValue('N1','SICK LEAVE');
		$objWorkSheet->SetCellValue('O1','VACATION LEAVE');
		$objWorkSheet->SetCellValue('P1','ABSENCES');
		$objWorkSheet->SetCellValue('Q1','REMARKS');

		$objWorkSheet->SetCellValue('A2',' ');
		$objWorkSheet->SetCellValue('B2',' ');
		$objWorkSheet->SetCellValue('C2',' ');
		$objWorkSheet->SetCellValue('D2',' ');
		$objWorkSheet->SetCellValue('E2',' ');
		$objWorkSheet->SetCellValue('F2','HOURS.MINS');
		$objWorkSheet->SetCellValue('G2','HOURS.MINS');
		$objWorkSheet->SetCellValue('H2','HOURS.MINS');
		$objWorkSheet->SetCellValue('I2','HOURS.MINS');
		$objWorkSheet->SetCellValue('J2','HOURS.MINS');
		$objWorkSheet->SetCellValue('K2','HOURS.MINS');
		$objWorkSheet->SetCellValue('L2','HOURS.MINS');
		$objWorkSheet->SetCellValue('M2','HOURS.MINS');
		$objWorkSheet->SetCellValue('N2','DAYS');
		$objWorkSheet->SetCellValue('O2','DAYS');
		$objWorkSheet->SetCellValue('P2','HOURS.MINS');
		$objWorkSheet->SetCellValue('Q2',' ');

		$row = 3;

		foreach($data['employeesNovaliches'] as $key => $value)
		{
			$in_office	= $data['schedules']->daily_in; 
			$out_office   = $data['schedules']->daily_out;
			$friday_out = $data['schedules']->daily_friday_out;
			$night_diff = '22:00';
			$in_daily = $value->intime;
			$out_daily = $value->outtime;
			$week_date = date('w', strtotime($value->dates)); // Convert in days . friday (5)

			// EXPLODE DATE IN TIME IN / TIME OUT
			$explode_in_date_daily = explode(" ", $in_daily);
			$explode_out_date_daily = explode(" ", $out_daily);
			$date_date_in = $explode_in_date_daily[0];
			$date_date_out = $explode_out_date_daily[0];
			$date_in = $explode_in_date_daily[1];
			$date_out = $explode_out_date_daily[1];

			//NIGHT DIFF
			$explode_night_diff = explode(":", $night_diff);
			$night_diff_hr = $explode_night_diff[0]; 
			$night_diff_min = $explode_night_diff[1]; 
			$total_night_diff = intval($night_diff_hr*60) + $night_diff_min; // total night diff

			// EXPLODE IN AND OUT 
			$explode_in_office = explode(":", $in_office);
			$explode_out_office = explode(":", $out_office);
			$explode_friday_out_office = explode(":", $friday_out);
			$explode_in_daily = explode(":", $date_in);
			$explode_out_daily = explode(":", $date_out);
			$time_in_hr_daily = $explode_in_daily[0];
			$time_in_min_daily = $explode_in_daily[1];
			$time_out_hr_daily = $explode_out_daily[0];
			$time_out_min_daily = $explode_out_daily[1];
			$time_in_hr = $explode_in_office[0];
			$time_in_min = $explode_in_office[1];
			$time_out_hr = $explode_out_office[0];
			$time_out_min = $explode_out_office[1];
			$time_friday_out_hr = $explode_friday_out_office[0];
			$time_friday_out_min = $explode_friday_out_office[1];


			// Convert IN AND OUT
			$total_in_min = intval($time_in_hr*60) + $time_in_min; // DEFAULT IN
			$total_in_min_grace = intval($time_in_hr*60) + $time_in_min + 15; // DEFAULT IN WITH GRACE PERIOD!
			$total_out_min = intval($time_out_hr*60) + $time_out_min; // DEFAULT OUT
			$total_friday_out_min = intval($time_friday_out_hr*60) + $time_friday_out_min; // DEFAULT OUT IN FRIDAY
			$total_in_daily = intval($time_in_hr_daily*60) + $time_in_min_daily; // EMPLOYEE IN
			$total_out_daily = intval($time_out_hr_daily*60) + $time_out_min_daily; // EMPLOYEE OUT

			//COMPUTATION IN OFFICE IN AND OUT
			$total_min_diff = intval($total_out_min - $total_in_min);
			$hr_diff = intval($total_min_diff/60);
			$min_diff = intval($total_min_diff%60);
			

			// IN AND OUT OF EMPLOYEE
			$in = strtotime($value->intime);
			$out   = strtotime($value->outtime);
			$diff  = $out - $in;

			//CONVERT OF IN AND OUT
			$hours = floor($diff / (60 * 60));
			$minutes = $diff - $hours * (60 * 60); 
			$total_minutes = floor( $minutes / 60 );
			
			// COMPUTATION OF IN AND OUT
			$total_number_of_hours = $hours.".".$total_minutes; //
			$total_office_hours = $hr_diff.".".$min_diff; // 9:30 Fixed
			$number_hr_daily = $total_number_of_hours; // TOTAL HOURS DAILY!
			$start_halfday = 660; 
			$end_halfday = 750;

			$objWorkSheet->SetCellValue('A'.$row,$value->employee_number);
			$objWorkSheet->SetCellValue('B'.$row,$value->name);
			$objWorkSheet->SetCellValue('C'.$row,$value->dates);
			$objWorkSheet->SetCellValue('D'.$row,$date_in);
			$objWorkSheet->SetCellValue('E'.$row,$date_out);

			//DAILY HOURS
			if($week_date >= 1 && $week_date <= 4)
			{
				if($date_date_in == $date_date_out && $total_in_daily == 0 && $total_out_daily == 0)
				{
					$objWorkSheet->SetCellValue('F'.$row," ");
				}
				elseif($total_in_min_grace >= $total_in_daily && $total_out_daily >= $total_out_min)
				{
					$total_min_diff = intval($total_out_min - $total_in_min - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}			
				elseif($total_in_daily > $total_in_min_grace && $total_out_daily < $total_out_min)
				{
					$total_min_diff = intval($total_out_daily - $total_in_daily - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($total_in_daily > $total_in_min_grace )
				{
					$total_min_diff = intval($total_out_min - $total_in_daily - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($total_out_daily < $total_out_min)
				{
					$total_min_diff = intval($total_out_daily - $total_in_min - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($total_in_daily >= $total_in_min_grace)
				{
					$total_min_diff = intval($total_out_min - $total_in_daily - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$min_diff."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($total_in_daily <= $total_in_min_grace)
				{
					$total_min_diff = intval($total_out_min - $total_in_min - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
			}
			elseif($week_date == 5)
			{
				if($date_date_in == $date_date_out && $total_in_daily == 0 && $total_out_daily == 0)
				{
					$objWorkSheet->SetCellValue('F'.$row," ");
				}
				elseif($date_date_in == $date_date_out && $total_in_min_grace >= $total_in_daily && $total_out_daily >= $total_friday_out_min)
				{
					$total_min_diff = intval($total_friday_out_min - $total_in_min );
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");

				}	
				elseif($date_date_in == $date_date_out && $total_in_daily > $total_in_min_grace && $total_out_daily < $total_friday_out_min)
				{
					$total_min_diff = intval($total_out_daily - $total_in_daily);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($date_date_in == $date_date_out && $total_in_daily > $total_in_min_grace )
				{
					$total_min_diff = intval($total_friday_out_min - $total_in_daily);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($date_date_in == $date_date_out && $total_out_daily < $total_friday_out_min)
				{
					$total_min_diff = intval($total_out_daily - $total_in_min);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($date_date_in != $date_date_out && $total_in_daily >= $total_in_min_grace)
				{
					$total_min_diff = intval($total_friday_out_min - $total_in_daily);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($date_date_in != $date_date_out || $total_in_daily <= $total_in_min_grace)
				{
					$total_min_diff = intval($total_friday_out_min - $total_in_min);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
			}
			elseif($week_date == 6)
			{
				$objWorkSheet->SetCellValue('F'.$row,"SATURDAY");
			}
			elseif($week_date == 0)
			{
				$objWorkSheet->SetCellValue('F'.$row,"SUNDAY");
			}
			else
			{
				$objWorkSheet->SetCellValue('F'.$row," ");
			}

			//TARDINESS
			if($week_date == 1 || $week_date == 2 || $week_date == 3 || $week_date == 4)
			{
				if($start_halfday <= $total_in_daily)
				{
					$objWorkSheet->SetCellValue('G'.$row," ");
				}
				else
				{
					if($total_in_daily > $total_in_min_grace)
					{
						$late_hr = intval($total_in_daily - $total_in_min);
						$hr_diff = intval($late_hr/60);
						$min_diff = intval($late_hr%60);
						//echo $hr_diff.".".$min_diff."";
						$minn = sprintf("%02d", $min_diff);
						$number_of_late = $hr_diff.".".$minn;
						$objWorkSheet->SetCellValue('G'.$row,$hr_diff.".".$minn."");
						$hr_lte = $late_hr;
					}
					else
					{
						$objWorkSheet->SetCellValue('G'.$row," ");
					}
				}
			}	
			elseif($week_date == 5)
			{
				if($start_halfday <= $total_in_daily)
				{
					$objWorkSheet->SetCellValue('G'.$row," ");
				}
				else
				{
					if($total_in_daily > $total_in_min_grace)
					{
						$late_hr = intval($total_in_daily - $total_in_min);
						$hr_diff = intval($late_hr/60);
						$min_diff = intval($late_hr%60);
						$objWorkSheet->SetCellValue('G'.$row,$hr_diff.".".$min_diff."");
						$minn = sprintf("%02d", $min_diff);
					}
					else
					{
						$objWorkSheet->SetCellValue('G'.$row," ");
					}
				}
				
			}
			else
			{
				$objWorkSheet->SetCellValue('G'.$row," ");
			}
			
			//UNDERTIME
			$halfday_in = 810;
			if($week_date == 1 || $week_date == 2 || $week_date == 3 || $week_date == 4)
			{
				if($date_date_in == $date_date_out && $total_out_daily < $halfday_in)
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
				elseif($total_out_min <= $total_out_daily)
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
				elseif($total_out_daily > $halfday_in)
				{
					$undertime_hr = intval($total_out_min - $total_out_daily);
					$hr_diff = intval($undertime_hr/60);
					$min_diff = intval($undertime_hr%60);
					$minn = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('H'.$row,$hr_diff.".".$minn);
				}
				else
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
			}
			elseif($week_date == 5)
			{
				if($date_date_in == $date_date_out && $total_out_daily < $halfday_in)
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
				elseif($total_friday_out_min <= $total_out_daily)
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
				elseif($date_date_in == $date_date_out && $halfday_in < $total_out_daily)
				{
					$undertime_hr = intval($total_friday_out_min - $total_out_daily);
					$hr_diff = intval($undertime_hr/60);
					$min_diff = intval($undertime_hr%60);
					$minn = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('H'.$row,$hr_diff.".".$minn);
				}
				else
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
			}
			else
			{
				$objWorkSheet->SetCellValue('H'.$row," ");
			}

			//REGULAR OT
			foreach($data['rots'] as $key => $value1)
			{
				if($value1->ot_employee_number == $value->employee_number && $value1->date_ot == $value->dates)
				{
					$reg_ot = $value1->total_ot;
					$hr_diff = intval($reg_ot/60);
					$min_diff = intval($reg_ot%60);
					$min_diff1 = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('I'.$row,$hr_diff.".".$min_diff1);
				}
			}	

			//LEGAL HOLIDAY OT
			foreach($data['lots'] as $key => $value2)
			{
				if($value2->legal_ot_employee_number == $value->employee_number && $value2->date_ot == $value->dates)
				{
					$reg_ot = $value2->total_ot;
					$hr_diff = intval($reg_ot/60);
					$min_diff = intval($reg_ot%60);
					$min_diff1 = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('J'.$row,$hr_diff.".".$min_diff1);
				}
			}

			//SPECIAL HOLIDAY OT
			foreach($data['shots'] as $key => $value3)
			{
				if($value3->special_ot_employee_number == $value->employee_number && $value3->date_ot == $value->dates)
				{
					$reg_ot = $value3->total_ot;
					$hr_diff = intval($reg_ot/60);
					$min_diff = intval($reg_ot%60);
					$min_diff1 = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('K'.$row,$hr_diff.".".$min_diff1);
				}
			}	

			//REST DAY OT
			foreach($data['rdots'] as $key => $value4)
			{
				if($value4->restday_ot_employee_number == $value->employee_number && $value4->date_ot == $value->dates)
				{
					$reg_ot = $value4->total_ot;
					$hr_diff = intval($reg_ot/60);
					$min_diff = intval($reg_ot%60);
					$min_diff1 = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('L'.$row,$hr_diff.".".$min_diff1);
				}
			}	

			//NIGHT DIFF
			$set_night_diff_morning = '6:00';
			$explode_night_diff_morning = explode(':', $set_night_diff_morning);
			$night_diff_morning = intval($explode_night_diff_morning[0]*60);
			$compute_night_diff_morning =$night_diff_morning - $total_in_daily;
			if($total_in_daily < $night_diff_morning)
			{
				$compute_night_diff_morning;
				$hr_diff = intval($compute_night_diff_morning/60);
				$min_diff = intval($compute_night_diff_morning%60);
				if($total_in_daily == 0 && $total_out_daily == 0)
				{
					$nd = ' ';
					//echo 0;
					$objWorkSheet->SetCellValue('M'.$row, $nd);
				}
				elseif($min_diff > 30 || $min_diff == 0)
				{
					$nd = $hr_diff."."."30";
					//echo $hr_diff."."."30";
					$objWorkSheet->SetCellValue('M'.$row, $nd);
				}
				elseif($min_diff < 30)
				{
					$nd = $hr_diff;
					$objWorkSheet->SetCellValue('M'.$row, $nd);
					//echo $hr_diff;
				}
			}
			else
			{
				$nd = ' ';
				$objWorkSheet->SetCellValue('M'.$row, $nd);
				//echo 0;
			}

			//SICK LEAVE
			foreach($data['sl'] as $key => $value5)
			{
				if($value5->slvl_employee_number == $value->employee_number && $value5->sl_date == $value->dates)
				{
					$sl_per_day = $value5->total_slvl;
					$objWorkSheet->SetCellValue('N'.$row,$sl_per_day);
				}
			}

			//VACATION LEAVE
			foreach($data['vl'] as $key => $value6)
			{
				if($value6->slvl_employee_number == $value->employee_number && $value6->vl_date == $value->dates)
				{
					$vl_per_day = $value6->total_slvl;
					$objWorkSheet->SetCellValue('O'.$row,$vl_per_day);
				}
			}

			//ABSENCES
			foreach($data['ab'] as $key => $value7)
			{
				if($value7->slvl_employee_number == $value->employee_number && $value7->ab_date == $value->dates && $value7->slvl_type == 'AB')
				{
					$ab_per_day = $value7->total_slvl;
					$reg_ab = $ab_per_day;
					$hr_diff = intval($reg_ab/60);
					$min_diff = intval($reg_ab%60);
					$min_diff1 = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('P'.$row,$hr_diff.".".$min_diff1);
				}
			}

			//REMARKS
			foreach($data['remarks'] as $key => $value8)
			{
				if($value8->date == $value->dates && $value8->remarks_employee_number == $value->employee_number)
    		{
    			$remarkss = $value8->type_name;
    			$objWorkSheet->SetCellValue('Q'.$row,$remarkss);
    		}
			}

			$row++;
		}	
    $objWorkSheet->setTitle("NOVALICHES");


    $i++;
    
    }

    /////////////////////////////////////////////////////// ROXAS \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

    $i=0;
    while ($i < 1) {

      // Add new sheet
    $objWorkSheet = $objPHPExcel->createSheet($i); //Setting index when creating

	
		foreach(range('A','Q') as $columnID) {
    $objWorkSheet->getColumnDimension($columnID)
        ->setAutoSize(true);
		} 
		
		$objWorkSheet->SetCellValue('A1','EMPLOYEE NUMBER'); 
		$objWorkSheet->SetCellValue('B1','EMPLOYEE NAME');
		$objWorkSheet->SetCellValue('C1','DATES');
		$objWorkSheet->SetCellValue('D1','TIME IN');
		$objWorkSheet->SetCellValue('E1','TIME OUT');
		$objWorkSheet->SetCellValue('F1','Daily Hours');
		$objWorkSheet->SetCellValue('G1','TARDINESS');
		$objWorkSheet->SetCellValue('H1','UNDERTIME');
		$objWorkSheet->SetCellValue('I1','REGULAR OT');
		$objWorkSheet->SetCellValue('J1','LEGAL HOLIDAY OT');
		$objWorkSheet->SetCellValue('K1','SPECIAL HOLIDAY OT');
		$objWorkSheet->SetCellValue('L1','RESTDAY OT');
		$objWorkSheet->SetCellValue('M1','NSD');
		$objWorkSheet->SetCellValue('N1','SICK LEAVE');
		$objWorkSheet->SetCellValue('O1','VACATION LEAVE');
		$objWorkSheet->SetCellValue('P1','ABSENCES');
		$objWorkSheet->SetCellValue('Q1','REMARKS');

		$objWorkSheet->SetCellValue('A2',' ');
		$objWorkSheet->SetCellValue('B2',' ');
		$objWorkSheet->SetCellValue('C2',' ');
		$objWorkSheet->SetCellValue('D2',' ');
		$objWorkSheet->SetCellValue('E2',' ');
		$objWorkSheet->SetCellValue('F2','HOURS.MINS');
		$objWorkSheet->SetCellValue('G2','HOURS.MINS');
		$objWorkSheet->SetCellValue('H2','HOURS.MINS');
		$objWorkSheet->SetCellValue('I2','HOURS.MINS');
		$objWorkSheet->SetCellValue('J2','HOURS.MINS');
		$objWorkSheet->SetCellValue('K2','HOURS.MINS');
		$objWorkSheet->SetCellValue('L2','HOURS.MINS');
		$objWorkSheet->SetCellValue('M2','HOURS.MINS');
		$objWorkSheet->SetCellValue('N2','DAYS');
		$objWorkSheet->SetCellValue('O2','DAYS');
		$objWorkSheet->SetCellValue('P2','HOURS.MINS');
		$objWorkSheet->SetCellValue('Q2',' ');

		$row = 3;

		foreach($data['employeesRoxas'] as $key => $value)
		{
			$in_office	= $data['schedules']->daily_in; 
			$out_office   = $data['schedules']->daily_out;
			$friday_out = $data['schedules']->daily_friday_out;
			$night_diff = '22:00';
			$in_daily = $value->intime;
			$out_daily = $value->outtime;
			$week_date = date('w', strtotime($value->dates)); // Convert in days . friday (5)

			// EXPLODE DATE IN TIME IN / TIME OUT
			$explode_in_date_daily = explode(" ", $in_daily);
			$explode_out_date_daily = explode(" ", $out_daily);
			$date_date_in = $explode_in_date_daily[0];
			$date_date_out = $explode_out_date_daily[0];
			$date_in = $explode_in_date_daily[1];
			$date_out = $explode_out_date_daily[1];

			//NIGHT DIFF
			$explode_night_diff = explode(":", $night_diff);
			$night_diff_hr = $explode_night_diff[0]; 
			$night_diff_min = $explode_night_diff[1]; 
			$total_night_diff = intval($night_diff_hr*60) + $night_diff_min; // total night diff

			// EXPLODE IN AND OUT 
			$explode_in_office = explode(":", $in_office);
			$explode_out_office = explode(":", $out_office);
			$explode_friday_out_office = explode(":", $friday_out);
			$explode_in_daily = explode(":", $date_in);
			$explode_out_daily = explode(":", $date_out);
			$time_in_hr_daily = $explode_in_daily[0];
			$time_in_min_daily = $explode_in_daily[1];
			$time_out_hr_daily = $explode_out_daily[0];
			$time_out_min_daily = $explode_out_daily[1];
			$time_in_hr = $explode_in_office[0];
			$time_in_min = $explode_in_office[1];
			$time_out_hr = $explode_out_office[0];
			$time_out_min = $explode_out_office[1];
			$time_friday_out_hr = $explode_friday_out_office[0];
			$time_friday_out_min = $explode_friday_out_office[1];


			// Convert IN AND OUT
			$total_in_min = intval($time_in_hr*60) + $time_in_min; // DEFAULT IN
			$total_in_min_grace = intval($time_in_hr*60) + $time_in_min + 15; // DEFAULT IN WITH GRACE PERIOD!
			$total_out_min = intval($time_out_hr*60) + $time_out_min; // DEFAULT OUT
			$total_friday_out_min = intval($time_friday_out_hr*60) + $time_friday_out_min; // DEFAULT OUT IN FRIDAY
			$total_in_daily = intval($time_in_hr_daily*60) + $time_in_min_daily; // EMPLOYEE IN
			$total_out_daily = intval($time_out_hr_daily*60) + $time_out_min_daily; // EMPLOYEE OUT

			//COMPUTATION IN OFFICE IN AND OUT
			$total_min_diff = intval($total_out_min - $total_in_min);
			$hr_diff = intval($total_min_diff/60);
			$min_diff = intval($total_min_diff%60);
			

			// IN AND OUT OF EMPLOYEE
			$in = strtotime($value->intime);
			$out   = strtotime($value->outtime);
			$diff  = $out - $in;

			//CONVERT OF IN AND OUT
			$hours = floor($diff / (60 * 60));
			$minutes = $diff - $hours * (60 * 60); 
			$total_minutes = floor( $minutes / 60 );
			
			// COMPUTATION OF IN AND OUT
			$total_number_of_hours = $hours.".".$total_minutes; //
			$total_office_hours = $hr_diff.".".$min_diff; // 9:30 Fixed
			$number_hr_daily = $total_number_of_hours; // TOTAL HOURS DAILY!
			$start_halfday = 660; 
			$end_halfday = 750;

			$objWorkSheet->SetCellValue('A'.$row,$value->employee_number);
			$objWorkSheet->SetCellValue('B'.$row,$value->name);
			$objWorkSheet->SetCellValue('C'.$row,$value->dates);
			$objWorkSheet->SetCellValue('D'.$row,$date_in);
			$objWorkSheet->SetCellValue('E'.$row,$date_out);

			//DAILY HOURS
			if($week_date >= 1 && $week_date <= 4)
			{
				if($date_date_in == $date_date_out && $total_in_daily == 0 && $total_out_daily == 0)
				{
					$objWorkSheet->SetCellValue('F'.$row," ");
				}
				elseif($total_in_min_grace >= $total_in_daily && $total_out_daily >= $total_out_min)
				{
					$total_min_diff = intval($total_out_min - $total_in_min - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}			
				elseif($total_in_daily > $total_in_min_grace && $total_out_daily < $total_out_min)
				{
					$total_min_diff = intval($total_out_daily - $total_in_daily - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($total_in_daily > $total_in_min_grace )
				{
					$total_min_diff = intval($total_out_min - $total_in_daily - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($total_out_daily < $total_out_min)
				{
					$total_min_diff = intval($total_out_daily - $total_in_min - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($total_in_daily >= $total_in_min_grace)
				{
					$total_min_diff = intval($total_out_min - $total_in_daily - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$min_diff."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($total_in_daily <= $total_in_min_grace)
				{
					$total_min_diff = intval($total_out_min - $total_in_min - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
			}
			elseif($week_date == 5)
			{
				if($date_date_in == $date_date_out && $total_in_daily == 0 && $total_out_daily == 0)
				{
					$objWorkSheet->SetCellValue('F'.$row," ");
				}
				elseif($date_date_in == $date_date_out && $total_in_min_grace >= $total_in_daily && $total_out_daily >= $total_friday_out_min)
				{
					$total_min_diff = intval($total_friday_out_min - $total_in_min );
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");

				}	
				elseif($date_date_in == $date_date_out && $total_in_daily > $total_in_min_grace && $total_out_daily < $total_friday_out_min)
				{
					$total_min_diff = intval($total_out_daily - $total_in_daily);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($date_date_in == $date_date_out && $total_in_daily > $total_in_min_grace )
				{
					$total_min_diff = intval($total_friday_out_min - $total_in_daily);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($date_date_in == $date_date_out && $total_out_daily < $total_friday_out_min)
				{
					$total_min_diff = intval($total_out_daily - $total_in_min);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($date_date_in != $date_date_out && $total_in_daily >= $total_in_min_grace)
				{
					$total_min_diff = intval($total_friday_out_min - $total_in_daily);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($date_date_in != $date_date_out || $total_in_daily <= $total_in_min_grace)
				{
					$total_min_diff = intval($total_friday_out_min - $total_in_min);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
			}
			elseif($week_date == 6)
			{
				$objWorkSheet->SetCellValue('F'.$row,"SATURDAY");
			}
			elseif($week_date == 0)
			{
				$objWorkSheet->SetCellValue('F'.$row,"SUNDAY");
			}
			else
			{
				$objWorkSheet->SetCellValue('F'.$row," ");
			}

			//TARDINESS
			if($week_date == 1 || $week_date == 2 || $week_date == 3 || $week_date == 4)
			{
				if($start_halfday <= $total_in_daily)
				{
					$objWorkSheet->SetCellValue('G'.$row," ");
				}
				else
				{
					if($total_in_daily > $total_in_min_grace)
					{
						$late_hr = intval($total_in_daily - $total_in_min);
						$hr_diff = intval($late_hr/60);
						$min_diff = intval($late_hr%60);
						//echo $hr_diff.".".$min_diff."";
						$minn = sprintf("%02d", $min_diff);
						$number_of_late = $hr_diff.".".$minn;
						$objWorkSheet->SetCellValue('G'.$row,$hr_diff.".".$minn."");
						$hr_lte = $late_hr;
					}
					else
					{
						$objWorkSheet->SetCellValue('G'.$row," ");
					}
				}
			}	
			elseif($week_date == 5)
			{
				if($start_halfday <= $total_in_daily)
				{
					$objWorkSheet->SetCellValue('G'.$row," ");
				}
				else
				{
					if($total_in_daily > $total_in_min_grace)
					{
						$late_hr = intval($total_in_daily - $total_in_min);
						$hr_diff = intval($late_hr/60);
						$min_diff = intval($late_hr%60);
						$objWorkSheet->SetCellValue('G'.$row,$hr_diff.".".$min_diff."");
						$minn = sprintf("%02d", $min_diff);
					}
					else
					{
						$objWorkSheet->SetCellValue('G'.$row," ");
					}
				}
				
			}
			else
			{
				$objWorkSheet->SetCellValue('G'.$row," ");
			}
			
			//UNDERTIME
			$halfday_in = 810;
			if($week_date == 1 || $week_date == 2 || $week_date == 3 || $week_date == 4)
			{
				if($date_date_in == $date_date_out && $total_out_daily < $halfday_in)
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
				elseif($total_out_min <= $total_out_daily)
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
				elseif($total_out_daily > $halfday_in)
				{
					$undertime_hr = intval($total_out_min - $total_out_daily);
					$hr_diff = intval($undertime_hr/60);
					$min_diff = intval($undertime_hr%60);
					$minn = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('H'.$row,$hr_diff.".".$minn);
				}
				else
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
			}
			elseif($week_date == 5)
			{
				if($date_date_in == $date_date_out && $total_out_daily < $halfday_in)
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
				elseif($total_friday_out_min <= $total_out_daily)
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
				elseif($date_date_in == $date_date_out && $halfday_in < $total_out_daily)
				{
					$undertime_hr = intval($total_friday_out_min - $total_out_daily);
					$hr_diff = intval($undertime_hr/60);
					$min_diff = intval($undertime_hr%60);
					$minn = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('H'.$row,$hr_diff.".".$minn);
				}
				else
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
			}
			else
			{
				$objWorkSheet->SetCellValue('H'.$row," ");
			}

			//REGULAR OT
			foreach($data['rots'] as $key => $value1)
			{
				if($value1->ot_employee_number == $value->employee_number && $value1->date_ot == $value->dates)
				{
					$reg_ot = $value1->total_ot;
					$hr_diff = intval($reg_ot/60);
					$min_diff = intval($reg_ot%60);
					$min_diff1 = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('I'.$row,$hr_diff.".".$min_diff1);
				}
			}	

			//LEGAL HOLIDAY OT
			foreach($data['lots'] as $key => $value2)
			{
				if($value2->legal_ot_employee_number == $value->employee_number && $value2->date_ot == $value->dates)
				{
					$reg_ot = $value2->total_ot;
					$hr_diff = intval($reg_ot/60);
					$min_diff = intval($reg_ot%60);
					$min_diff1 = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('J'.$row,$hr_diff.".".$min_diff1);
				}
			}

			//SPECIAL HOLIDAY OT
			foreach($data['shots'] as $key => $value3)
			{
				if($value3->special_ot_employee_number == $value->employee_number && $value3->date_ot == $value->dates)
				{
					$reg_ot = $value3->total_ot;
					$hr_diff = intval($reg_ot/60);
					$min_diff = intval($reg_ot%60);
					$min_diff1 = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('K'.$row,$hr_diff.".".$min_diff1);
				}
			}	

			//REST DAY OT
			foreach($data['rdots'] as $key => $value4)
			{
				if($value4->restday_ot_employee_number == $value->employee_number && $value4->date_ot == $value->dates)
				{
					$reg_ot = $value4->total_ot;
					$hr_diff = intval($reg_ot/60);
					$min_diff = intval($reg_ot%60);
					$min_diff1 = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('L'.$row,$hr_diff.".".$min_diff1);
				}
			}	

			//NIGHT DIFF
			$set_night_diff_morning = '6:00';
			$explode_night_diff_morning = explode(':', $set_night_diff_morning);
			$night_diff_morning = intval($explode_night_diff_morning[0]*60);
			$compute_night_diff_morning =$night_diff_morning - $total_in_daily;
			if($total_in_daily < $night_diff_morning)
			{
				$compute_night_diff_morning;
				$hr_diff = intval($compute_night_diff_morning/60);
				$min_diff = intval($compute_night_diff_morning%60);
				if($total_in_daily == 0 && $total_out_daily == 0)
				{
					$nd = ' ';
					//echo 0;
					$objWorkSheet->SetCellValue('M'.$row, $nd);
				}
				elseif($min_diff > 30 || $min_diff == 0)
				{
					$nd = $hr_diff."."."30";
					//echo $hr_diff."."."30";
					$objWorkSheet->SetCellValue('M'.$row, $nd);
				}
				elseif($min_diff < 30)
				{
					$nd = $hr_diff;
					$objWorkSheet->SetCellValue('M'.$row, $nd);
					//echo $hr_diff;
				}
			}
			else
			{
				$nd = ' ';
				$objWorkSheet->SetCellValue('M'.$row, $nd);
				//echo 0;
			}

			//SICK LEAVE
			foreach($data['sl'] as $key => $value5)
			{
				if($value5->slvl_employee_number == $value->employee_number && $value5->sl_date == $value->dates)
				{
					$sl_per_day = $value5->total_slvl;
					$objWorkSheet->SetCellValue('N'.$row,$sl_per_day);
				}
			}

			//VACATION LEAVE
			foreach($data['vl'] as $key => $value6)
			{
				if($value6->slvl_employee_number == $value->employee_number && $value6->vl_date == $value->dates)
				{
					$vl_per_day = $value6->total_slvl;
					$objWorkSheet->SetCellValue('O'.$row,$vl_per_day);
				}
			}

			//ABSENCES
			foreach($data['ab'] as $key => $value7)
			{
				if($value7->slvl_employee_number == $value->employee_number && $value7->ab_date == $value->dates && $value7->slvl_type == 'AB')
				{
					$ab_per_day = $value7->total_slvl;
					$reg_ab = $ab_per_day;
					$hr_diff = intval($reg_ab/60);
					$min_diff = intval($reg_ab%60);
					$min_diff1 = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('P'.$row,$hr_diff.".".$min_diff1);
				}
			}

			//REMARKS
			foreach($data['remarks'] as $key => $value8)
			{
				if($value8->date == $value->dates && $value8->remarks_employee_number == $value->employee_number)
    		{
    			$remarkss = $value8->type_name;
    			$objWorkSheet->SetCellValue('Q'.$row,$remarkss);
    		}
			}

			$row++;
		}	
    $objWorkSheet->setTitle("ROXAS");


    $i++;
    
    }

    ///////////////////////////////////////////////////// SAN JUAN \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

    $i=0;
    while ($i < 1) {

      // Add new sheet
    $objWorkSheet = $objPHPExcel->createSheet($i); //Setting index when creating

	
		foreach(range('A','Q') as $columnID) {
    $objWorkSheet->getColumnDimension($columnID)
        ->setAutoSize(true);
		} 
		
		$objWorkSheet->SetCellValue('A1','EMPLOYEE NUMBER'); 
		$objWorkSheet->SetCellValue('B1','EMPLOYEE NAME');
		$objWorkSheet->SetCellValue('C1','DATES');
		$objWorkSheet->SetCellValue('D1','TIME IN');
		$objWorkSheet->SetCellValue('E1','TIME OUT');
		$objWorkSheet->SetCellValue('F1','Daily Hours');
		$objWorkSheet->SetCellValue('G1','TARDINESS');
		$objWorkSheet->SetCellValue('H1','UNDERTIME');
		$objWorkSheet->SetCellValue('I1','REGULAR OT');
		$objWorkSheet->SetCellValue('J1','LEGAL HOLIDAY OT');
		$objWorkSheet->SetCellValue('K1','SPECIAL HOLIDAY OT');
		$objWorkSheet->SetCellValue('L1','RESTDAY OT');
		$objWorkSheet->SetCellValue('M1','NSD');
		$objWorkSheet->SetCellValue('N1','SICK LEAVE');
		$objWorkSheet->SetCellValue('O1','VACATION LEAVE');
		$objWorkSheet->SetCellValue('P1','ABSENCES');
		$objWorkSheet->SetCellValue('Q1','REMARKS');

		$objWorkSheet->SetCellValue('A2',' ');
		$objWorkSheet->SetCellValue('B2',' ');
		$objWorkSheet->SetCellValue('C2',' ');
		$objWorkSheet->SetCellValue('D2',' ');
		$objWorkSheet->SetCellValue('E2',' ');
		$objWorkSheet->SetCellValue('F2','HOURS.MINS');
		$objWorkSheet->SetCellValue('G2','HOURS.MINS');
		$objWorkSheet->SetCellValue('H2','HOURS.MINS');
		$objWorkSheet->SetCellValue('I2','HOURS.MINS');
		$objWorkSheet->SetCellValue('J2','HOURS.MINS');
		$objWorkSheet->SetCellValue('K2','HOURS.MINS');
		$objWorkSheet->SetCellValue('L2','HOURS.MINS');
		$objWorkSheet->SetCellValue('M2','HOURS.MINS');
		$objWorkSheet->SetCellValue('N2','DAYS');
		$objWorkSheet->SetCellValue('O2','DAYS');
		$objWorkSheet->SetCellValue('P2','HOURS.MINS');
		$objWorkSheet->SetCellValue('Q2',' ');

		$row = 3;

		foreach($data['employeesSanjuan'] as $key => $value)
		{
			$in_office	= $data['schedules']->daily_in; 
			$out_office   = $data['schedules']->daily_out;
			$friday_out = $data['schedules']->daily_friday_out;
			$night_diff = '22:00';
			$in_daily = $value->intime;
			$out_daily = $value->outtime;
			$week_date = date('w', strtotime($value->dates)); // Convert in days . friday (5)

			// EXPLODE DATE IN TIME IN / TIME OUT
			$explode_in_date_daily = explode(" ", $in_daily);
			$explode_out_date_daily = explode(" ", $out_daily);
			$date_date_in = $explode_in_date_daily[0];
			$date_date_out = $explode_out_date_daily[0];
			$date_in = $explode_in_date_daily[1];
			$date_out = $explode_out_date_daily[1];

			//NIGHT DIFF
			$explode_night_diff = explode(":", $night_diff);
			$night_diff_hr = $explode_night_diff[0]; 
			$night_diff_min = $explode_night_diff[1]; 
			$total_night_diff = intval($night_diff_hr*60) + $night_diff_min; // total night diff

			// EXPLODE IN AND OUT 
			$explode_in_office = explode(":", $in_office);
			$explode_out_office = explode(":", $out_office);
			$explode_friday_out_office = explode(":", $friday_out);
			$explode_in_daily = explode(":", $date_in);
			$explode_out_daily = explode(":", $date_out);
			$time_in_hr_daily = $explode_in_daily[0];
			$time_in_min_daily = $explode_in_daily[1];
			$time_out_hr_daily = $explode_out_daily[0];
			$time_out_min_daily = $explode_out_daily[1];
			$time_in_hr = $explode_in_office[0];
			$time_in_min = $explode_in_office[1];
			$time_out_hr = $explode_out_office[0];
			$time_out_min = $explode_out_office[1];
			$time_friday_out_hr = $explode_friday_out_office[0];
			$time_friday_out_min = $explode_friday_out_office[1];


			// Convert IN AND OUT
			$total_in_min = intval($time_in_hr*60) + $time_in_min; // DEFAULT IN
			$total_in_min_grace = intval($time_in_hr*60) + $time_in_min + 15; // DEFAULT IN WITH GRACE PERIOD!
			$total_out_min = intval($time_out_hr*60) + $time_out_min; // DEFAULT OUT
			$total_friday_out_min = intval($time_friday_out_hr*60) + $time_friday_out_min; // DEFAULT OUT IN FRIDAY
			$total_in_daily = intval($time_in_hr_daily*60) + $time_in_min_daily; // EMPLOYEE IN
			$total_out_daily = intval($time_out_hr_daily*60) + $time_out_min_daily; // EMPLOYEE OUT

			//COMPUTATION IN OFFICE IN AND OUT
			$total_min_diff = intval($total_out_min - $total_in_min);
			$hr_diff = intval($total_min_diff/60);
			$min_diff = intval($total_min_diff%60);
			

			// IN AND OUT OF EMPLOYEE
			$in = strtotime($value->intime);
			$out   = strtotime($value->outtime);
			$diff  = $out - $in;

			//CONVERT OF IN AND OUT
			$hours = floor($diff / (60 * 60));
			$minutes = $diff - $hours * (60 * 60); 
			$total_minutes = floor( $minutes / 60 );
			
			// COMPUTATION OF IN AND OUT
			$total_number_of_hours = $hours.".".$total_minutes; //
			$total_office_hours = $hr_diff.".".$min_diff; // 9:30 Fixed
			$number_hr_daily = $total_number_of_hours; // TOTAL HOURS DAILY!
			$start_halfday = 660; 
			$end_halfday = 750;

			$objWorkSheet->SetCellValue('A'.$row,$value->employee_number);
			$objWorkSheet->SetCellValue('B'.$row,$value->name);
			$objWorkSheet->SetCellValue('C'.$row,$value->dates);
			$objWorkSheet->SetCellValue('D'.$row,$date_in);
			$objWorkSheet->SetCellValue('E'.$row,$date_out);

			//DAILY HOURS
			if($week_date >= 1 && $week_date <= 4)
			{
				if($date_date_in == $date_date_out && $total_in_daily == 0 && $total_out_daily == 0)
				{
					$objWorkSheet->SetCellValue('F'.$row," ");
				}
				elseif($total_in_min_grace >= $total_in_daily && $total_out_daily >= $total_out_min)
				{
					$total_min_diff = intval($total_out_min - $total_in_min - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}			
				elseif($total_in_daily > $total_in_min_grace && $total_out_daily < $total_out_min)
				{
					$total_min_diff = intval($total_out_daily - $total_in_daily - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($total_in_daily > $total_in_min_grace )
				{
					$total_min_diff = intval($total_out_min - $total_in_daily - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($total_out_daily < $total_out_min)
				{
					$total_min_diff = intval($total_out_daily - $total_in_min - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($total_in_daily >= $total_in_min_grace)
				{
					$total_min_diff = intval($total_out_min - $total_in_daily - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$min_diff."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($total_in_daily <= $total_in_min_grace)
				{
					$total_min_diff = intval($total_out_min - $total_in_min - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
			}
			elseif($week_date == 5)
			{
				if($date_date_in == $date_date_out && $total_in_daily == 0 && $total_out_daily == 0)
				{
					$objWorkSheet->SetCellValue('F'.$row," ");
				}
				elseif($date_date_in == $date_date_out && $total_in_min_grace >= $total_in_daily && $total_out_daily >= $total_friday_out_min)
				{
					$total_min_diff = intval($total_friday_out_min - $total_in_min );
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");

				}	
				elseif($date_date_in == $date_date_out && $total_in_daily > $total_in_min_grace && $total_out_daily < $total_friday_out_min)
				{
					$total_min_diff = intval($total_out_daily - $total_in_daily);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($date_date_in == $date_date_out && $total_in_daily > $total_in_min_grace )
				{
					$total_min_diff = intval($total_friday_out_min - $total_in_daily);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($date_date_in == $date_date_out && $total_out_daily < $total_friday_out_min)
				{
					$total_min_diff = intval($total_out_daily - $total_in_min);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($date_date_in != $date_date_out && $total_in_daily >= $total_in_min_grace)
				{
					$total_min_diff = intval($total_friday_out_min - $total_in_daily);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($date_date_in != $date_date_out || $total_in_daily <= $total_in_min_grace)
				{
					$total_min_diff = intval($total_friday_out_min - $total_in_min);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
			}
			elseif($week_date == 6)
			{
				$objWorkSheet->SetCellValue('F'.$row,"SATURDAY");
			}
			elseif($week_date == 0)
			{
				$objWorkSheet->SetCellValue('F'.$row,"SUNDAY");
			}
			else
			{
				$objWorkSheet->SetCellValue('F'.$row," ");
			}

			//TARDINESS
			if($week_date == 1 || $week_date == 2 || $week_date == 3 || $week_date == 4)
			{
				if($start_halfday <= $total_in_daily)
				{
					$objWorkSheet->SetCellValue('G'.$row," ");
				}
				else
				{
					if($total_in_daily > $total_in_min_grace)
					{
						$late_hr = intval($total_in_daily - $total_in_min);
						$hr_diff = intval($late_hr/60);
						$min_diff = intval($late_hr%60);
						//echo $hr_diff.".".$min_diff."";
						$minn = sprintf("%02d", $min_diff);
						$number_of_late = $hr_diff.".".$minn;
						$objWorkSheet->SetCellValue('G'.$row,$hr_diff.".".$minn."");
						$hr_lte = $late_hr;
					}
					else
					{
						$objWorkSheet->SetCellValue('G'.$row," ");
					}
				}
			}	
			elseif($week_date == 5)
			{
				if($start_halfday <= $total_in_daily)
				{
					$objWorkSheet->SetCellValue('G'.$row," ");
				}
				else
				{
					if($total_in_daily > $total_in_min_grace)
					{
						$late_hr = intval($total_in_daily - $total_in_min);
						$hr_diff = intval($late_hr/60);
						$min_diff = intval($late_hr%60);
						$objWorkSheet->SetCellValue('G'.$row,$hr_diff.".".$min_diff."");
						$minn = sprintf("%02d", $min_diff);
					}
					else
					{
						$objWorkSheet->SetCellValue('G'.$row," ");
					}
				}
				
			}
			else
			{
				$objWorkSheet->SetCellValue('G'.$row," ");
			}
			
			//UNDERTIME
			$halfday_in = 810;
			if($week_date == 1 || $week_date == 2 || $week_date == 3 || $week_date == 4)
			{
				if($date_date_in == $date_date_out && $total_out_daily < $halfday_in)
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
				elseif($total_out_min <= $total_out_daily)
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
				elseif($total_out_daily > $halfday_in)
				{
					$undertime_hr = intval($total_out_min - $total_out_daily);
					$hr_diff = intval($undertime_hr/60);
					$min_diff = intval($undertime_hr%60);
					$minn = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('H'.$row,$hr_diff.".".$minn);
				}
				else
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
			}
			elseif($week_date == 5)
			{
				if($date_date_in == $date_date_out && $total_out_daily < $halfday_in)
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
				elseif($total_friday_out_min <= $total_out_daily)
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
				elseif($date_date_in == $date_date_out && $halfday_in < $total_out_daily)
				{
					$undertime_hr = intval($total_friday_out_min - $total_out_daily);
					$hr_diff = intval($undertime_hr/60);
					$min_diff = intval($undertime_hr%60);
					$minn = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('H'.$row,$hr_diff.".".$minn);
				}
				else
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
			}
			else
			{
				$objWorkSheet->SetCellValue('H'.$row," ");
			}

			//REGULAR OT
			foreach($data['rots'] as $key => $value1)
			{
				if($value1->ot_employee_number == $value->employee_number && $value1->date_ot == $value->dates)
				{
					$reg_ot = $value1->total_ot;
					$hr_diff = intval($reg_ot/60);
					$min_diff = intval($reg_ot%60);
					$min_diff1 = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('I'.$row,$hr_diff.".".$min_diff1);
				}
			}	

			//LEGAL HOLIDAY OT
			foreach($data['lots'] as $key => $value2)
			{
				if($value2->legal_ot_employee_number == $value->employee_number && $value2->date_ot == $value->dates)
				{
					$reg_ot = $value2->total_ot;
					$hr_diff = intval($reg_ot/60);
					$min_diff = intval($reg_ot%60);
					$min_diff1 = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('J'.$row,$hr_diff.".".$min_diff1);
				}
			}

			//SPECIAL HOLIDAY OT
			foreach($data['shots'] as $key => $value3)
			{
				if($value3->special_ot_employee_number == $value->employee_number && $value3->date_ot == $value->dates)
				{
					$reg_ot = $value3->total_ot;
					$hr_diff = intval($reg_ot/60);
					$min_diff = intval($reg_ot%60);
					$min_diff1 = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('K'.$row,$hr_diff.".".$min_diff1);
				}
			}	

			//REST DAY OT
			foreach($data['rdots'] as $key => $value4)
			{
				if($value4->restday_ot_employee_number == $value->employee_number && $value4->date_ot == $value->dates)
				{
					$reg_ot = $value4->total_ot;
					$hr_diff = intval($reg_ot/60);
					$min_diff = intval($reg_ot%60);
					$min_diff1 = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('L'.$row,$hr_diff.".".$min_diff1);
				}
			}	

			//NIGHT DIFF
			$set_night_diff_morning = '6:00';
			$explode_night_diff_morning = explode(':', $set_night_diff_morning);
			$night_diff_morning = intval($explode_night_diff_morning[0]*60);
			$compute_night_diff_morning =$night_diff_morning - $total_in_daily;
			if($total_in_daily < $night_diff_morning)
			{
				$compute_night_diff_morning;
				$hr_diff = intval($compute_night_diff_morning/60);
				$min_diff = intval($compute_night_diff_morning%60);
				if($total_in_daily == 0 && $total_out_daily == 0)
				{
					$nd = ' ';
					//echo 0;
					$objWorkSheet->SetCellValue('M'.$row, $nd);
				}
				elseif($min_diff > 30 || $min_diff == 0)
				{
					$nd = $hr_diff."."."30";
					//echo $hr_diff."."."30";
					$objWorkSheet->SetCellValue('M'.$row, $nd);
				}
				elseif($min_diff < 30)
				{
					$nd = $hr_diff;
					$objWorkSheet->SetCellValue('M'.$row, $nd);
					//echo $hr_diff;
				}
			}
			else
			{
				$nd = ' ';
				$objWorkSheet->SetCellValue('M'.$row, $nd);
				//echo 0;
			}

			//SICK LEAVE
			foreach($data['sl'] as $key => $value5)
			{
				if($value5->slvl_employee_number == $value->employee_number && $value5->sl_date == $value->dates)
				{
					$sl_per_day = $value5->total_slvl;
					$objWorkSheet->SetCellValue('N'.$row,$sl_per_day);
				}
			}

			//VACATION LEAVE
			foreach($data['vl'] as $key => $value6)
			{
				if($value6->slvl_employee_number == $value->employee_number && $value6->vl_date == $value->dates)
				{
					$vl_per_day = $value6->total_slvl;
					$objWorkSheet->SetCellValue('O'.$row,$vl_per_day);
				}
			}

			//ABSENCES
			foreach($data['ab'] as $key => $value7)
			{
				if($value7->slvl_employee_number == $value->employee_number && $value7->ab_date == $value->dates && $value7->slvl_type == 'AB')
				{
					$ab_per_day = $value7->total_slvl;
					$reg_ab = $ab_per_day;
					$hr_diff = intval($reg_ab/60);
					$min_diff = intval($reg_ab%60);
					$min_diff1 = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('P'.$row,$hr_diff.".".$min_diff1);
				}
			}

			//REMARKS
			foreach($data['remarks'] as $key => $value8)
			{
				if($value8->date == $value->dates && $value8->remarks_employee_number == $value->employee_number)
    		{
    			$remarkss = $value8->type_name;
    			$objWorkSheet->SetCellValue('Q'.$row,$remarkss);
    		}
			}

			$row++;
		}	
    $objWorkSheet->setTitle("SAN JUAN");


    $i++;
    
    }

    /////////////////////////////////////////////////////// SAN PABLO \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

    $i=0;
    while ($i < 1) {

      // Add new sheet
    $objWorkSheet = $objPHPExcel->createSheet($i); //Setting index when creating

	
		foreach(range('A','Q') as $columnID) {
    $objWorkSheet->getColumnDimension($columnID)
        ->setAutoSize(true);
		} 
		
		$objWorkSheet->SetCellValue('A1','EMPLOYEE NUMBER'); 
		$objWorkSheet->SetCellValue('B1','EMPLOYEE NAME');
		$objWorkSheet->SetCellValue('C1','DATES');
		$objWorkSheet->SetCellValue('D1','TIME IN');
		$objWorkSheet->SetCellValue('E1','TIME OUT');
		$objWorkSheet->SetCellValue('F1','Daily Hours');
		$objWorkSheet->SetCellValue('G1','TARDINESS');
		$objWorkSheet->SetCellValue('H1','UNDERTIME');
		$objWorkSheet->SetCellValue('I1','REGULAR OT');
		$objWorkSheet->SetCellValue('J1','LEGAL HOLIDAY OT');
		$objWorkSheet->SetCellValue('K1','SPECIAL HOLIDAY OT');
		$objWorkSheet->SetCellValue('L1','RESTDAY OT');
		$objWorkSheet->SetCellValue('M1','NSD');
		$objWorkSheet->SetCellValue('N1','SICK LEAVE');
		$objWorkSheet->SetCellValue('O1','VACATION LEAVE');
		$objWorkSheet->SetCellValue('P1','ABSENCES');
		$objWorkSheet->SetCellValue('Q1','REMARKS');

		$objWorkSheet->SetCellValue('A2',' ');
		$objWorkSheet->SetCellValue('B2',' ');
		$objWorkSheet->SetCellValue('C2',' ');
		$objWorkSheet->SetCellValue('D2',' ');
		$objWorkSheet->SetCellValue('E2',' ');
		$objWorkSheet->SetCellValue('F2','HOURS.MINS');
		$objWorkSheet->SetCellValue('G2','HOURS.MINS');
		$objWorkSheet->SetCellValue('H2','HOURS.MINS');
		$objWorkSheet->SetCellValue('I2','HOURS.MINS');
		$objWorkSheet->SetCellValue('J2','HOURS.MINS');
		$objWorkSheet->SetCellValue('K2','HOURS.MINS');
		$objWorkSheet->SetCellValue('L2','HOURS.MINS');
		$objWorkSheet->SetCellValue('M2','HOURS.MINS');
		$objWorkSheet->SetCellValue('N2','DAYS');
		$objWorkSheet->SetCellValue('O2','DAYS');
		$objWorkSheet->SetCellValue('P2','HOURS.MINS');
		$objWorkSheet->SetCellValue('Q2',' ');

		$row = 3;

		foreach($data['employeesSanpablo'] as $key => $value)
		{
			$in_office	= $data['schedules']->daily_in; 
			$out_office   = $data['schedules']->daily_out;
			$friday_out = $data['schedules']->daily_friday_out;
			$night_diff = '22:00';
			$in_daily = $value->intime;
			$out_daily = $value->outtime;
			$week_date = date('w', strtotime($value->dates)); // Convert in days . friday (5)

			// EXPLODE DATE IN TIME IN / TIME OUT
			$explode_in_date_daily = explode(" ", $in_daily);
			$explode_out_date_daily = explode(" ", $out_daily);
			$date_date_in = $explode_in_date_daily[0];
			$date_date_out = $explode_out_date_daily[0];
			$date_in = $explode_in_date_daily[1];
			$date_out = $explode_out_date_daily[1];

			//NIGHT DIFF
			$explode_night_diff = explode(":", $night_diff);
			$night_diff_hr = $explode_night_diff[0]; 
			$night_diff_min = $explode_night_diff[1]; 
			$total_night_diff = intval($night_diff_hr*60) + $night_diff_min; // total night diff

			// EXPLODE IN AND OUT 
			$explode_in_office = explode(":", $in_office);
			$explode_out_office = explode(":", $out_office);
			$explode_friday_out_office = explode(":", $friday_out);
			$explode_in_daily = explode(":", $date_in);
			$explode_out_daily = explode(":", $date_out);
			$time_in_hr_daily = $explode_in_daily[0];
			$time_in_min_daily = $explode_in_daily[1];
			$time_out_hr_daily = $explode_out_daily[0];
			$time_out_min_daily = $explode_out_daily[1];
			$time_in_hr = $explode_in_office[0];
			$time_in_min = $explode_in_office[1];
			$time_out_hr = $explode_out_office[0];
			$time_out_min = $explode_out_office[1];
			$time_friday_out_hr = $explode_friday_out_office[0];
			$time_friday_out_min = $explode_friday_out_office[1];


			// Convert IN AND OUT
			$total_in_min = intval($time_in_hr*60) + $time_in_min; // DEFAULT IN
			$total_in_min_grace = intval($time_in_hr*60) + $time_in_min + 15; // DEFAULT IN WITH GRACE PERIOD!
			$total_out_min = intval($time_out_hr*60) + $time_out_min; // DEFAULT OUT
			$total_friday_out_min = intval($time_friday_out_hr*60) + $time_friday_out_min; // DEFAULT OUT IN FRIDAY
			$total_in_daily = intval($time_in_hr_daily*60) + $time_in_min_daily; // EMPLOYEE IN
			$total_out_daily = intval($time_out_hr_daily*60) + $time_out_min_daily; // EMPLOYEE OUT

			//COMPUTATION IN OFFICE IN AND OUT
			$total_min_diff = intval($total_out_min - $total_in_min);
			$hr_diff = intval($total_min_diff/60);
			$min_diff = intval($total_min_diff%60);
			

			// IN AND OUT OF EMPLOYEE
			$in = strtotime($value->intime);
			$out   = strtotime($value->outtime);
			$diff  = $out - $in;

			//CONVERT OF IN AND OUT
			$hours = floor($diff / (60 * 60));
			$minutes = $diff - $hours * (60 * 60); 
			$total_minutes = floor( $minutes / 60 );
			
			// COMPUTATION OF IN AND OUT
			$total_number_of_hours = $hours.".".$total_minutes; //
			$total_office_hours = $hr_diff.".".$min_diff; // 9:30 Fixed
			$number_hr_daily = $total_number_of_hours; // TOTAL HOURS DAILY!
			$start_halfday = 660; 
			$end_halfday = 750;

			$objWorkSheet->SetCellValue('A'.$row,$value->employee_number);
			$objWorkSheet->SetCellValue('B'.$row,$value->name);
			$objWorkSheet->SetCellValue('C'.$row,$value->dates);
			$objWorkSheet->SetCellValue('D'.$row,$date_in);
			$objWorkSheet->SetCellValue('E'.$row,$date_out);

			//DAILY HOURS
			if($week_date >= 1 && $week_date <= 4)
			{
				if($date_date_in == $date_date_out && $total_in_daily == 0 && $total_out_daily == 0)
				{
					$objWorkSheet->SetCellValue('F'.$row," ");
				}
				elseif($total_in_min_grace >= $total_in_daily && $total_out_daily >= $total_out_min)
				{
					$total_min_diff = intval($total_out_min - $total_in_min - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}			
				elseif($total_in_daily > $total_in_min_grace && $total_out_daily < $total_out_min)
				{
					$total_min_diff = intval($total_out_daily - $total_in_daily - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($total_in_daily > $total_in_min_grace )
				{
					$total_min_diff = intval($total_out_min - $total_in_daily - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($total_out_daily < $total_out_min)
				{
					$total_min_diff = intval($total_out_daily - $total_in_min - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($total_in_daily >= $total_in_min_grace)
				{
					$total_min_diff = intval($total_out_min - $total_in_daily - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$min_diff."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($total_in_daily <= $total_in_min_grace)
				{
					$total_min_diff = intval($total_out_min - $total_in_min - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
			}
			elseif($week_date == 5)
			{
				if($date_date_in == $date_date_out && $total_in_daily == 0 && $total_out_daily == 0)
				{
					$objWorkSheet->SetCellValue('F'.$row," ");
				}
				elseif($date_date_in == $date_date_out && $total_in_min_grace >= $total_in_daily && $total_out_daily >= $total_friday_out_min)
				{
					$total_min_diff = intval($total_friday_out_min - $total_in_min );
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");

				}	
				elseif($date_date_in == $date_date_out && $total_in_daily > $total_in_min_grace && $total_out_daily < $total_friday_out_min)
				{
					$total_min_diff = intval($total_out_daily - $total_in_daily);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($date_date_in == $date_date_out && $total_in_daily > $total_in_min_grace )
				{
					$total_min_diff = intval($total_friday_out_min - $total_in_daily);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($date_date_in == $date_date_out && $total_out_daily < $total_friday_out_min)
				{
					$total_min_diff = intval($total_out_daily - $total_in_min);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($date_date_in != $date_date_out && $total_in_daily >= $total_in_min_grace)
				{
					$total_min_diff = intval($total_friday_out_min - $total_in_daily);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($date_date_in != $date_date_out || $total_in_daily <= $total_in_min_grace)
				{
					$total_min_diff = intval($total_friday_out_min - $total_in_min);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
			}
			elseif($week_date == 6)
			{
				$objWorkSheet->SetCellValue('F'.$row,"SATURDAY");
			}
			elseif($week_date == 0)
			{
				$objWorkSheet->SetCellValue('F'.$row,"SUNDAY");
			}
			else
			{
				$objWorkSheet->SetCellValue('F'.$row," ");
			}

			//TARDINESS
			if($week_date == 1 || $week_date == 2 || $week_date == 3 || $week_date == 4)
			{
				if($start_halfday <= $total_in_daily)
				{
					$objWorkSheet->SetCellValue('G'.$row," ");
				}
				else
				{
					if($total_in_daily > $total_in_min_grace)
					{
						$late_hr = intval($total_in_daily - $total_in_min);
						$hr_diff = intval($late_hr/60);
						$min_diff = intval($late_hr%60);
						//echo $hr_diff.".".$min_diff."";
						$minn = sprintf("%02d", $min_diff);
						$number_of_late = $hr_diff.".".$minn;
						$objWorkSheet->SetCellValue('G'.$row,$hr_diff.".".$minn."");
						$hr_lte = $late_hr;
					}
					else
					{
						$objWorkSheet->SetCellValue('G'.$row," ");
					}
				}
			}	
			elseif($week_date == 5)
			{
				if($start_halfday <= $total_in_daily)
				{
					$objWorkSheet->SetCellValue('G'.$row," ");
				}
				else
				{
					if($total_in_daily > $total_in_min_grace)
					{
						$late_hr = intval($total_in_daily - $total_in_min);
						$hr_diff = intval($late_hr/60);
						$min_diff = intval($late_hr%60);
						$objWorkSheet->SetCellValue('G'.$row,$hr_diff.".".$min_diff."");
						$minn = sprintf("%02d", $min_diff);
					}
					else
					{
						$objWorkSheet->SetCellValue('G'.$row," ");
					}
				}
				
			}
			else
			{
				$objWorkSheet->SetCellValue('G'.$row," ");
			}
			
			//UNDERTIME
			$halfday_in = 810;
			if($week_date == 1 || $week_date == 2 || $week_date == 3 || $week_date == 4)
			{
				if($date_date_in == $date_date_out && $total_out_daily < $halfday_in)
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
				elseif($total_out_min <= $total_out_daily)
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
				elseif($total_out_daily > $halfday_in)
				{
					$undertime_hr = intval($total_out_min - $total_out_daily);
					$hr_diff = intval($undertime_hr/60);
					$min_diff = intval($undertime_hr%60);
					$minn = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('H'.$row,$hr_diff.".".$minn);
				}
				else
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
			}
			elseif($week_date == 5)
			{
				if($date_date_in == $date_date_out && $total_out_daily < $halfday_in)
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
				elseif($total_friday_out_min <= $total_out_daily)
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
				elseif($date_date_in == $date_date_out && $halfday_in < $total_out_daily)
				{
					$undertime_hr = intval($total_friday_out_min - $total_out_daily);
					$hr_diff = intval($undertime_hr/60);
					$min_diff = intval($undertime_hr%60);
					$minn = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('H'.$row,$hr_diff.".".$minn);
				}
				else
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
			}
			else
			{
				$objWorkSheet->SetCellValue('H'.$row," ");
			}

			//REGULAR OT
			foreach($data['rots'] as $key => $value1)
			{
				if($value1->ot_employee_number == $value->employee_number && $value1->date_ot == $value->dates)
				{
					$reg_ot = $value1->total_ot;
					$hr_diff = intval($reg_ot/60);
					$min_diff = intval($reg_ot%60);
					$min_diff1 = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('I'.$row,$hr_diff.".".$min_diff1);
				}
			}	

			//LEGAL HOLIDAY OT
			foreach($data['lots'] as $key => $value2)
			{
				if($value2->legal_ot_employee_number == $value->employee_number && $value2->date_ot == $value->dates)
				{
					$reg_ot = $value2->total_ot;
					$hr_diff = intval($reg_ot/60);
					$min_diff = intval($reg_ot%60);
					$min_diff1 = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('J'.$row,$hr_diff.".".$min_diff1);
				}
			}

			//SPECIAL HOLIDAY OT
			foreach($data['shots'] as $key => $value3)
			{
				if($value3->special_ot_employee_number == $value->employee_number && $value3->date_ot == $value->dates)
				{
					$reg_ot = $value3->total_ot;
					$hr_diff = intval($reg_ot/60);
					$min_diff = intval($reg_ot%60);
					$min_diff1 = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('K'.$row,$hr_diff.".".$min_diff1);
				}
			}	

			//REST DAY OT
			foreach($data['rdots'] as $key => $value4)
			{
				if($value4->restday_ot_employee_number == $value->employee_number && $value4->date_ot == $value->dates)
				{
					$reg_ot = $value4->total_ot;
					$hr_diff = intval($reg_ot/60);
					$min_diff = intval($reg_ot%60);
					$min_diff1 = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('L'.$row,$hr_diff.".".$min_diff1);
				}
			}	

			//NIGHT DIFF
			$set_night_diff_morning = '6:00';
			$explode_night_diff_morning = explode(':', $set_night_diff_morning);
			$night_diff_morning = intval($explode_night_diff_morning[0]*60);
			$compute_night_diff_morning =$night_diff_morning - $total_in_daily;
			if($total_in_daily < $night_diff_morning)
			{
				$compute_night_diff_morning;
				$hr_diff = intval($compute_night_diff_morning/60);
				$min_diff = intval($compute_night_diff_morning%60);
				if($total_in_daily == 0 && $total_out_daily == 0)
				{
					$nd = ' ';
					//echo 0;
					$objWorkSheet->SetCellValue('M'.$row, $nd);
				}
				elseif($min_diff > 30 || $min_diff == 0)
				{
					$nd = $hr_diff."."."30";
					//echo $hr_diff."."."30";
					$objWorkSheet->SetCellValue('M'.$row, $nd);
				}
				elseif($min_diff < 30)
				{
					$nd = $hr_diff;
					$objWorkSheet->SetCellValue('M'.$row, $nd);
					//echo $hr_diff;
				}
			}
			else
			{
				$nd = ' ';
				$objWorkSheet->SetCellValue('M'.$row, $nd);
				//echo 0;
			}

			//SICK LEAVE
			foreach($data['sl'] as $key => $value5)
			{
				if($value5->slvl_employee_number == $value->employee_number && $value5->sl_date == $value->dates)
				{
					$sl_per_day = $value5->total_slvl;
					$objWorkSheet->SetCellValue('N'.$row,$sl_per_day);
				}
			}

			//VACATION LEAVE
			foreach($data['vl'] as $key => $value6)
			{
				if($value6->slvl_employee_number == $value->employee_number && $value6->vl_date == $value->dates)
				{
					$vl_per_day = $value6->total_slvl;
					$objWorkSheet->SetCellValue('O'.$row,$vl_per_day);
				}
			}

			//ABSENCES
			foreach($data['ab'] as $key => $value7)
			{
				if($value7->slvl_employee_number == $value->employee_number && $value7->ab_date == $value->dates && $value7->slvl_type == 'AB')
				{
					$ab_per_day = $value7->total_slvl;
					$reg_ab = $ab_per_day;
					$hr_diff = intval($reg_ab/60);
					$min_diff = intval($reg_ab%60);
					$min_diff1 = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('P'.$row,$hr_diff.".".$min_diff1);
				}
			}

			//REMARKS
			foreach($data['remarks'] as $key => $value8)
			{
				if($value8->date == $value->dates && $value8->remarks_employee_number == $value->employee_number)
    		{
    			$remarkss = $value8->type_name;
    			$objWorkSheet->SetCellValue('Q'.$row,$remarkss);
    		}
			}

			$row++;
		}	
    $objWorkSheet->setTitle("SAN PABLO");


    $i++;
    
    }

    ////////////////////////////////////////////////////////// SANTIAGO \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

    $i=0;
    while ($i < 1) {

      // Add new sheet
    $objWorkSheet = $objPHPExcel->createSheet($i); //Setting index when creating

	
		foreach(range('A','Q') as $columnID) {
    $objWorkSheet->getColumnDimension($columnID)
        ->setAutoSize(true);
		} 
		
		$objWorkSheet->SetCellValue('A1','EMPLOYEE NUMBER'); 
		$objWorkSheet->SetCellValue('B1','EMPLOYEE NAME');
		$objWorkSheet->SetCellValue('C1','DATES');
		$objWorkSheet->SetCellValue('D1','TIME IN');
		$objWorkSheet->SetCellValue('E1','TIME OUT');
		$objWorkSheet->SetCellValue('F1','Daily Hours');
		$objWorkSheet->SetCellValue('G1','TARDINESS');
		$objWorkSheet->SetCellValue('H1','UNDERTIME');
		$objWorkSheet->SetCellValue('I1','REGULAR OT');
		$objWorkSheet->SetCellValue('J1','LEGAL HOLIDAY OT');
		$objWorkSheet->SetCellValue('K1','SPECIAL HOLIDAY OT');
		$objWorkSheet->SetCellValue('L1','RESTDAY OT');
		$objWorkSheet->SetCellValue('M1','NSD');
		$objWorkSheet->SetCellValue('N1','SICK LEAVE');
		$objWorkSheet->SetCellValue('O1','VACATION LEAVE');
		$objWorkSheet->SetCellValue('P1','ABSENCES');
		$objWorkSheet->SetCellValue('Q1','REMARKS');

		$objWorkSheet->SetCellValue('A2',' ');
		$objWorkSheet->SetCellValue('B2',' ');
		$objWorkSheet->SetCellValue('C2',' ');
		$objWorkSheet->SetCellValue('D2',' ');
		$objWorkSheet->SetCellValue('E2',' ');
		$objWorkSheet->SetCellValue('F2','HOURS.MINS');
		$objWorkSheet->SetCellValue('G2','HOURS.MINS');
		$objWorkSheet->SetCellValue('H2','HOURS.MINS');
		$objWorkSheet->SetCellValue('I2','HOURS.MINS');
		$objWorkSheet->SetCellValue('J2','HOURS.MINS');
		$objWorkSheet->SetCellValue('K2','HOURS.MINS');
		$objWorkSheet->SetCellValue('L2','HOURS.MINS');
		$objWorkSheet->SetCellValue('M2','HOURS.MINS');
		$objWorkSheet->SetCellValue('N2','DAYS');
		$objWorkSheet->SetCellValue('O2','DAYS');
		$objWorkSheet->SetCellValue('P2','HOURS.MINS');
		$objWorkSheet->SetCellValue('Q2',' ');

		$row = 3;

		foreach($data['employeesSantiago'] as $key => $value)
		{
			$in_office	= $data['schedules']->daily_in; 
			$out_office   = $data['schedules']->daily_out;
			$friday_out = $data['schedules']->daily_friday_out;
			$night_diff = '22:00';
			$in_daily = $value->intime;
			$out_daily = $value->outtime;
			$week_date = date('w', strtotime($value->dates)); // Convert in days . friday (5)

			// EXPLODE DATE IN TIME IN / TIME OUT
			$explode_in_date_daily = explode(" ", $in_daily);
			$explode_out_date_daily = explode(" ", $out_daily);
			$date_date_in = $explode_in_date_daily[0];
			$date_date_out = $explode_out_date_daily[0];
			$date_in = $explode_in_date_daily[1];
			$date_out = $explode_out_date_daily[1];

			//NIGHT DIFF
			$explode_night_diff = explode(":", $night_diff);
			$night_diff_hr = $explode_night_diff[0]; 
			$night_diff_min = $explode_night_diff[1]; 
			$total_night_diff = intval($night_diff_hr*60) + $night_diff_min; // total night diff

			// EXPLODE IN AND OUT 
			$explode_in_office = explode(":", $in_office);
			$explode_out_office = explode(":", $out_office);
			$explode_friday_out_office = explode(":", $friday_out);
			$explode_in_daily = explode(":", $date_in);
			$explode_out_daily = explode(":", $date_out);
			$time_in_hr_daily = $explode_in_daily[0];
			$time_in_min_daily = $explode_in_daily[1];
			$time_out_hr_daily = $explode_out_daily[0];
			$time_out_min_daily = $explode_out_daily[1];
			$time_in_hr = $explode_in_office[0];
			$time_in_min = $explode_in_office[1];
			$time_out_hr = $explode_out_office[0];
			$time_out_min = $explode_out_office[1];
			$time_friday_out_hr = $explode_friday_out_office[0];
			$time_friday_out_min = $explode_friday_out_office[1];


			// Convert IN AND OUT
			$total_in_min = intval($time_in_hr*60) + $time_in_min; // DEFAULT IN
			$total_in_min_grace = intval($time_in_hr*60) + $time_in_min + 15; // DEFAULT IN WITH GRACE PERIOD!
			$total_out_min = intval($time_out_hr*60) + $time_out_min; // DEFAULT OUT
			$total_friday_out_min = intval($time_friday_out_hr*60) + $time_friday_out_min; // DEFAULT OUT IN FRIDAY
			$total_in_daily = intval($time_in_hr_daily*60) + $time_in_min_daily; // EMPLOYEE IN
			$total_out_daily = intval($time_out_hr_daily*60) + $time_out_min_daily; // EMPLOYEE OUT

			//COMPUTATION IN OFFICE IN AND OUT
			$total_min_diff = intval($total_out_min - $total_in_min);
			$hr_diff = intval($total_min_diff/60);
			$min_diff = intval($total_min_diff%60);
			

			// IN AND OUT OF EMPLOYEE
			$in = strtotime($value->intime);
			$out   = strtotime($value->outtime);
			$diff  = $out - $in;

			//CONVERT OF IN AND OUT
			$hours = floor($diff / (60 * 60));
			$minutes = $diff - $hours * (60 * 60); 
			$total_minutes = floor( $minutes / 60 );
			
			// COMPUTATION OF IN AND OUT
			$total_number_of_hours = $hours.".".$total_minutes; //
			$total_office_hours = $hr_diff.".".$min_diff; // 9:30 Fixed
			$number_hr_daily = $total_number_of_hours; // TOTAL HOURS DAILY!
			$start_halfday = 660; 
			$end_halfday = 750;

			$objWorkSheet->SetCellValue('A'.$row,$value->employee_number);
			$objWorkSheet->SetCellValue('B'.$row,$value->name);
			$objWorkSheet->SetCellValue('C'.$row,$value->dates);
			$objWorkSheet->SetCellValue('D'.$row,$date_in);
			$objWorkSheet->SetCellValue('E'.$row,$date_out);

			//DAILY HOURS
			if($week_date >= 1 && $week_date <= 4)
			{
				if($date_date_in == $date_date_out && $total_in_daily == 0 && $total_out_daily == 0)
				{
					$objWorkSheet->SetCellValue('F'.$row," ");
				}
				elseif($total_in_min_grace >= $total_in_daily && $total_out_daily >= $total_out_min)
				{
					$total_min_diff = intval($total_out_min - $total_in_min - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}			
				elseif($total_in_daily > $total_in_min_grace && $total_out_daily < $total_out_min)
				{
					$total_min_diff = intval($total_out_daily - $total_in_daily - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($total_in_daily > $total_in_min_grace )
				{
					$total_min_diff = intval($total_out_min - $total_in_daily - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($total_out_daily < $total_out_min)
				{
					$total_min_diff = intval($total_out_daily - $total_in_min - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($total_in_daily >= $total_in_min_grace)
				{
					$total_min_diff = intval($total_out_min - $total_in_daily - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$min_diff."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($total_in_daily <= $total_in_min_grace)
				{
					$total_min_diff = intval($total_out_min - $total_in_min - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
			}
			elseif($week_date == 5)
			{
				if($date_date_in == $date_date_out && $total_in_daily == 0 && $total_out_daily == 0)
				{
					$objWorkSheet->SetCellValue('F'.$row," ");
				}
				elseif($date_date_in == $date_date_out && $total_in_min_grace >= $total_in_daily && $total_out_daily >= $total_friday_out_min)
				{
					$total_min_diff = intval($total_friday_out_min - $total_in_min );
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");

				}	
				elseif($date_date_in == $date_date_out && $total_in_daily > $total_in_min_grace && $total_out_daily < $total_friday_out_min)
				{
					$total_min_diff = intval($total_out_daily - $total_in_daily);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($date_date_in == $date_date_out && $total_in_daily > $total_in_min_grace )
				{
					$total_min_diff = intval($total_friday_out_min - $total_in_daily);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($date_date_in == $date_date_out && $total_out_daily < $total_friday_out_min)
				{
					$total_min_diff = intval($total_out_daily - $total_in_min);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($date_date_in != $date_date_out && $total_in_daily >= $total_in_min_grace)
				{
					$total_min_diff = intval($total_friday_out_min - $total_in_daily);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($date_date_in != $date_date_out || $total_in_daily <= $total_in_min_grace)
				{
					$total_min_diff = intval($total_friday_out_min - $total_in_min);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
			}
			elseif($week_date == 6)
			{
				$objWorkSheet->SetCellValue('F'.$row,"SATURDAY");
			}
			elseif($week_date == 0)
			{
				$objWorkSheet->SetCellValue('F'.$row,"SUNDAY");
			}
			else
			{
				$objWorkSheet->SetCellValue('F'.$row," ");
			}

			//TARDINESS
			if($week_date == 1 || $week_date == 2 || $week_date == 3 || $week_date == 4)
			{
				if($start_halfday <= $total_in_daily)
				{
					$objWorkSheet->SetCellValue('G'.$row," ");
				}
				else
				{
					if($total_in_daily > $total_in_min_grace)
					{
						$late_hr = intval($total_in_daily - $total_in_min);
						$hr_diff = intval($late_hr/60);
						$min_diff = intval($late_hr%60);
						//echo $hr_diff.".".$min_diff."";
						$minn = sprintf("%02d", $min_diff);
						$number_of_late = $hr_diff.".".$minn;
						$objWorkSheet->SetCellValue('G'.$row,$hr_diff.".".$minn."");
						$hr_lte = $late_hr;
					}
					else
					{
						$objWorkSheet->SetCellValue('G'.$row," ");
					}
				}
			}	
			elseif($week_date == 5)
			{
				if($start_halfday <= $total_in_daily)
				{
					$objWorkSheet->SetCellValue('G'.$row," ");
				}
				else
				{
					if($total_in_daily > $total_in_min_grace)
					{
						$late_hr = intval($total_in_daily - $total_in_min);
						$hr_diff = intval($late_hr/60);
						$min_diff = intval($late_hr%60);
						$objWorkSheet->SetCellValue('G'.$row,$hr_diff.".".$min_diff."");
						$minn = sprintf("%02d", $min_diff);
					}
					else
					{
						$objWorkSheet->SetCellValue('G'.$row," ");
					}
				}
				
			}
			else
			{
				$objWorkSheet->SetCellValue('G'.$row," ");
			}
			
			//UNDERTIME
			$halfday_in = 810;
			if($week_date == 1 || $week_date == 2 || $week_date == 3 || $week_date == 4)
			{
				if($date_date_in == $date_date_out && $total_out_daily < $halfday_in)
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
				elseif($total_out_min <= $total_out_daily)
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
				elseif($total_out_daily > $halfday_in)
				{
					$undertime_hr = intval($total_out_min - $total_out_daily);
					$hr_diff = intval($undertime_hr/60);
					$min_diff = intval($undertime_hr%60);
					$minn = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('H'.$row,$hr_diff.".".$minn);
				}
				else
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
			}
			elseif($week_date == 5)
			{
				if($date_date_in == $date_date_out && $total_out_daily < $halfday_in)
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
				elseif($total_friday_out_min <= $total_out_daily)
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
				elseif($date_date_in == $date_date_out && $halfday_in < $total_out_daily)
				{
					$undertime_hr = intval($total_friday_out_min - $total_out_daily);
					$hr_diff = intval($undertime_hr/60);
					$min_diff = intval($undertime_hr%60);
					$minn = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('H'.$row,$hr_diff.".".$minn);
				}
				else
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
			}
			else
			{
				$objWorkSheet->SetCellValue('H'.$row," ");
			}

			//REGULAR OT
			foreach($data['rots'] as $key => $value1)
			{
				if($value1->ot_employee_number == $value->employee_number && $value1->date_ot == $value->dates)
				{
					$reg_ot = $value1->total_ot;
					$hr_diff = intval($reg_ot/60);
					$min_diff = intval($reg_ot%60);
					$min_diff1 = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('I'.$row,$hr_diff.".".$min_diff1);
				}
			}	

			//LEGAL HOLIDAY OT
			foreach($data['lots'] as $key => $value2)
			{
				if($value2->legal_ot_employee_number == $value->employee_number && $value2->date_ot == $value->dates)
				{
					$reg_ot = $value2->total_ot;
					$hr_diff = intval($reg_ot/60);
					$min_diff = intval($reg_ot%60);
					$min_diff1 = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('J'.$row,$hr_diff.".".$min_diff1);
				}
			}

			//SPECIAL HOLIDAY OT
			foreach($data['shots'] as $key => $value3)
			{
				if($value3->special_ot_employee_number == $value->employee_number && $value3->date_ot == $value->dates)
				{
					$reg_ot = $value3->total_ot;
					$hr_diff = intval($reg_ot/60);
					$min_diff = intval($reg_ot%60);
					$min_diff1 = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('K'.$row,$hr_diff.".".$min_diff1);
				}
			}	

			//REST DAY OT
			foreach($data['rdots'] as $key => $value4)
			{
				if($value4->restday_ot_employee_number == $value->employee_number && $value4->date_ot == $value->dates)
				{
					$reg_ot = $value4->total_ot;
					$hr_diff = intval($reg_ot/60);
					$min_diff = intval($reg_ot%60);
					$min_diff1 = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('L'.$row,$hr_diff.".".$min_diff1);
				}
			}	

			//NIGHT DIFF
			$set_night_diff_morning = '6:00';
			$explode_night_diff_morning = explode(':', $set_night_diff_morning);
			$night_diff_morning = intval($explode_night_diff_morning[0]*60);
			$compute_night_diff_morning =$night_diff_morning - $total_in_daily;
			if($total_in_daily < $night_diff_morning)
			{
				$compute_night_diff_morning;
				$hr_diff = intval($compute_night_diff_morning/60);
				$min_diff = intval($compute_night_diff_morning%60);
				if($total_in_daily == 0 && $total_out_daily == 0)
				{
					$nd = ' ';
					//echo 0;
					$objWorkSheet->SetCellValue('M'.$row, $nd);
				}
				elseif($min_diff > 30 || $min_diff == 0)
				{
					$nd = $hr_diff."."."30";
					//echo $hr_diff."."."30";
					$objWorkSheet->SetCellValue('M'.$row, $nd);
				}
				elseif($min_diff < 30)
				{
					$nd = $hr_diff;
					$objWorkSheet->SetCellValue('M'.$row, $nd);
					//echo $hr_diff;
				}
			}
			else
			{
				$nd = ' ';
				$objWorkSheet->SetCellValue('M'.$row, $nd);
				//echo 0;
			}

			//SICK LEAVE
			foreach($data['sl'] as $key => $value5)
			{
				if($value5->slvl_employee_number == $value->employee_number && $value5->sl_date == $value->dates)
				{
					$sl_per_day = $value5->total_slvl;
					$objWorkSheet->SetCellValue('N'.$row,$sl_per_day);
				}
			}

			//VACATION LEAVE
			foreach($data['vl'] as $key => $value6)
			{
				if($value6->slvl_employee_number == $value->employee_number && $value6->vl_date == $value->dates)
				{
					$vl_per_day = $value6->total_slvl;
					$objWorkSheet->SetCellValue('O'.$row,$vl_per_day);
				}
			}

			//ABSENCES
			foreach($data['ab'] as $key => $value7)
			{
				if($value7->slvl_employee_number == $value->employee_number && $value7->ab_date == $value->dates && $value7->slvl_type == 'AB')
				{
					$ab_per_day = $value7->total_slvl;
					$reg_ab = $ab_per_day;
					$hr_diff = intval($reg_ab/60);
					$min_diff = intval($reg_ab%60);
					$min_diff1 = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('P'.$row,$hr_diff.".".$min_diff1);
				}
			}

			//REMARKS
			foreach($data['remarks'] as $key => $value8)
			{
				if($value8->date == $value->dates && $value8->remarks_employee_number == $value->employee_number)
    		{
    			$remarkss = $value8->type_name;
    			$objWorkSheet->SetCellValue('Q'.$row,$remarkss);
    		}
			}

			$row++;
		}	
    $objWorkSheet->setTitle("SANTIAGO");


    $i++;
    
    }

    ///////////////////////////////////////////////////// SOLANO \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

    $i=0;
    while ($i < 1) {

      // Add new sheet
    $objWorkSheet = $objPHPExcel->createSheet($i); //Setting index when creating

	
		foreach(range('A','Q') as $columnID) {
    $objWorkSheet->getColumnDimension($columnID)
        ->setAutoSize(true);
		} 
		
		$objWorkSheet->SetCellValue('A1','EMPLOYEE NUMBER'); 
		$objWorkSheet->SetCellValue('B1','EMPLOYEE NAME');
		$objWorkSheet->SetCellValue('C1','DATES');
		$objWorkSheet->SetCellValue('D1','TIME IN');
		$objWorkSheet->SetCellValue('E1','TIME OUT');
		$objWorkSheet->SetCellValue('F1','Daily Hours');
		$objWorkSheet->SetCellValue('G1','TARDINESS');
		$objWorkSheet->SetCellValue('H1','UNDERTIME');
		$objWorkSheet->SetCellValue('I1','REGULAR OT');
		$objWorkSheet->SetCellValue('J1','LEGAL HOLIDAY OT');
		$objWorkSheet->SetCellValue('K1','SPECIAL HOLIDAY OT');
		$objWorkSheet->SetCellValue('L1','RESTDAY OT');
		$objWorkSheet->SetCellValue('M1','NSD');
		$objWorkSheet->SetCellValue('N1','SICK LEAVE');
		$objWorkSheet->SetCellValue('O1','VACATION LEAVE');
		$objWorkSheet->SetCellValue('P1','ABSENCES');
		$objWorkSheet->SetCellValue('Q1','REMARKS');

		$objWorkSheet->SetCellValue('A2',' ');
		$objWorkSheet->SetCellValue('B2',' ');
		$objWorkSheet->SetCellValue('C2',' ');
		$objWorkSheet->SetCellValue('D2',' ');
		$objWorkSheet->SetCellValue('E2',' ');
		$objWorkSheet->SetCellValue('F2','HOURS.MINS');
		$objWorkSheet->SetCellValue('G2','HOURS.MINS');
		$objWorkSheet->SetCellValue('H2','HOURS.MINS');
		$objWorkSheet->SetCellValue('I2','HOURS.MINS');
		$objWorkSheet->SetCellValue('J2','HOURS.MINS');
		$objWorkSheet->SetCellValue('K2','HOURS.MINS');
		$objWorkSheet->SetCellValue('L2','HOURS.MINS');
		$objWorkSheet->SetCellValue('M2','HOURS.MINS');
		$objWorkSheet->SetCellValue('N2','DAYS');
		$objWorkSheet->SetCellValue('O2','DAYS');
		$objWorkSheet->SetCellValue('P2','HOURS.MINS');
		$objWorkSheet->SetCellValue('Q2',' ');

		$row = 3;

		foreach($data['employeesSolano'] as $key => $value)
		{
			$in_office	= $data['schedules']->daily_in; 
			$out_office   = $data['schedules']->daily_out;
			$friday_out = $data['schedules']->daily_friday_out;
			$night_diff = '22:00';
			$in_daily = $value->intime;
			$out_daily = $value->outtime;
			$week_date = date('w', strtotime($value->dates)); // Convert in days . friday (5)

			// EXPLODE DATE IN TIME IN / TIME OUT
			$explode_in_date_daily = explode(" ", $in_daily);
			$explode_out_date_daily = explode(" ", $out_daily);
			$date_date_in = $explode_in_date_daily[0];
			$date_date_out = $explode_out_date_daily[0];
			$date_in = $explode_in_date_daily[1];
			$date_out = $explode_out_date_daily[1];

			//NIGHT DIFF
			$explode_night_diff = explode(":", $night_diff);
			$night_diff_hr = $explode_night_diff[0]; 
			$night_diff_min = $explode_night_diff[1]; 
			$total_night_diff = intval($night_diff_hr*60) + $night_diff_min; // total night diff

			// EXPLODE IN AND OUT 
			$explode_in_office = explode(":", $in_office);
			$explode_out_office = explode(":", $out_office);
			$explode_friday_out_office = explode(":", $friday_out);
			$explode_in_daily = explode(":", $date_in);
			$explode_out_daily = explode(":", $date_out);
			$time_in_hr_daily = $explode_in_daily[0];
			$time_in_min_daily = $explode_in_daily[1];
			$time_out_hr_daily = $explode_out_daily[0];
			$time_out_min_daily = $explode_out_daily[1];
			$time_in_hr = $explode_in_office[0];
			$time_in_min = $explode_in_office[1];
			$time_out_hr = $explode_out_office[0];
			$time_out_min = $explode_out_office[1];
			$time_friday_out_hr = $explode_friday_out_office[0];
			$time_friday_out_min = $explode_friday_out_office[1];


			// Convert IN AND OUT
			$total_in_min = intval($time_in_hr*60) + $time_in_min; // DEFAULT IN
			$total_in_min_grace = intval($time_in_hr*60) + $time_in_min + 15; // DEFAULT IN WITH GRACE PERIOD!
			$total_out_min = intval($time_out_hr*60) + $time_out_min; // DEFAULT OUT
			$total_friday_out_min = intval($time_friday_out_hr*60) + $time_friday_out_min; // DEFAULT OUT IN FRIDAY
			$total_in_daily = intval($time_in_hr_daily*60) + $time_in_min_daily; // EMPLOYEE IN
			$total_out_daily = intval($time_out_hr_daily*60) + $time_out_min_daily; // EMPLOYEE OUT

			//COMPUTATION IN OFFICE IN AND OUT
			$total_min_diff = intval($total_out_min - $total_in_min);
			$hr_diff = intval($total_min_diff/60);
			$min_diff = intval($total_min_diff%60);
			

			// IN AND OUT OF EMPLOYEE
			$in = strtotime($value->intime);
			$out   = strtotime($value->outtime);
			$diff  = $out - $in;

			//CONVERT OF IN AND OUT
			$hours = floor($diff / (60 * 60));
			$minutes = $diff - $hours * (60 * 60); 
			$total_minutes = floor( $minutes / 60 );
			
			// COMPUTATION OF IN AND OUT
			$total_number_of_hours = $hours.".".$total_minutes; //
			$total_office_hours = $hr_diff.".".$min_diff; // 9:30 Fixed
			$number_hr_daily = $total_number_of_hours; // TOTAL HOURS DAILY!
			$start_halfday = 660; 
			$end_halfday = 750;

			$objWorkSheet->SetCellValue('A'.$row,$value->employee_number);
			$objWorkSheet->SetCellValue('B'.$row,$value->name);
			$objWorkSheet->SetCellValue('C'.$row,$value->dates);
			$objWorkSheet->SetCellValue('D'.$row,$date_in);
			$objWorkSheet->SetCellValue('E'.$row,$date_out);

			//DAILY HOURS
			if($week_date >= 1 && $week_date <= 4)
			{
				if($date_date_in == $date_date_out && $total_in_daily == 0 && $total_out_daily == 0)
				{
					$objWorkSheet->SetCellValue('F'.$row," ");
				}
				elseif($total_in_min_grace >= $total_in_daily && $total_out_daily >= $total_out_min)
				{
					$total_min_diff = intval($total_out_min - $total_in_min - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}			
				elseif($total_in_daily > $total_in_min_grace && $total_out_daily < $total_out_min)
				{
					$total_min_diff = intval($total_out_daily - $total_in_daily - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($total_in_daily > $total_in_min_grace )
				{
					$total_min_diff = intval($total_out_min - $total_in_daily - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($total_out_daily < $total_out_min)
				{
					$total_min_diff = intval($total_out_daily - $total_in_min - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($total_in_daily >= $total_in_min_grace)
				{
					$total_min_diff = intval($total_out_min - $total_in_daily - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$min_diff."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($total_in_daily <= $total_in_min_grace)
				{
					$total_min_diff = intval($total_out_min - $total_in_min - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
			}
			elseif($week_date == 5)
			{
				if($date_date_in == $date_date_out && $total_in_daily == 0 && $total_out_daily == 0)
				{
					$objWorkSheet->SetCellValue('F'.$row," ");
				}
				elseif($date_date_in == $date_date_out && $total_in_min_grace >= $total_in_daily && $total_out_daily >= $total_friday_out_min)
				{
					$total_min_diff = intval($total_friday_out_min - $total_in_min );
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");

				}	
				elseif($date_date_in == $date_date_out && $total_in_daily > $total_in_min_grace && $total_out_daily < $total_friday_out_min)
				{
					$total_min_diff = intval($total_out_daily - $total_in_daily);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($date_date_in == $date_date_out && $total_in_daily > $total_in_min_grace )
				{
					$total_min_diff = intval($total_friday_out_min - $total_in_daily);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($date_date_in == $date_date_out && $total_out_daily < $total_friday_out_min)
				{
					$total_min_diff = intval($total_out_daily - $total_in_min);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($date_date_in != $date_date_out && $total_in_daily >= $total_in_min_grace)
				{
					$total_min_diff = intval($total_friday_out_min - $total_in_daily);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($date_date_in != $date_date_out || $total_in_daily <= $total_in_min_grace)
				{
					$total_min_diff = intval($total_friday_out_min - $total_in_min);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
			}
			elseif($week_date == 6)
			{
				$objWorkSheet->SetCellValue('F'.$row,"SATURDAY");
			}
			elseif($week_date == 0)
			{
				$objWorkSheet->SetCellValue('F'.$row,"SUNDAY");
			}
			else
			{
				$objWorkSheet->SetCellValue('F'.$row," ");
			}

			//TARDINESS
			if($week_date == 1 || $week_date == 2 || $week_date == 3 || $week_date == 4)
			{
				if($start_halfday <= $total_in_daily)
				{
					$objWorkSheet->SetCellValue('G'.$row," ");
				}
				else
				{
					if($total_in_daily > $total_in_min_grace)
					{
						$late_hr = intval($total_in_daily - $total_in_min);
						$hr_diff = intval($late_hr/60);
						$min_diff = intval($late_hr%60);
						//echo $hr_diff.".".$min_diff."";
						$minn = sprintf("%02d", $min_diff);
						$number_of_late = $hr_diff.".".$minn;
						$objWorkSheet->SetCellValue('G'.$row,$hr_diff.".".$minn."");
						$hr_lte = $late_hr;
					}
					else
					{
						$objWorkSheet->SetCellValue('G'.$row," ");
					}
				}
			}	
			elseif($week_date == 5)
			{
				if($start_halfday <= $total_in_daily)
				{
					$objWorkSheet->SetCellValue('G'.$row," ");
				}
				else
				{
					if($total_in_daily > $total_in_min_grace)
					{
						$late_hr = intval($total_in_daily - $total_in_min);
						$hr_diff = intval($late_hr/60);
						$min_diff = intval($late_hr%60);
						$objWorkSheet->SetCellValue('G'.$row,$hr_diff.".".$min_diff."");
						$minn = sprintf("%02d", $min_diff);
					}
					else
					{
						$objWorkSheet->SetCellValue('G'.$row," ");
					}
				}
				
			}
			else
			{
				$objWorkSheet->SetCellValue('G'.$row," ");
			}
			
			//UNDERTIME
			$halfday_in = 810;
			if($week_date == 1 || $week_date == 2 || $week_date == 3 || $week_date == 4)
			{
				if($date_date_in == $date_date_out && $total_out_daily < $halfday_in)
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
				elseif($total_out_min <= $total_out_daily)
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
				elseif($total_out_daily > $halfday_in)
				{
					$undertime_hr = intval($total_out_min - $total_out_daily);
					$hr_diff = intval($undertime_hr/60);
					$min_diff = intval($undertime_hr%60);
					$minn = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('H'.$row,$hr_diff.".".$minn);
				}
				else
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
			}
			elseif($week_date == 5)
			{
				if($date_date_in == $date_date_out && $total_out_daily < $halfday_in)
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
				elseif($total_friday_out_min <= $total_out_daily)
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
				elseif($date_date_in == $date_date_out && $halfday_in < $total_out_daily)
				{
					$undertime_hr = intval($total_friday_out_min - $total_out_daily);
					$hr_diff = intval($undertime_hr/60);
					$min_diff = intval($undertime_hr%60);
					$minn = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('H'.$row,$hr_diff.".".$minn);
				}
				else
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
			}
			else
			{
				$objWorkSheet->SetCellValue('H'.$row," ");
			}

			//REGULAR OT
			foreach($data['rots'] as $key => $value1)
			{
				if($value1->ot_employee_number == $value->employee_number && $value1->date_ot == $value->dates)
				{
					$reg_ot = $value1->total_ot;
					$hr_diff = intval($reg_ot/60);
					$min_diff = intval($reg_ot%60);
					$min_diff1 = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('I'.$row,$hr_diff.".".$min_diff1);
				}
			}	

			//LEGAL HOLIDAY OT
			foreach($data['lots'] as $key => $value2)
			{
				if($value2->legal_ot_employee_number == $value->employee_number && $value2->date_ot == $value->dates)
				{
					$reg_ot = $value2->total_ot;
					$hr_diff = intval($reg_ot/60);
					$min_diff = intval($reg_ot%60);
					$min_diff1 = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('J'.$row,$hr_diff.".".$min_diff1);
				}
			}

			//SPECIAL HOLIDAY OT
			foreach($data['shots'] as $key => $value3)
			{
				if($value3->special_ot_employee_number == $value->employee_number && $value3->date_ot == $value->dates)
				{
					$reg_ot = $value3->total_ot;
					$hr_diff = intval($reg_ot/60);
					$min_diff = intval($reg_ot%60);
					$min_diff1 = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('K'.$row,$hr_diff.".".$min_diff1);
				}
			}	

			//REST DAY OT
			foreach($data['rdots'] as $key => $value4)
			{
				if($value4->restday_ot_employee_number == $value->employee_number && $value4->date_ot == $value->dates)
				{
					$reg_ot = $value4->total_ot;
					$hr_diff = intval($reg_ot/60);
					$min_diff = intval($reg_ot%60);
					$min_diff1 = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('L'.$row,$hr_diff.".".$min_diff1);
				}
			}	

			//NIGHT DIFF
			$set_night_diff_morning = '6:00';
			$explode_night_diff_morning = explode(':', $set_night_diff_morning);
			$night_diff_morning = intval($explode_night_diff_morning[0]*60);
			$compute_night_diff_morning =$night_diff_morning - $total_in_daily;
			if($total_in_daily < $night_diff_morning)
			{
				$compute_night_diff_morning;
				$hr_diff = intval($compute_night_diff_morning/60);
				$min_diff = intval($compute_night_diff_morning%60);
				if($total_in_daily == 0 && $total_out_daily == 0)
				{
					$nd = ' ';
					//echo 0;
					$objWorkSheet->SetCellValue('M'.$row, $nd);
				}
				elseif($min_diff > 30 || $min_diff == 0)
				{
					$nd = $hr_diff."."."30";
					//echo $hr_diff."."."30";
					$objWorkSheet->SetCellValue('M'.$row, $nd);
				}
				elseif($min_diff < 30)
				{
					$nd = $hr_diff;
					$objWorkSheet->SetCellValue('M'.$row, $nd);
					//echo $hr_diff;
				}
			}
			else
			{
				$nd = ' ';
				$objWorkSheet->SetCellValue('M'.$row, $nd);
				//echo 0;
			}

			//SICK LEAVE
			foreach($data['sl'] as $key => $value5)
			{
				if($value5->slvl_employee_number == $value->employee_number && $value5->sl_date == $value->dates)
				{
					$sl_per_day = $value5->total_slvl;
					$objWorkSheet->SetCellValue('N'.$row,$sl_per_day);
				}
			}

			//VACATION LEAVE
			foreach($data['vl'] as $key => $value6)
			{
				if($value6->slvl_employee_number == $value->employee_number && $value6->vl_date == $value->dates)
				{
					$vl_per_day = $value6->total_slvl;
					$objWorkSheet->SetCellValue('O'.$row,$vl_per_day);
				}
			}

			//ABSENCES
			foreach($data['ab'] as $key => $value7)
			{
				if($value7->slvl_employee_number == $value->employee_number && $value7->ab_date == $value->dates && $value7->slvl_type == 'AB')
				{
					$ab_per_day = $value7->total_slvl;
					$reg_ab = $ab_per_day;
					$hr_diff = intval($reg_ab/60);
					$min_diff = intval($reg_ab%60);
					$min_diff1 = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('P'.$row,$hr_diff.".".$min_diff1);
				}
			}

			//REMARKS
			foreach($data['remarks'] as $key => $value8)
			{
				if($value8->date == $value->dates && $value8->remarks_employee_number == $value->employee_number)
    		{
    			$remarkss = $value8->type_name;
    			$objWorkSheet->SetCellValue('Q'.$row,$remarkss);
    		}
			}

			$row++;
		}	
    $objWorkSheet->setTitle("SOLANO");


    $i++;
    
    }

    ////////////////////////////////////////////////////// TABUK \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

    $i=0;
    while ($i < 1) {

      // Add new sheet
    $objWorkSheet = $objPHPExcel->createSheet($i); //Setting index when creating

	
		foreach(range('A','Q') as $columnID) {
    $objWorkSheet->getColumnDimension($columnID)
        ->setAutoSize(true);
		} 
		
		$objWorkSheet->SetCellValue('A1','EMPLOYEE NUMBER'); 
		$objWorkSheet->SetCellValue('B1','EMPLOYEE NAME');
		$objWorkSheet->SetCellValue('C1','DATES');
		$objWorkSheet->SetCellValue('D1','TIME IN');
		$objWorkSheet->SetCellValue('E1','TIME OUT');
		$objWorkSheet->SetCellValue('F1','Daily Hours');
		$objWorkSheet->SetCellValue('G1','TARDINESS');
		$objWorkSheet->SetCellValue('H1','UNDERTIME');
		$objWorkSheet->SetCellValue('I1','REGULAR OT');
		$objWorkSheet->SetCellValue('J1','LEGAL HOLIDAY OT');
		$objWorkSheet->SetCellValue('K1','SPECIAL HOLIDAY OT');
		$objWorkSheet->SetCellValue('L1','RESTDAY OT');
		$objWorkSheet->SetCellValue('M1','NSD');
		$objWorkSheet->SetCellValue('N1','SICK LEAVE');
		$objWorkSheet->SetCellValue('O1','VACATION LEAVE');
		$objWorkSheet->SetCellValue('P1','ABSENCES');
		$objWorkSheet->SetCellValue('Q1','REMARKS');

		$objWorkSheet->SetCellValue('A2',' ');
		$objWorkSheet->SetCellValue('B2',' ');
		$objWorkSheet->SetCellValue('C2',' ');
		$objWorkSheet->SetCellValue('D2',' ');
		$objWorkSheet->SetCellValue('E2',' ');
		$objWorkSheet->SetCellValue('F2','HOURS.MINS');
		$objWorkSheet->SetCellValue('G2','HOURS.MINS');
		$objWorkSheet->SetCellValue('H2','HOURS.MINS');
		$objWorkSheet->SetCellValue('I2','HOURS.MINS');
		$objWorkSheet->SetCellValue('J2','HOURS.MINS');
		$objWorkSheet->SetCellValue('K2','HOURS.MINS');
		$objWorkSheet->SetCellValue('L2','HOURS.MINS');
		$objWorkSheet->SetCellValue('M2','HOURS.MINS');
		$objWorkSheet->SetCellValue('N2','DAYS');
		$objWorkSheet->SetCellValue('O2','DAYS');
		$objWorkSheet->SetCellValue('P2','HOURS.MINS');
		$objWorkSheet->SetCellValue('Q2',' ');

		$row = 3;

		foreach($data['employeesTabuk'] as $key => $value)
		{
			$in_office	= $data['schedules']->daily_in; 
			$out_office   = $data['schedules']->daily_out;
			$friday_out = $data['schedules']->daily_friday_out;
			$night_diff = '22:00';
			$in_daily = $value->intime;
			$out_daily = $value->outtime;
			$week_date = date('w', strtotime($value->dates)); // Convert in days . friday (5)

			// EXPLODE DATE IN TIME IN / TIME OUT
			$explode_in_date_daily = explode(" ", $in_daily);
			$explode_out_date_daily = explode(" ", $out_daily);
			$date_date_in = $explode_in_date_daily[0];
			$date_date_out = $explode_out_date_daily[0];
			$date_in = $explode_in_date_daily[1];
			$date_out = $explode_out_date_daily[1];

			//NIGHT DIFF
			$explode_night_diff = explode(":", $night_diff);
			$night_diff_hr = $explode_night_diff[0]; 
			$night_diff_min = $explode_night_diff[1]; 
			$total_night_diff = intval($night_diff_hr*60) + $night_diff_min; // total night diff

			// EXPLODE IN AND OUT 
			$explode_in_office = explode(":", $in_office);
			$explode_out_office = explode(":", $out_office);
			$explode_friday_out_office = explode(":", $friday_out);
			$explode_in_daily = explode(":", $date_in);
			$explode_out_daily = explode(":", $date_out);
			$time_in_hr_daily = $explode_in_daily[0];
			$time_in_min_daily = $explode_in_daily[1];
			$time_out_hr_daily = $explode_out_daily[0];
			$time_out_min_daily = $explode_out_daily[1];
			$time_in_hr = $explode_in_office[0];
			$time_in_min = $explode_in_office[1];
			$time_out_hr = $explode_out_office[0];
			$time_out_min = $explode_out_office[1];
			$time_friday_out_hr = $explode_friday_out_office[0];
			$time_friday_out_min = $explode_friday_out_office[1];


			// Convert IN AND OUT
			$total_in_min = intval($time_in_hr*60) + $time_in_min; // DEFAULT IN
			$total_in_min_grace = intval($time_in_hr*60) + $time_in_min + 15; // DEFAULT IN WITH GRACE PERIOD!
			$total_out_min = intval($time_out_hr*60) + $time_out_min; // DEFAULT OUT
			$total_friday_out_min = intval($time_friday_out_hr*60) + $time_friday_out_min; // DEFAULT OUT IN FRIDAY
			$total_in_daily = intval($time_in_hr_daily*60) + $time_in_min_daily; // EMPLOYEE IN
			$total_out_daily = intval($time_out_hr_daily*60) + $time_out_min_daily; // EMPLOYEE OUT

			//COMPUTATION IN OFFICE IN AND OUT
			$total_min_diff = intval($total_out_min - $total_in_min);
			$hr_diff = intval($total_min_diff/60);
			$min_diff = intval($total_min_diff%60);
			

			// IN AND OUT OF EMPLOYEE
			$in = strtotime($value->intime);
			$out   = strtotime($value->outtime);
			$diff  = $out - $in;

			//CONVERT OF IN AND OUT
			$hours = floor($diff / (60 * 60));
			$minutes = $diff - $hours * (60 * 60); 
			$total_minutes = floor( $minutes / 60 );
			
			// COMPUTATION OF IN AND OUT
			$total_number_of_hours = $hours.".".$total_minutes; //
			$total_office_hours = $hr_diff.".".$min_diff; // 9:30 Fixed
			$number_hr_daily = $total_number_of_hours; // TOTAL HOURS DAILY!
			$start_halfday = 660; 
			$end_halfday = 750;

			$objWorkSheet->SetCellValue('A'.$row,$value->employee_number);
			$objWorkSheet->SetCellValue('B'.$row,$value->name);
			$objWorkSheet->SetCellValue('C'.$row,$value->dates);
			$objWorkSheet->SetCellValue('D'.$row,$date_in);
			$objWorkSheet->SetCellValue('E'.$row,$date_out);

			//DAILY HOURS
			if($week_date >= 1 && $week_date <= 4)
			{
				if($date_date_in == $date_date_out && $total_in_daily == 0 && $total_out_daily == 0)
				{
					$objWorkSheet->SetCellValue('F'.$row," ");
				}
				elseif($total_in_min_grace >= $total_in_daily && $total_out_daily >= $total_out_min)
				{
					$total_min_diff = intval($total_out_min - $total_in_min - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}			
				elseif($total_in_daily > $total_in_min_grace && $total_out_daily < $total_out_min)
				{
					$total_min_diff = intval($total_out_daily - $total_in_daily - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($total_in_daily > $total_in_min_grace )
				{
					$total_min_diff = intval($total_out_min - $total_in_daily - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($total_out_daily < $total_out_min)
				{
					$total_min_diff = intval($total_out_daily - $total_in_min - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($total_in_daily >= $total_in_min_grace)
				{
					$total_min_diff = intval($total_out_min - $total_in_daily - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$min_diff."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($total_in_daily <= $total_in_min_grace)
				{
					$total_min_diff = intval($total_out_min - $total_in_min - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
			}
			elseif($week_date == 5)
			{
				if($date_date_in == $date_date_out && $total_in_daily == 0 && $total_out_daily == 0)
				{
					$objWorkSheet->SetCellValue('F'.$row," ");
				}
				elseif($date_date_in == $date_date_out && $total_in_min_grace >= $total_in_daily && $total_out_daily >= $total_friday_out_min)
				{
					$total_min_diff = intval($total_friday_out_min - $total_in_min );
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");

				}	
				elseif($date_date_in == $date_date_out && $total_in_daily > $total_in_min_grace && $total_out_daily < $total_friday_out_min)
				{
					$total_min_diff = intval($total_out_daily - $total_in_daily);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($date_date_in == $date_date_out && $total_in_daily > $total_in_min_grace )
				{
					$total_min_diff = intval($total_friday_out_min - $total_in_daily);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($date_date_in == $date_date_out && $total_out_daily < $total_friday_out_min)
				{
					$total_min_diff = intval($total_out_daily - $total_in_min);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($date_date_in != $date_date_out && $total_in_daily >= $total_in_min_grace)
				{
					$total_min_diff = intval($total_friday_out_min - $total_in_daily);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($date_date_in != $date_date_out || $total_in_daily <= $total_in_min_grace)
				{
					$total_min_diff = intval($total_friday_out_min - $total_in_min);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
			}
			elseif($week_date == 6)
			{
				$objWorkSheet->SetCellValue('F'.$row,"SATURDAY");
			}
			elseif($week_date == 0)
			{
				$objWorkSheet->SetCellValue('F'.$row,"SUNDAY");
			}
			else
			{
				$objWorkSheet->SetCellValue('F'.$row," ");
			}

			//TARDINESS
			if($week_date == 1 || $week_date == 2 || $week_date == 3 || $week_date == 4)
			{
				if($start_halfday <= $total_in_daily)
				{
					$objWorkSheet->SetCellValue('G'.$row," ");
				}
				else
				{
					if($total_in_daily > $total_in_min_grace)
					{
						$late_hr = intval($total_in_daily - $total_in_min);
						$hr_diff = intval($late_hr/60);
						$min_diff = intval($late_hr%60);
						//echo $hr_diff.".".$min_diff."";
						$minn = sprintf("%02d", $min_diff);
						$number_of_late = $hr_diff.".".$minn;
						$objWorkSheet->SetCellValue('G'.$row,$hr_diff.".".$minn."");
						$hr_lte = $late_hr;
					}
					else
					{
						$objWorkSheet->SetCellValue('G'.$row," ");
					}
				}
			}	
			elseif($week_date == 5)
			{
				if($start_halfday <= $total_in_daily)
				{
					$objWorkSheet->SetCellValue('G'.$row," ");
				}
				else
				{
					if($total_in_daily > $total_in_min_grace)
					{
						$late_hr = intval($total_in_daily - $total_in_min);
						$hr_diff = intval($late_hr/60);
						$min_diff = intval($late_hr%60);
						$objWorkSheet->SetCellValue('G'.$row,$hr_diff.".".$min_diff."");
						$minn = sprintf("%02d", $min_diff);
					}
					else
					{
						$objWorkSheet->SetCellValue('G'.$row," ");
					}
				}
				
			}
			else
			{
				$objWorkSheet->SetCellValue('G'.$row," ");
			}
			
			//UNDERTIME
			$halfday_in = 810;
			if($week_date == 1 || $week_date == 2 || $week_date == 3 || $week_date == 4)
			{
				if($date_date_in == $date_date_out && $total_out_daily < $halfday_in)
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
				elseif($total_out_min <= $total_out_daily)
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
				elseif($total_out_daily > $halfday_in)
				{
					$undertime_hr = intval($total_out_min - $total_out_daily);
					$hr_diff = intval($undertime_hr/60);
					$min_diff = intval($undertime_hr%60);
					$minn = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('H'.$row,$hr_diff.".".$minn);
				}
				else
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
			}
			elseif($week_date == 5)
			{
				if($date_date_in == $date_date_out && $total_out_daily < $halfday_in)
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
				elseif($total_friday_out_min <= $total_out_daily)
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
				elseif($date_date_in == $date_date_out && $halfday_in < $total_out_daily)
				{
					$undertime_hr = intval($total_friday_out_min - $total_out_daily);
					$hr_diff = intval($undertime_hr/60);
					$min_diff = intval($undertime_hr%60);
					$minn = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('H'.$row,$hr_diff.".".$minn);
				}
				else
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
			}
			else
			{
				$objWorkSheet->SetCellValue('H'.$row," ");
			}

			//REGULAR OT
			foreach($data['rots'] as $key => $value1)
			{
				if($value1->ot_employee_number == $value->employee_number && $value1->date_ot == $value->dates)
				{
					$reg_ot = $value1->total_ot;
					$hr_diff = intval($reg_ot/60);
					$min_diff = intval($reg_ot%60);
					$min_diff1 = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('I'.$row,$hr_diff.".".$min_diff1);
				}
			}	

			//LEGAL HOLIDAY OT
			foreach($data['lots'] as $key => $value2)
			{
				if($value2->legal_ot_employee_number == $value->employee_number && $value2->date_ot == $value->dates)
				{
					$reg_ot = $value2->total_ot;
					$hr_diff = intval($reg_ot/60);
					$min_diff = intval($reg_ot%60);
					$min_diff1 = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('J'.$row,$hr_diff.".".$min_diff1);
				}
			}

			//SPECIAL HOLIDAY OT
			foreach($data['shots'] as $key => $value3)
			{
				if($value3->special_ot_employee_number == $value->employee_number && $value3->date_ot == $value->dates)
				{
					$reg_ot = $value3->total_ot;
					$hr_diff = intval($reg_ot/60);
					$min_diff = intval($reg_ot%60);
					$min_diff1 = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('K'.$row,$hr_diff.".".$min_diff1);
				}
			}	

			//REST DAY OT
			foreach($data['rdots'] as $key => $value4)
			{
				if($value4->restday_ot_employee_number == $value->employee_number && $value4->date_ot == $value->dates)
				{
					$reg_ot = $value4->total_ot;
					$hr_diff = intval($reg_ot/60);
					$min_diff = intval($reg_ot%60);
					$min_diff1 = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('L'.$row,$hr_diff.".".$min_diff1);
				}
			}	

			//NIGHT DIFF
			$set_night_diff_morning = '6:00';
			$explode_night_diff_morning = explode(':', $set_night_diff_morning);
			$night_diff_morning = intval($explode_night_diff_morning[0]*60);
			$compute_night_diff_morning =$night_diff_morning - $total_in_daily;
			if($total_in_daily < $night_diff_morning)
			{
				$compute_night_diff_morning;
				$hr_diff = intval($compute_night_diff_morning/60);
				$min_diff = intval($compute_night_diff_morning%60);
				if($total_in_daily == 0 && $total_out_daily == 0)
				{
					$nd = ' ';
					//echo 0;
					$objWorkSheet->SetCellValue('M'.$row, $nd);
				}
				elseif($min_diff > 30 || $min_diff == 0)
				{
					$nd = $hr_diff."."."30";
					//echo $hr_diff."."."30";
					$objWorkSheet->SetCellValue('M'.$row, $nd);
				}
				elseif($min_diff < 30)
				{
					$nd = $hr_diff;
					$objWorkSheet->SetCellValue('M'.$row, $nd);
					//echo $hr_diff;
				}
			}
			else
			{
				$nd = ' ';
				$objWorkSheet->SetCellValue('M'.$row, $nd);
				//echo 0;
			}

			//SICK LEAVE
			foreach($data['sl'] as $key => $value5)
			{
				if($value5->slvl_employee_number == $value->employee_number && $value5->sl_date == $value->dates)
				{
					$sl_per_day = $value5->total_slvl;
					$objWorkSheet->SetCellValue('N'.$row,$sl_per_day);
				}
			}

			//VACATION LEAVE
			foreach($data['vl'] as $key => $value6)
			{
				if($value6->slvl_employee_number == $value->employee_number && $value6->vl_date == $value->dates)
				{
					$vl_per_day = $value6->total_slvl;
					$objWorkSheet->SetCellValue('O'.$row,$vl_per_day);
				}
			}

			//ABSENCES
			foreach($data['ab'] as $key => $value7)
			{
				if($value7->slvl_employee_number == $value->employee_number && $value7->ab_date == $value->dates && $value7->slvl_type == 'AB')
				{
					$ab_per_day = $value7->total_slvl;
					$reg_ab = $ab_per_day;
					$hr_diff = intval($reg_ab/60);
					$min_diff = intval($reg_ab%60);
					$min_diff1 = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('P'.$row,$hr_diff.".".$min_diff1);
				}
			}

			//REMARKS
			foreach($data['remarks'] as $key => $value8)
			{
				if($value8->date == $value->dates && $value8->remarks_employee_number == $value->employee_number)
    		{
    			$remarkss = $value8->type_name;
    			$objWorkSheet->SetCellValue('Q'.$row,$remarkss);
    		}
			}

			$row++;
		}	
    $objWorkSheet->setTitle("TABUK");


    $i++;
    
    }

    /////////////////////////////////////////////////// VIGAN \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

    $i=0;
    while ($i < 1) {

      // Add new sheet
    $objWorkSheet = $objPHPExcel->createSheet($i); //Setting index when creating

	
		foreach(range('A','Q') as $columnID) {
    $objWorkSheet->getColumnDimension($columnID)
        ->setAutoSize(true);
		} 
		
		$objWorkSheet->SetCellValue('A1','EMPLOYEE NUMBER'); 
		$objWorkSheet->SetCellValue('B1','EMPLOYEE NAME');
		$objWorkSheet->SetCellValue('C1','DATES');
		$objWorkSheet->SetCellValue('D1','TIME IN');
		$objWorkSheet->SetCellValue('E1','TIME OUT');
		$objWorkSheet->SetCellValue('F1','Daily Hours');
		$objWorkSheet->SetCellValue('G1','TARDINESS');
		$objWorkSheet->SetCellValue('H1','UNDERTIME');
		$objWorkSheet->SetCellValue('I1','REGULAR OT');
		$objWorkSheet->SetCellValue('J1','LEGAL HOLIDAY OT');
		$objWorkSheet->SetCellValue('K1','SPECIAL HOLIDAY OT');
		$objWorkSheet->SetCellValue('L1','RESTDAY OT');
		$objWorkSheet->SetCellValue('M1','NSD');
		$objWorkSheet->SetCellValue('N1','SICK LEAVE');
		$objWorkSheet->SetCellValue('O1','VACATION LEAVE');
		$objWorkSheet->SetCellValue('P1','ABSENCES');
		$objWorkSheet->SetCellValue('Q1','REMARKS');

		$objWorkSheet->SetCellValue('A2',' ');
		$objWorkSheet->SetCellValue('B2',' ');
		$objWorkSheet->SetCellValue('C2',' ');
		$objWorkSheet->SetCellValue('D2',' ');
		$objWorkSheet->SetCellValue('E2',' ');
		$objWorkSheet->SetCellValue('F2','HOURS.MINS');
		$objWorkSheet->SetCellValue('G2','HOURS.MINS');
		$objWorkSheet->SetCellValue('H2','HOURS.MINS');
		$objWorkSheet->SetCellValue('I2','HOURS.MINS');
		$objWorkSheet->SetCellValue('J2','HOURS.MINS');
		$objWorkSheet->SetCellValue('K2','HOURS.MINS');
		$objWorkSheet->SetCellValue('L2','HOURS.MINS');
		$objWorkSheet->SetCellValue('M2','HOURS.MINS');
		$objWorkSheet->SetCellValue('N2','DAYS');
		$objWorkSheet->SetCellValue('O2','DAYS');
		$objWorkSheet->SetCellValue('P2','HOURS.MINS');
		$objWorkSheet->SetCellValue('Q2',' ');

		$row = 3;

		foreach($data['employeesVigan'] as $key => $value)
		{
			$in_office	= $data['schedules']->daily_in; 
			$out_office   = $data['schedules']->daily_out;
			$friday_out = $data['schedules']->daily_friday_out;
			$night_diff = '22:00';
			$in_daily = $value->intime;
			$out_daily = $value->outtime;
			$week_date = date('w', strtotime($value->dates)); // Convert in days . friday (5)

			// EXPLODE DATE IN TIME IN / TIME OUT
			$explode_in_date_daily = explode(" ", $in_daily);
			$explode_out_date_daily = explode(" ", $out_daily);
			$date_date_in = $explode_in_date_daily[0];
			$date_date_out = $explode_out_date_daily[0];
			$date_in = $explode_in_date_daily[1];
			$date_out = $explode_out_date_daily[1];

			//NIGHT DIFF
			$explode_night_diff = explode(":", $night_diff);
			$night_diff_hr = $explode_night_diff[0]; 
			$night_diff_min = $explode_night_diff[1]; 
			$total_night_diff = intval($night_diff_hr*60) + $night_diff_min; // total night diff

			// EXPLODE IN AND OUT 
			$explode_in_office = explode(":", $in_office);
			$explode_out_office = explode(":", $out_office);
			$explode_friday_out_office = explode(":", $friday_out);
			$explode_in_daily = explode(":", $date_in);
			$explode_out_daily = explode(":", $date_out);
			$time_in_hr_daily = $explode_in_daily[0];
			$time_in_min_daily = $explode_in_daily[1];
			$time_out_hr_daily = $explode_out_daily[0];
			$time_out_min_daily = $explode_out_daily[1];
			$time_in_hr = $explode_in_office[0];
			$time_in_min = $explode_in_office[1];
			$time_out_hr = $explode_out_office[0];
			$time_out_min = $explode_out_office[1];
			$time_friday_out_hr = $explode_friday_out_office[0];
			$time_friday_out_min = $explode_friday_out_office[1];


			// Convert IN AND OUT
			$total_in_min = intval($time_in_hr*60) + $time_in_min; // DEFAULT IN
			$total_in_min_grace = intval($time_in_hr*60) + $time_in_min + 15; // DEFAULT IN WITH GRACE PERIOD!
			$total_out_min = intval($time_out_hr*60) + $time_out_min; // DEFAULT OUT
			$total_friday_out_min = intval($time_friday_out_hr*60) + $time_friday_out_min; // DEFAULT OUT IN FRIDAY
			$total_in_daily = intval($time_in_hr_daily*60) + $time_in_min_daily; // EMPLOYEE IN
			$total_out_daily = intval($time_out_hr_daily*60) + $time_out_min_daily; // EMPLOYEE OUT

			//COMPUTATION IN OFFICE IN AND OUT
			$total_min_diff = intval($total_out_min - $total_in_min);
			$hr_diff = intval($total_min_diff/60);
			$min_diff = intval($total_min_diff%60);
			

			// IN AND OUT OF EMPLOYEE
			$in = strtotime($value->intime);
			$out   = strtotime($value->outtime);
			$diff  = $out - $in;

			//CONVERT OF IN AND OUT
			$hours = floor($diff / (60 * 60));
			$minutes = $diff - $hours * (60 * 60); 
			$total_minutes = floor( $minutes / 60 );
			
			// COMPUTATION OF IN AND OUT
			$total_number_of_hours = $hours.".".$total_minutes; //
			$total_office_hours = $hr_diff.".".$min_diff; // 9:30 Fixed
			$number_hr_daily = $total_number_of_hours; // TOTAL HOURS DAILY!
			$start_halfday = 660; 
			$end_halfday = 750;

			$objWorkSheet->SetCellValue('A'.$row,$value->employee_number);
			$objWorkSheet->SetCellValue('B'.$row,$value->name);
			$objWorkSheet->SetCellValue('C'.$row,$value->dates);
			$objWorkSheet->SetCellValue('D'.$row,$date_in);
			$objWorkSheet->SetCellValue('E'.$row,$date_out);

			//DAILY HOURS
			if($week_date >= 1 && $week_date <= 4)
			{
				if($date_date_in == $date_date_out && $total_in_daily == 0 && $total_out_daily == 0)
				{
					$objWorkSheet->SetCellValue('F'.$row," ");
				}
				elseif($total_in_min_grace >= $total_in_daily && $total_out_daily >= $total_out_min)
				{
					$total_min_diff = intval($total_out_min - $total_in_min - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}			
				elseif($total_in_daily > $total_in_min_grace && $total_out_daily < $total_out_min)
				{
					$total_min_diff = intval($total_out_daily - $total_in_daily - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($total_in_daily > $total_in_min_grace )
				{
					$total_min_diff = intval($total_out_min - $total_in_daily - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($total_out_daily < $total_out_min)
				{
					$total_min_diff = intval($total_out_daily - $total_in_min - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($total_in_daily >= $total_in_min_grace)
				{
					$total_min_diff = intval($total_out_min - $total_in_daily - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$min_diff."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($total_in_daily <= $total_in_min_grace)
				{
					$total_min_diff = intval($total_out_min - $total_in_min - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
			}
			elseif($week_date == 5)
			{
				if($date_date_in == $date_date_out && $total_in_daily == 0 && $total_out_daily == 0)
				{
					$objWorkSheet->SetCellValue('F'.$row," ");
				}
				elseif($date_date_in == $date_date_out && $total_in_min_grace >= $total_in_daily && $total_out_daily >= $total_friday_out_min)
				{
					$total_min_diff = intval($total_friday_out_min - $total_in_min );
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");

				}	
				elseif($date_date_in == $date_date_out && $total_in_daily > $total_in_min_grace && $total_out_daily < $total_friday_out_min)
				{
					$total_min_diff = intval($total_out_daily - $total_in_daily);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($date_date_in == $date_date_out && $total_in_daily > $total_in_min_grace )
				{
					$total_min_diff = intval($total_friday_out_min - $total_in_daily);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($date_date_in == $date_date_out && $total_out_daily < $total_friday_out_min)
				{
					$total_min_diff = intval($total_out_daily - $total_in_min);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($date_date_in != $date_date_out && $total_in_daily >= $total_in_min_grace)
				{
					$total_min_diff = intval($total_friday_out_min - $total_in_daily);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($date_date_in != $date_date_out || $total_in_daily <= $total_in_min_grace)
				{
					$total_min_diff = intval($total_friday_out_min - $total_in_min);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
			}
			elseif($week_date == 6)
			{
				$objWorkSheet->SetCellValue('F'.$row,"SATURDAY");
			}
			elseif($week_date == 0)
			{
				$objWorkSheet->SetCellValue('F'.$row,"SUNDAY");
			}
			else
			{
				$objWorkSheet->SetCellValue('F'.$row," ");
			}

			//TARDINESS
			if($week_date == 1 || $week_date == 2 || $week_date == 3 || $week_date == 4)
			{
				if($start_halfday <= $total_in_daily)
				{
					$objWorkSheet->SetCellValue('G'.$row," ");
				}
				else
				{
					if($total_in_daily > $total_in_min_grace)
					{
						$late_hr = intval($total_in_daily - $total_in_min);
						$hr_diff = intval($late_hr/60);
						$min_diff = intval($late_hr%60);
						//echo $hr_diff.".".$min_diff."";
						$minn = sprintf("%02d", $min_diff);
						$number_of_late = $hr_diff.".".$minn;
						$objWorkSheet->SetCellValue('G'.$row,$hr_diff.".".$minn."");
						$hr_lte = $late_hr;
					}
					else
					{
						$objWorkSheet->SetCellValue('G'.$row," ");
					}
				}
			}	
			elseif($week_date == 5)
			{
				if($start_halfday <= $total_in_daily)
				{
					$objWorkSheet->SetCellValue('G'.$row," ");
				}
				else
				{
					if($total_in_daily > $total_in_min_grace)
					{
						$late_hr = intval($total_in_daily - $total_in_min);
						$hr_diff = intval($late_hr/60);
						$min_diff = intval($late_hr%60);
						$objWorkSheet->SetCellValue('G'.$row,$hr_diff.".".$min_diff."");
						$minn = sprintf("%02d", $min_diff);
					}
					else
					{
						$objWorkSheet->SetCellValue('G'.$row," ");
					}
				}
				
			}
			else
			{
				$objWorkSheet->SetCellValue('G'.$row," ");
			}
			
			//UNDERTIME
			$halfday_in = 810;
			if($week_date == 1 || $week_date == 2 || $week_date == 3 || $week_date == 4)
			{
				if($date_date_in == $date_date_out && $total_out_daily < $halfday_in)
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
				elseif($total_out_min <= $total_out_daily)
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
				elseif($total_out_daily > $halfday_in)
				{
					$undertime_hr = intval($total_out_min - $total_out_daily);
					$hr_diff = intval($undertime_hr/60);
					$min_diff = intval($undertime_hr%60);
					$minn = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('H'.$row,$hr_diff.".".$minn);
				}
				else
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
			}
			elseif($week_date == 5)
			{
				if($date_date_in == $date_date_out && $total_out_daily < $halfday_in)
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
				elseif($total_friday_out_min <= $total_out_daily)
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
				elseif($date_date_in == $date_date_out && $halfday_in < $total_out_daily)
				{
					$undertime_hr = intval($total_friday_out_min - $total_out_daily);
					$hr_diff = intval($undertime_hr/60);
					$min_diff = intval($undertime_hr%60);
					$minn = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('H'.$row,$hr_diff.".".$minn);
				}
				else
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
			}
			else
			{
				$objWorkSheet->SetCellValue('H'.$row," ");
			}

			//REGULAR OT
			foreach($data['rots'] as $key => $value1)
			{
				if($value1->ot_employee_number == $value->employee_number && $value1->date_ot == $value->dates)
				{
					$reg_ot = $value1->total_ot;
					$hr_diff = intval($reg_ot/60);
					$min_diff = intval($reg_ot%60);
					$min_diff1 = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('I'.$row,$hr_diff.".".$min_diff1);
				}
			}	

			//LEGAL HOLIDAY OT
			foreach($data['lots'] as $key => $value2)
			{
				if($value2->legal_ot_employee_number == $value->employee_number && $value2->date_ot == $value->dates)
				{
					$reg_ot = $value2->total_ot;
					$hr_diff = intval($reg_ot/60);
					$min_diff = intval($reg_ot%60);
					$min_diff1 = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('J'.$row,$hr_diff.".".$min_diff1);
				}
			}

			//SPECIAL HOLIDAY OT
			foreach($data['shots'] as $key => $value3)
			{
				if($value3->special_ot_employee_number == $value->employee_number && $value3->date_ot == $value->dates)
				{
					$reg_ot = $value3->total_ot;
					$hr_diff = intval($reg_ot/60);
					$min_diff = intval($reg_ot%60);
					$min_diff1 = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('K'.$row,$hr_diff.".".$min_diff1);
				}
			}	

			//REST DAY OT
			foreach($data['rdots'] as $key => $value4)
			{
				if($value4->restday_ot_employee_number == $value->employee_number && $value4->date_ot == $value->dates)
				{
					$reg_ot = $value4->total_ot;
					$hr_diff = intval($reg_ot/60);
					$min_diff = intval($reg_ot%60);
					$min_diff1 = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('L'.$row,$hr_diff.".".$min_diff1);
				}
			}	

			//NIGHT DIFF
			$set_night_diff_morning = '6:00';
			$explode_night_diff_morning = explode(':', $set_night_diff_morning);
			$night_diff_morning = intval($explode_night_diff_morning[0]*60);
			$compute_night_diff_morning =$night_diff_morning - $total_in_daily;
			if($total_in_daily < $night_diff_morning)
			{
				$compute_night_diff_morning;
				$hr_diff = intval($compute_night_diff_morning/60);
				$min_diff = intval($compute_night_diff_morning%60);
				if($total_in_daily == 0 && $total_out_daily == 0)
				{
					$nd = ' ';
					//echo 0;
					$objWorkSheet->SetCellValue('M'.$row, $nd);
				}
				elseif($min_diff > 30 || $min_diff == 0)
				{
					$nd = $hr_diff."."."30";
					//echo $hr_diff."."."30";
					$objWorkSheet->SetCellValue('M'.$row, $nd);
				}
				elseif($min_diff < 30)
				{
					$nd = $hr_diff;
					$objWorkSheet->SetCellValue('M'.$row, $nd);
					//echo $hr_diff;
				}
			}
			else
			{
				$nd = ' ';
				$objWorkSheet->SetCellValue('M'.$row, $nd);
				//echo 0;
			}

			//SICK LEAVE
			foreach($data['sl'] as $key => $value5)
			{
				if($value5->slvl_employee_number == $value->employee_number && $value5->sl_date == $value->dates)
				{
					$sl_per_day = $value5->total_slvl;
					$objWorkSheet->SetCellValue('N'.$row,$sl_per_day);
				}
			}

			//VACATION LEAVE
			foreach($data['vl'] as $key => $value6)
			{
				if($value6->slvl_employee_number == $value->employee_number && $value6->vl_date == $value->dates)
				{
					$vl_per_day = $value6->total_slvl;
					$objWorkSheet->SetCellValue('O'.$row,$vl_per_day);
				}
			}

			//ABSENCES
			foreach($data['ab'] as $key => $value7)
			{
				if($value7->slvl_employee_number == $value->employee_number && $value7->ab_date == $value->dates && $value7->slvl_type == 'AB')
				{
					$ab_per_day = $value7->total_slvl;
					$reg_ab = $ab_per_day;
					$hr_diff = intval($reg_ab/60);
					$min_diff = intval($reg_ab%60);
					$min_diff1 = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('P'.$row,$hr_diff.".".$min_diff1);
				}
			}

			//REMARKS
			foreach($data['remarks'] as $key => $value8)
			{
				if($value8->date == $value->dates && $value8->remarks_employee_number == $value->employee_number)
    		{
    			$remarkss = $value8->type_name;
    			$objWorkSheet->SetCellValue('Q'.$row,$remarkss);
    		}
			}

			$row++;
		}	
    $objWorkSheet->setTitle("VIGAN");


    $i++;
    
    } 

    ////////////////////////////////////////////////// ZAMBALES \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

    $i=0;
    while ($i < 1) {

      // Add new sheet
    $objWorkSheet = $objPHPExcel->createSheet($i); //Setting index when creating

	
		foreach(range('A','Q') as $columnID) {
    $objWorkSheet->getColumnDimension($columnID)
        ->setAutoSize(true);
		} 
		
		$objWorkSheet->SetCellValue('A1','EMPLOYEE NUMBER'); 
		$objWorkSheet->SetCellValue('B1','EMPLOYEE NAME');
		$objWorkSheet->SetCellValue('C1','DATES');
		$objWorkSheet->SetCellValue('D1','TIME IN');
		$objWorkSheet->SetCellValue('E1','TIME OUT');
		$objWorkSheet->SetCellValue('F1','Daily Hours');
		$objWorkSheet->SetCellValue('G1','TARDINESS');
		$objWorkSheet->SetCellValue('H1','UNDERTIME');
		$objWorkSheet->SetCellValue('I1','REGULAR OT');
		$objWorkSheet->SetCellValue('J1','LEGAL HOLIDAY OT');
		$objWorkSheet->SetCellValue('K1','SPECIAL HOLIDAY OT');
		$objWorkSheet->SetCellValue('L1','RESTDAY OT');
		$objWorkSheet->SetCellValue('M1','NSD');
		$objWorkSheet->SetCellValue('N1','SICK LEAVE');
		$objWorkSheet->SetCellValue('O1','VACATION LEAVE');
		$objWorkSheet->SetCellValue('P1','ABSENCES');
		$objWorkSheet->SetCellValue('Q1','REMARKS');

		$objWorkSheet->SetCellValue('A2',' ');
		$objWorkSheet->SetCellValue('B2',' ');
		$objWorkSheet->SetCellValue('C2',' ');
		$objWorkSheet->SetCellValue('D2',' ');
		$objWorkSheet->SetCellValue('E2',' ');
		$objWorkSheet->SetCellValue('F2','HOURS.MINS');
		$objWorkSheet->SetCellValue('G2','HOURS.MINS');
		$objWorkSheet->SetCellValue('H2','HOURS.MINS');
		$objWorkSheet->SetCellValue('I2','HOURS.MINS');
		$objWorkSheet->SetCellValue('J2','HOURS.MINS');
		$objWorkSheet->SetCellValue('K2','HOURS.MINS');
		$objWorkSheet->SetCellValue('L2','HOURS.MINS');
		$objWorkSheet->SetCellValue('M2','HOURS.MINS');
		$objWorkSheet->SetCellValue('N2','DAYS');
		$objWorkSheet->SetCellValue('O2','DAYS');
		$objWorkSheet->SetCellValue('P2','HOURS.MINS');
		$objWorkSheet->SetCellValue('Q2',' ');

		$row = 3;

		foreach($data['employeesZambales'] as $key => $value)
		{
			$in_office	= $data['schedules']->daily_in; 
			$out_office   = $data['schedules']->daily_out;
			$friday_out = $data['schedules']->daily_friday_out;
			$night_diff = '22:00';
			$in_daily = $value->intime;
			$out_daily = $value->outtime;
			$week_date = date('w', strtotime($value->dates)); // Convert in days . friday (5)

			// EXPLODE DATE IN TIME IN / TIME OUT
			$explode_in_date_daily = explode(" ", $in_daily);
			$explode_out_date_daily = explode(" ", $out_daily);
			$date_date_in = $explode_in_date_daily[0];
			$date_date_out = $explode_out_date_daily[0];
			$date_in = $explode_in_date_daily[1];
			$date_out = $explode_out_date_daily[1];

			//NIGHT DIFF
			$explode_night_diff = explode(":", $night_diff);
			$night_diff_hr = $explode_night_diff[0]; 
			$night_diff_min = $explode_night_diff[1]; 
			$total_night_diff = intval($night_diff_hr*60) + $night_diff_min; // total night diff

			// EXPLODE IN AND OUT 
			$explode_in_office = explode(":", $in_office);
			$explode_out_office = explode(":", $out_office);
			$explode_friday_out_office = explode(":", $friday_out);
			$explode_in_daily = explode(":", $date_in);
			$explode_out_daily = explode(":", $date_out);
			$time_in_hr_daily = $explode_in_daily[0];
			$time_in_min_daily = $explode_in_daily[1];
			$time_out_hr_daily = $explode_out_daily[0];
			$time_out_min_daily = $explode_out_daily[1];
			$time_in_hr = $explode_in_office[0];
			$time_in_min = $explode_in_office[1];
			$time_out_hr = $explode_out_office[0];
			$time_out_min = $explode_out_office[1];
			$time_friday_out_hr = $explode_friday_out_office[0];
			$time_friday_out_min = $explode_friday_out_office[1];


			// Convert IN AND OUT
			$total_in_min = intval($time_in_hr*60) + $time_in_min; // DEFAULT IN
			$total_in_min_grace = intval($time_in_hr*60) + $time_in_min + 15; // DEFAULT IN WITH GRACE PERIOD!
			$total_out_min = intval($time_out_hr*60) + $time_out_min; // DEFAULT OUT
			$total_friday_out_min = intval($time_friday_out_hr*60) + $time_friday_out_min; // DEFAULT OUT IN FRIDAY
			$total_in_daily = intval($time_in_hr_daily*60) + $time_in_min_daily; // EMPLOYEE IN
			$total_out_daily = intval($time_out_hr_daily*60) + $time_out_min_daily; // EMPLOYEE OUT

			//COMPUTATION IN OFFICE IN AND OUT
			$total_min_diff = intval($total_out_min - $total_in_min);
			$hr_diff = intval($total_min_diff/60);
			$min_diff = intval($total_min_diff%60);
			

			// IN AND OUT OF EMPLOYEE
			$in = strtotime($value->intime);
			$out   = strtotime($value->outtime);
			$diff  = $out - $in;

			//CONVERT OF IN AND OUT
			$hours = floor($diff / (60 * 60));
			$minutes = $diff - $hours * (60 * 60); 
			$total_minutes = floor( $minutes / 60 );
			
			// COMPUTATION OF IN AND OUT
			$total_number_of_hours = $hours.".".$total_minutes; //
			$total_office_hours = $hr_diff.".".$min_diff; // 9:30 Fixed
			$number_hr_daily = $total_number_of_hours; // TOTAL HOURS DAILY!
			$start_halfday = 660; 
			$end_halfday = 750;

			$objWorkSheet->SetCellValue('A'.$row,$value->employee_number);
			$objWorkSheet->SetCellValue('B'.$row,$value->name);
			$objWorkSheet->SetCellValue('C'.$row,$value->dates);
			$objWorkSheet->SetCellValue('D'.$row,$date_in);
			$objWorkSheet->SetCellValue('E'.$row,$date_out);

			//DAILY HOURS
			if($week_date >= 1 && $week_date <= 4)
			{
				if($date_date_in == $date_date_out && $total_in_daily == 0 && $total_out_daily == 0)
				{
					$objWorkSheet->SetCellValue('F'.$row," ");
				}
				elseif($total_in_min_grace >= $total_in_daily && $total_out_daily >= $total_out_min)
				{
					$total_min_diff = intval($total_out_min - $total_in_min - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}			
				elseif($total_in_daily > $total_in_min_grace && $total_out_daily < $total_out_min)
				{
					$total_min_diff = intval($total_out_daily - $total_in_daily - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($total_in_daily > $total_in_min_grace )
				{
					$total_min_diff = intval($total_out_min - $total_in_daily - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($total_out_daily < $total_out_min)
				{
					$total_min_diff = intval($total_out_daily - $total_in_min - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($total_in_daily >= $total_in_min_grace)
				{
					$total_min_diff = intval($total_out_min - $total_in_daily - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$min_diff."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($total_in_daily <= $total_in_min_grace)
				{
					$total_min_diff = intval($total_out_min - $total_in_min - 60);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
			}
			elseif($week_date == 5)
			{
				if($date_date_in == $date_date_out && $total_in_daily == 0 && $total_out_daily == 0)
				{
					$objWorkSheet->SetCellValue('F'.$row," ");
				}
				elseif($date_date_in == $date_date_out && $total_in_min_grace >= $total_in_daily && $total_out_daily >= $total_friday_out_min)
				{
					$total_min_diff = intval($total_friday_out_min - $total_in_min );
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");

				}	
				elseif($date_date_in == $date_date_out && $total_in_daily > $total_in_min_grace && $total_out_daily < $total_friday_out_min)
				{
					$total_min_diff = intval($total_out_daily - $total_in_daily);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($date_date_in == $date_date_out && $total_in_daily > $total_in_min_grace )
				{
					$total_min_diff = intval($total_friday_out_min - $total_in_daily);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($date_date_in == $date_date_out && $total_out_daily < $total_friday_out_min)
				{
					$total_min_diff = intval($total_out_daily - $total_in_min);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($date_date_in != $date_date_out && $total_in_daily >= $total_in_min_grace)
				{
					$total_min_diff = intval($total_friday_out_min - $total_in_daily);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
				elseif($date_date_in != $date_date_out || $total_in_daily <= $total_in_min_grace)
				{
					$total_min_diff = intval($total_friday_out_min - $total_in_min);
					$hr_diff = intval($total_min_diff/60);
					$min_diff = intval($total_min_diff%60);
					$hrs1 = sprintf("%02d", $min_diff);
					//echo $hr_diff.".".$hrs1."";
					$dly_hrs = $total_min_diff;
					$objWorkSheet->SetCellValue('F'.$row,$hr_diff.".".$hrs1."");
				}
			}
			elseif($week_date == 6)
			{
				$objWorkSheet->SetCellValue('F'.$row,"SATURDAY");
			}
			elseif($week_date == 0)
			{
				$objWorkSheet->SetCellValue('F'.$row,"SUNDAY");
			}
			else
			{
				$objWorkSheet->SetCellValue('F'.$row," ");
			}

			//TARDINESS
			if($week_date == 1 || $week_date == 2 || $week_date == 3 || $week_date == 4)
			{
				if($start_halfday <= $total_in_daily)
				{
					$objWorkSheet->SetCellValue('G'.$row," ");
				}
				else
				{
					if($total_in_daily > $total_in_min_grace)
					{
						$late_hr = intval($total_in_daily - $total_in_min);
						$hr_diff = intval($late_hr/60);
						$min_diff = intval($late_hr%60);
						//echo $hr_diff.".".$min_diff."";
						$minn = sprintf("%02d", $min_diff);
						$number_of_late = $hr_diff.".".$minn;
						$objWorkSheet->SetCellValue('G'.$row,$hr_diff.".".$minn."");
						$hr_lte = $late_hr;
					}
					else
					{
						$objWorkSheet->SetCellValue('G'.$row," ");
					}
				}
			}	
			elseif($week_date == 5)
			{
				if($start_halfday <= $total_in_daily)
				{
					$objWorkSheet->SetCellValue('G'.$row," ");
				}
				else
				{
					if($total_in_daily > $total_in_min_grace)
					{
						$late_hr = intval($total_in_daily - $total_in_min);
						$hr_diff = intval($late_hr/60);
						$min_diff = intval($late_hr%60);
						$objWorkSheet->SetCellValue('G'.$row,$hr_diff.".".$min_diff."");
						$minn = sprintf("%02d", $min_diff);
					}
					else
					{
						$objWorkSheet->SetCellValue('G'.$row," ");
					}
				}
				
			}
			else
			{
				$objWorkSheet->SetCellValue('G'.$row," ");
			}
			
			//UNDERTIME
			$halfday_in = 810;
			if($week_date == 1 || $week_date == 2 || $week_date == 3 || $week_date == 4)
			{
				if($date_date_in == $date_date_out && $total_out_daily < $halfday_in)
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
				elseif($total_out_min <= $total_out_daily)
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
				elseif($total_out_daily > $halfday_in)
				{
					$undertime_hr = intval($total_out_min - $total_out_daily);
					$hr_diff = intval($undertime_hr/60);
					$min_diff = intval($undertime_hr%60);
					$minn = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('H'.$row,$hr_diff.".".$minn);
				}
				else
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
			}
			elseif($week_date == 5)
			{
				if($date_date_in == $date_date_out && $total_out_daily < $halfday_in)
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
				elseif($total_friday_out_min <= $total_out_daily)
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
				elseif($date_date_in == $date_date_out && $halfday_in < $total_out_daily)
				{
					$undertime_hr = intval($total_friday_out_min - $total_out_daily);
					$hr_diff = intval($undertime_hr/60);
					$min_diff = intval($undertime_hr%60);
					$minn = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('H'.$row,$hr_diff.".".$minn);
				}
				else
				{
					$objWorkSheet->SetCellValue('H'.$row," ");
				}
			}
			else
			{
				$objWorkSheet->SetCellValue('H'.$row," ");
			}

			//REGULAR OT
			foreach($data['rots'] as $key => $value1)
			{
				if($value1->ot_employee_number == $value->employee_number && $value1->date_ot == $value->dates)
				{
					$reg_ot = $value1->total_ot;
					$hr_diff = intval($reg_ot/60);
					$min_diff = intval($reg_ot%60);
					$min_diff1 = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('I'.$row,$hr_diff.".".$min_diff1);
				}
			}	

			//LEGAL HOLIDAY OT
			foreach($data['lots'] as $key => $value2)
			{
				if($value2->legal_ot_employee_number == $value->employee_number && $value2->date_ot == $value->dates)
				{
					$reg_ot = $value2->total_ot;
					$hr_diff = intval($reg_ot/60);
					$min_diff = intval($reg_ot%60);
					$min_diff1 = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('J'.$row,$hr_diff.".".$min_diff1);
				}
			}

			//SPECIAL HOLIDAY OT
			foreach($data['shots'] as $key => $value3)
			{
				if($value3->special_ot_employee_number == $value->employee_number && $value3->date_ot == $value->dates)
				{
					$reg_ot = $value3->total_ot;
					$hr_diff = intval($reg_ot/60);
					$min_diff = intval($reg_ot%60);
					$min_diff1 = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('K'.$row,$hr_diff.".".$min_diff1);
				}
			}	

			//REST DAY OT
			foreach($data['rdots'] as $key => $value4)
			{
				if($value4->restday_ot_employee_number == $value->employee_number && $value4->date_ot == $value->dates)
				{
					$reg_ot = $value4->total_ot;
					$hr_diff = intval($reg_ot/60);
					$min_diff = intval($reg_ot%60);
					$min_diff1 = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('L'.$row,$hr_diff.".".$min_diff1);
				}
			}	

			//NIGHT DIFF
			$set_night_diff_morning = '6:00';
			$explode_night_diff_morning = explode(':', $set_night_diff_morning);
			$night_diff_morning = intval($explode_night_diff_morning[0]*60);
			$compute_night_diff_morning =$night_diff_morning - $total_in_daily;
			if($total_in_daily < $night_diff_morning)
			{
				$compute_night_diff_morning;
				$hr_diff = intval($compute_night_diff_morning/60);
				$min_diff = intval($compute_night_diff_morning%60);
				if($total_in_daily == 0 && $total_out_daily == 0)
				{
					$nd = ' ';
					//echo 0;
					$objWorkSheet->SetCellValue('M'.$row, $nd);
				}
				elseif($min_diff > 30 || $min_diff == 0)
				{
					$nd = $hr_diff."."."30";
					//echo $hr_diff."."."30";
					$objWorkSheet->SetCellValue('M'.$row, $nd);
				}
				elseif($min_diff < 30)
				{
					$nd = $hr_diff;
					$objWorkSheet->SetCellValue('M'.$row, $nd);
					//echo $hr_diff;
				}
			}
			else
			{
				$nd = ' ';
				$objWorkSheet->SetCellValue('M'.$row, $nd);
				//echo 0;
			}

			//SICK LEAVE
			foreach($data['sl'] as $key => $value5)
			{
				if($value5->slvl_employee_number == $value->employee_number && $value5->sl_date == $value->dates)
				{
					$sl_per_day = $value5->total_slvl;
					$objWorkSheet->SetCellValue('N'.$row,$sl_per_day);
				}
			}

			//VACATION LEAVE
			foreach($data['vl'] as $key => $value6)
			{
				if($value6->slvl_employee_number == $value->employee_number && $value6->vl_date == $value->dates)
				{
					$vl_per_day = $value6->total_slvl;
					$objWorkSheet->SetCellValue('O'.$row,$vl_per_day);
				}
			}

			//ABSENCES
			foreach($data['ab'] as $key => $value7)
			{
				if($value7->slvl_employee_number == $value->employee_number && $value7->ab_date == $value->dates && $value7->slvl_type == 'AB')
				{
					$ab_per_day = $value7->total_slvl;
					$reg_ab = $ab_per_day;
					$hr_diff = intval($reg_ab/60);
					$min_diff = intval($reg_ab%60);
					$min_diff1 = sprintf("%02d", $min_diff);
					$objWorkSheet->SetCellValue('P'.$row,$hr_diff.".".$min_diff1);
				}
			}

			//REMARKS
			foreach($data['remarks'] as $key => $value8)
			{
				if($value8->date == $value->dates && $value8->remarks_employee_number == $value->employee_number)
    		{
    			$remarkss = $value8->type_name;
    			$objWorkSheet->SetCellValue('Q'.$row,$remarkss);
    		}
			}

			$row++;
		}	
    $objWorkSheet->setTitle("ZAMBALES");


    $i++;
    
    }

		$filename = "NHFC(".$start_date.'-'.$end_date.')'.'.xlsx';
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="'.$filename.'"');
		header('Cache-Control: max-age=0');

		$writer = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$writer->save('php://output');
		exit;
		
	}

	public function excel()
	{
		$data['cut_off'] = $this->payroll_model->get_cut_off_date();

		$start_date = $data['cut_off']->start_date;
		$end_date = $data['cut_off']->end_date;

		$data['employees'] = $this->payroll_model->get_nhfc_summary_time();
		$data['gtlic_employees'] = $this->payroll_model->get_gtlic_summary_time();
		$data['ots'] = $this->payroll_model->get_regular_ot($start_date, $end_date);
		$data['legal_ots'] = $this->payroll_model->get_legal_ot($start_date, $end_date); 
		$data['special_ots'] = $this->payroll_model->get_special_ot($start_date, $end_date);
		$data['restday_ots'] = $this->payroll_model->get_restday_ot($start_date, $end_date); 
		$data['nightdiff'] = $this->payroll_model->get_total_night_diff($start_date, $end_date); 
		$data['tardiness'] = $this->payroll_model->get_total_tardiness($start_date, $end_date);
		$data['undertime'] = $this->payroll_model->get_total_undertime($start_date, $end_date);
		$data['abs'] = $this->payroll_model->get_total_ab($start_date, $end_date);
		$data['sls'] = $this->payroll_model->get_total_sl($start_date, $end_date);
		$data['vls'] = $this->payroll_model->get_total_vl($start_date, $end_date);

		require(APPPATH .'third_party/PHPExcel-1.8/Classes/PHPExcel.php');
		require(APPPATH.'third_party/PHPExcel-1.8/Classes/PHPExcel/Writer/Excel2007.php');

		$objPHPExcel = new PHPExcel();

		$objPHPExcel->getProperties()->setCreator("");
		$objPHPExcel->getProperties()->setLastModifiedBy("");
		$objPHPExcel->getProperties()->setTitle("");
		$objPHPExcel->getProperties()->setSubject("");
		$objPHPExcel->getProperties()->setDescription("");

		$objPHPExcel->setActiveSheetIndex(0); 

		 $style = array(
        	'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        	)
    	);

		$objPHPExcel->getDefaultStyle()->applyFromArray($style);	

		$styleArray = array(
			'borders' => array(
				'allborders' => array(
				'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			)
		);

		$objPHPExcel->getActiveSheet()->getStyle('A5:K104')->applyFromArray($styleArray);

		$styleArray = array(
			'font'  => array(
			'bold'  => true,
			'color' => array('rgb' => 'FFFFFF'),
			'size'  => 10,
			'name'  => 'Century Gothic'
		));

		$objPHPExcel->getActiveSheet()->getStyle('A4:K4')->applyFromArray($styleArray);

		$styleArray = array(
		'font'  => array(
			'bold'  => true,
			'color' => array('rgb' => '141414'),
			'size'  => 10,
			'name'  => 'Century Gothic'
		));

		$objPHPExcel->getActiveSheet()->getStyle('A6:A104')->applyFromArray($styleArray);

		$styleArray = array(
		'font'  => array(
			'bold'  => true,
			'color' => array('rgb' => '141414'),
			'size'  => 10,
			'name'  => 'Century Gothic'
		));

		$objPHPExcel->getActiveSheet()->getStyle('A1:A2')->applyFromArray($styleArray);

		$styleArray = array(
			'font'  => array(
			'bold'  => true,
			'color' => array('rgb' => '141414'),
			'size'  => 8,
			'name'  => 'Century Gothic'
		));

		$objPHPExcel->getActiveSheet()->getStyle('B5:K104')->applyFromArray($styleArray);

		$objPHPExcel->getActiveSheet()
		->getStyle('A4:K4')
		->getFill()
		->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
		->getStartColor()
		->setRGB('26A65B');

		foreach(range('A','K') as $columnID) 
		{
			$objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
				->setAutoSize(true);
		}

		
 
		//$objPHPExcel->getActiveSheet()->getStyle('A6:K102')->getNumberFormat()->setFormatCode('#,##0.00');

		$objPHPExcel->getActiveSheet()->getRowDimension('4')->setRowHeight(40);

		$objPHPExcel->getActiveSheet()->SetCellValue('A1','EMPLOYEE MAN HOUR REPORT');
		$objPHPExcel->getActiveSheet()->SetCellValue('A2','For pay period ended'. ' '. $end_date);
		
		$objPHPExcel->getActiveSheet()->SetCellValue('A4','NAME');
		$objPHPExcel->getActiveSheet()->SetCellValue('B4','REGULAR OVERTIME');
		$objPHPExcel->getActiveSheet()->SetCellValue('C4','NIGHT DIFFERENTIAL');
		$objPHPExcel->getActiveSheet()->SetCellValue('D4','REST DAY - OT');
		$objPHPExcel->getActiveSheet()->SetCellValue('E4','LEGAL HOLIDAY - OT');
		$objPHPExcel->getActiveSheet()->SetCellValue('F4','SPECIAL HOLIDAY - OT');
		$objPHPExcel->getActiveSheet()->SetCellValue('G4','TARDINESS');
		$objPHPExcel->getActiveSheet()->SetCellValue('H4','UNDERTIME');
		$objPHPExcel->getActiveSheet()->SetCellValue('I4','ABSENCES');
		$objPHPExcel->getActiveSheet()->SetCellValue('J4','VL TAKEN');
		$objPHPExcel->getActiveSheet()->SetCellValue('K4','SL TAKEN');

		$objPHPExcel->getActiveSheet()->SetCellValue('A5',' ');
		$objPHPExcel->getActiveSheet()->SetCellValue('B5','HOURS.MINS');
		$objPHPExcel->getActiveSheet()->SetCellValue('C5','HOURS.MINS');
		$objPHPExcel->getActiveSheet()->SetCellValue('D5','HOURS.MINS');
		$objPHPExcel->getActiveSheet()->SetCellValue('E5','HOURS.MINS');
		$objPHPExcel->getActiveSheet()->SetCellValue('F5','HOURS.MINS');
		$objPHPExcel->getActiveSheet()->SetCellValue('G5','HOURS.MINS');
		$objPHPExcel->getActiveSheet()->SetCellValue('H5','HOURS.MINS');
		$objPHPExcel->getActiveSheet()->SetCellValue('I5','HOURS.MINS');
		$objPHPExcel->getActiveSheet()->SetCellValue('J5','DAYS');
		$objPHPExcel->getActiveSheet()->SetCellValue('K5','DAYS');

		$row = 6;

		foreach($data['employees'] as $key => $value)
		{
			$objPHPExcel->getActiveSheet()->SetCellValue('A'.$row,$value->name);

			foreach($data['ots'] as $key => $value1)
			{
				if($value1->ot_employee_number == $value->employee_number && $value1->ot_type == 'ROT')
				{
					$ot = $value1->total_ot;
					$hr_diff = intval($ot/60);
					$min_diff = intval($ot%60);
					$ot1 = sprintf("%02d", $min_diff);
					$total_ots = $hr_diff. "." .$ot1;
					$objPHPExcel->getActiveSheet()->SetCellValue('B'.$row,$total_ots);
				}	
			}

			foreach ($data['nightdiff'] as $key => $value2) 
			{
				if($value2->nightdiff_employee_number == $value->employee_number && $value2->total_nightdiff == 0)
				{
					$objPHPExcel->getActiveSheet()->SetCellValue('C'.$row);
				}
				else
				{
					if($value2->nightdiff_employee_number == $value->employee_number)
					{
						$total_nightdffs = $value2->total_nightdiff;

						$objPHPExcel->getActiveSheet()->SetCellValue('C'.$row,$total_nightdffs);
					}
				}
			}

			foreach($data['legal_ots'] as $key => $value11)
			{
				if($value11->legal_ot_employee_number == $value->employee_number && $value11->ot_type == 'LHOT')
				{
					$ot = $value11->total_ot;
					$hr_diff = intval($ot/60);
					$min_diff = intval($ot%60);
					$ot1 = sprintf("%02d", $min_diff);
					$total_legal_ots = $hr_diff. "." .$ot1;

					$objPHPExcel->getActiveSheet()->SetCellValue('D'.$row, $total_legal_ots);
				}	
			}

			foreach($data['special_ots'] as $key => $value10)
			{
				if($value10->special_ot_employee_number == $value->employee_number && $value10->ot_type == 'SHOT')
				{
					$ot = $value10->total_ot;
					$hr_diff = intval($ot/60);
					$min_diff = intval($ot%60);
					$ot1 = sprintf("%02d", $min_diff);
					$total_special_ots = $hr_diff. "." .$ot1;

					$objPHPExcel->getActiveSheet()->SetCellValue('E'.$row, $total_special_ots);
				}	
			}
			
			foreach($data['restday_ots'] as $key => $value101)
			{
				if($value101->restday_ot_employee_number == $value->employee_number && $value101->ot_type == 'RDOT')
				{
					$ot = $value101->total_ot;
					$hr_diff = intval($ot/60);
					$min_diff = intval($ot%60);
					$ot1 = sprintf("%02d", $min_diff);
					$total_restday_ots = $hr_diff. "." .$ot1;

					$objPHPExcel->getActiveSheet()->SetCellValue('F'.$row, $total_restday_ots);
				}	
			}

			foreach($data['tardiness'] as $key => $value3)
			{
				if($value3->tard_employee_number == $value->employee_number && $value3->total_tardiness == 0)
				{
					$objPHPExcel->getActiveSheet()->SetCellValue('G'.$row);
				}
				else
				{
					if($value3->tard_employee_number == $value->employee_number)
					{
						$tard = $value3->total_tardiness;
						$hr_diff = intval($tard/60);
						$min_diff = intval($tard%60);
						$tard1 = sprintf("%02d", $min_diff);
						$total_tards = $hr_diff. "." .$tard1;

						$objPHPExcel->getActiveSheet()->SetCellValue('G'.$row,$total_tards);
					}
				}
			}

			foreach($data['undertime'] as $key => $value4)
			{
				if($value4->undertime_employee_number == $value->employee_number && $value4->total_undertime == 0)
				{
					$objPHPExcel->getActiveSheet()->SetCellValue('H'.$row);
				}
				else
				{
					if($value4->undertime_employee_number == $value->employee_number)
					{
						$under = $value4->total_undertime;
						$hr_diff = intval($under/60);
						$min_diff = intval($under%60);
						$under1 = sprintf("%02d", $min_diff);
						$total_undtme = $hr_diff. "." .$under1;

						$objPHPExcel->getActiveSheet()->SetCellValue('H'.$row,$total_undtme);
					}
				}
			}

			foreach($data['abs'] as $key => $value5)
			{
				if($value5->slvl_employee_number == $value->employee_number && $value5->slvl_type == 'AB')
				{
					$total_absent = $value5->total_slvl; 
					$hr_diff = intval($total_absent/60);
					$min_diff = intval($total_absent%60);
					$under1 = sprintf("%02d", $min_diff);
					$total_ab = $hr_diff. "." .$under1;

					$objPHPExcel->getActiveSheet()->SetCellValue('I'.$row,$total_ab);
				}
			}

			foreach($data['vls'] as $key => $value6)
			{
				if($value6->slvl_employee_number == $value->employee_number && $value6->slvl_type == 'VL')
				{
					$total_vl = $value6->total_slvl; 

					$objPHPExcel->getActiveSheet()->SetCellValue('J'.$row,$total_vl);
				}
			}

			foreach($data['sls'] as $key => $value7)
			{
				if($value7->slvl_employee_number == $value->employee_number && $value7->slvl_type == 'SL')
				{
					$total_sl = $value7->total_slvl; 

					$objPHPExcel->getActiveSheet()->SetCellValue('K'.$row,$total_sl);
				}
			}

			$row++;

			$objPHPExcel->getActiveSheet()->setTitle("NHFC");
		}


		//GTLIC 

		$i=0;
		while ($i < 1) {

		$objWorkSheet = $objPHPExcel->createSheet($i);

		$style = array(
			'alignment' => array(
			'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			)
		);

		$objWorkSheet->getDefaultStyle()->applyFromArray($style);

		$styleArray = array(
			'borders' => array(
				'allborders' => array(
				'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			)
		);

		$objWorkSheet->getStyle('A5:K102')->applyFromArray($styleArray);

		$objWorkSheet->getStyle('A5:K102')->applyFromArray($styleArray);

		$styleArray = array(
			'font'  => array(
			'bold'  => true,
			'color' => array('rgb' => 'FFFFFF'),
			'size'  => 10,
			'name'  => 'Century Gothic'
		));

		$objWorkSheet->getStyle('A4:K4')->applyFromArray($styleArray);

		$styleArray = array(
		'font'  => array(
			'bold'  => true,
			'color' => array('rgb' => '141414'),
			'size'  => 10,
			'name'  => 'Century Gothic'
		));

		$objWorkSheet->getStyle('A6:A102')->applyFromArray($styleArray);

		$styleArray = array(
		'font'  => array(
			'bold'  => true,
			'color' => array('rgb' => '141414'),
			'size'  => 10,
			'name'  => 'Century Gothic'
		));

		$objWorkSheet->getStyle('A1:A2')->applyFromArray($styleArray);

		$styleArray = array(
		'font'  => array(
			'bold'  => true,
			'color' => array('rgb' => '141414'),
			'size'  => 8,
			'name'  => 'Century Gothic'
		));

		$objWorkSheet->getStyle('B5:K102')->applyFromArray($styleArray);

		$objWorkSheet
		->getStyle('A4:K4')
		->getFill()
		->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
		->getStartColor()
		->setRGB('26A65B');

		foreach(range('A','K') as $columnID) 
		{
			$objWorkSheet->getColumnDimension($columnID)
				->setAutoSize(true);
		}
		
		$objWorkSheet->getStyle('B6:B105')->getNumberFormat()->setFormatCode('0.00');

		$objWorkSheet->getRowDimension('4')->setRowHeight(40);

		$objWorkSheet->SetCellValue('A1','EMPLOYEE MAN HOUR REPORT');
		$objWorkSheet->SetCellValue('A2','For pay period ended'. ' '. $end_date);
		
		$objWorkSheet->SetCellValue('A4','NAME');
		$objWorkSheet->SetCellValue('B4','REGULAR OVERTIME');
		$objWorkSheet->SetCellValue('C4','NIGHT DIFFERENTIAL');
		$objWorkSheet->SetCellValue('D4','REST DAY - OT');
		$objWorkSheet->SetCellValue('E4','LEGAL HOLIDAY - OT');
		$objWorkSheet->SetCellValue('F4','SPECIAL HOLIDAY - OT');
		$objWorkSheet->SetCellValue('G4','TARDINESS');
		$objWorkSheet->SetCellValue('H4','UNDERTIME');
		$objWorkSheet->SetCellValue('I4','ABSENCES');
		$objWorkSheet->SetCellValue('J4','VL TAKEN');
		$objWorkSheet->SetCellValue('K4','SL TAKEN');

		$objWorkSheet->SetCellValue('A5',' ');
		$objWorkSheet->SetCellValue('B5','HOURS.MINS');
		$objWorkSheet->SetCellValue('C5','HOURS.MINS');
		$objWorkSheet->SetCellValue('D5','HOURS.MINS');
		$objWorkSheet->SetCellValue('E5','HOURS.MINS');
		$objWorkSheet->SetCellValue('F5','HOURS.MINS');
		$objWorkSheet->SetCellValue('G5','HOURS.MINS');
		$objWorkSheet->SetCellValue('H5','HOURS.MINS');
		$objWorkSheet->SetCellValue('I5','HOURS.MINS');
		$objWorkSheet->SetCellValue('J5','DAYS');
		$objWorkSheet->SetCellValue('K5','DAYS');

		$row = 6;

		foreach($data['gtlic_employees'] as $key => $value)
		{
			$objWorkSheet->SetCellValue('A'.$row,$value->name);

			foreach($data['ots'] as $key => $value1)
			{
				if($value1->ot_employee_number == $value->employee_number && $value1->ot_type == 'ROT')
				{
					$ot = $value1->total_ot;
					$hr_diff = intval($ot/60);
					$min_diff = intval($ot%60);
					$ot1 = sprintf("%02d", $min_diff);
					$total_ots = $hr_diff. "." .$ot1;

					$objWorkSheet->SetCellValue('B'.$row,$total_ots);
				}	
			}

			foreach ($data['nightdiff'] as $key => $value2) 
			{
				if($value2->nightdiff_employee_number == $value->employee_number && $value2->total_nightdiff == 0)
				{
					$objWorkSheet->SetCellValue('C'.$row);
				}
				else
				{
					if($value2->nightdiff_employee_number == $value->employee_number)
					{
						$total_nightdffs = $value2->total_nightdiff;

						$objWorkSheet->SetCellValue('C'.$row,$total_nightdffs);
					}
				}
			}

			foreach($data['legal_ots'] as $key => $value11)
			{
				if($value11->legal_ot_employee_number == $value->employee_number && $value11->ot_type == 'LHOT')
				{
					$ot = $value11->total_ot;
					$hr_diff = intval($ot/60);
					$min_diff = intval($ot%60);
					$ot1 = sprintf("%02d", $min_diff);
					$total_legal_ots = $hr_diff. "." .$ot1;

					$objWorkSheet->SetCellValue('D'.$row, $total_legal_ots);
				}	
			}

			foreach($data['special_ots'] as $key => $value10)
			{
				if($value10->special_ot_employee_number == $value->employee_number && $value10->ot_type == 'SHOT')
				{
					$ot = $value10->total_ot;
					$hr_diff = intval($ot/60);
					$min_diff = intval($ot%60);
					$ot1 = sprintf("%02d", $min_diff);
					$total_special_ots = $hr_diff. "." .$ot1;

					$objWorkSheet->SetCellValue('E'.$row, $total_special_ots);
				}	
			}
			
			foreach($data['restday_ots'] as $key => $value101)
			{
				if($value101->restday_ot_employee_number == $value->employee_number && $value101->ot_type == 'RDOT')
				{
					$ot = $value101->total_ot;
					$hr_diff = intval($ot/60);
					$min_diff = intval($ot%60);
					$ot1 = sprintf("%02d", $min_diff);	
					$total_restday_ots = $hr_diff. "." .$ot1;

					$objWorkSheet->SetCellValue('F'.$row, $total_restday_ots);
				}	
			}

			foreach($data['tardiness'] as $key => $value3)
			{
				if($value3->tard_employee_number == $value->employee_number && $value3->total_tardiness == 0)
				{
					$objWorkSheet->SetCellValue('G'.$row);
				}
				else
				{
					if($value3->tard_employee_number == $value->employee_number)
					{
						$tard = $value3->total_tardiness;
						$hr_diff = intval($tard/60);
						$min_diff = intval($tard%60);
						$tard1 = sprintf("%02d", $min_diff);
						$total_tards = $hr_diff. "." .$tard1;

						$objWorkSheet->SetCellValue('G'.$row,$total_tards);
					}
				}
			}

			foreach($data['undertime'] as $key => $value4)
			{
				if($value4->undertime_employee_number == $value->employee_number && $value4->total_undertime == 0)
				{
					$objWorkSheet->SetCellValue('H'.$row);
				}
				else
				{
					if($value4->undertime_employee_number == $value->employee_number)
					{
						$under = $value4->total_undertime;
						$hr_diff = intval($under/60);
						$min_diff = intval($under%60);
						$under1 = sprintf("%02d", $min_diff);
						$total_undtme = $hr_diff. "." .$under1;

						$objWorkSheet->SetCellValue('H'.$row,$total_undtme);
					}
				}
			}

			foreach($data['abs'] as $key => $value5)
			{
				if($value5->slvl_employee_number == $value->employee_number && $value5->slvl_type == 'AB')
				{
					$total_absent = $value5->total_slvl;
					$hr_diff = intval($total_absent/60);
					$min_diff = intval($total_absent%60);
					$tard1 = sprintf("%02d", $min_diff);
					$total_ab = $hr_diff. "." .$tard1; 

					$objWorkSheet->SetCellValue('I'.$row,$total_ab);
				}
			}

			foreach($data['vls'] as $key => $value6)
			{
				if($value6->slvl_employee_number == $value->employee_number && $value6->slvl_type == 'VL')
				{
					$total_vl = $value6->total_slvl; 

					$objWorkSheet->SetCellValue('J'.$row,$total_vl);
				}
			}

			foreach($data['sls'] as $key => $value7)
			{
				if($value7->slvl_employee_number == $value->employee_number && $value7->slvl_type == 'SL')
				{
					$total_sl = $value7->total_slvl; 

					$objWorkSheet->SetCellValue('K'.$row,$total_sl);
				}
			}

			$row++;
		}	
	
		$objWorkSheet->setTitle("GTLIC");
	
		$i++;
		}

		$filename = "NHFC CUT-OFF(".$start_date.'-'.$end_date.')'.'.xlsx';
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="'.$filename.'"');
		header('Cache-Control: max-age=0');

		$writer = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$writer->save('php://output');
		exit;

	}

	public function adjustment_total_compute()
	{
		$this->form_validation->set_rules('employee_number','Employee Number','required|trim');
		$this->form_validation->set_rules('date','Date','required|trim');
		$this->form_validation->set_rules('adjust_type','Type','required|trim');
		$this->form_validation->set_rules('remarks','Remarks','required|trim');

		if($this->form_validation->run() == FALSE)
		{
			$data['cut_off'] = $this->payroll_model->get_cut_off_date();
			$data['main_content'] = 'reports/computetime/adjustment';
			$this->load->view('layouts/main', $data);
		}
		else
		{
			if($this->payroll_model->adjustment_totals())
			{
				$this->session->set_flashdata('add_msg','Adjustment Successfully Updated!');
				redirect('reports/adjustment_total_compute');
			}
		}
	}

	public function index_report_generation()
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
		$data['cut_off'] = $this->payroll_model->get_cut_off_date(); 
		$data['tardiness'] = $this->report_generation_model->get_total_tardiness($data['start_date'], $data['end_date']);
		$data['number_tardiness'] = $this->report_generation_model->get_number_tardiness($data['start_date'], $data['end_date']);
		$data['employees'] = $this->payroll_model->get_summary_time();
		$data['absences'] = $this->payroll_model->get_total_ab($data['start_date'], $data['end_date']);
		$data['sls'] = $this->payroll_model->get_total_sl($data['start_date'], $data['end_date']);
		$data['vls'] = $this->payroll_model->get_total_vl($data['start_date'], $data['end_date']);
		$data['els'] = $this->payroll_model->get_total_el($data['start_date'], $data['end_date']);
		$data['cwwuts'] = $this->payroll_model->get_total_cwwut($data['start_date'], $data['end_date']);
		$data['ots'] = $this->payroll_model->get_regular_ot($data['start_date'], $data['end_date']);
		$data['night_diffs'] = $this->report_generation_model->get_total_night_diff($data['start_date'], $data['end_date']);
		$data['undertime']  = $this->payroll_model->get_total_undertimes($data['start_date'], $data['end_date']);
		$data['main_content'] = 'reports/reportgeneration/index';
		$this->load->view('layouts/main', $data);
	}

	public function report_generation()
	{
		if($this->input->server('REQUEST_METHOD') == 'POST')
		{
			$data['start_date'] = $this->input->post('start_date');
			$data['end_date'] = $this->input->post('end_date');
			$data['company_id'] = $this->input->post('company_id');
			$data['type'] = $this->input->post('type');
		}
		else 
		{
			$data['start_date'] = date('Y-m-d');
			$data['end_date'] = date('Y-m-d'); 
			$data['company_id'] = ' ';
			$data['type'] = ' ';
		} 

		if($data['type'] == 'SL' || $data['type'] == 'VL' || $data['type'] == 'AB' || $data['type'] == 'EL')
		{
			$data['slvls'] = $this->report_generation_model->get_slvl_datas($data['start_date'],$data['end_date'],$data['company_id'],$data['type']);
			$this->load->view('reports/print/print_slvl', $data);
		}
		elseif($data['type'] == 'OT')
		{
			$data['ots'] = $this->report_generation_model->get_ot_datas($data['start_date'],$data['end_date'],$data['company_id'],$data['type']);
			$this->load->view('reports/print/print_ot', $data);
		}
		elseif($data['type'] == 'UT')
		{
			$data['uts'] = $this->report_generation_model->get_ut_datas($data['start_date'],$data['end_date'],$data['company_id'],$data['type']);
			$this->load->view('reports/print/print_ut', $data);
		}
		elseif($data['type'] == 'OB')
		{
			$data['obs'] = $this->report_generation_model->get_ob_datas($data['start_date'],$data['end_date'],$data['company_id'],$data['type']);
			$this->load->view('reports/print/print_ob', $data);
		}
		else
		{
			$data['main_content'] = 'reports/reportgeneration/report_generation';
			$this->load->view('layouts/main', $data);
		}
	}


	public function index_undertime() 
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
		
		$data['uts'] = $this->payroll_model->get_undertime($data['start_date'], $data['end_date']);
		$data['cut_off'] = $this->payroll_model->get_cut_off_date(); 
		$data['main_content'] = 'reports/undertime/index';

		$this->load->view('layouts/main', $data);
	}

	public function add_undertime()
	{
		$this->form_validation->set_rules('name', 'Name', 'required|trim');
		$this->form_validation->set_rules('date_ut', 'Date', 'required|trim');
		$this->form_validation->set_rules('time_out', 'Time Out', 'required|trim');
		$this->form_validation->set_rules('reason', 'Reason', 'required|trim');

		if($this->form_validation->run() == FALSE)
		{
			$data['employees'] = $this->payroll_model->get_employees();
			$data['main_content'] = 'reports/undertime/add';

			$this->load->view('layouts/main', $data);
		}
		else
		{
			if($this->payroll_model->add_undertime_by_hr())
			{
				$this->session->set_flashdata('add_msg', 'Undertime Successfully Added!');
				redirect('reports/index_undertime');
			}
		}
	
	}	

	public function edit_undertime($id)
	{
		$this->form_validation->set_rules('name', 'Name', 'required|trim');
		$this->form_validation->set_rules('date_ut', 'Date', 'required|trim');
		$this->form_validation->set_rules('time_out', 'Time Out', 'required|trim');
		$this->form_validation->set_rules('reason', 'Reason', 'required|trim');

		if($this->form_validation->run() == FALSE)
		{
			$data['employees'] = $this->payroll_model->get_employees();
			$data['undertime'] = $this->payroll_model->get_emp_undertime($id);
			$data['main_content'] = 'reports/undertime/edit';
			$this->load->view('layouts/main', $data);
		}
		else
		{
			if($this->payroll_model->update_undertime($id))
			{
				$this->session->set_flashdata('update_msg_ut', 'Undertime Successfully Updated!');
				redirect('users/ut_list');
			}
		}
	}

	public function delete_undertime($id,$employee_number,$type)
	{
		if($this->payroll_model->delete_undertime($id,$employee_number,$type))
		{
			$this->session->set_flashdata('delete_msg', 'Undertime Successfully Deleted!');
			redirect('reports/index_undertime');
		}
	}

	public function process_undertime()
	{
		$this->db->trans_start();

		foreach($this->input->post('employee') as $ut)
		{
			$explode_undetime = explode('|', $ut);

			$data = array(
				'process_by' 	 => $this->session->userdata('username'),
				'process_date' => date('Y-m-d h:m:s'),
				'status'       => 'PROCESSED'
			);

			$this->db->where('id', $explode_undetime[0]);
			$this->db->update('tbl_undertime', $data);

			$data = array(
				'process_by' 	 => $this->session->userdata('username'),
				'process_date' => date('Y-m-d h:m:s'),
				'status'       => 'PROCESSED'
			);

			$this->db->where('for_id', $explode_undetime[0]);
			$this->db->update('tbl_remarks', $data);
		}

		$trans = $this->db->trans_complete();

		redirect('reports/index_undertime');
	}

	public function index_adjustment()
	{
		$this->form_validation->set_rules('branch_id', 'Branch Id', 'required|trim');
		$this->form_validation->set_rules('start_date', 'Start Date', 'required|trim');
		$this->form_validation->set_rules('end_date', 'End Date', 'required|trim');

		if($this->form_validation->run() == FALSE)
		{
			$data['main_content'] = 'reports/adjustment/index';
			$this->load->view('layouts/main', $data);
		}
		else
		{
			if($this->report_generation_model->delete_csv_uploaded($start_date, $end_date, $branch_id))
			{
				$this->session->set_flashdata('delete_msg','Data Successfully Deleted!');
				redirect('reports/index_time_keeping');
			}
		}
	}

		//RECOMMENDING FOR APPROVAL
	public function rfa_slvl()
	{
		foreach($this->input->post('employee') as $slvl)
		{
			$explode_data = explode('|', $slvl);
			$data_w = date('w', strtotime($explode_data[3]));

			$data = array(
				'recommended_approv_by'  	 => $this->session->userdata('username'),
				'recommended_approv_date'  => date('Y-m-d h:i:s'),
				'status'         					 => 'FOR APPROVAL'
			); 

			$this->db->where('id', $explode_data[0]);
			$this->db->update('tbl_slvl', $data);
		}	

		redirect('reports/index_slvl');
	}

	// FOR APPROVAL
	public function fa_slvl()
	{
		foreach($this->input->post('employee') as $slvl)
		{
			$explode_data = explode('|', $slvl);
			$data_w = date('w', strtotime($explode_data[3]));

			$data = array(
				'approved_by'   => $this->session->userdata('username'),
				'approved_date' => date('Y-m-d h:i:s'),
				'status'        => 'Recommending for Verification'
			); 

			$this->db->where('id', $explode_data[0]);
			$this->db->update('tbl_slvl', $data);
		}	

		redirect('reports/index_slvl');
	}

	//RECOMMENDING FOR VERIFY
	public function rfv_slvl()
	{ 
		foreach($this->input->post('employee') as $slvl)
		{
			$explode_data = explode('|', $slvl);
			$data_w = date('w', strtotime($explode_data[3]));

			$data = array(
				'recommended_verify_by'  	 => $this->session->userdata('username'),
				'recommended_verify_date'  => date('Y-m-d h:i:s'),
				'status'                   => 'FOR VERIFICATION'
			); 

			$this->db->where('id', $explode_data[0]);
			$this->db->update('tbl_slvl', $data);
		}	

		redirect('reports/index_slvl');
	}

	// FOR VERIFY
	public function fv_slvl()
	{
		foreach($this->input->post('employee') as $slvl)
		{
			$explode_data = explode('|', $slvl);
			$data_w = date('w', strtotime($explode_data[3]));

			$data = array(
				'verified_by'  	 => $this->session->userdata('username'),
				'verified_date'  => date('Y-m-d h:i:s'),
				'status'         => 'FOR NOTIFICATION'
			); 

			$this->db->where('id', $explode_data[0]);
			$this->db->update('tbl_slvl', $data);
		}	

		redirect('reports/index_slvl');
	}

	// NOTED BY
	public function nb_slvl()
	{
		foreach($this->input->post('employee') as $slvl)
		{
			$explode_data = explode('|', $slvl);
			$data_w = date('w', strtotime($explode_data[3]));

			$data = array(
				'noted_by'	 => $this->session->userdata('username'),
				'noted_date' => date('Y-m-d h:i:s'),
				'status'     => 'FOR PROCESS'
			); 

			$this->db->where('id', $explode_data[0]);
			$this->db->update('tbl_slvl', $data);
		}	

		redirect('reports/index_slvl');
	}

	// APPROVED FOR PAYMENT
	public function afp_slvl()
	{
		//$explode_data = explode('|', $this->input->post('employee');
		//$balance = $explode_data[6];
		$i = 0;
		$emp_no_sl = 0;
		$emp_no_vl = 0;

		foreach($this->input->post('employee') as $slvl)
		{
			$explode_data = explode('|', $slvl);
			$data_w = date('w', strtotime($explode_data[3]));


			if($explode_data[4] == 'SL')
			{
				if($emp_no_sl != $explode_data[1])
				{
					$compute_sl = $explode_data[6] - $explode_data[10];
				}
				else
				{
					$compute_sl = $compute_sl - $explode_data[10];
				}

				$data = array(
					'sl_credit'       => $compute_sl
				);
				
				$emp_no_sl = $explode_data[1];

				$this->db->where('employee_number', $explode_data[1]);
				$this->db->update('leave_credits', $data);
				
			}
			elseif($explode_data[4] == 'VL')
			{
				if($emp_no_vl != $explode_data[1])
				{
					$compute_vl = $explode_data[7] - $explode_data[10];
				}
				else
				{
					$compute_vl = $compute_vl - $explode_data[10];
				}

				$data = array(
					'vl_credit'       => $compute_vl
				);
				
				$emp_no_vl = $explode_data[1];
			
				$this->db->where('employee_number', $explode_data[1]);
				$this->db->update('leave_credits', $data);
				
			}
			/*print_r('<pre>');
			print_r($data);
			print_r('</pre>');*/
			
			
			$data = array(
				'process_by'  	=> $this->session->userdata('username'),
				'process_date'  => date('Y-m-d h:i:s'),
				'status'        => 'PROCESSED'
			); 

			$this->db->where('id', $explode_data[0]);
			$this->db->update('tbl_slvl', $data);

			$data = array( 
				'process_by' 			=> $this->session->userdata('username'),
				'process_date' 		=> date('Y-m-d h:i:s'),
				'status'        	=> 'PROCESSED'
			);

			$this->db->where('for_id', $explode_data[0]);
			$this->db->where('employee_number', $explode_data[1]);
			$this->db->where('type', $explode_data[4]);
			$this->db->update('tbl_remarks', $data);
			

			$this->db->where('employee_number', $explode_data[1]);
			$this->db->where('date', $explode_data[3]);
			$in_att = $this->db->get('tbl_cwwut');

			if($in_att->num_rows() == 0)
			{
				if($explode_data[5] == 'WD' && $explode_data[4] == 'AB' && ($data_w <= 4))
				{
					$data = array(
						'for_id'               => $explode_data[0], 
						'employee_number'      => $explode_data[1],
						'name'                 => $explode_data[2],
						'date'								 => $explode_data[3],
						'type'								 => $explode_data[4],
						'undertime_hr'         => 60,
						'created_date'         => date('Y-m-d h:i:s'),
						'process_date'         => date('Y-m-d h:i:s'),
						'created_by'           => $this->session->userdata('username'),
						'status'               => 'PROCESSED'
					);
					$this->db->insert('tbl_cwwut', $data);
				
				}
				elseif($explode_data[5] == 'HFAM' && $explode_data[4] == 'AB' && ($data_w <= 4))
				{
					$data = array(
						'for_id'               => $explode_data[0], 
						'employee_number'      => $explode_data[1],
						'name'                 => $explode_data[2],
						'date'								 => $explode_data[3],
						'type'								 => $explode_data[4],
						'undertime_hr'         => 30,
						'created_date'         => date('Y-m-d h:i:s'),
						'process_date'         => date('Y-m-d h:i:s'),
						'created_by'           => $this->session->userdata('username'),
						'status'               => 'PROCESSED'
					);
					$this->db->insert('tbl_cwwut', $data);
				
				}
				elseif($explode_data[5] == 'HFPM' && $explode_data[4] == 'AB' && ($data_w <= 4))
				{
					$data = array(
						'for_id'               => $explode_data[0], 
						'employee_number'      => $explode_data[1],
						'name'                 => $explode_data[2],
						'date'								 => $explode_data[3],
						'type'								 => $explode_data[4],
						'undertime_hr'         => 30,
						'created_date'         => date('Y-m-d h:i:s'),
						'process_date'         => date('Y-m-d h:i:s'),
						'created_by'           => $this->session->userdata('username'),
						'status'               => 'PROCESSED'
					);
					$this->db->insert('tbl_cwwut', $data);
				}
			}
		}
	
		redirect('reports/index_slvl');
	}

	// RECOMMENDING FOR APPROVAL
	public function rfa_ob()
	{
		foreach($this->input->post('employee') as $ob)
		{
			$data_explode = explode("|", $ob);

			$id = $data_explode[0];

			$data = array(
				'recommended_approv_by'  	 => $this->session->userdata('username'),
				'recommended_approv_date'  => date('Y-m-d h:i:s'),
				'remarks'         				 => 'FOR APPROVAL'
			); 

			$this->db->where('id', $id);
			$this->db->update('tbl_ob', $data);
		}	

		redirect('reports/index_ob');
	}

	//FOR APPROVAL
	public function fa_ob()
	{
		foreach($this->input->post('employee') as $ob)
		{
			$data_explode = explode("|", $ob);

			$id = $data_explode[0];

			$data = array(
				'approved_by'  	 => $this->session->userdata('username'),
				'approved_date'  => date('Y-m-d h:i:s'),
				'remarks'        => 'Recommending for Verification'
			); 

			$this->db->where('id', $id);
			$this->db->update('tbl_ob', $data);
		}	

		redirect('reports/index_ob');
	} 

	public function rfv_ob()
	{
		foreach($this->input->post('employee') as $ob)
		{
			$data_explode = explode("|", $ob);

			$id = $data_explode[0];

			$data = array(
				'recommended_verify_by'  	 => $this->session->userdata('username'),
				'recommended_verify_date'  => date('Y-m-d h:i:s'),
				'remarks'         				 => 'FOR VERIFICATION'
			); 

			$this->db->where('id', $id);
			$this->db->update('tbl_ob', $data);
		}	

		redirect('reports/index_ob');
	}

	public function fv_ob()
	{
		foreach($this->input->post('employee') as $ob)
		{
			$data_explode = explode("|", $ob);

			$id = $data_explode[0];

			$data = array(
				'verified_by'  	 => $this->session->userdata('username'),
				'verified_date'  => date('Y-m-d h:i:s'),
				'remarks'        => 'FOR NOTIFICATION'
			); 

			$this->db->where('id', $id);
			$this->db->update('tbl_ob', $data);
		}	

		redirect('reports/index_ob');
	}

	public function nb_ob()
	{
		foreach($this->input->post('employee') as $ob)
		{
			$data_explode = explode("|", $ob);

			$id = $data_explode[0];

			$data = array(
				'noted_by'  	 => $this->session->userdata('username'),
				'noted_date'   => date('Y-m-d h:i:s'),
				'remarks'      => 'FOR PROCESS'
			); 

			$this->db->where('id', $id);
			$this->db->update('tbl_ob', $data);
		}	

		redirect('reports/index_ob');
	}

	public function afp_ob()
	{
		if($this->users_model->process_ob())
		{
			redirect('reports/index_ob');
		}
	}

	public function rfa_ut()
	{
		foreach($this->input->post('employee') as $ut)
		{
			$explode_data = explode('|', $ut);
			$data_w = date('w', strtotime($explode_data[3]));

			$data = array(
				'recommended_approv_by'    => $this->session->userdata('username'),
				'recommended_approv_date'  => date('Y-m-d h:i:s'),
				'status'         		   => 'FOR APPROVAL'
			); 

			$this->db->where('id', $explode_data[0]);
			$this->db->update('tbl_undertime', $data);
		}	

		redirect('reports/index_undertime');
	}

	// FOR APPROVAL
	public function fa_ut()
	{
		foreach($this->input->post('employee') as $ut)
		{
			$explode_data = explode('|', $ut);
			$data_w = date('w', strtotime($explode_data[3]));

			$data = array(
				'approved_by'   => $this->session->userdata('username'),
				'approved_date' => date('Y-m-d h:i:s'),
				'status'        => 'Recommending for Verification'
			); 

			$this->db->where('id', $explode_data[0]);
			$this->db->update('tbl_undertime', $data);
		}	

		redirect('reports/index_undertime');
	}

	//RECOMMENDING FOR VERIFY
	public function rfv_ut()
	{
		foreach($this->input->post('employee') as $ut)
		{
			$explode_data = explode('|', $ut);
			$data_w = date('w', strtotime($explode_data[3]));

			$data = array(
				'recommended_verify_by'    => $this->session->userdata('username'),
				'recommended_verify_date'  => date('Y-m-d h:i:s'),
				'status'                   => 'FOR VERIFICATION'
			); 

			$this->db->where('id', $explode_data[0]);
			$this->db->update('tbl_undertime', $data);
		}	

		redirect('reports/index_undertime');
	}

	// FOR VERIFY
	public function fv_ut()
	{
		foreach($this->input->post('employee') as $ut)
		{
			$explode_data = explode('|', $ut);
			$data_w = date('w', strtotime($explode_data[3]));

			$data = array(
				'verified_by'  	 => $this->session->userdata('username'),
				'verified_date'  => date('Y-m-d h:i:s'),
				'status'         => 'FOR NOTIFICATION'
			); 

			$this->db->where('id', $explode_data[0]);
			$this->db->update('tbl_undertime', $data);
		}	

		redirect('reports/index_undertime');
	}

	// NOTED BY
	public function nb_ut()
	{
		foreach($this->input->post('employee') as $ut)
		{
			$explode_data = explode('|', $ut);
			$data_w = date('w', strtotime($explode_data[3]));

			$data = array(
				'noted_by'	 => $this->session->userdata('username'),
				'noted_date' => date('Y-m-d h:i:s'),
				'status'     => 'FOR PROCESS'
			); 

			$this->db->where('id', $explode_data[0]);
			$this->db->update('tbl_undertime', $data);
		}	

		redirect('reports/index_undertime');
	}

	public function afp_ut()
	{
		foreach($this->input->post('employee') as $ut)
		{
			$explode_data = explode('|', $ut);
			$data_w = date('w', strtotime($explode_data[3]));

			$data = array(
				'process_by'   => $this->session->userdata('username'),
				'process_date' => date('Y-m-d h:i:s'),
				'status'       => 'PROCESSED'
			);

			$this->db->where('id', $explode_data[0]);
			$this->db->update('tbl_undertime', $data);

			$data1 = array(
				'process_by'   => $this->session->userdata('username'),
				'process_date' => date('Y-m-d h:i:s'),
				'status'       => 'PROCESSED'
			);

			$this->db->where('for_id', $explode_data[0]);
			$this->db->where('employee_number', $explode_data[1]);
			$this->db->where('type', $explode_data[5]);
			$this->db->update('tbl_remarks', $data1);
		}	

		redirect('reports/index_undertime');
	}

	public function rfa_ot()
	{
		foreach($this->input->post('employee') as $ot)
		{
			$data_explode = explode("|", $ot);

			$id = $data_explode[0];

			$data = array(
				'recommended_approv_by'    => $this->session->userdata('username'),
				'recommended_approv_date'  => date('Y-m-d h:i:s'),
				'status'         		   => 'FOR APPROVAL'
			); 

			$this->db->where('id', $id);
			$this->db->update('tbl_ot', $data);
		}	

		redirect('reports/index_ot');
	}
	
	//FOR APPROVAL
	public function fa_ot()
	{
		foreach($this->input->post('employee') as $ot)
		{
			$data_explode = explode("|", $ot);

			$id = $data_explode[0];

			$data = array(
				'approved_by'  	 => $this->session->userdata('username'),
				'approved_date'  => date('Y-m-d h:i:s'),
				'status'         => 'Recommending for Verification'
			); 

			$this->db->where('id', $id);
			$this->db->update('tbl_ot', $data);
		}	

		redirect('reports/index_ot');
	}

	public function rfv_ot()
	{
		foreach($this->input->post('employee') as $ot)
		{
			$data_explode = explode("|", $ot);

			$id = $data_explode[0];

			$data = array(
				'recommended_verify_by'    => $this->session->userdata('username'),
				'recommended_verify_date'  => date('Y-m-d h:i:s'),
				'status'         	       => 'FOR VERIFICATION'
			); 

			$this->db->where('id', $id);
			$this->db->update('tbl_ot', $data);
		}	

		redirect('reports/index_ot');
	}

	public function fv_ot()
	{
		foreach($this->input->post('employee') as $ot)
		{
			$data_explode = explode("|", $ot);

			$id = $data_explode[0];

			$data = array(
				'verified_by'  	 => $this->session->userdata('username'),
				'verified_date'  => date('Y-m-d h:i:s'),
				'status'         => 'FOR NOTIFICATION'
			); 

			$this->db->where('id', $id);
			$this->db->update('tbl_ot', $data);
		}	

		redirect('reports/index_ot');
	}

	public function nb_ot()
	{
		foreach($this->input->post('employee') as $ot)
		{
			$data_explode = explode("|", $ot);

			$id = $data_explode[0];

			$data = array(
				'noted_by'  	 => $this->session->userdata('username'),
				'noted_date'   => date('Y-m-d h:i:s'),
				'status'       => 'FOR PROCESS'
			); 

			$this->db->where('id', $id);
			$this->db->update('tbl_ot', $data);
		}	

		redirect('reports/index_ot');
	}

	public function afp_ot()
	{
		foreach($this->input->post('employee') as $ot)
		{
			$data_explode = explode("|", $ot);

			$id = $data_explode[0];

			$data = array(
				'process_by'  	 => $this->session->userdata('username'),
				'process_date'   => date('Y-m-d h:i:s'),
				'status'         => 'PROCESSED'
			); 

			$this->db->where('id', $id);
			$this->db->update('tbl_ot', $data);
		}	

		redirect('reports/index_ot');
	}

	public function index_leave_credits()
	{
		$data['main_content'] = 'reports/reportgeneration/leave_credits';
		$data['employees'] = $this->payroll_model->get_employees();
		$this->load->view('layouts/main', $data);
	}
	public function edit_leave_credits($id)
	{
		$this->form_validation->set_rules('sl', 'Sick Leave', 'required|trim');
		$this->form_validation->set_rules('vl', 'Vacation Leave', 'required|trim');
		$this->form_validation->set_rules('el', 'Emergency Leave', 'required|trim');
		$this->form_validation->set_rules('bl', 'Bereavement Leave', 'required|trim');

		if($this->form_validation->run() == FALSE)
		{
			$data['main_content'] = 'reports/reportgeneration/edit_leave_credit';
			$data['leave_credit'] = $this->payroll_model->get_leave_credit($id);
			$this->load->view('layouts/main', $data);
		}
		else
		{
			if($this->payroll_model->update_leave_credit($id))
			{
				$this->session->set_flashdata('edit_msg', 'Leave Credit Successfully Updated!');
				redirect('reports/index_leave_credits');
			}
		}

		
	}

	public function adjustment()
	{
		$this->form_validation->set_rules('name', 'Name', 'required|trim');
		$this->form_validation->set_rules('type', 'Type', 'required|trim');
		$this->form_validation->set_rules('adjust_date', 'Adjust Date', 'required|trim');
		$this->form_validation->set_rules('cutoff_date', 'CutOff Date', 'required|trim');
		$this->form_validation->set_rules('adjustment', 'Adjustment', 'required|trim');
		$this->form_validation->set_rules('remarks', 'Remarks', 'required|trim');

		$type = $this->input->post('type');

		$type_explod = explode('|', $type);

		if($this->form_validation->run() == FALSE)
		{
			$data['main_content'] = 'reports/adjustment/adj_index';
			$data['employees'] = $this->payroll_model->get_employees();
			$this->load->view('layouts/main', $data);
		}
		else
		{
			if($type_explod[0] == 'VL')
			{
				$this->payroll_model->add_adjusment_vl();
				$this->session->set_flashdata('add_vl', 'ADJUSTMENT VL SUCCESSFULLY ADDED!');
				redirect('reports/adjustment');
			}
			elseif($type_explod[0] == 'SL')
			{
				$this->payroll_model->add_adjusment_sl();
				$this->session->set_flashdata('add_sl', 'ADJUSTMENT SL SUCCESSFULLY ADDED!');
				redirect('reports/adjustment');
			}
			
			elseif($type_explod[0] == 'AB')
			{
				$this->payroll_model->add_adjusment_ab();
				$this->session->set_flashdata('add_ab', 'ADJUSTMENT AB SUCCESSFULLY ADDED!');
				redirect('reports/adjustment');
			}
			elseif($type_explod[2] == 'OT')
			{
				$this->payroll_model->add_adjustment_ot();
				$this->session->set_flashdata('add_ot', 'ADJUSTMENT OT SUCCESSFULLY ADDED!');
				redirect('reports/adjustment');
			}
			elseif($type_explod[0] == 'UT')
			{
				$this->payroll_model->add_adjustment_ut();
				$this->session->set_flashdata('add_ut', 'ADJUSTMENT UT SUCCESSFULLY ADDED!');
				redirect('reports/adjustment');
			}
		}

		
	}

}