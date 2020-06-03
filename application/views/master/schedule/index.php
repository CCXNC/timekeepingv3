<style>
    .container {
        margin-top: 150px;
        margin-left: 250px;
    }
</style>
<div class="container">
    <div class="col-md-8">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="panel-title">Employee Schedule</div>
            </div>
            <div class="panel-body">
                <form action="<?php echo base_url(); ?>index.php/master/index_schedule" method="POST">
                <?php if($this->session->flashdata('update_msg')) : ?>
                    <p class="alert alert-dismissable alert-success"><?php echo $this->session->flashdata('update_msg'); ?></p>
                <?php endif; ?>
                    <div style="color:red"><?php echo validation_errors(); ?></div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="for_name">IN (M-TH)</label>
                            <input type="text" class="form-control" name="in" value="<?php echo $schedule->daily_in; ?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="for_name">OUT (M-TH)</label>
                            <input type="text" class="form-control" name="out_m_th" value="<?php echo $schedule->daily_out;?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="for_name">OUT (F)</label>
                            <input type="text" class="form-control" name="out_f" value="<?php echo $schedule->daily_friday_out;?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="for_name">CASUAL IN (M-TH)</label>
                            <input type="text" class="form-control" name="casual_in" value="<?php echo $schedule->casual_in; ?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="for_name">CASUAL OUT (M-TH)</label>
                            <input type="text" class="form-control" name="casual_out_m_th" value="<?php echo $schedule->casual_out; ?>" >
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="for_name">CASUAL OUT (F)</label>
                            <input type="text" class="form-control" name="casual_out_f" value="<?php echo $schedule->casual_friday_out; ?>" >
                        </div>
                    </div>

                    <center>
                    <input type="submit" value="Update" class="btn btn-primary">
                    </center>
                    
                </form>
            </div>
        </div>
    </div>
</div>