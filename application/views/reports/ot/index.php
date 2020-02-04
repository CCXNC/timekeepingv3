<style type="text/css">
	.margin { 
		margin-top: 70px;
		color: black;
	}
	p{ 
		font-size: 24px;
		font-family: century gothic;
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
	      	<p>OT List</p>
	      </div>	
	        <form method="post" id="ots">
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
							        <button type="submit" class="btn btn-primary">load</button>  
							        <a class="btn btn-primary" href="<?php echo base_url(); ?>index.php/reports/add_ot">Add</a>
							        <input class="btn btn-primary" id="process" type="submit" value="Process">
									  </div> 
				            <thead>
				                <tr>
				                	<th><center><input type="checkbox" id="checkAll" name=""></center></th>
			                    <th>Employee No</th>
			                    <th>Name</th>
			                    <th>Type</th>
			                    <th>Date</th>
			                    <th>Time In</th>
			                    <th>Time Out</th>
			                    <th>Nature Of Work</th>
			                    <th>Status</th>
			                    <th><center>Action</center></th>
				                </tr>
				            </thead>
			            	<td class="trfixed">
										</td>
				            <?php if(isset($ots)) : ?>
				            		<?php $weekdate = ' '; $total_out_daily = ' '; ?>
				                <?php foreach($ots as $ot) : ?>
				                	<?php 
				                		if($ot->employee_in == NULL && $ot->employee_out == NULL)
				                		{
				                			
				                		}
				                		else
				                		{
					                		$weekdate = date('w', strtotime($ot->date_ot)); 
			                    		$fixed_in = 450;

				                    		$explode_in = explode(" ", $ot->employee_in);
				                    	
					                    	//echo $explode_in[1];

				                    		$time_in_explode = explode(":", $explode_in[1]);

				                    		$time_in_hr = $time_in_explode[0];
				                    		$time_in_mins = $time_in_explode[1];

				                    		$total_in_daily = intval($time_in_hr*60) + $time_in_mins;
				                    		//echo $total_in_daily;	
				                    	
																$fixed_out = 1050;
				                    		$explode_out =explode(" ", $ot->employee_out);
				                    		//echo $explode_out[1] . "|";

				                    		$time_out_explode = explode(":", $explode_out[1]);

				                    		$time_out_hr = $time_out_explode[0];
				                    		$time_out_mins = $time_out_explode[1];

				                    		$total_out_daily = intval($time_out_hr*60) + $time_out_mins;

				                    		//echo $total_out_daily. "||";

				                    		if($weekdate <= 4)
				                    		{
					                    		$total_min_diff_out = intval($total_out_daily - $fixed_out);
					                    		$mon_thru_out = $total_min_diff_out;
					                    		//echo $mon_thru_out;

					                    		$total_min_diff_in = intval($fixed_in - $total_in_daily);
																	$mon_fri_in = $total_min_diff_in;
					                    	}	

					                    	elseif($weekdate == 5)
					                    	{
					                    		$friday_out = 990;
																	$total_min_diff_out_friday = intval($total_out_daily - $friday_out);
																	$fri_out = $total_min_diff_out_friday;
																	//echo $fri_out;

																	$total_min_diff_in = intval($fixed_in - $total_in_daily);
																	$mon_fri_in = $total_min_diff_in;
					                    	}	

					                    	elseif($weekdate == 6)
					                    	{
				                    			$total_ot_hrs_in_saturday = intval($total_out_daily - $total_in_daily );
				                    			$hf_sat = $total_ot_hrs_in_saturday;
				                    			//echo $hf_sat;
				                    			
				                    			$sat_in = $total_in_daily;
					                    	}
					                    	elseif($weekdate == 0)
					                    	{
				                    			$total_ot_hrs_in_sunday = intval($total_out_daily - $total_in_daily );
				                    			$hf_sat = $total_ot_hrs_in_sunday;
				                    			//echo $hf_sat / 60;
				                    			
				                    			$sat_in = $total_in_daily;
					                    	}
				                		}	
				                	
		                    	?>
					                <tr class="data"
				                		<?php 	
					                		 echo $weekdate == 1 && $mon_fri_in < $ot->total_ot && $mon_thru_out < $ot->total_ot ? 'style="background-color:#e74c3c"' : $weekdate == 2 && $mon_fri_in < $ot->total_ot && $mon_thru_out < $ot->total_ot ? 'style="background-color:#e74c3c"' : $weekdate == 3 && $mon_fri_in < $ot->total_ot && $mon_thru_out < $ot->total_ot ? 'style="background-color:#e74c3c"' : $weekdate == 4 && $mon_fri_in < $ot->total_ot && $mon_thru_out < $ot->total_ot ? 'style="background-color:#e74c3c"' : $weekdate == 5 && $mon_fri_in < $ot->total_ot && $fri_out < $ot->total_ot ? 'style="background-color:#e74c3c"' : $weekdate == 6 && $total_out_daily > 720 && $hf_sat < $ot->total_ot ? 'style="background-color:#e74c3c"' : $weekdate == 0 && $total_out_daily > 720 && $hf_sat < $ot->total_ot ? 'style="background-color:#e74c3c"' : ' ';
					                	?>
				               		>
				               		<?php if($ot->ot_type_name != 'Adjustment') : ?>
					                	<td>	
					                		<?php if($ot->tbl_ot_status != "PROCESSED") : ?>
			                					<center><input type="checkbox" name="employee[]" value="<?php echo $ot->id . '|' . $ot->employee_number . '|' . $ot->name; ?>"> </center>
			                				<?php endif; ?>
			                			</td>
				                    <td><?php echo $ot->employee_number; ?></td>
				                    <td title="<?php echo $ot->branch_id; ?>"><?php echo $ot->name; ?></td>
				                    <td><?php echo $ot->ot_type_name; ?></td>
				                    <td><?php echo $ot->date_ot; ?></td>
				                    <td><?php echo $ot->time_in; ?></td>
				                    <td><?php echo $ot->time_out; ?></td>
				                    <td>
				                    	<?php echo $ot->nature_of_work; ?>
				                    	<?php 
				                    		/*$fixed_in = 450;

				                    		$explode_in = explode(" ", $ot->employee_in);
				                    	
					                    	echo $explode_in[1] . "||";

				                    		$time_in_explode = explode(":", $explode_in[1]);

				                    		$time_in_hr = $time_in_explode[0];
				                    		$time_in_mins = $time_in_explode[1];

				                    		$total_in_daily = intval($time_in_hr*60) + $time_in_mins;

				                    		//echo $total_in_daily . "||";	
				                    		if($weekdate  <= 5)
				                    		{
					                    		$total_min_diff_in = intval($fixed_in - $total_in_daily);
																	$mon_fri_in = $total_min_diff_in;
																	echo $mon_fri_in; 	
				                    		}
				                    		elseif($weekdate == 6)
				                    		{
				                    			$sat_in = $total_in_daily;
				                    			echo $sat_in;
				                    		}*/	
				                    	?>
				                    </td>
				                    <td>
				                    	<?php echo $ot->tbl_ot_status; ?>
				                    	<?php
				                    		/* 
				                    		$fixed_out = 1050;
				                    		$explode_out =explode(" ", $ot->employee_out);
				                    		echo $explode_out[1] . "|";

				                    		$time_out_explode = explode(":", $explode_out[1]);

				                    		$time_out_hr = $time_out_explode[0];
				                    		$time_out_mins = $time_out_explode[1];

				                    		$total_out_daily = intval($time_out_hr*60) + $time_out_mins;

				                    		//echo $total_out_daily. "||";

				                    		if($weekdate <= 4)
				                    		{
					                    		$total_min_diff_out = intval($total_out_daily - $fixed_out);
					                    		$mon_thru_out = $total_min_diff_out;
					                    		echo $mon_thru_out;
					                    	}	

					                    	elseif($weekdate == 5)
					                    	{
					                    		$friday_out = 990;
																	$total_min_diff_out_friday = intval($total_out_daily - $friday_out);
																	$fri_out = $total_min_diff_out_friday;
																	echo $fri_out;
					                    	}	

					                    	elseif($weekdate == 6)
					                    	{
					                    		if($total_out_daily > 720)
					                    		{
					                    			$total_ot_hrs_in_saturday = intval($total_out_daily - $total_in_daily - 60);
					                    			$hf_sat = $total_ot_hrs_in_saturday;
					                    			echo $hf_sat;
					                    		}
					                    		elseif($total_out_daily <= 720)
					                    		{
					                    			$total_ot_hrs_in_saturday = intval($total_out_daily - $total_in_daily);
					                    			$hf1_sat = $total_ot_hrs_in_saturday;
					                    			echo $hf1_sat;
					                    		}
					                    		
					                    	}
																
																*/
																//echo $total_min_diff_out. " " . $total_min_diff_out_friday. " " . $total_ot_hrs_in_saturday;
				                    	?>
				                    		
				                    </td>
				                    <td>
				                      <center>
				                      	<?php if($ot->tbl_ot_status != "PROCESSED") : ?>
				                      		<a class="btn btn-xs btn-primary" href="<?php echo base_url(); ?>index.php/reports/edit_ot/<?php echo $ot->id; ?>">Edit</a>
				                      	<?php endif; ?>
				                      	<a class="btn btn-xs btn-danger" onclick="return confirm('Do you want to delete?');" href="<?php echo base_url(); ?>index.php/reports/delete_ot/<?php echo $ot->id; ?>">Delete</a>
				                      </center>
				                    </td>
				                  <?php endif; ?> 
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
	$(document).ready(function(){

		$('#process').click(function() {
			var a = confirm("Are you sure you want to Processed Data?");
			if (a == true) {
				$('#ots').attr('action', 'process_ot');
				$('#ots').submit();
			} else {
				return false;
			} 
		});

		$("#checkAll").click(function(){
   	 $('input:checkbox').not(this).prop('checked', this.checked);
		});

	});
</script>
