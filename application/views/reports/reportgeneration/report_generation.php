<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
<style type="text/css">
	.container {
		margin-top: 100px;
	}
	form {
		border: 2px solid;
		width: 50%;
    padding: 10px;
    box-shadow: 5px 10px;
	}
	p {
		color: red;
	}
</style>
<div class="container">
	<?php if($this->session->flashdata('upload_date_msg')) : ?>
	   <p class="alert alert-dismissable alert-success"><?php echo $this->session->flashdata('upload_date_msg'); ?></p>
	<?php endif; ?>
	<h3 align="center">Report Generation</h3>
	
	<br />
	<center>
		<form method="post" action="<?php echo base_url(); ?>index.php/reports/report_generation" enctype="multipart/form-data">
			<div class="row">
				<p><?php echo validation_errors(); ?></p>
        <div class="col-md-6">
          <div class="form-group">
              <label for="form_name">START DATE</label>
              <input id="form_name" type="date" name="start_date" class="form-control" >
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
              <label for="form_name">END DATE</label>
              <input id="form_name" type="date" name="end_date" class="form-control">
          </div>
        </div>	
        
		  </div>  

		  <div class="row">
		  	<div class="col-md-6">
          <div class="form-group">
              <label for="form_name">Company</label>
              <select class="form-control" name="company_id">
              	<option value="">SELECT</option>
              	<option value="0">NHFC</option>
              	<option value="1">GTLIC</option>
              </select>
          </div>
        </div>	
        <div class="col-md-6">
          <div class="form-group">
              <label for="form_name">Type</label>
              <select class="form-control" name="type">
              	<option value="">SELECT</option>
              	<option value="SL">SICK LEAVE</option>
              	<option value="VL">VACATION LEAVE</option>
				<option value="EL">EMERGENCY LEAVE</option>
              	<option value="AB">ABSENCES</option>
              	<option value="OT">OVERTIME</option>
              	<option value="OB">OFFICIAL BUSINESS</option>
              	<option value="UT">UNDERTIME</option>
              </select>
          </div>
        </div>	
		  </div>      
			<button type="submit" class="btn btn-primary" >Generate</button>
		</form>
	</center>
</div>


