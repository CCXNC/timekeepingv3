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
	        <h3 class="panel-title">OT FORM</h3>
        	
	    </div>
	    <div class="panel-body">
	    	<?php if($this->session->flashdata('add_msg')) : ?>
			     <p class="alert alert-dismissable alert-success"><?php echo $this->session->flashdata('add_msg'); ?></p>
				<?php endif; ?>
	    	<center><h3>NEW HORIZON FINANCE CORP</h3></center>
	    	<center><h5>REQUEST FOR OVERTIME</h5></center>
	    	<br><br>
	    	<form method="POST" action="<?php echo base_url(); ?>index.php/reports/add_ot">
	    		<div style="color:red"><?php echo validation_errors(); ?> </div>
	    		<div class="row">
			    	<div class="col-md-5">
		          <div class="form-group">
		              <label for="form_name">TYPE</label>
		              <select class="form-control" name="ot_type">
		              	<option value=" ">SELECT</option>
		              	<option value="ROT|Regular OT">Regular OT</option>
		              	<option value="LHOT|Legal Holiday OT">Legal Holiday OT</option>
		              	<option value="SHOT|Special Holiday OT">Special Holiday OT</option>
		              	<option value="RDOT|RestDay OT">RestDay OT</option>
		              </select>	
		          </div>
		      	</div>    
			   		<div class="col-md-5">
	            <div class="form-group">
	                <label for="form_name">DATE</label>
	                <input id="form_name" type="text" name="date" class="form-control" placeholder="YYYY-MM-DD">
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
	              			<option value="<?php echo $emp->name . '|' . $emp->employee_number; ?>"><?php echo $emp->name; ?></option>
	              		<?php endforeach; ?>
	              	<?php endif; ?>	
	              </select>	
	            </div>
			   		</div>
			   	</div>	
			  	<div class="row">
			  	  <div class="col-md-5">
	            <div class="form-group">
	                <label for="form_name">Time In</label>
	                <input id="form_name" type="text" name="time_in" class="form-control" placeholder="HH:MM:SS">
	            </div>
	          </div>
	          <div class="col-md-5">
	            <div class="form-group">
	                <label for="form_name">Time Out</label>
	                <input id="form_name" type="text" name="time_out" class="form-control" placeholder="HH:MM:SS">
	            </div>
	          </div>
			   	</div>	
			    
	        <div class="row">
	          <div class="col-md-10">
	            <div class="form-group">
	                <label for="form_name">NATURE OF WORK </label>
	                <input id="form_name" type="text" name="nature_of_work" class="form-control">
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
