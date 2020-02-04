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
	    				</tr>
	    				<tr>
	    					<td><center>SL</center></td>
	    					<td><?php echo '15' ?></td>
	    					<td>
	    						<?php 
	    						 $used = '15';
	    						 $compute_sl =$used - $emp->sl_credit;
	    						 echo $compute_sl;
	    						?>
	    					</td>
	    					<td><?php echo $emp->sl_credit; ?></td>
	    					
	    				</tr>
	    				<tr>
	    					<td><center>VL</center></td>
	    					<td><?php echo '10' ?></td>
		    				<td>
	    						<?php 
	    						 $used = '10';
	    						 $compute_vl =$used - $emp->vl_credit;
	    						 echo $compute_vl;
	    						?>
	    					</td>
	    					<td><?php echo $emp->vl_credit; ?></td>
	    					
	    				</tr>
	    				<tr>
	    					<td><center>EL/CL</center></td>
	    					<td><?php echo $emp->elcl_credit;; ?></td>
	    						<td><?php echo '0' ?></td>
	    					<td><?php echo '0' ?></td>
	    				</tr>
	    				<tr>
	    					<td><center>BL</center></td>
	    					<td><?php echo $emp->fl_credit; ?></td>
	    					<td><?php echo '0' ?></td>
	    					<td><?php echo '0' ?></td>
	    				</tr>
	    			<?php endforeach; ?>	
	    		<?php endif; ?>	
	    	</table>
	    </div>
	  </form>  
  </div>
</div>
