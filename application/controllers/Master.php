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
	 	$limit = 5;

	 	$start = $this->uri->segment(3);

	 	$this->db->order_by('name','asc');
	 	$this->db->limit($limit, $start);
	 	//$keyword    =   $this->input->post('keyword');
	 	//$this->db->like('name', $keyword);

	 	$this->db->select("CONCAT(tbl_employees.last_name, ', ', tbl_employees.first_name , ' ', tbl_employees.middle_name) AS name,tbl_employees.id as id, tbl_employees.employee_number as employee_number,tbl_employees.last_name as last_name, branches.code as branch_code, branches.name as branch_name, company.name as company_name,");
		$this->db->from('tbl_employees');
		$this->db->where('is_active', '1');
		$this->db->join('branches', 'tbl_employees.branch_id = branches.id');
		$this->db->join('company', 'tbl_employees.company_id = company.id');

	  $query = $this->db->get();
	  $data['employee'] = $query->result();

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
				redirect('master/add_employee');
			}
		}
	}

	public function edit_employee($id)
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
			if($this->master_model->update_employee($id))
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

	public function index_user() 
	{
		$data['main_content'] = 'master/user/index';
		$this->load->view('layouts/main', $data);
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

}	