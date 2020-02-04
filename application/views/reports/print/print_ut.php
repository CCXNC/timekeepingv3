<!DOCTYPE html>
<html>
<head>
	<title>PRINT UNDERTIME</title>
	<style type="text/css">
		.container {
			text-align: center;
		}
		.header {
			margin-top: -5px;
		}
		body {
			font-family: arial;
			margin: 0;
		}
		.title-gtlic {
			color: rgb(251,0,11);
		}
		.title-nhfc {
			color: rgb(19,148,19);
			margin-top: -15px;
		}
		.cut_off_date {
			font-size : 10;
			margin-top: -10px;
			padding-bottom: 10px;
		}
		.reason {
			font-size: 8px;
		}
		td,th {
			font-size: 12px;
			text-align: left;
		}
		.prepared_by {
			float: left;
			margin-left:70px;
			margin-top: 30px;
		}
		.checked_by {
			float: right;
			margin-right:90px;
			margin-top: 30px;
		}
		.concurred_by {
			float: left;
			margin-left:60px;
			margin-top: 60px;
		}
		span {
			text-decoration: underline;
		}
		.approved_by {
			float: right;
			margin-right:80px;
			margin-top: 60px;
		}
		.title_emp
		{
			font-size: 10px;
			margin-left: 90px;
		}
		@media print {
    .pagebreak { page-break-before: always; } /* page-break-after works, as well */
		}
	</style>
	<link href="<?php echo base_url(); ?>/assets/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
	<div class="container">
		<div class="header">
			<?php if($company_id == 1) : ?>
				<img src="<?php echo base_url(); ?>/assets/img/gtlic.jpg" width="150px;">
			<?php endif; ?>
			<?php if($company_id == 0) : ?>
				<img src="<?php echo base_url(); ?>/assets/img/Logo.jpg" width="150px;">
			<?php endif; ?>
		</div>
		<div class="title-gtlic">
			<?php if($company_id == 1) : ?>
				<b>GOLDEN TREASURE LENDING INVESTORS CORPORATION</b>
			<?php endif; ?>
		</div>	
		<div class="title-nhfc">
			<br>
			<?php if($company_id == 0) : ?>
				<b>NEW HORIZON FINANCE CORPORATION</b>
			<?php endif; ?>
		</div>
		
		<br>
		<div class="cut_off_date">
		 		<p><b>UNDERTIME LIST</b></p>
			<p><b>CUTOFF DATE : <?php echo $start_date; ?> - <?php echo $end_date; ?></b></p> 
		</div>
		<table class="table table-bordered table-hover table-striped cl">
			<thead>
				<th>Employee Name</th>
				<th>Date</th>
				<th>Time out</th>
				<th>Status</th>
			</thead>
			<?php if($uts) : ?>
				<?php $i = 1; ?>
				<?php foreach($uts as $ut) : ?>
					<?php if($i <= 15) : ?>
						<tr>
							<td><?php echo $ut->name; ?></td>
							<td><?php echo $ut->date_ut; ?></td>
							<td><?php echo $ut->time_out; ?></td>
							<td><?php echo substr($ut->reason, 0, 50); ?></td>
						</tr>
					<?php endif; ?>
					<?php $i++; ?>
				<?php endforeach; ?>
			<?php endif; ?>	
		</table>
		<?php //echo $i; ?>
		<?php if($i >= 16) : ?>
		<table class="table table-bordered table-hover table-striped cl" style="page-break-before: always;">
			<?php if($uts) : ?>
				<?php $i = 1; ?>
				<?php foreach($uts as $ut) : ?>
					<?php if($i >= 16) : ?>
						<tr>
							<td><?php echo $ut->name; ?></td>
							<td><?php echo $ut->date_ut; ?></td>
							<td><?php echo $ut->time_out; ?></td>
							<td><?php echo substr($ut->reason, 0, 50); ?></td>
						</tr>
					<?php endif; ?>
					<?php $i++; ?>
				<?php endforeach; ?>
			<?php endif; ?>	
		</table>
		<?php endif; ?>
		<div class="prepared_by">
			Prepared By : <span><?php echo $this->session->userdata('fullname'); ?></span>
			<div class="title_emp">HR&ADMIN ASST</div>
		</div>
		<div class="checked_by">
			Checked By : <span>Ma. Rowena Hilario</span>
			<div class="title_emp">HR&ADMIN MANAGER</div>
		</div>
		<div class="concurred_by">
			Concurred By : <span>Cecilia Mendoza</span>
			<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;GM
		</div>
		<div class="approved_by">
			Approved By : <span>Karen Yvette Caronan</span>
			<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;CFO
		</div>
	</div>
	
	
</body>
</html>