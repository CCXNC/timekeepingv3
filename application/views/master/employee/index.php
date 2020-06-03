<style type="text/css">
	.container {
		margin-top: 60px;
		margin-left: 100px;
	}
	p {
		font-size: 24px;
		font-family: century gothic;
	}
	.add {
		margin-top: -45px;
		margin-left: 1050px;
	}
</style>
<div class="container">
<?php if($this->session->flashdata('update_emp')) : ?>
     <p class="alert alert-dismissable alert-success"><?php echo $this->session->flashdata('update_emp'); ?></p>
<?php endif; ?>
<!-- TABLE OF BRANCHES -->
<div class="row">
	<div class="col-lg-12">

  	<div class="panel panel-default">
    	<div class="panel-heading">
      	<p>Employee List</p>
      	<div class="add">
        	 <a class="btn btn-primary" href="<?php echo base_url(); ?>index.php/master/add_employee">Add</a>
      	</div>
      </div>	
        
			    <div class="panel-body">
			      <div class="table-responsive">
			          <table class="table table-bordered table-hover table-striped">
			            <thead>
			                <tr> 
			                    <th>Employee No</th>
			                    <th>Name</th>
			                    <th>Company</th>
			                    <th>Branch</th>
			                    <th>Action</th>
			                </tr>
			            </thead>

			            <?php if(isset($employee)) : ?>
			                <?php foreach($employee as $emp) : ?>
			                <tr>
		                    <td><?php echo $emp->employee_number; ?></td> 
		                    <td><?php echo $emp->name; ?></td>
		                    <td><?php echo $emp->company_name; ?></td>
		                    <td><?php echo $emp->branch_name; ?></td>
		                    <td>
		                      <a class="btn-sm btn-primary" href="<?php echo base_url(); ?>index.php/master/edit_employee/<?php echo $emp->id; ?>/<?php echo $emp->employee_number; ?>">Edit</a>
		                      <!--<a class="btn btn-danger btn-xs delete-btn" onclick="return confirm('Do you want to delete?');" href="<?php echo base_url(); ?>index.php/master/delete_employee/<?php echo $emp->id; ?>">Delete</a>-->
		                      <a class="btn-sm btn-danger" onclick="return confirm('Do you want to inactive this employee?');" href="<?php echo base_url(); ?>index.php/master/inactive_employee/<?php echo $emp->id; ?>">Inactive</a>
		                    </td>
			                </tr>
			                <?php endforeach; ?> 
			            <?php endif; ?>
			    			</table>
					        <div class="margin3">
					            <?php echo $this->pagination->create_links(); ?>
					        </div>     
	      		</div>
	  			</div>      
           

     </div>            
  </div>
</div>
</div>
