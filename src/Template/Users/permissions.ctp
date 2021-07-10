<?php
use Cake\Routing\Router;
?> 
<?= $this->element('navbar');?>

<div class="container-fluid">
	<div class="row"> 
        <div class="w-20 bg-white p-3 sideBarSticky sidenav" id="mySidenav">
			<?= $this->element('sidebar');?>
		</div> 
         
        <div class="w-80 pt-3">
          <div class="bg-white p-3 rounded">
            <div class="section-title">
              <h3>User roles permissions</h3>
            </div>
            <!-- Nav tabs -->
            <div class="">
				<?= $this->Form->create('',['class'=>"login-form",'id'=>"searchform"]) ?>
                <div class="row mt-2 mb-2 align-items-center pageHeaderStyle">
                  <div class="col-sm-6 p-0">
                    <h5 class="fw-bold m-0">FILTER</h5>
                  </div>
                  <div class="col-sm-6 p-0 text-right"> 
						<button  type="button" class="btn mes-btn" data-bs-toggle="modal" data-bs-target="#staticBackdrop">Add new user role</button> 
                  </div>
                </div>
                <div class="row"> 
					<div class="col-sm-4">
						<div class="row">
							<div class="col-sm-12 mb-2 filterTypeTitle">FILTER BY UNIT</div>
							<?php
							if(!empty($modules)){
								foreach($modules as $key=>$module) {
							?>
								
							  <div class="col-sm-6">
									<div class="mb-2 form-check">
										<input type="checkbox" class="form-check-input searchfilter" value="<?= $module; ?>" name="module_filter[]" id="<?= $module; ?>" <?php if(isset($savesearch['module_filer']) && in_array($module,$savesearch['module_filer'])){echo 'checked';}?>>
										<label class="form-check-label" for="<?= $module; ?>"><?= $module; ?></label>
									</div>
							  </div>
							<?php } } ?>
						   
						</div>
					</div>
					<div class="col-sm-4">
						<div class="row">
							<div class="col-sm-12 mb-2 filterTypeTitle">FILTER BY ACCESS</div>
							<div class="col-sm-6">
								<div class="mb-2 form-check">
								  <input type="checkbox" class="form-check-input searchfilter" id="Active" name="status_id[]" value="1" <?php if(isset($savesearch['status_id']) && in_array('1',$savesearch['status_id'])){echo 'checked';}?>>
								  <label class="form-check-label" for="Active">
									<img src="<?php echo Router::url('/', true);?>assets/show.svg">  
									View data
								  </label>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="mb-2 form-check">
								  <input type="checkbox" class="form-check-input searchfilter" id="OnLeave" name="status_id[]" value="2" <?php if(isset($savesearch['status_id']) && in_array('2',$savesearch['status_id'])){echo 'checked';}?>>
								  <label class="form-check-label" for="OnLeave">
									<img src="<?php echo Router::url('/', true);?>assets/edit-light-icon.svg"> 
									Edit data
								  </label>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="mb-2 form-check">
								  <input type="checkbox" class="form-check-input searchfilter" id="OnLeave" name="status_id[]" value="3"  <?php if(isset($savesearch['status_id']) && in_array('3',$savesearch['status_id'])){echo 'checked';}?>>
								  <label class="form-check-label" for="OnLeave">
									<img src="<?php echo Router::url('/', true);?>assets/delete-light-icon.svg"> 
									Delete data
								  </label>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="mb-2 form-check">
								  <input type="checkbox" class="form-check-input searchfilter" id="UpcomingLeave" name="status_id[]" value="4"  <?php if(isset($savesearch['status_id']) && in_array('4',$savesearch['status_id'])){echo 'checked';}?>>
								  <label class="form-check-label" for="UpcomingLeave">
									<img src="<?php echo Router::url('/', true);?>assets/addIcon.svg"> 
									Add data
								  </label>
								</div>
							</div>
						</div>
					</div> 
                </div>
				<?= $this->Form->end() ?>
				
                <div class="row">
                  <div class="col-sm-12">
                    <div class="table-responsive">
                      <table class="table table-striped">
                        <thead>
                          <tr>
                            <th>
                              <div class="tableInLineText" style="width: 160px;">Add new user role</div>
                            </th>
                            <th>
                              <div class="tableInLineText" style="width: 195px;">#EMPLOYEES ASSIGNED</div>
                            </th>
                            <th class="text-center"><div class="tHeadItemWidth">BOILER</div></th>
                            <th class="text-center"><div class="tHeadItemWidth">EXPELLER</div></th>
                            <th class="text-center"><div class="tHeadItemWidth">WAY BRIDGE</div></th>
                            <th class="text-center"><div class="tHeadItemWidth">OIL SECTION</div></th>
                            <th class="text-center"><div class="tHeadItemWidth">MANAGE USER</div></th>
                            <th class="text-center"><div class="tHeadItemWidth">MANAGE ROLE</div></th>
                            <th class="text-end"> </th>
                          </tr>
                        </thead>
                        <tbody>
						  <?php 
						  if(!empty($permissionsData)){
							foreach($permissionsData as $key => $permissn){  
							?>  
                          <tr>
                            <td>
                              <div class="fw-bold"><?= $permissn->name;?></div>
                            </td>
                            <td class="text-center">
                              <div class="d-flex justify-content-evenly">
                                <div>
                                  <?php echo $this->General->getUserCountByRole($permissn->id); ?>
                                </div>
                                <div>
									<a href="javascript:;" class="viewusr" id="<?= $permissn->id;?>">View</a>
									<?= $this->Form->create($user,['id'=>'usrfrm_'.$permissn->id,'action'=>"index"]) ?>
										<?php
											echo $this->Form->hidden('role_id_filter[]',['value'=>$permissn->id]); 
										?>
									<?= $this->Form->end() ?>
                                </div>
                              </div>
                            </td>
                            <td>
                              <div class="userRoleIcons">
								  <ul>
									<?php if(!empty($permissn->permissions[2]) && $permissn->permissions[2]->can_view == '1'){?>
										<li><img src="<?php echo Router::url('/', true);?>assets/show.svg"></li>
										<li class="v-line">
											<div class="line"></div>
										</li>
									<?php } ?>
									 
									<?php if(!empty($permissn->permissions[2]) && $permissn->permissions[2]->can_add == '1'){?>
									
										<li><img src="<?php echo Router::url('/', true);?>assets/addIcon.svg"></li>
										<li class="v-line">
											<div class="line"></div>
										</li>
									<?php } ?> 
									
									<?php if(!empty($permissn->permissions[2]) && $permissn->permissions[2]->can_edit == '1'){?>
										<li><img src="<?php echo Router::url('/', true);?>assets/edit-light-icon.svg"></li>
										<li class="v-line">
											<div class="line"></div>
										</li>
									<?php } ?> 
									
									<?php if(!empty($permissn->permissions[2]) && $permissn->permissions[2]->can_delete == '1'){?>
										<li><img src="<?php echo Router::url('/', true);?>assets/delete-light-icon.svg"></li>
									<?php }  ?>  
									
								  </ul>
                              </div>
                            </td>
                            <td class="text-center">
								<div class="userRoleIcons">
								  <ul>
									<?php if(!empty($permissn->permissions[3]) && $permissn->permissions[3]->can_view == '1'){?>
										<li><img src="<?php echo Router::url('/', true);?>assets/show.svg"></li>
										<li class="v-line">
											<div class="line"></div>
										</li>
									<?php } ?>
									 
									<?php if(!empty($permissn->permissions[3]) && $permissn->permissions[3]->can_add == '1'){?>
									
										<li><img src="<?php echo Router::url('/', true);?>assets/addIcon.svg"></li>
										<li class="v-line">
											<div class="line"></div>
										</li>
									<?php } ?> 
									
									<?php if(!empty($permissn->permissions[3]) && $permissn->permissions[3]->can_edit == '1'){?>
										<li><img src="<?php echo Router::url('/', true);?>assets/edit-light-icon.svg"></li>
										<li class="v-line">
											<div class="line"></div>
										</li>
									<?php } ?> 
									
									<?php if(!empty($permissn->permissions[3]) && $permissn->permissions[3]->can_delete == '1'){?>
										<li><img src="<?php echo Router::url('/', true);?>assets/delete-light-icon.svg"></li> 
									<?php } ?> 
									
								  </ul>
                              </div>
							</td>
                            <td class="text-center">
								<div class="userRoleIcons">
								  <ul>
									<?php if(!empty($permissn->permissions[4]) && $permissn->permissions[4]->can_view == '1'){?>
										<li><img src="<?php echo Router::url('/', true);?>assets/show.svg"></li>
										<li class="v-line">
											<div class="line"></div>
										</li>
									<?php } ?>
									 
									<?php if(!empty($permissn->permissions[4]) && $permissn->permissions[4]->can_add == '1'){?>
									
										<li><img src="<?php echo Router::url('/', true);?>assets/addIcon.svg"></li>
										<li class="v-line">
											<div class="line"></div>
										</li>
									<?php } ?> 
									
									<?php if(!empty($permissn->permissions[4]) && $permissn->permissions[4]->can_edit == '1'){?>
										<li><img src="<?php echo Router::url('/', true);?>assets/edit-light-icon.svg"></li>
										<li class="v-line">
											<div class="line"></div>
										</li>
									<?php } ?> 
									
									<?php if(!empty($permissn->permissions[4]) && $permissn->permissions[4]->can_delete == '1'){?>
										<li><img src="<?php echo Router::url('/', true);?>assets/delete-light-icon.svg"></li> 
									<?php } ?> 
									
								  </ul>
                              </div>
							</td>
                            <td class="text-center">
								<div class="userRoleIcons">
								  <ul>
									<?php if(!empty($permissn->permissions[5]) && $permissn->permissions[5]->can_view == '1'){?>
										<li><img src="<?php echo Router::url('/', true);?>assets/show.svg"></li>
										<li class="v-line">
											<div class="line"></div>
										</li>
									<?php } ?>
									 
									<?php if(!empty($permissn->permissions[5]) && $permissn->permissions[5]->can_add == '1'){?>
									
										<li><img src="<?php echo Router::url('/', true);?>assets/addIcon.svg"></li>
										<li class="v-line">
											<div class="line"></div>
										</li>
									<?php } ?> 
									
									<?php if(!empty($permissn->permissions[5]) && $permissn->permissions[5]->can_edit == '1'){?>
										<li><img src="<?php echo Router::url('/', true);?>assets/edit-light-icon.svg"></li>
										<li class="v-line">
											<div class="line"></div>
										</li>
									<?php } ?> 
									
									<?php if(!empty($permissn->permissions[5]) && $permissn->permissions[5]->can_delete == '1'){?>
										<li><img src="<?php echo Router::url('/', true);?>assets/delete-light-icon.svg"></li> 
									<?php } ?> 
									
								  </ul>
                              </div>
							</td>
                            <td class="text-center">
								<div class="userRoleIcons">
								  <ul>
									<?php if(!empty($permissn->permissions[0]) && $permissn->permissions[0]->can_view == '1'){?>
										<li><img src="<?php echo Router::url('/', true);?>assets/show.svg"></li>
										<li class="v-line">
											<div class="line"></div>
										</li>
									<?php } ?>
									 
									<?php if(!empty($permissn->permissions[0]) && $permissn->permissions[0]->can_add == '1'){?>
									
										<li><img src="<?php echo Router::url('/', true);?>assets/addIcon.svg"></li>
										<li class="v-line">
											<div class="line"></div>
										</li>
									<?php } ?> 
									
									<?php if(!empty($permissn->permissions[0]) && $permissn->permissions[0]->can_edit == '1'){?>
										<li><img src="<?php echo Router::url('/', true);?>assets/edit-light-icon.svg"></li>
										<li class="v-line">
											<div class="line"></div>
										</li>
									<?php } ?> 
									
									<?php if(!empty($permissn->permissions[0]) && $permissn->permissions[0]->can_delete == '1'){?>
										<li><img src="<?php echo Router::url('/', true);?>assets/delete-light-icon.svg"></li> 
									<?php } ?> 
									
								  </ul>
                              </div>
							</td>
                            <td class="text-center">
								<div class="userRoleIcons">
								  <ul>
									<?php if(!empty($permissn->permissions[1]) && $permissn->permissions[1]->can_view == '1'){?>
										<li><img src="<?php echo Router::url('/', true);?>assets/show.svg"></li>
										<li class="v-line">
											<div class="line"></div>
										</li>
									<?php } ?>
									 
									<?php if(!empty($permissn->permissions[1]) && $permissn->permissions[1]->can_add == '1'){?>
									
										<li><img src="<?php echo Router::url('/', true);?>assets/addIcon.svg"></li>
										<li class="v-line">
											<div class="line"></div>
										</li>
									<?php } ?> 
									
									<?php if(!empty($permissn->permissions[1]) && $permissn->permissions[1]->can_edit == '1'){?>
										<li><img src="<?php echo Router::url('/', true);?>assets/edit-light-icon.svg"></li>
										<li class="v-line">
											<div class="line"></div>
										</li>
									<?php } ?> 
									
									<?php if(!empty($permissn->permissions[1]) && $permissn->permissions[1]->can_delete == '1'){?>
										<li><img src="<?php echo Router::url('/', true);?>assets/delete-light-icon.svg"></li> 
									<?php } ?> 
									
								  </ul>
                              </div>
							</td>
                            <td class="text-end">
                              <div class="table-actions">
                                <a href="javascript:;" class="editemp" data-id="<?= $permissn->id;?>">
                                  <img src="<?php echo Router::url('/', true);?>assets/edit-light-icon.svg" alt="Edit">
                                </a>
                                <a href="<?php echo Router::url('/', true);?>/users/deletepermission/<?php echo $permissn->id;?>" onclick="return confirm('Are you sure want to delete this record?')">
                                  <img src="<?php echo Router::url('/', true);?>assets/delete-light-icon.svg" alt="Delete">
                                </a>
                              </div>
                            </td>
                          </tr>
						  <?php } } ?>
					  </tbody>
                      </table>
                    </div>
                  </div>
                </div> 
            </div>
            <!-- Nav tab End -->
          </div>
        </div> 
	</div>
