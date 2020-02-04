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
	input { 
    text-align: center; 
	}	 
	p {
		color: red;
	}
</style>
<div class="container">
	<?php if($this->session->flashdata('cutoff_msg')) : ?>
	   <p class="alert alert-dismissable alert-success"><?php echo $this->session->flashdata('cutoff_msg'); ?></p>
	<?php endif; ?>
	<h3 align="center">CUT-OFF DATE</h3>
	
	<br />
	<center>
		<form method="post" action="<?php echo base_url(); ?>index.php/payroll/set_cutoff_date" enctype="multipart/form-data">
			<div class="row">
				<p><?php echo validation_errors(); ?></p>
        <div class="col-md-6">
          <div class="form-group">
              <label for="form_name">START</label>
              <input id="form_name" type="text" name="start_date" class="form-control" placeholder="YYYY-MM-DD">
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
              <label for="form_name">END</label>
              <input id="form_name" type="text" name="end_date" class="form-control" placeholder="YYYY-MM-DD">
          </div>
        </div>	
		  </div>        
			<button type="submit" class="btn btn-primary" >Submit</button>
		</form>
	</center>
</div>


