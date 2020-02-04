<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master_model extends CI_Model {

	public function get_department()
	{
		$query = $this->db->get('department');
		return $query->result(); 
	}

	public function get_branches()
	{
		$query = $this->db->get('branches');
		return $query->result();
	}

	public function get_company()
	{
		$query = $this->db->get('company');
		return $query->result();
	} 

	public function get_employee_all()
	{
		$this->db->select("employee_number as emp_num,branch_id");
	  $query = $this->db->get('tbl_employees');

	  return $query->result();
	}

	public function add_employee()
	{
		$this->db->trans_start();

		$birthdate  	= date('Y-m-d', strtotime($this->input->post('birthdate')));
		$created_date = date('Y-m-d h:i:s');
		
		$data = array( 
			'emp_no'    				 => $this->input->post('emp_no'),
			'employee_number'    => $this->input->post('employee_number'),
			'last_name'          => $this->input->post('last_name'),
			'first_name'         => $this->input->post('first_name'),
			'middle_name'        => $this->input->post('middle_name'),
			'company_id'         => $this->input->post('company'),
			'branch_id'          => $this->input->post('branches'),
			'gender'             => $this->input->post('gender'),
			'type'               => $this->input->post('type'),
			'created_date'       => $created_date,
			'created_by'         => $this->session->userdata('username')
		);

		$this->db->insert('tbl_employees', $data);

		$trans = $this->db->trans_complete();
		return $trans;
	}

	public function update_employee($id)
	{
		$data = array(
			'emp_no'    				 => $this->input->post('emp_no'),
			'employee_number'    => $this->input->post('employee_number'),
			'last_name'          => $this->input->post('last_name'),
			'first_name'         => $this->input->post('first_name'),
			'middle_name'        => $this->input->post('middle_name'),
			'company_id'         => $this->input->post('company'),
			'branch_id'          => $this->input->post('branches'),
			'gender'             => $this->input->post('gender'),
			'type'               => $this->input->post('type')
		);

		$this->db->where('id', $id);
		$query = $this->db->update('tbl_employees', $data);

	  return $query;
	}

	public function get_employee($id)
	{
		$this->db->where('id', $id);
		$query = $this->db->get('tbl_employees');

		return $query->row();
	}

	public function delete_employee($id)
	{
		$this->db->trans_start();

		$this->db->where('id', $id);
		$this->db->delete('tbl_employees');

		$trans = $this->db->trans_complete();

		return $trans;
	}

	public function inactive_employee($id)
	{
		$data = array(
			'is_active' => 0
		);

		$this->db->where('id', $id);
		$trans = $this->db->update('tbl_employees', $data);

		return $trans;
	}

	public function add_holidays()
	{

		$data = array(
			'type'  				=> $this->input->post('holiday_type'),
			'dates' 				=> $this->input->post('date'),
			'description' 	=> $this->input->post('description'),
			'branch_id'   	=> $this->input->post('branch_id'),
			'created_by'  	=> $this->session->userdata('username'),
			'created_date'	=> date('Y-m-d h:i:s')
		);

		$query = $this->db->insert('calendar', $data);

		return $query;
 
	}

	public function update_holidays($id)
	{
		$data = array(
			'type'  				=> $this->input->post('holiday_type'),
			'dates' 				=> $this->input->post('date'),
			'description' 	=> $this->input->post('description'),
			'branch_id'   	=> $this->input->post('branch_id')
		);

		$this->db->where('id', $id);
		$query = $this->db->update('calendar', $data);

		return $query; 
	}
	public function get_holidays($year,$branch_id,$type)
	{
		$this->db->select('
			calendar.id as id, 
			calendar.description as description, 
			calendar.type as type, 
			calendar.dates as dates,
			branches.code as code
		');
		$this->db->from('calendar');
		$this->db->join('branches','branches.id = calendar.branch_id', 'left');
		$this->db->where('YEAR(calendar.dates)', $year);
		$this->db->where('branch_id', $branch_id);
		$this->db->where('type', $type);
		$query = $this->db->get();

		return $query->result();
	}

	public function get_holidayss($id)
	{
		$this->db->select('calendar.id as id, calendar.description as description, calendar.type as type, calendar.dates as dates, calendar.branch_id as branch_id');
		$this->db->from('calendar');
		$this->db->where('id', $id);
		$query = $this->db->get();

		return $query->row();
	}

	public function gget_holidays($start_date,$end_date)
	{
		$this->db->select(' calendar.description as description, calendar.type as type, calendar.dates as dates, calendar.branch_id as branch_id');
		$this->db->from('calendar');
		$this->db->where('calendar.dates >=', $start_date);
		$this->db->where('calendar.dates <=', $end_date);
		$query = $this->db->get();

		return $query->result();
	}

	public function delete_holidays($id)
	{
		$this->db->trans_start();

		$this->db->where('id', $id);
		$this->db->delete('calendar');

		$trans = $this->db->trans_complete();

		return $trans;
	}

}	