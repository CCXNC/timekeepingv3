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
}

