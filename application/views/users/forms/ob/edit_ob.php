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
	        <h3 class="panel-title">EDIT OB FORM</h3>
        	
	    </div> 
	    <div class="panel-body">
	    	<?php if($this->session->flashdata('add_msg')) : ?>
			     <p class="alert alert-dismissable alert-success"><?php echo $this->session->flashdata('add_msg'); ?></p>
				<?php endif; ?>
	    	<center><h3>NEW HORIZON FINANCE CORP</h3></center>
	    	<center><h5>OFFICIAL BUSSINESS FORM</h5></center>
	    	<br><br>
	    	<form method="POST" action="<?php echo base_url(); ?>index.php/users/edit_ob/<?php echo $ob->id; ?>">
	    		<div style="color:red"><?php echo validation_errors(); ?> </div>
	    		<div class="row">
			    	<div class="col-md-5">
	           <div class="form-group">
              <label for="form_name">EMPLOYEE NAME</label>
	             	<?php if($employee) : ?>
              		<input id="form_name" type="text" readonly="" class="form-control" value="<?php echo $employee->name; ?>">
              		<input type="hidden" name="name" value="<?php echo $employee->name . '|' . $employee->employee_number; ?>">
              	<?php endif; ?>	
	            </div>
			   		</div>
			   		<div class="col-md-5">
	            <div class="form-group">
	                <label for="form_name">DATE OF OB</label>
	                <input id="form_name" type="date"  readonly="" name="date" class="form-control" value="<?php echo $ob->date_ob; ?>">
	            </div>
	          </div>
			   	</div>	
			  	<div class="row">
			  		<div class="col-md-10">
		          <div class="form-group">
		              <label for="form_name">TYPE</label>
		              <select class="form-control" name="ob_type">
		              	<option value=" ">SELECT</option>
		              	<option value="WD"<?php echo $ob->type_ob == "WD" ? 'Selected' : ' '; ?>>WHOLE DAY</option>
		              	<option value="IN"<?php echo $ob->type_ob == "IN" ? 'Selected' : ' '; ?>>OB (IN) AM</option>
		              	<option value="OUT"<?php echo $ob->type_ob == "OUT" ? 'Selected' : ' '; ?>>OB (OUT) PM</option>
		              </select>	
		          </div>
		      	</div>    
			   	</div>	
			    
		    	<div class="row">
	          <div class="col-md-5">
	            <div class="form-group">
	                <label for="form_name">SITE/DESIGNATION</label>
	                <input id="form_name" type="text" name="site_from" class="form-control" value="<?php echo $ob->site_from; ?>">
	            </div>
	          </div>
	          <div class="col-md-5">
	            <div class="form-group">
	                <label for="form_name">&nbsp; </label>
	                <input id="form_name" type="text" name="site_to" class="form-control" value="<?php echo $ob->site_to; ?>">
	            </div>
	          </div>
	        </div> 
	        <div class="row">
	          <div class="col-md-10">
	            <div class="form-group">
	                <label for="form_name">PURPOSE (S)</label>
	                <input id="form_name" type="text" name="purpose" class="form-control" value="<?php echo $ob->purpose; ?>">
	            </div>
	          </div>
	        </div>
	        <div class="row">
	          <div class="col-md-5">
	            <div class="form-group">
	                <label for="form_name">TIME</label>
	                <input id="form_name" type="time" name="time_of_departure" class="form-control" value="<?php echo $ob->time_in; ?>">
	            </div>
	          </div>
	          <div class="col-md-5">
	            <div class="form-group">
	                <label for="form_name">&nbsp; </label>
	                <input id="form_name" type="time" name="time_of_return" class="form-control" value="<?php echo $ob->time_out; ?>">
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
