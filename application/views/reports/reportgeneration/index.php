<style type="text/css">
	.container {
		margin-top: 60px;
		width: 100%;
	}
	.table {
		width: auto;
	}
	.row {
		margin-left: 2px;
		margin-top: 10px;
	}
	input {
		text-align: center;
	}

</style>
<div class="container">
  <div class="panel panel-primary">
    <div class="panel-heading">
        <h3 class="panel-title">Total Summary List</h3>
    </div>
    <form id="timeForm" method="post">
	    <div class="row">
	    	<div class="col-xs-2">
	    		<input type="text" class="form-control" name="start_date" value="<?php echo $cut_off->start_date; ?>">
	    	</div>
	    	<div class="col-xs-2">
	    		<input type="text" class="form-control" name="end_date" value="<?php echo $cut_off->end_date; ?>">
	    	</div>
	    	<div class="col-md-2">
	    		<button type="submit" class="btn btn-primary">LOAD</button> 
	    		<a href="#" class="marginbtn btn btn-primary">DOWNLOAD</a> 
	    	</div>
	    </div>
	    <div class="panel-body">
	    	<table class="table table-bordered table-hover table-striped">
	    		<thead>
	    			<th>Employee Name</th>
	    			<th>Total Tardiness</th>
	    			<th>No. Tardiness</th>
	    			<th>Total Absences</th>
	    			<th>No. Absences</th>
	    			<!--<th>Total CWWUT</th>-->
	    			<th>Total UT</th>
	    			<th>No. UT</th>
	    			<th>Total ND</th>
	    			<th>Total OT</th>
	    			<th>No. OT</th>
	    			<th>Total SL</th>
	    			<th>Total VL</th>
	    		</thead>
					<?php if($employees) : ?> 
						<?php foreach($employees as $emp) : ?>
							<tr>
	  						<td><?php echo $emp->name; ?></td>

	  						<!-- TARDINESS -->
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

	  						<!-- NUMBER OF TARDINESS -->
	  						<td>
	  							<?php if($number_tardiness) : ?>
		            		<?php foreach($number_tardiness as $num_tard) : ?>
			            		<?php  
			            			//TOTAL TARDINESS
				            		if($num_tard->number_tardiness == 0)
				            		{
				            			echo ' ';
				            		} 
				            		else
				            		{
				            			if($num_tard->tard_employee_number == $emp->employee_number)
				            			{
				            				echo $num_tard->number_tardiness;
				            			}
				            		}	
			            		?>	
		            		<?php endforeach; ?>	
		            	<?php endif; ?>	
	  						</td>

	  						<!-- TOTAL OF ABSENCES -->
	  						<td>	
	  							<?php if($absences) : ?>
		            		<?php foreach($absences as $num_abs) : ?>
			            		<?php  
			            			//TOTAL TARDINESS
				            		if($num_abs->total_slvl == 0)
				            		{
				            			echo ' ';
				            		} 
				            		else
				            		{
				            			if($num_abs->slvl_employee_number == $emp->employee_number)
				            			{
				            				echo $num_abs->total_slvl;
				            			}
				            		}	
			            		?>	
		            		<?php endforeach; ?>	
		            	<?php endif; ?>	
	  						</td>
	  					
	  						
	  						<!-- NUMBER OF ABSENCES -->
	  						<td>
	  							<?php if($absences) : ?>
		            		<?php foreach($absences as $num_abs) : ?>
			            		<?php  
			            			//TOTAL TARDINESS
				            		if($num_abs->total_slvl == 0)
				            		{
				            			echo ' ';
				            		} 
				            		else
				            		{
				            			if($num_abs->slvl_employee_number == $emp->employee_number)
				            			{
				            				echo $num_abs->count_rows;
				            			}
				            		}	
			            		?>	
		            		<?php endforeach; ?>	
		            	<?php endif; ?>	
	  						</td>

	  						<!-- TOTAL CWWUT -->
	  						<!--<td>
	  							<?php //if($cwwuts) : ?>
									<?php //foreach($cwwuts as $cwwut) : ?>
										<?php  
											//TOTAL TARDINESS
											/*if($cwwut->undertime_hr == 0)
											{
												echo ' ';
											} 
											else
											{
												if($cwwut->cwwut_employee_number == $emp->employee_number)
												{
													$hr_diff = intval($cwwut->undertime_hr /60);
																$min_diff = intval($cwwut->undertime_hr %60);
																$hrs1 = sprintf("%02d", $min_diff);
																echo $hr_diff.".".$hrs1."";
												}
											}*/	
										?>	
									<?php //endforeach; ?>	
								<?php //endif; ?>	
	  						</td>-->

	  						<!-- TOTAL UNDERTIME -->
	  						<td>
	  							<?php if($undertime) : ?>
		            		<?php foreach($undertime as $ut) : ?>
			            		<?php  
			            			//TOTAL TARDINESS
				            		if($ut->total_undertime == 0)
				            		{
				            			echo ' ';
				            		} 
				            		else
				            		{
				            			if($ut->undertime_employee_number == $emp->employee_number)
				            			{
				            				$hr_diff = intval($ut->total_undertime /60);
														$min_diff = intval($ut->total_undertime %60);
														$hrs1 = sprintf("%02d", $min_diff);
														echo $hr_diff.".".$hrs1."";
				            			}
				            		}	
			            		?>	
		            		<?php endforeach; ?>	
		            	<?php endif; ?>	
	  						</td>	

	  							<!-- NUMBER UNDERTIME -->
	  						<td>
	  							<?php if($undertime) : ?>
		            		<?php foreach($undertime as $ut) : ?>
			            		<?php  
			            			//TOTAL TARDINESS
				            		if($ut->total_undertime == 0)
				            		{
				            			echo ' ';
				            		} 
				            		else
				            		{
				            			if($ut->undertime_employee_number == $emp->employee_number)
				            			{
				            				echo $ut->num_rows_ut;
				            			}
				            		}	
			            		?>	
		            		<?php endforeach; ?>	
		            	<?php endif; ?>	
	  						</td>	

	  						<!-- TOTAL NIGHT DIFF -->
	  						<td>
	  							<?php if($night_diffs) : ?>
		            		<?php foreach($night_diffs as $night_diff) : ?>
			            		<?php  
			            			//TOTAL TARDINESS
				            		if($night_diff->total_night_diff == 0)
				            		{
				            			echo ' ';
				            		} 
				            		else
				            		{
				            			if($night_diff->nd_employee_number == $emp->employee_number)
				            			{
				            				echo number_format($night_diff->total_night_diff, 2);
				            			}
				            		}	
			            		?>	
		            		<?php endforeach; ?>	
		            	<?php endif; ?>	
	  						</td>
	  						
	  						<!-- TOTAL OT -->
	  						<td>
	  							<?php if($ots) : ?>
		            		<?php foreach($ots as $ot) : ?>
			            		<?php  
			            			//TOTAL TARDINESS
				            		if($ot->total_ot == 0)
				            		{
				            			echo ' ';
				            		} 
				            		else
				            		{
				            			if($ot->ot_employee_number == $emp->employee_number)
				            			{
				            				$hr_diff = intval($ot->total_ot /60);
														$min_diff = intval($ot->total_ot %60);
														$hrs1 = sprintf("%02d", $min_diff);
														echo $hr_diff.".".$hrs1."";
				            			}
				            		}	
			            		?>	
		            		<?php endforeach; ?>	
		            	<?php endif; ?>	
	  						</td>


	  						<!-- NUMBER OT -->
	  						<td>
	  							<?php if($ots) : ?>
		            		<?php foreach($ots as $ot) : ?>
			            		<?php  
			            			//TOTAL TARDINESS
				            		if($ot->total_ot == 0)
				            		{
				            			echo ' ';
				            		} 
				            		else
				            		{
				            			if($ot->ot_employee_number == $emp->employee_number)
				            			{
														echo $ot->num_rows_ot;
				            			}
				            		}	
			            		?>	
		            		<?php endforeach; ?>	
		            	<?php endif; ?>	
	  						</td>

	  						<!-- TOTAL SICK LEAVE -->
	  						<td>
	  							<?php if($sls) : ?>
		            		<?php foreach($sls as $total_sl) : ?>
			            		<?php  
			            			//TOTAL TARDINESS
				            		if($total_sl->total_slvl == 0)
				            		{
				            			echo ' ';
				            		} 
				            		else
				            		{
				            			if($total_sl->slvl_employee_number == $emp->employee_number)
				            			{
				            				echo $total_sl->total_slvl;
				            			}
				            		}	
			            		?>	
		            		<?php endforeach; ?>	
		            	<?php endif; ?>	
	  						</td>

	  						<!-- TOTAL VACATION LEAVE -->
	  						<td>
	  							<?php if($vls) : ?>
		            		<?php foreach($vls as $total_vl) : ?>
			            		<?php  
			            			//TOTAL TARDINESS
				            		if($total_vl->total_slvl == 0)
				            		{
				            			echo ' ';
				            		} 
				            		else
				            		{
				            			if($total_vl->slvl_employee_number == $emp->employee_number)
				            			{
				            				echo $total_vl->total_slvl;
				            			}
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
	  </form>  
  </div>
</div>
