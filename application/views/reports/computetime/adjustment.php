<style type="text/css">
	.container {
		margin-top: 100px;
		margin-left: 280px;
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
	        <h3 class="panel-title">ADJUSTMENT FORM</h3>
        	
	    </div>
	    <div class="panel-body">
		    <?php if($this->session->flashdata('add_msg')) : ?>
				   <p class="alert alert-dismissable alert-success"><?php echo $this->session->flashdata('add_msg'); ?></p>
				<?php endif; ?>
	    	<form method="POST" action="<?php echo base_url(); ?>index.php/reports/adjustment_total_compute">
	    		<div style="color:red"><?php echo validation_errors(); ?> </div>
	    		<div class="row">
	    			<div class="col-md-5">
	            <div class="form-group">
	                <label for="form_name">EMPLOYEE NUMBER </label>
	                <input id="form_name" type="text" name="employee_number" class="form-control">
	            </div>
			   		</div>
			    	
			   		<div class="col-md-5">
	            <div class="form-group">
	                <label for="form_name">DATE</label>
	                <input id="form_name" type="text" name="date" readonly="" class="form-control" placeholder="YYYY-MM-DD" value="<?php echo $cut_off->end_date; ?>">
	            </div>
	          </div>
			   	</div>	
			   	<div class="row">
			   		<div class="col-md-10">
			   			<div class="form-group">
	                <label for="form_name">NAME</label>
	                <input id="form_name" type="text" name="name" class="form-control">
	            </div>
	          </div> 
			   	</div>
			   	<div class="row">
			   		<div class="col-md-5">
		          <div class="form-group">
		              <label for="form_name">TYPE</label>
		              <select class="form-control" name="adjust_type">
		              	<option value=" ">SELECT</option>
		              	<option value="ROT|Adjustment">Total Overtime</option>
		              	<option value="NDT|Adjustment">Night Differential OT</option>
		              	<option value="LHOT|Adjustment">Legal Holiday OT</option>
		              	<option value="SHOT|Adjustment">Special Holiday OT</option>
		              	<option value="RDOT|Adjustment">RestDay OT</option>
		              	<option value="TARD|Adjustment">Tardiness</option>
		              	<option value="UNDER|Adjustment">Undertime</option>
		              	<option value="AB|Adjustment">Absences</option>
              			<option value="SL|Adjustment">Sick Leave</option>
              			<option value="VL|Adjustment">Vacation Leave</option>
		              </select>	
		          </div>
		      	</div>    
		      	<div class="col-md-5">
	            <div class="form-group">
	                <label for="form_name">&nbsp;</label>
	                <input id="form_name" type="text" name="adjustment" class="form-control" placeholder="ADJUSTMENT">
	            </div>
	          </div>
			   	</div>	
			   	<div class="row">
			   		<div class="col-md-10">
	            <div class="form-group">
	                <label for="form_name">REMARKS</label>
	                <input id="form_name" type="text" name="remarks" class="form-control">
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
