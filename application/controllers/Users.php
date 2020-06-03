<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Users extends CI_Controller {

	public function __construct() 
	{ 
		parent::__construct();
		date_default_timezone_set('Asia/Manila');
	}

	public function add_slvl()
	{ 
		$this->form_validation->set_rules('slvl_type', 'TYPE', 'required');
		$this->form_validation->set_rules('HF', 'TYPE', 'required');
		$this->form_validation->set_rules('name', 'Employee Name', 'required|trim');
		$this->form_validation->set_rules('start_date', 'EFFECTIVE DATE OF LEAVE', 'required|trim');
		$this->form_validation->set_rules('reason', 'REASON', 'required|trim');
 
		if($this->form_validation->run() == FALSE)
		{
			$data['main_content'] = 'users/forms/slvl/add_slvl';
			$data['employee'] = $this->payroll_model->get_user();
			$data['leave_credit'] = $this->users_model->get_emp_leave_credits();
			$this->load->view('layouts/main', $data);
		}
		else 
		{
			$policy_file = $this->payroll_model->add_slvl();
			//print_r($policy_file);
			if($policy_file)
			{
				$this->session->set_flashdata('add_msg_slvl', 'SL/VL SUCCESSFULLY ADDED!');
			}
			else
			{
				$this->session->set_flashdata('policy_file_slvl', 'YOUR FILE CAN`T PROCEED DUE TO THE LEAVE POLICY!');
			}
			redirect('dashboard/dashboard');
		}
		
	}

	public function edit_slvl($id)
	{
		$this->form_validation->set_rules('name', 'Employee Name', 'required|trim');
		$this->form_validation->set_rules('start_date', 'EFFECTIVE DATE OF LEAVE', 'required|trim');
		$this->form_validation->set_rules('reason', 'REASON', 'required|trim');

		if($this->form_validation->run() == FALSE)
		{
			$data['main_content'] = 'users/forms/slvl/edit_slvl'; 
			$data['slvl'] = $this->payroll_model->get_slvls($id);
			$this->load->view('layouts/main', $data);
		}
		else
		{
			$policy_file = $this->payroll_model->update_slvl($id);
			if($policy_file != NULL)
			{
				$this->session->set_flashdata('update_msg_slvl', 'SL/VL SUCCESSFULLY UPDATED!');
				redirect('dashboard/dashboard');
			}
			else
			{
				$this->session->set_flashdata('policy_file_slvl', 'YOUR FILE CAN`T UPDATE DUE TO THE LEAVE POLICY!');
				redirect('dashboard/dashboard');
			}
		}
		
	}

	public function delete_slvl($id,$employee_number,$type)
	{
		$this->payroll_model->delete_slvl($id,$employee_number,$type);
		$this->session->set_flashdata('delete_msg_slvl', 'SL/VL SUCCESSFULLY DELETED!');
		redirect('dashboard/dashboard');
	}

	public function disapproved_slvl($id)
	{
		$user = $this->session->userdata('username');
		$data = array(
			'status'   => 'Disapproved By' . ' ' . $user 
			);
		$this->db->where('id', $id);
		$this->db->update('tbl_slvl', $data);

		$this->session->set_flashdata('disapproved_slvl', 'DISAPPROVED SLVL SUCCESSFULLY!');
		redirect('users/slvl_list');
	}

	public function cancelled_slvl($id)
	{
		$user = $this->session->userdata('username');
		$data = array(
			'status'   => 'CANCELLED BY' . ' ' . $user
			);
		$this->db->where('id', $id);
		$this->db->update('tbl_slvl', $data);

		$this->session->set_flashdata('cancel_msg', 'CANCELLED SLVL SUCCESSFULLY!');
		redirect('reports/index_slvl');
	}

	public function slvl_list()
	{
		// HR HEAD
		if($this->session->userdata('is_verify') == 1 && $this->session->userdata('is_hr') == 1)
		{
			if($this->input->server('REQUEST_METHOD') == 'POST')
			{
				$data['start_date'] = $this->input->post('start_date');
				$data['end_date'] = $this->input->post('end_date');
				$data['branch_id'] = $this->input->post('branch_id');
			}
			else 
			{
				$data['start_date'] = date('Y-m-d');
				$data['end_date'] = date('Y-m-d');
				$data['branch_id'] = $this->input->post('branch_id');
			} 

			$data['slvl'] = $this->users_model->get_slvl_all_by_isHr_and_isVerify($data['start_date'],$data['end_date'],$this->session->userdata('department_id'), $data['branch_id']); 
		}

		// SUPERVISORS
		elseif($this->session->userdata('is_rfa') == 1 && $this->session->userdata('is_oichead') == 0 && $this->session->userdata('is_fa') == 0)
		{
			if($this->input->server('REQUEST_METHOD') == 'POST')
			{
				$data['start_date'] = $this->input->post('start_date');
				$data['end_date'] = $this->input->post('end_date');
				$data['department_id'] = $this->session->userdata('department_id');
			}
			else 
			{
				$data['start_date'] = date('Y-m-d');
				$data['end_date'] = date('Y-m-d');
				$data['department_id'] = ' ';
			} 

			$data['slvl'] = $this->users_model->get_slvl_all_by_department_id($data['start_date'],$data['end_date'],$data['department_id']); 
		}

		// OIC IN BRANCH
		elseif($this->session->userdata('is_rfa') == 1 && $this->session->userdata('is_oichead') == 1)
		{
			if($this->input->server('REQUEST_METHOD') == 'POST')
			{
				$data['start_date'] = $this->input->post('start_date');
				$data['end_date'] = $this->input->post('end_date');
				$data['branch_id'] = $this->session->userdata('branch_id');
			}
			else 
			{
				$data['start_date'] = date('Y-m-d');
				$data['end_date'] = date('Y-m-d');
				$data['branch_id'] = ' ';
			} 

			$data['slvl'] = $this->users_model->get_slvl_all_by_oic($data['start_date'],$data['end_date'],$data['branch_id']); 
		}

		// HEADS ACCOUNTING AND IT
		elseif($this->session->userdata('is_fa') == 2)
		{
			if($this->input->server('REQUEST_METHOD') == 'POST')
			{
				$data['start_date'] = $this->input->post('start_date');
				$data['end_date'] = $this->input->post('end_date');
				$data['department_id'] = $this->session->userdata('department_id');
			}
			else 
			{
				$data['start_date'] = date('Y-m-d');
				$data['end_date'] = date('Y-m-d');
				$data['department_id'] = ' ';
			} 

			$data['slvl'] = $this->users_model->get_slvl_all_by_headCAM($data['start_date'],$data['end_date'],$data['department_id']); 
		}

		// HEADS SALES AND OPERATION
		elseif($this->session->userdata('is_fa') == 1 && $this->session->userdata('is_rfa') == 1)
		{
			if($this->input->server('REQUEST_METHOD') == 'POST')
			{
				$data['start_date'] = $this->input->post('start_date');
				$data['end_date'] = $this->input->post('end_date');
				$data['department_id'] = $this->session->userdata('department_id');
			}
			else 
			{
				$data['start_date'] = date('Y-m-d');
				$data['end_date'] = date('Y-m-d');
				$data['department_id'] = ' ';
			} 

			$data['slvl'] = $this->users_model->get_slvl_all_by_headSalesOperations($data['start_date'],$data['end_date'],$this->session->userdata('department_id')); 
		}

		// HEADS BILLING AND COLLECTION
		elseif($this->session->userdata('is_fa') == 4 && $this->session->userdata('is_rfa') == 0)
		{
			if($this->input->server('REQUEST_METHOD') == 'POST')
			{
				$data['start_date'] = $this->input->post('start_date');
				$data['end_date'] = $this->input->post('end_date');
				$data['department_id'] = $this->session->userdata('department_id');
				$data['branch_id'] = $this->input->post('branch_id');
			}
			else 
			{
				$data['start_date'] = date('Y-m-d');
				$data['end_date'] = date('Y-m-d');
				$data['department_id'] = ' ';
				$data['branch_id'] = $this->input->post('branch_id');
			} 

			$data['slvl'] = $this->users_model->get_slvl_all_by_Heads($data['start_date'],$data['end_date'],$this->session->userdata('department_id'), $data['branch_id']); 
		}

		// HR ASSISTANT
		elseif($this->session->userdata('is_rfv') == 1)
		{
			if($this->input->server('REQUEST_METHOD') == 'POST')
			{
				$data['start_date'] = $this->input->post('start_date');
				$data['end_date'] = $this->input->post('end_date');
				$data['branch_id'] = $this->input->post('branch_id');
			}
			else 
			{
				$data['start_date'] = date('Y-m-d');
				$data['end_date'] = date('Y-m-d');
				$data['branch_id'] = $this->input->post('branch_id');
			} 

			$data['slvl'] = $this->users_model->get_slvl_allbranch_rfv($data['start_date'],$data['end_date'],$data['branch_id']); 
		}

		// CFO PROCESS
		elseif($this->session->userdata('is_cfo') == 1)
		{
			if($this->input->server('REQUEST_METHOD') == 'POST')
			{
				$data['start_date'] = $this->input->post('start_date');
				$data['end_date'] = $this->input->post('end_date');
				$data['department_id'] = $this->session->userdata('department_id');
			}
			else  
			{
				$data['start_date'] = date('Y-m-d');
				$data['end_date'] = date('Y-m-d');
				$data['department_id'] = ' ';
			} 

			$data['slvl'] = $this->users_model->get_slvl_allbranch_process($data['start_date'],$data['end_date'],$data['department_id']); 
		}

		$data['branches'] = $this->master_model->get_branches();
		$data['cut_off'] = $this->payroll_model->get_cut_off_date();
		$data['employee'] = $this->payroll_model->get_user();
		$data['main_content'] = 'users/lists/slvl_list'; 
		$this->load->view('layouts/main', $data);

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

		redirect('users/slvl_list');
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

		redirect('users/slvl_list');
	}

	//RECOMMENDING FOR VERIFY
	public function rfv_slvl()
	{
		$i = 0;
		$emp_no_sl = 0;
		$emp_no_vl = 0;
		$emp_no_el = 0;
		$emp_no_bl = 0;
		$emp_no_ab = 0;
		$compute_ab = 0;
		$compute_bl = 0;
		$compute_el = 0;
		$compute_sl = 0;
		$compute_vl = 0;
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
					'sl_credit'   => $compute_sl
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
					'vl_credit'  => $compute_vl
				);
				
				$emp_no_vl = $explode_data[1];
			
				$this->db->where('employee_number', $explode_data[1]);
				$this->db->update('leave_credits', $data);
			}

			elseif($explode_data[4] == 'EL')
			{
				if($emp_no_el != $explode_data[1])
				{
					$compute_el = $explode_data[8] - $explode_data[10];
				}
				else
				{
					$compute_vl = $compute_vl - $explode_data[10];
				}

				$data = array(
					'elcl_credit' => $compute_el
				);

				$emp_no_el = $explode_data[1];

				$this->db->where('employee_number', $explode_data[1]);
				$this->db->update('leave_credits', $data);
			}

			elseif($explode_data[4] == 'BL')
			{
				if($emp_no_bl != $explode_data[1])
				{
					$compute_bl = $explode_data[9] - $explode_data[10];
				}
				else
				{
					$compute_bl = $compute_bl - $explode_data[10];
				}

				$data = array(
					'fl_credit'  => $compute_bl
				);

				$emp_no_bl = $explode_data[1];

				$this->db->where('employee_number', $explode_data[1]);
				$this->db->update('leave_credits', $data);
			}

			elseif($explode_data[4] == 'AB')
			{
				if($emp_no_ab != $explode_data[1])
				{
					$compute_ab += $explode_data[10];
				}
				else
				{
					$compute_ab += $explode_data[10];
				}

				$data = array(
					'absences'  => $compute_ab
				);

				$emp_no_ab = $explode_data[1];

				$this->db->where('employee_number', $explode_data[1]);
				$this->db->update('leave_credits', $data);

			}

			$data = array(
				'recommended_verify_by'  	 => $this->session->userdata('username'),
				'recommended_verify_date'  => date('Y-m-d h:i:s'),
				'status'                   => 'FOR VERIFICATION'
			); 

			$this->db->where('id', $explode_data[0]);
			$this->db->update('tbl_slvl', $data);
		}	

		redirect('users/slvl_list');
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

		redirect('users/slvl_list');
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

		redirect('users/slvl_list');
	}

	// APPROVED FOR PAYMENT
	public function afp_slvl()
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
				'process_by' 		=> $this->session->userdata('username'),
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
						'date'				   => $explode_data[3],
						'type'				   => $explode_data[4],
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
						'date'				   => $explode_data[3],
						'type'				   => $explode_data[4],
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
						'date'				   => $explode_data[3],
						'type'				   => $explode_data[4],
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
	
		 redirect('users/slvl_list');
	}

	public function add_ob()
	{
		$this->form_validation->set_rules('date', 'Date OF OB', 'required|trim');
		$this->form_validation->set_rules('ob_type', 'TYPE', 'required');
		$this->form_validation->set_rules('site_from', 'SITE / DESIGNATION FROM', 'required|trim');
		$this->form_validation->set_rules('site_to', 'SITE / DESIGNATION TO', 'required|trim');
		$this->form_validation->set_rules('purpose', 'Purpose', 'required|trim');
		$this->form_validation->set_rules('time_of_departure', 'TIME OF DEPARTURE', 'required|trim');
		$this->form_validation->set_rules('time_of_return', 'TIME OF RETURN', 'required|trim');


		if($this->form_validation->run() == FALSE)
		{
			$data['main_content'] = 'users/forms/ob/add_ob';
			$data['employee'] = $this->payroll_model->get_user();
			$this->load->view('layouts/main', $data);
		}
		else
		{
			$policy_file = $this->payroll_model->add_ob();
			if($policy_file)
			{
				$this->session->set_flashdata('add_msg_ob', 'OB SUCCESSFULLY ADDED!');
			}
			else
			{
				$this->session->set_flashdata('policy_file_ob', 'YOUR DATA CAN`T PROCEED DUE TO THE DATE FILE!');
			}
			redirect('dashboard/dashboard');
		}
		
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
			$data['main_content'] = 'users/forms/ob/edit_ob';
			$data['ob'] = $this->payroll_model->get_ob_by_id($id);
			$data['employee'] = $this->payroll_model->get_user();
			$this->load->view('layouts/main', $data);
		}
		else
		{
			if($this->payroll_model->update_ob($id))
			{
				$this->session->set_flashdata('update_msg_ob', 'OB SUCCESSFULLY UPDATED!');
				redirect('dashboard/dashboard');
			}
		}
	
	}

	public function delete_ob($id,$employee_number,$type)
	{
		$this->payroll_model->delete_ob($id,$employee_number,$type);
		$this->session->set_flashdata('delete_msg_ob', 'OB SUCCESSFULLY DELETED!');
		redirect('dashboard/dashboard');
	}

	public function disapproved_ob($id)
	{
		$user = $this->session->userdata('username');
		$data = array(
			'remarks'   => 'Disapproved By' . ' ' . $user
			);
		$this->db->where('id', $id);
		$this->db->update('tbl_ob', $data);

		$this->session->set_flashdata('disapproved_ob', 'DISAPPROVED OB SUCCESSFULLY!');
		redirect('users/ob_list');
	}

	public function cancelled_ob($id)
	{
		$user = $this->session->userdata('username');
		$data = array(
			'remarks'   => 'CANCELLED BY' . ' ' . $user
			);
		$this->db->where('id', $id);
		$this->db->update('tbl_ob', $data);

		$this->session->set_flashdata('cancel_msg', 'CANCELLED OB SUCCESSFULLY!');
		redirect('reports/index_ob');
	}

	public function ob_list()
	{
		// HR
		if($this->session->userdata('is_verify') == 1 && $this->session->userdata('is_hr') == 1)
		{
			if($this->input->server('REQUEST_METHOD') == 'POST')
			{
				$data['start_date'] = $this->input->post('start_date');
				$data['end_date'] = $this->input->post('end_date');
				$data['branch_id'] = $this->input->post('branch_id');
			}
			else 
			{
				$data['start_date'] = date('Y-m-d');
				$data['end_date'] = date('Y-m-d');
				$data['branch_id'] = $this->input->post('branch_id');
			} 

			$data['obs'] = $this->users_model->get_ob_all_by_isHr_and_isVerify($data['start_date'],$data['end_date'],$this->session->userdata('department_id'), $data['branch_id']); 
		}

		// SUPERVISORS
		elseif($this->session->userdata('is_rfa') == 1 && $this->session->userdata('is_oichead') == 0 && $this->session->userdata('is_fa') == 0)
		{
			if($this->input->server('REQUEST_METHOD') == 'POST')
			{
				$data['start_date'] = $this->input->post('start_date');
				$data['end_date'] = $this->input->post('end_date');
				$data['department_id'] = $this->session->userdata('department_id');
			}
			else 
			{
				$data['start_date'] = date('Y-m-d');
				$data['end_date'] = date('Y-m-d');
				$data['department_id'] = ' ';
			} 

			$data['obs'] = $this->users_model->get_ob_all_by_department_id($data['start_date'],$data['end_date'],$data['department_id']); 
		}

		// OIC IN BRANCH
		elseif($this->session->userdata('is_rfa') == 1 && $this->session->userdata('is_oichead') == 1)
		{
			if($this->input->server('REQUEST_METHOD') == 'POST')
			{
				$data['start_date'] = $this->input->post('start_date');
				$data['end_date'] = $this->input->post('end_date');
				$data['branch_id'] = $this->session->userdata('branch_id');
			}
			else 
			{
				$data['start_date'] = date('Y-m-d');
				$data['end_date'] = date('Y-m-d');
				$data['branch_id'] = ' ';
			} 

			$data['obs'] = $this->users_model->get_ob_all_by_oic($data['start_date'],$data['end_date'],$data['branch_id']); 
		}

		// HEADS ACCOUNTING AND IT
		elseif($this->session->userdata('is_fa') == 2)
		{
			if($this->input->server('REQUEST_METHOD') == 'POST')
			{
				$data['start_date'] = $this->input->post('start_date');
				$data['end_date'] = $this->input->post('end_date');
				$data['department_id'] = $this->session->userdata('department_id');
			}
			else 
			{
				$data['start_date'] = date('Y-m-d');
				$data['end_date'] = date('Y-m-d');
				$data['department_id'] = ' ';
			} 

			$data['obs'] = $this->users_model->get_ob_all_by_headCAM($data['start_date'],$data['end_date'],$data['department_id']); 
		}

		// HEADS SALES AND OPERATION
		elseif($this->session->userdata('is_fa') == 1 && $this->session->userdata('is_rfa') == 1)
		{
			if($this->input->server('REQUEST_METHOD') == 'POST')
			{
				$data['start_date'] = $this->input->post('start_date');
				$data['end_date'] = $this->input->post('end_date');
				$data['department_id'] = $this->session->userdata('department_id');
				$data['branch_id'] = $this->session->userdata('branch_id');
			}
			else 
			{
				$data['start_date'] = date('Y-m-d');
				$data['end_date'] = date('Y-m-d');
				$data['department_id'] = ' ';
				$data['branch_id']  = ' ';
			} 

			$data['obs'] = $this->users_model->get_ob_all_by_headSalesOperations($data['start_date'],$data['end_date'],$this->session->userdata('department_id'),$data['branch_id'] ); 
		}

		// HEADS BILLING AND COLLECTION
		elseif($this->session->userdata('is_fa') == 4 && $this->session->userdata('is_rfa') == 0)
		{
			if($this->input->server('REQUEST_METHOD') == 'POST')
			{
				$data['start_date'] = $this->input->post('start_date');
				$data['end_date'] = $this->input->post('end_date');
				$data['department_id'] = $this->session->userdata('department_id');
			}
			else 
			{
				$data['start_date'] = date('Y-m-d');
				$data['end_date'] = date('Y-m-d');
				$data['department_id'] = ' ';
			} 

			$data['obs'] = $this->users_model->get_ob_all_by_Heads($data['start_date'],$data['end_date'],$this->session->userdata('department_id')); 
		}

		// HR ASSISTANT
		elseif($this->session->userdata('is_rfv') == 1)
		{
			if($this->input->server('REQUEST_METHOD') == 'POST')
			{
				$data['start_date'] = $this->input->post('start_date');
				$data['end_date'] = $this->input->post('end_date');
				$data['branch_id'] = $this->input->post('branch_id');
			}
			else 
			{
				$data['start_date'] = date('Y-m-d');
				$data['end_date'] = date('Y-m-d');
				$data['branch_id'] = $this->input->post('branch_id');
			} 

			$data['obs'] = $this->users_model->get_ob_allbranch_rfv($data['start_date'],$data['end_date'], $data['branch_id']); 
		}

		// CFO PROCESS
		elseif($this->session->userdata('is_cfo') == 1)
		{
			if($this->input->server('REQUEST_METHOD') == 'POST')
			{
				$data['start_date'] = $this->input->post('start_date');
				$data['end_date'] = $this->input->post('end_date');
				$data['department_id'] = $this->session->userdata('department_id');
			}
			else  
			{
				$data['start_date'] = date('Y-m-d');
				$data['end_date'] = date('Y-m-d');
				$data['department_id'] = ' ';
			} 

			$data['obs'] = $this->users_model->get_ob_allbranch_process($data['start_date'],$data['end_date'],$data['department_id']); 
		}

		$data['branches'] = $this->master_model->get_branches();
		$data['cut_off'] = $this->payroll_model->get_cut_off_date();
		$data['employee'] = $this->payroll_model->get_user();
		$data['main_content'] = 'users/lists/ob_list'; 
		$this->load->view('layouts/main', $data);

	}

	// RECOMMENDING FOR APPROVAL
	public function rfa_ob()
	{
		foreach($this->input->post('employee') as $ob)
		{
			$data_explode = explode("|", $ob);

			$id = $data_explode[0];

			$data = array(
				'recommended_approv_by'    => $this->session->userdata('username'),
				'recommended_approv_date'  => date('Y-m-d h:i:s'),
				'remarks'         		   => 'FOR APPROVAL'
			); 

			$this->db->where('id', $id);
			$this->db->update('tbl_ob', $data);
		}	

		redirect('users/ob_list');
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

		redirect('users/ob_list');
	}

	public function rfv_ob()
	{
		$process_date = date('Y-m-d H:i:s');
		foreach($this->input->post('employee') as $ob)
		{
			$data_explode = explode("|", $ob);

			$id = $data_explode[0];
			$emp_num = $data_explode[1];
			$dateob = $data_explode[2];
			$weekdate = $data_explode[3];
			$type_ob = $data_explode[4]; 
			$time_in = $data_explode[5];
			$time_out = $data_explode[6];
			$type = $data_explode[7];

			if($type_ob == 'IN')
			{
				$data = array(
					'times'  => $dateob." ".$time_in,
					'status' => 'in'
				);
				$this->db->where('dates', $dateob);
				$this->db->where('employee_number', $emp_num);
				$this->db->update('tbl_in_attendance', $data);
			}

			elseif($type_ob == 'OUT')
			{
				if($weekdate == 5)
				{
					$data = array(
						'times' => $dateob." ".$time_out,
						'status' => 'out'
					);
					$this->db->where('dates', $dateob);
					$this->db->where('employee_number', $emp_num);
					$this->db->update('tbl_out_attendance', $data);
				}
				else
				{
					$data = array(
						'times' => $dateob." ".$time_out,
						'status' => 'out'
					);
					$this->db->where('dates', $dateob);
					$this->db->where('employee_number', $emp_num);
					$this->db->update('tbl_out_attendance', $data);
				}
			}

			elseif($type_ob == 'WD')
			{
				$data = array(
					'times'           => $dateob." ". $time_in,
					'status'          => 'in'
				);
				
				$this->db->where('dates', $dateob);
				$this->db->where('employee_number', $emp_num);
				$this->db->update('tbl_in_attendance', $data);
		
				$data = array(
					'times'           => $dateob." ".$time_out,
					'status'          => 'out'
				);
				$this->db->where('dates', $dateob);
				$this->db->where('employee_number', $emp_num);
				$this->db->update('tbl_out_attendance', $data);
				
			}

			$data = array(
				'recommended_verify_by'    => $this->session->userdata('username'),
				'recommended_verify_date'  => date('Y-m-d h:i:s'),
				'remarks'         		   => 'FOR NOTIFICATION'
			); 

			$this->db->where('id', $id);
			$this->db->update('tbl_ob', $data);

		}
	
		/*foreach($this->input->post('employee') as $ob)
		{
			$data_explode = explode("|", $ob);

			$id = $data_explode[0];

			$data = array(
				'recommended_verify_by'    => $this->session->userdata('username'),
				'recommended_verify_date'  => date('Y-m-d h:i:s'),
				'remarks'         		   => 'FOR NOTIFICATION'
			); 

			$this->db->where('id', $id);
			$this->db->update('tbl_ob', $data);
		}*/	

		redirect('users/ob_list');
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
				'remarks'        => 'FOR NOTED'
			); 

			$this->db->where('id', $id);
			$this->db->update('tbl_ob', $data);
		}	

		redirect('users/ob_list');
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

		redirect('users/ob_list');
	}

	public function afp_ob()
	{ 
		if($this->users_model->process_ob())
		{
			redirect('users/ob_list');
		}
	}


	public function add_ot()
	{
		$this->form_validation->set_rules('ot_type', 'TYPE', 'required');
		$this->form_validation->set_rules('name', 'Employee Name', 'required|trim');
		$this->form_validation->set_rules('date', 'Date OF OT', 'required|trim');
		$this->form_validation->set_rules('time_in', 'Time In', 'required|trim');
		$this->form_validation->set_rules('time_out', 'Time Out', 'required|trim');
		$this->form_validation->set_rules('nature_of_work', 'Nature Of Work', 'required|trim');

		if($this->form_validation->run() == FALSE)
		{
			$data['main_content'] = 'users/forms/ot/add_ot';
			$data['employee'] = $this->payroll_model->get_user();
			$this->load->view('layouts/main', $data);
		}
		else
		{
			$policy_file = $this->payroll_model->add_ot();
			if($policy_file)
			{
				$this->session->set_flashdata('add_msg_ot', 'OT SUCCESSFULLY ADDED!');
			}
			else
			{
				$this->session->set_flashdata('policy_file_ot', 'YOUR DATA CAN`T PROCEED DUE TO THE DATE FILE!');
			}
			redirect('dashboard/dashboard');
		}
	}

	public function edit_ot($id)
	{
		$this->form_validation->set_rules('ot_type', 'TYPE', 'required');
		$this->form_validation->set_rules('name', 'Employee Name', 'required|trim');
		$this->form_validation->set_rules('date', 'Date OF OT', 'required|trim');
		$this->form_validation->set_rules('time_in', 'Time In', 'required|trim');
		$this->form_validation->set_rules('time_out', 'Time Out', 'required|trim');
		$this->form_validation->set_rules('nature_of_work', 'Nature Of Work', 'required|trim');

		if($this->form_validation->run() == FALSE)
		{
			$data['main_content'] = 'users/forms/ot/edit_ot';
			$data['employee'] = $this->payroll_model->get_user();
			$data['ot'] = $this->payroll_model->get_ot_id($id);
			$this->load->view('layouts/main', $data);
		}
		else
		{
			if($this->payroll_model->update_ot($id))
			{
				$this->session->set_flashdata('update_msg_ot', 'OT SUCCESSFULLY UPDATED!');
				redirect('dashboard/dashboard');
			}
		}
	}

	public function delete_ot($id)
	{
		if($this->payroll_model->delete_ot($id))
		{
			$this->session->set_flashdata('delete_msg_ot', 'OT SUCCESSFULLY DELETED!');
			redirect('dashboard/dashboard');
		}
	}

	public function disapproved_ot($id)
	{
		$user = $this->session->userdata('username');
		$data = array(
			'status'   => 'Disapproved By' . ' ' . $user
			);
		$this->db->where('id', $id);
		$this->db->update('tbl_ot', $data);

		$this->session->set_flashdata('disapproved_ot', 'DISAPPROVED OT SUCCESSFULLY!');
		redirect('users/ot_list');
	}

	public function cancelled_ot($id)
	{
		$user = $this->session->userdata('username');
		$data = array(
			'status'   => 'CANCELLED BY' . ' ' . $user
			);
		$this->db->where('id', $id);
		$this->db->update('tbl_ot', $data);

		$this->session->set_flashdata('cancel_msg', 'CANCELLED OT SUCCESSFULLY!');
		redirect('reports/index_ot');
	}

	public function ot_list()
	{
		// HR
		if($this->session->userdata('is_verify') == 1 && $this->session->userdata('is_hr') == 1)
		{
			if($this->input->server('REQUEST_METHOD') == 'POST')
			{
				$data['start_date'] = $this->input->post('start_date');
				$data['end_date'] = $this->input->post('end_date');
				$data['branch_id'] = $this->input->post('branch_id');
			}
			else 
			{
				$data['start_date'] = date('Y-m-d');
				$data['end_date'] = date('Y-m-d');
				$data['branch_id'] = $this->input->post('branch_id');
			} 

			$data['ots'] = $this->users_model->get_ot_all_by_isHr_and_isVerify($data['start_date'],$data['end_date'],$this->session->userdata('department_id'),$data['branch_id']); 
		}

		// SUPERVISORS
		elseif($this->session->userdata('is_rfa') == 1 && $this->session->userdata('is_oichead') == 0 && $this->session->userdata('is_fa') == 0)
		{
			if($this->input->server('REQUEST_METHOD') == 'POST')
			{
				$data['start_date'] = $this->input->post('start_date');
				$data['end_date'] = $this->input->post('end_date');
				$data['department_id'] = $this->session->userdata('department_id');
			}
			else 
			{
				$data['start_date'] = date('Y-m-d');
				$data['end_date'] = date('Y-m-d');
				$data['department_id'] = ' ';
			} 

			$data['ots'] = $this->users_model->get_ot_all_by_department_id($data['start_date'],$data['end_date'],$data['department_id']); 
		}

		// OIC IN BRANCH
		elseif($this->session->userdata('is_rfa') == 1 && $this->session->userdata('is_oichead') == 1)
		{
			if($this->input->server('REQUEST_METHOD') == 'POST')
			{
				$data['start_date'] = $this->input->post('start_date');
				$data['end_date'] = $this->input->post('end_date');
				$data['branch_id'] = $this->session->userdata('branch_id');
			}
			else 
			{
				$data['start_date'] = date('Y-m-d');
				$data['end_date'] = date('Y-m-d');
				$data['branch_id'] = ' ';
			} 

			$data['ots'] = $this->users_model->get_ot_all_by_oic($data['start_date'],$data['end_date'],$data['branch_id']); 
		}

		// HEADS ACCOUNTING AND IT
		elseif($this->session->userdata('is_fa') == 2)
		{
			if($this->input->server('REQUEST_METHOD') == 'POST')
			{
				$data['start_date'] = $this->input->post('start_date');
				$data['end_date'] = $this->input->post('end_date');
				$data['department_id'] = $this->session->userdata('department_id');
			}
			else 
			{
				$data['start_date'] = date('Y-m-d');
				$data['end_date'] = date('Y-m-d');
				$data['department_id'] = ' ';
			} 

			$data['ots'] = $this->users_model->get_ot_all_by_headCAM($data['start_date'],$data['end_date'],$data['department_id']); 
		}

		// HEADS SALES AND OPERATION
		elseif($this->session->userdata('is_fa') == 1 && $this->session->userdata('is_rfa') == 1)
		{
			if($this->input->server('REQUEST_METHOD') == 'POST')
			{
				$data['start_date'] = $this->input->post('start_date');
				$data['end_date'] = $this->input->post('end_date');
				$data['department_id'] = $this->session->userdata('department_id');
				$data['branch_id'] = $this->session->userdata('branch_id');
			}
			else 
			{
				$data['start_date'] = date('Y-m-d');
				$data['end_date'] = date('Y-m-d');
				$data['department_id'] = ' ';
				$data['branch_id']  = ' ';
			} 

			$data['ots'] = $this->users_model->get_ot_all_by_headSalesOperations($data['start_date'],$data['end_date'],$this->session->userdata('department_id'),$data['branch_id'] ); 
		}

		// HEADS BILLING AND COLLECTION
		elseif($this->session->userdata('is_fa') == 4 && $this->session->userdata('is_rfa') == 0)
		{
			if($this->input->server('REQUEST_METHOD') == 'POST')
			{
				$data['start_date'] = $this->input->post('start_date');
				$data['end_date'] = $this->input->post('end_date');
				$data['department_id'] = $this->session->userdata('department_id');
			}
			else 
			{
				$data['start_date'] = date('Y-m-d');
				$data['end_date'] = date('Y-m-d');
				$data['department_id'] = ' ';
			} 

			$data['ots'] = $this->users_model->get_ot_all_by_Heads($data['start_date'],$data['end_date'],$this->session->userdata('department_id')); 
		}

		// HR ASSISTANT
		elseif($this->session->userdata('is_rfv') == 1)
		{
			if($this->input->server('REQUEST_METHOD') == 'POST')
			{
				$data['start_date'] = $this->input->post('start_date');
				$data['end_date'] = $this->input->post('end_date');
				$data['branch_id'] = $this->input->post('branch_id');
			}
			else 
			{
				$data['start_date'] = date('Y-m-d');
				$data['end_date'] = date('Y-m-d');
				$data['branch_id'] = $this->input->post('branch_id');
			} 

			$data['ots'] = $this->users_model->get_ot_allbranch_rfv($data['start_date'],$data['end_date'], $data['branch_id']); 
		}

		// CFO PROCESS
		elseif($this->session->userdata('is_cfo') == 1)
		{
			if($this->input->server('REQUEST_METHOD') == 'POST')
			{
				$data['start_date'] = $this->input->post('start_date');
				$data['end_date'] = $this->input->post('end_date');
				$data['department_id'] = $this->session->userdata('department_id');
			}
			else  
			{
				$data['start_date'] = date('Y-m-d');
				$data['end_date'] = date('Y-m-d');
				$data['department_id'] = ' ';
			} 

			$data['ots'] = $this->users_model->get_ot_allbranch_process($data['start_date'],$data['end_date'],$data['department_id']); 
		}

		$data['branches'] = $this->master_model->get_branches();
		$data['cut_off'] = $this->payroll_model->get_cut_off_date();
		$data['main_content'] = 'users/lists/ot_list'; 
		$this->load->view('layouts/main', $data);
	}

	// RECOMMENDING FOR APPROVAL
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

		redirect('users/ot_list');
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

		redirect('users/ot_list');
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

		redirect('users/ot_list');
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

		redirect('users/ot_list');
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

		redirect('users/ot_list');
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

		redirect('users/ot_list');
	}

	public function add_undertime()
	{
		$this->form_validation->set_rules('date_ut', 'Date', 'required|trim');
		$this->form_validation->set_rules('time_out', 'Time Out', 'required|trim');
		$this->form_validation->set_rules('reason', 'Reason', 'required|trim');

		if($this->form_validation->run() == FALSE)
		{
			$data['main_content'] = 'users/forms/ut/add_ut';
			$data['employee'] = $this->payroll_model->get_user();
			$this->load->view('layouts/main', $data);
		}
		else
		{
			$policy_file = $this->payroll_model->add_undertime();
			if($policy_file)
			{
				$this->session->set_flashdata('add_msg_ut', 'Undertime Successfully Added!');
			}
			else
			{
				$this->session->set_flashdata('policy_file_ut', 'YOUR DATA CAN`T PROCEED DUE TO THE DATE FILE!');
			}
			redirect('dashboard/dashboard');
		}
	
	}	

	public function edit_undertime($id)
	{
		$this->form_validation->set_rules('date_ut', 'Date', 'required|trim');
		$this->form_validation->set_rules('time_out', 'Time Out', 'required|trim');
		$this->form_validation->set_rules('reason', 'Reason', 'required|trim');

		if($this->form_validation->run() == FALSE)
		{
			$data['main_content'] = 'users/forms/ut/edit_ut';
			$data['employee'] = $this->payroll_model->get_user();
			$data['ut'] = $this->payroll_model->get_emp_undertime($id);
			$this->load->view('layouts/main', $data);
		}
		else
		{
			if($this->payroll_model->update_undertime($id))
			{
				$this->session->set_flashdata('update_msg_ut', 'Undertime Successfully Updated!');
				redirect('dashboard/dashboard');
			}
		}
	}	

	public function delete_undertime($id,$employee_number,$type)
	{
		$this->payroll_model->delete_undertime($id,$employee_number,$type);
		$this->session->set_flashdata('delete_msg_ut', 'UNDERTIME SUCCESSFULLY DELETED!');
		redirect('dashboard/dashboard');
	}

	public function disapproved_undertime($id)
	{
		$user = $this->session->userdata('username');
		$data = array(
			'status'   => 'Disapproved By' . ' ' . $user
			);
		$this->db->where('id', $id);
		$this->db->update('tbl_undertime', $data);

		$this->session->set_flashdata('disapproved_ut', 'DISAPPROVED UNDERTIME SUCCESSFULLY!');
		redirect('users/ut_list');
	}

	public function cancelled_undertime($id)
	{
		$user = $this->session->userdata('username');
		$data = array(
			'status'   => 'CANCELLED BY' . ' ' . $user
			);
		$this->db->where('id', $id);
		$this->db->update('tbl_undertime', $data);

		$this->session->set_flashdata('cancel_msg', 'CANCELLED UNDERTIME SUCCESSFULLY!');
		redirect('reports/index_undertime');
	}

	public function ut_list()
	{
		if($this->session->userdata('is_verify') == 1 && $this->session->userdata('is_hr') == 1)
		{
			if($this->input->server('REQUEST_METHOD') == 'POST')
			{
				$data['start_date'] = $this->input->post('start_date');
				$data['end_date'] = $this->input->post('end_date');
				$data['branch_id'] = $this->input->post('branch_id');
			}
			else 
			{
				$data['start_date'] = date('Y-m-d');
				$data['end_date'] = date('Y-m-d');
				$data['branch_id'] = $this->input->post('branch_id');
			} 

			$data['uts'] = $this->users_model->get_ut_all_by_isHr_and_isVerify($data['start_date'],$data['end_date'],$this->session->userdata('department_id'), $data['branch_id']); 
		}

		// SUPERVISORS
		elseif($this->session->userdata('is_rfa') == 1 && $this->session->userdata('is_oichead') == 0 && $this->session->userdata('is_fa') == 0)
		{
			if($this->input->server('REQUEST_METHOD') == 'POST')
			{
				$data['start_date'] = $this->input->post('start_date');
				$data['end_date'] = $this->input->post('end_date');
				$data['department_id'] = $this->session->userdata('department_id');
			}
			else 
			{
				$data['start_date'] = date('Y-m-d');
				$data['end_date'] = date('Y-m-d');
				$data['department_id'] = ' ';
			} 

			$data['uts'] = $this->users_model->get_ut_all_by_department_id($data['start_date'],$data['end_date'],$data['department_id']); 
		}

		// OIC IN BRANCH
		elseif($this->session->userdata('is_rfa') == 1 && $this->session->userdata('is_oichead') == 1)
		{
			if($this->input->server('REQUEST_METHOD') == 'POST')
			{
				$data['start_date'] = $this->input->post('start_date');
				$data['end_date'] = $this->input->post('end_date');
				$data['branch_id'] = $this->session->userdata('branch_id');
			}
			else 
			{
				$data['start_date'] = date('Y-m-d');
				$data['end_date'] = date('Y-m-d');
				$data['branch_id'] = ' ';
			} 

			$data['uts'] = $this->users_model->get_ut_all_by_oic($data['start_date'],$data['end_date'],$data['branch_id']); 
		}

		// HEADS ACCOUNTING AND IT
		elseif($this->session->userdata('is_fa') == 2)
		{
			if($this->input->server('REQUEST_METHOD') == 'POST')
			{
				$data['start_date'] = $this->input->post('start_date');
				$data['end_date'] = $this->input->post('end_date');
				$data['department_id'] = $this->session->userdata('department_id');
			}
			else 
			{
				$data['start_date'] = date('Y-m-d');
				$data['end_date'] = date('Y-m-d');
				$data['department_id'] = ' ';
			} 

			$data['uts'] = $this->users_model->get_ut_all_by_headCAM($data['start_date'],$data['end_date'],$data['department_id']); 
		}

		// HEADS SALES AND OPERATION
		elseif($this->session->userdata('is_fa') == 1 && $this->session->userdata('is_rfa') == 1)
		{
			if($this->input->server('REQUEST_METHOD') == 'POST')
			{
				$data['start_date'] = $this->input->post('start_date');
				$data['end_date'] = $this->input->post('end_date');
				$data['department_id'] = $this->session->userdata('department_id');
				$data['branch_id'] = $this->session->userdata('branch_id');
			}
			else 
			{
				$data['start_date'] = date('Y-m-d');
				$data['end_date'] = date('Y-m-d');
				$data['department_id'] = ' ';
				$data['branch_id']  = ' ';
			} 

			$data['uts'] = $this->users_model->get_ut_all_by_headSalesOperations($data['start_date'],$data['end_date'],$this->session->userdata('department_id'),$data['branch_id'] ); 
		}

		// HEADS BILLING AND COLLECTION
		elseif($this->session->userdata('is_fa') == 4 && $this->session->userdata('is_rfa') == 0)
		{
			if($this->input->server('REQUEST_METHOD') == 'POST')
			{
				$data['start_date'] = $this->input->post('start_date');
				$data['end_date'] = $this->input->post('end_date');
				$data['department_id'] = $this->session->userdata('department_id');
			}
			else 
			{
				$data['start_date'] = date('Y-m-d');
				$data['end_date'] = date('Y-m-d');
				$data['department_id'] = ' ';
			} 

			$data['uts'] = $this->users_model->get_ut_all_by_Heads($data['start_date'],$data['end_date'],$this->session->userdata('department_id')); 
		}

		// HR ASSISTANT
		elseif($this->session->userdata('is_rfv') == 1)
		{
			if($this->input->server('REQUEST_METHOD') == 'POST')
			{
				$data['start_date'] = $this->input->post('start_date');
				$data['end_date'] = $this->input->post('end_date');
				$data['branch_id'] = $this->input->post('branch_id');
			}
			else 
			{
				$data['start_date'] = date('Y-m-d');
				$data['end_date'] = date('Y-m-d');
				$data['branch_id'] = $this->input->post('branch_id');
			} 

			$data['uts'] = $this->users_model->get_ut_allbranch_rfv($data['start_date'],$data['end_date'], $data['branch_id']); 
		}

		// CFO PROCESS
		elseif($this->session->userdata('is_cfo') == 1)
		{
			if($this->input->server('REQUEST_METHOD') == 'POST')
			{
				$data['start_date'] = $this->input->post('start_date');
				$data['end_date'] = $this->input->post('end_date');
				$data['department_id'] = $this->session->userdata('department_id');
			}
			else  
			{
				$data['start_date'] = date('Y-m-d');
				$data['end_date'] = date('Y-m-d');
				$data['department_id'] = ' ';
			} 

			$data['uts'] = $this->users_model->get_ut_allbranch_process($data['start_date'],$data['end_date'],$data['department_id']); 
		}

		$data['branches'] = $this->master_model->get_branches();
		$data['cut_off'] = $this->payroll_model->get_cut_off_date();
		$data['main_content'] = 'users/lists/ut_list'; 
		$this->load->view('layouts/main', $data);
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

		redirect('users/ut_list');
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

		redirect('users/ut_list');
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

		redirect('users/ut_list');
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

		redirect('users/ut_list');
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

		redirect('users/ut_list');
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

		redirect('users/ut_list');
	}

}