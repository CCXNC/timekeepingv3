<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payroll_model extends CI_Model {

	public function get_allAttendance($start_date, $end_date, $branch)
	{
		$this->db->select('
			tbl_in_attendance.id as id,  
			tbl_in_attendance.employee_number as employee_number,
			tbl_in_attendance.name as name,
			tbl_in_attendance.dates as dates,
			tbl_in_attendance.times as intime,
			tbl_out_attendance.times as outtime,
			tbl_in_attendance.status as in_status, 
			tbl_out_attendance.status as out_status
			');
		$this->db->from('tbl_in_attendance');
		$this->db->where('tbl_in_attendance.dates >=', $start_date);
		$this->db->where('tbl_in_attendance.dates <=', $end_date);
		$this->db->where('tbl_in_attendance.branch_id', $branch);
		$this->db->join('tbl_out_attendance','tbl_in_attendance.id = tbl_out_attendance.in_id');
		$this->db->order_by('tbl_in_attendance.employee_number', 'ASC');
		$this->db->order_by('tbl_in_attendance.dates', 'ASC');

 		$query = $this->db->get();

		return $query->result();
	}
	public function get_allAttendance1($start_date, $end_date)
	{
		$this->db->select('
			tbl_in_attendance.id as id,
			tbl_in_attendance.employee_number as employee_number,
			tbl_in_attendance.name as name,
			tbl_in_attendance.dates as dates,
			tbl_in_attendance.times as intime,
			tbl_out_attendance.times as outtime,
			tbl_in_attendance.status as in_status, 
			tbl_out_attendance.status as out_status
			');
		$this->db->from('tbl_in_attendance');
		$this->db->where('tbl_in_attendance.dates >=', $start_date);
		$this->db->where('tbl_in_attendance.dates <=', $end_date);
		$this->db->where('tbl_in_attendance.branch_id', 1);
		$this->db->join('tbl_out_attendance','tbl_in_attendance.id = tbl_out_attendance.in_id');
		$this->db->order_by('tbl_in_attendance.employee_number', 'ASC');
		$this->db->order_by('tbl_in_attendance.dates', 'ASC');

 		$query = $this->db->get();

		return $query->result();
	}

	public function get_allAttendance2($start_date, $end_date)
	{
		$this->db->select('
			tbl_in_attendance.id as id,
			tbl_in_attendance.employee_number as employee_number,
			tbl_in_attendance.name as name,
			tbl_in_attendance.dates as dates,
			tbl_in_attendance.times as intime,
			tbl_out_attendance.times as outtime,
			tbl_in_attendance.status as in_status, 
			tbl_out_attendance.status as out_status
			');
		$this->db->from('tbl_in_attendance');
		$this->db->where('tbl_in_attendance.dates >=', $start_date);
		$this->db->where('tbl_in_attendance.dates <=', $end_date);
		$this->db->where('tbl_in_attendance.branch_id', 2);
		$this->db->join('tbl_out_attendance','tbl_in_attendance.id = tbl_out_attendance.in_id');
		$this->db->order_by('tbl_in_attendance.employee_number', 'ASC');
		$this->db->order_by('tbl_in_attendance.dates', 'ASC');

 		$query = $this->db->get();

		return $query->result();
	}

	public function get_allAttendance3($start_date, $end_date)
	{
		$this->db->select('
			tbl_in_attendance.id as id,
			tbl_in_attendance.employee_number as employee_number,
			tbl_in_attendance.name as name,
			tbl_in_attendance.dates as dates,
			tbl_in_attendance.times as intime,
			tbl_out_attendance.times as outtime,
			tbl_in_attendance.status as in_status, 
			tbl_out_attendance.status as out_status
			');
		$this->db->from('tbl_in_attendance');
		$this->db->where('tbl_in_attendance.dates >=', $start_date);
		$this->db->where('tbl_in_attendance.dates <=', $end_date);
		$this->db->where('tbl_in_attendance.branch_id', 3);
		$this->db->join('tbl_out_attendance','tbl_in_attendance.id = tbl_out_attendance.in_id');
		$this->db->order_by('tbl_in_attendance.employee_number', 'ASC');
		$this->db->order_by('tbl_in_attendance.dates', 'ASC');

 		$query = $this->db->get();

		return $query->result();
	}

	public function get_allAttendance4($start_date, $end_date)
	{
		$this->db->select('
			tbl_in_attendance.id as id,
			tbl_in_attendance.employee_number as employee_number,
			tbl_in_attendance.name as name,
			tbl_in_attendance.dates as dates,
			tbl_in_attendance.times as intime,
			tbl_out_attendance.times as outtime,
			tbl_in_attendance.status as in_status, 
			tbl_out_attendance.status as out_status
			');
		$this->db->from('tbl_in_attendance');
		$this->db->where('tbl_in_attendance.dates >=', $start_date);
		$this->db->where('tbl_in_attendance.dates <=', $end_date);
		$this->db->where('tbl_in_attendance.branch_id', 4);
		$this->db->join('tbl_out_attendance','tbl_in_attendance.id = tbl_out_attendance.in_id');
		$this->db->order_by('tbl_in_attendance.employee_number', 'ASC');
		$this->db->order_by('tbl_in_attendance.dates', 'ASC');

 		$query = $this->db->get();

		return $query->result();
	}

	public function get_allAttendance5($start_date, $end_date)
	{
		$this->db->select('
			tbl_in_attendance.id as id,
			tbl_in_attendance.employee_number as employee_number,
			tbl_in_attendance.name as name,
			tbl_in_attendance.dates as dates,
			tbl_in_attendance.times as intime,
			tbl_out_attendance.times as outtime,
			tbl_in_attendance.status as in_status, 
			tbl_out_attendance.status as out_status
			');
		$this->db->from('tbl_in_attendance');
		$this->db->where('tbl_in_attendance.dates >=', $start_date);
		$this->db->where('tbl_in_attendance.dates <=', $end_date);
		$this->db->where('tbl_in_attendance.branch_id', 5);
		$this->db->join('tbl_out_attendance','tbl_in_attendance.id = tbl_out_attendance.in_id');
		$this->db->order_by('tbl_in_attendance.employee_number', 'ASC');
		$this->db->order_by('tbl_in_attendance.dates', 'ASC');

 		$query = $this->db->get();

		return $query->result();
	}

	public function get_allAttendance6($start_date, $end_date)
	{
		$this->db->select('
			tbl_in_attendance.id as id,
			tbl_in_attendance.employee_number as employee_number,
			tbl_in_attendance.name as name,
			tbl_in_attendance.dates as dates,
			tbl_in_attendance.times as intime,
			tbl_out_attendance.times as outtime,
			tbl_in_attendance.status as in_status, 
			tbl_out_attendance.status as out_status
			');
		$this->db->from('tbl_in_attendance');
		$this->db->where('tbl_in_attendance.dates >=', $start_date);
		$this->db->where('tbl_in_attendance.dates <=', $end_date);
		$this->db->where('tbl_in_attendance.branch_id', 6);
		$this->db->join('tbl_out_attendance','tbl_in_attendance.id = tbl_out_attendance.in_id');
		$this->db->order_by('tbl_in_attendance.employee_number', 'ASC');
		$this->db->order_by('tbl_in_attendance.dates', 'ASC');

 		$query = $this->db->get();

		return $query->result();
	}

	public function get_allAttendance7($start_date, $end_date)
	{
		$this->db->select('
			tbl_in_attendance.id as id,
			tbl_in_attendance.employee_number as employee_number,
			tbl_in_attendance.name as name,
			tbl_in_attendance.dates as dates,
			tbl_in_attendance.times as intime,
			tbl_out_attendance.times as outtime,
			tbl_in_attendance.status as in_status, 
			tbl_out_attendance.status as out_status
			');
		$this->db->from('tbl_in_attendance');
		$this->db->where('tbl_in_attendance.dates >=', $start_date);
		$this->db->where('tbl_in_attendance.dates <=', $end_date);
		$this->db->where('tbl_in_attendance.branch_id', 7);
		$this->db->join('tbl_out_attendance','tbl_in_attendance.id = tbl_out_attendance.in_id');
		$this->db->order_by('tbl_in_attendance.employee_number', 'ASC');
		$this->db->order_by('tbl_in_attendance.dates', 'ASC');

 		$query = $this->db->get();

		return $query->result();
	}


	public function get_allAttendance8($start_date, $end_date)
	{
		$this->db->select('
			tbl_in_attendance.id as id,
			tbl_in_attendance.employee_number as employee_number,
			tbl_in_attendance.name as name,
			tbl_in_attendance.dates as dates,
			tbl_in_attendance.times as intime,
			tbl_out_attendance.times as outtime,
			tbl_in_attendance.status as in_status, 
			tbl_out_attendance.status as out_status
			');
		$this->db->from('tbl_in_attendance');
		$this->db->where('tbl_in_attendance.dates >=', $start_date);
		$this->db->where('tbl_in_attendance.dates <=', $end_date);
		$this->db->where('tbl_in_attendance.branch_id', 8);
		$this->db->join('tbl_out_attendance','tbl_in_attendance.id = tbl_out_attendance.in_id');
		$this->db->order_by('tbl_in_attendance.employee_number', 'ASC');
		$this->db->order_by('tbl_in_attendance.dates', 'ASC');

 		$query = $this->db->get();

		return $query->result();
	}

	public function get_allAttendance9($start_date, $end_date)
	{
		$this->db->select('
			tbl_in_attendance.id as id,
			tbl_in_attendance.employee_number as employee_number,
			tbl_in_attendance.name as name,
			tbl_in_attendance.dates as dates,
			tbl_in_attendance.times as intime,
			tbl_out_attendance.times as outtime,
			tbl_in_attendance.status as in_status, 
			tbl_out_attendance.status as out_status
			');
		$this->db->from('tbl_in_attendance');
		$this->db->where('tbl_in_attendance.dates >=', $start_date);
		$this->db->where('tbl_in_attendance.dates <=', $end_date);
		$this->db->where('tbl_in_attendance.branch_id', 9);
		$this->db->join('tbl_out_attendance','tbl_in_attendance.id = tbl_out_attendance.in_id');
		$this->db->order_by('tbl_in_attendance.employee_number', 'ASC');
		$this->db->order_by('tbl_in_attendance.dates', 'ASC');

 		$query = $this->db->get();

		return $query->result();
	}

	public function get_allAttendance10($start_date, $end_date)
	{
		$this->db->select('
			tbl_in_attendance.id as id,
			tbl_in_attendance.employee_number as employee_number,
			tbl_in_attendance.name as name,
			tbl_in_attendance.dates as dates,
			tbl_in_attendance.times as intime,
			tbl_out_attendance.times as outtime,
			tbl_in_attendance.status as in_status, 
			tbl_out_attendance.status as out_status
			');
		$this->db->from('tbl_in_attendance');
		$this->db->where('tbl_in_attendance.dates >=', $start_date);
		$this->db->where('tbl_in_attendance.dates <=', $end_date);
		$this->db->where('tbl_in_attendance.branch_id', 10);
		$this->db->join('tbl_out_attendance','tbl_in_attendance.id = tbl_out_attendance.in_id');
		$this->db->order_by('tbl_in_attendance.employee_number', 'ASC');
		$this->db->order_by('tbl_in_attendance.dates', 'ASC');

 		$query = $this->db->get();

		return $query->result();
	}

	public function get_allAttendance11($start_date, $end_date)
	{
		$this->db->select('
			tbl_in_attendance.id as id,
			tbl_in_attendance.employee_number as employee_number,
			tbl_in_attendance.name as name,
			tbl_in_attendance.dates as dates,
			tbl_in_attendance.times as intime,
			tbl_out_attendance.times as outtime,
			tbl_in_attendance.status as in_status, 
			tbl_out_attendance.status as out_status
			');
		$this->db->from('tbl_in_attendance');
		$this->db->where('tbl_in_attendance.dates >=', $start_date);
		$this->db->where('tbl_in_attendance.dates <=', $end_date);
		$this->db->where('tbl_in_attendance.branch_id', 11);
		$this->db->join('tbl_out_attendance','tbl_in_attendance.id = tbl_out_attendance.in_id');
		$this->db->order_by('tbl_in_attendance.employee_number', 'ASC');
		$this->db->order_by('tbl_in_attendance.dates', 'ASC');

 		$query = $this->db->get();

		return $query->result();
	}

	public function get_allAttendance12($start_date, $end_date)
	{
		$this->db->select('
			tbl_in_attendance.id as id,
			tbl_in_attendance.employee_number as employee_number,
			tbl_in_attendance.name as name,
			tbl_in_attendance.dates as dates,
			tbl_in_attendance.times as intime,
			tbl_out_attendance.times as outtime,
			tbl_in_attendance.status as in_status, 
			tbl_out_attendance.status as out_status
			');
		$this->db->from('tbl_in_attendance');
		$this->db->where('tbl_in_attendance.dates >=', $start_date);
		$this->db->where('tbl_in_attendance.dates <=', $end_date);
		$this->db->where('tbl_in_attendance.branch_id', 12);
		$this->db->join('tbl_out_attendance','tbl_in_attendance.id = tbl_out_attendance.in_id');
		$this->db->order_by('tbl_in_attendance.employee_number', 'ASC');
		$this->db->order_by('tbl_in_attendance.dates', 'ASC');

 		$query = $this->db->get();

		return $query->result();
	}

	public function get_allAttendance13($start_date, $end_date)
	{
		$this->db->select('
			tbl_in_attendance.id as id,
			tbl_in_attendance.employee_number as employee_number,
			tbl_in_attendance.name as name,
			tbl_in_attendance.dates as dates,
			tbl_in_attendance.times as intime,
			tbl_out_attendance.times as outtime,
			tbl_in_attendance.status as in_status, 
			tbl_out_attendance.status as out_status
			');
		$this->db->from('tbl_in_attendance');
		$this->db->where('tbl_in_attendance.dates >=', $start_date);
		$this->db->where('tbl_in_attendance.dates <=', $end_date);
		$this->db->where('tbl_in_attendance.branch_id', 13);
		$this->db->join('tbl_out_attendance','tbl_in_attendance.id = tbl_out_attendance.in_id');
		$this->db->order_by('tbl_in_attendance.employee_number', 'ASC');
		$this->db->order_by('tbl_in_attendance.dates', 'ASC');

 		$query = $this->db->get();

		return $query->result();
	}

	public function get_allAttendance14($start_date, $end_date)
	{
		$this->db->select('
			tbl_in_attendance.id as id,
			tbl_in_attendance.employee_number as employee_number,
			tbl_in_attendance.name as name,
			tbl_in_attendance.dates as dates,
			tbl_in_attendance.times as intime,
			tbl_out_attendance.times as outtime,
			tbl_in_attendance.status as in_status, 
			tbl_out_attendance.status as out_status
			');
		$this->db->from('tbl_in_attendance');
		$this->db->where('tbl_in_attendance.dates >=', $start_date);
		$this->db->where('tbl_in_attendance.dates <=', $end_date);
		$this->db->where('tbl_in_attendance.branch_id', 14);
		$this->db->join('tbl_out_attendance','tbl_in_attendance.id = tbl_out_attendance.in_id');
		$this->db->order_by('tbl_in_attendance.employee_number', 'ASC');
		$this->db->order_by('tbl_in_attendance.dates', 'ASC');

 		$query = $this->db->get();

		return $query->result();
	}

	public function get_allAttendance15($start_date, $end_date)
	{
		$this->db->select('
			tbl_in_attendance.id as id,
			tbl_in_attendance.employee_number as employee_number,
			tbl_in_attendance.name as name,
			tbl_in_attendance.dates as dates,
			tbl_in_attendance.times as intime,
			tbl_out_attendance.times as outtime,
			tbl_in_attendance.status as in_status, 
			tbl_out_attendance.status as out_status
			');
		$this->db->from('tbl_in_attendance');
		$this->db->where('tbl_in_attendance.dates >=', $start_date);
		$this->db->where('tbl_in_attendance.dates <=', $end_date);
		$this->db->where('tbl_in_attendance.branch_id', 15);
		$this->db->join('tbl_out_attendance','tbl_in_attendance.id = tbl_out_attendance.in_id');
		$this->db->order_by('tbl_in_attendance.employee_number', 'ASC');
		$this->db->order_by('tbl_in_attendance.dates', 'ASC');

 		$query = $this->db->get();

		return $query->result();
	}

	public function get_allAttendance16($start_date, $end_date)
	{
		$this->db->select('
			tbl_in_attendance.id as id,
			tbl_in_attendance.employee_number as employee_number,
			tbl_in_attendance.name as name,
			tbl_in_attendance.dates as dates,
			tbl_in_attendance.times as intime,
			tbl_out_attendance.times as outtime,
			tbl_in_attendance.status as in_status, 
			tbl_out_attendance.status as out_status
			');
		$this->db->from('tbl_in_attendance');
		$this->db->where('tbl_in_attendance.dates >=', $start_date);
		$this->db->where('tbl_in_attendance.dates <=', $end_date);
		$this->db->where('tbl_in_attendance.branch_id', 16);
		$this->db->join('tbl_out_attendance','tbl_in_attendance.id = tbl_out_attendance.in_id');
		$this->db->order_by('tbl_in_attendance.employee_number', 'ASC');
		$this->db->order_by('tbl_in_attendance.dates', 'ASC');

 		$query = $this->db->get();

		return $query->result();
	}

	public function get_allAttendance17($start_date, $end_date)
	{
		$this->db->select('
			tbl_in_attendance.id as id,
			tbl_in_attendance.employee_number as employee_number,
			tbl_in_attendance.name as name,
			tbl_in_attendance.dates as dates,
			tbl_in_attendance.times as intime,
			tbl_out_attendance.times as outtime,
			tbl_in_attendance.status as in_status, 
			tbl_out_attendance.status as out_status
			');
		$this->db->from('tbl_in_attendance');
		$this->db->where('tbl_in_attendance.dates >=', $start_date);
		$this->db->where('tbl_in_attendance.dates <=', $end_date);
		$this->db->where('tbl_in_attendance.branch_id', 17);
		$this->db->join('tbl_out_attendance','tbl_in_attendance.id = tbl_out_attendance.in_id');
		$this->db->order_by('tbl_in_attendance.employee_number', 'ASC');
		$this->db->order_by('tbl_in_attendance.dates', 'ASC');

 		$query = $this->db->get();

		return $query->result();
	}

	public function get_allAttendance18($start_date, $end_date)
	{
		$this->db->select('
			tbl_in_attendance.id as id,
			tbl_in_attendance.employee_number as employee_number,
			tbl_in_attendance.name as name,
			tbl_in_attendance.dates as dates,
			tbl_in_attendance.times as intime,
			tbl_out_attendance.times as outtime,
			tbl_in_attendance.status as in_status, 
			tbl_out_attendance.status as out_status
			');
		$this->db->from('tbl_in_attendance');
		$this->db->where('tbl_in_attendance.dates >=', $start_date);
		$this->db->where('tbl_in_attendance.dates <=', $end_date);
		$this->db->where('tbl_in_attendance.branch_id', 18);
		$this->db->join('tbl_out_attendance','tbl_in_attendance.id = tbl_out_attendance.in_id');
		$this->db->order_by('tbl_in_attendance.employee_number', 'ASC');
		$this->db->order_by('tbl_in_attendance.dates', 'ASC');

 		$query = $this->db->get();

		return $query->result();
	}

	public function get_allAttendance19($start_date, $end_date)
	{
		$this->db->select('
			tbl_in_attendance.id as id,
			tbl_in_attendance.employee_number as employee_number,
			tbl_in_attendance.name as name,
			tbl_in_attendance.dates as dates,
			tbl_in_attendance.times as intime,
			tbl_out_attendance.times as outtime,
			tbl_in_attendance.status as in_status, 
			tbl_out_attendance.status as out_status
			');
		$this->db->from('tbl_in_attendance');
		$this->db->where('tbl_in_attendance.dates >=', $start_date);
		$this->db->where('tbl_in_attendance.dates <=', $end_date);
		$this->db->where('tbl_in_attendance.branch_id', 19);
		$this->db->join('tbl_out_attendance','tbl_in_attendance.id = tbl_out_attendance.in_id');
		$this->db->order_by('tbl_in_attendance.employee_number', 'ASC');
		$this->db->order_by('tbl_in_attendance.dates', 'ASC');

 		$query = $this->db->get();

		return $query->result();
	}

	public function get_allAttendance20($start_date, $end_date)
	{
		$this->db->select('
			tbl_in_attendance.id as id,
			tbl_in_attendance.employee_number as employee_number,
			tbl_in_attendance.name as name,
			tbl_in_attendance.dates as dates,
			tbl_in_attendance.times as intime,
			tbl_out_attendance.times as outtime,
			tbl_in_attendance.status as in_status, 
			tbl_out_attendance.status as out_status
			');
		$this->db->from('tbl_in_attendance');
		$this->db->where('tbl_in_attendance.dates >=', $start_date);
		$this->db->where('tbl_in_attendance.dates <=', $end_date);
		$this->db->where('tbl_in_attendance.branch_id', 20);
		$this->db->join('tbl_out_attendance','tbl_in_attendance.id = tbl_out_attendance.in_id');
		$this->db->order_by('tbl_in_attendance.employee_number', 'ASC');
		$this->db->order_by('tbl_in_attendance.dates', 'ASC');

 		$query = $this->db->get();

		return $query->result();
	}

	public function get_allAttendance21($start_date, $end_date)
	{
		$this->db->select('
			tbl_in_attendance.id as id,
			tbl_in_attendance.employee_number as employee_number,
			tbl_in_attendance.name as name,
			tbl_in_attendance.dates as dates,
			tbl_in_attendance.times as intime,
			tbl_out_attendance.times as outtime,
			tbl_in_attendance.status as in_status, 
			tbl_out_attendance.status as out_status
			');
		$this->db->from('tbl_in_attendance');
		$this->db->where('tbl_in_attendance.dates >=', $start_date);
		$this->db->where('tbl_in_attendance.dates <=', $end_date);
		$this->db->where('tbl_in_attendance.branch_id', 21);
		$this->db->join('tbl_out_attendance','tbl_in_attendance.id = tbl_out_attendance.in_id');
		$this->db->order_by('tbl_in_attendance.employee_number', 'ASC');
		$this->db->order_by('tbl_in_attendance.dates', 'ASC');

 		$query = $this->db->get();

		return $query->result();
	}

	public function get_allAttendance22($start_date, $end_date)
	{
		$this->db->select('
			tbl_in_attendance.id as id,
			tbl_in_attendance.employee_number as employee_number,
			tbl_in_attendance.name as name,
			tbl_in_attendance.dates as dates,
			tbl_in_attendance.times as intime,
			tbl_out_attendance.times as outtime,
			tbl_in_attendance.status as in_status, 
			tbl_out_attendance.status as out_status
			');
		$this->db->from('tbl_in_attendance');
		$this->db->where('tbl_in_attendance.dates >=', $start_date);
		$this->db->where('tbl_in_attendance.dates <=', $end_date);
		$this->db->where('tbl_in_attendance.branch_id', 22);
		$this->db->join('tbl_out_attendance','tbl_in_attendance.id = tbl_out_attendance.in_id');
		$this->db->order_by('tbl_in_attendance.employee_number', 'ASC');
		$this->db->order_by('tbl_in_attendance.dates', 'ASC');

 		$query = $this->db->get();

		return $query->result();
	}

	public function get_allAttendance23($start_date, $end_date)
	{
		$this->db->select('
			tbl_in_attendance.id as id,
			tbl_in_attendance.employee_number as employee_number,
			tbl_in_attendance.name as name,
			tbl_in_attendance.dates as dates,
			tbl_in_attendance.times as intime,
			tbl_out_attendance.times as outtime,
			tbl_in_attendance.status as in_status, 
			tbl_out_attendance.status as out_status
			');
		$this->db->from('tbl_in_attendance');
		$this->db->where('tbl_in_attendance.dates >=', $start_date);
		$this->db->where('tbl_in_attendance.dates <=', $end_date);
		$this->db->where('tbl_in_attendance.branch_id', 23);
		$this->db->join('tbl_out_attendance','tbl_in_attendance.id = tbl_out_attendance.in_id');
		$this->db->order_by('tbl_in_attendance.employee_number', 'ASC');
		$this->db->order_by('tbl_in_attendance.dates', 'ASC');

 		$query = $this->db->get();

		return $query->result();
	}

	public function get_allAttendance24($start_date, $end_date)
	{
		$this->db->select('
			tbl_in_attendance.id as id,
			tbl_in_attendance.employee_number as employee_number,
			tbl_in_attendance.name as name,
			tbl_in_attendance.dates as dates,
			tbl_in_attendance.times as intime,
			tbl_out_attendance.times as outtime,
			tbl_in_attendance.status as in_status, 
			tbl_out_attendance.status as out_status
			');
		$this->db->from('tbl_in_attendance');
		$this->db->where('tbl_in_attendance.dates >=', $start_date);
		$this->db->where('tbl_in_attendance.dates <=', $end_date);
		$this->db->where('tbl_in_attendance.branch_id', 24);
		$this->db->join('tbl_out_attendance','tbl_in_attendance.id = tbl_out_attendance.in_id');
		$this->db->order_by('tbl_in_attendance.employee_number', 'ASC');
		$this->db->order_by('tbl_in_attendance.dates', 'ASC');

 		$query = $this->db->get();

		return $query->result();
	}

	public function get_holiday($start_date, $end_date)
	{
		$this->db->select('count(calendar.dates) as dates');
		$this->db->from('calendar');
		$this->db->where('calendar.dates >=', $start_date);
		$this->db->where('calendar.dates <=', $end_date);
		$query = $this->db->get();

		return $query->result(); 
	}

	public function get_in($start_date, $end_date, $status)
	{

		$this->db->select('
			tbl_in_attendance.id as id,
			tbl_in_attendance.employee_number as employee_number,
			tbl_in_attendance.name as name,
			tbl_in_attendance.dates as dates,
			tbl_in_attendance.times as intime,
			tbl_in_attendance.branch_id as branch_id,
			tbl_in_attendance.status as status,
			tbl_out_attendance.times as outtime,
			tbl_out_attendance.status aS out_status
			');
		$this->db->from('tbl_in_attendance');
		$this->db->where('tbl_in_attendance.dates >=', $start_date);
		$this->db->where('tbl_in_attendance.dates <=', $end_date);
		$this->db->where('tbl_in_attendance.status =', 'NO IN');
		//$this->db->where('tbl_out_attendance.status =', 'NO OUT');
		$this->db->where('tbl_in_attendance.status =', $status);
		$this->db->join('tbl_out_attendance','tbl_in_attendance.id = tbl_out_attendance.in_id');
		$this->db->order_by('tbl_in_attendance.dates', 'ASC');
		$this->db->order_by('tbl_in_attendance.employee_number', 'ASC');
		$this->db->order_by('tbl_in_attendance.status', 'ASC');

		$query = $this->db->get();

		return $query->result();
	}
	public function get_out($start_date, $end_date, $status)
	{
		$this->db->select('
			tbl_out_attendance.id as id,
			tbl_out_attendance.employee_number as employee_number,
			tbl_out_attendance.name as name,
			tbl_out_attendance.dates as dates,
			tbl_out_attendance.times as outtime,
			tbl_out_attendance.status as status,
			tbl_out_attendance.branch_id as branch_id,
			tbl_in_attendance.times as intime
			');
		$this->db->from('tbl_out_attendance');
		$this->db->where('tbl_out_attendance.dates >=', $start_date);
		$this->db->where('tbl_out_attendance.dates <=', $end_date);
		$this->db->where('tbl_out_attendance.status =', 'NO OUT');
		$this->db->where('tbl_out_attendance.status =', $status);
		$this->db->join('tbl_in_attendance','tbl_in_attendance.id = tbl_out_attendance.in_id');
		$this->db->order_by('tbl_out_attendance.dates', 'ASC');
		$this->db->order_by('tbl_out_attendance.employee_number', 'ASC');
		$this->db->order_by('tbl_out_attendance.status', 'ASC');

		$query = $this->db->get();

		return $query->result();
	}

	public function add_cut_off_date()
	{
		$this->db->trans_start();

		$start_date = date('Y-m-d', strtotime($this->input->post('start_date')));
		$end_date = date('Y-m-d', strtotime($this->input->post('end_date')));

		$data = array(
		'start_date' => $start_date,
		'end_date'   => $end_date 
		);
 
		$this->db->insert('tbl_cut_off_date', $data);

		$this->db->select('id,start_date,end_date');
		$this->db->order_by('id','DESC');
		$query = $this->db->get('tbl_cut_off_date');
		$id = $query->row()->id;
		$start_date = $query->row()->start_date;
		$end_date = $query->row()->end_date;


   	$datediff = (strtotime($end_date) - strtotime($start_date));
		$num_dates = floor($datediff / (60 * 60 * 24));
		$num_dates = $num_dates + 1;

		$data = array(
			'total_days'  => $num_dates
		);

		$this->db->where('id', $id);
		$this->db->update('tbl_cut_off_date', $data);

		$trans = $this->db->trans_complete();

		return $trans;
	}

	public function get_cut_off_date()
	{
		$this->db->select('total_days,start_date,end_date');
		$this->db->from('tbl_cut_off_date');
		$this->db->order_by('id','DESC');
		$query = $this->db->get();

		return $query->row();
	}

	public function employee_data($employee_no, $start_date, $end_date)
	{	

		$this->db->select('
			tbl_in_attendance.id as id,
			tbl_in_attendance.employee_number as employee_number,
			tbl_in_attendance.name as name,
			tbl_in_attendance.dates as date,
			tbl_in_attendance.times as intime,
			tbl_out_attendance.times as outtime,
			tbl_in_attendance.status as in_status,
			tbl_out_attendance.status as out_status
			');
		$this->db->from('tbl_in_attendance');
		//$this->db->order_by('name', 'ASC');
		$this->db->order_by('date', 'ASC');
		$this->db->where('tbl_in_attendance.employee_number', $employee_no);
		$this->db->where('tbl_in_attendance.dates >=', $start_date);
		$this->db->where('tbl_in_attendance.dates <=', $end_date);
		$this->db->join('tbl_out_attendance','tbl_in_attendance.id = tbl_out_attendance.in_id');

		$query = $this->db->get();
		return $query->result();
	}

	public function get_ot($employee_no, $start_date, $end_date)
	{
		$this->db->select('
			tbl_ot.id as id, 
			tbl_ot.employee_number as employee_number, 
			tbl_ot.name as name,
			tbl_ot.time_in as time_in, 
			tbl_ot.time_out as time_out, 
			tbl_ot.date_ot as date_ot,
			tbl_ot.ot_num as total_ot,
			tbl_ot.ot_type_name as ot_type_name, 
			tbl_ot.nature_of_work as nature_of_work,
			tbl_in_attendance.times as employee_in, 
			tbl_out_attendance.times as employee_out,
			tbl_ot.status as tbl_ot_status,
			tbl_in_attendance.branch_id as branch_id
		');
		$this->db->from('tbl_ot');
		$this->db->join('tbl_in_attendance', 'tbl_in_attendance.employee_number = tbl_ot.employee_number AND tbl_in_attendance.dates = tbl_ot.date_ot','left');
		$this->db->join('tbl_out_attendance', 'tbl_out_attendance.employee_number = tbl_ot.employee_number AND tbl_out_attendance.dates = tbl_ot.date_ot','left');
		$this->db->order_by('tbl_ot.date_ot', 'ASC');
		$this->db->where('tbl_ot.employee_number', $employee_no);
		$this->db->where('tbl_ot.date_ot >=', $start_date);
		$this->db->where('tbl_ot.date_ot <=', $end_date);
		$query = $this->db->get();

		return $query->result();
	}

	public function get_ots($start_date, $end_date)
	{
		$this->db->select('
			tbl_ot.id as id, 
			tbl_ot.employee_number as employee_number, 
			tbl_ot.name as name,
			tbl_ot.time_in as time_in, 
			tbl_ot.time_out as time_out, 
			tbl_ot.date_ot as date_ot,
			tbl_ot.ot_num as total_ot,
			tbl_ot.ot_type_name as ot_type_name, 
			tbl_ot.nature_of_work as nature_of_work,
			tbl_in_attendance.times as employee_in, 
			tbl_out_attendance.times as employee_out,
			tbl_ot.status as tbl_ot_status,
			tbl_in_attendance.branch_id as branch_id
		');

		$this->db->from('tbl_ot');
		$this->db->join('tbl_in_attendance', 'tbl_in_attendance.employee_number = tbl_ot.employee_number AND tbl_in_attendance.dates = tbl_ot.date_ot','left');
		$this->db->join('tbl_out_attendance', 'tbl_out_attendance.employee_number = tbl_ot.employee_number AND tbl_out_attendance.dates = tbl_ot.date_ot','left');
		$this->db->order_by('tbl_ot.date_ot', 'ASC');
		$this->db->order_by('tbl_ot.employee_number', 'ASC');	
		$this->db->where('tbl_ot.date_ot >=', $start_date);
		$this->db->where('tbl_ot.date_ot <=', $end_date);
		$query = $this->db->get();

		return $query->result();
	}


	public function get_ot_id($id)
	{
		$this->db->select('tbl_ot.id as id, tbl_ot.employee_number as employee_number, tbl_ot.name as name,tbl_ot.time_in as time_in, tbl_ot.time_out as time_out, tbl_ot.date_ot as date_ot, tbl_ot.nature_of_work as nature_of_work');
		$this->db->from('tbl_ot');
		$this->db->where('id', $id);
		$query = $this->db->get();

		return $query->row();
	}

	public function add_ot()
	{
		$this->db->trans_start();

		$ot_type = $this->input->post('ot_type');
		$explode_ot = explode("|", $ot_type);
		$name = $this->input->post('name');

		$explod_name = explode("|", $name);

		$data = array(
			'employee_number' => $explod_name[1],
			'name'            => $explod_name[0],
			'date_ot'         => $this->input->post('date'),
			'ot_type'         => $explode_ot[0],
			'ot_type_name'    => $explode_ot[1],
			'time_in'         => $this->input->post('time_in'),
			'time_out'        => $this->input->post('time_out'),
			'nature_of_work'  => $this->input->post('nature_of_work'),
			'status'          => 'FOR APPROVAL',
			'encode_by'       => $this->session->userdata('username'),
			'encode_date'     => date('Y-m-d H:i:s')
		);

		$query = $this->db->insert('tbl_ot', $data);

		$this->db->select('id,date_ot,time_in,time_out');
		$this->db->order_by('id','DESC');
		$this->db->from('tbl_ot');
		$query = $this->db->get();
		$id = $query->row()->id;
		$timein = $query->row()->time_in;
		$timeout = $query->row()->time_out;
		$date_ot = $query->row()->date_ot;

		$convert_date_ot = date('w', strtotime($date_ot));
		$in_ot = explode(':', $timein);
 		$hr_in_ot = $in_ot[0];
 		$min_in_ot = $in_ot[1];

 		$out_ot = explode(':', $timeout);
 		$hr_out_ot = $out_ot[0];
 		$min_out_ot = $out_ot[1];

 		$total_ot_in_min = intval($hr_in_ot*60) + $min_in_ot; 
		$total_ot_out_min = intval($hr_out_ot*60) + $min_out_ot; 
		$halfday_start = 721;
		$halfday_end = 750;
		if($convert_date_ot == 6 || $convert_date_ot == 0)
		{
			if($halfday_start > $total_ot_out_min || $halfday_start < $total_ot_in_min)
			{
				$total_min_diff = intval($total_ot_out_min - $total_ot_in_min);
				$hr_diff = intval($total_min_diff/60);
				$min_diff = intval($total_min_diff%60);
				$ot_num = $total_min_diff;

				$data = array(
					'ot_num' => $ot_num
				);

				$this->db->where('id', $id);
				$this->db->update('tbl_ot', $data);
			}
			else
			{
				$total_min_diff = intval($total_ot_out_min - $total_ot_in_min - 60);
				$hr_diff = intval($total_min_diff/60);
				$min_diff = intval($total_min_diff%60);
				$ot_num = $total_min_diff;

				$data = array(
					'ot_num' => $ot_num
				);

				$this->db->where('id', $id);
				$this->db->update('tbl_ot', $data);
			}
			
		}
		else
		{
			$total_min_diff = intval($total_ot_out_min - $total_ot_in_min);
			$hr_diff = intval($total_min_diff/60);
			$min_diff = intval($total_min_diff%60);
			$ot_num = $total_min_diff;

			$data = array(
				'ot_num' => $ot_num
			);

			$this->db->where('id', $id);
			$this->db->update('tbl_ot', $data);
		}

		$trans = $this->db->trans_complete();
		return $trans;
	
	}

	public function update_ot($id)
	{
		$time_in = $this->input->post('time_in');
		$time_out = $this->input->post('time_out');
		$date = $this->input->post('date');

		$convert_date_ot = date('w', strtotime($date));
		$in_ot = explode(':', $time_in);
 		$hr_in_ot = $in_ot[0];
 		$min_in_ot = $in_ot[1];

 		$out_ot = explode(':', $time_out);
 		$hr_out_ot = $out_ot[0];
 		$min_out_ot = $out_ot[1];

 		$total_ot_in_min = intval($hr_in_ot*60) + $min_in_ot; 
		$total_ot_out_min = intval($hr_out_ot*60) + $min_out_ot; 
		$halfday_start = 721;
		$halfday_end = 750;

		if($convert_date_ot == 6 || $convert_date_ot == 0)
		{
			if($halfday_start > $total_ot_out_min || $halfday_start < $total_ot_in_min)
			{
				$total_min_diff = intval($total_ot_out_min - $total_ot_in_min);
				$hr_diff = intval($total_min_diff/60);
				$min_diff = intval($total_min_diff%60);
				$ot_num = $total_min_diff;
			}
			else
			{
				$total_min_diff = intval($total_ot_out_min - $total_ot_in_min - 60);
				$hr_diff = intval($total_min_diff/60);
				$min_diff = intval($total_min_diff%60);
				$ot_num = $total_min_diff;
			}
		}
		else
		{
			$total_min_diff = intval($total_ot_out_min - $total_ot_in_min);
			$hr_diff = intval($total_min_diff/60);
			$min_diff = intval($total_min_diff%60);
			$ot_num = $total_min_diff;
		}
		
		$data = array(
			'employee_number' => $this->input->post('employee_number'),
			'name'            => $this->input->post('name'),
			'date_ot'         => $date,
			'time_in'         => $time_in,
			'time_out'        => $time_out,
			'ot_num'          => $ot_num,
			'nature_of_work'  => $this->input->post('nature_of_work')
		);
		$this->db->where('id', $id);
		$query = $this->db->update('tbl_ot', $data);

		return $query;
	}

	public function delete_ot($id)
	{
		$this->db->where('id', $id);
		$query = $this->db->delete('tbl_ot');

		return $query;
	}

	public function get_schedules()
	{
		$this->db->select('*');
		$query = $this->db->get('tbl_schedules');
		return $query->result();
	}

	public function employee_name($employee_no)
	{	
		$this->db->select("CONCAT(tbl_employees.last_name, ' ', tbl_employees.first_name , ' ', tbl_employees.middle_name) AS name");
		$this->db->where('employee_number', $employee_no);
		$query = $this->db->get('tbl_employees');
	
		return $query->row();
	}
	public function employee_type($employee_no)
	{	
		$this->db->select('type, branch_id');
		$this->db->where('employee_number', $employee_no);
		$query = $this->db->get('tbl_employees');
	
		return $query->row();
	}
	public function employee_sched()
	{	
		$this->db->select('*');
		$query = $this->db->get('tbl_schedules');
		return $query->row();
	}
	public function set_in_out($employee_no, $start_date)
	{	
		$this->db->select('tbl_in_attendance.id as id,tbl_in_attendance.employee_number as employee_number, tbl_in_attendance.name as name');
		$this->db->from('tbl_in_attendance');
		$this->db->where('employee_number', $employee_no);
		$this->db->where('date >=', $start_date);
		$query = $this->db->get();
		return $query->row();
	}	

	public function get_slvls($id)
	{
		$this->db->select("tbl_slvl.id as id, tbl_slvl.employee_number as employee_number, tbl_slvl.name as name, tbl_slvl.type as type,tbl_slvl.type_name as type_name, tbl_slvl.date as date, tbl_slvl.effective_date_start as date_start, tbl_slvl.effective_date_end as date_end, tbl_slvl.reason as reason, tbl_slvl.sl_am_pm as sl_am_pm");
		$this->db->from('tbl_slvl');
		$this->db->where('id', $id);
		$query = $this->db->get();

		return $query->row();
	}

	public function add_slvl() 
	{
		$this->db->trans_start();

		$hf = $this->input->post('HF');
		$slvl_type = $this->input->post('slvl_type');
		$start_date = date('Y-m-d', strtotime($this->input->post('start_date')));
		$end_date = date('Y-m-d', strtotime($this->input->post('start_date')));
		$name = $this->input->post('name');

		$explod_name = explode("|", $name);

		$explode_type =explode("|", $slvl_type);

		$data = array(
			'employee_number'      => $explod_name[1],
			'name'                 => $explod_name[0],
			'date'								 => $start_date,
			'effective_date_start' => $start_date,
			'effective_date_end'   => $end_date,
			'type'                 => $explode_type[0],
			'sl_am_pm'             => $hf,
			'type_name'            => $explode_type[1],
			'reason'               => $this->input->post('reason'),
			'encode_by'            => $this->session->userdata('username'),
			'encode_date'          => date('Y-m-d h:i:s'),
			'status'               => 'FOR APPROVAL'
		);

		$this->db->insert('tbl_slvl', $data);

		$this->db->select('id,effective_date_start, effective_date_end');
		$this->db->order_by('id', 'DESC');
		$query=$this->db->get('tbl_slvl');
		$id = $query->row()->id;
		$startdate = $query->row()->effective_date_start;
		$enddate = $query->row()->effective_date_end;
		$date = date('w', strtotime($startdate));

		$data = array(
			'for_id'               => $id, 
			'employee_number'      => $explod_name[1],
			'name'                 => $explod_name[0],
			'date'								 =>	$start_date,
			'date_start' 					 => $start_date,
			'date_end'             => $end_date,
			'type'                 => $explode_type[0],
			'type_name'            => $explode_type[1]. ' ' .'(' . $hf . ')' ,
			'status'               => 'FOR APPROVAL'
		);

		$this->db->insert('tbl_remarks', $data);

		if($hf == 'HFAM' && $explode_type[0] == 'AB' && $date != '5')
		{
			// 30 mins for cwwut
			$data = array( 
				'slvl_num' => .5
			); 
			$this->db->where('id', $id); 
			$this->db->update('tbl_slvl', $data); 
		}
		elseif($hf == 'HFAM' && $explode_type[0] == 'AB' && $date == '5')
		{
			$data = array( 
				'slvl_num' => .5 
			); 
			$this->db->where('id', $id); 
			$this->db->update('tbl_slvl', $data); 
		}
		elseif($hf == 'HFPM' && $explode_type[0] == 'AB' && $date != '5')
		{
			// 30 mins for cwwut
			$data = array( 
				'slvl_num' => .5
			); 
			$this->db->where('id', $id); 
			$this->db->update('tbl_slvl', $data); 
		} 
		elseif($hf == 'HFPM' && $explode_type[0] == 'AB' && $date == '5')
		{
			$data = array( 
				'slvl_num' => .5
			); 
			$this->db->where('id', $id); 
			$this->db->update('tbl_slvl', $data); 
		} 
		elseif($hf == 'HF' && $explode_type[0] == 'AB')
		{
			// 30 mins for cwwut
			$data = array( 
				'slvl_num' => .5
			); 
			$this->db->where('id', $id); 
			$this->db->update('tbl_slvl', $data); 
		} 
		elseif($hf == 'HF' && $explode_type[0] == 'VL')
		{
			$data = array( 
				'slvl_num' => .5
			); 
			$this->db->where('id', $id); 
			$this->db->update('tbl_slvl', $data); 
		} 
		elseif($hf == 'HFAM' && $explode_type[0] == 'VL')
		{
			$data = array( 
				'slvl_num' => .5
			); 
			$this->db->where('id', $id); 
			$this->db->update('tbl_slvl', $data); 
		} 
		elseif($hf == 'HFPM' && $explode_type[0] == 'VL')
		{
			$data = array( 
				'slvl_num' => .5
			); 
			$this->db->where('id', $id); 
			$this->db->update('tbl_slvl', $data); 
		} 
		elseif($hf == 'HF' && $explode_type[0] == 'SL')
		{
			$data = array( 
				'slvl_num' => .5
			); 
			$this->db->where('id', $id); 
			$this->db->update('tbl_slvl', $data); 
		} 
		elseif($hf == 'HFAM' && $explode_type[0] == 'SL')
		{
			$data = array( 
				'slvl_num' => .5
			); 
			$this->db->where('id', $id); 
			$this->db->update('tbl_slvl', $data); 
		} 
		elseif($hf == 'HFPM' && $explode_type[0] == 'SL')
		{
			$data = array( 
				'slvl_num' => .5
			); 
			$this->db->where('id', $id); 
			$this->db->update('tbl_slvl', $data); 
		} 
		elseif($hf == 'WD' && $explode_type[0] == 'AB' && $date != '5')
		{
			// 1 hr for cwwut
			$data = array( 
				'slvl_num' => 1
			); 
			$this->db->where('id', $id);
			$this->db->update('tbl_slvl', $data); 
		} 
		elseif($hf == 'WD' && $explode_type[0] == 'AB' && $date == '5')
		{
			$data = array( 
				'slvl_num' => 1
			); 
			$this->db->where('id', $id); 
			$this->db->update('tbl_slvl', $data); 
		} 
		elseif($hf == 'WD')
		{
			$start_date = $startdate; 
	   	$end_date = $enddate;

	   	$datediff = (strtotime($end_date) - strtotime($start_date));
			$num_dates = floor($datediff / (60 * 60 * 24));
			$num_dates = $num_dates + 1;

			$data = array(
				'slvl_num' => $num_dates
			);
			$this->db->where('id', $id);
			$this->db->update('tbl_slvl', $data);
		}

		$trans = $this->db->trans_complete();

		return $trans;
	}

	public function update_slvl($id)
	{
		$this->db->trans_start();

		$hf = $this->input->post('HF');
		$slvl_type = $this->input->post('slvl_type');
		$start_date = date('Y-m-d', strtotime($this->input->post('start_date')));
		$end_date = date('Y-m-d', strtotime($this->input->post('start_date')));
		$name = $this->input->post('name');
		$date = date('w', strtotime($start_date));

		$explode_type =explode("|", $slvl_type);

		$explod_name = explode("|", $name);

		$data = array(
			'employee_number'      => $explod_name[1],
			'name'                 => $explod_name[0],
			'date'								 =>	$start_date,
			'effective_date_start' => $start_date,
			'type'                 => $explode_type[0],
			'sl_am_pm'             => $hf,
			'type_name'            => $explode_type[1],
			'reason'               => $this->input->post('reason')
		);

		$this->db->where('id', $id);
		$this->db->update('tbl_slvl', $data);

		$data = array(
			'date'       => $start_date,
			'type' 		   => $explode_type[0],
			'type_name'  => $explode_type[1]. ' ' .'(' . $hf . ')' 
		);

		$this->db->where('for_id', $id);
		$this->db->update('tbl_remarks', $data);

		if($hf == 'HFAM')
		{
			$data = array( 
				'slvl_num' => .5 
			); 
			$this->db->where('id', $id); 
			$this->db->update('tbl_slvl', $data); 
		}
		elseif($hf == 'HFAM' && $explode_type[0] == 'AB' && $date != '5')
		{
			// 30 mins for cwwut
			$data = array( 
				'slvl_num' => .5
			); 
			$this->db->where('id', $id); 
			$this->db->update('tbl_slvl', $data); 
		}
		elseif($hf == 'HFAM' && $explode_type[0] == 'AB' && $date == '5')
		{
			$data = array( 
				'slvl_num' => .5
			); 
			$this->db->where('id', $id); 
			$this->db->update('tbl_slvl', $data); 
		}
		elseif($hf == 'HFPM' && $explode_type[0] == 'AB' && $date != '5')
		{
			// 30 mins for cwwut
			$data = array( 
				'slvl_num' => .5 
			); 
			$this->db->where('id', $id); 
			$this->db->update('tbl_slvl', $data); 
		} 
		elseif($hf == 'HFPM' && $explode_type[0] == 'AB' && $date == '5')
		{
			$data = array( 
				'slvl_num' => .5
			); 
			$this->db->where('id', $id); 
			$this->db->update('tbl_slvl', $data); 
		} 
		elseif($hf == 'HF' && $explode_type[0] == 'AB')
		{
			// 30 mins for cwwut
			$data = array( 
				'slvl_num' => .5
			); 
			$this->db->where('id', $id); 
			$this->db->update('tbl_slvl', $data); 
		} 
		elseif($hf == 'HF' && $explode_type[0] == 'VL')
		{
			$data = array( 
				'slvl_num' => .5
			); 
			$this->db->where('id', $id); 
			$this->db->update('tbl_slvl', $data); 
		} 
		elseif($hf == 'HFAM' && $explode_type[0] == 'VL')
		{
			$data = array( 
				'slvl_num' => .5
			); 
			$this->db->where('id', $id); 
			$this->db->update('tbl_slvl', $data); 
		} 
		elseif($hf == 'HFPM' && $explode_type[0] == 'VL')
		{
			$data = array( 
				'slvl_num' => .5
			); 
			$this->db->where('id', $id); 
			$this->db->update('tbl_slvl', $data); 
		} 
		elseif($hf == 'WD' && $explode_type[0] == 'AB' && $date != '5')
		{
			// 1 hr for cwwut
			$data = array( 
				'slvl_num' => 1
			); 
			$this->db->where('id', $id);
			$this->db->update('tbl_slvl', $data); 
		} 
		elseif($hf == 'WD' && $explode_type[0] == 'AB' && $date == '5')
		{
			$data = array( 
				'slvl_num' => 1
			); 
			$this->db->where('id', $id); 
			$this->db->update('tbl_slvl', $data); 
		} 
		elseif($hf == 'WD' && $explode_type[0] == 'VL')
		{
			$start_date = $start_date; 
	   	$end_date = $end_date;
	   	$datediff = (strtotime($end_date) - strtotime($start_date));
			$num_dates = floor($datediff / (60 * 60 * 24));
			$num_dates = $num_dates + 1;

			$data = array(
				'slvl_num' => $num_dates
			);
			$this->db->where('id', $id);
			$this->db->update('tbl_slvl', $data);
		}

		$trans = $this->db->trans_complete();

		return $trans;
	} 

	public function delete_slvl($id,$employee_number,$type)
	{
		$this->db->trans_start();

		$this->db->where('id', $id);
		$this->db->where('employee_number', $employee_number);
		$this->db->where('type', $type);
		$this->db->delete('tbl_slvl');

		$this->db->where('for_id', $id);
		$this->db->where('employee_number', $employee_number);
		$this->db->where('type', $type);
		$this->db->delete('tbl_remarks');

		$this->db->where('for_id', $id);
		$this->db->where('employee_number', $employee_number);
		$this->db->where('type', $type);
		$this->db->delete('tbl_cwwut');

		$trans = $this->db->trans_complete();

		return $trans;
	}
	
	public function get_slvl_all($start_date, $end_date)
	{
		$this->db->select("
			tbl_slvl.id as id, 
			tbl_slvl.employee_number as employee_number, 
			tbl_slvl.name as name, 
			tbl_slvl.type as type_slvl,
			tbl_slvl.type_name as type_name, 
			tbl_slvl.date as date, 
			tbl_slvl.effective_date_start as date_start, 
			tbl_slvl.effective_date_end as date_end, 
			tbl_slvl.reason as reason,
			tbl_slvl.status as status, 
			sl_am_pm as sl_am_pm,
			tbl_employees.branch_id as branch_id,
			");
		$this->db->from('tbl_slvl');
		//$this->db->group_by('name', 'ASC');
		$this->db->order_by('tbl_slvl.date', 'ASC');
		$this->db->where('tbl_slvl.date >=', $start_date);
		$this->db->where('tbl_slvl.date <=', $end_date);
		$this->db->join('tbl_employees', 'tbl_slvl.employee_number = tbl_employees.employee_number');
		$query = $this->db->get();

		return $query->result();
	}
	public function get_slvl_all1($start_date, $end_date)
	{
		$this->db->select("tbl_slvl.id as id, tbl_slvl.employee_number as employee_number, tbl_slvl.name as name, tbl_slvl.type as type,tbl_slvl.type_name as type_name, tbl_slvl.date as date, tbl_slvl.effective_date_start as date_start, tbl_slvl.effective_date_end as date_end, tbl_slvl.reason as reason,status as status, sl_am_pm as sl_am_pm, tbl_slvl.status as status");
		$this->db->from('tbl_slvl');
		$this->db->order_by('tbl_slvl.date', 'ASC');
		$this->db->where('tbl_slvl.date >=', $start_date);
		$this->db->where('tbl_slvl.date <=', $end_date);
		$this->db->where('tbl_slvl.status', 'PROCESSED');
		$query = $this->db->get();

		return $query->result();
	}

	public function get_remarks($start_date, $end_date)
	{
		$this->db->select("tbl_remarks.employee_number as remarks_employee_number, tbl_remarks.type as type, tbl_remarks.date as date, tbl_remarks.type_name as type_name");
		$this->db->from('tbl_remarks');
		$this->db->where('tbl_remarks.date >=', $start_date);
		$this->db->where('tbl_remarks.date <=', $end_date);
		$this->db->where('tbl_remarks.status', 'PROCESSED');
		$query = $this->db->get();

		return $query->result();
	}

	public function get_remark($employee_no,$start_date, $end_date)
	{
		$this->db->select("tbl_remarks.employee_number as remarks_employee_number, tbl_remarks.type as type, tbl_remarks.date as date, tbl_remarks.type_name as type_name");
		$this->db->from('tbl_remarks');
		$this->db->where('tbl_remarks.employee_number', $employee_no);
		$this->db->where('tbl_remarks.date >=', $start_date);
		$this->db->where('tbl_remarks.date <=', $end_date);
		$this->db->where('tbl_remarks.status', 'PROCESSED');
		$query = $this->db->get();
 
		return $query->result();
	}

	public function get_slvl($employee_no,$start_date,$end_date)
	{
		$this->db->select('
			tbl_slvl.id as id, tbl_slvl.employee_number as employee_number, 
			tbl_slvl.type as type, 
			tbl_slvl.effective_date_start as effective_date_start, 
			tbl_slvl.effective_date_end as effective_date_end, 
			tbl_slvl.reason as reason, tbl_slvl.status as status, 
			tbl_slvl.type_name as type_name, 
			tbl_slvl.sl_am_pm as sl_am_pm,
			tbl_slvl.slvl_num as slvl_num
			');
		$this->db->from('tbl_slvl');
		$this->db->order_by('tbl_slvl.effective_date_start', 'ASC');
		$this->db->where('tbl_slvl.employee_number', $employee_no);
		$this->db->where('tbl_slvl.effective_date_start >=', $start_date);
		$this->db->where('tbl_slvl.effective_date_start <=', $end_date);
		$query = $this->db->get();

		return $query->result();
	}

	public function get_ob($start_date, $end_date)
	{
		$this->db->select('
			tbl_ob.id as id, 
			tbl_ob.employee_number as employee_number, 
			tbl_ob.name as name, 
			tbl_ob.date_ob as date_ob, 
			tbl_ob.type as type,
			tbl_ob.site_designation_from as site_from, 
			tbl_ob.site_designation_to as site_to, 
			tbl_ob.type_ob as type_ob, 
			tbl_ob.remarks as remarks, 
			tbl_ob.time_of_departure as time_of_departure, 
			tbl_ob.time_of_return as time_of_return,
			tbl_employees.branch_id
		');
		$this->db->from('tbl_ob');
		$this->db->join('tbl_employees', 'tbl_ob.employee_number = tbl_employees.employee_number');
		$this->db->where('tbl_ob.date_ob >=', $start_date);
		$this->db->where('tbl_ob.date_ob <=', $end_date);
	  $query = $this->db->get();
	   
	  return $query->result();
	}

	public function get_ob1($employee_no,$start_date, $end_date)
	{
		$this->db->select('
			tbl_ob.id as id, 
			tbl_ob.employee_number as employee_number, 
			tbl_ob.name as name, 
			tbl_ob.date_ob as date_ob, 
			tbl_ob.site_designation_from as site_from, 
			tbl_ob.site_designation_to as site_to, 
			tbl_ob.type_ob as type_ob, 
			tbl_ob.remarks as remarks, 
			tbl_ob.time_of_departure as time_of_departure, 
			tbl_ob.time_of_return as time_of_return,
			tbl_employees.branch_id
		');
		$this->db->from('tbl_ob');
		$this->db->join('tbl_employees', 'tbl_ob.employee_number = tbl_employees.employee_number');
		$this->db->where('tbl_ob.employee_number', $employee_no);
		$this->db->where('tbl_ob.date_ob >=', $start_date);
		$this->db->where('tbl_ob.date_ob <=', $end_date);
	  $query = $this->db->get();
	   
	  return $query->result();
	}

	public function get_ob_by_id($id)
	{
		$this->db->select('tbl_ob.id as id, tbl_ob.employee_number as employee_number, tbl_ob.name as name, tbl_ob.date_ob as date_ob, tbl_ob.site_designation_from as site_from, tbl_ob.site_designation_to as site_to, tbl_ob.type_ob as type_ob, tbl_ob.remarks as remarks, tbl_ob.purpose as purpose, tbl_ob.time_of_departure as time_in, tbl_ob.time_of_return as time_out');
		$this->db->from('tbl_ob');
		$this->db->where('id', $id);
	  $query = $this->db->get();

	  return $query->row();
	}

	public function update_ob($id)
	{
		$date_ob = date('Y-m-d', strtotime($this->input->post('date')));
		$data = array(
			'employee_number'       => $this->input->post('employee_number'), 
			'name'          			  => $this->input->post('name'),
			'date_ob'        			  => $date_ob, 
			'type_ob'         			=> $this->input->post('ob_type'), 
			'site_designation_from' => $this->input->post('site_from'), 
			'site_designation_to'   => $this->input->post('site_to'), 
			'purpose'               => $this->input->post('purpose'), 
			'time_of_departure'     => $this->input->post('time_of_departure'), 
			'time_of_return'        => $this->input->post('time_of_return') 
		);

		$this->db->where('id', $id);
		$query = $this->db->update('tbl_ob', $data);


		return $query;
	}


	public function add_ob()
	{
		$this->db->trans_start();

		$date_ob = date('Y-m-d', strtotime($this->input->post('date')));
		$name = $this->input->post('name');

		$explod_name = explode("|", $name);

		$data = array(
			'employee_number'       => $explod_name[1],
			'name'          			  => $explod_name[0],
			'date_ob'        			  => $date_ob, 
			'type_ob'         			=> $this->input->post('ob_type'), 
			'site_designation_from' => $this->input->post('site_from'), 
			'site_designation_to'   => $this->input->post('site_to'), 
			'purpose'               => $this->input->post('purpose'), 
			'time_of_departure'     => $this->input->post('time_of_departure'), 
			'time_of_return'        => $this->input->post('time_of_return'),
			'remarks'               => 'FOR APPROVAL' 
		);

		$this->db->insert('tbl_ob', $data);

		$this->db->select('id');
		$this->db->order_by('id', 'DESC');
		$query=$this->db->get('tbl_ob');
		$id = $query->row()->id;

		$data = array(
			'for_id'                => $id,
			'employee_number'       => $explod_name[1],
			'name'          			  => $explod_name[0],
			'date'        			  	=> $date_ob, 
			'type'                  => 'OB',
			'type_name'             => 'OB',
			'ob_start'     					=> $this->input->post('time_of_departure'), 
			'ob_end'        				=> $this->input->post('time_of_return'),
			'status'               => 'FOR APPROVAL' 
		);

		$this->db->insert('tbl_remarks', $data);

		$trans = $this->db->trans_complete();
	
		return $trans;	
	}

	public function delete_ob($id,$employee_number,$type)
	{
		$this->db->trans_start();
		
		$this->db->where('id',$id);
		$this->db->where('employee_number', $employee_number);
		$this->db->where('type', $type);
		$this->db->delete('tbl_ob');

		$this->db->where('for_id', $id);
		$this->db->where('employee_number', $employee_number);
		$this->db->where('type', $type);
		$this->db->delete('tbl_remarks');

		$trans = $this->db->trans_complete();

		return $trans;
	} 

	public function process_time_keeping()
	{
		$this->db->trans_start();

		$name 			= $this->input->post('name');
		$dates 			= $this->input->post('dates');
		$con_dates = 	$this->input->post('con_dates');
		$intime 		= $this->input->post('intime');
		$outtime 		= $this->input->post('outtime');
		$daily_hrs 	= $this->input->post('daily_hrs');
		$hours_late = $this->input->post('hours_late');
		$undertime 	= $this->input->post('undertime');
		$ot_morning = $this->input->post('ot_morning');
		$ot_night   = $this->input->post('ot_night'); 
		$night_diff = $this->input->post('nd');
		$hfpm_dates =	$this->input->post('hfpm_dates');
		$hfam_dates =	$this->input->post('hfam_dates');
		$hfpm_time_out = $this->input->post('hfpm_time_out');
		$hfam_time_in = $this->input->post('hfam_time_in');
		$i = 0;

		foreach($this->input->post('employee_number') as $emp_no)
		{
			$data = array(
				'employee_number' => $emp_no,
				'name'            => $name[$i],
				'dates'           => $dates[$i],
				'time_in'         => $intime[$i],
				'time_out'       	=> $outtime[$i],
				'daily_hours'     => $daily_hrs[$i],
				'hours_late'      => $hours_late[$i],
				'undertime'       => $undertime[$i],
				'ot_morning'      => $ot_morning[$i],
				'ot_night'        => $ot_night[$i],
				'night_diff'      => $night_diff[$i],
				'convert_days'    => $con_dates[$i]
			);
			
			$this->db->insert('tbl_time_keeping', $data);

			$i++;
		}

		$trans = $this->db->trans_complete();

		return $trans;
	}

	public function process_employee_timekeeping()
	{
		$this->db->trans_start();

		$dates 			= $this->input->post('dates');
		$con_dates  = $this->input->post('con_dates');
		$intime 		= $this->input->post('intime');
		$outtime 		= $this->input->post('outtime');
		$daily_hrs 	= $this->input->post('daily_hrs');
		$hours_late = $this->input->post('hours_late');
		$num_late 	= $this->input->post('total_late');
		$nd         = $this->input->post('nd');
		$a = 0;

		foreach($this->input->post('employee_number') as $emp_no)
		{
			$this->db->where('employee_number', $emp_no);
			$this->db->where('dates', $dates[$a]);

			$in_att = $this->db->get('tbl_report_generation');

			if($in_att->num_rows() == 0)
			{
				$data = array(
					'employee_number'  => $emp_no,
					'dates'            => $dates[$a],
					'convert_days'     => $con_dates[$a],
					'time_in'          => $intime[$a],
					'time_out'         => $outtime[$a],
					'daily_hours'      => $daily_hrs[$a],
					'tardiness'        => $hours_late[$a],
					'num_of_tardiness' => $num_late[$a],
					'night_diff'       => $nd[$a]
	 			);

	 			$this->db->insert('tbl_report_generation', $data);
			}

 			$a++;
		}

		$trans = $this->db->trans_complete();

		return $trans;

	}
	

	public function get_hfpm($start_date, $end_date)
	{
		$this->db->select('tbl_hfpm_sl.employee_number as hfpm_employee_number, tbl_hfpm_sl.hfpm_dates as hfpm_dates, tbl_hfpm_sl.hfpm_time_out as hfpm_time_out');
		$this->db->from('tbl_hfpm_sl');
		$this->db->where('tbl_hfpm_sl.hfpm_dates >=', $start_date);
		$this->db->where('tbl_hfpm_sl.hfpm_dates <=', $end_date);
		$query = $this->db->get();

		return $query->result();
	}

	public function get_hfam($start_date, $end_date)
	{
		$this->db->select('tbl_hfam_sl.employee_number as hfam_employee_number, tbl_hfam_sl.hfam_dates as hfam_dates, tbl_hfam_sl.hfam_time_in as hfam_time_in');
		$this->db->from('tbl_hfam_sl');
		$this->db->where('tbl_hfam_sl.hfam_dates >=', $start_date);
		$this->db->where('tbl_hfam_sl.hfam_dates <=', $end_date);
		$query = $this->db->get();

		return $query->result();
	}

	public function process_ob()
	{
		$this->db->trans_start();
		$process_date = date('Y-m-d h:i:s');
		
		foreach($this->input->post('employee') as $emp)
		{
			$data_explode = explode("|", $emp);

			$id = $data_explode[0];
			$emp_num = $data_explode[1];
			$dateob = $data_explode[2];
			$weekdate = $data_explode[3];
			$type_ob = $data_explode[4];
			$time_in = $data_explode[5];
			$time_out = $data_explode[6];
			$type = $data_explode[7];

			if($type_ob == 'UD_in')
			{
				$data = array(
					'times'  => $dateob." ".$time_in,
					'status' => 'in'
				);
				$this->db->where('dates', $dateob);
				$this->db->where('employee_number', $emp_num);
				$this->db->update('tbl_in_attendance', $data);
			}

			elseif($type_ob == 'UD_out')
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
				'remarks'			 => 'PROCESSED',
				'process_date' => $process_date
			);
			$this->db->where('id', $id);
			$this->db->update('tbl_ob', $data);

			$data = array(
				'status'			 => 'PROCESSED',
				'process_date' => $process_date
			);
			$this->db->where('for_id', $id);
			$this->db->where('employee_number', $emp_num);
			$this->db->where('type', $type);
			$this->db->update('tbl_remarks', $data);
		}

		$trans = $this->db->trans_complete();
		return $trans;
	}

	public function get_summary_time()
	{
		$this->db->select("tbl_employees.emp_no as emp_no, tbl_employees.employee_number as employee_number, CONCAT(tbl_employees.last_name, ' ', tbl_employees.first_name , ' ', tbl_employees.middle_name) AS name");
		$this->db->where('is_active', '1');
		$this->db->order_by('tbl_employees.last_name', 'ASC');
		$this->db->from('tbl_employees');
		$query = $this->db->get();

		return $query->result();
	}

	public function get_nhfc_summary_time()
	{
		$this->db->select("tbl_employees.employee_number as employee_number, CONCAT(tbl_employees.last_name, ' ', tbl_employees.first_name , ' ', tbl_employees.middle_name) AS name");
		$this->db->order_by('tbl_employees.last_name', 'ASC');
		$this->db->where('company_id', 1);
		$this->db->from('tbl_employees');
		$query = $this->db->get();

		return $query->result();
	}

	public function get_gtlic_summary_time()
	{
		$this->db->select("tbl_employees.employee_number as employee_number, CONCAT(tbl_employees.last_name, ' ', tbl_employees.first_name , ' ', tbl_employees.middle_name) AS name");
		$this->db->order_by('tbl_employees.last_name', 'ASC');
		$this->db->where('company_id', 2);
		$this->db->from('tbl_employees');
		$query = $this->db->get();

		return $query->result();
	}

	public function get_regular_ot($start_date, $end_date)
	{
		$this->db->select('COUNT(*) as num_rows_ot,tbl_ot.employee_number as ot_employee_number, SUM(tbl_ot.ot_num) as total_ot, tbl_ot.ot_type as ot_type');
		$this->db->from('tbl_ot');
		$this->db->group_by('employee_number');
		$this->db->where('tbl_ot.date_ot >=', $start_date);
		$this->db->where('tbl_ot.date_ot <=', $end_date);
		$this->db->where('tbl_ot.ot_type ', 'ROT');
		$this->db->where('tbl_ot.status', 'PROCESSED');
		$query = $this->db->get();

		return $query->result();
	}

	public function get_regular_ot1($start_date, $end_date)
	{
		$this->db->select('tbl_ot.employee_number as ot_employee_number,tbl_ot.date_ot as date_ot,tbl_ot.ot_num as total_ot, tbl_ot.ot_type as ot_type');
		$this->db->from('tbl_ot');
		//$this->db->group_by('employee_number');
		$this->db->where('tbl_ot.date_ot >=', $start_date);
		$this->db->where('tbl_ot.date_ot <=', $end_date);
		$this->db->where('tbl_ot.ot_type ', 'ROT');
		$this->db->where('tbl_ot.status', 'PROCESSED');
		$query = $this->db->get();

		return $query->result();
	}

	public function get_regular_ot_emp($employee_no,$start_date, $end_date)
	{
		$this->db->select('SUM(tbl_ot.ot_num) as total_ot');
		$this->db->from('tbl_ot');
		//$this->db->group_by('employee_number');
		$this->db->where('tbl_ot.employee_number', $employee_no);
		$this->db->where('tbl_ot.date_ot >=', $start_date);
		$this->db->where('tbl_ot.date_ot <=', $end_date);
		$this->db->where('tbl_ot.ot_type ', 'ROT');
		$this->db->where('tbl_ot.status', 'PROCESSED');
		$query = $this->db->get();

		return $query->result();
	}

	public function get_legal_ot($start_date, $end_date)
	{
		$this->db->select('tbl_ot.employee_number as legal_ot_employee_number, SUM(tbl_ot.ot_num) as total_ot, tbl_ot.ot_type as ot_type');
		$this->db->from('tbl_ot');
		$this->db->group_by('employee_number');
		$this->db->where('tbl_ot.date_ot >=', $start_date);
		$this->db->where('tbl_ot.date_ot <=', $end_date);
		$this->db->where('tbl_ot.ot_type ', 'LHOT');
		$this->db->where('tbl_ot.status', 'PROCESSED');
		$query = $this->db->get();

		return $query->result();
	}

	public function get_legal_ot1($start_date, $end_date)
	{
		$this->db->select('tbl_ot.employee_number as legal_ot_employee_number, tbl_ot.ot_num as total_ot, tbl_ot.ot_type as ot_type, tbl_ot.date_ot as date_ot');
		$this->db->from('tbl_ot');
		//$this->db->group_by('employee_number');
		$this->db->where('tbl_ot.date_ot >=', $start_date);
		$this->db->where('tbl_ot.date_ot <=', $end_date);
		$this->db->where('tbl_ot.ot_type ', 'LHOT');
		$this->db->where('tbl_ot.status', 'PROCESSED');
		$query = $this->db->get();

		return $query->result();
	}

	public function get_legal_ot_emp($employee_no,$start_date, $end_date)
	{
		$this->db->select('SUM(tbl_ot.ot_num) as total_ot');
		$this->db->from('tbl_ot');
		$this->db->where('tbl_ot.employee_number', $employee_no);
		$this->db->where('tbl_ot.date_ot >=', $start_date);
		$this->db->where('tbl_ot.date_ot <=', $end_date);
		$this->db->where('tbl_ot.ot_type ', 'LHOT');
		$this->db->where('tbl_ot.status', 'PROCESSED');
		$query = $this->db->get();

		return $query->result();
	}

	public function get_special_ot($start_date, $end_date)
	{
		$this->db->select('tbl_ot.employee_number as special_ot_employee_number, SUM(tbl_ot.ot_num) as total_ot, tbl_ot.ot_type as ot_type');
		$this->db->from('tbl_ot');
		$this->db->group_by('employee_number');
		$this->db->where('tbl_ot.date_ot >=', $start_date);
		$this->db->where('tbl_ot.date_ot <=', $end_date);
		$this->db->where('tbl_ot.ot_type ', 'SHOT');
		$this->db->where('tbl_ot.status', 'PROCESSED');
		$query = $this->db->get();

		return $query->result();
	}

	public function get_special_ot1($start_date, $end_date)
	{
		$this->db->select('tbl_ot.employee_number as special_ot_employee_number, tbl_ot.ot_num as total_ot, tbl_ot.ot_type as ot_type, tbl_ot.date_ot as date_ot');
		$this->db->from('tbl_ot');
		//$this->db->group_by('employee_number');
		$this->db->where('tbl_ot.date_ot >=', $start_date);
		$this->db->where('tbl_ot.date_ot <=', $end_date);
		$this->db->where('tbl_ot.ot_type ', 'SHOT');
		$this->db->where('tbl_ot.status', 'PROCESSED');
		$query = $this->db->get();

		return $query->result();
	}

	public function get_special_ot_emp($employee_no, $start_date, $end_date)
	{
		$this->db->select('SUM(tbl_ot.ot_num) as total_ot');
		$this->db->from('tbl_ot');
		$this->db->where('tbl_ot.employee_number', $employee_no);
		$this->db->where('tbl_ot.date_ot >=', $start_date);
		$this->db->where('tbl_ot.date_ot <=', $end_date);
		$this->db->where('tbl_ot.ot_type ', 'SHOT');
		$this->db->where('tbl_ot.status', 'PROCESSED');
		$query = $this->db->get();

		return $query->result();
	}
 
	public function get_restday_ot($start_date, $end_date)
	{
		$this->db->select('tbl_ot.employee_number as restday_ot_employee_number, SUM(tbl_ot.ot_num) as total_ot, tbl_ot.ot_type as ot_type');
		$this->db->from('tbl_ot');
		$this->db->group_by('employee_number');
		$this->db->where('tbl_ot.date_ot >=', $start_date);
		$this->db->where('tbl_ot.date_ot <=', $end_date);
		$this->db->where('tbl_ot.ot_type ', 'RDOT');
		$this->db->where('tbl_ot.status', 'PROCESSED');
		$query = $this->db->get();

		return $query->result();
	}

	public function get_restday_ot1($start_date, $end_date)
	{
		$this->db->select('tbl_ot.employee_number as restday_ot_employee_number, tbl_ot.ot_num as total_ot, tbl_ot.ot_type as ot_type, tbl_ot.date_ot as date_ot');
		$this->db->from('tbl_ot');
		//$this->db->group_by('employee_number');
		$this->db->where('tbl_ot.date_ot >=', $start_date);
		$this->db->where('tbl_ot.date_ot <=', $end_date);
		$this->db->where('tbl_ot.ot_type ', 'RDOT');
		$this->db->where('tbl_ot.status', 'PROCESSED');
		$query = $this->db->get();
 
		return $query->result();
	}

	public function get_restday_ot_emp($employee_no, $start_date, $end_date)
	{
		$this->db->select('SUM(tbl_ot.ot_num) as total_ot');
		$this->db->from('tbl_ot');
		$this->db->where('tbl_ot.employee_number', $employee_no);
		$this->db->where('tbl_ot.date_ot >=', $start_date);
		$this->db->where('tbl_ot.date_ot <=', $end_date);
		$this->db->where('tbl_ot.ot_type ', 'RDOT');
		$this->db->where('tbl_ot.status', 'PROCESSED');
		$query = $this->db->get();

		return $query->result();
	}

	public function get_total_ab($start_date, $end_date)
	{
		$this->db->select('COUNT(*) as count_rows,tbl_slvl.employee_number as slvl_employee_number, SUM(tbl_slvl.slvl_num) as total_slvl, tbl_slvl.type as slvl_type');
		$this->db->from('tbl_slvl');
		$this->db->group_by('employee_number');
		$this->db->where('tbl_slvl.date >=', $start_date);
		$this->db->where('tbl_slvl.date <=', $end_date);
		$this->db->where('type', 'AB');
		$this->db->where('status', 'PROCESSED');
		$query = $this->db->get();

		return $query->result();
	}

	public function get_total_ab1($start_date, $end_date)
	{
		$this->db->select('tbl_slvl.employee_number as slvl_employee_number, tbl_slvl.slvl_num as total_slvl, tbl_slvl.type as slvl_type, tbl_slvl.date as ab_date');
		$this->db->from('tbl_slvl');
		//$this->db->group_by('employee_number');
		$this->db->where('tbl_slvl.date >=', $start_date);
		$this->db->where('tbl_slvl.date <=', $end_date);
		$this->db->where('type', 'AB');
		$this->db->where('status', 'PROCESSED');
		$query = $this->db->get();

		return $query->result();
	}

	public function get_total_ab_emp($employee_no,$start_date, $end_date)
	{
		$this->db->select('SUM(tbl_slvl.slvl_num) as total_slvl');
		$this->db->from('tbl_slvl');
		$this->db->group_by('employee_number');
		$this->db->where('tbl_slvl.employee_number', $employee_no);
		$this->db->where('tbl_slvl.date >=', $start_date);
		$this->db->where('tbl_slvl.date <=', $end_date);
		$this->db->where('type', 'AB');
		$this->db->where('status', 'PROCESSED');
		$query = $this->db->get();

		return $query->result();
	}

	public function get_total_sl($start_date, $end_date)
	{
		$this->db->select('COUNT(*) as count_rows_sl,tbl_slvl.employee_number as slvl_employee_number, SUM(tbl_slvl.slvl_num) as total_slvl, tbl_slvl.type as slvl_type');
		$this->db->from('tbl_slvl');
		$this->db->group_by('employee_number');
		$this->db->where('tbl_slvl.date >=', $start_date);
		$this->db->where('tbl_slvl.date <=', $end_date);
		$this->db->where('type', 'SL');
		$this->db->where('status', 'PROCESSED');
		$query = $this->db->get();

		return $query->result();
	}

	public function get_total_sl1($start_date, $end_date)
	{
		$this->db->select('tbl_slvl.employee_number as slvl_employee_number, tbl_slvl.slvl_num as total_slvl, tbl_slvl.type as slvl_type, tbl_slvl.date as sl_date');
		$this->db->from('tbl_slvl');
		//$this->db->group_by('employee_number');
		$this->db->where('tbl_slvl.date >=', $start_date);
		$this->db->where('tbl_slvl.date <=', $end_date);
		$this->db->where('tbl_slvl.type', 'SL');
		$this->db->where('status', 'PROCESSED');
		$query = $this->db->get();

		return $query->result();
	}

	public function get_total_sl_emp($employee_no,$start_date, $end_date)
	{
		$this->db->select('SUM(tbl_slvl.slvl_num) as total_slvl');
		$this->db->from('tbl_slvl');
		$this->db->group_by('employee_number');
		$this->db->where('tbl_slvl.employee_number', $employee_no);
		$this->db->where('tbl_slvl.date >=', $start_date);
		$this->db->where('tbl_slvl.date <=', $end_date);
		$this->db->where('type', 'SL');
		$this->db->where('status', 'PROCESSED');
		$query = $this->db->get();

		return $query->result();
	}

	public function get_total_vl($start_date, $end_date)
	{
		$this->db->select('COUNT(*) as count_rows_vl,tbl_slvl.employee_number as slvl_employee_number, SUM(tbl_slvl.slvl_num) as total_slvl, tbl_slvl.type as slvl_type');
		$this->db->from('tbl_slvl');
		$this->db->group_by('employee_number');
		$this->db->where('tbl_slvl.date >=', $start_date);
		$this->db->where('tbl_slvl.date <=', $end_date);
		$this->db->where('type', 'VL');
		$this->db->where('status', 'PROCESSED');	
		$query = $this->db->get();

		return $query->result();
	}

	public function get_total_vl1($start_date, $end_date)
	{
		$this->db->select('tbl_slvl.employee_number as slvl_employee_number, tbl_slvl.slvl_num as total_slvl, tbl_slvl.type as slvl_type, tbl_slvl.date as vl_date');
		$this->db->from('tbl_slvl');
		//$this->db->group_by('employee_number');
		$this->db->where('tbl_slvl.date >=', $start_date);
		$this->db->where('tbl_slvl.date <=', $end_date);
		$this->db->where('type', 'VL');
		$this->db->where('status', 'PROCESSED');	
		$query = $this->db->get();

		return $query->result();
	}

	public function get_total_vl_emp($employee_no,$start_date, $end_date)
	{
		$this->db->select('SUM(tbl_slvl.slvl_num) as total_slvl');
		$this->db->from('tbl_slvl');
		$this->db->where('tbl_slvl.employee_number', $employee_no);
		$this->db->where('tbl_slvl.date >=', $start_date);
		$this->db->where('tbl_slvl.date <=', $end_date);
		$this->db->where('type', 'VL');
		$this->db->where('status', 'PROCESSED');	
		$query = $this->db->get();

		return $query->result();
	}

	public function get_total_sh_emp($employee_no,$start_date, $end_date)
	{
		$this->db->select('SUM(tbl_slvl.slvl_num) as total_slvl');
		$this->db->from('tbl_slvl');
		$this->db->where('tbl_slvl.employee_number', $employee_no);
		$this->db->where('tbl_slvl.date >=', $start_date);
		$this->db->where('tbl_slvl.date <=', $end_date);
		$this->db->where('type', 'SH');
		$this->db->where('status', 'PROCESSED');	
		$query = $this->db->get();

		return $query->result();
	}

	public function get_total_slvl($start_date, $end_date)
	{
		$this->db->select('tbl_slvl.employee_number as sslvl_employee_number, SUM(tbl_slvl.slvl_num) as tot_total_slvl, tbl_slvl.type as slvl_type');
		$this->db->from('tbl_slvl');
		$this->db->group_by('employee_number');
		$this->db->where('tbl_slvl.date >=', $start_date);
		$this->db->where('tbl_slvl.date <=', $end_date);
		$query = $this->db->get();

		return $query->result();
	}

	public function get_total_tardiness($start_date, $end_date)
	{
		$this->db->select('tbl_time_keeping.employee_number as tard_employee_number, SUM(tbl_time_keeping.hours_late) as total_tardiness');
		$this->db->from('tbl_time_keeping');
		$this->db->group_by('employee_number');
		$this->db->where('tbl_time_keeping.dates >=', $start_date);
		$this->db->where('tbl_time_keeping.dates <=', $end_date);
		$query = $this->db->get();

		return $query->result();
	}

	public function get_total_undertime($start_date, $end_date)
	{
		$this->db->select('tbl_time_keeping.employee_number as undertime_employee_number, SUM(tbl_time_keeping.undertime) as total_undertime');
		$this->db->from('tbl_time_keeping');
		$this->db->group_by('employee_number');
		$this->db->where('tbl_time_keeping.dates >=', $start_date);
		$this->db->where('tbl_time_keeping.dates <=', $end_date);
		$query = $this->db->get();

		return $query->result();
	}

	public function get_total_night_diff($start_date, $end_date)
	{
		$this->db->select('tbl_time_keeping.employee_number as nightdiff_employee_number, SUM(tbl_time_keeping.night_diff) as total_nightdiff');
		$this->db->from('tbl_time_keeping');
		$this->db->group_by('employee_number');
		$this->db->where('tbl_time_keeping.dates >=', $start_date);
		$this->db->where('tbl_time_keeping.dates <=', $end_date);
		$query = $this->db->get();

		return $query->result();
	}

	public function get_total_day($start_date, $end_date)
	{
		$this->db->select('tbl_time_keeping.employee_number as get_total_employee_number, count(tbl_time_keeping.name) as total_employee_days');
		$this->db->from('tbl_time_keeping');
		$this->db->group_by('employee_number');
		$this->db->where('tbl_time_keeping.convert_days !=', 6);
		$this->db->where('tbl_time_keeping.dates >=', $start_date);
		$this->db->where('tbl_time_keeping.dates <=', $end_date);
		$query = $this->db->get();

		return $query->result();
	}

	public function get_total_dailyhrs($start_date, $end_date)
	{
		$this->db->select('tbl_time_keeping.employee_number as get_totaldailyhrs_employee_number, SUM(tbl_time_keeping.daily_hours) as total_daily_hrs');
		$this->db->from('tbl_time_keeping');
		$this->db->group_by('employee_number');
		$this->db->where('tbl_time_keeping.dates >=', $start_date);
		$this->db->where('tbl_time_keeping.dates <=', $end_date);
		$query = $this->db->get();

		return $query->result();
	} 

	public function get_employees()
	{
		$this->db->select("CONCAT(tbl_employees.last_name, ', ', tbl_employees.first_name , ' ', tbl_employees.middle_name) AS name,tbl_employees.id as id, tbl_employees.employee_number as employee_number");
		$this->db->order_by('tbl_employees.last_name', 'ASC');
		$this->db->where('is_active', '1');
		$this->db->from('tbl_employees');
		$query = $this->db->get();

		return $query->result();
	}

	public function get_user()
	{
		$this->db->select("CONCAT(tbl_employees.last_name, ', ', tbl_employees.first_name , ' ', tbl_employees.middle_name) AS name,tbl_employees.id as id, tbl_employees.employee_number as employee_number, tbl_employees.emp_no as emp_no");
		$this->db->where('is_active', '1');
		$this->db->where('tbl_employees.employee_number', $this->session->userdata('emp_no_id'));
		$this->db->from('tbl_employees');
		$query = $this->db->get();

		return $query->row();
	}

	public function delete_all_data($start_date, $end_date)
	{
		$this->db->where('tbl_time_keeping.dates >=', $start_date);
		$this->db->where('tbl_time_keeping.dates <=', $end_date);
		$query = $this->db->delete('tbl_time_keeping');

		return $query;
	}

	public function adjustment() 
	{
		$name = $this->input->post('name');
		$date = $this->input->post('date');
		$time_in = $this->input->post('timein');
		$time_out = $this->input->post('timeout');
		$type_in_out = $this->input->post('in_out_type');
		$type = $this->input->post('type');
		$branch_id = $this->input->post('branch_id');

		$explod_name = explode("|", $name);

		$this->db->trans_start();

		$data = array(
			'employee_number' => $explod_name[1],
			'name'            => $explod_name[0],
			'type'            => $type,
			'type_in_out'     => $type_in_out,
			'date'            => $date,
			'time_in'         => $time_in,
			'time_out'        => $time_out,
			'branch_id'       => $branch_id,
			'encode_by'       => $this->session->userdata('username'),
			'encode_date'     => date('Y-m-d h:i:s'),
			'remarks'         => $this->input->post('remarks')
		);

		$this->db->insert('tbl_adjustment_time', $data);

		$this->db->select('id, employee_number, type, type_in_out, date, time_in, time_out, branch_id');
		$this->db->order_by('id', 'DESC');
		$query=$this->db->get('tbl_adjustment_time');
		$id = $query->row()->id;
		$employee_number1 = $query->row()->employee_number;
		$type1 = $query->row()->type;
		$type_in_out1 = $query->row()->type_in_out;
		$date1 = $query->row()->date;
		$time1 = $query->row()->time_in; 
		$time2 = $query->row()->time_out; 
		$branch_id1 =$query->row()->branch_id;

		if($type1 == 'ONE' && $type_in_out1 == 'out')
		{  
			$data = array(
				'times' => $date1." ".$time2,
				'status' => $type_in_out1
			);

			$this->db->where('employee_number', $employee_number1);
			$this->db->where('dates', $date1);
			$this->db->where('branch_id', $branch_id1);

			$this->db->update('tbl_out_attendance', $data);
		}
		elseif($type1 == 'ONE' && $type_in_out1 == 'in')
		{  
			$data = array(
				'times' => $date1." ".$time1,
				'status' => $type_in_out1
			);

			$this->db->where('employee_number', $employee_number1);
			$this->db->where('dates', $date1);
			$this->db->where('branch_id', $branch_id1);

			$this->db->update('tbl_in_attendance', $data);
		}
		elseif($type1 == 'ONE' && $type_in_out1 == 'all')
		{  
			//in
			$data = array(
				'times' => $date1." ".$time1,
				'status' => 'in'
			);

			$this->db->where('employee_number', $employee_number1);
			$this->db->where('dates', $date1);
			$this->db->where('branch_id', $branch_id1);

			$this->db->update('tbl_in_attendance', $data);

			//out
			$data = array(
				'times' => $date1." ".$time2,
				'status' => 'out'
			);

			$this->db->where('employee_number', $employee_number1);
			$this->db->where('dates', $date1);
			$this->db->where('branch_id', $branch_id1);

			$this->db->update('tbl_out_attendance', $data);
		}
		elseif($type1 == 'ALL' && $type_in_out1 == 'in' )
		{
			$data = array(
				'times' => $date1." ".$time1
			);

			$this->db->where('dates', $date1);
			$this->db->where('status', $type_in_out1);
			$this->db->where('branch_id', $branch_id1);

			$this->db->update('tbl_in_attendance', $data);
		}
		elseif($type1 == 'ALL' && $type_in_out1 == 'out')
		{
			$data = array(
				'times' => $date1." ".$time2
			);

			$this->db->where('dates', $date1);
			$this->db->where('status', $type_in_out1);
			$this->db->where('branch_id', $branch_id1);

			$this->db->update('tbl_out_attendance', $data);
		}
		elseif($type1 == 'ALL' && $type_in_out1 == 'all')
		{
			//in
			$data = array(
				'times' => $date1." ".$time1
			);

			$this->db->where('dates', $date1);
			$this->db->where('status', 'in');
			$this->db->where('branch_id', $branch_id1);

			$this->db->update('tbl_in_attendance', $data);

			//out
			$data = array(
				'times' => $date1." ".$time2
			);

			$this->db->where('dates', $date1);
			$this->db->where('status', 'out');
			$this->db->where('branch_id', $branch_id1);

			$this->db->update('tbl_out_attendance', $data);
		}

		$trans = $this->db->trans_complete();

		return $trans;
	}

	public function adjustment_totals()
	{
		$this->db->trans_start();
		$name 				= $this->input->post('name');
		$emp_no 		 	= $this->input->post('employee_number');
		$date 			  = $this->input->post('date');
		$adjust_type 	= $this->input->post('adjust_type');
		$adjustment  	= $this->input->post('adjustment');
		$remarks      = $this->input->post('remarks');

		$adjust_name = explode("|", $adjust_type);

		if($adjust_name[0] == 'ROT')
		{
			$data = array(
				'employee_number' => $emp_no,
				'name'            => $name,
				'date_ot'         => $date,
				'ot_type'         => $adjust_name[0],
				'ot_type_name'    => $adjust_name[1],
				'ot_num'          => $adjustment,
				'nature_of_work'  => $remarks,
				'process_by'    	=> $this->session->userdata('username'),
				'process_date'    => date('Y-m-d h:i:s'),
				'status'          => 'PROCESSED' 
 			);

 			$this->db->insert('tbl_ot', $data);
		}
		elseif($adjust_name[0] == 'NDT')
		{
			$data = array(
				'employee_number' => $emp_no,
				'name'            => $name,
				'dates'           => $date,
				'night_diff'      => $adjustment
			);
			
			$this->db->insert('tbl_time_keeping', $data);
		}

		elseif($adjust_name[0] == 'LHOT')
		{
			$data = array(
				'employee_number' => $emp_no,
				'name'            => $name,
				'date_ot'         => $date,
				'ot_type'         => $adjust_name[0],
				'ot_type_name'    => $adjust_name[1],
				'ot_num'          => $adjustment,
				'nature_of_work'  => $remarks,
				'process_by'    	=> $this->session->userdata('username'),
				'process_date'    => date('Y-m-d h:i:s'),
				'status'          => 'PROCESSED' 
 			);

 			$this->db->insert('tbl_ot', $data);
		}

		elseif($adjust_name[0] == 'SHOT')
		{
			$data = array(
				'employee_number' => $emp_no,
				'name'            => $name,
				'date_ot'         => $date,
				'ot_type'         => $adjust_name[0],
				'ot_type_name'    => $adjust_name[1],
				'ot_num'          => $adjustment,
				'nature_of_work'  => $remarks,
				'process_by'    	=> $this->session->userdata('username'),
				'process_date'    => date('Y-m-d h:i:s'),
				'status'          => 'PROCESSED' 
 			);

 			$this->db->insert('tbl_ot', $data);
		}

		elseif($adjust_name[0] == 'RDOT')
		{
			$data = array(
				'employee_number' => $emp_no,
				'name'            => $name,
				'date_ot'         => $date,
				'ot_type'         => $adjust_name[0],
				'ot_type_name'    => $adjust_name[1],
				'ot_num'          => $adjustment,
				'nature_of_work'  => $remarks,
				'process_by'    	=> $this->session->userdata('username'),
				'process_date'    => date('Y-m-d h:i:s'),
				'status'          => 'PROCESSED' 
 			);

 			$this->db->insert('tbl_ot', $data);
		}

		elseif($adjust_name[0] == 'TARD')
		{
			$data = array(
				'employee_number' => $emp_no,
				'name'            => $name,
				'dates'           => $date,
				'hours_late'      => $adjustment
			);
			
			$this->db->insert('tbl_time_keeping', $data);
		}

		elseif($adjust_name[0] == 'UNDER')
		{
			$data = array(
				'employee_number' => $emp_no,
				'name'            => $name,
				'dates'           => $date,
				'undertime'       => $adjustment
			);
			
			$this->db->insert('tbl_time_keeping', $data);
		}

		elseif($adjust_name[0] == 'AB')
		{
			$data = array(
				'employee_number' => $emp_no,
				'name'            => $name,
				'date'         		=> $date,
				'type'         		=> $adjust_name[0],
				'type_name'    		=> $adjust_name[1],
				'slvl_num'        => $adjustment,
				'process_by'    	=> $this->session->userdata('username'),
				'process_date'    => date('Y-m-d h:i:s'),
				'status'          => 'PROCESSED' 
 			);

 			$this->db->insert('tbl_slvl', $data);
		}

		elseif($adjust_name[0] == 'SL')
		{
			$data = array(
				'employee_number' => $emp_no,
				'name'            => $name,
				'date'         		=> $date,
				'type'         		=> $adjust_name[0],
				'type_name'    		=> $adjust_name[1],
				'slvl_num'        => $adjustment,
				'process_by'    	=> $this->session->userdata('username'),
				'process_date'    => date('Y-m-d h:i:s'),
				'status'          => 'PROCESSED' 
 			);

 			$this->db->insert('tbl_slvl', $data);
		}

		elseif($adjust_name[0] == 'VL')
		{
			$data = array(
				'employee_number' => $emp_no,
				'name'            => $name,
				'date'         		=> $date,
				'type'         		=> $adjust_name[0],
				'type_name'    		=> $adjust_name[1],
				'slvl_num'        => $adjustment,
				'process_by'    	=> $this->session->userdata('username'),
				'process_date'    => date('Y-m-d h:i:s'),
				'status'          => 'PROCESSED' 
 			);

 			$this->db->insert('tbl_slvl', $data);
		}

		$data = array(
			'employee_number' => $emp_no,
			'name'						=> $name,
			'date'            => date('Y-m-d h:i:s'),
			'type'            => $adjust_name[1],
			'adjustment'     	=> $adjustment,
			'remarks'         => $remarks,
			'encode_by'       => $this->session->userdata('username'),
			'encode_date'     => date('Y-m-d h:i:s')
		);

		$this->db->insert('tbl_adjustment', $data);

		$trans = $this->db->trans_complete();

		return $trans;
	}

	public function get_cwwut($employee_no,$start_date,$end_date)
	{
		$this->db->select('SUM(tbl_cwwut.undertime_hr) as undertime_hr');
		$this->db->from('tbl_cwwut');
		$this->db->where('tbl_cwwut.employee_number', $employee_no);
		$this->db->where('tbl_cwwut.date >=', $start_date);
		$this->db->where('tbl_cwwut.date <=', $end_date);
		$this->db->where('tbl_cwwut.status', 'PROCESSED');
		$query = $this->db->get();

		return $query->result();
	}

	public function get_total_cwwut($start_date,$end_date)
	{
		$this->db->select('tbl_cwwut.employee_number as cwwut_employee_number, SUM(tbl_cwwut.undertime_hr) as undertime_hr');
		$this->db->from('tbl_cwwut');
		$this->db->group_by('cwwut_employee_number');
		$this->db->where('tbl_cwwut.date >=', $start_date);
		$this->db->where('tbl_cwwut.date <=', $end_date);
		$this->db->where('tbl_cwwut.status', 'PROCESSED');
		$query = $this->db->get();

		return $query->result();
	}

	public function add_undertime()
	{
		$this->db->trans_start();

		$name = $this->input->post('name');
		$date_ut = $this->input->post('date_ut');
		$time_out = $this->input->post('time_out');
		$reason = $this->input->post('reason');

		$explode_name = explode("|", $name);

		$data = array(
			'employee_number' => $explode_name[1],
			'name'            => $explode_name[0],
			'date_ut'         => $date_ut,
			'time_out'        => $time_out,
			'reason'          => $reason,
			'encode_by'				=> $this->session->userdata('username'),
			'status'          => 'FOR APPROVAL',
			'encode_date'     => date('Y-m-d')
		);

		$this->db->insert('tbl_undertime', $data);

		$this->db->select('id, employee_number, name, date_ut, time_out');
		$this->db->order_by('id', 'DESC');
		$query = $this->db->get('tbl_undertime');	

		$id = $query->row()->id;
		$employee_number = $query->row()->employee_number;
		$name = $query->row()->name;
		$date_ut = $query->row()->date_ut;
		$time_out = $query->row()->time_out;
		$w_date = date('w', strtotime($date_ut));

		$data = array(
			'for_id'  				=> $id,
			'employee_number' => $employee_number,
			'name'            => $name,
			'type'						=> 'UT',
			'type_name'       => 'UNDERTIME',
			'date'            => $date_ut,
			'status'          => 'FOR APPROVAL'
		);

		$this->db->insert('tbl_remarks', $data);

		if($w_date != 5)
		{
			$mon_thru = 1050;
			$explode_time = explode(':', $time_out);
			$hr = $explode_time[0] * 60;
			$hr_mins = $hr + $explode_time[1];
			$total_ut = $mon_thru - $hr_mins;

			$data = array(
				'ut_no' => $total_ut
			);

			$this->db->where('id', $id);
			$this->db->update('tbl_undertime', $data);
		}
		elseif($w_date == 5)
		{
			$fri = 990;
			$explode_time = explode(':', $time_out);
			$hr = $explode_time[0] * 60;
			$hr_mins = $hr + $explode_time[1];
			$total_ut = $fri - $hr_mins;

			$data = array(
				'ut_no' => $total_ut
			);

			$this->db->where('id', $id);
			$this->db->update('tbl_undertime', $data);
		}

		$trans = $this->db->trans_complete();

		return $trans;
	}

	public function get_undertime($start_date, $end_date)
	{
		$this->db->select('tbl_undertime.id as id,tbl_undertime.employee_number as employee_number, tbl_undertime.name as name, tbl_undertime.date_ut as date_ut, tbl_undertime.ut_no as ut_no, tbl_undertime.time_out as time_out, tbl_undertime.reason as reason, tbl_undertime.status as status, tbl_out_attendance.times as emp_time_out, tbl_out_attendance.dates as date');
		$this->db->from('tbl_undertime');
		$this->db->join('tbl_out_attendance', 'tbl_out_attendance.employee_number = tbl_undertime.employee_number AND tbl_out_attendance.dates = tbl_undertime.date_ut','left');
		$this->db->where('date_ut >=', $start_date);
		$this->db->where('date_ut <=', $end_date);

		$query = $this->db->get();

		return $query->result();
	}

	public function get_total_undertimes($start_date, $end_date)
	{
		$this->db->select('COUNT(*) as num_rows_ut,tbl_undertime.employee_number as undertime_employee_number, SUM(tbl_undertime.ut_no) as total_undertime');
		$this->db->from('tbl_undertime');
		$this->db->group_by('employee_number');
		$this->db->where('tbl_undertime.status', 'PROCESSED');
		$this->db->where('tbl_undertime.date_ut >=', $start_date);
		$this->db->where('tbl_undertime.date_ut <=', $end_date);

		$query = $this->db->get();

		return $query->result();
	}

	public function get_emp_undertime($id)
	{
		$this->db->select('id, employee_number, name, date_ut, ut_no,time_out, reason, status');
		$this->db->where('id', $id);
		$this->db->from('tbl_undertime');

		$query = $this->db->get();

		return $query->row();
	}

	public function update_undertime($id)
	{
		$this->db->trans_start();

		$name = $this->input->post('name');
		$date_ut = $this->input->post('date_ut');
		$time_out = $this->input->post('time_out');
		$reason = $this->input->post('reason');
		$w_date = date('w', strtotime($date_ut));

		$explode_name = explode("|", $name);

		$data = array(
			'employee_number' => $explode_name[1],
			'name'            => $explode_name[0],
			'date_ut'         => $date_ut,
			'time_out'        => $time_out,
			'reason'          => $reason,
			'encode_by'				=> $this->session->userdata('username'),
			'status'          => 'FOR APPROVAL',
			'encode_date'     => date('Y-m-d')
		);

		$this->db->where('id', $id);
		$this->db->update('tbl_undertime', $data);

		$data = array(
			'employee_number' => $explode_name[1],
			'name'            => $explode_name[0],
			'date'            => $date_ut,
		);

		$this->db->where('for_id', $id);
		$this->db->update('tbl_remarks', $data);

		if($w_date == 5)
		{
			$fri = 990;
			$explode_time = explode(':', $time_out);
			$hr = $explode_time[0] * 60;
			$hr_mins = $hr + $explode_time[1];
			$total_ut = $fri - $hr_mins;

			$data = array(
				'ut_no' => $total_ut
			);

			$this->db->where('id', $id);
			$this->db->update('tbl_undertime', $data);

			
		}
		else
		{
			$mon_thru = 1050;
			$explode_time = explode(':', $time_out);
			$hr = $explode_time[0] * 60;
			$hr_mins = $hr + $explode_time[1];
			$total_ut = $mon_thru - $hr_mins;

			$data = array(
				'ut_no' => $total_ut
			);

			$this->db->where('id', $id);
			$this->db->update('tbl_undertime', $data);
		}

		$trans = $this->db->trans_complete();

		return $trans;
	}

	public function delete_undertime($id)
	{
		$this->db->trans_start();

		$this->db->where('id', $id);
		$this->db->delete('tbl_undertime');

		$this->db->where('for_id', $id);
		$this->db->delete('tbl_remarks');

		$trans = $this->db->trans_complete();
		return $trans;
	}

	public function get_undertime_emp($employee_no, $start_date, $end_date)
	{
		$this->db->select('tbl_undertime.id as id,tbl_undertime.employee_number as employee_number, tbl_undertime.name as name, tbl_undertime.date_ut as date_ut, tbl_undertime.ut_no as ut_no, tbl_undertime.time_out as time_out, tbl_undertime.reason as reason, tbl_undertime.status as status, tbl_out_attendance.times as emp_time_out, tbl_out_attendance.dates as date');
		$this->db->from('tbl_undertime');
		$this->db->join('tbl_out_attendance', 'tbl_out_attendance.employee_number = tbl_undertime.employee_number AND tbl_out_attendance.dates = tbl_undertime.date_ut','left');
		$this->db->where('tbl_undertime.employee_number', $employee_no);
		$this->db->where('tbl_undertime.date_ut >=', $start_date);
		$this->db->where('tbl_undertime.date_ut <=', $end_date);
		
		$query = $this->db->get();

		return $query->result();
	}}	