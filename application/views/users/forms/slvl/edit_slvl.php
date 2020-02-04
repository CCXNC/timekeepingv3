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
	        <h3 class="panel-title">EDIT SL/VL FORM</h3>
        	
	    </div>
	    <div class="panel-body">
	    	<center><h3>NEW HORIZON FINANCE CORP</h3></center>
	    	<center><h5>REQUEST FOR LEAVE OR ABSENT</h5></center>
	    	<br><br><br>
	    	<form method="POST" action="<?php echo base_url(); ?>index.php/users/edit_slvl/<?php echo $slvl->id; ?>">
	    		<div style="color:red"><?php echo validation_errors(); ?> </div>
	    		<div class="row">
		    		<div class="col-md-5">
		          <div class="form-group">
		              <label for="form_name">TYPE</label>
		              <select class="form-control" name="slvl_type">
		              	<option value=" ">SELECT</option>

		              	<option value="VL|VACATION LEAVE" <?php echo $slvl->type_name == "VACATION LEAVE"  ? 'selected' : ''; ?> >VACATION LEAVE</option>
		              	<option value="AB|VACATION LEAVE W/OUT PAY" <?php echo $slvl->type_name == "VACATION LEAVE W/OUT PAY" ? 'selected' : ''; ?>>VACATION LEAVE  W/OUT PAY</option>
		              	<option value="SL|SICK LEAVE" <?php echo $slvl->type_name == "SICK LEAVE" ? 'selected' : ''; ?> >SICK LEAVE</option>
		              	<option value="AB|SICK LEAVE W/OUT PAY" <?php echo $slvl->type_name == "SICK LEAVE W/OUT PAY" ? 'selected' : ''; ?> >SICK LEAVE W/OUT PAY</option>
		              	<option value="ML|MATERNITY LEAVE" <?php echo $slvl->type == 'MATERNITY LEAVE' ? 'selected' : ''; ?> >MATERNITY LEAVE</option>
		              	<option value="PL|PATERNITY LEAVE" <?php echo $slvl->type == 'PATERNITY LEAVE' ? 'selected' : ''; ?> >PATERNITY LEAVE</option>
		              	<option value="EL|EMERGENCY LEAVE" <?php echo $slvl->type_name == 'EMERGENCY LEAVE' ? 'selected' : ''; ?> >EMERGENCY LEAVE</option>
		              	<option value="SH|SPECIAL HOLIDAY W/PAY" <?php echo $slvl->type == 'SH' ? 'selected' : ''; ?> >SPECIAL HOLIDAY W/PAY</option>
		              	<option value="SHW|SPECIAL HOLIDAY W/OUT PAY" <?php echo $slvl->type == 'SHW' ? 'selected' : ''; ?>>SPECIAL HOLIDAY W/OUT PAY</option>
		              </select>	
		          </div>
			    	</div>
			    	<div class="col-md-5">
		          <div class="form-group">
		              <label for="form_name">&nbsp;</label>
		              <select class="form-control" name="HF">
		              	<option value=" ">SELECT</option>
		              	<option value="WD" <?php echo $slvl->sl_am_pm=='WD' ? 'selected' : ''; ?> >WHOLEDAY</option>
		              	<option value="HF" <?php echo $slvl->sl_am_pm=='HF' ? 'selected' : ''; ?> >1/2</option>
		              	<option value="HFAM" <?php echo $slvl->sl_am_pm=='HFAM' ? 'selected' : ''; ?> >HALF DAY (AM)</option>
		              	<option value="HFPM" <?php echo $slvl->sl_am_pm=='HFPM' ? 'selected' : ''; ?> >HALF DAY (PM)</option>
		              </select>	
		          </div>
			    	</div>
			   	</div>
			   	<div class="row">  
			   		<div class="col-md-10">  
	            <div class="form-group">  
	            <label for="form_name">EMPLOYEE NAME</label>  
								<input id="form_name" type="text" class="form-control" readonly="" value="<?php echo $slvl->name ?>">
								<input type="hidden" name="name" value="<?php echo $slvl->name . '|' . $slvl->employee_number; ?>">     
	            </div>
			   		</div>
			   	</div>	
		    	<div class="row">
	          <div class="col-md-10">
	            <div class="form-group">
	                <label for="form_name">EFFECTIVE DATE OF LEAVE</label>
	                <input id="form_name" type="date" name="start_date" class="form-control" placeholder="START YYYY-MM-DD" value="<?php echo $slvl->date_start; ?>">
	            </div>
	          </div>
	        </div> 
	        <div class="row">
	          <div class="col-md-10">
	            <div class="form-group">
	                <label for="form_name">REASON</label>
	                <input id="form_name" type="text" name="reason" class="form-control" value="<?php echo $slvl->reason; ?>">
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