</div> 

<!-- Start Add New Employee popup -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog customWidth">
	<div class="modal-content">
	  <div class="modal-header">
		<h5 class="modal-title" id="staticBackdropLabel">Add new role permission</h5>
		<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
	  </div>
	  <div class="modal-body">
		<?= $this->Form->create('',['class'=>"login-form",'id'=>"empform"]) ?>
		<div class="modalBodyContent">
		  <div class="row innerGap borderForSection">
			<div class="col-sm-6">
				<label>User role</label> 
				<!--select class="custom-control role-select" name="role" aria-label="Default select example"> 
				<?php
					if(!empty($rolelist))
					foreach($rolelist as $role) {
					?>
						<option value="<?= $role->id; ?>"><?= $role->name; ?></option>
					<?php
					}
				?>
				</select--> 
				<?php
					echo $this->Form->control('role', [
						'templates' => [
							'inputContainer' => '{{content}}'
						],
						'label' => false,
						'id' => 'role',
						'autocomplete' => 'off',
						'class' => 'custom-control',
						'placeholder' => 'Role',
						'required'=>true
					]);
				?> 
			</div>
		  </div>
		  <div class="row innerGap">
			<div class="col-sm-12">
			  <div class="table-responsive">
				<table class="table">
				  <thead>
					<tr>
					  <th scope="col">UNIT ACCESS</th>
					  <th scope="col" class="text-center">VIEW PERFORMANCE</th>
					  <th scope="col" class="text-center">ADD NEW DATA</th>
					  <th scope="col" class="text-center">EDIT DATA</th>
					  <th scope="col" class="text-center">DELETE DATA</th>
					  <!--th></th-->
					</tr>
				  </thead>
				  <tbody>
					<?php
					if(!empty($modules)){
						foreach($modules as $key=>$module) {
					?>
					<tr>
					  <td>
						<div class="fw-bold">
							<?= $module ;?>
							<input type="hidden" name="permission[<?= $key; ?>][module]" value="<?= $module ;?>">
						</div>
					  </td>
					  <td class="text-center"> 
						<div class="mb-2 form-check justify-content-center">
						  <input type="checkbox" class="form-check-input" id="ModalShow" name="permission[<?= $key; ?>][can_view]" value="1">
						  <label class="form-check-label" for="ModalShow">
							<img src="<?php echo Router::url('/', true);?>assets/show.svg">
						  </label>
						</div>
					  </td>
					  <td class="text-center"> 
						<div class="mb-2 form-check justify-content-center">
						  <input type="checkbox" class="form-check-input" id="ModalAdd" name="permission[<?= $key; ?>][can_add]" value="1">
						  <label class="form-check-label" for="ModalAdd">
							<img src="<?php echo Router::url('/', true);?>assets/addIcon.svg">
						  </label>
						</div>
					  </td>
					  <td class="text-center"> 
						<div class="mb-2 form-check justify-content-center">
						  <input type="checkbox" class="form-check-input" id="ModalEdit" name="permission[<?= $key; ?>][can_edit]" value="1">
						  <label class="form-check-label" for="ModalEdit">
							<img src="<?php echo Router::url('/', true);?>assets/edit-light-icon.svg">
						  </label>
						</div>
					  </td>
					  <td class="text-center"> 
						<div class="mb-2 form-check justify-content-center">
						  <input type="checkbox" class="form-check-input" id="ModalDelete" name="permission[<?= $key; ?>][can_delete]" value="1">
						  <label class="form-check-label" for="ModalDelete">
							<img src="<?php echo Router::url('/', true);?>assets/delete-light-icon.svg">
						  </label>
						</div>
					  </td>
					  <!--td>
						<div class="table-actions">
						  <a href="#">
							<img src="<?php echo Router::url('/', true);?>assets/delete-light-icon.svg" alt="Delete">
						  </a>
						</div>
					  </td-->
					</tr>
					
					<?php 
						}
					}
					?> 
				  </tbody>
				</table>
			  </div>
			</div>
		  </div>
		</div>
		<?= $this->Form->end() ?>
	  </div>
	  <div class="modal-footer">
		<button type="button" class="btn btn-outline-dark rounded-pill" data-bs-dismiss="modal">Cancel</button>
		<button type="button" class="btn btn-dark rounded-pill addPermission">SAVE</button>
	  </div>
	</div>
  </div>
