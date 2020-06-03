<style type="text/css">
	.container {
		margin-top: 70px;
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
		    <?php if($this->session->flashdata('add_vl')) : ?>
                <p class="alert alert-dismissable alert-success"><?php echo $this->session->flashdata('add_vl'); ?></p>
            <?php endif; ?>
            <?php if($this->session->flashdata('add_sl')) : ?>
                <p class="alert alert-dismissable alert-success"><?php echo $this->session->flashdata('add_sl'); ?></p>
            <?php endif; ?>
            <?php if($this->session->flashdata('add_ab')) : ?>
                <p class="alert alert-dismissable alert-success"><?php echo $this->session->flashdata('add_ab'); ?></p>
            <?php endif; ?>
            <?php if($this->session->flashdata('add_ot')) : ?>
                <p class="alert alert-dismissable alert-success"><?php echo $this->session->flashdata('add_ot'); ?></p>
            <?php endif; ?>
            <?php if($this->session->flashdata('add_ut')) : ?>
                <p class="alert alert-dismissable alert-success"><?php echo $this->session->flashdata('add_ut'); ?></p>
            <?php endif; ?>
	    	<form method="POST" action="<?php echo base_url(); ?>index.php/reports/adjustment">
	    		<div style="color:red"><?php echo validation_errors(); ?> </div>
	    		<div class="row">
			   	    <div class="col-md-5">
                        <div class="form-group">
                            <label for="form_name">Employee Name</label>
                            <select class="form-control" name="name">
                            <?php if($employees) : ?>
                                <?php foreach($employees as $emp) : ?>
                                    <option value="<?php echo $emp->employee_number . '|' . $emp->name . '|' . $emp->branch_id . '|' . $emp->department_id; ?>"><?php echo $emp->name; ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            </select>	
                        </div>
                    </div>   
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="form_name">TYPE</label>
                            <select class="form-control" name="type">
                                <option value="VL|VACATION LEAVE">VACATION LEAVE</option>
                                <option value="AB|VACATION LEAVE W/OUT PAY">VACATION LEAVE  W/OUT PAY</option>
                                <option value="SL|SICK LEAVE">SICK LEAVE</option>
                                <option value="AB|SICK LEAVE W/OUT PAY">SICK LEAVE W/OUT PAY</option>
                                <option value="AB|AWOL">AWOL</option>
                                <option value="AB|SUSPENDED">SUSPENDED</option>
                                <option value="VL|EMERGENCY LEAVE">EMERGENCY LEAVE</option>
                                <option value="ROT|Regular OT|OT">REGULAR OT</option>
                                <option value="LHOT|Legal Holiday OT|OT">LEGAL HOLIDAY OT</option>
                                <option value="SHOT|Special Holiday OT|OT">SPECIAL HOLIDAY OT</option>
                                <option value="RDOT|RestDay OT|OT">RESTDAY OT</option>
                                <option value="UT|Undertime">UNDERTIME</option>
                            </select>	
                        </div>
                    </div>   
			   	</div>	

			   	<div class="row">
			   		<div class="col-md-5">
                        <div class="form-group">
                            <label for="form_name">DATE</label>
                            <input id="form_name" type="date" name="adjust_date" class="form-control">
                        </div>
                    </div>   
			   	
			   		<div class="col-md-5">
                        <div class="form-group">
                            <label for="form_name">CUT-OFF DATE</label>
                            <input id="form_name" type="date" name="cutoff_date" class="form-control">
                        </div>
                    </div>   
			   	</div>
			   	<div class="row">
                   <div class="col-md-5">
                        <div class="form-group">
                            <label for="form_name">Cwwut (Required for Absent)</label>
                            <input id="form_name" type="text" name="cwwut" class="form-control" >
                        </div>
                    </div>
                   <div class="col-md-5">
                        <div class="form-group">
                            <label for="form_name">ADJUSTMENT</label>
                            <input id="form_name" type="text" name="adjustment" class="form-control" >
                        </div>
                    </div>
			   		<div class="col-md-10">
                        <div class="form-group">
                            <label for="form_name">REMARKS</label>
                            <input id="form_name" type="text" name="remarks" class="form-control" >
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
<script type="text/javascript" src="<?php echo base_url(); ?>/assets/js/jquery.min.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$('#submit').alert('');

	});
</script>
