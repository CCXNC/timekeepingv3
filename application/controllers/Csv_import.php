<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Csv_import extends CI_Controller {
	
	public function __construct()
	{ 
		parent::__construct();

		date_default_timezone_set('Asia/Manila');
		if($this->session->userdata('logged_in') == FALSE)
		{
			$this->session->set_flashdata('no_access', 'Sorry, you are not logged in');
			
			redirect('user/index');
		}
		$this->load->model('csv_import_model');
		$this->load->library('csvimport');
	}

	public function index_csv()
	{ 
		$data['employee'] = $this->csv_import_model->select();
		$data['set_date'] = $this->csv_import_model->get_set_dates();
		$data['main_content'] = 'payroll/csv/add';
		$this->load->view('layouts/main', $data);
	}

	public function index_csv1()
	{ 
		$data['employee'] = $this->csv_import_model->select();
		$data['set_date'] = $this->csv_import_model->get_set_dates();
		$data['main_content'] = 'payroll/csv/add1';
		$this->load->view('layouts/main', $data);
	}

	function view_csv()
	{
		$data['employee'] = $this->csv_import_model->select(); 
		$data['set_date'] = $this->csv_import_model->get_set_dates();
		$data['main_content'] = 'payroll/csv/index';
		$this->load->view('layouts/main', $data);
	}	

	public function attendance_process()
	{
		$this->csv_import_model->insert_csv_attendance();
		redirect('reports/index_time_keeping');
	} 
 
	function import() 
	{
		$file_data = $this->csvimport->get_array($_FILES["csv_file"]["tmp_name"]);
		foreach($file_data as $row)
		{
			$data[] = array( 
					'employee_number'	=>	$row["No."],
        	'name'			      =>	$row["Name"],
        	'date_time'			  =>	$row["Date/Time"],
        	'status'		      =>	$row["Status"]
			);
			/*$data[] = array( 
					'employee_number'	=>	$row["No."],
        	'name'						=>	$row["NAME"],
        	'date_time'				=>	$row["DATE"].' '.$row["TIME"],
        	'status'					=>	$row["STATUS"]
			);*/
		}
		$this->csv_import_model->insert($data);
	} 
	function import1() 
	{
		$file_data = $this->csvimport->get_array($_FILES["csv_file"]["tmp_name"]);
		foreach($file_data as $row)
		{
			$data[] = array( 
					'employee_number'	=>	$row["No."],
        	'name'						=>	$row["Name"],
        	'date_time'				=>	$row["Date"].' '.$row["Time"],
        	'status'					=>	$row["Status Name"]
			);
		}
		
		$this->csv_import_model->insert($data);
	}

	public function delete_csv()
	{
		$this->db->where('is_transfer', '0');
		$this->db->delete('temp_attendance');
		redirect('Csv_import/view_csv');
	}
}
