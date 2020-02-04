<style type="text/css">
	.margin {
		margin-top: 70px;
		color: black;
	}
	p{
		font-size: 24px;
		font-family: century gothic;
	}
	td {
		color: black;
	}
	.add{
		margin-top: -45px;
		margin-left: 1200px;
	}
</style>
<div class="margin">
<?php if($this->session->flashdata('add_msg')) : ?>
   <p class="alert alert-dismissable alert-success"><?php echo $this->session->flashdata('add_msg'); ?></p>
<?php endif; ?>
<?php if($this->session->flashdata('update_msg')) : ?>
   <p class="alert alert-dismissable alert-success"><?php echo $this->session->flashdata('update_msg'); ?></p>
<?php endif; ?>
<?php if($this->session->flashdata('delete_msg')) : ?>
      <p class="alert alert-dismissable alert-danger"><?php echo $this->session->flashdata('delete_msg'); ?></p>
<?php endif; ?>  
<!-- TABLE OF BRANCHES -->
<div class="row">
	<div class="col-lg-12">

  	<div class="panel panel-default">
    	<div class="panel-heading">
      	<p>Undertime List</p>
      </div>	
        <form method="post" id="ut">
			    <div class="panel-body">
			      <div class="table-responsive">
			          <table class="table table-bordered table-hover table-striped cl">
			          	<div class="row">  	
				          	<div class="col-md-2">
						          <div class="form-group">
						              <label for="form_name">Start Date</label>
						              <input id="form_name" type="text" name="start_date" class="form-control" value=" <?php echo $cut_off->start_date; ?>">
						          </div>
						        </div>
						        <div class="col-md-2">
						          <div class="form-group">
						              <label for="form_name">End Date</label>
						              <input id="form_name" type="text" name="end_date" class="form-control" value="<?php echo $cut_off->end_date; ?>">
						          </div>
						        </div>	
						        <br>
						        <button type="submit" class="btn btn-primary">Load</button>
						        <a class="btn btn-primary" href="<?php echo base_url(); ?>index.php/reports/add_undertime">Add</a>
						        <input class="btn btn-primary" id="process" type="submit" value="Process">  
								  </div> 
			            <thead>
		                <tr>
		                	<th><center><input type="checkbox" id="checkAll" name=""></center></th>
	                    <th>Employee No</th>
	                    <th>Name</th>
	                    <th>Date</th>
	                    <th>Time Out</th>
	                    <th>UT No</th>
	                    <th>Reason</th>
	                    <th>Status</th>
	                    <th><center>Action</center></th>
		                </tr>
			            </thead>
			            	<?php if($emps_under) : ?>
				          		<?php foreach($emps_under as $undertime) : ?>
				          			<?php
					    	 					$halfday_ut = 720;
					    	 					$halfday_in = 780; 
					    	 					$total_friday_out_min = 990;
					    	 					$total_out_min = 1050;
					    	 					$total_casual_out = 1140;
					    	 					$total_casual_friday_out = 1080;
					    	 					$w_date_ut = date('w', strtotime($undertime->date_ut));
					    	 					$explode_time_out = explode(' ', $undertime->emp_time_out);
				    	 						$hr_mins_explode = explode(':', $explode_time_out[1]);

				    	 						$hrs = $hr_mins_explode[0] * 60;
				    	 						$hrs_mins = $hrs + $hr_mins_explode[1];

					    	 					if($w_date_ut <= 4 )
													{
														if($hrs_mins < $halfday_ut)
														{
															$undertime_hr = intval($halfday_ut - $hrs_mins);
															$hr_diff = intval($undertime_hr/60);
															$min_diff = intval($undertime_hr%60);
															$hrs1 = sprintf("%02d", $min_diff);
															//echo $hr_diff.".".$hrs1."";
															$number_of_undertime = $hr_diff.".".$min_diff;
															$ud_time = $undertime_hr;
														}
														else
														{
															if($hrs_mins > $halfday_in && $undertime->employee_number != 10195)
															{
																$undertime_hr = intval($total_out_min - $hrs_mins);
																$hr_diff = intval($undertime_hr/60);
																$min_diff = intval($undertime_hr%60);
																$hrs1 = sprintf("%02d", $min_diff);
																//echo $hr_diff.".".$hrs1."";
																$number_of_undertime = $hr_diff.".".$min_diff;
																$ud_time = $undertime_hr;
															}
															elseif($hrs_mins > $halfday_in && $undertime->employee_number == 10195)
															{
																$undertime_hr = intval($total_casual_out - $hrs_mins);
																$hr_diff = intval($undertime_hr/60);
																$min_diff = intval($undertime_hr%60);
																$hrs1 = sprintf("%02d", $min_diff);
																//echo $hr_diff.".".$hrs1."";
																$number_of_undertime = $hr_diff.".".$min_diff;
																$ud_time = $undertime_hr;
															}
														}
													}
													elseif($w_date_ut == 5)
													{
														if($halfday_in < $hrs_mins && $undertime->employee_number != 10195)
														{
															$undertime_hr = intval($total_friday_out_min - $hrs_mins);
															$hr_diff = intval($undertime_hr/60);
															$min_diff = intval($undertime_hr%60);
															$hrs1 = sprintf("%02d", $min_diff);
															//echo $hr_diff.".".$hrs1."";
															$number_of_undertime = $hr_diff.".".$min_diff; 
															$ud_time = $undertime_hr;
														}
														elseif($hrs_mins > $halfday_in && $undertime->employee_number == 10195)
														{
															$undertime_hr = intval($total_casual_friday_out - $hrs_mins);
															$hr_diff = intval($undertime_hr/60);
															$min_diff = intval($undertime_hr%60);
															$hrs1 = sprintf("%02d", $min_diff);
															//echo $hr_diff.".".$hrs1."";
															$number_of_undertime = $hr_diff.".".$min_diff;
															$ud_time = $undertime_hr;
														}
													}
													else
													{
														//echo ' ';
														$ud_time = 0;
													}  
				          			?>
				          			<tr 	
	                			<?php echo $ud_time != $undertime->ut_no ? 'style="background-color:#e74c3c"' : ' '; ?> >
				          				<td>
														<?php if($undertime->status != 'PROCESSED') : ?>				            
				                			<center><input type="checkbox" name="employee[]" value="<?php echo $undertime->id . '|' . $undertime->employee_number . '|' . $undertime->name . '|' . $undertime->date_ut . '|' . $undertime->ut_no . '|' . $undertime->status ; ?>"> </center>
				                		<?php endif; ?>
				                	</td>
				          				<td><?php echo $undertime->employee_number; ?></td>
				          				<td><?php echo $undertime->name; ?></td>
				          				<td><?php echo $undertime->date_ut; ?></td>
				          				<td><?php echo $undertime->time_out; ?></td>
				          				<td>
				          					<?php 
				          					$hours = floor($undertime->ut_no / 60);
														$minutes = $undertime->ut_no % 60;
														$ut_hrs = $hours. '.' .$minutes;
														echo $ut_hrs;
				          					?>
				          				</td>
				          				<td><?php echo $undertime->reason; ?></td>
				          				<td><?php echo $undertime->status; ?></td>
				          				<td>
				          					<center>
				          						<?php if($undertime->status != 'PROCESSED') : ?>
				          							<a href="<?php echo base_url(); ?>index.php/reports/edit_undertime/<?php echo $undertime->id; ?>" class="btn-sm btn-primary">Edit</a>
				          						<?php endif; ?>	
				          						<a href="<?php echo base_url(); ?>index.php/reports/delete_undertime/<?php echo $undertime->id; ?>" class="btn-sm btn-danger">Delete</a>
				          					</center>
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
<script type="text/javascript" src="<?php echo base_url(); ?>/assets/js/jquery.min.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
		
		$('#process').click(function() {
			var a = confirm("Are you sure you want to Processed Data?");
			if (a == true) {
				$('#ut').attr('action', 'process_undertime');
				$('#ut').submit();
			} else {
				return false;
			} 
		});

		$("#checkAll").click(function(){
   	 $('input:checkbox').not(this).prop('checked', this.checked);
		});

	});	
</script>

