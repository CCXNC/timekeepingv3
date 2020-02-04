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
	        <h3 class="panel-title">EDIT OT FORM</h3>
        	
	    </div>
	    <div class="panel-body">
	    	<?php if($this->session->flashdata('add_msg_ot')) : ?>
			     <p class="alert alert-dismissable alert-success"><?php echo $this->session->flashdata('add_msg_ot'); ?></p>
				<?php endif; ?>
	    	<center><h3>NEW HORIZON FINANCE CORP</h3></center>
	    	<center><h5>REQUEST FOR OVERTIME</h5></center>
	    	<br><br>
	    	<form method="POST" action="<?php echo base_url(); ?>index.php/users/edit_ot/<?php echo $ot->id; ?>">
	    		<div style="color:red"><?php echo validation_errors(); ?> </div>
	    		<div class="row">
			    	<div class="col-md-5">
		          <div class="form-group">
		              <label for="form_name">TYPE</label>
		              <select class="form-control" name="ot_type">
		              	<option value=" ">SELECT</option>
		              	<option value="ROT|Regular OT"<?php echo $ot->type == 'ROT' ? 'selected' : ' '; ?>>Regular OT</option>
		              	<option value="LHOT|Legal Holiday OT"<?php echo $ot->type == 'LHOT' ? 'selected' : ' '; ?>>Legal Holiday OT</option>
		              	<option value="SHOT|Special Holiday OT"<?php echo $ot->type == 'SHOT' ? 'selected' : ' '; ?>>Special Holiday OT</option>
		              	<option value="RDOT|RestDay OT"<?php echo $ot->type == 'RDOT' ? 'selected' : ' '; ?>>RestDay OT</option>
		              </select>	
		          </div>
		      	</div>    
			   		<div class="col-md-5">
	            <div class="form-group">
	                <label for="form_name">DATE</label>
	                <input id="form_name" type="date" name="date" class="form-control" value="<?php echo $ot->date_ot; ?>">
	            </div>
	          </div>
			   	</div>	
			   	<div class="row">
			   		<div class="col-md-10">
	            <div class="form-group">
	              <label for="form_name">EMPLOYEE NAME</label>
		             	<?php if($employee) : ?>
	              		<input id="form_name" type="text" readonly="" class="form-control" value="<?php echo $employee->name; ?>">
	              		<input type="hidden" name="name" value="<?php echo $employee->name; ?>">
	              		<input type="hidden" name="employee_number" value="<?php echo $employee->employee_number; ?>">
	              	<?php endif; ?>	
	            </div>
			   		</div>
			   	</div>	
			  	<div class="row">
			  	  <div class="col-md-5">
	            <div class="form-group">
	                <label for="form_name">Time In</label>
	                <input id="form_name" type="time" name="time_in" class="form-control" value="<?php echo $ot->time_in; ?>">
	            </div>
	          </div>
	          <div class="col-md-5">
	            <div class="form-group">
	                <label for="form_name">Time Out</label>
	                <input id="form_name" type="time" name="time_out" class="form-control" value="<?php echo $ot->time_out; ?>">
	            </div>
	          </div>
			   	</div>	
			    
	        <div class="row">
	          <div class="col-md-10">
	            <div class="form-group">
	                <label for="form_name">NATURE OF WORK </label>
	                <input id="form_name" type="text" name="nature_of_work" class="form-control" value="<?php echo $ot->nature_of_work; ?>">
	            </div>
	          </div>
	        </div>
         	<div class="row">
	          <div class="col-md-10">
	              <center><input type="submit" class="btn btn-primary btn-send" value="Update"></center>
	          </div>
		      </div>
	    	</form>
	    </div>  
	  </div>
	</div>
</div>
