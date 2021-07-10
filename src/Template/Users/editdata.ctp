<?php
use Cake\Routing\Router; 
?> 
<?= $this->Form->create($user,['class'=>"login-form",'id'=>"editempform"]) ?>

<?php
	echo $this->Form->hidden('id',['value'=>$user->id]); 
?>

<div class="modalBodyContent">
	<div class="successmsg"></div>
	<div class="row innerGap borderForSection"> 
		<div class="col-sm-6">
			<label>Employee Name</label> 
			<?php
				echo $this->Form->control('name', [
					'templates' => [
						'inputContainer' => '{{content}}'
					],
					'label' => false,
					'class' => 'custom-control',
					'placeholder' => 'Enter Name',
					'required'=>true
				]);
			?>
		</div>
		<!--div class="col-sm-6">
			<label>Supervisor Name</label> 
			<?php
				echo $this->Form->control('supervisor_name', [
					'templates' => [
						'inputContainer' => '{{content}}'
					],
					'label' => false,
					'class' => 'custom-control',
					'placeholder' => 'Enter Supervisor Name',
					'required'=>true
				]);
			?>
			
		</div-->
		<div class="col-sm-6">
			<label>Email</label>
			<?php
				echo $this->Form->control('email', [
					'templates' => [
						'inputContainer' => '{{content}}'
					],
					'label' => false,
					'class' => 'custom-control',
					'placeholder' => 'Enter Email',
					'required'=>true
				]);
			?>
		</div>
		<div class="col-sm-6">
			<label>Phone Number</label>
			<?php
				echo $this->Form->control('phone', [
					'templates' => [
						'inputContainer' => '{{content}}'
					],
					'label' => false,
					'class' => 'custom-control',
					'placeholder' => '#',
					'required'=>true
				]);
			?>
		</div>
		<div class="col-sm-6">
			<label>Address</label>
			<textarea class="custom-control" placeholder="address" name="address" required>
				<?= $user->address; ?>
			</textarea>
		</div>
		<div class="col-sm-6">
			<label>User Status</label> 
			<?php $status = array('1'=>'Active','2'=>'Inactive');?>
			<?= 
				$this->Form->select(
					"status",
					$status,
					['empty' => 'Select status','required'=>true,"class"=>"form-select search-gap"]
				);
			?> 
		</div>
		
		<div class="row mt-3">
			<h5 class="modal-title">Access Dates</h5>
		</div>
		
		<?php 
		if(!empty($usersAccessData)){
			foreach($usersAccessData as $key1=>$val1){
				 
				if($key1 == 0){
					$cloneAcCls = 'clonedataaccess'; 
				}else{
					$cloneAcCls = 'clonedataaccess'; 
				}
		?>
			<div class="row mt-3 <?= $cloneAcCls;?>">
				<div class="form-group col-md-5"> 
					<?php
						echo $this->Form->control('access_from[]', [
							'templates' => [
								'inputContainer' => '{{content}}'
							],
							'label' => false, 
							'type' => 'text',
							'id' => "access_from[".$key1."]",
							'name' => "access_from[".$key1."]",
							'autocomplete' => 'off',
							'class' => 'custom-control search-gap datepicker',
							'placeholder' => 'Access Date From',
							'required'=>false,
							'value'=>$val1['access_from']
						]);
					?>
				</div>
				<div class="form-group col-md-5"> 
					<?php
						echo $this->Form->control('access_to[]', [
							'templates' => [
								'inputContainer' => '{{content}}'
							],
							'label' => false, 
							'type' => 'text',
							'id' => "access_to[".$key1."]",
							'name' => "access_to[".$key1."]",
							'class' => 'custom-control search-gap datepicker',
							'placeholder' => 'Access Date To',
							'autocomplete' => 'off',
							'required'=>false,
							'value'=>$val1['access_to']
						]);
					?>
				</div>
				<div class="form-group col-md-2 tn-buttonsaccess">
					<?php if($key1 == 0){ ?>
					<?= $this->Form->button(__('Add'),['type'=>"button",'class'=>"btn btn-dark addmoreaccess"]) ?>
					
					<?php }else{ ?> 
						<button type='button' class='btn btn-danger removemore'>Remove</button>
					<?php } ?>
					
				</div>
			</div>
			
		
		<?php 
			}
		}else{  
		?>
		 
		<div class="row mt-3 clonedataacess">
			<div class="form-group col-md-5"> 
				<?php
					echo $this->Form->control('access_from[]', [
						'templates' => [
							'inputContainer' => '{{content}}'
						],
						'label' => false, 
						'type' => 'text',
						'id' => 'access_from[0]',
						'autocomplete' => 'off',
						'class' => 'custom-control search-gap datepicker',
						'placeholder' => 'Access Date From',
						'required'=>false
					]);
				?>
			</div>
			<div class="form-group col-md-5"> 
				<?php
					echo $this->Form->control('access_to[]', [
						'templates' => [
							'inputContainer' => '{{content}}'
						],
						'label' => false, 
						'type' => 'text',
						'id' => 'access_to[0]',
						'class' => 'custom-control search-gap datepicker',
						'placeholder' => 'Access Date To',
						'autocomplete' => 'off',
						'required'=>false
					]);
				?>
			</div>
			<div class="form-group col-md-2 tn-buttonsaccess">
				<?= $this->Form->button(__('Add'),['type'=>"button",'class'=>"btn btn-dark rounded-pill addmoreaccess"]) ?>
			</div>
		</div>
		
		<?php } ?>
		<div class="row">
			<div id="accessmore"> </div>
		</div>
		
	</div>
	<?php if(!empty($user->user_roles)){
			$totalCount = count($user->user_roles);
		foreach($user->user_roles as $key=>$urole){ 
	?>
		<div class="row innerGap align-items-center <?php if($key != ($totalCount-1)){echo 'pb-md-0';}?> clonedata">
			<div class="col-sm-6 "> 
				<select class="form-select search-gap" aria-label="Default select example" name="role_id[0]">
					<option value="">Select new role</option>
					<?php 
						foreach($roles as $role){
					?>
						<option value="<?= $role->id;?>" <?php if($role->id == $urole->role_id){echo 'selected';}?>><?= $role->name;?></option>
					<?php } ?> 
				</select> 
			</div>
			<div class="col-sm-2 tn-buttons">
				<?php if($key == 0){ ?>
					<button type="button" class="pe-3 ps-3 btn btn-dark rounded-pill addmore">Add</button>
				<?php } else{ ?>
					<button type='button' class='pe-3 ps-3 btn btn-danger rounded-pill removemore1' data-id="<?= $urole->id;?>">Remove</button>
				<?php } ?>
			</div>
			<?php if($key == 0){ ?>
			<div class="col-sm-4 text-right">
			  # roles assigned: <span class="fw-bold"><?= $totalCount; ?></span>
			</div>
			<?php } ?>
		</div>
		
	<?php }}else{ ?>
	
	<div class="row innerGap align-items-center clonedata">
		<div class="col-sm-6 "> 
			<select class="form-select search-gap" aria-label="Default select example" name="role_id[0]">
				<option value="" selected>Select new role</option>
				<?php 
					foreach($roles as $role){
				?>
					<option value="<?= $role->id;?>"><?= $role->name;?></option>
				<?php } ?> 
			</select> 
		</div>
		<div class="col-sm-2 tn-buttons">
			<button type="button" class="pe-3 ps-3 btn btn-dark rounded-pill addmore">Add</button>
		</div>
		<!--div class="col-sm-4 text-right">
		  # roles assigned: <span class="fw-bold">3</span>
		</div-->
	</div>
	<?php } ?>
	
	<div id="clonedataappend" class="innerGap pt-md-0">
		 
	</div>
	
	<div class="row innerGap">
		<div class="col-sm-12">
			<div class="table-responsive">
				<table class="table">
				  <thead>
					<tr>
					  <th scope="col">USER ROLE</th>
					  <th scope="col" class="text-center">BOILER</th>
					  <th scope="col" class="text-center">EXPELLER</th>
					  <th scope="col" class="text-center">WAY BRIDGE</th>
					  <th scope="col" class="text-center">OIL SECTION</th>
					  <th scope="col" class="text-center">MANAGE USER</th>
					  <th scope="col" class="text-center">MANAGE ROLE</th>
					  <th></th>
					</tr>
				  </thead>
				  <tbody>
					<?php 
						  if(!empty($permissionsData)){
							foreach($permissionsData as $key => $permissn){ 
							?>  
                          <tr>  
                            <td>
								<div>
								  <span class="AssignedUserRole"><?= $permissn->name;?></span>
								</div>
							</td>
						    <td class="text-center"> 
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
						</tr>
					<?php } } ?>
				 </tbody>
				</table>
			</div>
		</div>
	</div>
		  
</div>
<?= $this->Form->end() ?>
   
