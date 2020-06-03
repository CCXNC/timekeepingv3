<style type="text/css">
	.container {
		margin-top: 60px;
		width:95%;
		color: black;
	}
	.is-true { 
		background-color: #F9F7AD; 
	}
	.desslvl
	{
		float: right;
		margin-top: -30px;
		font-size:20px;
	}
</style>
<div class="container">
	<div class="col-sm-17">
		  <div class="panel panel-primary">
		    <div class="panel-heading"> 
		        <h3><?php echo $employee_name->name; ?></h3>
		        <div class="desslvl">
					SL:&nbsp;<?php echo $employee_leave->sl_credit; ?> &nbsp; VL:&nbsp;<?php echo $employee_leave->vl_credit; ?> &nbsp; EL:&nbsp;<?php echo $employee_leave->elcl_credit; ?> &nbsp; FL:&nbsp;<?php echo $employee_leave->fl_credit;?> &nbsp; A:&nbsp;<?php echo $employee_leave->absences;?>
				</div>
		        
		    </div>
		    <div class="panel-body">
				<a href="<?php echo base_url(); ?>index.php/reports/index_ob" class="btn btn-primary">OB</a>
				<a href="<?php echo base_url(); ?>index.php/reports/index_slvl" class="btn btn-primary">SL | VL</a>
				<a href="<?php echo base_url(); ?>index.php/reports/index_ot" class="btn btn-primary">OT</a>
				<a href="<?php echo base_url() ?>index.php/reports/adj_employee_time" class="btn btn-primary">Adjustment</a>
				<input class="btn btn-primary" id="processTime" type="submit" value="Process">
				<br><br>
		    <form id="timeForm" method="post">	
		    	<table class="table table-bordered table-hover table-striped cl">
            <thead>
              <tr> 
				<th>Days</th>
				<th>Date</th>
				<th>Time In</th>
				<th>Time Out</th>
				<th>Daily Hours</th>
				<th>Hours Late</th>
				<th>Undertime</th>
				<th>OT Morning</th>
				<th>OT</th>
				<th>ND</th>
				<th>Remarks</th>
              </tr>
	          </thead> 
	          	<?php $holiday_dates = ' '; ?>
					<?php if($holidays) : ?>
						<?php foreach($holidays as $holiday) : ?>
							<?php 
								if($holiday->branch_id == 'ALL' || $employee_type->branch_id == $holiday->branch_id) 
								{
									$holiday_dates = $holiday->dates;
								}
								
							?>	
						<?php endforeach; ?>	
					<?php endif; ?>	
		          	<?php if($schedules) : ?>
							<?php 
								$fixed_daily_in = $schedules->daily_in; 
								$fixed_daily_out = $schedules->daily_out; 
								$fixed_friday_out = $schedules->daily_friday_out; 
								$fixed_casual_in = $schedules->casual_in;
								$fixed_casual_out = $schedules->casual_out;
								$fixed_casual_friday_out = $schedules->casual_friday_out;
								$total_hr_lte = 0; 
								$total_undrtme = 0; 
								$total_ot_nght = 0; 
								$total_ot_mnng = 0; 
								$total_nd = 0; 
								$total_daily_hours = 0; 
								$total_sunday_date = 0; 
							?>
							<?php endif; ?>
						    <?php if($employee_time) : ?>
						 			<?php foreach($employee_time as $emp) : ?>
			 							<?php //if($emp->in_status != 'NO IN' || $emp->out_status != 'NO OUT'): ?>
			 								<input type="hidden" name="employee_number[]" value="<?php echo $emp->employee_number; ?>">
					 						<?php
												$in_office	= $fixed_daily_in; 
												$out_office   = $fixed_daily_out;
												$friday_out = $fixed_friday_out;
												$night_diff = '22:00';
												$in_daily = $emp->intime;
												$out_daily = $emp->outtime;
												$week_date = date('w', strtotime($emp->date)); // Convert in days . friday (5)

												//CASUAL COMPUTATION 
												$explode_casual_in = explode(":", $fixed_casual_in);
												$explode_casual_out = explode(":", $fixed_casual_out);
												$explode_friday_casual_out = explode(":", $fixed_casual_friday_out);
												// IN CASUAL
												$casual_in_hr = $explode_casual_in[0];
												$casual_in_mins = $explode_casual_in[1];
												$total_casual_in = intval(($casual_in_hr * 60) + $casual_in_mins);
												//OUT CASUAL
												$casual_out_hr = $explode_casual_out[0];
												$casual_out_mins = $explode_casual_out[1];
												$total_casual_out = intval(($casual_out_hr * 60) + $casual_out_mins);
												//OUT FRIDAY CASUAL
												$casual_friday_out_hr = $explode_friday_casual_out[0];
												$casual_friday_out_mins = $explode_friday_casual_out[1];
												$total_casual_friday_out = intval(($casual_friday_out_hr * 60) + $casual_friday_out_mins);
												$total_casual_in_min_grace = intval($casual_in_hr*60) + $casual_in_mins + 15;


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
												$total_in_min_grace = intval($time_in_hr*60) + $time_in_min; // DEFAULT IN WITH GRACE PERIOD CANCEL 15 MINS!
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
											<tr>
												<!-- DAYS -->	
			    	 							<td>
													<?php 
														$data_w = $emp->date;
														echo date('D', strtotime($emp->date));
													?>
													</td>
			    	 						<!-- DATES -->	
				    	 					<td>
				    	 						<?php 
				    	 							echo $emp->date;
				    	 							$con_dates = date('w', strtotime($emp->date));
				    	 							if($con_dates == '0')
				    	 							{
				    	 								$sunday_date =  1;
				    	 								$total_sunday_date += $sunday_date;
				    	 							}
				    	 						?>
				    	 						<input type="hidden" name="dates[]" value="<?php echo $emp->date; ?>">
								    	 		<input type="hidden" name="con_dates[]" value="<?php echo $con_dates; ?>">
				    	 					</td>
				    	 					<!-- time in -->
				    	 					<?php $start_halfday = 720; $end_halfday = 780; ?>
				    	 					<td <?php echo $total_in_daily >=  $start_halfday && $week_date != 5 && $holiday_dates != $date_date_in ? 'style="background-color:#e74c3c"' : $total_in_daily ==  0 && $week_date != 6 && $week_date != 0 ? 'style="background-color:#e74c3c"' : $total_in_daily >=  $start_halfday && $week_date == 5 && $holiday_dates != $date_date_in ? 'style="background-color:#e74c3c"' : ' '; ?> >
												<?php 
													$in = explode(" ",$emp->intime);
													echo $in[1];
												?>
												<input type="hidden" name="intime[]" value="<?php echo $emp->intime; ?>">
												</td>
												<!-- time out -->
												<td <?php echo $total_out_daily <  $total_out_min && $week_date != 5 && $week_date != 6 && $week_date != 0 && $holiday_dates != $date_date_in ? 'style="background-color:#e74c3c"' : $total_out_daily ==  0 && $week_date != 6 && $week_date != 0 ? 'style="background-color:#ccc"' : $total_out_daily < $total_friday_out_min && $week_date == 5 && $holiday_dates != $date_date_in ? 'style="background-color:#e74c3c"'  : ' '; ?> >
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
														$total_min_diff = intval($total_friday_out_min - $total_in_min - 60);
														$hr_diff = intval($total_min_diff/60);
														$min_diff = intval($total_min_diff%60);
														$hrs1 = sprintf("%02d", $min_diff);
														echo $hr_diff.".".$hrs1."";
														$dly_hrs = $total_min_diff;

													}	
													elseif($date_date_in == $date_date_out && $total_in_daily > $total_in_min_grace && $total_out_daily < $total_friday_out_min)
													{
														$total_min_diff = intval($total_out_daily - $total_in_daily - 60);
														$hr_diff = intval($total_min_diff/60);
														$min_diff = intval($total_min_diff%60);
														$hrs1 = sprintf("%02d", $min_diff);
														echo $hr_diff.".".$hrs1."";
														$dly_hrs = $total_min_diff;
													}
													elseif($date_date_in == $date_date_out && $total_in_daily > $total_in_min_grace )
													{
														$total_min_diff = intval($total_friday_out_min - $total_in_daily - 60);
														$hr_diff = intval($total_min_diff/60);
														$min_diff = intval($total_min_diff%60);
														$hrs1 = sprintf("%02d", $min_diff);
														echo $hr_diff.".".$hrs1."";
														$dly_hrs = $total_min_diff;
													}
													elseif($date_date_in == $date_date_out && $total_out_daily < $total_friday_out_min)
													{
														$total_min_diff = intval($total_out_daily - $total_in_min - 60);
														$hr_diff = intval($total_min_diff/60);
														$min_diff = intval($total_min_diff%60);
														$hrs1 = sprintf("%02d", $min_diff);
														echo $hr_diff.".".$hrs1."";
														$dly_hrs = $total_min_diff;
													}
													elseif($date_date_in != $date_date_out && $total_in_daily >= $total_in_min_grace)
													{
														$total_min_diff = intval($total_friday_out_min - $total_in_daily - 60);
														$hr_diff = intval($total_min_diff/60);
														$min_diff = intval($total_min_diff%60);
														$hrs1 = sprintf("%02d", $min_diff);
														echo $hr_diff.".".$hrs1."";
														$dly_hrs = $total_min_diff;
													}
													elseif($date_date_in != $date_date_out || $total_in_daily <= $total_in_min_grace)
													{
														$total_min_diff = intval($total_friday_out_min - $total_in_min - 60);
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
													$dly_hrs = 540;
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

												if($dly_hrs != 0)
												{
													$total_daily_hours += count($dly_hrs);
												}

												?>
												
												<input type="hidden" name="daily_hrs[]" value="<?php echo $dly_hrs; ?>">
											</td>

											<?php
													//COMPUTATION OF HOURS LATE !
													$halfday = 780;
													if($week_date == 1 || $week_date == 2 || $week_date == 3 || $week_date == 4)
													{
														if($total_in_daily == 0 && $total_out_daily == 0)
														{
															$hr_lte1 = 0;
															//echo ' ';
														}
														elseif($halfday <= $total_in_daily)
														{
															$late_hr = intval($total_in_daily - $halfday);
															$hr_diff = intval($late_hr/60);
															$min_diff = intval($late_hr%60);
															$hrs1 = sprintf("%02d", $min_diff);
															//echo $hr_diff.".".$hrs1."";
															$number_of_late = $hr_diff.".".$min_diff;
															$hr_lte1 = $late_hr;
														}
														elseif($start_halfday <= $total_in_daily)
														{
															//echo 0;
															$hr_lte1 = 0;
														}
														elseif($holiday_dates == $date_date_in)
														{
															$hr_lte1 = 0;
															//echo 0 ;
														}
														else
														{
															if($total_in_daily > $total_in_min_grace && $emp->employee_number != 10195)
															{
																$late_hr = intval($total_in_daily - $total_in_min);
																$hr_diff = intval($late_hr/60);
																$min_diff = intval($late_hr%60);
																$hrs1 = sprintf("%02d", $min_diff);
																//echo $hr_diff.".".$hrs1."";
																$number_of_late = $hr_diff.".".$min_diff;
																$hr_lte1 = $late_hr;
															}
															elseif($total_in_daily > $total_casual_in_min_grace && $emp->employee_number == 10195)
															{
																$late_hr = intval($total_in_daily - $total_casual_in);
																$hr_diff = intval($late_hr/60);
																$min_diff = intval($late_hr%60);
																$hrs1 = sprintf("%02d", $min_diff);
																//echo $hr_diff.".".$hrs1."";
																$number_of_late = $hr_diff.".".$min_diff;
																$hr_lte1 = $late_hr;
															}
															else
															{
																//echo 0;
																$hr_lte1 = 0;
															}
														}
													}	
													elseif($week_date == 5)
													{
														if($total_in_daily == 0 && $total_out_daily == 0)
														{
															$hr_lte1 = 0;
															//echo ' ';
														}
														elseif($halfday <= $total_in_daily)
														{
															$late_hr = intval($total_in_daily - $halfday);
															$hr_diff = intval($late_hr/60);
															$min_diff = intval($late_hr%60);
															$hrs1 = sprintf("%02d", $min_diff);
															//echo $hr_diff.".".$hrs1."";
															$number_of_late = $hr_diff.".".$min_diff;
															$hr_lte1 = $late_hr;
														}
														elseif($start_halfday <= $total_in_daily)
														{
															//echo 0;
															$hr_lte1 = 0;
														}
														elseif($holiday_dates == $date_date_in)
														{
															$hr_lte1 = 0;
															//echo 0 ;
														}
														else
														{
															if($total_in_daily > $total_in_min_grace && $emp->employee_number != 10195)
															{
																$late_hr = intval($total_in_daily - $total_in_min);
																$hr_diff = intval($late_hr/60);
																$min_diff = intval($late_hr%60);
																$hrs1 = sprintf("%02d", $min_diff);
																//echo $hr_diff.".".$hrs1."";
																$number_of_late = $hr_diff.".".$min_diff;
																$hr_lte1 = $late_hr;
															}
															elseif($total_in_daily > $total_casual_in_min_grace && $emp->employee_number == 10195)
															{
																$late_hr = intval($total_in_daily - $total_casual_in);
																$hr_diff = intval($late_hr/60);
																$min_diff = intval($late_hr%60);
																$hrs1 = sprintf("%02d", $min_diff);
																//echo $hr_diff.".".$hrs1."";
																$number_of_late = $hr_diff.".".$min_diff;
																$hr_lte1 = $late_hr;
															}
															else
															{
																//echo 0;
																$hr_lte1 = 0;
															}
														}
														
													}
													else
													{
														//echo ' ';
														$hr_lte1 = 0;
													}
												?>

											<td <?php echo $hr_lte1 != 0 ? 'style="background-color:#e74c3c"' : ''; ?>>
												<?php
													//COMPUTATION OF HOURS LATE !
													$halfday = 780;
													if($week_date == 1 || $week_date == 2 || $week_date == 3 || $week_date == 4)
													{
														if($total_in_daily == 0 && $total_out_daily == 0)
														{
															$hr_lte = 0;
															$ttl_late_dy = 0;
															echo ' ';
														}
														elseif($halfday <= $total_in_daily)
														{
															$late_hr = intval($total_in_daily - $halfday);
															$hr_diff = intval($late_hr/60);
															$min_diff = intval($late_hr%60);
															$hrs1 = sprintf("%02d", $min_diff);
															echo $hr_diff.".".$hrs1."";
															$number_of_late = $hr_diff.".".$min_diff;
															$hr_lte = $late_hr;
															$ttl_late_dy = 1;
														}
														elseif($start_halfday <= $total_in_daily)
														{
															echo 0;
															$hr_lte = 0;
															$ttl_late_dy = 0;
														}
														elseif($holiday_dates == $date_date_in)
														{
															$hr_lte = 0;
															$ttl_late_dy = 0;
															echo 0 ;
														}
														else
														{
															if($total_in_daily > $total_in_min_grace && $emp->employee_number != 10195)
															{
																$late_hr = intval($total_in_daily - $total_in_min);
																$hr_diff = intval($late_hr/60);
																$min_diff = intval($late_hr%60);
																$hrs1 = sprintf("%02d", $min_diff);
																echo $hr_diff.".".$hrs1."";
																$number_of_late = $hr_diff.".".$min_diff;
																$hr_lte = $late_hr;
																$ttl_late_dy = 1;
															}
															elseif($total_in_daily > $total_casual_in_min_grace && $emp->employee_number == 10195)
															{
																$late_hr = intval($total_in_daily - $total_casual_in);
																$hr_diff = intval($late_hr/60);
																$min_diff = intval($late_hr%60);
																$hrs1 = sprintf("%02d", $min_diff);
																echo $hr_diff.".".$hrs1."";
																$number_of_late = $hr_diff.".".$min_diff;
																$hr_lte = $late_hr;
																$ttl_late_dy = 1;
															}
															else
															{
																echo 0;
																$hr_lte = 0;
																$ttl_late_dy = 0;
															}
														}
													}	
													elseif($week_date == 5)
													{
														if($total_in_daily == 0 && $total_out_daily == 0)
														{
															$hr_lte = 0;
															$ttl_late_dy = 0;
															echo ' ';
														}
														elseif($halfday <= $total_in_daily)
														{
															$late_hr = intval($total_in_daily - $halfday);
															$hr_diff = intval($late_hr/60);
															$min_diff = intval($late_hr%60);
															$hrs1 = sprintf("%02d", $min_diff);
															echo $hr_diff.".".$hrs1."";
															$number_of_late = $hr_diff.".".$min_diff;
															$hr_lte = $late_hr;
															$ttl_late_dy = 1;
														}
														elseif($start_halfday <= $total_in_daily)
														{
															echo 0;
															$hr_lte = 0;
															$ttl_late_dy = 0;
														}
														elseif($holiday_dates == $date_date_in)
														{
															$hr_lte = 0;
															$ttl_late_dy = 0;
															echo 0 ;
														}
														else
														{
															if($total_in_daily > $total_in_min_grace && $emp->employee_number != 10195)
															{
																$late_hr = intval($total_in_daily - $total_in_min);
																$hr_diff = intval($late_hr/60);
																$min_diff = intval($late_hr%60);
																$hrs1 = sprintf("%02d", $min_diff);
																echo $hr_diff.".".$hrs1."";
																$number_of_late = $hr_diff.".".$min_diff;
																$hr_lte = $late_hr;
																$ttl_late_dy = 1;
															}
															elseif($total_in_daily > $total_casual_in_min_grace && $emp->employee_number == 10195)
															{
																$late_hr = intval($total_in_daily - $total_casual_in);
																$hr_diff = intval($late_hr/60);
																$min_diff = intval($late_hr%60);
																$hrs1 = sprintf("%02d", $min_diff);
																echo $hr_diff.".".$hrs1."";
																$number_of_late = $hr_diff.".".$min_diff;
																$hr_lte = $late_hr;
																$ttl_late_dy = 1;
															}
															else
															{
																echo 0;
																$hr_lte = 0;
																$ttl_late_dy = 0;
															}
														}
														
													}
													else
													{
														echo ' ';
														$hr_lte = 0;
														$ttl_late_dy = 0;
													}
													$total_hr_lte += $hr_lte;
												?>
												<input type="hidden" name="hours_late[]" value="<?php echo $hr_lte; ?>">
												<input type="hidden" name="total_late[]" value="<?php echo $ttl_late_dy; ?>">
											</td>
											<?php $ud_time = ' '; ?>
											<?php
												//COMPUTATION OF UNDERTIME ! 
												$halfday_ut = 720;
												$halfday_in = 780;
												if($week_date == 1 || $week_date == 2 || $week_date == 3 || $week_date == 4)
												{
													if($total_in_daily == 0 && $total_out_daily == 0)
													{
														$ud_time = 0;
														//echo ' ';
													}
													elseif($total_out_daily < $halfday_ut)
													{
														$undertime_hr = intval($halfday_ut - $total_out_daily);
														$hr_diff = intval($undertime_hr/60);
														$min_diff = intval($undertime_hr%60);
														$hrs1 = sprintf("%02d", $min_diff);
														//echo $hr_diff.".".$hrs1."";
														$number_of_undertime = $hr_diff.".".$min_diff;
														$ud_time = $undertime_hr;
													}
													elseif($total_out_min <= $total_out_daily)
													{
														//echo 0;
														$ud_time = 0;
													}
													elseif($holiday_dates == $date_date_in)
													{
														//echo 0 ;
														$ud_time = 0;
													}
													else
													{
														if($total_out_daily > $halfday_in && $emp->employee_number != 10195)
														{
															$undertime_hr = intval($total_out_min - $total_out_daily);
															$hr_diff = intval($undertime_hr/60);
															$min_diff = intval($undertime_hr%60);
															$hrs1 = sprintf("%02d", $min_diff);
															//echo $hr_diff.".".$hrs1."";
															$number_of_undertime = $hr_diff.".".$min_diff;
															$ud_time = $undertime_hr;
														}
														elseif($total_out_daily > $halfday_in && $emp->employee_number == 10195)
														{
															$undertime_hr = intval($total_casual_out - $total_out_daily);
															$hr_diff = intval($undertime_hr/60);
															$min_diff = intval($undertime_hr%60);
															$hrs1 = sprintf("%02d", $min_diff);
															//echo $hr_diff.".".$hrs1."";
															$number_of_undertime = $hr_diff.".".$min_diff;
															$ud_time = $undertime_hr;
														}
													}
												}
												elseif($week_date == 5)
												{
													if($total_in_daily == 0 && $total_out_daily == 0)
													{
														$ud_time = 0;
														//echo ' ';
													}
													elseif($total_friday_out_min <= $total_out_daily)
													{
														//echo 0;
														$ud_time = 0;
													}
													elseif($holiday_dates == $date_date_in)
													{
														//echo 0 ; 
														$ud_time = 0;
													}
													else
													{
														if($halfday_in < $total_out_daily && $emp->employee_number != 10195)
														{
															$undertime_hr = intval($total_friday_out_min - $total_out_daily);
															$hr_diff = intval($undertime_hr/60);
															$min_diff = intval($undertime_hr%60);
															$hrs1 = sprintf("%02d", $min_diff);
															//echo $hr_diff.".".$hrs1."";
															$number_of_undertime = $hr_diff.".".$min_diff; 
															$ud_time = $undertime_hr;
														}
														elseif($total_out_daily > $halfday_in && $emp->employee_number == 10195)
														{
															$undertime_hr = intval($total_casual_friday_out - $total_out_daily);
															$hr_diff = intval($undertime_hr/60);
															$min_diff = intval($undertime_hr%60);
															$hrs1 = sprintf("%02d", $min_diff);
															//echo $hr_diff.".".$hrs1."";
															$number_of_undertime = $hr_diff.".".$min_diff;
															$ud_time = $undertime_hr;
														}
													}
												}
												else
												{
													//echo ' ';
													$ud_time = 0;
												}
													 
												?>

											<td <?php echo $ud_time != 0 ? 'style="background-color:#e74c3c"' : ''; ?>
											>
												<?php
												//COMPUTATION OF UNDERTIME ! 
												$halfday_ut = 720;
												$halfday_in = 780; 
												if($week_date == 1 || $week_date == 2 || $week_date == 3 || $week_date == 4)
												{
													if($total_in_daily == 0 && $total_out_daily == 0)
													{
														$ud_time = 0;
														echo ' ';
													}
													elseif($total_out_daily < $halfday_ut)
													{
														$undertime_hr = intval($halfday_ut - $total_out_daily);
														$hr_diff = intval($undertime_hr/60);
														$min_diff = intval($undertime_hr%60);
														$hrs1 = sprintf("%02d", $min_diff);
														echo $hr_diff.".".$hrs1."";
														$number_of_undertime = $hr_diff.".".$min_diff;
														$ud_time = $undertime_hr;
													}
													elseif($total_out_min <= $total_out_daily)
													{
														echo 0;
														$ud_time = 0;
													}
													elseif($holiday_dates == $date_date_in)
													{
														echo 0 ;
														$ud_time = 0;
													}
													else
													{
														if($total_out_daily > $halfday_in && $emp->employee_number != 10195)
														{
															$undertime_hr = intval($total_out_min - $total_out_daily);
															$hr_diff = intval($undertime_hr/60);
															$min_diff = intval($undertime_hr%60);
															$hrs1 = sprintf("%02d", $min_diff);
															echo $hr_diff.".".$hrs1."";
															$number_of_undertime = $hr_diff.".".$min_diff;
															$ud_time = $undertime_hr;
														}
														elseif($total_out_daily > $halfday_in && $emp->employee_number == 10195)
														{
															$undertime_hr = intval($total_casual_out - $total_out_daily);
															$hr_diff = intval($undertime_hr/60);
															$min_diff = intval($undertime_hr%60);
															$hrs1 = sprintf("%02d", $min_diff);
															echo $hr_diff.".".$hrs1."";
															$number_of_undertime = $hr_diff.".".$min_diff;
															$ud_time = $undertime_hr;
														}
													}
												}
												elseif($week_date == 5)
												{
													if($total_in_daily == 0 && $total_out_daily == 0)
													{
														$ud_time = 0;
														echo ' ';
													}
													elseif($total_friday_out_min <= $total_out_daily)
													{
														echo 0;
														$ud_time = 0;
													}
													elseif($holiday_dates == $date_date_in)
													{
														echo 0 ; 
														$ud_time = 0;
													}
													else
													{
														if($halfday_in < $total_out_daily && $emp->employee_number != 10195)
														{
															$undertime_hr = intval($total_friday_out_min - $total_out_daily);
															$hr_diff = intval($undertime_hr/60);
															$min_diff = intval($undertime_hr%60);
															$hrs1 = sprintf("%02d", $min_diff);
															echo $hr_diff.".".$hrs1."";
															$number_of_undertime = $hr_diff.".".$min_diff; 
															$ud_time = $undertime_hr;
														}
														elseif($total_out_daily > $halfday_in && $emp->employee_number == 10195)
														{
															$undertime_hr = intval($total_casual_friday_out - $total_out_daily);
															$hr_diff = intval($undertime_hr/60);
															$min_diff = intval($undertime_hr%60);
															$hrs1 = sprintf("%02d", $min_diff);
															echo $hr_diff.".".$hrs1."";
															$number_of_undertime = $hr_diff.".".$min_diff;
															$ud_time = $undertime_hr;
														}
													}
												}
												else
												{
													echo ' ';
													$ud_time = 0;
												}
												 $total_undrtme += $ud_time; 
													 
												?>
												<input type="hidden" name="undertime[]" value="<?php echo $ud_time; ?>">
											</td>

											<?php 
												if($week_date == 1 || $week_date == 2 || $week_date == 3 || $week_date == 4)
												{
													if($total_in_daily == 0 && $total_out_daily == 0)
													{
														$ot_mrning = 0;
														//echo ' ';
													}
													elseif($total_in_daily <= $total_in_min)
													{
														$ot_hr_morning = intval($total_in_min - $total_in_daily);
														$hr_diff = intval($ot_hr_morning/60);
														$min_diff = intval($ot_hr_morning%60);
														$hrs1 = sprintf("%02d", $min_diff);
														//echo $hr_diff.".".$hrs1."";
														$number_ot_morning = $hr_diff.".".$min_diff;
														$ot_mrning = $ot_hr_morning;
													} 
													else
													{
														//echo 0;
														$ot_hr_morning = 0;
														$ot_mrning = 0;
													}
												}
												elseif($week_date == 5)
												{
													if($total_in_daily == 0 && $total_out_daily == 0)
													{
														$ot_mrning = 0;
														//echo ' ';
													}
													elseif($total_in_daily <= $total_in_min)
													{
														$ot_hr_morning = intval($total_in_min - $total_in_daily);
														$hr_diff = intval($ot_hr_morning/60);
														$min_diff = intval($ot_hr_morning%60);
														$hrs1 = sprintf("%02d", $min_diff);
														//echo $hr_diff.".".$hrs1."";
														$number_ot_morning = $hr_diff.".".$min_diff;
														$ot_mrning = $ot_hr_morning;
													} 
													else
													{
														//echo 0;
														$ot_hr_morning = 0;
														$ot_mrning = 0;
													}
												}
												else
												{
													//echo ' ';
													$ot_hr_morning = 0;
													$ot_mrning = 0;
												}
											?>

											<td <?php echo $ot_mrning != 0 && $ot_mrning > 60 ? 'style="background-color:#87CEFA"' : ''; ?>
											>
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
													$total_ot_mnng += $ot_mrning;
												?>
												<input type="hidden" name="ot_morning[]" value="<?php echo $ot_mrning; ?>">
											</td>

											<?php
												if($week_date == 1 || $week_date == 2 || $week_date == 3 || $week_date == 4)
													{
														if($total_in_daily == 0 && $total_out_daily == 0)
														{
															$ot_nght = 0;
															//echo ' ';
														}
														elseif($total_out_daily >= $total_out_min && $emp->employee_number != 10195) 
														{
															$late_hr = intval($total_out_daily - $total_out_min);
															$hr_diff = intval($late_hr/60);
															$min_diff = intval($late_hr%60);
															$hrs1 = sprintf("%02d", $min_diff);
															//echo $hr_diff.".".$hrs1."";
															$number_ot_night = $hr_diff.".".$min_diff;
															$ot_nght = $late_hr;

														}	
														elseif($total_out_daily >= $total_casual_out && $emp->employee_number == 10195) 
														{
															$late_hr = intval($total_out_daily - $total_casual_out);
															$hr_diff = intval($late_hr/60);
															$min_diff = intval($late_hr%60);
															$hrs1 = sprintf("%02d", $min_diff);
															//echo $hr_diff.".".$hrs1."";
															$number_ot_night = $hr_diff.".".$min_diff;
															$ot_nght = $late_hr;

														}	
														elseif($holiday_dates == $date_date_in)
														{
															$ot_hr = intval($total_out_daily - $total_in_daily);
															$hr_diff = intval($ot_hr/60);
															$min_diff = intval($ot_hr%60);
															$hrs1 = sprintf("%02d", $min_diff);
															//echo $hr_diff.".".$hrs1."";
															$ot_nght = $ot_hr;
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
															//echo $total_ot_night;
															$ot_nght = $total_ot_hrs;
														}
														else
														{
															//echo 0 ;
															$ot_nght = 0;
														}
													}	
													elseif($week_date == 5)
													{
														if($total_in_daily == 0 && $total_out_daily == 0)
														{
															$ot_nght = 0;
															//echo ' ';
														}
														elseif($total_out_daily >= $total_friday_out_min && $emp->employee_number != 10195) 
														{
															$late_hr = intval($total_out_daily - $total_friday_out_min);
															$hr_diff = intval($late_hr/60);
															$min_diff = intval($late_hr%60);
															$hrs1 = sprintf("%02d", $min_diff);
															//echo $hr_diff.".".$hrs1."";
															$number_ot_night = $hr_diff.".".$min_diff;
															$ot_nght = $late_hr;
														}	
														elseif($total_out_daily >= $total_casual_friday_out && $emp->employee_number == 10195) 
														{
															$late_hr = intval($total_out_daily - $total_casual_friday_out);
															$hr_diff = intval($late_hr/60);
															$min_diff = intval($late_hr%60);
															$hrs1 = sprintf("%02d", $min_diff);
															//echo $hr_diff.".".$hrs1."";
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
															//echo $total_ot_night;
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
															//echo $total_ot_night;
															$ot_nght = $total_ot_hrs;
														}
														else
														{
															//echo 0 ;
															$ot_nght = 0;
														}
													}
													elseif($week_date == 6)
													{
														$ot_hr = intval($total_out_daily - $total_in_daily);
														$hr_diff = intval($ot_hr/60);
														$min_diff = intval($ot_hr%60);
														$hrs1 = sprintf("%02d", $min_diff);
														//echo $hr_diff.".".$hrs1."";
														$ot_nght = $ot_hr;
													}
													else
													{
														$ot_hr = intval($total_out_daily - $total_in_daily);
														$hr_diff = intval($ot_hr/60);
														$min_diff = intval($ot_hr%60);
														$hrs1 = sprintf("%02d", $min_diff);
														//echo $hr_diff.".".$hrs1."";
														$ot_nght = $ot_hr;
													} 
											?>

											<td <?php echo $ot_nght != 0 && $ot_nght >= 60 ? 'style="background-color:#87CEFA"' : ' ';?>
											>
												<?php
													//COMPUTATION OF OT NIGHT !
													if($week_date == 1 || $week_date == 2 || $week_date == 3 || $week_date == 4)
													{
														if($total_in_daily == 0 && $total_out_daily == 0)
														{
															$ot_nght = 0;
															echo ' ';
														}
														elseif($total_out_daily >= $total_out_min && $emp->employee_number != 10195) 
														{
															$ot_hr = intval($total_out_daily - $total_out_min) + 1;
															$hr_diff = intval($late_hr/60);
															$min_diff = intval($late_hr%60);
															$hrs1 = sprintf("%02d", $min_diff);
															echo $hr_diff.".".$hrs1."";
															$number_ot_night = $hr_diff.".".$min_diff;
															$ot_nght = $ot_hr;
														}	
														elseif($total_out_daily >= $total_casual_out && $emp->employee_number == 10195) 
														{
															$ot_hr = intval($total_out_daily - $total_casual_out);
															$hr_diff = intval($late_hr/60);
															$min_diff = intval($late_hr%60);
															$hrs1 = sprintf("%02d", $min_diff);
															echo $hr_diff.".".$hrs1."";
															$number_ot_night = $hr_diff.".".$min_diff;
															$ot_nght = $ot_hr;
														}
														elseif($holiday_dates == $date_date_in)
														{
															$ot_hr = intval($total_out_daily - $total_in_daily);
															$hr_diff = intval($ot_hr/60);
															$min_diff = intval($ot_hr%60);
															$hrs1 = sprintf("%02d", $min_diff);
															echo $hr_diff.".".$hrs1."";
															$ot_nght = $ot_hr;
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
														elseif($total_out_daily >= $total_friday_out_min && $emp->employee_number != 10195) 
														{
															$late_hr = intval($total_out_daily - $total_friday_out_min) + 1;
															$hr_diff = intval($late_hr/60);
															$min_diff = intval($late_hr%60);
															$hrs1 = sprintf("%02d", $min_diff);
															echo $hr_diff.".".$hrs1."";
															$number_ot_night = $hr_diff.".".$min_diff;
															$ot_nght = $late_hr;
														}	
														elseif($total_out_daily >= $total_casual_friday_out && $emp->employee_number == 10195) 
														{
															$late_hr = intval($total_out_daily - $total_casual_friday_out);
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
													if($emp->employee_number == 10004 || $emp->employee_number == 10153 || $emp->employee_number == 10025 || $emp->employee_number == 10267)
													{
														$compute_night_diff =$total_out_daily - $night_diff + 1;
														$compute_night_diff_morning = $night_diff_morning - $total_in_daily;
													}
													else
													{
														$compute_night_diff =$total_out_daily - $night_diff;
														$compute_night_diff_morning = $night_diff_morning - $total_in_daily;
													}

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
													elseif($night_diff_morning > $total_in_daily && $total_out_daily < $night_diff)	
													{
														if($night_diff_morning > $total_out_daily)
														{
															$compute_night_diff_morning = $total_out_daily - $total_in_daily;
															$hr_diff = intval($compute_night_diff_morning/60);
															$min_diff = intval($compute_night_diff_morning%60);
														}
														else
														{
															$hr_diff = intval($compute_night_diff_morning/60);
															$min_diff = intval($compute_night_diff_morning%60);
														}
														//echo $compute_night_diff_morning;
														
														if($min_diff >= 30)
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
													elseif($total_out_daily > $night_diff && $night_diff_morning < $total_in_daily)	
													{

														$compute_night_diff;
														$hr_diff = intval($compute_night_diff/60);
														$min_diff = intval($compute_night_diff%60);
														
														if($min_diff >= 30)
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
													elseif($total_out_daily > $night_diff && $night_diff_morning > $total_in_daily)
													{
														$totl_mght_diff = $compute_night_diff_morning + $compute_night_diff;
														$hr_diff = intval($totl_mght_diff/60);
														$min_diff = intval($totl_mght_diff%60);
														if($min_diff >= 30)
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
												<?php $total_nd += $nd ; ?>
												<input type="hidden" name="nd[]" value="<?php echo $nd; ?>">
											</td>

						 					<td>
						 						<?php $date_remark = ''; ?>
												<?php if(isset($remarks)) : ?>
								          <?php foreach($remarks as $remark) : ?>
								          	<?php 
								          		if($remark->date == $emp->date && $remark->remarks_employee_number == $emp->employee_number)
								          		{
								          			echo '<b><center>'. $remark->type_name .'</b></center>';
								          			$date_remark = $remark->date;
								          		}
															else
															{
																echo ' ';
															}	
														?>
								          <?php endforeach; ?>
								        <?php endif; ?>  	
											</td>
										</tr>
									<?php endforeach; ?>	
			          <?php endif; ?>	
			        </form>  
	          	<tr>
	          		<td></td>
	          		<td></td>
	          		<td></td>
	          		<td></td>
	          		<td></td>
	          		<td>	
	          			<b>
	          				<?php 
		          				$hours = floor($total_hr_lte / 60);
								$minutes = $total_hr_lte % 60;
								$mins = sprintf("%02d", $minutes);
								echo $hours. '.' .$mins;
	          				?>
	          			</b>
	          		</td>
	          		<td>
	          			<b>
	          				<?php 
	          					$hours = floor($total_undrtme / 60);
								$minutes = $total_undrtme % 60;
								$mins = sprintf("%02d", $minutes);
								echo $hours. '.' .$mins;
	          				?>
	          			</b>	
	          		</td>
	          		<td></td>
	          		<td></td>
	          		<td>
	          			<b>
	          				<?php
		          			 	echo number_format($total_nd, 2); 
		          			?>
	          			</b>
	          		
	          		</td>
	          		<td></td>
	          	</tr>
		    	</table>	
	    	 	<hr>
	    	 	<?php $total_dp_sh_ab = 0; $total_dp_sh_cwwut = 0; $total_dp_sh_wcp = 0;?>
	    	 	<!-- HOLIDAYS -->
	    	 	<div class="panel panel-primary">
				  	<div class="panel-heading">
				  		<h3 class="panel-title">List of Holidays & Events</h3>
				  	</div>
				  	<div class="panel-body">
				  		<table class="table table-bordered table-hover table-striped">
				  			<thead>
				  				<tr>
				  					<th>Date</th>
				  					<th>Description</th>
				  					<th>Type</th>
				  				</tr>
				  			</thead>
				  			<thead>
				  				<?php $total_sh_day = 0; $total_lh_day = 0; ?>
			  					<?php if($holidays) : ?>
				  					<?php foreach($holidays as $holiday) : ?>
				  						<tr>
				  							<?php if($holiday->branch_id == 'ALL' || $employee_type->branch_id == $holiday->branch_id) : ?>
						  						<td>
						  							<?php 
						  								echo $holiday->dates;
						  						 		$w_holiday = date('w', strtotime($holiday->dates));
						  						 	?>
						  						</td>
						  						<td><?php echo $holiday->description; ?></td>
						  						<td>
						  							<?php 
						  							if($holiday->type == "SH")
						  							{
						  								echo 'SPECIAL HOLIDAY';
						  								$sh_day = 1;
						  								$total_sh_day += $sh_day;
						  							} 
						  							elseif($holiday->type == "LH")
						  							{
						  								echo 'LEGAL HOLIDAY';
						  								$lh_day = 1;
						  								$total_lh_day += $lh_day;
						  							}
						  							else
						  							{
						  								echo 'EVENTS';
						  							}
						  							?>
						  								
						  						</td>
					  							<?php 
					  							if($employee_type->type == 'DP' && $holiday->type == 'SH' && $holiday->dates != $date_remark) 
					  							{
					  								if($w_holiday == 5)
					  								{
					  									$dp_sh_ab = 1;
						  								$dp_sh_cwwut = 0;
						  								$total_dp_sh_ab +=$dp_sh_ab;
						  								$total_dp_sh_cwwut +=$dp_sh_cwwut;
					  								}
					  								elseif($w_holiday == 1 || $w_holiday == 2 || $w_holiday == 3 || $w_holiday == 4)
					  								{
					  									$dp_sh_ab = 1;
						  								$dp_sh_cwwut = 60;
						  								$total_dp_sh_ab +=$dp_sh_ab;
						  								$total_dp_sh_cwwut +=$dp_sh_cwwut;
					  								}
					  								else
					  								{
					  									$dp_sh_ab = 0;
						  								$dp_sh_cwwut = 0;
						  								$total_dp_sh_ab +=$dp_sh_ab;
						  								$total_dp_sh_cwwut +=$dp_sh_cwwut;
					  								}
					  							}
					  						
					  							elseif($employee_type->type == 'MP' && $holiday->type == 'SH')
					  							{
					  								$mp_sh_wcp = 1;
					  								$total_dp_sh_wcp += $mp_sh_wcp; 
					  							}

					  							?>
					  						<?php endif; ?>
				  						</tr>
				  					<?php endforeach; ?>	 
				  				<?php endif; ?>	
				  			</thead>
				  		</table>
				  	</div>
				  </div>

				  <div class="panel panel-primary">
				    <div class="panel-heading">
				        <h3 class="panel-title">SL / VL</h3>
				    </div>
				    <div class="panel-body">
				    	<table class="table table-bordered table-hover table-striped cl">
			    	 		<thead>
			    	 			<tr>
			    	 				<th>Day</th>
			    	 				<th>Effective Date</th>
			    	 				<th>Type</th>
			    	 				<th>Reason</th>
			    	 				<th>Status</th>
			    	 			</tr>
			    	 		</thead>
			    	 		<?php if($slvl) : ?>
			    	 			<?php foreach($slvl as $slvl) : ?>
				    	 			<tr>
									<td><?php echo date('D', strtotime($slvl->effective_date_start)); ?></td>
									<td><?php echo $slvl->effective_date_start; ?></td>
									<td>
								<?php
									if($slvl->sl_am_pm == 'HFAM')
									{ 
										echo $slvl->type_name . '    |    ' . ' (Halfday AM) '; 
									}
									elseif($slvl->sl_am_pm == 'HFPM')
									{
										echo $slvl->type_name . '    |    ' . ' (Halfday PM) '; 
									}
									elseif($slvl->sl_am_pm == 'ADJ')
									{
										echo $slvl->type_name . '    |    ' . ' (ADJ) '; 
									}
									else
									{
										echo $slvl->type_name . '    |    ' . ' (WD) '; 
									}	
				    	 		?>	
				    	 				 	
									</td>
									<td><?php echo $slvl->reason; ?></td>
									<td><?php echo $slvl->status; ?></td>
								</tr>
							<?php endforeach; ?>
						<?php endif; ?>	
			    	</table>
				    </div>
				  </div>

				  <!-- OB -->
				  <div class="panel panel-primary">
				    <div class="panel-heading">
				        <h3 class="panel-title">OB</h3>
				    </div>
				    <div class="panel-body">
				    	<table class="table table-bordered table-hover table-striped cl">
			    	 		<thead>
			    	 			<tr>
			    	 				<th>Days</th>
			    	 				<th>Date</th>
			    	 				<th>Type</th>
			    	 				<th>From</th>
		                <th>To</th>
		                <th>Status</th>
			    	 			</tr>
			    	 		</thead> 
			    	 		<?php if($ob) : ?>
			    	 			<?php foreach($ob as $ob) : ?>
				    	 			<tr>
				    	 				<td><?php echo date('D', strtotime($ob->date_ob)); ?></td>
				    	 				<td><?php echo $ob->date_ob; ?></td>
				    	 				<td>
										<?php 
											if($ob->type_ob == 'OUT')
											{
												echo 'OB (PM) OUT'; 
											}
											elseif($ob->type_ob == 'IN') 
											{
												echo 'OB (AM) IN'; 
											}
											elseif($ob->type_ob == 'WD')
											{
												echo 'WHOLEDAY'; 
											}
										?>
				    	 				</td>
				    	 				<td><?php echo $ob->site_from; ?></td>
				    	 				<td><?php echo $ob->site_to; ?></td>
				    	 				<td><?php echo $ob->remarks; ?></td>
				    	 			</tr>
			    	 			<?php endforeach; ?>
			    	 		<?php endif; ?>	
			    	 	</table>
				    </div>
				  </div>
					
					<!-- OT -->	    	  
	    	 	<hr>
	    	 	<?php $total_hrs_ot = 0; ?>
	    	 	<div class="panel panel-primary">
				    <div class="panel-heading">
				        <h3 class="panel-title">OT</h3>
				    </div>
				    <div class="panel-body">
				    	<table class="table table-bordered table-hover table-striped cl">
			    	 		<thead>
			    	 			<tr>
			    	 				<th>Days</th>
			    	 				<th>Type</th>
                    <th>Date</th>
                    <th>Time In</th>
                    <th>Time Out</th>
                    <th>Ot Hours</th>
                    <th>Nature Of Work</th>
                    <th>Status</th>
			    	 			</tr>
			    	 		</thead>
			    	 		<?php $ot_total_hrs = 0; ?>
			    	 		<?php if($ot) : ?>
			    	 			<?php foreach($ot as $ot) : ?>
				    	 		 <tr class="data" <?php echo $ot->red_mark_alert == 1 ? 'style="background-color:#e74c3c"' : ' '; ?>>
				    	 				<td><?php echo date('D', strtotime($ot->date_ot)); ?></td>
				    	 				<td><?php echo $ot->ot_type_name; ?></td>
				    	 				<td><?php echo $ot->date_ot; ?></td>
				    	 				<td><?php echo $ot->time_in; ?></td>
				    	 				<td><?php echo $ot->time_out; ?></td>
				    	 				<td>
				    	 					<?php
				    	 						$status_processed = $ot->tbl_ot_status;

				    	 						$ot_hrs = $ot->total_ot;
				    	 						$hours = floor($ot_hrs / 60);
													$minutes = $ot_hrs % 60;
													$ot_hrs1 = $hours. '.' .$minutes;
													$ot_total_hrs += $ot_hrs1; 
													echo $ot_hrs1;
				    	 				  ?>
				    	 				</td>

				    	 				<td><?php echo $ot->nature_of_work; ?></td>
				    	 				<td><?php echo $ot->tbl_ot_status; ?></td>
				    	 			</tr>
			    	 			<?php endforeach; ?>
			    	 		<?php endif; ?>	

			    	 	</table>
				    </div>
				  </div>
				  <!-- UNDERTIME -->
				  <div class="panel panel-primary">
				    <div class="panel-heading">
				        <h3 class="panel-title">UNDERTIME</h3>
				    </div>
				    <div class="panel-body">
				    	<table class="table table-bordered table-hover table-striped cl">
			    	 		<thead>
			    	 			<tr>
			    	 				<th>Days</th>
			    	 				<th>Date</th>
			    	 				<th>Time out</th>
			    	 				<th>Type</th>
			    	 				<th>UT No.</th>
			    	 				<th>Reason</th>
		                <th>Status</th>
			    	 			</tr>
			    	 		</thead> 
			    	 		<?php $undertime_total = 0; ?>
			    	 		<?php if($undertime) : ?>
			    	 			<?php foreach($undertime as $ut) : ?>
			    	 				<tr <?php echo $ut->is_correct == 0 ? 'style="background-color:#e74c3c"' : ' '; ?>>
			    	 					<td><?php echo date('D', strtotime($ut->date_ut)); ?></td>
				    	 				<td><?php echo $ut->date_ut; ?></td>
				    	 				<td><?php echo $ut->time_out; ?></td>
				    	 				<td><?php echo 'UNDERTIME'; ?></td>
				    	 				<td>
				    	 					<?php 
						    	 				$hr = floor($ut->ut_no / 60);
						    	 				$minutes = $ut->ut_no % 60;
													$mins = sprintf("%02d", $minutes); 
													echo $hr .".". $mins ;
					    	 				?>
				    	 				</td>
				    	 				<td><?php echo $ut->reason; ?></td>
				    	 				<td><?php echo $ut->status; ?></td>
			    	 				</tr>
			    	 			<?php endforeach; ?>
			    	 		<?php endif; ?>	
			    	 	</table>
				    </div>
				  </div>


				  <!-- TOTAL SUMMARY -->
				  <div class="panel panel-primary">
				    <div class="panel-heading">
				        <h3 class="panel-title">TOTAL SUMMARY</h3>
				    </div>
				    <div class="panel-body">
				    	<table class="table table-bordered table-hover table-striped cl">
			    	 		<thead>
			    	 			<tr>
			    	 				<th>ROT</th>
			    	 				<th>LH OT</th>
			    	 				<th>SH OT</th>
			    	 				<th>RD</th>
			    	 				<th>RD OT</th>
			    	 				<th>Night Diff</th>
			    	 				<th>Tardiness</th>
			    	 				<th>Undertime</th>
			    	 				<!--<th>CWWUT</th>-->
			    	 				<th>Absences</th>
			    	 				<th>VL</th>
			    	 				<th>SL</th>
			    	 				<?php if($employee_type->type == 'MP') : ?>
		    	 						<th>SH W/PAY</th>
		    	 					<?php endif; ?>
		    	 					<?php if($employee_type->type == 'DP') : ?>
			    	 					<th>No. Work Days</th>
			    	 				<?php endif; ?>	
			    	 			</tr>
			    	 		</thead>
			    	 			<tr>
			    	 				<!-- SREGULAR OT -->	
			    	 				<td>
			    	 					<b>
			    	 						<?php if($ot_total) : ?>
			    	 							<?php foreach($ot_total as $ot_total) : ?>
			    	 								<?php 
				    	 								$hours = floor($ot_total->total_ot / 60);
															$minutes = $ot_total->total_ot % 60;
															echo $hours. '.' .$minutes;
			    	 								?>
			    	 							<?php endforeach; ?>
			    	 						<?php endif; ?>	
			    	 					</b>
			    	 				</td>

			    	 				<!-- LEGAL HOLIDAY OT -->	
			    	 				<td>
			    	 					<b>
			    	 						<?php if($lhot_total) : ?>
			    	 							<?php foreach($lhot_total as $lhot_total) : ?>
			    	 								<?php 
				    	 								$hours = floor($lhot_total->total_ot / 60);
															$minutes = $lhot_total->total_ot % 60;
															echo $hours. '.' .$minutes;
			    	 								?>
			    	 							<?php endforeach; ?>
			    	 						<?php endif; ?>	
			    	 					</b>
			    	 				</td>

			    	 				<!-- SPECIAL HOLIDAY OT -->	
			    	 				<td>
			    	 					<b>
			    	 						<?php if($shot_total) : ?>
			    	 							<?php foreach($shot_total as $shot_total) : ?>
			    	 								<?php 
				    	 								$hours = floor($shot_total->total_ot / 60);
															$minutes = $shot_total->total_ot % 60;
															echo $hours. '.' .$minutes;
			    	 								?>
			    	 							<?php endforeach; ?>
			    	 						<?php endif; ?>	
			    	 					</b>
			    	 				</td>

			    	 				<!-- RESTDAY-->	
			    	 				<td>
			    	 					
			    	 				</td>

			    	 				<!-- REST DAY OT -->	
			    	 				<td>
			    	 					<b>
			    	 						<?php if($rdot_total) : ?>
			    	 							<?php foreach($rdot_total as $rdot_total) : ?>
			    	 								<?php 
				    	 								$hours = floor($rdot_total->total_ot / 60);
															$minutes = $rdot_total->total_ot % 60;
															echo $hours. '.' .$minutes;
			    	 								?>
			    	 							<?php endforeach; ?>
			    	 						<?php endif; ?>	
			    	 					</b>
			    	 				</td>

			    	 				<!-- NIGHT DIFF -->	
			    	 				<td>
			    	 					<b>
			    	 						<?php
				          			 echo number_format($total_nd, 2); 
				          			?>
			    	 					</b>
			    	 				</td>

			    	 				<!-- TARDINESS -->	
			    	 				<td>
			    	 					<b>
			          				<?php 
				          				$hours = floor($total_hr_lte / 60);
										$minutes = $total_hr_lte % 60;
										$mins = sprintf("%02d", $minutes);
										echo $hours. '.' .$mins;
			          				?>
			          			</b>
				          	</td>

				          	<!-- UNDERTIME -->	
				          	<td>
			    	 			<b>
									<?php if($ut_total) : ?>
										<?php foreach($ut_total as $ut) : ?>
											<?php 
												$hours = floor($ut->total_undertime / 60);
												$minutes = $ut->total_undertime % 60;
												echo $hours. '.' .$minutes;
											?>
										<?php endforeach; ?>
									<?php endif; ?>	
			          			</b>
				          	</td>

				          	<!-- CCWUT -->	
				          	<!--<td>
				          		<b>
				          			<?php //$total_cwwut = 0; ?>
				          			<?php //if($cwwut) : ?>
				          				<?php //foreach($cwwut as $cwwut) : ?>
				          					<?php 
				          						//$cwwut = $cwwut->undertime_hr; 
				          						//$total_cwwut = $total_dp_sh_cwwut + $cwwut;

				          					?>
				          				<?php //endforeach; ?>
				          			<?php //endif; ?>	
				          			<?php 	
				          				//$hours = floor($total_cwwut / 60);
										//$minutes = $total_cwwut % 60;
										//$hr_mins_cwwut = $hours. '.' .$minutes; 
										//echo $hr_mins_cwwut;
									?>
				          		</b>
				          	</td>-->

				          	<!-- ABSENCES -->	
				          	<td>
				          		<b>
				          			<?php $hr_mins_ab = 0; ?>
				          			<?php if($ab_total) : ?>
					          			<?php foreach($ab_total as $ab_total) : ?>
					          				<?php 
															$hr_mins_ab = $ab_total->total_slvl; 
					          				?>
					          			<?php endforeach; ?>	
					          		<?php endif;  ?>
					          		<?php 
					          			$total_absences = $hr_mins_ab + $total_dp_sh_ab;
					          			echo $total_absences; 
					          		?>
				          		</b>
				          	</td>

				          	<!-- VACATION LEAVE -->	
				          	<td>
				          		<b>
				          			<?php if($vl_total) : ?>
					          			<?php foreach($vl_total as $vl_total) : ?>
					          				<?php echo $vl_total->total_slvl; ?>
					          			<?php endforeach; ?>	
					          		<?php endif;  ?>
				          		</b>
				          	</td>

				          	<!-- SICK LEAVE -->	
				          	<td>
				          		<b>
				          			<?php if($sl_total) : ?>
					          			<?php foreach($sl_total as $sl_total) : ?>
					          				<?php echo $sl_total->total_slvl ; ?>
					          			<?php endforeach; ?>	
					          		<?php endif;  ?>
				          		</b>
				          	</td>

				          		<!-- SPECIAL HOLIDAY W/PAY -->
				          		<?php if($employee_type->type == 'MP') : ?>	
				          			<td>
						          		<b>
						          			<?php foreach($sh_total as $sh_total) : ?>
					          					<?php //echo $sh_total->total_slvl; ?>
					          				<?php endforeach; ?>	
					          				<?php echo $total_dp_sh_wcp; ?>
						          		</b>
						          	</td>	
				          		<?php endif; ?>
				          	

				          	<!-- TOTAL DAILY HOURS-->
				          	<?php if($employee_type->type == 'DP') : ?>	
					          	<td>
					          		<b>
					          			<?php if($cut_off) : ?>
					          				<?php 
					          					$total_work_days = $cut_off->total_days - $total_sunday_date;
					          					$lh_plus_daily_hours = $total_absences + $total_lh_day; 
					          					$total_work_days_emp = $total_work_days - $lh_plus_daily_hours; 
						          			 	echo $total_work_days_emp . '/' . $total_work_days; 
						          			?>
						          		<?php endif; ?>
					          		</b>
					          	</td>
					          <?php endif; ?>	

			    	 			</tr>
			    	 	</table>
				    </div>
				  </div>
		    </div>
		  </div>
	  </div>
</div>

<script type="text/javascript" src="<?php echo base_url(); ?>/assets/js/jquery.min.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
		$('#processTime').click(function() {
			var a = confirm("Process Employee Attendance?");
			if (a == true) {
				$('#timeForm').attr('action', 'process_employee_time');
				$('#timeForm').submit();
			} else {
				return false;
			} 
		});
	});	
</script>