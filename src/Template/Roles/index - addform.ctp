<?php
use Cake\Routing\Router;
$session = $this->request->getSession(); 
$authUser = $session->read('Auth');  
?> 
<?= $this->element('navbar');?>
<div class="container-fluid">
	<div class="row mt-3">
	
        <div class="w-20">
			<div class="bg-white p-3 rounded">
				<?= $this->element('sidebar');?> 
			</div>
        </div>
        <div class="w-80">
			<div>
				<h3>Roles</h3>
			</div>
			
			<?php if(($authUser['User']['id'] == 1) || (!empty($roleModulePermission) && $roleModulePermission->can_add == 1)){ ?>
			
			
			
			<div class="bg-white p-3 rounded"> 
				<?= $this->Form->create('',['action'=>'add']) ?>
				  <div class="form-custom">
					<div class="mb-3 flex-1 me-3"> 
					  <?php
						echo $this->Form->control('name', [
							'templates' => [
								'inputContainer' => '{{content}}'
							],
							'label' => false,
							'class' => 'form-control',
							'placeholder' => 'Name',
							'required'=>true
						]);
						?> 
					</div>
					 
					<div class="mb-3 flex-1">
						<?php $status = array('1'=>'Active','2'=>'Inactive');?>
						<?= 
							$this->Form->select(
								"status",
								$status,
								['empty' => 'Select status','required'=>true,"class"=>"form-select"]
							);
						?> 
					</div>
				  </div>
				  <div class="form-btn">
					<button type="submit" class="btn btn-dark">Add Role</button>
				  </div>
				<?= $this->Form->end() ?>
			</div>
			<?php } ?>
			
			<div class="bg-white p-3 rounded mt-3">
				<div class="table-responsive">
					<table class="table">
						<thead>
							<tr>
								<th><?= $this->Paginator->sort('id') ?></th>
								<th><?= $this->Paginator->sort('name') ?></th> 
								<th><?= $this->Paginator->sort('status') ?></th> 
								<th><?= $this->Paginator->sort('created') ?></th>
								<th><?= $this->Paginator->sort('modified') ?></th> 
								<th class="text-end"><?= __('Actions') ?></th>
							</tr>
						</thead>
						<tbody>
							<?php 
							if(!empty($roles)){
							foreach ($roles as $role): 
							?>
							<tr>
								<td><?= $this->Number->format($role->id) ?></td>
								<td><?= h($role->name) ?></td> 
								 
								<td><?php if($role->status == 1){echo 'Active';}else{echo 'Inactive';} ?></td> 
								<td><?= h($role->created) ?></td>
								<td><?= h($role->modified) ?></td>
								 
								<td class="text-end">
								  <div class="table-actions">
									<?php if(($authUser['User']['id'] == 1) || (!empty($roleModulePermission) && $roleModulePermission->can_edit == 1)){ ?>  
									<?= 
										$this->Html->link(
											$this->Html->image(Router::url('/', true).'assets/edit-light-icon.svg'),
											array(
												'controller' => 'Roles', 
												'action' => 'edit',$role->id
											), array('escape' => false)
										);
									?>
									
									<?php } ?>
									
									<?php if(($authUser['User']['id'] == 1) || (!empty($roleModulePermission) && $roleModulePermission->can_delete == 1)){ ?>
									
									<?= 
										$this->Html->link(
											$this->Html->image(Router::url('/', true).'assets/delete-light-icon.svg'),
											array(
												'controller' => 'Roles', 
												'action' => 'delete',$role->id
											), array('escape' => false,'confirm' => __('Are you sure you want to delete # {0}?', $role->id))
										);
									?> 
									<?php } ?>
								  </div>
								</td>
							</tr>
							<?php endforeach; ?>
							<?php } ?>					
						</tbody>
					</table>
					<div class="paginator">
						<ul class="pagination">
							<?= $this->Paginator->first('<< ' . __('first')) ?>
							<?= $this->Paginator->prev('< ' . __('previous')) ?>
							<?= $this->Paginator->numbers() ?>
							<?= $this->Paginator->next(__('next') . ' >') ?>
							<?= $this->Paginator->last(__('last') . ' >>') ?>
						</ul>
						<p><?= $this->Paginator->counter(['format' => __('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')]) ?></p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>