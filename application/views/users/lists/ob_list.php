<style type="text/css">
	.margin {
		margin-top: 70px;
		color: black;
	}
	.panel-heading{
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
	.row
	{
		width: 100%;
		overflow: hidden;
	}
</style> 
<div class="margin">      
<?php if($this->session->flashdata('add_msg')) : ?>  
   <p class="alert alert-dismissable alert-success"><?php echo $this->session->flashdata('add_msg'); ?></p> 
<?php endif; ?>  
<?php if($this->session->flashdata('update_ob_msg')) : ?>  
   <p class="alert alert-dismissable alert-success"><?php echo $this->session->flashdata('update_ob_msg'); ?></p>  
<?php endif; ?>     
<?php if($this->session->flashdata('delete_msg')) : ?>  
      <p class="alert alert-dismissable alert-danger"><?php echo $this->session->flashdata('delete_msg'); ?></p>    
<?php endif; ?>   
<?php if($this->session->flashdata('disapproved_ob')) : ?> 
  <p class="alert alert-dismissable alert-danger"><?php echo $this->session->flashdata('disapproved_ob'); ?></p>  
<?php endif; ?> 
<!-- TABLE OF OB  --> 
<div class="row"> 
	<div class="col-lg-12"> 
  	<div class="panel panel-primary"> 
    	<div class="panel-heading">  
      	OB List 
      </div>	 
        <form method="post" id="ob"> 
			    <div class="panel-body"> 
			      <div class="table-responsive"> 
			          <table class="table table-bordered table-hover table-striped cl"> 
			          	<div class="row">  	 
				          	<div class="col-md-2"> 
						          <div class="form-group"> 
						              <label for="form_name">Start Date</label> 
						              <input id="form_name" type="date" name="start_date" class="form-control" value="<?php echo $cut_off->start_date; ?>"> 
						          </div>  
						        </div> 
						        <div class="col-md-2"> 
						          <div class="form-group"> 
						              <label for="form_name">End Date</label> 
						              <input id="form_name" type="date" name="end_date" class="form-control" value="<?php echo $cut_off->end_date; ?>">
						          </div>
						        </div>	

								<?php if($this->session->userdata('is_hr') == 1 || $this->session->userdata('is_rfv') == 1 ) : ?>
									<div class="col-md-2">
										<div class="form-group">
											<label for="form_name">Branch</label>
											<select class="form-control" name="branch_id">
												<option value="ALL"<?php echo $branch_id == 'ALL' ? 'selected' : ''; ?>>ALL</option>
												<?php if($branches) : ?>
													<?php foreach($branches as $branch) : ?>
														<option  value="<?php echo $branch->id; ?>" <?php echo $branch->id == $branch_id ? 'selected' : ' '; ?> ><?php echo $branch->name; ?></option>
													<?php endforeach; ?>
												<?php endif; ?>
											</select>
										</div>
									</div>	
								<?php endif; ?>

						        <?php if($employee) : ?>
						        	<input type="hidden" name="department_id" value="<?php echo $employee->department_id; ?>">
						        <?php endif; ?>	

						        <br>
						        <button type="submit" class="btn btn-default">Load</button>
						        <?php if($this->session->userdata('is_rfa') == 1 || $this->session->userdata('is_oichead') == 1 || $this->session->userdata('is_hr') == 1) : ?> 
						        	<input class="btn btn-success" id="rfa" type="submit" value="RFA">  
						        <?php endif; ?>	 

						        <?php if($this->session->userdata('is_hr') == 1 || $this->session->userdata('is_fa') == 2 || $this->session->userdata('is_fa') == 1 || $this->session->userdata('is_fa') == 4) : ?>           
							        <input class="btn btn-info" id="fa" type="submit" value="FA">   
							      <?php endif; ?>	  

							      <?php if($this->session->userdata('is_hr') == 1 || $this->session->userdata('is_rfv') == 1) : ?> 
							        <input class="btn btn-default" id="rfv" type="submit" value="RFV"> 
							      <?php endif; ?>	

						        <?php if($this->session->userdata('is_hr') == 1) : ?> 
							        <input class="btn btn-danger" id="fv" type="submit" value="FV"> 
							      <?php endif; ?>	

						        <?php if($this->session->userdata('is_hr') == 1 || $this->session->userdata('is_gm') == 1) : ?> 
							        <input class="btn btn-warning" id="nb" type="submit" value="FN">
							      <?php endif; ?>	
						        <?php if($this->session->userdata('is_hr') == 1 || $this->session->userdata('is_cfo') == 1) : ?> 
							        <input class="btn btn-primary" id="afp" type="submit" value="AFP"> 
							      <?php endif; ?>  
								  </div>
			            <thead>
			                <tr>
			                	<th><center><input type="checkbox" id="checkAll" name=""></center></th>
								<th>Employee Name</th>
								<th>Date</th>
								<th>Type</th>
								<th>From | To</th>
								<th>Departure (Time in)</th>
								<th>Return (Time out)</th>
								<th>Remarks</th>
								<th>Action</th>
			                </tr>
			            </thead> 
			            <?php if(isset($obs)) : ?>
		                <?php foreach($obs as $ob) : ?>
		                	<?php if($ob->employee_number != $this->session->userdata('emp_no_id' && $this->session->userdata('is_hr' == 1))) : ?>
			                	<?php $week_date = date('w', strtotime($ob->date_ob)); ?>
				                <tr class="data">
				                	<input type="hidden" name="process_date" value="<?php echo date('Y-m-d'); ?>">
				                	<td>
				                		<?php if($ob->remarks != 'PROCESSED') : ?>
				                			<center><input type="checkbox" name="employee[]" value="<?php echo $ob->id . '|' . $ob->employee_number . '|' . $ob->date_ob . '|' . $week_date . '|' .  $ob->type_ob . '|' . $ob->time_of_departure . '|' . $ob->time_of_return . '|' . $ob->type; ?>"> </center>
				                		<?php endif; ?>
				                	</td>
				                	<input type="hidden" name="employee_number[]" value="<?php echo $ob->employee_number; ?>">
			                    <td title="<?php echo $ob->branch_id; ?>"><?php echo $ob->name; ?></td>
			                    <td>
			                    	<?php 
			                    		echo $ob->date_ob;
			                    	?>
			                    	<input type="hidden" name="date_ob[]" value="<?php echo $ob->date_ob; ?>">
			                    	<input type="hidden" name="date_num[]" value="<?php echo $week_date; ?>">
			                    </td>
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
			                    	<input type="hidden" name="status[]" value="<?php echo $ob->type_ob; ?>">
			                    </td>
			                    <td><?php echo $ob->site_from . '  |  ' . $ob->site_to; ?></td>
			                    <td><?php echo $ob->time_of_departure; ?></td>
			                    <td><?php echo $ob->time_of_return; ?></td>
			                   
			                    <td><?php echo $ob->remarks; ?></td>
			                    <td>
			                    	<center>
			                    		<?php if($ob->remarks != 'PROCESSED') : ?>
				                    		<?php if($this->session->userdata('is_hr') == 1 || $this->session->userdata('is_rfv')) : ?>
				                      			<a class="btn btn-xs btn-primary" href="<?php echo base_url() ?>index.php/reports/edit_ob/<?php echo $ob->id; ?>">Edit</a>
				                    		<?php endif; ?>
				                      	<a class="btn btn-danger btn-xs delete-btn" onclick="return confirm('Do you want to disapproved this employee?');" href="<?php echo base_url() ?>index.php/users/disapproved_ob/<?php echo $ob->id; ?>">Disapproved</a>
				                      <?php endif; ?>
			                    	</center>
			                    </td>
				                </tr>
		                	
		            			<?php endif; ?>
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
		
		$('#rfa').click(function() {
			var a = confirm("Are you sure you want to Approved Data?");
			if (a == true) {
				$('#ob').attr('action', 'rfa_ob');
				$('#ob').submit();
			} else {
				return false;
			} 
		});

		$('#fa').click(function() {
			var a = confirm("Are you sure you want to Approved Data?");
			if (a == true) {
				$('#ob').attr('action', 'fa_ob');
				$('#ob').submit();
			} else {
				return false;
			} 
		});

		$('#rfv').click(function() {
			var a = confirm("Are you sure you want to Verified Data?");
			if (a == true) {
				$('#ob').attr('action', 'rfv_ob');
				$('#ob').submit();
			} else {
				return false;
			} 
		});

		$('#fv').click(function() {
			var a = confirm("Are you sure you want to Verified Data?");
			if (a == true) {
				$('#ob').attr('action', 'fv_ob');
				$('#ob').submit();
			} else {
				return false;
			} 
		});

		$('#nb').click(function() {
			var a = confirm("Are you sure you want to Processed Data?");
			if (a == true) {
				$('#ob').attr('action', 'nb_ob');
				$('#ob').submit();
			} else {
				return false;
			} 
		});

		$('#afp').click(function() {
			var a = confirm("Are you sure you want to Processed Data?");
			if (a == true) {
				$('#ob').attr('action', 'afp_ob');
				$('#ob').submit();
			} else {
				return false;
			} 
		});

		$("#checkAll").click(function(){
   	 $('input:checkbox').not(this).prop('checked', this.checked);
		});

	});	
</script>

