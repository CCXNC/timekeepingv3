<style type="text/css">
	.container{
		margin-top: 100px;
		margin-left: 100px;
	}
	p{
		font-size: 24px;
		font-family: century gothic;
	}
	table {
    width: 90px;
  }
  .filter-table{
		position: fixed;
		margin-top: 0px;
		background-color: white;
		width: 100%;
		padding: 2px;
	}

	.is-true {
		background-color: #F9F7AD;
	}
	
	.trfixed{
		padding-bottom:10px;
	}
	
	.fixed{
		position: ;
		background-color: #FFF;
		width: 100%;
	}
	.aa {
		margin-left: 107px;
		margin-top: -50px;
	}
</style>
<div class="container">
<?php if($this->session->flashdata('branch_updated')) : ?>
     <p class="alert alert-dismissable alert-info"><?php echo $this->session->flashdata('branch_updated'); ?></p>
<?php endif; ?>
<?php if($this->session->flashdata('branch_deleted')) : ?>
      <p class="alert alert-dismissable alert-danger"><?php echo $this->session->flashdata('branch_deleted'); ?></p>
<?php endif; ?>  
<!-- TABLE OF BRANCHES -->
<div class="row">
	<form id="attendanceForm" method="post">
	<div class="col-lg-12"> 
  	<div class="panel panel-default">
	    	<div class="panel-heading">
	      	<p>ATTENDANCE LIST</p>
	      </div>	
       	<?php 
       	$start_date = $set_date->start_date; 
       	$end_date = $set_date->end_date;

       	$datediff = (strtotime($end_date) - strtotime($start_date));
				$num_dates = floor($datediff / (60 * 60 * 24));
				$num_dates = $num_dates + 1;
       	?>
       	<br>
       	<div class="row">
      		<div class="col-md-2">
	          <div class="form-group">
	              <label for="form_name">&nbsp;</label>
	             	<button type="submit" id="processBtn" class="btn btn-primary">PROCESS</button>
	             	<button type="submit" id="processBtn1" class=" aa btn btn-danger">DELETE</button>
	          </div>
	        </div>
	        <div class="col-md-3">
	          <div class="form-group">
	              <select class="form-control" name="branch_id">
	              	<option value="1">ALABANG</option>
	              	<option value="2">ALAMINOS</option>
	              	<option value="3">BACLARAN</option>
	              	<option value="4">BAGUIO</option>
	              	<option value="5">BALAGTAS</option>
	              	<option value="6">BAMBANG</option>
	              	<option value="7">BANGUED</option>
	              	<option value="8">BATANGAS</option>
	              	<option value="9">BONTOC</option>
	              	<option value="10">CANDON</option>
	              	<option value="11">DAGUPAN</option>
	              	<option value="12">DIVISORIA</option>
	              	<option value="13">LA UNION</option>
	              	<option value="14">LEGAZPI</option>
	              	<option value="15">NAGA</option>
	              	<option value="16">NOVALICHES</option>
	              	<option value="17">ROXAS</option>
	              	<option value="18">SAN JUAN</option>
	              	<option value="19">SAN PABLO</option>
	              	<option value="20">SANTIAGO</option>
	              	<option value="21">SOLANO</option>
	              	<option value="22">TABUK</option>
	              	<option value="23">VIGAN</option>
	              	<option value="24">ZAMBALES</option>
	              </select>	
	          </div>
	      	</div>   	 
       	</div>
      	<input type="hidden" name="number_dates" value="<?php echo $num_dates; ?>">
			    <div class="panel-body">
			      <div class="table-responsive">
			          <table class="table table-bordered cl">
				          <tbody>
		                <tr>
		                	<th>ID</th>
		                  <th>Employee Number</th>
											<th>Name</th>
											<th>Date</th>
											<th>Time</th>
											<th>Status</th>
		                </tr>
				            <?php

				            $emp_no = '';
				            $status = '';
				            $i = 1;
				       

				            ?>
				            <?php if($employee) : ?>
											<?php foreach($employee as $emp) : ?>
														<?php if($emp->is_transfer == '0') : ?>	
															<tr>
							                	<td>
							                		<?php echo $i; ?>
							                	</td>
							                  <td>
							                  	<input type="text" name="employee_number[]" class="hidden" value="<?php echo $emp->employee_number; ?>">
							                  	<?php echo $emp->employee_number; ?>
							                  </td>
																<td>
																	<input type="text" name="name[]" class="hidden" value="<?php echo  $emp->name; ?>">
																	<?php echo $emp->name; ?>
																</td>
																<?php 
																	$datetime = $emp->date_time;
																	$dt = explode(" ", $datetime);
																?>
																<td>
																	<input type="text" name="date[]" class="hidden" value="<?php echo  $dt[0]; ?>">
																	<?php echo $dt[0]; ?>
																</td>
																	<td>
																	<input type="text" name="time[]" class="hidden" value="<?php echo  $emp->date_time; ?>">
																	<?php echo $dt[1]; ?>
																</td>
																<td>
																	<input type="text" name="status[]" class="hidden" value="<?php echo $emp->status; ?>">
																	<?php echo $emp->status; ?>
																</td>
						                	</tr>
						                	<?php 
						                		$i++; 
						                	?>
						                <?php endif; ?>
				            	<?php endforeach; ?>
				            <?php endif; ?>
				          </tbody>	
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
		$('#processBtn').click(function() {
				var a = confirm("Process Attendance?");
				if (a == true) {
					$('#attendanceForm').attr('action', 'attendance_process');
					$('#attendanceForm').submit();
				} else {
					return false;
				} 
			});

		$('#processBtn1').click(function() {
				var a = confirm("Delete Data?");
				if (a == true) {
					$('#attendanceForm').attr('action', 'delete_csv');
					$('#attendanceForm').submit();
				} else {
					return false;
				} 
			});


		/*$("#checkAll").click(function(){
		    $('input:checkbox').not(this).prop('checked', this.checked);
		});

		$('#inBtn').click(function() {
			var a = confirm("Process IN/OUT?");
			if (a == true) {
				$('#attendanceForm').attr('action', 'is_in');
				$('#attendanceForm').submit();
			} else {
				return false;
			} 
		});

		$('#obBtn').click(function() {
			var a = confirm("Process OB?");
			if (a == true) {
				$('#attendanceForm').attr('action', 'is_ob');
				$('#attendanceForm').submit();
			} else {
				return false;
			} 
		});

		$('#processBtn').click(function() {
			var a = confirm("Process Attendance?");
			if (a == true) {
				$('#attendanceForm').attr('action', 'attendance_process');
				$('#attendanceForm').submit();
			} else {
				return false;
			} 
		});
		*/
	});
</script>