<?php
class Csv_import_model extends CI_Model
{
	public function select()
	{
		$query = $this->db->query('
			SELECT *
			FROM temp_attendance
			WHERE id IN (
			    SELECT MAX(id)
			    FROM temp_attendance
			    WHERE status="out"
			    GROUP BY employee_number, DATE(date_time)
			)
			OR id IN (
			    SELECT MIN(id)
			    FROM temp_attendance
			    WHERE status="in"
			    GROUP BY employee_number, DATE(date_time)
			) ORDER BY employee_number, DATE(date_time) ASC, status
		')->result();

		return $query;
	}
	/*public function select()
	{
		$query = $this->db->query('
			SELECT *
			FROM temp_attendance
			WHERE id IN (
			    SELECT MAX(id)
			    FROM temp_attendance
			    WHERE status="out"
			    GROUP BY employee_number, DATE(date_time)
			)
			OR id IN (
			    SELECT MIN(id)
			    FROM temp_attendance
			    WHERE status="in"
			    GROUP BY employee_number, DATE(date_time)
			) ORDER BY employee_number, DATE(date_time) ASC, status
		')->result();

		return $query;
	}*/

	public function insert($data)
	{
		$this->db->insert_batch('temp_attendance', $data);
	}

	public function insert_csv_attendance()
	{
		//ini_set('max_execution_time', 3000);
		//ini_set("upload_max_filesize","300M");

		$emp_nos = $this->input->post('employee_number');
		$name = $this->input->post('name');
		$status =	$this->input->post('status');
		$date = $this->input->post('date');
		$time = $this->input->post('time');
		$number_dates = $this->input->post('number_dates');
		$branch_id = $this->input->post('branch_id');
		$this->db->trans_start();

		$this->db->select('start_date,end_date');
		$this->db->order_by('id','DESC');
		$query = $this->db->get('tbl_cut_off_date');
		$start_date = $query->row()->start_date;
		$end_date = $query->row()->end_date;
		$cur_date = $start_date;
		$this->db->select("employee_number, CONCAT(tbl_employees.last_name, ' ', tbl_employees.first_name , ' ', tbl_employees.middle_name) AS name, branch_id");
		$this->db->where('tbl_employees.branch_id', $branch_id);
		$this->db->where('tbl_employees.is_active', '1');
		$this->db->order_by('employee_number','ASC');

		$query = $this->db->get('tbl_employees');

		$employees = $query->result();

		$in_outs = array('in', 'out');
		$cur_date = $start_date;
		$trans = '';
		$data = '';
		$j = 1;

		foreach($employees as $emp)
		{
			//$trans .= '<br>' . $j . ') ' . $emp->employee_number;

			$cur_date = $start_date;

			for($k = 1; $k <= $number_dates; $k++)
			{
				//$trans .= '<br>----- ) ' . $cur_date;

				foreach($in_outs as $in_out)
				{
					//$trans .= '<br>---------- ) ' . $in_out;
					//$data = '<br>--------------- ) ' . $emp->employee_number . ' ' . $emp->name . ' ' . '0000-00-00' . ' ' . '0000-00-00 00:00:00' . ' ' . 'NO IN/OUT';

					if($in_out == 'in')
					{
						//$data = '<br>--------------- ) ' . $emp->employee_number . ' ' . $emp->name . ' ' . '0000-00-00' . ' ' . '0000-00-00 00:00:00' . ' ' . $in_out;
						$data = array(
							'employee_number' => $emp->employee_number,
							'name'            => $emp->name,
							'dates'           => $cur_date,
							'times'           => $cur_date . ' ' . '00:00:00',
							'status'          => 'NO IN',
							'branch_id'       => $emp->branch_id
						);
					}
					elseif($in_out == 'out')
					{
						//$data = '<br>--------------- ) ' . $emp->employee_number . ' ' . $emp->name . ' ' . '0000-00-00' . ' ' . '0000-00-00 00:00:00' . ' ' . $in_out;
						$this->db->select('id');
						$this->db->order_by('id', 'DESC');

						$query = $this->db->get('tbl_in_attendance');
						$in_id = $query->row()->id;

						$data = array(
							'in_id'						=> $in_id,
							'employee_number' => $emp->employee_number,
							'name'            => $emp->name,
							'dates'           => $cur_date,
							'times'           => $cur_date . ' ' . '00:00:00',
							'status'          => 'NO OUT',
							'branch_id'       => $emp->branch_id
						);
					}

					$rec = FALSE;
					$i = 0;

					foreach($emp_nos as $emp_no)
					{
						if($emp->employee_number == $emp_no && $cur_date == $date[$i] && $in_out == $status[$i])
						{
							//$data = '<br>--------------- ) ' . $emp_no . ' ' . $date[$i] . ' ' . $time[$i] . ' ' . $status[$i];
							if($in_out == 'in')
							{
								$this->db->where('employee_number', $emp_no);
								$this->db->where('dates', $date[$i]);
								$this->db->where('status', $status[$i]);

								$in_att = $this->db->get('tbl_in_attendance');

								if($in_att->num_rows() == 0)
								{
									$data = array(
										'employee_number' => $emp_no,
										'name'            => $name[$i],
										'dates'           => $date[$i],
										'times'           => $time[$i],
										'status'          => $status[$i],
										'branch_id'       => $emp->branch_id
									);

									$this->db->insert('tbl_in_attendance', $data) or die($this->db->_error_message());
								}
							}
							elseif($in_out == 'out')
							{
								$this->db->where('employee_number', $emp_no);
								$this->db->where('dates', $date[$i]);
								$this->db->where('status', $status[$i]);

								$in_att = $this->db->get('tbl_out_attendance');

								if($in_att->num_rows() == 0)
								{
									$this->db->select('id');
									$this->db->order_by('id', 'DESC');

									$query = $this->db->get('tbl_in_attendance');

									$in_id = $query->row()->id;


									$data = array(
										'in_id'						=> $in_id,
										'employee_number' => $emp_no,
										'name'            => $name[$i],
										'dates'           => $date[$i],
										'times'           => $time[$i],
										'status'          => $status[$i],
										'branch_id'       => $emp->branch_id
									);

									$this->db->insert('tbl_out_attendance', $data) or die($this->db->_error_message());
								}
							}

							$rec = TRUE;
							//$trans .= $data;
							$j++;
						}

						$i++;
					}

					if($rec == FALSE)
					{
						if($in_out == 'in')
						{
							$this->db->where('employee_number', $data['employee_number']);
							$this->db->where('dates', $data['dates']);
							$this->db->where('status', $data['status']);

							$in_att = $this->db->get('tbl_in_attendance');

							if($in_att->num_rows() == 0)
							{
								$this->db->insert('tbl_in_attendance', $data) or die($this->db->_error_message());
							}
						}
						elseif($in_out == 'out')
						{
							$this->db->where('employee_number', $data['employee_number']);
							$this->db->where('dates', $data['dates']);
							$this->db->where('status', $data['status']);

							$in_att = $this->db->get('tbl_out_attendance');

							if($in_att->num_rows() == 0)
							{
								$this->db->insert('tbl_out_attendance', $data) or die($this->db->_error_message());
							}
						}

						//trans .= $data;
						$j++;
					}
				}
				$conv_date = strtotime($start_date);
				$cur_date = date('Y-m-d', strtotime('+' . $k .' days', $conv_date));
			}
		}

		/*$data = array(
			'is_transfer' => 1
		);
		$this->db->update('temp_attendance', $data);
		return $trans;
		*/
		$this->db->where('is_transfer', 0);
		$this->db->delete('temp_attendance');

		$trans = $this->db->trans_complete();

		return $trans;
		//print_r($trans);

	}

	public function get_set_dates()
	{
		$this->db->select();
		$this->db->order_by('id','DESC');
		$query = $this->db->get('tbl_cut_off_date');
		return $query->row();
	}
}
