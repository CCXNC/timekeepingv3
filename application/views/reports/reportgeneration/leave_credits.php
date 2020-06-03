<style type="text/css">
	.container {
		margin-top: 60px;
		width: 100%;
	}
	.table {
		width: auto;
	}
	.row {
		margin-left: 15px;
		margin-top: 10px;
	}
	input {
		text-align: center;
	}

</style>
<div class="container">
  <div class="panel panel-primary">
    <div class="panel-heading">
        <h3 class="panel-title">Leave Credit List</h3>
    </div>
    <form method="post">
	    <div class="row">
    		<a href="#" class="btn btn-primary">DOWNLOAD</a> 
    		<a href="#" class="btn btn-primary">RE-GENERATE</a> 
	    </div>
	    <div class="panel-body">
			<?php if($this->session->flashdata('edit_msg')) : ?>
				<p class="alert alert-dismissable alert-success"><?php echo $this->session->flashdata('edit_msg'); ?></p>
			<?php endif; ?>
	    	<table class="table table-bordered table-hover table-striped">
	    		<thead>
	    			<th>Employee Name</th>
	    		
	    		</thead>
	    		<?php if($employees) : ?>
	    			<?php foreach($employees as $emp) : ?>
	    				<tr>
	    					<td><b><?php echo $emp->name; ?></b></td>
	    					<th>CREDIT</th>
			    			<th>USED</th>
			    			<th>BALANCE</th>
							<th><a href="<?php echo base_url(); ?>index.php/reports/edit_leave_credits/<?php echo $emp->leave_id; ?>" class="btn btn-primary btn-sm">Edit</a></th>
	    				</tr>
	    				<tr>
	    					<td><center>SL</center></td>
	    					<td><?php echo $emp->actual_sl_credit; ?></td>
	    					<td>
								<?php
	    						 $used = $emp->sl_credit;
	    						 $compute_sl = $emp->actual_sl_credit - $emp->sl_credit;
	    						 echo $compute_sl;
	    						?>
	    					</td>
	    					<td><?php echo $emp->sl_credit; ?></td>
	    					
	    				</tr>
	    				<tr>
	    					<td><center>VL</center></td>
	    					<td><?php echo $emp->actual_vl_credit ?></td>
		    				<td>
	    						<?php 
	    						 $used = $emp->actual_vl_credit;
	    						 $compute_vl =$emp->actual_vl_credit - $emp->vl_credit;
	    						 echo $compute_vl;
	    						?>
	    					</td>
	    					<td><?php echo $emp->vl_credit; ?></td>
	    					
	    				</tr>
	    				<tr>
	    					<td><center>EL</center></td>
	    					<td><?php echo $emp->actual_el_credit;; ?></td>
	    					<td>
	    						<?php 
	    						 $used = $emp->actual_el_credit;
	    						 $compute_el =$emp->actual_el_credit - $emp->elcl_credit;
	    						 echo $compute_el;
	    						?>
	    					</td>
	    					<td><?php echo $emp->elcl_credit; ?></td>
	    				</tr>
	    				<tr>
	    					<td><center>BL</center></td>
	    					<td><?php echo $emp->actual_bl_credit; ?></td>
	    					<td>
	    						<?php 
	    						 $used = $emp->actual_bl_credit;
	    						 $compute_bl =$emp->actual_bl_credit - $emp->fl_credit;
	    						 echo $compute_bl;
	    						?>
	    					</td>
	    					<td><?php echo $emp->fl_credit; ?></td>
	    				</tr>
	    			<?php endforeach; ?>	
	    		<?php endif; ?>	
	    	</table>
	    </div>
	  </form>  
  </div>
</div>
