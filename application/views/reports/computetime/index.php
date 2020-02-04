<style type="text/css">
	.container{
		margin-top: 70px;
		width: 99%;
		color: black;
	}
	p{ 
		font-size: 24px;
		font-family: century gothic;
	} 
	.table {
		width: auto;
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
	      </div>	
	        <form id="timeForm" method="post">
					    <div class="panel-body">
					      <div class="table-responsive">
					          <table class="table table-bordered table-hover table-striped cl" id='tbl'>
					          	<div class="row">  	
							          	<div class="col-md-2">
									          <div class="form-group">
									              <label for="form_name">CUT OFF DATE</label>
									              <input id="form_name" type="text" name="start_date" class="form-control" readonly="" value=" <?php echo $cut_off->start_date .'|'. $cut_off->end_date; ?>">
									          </div>
									        </div>
						              <input id="form_name" type="hidden" name="start_date" class="form-control" readonly="" value=" <?php echo $cut_off->start_date; ?>">
						              <input id="form_name" type="hidden" name="end_date" class="form-control" readonly="" value="<?php echo $cut_off->end_date; ?>">
								        <br>
								        <button type="submit" class="btn btn-primary">LOAD</button> 
								        <a href="<?php echo base_url(); ?>index.php/reports/excel" class="marginbtn btn btn-primary">DOWNLOAD</a> 
								        <!--<a href="<?php echo base_url(); ?>index.php/reports/delete_all_data" onclick="return confirm('Do you want to delete all Data?');" class="btn btn-danger">DELETE</a>--> 
								        <a href="<?php echo base_url(); ?>index.php/reports/adjustment_total_compute" class="btn btn-primary">ADJUSTMENT</a>
										  </div>
					            <thead>
				                <tr>
				                  <th>Employee Number</th>
													<th>Name</th>
													<th>Total Overtime</th>
													<th>Night Differential OT</th>
													<th>Legal Holiday OT</th>
													<th>Special Holiday OT</th>
													<th>RestDay OT</th>
													<th>Tardiness</th>
													<th>Undertime</th>
													<th>VL TAKEN</th>
													<th>SL TAKEN</th>
													<th>Absences &nbsp;&nbsp;(Hours)</th> 
													<th>Total Days</th>
													<th>Total Holiday Days</th>
													<th>Total Hours</th>
				                </tr>
					            </thead>
					            <?php
					            	$total_days = $cut_off->total_days;
					            	$start_date = $cut_off->start_date;
					            	$end_date = $cut_off->end_date;

					            	$cur_date = $start_date;
												$k=0;
												$a=0;
												
												for($k = 1; $k <= $total_days; $k++)
												{
													$week_date = date('w', strtotime($cur_date));
													if($week_date != 0)
													{
														$a++;
														//echo $cur_date;
														//echo '<br>';
													}
													$conv_date = strtotime($start_date);
													$cur_date = date('Y-m-d', strtotime('+' . $k .' days', $conv_date));	
												}
												$tot_days = $a; 
												//echo $a;
												//echo '<br>';
					            ?>
					            <?php
					            	$total_days = $cut_off->total_days;
					            	$start_date = $cut_off->start_date;
					            	$end_date = $cut_off->end_date;

					            	$cur_date = $start_date;
												$k=0;
												$a=0;
												
												for($k = 1; $k <= $total_days; $k++)
												{
													$week_date = date('w', strtotime($cur_date));
													if($week_date == 6)
													{
														$a++;
														//echo $cur_date;
														//echo '<br>';
													}
													$conv_date = strtotime($start_date);
													$cur_date = date('Y-m-d', strtotime('+' . $k .' days', $conv_date));	
												}
												$tot_saturday_days = $a; 
												//echo $a;
					            ?>

					            <?php if($holidays) : ?>
					            	<?php foreach($holidays as $holiday) : ?>
					            		<?php 
					            			//echo $holiday->dates; 
					            			$total_holiday_days = $holiday->dates;
					            		?>
					            	<?php endforeach; ?>	
					            <?php endif; ?>	
					            
					            <br>
					            	<?php if($employees) : ?>
					            		<?php foreach($employees as $emp) : ?>
					            			<tr class="data">
					            				<td><?php echo $emp->emp_no; ?></td>
					            				<td><?php echo $emp->name; ?></td>				            				
					            				<td>
						            			<?php if($ots) : ?>
									            	<?php foreach($ots as $ot) : ?>
									            			<?php 
									            			//TOTAL OVERTIME
									            			if($ot->ot_employee_number == $emp->employee_number && $ot->ot_type == 'ROT')
									            			{
									            				$ot = $ot->total_ot;
									            				$hr_diff = intval($ot/60);
																			$min_diff = intval($ot%60);
																			$ot1 = sprintf("%02d", $min_diff);
									            				echo $hr_diff. "." .$ot1;
									            			}
									            			?>
									            	<?php endforeach; ?>	
									            <?php endif; ?>	
									            </td>
									            <td>
									            	<?php if($nightdiff) : ?>
									            		<?php foreach($nightdiff as $nghtdff) : ?>
									            		<?php 
									            			//TOTAL NIGHT DIFFERENTIAL
									            			if($nghtdff->total_nightdiff == 0)
									            			{
									            				echo ' ';
									            			} 
									            			else
									            			{
									            				if($nghtdff->nightdiff_employee_number == $emp->employee_number)
										            			{

										            				echo $nghtdff->total_nightdiff;
										            			}
									            			}
									            		?>	
									            		<?php endforeach; ?>	
									            	<?php endif; ?>	
									            </td>
									            <td>
									            	<?php if($legal_ots) : ?>
										            	<?php foreach($legal_ots as $legal_ot) : ?>
										            			<?php 
										            			//TOTAL LEGAL HOLIDAY OT
										            			if($legal_ot->legal_ot_employee_number == $emp->employee_number && $legal_ot->ot_type == 'LHOT')
										            			{
										            				$legal_ot = $legal_ot->total_ot;
										            				$hr_diff = intval($legal_ot/60);
																				$min_diff = intval($legal_ot%60);
																				$ot1 = sprintf("%02d", $min_diff);
										            				echo $hr_diff. "." .$ot1;
										            			}
										            			?>
										            	<?php endforeach; ?>	
										            <?php endif; ?>	
									            </td>
									            <td>
									            	<?php if($special_ots) : ?>
										            	<?php foreach($special_ots as $special_ot) : ?>
										            			<?php 
										            			//TOTAL SPECIAL HOLIDAY OT
										            			if($special_ot->special_ot_employee_number == $emp->employee_number && $special_ot->ot_type == 'SHOT')
										            			{
										            				$special_ot = $special_ot->total_ot;
										            				$hr_diff = intval($special_ot/60);
																				$min_diff = intval($special_ot%60);
																				$ot1 = sprintf("%02d", $min_diff);
										            				echo $hr_diff. "." .$min_diff;
										            			}
										            			?>
										            	<?php endforeach; ?>	
										            <?php endif; ?>	
									            </td>
									            <td>
									            	<?php if($restday_ots) : ?>
										            	<?php foreach($restday_ots as $restday_ot) : ?>
										            			<?php 
										            			//TOTAL SPECIAL HOLIDAY OT
										            			if($restday_ot->restday_ot_employee_number == $emp->employee_number && $restday_ot->ot_type == 'RDOT')
										            			{
										            				$restday_ot = $restday_ot->total_ot;
										            				$hr_diff = intval($restday_ot/60);
																				$min_diff = intval($restday_ot%60);
																				$ot1 = sprintf("%02d", $min_diff);
										            				echo $hr_diff. "." .$ot1;
										            			}
										            			?>
										            	<?php endforeach; ?>	
										            <?php endif; ?>	
									            </td>
									            <td>
									            	<?php if($tardiness) : ?>
									            		<?php foreach($tardiness as $tard) : ?>
									            		<?php  
									            			//TOTAL TARDINESS
										            		if($tard->total_tardiness == 0)
										            		{
										            			echo ' ';
										            		} 
										            		else
										            		{
										            			if($tard->tard_employee_number == $emp->employee_number)
										            			{
										            				$tard = $tard->total_tardiness;
										            				$hr_diff = intval($tard/60);
																				$min_diff = intval($tard%60);
																				$tard1 = sprintf("%02d", $min_diff);
										            				echo $hr_diff. "." .$tard1;
										            			}
										            		}	
									            		?>	
									            		<?php endforeach; ?>	
									            	<?php endif; ?>	
									            </td>
									            <td>
									            	<?php if($undertime) : ?>
									            		<?php foreach($undertime as $under) : ?>
									            		<?php  
									            			//TOTAL UNDERTIME 
									            			if($under->total_undertime == 0)
									            			{
									            				echo ' ';
									            			}
									            			else
									            			{
									            				if($under->undertime_employee_number == $emp->employee_number)
										            			{
										            				$under = $under->total_undertime;
										            				$hr_diff = intval($under/60);
																				$min_diff = intval($under%60);
																				$under1 = sprintf("%02d", $min_diff);
										            				echo $hr_diff. "." .$under1;
										            			}
									            			}	
									            		?>	
									            		<?php endforeach; ?>	
									            	<?php endif; ?>	
									            </td>
									            <td>
							            			<?php if($vls) : ?>
										            	<?php foreach($vls as $vl) : ?>
									            			<?php 
									            				//TOTAL VL DAYS
										            			if($vl->slvl_employee_number == $emp->employee_number && $vl->slvl_type == 'VL')
										            			{
										            				echo $vl->total_slvl; 
										            				$total_vl = $vl->total_slvl; 
										            			}
									            			?>
										            	<?php endforeach; ?>	
										            <?php endif; ?>	
									            </td>
									            <td>
							            			<?php if($sls) : ?>
										            	<?php foreach($sls as $sl) : ?>
									            			<?php 
									            				//TOTAL SL DAYS
										            			if($sl->slvl_employee_number == $emp->employee_number && $sl->slvl_type == 'SL')
										            			{
									            					echo $sl->total_slvl; 
										            			}
									            			?>
										            	<?php endforeach; ?>	
										            <?php endif; ?>	
									            </td>
									            <td>
									            	<?php if($abs) : ?>
										            	<?php foreach($abs as $ab) : ?>
									            			<?php 
									            				//TOTAL ABSENT DAYS
										            			if($ab->slvl_employee_number == $emp->employee_number && $ab->slvl_type == 'AB')
										            			{
									            					$absences =$ab->total_slvl / 60; 
									            					echo $absences;
										            			}
									            			?>
										            	<?php endforeach; ?>	
										            <?php endif; ?>	
									            </td>
									            <td>
					            					<?php if($total_dayss) : ?>
					            						<?php foreach($total_dayss as $tt_days) : ?>
					            							<?php
						            							if($tt_days->get_total_employee_number == $emp->employee_number)
										            			{
										            				$total_days_daily = $tt_days->total_employee_days + $tot_saturday_days; 
									            					echo $total_days_daily; 
					            								}	
					            							?>
					            						<?php endforeach; ?>	
					            					<?php endif; ?>
					            				</td>
									            <td>
									            	<?php 
									            		//TOTAL HOLIDAY DAYS
									            		echo $total_holiday_days; 
									            	?>
									            </td>
									            <td>
					            					<?php if($dailyhrs) : ?>
					            						<?php foreach($dailyhrs as $dailyhr) : ?>
					            							<?php
					            								if($dailyhr->get_totaldailyhrs_employee_number == $emp->employee_number)
					            								{
					            									$compute_sat_hours = $tot_saturday_days * 480;
					            									$compute_total_hrs = $dailyhr->total_daily_hrs + $compute_sat_hours;
					            									$hr_diff = intval($compute_total_hrs/60);
																				$min_diff = intval($compute_total_hrs%60);
										            				echo $hr_diff. "." .$min_diff;
					            								} 
					            							?>
					            						<?php endforeach; ?>	
					            					<?php endif; ?>
					            				</td>
					            			</tr>
					            		<?php endforeach; ?>	
					            	<?php endif; ?>
					    			</table>
			      		</div>
			  			</div>      
          </form>

     </div>            
  </div>
</div>
</div>
