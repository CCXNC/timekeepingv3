<style type="text/css">
	.container {
		margin-top: 60px;
		margin-left: 250px;
	}
	h3,h5 {
		color: green;
	}
	.row {
		margin-left: 80px;
	}
</style>
<div class="container">

	<div class="col-sm-8">
	  <div class="panel panel-primary">
	    <div class="panel-heading">
	        <h3 class="panel-title">Holiday & Events Calendar Form</h3>
        	
	    </div>
	    <div class="panel-body">
	    	<?php if($this->session->flashdata('add_msg')) : ?>
			     <p class="alert alert-dismissable alert-success"><?php echo $this->session->flashdata('add_msg'); ?></p>
				<?php endif; ?>
	    	<center><h3>NEW HORIZON FINANCE CORP</h3></center>
	    	<br><br>
	    	<form method="POST" action="<?php echo base_url(); ?>index.php/master/add_holiday">
	    		<div style="color:red"><?php echo validation_errors(); ?> </div>
	    		<div class="row">
			    	<div class="col-md-10">
		          <div class="form-group">
		              <label for="form_name">TYPE</label>
		              <select class="form-control" name="holiday_type">
		              	<option value=" ">SELECT</option>
		              	<option value="LH">LEGAL HOLIDAY</option>
		              	<option value="SH">SPECIAL HOLIDAY</option>
		              	<option value="SE">EVENTS</option>
		              </select>	
		          </div>
		      	</div>    
			   	</div>	
			   	<div class="row">
			   		<div class="col-md-10">
	            <div class="form-group">
	                <label for="form_name">DATE</label>
	                <input id="form_name" type="date" name="date" class="form-control">
	            </div>
	          </div>
			   	</div>	
			   	<div class="row">
	          <div class="col-md-10">
	            <div class="form-group">
	              <label for="form_name">BRANCHES</label>
	              <select class="form-control" name="branch_id">
	               	<option value="">SELECT</option>
	              	<option value="ALL">ALL</option>
	              	<?php if($branches) : ?>
	              		<?php foreach($branches as $branch) : ?>
	              			<option value="<?php echo $branch->id;?>"><?php echo $branch->name; ?></option>
	              		<?php endforeach; ?>
	              	<?php endif; ?>	
	              </select>	
	            </div>
	          </div>
	        </div>
	        <div class="row">
	          <div class="col-md-10">
	            <div class="form-group">
	                <label for="form_name">DESCRIPTION</label>
	                <input id="form_name" type="text" name="description" class="form-control">
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
