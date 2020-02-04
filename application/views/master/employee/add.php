<style type="text/css">
	.container {
		margin-top: 55px;
		width: 100%;
	}
	.center {
		margin-left: 200px;
	}
</style>
<div class="container">
 	<?php if($this->session->flashdata('add_emp')) : ?>
	     <p class="alert alert-dismissable alert-success"><?php echo $this->session->flashdata('add_emp'); ?></p>
	<?php endif; ?>
	<div style="color:red"><?php echo validation_errors(); ?> </div>
	<div class="panel panel-primary">
		<div class="panel-heading">
			<h3 class="panel-title">Basic Employee Information</h3>
		</div>
		<div class="panel-body">
			<form id="contact-form" method="post" action="<?php echo base_url(); ?>index.php/master/add_employee" role="form">

			  <div class="messages"></div>

			  <div class="controls">
			  		<!-- 1ST ROW !-->
			  		<div class="row">
		          <div class="col-md-3">
	              <div class="form-group">
	                  <label for="form_name">Employee Number *</label>
	                  <input id="form_name" type="text" name="employee_number" class="form-control">
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
		          <div class="col-md-3">
	              <div class="form-group">
	                  <label for="form_name">Middlename *</label>
	                  <input id="form_name" type="text" name="middle_name" class="form-control">
	              </div>
		          </div>
			      </div>

			      <!-- 2ND ROW !-->
			      <div class="row">
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
	                  <label for="form_name">Birthdate *</label>
	                  <input id="form_name" type="text" name="birthdate" class="form-control" value="<?php echo date('Y/m/d') ?>">
	              </div>
		          </div>
		          <div class="col-md-3">
	              <div class="form-group">
	                  <label for="form_name">Age *</label>
	                  <input id="form_name" type="text" name="age" class="form-control">
	              </div>
		          </div>
		          <div class="col-md-3">
	              <div class="form-group">
	                  <label for="form_name">Status *</label>
	                  <select class="form-control" name="status">
	                  	<option value=" ">SELECT</option>
	                  	<option value="Single">Single</option>
	                  	<option value="Married">Married</option>
	                  	<option value="Widow">Widow</option>
	                  	<option value="Separated">Separated</option>
	                  </select>	
	              </div>
		          </div>
			      </div>

			      <!-- 3RD ROW !-->
			      <div class="row">
		      		<div class="col-md-3">
	              <div class="form-group">
	                  <label for="form_name">Registered Address *</label>
	                  <textarea class="form-control" name="registered_address"></textarea>
	              </div>
		          </div>
		          <div class="col-md-3">
	              <div class="form-group">
	                  <label for="form_name">Provincial Address </label>
	                  <textarea class="form-control" name="provincial_address"></textarea>
	              </div>
		        	</div>
		        	<div class="col-md-3">
		              <div class="form-group">
		                  <label for="form_name">Contact Number *</label>
		                  <input id="form_name" type="text" name="contact_no" class="form-control">
		              </div>
			        </div>
			        <div class="col-md-3">
	              <div class="form-group">
	                  <label for="form_name">Employment Status *</label>
	                  <select class="form-control" name="employment_status">
	                  	<option value=" ">SELECT</option>
	                  	<option value="Probationary">Probationary</option>
	                  	<option value="Regular">Regular</option>
	                  </select>	
	              </div>
		          </div>
			      </div>

			      <!-- 4TH ROW !-->
			      <div class="row">
			      	<div class="col-md-3">
	              <div class="form-group">
	                  <label for="form_name">Date Hired </label>
	                  <input id="form_name" type="text" name="date_hired" class="form-control" placeholder="YYYY/MM/DD">
	              </div>
		          </div>	
		          <div class="col-md-3">
	              <div class="form-group">
	                  <label for="form_name">Salary *</label>
	                  <input id="form_name" type="text" name="salary" class="form-control">
	              </div>
		          </div>
		          <div class="col-md-3">
	              <div class="form-group">
	                  <label for="form_name">SSS No. *</label>
	                  <input id="form_name" type="text" name="sss_no" class="form-control">
	              </div>
		          </div>
		          <div class="col-md-3">
	              <div class="form-group">
	                  <label for="form_name">Philhealth No. *</label>
	                  <input id="form_name" type="text" name="phil_no" class="form-control">
	              </div>
		          </div>
			      </div>

			      <!-- 5TH ROW -->
			      <div class="row">
			      	<div class="col-md-3">
	              <div class="form-group">
	                  <label for="form_name">Pagibig No. *</label>
	                  <input id="form_name" type="text" name="pagibig_no" class="form-control">
	              </div>
		          </div>  
		          <div class="col-md-3">
	              <div class="form-group">
	                  <label for="form_name">TIN No. </label>
	                  <input id="form_name" type="text" name="tin_no" class="form-control">
	              </div>
		          </div>
		          <div class="col-md-3">
	              <div class="form-group">
	                  <label for="form_name">TAX Exemption *</label>
	                  <select class="form-control" name="tax_exemption">
	                  	<option value=" ">SELECT</option>
	                  	<option value="1">1</option>
	                  	<option value="2">2</option>
	                  	<option value="3">3</option>
	                  	<option value="4">4</option>
	                  	<option value="5">5</option>
	                  	<option value="6">6</option>
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
			      </div>

			      <!-- 6th row -->
			      <div class="row">
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
		          <div class="col-md-3">
	              <div class="form-group"> 
	                  <label for="form_name">Department *</label> 
	                  <select class="form-control" name="department">
	                  	<option value=" ">SELECT</option>
	                  	<?php if($department) : ?> 
	                  		<?php foreach($department as $dept) : ?>
	                  			<option value="<?php echo $dept->id; ?>"><?php echo $dept->name; ?></option>
	                  		<?php endforeach; ?>	
	                  	<?php endif; ?>	
	                  </select>	
	              </div>
		          </div>
		          <div class="col-md-3">
	              <div class="form-group">
	                  <label for="form_name">Sick Leave </label>
	                  <input id="form_name" type="text" name="sl" class="form-control">
	              </div>
		          </div>
		          <div class="col-md-3">
	              <div class="form-group">
	                  <label for="form_name">Vacation Leave </label>
	                  <input id="form_name" type="text" name="vl" class="form-control">
	              </div>
		          </div>
			      </div>

			      <hr>
			      <center><h3>DEPENDENT(S)/BENEFICIARY(IES)</h3></center>
			      <div class="row">
			      		<div class="col-md-3">
		              <div class="form-group">
		                  <label for="form_name">SPOUSE</label>
		                  <input type="hidden" name="spouse" value="SPOUSE">
		                  <input id="form_name" type="text" name="spouse_lastname" class="form-control" placeholder="Lastname">
		              </div>
			          </div>	
			          <div class="col-md-3">
		              <div class="form-group">
		                  <label for="form_name"></label>
		                  <input id="form_name" type="text" name="spouse_firstname" class="form-control" placeholder="Firstname">
		              </div>
			          </div>
			          <div class="col-md-3">
		              <div class="form-group">
		                  <label for="form_name"></label>
		                  <input id="form_name" type="text" name="spouse_middlename" class="form-control" placeholder="Middlename">
		              </div>
			          </div>
			          <div class="col-md-3">
		              <div class="form-group">
		                  <label for="form_name"></label>
		                  <input id="form_name" type="text" name="spouse_birthdate" class="form-control" placeholder="Birthdate (YYYY/MM/DD)">
		              </div>
			          </div>
			      </div>

			      <div class="row">
		      		<div class="col-md-3">
	              <div class="form-group">
	                  <label for="form_name">CHILDREN</label>
	                  <input type="hidden" name="children" value="CHILDREN">
	                  <input id="form_name" type="text" name="children_lastname" class="form-control" placeholder="Lastname">
	              </div>
		          </div>	
		          <div class="col-md-3">
	              <div class="form-group">
	                  <label for="form_name"></label>
	                  <input id="form_name" type="text" name="children_firstname" class="form-control" placeholder="Firstname">
	              </div>
		          </div>
		          <div class="col-md-3">
	              <div class="form-group">
	                  <label for="form_name"></label>
	                  <input id="form_name" type="text" name="children_middlename" class="form-control" placeholder="Middlename">
	              </div>
		          </div>
		          <div class="col-md-3">
	              <div class="form-group">
	                  <label for="form_name"></label>
	                  <input id="form_name" type="text" name="children_birthdate" class="form-control" placeholder="Birthdate (YYYY/MM/DD)">
	              </div>
		          </div>
			      </div>


						<div class="row">
		      		<div class="col-md-3">
	              <div class="form-group">
	                  <label for="form_name"></label>
	                  <input id="form_name" type="text" name="children_lastname2" class="form-control" placeholder="Lastname">
	              </div>
		          </div>	
		          <div class="col-md-3">
	              <div class="form-group">
	                  <label for="form_name"></label>
	                  <input id="form_name" type="text" name="children_firstname2" class="form-control" placeholder="Firstname">
	              </div>
		          </div>
		          <div class="col-md-3">
	              <div class="form-group">
	                  <label for="form_name"></label>
	                  <input id="form_name" type="text" name="children_middlename2" class="form-control" placeholder="Middlename">
	              </div>
		          </div>
		          <div class="col-md-3">
	              <div class="form-group">
	                  <label for="form_name"></label>
	                  <input id="form_name" type="text" name="children_birthdate2" class="form-control" placeholder="Birthdate (YYYY/MM/DD)">
	              </div>
		          </div>
			      </div>

			      <div class="row">
		      		<div class="col-md-3">
	              <div class="form-group">
	                  <label for="form_name"></label>
	                  <input id="form_name" type="text" name="children_lastname3" class="form-control" placeholder="Lastname">
	              </div>
		          </div>	
		          <div class="col-md-3">
	              <div class="form-group">
	                  <label for="form_name"></label>
	                  <input id="form_name" type="text" name="children_firstname3" class="form-control" placeholder="Firstname">
	              </div>
		          </div>
		          <div class="col-md-3">
	              <div class="form-group">
	                  <label for="form_name"></label>
	                  <input id="form_name" type="text" name="children_middlename2" class="form-control" placeholder="Middlename">
	              </div>
		          </div>
		          <div class="col-md-3">
	              <div class="form-group">
	                  <label for="form_name"></label>
	                  <input id="form_name" type="text" name="children_birthdate3" class="form-control" placeholder="Birthdate (YYYY/MM/DD)">
	              </div>
		          </div>
			      </div>

			      <hr>
			      <center><h4>OTHER BENEFICIARY/IES (If without spouse & child and parents are both deceased)</h4></center>
			      <div class="row">
			      	<div class="center">
			      		<div class="col-md-2">
		              <div class="form-group">
		                  <label for="form_name"></label>
		                  <input id="form_name" type="text" name="benificiary_lastname" class="form-control" placeholder="Lastname">
		              </div>
			          </div>	
			          <div class="col-md-2">
		              <div class="form-group">
		                  <label for="form_name"></label>
		                  <input id="form_name" type="text" name="benificiary_firstname" class="form-control" placeholder="Firstname">
		              </div>
			          </div>
			          <div class="col-md-2">
		              <div class="form-group">
		                  <label for="form_name"></label>
		                  <input id="form_name" type="text" name="benificiary_middlename" class="form-control" placeholder="Middlename">
		              </div>
			          </div>
			          <div class="col-md-2">
		              <div class="form-group">
		                  <label for="form_name"></label>
		                  <input id="form_name" type="text" name="benificiary_relationship" class="form-control" placeholder="Relationship">
		              </div>
			          </div>
			          <div class="col-md-2">
		              <div class="form-group">
		                  <label for="form_name"></label>
		                  <input id="form_name" type="text" name="benificiary_birthdate" class="form-control" placeholder="Birthdate">
		              </div>
			          </div>	
			      	</div>
			      </div>

			      <div class="row">
			      	<div class="center">
			      		<div class="col-md-2">
		              <div class="form-group">
		                  <label for="form_name"></label>
		                  <input id="form_name" type="text" name="benificiary_lastname2" class="form-control" placeholder="Lastname">
		              </div>
			          </div>	
			          <div class="col-md-2">
		              <div class="form-group">
		                  <label for="form_name"></label>
		                  <input id="form_name" type="text" name="benificiary_firstname2" class="form-control" placeholder="Firstname">
		              </div>
			          </div>
			          <div class="col-md-2">
		              <div class="form-group">
		                  <label for="form_name"></label>
		                  <input id="form_name" type="text" name="benificiary_middlename2" class="form-control" placeholder="Middlename">
		              </div>
			          </div>
			          <div class="col-md-2">
		              <div class="form-group">
		                  <label for="form_name"></label>
		                  <input id="form_name" type="text" name="benificiary_relationship2" class="form-control" placeholder="Relationship">
		              </div>
			          </div>
			          <div class="col-md-2">
		              <div class="form-group">
		                  <label for="form_name"></label>
		                  <input id="form_name" type="text" name="benificiary_birthdate2" class="form-control" placeholder="Birthdate">
		              </div>
			          </div>	
			      	</div>
			      </div>


			      <div class="row">
		          <div class="col-md-12">
		              <center><input type="submit" class="btn btn-primary btn-send" value="Submit"></center>
		          </div>
			      </div>

			</div>
			</form>
		</div>
	</div>
</div>	