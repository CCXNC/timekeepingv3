<style type="text/css">
	.container{
		margin-top: 100px;
		margin-left: 150px;
	}

</style>

<div class="container">
  <div class="col-sm-5">
	  <div class="panel panel-primary">
	    <div class="panel-heading">
	        <h3 class="panel-title">Recent Activity</h3>
	    </div>
	    <div class="panel-body">
	  		<?php echo "emp_no_id" . ' : ' . $this->session->userdata('emp_no_id') . ' | ' . "department_id" . ' : ' . $this->session->userdata('department_id') . ' | ' . "branch_id" . ' : ' . $this->session->userdata('branch_id') . ' | ' . "supervisor_id" . ' : ' . $this->session->userdata('supervisor_id') . ' | ' . "head_id" . ' : ' . $this->session->userdata('head_id') . ' | ' . "user_level" . ' : ' . $this->session->userdata('user_level') . ' | ' . "is_hr" . ' : ' . $this->session->userdata('is_hr') . ' | ' . "is_gm" . ' : ' . $this->session->userdata('is_gm') . ' | ' . "is_cfo" . ' : ' . $this->session->userdata('is_cfo');?>
	    </div>
	  </div>
  </div>
  <div class="col-sm-5">
	  <div class="panel panel-primary">
	    <div class="panel-heading">
	        <h3 class="panel-title">Recent Time</h3>
	    </div>
	    <div class="panel-body">
	  
	    </div>
	  </div>
  </div>

  <div class="col-sm-5">
	  <div class="panel panel-primary">
	    <div class="panel-heading">
	        <h3 class="panel-title">Company Details</h3>
	    </div>
	    <div class="panel-body">
	        <b>NHFC</b> -NEW HORIZON FINANCE CORP.
	        <br>
	        <b>GTLIC</b> -GOLDEN TREASURE LENDING INVESTOR CORP.  
	        <br><br><br><br>
	    </div>
	  </div>
  </div>
</div>