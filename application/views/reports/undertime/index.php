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
	.a {
		float: right;
		margin-right: 5px;
	}
</style> 
<div class="margin">
<?php if($this->session->flashdata('add_msg')) : ?>
   <p class="alert alert-dismissable alert-success"><?php echo $this->session->flashdata('add_msg'); ?></p>
<?php endif; ?>
<?php if($this->session->flashdata('update_msg_ut')) : ?>
   <p class="alert alert-dismissable alert-success"><?php echo $this->session->flashdata('update_msg_ut'); ?></p>
<?php endif; ?>
<?php if($this->session->flashdata('cancel_msg')) : ?>
      <p class="alert alert-dismissable alert-danger"><?php echo $this->session->flashdata('cancel_msg'); ?></p>
<?php endif; ?>  
<!-- TABLE OF BRANCHES -->
<div class="row">
	<div class="col-lg-12">
  	<div class="panel panel-primary">
    	<div class="panel-heading">
      	Undertime List
		<a href="<?php echo base_url(); ?>index.php/reports/add_undertime" class="a btn btn-default">ADD</a>
      </div>	
        <form method="post" id="ut">
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
						        <br>
						        <button type="submit" class="btn btn-default">Load</button>
						        	<input class="btn btn-success" id="rfa" type="submit" value="RFA">  
							        <input class="btn btn-info" id="fa" type="submit" value="FA">   
							        <input class="btn btn-default" id="rfv" type="submit" value="RFV"> 
							        <input class="btn btn-danger" id="fv" type="submit" value="FV"> 
							        <input class="btn btn-warning" id="nb" type="submit" value="FN">
							        <input class="btn btn-primary" id="afp" type="submit" value="AFP"> 
								  </div> 
			            <thead>
		                <tr>
							<th><center><input type="checkbox" id="checkAll" name=""></center></th>
							<th>Name</th>
							<th>Date</th>
							<th>Time Out</th>
							<th>UT NO</th>
							<th>Reason</th>
							<th>Status</th>
							<th><center>Action</center></th>
		                </tr>
			            </thead>
			            	<?php if($uts) : ?>
				          		<?php foreach($uts as $undertime) : ?>
				          			<tr>
										<td>
											<center><input type="checkbox" name="employee[]" value="<?php echo $undertime->id . '|' . $undertime->employee_number . '|' . $undertime->name . '|' . $undertime->date_ut . '|' . $undertime->ut_no . '|' . $undertime->type ; ?>"> </center>
										</td>
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
											  	<a class="btn btn-danger btn-xs delete-btn" onclick="return confirm('Do you want to delete?');" href="<?php echo base_url() ?>index.php/reports/delete_undertime/<?php echo $undertime->id; ?>/<?php echo $undertime->employee_number; ?>/<?php echo $undertime->type; ?>">Delete</a>
				          						<!-- <a href="<?php //echo base_url(); ?>index.php/reports/edit_undertime/<?php //echo $undertime->id; ?>" class="btn-sm btn-primary">Edit</a> -->
												<a onclick="return confirm('Do you want to Cancel ut?');" href="<?php echo base_url(); ?>index.php/users/cancelled_undertime/<?php echo $undertime->id; ?>" class="btn-sm btn-danger">Cancelled</a>
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
		
		$('#rfa').click(function() {
			var a = confirm("Are you sure you want to Approved Data?");
			if (a == true) {
				$('#ut').attr('action', 'rfa_ut');
				$('#ut').submit();
			} else {
				return false;
			} 
		});

		$('#fa').click(function() {
			var a = confirm("Are you sure you want to Approved Data?");
			if (a == true) {
				$('#ut').attr('action', 'fa_ut');
				$('#ut').submit();
			} else {
				return false;
			} 
		});

		$('#rfv').click(function() {
			var a = confirm("Are you sure you want to Verified Data?");
			if (a == true) {
				$('#ut').attr('action', 'rfv_ut');
				$('#ut').submit();
			} else {
				return false;
			} 
		});

		$('#fv').click(function() {
			var a = confirm("Are you sure you want to Verified Data?");
			if (a == true) {
				$('#ut').attr('action', 'fv_ut');
				$('#ut').submit();
			} else {
				return false;
			} 
		});

		$('#nb').click(function() {
			var a = confirm("Are you sure you want to Processed Data?");
			if (a == true) {
				$('#ut').attr('action', 'nb_ut');
				$('#ut').submit();
			} else {
				return false;
			} 
		});

		$('#afp').click(function() {
			var a = confirm("Are you sure you want to Processed Data?");
			if (a == true) {
				$('#ut').attr('action', 'afp_ut');
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

