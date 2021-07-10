<?php
use Cake\Routing\Router; 
?> 
<?= $this->Form->create('',['class'=>"login-form",'id'=>"permissionform"]) ?>
<div class="modalBodyContent">
  <div class="row innerGap borderForSection">
	<div class="col-sm-6">
		<label>User role</label> 
		<!--select class="custom-control role-select" name="role" aria-label="Default select example"> 
		<?php
			if(!empty($rolelist))
			foreach($rolelist as $role) {
			?>
				<option value="<?= $role->id; ?>" <?php if(isset($permissionsData->id) && $permissionsData->id == $role->id){echo 'selected';}?>><?= $role->name; ?></option>
			<?php
			}
		?>
		</select-->
		<input type="hidden" name="added_role" value="<?= $permissionsData->id; ?>">
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
				'value'=>$permissionsData->name,
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
				  <input type="checkbox" class="form-check-input" id="ModalShow" name="permission[<?= $key; ?>][can_view]" value="1" <?php if(!empty($permissionsData->permissions[$key]) && $permissionsData->permissions[$key]->can_view == '1'){ echo 'checked';}?>>
				  <label class="form-check-label" for="ModalShow">
					<img src="<?php echo Router::url('/', true);?>assets/show.svg">
				  </label>
				</div>
			  </td>
			  <td class="text-center"> 
				<div class="mb-2 form-check justify-content-center">
				  <input type="checkbox" class="form-check-input" id="ModalAdd" name="permission[<?= $key; ?>][can_add]" value="1" <?php if(!empty($permissionsData->permissions[$key]) && $permissionsData->permissions[$key]->can_add == '1'){ echo 'checked';}?>>
				  <label class="form-check-label" for="ModalAdd">
					<img src="<?php echo Router::url('/', true);?>assets/addIcon.svg">
				  </label>
				</div>
			  </td>
			  <td class="text-center"> 
				<div class="mb-2 form-check justify-content-center">
				  <input type="checkbox" class="form-check-input" id="ModalEdit" name="permission[<?= $key; ?>][can_edit]" value="1" <?php if(!empty($permissionsData->permissions[$key]) && $permissionsData->permissions[$key]->can_edit == '1'){ echo 'checked';}?>>
				  <label class="form-check-label" for="ModalEdit">
					<img src="<?php echo Router::url('/', true);?>assets/edit-light-icon.svg">
				  </label>
				</div>
			  </td>
			  <td class="text-center"> 
				<div class="mb-2 form-check justify-content-center">
				  <input type="checkbox" class="form-check-input" id="ModalDelete" name="permission[<?= $key; ?>][can_delete]" value="1" <?php if(!empty($permissionsData->permissions[$key]) && $permissionsData->permissions[$key]->can_delete == '1'){ echo 'checked';}?>>
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
   
