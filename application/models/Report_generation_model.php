<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report_generation_model extends CI_Model {

	public function get_total_tardiness($start_date, $end_date)
	{
		$this->db->select('tbl_report_generation.employee_number as tard_employee_number, SUM(tbl_report_generation.tardiness) as total_tardiness');
		$this->db->from('tbl_report_generation');
		$this->db->group_by('employee_number');
		$this->db->where('tbl_report_generation.dates >=', $start_date);
		$this->db->where('tbl_report_generation.dates <=', $end_date);
		$query = $this->db->get();

		return $query->result(); 
	}

	public function get_number_tardiness($start_date, $end_date)
	{
		$this->db->select('tbl_report_generation.employee_number as tard_employee_number, SUM(tbl_report_generation.num_of_tardiness) as number_tardiness');
		$this->db->from('tbl_report_generation');
		$this->db->group_by('employee_number');
		$this->db->where('tbl_report_generation.dates >=', $start_date);
		$this->db->where('tbl_report_generation.dates <=', $end_date);
		$query = $this->db->get();

		return $query->result();
	}
 
	public function get_total_night_diff($start_date, $end_date)
	{
		$this->db->select('tbl_report_generation.employee_number as nd_employee_number, SUM(tbl_report_generation.night_diff) as total_night_diff');
		$this->db->from('tbl_report_generation');
		$this->db->group_by('employee_number');
		$this->db->where('tbl_report_generation.dates >=', $start_date);
		$this->db->where('tbl_report_generation.dates <=', $end_date);
		$query = $this->db->get();

		return $query->result();
	}

	public function delete_csv_uploaded($start_date, $end_date, $branch_id)
	{
		$start_date = $this->input->post('start_date');
		$end_date = $this->input->post('end_date');
		$branch_id = $this->input->post('branch_id');

		$this->db->trans_start();
		
		$this->db->where('dates >=', $start_date);
		$this->db->where('dates <=', $end_date);
		$this->db->where('branch_id', $branch_id);
		$this->db->delete('tbl_in_attendance');

		$this->db->where('dates >=', $start_date);
		$this->db->where('dates <=', $end_date);
		$this->db->where('branch_id', $branch_id);
		$this->db->delete('tbl_out_attendance');

		$trans = $this->db->trans_complete();

		return $trans;
	}

	public function get_slvl_datas($start_date, $end_date, $company_id, $type)
	{
		$this->db->select('
			tbl_slvl.id as id, 
			tbl_slvl.employee_number as employee_number, 
			tbl_slvl.name as name, 
			tbl_slvl.type as type_slvl,
			tbl_slvl.type_name as type_name, 
			tbl_slvl.date as date, 
			tbl_slvl.slvl_num as slvl_num,
			tbl_slvl.effective_date_start as date_start, 
			tbl_slvl.effective_date_end as date_end, 
			tbl_slvl.reason as reason,
			tbl_slvl.status as status, 
			tbl_slvl.sl_am_pm as am_pm_type,
			admin1.company as company_id
		');
			$this->db->from('tbl_slvl');
			$this->db->join('admin1', 'admin1.emp_no_id = tbl_slvl.employee_number');
			$this->db->order_by('name');
			$this->db->where('tbl_slvl.date >=', $start_date);
			$this->db->where('tbl_slvl.date <=', $end_date);
			$this->db->where('type', $type);
			$this->db->where('admin1.company', $company_id);
			$this->db->where('status', 'PROCESSED');
			$query = $this->db->get();

			return $query->result();
	}

	public function get_ot_datas($start_date, $end_date, $company_id, $type)
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
			tbl_ot.status as tbl_ot_status,
			admin1.company as company_id
		');
		$this->db->from('tbl_ot');
		$this->db->join('admin1', 'admin1.emp_no_id = tbl_ot.employee_number');
		$this->db->order_by('name');
		$this->db->where('tbl_ot.date_ot >=', $start_date);
		$this->db->where('tbl_ot.date_ot <=', $end_date);
		$this->db->like('ot_type', $type);
		$this->db->where('admin1.company', $company_id);
		$this->db->where('tbl_ot.status', 'PROCESSED');
		$query = $this->db->get();

		return $query->result();
	}

	public function get_ut_datas($start_date,$end_date,$company_id, $type)
	{
		$this->db->select('
			tbl_undertime.id as id,
			tbl_undertime.employee_number as employee_number, 
			tbl_undertime.name as name, 
			tbl_undertime.date_ut as date_ut, 
			tbl_undertime.ut_no as ut_no, 
			tbl_undertime.time_out as time_out, 
			tbl_undertime.reason as reason, 
			tbl_undertime.status as status, 
			admin1.company as company_id
		');
		$this->db->from('tbl_undertime');
		$this->db->join('admin1', 'admin1.emp_no_id = tbl_undertime.employee_number');
		$this->db->order_by('name');
		$this->db->where('date_ut >=', $start_date);
		$this->db->where('date_ut <=', $end_date);
		$this->db->where('tbl_undertime.type', $type);
		$this->db->where('admin1.company', $company_id);
		$this->db->where('tbl_undertime.status', 'PROCESSED');
		$query = $this->db->get();

		return $query->result();
	}

	public function get_ob_datas($start_date,$end_date,$company_id, $type)
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
			tbl_ob.purpose as purpose,
			tbl_ob.time_of_departure as time_of_departure, 
			tbl_ob.time_of_return as time_of_return,
			admin1.company as company_id
		');
		$this->db->from('tbl_ob');
		$this->db->join('admin1', 'admin1.emp_no_id = tbl_ob.employee_number');
		$this->db->order_by('name');
		$this->db->where('date_ob >=', $start_date);
		$this->db->where('date_ob <=', $end_date);
		$this->db->where('tbl_ob.type', $type);
		$this->db->where('admin1.company', $company_id);
		$this->db->where('tbl_ob.remarks', 'PROCESSED');
		$query = $this->db->get();

		return $query->result();
	}
}	
