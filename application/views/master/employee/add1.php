<style type="text/css">
	.container {
		margin-top: 65px;
		margin-left: 10px;
		width: 100%;
	}

</style>
<div class="container">
 	<?php if($this->session->flashdata('add_emp')) : ?>
	     <p class="alert alert-dismissable alert-success"><?php echo $this->session->flashdata('add_emp'); ?></p>
	<?php endif; ?>
	<div class="panel panel-primary">
		<div class="panel-heading">
			<h3 class="panel-title">Basic Employee Information</h3>
		</div>
		<div class="panel-body">
			<form id="contact-form" method="post" action="<?php echo base_url(); ?>index.php/master/add_employee" role="form">
				<div style="color:red"><?php echo validation_errors(); ?> </div>
				  	<!-- 1ST ROW !-->
				  	<div class="row">
						<div class="col-md-3"> 
							<div class="form-group">
								<label for="form_name">Bio No *</label>
								<input id="form_name" type="text" name="employee_number" class="form-control">
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label for="form_name">Employee Number *</label>
								<input id="form_name" type="text" name="emp_no" class="form-control">
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label for="form_name">Lastname *</label>
								<input id="form_name" type="text" name="last_name" class="form-control">
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label for="form_name">Firstname *</label>
								<input id="form_name" type="text" name="first_name" class="form-control">
							</div>
						</div>
				    </div>

				      <!-- 2ND ROW !-->
					<div class="row">
						<div class="col-md-3">
							<div class="form-group">
								<label for="form_name">Middlename </label>
								<input id="form_name" type="text" name="middle_name" class="form-control">
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label for="form_name">Gender *</label>
								<select class="form-control" name="gender">
									<option value=" ">SELECT</option>
									<option value="Male">Male</option>
									<option value="Female">Female</option>
								</select>	
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
							<label for="form_name">Company *</label>
								<select class="form-control" name="company">
									<option value=" ">SELECT</option>
									<?php if($company) : ?>
										<?php foreach($company as $com) : ?>
											<option value="<?php echo $com->id; ?>"><?php echo $com->name; ?></option>
										<?php endforeach; ?>	
									<?php endif;?>	
								</select>	
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label for="form_name">Branch *</label>
								<select class="form-control" name="branches">
									<option value=" ">SELECT</option>
									<?php if($branches) : ?>
										<?php foreach($branches as $branch) : ?>
											<option value="<?php echo $branch->id; ?>"><?php echo $branch->name; ?></option> 
										<?php endforeach; ?>	
									<?php endif; ?>	
								</select>	
							</div>
						</div>  
					</div>

				      <!-- 4th ROW !-->
					<div class="row">
						<div class="col-md-3">
							<div class="form-group">
								<label for="form_name">Type *</label>
								<select class="form-control" name="type">
									<option value=" ">SELECT</option>
									<option value="MP">MONTHLY PAID</option>
									<option value="DP">DAILY PAID</option>
								</select>
							</div>
						</div>

						<div class="col-md-3">
							<div class="form-group">
								<label for="form_name">Date Hired</label>
								<input id="form_name" type="date" name="date_hired" class="form-control">
							</div>
						</div>
				    </div>

					<div class="panel panel-primary">
						<div class="panel-heading">
							<h5 class="panel-title">Leave Credits</h3>
						</div>
						<div class="panel-body">
							<div class="row">
								<div class="col-md-3">
									<div class="form-group">
										<label for="form_name">Sick Leave</label>
										<input type="text" class="form-control" name="sl">
									</div>	
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="form_name">Vacation Leave</label>
										<input type="text" class="form-control" name="vl">
									</div>	
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="form_name">Emergency Leave</label>
										<input type="text" class="form-control" name="el">
									</div>	
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="form_name">Bereavement Leave</label>
										<input type="text" class="form-control" name="bl">
									</div>	
								</div>
							</div>
						</div>
					</div>						
					

				    <div class="row">
			          <div class="col-md-12">
			              <center><input type="submit" class="btn btn-primary a" value="Submit"></center>
			          </div>
				    </div>
			</form>
		</div>
	</div>
</div>	