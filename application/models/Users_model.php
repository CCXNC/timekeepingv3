<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users_model extends CI_Model {
 

	public function get_slvl_emp($start_date,$end_date)
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
		$this->db->where('tbl_slvl.date >=', $start_date);
		$this->db->where('tbl_slvl.date <=', $end_date);
		$this->db->where('tbl_slvl.employee_number', $this->session->userdata('emp_no_id'));
		$query = $this->db->get();

		return $query->result();
	}
	public function get_emp_leave_credits()
	{
		$this->db->select('
			leave_credits.sl_credit as sl_credit,
			leave_credits.vl_credit as vl_credit,
			leave_credits.elcl_credit as elcl_credit,
			leave_credits.fl_credit as fl_credit
		');

		$this->db->from('leave_credits');
		$this->db->where('leave_credits.employee_number', $this->session->userdata('emp_no_id'));
		$query = $this->db->get();

		return $query->row();
	}

	public function get_ob_emp($start_date,$end_date)
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
			tbl_ob.time_of_return as time_of_return          
		');                                       
		$this->db->from('tbl_ob');
		$this->db->order_by('date_ob', 'ASC');
		$this->db->where('tbl_ob.date_ob >=', $start_date);
		$this->db->where('tbl_ob.date_ob <=', $end_date);
		$this->db->where('tbl_ob.employee_number', $this->session->userdata('emp_no_id'));
	  $query = $this->db->get();
	   
	  return $query->result();
	}

	public function get_ot_emp($start_date,$end_date)
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
			tbl_ot.status as status,
		');
		$this->db->from('tbl_ot');
		$this->db->join('tbl_in_attendance', 'tbl_in_attendance.employee_number = tbl_ot.employee_number AND tbl_in_attendance.dates = tbl_ot.date_ot','left');
		$this->db->join('tbl_out_attendance', 'tbl_out_attendance.employee_number = tbl_ot.employee_number AND tbl_out_attendance.dates = tbl_ot.date_ot','left');
		$this->db->order_by('tbl_ot.date_ot', 'ASC');
		$this->db->where('tbl_ot.date_ot >=', $start_date);
		$this->db->where('tbl_ot.date_ot <=', $end_date);
		$this->db->where('tbl_ot.employee_number', $this->session->userdata('emp_no_id'));
		$query = $this->db->get();

		return $query->result();
	}

	public function get_undertime_emp($start_date,$end_date)
	{
		$this->db->select('
			tbl_undertime.id as id,
			tbl_undertime.employee_number as employee_number, 
			tbl_undertime.name as name, 
			tbl_undertime.type as type,
			tbl_undertime.date_ut as date_ut, 
			tbl_undertime.ut_no as ut_no, 
			tbl_undertime.time_out as time_out, 
			tbl_undertime.reason as reason, 
			tbl_undertime.status as status, 
			tbl_out_attendance.times as emp_time_out, 
			tbl_out_attendance.dates as date
		');
		$this->db->from('tbl_undertime');
		$this->db->join('tbl_out_attendance', 'tbl_out_attendance.employee_number = tbl_undertime.employee_number AND tbl_out_attendance.dates = tbl_undertime.date_ut','left');
		$this->db->order_by('tbl_undertime.date_ut', 'ASC');
		$this->db->where('tbl_undertime.date_ut >=', $start_date);
		$this->db->where('tbl_undertime.date_ut <=', $end_date);
		$this->db->where('tbl_undertime.employee_number', $this->session->userdata('emp_no_id'));
		$query = $this->db->get();

		return $query->result();
	}

	public function get_slvl_all_by_department_id($start_date, $end_date, $department_id)
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
			tbl_employees.department_id as department_id,
			admin1.department_id as dept_id,
			admin1.branch_id as brnch_id,
			admin1.is_hr as is_hr,
			tbl_slvl.slvl_num as slvl_num,
			leave_credits.sl_credit as sl_credit,
			leave_credits.vl_credit as vl_credit,
			leave_credits.elcl_credit as elcl_credit,
			leave_credits.fl_credit	as fl_credit
			");
		$this->db->from('tbl_slvl');
		$this->db->join('tbl_employees', 'tbl_slvl.employee_number = tbl_employees.employee_number');
		$this->db->join('admin1', 'tbl_slvl.employee_number = admin1.emp_no_id', 'left');
		$this->db->join('leave_credits', 'tbl_slvl.employee_number = leave_credits.employee_number', 'left');
		$this->db->order_by('tbl_slvl.date', 'ASC');
		$this->db->order_by('tbl_slvl.name', 'ASC');
		$this->db->where('tbl_slvl.date >=', $start_date);
		$this->db->where('tbl_slvl.date <=', $end_date);
		$this->db->where('admin1.department_id ', $department_id);
		$this->db->where('tbl_slvl.status ', 'Recommending for Approval');
		
		$query = $this->db->get();

		return $query->result();
	}

	public function get_ut_all_by_department_id($start_date, $end_date, $department_id)
	{
		$this->db->select('
			tbl_undertime.id as id,
			tbl_undertime.employee_number as employee_number, 
			tbl_undertime.name as name, 
			tbl_undertime.type as type,
			tbl_undertime.date_ut as date_ut, 
			tbl_undertime.ut_no as ut_no, 
			tbl_undertime.time_out as time_out, 
			tbl_undertime.reason as reason, 
			tbl_undertime.status as status, 
			tbl_out_attendance.times as emp_time_out, 
			tbl_out_attendance.dates as date,
			admin1.department_id as dept_id,
			admin1.branch_id as brnch_id,
			admin1.is_hr as is_hr
		');
		$this->db->from('tbl_undertime');
		$this->db->join('tbl_out_attendance', 'tbl_out_attendance.employee_number = tbl_undertime.employee_number AND tbl_out_attendance.dates = tbl_undertime.date_ut','left');
		$this->db->join('admin1', 'tbl_undertime.employee_number = admin1.emp_no_id', 'left');
		$this->db->order_by('tbl_undertime.date_ut', 'ASC');
		$this->db->order_by('tbl_undertime.name', 'ASC');
		$this->db->where('tbl_undertime.date_ut >=', $start_date);
		$this->db->where('tbl_undertime.date_ut <=', $end_date);
		$this->db->where('admin1.department_id ', $department_id);
		$this->db->where('tbl_undertime.status ', 'Recommending for Approval');
		
		$query = $this->db->get();

		foreach($query->result() as $ut)
		{
			$ut->is_correct = 1;
		}

		return $query->result();
	}

	public function get_ob_all_by_department_id($start_date, $end_date, $department_id)
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
			tbl_employees.branch_id as branch_id,
			tbl_employees.department_id as department_id,
			admin1.department_id as dept_id,
			admin1.branch_id as brnch_id,
			admin1.is_hr as is_hr
		');
		$this->db->from('tbl_ob');
		$this->db->join('tbl_employees', 'tbl_ob.employee_number = tbl_employees.employee_number');
		$this->db->join('admin1', 'tbl_ob.employee_number = admin1.emp_no_id', 'left');
		$this->db->order_by('tbl_ob.date_ob', 'ASC');
		$this->db->order_by('tbl_ob.name', 'ASC');
		$this->db->where('tbl_ob.date_ob >=', $start_date);
		$this->db->where('tbl_ob.date_ob <=', $end_date);
		$this->db->where('admin1.department_id ', $department_id);
		$this->db->where('tbl_ob.remarks ', 'Recommending for Approval');
		
	  $query = $this->db->get();
	   
	  return $query->result();
	}

	public function get_ot_all_by_department_id($start_date, $end_date, $department_id)
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
			tbl_employees.branch_id as branch_id,
			tbl_employees.department_id as department_id,
			admin1.department_id as dept_id,
			admin1.branch_id as brnch_id,
			admin1.is_hr as is_hr
		');

		$this->db->from('tbl_ot');
		$this->db->join('tbl_in_attendance', 'tbl_in_attendance.employee_number = tbl_ot.employee_number AND tbl_in_attendance.dates = tbl_ot.date_ot','left');
		$this->db->join('tbl_out_attendance', 'tbl_out_attendance.employee_number = tbl_ot.employee_number AND tbl_out_attendance.dates = tbl_ot.date_ot','left');
		$this->db->join('tbl_employees', 'tbl_ot.employee_number = tbl_employees.employee_number', 'left');
		$this->db->join('admin1', 'tbl_ot.employee_number = admin1.emp_no_id', 'left');
		$this->db->order_by('tbl_ot.date_ot', 'ASC');
		$this->db->order_by('tbl_ot.name', 'ASC');
		$this->db->where('tbl_ot.date_ot >=', $start_date);
		$this->db->where('tbl_ot.date_ot <=', $end_date);
		$this->db->where('admin1.department_id ', $department_id);
		$this->db->where('tbl_ot.status ', 'Recommending for Approval');
		
		$query = $this->db->get();

		foreach($query->result() as $ot)
		{
			$ot->red_mark_alert = 0 ;
		}

		return $query->result();
	}

	public function get_slvl_all_by_oic($start_date, $end_date, $branch_id)
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
			tbl_employees.department_id as department_id, 
			admin1.department_id as dept_id, 
			admin1.branch_id as brnch_id, 
			admin1.is_hr as is_hr,
			tbl_slvl.slvl_num as slvl_num,
			leave_credits.sl_credit as sl_credit,
			leave_credits.vl_credit as vl_credit,
			leave_credits.elcl_credit as elcl_credit,
			leave_credits.fl_credit	as fl_credit
		");
		$this->db->from('tbl_slvl');
		$this->db->join('tbl_employees', 'tbl_slvl.employee_number = tbl_employees.employee_number');
		$this->db->join('admin1', 'tbl_slvl.employee_number = admin1.emp_no_id', 'left');
		$this->db->join('leave_credits', 'tbl_slvl.employee_number = leave_credits.employee_number', 'left');
		$this->db->order_by('tbl_slvl.date', 'ASC');
		$this->db->order_by('tbl_slvl.name', 'ASC');
		$this->db->where('tbl_slvl.date >=', $start_date);
		$this->db->where('tbl_slvl.date <=', $end_date);
		$this->db->where('admin1.branch_id ', $branch_id);
		$this->db->where('tbl_slvl.status ', 'Recommending for Approval');
		
		$query = $this->db->get();

		return $query->result();
	}

	public function get_ut_all_by_oic($start_date, $end_date, $branch_id)
	{
		$this->db->select('
			tbl_undertime.id as id,
			tbl_undertime.employee_number as employee_number, 
			tbl_undertime.name as name, 
			tbl_undertime.type as type,
			tbl_undertime.date_ut as date_ut, 
			tbl_undertime.ut_no as ut_no, 
			tbl_undertime.time_out as time_out, 
			tbl_undertime.reason as reason, 
			tbl_undertime.status as status, 
			tbl_out_attendance.times as emp_time_out, 
			tbl_out_attendance.dates as date,
			admin1.department_id as dept_id,
			admin1.branch_id as brnch_id,
			admin1.is_hr as is_hr
		');
		$this->db->from('tbl_undertime');
		$this->db->join('tbl_out_attendance', 'tbl_out_attendance.employee_number = tbl_undertime.employee_number AND tbl_out_attendance.dates = tbl_undertime.date_ut','left');
		$this->db->join('admin1', 'tbl_undertime.employee_number = admin1.emp_no_id', 'left');
		$this->db->order_by('tbl_undertime.date_ut', 'ASC');
		$this->db->order_by('tbl_undertime.name', 'ASC');
		$this->db->where('tbl_undertime.date_ut >=', $start_date);
		$this->db->where('tbl_undertime.date_ut <=', $end_date);
		$this->db->where('admin1.branch_id ', $branch_id);
		$this->db->where('tbl_undertime.status ', 'Recommending for Approval');
	
		$query = $this->db->get();

		foreach($query->result() as $ut)
		{
			$ut->is_correct = 1;
		}

		return $query->result();
	}

	public function get_ot_all_by_oic($start_date, $end_date, $branch_id)
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
			tbl_employees.branch_id as branch_id,
			tbl_employees.department_id as department_id,
			admin1.department_id as dept_id,
			admin1.branch_id as brnch_id,
			admin1.is_hr as is_hr
		');

		$this->db->from('tbl_ot');
		$this->db->join('tbl_in_attendance', 'tbl_in_attendance.employee_number = tbl_ot.employee_number AND tbl_in_attendance.dates = tbl_ot.date_ot','left');
		$this->db->join('tbl_out_attendance', 'tbl_out_attendance.employee_number = tbl_ot.employee_number AND tbl_out_attendance.dates = tbl_ot.date_ot','left');
		$this->db->join('tbl_employees', 'tbl_ot.employee_number = tbl_employees.employee_number', 'left');
		$this->db->join('admin1', 'tbl_ot.employee_number = admin1.emp_no_id', 'left');
		$this->db->order_by('tbl_ot.date_ot', 'ASC');
		$this->db->order_by('tbl_ot.name', 'ASC');
		$this->db->where('tbl_ot.date_ot >=', $start_date);
		$this->db->where('tbl_ot.date_ot <=', $end_date);
		$this->db->where('admin1.branch_id ', $branch_id);
		$this->db->where('tbl_ot.status ', 'Recommending for Approval');
		
		$query = $this->db->get();

		foreach($query->result() as $ot)
		{
			$ot->red_mark_alert = 0 ;
		}

		return $query->result();
	}

	public function get_ob_all_by_oic($start_date, $end_date, $branch_id)
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
			tbl_employees.branch_id as branch_id,
			tbl_employees.department_id as department_id,
			admin1.department_id as dept_id,
			admin1.branch_id as brnch_id,
			admin1.is_hr as is_hr
		');
		$this->db->from('tbl_ob');
		$this->db->join('tbl_employees', 'tbl_ob.employee_number = tbl_employees.employee_number');
		$this->db->join('admin1', 'tbl_ob.employee_number = admin1.emp_no_id', 'left');
		$this->db->order_by('tbl_ob.date_ob', 'ASC');
		$this->db->order_by('tbl_ob.name', 'ASC');
		$this->db->where('tbl_ob.date_ob >=', $start_date);
		$this->db->where('tbl_ob.date_ob <=', $end_date);
		$this->db->where('admin1.branch_id ', $branch_id);
		$this->db->where('tbl_ob.remarks ', 'Recommending for Approval');
		
	  $query = $this->db->get();
	   
	  return $query->result();
	}

	public function get_slvl_all_by_headSalesOperations($start_date, $end_date, $department_id)
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
			tbl_employees.department_id as department_id,
			admin1.department_id as dept_id,
			admin1.branch_id as brnch_id,
			admin1.is_hr as is_hr,
			tbl_slvl.slvl_num as slvl_num,
			leave_credits.sl_credit as sl_credit,
			leave_credits.vl_credit as vl_credit,
			leave_credits.elcl_credit as elcl_credit,
			leave_credits.fl_credit	as fl_credit
			");
		$this->db->from('tbl_slvl');
		$this->db->join('tbl_employees', 'tbl_slvl.employee_number = tbl_employees.employee_number');
		$this->db->join('admin1', 'tbl_slvl.employee_number = admin1.emp_no_id', 'left');
		$this->db->join('leave_credits', 'tbl_slvl.employee_number = leave_credits.employee_number', 'left');
		$this->db->order_by('tbl_slvl.status', 'DESC');
		$this->db->order_by('tbl_slvl.name', 'ASC');
		$this->db->order_by('tbl_slvl.date', 'ASC');
		$this->db->where('tbl_slvl.date >=', $start_date);
		$this->db->where('tbl_slvl.date <=', $end_date);
		$this->db->where('(tbl_slvl.status = "FOR APPROVAL" AND admin1.department_id = '. $department_id .' OR  admin1.department_id = '. $department_id .' AND admin1.branch_id = '. 18 .')');
		
		$query = $this->db->get();

		return $query->result();
	}

	public function get_ut_all_by_headSalesOperations($start_date, $end_date, $department_id)
	{
		$this->db->select('
			tbl_undertime.id as id,
			tbl_undertime.employee_number as employee_number, 
			tbl_undertime.name as name, 
			tbl_undertime.type as type,
			tbl_undertime.date_ut as date_ut, 
			tbl_undertime.ut_no as ut_no, 
			tbl_undertime.time_out as time_out, 
			tbl_undertime.reason as reason, 
			tbl_undertime.status as status, 
			tbl_out_attendance.times as emp_time_out, 
			tbl_out_attendance.dates as date,
			admin1.department_id as dept_id,
			admin1.branch_id as brnch_id,
			admin1.is_hr as is_hr
		');
		$this->db->from('tbl_undertime');
		$this->db->join('tbl_out_attendance', 'tbl_out_attendance.employee_number = tbl_undertime.employee_number AND tbl_out_attendance.dates = tbl_undertime.date_ut','left');
		$this->db->join('admin1', 'tbl_undertime.employee_number = admin1.emp_no_id', 'left');
		$this->db->order_by('tbl_undertime.status', 'DESC');
		$this->db->order_by('tbl_undertime.date_ut', 'ASC');
		$this->db->order_by('tbl_undertime.name', 'ASC');
		$this->db->where('tbl_undertime.date_ut >=', $start_date);
		$this->db->where('tbl_undertime.date_ut <=', $end_date);
		$this->db->where('(tbl_undertime.status = "FOR APPROVAL" AND admin1.department_id = '. $department_id .' OR  admin1.department_id = '. $department_id .' AND admin1.branch_id = '. 18 .')');
		
		$query = $this->db->get();

		foreach($query->result() as $ut)
		{
			$ut->is_correct = 1;
		}

		return $query->result();
	}

	public function get_ot_all_by_headSalesOperations($start_date, $end_date, $department_id)
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
			tbl_employees.branch_id as branch_id,
			tbl_employees.department_id as department_id,
			admin1.department_id as dept_id,
			admin1.branch_id as brnch_id,
			admin1.is_hr as is_hr
		');

		$this->db->from('tbl_ot');
		$this->db->join('tbl_in_attendance', 'tbl_in_attendance.employee_number = tbl_ot.employee_number AND tbl_in_attendance.dates = tbl_ot.date_ot','left');
		$this->db->join('tbl_out_attendance', 'tbl_out_attendance.employee_number = tbl_ot.employee_number AND tbl_out_attendance.dates = tbl_ot.date_ot','left');
		$this->db->join('tbl_employees', 'tbl_ot.employee_number = tbl_employees.employee_number', 'left');
		$this->db->join('admin1', 'tbl_ot.employee_number = admin1.emp_no_id', 'left');
		$this->db->order_by('tbl_ot.status', 'DESC');
		$this->db->order_by('tbl_ot.date_ot', 'ASC');
		$this->db->order_by('tbl_ot.name', 'ASC');
		$this->db->where('tbl_ot.date_ot >=', $start_date);
		$this->db->where('tbl_ot.date_ot <=', $end_date);
		$this->db->where('(tbl_ot.status = "FOR APPROVAL" AND admin1.department_id = '. $department_id .' OR  admin1.department_id = '. $department_id .' AND admin1.branch_id = '. 18 .')');
		
		$query = $this->db->get();

		foreach($query->result() as $ot)
		{
			$ot->red_mark_alert = 0 ;
		}

		return $query->result();
	}

	public function get_ob_all_by_headSalesOperations($start_date, $end_date, $department_id)
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
			tbl_employees.branch_id as branch_id,
			tbl_employees.department_id as department_id,
			admin1.department_id as dept_id,
			admin1.branch_id as brnch_id,
			admin1.is_hr as is_hr
		');
		$this->db->from('tbl_ob');
		$this->db->join('tbl_employees', 'tbl_ob.employee_number = tbl_employees.employee_number');
		$this->db->join('admin1', 'tbl_ob.employee_number = admin1.emp_no_id', 'left');
		$this->db->order_by('tbl_ob.remarks', 'DESC');
		$this->db->order_by('tbl_ob.date_ob', 'ASC');
		$this->db->order_by('tbl_ob.name', 'ASC');
		$this->db->where('tbl_ob.date_ob >=', $start_date);
		$this->db->where('tbl_ob.date_ob <=', $end_date);
		$this->db->where('(tbl_ob.remarks = "FOR APPROVAL" AND admin1.department_id = '. $department_id .' OR  admin1.department_id = '. $department_id .' AND admin1.branch_id = '. 18 .')');
		
	  $query = $this->db->get();
	   
	  return $query->result();
	}

	public function get_slvl_all_by_Heads($start_date, $end_date, $department_id)
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
			tbl_employees.department_id as department_id,
			admin1.department_id as dept_id,
			admin1.branch_id as brnch_id,
			admin1.is_hr as is_hr,
			tbl_slvl.slvl_num as slvl_num,
			leave_credits.sl_credit as sl_credit,
			leave_credits.vl_credit as vl_credit,
			leave_credits.elcl_credit as elcl_credit,
			leave_credits.fl_credit	as fl_credit
			");
		$this->db->from('tbl_slvl');
		$this->db->join('tbl_employees', 'tbl_slvl.employee_number = tbl_employees.employee_number');
		$this->db->join('admin1', 'tbl_slvl.employee_number = admin1.emp_no_id', 'left');
		$this->db->join('leave_credits', 'tbl_slvl.employee_number = leave_credits.employee_number', 'left');
		$this->db->order_by('tbl_slvl.date', 'ASC');
		$this->db->order_by('tbl_slvl.name', 'ASC');
		$this->db->where('tbl_slvl.date >=', $start_date);
		$this->db->where('tbl_slvl.date <=', $end_date);
		$this->db->where('(tbl_slvl.status = "FOR APPROVAL" AND admin1.department_id = '. $department_id .')');
		
		$query = $this->db->get();

		return $query->result();
	}

	public function get_ut_all_by_Heads($start_date, $end_date, $department_id)
	{
		$this->db->select('
			tbl_undertime.id as id,
			tbl_undertime.employee_number as employee_number, 
			tbl_undertime.name as name, 
			tbl_undertime.type as type,
			tbl_undertime.date_ut as date_ut, 
			tbl_undertime.ut_no as ut_no, 
			tbl_undertime.time_out as time_out, 
			tbl_undertime.reason as reason, 
			tbl_undertime.status as status, 
			tbl_out_attendance.times as emp_time_out, 
			tbl_out_attendance.dates as date,
			admin1.department_id as dept_id,
			admin1.branch_id as brnch_id,
			admin1.is_hr as is_hr
		');
		$this->db->from('tbl_undertime');
		$this->db->join('tbl_out_attendance', 'tbl_out_attendance.employee_number = tbl_undertime.employee_number AND tbl_out_attendance.dates = tbl_undertime.date_ut','left');
		$this->db->join('admin1', 'tbl_undertime.employee_number = admin1.emp_no_id', 'left');
		$this->db->order_by('tbl_undertime.date_ut', 'ASC');
		$this->db->order_by('tbl_undertime.name', 'ASC');
		$this->db->where('tbl_undertime.date_ut >=', $start_date);
		$this->db->where('tbl_undertime.date_ut <=', $end_date);
		$this->db->where('(tbl_undertime.status = "FOR APPROVAL" AND admin1.department_id = '. $department_id .')');
		
		$query = $this->db->get();

		foreach($query->result() as $ut)
		{
			$ut->is_correct = 1;
		}

		return $query->result();
	}

	public function get_ot_all_by_Heads($start_date, $end_date, $department_id)
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
			tbl_employees.branch_id as branch_id,
			tbl_employees.department_id as department_id,
			admin1.department_id as dept_id,
			admin1.branch_id as brnch_id,
			admin1.is_hr as is_hr
		');

		$this->db->from('tbl_ot');
		$this->db->join('tbl_in_attendance', 'tbl_in_attendance.employee_number = tbl_ot.employee_number AND tbl_in_attendance.dates = tbl_ot.date_ot','left');
		$this->db->join('tbl_out_attendance', 'tbl_out_attendance.employee_number = tbl_ot.employee_number AND tbl_out_attendance.dates = tbl_ot.date_ot','left');
		$this->db->join('tbl_employees', 'tbl_ot.employee_number = tbl_employees.employee_number', 'left');
		$this->db->join('admin1', 'tbl_ot.employee_number = admin1.emp_no_id', 'left');
		$this->db->order_by('tbl_ot.date_ot', 'ASC');
		$this->db->order_by('tbl_ot.name', 'ASC');
		$this->db->where('tbl_ot.date_ot >=', $start_date);
		$this->db->where('tbl_ot.date_ot <=', $end_date);
		$this->db->where('(tbl_ot.status = "FOR APPROVAL" AND admin1.department_id = '. $department_id .')');
		
		$query = $this->db->get();

		foreach($query->result() as $ot)
		{
			$ot->red_mark_alert = 0 ;
		}

		return $query->result();
	}

	public function get_ob_all_by_Heads($start_date, $end_date, $department_id)
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
			tbl_employees.branch_id as branch_id,
			tbl_employees.department_id as department_id,
			admin1.department_id as dept_id,
			admin1.branch_id as brnch_id,
			admin1.is_hr as is_hr
		');
		$this->db->from('tbl_ob');
		$this->db->join('tbl_employees', 'tbl_ob.employee_number = tbl_employees.employee_number');
		$this->db->join('admin1', 'tbl_ob.employee_number = admin1.emp_no_id', 'left');
		$this->db->order_by('tbl_ob.date_ob', 'ASC');
		$this->db->order_by('tbl_ob.name', 'ASC');
		$this->db->where('tbl_ob.date_ob >=', $start_date);
		$this->db->where('tbl_ob.date_ob <=', $end_date);
		$this->db->where('(tbl_slvl.remarks = "FOR APPROVAL" AND admin1.department_id = '. $department_id .')');
		
	  $query = $this->db->get();
	   
	  return $query->result();
	}

	public function get_slvl_all_by_headCAM($start_date, $end_date, $department_id)
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
			tbl_employees.department_id as department_id,
			admin1.department_id as dept_id,
			admin1.branch_id as brnch_id,
			admin1.is_hr as is_hr,
			admin1.is_rfa as is_rfa,
			admin1.is_fa as is_fa,
			tbl_slvl.slvl_num as slvl_num,
			leave_credits.sl_credit as sl_credit,
			leave_credits.vl_credit as vl_credit,
			leave_credits.elcl_credit as elcl_credit,
			leave_credits.fl_credit	as fl_credit
			");
		$this->db->from('tbl_slvl');
		$this->db->join('tbl_employees', 'tbl_slvl.employee_number = tbl_employees.employee_number');
		$this->db->join('admin1', 'tbl_slvl.employee_number = admin1.emp_no_id', 'left');
		$this->db->join('leave_credits', 'tbl_slvl.employee_number = leave_credits.employee_number', 'left');
		$this->db->order_by('tbl_slvl.status', 'ASC');
		$this->db->order_by('tbl_slvl.date', 'ASC');
		$this->db->order_by('tbl_slvl.name', 'ASC');
		$this->db->where('tbl_slvl.date >=', $start_date);
		$this->db->where('tbl_slvl.date <=', $end_date);
		$this->db->where('(tbl_slvl.status = "FOR APPROVAL" AND admin1.department_id = '. 2 .' OR admin1.department_id = '. 5 . ' AND tbl_slvl.status = "FOR APPROVAL" OR admin1.is_rfa = '. 1 . ' AND tbl_slvl.status = "FOR APPROVAL" OR admin1.is_fa != '. 0 .' AND tbl_slvl.status = "FOR APPROVAL"  OR tbl_slvl.status = "FOR NOTIFICATION")');
		
		$query = $this->db->get();

		return $query->result();
	}

	public function get_ut_all_by_headCAM($start_date, $end_date, $department_id)
	{
		$this->db->select('
			tbl_undertime.id as id,
			tbl_undertime.employee_number as employee_number, 
			tbl_undertime.name as name, 
			tbl_undertime.type as type,
			tbl_undertime.date_ut as date_ut, 
			tbl_undertime.ut_no as ut_no, 
			tbl_undertime.time_out as time_out, 
			tbl_undertime.reason as reason, 
			tbl_undertime.status as status, 
			tbl_out_attendance.times as emp_time_out, 
			tbl_out_attendance.dates as date,
			admin1.department_id as dept_id,
			admin1.branch_id as brnch_id,
			admin1.is_hr as is_hr
		');
		$this->db->from('tbl_undertime');
		$this->db->join('tbl_out_attendance', 'tbl_out_attendance.employee_number = tbl_undertime.employee_number AND tbl_out_attendance.dates = tbl_undertime.date_ut','left');
		$this->db->join('admin1', 'tbl_undertime.employee_number = admin1.emp_no_id', 'left');
		$this->db->order_by('tbl_undertime.status', 'ASC');
		$this->db->order_by('tbl_undertime.date_ut', 'ASC');
		$this->db->order_by('tbl_undertime.name', 'ASC');
		$this->db->where('tbl_undertime.date_ut >=', $start_date);
		$this->db->where('tbl_undertime.date_ut <=', $end_date);
		$this->db->where('(tbl_undertime.status = "FOR APPROVAL" AND admin1.department_id = '. 2 .' OR admin1.department_id = '. 5 . ' AND tbl_undertime.status = "FOR APPROVAL" OR admin1.is_rfa = '. 1 . ' AND tbl_undertime.status = "FOR APPROVAL" OR admin1.is_fa != '. 0 .' AND tbl_undertime.status = "FOR APPROVAL"  OR tbl_undertime.status = "FOR NOTIFICATION")');
		
		$query = $this->db->get();

		foreach($query->result() as $ut)
		{
			$ut->is_correct = 1;
		}

		return $query->result();
	}

	public function get_ot_all_by_headCAM($start_date, $end_date, $department_id)
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
			tbl_employees.branch_id as branch_id,
			tbl_employees.department_id as department_id,
			admin1.department_id as dept_id,
			admin1.branch_id as brnch_id,
			admin1.is_hr as is_hr,
			admin1.is_rfa as is_rfa,
			admin1.is_fa as is_fa
		');

		$this->db->from('tbl_ot');
		$this->db->join('tbl_in_attendance', 'tbl_in_attendance.employee_number = tbl_ot.employee_number AND tbl_in_attendance.dates = tbl_ot.date_ot','left');
		$this->db->join('tbl_out_attendance', 'tbl_out_attendance.employee_number = tbl_ot.employee_number AND tbl_out_attendance.dates = tbl_ot.date_ot','left');
		$this->db->join('tbl_employees', 'tbl_ot.employee_number = tbl_employees.employee_number', 'left');
		$this->db->join('admin1', 'tbl_ot.employee_number = admin1.emp_no_id', 'left');
		$this->db->order_by('tbl_ot.status', 'ASC');
		$this->db->order_by('tbl_ot.date_ot', 'ASC');
		$this->db->order_by('tbl_ot.name', 'ASC');
		$this->db->where('tbl_ot.date_ot >=', $start_date);
		$this->db->where('tbl_ot.date_ot <=', $end_date);
		$this->db->where('(tbl_ot.status = "FOR APPROVAL" AND admin1.department_id = '. 2 .' OR admin1.department_id = '. 5 . ' AND tbl_ot.status = "FOR APPROVAL" OR admin1.is_rfa = '. 1 . ' AND tbl_ot.status = "FOR APPROVAL" OR admin1.is_fa != '. 0 .' AND tbl_ot.status = "FOR APPROVAL"  OR tbl_ot.status = "FOR NOTIFICATION")');
		
		$query = $this->db->get();

		foreach($query->result() as $ot)
		{
			$ot->red_mark_alert = 0 ;
		}

		return $query->result();
	}

	public function get_ob_all_by_headCAM($start_date, $end_date, $department_id)
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
			tbl_employees.branch_id as branch_id,
			tbl_employees.department_id as department_id,
			admin1.department_id as dept_id,
			admin1.branch_id as brnch_id,
			admin1.is_hr as is_hr,
			admin1.is_rfa as is_rfa,
			admin1.is_fa as is_fa
		');
		$this->db->from('tbl_ob');
		$this->db->join('tbl_employees', 'tbl_ob.employee_number = tbl_employees.employee_number');
		$this->db->join('admin1', 'tbl_ob.employee_number = admin1.emp_no_id', 'left');
		$this->db->order_by('tbl_ob.remarks', 'ASC');
		$this->db->order_by('tbl_ob.date_ob', 'ASC');
		$this->db->order_by('tbl_ob.name', 'ASC');
		$this->db->where('tbl_ob.date_ob >=', $start_date);
		$this->db->where('tbl_ob.date_ob <=', $end_date);
		$this->db->where('(tbl_ob.remarks = "FOR APPROVAL" AND admin1.department_id = '. 2 .' OR admin1.department_id = '. 5 . ' AND tbl_ob.remarks = "FOR APPROVAL" OR admin1.is_rfa = '. 1 . ' AND tbl_ob.remarks = "FOR APPROVAL" OR admin1.is_fa != '. 0 .' AND tbl_ob.remarks = "FOR APPROVAL"  OR tbl_ob.remarks = "FOR NOTIFICATION")');
		
	  $query = $this->db->get();
	   
	  return $query->result();
	}

	public function get_slvl_all_by_isHr_and_isVerify($start_date, $end_date, $department_id, $branch_id)
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
			tbl_employees.department_id as department_id,
			admin1.department_id as dept_id,
			admin1.branch_id as brnch_id,
			admin1.is_hr as is_hr,
			tbl_slvl.slvl_num as slvl_num,
			leave_credits.sl_credit as sl_credit,
			leave_credits.vl_credit as vl_credit,
			leave_credits.elcl_credit as elcl_credit,
			leave_credits.fl_credit	as fl_credit
			");
		$this->db->from('tbl_slvl');
		$this->db->join('tbl_employees', 'tbl_slvl.employee_number = tbl_employees.employee_number');
		$this->db->join('admin1', 'tbl_slvl.employee_number = admin1.emp_no_id', 'left');
		$this->db->join('leave_credits', 'tbl_slvl.employee_number = leave_credits.employee_number', 'left');
		$this->db->order_by('tbl_slvl.status', 'DESC');
		$this->db->order_by('tbl_slvl.name', 'ASC');
		$this->db->order_by('tbl_slvl.date', 'ASC');
		$this->db->where('tbl_slvl.date >=', $start_date);
		$this->db->where('tbl_slvl.date <=', $end_date);
		if($branch_id != 'ALL')
		{
			$this->db->where('tbl_slvl.branch_id ', $branch_id);
		}
		$this->db->where('(tbl_slvl.status = "Recommending for Approval" AND admin1.department_id = '. $department_id .' OR tbl_slvl.staTUS = "FOR APPROVAL" AND admin1.department_id = '. $department_id .' OR tbl_slvl.status = "FOR VERIFICATION")');
	
		$query = $this->db->get();

		return $query->result();
	}

	public function get_ot_all_by_isHr_and_isVerify($start_date, $end_date, $department_id, $branch_id)
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
			tbl_employees.branch_id as branch_id,
			tbl_employees.department_id as department_id,
			admin1.department_id as dept_id,
			admin1.branch_id as brnch_id,
		');

		$this->db->from('tbl_ot');
		$this->db->join('tbl_in_attendance', 'tbl_in_attendance.employee_number = tbl_ot.employee_number AND tbl_in_attendance.dates = tbl_ot.date_ot','left');
		$this->db->join('tbl_out_attendance', 'tbl_out_attendance.employee_number = tbl_ot.employee_number AND tbl_out_attendance.dates = tbl_ot.date_ot','left');
		$this->db->join('tbl_employees', 'tbl_ot.employee_number = tbl_employees.employee_number', 'left');
		$this->db->join('admin1', 'tbl_ot.employee_number = admin1.emp_no_id', 'left');
		$this->db->order_by('tbl_ot.status', 'DESC');
		$this->db->order_by('tbl_ot.date_ot', 'ASC');
		$this->db->order_by('tbl_ot.name', 'ASC');
		$this->db->where('tbl_ot.date_ot >=', $start_date);
		$this->db->where('tbl_ot.date_ot <=', $end_date);
		if($branch_id != 'ALL')
		{
			$this->db->where('tbl_ot.branch_id', $branch_id);
		}
		$this->db->where('(tbl_ot.status = "Recommending for Approval" AND admin1.department_id = '. $department_id .' OR tbl_ot.staTUS = "FOR APPROVAL" AND admin1.department_id = '. $department_id .' OR tbl_ot.status = "FOR VERIFICATION")');
		
		$query = $this->db->get();

		foreach($query->result() as $ot)
		{
			$ot->red_mark_alert = 0 ;
		}

		return $query->result();
	}

	public function get_ob_all_by_isHr_and_isVerify($start_date, $end_date, $department_id, $branch_id)
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
			tbl_employees.branch_id as branch_id,
			tbl_employees.department_id as department_id,
			admin1.department_id as dept_id,
			admin1.branch_id as brnch_id,
			admin1.is_hr as is_hr
		');
		$this->db->from('tbl_ob');
		$this->db->join('tbl_employees', 'tbl_ob.employee_number = tbl_employees.employee_number');
		$this->db->join('admin1', 'tbl_ob.employee_number = admin1.emp_no_id', 'left');
		$this->db->order_by('tbl_ob.remarks', 'DESC');
		$this->db->order_by('tbl_ob.date_ob', 'ASC');
		$this->db->order_by('tbl_ob.name', 'ASC');
		$this->db->where('tbl_ob.date_ob >=', $start_date);
		$this->db->where('tbl_ob.date_ob <=', $end_date);
		if($branch_id != 'ALL')
		{
			$this->db->where('tbl_ob.branch_id', $branch_id);
		}
		$this->db->where('(tbl_ob.remarks = "Recommending for Approval" AND admin1.department_id = '. $department_id .' OR tbl_ob.remarks = "FOR APPROVAL" AND admin1.department_id = '. $department_id .' OR tbl_ob.remarks = "FOR VERIFICATION")');
		
	  $query = $this->db->get();
	   
	  return $query->result();
	}

	public function get_ut_all_by_isHr_and_isVerify($start_date, $end_date, $department_id, $branch_id)
	{
		$this->db->select('
			tbl_undertime.id as id,
			tbl_undertime.employee_number as employee_number, 
			tbl_undertime.name as name, 
			tbl_undertime.type as type,
			tbl_undertime.date_ut as date_ut, 
			tbl_undertime.ut_no as ut_no, 
			tbl_undertime.time_out as time_out, 
			tbl_undertime.reason as reason, 
			tbl_undertime.status as status, 
			tbl_out_attendance.times as emp_time_out, 
			tbl_out_attendance.dates as date,
			admin1.department_id as dept_id,
			admin1.branch_id as brnch_id,
			admin1.is_hr as is_hr
		');
		$this->db->from('tbl_undertime');
		$this->db->join('tbl_out_attendance', 'tbl_out_attendance.employee_number = tbl_undertime.employee_number AND tbl_out_attendance.dates = tbl_undertime.date_ut','left');
		$this->db->join('admin1', 'tbl_undertime.employee_number = admin1.emp_no_id', 'left');
		$this->db->order_by('tbl_undertime.status', 'DESC');
		$this->db->order_by('tbl_undertime.date_ut', 'ASC');
		$this->db->order_by('tbl_undertime.name', 'ASC');
		$this->db->where('tbl_undertime.date_ut >=', $start_date);
		$this->db->where('tbl_undertime.date_ut <=', $end_date);
		if($branch_id != 'ALL')
		{
			$this->db->where('tbl_undertime.branch_id', $branch_id);
		}
		$this->db->where('(tbl_undertime.status = "Recommending for Approval" AND admin1.department_id = '. $department_id .' OR tbl_undertime.status = "FOR APPROVAL" AND admin1.department_id = '. $department_id .' OR tbl_undertime.status = "FOR VERIFICATION")');
		
		$query = $this->db->get();

		foreach($query->result() as $ut)
		{
			$ut->is_correct = 1;
		}

		return $query->result();
	}

	public function get_slvl_allbranch_rfv($start_date, $end_date, $branch_id)
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
			tbl_employees.department_id as department_id,
			admin1.department_id as dept_id,
			admin1.branch_id as brnch_id,
			admin1.is_hr as is_hr,
			tbl_slvl.slvl_num as slvl_num,
			leave_credits.sl_credit as sl_credit,
			leave_credits.vl_credit as vl_credit,
			leave_credits.elcl_credit as elcl_credit,
			leave_credits.fl_credit	as fl_credit
			");
		$this->db->from('tbl_slvl');
		$this->db->join('tbl_employees', 'tbl_slvl.employee_number = tbl_employees.employee_number');
		$this->db->join('admin1', 'tbl_slvl.employee_number = admin1.emp_no_id', 'left');
		$this->db->join('leave_credits', 'tbl_slvl.employee_number = leave_credits.employee_number', 'left');
		$this->db->order_by('tbl_slvl.name', 'ASC');
		$this->db->order_by('tbl_slvl.date', 'ASC');
		$this->db->where('tbl_slvl.date >=', $start_date);
		$this->db->where('tbl_slvl.date <=', $end_date);
		if($branch_id != 'ALL')
		{
			$this->db->where('tbl_slvl.branch_id', $branch_id);
		}
		$this->db->where('tbl_slvl.status ', 'Recommending for Verification');
		
		$query = $this->db->get();

		return $query->result();
	}

	public function get_ut_allbranch_rfv($start_date, $end_date, $branch_id)
	{
		$this->db->select('
			tbl_undertime.id as id,
			tbl_undertime.employee_number as employee_number, 
			tbl_undertime.name as name, 
			tbl_undertime.type as type,
			tbl_undertime.date_ut as date_ut, 
			tbl_undertime.ut_no as ut_no, 
			tbl_undertime.time_out as time_out, 
			tbl_undertime.reason as reason, 
			tbl_undertime.status as status, 
			tbl_out_attendance.times as emp_time_out, 
			tbl_out_attendance.dates as date,
			admin1.department_id as dept_id,
			admin1.branch_id as brnch_id,
			admin1.is_hr as is_hr
		');
		$this->db->from('tbl_undertime');
		$this->db->join('tbl_out_attendance', 'tbl_out_attendance.employee_number = tbl_undertime.employee_number AND tbl_out_attendance.dates = tbl_undertime.date_ut','left');
		$this->db->join('admin1', 'tbl_undertime.employee_number = admin1.emp_no_id', 'left');
		$this->db->order_by('tbl_undertime.name', 'ASC');
		$this->db->order_by('tbl_undertime.date_ut', 'ASC');
		$this->db->where('tbl_undertime.date_ut >=', $start_date);
		$this->db->where('tbl_undertime.date_ut <=', $end_date);
		if($branch_id != 'ALL')
		{
			$this->db->where('tbl_undertime.branch_id', $branch_id);
		}
		$this->db->where('tbl_undertime.status ', 'Recommending for Verification');

		$query = $this->db->get();


		$this->db->select('daily_in,daily_out,daily_friday_out,casual_in,casual_out,casual_friday_out');
		$this->db->order_by('id','DESC');
		$query1 = $this->db->get('tbl_schedules');
		// REGULAR EMPLOYEE TIME IN AND OUT
		$daily_in = $query1->row()->daily_in; 
		$daily_out  = $query1->row()->daily_out;
		$daily_friday_out = $query1->row()->daily_friday_out;
		//CASUAL TIME IN AND OUT
		$casual_in = $query1->row()->casual_in;
		$casual_out = $query1->row()->casual_out;
		$casual_friday_out = $query1->row()->casual_friday_out;

		//REGULAR EMPLOYEE CONVERT TO MINS TIME IN AND OUT
		$explod_in = explode(':', $daily_in);
		$daily_in_mins = $explod_in[0] * 60;

		$explod_out = explode(':', $daily_out);
		$daily_out_mins = $explod_out[0] * 60;

		$explode_fri_out = explode(':', $daily_friday_out);
		$daily_friday_out_mins = $explode_fri_out[0] * 60;

		//CASUAL CONVERT TO MINS TIME IN AND OUT
		$explod_casual_in = explode(':', $casual_in);
		$casual_daily_in_mins = $explod_casual_in[0] * 60;

		$explod_casual_out = explode(':', $casual_out);
		$casual_out_mins = $explod_casual_out[0] * 60;

		$explod_casual_friday_out = explode(':', $casual_friday_out);
		$casual_friday_out_mins = $explod_casual_friday_out[0] * 60;
	

		foreach($query->result() as $ut)
		{
			if($ut->emp_time_out == NULL)
			{
				$total_ut = '';
				$ut->is_correct = 0;	
			}
			else
			{
				$explod_time_out = explode(' ', $ut->emp_time_out);
				$explod_hr_mins = explode(':', $explod_time_out[1]);
				$convert_hrs = $explod_hr_mins[0] * 60 + $explod_hr_mins[1];
				//echo $convert_hrs . '|';

				$weekdate = date('w', strtotime($ut->date_ut)); 
				
				if($weekdate <= 4)
				{
					if($ut->employee_number == 10195)
					{
						//$casual_mon_thru = 1080;
						$casual_mon_thru = $casual_out_mins;
						$total_ut = $casual_mon_thru - $convert_hrs;

						if($total_ut == $ut->ut_no)
						{
							//echo $ut->employee_number . ':' . 'SUCCESS' . ' ';
							$ut->is_correct = 1;
						}
						else
						{
							//echo $ut->employee_number . ':' . 'FAILED' . ' ';
							$ut->is_correct = 0;
						}
					}
					else 
					{
						//$mon_thru = 1020;
						$mon_thru = $daily_out_mins;
						$total_ut = $mon_thru - $convert_hrs;
	
						if($total_ut == $ut->ut_no)
						{
							//echo $ut->employee_number . ':' . 'SUCCESS' . ' ';
							$ut->is_correct = 1;
						}
						else
						{
							//echo $ut->employee_number . ':' . 'FAILED' . ' ';
							$ut->is_correct = 0;
						}
					}

				}
				elseif($weekdate == 5)
				{
					if($ut->employee_number == 10195)
					{
						//$casual_friday = 1080;
						$casual_friday = $casual_friday_out_mins;
						$total_ut_friday = $casual_friday - $convert_hrs;
	
						if($total_ut_friday == $ut->ut_no)
						{
							//echo $ut->employee_number . ':' . 'SUCCESS' . ' ';
							$ut->is_correct = 1;
						}
						else
						{
							//echo $ut->employee_number . ':' . 'FAILED' . ' ';
							$ut->is_correct = 0;
						}
					}
					else 
					{
						//$friday = 1020;
						$friday = $daily_friday_out_mins;
						$total_ut_friday = $friday - $convert_hrs;
	
						if($total_ut_friday == $ut->ut_no)
						{
							//echo $ut->employee_number . ':' . 'SUCCESS' . ' ';
							$ut->is_correct = 1;
						}
						else
						{
							//echo $ut->employee_number . ':' . 'FAILED' . ' ';
							$ut->is_correct = 0;
						}	
					}

				}
			}
			
			
		}
		/*echo '<pre>';
		print_r($query->result());
		echo '</pre>';*/

		return $query->result();
	}

	public function get_ot_allbranch_rfv($start_date, $end_date, $branch_id)
	{
		$this->db->select('
			tbl_ot.id as id, 
			tbl_ot.employee_number as employee_number, 
			tbl_ot.name as name,
			tbl_ot.time_in as time_in, 
			tbl_ot.time_out as time_out, 
			tbl_ot.date_ot as date_ot,
			tbl_ot.ot_num as total_ot,    
			tbl_ot.ot_type as ot_type,      
			tbl_ot.ot_type_name as ot_type_name,     
			tbl_ot.nature_of_work as nature_of_work, 
			tbl_in_attendance.times as employee_in,  
 			tbl_out_attendance.times as employee_out,
			tbl_ot.status as tbl_ot_status,
			tbl_employees.branch_id as branch_id,
			tbl_employees.department_id as department_id,
			admin1.department_id as dept_id,
			admin1.branch_id as brnch_id,
		');

		$this->db->from('tbl_ot');
		$this->db->join('tbl_in_attendance', 'tbl_in_attendance.employee_number = tbl_ot.employee_number AND tbl_in_attendance.dates = tbl_ot.date_ot','left');
		$this->db->join('tbl_out_attendance', 'tbl_out_attendance.employee_number = tbl_ot.employee_number AND tbl_out_attendance.dates = tbl_ot.date_ot','left');
		$this->db->join('tbl_employees', 'tbl_ot.employee_number = tbl_employees.employee_number', 'left');
		$this->db->join('admin1', 'tbl_ot.employee_number = admin1.emp_no_id', 'left');
		$this->db->order_by('tbl_ot.name', 'ASC');
		$this->db->order_by('tbl_ot.date_ot', 'ASC');
		$this->db->where('tbl_ot.date_ot >=', $start_date);
		$this->db->where('tbl_ot.date_ot <=', $end_date);
		if($branch_id != 'ALL')
		{
			$this->db->where('tbl_ot.branch_id', $branch_id);
		}
		$this->db->where('tbl_ot.status', 'Recommending for Verification');
		
		$query = $this->db->get();

		
		$this->db->select('daily_in,daily_out,daily_friday_out,casual_in,casual_out,casual_friday_out');
		$this->db->order_by('id','DESC');
		$query1 = $this->db->get('tbl_schedules');
		// REGULAR EMPLOYEE TIME IN AND OUT
		$daily_in = $query1->row()->daily_in; 
		$daily_out  = $query1->row()->daily_out;
		$daily_friday_out = $query1->row()->daily_friday_out;
		//CASUAL TIME IN AND OUT
		$casual_in = $query1->row()->casual_in;
		$casual_out = $query1->row()->casual_out;
		$casual_friday_out = $query1->row()->casual_friday_out;

		//REGULAR EMPLOYEE CONVERT TO MINS TIME IN AND OUT
		$explod_in = explode(':', $daily_in);
		$daily_in_mins = $explod_in[0] * 60;

		$explod_out = explode(':', $daily_out);
		$daily_out_mins = $explod_out[0] * 60;

		$explode_fri_out = explode(':', $daily_friday_out);
		$daily_friday_out_mins = $explode_fri_out[0] * 60;

		//CASUAL CONVERT TO MINS TIME IN AND OUT
		$explod_casual_in = explode(':', $casual_in);
		$casual_daily_in_mins = $explod_casual_in[0] * 60;

		$explod_casual_out = explode(':', $casual_out);
		$casual_out_mins = $explod_casual_out[0] * 60;

		$explod_casual_friday_out = explode(':', $casual_friday_out);
		$casual_friday_out_mins = $explod_casual_friday_out[0] * 60;

		foreach ($query->result() as $ot) 
		{
			$fixed_in = $daily_in_mins;
			$halfday = 750;
			$fixed_out = $daily_out_mins;
			$friday_out = $daily_friday_out_mins;

			$casual_in = $casual_daily_in_mins;
			$casual_out = $casual_out_mins;
			$casual_friday_out = $casual_friday_out_mins;

			$weekdate = date('w', strtotime($ot->date_ot)); 

			if($ot->employee_in == NULL && $ot->employee_out == NULL)
			{
				$employee_in = '';
				$employee_out = '';
				$total_in = '';
				$total_out = '';
				$ot->red_mark_alert = 1;
			}
			else
			{
				//BIO TIME IN
				$explod_date_time = explode(' ', $ot->employee_in); 
				$employee_in = explode(':', $explod_date_time[1]);
				$total_in = intval($employee_in[0]*60) + $employee_in[1];
				//echo $total_in . ' | ' ;

				//BIO TIME OUT
				$explod_date_time1 = explode(' ', $ot->employee_out); 
				$employee_out = explode(':', $explod_date_time1[1]);
				$total_out = intval($employee_out[0]*60) + $employee_out[1];
				//echo $total_out . ' | ' ;
				//echo '<br>';

				//OT FILE TIME IN
				$ot_time_in = explode(':', $ot->time_in);
				$ot_total_in = intval($ot_time_in[0]*60) + $ot_time_in[1];
				//echo $ot_total_in . ' | ' ;

				//OT FILE TIME OUT
				$ot_time_out = explode(':', $ot->time_out);
				$ot_total_out = intval($ot_time_out[0]*60) + $ot_time_out[1];
				//echo $ot_total_out . ' | ' ;

				// computation of time in overtime

				if($weekdate <= 4 && $weekdate != 0)
				{
					if($ot->employee_number == 10195)
					{
						if($total_out > $halfday)
						{
							$whole_day_ot = $total_out - $total_in - 60;
						}
						else
						{
							$whole_day_ot = $total_out - $total_in;
						}
						$ot_hrs= $ot->total_ot;
						$actual_ot_hrs_night = $total_out - $casual_out + 1;
						$actual_ot_hrs_morning = $casual_in - $total_in + 1;
						/*echo  ' ID ' . $ot->id . ' OT HRS: ' . $ot_hrs . ' OT NIGHT: ' . $actual_ot_hrs_night . ' OT MORNING: ' . $actual_ot_hrs_morning . ' OT WHOLEDAY: ' . $whole_day_ot;
						echo '<br>';*/
						if($actual_ot_hrs_morning >= $ot_hrs && $ot->ot_type == 'ROT')
						{
							/*echo $weekdate . ' | ' . $ot->employee_number . ' OT MORNING';
							echo '<br>';*/
							$ot->red_mark_alert = 0;
						}
						elseif($actual_ot_hrs_night >= $ot_hrs && $ot->ot_type == 'ROT')
						{
							/*echo  $weekdate . ' | ' . $ot->employee_number . ' OT NIGHT';
							echo '<br>';*/
							$ot->red_mark_alert = 0;
						}
						elseif($whole_day_ot >= $ot_hrs && $ot->ot_type == 'SHOT')
						{
							/*echo  $weekdate . ' | ' . $ot->employee_number . ' OT WHOLE DAY';
							echo '<br>';*/
							$ot->red_mark_alert = 0;
						}
						elseif($whole_day_ot >= $ot_hrs && $ot->ot_type == 'LHOT')
						{
							/*echo  $weekdate . ' | ' . $ot->employee_number . ' OT WHOLE DAY';
							echo '<br>';*/
							$ot->red_mark_alert = 0;
						}
						else
						{
							/*echo 'RED MARK ALERT!';
							echo '<br>';*/
							$ot->red_mark_alert = 1;
						}
					}

					else
					{
						if($total_out > $halfday)
						{
							$whole_day_ot = $total_out - $total_in - 60;
						}
						else
						{
							$whole_day_ot = $total_out - $total_in;
						}
						$ot_hrs= $ot->total_ot;
						$actual_ot_hrs_night = $total_out - $fixed_out + 1;
						$actual_ot_hrs_morning = $fixed_in - $total_in + 1;
						/*echo  ' ID ' . $ot->id . ' OT HRS: ' . $ot_hrs . ' OT NIGHT: ' . $actual_ot_hrs_night . ' OT MORNING: ' . $actual_ot_hrs_morning . ' OT WHOLEDAY: ' . $whole_day_ot;
						echo '<br>';*/
						if($actual_ot_hrs_morning >= $ot_hrs && $ot->ot_type == 'ROT')
						{
							/*echo $weekdate . ' | ' . $ot->employee_number . ' OT MORNING';
							echo '<br>';*/
							$ot->red_mark_alert = 0;
						}
						elseif($actual_ot_hrs_night >= $ot_hrs && $ot->ot_type == 'ROT')
						{
							/*echo  $weekdate . ' | ' . $ot->employee_number . ' OT NIGHT';
							echo '<br>';*/
							$ot->red_mark_alert = 0;
						}
						elseif($whole_day_ot >= $ot_hrs && $ot->ot_type == 'SHOT')
						{
							/*echo  $weekdate . ' | ' . $ot->employee_number . ' OT WHOLE DAY';
							echo '<br>';*/
							$ot->red_mark_alert = 0;
						}
						elseif($whole_day_ot >= $ot_hrs && $ot->ot_type == 'LHOT')
						{
							/*echo  $weekdate . ' | ' . $ot->employee_number . ' OT WHOLE DAY';
							echo '<br>';*/
							$ot->red_mark_alert = 0;
						}
						else
						{
							/*echo 'RED MARK ALERT!';
							echo '<br>';*/
							$ot->red_mark_alert = 1;
						}

					}
					
				}
				elseif($weekdate == 5)
				{
					if($ot->employee_number == 10195)
					{
						if($total_out > $halfday)
						{
							$whole_day_ot = $total_out - $total_in - 60;
						}
						else
						{
							$whole_day_ot = $total_out - $total_in;
						}
						$ot_hrs= $ot->total_ot;
						$actual_ot_hrs_night = $total_out - $casual_friday_out + 1;
						$actual_ot_hrs_morning = $casual_in - $total_in + 1;
						/*echo ' ID ' . $ot->id . ' OT HRS: ' . $ot_hrs . ' OT NIGHT: ' . $actual_ot_hrs_night . ' OT MORNING: ' . $actual_ot_hrs_morning . ' OT WHOLEDAY: ' . $whole_day_ot;
						echo '<br>';*/
						if($actual_ot_hrs_morning >= $ot_hrs && $ot->ot_type == 'ROT')
						{
							/*echo  $weekdate . ' | ' . $ot->employee_number . ' OT MORNING';
							echo '<br>';*/
							$ot->red_mark_alert = 0;
						}
						elseif($actual_ot_hrs_night >= $ot_hrs && $ot->ot_type == 'ROT')
						{
							/*echo  $weekdate . ' | ' . $ot->employee_number . ' OT NIGHT';
							echo '<br>';*/
							$ot->red_mark_alert = 0;
						}
						elseif($whole_day_ot >= $ot_hrs && $ot->ot_type == 'SHOT')
						{
							/*echo  $weekdate . ' | ' . $ot->employee_number . ' OT WHOLE DAY';
							echo '<br>';*/
							$ot->red_mark_alert = 0;
						}
						elseif($whole_day_ot >= $ot_hrs && $ot->ot_type == 'LHOT')
						{
							/*echo  $weekdate . ' | ' . $ot->employee_number . ' OT WHOLE DAY';
							echo '<br>';*/
							$ot->red_mark_alert = 0;
						}
						else
						{
							/*echo 'RED MARK ALERT!';
							echo '<br>';*/
							$ot->red_mark_alert = 1;
						}
					}

					else 
					{
						if($total_out > $halfday)
						{
							$whole_day_ot = $total_out - $total_in - 60;
						}
						else
						{
							$whole_day_ot = $total_out - $total_in;
						}
						$ot_hrs= $ot->total_ot;
						$actual_ot_hrs_night = $total_out - $friday_out + 1;
						$actual_ot_hrs_morning = $fixed_in - $total_in + 1;
						/*echo ' ID ' . $ot->id . ' OT HRS: ' . $ot_hrs . ' OT NIGHT: ' . $actual_ot_hrs_night . ' OT MORNING: ' . $actual_ot_hrs_morning . ' OT WHOLEDAY: ' . $whole_day_ot;
						echo '<br>';*/
						if($actual_ot_hrs_morning >= $ot_hrs && $ot->ot_type == 'ROT')
						{
							/*echo  $weekdate . ' | ' . $ot->employee_number . ' OT MORNING';
							echo '<br>';*/
							$ot->red_mark_alert = 0;
						}
						elseif($actual_ot_hrs_night >= $ot_hrs && $ot->ot_type == 'ROT')
						{
							/*echo  $weekdate . ' | ' . $ot->employee_number . ' OT NIGHT';
							echo '<br>';*/
							$ot->red_mark_alert = 0;
						}
						elseif($whole_day_ot >= $ot_hrs && $ot->ot_type == 'SHOT')
						{
							/*echo  $weekdate . ' | ' . $ot->employee_number . ' OT WHOLE DAY';
							echo '<br>';*/
							$ot->red_mark_alert = 0;
						}
						elseif($whole_day_ot >= $ot_hrs && $ot->ot_type == 'LHOT')
						{
							/*echo  $weekdate . ' | ' . $ot->employee_number . ' OT WHOLE DAY';
							echo '<br>';*/
							$ot->red_mark_alert = 0;
						}
						else
						{
							/*echo 'RED MARK ALERT!';
							echo '<br>';*/
							$ot->red_mark_alert = 1;
						}	
					}

				}
				elseif($weekdate == 6)
				{
					if($total_out > $halfday)
					{
						$whole_day_ot = $total_out - $total_in - 60;
					}
					else
					{
						$whole_day_ot = $total_out - $total_in;
					}
					$ot_hrs= $ot->total_ot;
					/*echo ' ID ' . $ot->id . ' OT HRS: ' . $ot_hrs .  ' OT WHOLEDAY: ' . $whole_day_ot;
					echo '<br>';*/
					if($whole_day_ot >= $ot_hrs && $ot->ot_type == 'ROT')
					{
						/*echo  $weekdate . ' | ' . $ot->employee_number . ' OT WHOLE DAY';
						echo '<br>';*/
						$ot->red_mark_alert = 0;
					}
					elseif($whole_day_ot >= $ot_hrs && $ot->ot_type == 'SHOT')
					{
						/*echo  $weekdate . ' | ' . $ot->employee_number . ' OT WHOLE DAY';
						echo '<br>';*/
						$ot->red_mark_alert = 0;
					}
					elseif($whole_day_ot >= $ot_hrs && $ot->ot_type == 'LHOT')
					{
						/*echo  $weekdate . ' | ' . $ot->employee_number . ' OT WHOLE DAY';
						echo '<br>';*/
						$ot->red_mark_alert = 0;
					}
					else
					{
						/*echo 'RED MARK ALERT!';
						echo '<br>';*/
						$ot->red_mark_alert = 1;
					}
				}
				elseif($weekdate == 0)
				{
					if($total_out > $halfday)
					{
						$whole_day_ot = $total_out - $total_in - 60;
					}
					else 
					{
						$whole_day_ot = $total_out - $total_in;
					}
					$ot_hrs = $ot->total_ot;
					/*echo ' ID ' . $ot->id . ' OT HRS: ' . $ot_hrs .  ' OT WHOLEDAY: ' . $whole_day_ot;
					echo '<br>';*/
					if($whole_day_ot >= $ot_hrs && $ot->ot_type == 'RDOT')
					{
						/*echo  $weekdate . ' | ' . $ot->employee_number . 'RD OT WHOLE DAY';
						echo '<br>';*/
						$ot->red_mark_alert = 0;
					}
					else
					{
						/*echo 'RED MARK ALERT!';
						echo '<br>';*/
						$ot->red_mark_alert = 1;
					}
				}
			}	
		}

		/*echo '<pre>'; 
		print_r($query->result());
		echo '</pre>';*/
		return $query->result();
	}

	public function get_ob_allbranch_rfv($start_date, $end_date, $branch_id)
	{
		$this->db->select("
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
			tbl_employees.branch_id as branch_id,
			tbl_employees.department_id as department_id,
			admin1.department_id as dept_id,
			admin1.branch_id as brnch_id,
			admin1.is_hr as is_hr
			");
		$this->db->from('tbl_ob');
		$this->db->join('tbl_employees', 'tbl_ob.employee_number = tbl_employees.employee_number');
		$this->db->join('admin1', 'tbl_ob.employee_number = admin1.emp_no_id', 'left');
		$this->db->order_by('tbl_ob.name', 'ASC');
		$this->db->order_by('tbl_ob.date_ob', 'ASC');
		$this->db->where('tbl_ob.date_ob >=', $start_date);
		$this->db->where('tbl_ob.date_ob <=', $end_date);
		if($branch_id != 'ALL')
		{
			$this->db->where('tbl_ob.branch_id', $branch_id);
		}
		$this->db->where('tbl_ob.remarks ', 'Recommending for Verification');
		
		$query = $this->db->get();

		return $query->result();
	}

	public function get_slvl_allbranch_process($start_date, $end_date, $department_id)
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
			tbl_employees.department_id as department_id,
			admin1.department_id as dept_id,
			admin1.branch_id as brnch_id,
			admin1.is_hr as is_hr,
			tbl_slvl.slvl_num as slvl_num,
			leave_credits.sl_credit as sl_credit,
			leave_credits.vl_credit as vl_credit,
			leave_credits.elcl_credit as elcl_credit,
			leave_credits.fl_credit	as fl_credit
			");
		$this->db->from('tbl_slvl');
		$this->db->join('tbl_employees', 'tbl_slvl.employee_number = tbl_employees.employee_number');
		$this->db->join('admin1', 'tbl_slvl.employee_number = admin1.emp_no_id', 'left');
		$this->db->join('leave_credits', 'tbl_slvl.employee_number = leave_credits.employee_number', 'left');
		$this->db->order_by('tbl_slvl.name', 'ASC');
		$this->db->order_by('tbl_slvl.date', 'ASC');
		$this->db->where('tbl_slvl.date >=', $start_date);
		$this->db->where('tbl_slvl.date <=', $end_date);
		$this->db->where('tbl_slvl.status =  "FOR PROCESS" OR tbl_slvl.status = "Recommending for Approval"  AND admin1.department_id = '. 7 .' OR tbl_slvl.status = "FOR APPROVAL"  AND admin1.department_id = '. 3 .'');
		$query = $this->db->get();

		return $query->result();
	}

	public function get_ut_allbranch_process($start_date, $end_date, $department_id)
	{
		$this->db->select('
			tbl_undertime.id as id,
			tbl_undertime.employee_number as employee_number, 
			tbl_undertime.name as name, 
			tbl_undertime.type as type,
			tbl_undertime.date_ut as date_ut, 
			tbl_undertime.ut_no as ut_no, 
			tbl_undertime.time_out as time_out, 
			tbl_undertime.reason as reason, 
			tbl_undertime.status as status, 
			tbl_out_attendance.times as emp_time_out, 
			tbl_out_attendance.dates as date,
			admin1.department_id as dept_id,
			admin1.branch_id as brnch_id,
			admin1.is_hr as is_hr
		');
		$this->db->from('tbl_undertime');
		$this->db->join('tbl_out_attendance', 'tbl_out_attendance.employee_number = tbl_undertime.employee_number AND tbl_out_attendance.dates = tbl_undertime.date_ut','left');
		$this->db->join('admin1', 'tbl_undertime.employee_number = admin1.emp_no_id', 'left');
		$this->db->order_by('tbl_undertime.name', 'ASC');
		$this->db->order_by('tbl_undertime.date_ut', 'ASC');
		$this->db->where('tbl_undertime.date_ut >=', $start_date);
		$this->db->where('tbl_undertime.date_ut <=', $end_date);
		$this->db->where('tbl_undertime.status =  "FOR PROCESS" OR tbl_undertime.status = "Recommending for Approval"  AND admin1.department_id = '. 7 .' OR tbl_undertime.status = "FOR APPROVAL"  AND admin1.department_id = '. 3 .'');

		$query = $this->db->get();

		foreach($query->result() as $ut)
		{
			$ut->is_correct = 1;
		}

		return $query->result();
	}

	public function get_ot_allbranch_process($start_date, $end_date, $department_id)
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
			tbl_employees.branch_id as branch_id,
			tbl_employees.department_id as department_id,
			admin1.department_id as dept_id,
			admin1.branch_id as brnch_id,
		');

		$this->db->from('tbl_ot');
		$this->db->join('tbl_in_attendance', 'tbl_in_attendance.employee_number = tbl_ot.employee_number AND tbl_in_attendance.dates = tbl_ot.date_ot','left');
		$this->db->join('tbl_out_attendance', 'tbl_out_attendance.employee_number = tbl_ot.employee_number AND tbl_out_attendance.dates = tbl_ot.date_ot','left');
		$this->db->join('tbl_employees', 'tbl_ot.employee_number = tbl_employees.employee_number', 'left');
		$this->db->join('admin1', 'tbl_ot.employee_number = admin1.emp_no_id', 'left');
		$this->db->order_by('tbl_ot.date_ot', 'ASC');
		$this->db->order_by('tbl_ot.name', 'ASC');
		$this->db->where('tbl_ot.date_ot >=', $start_date);
		$this->db->where('tbl_ot.date_ot <=', $end_date);
		$this->db->where('tbl_ot.status =  "FOR PROCESS" OR tbl_ot.status = "Recommending for Approval"  AND admin1.department_id = '. 7 .' OR tbl_ot.status = "FOR APPROVAL"  AND admin1.department_id = '. 3 .'');

		$query = $this->db->get();

		foreach($query->result() as $ot)
		{
			$ot->red_mark_alert = 0;
		}

		return $query->result();
	}

	public function get_ob_allbranch_process($start_date, $end_date, $department_id)
	{
		$this->db->select("
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
			tbl_employees.branch_id as branch_id,
			tbl_employees.department_id as department_id,
			admin1.department_id as dept_id,
			admin1.branch_id as brnch_id,
			admin1.is_hr as is_hr
			");
		$this->db->from('tbl_ob');
		$this->db->join('tbl_employees', 'tbl_ob.employee_number = tbl_employees.employee_number');
		$this->db->join('admin1', 'tbl_ob.employee_number = admin1.emp_no_id', 'left');
		$this->db->order_by('tbl_ob.date_ob', 'ASC');
		$this->db->order_by('tbl_ob.name', 'ASC');
		$this->db->where('tbl_ob.date_ob >=', $start_date);
		$this->db->where('tbl_ob.date_ob <=', $end_date);
		$this->db->where('tbl_ob.remarks =  "FOR PROCESS" OR tbl_ob.remarks = "Recommending for Approval"  AND admin1.department_id = '. 7 .' OR tbl_ob.remarks = "FOR APPROVAL"  AND admin1.department_id = '. 3 .'');

	
		$query = $this->db->get();

		return $query->result();
	}

	public function process_ob()
	{
		$this->db->trans_start();
		$process_date = date('Y-m-d H:i:s');
		
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

			$data = array(
				'status'	   => 'PROCESSED',
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

	public function process_ut()
	{
		$this->db->trans_start();

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

		$trans = $this->db->trans_complete();
		return $trans;
	}



}	