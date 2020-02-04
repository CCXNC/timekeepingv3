<style type="text/css">
	.container{
		margin-top: 100px;
		margin-left: 100px;
	}
	p{
		font-size: 24px;
		font-family: century gothic;
	}
	.add{
		margin-top: -45px;
		margin-left: 1050px;
	}
	.mar {
		margin-left: 360px;
	}
</style>
<div class="container">
<!-- TABLE OF BRANCHES -->
<div class="row">
	<div class="col-lg-12">

  	<div class="panel panel-default">
    	<div class="panel-heading">
      	<p>IN/OUT List</p>
      
      </div>	
      <form id="in_out" method="post">
			    <div class="panel-body">
			      <div class="table-responsive">
			          <table class="table table-bordered table-hover table-striped">
		          		<div class="row">  	
				          	<div class="col-md-2">
						          <div class="form-group">
						              <label for="form_name">START</label>
						              <input id="form_name" type="text" name="start_date" class="form-control" value=" <?php echo $cut_off->start_date; ?>">
						          </div>
						        </div>
						        <div class="col-md-2">
						          <div class="form-group">
						              <label for="form_name">END</label>
						              <input id="form_name" type="text" name="end_date" class="form-control" value="<?php echo $cut_off->end_date; ?>">
						          </div>
						        </div>	
					        	<div class="col-md-2">
				              <div class="form-group">
				                  <label for="form_name">&nbsp;</label>
				                  <select class="form-control" name="status">
				                  	
				                  	<option value="NO IN">NO IN</option>
				                  	<option value="NO OUT">NO OUT</option>
				                  </select>	
				              </div>
					          </div>
						        <br>
						        <button type="submit" class="btn btn-primary">load</button>
						        <a href="<?php echo base_url() ?>index.php/reports/adj_employee_time" class="btn btn-primary">Adjustment</a>
						        
						        &nbsp;&nbsp;&nbsp; 
						        <!-- <input id="inBtn" class="mar btn btn-primary" type="button" name="inBtn" value="IN" />
						        <input id="outBtn" class="btn btn-primary" type="button" name="inBtn" value="OUT" /> -->
						        
								  </div> 
			            <thead>
			                <tr>
		                    <th>Employee No</th>
		                    <th>Name</th>
		                    <th>Date</th>
		                    <th>Time IN</th>
		                    <th>Time OUT</th>
		                    <th>Status</th>
		                    <!-- <th><center>Action</center></th> -->
			                </tr>
			            </thead>
			            <?php if(isset($in)) : ?>
		                <?php foreach($in as $in) : ?>
		                	<?php 
			                	$time = $in->intime;
	                   		$explodetime = explode(" ", $time);
	                   		$in_time = $explodetime[1];
	                   		$time = $in->outtime;
	                   		$explodetime = explode(" ", $time);
	                   		$out_time = $explodetime[1];
		                		$week_date = date('w', strtotime($in->dates)); 
		                	?>	
		                	<?php if($week_date != '6' && $week_date != '0' && $in_time != '00:00:00' || $out_time != '00:00:00'): ?>
				                <tr>
			                    <td><?php echo $in->employee_number; ?></td>
			                    <td title="<?php echo $in->branch_id; ?>"><?php echo $in->name; ?></td> 
			                    <td><?php echo $in->dates; ?></td>
			                    <td>
			                    	<?php
			                     		$time = $in->intime;
			                     		$explodetime = explode(" ", $time);
			                     		$in_time = $explodetime[1];
			                     		echo $in_time;
		                     		?>
	                     		</td>
	                     		<td>
	                     			<?php
			                     		$time = $in->outtime;
			                     		$explodetime = explode(" ", $time);
			                     		$out_time = $explodetime[1];
			                     		echo $out_time;
		                     		?>
	                     		</td>
			                    <td><?php echo $in->status; ?></td>
			                    <td>
			                      <!-- <center>	<input type="checkbox" name="ins[]" value="<?php echo $in->employee_number . '|' . $in->id . '|' . $in->dates . '|' . $in_time; ?>"></center> -->
			                    </td>
				                </tr>
				              <?php endif; ?>  
		                <?php endforeach; ?>
			            <?php endif; ?>

			            <?php if(isset($outs)) : ?>
		                <?php foreach($outs as $out) : ?>
		                	<?php 
		                		$time = $out->intime;
                     		$explodetime = explode(" ", $time);
                     		$in_time = $explodetime[1];
                     		$time = $out->outtime;
                     		$explodetime = explode(" ", $time);
                     		$out_time = $explodetime[1];
		                		$week_date = date('w', strtotime($out->dates)); 
		                		//echo $week_date;
		                	?>	
		                	<?php if($week_date != '6' && $week_date != '0' && $in_time != '00:00:00' || $out_time != '00:00:00'): ?>
				                <tr>
			                    <td><?php echo $out->employee_number; ?></td>
			                    <td title="<?php echo $out->branch_id; ?>"><?php echo $out->name; ?></td>
			                    <td><?php echo $out->dates; ?></td>
			                    <td>
			                    	<?php
			                     		$time = $out->intime;
			                     		$explodetime = explode(" ", $time);
			                     		$in_time = $explodetime[1];
			                     		echo $in_time;
		                     		?>
	                     		</td>
	                     		<td>
	                     			<?php
			                     		$time = $out->outtime;
			                     		$explodetime = explode(" ", $time);
			                     		$out_time = $explodetime[1];
			                     		echo $out_time;
		                     		?>
	                     		</td>
			                    <td><?php echo $out->status; ?></td>
			                    <td>
			                      <!-- <center>	<input type="checkbox" name="outs[]" value="<?php echo $out->employee_number . '|' . $out->id . '|' . $out->dates . '|' . $out_time . '|' . $week_date; ?>"></center> -->
			                    </td>
				                </tr>
				              <?php endif; ?>  
		                <?php endforeach; ?>
			            <?php endif; ?>
			    			</table>
	      		</div>
	  			</div>      
        </form>   
     </div>            
  </div>
</div>
</div>
<script type="text/javascript" src="<?php echo base_url(); ?>/assets/js/jquery.min.js"></script>
<script>
	$(document).ready(function() {
		$('#inBtn').click(function() {
			var a = confirm("Process IN?");
			if (a == true) {
				$('#in_out').attr('action', 'in_process');
				$('#in_out').submit();
			} else {
				return false;
			} 
		});

		$('#outBtn').click(function() {
			var a = confirm("Process OUT?");
			if (a == true) {
				$('#in_out').attr('action', 'out_process');
				$('#in_out').submit();
			} else {
				return false;
			} 
		});
	});
	
</script>


