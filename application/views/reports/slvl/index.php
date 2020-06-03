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
	.add{
		margin-top: -45px;
		margin-left: 1200px;
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
<?php if($this->session->flashdata('update_msg')) : ?>
   <p class="alert alert-dismissable alert-success"><?php echo $this->session->flashdata('update_msg'); ?></p>
<?php endif; ?>
<?php if($this->session->flashdata('cancel_msg')) : ?>
   <p class="alert alert-dismissable alert-danger"><?php echo $this->session->flashdata('cancel_msg'); ?></p>
<?php endif; ?>
<!-- TABLE OF BRANCHES -->
<div class="row">
	<div class="col-lg-12">
  	<div class="panel panel-primary">
    	<div class="panel-heading">
      	SL/VL List
      	<a href="<?php echo base_url(); ?>index.php/reports/add_slvl" class="a btn btn-default">ADD</a>
      </div>	
        <form method="post" id="slvl">
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
						        <div class="col-md-2">
						          <div class="form-group">
						              <label for="form_name">Type</label>
						              <select class="form-control" name="slvl_type">
						              	<option value="ALL" <?php  echo 'ALL' == $slvl_type ? 'selected' : ' '; ?>>ALL</option>
						              	<option value="SL" <?php  echo 'SL' == $slvl_type ? 'selected' : ' '; ?> >SICK LEAVE</option>
						              	<option value="VL" <?php  echo 'VL' == $slvl_type ? 'selected' : ' '; ?> >VACATION LEAVE</option>
						              	<option value="AB" <?php  echo 'AB' == $slvl_type ? 'selected' : ' '; ?> >ABSENCES</option>
						              </select>	
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
	                    <th>Employee Name</th>
	                    <th>Date</th>
	                    <th>Type</th>
	                    <th>Reason</th>
	                    <th>Status</th>
	                    <th><center>Action</center></th>
		                </tr>
			            </thead>
		            	<tr>
			            <?php if(isset($slvl)) : ?>
			                <?php foreach($slvl as $slvl) : ?>
					                <tr class="data"> 
					                	<td>
											<?php 
												/*$string = $slvl->status; 
												$search = "CANCELLED"; 
												$position = strpos($string, $search);  
												if($position !== FALSE) : */
												if($slvl->status != "PROCESSED") :
											?>
					                			<center><input type="checkbox" name="employee[]" value="<?php echo $slvl->id . '|' . $slvl->employee_number . '|' . $slvl->name . '|' . $slvl->date_start . '|' . $slvl->type_slvl . '|' . $slvl->sl_am_pm . '|' . $slvl->sl_credit . '|' . $slvl->vl_credit . '|' . $slvl->elcl_credit . '|' . $slvl->fl_credit . '|' . $slvl->slvl_num; ?>"> </center>
					                		<?php endif; ?>
					                	</td>
				                    <td title="<?php echo $slvl->branch_id; ?>"><?php echo $slvl->name; ?></td>
				                    <td><?php echo $slvl->date_start ?></td>
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
				                    		else
				                    		{
				                    			echo $slvl->type_name;
				                    		}	
				                    	?> 
				                    </td>
				                    <td><?php echo $slvl->reason; ?></td>
				                    <td><?php echo $slvl->status; ?></td>
				                    <td>
				                     <center>
										<a class="btn btn-xs btn-danger" onclick="return confirm('Do you want to Delete slvl?');" href="<?php echo base_url(); ?>index.php/reports/delete_slvl/<?php echo $slvl->id; ?>/<?php echo $slvl->employee_number; ?>/<?php echo $slvl->type_slvl; ?>">Delete</a>
										<a class="btn btn-xs btn-danger" onclick="return confirm('Do you want to Cancelled slvl?');" href="<?php echo base_url(); ?>index.php/users/cancelled_slvl/<?php echo $slvl->id; ?>">Cancelled</a>
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
				$('#slvl').attr('action', 'rfa_slvl');
				$('#slvl').submit();
			} else {
				return false;
			} 
		});

		$('#fa').click(function() {
			var a = confirm("Are you sure you want to Approved Data?");
			if (a == true) {
				$('#slvl').attr('action', 'fa_slvl');
				$('#slvl').submit();
			} else {
				return false;
			} 
		});

		$('#rfv').click(function() {
			var a = confirm("Are you sure you want to Verified Data?");
			if (a == true) {
				$('#slvl').attr('action', 'rfv_slvl');
				$('#slvl').submit();
			} else {
				return false;
			} 
		});

		$('#fv').click(function() {
			var a = confirm("Are you sure you want to Verified Data?");
			if (a == true) {
				$('#slvl').attr('action', 'fv_slvl');
				$('#slvl').submit();
			} else {
				return false;
			} 
		});

		$('#nb').click(function() {
			var a = confirm("Are you sure you want to Processed Data?");
			if (a == true) {
				$('#slvl').attr('action', 'nb_slvl');
				$('#slvl').submit();
			} else {
				return false;
			} 
		});

		$('#afp').click(function() {
			var a = confirm("Are you sure you want to Processed Data?");
			if (a == true) {
				$('#slvl').attr('action', 'afp_slvl');
				$('#slvl').submit();
			} else {
				return false;
			} 
		});

		$("#checkAll").click(function(){
   	 $('input:checkbox').not(this).prop('checked', this.checked);
		});



	});	
</script>

