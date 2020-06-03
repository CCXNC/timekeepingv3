<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master extends CI_Controller {

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

	public function index_employee()
	{
		// Get record count
	 	$this->load->library('pagination');

	 	$total_rows = $this->db->count_all('tbl_employees');
	 	$limit = 15;

	 	$start = $this->uri->segment(3);

	 	$this->db->order_by('name','asc');
	 	$this->db->limit($limit, $start);
	 	//$keyword    =   $this->input->post('keyword');
	 	//$this->db->like('name', $keyword);

		 $this->db->select("
			CONCAT(tbl_employees.last_name, ', ', tbl_employees.first_name , ' ', tbl_employees.middle_name) AS name,
			tbl_employees.id as id, 
			tbl_employees.employee_number as employee_number,
			tbl_employees.last_name as last_name, 
			branches.code as branch_code, 
			branches.name as branch_name, 
			company.name as company_name,
		 ");
		$this->db->from('tbl_employees');
		$this->db->where('is_active', '1');
		$this->db->join('branches', 'tbl_employees.branch_id = branches.id','left');
		$this->db->join('company', 'tbl_employees.company_id = company.id','left');
		//$this->db->join('leave_credits', 'tbl_employees.employee_number = leave_credits.employee_number','left');

	  	$query = $this->db->get();
	 	$data['employee'] = $query->result();
		 //http://nhfctimekeeping.newhorizonfinancecorp.com/index.php/user/index
	  	$config['base_url']   = 'http://it2-pc/nhfcpayrollsystem/index.php/master/index_employee';
	  	$config['total_rows'] = $total_rows;
	  	$config['per_page']   = $limit;

	  	$config['full_tag_open']    = "<ul class='pagination'>";
		$config['full_tag_close']   = "</ul>";
		$config['num_tag_open']     = "<li>";
		$config['num_tag_close']    = "</li>";
		$config['cur_tag_open']     = "<li class='disabled'><li class='active'><a href='#'>";
		$config['cur_tag_close']    = "<span class='sr-only'></span></a></li>";
		$config['next_tag_open']    = "<li>";
		$config['next_tagl_close']  = "</li>"; 
		$config['prev_tag_open']    = "<li>";
	  	$config['prev_tagl_close']  = "</li>";
		$config['first_tag_open']   = "<li>";
		$config['first_tagl_close'] = "</li>";
		$config['last_tag_open']    = "<li>";
		$config['last_tagl_close']  = "</li>";

	  
	  	$this->pagination->initialize($config);	
	 	$data['main_content'] = 'master/employee/index';
		$this->load->view('layouts/main', $data);		
	}

	public function add_employee()
	{
		$this->form_validation->set_rules('employee_number','Employee Number','required|trim');
		$this->form_validation->set_rules('first_name','Firstname','required|trim');
		$this->form_validation->set_rules('last_name','Lastname','required|trim');
		$this->form_validation->set_rules('company','Company','required|trim');
		$this->form_validation->set_rules('branches','Branch','required|trim');

		if($this->form_validation->run() == FALSE)
		{
			$data['main_content'] = 'master/employee/add1';
			$data['branches'] = $this->master_model->get_branches();
			$data['department'] = $this->master_model->get_department();
			$data['company'] = $this->master_model->get_company();
			$this->load->view('layouts/main', $data);
		}	
		else
		{
			if($this->master_model->add_employee())
			{
				$this->session->set_flashdata('add_emp','Employee Successfully Added!');
				redirect('master/index_employee');
			}
		}
	} 

	public function edit_employee($id,$employee_no)
	{
		$this->form_validation->set_rules('employee_number','Employee Number','required|trim');
		$this->form_validation->set_rules('first_name','Firstname','required|trim');
		$this->form_validation->set_rules('last_name','Lastname','required|trim');
		$this->form_validation->set_rules('company','Company','required|trim');
		$this->form_validation->set_rules('branches','Branch','required|trim');

		if($this->form_validation->run() == FALSE)
		{
			$data['main_content'] = 'master/employee/edit';
			$data['branches'] = $this->master_model->get_branches();
			$data['department'] = $this->master_model->get_department();
			$data['company'] = $this->master_model->get_company();
			$data['employee'] = $this->master_model->get_employee($id);
			$this->load->view('layouts/main', $data);
		}
		else
		{
			if($this->master_model->update_employee($id,$employee_no))
			{
				$this->session->set_flashdata('update_emp', 'Employee Successfully Updated!');
				redirect('master/index_employee');
			}
		}	
	}

	public function delete_employee($id)
	{
		if($this->master_model->delete_employee($id))
		{
			$this->session->set_flashdata('delete_emp', 'Employee Successfully Deleted!');
			redirect('master/index_employee');
		}
	}
	public function inactive_employee($id)
	{
		if($this->master_model->inactive_employee($id))
		{
			redirect('master/index_employee');
		}
	}

	public function add_user()
	{
		$this->form_validation->set_rules('emp_no_id', 'Emp No ID', 'required|trim');
		$this->form_validation->set_rules('fullname', 'Fullname', 'required|trim');
		$this->form_validation->set_rules('user_level', 'User Level', 'required|trim');
		$this->form_validation->set_rules('type', 'Type', 'required|trim');
		$this->form_validation->set_rules('branch', 'Branch', 'required|trim');
		$this->form_validation->set_rules('department', 'Department', 'required|trim');
		$this->form_validation->set_rules('company', 'Company', 'required|trim');
		$this->form_validation->set_rules('username', 'Username', 'required|trim|min_length[8]|max_length[12]');
		$this->form_validation->set_rules('password', 'Password', 'required|trim|min_length[8]|max_length[12]');

		if($this->form_validation->run() == FALSE)
		{
			$data['branches'] = $this->user_model->get_branches();
			$data['company'] = $this->user_model->get_company();
			$data['department'] = $this->user_model->get_department();
			$data['main_content'] = 'master/user/add';
			$this->load->view('layouts/main', $data);
		}
		else
		{
			if($this->user_model->add_user())
			{
				$this->session->set_flashdata('add_msg','User Successfully added!');
				redirect('master/index_user');
			}
		}
		
		
	}
	public function index_user() 
	{ 
		$data['users'] = $this->user_model->get_users();
		$data['main_content'] = 'master/user/index';
		$this->load->view('layouts/main', $data);
	}

	public function reset_password($id)
	{
		if($this->user_model->reset_password_user($id))
		{
			$this->session->set_flashdata('reset_msg', 'Password Successfully Reset!');
			redirect('master/index_user');
		}
		 
	}

	public function index_calendar()
	{
		if($this->input->server('REQUEST_METHOD') == 'POST')
		{ 
			$data['year'] = $this->input->post('year');
			$data['branch_id'] = $this->input->post('branch_id');
			$data['type'] = $this->input->post('type');
		}
		else 
		{
			$data['year'] = ' ';
			$data['branch_id'] = ' ';
			$data['type'] = ' ';
		}
		$data['holidays'] = $this->master_model->get_holidays($data['year'],$data['branch_id'],$data['type']);
		$data['branches'] = $this->master_model->get_branches();
		$data['main_content'] = 'master/calendar/index';
		$this->load->view('layouts/main', $data);
	}

	public function edit_calendar($id)
	{
		$this->form_validation->set_rules('holiday_type', 'Type', 'required|trim');
		$this->form_validation->set_rules('date', 'Date', 'required|trim');
		$this->form_validation->set_rules('description', 'Description', 'required|trim');

		if($this->form_validation->run() == FALSE)
		{
			$data['branches'] = $this->master_model->get_branches();
			$data['main_content'] = 'master/calendar/edit';
			$data['holiday'] = $this->master_model->get_holidayss($id);
			$this->load->view('layouts/main', $data);
		}
		else
		{
			if($this->master_model->update_holidays($id))
			{
				$this->session->set_flashdata('update_msg', 'Holiday Successfully Updated!');
				redirect('master/index_calendar');
			}
		}
	}

	public function add_holiday()
	{
		$this->form_validation->set_rules('holiday_type', 'Type', 'required|trim');
		$this->form_validation->set_rules('date', 'Date', 'required|trim');
		$this->form_validation->set_rules('description', 'Description', 'required|trim');

		if($this->form_validation->run() == FALSE)
		{
			$data['branches'] = $this->master_model->get_branches();
			$data['main_content'] = 'master/calendar/add';
			$this->load->view('layouts/main', $data);
		}
		else
		{
			if($this->master_model->add_holidays())
			{
				$this->session->set_flashdata('add_msg', 'Holiday Successfully Added!');
				redirect('master/index_calendar');
			}
		}
	
	}

	public function delete_holidays($id)
	{
		if($this->master_model->delete_holidays($id))
		{
			$this->session->set_flashdata('delete_msg', 'Holiday Successfully Deleted!');
			redirect('master/index_calendar');
		}
	}

	public function index_schedule()
	{
		$this->form_validation->set_rules('in', 'IN (M-TH)', 'required|trim');
		$this->form_validation->set_rules('out_m_th', 'OUT (M-TH)', 'required|trim');
		$this->form_validation->set_rules('out_f', 'OUT (F)', 'required|trim');
		$this->form_validation->set_rules('casual_in', 'Casual IN', 'required|trim');
		$this->form_validation->set_rules('casual_out_m_th', 'Casual OUT (M-TH)', 'required|trim');
		$this->form_validation->set_rules('casual_out_f', 'Casual OUT (F)', 'required|trim');

		if($this->form_validation->run() == FALSE)
		{
			$data['schedule'] = $this->master_model->get_schedule();
			$data['main_content'] = 'master/schedule/index';
			$this->load->view('layouts/main', $data);
		}
		else
		{
			if($this->master_model->update_schedule())
			{
				$this->session->set_flashdata('update_msg', 'Schedule Successfully Updated!');
				redirect('master/index_schedule');
			}
		}
		
	}

	public function delete_user($id)
	{
		if($this->user_model->delete_user($id))
		{
			$this->session->set_flashdata('delete_msg', 'USER SUCCESSFULLY DELETED!');
			redirect('master/index_user');
		}
	}

	public function index_branches()
	{
		$data['branches'] = $this->master_model->get_branches();
		$data['main_content'] = 'master/branches/index';
		$this->load->view('layouts/main', $data);
	}

	public function add_branch()
	{
		$this->form_validation->set_rules('code', 'Code', 'trim|required');
		$this->form_validation->set_rules('name', 'Name', 'trim|required');
		$this->form_validation->set_rules('oic', 'Oic', 'trim|required');
		$this->form_validation->set_rules('address', 'Address', 'trim|required');

		if($this->form_validation->run() == FALSE)
		{
			$data['main_content'] = 'master/branches/add';
			$this->load->view('layouts/main', $data);
		}
		else
		{
			if($this->master_model->add_branch())
			{
				$this->session->set_flashdata('add_msg', 'Branch Successfully Added!');
				redirect('master/index_branches');
			}
		}
	}

	public function edit_branch($id)
	{
		$this->form_validation->set_rules('code', 'Code', 'trim|required');
		$this->form_validation->set_rules('name', 'Name', 'trim|required');
		$this->form_validation->set_rules('oic', 'Oic', 'trim|required');
		$this->form_validation->set_rules('address', 'Address', 'trim|required');

		if($this->form_validation->run() == FALSE)
		{
			$data['branch'] = $this->master_model->get_branch($id);
			$data['main_content'] = 'master/branches/edit';
			$this->load->view('layouts/main', $data);
		}
		else
		{
			if($this->master_model->update_branch($id))
			{
				$this->session->set_flashdata('update_msg', 'Branch Successfully Updated!');
				redirect('master/index_branches');
			}
		}
		
	}

	public function delete_branch($id)
	{
		if($this->master_model->delete_branch($id))
		{
			$this->session->set_flashdata('delete_msg', 'Branch Successfully Deleted!');
			redirect('master/index_branches');
		}
	}

	public function index_leave_restriction()
	{
		$data['leave_restriction'] = $this->master_model->get_leave_restriction();
		$data['main_content'] = 'master/leave_restriction/index';
		$this->load->view('layouts/main', $data);
	}

	public function update_leave_restriction()
	{
		if($this->master_model->update_leave_restriction())
		{
			$this->session->set_flashdata('update_msg', 'Leave Restriction Successfully Updated!');
			redirect('master/index_leave_restriction');
		}
	}

}	