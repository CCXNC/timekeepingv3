 <style type="text/css">
	.container {
		margin-top: 60px;
		margin-left: 250px;
	}
	h3,h5 {
		color: green;
	}
	.row {
		margin-left: 50px;
	}
</style>
<div class="container">

	<div class="col-sm-8">
	  <div class="panel panel-primary">
	    <div class="panel-heading">
	        <h3 class="panel-title">SL/VL FORM</h3>
        	
	    </div>
	    <div class="panel-body">
	    	<?php if($this->session->flashdata('add_msg')) : ?>
			     <p class="alert alert-dismissable alert-success"><?php echo $this->session->flashdata('add_msg'); ?></p>
				<?php endif; ?>
	    	<center><h3>NEW HORIZON FINANCE CORP</h3></center>
	    	<center><h5>REQUEST FOR LEAVE OR ABSENT</h5></center>
	    	<br><br><br>
	    	<form method="POST" action="<?php echo base_url(); ?>index.php/reports/add_slvl">
	    		<div style="color:red"><?php echo validation_errors(); ?> </div>
	    		<div class="row">
		    		<div class="col-md-5">
		          <div class="form-group">
		              <label for="form_name">TYPE</label>
		              <select class="form-control" name="slvl_type">
		              	<option value=" ">SELECT</option>
		              	<option value="VL|VACATION LEAVE">VACATION LEAVE</option>
		              	<option value="AB|VACATION LEAVE W/OUT PAY">VACATION LEAVE  W/OUT PAY</option>
		              	<option value="SL|SICK LEAVE">SICK LEAVE</option>
		              	<option value="AB|SICK LEAVE W/OUT PAY">SICK LEAVE W/OUT PAY</option>
		              	<option value="AB|AWOL">AWOL</option>
		              	<option value="AB|SUSPENDED">SUSPENDED</option>
		               	<option value="ML|MATERNITY LEAVE">MATERNITY LEAVE</option>
		              	<option value="PL|PATERNITY LEAVE">PATERNITY LEAVE</option>
		              	<option value="EL|EMERGENCY LEAVE">EMERGENCY LEAVE</option>
		              	<option value="CL|Calamity Leave">CALAMITY LEAVE</option>
		              	<option value="BL|BEREAVEMENT Leave">BEREAVEMENT LEAVE</option>
						<option value="AB|ABSENT">ABSENT</option>
						<option value="SSS|SSS SICKNESS LEAVE">SSS SICKNESS LEAVE</option>
		              </select>	
		          </div>
			    	</div>
			    	<div class="col-md-5">
						<div class="form-group">
							<label for="form_name">&nbsp;</label>
							<select class="form-control" name="HF">
								<option value=" ">SELECT</option>
								<option value="WD">WHOLEDAY</option>
								<option value="HFAM">HALF DAY (AM)</option>
								<option value="HFPM">HALF DAY (PM)</option>
							</select>	
						</div>
			    	</div> 
			   	</div>	
			   	<div class="row">
			   		<div class="col-md-10">
						<div class="form-group">
							<label for="form_name">EMPLOYEE NAME</label>
							<select class="form-control" name="name">
								<option value=" ">SELECT</option>
								<?php if($employees) : ?>
									<?php foreach($employees as $emp) : ?>
										<option value="<?php echo $emp->name . '|' . $emp->employee_number . '|' . $emp->branch_id . '|' . $emp->department_id . '|' . $emp->sl_credit . '|' . $emp->vl_credit . '|' . $emp->elcl_credit . '|' . $emp->fl_credit; ?>"><?php echo $emp->name; ?></option>
									<?php endforeach; ?>
								<?php endif; ?>	
							</select>	
						</div>
			   		</div>
			   	</div>	
			    		
		    	<div class="row">
	          <div class="col-md-10">
	            <div class="form-group">
	                <label for="form_name">EFFECTIVE DATE OF LEAVE</label>
	                <input id="form_name" type="date" name="start_date" class="form-control" placeholder="START YYYY-MM-DD">
	            </div>
	          </div>
	        </div> 
	        <div class="row">
	          <div class="col-md-10">
	            <div class="form-group">
	                <label for="form_name">REASON</label>
	                <input id="form_name" type="text" name="reason" class="form-control">
	            </div>
	          </div>
	        </div> 
         	<div class="row">
	          <div class="col-md-10">
	              <center><input type="submit" class="btn btn-primary btn-send" value="Submit"></center>
	          </div>
		      </div>
	    	</form>
	    </div>  
	  </div>
	</div>
</div>
