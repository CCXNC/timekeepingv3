<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>NHFC Time Keeping</title>

    <!-- Bootstrap Core CSS -->
    <link href="<?php echo base_url(); ?>/assets/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="<?php echo base_url(); ?>/assets/css/metisMenu.min.css" rel="stylesheet">

    <!-- Timeline CSS -->
    <link href="<?php echo base_url(); ?>/assets/css/timeline.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="<?php echo base_url(); ?>/assets/css/startmin.css" rel="stylesheet">

    <!-- Morris Charts CSS -->
    <link href="<?php echo base_url(); ?>/assets/css/morris.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="<?php echo base_url(); ?>/assets/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" type="text/css">

    <link href="<?php echo base_url(); ?>/assets/css/jquery-ui.css" rel="stylesheet" type="text/css">          
                                                                                                               
                              
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/bootstrap-theme.min.css') ?>">

</head>
<style type="text/css">
    .container{
        color: black;
    }
</style>
<body>
<div id="wrapper">

    <!-- Navigation -->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="navbar-header">
            <a class="navbar-brand" href="<?php echo base_url(); ?>index.php/dashboard/dashboard">NHFC Time Keeping v3</a>
        </div>
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <!-- Top Navigation: Right Menu -->

        <ul class="nav navbar-right navbar-top-links">
            <?php if($this->session->userdata('user_level') == 1) : ?>
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                       <i class="fa fa-group fa-fw"></i> MASTER <b class="caret"></b>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li>
                            <a href="<?php echo base_url(); ?>index.php/master/index_employee"><i class="fa fa-group fa-fw"></i>Employees</a>
                        </li>
                         <li>
                            <a href="<?php echo base_url(); ?>index.php/master/index_user"><i class="fa fa-group fa-fw"></i>Users</a>
                        </li>
                        <li>
                            <a href="<?php echo base_url(); ?>index.php/master/index_branches"><i class="fa fa-institution fa-fw"></i>Branches</a>
                        </li>
                        <li>
                            <a href="<?php echo base_url(); ?>index.php/master/index_calendar"><i class="fa fa-calendar fa-fw"></i>Calendar</a>
                        </li>
                        <li>
                            <a href="<?php echo base_url(); ?>index.php/master/index_schedule"><i class="fa fa-calendar fa-fw"></i>Schedule</a>
                        </li>
                        <li>
                            <a href="<?php echo base_url(); ?>index.php/master/index_leave_restriction"><i class="fa fa-cog fa-fw"></i>Leave Restriction</a>
                        </li>
                        
                    </ul>
                </li>
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-clock-o fa-fw"></i> TIMEKEEPING <b class="caret"></b>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li>
                            <a href="<?php echo base_url(); ?>index.php/payroll/set_upload_date"><i class="fa fa-clock-o fa-fw"></i>Set Upload Date</a>
                        </li>
                        <li>
                            <a href="<?php echo base_url(); ?>index.php/csv_import/index_csv"><i class="fa fa-clock-o fa-fw"></i>CSV DATA</a>
                        </li>
                        <li>
                            <a href="<?php echo base_url(); ?>index.php/reports/index_time_keeping"><i class="fa fa-clock-o fa-fw"></i>Time Keeping</a>
                        </li>
                        <li>
                            <a href="<?php echo base_url(); ?>index.php/reports/in_out_index"><i class="fa fa-clock-o fa-fw"></i>IN/OUT</a>
                        </li>
                        <!--<li>
                            <a href="<?php echo base_url(); ?>index.php/attendance/index_attendance"><i class="fa fa-book fa-fw"></i>Attendance</a>
                        </li>-->
                    </ul> 
                </li>
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-file-pdf-o"></i> REPORTS <b class="caret"></b>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        
                        <li>
                            <a href="<?php echo base_url(); ?>index.php/reports/index_slvl"><i class="fa fa-archive fa-fw"></i>SL/VL</a>
                        </li>
                        <li>
                            <a href="<?php echo base_url(); ?>index.php/reports/index_ob"><i class="fa fa-archive fa-fw"></i>OB</a>
                        </li>
                         <li>
                            <a href="<?php echo base_url(); ?>index.php/reports/index_undertime"><i class="fa fa-archive fa-fw"></i>Undertime</a>
                        </li>
                         <li>
                            <a href="<?php echo base_url(); ?>index.php/reports/index_ot"><i class="fa fa-archive fa-fw"></i>OverTime</a>
                        </li>
                         <li>
                            <a href="<?php echo base_url(); ?>index.php/reports/index_report_generation"><i class="fa fa-archive fa-fw"></i>Total Summary List</a>
                        </li>
                        <li>
                            <a target="_blank" href="<?php echo base_url(); ?>index.php/reports/report_generation"><i class="fa fa-archive fa-fw"></i>Report Generation</a>
                        </li>
                        <li>
                            <a href="<?php echo base_url(); ?>index.php/reports/index_leave_credits"><i class="fa fa-archive fa-fw"></i>Leave Credits</a>
                        </li>
                        <li>
                            <a href="<?php echo base_url(); ?>index.php/reports/index_adjustment"><i class="fa fa-cog fa-fw"></i>Delete Upload/CSV</a>
                        </li>
                        <li>
                            <a href="<?php echo base_url(); ?>index.php/reports/adjustment"><i class="fa fa-cog fa-fw"></i>Adjustment</a>
                        </li>
                    </ul>
                </li>
            <?php endif; ?>    
            <?php if($this->session->userdata('is_oichead') == 1 || $this->session->userdata('is_rfa') == 1  || $this->session->userdata('is_gm') == 1 || $this->session->userdata('is_fa') == 4 || $this->session->userdata('is_rfv') == 1 || $this->session->userdata('is_cfo') == 1)  : ?>
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-folder"></i> FILES <b class="caret"></b>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li>
                            <a href="<?php echo base_url(); ?>index.php/users/slvl_list"><i class="fa fa-folder fa-fw"></i>SL/VL</a>
                        </li>
                        <li>
                            <a href="<?php echo base_url(); ?>index.php/users/ob_list"><i class="fa fa-folder fa-fw"></i>OB</a>
                        </li>
                         <li>
                            <a href="<?php echo base_url(); ?>index.php/users/ut_list"><i class="fa fa-folder fa-fw"></i>Undertime</a>
                        </li>
                         <li>
                            <a href="<?php echo base_url(); ?>index.php/users/ot_list"><i class="fa fa-folder fa-fw"></i>OverTime</a>
                        </li>
                    </ul>
                </li> 
            <?php endif; ?>    
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-folder"></i> FORMS <b class="caret"></b>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li>
                            <a href="<?php echo base_url(); ?>index.php/users/add_slvl"><i class="fa fa-file fa-fw"></i>SL/VL</a>
                        </li>
                        <li>
                            <a href="<?php echo base_url(); ?>index.php/users/add_ob"><i class="fa fa-file fa-fw"></i>OB</a>
                        </li>
                        <li>
                            <a href="<?php echo base_url(); ?>index.php/users/add_ot"><i class="fa fa-file fa-fw"></i>OverTime</a>
                        </li>
                         <li>
                            <a href="<?php echo base_url(); ?>index.php/users/add_undertime"><i class="fa fa-file fa-fw"></i>Undertime</a>
                        </li>
                        
                    </ul>
                </li>  
     
            <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                    <i class="fa fa-user fa-fw"></i> <?php echo $this->session->userdata('username'); ?> <b class="caret"></b>
                </a>
                <ul class="dropdown-menu dropdown-user">
                    <li>
                        <a href="<?php echo base_url(); ?>index.php/user/change_password"><i class="fa fa-user fa-fw"></i> User Change Password</a>
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-gear fa-fw"></i> Settings</a>
                    </li>
                    <li class="divider"></li>
                    <li>
                        <a href="<?php echo base_url();?>index.php/user/logout"><i class="fa fa-sign-out fa-fw"></i>Logout</a>
                    </li>
                </ul>
            </li>
        </ul>
       
    </nav> 
    <!-- Page Content -->
    <div>
        <div class="container-fluid">
            <?php $this->load->view($main_content); ?> 
        </div>
    </div>

</div>


<!-- jQuery -->
<script src="<?php echo base_url(); ?>/assets/js/jquery.min.js"></script>

<!-- Bootstrap Core JavaScript -->
<script src="<?php echo base_url(); ?>/assets/js/bootstrap.min.js"></script>

<!-- Metis Menu Plugin JavaScript -->
<!--<script src="<?php echo base_url(); ?>/assets/js/metisMenu.min.js"></script>-->


<!-- Custom Theme JavaScript -->
<!--<script src="<?php echo base_url(); ?>/assets/js/startmin.js"></script>-->

<script src="<?php echo base_url(); ?>/assets/js/jquery-ui.min.js"></script>

<script type="text/javascript" src="<?php echo base_url(); ?>/assets/js/jquery.filterTable.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        
        $('table.cl').filterTable({
            autofocus: 1, 
            placeholder: 'SEARCH'
        });

    }); 
</script>
</body>
</html>