<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
<style type="text/css">
	.container {
		margin-top: 100px;
	}
	form {
		border: 2px solid;
		width: 50%;
    padding: 10px;
    box-shadow: 5px 10px;
	}
</style>
<div class="container">
	<h3 align="center">UPLOAD YOUR CSV ATTENDANCE FILE HERE!</h3>
	<br />
	<center>
		<form method="post" id="import_csv" enctype="multipart/form-data">
			<div class="form-group">
				<label>Select CSV File</label>
				<input type="file" name="csv_file" id="csv_file" required accept=".csv" />
			</div>
			<br />
			<button type="submit" name="import_csv" class="btn btn-info" id="import_csv_btn">Import CSV</button>
			<a href="<?php echo base_url(); ?>index.php/csv_import/view_csv" class="btn btn-info">View</a>
		</form>
	</center>
</div> 

<script>
	$(document).ready(function(){

		$('#import_csv').on('submit', function(event){
			event.preventDefault();
			$.ajax({
				url:"<?php echo base_url(); ?>index.php/csv_import/import",
				method:"POST",
				data:new FormData(this),
				contentType:false,
				cache:false,
				processData:false,
				beforeSend:function(){
					$('#import_csv_btn').html('Importing...');
				},
				success:function(data)
				{
					$('#import_csv')[0].reset();
					$('#import_csv_btn').attr('disabled', false);
					$('#import_csv_btn').html('Import Done');
					load_data();
				}
			})
		});
		
	});
</script>