</div>
<!-- End Add New Employee popup -->


<!-- Start Edit New Employee popup -->
<div class="modal fade" id="staticBackdrop1" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
	<div class="modal-dialog customWidth">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="staticBackdropLabel">Edit role permissions</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			
			<div class="modal-body employeeform">&nbsp;</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-outline-dark rounded-pill" data-bs-dismiss="modal">Cancel</button>
				<button type="button" class="btn btn-dark rounded-pill editpermission">SAVE</button>
			</div> 
		</div>
	</div>
</div>
<!-- End Edit New Employee popup -->


<?= $this->Html->script(['jquery-1.12.4.js']) ?> 
<script>  
$(document).on('click', '.addPermission', function () {
	$(this).text('Saving...');
	$(this).prop('disabled', true);
	
	var url 	= '<?php echo Router::url('/', true).'users/permissionadd';?>';
	 
	$.ajax({
		type:'POST',
		dataType: 'json',
		data: $('#empform').serialize(),
		url:url,
		success:function(res) {
			if(res.status == '200'){
				location.reload();
			}else{
				var msg = '<div class="message error" onclick="this.classList.add(\'hidden\')">' + res.msg + '</div>';
				$('.successmsg').html(msg);
				
				$('.addPermission').text('SAVE');
				$('.addPermission').prop('disabled', false);
					
			} 
		}
	}); 
	 
});


