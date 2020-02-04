<style type="text/css">
	.container {
		margin-top: 180px;
		margin-left: 300px;
	}
	input {
		text-align: center;
	}
</style>
<div class="container">
 	<div class="col-sm-7">
 		<?php if($this->session->flashdata('employee_process')) : ?>
		  <center><p class="alert alert-dismissable alert-success"><?php echo $this->session->flashdata('employee_process'); ?></p></center>
		<?php endif; ?>
	  <div class="panel panel-primary">
	    <div class="panel-heading">
	        <h3 class="panel-title">Time Keeping Form</h3>
	    </div>
	    <div class="panel-body">
	    	<center>
	    		<form method="POST" action="<?php echo base_url(); ?>index.php/reports/employee_timekeeping">
	    			<div class="col-md-4">
		          <div class="form-group">
		              <label for="form_name">Employee Name</label>
		              <select class="form-control" name="employee_no">
		              	<?php if($employees) : ?>
		              		<?php foreach($employees as $emp) : ?>
		              			<option value="<?php echo $emp->employee_number; ?>"><?php echo $emp->name; ?></option>
		              		<?php endforeach; ?>
		              	<?php endif; ?>	
		              </select>	
		          </div>
	        	</div>
	        	<div class="col-md-4">
		          <div class="form-group">
		              <label for="form_name">Start Date</label>
		              <input id="form_name" type="text" name="start_date" class="form-control" value="<?php echo $cut_off->start_date; ?>">
		          </div>
	        	</div>
	        	<div class="col-md-4">
		          <div class="form-group">
		              <label for="form_name">End Date</label>
		              <input id="form_name" type="text" name="end_date" class="form-control" value="<?php echo $cut_off->end_date; ?>">
		          </div>
	        	</div>
	        	<button type="submit" class="btn btn-primary">Submit</button>
	    		</form>
	    	</center>
	    </div>
	  </div>
  </div>
</div>

