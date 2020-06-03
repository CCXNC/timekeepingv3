<style>
    .container {
		margin-top: 150px;
		margin-left: 300px;
		width: 50%;
	}
</style>
<div class="container">
    <form method="POST" action="<?php echo base_url(); ?>index.php/reports/edit_leave_credits/<?php echo $leave_credit->id; ?>">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h5 class="panel-title">Edit Leave Credits</h3>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="form_name">Sick Leave</label>
                            <input type="text" class="form-control" name="sl" value="<?php echo $leave_credit->sl_credit; ?>">
                        </div>	
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="form_name">Vacation Leave</label>
                            <input type="text" class="form-control" name="vl" value="<?php echo $leave_credit->vl_credit; ?>">
                        </div>	
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="form_name">Emergency Leave</label>
                            <input type="text" class="form-control" name="el" value="<?php echo $leave_credit->elcl_credit; ?>">
                        </div>	
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="form_name">Bereavement Leave</label>
                            <input type="text" class="form-control" name="bl" value="<?php echo $leave_credit->fl_credit; ?>">
                        </div>	
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <center><input type="submit" class="btn btn-primary a" value="Submit"></center>
                </div>
            </div>
            <br>
        </div>
    </form>    						
</div>
