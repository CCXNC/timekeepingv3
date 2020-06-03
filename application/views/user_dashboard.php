<style type="text/css">
	.container{
		margin-top: 70px;
	}

	.row {
		margin-left: 2px;
		margin-top: 10px;
	}
	.name {
		font-size: 20px;
	}
	.desslvl
	{
		float: right;
	}

</style>
<div class="container">
	<form method="post">
	  <div class="col-sm-12">
		  <div class="panel panel-primary">
		    <div class="panel-heading">
		    	<h3 class="panel-title">
		    		<div class="name">
		    			<?php if($employee) : ?>
				        <?php 
				        	echo $employee->name ; 
				        ?>
				      <?php endif; ?> 
				      <div class="desslvl">
			    			SL:&nbsp;<?php echo $leave_credit->sl_credit; ?> &nbsp; VL:&nbsp;<?php echo $leave_credit->vl_credit; ?> &nbsp; EL:&nbsp;<?php echo $leave_credit->elcl_credit; ?> &nbsp; BL:&nbsp;<?php echo $leave_credit->fl_credit; ?>
			    		</div>
		    		</div>
		    		
		      </h3>  
		    </div>
		     <div class="row">
		    	<div class="col-xs-2">
		    		<input type="date" class="form-control" name="start_date" value="<?php echo $start_date; ?>">
		    	</div>
		    	<div class="col-xs-2">
		    		<input type="date" class="form-control" name="end_date" value="<?php echo $end_date; ?>">
		    	</div>
		    	<div class="col-md-2">
		    		<button type="submit" class="btn btn-primary">LOAD</button> 
		    	</div>
		    </div>
		    <div class="panel-body">
		   		<div class="col-sm-12">
					<div class="panel panel-primary">
					    <div class="panel-heading">
					        <h3 class="panel-title">SL/VL</h3>
					    </div>
					     <div class="panel-body">
					     	<?php if($this->session->flashdata('add_msg_slvl')) : ?>
							     <p class="alert alert-dismissable alert-success"><?php echo $this->session->flashdata('add_msg_slvl'); ?></p>
								<?php endif; ?>
					     	<?php if($this->session->flashdata('update_msg_slvl')) : ?>
							     <p class="alert alert-dismissable alert-success"><?php echo $this->session->flashdata('update_msg_slvl'); ?></p>
								<?php endif; ?>
								<?php if($this->session->flashdata('delete_msg_slvl')) : ?>
							     <p class="alert alert-dismissable alert-danger"><?php echo $this->session->flashdata('delete_msg_slvl'); ?></p>
								<?php endif; ?>
								<?php if($this->session->flashdata('policy_file_slvl')) : ?>
							     <p class="alert alert-dismissable alert-danger"><?php echo $this->session->flashdata('policy_file_slvl'); ?></p>
								<?php endif; ?>
					    	<table class="table table-bordered table-hover table-striped cl">
				    	 		<thead>
				    	 			<tr>
				    	 				<th>Days</th> 
				    	 				<th>Type</th> 
				    	 				<th>Date</th> 
				    	 				<th>Reason</th> 
				    	 				<th>Status</th> 
				    	 				<th>Action</th> 
				    	 			</tr>
				    	 		</thead>
				    	 		<?php if($slvl) : ?>
				    	 			<?php foreach($slvl as $slvl) : ?>
					    	 			<tr>
					    	 				<td><?php echo date('D', strtotime($slvl->effective_date_start)); ?></td>
					    	 				<td>
					    	 					<?php
					    	 				 		if($slvl->sl_am_pm == 'HFAM')
													{ 
														echo $slvl->type_name . '    |    ' . ' (Halfday AM) '; 
													}
													elseif($slvl->sl_am_pm == 'HFPM')
													{
														echo $slvl->type_name . '    |    ' . ' (Halfday PM) '; 
													}
													elseif($slvl->sl_am_pm == 'ADJ')
													{
														echo $slvl->type_name . '    |    ' . ' (ADJ) '; 
													}
													else
													{
														echo $slvl->type_name . '    |    ' . ' (WHOLEDAY) '; 
													}	
												?>	
					    	 				 	
					    	 				</td>
					    	 				<td><?php echo $slvl->effective_date_start; ?></td>
					    	 				<td><?php echo $slvl->reason; ?></td>
					    	 				<td><?php echo $slvl->status; ?></td>
					    	 				<td>
					    	 					<?php if($slvl->status != "PROCESSED") : ?>
					    	 						<center>
														<a class="btn btn-xs btn-danger" onclick="return confirm('Do you want to Delete?');" href="<?php echo base_url(); ?>index.php/users/delete_slvl/<?php echo $slvl->id; ?>/<?php echo $slvl->employee_number;?>/<?php echo $slvl->type; ?>">Delete</a>
													</center>
												<?php endif; ?>
												<?php if($slvl->status == "PROCESSED") : ?>
													<center><a class="btn btn-xs btn-success" href="#">SUCCESS</a></center>
												<?php endif; ?>
												<?php if($slvl->status == "CANCELLED") : ?>
													<center><a class="btn btn-xs btn-danger" href="#">CANCELLED</a></center>
												<?php endif; ?> 
					    	 				</td>
					    	 			</tr>
				    	 			<?php endforeach; ?>
				    	 		<?php endif; ?>	
				    	 	</table>
					 	</div>
					</div>								
				</div>

				  <div class="col-sm-12">
					  <div class="panel panel-primary">
					    <div class="panel-heading">
					        <h3 class="panel-title">OB</h3>
					    </div>
					    <div class="panel-body">
					    	<?php if($this->session->flashdata('add_msg_ob')) : ?>
							     <p class="alert alert-dismissable alert-success"><?php echo $this->session->flashdata('add_msg_ob'); ?></p>
								<?php endif; ?>
					     	<?php if($this->session->flashdata('update_msg_ob')) : ?>
							     <p class="alert alert-dismissable alert-success"><?php echo $this->session->flashdata('update_msg_ob'); ?></p>
								<?php endif; ?>
								<?php if($this->session->flashdata('delete_msg_ob')) : ?>
							     <p class="alert alert-dismissable alert-danger"><?php echo $this->session->flashdata('delete_msg_ob'); ?></p>
								<?php endif; ?>
								<?php if($this->session->flashdata('policy_file_ob')) : ?>
							     <p class="alert alert-dismissable alert-danger"><?php echo $this->session->flashdata('policy_file_ob'); ?></p>
								<?php endif; ?>
					    	<table class="table table-bordered table-hover table-striped cl">
				    	 		<thead>
				    	 			<tr>
				    	 				<th>Days</th>
				    	 				<th>Type</th>
				    	 				<th>Date</th>
				    	 				<th>From | To</th>
										<th>Departure (Time in)</th>
										<th>Return (Time out)</th>
										<th>Remarks</th>
										<th>Action</th>
				    	 			</tr>
				    	 		</thead> 
				    	 		<?php if($ob) : ?>
				    	 			<?php foreach($ob as $ob) : ?>
					    	 			<tr>
					    	 				<td><?php echo date('D', strtotime($ob->date_ob)); ?></td>
					    	 				<td>
					    	 					<?php 
													if($ob->type_ob == 'UD_out')
													{
														echo 'UNDERTIME OUT'; 
													}
													elseif($ob->type_ob == 'UD_in')
													{
														echo 'HALFDAY IN'; 
													}
													elseif($ob->type_ob == 'WD')
													{
														echo 'WHOLEDAY'; 
													}
												?>
					    	 				</td>
					    	 				<td><?php echo $ob->date_ob; ?></td>
					    	 				<td><?php echo $ob->site_from . ' | ' . $ob->site_to ;?></td>
					    	 				<td><?php echo $ob->time_of_departure; ?></td>
					    	 				<td><?php echo $ob->time_of_return; ?></td>
					    	 				<td><?php echo $ob->remarks; ?></td>
					    	 				<td>
												 
					    	 					<?php if($ob->remarks != "PROCESSED") : ?>
						    	 					<center>
														<?php if($ob->remarks == "Recommending for Approval" || $ob->remarks == "FOR APPROVAL" ) : ?>
															<a class="btn btn-xs btn-primary" href="<?php echo base_url(); ?>index.php/users/edit_ob/<?php echo $ob->id; ?>">Edit</a>
														<?php endif; ?>
														<a class="btn btn-danger btn-xs delete-btn" onclick="return confirm('Do you want to delete?');" href="<?php echo base_url() ?>index.php/users/delete_ob/<?php echo $ob->id; ?>/<?php echo $ob->employee_number; ?>/<?php echo $ob->type; ?>">Delete</a>
													</center>
												<?php endif; ?>
												<?php if($ob->remarks == "PROCESSED") : ?>
													<center><a class="btn btn-xs btn-success" href="#">SUCCESS</a></center>
												<?php endif; ?>
					    	 				</td>
					    	 			</tr>
				    	 			<?php endforeach; ?>
				    	 		<?php endif; ?>	
				    	 	</table>
					    </div>
					  </div>
				  </div>

				  <div class="col-sm-12">
					  <div class="panel panel-primary">
					    <div class="panel-heading">
					        <h3 class="panel-title">OVERTIME</h3>
					    </div>
					    <div class="panel-body">
					    	<?php if($this->session->flashdata('add_msg_ot')) : ?>
							     <p class="alert alert-dismissable alert-success"><?php echo $this->session->flashdata('add_msg_ot'); ?></p>
								<?php endif; ?>
					    	<?php if($this->session->flashdata('update_msg_ot')) : ?>
							     <p class="alert alert-dismissable alert-success"><?php echo $this->session->flashdata('update_msg_ot'); ?></p>
								<?php endif; ?>
								<?php if($this->session->flashdata('delete_msg_ot')) : ?>
							     <p class="alert alert-dismissable alert-danger"><?php echo $this->session->flashdata('delete_msg_ot'); ?></p>
								<?php endif; ?>
								<?php if($this->session->flashdata('policy_file_ot')) : ?>
							     <p class="alert alert-dismissable alert-danger"><?php echo $this->session->flashdata('policy_file_ot'); ?></p>
								<?php endif; ?>
					    	<table class="table table-bordered table-hover table-striped cl">
				    	 		<thead>
				    	 			<tr>
				    	 				<th>Days</th>
				    	 				<th>Type</th>
										<th>Date</th>
										<th>Time In</th>
										<th>Time Out</th>
										<th>OT Hours</th>
										<th>Nature Of Work</th>
										<th>Status</th>
										<th>Action</th>
				    	 			</tr>
				    	 		</thead>
				    	 		<?php $ot_total_hrs = 0; ?>
				    	 		<?php if($ot) : ?>
				    	 			<?php foreach($ot as $ot) : ?>
					    	 			<tr>
					    	 				<td><?php echo date('D', strtotime($ot->date_ot)); ?></td>
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
													$ot_total_hrs += $ot_hrs1; 
													echo $ot_hrs1;
					    	 				  	?>
					    	 				</td>

					    	 				<td><?php echo $ot->nature_of_work; ?></td>
					    	 				<td><?php echo $ot->status; ?></td>
					    	 				<td>
					    	 					<?php if($ot->status != "PROCESSED") : ?>
						    	 					<center>
						    	 						<?php if($ot->status == "Recommending for Approval" || $ot->status == "FOR APPROVAL") : ?>
						    	 							<a class="btn btn-xs btn-primary" href="<?php echo base_url(); ?>index.php/users/edit_ot/<?php echo $ot->id; ?>">Edit</a>
						    	 						<?php endif; ?> 
														<a class="btn btn-danger btn-xs delete-btn" onclick="return confirm('Do you want to delete?');" href="<?php echo base_url() ?>index.php/users/delete_ot/<?php echo $ot->id; ?>">Delete</a>
													</center>
												<?php endif; ?>
												<?php if($ot->status == "PROCESSED") : ?>
													<center><a class="btn btn-xs btn-success" href="#">SUCCESS</a></center>
												<?php endif; ?>
					    	 				</td>
					    	 			</tr>
				    	 			<?php endforeach; ?>
				    	 		<?php endif; ?>	
				    	 	</table>
					    </div>
					  </div>
				  </div>

				  <div class="col-sm-12">
					  <div class="panel panel-primary">
					    <div class="panel-heading">
					        <h3 class="panel-title">UNDERTIME</h3>
					    </div>
					    <div class="panel-body">
					    	<?php if($this->session->flashdata('add_msg_ut')) : ?>
							     <p class="alert alert-dismissable alert-success"><?php echo $this->session->flashdata('add_msg_ut'); ?></p>
								<?php endif; ?>
					    	<?php if($this->session->flashdata('update_msg_ut')) : ?>
							     <p class="alert alert-dismissable alert-success"><?php echo $this->session->flashdata('update_msg_ut'); ?></p>
								<?php endif; ?>
								<?php if($this->session->flashdata('delete_msg_ut')) : ?>
							     <p class="alert alert-dismissable alert-danger"><?php echo $this->session->flashdata('delete_msg_ut'); ?></p>
								<?php endif; ?>
								<?php if($this->session->flashdata('policy_file_ut')) : ?>
							     <p class="alert alert-dismissable alert-danger"><?php echo $this->session->flashdata('policy_file_ut'); ?></p>
								<?php endif; ?>
					    	<table class="table table-bordered table-hover table-striped cl">
				    	 		<thead>
				    	 			<tr>
				    	 				<th>Days</th>
				    	 				<th>Date</th>
				    	 				<th>Time out</th>
				    	 				<th>UT Hours</th>
				    	 				<th>Reason</th>
										<th>Status</th>
										<th>Action</th>
				    	 			</tr>
				    	 		</thead> 
				    	 		<?php $undertime_total = 0; ?>
				    	 		<?php if($undertime) : ?>
				    	 			<?php foreach($undertime as $ut) : ?>
				    	 				<tr >
				    	 					<td><?php echo date('D', strtotime($ut->date_ut)); ?></td>
					    	 				<td><?php echo $ut->date_ut; ?></td>
					    	 				<td><?php echo $ut->time_out; ?></td>
					    	 				<td>
					    	 					<?php 
							    	 				$hr = floor($ut->ut_no / 60);
							    	 				$minutes = $ut->ut_no % 60;
													$mins = sprintf("%02d", $minutes); 
													echo $hr .".". $mins ;
						    	 				?>
					    	 				</td>
					    	 				<td><?php echo $ut->reason; ?></td>
					    	 				<td><?php echo $ut->status; ?></td>
					    	 				<td>
					    	 					<?php if($ut->status != "PROCESSED") : ?>
						    	 					<center>
						    	 						<?php if($ut->status == "Recommending for Approval" || $ut->status == "FOR APPROVAL" ) : ?>
						    	 							<a class="btn btn-xs btn-primary" href="<?php echo base_url(); ?>index.php/users/edit_undertime/<?php echo $ut->id; ?>">Edit</a>
						    	 							<?php endif; ?> 
						                				<a class="btn btn-danger btn-xs delete-btn" onclick="return confirm('Do you want to delete?');" href="<?php echo base_url() ?>index.php/users/delete_undertime/<?php echo $ut->id; ?>/<?php echo $ut->employee_number; ?>/<?php echo $ut->type; ?>">Delete</a>
						               			 	</center>
					              				<?php endif; ?>
					              	<?php if($ut->status == "PROCESSED") : ?>
						                <center><a class="btn btn-xs btn-success" href="#">SUCCESS</a></center>
					              	<?php endif; ?>
					    	 				</td>
				    	 				</tr>
				    	 			<?php endforeach; ?>
				    	 		<?php endif; ?>	
				    	 	</table>
					    </div>
					  </div>
				  </div>

		    </div>
		  </div>
	  	</div>
		</div>
	</form>  
</div>