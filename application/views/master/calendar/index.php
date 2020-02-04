<style type="text/css">
	.container{
		margin-top: 70px;
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
</style>
<div class="container">
	<?php if($this->session->flashdata('add_msg')) : ?>
	   <p class="alert alert-dismissable alert-success"><?php echo $this->session->flashdata('add_msg'); ?></p>
	<?php endif; ?>
	<?php if($this->session->flashdata('update_msg')) : ?>
	   <p class="alert alert-dismissable alert-success"><?php echo $this->session->flashdata('update_msg'); ?></p>
	<?php endif; ?> 
		<?php if($this->session->flashdata('delete_msg')) : ?>
	   <p class="alert alert-dismissable alert-danger"><?php echo $this->session->flashdata('delete_msg'); ?></p>
	<?php endif; ?> 
	<!-- TABLE OF BRANCHES -->
	<div class="row">
		<div class="col-lg-12">
	  	<div class="panel panel-primary">
	    	<div class="panel-heading">
	      	<p>Holiday & Events List</p>
	      	<div class="add">
	      		<a class="btn btn-default" href="<?php echo base_url(); ?>index.php/master/add_holiday">Add</a>
	      	</div>	
	      </div>	
	        <form method="post">
				    <div class="panel-body">
				    	<div class="row">
				    		<div class="col-md-2">
									<div class="form-group">
										 <label for="form_name">Year</label>
										<input type="text" class="form-control" name="year" value="<?php echo date('Y'); ?>">
									</div>					    			
				    		</div>
				    		<div class="col-md-2">
									<div class="form-group">
										<label for="form_name">Branches</label>
										<select class="form-control" name="branch_id">
			              	<option value="ALL">ALL</option>
			              	<?php if($branches) : ?>
			              		<?php foreach($branches as $branch) : ?>
			              			<option value="<?php echo $branch->id;?>"<?php echo $branch_id == $branch->id ? 'selected' : ' '; ?>><?php echo $branch->name; ?></option>
			              		<?php endforeach; ?>
			              	<?php endif; ?>	
			              </select>	
									</div>					    			
				    		</div>
				    		<div class="col-md-3">
									<div class="form-group">
										<label for="form_name">Type</label>
			              <select class="form-control" name="type">
			              	<option value=" ">SELECT</option>
			              	<option value="LH"<?php echo $type == 'LH' ? 'selected' : ' '; ?>>LEGAL HOLIDAY</option>
			              	<option value="SH"<?php echo $type == 'SH' ? 'selected' : ' '; ?>>SPECIAL HOLIDAY</option>
			              	<option value="SE"<?php echo $type == 'SE' ? 'selected' : ' '; ?>>EVENTS</option>
			              </select>	
									</div>					    			
				    		</div>
								<br>				    		
				    		<button type="submit" class="btn btn-default">Load</button>
				    	</div>
				      <div class="table-responsive">
				          <table class="table table-bordered table-hover table-striped">
				            <thead>
				                <tr>
				                	<th>Branch</th>
			                    <th>Decription</th>
			                    <th>Type</th>
			                    <th>Date</th>
			                    <th><center>Action</center></th>
				                </tr>
				            </thead>
				            <?php if(isset($holidays)) : ?>
				                <?php foreach($holidays as $holiday) : ?>
				                <tr>
				                	<td>
				                	<?php 
					                	if($holiday->code = 'ALL')
					                	{
					                		echo 'ALL';
					                	} 
					                	else
					                	{
					                		echo $holiday->code;
					                	}
				                	?>
				                		
				                	</td>
			                    <td><?php echo $holiday->description; ?></td>
			                    <td>
			                    	<?php 
			                    		if($holiday->type == 'LH')
			                    		{
			                    			echo 'LEGAL HOLIDAY';
			                    		}
			                    		elseif($holiday->type == 'SH')
			                    		{
			                    			echo 'SPECIAL HOLIDAY';
			                    		}
			                    		elseif($holiday->type == 'SE')
			                    		{
			                    			echo 'EVENTS';
			                    		}
			                    	?>
			                   	</td>
			                    <td><?php echo $holiday->dates; ?></td>
			                    <td>
			                      <center>
			                      	<a class="btn btn-xs btn-info" href="<?php echo base_url(); ?>index.php/master/edit_calendar/<?php echo $holiday->id; ?>">Edit</a>
			                      	<a class="btn btn-xs btn-danger" href="<?php echo base_url(); ?>index.php/master/delete_holidays/<?php echo $holiday->id; ?>">Delete</a>
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
