<style type="text/css">
	.margin {
		margin-top: 70px;
		color: black;
	}
	.panel-heading{
		font-size: 24px;
		font-family: century gothic;
	}
	td {
		color: black;
	}
	.row
	{
		width: 100%;
		overflow: hidden;
	}
	.a {
		float: right;
		margin-right: 5px;
	}
</style>
<div class="margin">
	<?php if($this->session->flashdata('add_msg')) : ?>  
	    <p class="alert alert-dismissable alert-success"><?php echo $this->session->flashdata('add_msg'); ?></p> 
	<?php endif; ?>  
	<?php if($this->session->flashdata('update_ot_msg')) : ?>  
	    <p class="alert alert-dismissable alert-success"><?php echo $this->session->flashdata('update_ot_msg'); ?></p>  
	<?php endif; ?>     
	<?php if($this->session->flashdata('cancel_msg')) : ?>
   		<p class="alert alert-dismissable alert-danger"><?php echo $this->session->flashdata('cancel_msg'); ?></p>
	<?php endif; ?>
	<!-- TABLE OF BRANCHES -->
	<div class="row">
		<div class="col-lg-12">
	  	<div class="panel panel-primary">
	    	<div class="panel-heading">
	      	OT List
			<a href="<?php echo base_url(); ?>index.php/reports/add_ot" class="a btn btn-default">ADD</a>
	      </div>	
	        <form method="post" id="ot">
				    <div class="panel-body">
				      <div class="table-responsive">
				          <table class="table table-bordered table-hover table-striped cl">
				          	<div class="row">  	
						          <div class="col-md-2">
							          <div class="form-group">
							              <label for="form_name">Start Date</label>
							              <input id="form_name" type="date" name="start_date" class="form-control" value="<?php echo $cut_off->start_date; ?>">
							          </div>
							        </div>
							        <div class="col-md-2">
							          <div class="form-group">
							              <label for="form_name">End Date</label>
							              <input id="form_name" type="date" name="end_date" class="form-control" value="<?php echo $cut_off->end_date; ?>">
							          </div>
							        </div>	

							        <br>
							        <button type="submit" class="btn btn-default">Load</button>
									<input class="btn btn-success" id="rfa" type="submit" value="RFA">  
									<input class="btn btn-info" id="fa" type="submit" value="FA">   
									<input class="btn btn-default" id="rfv" type="submit" value="RFV"> 
									<input class="btn btn-danger" id="fv" type="submit" value="FV"> 
									<input class="btn btn-warning" id="nb" type="submit" value="FN">
									<input class="btn btn-primary" id="afp" type="submit" value="AFP"> 
								</div> 
				            <thead>
				                <tr>
				                	<th><center><input type="checkbox" id="checkAll" name=""></center></th>
			                    <th>Name</th>
			                    <th>Type</th>
			                    <th>Date</th>
			                    <th>Time In</th>
			                    <th>Time Out</th>
			                    <th>OT Hours</th>
			                    <th>Nature Of Work</th>
			                    <th>Status</th>
			                    <th><center>Action</center></th>
				                </tr>
				            </thead>
				            <?php if(isset($ots)) : ?>
				            		<?php $weekdate = ' '; $total_out_daily = ' '; ?>
				                <?php foreach($ots as $ot) : ?>
					                <tr>
										<td>	
											<center><input type="checkbox" name="employee[]" value="<?php echo $ot->id . '|' . $ot->employee_number . '|' . $ot->name; ?>"> </center>
										</td>
										<td><?php echo $ot->name; ?></td>
										<td><?php echo $ot->ot_type_name; ?></td>
										<td><?php echo $ot->date_ot; ?></td>
										<td><?php echo $ot->time_in; ?></td>
										<td><?php echo $ot->time_out; ?></td>
										<td>
											<?php
												$ot_hrs = $ot->total_ot;
												$hours = floor($ot_hrs / 60);
												$minutes = $ot_hrs % 60;
												$ot_hrs1 = $hours. '.' .$minutes;
												echo $ot_hrs1;
											?>
										</td>
										<td>
											<?php echo $ot->nature_of_work; ?>
										</td>
										<td>
											<?php echo $ot->tbl_ot_status; ?>
										</td>
										<td>
											<center>
											<a class="btn btn-danger btn-xs delete-btn" onclick="return confirm('Do you want to delete?');" href="<?php echo base_url() ?>index.php/reports/delete_ot/<?php echo $ot->id; ?>">Delete</a>
												<!-- <a class="btn btn-xs btn-primary" href="<?php //echo base_url() ?>index.php/reports/edit_ot/<?php //echo $ot->id; ?>">Edit</a> -->
												<a class="btn btn-danger btn-xs delete-btn" onclick="return confirm('Do you want to Cancel ot?');" href="<?php echo base_url() ?>index.php/users/cancelled_ot/<?php echo $ot->id; ?>">Cancelled</a>
											</center>
										</td>
					                </tr>
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
<script type="text/javascript">
	$(document).ready(function() {
		
		$('#rfa').click(function() {
			var a = confirm("Are you sure you want to Approved Data?");
			if (a == true) {
				$('#ot').attr('action', 'rfa_ot');
				$('#ot').submit();
			} else {
				return false;
			} 
		});

		$('#fa').click(function() {
			var a = confirm("Are you sure you want to Approved Data?");
			if (a == true) {
				$('#ot').attr('action', 'fa_ot');
				$('#ot').submit();
			} else {
				return false;
			} 
		});

		$('#rfv').click(function() {
			var a = confirm("Are you sure you want to Verified Data?");
			if (a == true) {
				$('#ot').attr('action', 'rfv_ot');
				$('#ot').submit();
			} else {
				return false;
			} 
		});

		$('#fv').click(function() {
			var a = confirm("Are you sure you want to Verified Data?");
			if (a == true) {
				$('#ot').attr('action', 'fv_ot');
				$('#ot').submit();
			} else {
				return false;
			} 
		});

		$('#nb').click(function() {
			var a = confirm("Are you sure you want to Processed Data?");
			if (a == true) {
				$('#ot').attr('action', 'nb_ot');
				$('#ot').submit();
			} else {
				return false;
			} 
		});

		$('#afp').click(function() {
			var a = confirm("Are you sure you want to Processed Data?");
			if (a == true) {
				$('#ot').attr('action', 'afp_ot');
				$('#ot').submit();
			} else {
				return false;
			} 
		});

		$("#checkAll").click(function(){
   	 $('input:checkbox').not(this).prop('checked', this.checked);
		});

	});	
</script>