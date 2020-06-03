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
	        <h3 class="panel-title">OB FORM</h3>
        	
	    </div> 
	    <div class="panel-body">
	    	<?php if($this->session->flashdata('add_msg')) : ?>
			    <p class="alert alert-dismissable alert-success"><?php echo $this->session->flashdata('add_msg'); ?></p>
			<?php endif; ?>
	    	<center><h3>NEW HORIZON FINANCE CORP</h3></center>
	    	<center><h5>OFFICIAL BUSSINESS FORM</h5></center>
	    	<br><br>
	    	<form method="POST" action="<?php echo base_url(); ?>index.php/reports/add_ob">
	    		<div style="color:red"><?php echo validation_errors(); ?> </div>
	    		<div class="row">
			    	<div class="col-md-5">
	           <div class="form-group">
              <label for="form_name">EMPLOYEE NAME</label>
	              <select class="form-control" name="name">
	              	<option value=" ">SELECT</option>
	              	<?php if($employees) : ?>
	              		<?php foreach($employees as $emp) : ?>
	              			<option value="<?php echo $emp->name . '|' . $emp->employee_number . '|' . $emp->branch_id . '|' . $emp->department_id; ?>"><?php echo $emp->name; ?></option>
	              		<?php endforeach; ?>
	              	<?php endif; ?>	
	              </select>	
	            </div>
			   		</div>
			   		<div class="col-md-5">
	            <div class="form-group">
	                <label for="form_name">DATE OF OB</label>
	                <input id="form_name" type="date" name="date" class="form-control" placeholder="YYYY-MM-DD">
	            </div>
	          </div>
			   	</div>	
				<div class="row">
			  		<div class="col-md-10">
						<div class="form-group">
							<label for="form_name">TYPE</label>
							<select class="form-control" name="ob_type">
								<option value=" ">SELECT</option>
								<option value="WD">WHOLE DAY</option>
								<option value="IN">OB (IN) AM</option>
								<option value="OUT">OB (OUT) PM</option>
							</select>	
						</div>
		      		</div>    
			   	</div>	
			    
		    	<div class="row">
	          <div class="col-md-5">
	            <div class="form-group">
	                <label for="form_name">SITE/DESIGNATION</label>
	                <input id="form_name" type="text" name="site_from" class="form-control" placeholder="FROM">
	            </div>
	          </div>
	          <div class="col-md-5">
	            <div class="form-group">
	                <label for="form_name">&nbsp; </label>
	                <input id="form_name" type="text" name="site_to" class="form-control" placeholder="TO">
	            </div>
	          </div>
	        </div> 
	        <div class="row">
	          <div class="col-md-10">
	            <div class="form-group">
	                <label for="form_name">PURPOSE (S)</label>
	                <input id="form_name" type="text" name="purpose" class="form-control">
	            </div>
	          </div>
	        </div>
	        <div class="row">
	          <div class="col-md-5">
	            <div class="form-group">
	                <label for="form_name">TIME</label>
	                <input id="form_name" type="time" name="time_of_departure" class="form-control" placeholder="DEPARTURE HH:MM:SS">
	            </div>
	          </div>
	          <div class="col-md-5">
	            <div class="form-group">
	                <label for="form_name">&nbsp;</label>
	                <input id="form_name" type="time" name="time_of_return" class="form-control" placeholder="RETURN HH:MM:SS">
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
