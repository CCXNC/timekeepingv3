<style type="text/css">
	.container {
		margin-top: 100px;
	}
</style>
<div class="container">
  <div style="color:red">
   <?php echo validation_errors(); ?> 
  </div>

  <?php if($this->session->flashdata('item_added')) : ?>
      <p class="alert alert-dismissable alert-success"><?php echo $this->session->flashdata('item_added'); ?></p>
  <?php endif; ?>
  
  <h2>ADD IN/OUT</h2>
  <form class="form-horizontal" action="<?php echo base_url(); ?>index.php/reports/add_in_out" method="post">
    <div class="form-group">
      <input type="hidden" class="form-control" name="in_id" value="<?php echo $in->id; ?>">
      <label class="control-label col-sm-2">Employee Number:</label>
      <div class="col-sm-6">
        <input type="text" class="form-control" name="employee_number" value="<?php echo $in->employee_number; ?>">
      </div>
    </div>
    <div class="form-group">
      <label class="control-label col-sm-2" >Name:</label>
      <div class="col-sm-6">          
        <input type="text" class="form-control"  name="name" value="<?php echo $in->name; ?>">
      </div>
    </div>
    <div class="form-group">
      <label class="control-label col-sm-2" >Date:</label>
      <div class="col-sm-6">          
        <input type="text" class="form-control"  name="date" placeholder="YYYY-MM-DD">
      </div>
    </div>
    <div class="form-group">
      <label class="control-label col-sm-2" >Time:</label>
      <div class="col-sm-6">          
        <input type="text" class="form-control"  name="time" placeholder="YYYY-MM-DD HH:MM:SS">
      </div>
    </div>
    <div class="form-group">
      <label class="control-label col-sm-2" >Status:</label>
      <div class="col-sm-6">          
        <input type="text" class="form-control"  name="status">
      </div>
    </div>
    <div class="form-group">        
      <div class="col-sm-offset-2 col-sm-10">
        <button type="submit" class="btn btn-default">Submit</button>
      </div>
    </div>
  </form>
</div>