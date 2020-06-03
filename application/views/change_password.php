<style type="text/css">
	.container {
		margin-top: 100px;
		width: 50%;
	}
	.a {
		float: right;
		margin-right: 5px;
	}
	.panel-heading{
		font-size: 20px;
		font-family: century gothic;
	}
	.panel-body {
		margin-left : 80px;
	}

</style>
<div class="container">
	<div class="col-lg-12">
		<div class="panel panel-primary">
			<div class="panel-heading">
				Change Password
			</div>
			<?php if($this->session->flashdata('update_msg')) : ?>
				<p class="alert alert-dismissable alert-success"><?php echo $this->session->flashdata('update_msg'); ?></p>
			<?php endif; ?>
			<?php if($this->session->flashdata('error_msg')) : ?>
				<p class="alert alert-dismissable alert-danger"><?php echo $this->session->flashdata('error_msg'); ?></p>
			<?php endif; ?>
			<div class="panel-body">
				<form action="<?php echo base_url(); ?>index.php/user/change_password" method="POST">
					
					<div style="color:red"><?php echo validation_errors(); ?> </div>
					<div class="col-md-9">
						<div class="form-group">
							<label>OLD PASSWORD</label>
							<input type="password" name="old_password" class="form-control">
						</div>	
					</div>	
					<div class="col-md-9">
						<div class="form-group">
							<label>Password</label>
							<div class="input-group" id="show_hide_password">
								<input class="form-control" type="password" name="new_password">
								<div class="input-group-addon">
									<a href=""><i class="fa fa-eye-slash" aria-hidden="true"></i></a>
								</div>
							</div>
						</div>
					</div>	
					<div class="col-md-9">
						<div class="form-group">
							<center><input type="submit" class="btn btn-primary" value="Submit"></center>
						</div>	
					</div>	
				</form>
			</div>
		</div>
	</div>
	
</div>
<script src="<?php echo base_url(); ?>/assets/js/jquery.min.js"></script>
<script>
	$(document).ready(function() {
		$("#show_hide_password a").on('click', function(event) {
			event.preventDefault();
			if($('#show_hide_password input').attr("type") == "text")
			{
				$('#show_hide_password input').attr('type', 'password');
				$('#show_hide_password i').addClass( "fa-eye-slash" );
				$('#show_hide_password i').removeClass( "fa-eye" );
			}
			else if($('#show_hide_password input').attr("type") == "password")
			{
				$('#show_hide_password input').attr('type', 'text');
				$('#show_hide_password i').removeClass( "fa-eye-slash" );
				$('#show_hide_password i').addClass( "fa-eye" );
			}
		});
	});
</script>
