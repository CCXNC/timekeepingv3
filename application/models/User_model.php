<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model { 

	// ADMIN //
	public function admin_userpass($username,$password)
	{
		$this->db->where('username',$username);
		$this->db->where('password', $password);
		$this->db->where('is_deleted !=', 1);

		$result = $this->db->get('admin1');

		if($result->num_rows() == 1)
		{
			return $result->row();
		} 
		else
		{
			return FALSE;
		}
	}

	public function get_users()
	{
		$this->db->select('
			admin1.id as id,
			branches.name as branch_name, 
			admin1.fullname as fullname,
			admin1.username as username,
			admin1.default_password as default_password,
			admin1.password as password
		');
		$this->db->from('admin1');
		$this->db->join('branches', 'branches.id = admin1.branch_id');
		$this->db->order_by('fullname','ASC');
		$query = $this->db->get();
		
		return $query->result();
	}

	public function reset_password_user($id)
	{
		$this->db->where('id', $id);
		$query = $this->db->get('admin1');
		$default_password = $query->row()->default_password;

		$data = array(
			'password' => $default_password
		);

		$this->db->where('id', $id);
		$query1 = $this->db->update('admin1', $data);

		return $query;
	}

	public function get_department()
	{
		$this->db->order_by('name', 'ASC');
		$query = $this->db->get('department');
		return $query->result();
	}

	public function get_company()
	{
		$query = $this->db->get('company');
		return $query->result();
	}

	public function get_branches()
	{
		$query = $this->db->get('branches');
		return $query->result();
	}

	public function add_user()
	{
		$this->db->trans_start();

		$type = $this->input->post('type');

		if($type == 0)
		{
			$data = array(
				'emp_no_id'     => $this->input->post('emp_no_id'),
				'fullname'      => $this->input->post('fullname'),
				'username'      => $this->input->post('username'),
				'password'      => $this->input->post('password'),
				'user_level'    => $this->input->post('user_level'),
				'branch_id'     => $this->input->post('branch'),
				'department_id' => $this->input->post('department'),
				'company'       => $this->input->post('company'),
				'created_by'    => $this->session->userdata('username'),
				'created_date'  => date('Y-m-d H:i:s')
			);

			$this->db->insert('admin1', $data);
		}
		elseif($type == 'is_rfa')
		{
			$data = array(
				'emp_no_id'     => $this->input->post('emp_no_id'),
				'fullname'      => $this->input->post('fullname'),
				'username'      => $this->input->post('username'),
				'password'      => $this->input->post('password'),
				'user_level'    => $this->input->post('user_level'),
				'branch_id'     => $this->input->post('branch'),
				'department_id' => $this->input->post('department'),
				'company'       => $this->input->post('company'),
				'is_rfa'        => 1,
				'is_oichead'    => 1,
				'created_by'    => $this->session->userdata('username'),
				'created_date'  => date('Y-m-d H:i:s')
			);

			$this->db->insert('admin1', $data);
		}
		elseif($type == 'is_fa')
		{
			$data = array(
				'emp_no_id'     => $this->input->post('emp_no_id'),
				'fullname'      => $this->input->post('fullname'),
				'username'      => $this->input->post('username'),
				'password'      => $this->input->post('password'),
				'user_level'    => $this->input->post('user_level'),
				'branch_id'     => $this->input->post('branch'),
				'department_id' => $this->input->post('department'),
				'company'       => $this->input->post('company'),
				'is_fa'         => $this->input->post('department'),
				'created_by'    => $this->session->userdata('username'),
				'created_date'  => date('Y-m-d H:i:s')
			);

			$this->db->insert('admin1', $data);
		}
		elseif($type == 'is_oichead')
		{
			$data = array(
				'emp_no_id'     => $this->input->post('emp_no_id'),
				'fullname'      => $this->input->post('fullname'),
				'username'      => $this->input->post('username'),
				'password'      => $this->input->post('password'),
				'user_level'    => $this->input->post('user_level'),
				'branch_id'     => $this->input->post('branch'),
				'department_id' => $this->input->post('department'),
				'company'       => $this->input->post('company'),
				'is_oichead'    => 1,
				'created_by'    => $this->session->userdata('username'),
				'created_date'  => date('Y-m-d H:i:s')
			);

			$this->db->insert('admin1', $data);
		}
		elseif($type == 'is_rfv')
		{
			$data = array(
				'emp_no_id'     => $this->input->post('emp_no_id'),
				'fullname'      => $this->input->post('fullname'),
				'username'      => $this->input->post('username'),
				'password'      => $this->input->post('password'),
				'user_level'    => $this->input->post('user_level'),
				'branch_id'     => $this->input->post('branch'),
				'department_id' => $this->input->post('department'),
				'company'       => $this->input->post('company'),
				'is_rfv'        => 1,
				'created_by'    => $this->session->userdata('username'),
				'created_date'  => date('Y-m-d H:i:s')
			);

			$this->db->insert('admin1', $data);
		}
		elseif($type == 'is_verify')
		{
			$data = array(
				'emp_no_id'     => $this->input->post('emp_no_id'),
				'fullname'      => $this->input->post('fullname'),
				'username'      => $this->input->post('username'),
				'password'      => $this->input->post('password'),
				'user_level'    => $this->input->post('user_level'),
				'branch_id'     => $this->input->post('branch'),
				'department_id' => $this->input->post('department'),
				'company'       => $this->input->post('company'),
				'is_verify'     => 1,
				'is_hr'         => 1,
				'created_by'    => $this->session->userdata('username'),
				'created_date'  => date('Y-m-d H:i:s')
			);

			$this->db->insert('admin1', $data);
		}
		elseif($type == 'is_gm')
		{
			$data = array(
				'emp_no_id'     => $this->input->post('emp_no_id'),
				'fullname'      => $this->input->post('fullname'),
				'username'      => $this->input->post('username'),
				'password'      => $this->input->post('password'),
				'user_level'    => $this->input->post('user_level'),
				'branch_id'     => $this->input->post('branch'),
				'department_id' => $this->input->post('department'),
				'company'       => $this->input->post('company'),
				'is_gm'         => 1,
				'created_by'    => $this->session->userdata('username'),
				'created_date'  => date('Y-m-d H:i:s')
				
			);

			$this->db->insert('admin1', $data);
		}

		$trans = $this->db->trans_complete();

		return $trans;
	}

	public function delete_user($id)
	{
		$this->db->where('id', $id);
		$query = $this->db->delete('admin1');

		return $query;
	}

	public function change_password()
	{
		$data = array(
			'password' => $this->input->post('new_password'),
			'change_password_by' => $this->session->userdata('username'),
			'change_password_date' => date('Y-m-d H:i:s')
		);

		$this->db->where('id', $this->session->userdata('id'));
		$query = $this->db->update('admin1', $data);

		return $query;
		
	}
}

