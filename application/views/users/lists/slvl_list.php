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
<?php if($this->session->flashdata('update_msg')) : ?>
   <p class="alert alert-dismissable alert-success"><?php echo $this->session->flashdata('update_msg'); ?></p>
<?php endif; ?>
<?php if($this->session->flashdata('delete_msg')) : ?>
      <p class="alert alert-dismissable alert-danger"><?php echo $this->session->flashdata('delete_msg'); ?></p>
<?php endif; ?>  
<?php if($this->session->flashdata('disapproved_slvl')) : ?>
	<p class="alert alert-dismissable alert-danger"><?php echo $this->session->flashdata('disapproved_slvl'); ?></p>
<?php endif; ?>
<!-- TABLE OF BRANCHES -->
<div class="row">
	<div class="col-lg-12">
		
  	<div class="panel panel-primary">
    	<div class="panel-heading">
      	SL/VL List
      </div>	
        <form method="post" id="slvl">
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

						        <?php if($this->session->userdata('is_hr') == 1 || $this->session->userdata('is_fa') == 2 || $this->session->userdata('is_fa') == 1 || $this->session->userdata('is_fa') == 4 || $this->session->userdata('is_cfo') == 1) : ?>
							        <input class="btn btn-info" id="fa" type="submit" value="FA"> 
							      <?php endif; ?>	

							      <?php if($this->session->userdata('is_hr') == 1 || $this->session->userdata('is_rfv') == 1) : ?> 
							        <input class="btn btn-default" id="rfv" type="submit" value="RFV"> 
							      <?php endif; ?>	

						        <?php if($this->session->userdata('is_hr') == 1 ) : ?> 
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
							<th>Reason</th>
							<th>Status</th>
							<th><center>Action</center></th>
		                </tr>
			            </thead>
		            	<tr>
			            <?php if(isset($slvl)) : ?>
			                <?php foreach($slvl as $slvl) : ?>
			                	<?php if($slvl->employee_number != $this->session->userdata('emp_no_id' && $this->session->userdata('is_hr' == 1))) : ?>
					                <tr class="data"> 
					                	<td>
					                		<?php if($slvl->status != "PROCESSED") : ?>
					                			<center><input type="checkbox" name="employee[]" value="<?php echo $slvl->id . '|' . $slvl->employee_number . '|' . $slvl->name . '|' . $slvl->date_start . '|' . $slvl->type_slvl . '|' . $slvl->sl_am_pm . '|' . $slvl->sl_credit . '|' . $slvl->vl_credit . '|' . $slvl->elcl_credit . '|' . $slvl->fl_credit . '|' . $slvl->slvl_num; ?>"> </center>
					                		<?php endif; ?>
					                	</td>
				                    <td title="<?php echo $slvl->branch_id; ?>"><?php echo $slvl->name; ?></td>
				                    <td><?php echo $slvl->date_start ?></td>
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
				                    		else
				                    		{
				                    			echo $slvl->type_name;
				                    		}	
				                    	?>
				                    </td>
				                    <td><?php echo $slvl->reason; ?></td>
				                    <td><?php echo $slvl->status; ?></td>
				                    <td>
				                     <center>
				                     	<?php if($slvl->status != "PROCESSED") : ?>
				                     	<!--<?php if($this->session->userdata('is_hr') == 1 || $this->session->userdata('is_rfv')) : ?>
				                     			<a class="btn btn-xs btn-primary" href="<?php echo base_url(); ?>index.php/reports/edit_slvl/<?php echo $slvl->id; ?>">EDIT</a>
				                     		<?php endif; ?>	-->
				                     		<a class="btn btn-xs btn-danger" onclick="return confirm('Do you want to disapproved this employee?');" href="<?php echo base_url(); ?>index.php/users/disapproved_slvl/<?php echo $slvl->id; ?>">DISAPPROVED</a>
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
				$('#slvl').attr('action', 'rfa_slvl');
				$('#slvl').submit();
			} else {
				return false;
			} 
		});

		$('#fa').click(function() {
			var a = confirm("Are you sure you want to Approved Data?");
			if (a == true) {
				$('#slvl').attr('action', 'fa_slvl');
				$('#slvl').submit();
			} else {
				return false;
			} 
		});

		$('#rfv').click(function() {
			var a = confirm("Are you sure you want to Verified Data?");
			if (a == true) {
				$('#slvl').attr('action', 'rfv_slvl');
				$('#slvl').submit();
			} else {
				return false;
			} 
		});

		$('#fv').click(function() {
			var a = confirm("Are you sure you want to Verified Data?");
			if (a == true) {
				$('#slvl').attr('action', 'fv_slvl');
				$('#slvl').submit();
			} else {
				return false;
			} 
		});

		$('#nb').click(function() {
			var a = confirm("Are you sure you want to Processed Data?");
			if (a == true) {
				$('#slvl').attr('action', 'nb_slvl');
				$('#slvl').submit();
			} else {
				return false;
			} 
		});

		$('#afp').click(function() {
			var a = confirm("Are you sure you want to Processed Data?");
			if (a == true) {
				$('#slvl').attr('action', 'afp_slvl');
				$('#slvl').submit();
			} else {
				return false;
			} 
		});

		$("#checkAll").click(function(){
   	 $('input:checkbox').not(this).prop('checked', this.checked);
		});



	});	
</script>

