<style type="text/css">
	.container {
		margin-top: 60px;
		margin-left: 250px;
	}
	h3,h5 {
		color: green;
	}
	.row {
		margin-left: 80px;
	}
</style>
<div class="container">

	<div class="col-sm-8">
	  <div class="panel panel-primary">
	    <div class="panel-heading">
	        <h3 class="panel-title">Holiday Calendar Form</h3>
        	
	    </div>
	    <div class="panel-body">
	    	<?php if($this->session->flashdata('add_msg')) : ?>
			     <p class="alert alert-dismissable alert-success"><?php echo $this->session->flashdata('add_msg'); ?></p>
				<?php endif; ?>
	    	<center><h3>NEW HORIZON FINANCE CORP</h3></center>
	    	<br><br>
	    	<form method="POST" action="<?php echo base_url(); ?>index.php/master/edit_calendar/<?php echo $holiday->id; ?>">
	    		<div style="color:red"><?php echo validation_errors(); ?> </div>
	    		<div class="row">
			    	<div class="col-md-10">
		          <div class="form-group">
		              <label for="form_name">TYPE</label>
		              <select class="form-control" name="holiday_type">
		              	<option value=" ">SELECT</option>
		              	<option value="LH"<?php echo $holiday->type == 'LH' ? 'selected' : ' '; ?>>LEGAL HOLIDAY</option>
		              	<option value="SH"<?php echo $holiday->type == 'SH' ? 'selected' : ' '; ?>>SPECIAL HOLIDAY</option>
		              	<option value="SE"<?php echo $holiday->type == 'SE' ? 'selected' : ' '; ?>>EVENTS</option>
		              </select>	
		          </div>
		      	</div>    
			   	</div>	
			   	<div class="row">
			   		<div class="col-md-10">
	            <div class="form-group">
	                <label for="form_name">DATE</label>
	                <input id="form_name" type="text" name="date" class="form-control" value="<?php echo $holiday->dates ?>">
	            </div>
	          </div>
			   	</div>	
	        <div class="row">
	          <div class="col-md-10">
	            <div class="form-group">
	                <label for="form_name">DESCRIPTION</label>
	                <input id="form_name" type="text" name="description" class="form-control" value="<?php echo $holiday->description; ?>">
	            </div>
	          </div>
	        </div>
	        <div class="row">
	          <div class="col-md-10">
	            <div class="form-group">
	                <label for="form_name">BRANCHES</label>
		               <select class="form-control" name="branch_id">
		              	<option value=" ">SELECT</option>
		              	<option value="ALL"<?php echo $holiday->branch_id == 'ALL' ? 'selected' : ' '; ?>>ALL</option>
		              	<option value="1"<?php echo $holiday->branch_id == 1 ? 'selected' : ' '; ?> >ALABANG</option>
		              	<option value="2"<?php echo $holiday->branch_id == 2 ? 'selected' : ' '; ?> >ALAMINOS</option>
		              	<option value="3"<?php echo $holiday->branch_id == 3 ? 'selected' : ' '; ?> >BACLARAN</option>
		              	<option value="4"<?php echo $holiday->branch_id == 4 ? 'selected' : ' '; ?> >BAGUIO</option>
		              	<option value="5"<?php echo $holiday->branch_id == 5 ? 'selected' : ' '; ?> >BALAGTAS</option>
		              	<option value="6"<?php echo $holiday->branch_id == 6 ? 'selected' : ' '; ?> >BAMBANG</option>
		              	<option value="7"<?php echo $holiday->branch_id == 7 ? 'selected' : ' '; ?> >BANGUED</option>
		              	<option value="8"<?php echo $holiday->branch_id == 8 ? 'selected' : ' '; ?> >BATANGAS</option>
		              	<option value="9"<?php echo $holiday->branch_id == 9 ? 'selected' : ' '; ?>  >BONTOC</option>
		              	<option value="10"<?php echo $holiday->branch_id == 10 ? 'selected' : ' '; ?> >CANDON</option>
		              	<option value="11"<?php echo $holiday->branch_id == 11? 'selected' : ' '; ?> >DAGUPAN</option>
		              	<option value="12"<?php echo $holiday->branch_id == 12 ? 'selected' : ' '; ?> >DIVISORIA</option>
		              	<option value="13"<?php echo $holiday->branch_id == 13 ? 'selected' : ' '; ?> >LA UNION</option>
		              	<option value="14"<?php echo $holiday->branch_id == 14 ? 'selected' : ' '; ?> >LEGAZPI</option>
		              	<option value="15"<?php echo $holiday->branch_id == 15 ? 'selected' : ' '; ?> >NAGA</option>
		              	<option value="16"<?php echo $holiday->branch_id == 16 ? 'selected' : ' '; ?> >NOVALICHES</option>
		              	<option value="17"<?php echo $holiday->branch_id == 17 ? 'selected' : ' '; ?> >ROXAS</option>
		              	<option value="18"<?php echo $holiday->branch_id == 18 ? 'selected' : ' '; ?> >SAN JUAN</option>
		              	<option value="19"<?php echo $holiday->branch_id == 19 ? 'selected' : ' '; ?> >SAN PABLO</option>
		              	<option value="20"<?php echo $holiday->branch_id == 20 ? 'selected' : ' '; ?> >SANTIAGO</option>
		              	<option value="21"<?php echo $holiday->branch_id == 21 ? 'selected' : ' '; ?> >SOLANO</option>
		              	<option value="22"<?php echo $holiday->branch_id == 22 ? 'selected' : ' '; ?> >TABUK</option>
		              	<option value="23"<?php echo $holiday->branch_id == 23 ? 'selected' : ' '; ?> >VIGAN</option>
		              	<option value="24"<?php echo $holiday->branch_id == 24 ? 'selected' : ' '; ?> >ZAMBALES</option>
		              </select>	
	            </div>
	          </div>
	        </div>
         	<div class="row">
	          <div class="col-md-10">
	              <center><input type="submit" class="btn btn-primary btn-send" value="Update"></center>
	          </div>
		      </div>
	    	</form>
	    </div>  
	  </div>
	</div>
</div>
