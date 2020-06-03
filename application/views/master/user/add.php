<style>
    .container {
        margin-top:100px;
    }
    .panel-heading{
		font-size: 18px;
		font-family: century gothic;
	}
</style>
<div class="container">
    <div class="col-lg-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
            Add User Form    
            </div>
            <div class="panel-body">
                <form action="<?php echo base_url(); ?>index.php/master/add_user" method="POST">
                    <div style="color:red"><?php echo validation_errors(); ?> </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Employee Number*</label>    
                            <input type="text" name="emp_no_id" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Fullname*</label>    
                            <input type="text" name="fullname" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>User Level*</label>    
                            <select name="user_level" class="form-control">
                                <option value="0">Employee</option>
                                <option value="1">ADMIN</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Type*</label>  
                            <select name="type" class="form-control">
                                <option value="0">EMPLOYEE</option>
                                <option value="is_rfa">OIC</option>
                                <option value="is_oichead">SUPERVISOR</option>
                                <option value="is_fa">DEPARTMENT HEAD</option>
                                <option value="is_rfv">HR ASSIST</option>
                                <option value="is_verify">HR HEAD</option>
                                <option value="is_gm">GM</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Branch*</label>    
                            <select name="branch" class="form-control">
                            <?php if($branches) : ?>
                                <?php foreach($branches as $branch) : ?>
                                    <option value="<?php echo $branch->id; ?>"><?php echo $branch->name; ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Department*</label>    
                            <select name="department" class="form-control">
                            <?php if($department) : ?>
                                <?php foreach($department as $department) : ?>
                                    <option value="<?php echo $department->id; ?>"><?php echo $department->name; ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Company*</label>    
                            <select name="company" class="form-control">
                            <?php if($company) : ?>
                                <?php foreach($company as $com) : ?>
                                    <option value="<?php echo $com->id; ?>"><?php echo $com->name; ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Username*</label>    
                            <input type="text" name="username" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Password*</label>    
                            <input type="text" name="password" class="form-control">
                        </div>
                    </div>
                    <center><input type="submit" class="btn btn-primary" value="submit"></center>
                </form>
            </div>  
        </div>
    </div>    
</div>