<style>
    .img-thumb-path{
        width:100px;
        height:80px;
        object-fit:scale-down;
        object-position:center center;
    }
</style>
<div class="card card-outline card-primary rounded-0 shadow">
	<div class="card-header">
		<h3 class="card-title">List of Tests</h3>
		<div class="card-tools">
			<a href="javascript:void(0)" id="create_new" class="btn btn-flat btn-sm btn-primary"><span class="fas fa-plus"></span>  Add New Test</a>
		</div>
	</div>
	<div class="card-body">
		<div class="container-fluid">
        <div class="container-fluid">
			<table class="table table-bordered table-hover table-striped">
				<colgroup>
					<col width="5%">
					<col width="20%">
					<col width="25%">
					<col width="20%">
					<col width="15%">
					<col width="15%">
				</colgroup>
				<thead>
					<tr class="bg-gradient-primary text-light">
						<th>#</th>
						<th>Date Created</th>
						<th>Name</th>
						<th>Price</th>
						<th>Status</th>
						<th>Send SMS</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php 
						$i = 1;
						$qry = $conn->query("SELECT * from `test_list` where delete_flag = 0 order by `name` asc ");
						while($row = $qry->fetch_assoc()):
					?>
						<tr>
							<td class="text-center"><?php echo $i++; ?></td>
							<td class=""><?php echo date("Y-m-d H:i",strtotime($row['date_created'])) ?></td>
							<td class=""><p class="m-0 truncate-1"><?php echo $row['name'] ?></p></td>
							<td class=""><?= number_format($row['cost'],2) ?></td>
							<td class="text-center">
								<?php 
									switch ($row['status']){
										case 0:
											echo '<span class="rounded-pill badge badge-danger col-6">Inactive</span>';
											break;
										case 1:
											echo '<span class="rounded-pill badge badge-primary col-6">Active</span>';
											break;
									}
								?>
							</td>
							<?php
							$appointment_id = $row['appointment_id'];
							if(!empty($appointment_id)){
								$appointment = $conn->query("SELECT `client_id` from `appointment_list` where id={$appointment_id}")->fetch_assoc();
								$client = $conn->query("SELECT * from `client_list` where `id`=".$appointment['client_id']."")->fetch_assoc();
								$username = $client['firstname'].' '. $client['lastname'];
								$phone = $client['contact'];
								$email = $client['email'];
							}
							?>
							<td><button data-email="<?php echo $email; ?>" data-username="<?php echo $username; ?>" data-phone="<?php echo $phone; ?>" class="btn btn-primary send_sms">Send SMS</button></td>
							<td align="center">
								 <button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
				                  		Action
				                    <span class="sr-only">Toggle Dropdown</span>
				                  </button>
				                  <div class="dropdown-menu" role="menu">
								  	<a class="dropdown-item view_data" href="javascript:void(0)" data-id ="<?php echo $row['id'] ?>"><span class="fa fa-eye text-dark"></span> View</a>
				                    <div class="dropdown-divider"></div>
				                    <a class="dropdown-item edit_data" href="javascript:void(0)" data-appointment="<?php echo $row['appointment_id'];?>" data-id ="<?php echo $row['id'] ?>"><span class="fa fa-edit text-primary"></span> Edit</a>
				                    <div class="dropdown-divider"></div>
				                    <a class="dropdown-item delete_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><span class="fa fa-trash text-danger"></span> Delete</a>
				                  </div>
							</td>
						</tr>
					<?php endwhile; ?>
				</tbody>
			</table>
		</div>
		</div>
	</div>
</div>
<div class="modal fade" id="sendSMSModal" role='dialog'>
    <div class="modal-dialog modal-md modal-dialog-centered rounded-0" role="document">
      <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title">Send SMS</h5>
      </div>
	  <form id="send_sms_form" action="" method="post">
		<div class="modal-body">
			<div class="form-group">
				<input type="text" id="sms_name" name="sms_name" class="form-control">
			</div>
			<div class="form-group">
				<input type="text" id="sms_phone" name="sms_phone" class="form-control">
			</div>
			<div class="form-group">
				<input type="text" id="sms_email" name="sms_email" class="form-control">
			</div>
			<div class="form-group">
				<textarea id="sms_msg" class="form-control" name="sms_msg" placeholde="enter the message" cols="30" rows="10"></textarea>
			</div>
			
		</div>
		<div class="modal-footer">
			<button type="submit" class="btn btn-primary btn-flat" id='submit_send_sms_btn'>Send SMS</button>
			<button type="button" class="btn btn-secondary btn-flat" data-dismiss="modal">Close</button>
		</div>
		</form>
      </div>
    </div>
</div>
<script>
	$(document).ready(function(){
		$('.send_sms').click(function(){
			var name = $(this).data('username')
			var phone = $(this).data('phone')
			var email = $(this).data('email')
			$('#sms_name').val(name )
			$('#sms_phone').val(phone)
			$('#sms_email').val(email)
			$('#sendSMSModal').modal('show')

		})
		$('#submit_send_sms_btn').click(function(e){
			var name = $('#send_sms_form #sms_name').val();
			var phone = $('#send_sms_form #sms_phone').val();
			var email = $('#send_sms_form #sms_email').val();
			var msg = $('#send_sms_form #sms_msg').val();

			e.preventDefault();
			var $data = {
				"name": name,
				"contact": phone,  
				"email": email,  
				"message": msg,
			}
			$.ajax({
				url:_base_url_+"tests/sendSMS.php",
				type: "POST",
				data: $data,
				success: function(e){
					if(typeof resp== 'object' && resp.status == 'success'){
						location.reload();
					}else{
						alert_toast("An error occured.",'error');
						end_loader();
					}
					console.log(e)
				}
			});
		})
        $('#create_new').click(function(){
			uni_modal("Add New Test","tests/manage_test.php")
		})
		$('.view_data').click(function(){
			uni_modal("Test Details","tests/view_test.php?id="+$(this).attr('data-id'))
		})
        $('.edit_data').click(function(){
			var appointment = $(this).data('appointment');
			$('#appointment').val(appointment).trigger('change')
			uni_modal("Update Test Details","tests/manage_test.php?id="+$(this).attr('data-id'));
		})
		$('.delete_data').click(function(){
			_conf("Are you sure to delete this Test permanently?","delete_test",[$(this).attr('data-id')])
		})
		$('.table td, .table th').addClass('py-1 px-2 align-middle')
		$('.table').dataTable({
            columnDefs: [
                { orderable: false, targets: 5 }
            ],
        });
	})
	function delete_test($id){
		start_loader();
		$.ajax({
			url:_base_url_+"classes/Master.php?f=delete_test",
			method:"POST",
			data:{id: $id},
			dataType:"json",
			error:err=>{
				console.log(err)
				alert_toast("An error occured.",'error');
				end_loader();
			},
			success:function(resp){
				if(typeof resp== 'object' && resp.status == 'success'){
					location.reload();
				}else{
					alert_toast("An error occured.",'error');
					end_loader();
				}
			}
		})
	}
</script>