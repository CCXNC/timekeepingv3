<style type="text/css">
	.container {
		margin-top: 80px;
        width: 100%;
	}
	.a {
		float: right;
		margin-right: 5px;
	}
	.panel-heading{
		font-size: 24px;
		font-family: century gothic;
	}

</style>
<div class="container">
	<div class="col-lg-12">
		<div class="panel panel-primary">
			<div class="panel-heading">
				Branch List
				<a href="<?php echo base_url(); ?>index.php/master/add_branch" class="a btn btn-default">ADD</a>
			</div>
			<div class="panel-body">
				<?php if($this->session->flashdata('add_msg')) : ?>
					<p class="alert alert-dismissable alert-success"><?php echo $this->session->flashdata('add_msg'); ?></p>
                <?php endif; ?>
                <?php if($this->session->flashdata('update_msg')) : ?>
					<p class="alert alert-dismissable alert-success"><?php echo $this->session->flashdata('update_msg'); ?></p>
				<?php endif; ?>
				<?php if($this->session->flashdata('delete_msg')) : ?>
					<p class="alert alert-dismissable alert-danger"><?php echo $this->session->flashdata('delete_msg'); ?></p>
				<?php endif; ?>
				<div class="table-responsive"> 
			        <table class="table table-bordered table-hover table-striped cl"> 
						<thead>
							<tr>
                                <th>Code</th>
                                <th>Name</th>
                                <th>Officer In Charge</th>
                                <th>Address</th>
                                <th>Action</th>
							</tr>
                        </thead>
                        <?php if($branches) : ?>
                            <?php foreach($branches as $branch) : ?>
                                <tr>
                                    <td><?php echo $branch->code; ?></td>
                                    <td><?php echo $branch->name; ?></td>
                                    <td><?php echo $branch->oic; ?></td>
                                    <td><?php echo $branch->address; ?></td>
                                    <td>
                                        <a href="<?php echo base_url(); ?>master/edit_branch/<?php echo $branch->id; ?>" class="btn-sm btn-primary">Edit</a>
                                        <a class="btn-sm btn-danger" onclick="return confirm('Do you want to delete this branch?');" href="<?php echo base_url(); ?>index.php/master/delete_branch/<?php echo $branch->id; ?>">Delete</a>

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
