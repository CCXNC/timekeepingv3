<style type="text/css">
	.container{
		margin-top: 100px;
		margin-left: 10px;
		width: 99%;
		color: black;
	}
	td {
		color: black;
	}
	p{
		font-size: 24px;
		font-family: century gothic;
	}  
  .submit {
  	padding-top: 10px;
  	margin-left: 1020px;
  }
</style>

<div class="container">
<?php if($this->session->flashdata('branch_updated')) : ?>
     <p class="alert alert-dismissable alert-info"><?php echo $this->session->flashdata('branch_updated'); ?></p>
<?php endif; ?>
<?php if($this->session->flashdata('branch_deleted')) : ?>
      <p class="alert alert-dismissable alert-danger"><?php echo $this->session->flashdata('branch_deleted'); ?></p>
<?php endif; ?>  
<!-- TABLE OF BRANCHES -->
<div class="row">
	<div class="col-lg-17">
  	<div class="panel panel-default">
	    	<div class="panel-heading">
	      	<p>Attendance List</p>
	      	<a href="<?php echo base_url(); ?>index.php/reports/index_ob" class="marginbtn btn btn-primary">OB</a>
	      	<a href="<?php echo base_url(); ?>index.php/reports/in_out_index" class=" btn btn-primary">NO IN/OUT</a>
	      	<a href="<?php echo base_url() ?>index.php/reports/adj_employee_time" class="btn btn-primary">Adjustment</a>
		    	<input class="btn btn-primary" id="processTime" type="submit" value="Process">
		    	<a href="<?php echo base_url() ?>index.php/reports/excel1" class="btn btn-primary">Download</a>
	      </div>	
	        <form id="timeForm" method="post">
					    <div class="panel-body">
					      <div class="table-responsive">
					       	<table class="table table-bordered table-hover table-striped cl" id='tbl'>
						        <div class="row">  	
					          	<div class="col-md-2">
							          <div class="form-group">
							              <label for="form_name">START</label> 
							              <input id="form_name" type="text" name="start_date" class="form-control" value=" <?php echo $cut_off->start_date; ?>">
							          </div>
							        </div> 
							        <div class="col-md-2">
							          <div class="form-group">
							              <label for="form_name">END</label>
							              <input id="form_name" type="text" name="end_date" class="form-control" value="<?php echo $cut_off->end_date; ?>">
							          </div>
							        </div>
							      	<?php $convert = (int)$branch; ?>
							        <div class="col-md-2"> 
							          <div class="form-group">
							              <label for="form_name">Branch</label>
							              <select class="form-control" name="branch">
							              	<?php if($branches) : ?>
							              		<?php foreach($branches as $branch) : ?>
							              			<option value="<?php echo $branch->id; ?>" <?php  echo $branch->id == $convert ? 'selected' : ' '; ?> ><?php echo $branch->name; ?></option>
							              		<?php endforeach; ?>
							              	<?php endif; ?>	
							              </select>	
							          </div>
							      	</div>   
							        <br>
							        <button type="submit" class="btn btn-primary">load</button>  
									  </div>
									  <input type="hidden" name="startdates" value="<?php echo $cut_off->start_date; ?>">  
									  <input type="hidden" name="enddates" value="<?php echo $cut_off->end_date; ?>"> 
					            <thead>
				                <tr> 
													<th>Employee Name</th>
													<th>Date</th>
													<th>Time In</th>
													<th>Time Out</th>
													<th>Daily Hours</th>
													<th>Hours Late</th>
													<th>Undertime</th>
													<th>OT Morning</th>
													<th>OT Night</th>
													<th>ND</th>
													<th>BreakTime</th>
													<th>Hours/Saturday</th> 
													<th>Remarks</th>
													<!--<th>ROT</th>
													<th>LOT</th>
													<th>SHOT</th>
													<th>RDOT</th>
													<th>VL</th>
													<th>SL</th>
													<th>AB</th>-->
				                </tr>
					            </thead> 
											<td class="trfixed">
											</td>
											
											<?php if($holidays) : ?>
						            	<?php foreach($holidays as $holiday) : ?>
						            		<?php 
						            			$holiday = $holiday->dates;
						            			//$holiday_day = date('w', strtotime($holiday->dates));
						            			//echo $holiday_day;
						            		?>
						            	<?php endforeach; ?>	
						            <?php endif; ?>	
					            <?php if($schedules) : ?>
												<?php $fixed_daily_in = $schedules->daily_in; ?>
												<?php $fixed_daily_out = $schedules->daily_out; ?>
												<?php $fixed_friday_out = $schedules->daily_friday_out; ?>
											<?php endif; ?>
									    <?php if($employee) : ?>
						    	 			<?php foreach($employee as $emp) : ?>
						    	 			<?php //if($emp->in_status != 'NO IN' || $emp->out_status != 'NO OUT'): ?>
					    	 						<?php
															$in_office	= $fixed_daily_in; 
															$out_office   = $fixed_daily_out;
															$friday_out = $fixed_friday_out;
															$night_diff = '22:00';
															$in_daily = $emp->intime;
															$out_daily = $emp->outtime;
															$week_date = date('w', strtotime($emp->dates)); // Convert in days . friday (5)

															// EXPLODE DATE IN TIME IN / TIME OUT
															$explode_in_date_daily = explode(" ", $in_daily);
															$explode_out_date_daily = explode(" ", $out_daily);
															$date_date_in = $explode_in_date_daily[0];
															$date_date_out = $explode_out_date_daily[0];
															$date_in = $explode_in_date_daily[1];
															$date_out = $explode_out_date_daily[1];

															//NIGHT DIFF
															$explode_night_diff = explode(":", $night_diff);
															$night_diff_hr = $explode_night_diff[0]; 
															$night_diff_min = $explode_night_diff[1]; 
															$total_night_diff = intval($night_diff_hr*60) + $night_diff_min; // total night diff

															// EXPLODE IN AND OUT 
															$explode_in_office = explode(":", $in_office);
															$explode_out_office = explode(":", $out_office);
															$explode_friday_out_office = explode(":", $friday_out);
															$explode_in_daily = explode(":", $date_in);
															$explode_out_daily = explode(":", $date_out);
															$time_in_hr_daily = $explode_in_daily[0];
															$time_in_min_daily = $explode_in_daily[1];
															$time_out_hr_daily = $explode_out_daily[0];
															$time_out_min_daily = $explode_out_daily[1];
															$time_in_hr = $explode_in_office[0];
															$time_in_min = $explode_in_office[1];
															$time_out_hr = $explode_out_office[0];
															$time_out_min = $explode_out_office[1];
															$time_friday_out_hr = $explode_friday_out_office[0];
															$time_friday_out_min = $explode_friday_out_office[1];


															// Convert IN AND OUT
															$total_in_min = intval($time_in_hr*60) + $time_in_min; // DEFAULT IN
															$total_in_min_grace = intval($time_in_hr*60) + $time_in_min + 15; // DEFAULT IN WITH GRACE PERIOD!
															$total_out_min = intval($time_out_hr*60) + $time_out_min; // DEFAULT OUT
															$total_friday_out_min = intval($time_friday_out_hr*60) + $time_friday_out_min; // DEFAULT OUT IN FRIDAY
															$total_in_daily = intval($time_in_hr_daily*60) + $time_in_min_daily; // EMPLOYEE IN
															$total_out_daily = intval($time_out_hr_daily*60) + $time_out_min_daily; // EMPLOYEE OUT

															//COMPUTATION IN OFFICE IN AND OUT
															$total_min_diff = intval($total_out_min - $total_in_min);
															$hr_diff = intval($total_min_diff/60);
															$min_diff = intval($total_min_diff%60);
															

															// IN AND OUT OF EMPLOYEE
															$in = strtotime($emp->intime);
															$out   = strtotime($emp->outtime);
															$diff  = $out - $in;

															//CONVERT OF IN AND OUT
															$hours = floor($diff / (60 * 60));
															$minutes = $diff - $hours * (60 * 60); 
															$total_minutes = floor( $minutes / 60 );
															
															// COMPUTATION OF IN AND OUT
															$total_number_of_hours = $hours.".".$total_minutes; //
															$total_office_hours = $hr_diff.".".$min_diff; // 9:30 Fixed
															$number_hr_daily = $total_number_of_hours; // TOTAL HOURS DAILY!
														?>
					    	 						<tr class="data">
						    	 						<input type="hidden" name="employee_number[]" value="<?php echo $emp->employee_number; ?>">
						    	 						<td>
						    	 							<?php echo $emp->name; ?>
						    	 							<input type="hidden" name="name[]" value="<?php echo $emp->name; ?>">
						    	 						</td>
							    	 					<td>
							    	 						<?php 
							    	 							echo $emp->dates;
							    	 							$con_dates = date('w', strtotime($emp->dates));
							    	 						?>
							    	 						<input type="hidden" name="dates[]" value="<?php echo $emp->dates; ?>">
							    	 						<input type="hidden" name="con_dates[]" value="<?php echo $con_dates; ?>">
							    	 					</td>
							    	 					<?php $start_halfday = 660; $end_halfday = 750; ?>
							    	 					<td <?php echo $total_in_daily >=  $start_halfday && $week_date != 5 && $week_date != 6 && $week_date != 0 ? 'style="background-color:#e74c3c"' : $total_in_daily ==  0 ? 'style="background-color:#e74c3c"' : $total_in_daily >=  $start_halfday && $week_date == 5 ? 'style="background-color:#e74c3c"' : ' '; ?> >
																<?php 
																	$in = explode(" ",$emp->intime);
																	echo $in[1];
																?>
																<input type="hidden" name="intime[]" value="<?php echo $emp->intime; ?>">
															</td>
															<td <?php echo $total_out_daily <  $total_out_min && $week_date != 5 && $week_date != 6 && $week_date != 0 ? 'style="background-color:#e74c3c"' : $total_out_daily ==  0 ? 'style="background-color:#e74c3c"' : $total_out_daily < $total_friday_out_min && $week_date == 5 ? 'style="background-color:#e74c3c"' : ' '; ?> >
																<?php 
																	$out = explode(" ",$emp->outtime);
																	echo $out[1];
																?>	
																<input type="hidden" name="outtime[]" value="<?php echo $emp->outtime; ?>">
															</td>
															<td>	
																<?php 
																// DAILY HOURS

																if($week_date >= 1 && $week_date <= 4)
																{
																	if($date_date_in == $date_date_out && $total_in_daily == 0 && $total_out_daily == 0)
																	{
																		$dly_hrs = 0;
																		echo ' ';
																	}
																	elseif($total_in_min_grace >= $total_in_daily && $total_out_daily >= $total_out_min)
																	{
																		$total_min_diff = intval($total_out_min - $total_in_min - 60);
																		$hr_diff = intval($total_min_diff/60);
																		$min_diff = intval($total_min_diff%60);
																		$hrs1 = sprintf("%02d", $min_diff);
																		echo $hr_diff.".".$hrs1."";
																		$dly_hrs = $total_min_diff;
																	}			
																	elseif($total_in_daily > $total_in_min_grace && $total_out_daily < $total_out_min)
																	{
																		$total_min_diff = intval($total_out_daily - $total_in_daily - 60);
																		$hr_diff = intval($total_min_diff/60);
																		$min_diff = intval($total_min_diff%60);
																		$hrs1 = sprintf("%02d", $min_diff);
																		echo $hr_diff.".".$hrs1."";
																		$dly_hrs = $total_min_diff;
																	}
																	elseif($total_in_daily > $total_in_min_grace )
																	{
																		$total_min_diff = intval($total_out_min - $total_in_daily - 60);
																		$hr_diff = intval($total_min_diff/60);
																		$min_diff = intval($total_min_diff%60);
																		$hrs1 = sprintf("%02d", $min_diff);
																		echo $hr_diff.".".$hrs1."";
																		$dly_hrs = $total_min_diff;
																	}
																	elseif($total_out_daily < $total_out_min)
																	{
																		$total_min_diff = intval($total_out_daily - $total_in_min - 60);
																		$hr_diff = intval($total_min_diff/60);
																		$min_diff = intval($total_min_diff%60);
																		$hrs1 = sprintf("%02d", $min_diff);
																		echo $hr_diff.".".$hrs1."";
																		$dly_hrs = $total_min_diff;
																	}
																	elseif($total_in_daily >= $total_in_min_grace)
																	{
																		$total_min_diff = intval($total_out_min - $total_in_daily - 60);
																		$hr_diff = intval($total_min_diff/60);
																		$min_diff = intval($total_min_diff%60);
																		$hrs1 = sprintf("%02d", $min_diff);
																		echo $hr_diff.".".$min_diff."";
																		$dly_hrs = $total_min_diff;
																	}
																	elseif($total_in_daily <= $total_in_min_grace)
																	{
																		$total_min_diff = intval($total_out_min - $total_in_min - 60);
																		$hr_diff = intval($total_min_diff/60);
																		$min_diff = intval($total_min_diff%60);
																		$hrs1 = sprintf("%02d", $min_diff);
																		echo $hr_diff.".".$hrs1."";
																		$dly_hrs = $total_min_diff;
																	}
																}
																elseif($week_date == 5)
																{
																	if($date_date_in == $date_date_out && $total_in_daily == 0 && $total_out_daily == 0)
																	{
																		$dly_hrs = 0;
																		echo ' ';
																	}
																	elseif($date_date_in == $date_date_out && $total_in_min_grace >= $total_in_daily && $total_out_daily >= $total_friday_out_min)
																	{
																		$total_min_diff = intval($total_friday_out_min - $total_in_min );
																		$hr_diff = intval($total_min_diff/60);
																		$min_diff = intval($total_min_diff%60);
																		$hrs1 = sprintf("%02d", $min_diff);
																		echo $hr_diff.".".$hrs1."";
																		$dly_hrs = $total_min_diff;

																	}	
																	elseif($date_date_in == $date_date_out && $total_in_daily > $total_in_min_grace && $total_out_daily < $total_friday_out_min)
																	{
																		$total_min_diff = intval($total_out_daily - $total_in_daily);
																		$hr_diff = intval($total_min_diff/60);
																		$min_diff = intval($total_min_diff%60);
																		$hrs1 = sprintf("%02d", $min_diff);
																		echo $hr_diff.".".$hrs1."";
																		$dly_hrs = $total_min_diff;
																	}
																	elseif($date_date_in == $date_date_out && $total_in_daily > $total_in_min_grace )
																	{
																		$total_min_diff = intval($total_friday_out_min - $total_in_daily);
																		$hr_diff = intval($total_min_diff/60);
																		$min_diff = intval($total_min_diff%60);
																		$hrs1 = sprintf("%02d", $min_diff);
																		echo $hr_diff.".".$hrs1."";
																		$dly_hrs = $total_min_diff;
																	}
																	elseif($date_date_in == $date_date_out && $total_out_daily < $total_friday_out_min)
																	{
																		$total_min_diff = intval($total_out_daily - $total_in_min);
																		$hr_diff = intval($total_min_diff/60);
																		$min_diff = intval($total_min_diff%60);
																		$hrs1 = sprintf("%02d", $min_diff);
																		echo $hr_diff.".".$hrs1."";
																		$dly_hrs = $total_min_diff;
																	}
																	elseif($date_date_in != $date_date_out && $total_in_daily >= $total_in_min_grace)
																	{
																		$total_min_diff = intval($total_friday_out_min - $total_in_daily);
																		$hr_diff = intval($total_min_diff/60);
																		$min_diff = intval($total_min_diff%60);
																		$hrs1 = sprintf("%02d", $min_diff);
																		echo $hr_diff.".".$hrs1."";
																		$dly_hrs = $total_min_diff;
																	}
																	elseif($date_date_in != $date_date_out || $total_in_daily <= $total_in_min_grace)
																	{
																		$total_min_diff = intval($total_friday_out_min - $total_in_min);
																		$hr_diff = intval($total_min_diff/60);
																		$min_diff = intval($total_min_diff%60);
																		$hrs1 = sprintf("%02d", $min_diff);
																		echo $hr_diff.".".$hrs1."";
																		$dly_hrs = $total_min_diff;
																	}
																}
																elseif($week_date == 6)
																{
																	echo 'SATURDAY';
																	$dly_hrs = 0;
																}
																elseif($week_date == 0)
																{
																	echo 'SUNDAY';
																	$dly_hrs = 0;
																}
																else
																{
																	echo 0;
																	$dly_hrs = 0;
																}

																?>
																
																<input type="hidden" name="daily_hrs[]" value="<?php echo $dly_hrs; ?>">
															</td>
															<td>
																<?php
																	//COMPUTATION OF HOURS LATE !
																	if($week_date == 1 || $week_date == 2 || $week_date == 3 || $week_date == 4)
																	{
																		if($total_in_daily == 0 && $total_out_daily == 0)
																		{
																			$hr_lte = 0;
																			echo ' ';
																		}
																		elseif($start_halfday <= $total_in_daily)
																		{
																			echo 0;
																			$hr_lte = 0;
																		}
																		else
																		{
																			if($total_in_daily > $total_in_min_grace)
																			{
																				$late_hr = intval($total_in_daily - $total_in_min);
																				$hr_diff = intval($late_hr/60);
																				$min_diff = intval($late_hr%60);
																				$hrs1 = sprintf("%02d", $min_diff);
																				echo $hr_diff.".".$hrs1."";
																				$number_of_late = $hr_diff.".".$min_diff;
																				$hr_lte = $late_hr;
																			}
																			else
																			{
																				echo 0;
																				$hr_lte = 0;
																			}
																		}
																	}	
																	elseif($week_date == 5)
																	{
																		if($total_in_daily == 0 && $total_out_daily == 0)
																		{
																			$hr_lte = 0;
																			echo ' ';
																		}
																		elseif($start_halfday <= $total_in_daily)
																		{
																			echo 0;
																			$hr_lte = 0;
																		}
																		else
																		{
																			if($total_in_daily > $total_in_min_grace)
																			{
																				$late_hr = intval($total_in_daily - $total_in_min);
																				$hr_diff = intval($late_hr/60);
																				$min_diff = intval($late_hr%60);
																				$hrs1 = sprintf("%02d", $min_diff);
																				echo $hr_diff.".".$hrs1."";
																				$number_of_late = $hr_diff.".".$min_diff;
																				$hr_lte = $late_hr;
																			}
																			else
																			{
																				echo 0;
																				$hr_lte = 0;
																			}
																		}
																		
																	}
																	else
																	{
																		echo ' ';
																		$hr_lte = 0;
																	}
																?>
																<input type="hidden" name="hours_late[]" value="<?php echo $hr_lte; ?>">
															</td>
															<td>
																<?php
																//COMPUTATION OF UNDERTIME ! 
																$halfday_in = 840;
																if($week_date == 1 || $week_date == 2 || $week_date == 3 || $week_date == 4)
																{
																	if($total_in_daily == 0 && $total_out_daily == 0)
																	{
																		$ud_time = 0;
																		echo ' ';
																	}
																	elseif($date_date_in == $date_date_out && $total_out_daily < $halfday_in)
																	{
																		echo 0; 
																		$ud_time = 0;
																	}
																	elseif($total_out_min <= $total_out_daily)
																	{
																		echo 0;
																		$ud_time = 0;
																	}
																	elseif($total_out_daily > $halfday_in)
																	{
																		$undertime_hr = intval($total_out_min - $total_out_daily);
																		$hr_diff = intval($undertime_hr/60);
																		$min_diff = intval($undertime_hr%60);
																		$hrs1 = sprintf("%02d", $min_diff);
																		echo $hr_diff.".".$hrs1."";
																		$number_of_undertime = $hr_diff.".".$min_diff;
																		$ud_time = $undertime_hr;
																	}
																	else
																	{
																		echo 0;
																		$ud_time = 0;
																	}
																}
																elseif($week_date == 5)
																{
																	if($total_in_daily == 0 && $total_out_daily == 0)
																	{
																		$ud_time = 0;
																		echo ' ';
																	}
																	elseif($date_date_in == $date_date_out && $total_out_daily < $halfday_in)
																	{
																		echo 0; 
																		$ud_time = 0;
																	}
																	elseif($total_friday_out_min <= $total_out_daily)
																	{
																		echo 0;
																		$ud_time = 0;
																	}
																	elseif($date_date_in == $date_date_out && $halfday_in < $total_out_daily)
																	{
																		$undertime_hr = intval($total_friday_out_min - $total_out_daily);
																		$hr_diff = intval($undertime_hr/60);
																		$min_diff = intval($undertime_hr%60);
																		$hrs1 = sprintf("%02d", $min_diff);
																		echo $hr_diff.".".$hrs1."";
																		$number_of_undertime = $hr_diff.".".$min_diff; 
																		$ud_time = $undertime_hr;
																	}
																	else
																	{
																		echo 0;
																		$ud_time = 0;
																	}
																}
																else
																{
																	echo ' ';
																	$ud_time = 0;
																}
																?>
																<input type="hidden" name="undertime[]" value="<?php echo $ud_time; ?>">
															</td>
															<td>
																<?php
																	//COMPUTATION OF OT MORNING !
																	if($week_date == 1 || $week_date == 2 || $week_date == 3 || $week_date == 4)
																	{
																		if($total_in_daily == 0 && $total_out_daily == 0)
																		{
																			$ot_mrning = 0;
																			echo ' ';
																		}
																		elseif($total_in_daily <= $total_in_min)
																		{
																			$ot_hr_morning = intval($total_in_min - $total_in_daily);
																			$hr_diff = intval($ot_hr_morning/60);
																			$min_diff = intval($ot_hr_morning%60);
																			$hrs1 = sprintf("%02d", $min_diff);
																			echo $hr_diff.".".$hrs1."";
																			$number_ot_morning = $hr_diff.".".$min_diff;
																			$ot_mrning = $ot_hr_morning;
																		} 
																		else
																		{
																			echo 0;
																			$ot_hr_morning = 0;
																			$ot_mrning = 0;
																		}
																	}
																	elseif($week_date == 5)
																	{
																		if($total_in_daily == 0 && $total_out_daily == 0)
																		{
																			$ot_mrning = 0;
																			echo ' ';
																		}
																		elseif($total_in_daily <= $total_in_min)
																		{
																			$ot_hr_morning = intval($total_in_min - $total_in_daily);
																			$hr_diff = intval($ot_hr_morning/60);
																			$min_diff = intval($ot_hr_morning%60);
																			$hrs1 = sprintf("%02d", $min_diff);
																			echo $hr_diff.".".$hrs1."";
																			$number_ot_morning = $hr_diff.".".$min_diff;
																			$ot_mrning = $ot_hr_morning;
																		} 
																		else
																		{
																			echo 0;
																			$ot_hr_morning = 0;
																			$ot_mrning = 0;
																		}
																	}
																	else
																	{
																		echo ' ';
																		$ot_hr_morning = 0;
																		$ot_mrning = 0;
																	}
																?>
																<input type="hidden" name="ot_morning[]" value="<?php echo $ot_mrning; ?>">
															</td>
															<td>
																<?php
																	//COMPUTATION OF OT NIGHT !
																	if($week_date == 1 || $week_date == 2 || $week_date == 3 || $week_date == 4)
																	{
																		if($total_in_daily == 0 && $total_out_daily == 0)
																		{
																			$ot_nght = 0;
																			echo ' ';
																		}
																		elseif($total_out_daily >= $total_out_min && $date_date_in == $date_date_out) 
																		{
																			$late_hr = intval($total_out_daily - $total_out_min);
																			$hr_diff = intval($late_hr/60);
																			$min_diff = intval($late_hr%60);
																			$hrs1 = sprintf("%02d", $min_diff);
																			echo $hr_diff.".".$hrs1."";
																			$number_ot_night = $hr_diff.".".$min_diff;
																			$ot_nght = $late_hr;

																		}	
																		elseif($date_date_in != $date_date_out && $ot_hr_morning == 0)
																		{
																			
																			$explode_ot_morning_hr_min = explode(".", $number_ot_morning);
																			$explode_total_hrs = explode(".", $number_hr_daily);
																			$total_hrs = $explode_total_hrs[0];
																			$total_mins = $explode_total_hrs[1];
																			$total_hr = $explode_ot_morning_hr_min[0];
																			$total_min = $explode_ot_morning_hr_min[1];
																			
																			$total_in_and_out = $total_out_min - $total_in_min ;
																			$total_ot_morning_min = intval($total_hr*60) + $total_min; // OT MORNING
																			$total_daily_min = intval($total_hrs*60) + $total_mins; // TOTAL HRS CONVERT TO MINS

																			$compute_total_in_and_ot_morning = $total_in_and_out + $total_ot_morning_min;
																			$total_ot_hrs = $total_daily_min - $compute_total_in_and_ot_morning;

																			$hr_ot_diff = intval($total_ot_hrs/60);
																			$min_ot_diff = intval($total_ot_hrs%60);
																			$hrs1 = sprintf("%02d", $min_ot_diff);
																			$total_ot_night = $hr_ot_diff.".".$hrs1."";
																			echo $total_ot_night;
																			$ot_nght = $total_ot_hrs;
																			
																		}
																		elseif($date_date_in != $date_date_out && $ot_hr_morning != 0)
																		{
																			$explode_ot_morning_hr_min = explode(".", $number_ot_morning);
																			$explode_total_hrs = explode(".", $number_hr_daily);
																			$total_hrs = $explode_total_hrs[0];
																			$total_mins = $explode_total_hrs[1];
																			$total_hr = $explode_ot_morning_hr_min[0];
																			$total_min = $explode_ot_morning_hr_min[1];
																			
																			$total_in_and_out = $total_out_min - $total_in_min ;
																			$total_ot_morning_min = intval($total_hr*60) + $total_min; // OT MORNING
																			$total_daily_min = intval($total_hrs*60) + $total_mins; // TOTAL HRS CONVERT TO MINS

																			$compute_total_in_and_ot_morning = $total_in_and_out + $total_ot_morning_min;
																			$total_ot_hrs = $total_daily_min - $compute_total_in_and_ot_morning;

																			$hr_ot_diff = intval($total_ot_hrs/60);
																			$min_ot_diff = intval($total_ot_hrs%60);
																			$hrs1 = sprintf("%02d", $min_ot_diff);
																			$total_ot_night = $hr_ot_diff.".".$hrs1."";
																			echo $total_ot_night;
																			$ot_nght = $total_ot_hrs;
																		}
																		else
																		{
																			echo 0 ;
																			$ot_nght = 0;
																		}
																	}	
																	elseif($week_date == 5)
																	{
																		if($total_in_daily == 0 && $total_out_daily == 0)
																		{
																			$ot_nght = 0;
																			echo ' ';
																		}
																		elseif($total_out_daily >= $total_friday_out_min && $date_date_in == $date_date_out) 
																		{
																			$late_hr = intval($total_out_daily - $total_friday_out_min);
																			$hr_diff = intval($late_hr/60);
																			$min_diff = intval($late_hr%60);
																			$hrs1 = sprintf("%02d", $min_diff);
																			echo $hr_diff.".".$hrs1."";
																			$number_ot_night = $hr_diff.".".$min_diff;
																			$ot_nght = $late_hr;
																		}	
																		elseif($date_date_in != $date_date_out && $ot_hr_morning == 0)
																		{
																			$explode_ot_morning_hr_min = explode(".", $number_ot_morning);
																			$explode_total_hrs = explode(".", $number_hr_daily);
																			$total_hrs = $explode_total_hrs[0];
																			$total_mins = $explode_total_hrs[1];
																			$total_hr = $explode_ot_morning_hr_min[0];
																			$total_min = $explode_ot_morning_hr_min[1];
																			
																			$total_in_and_out = $total_friday_out_min - $total_in_min ;
																			$total_ot_morning_min = intval($total_hr*60) + $total_min; // OT MORNING
																			$total_daily_min = intval($total_hrs*60) + $total_mins; // TOTAL HRS CONVERT TO MINS

																			$compute_total_in_and_ot_morning = $total_in_and_out + $total_ot_morning_min;
																			$total_ot_hrs = $total_daily_min - $compute_total_in_and_ot_morning;

																			$hr_ot_diff = intval($total_ot_hrs/60);
																			$min_ot_diff = intval($total_ot_hrs%60);
																			$hrs1 = sprintf("%02d", $min_ot_diff);
																			$total_ot_night = $hr_ot_diff.".".$hrs1."";
																			echo $total_ot_night;
																			$ot_nght = $total_ot_hrs;
																		}
																			elseif($date_date_in != $date_date_out && $ot_hr_morning != 0)
																		{
																			$explode_ot_morning_hr_min = explode(".", $number_ot_morning);
																			$explode_total_hrs = explode(".", $number_hr_daily);
																			$total_hrs = $explode_total_hrs[0];
																			$total_mins = $explode_total_hrs[1];
																			$total_hr = $explode_ot_morning_hr_min[0];
																			$total_min = $explode_ot_morning_hr_min[1];
																			
																			$total_in_and_out = $total_friday_out_min - $total_in_min ;
																			$total_ot_morning_min = intval($total_hr*60) + $total_min; // OT MORNING
																			$total_daily_min = intval($total_hrs*60) + $total_mins; // TOTAL HRS CONVERT TO MINS

																			$compute_total_in_and_ot_morning = $total_in_and_out + $total_ot_morning_min;
																			$total_ot_hrs = $total_daily_min - $compute_total_in_and_ot_morning;

																			$hr_ot_diff = intval($total_ot_hrs/60);
																			$min_ot_diff = intval($total_ot_hrs%60);
																			$hrs1 = sprintf("%02d", $min_ot_diff);
																			$total_ot_night = $hr_ot_diff.".".$hrs1."";
																			echo $total_ot_night;
																			$ot_nght = $total_ot_hrs;
																		}
																		else
																		{
																			echo 0 ;
																			$ot_nght = 0;
																		}
																	}
																	elseif($week_date == 6)
																	{
																		$ot_hr = intval($total_out_daily - $total_in_daily);
																		$hr_diff = intval($ot_hr/60);
																		$min_diff = intval($ot_hr%60);
																		$hrs1 = sprintf("%02d", $min_diff);
																		echo $hr_diff.".".$hrs1."";
																		$ot_nght = $ot_hr;
																	}
																	else
																	{
																		$ot_hr = intval($total_out_daily - $total_in_daily);
																		$hr_diff = intval($ot_hr/60);
																		$min_diff = intval($ot_hr%60);
																		$hrs1 = sprintf("%02d", $min_diff);
																		echo $hr_diff.".".$hrs1."";
																		$ot_nght = $ot_hr;
																	}
																?>
																<input type="hidden" name="ot_night[]" value="<?php echo $ot_nght; ?>">
															</td>
															<td>
																<?php
																	//NIGHT DIFF
																	$set_night_diff_morning = '6:00';
																	$set_night_diff = '22:00';
																	$explode_night_diff_morning = explode(':', $set_night_diff_morning);
																	$explode_night_diff = explode(':', $set_night_diff);
																	$night_diff_morning = intval($explode_night_diff_morning[0]*60);
																	$night_diff= intval($explode_night_diff[0]*60);
																	$compute_night_diff =$total_out_daily - $night_diff;
																	$compute_night_diff_morning = $night_diff_morning - $total_in_daily;

																	if($total_in_daily == 0 && $total_out_daily == 0)
																	{
																		$nd = 0 ;
																		echo 0;
																	}
																	elseif($night_diff_morning < $total_in_daily && $total_out_daily < $night_diff)
																	{
																		$nd = 0 ;
																		echo 0;
																	}
																	elseif($night_diff_morning > $total_in_daily)	
																	{
																		$compute_night_diff_morning;
																		$hr_diff = intval($compute_night_diff_morning/60);
																		$min_diff = intval($compute_night_diff_morning%60);
																		
																		if($min_diff > 30 || $min_diff == 0)
																		{
																			$nd = $hr_diff."."."30";
																			echo $hr_diff."."."30";
																		}
																		elseif($min_diff < 30)
																		{
																			$nd = $hr_diff;
																			echo $hr_diff;
																		}
																	}		
																	elseif($total_out_daily > $night_diff)	
																	{
																		$compute_night_diff;
																		$hr_diff = intval($compute_night_diff/60);
																		$min_diff = intval($compute_night_diff%60);
																		
																		if($min_diff > 30 || $min_diff == 0)
																		{
																			$nd = $hr_diff."."."30";
																			echo $hr_diff."."."30";
																		}
																		elseif($min_diff < 30)
																		{
																			$nd = $hr_diff;
																			echo $hr_diff;
																		}
																	}																					
																?>
																<input type="hidden" name="nd[]" value="<?php echo $nd; ?>">
															</td>
															<td>
																<?php
																	//BreakTime
																	if($total_in_daily == 0 && $total_out_daily == 0)
																	{
																		$breaktime = 0;
																		echo $breaktime;
																	}
																	else
																	{
																		$breaktime = 1;
																		echo $breaktime;
																	}
																?>
																<input type="hidden" name="breaktime[]" value="<?php echo $breaktime; ?>" >
															</td>
															<td>
																<?php
																	//SATURDAY Hours
																	if($total_in_daily == 0 && $total_out_daily == 0)
																	{
																		echo 0;
																	}
																	elseif($week_date != 5 && $week_date != 6 && $week_date != 0)
																	{
																		echo 1;
																	} 
																	
																?>
															</td>
															
															<td>
																<?php if(isset($remarks)) : ?>
												          <?php foreach($remarks as $remark) : ?>
												          	<?php 
												          		if($remark->date == $emp->dates && $remark->remarks_employee_number == $emp->employee_number)
												          		{
												          			echo '<center>'. $remark->type_name .'</center>';
												          		}
																			else
																			{
																				echo ' ';
																			}	
																		?>
												          <?php endforeach; ?>
												        <?php endif; ?>  	
															</td>
															<!--
															<td>
																<?php if(isset($rots)) : ?>
																	<?php foreach($rots as $rot) : ?>
																		<?php 
																			if($rot->ot_employee_number == $emp->employee_number && $rot->date_ot == $emp->dates)
																			{
																				$reg_ot = $rot->total_ot;
																				$hr_diff = intval($reg_ot/60);
																				$min_diff = intval($reg_ot%60);
																				//$min_diff1 = sprintf("%02d", $min_diff);
																				echo $hr_diff. "." . $min_diff;
																			}
																		?>
																	<?php endforeach; ?>	
																<?php endif; ?>
																
															</td>
															<td>
																<?php if(isset($lots)) : ?>
																	<?php foreach($lots as $lot) : ?>
																		<?php 
																			if($lot->legal_ot_employee_number == $emp->employee_number && $lot->date_ot == $emp->dates)
																			{
																				$reg_ot = $lot->total_ot;
																				$hr_diff = intval($reg_ot/60);
																				$min_diff = intval($reg_ot%60);
																				//$min_diff1 = sprintf("%02d", $min_diff);
																				echo $hr_diff. "." . $min_diff;
																			}
																		?>
																	<?php endforeach; ?>	
																<?php endif; ?>
																
															</td>
															<td>
																<?php if(isset($shots)) : ?>
																	<?php foreach($shots as $shot) : ?>
																		<?php 
																			if($shot->special_ot_employee_number == $emp->employee_number && $shot->date_ot == $emp->dates)
																			{
																				$reg_ot = $shot->total_ot;
																				$hr_diff = intval($reg_ot/60);
																				$min_diff = intval($reg_ot%60);
																				//$min_diff1 = sprintf("%02d", $min_diff);
																				echo $hr_diff. "." . $min_diff;
																			}
																		?>
																	<?php endforeach; ?>	
																<?php endif; ?>
																
															</td>
															<td>
																<?php if(isset($rdots)) : ?>
																	<?php foreach($rdots as $rdot) : ?>
																		<?php 
																			if($rdot->restday_ot_employee_number == $emp->employee_number && $rdot->date_ot == $emp->dates)
																			{
																				$reg_ot = $rdot->total_ot;
																				$hr_diff = intval($reg_ot/60);
																				$min_diff = intval($reg_ot%60);
																				//$min_diff1 = sprintf("%02d", $min_diff);
																				echo $hr_diff. "." . $min_diff;
																			}
																		?>
																	<?php endforeach; ?>	
																<?php endif; ?>
																
															</td>
															<td>
																<?php if(isset($vls)) : ?>
																	<?php foreach($vls as $vl) : ?>
																		<?php 
																			if($vl->slvl_employee_number == $emp->employee_number && $vl->vl_date == $emp->dates)
																			{
																				echo $vl->total_slvl;
																			}
																		?>
																	<?php endforeach; ?>	
																<?php endif; ?>
																
															</td>
															<td>
																<?php if(isset($sls)) : ?>
																	<?php foreach($sls as $sl) : ?>
																		<?php 
																			if($sl->slvl_employee_number == $emp->employee_number && $sl->sl_date == $emp->dates)
																			{
																				echo $sl->total_slvl;
																			}
																		?>
																	<?php endforeach; ?>	
																<?php endif; ?>
															</td>
															<td>
																<?php if(isset($abs)) : ?>
																	<?php foreach($abs as $ab) : ?>
																		<?php 
																			if($ab->slvl_employee_number == $emp->employee_number && $ab->ab_date == $emp->dates)
																			{
																				$ab1 = $ab->total_slvl;
																				$hr_diff = intval($ab1/60);
																				$min_diff = intval($ab1%60);
																				//$min_diff1 = sprintf("%02d", $min_diff);
																				echo $hr_diff. "." . $min_diff;
																			}
																		?>
																	<?php endforeach; ?>	
																<?php endif; ?>
															</td>
														
							    	 				</tr>-->
						    	 				<?php //endif; ?>
					    	 				<?php endforeach; ?>	
					    	 			<?php endif; ?>
					    			</table>
			      		</div>
			  			</div>  
			  			

				  			<?php if($hfpm_sl) : ?>
									<?php foreach($hfpm_sl as $hfpm) : ?>
										<input type="hidden" name="hfpm_employee_number[]" value="<?php echo $hfpm->hfpm_employee_number; ?>">
										<input type="hidden" name="hfpm_dates[]" value="<?php echo $hfpm->hfpm_dates; ?>">
										<?php 
											$explode = explode(" ", $hfpm->hfpm_time_out);
											$hfpm_time = $explode[1];
										?>
										<input type="hidden" name="hfpm_time_out[]" value="<?php echo $hfpm_time; ?>">
										<br>
									<?php endforeach; ?>	
								<?php endif; ?>	

								<?php if($hfam_sl) : ?>
									<?php foreach($hfam_sl as $hfam) : ?>
										<input type="hidden" name="hfam_employee_number[]" value="<?php echo $hfam->hfam_employee_number; ?>">
										<input type="hidden" name="hfam_dates[]" value="<?php echo $hfam->hfam_dates; ?>">
										<?php 
										$explode = explode(" ", $hfam->hfam_time_in);
										$hfam_time = $explode[1];
									?>
									<input type="hidden" name="hfam_time_in[]" value="<?php echo $hfam_time; ?>">
									<br>
								<?php endforeach; ?>	
							<?php endif; ?>	    
          </form>

     </div>            
  </div>
</div>
</div>
<script type="text/javascript" src="<?php echo base_url(); ?>/assets/js/jquery.min.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
		$('#processTime').click(function() {
			var a = confirm("Process Attendance?");
			if (a == true) {
				$('#timeForm').attr('action', 'process_time');
				$('#timeForm').submit();
			} else {
				return false;
			} 
		});
	});	
</script>