$(document).on('click', '.editemp', function () {
	 
	var id = $(this).attr('data-id');
	var url 	= '<?php echo Router::url('/', true).'users/editpermission';?>';
	var csrfToken = $("[name='_csrfToken']").val(); 
	if(id != ''){
		$.ajax({
			type:'POST',
			dataType: 'html',
			data: {id:id,_csrfToken:csrfToken},
			url:url,
			success:function(res) {
				$('.employeeform').html(res);
				
				$('#staticBackdrop1').modal('show');
			}
		}); 
	} 
	 
});

$(document).on('click', '.editpermission', function () {
	$(this).text('Saving...');
	$(this).prop('disabled', true);
	
	var url 	= '<?php echo Router::url('/', true).'users/permissionadd';?>';
	 
	$.ajax({
		type:'POST',
		dataType: 'json',
		data: $('#permissionform').serialize(),
		url:url,
		success:function(res) {
			if(res.status == '200'){
				location.reload();
			}else{
				var msg = '<div class="message error" onclick="this.classList.add(\'hidden\')">' + res.msg + '</div>';
				$('.successmsg').html(msg);
				
				$('.editpermission').text('SAVE');
				$('.editpermission').prop('disabled', false);
					
			} 
		}
	}); 
	 
});

$('.searchfilter').click(function() {
	
	$('#searchform').submit();
	 
});


$('.viewusr').click(function() {
	var id = $(this).attr('id');
	console.log(id);
	if(id != ''){
		$('#usrfrm_'+id).submit();
	}
	 
});

</script> 
