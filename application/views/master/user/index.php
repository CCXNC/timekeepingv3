<style type="text/css">
	.container {
		margin-top: 100px;
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
				Users List
				<a href="<?php echo base_url(); ?>index.php/master/add_user" class="a btn btn-default">ADD</a>
			</div>
			<div class="panel-body">
				<?php if($this->session->flashdata('add_msg')) : ?>
					<p class="alert alert-dismissable alert-success"><?php echo $this->session->flashdata('add_msg'); ?></p>
				<?php endif; ?>
				<?php if($this->session->flashdata('delete_msg')) : ?>
					<p class="alert alert-dismissable alert-danger"><?php echo $this->session->flashdata('delete_msg'); ?></p>
				<?php endif; ?>
				<?php if($this->session->flashdata('reset_msg')) : ?>
					<p class="alert alert-dismissable alert-success"><?php echo $this->session->flashdata('reset_msg'); ?></p>
				<?php endif; ?>
				<div class="table-responsive"> 
			        <table class="table table-bordered table-hover table-striped cl"> 
						<thead>
							<tr>
								<th>Fullname</th>
								<th>Username</th>
								<th>Default Password</th>
								<th>Action</th>
							</tr>
						</thead>
						<?php if($users) : ?>
							<?php foreach($users as $user) : ?>
								<tr class="data">
									<td><?php echo $user->fullname; ?></td>
									<td><?php echo $user->username; ?></td>
									<td><?php echo $user->default_password; ?></td>
									<td>
										<a href="<?php echo base_url(); ?>index.php/master/reset_password/<?php echo $user->id; ?>" onclick="return confirm('Do you want to reset password?');" class="btn-sm btn-primary">Reset</a>
										<a href="<?php echo base_url(); ?>master/delete_user/<?php echo $user->id; ?>" onclick="return confirm('Do you want to delete?');" class="btn-sm btn-danger">Delete</a>
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
