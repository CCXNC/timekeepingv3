<style type="text/css">
	.container {
		margin-top: 65px;
		margin-left: 250px;
		width: 60%;
	}

</style>
<div class="container">
	<div class="panel panel-primary">
		<div class="panel-heading">
			<h3 class="panel-title">Edit Branch Form</h3>
		</div>
		<div class="panel-body">
			<form id="contact-form" method="post" action="<?php echo base_url(); ?>index.php/master/edit_branch/<?php echo $branch->id; ?>" role="form">
				<div style="color:red"><?php echo validation_errors(); ?> </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="form_name">Code</label>
                            <input type="text" class="form-control" name="code" value="<?php echo $branch->code; ?>">
                        </div>	
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="form_name">Name</label>
                            <input type="text" class="form-control" name="name" value="<?php echo $branch->name; ?>">
                        </div>	
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="form_name">OIC</label>
                            <input type="text" class="form-control" name="oic" value="<?php echo $branch->oic; ?>">
                        </div>	
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="form_name">Address</label>
                            <textarea class="form-control" name="address" cols="30" rows="5"><?php echo $branch->address; ?></textarea>
                        </div>	
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <center><input type="submit" class="btn btn-primary a" value="Update"></center>
                    </div>
                </div>
			</form>
		</div>
	</div>
</div>	