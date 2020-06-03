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
	        <h3 class="panel-title">DELETE UPLOAD/CSV FORM</h3>
        	
	    </div>
	    <div class="panel-body">
		    <?php if($this->session->flashdata('delete_msg')) : ?>
				   <p class="alert alert-dismissable alert-danger"><?php echo $this->session->flashdata('delete_msg'); ?></p>
				<?php endif; ?>
	    	<form method="POST" action="<?php echo base_url(); ?>index.php/reports/index_adjustment">
	    		<div style="color:red"><?php echo validation_errors(); ?> </div>
	    		<div class="row">
			   		<div class="col-md-10">
		          <div class="form-group">
		              <label for="form_name">BRANCH</label>
		              <select class="form-control" name="branch_id">
		              	<option value=" ">SELECT</option>
		              	<option value="1">ALABANG</option>
		              	<option value="2">ALAMINOS</option>
		              	<option value="3">BACLARAN</option>
		              	<option value="4">BAGUIO</option>
		              	<option value="5">BALAGTAS</option>
		              	<option value="6">BAMBANG</option>
		              	<option value="7">BANGUED</option>
		              	<option value="8">BATANGAS</option>
		              	<option value="9">BONTOC</option>
		              	<option value="10">CANDON</option>
		              	<option value="11">DAGUPAN</option>
		              	<option value="12">DIVISORIA</option>
		              	<option value="13">LA UNION</option>
		              	<option value="14">LEGAZPI</option>
		              	<option value="15">NAGA</option>
		              	<option value="16">NOVALICHES</option>
		              	<option value="17">ROXAS</option>
		              	<option value="18">SAN JUAN</option>
		              	<option value="19">SAN PABLO</option>
		              	<option value="20">SANTIAGO</option>
		              	<option value="21">SOLANO</option>
		              	<option value="22">TABUK</option>
		              	<option value="23">VIGAN</option>
		              	<option value="24">ZAMBALES</option>
		              </select>	
		          </div>
		      	</div>   
			   	</div>	

			   	<div class="row">
			   		<div class="col-md-5">
	            <div class="form-group">
	                <label for="form_name">START DATE</label>
	                <input id="form_name" type="date" name="start_date" class="form-control" placeholder="YYYY-MM-DD">
	            </div>
	          </div>   
			   	
			   		<div class="col-md-5">
	            <div class="form-group">
	                <label for="form_name">END DATE</label>
	                <input id="form_name" type="date" name="end_date" class="form-control" placeholder="YYYY-MM-DD">
	            </div>
	          </div>   
			   	</div>
			   	<div class="row">
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
