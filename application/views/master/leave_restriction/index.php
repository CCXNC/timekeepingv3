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
				Leave Restriction
			</div>
			<div class="panel-body">
				<?php if($this->session->flashdata('update_msg')) : ?>
					<p class="alert alert-dismissable alert-success"><?php echo $this->session->flashdata('update_msg'); ?></p>
				<?php endif; ?>
				<div class="table-responsive"> 
                    <form action="<?php echo base_url(); ?>index.php/master/update_leave_restriction" method="POST" >
                        <table class="table table-bordered table-hover table-striped cl"> 
                            <thead>
                                <tr>
                                    <th>VACATION LEAVE</th>
                                    <th>SICK LEAVE</th>
                                    <th>EMERGENCY LEAVE</th>
                                    <th>OVERTIME</th>
                                    <th>UNDERTIME</th>
                                </tr>
                                <tr>
                                    <!-- VL -->
                                    <td>
                                        <input type="checkbox">
                                        <label>On the same day</label><br>
                                        
                                        <input type="checkbox"  name="" value="">
                                        <label>Before</label><br>
                                        <input type="number" value="0" ><br>
                                    
                                        <input type="checkbox" id="checkBoxVl" name="" value="">
                                        <label>After</label><br>
                                        <input type="number" id="vlTotal" name="vl" value="<?php echo $leave_restriction->rec_vl;?>" ><br>
                                    </td>
                                    <!-- SL -->
                                    <td>
                                    <input type="checkbox" checked>
                                        <label>On the same day</label><br>

                                        <input type="checkbox"  name="" >
                                        <label>Before</label><br>
                                        <input type="number" value="0" ><br>
                                    
                                        <input type="checkbox"  name="" value="">
                                        <label>After</label><br>
                                        <input type="number" value="0" ><br>
                                    </td>
                                    <!-- EL -->
                                    <td>
                                        <input type="checkbox" checked>
                                        <label for="vehicle2">On the same day</label><br>

                                        <input type="checkbox"  name="" id="checkBoxEl">
                                        <label>Before</label><br>
                                        <input type="number" id="elTotal" name="el" value="<?php echo $leave_restriction->rec_el;?>" ><br>
                                    
                                        <input type="checkbox"  name="" value="">
                                        <label>After</label><br>
                                        <input type="number" value="0" ><br>
                                    </td>
                                    <!-- OT -->
                                    <td>
                                        <input type="checkbox" checked>
                                        <label for="vehicle2">On the same day</label><br>

                                        <input type="checkbox"  name="" value="">
                                        <label>Before</label><br>
                                        <input type="number" name="" ><br>
                                    
                                        <input type="checkbox" id="checkBoxOt">
                                        <label>After</label><br>
                                        <input type="number" id="otTotal" name="test" value="<?php echo $leave_restriction->rec_ot;?>" ><br>
                                    </td>
                                    <!-- UT -->
                                    <td>
                                        <input type="checkbox" checked>
                                        <label for="vehicle2">On the same day</label><br>

                                        <input type="checkbox"  name="" value="">
                                        <label>Before</label><br>
                                        <input type="number" name="" ><br>
                                    
                                        <input type="checkbox"  id="checkBoxUt">
                                        <label>After</label><br>
                                        <input type="number" id="utTotal" name="ut12" value="<?php echo $leave_restriction->rec_ut;?>" ><br>
                                    </td>
                                </tr>
                            </thead>
                        </table>	
                        <center>
                            <input type="submit" value="Update" class="btn btn-primary">
                        </center>
                    </form>    
				</div>		
			</div>
		</div>
	</div>
</div>
<script>
    var vlNum = document.getElementById('vlTotal').value;
    var vl = document.getElementById("checkBoxVl");
    var elNum = document.getElementById('elTotal').value;
    var el = document.getElementById("checkBoxEl");
    var otNum = document.getElementById('otTotal').value;
    var ot = document.getElementById("checkBoxOt");
    var utNum = document.getElementById('utTotal').value;
    var ut = document.getElementById("checkBoxUt");

    if(vlNum != null) {
        vl.checked = true;
        //alert('123')
    }

    if(elNum != null) {
        el.checked = true;
    }

    if(otNum != null) {
        ot.checked = true;
    }

    if(utNum != null) {
        ut.checked = true;
    }
    
</script>
